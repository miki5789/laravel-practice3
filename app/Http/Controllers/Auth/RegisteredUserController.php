<?php

namespace App\Http\Controllers\Auth;
use DB;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Lib\MyFunction;

//viewで登録ボタン押す→controller->(モデル経由、登録)->viewに戻る

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }


    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {

        $request->validate([
            'surname' => ['required', 'string', 'max:255'],
            'given_name' => ['required', 'string', 'max:255'],
            'image_file_name'=>['required', 'file', 'mimes:jpeg,png', 'max:1000'], //kb
            'birth_day'=>['required', 'date', 'before:today'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
 
        //現時点ではidが確定しておらずfilenameを作れないためいったんnullに設定
        $file_name = null;
        
        //transaction処理
        
        try{
            DB::beginTransaction(); 
            //transaction = プログラムが成功するか、失敗したら全部なかったことにするかをするときにtransaction処理をする。
            //画像保存とか一部の処理で失敗したら、ユーザー登録じたいを行わない
            //transactionをはる = 一部でもうまくいかなかったら取引をなしにする(All or Nothing - don't register uncompleted data and terminate the process)
            //user情報つくったら↓のuserに勝手にID情報もいれてくれる
            $file_name = null;
            $birth_day = date('Y-m-d', strtotime("{$request->year}-{$request->month}-{$request->day}"));

            $user = User::create([
                'surname' => $request->surname,
                'given_name' => $request->given_name,
                'image_path' => 'storage/app/public/img/'.$file_name,
                'image_file_name' => $file_name,
                'birth_day'=>$birth_day, 
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            DB::commit();
            
            //TODO；dirをjpg/png対応できるようにしておく。imagefilenameからぬきとる
            
            //IDが確定したので、ファイル名・パスを更新

            $id = $user->id;
            $surname = $user->surname;
            $given_name = $user->given_name;

            $format = $request->file('image_file_name')->getClientOriginalExtension();

            $file_name = "{$id}_{$surname}_{$given_name}.{$format}";
            $image_path = 'public/img/'.$file_name;
            $user->update([
                'image_path' => $image_path,
                'image_file_name' => $file_name,
            ]);
            //画像を保存
            
            $request->file('image_file_name')->storeAs('public',$image_path);

            // トランザクションをコミット
            DB::commit();

        }catch(Exception $e){
            DB::rollback();
            Log::error('エラーが発生しました：' . $e->getMessage());
            throw $e; //親クラスに例外の処理を依頼する（親が勝手にやってくれる）
        }

        event(new Registered($user));

        Auth::login($user);
        return redirect(RouteServiceProvider::HOME);
    }
}
