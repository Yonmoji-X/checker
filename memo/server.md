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


了解です！では今日やった作業を整理して、**Bitnami Lightsail 上の Laravel デプロイ用マニュアル**としてまとめます。⭐印は注意ポイントやコツです。

---

# Bitnami Lightsail + Laravel デプロイマニュアル（独自ドメイン + SSL）

## ① Laravel の `.env` を安全に配置する方法

Laravel の設定ファイル `.env` は **絶対にウェブ公開ディレクトリ内に置かない**ことが安全です。

### 手順

1. Laravel プロジェクトを `/opt/bitnami/apps/laravel/htdocs` に配置

   ```bash
   sudo mkdir -p /opt/bitnami/apps/laravel/htdocs
   sudo chown -R bitnami:daemon /opt/bitnami/apps/laravel/htdocs
   sudo chmod -R 775 /opt/bitnami/apps/laravel/htdocs
   ```

2. `.env` を Laravel プロジェクト内にコピー

   * ⭐ **公開ディレクトリ `public` の外に置くこと**
   * 例: `/opt/bitnami/apps/laravel/htdocs/.env`

3. `.env` の権限を設定

   ```bash
   sudo chown bitnami:daemon /opt/bitnami/apps/laravel/htdocs/.env
   sudo chmod 640 /opt/bitnami/apps/laravel/htdocs/.env
   ```

4. `storage` と `bootstrap/cache` の書き込み権限を Apache 用に設定

   ```bash
   sudo chown -R bitnami:daemon /opt/bitnami/apps/laravel/htdocs/storage /opt/bitnami/apps/laravel/htdocs/bootstrap/cache
   sudo chmod -R 775 /opt/bitnami/apps/laravel/htdocs/storage /opt/bitnami/apps/laravel/htdocs/bootstrap/cache
   ```

---

## ② 独自ドメインの取得と Lightsail・お名前ドットコムでの設定

### 1. 独自ドメイン取得

* お名前ドットコムなどで購入。
* 例: `safetimecard.com`

### 2. Lightsail 側設定

1. Lightsail コンソールで **静的 IP** を取得

   * ⭐ Elastic IP のように固定 IP を設定しておくと便利

2. Lightsail インスタンスに静的 IP を紐づけ

### 3. DNS 設定（お名前ドットコム）

1. ドメイン管理画面で **DNS レコードを設定**

   * A レコード: `www` → Lightsail の静的 IP
   * A レコード: `@`（ルートドメイン） → 同じ IP

2. 設定後、反映に最大 24 時間かかることもある

---

## ③ SSL 化（Let’s Encrypt + Bitnami bncert）

### 1. bncert ツールの実行

```bash
sudo /opt/bitnami/bncert-tool
```

* ドメインを入力（例: `safetimecard.com www.safetimecard.com`）
* メールアドレスを入力
* 利用規約に同意
* 自動で SSL 証明書取得 + Apache 設定

### 2. Apache 設定のポイント

* `bitnami.conf` の DocumentRoot は **Laravel の public ディレクトリ** に

  ```apache
  DocumentRoot "/opt/bitnami/apps/laravel/htdocs/public"  # ⭐
  <Directory "/opt/bitnami/apps/laravel/htdocs/public">  
      Options Indexes FollowSymLinks
      AllowOverride All
      Require all granted
  </Directory>
  ```
* SSL 証明書パスを bncert 自動生成のものに変更

  ```apache
  SSLCertificateFile "/opt/bitnami/letsencrypt/certificates/www.safetimecard.com.crt"  # ⭐
  SSLCertificateKeyFile "/opt/bitnami/letsencrypt/certificates/www.safetimecard.com.key"
  SSLCertificateChainFile "/opt/bitnami/letsencrypt/certificates/www.safetimecard.com.crt"
  ```

### 3. Apache 再起動

```bash
sudo /opt/bitnami/ctlscript.sh restart apache
```

* ⭐ 設定後に必ず構文チェック

  ```bash
  sudo /opt/bitnami/apache2/bin/apachectl -t
  ```

---

## ④ HTTP → HTTPS リダイレクト & www リダイレクト

* HTTP → HTTPS

  ```apache
  RewriteEngine On
  RewriteCond %{HTTPS} !=on
  RewriteCond %{REQUEST_URI} !^/\.well-known
  RewriteRule ^/(.*) https://%{SERVER_NAME}/$1 [R,L]
  ```
* 非 www → www

  ```apache
  RewriteCond %{HTTP_HOST} !^www\. [NC]
  RewriteCond %{REQUEST_URI} !^/\.well-known
  RewriteRule ^(.*)$ https://www.%{HTTP_HOST}%{REQUEST_URI} [R=permanent,L]
  ```

---

💡 **注意点**

1. Apache が起動しない場合は `apachectl -t` で原因を確認
2. SSL 証明書のファイル名・パスは正確に
3. Laravel の `.env` は公開ディレクトリ外に置く
4. Lightsail のセキュリティグループで HTTP/HTTPS ポート 80/443 を開放

---

もし希望なら、このマニュアルを **PDF 形式に整形して図入りでまとめる**こともできます。

作りますか？



