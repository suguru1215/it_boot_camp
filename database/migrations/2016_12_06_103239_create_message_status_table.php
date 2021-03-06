<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * message_status_id: ユニークキー
 * message_status_message_id: message.message_id
 * message_status_user_id: user.user_id
 * message_status_read_status: 既読状態 {0: 未読, 1: 既読}
 * created_at
 * updated_at
 * deleted_at
 */
class CreateMessageStatusTable extends Migration
{
    private $table_name = "message_status";

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
                $table->integer($this->table_name . "_message_id");
                $table->integer($this->table_name . "_user_id");
                $table->smallInteger($this->table_name . "_read_status");
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
