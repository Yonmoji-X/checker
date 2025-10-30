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
                                                $totalBreakDuration = $attendance->breakSessions->sum(function($breakSession) {
                                                             if (is_null($breakSession->break_in) && is_null($breakSession->break_out)) {
                                                                // 申請休憩（打刻なし）
                                                                return $breakSession->break_duration;
                                                            } elseif ($breakSession->break_out) {
                                                                // 通常の休憩（終了時刻がある）
                                                                return $breakSession->break_duration;
                                                            } else {
                                                                // 休憩中 or データ不完全 → カウントしない
                                                                return 0;
                                                            }
                                                });
                                                $isCurrentlyOnBreak = $attendance->breakSessions->contains(function($breakSession) {
                                                    return $breakSession->break_in && is_null($breakSession->break_out);
                                                });
                                            @endphp
                                            {{ $totalBreakDuration }} 分
                                          

                                            @if ($isCurrentlyOnBreak)
                                                <span class="text-red-500">（休憩中）</span>
                                            @endif
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
        <form action="{{ url('/attendances/' . $attendance->id) }}" method="POST" class="flex items-center gap-2 max-w-xs mx-auto">
            @csrf
            @method('PUT')
            <input type="text" name="attendance" value="{{ $attendance->attendance }}" class="flex-1 px-3 py-2 border rounded-md text-sm">
            <button type="submit" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-blue-600 bg-blue-100 rounded-md hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-300 dark:hover:bg-blue-800">保存</button>
            <!-- <button type="submit" class="px-3 py-2 bg-green-500 text-white text-xs rounded-md hover:bg-green-600">保存</button> -->
        </form>
    </td>
</tr>
@endforeach
