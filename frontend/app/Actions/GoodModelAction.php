<?php

namespace App\Actions;

use App\Concerns\Action;
use Illuminate\Support\Facades\DB;

class GoodModelAction implements Action
{
    public function execute(): string
    {
        return DB::transaction(function () {
            return 'Dummy good model action completed';
        });
    }
}
