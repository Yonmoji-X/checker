@foreach ($attendances as $attendance)
<tr class="h-12">
    <td class="py-2 text-center">{{ $attendance->attendance_date }}</td>
    <td class="py-2 text-center">{{ $attendance->clock_in }}</td>
    <td class="py-2 text-center">{{ $attendance->clock_out }}</td>
    <td class="py-2 text-center">{{ $attendance->member->name }}</td>
    <td class="py-2 text-center">
        @php
            $totalBreakDuration = $attendance->breakSessions->sum('break_duration');
        @endphp
        {{ $totalBreakDuration }} 分
    </td>
    <td class="py-2 text-center">
        <form action="{{ url('/attendances/' . $attendance->id) }}" method="POST" class="flex items-center gap-2 max-w-xs mx-auto">
            @csrf
            @method('PUT')
            <input type="text" name="attendance" value="{{ $attendance->attendance }}" class="flex-1 px-3 py-2 border rounded-md text-sm">
            <button type="submit" class="px-3 py-2 bg-green-500 text-white text-xs rounded-md hover:bg-green-600">保存</button>
        </form>
    </td>
</tr>
@endforeach
