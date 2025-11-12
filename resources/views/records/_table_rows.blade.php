@foreach ($groups as $recordsGroup)
    @php
        $firstRecord = $recordsGroup->first();
        if (!$firstRecord) continue; // 空グループはスキップ
        $headId = $firstRecord->head_id;
    @endphp

    <div id="head_{{ $headId }}" class="template mb-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border">

        {{-- head_id のグループタイトル --}}
        <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">
            <!-- グループID: {{ $headId }} -->
            {{ $firstRecord->created_at->format('Y/m/d H:i') }}
        </div>

        {{-- 各レコードを個別に表示 --}}
        @foreach ($recordsGroup as $record)
            @if($record instanceof \App\Models\Record)
            <div class="mb-3 p-2 border rounded bg-white dark:bg-gray-800">
                <div class="font-semibold text-gray-700 dark:text-gray-200 text-sm mb-1">
                    {{ optional($record->template)->title ?? '' }}
                    <!-- ({{ optional($record->created_at)?->format('Y-m-d H:i') ?? '' }}) -->
                </div>
                <ul class="list-none text-gray-600 dark:text-gray-400 text-sm">
                    @if(isset($record->check_item) && $record->check_item == 1)
                        <li class="mb-1">チェック: はい</li>
                    @elseif(isset($record->check_item) && $record->check_item == 0)
                        <li class="mb-1">チェック: いいえ</li>
                    @endif
                    @if(isset($record->content_item) && $record->content_item !== '')
                        <li class="mb-1">内容: {{ $record->content_item }}</li>
                    @endif
                    @if(isset($record->photo_item) && $record->photo_item !== '')
                        <li class="mb-1">
                            画像: <img src="{{ asset('storage/' . $record->photo_item) }}" class="w-24">
                        </li>
                    @endif
                    @if(isset($record->temperature_item) && $record->temperature_item != 0)
                        <li class="mb-1">温度: {{ $record->temperature_item }}℃</li>
                    @endif
                </ul>
            </div>
            @endif
        @endforeach

    </div>
@endforeach
