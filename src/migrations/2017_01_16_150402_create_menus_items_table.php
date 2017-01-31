<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('menus_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id');
            $table->integer('menus_id');
            $table->integer('category_id');
            $table->string('icon',200);
            $table->integer('sort');
			$table->text('url');	
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