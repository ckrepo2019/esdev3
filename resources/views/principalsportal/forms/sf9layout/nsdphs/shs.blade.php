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
        
        @page { size: 11in 8.5in; margin: 20px 5px 0px 5px;  }
        
    </style>
</head>
<body>
        <table class="table" width="100%">
                <tr>
                    <td width="50%" style="padding-right: 50px;">
                        <table class="table table-sm studentinfo mb-0"  width="100%">
                            <tr>
                                <td width="100%" class="text-left" style="font-size:13px !important">Appendix D</td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0" width="100%" style="">
                            <tr>
                                <td class="p-0" style="font-size: 17px; font-family:'Times New Roman', Times, serif !important;"><center><b>REPORT ON ATTENDANCE</b></center></td>
                            </tr>
                        </table >
                        <table class="table table-bordered table-sm" width="100%" style="table-layout: fixed; margin-top: 5px;" >
                            @php
                                 $width = count($attendance_setup) != 0? 73 / count($attendance_setup) : 0;
                            @endphp
                            <tr>
                                <th width="17%" class="align-middle text-center" style="font-size: 14px; font-weight: 400!important; height: 20px;">Month</th>
                                @foreach ($attendance_setup as $item)
                                    <th class="text-center align-middle"  width="{{$width}}%">{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</th>
                                @endforeach
                                <th class="align-middle text-center text-center"  width="10%">Total</th>
                            </tr>
                            <tr>
                                <td class="text-center" style="font-size: 10px;"><b>No. of School Days</b></td>
                                @foreach ($attendance_setup as $item)
                                    <td class="text-center align-middle">{{$item->days}}</td>
                                @endforeach
                                <th class="text-center text-center align-middle">{{collect($attendance_setup)->sum('days')}}</td>
                            </tr>
                            <tr >
                                <td class="text-center"><b>No. of days Present</b></td>
                                @foreach ($attendance_setup as $item)
                                    <td class="text-center align-middle">{{$item->present}}</td>
                                @endforeach
                                <th class="text-center text-center align-middle">{{collect($attendance_setup)->sum('present')}}</th>
                            </tr>
                            <tr>
                                <td class="text-center"><b>No. of days Absent</b></td>
                                @foreach ($attendance_setup as $item)
                                    <td class="text-center align-middle">{{$item->absent}}</td>
                                @endforeach
                                <th class="text-center align-middle" >{{ collect($attendance_setup)->sum('absent')}}</td>
                            </tr>
                        </table>
                        <table  class="table table-sm mb-0" width="100%" style="margin-top: 60px;">
                            <tr>
                                <td class="p-0" style="font-size: 15px!important;"><b>PARENT / GUARDIAN'S SIGNATURE</b></td>
                            </tr>
                        </table>   
                        <table width="100%" class="table table-sm" style="table-layout: fixed; margin-top: 20px;">
                            <tr>
                                <td width="25%" class="text-left p-0"><b>1ST QUARTER</b></td>
                                <td width="75%" class="p-0" style="border-bottom: 1.5px solid #000;"></td>
                            </tr>
                        </table>
                        <table width="100%" class="table table-sm" style="table-layout: fixed; margin-top: 25px;">
                            <tr>
                                <td width="25%" class="text-left p-0"><b>2ND QUARTER</b></td>
                                <td width="75%" class="p-0" style="border-bottom: 1.5px solid #000;"></td>
                            </tr>
                        </table>
                        <table width="100%" class="table table-sm" style="table-layout: fixed; margin-top: 25px;">
                            <tr>
                                <td width="25%" class="text-left p-0"><b>3RD QUARTER</b></td>
                                <td width="75%" class="p-0" style="border-bottom: 1.5px solid #000;"></td>
                            </tr>
                        </table>
                        <table width="100%" class="table table-sm" style="table-layout: fixed; margin-top: 25px;">
                            <tr>
                                <td width="25%" class="text-left p-0"><b>4TH QUARTER</b></td>
                                <td width="75%" class="p-0" style="border-bottom: 1.5px solid #000;"></td>
                            </tr>
                        </table>
                    </td>
                    <td width="50%" style="padding-left: 10px!important;">
                        <table class="table table-sm studentinfo mb-0"  width="100%">
                            <tr>
                                <td width="100%" class="text-left" style="font-size:13px !important">DepEd FORM 138</td>
                            </tr>
                        </table>
                        <table class="table table-sm studentinfo mb-0"  width="100%" style="margin-top: 5px;">
                            <tr>
                                <td width="7%" class="align-middle text-left p-0">
                                    <img style="padding-top: 60px!important;" src="{{base_path()}}/public/{{$schoolinfo[0]->picurl}}" alt="school" width="65px" height="60px">
                                </td>
                                <td width="93%" class="align-middle text-center p-0" style="font-size: 14px;">
                                    <div>Republic of the Philippines</div>
                                    <div>Department of Education</div>
                                    <div>Region X</div>
                                    <div>Division of Bukidnon</div>
                                    <div>District 1</div>
                                    <div style="font-size: 15px!important;"><b>NUESTRA SEÑORA DEL PILAR HIGH SCHOOL</b></div>
                                    <div style="font-size: 17px!important; font-family:'Times New Roman', Times, serif !important; padding-top: 10px!important;"><b>LEARNER'S PROGRESS REPORT CARD</b></div>
                                </td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0"  width="100%"  style="margin-top: 30px!important; font-size: 13px;">
                            <tr>
                                <td width="10%" class="p-0">Name:</td>
                                <td width="40%" class="text-left p-0" style="border-bottom: 1px solid #000;"><b>{{$student->firstname.' '.$student->middlename[0].'. '.$student->lastname}}</b></td>
                                <td width="8%" class="text-center p-0">LRN:</td>
                                <td width="42%" class="text-left align-middle p-0" style="border-bottom: 1px solid #000;"><b>{{$student->lrn}}</b></td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0"  width="100%"  style="font-size: 13px; margin-top: 5px;">
                            <tr>
                                <td width="10%" class="p-0">Age:</td>
                                <td width="15%" class="text-left p-0" style="border-bottom: 1px solid #000;"><b>{{$student->age}}</b></td>
                                <td width="6%" class="text-left p-0"></td>
                                <td width="8%" class="text-center p-0">Sex:</td>
                                <td width="61%" class="text-left align-middle p-0" style="border-bottom: 1px solid #000;"><b>{{$student->lrn}}</b></td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0"  width="100%"  style="font-size: 13px; margin-top: 5px;">
                            <tr>
                                <td width="10%" class="p-0">Grade:</td>
                                <td width="15%" class="text-center p-0" style="border-bottom: 1px solid #000;"><b>{{str_replace('GRADE', '', $student->levelname)}}</b></td>
                                <td width="2%" class="text-left p-0"></td>
                                <td width="12%" class="text-left p-0">Section:</td>
                                <td width="61%" class="text-left align-middle p-0" style="border-bottom: 1px solid #000;"><b>{{$student->sectionname}}</b></td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0"  width="100%"  style="font-size: 13px; margin-top: 5px;">
                            <tr>
                                <td width="25%" class="p-0">School Year:</td>
                                <td width="14%" class="text-center p-0" style="border-bottom: 1px solid #000;"><b>{{$schoolyear->sydesc}}</b></td>
                                <td width="61%" class="text-left p-0"></td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0"  width="100%"  style="font-size: 13px; margin-top: 5px;">
                            <tr>
                                <td width="25%" class="p-0">Track/Strand:</td>
                                <td width="75%" class="text-center p-0" style="border-bottom: 1px solid #000;"><b></b></td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0 studentinfo"  width="100%" style="margin-top: 20px; font-size:13px;">
                            <tr>
                                <td width="100%" style="padding-left: 35px!important;"><b><i>Dear Parent/Guardian,</i></b></td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0 studentinfo"  width="100%" style="padding-left: 35px!important;">
                            <tr>
                                <td width="100%"> 
                                    <p style="font-size:13px; text-indent: 28px;  margin-bottom: 0 !important; margin-top: 0 !important; padding-top: 0 !important" ><b><i>This report card shows the ability and progress your child has made in the different learning areas as well as his/her core values.</i></b></p>
                                </td>
                            </tr>
                        </table>
                         <table class="table table-sm studentinfo"  width="100%" style="padding-left: 35px!important;">
                            <tr>
                                <td width="100%"> 
                                    <p style="font-size:13px; text-indent: 28px; margin-bottom: 0 !important;margin-top: 0 !important;padding-top: 0 !important" class="mb-0"><b><i>The school welcomes you should  you desire to know more about your child’s progress.</i></b></p>
                                </td>
                            </tr>
                        </table>
                       
                        <table class="table table-sm studentinfo mb-0"  width="100%" style="font-size: 13px; margin-top: 35px;">
                            <tr>
                                <td width="48%"></td>
                                <td width="2%"></td>
                                <td width="50%" class="text-center border-bottom"><b>{{$adviser}}</b></td>
                            </tr>
                              <tr>
                                <td width="48%"></td>
                                <td width="2%"></td>
                                <td width="50%" class="text-center"><b><i>Class Adviser</i></b></td>
                            </tr>
                        </table>
                        
                        <table class="table table-sm studentinfo mb-1"  width="100%" style="font-size: 13px;">
                            <tr>
                                <td width="48%" class="text-left border-bottom"><b>SR. LEONILA S. SAJELAN, MCM</b></td>
                                <td width="52%"></td>
                            </tr>
                            <tr>
                                <td width="48%" class="text-center"><b><i>School Principal</i></b></td>
                                <td width="52%"></td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0 studentinfo"  width="100%" style="margin-top: 5px; font-size: 13px;">
                            <tr>
                                <td width="100%" class="text-center"><b>Certificate of Transfer</b></td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0 studentinfo"  width="100%" style="margin-top: 10px; font-size: 13px;">
                            <tr>
                                <td width="18%">Admitted to: </td>
                                <td width="24%" class="text-center border-bottom"></td>
                                <td width="8%" class="text-left">Section:</td>
                                <td width="22%" class="text-center border-bottom"></td>
                                <td width="28%"></td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0 studentinfo"  width="100%" style="font-size: 13px;">
                            <tr>
                                <td width="41%">Eligibility for admission to Grade</td>
                                <td width="35%" class="text-center border-bottom"></td>
                                <td width="24%"></td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0 studentinfo"  width="100%" style="font-size: 13px;">
                            <tr>
                                <td width="100%">Approved by: </td>
                            </tr>
                        </table>
                        <table class="table table-sm studentinfo mb-0"  width="100%" style="font-size: 13px;">
                            <tr> 
                                <td width="40%" class="border-bottom"></td>
                                <td width="10%"></td>
                                <td width="50%" class="text-center border-bottom">&nbsp;</td>
                            </tr>
                              <tr>
                                <td width="40%" class="text-center">Principal</td>
                                <td width="10%"></td>
                                <td width="50%" class="text-center">Teacher</td>
                            </tr>
                        </table>
                      
                        {{-- <table class="table table-sm studentinfo  mb-1" width="100%" style="font-size: 13px;">
                            <tr>
                                <td width="45%" class="text-center border-bottom">SR. ELVIE G. BORLADO, PM, PhD.</td>
                                <td width="55%"></td>
                            </tr>
                            <tr>
                                <td width="45%" class="text-center">Director</td>
                                <td width="55%"></td>
                            </tr>
                        </table> --}}
                        
                        <table class="table table-sm studentinfo mb-0"  width="100%" style="font-size: 13px; margin-top: 5px;">
                            <tr>
                                <td width="100%" class="text-left" style="text-indent: 48px;">Cancellation of Eligibility to Transfer</td>
                            </tr>
                        </table>
                        
                        <table class="table table-sm mb-0 studentinfo"  width="100%" style="font-size: 13px;">
                            <tr>
                                <td width="18%">Admitted in: </td>
                                <td width="24%" class="text-center border-bottom"></td>
                                <td widht="58%"></td>
                            </tr>
                        </table>
                        <table class="table table-sm studentinfo mb-0"  width="100%" style="font-size: 13px;">
                            <tr> 
                                <td width="10%">Date:</td>
                                <td width="20%" class="border-bottom"></td>
                                <td width="19%"></td>
                                <td width="41%" class="text-center border-bottom"></td>
                                <td width="10%" class=""></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-center" style="text-indent: 60px;">Principal</td>
                                <td></td>
                            </tr>
                        </table>
                    </td>
                </tr>
        </table>
        <table class="table" width="100%">
                <tr>
                    <td width="50%" style="padding-right: 50px;">
                            <table  class="table table-sm mb-0" width="100%">
                                <tr>
                                    <td class="p-0" style="font-size: 13px;"><center><b>REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</b></center></td>
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
                                    <table class="table mb-0" width="100%" style="table-layout: fixed; margin-top: 10px;">
                                        <tr>
                                            <td width="72%" class="align-middle text-center p-0" style="font-size: 15px;"><b>{{$x == 1 ? 'First Semester' : 'Second Semester'}}</b></td>
                                            <td width="14%"></td>
                                            <td width="14%"></td>
                                        </tr>
                                    </table>
                                    <table class="table table-bordered grades_2 mb-0" width="100%" style="table-layout: fixed; margin-top: 10px;">
                                        <tr style="">
                                            <td rowspan="2" width="72%" class="align-middle text-center p-0" style="font-size: 13px!important;"><b>SUBJECTS</b></td>
                                            <td width="14%" colspan="2"  class="text-center align-middle" style="font-size: 12px!important;"><b>Quarter</b></td>
                                            <td width="14%" class="text-center align-middle p-0" style="font-size: 12px!important;"><b>Semester</b></td>
                                        </tr>
                                        <tr style="">
                                            @if($x == 1)
                                                <td class="text-center align-middle"><b>1</b></td>
                                                <td class="text-center align-middle"><b>2</b></td>
                                                <td class="text-center align-middle p-0" style="font-size: 11px!important;"><b>Final Grade</b></td>
                                            @elseif($x == 2)
                                                <td class="text-center align-middle"><b>3</b></td>
                                                <td class="text-center align-middle"><b>4</b></td>
                                                <td class="text-center align-middle p-0" style="font-size: 11px!important;"><b>Final Grade</b></td>
                                            @endif
                                        </tr>
                                        <tr><td colspan="4" style=" color:black; border:solid 1px black"><b>CORE Subjects</b></td></tr>
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
                                            </tr>
                                        @endforeach
                                        <tr><td colspan="4" style=" color:black; border:solid 1px black"><b>APPLIED Subjects</b></td></tr>
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
                                            </tr>
                                        @endforeach
                                          <tr><td colspan="4" style=" color:black; border:solid 1px black"><b>SPECIALIZED Subjects</b></td></tr>
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
                                            </tr>
                                        @endforeach
                                        <tr>
                                            @php
                                                $genave = collect($finalgrade)->where('semid',$x)->first();
                                            @endphp
                                            <td class="text-right" style="font-size: 13px!important;"><b>General Average for Semester</b></td>
                                            @if($x == 1)
                                                <td class="text-center align-middle">{{isset($genave->quarter1) ? $genave->quarter1 != null ? $genave->quarter1:'' : ''}}</td>
                                                <td class="text-center align-middle">{{isset($genave->quarter2) ? $genave->quarter2 != null ? $genave->quarter2:'' : ''}}</td>
                                            @elseif($x == 2)
                                                <td class="text-center align-middle">{{isset($genave->quarter3) ? $genave->quarter3 != null ? $genave->quarter3:'' : ''}}</td>
                                                <td class="text-center align-middle">{{isset($genave->quarter4) ? $genave->quarter4 != null ? $genave->quarter4:'' : ''}}</td>
                                            @endif
                                            <td class="text-center align-middle  p-0">{{ isset($genave->finalrating) ? $genave->finalrating != null ? $genave->finalrating:'' :''}}</td>
                                        </tr>
                                    </table>
                                @endfor
                            @endif
                           
                        </td>
                    <td width="50%" style="padding-right: 15px; vertical-align: top;">
                        <table  class="table table-sm mb-0" width="100%">
                            <tr>
                                <td class="p-0" style="font-size: 13px;"><center><b>REPORT ON LEARNER'S OBSERVED VALUES</b></center></td>
                            </tr>
                        </table>  
                        <table class="table-sm table table-bordered mb-0 mt-0" width="100%" style="margin-top: 43px; table-layout: fixed; padding-left: 30px!important; padding-right: 20px!important;">
                            <tr>
                                <td rowspan="2" class="align-middle text-center" style="font-size: 13px!important;"><b>Core <br> Values</b></td>
                                <td rowspan="2" class="align-middle text-center" style="font-size: 12px;"><b>Behavior Statement</b></td>
                                <td colspan="4" class="align-middle text-center"style="font-size: 12px;"><b>Quarter</b></td>
                            </tr>
                            <tr>
                                <td class="text-center" width="7%" style="font-size: 12px;"><center>1</center></td>
                                <td class="text-center" width="7%" style="font-size: 12px;"><center>2</center></td>
                                <td class="text-center" width="7%" style="font-size: 12px;"><center>3</center></td>
                                <td class="text-center" width="7%" style="font-size: 12px;"><center>4</center></td>
                            </tr>
                            @foreach (collect($checkGrades)->groupBy('group') as $groupitem)
                                @php
                                    $count = 0;
                                @endphp
                                @foreach ($groupitem as $item)
                                    @if($item->value == 0)
                                    @else
                                        <tr >
                                            @if($count == 0)
                                                    <td width="18%" class="text-center align-middle" style="font-size: 12px;" rowspan="{{count($groupitem)}}">&nbsp;&nbsp;{{$item->group}}&nbsp;&nbsp;&nbsp;</td>
                                                    @php
                                                        $count = 1;
                                                    @endphp
                                            @endif
                                            <td class="p-0" width="54%" class="align-middle" style="font-size: 12px; text-align: center!important; vertical-align: top!important;">{{$item->description}}</td>
                                            <td class="text-center align-middle">
                                                @foreach ($rv as $key=>$rvitem)
                                                    {{$item->q1eval == $rvitem->id ? $rvitem->value : ''}}
                                                @endforeach 
                                            </td>
                                            <td class="text-center align-middle">
                                                @foreach ($rv as $key=>$rvitem)
                                                    {{$item->q2eval == $rvitem->id ? $rvitem->value : ''}}
                                                @endforeach 
                                            </td>
                                            <td class="text-center align-middle">
                                                @foreach ($rv as $key=>$rvitem)
                                                    {{$item->q3eval == $rvitem->id ? $rvitem->value : ''}}
                                                @endforeach 
                                            </td>
                                            <td class="text-center align-middle">
                                                @foreach ($rv as $key=>$rvitem)
                                                    {{$item->q4eval == $rvitem->id ? $rvitem->value : ''}}
                                                @endforeach 
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endforeach
                            {{-- ========================================================== --}}
                        </table>  
                        <table class="table-sm table mb-0 mt-0" width="100%" style="table-layout: fixed; margin-top: 60px; padding-left: 30px!important; padding-right: 20px!important;">
                            <tr>
                                <td colspan="3" class="text-left p-0"><b>Observed Values</b></td>
                            </tr>
                            <tr>
                                <td width="18%" class="p-0"></td>
                                <td width="11%" class="text-center p-0"><b>Marking</b></td>
                                <td width="71%" class="text-center p-0"><b>Non-numerical Rating</b></td>
                            </tr>
                            <tr>
                                <td width="18%" class="p-0"></td>
                                <td width="11%" class="text-center p-0">AO</td>
                                <td width="71%" class="text-center p-0">Always Observed</td>
                            </tr>
                            <tr>
                                <td width="18%" class="p-0"></td>
                                <td width="11%" class="text-center p-0">SO</td>
                                <td width="71%" class="text-center p-0">Sometimes Observed</td>
                            </tr>
                            <tr>
                                <td width="18%" class="p-0"></td>
                                <td width="11%" class="text-center p-0">RO</td>
                                <td width="71%" class="text-center p-0">Rarely Observed</td>
                            </tr>
                            <tr>
                                <td width="18%" class="p-0"></td>
                                <td width="11%" class="text-center p-0">NO</td>
                                <td width="71%" class="text-center p-0">Not Observed</td>
                            </tr>
                        </table>
                        <table class="table-sm table mb-0 mt-0" width="100%" style="table-layout: fixed; margin-top: 20px; padding-left: 30px!important; padding-right: 20px!important;">
                            <tr>
                                <td colspan="3" class="p-0"><b>Learner Progress and Achievement:</b></td>
                            </tr>
                            <tr>
                                <td width="33%" class="p-0 align-middle" style="height: 20px;">&nbsp;&nbsp;&nbsp;Descriptors</td>
                                <td width="30%" class="p-0 text-center align-middle">Grading Scale</td>
                                <td width="30%" class="p-0 text-center align-middle">Remarks</td>
                                <td width="7%" class="p-0"></td>
                            </tr>
                            <tr>
                                <td class="p-0"><b>&nbsp;Outstanding</b></td>
                                <td class="p-0 text-center">90-100</td>
                                <td class="p-0 text-center">Passed</td>
                                <td width="7%"></td>
                            </tr>
                            <tr>
                                <td class="p-0"><b>&nbsp;Satisfactory</b></td>
                                <td class="p-0 text-center">85-89</td>
                                <td class="p-0 text-center">Passed</td>
                                <td width="7%" class="p-0"></td>
                            </tr>
                            <tr>
                                <td class="p-0"><b>&nbsp;Satisfactory</b></td>
                                <td class="p-0 text-center">80-84</td>
                                <td class="p-0 text-center">Passed</td>
                                <td width="7%" class="p-0"></td>
                            </tr>
                            <tr>
                                <td class="p-0"><b>&nbsp;Satisfactory</b></td>
                                <td class="p-0 text-center">75-79</td>
                                <td class="p-0 text-center">Passed</td>
                                <td width="7%" class="p-0"></td>
                            </tr>
                            <tr>
                                <td class="p-0"><b>&nbsp;Expectation</b></td>
                                <td class="p-0 text-center">Below 75</td>
                                <td class="p-0 text-center">Failed</td>
                                <td width="7%" class="p-0"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
        </table>

    
</div>

</body>
</html>