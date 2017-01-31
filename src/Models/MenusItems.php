<?php namespace Rts\Menu\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use \DB;

class MenusItems extends Model {

	protected $table = 'menus_items';
	
	public $timestamps = false;
	
	//Делаем поля доступными для автозаполнения
    protected $fillable = array('parent_id', 'menus_id', 'category_id', 'icon', 'sort', 'url');
	
	public function category(){
		return $this->belongsTo('\Rts\Menu\Models\MenusCategory','category_id');
	}
	
	public function menus(){
		return $this->belongsTo('\Rts\Menu\Models\Menus','menus_id');
	}
}