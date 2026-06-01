@extends('dashboard.layout.master')
@php
    use Carbon\Carbon;
    use Morilog\Jalali\Jalalian;
@endphp
@section('onvan')
تقویم کاری
@endsection
@section('title')
    <title>تقویم کاری ارایشگر</title>
    <link rel="stylesheet" href="{{ asset('asset/css/calender.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('body')
    <!-- main  -->
    <div class="col px-0 px-lg-5">
        <!-- tarakonesh ha -->
        <div class="row mt-4 p-2 rounded-4 shadow bg-white">
            <div class="clearfix mt-2">
                <h5 class="float-end">تقویم کاری ارایشگر</h5>
            </div>

            <div class="row g-0">
                <div class="calendar-wrapper p-0">
                    <!-- هدر با نویگیشن هفته -->
                    <div class="calendar-header d-flex flex-wrap align-items-center justify-content-between">
                        <div class="d-flex justify-content-between align-items-center gap-3">
                            <button class="today-btn" id="todayBtn"><i
                                    class="fas fa-calendar-check me-1"></i>امروز</button>
                            <button id="prevWeek"><i class="fas fa-chevron-right ms-1"></i>هفته قبل</button>
                            <button id="nextWeek">هفته بعد <i class="fas fa-chevron-left me-1"></i></button>
                        </div>
                    </div>

                    <!-- روزهای هفته (شنبه تا جمعه) -->
                    <div class="week-days" id="weekDaysContainer"></div>

                    <!-- نمای روزانه با ساعت‌ها -->
                    <div class="daily-view clearfix" id="dailyView">
                        <div class="hour-label-column" id="hourLabels"></div>
                        <div class="hour-grid" id="hourGrid">
                            <div class="hour-slots-container" id="hourSlotsContainer"></div>
                            <div class="events-layer" id="eventsLayer"></div>
                        </div>
                    </div>
                </div>

                <!-- مودال ساخت رویداد جدید -->
                <div class="modal fade" id="eventModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">رویداد جدید</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form id="eventForm" action="" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="eventTitle" class="form-label">عنوان</label>
                                        <input type="text" class="form-control" id="eventTitle"
                                            placeholder="مثلاً جلسه تیم" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <label class="form-label">ساعت شروع</label>
                                            <select class="form-select" id="startHour"></select>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label">ساعت پایان</label>
                                            <select class="form-select" id="endHour"></select>
                                        </div>
                                    </div>
                                    <div class="text-muted mt-2 small" id="dateHint"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                                    <button type="button" class="btn btn-primary" id="saveEventBtn">ذخیره</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('javaScript')
    <script src="{{ asset('date/jalalinone.js') }}"></script>
    <script src="{{ asset('date/jalalintwo.js') }}"></script>
    <script src="{{ asset('script/jQuery.js') }}"></script>

    <script>
        // 3. بلافاصله پلاگین را فعال کنید (قبل از هر کد دیگری)
        moment.loadPersian({
            usePersianDigits: false
        })
        console.log(moment().format('jYYYY/jMM/jDD'))
    </script>

    <script>
        $(document).ready(function() {
            let workHours = [];
            let reservations = [];
            let operatorId = {{ Auth::id() }}; // آرایشگر فعلی
            let timeOffs = [];
            // ---------- state ----------
            let events = []; // آرایه رویدادها {id, title, start: Date, end: Date}
            let currentSaturday = getSaturday(new Date()); // شنبه هفته جاری
            let selectedDate = new Date(); // روز انتخاب شده (همان امروز)

            // نمونه رویدادهای پیش‌فرض (برای امروز و چند روز دیگر)
            function addSampleEvents() {
                let today = new Date();
                today.setHours(0, 0, 0, 0);
                let tomorrow = new Date(today);
                tomorrow.setDate(tomorrow.getDate() + 1);

                // رویداد امروز 10 تا 11
                let e1 = {
                    id: 'e1',
                    title: 'جلسه برنامه‌ریزی',
                    start: new Date(today.getFullYear(), today.getMonth(), today.getDate(), 10, 0),
                    end: new Date(today.getFullYear(), today.getMonth(), today.getDate(), 11, 0)
                };
                // رویداد امروز 13:30 تا 14:30 (ناهار)
                let e2 = {
                    id: 'e2',
                    title: 'ناهار با تیم',
                    start: new Date(today.getFullYear(), today.getMonth(), today.getDate(), 13, 30),
                    end: new Date(today.getFullYear(), today.getMonth(), today.getDate(), 14, 30)
                };
                // رویداد فردا 9 تا 10
                let e3 = {
                    id: 'e3',
                    title: 'بررسی پروژه',
                    start: new Date(tomorrow.getFullYear(), tomorrow.getMonth(), tomorrow.getDate(), 9, 0),
                    end: new Date(tomorrow.getFullYear(), tomorrow.getMonth(), tomorrow.getDate(), 10, 0)
                };
                events = [e1, e2, e3];
            }
            addSampleEvents();

            // ---------- helper functions ----------
            function getSaturday(baseDate) {
                let d = new Date(baseDate);
                d.setHours(0, 0, 0, 0);
                let day = d.getDay(); // 0=Sun ... 6=Sat
                // فاصله تا شنبه: اگر شنبه (6) باشه 0 روز قبل، اگر یکشنبه (0) باشه 1 روز قبل و...
                let diffToSat = (day + 1) % 7; // چون شنبه ایندکس 6 داره
                d.setDate(d.getDate() - diffToSat);
                return d;
            }

            // آرایه‌ای از 7 روز (شنبه تا جمعه) از یک شنبه ورودی
            function getWeekDays(sat) {
                let days = [];
                for (let i = 0; i < 7; i++) {
                    let d = new Date(sat);
                    d.setDate(sat.getDate() + i);
                    days.push(d);
                }
                return days;
            }

            // نام فارسی روزها
            const persianWeekdays = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنجشنبه', 'جمعه'];

            // رندر هدر روزهای هفته
            function renderWeekDays() {
                let weekDays = getWeekDays(currentSaturday); // آرایه Date (شنبه تا جمعه)
                let html = '';

                weekDays.forEach((day) => {
                    let d = moment(day).format('YYYY/MM/DD'); // تاریخ شمسی با moment-jalaali
                    let m = moment(day);
                    m.locale('fa'); // مطمئن شو که فارسیه
                    let activeClass = (moment(day).isSame(selectedDate, 'day')) ? 'active' : '';

                    let dayIndex = (day.getDay() + 1) % 7; // تبدیل به 0=Sat ... 6=Fri
                    let hasWork = workHours.some(w => w.day_of_week === dayIndex);

                    let disabledClass = hasWork ? '' : 'disabled';

                    // نام روز هفته شمسی
                    let dayName = m.format('dddd'); // شنبه، یکشنبه، ...
                    let dayDate = m.format('jDD'); // روز
                    let month = m.format('jMM'); // ماه شمسی

                    html += `
                        <button class="day-card ${activeClass} ${disabledClass}" data-date="${moment(day).toISOString()}">
                            <div class="day-name">${dayName}</div>
                            <div class="day-date">${month}/${dayDate}</div>
                        </button>`;
                });

                $('#weekDaysContainer').html(html);
            }

            // رندر برچسب ساعت‌ها (00:00 تا 23:00)
            function renderHourLabels() {

                let dayIndex = (selectedDate.getDay() + 1) % 7;
                let dayWork = Array.isArray(workHours) ?
                    workHours.find(w => w.day_of_week === dayIndex) :
                    null;

                if (!dayWork) {
                    $('#hourLabels').html('');
                    return;
                }

                let startHour = parseInt(dayWork.start_time.split(':')[0]);
                let endHour = parseInt(dayWork.end_time.split(':')[0]);

                let labels = '';

                for (let h = startHour; h < endHour; h++) {
                    labels += `
            <div class="hour-label">
                ${h.toString().padStart(2, '0')}:00
            </div>`;
                }

                $('#hourLabels').html(labels);
            }

            // رندر خانه‌های ساعتی (برای انتخاب بازه)
            function renderHourSlots() {

                let dayIndex = (selectedDate.getDay() + 1) % 7;
                let dayWork = workHours.find(w => w.day_of_week === dayIndex);

                let slots = '';

                if (!dayWork) {
                    $('#hourSlotsContainer').html('<div class="text-center mt-4">این روز تعطیل است</div>');
                    return;
                }

                let startHour = parseInt(dayWork.start_time.split(':')[0]);
                let endHour = parseInt(dayWork.end_time.split(':')[0]);

                for (let h = startHour; h < endHour; h++) {
                    slots += `<div class="hour-slot" data-hour="${h}"></div>`;
                }

                $('#hourSlotsContainer').html(slots);
            }

            // رندر رویدادها برای روز انتخاب شده
            function renderEventsForDay() {

                $('#eventsLayer').empty();

                let dayIndex = (selectedDate.getDay() + 1) % 7;
                let dayWork = workHours.find(w => w.day_of_week === dayIndex);
                if (!dayWork) return;

                let workStartHour = parseInt(dayWork.start_time.split(':')[0]);
                let dayStr = moment(selectedDate).format('YYYY-MM-DD');

                // ---------- رزروها ----------
                let dayReservations = reservations.filter(r =>
                    moment(r.start_at).format('YYYY-MM-DD') === dayStr
                );

                dayReservations.forEach(r => {
                    renderBlock(r.start_at, r.end_at, workStartHour, 'reserved', ' رزرو شده توسط : ' + r
                        .costumer.name + '; بابت خدمت : ' + r.services[0].service.name);
                });

                // ---------- تایم آف ----------
                let dayTimeOffs = timeOffs.filter(t =>
                    moment(t.start_at).format('YYYY-MM-DD') === dayStr
                );

                dayTimeOffs.forEach(t => {
                    renderBlock(t.start_at, t.end_at, workStartHour, 'timeoff', t.reason);
                });
            }

            function renderBlock(start_at, end_at, workStartHour, cssClass, title) {

                let startMoment = moment(start_at);
                let endMoment = moment(end_at);

                let startHour = startMoment.hour() + startMoment.minute() / 60;
                let endHour = endMoment.hour() + endMoment.minute() / 60;

                let relativeStart = startHour - workStartHour;
                let relativeEnd = endHour - workStartHour;

                const HOUR_HEIGHT = 60;

                let top = relativeStart * HOUR_HEIGHT;
                let height = (relativeEnd - relativeStart) * HOUR_HEIGHT;

                let $eventDiv = $('<div class="event-block"></div>');
                $eventDiv.addClass(cssClass);

                $eventDiv.css({
                    top: top + 'px',
                    height: height + 'px'
                });

                $eventDiv.html(`<div class="event-title">${title}</div>`);
                $('#eventsLayer').append($eventDiv);
            }

            // بازسازی کل نمای روزانه (اسلات‌ها و ایونت‌ها)
            function renderDailyView() {
                renderHourLabels(); // اول لیبل
                renderHourSlots(); // بعد اسلات
                renderEventsForDay();
            }

            // به‌روزرسانی کامل صفحه پس از تغییر هفته یا روز
            function refreshView() {

                let weekDays = getWeekDays(currentSaturday);
                let start = moment(weekDays[0]).format('YYYY-MM-DD');
                let end = moment(weekDays[6]).format('YYYY-MM-DD');

                $.get(`/dashboard/calendar-data/${operatorId}`, {
                    start,
                    end
                }, function(res) {
                    // console.log(res);

                    workHours = res.work_hours || [];
                    reservations = res.reservations || [];
                    timeOffs = res.time_offs || [];
                    // console.log(reservations);


                    renderWeekDays();
                    renderDailyView();
                });
            }

            // ---------- انتخاب بازه با موس (drag selection) ----------
            let dragging = false;
            let dragStartHour = null;
            let dragEndHour = null;

            function clearSelectionHighlight() {
                $('.hour-slot').removeClass('selected');
            }

            function highlightRange(start, end) {
                let min = Math.min(start, end);
                let max = Math.max(start, end);
                $('.hour-slot').each(function() {
                    let h = $(this).data('hour');
                    if (h >= min && h <= max) {
                        $(this).addClass('selected');
                    } else {
                        $(this).removeClass('selected');
                    }
                });
            }

            // مودال و ساخت رویداد
            let modal = new bootstrap.Modal(document.getElementById('eventModal'));
            let selectedStartHour = null,
                selectedEndHour = null; // برای ذخیره قبل از باز کردن مودال

            function openModalWithRange(startH, endH) {
                // startH و endH همان ساعت‌های سلول هستند (0-23). endH آخرین سلول انتخاب شده است.
                let minH = Math.min(startH, endH);
                let maxH = Math.max(startH, endH);
                selectedStartHour = minH;
                selectedEndHour = maxH + 1; // تا ابتدای ساعت بعدی

                // پر کردن سلکت‌های ساعت در مودال
                let startSelect = $('#startHour').empty();
                let endSelect = $('#endHour').empty();
                for (let h = 0; h < 24; h++) {
                    let hourStr = h.toString().padStart(2, '0') + ':00';
                    startSelect.append(`<option value="${h}" ${h === minH ? 'selected' : ''}>${hourStr}</option>`);
                    endSelect.append(
                        `<option value="${h + 1}" ${h + 1 === maxH + 1 ? 'selected' : ''}>${(h + 1).toString().padStart(2, '0')}:00</option>`
                    );
                }
                // تاریخ را در hint نمایش بده
                let d = moment(selectedDate); // selectedDate یک Date object هست
                d.locale('fa'); // فارسی
                $('#dateHint').text(`تاریخ: ${d.format('jYYYY/jMM/jDD')}`);
                modal.show();
            }

            // رویدادهای موس
            $('#hourSlotsContainer')
                .on('mousedown', '.hour-slot', function(e) {
                    e.preventDefault(); // جلوگیری از انتخاب متن
                    dragging = true;
                    dragStartHour = $(this).data('hour');
                    dragEndHour = dragStartHour;
                    clearSelectionHighlight();
                    $(this).addClass('selected');
                })
                .on('mouseenter', '.hour-slot', function() {
                    if (!dragging) return;
                    dragEndHour = $(this).data('hour');
                    highlightRange(dragStartHour, dragEndHour);
                });

            $(window).on('mouseup', function() {
                function isBlockedByTimeOff(startH, endH) {

                    let startMoment = moment(selectedDate).hour(startH);
                    let endMoment = moment(selectedDate).hour(endH + 1);

                    return timeOffs.some(t => {
                        let tStart = moment(t.start_at);
                        let tEnd = moment(t.end_at);

                        return startMoment.isBefore(tEnd) && endMoment.isAfter(tStart);
                    });
                }
                if (isBlockedByTimeOff(dragStartHour, dragEndHour)) {
                    alert('این بازه در مرخصی قرار دارد');
                    clearSelectionHighlight();
                    return;
                }

                if (dragging && dragStartHour !== null && dragEndHour !== null) {
                    // باز کردن مودال با بازه انتخاب شده
                    openModalWithRange(dragStartHour, dragEndHour);
                }
                // پاک کردن وضعیت درگ
                dragging = false;
                dragStartHour = null;
                dragEndHour = null;
                // حذف هایلایت بعد از بسته شدن مودال (یا الان)
                // اما اگر مودال باز بشه، هایلایت رو نگه میداریم تا کاربر ببینه
                // ولی بعد از ذخیره یا انصراف باید حذف بشه. در handlers مودال پاک می‌کنیم.
            });

            // ذخیره رویداد جدید
            $('#saveEventBtn').on('click', function() {

                let reason = $('#eventTitle').val().trim();
                if (!reason) {
                    alert('لطفاً دلیل را وارد کنید');
                    return;
                }

                let startHourVal = parseInt($('#startHour').val());
                let endHourVal = parseInt($('#endHour').val());

                if (endHourVal <= startHourVal) {
                    alert('ساعت پایان باید بعد از شروع باشد');
                    return;
                }

                let startMoment = moment(selectedDate)
                    .hour(startHourVal)
                    .minute(0)
                    .second(0);

                let endMoment = moment(selectedDate)
                    .hour(endHourVal)
                    .minute(0)
                    .second(0);

                $.ajax({
                    url: '{{ route('time_off') }}',
                    method: 'POST',
                    data: {
                        user_id: operatorId,
                        start_at: startMoment.format('YYYY-MM-DD HH:mm:ss'),
                        end_at: endMoment.format('YYYY-MM-DD HH:mm:ss'),
                        reason: reason,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {

                        modal.hide();
                        $('#eventTitle').val('');

                        // رفرش تقویم
                        refreshView();

                    },
                    error: function(err) {
                        alert('خطا در ثبت تایم آف');
                        console.log(err);
                    }
                });

            });

            // بعد از بسته شدن مودال، هایلایت پاک بشه
            $('#eventModal').on('hidden.bs.modal', function() {
                clearSelectionHighlight();
            });

            // ---------- تغییر روز با کلیک روی day-card ----------
            $(document).on('click', '.day-card', function() {
                let dateStr = $(this).data('date');
                selectedDate = new Date(dateStr);
                renderWeekDays(); // برای آپدیت کلاس active
                renderDailyView();
            });

            // هفته قبل و بعد
            $('#prevWeek').click(function() {
                currentSaturday.setDate(currentSaturday.getDate() - 7);
                // اگر selectedDate بیرون از هفته جدید افتاد، آن را روی شنبه هفته جدید تنظیم کن
                if (selectedDate < currentSaturday || selectedDate > getWeekDays(currentSaturday)[6]) {
                    selectedDate = new Date(currentSaturday);
                }
                refreshView();
            });

            $('#nextWeek').click(function() {
                currentSaturday.setDate(currentSaturday.getDate() + 7);
                if (selectedDate < currentSaturday || selectedDate > getWeekDays(currentSaturday)[6]) {
                    selectedDate = new Date(currentSaturday);
                }
                refreshView();
            });

            // امروز
            $('#todayBtn').click(function() {
                let today = new Date();
                currentSaturday = getSaturday(today);
                selectedDate = today;
                refreshView();
            });

            // ---------- مقداردهی اولیه ----------
            renderHourLabels();
            refreshView();

            // (اختیاری) اگر روی رویداد کلیک شد اطلاع بده
            $(document).on('click', '.event-block', function() {
                alert('رویداد: ' + $(this).find('.event-title').text());
            });
        });
    </script>
@endsection
