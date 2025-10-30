<?php

namespace App\Http\Controllers;

use App\Services\PredictionApiService;
use Illuminate\View\View;

class InfoController extends Controller
{
    public function __invoke(): View
    {
        $api = new PredictionApiService;
        $apiHealth = $api->healthCheck()->json();

        return view('info', ['apiHealth' => $apiHealth]);
    }
}
