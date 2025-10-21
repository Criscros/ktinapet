<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/layouts/PublicLayout.vue';

defineOptions({ layout: PublicLayout });

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

  <div class="min-h-screen bg-white dark:bg-neutral-900">
    <!-- Featured Image Section - Full Width -->
    <div class="relative w-full overflow-hidden bg-gradient-to-br from-neutral-50 via-purple-50 to-pink-50 dark:from-purple-900/20 dark:via-pink-900/20 dark:to-neutral-900">
      <div class="absolute inset-0 opacity-10">
        <!-- Decorative pattern background -->
        <div class="h-full w-full" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%239C92AC\' fill-opacity=\'0.2\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
      </div>
      
      <div class="relative mx-auto max-w-6xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
        <div class="text-center">
          <!-- VIG Spotlight style header -->
          <div class="mb-6 inline-flex items-center gap-3">
            <img src="/logo_black.jpeg" alt="KTINA" class="h-16 w-auto" />
            <div class="text-left">
              <div class="text-2xl font-bold text-neutral-900 dark:text-white">Blog</div>
              <div class="text-sm font-semibold uppercase tracking-wider text-purple-600 dark:text-purple-400">Spotlight</div>
            </div>
          </div>
          
          <!-- Placeholder for featured images (can be customized per post) -->
          <div class="mb-8 flex items-center justify-center gap-4">
            <div class="h-32 w-32 overflow-hidden rounded-full border-4 border-white shadow-xl dark:border-neutral-800">
              <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-purple-100 to-pink-100 dark:from-purple-800 dark:to-pink-800">
                <svg class="h-16 w-16 text-neutral-400 dark:text-neutral-300" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Content Section -->
    <div class="mx-auto max-w-4xl px-4 py-12 sm:px-6 lg:px-8">
      <!-- Breadcrumb -->
      <nav class="mb-8 text-sm text-neutral-500 dark:text-neutral-400">
        <Link href="/blogs" class="hover:text-neutral-900 dark:hover:text-neutral-200">← Back to all posts</Link>
      </nav>

      <!-- Article -->
      <article class="bg-white dark:bg-neutral-900">
        <!-- Header -->
        <header class="mb-10 border-b border-neutral-200 pb-8 dark:border-neutral-800">
          <h1 class="mb-4 text-4xl font-bold leading-tight text-neutral-900 dark:text-neutral-100 sm:text-5xl">
            {{ props.item.title }}
          </h1>
          
          <div class="flex items-center gap-4 text-sm text-neutral-600 dark:text-neutral-400">
            <div class="flex items-center gap-2">
              <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
              </svg>
              <time>{{ formatDate(props.item.created_at) }}</time>
            </div>
          </div>

          <!-- Tags -->
          <div v-if="props.item.tags?.length" class="mt-6 flex flex-wrap gap-2">
            <span 
              v-for="t in props.item.tags" 
              :key="t" 
              class="rounded-full bg-purple-100 px-3 py-1.5 text-xs font-semibold uppercase tracking-wider text-purple-700 dark:bg-purple-900/30 dark:text-purple-300"
            >
              {{ t }}
            </span>
          </div>
        </header>

        <!-- Content -->
        <div
          class="blog-post__body prose prose-lg prose-neutral max-w-none dark:prose-invert prose-headings:font-bold prose-headings:text-neutral-900 prose-p:text-neutral-700 prose-a:text-purple-600 prose-a:no-underline hover:prose-a:underline prose-strong:text-neutral-900 prose-img:rounded-2xl prose-img:shadow-lg dark:prose-headings:text-white dark:prose-p:text-white dark:prose-a:text-purple-400 dark:prose-strong:text-white"
          v-html="props.item.description || ''"
        />
      </article>
    </div>
  </div>
</template>
