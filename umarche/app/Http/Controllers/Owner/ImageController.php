<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Image;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UploadImageRequest;
use App\Services\Image\ImageService;

class ImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:owners'); 

        $this->middleware(function ($request, $next) {
            $id = $request->route()->parameter('image');
            if(!is_null($id)){
            $imagesOwnerId = Image::findOrFail($id)->owner->id;
              $imageId = (int)$imagesOwnerId;
              if($imageId !== Auth::id()){
              abort(404);
              }
            }
            return $next($request);
        });
    }

    public function index()
    {
        $this->viewData['images'] = Image::where('owner_id', Auth::id())
        ->orderBy('updated_at','desc')
        ->paginate(20);
        return view('owner.images.index', $this->viewData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('owner.images.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UploadImageRequest $request)
    {
        //create.blade側のinputのname属性filesを引数とすることで、複数の画像を配列形式で取得
        $imageFiles = $request->file('files');
        if(!is_null($imageFiles)){
            //foreachで1つずつImageServiceでファイル名作る→拡張子取得→interventionImageでリサイズ
            //出来上がったファイル名を$fileNameToStoreとして取得し、createで保存
            foreach($imageFiles as $imageFile){
                $fileNameToStore = ImageService::addByImage($imageFile, 'products');
                 Image::create([
                    'owner_id' => Auth::id(),
                    'filename' => $fileNameToStore
                 ]);
            }
        }
        return redirect()->route('owner.images.index')->with(['message' => '画像登録を実施しました。','status'=>'info']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
