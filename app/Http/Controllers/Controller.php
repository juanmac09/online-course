<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;

abstract class Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Handles a service call and returns the result as JSON.
     *
     * @param callable $callback The function to be executed.
     *
     * @return JsonResponse A JSON response containing the result of the callback function.
     * @throws \Throwable If an exception is thrown during the execution of the callback function.
     */
    protected function handleServiceCall(callable $callback): JsonResponse
    {
        try {
            $data = $callback();
            return response()->json(['success' => true, 'response' => $data], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }
}
