<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userRole = $user->role ?? 'Member';

        switch ($userRole) {
            case 'Project_Admin':
                return redirect()->route('admin.panel');
            case 'Team_Lead':
                return redirect()->route('teamlead.panel');
            case 'Developer':
                return redirect()->route('developer.panel');
            case 'Designer':
                return view('dashboard.designer', compact('user'));
            case 'Member':
            default:
                return view('dashboard.member', compact('user'));
        }
    }
}
