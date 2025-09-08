@foreach ($attendances as $attendance)
<tr class="h-12"> <!-- 高さを指定 -->
    <td class="py-2">{{ $attendance->attendance_date }}</td>
    <td class="py-2">{{ $attendance->clock_in }}</td>
    <td class="py-2">{{ $attendance->clock_out }}</td>
    <td class="py-2">{{ $attendance->member->name }}</td>
    <td class="py-2">
        @php
            $totalBreakDuration = $attendance->breakSessions->sum('break_duration');
        @endphp
        {{ $totalBreakDuration }} 分
    </td>
    <td class="py-2">
        <form action="{{ url('/attendances/' . $attendance->id) }}" method="POST" class="flex items-center gap-2 max-w-xs">
            @csrf
            @method('PUT') <!-- ここでPUTを指定 -->
            <input 
                type="text" 
                name="attendance" 
                value="{{ $attendance->attendance }}"
                class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm"
            />

            <button 
                type="submit" 
                class="px-3 py-2 bg-green-500 text-white text-xs rounded-md hover:bg-green-600"
            >
                保存
            </button>
        </form>
    </td>
</tr>

@endforeach
