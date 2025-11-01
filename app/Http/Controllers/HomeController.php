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
                return view('dashboard.admin', compact('user'));
            case 'Team_Lead':
                return view('dashboard.teamlead', compact('user'));
            case 'Developer':
                return view('dashboard.developer', compact('user'));
            case 'Designer':
                return view('dashboard.designer', compact('user'));
            case 'Member':
            default:
                return view('dashboard.member', compact('user'));
        }
    }
}
