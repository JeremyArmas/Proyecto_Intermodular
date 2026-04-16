<?php

namespace App\Services;

class CurrencyService
{
    /**
     * Tasas de cambio fijas respecto al Euro (EUR).
     */
    protected static $rates = [
        'EUR' => 1.0,
        'USD' => 1.08,
        'GBP' => 0.86,
    ];

    /**
     * Símbolos de moneda.
     */
    protected static $symbols = [
        'EUR' => '€',
        'USD' => '$',
        'GBP' => '£',
    ];

    /**
     * Obtiene la moneda actual de la sesión.
     */
    public static function getCurrent()
    {
        return session('currency', 'EUR');
    }

    /**
     * Obtiene el símbolo de la moneda actual.
     */
    public static function getSymbol()
    {
        return self::$symbols[self::getCurrent()] ?? '€';
    }

    /**
     * Convierte un precio de EUR a la moneda actual.
     */
    public static function convert($amount)
    {
        $rate = self::$rates[self::getCurrent()] ?? 1.0;
        return $amount * $rate;
    }

    /**
     * Convierte un precio de la moneda actual de vuelta a EUR.
     */
    public static function convertToEur($amount)
    {
        $rate = self::$rates[self::getCurrent()] ?? 1.0;
        return $amount / $rate;
    }

    /**
     * Formatea un precio para mostrarlo en la vista.
     */
    public static function format($amount)
    {
        $converted = self::convert($amount);
        $symbol = self::getSymbol();
        
        // Formato: 0.00 $ o £ 0.00 según sea común, 
        // pero para simplificar usaremos: 0,00 [Símbolo]
        return number_format($converted, 2, ',', '.') . ' ' . $symbol;
    }

    /**
     * Obtiene todas las monedas soportadas.
     */
    public static function getAll()
    {
        return ['EUR', 'USD', 'GBP'];
    }
}
