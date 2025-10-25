<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import PublicFooter from '@/components/PublicFooter.vue'
</script>

<template>
  <div class="min-h-screen bg-[#FDFDFC] text-[#1b1b18] dark:bg-neutral-900 flex flex-col">
    <header class="w-full sticky top-0 z-30 backdrop-blur supports-[backdrop-filter]:bg-white/70 bg-white/90 dark:bg-[#0a0a0a]/90 border-b border-black/5 dark:border-white/10">
      <div class="mx-auto max-w-7xl px-4 lg:px-8 h-20 flex items-center justify-between gap-4">
        <!-- Logo -->
        <Link href="/" class="flex items-center flex-shrink-0">
          <img src="/logo_withe.jpeg" alt="KTINA Grooming" class="h-14 w-auto" />
        </Link>

        <!-- Center: Tagline (hidden on mobile) -->
        <div class="hidden lg:flex items-center text-sm text-[#1b1b18]/70 dark:text-[#EDEDEC]/70">
          ¡Cambios de look!
        </div>

        <!-- Right: Phone + Nav -->
        <div class="flex items-center gap-4">
          <!-- Phone Number -->
          <a 
            href="tel:3205556677" 
            class="hidden sm:flex items-center gap-2 rounded-full bg-lime-400 px-4 py-2 text-sm font-bold text-neutral-900 hover:bg-lime-500 transition-colors"
          >
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
              <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
            </svg>
            3205556677
          </a>

          <!-- Navigation (desktop) -->
          <nav class="hidden lg:flex items-center gap-6 text-base">
            <Link href="/news" class="text-[#1b1b18]/80 hover:text-[#1b1b18] dark:text-[#EDEDEC]/80 dark:hover:text-[#EDEDEC]">Noticias</Link>
            <Link href="/blogs" class="text-[#1b1b18]/80 hover:text-[#1b1b18] dark:text-[#EDEDEC]/80 dark:hover:text-[#EDEDEC]">Blog</Link>
            <Link href="/servicios" class="text-[#1b1b18]/80 hover:text-[#1b1b18] dark:text-[#EDEDEC]/80 dark:hover:text-[#EDEDEC]">Servicios</Link>
          </nav>

          <!-- Booking Button -->
          <Link href="/#booking" class="inline-flex items-center rounded-full bg-pink-600 px-5 py-2 font-semibold text-white hover:bg-pink-700 text-sm">
            Reservar
          </Link>
        </div>
      </div>
    </header>

    <main class="flex-1">
      <slot />
    </main>

    <PublicFooter />
  </div>
</template>
