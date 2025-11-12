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
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            必ずPCで設定を行ってください。
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

    // 描画待機
    await new Promise(r => setTimeout(r, 1500));

    const qrElement = document.getElementById('two-factor-qr');
    const recoveryElement = document.getElementById('recovery-codes');

    if (!qrElement || !recoveryElement) {
        console.warn('⚠️ QRまたはリカバリー要素が見つかりません');
        return;
    }

    try {
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF();

        // --- HTMLでタイトルと説明を作成してcanvas化 ---
        const tempDiv = document.createElement('div');
        tempDiv.style.fontFamily = "'Noto Sans JP', sans-serif";
        tempDiv.style.width = "210mm"; // A4幅
        tempDiv.style.padding = "10px";
        tempDiv.style.backgroundColor = "#fff";

        const now = new Date();
        const yyyy = now.getFullYear();
        const mm = String(now.getMonth()+1).padStart(2,'0');
        const dd = String(now.getDate()).padStart(2,'0');
        const h = String(now.getHours()).padStart(2,'0');
        const m = String(now.getMinutes()).padStart(2,'0');

        tempDiv.innerHTML = `
            <h1 style="font-size:24px; margin-bottom:5px;">SafeTimeCard 二段階認証</h1>
            <p style="font-size:12px; margin-bottom:5px;">日付: ${yyyy}/${mm}/${dd} ${h}:${m}</p>
            <p style="font-size:10px; line-height:1.2; margin-bottom:10px;">
            これは重要なファイルです。安全に保管してください。<br>
            Google Authenticatorをスマートフォンにダウンロードし、QRコードを読み込んで二段階認証を有効化してください。
            </p>
        `;
        document.body.appendChild(tempDiv);

        const canvasHeader = await html2canvas(tempDiv, {scale:2, useCORS:true, backgroundColor:'#fff'});
        const imgHeader = canvasHeader.toDataURL('image/png');
        pdf.addImage(imgHeader, 'PNG', 0, 0, 190, 0); // 高さ自動

        document.body.removeChild(tempDiv);

        // --- QRコードを正方形でキャプチャ ---
        const canvasQR = await html2canvas(qrElement, { useCORS:true, scale:2, scrollY:0, backgroundColor:null });
        const imgDataQR = canvasQR.toDataURL('image/png');
        pdf.addImage(imgDataQR, 'PNG', 15, 60, 60, 60);

        // --- リカバリーコードをキャプチャ ---
        const originalOverflow = recoveryElement.style.overflow;
        const originalPadding = recoveryElement.style.paddingBottom;
        recoveryElement.style.overflow = 'visible';
        recoveryElement.style.paddingBottom = '40px';

        const canvasRecovery = await html2canvas(recoveryElement, { useCORS:true, scale:2, scrollY:0, windowWidth:document.body.scrollWidth, windowHeight:document.body.scrollHeight, backgroundColor:null });

        recoveryElement.style.overflow = originalOverflow;
        recoveryElement.style.paddingBottom = originalPadding;

        const imgDataRecovery = canvasRecovery.toDataURL('image/png');
        pdf.addImage(imgDataRecovery, 'PNG', 15, 130, 180, 0);

        // --- PDF保存 ---
        const isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
        if(isSafari){
            const blob = pdf.output('blob');
            const url = URL.createObjectURL(blob);
            window.open(url);
        }else{
            pdf.save('SafeTimeCard_2FA.pdf');
        }

        console.log('✅ PDF生成完了');
    } catch(error) {
        console.error('❌ PDF生成エラー:', error);
    }
});
</script>
@endif
