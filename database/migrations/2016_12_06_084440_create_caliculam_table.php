<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * caliculam_id: ユニークキー
 * caliculam_title: カリキュラム名
 * caliculam_pr_text: カリキュラムPR文
 * caliculam_text: カリキュラムテキスト
 * caliculam_price: カリキュラム価格
 * caliculam_image: カリキュラム画像
 * created_at
 * updated_at
 * deleted_at
 */
class CreateCaliculamTable extends Migration
{
    private $table_name = "caliculam";

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
                $table->text($this->table_name . "_title");
                $table->text($this->table_name . "_pr_text");
                $table->text($this->table_name . "_text");
                $table->integer($this->table_name . "_price");
                $table->text($this->table_name . "_image");
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
