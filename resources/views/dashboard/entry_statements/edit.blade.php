@extends('layouts.app')

@section('content')
<div class="container">
    <h1>تعديل حركة دخول</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>حدثت الأخطاء التالية:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('entry_statements.update', $entryStatement->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            {{-- نوع السيارة --}}
            <div class="col-md-6 mb-3">
                <label for="car_type" class="form-label">نوع السيارة</label>
                <select name="car_type" id="car_type" class="form-control" required>
                    <option value="">-- اختر نوع السيارة --</option>
                    @foreach($carTypes as $type)
                        <option value="{{ $type }}" {{ old('car_type', $entryStatement->car_type) == $type ? 'selected' : '' }}>
                            {{ $type }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- المعبر الحدودي --}}
            @if (auth()->user()->hasRole('Admin'))
                <div class="col-md-6 mb-3">
                    <label for="border_crossing_id" class="form-label">المعبر الحدودي:</label>
                    <select id="border_crossing_id" name="border_crossing_id" class="form-control" required>
                        <option value="" disabled>اختر المعبر</option>
                        @foreach($borderCrossings as $crossing)
                            <option value="{{ $crossing->id }}"
                                {{ old('border_crossing_id', $entryStatement->border_crossing_id) == $crossing->id ? 'selected' : '' }}>
                                {{ $crossing->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <input type="hidden" name="type" value="دخول وخروج">
            @else
                <input type="hidden" name="border_crossing_id" value="{{ auth()->user()->border_crossing_id }}">
                <input type="hidden" name="type" value="دخول وخروج">
            @endif

            {{-- باقي الحقول --}}
            <div class="col-md-6 mb-2">
                <label>اسم السائق</label>
                <input type="text" name="driver_name" class="form-control"
                    value="{{ old('driver_name', $entryStatement->driver_name) }}">
            </div>

            <div class="col-md-6 mb-2">
                <label>ماركة السيارة</label>
                <input type="text" name="car_brand" class="form-control"
                    value="{{ old('car_brand', $entryStatement->car_brand) }}">
            </div>

            <div class="col-md-6 mb-2">
                <label>جنسية السيارة</label>
                <input type="text" name="car_nationality" class="form-control"
                    value="{{ old('car_nationality', $entryStatement->car_nationality) }}">
            </div>

            <div class="col-md-6 mb-2">
                <label>رقم السيارة</label>
                <input type="text" name="car_number" class="form-control"
                    value="{{ old('car_number', $entryStatement->car_number) }}">
            </div>

            {{-- مدة البقاء --}}
            <div class="col-md-6 mb-2" id="stay_duration_wrapper">
                <label>مدة البقاء</label>
                <select name="stay_duration" id="stay_duration" class="form-control">
                    <option value="">-- اختر مدة البقاء --</option>
                    <option value="4" {{ old('stay_duration', $entryStatement->stay_duration) == 4 ? 'selected' : '' }}>شهر - 50$</option>
                    <option value="12" {{ old('stay_duration', $entryStatement->stay_duration) == 12 ? 'selected' : '' }}>ثلاث أشهر - 200$</option>
                </select>
            </div>

            {{-- رقم الدفتر --}}
            <div class="col-md-6 mb-2" id="book_number_wrapper" style="display: none;">
                <label>رقم الدفتر</label>
                <input type="text" name="book_number" class="form-control"
                    value="{{ old('book_number', $entryStatement->book_number) }}">
            </div>

            {{-- نوع الدفتر --}}
            <div class="col-md-6 mb-2" id="book_type_wrapper" style="display: none;">
                <label>نوع الدفتر</label>
                <select name="book_type" class="form-control">
                    <option value="">-- اختر نوع الدفتر --</option>
                    <option value="خاص" {{ old('book_type', $entryStatement->book_type) == 'خاص' ? 'selected' : '' }}>خاص</option>
                    <option value="عام" {{ old('book_type', $entryStatement->book_type) == 'عام' ? 'selected' : '' }}>عام</option>
                </select>
            </div>

            {{-- التعهد --}}
            <div class="col-md-6 mb-2" id="commitment_wrapper" style="display: none;">
                <label class="form-label d-block">يتضمن تعهد</label>
                <input type="hidden" name="has_commitment" value="0">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" 
                        name="has_commitment" id="has_commitment"
                        value="1"
                        {{ old('has_commitment', $entryStatement->has_commitment) ? 'checked' : '' }}>
                    <label class="form-check-label" for="has_commitment">نعم</label>
                </div>
            </div>

        </div>

        <button type="submit" class="btn btn-success mt-2">تحديث</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const carTypeSelect = document.getElementById('car_type');
    const stayDurationWrapper = document.getElementById('stay_duration_wrapper');
    const stayDurationSelect = document.getElementById('stay_duration');
    const bookNumberWrapper = document.getElementById('book_number_wrapper');
    const bookTypeWrapper = document.getElementById('book_type_wrapper');
    const commitmentWrapper = document.getElementById('commitment_wrapper');

    const hiddenTypes = ['سيارات سورية', 'سيارات لبنانية', 'سيارات أردنية'];
    const defaultDurations = [
        { value: '4', text: 'شهر - 50$' },
        { value: '12', text: 'ثلاث أشهر - 200$' }
    ];
    const gulfTruckDurations = [
        { value: '2', text: 'أسبوعين - 50$' }
    ];

    function updateStayDurations(options) {
        stayDurationSelect.innerHTML = '<option value="">-- اختر مدة البقاء --</option>';
        options.forEach(opt => {
            const option = document.createElement('option');
            option.value = opt.value;
            option.textContent = opt.text;
            if (opt.value == "{{ old('stay_duration', $entryStatement->stay_duration) }}") {
                option.selected = true;
            }
            stayDurationSelect.appendChild(option);
        });
    }

    function toggleFields(type) {
        if (hiddenTypes.includes(type)) {
            stayDurationWrapper.style.display = 'none';
            stayDurationSelect.innerHTML = '';
            bookNumberWrapper.style.display = 'block';
            bookTypeWrapper.style.display = 'block';
            commitmentWrapper.style.display = 'block';
        } else {
            stayDurationWrapper.style.display = 'block';
            bookNumberWrapper.style.display = 'none';
            bookTypeWrapper.style.display = 'none';
            commitmentWrapper.style.display = 'none';

            if (type === 'شاحنات وباصات خليجية') {
                updateStayDurations(gulfTruckDurations);
            } else {
                updateStayDurations(defaultDurations);
            }
        }
    }

    carTypeSelect.addEventListener('change', function () {
        toggleFields(this.value);
    });

    toggleFields(carTypeSelect.value);
});
</script>
@endsection
