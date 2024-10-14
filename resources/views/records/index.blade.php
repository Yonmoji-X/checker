



<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('チェック一覧') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <a href="{{ url('/records/create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">
                        出退勤
                    </a>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                        <div>
                            <!-- <input type="hidden" name=""> -->
                            <label for="member_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">メンバーの種類</label>
                            <select name="member_status" id="member_status" onchange="filterDataRecords()" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <!-- <option value="">選択してください</option> -->
                                <option value="0">従業員</option>
                                <option value="1">管理者</option>
                            </select>
                        </div>
                        <div>
                            <label for="clock_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ステータス</label>
                            <select name="clock_status" id="clock_status" onchange="filterDataRecords()" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <!-- <option value="">選択してください</option> -->
                                <option value="1">出勤時</option>
                                <option value="0">退勤時</option>
                            </select>
                        </div>
                        <div>
                            <label for="member_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">メンバー</label>
                            <select name="member_id" id="member_id" onchange="filterDataRecords()" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @foreach ($members as $member)
                                    <option value="{{ $member->id }}">{{ $member->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div id="items_container" class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                        <!-- JavaScriptでここにフィルタリングされたデータを表示 -->
                    </div>
                    <!-- ==================== -->

                    <!-- ==================== -->
                </div>

            </div>
        </div>
    </div>
    <script>
    let basePath = "{{ url('/') }}";
    console.log("_basePath_");
    console.log(basePath);
    if (basePath === "https://chiburi.sakura.ne.jp/checker") {
        basePath = "https://chiburi.sakura.ne.jp/checker";
        console.log(basePath);
    } else {
        basePath = '';
    }
    console.log("_basePath_処理後");
    console.log(basePath);

    const jsonRecords = `<?= $jsonRecords ?>`;
    const jsonTemplates = `<?= $jsonTemplates ?>`;
    let dataRecords = [];
    let dataTemplates = [];

    try {
        dataRecords = JSON.parse(jsonRecords);
        dataTemplates = JSON.parse(jsonTemplates);
        console.log(`dataRecords:${dataRecords}`);
        console.log(`dataTemplates:${dataTemplates}`);
        console.log(`dataTemplates: ${JSON.stringify(dataTemplates, null, 2)}`);
    } catch (e) {
        console.error('Error parsing JSON:', e);
    }
    dataRecords.sort((a, b) => a.id - b.id);
    console.log(dataRecords);

    function filterDataRecords() {
        const memberStatus_selected = document.getElementById('member_status').value;
        const clockStatus_selected = document.getElementById('clock_status').value;
        const member_selected = document.getElementById('member_id').value;

        const filterDataRecords = dataRecords.filter(row => row.member_status == memberStatus_selected && row.clock_status == clockStatus_selected && row.member_id == member_selected);
        console.log(`filterDataRecords：${filterDataRecords}`);
        displayDataRecords(filterDataRecords);

        console.log(filterDataRecords);
    }

    function displayDataRecords(filteredDataRecords) {
        const container = document.getElementById('items_container');
        container.innerHTML = '';

        filteredDataRecords.forEach((row, index) => {
            const item = document.createElement('div');
            item.classList.add(
                'template',
                'mb-4',
                'p-4',
                'bg-gray-50',
                'dark:bg-gray-700',
                'rounded-lg',
                'border',
                'border-gray-300',
                'dark:border-gray-600'
            );

            const titleBox = document.createElement('div');

            // created_atを東京時間に変換する部分
            const createdAtDate = new Date(row.created_at);
            const tokyoTime = createdAtDate.toLocaleString("ja-JP", {
                timeZone: "Asia/Tokyo",
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            });

            titleBox.innerHTML = `
                <span>${tokyoTime}</span>
            <!-- <a href="${basePath}/records/${row.id}/edit"
            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mx-2">
                編集
            </a>
            <form action="${basePath}/records/${row.id}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                    　削除
                </button>
            </form> -->
            `;

            const record = dataTemplates.find(template => template.id === row.template_id);
            const title_Template = record ? record.title : 'Record not found';

            const contentsUl = document.createElement('ul');
            contentsUl.classList.add(
                'text-gray-600',
                'dark:text-gray-400',
                'text-sm',
                'mt-4',
                'list-none'
            );

            const titleTemplate = document.createElement('span');
            titleTemplate.textContent = `🔹 ${title_Template}`;
            titleTemplate.classList.add(
                'text-gray-600',
                'dark:text-gray-400',
                'text-sm'
            );
            contentsUl.appendChild(titleTemplate);

            // クラスリストをまとめて追加する関数
            function addClassList(element) {
                element.classList.add(
                    'text-black',
                    'dark:text-white',
                    'text-lg',
                    'font-medium',
                    'bg-blue-100',
                    'dark:bg-blue-800',
                    'px-4',
                    'py-2',
                    'rounded-md',
                    'shadow-sm',
                    'hover:bg-blue-200',
                    'dark:hover:bg-blue-700'
                );
            }

            if (row.check_item != null) {
                const checkLi = document.createElement('li');
                checkLi.textContent = row.check_item == 1 ? 'はい' : 'いいえ';
                contentsUl.appendChild(checkLi);
                addClassList(checkLi);  // クラスを追加
            }

            if (row.photo_item != null) {
                const photoLi = document.createElement('li');
                photoLi.textContent = 'ここに画像が入ります。';
                contentsUl.appendChild(photoLi);
                addClassList(photoLi);  // クラスを追加
            }

            if (row.content_item != null) {
                const contentLi = document.createElement('li');
                contentLi.textContent = row.content_item;
                contentsUl.appendChild(contentLi);
                addClassList(contentLi);  // クラスを追加
            }

            if (row.temperature_item != null) {
                const temperatureLi = document.createElement('li');
                temperatureLi.textContent = row.temperature_item;
                contentsUl.appendChild(temperatureLi);
                addClassList(temperatureLi);  // クラスを追加
            }


            item.appendChild(titleBox);
            item.appendChild(contentsUl);

            if (row.id == row.head_id) {
                item.id = `head_${row.head_id}`;
                container.prepend(item);
            } else {
                const headItem = document.getElementById(`head_${row.head_id}`);
                if (headItem) {
                    headItem.appendChild(contentsUl);
                }
            }
        });
    }

    window.onload = function() {
        filterDataRecords();
    };
</script>

</x-app-layout>

