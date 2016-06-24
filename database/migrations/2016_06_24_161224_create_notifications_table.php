<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNotificationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('notifications', function(Blueprint $table)
		{
			$table->increments('id');
			$table->bigInteger('from_id')->unsigned()->index();
			$table->string('from_type')->nullable()->index();
			$table->bigInteger('to_id')->unsigned()->index();
			$table->string('to_type')->nullable()->index();
			$table->integer('category_id')->unsigned()->index();
			$table->string('url')->nullable();
			$table->string('extra')->nullable();
			$table->boolean('read')->default(0);
			$table->timestamps();
			$table->dateTime('expire_time')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('notifications');
	}

}
