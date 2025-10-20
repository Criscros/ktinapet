<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'TextBlog', href: '/textblog' },
  { title: 'Nuevo (Texto)', href: '/textblog/create' },
];

const title = ref('');
const tagsText = ref('');
const description = ref('');
const editorInstance = ref<any>(null);

const tinyMceApiKey =
  import.meta.env.VITE_TINYMCE_API_KEY ||
  (document.querySelector('meta[name="tinymce-api-key"]') as HTMLMetaElement | null)?.content ||
  '';

const tinymceScriptSrc = `https://cdn.tiny.cloud/1/${tinyMceApiKey || 'no-api-key'}/tinymce/8/tinymce.min.js`;
if (!tinyMceApiKey) {
  console.warn('[TinyMCE] Missing API key. Set VITE_TINYMCE_API_KEY in .env (client) or TINYMCE_API_KEY in server env, then rebuild.');
}

const editorInit = {
  height: 400,
  menubar: true,
  // Provide plugins as a single space-separated string to avoid invalid CDN paths
  plugins:
    [
      'advlist',
      'anchor',
      'autolink',
      'charmap',
      'code',
      'codesample',
      'directionality',
      'emoticons',
      'fullscreen',
      'help',
      'image',
      'insertdatetime',
      'link',
      'lists',
      'media',
      'nonbreaking',
      'pagebreak',
      'preview',
      'quickbars',
      'searchreplace',
      'table',
      'visualblocks',
      'visualchars',
      'wordcount',
    ].join(' '),
  toolbar:
    [
      'undo redo | blocks fontfamily fontsize |',
      'bold italic underline strikethrough | forecolor backcolor |',
      'alignleft aligncenter alignright alignjustify |',
      'outdent indent | numlist bullist |',
      'rtl ltr |',
      'link image media table |',
      'hr pagebreak charmap emoticons |',
      'codesample code | fullscreen |',
      'searchreplace preview | removeformat | help',
    ].join(' '),
  images_upload_url: '/api/uploads',
  automatic_uploads: true,
  relative_urls: false,
};

const handleEditorInit = (_evt: any, editor: any) => {
  editorInstance.value = editor;
};

const handleSubmit = () => {
  if (editorInstance.value) {
    try {
      description.value = editorInstance.value.getContent({ format: 'html' }) || '';
    } catch {}
  }
  const form = new FormData();
  form.append('title', title.value);
  form.append('description', description.value || '');
  form.append('tags', tagsText.value || '');
  router.post('/textblog', form, { forceFormData: true });
};
</script>

<template>
  <Head title="Admin · Posts · Nuevo (Texto)" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
      <div class="flex items-center justify-between">
        <h1 class="text-xl font-semibold">Nuevo Post (Texto)</h1>
        <Link href="/textblog" class="text-sm underline">Volver</Link>
      </div>

      <div class="relative flex-1 rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border max-w-3xl">
        <form @submit.prevent="handleSubmit" class="space-y-4">
          <div>
            <label class="block text-sm mb-1">Título</label>
            <input v-model="title" type="text" class="w-full rounded border px-3 py-2 text-sm" required />
          </div>

          <div>
            <label class="block text-sm mb-1">Contenido</label>
            <Editor v-model="description" :init="editorInit" :tinymce-script-src="tinymceScriptSrc" @init="handleEditorInit" />
          </div>

          <div>
            <label class="block text-sm mb-1">Tags (separados por coma)</label>
            <input v-model="tagsText" type="text" placeholder="ej: perro, grooming, oferta" class="w-full rounded border px-3 py-2 text-sm" />
          </div>

          <div class="pt-2">
            <button type="submit" class="rounded bg-neutral-900 px-3 py-1 text-white dark:bg-white dark:text-neutral-900 text-sm">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>
