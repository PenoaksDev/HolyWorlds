<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('settings', function(Blueprint $table)
		{
			$table->string('key')->unique();
			$table->string('title')->nullable();
			$table->text('desc', 65535)->nullable();
			$table->string('global_perm')->default('sys.admin');
			$table->string('public_perm')->default('sys.user');
			$table->boolean('global_only')->default(0);
			$table->boolean('type')->default(0);
			$table->string('enum')->default('');
			$table->string('def')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('settings');
	}

}
