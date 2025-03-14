<?php

namespace App\Http\Controllers;

use App\Http\Requests\BulkUpdateProductRequest;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = auth()->user()
            ->products()
            ->with('category') // eager loading
            ->where(function ($query) {
                if($search = request()->search) {
                    $query->where('name', 'like', '%'.$search.'%')
                        ->orWhereHas('category', function($query) use ($search) {
                            $query->where('name', 'like', '%'.$search.'%');
                        });
                }
            })
            // ->latest()
            ->when(!request()->query('sort_by'), function($query) {
                    $query->latest();
                })
            ->when(in_array(request()->query('sort_by'), [
                'name', 'price', 'weight'
            ]), function($query) {
                    $sortBy = request()->query(('sort_by'));
                    $field = ltrim($sortBy, '-');
                    $direction = substr($sortBy, 0, 1) === '-' ?"desc": "asc";
                    $query->orderBy($field, $direction );
                })
            ->paginate(10)
            ->withQueryString(); // keep the search results
        // return ProductResource::collection($products);
        // return Inertia::render('Product/Index');
        return inertia('Product/Index', [
            'products' => ProductResource::collection($products),
            'categories' => CategoryResource::collection(Category::orderBy('name')->get()),
            'query' => (object) request()->query()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Product/Create', [
            'categories' => CategoryResource::collection(Category::orderBy('name')->get())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        // TODO Validation
        $request->user()->products()->create($request->validated());

        // reload page
        return redirect()->route('products.index')->with('message', 'Product has been created successfully.');

    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return inertia('Product/Show', [
            'product' => ProductResource::make($product)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return inertia('Product/Edit', [
            'categories' => CategoryResource::collection(Category::orderBy('name')->get()),
            'product' => ProductResource::make($product)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(UpdateProductRequest $request, Product $product)
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());
        return redirect()->route('products.index')->with('message', 'Product has been updated successfully.');

    }

    /**
     * BULK Update the specified resource in storage.
     */

    public function bulkUpdate(BulkUpdateProductRequest $request)
    {
        Product::whereIn('id', $request->product_ids)
            ->update([
                'category_id' =>$request->category_id
            ]);
        return redirect()->route('products.index')
            ->with('message', 'Selected Products updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        // return back(); // return to prevcious page
        return redirect()->route('products.index')->with('message', 'Product has been deleted.');
    }

    /**
     * Removes MULTI resources from storage.
     */
    public function bulkdestroy(string $ids)
    {
        // 123,125,165 => ['123', '125', '165']
        $ids = explode(',', $ids);
        Product::destroy($ids);
        // return back(); // return to prevcious page
        return redirect()->route('products.index')->with('message', 'Selected product have been deleted.');
    }
}
