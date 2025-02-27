@extends('teacher.layouts.app')

@section('content')
<style>
    .summaryTable th, .summaryTable td{
        font-size: 11px;
        border:1px solid black !important;
        text-align: center;
        /* table-layout: fixed; */
        padding: 3px;
    }
    .summaryTable{
        table-layout: fixed;
    }
    #header, #header th, #header td{
        font-size: 12px;
        border: none !important;
        /* border:1px solid black !important; */
        padding:2px;
        text-align: right;
    }
    input[type=text]{
        text-align: center;
        width:100%;
    }
    .leftAlign{
        text-align: left !important;
    }
    #female{
        width: 5%;
    }
    .guidelines{
        font-size: 12px;
    }

</style>
<div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-10">
                <h4>School Form 4 (SF4) Monthly Learner's Movement and Attendance</h4>
                <em>This replaces Form 3 & STS Form 4-Absenteeism and Dropout Profile</em>
          </div>
          <div class="col-sm-2">
            <ol class="breadcrumb float-sm-right">
                    <a href="/schoolForm_4/preview" target="_blank" class="btn btn-success btn-sm text-white">
                        <i class="fa fa-upload"></i>
                    Print
                </a>
            </ol>
          </div>
        </div>
      </div>
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="main-card mb-3 card ">
                <div class="card-body">
                    <table id="header" class="table table-bordered">
                        <thead>
                            <tr>
                                <th rowspan="2" style="padding:10px;width:5%">
                                    <center><img src="{{asset('assets/images/department_of_Education.png')}}" alt="school" width="80px"></center>
                                </th>
                                <th width="10%">School ID</th>
                                <th><input type="text" value="{{$school[0]->schoolid}}" readonly/></th>
                                <th style="padding:0px 2px 0px 20px;">Region</th>
                                <th style="width: 10%"><input type="text" value="{{$school[0]->region}}" readonly/></th>
                                <th>Division</th>
                                <th colspan="2" style="width:20%"><input type="text" value="{{$school[0]->division}}" readonly/></th>
                                {{-- <th>District</th>
                                <th colspan="2"><input type="text" value="{{$school[0]->district}}"/></th> --}}
                                <th>District</th>
                                <th colspan="2"><input type="text" value="{{$school[0]->district}}" readonly/></th>
                                <th colspan="2"></th>
                            </tr>
                            <tr>
                                <th>School Name</th>
                                <th colspan="5"><input type="text" value="{{$school[0]->schoolname}}" readonly/></th>
                                <th colspan="2">School Year</th>
                                <th><input type="text" value="{{$sy}}" readonly/></th>
                                <th colspan="2" style="padding:0px 2px 0px 40px;width:10%">Report for the Month of</th>
                                <th><input type="text" value="" readonly/></th>
                            </tr>
                        </thead>
                    </table>
                    <br>
                    <table class="table table-bordered summaryTable">
                        <tr>
                            <th rowspan="3" style="width:4%;">GRADE/YEAR LEVEL</th>
                            <th rowspan="3" style="width:6%;">SECTION</th>
                            <th rowspan="3" style="width:15%;">NAME OF ADVISER</th>
                            <th rowspan="2" colspan="3">REGISTERED LEARNERS<br>(As of End of the Month)</th>
                            <th colspan="6">ATTENDANCE</th>
                            <th colspan="9">DROPPED OUT</th>
                            <th colspan="9">TRANSFERRED OUT</th>
                            <th colspan="9">TRANSFERRED IN</th>
                        </tr>
                        <tr>
                            <th colspan="3">Daily Average</th>
                            <th colspan="3">Percentage for the Month</th>
                            <th colspan="3">(A) Cumulative as of Previous Month</th>
                            <th colspan="3">(B) For the Month</th>
                            <th colspan="3">(A + B) Cumulative as of End of the Month</th>
                            <th colspan="3">(A) Cumulative as of Previous Month</th>
                            <th colspan="3">(B) For the Month</th>
                            <th colspan="3">(A + B) Cumulative as of End of the Month</th>
                            <th colspan="3">(A) Cumulative as of Previous Month</th>
                            <th colspan="3">(B) For the Month</th>
                            <th colspan="3">(A + B) Cumulative as of End of the Month</th>
                        </tr>
                        <tr>
                            <th>M</th>
                            <th>F</th>
                            <th>T</th>
                            <th>M</th>
                            <th>F</th>
                            <th>T</th>
                            <th>M</th>
                            <th>F</th>
                            <th>T</th>
                            <th>M</th>
                            <th>F</th>
                            <th>T</th>
                            <th>M</th>
                            <th>F</th>
                            <th>T</th>
                            <th>M</th>
                            <th>F</th>
                            <th>T</th>
                            <th>M</th>
                            <th>F</th>
                            <th>T</th>
                            <th>M</th>
                            <th>F</th>
                            <th>T</th>
                            <th>M</th>
                            <th>F</th>
                            <th>T</th>
                            <th>M</th>
                            <th>F</th>
                            <th>T</th>
                            <th>M</th>
                            <th>F</th>
                            <th>T</th>
                            <th>M</th>
                            <th>F</th>
                            <th>T</th>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <th colspan="3">ELEMENTARY/SECONDARY</th>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <th colspan="3">&nbsp;</th>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <th colspan="3">TOTAL</th>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                    <br> 
                    <div class="row guidelines">
                            <div class="col-md-7">
                                <span>GUIDELINES:</span>
                                <ol class="pl-3">
                                    <li>This form shall be accomplish every end of the month using the summary box of SF2 submitted by the teachers/advisers to update figures for the month.</li>
                                    <li>Furnish the Division Office with a copy a week after June 30, October 30 & March 31</li>
                                </ol>
                            </div>
                            <div class="col-md-5">
                                <strong>Prepared and Submitted by:</strong>
                                <div class="col-md-12">
                                   <center>
                                       <div style="width: 80%;border-bottom: 1px solid black;">&nbsp;</div>
                                       <em>(Signature of School Head over Printed Name)</em>
                                    </center>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>

    <script>
            var $ = jQuery;
  
        </script>
{{-- </div> --}}


@endsection