<?php

namespace App\Http\Controllers;

use App\Http\Requests\AggregateRequest;
use App\Services\MarketData\MarketDataService;
use Exception;

class AggregateController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @throws Exception
     */
    public function __invoke(AggregateRequest $request, MarketDataService $provider)
    {
        // Retrieve the validated input data...
        $validated = $request->validated();

        // Process the request...
        try {
            $response = $provider->getAggregateData(...$validated);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        return response()->json($response);
    }
}
