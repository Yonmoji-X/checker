{{-- resources/views/components/plan-modal.blade.php --}}
@php
    // config から取得
    $plans = $plans ?? config('stripe.plans_list');
@endphp

@if(auth()->check() && auth()->user()->role === 'admin' && 
   (is_null(auth()->user()->stripe_status) || auth()->user()->stripe_status === 'canceled'))
    <div class="fixed inset-0 bg-black bg-opacity-70 z-[9998]"></div>

    <div class="fixed inset-0 flex items-center justify-center z-[9999]">
        <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl w-full max-w-3xl text-center relative">
            <!-- <h2 class="text-2xl font-bold mb-6">プランを選択してください</h2>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-gray-700 hover:bg-gray-600 text-white py-2 rounded-md text-sm font-medium">
                    {{ __('ログアウト') }}
                </button>
            </form> -->
            <div class="flex items-center justify-center relative mb-6">

                <!-- 左端: ユーザー情報 -->
                <!-- <div class="absolute left-0 text-left">
                    <div class="text-sm text-gray-400 truncate">
                        <div>{{ Auth::user()->name }}</div>
                        <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                </div> -->

                <!-- 中央: タイトル -->
                <h2 class="text-2xl font-bold">
                    {{ Auth::user()->name }}様 ご登録ありがとうございます<br> プランを選択してください
                </h2>

                <!-- 右端: ログアウトボタン -->
                <div class="absolute right-0">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-500 hover:text-gray-700 text-sm font-medium">
                            {{ __('ログアウト') }}
                        </button>
                    </form>
                </div>
            </div>




            {{-- $plans を plan-card に渡す --}}
            <x-plan-card :plans="$plans" :user="auth()->user()"/>
        </div>
    </div>

    
    {{-- 背景スクロール禁止 --}}
    <script>
        document.body.style.overflow = 'hidden';
        document.addEventListener('keydown', e => { if(e.key === "Escape") e.preventDefault(); });
    </script>
@endif
