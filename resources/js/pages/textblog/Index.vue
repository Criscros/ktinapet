<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps<{ items: Array<{ id: number; title: string; tags?: string[] }>; }>();

const handleDelete = (id: number) => {
  if (!confirm('Eliminar este post?')) return;
  router.delete(`/posts/${id}`);
};
</script>

<template>
  <Head title="Admin · Posts" />
  <AppLayout :breadcrumbs="[{ title: 'Posts', href: '/posts' }]">
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
      <div class="flex items-center justify-between">
        <h1 class="text-xl font-semibold">Posts</h1>
        <Link href="/posts/create" class="text-sm underline">Nuevo</Link>
      </div>

      <div class="rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border">
        <div v-if="!props.items?.length" class="text-sm">Sin registros</div>
        <ul v-else class="space-y-3">
          <li v-for="it in props.items" :key="it.id" class="flex items-center justify-between">
            <div>
              <div class="font-medium">{{ it.title }}</div>
              <div class="text-xs opacity-70" v-if="it.tags?.length">{{ it.tags.join(', ') }}</div>
            </div>
            <div class="flex items-center gap-3 text-sm">
              <Link :href="`/posts/${it.id}/edit`" class="underline">Editar</Link>
              <button class="text-red-600 underline" @click="handleDelete(it.id)">Eliminar</button>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </AppLayout>
</template>
