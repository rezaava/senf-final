<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Organ;
use App\Models\PhoneVerification;
use App\Models\User; // اگر مدل کارمندان سالن دارید
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OtpAuthController extends Controller
{
    /**
     * Step 1: فرم ورود شماره
     */
    public function showPhoneForm()
    {
        $attempts = session()->get('login_attempts', 0);

        return view('auth.phone', compact('attempts'));
    }

    /**
     * Step 1: ارسال OTP
     */
    public function sendOtp(Request $request)
    {
        // تبدیل ارقام فارسی/عربی شماره موبایل به انگلیسی قبل از اعتبارسنجی
        $this->convertRequestNumbers($request, ['phone']);

        // شمارش دفعات ورود اشتباه
        $attempts = session()->get('login_attempts', 0);

        // اگر بیشتر از یک بار اشتباه کرده بود، کپچا رو اعتبارسنجی کن
        $rules = [
            'phone' => ['required', 'regex:/^09\d{9}$/'],
        ];

        if ($attempts >= 1) {
            $rules['captcha'] = 'required|captcha';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            // session()->put('login_attempts', $attempts + 1);

            // برای AJAX
            if ($request->expectsJson()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 422);
            }

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $phone = $request->phone;
        // $code = random_int(10000, 99999);
        $code = 12345;

        PhoneVerification::updateOrCreate(
            ['phone' => $phone],
            [
                'code' => $code,
                'expires_at' => now()->addMinutes(2),
                'attempts' => DB::raw('attempts + 1'),
                'send_count' => DB::raw('send_count + 1'),
                'ip_address' => $request->ip(),
                'user_agent' => substr($request->userAgent(), 0, 1000),
            ]
        );

        // TODO: ارسال پیامک
        // SmsService::send($phone, $code);

        session()->put('login_attempts', $attempts + 1);
        session(['otp_phone' => $phone]);

        // برای AJAX
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'کد تایید با موفقیت ارسال شد',
            ]);
        }
    }

    /**
     * Step 2: بررسی OTP
     */
    public function verifyOtp(Request $request)
    {
        // تبدیل ارقام فارسی/عربی کد وارد شده به انگلیسی قبل از اعتبارسنجی
        $this->convertRequestNumbers($request, ['code']);

        $request->validate([
            'code' => ['required', 'digits:5'],
        ]);

        $phone = session('otp_phone');
        $record = PhoneVerification::where('phone', $phone)->first();

        if (! $record) {
            if ($request->expectsJson()) {
                return response()->json([
                    'errors' => ['code' => ['کد نامعتبر است']],
                ], 422);
            }

            return back()->withErrors(['code' => 'کد نامعتبر است']);
        }

        if (now()->gt($record->expires_at)) {
            $record->delete();
            if ($request->expectsJson()) {
                return response()->json([
                    'errors' => ['code' => ['کد منقضی شده است']],
                ], 422);
            }

            return back()->withErrors(['code' => 'کد منقضی شده است']);
        }

        if ($record->attempts >= 5) {
            if ($request->expectsJson()) {
                return response()->json([
                    'errors' => ['code' => ['تعداد تلاش بیش از حد مجاز']],
                ], 422);
            }

            return back()->withErrors(['code' => 'تعداد تلاش بیش از حد مجاز']);
        }

        if ($request->code != $record->code) {
            $record->increment('attempts');
            if ($request->expectsJson()) {
                return response()->json([
                    'errors' => ['code' => ['کد وارد شده اشتباه است']],
                ], 422);
            }

            return back()->withErrors(['code' => 'کد وارد شده اشتباه است']);
        }

        // ✅ OTP معتبر است
        $user = User::where('mobile', $phone)->first();
        $isNewUser = false;

        if (! $user) {
            // کاربر جدید - فقط کاربر ایجاد می‌شود
            $user = User::create([
                'mobile' => $phone,
            ]);
            $isNewUser = true;
            // اختصاص نقش پیش‌فرض کاربر عادی
            $user->syncRole('user');
        }

        // پاکسازی
        $record->delete();
        session()->forget(['otp_phone', 'login_attempts']);

        // لاگین کاربر
        Auth::login($user);

        // برای AJAX
        if ($request->expectsJson()) {
            $responseData = [
                'success' => true,
                'requires_gender' => $isNewUser,
                'user_roles' => $user->roles,
            ];

            // اگر کاربر جدید نیست، اطلاعات بیشتر بده
            if (! $isNewUser) {
                // بررسی نیاز به انتخاب نقش
                $hasOperatorOrManager = $user->hasRole(['operator', 'manager']);

                // اگر کاربر نقش operator یا manager دارد (حتی اگر یک نقش داشته باشد)
                if ($hasOperatorOrManager) {
                    $responseData['requires_role_selection'] = true;
                    $responseData['user_roles'] = $user->roles;

                    // اگر آرایشگر است، بررسی تعداد سالن‌ها
                    if ($user->hasRole('operator')) {
                        $salons = $this->getOperatorSalonsData($user);
                        if (count($salons) > 1) {
                            $responseData['salons'] = $salons;
                            $responseData['requires_salon_selection'] = true;
                        } elseif (count($salons) === 1) {
                            $responseData['salons'] = $salons;
                            $responseData['requires_salon_selection'] = false;
                        }
                    }
                } else {
                    // فقط کاربر عادی است
                    $responseData['redirect_url'] = $this->getRedirectUrl($user, 'user');
                }
            }

            return response()->json($responseData);
        }

        // هدایت بعد از لاگین
        return $this->redirectAfterLogin($user);
    }

    /**
     * ذخیره جنسیت کاربر
     */
    public function saveGender(Request $request)
    {
        $request->validate([
            'gender' => ['required', 'in:male,female'],
        ]);

        $user = Auth::user();

        if (! $user) {
            return response()->json([
                'errors' => ['general' => ['کاربر لاگین نشده است']],
            ], 401);
        }

        // ذخیره جنسیت
        $user->gender = $request->gender;
        $user->save();

        // برگرداندن نقش‌های کاربر
        $userRoles = $user->roles;

        $responseData = [
            'success' => true,
            'message' => 'جنسیت با موفقیت ذخیره شد',
            'user_roles' => $userRoles,
        ];

        // اگر کاربر نقش operator یا manager دارد
        if ($user->hasRole(['operator', 'manager'])) {
            $responseData['requires_role_selection'] = true;
        } else {
            $responseData['redirect_url'] = $this->getRedirectUrl($user, 'user');
        }

        return response()->json($responseData);
    }

    /**
     * دریافت نقش‌های کاربر
     */
    public function getUserRoles(Request $request)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json([
                'errors' => ['general' => ['کاربر لاگین نشده است']],
            ], 401);
        }

        $userRoles = $user->roles;

        return response()->json([
            'success' => true,
            'user_roles' => $userRoles,
        ]);
    }

    /**
     * انتخاب نقش توسط کاربر
     */
    public function selectRole(Request $request)
    {
        $request->validate([
            'role' => ['required', 'in:user,operator,manager'],
        ]);

        $user = Auth::user();
        $selectedRole = $request->role;

        if (! $user) {
            return response()->json([
                'errors' => ['general' => ['کاربر لاگین نشده است']],
            ], 401);
        }

        // بررسی مجوز دسترسی به نقش انتخابی
        // if (! $user->hasRole($selectedRole)) {
        //     return response()->json([
        //         'errors' => ['role' => ['شما به این نقش دسترسی ندارید']],
        //     ], 403);
        // }

        // ذخیره نقش انتخابی در سشن (یا کوکی)
        session(['selected_role' => $selectedRole]);

        // اگر نقش آرایشگر انتخاب شده، بررسی سالن‌ها
        if ($selectedRole === 'operator') {
            $salons = $this->getOperatorSalonsData($user);

            if (count($salons) > 1) {
                return response()->json([
                    'success' => true,
                    'requires_salon_selection' => true,
                    'salons' => $salons,
                ]);
            } elseif (count($salons) === 1) {
                // فقط یک سالن دارد - ذخیره و هدایت
                session(['selected_salon_id' => $salons[0]['id']]);

                return response()->json([
                    'success' => true,
                    'redirect_url' => $this->getRedirectUrl($user, $selectedRole, $salons[0]['id']),
                ]);
            } else {
                // هیچ سالنی ندارد
                return response()->json([
                    'errors' => ['general' => ['شما به هیچ سالنی دسترسی ندارید']],
                ], 403);
            }
        }

        if ($selectedRole === 'manager') {
            $organ = $user->organs()->first(); // اولین سالن رو بگیر

            if ($organ) {
                // ✅ اینجا organ_id رو ذخیره کن
                $user->organ_id = $organ->id;
                $user->save();
            }

            return response()->json([
                'success' => true,
                'redirect_url' => $this->getRedirectUrl($user, $selectedRole),
            ]);
         }

        // برای سایر نقش‌ها
        return response()->json([
            'success' => true,
            'redirect_url' => $this->getRedirectUrl($user, $selectedRole),
        ]);
    }

    /**
     * دریافت لیست سالن‌های آرایشگر
     */
    public function getOperatorSalons(Request $request)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json([
                'errors' => ['general' => ['کاربر لاگین نشده است']],
            ], 401);
        }

        if (! $user->hasRole('operator')) {
            return response()->json([
                'errors' => ['general' => ['شما آرایشگر نیستید']],
            ], 403);
        }

        $salons = $this->getOperatorSalonsData($user);

        return response()->json([
            'success' => true,
            'salons' => $salons,
        ]);
    }

    /**
     * انتخاب سالن توسط آرایشگر
     */
    public function selectSalon(Request $request)
    {
        // تبدیل ارقام فارسی/عربی شناسه‌ی سالن به انگلیسی (احتیاط، در صورت ورود دستی)
        $this->convertRequestNumbers($request, ['salon_id']);

        $request->validate([
            'salon_id' => ['required', 'integer'],
        ]);

        $user = Auth::user();

        if (! $user) {
            return response()->json([
                'errors' => ['general' => ['کاربر لاگین نشده است']],
            ], 401);
        }

        if (! $user->hasRole('operator')) {
            return response()->json([
                'errors' => ['general' => ['شما آرایشگر نیستید']],
            ], 403);
        }

        // بررسی دسترسی کاربر به این سالن
        $hasAccess = $this->checkOperatorSalonAccess($user, $request->salon_id);

        if (! $hasAccess) {
            return response()->json([
                'errors' => ['salon_id' => ['شما به این سالن دسترسی ندارید']],
            ], 403);
        }

        // ذخیره سالن انتخابی در سشن
        session(['selected_salon_id' => $request->salon_id]);

        return response()->json([
            'success' => true,
            'redirect_url' => $this->getRedirectUrl($user, 'operator', $request->salon_id),
        ]);
    }

    /**
     * ارسال مجدد OTP
     */
    public function resendOtp(Request $request)
    {
        abort_if(! session()->has('otp_phone'), 403);

        $phone = session('otp_phone');
        $record = PhoneVerification::where('phone', $phone)->first();

        if (! $record) {
            if ($request->expectsJson()) {
                return response()->json([
                    'errors' => ['code' => ['شماره موبایل یافت نشد']],
                ], 422);
            }

            return redirect()->route('login');
        }

        if ($record->send_count >= 5) {
            if ($request->expectsJson()) {
                return response()->json([
                    'errors' => ['code' => ['تعداد ارسال بیش از حد مجاز است']],
                ], 422);
            }

            return back()->withErrors(['code' => 'تعداد ارسال بیش از حد مجاز است']);
        }

        $code = 12345;
        // $code = random_int(10000, 99999);

        $record->update([
            'code' => $code,
            'expires_at' => now()->addMinutes(2),
            'attempts' => 0,
            'send_count' => $record->send_count + 1,
            'ip_address' => $request->ip(),
            'user_agent' => substr($request->userAgent(), 0, 1000),
        ]);

        // TODO: ارسال پیامک
        // SmsService::send($phone, $code);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'کد جدید ارسال شد',
            ]);
        }

        return back()->with('success', 'کد جدید ارسال شد');
    }

    /**
     * دریافت اطلاعات سالن‌های آرایشگر
     */
    protected function getOperatorSalonsData(User $user)
    {
        $salons = $user->organs;

        return $salons;
    }

    /**
     * بررسی دسترسی آرایشگر به سالن
     */
    protected function checkOperatorSalonAccess(User $user, $salonId)
    {
        // TODO: پیاده‌سازی بررسی دسترسی
        // مثال:
        $organ = Organ::findOrFail($salonId);
        if (! $organ) {
            return false;
        }
        if (! $user->organs()->contains($organ)) {
            return false;
        }
        $user->OrganSelected()->associate($organ);
        $user->save();

        return true;
    }

    /**
     * هدایت بعد از لاگین
     */
    protected function redirectAfterLogin(User $user)
    {
        return redirect($this->getRedirectUrl($user));
    }

    /**
     * دریافت URL هدایت بر اساس نقش کاربر
     */
    protected function getRedirectUrl(User $user, $role = null, $salonId = null)
    {
        $selectedRole = $role ?: session('selected_role', 'user');

        if ($user->hasRole('admin')) {
            return route('profile');
        }
        switch ($selectedRole) {
            case 'operator':
                $salonId = $salonId ?: session('selected_salon_id');

                // TODO: برگرداندن URL پنل آرایشگر
                return route('profile', ['salon' => $salonId]);

            case 'manager':
                // TODO: برگرداندن URL پنل مدیریت سالن
                return route('profile');
            case 'admin':
                // TODO: برگرداندن URL پنل مدیریت سالن
                return route('profile');

            case 'user':
            default:
                return route('profile');
        }
    }

    /**
     * تکمیل ثبت نام (برای سازگاری با کد قدیمی - اختیاری)
     */
    public function completeRegistration(Request $request)
    {
        $request->validate([
            'gender' => ['required', 'in:male,female'],
            'role' => ['required', 'in:user,operator,manager'],
        ]);

        $user = Auth::user();

        if (! $user) {
            return response()->json([
                'errors' => ['general' => ['کاربر لاگین نشده است']],
            ], 401);
        }

        // ذخیره جنسیت
        $user->gender = $request->gender;

        // اختصاص نقش (فقط اگر کاربر جدید است)
        if ($user->roles) {
            $user->syncRoles([$request->role]);
        }

        $user->save();

        // ذخیره نقش انتخابی
        session(['selected_role' => $request->role]);

        return response()->json([
            'success' => true,
            'message' => 'ثبت نام با موفقیت تکمیل شد',
            'redirect_url' => $this->getRedirectUrl($user, $request->role),
        ]);
    }

    /**
     * تبدیل ارقام فارسی و عربی به انگلیسی برای فیلدهای مشخص‌شده در Request
     * مثال استفاده: $this->convertRequestNumbers($request, ['phone', 'code']);
     */
    protected function convertRequestNumbers(Request $request, array $fields): void
    {
        $merge = [];

        foreach ($fields as $field) {
            if ($request->has($field) && is_string($request->input($field))) {
                $merge[$field] = $this->convertToEnglishNumbers($request->input($field));
            }
        }

        if (! empty($merge)) {
            $request->merge($merge);
        }
    }

    /**
     * تبدیل رشته حاوی ارقام فارسی/عربی به معادل انگلیسی
     * همچنین فاصله‌های اضافی احتمالی را حذف می‌کند.
     */
    protected function convertToEnglishNumbers(string $input): string
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabic  = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        $input = str_replace($persian, $english, $input);
        $input = str_replace($arabic, $english, $input);

        return trim($input);
    }
}