@extends('teacher.layouts.app')

@section('content')
    <style>
        .table td, .table th            { border: 1px solid black !important; }

        #studentInfo                    { border: hidden; width:100%; font-size: 13px; /* margin: 0px 20px 0px 0px; */ /* font-family: Arial, Helvetica, sans-serif; */ }

        #studentInfo td,#studentInfo th { /* border:1px solid black; */ border: hidden !important; padding:3px; /* margin: 0px 20px 0px 0px; */ /* font-family: Arial, Helvetica, sans-serif; */ }
    </style>
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h2 style="text-transform: uppercase" >
                    <i class="fa fa-user"></i>&nbsp;&nbsp;&nbsp;{{$arrayForm[0][0]->lastname}}, {{$arrayForm[0][0]->firstname}} {{$arrayForm[0][0]->middlename[0]}}.
                </h2>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <a href="{{ url('/form_138/print/'.$studentid) }}" target="_blank" class="btn btn-sm btn-success text-white" target="_blank">
                <i class="fa fa-print"></i> Print</a>
            </ol>
            </div>
        </div>
    </div>
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div>
                    <div class="page-title-subheading">
                        <input type="hidden" id="section_id" value="{{$arrayForm[0][0]->sectionid}}"/>
                        <input type="hidden" id="student_id" value="{{$studentid}}"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="main-card mb-3 card ">
                <div class="card-body">
                    <div class="form-row">
                        <div class="col-md-4">
                            <div class="position-relative form-group ">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">Age</span>
                                    </div>
                                    @if ($arrayForm[1] != 0)
                                        <input type="text" class="form-control" id="validationCustomUsername"  value="{{$arrayForm[1]}}" aria-describedby="inputGroupPrepend" readonly>
                                    @else
                                        <input type="text" class="form-control" id="validationCustomUsername"  value="" aria-describedby="inputGroupPrepend" readonly>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            &nbsp;
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group ">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">Year & Section</span>
                                    </div>
                                    <input type="text" class="form-control" id="validationCustomUsername"  value="{{$arrayForm[2][0]->levelname}} - {{$arrayForm[3][0]->sectionname}}" aria-describedby="inputGroupPrepend" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-4">
                            <div class="position-relative form-group ">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">Gender</span>
                                    </div>
                                    <input type="text" class="form-control" id="validationCustomUsername"  value="{{$arrayForm[0][0]->gender}}" aria-describedby="inputGroupPrepend" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">&nbsp;</div>
                        <div class="col-md-6">
                            <div class="position-relative form-group ">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">School Year</span>
                                    </div>
                                    <input type="text" class="form-control" id="validationCustomUsername"  value="{{$arrayForm[4]}}" aria-describedby="inputGroupPrepend" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p>
                        <strong>
                            <center>REPORT ON ATTENDANCE</center>
                        </strong>
                    </p>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th></th>
                                @foreach($studattrep->monthly as $month)
                                <th>{{$month->month}}</th>
                                @endforeach
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>No. of School Days</td>
                                @foreach($studattrep->monthly as $month)
                                <td>{{$month->numDays}}</td>
                                @endforeach
                                <td>{{$studattrep->yearly->numDays}}</td>
                            </tr>
                            <tr>
                                <td>No. of Days Present</td>
                                @foreach($studattrep->monthly as $month)
                                <td>{{$month->present}}</td>
                                @endforeach
                                <td>{{$studattrep->yearly->present}}</td>
                            </tr>
                            <tr>
                                <td>No. of days absent</td>
                                @foreach($studattrep->monthly as $month)
                                <td>{{$month->absent}}</td>
                                @endforeach
                                <td>{{$studattrep->yearly->absent}}</td>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                    @if($progname=="SENIOR HIGH SCHOOL")
                    <p>
                        <strong>
                            <center>REPORT ON LEARNING PROGRESS AND ACHIEVEMENT<br>Track: <u></u></center>
                        </strong>
                    </p>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th colspan="5" class="bg-warning">1st Sem</th>
                                </tr>
                                <tr>
                                    <th rowspan="2">SUBJECTS</th>
                                    <th colspan="2"><center>PERIODIC RATINGS</center></th>
                                    <th rowspan="2"><center>SEMESTER<br>FINAL RATING</center></th>
                                    <th rowspan="2"><center>REMARKS</center></th>
                                </tr>
                                <tr>
                                    <th><center>1</th>
                                    <th><center>2</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($generateGrade as $grade)
                                <tr>
                                    <td>{{$grade->assignsubject}}</td>
                                    <td><center>{{$grade->firstsem[0]['quarter1']}}</center></td>
                                    <td><center>{{$grade->firstsem[0]['quarter2']}}</center></td>
                                    <td><center>{{$grade->finalRating}}</center></td>
                                    <td><center>{{$grade->remarks}}</center></td>
                                </tr>
                                @endforeach
                                <tr>
                                    <th>GENERAL AVERAGE</th>
                                    <td><center></center></td>
                                    <td><center></center></td>
                                    <td><center></center></td>
                                    <td><center></center></td>
                                </tr>
                            </tbody>
                            <thead>
                                <tr>
                                    <th colspan="5" class="bg-warning">2nd Sem</th>
                                </tr>
                                <tr>
                                    <th rowspan="2">SUBJECTS</th>
                                    <th colspan="2"><center>PERIODIC RATINGS</center></th>
                                    <th rowspan="2"><center>SEMESTER<br>FINAL RATING</center></th>
                                    <th rowspan="2"><center>REMARKS</center></th>
                                </tr>
                                <tr>
                                    <th><center>3</center></th>
                                    <th><center>4</center></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($generateGrade as $grade)
                                <tr>
                                    <td>{{$grade->assignsubject}}</td>
                                    <td><center>{{$grade->secondsem[0]['quarter3']}}</center></td>
                                    <td><center>{{$grade->secondsem[0]['quarter4']}}</center></td>
                                    <td><center>{{$grade->finalRating}}</center></td>
                                    <td><center>{{$grade->remarks}}</center></td>
                                </tr>
                                @endforeach
                                <tr>
                                    <th>GENERAL AVERAGE</th>
                                    <td><center></center></td>
                                    <td><center></center></td>
                                    <td><center></center></td>
                                    <td><center></center></td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="7">
                                        <table id="studentInfo">
                                            <tr>
                                                <th>Grading Scale</th>
                                                <th>Descriptors</th>
                                                <th>Remaks</th>
                                            </tr>
                                            <tr>
                                                <td>A — 90-100</td>
                                                <td>Outstanding</td>
                                                <td>Passed</td>
                                            </tr>
                                            <tr>
                                                <td>B — 85-89</td>
                                                <td>Satisfactory</td>
                                                <td>Passed</td>
                                            </tr>
                                            <tr>
                                                <td>C — 80-84</td>
                                                <td>Needs Improvement</td>
                                                <td>Passed</td>
                                            </tr>
                                            <tr>
                                                <td>D — 75-79</td>
                                                <td>Fairly Satisfactory</td>
                                                <td>Passed</td>
                                            </tr>
                                            <tr>
                                                <td>E — Below 75</td>
                                                <td>Did Not Meet Expectation</td>
                                                <td>Failed</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    @else
                    <p>
                        <strong>
                            <center>REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</center>
                        </strong>
                    </p>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th rowspan="2">SUBJECTS</th>
                                <th colspan="4"><center>PERIODIC RATINGS</center></th>
                                <th rowspan="2"><center>SEMESTER<br>FINAL RATING</center></th>
                                <th rowspan="2"><center>REMARKS</center></th>
                            </tr>
                            <tr>
                                <th><center>1</center></th>
                                <th><center>2</center></th>
                                <th><center>3</center></th>
                                <th><center>4</center></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $generalAverage = 0;
                                $countAve = 0;  
                            @endphp
                            @foreach ($generateGrade as $grade)
                            <tr>
                                <td>{{$grade->assignsubject}}</td>
                                <td><center>{{$grade->quarter1}}</center></td>
                                <td><center>{{$grade->quarter2}}</center></td>
                                <td><center>{{$grade->quarter3}}</center></td>
                                <td><center>{{$grade->quarter4}}</center></td>
                                <td><center>{{$grade->finalRating}}</center></td>
                                <td><center>{{$grade->remarks}}</center></td>
                            </tr>
                            @php
                                $generalAverage+=$grade->finalRating;  
                                $countAve+= 1;  
                            @endphp
                            @endforeach
                            <tr>
                                <th colspan="5">GENERAL AVERAGE</th>
                                <td>
                                    <center>
                                        @if($generalAverage != 0)
                                            @if (($generalAverage/$countAve) <=94.99)
                                                {{round(($generalAverage)/($countAve),2)}}
                                            @elseif($generalAverage/$countAve>=95)
                                                {{round(($generalAverage)/($countAve))}}
                                            @endif
                                        @else
                                        @endif
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        @if($generalAverage != 0)
                                            @if ($generalAverage/$countAve>=75)
                                                PASSED
                                            @else
                                                FAILED
                                            @endif
                                        @else
                                        @endif
                                    </center>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="7">
                                    <table id="studentInfo">
                                        <tr>
                                            <th>Grading Scale</th>
                                            <th>Descriptors</th>
                                            <th>Remaks</th>
                                        </tr>
                                        <tr>
                                            <td>A — 90-100</td>
                                            <td>Outstanding</td>
                                            <td>Passed</td>
                                        </tr>
                                        <tr>
                                            <td>B — 85-89</td>
                                            <td>Satisfactory</td>
                                            <td>Passed</td>
                                        </tr>
                                        <tr>
                                            <td>C — 80-84</td>
                                            <td>Needs Improvement</td>
                                            <td>Passed</td>
                                        </tr>
                                        <tr>
                                            <td>D — 75-79</td>
                                            <td>Fairly Satisfactory</td>
                                            <td>Passed</td>
                                        </tr>
                                        <tr>
                                            <td>E — Below 75</td>
                                            <td>Did Not Meet Expectation</td>
                                            <td>Failed</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    @endif
                    <br>
                    <p>
                        <strong>
                            <center>REPORT ON LEARNER'S OBSERVED VALUES</center>
                        </strong>
                    </p>
                    {{-- {{$getValues['quarter2'][0]->makaDiyos_1}} --}}
                    <table class="table table-bordered" >
                        <thead>
                            <tr>
                                <th rowspan="2"><center>Core Values</center></th>
                                <th rowspan="2"><center>Behavior Statements</center></th>
                                <th colspan="4"><center>Quarter</center></th>
                            </tr>
                            <tr>
                                <th>1</th>
                                <th>2</th>
                                <th>3</th>
                                <th>4</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th rowspan="2">1. Maka-Diyos</th>
                                <td >Expesses one's spiritual beliefs while especting the beliefs of others</td>
                                <td>
                                    <select name="makaDiyos_1" class="1">
                                        @if(count($getValues)==0)
                                            <option value=""></option>
                                            <option value="AO">AO</option>
                                            <option value="SO">SO</option>
                                            <option value="RO">RO</option>
                                            <option value="NO">NO</option>
                                        @elseif(count($getValues)>0)
                                            @if(isset($getValues['quarter1'][0]->makaDiyos_1))
                                                <option value=" " {{($getValues['quarter1'][0]->makaDiyos_1) == " " ? 'selected' : ''}}></option>
                                                <option value="AO" {{($getValues['quarter1'][0]->makaDiyos_1) == "AO" ? 'selected' : ''}}>AO</option>
                                                <option value="SO" {{($getValues['quarter1'][0]->makaDiyos_1) == "SO" ? 'selected' : ''}}>SO</option>
                                                <option value="RO" {{($getValues['quarter1'][0]->makaDiyos_1) == "RO" ? 'selected' : ''}}>RO</option>
                                                <option value="NO" {{($getValues['quarter1'][0]->makaDiyos_1) == "NO" ? 'selected' : ''}}>NO</option>
                                            @else
                                                <option value=" "></option>
                                                <option value="AO">AO</option>
                                                <option value="SO">SO</option>
                                                <option value="RO">RO</option>
                                                <option value="NO">NO</option>
                                            @endif
                                        @endif
                                    </select>
                                </td>
                                <td>
                                    <select name="makaDiyos_1" class="2">
                                        @if(count($getValues)==0)
                                            <option value=""></option>
                                            <option value="AO">AO</option>
                                            <option value="SO">SO</option>
                                            <option value="RO">RO</option>
                                            <option value="NO">NO</option>
                                        @elseif(count($getValues)>0)
                                            @if(isset($getValues['quarter2'][0]->makaDiyos_1))
                                                <option value=" " {{($getValues['quarter2'][0]->makaDiyos_1) == " " ? 'selected' : ''}}></option>
                                                <option value="AO" {{($getValues['quarter2'][0]->makaDiyos_1) == "AO" ? 'selected' : ''}}>AO</option>
                                                <option value="SO" {{($getValues['quarter2'][0]->makaDiyos_1) == "SO" ? 'selected' : ''}}>SO</option>
                                                <option value="RO" {{($getValues['quarter2'][0]->makaDiyos_1) == "RO" ? 'selected' : ''}}>RO</option>
                                                <option value="NO" {{($getValues['quarter2'][0]->makaDiyos_1) == "NO" ? 'selected' : ''}}>NO</option>
                                            @else
                                                <option value=""></option>
                                                <option value="AO">AO</option>
                                                <option value="SO">SO</option>
                                                <option value="RO">RO</option>
                                                <option value="NO">NO</option>
                                            @endif
                                        @endif
                                    </select>
                                </td>
                                <td>
                                    <select name="makaDiyos_1" class="3">
                                        @if(count($getValues)==0)
                                            <option value=""></option>
                                            <option value="AO">AO</option>
                                            <option value="SO">SO</option>
                                            <option value="RO">RO</option>
                                            <option value="NO">NO</option>
                                        @elseif(count($getValues)>0)
                                            @if(isset($getValues['quarter3'][0]->makaDiyos_1))
                                                <option value=" " {{($getValues['quarter3'][0]->makaDiyos_1) == " " ? 'selected' : ''}}></option>
                                                <option value="AO" {{($getValues['quarter3'][0]->makaDiyos_1) == "AO" ? 'selected' : ''}}>AO</option>
                                                <option value="SO" {{($getValues['quarter3'][0]->makaDiyos_1) == "SO" ? 'selected' : ''}}>SO</option>
                                                <option value="RO" {{($getValues['quarter3'][0]->makaDiyos_1) == "RO" ? 'selected' : ''}}>RO</option>
                                                <option value="NO" {{($getValues['quarter3'][0]->makaDiyos_1) == "NO" ? 'selected' : ''}}>NO</option>
                                            @else
                                                <option value=""></option>
                                                <option value="AO">AO</option>
                                                <option value="SO">SO</option>
                                                <option value="RO">RO</option>
                                                <option value="NO">NO</option>
                                            @endif
                                        @endif
                                    </select>
                                </td>
                                <td>
                                    <select name="makaDiyos_1" class="4">
                                        @if(count($getValues)==0)
                                            <option value=""></option>
                                            <option value="AO">AO</option>
                                            <option value="SO">SO</option>
                                            <option value="RO">RO</option>
                                            <option value="NO">NO</option>
                                        @elseif(count($getValues)>0)
                                            @if(isset($getValues['quarter4'][0]->makaDiyos_1))
                                                <option value=" " {{($getValues['quarter4'][0]->makaDiyos_1) == " " ? 'selected' : ''}}></option>
                                                <option value="AO" {{($getValues['quarter4'][0]->makaDiyos_1) == "AO" ? 'selected' : ''}}>AO</option>
                                                <option value="SO" {{($getValues['quarter4'][0]->makaDiyos_1) == "SO" ? 'selected' : ''}}>SO</option>
                                                <option value="RO" {{($getValues['quarter4'][0]->makaDiyos_1) == "RO" ? 'selected' : ''}}>RO</option>
                                                <option value="NO" {{($getValues['quarter4'][0]->makaDiyos_1) == "NO" ? 'selected' : ''}}>NO</option>
                                            @else
                                                <option value=""></option>
                                                <option value="AO">AO</option>
                                                <option value="SO">SO</option>
                                                <option value="RO">RO</option>
                                                <option value="NO">NO</option>
                                            @endif
                                        @endif
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Shows adherence to ethical principles by upholding the truth in all undertakings</td>
                                <td>
                                    <select name="makaDiyos_2" class="1">
                                        @if(count($getValues)==0)
                                            <option value=""></option>
                                            <option value="AO">AO</option>
                                            <option value="SO">SO</option>
                                            <option value="RO">RO</option>
                                            <option value="NO">NO</option>
                                        @elseif(count($getValues)>0)
                                            @if(isset($getValues['quarter1'][0]->makaDiyos_2))
                                                <option value=" " {{($getValues['quarter1'][0]->makaDiyos_2) == " " ? 'selected' : ''}}></option>
                                                <option value="AO" {{($getValues['quarter1'][0]->makaDiyos_2) == "AO" ? 'selected' : ''}}>AO</option>
                                                <option value="SO" {{($getValues['quarter1'][0]->makaDiyos_2) == "SO" ? 'selected' : ''}}>SO</option>
                                                <option value="RO" {{($getValues['quarter1'][0]->makaDiyos_2) == "RO" ? 'selected' : ''}}>RO</option>
                                                <option value="NO" {{($getValues['quarter1'][0]->makaDiyos_2) == "NO" ? 'selected' : ''}}>NO</option>
                                            @else
                                                <option value=""></option>
                                                <option value="AO">AO</option>
                                                <option value="SO">SO</option>
                                                <option value="RO">RO</option>
                                                <option value="NO">NO</option>
                                            @endif
                                        @endif
                                    </select>
                                </td>
                                <td>
                                    <select name="makaDiyos_2" class="2">
                                        @if(count($getValues)==0)
                                            <option value=""></option>
                                            <option value="AO">AO</option>
                                            <option value="SO">SO</option>
                                            <option value="RO">RO</option>
                                            <option value="NO">NO</option>
                                        @elseif(count($getValues)>0)
                                            @if(isset($getValues['quarter2'][0]->makaDiyos_2))
                                                <option value=" " {{($getValues['quarter2'][0]->makaDiyos_2) == " " ? 'selected' : ''}}></option>
                                                <option value="AO" {{($getValues['quarter2'][0]->makaDiyos_2) == "AO" ? 'selected' : ''}}>AO</option>
                                                <option value="SO" {{($getValues['quarter2'][0]->makaDiyos_2) == "SO" ? 'selected' : ''}}>SO</option>
                                                <option value="RO" {{($getValues['quarter2'][0]->makaDiyos_2) == "RO" ? 'selected' : ''}}>RO</option>
                                                <option value="NO" {{($getValues['quarter2'][0]->makaDiyos_2) == "NO" ? 'selected' : ''}}>NO</option>
                                            @else
                                                <option value=""></option>
                                                <option value="AO">AO</option>
                                                <option value="SO">SO</option>
                                                <option value="RO">RO</option>
                                                <option value="NO">NO</option>
                                            @endif
                                        @endif
                                    </select>
                                </td>
                                <td>
                                    <select name="makaDiyos_2" class="3">
                                        @if(count($getValues)==0)
                                            <option value=""></option>
                                            <option value="AO">AO</option>
                                            <option value="SO">SO</option>
                                            <option value="RO">RO</option>
                                            <option value="NO">NO</option>
                                        @elseif(count($getValues)>0)
                                            @if(isset($getValues['quarter3'][0]->makaDiyos_2))
                                                <option value=" " {{($getValues['quarter3'][0]->makaDiyos_2) == " " ? 'selected' : ''}}></option>
                                                <option value="AO" {{($getValues['quarter3'][0]->makaDiyos_2) == "AO" ? 'selected' : ''}}>AO</option>
                                                <option value="SO" {{($getValues['quarter3'][0]->makaDiyos_2) == "SO" ? 'selected' : ''}}>SO</option>
                                                <option value="RO" {{($getValues['quarter3'][0]->makaDiyos_2) == "RO" ? 'selected' : ''}}>RO</option>
                                                <option value="NO" {{($getValues['quarter3'][0]->makaDiyos_2) == "NO" ? 'selected' : ''}}>NO</option>
                                            @else
                                                <option value=""></option>
                                                <option value="AO">AO</option>
                                                <option value="SO">SO</option>
                                                <option value="RO">RO</option>
                                                <option value="NO">NO</option>
                                            @endif
                                        @endif
                                    </select>
                                </td>
                                <td>
                                    <select name="makaDiyos_2" class="4">
                                        @if(count($getValues)==0)
                                            <option value=""></option>
                                            <option value="AO">AO</option>
                                            <option value="SO">SO</option>
                                            <option value="RO">RO</option>
                                            <option value="NO">NO</option>
                                        @elseif(count($getValues)>0)
                                            @if(isset($getValues['quarter4'][0]->makaDiyos_2))
                                                <option value=" " {{($getValues['quarter4'][0]->makaDiyos_2) == " " ? 'selected' : ''}}></option>
                                                <option value="AO" {{($getValues['quarter4'][0]->makaDiyos_2) == "AO" ? 'selected' : ''}}>AO</option>
                                                <option value="SO" {{($getValues['quarter4'][0]->makaDiyos_2) == "SO" ? 'selected' : ''}}>SO</option>
                                                <option value="RO" {{($getValues['quarter4'][0]->makaDiyos_2) == "RO" ? 'selected' : ''}}>RO</option>
                                                <option value="NO" {{($getValues['quarter4'][0]->makaDiyos_2) == "NO" ? 'selected' : ''}}>NO</option>
                                            @else
                                                <option value=""></option>
                                                <option value="AO">AO</option>
                                                <option value="SO">SO</option>
                                                <option value="RO">RO</option>
                                                <option value="NO">NO</option>
                                            @endif
                                        @endif
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>2. Makatao</th>
                                <td>Is sensitive to individual, social and cultural differences; resists stereotyping people</td>
                                <td>
                                    <select name="makaTao" class="1">
                                        @if(count($getValues)==0)
                                            <option value=""></option>
                                            <option value="AO">AO</option>
                                            <option value="SO">SO</option>
                                            <option value="RO">RO</option>
                                            <option value="NO">NO</option>
                                        @elseif(count($getValues)>0)
                                            @if(isset($getValues['quarter1'][0]->makaTao))
                                                <option value=" " {{($getValues['quarter1'][0]->makaTao) == " " ? 'selected' : ''}}></option>
                                                <option value="AO" {{($getValues['quarter1'][0]->makaTao) == "AO" ? 'selected' : ''}}>AO</option>
                                                <option value="SO" {{($getValues['quarter1'][0]->makaTao) == "SO" ? 'selected' : ''}}>SO</option>
                                                <option value="RO" {{($getValues['quarter1'][0]->makaTao) == "RO" ? 'selected' : ''}}>RO</option>
                                                <option value="NO" {{($getValues['quarter1'][0]->makaTao) == "NO" ? 'selected' : ''}}>NO</option>
                                            @else
                                                <option value=""></option>
                                                <option value="AO">AO</option>
                                                <option value="SO">SO</option>
                                                <option value="RO">RO</option>
                                                <option value="NO">NO</option>
                                            @endif
                                        @endif
                                    </select>
                                </td>
                                <td>
                                    <select name="makaTao" class="2">
                                        @if(count($getValues)==0)
                                            <option value=""></option>
                                            <option value="AO">AO</option>
                                            <option value="SO">SO</option>
                                            <option value="RO">RO</option>
                                            <option value="NO">NO</option>
                                        @elseif(count($getValues)>0)
                                            @if(isset($getValues['quarter2'][0]->makaTao))
                                                <option value=" " {{($getValues['quarter2'][0]->makaTao) == " " ? 'selected' : ''}}></option>
                                                <option value="AO" {{($getValues['quarter2'][0]->makaTao) == "AO" ? 'selected' : ''}}>AO</option>
                                                <option value="SO" {{($getValues['quarter2'][0]->makaTao) == "SO" ? 'selected' : ''}}>SO</option>
                                                <option value="RO" {{($getValues['quarter2'][0]->makaTao) == "RO" ? 'selected' : ''}}>RO</option>
                                                <option value="NO" {{($getValues['quarter2'][0]->makaTao) == "NO" ? 'selected' : ''}}>NO</option>
                                            @else
                                                <option value=""></option>
                                                <option value="AO">AO</option>
                                                <option value="SO">SO</option>
                                                <option value="RO">RO</option>
                                                <option value="NO">NO</option>
                                            @endif
                                        @endif
                                    </select>
                                </td>
                                <td>
                                    <select name="makaTao" class="3">
                                        @if(count($getValues)==0)
                                            <option value=""></option>
                                            <option value="AO">AO</option>
                                            <option value="SO">SO</option>
                                            <option value="RO">RO</option>
                                            <option value="NO">NO</option>
                                        @elseif(count($getValues)>0)
                                            @if(isset($getValues['quarter3'][0]->makaTao))
                                                <option value=" " {{($getValues['quarter3'][0]->makaTao) == " " ? 'selected' : ''}}></option>
                                                <option value="AO" {{($getValues['quarter3'][0]->makaTao) == "AO" ? 'selected' : ''}}>AO</option>
                                                <option value="SO" {{($getValues['quarter3'][0]->makaTao) == "SO" ? 'selected' : ''}}>SO</option>
                                                <option value="RO" {{($getValues['quarter3'][0]->makaTao) == "RO" ? 'selected' : ''}}>RO</option>
                                                <option value="NO" {{($getValues['quarter3'][0]->makaTao) == "NO" ? 'selected' : ''}}>NO</option>
                                            @else
                                                <option value=""></option>
                                                <option value="AO">AO</option>
                                                <option value="SO">SO</option>
                                                <option value="RO">RO</option>
                                                <option value="NO">NO</option>
                                            @endif
                                        @endif
                                    </select>
                                </td>
                                <td>
                                    <select name="makaTao" class="4">
                                        @if(count($getValues)==0)
                                            <option value=""></option>
                                            <option value="AO">AO</option>
                                            <option value="SO">SO</option>
                                            <option value="RO">RO</option>
                                            <option value="NO">NO</option>
                                        @elseif(count($getValues)>0)
                                            @if(isset($getValues['quarter4'][0]->makaTao))
                                                <option value=" " {{($getValues['quarter4'][0]->makaTao) == " " ? 'selected' : ''}}></option>
                                                <option value="AO" {{($getValues['quarter4'][0]->makaTao) == "AO" ? 'selected' : ''}}>AO</option>
                                                <option value="SO" {{($getValues['quarter4'][0]->makaTao) == "SO" ? 'selected' : ''}}>SO</option>
                                                <option value="RO" {{($getValues['quarter4'][0]->makaTao) == "RO" ? 'selected' : ''}}>RO</option>
                                                <option value="NO" {{($getValues['quarter4'][0]->makaTao) == "NO" ? 'selected' : ''}}>NO</option>
                                            @else
                                                <option value=""></option>
                                                <option value="AO">AO</option>
                                                <option value="SO">SO</option>
                                                <option value="RO">RO</option>
                                                <option value="NO">NO</option>
                                            @endif
                                        @endif
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th rowspan="2">3. Makakalikasan</th>
                                <td>Demonstrates contributions towards solidarity</td>
                                <td>
                                    <select name="makaKalikasan_1" class="1">
                                        @if(count($getValues)==0)
                                            <option value=""></option>
                                            <option value="AO">AO</option>
                                            <option value="SO">SO</option>
                                            <option value="RO">RO</option>
                                            <option value="NO">NO</option>
                                        @elseif(count($getValues)>0)
                                            @if(isset($getValues['quarter1'][0]->makaKalikasan_1))
                                                <option value=" " {{($getValues['quarter1'][0]->makaKalikasan_1) == " " ? 'selected' : ''}}></option>
                                                <option value="AO" {{($getValues['quarter1'][0]->makaKalikasan_1) == "AO" ? 'selected' : ''}}>AO</option>
                                                <option value="SO" {{($getValues['quarter1'][0]->makaKalikasan_1) == "SO" ? 'selected' : ''}}>SO</option>
                                                <option value="RO" {{($getValues['quarter1'][0]->makaKalikasan_1) == "RO" ? 'selected' : ''}}>RO</option>
                                                <option value="NO" {{($getValues['quarter1'][0]->makaKalikasan_1) == "NO" ? 'selected' : ''}}>NO</option>
                                            @else
                                                <option value=""></option>
                                                <option value="AO">AO</option>
                                                <option value="SO">SO</option>
                                                <option value="RO">RO</option>
                                                <option value="NO">NO</option>
                                            @endif
                                        @endif
                                    </select>
                                </td>
                                <td>
                                    <select name="makaKalikasan_1" class="2">
                                        @if(count($getValues)==0)
                                            <option value=""></option>
                                            <option value="AO">AO</option>
                                            <option value="SO">SO</option>
                                            <option value="RO">RO</option>
                                            <option value="NO">NO</option>
                                        @elseif(count($getValues)>0)
                                            @if(isset($getValues['quarter2'][0]->makaKalikasan_1))
                                                <option value=" " {{($getValues['quarter2'][0]->makaKalikasan_1) == " " ? 'selected' : ''}}></option>
                                                <option value="AO" {{($getValues['quarter2'][0]->makaKalikasan_1) == "AO" ? 'selected' : ''}}>AO</option>
                                                <option value="SO" {{($getValues['quarter2'][0]->makaKalikasan_1) == "SO" ? 'selected' : ''}}>SO</option>
                                                <option value="RO" {{($getValues['quarter2'][0]->makaKalikasan_1) == "RO" ? 'selected' : ''}}>RO</option>
                                                <option value="NO" {{($getValues['quarter2'][0]->makaKalikasan_1) == "NO" ? 'selected' : ''}}>NO</option>
                                            @else
                                                <option value=""></option>
                                                <option value="AO">AO</option>
                                                <option value="SO">SO</option>
                                                <option value="RO">RO</option>
                                                <option value="NO">NO</option>
                                            @endif
                                        @endif
                                    </select>
                                </td>
                                <td>
                                    <select name="makaKalikasan_1" class="3">
                                        @if(count($getValues)==0)
                                            <option value=""></option>
                                            <option value="AO">AO</option>
                                            <option value="SO">SO</option>
                                            <option value="RO">RO</option>
                                            <option value="NO">NO</option>
                                        @elseif(count($getValues)>0)
                                            @if(isset($getValues['quarter3'][0]->makaKalikasan_1))
                                                <option value=" " {{($getValues['quarter3'][0]->makaKalikasan_1) == " " ? 'selected' : ''}}></option>
                                                <option value="AO" {{($getValues['quarter3'][0]->makaKalikasan_1) == "AO" ? 'selected' : ''}}>AO</option>
                                                <option value="SO" {{($getValues['quarter3'][0]->makaKalikasan_1) == "SO" ? 'selected' : ''}}>SO</option>
                                                <option value="RO" {{($getValues['quarter3'][0]->makaKalikasan_1) == "RO" ? 'selected' : ''}}>RO</option>
                                                <option value="NO" {{($getValues['quarter3'][0]->makaKalikasan_1) == "NO" ? 'selected' : ''}}>NO</option>
                                            @else
                                                <option value=""></option>
                                                <option value="AO">AO</option>
                                                <option value="SO">SO</option>
                                                <option value="RO">RO</option>
                                                <option value="NO">NO</option>
                                            @endif
                                        @endif
                                    </select>
                                </td>
                                <td>
                                    <select name="makaKalikasan_1" class="4">
                                        @if(count($getValues)==0)
                                            <option value=""></option>
                                            <option value="AO">AO</option>
                                            <option value="SO">SO</option>
                                            <option value="RO">RO</option>
                                            <option value="NO">NO</option>
                                        @elseif(count($getValues)>0)
                                            @if(isset($getValues['quarter4'][0]->makaKalikasan_1))
                                                <option value=" " {{($getValues['quarter4'][0]->makaKalikasan_1) == " " ? 'selected' : ''}}></option>
                                                <option value="AO" {{($getValues['quarter4'][0]->makaKalikasan_1) == "AO" ? 'selected' : ''}}>AO</option>
                                                <option value="SO" {{($getValues['quarter4'][0]->makaKalikasan_1) == "SO" ? 'selected' : ''}}>SO</option>
                                                <option value="RO" {{($getValues['quarter4'][0]->makaKalikasan_1) == "RO" ? 'selected' : ''}}>RO</option>
                                                <option value="NO" {{($getValues['quarter4'][0]->makaKalikasan_1) == "NO" ? 'selected' : ''}}>NO</option>
                                            @else
                                                <option value=""></option>
                                                <option value="AO">AO</option>
                                                <option value="SO">SO</option>
                                                <option value="RO">RO</option>
                                                <option value="NO">NO</option>
                                            @endif
                                        @endif
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Cares for the environment and utilizes resources wisely, judiciously and economically</td>
                                <td>
                                    <select name="makaKalikasan_2" class="1">
                                        @if(count($getValues)==0)
                                            <option value=""></option>
                                            <option value="AO">AO</option>
                                            <option value="SO">SO</option>
                                            <option value="RO">RO</option>
                                            <option value="NO">NO</option>
                                        @elseif(count($getValues)>0)
                                            @if(isset($getValues['quarter1'][0]->makaKalikasan_2))
                                                <option value=" " {{($getValues['quarter1'][0]->makaKalikasan_2) == " " ? 'selected' : ''}}></option>
                                                <option value="AO" {{($getValues['quarter1'][0]->makaKalikasan_2) == "AO" ? 'selected' : ''}}>AO</option>
                                                <option value="SO" {{($getValues['quarter1'][0]->makaKalikasan_2) == "SO" ? 'selected' : ''}}>SO</option>
                                                <option value="RO" {{($getValues['quarter1'][0]->makaKalikasan_2) == "RO" ? 'selected' : ''}}>RO</option>
                                                <option value="NO" {{($getValues['quarter1'][0]->makaKalikasan_2) == "NO" ? 'selected' : ''}}>NO</option>
                                            @else
                                                <option value=""></option>
                                                <option value="AO">AO</option>
                                                <option value="SO">SO</option>
                                                <option value="RO">RO</option>
                                                <option value="NO">NO</option>
                                            @endif
                                        @endif
                                    </select>
                                </td>
                                <td>
                                    <select name="makaKalikasan_2" class="2">
                                        @if(count($getValues)==0)
                                            <option value=""></option>
                                            <option value="AO">AO</option>
                                            <option value="SO">SO</option>
                                            <option value="RO">RO</option>
                                            <option value="NO">NO</option>
                                        @elseif(count($getValues)>0)
                                            @if(isset($getValues['quarter2'][0]->makaKalikasan_2))
                                                <option value=" " {{($getValues['quarter2'][0]->makaKalikasan_2) == " " ? 'selected' : ''}}></option>
                                                <option value="AO" {{($getValues['quarter2'][0]->makaKalikasan_2) == "AO" ? 'selected' : ''}}>AO</option>
                                                <option value="SO" {{($getValues['quarter2'][0]->makaKalikasan_2) == "SO" ? 'selected' : ''}}>SO</option>
                                                <option value="RO" {{($getValues['quarter2'][0]->makaKalikasan_2) == "RO" ? 'selected' : ''}}>RO</option>
                                                <option value="NO" {{($getValues['quarter2'][0]->makaKalikasan_2) == "NO" ? 'selected' : ''}}>NO</option>
                                            @else
                                                <option value=""></option>
                                                <option value="AO">AO</option>
                                                <option value="SO">SO</option>
                                                <option value="RO">RO</option>
                                                <option value="NO">NO</option>
                                            @endif
                                        @endif
                                    </select>
                                </td>
                                <td>
                                    <select name="makaKalikasan_2" class="3">
                                        @if(count($getValues)==0)
                                            <option value=""></option>
                                            <option value="AO">AO</option>
                                            <option value="SO">SO</option>
                                            <option value="RO">RO</option>
                                            <option value="NO">NO</option>
                                        @elseif(count($getValues)>0)
                                            @if(isset($getValues['quarter3'][0]->makaKalikasan_2))
                                                <option value=" " {{($getValues['quarter3'][0]->makaKalikasan_2) == " " ? 'selected' : ''}}></option>
                                                <option value="AO" {{($getValues['quarter3'][0]->makaKalikasan_2) == "AO" ? 'selected' : ''}}>AO</option>
                                                <option value="SO" {{($getValues['quarter3'][0]->makaKalikasan_2) == "SO" ? 'selected' : ''}}>SO</option>
                                                <option value="RO" {{($getValues['quarter3'][0]->makaKalikasan_2) == "RO" ? 'selected' : ''}}>RO</option>
                                                <option value="NO" {{($getValues['quarter3'][0]->makaKalikasan_2) == "NO" ? 'selected' : ''}}>NO</option>
                                            @else
                                                <option value=""></option>
                                                <option value="AO">AO</option>
                                                <option value="SO">SO</option>
                                                <option value="RO">RO</option>
                                                <option value="NO">NO</option>
                                            @endif
                                        @endif
                                    </select>
                                </td>
                                <td>
                                    <select name="makaKalikasan_2" class="4">
                                        @if(count($getValues)==0)
                                            <option value=""></option>
                                            <option value="AO">AO</option>
                                            <option value="SO">SO</option>
                                            <option value="RO">RO</option>
                                            <option value="NO">NO</option>
                                        @elseif(count($getValues)>0)
                                            @if(isset($getValues['quarter4'][0]->makaKalikasan_2))
                                                <option value=" " {{($getValues['quarter4'][0]->makaKalikasan_2) == " " ? 'selected' : ''}}></option>
                                                <option value="AO" {{($getValues['quarter4'][0]->makaKalikasan_2) == "AO" ? 'selected' : ''}}>AO</option>
                                                <option value="SO" {{($getValues['quarter4'][0]->makaKalikasan_2) == "SO" ? 'selected' : ''}}>SO</option>
                                                <option value="RO" {{($getValues['quarter4'][0]->makaKalikasan_2) == "RO" ? 'selected' : ''}}>RO</option>
                                                <option value="NO" {{($getValues['quarter4'][0]->makaKalikasan_2) == "NO" ? 'selected' : ''}}>NO</option>
                                            @else
                                                <option value=""></option>
                                                <option value="AO">AO</option>
                                                <option value="SO">SO</option>
                                                <option value="RO">RO</option>
                                                <option value="NO">NO</option>
                                            @endif
                                        @endif
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th rowspan="2">4. Makabansa</th>
                                <td>Demonstrates pride in being a Filipino, exercises the rights and responsibilities of a Filipino Citizen</td>
                                <td>
                                    <select name="makaBansa_1" class="1">
                                        @if(count($getValues)==0)
                                            <option value=""></option>
                                            <option value="AO">AO</option>
                                            <option value="SO">SO</option>
                                            <option value="RO">RO</option>
                                            <option value="NO">NO</option>
                                        @elseif(count($getValues)>0)
                                            @if(isset($getValues['quarter1'][0]->makaBansa_1))
                                                <option value=" " {{($getValues['quarter1'][0]->makaBansa_1) == " " ? 'selected' : ''}}></option>
                                                <option value="AO" {{($getValues['quarter1'][0]->makaBansa_1) == "AO" ? 'selected' : ''}}>AO</option>
                                                <option value="SO" {{($getValues['quarter1'][0]->makaBansa_1) == "SO" ? 'selected' : ''}}>SO</option>
                                                <option value="RO" {{($getValues['quarter1'][0]->makaBansa_1) == "RO" ? 'selected' : ''}}>RO</option>
                                                <option value="NO" {{($getValues['quarter1'][0]->makaBansa_1) == "NO" ? 'selected' : ''}}>NO</option>
                                            @else
                                                <option value=""></option>
                                                <option value="AO">AO</option>
                                                <option value="SO">SO</option>
                                                <option value="RO">RO</option>
                                                <option value="NO">NO</option>
                                            @endif
                                        @endif
                                    </select>
                                </td>
                                <td>
                                    <select name="makaBansa_1" class="2">
                                        @if(count($getValues)==0)
                                            <option value=""></option>
                                            <option value="AO">AO</option>
                                            <option value="SO">SO</option>
                                            <option value="RO">RO</option>
                                            <option value="NO">NO</option>
                                        @elseif(count($getValues)>0)
                                            @if(isset($getValues['quarter2'][0]->makaBansa_1))
                                                <option value=" " {{($getValues['quarter2'][0]->makaBansa_1) == " " ? 'selected' : ''}}></option>
                                                <option value="AO" {{($getValues['quarter2'][0]->makaBansa_1) == "AO" ? 'selected' : ''}}>AO</option>
                                                <option value="SO" {{($getValues['quarter2'][0]->makaBansa_1) == "SO" ? 'selected' : ''}}>SO</option>
                                                <option value="RO" {{($getValues['quarter2'][0]->makaBansa_1) == "RO" ? 'selected' : ''}}>RO</option>
                                                <option value="NO" {{($getValues['quarter2'][0]->makaBansa_1) == "NO" ? 'selected' : ''}}>NO</option>
                                            @else
                                                <option value=""></option>
                                                <option value="AO">AO</option>
                                                <option value="SO">SO</option>
                                                <option value="RO">RO</option>
                                                <option value="NO">NO</option>
                                            @endif
                                        @endif
                                    </select>
                                </td>
                                <td>
                                    <select name="makaBansa_1" class="3">
                                        @if(count($getValues)==0)
                                            <option value=""></option>
                                            <option value="AO">AO</option>
                                            <option value="SO">SO</option>
                                            <option value="RO">RO</option>
                                            <option value="NO">NO</option>
                                        @elseif(count($getValues)>0)
                                            @if(isset($getValues['quarter3'][0]->makaBansa_1))
                                                <option value=" " {{($getValues['quarter3'][0]->makaBansa_1) == " " ? 'selected' : ''}}></option>
                                                <option value="AO" {{($getValues['quarter3'][0]->makaBansa_1) == "AO" ? 'selected' : ''}}>AO</option>
                                                <option value="SO" {{($getValues['quarter3'][0]->makaBansa_1) == "SO" ? 'selected' : ''}}>SO</option>
                                                <option value="RO" {{($getValues['quarter3'][0]->makaBansa_1) == "RO" ? 'selected' : ''}}>RO</option>
                                                <option value="NO" {{($getValues['quarter3'][0]->makaBansa_1) == "NO" ? 'selected' : ''}}>NO</option>
                                            @else
                                                <option value=""></option>
                                                <option value="AO">AO</option>
                                                <option value="SO">SO</option>
                                                <option value="RO">RO</option>
                                                <option value="NO">NO</option>
                                            @endif
                                        @endif
                                    </select>
                                </td>
                                <td>
                                    <select name="makaBansa_1" class="4">
                                        @if(count($getValues)==0)
                                            <option value=""></option>
                                            <option value="AO">AO</option>
                                            <option value="SO">SO</option>
                                            <option value="RO">RO</option>
                                            <option value="NO">NO</option>
                                        @elseif(count($getValues)>0)
                                            @if(isset($getValues['quarter4'][0]->makaBansa_1))
                                                <option value=" " {{($getValues['quarter4'][0]->makaBansa_1) == " " ? 'selected' : ''}}></option>
                                                <option value="AO" {{($getValues['quarter4'][0]->makaBansa_1) == "AO" ? 'selected' : ''}}>AO</option>
                                                <option value="SO" {{($getValues['quarter4'][0]->makaBansa_1) == "SO" ? 'selected' : ''}}>SO</option>
                                                <option value="RO" {{($getValues['quarter4'][0]->makaBansa_1) == "RO" ? 'selected' : ''}}>RO</option>
                                                <option value="NO" {{($getValues['quarter4'][0]->makaBansa_1) == "NO" ? 'selected' : ''}}>NO</option>
                                            @else
                                                <option value=""></option>
                                                <option value="AO">AO</option>
                                                <option value="SO">SO</option>
                                                <option value="RO">RO</option>
                                                <option value="NO">NO</option>
                                            @endif
                                        @endif
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Demonstrates appropriate behavior in carying out activities in the school, community and country</td>
                                <td>
                                    <select name="makaBansa_2" class="1">
                                        @if(count($getValues)==0)
                                            <option value=""></option>
                                            <option value="AO">AO</option>
                                            <option value="SO">SO</option>
                                            <option value="RO">RO</option>
                                            <option value="NO">NO</option>
                                        @elseif(count($getValues)>0)
                                            @if(isset($getValues['quarter1'][0]->makaBansa_2))
                                                <option value=" " {{($getValues['quarter1'][0]->makaBansa_2) == " " ? 'selected' : ''}}></option>
                                                <option value="AO" {{($getValues['quarter1'][0]->makaBansa_2) == "AO" ? 'selected' : ''}}>AO</option>
                                                <option value="SO" {{($getValues['quarter1'][0]->makaBansa_2) == "SO" ? 'selected' : ''}}>SO</option>
                                                <option value="RO" {{($getValues['quarter1'][0]->makaBansa_2) == "RO" ? 'selected' : ''}}>RO</option>
                                                <option value="NO" {{($getValues['quarter1'][0]->makaBansa_2) == "NO" ? 'selected' : ''}}>NO</option>
                                            @else
                                                <option value=""></option>
                                                <option value="AO">AO</option>
                                                <option value="SO">SO</option>
                                                <option value="RO">RO</option>
                                                <option value="NO">NO</option>
                                            @endif
                                        @endif
                                    </select>
                                </td>
                                <td>
                                    <select name="makaBansa_2" class="2">
                                        @if(count($getValues)==0)
                                            <option value=""></option>
                                            <option value="AO">AO</option>
                                            <option value="SO">SO</option>
                                            <option value="RO">RO</option>
                                            <option value="NO">NO</option>
                                        @elseif(count($getValues)>0)
                                            @if(isset($getValues['quarter2'][0]->makaBansa_2))
                                                <option value=" " {{($getValues['quarter2'][0]->makaBansa_2) == " " ? 'selected' : ''}}></option>
                                                <option value="AO" {{($getValues['quarter2'][0]->makaBansa_2) == "AO" ? 'selected' : ''}}>AO</option>
                                                <option value="SO" {{($getValues['quarter2'][0]->makaBansa_2) == "SO" ? 'selected' : ''}}>SO</option>
                                                <option value="RO" {{($getValues['quarter2'][0]->makaBansa_2) == "RO" ? 'selected' : ''}}>RO</option>
                                                <option value="NO" {{($getValues['quarter2'][0]->makaBansa_2) == "NO" ? 'selected' : ''}}>NO</option>
                                            @else
                                                <option value=""></option>
                                                <option value="AO">AO</option>
                                                <option value="SO">SO</option>
                                                <option value="RO">RO</option>
                                                <option value="NO">NO</option>
                                            @endif
                                        @endif
                                    </select>
                                </td>
                                <td>
                                    <select name="makaBansa_2" class="3">
                                        @if(count($getValues)==0)
                                            <option value=""></option>
                                            <option value="AO">AO</option>
                                            <option value="SO">SO</option>
                                            <option value="RO">RO</option>
                                            <option value="NO">NO</option>
                                        @elseif(count($getValues)>0)
                                            @if(isset($getValues['quarter3'][0]->makaBansa_2))
                                                <option value=" " {{($getValues['quarter3'][0]->makaBansa_2) == " " ? 'selected' : ''}}></option>
                                                <option value="AO" {{($getValues['quarter3'][0]->makaBansa_2) == "AO" ? 'selected' : ''}}>AO</option>
                                                <option value="SO" {{($getValues['quarter3'][0]->makaBansa_2) == "SO" ? 'selected' : ''}}>SO</option>
                                                <option value="RO" {{($getValues['quarter3'][0]->makaBansa_2) == "RO" ? 'selected' : ''}}>RO</option>
                                                <option value="NO" {{($getValues['quarter3'][0]->makaBansa_2) == "NO" ? 'selected' : ''}}>NO</option>
                                            @else
                                                <option value=""></option>
                                                <option value="AO">AO</option>
                                                <option value="SO">SO</option>
                                                <option value="RO">RO</option>
                                                <option value="NO">NO</option>
                                            @endif
                                        @endif
                                    </select>
                                </td>
                                <td>
                                    <select name="makaBansa_2" class="4">
                                        @if(count($getValues)==0)
                                            <option value=""></option>
                                            <option value="AO">AO</option>
                                            <option value="SO">SO</option>
                                            <option value="RO">RO</option>
                                            <option value="NO">NO</option>
                                        @elseif(count($getValues)>0)
                                            @if(isset($getValues['quarter4'][0]->makaBansa_2))
                                                <option value="" {{($getValues['quarter4'][0]->makaBansa_2) == "" ? 'selected' : ''}}></option>
                                                <option value="AO" {{($getValues['quarter4'][0]->makaBansa_2) == "AO" ? 'selected' : ''}}>AO</option>
                                                <option value="SO" {{($getValues['quarter4'][0]->makaBansa_2) == "SO" ? 'selected' : ''}}>SO</option>
                                                <option value="RO" {{($getValues['quarter4'][0]->makaBansa_2) == "RO" ? 'selected' : ''}}>RO</option>
                                                <option value="NO" {{($getValues['quarter4'][0]->makaBansa_2) == "NO" ? 'selected' : ''}}>NO</option>
                                            @else
                                                <option value=""></option>
                                                <option value="AO">AO</option>
                                                <option value="SO">SO</option>
                                                <option value="RO">RO</option>
                                                <option value="NO">NO</option>
                                            @endif
                                        @endif
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6">
                                    <table id="studentInfo">
                                        <tr>
                                            <th>Marking</th>
                                            <th>Non-Numerical Rating</th>
                                        </tr>
                                        <tr>
                                            <td>AO</td>
                                            <td>Always Observed</td>
                                        </tr>
                                        <tr>
                                            <td>SO</td>
                                            <td>Sometimes Observed</td>
                                        </tr>
                                        <tr>
                                            <td>RO</td>
                                            <td>Rarely Observed</td>
                                        </tr>
                                        <tr>
                                            <td>NO</td>
                                            <td>Not Obseved</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>
    <script>
        var $ = jQuery;
        $('select').on('change', function(){
            var sectionid = $('#section_id').val();
            var field = $(this).attr('name');
            var quarter = $(this).attr('class');
            var behavior = $(this).val();
            var student_id = $('#student_id').val();
            $.ajax({
                url: '/observedValues/'+student_id,
                type:"GET",
                dataType:"json",
                data:{
                    sectionid: sectionid,
                    field: field,
                    quarter: quarter,
                    behavior: behavior
                },
                success:function(data) {
                    console.log('done!')
                },
            });
        });
    </script>
@endsection