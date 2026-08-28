<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query()
            ->when($request->search, fn ($q,$s)=> $q->where('name','like',"%{$s}%")->orWhere('code','like',"%{$s}%"))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

            return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $data= $this->validateProduct($request);
        $data['is_active'] = $request->boolean('is_active');

        if($request->hasFile('image')){
            $data['image']= $request->file('image')->store('products','public');
        }

        Product::create($data);

        return redirect()->route('products.index')->with('success','Produk berhasil ditambahkan!');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validateProduct($request,$product->id);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')){
            if($product->image){
                Storage::disk('public')->delete($product->image);
            }
            $data['image']= $request->file('image')->store('products','public');
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success','Produk berhasil diperbaharui!');
    }

    public function destroy(Product $product)
    {
        if($product->image){
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return redirect()->route('products.index')->with('success','Produk berhasil dihapus');
    }

    private function validateProduct(Request $request, ?int $productId= null): array
    {
        return $request->validate([
            'code'=>'required|string|max:255|unique:products,code' . ($productId ? ",$productId" : ''),
            'name'=>'required|string|max:255',
            'category'=>'nullable|string|max:255',
            'unit'=>'required|string|max:50',
            'price'=>'required|integer|min:0',
            'cost' =>'nullable|integer|min:0',
            'stock'=> 'required|integer|min:0',
            'image'=> 'nullable|image|max:2048'
        ]);
    }
}