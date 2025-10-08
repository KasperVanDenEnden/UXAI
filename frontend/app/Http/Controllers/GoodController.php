<?php

namespace App\Http\Controllers;

use App\Actions\GoodModelAction;
use App\Http\Requests\GoodRequest;
use Illuminate\View\View;

class GoodController extends Controller
{
    public function index(): View
    {
        return view('good.index');
    }

    public function create(): View
    {
        return view('good.create');
    }

    public function store(GoodRequest $request): View
    {
        $result = (new GoodModelAction)->execute($request);

        return view('good.show', ['result' => $result]);
    }
}
