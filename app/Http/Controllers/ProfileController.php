<?php

namespace App\Http\Controllers;

use DB;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use App\Lib\MyFunction;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{

    /**
     * Display the user's profile form.
     */
    public function info(User $user, $id){
        $user=User::findOrFail($id);
        return view('profile.info', compact('user'));
    }

    public function edit(Request $request, $id): View
    {
        $user = User::findOrFail($id);
        $date = explode("-", $user->birth_day);
        $date['userYear'] = (int)$date[0];
        $date['userMonth'] = (int)$date[1];
        $date['userDay'] = (int)$date[2];
        return view('profile.edit', compact('user', 'date'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request, User $user)
    {
        $user = User::find($request->id);
        $rules = [
            'surname' => ['required', 'string', 'max:255'],
            'given_name' => ['required', 'string', 'max:255'],
            'image_file_name' => ['nullable', 'file', 'mimes:jpeg,png', 'max:1000'],
            'phone' => ['required', 'string', 'max:30', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],                       
        ];
        
    
        
        $validated = $request->validate($rules);
        

        $rules['birth_day'] = ['required', 'date', 'date_format:Y-m-d', 'before:today'];
        $validated['birth_day'] = date('Y-m-d', strtotime("{$request->year}-{$request->month}-{$request->day}"));        
        //dd($validated);
        try{
            DB::beginTransaction(); 
            //transaction処理
            $user->update($validated);
            //DB::commit();
            
            if ($request->hasFile('image_file_name')){
                dd('yes');
                $format = $request->file('image_file_name')->getClientOriginalExtension();
                $file_name = "{$request->id}_{$request->surname}_{$request->given_name}.{$format}";
                $request->file('image_file_name')->storeAs('public/img', $file_name);
                $user->update([
                    'image_file_name' => $file_name,
                ]); 
            }
            DB::commit();
            
        }catch(Exception $e){

            DB::rollback();
            Log::error('エラーが発生しました：' . $e->getMessage());
            throw $e; //親クラスに例外の処理を依頼する（親が勝手にやってくれる）
        }
        
        $request->session()->flash('message', '更新しました');
        return back();
        
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request, User $user)
    {
        //$user = User::findOrFail($id);
        $user->delete();
        $request->session()->flash('message', '削除しました');
        return redirect('show');
    }
}
