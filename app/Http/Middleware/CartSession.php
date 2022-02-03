<?php

namespace App\Http\Middleware;

use App\Cart;
use Closure;
use Illuminate\Support\Facades\Session;


class CartSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!Session::has('cart')){
            $cart = Cart::create();
            Session::put('cart', $cart->id);
        }
        return $next($request);
    }
}
