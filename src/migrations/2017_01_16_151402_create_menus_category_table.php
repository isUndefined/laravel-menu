<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('menus_category', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug',100);	
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