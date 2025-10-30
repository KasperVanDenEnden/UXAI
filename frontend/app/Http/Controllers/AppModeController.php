<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppModeController extends Controller
{
    public function toggle(Request $request)
    {
        $currentMode = $request->cookie('app_mode', 'good');

        $newMode = $currentMode === 'good' ? 'bad' : 'good';

        return to_route($newMode.'.index')
            ->cookie('app_mode', $newMode, 60 * 24 * 365);
    }
}
