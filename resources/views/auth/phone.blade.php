@php
    use Anhskohbo\NoCaptcha\Facades\NoCaptcha;
@endphp
<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود به سیستم</title>
    <link rel="stylesheet" href="{{ asset('asset/css/bootstrap.css') }}">
    <link rel="stylesheet" href="https://lib.arvancloud.ir/bootstrap-icons/1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet"
        href="https://lib.arvancloud.ir/vazir-font/33.003/Farsi-Digits-Non-Latin/Vazirmatn-FD-NL-font-face.css">
    <link rel="stylesheet" href="{{ asset('asset/css/login.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .form-step{
            display: none;
        }

        /* Chrome, Safari, Edge */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        
        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }

    </style>
</head>

<body class="body">

    <div class="login-container">
        <div class="border rounded-4 bg-white p-5 px-3">
            <!-- مرحله 1: فرم شماره موبایل -->
            <div id="phoneForm" class="form-step fade-in">
                <div class="form-header">
                    <h5>ورود با شماره موبایل</h5>
                    <p>شماره موبایل خود را وارد کنید</p>
                </div>
                <div class="form-group">
                    <input type="number" class="form-input no-spinner" id="phoneNumber" placeholder=" " maxlength="11">
                    <label for="phoneNumber" class="form-label">شماره موبایل</label>
                </div>

                <div class="@if (!$attempts or $attempts == 0) d-none @endif" id="captcha-div">
                    <div class="d-flex align-items-center text-center">
                        <span class="w-100">{!! captcha_img('default') !!}</span>
                        <button type="button" class="btn btn-light ms-2" id="refresh-captcha">
                            ↻
                        </button>
                    </div>
                    <div class="form-group mt-3">
                        <input type="number" class="form-input no-spinner" id="captcha" placeholder=" "
                            maxlength="11">
                        <label for="captcha" class="form-label">کد امنیتی</label>
                    </div>
                </div>


                <button class="submit-btn" id="sendCodeBtn">
                    دریافت کد تایید
                </button>

                {{-- <div class="social-login">
                    <div class="social-divider">
                        یا با روش دیگر وارد شوید
                    </div>
                    <div class="social-buttons">
                        <button class="social-btn">
                            <i class="bi bi-google"></i>
                        </button>
                        <button class="social-btn">
                            <i class="bi bi-facebook"></i>
                        </button>
                        <button class="social-btn">
                            <i class="bi bi-apple"></i>
                        </button>
                    </div>
                </div> --}}
            </div>

            <!-- مرحله 2: فرم کد تایید -->
            <div id="verificationForm" class="form-step">
                <div class="form-header">
                    <h5>تایید شماره موبایل</h5>
                    <p>کد ارسال شده به شماره <span id="phoneDisplay" class="fw-bold"></span> را وارد کنید</p>
                </div>
                <button type="button" class="back-btn" id="backToPhone">
                    <i class="bi bi-arrow-right"></i> تغییر شماره موبایل
                </button>

                <div class="verification-inputs">
                    <input type="number" class="verification-input" maxlength="1" data-index="5">
                    <input type="number" class="verification-input" maxlength="1" data-index="4">
                    <input type="number" class="verification-input" maxlength="1" data-index="3">
                    <input type="number" class="verification-input" maxlength="1" data-index="2">
                    <input type="number" class="verification-input" maxlength="1" data-index="1">
                </div>

                <button class="submit-btn" id="verifyCodeBtn" disabled>
                    تایید کد
                </button>

                <div class="countdown" id="countdown">
                    ارسال مجدد کد پس از <span id="timer">120</span> ثانیه
                </div>

                <div class="resend-code">
                    <a href="#" class="resend-link" id="resendCode" style="display: none;">ارسال مجدد
                        کد</a>
                </div>
            </div>

            <!-- مرحله 3: انتخاب جنسیت (فقط برای ثبت نام اولیه) -->
            <div id="genderForm" class="form-step">
                <div class="form-header">
                    <h5>انتخاب جنسیت</h5>
                    <p>لطفا جنسیت خود را انتخاب کنید</p>
                </div>
                <button type="button" class="back-btn" id="backToVerification">
                    <i class="bi bi-arrow-right"></i> بازگشت به کد تایید
                </button>

                <div class="gender-cards">
                    <div class="gender-card male" data-gender="male">
                        <div class="gender-icon">
                            <i class="bi bi-gender-male"></i>
                        </div>
                        <div class="gender-name">مرد</div>
                    </div>
                    <div class="gender-card female" data-gender="female">
                        <div class="gender-icon">
                            <i class="bi bi-gender-female"></i>
                        </div>
                        <div class="gender-name">زن</div>
                    </div>
                </div>

                <button class="submit-btn" id="confirmGenderBtn" disabled>
                    ادامه
                </button>
            </div>

            <!-- مرحله 4: انتخاب نقش برای آرایشگر/مدیر (فقط وقتی که کاربر نقش دارد) -->
            <div id="roleSelectionForm" class="form-step">
                <div class="form-header">
                    <h5>انتخاب حالت ورود</h5>
                    <p>لطفا انتخاب کنید که چگونه می‌خواهید وارد شوید</p>
                </div>
                <button type="button" class="back-btn" id="backToGender">
                    <i class="bi bi-arrow-right"></i> بازگشت
                </button>

                <div class="role-selection-cards" id="roleSelectionCards">
                    <!-- این بخش توسط جاوااسکریپت پر می‌شود -->
                </div>

                <button class="submit-btn" id="confirmRoleSelectionBtn" disabled>
                    ادامه
                </button>
            </div>

            <!-- مرحله 5: انتخاب سالن (برای آرایشگرانی که در چند سالن کار می‌کنند) -->
            <div id="salonSelectionForm" class="form-step">
                <div class="form-header">
                    <h5>انتخاب سالن</h5>
                    <p>لطفا سالن مورد نظر خود را انتخاب کنید</p>
                </div>
                <button type="button" class="back-btn" id="backToRoleSelection">
                    <i class="bi bi-arrow-right"></i> بازگشت
                </button>

                <div class="salon-cards" id="salonCards">
                    <!-- این بخش توسط جاوااسکریپت پر می‌شود -->
                </div>

                <button class="submit-btn" id="confirmSalonBtn" disabled>
                    ورود به پنل
                </button>
            </div>
        </div>
    </div>

    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <script src="https://lib.arvancloud.ir/jquery/3.6.3/jquery.js"></script>
    <script src="{{ asset('asset/js/sweetalert2.js') }}"></script>
    <script src="{{ asset('asset/js/bootstrap.js') }}"></script>
    <script>
        document.getElementById('refresh-captcha').onclick = function() {
            fetch('/refresh-captcha')
                .then(res => res.text())
                .then(data => {
                    document.querySelector('span').innerHTML = data;
                });
        };
    </script>
    <script>
        $(document).ready(function() {
            // متغیرهای عمومی
            let countdownInterval;
            let countdownTime = 120;
            let userData = {
                phoneNumber: '',
                captcha: '',
                verificationCode: '',
                gender: '',
                userRoles: [], // نقش‌های کاربر از بک‌اند
                selectedRole: '', // نقش انتخابی کاربر
                salons: [], // لیست سالن‌های کاربر (برای آرایشگر)
                selectedSalonId: '' // سالن انتخابی
            };

            // نمایش مرحله مورد نظر
            function showStep(stepNumber) {
                $('.form-step').hide().removeClass('fade-in');

                switch (stepNumber) {
                    case 1:
                        $('#phoneForm').show().addClass('fade-in');
                        break;
                    case 2:
                        $('#verificationForm').show().addClass('fade-in');
                        break;
                    case 3:
                        $('#genderForm').show().addClass('fade-in');
                        break;
                    case 4:
                        $('#roleSelectionForm').show().addClass('fade-in');
                        break;
                    case 5:
                        $('#salonSelectionForm').show().addClass('fade-in');
                        break;
                }
            }
            // مقداردهی اولیه
            showStep(1);

            // مدیریت نوار پیشرفت
            function updateProgressBar(currentStep) {
                // آپدیت مراحل فعال
                $('.step').removeClass('active completed');

                for (let i = 1; i <= currentStep; i++) {
                    $('#step' + i).addClass('completed');
                }

                $('#step' + currentStep).addClass('active');

                // آپدیت نوار پیشرفت
                // const progress = ((currentStep - 1) / 3) * 100;
                // $('#progressBar').css('width', progress + '%');
            }



            // مدیریت فرم شماره موبایل
            $('#sendCodeBtn').click(function() {
                const phoneNumber = $('#phoneNumber').val().trim();
                const captcha = $('#captcha').val().trim();

                // اعتبارسنجی شماره موبایل
                if (!isValidPhoneNumber(phoneNumber)) {
                    showAlert('خطا', 'لطفا شماره موبایل معتبر وارد کنید (09xxxxxxxxx)', 'error');
                    return;
                }

                // ذخیره شماره موبایل
                userData.phoneNumber = phoneNumber;

                // ارسال درخواست به بک‌اند
                sendPhoneNumber(phoneNumber, captcha);
            });

            // اعتبارسنجی شماره موبایل
            function isValidPhoneNumber(phone) {
                const phoneRegex = /^09[0-9]{9}$/;
                return phoneRegex.test(phone);
            }

            // ارسال شماره موبایل به سرور
            function sendPhoneNumber(phone, captcha) {
                $('#sendCodeBtn').html('<i class="bi bi-arrow-repeat spinner"></i> در حال ارسال...');
                $('#sendCodeBtn').prop('disabled', true);

                $.ajax({
                    url: '/auth/phone', // مطابق با روت شما
                    method: 'POST',
                    data: {
                        phone: phone,
                        captcha: captcha,
                        _token: $('meta[name="csrf-token"]').attr('content') // برای Laravel
                    },
                    success: function(response) {
                        // نمایش شماره در فرم کد تایید
                        $('#phoneDisplay').text(phone);

                        // رفتن به مرحله کد تایید
                        showStep(2);
                        $(`.verification-input[data-index="1"]`).focus();

                        // شروع شمارش معکوس
                        startCountdown();

                        showAlert('موفقیت', 'کد تایید با موفقیت ارسال شد', 'success');
                    },
                    error: function(xhr) {
                        let errorMessage = 'تعداد درخواست بیش از حد مجاز!';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                        }
                        showAlert('خطا', errorMessage, 'error');
                    },
                    complete: function() {
                        $('#sendCodeBtn').html('دریافت کد تایید');
                        $('#sendCodeBtn').prop('disabled', false);
                    }
                });
            }

            // شروع شمارش معکوس
            function startCountdown() {
                clearInterval(countdownInterval);
                countdownTime = 120;
                $('#timer').text(countdownTime);
                $('#resendCode').hide();
                $('#countdown').show();

                countdownInterval = setInterval(function() {
                    countdownTime--;
                    $('#timer').text(countdownTime);

                    if (countdownTime <= 0) {
                        clearInterval(countdownInterval);
                        $('#countdown').hide();
                        $('#resendCode').show();
                    }
                }, 1000);
            }

            // بازگشت به فرم شماره موبایل
            $('#backToPhone').click(function() {
                $('#captcha-div').removeClass('d-none');
                $('#refresh-captcha').click();
                showStep(1);
                clearInterval(countdownInterval);
                resetVerificationInputs();
            });

            // ارسال مجدد کد
            $('#resendCode').click(function(e) {
                e.preventDefault();
                if ($(this).is(':disabled')) return;

                // ارسال درخواست به سرور برای ارسال مجدد کد
                resendVerificationCode();
            });

            // ارسال مجدد کد تایید
            function resendVerificationCode() {
                $('#resendCode').html('<i class="bi bi-arrow-repeat spinner"></i> در حال ارسال...');
                $('#resendCode').prop('disabled', true);

                $.ajax({
                    url: '/auth/resend',
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        startCountdown();
                        showAlert('موفقیت', 'کد جدید ارسال شد', 'success');
                    },
                    error: function(xhr) {
                        let errorMessage = 'خطا در ارسال مجدد کد';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                        }
                        showAlert('خطا', errorMessage, 'error');
                    },
                    complete: function() {
                        $('#resendCode').html('ارسال مجدد کد');
                        $('#resendCode').prop('disabled', false);
                    }
                });
            }

            // مدیریت ورودی‌های کد تایید
            $('.verification-input').on('input', function() {
                const value = $(this).val();
                const index = parseInt($(this).data('index'));

                // فقط اعداد مجاز هستند
                if (value && !/^\d+$/.test(value)) {
                    $(this).val('');
                    return;
                }

                if (value.length === 1) {
                    $(this).addClass('filled');

                    // رفتن به فیلد بعدی
                    if (index < 5) {
                        $(`.verification-input[data-index="${index + 1}"]`).focus();
                    }
                } else {
                    $(this).removeClass('filled');
                }

                // بررسی آیا همه فیلدها پر شده‌اند
                checkVerificationCode();
            });

            // مدیریت کلیدهای جهت‌دار در فیلدهای کد تایید
            $('.verification-input').on('keydown', function(e) {
                const index = parseInt($(this).data('index'));

                if (e.key === 'Backspace' && $(this).val() === '') {
                    if (index > 1) {
                        $(`.verification-input[data-index="${index - 1}"]`).focus();
                    }
                }

                if (e.key === 'ArrowRight' && index > 1) {
                    $(`.verification-input[data-index="${index + 1}"]`).focus();
                }

                if (e.key === 'ArrowLeft' && index < 5) {
                    $(`.verification-input[data-index="${index - 1}"]`).focus();
                }
            });

            // بررسی کامل بودن کد تایید
            function checkVerificationCode() {
                let allFilled = true;
                let verificationCode = '';

                $('.verification-input').each(function() {
                    if ($(this).val() === '') {
                        allFilled = false;
                    } else {
                        verificationCode += $(this).val();
                    }
                });

                if (allFilled && verificationCode.length === 5) {
                    $('#verifyCodeBtn').prop('disabled', false);
                } else {
                    $('#verifyCodeBtn').prop('disabled', true);
                }
            }

            // بازنشانی فیلدهای کد تایید
            function resetVerificationInputs() {
                $('.verification-input').val('').removeClass('filled');
                $('#verifyCodeBtn').prop('disabled', true);
            }

            // تایید کد و رفتن به مرحله بعد
            $('#verifyCodeBtn').click(function() {
                if ($(this).is(':disabled')) return;

                // جمع‌آوری کد تایید
                let verificationCode = '';
                $($(".verification-input").get().reverse()).each(function() {
                    verificationCode += $(this).val();
                });

                // ذخیره کد تایید
                userData.verificationCode = verificationCode;

                // ارسال کد به سرور برای تأیید
                verifyCodeOnServer(verificationCode);
            });

            // تأیید کد در سرور
            function verifyCodeOnServer(code) {
                $('#verifyCodeBtn').html('<i class="bi bi-arrow-repeat spinner"></i> در حال بررسی...');
                $('#verifyCodeBtn').prop('disabled', true);

                $.ajax({
                    url: '/auth/verify',
                    method: 'POST',
                    data: {
                        code: code,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log('پاسخ سرور:', response);

                        // استخراج نام نقش‌ها از آرایه آبجکت‌ها
                        const roleNames = response.user_roles ? response.user_roles.map(role => role
                            .name) : [];
                        userData.userRoles = roleNames;

                        console.log('نام نقش‌های کاربر:', roleNames);

                        // بررسی سناریوهای مختلف بر اساس پاسخ سرور
                        if (response.requires_gender) {
                            console.log('سناریو 1: کاربر جدید نیاز به انتخاب جنسیت دارد');
                            // کاربر جدید - نیاز به انتخاب جنسیت
                            showStep(3);
                        } else if (response.requires_role_selection) {
                            console.log('سناریو 2: کاربر نیاز به انتخاب نقش دارد');
                            // کاربر نیاز به انتخاب نقش دارد
                            prepareRoleSelection(roleNames);
                            showStep(4);
                        } else if (response.requires_salon_selection) {
                            console.log('سناریو 3: آرایشگر نیاز به انتخاب سالن دارد');
                            // آرایشگر - چند سالن دارد
                            userData.salons = response.salons || [];
                            prepareSalonSelection(response.salons);
                            showStep(5);
                        } else if (response.user_roles && response.user_roles.length > 0) {
                            console.log('سناریو 4: کاربر نقش دارد و نیازی به انتخاب ندارد');
                            // کاربر یک نقش دارد و نیازی به انتخاب ندارد
                            handleDirectLogin(response);
                        } else {
                            console.log('سناریو 5: هدایت مستقیم');
                            // هدایت به صفحه اصلی
                            if (response.redirect_url) {
                                window.location.href = response.redirect_url;
                            }
                        }
                        resetVerificationInputs();
                    },
                    error: function(xhr) {
                        let errorMessage = 'خطا در اعتبار سنجی';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                        }
                        showAlert('خطا', errorMessage, 'error');
                        $('#verifyCodeBtn').html('تایید کد');
                        $('#verifyCodeBtn').prop('disabled', false);
                        resetVerificationInputs();
                    },
                    complete: function() {
                        $('#verifyCodeBtn').html('تایید کد');
                        $('#verifyCodeBtn').prop('disabled', false);
                    }
                });
            }


            // بازگشت به مرحله کد تایید
            $('#backToVerification').click(function() {
                showStep(2);
            });

            // انتخاب جنسیت
            $('.gender-card').click(function() {
                $('.gender-card').removeClass('selected');
                $(this).addClass('selected');

                userData.gender = $(this).data('gender');
                $('#confirmGenderBtn').prop('disabled', false);
            });

            // بازگشت به مرحله انتخاب جنسیت
            $('#backToGender').click(function() {
                showStep(3);
            });

            // انتخاب نقش کاربر
            $('.role-card').click(function() {
                $('.role-card').removeClass('selected');
                $(this).addClass('selected');

                userData.role = $(this).data('role');
                $('#confirmRoleBtn').prop('disabled', false);
            });

            // تکمیل ثبت نام
            $('#confirmRoleBtn').click(function() {
                if ($(this).is(':disabled')) return;

                // نمایش وضعیت در حال بارگذاری
                $('#confirmRoleBtn').html(
                    '<i class="bi bi-arrow-repeat spinner"></i> در حال ثبت اطلاعات...');
                $('#confirmRoleBtn').prop('disabled', true);

                // ارسال اطلاعات کامل کاربر به سرور
                completeRegistration();
            });

            // تکمیل ثبت نام در سرور
            function completeRegistration() {
                $.ajax({
                    url: '/auth/complete-registration',
                    method: 'POST',
                    data: {
                        gender: userData.gender,
                        role: userData.role,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        showAlert('موفقیت', 'ثبت نام با موفقیت انجام شد!', 'success');

                        // بستن مودال
                        $('#loginModal').modal('hide');

                        // هدایت کاربر
                        if (response.redirect_url) {
                            setTimeout(function() {
                                window.location.href = response.redirect_url;
                            }, 1500);
                        }

                        resetForm();
                    },
                    error: function(xhr) {
                        let errorMessage = 'خطا در تکمیل ثبت نام';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                        }
                        showAlert('خطا', errorMessage, 'error');
                        $('#confirmRoleBtn').html('تکمیل ثبت نام');
                        $('#confirmRoleBtn').prop('disabled', false);
                    }
                });
            }

            // بازنشانی کامل فرم
            function resetForm() {
                $('#phoneNumber').val('');
                resetVerificationInputs();
                $('.gender-card').removeClass('selected');
                $('.role-card').removeClass('selected');
                userData = {
                    phoneNumber: '',
                    verificationCode: '',
                    gender: '',
                    role: ''
                };

                $('#confirmGenderBtn').prop('disabled', true);
                $('#confirmRoleBtn').prop('disabled', true);
                $('#verifyCodeBtn').html('تایید کد');
                $('#confirmRoleBtn').html('تکمیل ثبت نام');

                showStep(1);
                clearInterval(countdownInterval);
            }

            // نمایش آلرت
            function showAlert(title, message, type) {
                // نحوه نمایش نوار پیشرفت
                const timerInterval = 3500; // 5 ثانیه
                const swalIcon = type === 'error' ? 'error' : 'success';

                Swal.fire({
                    title: title,
                    text: message,
                    icon: swalIcon,
                    timer: timerInterval,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    width: 400,
                    didOpen: () => {
                        Swal.showLoading();
                        const b = Swal.getHtmlContainer().querySelector('b')
                        setInterval(() => {
                            const timeLeft = Swal.getTimerLeft();
                            if (timeLeft <= 0) {
                                b.innerText = 'تمام شد!';
                            }
                        }, 100);
                    },
                    willClose: () => {
                        Swal.hideLoading();
                    }
                });
            }

            // هنگامی که مودال بسته می‌شود، فرم را بازنشانی کن
            $('#loginModal').on('hidden.bs.modal', function() {
                resetForm();
            });

            // هنگامی که مودال باز می‌شود، فوکوس را روی فیلد شماره موبایل بگذار
            $('#loginModal').on('shown.bs.modal', function() {
                $('#phoneNumber').focus();
            });

            // آماده‌سازی فرم انتخاب نقش
            function prepareRoleSelection(userRoles) {
                let html = '';

                console.log('آماده‌سازی نقش‌ها برای انتخاب:', userRoles);

                // ابتدا نقش user را بررسی می‌کنیم
                if (userRoles.includes('user')) {
                    html += `
            <div class="role-selection-card" data-role="user">
                <div class="role-selection-icon">
                    <i class="bi bi-person"></i>
                </div>
                <div class="role-selection-name">ورود به عنوان کاربر عادی</div>
                <div class="role-selection-description">برای رزرو نوبت در آرایشگاه‌ها</div>
            </div>
                `;
                }

                // سپس نقش operator را بررسی می‌کنیم
                if (userRoles.includes('operator')) {
                    html += `
                    <div class="role-selection-card" data-role="user">
                <div class="role-selection-icon">
                    <i class="bi bi-person"></i>
                </div>
                <div class="role-selection-name">ورود به عنوان کاربر عادی</div>
                <div class="role-selection-description">برای رزرو نوبت در آرایشگاه‌ها</div>
            </div>
            <div class="role-selection-card" data-role="operator">
                <div class="role-selection-icon">
                    <i class="bi bi-scissors"></i>
                </div>
                <div class="role-selection-name">ورود به پنل آرایشگر</div>
                <div class="role-selection-description">برای مدیریت نوبت‌های شخصی</div>
            </div>
                `;
                }

                // سپس نقش manager را بررسی می‌کنیم
                if (userRoles.includes('manager')) {
                    html += `
                    <div class="role-selection-card" data-role="user">
                <div class="role-selection-icon">
                    <i class="bi bi-person"></i>
                </div>
                <div class="role-selection-name">ورود به عنوان کاربر عادی</div>
                <div class="role-selection-description">برای رزرو نوبت در آرایشگاه‌ها</div>
            </div>
            <div class="role-selection-card" data-role="manager">
                <div class="role-selection-icon">
                    <i class="bi bi-shop"></i>
                </div>
                <div class="role-selection-name">ورود به پنل مدیریت</div>
                <div class="role-selection-description">برای مدیریت آرایشگاه و پرسنل</div>
            </div>
                `;
                }

                $('#roleSelectionCards').html(html);

                // اگر فقط یک نقش وجود داشت، آن را به طور خودکار انتخاب کن
                if (userRoles.length === 1) {
                    setTimeout(() => {
                        $(`.role-selection-card[data-role="${userRoles[0]}"]`).addClass('selected');
                        userData.selectedRole = userRoles[0];
                        $('#confirmRoleSelectionBtn').prop('disabled', false);
                        console.log('تنها یک نقش موجود است، به طور خودکار انتخاب شد:', userRoles[0]);
                    }, 100);
                }

                // اضافه کردن رویداد کلیک
                $('.role-selection-card').off('click').on('click', function() {
                    $('.role-selection-card').removeClass('selected');
                    $(this).addClass('selected');
                    userData.selectedRole = $(this).data('role');
                    $('#confirmRoleSelectionBtn').prop('disabled', false);
                    console.log('نقش انتخاب شد:', userData.selectedRole);
                });
            }

            // آماده‌سازی فرم انتخاب سالن
            function prepareSalonSelection(salons) {
                let html = '';

                salons.forEach(function(salon, index) {
                    html += `
                <div class="salon-card" data-salon-id="${salon.id}">
                    <div class="salon-name">${salon.name}</div>
                    <div class="salon-address">${salon.address || 'آدرس مشخص نشده'}</div>
                    <div class="salon-info">
                        <span>تعداد پرسنل: ${salon.staff_count || 0}</span>
                        <span>${salon.is_primary ? 'سالن اصلی' : ''}</span>
                    </div>
                </div>
                `;
                });

                $('#salonCards').html(html);

                // اضافه کردن رویداد کلیک
                $('.salon-card').off('click').click(function() {
                    $('.salon-card').removeClass('selected');
                    $(this).addClass('selected');
                    userData.selectedSalonId = $(this).data('salon-id');
                    $('#confirmSalonBtn').prop('disabled', false);
                });
            }

            // هدایت مستقیم کاربر
            function handleDirectLogin(response) {
                console.log('هدایت مستقیم کاربر');
                if (response.redirect_url) {
                    console.log('هدایت به:', response.redirect_url);
                    window.location.href = response.redirect_url;
                } else {
                    // اگر redirect_url وجود نداشت، به صفحه پیش‌فرض هدایت کن
                    window.location.href = '/';
                }
            }

            // تایید جنسیت و رفتن به مرحله بعد
            $('#confirmGenderBtn').click(function() {
                if ($(this).is(':disabled')) return;

                // ارسال جنسیت به سرور
                saveGenderToServer();
            });

            // ذخیره جنسیت در سرور
            function saveGenderToServer() {
                $('#confirmGenderBtn').html('<i class="bi bi-arrow-repeat spinner"></i> در حال ذخیره...');
                $('#confirmGenderBtn').prop('disabled', true);

                $.ajax({
                    url: '/auth/save-gender',
                    method: 'POST',
                    data: {
                        gender: userData.gender,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // بررسی اگر کاربر نقش دیگری هم دارد
                        if (response.user_roles && response.user_roles.length > 1) {
                            prepareRoleSelection(response.user_roles);
                            showStep(4);
                        } else {
                            // هدایت به صفحه اصلی
                            if (response.redirect_url) {
                                window.location.href = response.redirect_url;
                            }
                        }
                    },
                    error: function(xhr) {
                        // خطا
                    }
                });
            }

            // تایید انتخاب نقش
            $('#confirmRoleSelectionBtn').click(function() {
                if ($(this).is(':disabled')) return;

                console.log('تایید نقش انتخاب شده:', userData.selectedRole);

                // نمایش وضعیت بارگذاری
                $('#confirmRoleSelectionBtn').html(
                    '<i class="bi bi-arrow-repeat spinner"></i> در حال بررسی...');
                $('#confirmRoleSelectionBtn').prop('disabled', true);

                // ارسال درخواست انتخاب نقش به سرور
                $.ajax({
                    url: '/auth/select-role',
                    method: 'POST',
                    data: {
                        role: userData.selectedRole,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log('پاسخ انتخاب نقش:', response);

                        if (response.success) {
                            if (response.requires_salon_selection) {
                                // نیاز به انتخاب سالن دارد
                                userData.salons = response.salons || [];
                                prepareSalonSelection(response.salons);
                                showStep(5);
                            } else if (response.redirect_url) {
                                // هدایت مستقیم
                                window.location.href = response.redirect_url;
                            } else {
                                showAlert('خطا', 'پاسخ سرور نامعتبر است', 'error');
                                $('#confirmRoleSelectionBtn').html('ادامه');
                                $('#confirmRoleSelectionBtn').prop('disabled', false);
                            }
                        } else {
                            showAlert('خطا', response.message || 'خطا در انتخاب نقش', 'error');
                            $('#confirmRoleSelectionBtn').html('ادامه');
                            $('#confirmRoleSelectionBtn').prop('disabled', false);
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'خطا در انتخاب نقش';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors).flat().join(
                                '<br>');
                        }
                        showAlert('خطا', errorMessage, 'error');
                        $('#confirmRoleSelectionBtn').html('ادامه');
                        $('#confirmRoleSelectionBtn').prop('disabled', false);
                    }
                });
            });

            // دریافت لیست سالن‌های آرایشگر
            function getOperatorSalons() {
                $('#confirmRoleSelectionBtn').html('<i class="bi bi-arrow-repeat spinner"></i> در حال بارگذاری...');
                $('#confirmRoleSelectionBtn').prop('disabled', true);

                $.ajax({
                    url: '/auth/get-operator-salons',
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.salons && response.salons.length > 1) {
                            userData.salons = response.salons;
                            prepareSalonSelection(response.salons);
                            showStep(5);
                        } else if (response.salons && response.salons.length === 1) {
                            // فقط یک سالن دارد - مستقیم وارد شود
                            userData.selectedSalonId = response.salons[0].id;
                            completeSalonSelection();
                        } else {
                            showAlert('خطا', 'شما به هیچ سالنی دسترسی ندارید', 'error');
                        }
                    },
                    error: function(xhr) {
                        // خطا
                    }
                });
            }

            // تکمیل انتخاب نقش
            function completeRoleSelection() {
                $.ajax({
                    url: '/auth/select-role',
                    method: 'POST',
                    data: {
                        role: userData.selectedRole,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.redirect_url) {
                            window.location.href = response.redirect_url;
                        }
                    },
                    error: function(xhr) {
                        // خطا
                    }
                });
            }

            // تایید انتخاب سالن
            $('#confirmSalonBtn').click(function() {
                if ($(this).is(':disabled')) return;
                completeSalonSelection();
            });

            // تکمیل انتخاب سالن
            function completeSalonSelection() {
                $('#confirmSalonBtn').html('<i class="bi bi-arrow-repeat spinner"></i> در حال ورود...');
                $('#confirmSalonBtn').prop('disabled', true);

                $.ajax({
                    url: '/auth/select-salon',
                    method: 'POST',
                    data: {
                        salon_id: userData.selectedSalonId,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.redirect_url) {
                            window.location.href = response.redirect_url;
                        }
                    },
                    error: function(xhr) {
                        // خطا
                    }
                });
            }

            // دکمه‌های بازگشت
            $('#backToRoleSelection').click(function() {
                showStep(4);
            });
        });
    </script>
</body>

</html>
