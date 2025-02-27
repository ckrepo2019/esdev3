@extends('studentPortal.layouts.app2')
@section('content')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
<link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<style>
    .color-palette                  { display: block; height: 35px; line-height: 35px; text-align: left; padding-left: .75rem; }
    .color-palette.disabled         { text-align: center; padding-right: 0; display: block; }
    .color-palette-set              { margin-bottom: 15px; }
    .color-palette.disabled span    { display: block; text-align: left; padding-left: .75rem; }
    .color-palette-box h4           { position: absolute; left: 1.25rem; margin-top: .75rem; color: rgba(255, 255, 255, 0.8); font-size: 12px; display: block; z-index: 7; }
    img {
        border-radius: unset !important;
    }
.note-editor .note-dropzone { opacity: 0 !important; }
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
    /* padding-left: 20px; */
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
.note-toolbar{
    display:none;   
}
.note-editor.note-frame {
    border: hidden;
}
.card{
    border: none;
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
    <div id="content">

        @if(DB::table('schoolinfo')->first()->withVC == 1)
        <button type="button" class="btn btn-primary btn-sm classroom mb-2 mr-2" id="{{$classroom->id}}"><i class="fa fa-video"></i> &nbsp;&nbsp;&nbsp;Join Call</button>
        @elseif(DB::table('schoolinfo')->first()->withVC == 2)
        {{-- <button type="button" class="btn btn-primary btn-sm mb-2 mr-2 btn-warning"><small>Note: Download first the MS Teams App</small></button> --}}
        <button type="button" class="btn btn-primary btn-sm classroom mb-2 mr-2" id="{{$classroom->id}}"><i class="fa fa-book"></i> &nbsp;&nbsp;&nbsp;OPEN LMS</button>
        @endif
        <nav class="navbar navbar-expand-lg navbar-light bg-light p-0 mb-2">
            <div class="container-fluid p-0">
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
          {{-- <div class="tab-pane fade show active" id="custom-content-above-studentfiles" role="tabpanel" aria-labelledby="custom-content-above-studentfiles-tab">
            <br/>
            <input type="file" name="files[]" id="fileid" multiple hidden/>
            <button type="button" id="addattachment" class="btn btn-sm btn-info"><i class="fa fa-plus"></i> File/s</button>
            @if(count($myattachments)>0)
                <div class="row">
                    <div class="col-md-12 col-12 col-lg-12">
                        <br/>
                        <table id="studentattachmentstable" class="table table-hover" >
                            <thead>
                                <tr>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($myattachments as $studentattachment)
                                    <tr>
                                        <td style="vertical-align: middle">
                                            <div class="info-box">
                                                <span class="info-box-icon bg-info">
                                                    @if($studentattachment->extension == 'doc' || $studentattachment->extension == 'docx' )
                                                        <img src="{{asset('assets/images/doc.png')}}" alt="" style="width: 40px;">
                                                    @elseif($studentattachment->extension == 'ppt' || $studentattachment->extension == 'pptx' )
                                                        <img src="{{asset('assets/images/ppt.png')}}" alt="" style="width: 40px;" >
                                                    @elseif($studentattachment->extension == 'xls' || $studentattachment->extension == 'xlsx' )
                                                        <img src="{{asset('assets/images/xls.jpg')}}" alt="" style="width: 40px;" >
                                                    @elseif($studentattachment->extension == 'pdf')
                                                        <img src="{{asset('assets/images/pdf.png')}}" alt="" style="width: 40px;" >
                                                    @elseif($studentattachment->extension == 'mp3' || $studentattachment->extension == 'm4a')
                                                        <img src="{{asset('assets/images/audio.png')}}" alt="" style="width: 40px;">
                                                    @elseif($studentattachment->extension == 'mp4')
                                                        <img src="{{asset('assets/images/video.png')}}" alt="" style="width: 40px;">
                                                    @else
                                                        <img src="{{asset($studentattachment->filepath)}}" alt="" style="width: 40px;" >
                                                    @endif
                                                </span>
                                    
                                                <div class="info-box-content">
                                                    <span class="info-box-text">
                                                        @if($studentattachment->extension == 'doc' || $studentattachment->extension == 'docx' ||  $studentattachment->extension == 'mp3' || $studentattachment->extension == 'm4a' || $studentattachment->extension == 'xls' || $studentattachment->extension == 'xlsx')
                                                            <a href="{{asset($studentattachment->filepath)}}" class="eachattachment"> 
                                                        @else
                                                            <a href="#" class="eachattachment" data-toggle="modal" data-target="#modal-show-{{$studentattachment->attachmentid}}">
                                                        @endif
                                                        {{$studentattachment->filename}}
                                                    </a>
                                                    </span>
                                                    <span class="info-box-number">
                                                    <small class="text-muted">
                                                        Date submitted: {{$studentattachment->createddatetime}}
                                                        <br/>
                                                        <button type="button" idenc="{{Crypt::encrypt($studentattachment->attachmentid)}}" id="{{$studentattachment->attachmentid}}" class="btn btn-danger btn-sm deleteattach">Delete</button>
                                                    </small>
                                                    </span>
                                                </div>
                                                <!-- /.info-box-content -->
                                                </div>
                                            <div class="modal fade" id="modal-show-{{$studentattachment->attachmentid}}" style="display: none;" aria-hidden="true">
                                                <div class="modal-dialog modal-xl">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">{{$studentattachment->filename}}</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            @if($studentattachment->extension == 'mp4')
                                                            <div class="embed-responsive embed-responsive-16by9">
                                                                <video controls="true" class="embed-responsive-item">
                                                                <source src="{{asset($studentattachment->filepath)}}" type="video/mp4" />
                                                                </video>
                                                            </div>
                                                            @elseif($studentattachment->extension == 'png' || $studentattachment->extension == 'jpg' )
                                                                <img src="{{asset($studentattachment->filepath)}}" calt="User Image" style="width:100%">
                                                            @elseif($studentattachment->extension == 'mp3')
                                                            <audio
                                                                controls
                                                                src="{{asset($studentattachment->filepath)}}">
                                                            </audio>
                                                            @else
                                                            <iframe style="width: 100%; min-height: 80vh;" src="{{asset($studentattachment->filepath)}}" frameborder="0" allowfullscreen>
                                                            
                                                            </iframe>
                                                            @endif
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                            <!-- /.modal-dialog -->
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
          </div> --}}
          <div class="tab-pane fade show active" id="custom-content-above-classroomfiles" role="tabpanel" aria-labelledby="custom-content-above-classroomfiles-tab">
            @if(count($classattachments)>0)
                <br/>
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
                                    <span class="info-box-number">{{strtoupper($classattachment->extension)}} File</span>
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
            @if(count($classassignments)>0)
                <div class="row">
                    @foreach($classassignments as $classassignment)
                        <div class="col-md-12">
                            <div class="card collapsed-card">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-md-4">
                                            Title : {{strtoupper($classassignment->title)}}
                                        </div>
                                        <div class="col-md-3">
                                            <small>Date & Time due : {{$classassignment->dueto}}</small> 
                                        </div>
                                        <div class="col-md-2">
                                            <small>Perfect Score: {{$classassignment->perfectscore}}</small> 
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-sm btn-info btn-block" data-toggle="modal" data-target="#viewclassassignment{{$classassignment->id}}">View</button>
                                            <div class="modal fade" id="viewclassassignment{{$classassignment->id}}">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <input type="hidden" name="assignmentid" value="{{$classassignment->id}}"/>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <label>Title</label><br/>
                                                                    {{$classassignment->title}}<br/>
                                                                    <label>Instructions</label><br/>
                                                                    <textarea class="textarea" placeholder="Place some text here" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" name="assignmentinstruction" disabled>
                                                                        {{$classassignment->instructions}}
                                                                    </textarea>
                                                                    <script>
                                                                        $(document).ready(function(){
                                                                            $('.textarea').summernote({
                                                                                disableDragAndDrop: true,
                                                                                toolbar:[]
                                                                            })
                                                                            $('.textarea').summernote('disable')
                                                                            $('.note-editable').attr('contenteditable', false)
                                                                        })
                                                                    </script>
                                                                    <br/>
                                                                    @if($classassignment->extension == 'pdf')
                                                                        <object  style="width: 100%;" height="300"  type="application/pdf" data="{{asset($classassignment->filepath)}}?#view=FitH&scrollbar=0&toolbar=0&navpanes=0">
                                                                            <p>File could not open.</p>
                                                                        </object>
                                                                    @elseif($classassignment->extension == 'png' || $classassignment->extension == 'jpg' || $classassignment->extension == 'gif')
                                                                        <img   src="{{asset($classassignment->filepath)}}" style="width: inherit;">
                                                                    @elseif($classassignment->extension == 'mp4' || $classassignment->extension == 'm4a')
                                                                        <video style="width: 100%;"  height="300" controls>
                                                                            <source src="{{asset($classassignment->filepath)}}" type="video/mp4">
                                                                        </video>
                                                                    @endif
                                                                    
                                                                    <a href="{{asset($classassignment->filepath)}}" class="btn btn-default mt-3" download>Download: {{$classassignment->filename}}</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">          
                                            @if($classassignment->status == 0)      
                                                @if(count($classassignment->turnedin) == 0)
                                                    <button type="button" class="btn btn-sm btn-success btn-block turninassignment" id="{{$classassignment->id}}">Turn In</button>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-warning btn-block" data-toggle="modal" data-target="#turnedin{{$classassignment->id}}">View Turned In</button>
                                                    <div class="modal fade" id="turnedin{{$classassignment->id}}">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="turnedinid" value="{{$classassignment->turnedin[0]->id}}"/>
                                                                    <div class="row">
                                                                        @if($classassignment->turnedin[0]->extension == 'pdf')
                                                                            <object  style="width: 100%;" height="500"  type="application/pdf" data="{{asset($classassignment->turnedin[0]->filepath)}}?#view=FitH&scrollbar=0&toolbar=0&navpanes=0">
                                                                                <p>File could not open.</p>
                                                                            </object>
                                                                        @elseif($classassignment->turnedin[0]->extension == 'png' || $classassignment->turnedin[0]->extension == 'jpg' || $classassignment->turnedin[0]->extension == 'gif')
                                                                            <object   style="width: 100%;"   height="300" type="image/gif" data="{{asset($classassignment->turnedin[0]->filepath)}}?#view=FitH&scrollbar=0&toolbar=0&navpanes=0">
                                                                                <p>File could not open.</p>
                                                                            </object>
                                                                        @elseif($classassignment->turnedin[0]->extension == 'mp4' || $classassignment->turnedin[0]->extension == 'm4a')
                                                                            <video style="width: 100%;"  height="300" controls>
                                                                                <source src="{{asset($classassignment->turnedin[0]->filepath)}}" type="video/mp4">
                                                                            </video>
                                                                        @endif
                                                                        
                                                                        <a href="{{asset($classassignment->turnedin[0]->filepath)}}" class="btn btn-default mt-3" download>Download</a>
                                                                        <a href="{{asset($classassignment->turnedin[0]->filepath)}}" class="btn btn-default mt-3" download>Score:  {{$classassignment->turnedin[0]->score}}/{{$classassignment->perfectscore}}</a>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer justify-content-between">
                                                                    @if($classassignment->turnedin[0]->show == 1)
                                                                        <form action="/virtualclassroomdeleteturnedin" method="get">
                                                                            <input type="hidden" name="turnedinid" value="{{$classassignment->turnedin[0]->id}}"/>
                                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                                        </form>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @else
                                                @if(count($classassignment->turnedin) == 0)
                                                    <button type="button" class="btn btn-sm btn-success btn-block" disabled>Turn In</button>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-warning btn-block " disabled>View Turned In</button>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
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
<script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>
<script src="{{asset('assets/scripts/gijgo.min.js')}}" ></script>
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<!-- Select2 -->
<script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
<!-- Summernote -->
<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
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

        // function openDialog() {
        //     document.getElementById('fileid').click();
        // }

        // document.getElementById('addattachment').addEventListener('click', openDialog);

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
                        "<img class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + e.target.fileName + "\" style='width:100px;height:100px;display: inline;'/>" +
                        "<br/><span class=\"remove\"><i class='fa fa-trash'></i></span>" +
                        "</span>"
                        )
                    });
                    fileReader.readAsDataURL(f);
                }
            });
            $(".turninfile").on("change", function(e) {
                var assignmentid = $(this).attr('id');
                $('#assignmentfilescontainer'+assignmentid).empty()
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
                    else if(fileextension == 'mp4' || fileextension == 'm4a')
                    {
                        var altimage = 'onerror="this.onerror = null, this.src=\'{{asset("assets/images/mp4.png")}}\'"';
                    }
                    var fileReader = new FileReader();
                    fileReader.fileName = f.name 
                    fileReader.onload = (function(e) {
                        var file = e.target;
                        $('#assignmentfilescontainer'+assignmentid).append(
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
                        $('button#'+id+'').closest('tr').remove()
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
        $(document).on('click','.turninassignment', function(){
            Swal.fire({
                html:   '<form action="/virtualclassroomsubmitassignment" method="POSt" name="submitassignment" enctype="multipart/form-data">'+
                            '@csrf'+
                            '<label>Submit Assignment</label>'+
                            '<input type="file" name="submitfile" class="form-control"/>'+
                            '<input type="hidden" name="assignmentid" value="'+$(this).attr('id')+'" class="form-control"/>'+
                        '</form>',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Turn In',
                preConfirm: () => {
                    if($('input[name="submitfile"]').val() == "")
                    {
                            Swal.showValidationMessage(
                                "No file chosen!"
                            );
                    }
                }
            }).then((result) => {
                if (result.value) {
                    $('form[name="submitassignment"]').submit();
                }
            })
        })
        $(document).on('click', '.classroom',function(){
            var codex = $(this).attr('id');
            
            @if(DB::table('schoolinfo')->first()->withVC == 1)
            window.open('/virtualclassroom'+'/'+codex+'','newwindow','width=700,height=700,top=0, left=960');
            @elseif(DB::table('schoolinfo')->first()->withVC == 2)
            window.open('https://teams.microsoft.com/_?culture=en-us&country=US&lm=deeplink&lmsrc=homePageWeb&cmpid=WebSignIn','width=700,height=700,top=0, left=960');'width=700,height=700,top=0, left=960');
            Swal.fire({
                // title: 'Launching MS Teams ...',
                text: "MS Teams don't run in Incognito",
                icon: 'warning',
                showCancelButton: false,
                showConfirmButton: false
            })
            // $.ajax({
            //     url:'/virtualclassroom'+'/'+codex,
            //     type: 'GET'
            // })
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
            // window.open('/virtualclassroom'+'/'+codex+'','newwindow','width=700,height=700,top=0, left=960');
        })

    })
</script>
@endsection