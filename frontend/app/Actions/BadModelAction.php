<?php

namespace App\Actions;

use App\Concerns\Action;
use App\Http\Requests\BadRequest;

class BadModelAction implements Action
{
    public function execute(BadRequest $request): void
    {
        (new StoreBadDataAction)->execute();

        // @todo api service to call bad prediction method
    }
}
