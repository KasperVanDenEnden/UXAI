<?php

namespace App\Actions;

use App\Concerns\Action;
use Illuminate\Support\Facades\DB;

class StoreBadDataAction implements Action
{
    public function execute(): void
    {
        DB::transaction(function () {
            // implement transaction with the database
        });
    }
}
