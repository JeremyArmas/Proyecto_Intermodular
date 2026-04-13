<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmedMail;

class CheckoutController extends Controller
{
    /**
     * Crea la sesión de Stripe Checkout y redirige al usuario al pago.
     */
    public function createSession(Request $request)
    {
        $user = auth()->user();
        $cart = Cart::where('user_id', $user->id)->first();

        // Verifica que el carrito no esté vacío
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('carrito.index')->with('error', 'Tu carrito está vacío.');
        }

        // Configura la clave secreta de Stripe
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Construye los line_items para Stripe
        $lineItems = [];
        foreach ($cart->items as $item) {
            $game = $item->game;
            
            // Stripe espera el precio en céntimos
            $priceInCents = (int) ($game->getPriceForUser($user) * 100);

            // Prepara la imagen absoluta si existe
            $images = [];
            
            if ($game->cover_image) {
                // En local con .test probablemente no cargue la imagen en la página de Stripe real,
                // pero si la URL es púbica sí que la cargaría. Para el proyecto está bien.
                $images[] = url('storage/' . $game->cover_image); 
            }

            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $game->title,
                        'images' => $images,
                    ],
                    'unit_amount' => $priceInCents,
                ],
                'quantity' => $item->quantity,
            ];
        }

        try {
            // Crea la Checkout Session de Stripe
            $checkoutSession = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'shipping_address_collection' => [
                    'allowed_countries' => ['ES', 'US', 'GB', 'FR', 'DE', 'IT', 'PT'],
                ],
                'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('checkout.cancel'),
                'customer_email' => $user->email, // Rellena el email automáticamente en Stripe
            ]);

            // Redirige al usuario a la página de pago de Stripe
            return redirect()->away($checkoutSession->url);

        } catch (\Exception $e) {
            Log::error('Error creando Stripe Session: ' . $e->getMessage());
            return redirect()->route('carrito.index')->with('error', 'Ocurrió un error al procesar el pago. Inténtalo más tarde.');
        }
    }

    /**
     * Página de éxito a la que vuelve el usuario desde Stripe.
     */
    public function success(Request $request)
    {
        $sessionId = $request->query('session_id');

        if (!$sessionId) {
            return redirect()->route('home');
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            // Recuperamos la sesión de Stripe
            $session = StripeSession::retrieve($sessionId);

            // Verificamos si realmente está pagado
            if ($session->payment_status !== 'paid') {
                return redirect()->route('carrito.index')->with('error', 'El pago no se ha completado.');
            }

            // Verificamos que este order no exista ya (para evitar duplicados si recargan la página)
            $existingOrder = Order::where('stripe_session_id', $sessionId)->first();
            if ($existingOrder) {
                // Si la orden ya se procesó, simplemente mostramos la vista de éxito
                return view('checkout.success', ['order' => $existingOrder]);
            }

            // Si llegamos aquí: El pago se completó y no existe el order. Procedemos a crearlo.
            $user = auth()->user();
            $cart = Cart::where('user_id', $user->id)->first();

            // Si por alguna razón el carrito ya está vacío, buscamos la orden por sesión
            if (!$cart || $cart->items->isEmpty()) {
                return redirect()->route('home');
            }

            // Calculamos total
            $totalAmount = 0;
            foreach ($cart->items as $item) {
                $totalAmount += $item->game->getPriceForUser($user) * $item->quantity;
            }

            // Extrae la dirección de envío de la sesión de Stripe
            $shippingAddress = 'Dirección no proporcionada';
            
            // Stripe puede guardarla en shipping_details o en customer_details
            $addr = null;
            if (!empty($session->shipping_details->address)) {
                $addr = $session->shipping_details->address;
            } elseif (!empty($session->customer_details->address)) {
                $addr = $session->customer_details->address;
            }

            if ($addr) {
                $parts = array_filter([
                    $addr->line1 ?? null,
                    $addr->line2 ?? null,
                    trim(($addr->postal_code ?? '') . ' ' . ($addr->city ?? '')),
                    $addr->state ?? null,
                    $addr->country ?? null
                ]);
                $shippingAddress = implode(', ', array_filter($parts));
            } elseif (isset($user->address) && !empty($user->address)) {
                $shippingAddress = $user->address;
            }

            // 1. Crear el Order
            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'paid', // Ya verificado
                'total_amount' => $totalAmount,
                'shipping_address' => $shippingAddress, 
                'order_type' => $user->isCompany() ? 'b2b' : 'b2c',
                'stripe_session_id' => $sessionId,
            ]);

            // 2. Crear los OrderItems y actualizar Stock
            foreach ($cart->items as $item) {
                $game = $item->game;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'game_id' => $game->id,
                    'quantity' => $item->quantity,
                    'price_at_purchase' => $game->getPriceForUser($user),
                ]);

                // Descontar stock
                $game->decrement('stock', $item->quantity);
            }

            // 3. Vaciar el Carrito
            $cart->items()->delete();

            // 4. Enviar correo de confirmación
            try {
                Mail::to($user->email)->send(new OrderConfirmedMail($order));
            } catch (\Exception $e) {
                Log::error('Error enviando correo de confirmación de pedido: ' . $e->getMessage());
                // El error de correo no debe evitar que se muestre el éxito de la compra
            }

            // Retornamos la vista de éxito con la orden que acabamos de crear
            return view('checkout.success', ['order' => $order]);

        } catch (\Exception $e) {
            Log::error('Error verificando pago en Stripe: ' . $e->getMessage());
            return redirect()->route('carrito.index')->with('error', 'No se pudo verificar el pago.');
        }
    }

    /**
     * Página de cancelación si el usuario se echa atrás en la pasarela de pago.
     */
    public function cancel()
    {
        // Simple redirección al carrito con un mensaje
        return view('checkout.cancel');
    }
}
