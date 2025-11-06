<!-- 他の部分をまだ実装していないので、とりあえず作ってる感じ -->

<x-app-layout>
    <div class="p-8 bg-white dark:bg-gray-800 shadow rounded-xl text-center">
        <h1 class="text-2xl font-bold mb-4">解約が完了しました</h1>
        <p class="text-gray-600">ご利用ありがとうございました。</p>
        <p class="text-gray-600">今回請求分の期間中は使用可能です。</p>
        <a href="{{ route('dashboard') }}" class="mt-6 inline-block px-4 py-2 bg-blue-600 text-white rounded-lg">
            ダッシュボードへ戻る
        </a>
    </div>
</x-app-layout>