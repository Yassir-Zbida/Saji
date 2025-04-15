<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the notifications.
     */
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(10);
        return view('notifications.index', compact('notifications'));
    }
    
    /**
     * Display the specified notification.
     */
    public function show($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        
        // Mark as read if unread
        if (!$notification->read_at) {
            $notification->markAsRead();
        }
        
        // Redirect based on notification type
        if (isset($notification->data['url'])) {
            return redirect($notification->data['url']);
        }
        
        return redirect()->route('notifications.index');
    }
    
    /**
     * Mark a notification as read.
     */
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        return back()->with('success', 'Notification marked as read.');
    }
    
    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        
        return back()->with('success', 'All notifications marked as read.');
    }
    
    /**
     * Delete a notification.
     */
    public function destroy($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->delete();
        
        return back()->with('success', 'Notification deleted.');
    }
    
    /**
     * Delete all notifications.
     */
    public function destroyAll()
    {
        Auth::user()->notifications()->delete();
        
        return back()->with('success', 'All notifications deleted.');
    }
    
    /**
     * Get unread notifications count (for AJAX requests).
     */
    public function getUnreadCount()
    {
        $count = Auth::user()->unreadNotifications->count();
        
        return response()->json(['count' => $count]);
    }
    
    /**
     * Get recent notifications (for AJAX requests).
     */
    public function getRecent()
    {
        $notifications = Auth::user()->notifications()->take(5)->get();
        
        return response()->json([
            'notifications' => $notifications,
            'count' => Auth::user()->unreadNotifications->count()
        ]);
    }
}