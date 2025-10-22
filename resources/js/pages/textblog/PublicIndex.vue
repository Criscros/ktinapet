<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/layouts/PublicLayout.vue';

defineOptions({ layout: PublicLayout });

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
  <Head title="Blogs"   cllas/>
  <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <header class="mb-10 text-center">
      <h1 class="text-3xl font-bold tracking-tight sm:text-4xl text-neutral-900 dark:text-neutral-100">Blogs</h1>
      <p class="mt-2 text-base text-neutral-600 dark:text-neutral-400">Historias, tips y noticias de grooming</p>
    </header>

    <div v-if="!props.items?.length" class="py-12 text-center text-neutral-500">No hay publicaciones aún.</div>

    <div v-else class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
      <article
        v-for="it in props.items"
        :key="it.id"
        class="group flex flex-col overflow-hidden rounded-2xl bg-white shadow-md transition-all hover:-translate-y-1 hover:shadow-xl dark:bg-neutral-900"
      >
        <!-- Featured Image -->
        <Link :href="`/blogs/${it.id}`" class="relative aspect-[4/3] overflow-hidden bg-gradient-to-br from-neutral-100 to-neutral-200 dark:from-neutral-800 dark:to-neutral-700">
          <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-blue-50 to-purple-50 dark:from-blue-950/30 dark:to-purple-950/30">
            <div class="text-6xl font-bold uppercase tracking-wider text-neutral-200/40 dark:text-neutral-700/40">VIG</div>
          </div>
        </Link>

        <!-- Content -->
        <div class="flex flex-1 flex-col p-6">
          <!-- Tags -->
          <div v-if="it.tags?.length" class="mb-3 flex flex-wrap gap-2">
            <span 
              v-for="t in it.tags" 
              :key="t" 
              class="text-[10px] font-medium uppercase tracking-wider text-neutral-600 dark:text-neutral-400"
            >
              {{ t }}
            </span>
          </div>

          <!-- Title -->
          <h2 class="mb-3 text-xl font-bold leading-tight text-neutral-900 dark:text-neutral-100">
            <Link :href="`/blogs/${it.id}`" class="transition-colors hover:text-blue-600 dark:hover:text-blue-400">
              {{ it.title }}
            </Link>
          </h2>

          <!-- Excerpt -->
          <p class="mb-4 flex-1 text-sm leading-relaxed text-neutral-600 dark:text-neutral-400">
            {{ getExcerpt(it.description, 120) }}
          </p>

          <!-- Footer -->
          <div class="flex items-center justify-between border-t border-neutral-100 pt-4 dark:border-neutral-800">
            <span class="text-xs text-neutral-500 dark:text-neutral-500">{{ getReadTime(it.description) }}</span>
            <Link 
              :href="`/blogs/${it.id}`" 
              class="inline-flex items-center gap-1 text-xs font-semibold text-neutral-900 transition-all hover:gap-2 dark:text-neutral-100"
            >
              Read More
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="h-3.5 w-3.5">
                <path d="M9 18l6-6-6-6"/>
              </svg>
            </Link>
          </div>
        </div>
      </article>
    </div>
  </div>
</template>
