<template>
  <div class="min-h-screen bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC]">
    <div class="mx-auto max-w-5xl px-6 py-12">
      <h1 class="text-3xl font-semibold mb-4">Contáctanos</h1>
      <p class="text-neutral-700 dark:text-neutral-300 mb-8">
        ¿Tienes preguntas o quieres una cita? Escríbenos y te responderemos pronto.
      </p>

      <form class="grid gap-4 max-w-xl">
        <input type="text" placeholder="Nombre" class="w-full rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm dark:border-neutral-700 dark:bg-neutral-100" />
        <input type="email" placeholder="Email" class="w-full rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm dark:border-neutral-700 dark:bg-neutral-100" />
        <input type="tel" placeholder="Teléfono" class="w-full rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm dark:border-neutral-700 dark:bg-neutral-100" />
        <textarea rows="5" placeholder="Mensaje" class="w-full rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm dark:border-neutral-700 dark:bg-neutral-100"></textarea>
        <button type="button" class="rounded-full bg-pink-600 px-5 py-2.5 text-white text-sm font-medium hover:bg-pink-700">Enviar</button>
      </form>

      <div class="mt-10">
        <a href="/" class="inline-flex items-center rounded-full bg-pink-600 px-5 py-2.5 text-white text-sm font-medium hover:bg-pink-700">Volver</a>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import PublicLayout from '@/layouts/PublicLayout.vue'

defineOptions({ layout: PublicLayout })
</script>
