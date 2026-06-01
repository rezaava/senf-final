@extends('dashboard.layout.master')
@section('onvan')
قرارداد ها
@endsection
@section('main')
    <div class="col px-5">
        <div class="row mt-4 p-2 rounded-4 shadow bg-white">
            <h4>قرارداد ها</h4>

            <a href="{{ route('contract-templates.create') }}" class="btn btn-success my-3">قرارداد جدید</a>

            @foreach ($templates as $template)
                <div class="card mb-3">
                    <div class="card-header">
                        {{ $template->target_role == 'manager' ? 'قرارداد برای مدیر سالن' : 'قرارداد برای آرایشگر (' . $template->type . ')' }}
                    </div>
                    <div class="card-body">
                        <h4>{{ $template->title }}</h4>
                        <p>{{ Str::limit($template->text, 150) }}</p>
                        <a href="{{ route('contract-templates.edit', $template->id) }}"
                            class="btn btn-sm btn-primary">ویرایش</a>
                        <form action="{{ route('contract-templates.destroy', $template->id) }}" method="POST"
                            class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('حذف شود؟')">حذف</button>
                        </form>
                        @if ($template->target_role == 'operator' and $template->type == 'percentage')
                            <div class="row mt-3">
                                <h5 class="p-3">درصد هر خدمت</h5>
                                @foreach ($template->services as $service)
                                    <div class="col-md-3 border rounded-4 p-2">
                                        <p>{{ $service->name }} : <small>{{ $service->pivot->percentage }}%</small></p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
@section('script')
    <script>
        const typeSelector = document.getElementById('type-selector');
        const targetRole = document.getElementById('target-role');
        const percentageGroup = document.getElementById('percentage-group');
        const amountGroup = document.getElementById('amount-group');

        function toggleFields() {
            const selectedType = typeSelector.value;

            percentageGroup.style.display = (selectedType === 'percentage' || targetRole.value === 'manager') ? 'block' :
                'none';
            amountGroup.style.display = (selectedType === 'fixed' || selectedType === 'chair_rent') ? 'block' : 'none';
        }

        if (typeSelector) {
            typeSelector.addEventListener('change', toggleFields);
        }

        toggleFields();
    </script>
@endsection
