<script setup lang="ts">
import { ref, reactive, computed, watch } from 'vue';

const props = defineProps<{
  isOpen: boolean
}>()

const emit = defineEmits<{
  close: []
}>()

// Stepper state
const stepIndex = ref(0);
const steps = [
    'Phone number',
    'Address',
    'Add pet',
    'Services',
];

// Submit state
const isSubmitting = ref(false);
const isSuccess = ref(false);

// Form state
const form = reactive({
    owner: {  phone: '' },
    address: { city: '', street: ''},
    pet: { type: 'Dog', breed: '', name: '', weight: '', coat: '' },
    services: { bath: false, groom: false, nails: false, earCleaning: false },
});

const currentStepLabel = computed(() => steps[stepIndex.value]);
const isLastStep = computed(() => stepIndex.value === steps.length - 1);

// Data sources for selects
const dogBreeds = [
    'Mixed',
    'Labrador Retriever',
    'German Shepherd',
    'Golden Retriever',
    'French Bulldog',
    'Poodle',
    'Bulldog',
    'Beagle',
    'Rottweiler',
    'Yorkshire Terrier',
];
const catBreeds = [
    'Mixed',
    'Persian',
    'Maine Coon',
    'Siamese',
    'Ragdoll',
    'Sphynx',
    'British Shorthair',
    'Bengal',
];
const availableBreeds = computed(() => (form.pet.type === 'Cat' ? catBreeds : dogBreeds));
const coatTypes = ['Short', 'Medium', 'Long', 'Curly', 'Wire', 'Hairless', 'Double Coat'];

// Breed searchable combobox state
const search = ref('');
const isBreedListOpen = ref(false);
const highlightIndex = ref(-1);
const filteredBreeds = computed(() =>
    availableBreeds.value.filter((b) => b.toLowerCase().includes(search.value.toLowerCase()))
);

const openList = () => {
    isBreedListOpen.value = true;
    highlightIndex.value = filteredBreeds.value.length ? 0 : -1;
};
const closeList = () => {
    isBreedListOpen.value = false;
};
const onBlur = () => {
    setTimeout(() => {
        isBreedListOpen.value = false;
    }, 100);
};
const selectBreed = (b: string) => {
    form.pet.breed = b;
    search.value = b;
    closeList();
};
const highlightNext = () => {
    if (!isBreedListOpen.value) {
        openList();
        return;
    }
    if (!filteredBreeds.value.length) return;
    highlightIndex.value = (highlightIndex.value + 1) % filteredBreeds.value.length;
};
const highlightPrev = () => {
    if (!isBreedListOpen.value) {
        openList();
        return;
    }
    if (!filteredBreeds.value.length) return;
    highlightIndex.value = (highlightIndex.value - 1 + filteredBreeds.value.length) % filteredBreeds.value.length;
};
const selectHighlighted = () => {
    if (highlightIndex.value >= 0 && highlightIndex.value < filteredBreeds.value.length) {
        selectBreed(filteredBreeds.value[highlightIndex.value]);
    }
};

watch(search, (v) => {
    form.pet.breed = v;
});

const validateStep = () => {
      const i = stepIndex.value;
      if (i === 0) {
          const { phone } = form.owner;
          return !!phone;
      }
      if (i === 1) {
          const { street, city } = form.address;
          return !!street && !!city;
      }
    if (i === 2) {
      const { name, breed } = form.pet;
      return !!name && !!breed;
    }
  if (i === 3) {
      const any = Object.values(form.services).some(Boolean);
      return any;
  }
  return true;
};

const handleNext = () => {
  if (!validateStep()) return;
  if (stepIndex.value < steps.length - 1) {
    stepIndex.value += 1;
  }
};

const handleBack = () => {
  if (stepIndex.value > 0) stepIndex.value -= 1;
};

const handleBookingSubmit = async () => {
  if (isSubmitting.value) return;
  isSubmitting.value = true;
  const payload = JSON.parse(JSON.stringify(form));
  try {
    const res = await fetch('/api/bookings', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: JSON.stringify(payload),
      credentials: 'same-origin',
    });

    if (!res.ok) {
      const errText = await res.text();
      console.error('Booking failed', res.status, errText);
      return;
    }

    await res.json();
    isSuccess.value = true;
  } catch (error) {
    console.error('Network error creating booking', error);
  } finally {
    isSubmitting.value = false;
  }
};

const handleClose = () => {
  emit('close');
};
</script>

<template>
  <Teleport to="body">
    <Transition
      enter-active-class="transition-opacity duration-300"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition-opacity duration-200"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="isOpen"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
        @click.self="handleClose"
      >
        <Transition
          enter-active-class="transition-all duration-300"
          enter-from-class="opacity-0 scale-95"
          enter-to-class="opacity-100 scale-100"
          leave-active-class="transition-all duration-200"
          leave-from-class="opacity-100 scale-100"
          leave-to-class="opacity-0 scale-95"
        >
          <div
            v-if="isOpen"
            class="relative w-full max-w-6xl max-h-[90vh] overflow-y-auto rounded-3xl bg-white dark:bg-neutral-900 shadow-2xl"
          >
            <!-- Close button -->
            <button
              @click="handleClose"
              class="absolute right-4 top-4 z-10 rounded-full p-2 hover:bg-neutral-100 dark:hover:bg-neutral-800 transition-colors"
              aria-label="Close modal"
            >
              <svg class="h-6 w-6 text-neutral-600 dark:text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>

            <div class="grid gap-10 lg:grid-cols-2 p-8 lg:p-10">
              <!-- Left: CTA -->
              <div class="text-center lg:text-left flex flex-col justify-center">
                <span class="inline-block rounded-full bg-indigo-100 px-3 py-1 text-xs font-medium text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300 mb-4">Clean, Fluffy, Fabulous Pets</span>
                <h2 class="text-3xl font-bold leading-tight text-neutral-900 dark:text-neutral-100 lg:text-4xl mb-4">
                  <span class="block">Grooming Day? Make</span>
                  <span class="block">It Their <span class="text-pink-600">FAVORITE TIME</span> ⚡</span>
                </h2>
                <div class="rounded-2xl bg-rose-50 dark:bg-rose-900/20 p-10 flex items-center justify-center">
                  <img src="/logo_black.jpeg" alt="KTINA Pet Grooming" class="max-h-48 object-contain" />
                </div>
              </div>

              <!-- Right: Booking Form -->
              <div class="w-full flex justify-center">
                <div v-if="!isSuccess" class="w-full rounded-2xl bg-neutral-50 dark:bg-neutral-800 p-8">
                  <div class="mb-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                      <img src="/icon.webp" alt="Brand" class="h-10 w-10 rounded-md object-cover" />
                      <div class="text-xs text-neutral-600 dark:text-neutral-300">[MPGPRO] Broward, Boca & Delray</div>
                    </div>
                    <div class="text-xs text-neutral-500">Step {{ stepIndex + 1 }} / {{ steps.length }}</div>
                  </div>

                  <h2 class="text-2xl font-semibold mb-1 dark:text-neutral-300">{{ currentStepLabel }}</h2>
                  <p class="text-sm text-neutral-600 dark:text-neutral-300 mb-6">
                    Please fill out the information below to continue your booking.
                  </p>

                  <!-- Steps -->
                  <div class="space-y-6">
                    <!-- Step 1: Phone only -->
                    <div v-if="stepIndex === 0" class="grid gap-4">
                      <div>
                        <label class="block text-sm mb-1 dark:text-neutral-300">Phone number*</label>
                        
                        <div class="flex items-center rounded-md border border-neutral-300 bg-white px-2 focus-within:ring-2 focus-within:ring-pink-500 dark:border-neutral-700 dark:bg-neutral-700">
                          <span class="px-2 text-sm text-neutral-500 dark:text-neutral-400">+57</span>
                          <input
                            v-model="form.owner.phone"
                            type="tel"
                            inputmode="tel"
                            placeholder="323 280 5247"
                            class="flex-1 bg-transparent py-2 text-sm text-neutral-800 dark:text-neutral-100 placeholder-neutral-400 focus:outline-none"
                          />
                        </div>

                        <p class="mt-2 text-[11px] text-neutral-500 dark:text-neutral-400">
                          We'll use this to confirm and send appointment updates.
                        </p>
                      </div>
                    </div>

                    <!-- Step 2: Address -->
                    <div v-else-if="stepIndex === 1" class="grid gap-4">
                      <div>
                        <label class="block text-sm mb-1 dark:text-neutral-300">City*</label>
                        <input v-model="form.address.city" type="text" class="w-full rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm dark:border-neutral-700 dark:bg-neutral-700 dark:text-neutral-100" />
                      </div>
                      <div>
                        <label class="block text-sm mb-1 dark:text-neutral-300">Street*</label>
                        <input v-model="form.address.street" type="text" class="w-full rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm dark:border-neutral-700 dark:bg-neutral-700 dark:text-neutral-100" />
                      </div>
                    </div>

                    <!-- Step 3: Add pet -->
                    <div v-else-if="stepIndex === 2" class="grid gap-4">
                      <div class="flex items-center gap-2">
                        <button type="button" @click="form.pet.type = 'Dog'" :class="['px-3 py-1.5 rounded-full text-sm', form.pet.type==='Dog' ? 'bg-pink-600 text-white' : 'bg-neutral-200 dark:bg-neutral-700 dark:text-neutral-100']">Dog</button>
                        <button type="button" @click="form.pet.type = 'Cat'" :class="['px-3 py-1.5 rounded-full text-sm', form.pet.type==='Cat' ? 'bg-pink-600 text-white' : 'bg-neutral-200 dark:bg-neutral-700 dark:text-neutral-100']">Cat</button>
                      </div>
                      <div>
                        <label class="block text-sm mb-1 dark:text-neutral-300">Breed*</label>
                        <div
                          class="relative"
                          @keydown.down.prevent="highlightNext"
                          @keydown.up.prevent="highlightPrev"
                          @keydown.enter.prevent="selectHighlighted"
                          @keydown.esc="closeList"
                        >
                          <input
                            v-model="search"
                            type="search"
                            inputmode="search"
                            placeholder="Search breed..."
                            @focus="openList"
                            @blur="onBlur"
                            aria-autocomplete="list"
                            aria-controls="breed-listbox"
                            :aria-expanded="isBreedListOpen"
                            role="combobox"
                            class="w-full rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-pink-500 dark:border-neutral-700 dark:bg-neutral-700 dark:text-neutral-100"
                          />

                          <ul
                            v-if="isBreedListOpen && filteredBreeds.length"
                            id="breed-listbox"
                            role="listbox"
                            class="absolute z-20 mt-1 w-full max-h-44 overflow-auto rounded-md border border-neutral-200 bg-white shadow-md dark:border-neutral-700 dark:bg-neutral-700"
                          >
                            <li
                              v-for="(b, idx) in filteredBreeds"
                              :key="b"
                              role="option"
                              :aria-selected="highlightIndex === idx"
                              @mousedown.prevent="selectBreed(b)"
                              @mousemove="highlightIndex = idx"
                              :class="[
                                'px-3 py-2 cursor-pointer text-sm dark:text-neutral-100',
                                highlightIndex === idx ? 'bg-pink-50 dark:bg-pink-900/40' : 'hover:bg-neutral-100 dark:hover:bg-neutral-800'
                              ]"
                            >
                              {{ b }}
                            </li>
                          </ul>
                        </div>
                      </div>
                      <div>
                        <label class="block text-sm mb-1 dark:text-neutral-300">Name*</label>
                        <input v-model="form.pet.name" type="text" class="w-full rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm dark:border-neutral-700 dark:bg-neutral-700 dark:text-neutral-100" />
                      </div>
                      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                          <label class="block text-sm mb-1 dark:text-neutral-300">Weight</label>
                          <input v-model="form.pet.weight" type="text" class="w-full rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm dark:border-neutral-700 dark:bg-neutral-700 dark:text-neutral-100" />
                        </div>
                        <div>
                          <label class="block text-sm mb-1 dark:text-neutral-300">Coat type</label>
                          <select
                            v-model="form.pet.coat"
                            class="w-full rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm dark:border-neutral-700 dark:bg-neutral-700 dark:text-neutral-100"
                          >
                            <option disabled value="">Select coat type</option>
                            <option v-for="c in coatTypes" :key="c" :value="c">{{ c }}</option>
                          </select>
                        </div>
                      </div>
                    </div>

                    <!-- Step 4: Services -->
                    <div v-else-if="stepIndex === 3" class="grid gap-3">
                      <label class="flex items-center gap-2 text-sm dark:text-neutral-300">
                        <input type="checkbox" v-model="form.services.bath" class="rounded" /> Bath
                      </label>
                      <label class="flex items-center gap-2 text-sm dark:text-neutral-300">
                        <input type="checkbox" v-model="form.services.groom" class="rounded" /> Groom
                      </label>
                      <label class="flex items-center gap-2 text-sm dark:text-neutral-300">
                        <input type="checkbox" v-model="form.services.nails" class="rounded" /> Nails
                      </label>
                      <label class="flex items-center gap-2 text-sm dark:text-neutral-300">
                        <input type="checkbox" v-model="form.services.earCleaning" class="rounded" /> Ear cleaning
                      </label>
                      <p class="text-[11px] text-neutral-500 mt-1 dark:text-neutral-400">Choose at least one service.</p>
                    </div>
                  </div>

                  <!-- Nav buttons -->
                  <div class="mt-6 flex items-center justify-between gap-3">
                    <button type="button" @click="handleBack" :disabled="stepIndex===0" class="rounded-full px-4 py-2 text-sm border border-neutral-300 text-neutral-700 disabled:opacity-50 dark:border-neutral-600 dark:text-neutral-200">
                      Back
                    </button>
                    <button v-if="!isLastStep" type="button" @click="handleNext" class="rounded-full bg-pink-600 px-5 py-2.5 text-white text-sm font-medium hover:bg-pink-700">
                      Next
                    </button>
                    <button v-else type="button" @click="handleBookingSubmit" :disabled="isSubmitting" class="rounded-full bg-pink-600 px-5 py-2.5 text-white text-sm font-medium hover:bg-pink-700 disabled:opacity-60 disabled:cursor-not-allowed">
                      <span v-if="isSubmitting" class="inline-flex items-center gap-2">
                        <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                        Sending...
                      </span>
                      <span v-else>Send booking</span>
                    </button>
                  </div>
                </div>
                <div v-else class="w-full rounded-2xl bg-white p-8 text-center shadow-sm ring-1 ring-black/10 dark:bg-neutral-800">
                  <img src="/icon.webp" alt="Brand" class="mx-auto mb-4 h-12 w-12 rounded-md object-cover" />
                  <h2 class="text-xl font-semibold mb-2 dark:text-neutral-100">Your booking was created</h2>
                  <p class="text-sm text-neutral-600 dark:text-neutral-300 mb-4">Soon our team contact you. Thank you.</p>
                  <button @click="handleClose" class="rounded-full bg-pink-600 px-5 py-2.5 text-white text-sm font-medium hover:bg-pink-700">
                    Close
                  </button>
                </div>
              </div>
            </div>
          </div>
        </Transition>
      </div>
    </Transition>
  </Teleport>
</template>
