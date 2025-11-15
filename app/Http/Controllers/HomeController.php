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
            case 'Admin':
            case 'Project_Admin':
                return redirect()->route('admin.panel');
            case 'Team_Lead':
                return redirect()->route('teamlead.panel');
            case 'Developer':
                return redirect()->route('developer.panel');
            case 'Designer':
                return redirect()->route('designer.panel');
            case 'Member':
            default:
                return view('dashboard.member', compact('user'));
        }
    }
}
