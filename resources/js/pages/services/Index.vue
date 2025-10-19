<template>
  <div class="min-h-screen bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC]">
    <div class="mx-auto max-w-5xl px-6 py-12">
      <h1 class="text-3xl font-semibold mb-6">Servicios</h1>
      <p class="text-neutral-700 dark:text-neutral-300 mb-10">Conoce nuestros servicios de grooming para tu mascota.</p>

      <div class="grid gap-6 sm:grid-cols-2">
        <div class="rounded-xl border border-black/10 dark:border-white/10 bg-white dark:bg-[#161615] p-6 flex gap-4 items-center">
          <img src="/logos/baño.jpeg" alt="Baño" class="w-16 h-16 rounded-lg object-cover bg-white" />
          <div>
            <h2 class="text-xl font-semibold mb-1">Baño</h2>
            <p class="text-sm text-neutral-600 dark:text-neutral-300">Baño completo con productos hipoalergénicos.</p>
          </div>
        </div>
        <div class="rounded-xl border border-black/10 dark:border-white/10 bg-white dark:bg-[#161615] p-6 flex gap-4 items-center">
          <img src="/logos/peinado.jpeg" alt="Groom" class="w-16 h-16 rounded-lg object-cover bg-white" />
          <div>
            <h2 class="text-xl font-semibold mb-1">Groom</h2>
            <p class="text-sm text-neutral-600 dark:text-neutral-300">Corte, peinado y retoque profesional.</p>
          </div>
        </div>
        <div class="rounded-xl border border-black/10 dark:border-white/10 bg-white dark:bg-[#161615] p-6 flex gap-4 items-center">
          <img src="/logos/patas.jpeg" alt="Uñas" class="w-16 h-16 rounded-lg object-cover bg-white" />
          <div>
            <h2 class="text-xl font-semibold mb-1">Uñas</h2>
            <p class="text-sm text-neutral-600 dark:text-neutral-300">Corte y limado seguro de uñas.</p>
          </div>
        </div>
        <div class="rounded-xl border border-black/10 dark:border-white/10 bg-white dark:bg-[#161615] p-6 flex gap-4 items-center">
          <img src="/logos/lengua.jpeg" alt="Limpieza de oídos" class="w-16 h-16 rounded-lg object-cover bg-white" />
          <div>
            <h2 class="text-xl font-semibold mb-1">Limpieza de oídos</h2>
            <p class="text-sm text-neutral-600 dark:text-neutral-300">Limpieza suave y efectiva para su bienestar.</p>
          </div>
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
