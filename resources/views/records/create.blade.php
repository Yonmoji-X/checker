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
                <form method="POST" action="{{ route('records.store') }}" id="postForm">
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
    <style>
        .check-li {
            margin-bottom: 10px;
        }
        .radio-group {
            display: flex;
            gap: 20px; /* 2つの要素の間に間隔を追加 */
        }

        .form-radio {
            /* display: none; これを削除 */
            position: absolute; /* ラジオボタンを見えなくする */
            opacity: 0; /* ラジオボタンを完全に隠す */
        }

        .radio-label {
            background-color: rgb(31, 41, 55 / var(--tw-bg-opacity)); /* ダークグレー */
            color: #d1d5db; /* ライトグレーの文字色 */
            font-weight: bold;
            padding: 0.5rem 1rem;
            border-radius: 50px; /* 完全に丸く */
            outline: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25); /* 深めのシャドウで立体感 */
            transition: background-color 0.4s ease, color 0.4s ease, transform 0.3s ease; /* スムーズなトランジション */
        }

        .radio-label:hover {
            background-color: #6b5bff; /* 明るい紫 */
            color: #ffffff; /* ホワイト */
            background-image: linear-gradient(145deg, #6b5bff, #8a74ff); /* 鮮やかな紫のグラデーション */
            transform: translateY(-2px); /* ホバー時に少し上に移動 */
        }

        .form-radio:checked + .radio-label {
            background-color: #8a44e4; /* 鮮やかな紫 */
            color: #ffffff; /* ホワイト */
            background-image: linear-gradient(145deg, #b057ff, #007bff);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3); /* 選択時のシャドウを強化 */
        }

        .form-radio:checked + .radio-label:hover {
            background-color: #9b57ff; /* さらに明るい紫 */
            color: #ffffff; /* ホワイト */
            background-image: linear-gradient(145deg, #9b57ff, #bb81ff); /* ホバー時のより明るい輝き */
        }
    </style>


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
                // titleP.textContent = row.title;
                titleP.innerHTML = `${row.title}<span style="color: red; font-size: 1.2em;"> *</span>`;


                titleP.classList.add(
                    'text-gray-600',
                    'dark:text-gray-400',
                    'text-sm'
                );

                const templateId = document.createElement('input');
                templateId.type = 'hidden';
                templateId.name = `template_id_${index}`;
                templateId.value = row.id;
                templateId.setAttribute('required', '');  // 必須属性を追加

                titleBox.appendChild(titleP);
                titleBox.appendChild(templateId);
                item.appendChild(titleBox);

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
    checkLi.classList.add('radio-group', 'mt-4', 'check-li');

    // "はい" ボタンの作成
    const yesButton = document.createElement('input');
    yesButton.type = 'radio';
    yesButton.name = `check_item_${index}`;
    yesButton.value = '1';
    yesButton.id = `check_li_yes_${index}`;
    yesButton.classList.add('form-radio', 'text-indigo-600');
    yesButton.setAttribute('required', '');  // 必須属性を追加

    const yesLabel = document.createElement('label');
    yesLabel.htmlFor = `check_li_yes_${index}`;
    yesLabel.textContent = 'はい';
    yesLabel.classList.add('radio-label', 'inline-flex', 'items-center');

    // "いいえ" ボタンの作成
    const noButton = document.createElement('input');
    noButton.type = 'radio';
    noButton.name = `check_item_${index}`;
    noButton.value = '0';
    noButton.id = `check_li_no_${index}`;
    noButton.classList.add('form-radio', 'text-indigo-600');

    const noLabel = document.createElement('label');
    noLabel.htmlFor = `check_li_no_${index}`;
    noLabel.textContent = 'いいえ';
    noLabel.classList.add('radio-label', 'inline-flex', 'items-center');

    // 要素の追加
    checkLi.appendChild(yesButton);
    checkLi.appendChild(yesLabel);
    checkLi.appendChild(noButton);
    checkLi.appendChild(noLabel);
    contentsUl.appendChild(checkLi);
}


                if (row.has_content == 1) {
                    const contentLi = document.createElement('li');
                    const contentInput = document.createElement('input');
                    contentInput.type = 'text';
                    contentInput.name = `content_${index}`;
                    contentInput.classList.add('block', 'w-full', 'py-2', 'px-3', 'border', 'border-gray-300', 'dark:border-gray-600', 'rounded-md', 'shadow-sm', 'focus:ring-indigo-500', 'focus:border-indigo-500', 'sm:text-sm', 'dark:bg-gray-700', 'dark:text-gray-300', 'check-li');
                    contentInput.setAttribute('required', '');  // 必須属性を追加

                    contentLi.appendChild(contentInput);
                    contentsUl.appendChild(contentLi);
                }

                if (row.has_photo == 1) {
                    const photoLi = document.createElement('li');
                    const photoInput = document.createElement('input');
                    photoInput.type = 'file';
                    photoInput.name = `photo_${index}`;
                    photoInput.classList.add('block', 'w-full', 'text-sm', 'text-gray-500', 'dark:text-gray-300', 'border', 'border-gray-300', 'dark:border-gray-600', 'rounded-md', 'shadow-sm', 'check-li');
                    photoInput.setAttribute('required', '');  // 必須属性を追加

                    photoLi.appendChild(photoInput);
                    contentsUl.appendChild(photoLi);
                }

                item.appendChild(contentsUl);
                container.appendChild(item);
            });
        }


        window.onload = function() {
            filterData();
        };

        document.getElementById('member_status').addEventListener('change', filterData);
        document.getElementById('clock_status').addEventListener('change', filterData);

        document.getElementById('postForm').addEventListener('submit', function(event) {
        // 確認ダイアログを表示
        const confirmed = confirm("送信してよろしいですか？");
        if (!confirmed) {
            // 確認が取れなかった場合、送信をキャンセル
            event.preventDefault();
        }
    });
        // listItems.forEach(item => {
        //     item.classList.add('text-gray-600', 'dark-text-gray-400', 'text-sm', 'mt-4', 'list-none');
        // });
    </script>
</x-app-layout>
