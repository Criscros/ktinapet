<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';

type Item = { id: number; title: string; description?: string; tags?: string[] };
const props = defineProps<{ items: Array<Item> }>();

const wordsPerMinute = 200;
const getReadTime = (text?: string) => {
  if (!text) return '1 minute read';
  const words = text.replace(/<[^>]*>/g, ' ').trim().split(/\s+/).length;
  const minutes = Math.max(1, Math.round(words / wordsPerMinute));
  return `${minutes} minute read`;
};

const getExcerpt = (text?: string, maxLen = 140) => {
  if (!text) return '';
  const plain = text.replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim();
  if (plain.length <= maxLen) return plain;
  return plain.slice(0, maxLen).trimEnd() + '…';
};
</script>

<template>
  <Head title="Blogs" />
  <div class="mx-auto max-w-6xl p-6">
    <header class="mb-6">
      <h1 class="text-2xl font-semibold">Blogs</h1>
      <p class="text-sm opacity-70">Historias, tips y noticias de grooming.</p>
    </header>

    <div v-if="!props.items?.length" class="text-sm">No hay publicaciones aún.</div>

    <div v-else class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
      <article
        v-for="it in props.items"
        :key="it.id"
        class="group overflow-hidden rounded-xl border border-neutral-200 bg-white shadow-sm transition hover:shadow-lg dark:border-neutral-800 dark:bg-neutral-900"
      >
        <!-- Media placeholder (no image field yet) -->
        <div class="h-32 bg-gradient-to-br from-neutral-200 to-neutral-300 dark:from-neutral-800 dark:to-neutral-700"></div>

        <div class="space-y-2 p-4">
          <div v-if="it.tags?.length" class="flex flex-wrap gap-2 text-[11px] uppercase tracking-wide opacity-70">
            <span v-for="t in it.tags" :key="t" class="rounded-full bg-neutral-100 px-2 py-0.5 dark:bg-neutral-800">{{ t }}</span>
          </div>

          <h2 class="text-base font-semibold leading-snug">
            <Link :href="`/blogs/${it.id}`" class="group-hover:underline">{{ it.title }}</Link>
          </h2>

          <p class="text-sm opacity-80">{{ getExcerpt(it.description) }}</p>

          <div class="mt-3 flex items-center justify-between text-xs opacity-70">
            <span>{{ getReadTime(it.description) }}</span>
            <Link :href="`/blogs/${it.id}`" class="inline-flex items-center gap-1 font-medium group-hover:gap-2 transition-all">
              Read More
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-3.5 w-3.5"><path d="M9 18l6-6-6-6"/></svg>
            </Link>
          </div>
        </div>
      </article>
    </div>
  </div>
</template>
