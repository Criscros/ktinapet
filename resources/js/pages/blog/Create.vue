<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, onUnmounted } from 'vue';
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

const imagePreviews = ref<string[]>([]);

const handleImagesChange = (e: Event) => {
  const target = e.target as HTMLInputElement | null;
  const files = target?.files || null;
  // revoke previous URLs before replacing
  imagePreviews.value.forEach((u) => URL.revokeObjectURL(u));
  images.value = files;
  imagePreviews.value = files ? Array.from(files).map((f) => URL.createObjectURL(f)) : [];
};

onUnmounted(() => {
  imagePreviews.value.forEach((u) => URL.revokeObjectURL(u));
});

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
            <input type="file" multiple accept="image/*" @change="handleImagesChange" />
            <div v-if="imagePreviews.length" class="mt-2 grid grid-cols-3 gap-2">
              <img v-for="(src, idx) in imagePreviews" :key="idx" :src="src" class="h-24 w-full object-cover rounded border" />
            </div>
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
