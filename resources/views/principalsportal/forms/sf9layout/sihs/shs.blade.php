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
        @page {  
            margin:20px 20px;
            
        }
        body { 
            /* margin:0px 10px; */
            
        }
		
		 .check_mark {
               font-family: ZapfDingbats, sans-serif;
            }

            @page { size: 5.5in 8.5in; margin: 10px 15px;}
        
    </style>
</head>
<body style="vertical-align: top;">  
    <table style="width: 100%; table-layout: fixed;">
        <td style="width: 15%;text-align: left; font-size: 11px;"></td>
        <td style="width: 70%; text-align: center;"></td>
        <td style="font-size: 9px; text-align: right;">FORM 138</td>
    </table>
    <table style="width: 100%; table-layout: fixed;">
        <tr>
            <td width="15%" style="vertical-align: top;">
                <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="80px">
            </td>
            <td style="width: 70%; text-align: center;">
                <div style="width: 100%; font-size: 12px;"><b>Republic of the Philippines</b></div>
                <div style="width: 100%; font-size: 13px;">Department of Education</div>
                <div style="width: 100%; font-size: 12px;"><b>Region X</b></div>
                <div style="width: 100%; font-size: 12px;"><b>Division of Bukidnon</b></div>
                <div style="width: 100%; font-size: 13px; color: rgb(4, 168, 4); font-weight: 900;  font-family: arial!important;">{{$schoolinfo[0]->schoolname}}</div>
                <div style="width: 100%; font-size: 11px;"><b>{{$schoolinfo[0]->address}}</b></div>
                <span>=============================================================================</span>

            </td>
            <td width="15%"></td>
        </tr>
    </table>
    <table style="width: 100%; table-layout: fixed; margin-bottom: 5px;">
        <tr>
            <td style="width: 50%; text-align: center;">
                <div style="width: 100%; font-size: 10px;">SHS GOVERNMENT PERMIT NO. 158 s.2015</div>
                <div style="width: 100%; font-size: 10px;">SENIOR HIGH SCHOOL PROGRESS</div>
                <div style="width: 100%; font-size: 12px;"><b>GRADE 12 REPORT CARD</b></div>
                <div style="width: 100%; font-size: 9px;">SY: {{$schoolyear->sydesc}}</div>                     
            </td>
        </tr>
    </table>
    <div style="width: 70%; margin: auto; border: 2px solid rgb(4, 168, 4); padding: .5em; border-radius: 20px;font-size:10px!important;">
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
                <td width="30%" class="text-left p-0"><b>Grade & Section:</b></td>
                <td width="70%" class="text-center p-0" style="border-bottom: 1px solid #000;"><b>{{$student->levelname}} - {{$student->sectionname}}</b></td>
            </tr>
        </table>
		<table class="table mb-1" width="100%">
            <tr>
                <td width="30%" class="text-left p-0"><b>Strand and Track</b></td>
                <td width="70%" class="text-center p-0" style="border-bottom: 1px solid #000;"><b>{{$strandInfo->strandcode}}</b></td>
            </tr>
        </table>
        <table class="table mb-1" width="100%">
            <tr>
                <td width="13%" class="p-0"></td>
                <td width="20%" class="text-left p-0"><b>School Year:</b></td>
                <td width="67%" class="text-center p-0" style="border-bottom: 1px solid #000;"><b>{{$schoolyear->sydesc}}</b></td>
            </tr>
        </table>
        <table class="table mb-0 mt-0" width="100%">
            <tr>
                <td width="" class="text-center p-0" style="font-size: 12px!important;"><i>K to 12 Basic Education Curriculum</i></td> 
            </tr>
        </table>
    </div>
    <table style="width: 100%; padding-top: 10px; text-align: center; font-size:11px!important">
        <tr>
            <td width="40%" style="border-bottom: 1px solid #000;">{{$principal_info[0]->name}}</td>
            <td width="20%" style=""></td>
            <td width="40%" style="border-bottom: 1px solid #000;">{{$adviser}}</td>
        </tr>
        <tr>
            <td width="40%" style="text-align: center; font-size: 11px;"><b>{{$principal_info[0]->title}}</b></td>
            <td width="20%" style=""></td>
            <td width="40%" style="text-align: center; font-size: 11px;"><b>Class Adviser</b></td>
        </tr>
    </table>
    <table style="width: 100%; padding-top: 5px; text-align: center; font-size: 11px; ">
        <tr>
            <td width="20%" ></td>
            <td width="60%" style="text-align: center;border-bottom: 1px solid #000; "><b>SR. MA. MINDA D. DERILO, MCST</b></td>
            <td width="20%" ></td>
        </tr>
        <tr>
            <td width="35%" ></td>
            <td width="30%" style="text-align: center;"><b>School Directress</b></td>
            <td width="35%" ></td>
        </tr>
    </table>
    <br>
    {{-- ======================================================================== --}}

    <div>
        <table width="100%">
            <tr>
                <td width="48%" style="text-align: center; font-size: 9px!important; padding: 5px; border: 1px solid blue; vertical-align: top;">
                    <table width="100%" style="">
                        <tr>
                            <td style="color: green;"><b>VISSION</b></td>
                        </tr>
                        <tr>
                            <td>San Isidro High School-Kadingilan, Inc, is a community of learners and evangelizers that provides sustainable, transformative, quality Catholic Education guided by the Gospel values.</td>
                        </tr>
                    </table>
                </td>
                <td width="1%"></td>
                <td width="51%" style="text-align: center; font-size: 9px!important; padding: 5px; border: 1px solid blue; vertical-align: top;">
                    <table width="100%">
                        <tr>
                            <td style="color: green;"><b>MISSION</b></td>
                        </tr>
                        <tr>
                            <td>We commit ourselves to a quality Catholic education that builds a Christ-centered community and produces transformative leaders through academic excellence, perserverance love for creation and humility in service.</td>
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
        <table width="100%" style="margin-top: 10px;">
        </table>
        <table width="100%">
            <tr>
                <td width="48%" style="text-align: center; font-size: 9px!important; padding-top: 5px; border: 1px solid blue; vertical-align: top;">
                    <table width="100%">
                        <tr>
                            <td><b>DEAR PARENT</b></td>
                        </tr>
                        <tr>
                            <td>This Report Card is issued four times a year to give you an update of your son's/daughter's performance in school.</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>You are requested to confer with the subject teacher/s about his/her rating/s.</td>
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
                    <table width="100%">
                        <tr>
                            <td width="100%"><b>FIRST SEMESTER</b></td>
                        </tr>
                    </table>
                    <table width="100%">
                        <tr>
                            <td width="8%" class="p-0"></td>
                            <td width="38%" class="p-0">1st Grading:</td>
                            <td width="44%" class="p-0" style="border-bottom: 1px solid #000;"></td>
                            <td width="10%" class="p-0"></td>
                        </tr>
                        <tr>
                            <td width="8%" class="p-0"></td>
                            <td width="38%" class="p-0">2nd Grading:</td>
                            <td width="44%" class="p-0"  style="border-bottom: 1px solid #000;"></td>
                            <td width="10%" class="p-0"></td>
                        </tr>
                    </table>
                    <table width="100%">
                        <tr>
                            <td width="100%"><b>SECOND SEMESTER</b></td>
                        </tr>
                    </table>
                    <table width="100%">
                        <tr>
                            <td width="8%" class="p-0"></td>
                            <td width="38%" class="p-0">3rd Grading:</td>
                            <td width="44%" class="p-0" style="border-bottom: 1px solid #000;"></td>
                            <td width="10%" class="p-0"></td>
                        </tr>
                        <tr>
                            <td width="8%" class="p-0"></td>
                            <td width="38%" class="p-0">4th Grading:</td>
                            <td width="44%" class="p-0" style="border-bottom: 1px solid #000;"></td>
                            <td width="10%" class="p-0"></td>
                        </tr>
                    </table>
                </td>
                <td width="1%"></td>
                <td width="51%" style="text-align: center; font-size: 9px!important; padding: 10px; border: 1px solid blue; vertical-align: top;">
                    <table width="100%">
                        <tr>
                            <td width="43%">Admitted to Grade :</td>
                            <td width="47%"  class="p-0"  style="border-bottom: 1px solid #000;"></td>
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
                            <td width="70%">Eligibility for Admission to Grade:</td>
                            <td width="20%" class="p-0"  style="border-bottom: 1px solid #000;"></td>
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
                    <br>
                    <table width="100%" style="margin-top: 10px!important;">
                        <tr>
							<td width="38%" style="border-bottom: 1px solid #000;"></td>
                            <td width="10%" style=""></td>
                            <td width="38%" style="border-bottom: 1px solid #000; font-size: 9px;"></td>
                            <td width="14%" style=""></td>
                        </tr>
                        <tr>
                            <td width="38%" style="text-align: center;"><b>Principal</b></td>
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
                    <table width="100%" class="table table-sm mb-0">
                        <tr>
                            <td width="13%" class="p-0" ></td>
                            <td width="26%" class="p-0" >Admitted in:</td>
                            <td width="51%" class="p-0" style="border-bottom: 1px solid #000;"></td>
                            <td width="10%" class="p-0" ></td>
                        </tr>
                    </table>
                    <table width="100%">
                        <tr>
                            <td width="13%" class="p-0" ></td>
                            <td width="18%" class="p-0" >Section:</td>
                            <td width="59%" class="p-0" style="border-bottom: 1px solid #000;"></td>
                            <td width="10%" class="p-0" ></td>
                        </tr>
                    </table>
                    <table width="100%">
                        <tr>
                            <td width="13%"></td>
                            <td width="12%"  class="p-0" >Date:</td>
                            <td width="65%"  class="p-0" style="border-bottom: 1px solid #000;"></td>
                            <td width="10%"></td>
                        </tr>
                    </table>
                    <table width="100%" style="margin-top: 30px;">
                        <tr>
                            <td width="55%" class="p-0"></td>
                            <td width="35%"  class="p-0" style="border-bottom: 1px solid #000;"></td>
                            <td width="10%" class="p-0"></td>
                        </tr>
                        <tr>
                            <td width="55%" class="p-0"></td>
                            <td width="35%" class="text-center p-0">Principal</td>
                            <td width="10%" class="p-0"></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    {{-- ======================================================================== --}}
    <br>
    <br>
    <br>
	<br>
    @php
    $x = $semid;
    @endphp
    {{-- ======================================================================== --}}
    <table class="table-sm table-bordered grades" width="100%">
        <thead>
            <tr style="font-size: 10px!important;">
                <td colspan="5" class="text-left" style="color: none;">&nbsp;</td>
            </tr>
            <tr style="font-size: 10px!important;">
                <td colspan="5" class="text-left"><b><span style="color: cornflowerblue">{{$student->levelname}} / </span> <span style="color: rgb(127, 46, 46);">FIRST SEMESTER</span></b></td>
            </tr>
            <tr style="font-size: 10px!important;">
                <td width="60%" rowspan="2"  class="align-middle text-center"><b><span style="color: cornflowerblue; font-size: 15px!important;">LEARNING AREAS</span></b></td>
                <td width="15%" colspan="2"  class="text-center align-middle"><b>QUARTER</b></td>
                <td width="8%" class="text-center align-middle"><b>FINAL</b></td>
                <td width="17%" rowspan="2"  class="text-center align-middle"><b>REMARKS</b></td>
            </tr>
            <tr>
                @if($x == 1)
                    <td class="text-center align-middle"><b>1st</b></td>
                    <td class="text-center align-middle"><b>2nd</b></td>
                    <td class="text-center align-middle"><b>RATING</b></td>
                @elseif($x == 2)
                    <td class="text-center align-middle"><b>3rd</b></td>
                    <td class="text-center align-middle"><b>4th</b></td>
                    <td class="text-center align-middle"><b>RATING</b></td>

                @endif
            </tr>
        </thead>
        <tbody>
            {{-- <tr>
                <td style="text-align: left; font-style: italic;"><b>CORE SUBJECTS</b></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr> --}}
            @foreach (collect($studgrades)->where('semid',$x) as $item)
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
                    <td class="text-center align-middle">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
                </tr>
            @endforeach
            {{-- <tr>
                <td style="text-align: left; font-style: italic;"><b>APPLIED SUBJECTS</b></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr> --}}
            <!-- @foreach (collect($studgrades)->where('type',3)->where('semid',$x) as $item)
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
                    <td class="text-center align-middle">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
                </tr>
            @endforeach -->
            {{-- <tr>
                <td style="text-align: left; font-style: italic;"><b>SPECIALIZED SUBJECTS</b></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr> --}}
            <!-- @foreach (collect($studgrades)->where('type',2)->where('semid',$x) as $item)
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
                    <td class="text-center align-middle">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
                </tr>
            @endforeach -->
              {{-- <tr>
                <th style="text-align: left; font-style: italic;"></th>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr> --}}
            <tr>
                @php
                    $genave = collect($finalgrade)->where('semid',$x)->first();
                @endphp
                <td colspan="3" class="text-center"style="font-size: 8px;"><b>GENERAL AVERAGE</b></td>
                <td class="text-center align-middle"style="font-size: 8px!important;">{{ isset($genave->finalrating) ? $genave->finalrating != null ? $genave->finalrating:'' :''}}</td>
                <td class="text-center align-middle" >{{isset(collect($finalgrade)->first()->actiontaken) ? collect($finalgrade)->first()->actiontaken : ''}}</td>
            </tr>
        </tbody>
    </table>
    {{-- ======================================================================== --}}
    
    @php
        $x = 2;
    @endphp
    <table class="table-sm table-bordered grades" width="100%">
        <thead>
            <tr style="font-size: 10px!important;">
                <td colspan="5" class="text-left"><b><span style="color: cornflowerblue">GRADE 12 / </span> <span style="color: rgb(127, 46, 46);">SECOND SEMESTER</span></b></td>
            </tr>
            <tr style="font-size: 10px!important;">
                <td width="60%" rowspan="2"  class="align-middle text-center"><b><span style="color: cornflowerblue; font-size: 15px!important;">LEARNING AREAS</span></b></td>
                <td width="15%" colspan="2"  class="text-center align-middle"><b>QUARTER</b></td>
                <td width="8%" class="text-center align-middle"><b>FINAL</b></td>
                <td width="17%" rowspan="2"  class="text-center align-middle"><b>REMARKS</b></td>
            </tr>
            <tr>
                @if($x == 1)
                    <td class="text-center align-middle"><b>1st</b></td>
                    <td class="text-center align-middle"><b>2nd</b></td>
                    <td class="text-center align-middle"><b>RATING</b></td>
                @elseif($x == 2)
                    <td class="text-center align-middle"><b>3rd</b></td>
                    <td class="text-center align-middle"><b>4th</b></td>
                    <td class="text-center align-middle"><b>RATING</b></td>
                @endif
            </tr>
        </thead>
        <tbody>
            {{-- <tr class="trhead">
                <td style="text-align: left;" colspan="5"><b>Core</b></td>
            </tr> --}}
            @foreach (collect($studgrades)->where('semid',$x) as $item)
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
                    <td class="text-center align-middle">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
                </tr>
            @endforeach
            {{-- <tr class="trhead">
                <td style="text-align: left;" colspan="5"><b>Applied</b></td>
            </tr> --}}
            <!-- @foreach (collect($studgrades)->where('type',3)->where('semid',$x) as $item)
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
                    <td class="text-center align-middle">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
                </tr>
            @endforeach -->
            {{-- <tr class="trhead">
                <td style="text-align: left;" colspan="4"><b>CONTEXTUALIZED SUBJECTS</b></td>
            </tr> --}}
            {{-- <tr class="trhead">
                <td style="text-align: left;" colspan="5"><b>Specialized</b></td>
            </tr> --}}
            <!-- @foreach (collect($studgrades)->where('type',2)->where('semid',$x) as $item)
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
                    <td class="text-center align-middle">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
                </tr>
            @endforeach -->
            
            <tr>
                @php
                    $genave = collect($finalgrade)->where('semid',$x)->first();
                @endphp
                <td class="text-center" colspan="3" style="font-size: 11px!important;"><b>GENERAL AVERAGE</b></td>
                <td class="text-right" style="font-weight: bold; font-size: 11px!important;padding-right: 32px;">{{ isset($genave->finalrating) ? $genave->finalrating != null ? $genave->finalrating:'' :''}}</td>
               <td class="text-center align-middle" >{{isset(collect($finalgrade)->first()->actiontaken) ? collect($finalgrade)->first()->actiontaken : ''}}</td>
            </tr>
        </tbody>
    </table>
    {{-- ======================================================================== --}}

    @php
        $width = count($attendance_setup) != 0? 55 / count($attendance_setup) : 0;
    @endphp
    <table class="table-bordered table-sm grades" width="100%">
        <tr>
            <th colspan="{{count($attendance_setup)+2}}" class="text-center" style="font-size: 9px;">ATTENDANCE REPORT</th>
        </tr>
        <tr class=" ">
            <td width="25%">Months of the S.Y</td>
            @foreach ($attendance_setup as $item)
                <td class="text-center align-middle" width="{{$width}}%"><b><span style="color: cornflowerblue">{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</span></b></td>
            @endforeach
            <td class="text-center text-center" width="10%" ><b><span style="color: cornflowerblue">Total</span></b></td>
        </tr>
        <tr class="table-bordered">
            <td >Total No. of Days</td>
            @foreach ($attendance_setup as $item)
                <td class="text-center align-middle">{{$item->days != 0 ? $item->days : '' }}</td>
            @endforeach
            <td class="text-center align-middle">{{collect($attendance_setup)->sum('days')}}</td>
        </tr>
        <tr class="table-bordered">
            <td>No. of Days Present </td>
            @foreach ($attendance_setup as $item)
                <td class="text-center align-middle">{{$item->days != 0 ? $item->present : ''}}</td>
            @endforeach
            <td class="text-center align-middle" >{{collect($attendance_setup)->where('days','!=',0)->sum('present')}}</td>
        </tr>
        <tr class="table-bordered">
            <td>Tardiness</td>
            @foreach ($attendance_setup as $item)
                <td class="text-center align-middle" >{{$item->days != 0 ? $item->absent : ''}}</td>
            @endforeach
            <td class="text-center align-middle" >{{collect($attendance_setup)->sum('absent')}}</td>
        </tr>
    </table>
    {{-- ====================================================================== --}}
    <table class="table-sm table mb-0 mt-0" width="100%" style="border: 1px solid #000;">
        <tr>
            <td class="align-middle text-center" style="font-size: 7px; color:rgb(4, 168, 4)"><b>RATING ON STUDENT'S MANIFESTATION OF THE FOUR(4) CORE VALUES</b></td>
        </tr>
    </table>
    <table class="table-sm table mb-0 mt-0" width="100%" style="border: 1px solid #000;font-weight: bold;">
        <tr style="font-size: 7px!important;">
            <td width="10%" class="text-left">LEGEND:</td>
            <td width="20%" class="text-left"><span style="color: cornflowerblue">AO</span>-Always Observed;</td>
            <td width="70%"class="text-left"><span style="color: cornflowerblue">SO</span>-Sometimes Observed</td>
        </tr>
    </table>
    <table class="table-sm table mb-0 mt-0" width="100%" style="border: 1px solid #000;font-weight: bold;">
        <tr style="font-size: 7px!important;">
            <td width="10%"></td>
            <td width="20%" class="text-left"><span style="color: cornflowerblue">RO</span>-Rarely Observed;</td>
            <td width="70%" class="text-left"><span style="color: cornflowerblue">NO</span>-Not Observed</td>
        </tr>
    </table>
    <table class="table-sm table table-bordered mb-0 mt-0" width="100%"  style="font-size: 7px;">
        <tr>
            <td colspan="2" class="align-middle text-center"><b>FOUR (4) CORE VALUES</b></td>
            <td width="6.5%"><center>1st</center></td>
            <td width="6.5%"><center>2nd</center></td>
            <td width="6.5%"><center>3rd</center></td>
            <td width="6.5%"><center>4th</center></td>
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
                                <td width="74%" class="align-middle" colspan="2" rowspan="{{count($groupitem)}}">
                                <span style="font-size: 8px;">
                                    <b>{{$item->group}}</b>
                                </span><br>
                                <span style="font-size: 6.5px;">
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