<div class="row">
    <div class="col-md-6 mb-3">
        <label for="car_type" class="form-label">نوع السيارة</label>
        <select name="car_type" id="car_type" class="form-control" required>
            <option value="">-- اختر نوع السيارة --</option>
            @foreach($carTypes as $type)
                <option value="{{ $type }}">{{ $type }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6 mb-3 d-none" id="sub_car_type_wrapper">
        <label for="sub_car_type" class="form-label">تفاصيل نوع السيارة</label>
        <select name="sub_car_type" id="sub_car_type" class="form-control">
            <option value="">-- اختر تفصيل النوع --</option>
            @foreach($subCarTypes['سيارات سورية او اردنية او لبنانية'] as $sub)
                <option value="{{ $sub }}">{{ $sub }}</option>
            @endforeach
        </select>
    </div>

    @if (auth()->user()->hasRole('Admin'))
        <div class="col-md-6 mb-3" id="sub_car_type_wrapper">
            <label class="form-label" for="border_crossing_id">المعبر الحدودي:</label>
            <select id="border_crossing_id" name="border_crossing_id" class="form-control" required>
                <option value="" disabled selected>اختر المعبر</option>
                @foreach($borderCrossings as $crossing)
                    <option value="{{ $crossing->id }}" {{ old('border_crossing_id') == $crossing->id ? 'selected' : '' }}>
                        {{ $crossing->name }}
                    </option>
                @endforeach
            </select>
        </div>
    @else
        <input type="text" name="border_crossing_id" class="form-control" style="display: none"
            value="{{ auth()->user()->border_crossing_id }}">
    @endif
    <div class="col-md-6 mb-2">
        <label>اسم السائق</label>
        <input type="text" name="driver_name" class="form-control"
            value="{{ old('driver_name', $entry_statement->driver_name ?? '') }}">
        @error('driver_name') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
    <div class="col-md-6 mb-2">
        <label>ماركة السيارة</label>
        <input type="text" name="car_brand" class="form-control"
            value="{{ old('car_brand', $entry_statement->car_brand ?? '') }}">
        @error('car_brand') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
    <div class="col-md-6 mb-2">
        <label>جنسية السيارة</label>
        <input type="text" name="car_nationality" class="form-control"
            value="{{ old('car_nationality', $entry_statement->car_nationality ?? '') }}">
        @error('car_nationality') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="col-md-6 mb-2">
        <label>رقم السيارة</label>
        <input type="text" name="car_number" class="form-control"
            value="{{ old('car_number', $entry_statement->car_number ?? '') }}">
        @error('car_number') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="col-md-6 mb-2" id="stay_duration_wrapper">
        <label>مدة البقاء</label>
        <select name="stay_duration" id="stay_duration" class="form-control">
            <option value="">-- اختر مدة البقاء --</option>
            <option value="4">شهر - 50$</option>
            <option value="12">ثلاث أشهر - 200$</option>
        </select>
        @error('stay_duration') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
</div>

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const carTypeSelect = document.getElementById('car_type');
            const subCarTypeWrapper = document.getElementById('sub_car_type_wrapper');
            const stayDurationSelect = document.getElementById('stay_duration');

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
                    stayDurationSelect.appendChild(option);
                });
            }

            carTypeSelect.addEventListener('change', function () {
                const selectedType = this.value;
                const stayDurationWrapper = document.getElementById('stay_duration_wrapper');

                if (selectedType === 'سيارات سورية او اردنية او لبنانية') {
                    subCarTypeWrapper.classList.remove('d-none');
                    stayDurationWrapper.classList.add('d-none');
                    stayDurationSelect.value = '';
                } else {
                    subCarTypeWrapper.classList.add('d-none');
                    document.getElementById('sub_car_type').value = '';
                    stayDurationWrapper.classList.remove('d-none');
                }

                if (selectedType === 'سيارات سورية او اردنية او لبنانية') {
                    subCarTypeWrapper.classList.remove('d-none');
                } else {
                    subCarTypeWrapper.classList.add('d-none');
                    document.getElementById('sub_car_type').value = '';
                }

                if (selectedType === 'شاحنات وباصات خليجية') {
                    updateStayDurations(gulfTruckDurations);
                } else {
                    updateStayDurations(defaultDurations);
                }
            });
        });
    </script>
@endsection



<!-- <div class="col-md-6 mb-2">
        <label>رسم البقاء</label>
        <input type="number" step="0.01" name="stay_fee" class="form-control"
            value="{{ old('stay_fee', $entry_statement->stay_fee ?? '') }}">
        @error('stay_fee') <small class="text-danger">{{ $message }}</small> @enderror
    </div> -->

<!-- <div class="col-md-6 mb-2">
        <label>سجل خروج؟</label>
        <select name="is_checked_out" class="form-control">
            <option value="1" {{ old('is_checked_out', $entry_statement->is_checked_out ?? '') == 1 ? 'selected' : '' }}>
                نعم</option>
            <option value="0" {{ old('is_checked_out', $entry_statement->is_checked_out ?? '') == 0 ? 'selected' : '' }}>
                لا</option>
        </select>
        @error('is_checked_out') <small class="text-danger">{{ $message }}</small> @enderror
    </div> -->

<!-- <div class="col-md-6 mb-2">
        <label>رسم الخروج</label>
        <input type="number" step="0.01" name="exit_fee" class="form-control"
            value="{{ old('exit_fee', $entry_statement->exit_fee ?? '') }}">
        @error('exit_fee') <small class="text-danger">{{ $message }}</small> @enderror
    </div> -->