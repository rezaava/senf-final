@extends('dashboard.layout.master')
@section('onvan')
پیشنهادات ویژه
@endsection
@section('body')
    <div class="col px-5">
        <div class="row mt-4 p-3 rounded-4 shadow bg-white">
            <h4 class="mb-4">پیشنهادات ویژه</h4>
            <form action="{{ route('special-offers.update') }}" method="POST">
                @csrf
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>انتخاب</th>
                            <th>نام خدمت</th>
                            <th>درصد تخفیف (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($services as $service)
                            <tr>
                                <td>
                                    <input type="checkbox" id="check_{{ $service->id }}"
                                        onchange="toggleInput({{ $service->id }})"
                                        {{ $service->off > 0 ? 'checked' : '' }}>
                                </td>
                                <td>{{ $service->name }}</td>
                                <td>
                                    <input type="number" name="services[{{ $service->id }}]" class="form-control"
                                        id="input_{{ $service->id }}"
                                        value="{{ old('services.' . $service->id, $service->off) }}" min="0"
                                        max="100" {{ $service->off > 0 ? '' : 'disabled' }}>
                                    @error('services.' . $service->id)
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <button type="submit" class="btn btn-primary mt-3">ذخیره تخفیف‌ها</button>
            </form>
        </div>
    </div>
@endsection

@section('javaScript')
    <script>
        function toggleInput(id) {
            const checkbox = document.getElementById('check_' + id);
            const input = document.getElementById('input_' + id);
            input.disabled = !checkbox.checked;
            if (!checkbox.checked) {
                input.value = 0;
            }
        }
    </script>
@endsection
