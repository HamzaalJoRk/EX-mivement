@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>قائمة حركات الدخول</h1>

        <div class="row mb-1 align-items-center">
            <div class="col-md-6">
                <form method="GET" action="{{ route('entry_statements.index') }}" class="form-inline d-inline">
                    <div class="form-group mr-3">
                        <label for="startDate" class="mr-2">من تاريخ:</label>
                        <input type="date" id="startDate" name="startDate" value="{{ $startDate }}" class="form-control">
                    </div>
                    <div class="form-group mr-3">
                        <label for="endDate" class="mr-2">إلى تاريخ:</label>
                        <input type="date" id="endDate" name="endDate" value="{{ $endDate }}" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">فلترة</button>
                    <a href="{{ route('entry_statements.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-rotate-left"></i>
                    </a>
                </form>
                @if (auth()->user()->hasRole('CustomEntry') || auth()->user()->hasRole('Admin'))
                    <a href="{{ route('entry_statements.create') }}" class="btn btn-primary mt-1">إضافة حركة جديدة</a>
                @endif
                @if (auth()->user()->hasRole('CustomEntry') || auth()->user()->hasRole('Admin'))
                    <a href="/entry-statements-book/create" class="btn btn-primary mt-1">إضافة حركة لدفتر</a>
                @endif
            </div>

            <div class="col-md-6">
                @if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Finance'))
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <div class="stat-card p-1 bg-light rounded shadow-sm">
                                <h5 class="stat-title">إجمالي رسوم الدخول:</h5>
                                <p class="stat-value">{{ number_format($totalEntryFee, 2) }} $</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="stat-card p-1 bg-light rounded shadow-sm">
                                <h5 class="stat-title">إجمالي رسوم الخروج:</h5>
                                <p class="stat-value">{{ number_format($totalExitFee, 2) }} $</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="stat-card p-1 bg-light rounded shadow-sm">
                                <h5 class="stat-title">عدد الحركات:</h5>
                                <p class="stat-value">{{ $entryCount }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @if(session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'تم بنجاح',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'حسناً'
                });
            </script>
        @endif

        <div class="table-responsive" style="overflow-x: auto; white-space: nowrap;">
            <table id="entryTable" class="table table-bordered table-striped w-100"
                style="direction: rtl; text-align: right;">
                <thead>
                    <tr>
                        <th>الرقم التسلسلي
                            <input type="text" id="filterSerialNumber" class="form-control form-control-sm"
                                placeholder="ابحث عن الرقم التسلسلي">
                        </th>
                        <th>نوع السيارة
                            <select id="carTypeFilter" class="form-control form-control-sm">
                                <option value="">كل الأنواع</option>
                                <option value="سيارات غير السورية والاردنية واللبنانية">سيارات غير السورية والاردنية
                                    واللبنانية</option>
                                <option value="دراجات نارية">دراجات نارية</option>
                                <option value="شاحنات وباصات خليجية">شاحنات وباصات خليجية</option>
                                <option value="سيارات سورية">سيارات سورية</option>
                                <option value="سيارات لبنانية">سيارات لبنانية</option>
                                <option value="سيارات أردنية">سيارات أردنية</option>
                            </select>
                        </th>
                        <th>اسم السائق
                            <input type="text" id="filterDriverName" class="form-control form-control-sm"
                                placeholder="ابحث عن اسم السائق">
                        </th>
                        <th>رقم السيارة
                            <input type="text" id="filterCarNumber" class="form-control form-control-sm"
                                placeholder="ابحث عن رقم السيارة">
                        </th>
                        <th>مدة البقاء
                            <input type="text" id="filterStayDuration" class="form-control form-control-sm"
                                placeholder="ابحث عن مدة البقاء">
                        </th>
                        <th>رسم البقاء
                            <input type="text" id="filterStayFee" class="form-control form-control-sm"
                                placeholder="ابحث عن رسم البقاء">
                        </th>
                        <th>سجل خروج؟
                            <input type="text" id="filterCheckedOut" class="form-control form-control-sm"
                                placeholder="ابحث عن سجل الخروج">
                        </th>
                        <th>رسم الخروج
                            <input type="text" id="filterExitFee" class="form-control form-control-sm"
                                placeholder="ابحث عن رسم الخروج">
                        </th>
                        <th>رقم الدفتر
                            <input type="text" id="filterBookNumber" class="form-control form-control-sm"
                                placeholder="ابحث عن رقم الدفتر">
                        </th>
                        <th>خيارات</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($entries as $entry)
                        <tr>
                            <td>{{ $entry->serial_number }}</td>
                            <td>
                                <a href="{{ route('entry_statements.show', Crypt::encrypt($entry->id)) }}" style="color: #000;"
                                    title="عرض">
                                    {{ $entry->car_type }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('entry_statements.show', Crypt::encrypt($entry->id)) }}" style="color: #000;"
                                    title="عرض">
                                    {{ $entry->driver_name }}
                                </a>
                            </td>
                            <td>{{ $entry->car_number }}</td>
                            <td>
                                @if($entry->stay_duration == 2)
                                    اسبوعين
                                @elseif($entry->stay_duration == 4)
                                    شهر
                                @elseif($entry->stay_duration == 0)
                                    غير محدودة
                                @else
                                    ثلاث أشهر
                                @endif
                            </td>
                            <td>{{ number_format($entry->stay_fee, 2) }}</td>
                            <td>
                                @if($entry->is_checked_out == false)
                                    <span style="color: red;">لم تسجل الخروج</span>
                                @else
                                    <span style="color: green;">تم الخروج بتاريخ {{ $entry->checked_out_date }}</span>
                                @endif
                            </td>
                            <td>{{ $entry->exit_fee ? number_format($entry->exit_fee, 2) : 'لم تخرج' }}</td>
                            <td>{{ $entry->book_number ? $entry->book_number : '-' }}</td>
                            <td>
                                <div class="d-flex justify-content-start gap-1">
                                    <a href="{{ route('entry.logs', $entry->id) }}" class="btn btn-sm btn-info">
                                        سجل التحركات
                                    </a>
                                    <a href="{{ route('entry_statements.show', Crypt::encrypt($entry->id)) }}"
                                        class="btn btn-info btn-sm" title="عرض">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a href="{{ route('entry_statements.edit', Crypt::encrypt($entry->id)) }}"
                                        class="btn btn-primary btn-sm" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <button onclick="confirmDelete({{ $entry->id }})" class="btn btn-danger btn-sm" title="حذف">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>

                                    <form id="delete-form-{{ $entry->id }}"
                                        action="{{ route('entry_statements.destroy', $entry->id) }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-start mt-1">
                    {{ $entries->appends(request()->input())->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <script src="//cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json"></script>

    <script>
        $(document).ready(function () {
            var table = $('#entryTable').DataTable({
                var table = $('#entryTable').DataTable({
                    dom: 'Bfrtip',
                    paging: false,            // 🚫 إلغاء الباجينيشن تبع DataTables
                    info: false,              // 🚫 إخفاء "عرض 1 إلى 10 من..."
                    ordering: false,
                    searching: false,         // 🚫 إلغاء البحث العام (نستخدم فقط الفلاتر اللي فوق الأعمدة)
                    scrollX: true,
                    autoWidth: false,
                    buttons: [
                        {
                            extend: 'copy',
                            text: '<i class="fas fa-copy"></i> نسخ',
                            exportOptions: { columns: ':not(:last-child)' },
                            className: 'btn btn-sm shadow-sm rounded'
                        },
                        {
                            extend: 'excel',
                            text: '<i class="fas fa-file-excel"></i> Excel',
                            title: 'قائمة حركات الدخول - التاريخ: ' + new Date().toLocaleDateString('en-US'),
                            exportOptions: { columns: ':not(:last-child)' },
                            className: 'btn btn-sm shadow-sm rounded'
                        },
                        {
                            extend: 'print',
                            text: '<i class="fas fa-print"></i> طباعة',
                            title: 'قائمة حركات الدخول - التاريخ: ' + new Date().toLocaleDateString('en-US'),
                            exportOptions: { columns: ':not(:last-child)' },
                            className: 'btn btn-sm shadow-sm rounded'
                        }
                    ],
                    language: {
                        url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json"
                    }
                });

                $('#filterSerialNumber').on('change', function () {
                    var selectedType = $(this).val();
                    table.column(0).search(selectedType).draw();
                });
                $('#carTypeFilter').on('keyup', function () {
                    table.column(1).search(this.value).draw();
                });
                $('#filterDriverName').on('keyup', function () {
                    table.column(2).search(this.value).draw();
                });
                $('#filterCarNumber').on('keyup', function () {
                    table.column(3).search(this.value).draw();
                });
                $('#filterStayDuration').on('keyup', function () {
                    table.column(4).search(this.value).draw();
                });
                $('#filterStayFee').on('keyup', function () {
                    table.column(5).search(this.value).draw();
                });
                $('#filterCheckedOut').on('keyup', function () {
                    table.column(6).search(this.value).draw();
                });
                $('#filterBookNumber').on('keyup', function () {
                    table.column(8).search(this.value).draw();
                });
                $('#filterExitFee').on('keyup', function () {
                    table.column(7).search(this.value).draw();
                });
            });

            function confirmDelete(id) {
                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: "لا يمكن التراجع بعد الحذف!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'نعم، احذف',
                    cancelButtonText: 'إلغاء'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`delete-form-${id}`).submit();
                    }
                });
            }

            function openModal(entryId) {
                Swal.fire({
                    title: 'إجراء مطلوب',
                    html: `
                                                    <div>
                                                        <a href="#" class="btn btn-danger btn-sm"
                                                            title="عرض">
                                                            تسجيل خروج
                                                        </a>
                                                    </div>
                                                    <div class="mt-2">
                                                        <a href="#" class="btn btn-info btn-sm"
                                                            title="عرض">
                                                            تمديد فترة البقاء
                                                        </a>
                                                    </div>
                                                `,
                    showCancelButton: true,
                    confirmButtonText: 'تأكيد',
                    cancelButtonText: 'إلغاء',
                    preConfirm: () => {
                        const exitFee = document.getElementById('exitFee').value;
                        const stayDuration = document.getElementById('stayDuration').value;
                        console.log('إدخال رسم الخروج:', exitFee, 'مدة البقاء:', stayDuration);
                    }
                });
            }
    </script>
@endsection

<style>
    #entryTable {
        font-size: 14px;
    }

    #entryTable thead th,
    #entryTable tbody td {
        padding: 8px 10px;
    }

    #entryTable thead tr {
        direction: rtl !important;
        text-align: right !important;
    }

    .dataTables_filter {
        float: right;
        text-align: right;
        margin-bottom: 1rem;
    }

    .dataTables_filter label {
        font-weight: bold;
        font-size: 16px;
    }

    .dataTables_filter input {
        border: 1px solid #ced4da;
        border-radius: 10px;
        padding: 6px 12px;
        margin-right: 8px;
        width: 250px;
    }

    div.dataTables_wrapper {
        direction: rtl !important;
        text-align: right !important;
    }

    .form-inline .form-group {
        display: inline-block;
        margin-right: 10px;
    }

    .stat-card {
        background-color: #f8f9fa;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 20px;
        text-align: center;
    }

    .stat-title {
        font-size: 16px;
        font-weight: bold;
        color: #333;
    }

    .stat-value {
        font-size: 18px;
        color: #007bff;
        font-weight: bold;
    }

    .row.mb-4 {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .row .col-md-8 {
        display: flex;
        align-items: center;
    }

    .row .col-md-4 {
        display: flex;
        justify-content: flex-end;
    }




    th,
    td {
        white-space: nowrap;
    }


    table.dataTable th {
        min-width: 100px;
    }

    .dataTables_wrapper {
        overflow-x: auto;
    }
</style>