<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <title>{{$student->firstname.' '.$student->middlename[0].' '.$student->lastname}}</title> --}}
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
            font-size: 10px !important;
        }

        .studentinfo td{
            padding-top: .1rem;
            padding-bottom: .1rem;
          
        }

        .bg-red{
            color: red;
            border: solid 1px black !important;
        }

        td{
            padding-left: 5px;
            padding-right: 5px;
        }
        .aside {
            background: #48b4e0;
            color: #fff;
            line-height: 15px;
            height: 40px;
            border: 1px solid #000!important;
            
        }
        .aside span {
            /* Abs positioning makes it not take up vert space */
            /* position: absolute; */
            top: 0;
            left: 0;

            /* Border is the new background */
            background: none;

            /* Rotate from top left corner (not default) */
            transform-origin: 10 10;
            transform: rotate(-90deg);
        }
        .trhead {
            background-color: #48b4e0; 
            color: #fff; font-size;
        }
        .trhead td {
            border: 1px solid #000;
        }
        @page {  
            margin:0px 30px;
            
        }
        body { 
            /* margin:0px 10px; */
            
        }
		
		 .check_mark {
               font-family: ZapfDingbats, sans-serif;
            }

        @page { size: 8.5in 11in;} 
        
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
        <td style="width: 15%;text-align: left; font-size: 11px;">School Form - SF9</td>
        <td style="width: 50%; text-align: center;"></td>
        <td class="text-right" style="width: 20%;font-size: 11px;">LRN : {{$student->lrn}}</td>
    </table>
    <table style="width: 100%; table-layout: fixed;">
        <tr>
            <td style="width: 15%;text-align: right!important; vertical-align: top;">
                <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="100px">
            </td>
            <td style="width: 70%; text-align: center;">
                <span style="width: 100%; font-size: 11px;">Republic of the Philippines<br>Department of Education<br>
                Region X<br>
                Division of Bukidnon<br></span>
                <span style="width: 100%; font-size: 18px;"><b>{{$schoolinfo[0]->schoolname}}</b></span><br>
                <span style="width: 100%; font-size: 11px;">Camp Philips, Agusan Canyon, Manolo Fortich, Bukidnon<br>
                Government Recognition No. 114 s. 1854<br>
                Accredited: Philippine Accrediting Association of School, Colleges and Universities (PAASCU) Level 2<br>
                School Year: {{$schoolyear->sydesc}}</span>
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
    <!-- <table style="width: 100%; table-layout: fixed;">
        <tr>
            <td style="width: 50%; text-align: center;">
                <span style="width: 100%; font-size: 12px;">
                Accredited: Philippine Accrediting Association of School, Colleges and Universities (PAASCU) Level 2<br>
                School Year: {{$schoolyear->sydesc}}</span>
            </td>
        </tr>
    </table> -->
    <!-- <br/>
    <table style="width: 100%; table-layout: fixed;">
        <tr>
            <td style="text-align: right; vertical-align: top;">
                <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="100px">
            </td>
            <td style="width: 50%; text-align: center;">
                <div style="width: 100%; font-weight: bold; font-size: 18px;">{{$schoolinfo[0]->schoolname}}</div>
                <div style="width: 100%; font-size: 11px;">{{$schoolinfo[0]->address}}</div>
                <div style="width: 100%; font-weight: bold; font-size: 11px; font-style: italic;">SENIOR HIGH SCHOOL (GRADE)</div>
                <div style="width: 100%; font-size: 11px;">Government Permit No. 096, s. 2020</div>
                {{-- <div style="width: 100%; font-weight: bold; font-size: 13px; line-height: 5px;">&nbsp;</div> --}}
                <div style="width: 100%; font-weight: bold; font-size: 11px;">OFFICIAL REPORT CARD (SF 9)</div>
                <div style="width: 100%; font-size: 11px;">School Year: {{$schoolyear->sydesc}}</div>
            </td>
            <td></td>
        </tr>
    </table> -->
    <table style="width: 100%; font-size: 11px; margin-top: 5px;" >
        <tr>
            <td width="11%" class="text-left p-0">Student Name:</td>
            <td width="32%" class="text-left p-0" style="border-bottom: 1px solid #000;"><b>{{$student->student}}</b></td>
            <td width="4%" class="text-left p-0"></td>
            <td width="8%" class="text-left p-0">Address:</td>
            <td width="45%" class="text-left p-0" style="border-bottom: 1px solid #000;"><b></b></td>
        </tr>
       
        <!-- <tr>
            <td colspan="2" style="width: 20%;">Name : {{$student->student}}</td>
            <td  style="width: 20%;">Gender : {{$student->gender}}</td>
            <td width="60%">Grade & Section : {{$student->levelname}} - {{$student->sectionname}}</td>
        </tr>
        <tr>
            <td width="25%">LRN : {{$student->lrn}}</td>
            <td width="25%">Track :  Academic</td>
            <td width="15%">Strand : {{$strandInfo->strandcode}}</td>
            <td width="35%">Adviser : {{$adviser}}</td>
        </tr> -->
    </table>
    <table style="width: 100%; font-size: 11px; margin-top: 5px;" >
        <tr>
            <td width="15%" class="text-left p-0">Grade and Section:</td>
            <td width="28%" class="text-left p-0" style="border-bottom: 1px solid #000;"><b>{{$student->levelname}} - {{$student->sectionname}}</b></td>
            <td width="4%" class="text-left p-0"></td>
            <td width="15%" class="text-left p-0">Parent/Guardian:</td>
            <td width="38%" class="text-left p-0" style="border-bottom: 1px solid #000;"><b>MR. AND MRS. </b></td>
        </tr>
    </table>
    <table style="width: 100%; font-size: 11px; margin-top: 5px;" >
        <tr>
            <td width="5%" class="text-left p-0">Age:</td>
            <td width="38%" class="text-left p-0" style="border-bottom: 1px solid #000;"><b>{{$student->age}}</b></td>
            <td width="4%" class="text-left p-0"></td>
            <td width="15%" class="text-left p-0">Track & Strand:</td>
            <td width="38%" class="text-left p-0" style="border-bottom: 1px solid #000;"><b>Academic - {{$strandInfo->strandcode}}</b></td>
        </tr>
    </table>

    
    @php
        $x = 1;
    @endphp
	<!-- @if($x == 1)
		<div style="font-size: 11px; width: 100%; font-weight: bold; font-style: italic;"><u>FIRST SEMESTER</u></div> 
	@else
		<div style="font-size: 11px; width: 100%; font-weight: bold; font-style: italic;"><u>SECOND SEMESTER</u></div> 
    @endif -->
	
    <table style="width: 100%; margin-top: 5px;">
        <tr>
            <td  width="42.5%" class="p-0" style="vertical-align: top;">
                <table class="table table-bordered table-sm grades" width="100%">
                <tr>
                    <th class="text-center" colspan="4">FIRST SEMESTER</th>
                </tr>
                <tr>
                    <td width="63%" rowspan="2"  class="align-middle text-center"><b>SUBJECTS</b></td>
                    <td width="20%" colspan="2"  class="text-center align-middle" ><b>QUARTER</b></td>
                    <td width="17%" rowspan="2"  class="text-center align-middle" ><b>FINAL<br>GRADE</b></td>
                </tr>
                <tr>
                    @if($x == 1)
                        <td width="10%" class="text-center align-middle"><b>1</b></td>
                        <td width="10%" class="text-center align-middle"><b>2</b></td>
                    @elseif($x == 2)
                        <td width="10%" class="text-center align-middle"><b>3</b></td>
                        <td width="10%" class="text-center align-middle"><b>4</b></td>
                    @endif
                </tr>
                <tr class="trhead">
                    <td style="text-align: center;" colspan="4"><b>CORE SUBJECTS</b></td>
                </tr>
                @foreach (collect($studgrades)->where('type',1)->whereNotIn('subjid',[9,18,33,43])->where('semid',$x) as $item)
                    <tr>
                        <td style="font-size: 8px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                        @if($x == 1)
                            <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                            <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                        @elseif($x == 2)
                            <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                            <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                        @endif
                        <td class="text-center align-middle">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                    </tr>
                @endforeach
                <!-- <tr class="trhead">
                    <td style="text-align: center;" colspan="4"><b>CONTEXTUALIZED SUBJECTS</b></td>
                </tr> -->
                
                <tr class="trhead">
                    <td style="text-align: center;" colspan="4"><b>APPLIED SUBJECTS</b></td>
                </tr>
                @foreach (collect($studgrades)->where('type',3)->where('semid',$x) as $item)
                    <tr>
                        <td style="font-size: 8px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                        @if($x == 1)
                            <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                            <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                        @elseif($x == 2)
                            <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                            <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                        @endif
                        <td class="text-center align-middle">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                    </tr>
                @endforeach
                <tr class="trhead">
                    <td style="text-align: center;" colspan="4"><b>SPECIALIZED SUBJECTS</b></td>
                </tr>
                @foreach (collect($studgrades)->where('type',2)->where('semid',$x) as $item)
                    <tr>
                        <td style="font-size: 8px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                        @if($x == 1)
                            <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                            <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                        @elseif($x == 2)
                            <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                            <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                        @endif
                        <td class="text-center align-middle">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                    </tr>
                @endforeach
                 <tr class="trhead">
                        <td style="text-align: center;" colspan="4"><b>OTHER SUBJECTS</b></td>
                    </tr>
                    @foreach (collect($studgrades)->where('type',1)->whereIn('subjid',[9,18,33,43])->where('semid',$x) as $item)
                        <tr>
                            <td style="font-size: 8px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                            @if($x == 1)
                                <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                            @elseif($x == 2)
                                <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                            @endif
                            <td class="text-center align-middle">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                        </tr>
                    @endforeach
                <tr class="trhead">
                    <td style="text-align: center;" colspan="4"><b>&nbsp;</b></td>
                </tr>
                
                    @foreach (collect($checkGrades)->groupBy('group') as $groupitem)
                        @php
                            $count = 0;
                        @endphp
                        @foreach ($groupitem as $item)
                                    <tr>
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
                                        <td></td>
                                    </tr>
                        @endforeach
                    @endforeach
                    <!--<td>RHGP</td>-->
                    <!--<td class="text-center align-middle"></td>-->
                    <!--<td class="text-center align-middle"></td>-->
                    <!--<td class="text-center align-middle"></td>-->
                
                <!-- <tr>
                    <td>Conduct</td>
                    <td class="text-center align-middle"></td>
                    <td class="text-center align-middle"></td>
                    <td class="text-center align-middle"></td>
                </tr>
                <tr>
                    <td>Co-Curricular</td>
                    <td class="text-center align-middle"></td>
                    <td class="text-center align-middle"></td>
                    <td class="text-center align-middle"></td>
                </tr> -->
                <tr>
                    @php
                        $genave = collect($finalgrade)->where('semid',$x)->first();
                    @endphp
                    <td class="text-right"><b>GENERAL AVERAGE:</b></td>
                    <td colspan="3" style="font-weight: bold; font-size: 13px!important;padding-left: 55px;">{{number_format($genave->fcomp, 3)}}</td>
                </tr>
                </table>
            </td>
            <td width="4%" class="p-0"></td>
            <td width="52%" style="vertical-align: top;" class="p-0">
            @php
                $x = 2;
            @endphp
				<table class="table table-bordered table-sm grades" width="100%">
                    <tr>
                        <th class="text-center" colspan="4">SECOND SEMESTER</th>
                    </tr>
                    <tr>
                        <td width="68%" rowspan="2"  class="align-middle text-center"><b>SUBJECTS</b></td>
                        <td width="17%" colspan="2"  class="text-center align-middle" ><b>QUARTER</b></td>
                        <td width="15%" rowspan="2"  class="text-center align-middle" ><b>FINAL<br>GRADE</b></td>
                    </tr>
                    <tr>
                        @if($x == 1)
                            <td width="8.5%" class="text-center align-middle"><b>1</b></td>
                            <td width="8.5%" class="text-center align-middle"><b>2</b></td>
                        @elseif($x == 2)
                            <td width="8.5%" class="text-center align-middle"><b>3</b></td>
                            <td width="8.5%" class="text-center align-middle"><b>4</b></td>
                        @endif
                    </tr>
                    <tr class="trhead">
                        <td style="text-align: center;" colspan="4"><b>CORE SUBJECTS</b></td>
                    </tr>
                    @foreach (collect($studgrades)->where('type',1)->whereNotIn('subjid',[9,18,33,43])->where('semid',$x) as $item)
                        <tr>
                            <td style="font-size: 8px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                            @if($x == 1)
                                <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                            @elseif($x == 2)
                                <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                            @endif
                            <td class="text-center align-middle">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                        </tr>
                    @endforeach
                    <tr class="trhead">
                        <td style="text-align: center;" colspan="4"><b>APPLIED SUBJECTS</b></td>
                    </tr>
                    @foreach (collect($studgrades)->where('type',3)->where('semid',$x) as $item)
                        <tr>
                            <td style="font-size: 8px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                            @if($x == 1)
                                <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                            @elseif($x == 2)
                                <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                            @endif
                            <td class="text-center align-middle">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                        </tr>
                    @endforeach
                    <tr class="trhead">
                        <td style="text-align: center;" colspan="4"><b>SPECIALIZED SUBJECTS</b></td>
                    </tr>
                    @foreach (collect($studgrades)->where('type',2)->where('semid',$x) as $item)
                        <tr>
                            <td style="font-size: 8px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                            @if($x == 1)
                                <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                            @elseif($x == 2)
                                <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                            @endif
                            <td class="text-center align-middle">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                        </tr>
                    @endforeach
                    
                    <tr class="trhead">
                        <td style="text-align: center;" colspan="4"><b>OTHER SUBJECTS</b></td>
                    </tr>
                    @foreach (collect($studgrades)->where('type',1)->whereIn('subjid',[9,18,33,43])->where('semid',$x) as $item)
                        <tr>
                            <td style="font-size: 8px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                            @if($x == 1)
                                <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                            @elseif($x == 2)
                                <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                            @endif
                            <td class="text-center align-middle">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                        </tr>
                    @endforeach
                    <tr class="trhead">
                    <td style="text-align: center;" colspan="4"><b>&nbsp;</b></td>
                    </tr>
                     @foreach (collect($checkGrades)->groupBy('group') as $groupitem)
                        @php
                            $count = 0;
                        @endphp
                        @foreach ($groupitem as $item)
                                    <tr>
                                        <td class="align-middle">{{$item->description}}</td>
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
                                        <td></td>
                                    </tr>
                        @endforeach
                    @endforeach
                    <!--<tr>-->
                    <!--    <td>RHGP</td>-->
                    <!--    <td class="text-center align-middle"></td>-->
                    <!--    <td class="text-center align-middle"></td>-->
                    <!--    <td class="text-center align-middle"></td>-->
                    <!--</tr>-->
                    <!-- <tr>
                    <td>Conduct</td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                    </tr>
                    <tr>
                        <td>Co-Curricular</td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                    </tr> -->
                    <tr>
                        @php
                            $genave = collect($finalgrade)->where('semid',$x)->first();
                        @endphp
                        <td><b>GENERAL AVERAGE</b></td>
                        <td colspan="3" style="font-weight: bold; font-size: 13px!important;padding-left: 60px;">{{number_format($genave->fcomp, 3)}}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
	
    <table style="width: 100%; margin-top: 0px;">
        <tr>
            <td  width="65%" class="p-0">
                @php
                    $width = count($attendance_setup) != 0? 55 / count($attendance_setup) : 0;
                @endphp
                <table class="table table-bordered table-sm grades" width="100%">
                    <tr class="trhead">
                        <td width="25%" style="padding-top: 0px!important;border: 1px solid #000; text-align: center; vertical-align: middle;">ATTENDANCE REPORT</td>
                        @foreach ($attendance_setup as $item)
                            <td class="aside text-center align-middle;" width="{{$width}}%"><span style="text-transform: uppercase;">{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</span></td>
                        @endforeach
                        <td class="aside text-center align-middle;" width="10%"><span style="transform-origin: 21 10;">Total</span></td>
                    </tr>
                    <tr class="table-bordered">
                        <td >Days of School</td>
                        @foreach ($attendance_setup as $item)
                            <td class="text-center align-middle">{{$item->days != 0 ? $item->days : '' }}</td>
                        @endforeach
                        <td class="text-center align-middle">{{collect($attendance_setup)->sum('days')}}</td>
                    </tr>
                    <tr class="table-bordered">
                        <td>Days of Present </td>
                        @foreach ($attendance_setup as $item)
                            <td class="text-center align-middle">{{$item->days != 0 ? $item->present : ''}}</td>
                        @endforeach
                        <td class="text-center align-middle" >{{collect($attendance_setup)->where('days','!=',0)->sum('present')}}</td>
                    </tr>
                    <tr class="table-bordered">
                        <td>Days of Tardy</td>
                        @foreach ($attendance_setup as $item)
                            <td class="text-center align-middle" >{{$item->days != 0 ? $item->absent : ''}}</td>
                        @endforeach
                        <td class="text-center align-middle" >{{collect($attendance_setup)->sum('absent')}}</td>
                    </tr>
                    <tr class="table-bordered">
                        <td>Cutting Classes</td>
                        @foreach ($attendance_setup as $item)
                            <td class="text-center align-middle" >{{$item->days != 0 ? $item->absent : ''}}</td>
                        @endforeach
                        <td class="text-center align-middle" >{{collect($attendance_setup)->sum('absent')}}</td>
                    </tr>
                </table>
            </td>
            <td width="6%" class="p-0"></td>
            <td width="29%" style="vertical-align: top;" class="p-0">
				<table class="table table-bordered table-sm grades" width="100%">
                    <tr class="trhead">
                        <td class="text-center"  style="border: 1px solid #000;" colspan="2">RHGP/CONDUCT</td>
                    </tr>
                    <tr>
                        <td width="70%" class="text-center">DEVELOPED AND COMMENDABLE</td>
						<td width="30%" class="text-center">DC</td>
                    </tr>
                    <tr>
                        <td class="text-center">SUFFICIENTLY DEVELOPED</td>
						<td class="text-center">SD</td>
                    </tr>
                    <tr>
                        <td class="text-center">DEVELOPING</td>
						<td class="text-center">D</td>
                    </tr>
                    <tr>
                        <td class="text-center">NEEDS IMPROVEMENT</td>
						<td class="text-center">NI</td>
                    </tr>
                    <tr>
                        <td class="text-center">NO CHANCE TO OBSERVE</td>
						<td class="text-center">N</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table style="width: 100%; margin-top: 0px;">
        <tr>
            <td class="p-0" width="32.5%" style="border: 1px solid #000;">
            <table style="width: 100%; font-size: 11px; border: 1px solid #000;" >
                <tr class="trhead">
                    <td width="98%" class="text-center" colspan="2">Transfer Eligibility</td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 11px;">
                <!-- <tr style="background-color: skyblue;">
                    <td class="text-center" colspan="2">Transfer Eligibility</td>
                </tr> -->
                <tr>
                    <td width="61%" class="text-left">The bearer is promoted to: </td>
                    <td width="26%" class="text-left" style="border-bottom: 1px solid #000;"></td>
                    <td width="3%"></td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 11px;">
                <tr>
                    <td width="20%" class="text-left">&nbsp;</td>
                    <td width="77%" class="text-left" style="border-bottom: 1px solid #000; font-size: 9px;">{{$student->levelid == 14 ? 'GRADE 12' : 'COLLEGE'}}</td>
                    <td width="3%"></td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 11px;">
                <tr>
                    <td width="20%" class="text-left">Date:</td>
                    <td width="77%" class="text-left" style="border-bottom: 1px solid #000; font-size: 9px;">June 30, 2022</td>
                    <td width="3%"></td>
                </tr><br>
            </table>
            </td>
            <td width="4%"></td>
            <td  class="p-0" width="32.5%" style="vertical-align: top; border: 1px solid #000;">
                <table style="width: 100%; font-size: 11px; border: 1px solid #000;" >
                    <tr class="trhead">
                        <td width="98%" class="text-center" colspan="2">PARENTS SIGNATURE</td>
                    </tr>
                </table>
                <table style="width: 100%; font-size: 11px;">
                    <tr>
                        <td width="40%" class="text-left">First Quarter</td>
                        <td width="57%" class="text-left" style="border-bottom: 1px solid #000; font-size: 9px;"></td>
                        <td width="3%"></td>
                    </tr>
                </table>
                <table style="width: 100%; font-size: 11px;">
                    <tr>
                        <td width="40%" class="text-left">Second Quarter</td>
                        <td width="57%" class="text-left" style="border-bottom: 1px solid #000; font-size: 9px;"></td>
                        <td width="3%"></td>
                    </tr>
                </table>
                <table style="width: 100%; font-size: 11px;">
                    <tr>
                        <td width="40%" class="text-left">Third Quarter</td>
                        <td width="57%" class="text-left" style="border-bottom: 1px solid #000; font-size: 9px;"></td>
                        <td width="3%"></td>
                    </tr>
                </table>
                <table style="width: 100%; font-size: 11px;">
                    <tr>
                        <td width="40%" class="text-left">Fourth Quarter</td>
                        <td width="57%" class="text-left" style="border-bottom: 1px solid #000; font-size: 9px;"></td>
                        <td width="3%"></td>
                    </tr>
                </table>
            </td>
            <td width="31%" class="p-0"></td>
        </tr>
    </table>
    <br>
    <table style="width: 100%; margin-top: 0px;">
        <tr>
            <td class="p-0" width="32.5%" style="border: 1px solid #000;">
            <table style="width: 100%; font-size: 11px; border: 1px solid #000;" >
                <tr class="trhead">
                    <td width="98%" class="text-center" colspan="2">Cancellation of Transfer Eligibility</td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 11px;">
                <!-- <tr style="background-color: skyblue;">
                    <td class="text-center" colspan="2">Transfer Eligibility</td>
                </tr> -->
                <tr>
                    <td width="75%" class="text-left">The bearer has been accepted to: </td>
                    <td width="25%" class="text-left" style="border-bottom: 1px solid #000;"></td>
                    <td width="3%"></td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 11px;">
                <tr>
                    <td width="20%" class="text-left">&nbsp;</td>
                    <td width="77%" class="text-left" style="border-bottom: 1px solid #000; font-size: 9px;"></td>
                    <td width="3%"></td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 11px;">
                <tr>
                    <td width="20%" class="text-left">School:</td>
                    <td width="40%" class="text-left" style="border-bottom: 1px solid #000; font-size: 9px;"></td>
                    <td width="15%" class="text-left">Date:</td>
                    <td width="22%" class="text-left" style="border-bottom: 1px solid #000; font-size: 9px;"></td>
                    <td width="3%"></td>
                </tr><br>
            </table>
            </td>
            <td width="4%"></td>
            <td  class="p-0" width="32.5%" style="vertical-align: top;"></td>
            <td width="31%" class="p-0"></td>
        </tr>
    </table>
    <br>
    <br>
    <table style="width: 100%; font-size: 11px;" >
        <tr>
            <td width="32.5%" class="text-left p-0"></td>
            <td width="4%"></td>
            <td width="29%" class="text-center p-0" style="border-bottom: 1px solid #000; text-transform: uppercase!important;"><b>{{$adviser}}</b></td>
            <td width="5%"></td>
            <td width="30%"></td>
        </tr>
    </table>
    <table style="width: 100%; font-size: 11px;">
        <tr>
            <td width="32.5%" class="text-left p-0"></td>
            <td width="4%"></td>
            <td width="29%" class="text-center p-0" style="">Homeroom Adviser</td>
            <td width="5%"></td>
            <td width="30%"></td>
        </tr>
    </table>
    <br>
    <table style="width: 100%; font-size: 11px;" >
        <tr>
            <td width="32.5%" class="text-left p-0"></td>
            <td width="4%"></td>
            <td width="29%" class="text-center p-0" style="border-bottom: 1px solid #000; text-transform: uppercase!important;"><b>Sr. Mary Gertrude C. Juance, RSM</b></td>
            <td width="5%"></td>
            <td width="30%"></td>
        </tr>
    </table>
    <table style="width: 100%; font-size: 11px;" >
        <tr>
            <td width="32.5%" class="text-left p-0"></td>
            <td width="4%"></td>
            <td width="29%" class="text-center p-0" style="">Principal</td>
            <td width="5%"></td>
            <td width="30%"></td>
        </tr>
    </table>
	<!-- <table class="table table-sm" style="font-size: 10px; margin-top: 3px;" width="100%">
		<tr>
            <td  width="60%" class="p-0">
				<table class="table-sm table table-bordered"  width="100%">
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
                
            </td>
            <td width="2%"></td>
            <td width="38%" style="padding: 0px; vertical-align: top;">
				<table style="width: 100%; border: 1px solid black; vertical-align: top;" >
                    <tr>
                        <th colspan="2">Observed Values</th>
                    </tr>
                    <tr style=" font-style: italic;">
                        <th>Non-Numerical Ratings</th>
                        <th style="width: 40%;">Marking</th>
                    </tr>
                    <tr>
                        <td style="text-align: center;">Always Observed</td>
                        <td style="text-align: center;">AO</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">Sometimes Observed</td>
                        <td style="text-align: center;">SO</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">Rarely Observed</td>
                        <td style="text-align: center;">RO</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">Not Observed</td>
                        <td style="text-align: center;">NO</td>
                    </tr>
                </table>
				<br>
				<table style="border: 1px solid black; vertical-align: top;" width="100%">
                    <tr>
                        <th colspan="3">Academic Rating</th>
                    </tr>
                    <tr style=" font-style: italic;">
                        <th style="text-align: left !mportant; width: 40%;">Descriptors</th>
                        <th>Grading Scale</th>
                        <th>Remark</th>
                    </tr>
                    <tr>
                        <td>Outstanding</td>
                        <td style="text-align: center;">90-100</td>
                        <td style="text-align: center;">Passed</td>
                    </tr>
                    <tr>
                        <td>Very Satisfactory</td>
                        <td style="text-align: center;">85-89</td>
                        <td style="text-align: center;">Passed</td>
                    </tr>
                    <tr>
                        <td>Satisfactory</td>
                        <td style="text-align: center;">80-84</td>
                        <td style="text-align: center;">Passed</td>
                    </tr>
                    <tr>
                        <td>Fairly Satisfactory</td>
                        <td style="text-align: center;">75-79</td>
                        <td style="text-align: center;">Passed</td>
                    </tr>
                    <tr>
                        <td>Did Not Meet Expectations</td>
                        <td style="text-align: center;">Beloww 75</td>
                        <td style="text-align: center;">Failed</td>
                    </tr>
                </table>
            </td>
        </tr>
	
	</table> -->
    
	
	<!-- <table style="width: 100%; margin: 20px 120px; font-size: 12px;">
        <tr>
            <td style="width: 45%;">Eligible for transfer and admission to</td>
            <td style="border-bottom: 1px solid black;"></td>
        </tr>
    </table>
    <table style="width: 100%; margin: 40px 120px; font-size: 12px;">
        <tr>
            <td style="width: 45%; border-bottom: 1px solid black; text-align: center;">{{$principal_info[0]->name}}</td>
            <td style="width: 5%;"></td>
            <td style="border-bottom: 1px solid black;" class="text-center">{{\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMMM DD, YYYY')}}</td>
        </tr>
        <tr>
            <td style="text-align: center;">{{$principal_info[0]->title}}</td>
            <td></td>
            <td style="text-align: center;">Date</td>
        </tr>
    </table> -->
</body>
</html>