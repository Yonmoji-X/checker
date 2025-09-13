@foreach ($recordsGroups as $headId => $recordsGroup)
    <div id="head_{{ $headId }}" class="template mb-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border">
        <div class="text-sm text-gray-600 dark:text-gray-400">
            {{ $recordsGroup->first()->template->title ?? '' }} ({{ $recordsGroup->first()->created_at->format('Y-m-d H:i') }})
        </div>

        <ul class="list-none mt-2 text-gray-600 dark:text-gray-400 text-sm">
            @foreach ($recordsGroup as $record)
                @if($record->check_item)
                    <li class="mb-1">チェック: {{ $record->check_item ? 'はい' : 'いいえ' }}</li>
                @endif
                @if($record->content_item)
                    <li class="mb-1">内容: {{ $record->content_item }}</li>
                @endif
                @if($record->photo_item)
                    <li class="mb-1">画像: <img src="{{ asset('storage/' . $record->photo_item) }}" class="w-24"></li>
                @endif
                @if($record->temperature_item)
                    <li class="mb-1">温度: {{ $record->temperature_item }}℃</li>
                @endif
            @endforeach
        </ul>
    </div>
@endforeach
