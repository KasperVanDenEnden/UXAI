<?php

namespace App\Actions;

use App\Concerns\Action;

class BadModelAction implements Action
{
    public function execute(): void
    {
        (new StoreBadDataAction)->execute();

        // @todo api service to call bad prediction method
    }
}
