<?php namespace Rts\Menu\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Rts\Menu\Models\MenusTranslate;
use \DB;

class Menus extends Model {

	protected $table = 'menus';
	
	public $timestamps = false;
	
	//Делаем поля доступными для автозаполнения
    protected $fillable = array('name', 'author_id');
	
	public static $rules = [
		'name'=>'required|min:3|max:250',
		'url'=>'required|min:1|max:500',
	];
	
	protected $translatable = array('name');
	
	protected $translateModel = array();
		
	public static $messages = array();
	
	public function author(){
		return $this->belongsTo('\App\User','author_id');
	}
	
	public function menus_items(){
		return $this->hasOne('\Rts\Menu\Models\MenusItems','menus_id');
	}
	
	public function __get($key){
		if(in_array($key,$this->translatable)){
			$currentLocale = app()->getLocale();

			if($currentLocale!=config('l18n.default')){
				if(empty($this->translateModel)){
					$this->translateModel = $this->menusLocale($currentLocale,true);
				}
				$this->attributes[$key]	= $this->translateModel->$key;
			}
		} 
		return $this->getAttribute($key);
	}
	
	public function menusLocale($locale, $replaceWithDefault = false){
		
		if(!in_array($locale, array_keys(config('l18n.locales')))){
			return false;
		}
		
		if (!$l18n = MenusTranslate::where('locale',$locale)
											->where('menus_id', $this->attributes['id'])											
											->first()){
				if($replaceWithDefault or $locale == config('l18n.default')){					
					$l18n = DB::table('menus')->where('id',$this->attributes['id'])->first();
				}
			}
			
		return $l18n;
	}
}