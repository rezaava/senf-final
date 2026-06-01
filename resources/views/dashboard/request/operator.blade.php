@extends('dashboard.layout.master')
@section('onvan')
   درخواست همکاری
@endsection
@section('title')
    <title>درخواست همکاری</title>
    <style>
        .services {
            margin-right: 30px;
            margin-top: 10px;
        }
    </style>
@endsection

@section('body')
    <!-- main  -->
    <div class="col px-3">
        <!-- Form -->
        <div class="row g-0 mt-4 p-3 rounded-4 shadow bg-white pb-3">
            <div class="clearfix mt-2">
                <h5 class="float-end">درخواست اپراتور شدن</h5>
            </div>
            <p>
                لطفا با دقت سالن ها و خدماتی که میخواهید را انتخاب کنید و پس از ثبت درخواست منتظر تایید سالن باشید.
            </p>
            <div class="input-group mb-3">
                <input type="text" id="organ_search_input" class="form-control" placeholder="نام یا کد ارگان را وارد کنید">
                <button class="btn btn-primary" id="organ_search_btn">جستجو</button>
            </div>

            <form action="{{ route('operatorStore') }}" class="px-4 mt-4" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="organ_result" class="row"></div>

                <div class="d-flex justify-content-center mt-4">
                    <button type="submit"
                        class="btn submit-btn w-25 border border-3 border-dark align-middle rounded-pill shadow">ذخیره</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="organModal" tabindex="-1" aria-labelledby="organModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">اطلاعات ارگان</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="organModalBody">
                    <div class="text-center">در حال بارگذاری...</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javaScript')
    <script>
        // نمایش خدمات ارگان وقتی تیک زده میشه
        // document.querySelectorAll(".organ-checkbox").forEach(function(checkbox) {
        //     checkbox.addEventListener("change", function() {
        //         const servicesDiv = document.getElementById("services-" + this.id);
        //         if (this.checked) {
        //             servicesDiv.classList.remove("d-none");
        //         } else {
        //             servicesDiv.classList.add("d-none");

        //             // وقتی ارگان غیر فعال میشه، تیک خدمات هم برداشته بشه
        //             servicesDiv.querySelectorAll("input[type=checkbox]").forEach(cb => cb.checked = false);
        //         }
        //     });
        // });
    </script>
    <script>
        $(document).ready(function() {
            $('#organ_search_btn').click(function() {
                // $("#organ_result").html('');
                let keyword = $('#organ_search_input').val().trim();

                if (!keyword) return alert('لطفاً یک کلمه وارد کنید.');

                $.ajax({
                    url: '{{ route('organ.search') }}',
                    data: {
                        q: keyword
                    },
                    success: function(data) {
                        if (data.error) {
                            $('#organ_result').html('<div class="alert alert-danger">' + data
                                .error + '</div>');
                            return;
                        }
                        if (data.length > 0) {
                            data.forEach(item => {
                                $('#organ_result').append(`
                            <div class="col-md-4 mt-3 px-4">
                                <div class="border rounded-4 shadow p-3">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input organ-select-btn" type="checkbox" value="${item.id}" name="organs[]" data-id='${item.id}' data-organ='${JSON.stringify(item)}' id="organ_${item.id}">
                                        <label class="form-check-label" for="organ_${item.id}">${item.name}</label>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade" id="organModal${item.id}" tabindex="-1" aria-labelledby="organModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">اطلاعات ارگان</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body" id="organModalBody${item.id}">
                                            <div class="text-center">در حال بارگذاری...</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            `);
                            });
                        }
                    }
                });
            });

            // نمایش Modal با اطلاعات کامل ارگان
            $(document).on('change', '.organ-select-btn', function() {
                const organ = $(this).data('organ');
                const id = $(this).data('id');
                let serviceHTML = '';
                organ.services.forEach(service => {
                    serviceHTML += `<div class="form-check w-25">
                    <input class="form-check-input" type="checkbox" value="${service.id}" id="s_${service.id}" name="services[]">
                    <label class="form-check-label" for="s_${service.id}">${service.name}</label>
                </div>`;
                });

                let contractHTML = `<label class="form-label mt-3">انتخاب قرارداد:</label>
                <select class="form-select" id="contract_select" name="contract[]">
                    <option disabled selected>انتخاب کنید</option>`;
                organ.contract_template.forEach(c => {
                    contractHTML +=
                        `<option value="${c.id}" data-type="${c.type}" data-organ='${organ.id}' data-text='${c.text}' data-percent="${c.percentage}" data-salary="${c.amount}">${c.title}</option>`;
                });
                contractHTML += '</select>';
                // alert(contractHTML);
                $('#organModalBody' + id).html(`
                <h5>ارگان: ${organ.name}</h5>
                <hr>
                <div><strong>خدمات:</strong></div>
                ${serviceHTML}
                <hr>
                ${contractHTML}
                <div id="contract_details${id}" class="mt-3"></div>
            `);

                $('#organModal' + id).modal('show');
            });

            // وقتی قرارداد انتخاب شد
            $(document).on('change', '#contract_select', function() {
                const selected = $(this).find(':selected');
                const type = selected.data('type');
                const percent = selected.data('percent');
                const salary = selected.data('salary');
                const text = selected.data('text');
                const organId = selected.data('organ');
                // alert(organId);

                const organ = $('#organ_' + organId).data('organ');
                // alert(organ);
                let selectedContract = organ.contract_template.find(c => c.id == selected.val());
                // alert(selectedContract);
                let services = selectedContract.services;

                // alert(services);
                // let services = selected.services;
                let details = '';
                if (type == 'percentage') {
                    details = `<p class="p-2">
                    درصد پایه خدمات: ${percent}%
                    </p>
                    <p class="p-2 mt-2">
                    متن قرارداد : ${text}
                    </p>`;

                    let table = `<table class="table table-bordered mt-3">
                        <thead>
                            <tr>
                                <th>نام خدمت</th>
                                <th>درصد</th>
                            </tr>
                        </thead>
                        <tbody>`;
                    services.forEach(service => {
                        table += `<tr>
                        <td>${service.name}</td>
                        <td>${service.pivot.percentage}%</td>
                      </tr>`;
                    });
                    table += `</tbody></table>`;
                    $('#contract_details' + organId).html(details + table);

                } else {
                    details = `<p class="p-2">
                    حقوق ثابت: ${salary} تومان
                    </p>
                    <p class="p-2 mt-2">
                    متن قرارداد : ${text}
                    </p>`;
                    $('#contract_details' + organId).html(details);

                }

            });
        });
    </script>
@endsection
