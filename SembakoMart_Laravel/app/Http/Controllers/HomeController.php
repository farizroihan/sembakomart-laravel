<?php
namespace App\Http\Controllers;
use App\Models\{Product,Category};use Illuminate\Http\Request;
class HomeController extends Controller {
 public function index(Request $r){$products=Product::with('category')->where('is_active',1)->when($r->q,fn($q,$v)=>$q->where('name','like',"%$v%"))->latest()->paginate(12)->withQueryString();$categories=Category::withCount('products')->get();return view('home',compact('products','categories'));}
 public function show(Product $product){abort_unless($product->is_active,404);return view('product',compact('product'));}
 public function category(Category $category){$products=$category->products()->where('is_active',1)->paginate(12);$categories=Category::all();return view('home',compact('products','categories'));}
}