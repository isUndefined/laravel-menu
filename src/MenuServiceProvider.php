<?php namespace Rts\Menu;
 
use Illuminate\Support\ServiceProvider;
 
class MenuServiceProvider extends ServiceProvider
{
		public function boot(){

		$this->publishes([
            __DIR__ . '/migrations/' => $this->app->databasePath() . '/migrations'
        ], 'menu_migrations');
		
		$this->publishes([
            __DIR__ . '/../config/' => $this->app->configPath()
        ], 'menu_config');
		
		$this->loadViewsFrom( __DIR__ . '/views', 'MenuView');
		
		require __DIR__ . '/Http/routes.php';
	}

	
	public function register(){
		$this->app->bind('Menu', function () {
			return new Menu;
		});
	}
 
}