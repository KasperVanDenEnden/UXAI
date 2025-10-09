<?php

namespace App\Http\Controllers;

use App\Actions\BadModelAction;
use App\Http\Requests\BadRequest;
use Illuminate\View\View;

class BadController extends Controller
{
    public function index(): View
    {
        return view('bad.index');
    }

    public function create(): View
    {
        return view('bad.create');
    }

    public function store(BadRequest $request): View
    {
        app(BadModelAction::class)->execute($request);

        return view('bad.show');
    }
}
