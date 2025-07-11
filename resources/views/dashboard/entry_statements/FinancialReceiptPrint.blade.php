<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>بيان سيارة للطباعة</title>
    <style>
        @media print {
            @page {
                size: A5;
                margin: 0;
            }
        }

        body {
            font-family: 'Arial', sans-serif;
            font-weight: bold;
            direction: rtl;
            margin: 1mm;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header img {
            width: 100px;
        }

        .header .center {
            text-align: center;
            flex: 1;
        }

        .header .right {
            text-align: right;
            font-size: 14px;
            line-height: 1.6;
        }

        table {
            width: 100%;
            margin-top: 20px;
            font-size: 15px;
        }


        td {
            border: 1.5px solid black;
            padding: 4px;
            text-align: center;
            margin: 5px;
        }

        tr {
            margin: 5px !important;
        }

        .bold {
            font-weight: bold;
        }

        .amount-box {
            border: 1px solid black;
            padding: 10px;
            margin-top: 5px;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
        }

        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 14px;
        }

        .qr-codes {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .qr-codes img {
            width: 100px;
            height: 100px;
        }
    </style>
</head>

<body onload="window.print();">

    <div class="header" style="direction: ltr">
        <img src="/qr.png" alt="QR Code">

        <div class="center">
            <img src="/logo.jpg" alt="شعار" style="width: 60px;"><br>
        </div>

        <div class="right">
            أمانة نصيب<br>
            بيان تصفية<br>
            <span id="today-date"></span><br>
            <span style="color: green;">إيصال قبض</span>
        </div>
    </div>

    <table>
        <tr>
            <td>رقم الإيصال</td>
            <td>{{ $data->receipt_number }}</td>
            <td>رقم التصفية</td>
            <td>{{ $data->statement_number }}</td>
        </tr>
        <tr>
            <td>السائق</td>
            <td>{{ $data->driver_name }}</td>
            <td>رقم السيارة</td>
            <td>{{ $data->car_number }}</td>
        </tr>
        <tr>
            <td>رسم العبور</td>
            <td>{{ $data->fees }}</td>
            <td>رسوم إضافية</td>
            <td>{{ $data->additionalFee }}</td>
        </tr>
        <tr>
            <td colspan="1">مجموع الغرامات</td>
            <td colspan="3">{{ $data->violations_total }}</td>
        </tr>
        <tr>
            <td colspan="1">الإجمالي</td>
            <td colspan="3">{{ $data->total_amount }}</td>
        </tr>
    </table>

    <div class="amount-box">
         الإجمالي رقماً: {{ $data->total_amount }}
    </div>
    <div class="amount-box">
         الإجمالي كتابة: {{ $totalInWords }}
    </div>

    <div class="footer">الصندوق</div>

    <script>
        const today = new Date();
        const day = today.getDate();
        const month = today.getMonth() + 1;
        const year = today.getFullYear();
        const formattedDate = `${day}/${month}/${year}`;
        document.getElementById('today-date').innerText = formattedDate;
    </script>
</body>

</html>