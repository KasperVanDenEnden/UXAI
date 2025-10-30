<?php

namespace App\Actions;

use App\Concerns\Action;
use App\Http\Requests\BadRequest;
use App\Models\BadInfo;
use Illuminate\Support\Facades\DB;

class BadModelAction implements Action
{
    public function execute(BadRequest $request): void
    {
        DB::transaction(function () use ($request) {
            BadInfo::create($request->validated());
        });
        // @todo api service to call bad prediction method
    }
}
