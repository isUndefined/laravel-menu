<?php namespace Rts\Menu\Http\Controllers;
 
use App\Http\Controllers\Controller;
use App\Composers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as IRoutingCController;
use \Auth;
use Rts\Menu\Models\Menus;
use Rts\Menu\Models\MenuBild;
use Rts\Menu\Models\MenusItems;
use Rts\Menu\Models\MenusCategory;
use Rts\Menu\Models\MenusTranslate;
 
class MenuController extends Controller
{
	public function backendIndex(){
		$menus = MenusCategory::orderBy('id','desc')->get();		
		return view('MenuView::back.list', compact('menus'));
	}
	
	public function backendViewMenu($id){	
		$menus = MenusCategory::where('id', '=', $id)->firstOrFail();
		
		$menuBild = new MenuBild;
		$menuItems = $menuBild->prepare($menus->slug);
		
		$renderMenu = $menuBild->print_menu_editor($menuItems);
		
		return view('MenuView::back.menu', compact('menus','renderMenu'));
	}
	
	public function backendUpdatePosition(Request $request){

		$hierarhy = $request->input('hierarhy');
		$source = $request->input('source');
		$sourceParent = $request->input('menus');
		
		$menuBild = new MenuBild;
		// * Get array road
		$sourceDeep = $menuBild->array_find_deep($hierarhy,$source, 'id');
		
		// * Select childrens for parent by child
		$neighbours = $hierarhy;
		$sourceDeep = array_slice($sourceDeep,0,-2);
		foreach($sourceDeep as $deep){
			$neighbours = &$neighbours[$deep];
		}
		
		// * Update sort & parent
		if(count($neighbours)){
			$i = 1;
			foreach($neighbours as $k=>$menu){
				$menusItem = MenusItems::where('id',$menu['id'])->first();
				if($menusItem){
					$menusItem->sort = $i;
					if($source == $menu['id']){						
						$menusItem->parent_id = $sourceParent;
					}
					$menusItem->save();
					$i++;	
				}
			}
		}
	}
	
	public function backendCreateMenu(Request $request){
		
		//* Translate content
		if(checkL18n()){			
			$this->L18nTranslate();
		}
		
		// * Validation
		$v = \Validator::make($request->all(), Menus::$rules);
		if ($v->fails()){
            return redirect()->back()->with(['msg'=>$v->errors()->first()]);
        }
		
		$menus = new Menus;
		$menus->fill($request->all());		
		$menus->author_id = Auth::user()->id;
	
		if($menus->save()){
			$menusItem = new MenusItems;
			$menusItem->parent_id = 0;
			$menusItem->menus_id = $menus->id;
			$menusItem->category_id = $request->input('category');
			$menusItem->icon = $request->input('icon');
			$menusItem->sort = 0;
			$menusItem->url = $request->input('url');
			
			$menusItem->save();
			
			// * Save translate content on other languages
			if(checkL18n()){
				$this->L18nTranslateSave($menus);
			}
			
			return redirect()->back()->with('msg','Menu successfully added.');
		}
		return redirect()->back()->with('msg','Something wrong. Try again.');
	}
	
	public function L18nTranslate(){
		// * Get l18n inputs
		$L18nTranslate = \Request::input('L18nTranslate');
		
		$request = \Request::all();
		
		// * Replace values
		$request['name'] = (!empty($L18nTranslate[config('l18n.default')]['name']) ? $L18nTranslate[config('l18n.default')]['name'] : '');
		
		\Request::replace($request);		
		
		// * For validation
		unset($L18nTranslate[config('l18n.default')]);
		if(!empty($L18nTranslate)){
			foreach($L18nTranslate as $locale=>$data){
				if(!empty($data['name'])){
					Menus::$rules['L18nTranslate.'.$locale.'.name'] = 'required|min:3|max:250';				
					
					Menus::$messages['L18nTranslate.'.$locale.'.header.min'] = 'The name in '.$locale.'  may not be small than :min.';					
					Menus::$messages['L18nTranslate.'.$locale.'.header.max'] = 'The name in '.$locale.'  may not be greater than :max.';					
				}
			}
		}
	}
	
	public function L18nTranslateSave($menus){
		// * Get l18n inputs
		$L18nTranslate = \Request::input('L18nTranslate');
		
		unset($L18nTranslate[config('l18n.default')]);
		
		if(!empty($L18nTranslate)){

			foreach($L18nTranslate as $locale=>$data){
				if(!empty($data['name'])){
					
					if(!$l18n = MenusTranslate::where('locale',$locale)
											->where('menus_id',$menus->id)
											->first()){
						$l18n = new MenusTranslate();						
						$l18n->locale = $locale;
						$l18n->menus_id = $menus->id;
					}
					
					$l18n->name = $data['name'];					
					$l18n->save();
				} else {
					if($emptyTranslate = MenusTranslate::where('locale',$locale)
											->where('menus_id',$menus->id)
											->first()){
						$emptyTranslate->delete();
					}
				}
			}
		}
	}
	
	public function backendUpdateMenu(Request $request){
		$info = json_decode($request->input('menu_data'));
		
		$menus = Menus::where('id',$info->id)->firstOrFail();
		
		//* Translate content
		if(checkL18n()){			
			$this->L18nTranslate();
		}
		
		// * Validation
		$v = \Validator::make($request->all(), Menus::$rules);		
		if ($v->fails()){
            return response()->json(['status'=>'error','msg'=>$v->errors()]);
        }

		$menus->name = $request->input('name');		
		$menus->author_id = Auth::user()->id;		
		
		if($menus->save()){
			$menusItems = $menus->menus_items;
			$menusItems->url = $request->input('url');
			$menusItems->icon = $request->input('icon');
			$menusItems->save();
			
			// * Save translate content on other languages
			if(checkL18n()){
				$this->L18nTranslateSave($menus);
			}
			
			return response()->json(['status'=>'success','msg'=>'Menu updated.']);
		}
		
		return response()->json(['status'=>'error','msg'=>'System error. Try again.']);
	}
	
	public function backendDeleteMenu($relation_id){
		$menusItems = MenusItems::where('id',$relation_id)->firstOrFail();
		$mainMenusId = $menusItems->menus_id;
		
		$menuBild = new MenuBild;
		$childrensIds = $menuBild->prepare($menusItems->category->slug, $menusItems->menus_id);
	
		// * Remove parent
		$menusItems->menus()->delete();
		$menusItems->delete();
		
		// * Remove childrens
		$menusIds = array();
		if(!empty($childrensIds)){
			$menusIds = $menuBild->array_keys_multi($childrensIds);
			MenusItems::whereIn('menus_id',$menusIds)->delete();
		}
		$menusIds[] = $mainMenusId;
		
		MenusTranslate::whereIn('menus_id',$menusIds)->delete();
		
		return redirect()->back()->with(['msg'=>'Menus successfully deleted']);
	}
	
	public function backendShowUpdateMenu(Request $request){
		
		$menus = Menus::where('id',$request->input('menus'))->firstOrFail();
		$menus_item = $menus->menus_items;
		
		$renderModal = view('MenuView::back.modal_edit_menu', compact('menus','menus_item'))->render();
		
		return response()->json(['html'=>$renderModal]);
	}
	
	public function backendNewMenu(){
		
		return view('MenuView::back.menu_create');
	}
	
	public function backendNewMenuStore(Request $request){
		
		$slug = $this->cleanCategorySlug($request->input('slug'));
		
		$request->input('slug',$slug);
		
		$v = \Validator::make($request->all(), MenusCategory::$rules);
		if ($v->fails()){
            return redirect()->back()->with(['msg'=>$v->errors()->first()]);
        }
		
		$category = new MenusCategory;
		$category->slug = $slug;
		$category->save();
		
		return redirect()->route('backend.menu.manage')->with(['msg'=>'Menu category successfully created.']);
	}
	
	public function cleanCategorySlug($slug){
		
		$cleanSlug = preg_replace("/[^A-Za-z0-9?!]/",'_',$slug);
		if(substr_count($cleanSlug,'_')){
			$aSlug = explode('_',$cleanSlug);
			$cleanSlug = '';
			foreach($aSlug as $word){
				$cleanSlug .= ucfirst($word);
			}
		}
		
		return $cleanSlug;
	}
	
	
	public function backendDeleteMenusCategory($id){
		
		// * Чистим ИД
		$aId = explode(',',$id);
		$aId = array_map(function($e){
			return (int)trim($e);
		},$aId);
		$aId = array_filter($aId);
			
		if(count($aId)){
			$menusCategory = MenusCategory::whereIn('id',$aId)->get();
			if($menusCategory->count()){
				
				foreach($menusCategory as $category){
					
					// * Delete main menu items relations
					if($category->items()->count()){
						
						foreach($category->items as $item){
							$item->delete();
							
							// * Delete main menu items
							$item->menus()->delete();
							
							// * Delete main menu items on other languages
							if(checkL18n()){	
								$this->L18nTranslateDelete($item);
							}
						}
					}
					
					// * Delete menu category
					$category->delete();
					
				}
				
				return redirect()->route('backend.menu.manage')->with(['msg'=>'Menus deleted successfully']);	
			}
			
		}
		
	}
	
		
	public function L18nTranslateDelete($menus){
		$L18n = MenusTranslate::where('menus_id', $menus->menus_id)
								->get();
		if($L18n->count()){
			foreach($L18n as $item){
				$item->delete();
			}
		}
	}
	
	
}
	