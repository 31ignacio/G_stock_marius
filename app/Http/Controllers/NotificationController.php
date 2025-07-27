<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    //

    public function getNotifications()
    {
        $user = auth()->user();

        return response()->json([
            'count' => $user->unreadNotifications->count(),
            'notifications' => $user->unreadNotifications
        ]);
    }

    public function delete($id)
{
    $notification = auth()->user()->notifications()->findOrFail($id);
    $notification->delete();

    return response()->json(['success' => true]);
}


}
