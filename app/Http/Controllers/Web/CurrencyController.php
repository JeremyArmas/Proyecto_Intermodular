<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    /**
     * Cambia la moneda de la sesión.
     */
    public function switch($code)
    {
        $supported = ['EUR', 'USD', 'GBP'];
        
        if (in_array(strtoupper($code), $supported)) {
            session(['currency' => strtoupper($code)]);
        }

        return redirect()->back();
    }
}
