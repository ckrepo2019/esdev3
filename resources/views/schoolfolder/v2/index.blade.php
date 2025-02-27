@extends($extends)

@section('headerjavascript')
    <link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css')}}">
    <!-- Bootstrap4 Duallistbox -->
    <link rel="stylesheet" href="{{asset('plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css')}}">
    <!-- Ekko Lightbox -->
    <link rel="stylesheet" href="{{asset('plugins/ekko-lightbox/ekko-lightbox.css')}}">
    
<link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
<link href="{{asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
<!-- dropzonejs -->
<link rel="stylesheet" href="{{asset('plugins/dropzone/min/dropzone.min.css')}}">
<link rel="stylesheet" href="{{asset('dist/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('dist/css/select2-bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
@endsection

@section('content')
<style>
    .content-img {
        border-radius: unset !important;
        max-height: 75px !important;
    }
    .shadow{
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
        border: none !important;
    }
    .custom-container-1 {
        max-width: 70%;
        width: 70%;
        float: left;
        height: 100vh;
        max-height: 100vh;
        overflow-x: scroll;
        overflow-y: scroll; 
        background: #EFF0F1;
        overflow: scroll;
    }
    .custom-container-2 {
        max-width: 30%;
        width: 30%;
        height: 100vh;
        max-height: 100vh;
        overflow-x: scroll;
        overflow-y: scroll; 
        background: #EFF0F1;
        overflow: scroll;
    }
    .note-frame{
        border: none !important;
        box-shadow: none !important;
    }
</style>
@php
    $faspriv = DB::table('faspriv')
        ->where('userid', auth()->user()->id)
        ->where('usertype','1')
        ->where('deleted','0')
        ->count();
@endphp
    
@if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct' && auth()->user()->type != 7)

{{-- <div class="custom-container-1"> --}}


    <div class="card shadow mb-0" style=" position:sticky;
    top:80px;
    width:100%;
    z-index:999;">
        <div class="card-header d-flex p-0">
            <h3 class="card-title p-3">Contribution Page</h3>
            <ul class="nav nav-pills ml-auto p-2">
                <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Feed</a></li>
                <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">Post</a></li>
                <li class="nav-item"><a class="nav-link" href="#tab_3" data-toggle="tab">My Folders</a></li>
            </ul>
        </div>
    </div>
        
    <div class="tab-content">
        <div class="tab-pane active" id="tab_1">        
            <div class="card shadow mb-2">
                <div class="card-header">
                    <input type="text" class="form-control" placeholder="Search..." id="input-search"/>
                </div>
            </div>
            @if(count($posts)>0)
                @foreach($posts as $post)
                    <div class="card card-widget shadow card-post" data-string="{{$post->firstname}} {{$post->middlename}} {{$post->lastname}} {{$post->suffix}} {{$post->description}}<">
                        <div class="card-header">
                            <div class="user-block">                            
                                <img src="{{asset($post->picurl)}}" onerror="this.onerror = null, this.src='{{asset('assets/images/avatars/unknown.png')}}'" class="img-circle " alt="User Image">
                                <span class="username"><a href="#">{{$post->firstname}} {{$post->middlename}} {{$post->lastname}} {{$post->suffix}}</a></span>
                                <span class="description">Shared {{--publicly --}} - {{date('h:i A', strtotime($post->createddatetime))}} @if(date('Y-m-d') == date('Y-m-d', strtotime($post->createddatetime))) Today @elseif(date('Y-m-d', strtotime($post->createddatetime)) == \Carbon\Carbon::yesterday()->toDateString()) Yesterday @else {{date('M d, Y', strtotime($post->createddatetime))}} @endif</span>
                            </div>            
                            <div class="card-tools">
                                @if($post->createdby == auth()->user()->id)
                                <button type="button" class="btn btn-tool each-delete-post" data-id="{{$post->id}}">
                                    <i class="fa fa-trash-alt text-danger"></i>
                                </button>
                                @endif
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                                </button>
                            </div>                        
                        </div>                        
                        <div class="card-body">
                            
                            <p>{{$post->description}}</p>
                            @if(count($post->attachments)>0)
                                @foreach($post->attachments as $eachatt)
                                    <div class="attachment-block clearfix" style="border-radius: 10px;">
                                        @if($eachatt->attachtype == 1)
                                            @if($eachatt->fileext == 'png' || $eachatt->fileext == 'jpeg' || $eachatt->fileext == 'jpg' || $eachatt->fileext == 'svg' || $eachatt->fileext == 'gif')
                                            <img class="attachment-img content-img" src="{{asset($eachatt->filepath)}}" alt="Attachment Image">
                                            @elseif($eachatt->fileext == 'mp4')
                                            <img class="attachment-img content-img" src="{{asset('assets/images/icon-video.png')}}" alt="Attachment Image"/>
                                            @elseif($eachatt->fileext == 'doc' || $eachatt->fileext == 'docx')
                                            <img class="attachment-img content-img" src="{{asset('assets/images/doc.png')}}" alt="Attachment Image">
                                            @elseif($eachatt->fileext == 'xls' || $eachatt->fileext == 'xlsx')
                                            <img class="attachment-img content-img" src="{{asset('assets/images/xls (2).png')}}" alt="Attachment Image">
                                            @elseif($eachatt->fileext == 'pdf')
                                            <img class="attachment-img content-img" src="{{asset('assets/images/pdf.png')}}" alt="Attachment Image">
                                            @endif
                                            <div class="attachment-pushed">
                                                <h6 class="attachment-heading">{{$eachatt->title}}</h6>
                                                <div class="attachment-text">
                                                    {{$eachatt->description}}
                                                    <div class="row">
                                                        <div class="col-md-12 text-right" style="font-size: 13px">
                                                            @if($eachatt->fileext == 'pdf' || $eachatt->fileext == 'mp4')
                                                                <a href="#" class="each-att-view" data-toggle="modal" data-target="#modal-view-att-{{$eachatt->id}}">View</a>

                                                                <div class="modal fade" id="modal-view-att-{{$eachatt->id}}">
                                                                    <div class="modal-dialog modal-lg">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h4 class="modal-title">View Attachment</h4>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body text-center"
                                                                            >
                                                                                @if($eachatt->fileext == 'pdf')
                                                                                    <embed src="{{asset($eachatt->filepath)}}" width="100%" height="600" type="application/pdf">
                                                                                @else
                                                                                    <video width="100%" controls>
                                                                                        <source src="{{asset($eachatt->filepath)}}" type="video/mp4">
                                                                                        Your browser does not support the video tag.
                                                                                    </video>
                                                                                @endif
                                                                            </div>
                                                                            <div class="modal-footer justify-content-between">
                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                            </div>
                                                                        </div>
                                                                    
                                                                    </div>
                                                                
                                                                </div>
                                                            @else
                                                            <a href="{{asset($eachatt->filepath)}}" class="each-att-download" target="_blank">Download</a>
                                                            @endif
                                                            @if($eachatt->createdby == auth()->user()->id)
                                                            |<a href="#" class="each-att-delete" data-attid="{{$eachatt->id}}">Delete</a>
                                                            @endif
                                                            
                                                        </div>
                                                    </div>
                                                </div>                                    
                                            </div>  
                                        @else
                                            <i class="fa fa-link attachment-img content-img text-muted" style="font-size: 60px;vertical-align: middle; margin: 10px !important;"></i>
                                            <div class="attachment-pushed">
                                                <h6 class="attachment-heading">{{$eachatt->title}}</h6>
                                                <div class="attachment-text">
                                                    <p><a href="{{$eachatt->filename}}" target="_blank">{{$eachatt->filename}}</a></p>
                                                    <p>{{$eachatt->description}}</p>
                                                    @if($eachatt->createdby == auth()->user()->id)
                                                    <div class="row">
                                                        <div class="col-md-12 text-right" style="font-size: 13px">
                                                            <a href="#" class="each-att-delete">Delete</a>
                                                            
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>                                    
                                            </div>  
                                        @endif                              
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        
                        <div class="card-footer card-comments" id="comment-section-{{$post->id}}">
                            @if(count($post->comments)>0)
                                @foreach($post->comments as $eachcomment)
                                    <div class="card-comment">                            
                                        <img class="img-circle img-sm" src="{{asset($eachcomment->picurl)}}" onerror="this.onerror = null, this.src='{{asset('assets/images/avatars/unknown.png')}}'"  alt="User Image">
                                        <div class="comment-text">
                                            <span class="username">
                                            {{$eachcomment->firstname}} {{$eachcomment->middlename}} {{$eachcomment->lastname}} {{$eachcomment->suffix}}
                                            @php
                                                $datetimecreated = '';
                                                $today = \Carbon\Carbon::now();
                                                if(date('Y-m-d',strtotime($eachcomment->createddatetime)) == $today->toDateString())
                                                {
                                                    $datetimecreated = date('h:i A', strtotime($eachcomment->createddatetime)).' Today';
                                                }
                                                elseif(date('Y-m-d',strtotime($eachcomment->createddatetime)) ==\Carbon\Carbon::yesterday()->toDateString())
                                                {
                                                    $datetimecreated = date('h:i A', strtotime($eachcomment->createddatetime)).' Yesterday';
                                                }else{
                                                    $datetimecreated = date('h:i A M d, Y', strtotime($eachcomment->createddatetime));
                                                }
                                            @endphp
                                            <span class="text-muted float-right">{{$datetimecreated}} @if(auth()->user()->id == $eachcomment->createdby)<button type="button" class="btn btn-sm each-delete-comment" data-id="{{$eachcomment->id}}"><i class="fa fa-trash-alt text-danger"></i></button>@endif</span>
                                            </span>
                                            {{$eachcomment->comment}}
                                        </div>
                                        
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        
                        <div class="card-footer">
                            <img class="img-fluid img-circle img-sm" src="{{asset($post->picurl)}}" onerror="this.onerror = null, this.src='{{asset('assets/images/avatars/unknown.png')}}'" alt="Alt Text">
                            
                            <div class="img-push">                                
                                <div class="input-group" >
                                    <input type="text" class="form-control form-control-sm" {{--placeholder="Press enter to post comment"--}}>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-sm btn-secondary each-btn-comment" data-postid="{{$post->id}}">
                                            Comment
                                        </button>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        
                    </div>
                @endforeach
            @endif
        </div>
        
        <div class="tab-pane" id="tab_2">
            <form action="/schoolfolderv2/publishapost" method="POST"  enctype="multipart/form-data">
                @csrf
                <div class="card mt-3 shadow">
                    <div class="card-header d-flex p-0">
                    <h3 class="card-title p-3">Post to Contribution Page</h3>
                    </div>
                    <div class="card-body pt-0">
                        <textarea id="summernote" name="post-description">
                            
                        </textarea>
                        <button type="button" class="btn btn-secondary btn-sm mb-2" id="btn-add-attachment-file">Attach File</button>
                        <button type="button" class="btn btn-secondary btn-sm mb-2" id="btn-add-attachment-link">Add Link</button>
                        <div id="container-attachments">
                            
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="button" class="btn btn-md btn-secondary" id="btn-submit-post">Publish Post</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="tab-pane" id="tab_3">
            <div class="card shadow ">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12 text-right"><button type="button" class="btn btn-secondary" id="btn-addfolder"><i class="fa fa-plus"></i> Folder</button></div>
                    </div>
                    
                    {{-- <div class="card-tools"> --}}
                    {{-- <button type="button" class="btn btn-primary btn-sm" id="btn-addfolder"><i class="fa fa-plus"></i> Folder</button> --}}
                    {{-- </div> --}}
                </div>
            </div>
            @if(count($sharedfolders)>0)
            <div class="row" >
                <div class="col-md-12 text-right">
                    <button type="button" class="btn btn-outline-info shadow" data-toggle="modal" data-target="#modal-view-sharedfolders"><i class="fa fa-folder-open"></i> Folders shared with me</button>
                    <div class="modal fade" id="modal-view-sharedfolders">
                        <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                        <div class="modal-header">
                        <h4 class="modal-title">Folders shared with me</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                            <div class="modal-body">
                                
                                <div class="row mt-3">
                                    @foreach($sharedfolders as $sharedfolder)
                                        @php
                                            $sharedby = DB::table('teacher')
                                                ->where('userid', $sharedfolder->createdby)
                                                ->where('deleted','0')
                                                ->first();
                                        @endphp
                                        <div class="col-md-3">
                                            <div class="card shadow">
                                            <div class="card-header p-0 pb-2"> 
                                                <div class="row">
                                                    <div class="col-md-12 text-center">
                                                        <span style="font-size: 80px; color: #ddd;"><i class="fa fa-folder"></i></span>
                                                    </div>
                                                </div> 
                                            </div>
                                            
                                            <div class="card-body p-1 text-center each-card-folder" style="cursor: pointer;" data-id="{{$sharedfolder->id}}">
                                                <label style="cursor: pointer;"><small>{{$sharedfolder->foldername}}</small></label><br/>
                                                <label style="cursor: pointer; font-size: 11px;"><small>Shared by: {{$sharedby->lastname}}, {{$sharedby->firstname}}</small></label><br/>
                                            </div>
                                            
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                        
                        </div>
                        
                        </div>
                </div>
            </div>
            <div class="row mt-3">
            @else
                <div class="row">
            @endif
                @if(count($folders)>0)
                    @foreach($folders as $folder)
                        <div class="col-sm-12 col-lg-3">
                            <div class="card shadow">
                            <div class="card-header p-0 pb-2"> 
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <span style="font-size: 80px; color: #ddd;"><i class="fa fa-folder"></i></span>
                                    </div>
                                </div> 
                            </div>
                            
                            <div class="card-body p-1 text-center each-card-folder" style="cursor: pointer;" data-id="{{$folder->id}}">
                                <label style="cursor: pointer;"><small>{{$folder->foldername}}</small></label>
                            </div>
                            
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            
        </div>
            </div>
    </div>
    
    {{-- </div> --}}
{{-- </div> --}}
{{-- <div class="custom-container-2">
    
    <div class="card shadow "style="
    position:fixed; z-index: 999; width: 37%;">
        <div class="card-header">
            <div class="row">
                <div class="col-6"><h4 class="card-title">My Folders</h4></div>
                <div class="col-6 text-right"><button type="button" class="btn btn-primary btn-sm" id="btn-addfolder"><i class="fa fa-plus"></i> Folder</button></div>
            </div>
            
        </div>
    </div>
     --}}
    
    @else


        
    
    <nav class="navbar navbar-expand-md navbar-light shadow" style="border: 1px solid #ddd; color: #ddd !important;">
        <a href="#" class="navbar-brand">
        <span >My Folders</span>
        </a>
        <div class="collapse navbar-collapse order-3" id="navbarCollapse">
        </div>
        <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
            <li class="nav-item text-right">
                @if($create_folder == 1)
                    <button type="button" class="btn btn-primary" id="btn-addfolder"><i class="fa fa-plus"></i> Add Folder</button>
                @endif
            </li>
        </ul> 
    </nav>
    @if(count($sharedfolders)>0)
    <div class="row mt-3">
        <div class="col-md-12 text-right">
            <button type="button" class="btn btn-outline-info shadow" data-toggle="modal" data-target="#modal-view-sharedfolders"><i class="fa fa-folder-open"></i> Folders shared with me</button>
            <div class="modal fade" id="modal-view-sharedfolders">
                <div class="modal-dialog modal-xl">
                <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Folders shared with me</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                 <span aria-hidden="true">&times;</span>
                </button>
                </div>
                    <div class="modal-body">
                        
                        <div class="row mt-3">
                            @foreach($sharedfolders as $sharedfolder)
                                @php
                                    $sharedby = DB::table('teacher')
                                        ->where('userid', $sharedfolder->createdby)
                                        ->where('deleted','0')
                                        ->first();
                                @endphp
                                <div class="col-md-3">
                                    <div class="card shadow">
                                    <div class="card-header p-0 pb-2"> 
                                        <div class="row">
                                            <div class="col-md-12 text-center">
                                                <span style="font-size: 80px; color: #ddd;"><i class="fa fa-folder"></i></span>
                                            </div>
                                        </div> 
                                    </div>
                                    
                                    <div class="card-body p-1 text-center each-card-folder" style="cursor: pointer;" data-id="{{$sharedfolder->id}}">
                                        <label style="cursor: pointer;"><small>{{$sharedfolder->foldername}}</small></label><br/>
                                        <label style="cursor: pointer; font-size: 11px;"><small>Shared by: {{$sharedby->lastname}}, {{$sharedby->firstname}}</small></label><br/>
                                    </div>
                                    
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
                
                </div>
                
                </div>
        </div>
    </div>
    @endif
    <div class="row mt-3">
        @if(count($folders)>0)
            @foreach($folders as $folder)
                <div class="col-md-3">
                    <div class="card shadow">
                    <div class="card-header p-0 pb-2"> 
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <span style="font-size: 80px; color: #ddd;"><i class="fa fa-folder"></i></span>
                            </div>
                        </div> 
                    </div>
                    
                    <div class="card-body p-1 text-center each-card-folder" style="cursor: pointer;" data-id="{{$folder->id}}">
                        <label style="cursor: pointer;"><small>{{$folder->foldername}}</small></label>
                    </div>
                    
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    
    
    @endif

    <div class="modal fade" id="modal-default">
        <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title">Add New Folder</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
         <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <form action="/schoolfolderv2/folder" method="GET">
            @csrf
            <div class="modal-body">
                <input type="hidden" class="form-control" name="action" value="folderadd"/>
                <div class="row">
                    <div class="col-md-12">
                        <label>Folder Name</label>
                        <input type="text" class="form-control" name="foldername" required/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mt-2">
                        <input type="hidden"  name="action" value="folderadd">
                        <label>Visible to</label>
                        <div class="form-group clearfix">
                            <div class="icheck-primary d-inline mr-4">
                            <input type="radio" id="radioPrimary1" name="visibilitytype" value="1">
                            <label for="radioPrimary1">
                                All
                            </label>
                            </div>
                            <div class="icheck-primary d-inline mr-4">
                            <input type="radio" id="radioPrimary4" name="visibilitytype" checked value="0">
                            <label for="radioPrimary4">
                                Only me
                            </label>
                            </div>
                            <div class="icheck-primary d-inline mr-2">
                            <input type="radio" id="radioPrimary2" name="visibilitytype" value="2">
                            <label for="radioPrimary2">
                                Selected Departments
                            </label>
                            </div>
                            <div class="icheck-primary d-inline">
                            <input type="radio" id="radioPrimary3" name="visibilitytype" value="3">
                            <label for="radioPrimary3">
                                Selected Users
                            </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-2" id="container-visibility">
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </form>
        </div>
        
        </div>
        
        </div>
@endsection
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
@section('footerjavascript')
    <script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
    <!-- bootstrap color picker -->
    <script src="{{asset('plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js')}}"></script>
    <!-- Bootstrap4 Duallistbox -->
    <script src="{{asset('plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js')}}"></script>
    <script src="{{asset('plugins/ekko-lightbox/ekko-lightbox.min.js')}}"></script>
    <script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <!-- Filterizr-->
    <!-- dropzonejs -->
    <script src="{{asset('plugins/dropzone/min/dropzone.min.js')}}"></script>
    <script src="{{asset('dist/js/select2.full.min.js')}}"></script>


    <script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
    <script type="text/javascript">
        Dropzone.options.imageUpload = {
            maxFilesize         :       1,
            acceptedFiles: ".jpeg,.jpg,.png,.gif,.pdf"
        };
</script>
    <script type="text/javascript">
        $(document).ready(function(){
            $("#input-search").on("keyup", function() {
                var input = $(this).val().toUpperCase();
                var visibleCards = 0;
                var hiddenCards = 0;

                $(".container").append($("<div class='card-group card-group-filter'></div>"));


                $(".card-post").each(function() {
                    if ($(this).data("string").toUpperCase().indexOf(input) < 0) {

                    $(".card-group.card-group-filter:first-of-type").append($(this));
                    $(this).hide();
                    hiddenCards++;

                    } else {

                    $(".card-group.card-group-filter:last-of-type").prepend($(this));
                    $(this).show();
                    visibleCards++;

                    if (((visibleCards % 4) == 0)) {
                        $(".container").append($("<div class='card-group card-group-filter'></div>"));
                    }
                    }
                });

            });
            $('#summernote').summernote({
                spellCheck: true,
                disableDragAndDrop: true,
                height: 150, 
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
            $('.note-editor').find('.modal[aria-label="Insert Image"]').remove()
            $('.note-editor').find('.modal[aria-label="Insert Video"]').remove()
            $('.note-editor').find('.modal[aria-label="Help"]').remove()
            // $('.note-editing-area').remove()
            // $('body').addClass('sidebar-collapse')
            $('#btn-addfolder').on('click', function(){
                $('#modal-default').modal('show')
            })
            $('input[name="visibilitytype"]').on('click', function(){
                $('#container-visibility').empty();
                var selectedtype = $(this).val();
				$.ajax({
					url: '/schoolfolderv2/folder',
					type: 'GET',
                    dataType: 'json',
					data: {
						action   			: 'getvisibilityresults',
						selectedtype		:  selectedtype
					},
					success:function(data)
					{
                        console.log(data);
                        
                        if(selectedtype == 0)
                        {
						$('#container-visibility').empty();
                        }
                        else if(selectedtype == 2)
                        {
                            $('#container-visibility').empty();
                            var displayhtml = '<div class="row"><div class="col-md-8"> <select class="form-control select2portals" multiple="multiple" name="portals[]" required>'

                                if(data.length > 0)
                                {
                                    $.each(data, function(key, value){
                                        displayhtml+='<option value="'+value.id+'">'+value.utype+'</option>'
                                    })
                                }
                            displayhtml += `</select></div><div class="col-md-4"><select name="viewtype" class="form-control viewwertype">
                                            <option value="viewer">Viewer</option>
                                            <option value="comment">Comment</option>
                                            <option value="editor">Editor</option>
                                            </select></div></div>`
                            $('#container-visibility').append(displayhtml);
                            $('.select2portals').select2({
                                theme: 'bootstrap4'
                            })
                        }
                        else if(selectedtype == 3)
                        {
                            $('#container-visibility').empty();
                            var displayhtml = '<div class="row"><div class="col-md-8"> <select class="form-control select2portals" multiple="multiple" name="users[]" required>'

                                if(data.length > 0)
                                {
                                    $.each(data, function(key, value){
										var temp_middle = value.middlename != null ? ' '+value.middlename : ''
                                        displayhtml+='<option value="'+value.userid+'">'+value.lastname+', '+value.firstname+' '+temp_middle+ ' - ' + value.utype+ '</option>'
                                    })
                                }
                            displayhtml += `</select></div><div class="col-md-4"><select name="viewtype" class="form-control viewwertype">
                                            <option value="viewer">Viewer</option>
                                            <option value="comment">Comment</option>
                                            <option value="editor">Editor</option>
                                            </select></div></div>`
                            $('#container-visibility').append(displayhtml);
                            $('.select2portals').select2({
                                theme: 'bootstrap4'
                            })
                        }
					}
				})
            })
            $(document).one('click','.each-card-folder', function(event){
                event.preventDefault();
                var folderid = $(this).attr('data-id');
                window.location.href = '/schoolfolderv2/viewfolder?folderid='+folderid;
                // $('#modal-view-folder').modal('show')
            })

            $('#btn-add-attachment-file').on('click', function(){
                $('#container-attachments').append(
                    `<div class="row mb-2">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="file" class="form-control" name="files[]" required/>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-sm btn-outline-danger each-attachment-remove" style="font-size: 11px;">Remove</button>
                                </div>
                            </div>
                            
                        </div>    
                        <div class="col-md-12">
                            <input type="text" class="form-control" placeholder="Title (Optional)" name="file-title[]"/>
                        </div>   
                        <div class="col-md-12">
                            <textarea class="form-control" placeholder="Description (Optional)" name="file-description[]"></textarea>
                        </div>    
                    </div>`
                )
            })
            $('#btn-add-attachment-link').on('click', function(){
                $('#container-attachments').append(
                    `<div class="row mb-2">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" class="form-control" name="links[]" required placeholder="Paste link here..."/>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-sm btn-outline-danger each-attachment-remove" style="font-size: 11px;">Remove</button>
                                </div>
                            </div>
                            
                        </div>    
                        <div class="col-md-12">
                            <input type="text" class="form-control" placeholder="Title (Optional)" name="link-title[]"/>
                        </div>   
                        <div class="col-md-12">
                            <textarea class="form-control" placeholder="Description (Optional)" name="link-description[]"></textarea>
                        </div>    
                    </div>`
                )
            })
            $(document).on('click','.each-attachment-remove', function(){
                $(this).closest('.row').remove()
            })
            $('#btn-submit-post').on('click', function(){
                var inputs = $('#container-attachments').find('input');
                if(inputs.length == 0)
                {
                    if($('#summernote').val().replace(/^\s+|\s+$/g, "").length > 0)
                    {
                        $(this).closest('form').submit()
                    }else{
                        toastr.error('No content to publish!', 'Publish a Post')
                    }
                    
                }else{
                    var validation_input = 0;
                    $.each(inputs, function(key,value){
                        if($(this).val().replace(/^\s+|\s+$/g, "").length > 0)
                        {
                            validation_input = 1;
                        }
                    })
                    if(validation_input == 0)
                    {
                        toastr.error('No content to publish!', 'Publish a Post')
                    }else{
                        $(this).closest('form').submit()
                    }
                }
            })
            $('.each-att-delete').on('click', function(){
                var attid = $(this).attr('data-attid')
                var thisatt = $(this).closest('.attachment-block');
                Swal.fire({
                    title: 'Are you sure you want to delete this attachment?',
                    html:
                        "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '/schoolfolderv2/contpage',
                            type:"GET",
                            dataType:"json",
                            data: {
                                action   	    : 'attachmentdelete',
                                attid          : attid
                            },
                            // headers: { 'X-CSRF-TOKEN': token },,
                            success: function(data){
                                toastr.success('Attachment deleted successfully!', 'Post Attachment')
                                thisatt.remove()

                            }
                        })
                    }
                })
            })
            var onerror_url = @json(asset('assets/images/avatars/unknown.png'));
            $(document).on('click','.each-btn-comment', function(){
                var postid = $(this).attr('data-postid');
                var commentval = $(this).closest('.img-push').find('input').val();
                if(commentval.replace(/^\s+|\s+$/g, "").length > 0)
                {
                    $.ajax({
                        url: '/schoolfolderv2/contpage',
                        type:"GET",
                        dataType:"json",
                        data: {
                            action   	    : 'commentsave',
                            postid          : postid,
                            commentval      : commentval
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        success: function(data){
                            $('#comment-section-'+postid).append(
                                `
                        <div class="card-comment">
                            
                            <img class="img-circle img-sm" src="`+data.picurl+`"  onerror="this.src=\'`+onerror_url+`\'" alt="User Image">
                            <div class="comment-text">
                            <span class="username">
                           `+data.firstname+` `+data.middlename+` `+data.lastname+` `+data.suffix+`
                            <span class="text-muted float-right">`+data.commentcreateddatetime+` <button type="button" class="btn btn-sm each-delete-comment" data-id="`+data.id+`"><i class="fa fa-trash-alt text-danger"></i></button></span>
                            </span>
                           `+data.comment+`
                            </div>
                        
                        </div>`
                            )

                        }
                    })
                }
            })
            $(document).on('click','.each-delete-post', function(){
                var postid = $(this).attr('data-id')
                var thispost = $(this).closest('.card-post');
                Swal.fire({
                    title: 'Are you sure you want to delete this post?',
                    html:
                        "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '/schoolfolderv2/contpage',
                            type:"GET",
                            dataType:"json",
                            data: {
                                action   	    : 'postdelete',
                                postid          : postid
                            },
                            // headers: { 'X-CSRF-TOKEN': token },,
                            success: function(data){
                                toastr.success('Post deleted successfully!', 'Post')
                                thispost.remove()

                            }
                        })
                    }
                })
            })
            $(document).on('click','.each-delete-comment', function(){
                var commentid = $(this).attr('data-id')
                var thiscomment = $(this).closest('.card-comment');
                Swal.fire({
                    title: 'Are you sure you want to delete this comment?',
                    html:
                        "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '/schoolfolderv2/contpage',
                            type:"GET",
                            dataType:"json",
                            data: {
                                action   	    : 'commentdelete',
                                commentid          : commentid
                            },
                            // headers: { 'X-CSRF-TOKEN': token },,
                            success: function(data){
                                toastr.success('Comment deleted successfully!', 'Comment')
                                thiscomment.remove()

                            }
                        })
                    }
                })
            })
        })
    </script>
@endsection