<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>بطاقة الدخول</title>
    <style>
        @media print {
            @page {
                size: A7 portrait;
                margin: 0;
            }

            body {
                margin: 0;
                -webkit-print-color-adjust: exact;
            }
        }

        body {
            font-family: 'Arial', sans-serif;
            direction: rtl;
            background-image: url('{{ asset('images/entry_card_bg.jpg') }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            width: 74mm;
            height: 105mm;
            margin: 0;
            position: relative;
        }

        .container {
            position: absolute;
            top: 45mm;
            right: 8mm;
            font-size: 10pt;
            color: #000;
        }

        .container p {
            margin: 3mm 0;
        }

        .bold {
            font-weight: bold;
        }

        .qr-container {
            position: absolute;
            top: 35mm;
            left: 5mm;
        }

        .qr-container img {
            width: 30mm;
            height: 30mm;
        }

        .footer {
            position: absolute;
            bottom: 4mm;
            width: 100%;
            text-align: center;
            color: red;
            font-size: 8pt;
        }
    </style>
</head>
<body onload="window.print();">

    <div class="container">
        <p><span class="bold">اسم المالك:</span> {{ $entryCard->owner_name }}</p>
        <p><span class="bold">رقم السيارة:</span> {{ $entryCard->car_number }}</p>
        <p><span class="bold">مدة البقاء:</span> {{ $entryCard->stay_duration }}</p>
        <p><span class="bold">نوع السيارة:</span> {{ $entryCard->car_type }}</p>
        <p>
            <span class="bold">تاريخ الدخول:</span> {{ \Carbon\Carbon::parse($entryCard->entry_date)->format('j/n/Y') }}<br>
            <span class="bold">تاريخ الانتهاء:</span> {{ \Carbon\Carbon::parse($entryCard->exit_date)->format('j/n/Y') }}
        </p>
    </div>

    <div class="qr-container">
        <img src="{{ asset('storage/qrcodes/' . $entryCard->qr_code) }}" alt="QR Code">
    </div>

    <div class="footer">
        الرجاء التقيد بالتعليمات المذكورة في الطرف الخلفي للبطاقة
    </div>

</body>
</html>
