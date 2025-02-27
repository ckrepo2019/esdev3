<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use File;
use Session;
class SchoolFilesController extends Controller
{

    public function index(Request $request)
    {
        // return Session::get('currentPortal');
        $audiencetypes = Db::table('usertype')
            // ->where('constant','1')
            ->where('deleted', '0')
            ->orderBy('utype', 'asc')
            ->get();

        $allfolders = Db::table('schoolfolder')
            ->where('deleted', '0')
            ->orderBy('foldername', 'asc')
            ->get();

        $folders = array();

        if (count($allfolders) > 0) {
            foreach ($allfolders as $folder) {
                $usertypes = DB::table('schoolfolder_visibleto')
                    ->where('folderid', $folder->id)
                    ->where('deleted', 0)
                    ->get();

                $folder->usertypes = $usertypes;

                if (count(collect($usertypes)->where('usertypeid', auth()->user()->type)) > 0) {
                    array_push($folders, $folder);
                }

            }
        }
        // return $folders;

        if (Session::get('currentPortal') == 1) {

            $extends = "teacher.layouts.app";

        } elseif (Session::get('currentPortal') == 2) {

            $extends = "principalsportal.layouts.app2";

        } elseif (Session::get('currentPortal') == 3 || Session::get('currentPortal') == 8) {

            $extends = "registrar.layouts.app";

        } elseif (Session::get('currentPortal') == 4 || Session::get('currentPortal') == 15) {

            $extends = "finance.layouts.app";

        } elseif (Session::get('currentPortal') == 6) {

            $extends = "adminPortal.layouts.app2";

        } elseif (Session::get('currentPortal') == 10) {

            $extends = "hr.layouts.app";

        } elseif (Session::get('currentPortal') == 12) {

            $extends = "adminITPortal.layouts.app";

        } elseif (Session::get('currentPortal') == 18) {

            $extends = "ctportal.layouts.app2";

        } elseif (Session::get('currentPortal') == 7) {

            $extends = "studentPortal.layouts.app2";

        } else {

            $extends = "general.defaultportal.layouts.app";

        }

        $authorizedusers = Db::table('schoolfolder_authorizeduser')
            ->where('deleted', '0')
            ->get();

        $employee_folders = Db::table('employee_folders')
            ->where('deleted', '0')
            ->where('visible', '1')
            ->orderBy('foldername', 'asc')
            ->get();
        return view('schoolfolder.adminschoolfiles')
            ->with('usertypes', $audiencetypes)
            ->with('folders', $folders)
            ->with('employee_folders', $employee_folders)
            ->with('extends', $extends)
            ->with('authorizedusers', $authorizedusers);
    }
    public function addfolder(Request $request)
    {
        if ($request->ajax()) {
            date_default_timezone_set('Asia/Manila');

            $folderid = DB::table('schoolfolder')
                ->insertGetId([
                    'foldername' => $request->get('foldername'),
                    'color' => $request->get('colorpicked'),
                    'createdby' => auth()->user()->id,
                    'createddatetime' => date('Y-m-d H:i:s')
                ]);

            foreach ($request->get('usertypes') as $usertypeid) {
                DB::table('schoolfolder_visibleto')
                    ->insert([
                        'folderid' => $folderid,
                        'usertypeid' => $usertypeid,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => date('Y-m-d H:i:s')
                    ]);
            }

            foreach ($request->get('filetypes') as $filetype) {
                DB::table('schoolfolder_filetype')
                    ->insert([
                        'folderid' => $folderid,
                        'filetype' => $filetype,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => date('Y-m-d H:i:s')
                    ]);
            }
        }
    }
    public function folderview(Request $request)
    {
        if (auth()->user()->type == '1' || Session::get('currentPortal') == 1) {

            $extends = "teacher.layouts.app";

        } elseif (auth()->user()->type == '2' || Session::get('currentPortal') == 2) {

            $extends = "principalsportal.layouts.app2";

        } elseif (auth()->user()->type == '3' || auth()->user()->type == '8' || Session::get('currentPortal') == 3 || Session::get('currentPortal') == 8) {

            $extends = "registrar.layouts.app";

        } elseif (auth()->user()->type == '4' || auth()->user()->type == '15' || Session::get('currentPortal') == 4 || Session::get('currentPortal') == 15) {

            $extends = "finance.layouts.app";

        } elseif (auth()->user()->type == '6' || Session::get('currentPortal') == 6) {

            $extends = "adminPortal.layouts.app2";

        } elseif (auth()->user()->type == '10' || Session::get('currentPortal') == 10) {

            $extends = "hr.layouts.app";

        } elseif (auth()->user()->type == '12' || Session::get('currentPortal') == 12) {

            $extends = "adminITPortal.layouts.app";

        } elseif (auth()->user()->type == '18' || Session::get('currentPortal') == 18) {

            $extends = "ctportal.layouts.app2";

        } elseif (Session::get('currentPortal') == 7) {

            $extends = "studentPortal.layouts.app2";

        } else {
            $extends = "general.defaultportal.layouts.app";
        }


        $authorizedusers = Db::table('schoolfolder_authorizeduser')
            ->where('deleted', '0')
            ->get();

        $usertypes = DB::table('usertype')
            ->select('usertype.*')
            // ->where('constant', 1)
            ->where('deleted', 0)
            ->get();

        $users = array();

        foreach ($usertypes as $usertype) {
            if ($usertype->id == 7) {
                $userinfostudents = Db::table('studinfo')
                    ->select('studinfo.id', 'studinfo.userid', 'studinfo.lastname', 'studinfo.firstname', 'studinfo.middlename', 'studinfo.suffix')
                    ->where('deleted', '0')
                    ->get();

                if (count($userinfostudents) > 0) {
                    foreach ($userinfostudents as $userinfostudent) {
                        $userinfostudent->usertypeid = 7;
                        array_push($users, $userinfostudent);
                    }
                }

            } else {
                $userinfoteachers = Db::table('teacher')
                    ->select('teacher.id', 'teacher.userid', 'teacher.lastname', 'teacher.firstname', 'teacher.middlename', 'teacher.suffix', 'teacher.usertypeid')
                    ->where('deleted', '0')
                    ->where('usertypeid', $usertype->id)
                    ->get();

                if (count($userinfoteachers) > 0) {
                    foreach ($userinfoteachers as $userinfoteacher) {
                        array_push($users, $userinfoteacher);
                    }
                }
            }
            $visibleto = DB::table('schoolfolder_visibleto')
                ->where('folderid', $request->get('folderid'))
                ->where('usertypeid', $usertype->id)
                ->where('deleted', 0)
                ->get();

            if (count($visibleto) == 0) {
                $usertype->checked = 0;
            } else {
                $usertype->checked = 1;
            }
        }


        $filetypes = DB::table('schoolfolder_filetype')
            ->where('folderid', $request->get('folderid'))
            ->where('deleted', 0)
            ->get();

        $folderinfo = DB::table('schoolfolder')
            ->where('id', $request->get('folderid'))
            ->first();

        if ($folderinfo->deleted == 1) {
            return back();
        }

        $turnedins = DB::table('schoolfolder_files')
            ->select('schoolfolder_files.createdby', 'users.type as usertypeid', 'teacher.lastname', 'teacher.firstname', 'teacher.middlename', 'teacher.suffix')
            ->join('users', 'schoolfolder_files.createdby', '=', 'users.id')
            ->leftJoin('teacher', 'users.id', '=', 'teacher.userid')
            ->where('folderid', $folderinfo->id)
            ->where('schoolfolder_files.deleted', '0')
            ->distinct()
            // ->groupBy('schoolfolder_files.createdby')
            ->get();


        if (count($turnedins) > 0) {
            foreach ($turnedins as $turnedin) {


                $files = DB::table('schoolfolder_files')
                    ->select('schoolfolder_files.*', 'users.type as usertypeid', 'teacher.lastname', 'teacher.firstname', 'teacher.middlename', 'teacher.suffix')
                    ->join('users', 'schoolfolder_files.createdby', '=', 'users.id')
                    ->leftJoin('teacher', 'users.id', '=', 'teacher.userid')
                    ->where('folderid', $folderinfo->id)
                    ->where('schoolfolder_files.createdby', $turnedin->createdby)
                    ->where('schoolfolder_files.deleted', '0')
                    // ->groupBy('schoolfolder_files.createdby')
                    ->get();


                if (count($files) > 0) {
                    foreach ($files as $file) {
                        $customaudiences = array();
                        $customaudiencesteachers = DB::table('schoolfolder_visibletousers')
                            ->select('teacher.userid', 'teacher.lastname', 'teacher.firstname', 'teacher.middlename', 'usertype.utype')
                            ->join('teacher', 'schoolfolder_visibletousers.userid', '=', 'teacher.userid')
                            ->join('usertype', 'teacher.usertypeid', '=', 'usertype.id')
                            ->where('fileid', $file->id)
                            ->where('schoolfolder_visibletousers.deleted', '0')
                            ->get();

                        if (count($customaudiencesteachers) > 0) {
                            foreach ($customaudiencesteachers as $customaudiencesteacher) {
                                array_push($customaudiences, $customaudiencesteacher);

                            }
                        }
                        $customaudiencesstudents = DB::table('schoolfolder_visibletousers')
                            ->select('studinfo.userid', 'studinfo.lastname', 'studinfo.firstname', 'studinfo.middlename')
                            ->join('studinfo', 'schoolfolder_visibletousers.userid', '=', 'studinfo.userid')
                            ->where('fileid', $file->id)
                            ->where('schoolfolder_visibletousers.deleted', '0')
                            ->get();

                        if (count($customaudiencesstudents) > 0) {
                            foreach ($customaudiencesstudents as $customaudiencesstudent) {
                                $customaudiencesstudent->utype = 'STUDENT';
                                array_push($customaudiences, $customaudiencesstudent);

                            }
                        }


                        $file->audiences = $customaudiences;

                        if ($file->visibilitytype == 1) {
                            $file->view = 1;
                        } elseif ($file->visibilitytype == 2) {
                            if ($file->createdby == auth()->user()->id) {
                                $file->view = 1;
                            } else {
                                if (count(collect($file->audiences)->where('userid', auth()->user()->id)) > 0) {
                                    $file->view = 1;
                                } else {
                                    $file->view = 0;
                                }
                            }
                        } else {
                            if ($file->createdby == auth()->user()->id) {
                                $file->view = 1;
                            } else {
                                $file->view = 0;
                            }
                        }
                        // if($file->status == 0 && $file->createdby != auth()->user()->id)
                        // {

                        //     $file->view = 0;

                        // }elseif($file->status == 0 && $file->createdby == auth()->user()->id)
                        // {
                        //     $file->view = 1;
                        // }elseif($file->status == 1 && $file->createdby == auth()->user()->id)
                        // {
                        //     $file->view = 1;
                        // }elseif($file->status == 1 && $file->createdby != auth()->user()->id)
                        // {
                        //     $file->view = 1;
                        // }

                    }
                }

                $turnedin->files = $files;
            }
        }
        // return $turnedins;

        $authorized = 0;

        if (count($authorizedusers) == 0) {
            $filteredfiles = array();

            foreach ($turnedins as $file) {
                if (count(collect($usertypes)->where('checked', '1')->where('id', $file->usertypeid)) > 0 || collect($file->files)->contains('createdby', auth()->user()->id)) {
                    array_push($filteredfiles, $file);
                }
            }
        } else {
            if (count(collect($authorizedusers)->where('userid', auth()->user()->id)) > 0) {
                $authorized = 1;
                $filteredfiles = $turnedins;
            } else {
                $filteredfiles = array();

                foreach ($turnedins as $file) {
                    if (count(collect($usertypes)->where('checked', '1')->where('id', $file->usertypeid)) > 0 || collect($file->files)->contains('createdby', auth()->user()->id)) {
                        array_push($filteredfiles, $file);
                    }
                }
            }
        }
        // if(auth()->user()->type == 6)
        // {
        //     $filteredfiles = $turnedins;

        // }else{

        //     // return $turnedins;
        //     $filteredfiles = array();

        //     foreach($turnedins as $file)
        //     {
        //         if(count(collect($usertypes)->where('checked','1')->where('id',$file->usertypeid)) > 0 || collect($file->files)->contains('createdby',auth()->user()->id))
        //         {
        //             array_push($filteredfiles, $file);
        //         }
        //     }

        // }


        // foreach($filteredfiles as $userid)
        // {
        //     if($userid->usertypeid == 7)
        //     {
        //         $userinfostudents = Db::table('studinfo')
        //             ->select('studinfo.id','studinfo.userid','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix')
        //             ->where('deleted','0')
        //             ->get();

        //         if(count($userinfostudents)>0)
        //         {
        //             foreach($userinfostudents as $userinfostudent)
        //             {
        //                 $userinfostudent->usertypeid = 7;
        //                 array_push($users, $userinfostudent);
        //             }
        //         }

        //     }else{
        //         $userinfoteachers = Db::table('teacher')
        //             ->select('teacher.id','teacher.userid','teacher.lastname','teacher.firstname','teacher.middlename','teacher.suffix','teacher.usertypeid')
        //             ->where('deleted','0')
        //             ->where('usertypeid',$userid->createdby)
        //             ->get();

        //         if(count($userinfoteachers)>0)
        //         {
        //             foreach($userinfoteachers as $userinfoteacher)
        //             {
        //                 array_push($users, $userinfoteacher);
        //             }
        //         }
        //     }


        // }

        $canupload = 0;

        $whocanupload = Db::table('schoolfolder_userupload')
            ->select('id', 'type')
            // ->join('schoolfolder_useruploaddetail','schoolfolder_userupload.id','schoolfolder_useruploaddetail.headerid')
            ->where('folderid', $folderinfo->id)
            // ->where('schoolfolder_useruploaddetail.deleted','0')
            ->first();

        if ($whocanupload) {
            if ($whocanupload->type == 1) {
                $canupload = 1;
            } elseif ($whocanupload->type == 2) {
                $canusertypes = Db::table('schoolfolder_useruploaddetail')
                    ->where('headerid', $whocanupload->id)
                    ->where('deleted', '0')
                    ->get();

                if (collect($canusertypes)->contains('userid', Session::get('currentPortal'))) {
                    $canupload = 1;
                } else {
                    if ($folderinfo->createdby == auth()->user()->id) {
                        $canupload = 1;
                    }
                }
            } elseif ($whocanupload->type == 3) {
                $canusertypes = Db::table('schoolfolder_useruploaddetail')
                    ->where('headerid', $whocanupload->id)
                    ->where('deleted', '0')
                    ->get();

                if (collect($canusertypes)->contains('userid', auth()->user()->id)) {
                    $canupload = 1;
                } else {
                    if ($folderinfo->createdby == auth()->user()->id) {
                        $canupload = 1;
                    }
                }
            }
        }


        return view('schoolfolder.folderview')
            ->with('usertypes', $usertypes)
            ->with('folderinfo', $folderinfo)
            ->with('filetypes', $filetypes)
            ->with('files', $filteredfiles)
            ->with('users', $users)
            ->with('extends', $extends)
            ->with('authorized', $authorized)
            ->with('canupload', $canupload);
    }
    public function employeefolderview(Request $request)
    {
        if (Session::get('currentPortal') == 1) {

            $extends = "teacher.layouts.app";

        } elseif (Session::get('currentPortal') == 2) {

            $extends = "principalsportal.layouts.app2";

        } elseif (Session::get('currentPortal') == 3 || Session::get('currentPortal') == 8) {

            $extends = "registrar.layouts.app";

        } elseif (Session::get('currentPortal') == 4 || Session::get('currentPortal') == 15) {

            $extends = "finance.layouts.app";

        } elseif (Session::get('currentPortal') == 6) {

            $extends = "adminPortal.layouts.app2";

        } elseif (Session::get('currentPortal') == 10) {

            $extends = "hr.layouts.app";

        } elseif (Session::get('currentPortal') == 12) {

            $extends = "adminITPortal.layouts.app";

        } elseif (Session::get('currentPortal') == 18) {

            $extends = "ctportal.layouts.app2";

        } elseif (Session::get('currentPortal') == 7) {

            $extends = "studentPortal.layouts.app2";

        } else {
            $extends = "general.defaultportal.layouts.app";
        }

        $authorizedusers = Db::table('schoolfolder_authorizeduser')
            ->where('deleted', '0')
            ->get();

        $usertypes = DB::table('usertype')
            ->select('usertype.*')
            // ->where('constant', 1)
            ->where('deleted', 0)
            ->get();

        $users = array();

        foreach ($usertypes as $usertype) {
            if ($usertype->id == 7) {
                $userinfostudents = Db::table('studinfo')
                    ->select('studinfo.id', 'studinfo.userid', 'studinfo.lastname', 'studinfo.firstname', 'studinfo.middlename', 'studinfo.suffix')
                    ->where('deleted', '0')
                    ->get();

                if (count($userinfostudents) > 0) {
                    foreach ($userinfostudents as $userinfostudent) {
                        $userinfostudent->usertypeid = 7;
                        array_push($users, $userinfostudent);
                    }
                }

            } else {
                $userinfoteachers = Db::table('teacher')
                    ->select('teacher.id', 'teacher.userid', 'teacher.lastname', 'teacher.firstname', 'teacher.middlename', 'teacher.suffix', 'teacher.usertypeid')
                    ->where('deleted', '0')
                    ->where('usertypeid', $usertype->id)
                    ->get();

                if (count($userinfoteachers) > 0) {
                    foreach ($userinfoteachers as $userinfoteacher) {
                        array_push($users, $userinfoteacher);
                    }
                }
            }
            $visibleto = DB::table('schoolfolder_visibleto')
                ->where('folderid', $request->get('folderid'))
                ->where('usertypeid', $usertype->id)
                ->where('deleted', 0)
                ->get();

            if (count($visibleto) == 0) {
                $usertype->checked = 0;
            } else {
                $usertype->checked = 1;
            }
        }


        $filetypes = DB::table('schoolfolder_filetype')
            ->where('folderid', $request->get('folderid'))
            ->where('deleted', 0)
            ->get();

        $folderinfo = DB::table('employee_folders')
            ->where('id', $request->get('folderid'))
            ->first();

        if ($folderinfo->deleted == 1) {
            return back();
        }

        $turnedins = DB::table('employee_folderdetail')
            ->select('employee_folderdetail.createdby', 'users.type as usertypeid', 'teacher.lastname', 'teacher.firstname', 'teacher.middlename', 'teacher.suffix')
            ->join('users', 'employee_folderdetail.createdby', '=', 'users.id')
            ->leftJoin('teacher', 'users.id', '=', 'teacher.userid')
            ->where('headerid', $folderinfo->id)
            ->where('employee_folderdetail.deleted', '0')
            ->distinct()
            // ->groupBy('schoolfolder_files.createdby')
            ->get();


        if (count($turnedins) > 0) {
            foreach ($turnedins as $turnedin) {


                $files = DB::table('employee_folderdetail')
                    ->select('employee_folderdetail.*', 'users.type as usertypeid', 'teacher.lastname', 'teacher.firstname', 'teacher.middlename', 'teacher.suffix')
                    ->join('users', 'employee_folderdetail.createdby', '=', 'users.id')
                    ->leftJoin('teacher', 'users.id', '=', 'teacher.userid')
                    ->where('headerid', $folderinfo->id)
                    ->where('employee_folderdetail.createdby', $turnedin->createdby)
                    ->where('employee_folderdetail.deleted', '0')
                    // ->groupBy('schoolfolder_files.createdby')
                    ->get();


                if (count($files) > 0) {
                    foreach ($files as $file) {
                        $file->view = 1;
                        // $customaudiences = array();
                        // $customaudiencesteachers = DB::table('schoolfolder_visibletousers')
                        //     ->select('teacher.userid','teacher.lastname','teacher.firstname','teacher.middlename','usertype.utype')
                        //     ->join('teacher','schoolfolder_visibletousers.userid','=','teacher.userid')
                        //     ->join('usertype','teacher.usertypeid','=','usertype.id')
                        //     ->where('fileid', $file->id)
                        //     ->where('schoolfolder_visibletousers.deleted','0')
                        //     ->get();

                        // if(count($customaudiencesteachers)>0)
                        // {
                        //     foreach($customaudiencesteachers as $customaudiencesteacher)
                        //     {
                        //         array_push($customaudiences, $customaudiencesteacher);

                        //     }
                        // }
                        // $customaudiencesstudents = DB::table('schoolfolder_visibletousers')
                        //     ->select('studinfo.userid','studinfo.lastname','studinfo.firstname','studinfo.middlename')
                        //     ->join('studinfo','schoolfolder_visibletousers.userid','=','studinfo.userid')
                        //     ->where('fileid', $file->id)
                        //     ->where('schoolfolder_visibletousers.deleted','0')
                        //     ->get();

                        // if(count($customaudiencesstudents)>0)
                        // {
                        //     foreach($customaudiencesstudents as $customaudiencesstudent)
                        //     {
                        //         $customaudiencesstudent->utype = 'STUDENT';
                        //         array_push($customaudiences, $customaudiencesstudent);

                        //     }
                        // }


                        // $file->audiences = $customaudiences;

                        // if($file->visibilitytype == 1)
                        // {
                        //     $file->view = 1;
                        // }elseif($file->visibilitytype == 2){
                        //     if($file->createdby == auth()->user()->id)
                        //     {
                        //         $file->view = 1;
                        //     }else
                        //     {
                        //         if(count(collect($file->audiences)->where('userid', auth()->user()->id)) > 0)
                        //         {
                        //             $file->view = 1;
                        //         }else{
                        //             $file->view = 0;
                        //         }
                        //     }
                        // }else{
                        //     if($file->createdby == auth()->user()->id)
                        //     {
                        //         $file->view = 1;
                        //     }else
                        //     {
                        //         $file->view = 0;
                        //     }
                        // }

                    }
                }

                $turnedin->files = $files;
            }
        }
        // return $turnedins;

        $authorized = 0;

        if (count($authorizedusers) == 0) {
            $filteredfiles = array();

            foreach ($turnedins as $file) {
                if (count(collect($usertypes)->where('checked', '1')->where('id', $file->usertypeid)) > 0 || collect($file->files)->contains('createdby', auth()->user()->id)) {
                    array_push($filteredfiles, $file);
                }
            }
        } else {
            if (count(collect($authorizedusers)->where('userid', auth()->user()->id)) > 0) {
                $authorized = 1;
                $filteredfiles = $turnedins;
            } else {
                $filteredfiles = array();

                foreach ($turnedins as $file) {
                    if (count(collect($usertypes)->where('checked', '1')->where('id', $file->usertypeid)) > 0 || collect($file->files)->contains('createdby', auth()->user()->id)) {
                        array_push($filteredfiles, $file);
                    }
                }
            }
        }
        $canupload = 0;

        $whocanupload = Db::table('schoolfolder_userupload')
            ->select('id', 'type')
            // ->join('schoolfolder_useruploaddetail','schoolfolder_userupload.id','schoolfolder_useruploaddetail.headerid')
            ->where('folderid', $folderinfo->id)
            // ->where('schoolfolder_useruploaddetail.deleted','0')
            ->first();

        if ($whocanupload) {
            if ($whocanupload->type == 1) {
                $canupload = 1;
            } elseif ($whocanupload->type == 2) {
                $canusertypes = Db::table('schoolfolder_useruploaddetail')
                    ->where('headerid', $whocanupload->id)
                    ->where('deleted', '0')
                    ->get();

                if (collect($canusertypes)->contains('userid', Session::get('currentPortal'))) {
                    $canupload = 1;
                } else {
                    if ($folderinfo->createdby == auth()->user()->id) {
                        $canupload = 1;
                    }
                }
            } elseif ($whocanupload->type == 3) {
                $canusertypes = Db::table('schoolfolder_useruploaddetail')
                    ->where('headerid', $whocanupload->id)
                    ->where('deleted', '0')
                    ->get();

                if (collect($canusertypes)->contains('userid', auth()->user()->id)) {
                    $canupload = 1;
                } else {
                    if ($folderinfo->createdby == auth()->user()->id) {
                        $canupload = 1;
                    }
                }
            }
        }

        return view('schoolfolder.folderview')
            ->with('usertypes', $usertypes)
            ->with('folderinfo', $folderinfo)
            ->with('filetypes', $filetypes)
            ->with('files', $filteredfiles)
            ->with('users', $users)
            ->with('extends', $extends)
            ->with('authorized', $authorized)
            ->with('canupload', $canupload);

    }
    public function getfileview(Request $request)
    {
        $fileinfo = DB::table('schoolfolder_files')
            ->where('id', $request->get('fileid'))
            ->first();

        return view('schoolfolder.fileview')
            ->with('fileinfo', $fileinfo);
    }
    public function storeMedia(Request $request)
    {
        // $path = storage_path('tmp/uploads');
        // return $request->all();
        $folderinfo = DB::table('schoolfolder')
            ->where('id', $request->get('folderid'))
            ->first();

        try {
            $userlastname = DB::table('teacher')
                ->where('userid', auth()->user()->id)->first()->lastname;

            $lastname = str_replace(' ', '_', $userlastname);
        } catch (\Exception $error) {

            $lastname = str_replace(' ', '_', 'LASTNAME');
        }

        $lastname = str_replace('', '.', $lastname);

        $localfolder = 'Intranet/' . $folderinfo->foldername . '/' . auth()->user()->email . '_' . $lastname;
        // return $localfolder;
        $file = $request->file('file');

        $filename = $file->getClientOriginalName();

        $extension = $file->getClientOriginalExtension();

        if (!File::exists(public_path() . $localfolder)) {

            $path = public_path($localfolder);

            if (!File::isDirectory($path)) {

                File::makeDirectory($path, 0777, true, true);

            }

        }
        if (strpos($request->root(), 'http://') !== false) {
            $urlFolder = str_replace('http://', '', $request->root());
        } else {
            $urlFolder = str_replace('https://', '', $request->root());
        }

        if (!File::exists(dirname(base_path(), 1) . '/' . $urlFolder . '/' . $localfolder)) {

            $cloudpath = dirname(base_path(), 1) . '/' . $urlFolder . '/' . $localfolder;

            if (!File::isDirectory($cloudpath)) {

                File::makeDirectory($cloudpath, 0777, true, true);

            }

        }


        $clouddestinationPath = dirname(base_path(), 1) . '/' . $urlFolder . '/' . $localfolder;
        try {

            $file->move($clouddestinationPath, $filename);
        } catch (\Exception $e) {


        }

        $destinationPath = public_path($localfolder . '/');

        try {

            $file->move($destinationPath, $filename);

        } catch (\Exception $e) {


        }

        DB::table('schoolfolder_files')
            ->insert([
                'folderid' => $request->get('folderid'),
                'filename' => auth()->user()->email . '-' . $filename,
                'filepath' => $localfolder . '/' . $filename,
                'extension' => $extension,
                'createdby' => auth()->user()->id,
                'createddatetime' => date('Y-m-d H:i:s')
            ]);


        // if (!file_exists($path)) {
        //     mkdir($path, 0777, true);
        // }

        // $file = $request->file('file');
        // $extension = $file->getClientOriginalExtension();

        // $name = uniqid() . '_' . trim($file->getClientOriginalName());

        // $file->move($path, $name);

        // return response()->json([
        //     'name'          => $name,
        //     'original_name' => $file->getClientOriginalName(),
        // ]);
    }
    public function updatevisibility(Request $request)
    {
        // return $request->all();
        if ($request->ajax()) {
            try {
                if ($request->get('status') == 1) {
                    DB::table('schoolfolder_visibleto')
                        ->where('folderid', $request->get('folderid'))
                        ->where('usertypeid', $request->get('id'))
                        ->update([
                            'deleted' => $request->get('status'),
                            'deletedby' => auth()->user()->id,
                            'deleteddatetime' => date('Y-m-d H:i:s')
                        ]);
                } else {
                    DB::table('schoolfolder_visibleto')
                        ->insert([
                            'folderid' => $request->get('folderid'),
                            'usertypeid' => $request->get('id'),
                            'createdby' => auth()->user()->id,
                            'createddatetime' => date('Y-m-d H:i:s')
                        ]);
                }
                return '1';
            } catch (\Exception $error) {
                return '0';
            }
        }
    }
    public function updatefiletype(Request $request)
    {
        // return $request->all();
        if ($request->ajax()) {
            try {
                if ($request->get('status') == 1) {
                    DB::table('schoolfolder_filetype')
                        ->where('folderid', $request->get('folderid'))
                        ->where('filetype', $request->get('id'))
                        ->update([
                            'deleted' => $request->get('status'),
                            'deletedby' => auth()->user()->id,
                            'deleteddatetime' => date('Y-m-d H:i:s')
                        ]);
                } else {
                    DB::table('schoolfolder_filetype')
                        ->insert([
                            'folderid' => $request->get('folderid'),
                            'filetype' => $request->get('id'),
                            'createdby' => auth()->user()->id,
                            'createddatetime' => date('Y-m-d H:i:s')
                        ]);
                }
                return '1';
            } catch (\Exception $error) {
                return '0';
            }
        }
    }
    public function updatefoldername(Request $request)
    {
        // return $request->all();
        if ($request->ajax()) {
            try {
                DB::table('schoolfolder')
                    ->where('id', $request->get('folderid'))
                    ->update([
                        'foldername' => $request->get('foldername'),
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => date('Y-m-d H:i:s')
                    ]);
                return '1';
            } catch (\Exception $error) {
                return '0';
            }
        }
    }
    public function updatefoldercolor(Request $request)
    {
        // return $request->all();
        if ($request->ajax()) {
            try {
                DB::table('schoolfolder')
                    ->where('id', $request->get('folderid'))
                    ->update([
                        'color' => $request->get('color'),
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => date('Y-m-d H:i:s')
                    ]);
                return '1';
            } catch (\Exception $error) {
                return '0';
            }
        }
    }
    public function deletefolder(Request $request)
    {
        // return $request->all();
        try {
            DB::table('schoolfolder')
                ->where('id', $request->get('folderid'))
                ->update([
                    'deleted' => 1,
                    'deletedby' => auth()->user()->id,
                    'deletedby' => date('Y-m-d H:i:s')
                ]);
            return redirect('/administrator/schoolfolders');
        } catch (\Exception $error) {
            return back();
        }
    }
    public function deletefile(Request $request)
    {
        try {
            DB::table('schoolfolder_files')
                ->where('id', $request->get('fileid'))
                ->update([
                    'deleted' => 1,
                    'deletedby' => auth()->user()->id,
                    'deletedby' => date('Y-m-d H:i:s')
                ]);
            return back();
        } catch (\Exception $error) {
            return back();
        }
    }
    public function downloadfile(Request $request)
    {
        // return $request->all();
        return response()->download($request->get('filepath'));
    }
    public function updatevisibilitytype(Request $request)
    {
        // return $request->all();
        DB::table('schoolfolder_files')
            ->where('id', $request->get('fileid'))
            ->update([
                'visibilitytype' => $request->get('visibilitytype'),
                'updatedby' => auth()->user()->id,
                'updateddatetime' => date('Y-m-d H:i:s')
            ]);

        if ($request->has('input-audiences')) {
            DB::table('schoolfolder_visibletousers')
                ->where('fileid', $request->get('fileid'))
                ->update([
                    'deleted' => 1,
                    'deletedby' => auth()->user()->id,
                    'deletedby' => date('Y-m-d H:i:s')
                ]);

            foreach ($request->get('input-audiences') as $userid) {
                $checkifexists = DB::table('schoolfolder_visibletousers')
                    ->where('fileid', $request->get('fileid'))
                    ->where('userid', $userid)
                    ->where('deleted', '0')
                    ->get();

                if (count($checkifexists) == 0) {
                    DB::table('schoolfolder_visibletousers')
                        ->insert([
                            'fileid' => $request->get('fileid'),
                            'userid' => $userid,
                            'createdby' => auth()->user()->id,
                            'createddatetime' => date('Y-m-d H:i:s')
                        ]);
                }
            }
        }
        return back();
    }
    public function removeaudience(Request $request)
    {
        // return $request->all();
        if ($request->ajax()) {
            try {
                DB::table('schoolfolder_visibletousers')
                    ->where('fileid', $request->get('fileid'))
                    ->where('userid', $request->get('userid'))
                    ->update([
                        'deleted' => 1,
                        'deletedby' => auth()->user()->id,
                        'deletedby' => date('Y-m-d H:i:s')
                    ]);
                return 1;
            } catch (\Exception $error) {
                return 2;
            }
        }

    }
    public function whocanupload(Request $request)
    {
        $folderid = $request->get('folderid');

        $usertypes = DB::table('usertype')
            ->select('id', 'utype as name')
            ->where('deleted', '0')
            ->where('utype', '!=', 'SUPER ADMIN')
            ->orderBy('utype', 'asc')
            ->get();

        $info = DB::table('schoolfolder_userupload')
            ->where('folderid', $folderid)
            ->where('deleted', '0')
            ->first();

        if ($info) {
            if ($info->type == 2) {
                foreach ($usertypes as $usertype) {
                    $detail = DB::table('schoolfolder_useruploaddetail')
                        ->where('deleted', '0')
                        ->where('headerid', $info->id)
                        ->where('folderid', $folderid)
                        ->where('userid', $usertype->id)
                        ->get();

                    if (count($detail) == 0) {
                        $usertype->checked = 0;

                    } else {
                        $usertype->checked = 1;
                    }
                }

                $info->detail = $usertypes;


            } elseif ($info->type == 3) {
                $users = DB::table('schoolfolder_useruploaddetail')

                    ->where('folderid', $folderid)
                    ->where('headerid', $info->id)
                    ->where('deleted', '0')
                    ->get();

                if (count($users) > 0) {
                    foreach ($users as $user) {
                        $userinfoteacher = Db::table('teacher')
                            ->select('id', 'userid', 'lastname', 'firstname', 'middlename', 'suffix')
                            ->where('userid', $user->userid)
                            ->first();
                        $user->name = "";
                        if ($userinfoteacher) {
                            $user->name = $userinfoteacher->lastname . ', ' . $userinfoteacher->firstname . ' ' . $userinfoteacher->middlename . ' ' . $userinfoteacher->suffix;
                        } else {
                            $userinfostudent = Db::table('studinfo')
                                ->select('id', 'userid', 'lastname', 'firstname', 'middlename', 'suffix')
                                ->where('userid', $user->userid)
                                ->first();

                            if ($userinfostudent) {
                                $user->name = $userinfostudent->lastname . ', ' . $userinfostudent->firstname . ' ' . $userinfostudent->middlename . ' ' . $userinfostudent->suffix;
                            }
                        }


                    }
                }
                $info->detail = $users;

                // $users = array();

                // $userinfostudents = Db::table('studinfo')
                //     ->select('studinfo.id','studinfo.userid','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix')
                //     ->where('userid','!=',null)
                //     ->where('deleted','0')
                //     ->get();

                // if(count($userinfostudents)>0)
                // {
                //     foreach($userinfostudents as $userinfostudent)
                //     {
                //         array_push($users, $userinfostudent);
                //     }
                // }

                // $userinfoteachers = Db::table('teacher')
                //     ->select('teacher.id','teacher.userid','teacher.lastname','teacher.firstname','teacher.middlename','teacher.suffix','teacher.usertypeid')
                //     ->where('userid','!=',null)
                //     ->where('deleted','0')
                //     ->get();

                // if(count($userinfoteachers)>0)
                // {
                //     foreach($userinfoteachers as $userinfoteacher)
                //     {
                //         array_push($users, $userinfoteacher);
                //     }
                // }


            }
        }

        // return collect($info);
        return view('schoolfolder.whocanupload')
            ->with('info', $info);
    }
    public function whocanuploadget(Request $request)
    {
        if ($request->ajax()) {
            $usertypes = DB::table('usertype')
                ->select('id', 'utype')
                ->where('utype', '!=', 'SUPER ADMIN')
                ->where('deleted', '0')
                ->orderBy('utype')
                ->get();

            return $usertypes;
        }
    }
    public function whocanuploadgetusers(Request $request)
    {

        $users = array();

        $userinfostudents = Db::table('studinfo')
            ->select('studinfo.id', 'studinfo.userid', 'studinfo.lastname', 'studinfo.firstname', 'studinfo.middlename', 'studinfo.suffix')
            ->where('userid', '!=', null)
            ->where('deleted', '0')
            ->get();

        if (count($userinfostudents) > 0) {
            foreach ($userinfostudents as $userinfostudent) {
                array_push($users, $userinfostudent);
            }
        }

        $userinfoteachers = Db::table('teacher')
            ->select('teacher.id', 'teacher.userid', 'teacher.lastname', 'teacher.firstname', 'teacher.middlename', 'teacher.suffix', 'teacher.usertypeid')
            ->where('userid', '!=', null)
            ->where('deleted', '0')
            ->get();

        if (count($userinfoteachers) > 0) {
            foreach ($userinfoteachers as $userinfoteacher) {
                array_push($users, $userinfoteacher);
            }
        }

        return $users;
    }
    public function whocanuploadsubmit(Request $request)
    {
        // return $request->all();
        $folderid = $request->get('folderid');
        $checkifexists = DB::table('schoolfolder_userupload')
            ->where('folderid', $folderid)
            ->where('deleted', 0)
            ->get();

        // return $checkifexists;
        $type = $request->get('selectedtype');

        if ($type == 'all') // 1
        {
            $type = 1;
        } elseif ($type == 'custom') {
            $type = 3;
        } else {
            $type = 2;
        }

        if (count($checkifexists) == 0) {
            $headerid = DB::table('schoolfolder_userupload')
                ->insertGetId([
                    'folderid' => $folderid,
                    'type' => $type,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => date('Y-m-d H:i:s')
                ]);

            if ($type == 2) {
                if ($request->has('selectedusertypes')) {
                    foreach ($request->get('selectedusertypes') as $usertype) {
                        DB::table('schoolfolder_useruploaddetail')
                            ->insertGetId([
                                'headerid' => $headerid,
                                'folderid' => $folderid,
                                'userid' => $usertype,
                                'createdby' => auth()->user()->id,
                                'createddatetime' => date('Y-m-d H:i:s')
                            ]);
                    }
                }
            } elseif ($type == 3) {
                if ($request->has('users')) {
                    foreach ($request->get('users') as $user) {
                        DB::table('schoolfolder_useruploaddetail')
                            ->insertGetId([
                                'headerid' => $headerid,
                                'folderid' => $folderid,
                                'userid' => $user,
                                'createdby' => auth()->user()->id,
                                'createddatetime' => date('Y-m-d H:i:s')
                            ]);
                    }
                }
            }
        } else {

            DB::table('schoolfolder_userupload')
                ->where('id', $checkifexists[0]->id)
                ->update([
                    'type' => $type,
                    'updatedby' => auth()->user()->id,
                    'updateddatetime' => date('Y-m-d H:i:s')
                ]);


            if ($type == 1) {
                DB::table('schoolfolder_useruploaddetail')
                    ->where('headerid', $checkifexists[0]->id)
                    ->where('folderid', $folderid)
                    ->where('deleted', '0')
                    ->update([
                        'deleted' => 1,
                        'deletedby' => auth()->user()->id,
                        'deleteddatetime' => date('Y-m-d H:i:s')
                    ]);
            } elseif ($type == 2) {
                // return $request->get('selectedusertypes');
                if ($request->has('selectedusertypes')) {
                    $checkifexistsusertypes = DB::table('schoolfolder_useruploaddetail')
                        ->where('headerid', $checkifexists[0]->id)
                        ->where('folderid', $folderid)
                        ->where('deleted', '0')
                        ->get();

                    if (count($checkifexistsusertypes) > 0) {
                        foreach ($checkifexistsusertypes as $checkifexistsusertype) {
                            if (in_array($checkifexistsusertype->userid, $request->get('selectedusertypes'))) {

                            } else {
                                DB::table('schoolfolder_useruploaddetail')
                                    ->where('headerid', $checkifexists[0]->id)
                                    ->where('folderid', $folderid)
                                    ->where('userid', $checkifexistsusertype->userid)
                                    ->where('deleted', '0')
                                    ->update([
                                        'deleted' => 1,
                                        'deletedby' => auth()->user()->id,
                                        'deleteddatetime' => date('Y-m-d H:i:s')
                                    ]);
                            }
                        }
                    }
                    foreach ($request->get('selectedusertypes') as $usertype) {
                        DB::table('schoolfolder_useruploaddetail')
                            ->insertGetId([
                                'headerid' => $checkifexists[0]->id,
                                'folderid' => $folderid,
                                'userid' => $usertype,
                                'createdby' => auth()->user()->id,
                                'createddatetime' => date('Y-m-d H:i:s')
                            ]);
                    }
                } else {
                    DB::table('schoolfolder_useruploaddetail')
                        ->where('headerid', $checkifexists[0]->id)
                        ->where('folderid', $folderid)
                        ->where('deleted', '0')
                        ->update([
                            'deleted' => 1,
                            'deletedby' => auth()->user()->id,
                            'deleteddatetime' => date('Y-m-d H:i:s')
                        ]);
                }
            } elseif ($type == 3) {
                if ($request->has('users')) {
                    foreach ($request->get('users') as $user) {
                        $checkifexistsuser = DB::table('schoolfolder_useruploaddetail')
                            ->where('headerid', $checkifexists[0]->id)
                            ->where('folderid', $folderid)
                            ->where('deleted', '0')
                            ->where('userid', $user)
                            ->first();

                        if ($checkifexistsuser) {

                        } else {
                            DB::table('schoolfolder_useruploaddetail')
                                ->insertGetId([
                                    'headerid' => $checkifexists[0]->id,
                                    'folderid' => $folderid,
                                    'userid' => $user,
                                    'createdby' => auth()->user()->id,
                                    'createddatetime' => date('Y-m-d H:i:s')
                                ]);
                        }
                    }
                    $getusers = DB::table('schoolfolder_useruploaddetail')
                        ->where('headerid', $checkifexists[0]->id)
                        ->where('folderid', $folderid)
                        ->where('deleted', '0')
                        ->get();

                    if (count($getusers) > 0) {
                        foreach ($getusers as $eachuser) {
                            if (in_array($eachuser->userid, $request->get('users'))) {

                            } else {
                                DB::table('schoolfolder_useruploaddetail')
                                    ->where('headerid', $checkifexists[0]->id)
                                    ->where('folderid', $folderid)
                                    ->where('userid', $eachuser->userid)
                                    ->where('deleted', '0')
                                    ->update([
                                        'deleted' => 1,
                                        'deletedby' => auth()->user()->id,
                                        'deleteddatetime' => date('Y-m-d H:i:s')
                                    ]);
                            }
                        }
                    }
                }
            }
            // if($checkifexists[0]->type == 1)
            // {
            // }
            // elseif($checkifexists[0]->type == 2)
            // {
            //     if($type == 1)
            //     {

            //     }
            // }


        }
    }
    public function indexv2(Request $request)
    {
        $check_refid = DB::table('usertype')
            ->where('id', Session::get('currentPortal'))
            ->select('refid', 'resourcepath')
            ->first();

        if (Session::get('currentPortal') == 14) {
            $extends = 'deanportal.layouts.app2';
        } elseif (auth()->user()->type == 17) {
            $extends = 'superadmin.layouts.app2';
        } elseif (Session::get('currentPortal') == 3) {
            $extends = 'registrar.layouts.app';
        } elseif (Session::get('currentPortal') == 8) {
            $extends = 'admission.layouts.app2';
        } elseif (Session::get('currentPortal') == 1) {
            $extends = 'teacher.layouts.app';
        } elseif (Session::get('currentPortal') == 2) {
            $extends = 'principalsportal.layouts.app2';
        } elseif (Session::get('currentPortal') == 6) {
            $extends = 'adminPortal.layouts.app2';
        } elseif (Session::get('currentPortal') == 4) {
            $extends = 'finance.layouts.app';
        } elseif (Session::get('currentPortal') == 15) {
            $extends = 'finance.layouts.app';
        } elseif (Session::get('currentPortal') == 18) {
            $extends = 'ctportal.layouts.app2';
        } elseif (Session::get('currentPortal') == 10) {
            $extends = 'hr.layouts.app';
        } elseif (Session::get('currentPortal') == 16) {
            $extends = 'chairpersonportal.layouts.app2';
        } elseif (auth()->user()->type == 16) {
            $extends = 'chairpersonportal.layouts.app2';
        } else {
            if (isset($check_refid->refid)) {
                if ($check_refid->resourcepath == null) {
                    $extends = 'general.defaultportal.layouts.app';
                } elseif ($check_refid->refid == 27) {
                    $extends = 'academiccoor.layouts.app2';
                } elseif ($check_refid->refid == 22) {
                    $extends = 'principalcoor.layouts.app2';
                } elseif ($check_refid->refid == 29) {
                    $extends = 'idmanagement.layouts.app2';
                } elseif ($check_refid->refid == 23) {
                    $extends = 'clinic.index';
                } elseif ($check_refid->refid == 24) {
                    $extends = 'clinic_nurse.index';
                } elseif ($check_refid->refid == 25) {
                    $extends = 'clinic_doctor.index';
                } elseif ($check_refid->refid == 26) {
                    $extends = 'hr.layouts.app';
                } elseif ($check_refid->refid == 31) {
                    $extends = 'guidanceV2.layouts.app2';
                } elseif ($check_refid->refid == 33) {
                    $extends = 'inventory.layouts.app2';
                } elseif ($check_refid->refid == 35) {
                    $extends = 'tesda.layouts.app2';
                } elseif ($check_refid->refid == 36) {
                    $extends = 'tesda_trainer.layouts.app2';
                }   
                 else {
                    $extends = 'general.defaultportal.layouts.app';
                }
            } else {
                $extends = 'general.defaultportal.layouts.app';
            }
        }

        $folders = DB::table('schoolfolder')
            ->where('deleted', '0')
            ->where('createdby', auth()->user()->id)
            ->get();

        $getsharedfolders = DB::table('schoolfolder')
            ->where('deleted', '0')
            ->where('vtype', '>', 0)
            ->where('createdby', '!=', auth()->user()->id)
            ->get();
        // return $getsharedfolders;
        // $myusertype = auth()->user()->type;
        if (count($getsharedfolders) > 0) {
            foreach ($getsharedfolders as $getsharedfolder) {
                $getsharedfolder->display = 0;
                if ($getsharedfolder->vtype == 1) {
                    $getsharedfolder->display = 1;
                } elseif ($getsharedfolder->vtype == 2) {
                    // return auth()->user()->type;
                    $checkportal = DB::table('schoolfolder_visibleto')
                        ->where('folderid', $getsharedfolder->id)
                        ->where('usertypeid', auth()->user()->type)
                        ->where('deleted', '0')
                        ->first();

                    if ($checkportal) {
                        $getsharedfolder->display = 1;
                    }
                } elseif ($getsharedfolder->vtype == 3) {
                    $checkuser = DB::table('schoolfolder_visibletousers')
                        ->where('folderid', $getsharedfolder->id)
                        ->where('userid', auth()->user()->id)
                        ->where('deleted', '0')
                        ->first();

                    if ($checkuser) {
                        $getsharedfolder->display = 1;
                    }
                }
            }
        }
        // return  auth()->user()->id;
        $sharedfolders = collect($getsharedfolders)->where('display', '1')->values()->all();
        $posts = array();
        if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct') {
            $posts = DB::table('bct_commscont')
                ->select('bct_commscont.*', 'teacher.id as teacherid', 'teacher.firstname', 'teacher.middlename', 'teacher.lastname', 'teacher.suffix', 'teacher.picurl')
                ->where('bct_commscont.deleted', '0')
                ->join('teacher', 'bct_commscont.createdby', '=', 'teacher.userid')
                ->where('teacher.deleted', '0')
                ->orderByDesc('bct_commscont.id')
                ->get();

            if (count($posts) > 0) {
                foreach ($posts as $eachpost) {
                    $attachments = DB::table('bct_commscontatt')
                        ->where('postid', $eachpost->id)
                        ->where('deleted', '0')
                        ->get();

                    $eachpost->attachments = $attachments;
                    $comments = DB::table('bct_commscontcomments')
                        ->select('bct_commscontcomments.*', 'teacher.id as teacherid', 'teacher.firstname', 'teacher.middlename', 'teacher.lastname', 'teacher.suffix', 'teacher.picurl')
                        ->where('postid', $eachpost->id)
                        ->join('teacher', 'bct_commscontcomments.createdby', '=', 'teacher.userid')
                        ->where('bct_commscontcomments.deleted', '0')
                        ->where('teacher.deleted', '0')
                        ->orderByDesc('bct_commscontcomments.createddatetime')
                        ->get();

                    $eachpost->comments = $comments;
                }
            }
        }

        $creator = DB::table('school_folder_setup')
            ->where('usertypeid', auth()->user()->type)
            ->where('deleted', '0')
            ->get();

        if (count($creator) > 0) {
            $create_folder = 1;
        } else {
            $create_folder = 0;
        }

        // $yesterday = \Carbon\Carbon::yesterday();

        return view('schoolfolder.v2.index')
            ->with('posts', $posts)
            ->with('create_folder', $create_folder)
            ->with('sharedfolders', $sharedfolders)
            ->with('folders', $folders)
            ->with('extends', $extends);

    }
    public function publishapost(Request $request) //v2 BCT Commons
    {
        date_default_timezone_set('Asia/Manila');

        // return $request->all();

        $postid = DB::table('bct_commscont')
            ->insertGetId([
                'description' => $request->get('post-description'),
                'createdby' => auth()->user()->id,
                'createddatetime' => \Carbon\Carbon::now('Asia/Manila')->toDateTimeString()
            ]);

        if ($request->hasfile('files')) {
            // return $request->file('files');
            foreach ($request->file('files') as $key => $file) {
                $filesize = filesize($file); // bytes
                $filesize = round($filesize / 1024 / 1024, 1); // megabytes with 1 digit

                // return "The size of your file is $filesize MB.";

                $filename = $file->getClientOriginalName();

                $extension = $file->getClientOriginalExtension();

                $localfolder = 'bct_commons/contpage/postid' . $postid . '-' . auth()->user()->email;

                if (!File::exists(public_path() . $localfolder)) {

                    $path = public_path($localfolder);

                    if (!File::isDirectory($path)) {

                        File::makeDirectory($path, 0777, true, true);

                    }

                }
                // if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                // {
                //     $urlFolder = 'bct-schoolfolders.smsaccess.net';
                // }else{
                if (strpos($request->root(), 'http://') !== false) {
                    $urlFolder = str_replace('http://', '', $request->root());
                } else {
                    $urlFolder = str_replace('https://', '', $request->root());
                }
                // }
                if (!File::exists(dirname(base_path(), 1) . '/' . $urlFolder . '/' . $localfolder)) {

                    $cloudpath = dirname(base_path(), 1) . '/' . $urlFolder . '/' . $localfolder;

                    if (!File::isDirectory($cloudpath)) {

                        File::makeDirectory($cloudpath, 0777, true, true);

                    }

                }

                //$clouddestinationPath = dirname(base_path(), 1).'/'.$urlFolder.'/'.$localfolder;
                //try{

                //$file->move($clouddestinationPath,  pathinfo($filename, PATHINFO_FILENAME).'-'.date('Ymdhis').'.'.$extension);
                //}
                //catch(\Exception $e){


                //}

                $destinationPath = public_path($localfolder . '/');

                try {

                    $file->move($destinationPath, pathinfo($filename, PATHINFO_FILENAME) . '-' . date('Ymdhis') . '.' . $extension);

                } catch (\Exception $e) {


                }
                DB::table('bct_commscontatt')
                    ->insert([
                        'postid' => $postid,
                        'title' => $request->get('file-title')[$key],
                        'description' => $request->get('file-description')[$key],
                        'attachtype' => 1,
                        'filename' => pathinfo($filename, PATHINFO_FILENAME) . '-' . date('Ymdhis') . '.' . $extension,
                        'filepath' => $localfolder . '/' . pathinfo($filename, PATHINFO_FILENAME) . '-' . date('Ymdhis') . '.' . $extension,
                        'fileext' => $extension,
                        'filesize' => $filesize,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => \Carbon\Carbon::now('Asia/Manila')->toDateTimeString()
                    ]);

            }
        }
        if ($request->has('links')) {

            foreach ($request->get('links') as $key => $link) {
                DB::table('bct_commscontatt')
                    ->insert([
                        'postid' => $postid,
                        'title' => $request->get('link-title')[$key],
                        'description' => $request->get('link-description')[$key],
                        'attachtype' => 0,
                        'filename' => $link,
                        'filepath' => $link,
                        // 'fileext'         => $extension,
                        // 'filesize'         => $filesize,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => \Carbon\Carbon::now('Asia/Manila')->toDateTimeString()
                    ]);
            }
        }
        return back();
    }
    public function contributionpage(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        // return \Carbon\Carbon::now('Asia/Manila')->toDateTimeString();
        $myinfo = DB::table('teacher')
            ->where('userid', auth()->user()->id)
            ->where('deleted', '0')
            ->first();

        if ($request->get('action') == 'commentsave') {
            $commentid = DB::table('bct_commscontcomments')
                ->insertGetId([
                    'postid' => $request->get('postid'),
                    'comment' => $request->get('commentval'),
                    'createdby' => auth()->user()->id,
                    'createddatetime' => \Carbon\Carbon::now('Asia/Manila')->toDateTimeString()
                ]);

            $commentinfo = DB::table('bct_commscontcomments')
                ->where('id', $commentid)
                ->first();

            $myinfo->commentid = $commentid;
            $myinfo->comment = $commentinfo->comment;

            $datetimecreated = '';
            $today = \Carbon\Carbon::now();
            if (date('Y-m-d', strtotime($commentinfo->createddatetime)) == $today->toDateString()) {
                $datetimecreated = date('h:i A', strtotime($commentinfo->createddatetime)) . ' Today';
            } elseif (date('Y-m-d', strtotime($commentinfo->createddatetime)) == \Carbon\Carbon::yesterday()->toDateString()) {
                $datetimecreated = date('h:i A', strtotime($commentinfo->createddatetime)) . ' Yesterday';
            } else {
                $datetimecreated = date('h:i A M d, Y', strtotime($commentinfo->createddatetime));
            }
            $myinfo->commentcreateddatetime = $datetimecreated;

            return collect($myinfo);
        }
        if ($request->get('action') == 'commentdelete') {
            DB::table('bct_commscontcomments')
                ->where('id', $request->get('commentid'))
                ->update([
                    'deleted' => 1,
                    'deletedby' => auth()->user()->id,
                    'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')->toDateTimeString()
                ]);

            return 1;
        }
        if ($request->get('action') == 'postdelete') {
            DB::table('bct_commscont')
                ->where('id', $request->get('postid'))
                ->update([
                    'deleted' => 1,
                    'deletedby' => auth()->user()->id,
                    'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')->toDateTimeString()
                ]);

            return 1;
        }
        if ($request->get('action') == 'attachmentdelete') {
            DB::table('bct_commscontatt')
                ->where('id', $request->get('attid'))
                ->update([
                    'deleted' => 1,
                    'deletedby' => auth()->user()->id,
                    'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')->toDateTimeString()
                ]);
            return 1;
        }

    }
    public function folder(Request $request)
    {
        date_default_timezone_set('Asia/Manila');


        if ($request->get('action') == 'getvisibilityresults') {
            if ($request->ajax()) {
                if ($request->get('selectedtype') == 2) {
                    $usertypes = DB::table('usertype')
                        ->select('id', 'utype')
                        ->where('deleted', '0')
                        ->where('id', '!=', '6')
                        ->where('id', '!=', '9')
                        ->where('id', '!=', '12')
                        ->where('id', '!=', '17')
                        ->get();

                    if (count($usertypes) > 0) {
                        foreach ($usertypes as $usertype) {
                            $usertype->display = 0;
                        }
                    }
                    if ($request->has('folderid')) {

                        $existingportals = DB::table('schoolfolder_visibleto')
                            ->where('folderid', $request->get('folderid'))
                            ->where('deleted', '0')
                            ->get();

                        if (count($usertypes) > 0) {
                            foreach ($usertypes as $usertype) {
                                if (collect($existingportals)->where('usertypeid', $usertype->id)->first()) {
                                    $usertype->display = 1;
                                } else {
                                    $usertype->display = 0;
                                }
                            }
                        }

                    }
                    return $usertypes;

                } elseif ($request->get('selectedtype') == 3) {
                    $users = collect();
                    $students = DB::table('users')
                        ->select('studinfo.userid', 'studinfo.id', 'studinfo.lastname', 'studinfo.firstname', 'studinfo.middlename', 'studinfo.suffix', 'users.type as usertypeid')
                        ->join('studinfo', 'users.id', '=', 'studinfo.userid')
                        ->where('users.deleted', '0')
                        ->where('studinfo.deleted', '0')
                        ->where('users.type', '7')
                        ->get();

                    $teachers = DB::table('users')
                        ->select('teacher.userid', 'teacher.id', 'teacher.lastname', 'teacher.firstname', 'teacher.middlename', 'teacher.suffix', 'users.type as usertypeid', 'usertype.utype')
                        ->join('teacher', 'users.id', '=', 'teacher.userid')
                        ->join('usertype', 'users.type', '=', 'usertype.id')
                        ->where('users.deleted', '0')
                        ->where('teacher.deleted', '0')
                        ->where('users.type', '!=', '7')
                        ->get();

                    $users = collect($users)->merge($teachers);
                    // $users = collect($users)->merge($students);

                    if (count($users) > 0) {
                        foreach ($users as $user) {
                            $user->display = 0;
                        }
                    }
                    if ($request->has('folderid')) {

                        $existingusers = DB::table('schoolfolder_visibletousers')
                            ->where('folderid', $request->get('folderid'))
                            ->where('deleted', '0')
                            ->get();

                        if (count($users) > 0) {
                            foreach ($users as $user) {
                                if (collect($existingusers)->where('userid', $user->userid)->first()) {
                                    $user->display = 1;
                                } else {
                                    $user->display = 0;
                                }
                            }
                        }

                    }
                    return $users;
                }
            }
        } elseif ($request->get('action') == 'folderadd') {
            // return $request->all();
            $folderid = DB::table('schoolfolder')
                ->insertGetId([
                    'foldername' => $request->get('foldername'),
                    'vtype' => $request->get('visibilitytype'),
                    'createdby' => auth()->user()->id,
                    'createddatetime' => date('Y-m-d H:i:s')
                ]);

            if ($request->get('visibilitytype') == '2') {
                if (count($request->get('portals')) > 0) {
                    foreach ($request->get('portals') as $eachportal) {
                        DB::table('schoolfolder_visibleto')
                            ->insert([
                                'folderid' => $folderid,
                                'usertypeid' => $eachportal,
                                'viewtype' => $request->get('viewtype'),
                                'createdby' => auth()->user()->id,
                                'createddatetime' => date('Y-m-d H:i:s')
                            ]);
                    }
                }
            } elseif ($request->get('visibilitytype') == '3') {
                if (count($request->get('users')) > 0) {
                    foreach ($request->get('users') as $eachuser) {
                        DB::table('schoolfolder_visibletousers')
                            ->insert([
                                'folderid' => $folderid,
                                'userid' => $eachuser,
                                'viewtype' => $request->get('viewtype'),
                                'createdby' => auth()->user()->id,
                                'createddatetime' => date('Y-m-d H:i:s')
                            ]);
                    }
                }
            }

            return back();
        } elseif ($request->get('action') == 'updatefolder') {
            DB::table('schoolfolder')
                ->where('id', $request->get('folderid'))
                ->update([
                    'foldername' => $request->get('foldername'),
                    'updatedby' => auth()->user()->id,
                    'updateddatetime' => date('Y-m-d H:i:s')
                ]);

            return 1;
        } elseif ($request->get('action') == 'deletefolder') {
            $checkifexists = DB::table('schoolfolder_files')
                ->where('folderid', $request->get('folderid'))
                ->get();

            if (count($checkifexists) == 0) {

                DB::table('schoolfolder')
                    ->where('id', $request->get('folderid'))
                    ->update([
                        'deleted' => 1,
                        'deletedby' => auth()->user()->id,
                        'deleteddatetime' => date('Y-m-d H:i:s')
                    ]);

                return 1;
            } else {
                return 2;
            }

        } elseif ($request->get('action') == 'updatevisibility') {
            $folderid = $request->get('folderid');
            DB::table('schoolfolder')
                ->where('id', $request->get('folderid'))
                ->update([
                    'vtype' => $request->get('visibilitytype'),
                    'updatedby' => auth()->user()->id,
                    'updateddatetime' => date('Y-m-d H:i:s')
                ]);


            if ($request->get('visibilitytype') == '2') {
                $existingportals = DB::table('schoolfolder_visibleto')
                    ->where('folderid', $request->get('folderid'))
                    ->where('deleted', '0')
                    ->get();

                if (count($existingportals) > 0) {
                    foreach ($existingportals as $eachportal) {
                        if (!in_array($eachportal->usertypeid, $request->get('portals'))) {
                            DB::table('schoolfolder_visibleto')
                                ->where('id', $eachportal->id)
                                ->update([
                                    'deleted' => 1,
                                    'deletedby' => auth()->user()->id,
                                    'deleteddatetime' => date('Y-m-d H:i:s')
                                ]);
                        }
                    }
                }
                if (count($request->get('portals')) > 0) {
                    foreach ($request->get('portals') as $eachportal) {
                        DB::table('schoolfolder_visibleto')
                            ->insert([
                                'folderid' => $folderid,
                                'usertypeid' => $eachportal,
                                'createdby' => auth()->user()->id,
                                'createddatetime' => date('Y-m-d H:i:s')
                            ]);
                    }
                }
                DB::table('schoolfolder_visibletousers')
                    ->where('folderid', $request->get('folderid'))
                    ->update([
                        'deleted' => 1,
                        'deletedby' => auth()->user()->id,
                        'deleteddatetime' => date('Y-m-d H:i:s')
                    ]);
            } elseif ($request->get('visibilitytype') == '3') {
                // return $request->all();
                $existingusers = DB::table('schoolfolder_visibletousers')
                    ->where('folderid', $request->get('folderid'))
                    ->where('deleted', '0')
                    ->get();

                if ($request->has('users')) {
                    if (count($existingusers) > 0) {
                        foreach ($existingusers as $eachuser) {
                            if (!in_array($eachuser->userid, $request->get('users'))) {
                                DB::table('schoolfolder_visibletousers')
                                    ->where('id', $eachuser->id)
                                    ->update([
                                        'deleted' => 1,
                                        'deletedby' => auth()->user()->id,
                                        'deleteddatetime' => date('Y-m-d H:i:s')
                                    ]);
                            }
                        }
                    }
                    if (count($request->get('users')) > 0) {
                        foreach ($request->get('users') as $eachuser) {
                            if (collect($existingusers)->where('userid', $eachuser)->count() == 0) {
                                DB::table('schoolfolder_visibletousers')
                                    ->insert([
                                        'folderid' => $folderid,
                                        'userid' => $eachuser,
                                        'createdby' => auth()->user()->id,
                                        'createddatetime' => date('Y-m-d H:i:s')
                                    ]);
                            }
                        }
                    }
                } else {
                    DB::table('schoolfolder_visibletousers')
                        ->where('folderid', $request->get('folderid'))
                        ->update([
                            'deleted' => 1,
                            'deletedby' => auth()->user()->id,
                            'deleteddatetime' => date('Y-m-d H:i:s')
                        ]);
                }
                DB::table('schoolfolder_visibleto')
                    ->where('folderid', $request->get('folderid'))
                    ->update([
                        'deleted' => 1,
                        'deletedby' => auth()->user()->id,
                        'deleteddatetime' => date('Y-m-d H:i:s')
                    ]);
            }

            return back();
        } elseif ($request->get('action') == 'deletefile') {
            DB::table('schoolfolder_files')
                ->where('id', $request->get('fileid'))
                ->update([
                    'deleted' => 1,
                    'deletedby' => auth()->user()->id,
                    'deleteddatetime' => date('Y-m-d H:i:s')
                ]);

            return 1;
        } elseif ($request->get('action') == 'commentonfile') {
            try {
                DB::table('schoolfolder_comments')
                    ->insert([
                        'fileid' => $request->get('fileid'),
                        'comment' => $request->get('comment'),
                        'userid' => auth()->user()->id,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => date('Y-m-d H:i:s')
                    ]);

                return 1;
            } catch (\Exception $error) {
                return 0;
            }
        } elseif ($request->get('action') == 'getcomments') {
            $createdby = DB::table('schoolfolder_files')
                ->where('id', $request->get('fileid'))
                ->first()->createdby;

            if ($createdby == auth()->user()->id) {
                DB::table('schoolfolder_comments')
                    ->where('fileid', $request->get('fileid'))
                    ->update([
                        'seen' => 1,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => date('Y-m-d H:i:s')
                    ]);
            }
            $comments = DB::table('schoolfolder_comments')
                ->where('fileid', $request->get('fileid'))
                ->where('schoolfolder_comments.deleted', '0')
                ->join('users', 'schoolfolder_comments.userid', '=', 'users.id')
                ->select('schoolfolder_comments.*', 'users.type')
                ->get();

            if (count($comments) > 0) {
                foreach ($comments as $comment) {
                    $nameofthecommentor = '';
                    if ($comment->type == 7) {
                        $createdbyinfo = DB::table('studinfo')
                            ->where('userid', $comment->userid)
                            ->where('deleted', '0')
                            ->first();
                    } else {
                        $createdbyinfo = DB::table('teacher')
                            ->where('userid', $comment->userid)
                            ->where('deleted', '0')
                            ->first();
                    }

                    $comment->picurl = '';
                    if ($createdbyinfo) {
                        $nameofthecommentor .= ucwords(strtolower($createdbyinfo->firstname)) . ' ';
                        $nameofthecommentor .= ucwords(strtolower($createdbyinfo->lastname)) . ' ';
                        $nameofthecommentor .= $createdbyinfo->suffix . ' ';

                        $comment->picurl = $createdbyinfo->picurl;
                    }
                    $comment->name = $nameofthecommentor;

                    if (date('Y-m-d', strtotime($comment->createddatetime)) == date('Y-m-d')) {
                        $comment->datestring = date('h:i A', strtotime($comment->createddatetime)) . ' Today';
                    } else {
                        $comment->datestring = date('F d, Y h:i A', strtotime($comment->createddatetime));
                    }
                }
            }

            return $comments;
        } elseif ($request->get('action') == 'commentdelete') {
            try {
                DB::table('schoolfolder_comments')
                    ->where('id', $request->get('commentid'))
                    ->update([
                        'deleted' => 1,
                        'deletedby' => auth()->user()->id,
                        'deleteddatetime' => date('Y-m-d H:i:s')
                    ]);

                return 1;
            } catch (\Exception $error) {
                return 0;
            }
        }

    }
    public function viewfolder(Request $request)
    {
        $currentPortal = Session::get('currentPortal');
        $refid = DB::table('usertype')->where('id', $currentPortal)->value('refid');

        // Determine the layout to extend based on the current portal
        if ($currentPortal == 1) {
            $extends = "teacher.layouts.app";
        } elseif ($currentPortal == 2) {
            $extends = "principalsportal.layouts.app2";
        } elseif (in_array($currentPortal, [3, 8])) {
            $extends = "registrar.layouts.app";
        } elseif (in_array($currentPortal, [4, 15])) {
            $extends = "finance.layouts.app";
        } elseif ($currentPortal == 6) {
            $extends = "adminPortal.layouts.app2";
        } elseif ($currentPortal == 10 || $refid == 26) {
            $extends = "hr.layouts.app";
        } elseif ($currentPortal == 12) {
            $extends = "adminITPortal.layouts.app";
        } elseif ($currentPortal == 14) {
            $extends = "deanportal.layouts.app2";
        } elseif ($currentPortal == 16) {
            $extends = "chairpersonportal.layouts.app2";
        } elseif ($currentPortal == 18) {
            $extends = "ctportal.layouts.app2";
        } elseif ($currentPortal == 7) {
            $extends = "studentPortal.layouts.app2";
        } else {
            $extends = "general.defaultportal.layouts.app";
        }


        // Retrieve folder information
        $folderid = $request->get('folderid');
        $folderinfo = DB::table('schoolfolder')
            ->where('id', $folderid)
            ->first();

        // Retrieve files from the folder
        $files = DB::table('schoolfolder_files')
            ->where('folderid', $folderid)
            ->where('deleted', '0')
            ->orderByDesc('id')
            ->get();

        // Format file creation time and count unseen comments
        foreach ($files as $file) {
            $file->createddatetime = date('Y-m-d h:i A', strtotime($file->createddatetime));

            $comments = DB::table('schoolfolder_comments')
                ->where('fileid', $file->id)
                ->where('deleted', '0')
                ->get();

            $file->unseen = collect($comments)
                ->where('userid', '!=', auth()->user()->id)
                ->where('seen', '0')
                ->count();
        }

        // Initialize variables for portals and users
        $portals = [];
        $users = [];
        $viewtype = '';

        // Check folder visibility type and retrieve corresponding data
        if ($folderinfo->vtype == 2) {
            $portals = DB::table('schoolfolder_visibleto')
                ->select('usertype.*', 'schoolfolder_visibleto.*')
                ->join('usertype', 'schoolfolder_visibleto.usertypeid', '=', 'usertype.id')
                ->where('folderid', $folderinfo->id)
                ->where('schoolfolder_visibleto.deleted', '0')
                ->get();

            if ($portals->isNotEmpty()) {
                $viewtype = $portals[0]->viewtype ?? '';
            }
        }

        if ($folderinfo->vtype == 3) {
            $users = DB::table('schoolfolder_visibletousers')
                ->select('teacher.*', 'schoolfolder_visibletousers.*')
                ->join('teacher', 'schoolfolder_visibletousers.userid', '=', 'teacher.userid')
                ->where('folderid', $folderinfo->id)
                ->where('schoolfolder_visibletousers.deleted', '0')
                ->where('teacher.deleted', '0')
                ->get();

            if ($users->isNotEmpty()) {
                $viewtype = $users[0]->viewtype ?? '';
            }
        }

        // dd($files);

        // Return the view with the necessary data
        return view('schoolfolder.v2.viewfolder')
            ->with('folderinfo', $folderinfo)
            ->with('files', $files)
            ->with('viewtype', $viewtype)
            ->with('portals', $portals)
            ->with('users', $users)
            ->with('extends', $extends);
    }

    public function uploadfile(Request $request)
    {

        // return $request->all();

        if ($request->hasfile('files')) {
            foreach ($request->file('files') as $file) {
                $filesize = filesize($file); // bytes
                $filesize = round($filesize / 1024 / 1024, 1); // megabytes with 1 digit

                echo "The size of your file is $filesize MB.";

                $filename = $file->getClientOriginalName();

                $extension = $file->getClientOriginalExtension();

                $localfolder = 'bct_commons/' . auth()->user()->email;

                if (!File::exists(public_path() . $localfolder)) {

                    $path = public_path($localfolder);

                    if (!File::isDirectory($path)) {

                        File::makeDirectory($path, 0777, true, true);

                    }

                }
                if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct') {
                    $urlFolder = 'bct-schoolfolders.smsaccess.net';
                } else {
                    if (strpos($request->root(), 'http://') !== false) {
                        $urlFolder = str_replace('http://', '', $request->root());
                    } else {
                        $urlFolder = str_replace('https://', '', $request->root());
                    }
                }
                if (!File::exists(dirname(base_path(), 1) . '/' . $urlFolder . '/' . $localfolder)) {

                    $cloudpath = dirname(base_path(), 1) . '/' . $urlFolder . '/' . $localfolder;

                    if (!File::isDirectory($cloudpath)) {

                        File::makeDirectory($cloudpath, 0777, true, true);

                    }

                }

                //$clouddestinationPath = dirname(base_path(), 1).'/'.$urlFolder.'/'.$localfolder;
                //try{

                //$file->move($clouddestinationPath,  pathinfo($filename, PATHINFO_FILENAME).'-'.date('Ymdhis').'.'.$extension);
                //}
                //catch(\Exception $e){


                // }

                $destinationPath = public_path($localfolder . '/');

                try {

                    $file->move($destinationPath, pathinfo($filename, PATHINFO_FILENAME) . '-' . date('Ymdhis') . '.' . $extension);

                } catch (\Exception $e) {


                }
                DB::table('schoolfolder_files')
                    ->insert([
                        'folderid' => $request->get('folderid'),
                        'filename' => pathinfo($filename, PATHINFO_FILENAME) . '-' . date('Ymdhis') . '.' . $extension,
                        'filepath' => $localfolder . '/' . pathinfo($filename, PATHINFO_FILENAME) . '-' . date('Ymdhis') . '.' . $extension,
                        'extension' => $extension,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => date('Y-m-d H:i:s')
                    ]);

            }
            return back();
        }

        // $image = $request->file('file');

        // $filename = time(). '-' . $image->getClientOriginalName();

        // $file = $image->move(public_path().'/uploads/', $filename);

        // $imageName = time().'.'.$image->extension();
        // $image->move(public_path('images'),$imageName);

        // return response()->json(['success'=>$imageName]);
    }
    // public function updatefilestatus(Request $request)
    // {
    //     // return $request->all();
    //     try{
    //         DB::table('schoolfolder_files')
    //             ->where('id', $request->get('fileid'))
    //             // ->where('createdby', auth()->user()->id)
    //             ->update([
    //                 'status'            => $request->get('status'),
    //                 'updatedby'         => auth()->user()->id,
    //                 'updateddatetime'   => date('Y-m-d H:i:s')
    //             ]);
    //         return 1;
    //     }catch(\Exception $error)
    //     {
    //         return 2;
    //     }
    // }
}
