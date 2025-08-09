@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>تعديل حركة دخول</h1>

        <form action="{{ route('entry_statements.update', $entryStatement->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
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

                @if (auth()->user()->hasRole('Admin'))
                    <div class="col-md-6 mb-3" id="border">
                        <label class="form-label" for="border_crossing_id">المعبر الحدودي:</label>
                        <select id="border_crossing_id" name="border_crossing_id" class="form-control" required>
                            <option value="" disabled>اختر المعبر</option>
                            @foreach($borderCrossings as $crossing)
                                <option value="{{ $crossing->id }}" {{ old('border_crossing_id', $entryStatement->border_crossing_id) == $crossing->id ? 'selected' : '' }}>
                                    {{ $crossing->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <input type="hidden" name="border_crossing_id"
                           value="{{ old('border_crossing_id', auth()->user()->border_crossing_id) }}">
                @endif

                <div class="col-md-6 mb-2">
                    <label>اسم السائق</label>
                    <input type="text" name="driver_name" class="form-control"
                           value="{{ old('driver_name', $entryStatement->driver_name) }}">
                    @error('driver_name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-6 mb-2">
                    <label>ماركة السيارة</label>
                    <input type="text" name="car_brand" class="form-control"
                           value="{{ old('car_brand', $entryStatement->car_brand) }}">
                    @error('car_brand') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-6 mb-2">
                    <label>جنسية السيارة</label>
                    <input type="text" name="car_nationality" class="form-control"
                           value="{{ old('car_nationality', $entryStatement->car_nationality) }}">
                    @error('car_nationality') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-6 mb-2">
                    <label>رقم السيارة</label>
                    <input type="text" name="car_number" class="form-control"
                           value="{{ old('car_number', $entryStatement->car_number) }}">
                    @error('car_number') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-6 mb-2" id="stay_duration_wrapper">
                    <label>مدة البقاء</label>
                    <select name="stay_duration" id="stay_duration" class="form-control">
                        <option value="">-- اختر مدة البقاء --</option>
                    </select>
                    @error('stay_duration') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3">تحديث</button>
        </form>
    </div>
@endsection


@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const carTypeSelect = document.getElementById('car_type');
            const stayDurationWrapper = document.getElementById('stay_duration_wrapper');
            const stayDurationSelect = document.getElementById('stay_duration');

            const hiddenTypes = ['سيارات سورية', 'سيارات لبنانية', 'سيارات أردنية'];

            const defaultDurations = [
                { value: '4', text: 'شهر - 50$' },
                { value: '12', text: 'ثلاث أشهر - 200$' }
            ];

            const gulfTruckDurations = [
                { value: '2', text: 'أسبوعين - 50$' }
            ];

            const currentStayDuration = "{{ old('stay_duration', $entryStatement->stay_duration) }}";

            function updateStayDurations(options, selectedValue = '') {
                stayDurationSelect.innerHTML = '<option value="">-- اختر مدة البقاء --</option>';
                options.forEach(opt => {
                    const option = document.createElement('option');
                    option.value = opt.value;
                    option.textContent = opt.text;
                    if (opt.value === selectedValue) {
                        option.selected = true;
                    }
                    stayDurationSelect.appendChild(option);
                });
            }

            function toggleStayDurationVisibility(type) {
                if (hiddenTypes.includes(type)) {
                    stayDurationWrapper.style.display = 'none';
                    stayDurationSelect.innerHTML = ''; // Clear options
                } else {
                    stayDurationWrapper.style.display = 'block';
                    if (type === 'شاحنات وباصات خليجية') {
                        updateStayDurations(gulfTruckDurations, currentStayDuration);
                    } else {
                        updateStayDurations(defaultDurations, currentStayDuration);
                    }
                }
            }

            carTypeSelect.addEventListener('change', function () {
                toggleStayDurationVisibility(this.value);
            });

            // Trigger on page load if value exists
            toggleStayDurationVisibility(carTypeSelect.value);
        });
    </script>
@endsection
