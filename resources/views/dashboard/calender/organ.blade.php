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
    <link rel="stylesheet" href="{{ asset('asset/css/calender-organ.css') }}">
@endsection

@section('body')
    <!-- main  -->
    <div class="col px-0 px-lg-2">
        <!-- tarakonesh ha -->
        <div class="row mt-4 p-2 rounded-4 shadow bg-white">
            <div class="clearfix mt-2">
                <h5 class="float-end">تقویم کاری ارایشگر</h5>
            </div>

            <div class="row g-0">
                <div class="calendar-wrapper">
                    <!-- header with week navigation -->
                    <div class="calendar-header d-flex flex-wrap align-items-center justify-content-between">
                        <div>
                            <button class="today-btn" id="todayBtn"><i
                                    class="fas fa-calendar-check me-1"></i>امروز</button>
                            <button id="prevWeek"><i class="fas fa-chevron-right ms-1"></i>هفته قبل</button>
                            <button id="nextWeek">هفته بعد <i class="fas fa-chevron-left me-1"></i></button>
                        </div>
                    </div>

                    <!-- week days (shamsi) -->
                    <div class="week-days" id="weekDaysContainer"></div>

                    <!-- daily view with multiple stylists -->
                    <div class="daily-view" id="dailyView">
                        <div class="stylist-schedule-container">
                            <!-- hour labels column -->
                            <div class="hour-labels-col" id="hourLabels"></div>
                            <!-- scrollable stylist columns -->
                            <div class="stylists-scroll" id="stylistsScroll">
                                <div class="stylists-wrapper" id="stylistsWrapper"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- modal for new event -->
                <div class="modal fade" id="eventModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">نوبت جدید</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="eventForm">
                                    <div class="mb-3">
                                        <label for="eventTitle" class="form-label">عنوان / نام مشتری</label>
                                        <input type="text" class="form-control" id="eventTitle" placeholder="مثلاً ناهید"
                                            required>
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
                                    <input type="hidden" id="stylistIdInput">
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                                <button type="button" class="btn btn-primary" id="saveEventBtn">ذخیره</button>
                            </div>
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
    {{-- <script src="https://cdn.jsdelivr.net/npm/moment-jalaali@0.9.2/build/moment-jalaali.min.js"></script> --}}
    <script>
        // 3. بلافاصله پلاگین را فعال کنید (قبل از هر کد دیگری)
        moment.loadPersian({
            usePersianDigits: false
        })
        console.log(moment().format('jYYYY/jMM/jDD'))
    </script>
    <script>
        $(document).ready(function() {
            // ---------- data ----------
            // لیست آرایشگرها
            let stylists = [];
            // رویدادها (نوبت‌ها)
            let events = [];
            let timeOffs = [];

            function loadCalendarData() {

                let dateStr = moment(selectedDate).format('YYYY-MM-DD');

                $.get('/dashboard/salon-calendar/data', {
                    date: dateStr
                }, function(res) {

                    stylists = res.stylists;

                    events = res.events.map(ev => ({
                        id: ev.id,
                        title: ev.costumer.name,
                        serviceName: ev.services[0].service.name,
                        stylistId: ev.operator_id,
                        start: new Date(ev.start_at),
                        end: new Date(ev.end_at)
                    }));
                    timeOffs = res.time_offs.map(t => ({
                        id: t.id,
                        stylistId: t.user_id,
                        start: new Date(t.start_at),
                        end: new Date(t.end_at)
                    }));

                    renderStylistColumns();
                });
            }

            // ---------- state ----------
            let currentSaturday = getSaturday(new Date());
            let selectedDate = new Date();

            // helper functions
            function getSaturday(baseDate) {
                let d = new Date(baseDate);
                d.setHours(0, 0, 0, 0);
                let day = d.getDay(); // 0=Sun ... 6=Sat
                let diffToSat = (day + 1) % 7; // یکشنبه=6, شنبه=0
                d.setDate(d.getDate() - diffToSat);
                return d;
            }

            function getWeekDays(sat) {
                let days = [];
                for (let i = 0; i < 7; i++) {
                    let d = new Date(sat);
                    d.setDate(sat.getDate() + i);
                    days.push(d);
                }
                return days;
            }

            const persianWeekdays = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنجشنبه', 'جمعه'];

            function toJalaliDisplay(date) {
                return moment(date).format('jD/jM');
            }

            // render week days bar (shamsi)
            function renderWeekDays() {
                let weekDays = getWeekDays(currentSaturday);
                let html = '';
                weekDays.forEach((day, index) => {
                    let activeClass = (day.toDateString() === selectedDate.toDateString()) ? 'active' : '';
                    let dayName = persianWeekdays[index];
                    let jalaliDay = moment(day).format('jD');
                    let jalaliMonth = moment(day).format('jM');
                    html += `<button class="day-card ${activeClass}" data-date="${day.toISOString()}">
                    <div class="day-name">${dayName}</div>
                    <div class="day-date">${jalaliDay}/${jalaliMonth}</div>
                </button>`;
                });
                $('#weekDaysContainer').html(html);

                let startWeek = weekDays[0];
                let endWeek = weekDays[6];
            }

            // render hour labels (left column)
            function renderHourLabels() {
                let labels = '';
                for (let h = 0; h < 24; h++) {
                    labels += `<div class="hour-label">${h.toString().padStart(2, '0')}:00</div>`;
                }
                $('#hourLabels').html(labels);
            }

            // render all stylist columns for selectedDate
            function renderStylistColumns() {
                let wrapper = $('#stylistsWrapper');
                wrapper.empty();

                stylists.forEach(stylist => {
                    let colHtml = `
                    <div class="stylist-column" data-stylist-id="${stylist.id}">
                        <div class="stylist-header">${stylist.name}</div>
                        <div class="stylist-grid" data-stylist-id="${stylist.id}">
                            <!-- hour slots (24) will be injected here -->
                            <div class="hour-slots-container" style="position:relative; height:100%;"></div>
                            <div class="events-layer" style="position:absolute; top:0; right:0; left:0; bottom:0; pointer-events:none;"></div>
                        </div>
                    </div>
                `;
                    let $col = $(colHtml);
                    wrapper.append($col);

                    // populate hour slots (24 * 60px)
                    let $slotsContainer = $col.find('.hour-slots-container');
                    for (let h = 0; h < 24; h++) {
                        $slotsContainer.append(`<div class="hour-slot" data-hour="${h}"></div>`);
                    }

                    // render events for this stylist on selected day
                    renderEventsForStylist(stylist.id, $col.find('.events-layer'));
                });
            }

            // render events for a specific stylist inside the events-layer
            function renderEventsForStylist(stylistId, $eventsLayer) {

                $eventsLayer.empty();

                let dayStart = new Date(selectedDate);
                dayStart.setHours(0, 0, 0, 0);

                let dayEnd = new Date(selectedDate);
                dayEnd.setHours(23, 59, 59, 999);

                // ---------- رزروها ----------
                let stylistEvents = events.filter(ev =>
                    ev.stylistId == stylistId &&
                    ev.start >= dayStart &&
                    ev.end <= dayEnd
                );

                stylistEvents.forEach(ev => {
                    renderBlock(ev.start, ev.end, $eventsLayer, 'reserved', ev.title, ev.serviceName);
                });

                // ---------- تایم آف ----------
                let stylistTimeOffs = timeOffs.filter(t =>
                    t.stylistId == stylistId &&
                    t.start >= dayStart &&
                    t.end <= dayEnd
                );

                stylistTimeOffs.forEach(t => {
                    renderBlock(t.start, t.end, $eventsLayer, 'timeoff', 'مرخصی');
                });
            }

            function renderBlock(startDate, endDate, $layer, cssClass, title, service = '') {

                let startHour = startDate.getHours() + startDate.getMinutes() / 60;
                let endHour = endDate.getHours() + endDate.getMinutes() / 60;

                let top = startHour * 60;
                let height = (endHour - startHour) * 60;

                let $eventDiv = $('<div class="event-block"></div>');
                $eventDiv.addClass(cssClass);

                $eventDiv.css({
                    top: top + 'px',
                    height: height + 'px'
                });

                if (service.length > 1) {
                    $eventDiv.html(`<div class="event-title">${title}; خدمت :${service}</div>`);
                } else {
                    $eventDiv.html(`<div class="event-title">${title}</div>`);
                }

                $layer.append($eventDiv);
            }

            // re-render all events for all stylists (after changes)
            function renderAllEvents() {
                $('.stylist-column').each(function() {
                    let stylistId = $(this).data('stylist-id');
                    let $eventsLayer = $(this).find('.events-layer');
                    renderEventsForStylist(stylistId, $eventsLayer);
                });
            }

            // clear all highlighted slots
            function clearSelectionHighlights() {
                $('.hour-slot').removeClass('selected');
            }

            // drag state per column
            let dragState = {
                active: false,
                stylistId: null,
                startHour: null,
                endHour: null
            };

            // attach drag handlers dynamically (delegation from wrapper)
            $('#stylistsWrapper')
                .on('mousedown', '.hour-slot', function(e) {
                    e.preventDefault();
                    let $slot = $(this);
                    let $column = $slot.closest('.stylist-column');
                    let stylistId = $column.data('stylist-id');
                    let hour = $slot.data('hour');

                    dragState.active = true;
                    dragState.stylistId = stylistId;
                    dragState.startHour = hour;
                    dragState.endHour = hour;

                    clearSelectionHighlights();
                    // highlight current slot
                    $slot.addClass('selected');
                })
                .on('mouseenter', '.hour-slot', function() {
                    if (!dragState.active) return;
                    let $slot = $(this);
                    let $column = $slot.closest('.stylist-column');
                    let stylistId = $column.data('stylist-id');
                    if (stylistId !== dragState.stylistId) return; // only same column

                    let hour = $slot.data('hour');
                    dragState.endHour = hour;

                    // highlight range within this column only
                    let min = Math.min(dragState.startHour, dragState.endHour);
                    let max = Math.max(dragState.startHour, dragState.endHour);
                    $column.find('.hour-slot').each(function() {
                        let h = $(this).data('hour');
                        if (h >= min && h <= max) {
                            $(this).addClass('selected');
                        } else {
                            $(this).removeClass('selected');
                        }
                    });
                });

            $(window).on('mouseup', function() {
                function isBlockedByTimeOff(stylistId, startH, endH) {

                    let startMoment = moment(selectedDate).hour(startH);
                    let endMoment = moment(selectedDate).hour(endH + 1);

                    return timeOffs.some(t => {

                        if (t.stylistId != stylistId) return false;

                        let tStart = moment(t.start);
                        let tEnd = moment(t.end);

                        return startMoment.isBefore(tEnd) && endMoment.isAfter(tStart);
                    });
                }
                if (isBlockedByTimeOff(dragState.stylistId, dragState.startHour, dragState.endHour)) {
                    alert('این بازه در مرخصی قرار دارد');
                    clearSelectionHighlights();
                    return;
                }
                if (dragState.active && dragState.stylistId && dragState.startHour !== null && dragState
                    .endHour !== null) {
                    // open modal with selected stylist and range
                    openModalWithRange(dragState.stylistId, dragState.startHour, dragState.endHour);
                }
                // reset drag state
                dragState.active = false;
                dragState.stylistId = null;
                dragState.startHour = null;
                dragState.endHour = null;
            });

            // modal logic
            let modal = new bootstrap.Modal(document.getElementById('eventModal'));

            function openModalWithRange(stylistId, startH, endH) {
                let minH = Math.min(startH, endH);
                let maxH = Math.max(startH, endH);

                // populate hour selects
                let startSelect = $('#startHour').empty();
                let endSelect = $('#endHour').empty();
                for (let h = 0; h < 24; h++) {
                    let hourStr = h.toString().padStart(2, '0') + ':00';
                    startSelect.append(`<option value="${h}" ${h === minH ? 'selected' : ''}>${hourStr}</option>`);
                    endSelect.append(
                        `<option value="${h + 1}" ${h + 1 === maxH + 1 ? 'selected' : ''}>${(h + 1).toString().padStart(2, '0')}:00</option>`
                    );
                }
                // set stylist hidden
                $('#stylistIdInput').val(stylistId);
                // show jalali date
                let jalaliDate = moment(selectedDate).format('jD jMMMM jYYYY');
                $('#dateHint').text(
                    `تاریخ: ${jalaliDate} - آرایشگر: ${stylists.find(s => s.id === stylistId)?.name}`);
                modal.show();
            }

            // save new event
            $('#saveEventBtn').on('click', function() {

                let title = $('#eventTitle').val().trim();
                if (!title) {
                    alert('عنوان را وارد کنید');
                    return;
                }

                let startHourVal = parseInt($('#startHour').val());
                let endHourVal = parseInt($('#endHour').val());
                if (endHourVal <= startHourVal) {
                    alert('ساعت پایان باید بعد از شروع باشد');
                    return;
                }

                let stylistId = $('#stylistIdInput').val();

                let startDate = moment(selectedDate)
                    .hour(startHourVal)
                    .minute(0)
                    .format('YYYY-MM-DD HH:mm:ss');

                let endDate = moment(selectedDate)
                    .hour(endHourVal)
                    .minute(0)
                    .format('YYYY-MM-DD HH:mm:ss');

                $.post('/dashboard/salon-calendar/store', {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    title: title,
                    stylist_id: stylistId,
                    start_at: startDate,
                    end_at: endDate
                }, function(res) {

                    if (res.success) {
                        modal.hide();
                        $('#eventTitle').val('');
                        clearSelectionHighlights();

                        // دوباره از بک بگیر
                        loadCalendarData();
                    }

                });

            });

            $('#eventModal').on('hidden.bs.modal', function() {
                clearSelectionHighlights();
            });

            // day selection
            $(document).on('click', '.day-card', function() {
                let dateStr = $(this).data('date');
                selectedDate = new Date(dateStr);
                renderWeekDays();
                // re-render all stylist columns (because events per day change)
                loadCalendarData();
            });

            // week navigation
            $('#prevWeek').click(function() {
                currentSaturday.setDate(currentSaturday.getDate() - 7);
                let weekDays = getWeekDays(currentSaturday);
                if (selectedDate < weekDays[0] || selectedDate > weekDays[6]) {
                    selectedDate = new Date(currentSaturday);
                }
                renderWeekDays();
                loadCalendarData();
            });

            $('#nextWeek').click(function() {
                currentSaturday.setDate(currentSaturday.getDate() + 7);
                let weekDays = getWeekDays(currentSaturday);
                if (selectedDate < weekDays[0] || selectedDate > weekDays[6]) {
                    selectedDate = new Date(currentSaturday);
                }
                renderWeekDays();
                loadCalendarData();
            });

            $('#todayBtn').click(function() {
                let today = new Date();
                currentSaturday = getSaturday(today);
                selectedDate = today;
                renderWeekDays();
                loadCalendarData();
            });

            // initial render
            renderHourLabels();
            renderWeekDays();
            loadCalendarData();

            // optional click on event
            $(document).on('click', '.event-block', function() {
                alert('نوبت: ' + $(this).find('.event-title').text());
            });
        });
    </script>
@endsection
