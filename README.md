# Rese
飲食店予約サービス

<--- トップ画面の画像 ---->
<img width="1437" alt="shop_all" src="https://github.com/Orihama-kajiki/advanced-term/assets/116421776/17f37765-7e02-4223-9263-a1a7a59b9a3c">

## 作成した目的
Advancedtermの模擬演習提出のため。

## 機能一覧
以下、権限毎に一覧を分けました。
### 利用者 (一般ユーザー)

- 会員登録
- メール認証
- ログイン
- ログアウト
- ユーザー情報取得
- ユーザー飲食店お気に入り一覧取得
- ユーザー飲食店予約情報取得
- ユーザー飲食店予約履歴情報取得
- ユーザー飲食店レビュー履歴取得
- 飲食店一覧取得
- 飲食店詳細取得
- 飲食店レビュー情報取得
- 飲食店コース料理情報取得
- 飲食店お気に入り追加
- 飲食店お気に入り削除
- 飲食店予約情報追加
- 飲食店予約情報削除
- 飲食店レビュー投稿
- エリアで検索する
- ジャンルで検索する
- 店名で検索する
- 予約リマインダー
- QRコード発行
- 決済

### 店舗責任者

- ログイン
- ログアウト
- 飲食店作成
- 飲食店内容変更
- 飲食店削除
- 管理飲食店一覧取得
- 管理飲食店予約詳細取得
- 管理飲食店予約内容変更
- 管理飲食店予約削除

### サービス管理者

- ログイン
- ログアウト
- サービス利用者一斉メール配信
- 店舗責任者アカウント作成

## 使用技術（実行環境）

- PHP: バージョン 8.1
- Laravel: バージョン 8.75
- AWS SDK for PHP: バージョン 3.268
- endroid/qr-code: バージョン 4.8
- fruitcake/laravel-cors: バージョン 2.0
- Guzzle: バージョン 7.0.1
- Laravel Sanctum: バージョン 2.15.1
- spatie/laravel-permission: バージョン 5.10
- Stripe PHP: バージョン 10.12
- Laravel Breeze (開発用): バージョン 1.9.0
- Laravel Sail (開発用): バージョン 1.0.1
- PHPUnit (開発用): バージョン 9.5.10
- Tailwind CSS: バージョン 3.2.7
- Alpine.js: バージョン 3.4.2
- Axios: バージョン 0.21
- qrcode: バージョン 1.5.3

## テーブル設計
<--- 作成したテーブル設計の画像 ---->
![table](https://github.com/Orihama-kajiki/advanced-term/assets/116421776/a8a0183e-670c-4c55-b9ea-2cddb12677f1)

## ER図
<--- 作成したER図の画像 ---->
![ER](https://github.com/Orihama-kajiki/advanced-term/assets/116421776/4c8dc2e9-b55a-4a99-94f5-d2726e0e3b5b)


## 環境構築

以下はプロジェクトの環境構築手順です。

### 必要なインストール

- PHP: バージョン 8.1
- Node.js: Node.jsをインストールします。
- Git: Gitをインストールします。
- Composer: Composerをインストールします。コマンドは公式のドキュメントを参照してください。
- Tailwind CSS: Tailwind CSSのバージョン 3.2.7をインストールします。
- Stripe CLI: ローカル環境でStripeの決済機能をテストする場合、Stripe CLIをインストールします。


### パッケージのインストール
上記の要件を満たすために、プロジェクトのルートディレクトリで以下のコマンドを実行します。

composer install
npm install
上記のコマンドは、composer.json ファイルと package.json ファイルに記載された依存関係を解決し、必要なパッケージをインストールします。

マイグレーションとシーディング
データベースのマイグレーションとシーディングを行うために、以下のコマンドを実行します。
php artisan migrate
php artisan db:seed
これにより、データベースのテーブルが作成され、初期データが投入されます。

.env ファイルの編集:
プロジェクトのルートディレクトリにある .env ファイルを編集し、必要な設定を行います。具体的には、データベース接続情報やメール設定などを自分の環境に合わせて変更してください。

メール送信の設定:
メールが届くようにするために、.env ファイル内のメール設定を変更します。SMTPサーバーの設定やメールドライバーを適切なものに変更してください。

決済機能について
ローカル環境でのみのテストにあたってStripeCLIをインストールして利用しています。テストをしたい場合はStripeにて登録を済ませた後、以下のURLを参考に導入してください。"

## 他に記載することがあれば記述する
テストユーザー

　ユーザーネーム：Admin(管理者権限所持)
　email：kazunori_rakugaki@yahoo.co.jp
　password：admin

　ユーザーネーム：Owner(店舗責任者権限所持)
　email：owner@example.com
　password：owner

　ユーザーネーム：User(利用者権限所持)
　email：user@example.com
　password：user"
