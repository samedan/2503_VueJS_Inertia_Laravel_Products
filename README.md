### Source

> https://gist.github.com/edomaru/c593651692bfa2f11a7843627656a731

### Category Model

### Product Model, Factory, etc

### Routes in Vue Inertia

> web.php ->

# List routes

> php artisan route:list

# Products routes

```
Route::middleware('auth')->group(function () {
    Route::resource('/products', ProductController::class); ...})
```

> php artisan route:list --path=products

### Products Inertia Vue files

> /resources//js/Pages/Product/Index.vue

# ProductController

> public function index()

```
    {
        // return Inertia::render('Product/Index');
        return inertia('Product/Index');
    }
```

### Control the Product data sent to vue

> ProductResource.php

> CategoryResource.php

### Remove 'data' from response

> AppServiceProvider.php -> JsonResource::withoutWrapping();
