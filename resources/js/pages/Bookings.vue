<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Bookings' },
];

type Services = Record<string, boolean> | null | undefined;
type Pet = {
  id: number;
  name: string;
  type: string;
  breed: string;
};
type BookingRow = {
  id: number;
  date: string | null;
  notes: string | null;
  services: Services;
  customer_phone: string | null;
  customer_address: string | null;
  customer_id: number;
  pets: Pet[];
  status: boolean;
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

const getCsrfToken = (): string => {
  const el = document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement | null;
  return el?.content || '';
};

const handleToggleStatus = async (id: number, next: boolean) => {
  try {
    const res = await fetch(`/bookings/${id}/status`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': getCsrfToken(),
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: JSON.stringify({ status: next }),
      credentials: 'same-origin',
    });

    if (!res.ok) {
      console.error('Failed to update status');
      return;
    }

    router.reload({ only: ['bookings'] });
  } catch (e) {
    console.error(e);
  }
};

const showNotesModal = ref(false);
const modalNotesText = ref('');
const modalBookingId = ref<number | null>(null);
const modalMode = ref<'append' | 'replace'>('append');

const handleOpenNotes = (id: number, current: string, mode: 'append' | 'replace') => {
  modalBookingId.value = id;
  modalMode.value = mode;
  modalNotesText.value = mode === 'replace' ? (current || '') : '';
  showNotesModal.value = true;
};

const handleSaveNotes = async () => {
  if (!modalBookingId.value) return;
  try {
    const res = await fetch(`/bookings/${modalBookingId.value}/notes`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': getCsrfToken(),
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: JSON.stringify({ notes: modalNotesText.value, mode: modalMode.value }),
      credentials: 'same-origin',
    });

    if (!res.ok) return; // keep modal open on validation error (e.g., empty note)
    showNotesModal.value = false;
    modalBookingId.value = null;
    router.reload({ only: ['bookings'] });
  } catch {}
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
                
   
                <th class="py-2 pr-4">Phone</th>
                <th class="py-2 pr-4">Address</th>
                <th class="py-2 pr-4">Created</th>
                <th class="py-2 pr-4">Services</th>
                <th class="py-2 pr-4">Notes</th>
                <th class="py-2 pr-4">Status</th>

                <th class="py-2 pr-4">pets</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="row in props.bookings.data" :key="row.id" class="border-b border-sidebar-border/40">
                <td class="py-2 pr-4">{{ row.id }}</td>
     
                <td class="py-2 pr-4">{{ row.customer_phone  }}</td>
                <td class="py-2 pr-4">{{ row.customer_address }}</td>
                <td class="py-2 pr-4 whitespace-nowrap">{{ row.created_at || '-' }}</td>
                <td class="py-2 pr-4">{{ formatServices(row.services) }}</td>
                <td class="py-2 pr-4 max-w-[24rem] truncate" :title="row.notes || ''">
      
                  <button
                    v-if="row.notes && row.notes.length"
                    type="button"
                    class="ml-2 inline-flex items-center rounded border px-2 py-0.5 text-xs text-neutral-700 dark:text-neutral-200 border-sidebar-border/70 hover:bg-neutral-100 dark:hover:bg-neutral-800"
                    @click="() => handleOpenNotes(row.id, row.notes || '', 'replace')"
                  >Ver</button>
                  <button
                    v-else
                    type="button"
                    class="ml-2 inline-flex items-center rounded border px-2 py-0.5 text-xs text-neutral-700 dark:text-neutral-200 border-sidebar-border/70 hover:bg-neutral-100 dark:hover:bg-neutral-800"
                    @click="() => handleOpenNotes(row.id, '', 'append')"
                  >Agregar</button>
                </td>
                <td class="py-2 pr-4">
                  <!-- Custom styled toggle wired to status -->
                  <label class="inline-flex items-center cursor-pointer">
                  <input
                    type="checkbox"
                    :checked="row.status"
                    @change="(e: Event) => handleToggleStatus(row.id, (e.target as HTMLInputElement).checked)"
                    class="sr-only peer"
                  >
                  <div class="
                    relative w-11
                    h-6 bg-gray-200 
                    peer-focus:outline-none 
                    rounded-full peer 
                    dark:bg-gray-700
                    peer-checked:after:translate-x-full 
                    rtl:peer-checked:after:-translate-x-full 
                    peer-checked:after:border-white after:content-['']
                    after:absolute after:top-[2px]
                    after:start-[2px]
                    after:bg-white after:border-gray-300 
                    after:border after:rounded-full after:h-5 
                    after:w-5 after:transition-all 
                    dark:border-green-600 
                    peer-checked:bg-green-600
                    dark:peer-checked:bg-green-600"></div>
                </label>
                </td>
                <td class="py-2 pr-4">
                  <div v-if="row.pets && row.pets.length" class="flex items-center gap-2">
          
                    <Link
                      :href="`/customer/${row.customer_id}/pets`"
                      class="inline-flex items-center gap-1 rounded-md px-2 py-1 text-xs font-medium text-blue-600 hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-900/20 transition-colors"
                    >
                      <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                      </svg>
                 
                    </Link>
                  </div>
                  <span v-else class="text-xs text-neutral-400">No pets</span>
                </td>
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
          
          <div v-if="showNotesModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4">
            <div class="w-full max-w-lg rounded-lg bg-white p-4 shadow-lg dark:bg-neutral-900">
              <h3 class="mb-3 text-lg font-semibold">{{ modalMode === 'replace' ? 'Editar nota' : 'Agregar nota' }}</h3>
              <textarea
                v-model="modalNotesText"
                rows="5"
                class="w-full rounded border border-sidebar-border/70 bg-white p-2 text-sm text-neutral-900 outline-none focus:ring-2 focus:ring-neutral-300 dark:bg-neutral-900 dark:text-neutral-100"
              />
              <div class="mt-4 flex items-center justify-end gap-2">
                <button type="button" class="px-3 py-1 text-sm rounded border border-sidebar-border/70" @click="() => { showNotesModal = false; modalBookingId = null; }">Cancelar</button>
                <button type="button" class="px-3 py-1 text-sm rounded bg-neutral-900 text-white dark:bg-white dark:text-neutral-900" @click="handleSaveNotes">Guardar</button>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </AppLayout>
</template>
