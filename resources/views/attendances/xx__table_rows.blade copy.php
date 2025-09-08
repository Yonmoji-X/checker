@foreach ($attendances as $attendance)
<tr>
    <td class="px-6 py-4">{{ $attendance->attendance_date }}</td>
    <td class="px-6 py-4">{{ $attendance->clock_in }}</td>
    <td class="px-6 py-4">{{ $attendance->clock_out }}</td>
    <td class="px-6 py-4">{{ $attendance->member->name }}</td>
    <td class="px-6 py-4">
        @php
            $totalBreakDuration = $attendance->breakSessions->sum('break_duration');
        @endphp
        {{ $totalBreakDuration }} 分
    </td>
    <td class="px-6 py-4">
        <form action="{{ route('attendances.update', $attendance->id) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="text" name="attendance" value="{{ $attendance->attendance }}" class="border px-2 py-1 rounded w-full">
            <button type="submit" class="mt-1 px-2 py-1 bg-indigo-600 text-white rounded">保存</button>
        </form>
    </td>
</tr>
@endforeach
