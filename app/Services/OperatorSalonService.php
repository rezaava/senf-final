<?php

namespace App\Services;

use App\Models\Organ;
use App\Models\User;
use Illuminate\Support\Collection;

/**
 * تمام منطق «سالن‌های یک آرایشگر» در همین کلاس جمع شده است.
 *
 * هرجا لازم شد قوانین دسترسی، فیلدهای نمایشی کارت سالن یا نحوه‌ی
 * ذخیره‌ی سالن انتخابی تغییر کند، فقط همین فایل ویرایش می‌شود و
 * کنترلر / ویو / جاوااسکریپت دست‌نخورده باقی می‌مانند.
 */
class OperatorSalonService
{
    /**
     * وضعیت عضویت فعال در جدول واسط organ_users
     * 0:waiting 1:active 2:reject
     */
    public const PIVOT_ACTIVE = 1;

    /**
     * سالن‌هایی که آرایشگر واقعاً در آن‌ها عضو فعال است.
     */
    public function availableFor(User $user): Collection
    {
        return $user->organs()
            ->wherePivot('status', self::PIVOT_ACTIVE)
            ->with('city')
            ->withCount(['users as staff_count' => fn ($q) => $q->where('organ_users.status', self::PIVOT_ACTIVE)])
            ->orderBy('organs.name')
            ->get();
    }

    /**
     * ساختار آماده‌ی نمایش برای فرانت‌اند (کارت‌های انتخاب سالن).
     *
     * @return array<int, array<string, mixed>>
     */
    public function presentFor(User $user): array
    {
        $currentId = $user->organ_id;

        return $this->availableFor($user)
            ->map(fn (Organ $organ) => $this->present($organ, $currentId))
            ->values()
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    public function present(Organ $organ, $currentId = null): array
    {
        return [
            'id' => $organ->id,
            'name' => $organ->name,
            'city' => optional($organ->city)->title,
            'address' => $organ->address ?: $organ->addres,
            'image' => $organ->image ? asset($organ->image) : null,
            'initial' => mb_substr(trim((string) $organ->name), 0, 1, 'UTF-8'),
            'phone' => $organ->phone,
            'staff_count' => (int) ($organ->staff_count ?? 0),
            'is_current' => $currentId !== null && (int) $currentId === (int) $organ->id,
        ];
    }

    /**
     * آیا این آرایشگر به این سالن دسترسی دارد؟
     */
    public function canAccess(User $user, $salonId): bool
    {
        return $user->organs()
            ->wherePivot('status', self::PIVOT_ACTIVE)
            ->whereKey($salonId)
            ->exists();
    }

    /**
     * ثبت نهایی سالن انتخاب‌شده: هم در ستون users.organ_id و هم در سشن.
     */
    public function select(User $user, $salonId): Organ
    {
        $organ = Organ::findOrFail($salonId);

        $user->organ_id = $organ->id;
        $user->save();

        session(['selected_salon_id' => $organ->id]);

        return $organ;
    }

    /**
     * وقتی کاربر به‌عنوان «کاربر عادی» وارد می‌شود، سالن فعال باید پاک شود
     * تا پنل سالن به‌اشتباه در دسترس نماند.
     */
    public function clear(User $user): void
    {
        if ($user->organ_id !== null) {
            $user->organ_id = null;
            $user->save();
        }

        session()->forget('selected_salon_id');
    }
}
