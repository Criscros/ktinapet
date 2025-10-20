<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';

type Item = {
  id: number;
  title: string;
  description?: string; // HTML from TinyMCE
  tags?: string[];
  created_at?: string;
};

const props = defineProps<{ item: Item }>();

// Try to use TinyMCE content CSS from the CDN for consistent rendering
const tinyMceApiKey =
  import.meta.env.VITE_TINYMCE_API_KEY ||
  (document.querySelector('meta[name="tinymce-api-key"]') as HTMLMetaElement | null)?.content ||
  '';
const tinyContentCssHref = `https://cdn.tiny.cloud/1/${tinyMceApiKey || 'no-api-key'}/tinymce/8/skins/content/default/content.min.css`;

const formatDate = (iso?: string) => {
  if (!iso) return '';
  try {
    return new Date(iso).toLocaleDateString(undefined, {
      year: 'numeric', month: 'long', day: 'numeric',
    });
  } catch {
    return iso;
  }
};
</script>

<template>
  <Head :title="props.item.title || 'Blog'">
    <link rel="stylesheet" :href="tinyContentCssHref" />
  </Head>

  <div class="mx-auto max-w-3xl p-6">
    <nav class="mb-4 text-sm opacity-70">
      <Link href="/blogs" class="underline">Blogs</Link>
      <span> / </span>
      <span>{{ props.item.title }}</span>
    </nav>

    <!-- Hero placeholder (add real image support later) -->
    <div class="mb-6 h-48 w-full overflow-hidden rounded-xl bg-gradient-to-br from-neutral-200 to-neutral-300 dark:from-neutral-800 dark:to-neutral-700"></div>

    <article class="rounded-xl bg-white p-6 shadow-sm dark:bg-neutral-900">
      <header class="mb-6">
        <h1 class="text-3xl font-semibold leading-tight">{{ props.item.title }}</h1>
        <div class="mt-2 text-xs opacity-70">
          Published on {{ formatDate(props.item.created_at) }}
        </div>
        <div v-if="props.item.tags?.length" class="mt-3 flex flex-wrap gap-2">
          <span v-for="t in props.item.tags" :key="t" class="rounded-full bg-neutral-100 px-2 py-0.5 text-xs uppercase tracking-wide dark:bg-neutral-800">{{ t }}</span>
        </div>
      </header>

      <!-- TinyMCE HTML content -->
      <div
        class="prose prose-neutral max-w-none dark:prose-invert prose-img:rounded-lg prose-headings:scroll-mt-20"
        v-html="props.item.description || ''"
      />
    </article>
  </div>
</template>
