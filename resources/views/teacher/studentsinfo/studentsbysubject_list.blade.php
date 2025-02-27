
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

$quarters = array();
if(strtolower(Db::table('schoolinfo')->first()->abbreviation) == 'sait')
{
    $quarters = DB::table('quarter_setup')
        ->where('deleted','0')
        ->get();
}
elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mhssi')
{
    $quarters = DB::table('quarter_setup')
        ->where('id',$setupid)
        ->where('deleted','0')
        ->where('isactive','1')
        ->where('acadprogid',$acadprogid)
        ->get();
}
elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hchs')
{
    $quarters = DB::table('quarter_setup')
        ->where('deleted','0')
        ->where('isactive','1')
        ->where('acadprogid',$acadprogid)
        ->get();
}
     
     
$avatar = 'assets/images/avatars/unknown.jpg';               
@endphp

<style>
    td, th{
        padding: 1px !important;
    }
</style>

<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">

@if(strtolower(Db::table('schoolinfo')->first()->abbreviation) == 'sait' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hchs')
    <div class="row mb-2">
        <div class="col-md-12">
            <button type="button" class="btn btn-sm btn-warning" id="totalmale">Male : {{$totalmale}}</button>
            <button type="button" class="btn btn-sm btn-warning" id="totalfemale">Female : {{$totalfemale}}</button>
            <button type="button" class="btn btn-sm btn-warning" id="totalunspecified">Unspecified : {{$totalunspecified}}</button>
            <button type="button" class="btn btn-sm btn-warning" id="totalstudent">Total Number of Students : {{count($students)}}</button>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-md-6">
            <input class="form-control" id="input-search" placeholder="Search student" />
        </div>
        <div class="col-md-6 text-right">
            
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
                    <h3 class="card-title">
                        <div class="row">
                            <div class="col-3">
                                <img src="{{asset($student->picurl)}}"   onerror="this.onerror = null, this.src='{{asset($avatar)}}'" width="70px">
                            </div>
                            <div class="col-9" style="font-size: 13px;">
                                <div class="row">
                                    <div class="col-12">
                                        <span data-studid="{{$student->id}}"><strong>{{$student->lastname}}</strong>, {{$student->firstname}}</span>
                                    </div>
                                    <div class="col-8">
                                        <span class="badge badge-light border">{{$student->description}}</span>
                                        <span class="badge badge-light border">{{$student->moldesc}}</span>
                                    </div>
                                    <div class="col-4 text-right">
                                        <span class="text-muted">{{$student->sid}}</span>
                                    </div>
                                </div>
                                <div class="card-tools mt-2">
                                <button type="button"  class="btn btn-sm btn-default toModal mb-2" id="{{$student->id}}"  data-toggle="modal" data-target="#studentmodal{{$student->id}}">View Info</button>
                                @if(strtolower(Db::table('schoolinfo')->first()->abbreviation) == 'sait' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hchs')
                                                <button type="button" class="btn btn-tool text-secondary" data-card-widget="collapse"><i class="fas fa-plus"></i> Permit Details
                                                </button>
                                @elseif(strtolower(Db::table('schoolinfo')->first()->abbreviation) == 'gbbc' )
                                    @foreach ($quarters as $quarter)
                                    <button type="button" class="btn btn-sm btn-default text-bold getpermitstatus" data-studid="{{$student->id}}">{{$quarter->monthname}} : <span class="quarterpermit{{$quarter->id}}"></span></button>
                                    @endforeach
                                @endif
                                </div>
                            </div>
                        </div>
                    </h3>
    
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
                                                <td class="quarterpermit{{$quarter->id}} p-0 text-center" style="font-size: 10px;">
        
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
                                                    <a class="nav-link active" id="studentProfile-tab{{$student->id}}" data-toggle="pill" href="#studentProfile{{$student->id}}" role="tab" aria-controls="studentProfile{{$student->id}}" aria-selected="false">Profile</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="parents-tab{{$student->id}}" data-toggle="pill" href="#parents{{$student->id}}" role="tab" aria-controls="parents{{$student->id}}" aria-selected="false">Parents/Guardian</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="card-body">
                                            <div class="tab-content" id="custom-tabs-three-tabContent{{$student->id}}">
                                                <div class="tab-pane fade show active" id="studentProfile{{$student->id}}" role="tabpanel" aria-labelledby="studentProfile-tab{{$student->id}}">
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
                                <h3 class="card-title">
                                    <div class="row">
                                        <div class="col-3">
                                            <img src="{{asset($student->picurl)}}"   onerror="this.onerror = null, this.src='{{asset($avatar)}}'" width="70px">
                                        </div>
                                        <div class="col-9" style="font-size: 13px;">
                                            <div class="row">
                                                <div class="col-12">
                                                    <span data-studid="{{$student->id}}"><strong>{{$student->lastname}}</strong>, {{$student->firstname}}</span>
                                                </div>
                                                <div class="col-8">
                                                    <span class="badge badge-light border">{{$student->description}}</span>
                                                    <span class="badge badge-light border">{{$student->moldesc}}</span>
                                                </div>
                                                <div class="col-4 text-right">
                                                    <span class="text-muted">{{$student->sid}}</span>
                                                </div>
                                            </div>
                                            <div class="card-tools mt-2">
                                                <button type="button"  class="btn btn-sm btn-default toModal mb-2" id="{{$student->id}}"  data-toggle="modal" data-target="#studentmodal{{$student->id}}">View Info</button>
                                                @if(strtolower(Db::table('schoolinfo')->first()->abbreviation) == 'sait' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hchs')
                                                            <button type="button" class="btn btn-tool text-secondary" data-card-widget="collapse"><i class="fas fa-plus"></i> Permit Details
                                                            </button>
                                                @elseif(strtolower(Db::table('schoolinfo')->first()->abbreviation) == 'gbbc')
                                                    @foreach ($quarters as $quarter)
                                                        <button type="button" class="btn btn-sm btn-default text-bold getpermitstatus" data-studid="{{$student->id}}">{{$quarter->monthname}} : <span class="quarterpermit{{$quarter->id}}"></span></button>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </h3>
                            </div>
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
                                                        <td class="quarterpermit{{$quarter->id}} p-0 text-center" style="font-size: 10px;">
                
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
                                            <div class="tab-content" id="custom-tabs-three-tabContent{{$student->id}}">
                                                <div class="tab-pane fade show active" id="studentProfile{{$student->id}}" role="tabpanel" aria-labelledby="studentProfile-tab{{$student->id}}">
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                @endforeach
            @endif
        </div>
    </div>
    <script>
        $(document).ready(function(){
            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sait' )
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
                                    }
                                }); 
                            })
                        @endforeach
                    @endif
            @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc' || strtolower(Db::table('schoolinfo')->first()->abbreviation) == 'hchs'  || strtolower(Db::table('schoolinfo')->first()->abbreviation) == 'mhssi')
                // Swal.fire({
                //     title: 'Loading students...',
                //     allowOutsideClick: false,
                //     closeOnClickOutside: false,
                //     onBeforeOpen: () => {
                //         Swal.showLoading()
                //     }
                // }) 
                @php
                if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc'  || strtolower(Db::table('schoolinfo')->first()->abbreviation) == 'mhssi')
                {
                    $quarters = DB::table('quarter_setup')
                        ->where('id',$setupid)
                        ->where('deleted','0')
                        ->where('isactive','1')
                        ->where('acadprogid',$acadprogid)
                        ->get();
                }else{
                    $quarters = DB::table('quarter_setup')
                        ->where('deleted','0')
                        ->where('isactive','1')
                        ->where('acadprogid',$acadprogid)
                        ->get();
                }
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
        })
    </script>
            
@else
    <div class="row">
        <div class="col-12">    
            <div class="card">
                <div class="card-header">
                        <div class="row">
                            <div class="col-md-8">
                                <button type="button" class="btn btn-sm btn-warning" id="totalmale">Male : {{$totalmale}}</button>
                                <button type="button" class="btn btn-sm btn-warning" id="totalfemale">Female : {{$totalfemale}}</button>
                                <button type="button" class="btn btn-sm btn-warning" id="totalunspecified">Unspecified : {{$totalunspecified}}</button>
                                <button type="button" class="btn btn-sm btn-warning" id="totalstudent">Total Number of Students : {{count($students)}}</button>
                            </div>
                            <div class="col-md-4 text-right">
                                <button type="button" class="btn btn-sm btn-outline-info" id="export-classlist">Export Class List</button>
                            </div>
                        </div>
                    {{-- <ul class="nav nav-pills ml-auto p-2">
                        <li class="nav-item"><a class="nav-link active btn-sm" href="#tab_1" data-toggle="tab">Students' Information</a></li>
                        <li class="nav-item"><a class="nav-link btn-sm" href="#tab_2" data-toggle="tab">Exam Permit Status</a></li>
                    </ul> --}}
                </div>
                <div class="card-body">
                    {{-- <div class="tab-content">
                        <div class="tab-pane active" id="tab_1"> --}}
                            <table class="table" id="table-studinfo" style="font-size: 12px;">
                                <thead>
                                    <tr>
                                        {{-- <th></th> --}}
                                        <th style="width: 13%;">Student ID</th>
                                        <th style="width: 30%;">Student</th>
                                        <th></th>
                                        <th class="text-center">Gender</th>
                                        <th class="text-center" style="width: 15%;">View Info</th>
                                        <th class="text-center">Exam Permit Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $keystudent=>$eachstudent)
                                        <tr>
                                            {{-- <td><img src="{{asset($eachstudent->picurl)}}"   onerror="this.onerror = null, this.src='{{asset($avatar)}}'" width="50px"></td> --}}
                                            <td style="vertical-align: middle;">{{$eachstudent->sid}}</td>
                                            <td style="vertical-align: middle;">
                                                <span data-studid="{{$eachstudent->id}}"><strong>{{$eachstudent->lastname}}</strong>, {{$eachstudent->firstname}}</span><br/>
                                            </td>
                                            <td style="text-align: center; vertical-align: middle;">
                                                {{-- <span class="badge badge-light border">{{$eachstudent->description}}</span> --}}
                                                <span class="badge badge-light border">{{$eachstudent->moldesc}}</span>
                                            </td>
                                            <td style="text-align: center; vertical-align: middle;">{{$eachstudent->gender[0] ?? ''}}</td>
                                            <td style="vertical-align: middle;">
                                                <center><button type="button"  class="btn btn-sm btn-default toModal mb-2" id="{{$eachstudent->id}}"  data-toggle="modal" data-target="#studentmodal{{$eachstudent->id}}">View Info</button></center>
                                                <div class="modal fade studentmodal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="studentmodal{{$eachstudent->id}}" style="font-size: 13px;">
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
                                                                            <a class="nav-link" id="studentProfile-tab{{$eachstudent->id}}" data-toggle="pill" href="#studentProfile{{$eachstudent->id}}" role="tab" aria-controls="studentProfile{{$eachstudent->id}}" aria-selected="false">Profile</a>
                                                                        </li>
                                                                        <li class="nav-item">
                                                                            <a class="nav-link" id="parents-tab{{$eachstudent->id}}" data-toggle="pill" href="#parents{{$eachstudent->id}}" role="tab" aria-controls="parents{{$eachstudent->id}}" aria-selected="false">Parents/Guardian</a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="tab-content" id="custom-tabs-three-tabContent{{$eachstudent->id}}">
                                                                        <div class="tab-pane fade show active" id="studentProfile{{$eachstudent->id}}" role="tabpanel" aria-labelledby="studentProfile-tab{{$eachstudent->id}}">
                                                                            <p><span style="width: 30%">SID:</span> <strong>{{$eachstudent->sid}}</strong></p>
                                                                            <p><span style="width: 30%">LRN:</span> <strong>{{$eachstudent->lrn}}</strong></p>
                                                                            <p>Full name: <strong>{{$eachstudent->firstname}} {{$eachstudent->middlename}} {{$eachstudent->lastname}} {{$eachstudent->suffix}}</strong></p> 
                                                                            <p>Date of Birth: <strong>{{$eachstudent->dob}}</strong></p>
                                                                            <p>Gender: <strong>{{$eachstudent->gender}}</strong></p>
                                                                            <p>Contact No.: <strong>{{$eachstudent->contactno}}</strong></p>
                                                                            <p>Home Address: <strong>{{$eachstudent->street}}, {{$eachstudent->barangay}} {{$eachstudent->city}} {{$eachstudent->province}}</strong></p>
                                                                            <hr style="border:1px solid #ddd">
                                                                            <p>Blood Type: <strong>{{$eachstudent->bloodtype}}</strong></p>
                                                                            <p>Allergies: <strong>{{$eachstudent->allergy}}</strong></p>
                                                                        </div>
                                                                        <div class="tab-pane fade" id="parents{{$eachstudent->id}}" role="tabpanel" aria-labelledby="parents-tab{{$eachstudent->id}}">
                                                                            <em>In case of emergency, contact:</em>
                                                                            <hr style="border:1px solid #ddd">
                                                                            <p>Mother's Name: <strong>{{$eachstudent->mothername}}</strong></p>
                                                                            <p>Contact No.: <strong>{{$eachstudent->mcontactno}}</strong></p>
                                                                            <p>Occupation: <strong>{{$eachstudent->moccupation}}</strong></p>
                                                                            <hr style="border:1px solid #ddd">
                                                                            <p>Father's Name: <strong>{{$eachstudent->fathername}}</strong></p>
                                                                            <p>Contact No.: <strong>{{$eachstudent->fcontactno}}</strong></p>
                                                                            <p>Occupation: <strong>{{$eachstudent->foccupation}}</strong></p>
                                                                            <hr style="border:1px solid #ddd">
                                                                            <p>Guardian's Name: <strong>{{$eachstudent->guardianname}}</strong></p>
                                                                            <p>Contact No.: <strong>{{$eachstudent->gcontactno}}</strong></p>
                                                                            <p>Relation: <strong>{{$eachstudent->guardianrelation}}</strong></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer justify-content-between">
                                                                <button type="button" class="btn btn-secondary btn-view-close" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td style="text-align: center; vertical-align: middle;">
                                                @if($eachstudent->exampermitstatus == 'allowed' || $eachstudent->exampermitstatus == 'a')
                                                <span class="badge badge-success">PERMITTED</span>
                                                @elseif($eachstudent->exampermitstatus == 'not_allowed' || $eachstudent->exampermitstatus == 'na')
                                                <span class="badge badge-secondary">WITH BALANCE</span>
                                                @else
                                                
                                                    {{-- <span style="color: white;">remove_ajax</span> --}}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        {{-- </div>
                        
                        <div class="tab-pane" id="tab_2">
                        The European languages are members of the same family. Their separate existence is a myth.
                        For science, music, sport, etc, Europe uses the same vocabulary. The languages only differ
                        in their grammar, their pronunciation and their most common words. Everyone realizes why a
                        new common language would be desirable: one could refuse to pay expensive translators. To
                        achieve this, it would be necessary to have uniform grammar, pronunciation and more common
                        words. If several languages coalesce, the grammar of the resulting language is more simple
                        and regular than that of the individual languages.
                        </div>
                    
                    </div> --}}
                
                </div>
            </div>
            
        </div>
    </div>
    <script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
    <script>
        $(document).ready(function(){
            $('#table-studinfo').DataTable({
                // "paging": false,
                // "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": false,
                "responsive": true
            });
        });
    </script>
@endif
