<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use \App\Cart;
use App\LineItem;

class CartController extends Controller
{
    /**
     * カートの中身を表示する
     */
    public function index()
    {
        $cart_id = Session::get('cart');
        $cart = Cart::find($cart_id);

        $total_price = 0;
        foreach($cart->products as $product){
            $total_price += $product->price * $product->pivot->quantity;
        }

        return view('cart.index',[
            'line_items' => $cart->products,
            'total_price' => $total_price,
        ]);
    }

    /**
     *  決算処理する
     */

    public function checkout()
    {
        //セッションからカートの情報を取得
        $cart_id = Session::get('cart');
        $cart = Cart::find($cart_id);

        //カートに商品がなかったらカート画面へリダイレクト
        if(count($cart->products) <= 0){
            return redirect(route('cart.index'));
        }

        //購入商品のセット
        $line_items = [];
        foreach ($cart->products as $product) {
            $line_item = [
                'name'        => $product->name,
                'description' => $product->description,
                'amount'      => $product->price,
                'currency'    => 'jpy',
                'quantity'    => $product->pivot->quantity,
            ];
            array_push($line_items, $line_item);
        }

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items'           => [$line_items],
            'success_url'          => route('cart.success'),
            'cancel_url'           => route('cart.index'),
        ]);

        return view('cart.checkout', [
            'session' => $session,
            'publicKey' => env('STRIPE_PUBLIC_KEY')
        ]);
    }

    /**
     * 決算処理成功時
     */

    public function success()
    {
        //セッションからカートの情報を取得
        $cart_id = Session::get('cart');
        LineItem::where('cart_id', $cart_id)->delete();

        return redirect(route('product.index'));
    }
}
