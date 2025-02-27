<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title>Document</title>
    <style>
        @page{
            size: 13in 8.5in;
            margin: 1in 0.5in
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
    header { position: fixed; top: -40px; left: 0px; right: 0px; height: 250px; }
    footer { position: fixed; bottom: 50px; left: 0px; right: 0px; height: 50px; }
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
            
        @endphp
        <table style="width: 100%;">
                <tr>
                    <th rowspan="4" style="width: 10%; text-align: left; vertical-align: top; padding-right: 20px;">
                        <img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="120px">
                        {{-- <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="100px"> --}}
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
                <tr>
                    <td colspan="2" style="width: 8%;">School ID</td>
                    <td style="border: 1px solid black; text-align: center;">{{DB::table('schoolinfo')->first()->schoolid}}</td>
                    <td style="width: 8%; text-align: right;">Region &nbsp;&nbsp;</td>
                    <td style="border: 1px solid black; text-align: center;">{{DB::table('schoolinfo')->first()->regiontext}}</td>
                    <td style="width: 8%; text-align: right;">Division &nbsp;&nbsp;</td>
                    <td style="border: 1px solid black; text-align: center;">{{DB::table('schoolinfo')->first()->divisiontext}}</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="10" style="line-height: 20x; !important;">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:right;">School Name &nbsp;&nbsp;</td>
                    <td colspan="4" style="border: 1px solid black; text-align: center;">{{DB::table('schoolinfo')->first()->schoolname}}</td>
                    <td style="width: 8%; text-align: right;">District &nbsp;&nbsp;</td>
                    <td style="border: 1px solid black; text-align: center;">{{DB::table('schoolinfo')->first()->districttext}}</td>
                    <td style="width: 10%; text-align: right;">School Year &nbsp;&nbsp;</td>
                    <td style="border: 1px solid black; text-align: center;">{{$sydesc}}</td>
                </tr> 
        </table>
    </header>
    <footer>
        @if(count($signatories) == 0)
            <table style="width: 100%; font-size: 13px;">
                <tr>
                    <td style="width: 16%;">Prepared and Submitted by: </td>
                    <td style="width: 20%;border-bottom: 1px solid black; text-align: center;">{{DB::table('schoolinfo')->first()->authorized}}</td>
                    <td style="width: 16%; text-align: right;">Reviewed & Validated by:</td>
                    <td style="text-align: center; border-bottom: 1px solid black;"></td>
                    <td style="width: 8%; text-align: right;">Noted by:</td>
                    <td style="width: 20%; border-bottom: 1px solid black; text-align: center;"></td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align: center; vertical-align: top;">SCHOOL HEAD</td>
                    <td></td>
                    <td style="text-align: center; vertical-align: top;">DIVISION REPRESENTATIVE</td>
                    <td></td>
                    <td style="text-align: center; vertical-align: top;">SCHOOLS DIVISION SUPERINTENDENT</td>
                </tr>
            </table>
        @else
            <table style="width: 100%; font-size: 13px;">
                <tr>
                    <td style="width: 16%;">@if(isset($signatories[0])){{$signatories[0]->title}}: @else Prepared and Submitted by: @endif</td>
                    <td style="width: 20%;border-bottom: 1px solid black; text-align: center;">@if(isset($signatories[0])){{$signatories[0]->name}} @endif</td>
                    <td style="width: 16%; text-align: right;">@if(isset($signatories[1])){{$signatories[1]->title}}: @else Reviewed & Validated by: @endif</td>
                    <td style="text-align: center; border-bottom: 1px solid black;">@if(isset($signatories[1])){{$signatories[1]->name}} @endif</td>
                    <td style="width: 8%; text-align: right;">@if(isset($signatories[2])){{$signatories[2]->title}}: @else Noted by: @endif</td>
                    <td style="width: 20%; border-bottom: 1px solid black; text-align: center;">@if(isset($signatories[2])){{$signatories[2]->name}} @endif</td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align: center; vertical-align: top;">@if(isset($signatories[0])){{$signatories[0]->description}} @else SCHOOL HEAD @endif</td>
                    <td></td>
                    <td style="text-align: center; vertical-align: top;">@if(isset($signatories[1])){{$signatories[1]->description}} @else DIVISION REPRESENTATIVE @endif</td>
                    <td></td>
                    <td style="text-align: center; vertical-align: top;">@if(isset($signatories[2])){{$signatories[2]->description}} @else SCHOOLS DIVISION SUPERINTENDENT @endif</td>
                </tr>
            </table>
        @endif
        <div style="width: 100%; font-size: 13px; font-weight: bold;">GUIDELINES:</div>
        <div style="width: 100%; font-size: 13px; padding-left: 30px;">1. After receiving and validating the Report for Promotion submitted by the class adviser, the School Head shall compute the grade level total and school total.</div>
        <div style="width: 100%; font-size: 13px; padding-left: 30px;">2. This report together with the copy of Report for Promotion submitted by the class adviser shall be forwarded to the Division Office by the end of the school year.</div>
        <div style="width: 100%; font-size: 13px; padding-left: 30px;">3. The Report on Promotion per grade level is reflected in the End of School Year Report of GESP/GSSP.</div>
        <div style="width: 100%; font-size: 13px; padding-left: 30px;">4. Protocols of validation & submission is under the discretion of the Schools Division Superintendent.</div>
    </footer>
    <main style="margin-top: 150px;">
        @if($acadprogid == 5)      
            @php
                $gradelevels = array_chunk($gradelevels,'6');
            @endphp  
            @foreach($gradelevels as $key=>$eachchunk)
                <table style="font-size: 11px; width: 100%;@if($key>0) margin-top: 150px; @endif" border="1">
                    <thead class="text-center">
                        <tr>
                            <th rowspan="2" style="vertical-align: middle;">Summary Table</th>
                            @foreach($eachchunk as $eachgradelevel)
                            <th colspan="3">{{$eachgradelevel->levelname}}<br/>({{$eachgradelevel->strandcode}})</th>
                            @endforeach
                            <th colspan="3" style="vertical-align: middle;">Total</th>
                        </tr>
                        <tr>
                            @foreach($eachchunk as $eachgradelevel)
                            <th style="font-size: 9px;">MALE</th>
                            <th style="font-size: 9px;">FEMALE</th>
                            <th style="font-size: 9px;">TOTAL</th>
                            @endforeach
                            <th style="font-size: 9px;">MALE</th>
                            <th style="font-size: 9px;">FEMALE</th>
                            <th style="font-size: 9px;">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>PROMOTED</th>
                            @foreach($eachchunk as $eachgradelevel)
                            <td class="text-center">{{$eachgradelevel->promotedmale}}</td>
                            <td class="text-center">{{$eachgradelevel->promotedfemale}}</td>
                            <td class="text-center">{{$eachgradelevel->promoted}}</th>
                            @endforeach
                            <th class="text-center">{{collect($eachchunk)->sum('promotedmale')}}</th>
                            <th class="text-center">{{collect($eachchunk)->sum('promotedfemale')}}</th>
                            <th class="text-center">{{collect($eachchunk)->sum('promoted')}}</th>
                        </tr>
                        @if($acadprogid != 3)
                        <tr>
                            <th>IRREGULAR (Grade 7 onwards only)	</th>
                            @foreach($eachchunk as $eachgradelevel)
                            <td class="text-center">{{$eachgradelevel->irregularmale}}</td>
                            <td class="text-center">{{$eachgradelevel->irregularfemale}}</td>
                            <td class="text-center">{{$eachgradelevel->irregular}}</th>
                            @endforeach
                            <th class="text-center">{{collect($eachchunk)->sum('irregularmale')}}</th>
                            <th class="text-center">{{collect($eachchunk)->sum('irregularfemale')}}</th>
                            <th class="text-center">{{collect($eachchunk)->sum('irregular')}}</th>
                        </tr>
                        @endif
                        <tr>
                            <th>RETAINED</th>
                            @foreach($eachchunk as $eachgradelevel)
                            <td class="text-center">{{$eachgradelevel->retainedmale}}</td>
                            <td class="text-center">{{$eachgradelevel->retainedfemale}}</td>
                            <td class="text-center">{{$eachgradelevel->retained}}</th>
                            @endforeach
                            <th class="text-center">{{collect($eachchunk)->sum('retainedmale')}}</th>
                            <th class="text-center">{{collect($eachchunk)->sum('retainedfemale')}}</th>
                            <th class="text-center">{{collect($eachchunk)->sum('retained')}}</th>
                        </tr>
                        @if($acadprogid == 5)
                        <tr>
                            <th>LEVEL OF POFICIENCY (K to 12 Only)	</th>
                            @foreach($eachchunk as $eachgradelevel)
                            <th class="text-center" style="vertical-align: middle;font-size: 9px;">MALE</th>
                            <th class="text-center" style="vertical-align: middle;font-size: 9px;">FEMALE</th>
                            <th class="text-center" style="vertical-align: middle;font-size: 9px;">TOTAL</th>
                            @endforeach
                            <th class="text-center" style="vertical-align: middle;font-size: 9px;">MALE</th>
                            <th class="text-center" style="vertical-align: middle;font-size: 9px;">FEMALE</th>
                            <th class="text-center" style="vertical-align: middle;font-size: 9px;">TOTAL</th>
                        </tr>
                        @endif
                        <tr>
                            <th>BEGINNING (B: 74% and below)	</th>
                            @foreach($eachchunk as $eachgradelevel)
                            <td class="text-center">{{$eachgradelevel->proficiencybmale}}</td>
                            <td class="text-center">{{$eachgradelevel->proficiencybfemale}}</td>
                            <td class="text-center">{{$eachgradelevel->proficiencyb}}</th>
                            @endforeach
                            <th class="text-center">{{collect($eachchunk)->sum('proficiencybmale')}}</th>
                            <th class="text-center">{{collect($eachchunk)->sum('proficiencybfemale')}}</th>
                            <th class="text-center">{{collect($eachchunk)->sum('proficiencyb')}}</th>
                        </tr>
                        <tr>
                            <th>DEVELOPING (D: 75%-79%)	</th>
                            @foreach($eachchunk as $eachgradelevel)
                            <td class="text-center">{{$eachgradelevel->proficiencydmale}}</td>
                            <td class="text-center">{{$eachgradelevel->proficiencydfemale}}</td>
                            <td class="text-center">{{$eachgradelevel->proficiencyd}}</th>
                            @endforeach
                            <th class="text-center">{{collect($eachchunk)->sum('proficiencydmale')}}</th>
                            <th class="text-center">{{collect($eachchunk)->sum('proficiencydfemale')}}</th>
                            <th class="text-center">{{collect($eachchunk)->sum('proficiencyd')}}</th>
                        </tr>
                        <tr>
                            <th>APPROACHING PROFICIENCY (AP: 80%-84%)	</th>
                            @foreach($eachchunk as $eachgradelevel)
                            <td class="text-center">{{$eachgradelevel->proficiencyapmale}}</td>
                            <td class="text-center">{{$eachgradelevel->proficiencyapfemale}}</td>
                            <td class="text-center">{{$eachgradelevel->proficiencyap}}</th>
                            @endforeach
                            <th class="text-center">{{collect($eachchunk)->sum('proficiencyapmale')}}</th>
                            <th class="text-center">{{collect($eachchunk)->sum('proficiencyapfemale')}}</th>
                            <th class="text-center">{{collect($eachchunk)->sum('proficiencyap')}}</th>
                        </tr>
                        <tr>
                            <th>PROFICIENT (P: 85%-89%)	</th>
                            @foreach($eachchunk as $eachgradelevel)
                            <td class="text-center">{{$eachgradelevel->proficiencypmale}}</td>
                            <td class="text-center">{{$eachgradelevel->proficiencypfemale}}</td>
                            <td class="text-center">{{$eachgradelevel->proficiencyp}}</th>
                            @endforeach
                            <th class="text-center">{{collect($eachchunk)->sum('proficiencypmale')}}</th>
                            <th class="text-center">{{collect($eachchunk)->sum('proficiencypfemale')}}</th>
                            <th class="text-center">{{collect($eachchunk)->sum('proficiencyp')}}</th>
                        </tr>
                        <tr>
                            <th>ADVANCED (A: 90% and above)	</th>
                            @foreach($eachchunk as $eachgradelevel)
                            <td class="text-center">{{$eachgradelevel->proficiencyamale}}</td>
                            <td class="text-center">{{$eachgradelevel->proficiencyafemale}}</td>
                            <td class="text-center">{{$eachgradelevel->proficiencya}}</th>
                            @endforeach
                            <th class="text-center">{{collect($eachchunk)->sum('proficiencyamale')}}</th>
                            <th class="text-center">{{collect($eachchunk)->sum('proficiencyafemale')}}</th>
                            <th class="text-center">{{collect($eachchunk)->sum('proficiencya')}}</th>
                        </tr>
                        <tr>
                            <th>TOTAL	</th>
                            @foreach($eachchunk as $eachgradelevel)
                            <th class="text-center">{{$eachgradelevel->irregularmale+$eachgradelevel->retainedmale+$eachgradelevel->proficiencybmale+$eachgradelevel->proficiencydmale+$eachgradelevel->proficiencyapmale+$eachgradelevel->proficiencypmale+$eachgradelevel->proficiencyamale}}
                            </th>
                            <th class="text-center">{{$eachgradelevel->irregularfemale+$eachgradelevel->retainedfemale+$eachgradelevel->proficiencybfemale+$eachgradelevel->proficiencydfemale+$eachgradelevel->proficiencyapfemale+$eachgradelevel->proficiencypfemale+$eachgradelevel->proficiencyafemale}}</th>
                            <th class="text-center">{{$eachgradelevel->irregular+$eachgradelevel->retained+$eachgradelevel->proficiencyb+$eachgradelevel->proficiencyd+$eachgradelevel->proficiencyap+$eachgradelevel->proficiencyp+$eachgradelevel->proficiencya}}</th>
                            @endforeach
                            <th class="text-center">{{collect($eachchunk)->sum('irregularmale')+collect($eachchunk)->sum('retainedmale')+collect($eachchunk)->sum('proficiencybmale')+collect($eachchunk)->sum('proficiencydmale')+collect($eachchunk)->sum('proficiencyapmale')+collect($eachchunk)->sum('proficiencypmale')+collect($eachchunk)->sum('proficiencyamale')}}</th>
                            <th class="text-center">{{collect($eachchunk)->sum('irregularfemale')+collect($eachchunk)->sum('retainedfemale')+collect($eachchunk)->sum('proficiencybfemale')+collect($eachchunk)->sum('proficiencydfemale')+collect($eachchunk)->sum('proficiencyapfemale')+collect($eachchunk)->sum('proficiencypfemale')+collect($eachchunk)->sum('proficiencyafemale')}}</th>
                            <th class="text-center">{{collect($eachchunk)->sum('irregular')+collect($eachchunk)->sum('retained')+collect($eachchunk)->sum('proficiencyb')+collect($eachchunk)->sum('proficiencyd')+collect($eachchunk)->sum('proficiencyap')+collect($eachchunk)->sum('proficiencyp')+collect($eachchunk)->sum('proficiencya')}}</th>
                        </tr>
                        
                    </tbody>
                </table>
                @if(isset($gradelevels[$key+1]))
                <div style="page-break-after: always;">&nbsp;<br/></div>
                @endif
            @endforeach
        @else
            <table style="font-size: 10px; width: 100%;" border="1">
                <thead class="text-center">
                    <tr>
                        <th rowspan="2" style="width: 15%;">Summary Table</th>
                        @foreach($gradelevels as $eachgradelevel)
                        <th colspan="3">{{$eachgradelevel->levelname}}</th>
                        @endforeach
                        <th colspan="3">Total</th>
                    </tr>
                    <tr>
                        @foreach($gradelevels as $eachgradelevel)
                        <th style="font-size: 8px;">MALE</th>
                        <th style="font-size: 8px;">FEMALE</th>
                        <th style="font-size: 8px;">TOTAL</th>
                        @endforeach
                        <th style="font-size: 8px;">MALE</th>
                        <th style="font-size: 8px;">FEMALE</th>
                        <th style="font-size: 8px;">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>PROMOTED</th>
                        @foreach($gradelevels as $eachgradelevel)
                        <td class="text-center">{{$eachgradelevel->promotedmale}}</td>
                        <td class="text-center">{{$eachgradelevel->promotedfemale}}</td>
                        <td class="text-center">{{$eachgradelevel->promoted}}</th>
                        @endforeach
                        <th class="text-center">{{collect($gradelevels)->sum('promotedmale')}}</th>
                        <th class="text-center">{{collect($gradelevels)->sum('promotedfemale')}}</th>
                        <th class="text-center">{{collect($gradelevels)->sum('promoted')}}</th>
                    </tr>
                    @if($acadprogid != 3)
                    <tr>
                        <th>IRREGULAR (Grade 7 onwards only)	</th>
                        @foreach($gradelevels as $eachgradelevel)
                        <td class="text-center">{{$eachgradelevel->irregularmale}}</td>
                        <td class="text-center">{{$eachgradelevel->irregularfemale}}</td>
                        <td class="text-center">{{$eachgradelevel->irregular}}</th>
                        @endforeach
                        <th class="text-center">{{collect($gradelevels)->sum('irregularmale')}}</th>
                        <th class="text-center">{{collect($gradelevels)->sum('irregularfemale')}}</th>
                        <th class="text-center">{{collect($gradelevels)->sum('irregular')}}</th>
                    </tr>
                    @endif
                    <tr>
                        <th>RETAINED</th>
                        @foreach($gradelevels as $eachgradelevel)
                        <td class="text-center">{{$eachgradelevel->retainedmale}}</td>
                        <td class="text-center">{{$eachgradelevel->retainedfemale}}</td>
                        <td class="text-center">{{$eachgradelevel->retained}}</th>
                        @endforeach
                        <th class="text-center">{{collect($gradelevels)->sum('retainedmale')}}</th>
                        <th class="text-center">{{collect($gradelevels)->sum('retainedfemale')}}</th>
                        <th class="text-center">{{collect($gradelevels)->sum('retained')}}</th>
                    </tr>
                    @if($acadprogid == 5)
                    <tr>
                        <th>LEVEL OF POFICIENCY (K to 12 Only)	</th>
                        @foreach($gradelevels as $eachgradelevel)
                        <th class="text-center">MALE</th>
                        <th class="text-center">FEMALE</th>
                        <th class="text-center">TOTAL</th>
                        @endforeach
                        <th class="text-center">MALE</th>
                        <th class="text-center">FEMALE</th>
                        <th class="text-center">TOTAL</th>
                    </tr>
                    @endif
                    <tr>
                        <th>BEGINNING (B: 74% and below)	</th>
                        @foreach($gradelevels as $eachgradelevel)
                        <td class="text-center">{{$eachgradelevel->proficiencybmale}}</td>
                        <td class="text-center">{{$eachgradelevel->proficiencybfemale}}</td>
                        <td class="text-center">{{$eachgradelevel->proficiencyb}}</th>
                        @endforeach
                        <th class="text-center">{{collect($gradelevels)->sum('proficiencybmale')}}</th>
                        <th class="text-center">{{collect($gradelevels)->sum('proficiencybfemale')}}</th>
                        <th class="text-center">{{collect($gradelevels)->sum('proficiencyb')}}</th>
                    </tr>
                    <tr>
                        <th>DEVELOPING (D: 75%-79%)	</th>
                        @foreach($gradelevels as $eachgradelevel)
                        <td class="text-center">{{$eachgradelevel->proficiencydmale}}</td>
                        <td class="text-center">{{$eachgradelevel->proficiencydfemale}}</td>
                        <td class="text-center">{{$eachgradelevel->proficiencyd}}</th>
                        @endforeach
                        <th class="text-center">{{collect($gradelevels)->sum('proficiencydmale')}}</th>
                        <th class="text-center">{{collect($gradelevels)->sum('proficiencydfemale')}}</th>
                        <th class="text-center">{{collect($gradelevels)->sum('proficiencyd')}}</th>
                    </tr>
                    <tr>
                        <th>APPROACHING PROFICIENCY (AP: 80%-84%)	</th>
                        @foreach($gradelevels as $eachgradelevel)
                        <td class="text-center">{{$eachgradelevel->proficiencyapmale}}</td>
                        <td class="text-center">{{$eachgradelevel->proficiencyapfemale}}</td>
                        <td class="text-center">{{$eachgradelevel->proficiencyap}}</th>
                        @endforeach
                        <th class="text-center">{{collect($gradelevels)->sum('proficiencyapmale')}}</th>
                        <th class="text-center">{{collect($gradelevels)->sum('proficiencyapfemale')}}</th>
                        <th class="text-center">{{collect($gradelevels)->sum('proficiencyap')}}</th>
                    </tr>
                    <tr>
                        <th>PROFICIENT (P: 85%-89%)	</th>
                        @foreach($gradelevels as $eachgradelevel)
                        <td class="text-center">{{$eachgradelevel->proficiencypmale}}</td>
                        <td class="text-center">{{$eachgradelevel->proficiencypfemale}}</td>
                        <td class="text-center">{{$eachgradelevel->proficiencyp}}</th>
                        @endforeach
                        <th class="text-center">{{collect($gradelevels)->sum('proficiencypmale')}}</th>
                        <th class="text-center">{{collect($gradelevels)->sum('proficiencypfemale')}}</th>
                        <th class="text-center">{{collect($gradelevels)->sum('proficiencyp')}}</th>
                    </tr>
                    <tr>
                        <th>ADVANCED (A: 90% and above)	</th>
                        @foreach($gradelevels as $eachgradelevel)
                        <td class="text-center">{{$eachgradelevel->proficiencyamale}}</td>
                        <td class="text-center">{{$eachgradelevel->proficiencyafemale}}</td>
                        <td class="text-center">{{$eachgradelevel->proficiencya}}</th>
                        @endforeach
                        <th class="text-center">{{collect($gradelevels)->sum('proficiencyamale')}}</th>
                        <th class="text-center">{{collect($gradelevels)->sum('proficiencyafemale')}}</th>
                        <th class="text-center">{{collect($gradelevels)->sum('proficiencya')}}</th>
                    </tr>
                    <tr>
                        <th>TOTAL	</th>
                        @foreach($gradelevels as $eachgradelevel)
                        <th class="text-center">{{$eachgradelevel->promotedmale+$eachgradelevel->irregularmale+$eachgradelevel->retainedmale+$eachgradelevel->proficiencybmale+$eachgradelevel->proficiencydmale+$eachgradelevel->proficiencyapmale+$eachgradelevel->proficiencypmale+$eachgradelevel->proficiencyamale}}
                        </th>
                        <th class="text-center">{{$eachgradelevel->promotedfemale+$eachgradelevel->irregularfemale+$eachgradelevel->retainedfemale+$eachgradelevel->proficiencybfemale+$eachgradelevel->proficiencydfemale+$eachgradelevel->proficiencyapfemale+$eachgradelevel->proficiencypfemale+$eachgradelevel->proficiencyafemale}}</th>
                        <th class="text-center">{{$eachgradelevel->promoted+$eachgradelevel->irregular+$eachgradelevel->retained+$eachgradelevel->proficiencyb+$eachgradelevel->proficiencyd+$eachgradelevel->proficiencyap+$eachgradelevel->proficiencyp+$eachgradelevel->proficiencya}}</th>
                        @endforeach
                        <th class="text-center">{{collect($gradelevels)->sum('promotedmale')+collect($gradelevels)->sum('irregularmale')+collect($gradelevels)->sum('retainedmale')+collect($gradelevels)->sum('proficiencybmale')+collect($gradelevels)->sum('proficiencydmale')+collect($gradelevels)->sum('proficiencyapmale')+collect($gradelevels)->sum('proficiencypmale')+collect($gradelevels)->sum('proficiencyamale')}}</th>
                        <th class="text-center">{{collect($gradelevels)->sum('promotedfemale')+collect($gradelevels)->sum('irregularfemale')+collect($gradelevels)->sum('retainedfemale')+collect($gradelevels)->sum('proficiencybfemale')+collect($gradelevels)->sum('proficiencydfemale')+collect($gradelevels)->sum('proficiencyapfemale')+collect($gradelevels)->sum('proficiencypfemale')+collect($gradelevels)->sum('proficiencyafemale')}}</th>
                        <th class="text-center">{{collect($gradelevels)->sum('promoted')+collect($gradelevels)->sum('irregular')+collect($gradelevels)->sum('retained')+collect($gradelevels)->sum('proficiencyb')+collect($gradelevels)->sum('proficiencyd')+collect($gradelevels)->sum('proficiencyap')+collect($gradelevels)->sum('proficiencyp')+collect($gradelevels)->sum('proficiencya')}}</th>
                    </tr>
                </tbody>
            </table>
        @endif
    </main>
</body>
</html>