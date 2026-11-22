<?php
namespace App\Http\Controllers;
use App\Models\Product;use Illuminate\Http\Request;
class CartController extends Controller {
 public function index(){$cart=session('cart',[]);$products=Product::whereIn('id',array_keys($cart))->get()->keyBy('id');$total=0;foreach($cart as $id=>$qty)if(isset($products[$id]))$total+=$products[$id]->price*$qty;return view('cart',compact('cart','products','total'));}
 public function add(Request $r,Product $product){$d=$r->validate(['quantity'=>'required|integer|min:1']);$c=session('cart',[]);$n=($c[$product->id]??0)+$d['quantity'];if($n>$product->stock)return back()->with('error','Stok tidak mencukupi.');$c[$product->id]=$n;session(['cart'=>$c]);return back()->with('success','Produk masuk keranjang.');}
 public function update(Request $r,Product $product){$d=$r->validate(['quantity'=>'required|integer|min:1']);if($d['quantity']>$product->stock)return back()->with('error','Jumlah melebihi stok.');$c=session('cart',[]);$c[$product->id]=$d['quantity'];session(['cart'=>$c]);return back();}
 public function remove(Product $product){$c=session('cart',[]);unset($c[$product->id]);session(['cart'=>$c]);return back();}
}