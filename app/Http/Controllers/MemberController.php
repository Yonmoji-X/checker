<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate; //←追記

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('isAdmin');
        $user = Auth::user();

        $members = Member::where('user_id', $user->id)
                        ->with('user')
                        ->reorder()
                        ->orderByRaw('name COLLATE utf8mb4_ja_0900_as_cs ASC')
                        ->paginate(5);

        // プラン情報
        $planName = null;
        $limit = null;
        $memberCount = $members->total(); // 全メンバー数

        if ($user->role === 'admin') {
            $plan = collect(config('stripe.plans_list'))->firstWhere('stripe_plan', $user->stripe_plan);
            $planName = $plan['name'] ?? '不明';
            $limit = $plan['limit'] ?? null;
        }

        // ページをまたいで全体の順序で over_limit を判定
        $currentPage = $members->currentPage();
        $perPage = $members->perPage();

        foreach ($members as $index => $member) {
            $globalIndex = ($currentPage - 1) * $perPage + $index + 1;
            $member->is_over_limit = $limit && $globalIndex > $limit;
        }

        return view('members.index', compact('members', 'planName', 'memberCount', 'limit'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('isAdmin'); //←追記
        return view('members.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('isAdmin'); //←追記

        $request->validate([
            'name' => 'required|max:64',
            'email' => 'nullable|email|max:255',   // ← required → nullable に変更
            'content' => 'nullable|max:255',       // ← required → nullable に変更
            'is_visible' => 'sometimes|boolean',
        ]);



        $user = $request->user();
        if ($user->role === 'admin') {
            // 名簿の数
            $memberCount = $user->members()->count();

            // 現在のプラン
            // $plan = $user->stripe_plan ?? 'free';
            // $price_id = config("stripe.plans.$plan") ?? $plan; // planがprice_idならそのまま使う
            // $limit = config("stripe.limits.$price_id");

            $userPlanKey = $user->stripe_plan; // price_id なら price_id を使う
            $plan = collect(config('stripe.plans_list'))->firstWhere('stripe_plan', $userPlanKey);
            $limit = $plan['limit'] ?? null;
            // プランの名簿上限値（stripe.php参照）

            // 上限チェック
            if (!is_null($limit) && $memberCount >= $limit) {
                return redirect()->route('members.index')
                    ->with('error', "現在のプランでは最大{$limit}名までしか登録できません。...");
            }
        }

        $data = $request->only(['name', 'email', 'content']);
        $data['is_visible'] = $request->has('is_visible'); // チェックが入っていれば 1、なければ 0
        // $data['is_visible'] = $request->has('is_visible') ? 1 : 0; // チェックが入っていれば 1、なければ 0

        // ユーザーが認証済みであることを確認し、members リレーションを使用
        $member = $request->user()->members()->create($data);

        return redirect()->route('members.index')
                ->with('success', "{$member->name} の登録が完了しました");
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {
        Gate::authorize('isAdmin'); //←追記
        return view('members.show', compact('member'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member)
    {
        Gate::authorize('isAdmin'); //←追記
        return view('members.edit', compact('member'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Member $member)
    {
        Gate::authorize('isAdmin'); //←追記
        $request->validate([
            'name' => 'required|max:64',
            'email' => 'nullable|email|max:255',   // ← required → nullable に変更
            'content' => 'nullable|max:255',       // ← required → nullable に変更
            'is_visible' => 'sometimes|boolean',
        ]);
        // $request->validate([
        //     'name' => 'required|max:64',
        //     'email' => 'required|email|max:255',
        //     'content' => 'required|max:255',
        //     // 'is_visible' => 'boolean', // `is_visible` をブール値としてバリデーション
        //     'is_visible' => 'sometimes|boolean', // `is_visible` をブール値としてバリデーション
        // ]);

        $data = $request->only(['name', 'email', 'content']);
        // $data['is_visible'] = $request->has('is_visible') ? 1 : 0; // チェックボックスがオンなら 1、それ以外なら 0
        $data['is_visible'] = $request->boolean('is_visible');

        $member->update($data);

        return redirect()->route('members.index')->with('success', 'メンバー情報が更新されました');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        Gate::authorize('isAdmin'); //←追記
        $member->delete();
        return redirect()->route('members.index');
    }
}
