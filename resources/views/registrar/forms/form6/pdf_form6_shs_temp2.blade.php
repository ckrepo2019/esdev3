<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title>Document</title>
    <style>
        body{
            
        }
        @page{
            size: 13in 8.5in;
            margin: 50px 0.5in 0.5in 0.5in;
        }
        *{
            
            font-family: Arial, Helvetica, sans-serif;
        /* font-family:  "Times New Roman", Georgia, serif; */
            /* font-size: 12px; */
        }
        footer {
                    position: fixed; 
                    bottom: 0cm; 
                    left: 0cm; 
                    right: 0cm;
                    height: 2cm;
                }

        table{
            border-collapse: collapse;
        }
.table-layout td, .table-layout th{
    padding: 0px !important;
}
.text-center{
    text-align: center;
}
    header { position: fixed;  left: 0px; right: 0px; height: 250px; top: 0px; }
    footer { position: fixed; bottom: 90px; left: 0px; right: 0px; height: 50px; }
    </style>
</head>
<body>
    <header>
        @php
            $header_dept = 'BASIC EDUCATION DEPARTMENT';
            if($acadprogid == 4)
            {
                $header_dept .= ' - High  School';
            }
            elseif($acadprogid == 3)
            {
                $header_dept .= ' - Elementary';
            }
            elseif($acadprogid == 2)
            {
                $header_dept .= ' - Pre-school';
            }
            elseif($acadprogid == 5)
            {
                $header_dept .= ' - Senior High  School';
            }
            $signatories = DB::table('signatory')
                ->where('form','form6')
                ->where('deleted','0')
                ->get();

            if(count($signatories)>0)
            {
                $filtersignatories = collect($signatories)->where('acadprogid', $acadprogid)->values();
                if(count($filtersignatories)>0)
                {
                    $signatories = $filtersignatories;
                }
            }
            function lower_case($string_)
            {
                $exclude = array('and','in','of','the','on','at','or','for','as','sa');
                $subj_desc = strtolower($string_);
                $words = explode(' ', $subj_desc);
                foreach($words as $key => $word) {
                    if($key == 0)
                    {
                        $words[$key] = ucfirst($word);
                    }else{
                        if(in_array($word, $exclude)) {
                            continue;
                        }
                        $words[$key] = ucfirst($word);
                    }
                }
                return $subjectname = implode(' ', $words);
            }
            $countnum = 1;
            
        @endphp
        <table style="width: 100%; table-layout: fixed;">
            <tr>
                <td style="vertical-align: top;"><img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="70px"></td>
                <th colspan="8" style="font-size: 20px;"> School Form 6 Summarized Report of Learner Status as of End of Semester<br/>and School Year for Senior High School (SF6-SHS) </th>
                <td  style="text-align: right; vertical-align: top;"><img src="{{base_path()}}/public/assets/images/deped_logo.png" alt="school" width="110px"></td>
            </tr>
            <tr>
                <td colspan="10" style="line-height: 7px;">&nbsp;</td>
            </tr>
            <tr style="font-size: 12px;">
                <td>School Name</td>
                <td style="width: 24%; border: 1px solid black;">{{DB::table('schoolinfo')->first()->schoolname}}</td>
                <td style="width: 7%;">&nbsp;&nbsp;&nbsp;School ID</td>
                <td style="border: 1px solid black; text-align: center;">{{DB::table('schoolinfo')->first()->schoolid}}</td>
                <td style="width: 6%;">&nbsp;&nbsp;&nbsp;District</td>
                <td style="border: 1px solid black; text-align: center;">{{DB::table('schoolinfo')->first()->districttext ?? null}}</td>
                <td style="width: 7%;">&nbsp;&nbsp;&nbsp;Division</td>
                <td style="border: 1px solid black; text-align: center;">{{DB::table('schoolinfo')->first()->divisiontext ?? null}}</td>
                <td style="width: 6%;">&nbsp;&nbsp;&nbsp;Region</td>
                <td style="border: 1px solid black; text-align: center;">{{DB::table('schoolinfo')->first()->regiontext ?? null}}</td>
            </tr>
            <tr>
                <td colspan="10" style="line-height: 7px;">&nbsp;</td>
            </tr>
            <tr style="font-size: 12px;"> 
                <td>Semester</td>
                <td style="border: 1px solid black; text-align: center;">@if($semid == 1)First Semester @else Second Semester @endif</td>
                <td>&nbsp;&nbsp;&nbsp;School Year</td>
                <td colspan="2" style="border: 1px solid black; text-align: center;">{{$sydesc}}</td>
                <td colspan="5">&nbsp;</td>
            </tr>
                {{-- <tr>
                    <th rowspan="4" style="width: 10%; text-align: left; vertical-align: top; padding-right: 20px;">
                        <img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="120px">
                        <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="100px">
                    </th>
                    <th style="font-size: 24px;" colspan="8">School Form 6 (SF6)</th>
                    <th rowspan="4" style="width: 10%;">&nbsp;</th>
                </tr>
                <tr>
                    <th style="font-size: 20px;" colspan="8">Summarized Report on Promotion and Learning Progress & Achievement</th>
                </tr>
                <tr>
                    <td style="text-align: center; vertical-align: top;" colspan="8"><em><sup>Revised to conform with the instructions of Deped Order 8, s. 2015</sup></em></td>
                </tr>
                <tr style="font-size: 13px;">
                    <td colspan="2" style="width: 8%;">School ID</td>
                    <td style="border: 1px solid black; text-align: center;">{{DB::table('schoolinfo')->first()->schoolid}}</td>
                    <td style="width: 8%; text-align: right;">Region &nbsp;&nbsp;</td>
                    <td style="border: 1px solid black; text-align: center;">{{DB::table('schoolinfo')->first()->regiontext ?? null}}</td>
                    <td style="width: 8%; text-align: right;">Division &nbsp;&nbsp;</td>
                    <td style="border: 1px solid black; text-align: center;">{{DB::table('schoolinfo')->first()->divisiontext ?? null}}</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="10" style="line-height: 20x; !important;">&nbsp;</td>
                </tr>
                <tr style="font-size: 13px;">
                    <td colspan="2" style="text-align:right;">School Name &nbsp;&nbsp;</td>
                    <td colspan="4" style="border: 1px solid black; text-align: center;">{{DB::table('schoolinfo')->first()->schoolname}}</td>
                    <td style="width: 8%; text-align: right;">District &nbsp;&nbsp;</td>
                    <td style="border: 1px solid black; text-align: center;">{{DB::table('schoolinfo')->first()->districttext ?? null}}</td>
                    <td style="width: 10%; text-align: right;">School Year &nbsp;&nbsp;</td>
                    <td style="border: 1px solid black; text-align: center;">{{$sydesc}}</td>
                </tr>  --}}
        </table>
    </header>
    <footer>
        @if(count($signatories) == 0)
            <table style="width: 100%; font-size: 13px; table-layout: fixed;">
                <tr style="font-size: 11px;">
                    <td>Prepared and Submitted by:</td>
                    <td style="width: 7%;"></td>
                    <td>Reviewed and Validated by:</td>
                    <td style="width: 7%;"></td>
                    <td>Noted by:</td>
                    <td style="width: 7%;"></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="7" style="line-height: 5px;">&nbsp;</td>
                </tr>
                <tr style="font-size: 13px;">
                    <td style="text-align: center; border-bottom: 1px solid black;">&nbsp;{{DB::Table('schoolinfo')->first()->authorized}}&nbsp;</td>
                    <td></td>
                    <td style="text-align: center; border-bottom: 1px solid black;">&nbsp;&nbsp;</td>
                    <td></td>
                    <td style="text-align: center; border-bottom: 1px solid black;">&nbsp;&nbsp;</td>
                    <td></td>
                    <td style="text-align: center; border-bottom: 1px solid black;">&nbsp;&nbsp;</td>
                </tr>
                <tr style="font-size: 11px;">
                    <td style="text-align: center;">Signature of School Head over Printed Name</td>
                    <td></td>
                    <td style="text-align: center;">Signature of Division Superintendent</td>
                    <td></td>
                    <td style="text-align: center;">Signature of Division Representative</td>
                    <td></td>
                    <td style="text-align: center;">Generated thru LIS</td>
                </tr>
                <tr>
                    <td colspan="7" style="line-height: 10px;">&nbsp;</td>
                </tr>
            </table>
        @else
            <table style="width: 100%; font-size: 13px; table-layout: fixed;">
                <tr style="font-size: 11px;">
                    @foreach($signatories as $signkey=>$signatory)
                    <td>{{$signatory->title}}</td>
                    @if(isset($signatories[$signkey+1]))
                    <td style="width: 7%;"></td>
                    @endif
                    @endforeach
                </tr>
                <tr>
                    <td colspan="{{(count($signatories)*2)-1}}" style="line-height: 5px;">&nbsp;</td>
                </tr>
                <tr style="font-size: 13px;">
                    @foreach($signatories as $signkey=>$signatory)
                    <td style="text-align: center; border-bottom: 1px solid black;">&nbsp;{{$signatory->name}}&nbsp;</td>
                    @if(isset($signatories[$signkey+1]))
                    <td></td>
                    @endif
                    @endforeach
                </tr>
                <tr style="font-size: 11px;">
                    @foreach($signatories as $signkey=>$signatory)
                    <td style="text-align: center;">{{$signatory->description}}</td>
                    @if(isset($signatories[$signkey+1]))
                    <td style="width: 7%;"></td>
                    @endif
                    @endforeach
                </tr>
                <tr>
                    <td colspan="{{(count($signatories)*2)-1}}" style="line-height: 5px;">&nbsp;</td>
                </tr>
            </table>
        @endif
        <div style="width: 100%; font-size: 13px; font-weight: bold;">GUIDELINES:</div>
        <div style="width: 100%; font-size: 13px; padding-left: 30px;">1. After receiving and validating the report on Status of Learners submitted by the Class Adviser, the School Head shall compute the grade level total per track/strand/course and school total.</div>
        <div style="width: 100%; font-size: 13px; padding-left: 30px;">2. This report shall be forwarded to the Division Office by the end of the semester.</div>
        <div style="width: 100%; font-size: 13px; padding-left: 30px;">3. Column for End of School Year shall be accomplished at the end of SY or every after the 2nd semester.</div>
        <div style="width: 100%; font-size: 13px; padding-left: 30px;">4. Protocols of validation & submission are under the discretion of the Schools Division Superintendent.</div>
    </footer>
    <main style="margin-top: 150px;">
        <table style="width: 100%; font-size: 12px; table-layout: fixed;" border="1">
            <thead>
                <tr>
                    <th rowspan="3" colspan="2" style="vertical-align: middle;">GRADE LEVEL</th>
                    <th colspan="9" style="width: 35%; vertical-align: middle;">END OF SEMESTER STATUS</th>
                    <th colspan="9" style="width: 35%; vertical-align: middle;">END OF SCHOOL YEAR<br/>(Fill up only at the end of the second semester)</th>
                </tr>
                <tr>
                    <th colspan="3">COMPLETE</th>
                    <th colspan="3">INCOMPLETE</th>
                    <th colspan="3">TOTAL</th>
                    <th colspan="3">COMPLETE</th>
                    <th colspan="3">INCOMPLETE</th>
                    <th colspan="3">TOTAL</th>
                </tr>
                <tr style="font-size: 9px;">
                    <th>MALE</th>
                    <th>FEMALE</th>
                    <th>TOTAL</th>
                    <th>MALE</th>
                    <th>FEMALE</th>
                    <th>TOTAL</th>
                    <th>MALE</th>
                    <th>FEMALE</th>
                    <th>TOTAL</th>
                    <th>MALE</th>
                    <th>FEMALE</th>
                    <th>TOTAL</th>
                    <th>MALE</th>
                    <th>FEMALE</th>
                    <th>TOTAL</th>
                    <th>MALE</th>
                    <th>FEMALE</th>
                    <th>TOTAL</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th colspan="20" style="text-align: left; background-color: #ccccff;">GRADE 11</th>
                </tr>
                <tr>
                    <th colspan="20" style="text-align: left; background-color: #ccccff;">TRACK/STRAND/COURSE</th>
                </tr>
                @foreach(collect($gradelevels)->where('id',14)->values() as $gradelevel)
                    <tr>
                        <td style="width: 3% !important; font-size: 10px;">{{$countnum}}</td>
                        <td style="width: 30% !important; font-size: 10px;">{{$gradelevel->trackname}} - {{lower_case(strtolower($gradelevel->strandname))}}</td>
                        <td style="text-align: center;">{{$gradelevel->promotedmale}}</td>
                        <td style="text-align: center;">{{$gradelevel->promotedfemale}}</td>
                        <td style="text-align: center;">{{$gradelevel->promoted}}</td>
                        <td style="text-align: center;">{{$gradelevel->irregularmale}}</td>
                        <td style="text-align: center;">{{$gradelevel->irregularfemale}}</td>
                        <td style="text-align: center;">{{$gradelevel->irregular}}</td>
                        <td style="text-align: center;">{{$gradelevel->promotedmale+$gradelevel->irregularmale}}</td>
                        <td style="text-align: center;">{{$gradelevel->promotedfemale+$gradelevel->irregularfemale}}</td>
                        <td style="text-align: center;">{{$gradelevel->promoted+$gradelevel->irregular}}</td>
                        @if($semid == 1)
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        @else
                        <td style="text-align: center;">{{$gradelevel->promotedmale}}</td>
                        <td style="text-align: center;">{{$gradelevel->promotedfemale}}</td>
                        <td style="text-align: center;">{{$gradelevel->promoted}}</td>
                        <td style="text-align: center;">{{$gradelevel->irregularmale}}</td>
                        <td style="text-align: center;">{{$gradelevel->irregularfemale}}</td>
                        <td style="text-align: center;">{{$gradelevel->irregular}}</td>
                        <td style="text-align: center;">{{$gradelevel->promotedmale+$gradelevel->irregularmale}}</td>
                        <td style="text-align: center;">{{$gradelevel->promotedfemale+$gradelevel->irregularfemale}}</td>
                        <td style="text-align: center;">{{$gradelevel->promoted+$gradelevel->irregular}}</td>
                        @endif
                    </tr>
                    @php
                        $countnum+=1;
                    @endphp
                @endforeach
                <tr>
                    <th colspan="2" style="text-align: right;">SUB TOTAL</th>
                    <th>{{collect($gradelevels)->where('id',14)->sum('promotedmale')}}</th>
                    <th>{{collect($gradelevels)->where('id',14)->sum('promotedfemale')}}</th>
                    <th>{{collect($gradelevels)->where('id',14)->sum('promoted')}}</th>
                    <th>{{collect($gradelevels)->where('id',14)->sum('irregularmale')}}</th>
                    <th>{{collect($gradelevels)->where('id',14)->sum('irregularfemale')}}</th>
                    <th>{{collect($gradelevels)->where('id',14)->sum('irregular')}}</th>
                    <th>{{collect($gradelevels)->where('id',14)->sum('promotedmale') + collect($gradelevels)->where('id',14)->sum('irregularmale')}}</th>
                    <th>{{collect($gradelevels)->where('id',14)->sum('promotedfemale') + collect($gradelevels)->where('id',14)->sum('irregularfemale')}}</th>
                    <th>{{collect($gradelevels)->where('id',14)->sum('promoted') + collect($gradelevels)->where('id',14)->sum('irregular')}}</th>
                    @if($semid == 1)
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    @else
                    <th>{{collect($gradelevels)->where('id',14)->sum('promotedmale')}}</th>
                    <th>{{collect($gradelevels)->where('id',14)->sum('promotedfemale')}}</th>
                    <th>{{collect($gradelevels)->where('id',14)->sum('promoted')}}</th>
                    <th>{{collect($gradelevels)->where('id',14)->sum('irregularmale')}}</th>
                    <th>{{collect($gradelevels)->where('id',14)->sum('irregularfemale')}}</th>
                    <th>{{collect($gradelevels)->where('id',14)->sum('irregular')}}</th>
                    <th>{{collect($gradelevels)->where('id',14)->sum('promotedmale') + collect($gradelevels)->where('id',14)->sum('irregularmale')}}</th>
                    <th>{{collect($gradelevels)->where('id',14)->sum('promotedfemale') + collect($gradelevels)->where('id',14)->sum('irregularfemale')}}</th>
                    <th>{{collect($gradelevels)->where('id',14)->sum('promoted') + collect($gradelevels)->where('id',14)->sum('irregular')}}</th>
                    @endif
                </tr>
                @if(count($gradelevels)>12)

            </tbody>
        </table>
        <table style="width: 100%; font-size: 12px; table-layout: fixed; margin-top: 145px; page-break-before: always;" border="1">
            <thead>
                <tr>
                    <th rowspan="3" colspan="2" style="vertical-align: middle;">GRADE LEVEL</th>
                    <th colspan="9" style="width: 35%; vertical-align: middle;">END OF SEMESTER STATUS</th>
                    <th colspan="9" style="width: 35%; vertical-align: middle;">END OF SCHOOL YEAR<br/>(Fill up only at the end of the second semester)</th>
                </tr>
                <tr>
                    <th colspan="3">COMPLETE</th>
                    <th colspan="3">INCOMPLETE</th>
                    <th colspan="3">TOTAL</th>
                    <th colspan="3">COMPLETE</th>
                    <th colspan="3">INCOMPLETE</th>
                    <th colspan="3">TOTAL</th>
                </tr>
                <tr style="font-size: 9px;">
                    <th>MALE</th>
                    <th>FEMALE</th>
                    <th>TOTAL</th>
                    <th>MALE</th>
                    <th>FEMALE</th>
                    <th>TOTAL</th>
                    <th>MALE</th>
                    <th>FEMALE</th>
                    <th>TOTAL</th>
                    <th>MALE</th>
                    <th>FEMALE</th>
                    <th>TOTAL</th>
                    <th>MALE</th>
                    <th>FEMALE</th>
                    <th>TOTAL</th>
                    <th>MALE</th>
                    <th>FEMALE</th>
                    <th>TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @endif

                <tr>
                    <th colspan="20" style="text-align: left; background-color: #ccccff;">GRADE 12</th>
                </tr>
                <tr>
                    <th colspan="20"  style="text-align: left; background-color: #ccccff;">TRACK/STRAND/COURSE</th>
                </tr>
                @foreach(collect($gradelevels)->where('id',15)->values() as $gradelevel)
                    <tr>
                        <td style="width: 3% !important; font-size: 10px;">{{$countnum}}</td>
                        <td style="width: 30% !important; font-size: 10px;">{{$gradelevel->trackname}} - {{lower_case(strtolower($gradelevel->strandname))}}</td>
                        <td style="text-align: center;">{{$gradelevel->promotedmale}}</td>
                        <td style="text-align: center;">{{$gradelevel->promotedfemale}}</td>
                        <td style="text-align: center;">{{$gradelevel->promoted}}</td>
                        <td style="text-align: center;">{{$gradelevel->irregularmale}}</td>
                        <td style="text-align: center;">{{$gradelevel->irregularfemale}}</td>
                        <td style="text-align: center;">{{$gradelevel->irregular}}</td>
                        <td style="text-align: center;">{{$gradelevel->promotedmale+$gradelevel->irregularmale}}</td>
                        <td style="text-align: center;">{{$gradelevel->promotedfemale+$gradelevel->irregularfemale}}</td>
                        <td style="text-align: center;">{{$gradelevel->promoted+$gradelevel->irregular}}</td>
                        @if($semid == 1)
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        @else
                        <td style="text-align: center;">{{$gradelevel->promotedmale}}</td>
                        <td style="text-align: center;">{{$gradelevel->promotedfemale}}</td>
                        <td style="text-align: center;">{{$gradelevel->promoted}}</td>
                        <td style="text-align: center;">{{$gradelevel->irregularmale}}</td>
                        <td style="text-align: center;">{{$gradelevel->irregularfemale}}</td>
                        <td style="text-align: center;">{{$gradelevel->irregular}}</td>
                        <td style="text-align: center;">{{$gradelevel->promotedmale+$gradelevel->irregularmale}}</td>
                        <td style="text-align: center;">{{$gradelevel->promotedfemale+$gradelevel->irregularfemale}}</td>
                        <td style="text-align: center;">{{$gradelevel->promoted+$gradelevel->irregular}}</td>
                        @endif
                    </tr>
                    @php
                        $countnum+=1;
                    @endphp
                @endforeach
                <tr>
                    <th colspan="2" style="text-align: right;">SUB TOTAL</th>
                    <th>{{collect($gradelevels)->where('id',15)->sum('promotedmale')}}</th>
                    <th>{{collect($gradelevels)->where('id',15)->sum('promotedfemale')}}</th>
                    <th>{{collect($gradelevels)->where('id',15)->sum('promoted')}}</th>
                    <th>{{collect($gradelevels)->where('id',15)->sum('irregularmale')}}</th>
                    <th>{{collect($gradelevels)->where('id',15)->sum('irregularfemale')}}</th>
                    <th>{{collect($gradelevels)->where('id',15)->sum('irregular')}}</th>
                    <th>{{collect($gradelevels)->where('id',15)->sum('promotedmale') + collect($gradelevels)->where('id',15)->sum('irregularmale')}}</th>
                    <th>{{collect($gradelevels)->where('id',15)->sum('promotedfemale') + collect($gradelevels)->where('id',15)->sum('irregularfemale')}}</th>
                    <th>{{collect($gradelevels)->where('id',15)->sum('promoted') + collect($gradelevels)->where('id',15)->sum('irregular')}}</th>
                    @if($semid == 1)
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    @else
                    <th>{{collect($gradelevels)->where('id',15)->sum('promotedmale')}}</th>
                    <th>{{collect($gradelevels)->where('id',15)->sum('promotedfemale')}}</th>
                    <th>{{collect($gradelevels)->where('id',15)->sum('promoted')}}</th>
                    <th>{{collect($gradelevels)->where('id',15)->sum('irregularmale')}}</th>
                    <th>{{collect($gradelevels)->where('id',15)->sum('irregularfemale')}}</th>
                    <th>{{collect($gradelevels)->where('id',15)->sum('irregular')}}</th>
                    <th>{{collect($gradelevels)->where('id',15)->sum('promotedmale') + collect($gradelevels)->where('id',15)->sum('irregularmale')}}</th>
                    <th>{{collect($gradelevels)->where('id',15)->sum('promotedfemale') + collect($gradelevels)->where('id',15)->sum('irregularfemale')}}</th>
                    <th>{{collect($gradelevels)->where('id',15)->sum('promoted') + collect($gradelevels)->where('id',15)->sum('irregular')}}</th>
                    @endif
                </tr>
                <tr>
                    <th colspan="2" style="text-align: right;">TOTAL</th>
                    <th>{{collect($gradelevels)->sum('promotedmale')}}</th>
                    <th>{{collect($gradelevels)->sum('promotedfemale')}}</th>
                    <th>{{collect($gradelevels)->sum('promoted')}}</th>
                    <th>{{collect($gradelevels)->sum('irregularmale')}}</th>
                    <th>{{collect($gradelevels)->sum('irregularfemale')}}</th>
                    <th>{{collect($gradelevels)->sum('irregular')}}</th>
                    <th>{{collect($gradelevels)->sum('promotedmale') + collect($gradelevels)->sum('irregularmale')}}</th>
                    <th>{{collect($gradelevels)->sum('promotedfemale') + collect($gradelevels)->sum('irregularfemale')}}</th>
                    <th>{{collect($gradelevels)->sum('promoted') + collect($gradelevels)->sum('irregular')}}</th>
                    @if($semid == 1)
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    @else
                    <th>{{collect($gradelevels)->sum('promotedmale')}}</th>
                    <th>{{collect($gradelevels)->sum('promotedfemale')}}</th>
                    <th>{{collect($gradelevels)->sum('promoted')}}</th>
                    <th>{{collect($gradelevels)->sum('irregularmale')}}</th>
                    <th>{{collect($gradelevels)->sum('irregularfemale')}}</th>
                    <th>{{collect($gradelevels)->sum('irregular')}}</th>
                    <th>{{collect($gradelevels)->sum('promotedmale') + collect($gradelevels)->sum('irregularmale')}}</th>
                    <th>{{collect($gradelevels)->sum('promotedfemale') + collect($gradelevels)->sum('irregularfemale')}}</th>
                    <th>{{collect($gradelevels)->sum('promoted') + collect($gradelevels)->sum('irregular')}}</th>
                    @endif
                </tr>
            </tbody>
        </table>
    </main>
</body>
</html>