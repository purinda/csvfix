<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatasetsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('files', function($t) {

            // auto increment id (primary key)
            $t->increments('id');

            $t->text('directory');
            $t->text('file_name');
            $t->integer('file_size')->unsigned();

            // created_at, updated_at DATETIME
            $t->timestamps();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop("files");
	}

}
