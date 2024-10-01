<x-app-layout>
    <!-- <style>
        ul {
            list-style: none;
        }
    </style> -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('チェック') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('records.store') }}">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                        <div>
                            <!-- <input type="hidden" name=""> -->
                            <label for="member_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">メンバーの種類</label>
                            <select name="member_status" id="member_status" onchange="filterData()" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <!-- <option value="">選択してください</option> -->
                                <option value="0">従業員</option>
                                <option value="1">管理者</option>
                            </select>
                        </div>
                        <div>
                            <label for="clock_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ステータス</label>
                            <select name="clock_status" id="clock_status" onchange="filterData()" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <!-- <option value="">選択してください</option> -->
                                <option value="1">出勤時</option>
                                <option value="0">退勤時</option>
                            </select>
                        </div>
                        <div>
                            <label for="member_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">メンバー</label>
                            <select name="member_id" id="member_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @foreach ($members->sortBy('created_at') as $member)
                                    <option value="{{ $member->id }}">{{ $member->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- アラートメッセージの表示 -->
                    @if (session('error'))
                        <div class="bg-red-500 text-white p-4 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="bg-green-500 text-white p-4 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div id="items_container" class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                        <input type="hidden" name="attendance" value=null >
                        <!-- JavaScriptでここにフィルタリングされたデータを表示 -->
                    </div>


                    <div class="mt-6">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">送信</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // JSONデータをPHPから受け取る
        const jsonTemplates = `<?= $jsonTemplates ?>`;

        // 空のデータ配列を定義
        let data = [];

        try {
            // 受け取ったJSON文字列を解析してオブジェクトに変換
            data = JSON.parse(jsonTemplates);
            console.log(data);  // 解析したデータを確認する
        } catch (e) {
            console.error('Error parsing JSON:', e);  // JSON解析時のエラー処理
        }

        function filterData() {
            // 選択された値を取得
            const memberStatus_selected = document.getElementById('member_status').value;
            const clockStatus_selected = document.getElementById('clock_status').value;

            // データをフィルタリング
            const filteredData = data.filter(row =>
                row.member_status == memberStatus_selected && row.clock_status == clockStatus_selected
            );

            // フィルタリングされたデータを表示する
            console.log(filteredData);
            displayData(filteredData);  // フィルタリングされたデータを表示関数に渡す
        }

        function displayData(filteredData) {
            // 表示コンテナの取得
            const container = document.getElementById('items_container');

            // コンテナをクリア
            container.innerHTML = '';

            // フィルタリングされたデータをループして表示
            let headId = 0;
            filteredData.forEach((row, index) => {
                // 新しい要素を作成し、データを挿入
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

                const titleP = document.createElement('p');
                titleP.textContent = row.title;
                titleP.classList.add(
                    'text-gray-600',
                    'dark:text-gray-400',
                    'text-sm'
                );

                const templateId = document.createElement('input');
                templateId.type = 'hidden';
                templateId.name = `template_id_${index}`;
                templateId.value = row.id;

                titleBox.appendChild(titleP);
                titleBox.appendChild(templateId);
                item.appendChild(titleBox);

                // const contentsUl = document.getElementById('content_ul');
                const contentsUl = document.createElement('ul');
                contentsUl.classList.add(
                    'text-gray-600',
                    'dark:text-gray-400',
                    'text-sm',
                    'mt-4',
                    'list-none'
                );



                if (row.has_check == 1) {
                    const checkLi = document.createElement('li');
                    checkLi.classList.add('mt-4');

                    const yesButton = document.createElement('input');
                    yesButton.type = 'radio';
                    yesButton.name = `check_item_${index}`;
                    yesButton.value = '1';
                    yesButton.id = `check_li_yes_${index}`;
                    yesButton.classList.add('form-radio', 'text-indigo-600');

                    const yesLabel = document.createElement('label');
                    yesLabel.htmlFor = `check_li_yes_${index}`;
                    yesLabel.textContent = 'はい';
                    yesLabel.classList.add('inline-flex', 'items-center');

                    const noButton = document.createElement('input');
                    noButton.type = 'radio';
                    noButton.name = `check_item_${index}`;
                    noButton.value = '0';
                    noButton.id = `check_li_no_${index}`;
                    noButton.classList.add('form-radio', 'text-indigo-600');

                    const noLabel = document.createElement('label');
                    noLabel.htmlFor = `check_li_no_${index}`;
                    noLabel.textContent = 'いいえ';
                    noLabel.classList.add('inline-flex', 'items-center');

                    checkLi.appendChild(yesButton);
                    checkLi.appendChild(yesLabel);
                    checkLi.appendChild(noButton);
                    checkLi.appendChild(noLabel);

                    // item.appendChild(checkLi);
                    contentsUl.appendChild(checkLi);
                } else {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = `check_item_${index}`;
                    hiddenInput.value = null;
                    contentsUl.appendChild(hiddenInput);
                }

                if (row.has_content == 1) {
                    const contentLi = document.createElement('li');
                    const contentInput = document.createElement('input');
                    contentInput.type = 'text';
                    contentInput.name = `content_${index}`;
                    contentInput.classList.add('block', 'w-full', 'py-2', 'px-3', 'border', 'border-gray-300', 'dark:border-gray-600', 'rounded-md', 'shadow-sm', 'focus:ring-indigo-500', 'focus:border-indigo-500', 'sm:text-sm', 'dark:bg-gray-700', 'dark:text-gray-300');

                    contentLi.appendChild(contentInput);
                    contentsUl.appendChild(contentLi);
                } else {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = `content_${index}`;
                    hiddenInput.value = null;
                    contentsUl.appendChild(hiddenInput);
                }

                if (row.has_photo == 1) {
                    const photoLi = document.createElement('li');
                    const photoInput = document.createElement('input');
                    photoInput.type = 'file';
                    photoInput.name = `photo_${index}`;
                    photoInput.classList.add('block', 'w-full', 'text-sm', 'text-gray-500', 'dark:text-gray-300', 'border', 'border-gray-300', 'dark:border-gray-600', 'rounded-md', 'shadow-sm');

                    photoLi.appendChild(photoInput);
                    contentsUl.appendChild(photoLi);
                } else {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = `photo_${index}`;
                    hiddenInput.value = null;
                    contentsUl.appendChild(hiddenInput);
                }

                if (row.has_temperature == 1) {
                    const tempLi = document.createElement('li');
                    const tempInput = document.createElement('input');
                    // tempInput.style.listStyleType = 'none';
                    tempInput.type = 'number';
                    tempInput.step = '0.1';
                    tempInput.name = `temperature_${index}`;
                    tempInput.classList.add('block', 'w-full', 'py-2', 'px-3', 'border', 'border-gray-300', 'dark:border-gray-600', 'rounded-md', 'shadow-sm', 'focus:ring-indigo-500', 'focus:border-indigo-500', 'sm:text-sm', 'dark:bg-gray-700', 'dark:text-gray-300');

                    const tempLabel = document.createElement('label');
                    // tempLabel.htmlFor = `temperature_${index}`;
                    tempLabel.textContent = '℃';
                    tempLabel.classList.add('ml-2');

                    tempLi.appendChild(tempInput);
                    tempLi.appendChild(tempLabel);
                    contentsUl.appendChild(tempLi);
                } else {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = `temperature_${index}`;
                    hiddenInput.value = null;
                    contentsUl.appendChild(hiddenInput);
                }
                // contentsUl.style.listStyleType = 'none';
                item.appendChild(contentsUl);
                container.appendChild(item);
            });
        }

        window.onload = function() {
            filterData();
        };

        document.getElementById('member_status').addEventListener('change', filterData);
        document.getElementById('clock_status').addEventListener('change', filterData);
        // listItems.forEach(item => {
        //     item.classList.add('text-gray-600', 'dark-text-gray-400', 'text-sm', 'mt-4', 'list-none');
        // });
    </script>
</x-app-layout>
