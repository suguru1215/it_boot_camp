<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * message_id: ユニークキー
 * message_user_id: 投稿者のuser.user_id
 * created_at
 * updated_at
 * deleted_at
 */
class CreateMessageTable extends Migration
{
    private $table_name = "message";

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
                $table->integer($this->table_name . "_user_id");
            });

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
