<?php
namespace App\Services\Cart;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Cart;

class CartService
{
  public static function getItemsInCart($items){
    //空の配列を準備
    $products = [];
    // カート内の商品を一つずつ処理 略(次ページ)
    foreach($items as $item)
    { 
      $p = Product::findOrFail($item->product_id);
      $owner = $p->shop->owner->select('name', 'email')->first()->toArray();//オーナー情報 
      $values = array_values($owner); //連想配列の値を取得
      $keys = ['ownerName', 'email'];
      $ownerInfo = array_combine($keys, $values); // オーナー情報のキーを変更 
      $product = Product::where('id', $item->product_id)
      ->select('id', 'name', 'price')->get()->toArray(); // 商品情報の配列 
      $quantity = Cart::where('product_id', $item->product_id)
      ->select('quantity')->get()->toArray(); // 在庫数の配列
      //ddd($ownerInfo,$product,$quantity);
      $result = array_merge($product[0], $ownerInfo, $quantity[0]); // 配列の結合 
      array_push($products, $result); //作成した空の配列に追加
      // 新しい配列を返す
      return $products;
    }
  }
}