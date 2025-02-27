
@extends('teacher.layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Toastr -->
<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
<style>
    
    #modal-edit-view .modal {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        overflow: hidden;
    }

    #modal-edit-view .modal-dialog {
        position: fixed;
        margin: 0;
        width: 100%;
        height: 100%;
        padding: 0;
    }
    @media (min-width: 576px)
    {
        #modal-edit-view .modal-dialog {
            max-width:  unset !important;
            margin: unset !important;
        }
    }
    #modal-edit-view .modal-content {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        border: 2px solid #3c7dcf;
        border-radius: 0;
        box-shadow: none;
    }

    #modal-edit-view .modal-header {
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        height: 50px;
        padding: 10px;
        background: #6598d9;
        border: 0;
    }

    #modal-edit-view .modal-title {
        font-weight: 300;
        font-size: 2em;
        color: #fff;
        line-height: 30px;
    }

    #modal-edit-view .modal-body {
        position: absolute;
        top: 50px;
        bottom: 60px;
        width: 100%;
        font-weight: 300;
        overflow: auto;
        background-color: rgba(0,0,0,.0001) !important;
    }
    #modal-edit-view .modal-footer {
        position: absolute;
        right: 0;
        bottom: 0;
        left: 0;
        height: 60px;
        padding: 10px;
        background: #f1f3f5;
    }

</style>
<div>
    <nav class="" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="active breadcrumb-item" aria-current="page">Advisory Sections</li>
            <li class="active breadcrumb-item" aria-current="page">{{$sectioninfo->sectionname}}</li>
        </ol>
    </nav>
</div>
@php
$totalmale          = 0;
$totalfemale        = 0;
$totalunspecified   = 0;
if(count($students) > 0)
{
    foreach($students as $student)
    {
        if(strtolower($student->gender) == 'female'){
            $totalfemale += 1;
        }
        elseif(strtolower($student->gender) == 'male'){
            $totalmale += 1;
        }else{
            
            $totalunspecified += 1;
        }
    }
}

if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sait')
{
    $quarters = DB::table('quarter_setup')
        ->where('deleted','0')
        ->get();
}
if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hchs')
{
    $quarters = DB::table('quarter_setup')
        ->where('deleted','0')
        ->where('isactive','1')
        ->where('acadprogid',$sectioninfo->acadprogid)
        ->get();
}
     
     
$avatar = 'assets/images/avatars/unknown.jpg';               
@endphp
<div class="row">
    <div class="col-md-3 col-sm-6 col-12">
      <div class="info-box" style="color: #004085;
      background-color: #cce5ff;
      border-color: #b8daff;">
        <span class="info-box-icon"><i class="fa fa-male"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Male</span>
          <span class="info-box-number">{{$totalmale}}</span>
        </div>
        <!-- /.info-box-content -->
      </div>
</div>
    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box" style="color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;">
        <span class="info-box-icon"><i class="fa fa-female"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Female</span>
          <span class="info-box-number">{{$totalfemale}}</span>

        </div>
        <!-- /.info-box-content -->
      </div>
</div>
    <div class="col-md-3 col-sm-6 col-12">
      <div class="info-box" style="color: #155724;
      background-color: #d4edda;
      border-color: #c3e6cb;">
        <span class="info-box-icon"><i class="far fa-bookmark"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Unspecified</span>
          <span class="info-box-number">{{$totalunspecified}}</span>

        </div>
        <!-- /.info-box-content -->
      </div>
</div>
    <div class="col-md-3 col-sm-6 col-12">
      <div class="info-box" style="color: #856404;
      background-color: #fff3cd;
      border-color: #ffeeba;">
        <span class="info-box-icon"><i class="far fa-bookmark"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">No. of Students</span>
          <span class="info-box-number">{{count($students)}}</span>

        </div>
        <!-- /.info-box-content -->
      </div>
</div>
</div>
{{-- <div class="row mb-2">
    <div class="col-md-12">
        <button type="button" class="btn btn-sm btn-warning" id="totalmale">Male : {{$totalmale}}</button>
        <button type="button" class="btn btn-sm btn-warning" id="totalfemale">Female : {{$totalfemale}}</button>
        <button type="button" class="btn btn-sm btn-warning" id="totalunspecified">Unspecified : {{$totalunspecified}}</button>
        <button type="button" class="btn btn-sm btn-warning" id="totalstudent">Total Number of Students : {{count($students)}}</button>

        <button type="button" class="btn btn-sm btn-default float-right" id="btn-export-msteams"><i class="fa fa-download"></i> MSTeams Accounts</button>
    </div>
</div> --}}
<div class="row mb-2">
    <div class="col-md-6">
        <input class="filter form-control" placeholder="Search student" />
    </div>
    <div class="col-md-6 text-right">
        <button type="button" class="btn btn-default" id="btn-export-pdf"><i class="fa fa-file-pdf"></i> Export to PDF</button>
        {{-- <button type="button" class="btn btn-default" id="btn-export-msteams"><i class="fa fa-download"></i> MSTeams Accounts</button> --}}
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <label>MALE</label>
        @if(count($students) > 0)
            @foreach($students as $student)
                @if(strtolower($student->gender) == 'male')
                    <!-- small box -->
                    
                        
            <div class="card card-primary collapsed-card card-student" style="border: none; box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important;
        }"  data-string="{{$student->lastname}}, {{$student->firstname}}<">
                <div class="card-header ">
                      <div class="row">
                          <div class="col-3">
                            <img src="{{asset($student->picurl)}}"   onerror="this.onerror = null, this.src='{{asset($avatar)}}'" width="70px">
                          </div>
                          <div class="col-9" style="font-size: 13px;">
                            <div class="row">
                                <div class="col-12">
                                    <span data-studid="{{$student->id}}"><strong>{{$student->lastname}}</strong>, {{$student->firstname}}</span>
                                </div>
                                <div class="col-12">
                                    <span class="badge badge-light border">{{$student->description}}</span>
                                    <span class="badge badge-light border">{{$student->moldesc}}</span>
                                    <span class="badge badge-light border">{{$student->sid}}</span>
                                </div>
                            </div>
                            <div class="card-tools mt-1">
                              <button type="button"  class="btn btn-sm btn-default toModal" id="{{$student->id}}"  data-toggle="modal" data-target="#studentmodal{{$student->id}}">View Info</button>
                              @if(strtolower(Db::table('schoolinfo')->first()->abbreviation) == 'sait' || strtolower(Db::table('schoolinfo')->first()->abbreviation) == 'gbbc' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hchs')
                                            <button type="button" class="btn btn-tool text-secondary" data-card-widget="collapse"><i class="fas fa-plus"></i> Permit Details
                                            </button>
                                            @endif
                            </div>
                          </div>
                      </div>
  
                  <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                @if(strtolower(Db::table('schoolinfo')->first()->abbreviation) == 'sait')
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 getpermitstatus" data-studid="{{$student->id}}">
                            <table class="table">
                                <tr  style="font-size: 10px;">
                                    @foreach ($quarters as $quarter)
                                    <th class="text-center p-0">{{$quarter->description}}</th>
                                    @endforeach
                                </tr>
                                <tr>
                                    @foreach ($quarters as $quarter)
                                        <td class="quarterpermit{{$quarter->id}} p-0 text-center">

                                        </td>
                                    @endforeach
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                @elseif(strtolower(Db::table('schoolinfo')->first()->abbreviation) == 'gbbc' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hchs')
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-md-12 getpermitstatus pr-2 pl-2" data-studid="{{$student->id}}">
                            <table class="table ml-2">
                                @foreach ($quarters as $quarter)
                                <tr>
                                    <th class="text-left p-0">{{$quarter->description}}</th>
                                    <td class="quarterpermit{{$quarter->id}} p-0 text-center"></td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                <!-- /.card-body -->
              </div>
                    <div class="modal fade studentmodal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="studentmodal{{$student->id}}">
                        <div class="modal-dialog modal-md">
                            <div class="modal-content">
                                <div class="modal-header bg-info">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Profile</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="card-header p-0 border-bottom-0">
                                        <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link" id="studentProfile-tab{{$student->id}}" data-toggle="pill" href="#studentProfile{{$student->id}}" role="tab" aria-controls="studentProfile{{$student->id}}" aria-selected="false">Profile</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="parents-tab{{$student->id}}" data-toggle="pill" href="#parents{{$student->id}}" role="tab" aria-controls="parents{{$student->id}}" aria-selected="false">Parents/Guardian</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content" id="custom-tabs-three-tabContent">
                                            <div class="tab-pane fade" id="studentProfile{{$student->id}}" role="tabpanel" aria-labelledby="studentProfile-tab{{$student->id}}">
                                                <p><span style="width: 30%">SID:</span> <strong>{{$student->sid}}</strong></p>
                                                <p><span style="width: 30%">LRN:</span> <strong>{{$student->lrn}}</strong></p>
                                                <p>Full name: <strong>{{$student->firstname}} {{$student->middlename}} {{$student->lastname}} {{$student->suffix}}</strong></p> 
                                                <p>Date of Birth: <strong>{{$student->dob}}</strong></p>
                                                <p>Gender: <strong>{{$student->gender}}</strong></p>
                                                <p>Contact No.: <strong>{{$student->contactno}}</strong></p>
                                                <p>Home Address: <strong>{{$student->street}}, {{$student->barangay}} {{$student->city}} {{$student->province}}</strong></p>
                                                <hr style="border:1px solid #ddd">
                                                <p>Blood Type: <strong>{{$student->bloodtype}}</strong></p>
                                                <p>Allergies: <strong>{{$student->allergy}}</strong></p>
                                            </div>
                                            <div class="tab-pane fade" id="parents{{$student->id}}" role="tabpanel" aria-labelledby="parents-tab{{$student->id}}">
                                                <em>In case of emergency, contact:</em>
                                                <hr style="border:1px solid #ddd">
                                                <p>Mother's Name: <strong>{{$student->mothername}}</strong></p>
                                                <p>Contact No.: <strong>{{$student->mcontactno}}</strong></p>
                                                <p>Occupation: <strong>{{$student->moccupation}}</strong></p>
                                                <hr style="border:1px solid #ddd">
                                                <p>Father's Name: <strong>{{$student->fathername}}</strong></p>
                                                <p>Contact No.: <strong>{{$student->fcontactno}}</strong></p>
                                                <p>Occupation: <strong>{{$student->foccupation}}</strong></p>
                                                <hr style="border:1px solid #ddd">
                                                <p>Guardian's Name: <strong>{{$student->guardianname}}</strong></p>
                                                <p>Contact No.: <strong>{{$student->gcontactno}}</strong></p>
                                                <p>Relation: <strong>{{$student->guardianrelation}}</strong></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-secondary btn-view-close" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-warning btn-studinfo-edit" data-id="{{$student->id}}"><i class="fa fa-edit"></i> Edit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            @endforeach
        @endif
    </div>
    <div class="col-md-6">
        <label>FEMALE</label>
        @if(count($students) > 0)
            @foreach($students as $student)
                @if(strtolower($student->gender) == 'female')
                        
                <div class="card card-primary collapsed-card card-student" style="border: none; box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important;
            }"  data-string="{{$student->lastname}}, {{$student->firstname}}<">
                    <div class="card-header ">
                          <div class="row">
                              <div class="col-3">
                                <img src="{{asset($student->picurl)}}"   onerror="this.onerror = null, this.src='{{asset($avatar)}}'" width="70px">
                              </div>
                              <div class="col-9" style="font-size: 13px;">
                                <div class="row">
                                <div class="col-12">
                                    <span data-studid="{{$student->id}}"><strong>{{$student->lastname}}</strong>, {{$student->firstname}}</span>
                                </div>
                                <div class="col-12">
                                    <span class="badge badge-light border">{{$student->description}}</span>
                                    <span class="badge badge-light border">{{$student->moldesc}}</span>
                                    <span class="badge badge-light border">{{$student->sid}}</span>
                                </div>
                                </div>
                                <div class="card-tools mt-1">
                                  <button type="button"  class="btn btn-sm btn-default toModal" id="{{$student->id}}"  data-toggle="modal" data-target="#studentmodal{{$student->id}}">View Info</button>
                                  
                              @if(strtolower(Db::table('schoolinfo')->first()->abbreviation) == 'sait' || strtolower(Db::table('schoolinfo')->first()->abbreviation) == 'gbbc' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hchs')
                              <button type="button" class="btn btn-tool text-secondary" data-card-widget="collapse"><i class="fas fa-plus"></i> Permit Details
                              </button>
                              @endif
                                </div>
                              </div>
                          </div>
      
                      <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    @if(strtolower(Db::table('schoolinfo')->first()->abbreviation) == 'sait')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 getpermitstatus" data-studid="{{$student->id}}">
                                <table class="table">
                                    <tr  style="font-size: 10px;">
                                        @foreach ($quarters as $quarter)
                                        <th class="text-center p-0">{{$quarter->description}}</th>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        @foreach ($quarters as $quarter)
                                            <td class="quarterpermit{{$quarter->id}} p-0 text-center">
    
                                            </td>
                                        @endforeach
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    @elseif(strtolower(Db::table('schoolinfo')->first()->abbreviation) == 'gbbc' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hchs')
                    <div class="card-body p-0">
                        <div class="row">
                        <div class="col-md-12 getpermitstatus pr-2 pl-2" data-studid="{{$student->id}}">
                            <table class="table ml-2">
                                    @foreach ($quarters as $quarter)
                                    <tr>
                                        <th class="text-left p-0">{{$quarter->description}}</th>
                                        <td class="quarterpermit{{$quarter->id}} p-0 text-center"></td>
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
    
                    <!-- /.card-body -->
                  </div>
                    <div class="modal fade studentmodal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="studentmodal{{$student->id}}">
                        <div class="modal-dialog modal-md">
                            <div class="modal-content">
                                <div class="modal-header bg-info">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Profile</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="card-header p-0 border-bottom-0">
                                        <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link" id="studentProfile-tab{{$student->id}}" data-toggle="pill" href="#studentProfile{{$student->id}}" role="tab" aria-controls="studentProfile{{$student->id}}" aria-selected="false">Profile</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="parents-tab{{$student->id}}" data-toggle="pill" href="#parents{{$student->id}}" role="tab" aria-controls="parents{{$student->id}}" aria-selected="false">Parents/Guardian</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content" id="custom-tabs-three-tabContent">
                                            <div class="tab-pane fade" id="studentProfile{{$student->id}}" role="tabpanel" aria-labelledby="studentProfile-tab{{$student->id}}">
                                                <p><span style="width: 30%">SID:</span> <strong>{{$student->sid}}</strong></p>
                                                <p><span style="width: 30%">LRN:</span> <strong>{{$student->lrn}}</strong></p>
                                                <p>Full name: <strong>{{$student->firstname}} {{$student->middlename}} {{$student->lastname}} {{$student->suffix}}</strong></p> 
                                                <p>Date of Birth: <strong>{{$student->dob}}</strong></p>
                                                <p>Gender: <strong>{{$student->gender}}</strong></p>
                                                <p>Contact No.: <strong>{{$student->contactno}}</strong></p>
                                                <p>Home Address: <strong>{{$student->street}}, {{$student->barangay}} {{$student->city}} {{$student->province}}</strong></p>
                                                <hr style="border:1px solid #ddd">
                                                <p>Blood Type: <strong>{{$student->bloodtype}}</strong></p>
                                                <p>Allergies: <strong>{{$student->allergy}}</strong></p>
                                            </div>
                                            <div class="tab-pane fade" id="parents{{$student->id}}" role="tabpanel" aria-labelledby="parents-tab{{$student->id}}">
                                                <em>In case of emergency, contact:</em>
                                                <hr style="border:1px solid #ddd">
                                                <p>Mother's Name: <strong>{{$student->mothername}}</strong></p>
                                                <p>Contact No.: <strong>{{$student->mcontactno}}</strong></p>
                                                <p>Occupation: <strong>{{$student->moccupation}}</strong></p>
                                                <hr style="border:1px solid #ddd">
                                                <p>Father's Name: <strong>{{$student->fathername}}</strong></p>
                                                <p>Contact No.: <strong>{{$student->fcontactno}}</strong></p>
                                                <p>Occupation: <strong>{{$student->foccupation}}</strong></p>
                                                <hr style="border:1px solid #ddd">
                                                <p>Guardian's Name: <strong>{{$student->guardianname}}</strong></p>
                                                <p>Contact No.: <strong>{{$student->gcontactno}}</strong></p>
                                                <p>Relation: <strong>{{$student->guardianrelation}}</strong></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-secondary btn-view-close" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-warning btn-studinfo-edit" data-id="{{$student->id}}"><i class="fa fa-edit"></i> Edit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            @endforeach
        @endif
    </div>
</div>
<div class="modal fade" id="modal-edit-view">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit student information</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="resultscontainer">
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
        {{-- <button type="button" class="btn btn-primary" id="btn-edit-submit" disabled>We're still working on this page!</button> --}}
        <button type="button" class="btn btn-primary" id="btn-edit-submit">Save changes</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div id="edit_profile_pic" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><strong>Profile Photo</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                    <div class="row">
                        
                        <div class="col-md-12 text-center">
        
                        <div id="upload-demo"></div>
        
                        </div>
        
                    </div>
                    <input type="file" id="upload" class="form-control form-control-sm" style="overflow: hidden;" accept="image/*">
                    <br>
                    <br>
                <button class="btn btn-success upload-result">Upload Image</button>
            </div>
        </div>
    </div>
</div>
<!-- Toastr -->
<script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
<script>
    var studid;
    $(document).on('click','.toModal',function(){
        var id = $(this).attr('id');
        $('#studentProfile-tab'+id).attr('class','nav-link active');
        $('#studentProfile'+id).attr('class','tab-pane fade show active');
        $('#parents-tab'+id).attr('class','nav-link');
        $('#parents'+id).attr('class','tab-pane fade');
    })
    $(document).ready(function(){
        $(".filter").on("keyup", function() {
            var input = $(this).val().toUpperCase();
            var visibleCards = 0;
            var hiddenCards = 0;

            $(".container").append($("<div class='card-group card-group-filter'></div>"));

            $(".card-student").each(function() {
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
        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sait')
            @php
                $quarters = DB::table('quarter_setup')
                    ->where('deleted','0')
                    ->get();
            @endphp
            @if(count($quarters)>0)
                    @foreach($quarters as $quarter)
                        $('.getpermitstatus').each(function(){
                            var thiscontainer = $(this).find('.quarterpermit{{$quarter->id}}');
                            var studid  = $(this).attr('data-studid');
                                    var quarterid = '{{$quarter->id}}';
                                    $.ajax({
                                        url: '{{route('api_exampermit_flag')}}',
                                        type: 'GET',
                                        dataType: '',
                                        data: {
                                            studid:studid,
                                            qid:'{{$quarter->id}}'
                                        },
                                        success:function(data)
                                        {
                                            if(data == 'allowed')
                                            {
                                                thiscontainer.append(
                                                    '<span class="badge badge-success">Permitted</span>'
                                                )
                                            }else{
                                                thiscontainer.append(
                                                    '<span class="badge badge-secondary">With Balance</span>'
                                                )
                                            }
                                            //return - allowed or not_allowed
                                        }
                                    }); 
                        })
                    @endforeach
                @endif
        @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hchs')
            @php
                $quarters = DB::table('quarter_setup')
                    ->where('deleted','0')
                    ->where('isactive','1')
                    ->where('acadprogid',$sectioninfo->acadprogid)
                    ->get();
            @endphp
            @if(count($quarters)>0)
                    @foreach($quarters as $quarter)
                        $('.getpermitstatus').each(function(){
                            var thiscontainer = $(this).find('.quarterpermit{{$quarter->id}}');
                            var studid  = $(this).attr('data-studid');
                                    var quarterid = '{{$quarter->id}}';
                                    $.ajax({
                                        url: '{{route('api_exampermit_flag')}}',
                                        type: 'GET',
                                        data: {
                                            studid:studid,
                                            qid:'{{$quarter->id}}'
                                        },
                                        success:function(data)
                                        {
                                            console.log(thiscontainer)
                                            if(data == 'allowed')
                                            {
                                                thiscontainer.append(
                                                    '<span class="badge badge-success">Permitted</span>'
                                                )
                                            }else{
                                                thiscontainer.append(
                                                    '<span class="badge badge-secondary">With Balance</span>'
                                                )
                                            }
                                            //return - allowed or not_allowed
                                        }
                                    }); 
                        })
                    @endforeach
                @endif
        @endif
        $('.btn-studinfo-edit').on('click', function(){
            var studentid = $(this).attr('data-id');
            studid = studentid;
            $('.btn-view-close').click();
            // console.log(studentid)
            $('#modal-edit-view').modal('show');

            $.ajax({
                url: '/students/advisorygetstudinfo',
                type: 'GET',
                data: {
                    studentid   :   studentid
                },
                // dataType: 'json',
                success:function(data)
                {
                    $('#resultscontainer').empty();
                    $('#resultscontainer').append(data);

                }
            })

        })
        $('#btn-edit-submit').on('click', function(){
            var validation = 0

            if($('#edit-firstname').val().replace(/^\s+|\s+$/g, "").length == 0)
            {
                validation=1;
                $('#edit-firstname').css('border','1px solid red');
            }else{
                $('#edit-firstname').removeAttr('style')
            }
            if($('#edit-lastname').val().replace(/^\s+|\s+$/g, "").length == 0)
            {
                validation=1;
                $('#edit-lastname').css('border','1px solid red');
            }else{
                $('#edit-lastname').removeAttr('style')
            }
            if(validation == 1)
            {
                Swal.fire({
                    title: 'Please, don\'t leave important fields empty!',
                })
            }else{
                var studentid = studid;
                var lrn = $('#edit-lrn').val();
                var firstname = $('#edit-firstname').val();
                var middlename = $('#edit-middlename').val();
                var lastname = $('#edit-lastname').val();
                var suffix = $('#edit-suffix').val();
                var gender = $('#edit-gender').val();
                var birthdate = $('#edit-birthdate').val();
                var mothertongue = $('#edit-mothertongue').val();
                var ethnicgroup = $('#edit-ethnicgroup').val();
                var religion = $('#edit-religion').val();
                var street = $('#edit-street').val();
                var barangay = $('#edit-barangay').val();
                var city = $('#edit-city').val();
                var province = $('#edit-province').val();
                var contactno = $('#edit-contactno').val();
                var bloodtype = $('#edit-bloodtype').val();
                var allergy = $('#edit-allergies').val();
                var fathername = $('#edit-fathername').val();
                var fathermobilenum = $('#edit-fathermobilenum').val();
                var mothername = $('#edit-mothername').val();
                var mothermobilenum = $('#edit-mothermobilenum').val();
                var guardianname = $('#edit-guardianname').val();
                var guardianrelationship = $('#edit-guardianrelationship').val();
                var guardianmobilenum = $('#edit-guardianmobilenum').val();
                
                var ffname      = $('#edit-ffname').val();
                var fmname      = $('#edit-fmname').val();
                var flname      = $('#edit-flname').val();
                var fsuffix     = $('#edit-fsuffix').val();
                var fcontactno  = $('#edit-fcontactno').val();
                var mfname      = $('#edit-mfname').val();
                var mmname      = $('#edit-mmname').val();
                var mlname      = $('#edit-mlname').val();
                var msuffix     = $('#edit-msuffix').val();
                var mcontactno  = $('#edit-mcontactno').val();
                var gfname      = $('#edit-gfname').val();
                var gmname      = $('#edit-gmname').val();
                var glname      = $('#edit-glname').val();
                var gsuffix     = $('#edit-gsuffix').val();
                var gcontactno  = $('#edit-gcontactno').val();
                studid = studentid;
                
                Swal.fire({
                    title: 'Saving changes...',
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: false
                })

                $.ajax({
                    url: '/students/advisorygetstudinfosubmit',
                    type: 'GET',
                    dataType: 'json',
                    data:{
                        studentid               :   studentid,
                        lrn                     :   lrn,
                        firstname               :   firstname,
                        middlename              :   middlename,
                        lastname                :   lastname,
                        suffix                  :   suffix,
                        gender                  :   gender,
                        birthdate               :   birthdate,
                        mothertongue            :   mothertongue,
                        ethnicgroup             :   ethnicgroup,
                        religion                :   religion,
                        street                  :   street,
                        barangay                :   barangay,
                        city                    :   city,
                        province                :   province,
                        contactno               :   contactno,
                        bloodtype               :   bloodtype,
                        allergy                 :   allergy,
                        ffname                  :   ffname,
                        fmname                  :   fmname,
                        flname                  :   flname,
                        fsuffix                 :   fsuffix,
                        fcontactno              :   fcontactno,
                        mfname                  :   mfname,
                        mmname                  :   mmname,
                        mlname                  :   mlname,
                        msuffix                 :   msuffix,
                        mcontactno              :   mcontactno,
                        gfname                  :   gfname,
                        gmname                  :   gmname,
                        glname                  :   glname,
                        gsuffix                 :   gsuffix,
                        gcontactno              :   gcontactno
                    },
                    complete:function(data)
                    {
                        // $('.btn-studinfo-edit[data-id="'+studentid+'"]').click()
                        
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')
                        toastr.success('Updated successfully!', 'Student Info')
                    }
                })
            }
        })
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $uploadCrop = $('#upload-demo').croppie({
            enableExif: true,
            viewport: {
                width: 304,
                height: 289,
                // type: 'circle'        
            },
            boundary: {
                width: 304,
                height: 289
            }
        });
        $('#upload').on('change', function () { 
            var reader = new FileReader();
            reader.onload = function (e) {
                $uploadCrop.croppie('bind', {
                    url: e.target.result
                }).then(function(){
                    console.log('jQuery bind complete');
                });
            }
            reader.readAsDataURL(this.files[0]);
        });
        $('.upload-result').on('click', function (ev) {
            $uploadCrop.croppie('result', {
                type: 'canvas',
                size: 'viewport'
            }).then(function (resp) {
                $.ajax({
                    url: "{{ route('teacherupdateStudPic')}}",
                    type: "POST",
                    data: {
                        "image"     :   resp,
                        'studid'    :   studid
                        },
                    success: function (data) {
                        console.log(data)
                        window.location.reload();
                        // $('#profilepic').attr('src',data)
                    }
                });
            });        
        });
        $('#btn-export-pdf').on('click',function(){
            window.open('/students/advisorygetstudents?sectionid={{$sectionid}}&levelid={{$levelid}}&semid={{$semid}}&syid={{$syid}}&action=printclasslist','_blank')
        })
        $('#btn-export-msteams').on('click',function(){
            window.open('/students/advisorygetstudents?sectionid={{$sectionid}}&levelid={{$levelid}}&semid={{$semid}}&syid={{$syid}}&action=print','_blank')
        })
    })
</script>
@endsection
