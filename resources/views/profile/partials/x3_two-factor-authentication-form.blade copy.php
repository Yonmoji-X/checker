<div>
    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        二段階認証
    </h2>

    @if (auth()->user()->two_factor_secret)
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            二段階認証は現在有効です。
        </p>

        {{-- QRコード --}}
        <div id="two-factor-qr" class="mt-4 inline-block">
            {!! auth()->user()->twoFactorQrCodeSvg() !!}
        </div>

        {{-- リカバリーコード --}}
        <div id="recovery-codes" class="mt-4">
            <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                リカバリーコード
            </h3>
            <ul class="mt-2 text-sm text-gray-600 dark:text-gray-400 font-mono">
                @php
                    $recoveryCodes = json_decode(decrypt(auth()->user()->two_factor_recovery_codes), true);
                @endphp
                @foreach ($recoveryCodes as $code)
                    <li>{{ $code }}</li>
                @endforeach
            </ul>
        </div>

        {{-- 無効化ボタン --}}
        <form method="POST" action="{{ url('/user/two-factor-authentication') }}">
            @csrf
            @method('DELETE')
            <x-primary-button class="mt-4">
                二段階認証を無効化
            </x-primary-button>
        </form>
    @else
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            二段階認証を有効にすると、ログイン時に認証アプリによる確認が必要になります。
        </p>

        {{-- 有効化ボタン --}}
        <form method="POST" action="{{ url('/user/two-factor-authentication') }}">
            @csrf
            <x-primary-button type="submit" class="mt-4">
                二段階認証を有効化
            </x-primary-button>
        </form>
    @endif

    {{-- デバッグ表示 --}}
    <p>status: {{ session('status') }}</p>
    <p>recovery_codes: {{ auth()->user()->two_factor_recovery_codes ? 'あり' : 'なし' }}</p>
</div>

{{-- jsPDF & html2canvas --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js" defer></script>

@if (session('status') == 'two-factor-authentication-enabled' && auth()->user()->two_factor_recovery_codes)
<script>
document.addEventListener('DOMContentLoaded', async function () {
    console.log('✅ 2FA有効化 → PDF生成開始');

    await new Promise(r => setTimeout(r, 1500)); // 少し待つ

    const qrElement = document.getElementById('two-factor-qr');
    const recoveryElement = document.getElementById('recovery-codes');

    if (!qrElement || !recoveryElement) {
        console.warn('⚠️ QRまたはリカバリー要素が見つかりません');
        return;
    }

    try {
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF();

        // QRコードをcanvas化（正方形維持）
        const canvasQR = await html2canvas(qrElement, { useCORS: true, scale: 2 });
        const imgDataQR = canvasQR.toDataURL('image/png');
        pdf.addImage(imgDataQR, 'PNG', 15, 15, 60, 60); // ← 幅60・高さ60で固定

        // リカバリーコードをcanvas化
        const canvasRecovery = await html2canvas(recoveryElement, { useCORS: true, scale: 2 });
        const imgDataRecovery = canvasRecovery.toDataURL('image/png');
        pdf.addImage(imgDataRecovery, 'PNG', 15, 85, 180, 0);

        // PDFを保存
        const isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
        if (isSafari) {
            const blob = pdf.output('blob');
            const url = URL.createObjectURL(blob);
            window.open(url);
        } else {
            pdf.save('SafeTimeCard_2FA.pdf');
        }

        console.log('✅ PDF生成完了');
    } catch (error) {
        console.error('❌ PDF生成エラー:', error);
    }
});
</script>
@endif
