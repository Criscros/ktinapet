<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Aws\S3\S3Client;

class S3Controller extends Controller
{
    public function presign(Request $request)
    {
        $request->validate([
            'filename' => 'required|string',
            'contentType' => 'required|string',
        ]);

        $userId = Auth::id() ?? 'guest';
        $filename = basename($request->input('filename'));
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $key = 'videos/' . $userId . '/' . Str::uuid() . ($ext ? '.' . $ext : '');

        // Construct an S3 client from config
        $cfg = config('filesystems.disks.s3');
        $client = new S3Client([
            'version' => 'latest',
            'region' => $cfg['region'] ?? env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key' => $cfg['key'] ?? env('AWS_ACCESS_KEY_ID'),
                'secret' => $cfg['secret'] ?? env('AWS_SECRET_ACCESS_KEY'),
            ],
            'endpoint' => $cfg['endpoint'] ?? null,
            'use_path_style_endpoint' => $cfg['use_path_style_endpoint'] ?? false,
        ]);
        $bucket = config('filesystems.disks.s3.bucket');

        $cmd = $client->getCommand('PutObject', [
            'Bucket' => $bucket,
            'Key' => $key,
            'ContentType' => $request->input('contentType'),
            'ACL' => 'private' // keep private by default
        ]);

        // Presign for 20 minutes
        $presignedRequest = $client->createPresignedRequest($cmd, '+20 minutes');
        $url = (string) $presignedRequest->getUri();

        return response()->json([
            'url' => $url,
            'key' => $key,
        ]);
    }
}
