<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function index(): Response
    {
        $items = Post::orderByDesc('id')->get();
        return Inertia::render('textblog/Index', [
            'items' => $items,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('textblog/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'tags' => ['nullable', 'string'],
        ]);

        $tags = array_values(array_filter(array_map(fn ($t) => trim($t), explode(',', $data['tags'] ?? ''))));

        $item = Post::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'tags' => $tags ?: null,
        ]);

        return redirect()->route('posts.edit', $item);
    }

    public function edit(Post $post): Response
    {
        return Inertia::render('textblog/Edit', [
            'item' => $post,
        ]);
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'tags' => ['nullable', 'string'],
        ]);

        $tags = array_values(array_filter(array_map(fn ($t) => trim($t), explode(',', $data['tags'] ?? ''))));

        $post->update([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'tags' => $tags ?: null,
        ]);

        return redirect()->route('posts.index');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $post->delete();
        return redirect()->route('posts.index');
    }

    public function publicIndex(): Response
    {
        $items = Post::orderByDesc('id')->get();
        return Inertia::render('textblog/PublicIndex', [
            'items' => $items,
        ]);
    }

    public function show(Post $post): Response
    {
        return Inertia::render('textblog/PublicShow', [
            'item' => $post,
        ]);
    }
}
