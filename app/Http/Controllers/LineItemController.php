<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use \App\LineItem;

class LineItemController extends Controller
{
    /**
     * カートに商品を追加する
     */
    public function create(Request $request)
    {
        //セッションの取得
        $cart_id = Session::get('cart');

        //追加した商品が既にカートに入っているかを確認
        $line_item = LineItem::where('cart_id', $cart_id)->where('product_id',$request->input('id'))->first();

        //すでにカートに入れている商品を追加した場合の処理
        if($line_item){
            $line_item->quantity += $request->input('quantity');
            $line_item->save();
        } else {  //追加した商品が新規の場合
            LineItem::create([
                'cart_id' => $cart_id,
                'product_id' => $request->input('id'),
                'quantity' => $request->input('quantity'),
            ]);
        }

        return redirect(route('cart.index'));

    }

    /**
     * カート中身を削除する
     */

    public function delete(Request $request)
    {
        LineItem::destroy($request->input('id'));

        return redirect(route('cart.index'));
    }
}
