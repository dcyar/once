<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\Client\ClientStoreRequest;
use App\Http\Requests\Panel\Client\ClientUpdateRequest;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller {
    public function index() {
        return view('panel.clients.index');
    }

    public function ajax(Request $request) {
        $page             = $request->integer('start') / $request->integer('length') + 1;
        $perPage          = $request->integer('length');
        $search           = $request->input('search.value');
        $orderColumnIndex = $request->integer('order.0.column');
        $orderColumn      = $request->input("columns.{$orderColumnIndex}.data");
        $orderDirection   = $request->input('order.0.dir', 'desc');

        $paginator = Client::query()
            ->select(['id', 'name', 'dni', 'address', 'phone'])
            ->withCount(['fises', 'active_fises', 'used_fises'])
            ->when($search, function ($query, $search) {
                $query->whereAny(['name', 'dni'], 'LIKE', "%{$search}%");
            })
            ->orderBy($orderColumn, $orderDirection)
            ->paginate(perPage: $perPage, page: $page);

        return response()->json([
            'draw'            => $request->integer('draw'),
            'recordsTotal'    => $paginator->total(),
            'recordsFiltered' => $paginator->total(),
            'data'            => $paginator->items(),
        ]);
    }

    public function ajaxSelect(Request $request) {
        $search = $request->input('q', '');

        $clients = Client::query()
            ->select(['id', 'name as text'])
            ->whereAny(['name', 'dni'], 'LIKE', "%{$search}%")
            ->get();

        return response()->json([
            'results' => $clients,
        ]);
    }

    public function store(ClientStoreRequest $request) {
        Client::create($request->validated());

        return response()->json([
            'message' => 'El cliente ha sido creado correctamente.',
        ]);
    }

    public function update(ClientUpdateRequest $request, Client $client) {
        $client->update($request->validated());

        return response()->json([
            'message' => 'El cliente ha sido actualizado correctamente.',
        ]);
    }

    public function destroy(Client $client) {
        $client->delete();

        return response()->json([
            'message' => 'El cliente ha sido eliminado correctamente.',
        ]);
    }
}
