<div>
    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        二段階認証
    </h2>

    @if (auth()->user()->two_factor_secret)
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            二段階認証は現在有効です。
        </p>

        {{-- QRコード --}}
        <div id="two-factor-qr" class="mt-4">
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
                    $recoveryText = implode("\n", $recoveryCodes);
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
        <form method="POST" action="{{ url('/user/two-factor-authentication') }}" id="enable-2fa-form">
            @csrf
            <x-primary-button type="submit" class="mt-4">
                二段階認証を有効化
            </x-primary-button>
        </form>
    @endif

    {{-- デバッグ用（確認が終わったら削除してOK） --}}
    <p>status: {{ session('status') }}</p>
    <p>recovery_codes: {{ auth()->user()->two_factor_recovery_codes ? 'あり' : 'なし' }}</p>
</div>

{{-- jsPDF と html2canvas の CDN --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js" defer></script>

@if (session('status') == 'two-factor-authentication-enabled' && auth()->user()->two_factor_recovery_codes)
<script>
document.addEventListener('DOMContentLoaded', async function () {
    console.log('2FA有効化後スクリプト実行開始');

    // 少し描画完了を待機（1.5秒）
    await new Promise(r => setTimeout(r, 1500));

    const qrElement = document.getElementById('two-factor-qr');
    const recoveryElement = document.getElementById('recovery-codes');

    if (!qrElement || !recoveryElement) {
        console.warn('2FA要素が見つかりません。PDF生成をスキップします。');
        return;
    }

    try {
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF();

        // QRコード部分をcanvas化
        const canvasQR = await html2canvas(qrElement, { useCORS: true, scale: 2 });
        const imgDataQR = canvasQR.toDataURL('image/png');
        pdf.addImage(imgDataQR, 'PNG', 15, 15, 60, 60);

        // リカバリーコード部分をcanvas化
        const canvasRecovery = await html2canvas(recoveryElement, { useCORS: true, scale: 2 });
        const imgDataRecovery = canvasRecovery.toDataURL('image/png');
        pdf.addImage(imgDataRecovery, 'PNG', 15, 85, 180, 0);

        // Safari対応：自動ダウンロード不可の場合は別タブで開く
        const isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
        if (isSafari) {
            const pdfBlob = pdf.output('blob');
            const pdfUrl = URL.createObjectURL(pdfBlob);
            window.open(pdfUrl);
        } else {
            pdf.save('SafeTimeCard_2FA.pdf');
        }

        console.log('PDF生成完了');
    } catch (e) {
        console.error('PDF生成中にエラー:', e);
    }
});
</script>
@endif
