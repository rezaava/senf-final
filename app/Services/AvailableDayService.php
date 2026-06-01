<?php

namespace App\Services;

use Carbon\Carbon;

class AvailableDayService
{
    private AvailableSlotService $slotService;

    public function __construct(AvailableSlotService $slotService)
    {
        $this->slotService = $slotService;
    }

    public function forOperator(
    int $operatorId,
    int $serviceDurationMinutes,
    Carbon $from,
    int $daysToCheck = 30
): array {
    $availableDays = [];

    // شروع از 4 روز قبل از امروز
    $startDate = $from->copy()->subDays(7);

    for ($i = 0; $i < $daysToCheck; $i++) {
        $date = $startDate->copy()->addDays($i);

        // اگر روز قبل از امروز بود، اکتیو نباشه
        if ($date->lt(Carbon::now()->startOfDay())) {
            continue;
        }

        $slots = $this->slotService->forOperator(
            operatorId: $operatorId,
            date: $date,
            durationMinutes: $serviceDurationMinutes
        );

        if (! empty($slots)) {
            $availableDays[] = $date->toDateString();
        }
    }

    return $availableDays;
}


    public function persianDayOfWeek(Carbon $date): int
    {
        return match ($date->dayOfWeek) {
            Carbon::SATURDAY => 0,
            Carbon::SUNDAY => 1,
            Carbon::MONDAY => 2,
            Carbon::TUESDAY => 3,
            Carbon::WEDNESDAY => 4,
            Carbon::THURSDAY => 5,
            Carbon::FRIDAY => 6,
        };
    }
}
