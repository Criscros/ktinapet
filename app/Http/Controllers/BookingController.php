<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Pet;
use App\Models\Booking;
use Inertia\Inertia;
use Inertia\Response;


class BookingController extends Controller
{
    /**
     * Display a listing of the bookings.
     */
    public function index(): Response
    {
        $bookings = Booking::with('customer')
            ->latest('created_at')
            ->paginate(10)
            ->through(function ($b) {
                return [
                    'id' => $b->id,
                    'date' => optional($b->date)->format('Y-m-d'),
                    'notes' => $b->notes,
                    'services' => $b->services,
                    'customer_phone' => optional($b->customer)->phone,
                    'created_at' => optional($b->created_at)->format('Y-m-d H:i'),
                ];
            });
    
        

        return Inertia::render('Bookings', [
            'bookings' => $bookings,
        ]);
    }

    /**
     * Handle incoming public booking data.
     */
    public function store(Request $request): JsonResponse
    {
        // Accept multiple shapes for phone: personal.phone, owner.phone, or flat phone
        $payload = $request->all();
        $phone = data_get($payload, 'personal.phone')
            ?? data_get($payload, 'owner.phone')
            ?? $request->string('phone')->toString();

  

        $result = DB::transaction(function () use ($payload, $phone) {
            // 1) Customer by phone
            $customer = Customer::firstOrCreate(['phone' => $phone], ['phone' => $phone]);

            // 2) Upsert Pet for this customer
            $petType = data_get($payload, 'pet.type');
            $petBreed = data_get($payload, 'pet.breed');
            $petName = data_get($payload, 'pet.name');
            $petWeight = data_get($payload, 'pet.weight');
            $petCoat = data_get($payload, 'pet.coat');

            // Use name if provided; else synthesize from type+breed to avoid duplicates without a name
            $match = ['customer_id' => $customer->id];
            if ($petName) {
                $match['name'] = $petName;
            } else {
                $match['name'] = sprintf('%s - %s', (string) $petType, (string) $petBreed);
            }

            $pet = Pet::updateOrCreate($match, [
                'customer_id' => $customer->id,
                'type' => $petType,
                'breed' => $petBreed,
                'weight' => $petWeight,
                'coat' => $petCoat,
            ]);

            // 3) Create Booking
            $services = (array) data_get($payload, 'services', []);
            $date = data_get($payload, 'date');
            $notes = data_get($payload, 'notes');

            $booking = $customer->bookings()->create([
                'date' => $date,
                'notes' => $notes,
                'services' => $services,
            ]);

            return compact('customer', 'pet', 'booking');
        });

        return response()->json([
            'message' => 'Booking saved',
            'data' => $result,
        ], 201);
    }
}
