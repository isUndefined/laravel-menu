<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusTranslateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('menus_translate', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('menus_id');
            $table->string('name',250);	
            $table->string('locale',3);	
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