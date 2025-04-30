<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

-   [Simple, fast routing engine](https://laravel.com/docs/routing).
-   [Powerful dependency injection container](https://laravel.com/docs/container).
-   Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
-   Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
-   Database agnostic [schema migrations](https://laravel.com/docs/migrations).
-   [Robust background job processing](https://laravel.com/docs/queues).
-   [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

-   **[Vehikl](https://vehikl.com/)**
-   **[Tighten Co.](https://tighten.co)**
-   **[WebReinvent](https://webreinvent.com/)**
-   **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
-   **[64 Robots](https://64robots.com)**
-   **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
-   **[Cyber-Duck](https://cyber-duck.co.uk)**
-   **[DevSquad](https://devsquad.com/hire-laravel-developers)**
-   **[Jump24](https://jump24.co.uk)**
-   **[Redberry](https://redberry.international/laravel/)**
-   **[Active Logic](https://activelogic.com)**
-   **[byte5](https://byte5.de)**
-   **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

# 情報

## 【必要機能（残りの実装機能）】

### ☆ チェックページはログインなしで使用できる必要がある。

-   QR コードで、check ページに飛ばす。
-   チェックページで QR コード生成（popup 表示、ダウンロード）

### ☆ 勤怠管理機能

-   出勤時チェック時に、出勤タイムカード
-   退勤時チェック時に、退勤タイムカード
-   勤怠管理の管理画面
-   休憩に入るときに、休憩ボタン。
-   休憩から戻るときに、再開ボタン。
-   休憩に入るときに、

## 【現在の進行状況】

### 各ページ基本機能

#### Record

-   ☑︎View create デザイン以外
-   ☑︎View index 表示
-   ☑︎View index → 削除機能：デプロイ画面で実行できない
-   ☑︎View index → 編集機能：ローカルでページに飛べない
-   ☑︎View edit → 編集画面の作成
-   □Controller edit update → コントローラの作成。

## メモ

-   ☑︎ エクセル出力
-   ☑︎ メールでアドレス再登録機能
-   □ エクセルには式を入れる。
-   □ 日本語化
-   □ チェック機能の並び替え機能
-   □ いいえの場合通知したい
-   □ デザインの更新
-   ☑︎ アプリ化 → 明日 PWA にしたい。
-   ☑︎ 勤怠管理は、管理アカウントからは削除できるようにしたい。
-   ☑︎CSV 出力
-   □ 勤怠管理に、日にちが加味されていない気がする。修正。
-   □ チェック項目の編集

## 修正依頼【重要度：髙】

-   ☑︎ エクセル出力したい。←10 月中
-   → 拒否：× チェック内容修正したい。
-   □ チェック内容の並び替えできるようにしたい。
-   □ 前日の累計を簡単に確認できるように
-   → ダッシュボードにをワイトボード追加？
-   □ 前日退勤していなくても、翌日出勤できるように。
-   ☑︎ チェック項目は必須項目に。→ フロントでやる。
-   □ チェック項目必須フラグ → カラム追加（必須 or not）
-   □ 表示非表示フラグ → カラム追加（member）
-   ☑︎ 表示非表示フラグ → カラム追加（template）
-   ☑︎ 順序を入れ替えた時の、チェックデータはうまく入らない。
-   □ セレクトボックスを Ajax に変更（今は、json データを js に渡して原始的に処理している。）
-   □ テンプレートは、テンプレートを使用した投稿がない場合のみ変更できる。
-   □ 配下の一般アカウント数の制限
-   ☑︎ 任意のテンプレートを出勤簿 Excel に反映するフラグを設ける。
-           →tamplateテーブルにExcel反映フラグのカラム（reflection）を作る    （int）デフォルト非反映(0)
-           →templateのviewで設定画面作成
-           →excel出力用のコントローラで
-   □ チェック時間を、ボタンを押した時間から引く。
-   □ ページネーション
-   □ チェック依頼メール送信。
    → 月初に勤怠データ集める際、ボタンを押すと自動で確認メールが送られる。（excel 添付）
    → 確認フォームにチェックすると、AC-Sync に自動反映。
    → 修正店がある場合は、確認フォームに書いて送ると反映される。
    □ wi-fi 勤怠
    □ チェックテンプレートを複数つけて、人ごとに、チェック項目を変更できるようにする必要がある。
    □ 人と、出退勤状況を関連づけて、出勤状態ならデフォルトで選択肢が「退勤」逆なら「出勤」となるようにする必要がある。
    □選択肢の回答が、「いいえ」だった際は、管理者にメールがいくようにしたい。

# テーマカラー候補

-   #3300cc セーフカラー
-   #4A2FB6 イリス
-   #007bff デフォルト青
-   #111827 黒
-   iPhone の PWA 背景はこれを参照
-   https://sogo.dev/posts/2024/07/pwa-appearance

# コマンド類

■ サーバー起動
Docker 起動
./vendor/bin/sail up -d

アプリ URL:
http://localhost/

phpmyadminURL:
http://localhost:8082/
パスワードは、環境変数参照
