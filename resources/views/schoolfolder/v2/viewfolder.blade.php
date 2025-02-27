@extends($extends)

@section('headerjavascript')
    <link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css')}}">
    <!-- Bootstrap4 Duallistbox -->
    <link rel="stylesheet" href="{{asset('plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css')}}">
    <!-- Ekko Lightbox -->
    <link rel="stylesheet" href="{{asset('plugins/ekko-lightbox/ekko-lightbox.css')}}">
    
<link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
<link href="{{asset('plugins/bootstrap-datepicker/1.2.0/css/datepicker.min.css')}}" rel="stylesheet">
<!-- dropzonejs -->
<link rel="stylesheet" href="{{asset('plugins/dropzone/min/dropzone.min.css')}}">
@endsection

@section('content')
    <style>
        .shadow{
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
            border: none !important;
        }
        img {
            border-radius: unset !important;
        }
    </style>
{{-- <div class="card shadow collapsed-card">
<div class="card-header">
<h3 class="card-title">Expandable</h3>
<div class="card-tools">
<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
</button>
</div>

</div>

<div class="card-body">
The body of the card
</div>

</div> --}}

@php
$foldercreatedby = DB::table('teacher')
    ->where('userid', $folderinfo->createdby)
    ->where('deleted','0')
    ->first();
@endphp

@if($folderinfo->deleted == 1)
    <section class="content">
        <div class="error-page">
            <h2 class="headline text-danger"><i class="fa fa-trash-alt"></i></h2>
            <div class="error-content">
                <h3><i class="fas fa-exclamation-triangle text-danger"></i> This folder is deleted.</h3>
                <p>You may <a href="/schoolfolderv2/index">return to dashboard</a> to see the active folders.</p>
            </div>
        </div>    
    </section>    
@else
    <div class="row">
        <div class="col-md-12">
            <div class="info-box shadow-lg">
                <span class="info-box-icon" style="font-size: 70px; color: #ddd;"><i class="fa fa-folder"></i></span>
                <div class="info-box-content">
                    <div class="row">
                        <div class="col-md-8">
                            <h4 class="m-0">
                                {{-- <i class="fa fa-edit text-muted" style="font-size: 15px; cursor: pointer;" id="icon-edit-foldername"></i>&nbsp;<span id="span-forldername" hidden>{{$folderinfo->foldername}}</span> --}}
                                @if($folderinfo->createdby == auth()->user()->id)
                                <input type="text" id="input-foldername" style="border: none; borde-bottom: 1px solid" value="{{$folderinfo->foldername}}"/>
                                <button type="button" class="btn btn-sm btn-outline-success" id="btn-update-foldername">
                                    <i class="fa fa-share" style="" id="icon-edit-foldername"></i>&nbsp; Save Changes
                                </button>
                                @else
                                <input type="text" id="input-foldername" style="border: none; borde-bottom: 1px solid" value="{{$folderinfo->foldername}}" disabled/>
                                @endif
                            </h4>
                            <small class="text-muted">Created by: {{$foldercreatedby->lastname}}, {{$foldercreatedby->firstname}}</small><br/>
                            <small class="text-muted">Date created: {{date('M d, Y h:i A', strtotime($folderinfo->createddatetime))}}</small><br/>
                            @if($folderinfo->createdby == auth()->user()->id)
                            <small class="text-muted" style="cursor: pointer;"><i class="fa fa-eye"></i> <u data-toggle="modal" data-target="#modal-update-visibility">{{$folderinfo->vtype == 0 ? 'Only Me' : ($folderinfo->vtype == 1 ? 'All' : ($folderinfo->vtype == 2 ? 'Selected portals' : ($folderinfo->vtype == 3 ? 'Selected users' : '')))}}</u></small>
                            @if($folderinfo->vtype == 2)
                                @if(count($portals)>0)
                                <br/>
                                <small>:
                                    @for($x=0; $x < count($portals); $x++)
                                        {{$portals[$x]->utype ?? ''}}  @if(($x+1) <  count($portals)) | @endif  
                                    @endfor
                                </small> 
                                   
                                @endif
                            @elseif($folderinfo->vtype == 3)
                                @if(count($users)>0)
                                <br/>
                                <small>:
                                    @for($x=0; $x < count($users); $x++)
                                        @if($users[$x])
                                            {{$users[$x]->lastname}}, {{$users[$x]->firstname}}  @if(($x+1) <  count($users)) | @endif 
                                        @endif
                                    @endfor
                                </small> 
                                
                                @endif
                            @endif
                            <div class="modal fade" id="modal-update-visibility">
                                <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                <div class="modal-header">
                                <h4 class="modal-title">Visibility Type</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                                </div>
                                <form action="/schoolfolderv2/folder" method="GET">
                                    @csrf
                                    <div class="modal-body">
                                        <input type="hidden" class="form-control" name="action" value="updatevisibility"/>
                                        <input name="folderid" value="{{$folderinfo->id}}" hidden/>
                                        <div class="row">
                                            <div class="col-md-12 mt-2">
                                                <label>Visible to</label>
                                                <div class="form-group clearfix">
                                                    <div class="icheck-primary d-inline mr-4">
                                                    <input type="radio" id="radioPrimary4" name="visibilitytype" @if($folderinfo->vtype == 0) checked @endif value="0">
                                                    <label for="radioPrimary4">
                                                        Only me
                                                    </label>
                                                    </div>
                                                <div class="icheck-primary d-inline mr-4">
                                                <input type="radio" id="radioPrimary1" name="visibilitytype"@if($folderinfo->vtype == 1) checked @endif value="1">
                                                <label for="radioPrimary1">
                                                    All
                                                </label>
                                                </div>
                                                <div class="icheck-primary d-inline mr-2">
                                                <input type="radio" id="radioPrimary2" name="visibilitytype" @if($folderinfo->vtype == 2) checked @endif value="2">
                                                <label for="radioPrimary2">
                                                    Selected Portals
                                                </label>
                                                </div>
                                                <div class="icheck-primary d-inline">
                                                <input type="radio" id="radioPrimary3" name="visibilitytype" @if($folderinfo->vtype == 3) checked @endif value="3">
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
                                @endif
                            {{-- <input type="text" class="form-control form-control-sm" value="{{$folderinfo->foldername}}"/> --}}
                        </div>
                        <div class="col-md-4 text-right">
                            @if($folderinfo->createdby == auth()->user()->id)
                            <button type="button" class="btn btn-sm btn-outline-danger" id="btn-delete-folder"><i class="fa fa-trash-alt"></i> Delete</button>
                            @endif
                        </div>
                    </div>
                </div>
            
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-upload-files" id="addattachment"><i class="fa fa-upload"></i>&nbsp;&nbsp;Upload File</button>   
            <form action="/schoolfolderv2/upload" method="post" enctype="multipart/form-data" name="submitfiles">
                @csrf
                <input name="folderid" value="{{$folderinfo->id}}" hidden/>
                <input type="file" id="fileid" name="files[]" multiple accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint,application/pdf, image/*,video/*" hidden/>
            </form>
        </div>
    </div>
           
    @if(count($files)>0)
        <div class="row">
            @foreach($files as $eachfile)
                @php
                    $imgonerror =  public_path().'/'.str_replace('http://','',asset($eachfile->filepath));
                @endphp
                
                <div class="col-12 col-md-6 col-sm-3 d-flex align-items-stretch flex-column">
                    <div class="card  d-flex flex-fill shadow-lg" style="box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;">
                        <div class="card-header text-muted border-bottom-0" style="font-size: 11.5px;">
                            <p class="text-muted text-sm"><b>Date posted: </b>{{date('M d, Y h:i A', strtotime($eachfile->createddatetime))}}</p>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row">
                                <div class="col-7">
                                    <p class="text-muted text-sm">{{$eachfile->filename}}</p>
                                
                                </div>
                                <div class="col-5 text-center">
                                    @if($eachfile->extension == 'jpg' || $eachfile->extension == 'png')
                                        <img src="{{asset($eachfile->filepath)}}" alt="{{$imgonerror}}"  onerror="this.onerror = null, this.src='{{$imgonerror}}'"  style="width: 80px;height: 80px;" draggable="false" data-toggle="modal" data-target="#modal-view-file{{$eachfile->id}}"/>
                                    @elseif($eachfile->extension == 'pdf')
                                        <img src="{{asset('assets/images/pdf.png')}}" alt="{{$eachfile->filename}}"  style="width: 80px;height: 80px;" draggable="false" />
                                    @elseif($eachfile->extension == 'doc' || $eachfile->extension == 'docx')
                                        <img src="{{asset('assets/images/doc.png')}}" alt="{{$eachfile->filename}}"  style="width: 80px;height: 80px;" draggable="false" />
                                    @elseif($eachfile->extension == 'xls' || $eachfile->extension == 'xlsx')
                                        <img src="{{asset('assets/images/xls.png')}}" alt="{{$eachfile->filename}}"  style="width: 80px;height: 80px;" draggable="false" />
                                    @endif
                                    
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                @if($eachfile->createdby == auth()->user()->id)
                                <div class="col-2 align-self-end">
                                    <button type="button" class="btn btn-sm btn-default each-file-delete text-muted" data-id="{{$eachfile->id}}"><i class="fa fa-trash-alt"></i> </button>
                                </div>
                                <div class="col-10 text-right p-0">
                                @else
                                <div class="col-12 text-right">
                                @endif
                                    @if($eachfile->extension == 'jpg' || $eachfile->extension == 'png')
                                        <button class="btn btn-sm btn-default" data-toggle="modal" data-target="#modal-view-file{{$eachfile->id}}">
                                        View File
                                        </button>
                                        <div class="modal fade" id="modal-view-file{{$eachfile->id}}">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h6 class="modal-title" style=" text-overflow: ellipsis;
                                                        overflow: hidden;">{{$eachfile->filename}}</h6>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <img src="{{asset($eachfile->filepath)}}" alt="{{$imgonerror}}"  onerror="this.onerror = null, this.src='{{$imgonerror}}'"  style="width: 100%" draggable="false"/>
                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        <a type="button" href="{{asset($eachfile->filepath)}}" class="btn btn-default" download>Download</a>
                                                    </div>
                                                </div>
                                            
                                            </div>
                                            
                                        </div>
                                    @elseif($eachfile->extension == 'pdf')
                                        <a href="{{asset($eachfile->filepath)}}" download  class="btn btn-sm btn-default">
                                        Download File
                                        </a>
                                    @elseif($eachfile->extension == 'doc' || $eachfile->extension == 'docx')
                                        <a href="{{asset($eachfile->filepath)}}" download  class="btn btn-sm btn-default">
                                        Download File
                                        </a>
                                    @elseif($eachfile->extension == 'xls' || $eachfile->extension == 'xlsx')
                                        <a href="{{asset($eachfile->filepath)}}" download  class="btn btn-sm btn-default">
                                            Download File
                                        </a>
                                    @endif
                                    @if($eachfile->createdby == auth()->user()->id)
                                        <button  type="button" class="btn btn-sm bg-warning btn-view-comments" data-id="{{$eachfile->id}}" data-name="{{$eachfile->filename}}">
                                            <i class="fas fa-comments"></i>
                                        </button>
                                        @if($eachfile->unseen > 0)
                                            <sup class="badge badge-danger float-right" style="right: 10px;">{{$eachfile->unseen}}</sup>
                                        @endif
                                    @else
                                        <button  type="button" class="btn btn-sm bg-warning btn-view-comments" data-id="{{$eachfile->id}}" data-name="{{$eachfile->filename}}">
                                            <i class="fas fa-comments"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-3" >
                    @if($eachfile->extension == 'jpg' || $eachfile->extension == 'png')
                        <img src="{{asset($eachfile->filepath)}}" alt="{{$imgonerror}}"  onerror="this.onerror = null, this.src='{{$imgonerror}}'"  style="width: 80px;height: 80px;" draggable="false" data-toggle="modal" data-target="#modal-view-file{{$eachfile->id}}"/>
                        <div class="modal fade" id="modal-view-file{{$eachfile->id}}">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h6 class="modal-title" style=" text-overflow: ellipsis;
                                        overflow: hidden;">{{$eachfile->filename}}</h6>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <img src="{{asset($eachfile->filepath)}}" alt="{{$imgonerror}}"  onerror="this.onerror = null, this.src='{{$imgonerror}}'"  style="width: 100%" draggable="false"/>
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        <a type="button" href="{{asset($eachfile->filepath)}}" class="btn btn-default" download>Download</a>
                                    </div>
                                </div>
                            
                            </div>
                            
                        </div>
                    @elseif($eachfile->extension == 'pdf')
                        <img src="{{asset('assets/images/pdf.png')}}" alt="{{$eachfile->filename}}"  style="width: 80px;height: 80px;" draggable="false" data-toggle="modal" data-target="#modal-view-file{{$eachfile->id}}"/>
                        <div class="modal fade" id="modal-view-file{{$eachfile->id}}">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h6 class="modal-title" style=" text-overflow: ellipsis;
                                        overflow: hidden;">{{$eachfile->filename}}</h6>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <embed src={{asset($eachfile->filepath)}} 
                                                    width="100%"
                                                    height="500">
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                </div>                                                        
                            </div>                                                        
                        </div>
                    @elseif($eachfile->extension == 'doc' || $eachfile->extension == 'docx')
                        <a href="{{asset($eachfile->filepath)}}" download class="row">
                            <div class="col-12">
                                <img src="{{asset('assets/images/doc.png')}}" alt="{{$eachfile->filename}}"  style="width: 80px;height: 80px;" draggable="false" />
                            </div>
                            <div class="col-12" style=" white-space: nowrap; width: 70%; overflow: hidden;text-overflow: ellipsis; ">
                                <small class="text-muted">{{$eachfile->filename}}</small>
                            </div>
                        </a>
                    @elseif($eachfile->extension == 'xls' || $eachfile->extension == 'xlsx')
                        <a href="{{asset($eachfile->filepath)}}" download class="row">
                            <div class="col-12">
                                <img src="{{asset('assets/images/xls.png')}}" alt="{{$eachfile->filename}}"  style="width: 80px;height: 80px;" draggable="false" />
                            </div>
                            <div class="col-12" style=" white-space: nowrap; width: 70%; overflow: hidden;text-overflow: ellipsis; ">
                                <small class="text-muted">{{$eachfile->filename}}</small>
                            </div>
                        </a>
                    @endif
                    <div style="width: 100%;">
                        <button type="button" class="btn btn-sm btn-default each-file-delete" data-id="{{$eachfile->id}}" style="width: 80px;"><i class="fa fa-trash"></i> Delete</button>
                    </div>
                </div> --}}
            @endforeach
        </div>
    @endif
    {{-- <div class="tab-pane" id="timeline"> --}}
    {{-- <div class="row">
        <div class="col-md-12">
            <div class="timeline timeline-inverse">
            
                <div class="time-label">
                    @if($folderinfo->createdby == auth()->user()->id)
                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-upload-files" id="addattachment"><i class="fa fa-upload"></i>&nbsp;&nbsp;Upload File</button>   
                    @endif   

                </div>  
                <div hidden>
                    <div class="timeline-item">
                        <div class="timeline-body">
                    
                            <form action="/schoolfolderv2/upload" method="post" enctype="multipart/form-data" name="submitfiles">
                                @csrf
                                <input name="folderid" value="{{$folderinfo->id}}" hidden/>
                                <input type="file" id="fileid" name="files[]" multiple accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint,application/pdf, image/*,video/*" hidden/>
                            </form>
                        </div>
                    </div>
                </div>   
                @if(count($files)>0)
                    @foreach($files as $filekey => $file)
                        <div class="time-label">
                            <span style="background-color: white; border: 1px solid #ddd;">
                            {{date('d M. Y h:i A', strtotime($filekey))}}
                            </span>
                        </div>   
                
                        <div>
                            <i class="fas fa-paperclip bg-warning"></i>
                            <div class="timeline-item">
                                <div class="timeline-body">
                                    <div class="row">
                                        @foreach($file as $eachfile)
                                            @php
                                                $imgonerror =  public_path().'/'.str_replace('http://','',asset($eachfile->filepath));;
                                            @endphp
                                            <div class="col-3" >
                                                @if($eachfile->extension == 'jpg' || $eachfile->extension == 'png')
                                                    <img src="{{asset($eachfile->filepath)}}" alt="{{$imgonerror}}"  onerror="this.onerror = null, this.src='{{$imgonerror}}'"  style="width: 80px;height: 80px;" draggable="false" data-toggle="modal" data-target="#modal-view-file{{$eachfile->id}}"/>
                                                    <div class="modal fade" id="modal-view-file{{$eachfile->id}}">
                                                        <div class="modal-dialog modal-xl">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h6 class="modal-title" style=" text-overflow: ellipsis;
                                                                    overflow: hidden;">{{$eachfile->filename}}</h6>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <img src="{{asset($eachfile->filepath)}}" alt="{{$imgonerror}}"  onerror="this.onerror = null, this.src='{{$imgonerror}}'"  style="width: 100%" draggable="false"/>
                                                                </div>
                                                                <div class="modal-footer justify-content-between">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                    <a type="button" href="{{asset($eachfile->filepath)}}" class="btn btn-default" download>Download</a>
                                                                </div>
                                                            </div>
                                                        
                                                        </div>
                                                        
                                                    </div>
                                                @elseif($eachfile->extension == 'pdf')
                                                    <img src="{{asset('assets/images/pdf.png')}}" alt="{{$eachfile->filename}}"  style="width: 80px;height: 80px;" draggable="false" data-toggle="modal" data-target="#modal-view-file{{$eachfile->id}}"/>
                                                    <div class="modal fade" id="modal-view-file{{$eachfile->id}}">
                                                        <div class="modal-dialog modal-xl">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h6 class="modal-title" style=" text-overflow: ellipsis;
                                                                    overflow: hidden;">{{$eachfile->filename}}</h6>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <embed src={{asset($eachfile->filepath)}} 
                                                                                width="100%"
                                                                                height="500">
                                                                </div>
                                                                <div class="modal-footer justify-content-between">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>                                                        
                                                        </div>                                                        
                                                    </div>
                                                @elseif($eachfile->extension == 'doc' || $eachfile->extension == 'docx')
                                                    <a href="{{asset($eachfile->filepath)}}" download class="row">
                                                        <div class="col-12">
                                                            <img src="{{asset('assets/images/doc.png')}}" alt="{{$eachfile->filename}}"  style="width: 80px;height: 80px;" draggable="false" />
                                                        </div>
                                                        <div class="col-12" style=" white-space: nowrap; width: 70%; overflow: hidden;text-overflow: ellipsis; ">
                                                            <small class="text-muted">{{$eachfile->filename}}</small>
                                                        </div>
                                                    </a>
                                                @elseif($eachfile->extension == 'xls' || $eachfile->extension == 'xlsx')
                                                    <a href="{{asset($eachfile->filepath)}}" download class="row">
                                                        <div class="col-12">
                                                            <img src="{{asset('assets/images/xls.png')}}" alt="{{$eachfile->filename}}"  style="width: 80px;height: 80px;" draggable="false" />
                                                        </div>
                                                        <div class="col-12" style=" white-space: nowrap; width: 70%; overflow: hidden;text-overflow: ellipsis; ">
                                                            <small class="text-muted">{{$eachfile->filename}}</small>
                                                        </div>
                                                    </a>
                                                @endif
                                                <div style="width: 100%;">
                                                    <button type="button" class="btn btn-sm btn-default each-file-delete" data-id="{{$eachfile->id}}" style="width: 80px;"><i class="fa fa-trash"></i> Delete</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>   
                    @endforeach
                    <div>
                        <i class="far fa-clock bg-gray"></i>
                    </div>
                @endif    
                
            </div>
        </div>
    </div> --}}
    <div class="modal fade" id="modal-view-comments" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" style=" text-overflow: ellipsis;
                    overflow: hidden;" id="h6-filename"></h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body card-comments" id="container-comments" style="max-height: 500px; overflow-y: scroll;">
                    <div class="card-comment">

                        <img class="img-circle img-sm" src="../dist/img/user3-128x128.jpg" alt="User Image">
                        <div class="comment-text">
                        <span class="username">
                        Maria Gonzales
                        <span class="text-muted float-right">8:03 PM Today</span>
                        </span>
                        It is a long established fact that a reader will be distracted
                        by the readable content of a page when looking at its layout.
                        </div>
                        
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    {{-- <form action="#" method="GET" style="width: 100%;"> --}}
                        {{-- <img class="img-fluid img-circle img-sm" src="../dist/img/user4-128x128.jpg" alt="Alt Text"> --}}
                    
                        <div class="img-push" style="width: 100%;">
                            <div class="input-group input-group-sm">
                                <textarea class="form-control form-control-sm" id="textarea-comment"></textarea>
                                 <div class="input-group-append">
                                    <button type="button" class="btn btn-primary" id="btn-submit-comment">Comment</button>
                                </div>
                            </div>
                            <small class="float-right"><span class="text-muted" id="span-count-chars-left">255</span> characters left</small>
                        </div>
                    {{-- </form> --}}
                    {{-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> --}}
                </div>
            </div>                                                        
        </div>                                                        
    </div>
@endif
    {{-- </div> --}}
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
    <script src="{{asset('plugins/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js')}}"></script>
    <!-- Filterizr-->
    <!-- dropzonejs -->
    <script src="{{asset('plugins/dropzone/min/dropzone.min.js')}}"></script>

    {{-- <script src="{{asset('plugins/filterizr/jquery.filterizr.min.js')}}"></script> --}}
    <script type="text/javascript">
        Dropzone.options.imageUpload = {
            maxFilesize         :       1,
            acceptedFiles: ".jpeg,.jpg,.png,.gif,.pdf"
        };
</script>

@if($folderinfo->deleted == 0)
    <script type="text/javascript">
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip()
            $('#btn-update-foldername').hide()
            
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

        $('input[name="visibilitytype"]').on('click', function(){
            $('#container-visibility').empty();
            var selectedtype = $(this).val();
            $.ajax({
                url: '/schoolfolderv2/folder',
                type: 'GET',
                dataType: 'json',
                data: {
                    action   			: 'getvisibilityresults',
                    folderid        : '{{$folderinfo->id}}',
                    selectedtype		:  selectedtype
                },
                success:function(data)
                {
                    if(selectedtype == 0)
                    {
                    $('#container-visibility').empty();
                    }
                    else if(selectedtype == 2)
                    {
                        $('#container-visibility').empty();
                        var displayhtml = '<select class="form-control select2portals" multiple="multiple" name="portals[]" required>'

                            if(data.length > 0)
                            {
                                $.each(data, function(key, value){
                                    if(value.display == 1)
                                    {
                                    displayhtml+='<option value="'+value.id+'" selected>'+value.utype+'</option>'
                                    }else{
                                    displayhtml+='<option value="'+value.id+'">'+value.utype+'</option>'
                                    }
                                })
                            }
                        displayhtml += '<</select>'
                        $('#container-visibility').append(displayhtml);
                        $('.select2portals').select2({
                            theme: 'bootstrap4'
                        })
                    }
                    else if(selectedtype == 3)
                    {
                        $('#container-visibility').empty();
                        var displayhtml = '<select class="form-control select2portals" multiple="multiple" name="users[]">'

                            if(data.length > 0)
                            {
                                $.each(data, function(key, value){
                                    if(value.display == 1)
                                    {
                                    displayhtml+='<option value="'+value.userid+'" selected>'+value.lastname+', '+value.firstname+' '+value.middlename+'</option>'
                                    }else{
                                    displayhtml+='<option value="'+value.userid+'">'+value.lastname+', '+value.firstname+' '+value.middlename+'</option>'
                                    }
                                })
                            }
                        displayhtml += '<</select>'
                        $('#container-visibility').append(displayhtml);
                        $('.select2portals').select2({
                            theme: 'bootstrap4'
                        })
                    }
                }
            })
        })
        $('input[name="visibilitytype"]:checked').click();
        $('#input-foldername').on('input', function(){ 
            $('#btn-update-foldername').show()
        });
        $('#btn-update-foldername').on('click', function(){
            var foldername = $('#input-foldername').val();
            $.ajax({
                url: '/schoolfolderv2/folder',
                type: 'GET',
                dataType: 'json',
                data: {
                    action   	    : 'updatefolder',
                    folderid        : '{{$folderinfo->id}}',
                    foldername		:  foldername
                },
                success:function(data)
                {
                    if(data == 1)
                    {
						toastr.success('Updated succesfully!','Folder')
                        $('#btn-update-foldername').hide()
                    }
                }
            })
        })
            $('#btn-delete-folder').on('click', function(){
                
                Swal.fire({
                    title: 'Are you sure you want to delete this folder?',
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
                            url: '/schoolfolderv2/folder',
                            type:"GET",
                            dataType:"json",
                            data: {
                                action   	    : 'deletefolder',
                                folderid        : '{{$folderinfo->id}}'
                            },
                            // headers: { 'X-CSRF-TOKEN': token },,
                            success: function(data){
                                if(data == 1)
                                {
                                        Toast.fire({
                                            type: 'success',
                                            title: 'Deleted successfully!'
                                        })

                                    window.location.replace('/schoolfolderv2/index');
                                }                                       

                            }
                        })
                    }
                })
            })
            $('.each-file-delete').on('click', function(){
                var fileid = $(this).attr('data-id')
                
                Swal.fire({
                    title: 'Are you sure you want to delete this file?',
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
                            url: '/schoolfolderv2/folder',
                            type:"GET",
                            dataType:"json",
                            data: {
                                action   	    : 'deletefile',
                                fileid        : fileid
                            },
                            // headers: { 'X-CSRF-TOKEN': token },,
                            success: function(data){
                                if(data == 1)
                                {
                                        Toast.fire({
                                            type: 'success',
                                            title: 'Deleted successfully!'
                                        })

                                    window.location.reload();
                                }                                       

                            }
                        })
                    }
                })
            })

        })
        var fileid = 0;
        $('.btn-view-comments').on('click', function(){
            $('#h6-filename').text($(this).attr('data-name'))
            $('#modal-view-comments').modal('show')
            fileid = $(this).attr('data-id')
            $('#container-comments').empty()
            $.ajax({
                url: '/schoolfolderv2/folder',
                type:"GET",
                dataType:"json",
                data: {
                    action   	    : 'getcomments',
                    fileid        : fileid
                },
                // headers: { 'X-CSRF-TOKEN': token },,
                success: function(data){
                    var onerror_url = @json(asset('dist/img/download.png'));
                    if(data.length == 0)
                    {
                        $('#container-comments').append(
                            '<div class="alert alert-warning" role="alert">'+
                            'Be the first to comment!'+
                            '</div>'
                        )
                    }else{
                        $.each(data, function(key, value){
                            var appendthis =
                                '<div class="card-comment">'+
                                    '<img src="'+value.picurl+'" onerror="this.src=\''+onerror_url+'\'" alt="" class="img-circle img-sm" style="border-radius: 50% !important;">'+
                                    '<div class="comment-text" style="font-size: 13px !important; text-align: justify;">'+
                                        '<span class="username">'+
                                            value.name+
                                            '<span class="text-muted float-right">'+value.datestring+'</span>'+
                                        '</span>'+
                                        value.comment;

                            if(value.userid == '{{auth()->user()->id}}')
                            {
                                appendthis+=
                                        '<p>'+
                                            '<a href="#" class="link-black text-sm each-delete-comment" style="font-size: 13px !important;" data-id="'+value.id+'"><i class="far fa-trash-alt mr-1"></i> Delete</a>'+
                                        '</p>'
                            }

                            appendthis+='</div>'+
                                '</div>'
                                
                            $('#container-comments').append( appendthis )
                        })
                        $("#container-comments").animate({ scrollTop: $('#container-comments').prop("scrollHeight")}, 1000);
                    }
                }
            })
        })
        
        // var max = 255;
        // $('#textarea-comment').keypress(function(e) {
        //     var inputcount = $('#textarea-comment').val().length;
        //     var inputdiff = 255-inputcount;
        //     $('#span-count-chars-left').text('('+(255-inputcount)+')');
        //     if (e.which < 0x20) {
        //         // e.which < 0x20, then it's not a printable character
        //         // e.which === 0 - Not a character
        //         return;     // Do nothing
        //     }
        //     if (this.value.length == max) {
        //         $('#textarea-comment').css('border','1px solid red')
        //         e.preventDefault();
        //     } else if (this.value.length > max) {
        //         $('#textarea-comment').css('border','1px solid red')anim
        //         // Maximum exceeded
        //         this.value = this.value.substring(0, max);
        //     }
        // });
        $('#textarea-comment').keyup(function(e) {
            var inputcount = $(this).val().length;
            var inputdiff = 255-inputcount;
            if(inputdiff >= 0)
            {
                $('#span-count-chars-left').text(255-inputcount);
                $('#textarea-comment').removeAttr('style')
            }else{
                $('#textarea-comment').css('border','1px solid red')
                this.value = this.value.substring(0, 255);
                e.preventDefault();
            }
        });
        $('#btn-submit-comment').on('click', function(){
            if($('#textarea-comment').val().replace(/^\s+|\s+$/g, "").length == 0)
            {
                $('#textarea-comment').css('border','1px solid red')
                
            }else{
                $('#textarea-comment').removeAttr('style')
                $.ajax({
                    url: '/schoolfolderv2/folder',
                    type:"GET",
                    dataType:"json",
                    data: {
                        action   	    : 'commentonfile',
                        comment   	    : $('#textarea-comment').val(),
                        fileid          : fileid
                    },
                    // headers: { 'X-CSRF-TOKEN': token },,
                    success: function(data){
                            window.location.reload();                            

                    }
                })
            }
        })
        $('.close').on('click', function(){
            window.location.reload();  
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
                        url: '/schoolfolderv2/folder',
                        type:"GET",
                        dataType:"json",
                        data: {
                            action   	    : 'commentdelete',
                            commentid          : commentid
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        success: function(data){
                            thiscomment.remove()

                        }
                    })
                }
            })
        })
    </script>
    @endif
@endsection