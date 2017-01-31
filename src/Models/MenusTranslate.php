<?php namespace Rts\Menu\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use \DB;

class MenusTranslate extends Model {

	protected $table = 'menus_translate';
	
	public $timestamps = false;
	
	//Делаем поля доступными для автозаполнения
    protected $fillable = array('name', 'menus_id', 'locale');
	
}
	