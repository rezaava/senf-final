<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Contract;
use App\Models\ContractService;
use App\Models\Reservation;
use App\Models\ReservationFinancial;
use App\Models\ReservationMedia;
use App\Models\ReservationService;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReservationController extends Controller
{
    public function store(Request $request)
    {
        // Log::info('Reservation store started', [
        //     'user_id' => Auth::id(),
        //     'payload' => $request->except(['reference_image'])
        // ]);

        $request->validate([
            'operator_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'date' => 'required|date',
            'start_at' => 'required|date_format:Y-m-d H:i:s',
            'end_at' => 'required|date_format:Y-m-d H:i:s|after:start_at',
            'reference_image' => 'nullable|image|max:5120',
        ]);

        // Log::info('Reservation validation passed');

        $user = Auth::user();
        $service = Service::findOrFail($request->service_id);

        // Log::info('Service loaded', [
        //     'service_id' => $service->id
        // ]);

        DB::beginTransaction();

        try {

            // 🛒 Cart
            $cart = Cart::firstOrCreate([
                'user_id' => $user->id,
                'status' => 'active'
            ]);

            // Log::info('Cart resolved', [
            //     'cart_id' => $cart->id
            // ]);

            // 📅 Reservation
            $reservation = Reservation::create([
                'user_id' => $user->id,
                'operator_id' => $request->operator_id,
                'organ_id' => $service->organ->id,
                'start_at' => $request->start_at,
                'end_at' => $request->end_at,
                'status' => 'pending',
                'pricing_status' => $service->price_max ? 'min_paid_waiting_service' : 'fixed',
                'total_price' => $service->price ?? 0,
                'cart_id' => $cart->id,
                'reserved_until' => now()->addMinutes(20),
            ]);

            // Log::info('Reservation created', [
            //     'reservation_id' => $reservation->id
            // ]);

            // 🧾 Reservation Service
            $pricingMode = $service->price_max ? 'ranged_min' : 'fixed';
            $basePrice = $service->price ?? 0;

            $time = Carbon::createFromFormat('H:i:s', $service->time);
            $time_minutes = ($time->hour * 60) + $time->minute;

            ReservationService::create([
                'reservation_id' => $reservation->id,
                'service_id' => $service->id,
                'duration_minutes' => $time_minutes,
                'base_price' => $basePrice,
                'final_price' => $pricingMode === 'fixed' ? $basePrice : null,
                'pricing_mode' => $pricingMode,
            ]);

            // Log::info('Reservation service created');

            // 💰 Financial
            $salonShare = 0;
            $operatorShare = 0;

            $salonContract = Contract::where('to_user_id', $service->organ->Manager->id)
                ->where('status', 'approved')
                ->first();

            if ($salonContract) {
                $salonShare = intval(($basePrice * $salonContract->template->percentage) / 100);
            }

            $operatorContract = Contract::where('to_user_id', $request->operator_id)
                ->where('organ_id', $service->organ->id)
                ->where('status', 'approved')
                ->first();

            if ($operatorContract) {
                $type = $operatorContract->template->type;

                if ($type === 'fixed') {
                    $salonShare = $basePrice;
                    $operatorShare = 0;
                } elseif ($type === 'chair_rent') {
                    $operatorShare = $basePrice;
                    $salonShare = 0;
                } else {
                    $contractService = ContractService::where('contract_template_id', $operatorContract->template_id)
                        ->where('service_id', $service->id)
                        ->first();

                    $percentage = $contractService
                        ? $contractService->percentage
                        : $operatorContract->template->percentage;

                    $operatorShare = intval(($basePrice * $percentage) / 100);
                }
            }

            $platformFee = $basePrice - $salonShare - $operatorShare;

            ReservationFinancial::create([
                'reservation_id' => $reservation->id,
                'min_paid_amount' => $basePrice,
                'final_total_amount' => $pricingMode === 'fixed' ? $basePrice : null,
                'salon_share' => $salonShare,
                'operator_share' => $operatorShare,
                'platform_fee' => $platformFee,
                'salon_contract_id' => $salonContract->id ?? null,
                'operator_contract_id' => $operatorContract->id ?? null,
                'pricing_locked_at' => now(),
            ]);

            // Log::info('Reservation financial created');

            // 🖼 Media
            if ($request->hasFile('reference_image')) {
                $file = $request->file('reference_image');
                $path = $file->store('reservation_media', 'public');

                ReservationMedia::create([
                    'reservation_id' => $reservation->id,
                    'type' => 'reference_image',
                    'path' => $path,
                ]);

                // Log::info('Reservation media stored');
            }

            DB::commit();

            // Log::info('Reservation store completed successfully', [
            //     'reservation_id' => $reservation->id
            // ]);

            return response()->json(['success' => true]);

        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error('Reservation store failed', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'خطا در ثبت رزرو'
            ], 500);
        }
    }
}
