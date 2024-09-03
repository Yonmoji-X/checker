



<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Records List') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <a href="{{ url('/records/create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">
                        チェック
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
        console.log(basePath);

            // 比較のために末尾のスラッシュを削除しておくと、比較がより確実になります
            // basePath = basePath.replace(/\/$/, '');  // 末尾のスラッシュを削除

        if (basePath === "https://chiburi.sakura.ne.jp") {
                basePath === "https://chiburi.sakura.ne.jp/checker"
                // basePath = '{{ url('/checker') }}';
                console.log(basePath);
        } else {
                basePath = '';
        }
        const jsonRecords =  `<?= $jsonRecords ?>`;
        const jsonTemplates =  `<?= $jsonTemplates ?>`;
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
            // console.log(sortDataRecords(filterDataRecords));
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
        titleBox.innerHTML = `
            <span>${row.created_at}</span>
            <span>_ID：${row.id}</span>
            <span>_head ID：${row.head_id}</span>
            <a href="${basePath}/records/${row.id}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">詳細</a>
            <a href="${basePath}/records/${row.id}/edit"
            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mx-2">
                編集
            </a>
            <form action="${basePath}/records/${row.id}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                    削除
                </button>
            </form>
        `;

        // const headTime = document.createElement('span');
        // headTime.textContent = `${row.created_at}`;  // テンプレートIDを表示
        const record = dataTemplates.find(templete => templete.id === row.template_id);

        const title_Template = record ? record.title : 'Record not found';
        console.log(title_Template); // "40秒間、手を洗いましたか？"
        // const headId = document.createElement('span');
        // headId.textContent = `head ID: ${row.head_id}`;  // テンプレートIDを表示
        // const thisId = document.createElement('span');
        // thisId.textContent = `ID: ${row.id}`;  // テンプレートIDを表示
        const contentsUl = document.createElement('ul');
                contentsUl.classList.add(
                    'text-gray-600',
                    'dark:text-gray-400',
                    'text-sm',
                    'mt-4',
                    'list-none'
                );
        const titleTemplate = document.createElement('span');
        titleTemplate.textContent = `>${title_Template}`;
        titleTemplate.classList.add(
            'text-gray-600',
            'dark:text-gray-400',
            'text-sm'
        );
        contentsUl.appendChild(titleTemplate);
        // contentsUl.textContent = `>${title_Template}`;


        if (row.check_item != null) {
            const checkLi = document.createElement('li');
            if (row.check_item == 1) {
                checkLi.textContent = 'はい';
            } else if (row.check_item == 0) {
                checkLi.textContent = 'いいえ';
            }
            contentsUl.appendChild(checkLi);
        }
        if (row.photo_item != null) {
            const photoLi = document.createElement('li');
            photoLi.textContent = 'ここに画像が入ります。';
            contentsUl.appendChild(photoLi);
        }
        if (row.content_item != null) {
            const contentLi = document.createElement('li');
            contentLi.textContent = row.content_item;
            contentsUl.appendChild(contentLi);
        }
        if (row.temperature_item != null) {
            const temperatureLi = document.createElement('li');
            temperatureLi.textContent = row.temperature_item;
            contentsUl.appendChild(temperatureLi);
        }
        // const itemFootLi = document.createElement('div');
        // itemFootLi.classList.add('px-6', 'py-4', 'whitespace-nowrap', 'text-sm', 'font-medium');

        // ↓このif文がうまくいってない。

        item.appendChild(titleBox);
        item.appendChild(contentsUl);
        console.log(`ID：${row.id}`);
        console.log(`head ID：${row.head_id}`);
        if (row.id == row.head_id) {
            // console.log(`head_${row.head_id}`);
            item.id = `head_${row.head_id}`;
            // container.appendChild(item);
            container.prepend(item);
        }
        if (row.id != row.head_id) {
            headItem = document.getElementById(`head_${row.head_id}`);
            if(headItem) {
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

