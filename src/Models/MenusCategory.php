<?php namespace Rts\Menu\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use \DB;

class MenusCategory extends Model {

	protected $table = 'menus_category';
	
	public $timestamps = false;
	
	//Делаем поля доступными для автозаполнения
    protected $fillable = array('id', 'slug');
	
	public static $rules = [
		'slug'=>'required|unique:menus_category|min:2|max:100'
	];
	
	public function items(){
		return $this->hasMany('\Rts\Menu\Models\MenusItems','category_id', 'id');
	}
}