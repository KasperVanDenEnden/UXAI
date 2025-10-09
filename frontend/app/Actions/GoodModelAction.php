<?php

namespace App\Actions;

use App\Concerns\Action;
use App\Http\Requests\GoodRequest;
use App\Services\PredictionApiService;

class GoodModelAction implements Action
{
    public function execute(GoodRequest $request): void
    {
        // @todo api service to call good prediction method
        $api = new PredictionApiService;
        $api->healthCheck();
    }
}
