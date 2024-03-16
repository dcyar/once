<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\Fise\FiseStoreRequest;
use App\Http\Requests\Panel\Fise\FiseUpdateRequest;
use App\Models\Fise;
use Illuminate\Http\Request;

class FiseController extends Controller {
    public function index() {
        return view('panel.fises.index');
    }

    public function ajax(Request $request) {
        $page             = $request->integer('start') / $request->integer('length') + 1;
        $perPage          = $request->integer('length');
        $search           = $request->input('search.value');
        $orderColumnIndex = $request->integer('order.0.column');
        $orderColumn      = $request->input("columns.{$orderColumnIndex}.data");
        $orderDirection   = $request->input('order.0.dir', 'desc');

        $paginator = Fise::query()
            ->with('client')
            ->select(['id', 'code', 'client_id', 'amount', 'expiration_date', 'is_active'])
            ->when($search, function ($query, $search) {
                $query
                    ->whereAny(['code', 'expiration_date'], 'LIKE', "%{$search}%")
                    ->orWhereHas('client', function ($query) use ($search) {
                        $query->whereAny(['name', 'dni'], 'LIKE', "%{$search}%");
                    });
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

    public function store(FiseStoreRequest $request) {
        Fise::create($request->validated());

        return response()->json([
            'message' => 'El fise ha sido creado correctamente.',
        ]);
    }

    public function update(FiseUpdateRequest $request, Fise $fise) {
        $fise->update($request->validated());

        return response()->json([
            'message' => 'El fise ha sido actualizado correctamente.',
        ]);
    }

    public function destroy(Fise $fise) {
        $fise->delete();

        return response()->json([
            'message' => 'El fise ha sido eliminado correctamente.',
        ]);
    }
}
