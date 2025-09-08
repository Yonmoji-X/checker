                            @foreach($attendancerequests as $req)
                                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                        {{ $req->member->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                        {{ \Carbon\Carbon::parse($req->attendance_date)->format('Y-m-d') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                        {{ \Carbon\Carbon::parse($req->clock_in)->format('H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                        {{ \Carbon\Carbon::parse($req->clock_out)->format('H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                        {{ $req->break_minutes }}分
                                    </td>
                                    <td class="relative px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200 group">
                                        <span class="truncate cursor-pointer" title="{{ $req->remarks }}">
                                            {{ Str::limit($req->remarks, 20) }}
                                        </span>
                                        <!-- ホバー時に表示されるツールチップ -->
                                        <!-- <div class="absolute left-1/2 -translate-x-1/2 bottom-full hidden group-hover:block w-64 bg-gray-800 text-white text-xs rounded py-2 px-3 z-50">
                                            {{ $req->remarks }}
                                        </div> -->

                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($req->status === 'pending')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">未承認</span>
                                        @elseif($req->status === 'approved')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">承認</span>
                                        @elseif($req->status === 'rejected')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">却下</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                        <div class="flex justify-center space-x-2">
                                            @if($req->status !== 'approved')
                                                <form action="{{ route('attendancerequests.approve', $req->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                        class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-green-600 bg-green-100 rounded-md hover:bg-green-200 dark:bg-green-900 dark:text-green-300 dark:hover:bg-green-800">
                                                        承認
                                                    </button>
                                                </form>
                                            @endif

                                            @if($req->status !== 'rejected')
                                                <form action="{{ route('attendancerequests.reject', $req->id) }}" method="POST" onsubmit="return confirm('却下してよろしいですか？')">
                                                    @csrf
                                                    <button type="submit"
                                                        class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-red-600 bg-red-100 rounded-md hover:bg-red-200 dark:bg-red-900 dark:text-red-300 dark:hover:bg-red-800">
                                                        却下
                                                    </button>
                                                </form>
                                            @endif

                                            <a href="{{ route('attendancerequests.edit', $req->id) }}"
                                               class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-indigo-600 bg-indigo-100 rounded-md hover:bg-indigo-200 dark:bg-indigo-900 dark:text-indigo-300 dark:hover:bg-indigo-800">
                                               修正
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
