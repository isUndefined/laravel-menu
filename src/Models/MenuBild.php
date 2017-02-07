<?php

namespace Rts\Menu\Models;

use Illuminate\Database\Eloquent\Model;
use \DB;
use Rts\Menu\Models\MenusItems;
use Rts\Menu\Models\MenusCategory;

class MenuBild extends Model
{	

    public function prepare($name,$parent=0){
		$menu_category = MenusCategory::whereSlug($name)->first();
		if(!$menu_category){
			return false;
		}
		$items = MenusItems::join('menus','menus_items.menus_id','=','menus.id')->select('*','menus_items.id as item_id')->where('category_id',$menu_category->id)->get()->toArray();
		
		if(checkL18n()){	
			$transItems = new \Rts\Menu\Models\MenusTranslate;
			$transItems = $transItems::all()->toArray();
			$groupTransItems = array();
			foreach($transItems as $transItem){
				$groupTransItems[$transItem['menus_id']][$transItem['locale']] = $transItem;
			}
			foreach($items as $k=>$item){
				if(isset($groupTransItems[$item['id']])){
					$items[$k]['translate'] = $groupTransItems[$item['id']];
				}
			}
			
		}
		
		$items = $this->build($items,$parent);

		return $items;
	}
	
	public function get_menu($name,$view=null){		
		$items = $this->prepare($name);
		if(!$items){
			return false;
		}
		if($view && in_array($view, config('menu.templates'))){			
			return view('MenuView::templates.'.$view, compact('items'));
		}
		return $this->print_menu($items);
	}
	
	public function build($categories, $parent = null, $level = 0){
		$return = array();

		usort($categories, function ($a, $b) { 
			return strcmp($a["sort"], $b["sort"]); 
		});
		
		foreach($categories as $index => $category)
		{
			$category = (array)$category;
			if($category['parent_id'] == $parent)
			{
				$return[$category['id']] = $category;
				if($hasChildren = $this->build($categories, $category['menus_id'], $level+1)){
					$return[$category['id']]['children'] = $hasChildren;				
				}
			}
		}
		return $return;
	}
	
	public function print_menu($menu, $active = false){
		$str = '';	
		
		usort($menu, function ($a, $b) { 
			return strcmp($a["sort"], $b["sort"]); 
		});
		
		foreach($menu as $item){
			$str .= '<li><a href="'. $item['url'] .'">'. (isset($item['icon'])?"<i class='". $item['icon'] ."'></i> ":"") . $item['name'].'</a>';
			if(!empty($item['children'])){
				$str.='<ul class="treeview-menu">';
				$str .= $this->print_menu($item['children']);
				$str.='</ul>';
			}
			$str .= '</li>';
		}
		return $str;
	}
	
	public function print_menu_editor($menu) {
		$str ='';
		
		usort($menu, function ($a, $b) { 
			return strcmp($a["sort"], $b["sort"]); 
		});
		
		foreach($menu as $item){
			
			$withoutChildren = $item;
			if(!empty($withoutChildren['children'])){
				unset($withoutChildren['children']);
			}
			$editing ='<a href="/backend/menu/delete/'.$item['item_id'].'" onclick="if(!confirm(\'Are you sure? All childrens will be deleted.\')) return false;" class="btn btn-xs btn-danger pull-right btn-delete"><i class="fa fa-times"></i></a>';
			$editing .='<a data-info=\''.json_encode($withoutChildren).'\' class="btn btn-xs btn-info pull-right btn-edit"><i class="fa fa-pencil"></i></a>';
			
			
			$str .= '<li class="dd-item dd3-item" data-id="'.$item['item_id'].'" data-menu="'.$item['menus_id'].'" data-sort="'.$item['sort'].'">
			<div class="dd-handle dd3-handle"></div>
			<div class="dd3-content"><i class="fa '.$item['icon'].'"></i> <h6>'.$item['name'].'</h6> '.$editing.'</div>';
			
			if(!empty($item['children'])){
				$str .= '<ol class="dd-list">';
				$str .= $this->print_menu_editor($item['children']);
				$str .= '</ol>';
			}
			$str .= '</li>';
		}
		
		return $str;
	}
	
	
	public function array_find_deep($array, $search, $elemKey = null, $keys = array())
	{
		foreach($array as $key => $value) {
			if (is_array($value)) {			
				$sub = $this->array_find_deep($value, $search, $elemKey, array_merge($keys, array($key)));
				if (count($sub)) {
					return $sub;
				}
			} elseif ($value === $search) {
				if($elemKey && $key==$elemKey){
					return array_merge($keys, array($key));
				}
			}
		}

		return array();
	}
	
	public function array_keys_multi($array = array())
	{
		$keys = array();
		foreach ($array as $key => $value) {
			$keys[] = $key;			
			if (isset($value['children'])) {
				$keys = array_merge($keys, $this->array_keys_multi($value['children']));
			}
		}
		return $keys;
	}
	
}
