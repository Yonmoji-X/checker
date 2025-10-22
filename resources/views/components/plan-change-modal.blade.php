<div id="plan-change-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-xl p-6 max-w-md w-full">
    <h2 class="text-xl font-semibold mb-4">プラン変更の確認</h2>
    <p class="mb-2">
      選択したプランに変更すると、現在のプランの残り日数分は日割りで計算され、次回請求に反映されます。
    </p>
    <p class="mb-4 text-red-600">
      下位プランに変更した場合、一部機能が制限されることがあります。本当に変更してよろしいですか？
    </p>
    <div class="flex justify-end space-x-4">
      <button id="cancel-btn" class="px-4 py-2 bg-gray-300 rounded">キャンセル</button>
      <button id="confirm-btn" class="px-4 py-2 bg-blue-500 text-white rounded">変更する</button>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById('plan-change-modal');
  const cancelBtn = document.getElementById('cancel-btn');
  const confirmBtn = document.getElementById('confirm-btn');

  window.showPlanChangeModal = function() {
    modal.classList.remove('hidden');
  };

  cancelBtn.addEventListener('click', () => modal.classList.add('hidden'));
  confirmBtn.addEventListener('click', () => {
    modal.classList.add('hidden');
    if (window.onPlanChangeConfirm) {
      window.onPlanChangeConfirm(); // 呼び出し元でAPI呼び出し
    }
  });
});
</script>
