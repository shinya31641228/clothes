<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * 商品一覧を表示する
     */
    public function index()
    {
        return view('product.index',[
            'products' => Product::get()
        ]);
    }

    /**
     * 商品詳細を表示する
     */
    public function show($id)
    {
        return view('product.show',[
            'product' => Product::find($id)
        ]);
    }


}
