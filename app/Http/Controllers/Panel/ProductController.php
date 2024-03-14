<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('panel.products.index', [
            'products' => Product::all(),
        ]);
    }

    public function edit(Request $request, Product $product)
    {
        
    }
}
