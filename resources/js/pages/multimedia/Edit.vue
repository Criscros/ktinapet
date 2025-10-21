<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import type { BreadcrumbItem } from '@/types';

const props = defineProps<{
  post: {
    id: number;
    title: string;
    description: string | null;
    tags: string[];
    images: string[];
    video_url: string | null;
    created_at: string | null;
  }
}>();

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Multimedia', href: '/multimedia' },
  { title: 'Editar', href: `/multimedia/${props.post.id}/edit` },
];

const title = ref(props.post.title || '');
const description = ref(props.post.description || '');
const tagsText = ref((props.post.tags || []).join(', '));

const existingImages = ref<string[]>(props.post.images || []);
const resetImages = ref(false);
const newImages = ref<FileList | null>(null);

const existingVideo = ref<string | null>(props.post.video_url || null);
const resetVideo = ref(false);
const newVideo = ref<File | null>(null);

const hasExistingImages = computed(() => existingImages.value && existingImages.value.length > 0);

const handleSubmit = () => {
  const form = new FormData();
  form.append('_method', 'put');
  if (title.value) form.append('title', title.value);
  form.append('description', description.value || '');
  form.append('tags', tagsText.value || '');
  form.append('reset_images', resetImages.value ? '1' : '0');
  form.append('reset_video', resetVideo.value ? '1' : '0');
  if (newImages.value) {
    Array.from(newImages.value).forEach((f) => form.append('images[]', f));
  }
  if (newVideo.value) form.append('video', newVideo.value);

  router.post(`/blog/${props.post.id}`, form, { forceFormData: true });
};
</script>

<template>
  <Head :title="`Admin · Posts · Editar · ${props.post.title}`" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
      <div class="flex items-center justify-between">
        <h1 class="text-xl font-semibold">Editar Post #{{ props.post.id }}</h1>
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
            <input v-model="tagsText" type="text" class="w-full rounded border px-3 py-2 text-sm" />
          </div>

          <div class="space-y-2">
            <div class="flex items-center justify-between">
              <label class="block text-sm">Imágenes actuales</label>
              <label class="flex items-center gap-2 text-xs"><input type="checkbox" v-model="resetImages" /> Resetear imágenes</label>
            </div>
            <div v-if="hasExistingImages && !resetImages" class="flex gap-2 flex-wrap">
              <img v-for="(img, idx) in existingImages" :key="idx" :src="`${img}`" class="h-20 w-20 object-cover rounded border" />
            </div>
            <div>
              <label class="block text-sm mb-1">Añadir nuevas imágenes</label>
              <input type="file" multiple accept="image/*" @change="(e:any)=>{ newImages = e.target.files }" />
            </div>
          </div>

          <div class="space-y-2">
            <div class="flex items-center justify-between">
              <label class="block text-sm">Video actual</label>
              <label class="flex items-center gap-2 text-xs"><input type="checkbox" v-model="resetVideo" /> Resetear video</label>
            </div>
            <div v-if="existingVideo && !resetVideo" class="text-xs text-neutral-600 dark:text-neutral-300">
              {{ existingVideo }}
            </div>
            <div>
              <label class="block text-sm mb-1">Subir nuevo video</label>
              <input type="file" accept="video/*" @change="(e:any)=>{ newVideo = e.target.files?.[0] || null }" />
            </div>
          </div>

          <div class="pt-2">
            <button type="submit" class="rounded bg-neutral-900 px-3 py-1 text-white dark:bg-white dark:text-neutral-900 text-sm">Actualizar</button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>
