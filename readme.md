# Laravel 5 Menu

### Instalation

**1.** Open `composer.json`, find and add:
```
"psr-4": {
	...
	"Isundefined\\Menu\\": "packages/Isundefined/Menu/src"
}
```
**1a.** Run command:
```ssh
php composer.phar update
```

**2.** Open `config/app.php`, find and add:
```
'providers' => [
	...
	Isundefined\Menu\MenuServiceProvider::class,
	...
]
```
**3.** Open `config/app.php`, find and add:
```
'aliases' => [
	...
	'Menu'      	=> Isundefined\Menu\Facades\Menu::class,
	...
]
```

**4.** Run command:
```ssh
php artisan vendor:publish --tag=menu_migrations
```

### Show menu in blade
```
	{!! Menu::show('<menu_category>','<menu_template>') !!}
```
>Note: Parameter **menu_template** is optional and can be null.