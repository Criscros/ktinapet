<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@/types';

const props = defineProps<{
  posts: {
    data: Array<{
      id: number;
      title: string;
      description: string | null;
      tags: string[];
      images: string[];
      video_url: string | null;
      created_at: string | null;
    }>;
    links?: Array<{ url: string | null; label: string; active: boolean }>;
  }
}>();

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Posts', href: '/blog' },
];

const handleDelete = (id: number) => {
  if (!confirm('Eliminar este post?')) return;
  router.delete(`/blog/${id}`);
};
</script>

<template>
  <Head title="Admin · Posts" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
      <div class="flex items-center justify-between">
        <h1 class="text-xl font-semibold">Posts</h1>
        <Link href="/blog/create" class="rounded bg-neutral-900 px-3 py-1 text-white dark:bg-white dark:text-neutral-900 text-sm">Nuevo</Link>
      </div>

      <div class="relative flex-1 rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border">
        <div v-if="!props.posts?.data?.length" class="text-sm text-neutral-500 dark:text-neutral-300">
          No hay posts aún.
        </div>

        <div v-else class="flex h-full flex-col">
          <div class="overflow-x-auto flex-1">
            <table class="min-w-full text-sm">
              <thead>
                <tr class="text-left text-neutral-600 dark:text-neutral-300 border-b border-sidebar-border/70">
                  <th class="py-2 pr-4">#</th>
                  <th class="py-2 pr-4">Título</th>
                  <th class="py-2 pr-4">Tags</th>
                  <th class="py-2 pr-4">Creado</th>
                  <th class="py-2 pr-4"></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="row in props.posts.data" :key="row.id" class="border-b border-sidebar-border/40">
                  <td class="py-2 pr-4">{{ row.id }}</td>
                  <td class="py-2 pr-4 max-w-[28rem] truncate" :title="row.title">{{ row.title }}</td>
                  <td class="py-2 pr-4">{{ (row.tags || []).join(', ') }}</td>
                  <td class="py-2 pr-4 whitespace-nowrap">{{ row.created_at || '-' }}</td>
                  <td class="py-2 pr-4 whitespace-nowrap flex items-center gap-2">
                    <Link :href="`/blog/${row.id}/edit`" class="text-xs underline">Editar</Link>
                    <button class="text-xs text-red-600" @click="handleDelete(row.id)">Eliminar</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div v-if="props.posts.links?.length" class="mt-4 flex flex-wrap items-center gap-2 self-end">
            <Link
              v-for="(l, idx) in props.posts.links"
              :key="idx"
              :href="l.url || '#'"
              :class="[
                'px-3 py-1 rounded border text-xs',
                l.active ? 'bg-neutral-900 text-white dark:bg-white dark:text-neutral-900' : 'border-sidebar-border/70 text-neutral-700 dark:text-neutral-300'
              ]"
              :disabled="!l.url"
              preserve-scroll
              preserve-state
            >
              <span v-html="l.label" />
            </Link>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
