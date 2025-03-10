### Source

> https://gist.github.com/edomaru/c593651692bfa2f11a7843627656a731

### This Git

> https://github.com/samedan/2503_VueJS_Inertia_Laravel_Products

### Udemy

> https://www.udemy.com/course/laravel-vuejs-fullstack-web-development/learn/lecture/42801270#questions

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

### Create Product Form

> /resources/js/Pages/Product/Create.vue

# Validation

> StoreProductRequest.php

# Store in ProudctController

```
public function store(StoreProductRequest $request)
    {
        $request->user()->products()->create($request->validated());

        // reload page
        return redirect()->route('products.index');

    }
```

### Error change backend returned text

> StoreproductRequest.php -> public function attributes()

> ![Error rename](https://github.com/samedan/2503_VueJS_Inertia_Laravel_Products/blob/main/_printscreens/01printscreen.jpg)

### Chaneg price before writing to DBB

### Toasts

> ProductController.php -> with('message', 'x')

> app/Http/Middleware/HandleInertiaRequests.php -> 'toast'

# Authenticatedlayout.vue

> <div v-if="$page.props.toast.message">...
