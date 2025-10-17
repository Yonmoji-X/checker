【AWS LightSail Laravelデプロイ方法】

■概要
・chacker（Laravel）をAWS LightSailにデプロイする。
・.envファイルは作らず、サーバー環境変数に重量情報を保持する。（セキュリティ面考慮）
・Stripe（サブスク）のセキュリティ要件を満たすよう設計する。
・LightSailデプロイに向けたchackerの調整を行う。
	・ログインsessionの問題解決（404エラー）
	・.envへのpathの調整。
	・JSごり押しのコードをlaravelに書き換える。（コントローラで取得データ処理）
	・セキュリティ上、DBは別に用意する
	　Laravelアプリ用Lightsailインスタンス 
	　＋ 外部DB（Lightsail Managed DBまたはRDS）



■手順
①stripeのセキュリティ要件を満たすように、下記さん点を同時並行で調整する。
	・chackerの調整
	・AWS lightsail登録、環境作成
	・awsと同時並行でStripeの準備
	・DBはLightSailインスタンスと別に用意する。（セキュリティ的観点）
	

②プランの調整
	・Stripe


■参考資料
・AWS LightSailへのLaravelアプリデプロイ手順
	https://qiita.com/nozhy/items/a970a843121e9d9b1b44

・AWS LightSailへのLaravelアプリデプロイ手順2
	https://zenn.dev/oyatsu_karpas/articles/9536427de600d1

・GitHubからAWS LightsailへLaravel 11プロジェクトをデプロイする方法（データベース連携有）
	https://biz.addisteria.com/laravel_aws_lightsail/





