<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->string('id')->primary();
			$table->integer('oldid')->unsigned();
			$table->string('name');
			$table->string('email')->unique();
			$table->boolean('usebbhash')->default(1);
			$table->string('password');
			$table->string('remember_token')->nullable();
			$table->string('activation_token')->nullable();
			$table->string('activation_updated')->nullable();
			$table->string('created_at')->nullable();
			$table->string('updated_at')->nullable();
			$table->string('visited_at')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
