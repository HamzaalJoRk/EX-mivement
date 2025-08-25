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

    @if (auth()->user()->hasRole('Admin'))
        <div class="col-md-6 mb-3" id="border">
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
        <input type="text" name="type" class="form-control" style="display: none" value="دخول وخروج">
    @else
        <input type="text" name="border_crossing_id" class="form-control" style="display: none"
            value="{{ auth()->user()->border_crossing_id }}">
        <input type="text" name="type" class="form-control" style="display: none" value="دخول وخروج">
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

    <div class="col-md-6 mb-2" id="book_number_wrapper" style="display: none;">
        <label>رقم الدفتر</label>
        <input type="text" name="book_number" class="form-control" required
            value="{{ old('book_number', $entry_statement->book_number ?? '') }}">
        @error('book_number') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="col-md-6 mb-2" id="book_type_wrapper" style="display: none;">
        <label>نوع الدفتر</label>
        <select name="book_type" class="form-control" required>
            <option value="">-- اختر نوع الدفتر --</option>
            <option value="خاص" {{ old('book_type', $entry_statement->book_type ?? '') == 'private' ? 'selected' : '' }}>
                خاص</option>
            <option value="عام" {{ old('book_type', $entry_statement->book_type ?? '') == 'public' ? 'selected' : '' }}>
                عام</option>
        </select>
        @error('book_type') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

</div>

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const carTypeSelect = document.getElementById('car_type');
            const stayDurationWrapper = document.getElementById('stay_duration_wrapper');
            const stayDurationSelect = document.getElementById('stay_duration');

            // الحقول الجديدة
            const bookNumberWrapper = document.getElementById('book_number_wrapper');
            const bookTypeWrapper = document.getElementById('book_type_wrapper');

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
                    stayDurationSelect.appendChild(option);
                });
            }

            function toggleFields(type) {
                // إخفاء مدة البقاء عند الأنواع المحددة
                if (hiddenTypes.includes(type)) {
                    stayDurationWrapper.style.display = 'none';
                    stayDurationSelect.innerHTML = '';
                    bookNumberWrapper.style.display = 'block';
                    bookTypeWrapper.style.display = 'block';
                } else {
                    stayDurationWrapper.style.display = 'block';
                    bookNumberWrapper.style.display = 'none';
                    bookTypeWrapper.style.display = 'none';

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

            // تشغيل أول مرة
            toggleFields(carTypeSelect.value);
        });
    </script>

@endsection