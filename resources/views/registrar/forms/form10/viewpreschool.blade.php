
@extends($extends.'.layouts.app')
@section('content')
    <link rel="stylesheet" href="{{asset('plugins/jquery-year-picker/css/yearpicker.css')}}" />
    <style>
        td                                          { border-bottom: hidden; }
        .input-group-text, .select{ background-color: white !important; border: hidden; border-bottom: 2px solid #ddd; font-size: 12px !important; }
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
    <div class="row">
        <div class="col-12">
            <div class="card card-default color-palette-box">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-8">
                            <h3><strong>Learner's Permanent Academic Record</strong></h3>
                            <small><em>(Formerly Form 137)</em></small>
                        </div>
                        <div class="col-md-4 text-right">
                            
                                <form action="/reports_schoolform10/getrecordspreschool" target="_blank" method="get" class="m-0 p-0" style="display: inline;">
                                    <input type="hidden" value="1" name="export"/>
                                    <input type="hidden" value="{{$studentid}}" name="studentid"/>
                                    <input type="hidden" value="{{$acadprogid}}" name="acadprogid"/>
                                    <input type="hidden" value="pdf" name="exporttype"/>
                                    <button type="submit" class="btn btn-primary btn-sm text-white" id="btn-exportpdf">
                                        <i class="fa fa-file-pdf"></i>
                                            PDF
                                    </button>
                                </form>
                                <form action="/reports_schoolform10/getrecordspreschool" target="_blank" method="get" class="m-0 p-0" style="display: inline;">
                                    <input type="hidden" value="1" name="export"/>
                                    <input type="hidden" value="{{$studentid}}" name="studentid"/>
                                    <input type="hidden" value="{{$acadprogid}}" name="acadprogid"/>
                                    <input type="hidden" value="excel" name="exporttype"/>
                                    <button type="submit" class="btn btn-primary btn-sm text-white">
                                        <i class="fa fa-file-excel"></i>
                                    EXCEL
                                    </button>
                                </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                    {{-- <div class="row">
                        <div class="col-12 bg-gray text-center mb-2">
                            <h6>ELIGIBILITY FOR ELEMENTARY SCHOOL ENROLMENT</h6>
                        </div>
                    </div>
                    <div class="row p-1" style="font-size: 12px; border: 1px solid black;">
                        <div class="col-3">
                            Credential Presented for Grade 1:
                        </div>
                        
                        <div class="col-3">
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                  <input type="checkbox"  id="checkbox-kinderprogressreport" value="{{$eligibility->kinderprogreport}}" @if($eligibility->kinderprogreport == 1) checked="" @endif>
                                  <label for="checkbox-kinderprogressreport">
                                      Kinder Progress Report
                                  </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                  <input type="checkbox"  id="checkbox-eccdchecklist" value="{{$eligibility->eccdchecklist}}" @if($eligibility->eccdchecklist == 1) checked="" @endif>
                                  <label for="checkbox-eccdchecklist">
                                      ECCD Checklist
                                  </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                  <input type="checkbox"  id="checkbox-kindergartencert" value="{{$eligibility->kindergartencert}}" @if($eligibility->kindergartencert == 1) checked="" @endif>
                                  <label for="checkbox-kindergartencert">
                                        Kindergarten Certificate of Completion
                                  </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            Name of School: <input type="text" class="form-control" id="schoolname" value="{{$eligibility->schoolname}}"/>
                        </div>
                        <div class="col-4">
                            School ID: <input type="text" class="form-control" id="schoolid" value="{{$eligibility->schoolid}}"/>
                        </div>
                        <div class="col-4">
                            Address of School: <input type="text" class="form-control" id="schooladdress" value="{{$eligibility->schooladdress}}"/>
                        </div>
                    </div>
                    <div class="row" style="font-size: 12px;">
                        <div class="col-12">
                            Other Credential Presented
                        </div>
                        <div class="col-4">
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                  <input type="checkbox" id="checkbox-peptpasser" value="{{$eligibility->pept}}" @if($eligibility->pept == 1) checked="" @endif>
                                  <label for="checkbox-peptpasser">
                                        PEPT Passer
                                  </label>
                                </div>
                            </div>
                            Rating: <input type="text" id="peptrating" class="form-control form-control-sm" value="{{$eligibility->peptrating}}"/>
                        </div>
                        <div class="col-4">
                            Date of Examination/Assessment (mm/dd/yyyy):<input type="date" id="examdate" class="form-control form-control-sm" value="{{$eligibility->examdate}}"/>
                        </div>
                        <div class="col-4">
                            Other (Pls.Specify)
                            <textarea class="form-control" id="specify">{{$eligibility->specifyothers}}</textarea>
                        </div>
                    </div>
                    <div class="row mt-2" style="font-size: 12px;position: relative;">
                        <div class="col-3"><span style="position: absolute;bottom: 0;">Name and Address of Testing Center:</span></div>
                        <div class="col-5"><input type="text" id="centername" class="form-control form-control-sm" value="{{$eligibility->centername}}"/></div>
                        <div class="col-1"><span style="position: absolute;bottom: 0;">Remarks:</span></div>
                        <div class="col-3"><input type="text" id="remarks" class="form-control form-control-sm" value="{{$eligibility->remarks}}"/></div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-12 text-right">
                            <button type="button" class="btn btn-sm btn-primary" id="btn-eligibility-update"><i class="fa fa-edit"></i> Update</button>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb2">
        <div class="col-md-4">
            <label>Select School Year</label>
            <select class="form-control" id="selectsy">
                @foreach(DB::table('sy')->orderByDesc('sydesc')->get() as $sy)
                    <option value="{{$sy->id}}" @if($sy->isactive == 1) selected @endif>{{$sy->sydesc}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-8 text-right">
            <label>&nbsp;</label><br/>
            <button type="button" class="btn btn-primary"  id="btn-generate"><i class="fa fa-sync"></i> Generate</button>
        </div>
    </div>
    <div id="table-container">
    </div>
@endsection
@section('footerscripts')
    <script>
        $(document).ready(function(){

            // $('#btn-exportpdf').on('click', function(){
            //     window.open('/prinsf9print/{{$studentid}}?studid={{$studentid}}&action=printpdf&syid='+$('#selectsy').val())
            // })
            $('#btn-generate').on('click', function(){
                
            

                Swal.fire({
                    title: 'Generating results...',
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: false
                })
                
                $.ajax({
            
                    url: "/reports_schoolform10/getrecordspreschool",

                    type: "GET",

                    data: {
                            syid      : $('#selectsy').val(),
                            studentid        : '{{$studentid}}',
                            studid        : '{{$studentid}}',
                            action        : 'show'
                        },

                    success: function (data) {
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')
                        $('#table-container').empty()
                        $('#table-container').append(data)
                    }
                })
            })
            $(document).on('click','#btn-submit-levelinfo', function(){
                var schoolid = $('#input-schoolid').val();
                var schoolname = $('#input-schoolname').val();
                var levelname = $('#input-levelname').val();
                var section = $('#input-section').val();
                var adviser = $('#input-adviser').val();
                var syid = $('#selectsy').val()

                Swal.fire({
                    title: 'Saving changes...',
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: false
                })

                $.ajax({

                    url: "/reports_schoolform10/getgrades",

                    type: "GET",

                    data: {
                            schoolid      : schoolid,
                            schoolname      : schoolname,
                            levelname      : levelname,
                            sectionname      : section,
                            teachername      : adviser,
                            syid      : syid,
                            studid        : '{{$studentid}}',
                            action        : 'updateinfo'
                        },

                    success: function (data) {
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')
                        toastr.success('Updated successfully!')
                    }
                })
            })
        })
    </script>
@endsection