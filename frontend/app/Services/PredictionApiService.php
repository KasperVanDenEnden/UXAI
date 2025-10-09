<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class PredictionApiService
{
    protected string $baseUrl;

    protected string $health = '/health';

    protected string $route = '/predict';

    protected int $timeout;

    public function __construct()
    {
        $this->baseUrl = config('nodejs.api_url');
        $this->timeout = config('nodejs.timeout');
    }

    public function healthCheck(): Response
    {
        return Http::timeout($this->timeout)->get($this->baseUrl.$this->health);
    }

    public function goodPrediction(): Response
    {
        return Http::timeout($this->timeout)->post($this->baseUrl.$this->route, []);
    }

    //    public function badPrediction(): Response
    //    {
    //
    //    }
}
