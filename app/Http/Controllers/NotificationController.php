<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging;
use Exception;

class NotificationController extends Controller
{
    // public function notification()
    // {
    //     $messaging = app(Messaging::class);
    //     $deviceTokens = [
    //         'device_token',
    //     ];

    //     $notification = Notification::create('LDCU Notification', 'Hi name, your status is now okay');

    //     foreach ($deviceTokens as $deviceToken) {
    //         $message = CloudMessage::withTarget('token', $deviceToken)
    //                               ->withNotification($notification);
    //         try {
    //             $response = $messaging->send($message);
    //         } catch (Exception $e) {
    //             echo 'Failed to send notification to token ' . $deviceToken . ': ' . $e->getMessage() . '<br>';
    //         }
    //     }
    //     return 'Notifications sent to that device!';
    // }


    public function notification()
    {
        $messaging = app(Messaging::class);


        $students = DB::table('smsbunker')
            ->join('studinfo', function ($join) {
                $join->on(DB::raw("REPLACE(smsbunker.receiver, '+63', '0')"), '=', 'studinfo.contactno');

            })
            ->join('fcmtokens', function ($join) {
                $join->on('studinfo.id', '=', 'fcmtokens.studid')
                     ->where('fcmtokens.type', 7);
            })
            ->where('smsbunker.receiver', '!=', '+630000000000')
            ->where('smsbunker.pushstatus', 0)
            ->select('smsbunker.id as smsbunker_id', 'smsbunker.message', 'studinfo.id as studinfo_id', 'fcmtokens.fcmtoken', 'fcmtokens.type')
            ->get();


        $parents = DB::table('smsbunker')
            ->join('studinfo', function ($join) {
                $join->on(DB::raw("REPLACE(smsbunker.receiver, '+63', '0')"), '=', 'studinfo.fcontactno')
                    ->orOn(DB::raw("REPLACE(smsbunker.receiver, '+63', '0')"), '=', 'studinfo.mcontactno')
                    ->orOn(DB::raw("REPLACE(smsbunker.receiver, '+63', '0')"), '=', 'studinfo.gcontactno');
            })
            ->join('fcmtokens', function ($join) {
                $join->on('studinfo.id', '=', 'fcmtokens.studid')
                     ->where('fcmtokens.type', 9);
            })
            ->where('smsbunker.receiver', '!=', '+630000000000')
            ->where('smsbunker.pushstatus', 0)
            ->select('smsbunker.id as smsbunker_id', 'smsbunker.message', 'studinfo.id as studinfo_id', 'fcmtokens.fcmtoken', 'fcmtokens.type')
            ->get();


        $parentstapbunker = DB::table('tapbunker')
            ->join('studinfo', function ($join) {
                $join->on(DB::raw("REPLACE(tapbunker.receiver, '+63', '0')"), '=', 'studinfo.fcontactno')
                        ->orOn(DB::raw("REPLACE(tapbunker.receiver, '+63', '0')"), '=', 'studinfo.mcontactno')
                        ->orOn(DB::raw("REPLACE(tapbunker.receiver, '+63', '0')"), '=', 'studinfo.gcontactno');

            })
            ->join('fcmtokens', function ($join) {
                $join->on('studinfo.id', '=', 'fcmtokens.studid')
                     ->where('fcmtokens.type', 9);
            })
            ->where('tapbunker.receiver', '!=', '+630000000000')
            ->where('tapbunker.pushstatus', 0)
            ->select('tapbunker.id as tapbunker_id', 'tapbunker.message', 'studinfo.id as studinfo_id', 'fcmtokens.fcmtoken', 'fcmtokens.type')
            ->get();



        $studenttextsblast = DB::table('smsbunkertextblast')
            ->join('studinfo', function ($join) {
                $join->on(DB::raw("REPLACE(smsbunkertextblast.receiver, '+63', '0')"), '=', 'studinfo.contactno');

            })
            ->join('fcmtokens', function ($join) {
                $join->on('studinfo.id', '=', 'fcmtokens.studid')
                     ->where('fcmtokens.type', 7);
            })
            ->where('smsbunkertextblast.receiver', '!=', '+630000000000')
            ->where('smsbunkertextblast.pushstatus', 0)
            ->select('smsbunkertextblast.id as smsbunkertextblast_id', 'smsbunkertextblast.message', 'studinfo.id as studinfo_id', 'fcmtokens.fcmtoken', 'fcmtokens.type')
            ->get();


        $parenttextsblast = DB::table('smsbunkertextblast')
            ->join('studinfo', function ($join) {
                $join->on(DB::raw("REPLACE(smsbunkertextblast.receiver, '+63', '0')"), '=', 'studinfo.fcontactno')
                        ->orOn(DB::raw("REPLACE(smsbunkertextblast.receiver, '+63', '0')"), '=', 'studinfo.mcontactno')
                        ->orOn(DB::raw("REPLACE(smsbunkertextblast.receiver, '+63', '0')"), '=', 'studinfo.gcontactno');

            })
            ->join('fcmtokens', function ($join) {
                $join->on('studinfo.id', '=', 'fcmtokens.studid')
                        ->where('fcmtokens.type', 9);
            })
            ->where('smsbunkertextblast.receiver', '!=', '+630000000000')
            ->where('smsbunkertextblast.pushstatus', 0)
            ->select('smsbunkertextblast.id as smsbunkertextblast_id', 'smsbunkertextblast.message', 'studinfo.id as studinfo_id', 'fcmtokens.fcmtoken', 'fcmtokens.type')
            ->get();




        $sentNotifications = [];

        foreach ($parents as $parent) {
            $notificationMessage = $parent->message;

            $notification = Notification::create('', $notificationMessage);
            $message = CloudMessage::withTarget('token', $parent->fcmtoken)
                                    ->withNotification($notification);

            try {
                $response = $messaging->send($message);

                DB::table('smsbunker')
                    ->where('id', $parent->smsbunker_id)
                    ->update(['pushstatus' => 1]);


                $sentNotifications[] = [
                    'studid' => $parent->studinfo_id,
                    'message' => $parent->message,
                    'fcmtoken' => $parent->fcmtoken,
                    'type' => $parent->type,
                ];
            } catch (Exception $e) {
                $error = $e->getMessage();


                if ($error) {
                   DB::table('fcmtokens')
                        ->where('fcmtoken', $parent->fcmtoken)
                        ->delete();

                    // echo 'Invalid FCM token: ' . $parent->fcmtoken . ': ' . $error . '';
                } else {
                    echo 'Failed to send notification to token ' . $parent->fcmtoken . ': ' . $error . '';
                }
            }
        }

        foreach ($students as $student) {
            $notificationMessage = $student->message;

            $notification = Notification::create('', $notificationMessage);
            $message = CloudMessage::withTarget('token', $student->fcmtoken)
                                    ->withNotification($notification);

            try {
                $response = $messaging->send($message);

                DB::table('smsbunker')
                    ->where('id', $student->smsbunker_id)
                    ->update(['pushstatus' => 1]);

                $sentNotifications[] = [
                    'studid' => $student->studinfo_id,
                    'message' => $student->message,
                    'fcmtoken' => $student->fcmtoken,
                    'type' => $student->type,
                ];
            } catch (Exception $e) {
                $error = $e->getMessage();


                if($error){

                    DB::table('fcmtokens')
                        ->where('fcmtoken', $student->fcmtoken)
                        ->delete();


                    // echo 'Invalid FCM token: ' . $student->fcmtoken . ': ' . $error . '';
                } else {
                    echo 'Failed to send notification to token and also token is not deleted' . $student->fcmtoken . ': ' . $error . '';
                }
            }
        }

        foreach ($parentstapbunker as $parenttapbunker) {
            $notificationMessage = $parenttapbunker->message;

            $notification = Notification::create('', $notificationMessage);
            $message = CloudMessage::withTarget('token', $parenttapbunker->fcmtoken)
                                    ->withNotification($notification);

            try {
                $response = $messaging->send($message);

                DB::table('tapbunker')
                    ->where('id', $parenttapbunker->tapbunker_id)
                    ->update(['pushstatus' => 1]);


                $sentNotifications[] = [
                    'studid' => $parenttapbunker->studinfo_id,
                    'message' => $parenttapbunker->message,
                    'fcmtoken' => $parenttapbunker->fcmtoken,
                    'type' => $parenttapbunker->type,
                ];
            } catch (Exception $e) {
                $error = $e->getMessage();


                if($error){
                    DB::table('fcmtokens')
                        ->where('fcmtoken', $parenttapbunker->fcmtoken)
                        ->delete();

                    // echo 'Invalid FCM token: ' . $parenttapbunker->fcmtoken . ': ' . $error . '';
                } else {
                    echo 'Failed to send notification to token ' . $parenttapbunker->fcmtoken . ': ' . $error . '';
                }
            }
        }

        foreach ($studenttextsblast as $studenttextblast) {
            $notificationMessage = $studenttextblast->message;

            $notification = Notification::create('', $notificationMessage);
            $message = CloudMessage::withTarget('token', $studenttextblast->fcmtoken)
                                    ->withNotification($notification);

            try {
                $response = $messaging->send($message);

                DB::table('smsbunkertextblast')
                    ->where('id', $studenttextblast->smsbunkertextblast_id)
                    ->update(['pushstatus' => 1]);


                $sentNotifications[] = [
                    'studid' => $studenttextblast->studinfo_id,
                    'message' => $studenttextblast->message,
                    'fcmtoken' => $studenttextblast->fcmtoken,
                    'type' => $studenttextblast->type,
                ];
            } catch (Exception $e) {
                $error = $e->getMessage();


                if($error){
                    DB::table('fcmtokens')
                        ->where('fcmtoken', $studenttextblast->fcmtoken)
                        ->delete();

                    echo 'Invalid FCM token: ' . $studenttextblast->fcmtoken . ': ' . $error . '';
                } else {
                    echo 'Failed to send notification to token ' . $studenttextblast->fcmtoken . ': ' . $error . '';
                }
            }
        }

        foreach ($parenttextsblast as $parenttextblast) {
            $notificationMessage = $parenttextblast->message;

            $notification = Notification::create('', $notificationMessage);
            $message = CloudMessage::withTarget('token', $parenttextblast->fcmtoken)
                                    ->withNotification($notification);

            try {
                $response = $messaging->send($message);

                DB::table('smsbunkertextblast')
                    ->where('id', $parenttextblast->smsbunkertextblast_id)
                    ->update(['pushstatus' => 1]);


                $sentNotifications[] = [
                    'studid' => $parenttextblast->studinfo_id,
                    'message' => $parenttextblast->message,
                    'fcmtoken' => $parenttextblast->fcmtoken,
                    'type' => $parenttextblast->type,
                ];
            } catch (Exception $e) {
                $error = $e->getMessage();


                if($error){
                    DB::table('fcmtokens')
                        ->where('fcmtoken', $parenttextblast->fcmtoken)
                        ->delete();

                    echo 'Invalid FCM token: ' . $parenttextblast->fcmtoken . ': ' . $error . '';
                } else {
                    echo 'Failed to send notification to token ' . $parenttextblast->fcmtoken . ': ' . $error . '';
                }
            }
        }


        return response()->json([
            'message' => 'Notifications sent successfully to type 9 and type 7!',
            'notifications' => $sentNotifications
        ]);
    }










    public function taphistory(Request $request)
    {
		$studid = $request->get('studid');


		$taphistory = DB::table('taphistory')
            ->where('studid', $studid)
            ->get();

        return $taphistory;

    }

    public function update_pushstatus(Request $request)
	{
		$studid = $request->get('studid');
		$id = $request->get('id');
		$pushstatus = $request->get('pushstatus');
		$message = $request->get('message');

		$updated = DB::table('taphistory')
			->where('studid', $studid)
			->where('id', $id)
			->update([
				'pushstatus' => $pushstatus,
				'message' => $message
			]);

		return response()->json(['success' => $updated > 0]);
	}

    // public function api_save_fcmtoken(Request $request)
    // {

    //     $studid = $request->input('studid');
    //     $type = $request->input('type');
    //     $fcmtoken = $request->input('fcmtoken');

    //     if (empty($studid) || empty($fcmtoken)) {
    //         return response()->json(['message' => 'Invalid input.'], 400);
    //     }
    //     $exists = DB::table('fcmtokens')
    //                 ->where('studid', $studid)
    //                 ->where('type', $type)
    //                 ->where('fcmtoken', $fcmtoken)
    //                 ->exists();

    //     if ($exists) {
    //         return response()->json(['message' => 'FCM token ,type, and studid already exist.'], 200);
    //     }

    //     $inserted = DB::table('fcmtokens')->insert([
    //         'studid' => $studid,
    //         'type' => $type,
    //         'fcmtoken' => $fcmtoken
    //     ]);

    //     if ($inserted) {
    //         return response()->json(['message' => 'FCM token saved successfully.']);
    //     } else {
    //         return response()->json(['message' => 'Failed to save FCM token.'], 500);
    //     }
    // }

    public function api_save_fcmtoken(Request $request)
    {
        $studid = $request->input('studid');
        $type = $request->input('type');
        $fcmtoken = $request->input('fcmtoken');

        if (empty($studid) || empty($fcmtoken)) {
            return response()->json(['message' => 'Invalid input.'], 400);
        }


        $existingToken = DB::table('fcmtokens')
            ->where('fcmtoken', $fcmtoken)
            ->first();

        if ($existingToken) {

            if ($existingToken->studid === $studid && $existingToken->type === $type) {
                return response()->json(['message' => 'FCM token, type, and studid already exist.'], 200);
            } else {

                $updated = DB::table('fcmtokens')
                    ->where('fcmtoken', $fcmtoken)
                    ->update([
                        'studid' => $studid,
                        'type' => $type,
                    ]);

                if ($updated) {
                    return response()->json(['message' => 'FCM token updated successfully with new studid and type.']);
                } else {
                    return response()->json(['message' => 'Failed to update FCM token. FCM token, type, and studid already exist.'], 500);
                }
            }
        }


        $inserted = DB::table('fcmtokens')->insert([
            'studid' => $studid,
            'type' => $type,
            'fcmtoken' => $fcmtoken
        ]);

        if ($inserted) {
            return response()->json(['message' => 'FCM token saved successfully.']);
        } else {
            return response()->json(['message' => 'Failed to save FCM token.'], 500);
        }
    }








    public function deleteFcmToken(Request $request)
    {
        $studid = $request->input('studid');
        $type = $request->input('type');
        $fcmtoken = $request->input('fcmtoken');

        DB::table('fcmtokens')
            ->where('studid', $studid)
            ->where('type', $type)
            ->where('fcmtoken', $fcmtoken)
            ->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'FCM token deletion processed',
        ], 200);
    }
}

