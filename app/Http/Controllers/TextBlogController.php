<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\TextBlog;

class TextBlogController extends Controller
{
    public function index(): Response
    {
        $items = TextBlog::orderByDesc('id')->get();
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

        $item = TextBlog::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'tags' => $tags ?: null,
        ]);

        return redirect()->route('textblog.edit', $item);
    }

    public function edit(TextBlog $textblog): Response
    {
        return Inertia::render('textblog/Edit', [
            'item' => $textblog,
        ]);
    }

    public function update(Request $request, TextBlog $textblog): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'tags' => ['nullable', 'string'],
        ]);

        $tags = array_values(array_filter(array_map(fn ($t) => trim($t), explode(',', $data['tags'] ?? ''))));

        $textblog->update([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'tags' => $tags ?: null,
        ]);

        return redirect()->route('textblog.index');
    }

    public function destroy(TextBlog $textblog): RedirectResponse
    {
        $textblog->delete();
        return redirect()->route('textblog.index');
    }

    public function publicIndex(): Response
    {
        $items = TextBlog::orderByDesc('id')->get();
        return Inertia::render('textblog/PublicIndex', [
            'items' => $items,
        ]);
    }

    public function show(TextBlog $textblog): Response
    {
        return Inertia::render('textblog/PublicShow', [
            'item' => $textblog,
        ]);
    }
}
