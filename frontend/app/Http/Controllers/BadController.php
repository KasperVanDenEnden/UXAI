<?php

namespace App\Http\Controllers;

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
        return view('bad.show');
    }
}
