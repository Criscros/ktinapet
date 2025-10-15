<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Posts', href: '/blog' },
  { title: 'Nuevo', href: '/blog/create' },
];

const title = ref('');
const description = ref('');
const tagsText = ref('');
const images = ref<FileList | null>(null);
const video = ref<File | null>(null);

const handleSubmit = () => {
  const form = new FormData();
  form.append('title', title.value);
  if (description.value) form.append('description', description.value);
  if (tagsText.value) form.append('tags', tagsText.value);
  if (images.value) {
    Array.from(images.value).forEach((f) => form.append('images[]', f));
  }
  if (video.value) form.append('video', video.value);
  router.post('/blog', form, { forceFormData: true });
};
</script>

<template>
  <Head title="Admin · Posts · Nuevo" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
      <div class="flex items-center justify-between">
        <h1 class="text-xl font-semibold">Nuevo Post</h1>
        <Link href="/blog" class="text-sm underline">Volver</Link>
      </div>

      <div class="relative flex-1 rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border max-w-3xl">
        <form @submit.prevent="handleSubmit" class="space-y-4">
          <div>
            <label class="block text-sm mb-1">Título</label>
            <input v-model="title" type="text" class="w-full rounded border px-3 py-2 text-sm" required />
          </div>
          <div>
            <label class="block text-sm mb-1">Descripción</label>
            <textarea v-model="description" class="w-full rounded border px-3 py-2 text-sm" rows="6" />
          </div>
          <div>
            <label class="block text-sm mb-1">Tags (separados por coma)</label>
            <input v-model="tagsText" type="text" placeholder="ej: perro, grooming, oferta" class="w-full rounded border px-3 py-2 text-sm" />
          </div>
          <div>
            <label class="block text-sm mb-1">Imágenes</label>
            <input type="file" multiple accept="image/*" @change="(e:any)=>{ images = e.target.files }" />
          </div>
          <div>
            <label class="block text-sm mb-1">Video</label>
            <input type="file" accept="video/*" @change="(e:any)=>{ video = e.target.files?.[0] || null }" />
          </div>
          <div class="pt-2">
            <button type="submit" class="rounded bg-neutral-900 px-3 py-1 text-white dark:bg-white dark:text-neutral-900 text-sm">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>
