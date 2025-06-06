<!DOCTYPE html>
<html>
<head>
    <title>Xác nhận đặt lịch khám</title>
</head>
<body>
    <h1>Xác nhận đặt lịch khám</h1>
    <p>Xin chào {{ $bookingData['patientName'] }},</p>
    <p>Bạn đã yêu cầu đặt lịch khám với thông tin sau:</p>
    <ul>
        <li><strong>Ngày khám:</strong> {{ \Carbon\Carbon::parse($bookingData['date'])->format('d/m/Y') }}</li>
        <li><strong>Khung giờ:</strong> {{ $bookingData['timeType'] }}</li>
        <li><strong>Mã khám bệnh:</strong> {{ $bookingData['appointmentCode'] }} (Vui lòng mang theo mã này khi đến khám)</li>
    </ul>
    <p>Vui lòng nhấn vào liên kết dưới đây để xác nhận đặt lịch:</p>
    <p><a href="{{ $confirmationUrl }}">Xác nhận đặt lịch</a></p>
    <p>Liên kết này sẽ hết hạn sau 24 giờ.</p>
    <p>Nếu bạn không thực hiện yêu cầu này, vui lòng bỏ qua email này.</p>
    <p>Trân trọng,<br>{{ config('app.name') }}</p>
</body>
</html>