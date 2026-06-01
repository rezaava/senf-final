@extends('dashboard.layout.master')

@section('head')
    {{-- <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.4/main.min.css' rel='stylesheet' /> --}}
    <style>
        .fc-event-main{
            padding: 5px !important;
        }
    </style>
@endsection
@section('main')
    <!-- Modal -->
    <div class="modal fade" id="editAppointmentModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('appointments.update-status') }}" method="POST">
                @csrf
                <input type="hidden" name="id" id="appointmentId">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">ویرایش وضعیت نوبت</h5>
                    </div>
                    <div class="modal-body">
                        <p>اپراتور: <span id="operatorName"></span></p>
                        <p>مشتری: <span id="customerName"></span></p>
                        <div class="form-group">
                            <label for="statusSelect">وضعیت نوبت</label>
                            <select class="form-control" name="status" id="statusSelect">
                                <option value="0">در انتظار</option>
                                <option value="1">رزرو شده</option>
                                <option value="2">لغو شده</option>
                                <option value="3">انجام شده</option>
                                <option value="4">منقضی شده</option>
                            </select>
                        </div>
                        <div class="form-group mt-3 d-none" id="customerNameInputWrapper">
                            <label for="newCustomerName">نام مشتری</label>
                            <input type="text" class="form-control" name="customer_name" id="newCustomerName">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">ذخیره تغییرات</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="col px-5">
        <div class="row mt-4 p-3 mb-4 rounded-4 shadow bg-white">
            <h4 class="mb-3">تقویم کاری</h4>
            <div id='calendar'></div>
        </div>
    </div>
@endsection
@section('script')
    {{-- <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.4/main.min.js'></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                selectable: true,
                selectHelper: true,
                initialView: 'timeGridWeek',
                locale: 'fa',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'timeGridDay,timeGridWeek,dayGridMonth'
                },
                slotMinTime: "08:00:00",
                slotMaxTime: "23:00:00",
                allDaySlot: false,
                events: @json($events),
                eventClick: function(info) {
                    const event = info.event;

                    // اطلاعات نوبت رو از event بگیر
                    const appointmentId = event.extendedProps.id_code;
                    const operatorName = event.extendedProps.operator;
                    const customerName = event.extendedProps.customer;
                    const status = event.extendedProps.status;
                    // اطلاعات رو بریز توی modal
                    $('#appointmentId').val(appointmentId);
                    $('#operatorName').text(operatorName);
                    $('#customerName').text(customerName);
                    $('#statusSelect').val(status);

                    // modal رو نشون بده
                    $('#editAppointmentModal').modal('show');
                },
                // eventContent: function(arg) {
                //     return {
                //         html: arg.event.title
                //     };
                // },
            });


            calendar.render();
        });


        // select status
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('statusSelect');
            const customerInputWrapper = document.getElementById('customerNameInputWrapper');

            statusSelect.addEventListener('change', function() {
                if (this.value == 1 || this.value == 2) {
                    customerInputWrapper.classList.remove('d-none');
                } else {
                    customerInputWrapper.classList.add('d-none');
                    document.getElementById('newCustomerName').value = ''; // پاک کردن مقدار قبلی
                }
            });
        });
    </script>
@endsection
