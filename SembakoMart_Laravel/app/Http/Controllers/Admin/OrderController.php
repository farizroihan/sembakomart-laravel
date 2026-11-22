<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;use App\Models\Order;use Illuminate\Http\Request;
class OrderController extends Controller {public function index(){$orders=Order::with('items')->latest()->paginate(20);return view('admin.orders',compact('orders'));}public function status(Request $r,Order $order){$d=$r->validate(['status'=>'required|in:Dikonfirmasi,Diproses,Dikirim,Selesai,Dibatalkan']);$order->update($d);return back()->with('success','Status diperbarui.');}}