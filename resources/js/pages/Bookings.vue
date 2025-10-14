<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Bookings' },
];

type Services = Record<string, boolean> | null | undefined;
type BookingRow = {
  id: number;
  date: string | null;
  notes: string | null;
  services: Services;
  customer_phone: string | null;
  created_at: string | null;
};

type Paginated<T> = {
  data: T[];
  links?: Array<{ url: string | null; label: string; active: boolean }>;
};

const props = defineProps<{ bookings: Paginated<BookingRow> }>();

const formatServices = (services: Services): string => {
  if (!services) return '-';
  const enabled = Object.entries(services)
    .filter(([, v]) => Boolean(v))
    .map(([k]) => k);
  return enabled.length ? enabled.join(', ') : '-';
};
</script>

<template>
  <Head title="Bookings" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
      <div class="flex items-center justify-between">
        <h1 class="text-xl font-semibold">Bookings</h1>
        <div class="text-sm text-neutral-500">Manage customer bookings</div>
      </div>

      <div class="relative flex-1 rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border">
        <div v-if="!props.bookings?.data?.length" class="text-sm text-neutral-500 dark:text-neutral-300">
          No bookings yet.
        </div>

        <div v-else class="flex h-full flex-col">
          <div class="overflow-x-auto flex-1">
          <table class="min-w-full text-sm">
            <thead>
              <tr class="text-left text-neutral-600 dark:text-neutral-300 border-b border-sidebar-border/70">
                <th class="py-2 pr-4">#</th>
               
   
                <th class="py-2 pr-4">Customer</th>
                <th class="py-2 pr-4">Services</th>
                <th class="py-2 pr-4">Notes</th>
                <th class="py-2 pr-4">Created</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="row in props.bookings.data" :key="row.id" class="border-b border-sidebar-border/40">
                <td class="py-2 pr-4">{{ row.id }}</td>
     
                <td class="py-2 pr-4">{{ row.customer_phone || '-' }}</td>
                <td class="py-2 pr-4">{{ formatServices(row.services) }}</td>
                <td class="py-2 pr-4 max-w-[24rem] truncate" :title="row.notes || ''">{{ row.notes || '-' }}</td>
                <td class="py-2 pr-4 whitespace-nowrap">{{ row.created_at || '-' }}</td>
              </tr>
            </tbody>
          </table>
          </div>

          <div v-if="props.bookings.links?.length" class="mt-4 flex flex-wrap items-center gap-2 self-end">
            <Link
              v-for="(l, idx) in props.bookings.links"
              :key="idx"
              :href="l.url || '#'"
              :class="[
                'px-3 py-1 rounded border text-xs',
                l.active ? 'bg-neutral-900 text-white dark:bg-white dark:text-neutral-900' : 'border-sidebar-border/70 text-neutral-700 dark:text-neutral-300'
              ]"
              :disabled="!l.url"
              preserve-scroll
              preserve-state
            >
              <span v-html="l.label" />
            </Link>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
