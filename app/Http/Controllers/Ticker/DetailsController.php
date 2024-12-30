<?php

namespace App\Http\Controllers\Ticker;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuoteRequest;
use App\Services\MarketData\MarketDataService;
use Exception;

class DetailsController extends Controller
{
    public function __invoke(QuoteRequest $request, MarketDataService $provider)
    {
        $validated = $request->validated();

        try {
            $response = $provider->getDetailsData(...$validated);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        return response()->json($response);
    }
}
