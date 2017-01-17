<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * message_content_id: ユニークキー
 * message_content_message_id: message.message_id
 * message_content_writer_id: 書き込んだ人のuser.user_id
 * message_content_reader_id: 書き込みの対象の人のuser.user_id
 * message_content_text: 書き込まれた内容
 * created_at
 * updated_at
 * deleted_at
 */
class CreateMessageContentTable extends Migration
{
    private $table_name = "message_content";

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->table_name) === true) {
            echo $this->table_name . "は既に存在しています．" . PHP_EOL;

        } else {
            Schema::create($this->table_name, function ($table) {
                $table->increments($this->table_name . "_id");
                $table->integer($this->table_name . "_message_id");
                $table->integer($this->table_name . "_writer_id");
                $table->integer($this->table_name . "_reader_id");
                $table->text($this->table_name . "_text");
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
