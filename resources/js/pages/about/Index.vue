<template>
  <div class="min-h-screen bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC]">
    <div class="mx-auto max-w-5xl px-6 py-12">
      <h1 class="text-3xl font-semibold mb-4">Nosotros</h1>
      <p class="text-neutral-700 dark:text-neutral-300 mb-6">
        Somos KTINA Pet Grooming, apasionados por el bienestar y la estética de tus mascotas.
      </p>
      <div class="grid gap-6 sm:grid-cols-2">
        <img src="/logo.jpeg" alt="KTINA" class="rounded-xl w-full h-64 object-cover bg-white" />
        <div class="rounded-xl border border-black/10 dark:border-white/10 bg-white dark:bg-[#161615] p-6">
          <h2 class="text-xl font-semibold mb-2">Nuestra misión</h2>
          <p class="text-sm text-neutral-700 dark:text-neutral-300">
            Ofrecer una experiencia de grooming cómoda, segura y de alta calidad.
          </p>
        </div>
      </div>
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
