<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     * User,Admin,Ownerそれぞれのログイン後のリダイレクト先パスの設定
     * @var string
     */
    public const HOME = '/';
    public const OWNER_HOME = '/owner/dashboard'; 
    public const ADMIN_HOME = '/admin/dashboard';


    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     * ルート情報を設定する。bootメソッド→サービスプロバロバイダが読み込まれたあとに実行されるメソッド
     * ルートパターンはミドルウェアのap1とwebを使う２パターン
     * laravelでviewを表示してリクエストレスポンスを返すパターンは→web
     * フロントをすべてjsなどで作る場合はapiを使う
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            //prefix→URLの頭にapiとつける。Routeはファサード内のメソッドを使ってる
            Route::prefix('api')
            //ミドルウェアをグループのすべてのルートに割り当てる
                ->middleware('api')
                ->namespace($this->namespace)
            //ヘルパ関数base_pathでroutes/api.phpのすべてのURLにapiがつく
                ->group(base_path('routes/api.php'));

            //prefix('/')でownerやadminがついてないURLは/へ遷移させる
            Route::prefix('/')
            //asをつけることで別名にできる'/'→'user.'に変わる
                ->as('user.')
                ->middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
            
            //owner用
            Route::prefix('owner')
                ->as('owner.')
                ->middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/owner.php'));

            //admin用
            Route::prefix('admin')
                ->as('admin.')
                ->middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/admin.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
