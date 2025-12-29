<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tickets;

class ProfileController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
         $allticket = Tickets::where('user_id', auth()->id())
            ->count();
            $overdueticket = Tickets::where('user_id', auth()->id())
            ->where('status', 'Overdue')
            ->count();
            $openticket = Tickets::where('user_id', auth()->id())
            ->where('status', 'Open')
            ->count();

        return view('pages.profile',compact('allticket','user','overdueticket','openticket'));
    }
}
