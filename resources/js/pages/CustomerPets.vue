<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@/types';

type Pet = {
  id: number;
  name: string;
  type: string;
  breed: string;
  weight: string | null;
  coat: string | null;
  created_at: string | null;
};

type Customer = {
  id: number;
  phone: string;
  address: string | null;
};

const props = defineProps<{
  customer: Customer;
  pets: Pet[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Reservas', href: '/bookings' },
  { title: 'Mascotas del cliente', href: '#' },
];
</script>

<template>
  <Head title="Mascotas del cliente" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto rounded-xl p-4">
      <!-- Customer Info Header -->
      <div class="rounded-xl border border-sidebar-border/70 bg-white dark:bg-neutral-900 p-6">
        <div class="flex items-center gap-4">
          <div class="flex h-16 w-16 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/30">
            <svg class="h-8 w-8 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
            </svg>
          </div>
          <div class="flex-1">
            <h1 class="text-2xl font-bold text-neutral-900 dark:text-neutral-100">Mascotas del cliente</h1>
            <div class="mt-1 flex flex-wrap gap-4 text-sm text-neutral-600 dark:text-neutral-400">
              <div class="flex items-center gap-2">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                </svg>
                {{ customer.phone }}
              </div>
              <div v-if="customer.address" class="flex items-center gap-2">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                </svg>
                {{ customer.address }}
              </div>
            </div>
          </div>
          <Link
            href="/bookings"
            class="rounded-lg px-4 py-2 text-sm font-medium text-neutral-700 hover:bg-neutral-100 dark:text-neutral-300 dark:hover:bg-neutral-800 transition-colors"
          >
            Volver a Reservas
          </Link>
        </div>
      </div>

      <!-- Pets Grid -->
      <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <div
          v-for="pet in pets"
          :key="pet.id"
          class="rounded-xl border border-sidebar-border/70 bg-white dark:bg-neutral-900 p-6 hover:shadow-lg transition-shadow"
        >
          <!-- Pet Icon & Name -->
          <div class="flex items-center gap-3 mb-4">
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900/30 dark:to-purple-900/30">
              <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-2a6 6 0 100-12 6 6 0 000 12z" clip-rule="evenodd"/>
              </svg>
            </div>
            <div>
              <h3 class="text-lg font-bold text-neutral-900 dark:text-neutral-100">{{ pet.name }}</h3>
              <p class="text-sm text-neutral-500 dark:text-neutral-400">{{ pet.type }}</p>
            </div>
          </div>

          <!-- Pet Details -->
          <div class="space-y-3">
            <div class="flex items-center justify-between py-2 border-b border-sidebar-border/40">
              <span class="text-sm text-neutral-600 dark:text-neutral-400">Raza</span>
              <span class="text-sm font-medium text-neutral-900 dark:text-neutral-100">{{ pet.breed }}</span>
            </div>

            <div v-if="pet.weight" class="flex items-center justify-between py-2 border-b border-sidebar-border/40">
              <span class="text-sm text-neutral-600 dark:text-neutral-400">Peso</span>
              <span class="text-sm font-medium text-neutral-900 dark:text-neutral-100">{{ pet.weight }}</span>
            </div>

            <div v-if="pet.coat" class="flex items-center justify-between py-2 border-b border-sidebar-border/40">
              <span class="text-sm text-neutral-600 dark:text-neutral-400">Tipo de pelaje</span>
              <span class="text-sm font-medium text-neutral-900 dark:text-neutral-100">{{ pet.coat }}</span>
            </div>

            <div v-if="pet.created_at" class="flex items-center justify-between py-2">
              <span class="text-sm text-neutral-600 dark:text-neutral-400">Agregada</span>
              <span class="text-xs text-neutral-500 dark:text-neutral-400">{{ pet.created_at }}</span>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-if="!pets.length" class="col-span-full rounded-xl border border-dashed border-sidebar-border/70 bg-neutral-50 dark:bg-neutral-900/50 p-12 text-center">
          <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
          </svg>
          <h3 class="mt-4 text-lg font-medium text-neutral-900 dark:text-neutral-100">No se encontraron mascotas</h3>
          <p class="mt-2 text-sm text-neutral-500 dark:text-neutral-400">Este cliente aún no tiene mascotas registradas.</p>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
