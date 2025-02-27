<?php

namespace App\Http\Controllers\NotificationController;

use App\Http\Controllers\Controller;
use Broadcast;
use Illuminate\Http\Request;
use App\Notification;
use App\Events\NotificationEvent;
use DB;

class NotificationController extends Controller
{
    public static function sendNotification($purpose, $message, $receiver_id, $type = 'notification', $status = null, $link = null, $created_by = null, $usertype = null)
    {
        $notifId = 0;

        try {
            if ($type == 'notification') {
                $notifId = DB::table('notificationv2')->insertGetId([
                    'message' => $message,
                    'receiver' => $receiver_id,
                    'about' => $purpose,
                    'link' => $link,
                    'usertype' => $usertype,
                    'created_by' => $created_by ?? auth()->id(),
                    'created_at' => now('Asia/Manila'),
                ]);
            }

            $notification = [
                'message' => $message,
                'receiver_id' => $receiver_id, // Dummy receiver ID
                'usertype' => $usertype,
                'created_by' => $created_by ?? auth()->id(), // Assuming an authenticated user
                'purpose' => $purpose,
                'status' => $status,
                'link' => $link,
                'type' => $type,
                'id' => $notifId
            ];

            // Broadcast the dummy notification
            broadcast(new NotificationEvent($notification))->toOthers();
            return response()->json(['status' => 'Notification sent!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'Error occurred, but continuing.']);
        }

    }

    public function sendMessage(Request $request)
    {
        // Example message
        $message = "Hello, this is a simple message!";

        // Broadcast the event
        Broadcast(new NotificationEvent($message));

        return response()->json(['status' => 'Message sent successfully!']);
    }


}
