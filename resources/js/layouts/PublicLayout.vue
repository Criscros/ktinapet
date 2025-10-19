<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
</script>

<template>
  <div class="min-h-screen bg-[#FDFDFC] text-[#1b1b18] dark:bg-[#0a0a0a]">
    <header class="w-full sticky top-0 z-30 backdrop-blur supports-[backdrop-filter]:bg-white/70 bg-white/90 dark:bg-[#0a0a0a]/90 border-b border-black/5 dark:border-white/10">
      <div class="mx-auto max-w-6xl px-2 lg:px-0 h-14 flex items-center justify-between">
        <Link href="/" class="font-semibold tracking-tight text-[#1b1b18] dark:text-[#EDEDEC]">KTINA</Link>
        <nav class="flex items-center gap-6 text-sm">
          <Link href="/news" class="text-[#1b1b18]/80 hover:text-[#1b1b18] dark:text-[#EDEDEC]/80 dark:hover:text-[#EDEDEC]">News</Link>
          <Link href="/nosotros" class="text-[#1b1b18]/80 hover:text-[#1b1b18] dark:text-[#EDEDEC]/80 dark:hover:text-[#EDEDEC]">Nosotros</Link>
          <Link href="/servicios" class="text-[#1b1b18]/80 hover:text-[#1b1b18] dark:text-[#EDEDEC]/80 dark:hover:text-[#EDEDEC]">Servicios</Link>
          <Link href="/contactanos" class="text-[#1b1b18]/80 hover:text-[#1b1b18] dark:text-[#EDEDEC]/80 dark:hover:text-[#EDEDEC]">Contáctanos</Link>
          <Link href="/#booking" class="inline-flex items-center rounded-full bg-pink-600 px-4 py-1.5 font-medium text-white hover:bg-pink-700">Booking</Link>
        </nav>
      </div>
    </header>

    <main>
      <slot />
    </main>
  </div>
</template>
