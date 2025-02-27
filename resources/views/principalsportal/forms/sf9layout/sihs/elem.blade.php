<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <title>{{$student->firstname.' '.$student->middlename[0].' '.$student->lastname}}</title> --}}
    <style>

        .table {
            /* width: 100%; */
            /* margin-bottom: 1rem; */
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
       
        .pl-3{
            padding-left: 1rem !important;
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
            font-size: .7rem !important;
        }

        .studentinfo td{
            padding-top: .1rem;
            padding-bottom: .1rem;
          
        }

        .bg-red{
            color: red;
            border: solid 1px black !important;
        }
        .trhead {
            background-color: #48b4e0; 
            color: #fff; font-size;
        }
        /* @page {  
            margin:30px 50px;
            
        } */
        body { 
            /* margin:0px 10px; */
            
        }

      

        @page { size: 5.5in 8.5in; margin: 10px 15px;}
        
    </style>
</head>
<body>  
	@php
		$acadtext = '';
		if($student->acadprogid == 3){
			$acadtext = 'GRADE SCHOOL';
		}else if($student->acadprogid == 4){
			$acadtext = 'JUNIOR HIGH SCHOOL';
		}
	@endphp
    <table style="width: 100%; table-layout: fixed;">
        <td style="width: 15%;text-align: left; font-size: 11px;"></td>
        <td style="width: 70%; text-align: center;"></td>
        <td style="font-size: 9px; text-align: right;">FORM 138</td>
    </table>
    <table style="width: 100%; table-layout: fixed;">
        <tr>
            <td style="vertical-align: top;">
                <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="80px">
            </td>
            <td style="width: 60%; text-align: center;">
                <div style="width: 100%; font-size: 12px;"><b>Republic of the Philippines</b></div>
                <div style="width: 100%; font-size: 13px;">Department of Education</div>
                <div style="width: 100%; font-size: 12px;"><b>Region X</b></div>
                <div style="width: 100%; font-size: 12px;"> Division of Bukidnon</div>
                <div style="width: 100%; font-size: 15px; color: rgb(4, 168, 4); font-weight: 900;  font-family: arial!important;">{{$schoolinfo[0]->schoolname}}</div>
                <div style="width: 100%; font-size: 14px; color: rgb(42, 19, 194); font-family: Bodoni MT Black;"><b>ELEMENTARY DEPARTMENT</b></div>
                <span>=============================================================================</span>
                <!-- <div style="width: 100%; font-weight: bold; font-size: 18px;">{{$schoolinfo[0]->schoolname}}</div>
                <div style="width: 100%; font-size: 12px;">{{$schoolinfo[0]->address}}</div>
                <div style="width: 100%; font-weight: bold; font-size: 18px;">{{$acadtext}}</div>
                <div style="width: 100%; font-size: 12px;"><i>(Government Recognition No. 79, s. 1950)</i></div>
                <div style="width: 100%; font-weight: bold; font-size: 15px;">SCHOOL ID - {{$schoolinfo[0]->schoolid}}</div>
                <div style="width: 100%; font-weight: bold; font-size: 13px;">(PAASCU ACCREDITED)</div>
                <div style="width: 100%; font-weight: bold; font-size: 13px; line-height: 5px;">&nbsp;</div>
                <div style="width: 100%; font-weight: bold; font-size: 18px;">REPORT CARD (SF 9)</div>
                <div style="width: 100%; font-weight: bold; font-size: 13px;">School Year: {{$schoolyear->sydesc}}</div> -->
            </td>
            <td></td>
        </tr>
    </table>
    <table style="width: 100%; table-layout: fixed;">
        <tr>
            <td style="width: 50%; text-align: center;">
                {{-- <span style="width: 100%; font-size: 12px;">Government Recognition No. 114 s. 1854<br>
                Accredited: Philippine Accrediting Association of School, Colleges and Universities (PAASCU) Level 2<br>
                School Year: {{$schoolyear->sydesc}}</span> --}}
                <span style="width: 100%; font-size: 12px;">Poblacion, Kadingilan, Bukidnon<br>
                                                            Government Recognition No. 314 s. 2008<br></span>
                 <span style="width: 100%; font-size: 11px;">ELEMENTARY PROGRESS REPORT</span>
                                                            
            </td>
        </tr>
    </table>
    <br>
    <div style="width: 90%; margin: auto; border: 2px solid rgb(4, 168, 4); padding: .5em; border-radius: 10px;font-size:11px!important;">
        <table class="table mb-1" width="100%">
            <tr>
                <td width="10%" class="text-left p-0"><b>NAME:</b></td>
                <td width="90%" class="text-center p-0" style="border-bottom: 1px solid #000; color: blue;"><b>{{$student->student}}</b></td>
            </tr>
        </table>
        <table class="table mb-1" width="100%">
            <tr>
                <td width="10%" class="text-left p-0"><b>LRN:</b></td>
                <td width="90%" class="text-center p-0" style="border-bottom: 1px solid #000; color: blue;"><b>{{$student->lrn}}</b></td>
            </tr>
        </table>
        <table class="table mb-1" width="100%">
            <tr>
                <td width="25%" class="text-left p-0"><b>Grade & Section:</b></td>
                <td width="75%" class="text-center p-0" style="border-bottom: 1px solid #000;"><b>{{$student->levelname}} - {{$student->sectionname}}</b></td>
            </tr>
        </table>
        <table class="table mb-1" width="100%">
            <tr>
                <td width="13%" class="p-0"></td>
                <td width="20%" class="text-left p-0"><b>School Year:</b></td>
                <td width="67%" class="text-center p-0" style="border-bottom: 1px solid #000;"><b>{{$schoolyear->sydesc}}</b></td>
            </tr>
        </table>
        <table class="table" width="100%">
            <tr>
                <td width="" class="text-center p-0" style="font-size: 12px!important;"><i>Curriculum K to 12 Basic Education Curriculum</i></td> 
            </tr>
        </table>
    </div>
    <table style="width: 100%; padding-top: 15px; text-align: center;">
        <tr>
            <td width="35%" style="border-bottom: 1px solid #000; font-size: 12px;">{{$principal_info[0]->name}}</td>
            <td width="30%" style=""></td>
            <td width="35%" style="border-bottom: 1px solid #000; font-size: 12px;">{{$adviser}}</td>
        </tr>
        <tr>
            <td width="30%" style="text-align: center; font-size: 12px;"><b>{{$principal_info[0]->title}}</b></td>
            <td width="40%" style=""></td>
            <td width="30%" style="text-align: center; font-size: 12px;"><b>Class Adviser</b></td>
        </tr>
    </table>
    <table style="width: 100%; padding-top: 15px; text-align: center;">
        <tr>
            <td width="20%" ></td>
            <td width="60%" style="text-align: center; font-size: 12px; border-bottom: 1px solid #000; "><b>SR. MA. MINDA D. DERILO, MCST</b></td>
            <td width="20%" ></td>
        </tr>
        <tr>
            <td width="35%" ></td>
            <td width="30%" style="text-align: center; font-size: 12px;"><b>School Directress</b></td>
            <td width="35%" ></td>
        </tr>
    </table>
    
    <table width="100%" style="padding-top: 5px;">
        <tr>
            <td width="48%" style="text-align: center; font-size: 10px; padding: 5px; border: 1px solid blue; vertical-align: top;">
                <table width="100%" style="">
                    <tr>
                        <td style="color: green;"><b>VISSION</b></td>
                    </tr>
                    <tr>
                        <td>A well-integrated Catholic Institution with
                            excellence in calues and academe as agent of
                            transformation where the Gospel values are lived.</td>
                    </tr>
                </table>
            </td>
            <td width="1%"></td>
            <td width="51%" style="text-align: center; font-size: 10px; padding: 5px; border: 1px solid blue; vertical-align: top;">
                <table width="100%">
                    <tr>
                        <td style="color: green;"><b>MISSION</b></td>
                    </tr>
                    <tr>
                        <td>We commit ourselves to a quality Catholic education
                            that builds a Christ-centered community and
                            produces transformative leaders through academic
                            excellence, perserverance love for creation and
                            humility in service.
                            </td>
                    </tr>
                    {{-- <tr>
                        <td>2. Provide venue and opportunities towards holistic formation.</td>
                    </tr>
                    <tr>
                        <td>3. Work for excellence in values and academe</td>
                    </tr>
                    <tr>
                        <td>4. Mold potential leaders as agent of transformation</td>
                    </tr>
                    <tr>
                        <td>5. Build a Christ-centered community</td>
                    </tr> --}}

                </table>
            </td>
        </tr>
    </table>
    <table width="100%" style="margin-top: .5%;">
        {{-- <tr>
            <td width="45" ></td>
            <td width="5" ></td>
            <td width="50" ></td>
        </tr> --}}
    </table>
    <table width="100%">
        <tr>
            <td width="48%" style="text-align: center; font-size: 10px; padding: 10px; border: 1px solid blue; vertical-align: top;">
                <table width="100%" style="">
                    <tr>
                        <td><b>DEAR PARENT</b></td>
                    </tr>
                    <tr>
                        <td>This Report Card is issued four times <br> a year to give you an update of your son's/daughter's performance in school.</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>You are requested to confer with the subject <br> teacher/s about his/her rating/s.</td>
                    </tr>
                    <tr>
                        <td>Thank you!</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="100%"><b><i>SIGNATURE OF PARENT/GUARDIAN:</i></b></td>
                    </tr>
                </table>
                <table width="100%" class="table table-sm">
                    <tr>
                        <td width="8%"  class="p-0"></td>
                        <td width="38%"  class="p-0" >1st Grading:</td>
                        <td width="44%"  class="p-0" style="border-bottom: 1px solid #000;"></td>
                        <td width="10%"  class="p-0"></td>
                    </tr>
                    <tr>
                        <td width="8%"  class="p-0"></td>
                        <td width="38%"  class="p-0" >2nd Grading:</td>
                        <td width="44%"  class="p-0" style="border-bottom: 1px solid #000;"></td>
                        <td width="10%"  class="p-0"></td>
                    </tr>
                    <tr>
                        <td width="8%"  class="p-0"></td>
                        <td width="38%"  class="p-0" >3rd Grading:</td>
                        <td width="44%"  class="p-0" style="border-bottom: 1px solid #000;"></td>
                        <td width="10%"  class="p-0"></td>
                    </tr>
                    <tr>
                        <td width="8%"  class="p-0"></td>
                        <td width="38%"  class="p-0" >4th Grading:</td>
                        <td width="44%"  class="p-0"  style="border-bottom: 1px solid #000;"></td>
                        <td width="10%"  class="p-0"></td>
                    </tr>
                </table>
            </td>
            <td width="1%"></td>
            <td width="51%" style="text-align: center; font-size: 10px; padding: 10px; border: 1px solid blue; vertical-align: top;">
                <table width="100%">
                    <tr>
                        <td width="42%">Admitted to Grade :</td>
                        <td width="48%"  class="p-0"  style="border-bottom: 1px solid #000;"></td>
                        <td width="10%"></td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="14%">Section:</td>
                        <td width="66%"  class="p-0"  style="border-bottom: 1px solid #000;"></td>
                        <td width="10%"></td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="68%">Eligibility for Admission to Grade:</td>
                        <td width="22%"  class="p-0"  style="border-bottom: 1px solid #000;"></td>
                        <td width="10%"></td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="53%">Approved :</td>
                        <td width="33%"></td>
                        <td width="4%"></td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="38%" style="border-bottom: 1px solid #000;">{{$principal_info[0]->name}}</td>
                        <td width="10%" style=""></td>
                        <td width="38%" style="border-bottom: 1px solid #000; font-size: 9px;">{{$adviser}}</td>
                        <td width="14%" style=""></td>
                    </tr>
                    <tr>
                        <td width="38%" style="text-align: center;"><b>{{$principal_info[0]->title}}</b></td>
                        <td width="10%" style=""></td>
                        <td width="38%" style="text-align: center;"><b>Class Adviser</b></td>
                        <td width="14%" style=""></td>
                    </tr>
                </table>
                <br>
                <table width="100%">
                    <tr>
                        <td width="100%"><b><i>CANCELLATION OF TRANSFER</i></b></td>
                    </tr>
                </table>
                <table width="100%" class="table table-sm">
                    <tr>
                        <td width="13%"></td>
                        <td width="26%" class="p-0" >Admitted in:</td>
                        <td width="51%" class="p-0" style="border-bottom: 1px solid #000;"></td>
                        <td width="10%"></td>
                    </tr>
                </table>
                <table width="100%" class="table table-sm">
                    <tr>
                        <td width="13%"></td>
                        <td width="18%" class="p-0" >Section:</td>
                        <td width="59%"  class="p-0" style="border-bottom: 1px solid #000;"></td>
                        <td width="10%"></td>
                    </tr>
                </table>
                <table width="100%" class="table table-sm">
                    <tr>
                        <td width="13%"></td>
                        <td width="12%"  class="p-0" >Date:</td>
                        <td width="65%"  class="p-0" style="border-bottom: 1px solid #000;"></td>
                        <td width="10%"></td>
                    </tr>
                </table>
                <br>
                <table width="100%" class="table table-sm">
                    <tr>
                        <td width="55%"></td>
                        <td width="35%"  class="p-0" style="border-bottom: 1px solid #000;"></td>
                        <td width="10%"></td>
                    </tr>
                    <tr>
                        <td width="55%"></td>
                        <td width="35%" class="text-center">Principal</td>
                        <td width="10%"></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <br>
    <table class="table table-sm table-bordered grades" width="100%" style="padding-top: 10px;">
        <thead>
            <tr style="font-size: 10px!important;">
                <td rowspan="2"  class="align-middle text-center" width="44%"><b>LEARNING AREAS</b></td>
                <td colspan="4"  class="text-center align-middle"><b>PERIODICAL RATINGS</b></td>
                <td class="text-center align-middle" width="8%" ><b>FINAL</b></td>
                <td rowspan="2"  class="text-center align-middle" width="18%" ><b>REMARKS</b></span></td>
            </tr>
            <tr>
                <td class="text-center align-middle" width="7.5%"><b>1</b></td>
                <td class="text-center align-middle" width="7.5%"><b>2</b></td>
                <td class="text-center align-middle" width="7.5%"><b>3</b></td>
                <td class="text-center align-middle" width="7.5%"><b>4</b></td>
                <td class="text-center align-middle"><b>RATING</b></td>
            </tr>
        </thead>
        <tbody>
            @foreach ($studgrades as $item)
                <tr>
                    <td style="padding-left:{{$item->subjCom != null ? '2rem':'.25rem'}}" >{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                    <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                    <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                    <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                    <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                    <td class="text-center align-middle">{{isset($item->finalrating) ? $item->finalrating:''}}</td>
                    <td class="text-center align-middle">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
                </tr>
            @endforeach
            <tr>
                <td class="text-left"><b>GENERAL AVERAGE</b></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="text-center {{collect($finalgrade)->first()->quarter1 < 75 ? 'bg-red':''}}">{{isset(collect($finalgrade)->first()->finalrating) ? collect($finalgrade)->first()->finalrating : ''}}</td>
                <td class="text-center align-middle" >{{isset(collect($finalgrade)->first()->actiontaken) ? collect($finalgrade)->first()->actiontaken : ''}}</td>
            </tr>
        </tbody>
    </table>
    {{-- attendance --}}
    <table style="width: 100%; margin-top: 10px; font-weight: bold;">
        <tr>
            <td  width="100%" class="p-0">
                @php
                    $width = count($attendance_setup) != 0? 55 / count($attendance_setup) : 0;
                @endphp
                <table class="table table-bordered table-sm grades mb-0" width="100%">
                    <tr>
                        <td class="text-center" width="100%" colspan="{{count($attendance_setup) + 2}}" style="vertical-align: middle; font-size: 12px!important; border: 1px solid #000;"><b>ATTENDANCE REPORT</b></td>
                    </tr>
                    <tr>
                        <td width="20%" style="border: 1px solid #000; text-align: center;">MONTH</td>
                        @foreach ($attendance_setup as $item)
                            <td class="text-center align-middle;" style="vertical-align: middle; font-size: 10px!important;" width="{{$width}}%"><span style="text-transform: uppercase;">{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</span></td>
                        @endforeach
                        <td class="text-center" width="10%" style="vertical-align: middle; font-size: 10px!important;"><span>TOTAL</span></td>
                    </tr>
                    <tr class="table-bordered">
                        <td >School Days</td>
                        @foreach ($attendance_setup as $item)
                            <td class="text-center align-middle">{{$item->days != 0 ? $item->days : '' }}</td>
                        @endforeach
                        <td class="text-center align-middle">{{collect($attendance_setup)->sum('days')}}</td>
                    </tr>
                    <tr class="table-bordered">
                        <td>Present Days</td>
                        @foreach ($attendance_setup as $item)
                            <td class="text-center align-middle">{{$item->days != 0 ? $item->present : ''}}</td>
                        @endforeach
                        <td class="text-center align-middle" >{{collect($attendance_setup)->where('days','!=',0)->sum('present')}}</td>
                    </tr>
                    <tr class="table-bordered">
                        <td>Late/Tardy</td>
                        @foreach ($attendance_setup as $item)
                            <td class="text-center align-middle" >{{$item->days != 0 ? $item->absent : ''}}</td>
                        @endforeach
                        <td class="text-center align-middle" >{{collect($attendance_setup)->sum('absent')}}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="table-sm table mb-0 mt-0" width="100%" style="border: 1px solid #000; margin-top: 10px;">
        <tr>
            <td class="align-middle text-center"><b>RATING ON STUDENT'S MANIFESTATION OF THE FOUR(4) CORE VALUES</b></td>
        </tr>
    </table>
    <table class="table-sm table mb-0 mt-0" width="100%" style="border: 1px solid #000;font-weight: bold;">
        <tr style="font-size: 9px!important;">
            <td class="align-middle text-center">LEGEND:</td>
            <td class="align-middle text-center"><span style="color: gray">AO</span>-Always Observed;</td>
            <td class="align-middle text-center"><span style="color: gray">SO</span>-So;</td>
            <td class="align-middle text-center"><span style="color: gray">G</span>-Good;</td>
            <td class="align-middle text-center"><span style="color: gray">F</span>-Fair;</td>
            <td class="align-middle text-center"><span style="color: gray">NI</span>-Needs Improvement</td>
        </tr>
    </table>
    <table class="table-sm table table-bordered mb-0 mt-0" width="100%">

        <tr>
            <td colspan="2" class="align-middle text-center">Observed Values and Attitudes</td>
            <td width="7.5%"><center>1st</center></td>
            <td width="7.5%"><center>2nd</center></td>
            <td width="7.5%"><center>3rd</center></td>
            <td width="7.5%"><center>4th</center></td>
        </tr>
        {{-- ========================================================== --}}
        @foreach (collect($checkGrades)->groupBy('group') as $groupitem)
            @php
                $count = 0;
            @endphp
            @foreach ($groupitem as $item)
                @if($item->value == 0)
                @else
                    <tr >
                        @if($count == 0)
                                <td width="71%" class="align-middle" colspan="2" rowspan="{{count($groupitem)}}">
                                <span>
                                    <b>{{$item->group}}</b> <br>
                                    {{$item->description}}
                                </span>
                                </td>
                                @php
                                    $count = 1;
                                @endphp
                        @endif
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
</body>
</html>