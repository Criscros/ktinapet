<script setup lang="ts">
import { dashboard, login, register } from '@/routes';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import PublicLayout from '@/layouts/PublicLayout.vue';
import BookingModalComponent from '@/components/BookingModalComponent.vue';

defineOptions({ layout: PublicLayout });

// Modal visibility state
const showBookingModal = ref(false);

// Open booking modal
const openBookingModal = () => {
    showBookingModal.value = true;
};

const closeBookingModal = () => {
    showBookingModal.value = false;
};
</script>

  <template>
    <Head title="Bienvenido - KTINA Grooming">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
        <link rel="icon" type="image/x-icon" href="/favico.ico" />
    </Head>

    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-purple-50 via-pink-50 to-rose-50 dark:from-purple-950/20 dark:via-pink-950/20 dark:to-rose-950/20">
      <div class="mx-auto max-w-6xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
        <div class="grid gap-12 lg:grid-cols-2 lg:gap-16 items-center">
          <!-- Left: Hero Content -->
          <div class="text-center lg:text-left">
            <div class="mb-6 inline-flex items-center gap-2 rounded-full bg-purple-100 px-4 py-2 text-sm font-medium text-purple-700 dark:bg-purple-900/30 dark:text-purple-300">
              <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
              </svg>
              ¡Cambios de look!
            </div>

            <h1 class="mb-6 text-4xl font-bold leading-tight tracking-tight text-neutral-900 dark:text-neutral-100 sm:text-5xl lg:text-6xl">
              <span class="block">Amor, tijeras y</span>
              <span class="block text-pink-600">colitas felices!</span>
            </h1>

            <p class="mb-8 text-lg text-neutral-600 dark:text-neutral-400">
              Nuestro servicio de peluquería ofrece comodidad, calidad y cuidado directamente a tu puerta, garantizando que tu mascota se vea y se sienta increíble, sin quemes ni estrés.
            </p>

            <p class="mb-8 flex items-center justify-center gap-2 text-sm text-neutral-500 dark:text-neutral-400 lg:justify-start">
              <svg class="h-5 w-5 text-lime-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              ¡Tu contacto es nuestra inspiración!
            </p>

            <button 
              @click="openBookingModal"
              class="group inline-flex items-center gap-3 rounded-full bg-purple-600 px-8 py-4 text-lg font-semibold text-white shadow-lg transition-all hover:bg-purple-700 hover:shadow-xl hover:scale-105"
            >
              ¡AGENDA TU CITA!
              <svg class="h-5 w-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
              </svg>
            </button>
          </div>

          <!-- Right: Image -->
          <div class="relative">
            <div class="relative overflow-hidden rounded-3xl bg-white p-8 shadow-2xl dark:bg-neutral-900">
              <img src="/logo_black.jpeg" alt="KTINA Pet Grooming" class="w-full rounded-2xl object-contain" />
            </div>
            <!-- Decorative elements -->
            <div class="absolute -top-4 -right-4 h-24 w-24 rounded-full bg-pink-200 opacity-50 blur-2xl dark:bg-pink-900/30"></div>
            <div class="absolute -bottom-4 -left-4 h-32 w-32 rounded-full bg-purple-200 opacity-50 blur-2xl dark:bg-purple-900/30"></div>
          </div>
        </div>
      </div>
    </section>

    <!-- Services Section -->
    <section class="bg-neutral-900 py-16">
      <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="mb-12 text-center">
          <h2 class="mb-4 text-2xl font-bold text-white">
            Desde tratamientos básicos hasta experiencias de spa, nos aseguramos de que tu mascota reciba el mejor cuidado, en un entorno tranquilo, familiar y libre de estrés.
          </h2>
          <p class="text-sm text-neutral-400">
            Tratamientos con ingredientes que cuidan la piel - creativos con sabor francés fresco y cruelty free para tu mejor amigo.
          </p>
        </div>

        <div class="grid grid-cols-2 gap-8 md:grid-cols-3 lg:grid-cols-5">
          <!-- Grooming completo -->
          <div class="flex flex-col items-center text-center">
            <div class="mb-4 flex h-24 w-24 items-center justify-center overflow-hidden rounded-full bg-lime-900/50 p-1">
              <img src="/logos/peinado.jpeg" alt="Grooming completo" class="h-full w-full rounded-full object-cover" />
            </div>
            <p class="text-sm font-medium text-white">Grooming completo</p>
          </div>

          <!-- Baño relajante -->
          <div class="flex flex-col items-center text-center">
            <div class="mb-4 flex h-24 w-24 items-center justify-center overflow-hidden rounded-full bg-lime-900/50 p-1">
              <img src="/logos/baño.jpeg" alt="Baño relajante" class="h-full w-full rounded-full object-cover" />
            </div>
            <p class="text-sm font-medium text-white">Baño relajante</p>
          </div>

          <!-- Corte de uñas -->
          <div class="flex flex-col items-center text-center">
            <div class="mb-4 flex h-24 w-24 items-center justify-center overflow-hidden rounded-full bg-lime-900/50 p-1">
              <img src="/logos/cut.jpeg" alt="Corte de uñas" class="h-full w-full rounded-full object-cover" />
            </div>
            <p class="text-sm font-medium text-white">Corte de uñas</p>
          </div>

          <!-- Limpieza de oídos -->
          <div class="flex flex-col items-center text-center">
            <div class="mb-4 flex h-24 w-24 items-center justify-center overflow-hidden rounded-full bg-lime-900/50 p-1">
              <img src="/logos/lengua.jpeg" alt="Limpieza de oídos" class="h-full w-full rounded-full object-cover" />
            </div>
            <p class="text-sm font-medium text-white">Limpieza de oídos</p>
          </div>

          <!-- Spa treatments -->
          <div class="flex flex-col items-center text-center">
            <div class="mb-4 flex h-24 w-24 items-center justify-center overflow-hidden rounded-full bg-lime-900/50 p-1">
              <img src="/logos/patas.jpeg" alt="Spa treatments" class="h-full w-full rounded-full object-cover" />
            </div>
            <p class="text-sm font-medium text-white">Tratamientos de spa</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Booking Form Section -->
    <!-- Booking Modal -->
    <BookingModalComponent :isOpen="showBookingModal" @close="closeBookingModal" />
  </template>
