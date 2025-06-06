<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Doctor</th>
            <th>Date</th>
            <th>Time</th>
        </tr>
    </thead>
    <tbody>
        @foreach($schedules as $schedule)
        <tr>
            <td>{{ $schedule->id }}</td>
            <td>{{ $schedule->doctor_id }}</td>
            <td>{{ $schedule->date }}</td>
            <td>{{ $schedule->timeTypeRelation ? $schedule->timeTypeRelation->valueVi : 'N/A' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
