# Laravel 5 Menu

### Instalation

**1.** Open `composer.json`, add:
```
"repositories": [
    {
        "type": "git",
        "name": "menu",			
	    "url": "http://git.rts.md/laravel/menu.git",
		"vendor-alias":"rts"
    }
],
```
**1a.** Run command:
```ssh
php composer.phar require "rts/menu":"*"
```

**2.** Open `config/app.php`, find and add:
```
'providers' => [
	...
	Rts\Menu\MenuServiceProvider::class,
	...
]
```
**3.** Open `config/app.php`, find and add:
```
'aliases' => [
	...
	'Menu'      	=> Rts\Menu\Facades\Menu::class,
	...
]
```

**4.** Run command:
```ssh
php artisan vendor:publish --tag=menu_migrations
```
```ssh
php artisan vendor:publish --tag=menu_config
```

**5.** Open `config/menu.php`, register your template in array:
```
'templates' => ['bootstrap','custom'],
```

### Show menu in blade
```
	{!! Menu::show('<menu_category>','<menu_template>') !!}
```
>Note: Parameter **menu_template** is optional and can be null.