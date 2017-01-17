#### 環境構築

```
# 作業環境へ移動
$ cd /PATH/TO/DIR
# レポジトリのクローン
$ git clone URL ./
# 依存ライブラリのインストール
$ composer install
$ npm install
# 設定ファイルの作成
$ cp .env_ex .env
# .envの内容を各自の環境に合わせて変更する
$ vi .env
# データベーステーブルを作成する
$ ./artisan migrate
```
