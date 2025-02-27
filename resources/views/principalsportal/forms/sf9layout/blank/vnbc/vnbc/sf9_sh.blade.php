<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{$student->firstname.' '.$student->middlename[0].' '.$student->lastname}}</title>
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

        .p-1{
            padding: .25rem !important;
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

        .studentinfo td{
            padding-top: .1rem;
            padding-bottom: .1rem;
          
        }

        .bg-red{
            color: red;
            border: solid 1px black;
        }

        .mt-2{
            margin-top: .50rem !important;
        }

        .rotate_text
        {
            -moz-transform:rotate(-90deg); 
            -moz-transform-origin: top left;
            -webkit-transform: rotate(-90deg);
            -webkit-transform-origin: top left;
            -o-transform: rotate(-90deg);
            -o-transform-origin:  top left;
            position:relative;
            left: 5px;
            width: 100px;
        }
        .rotated_cell
        {
            height: 200px;
            vertical-align:bottom
        }


        @page { size: 11in 8.5in; margin: .25in;  }
        
    </style>

    <style>

      
        .grades_display
        {
            font-family:"Calibri, sans-serif";
            font-size: 11px;
            table-layout: fixed;
            width: 100%;  
        }
        .rotated_cell
        {
            /* border: 1px solid black; */
            width: 23px;
        }



        /* .grades_display tr td:nth-child(3){
            width: 20%;
        } */

       

        .grades_display tr td:nth-child(1){
            width: 40%;
        }

        .header tr td:nth-child(1){
            width: 30%;
        }
        .header tr td:nth-child(2){
            width: 70%;
        }


        .rotate_text
        {
            -moz-transform:rotate(-90deg); 
            -moz-transform-origin: top left;
            -webkit-transform: rotate(-90deg);
            -webkit-transform-origin: top left;
            -o-transform: rotate(-90deg);
            -o-transform-origin:  top left;
            position:relative;
            left: -10px;
            width: 200px;
        }
        .rotated_cell
        {
            height: 80px;
            vertical-align:bottom
        }

        .bg-gray{
            background-color: gainsboro;
        }

        .text-red{
            border: solid 1px black !important;
            color: red;
        }
        
        .border-0{
            border:0!important;
        }
        
        .watermark {
            position: absolute;
            color: lightgray;
            opacity: 0.18;
            font-size: 3em;
            width: 100%;
            top: 25%;    
            text-align: center;
            z-index: -50;
        }
        // css example
        span {
          content: "\260E";
        }
    </style>
    <style>
        .temp_icon{ font-family: DejaVu Sans !important;}
  </style>
</head>
<body>
        <table class="table" width="100%">
                <tr>
                    <td width="50%" style="padding-right: .25in !important">
                        @if($student->acadprogid != 5)
                            <table width="100%" class="table" style="margin-top:80px">
                                 <tr>
                                     <td width="100%" class="text-center"><img src="{{base_path()}}/public/{{$schoolinfo[0]->picurl}}" alt="school" width="350px"></td>
                                 </tr>
                            </table>
                            <table width="100%" class="table table-sm mb-0" style="margin-top:40px">
                                <tr>
                                    <td width="100%" class="text-center p-0" style="font-size:22px !important"><b>Progress Report Card</b></td>
                                </tr>
                            </table>
                            <table width="100%" class="table table-sm">
                                <tr>
                                    <td width="100%" class="text-center p-0" style="font-size:13px !important"><i>School Year {{$schoolyear->sydesc}}</i></td>
                                </tr>
                            </table>
                            <table width="100%" class="table table-sm  mb-0" style="margin-top:40px">
                                <tr>
                                    <td width="100%" class="text-center p-0"><b><i>"The fear of the LORD is the beginning of knowledge,</i></b></td>
                                </tr>
                            </table>
                            <table width="100%" class="table table-sm mb-0">
                                <tr>
                                    <td width="100%" class="text-center p-0"><b><i>but fools despise wisdom and discipline."</i></b></td>
                                </tr>
                            </table>
                            <table width="100%" class="table table-sm">
                                <tr>
                                    <td width="100%" class="text-center p-0"><b><i>- Proverbs 1:7 -</b></i></td>
                                </tr>
                            </table>
                        @else
                            
                            <table class="table grades_display mb-0" width="100%">
                                <tr>
                                    <td class="text-center" style="font-size:13px !important"><strong>ATTENDANCE RECORD</strong></td>
                                </tr>
                            </table >
                            <table class="table-bordered grades_display">
                            
                                    <tr >
                                        <td class="border-0"></td>
                                        @foreach ($attendance_setup as $item)
                                            <td class="rotated_cell"> <div class="rotate_text" style="top: 80px !important">{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMMM')}}</div></td>
                                        @endforeach
                                        <td class="rotated_cell" >
                                            <div class="rotate_text" style="top: 80px !important">Total</div>
                                         </td>
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
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            <table class="table table-sm" width="100%" style="margin-top:20px; border-right:  solid 1px black; border-left:  solid 1px black; border-bottom:  solid 1px black; border-top:  solid 1px black;">
                                <tr>
                                    <td  width="100%" style=" padding: 20px !important;">
                                        <table class="table table-sm grades" width="100%">
                                            <tr>
                                                <td class="text-center"><b>CERTIFICATE OF TRANSFER</b></td>
                                            </tr>
                                        </table>
                                        <table class="table table-sm grades mb-0" width="100%">
                                            <tr>
                                                <td width="31%">Has been admitted to Grade</td>
                                                <td width="19%" class="border-bottom"></td>
                                                <td width="10%">Section</td>
                                                <td width="40%" class="border-bottom"></td>
                                            </tr>
                                        </table>
                                        <table class="table table-sm grades" width="100%">
                                            <tr>
                                                <td width="33%">Eligibility for admission to Grade</td>
                                                <td width="67%" class="border-bottom"></td>
                                            </tr>
                                        </table>
                                        <table class="table table-sm grades" width="100%">
                                            <tr>
                                                <td class="text-center"><b>CANCELLATION FOR TRANSFER AND ELIGIBILITY</b></td>
                                            </tr>
                                        </table>
                                        <table class="table table-sm grades" width="100%">
                                            <tr>
                                                <td width="32%">He/She has been admitted to</td>
                                                <td width="33%" class="border-bottom"></td>
                                                <td width="9%">Date:</td>
                                                <td width="26%" class="border-bottom"></td>
                                            </tr>
                                        </table>
                                        <table class="table table-sm grades mt-2 mb-0" width="100%">
                                            <tr>
                                                <td class="border-bottom text-center" width="45%">{{$adviser}}</td>
                                                <td width="10%"></td>
                                                <td class="border-bottom text-center" width="45%">{{$principal}}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-center" width="45%"><b>Adviser</b></td>
                                                <td width="10%"></td>
                                                <td class="text-center" width="45%"><b>Principal</b></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                          
                            <table class="table table-sm grades" width="100%" style="margin-top:20px">
                                <tr>
                                    <td class="text-center" style="font-size:13px !important"><strong>Parent's Signature</strong></td>
                                </tr>
                            </table>
                            <table class="table table-sm" width="100%">
                                <tr>
                                    <td width="18%">1st Grading</td>
                                    <td width="32%" class="border-bottom"></td>
                                    <td width="18%">2nd Grading</td>
                                    <td width="32%" class="border-bottom"></td>
                                </tr>
                                <tr>
                                    <td>3rd Grading</td>
                                    <td class="border-bottom"></td>
                                    <td>4th Grading</td>
                                    <td class="border-bottom"></td>
                                </tr>
                            </table>
                       
                        
                        @endif
                        @if($student->acadprogid != 5)
                            <table width="100%" class="table table-sm" style="margin-top:40px">
                                <tr>
                                    <td width="5%" class="text-center p-0"><b><i></b></i></td>
                                    <td width="21%" class="text-center p-0"><b><span class="temp_icon" style="font-size:15px !important">&#9742;</span> (032)231-2812</b></td>
                                    <td width="5%" class="text-center p-0"><b><i></b></i></td>
                                    <td width="40%" class="text-center p-0"><b><span class="temp_icon" style="font-size:15px !important">&#x2709;</span> registrar@vnbc.edu.ph </b></td>
                                    <td width="5%" class="text-center p-0"><b><i></b></i></td>
                                    <td width="24%" class="text-center p-0"><b><span class="temp_icon" style="font-size:15px !important">&#9776;</span> www.vnbc.edu.ph</b></td>
                                </tr>
                            </table>
                        @else
                            <table width="100%" class="table table-sm" style="margin-top:120px">
                                <tr>
                                    <td width="5%" class="text-center p-0"><b><i></b></i></td>
                                    <td width="21%" class="text-center p-0"><b><span class="temp_icon" style="font-size:15px !important">&#9742;</span> (032)231-2812</b></td>
                                    <td width="5%" class="text-center p-0"><b><i></b></i></td>
                                    <td width="40%" class="text-center p-0"><b><span class="temp_icon" style="font-size:15px !important">&#x2709;</span> registrar@vnbc.edu.ph </b></td>
                                    <td width="5%" class="text-center p-0"><b><i></b></i></td>
                                    <td width="24%" class="text-center p-0"><b><span class="temp_icon" style="font-size:15px !important">&#9776;</span> www.vnbc.edu.ph</b></td>
                                </tr>
                            </table>
                        @endif
                    </td>
                   
                    <td width="50%" style="padding-left: .25in !important" >
                        <div class="watermark"><img src="{{base_path()}}/public/{{$schoolinfo[0]->picurl}}" alt="school" width="450px"></div>
                        <table class="table table-sm" width="100%">
                            <tr>
                                <td class="text-center p-0" style="font-size: 25px !important; color:#00468e"><b>Central Philippine Nazarene College<br><span style="font-size: 20px !important; color:#00468e">(formerly Visayan Nazarene Bible College)</span></b></td>
                            </tr>
                            <tr>
                                <td class="text-center p-0" style="font-size: 14px !important;">St. Mary's Drive, Montebello Site, Apas, Cebu City </td>
                            </tr>
                            <tr>
                                <td class="text-center p-0" style="font-size: 14px !important;">Region VII</td>
                            </tr>
                            <tr>
                                <td class="text-center p-0" style="font-size: 14px !important;">Cebu City Division </td>
                            </tr>
                        </table>
                        <br>
                        <table class="table table-sm" width="100%">
                            <tr>
                                <td class="text-center p-0" style="font-size: 22px !important; color:#34891a"><b>BASIC EDUCATION DEPARTMENT </b></td>
                            </tr>
                            <tr>
                                <td class="text-center p-0" style="font-size: 20px !important; color:#d6111f"><i>"Mentoring Transformational Graduates"</i></td>
                            </tr>
                        </table>
                        <br>
                        <table class="table table-sm" width="100%">
                            <tr>
                                <td class="text-center p-0" style="font-size: 26px !important"><b>PROGRESS REPORT CARD </b></td>
                            </tr>
                            <tr>
                                <td class="text-center p-0" style="font-size: 15px !important"><b>SY {{$schoolyear->sydesc}}</b></td>
                            </tr>
                        </table>
                        <br>
                        @php
                            $middle = '';
                            if($student->middlename != null){
                                $temp_middle = explode(" ",$student->middlename);
                                foreach($temp_middle as $item){
                                    $middle .=  $item[0].'.';
                                }
                            }
                        @endphp
                        <table class="table table-sm  mb-1" width="100%">
                            <tr>
                                <td width="10%"><b>Name:</b></td>
                                <td width="80%" class="border-bottom  text-center">{{$student->lastname}}, {{$student->firstname}} {{$middle}}</td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-1" width="100%">
                            <tr>
                                <td width="10%"><b>LRN:</b></td>
                                <td width="40%" class="border-bottom text-center">{{$student->lrn}}</td>
                                <td width="9%"><b>Age:</b></td>
                                <td width="21%" class="border-bottom text-center">{{\Carbon\Carbon::parse($student->dob)->age}}</td>
                                <td width="10%"><b>Gender:</b></td>
                                <td width="20%" class="border-bottom text-center">{{$student->gender}}</td>
                            </tr>
                        </table>
                        <table class="table table-sm" width="100%">
                            <tr>
                                <td width="12%"><b>Grade:</b></td>
                                <td width="38%" class="border-bottom text-center">{{$student->levelname}}</td>
                                <td width="12%"><b>Section:</b></td>
                                <td width="38%" class="border-bottom text-center">{{$student->sectionname}}</td>
                            </tr>
                        </table>
                         <table class="table table-sm" width="100%">
                            <tr>
                                <td width="100%">
                                     <p style="font-size:12px!important;"><i>Dear Parents,</i></p>
                                    <p style="font-size:12px!important; text-indent: 50px;"> <i> This report card shows the ability and progress your child has made in the different learning areas as well as his / her core values. The school welcomes you should you desire to know more about your child's progress.</i></p>
                                </td>
                            </tr>
                        </table>
                        <table  class="table table-sm" width="100%">
                            <tr>
                                <td width="50%"></td>
                                <td width="50%" class="border-bottom text-center">{{$adviser}}</td>
                            </tr>
                             <tr>
                                <td></td>
                                <td class="text-center"><b>Adviser</b></td>
                            </tr>
                        </table>
                        <table  class="table table-sm" width="100%">
                            <tr>
                                <td width="50%" class="border-bottom text-center" >{{$principal}}</td>
                                <td width="50%"></td>
                            </tr>
                            <tr>
                                <td class="text-center"><b>Basic Education Principal</b></td>
                                <td></td>
                            </tr>
                        </table>
                    
                    </td>
                </tr>
        </table>
        <table class="table" width="100%">
                <tr>
                    <td width="50%" style="padding-right: .25in !important">
                           <table  class="table table-sm grades mb-0" width="100%">
                                <tr>
                                    <th class="text-center" style="font-size:13px !important">REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</th>
                                </tr>
                            </table>    
                           
                            @if($student->acadprogid != 5)
                                <table class="table table-sm table-bordered grades" width="100%">
                                    <tr>
                                        <td width="50%" rowspan="2"  class="align-middle text-center"><b>SUBJECTS</b></td>
                                        <td width="30%" colspan="4"  class="text-center align-middle"><b>PERIODIC RATINGS</b></td>
                                        <td width="10%" rowspan="2"  class="text-center align-middle"><b>FINAL RATING</b></td>
                                        <td width="10%" rowspan="2"  class="text-center align-middle" style="font-size:8px !important"><b>ACTION TAKEN</b></span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center align-middle"><b>1</b></td>
                                        <td class="text-center align-middle"><b>2</b></td>
                                        <td class="text-center align-middle"><b>3</b></td>
                                        <td class="text-center align-middle"><b>4</b></td>
                                    </tr>
                                        @foreach ($studgrades as $item)
                                            <tr>
                                                <td style="padding-left:{{$item->subjCom != null ? '2rem':'.25rem'}}" >{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                                <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                                <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                                <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                                <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                                <td class="text-center align-middle">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                                                <td class="text-center align-middle" style="font-size:8px !important">{{$item->actiontaken != null ? $item->actiontaken:''}}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="5"><b>GENERAL AVERAGE</b></td>
                                            <td class="text-center align-middle">{{$finalgrade[0]->finalrating != null ? $finalgrade[0]->finalrating:''}}</td>
                                            <td class="text-center align-middle" style="font-size:8px !important">{{$finalgrade[0]->actiontaken != null ? $finalgrade[0]->actiontaken:''}}</td>
                                        </tr>
                                    </table>
                                @else
                                    @for ($x=1; $x <= 2; $x++)
                                        <table class="table table-sm table-bordered grades" width="100%">
                                            <tr>
                                                <td colspan="5"  class="align-middle text-center"><b>{{$x == 1 ? '1ST SEMESTER' : '2ND SEMESTER'}}</b></td>
                                            </tr>
                                            <tr>
                                                <td width="60%" rowspan="2"  class="align-middle text-center"><b>SUBJECTS</b></td>
                                                <td width="20%" colspan="2"  class="text-center align-middle" ><b>PERIODIC RATINGS</b></td>
                                                <td width="10%" rowspan="2"  class="text-center align-middle" ><b>FINAL RATING</b></td>
                                                <td width="10%" rowspan="2"  class="text-center align-middle"><b>ACTION TAKEN</b></td>
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
                                            <tr>
                                                <td colspan="5" style="background-color:#00468e; color: white; border: solid 1px black">CORE</td>
                                            </tr>
                                            @foreach (collect($studgrades)->where('semid',$x)->where('type',1)->values() as $item)
                                                <tr>
                                                    <td>{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                                    @if($x == 1)
                                                        <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                                        <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                                    @elseif($x == 2)
                                                        <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                                        <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                                    @endif
                                                    <td class="text-center align-middle">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                                                    <td class="text-center align-middle">{{$item->actiontaken != null ? $item->actiontaken:''}}</td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="5" style="background-color:#00468e; color: white; border: solid 1px black">APPLIED / SPECIALIZED</td>
                                            </tr>
                                            @foreach (collect($studgrades)->where('semid',$x)->where('type',3)->values() as $item)
                                                <tr>
                                                    <td >{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                                    @if($x == 1)
                                                        <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                                        <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                                    @elseif($x == 2)
                                                        <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                                        <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                                    @endif
                                                    <td class="text-center align-middle">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                                                    <td class="text-center align-middle">{{$item->actiontaken != null ? $item->actiontaken:''}}</td>
                                                </tr>
                                            @endforeach
                                            @foreach (collect($studgrades)->where('semid',$x)->where('type',2)->values() as $item)
                                                <tr>
                                                    <td>{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                                    @if($x == 1)
                                                        <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                                        <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                                    @elseif($x == 2)
                                                        <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                                        <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                                    @endif
                                                    <td class="text-center align-middle">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                                                    <td class="text-center align-middle">{{$item->actiontaken != null ? $item->actiontaken:''}}</td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                @php
                                                    $genave = collect($finalgrade)->where('semid',$x)->first();
                                                @endphp
                                                <td colspan="3"><b>GENERAL AVERAGE</b></td>
                                                <td class="text-center align-middle">{{ isset($genave->finalrating) ? $genave->finalrating != null ? $genave->finalrating:'' :''}}</td>
                                                <td class="text-center align-middle">{{ isset($genave->actiontaken) ? $genave->actiontaken != null ? $genave->actiontaken:'' :''}}</td>
                                            </tr>
                                        </table>
                                    @endfor
                                
                                @endif
                            
                           @if($student->acadprogid != 5) 
                                <table class="table table-sm grades" width="100%">
                                    <tr>
                                        <td width="10%"></td>
                                        <td width="30%" class="text-center"><b>DESCRIPTORS</b></td>
                                        <td width="30%" class=" text-center"><b>GRADING SCALE</b></td>
                                        <td width="30%" class=" text-center"><b>REMARKS</b></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="text-center">Outstanding</td>
                                        <td class=" text-center">90-100</td>
                                        <td class=" text-center">Passed</td>
                                    </tr>
                                    <tr>
                                        <td></td>   
                                        <td class="text-center">Very Satisfactory</td>
                                        <td class=" text-center">85-89</td>
                                        <td class=" text-center">Passed</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="text-center">Satisfactory</td>
                                        <td class="text-center">80-84</td>
                                        <td class=" text-center">Passed</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="text-center">Fairly Satisfactory</td>
                                        <td class=" text-center">75-79</td>
                                        <td class=" text-center">Passed</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="text-center">Did Not Meet Expectations</td>
                                        <td class=" text-center">Below 75</td>
                                        <td class=" text-center">Failed</td>
                                    </tr>
                                </table>
                            @endif
                           
                        @if($student->acadprogid != 5) 
                            <table class="table grades_display mb-0" width="100%">
                                    <tr>
                                        <td class="text-center" style="font-size:13px !important"><strong>ATTENDANCE RECORD</strong></td>
                                    </tr>
                                </table >
                                <table class="table-bordered grades_display">
                                
                                        <tr >
                                            <td class="border-0"></td>
                                            @foreach ($attendance_setup as $item)
                                                <td class="rotated_cell"> <div class="rotate_text" style="top: 80px !important">{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMMM')}}</div></td>
                                            @endforeach
                                            <td class="rotated_cell" >
                                                <div class="rotate_text" style="top: 80px !important">Total</div>
                                             </td>
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
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                            
                                </table>
                            @endif
                          
                        </td>
                    <td width="50%" style="padding-left: .25in !important">
                        <table  class="table table-sm mb-0" width="100%">
                            <tr>
                                <th class="text-center " style="font-size:13px !important">REPORT ON LEARNER'S OBSERVED VALUES</th>
                            </tr>
                        </table>
                        <table class="table-sm table table-bordered"  width="100%" style="font-size:10 px !important">
                            <thead>
                                <tr>
                                    <th rowspan="2" width="23%" class="align-middle"><center >Core Values</center></th>
                                    <th rowspan="2" width="45%" class="align-middle"><center>Behavior Statements</center></th>
                                    <th colspan="4" class="cellRight"><center>Quarter</center></th>
                                </tr>
                                <tr>
                                    <th width="8%"><center>1</center></th>
                                    <th width="8%"><center>2</center></th>
                                    <th width="8%"><center>3</center></th>
                                    <th width="8%"><center>4</center></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (collect($checkGrades)->groupBy('group') as $groupitem)
                                    @php
                                        $count = 0;
                                    @endphp
                                    @foreach ($groupitem as $item)
                                        @if($item->value == 0)
                                                <tr>
                                                    <th colspan="6">{{$item->description}}</th>
                                                </tr>
                                        @else
                                                <tr>
                                                    @if($count == 0)
                                                            <td class="align-middle" rowspan="{{count($groupitem)}}">{{$item->group}}</td>
                                                            @php
                                                                $count = 1;
                                                            @endphp
                                                    @endif
                                                    <td class="align-middle">{{$item->description}}</td>
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
                                
                            </tbody>
                        </table>
                        @if($student->acadprogid != 5)
                            <table class="table table-sm grades" width="100%">
                                <tr>
                                    <td class="text-center"><b>Legend: AO-Always Observed | SO-Sometimes Observed | RO-Rarely Observed | NO-Not Observed</b></td>
                                </tr>
                            </table>
                        @endif
                        @if($student->acadprogid == 5)
                             <table class="table table-sm grades" width="100%">
                                <thead>
                                    <tr>
                                        <td width="25%"></td>
                                        <td width="20%"><b>Marking</b></td>
                                        <td width="30%"><b>Non- Numerical Rating</b></td>
                                        <td width="25%"></td>
                                    </tr>
                                </thead>
                                <tbody>lr
                                     @foreach ($rv as $key=>$rvitem)
                                        @if($rvitem->value != null)
                                            <tr>
                                                <td></td>
                                                <td clas="text-center">{{$rvitem->value}}</td>
                                                <td>{{$rvitem->description}}</td>
                                                <td></td>
                                             </tr>
                                        @endif
                                    @endforeach 
                                </tbody>
                            </table>
                                <table class="table table-sm grades" width="100%">
                                    <tr>
                                        <td width="10%"></td>
                                        <td width="30%" class="text-center"><b>DESCRIPTORS</b></td>
                                        <td width="30%" class=" text-center"><b>GRADING SCALE</b></td>
                                        <td width="30%" class=" text-center"><b>REMARKS</b></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="text-center">Outstanding</td>
                                        <td class=" text-center">90-100</td>
                                        <td class=" text-center">Passed</td>
                                    </tr>
                                    <tr>
                                        <td></td>   
                                        <td class="text-center">Very Satisfactory</td>
                                        <td class=" text-center">85-89</td>
                                        <td class=" text-center">Passed</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="text-center">Satisfactory</td>
                                        <td class="text-center">80-84</td>
                                        <td class=" text-center">Passed</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="text-center">Fairly Satisfactory</td>
                                        <td class=" text-center">75-79</td>
                                        <td class=" text-center">Passed</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="text-center">Did Not Meet Expectations</td>
                                        <td class=" text-center">Below 75</td>
                                        <td class=" text-center">Failed</td>
                                    </tr>
                                </table>
                            @endif
                        
                        @if($student->acadprogid != 5)
                        
                            <table class="table table-sm" width="100%" style="border-right:  solid 1px black; border-left:  solid 1px black; border-bottom:  solid 1px black; border-top:  solid 1px black;">
                                <tr>
                                    <td  width="100%" style=" padding: 20px !important;">
                                        <table class="table table-sm grades" width="100%">
                                            <tr>
                                                <td class="text-center"><b>CERTIFICATE OF TRANSFER</b></td>
                                            </tr>
                                        </table>
                                        <table class="table table-sm grades mb-0" width="100%">
                                            <tr>
                                                <td width="31%">Has been admitted to Grade</td>
                                                <td width="19%" class="border-bottom"></td>
                                                <td width="10%">Section</td>
                                                <td width="40%" class="border-bottom"></td>
                                            </tr>
                                        </table>
                                        <table class="table table-sm grades" width="100%">
                                            <tr>
                                                <td width="33%">Eligibility for admission to Grade</td>
                                                <td width="67%" class="border-bottom"></td>
                                            </tr>
                                        </table>
                                        <table class="table table-sm grades" width="100%">
                                            <tr>
                                                <td class="text-center"><b>CANCELLATION FOR TRANSFER AND ELIGIBILITY</b></td>
                                            </tr>
                                        </table>
                                        <table class="table table-sm grades" width="100%">
                                            <tr>
                                                <td width="32%">He/She has been admitted to</td>
                                                <td width="33%" class="border-bottom"></td>
                                                <td width="9%">Date:</td>
                                                <td width="26%" class="border-bottom"></td>
                                            </tr>
                                        </table>
                                        <table class="table table-sm grades mt-2 mb-0" width="100%">
                                            <tr>
                                                <td class="border-bottom text-center" width="45%">{{$adviser}}</td>
                                                <td width="10%"></td>
                                                <td class="border-bottom text-center" width="45%">{{$principal}}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-center" width="45%"><b>Adviser</b></td>
                                                <td width="10%"></td>
                                                <td class="text-center" width="45%"><b>Principal</b></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <table class="table table-sm grades" width="100%">
                                <tr>
                                    <td class="text-center"><b>Parent's Signature</b></td>
                                </tr>
                            </table>
                            <table class="table table-sm" width="100%">
                                <tr>
                                    <td width="18%">1st Grading</td>
                                    <td width="32%" class="border-bottom"></td>
                                    <td width="18%">2nd Grading</td>
                                    <td width="32%" class="border-bottom"></td>
                                </tr>
                                <tr>
                                    <td>3rd Grading</td>
                                    <td class="border-bottom"></td>
                                    <td>4th Grading</td>
                                    <td class="border-bottom"></td>
                                </tr>
                            </table>
                        @endif
                    </td>
                </tr>
        </table>

    
</div>

</body>
</html>