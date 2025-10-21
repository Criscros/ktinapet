<script setup lang="ts">
import { dashboard, login, register } from '@/routes';
import { Head, Link } from '@inertiajs/vue3';
import { ref, reactive, computed, watch } from 'vue';
import PublicLayout from '@/layouts/PublicLayout.vue';

defineOptions({ layout: PublicLayout });

// Booking visibility state
const showBooking = ref(false);

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

// Scroll to booking form
const scrollToBooking = () => {
    showBooking.value = true;
    setTimeout(() => {
        document.getElementById('booking-form')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }, 100);
};

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
const isOpen = ref(false);
const highlightIndex = ref(-1);
const filteredBreeds = computed(() =>
    availableBreeds.value.filter((b) => b.toLowerCase().includes(search.value.toLowerCase()))
);

const openList = () => {
    isOpen.value = true;
    highlightIndex.value = filteredBreeds.value.length ? 0 : -1;
};
const closeList = () => {
    isOpen.value = false;
};
const onBlur = () => {
    // Delay to allow click selection before blur closes the list
    setTimeout(() => {
        isOpen.value = false;
    }, 100);
};
const selectBreed = (b: string) => {
    form.pet.breed = b;
    search.value = b;
    closeList();
};
const highlightNext = () => {
    if (!isOpen.value) {
        openList();
        return;
    }
    if (!filteredBreeds.value.length) return;
    highlightIndex.value = (highlightIndex.value + 1) % filteredBreeds.value.length;
};
const highlightPrev = () => {
    if (!isOpen.value) {
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
// Keep form.pet.breed in sync when typing
watch(search, (v) => {
    form.pet.breed = v;
});

const validateStep = () => {
      const i = stepIndex.value;
      // minimal required validations per step
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
      return any; // require at least one service
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
</script>

  <template>
    <Head title="Welcome - KTINA Grooming">
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
              @click="scrollToBooking"
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
          <h2 class="mb-4 text-3xl font-bold text-white">
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
            <p class="text-sm font-medium text-white">Spa treatments</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Booking Form Section -->
    <section v-if="showBooking" id="booking-form" class="bg-neutral-50 py-16 dark:bg-neutral-800/50">
      <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="grid gap-12 lg:grid-cols-2 items-center">
          <!-- Left: CTA -->
          <div class="text-center lg:text-left">
            <span class="inline-block rounded-full bg-indigo-100 px-3 py-1 text-xs font-medium text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300 mb-4">Clean, Fluffy, Fabulous Pets</span>
            <h2 class="text-3xl font-bold leading-tight text-neutral-900 dark:text-neutral-100 lg:text-4xl mb-4">
              <span class="block">Grooming Day? Make</span>
              <span class="block">It Their <span class="text-pink-600">FAVORITE TIME</span> ⚡</span>
            </h2>
            <div class="rounded-2xl bg-rose-50 dark:bg-rose-900/20 p-8 flex items-center justify-center">
              <img src="/logo_black.jpeg" alt="KTINA Pet Grooming" class="max-h-40 object-contain" />
            </div>
          </div>

          <!-- Right: Booking Form -->
          <div class="w-full flex justify-center">
                    <div v-if="!isSuccess" class="mx-auto w-full max-w-lg rounded-2xl bg-white p-6 shadow-sm ring-1 ring-black/10 dark:bg-[#161615]">
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
                                
                                <!-- Contenedor del indicativo y campo -->
                                <div
                                class="flex items-center rounded-md border border-neutral-300 bg-white px-2 focus-within:ring-2 focus-within:ring-pink-500 dark:border-neutral-700 dark:bg-neutral-100"
                                >
                                <span class="px-2 text-sm text-neutral-500 dark:text-neutral-600">+57</span>
                                <input
                                    v-model="form.owner.phone"
                                    type="tel"
                                    inputmode="tel"
                                    placeholder="323 280 5247"
                                    class="flex-1 bg-transparent py-2 text-sm text-neutral-800 placeholder-neutral-400 focus:outline-none dark:text-neutral-900"
                                />
                                </div>

                                <p class="mt-2 text-[11px] text-neutral-500 dark:text-neutral-400">
                                We’ll use this to confirm and send appointment updates.
                                </p>
                            </div>
                            </div>

                            <!-- Step 2: Address -->
                            <div v-else-if="stepIndex === 1" class="grid gap-4">
                                <div>
                                    <label class="block text-sm mb-1 dark:text-neutral-300">City*</label>
                                    <input v-model="form.address.city" type="text" class="w-full rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm dark:border-neutral-700 dark:bg-neutral-100" />
                                </div>
                                <div>
                                    <label class="block text-sm mb-1 dark:text-neutral-300">Street*</label>
                                    <input v-model="form.address.street" type="text" class="w-full rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm dark:border-neutral-700 dark:bg-neutral-100" />
                                </div>
                    
                            </div>

                            <!-- Step 3: Add pet -->
                            <div v-else-if="stepIndex === 2" class="grid gap-4">
                                <div class="flex items-center gap-2">
                                    <button type="button" @click="form.pet.type = 'Dog'" :class="['px-3 py-1.5 rounded-full text-sm', form.pet.type==='Dog' ? 'bg-pink-600 text-white' : 'bg-neutral-200 dark:bg-neutral-800']">Dog</button>
                                    <button type="button" @click="form.pet.type = 'Cat'" :class="['px-3 py-1.5 rounded-full text-sm', form.pet.type==='Cat' ? 'bg-pink-600 text-white' : 'bg-neutral-200 dark:bg-neutral-800']">Cat</button>
                                </div>
                                <div>
                                    <label class="block text-sm mb-1">Breed*</label>
                                    <!-- Contenedor del input -->
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
                                        :aria-expanded="isOpen"
                                        role="combobox"
                                        class="w-full rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-pink-500 dark:border-neutral-700 dark:bg-neutral-100"
                                      />

                                      <!-- Lista filtrada -->
                                      <ul
                                        v-if="isOpen && filteredBreeds.length"
                                        id="breed-listbox"
                                        role="listbox"
                                        class="absolute z-20 mt-1 w-full max-h-44 overflow-auto rounded-md border border-neutral-200 bg-white shadow-md dark:border-neutral-700 dark:bg-neutral-100"
                                      >
                                        <li
                                          v-for="(b, idx) in filteredBreeds"
                                          :key="b"
                                          role="option"
                                          :aria-selected="highlightIndex === idx"
                                          @mousedown.prevent="selectBreed(b)"
                                          @mousemove="highlightIndex = idx"
                                          :class="[
                                            'px-3 py-2 cursor-pointer text-sm',
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
                                    <input v-model="form.pet.name" type="text" class="w-full rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm dark:border-neutral-700 dark:bg-neutral-100" />
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm mb-1 dark:text-neutral-300">Weight</label>
                                        <input v-model="form.pet.weight" type="text" class="w-full rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm dark:border-neutral-700 dark:bg-neutral-100" />
                                    </div>
                                    <div>
                                        <label class="block text-sm mb-1 dark:text-neutral-300">Coat type</label>
                                        <select
                                            v-model="form.pet.coat"
                                            class="w-full rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm dark:border-neutral-700 dark:bg-neutral-100"
                                        >
                                            <option disabled value="" class="dark:text-neutral-300">Select coat type</option>
                                            <option v-for="c in coatTypes" :key="c" :value="c" class="dark:text-neutral-300">{{ c }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 4: Services -->
                            <div v-else-if="stepIndex === 3" class="grid gap-3">
                                <label class="flex items-center gap-2 text-sm dark:text-neutral-300">
                                    <input type="checkbox" v-model="form.services.bath" /> Bath
                                </label>
                                <label class="flex items-center gap-2 text-sm dark:text-neutral-300">
                                    <input type="checkbox" v-model="form.services.groom" /> Groom
                                </label>
                                <label class="flex items-center gap-2 text-sm dark:text-neutral-300">
                                    <input type="checkbox" v-model="form.services.nails" /> Nails
                                </label>
                                <label class="flex items-center gap-2 text-sm dark:text-neutral-300">
                                    <input type="checkbox" v-model="form.services.earCleaning" /> Ear cleaning
                                </label>
                                <p class="text-[11px] text-neutral-500 mt-1 dark:text-neutral-400">Choose at least one service.</p>
                            </div>

                            
                        </div>

                        <!-- Nav buttons -->
                        <div class="mt-6 flex items-center justify-between gap-3">
                            <button type="button" @click="handleBack" :disabled="stepIndex===0" class="rounded-full px-4 py-2 text-sm border border-neutral-300 text-neutral-700 disabled:opacity-50 dark:border-neutral-700 dark:text-neutral-200">
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
                    <div v-else class="mx-auto w-full max-w-lg rounded-2xl bg-white p-8 text-center shadow-sm ring-1 ring-black/10 dark:bg-[#161615]">
                        <img src="/icon.webp" alt="Brand" class="mx-auto mb-4 h-12 w-12 rounded-md object-cover" />
                        <h2 class="text-xl font-semibold mb-2 dark:text-neutral-100">Your booking was created</h2>
                        <p class="text-sm text-neutral-600 dark:text-neutral-300">Soon our team contact you. Thank you.</p>
                    </div>
                </div>
        </div>
      </div>
    </section>
  </template>
