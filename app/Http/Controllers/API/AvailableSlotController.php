<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\User;
use App\Services\AvailableDayService;
use App\Services\AvailableSlotService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AvailableSlotController extends Controller
{
    private AvailableSlotService $slotService;

    private AvailableDayService $dayService;

    public function __construct(AvailableSlotService $slotService, AvailableDayService $dayService)
    {
        $this->slotService = $slotService;
        $this->dayService = $dayService;
    }

    public function index(Request $request)
    {
        $request->validate([
            'operator_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'service_id' => 'required|exists:services,id',
        ]);

        $service = Service::findOrFail($request->service_id);

        // محاسبه مدت زمان سرویس
        $durationMinutes = $this->getServiceDuration($service);

        $date = Carbon::parse($request->date);
        $slots = $this->slotService->forOperator(
            operatorId: $request->operator_id,
            date: $date,
            durationMinutes: $durationMinutes,
        );

        return response()->json([
            'success' => true,
            'data' => [
                'date' => $date->toDateString(),
                'slots' => $slots,
                'total_slots' => count($slots),
            ],
        ]);
    }

    public function days(Request $request)
    {
        $request->validate([
            'operator_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'from' => 'required|date',
            'days_count' => 'sometimes|integer|min:1|max:60',
        ]);

        $service = Service::findOrFail($request->service_id);
        $durationMinutes = $this->getServiceDuration($service);

        $from = Carbon::parse($request->from);
        $daysCount = $request->input('days_count', 30); // پیش‌فرض 30 روز

        $availableDays = $this->dayService->forOperator(
            operatorId: $request->operator_id,
            serviceDurationMinutes: $durationMinutes,
            from: $from,
            daysToCheck: $daysCount
        );
        // $availableDays = array_map(fn ($d) => \Morilog\Jalali\Jalalian::fromCarbon(Carbon::parse($d))->format('Y-m-d'), $availableDays);

        return response()->json([
            'success' => true,
            'data' => [
                'available_dates' => $availableDays,
                'total_days' => count($availableDays),
                'from' => $from->toDateString(),
                'to_date' => $from->copy()->addDays($daysCount - 1)->toDateString(),
            ],
        ]);
    }

    public function operators(Request $request)
    {
        // فقط آرایشگرها
        $query = User::whereHasRole('operator');

        // اگر service_id داده شد، فیلتر کن
        if ($request->has('service_id')) {
            $serviceId = $request->input('service_id');

            $query->whereHas('services', function ($q) use ($serviceId) {
                $q->where('services.id', $serviceId);
            });
        }

        // فیلدهایی که می‌خواهیم برگردانیم
        $operators = $query->get(['id', 'name', 'image']);

        return response()->json([
            'success' => true,
            'data' => $operators->map(function ($operator) {
                return [
                    'id' => $operator->id,
                    'name' => $operator->name,
                    'full_name' => $operator->name,
                    'profile_image' => $operator->image ? asset($operator->image) : asset('files/no-image.png'),
                ];
            }),
        ]);
    }

    /**
     * محاسبه مدت زمان سرویس به دقیقه
     */
    private function getServiceDuration(Service $service): int
    {
        // اولویت با duration_minutes
        if ($service->duration_minutes && $service->duration_minutes > 0) {
            return $service->duration_minutes;
        }

        // سپس time
        if ($service->time) {
            try {
                $time = Carbon::createFromFormat('H:i:s', $service->time);

                return ($time->hour * 60) + $time->minute;
            } catch (\Exception $e) {
                // اگر فرمت time اشتباه بود
            }
        }

        // پیش‌فرض
        return 30;
    }
}
