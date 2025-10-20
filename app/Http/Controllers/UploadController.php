<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class UploadController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'file' => ['required', 'image', 'max:5120'], // 5MB
        ]);

        $path = $request->file('file')->store('uploads', 'public');
        $url = Storage::disk('public')->url($path);

        return response()->json(['location' => $url]);
    }
}
