<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$student->student}}</title>
    <style>

        .table {
            width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
            font-size:11px ;
        }

        table {
            border-collapse: collapse;
        }
        
        .table thead th {
            vertical-align: bottom;
        }
        
        .table td, .table th {
            padding: .75rem;
            vertical-align: top;
        }
        .table td, .table th {
            padding: .75rem;
            vertical-align: top;
        }
        
        .table-bordered {
            border: 1px solid #00000;
        }

        .table-bordered td, .table-bordered th {
            border: 1px solid #00000;
        }

        .table-sm td, .table-sm th {
            padding: .3rem;
        }

        .text-center{
            text-align: center !important;
        }
        
        .text-right{
            text-align: right !important;
        }
        
        .text-left{
            text-align: left !important;
        }
        
        .p-0{
            padding: 0 !important;
        }
       


        .mb-0{
            margin-bottom: 0;
        }

        .border-bottom{
            border-bottom:1px solid black;
        }

        .mb-1, .my-1 {
            margin-bottom: .25rem!important;
        }

        body{
            font-family: Calibri, sans-serif;
        }
        
        .align-middle{
            vertical-align: middle !important;    
        }

         
        .grades td{
            padding-top: .1rem;
            padding-bottom: .1rem;
            font-size: 9px !important;
        }
        
        .grades_2 td{
            padding-top: .1rem;
            padding-bottom: .1rem;
            font-size: 10px !important;
        }

        .studentinfo td{
            padding-top: .1rem;
            padding-bottom: .1rem;
          
        }

        @page {  
            margin:0;
            
        }
        body { 
            margin:0;
            
        }
        
        @page { size: 11in 8.5in; margin: .25in;  }
        
    </style>
</head>
<body>
        <table class="table" width="100%">
                <tr>
                    <td width="50%">
                        <table  class="table table-sm mb-0" width="100%" style="margin-top: 60px;">
                            <tr>
                                <td class="p-0" style="font-size: 15px!important; padding-left: 60px!important;"><b>PARENT / GUARDIAN'S SIGNATURE</b></td>
                            </tr>
                        </table>   
                        <table width="100%" class="table table-sm" style="table-layout: fixed; margin-top: 35px;">
                            <tr>
                                <td width="25%" class="text-left p-0">1st QUARTER</td>
                                <td width="65%" class="p-0" style="border-bottom: 1.5px solid #000;"></td>
                                <td width="10%" class="p-0"></td>
                            </tr>
                        </table>
                        <table width="100%" class="table table-sm" style="table-layout: fixed; margin-top: 25px;">
                            <tr>
                                <td width="25%" class="text-left p-0">2nd QUARTER</td>
                                <td width="65%" class="p-0" style="border-bottom: 1.5px solid #000;"></td>
                                <td width="10%" class="p-0"></td>
                            </tr>
                        </table>
                        <table width="100%" class="table table-sm" style="table-layout: fixed; margin-top: 25px;">
                            <tr>
                                <td width="25%" class="text-left p-0">3rd QUARTER</td>
                                <td width="65%" class="p-0" style="border-bottom: 1.5px solid #000;"></td>
                                <td width="10%" class="p-0"></td>
                            </tr>
                        </table>
                        <table width="100%" class="table table-sm" style="table-layout: fixed; margin-top: 25px;">
                            <tr>
                                <td width="25%" class="text-left p-0">4th QUARTER</td>
                                <td width="65%" class="p-0" style="border-bottom: 1.5px solid #000;"></td>
                                <td width="10%" class="p-0"></td>
                            </tr>
                        </table>
                    </td>
                    <td width="50%" >
                        <table class="table table-sm studentinfo mb-0"  width="100%">
                            <tr>
                                <td width="60%" style="font-size:10px !important" class="text-left"><b>Provisional Permit to Operate No. SHS-R12-056 s.2016</b></td>
                                <td width="40%" class="text-right" style="font-size:10px !important"><b>DepEd FORM 138 / SF9</b></td>
                            </tr>
                        </table>
                        <table class="table table-sm studentinfo mb-0"  width="100%" style="margin-top: 5px;">
                            <tr>
                                <td width="30%" class="align-middle text-left p-0">
                                    <img src="{{base_path()}}/public/{{$schoolinfo[0]->picurl}}" alt="school" width="80px">
                                </td>
                                <td width="40%" class="align-middle text-center p-0">
                                    <div>Republic of the Philippines</div>
                                    <div>DEPARTMENT OF EDUCATION</div>
                                    <div>Region XII</div>
                                    <div>Cotabato Division</div>
                                </td>
                                <td width="30%" class="align-middle text-center p-0">
                                    <img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="60px">
                                </td>
                            </tr>
                        </table>
                        <table class="table table-sm studentinfo"  width="100%">
                            <tr>
                                <td width="100%" class="text-center p-0" style="font-size:22px !important; padding:-1px !important"><b>NOTRE DAME OF MLANG, INC.</b></td>
                            </tr>
                            <tr>
                                <td width="100%" class="text-center p-0" style="font-size:11px !important;">Garcia St., Poblacion B, M'lang, Cotabato</td>
                            </tr>
                        </table>
                        
                        <table class="table table-sm mb-0 studentinfo"  width="100%"  style="margin-top: 30px!important;">
                            <tr>
                                <td width="5%"><b>Name: </b></td>
                                <td width="95%" class="text-left"><u>{{$student->firstname.' '.$student->middlename[0].'. '.$student->lastname}}</u></td>
                                
                            </tr>
                        </table>
                        <table class="table table-sm mb-0 studentinfo"  width="100%">
                            <tr>
                                
                                <td width="5%"><b>Sex:</b></td>
                                <td width="24%" class="text-left"><u>{{$student->gender}}</u></td>
                                <td width="17%"><b>School Year: </b></td>
                                <td width="17%" class="text-left align-middle"><u>{{$schoolyear->sydesc}}</u></td>
                                <td width="5%"><b>Grade: </b></td>
                                <td width="32%" class="text-left"><u>{{str_replace('GRADE', '', $student->levelname)}}</u></td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0 studentinfo"  width="100%">
                            <tr>
                                <td width="5%"><b>Section: </b></td>
                                <td width="18%" class="text-left"><u>{{$student->sectionname}}</u></td>
                                <td width="16%"><b>Track/Strand</b></td>
                                <td width="51%" class="text-left align-middle"><u></u></td>
                            </tr>
                        </table>
                         <table class="table table-sm mb-0 studentinfo"  width="100%">
                            <tr>
                                <td width="5%"><b>LRN:</b></td>
                                <td width="95%" class="text-left align-middle"><u>{{$student->lrn}}</u></td>
                            </tr>
                        </table>
                        
                        <table class="table table-sm mb-0 studentinfo"  width="100%" style="margin-top: 5px;">
                            <tr>
                                <td width="100%">Dear Parents,</td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0 studentinfo"  width="100%">
                            <tr>
                                <td width="100%"> 
                                    <p style="font-size:11px!important; text-indent: 50px;  margin-bottom: 0 !important;margin-top: 0 !important;padding-top: 0 !important" >This report card shows the ability and progress your child in the different learning areas including its core values.</p>
                                </td>
                            </tr>
                        </table>
                         <table class="table table-sm studentinfo"  width="100%">
                            <tr>
                                <td width="100%"> 
                                    <p style="font-size:11px!important; text-indent: 50px; margin-bottom: 0 !important;margin-top: 0 !important;padding-top: 0 !important" class="mb-0">Should you desire to inquire more about your childs performance, you may contact the undersigned below.</p>
                        </td>
                            </tr>
                        </table>
                       
                        <table class="table table-sm studentinfo mb-0"  width="100%">
                            <tr>
                                <td width="55%"></td>
                                <td width="45%" class="text-center border-bottom">{{$adviser}}</td>
                            </tr>
                              <tr>
                                <td width="55%"></td>
                                <td width="45%" class="text-center">Adviser</td>
                            </tr>
                        </table>
                        
                        <table class="table table-sm studentinfo mb-1"  width="100%">
                            <tr>
                                <td width="45%" class="text-center border-bottom">SR. ELVIE G. BORLADO, PM, PhD.</td>
                                <td width="55%"></td>
                            </tr>
                            <tr>
                                <td width="45%" class="text-center">Director</td>
                                <td width="55%"></td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0 studentinfo"  width="100%">
                            <tr>
                                <td width="100%" class="text-center" style="border-bottom: 1px dotted #000;"></td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0 studentinfo"  width="100%" style="margin-top: 5px;">
                            <tr>
                                <td width="100%" class="text-center"><b>Certificate of Transfer</b></td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0 studentinfo"  width="100%" style="margin-top: 10px;">
                            <tr>
                                <td width="22%">Admitted to Grade: </td>
                                <td width="15%" class="text-center border-bottom"></td>
                                <td width="10%"></td>
                                <td width="8%" class="text-left">Section:</td>
                                <td width="45%" class="text-center border-bottom"></td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0 studentinfo"  width="100%">
                            <tr>
                                <td width="36%">Eligibility for admission to Grade</td>
                                <td width="64%" class="text-center border-bottom"></td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0 studentinfo"  width="100%" style="margin-top: 10px;">
                            <tr>
                                <td width="100%">Approved by: </td>
                            </tr>
                        </table>
                        <table class="table table-sm studentinfo mb-0"  width="100%">
                            <tr>
                                <td width="55%"></td>
                                <td width="45%" class="text-center border-bottom">&nbsp;</td>
                            </tr>
                              <tr>
                                <td width="55%"></td>
                                <td width="45%" class="text-center">Adviser</td>
                            </tr>
                        </table>
                      
                        <table class="table table-sm studentinfo  mb-1" style=:  width="100%">
                            <tr>
                                <td width="45%" class="text-center border-bottom">SR. ELVIE G. BORLADO, PM, PhD.</td>
                                <td width="55%"></td>
                            </tr>
                            <tr>
                                <td width="45%" class="text-center">Director</td>
                                <td width="55%"></td>
                            </tr>
                        </table>
                        
                        <table class="table table-sm studentinfo mb-0"  width="100%">
                            <tr>
                                <td width="100%" class="text-center"><b>Cancellation of Eligibility to Transfer</b></td>
                            </tr>
                        </table>
                        
                        <table class="table table-sm mb-0 studentinfo"  width="100%">
                            <tr>
                                <td width="20%">Admitted in: </td>
                                <td width="35%" class="text-center border-bottom"></td>
                                <td widht="45%"></td>
                            </tr>
                            <tr>
                                <td>Date: </td>
                                <td class="text-center border-bottom"></td>
                                <td></td>
                            </tr>
                        </table>
                        
                        <br>
                        <table class="table table-sm studentinfo b-0"  width="100%">
                            <tr> 
                                <td width="55%"></td>
                                <td width="45%" class="text-center border-bottom">SR. ELVIE G. BORLADO, PM, PhD.</td>
                            </tr>
                            <tr>
                                <td width="55%"></td>
                                <td width="45%" class="text-center">Director</td>
                            </tr>
                        </table>
                    </td>
                </tr>
        </table>
        <table class="table" width="100%">
                <tr>
                    <td width="50%" >
                            <table  class="table table-sm mb-0" width="100%">
                                <tr>
                                    <td class="p-0" style="font-size: 15px;"><center><b>REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</b></center></td>
                                </tr>
                            </table>    
                            @if($student->acadprogid != 5)
                                <table class="table table-sm table-bordered" width="100%">
                                    <tr>
                                        <td width="48%" rowspan="2"  class="align-middle text-center"><b>LEARNING AREAS</b></td>
                                        <td width="30%" colspan="4"  class="text-center align-middle"><b>QUARTER</b></td>
                                        <td width="10%" rowspan="2"  class="text-center align-middle"><b>FINAL GRADE</b></td>
                                        <td width="10%" rowspan="2"  class="text-center align-middle"><b>ACTION TAKEN</b></span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center align-middle"><b>1</b></td>
                                        <td class="text-center align-middle"><b>2</b></td>
                                        <td class="text-center align-middle"><b>3</b></td>
                                        <td class="text-center align-middle"><b>4</b></td>
                                    </tr>
                                    @foreach ($studgrades as $item)
                                        <tr>
                                            <td style="padding-left:{{$item->mapeh == 1 || $item->inTLE == 1 ? '2rem':'.25rem'}}" ></td>
                                            <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                            <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                            <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                            <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                            <td class="text-center align-middle">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                                            <td class="text-center align-middle">{{$item->actiontaken != null ? $item->actiontaken:''}}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        @php
                                            $genave = collect($finalgrade)->where('semid',1)->first();
                                        @endphp
                                        <td colspan="5"><b>GENERAL AVERAGE</b></td>
                                        <td class="text-center align-middle">{{$genave->finalrating != null ? $genave->finalrating:''}}</td>
                                        <td class="text-center align-middle">{{$genave->actiontaken != null ? $genave->actiontaken:''}}</td>
                                    </tr>
                                </table>
                            @else
                                @for ($x=1; $x <= 2; $x++)
                                    <table class="table mb-0" width="100%" style="table-layout: fixed;">
                                        <tr>
                                            <td class="align-middle text-left p-0"><b>{{$x == 1 ? 'FIRST SEMESTER' : 'SECOND SEMESTER'}}</b></td>
                                        </tr>
                                    </table>
                                    <table class="table table-bordered grades_2 mb-0" width="100%" style="table-layout: fixed;">
                                        <tr style="background-color:#00b0f0;">
                                            <td width="56%" class="align-middle text-center p-0"><b>SUBJECTS</b></td>
                                            <td width="17%" colspan="2"  class="text-center align-middle" ><b>Quarter</b></td>
                                            <td width="13%" rowspan="2"  class="text-center align-middle p-0" ><b>Semester <br> Final Grade</b></td>
                                            <td width="14%" rowspan="2"  class="text-center align-middle p-0"><b>Remarks</b></td>
                                        </tr>
                                        <tr style="background-color:#00b0f0;">
                                            <td></td>
                                            @if($x == 1)
                                                <td class="text-center align-middle"><b>1st</b></td>
                                                <td class="text-center align-middle"><b>2nd</b></td>
                                            @elseif($x == 2)
                                                <td class="text-center align-middle"><b>3rd</b></td>
                                                <td class="text-center align-middle"><b>4th</b></td>
                                            @endif
                                        </tr>
                                        <tr><td colspan="5" style="background-color:#00b0f0; color:black; border:solid 1px black"><b>CORE Subjects</b></td></tr>
                                        @foreach (collect($studgrades)->where('type',1)->where('semid',$x) as $item)
                                            <tr>
                                                <td>{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                                @if($x == 1)
                                                    <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                                    <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                                @elseif($x == 2)
                                                    <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                                    <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                                @endif
                                                <td class="text-center align-middle p-0">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                                                <td class="text-center align-middle p-0">{{$item->actiontaken != null ? $item->actiontaken:''}}</td>
                                            </tr>
                                        @endforeach
                                        <tr><td colspan="5" style="background-color:#00b0f0; color:black; border:solid 1px black"><b>APPLIED Subjects</b></td></tr>
                                        @foreach (collect($studgrades)->where('type',3)->where('semid',$x) as $item)
                                            <tr>
                                                <td>{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                                @if($x == 1)
                                                    <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                                    <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                                @elseif($x == 2)
                                                    <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                                    <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                                @endif
                                                <td class="text-center align-middle  p-0">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                                                <td class="text-center align-middle  p-0">{{$item->actiontaken != null ? $item->actiontaken:''}}</td>
                                            </tr>
                                        @endforeach
                                          <tr><td colspan="5" style="background-color:#00b0f0; color:black; border:solid 1px black"><b>SPECIALIZED Subjects</b></td></tr>
                                        @foreach (collect($studgrades)->where('type',2)->where('semid',$x) as $item)
                                            <tr>
                                                <td>{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                                @if($x == 1)
                                                    <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                                    <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                                @elseif($x == 2)
                                                    <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                                    <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                                @endif
                                                <td class="text-center align-middle  p-0">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                                                <td class="text-center align-middle  p-0">{{$item->actiontaken != null ? $item->actiontaken:''}}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            @php
                                                $genave = collect($finalgrade)->where('semid',$x)->first();
                                            @endphp
                                            <td colspan="3" class="text-center" style="font-size: 13px!important;">General Average for the Semester</td>
                                            {{-- @if($x == 1)
                                                <td class="text-center align-middle">{{isset($genave->quarter1) ? $genave->quarter1 != null ? $genave->quarter1:'' : ''}}</td>
                                                <td class="text-center align-middle">{{isset($genave->quarter2) ? $genave->quarter2 != null ? $genave->quarter2:'' : ''}}</td>
                                            @elseif($x == 2)
                                                <td class="text-center align-middle">{{isset($genave->quarter3) ? $genave->quarter3 != null ? $genave->quarter3:'' : ''}}</td>
                                                <td class="text-center align-middle">{{isset($genave->quarter4) ? $genave->quarter4 != null ? $genave->quarter4:'' : ''}}</td>
                                            @endif --}}
                                            <td class="text-center align-middle  p-0">{{ isset($genave->finalrating) ? $genave->finalrating != null ? $genave->finalrating:'' :''}}</td>
                                            <td class="text-center align-middle  p-0">{{ isset($genave->actiontaken) ? $genave->actiontaken != null ? $genave->actiontaken:'' :''}}</td>
                                        </tr>
                                    </table>
                                @endfor
                            @endif
                           
                        </td>
                    <td width="50%">
                        <table class="table table-bordered table-sm" width="100%" style="font-size: 11px; margin-top: 33px;">
                            <tr>
                                <td colspan="3"><b>Learner Progress and Achievement:</b></td>
                            </tr>
                            <tr>
                                <td width="40%" class="p-0 align-middle" style="height: 20px;"><b>&nbsp;Descriptors</b></td>
                                <td width="30%" class="p-0 text-center align-middle"><b>Grading Scale</b></td>
                                <td width="30%" class="p-0 text-center align-middle"><b>Remarks</b></td>
                            </tr>
                            <tr>
                                <td class="p-0">&nbsp;Outstanding</td>
                                <td class="p-0 text-center">90-100</td>
                                <td class="p-0 text-center">Passed</td>
                            </tr>
                            <tr>
                                <td class="p-0">&nbsp;Very Satisfactory</td>
                                <td class="p-0 text-center">85-89</td>
                                <td class="p-0 text-center">Passed</td>
                            </tr>
                            <tr>
                                <td class="p-0">&nbsp;Satisfactory</td>
                                <td class="p-0 text-center">80-84</td>
                                <td class="p-0 text-center">Passed</td>
                            </tr>
                            <tr>
                                <td class="p-0">&nbsp;Fairly Satisfactory</td>
                                <td class="p-0 text-center">75-79</td>
                                <td class="p-0 text-center">Passed</td>
                            </tr>
                            <tr>
                                <td class="p-0">&nbsp;Did Not Meet Expectations</td>
                                <td class="p-0 text-center">Below 75</td>
                                <td class="p-0 text-center">Failed</td>
                            </tr>
                        </table>
                        {{-- <table class="table table-sm" width="100%">
                            <tr>
                                <td><center><b>LEARNING MODALITY</b></center></td>
                            </tr>
                        </table >
                        --}}
                        <table class="table table-bordered" width="100%" style="margin-right: 60px; margin-top: 50px;">
                            <tr>
                                <td width="27%" rowspan="2" class="text-center align-middle p-0" style="height: 40px;"><b>Learning Modality</b></td>
                                <td width="17%" class="text-center align-middle p-0"><b>Q1</b></td>
                                <td width="17%" class="text-center align-middle p-0"><b>Q2</b></td>
                                <td width="17%" class="text-center align-middle p-0"><b>Q3</b></td>
                                <td width="17%" class="text-center align-middle p-0"><b>Q4</b></td>
                            </tr>
                            <tr>
                                <td class="text-center">&nbsp;</td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                            </tr>
                        </table >
                        {{-- <table class="table table-sm grades" width="100%">
                            <tr>
                                <td width="20%"></td>
                                <td width="30%"><b>Descriptions</b></td>
                                <td width="30%" class=" text-center"></td>
                                <td width="20%"></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Modular Distance Learning</td>
                                <td class=" text-center">MDL</td>
                                <td class=" text-center"></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Online Learning</td>
                                <td class=" text-center">OL</td>
                                <td class=" text-center"></td>
                            </tr>
                            <tr>
                                <td></td>   
                                <td>Blended Learning</td>
                                <td class=" text-center">BL</td>
                                <td class=" text-center"></td>
                            </tr>
                            
                        </table> --}}
                        
                        <table class="table table-sm mb-0" width="100%" style="margin-top: 50px;">
                            <tr>
                                <td class="p-0" style="font-size: 15px;"><center><b>REPORT ON ATTENDANCE</b></center></td>
                            </tr>
                        </table >
                        <table class="table table-bordered table-sm" width="100%" style="margin-top: 5px;">
                            @php
                                 $width = count($attendance_setup) != 0? 80 / count($attendance_setup) : 0;
                            @endphp
                            <tr>
                                <th width="10%"></th>
                                @foreach ($attendance_setup as $item)
                                    <th class="text-center align-middle"  width="{{$width}}%">{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</th>
                                @endforeach
                                <th class="text-center text-center"  width="10%">TOTAL</th>
                            </tr>
                            <tr>
                                <td class="text-center"><b>No. of School Days</b></td>
                                @foreach ($attendance_setup as $item)
                                    <td class="text-center align-middle">{{$item->days}}</td>
                                @endforeach
                                <th class="text-center text-center align-middle">{{collect($attendance_setup)->sum('days')}}</td>
                            </tr>
                            <tr >
                                <td class="text-center"><b>No. of Days Present</b></td>
                                @foreach ($attendance_setup as $item)
                                    <td class="text-center align-middle">{{$item->present}}</td>
                                @endforeach
                                <th class="text-center text-center align-middle">{{collect($attendance_setup)->sum('present')}}</th>
                            </tr>
                            <tr>
                                <td class="text-center"><b>No. of School Day Absent</b></td>
                                @foreach ($attendance_setup as $item)
                                    <td class="text-center align-middle">{{$item->absent}}</td>
                                @endforeach
                                <th class="text-center align-middle" >{{ collect($attendance_setup)->sum('absent')}}</td>
                            </tr>
                            <tr>
                                <td class="text-center"><b>No. of Times Tardy</b></td>
                                @foreach ($attendance_setup as $item)
                                    <td class="text-center align-middle">{{$item->absent}}</td>
                                @endforeach
                                <th class="text-center align-middle" >{{ collect($attendance_setup)->sum('absent')}}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
        </table>

    
</div>

</body>
</html>