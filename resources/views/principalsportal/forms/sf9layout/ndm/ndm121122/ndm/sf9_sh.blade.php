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
                      
                        <table class="table" >
                            <tr class="text-center">
                                <td colspan="2">Parent / Guardian Signature</td>
                            </tr>
                            <tr>
                                <td>1<sup>st</sup> Quarter</td>
                                <td>____________________________________________</td>
                            </tr>
                            <tr>
                                <td>2<sup>nd</sup> Quarter</td>
                                <td>____________________________________________</td>
                            </tr>
                            <tr>
                                <td>3<sup>rd</sup> Quarter</td>
                                <td>____________________________________________</td>
                            </tr>
                            <tr>
                                <td>4<sup>th</sup> Quarter</td>
                                <td>____________________________________________</td>
                            </tr>
                        </table> 
                    </td>
                    <td width="50%" >
                        <table class="table table-sm studentinfo"  width="100%">
                            <tr>
                                <td width="40%">DepEd Form 138-B/SF9</td>
                                <td width="60%" style="font-size:9px !important" class="text-right">Government Recognition (R-XII) No. CP 405628-028 s.2018</td>
                            </tr>
                        </table>
                        
                        <table class="table table-sm studentinfo"  width="100%">
                            <tr>
                                <td width="30%" rowspan="5" class="text-center align-middle p-0">
                                    <img src="{{base_path()}}/public/{{$schoolinfo[0]->picurl}}" alt="school" width="80px">
                                </td>
                                <td width="40%" class="text-center" style="padding:0 !imporant; margin:0 !important">
                                    Republic of the Philippines
                                </td>
                                <td width="30%" rowspan="5" class="text-center align-middle p-0">
                                      <img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="60px">
                                </td>
                            </tr>
                            <tr>
                                 <td class="text-center" style="padding:0 !imporant; margin:0 !important">Department of Education</td>
                            </tr>
                            <tr>
                                 <td class="text-center" style="padding:0 !imporant; margin:0 !important">Region XII</td>
                            </tr>
                            <tr>
                                 <td class="text-center" style="padding:0 !imporant; margin:0 !important">Cotabato Division</td>
                            </tr>
                             <tr>
                                 <td class="text-center"style="padding:0 !imporant; margin:0 !important">Central Mlang District</td>
                            </tr>
                        </table>
                        
                        
                        <table class="table table-sm mb-0 studentinfo"  width="100%">
                            <tr>
                                <td width="100%" class="text-center" style="font-size:20px !important; padding:-1px !important"><b>NOTRE DAME OF MLANG, INC.</b></td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0 studentinfo"  width="100%">
                            <tr>
                                <td width="100%" class="text-center">Garcia St., Mlang, Cotabato</td>
                            </tr>
                        </table>
                        <table class="table table-sm studentinfo "  width="100%">
                            <tr>
                                <td width="100%" class="text-center" style="font-size:13px !important;">PROGRESS REPORT CARD</td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0 studentinfo"  width="100%">
                            <tr>
                                <td width="5%">Name: </td>
                                <td width="25%" class="border-bottom text-center">{{$student->lastname}},</td>
                                <td width="25%" class="border-bottom text-center">{{$student->firstname}} </td>
                                <td width="25%" class="border-bottom text-center">{{$student->middlename}}</td>
                            </tr>
                            <tr>
                                <td width="5%"></td>
                                <td width="25%" class="text-center">( Surname ) </td>
                                <td width="25%" class="text-center">( First Name )</td>
                                <td width="25%" class="text-center">( Middle Name )</td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0 studentinfo"  width="100%">
                            <tr>
                                <td width="5%">Age: </td>
                                <td width="24%" class="text-center border-bottom">{{$student->age}}</td>
                                <td width="5%">Sex:</td>
                                <td width="24%" class="text-center border-bottom">{{$student->gender}}</td>
                                <td width="16%">School Year: </td>
                                <td width="26%" class="text-center border-bottom align-middle">{{$schoolyear->sydesc}}</td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0 studentinfo"  width="100%">
                            <tr>
                                <td width="5%">Age: </td>
                                <td width="18%" class="text-center border-bottom">{{$student->levelname}}</td>
                                <td width="5%">Sex:</td>
                                <td width="18%" class="text-center border-bottom">{{$student->sectionname}}</td>
                                <td width="16%">Track/Strand</td>
                                <td width="28%" class="text-center border-bottom align-middle">{{str_replace("TRACK","",strtoupper($strandInfo[0]->trackname))}}/{{strtoupper($strandInfo[0]->strandcode)}}</td>
                            </tr>
                        </table>
                         <table class="table table-sm mb-0 studentinfo"  width="100%">
                            <tr>
                                <td width="5%">LRN:</td>-->
                                <td width="95%" class="text-center border-bottom align-middle">{{$student->lrn}}</td>
                            </tr>
                        </table>
                        
                        <table class="table table-sm mb-0 studentinfo"  width="100%">
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
                                <td width="50%"></td>
                                <td width="50%" class="text-center border-bottom">{{$adviser}}</td>
                            </tr>
                              <tr>
                                <td width="50%"></td>
                                <td width="50%" class="text-center">Teacher</td>
                            </tr>
                        </table>
                        
                        <table class="table table-sm studentinfo mb-1"  width="100%">
                            <tr>
                                <td width="50%" class="text-center border-bottom">SR. ELVIE G. BORLADO, PM, PhD</td>
                                <td width="50%"></td>
                            </tr>
                            <tr>
                                <td width="50%" class="text-center">Director/Principal</td>
                                <td width="50%"></td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0 studentinfo"  width="100%">
                            <tr>
                                <td width="100%" class="text-center"><b>Certificate of Transfer</b></td>
                            </tr>
                        </table>
                        
                        <table class="table table-sm mb-0 studentinfo"  width="100%">
                            <tr>
                                <td width="30%">Admitted to Grade: </td>
                                <td width="20%" class="text-center border-bottom"></td>
                                <td width="5%">Section:</td>
                                <td width="45%" class="text-center border-bottom"></td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0 studentinfo"  width="100%">
                            <tr>
                                <td width="40%">Eligibility for admission to Grade</td>
                                <td width="60%" class="text-center border-bottom"></td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0 studentinfo"  width="100%">
                            <tr>
                                <td width="100%">Approved by: </td>
                            </tr>
                        </table>
                        <table class="table table-sm studentinfo mb-0"  width="100%">
                            <tr>
                                <td width="50%"></td>
                                <td width="50%" class="text-center border-bottom">&nbsp;</td>
                            </tr>
                              <tr>
                                <td width="50%"></td>
                                <td width="50%" class="text-center">Teacher</td>
                            </tr>
                        </table>
                      
                        <table class="table table-sm studentinfo  mb-1" style=:  width="100%">
                            <tr>
                                <td width="50%" class="text-center border-bottom">SR. ELVIE G. BORLADO, PM, PhD</td>
                                <td width="50%"></td>
                            </tr>
                            <tr>
                                <td width="50%" class="text-center">Director/Principal</td>
                                <td width="50%"></td>
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
                                <td width="30%" class="text-center border-bottom"></td>
                                <td widht="50%"></td>
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
                                <td width="50%"></td>
                                <td width="50%" class="text-center border-bottom">SR. ELVIE G. BORLADO, PM, PhD</td>
                            </tr>
                            <tr>
                                <td width="50%"></td>
                                <td width="50%" class="text-center">Director/Principal</td>
                            </tr>
                        </table>
                 
                 
                    </td>
                </tr>
        </table>
        <table class="table" width="100%">
                <tr>
                    <td width="50%" >
                            <table  class="table table-sm grades" width="100%">
                                <tr>
                                    <th class="text-center">REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</th>
                                </tr>
                            </table>    
                            @if($student->acadprogid != 5)
                                <table class="table table-sm table-bordered" width="100%">
                                    <tr>
                                        <td width="50%" rowspan="2"  class="align-middle text-center"><b>LEARNING AREAS</b></td>
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
                                    @foreach ($grades as $item)
                                        <tr>
                                            <td style="padding-left:{{$item->mapeh == 1 || $item->inTLE == 1 ? '2rem':'.25rem'}}" >{{$item->subjectcode!=null ? $item->subjectcode : null}}</td>
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
                                    <table class="table table-bordered grades_2" width="100%">
                                        <tr>
                                            <td colspan="5"  class="align-middle text-center"><b>{{$x == 1 ? '1ST SEMESTER' : '2ND SEMESTER'}}</b></td>
                                        </tr>
                                        <tr>
                                            <td width="60%" rowspan="2"  class="align-middle text-center"><b>SUBJECTS</b></td>
                                            <td width="20%" colspan="2"  class="text-center align-middle" ><b>PERIODIC RATINGS</b></td>
                                            <td width="10%" rowspan="2"  class="text-center align-middle p-0" ><b>FINAL RATING</b></td>
                                            <td width="10%" rowspan="2"  class="text-center align-middle p-0"><b>ACTION TAKEN</b></td>
                                        </tr>
                                        <tr>
                                            @if($x == 1)
                                                <td class="text-center align-middle"><b>1</b></td>
                                                <td class="text-center align-middle"><b>2</b></td>
                                            @elseif($x == 2)
                                                <td class="text-center align-middle"><b>3</b></td>
                                                <td class="text-center align-middle"><b>4</b></td>
                                            @endif
                                        </tr>
                                        <tr><td colspan="5" style="background-color:#00b0f0; color:black; border:solid 1px black">CORE SUBJECTS</td></tr>
                                        @foreach (collect($grades)->where('type',1)->where('semid',$x) as $item)
                                            <tr>
                                                <td>{{$item->subjectcode!=null ? $item->subjectcode : null}}</td>
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
                                        <tr><td colspan="5" style="background-color:#00b0f0; color:black; border:solid 1px black">APPLIED SUBJECTS</td></tr>
                                        @foreach (collect($grades)->where('type',3)->where('semid',$x) as $item)
                                            <tr>
                                                <td>{{$item->subjectcode!=null ? $item->subjectcode : null}}</td>
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
                                          <tr><td colspan="5" style="background-color:#00b0f0; color:black; border:solid 1px black">SPECIALIZED SUBJECTS</td></tr>
                                        @foreach (collect($grades)->where('type',2)->where('semid',$x) as $item)
                                            <tr>
                                                <td>{{$item->subjectcode!=null ? $item->subjectcode : null}}</td>
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
                                            <td><b>GENERAL AVERAGE</b></td>
                                            @if($x == 1)
                                                <td class="text-center align-middle">{{isset($genave->quarter1) ? $genave->quarter1 != null ? $genave->quarter1:'' : ''}}</td>
                                                <td class="text-center align-middle">{{isset($genave->quarter2) ? $genave->quarter2 != null ? $genave->quarter2:'' : ''}}</td>
                                            @elseif($x == 2)
                                                <td class="text-center align-middle">{{isset($genave->quarter3) ? $genave->quarter3 != null ? $genave->quarter3:'' : ''}}</td>
                                                <td class="text-center align-middle">{{isset($genave->quarter4) ? $genave->quarter4 != null ? $genave->quarter4:'' : ''}}</td>
                                            @endif
                                            <td class="text-center align-middle  p-0">{{ isset($genave->finalrating) ? $genave->finalrating != null ? $genave->finalrating:'' :''}}</td>
                                            <td class="text-center align-middle  p-0">{{ isset($genave->actiontaken) ? $genave->actiontaken != null ? $genave->actiontaken:'' :''}}</td>
                                        </tr>
                                    </table>
                                @endfor
                            @endif
                           
                        </td>
                    <td width="50%">
                        <table class="table table-sm grades" width="100%">
                            <tr>
                                <td width="10%"></td>
                                <td width="30%"><b>DESCRIPTORS</b></td>
                                <td width="30%" class=" text-center"><b>GRADING SCALE</b></td>
                                <td width="30%" class=" text-center"><b>REMARKS</b></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Outstanding</td>
                                <td class=" text-center">90-100</td>
                                <td class=" text-center">Passed</td>
                            </tr>
                            <tr>
                                <td></td>   
                                <td>Very Satisfactory</td>
                                <td class=" text-center">85-89</td>
                                <td class=" text-center">Passed</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Satisfactory</td>
                                <td class=" text-center">80-84</td>
                                <td class=" text-center">Passed</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Fairly Satisfactory</td>
                                <td class=" text-center">75-79</td>
                                <td class=" text-center">Passed</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Did Not Meet Expectations</td>
                                <td class=" text-center">Below 75</td>
                                <td class=" text-center">Failed</td>
                            </tr>
                        </table>
                        <table class="table table-sm" width="100%">
                            <tr>
                                <td><center><b>LEARNING MODALITY</b></center></td>
                            </tr>
                        </table >
                       
                        <table class="table table-bordered" width="100%">
                            <tr>
                                <td rowspan="2">Learning Modality</td>
                                <td class="text-center">Q1</td>
                                <td class="text-center">Q2</td>
                                <td class="text-center">Q3</td>
                                <td class="text-center">Q4</td>
                            </tr>
                            <tr>
                                <td class="text-center">BL</td>
                                <td class="text-center">BL</td>
                                <td class="text-center">BL</td>
                                <td class="text-center">BL</td>
                            </tr>
                        </table >
                        <table class="table table-sm grades" width="100%">
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
                            
                        </table>
                        
                        <table class="table table-sm mb-0" width="100%">
                            <tr>
                                <td><center><b>REPORT ON ATTENDANCE</b></center></td>
                            </tr>
                        </table >
                        <table class="table table-bordered table-sm" width="100%">
                            @php
                                 $width = count($attendance_setup) != 0? 75 / count($attendance_setup) : 0;
                            @endphp
                            <tr>
                                <th width="15%"></th>
                                @foreach ($attendance_setup as $item)
                                    <th class="text-center align-middle"  width="{{$width}}%">{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</th>
                                @endforeach
                                <th class="text-center text-center"  width="10%">Total</th>
                            </tr>
                            <tr>
                                <td>No. of School Days</td>
                                @foreach ($attendance_setup as $item)
                                    <td class="text-center align-middle">{{$item->days}}</td>
                                @endforeach
                                <th class="text-center text-center align-middle">{{collect($attendance_setup)->sum('days')}}</td>
                            </tr>
                            <tr >
                                <td>No. of Days Present</td>
                                @foreach ($attendance_setup as $item)
                                    <td class="text-center align-middle">{{$item->present}}</td>
                                @endforeach
                                <th class="text-center text-center align-middle">{{collect($attendance_setup)->sum('present')}}</th>
                            </tr>
                            <tr>
                                <td>No. of School Day Absent</td>
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