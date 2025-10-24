<x-guest-layout>
    <form id="register-form" method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('名前')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('メールアドレス')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('パスワード')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('パスワード確認用')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Role -->
        <div class="mt-4">
            <x-input-label for="role" :value="__('権限')" />
            <select id="role" name="role" class="block mt-1 w-full">
                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>管理者アカウント</option>
                <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>利用者アカウント（管理者アカウント必須）</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- 利用規約確認ボタン -->
        <div class="mt-4">
            <button type="button" id="open-terms-btn" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
                利用規約を確認
            </button>
        </div>

        <!-- 登録ボタン -->
        <div class="flex items-center justify-end mt-4">
            <a href="{{ route('login') }}" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md">
                すでに登録済みですか?
            </a>

            <button type="submit" id="register-btn" class="ms-4 bg-gray-400 text-white py-2 px-4 rounded cursor-not-allowed">
                登録
            </button>
        </div>
    </form>

    <!-- モーダル -->
    <div id="terms-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-900 rounded-lg w-3/4 max-w-3xl p-6 flex flex-col max-h-[90vh]">
            <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100">利用規約</h2>

            <!-- スクロール可能な規約テキスト -->
            <div id="terms-box" class="overflow-y-auto p-4 border rounded bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300 mb-4" style="height: 60vh;">
                <p>本利用規約（以下「本規約」といいます。）は、[会社名]（以下「当社」といいます。）が提供するサブスクリプションサービス（以下「本サービス」といいます。）の利用条件を定めるものです。本サービスの利用者（以下「ユーザー」といいます。）は、本規約に同意した上で本サービスを利用するものとします。</p>

                <p>第1条（適用）…</p>
                <p>第2条（利用登録）…</p>
                <p>第3条（利用料金および支払方法）…</p>
                <p>第4条（サービスの提供）…</p>
                <p>第5条（禁止事項）…</p>
                <p>第6条（契約の解除）…</p>
                <p>第7条（免責事項）…</p>
                <p>第8条（個人情報の取扱い）…</p>
                <p>第9条（準拠法および裁判管轄）…</p>
                <p>附則…</p>

                <!-- 長文ダミー -->
                @for ($i = 0; $i < 30; $i++)
                    <p>…十分にスクロール可能な内容…</p>
                @endfor
            </div>

            <!-- 同意ボタン -->
            <button id="terms-agree-btn" class="bg-gray-400 text-white py-2 px-4 rounded disabled:opacity-50" disabled>
                同意して閉じる
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('terms-modal');
            const openBtn = document.getElementById('open-terms-btn');
            const agreeBtn = document.getElementById('terms-agree-btn');
            const termsBox = document.getElementById('terms-box');
            const registerBtn = document.getElementById('register-btn');

            let agreed = false;

            // 利用規約モーダルを開く
            openBtn.addEventListener('click', () => {
                modal.classList.remove('hidden');
            });

            // スクロールで同意ボタン有効化
            termsBox.addEventListener('scroll', () => {
                if (termsBox.scrollTop + termsBox.clientHeight >= termsBox.scrollHeight - 1) {
                    agreeBtn.disabled = false;
                    agreeBtn.classList.remove('bg-gray-400');
                    agreeBtn.classList.add('bg-blue-600', 'hover:bg-blue-700', 'cursor-pointer');
                }
            });

            // 同意ボタン押下
            agreeBtn.addEventListener('click', () => {
                modal.classList.add('hidden');
                agreed = true;

                // 登録ボタンを有効化して見た目変更
                registerBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
                registerBtn.classList.add('bg-blue-600', 'hover:bg-blue-700', 'cursor-pointer');
            });

            // 登録ボタン押下
            registerBtn.addEventListener('click', (e) => {
                if (!agreed) {
                    e.preventDefault(); // 同意してなければモーダル表示
                    modal.classList.remove('hidden');
                }
                // 同意済みならフォーム送信される
            });
        });
    </script>
</x-guest-layout>
