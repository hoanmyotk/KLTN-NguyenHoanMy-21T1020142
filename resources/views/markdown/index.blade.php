<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Markdown List</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1>Markdown List</h1>
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Description</th>
                    <th>Doctor</th>
                </tr>
            </thead>
            <tbody>
                @foreach($markdowns as $markdown)
                    <tr>
                        <td>{{ $markdown->id }}</td>
                        <td>{{ $markdown->description }}</td>
                        <td>{{ $markdown->doctor ? $markdown->doctor->firstName : '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <form action={{url('/vnpay_payment')}} method="post">
            @csrf
            <button type="submit" name="Rec">Thanh toán bằng VNPAY</button>
        </form>
    </div>
</body>
</html>
