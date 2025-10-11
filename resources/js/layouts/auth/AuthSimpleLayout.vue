<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { home } from '@/routes';
import { Link } from '@inertiajs/vue3';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div
        class="flex min-h-svh flex-col items-center justify-center gap-6 bg-background p-6 md:p-10"
    >
        <div class="w-full max-w-sm">
            <div class="flex flex-col gap-8">
                <div class="flex flex-col items-center gap-4">
                    <Link
                        :href="home()"
                        class="flex flex-col items-center gap-2 font-medium w-full"
                    >
                        <!-- Logo wrapper: ocupa todo el ancho del form (w-full) -->
                        <div
                            class="mb-1 flex w-full items-center justify-center rounded-md logo-wrapper"
                        >
                            <!-- El logo ahora escala para llenar el ancho del wrapper -->
                            <AppLogoIcon
                                class="app-logo"
                                aria-hidden="true"
                            />
                        </div>

                        <span class="sr-only">{{ title }}</span>
                    </Link>

                    <div class="space-y-2 text-center">
                        <h1 class="text-xl font-medium">{{ title }}</h1>
                        <p class="text-center text-sm text-muted-foreground">
                            {{ description }}
                        </p>
                    </div>
                </div>

                <slot />
            </div>
        </div>
    </div>
</template>

<style scoped>
/* El wrapper tiene ya w-full max-w-sm por el padre.
   Aquí nos aseguramos que el logo ocupe todo el ancho disponible
   manteniendo la proporción y sin desbordar. */

.logo-wrapper {
  padding: 0.25rem; /* opcional: espacio alrededor del logo */
}

/* Ajusta la siguiente regla si quieres más o menos altura máxima */
.app-logo {
  width: 100%;
  height: auto;
  max-width: 100%;
  /* Si el componente AppLogoIcon acepta tamaño vía CSS interno
     esto lo respetará; object-fit garantiza que no se distorsione */
  object-fit: contain;
  display: block;
}

/* Si quieres un limite de altura (por ejemplo que no sea demasiado alto en pantallas grandes) */
@media (min-width: 768px) {
  .app-logo {
    max-height: 220px; /* ajusta a tu gusto */
    width: auto;
    max-width: 100%;
  }
}

/* Dark mode color (si el svg usa currentColor) */
.app-logo,
.logo-wrapper svg {
  color: var(--foreground);
}

.dark .app-logo,
.dark .logo-wrapper svg {
  color: white;
}
</style>
