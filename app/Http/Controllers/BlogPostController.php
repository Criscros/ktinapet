<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class BlogPostController extends Controller
{
    public function index(): Response
    {
        $posts = BlogPost::latest('created_at')
            ->paginate(10)
            ->through(function ($p) {
                return [
                    'id' => $p->id,
                    'title' => $p->title,
                    'description' => $p->description,
                    'tags' => $p->tags ?? [],
                    'images' => collect($p->images ?? [])
                        ->filter(fn ($path) => !empty($path))
                        ->map(fn ($path) => $this->s3Url($path))
                        ->values()
                        ->all(),
                    'video_url' => $p->video_url ? $this->s3Url($p->video_url) : null,
                    'created_at' => optional($p->created_at)->format('Y-m-d H:i'),
                ];
            });

        return Inertia::render('blog/Index', [
            'posts' => $posts,
        ]);
    }

    public function news(): Response
    {
        $posts = BlogPost::query()
            ->latest('id')
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'title' => $p->title,
                    'description' => $p->description,
                    'tags' => $p->tags ?? [],
                    'images' => collect($p->images ?? [])
                        ->filter(fn ($path) => !empty($path))
                        ->map(fn ($path) => $this->s3Url($path))
                        ->values()
                        ->all(),
                    'video_url' => $p->video_url ? $this->s3Url($p->video_url) : null,
                    'created_at' => optional($p->created_at)->toAtomString(),
                ];
            });

        return Inertia::render('news/Index', [
            'posts' => $posts,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('blog/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatePayload($request);

        $images = [];
        if ($request->hasFile('images')) {

            foreach ($request->file('images') as $file) {
         
                if ($file && $file->isValid()) {
                    $images[] = $file->store('uploads/blog/images', 's3');
                }
            }
        }

        // Prefer presigned upload key when present
        $videoPath = $request->string('video_key')->toString() ?: null;
        if (!$videoPath && $request->hasFile('video')) {
            $file = $request->file('video');
            if ($file && $file->isValid()) {
                $videoPath = $file->store('uploads/blog/videos', 's3');
            }
        }

        $post = BlogPost::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'tags' => $data['tags'] ?? [],
            'images' => $images ?: null,
            'video_url' => $videoPath,
        ]);

        return redirect()->route('blog.index');
    }

    public function edit(BlogPost $blog): Response
    {
        return Inertia::render('blog/Edit', [
            'post' => [
                'id' => $blog->id,
                'title' => $blog->title,
                'description' => $blog->description,
                'tags' => $blog->tags ?? [],
                'images' => collect($blog->images ?? [])
                    ->filter(fn ($path) => !empty($path))
                    ->map(fn ($path) => $this->s3Url($path))
                    ->values()
                    ->all(),
                'video_url' => $blog->video_url ? $this->s3Url($blog->video_url) : null,
                'created_at' => optional($blog->created_at)->format('Y-m-d H:i'),
            ],
        ]);
    }

    public function update(Request $request, BlogPost $blog): RedirectResponse
    {
        $data = $this->validatePayload($request, false);

        $images = $blog->images ?? [];
        if ($request->boolean('reset_images')) {
            $this->deleteMedia($images);
            $images = [];
        }
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if ($file && $file->isValid()) {
                    $images[] = $file->store('uploads/blog/images', 's3');
                }
            }
        }

        $videoPath = $blog->video_url;
        if ($request->boolean('reset_video') && $videoPath) {
            Storage::disk('s3')->delete($videoPath);
            $videoPath = null;
        }
        // If a new presigned key is provided, replace existing
        if ($request->filled('video_key')) {
            if ($videoPath) {
                Storage::disk('s3')->delete($videoPath);
            }
            $videoPath = $request->string('video_key')->toString();
        } elseif ($request->hasFile('video')) {
            if ($videoPath) {
                Storage::disk('s3')->delete($videoPath);
            }
            $file = $request->file('video');
            if ($file && $file->isValid()) {
                $videoPath = $file->store('uploads/blog/videos', 's3');
            }
        }

        $blog->update([
            'title' => $data['title'] ?? $blog->title,
            'description' => array_key_exists('description', $data) ? $data['description'] : $blog->description,
            'tags' => array_key_exists('tags', $data) ? $data['tags'] : ($blog->tags ?? []),
            'images' => $images ?: null,
            'video_url' => $videoPath,
        ]);

        return redirect()->route('blog.index');
    }

    public function destroy(BlogPost $blog): RedirectResponse
    {
        $this->deleteMedia($blog->images ?? []);
        if ($blog->video_url) {
            Storage::disk('s3')->delete($blog->video_url);
        }
        $blog->delete();
        return redirect()->route('blog.index');
    }

    private function validatePayload(Request $request, bool $requireTitle = true): array
    {
        $rules = [
            'title' => $requireTitle ? ['required', 'string', 'max:255'] : ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'tags' => ['nullable'],
            'images.*' => ['nullable', 'image', 'max:5120'],
            // Accept either a direct file upload or a presigned S3 key for videos
            'video' => ['nullable', 'file', 'mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/x-matroska', 'max:51200'],
            'video_key' => ['nullable', 'string'],
            'reset_images' => ['nullable', 'boolean'],
            'reset_video' => ['nullable', 'boolean'],
        ];

        $data = $request->validate($rules);

        $tags = $request->input('tags');
        if (is_string($tags)) {
            $tags = collect(explode(',', $tags))
                ->map(fn ($t) => trim($t))
                ->filter()
                ->values()
                ->all();
        }

        $data['tags'] = $tags ?: [];

        return $data;
    }

    private function deleteMedia(array $images): void
    {
        foreach ($images as $path) {
            if (!empty($path)) {
                Storage::disk('s3')->delete($path);
            }
        }
    }

    private function s3Url(string $path): string
    {
        $base = config('filesystems.disks.s3.url')
            ?? rtrim(env('AWS_URL', 'https://katinapet.s3.amazonaws.com'), '/');
        return $base . '/' . ltrim($path, '/');
    }
}
