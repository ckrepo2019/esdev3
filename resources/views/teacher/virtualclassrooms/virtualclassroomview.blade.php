@extends('teacher.layouts.app')
@section('content')
<!-- daterange picker -->
<link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
<link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>
<style>
    .color-palette                  { display: block; height: 35px; line-height: 35px; text-align: left; padding-left: .75rem; }
    .color-palette.disabled         { text-align: center; padding-right: 0; display: block; }
    .color-palette-set              { margin-bottom: 15px; }
    .color-palette.disabled span    { display: block; text-align: left; padding-left: .75rem; }
    .color-palette-box h4           { position: absolute; left: 1.25rem; margin-top: .75rem; color: rgba(255, 255, 255, 0.8); font-size: 12px; display: block; z-index: 7; }
    img {
    border-radius: unset !important;
}
img {
    /* vertical-align: unset !important; */
    border-style: none;
}
li{
    list-style: unset;
}

.imageThumb {
        max-height: 75px;
        border: 2px solid;
        padding: 1px;
        cursor: pointer;
        }
        .pip {
        display: inline-block;
        margin: 10px 10px 0 0;
        width: 50%;
        }
        .remove {
        display: block;
        background: #444;
        border: 1px solid black;
        color: white;
        text-align: center;
        cursor: pointer;
        }
        .remove:hover {
        background: white;
        color: black;
        }

        
#studentstable_wrapper{
    width: 100%;
}
.customwrapper {
    display: flex;
    width: 100%;
    align-items: stretch;
}

#sidebar {
    min-width: 250px;
    max-width: 250px;
    /* background: #7386D5; */
    /* color: #fff; */
    transition: all 0.3s;
}

#sidebar.active {
    margin-left: -266px;
}

#sidebar .sidebar-header {
    padding: 2px;
    /* background: #6d7fcc; */
}

#sidebar ul.components {
    padding: 20px 0;
    border-bottom: 1px solid #47748b;
}

#sidebar ul p {
    color: #fff;
    padding: 10px;
}

#sidebar ul li a {
    padding: 10px;
    font-size: 1.1em;
    display: block;
}

#sidebar ul li a:hover {
    /* color: #7386D5; */
    background: #fff;
}

#sidebar ul li.active>a,
a[aria-expanded="true"] {
    color: #fff;
    /* background: #6d7fcc; */
}

a[data-toggle="collapse"] {
    position: relative;
}

.dropdown-toggle::after {
    display: block;
    position: absolute;
    top: 50%;
    right: 20px;
    transform: translateY(-50%);
}

ul ul a {
    font-size: 0.9em !important;
    padding-left: 30px !important;
    /* background: #6d7fcc; */
}

ul.CTAs {
    padding: 20px;
}

ul.CTAs a {
    text-align: center;
    font-size: 0.9em !important;
    display: block;
    border-radius: 5px;
    margin-bottom: 5px;
}

a.download {
    background: #fff;
    /* color: #7386D5; */
}

a.article,
a.article:hover {
    /* background: #6d7fcc !important; */
    color: #fff !important;
}

/* ---------------------------------------------------
    CONTENT STYLE
----------------------------------------------------- */

#content {
    width: 100%;
    padding-left: 20px;
    min-height: 100vh;
    transition: all 0.3s;
}

/* ---------------------------------------------------
    MEDIAQUERIES
----------------------------------------------------- */

@media (max-width: 768px) {
    #sidebar {
        margin-left: -266px;
    }
    #sidebar.active {
        margin-left: 0;
    }
    #sidebarCollapse span {
        display: none;
    }
}
        .select2-container {
            z-index: 9999;
            margin: 0px;
        }
        #studentattachmentstable thead {    
    display:none;   
}
.card{
    border: none;
    box-shadow: none;
}
</style>

<div>
    <nav class="" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item"><a href="/virtualclassroomindex">Virtual Classrooms</a></li>
            <li class="active breadcrumb-item" aria-current="page">{{$classroom->classroomname}}</li>
        </ol>
    </nav>
</div>
<div class="customwrapper">
    <!-- Sidebar  -->
    <nav id="sidebar">
        <div class="sidebar-header">
            Students ({{count($students)}})
            {{-- <button type="button" class="btn btn-block btn-sm btn-default" id="addstudent"><i class="fa fa-plus"></i> Student/s</button> --}}
            &nbsp;
        </div>

        @if(count($students)>0)                    
        <table id="studentstable" class="table table-hover" >
            <thead>
                <tr>
                    <th>
                        <input type="text" id="searchstudent" class="form-control" placeholder="Search">
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                    <tr>
                        <td style="font-size: 11px; vertical-align: middle;">
                            @php
                                $number = rand(1,3);
                                if(strtolower($student->gender) == 'female'){
                                    $avatar = 'avatar/S(F) '.$number.'.png';
                                }
                                else{
                                    $avatar = 'avatar/S(M) '.$number.'.png';
                                }
                            @endphp
                            <img src="{{$student->picurl}}" class="img-circle elevation-2" onerror="this.onerror = null, this.src='{{asset($avatar)}}'" alt="User Image" style="width:40px">
                            <span style="margin: auto;">
                                {{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}
                                @if($student->crossover == 1)
                                    <br/>
                                    <span class="badge badge-success">ADDED</span> <span id="{{$student->id}}" idenc="{{Crypt::encrypt($student->id)}}" class="badge badge-danger deleteaddedstudent">DELETE</span>
                                @endif
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    </nav>

    <!-- Page Content  -->
    <div id="content">

        <nav class="navbar navbar-expand-lg navbar-light bg-light p-0 mb-2">
            <div class="container-fluid p-0">

                <button type="button" id="sidebarCollapse" class="btn btn-info btn-sm">
                    <i class="fa fa-users"></i>
                    <span>Students List</span>
                </button>
                <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-cogs"></i>
                </button>

               
                <div class="row">                    
                    <form action="/virtualclassroomaddfiles/{{Crypt::encrypt("manual")}}" method="post" name="submitfiles"  enctype="multipart/form-data">
                        @csrf
                        <input name="classroomid" value="{{$classroom->id}}" hidden/>
                        <input type="file" id="fileid" name="files[]" multiple accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint,text/plain, application/pdf, image/*,video/*,audio/*" hidden/>
                    </form>
                </div>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="nav navbar-nav ml-auto mt-2">
                        <li class="nav-item">
                            @if(DB::table('schoolinfo')->first()->withVC == 1)
                            <button type="button" class="btn btn-primary btn-sm classroom mb-2 mr-2" id="{{$classroom->id}}"><i class="fa fa-video"></i> Call</button>
                            @elseif(DB::table('schoolinfo')->first()->withVC == 2)
                            {{-- <button type="button" class="btn btn-primary btn-sm mb-2 mr-2 btn-warning"><small>Note: Download first the MS Teams App</small></button> --}}
                            <button type="button" class="btn btn-primary btn-sm classroom mb-2 mr-2" id="{{$classroom->id}}"><i class="fa fa-book"></i> OPEN LMS</button>
                            @endif
                        </li>
                        <li class="nav-item">
                            <button type="button" class="btn btn-sm btn-primary mb-2 mr-2" id="addstudent"><i class="fa fa-plus"></i>  Student/s</button>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        
        <ul class="nav nav-tabs" id="custom-content-above-tab" role="tablist">
          {{-- <li class="nav-item">
            <a class="nav-link active" id="custom-content-above-studentfiles-tab" data-toggle="pill" href="#custom-content-above-studentfiles" role="tab" aria-controls="custom-content-above-studentfiles" aria-selected="true">Submitted Files</a>
          </li> --}}
          <li class="nav-item">
            <a class="nav-link active" id="custom-content-above-classroomfiles-tab" data-toggle="pill" href="#custom-content-above-classroomfiles" role="tab" aria-controls="custom-content-above-classroomfiles" aria-selected="true">Classroom Files</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="custom-content-above-assignments-tab" data-toggle="pill" href="#custom-content-above-assignments" role="tab" aria-controls="custom-content-above-assignments" aria-selected="false">Assignments</a>
          </li>
        </ul>
        <div class="tab-content" id="custom-content-above-tabContent">
          <div class="tab-pane fade show active" id="custom-content-above-classroomfiles" role="tabpanel" aria-labelledby="custom-content-above-classroomfiles-tab">
            <br/>
            <button type="button" class="btn btn-sm btn-primary" id="addattachment"><i class="fa fa-plus"></i>  File/s</button>
            @if(count($classattachments)>0)
                <br/>&nbsp;
                <div class="row">
                    @foreach($classattachments as $classattachment)
                        <div class="col-md-3 col-sm-6 col-12 eachfile" >
                            <div class="info-box"  >
                                <span class="info-box-icon bg-info">
                                
                                    @if($classattachment->extension == 'doc' || $classattachment->extension == 'docx' )
                                        <img src="{{asset('assets/images/doc.png')}}" alt="" style="width: 40px;">
                                    @elseif($classattachment->extension == 'ppt' || $classattachment->extension == 'pptx' )
                                        <img src="{{asset('assets/images/ppt.png')}}" alt="" style="width: 40px;" >
                                    @elseif($classattachment->extension == 'xls' || $classattachment->extension == 'xlsx' )
                                        <img src="{{asset('assets/images/xls.jpg')}}" alt="" style="width: 40px;" >
                                    @elseif($classattachment->extension == 'pdf')
                                        <img src="{{asset('assets/images/pdf.png')}}" alt="" style="width: 40px;" >
                                    @elseif($classattachment->extension == 'mp3' || $classattachment->extension == 'm4a')
                                        <img src="{{asset('assets/images/audio.png')}}" alt="" style="width: 40px; ">
                                    @elseif($classattachment->extension == 'mp4')
                                        <img src="{{asset('assets/images/video.png')}}" alt="" style="width: 40px; ">
                                    @else
                                        <img src="{{asset($classattachment->filepath)}}" alt="" style="width: 40px;" >
                                    @endif    
                                </span>
                                <div class="info-box-content" style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis; ">
                                    
                                    @if($classattachment->extension == 'doc' || $classattachment->extension == 'docx' ||  $classattachment->extension == 'mp3' || $classattachment->extension == 'm4a' || $classattachment->extension == 'xls' || $classattachment->extension == 'xlsx')
                                        <a href="{{asset($classattachment->filepath)}}" class="eachattachment m-0 p-0">
                                    @else
                                        <a href="#" class="eachattachment m-0 p-0" data-toggle="modal" data-target="#modal-show-{{$classattachment->id}}">
                                    @endif      
                                        <span class="info-box-text">{{$classattachment->filename}}</span>
                    
                                    </a>
                                    <span class="info-box-number">{{strtoupper($classattachment->extension)}} File <button type="button" idenc="{{Crypt::encrypt($classattachment->id)}}" id="{{$classattachment->id}}" class="float-right btn btn-danger btn-sm deleteattach"><i class="fa fa-trash"></i></button></span>
                                </div>
                            </div>
                            <div class="modal fade" id="modal-show-{{$classattachment->id}}" style="display: none;" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            {{-- <h4 class="modal-title" style="overflow-wrap: anywhere">{{$classattachment->filename}}</h4> --}}
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            @if($classattachment->extension == 'mp4')
                                                <div class="embed-responsive embed-responsive-16by9">
                                                    <video controls="true" class="embed-responsive-item">
                                                    <source src="{{asset($classattachment->filepath)}}" type="video/mp4" />
                                                    </video>
                                                </div>
                                            @elseif($classattachment->extension == 'jpg' || $classattachment->extension == 'png' || $classattachment->extension == 'gif')
                                                <img src="{{asset($classattachment->filepath)}}" alt="" style="width: 100%;" >
                                            @else
                                            <embed style="width: -webkit-fill-available; min-height: 80vh;" src="{{asset($classattachment->filepath)}}" frameborder="0" allowfullscreen/>
                                            @endif
                                        </div>
                                        <div class="modal-footer justify-content-between">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            <a href="{{asset($classattachment->filepath)}}" class="btn btn-default" download>Download</a>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                            <!-- /.modal-dialog -->
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
          </div>
            <div class="tab-pane fade" id="custom-content-above-assignments" role="tabpanel" aria-labelledby="custom-content-above-assignments-tab">
                <div class="row" id="createassignmentcontainer">
                    <br/>
                    <div class="col-12">
                        
                        <form action="/virtualclassroomcreateassignment" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card collapsed-card">
                                <div class="card-header">
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-sm btn-success" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                        Create Assignment</button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-7">
                                                    <label>Title</label><br/>
                                                    <input type="text" class="form-control " name="assignmenttitle" required/>
                                                </div>
                                                <div class="col-md-5">
                                                    <label>Points</label><br/>
                                                    <input type="number" step="any" class="form-control float-right" name="perfectscore" required/>
                                                </div>
                                                <br/>
                                                <div class="col-md-12">
                                                    <label>Submission date</label><br/>
                                                    <input type="text" class="form-control float-right duedate" name="duedatetime"/>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <label>Instructions</label><br/>
                                                    <textarea class="textarea" placeholder="Place some text here"
                                                    style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" name="assignmentinstruction"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row mb-3">
                                                <label>Upload file</label><br/>
                                                <input type="hidden" name="classroomid" value="{{$classroom->id}}"/>
                                                <input type="file" class="form-control" name="assignmentfile" />
                                                <br/>
                                                <div id="assignmentfilescontainer"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="submit" id="btncreate" class="btn btn-primary btn-sm float-right">
                                                Publish Assignment
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @if(count($classassignments)>0)
                    <div class="row">
                        @foreach($classassignments as $classassignment)
                            <div class="col-md-12">
                                <div class="card collapsed-card">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-md-3">
                                                Title : {{strtoupper($classassignment->title)}}
                                            </div>
                                            <div class="col-md-2">
                                                <small>Date created : {{$classassignment->createddatetime}}</small> 
                                            </div>
                                            <div class="col-md-2">
                                                <small>Perfect Score : {{$classassignment->perfectscore}}</small> 
                                            </div>
                                            <div class="col-md-2">
                                                <small>Turned In : {{count($classassignment->turnedin)}}</small>
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" class="btn btn-sm btn-info btn-block" data-toggle="modal" data-target="#viewclassassignment{{$classassignment->id}}">View</button>
                                                <div class="modal fade" id="viewclassassignment{{$classassignment->id}}">
                                                    <div class="modal-dialog modal-xl">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="/virtualclassroomeditassignment" method="get" id="editclassassignment{{$classassignment->id}}">
                                                                    <input type="hidden" name="assignmentid" value="{{$classassignment->id}}"/>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <label>Title</label>
                                                                            <input type="text" class="form-control form-control-sm" name="assignmenttitle" value="{{$classassignment->title}}"/>
                                                                            <label>Instructions</label><br/>
                                                                            <textarea class="textarea" placeholder="Place some text here" style="width: 100%; height: 100px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" name="assignmentinstruction">
                                                                                {{$classassignment->instructions}}
                                                                            </textarea>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label>Start date & time - Due date & time</label><br/>
                                                                            <input type="text" class="form-control float-right duedate" value="{{$classassignment->duefrom}} - {{$classassignment->dueto}}" name="duedatetime"/><br/>
                                                                            <label>Perfect Score</label><br/>
                                                                            <input type="text" class="form-control float-right" value="{{$classassignment->perfectscore}}" name="perfectscore"/><br/>
                                                                            @if($classassignment->extension == 'pdf')
                                                                                <a href="{{asset($classassignment->filepath)}}" download>Download: {{$classassignment->filename}}</a>
                                                                                <object  style="width: 100%;" height="300"  draggable="false" type="application/pdf" data="{{asset($classassignment->filepath)}}?#view=FitH&scrollbar=0&toolbar=0&navpanes=0">
                                                                                    <p>File could not open.</p>
                                                                                </object>
                                                                            @elseif($classassignment->extension == 'png' || $classassignment->extension == 'jpg' || $classassignment->extension == 'gif')
                                                                                <a href="{{asset($classassignment->filepath)}}" download>Download: {{$classassignment->filename}}</a>
                                                                                {{-- <object   style="width: 100%;"   draggable="false" height="300" type="image/gif" data="{{asset($classassignment->filepath)}}?#view=FitH&scrollbar=0&toolbar=0&navpanes=0">
                                                                                    <p>File could not open.</p>
                                                                                </object> --}}
                                                                                <img   src="{{asset($classassignment->filepath)}}" style="width: inherit;" draggable="false">
                                                                            @elseif($classassignment->extension == 'mp4' || $classassignment->extension == 'm4a')
                                                                                <a href="{{asset($classassignment->filepath)}}" download>Download: {{$classassignment->filename}}</a>
                                                                                <video style="width: 100%;"  height="300" controls draggable="false">
                                                                                    <source src="{{asset($classassignment->filepath)}}" type="video/mp4">
                                                                                </video>
                                                                            @else
                                                                                <a href="{{asset($classassignment->filepath)}}" download>Download: {{$classassignment->filename}}</a>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <div class="modal-footer justify-content-between">
                                                                <form action="/virtualclassroomdeleteassignment" method="get">
                                                                    <input type="hidden" name="assignmentid" value="{{$classassignment->id}}"/>
                                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                                </form>
                                                                <button type="button" class="btn btn-warning updateclassassignment" id="{{$classassignment->id}}">Update</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                @if(count($classassignment->turnedin) == 0)
                                                    <button type="button" class="btn btn-sm btn-success btn-block" disabled>Turned In</button>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-success btn-block" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">Turned In</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        @if(count($classassignment->turnedin) > 0)
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <table class="turnedintable table">
                                                        <thead>
                                                            <tr>
                                                                <th>&nbsp;</th>
                                                                <th>Date submitted</th>
                                                                <th>Score</th>
                                                                <th>&nbsp;</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($classassignment->turnedin as $turnedin)
                                                                <tr>
                                                                    <td>
                                                                        <strong>{{$turnedin->lastname}}, {{$turnedin->firstname}} {{$turnedin->middlename}} {{$turnedin->suffix}}</strong>
                                                                    </td>
                                                                    <td>{{$turnedin->createddatetime}}</td>
                                                                    <td>{{$turnedin->score}}/{{$classassignment->perfectscore}}</td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-sm btn-warning btn-block" data-toggle="modal" data-target="#view{{$turnedin->turnedinid}}">View</button>
                                                                        <div class="modal fade" id="view{{$turnedin->turnedinid}}">
                                                                            <div class="modal-dialog modal-lg">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">&times;</span>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                        @if($turnedin->score == null)
                                                                                        <form action="/virtualclassroomscore/set" method="get" >
                                                                                        @else
                                                                                        <form action="/virtualclassroomscore/update" method="get" >
                                                                                        @endif
                                                                                            <input type="hidden" name="turnedinid" value="{{$turnedin->turnedinid}}"/>
                                                                                            @if($turnedin->extension == 'pdf')
                                                                                                <object  style="width: 100%; height: 500px;"   type="application/pdf" data="{{asset($turnedin->filepath)}}?#view=FitH&scrollbar=0&toolbar=0&navpanes=0" draggable="false">
                                                                                                    <p>File could not open.</p>
                                                                                                </object>
                                                                                                <br/>
                                                                                                <div class="row">
                                                                                                    <div class="col-md-6">
                                                                                                        <a href="{{asset($turnedin->filepath)}}"  class="btn btn-default mt-3" download>Download</a>
                                                                                                    </div>
                                                                                                    <div class="col-md-6">
                                                                                                        <div class="form-group mt-3">
                                                                                        
                                                                                                            <div class="input-group my-colorpicker2 colorpicker-element" data-colorpicker-id="2">
                                                                                                                <input type="number" class="form-control" name="score" step="any" value="{{$turnedin->score}}" required>
                                                                                        
                                                                                                                <div class="input-group-append">
                                                                                                                    <span class="input-group-text">
                                                                                                                        /{{$classassignment->perfectscore}}
                                                                                                                    </span>
                                                                                                                </div>

                                                                                                            <div class="input-group-append p-0">
                                                                                                                <span class="input-group-text p-0">
                                                                                                                    <button type="submit" class="btn btn-success btn-sm m-0">Submit score</button>
                                                                                                                </span>
                                                                                                            </div>
                                                                                                            </div>
                                                                                                            <!-- /.input group -->
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            @elseif($turnedin->extension == 'png' || $turnedin->extension == 'jpg' || $turnedin->extension == 'gif')
                                                                                                {{-- <object   style="width: 100%;"    type="image/gif" data="{{asset($turnedin->filepath)}}?#view=FitH&scrollbar=0&toolbar=0&navpanes=0">
                                                                                                    <p>File could not open.</p>
                                                                                                </object> --}}
                                                                                                <img   src="{{asset($turnedin->filepath)}}" style="width: 100%;">
                                                                                                <div class="row" draggable="false">
                                                                                                    <div class="col-md-6">
                                                                                                        <a href="{{asset($turnedin->filepath)}}"  class="btn btn-default mt-3" download>Download</a>
                                                                                                    </div>
                                                                                                    <div class="col-md-6">
                                                                                                        <div class="form-group mt-3">
                                                                                        
                                                                                                            <div class="input-group my-colorpicker2 colorpicker-element" data-colorpicker-id="2">
                                                                                                                <input type="number" class="form-control setscore" name="score" step="2" max="{{$classassignment->perfectscore}}" value="{{$turnedin->score}}" required>
                                                                                        
                                                                                                                <div class="input-group-append">
                                                                                                                    <span class="input-group-text">
                                                                                                                        /{{$classassignment->perfectscore}}
                                                                                                                    </span>
                                                                                                                </div>

                                                                                                            <div class="input-group-append">
                                                                                                                <span class="input-group-text p-0">
                                                                                                                    <button type="submit" class="btn btn-success btn-sm m-0">Submit score</button>
                                                                                                                </span>
                                                                                                            </div>
                                                                                                            </div>
                                                                                                            <!-- /.input group -->
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            @elseif($turnedin->extension == 'mp4' || $turnedin->extension == 'm4a')
                                                                                                <video style="width: 100%;"   controls draggable="false">
                                                                                                    <source src="{{asset($turnedin->filepath)}}" type="video/mp4">
                                                                                                </video>
                                                                                                <div class="row">
                                                                                                    <div class="col-md-6">
                                                                                                        <a href="{{asset($turnedin->filepath)}}"  class="btn btn-default mt-3" download>Download</a>
                                                                                                    </div>
                                                                                                    <div class="col-md-6">
                                                                                                        <div class="form-group mt-3">
                                                                                        
                                                                                                            <div class="input-group my-colorpicker2 colorpicker-element" data-colorpicker-id="2">
                                                                                                                <input type="number" class="form-control" name="score" step="any" value="{{$turnedin->score}}" required>
                                                                                        
                                                                                                                <div class="input-group-append">
                                                                                                                    <span class="input-group-text">
                                                                                                                        /{{$classassignment->perfectscore}}
                                                                                                                    </span>
                                                                                                                </div>

                                                                                                            <div class="input-group-append p-0">
                                                                                                                <span class="input-group-text p-0">
                                                                                                                    <button type="submit" class="btn btn-success btn-sm m-0">Submit score</button>
                                                                                                                </span>
                                                                                                            </div>
                                                                                                            </div>
                                                                                                            <!-- /.input group -->
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            @else
                                                                                                <div class="row">
                                                                                                    <div class="col-md-6">
                                                                                                        <a href="{{asset($turnedin->filepath)}}"  class="btn btn-default mt-3" download>Download</a>
                                                                                                    </div>
                                                                                                    <div class="col-md-6">
                                                                                                        <div class="form-group mt-3">
                                                                                        
                                                                                                            <div class="input-group my-colorpicker2 colorpicker-element" data-colorpicker-id="2">
                                                                                                                <input type="number" class="form-control" name="score" step="any" value="{{$turnedin->score}}" required>
                                                                                        
                                                                                                                <div class="input-group-append">
                                                                                                                    <span class="input-group-text">
                                                                                                                        /{{$classassignment->perfectscore}}
                                                                                                                    </span>
                                                                                                                </div>
                                                                                                            <div class="input-group-append p-0">
                                                                                                                <span class="input-group-text p-0">
                                                                                                                    <button type="submit" class="btn btn-success btn-sm m-0">Submit score</button>
                                                                                                                </span>
                                                                                                            </div>
                                                                                                            </div>
                                                                                                            <!-- /.input group -->
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            @endif
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <script>
                                                                            
                                                                            $('.setscore').on('mouseup keyup', function () {
                                                                            $(this).val(Math.min('{{$classassignment->perfectscore}}', Math.max(0, $(this).val())));
                                                                            });
                                                                        </script>
                                                                        {{-- <p>File could not open.</p> --}}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
{{-- <form action="/virtualclassroomviewfile" method="get" name="submitviewfile">
    <input type="hidden" name="classroomid" value="{{$classroom->id}}"/>
    <input type="hidden" name="fileid" value=""/>
</form> --}}
<script type="text/javascript" src="{{asset('assets/scripts/main.js')}}"></script>
<script src="{{asset('assets/scripts/gijgo.min.js')}}" ></script>
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<!-- Select2 -->
<script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
<!-- Summernote -->
<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
<!-- InputMask -->
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
<!-- date-range-picker -->
<script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
<script>
    $(document).ready(function(){
        $('#sidebar').toggleClass('active');

        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
        });

        $('body').addClass('sidebar-collapse');

        var studentstable = $("#studentstable").DataTable({
            pageLength : 10,
            // lengthMenu: ['Show All'],
            "paging"    : false,
            "bLengthChange": false,
            "bInfo": false,
            "dom": 'lrtip',
            "bfilter": false
        });

        $('#searchstudent').keyup(function(){
            studentstable.search($(this).val()).draw() ;
        })

        $('#studentattachmentstable').DataTable({
            "paging"    : false,
            "bLengthChange": false,
            "bInfo": false,
            // "dom": 'lrtip',"sDom": 'rt'
            "bfilter": false
        });

        $('.turnedintable').DataTable({
            "paging"    : false,
            "bLengthChange": false,
            "bInfo": false,
            // "dom": 'lrtip',"sDom": 'rt'
            "bfilter": false
        });

        $('.grades').DataTable({
            "paging"    : false,
            "bLengthChange": false,
            "bInfo": false,
            // "dom": 'lrtip',"sDom": 'rt'
            "bfilter": false
        });
        function openDialog() {
            document.getElementById('fileid').click();
        }

        document.getElementById('addattachment').addEventListener('click', openDialog);

        if (window.File && window.FileList && window.FileReader) {
            $("#fileid").on("change", function(e) {
                var clickedButton = this;
                var files = e.target.files,
                filesLength = files.length;
                addattachment=filesLength;
                var swalpreview = Swal.fire({
                    html: '<div class="row">'+
                            '<div class="col-12" id="attachmentscontainer">'+
                            '</div>'+
                        '</div>',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Upload',
                    showCancelButton: true
                }).then((confirm) => {
                    if (confirm.value) {
                        $('form[name="submitfiles"]').submit()
                    }else {
                        $('#fileid').val('')
                    }
                })
                for (var i = 0; i < filesLength; i++) {
                    var f = files[i]
                    var fileextension = f.name.split('.').pop()
                    var fileReader = new FileReader();
                    fileReader.fileName = f.name 
                    fileReader.onload = (function(e) {
                        var file = e.target;
                        $('#attachmentscontainer').append(
                        "<span class=\"pip\">" +
                        "<img  src=\"" + e.target.result + "\" title=\"" + e.target.fileName + "\" style='width:50px;height:50px;display: inline;'/>" +
                        "<br/><span class=\"remove\"><i class='fa fa-trash'></i></span>" +
                        "</span>"
                        )
                    });
                    fileReader.readAsDataURL(f);
                }
            });
            $("input[name='assignmentfile']").on("change", function(e) {
                // $('#btncreate').attr('disabled',false)
                $('#assignmentfilescontainer').empty();
                var clickedButton = this;
                var files = e.target.files,
                filesLength = files.length;
                addattachment=filesLength;
                for (var i = 0; i < filesLength; i++) {
                    var f = files[i]
                    var fileextension = f.name.split('.').pop()
                    if(fileextension == 'pdf')
                    {
                        var altimage = 'onerror="this.onerror = null, this.src=\'{{asset("assets/images/pdf.png")}}\'"';
                    }
                    else if(fileextension == 'doc' || fileextension == 'docx')
                    {
                        var altimage = 'onerror="this.onerror = null, this.src=\'{{asset("assets/images/doc.png")}}\'"';
                    }
                    else if(fileextension == 'xls' || fileextension == 'xlsx')
                    {
                        var altimage = 'onerror="this.onerror = null, this.src=\'{{asset("assets/images/xls.png")}}\'"';
                    }
                    else if(fileextension == 'mp4' || fileextension == 'm4a')
                    {
                        var altimage = 'onerror="this.onerror = null, this.src=\'{{asset("assets/images/mp4.png")}}\'"';
                    }
                    var fileReader = new FileReader();
                    fileReader.fileName = f.name 
                    fileReader.onload = (function(e) {
                        var file = e.target;
                        $('#assignmentfilescontainer').append(
                        '<span class="pip">' +
                        '<img src="'+ e.target.result + '" title="' + e.target.fileName + '"  '+altimage+' style="width:100%; display: inline;"/>' +
                        '<br/><span class="remove"><i class="fa fa-trash"></i></span>' +
                        '</span>'+
                        '<br/>&nbsp;'
                        )
                    });
                    fileReader.readAsDataURL(f);
                }
            });
        } else {
            alert("Your browser doesn't support to File API")
        }
                
        $(document).on('click','.remove',function(){
            $(this).parent(".pip").remove();
                addattachment-=1;
            // return false;
            if(addattachment == 0){
                $('.swal2-container').remove();
                $('#fileid').val('')
            }
        });

        $('.modal').on('hide.bs.modal', function(e) {
            if($(this).find('video').length > 0)
            {
                $(this).find('video')[0].pause();
            }
        });
        
        $(document).on('click', '#addstudent', function(){
            Swal.fire({
                html: '<div class="row">'+
                        '<div class="col-12" id="addstudentscontainer">'+
                            '<select id="studids" name="studids[]" class="select2" multiple="multiple" data-placeholder="Select student/s" style="width: 100%;">'+
                                '@foreach($sectionstudents as $sectionstudent)'+
                                    '<option  value="{{$sectionstudent->userid}}" >{{$sectionstudent->lastname}}, {{$sectionstudent->firstname}} {{$sectionstudent->middlename}} {{$sectionstudent->suffix}}</option>'+
                                '@endforeach'+
                            '</select>'+
                        '</div>'+
                    '</div>',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Add',
                showCancelButton: true,
                onOpen: function () {
                    $('.select2').select2({
                        minimumResultsForSearch: 15,
                    });
                }
            }).then((confirm) => {
                if (confirm.value) {
                    $.ajax({
                        url: '/virtualclassroomaddstudent',
                        type: 'GET',
                        dataType: 'json',
                        data: {
                            classroomid : '{{$classroom->id}}',
                            studids: $('#studids').val()
                        },
                        complete:function(data){
                            if(data.responseText == 'success')
                            {
                                let timerInterval
                                Swal.fire({
                                    title: 'Student/s added successfully!',
                                    timer: 2000,
                                    timerProgressBar: true,
                                    onBeforeOpen: () => {
                                        Swal.showLoading()
                                        timerInterval = setInterval(() => {
                                        const content = Swal.getContent()
                                        if (content) {
                                            const b = content.querySelector('b')
                                            if (b) {
                                            b.textContent = Swal.getTimerLeft()
                                            }
                                        }
                                        }, 100)
                                    },
                                    onClose: () => {
                                        clearInterval(timerInterval)
                                    }
                                })
                            }
                        }
                    })
                }else {
                    $('#fileid').val('')
                }
            })
        })
        $(document).on('click', '.deleteaddedstudent', function(){
            var idenc = $(this).attr('idenc');
            var studid = $(this).attr('id');
            Swal.fire({
                title: 'Are you sure you want to delete this student?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    $('span#'+studid+'').closest('tr').remove()
                    $.ajax({
                        url: '/virtualclassroomdeletestudent',
                        type: 'GET',
                        dataType: 'json',
                        data: {
                            studid : idenc
                        },
                        complete:function(data){
                            if(data.responseText == 'success')
                            {
                                Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                                )
                            }
                        }
                    })
                }
            })
        })
        $(document).on('click', '.deleteattach', function(){
            var attachmentid = $(this).attr('idenc');
            var id = $(this).attr('id');
            Swal.fire({
                title: 'Are you sure you want to delete this file?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    $('button#'+id+'').closest('.eachfile').remove()
                    $.ajax({
                        url: '/virtualclassroomdeleteattachment',
                        type: 'GET',
                        dataType: 'json',
                        data: {
                            attachmentid : attachmentid
                        },
                        complete:function(data){
                            if(data.responseText == 'success')
                            {
                                Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                                )
                            }
                        }
                    })
                }
            })
        })
        
    $('.duedate').daterangepicker({
      timePicker: true,
      timePickerIncrement: 30,
      locale: {
        format: 'MM/DD/YYYY hh:mm A'
      }
    })
        $('.textarea').summernote({
            disableDragAndDrop: true,
            height: "200",
            toolbar: [
                            // [groupName, [list of button]]
                            ['style', ['bold', 'italic', 'underline', 'clear']],
                            ['font', ['strikethrough', 'superscript', 'subscript']],
                            ['fontsize', ['fontsize']],
                            ['color', ['color']],
                            ['para', ['ul', 'ol', 'paragraph']],
                            ['height', ['height']]
                        ]
        })
        $(document).on('click','.updateclassassignment',function(){
            $('form#editclassassignment'+$(this).attr('id')).submit();
        })
        $(document).on('click', '.classroom',function(){
            var codex = $(this).attr('id');
            
            @if(DB::table('schoolinfo')->first()->withVC == 1)
            window.open('/virtualclassroom'+'/'+codex+'','newwindow','width=700,height=700,top=0, left=960');
            @elseif(DB::table('schoolinfo')->first()->withVC == 2)
            window.open('https://teams.microsoft.com/_?culture=en-us&country=US&lm=deeplink&lmsrc=homePageWeb&cmpid=WebSignIn','width=700,height=700,top=0, left=960');
            Swal.fire({
                // title: 'Launching MS Teams ...',
                text: "MS Teams don't run in Incognito",
                icon: 'warning',
                showCancelButton: false,
                showConfirmButton: false
            })
            $.ajax({
                url:'/virtualclassroom'+'/'+codex,
                type: 'GET'
            })
            // Swal.fire({
            //     title: 'Launching MS Teams ...',
            //     text: "Make sure you have downloaded the MS Teams App",
            //     icon: 'warning',
            //     showCancelButton: false,
            //     showConfirmButton: false
            // })
            // $.ajax({
            //     url:'/virtualclassroom'+'/'+codex,
            //     type: 'GET'
            // })
            @endif
        })
    })
    
</script>
@endsection