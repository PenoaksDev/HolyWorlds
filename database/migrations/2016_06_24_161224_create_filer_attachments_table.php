<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFilerAttachmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('filer_attachments', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->nullable();
			$table->string('title')->nullable();
			$table->string('description')->nullable();
			$table->string('model_type')->nullable();
			$table->integer('model_id')->unsigned()->nullable();
			$table->string('key')->nullable();
			$table->string('item_type')->nullable();
			$table->integer('item_id')->unsigned()->nullable();
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('filer_attachments');
	}

}
