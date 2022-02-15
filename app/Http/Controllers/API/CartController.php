<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $request->session()->put('cart-items', $request->cart_items);

        return response(null, 201);
    }

    public function get(Request $request)
    {
        return response($request->session()->get('cart-items', []));
    }
}
