<x-app-layout>
  <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ __('Dashboard') }}
      </h2>
  </x-slot>

  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 bg-white border-b border-gray-200">
                <x-flash-message status="session('status')"></x-flash-message>
                  @foreach ($shops as $shop)
                  <div class="w-1/2 p-4">
                  {{-- クリックしたらedit画面に飛ぶようにする --}}
                   <a href="{{route('owner.shops.edit',['shop'=>$shop->id])}}">
                    {{-- 角をとったパディング上下左右4方向にボーダーをいれる --}}
                   <div class="border rounded-md p-4">
                    <div class="mb-4">
                    {{-- is_sellingがtrueなら販売中falseなら停止中 --}}
                    @if($shop->is_selling)
                      <span class="border p-2 rounded-md bg-blue-400 text-white">販売中</span>
                    @else
                      <span class="border p-2 rounded-md bg-red-400 text-white">停止中</span>    
                    @endif
                   </div>
                    <div class="text-xl">{{ $shop->name }}</div>
                    <x-thumbnail :filename="$shop->filename" type="shops" />
                    
                    {{-- filename(画像)が空だったらnoimageを表示する。入っていたらその画像を表示する→コンポーネント化する --}}
                    {{-- <div>
                        @if(empty($shop->filename))
                          <img src="{{ asset('images/no_images.jpg')}}">
                        @else
                        画像アップロード先PATH
                          <img src="{{ asset('storage/shops/' . $shop -> filename)}}">
                        @endif
                    </div> --}}
                   </a>
                </div>
                  @endforeach
              </div>
          </div>
      </div>
  </div>
</x-app-layout>
