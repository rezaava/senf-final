<?php

namespace App\Services;

use App\Models\OperatorManualBlock;
use App\Models\OperatorTimeOff;
use App\Models\OperatorWorkingHour;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AvailableSlotService
{
    public function forOperator(int $operatorId, Carbon $date, int $durationMinutes): array
    {

        $dayOfWeek = $this->persianDayOfWeek($date);
        // $dayOfWeek = $date->dayOfWeek;

        // Log::info('==== forOperator ====', [
        //     'operator_id' => $operatorId,
        //     'date' => $dayOfWeek,
        //     'durationMinutes' => $durationMinutes,
        // ]);

        $workingHour = OperatorWorkingHour::query()
            ->where('user_id', $operatorId)
            ->where('day_of_week', $dayOfWeek)
            ->first();

        if (! $workingHour) {
            // Log::info('No working hours for operator', ['operator_id' => $operatorId, 'day_of_week' => $date->dayOfWeek]);

            return [];
        }

        $workStart = Carbon::createFromFormat('H:i:s', $workingHour->start_time)
            ->setDate($date->year, $date->month, $date->day);
        $workEnd = Carbon::createFromFormat('H:i:s', $workingHour->end_time)
            ->setDate($date->year, $date->month, $date->day);

        // Log::info('Working hours', ['start' => $workStart->toTimeString(), 'end' => $workEnd->toTimeString()]);

        $reservations = Reservation::query()
            ->where('operator_id', $operatorId)
            ->whereDate('start_at', $date)
            ->whereIn('status', ['pending', 'paid'])
            ->get(['start_at', 'end_at']);

        // Log::info('Reservations', $reservations->toArray());

        $manualBlocks = OperatorManualBlock::where('user_id', $operatorId)
            ->whereDate('start_at', $date)
            ->get(['start_at', 'end_at']);

        $timeOffs = OperatorTimeOff::where('user_id', $operatorId)
            ->whereDate('start_at', '<=', $date)
            ->whereDate('end_at', '>=', $date)
            ->get(['start_at', 'end_at']);

        $allBlocks = $manualBlocks->merge($timeOffs);

        // Log::info('Blocks', $allBlocks->toArray());

        $slots = [];
        $cursor = $workStart->copy();

        $now = Carbon::now();

        // برای امروز از ساعت فعلی شروع کن، برای روزهای آینده از شروع ساعت کاری
        if ($date->isToday()) {
            $nowRounded = now()->copy()->addHour()->startOfHour();

            $cursor = $workStart->copy()->max($nowRounded);
        } else {
            $cursor = $workStart->copy();
        }

        while ($cursor->lt($workEnd)) {
            $slotStart = $cursor->copy();
            $slotEnd = $slotStart->copy()->addMinutes($durationMinutes);

            if ($slotEnd->gt($workEnd)) {
                break;
            }

            // اسلات‌ها با رزروها و بلاک‌ها چک شوند
            $conflictReservation = $reservations->first(function ($res) use ($slotStart, $slotEnd) {
                return $slotStart < Carbon::parse($res->end_at) &&
                       $slotEnd > Carbon::parse($res->start_at);
            });

            $conflictBlock = $allBlocks->first(function ($block) use ($slotStart, $slotEnd) {
                return $slotStart < Carbon::parse($block->end_at) &&
                       $slotEnd > Carbon::parse($block->start_at);
            });

            if (! $conflictReservation && ! $conflictBlock) {
                $slots[] = [
                    'start' => $slotStart->format('H:i'),
                    'end' => $slotEnd->format('H:i'),
                    'start_at' => $slotStart->toDateTimeString(),
                    'end_at' => $slotEnd->toDateTimeString(),
                    'duration_minutes' => $durationMinutes,
                ];
            }

            $cursor->addMinutes(30);
        }

        // Log::info('Generated slots', $slots);

        return $slots;
    }

    /**
     * تبدیل dayOfWeek کربن به مدل شنبه‌محور
     * خروجی: 0 = شنبه ... 6 = جمعه
     */
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
