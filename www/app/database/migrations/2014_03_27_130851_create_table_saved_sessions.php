<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSavedSessions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up()
    {
        Schema::create("saved_sessions", function($table) {
            $table->increments("id");
            $table->string("name", 100);
            $table->string("description", 255);
            $table->integer("file_id")->unsigned();
            $table->integer("user_id")->unsigned();
            $table->mediumText("mappings");
            $table->timestamps();
        });

        Schema::table("saved_sessions", function($table) {
            $table->foreign("file_id")->references("id")->on("files")->onDelete('restrict');
            $table->foreign("user_id")->references("id")->on("users")->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop("saved_sessions");
    }


}
