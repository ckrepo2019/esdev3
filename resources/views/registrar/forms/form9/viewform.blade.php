
@extends('registrar.layouts.app')
@section('content')
    <link rel="stylesheet" href="{{asset('plugins/jquery-year-picker/css/yearpicker.css')}}" />
    <style>
        td                                          { border-bottom: hidden; }
        input[type=text], .input-group-text, .select{ background-color: white !important; border: hidden; border-bottom: 2px solid #ddd; font-size: 12px !important; }
        #input-addnewschoolyear{
            background-color: unset !important; border: 1px solid #ddd; border-bottom: 1px solid #ddd; font-size: unset !important; 
        }
        .input-group-text                           { border-bottom: hidden; }
        .fontSize                                   { font-size: 12px; }
        .container                                  { overflow-x: scroll !important; }
        table                                       { width: 100%; }
        .inputClass                                 { width: 100%; }
        .tdInputClass                               { padding: 0px !important; }
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button            { -webkit-appearance: none; margin: 0; }
        
        .show-modal .modal {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        overflow: hidden;
        }

        .show-modal .modal-dialog {
        position: fixed;
        margin: 0;
        width: 100%;
        height: 100%;
        padding: 0;
        }
        @media (min-width: 576px)
        {
            .show-modal .modal-dialog {
                max-width:  unset !important;
                margin: unset !important;
            }
        }
        .show-modal .modal-content {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        border: 2px solid #3c7dcf;
        border-radius: 0;
        box-shadow: none;
        }

        .show-modal .modal-header {
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        height: 50px;
        padding: 10px;
        background: #6598d9;
        border: 0;
        }

        .show-modal .modal-title {
        font-weight: 300;
        font-size: 2em;
        color: #fff;
        line-height: 30px;
        }

        .show-modal .modal-body {
        position: absolute;
        top: 50px;
        bottom: 60px;
        width: 100%;
        font-weight: 300;
        overflow: auto;
            background-color: rgba(0,0,0,.0001) !important;
        }

        .show-modal .modal-footer {
        position: absolute;
        right: 0;
        bottom: 0;
        left: 0;
        height: 60px;
        padding: 10px;
        background: #f1f3f5;
        }
        select[readonly]:-moz-read-only {
  /* For Firefox */
  pointer-events: none;
}

select[readonly]:read-only {
  pointer-events: none;
}
    </style>
    {{-- <div class="row">
        <div class="col-md-12">
            <div class="card card-default color-palette-box">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-8">
                            <h3><strong>Learner's Permanent Academic Record</strong></h3>
                            <small><em>(Formerly Form 137)</em></small>
                        </div>
                        <div class="col-md-4">
                            <h3>
                                <form action="/reports_schoolform10/getrecordssenior" target="_blank" method="get" class="m-0 p-0">
                                    <input type="hidden" value="1" name="export"/>
                                    <input type="hidden" value="{{$studentid}}" name="studentid"/>
                                    <button type="submit" class="btn btn-primary btn-sm text-white float-right">
                                        <i class="fa fa-upload"></i>
                                    Print
                                    </button>
                                </form>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="position-relative form-group ">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">NAME:</span>
                                    </div>
                                    <input type="text" class="form-control text-uppercase" id="validationCustomUsername"  value="{{$studentdata->lastname}}, {{$studentdata->firstname}} {{$studentdata->middlename}} {{$studentdata->suffix}}." aria-describedby="inputGroupPrepend" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="position-relative form-group ">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">SEX:</span>
                                    </div>
                                    <input type="text" class="form-control text-uppercase" id="validationCustomUsername"  value="{{$studentdata->gender}}" aria-describedby="inputGroupPrepend" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group ">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">DATE OF BIRTH:</span>
                                    </div>
                                    <input type="text" class="form-control text-uppercase" id="validationCustomUsername"  value="{{$studentdata->dob}}" aria-describedby="inputGroupPrepend" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 bg-gray text-center mb-2">
                            <h6>ELIGIBILITY FOR SHS ENROLMENT</h6>
                        </div>
                    </div>
                    <div class="row p-1" style="font-size: 12px; border: 1px solid black;">
                        <div class="col-md-6">
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                  <input type="checkbox"  id="checkbox-completerhs" value="{{$eligibility->completerhs}}" @if($eligibility->completerhs == 1) checked="" @endif>
                                  <label for="checkbox-completerhs">
                                      High School Completer* 
                                  </label>
                                </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>Gen. Ave.:</label> &nbsp; <input id="generalaveragehs" type="number" value="{{$eligibility->genavehs}}"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                  <input type="checkbox"  id="checkbox-completerjh" value="{{$eligibility->completerjh}}" @if($eligibility->completerjh == 1) checked="" @endif>
                                  <label for="checkbox-completerjh">
                                      Junior High School Completer*
                                  </label>
                                </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>Gen. Ave.:</label> &nbsp; <input id="generalaveragejh" type="number" value="{{$eligibility->genavejh}}"/>
                            </div>
                        </div>
                        {{-- <div class="col-md-4">
                            <label>Citation: (If Any)</label> &nbsp; <textarea class="form-control" id="citation">{{$eligibility->citation}}</textarea>
                        </div> --}}
                        <div class="col-md-4">
                            Date of Graduation/Completion (MM/DD/YYYY): <input type="date" class="form-control" id="graduationdate" value="{{$eligibility->graduationdate}}"/>
                        </div>
                        <div class="col-md-4">
                            Name of School: <input type="text" class="form-control" id="schoolname" value="{{$eligibility->schoolname}}"/>
                        </div>
                        <div class="col-md-4">
                            School Address: <input type="text" class="form-control" id="schooladdress" value="{{$eligibility->schooladdress}}"/>
                        </div>
                    </div>
                    <div class="row" style="font-size: 12px;">
                        <div class="col-md-12">
                            Other Credential Presented
                        </div>
                        <div class="col-md-4">
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                  <input type="checkbox" id="checkbox-peptpasser" value="{{$eligibility->peptpasser}}" @if($eligibility->peptpasser == 1) checked="" @endif>
                                  <label for="checkbox-peptpasser">
                                        PEPT Passer
                                  </label>
                                </div>
                            </div>
                            Rating: <input type="text" id="peptrating" class="form-control form-control-sm" value="{{$eligibility->peptrating}}"/>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                  <input type="checkbox" id="checkbox-alspasser" value="{{$eligibility->alspasser}}" @if($eligibility->alspasser == 1) checked="" @endif>
                                  <label for="checkbox-alspasser">
                                        ALS A & E Passer
                                  </label>
                                </div>
                            </div>
                            Rating: <input type="text" id="alsrating" class="form-control form-control-sm" value="{{$eligibility->alsrating}}"/>
                        </div>
                        <div class="col-md-4">
                            Other (Pls.Specify)
                            <textarea class="form-control" id="specify">{{$eligibility->others}}</textarea>
                        </div>
                    </div>
                    <div class="row mt-2" style="font-size: 12px;position: relative;">
                        <div class="col-md-3">
                            Date of Examination/Assessment (mm/dd/yyyy):
                        </div>
                        <div class="col-md-3"><input type="date" id="examdate" class="form-control form-control-sm" value="{{$eligibility->examdate}}"/>
                        </div>
                        <div class="col-md-3"><span style="position: absolute;bottom: 0;">Name and Address of Testing Center:</span></div>
                        <div class="col-md-3"><input type="text" id="centername" class="form-control form-control-sm" value="{{$eligibility->centername}}"/></div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-12 text-right">
                            <button type="button" class="btn btn-sm btn-primary" id="btn-eligibility-update"><i class="fa fa-edit"></i> Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="col-sm-12">
            <ol class="breadcrumb float-sm-left">
                <li class="breadcrumb-item active">Learner's Permanent Academic Record </li>
            </ol>
        </div> --}}
    </div>
    @php
        $buacsschoolids = array('405444','405042','405072','405075','404978','404989','404981','405032');
        $buacsschoolabb = array('sjhsti','phs','sait','sjhsli','lhs','sma','sihs','nsdphs');
        $schoolinfo = DB::table('schoolinfo')
                        ->first();

        if(in_array($schoolinfo->id, $buacsschoolids))
        {
            $buacs = 1;
        }else{
            if(in_array(strtolower($schoolinfo->abbreviation), $buacsschoolabb))
            {
                $buacs = 1;
            }else{
                $buacs = 0;
            }
        }
    @endphp
    <div class="row mb-2">
        <div class="col-md-12 text-right">
            @if($buacs == 1)
            <a type="button" href="/prinsf9print/{{$studentdata->id}}?syid={{$selectedschoolyear}}" class="btn btn-primary" target="_blank" style="text-decoration: none !important; color: white;"><i class="fa fa-file-pdf"></i> Export SF9A</a>
            <button type="button" class="btn btn-primary" id="btn-exporttopdf" ><i class="fa fa-file-pdf"></i> Export SF9B</button>
            @else
            <button type="button" class="btn btn-primary" id="btn-exporttopdf" ><i class="fa fa-file-pdf"></i> Export SF9 to PDF</button>
            @endif
        </div>
    </div>
                  <!-- we are adding the accordion ID so Bootstrap's collapse plugin detects it -->
    <div id="accordion" >
        @if(count($records)>0)
            @foreach($records as $recordkey => $recordval)
                <div class="card card-danger eachrecord" data-id="{{$recordval->id}}">
                    <div class="card-header" style="font-size: 12px !important;">
                        <div class="col-md-12" >
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{$recordkey}}">
                                <div class="row mb-2">
                                    <div class="col-2">School</div>
                                    <div class="col-4"><input type="text" class="form-control form-control-sm" value="{{$recordval->schoolname}}" disabled/></div>
                                    <div class="col-2">School ID</div>
                                    <div class="col-4"><input type="text" class="form-control form-control-sm" value="{{$recordval->schoolid}}" disabled/></div>
                                </div>
                                <div class="row mb-2">
                                <div class="col-2">Grade Level
                                </div>
                                <div class="col-2">
                                    <input type="text" class="form-control" value="{{$recordval->levelname}}" readonly/>
                                </div>
                                <div class="col-2">School Year:</div>
                                <div class="col-2"><input type="text" class="form-control form-control-sm"  value="{{$recordval->sydesc}}" disabled/></div>
                                <div class="col-2">Sem:</div>
                                <div class="col-2">
                                    @if($recordval->semid == 1)
                                        <input type="text" class="form-control form-control-sm"  value="1st Sem" disabled/>
                                    @elseif($recordval->semid == 2)
                                        <input type="text" class="form-control form-control-sm"  value="2nd Sem" disabled/>
                                    @endif
                                </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-2">Track/Strand
                                    </div>
                                    <div class="col-4"><input type="text" class="form-control form-control-sm" value="{{$recordval->trackname}}/{{$recordval->strandname}}" disabled/>
                                    </div>
                                    <div class="col-2">Section</div>
                                    <div class="col-4"><input type="text" class="form-control form-control-sm" value="{{$recordval->sectionname}}" disabled/></div>
                                </div>
                                {{-- <div class="row mb-2">
                                    <div class="col-3">
                                        Name of Adviser/Teacher
                                    </div>
                                    <div class="col-9">
                                        <input type="text" class="form-control form-control-sm" value="{{$recordval->teachername}}" disabled/>
                                    </div>
                                </div> --}}
                            </a>
                        </div>
                    </div>
                    <div id="collapse{{$recordkey}}" class="collapse" data-parent="#accordion">
                        <div class="card-body">
                            @if($recordval->type == 2)
                                <div class="row mb-2">
                                    <div class="col-12 text-right">
                                        <button type="button" class="btn btn-sm btn-edit-syinfo btn-info" data-id="{{$recordval->id}}"><i class="fa fa-edit"></i> Info</button>
                                        <button type="button" class="btn btn-sm btn-edit-reportcard btn-info" data-id="{{$recordval->id}}"><i class="fa fa-edit"></i> Report Card</button>
                                        <button type="button" class="btn btn-sm btn-edit-remedialclasses btn-info" data-id="{{$recordval->id}}"><i class="fa fa-edit"></i> Remedial Classes</button>
                                        <button type="button" class="btn btn-sm btn-delete-syinfo btn-danger" data-id="{{$recordval->id}}"><i class="fa fa-trash-alt"></i> Delete</button>
                                    </div>
                                </div>
                            @endif
                            <table class="table table-bordered text-uppercase" style="font-size: 11px; table-layout: fixed;">
                                <thead class="text-center">
                                    <tr>
                                        <th style="width:20%;">Indicate if Subject is CORE, APPLIED, or SPECIALIZED</th>
                                        <th style="width:40%;">SUBJECTS</th>
                                        <th>Final Rating</th>
                                        <th style="width:15%;">ACTION TAKEN</th>
                                        <th style="width:15%;">No. of Hours Taken</th>
                                        {{-- <th>Action Taken</th> --}}
                                        {{-- <th>Credits Earned</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($recordval->grades) == 0)
                                        <tr>
                                            <td colspan="5" class="text-center">No grades shown</td>
                                        </tr>
                                    @else
                                        @foreach(collect($recordval->grades)->where('semid', $recordval->semid) as $grade)
                                            @if(strtolower($grade->subjdesc)!= 'general average')
                                                <tr>
                                                    <td>
                                                        @if($grade->type == 1)
                                                            Core
                                                        @elseif($grade->type == 2)
                                                            Specialized
                                                        @elseif($grade->type == 3)
                                                            Applied
                                                        @else
                                                            Other Subject
                                                        @endif
                                                    </td>
                                                    <td>{{$grade->subjdesc}}</td>
                                                    <td class="text-center">{{$grade->finalrating}}</td>
                                                    <td class="text-center">{{$grade->remarks}}</td>
                                                    <td></td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        
                                    @if($recordval->type == 1)
                                        <tr>
                                        <td colspan="4">General Ave. for the Semester</td>
                                        <td class="text-center">{{collect($recordval->grades)->where('semid', $recordval->semid)->avg('finalrating')}}</td>
                                        </tr>
                                    @elseif($recordval->type == 2)
                                        @if(count($recordval->grades) > 1)
                                            @foreach(collect($recordval->grades)->where('semid', $recordval->semid) as $grade)
                                                @if(strtolower($grade->subjdesc) == 'general average')
                                                    <tr style="font-weight: bold;">
                                                        <td colspan="4">General Average</td>
                                                        <td class="text-center">{{$grade->finalrating}}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endif
                                    @endif
                                    @endif
                                </tbody>
                            </table>
                            <div class="row mt-2 mb-2" style="font-size: 11px;">
                                <div class="col-2">
                                    <label>REMARKS</label>
                                </div>
                                <div class="col-10">
                                    <input type="text" class="form-control form-control-sm" value="{{$recordval->remarks}}"/>
                                </div>
                            </div>
                            <div class="row" style="font-size: 11px;">
                                <div class="col-4">
                                    <label>Prepared by:</label>
                                    <input type="text" class="form-control form-control-sm" value="{{$recordval->teachername}}"/>
                                    <span>Class Adviser</span>
                                </div>
                                <div class="col-4">
                                <label>Certified True and Correct:</label>
                                <input type="text" class="form-control form-control-sm" value="{{$recordval->recordincharge}}"/>
                                <span>SHS-School Record's In-charge</span>
                                </div>
                                <div class="col-4">
                                <label>Date Checked:</label>
                                <input type="date" class="form-control form-control-sm" value="{{$recordval->datechecked}}"/>
                                </div>
                            </div>
                            <br/>
                            <br/>

                            <table class="table table-bordered" style="font-size: 11px;">
                                <thead>
                                    @if(collect($recordval->remedials)->contains('type','2'))                                              
                                    @foreach($recordval->remedials as $remedial)  
                                        @if($remedial->type == 2)
                                            <tr>
                                                <th style="width: 10%;">REMEDIAL CLASSES</th>
                                                <th colspan="2">CONDUCTED FROM: @if($remedial->datefrom!=null) <u>{{date('m/d/Y',strtotime($remedial->datefrom))}}</u> @endif &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TO:  @if($remedial->dateto!=null) <u>{{date('m/d/Y',strtotime($remedial->dateto))}}</u> @endif </th>
                                                <th>SCHOOL: {{$remedial->schoolname}}</th>
                                                <th></th>
                                                <th>SCHOOL ID: {{$remedial->schoolid}}</th>
                                            </tr>
                                        @endif
                                    @endforeach
                                    @else
                                    <tr>
                                        <th style="width: 10%;">REMEDIAL CLASSES</th>
                                        <th colspan="2">CONDUCTED FROM TO</th>
                                        <th>SCHOOL:</th>
                                        <th></th>
                                        <th>SCHOOL ID:</th>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th>INDICATE IF SUBJECT IS CORE, APPLIED, OR SPECIALIZED	</th>
                                        <th>SUBJECTS</th>
                                        <th>SEM FINAL GRADE</th>
                                        <th>REMEDIAL CLASS MARK</th>
                                        <th>RECOMPUTED FINAL GRADE</th>
                                        <th>ACTION TAKE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($recordval->remedials)>0)
                                        @foreach($recordval->remedials as $remedial)
                                            @if($remedial->type == 1)
                                                <tr>
                                                    <td>{{$remedial->subjectcode}}</td>
                                                    <td>{{$remedial->subjectname}}</td>
                                                    <td>{{$remedial->finalrating}}</td>
                                                    <td>{{$remedial->remclassmark}}</td>
                                                    <td>{{$remedial->recomputedfinal}}</td>
                                                    <td>{{$remedial->remarks}}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @else
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <!-- fullCalendar 2.2.5 -->
    <!-- InputMask -->
    <script src="{{asset('plugins/moment/moment.min.js')}}"></script>
    <script src="{{asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
    <!-- date-range-picker -->
    <script src="{{asset('plugins/jquery-year-picker/js/yearpicker.js')}}"></script>
    <script src="{{asset('assets/scripts/gijgo.min.js')}}" ></script>
    <script>
        $(document).ready(function(){

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });

            var completerhs = $('#checkbox-completerhs').val()
            var completerjh = $('#checkbox-completerjh').val()
            var peptpasser = $('#checkbox-peptpasser').val()
            var alspasser = $('#checkbox-alspasser').val()

            $('#btn-eligibility-update').on('click', function(){
                var generalaveragehs = $('#generalaveragehs').val()
                var generalaveragejh = $('#generalaveragejh').val()
                var graduationdate = $('#graduationdate').val()

                var schoolname = $('#schoolname').val();
                var schooladdress = $('#schooladdress').val();
                var peptrating = $('#peptrating').val();
                var alsrating = $('#alsrating').val();
                var specify = $('#specify').val();
                var examdate = $('#examdate').val();
                var centername = $('#centername').val();
                
                $.ajax({
                    url: '/reports_schoolform10/updateeligibility',
                    type: 'GET',
                    data:{
                        studentid           : '{{$studentid}}',
                        acadprogid          :   5,
                        completerhs         :   completerhs,
                        completerjh         :   completerjh,
                        generalaveragehs    :   generalaveragehs,
                        generalaveragejh    :   generalaveragejh,
                        graduationdate      :   graduationdate,
                        peptpasser          :   peptpasser,
                        alspasser           :   alspasser,
                        peptrating          :   peptrating,
                        alsrating           :   alsrating,
                        schoolname          :   schoolname,
                        schooladdress       :   schooladdress,
                        examdate            :   examdate,
                        others              :   specify,
                        centername          :   centername
                    }, success:function(data)
                    {

                        Toast.fire({
                            type: 'success',
                            title: 'Eligibility',
                            html: 'Updated successfully!'
                        })
                    }
                });
            })
            $('#btn-exporttopdf').on('click', function(){
                // selectedschoolyear
                // selectsemester
                // selectgradelevel
                // studentid
                // selectedsectionid
                window.open('/reports/form9/view?selectedschoolyear={{$selectedschoolyear}}&selectsemester={{$selectsemester}}&selectgradelevel={{$selectgradelevel}}&studentid={{$studentid}}&selectedsectionid={{$selectedsectionid}}&export=1');
            })
        });
    </script>
@endsection