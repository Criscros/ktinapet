<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BookingController extends Controller
{
    /**
     * Handle incoming public booking data.
     */
    public function store(Request $request): JsonResponse
    {

        return response()->json([
            'message' => 'Booking received',
            'data' => $request->all(),
        ], 201);
    }
}
