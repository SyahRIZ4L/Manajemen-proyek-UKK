<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DesignerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function panel()
    {
        return redirect()->route('member.dashboard');
    }

    public function getCards()
    {
        return response()->json(['cards' => []]);
    }

    public function getMyCards()
    {
        return response()->json(['cards' => []]);
    }

    public function getDashboardStats()
    {
        return response()->json(['stats' => []]);
    }

    public function getCardDetails($cardId)
    {
        return response()->json(['card' => null]);
    }

    public function acceptCard($cardId)
    {
        return response()->json(['success' => false]);
    }

    public function startCard($cardId)
    {
        return response()->json(['success' => false]);
    }

    public function updateCardStatus($cardId)
    {
        return response()->json(['success' => false]);
    }

    public function submitCardToTeamLead($cardId)
    {
        return response()->json(['success' => false]);
    }

    public function submitCard($cardId)
    {
        return response()->json(['success' => false]);
    }

    public function uploadDesignFiles($cardId)
    {
        return response()->json(['success' => false]);
    }

    public function toggleTimer($cardId)
    {
        return response()->json(['success' => false]);
    }

    public function addCardComment($cardId)
    {
        return response()->json(['success' => false]);
    }

    public function myTasks()
    {
        return redirect()->route('member.my-cards');
    }

    public function comments()
    {
        return redirect()->route('member.dashboard');
    }

    public function profile()
    {
        return redirect()->route('profile.show');
    }

    public function getDesignerStatistics()
    {
        return response()->json(['stats' => []]);
    }

    public function getPanelDesignAssets()
    {
        return response()->json(['assets' => []]);
    }

    public function getDesignProjects()
    {
        return response()->json(['projects' => []]);
    }

    public function getGalleryItems()
    {
        return response()->json(['gallery' => []]);
    }

    public function getClientFeedback()
    {
        return response()->json(['feedback' => []]);
    }

    public function getDesignerActivities()
    {
        return response()->json(['activities' => []]);
    }
}
