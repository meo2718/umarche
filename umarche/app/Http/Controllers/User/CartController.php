<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        //ログインしてるユーザーの取得
        $user = User::findOrFail(Auth::id());
        //userに紐づくproductsを取得→多対多のリレーション
        $products = $user->products;
        $totalPrice = 0;
        foreach($products as $product){
            //productのquantityをとpriceをかけて合計金額をだしてtotalpriceに＋する→中間テーブルなのでpivotをはさむ
            $totalPrice += $product->price * $product->pivot->quantity;
        }
        //dd($products,$totalPrice);
        return view('user.cart',compact('products','totalPrice'));
    }

    public function add(Request $request)
    {
        //カートに商品があるか確認(product_idがあるかどうか、'user_id', Auth::id())でログインしているユーザーであるかどうか)
        $itemInCart = Cart::where('user_id', Auth::id())->where('product_id', $request->product_id)->first();
        //もしカートに商品があれば、数量を追加する。(すでにカートに商品が1あった場合、2つ追加したら3になるようにする)
        if($itemInCart){
        $itemInCart->quantity += $request->quantity;
        $itemInCart->save();
        //なければ商品を新規作成
        } else {
        Cart::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'quantity' => $request->quantity
        ]); 
        }
        //dd('test');
        return redirect()->route('user.cart.index');
    }

    public function delete($id)
    {
        //product_idのidとログインしているuser_id検索してきて削除
        Cart::where('product_id', $id) ->where('user_id', Auth::id())->delete();
        return redirect()->route('user.cart.index');
    }

    public function checkout()
    {
        $user = User::findOrFail(Auth::id());
        //userに紐づくproductsを取得→多対多のリレーション
        $products = $user->products;
        //カートに入ってる商品
        $lineItems = [];
        //foreachですべてのカートに入ってる商品を取得しlineitemsという配列へ追加していく
        foreach($products as $product){
            //商品情報をstripe側に受け取れる形にして渡す→stripe側で用意してるパラメータを使用
            $lineItem = [
                'name' => $product->name,
                'description' => $product->information,
                'amount' => $product->price,
                'currency' => 'jpy',
                'quantity' => $product->pivot->quantity,
            ];
            //$lineItemsへ追加
            array_push($lineItems,$lineItem);
        }
        dd($lineItems);
    }
}
