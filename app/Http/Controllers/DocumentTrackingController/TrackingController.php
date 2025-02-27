<?php

namespace App\Http\Controllers\DocumentTrackingController;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;

class TrackingController extends Controller
{
    public function getalldoctype()
    {
        return DB::table('document_type')
            ->where('deleted', 0)
            ->select(
                'document_type.*',
                'name AS text'
            )
            ->get();
    }

    public function store_document(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'document_type_id' => 'required|integer',
            'document_name' => 'nullable|string',
            'document_issuedby' => 'required|integer',
            // 'document_createddate' => 'required|string',
            // 'document_remarks' => 'nullable|string',
            'document_signee' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // check duplicate
        $isExist = DB::table('document_tracking')
            ->where('document_name', $request->document_name)
            ->where('document_deleted', 0)
            ->exists();

        if ($isExist) {
            return response()->json(['status' => 'error', 'message' => 'Document name already exists.'], 200);
        }

        $resultId = DB::table('document_tracking')->insertGetId([
            'document_type_id' => $request->document_type_id,
            'document_name' => $request->document_name,
            'document_issuedby' => $request->document_issuedby,
            // 'document_createddate' => $request->document_createddate,
            'document_remarks' => $request->document_remarks,
            'document_viewers' => $request->document_viewers,
            'document_status' => 'open',
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila')
        ]);


        $files = $request->file('files');
        $row = 0;
        // Process and store each file
        foreach ($files as $file) {
            // Generate a new file name (e.g., using time() and original extension)
            $newFileName = Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss') . '-' . $row . '.' . $file->getClientOriginalExtension();

            // Move the file to the desired storage location
            $destinationPath = public_path('DocumentTrackingPictures/');
            $file->move($destinationPath, $newFileName);

            // Save the file path in the database
            $path = '/DocumentTrackingPictures/' . $newFileName;
            DB::table('document_tracking_picture')->insert([
                'document_tracking_id' => $resultId,
                'picurl' => $path,
                'created_at' => now('Asia/Manila'),
                'updated_at' => now('Asia/Manila')
            ]);

            $row += 1;
        }

        if ($resultId) {
            $document_signee = json_decode($request->input('document_signee'), true);
            if (count($document_signee) > 0) {
                foreach ($document_signee as $signee) {
                    $teacher = DB::table('teacher')->where('userid', $signee)->first();
                    DB::table('document_signee')->insert([
                        'document_tracking_id' => $resultId,
                        'userid' => $signee,
                        'status' => 'Pending',
                        'name' => $teacher->lastname . ',' . $teacher->firstname . $teacher->middlename,
                        'created_at' => now('Asia/Manila'),
                        'updated_at' => now('Asia/Manila'),
                    ]);
                }
            }

            $first_signee = DB::table('document_signee')
                ->where('document_tracking_id', $resultId)
                ->where('deleted', 0)
                ->first();

            DB::table('document_tracking_history')->insert([
                'document_tracking_id' => $resultId,
                'forwarded_by' => $request->document_issuedby,
                'forwarded_to' => $first_signee->userid,
                'forwarddate' => now('Asia/Manila'),
                'status' => 'Creator',
                'received' => 1,
                'receiveddate' => now('Asia/Manila'),
                'created_at' => now('Asia/Manila'),
                'updated_at' => now('Asia/Manila')
            ]);

            DB::table('document_tracking_history')->insertGetId([
                'document_tracking_id' => $resultId,
                'forwarded_by' => $request->document_issuedby,
                'forwarded_to' => $first_signee->userid,
                // 'forwarddate' => $request->forwarddate,
                'status' => 'Pending',
                'created_at' => now('Asia/Manila'),
                'updated_at' => now('Asia/Manila'),
            ]);

        }

        return response()->json([
            'status' => 'success',
            'message' => 'Added Successfully!'
        ]);

    }

    public function getAllTeacherByID(Request $request)
    {
        $jsonSignee = $request->input('signee');
        $signee = json_decode($jsonSignee, true);

        return DB::table('teacher')
            ->whereIn('teacher.userid', $signee)
            ->join('usertype', 'teacher.usertypeid', 'usertype.id')
            ->select(
                'teacher.lastname',
                'teacher.firstname',
                'usertype.utype'
            )
            ->get();
    }

    public function getAllDocuments()
    {
        $allDocuments = [];
        $openDocument = [];
        $closeDocument = [];
        $documents = DB::table('document_tracking')
            ->where('document_deleted', 0)
            ->where('document_type.deleted', 0)
            ->join('document_type', 'document_tracking.document_type_id', '=', 'document_type.id')
            ->join('teacher', 'document_tracking.document_issuedby', '=', 'teacher.userid')
            ->select(
                'document_tracking.*',
                'document_type.name AS doctype',
                DB::raw('DATE_FORMAT(document_tracking.created_at, "%M %e, %Y") as formatted_created_at'),
                DB::raw('CONCAT(teacher.lastname, " ", teacher.firstname) AS issuedby_name')
            )
            ->get();

        foreach ($documents as $docs) {
            $resultpics = DB::table('document_tracking_picture')
                ->where('deleted', 0)
                ->where('document_tracking_id', $docs->id)
                ->select(
                    'document_tracking_picture.picurl',
                )
                ->get();

            $resultHistory = DB::table('document_tracking_history')
                ->where('document_tracking_history.deleted', 0)
                ->where('document_tracking_history.document_tracking_id', $docs->id) // Changed 'document_tracking_id' to 'document_tracking_history.document_tracking_id'
                ->leftJoin('teacher', 'document_tracking_history.forwarded_by', '=', 'teacher.userid')
                ->leftJoin('teacher as guro', 'document_tracking_history.forwarded_to', '=', 'guro.userid')
                ->select(
                    'document_tracking_history.*',
                    DB::raw('CONCAT(teacher.lastname, " ", teacher.firstname) AS forwarded_by_name'),
                    DB::raw('CONCAT(guro.lastname, " ", guro.firstname) AS forwarded_to_name'),
                    DB::raw('DATE_FORMAT(document_tracking_history.forwarddate, "%M %e, %Y") as formatted_forwarddate'),
                    DB::raw('DATE_FORMAT(document_tracking_history.receiveddate, "%M %e, %Y") as formatted_receiveddate'),
                )
                ->get();

            $docs->picurl = $resultpics;
            $docs->history = $resultHistory;

            $isvalidSignee = DB::table('document_signee')
                ->where('document_tracking_id', $docs->id)
                ->where('userid', auth()->user()->id)
                ->exists();

            if ($isvalidSignee || in_array(auth()->user()->id, explode(",", $docs->document_viewers)) || $docs->document_issuedby == auth()->user()->id) {

                if (in_array(auth()->user()->id, explode(",", $docs->document_viewers)) && !$isvalidSignee) {
                    $docs->privelege = 'viewer';
                } else if ($docs->document_issuedby == auth()->user()->id) {
                    $docs->privelege = 'creator';
                } else {
                    $docs->privelege = 'editor';
                }

                $allDocuments[] = $docs;
                if ($docs->document_status == 'open' || $docs->document_status == "") {
                    $openDocument[] = $docs;
                } else if ($docs->document_status == 'close') {
                    $closeDocument[] = $docs;
                }
            }

        }
        // dd($openDocument, $allDocuments, $closeDocument);
        return response()->json([
            'alldocs' => $allDocuments,
            'opendocs' => $openDocument,
            'closedocs' => $closeDocument
        ]);
    }

    public function getDocumentTracking(Request $request)
    {
        $document = DB::table('document_tracking')
            ->where('document_deleted', 0)
            ->where('document_type.deleted', 0)
            ->where('document_tracking.id', $request->id)
            ->join('document_type', 'document_tracking.document_type_id', '=', 'document_type.id')
            ->join('teacher', 'document_tracking.document_issuedby', '=', 'teacher.userid')
            ->select(
                'document_tracking.*',
                'document_type.name AS doctype',
                DB::raw('DATE_FORMAT(document_tracking.created_at, "%M %e, %Y") as formatted_created_at'),
                DB::raw('CONCAT(teacher.lastname, " ", teacher.firstname) AS issuedby_name')
            )
            ->first();

        $resultpics = DB::table('document_tracking_picture')
            ->where('deleted', 0)
            ->where('document_tracking_id', $document->id)
            ->select(
                'document_tracking_picture.picurl',
            )
            ->get();

        $resultSignee = DB::table('document_signee')
            ->where('document_tracking_id', $document->id)
            ->where('document_signee.deleted', 0)
            ->join('teacher', 'document_signee.userid', '=', 'teacher.userid')
            ->join('usertype', 'teacher.usertypeid', '=', 'usertype.id')
            ->select(
                'document_signee.*',
                'usertype.utype',
                DB::raw('CONCAT(teacher.lastname, " ", teacher.firstname) AS name')
            )
            ->groupBy('document_signee.userid')  // Group by userid to ensure uniqueness
            ->get();


        $signee_user = DB::table('document_signee')
            ->where('document_tracking_id', $document->id)
            ->where('userid', auth()->user()->id)
            ->first();



        $document->picurl = $resultpics;
        $document->signees = $resultSignee->pluck('userid');
        $document->signeedetails = $resultSignee;
        $document->signee_user = $signee_user;


        // dd($document);

        return response()->json($document);

    }

    public function receiveDocument(Request $request)
    {
        $currentSignee = DB::table('document_tracking_history')
            ->where('forwarded_to', auth()->user()->id)
            ->where('document_tracking_id', $request->trackingid)
            ->first();

        if (!$currentSignee) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not the current Signee!',
            ], 200);
        }


        $signee_id = DB::table('document_signee')
            ->where('document_tracking_id', $request->trackingid)
            ->where('userid', auth()->user()->id)
            ->first();

        if (!$signee_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'You\'re Not a signee!',
            ], 200);
        }

        DB::table('document_tracking_history')
            ->where('document_tracking_id', $request->trackingid)
            ->where('forwarded_to', auth()->user()->id)
            ->where('status', '!=', 'Creator')
            ->update([
                'status' => 'Onhand',
                'received' => 1,
                'remarks' => $request->remarks,
                'receiveddate' => now('Asia/Manila'),
                'updated_at' => now('Asia/Manila'),
            ]);

        DB::table('document_signee')
            ->where('document_tracking_id', $request->trackingid)
            ->where('userid', auth()->user()->id)
            ->update([
                'status' => 'Onhand',
                'remarks' => 'sample remarks',
            ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Received Successfully!',
        ], 200);

    }

    public function forwardDocument(Request $request)
    {

        $isdone = DB::table('document_tracking')
            ->where('id', $request->trackingid)
            ->where('document_status', 'close')
            ->exists();

        if ($isdone) {
            return response()->json([
                'status' => 'error',
                'message' => 'This Document Tracking is Close!',
            ], 200);
        }

        $signee_id = DB::table('document_signee')
            ->where('document_tracking_id', $request->trackingid)
            ->where('userid', auth()->user()->id)
            ->first();

        if (!$signee_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'You\'re Not a signee!',
            ], 200);
        }

        DB::table('document_tracking_history')
            ->where('document_tracking_id', $request->trackingid)
            ->where('forwarded_to', auth()->user()->id)
            ->where('status', '!=', 'Creator')
            ->update([
                'status' => 'Forwarded',
                'remarks' => $request->remarks,
                'forwarddate' => now('Asia/Manila'),
                'updated_at' => now('Asia/Manila'),
            ]);

        DB::table('document_signee')
            ->where('document_tracking_id', $request->trackingid)
            ->where('userid', auth()->user()->id)
            ->update([
                'status' => 'Forwarded',
                'remarks' => 'sample remarks',
            ]);

        $next_signee = DB::table('document_signee')
            ->where('document_tracking_id', $request->trackingid)
            // ->where('status', null)
            ->whereIn('status', [null, 'Pending', " "])
            ->first();

        if ($next_signee) {
            if ($request->forwardedto) {
                $resultid = DB::table('document_tracking_history')->insertGetId([
                    'document_tracking_id' => $request->trackingid,
                    'forwarded_by' => auth()->user()->id,
                    'forwarded_to' => $request->forwardedto,
                    'status' => 'Pending',
                    'created_at' => now('Asia/Manila'),
                    'updated_at' => now('Asia/Manila'),
                ]);

                if ($resultid) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Document Successfully Signed and Forwarded to:',
                        'next_signee' => $next_signee->name
                    ], 200);
                }
            } else {
                $resultid = DB::table('document_tracking_history')->insertGetId([
                    'document_tracking_id' => $request->trackingid,
                    'forwarded_by' => auth()->user()->id,
                    'forwarded_to' => $next_signee->userid,
                    'status' => 'Pending',
                    'forwarddate' => now('Asia/Manila'),
                    'created_at' => now('Asia/Manila'),
                    'updated_at' => now('Asia/Manila'),
                ]);

                if ($resultid) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Document Successfully Signed and Forwarded to:',
                        'next_signee' => $next_signee->name
                    ], 200);
                }
            }

        } else {
            $rejectedSignee = DB::table('document_signee')
                ->where('document_tracking_id', $request->trackingid)
                ->where('status', "Rejected")
                ->first();

            if (!$rejectedSignee) {
                DB::table('document_tracking')
                    ->where('id', $request->trackingid)
                    ->update([
                        'document_status' => 'close'
                    ]);

                return response()->json([
                    'status' => 'info',
                    'message' => 'Document Closed Successfully!',
                ], 200);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Nothing to Forward To!',
                ], 200);
            }
        }

    }

    public function getAvailableSignee(Request $request)
    {
        $listofSignee = [];
        $signees = DB::table('document_signee')
            ->where('document_tracking_id', $request->trackingid)
            ->whereIn('status', [null, 'Pending', " "])
            ->where('userid', '!=', auth()->user()->id)
            ->select(
                'document_signee.userid as id',
                'document_signee.name as text'
            )
            ->get();

        foreach ($signees as $key => $value) {
            $signeeNext = DB::table('document_tracking_history')
                ->where(function ($query) use ($value) {
                    $query->where('forwarded_by', $value->id)
                        ->orWhere('forwarded_to', $value->id);
                })
                ->where('document_tracking_id', $request->trackingid)
                ->first();

            if (!$signeeNext) {
                $listofSignee[] = $value;
            }
        }

        return response()->json($listofSignee);
    }


    public function rejectDocument(Request $request)
    {

        $currentSignee = DB::table('document_tracking_history')
            ->where('forwarded_to', auth()->user()->id)
            ->where('document_tracking_id', $request->trackingid)
            ->first();

        if (!$currentSignee) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not the current Signee!',
            ], 200);
        }



        $isdone = DB::table('document_tracking')
            ->where('id', $request->trackingid)
            ->where('document_status', 'close')
            ->exists();

        if ($isdone) {
            return response()->json([
                'status' => 'error',
                'message' => 'This Document Tracking is Close!',
            ], 200);
        }

        $signee_id = DB::table('document_signee')
            ->where('document_tracking_id', $request->trackingid)
            ->where('userid', auth()->user()->id)
            ->first();

        if (!$signee_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'You\'re Not a signee!',
            ], 200);
        }

        DB::table('document_tracking_history')
            ->where('document_tracking_id', $request->trackingid)
            ->where('forwarded_to', auth()->user()->id)
            ->where('status', '!=', 'Creator')
            ->update([
                'status' => 'Rejected',
                'remarks' => $request->remarks,
                'forwarddate' => now('Asia/Manila'),
                'updated_at' => now('Asia/Manila'),
            ]);

        DB::table('document_signee')
            ->where('document_tracking_id', $request->trackingid)
            ->where('userid', auth()->user()->id)
            ->update([
                'status' => 'Rejected',
                'remarks' => $request->remarks,
            ]);

        if ($request->forwardedto) {

            $signeeNext = DB::table('document_tracking_history')
                ->where(function ($query) use ($request) {
                    $query->where('forwarded_by', $request->forwardedto)
                        ->orWhere('forwarded_to', $request->forwardedto);
                })
                ->where('document_tracking_id', $request->trackingid)
                ->first();


            if ($signeeNext) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Next Signee has already transaction!',
                    'next_signee' => $signeeNext,
                ], 200);
            }


            // $nextSignee = DB::table('document_signee')
            //     ->where('userid', $request->forwardedto)
            //     ->where('document_tracking_id', $request->trackingid)
            //     ->value('name'); // Retrieve only the 'name' column



            $resultid = DB::table('document_tracking_history')->insertGetId([
                'document_tracking_id' => $request->trackingid,
                'forwarded_by' => auth()->user()->id,
                'forwarded_to' => $request->forwardedto,
                'status' => 'Pending',
                'remarks' => $request->remarks,
                'created_at' => now('Asia/Manila'),
                'updated_at' => now('Asia/Manila'),
            ]);

            if ($resultid) {
                // Retrieve the next signee's name based on the forwardedto and trackingid
                $nextSignee = DB::table('document_signee')
                    ->where('userid', $request->forwardedto)
                    ->where('document_tracking_id', $request->trackingid)
                    ->value('name'); // Retrieve only the 'name' column

                if ($nextSignee) {
                    // If the next signee's name is found, return a success response
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Document has been Rejected and is Forwarded to:',
                        'next_signee' => $nextSignee,
                    ], 200);
                } else {
                    // If the next signee's name is not found, return an error response
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Next signee not found for the specified user and tracking ID.',
                    ], 404); // Assuming 404 is appropriate for a resource not found situation
                }

            }

        } else {
            // $resultid = DB::table('document_tracking_history')->insertGetId([
            //     'document_tracking_id' => $request->trackingid,
            //     'forwarded_by' => auth()->user()->id,
            //     'forwarded_to' => auth()->user()->id,
            //     'status' => 'Rejected',
            //     'remarks' => $request->remarks,
            //     'created_at' => now('Asia/Manila'),
            //     'updated_at' => now('Asia/Manila'),
            // ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Document has been Rejected!',
            ], 200);
        }
    }

    public function closeDocument(Request $request)
    {
        DB::table('document_tracking')
            ->where('id', $request->id)
            ->where('document_deleted', 0)
            ->update([
                'document_status' => 'close'
            ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Document Closed Successfully !',
        ], 200);
    }

}
