<?php namespace Rts\Menu;

use Rts\Menu\Models\MenuBild;

class Menu {
	public function show($name, $template=null){
		$menu = new MenuBild;
		return $menu->get_menu($name, $template);
	}
	
	public function print_menu_editor($menu){
		$menu = new MenuBild;
		return $menu->print_menu_editor($menu);
	}
}
	