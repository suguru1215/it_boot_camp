<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * user_id: ユニークキー
 * user_name: ユーザー表示名
 * user_real_name: ユーザー名
 * user_email: ユーザーメールアドレス
 * user_password: ユーザーパスワード
 * user_image: ユーザー画像
 * user_birthday: ユーザー誕生日
 * user_gender: ユーザー性別
 * user_address: ユーザー住所
 * user_role: ユーザー権限
 * user_rank: ユーザーランク
 * user_user_group_id: ユーザーグループのID user_group.user_group_id
 * user_pr_text: ユーザーPR文
 * user_facebook_id: ユーザーFacebook-ID
 * user_facebook_access_token: ユーザーFacebook-Token
 * user_twitter_id: ユーザーTwitter-ID
 * user_twitter_access_token: ユーザーTwitter-Token
 * user_is_mail_magazine: ユーザーメルマガ受信設定
 * user_login_time: ユーザー最終ログイン時間
 * created_at
 * updated_at
 * deleted_at
 */
class CreateUserTable extends Migration
{
    private $table_name = "user";

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->table_name) === true) {
            echo $this->table_name . "は既に存在しています。" . PHP_EOL;

        } else {
            Schema::create($this->table_name, function ($table) {
                $table->increments($this->table_name . "_id");
                $table->text($this->table_name . "_name");
                $table->text($this->table_name . "_real_name");
                $table->text($this->table_name . "_email");
                $table->text($this->table_name . "_password");
                $table->text($this->table_name . "_image");
                $table->text($this->table_name . "_birthday");
                $table->smallInteger($this->table_name . "_gender");
                $table->text($this->table_name . "_address");
                $table->smallInteger($this->table_name . "_role");
                $table->smallInteger($this->table_name . "_user_group_id");
                $table->text($this->table_name . "_pr_text");
                $table->text($this->table_name . "_facebook_id");
                $table->text($this->table_name . "_facebook_access_token");
                $table->text($this->table_name . "_twitter_id");
                $table->text($this->table_name . "_twitter_access_token");
                $table->smallInteger($this->table_name . "_is_mail_magazine");
            });

            DB::statement("alter table " . $this->table_name . " add column user_login_time timestamp null");
            DB::statement("alter table " . $this->table_name . " add column created_at timestamp not null default now()");
            DB::statement("alter table " . $this->table_name . " add column updated_at timestamp not null default now()");
            DB::statement("alter table " . $this->table_name . " add column deleted_at timestamp null");

            echo $this->table_name . "を作成しました。" . PHP_EOL;
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable($this->table_name) === true) {
            Schema::drop($this->table_name);

            echo $this->table_name . 'を削除しました。' . PHP_EOL;

        } else {
            echo $this->table_name . 'は存在していません。' . PHP_EOL;
        }
    }
}
