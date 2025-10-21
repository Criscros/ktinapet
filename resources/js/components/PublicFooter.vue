<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
</script>

<template>
  <footer class="bg-black text-white">
    <div class="mx-auto max-w-7xl px-6 py-12 lg:px-8 lg:py-16">
      <div class="grid gap-12 md:grid-cols-3">
        <!-- Brand Column -->
        <div>
          <Link href="/" class="mb-6 inline-block">
            <img src="/logo_withe.jpeg" alt="KTINA Grooming" class="h-16 w-auto" />
          </Link>
          <p class="mb-6 text-sm leading-relaxed text-neutral-300">
            Es el servicio de peluquería de mascotas favorito de tu zona. Comodidad, cuidado y limpieza puerta a puerta, porque tus mascotas merecen un trato VIP.
          </p>
          <Link 
            href="/#booking" 
            class="inline-flex items-center rounded-full bg-purple-600 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-purple-700"
          >
            Reservar Una Cita
          </Link>
        </div>

        <!-- Contact Column -->
        <div>
          <h3 class="mb-4 text-lg font-semibold">Contacto</h3>
          <div class="space-y-4 text-sm text-neutral-300">
            <p class="leading-relaxed">
              ¡Estamos abiertos toda la semana!
            </p>
            <div>
              <a href="tel:3205556677" class="block text-2xl font-bold text-lime-400 hover:text-lime-300">
                320 555 66 77
              </a>
            </div>
            <div class="space-y-2">
              <div>
                <div class="font-semibold text-white">Horario de peluquería</div>
                <div>Primera cita: 8:00 a. m.</div>
                <div>Última cita: 6:00 p. m.</div>
              </div>
              <div>
                <div class="font-semibold text-white">Horario de oficina</div>
                <div>8:00 a. m. a 6:00 p. m.</div>
              </div>
              <div>
                <div class="font-semibold text-white">Horario del Chatbot</div>
                <div>(Próximamente)</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Payment & Security Column -->
        <div>
          <div class="mb-8">
            <h3 class="mb-4 text-lg font-semibold">Métodos de pago</h3>
            <div class="mb-3 flex items-center gap-3">
              <svg class="h-12 w-12 text-neutral-400" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="4" y="8" width="40" height="32" rx="4" stroke="currentColor" stroke-width="2"/>
                <path d="M4 16h40" stroke="currentColor" stroke-width="2"/>
                <rect x="8" y="24" width="12" height="8" rx="2" fill="currentColor"/>
                <rect x="24" y="26" width="16" height="2" rx="1" fill="currentColor"/>
                <rect x="24" y="30" width="12" height="2" rx="1" fill="currentColor"/>
              </svg>
            </div>
            <p class="text-sm text-neutral-400">
              Por el momento, solo aceptamos Nequi y efectivo. ¡Agradecemos su apoyo!
            </p>
          </div>

          <div>
            <h3 class="mb-4 text-lg font-semibold">Sitio seguro</h3>
            <p class="mb-4 text-sm leading-relaxed text-neutral-300">
              <span class="font-semibold text-white">Este es un sitio web seguro.</span> Toda la información que ingresa está encriptada y protegida con protocolos de seguridad SSL, estándares de la industria.
            </p>
            <div class="flex items-center gap-3">
              <div class="flex items-center gap-1 rounded bg-white px-3 py-1.5">
                <svg class="h-5 w-5 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                </svg>
                <span class="text-xs font-semibold text-neutral-800">SSL secure connection</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Bottom Bar -->
      <div class="mt-12 border-t border-neutral-800 pt-8 text-center text-xs text-neutral-500">
        <p>&copy; {{ new Date().getFullYear() }} KTINA Grooming. Todos los derechos reservados.</p>
      </div>
    </div>
  </footer>
</template>
