<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Show user profile
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        
        $data = [
            'title' => 'Профиль',
            'user' => $user,
        ];
        
        return view('panel.profile', $data);
    }
    
    /**
     * Update user data
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateData(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        $user = Auth::user();
    
        $user->name = $request->input('name');
        $user->save();
        
        return back()->with('success', 'Данные сохранены');
    }
    
    /**
     * Update user password
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed|different:current_password',
            'new_password_confirmation' => 'required|string|min:8',
        ]);
    
        $user = Auth::user();
        $current_password = $request->input('current_password');
        $new_password = $request->input('new_password');
        
        if(Hash::check($current_password, $user->password)){
            $user->password = Hash::make($new_password);
            $user->save();
            
            return back()->with('success', 'Пароль сохранен');
        }
        
        return back()->with('danger', 'Текущий пароль указан неверно')->withErrors(['current_password' => 'Текущий пароль указан неверно']);
    }
}
