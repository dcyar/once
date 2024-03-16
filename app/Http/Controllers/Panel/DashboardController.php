<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Fise;

class DashboardController extends Controller {
    public function index() {
        return view('dashboard', [
            'last_fises'      => Fise::with('client:id,name')->latest()->take(10)->get(),
            'available_fises' => Fise::whereNull('used_at')->count(),
        ]);
    }
}
