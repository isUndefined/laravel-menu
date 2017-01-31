<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('menus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',250);
			$table->integer('author_id');	
        });
	}
	
	/**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
	
}