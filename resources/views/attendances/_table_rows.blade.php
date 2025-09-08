@foreach ($attendances as $attendance)
<tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
        {{ $attendance->attendance_date }}
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
        {{ $attendance->clock_in }}
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
        {{ $attendance->clock_out }}
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
        {{ $attendance->member->name }}
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
        @php
            $totalBreakDuration = $attendance->breakSessions->sum('break_duration');
        @endphp
        {{ $totalBreakDuration }} 分
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
        <form action="{{ url('/attendances/' . $attendance->id) }}" method="POST" class="flex items-center gap-2 max-w-xs mx-auto">
            @csrf
            @method('PUT')
            <input type="text" name="attendance" value="{{ $attendance->attendance }}" class="flex-1 px-3 py-2 border rounded-md text-sm">
            <button type="submit" class="px-3 py-2 bg-green-500 text-white text-xs rounded-md hover:bg-green-600">保存</button>
        </form>
    </td>
</tr>
@endforeach
