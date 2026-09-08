// ===========================
// 📌 داده‌های مورد نیاز
// ===========================
const dayNames = [
    "شنبه",
    "یک‌شنبه",
    "دوشنبه",
    "سه‌شنبه",
    "چهارشنبه",
    "پنج‌شنبه",
    "جمعه",
];
const months = [
    "فروردین",
    "اردیبهشت",
    "خرداد",
    "تیر",
    "مرداد",
    "شهریور",
    "مهر",
    "آبان",
    "آذر",
    "دی",
    "بهمن",
    "اسفند",
];

let service_name = null;

const reservationState = {
    service_id: null,
    operator_id: null,
    date: null,
    slot: null,
    operator_name: null,
};

let currentDate = new Date();
let selectedDate = null;
let selectedTime = null;
let currentStep = 1;
let selectedPaymentMethod = null;
let availableDates = [];

// ===========================
// 📌 توابع کمکی
// ===========================

function setupSteps() {
    if (serviceHasPriceRange) {
        // $("#step2").removeClass("d-none");

        $(".step-indicator").html(`
            <div class="step active">1</div>
            <div class="step-line"></div>
            <div class="step">2</div>
            <div class="step-line"></div>
            <div class="step">3</div>
        `);
    } else {
        $("#step2").addClass("d-none");

        $(".step-indicator").html(`
            <div class="step active">1</div>
            <div class="step-line"></div>
            <div class="step">2</div>
        `);
        // $("#nextStep").add("d-none");

    }

    currentStep = 1;
    updateStepIndicator();

    $("#prevStep").addClass("d-none");
}

$(document).ready(function () {
    setupSteps();
});

// تبدیل تاریخ میلادی به شمسی
function toPersianDate(gDate) {
    const g_y = gDate.getFullYear();
    const g_m = gDate.getMonth() + 1;
    const g_d = gDate.getDate();

    const g_days_in_month = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
    const j_days_in_month = [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29];

    let gy = g_y - 1600;
    let gm = g_m - 1;
    let gd = g_d - 1;

    let g_day_no =
        365 * gy +
        Math.floor((gy + 3) / 4) -
        Math.floor((gy + 99) / 100) +
        Math.floor((gy + 399) / 400);
    for (let i = 0; i < gm; ++i) g_day_no += g_days_in_month[i];
    if (gm > 1 && ((gy % 4 === 0 && gy % 100 !== 0) || gy % 400 === 0))
        ++g_day_no;
    g_day_no += gd;

    let j_day_no = g_day_no - 79;
    let j_np = Math.floor(j_day_no / 12053);
    j_day_no = j_day_no % 12053;

    let jy = 979 + 33 * j_np + 4 * Math.floor(j_day_no / 1461);
    j_day_no %= 1461;

    if (j_day_no >= 366) {
        jy += Math.floor((j_day_no - 1) / 365);
        j_day_no = (j_day_no - 1) % 365;
    }

    let i;
    for (i = 0; i < 11 && j_day_no >= j_days_in_month[i]; ++i)
        j_day_no -= j_days_in_month[i];
    let jm = i + 1;
    let jd = j_day_no + 1;

    return {
        year: jy,
        month: jm,
        monthName: months[jm - 1],
        day: jd,
    };
}

// ⬅️ جدید: تبدیل تاریخ شمسی به میلادی (معکوس toPersianDate)
function toGregorianDate(jy, jm, jd) {
    const j_days_in_month = [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29];
    const g_days_in_month = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

    let jy2 = jy - 979;

    let j_day_no =
        365 * jy2 +
        Math.floor(jy2 / 33) * 8 +
        Math.floor(((jy2 % 33) + 3) / 4);

    for (let i = 0; i < jm - 1; ++i) j_day_no += j_days_in_month[i];
    j_day_no += jd - 1;

    let g_day_no = j_day_no + 79;

    let gy = 1600 + 400 * Math.floor(g_day_no / 146097);
    g_day_no = g_day_no % 146097;

    let leap = true;
    if (g_day_no >= 36525) {
        g_day_no--;
        gy += 100 * Math.floor(g_day_no / 36524);
        g_day_no = g_day_no % 36524;

        if (g_day_no >= 365) g_day_no++;
        else leap = false;
    }

    gy += 4 * Math.floor(g_day_no / 1461);
    g_day_no %= 1461;

    if (g_day_no >= 366) {
        leap = false;
        g_day_no--;
        gy += Math.floor(g_day_no / 365);
        g_day_no = g_day_no % 365;
    }

    let gd = g_day_no + 1;
    const isLeapG = (gy % 4 === 0 && gy % 100 !== 0) || gy % 400 === 0;
    const gDaysInMonth = g_days_in_month.slice();
    gDaysInMonth[1] = isLeapG ? 29 : 28;

    let gm = 0;
    while (gm < 12 && gd > gDaysInMonth[gm]) {
        gd -= gDaysInMonth[gm];
        gm++;
    }
    gm += 1;

    return new Date(gy, gm - 1, gd);
}

function getStartOfWeek(date) {
    const d = new Date(date);
    const jsDay = d.getDay(); // 0=Sunday ... 6=Saturday

    // تبدیل JS day به شنبه‌محور
    // Saturday(6) => 0
    // Sunday(0)   => 1
    // Monday(1)   => 2
    const persianDayIndex = (jsDay + 1) % 7;

    d.setDate(d.getDate() - persianDayIndex);
    return d;
}
function formatLocalDate(date) {
    const y = date.getFullYear();
    const m = String(date.getMonth() + 1).padStart(2, "0");
    const d = String(date.getDate()).padStart(2, "0");
    return `${y}-${m}-${d}`;
}

// ===========================
// 📌 دریافت آرایشگرها از بک‌اند
// ===========================
function loadStylists(serviceId = null) {
    $("#stylistsLoader").removeClass("d-none");
    $("#stylistsList").empty();

    $.ajax({
        url: "/api/operators",
        method: "GET",
        data: { service_id: serviceId },
        success: function (response) {
            renderStylists(response.data);
        },
        error: function () {
            $("#stylistsList").html(
                '<li class="text-center text-danger">خطا در بارگذاری آرایشگرها</li>',
            );
        },
        complete: function () {
            $("#stylistsLoader").addClass("d-none");
        },
    });
}

function renderStylists(stylists) {
    const container = $("#stylistsList");
    container.empty();

    if (window.splideInstance) {
        window.splideInstance.destroy();
        window.splideInstance = null;
    }

    if (!stylists || stylists.length === 0) {
        container.html(
            '<li class="text-center text-muted w-100 py-3">اپراتوری وجود ندارد</li>'
        );

        // چون آرایشگری برای انتخاب نیست، بقیه مراحل رو مخفی/غیرفعال کن
        $("#weekContainer").addClass("d-none");
        $("#daySlider").empty();
        $("#timeSlots").html(
            '<p class="text-muted text-center w-100">اپراتوری وجود ندارد</p>'
        );
        $("#nextStep").prop("disabled", true);

        return;
    }

    $("#nextStep").prop("disabled", false);

    stylists.forEach((stylist) => {
        container.append(`
            <li class="splide__slide">
                <a href="#" class="text-decoration-none text-reset select-stylist"
                   data-id="${stylist.id}"
                   data-name="${stylist.name}">
                    <div class="d-inline-block">
                        <div class="service-card stylist-card">
                            <div class="service-icon">
                                <img src="${stylist.image || "/asset/images/no-image.png"}"
                                     alt="${stylist.name}">
                            </div>
                            ${stylist.name}
                        </div>
                    </div>
                </a>
            </li>
        `);
    });

    window.splideInstance = new Splide("#categories").mount();
}

// ===========================
// 📌 دریافت روزهای قابل رزرو
// ===========================
function loadAvailableDays(stylistId, serviceId) {
    $("#daysLoader").removeClass("d-none");
    $("#daySlider").empty();
    const fromDate = formatLocalDate(currentDate);
    $.ajax({
        url: `/api/operators/${stylistId}/available-days`,
        method: "GET",
        data: {
            operator_id: reservationState.operator_id,
            service_id: serviceId,
            from: fromDate,
        },
        success: function (response) {
            availableDates = response.data.available_dates || [];
            renderWeek(currentDate);

            $("#weekContainer").removeClass("d-none");

            // به‌روزرسانی عنوان ماه
            const persianDate = toPersianDate(currentDate);
            $("#monthTitle").text(persianDate.monthName);
        },
        error: function (error) {
            console.error("خطا در دریافت روزهای قابل رزرو:", error);
            availableDates = [];
            renderWeek(currentDate);
        },
        complete: function () {
            $("#daysLoader").addClass("d-none");
        },
    });
}

// ===========================
// 📌 نمایش هفته
// ===========================
function renderWeek(date) {
    const slider = $("#daySlider");
    slider.empty();

    const startOfWeek = getStartOfWeek(date);

    for (let i = 0; i < 7; i++) {
        const d = new Date(startOfWeek);
        d.setDate(startOfWeek.getDate() + i);

        const isoDate = formatLocalDate(d);
        const persianDate = toPersianDate(d);

        const todayIso = formatLocalDate(new Date());
        const isToday = isoDate === todayIso;
        // alert(isoDate)

        const isAvailable = availableDates.includes(isoDate);

        slider.append(`
            <div class="day-item ${!isAvailable ? "disabled" : ""} ${isToday ? "today" : ""}"
                 data-date="${isoDate}"
                 ${!isAvailable ? 'style="opacity:.5;cursor:not-allowed"' : ""}>

                <div>${dayNames[i]}</div>
                <div class="fw-bold">${persianDate.day}</div>
                <small>${persianDate.monthName}</small>
            </div>
        `);
    }

    // ⬅️ جدید: عنوان ماه رو هم اینجا آپدیت کنیم تا با تغییر ماه/هفته هماهنگ بمونه
    const titleDate = toPersianDate(date);
    $("#monthTitle").text(titleDate.monthName);
}

// ===========================
// 📌 دریافت زمان‌های قابل رزرو
// ===========================
function loadTimeSlots() {
    $("#slotsLoader").removeClass("d-none");
    $("#timeSlots").empty();

    if (!reservationState.date || !reservationState.operator_id) {
        $("#timeSlots").html(
            '<p class="text-center text-muted">لطفاً ابتدا آرایشگر و تاریخ را انتخاب کنید.</p>',
        );
        return;
    }

    $.ajax({
        url: `/api/reservations/available-slots`,
        method: "GET",
        data: {
            operator_id: reservationState.operator_id,
            service_id: reservationState.service_id,
            date: reservationState.date,
        },
        success: function (response) {
            renderSlots(response.data.slots || []);
        },
        error: function (error) {
            console.error("خطا در دریافت زمان‌ها:", error);
            $("#timeSlots").html(
                '<p class="text-center text-danger">خطا در دریافت زمان‌های قابل رزرو</p>',
            );
        },
        complete: function () {
            $("#slotsLoader").addClass("d-none");
        },
    });
}

function renderSlots(slots) {
    const container = $("#timeSlots");
    container.empty();

    if (!slots || slots.length === 0) {
        container.html(
            '<p class="text-muted text-center">هیچ نوبت خالی در این تاریخ وجود ندارد</p>',
        );
        return;
    }

    slots.forEach((slot) => {
        container.append(`
            <div class="time-slot"
                 data-start="${slot.start_at}"
                 data-end="${slot.end_at}"
                 data-duration="${slot.duration_minutes}">

                <div class="fw-bold">
                    ${slot.start} - ${slot.end}
                </div>

                <small class="text-muted">
                    ${slot.duration_minutes} دقیقه
                </small>
            </div>
        `);
    });
}

// ===========================
// 📌 رویدادهای کلیک
// ===========================

// انتخاب آرایشگر
$(document).on("click", ".select-stylist", function (e) {
    e.preventDefault();

    const stylistId = $(this).data("id");
    const stylistName = $(this).data("name");

    // ذخیره اطلاعات آرایشگر
    reservationState.operator_id = stylistId;
    reservationState.operator_name = stylistName;

    // هایلایت کردن آرایشگر انتخاب شده
    $(".stylist-card").removeClass("selected");
    $(this).find(".stylist-card").addClass("selected");

    // بارگذاری روزهای قابل رزرو برای این آرایشگر
    loadAvailableDays(stylistId, reservationState.service_id);

    // به‌روزرسانی اطلاعات در مرحله ۳
    $("#finalStaff").text(stylistName);
    $("#finalService").text(service_name);
});

// انتخاب روز
$(document).on("click", ".day-item:not(.disabled)", function () {
    $(".day-item").removeClass("active");
    $(this).addClass("active");

    reservationState.date = $(this).data("date");

    // به‌روزرسانی تاریخ در مرحله ۳
    const dateObj = new Date(reservationState.date);
    const persianDate = toPersianDate(dateObj);
    const persianDayIndex = (dateObj.getDay() + 1) % 7;
    $("#finalDate").text(
        `${dayNames[persianDayIndex]}، ${persianDate.day} ${persianDate.monthName} ${persianDate.year}`,
    );

    loadTimeSlots();
});

// انتخاب زمان
$(document).on("click", ".time-slot", function () {
    $(".time-slot").removeClass("active");
    $(this).addClass("active");

    selectedTime = $(this).data("start");
    reservationState.slot = {
        start_at: $(this).data("start"),
        end_at: $(this).data("end"),
    };

    // به‌روزرسانی زمان در مرحله ۳
    $("#finalTime").text($(this).data("start").substring(11, 16));
});

// هفته قبل و بعد
$("#prevWeek").click(function () {
    currentDate.setDate(currentDate.getDate() - 7);
    if (reservationState.operator_id) {
        loadAvailableDays(
            reservationState.operator_id,
            reservationState.service_id,
        );
    } else {
        renderWeek(currentDate);
    }
});

$("#nextWeek").click(function () {
    currentDate.setDate(currentDate.getDate() + 7);
    if (reservationState.operator_id) {
        loadAvailableDays(
            reservationState.operator_id,
            reservationState.service_id,
        );
    } else {
        renderWeek(currentDate);
    }
});

// ⬅️ جدید: رفتن به ماه قبل/بعد (روز اول همان ماه شمسی)
function goToMonth(offset) {
    const persianDate = toPersianDate(currentDate);
    let jy = persianDate.year;
    let jm = persianDate.month + offset;

    if (jm > 12) {
        jm = 1;
        jy++;
    } else if (jm < 1) {
        jm = 12;
        jy--;
    }

    // روز اول ماه (شمسی) -> تبدیل به میلادی
    currentDate = toGregorianDate(jy, jm, 1);

    if (reservationState.operator_id) {
        loadAvailableDays(
            reservationState.operator_id,
            reservationState.service_id,
        );
    } else {
        renderWeek(currentDate);
    }
}

$("#prevMonth").click(function () {
    goToMonth(-1);
});

$("#nextMonth").click(function () {
    goToMonth(1);
});

// ===========================
// 📌 مدیریت مراحل
// ===========================
$("#nextStep").click(function () {
    // مرحله 1
    if (currentStep === 1) {
        if (
            !reservationState.operator_id ||
            !reservationState.date ||
            !reservationState.slot
        ) {
            alert("لطفاً آرایشگر، تاریخ و زمان را کامل انتخاب کنید.");
            return;
        }

        goToNextStep();
        return;
    }

    // مرحله 2 فقط اگر بازه قیمتی داریم
    if (currentStep === 2 && serviceHasPriceRange) {
        if (
            !$("#noReferenceImage").is(":checked") &&
            !$("#previewImage").is(":visible")
        ) {
            if (!confirm("عکس مرجع انتخاب نشده، ادامه می‌دهید؟")) {
                return;
            }
        }

        goToNextStep();
    }
});

function goToNextStep() {
    $("#step" + currentStep).addClass("d-none");

    currentStep++;

    if (!serviceHasPriceRange && currentStep === 2) {
        currentStep = 3;
    }

    $("#step" + currentStep).removeClass("d-none");
    updateStepIndicator();

    $("#prevStep").removeClass("d-none");
    $("#nextStep").addClass("d-none");
    $("#confirmReserve").removeClass("d-none");

    if (
        (serviceHasPriceRange && currentStep === 3) ||
        (!serviceHasPriceRange && currentStep === 3)
    ) {
        $("#nextStep").addClass("d-none");
        $("#confirmReserve").removeClass("d-none");
    }
}

$("#prevStep").click(function () {
    $("#step" + currentStep).addClass("d-none");

    currentStep--;

    if (!serviceHasPriceRange && currentStep === 2) {
        currentStep = 1;
    }

    $("#step" + currentStep).removeClass("d-none");
    updateStepIndicator();

    $("#confirmReserve").addClass("d-none");
    $("#nextStep").removeClass("d-none");

    if (currentStep === 1) {
        $("#prevStep").addClass("d-none");
    }
});

function updateStepIndicator() {
    $(".step").removeClass("active").removeClass("completed");
    $(".step-line").removeClass("completed");

    for (let i = 1; i <= currentStep; i++) {
        if (i < currentStep) {
            $(`.step:nth-child(${i * 2 - 1})`).addClass("completed");
            $(`.step-line:nth-child(${i * 2})`).addClass("completed");
        } else {
            $(`.step:nth-child(${i * 2 - 1})`).addClass("active");
        }
    }
}

// ===========================
// 📌 تایید نهایی رزرو
// ===========================
$("#confirmReserve").click(function () {

    const formData = new FormData();

    // داده‌های متنی
    formData.append("operator_id", reservationState.operator_id);
    formData.append("service_id", reservationState.service_id);
    formData.append("date", reservationState.date);
    formData.append("start_at", reservationState.slot.start_at);
    formData.append("end_at", reservationState.slot.end_at);
    formData.append("payment_method", selectedPaymentMethod);

    const token = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    formData.append("_token", token);

    $.ajax({
        url: "/reservations/store",
        method: "POST",
        data: formData,
        processData: false, // مهم: اجازه نده jQuery داده‌ها رو رشته کنه
        contentType: false, // مهم: اجازه بده مرورگر خودش نوع multipart/form-data رو ست کنه
        success: function (response) {
            //alert("نوبت شما با موفقیت رزرو شد!");
            $("#reservationModal").modal("hide");
            resetForm();

            if (response.payment_url) {
                // هدایت به صفحه پرداخت آنلاین یا کیف پول
                window.location.href = response.payment_url;
            }
        },
        error: function (error) {
            console.error("خطا در رزرو:", error);
            alert("خطا در رزرو نوبت. لطفاً دوباره تلاش کنید.");
        },
    });
});

// ===========================
// 📌 بازنشانی فرم
// ===========================
function resetForm() {
    setTimeout(function () {
        $("#step1, #step2, #step3").addClass("d-none");
        $("#step1").removeClass("d-none");
        currentStep = 1;
        updateStepIndicator();
        $("#weekContainer").addClass("d-none");
        $("#daySlider").empty();
        $("#monthTitle").text("");
        $("#prevStep").addClass("d-none");
        $("#nextStep").removeClass("d-none");
        $("#confirmReserve").addClass("d-none");

        $(".day-item").removeClass("active");
        $(".time-slot").removeClass("active");
        $(".stylist-card").removeClass("selected");
        $(".payment-option").removeClass("active");

        $("#agreeTerms").prop("checked", false);
        $("#noReferenceImage").prop("checked", false);

        reservationState.operator_id = null;
        reservationState.date = null;
        reservationState.slot = null;
        selectedPaymentMethod = null;

        $("#previewImage").hide();
        $("#uploadArea").removeClass("active");
        $("#uploadArea h5").text("برای آپلود عکس اینجا کلیک کنید");
        $("#uploadArea p").text("فرمت‌های مجاز: JPG, PNG (حداکثر 5MB)");

        currentDate = new Date();
        renderWeek(currentDate);
        $("#timeSlots").html(
            '<p class="text-muted text-center">لطفاً روز مورد نظر را انتخاب کنید.</p>',
        );
    }, 500);
}

// ===========================
// 📌 رویدادهای مودال
// ===========================
$("#reservationModal").on("shown.bs.modal", function () {
    // بارگذاری آرایشگرها هنگام باز شدن مودال
    if (reservationState.service_id) {
        loadStylists(reservationState.service_id);
    } else {
        loadStylists();
    }

    currentDate = new Date();
    renderWeek(currentDate);
    $("#nextStep").prop("disabled", false);
});

$("#reservationModal").on("hidden.bs.modal", function () {
    resetForm();
});

// ===========================
// 📌 مقداردهی اولیه
// ===========================
$(document).ready(function () {
    // فرض کنید service_id از جایی گرفته می‌شود
    // reservationState.service_id = 1; // این مقدار باید از صفحه اصلی گرفته شود
    // renderWeek(currentDate);
});

document.getElementById('confirmReserve').addEventListener('click', function () {

    Swal.fire({
        icon: 'success',
        title: 'رزرو با موفقیت انجام شد',
        text: 'نوبت شما با موفقیت رزرو گردید.',
        confirmButtonText: 'ادامه می‌دهم',
        showCancelButton: true,
        cancelButtonText: 'مشاهده نوبت‌های رزرو شده'
    }).then((result) => {

        if (result.isConfirmed) {
            $("#reservationModal").modal("hide");
             resetForm();
        }

        if (result.dismiss === Swal.DismissReason.cancel) {
            // رفتن به صفحه نوبت‌های رزرو شده
            window.location.href = '/cart';
        }

    });

});