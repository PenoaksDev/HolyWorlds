<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserProfilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_profiles', function(Blueprint $table)
		{
			$table->string('id')->primary();
			$table->string('family_name')->nullable();
			$table->string('avatar_url')->nullable();
			$table->string('location')->nullable();
			$table->string('website')->nullable();
			$table->string('interests')->nullable();
			$table->string('occupation')->nullable();
			$table->text('about', 65535)->nullable();
			$table->text('signature', 65535)->nullable();
			$table->boolean('signature_bbcode')->default(0);
			$table->integer('post_count')->unsigned()->default(0);
			$table->float('timezone', 10, 0)->default(-6);
			$table->boolean('dst')->default(1);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_profiles');
	}

}
