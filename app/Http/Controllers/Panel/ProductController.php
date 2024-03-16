<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller {
    public function index() {
        return view('panel.products.index');
    }

    public function ajax(Request $request) {
        $page             = $request->integer('start') / $request->integer('length') + 1;
        $perPage          = $request->integer('length');
        $search           = $request->input('search.value');
        $orderColumnIndex = $request->integer('order.0.column');
        $orderColumn      = $request->input("columns.{$orderColumnIndex}.data");
        $orderDirection   = $request->input('order.0.dir', 'desc');

        $paginator = Product::query()
            ->select(['id', 'name', 'description', 'price', 'stock'])
            ->when($search, function ($query, $search) {
                $query->whereAny(['name', 'description'], 'LIKE', "%{$search}%");
            })
            ->orderBy($orderColumn, $orderDirection)
            ->paginate(perPage: $perPage, page: $page);

        return response()->json([
            'draw'            => $request->integer('draw'),
            'recordsTotal'    => $paginator->total(),
            'recordsFiltered' => $paginator->total(),
            'data'            => array_values($paginator->items()),
        ]);
    }

    public function store(ProductRequest $request) {
        Product::create($request->validated());

        return response()->json([
            'message' => 'El producto ha sido creado correctamente.',
        ]);
    }

    public function update(ProductRequest $request, Product $product) {
        $product->update($request->validated());

        return response()->json([
            'message' => 'El producto ha sido actualizado correctamente.',
        ]);
    }

    public function destroy(Product $product) {
        $product->delete();

        return response()->json([
            'message' => 'El producto ha sido eliminado correctamente.',
        ]);
    }
}
