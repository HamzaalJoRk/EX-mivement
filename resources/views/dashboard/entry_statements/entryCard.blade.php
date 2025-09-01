<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بطاقة دخول السيارة - الجمارك السورية</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @media print {
            @page {
                size: A6 landscape;
                margin: 2mm;
            }

            body {
                margin: 0;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        @font-face {
            font-family: 'TheYearOfTheCamel';
            src: url('{{ asset('fonts/TheYearofTheCamel-ExtraBold.otf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        body {
            font-family: 'TheYearOfTheCamel', sans-serif;
            direction: rtl;
            width: 148mm;
            height: 101mm;
            background: white;
            position: relative;
            border: 1px solid #ccc;
            overflow: hidden;
            margin: 0 auto;
            padding: 0;
            font-size: 16px;
            color: #333;
            line-height: 1.6;
        }

        .header {
            text-align: center;
            font-size: 20px;
            display: flex;
            align-items: center;
            justify-content: space-around;
            margin-bottom: 15px;
        }

        .header_bottom {
            border-bottom: 3px solid #0a4b46ff;
        }

        .content {
            padding: 20px 30px;
            position: relative;
        }

        .info {
            margin-top: 15px;
        }

        .info p {
            margin-bottom: 5px;
        }

        .bold {
            font-weight: bold;
        }

        .footer {
            text-align: center;
            font-size: 10px;
            color: red;
            font-weight: bold;
        }

        .card-body {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .border-top {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: #8B0000;
        }

        .border-bottom {
            position: absolute;
            bottom: 40px;
            left: 0;
            width: 100%;
            height: 5px;
            background: #8B0000;
        }

        .border-left {
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: #8B0000;
        }

        .border-right {
            position: absolute;
            top: 0;
            right: 0;
            width: 5px;
            height: 100%;
            background: #8B0000;
        }

        .corner {
            position: absolute;
            width: 20px;
            height: 20px;
            border: 3px solid #8B0000;
        }

        .corner-tl {
            top: 15px;
            left: 15px;
            border-right: none;
            border-bottom: none;
        }

        .corner-tr {
            top: 15px;
            right: 15px;
            border-left: none;
            border-bottom: none;
        }

        .corner-bl {
            bottom: 55px;
            left: 15px;
            border-right: none;
            border-top: none;
        }

        .corner-br {
            bottom: 55px;
            right: 15px;
            border-left: none;
            border-top: none;
        }

        .print-btn {
            position: absolute;
            top: 10px;
            left: 10px;
            background: #8B0000;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            z-index: 10;
        }

        .print-btn:hover {
            background: #A52A2A;
        }

        .stamp {
            position: absolute;
            top: 70px;
            left: 30px;
            width: 70px;
            height: 70px;
            border: 3px solid #8B0000;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #8B0000;
            font-weight: bold;
            font-size: 14px;
            transform: rotate(-15deg);
            opacity: 0.7;
            text-align: center;
            line-height: 1.3;
        }
    </style>
</head>

<body onclick="window.print()">

    <div class="content">
        <div class="header">
            <h3 style="color: #0a4b46ff">الجمهورية العربية السورية - الجمارك</h3>
            <img src="{{ asset('/logo.jpg') }}" alt="" width="75">
        </div>
        <div class="header_bottom">
        </div>
        <div class="card-body">
            <div class="info">
                <p><span class="bold">اسم السائق:</span> {{ $entry->driver_name }}</p>
                <p><span class="bold">رقم السيارة:</span> {{ $entry->car_number }}</p>
                @if (!($entry->checked_out_date))
                    <p><span class="bold">مدة البقاء:</span>
                        @php
                            $weeks = $entry->stay_duration;
                            $months = floor($weeks / 4);
                            $remainingWeeks = $weeks % 4;
                        @endphp

                        @if ($weeks >= 4)
                            {{ $months }} شهر{{ $months > 1 ? 'اً' : '' }}
                            @if ($remainingWeeks > 0)
                                و{{ $remainingWeeks }} أسبوع{{ $remainingWeeks > 1 ? 'اً' : '' }}
                            @endif
                        @elseif($weeks == 0)
                            غير محدودة
                        @else
                            {{ $weeks }} أسبوع{{ $weeks > 1 ? 'اً' : '' }}
                        @endif
                    </p>
                @endif
                @if ($entry->checked_out_date)
                    <p><span class="bold">أمانة الخروج:</span> {{ $entry->exitBorderCrossing->name }}</p>
                @else
                    <p><span class="bold">أمانة الدخول:</span> {{ $entry->borderCrossing->name }}</p>
                @endif
                <p><span class="bold">نوع السيارة:</span>
                    @if($entry->car_type === 'شاحنات وباصات خليجية' || $entry->book_type === 'عام')
                        عمومي
                    @else
                        سياحي
                    @endif
                </p>
                @if ($entry->checked_out_date)
                    <p><span class="bold">تاريخ الخروج:</span> {{ $entry->checked_out_date }}</p>
                @else
                    <p><span class="bold">تاريخ الدخول:</span> {{ $createdAt->format('Y-m-d') }}</p>
                    @if (($entry->car_type === 'سيارات سورية') || $entry->car_type === 'سيارات لبنانية' || $entry->car_type === 'سيارات أردنية')
                        <p><span class="bold">تاريخ الانتهاء:</span> لا يوجد</p>
                    @else
                        <p><span class="bold">تاريخ الانتهاء:</span> {{ $allowedStay->format('Y-m-d') }}</p>
                    @endif
                @endif
            </div>
            <div class="qr_imge">
                {!! QrCode::size(130)->generate($entry->serial_number) !!}
            </div>

        </div>
        <div class="footer">
            <span>
                @if (($entry->type == 'خروج'))
                    <br>
                @endif
                تنبيه هام: <br />
                إن أي تأخير عن المدة المسموح بالبقاء بها يعرض صاحب البطاقة لغرامة تأخير
            </span>
        </div>
    </div>
    </div>

    <script>
        window.print();
    </script>

    <style>
        @media print {
            @page {
                size: A6 landscape;
                margin: 2mm;
            }

            body {
                margin: 0;
            }

            .page {
                page-break-after: always;
                width: 148mm;
                height: 101mm;
            }

            .back {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                font-size: 16px;
            }
        }
    </style>
</body>

</html>