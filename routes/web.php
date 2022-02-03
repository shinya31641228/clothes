<?php

use App\Product;

Route::get('/products', 'ProductController@index')->name('product.index');
