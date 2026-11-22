<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Order extends Model {protected $fillable=['user_id','order_number','customer_name','phone','address','payment_method','total','status','notes'];protected function casts():array{return ['total'=>'integer'];}public function user(){return $this->belongsTo(User::class);}public function items(){return $this->hasMany(OrderItem::class);}}