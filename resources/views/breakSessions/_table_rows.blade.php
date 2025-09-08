                            @foreach ($breaksessions as $breaksession)
                                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                        {{ optional($breaksession->member)->name ?? '不明なメンバー' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                        @if($breaksession->attendance_request_id && $breaksession->attendanceRequest)
                                            {{ \Carbon\Carbon::parse($breaksession->attendanceRequest->attendance_date)->format('Y-m-d') }}
                                        @else
                                            {{ $breaksession->created_at->format('Y-m-d') }}
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                        {{ $breaksession->break_in }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                        {{ $breaksession->break_out }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                        {{ $breaksession->break_duration }} 分
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                        {{ $breaksession->attendance_request_id ? '申請データ' : '' }} 
                                    </td>
                                    <!-- <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                        <a href="{{ route('breaksessions.show', $breaksession) }}"
                                           class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-blue-600 bg-blue-100 rounded-md 
                                                  hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-300 dark:hover:bg-blue-800">
                                            詳細
                                        </a>
                                    </td> -->
                                </tr>
                            @endforeach