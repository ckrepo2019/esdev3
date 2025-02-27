
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

        <title>Document</title>

        <style>
            * { font-family: Arial, Helvetica, sans-serif; }
            table{
                border-collapse: collapse;
            }
            td, th{
                padding: 1px;
            }
            @page{
                margin: 285px 50px 130px 50px;
            }
    header { position: fixed; top: -245px; left: 0px; right: 0px; height: 250px; }
    footer { position: fixed; bottom: -90px; left: 0px; right: 0px; height: 100px; 
        /* border-top: 1px solid black; */
    }
        </style>
    </head>
    <body>
        <script type="text/php">
            if (isset($pdf)) {
                $x = 40;
                $y = 750;
                $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
                $font = null;
                $size = 7;
                $color = array(0,0,0);
                $word_space = 0.0;  //  default
                $char_space = 0.0;  //  default
                $angle = 0.0;   //  default
                $pdf->page_text($x, $y, $text, $font, $size, $color);
            }
        </script>
        @php
            $num_firstrow = 59;
            $done_first = 0;
        @endphp
    {{-- @php
    if (isset($pdf)) {
        $x = 100;
        $y = 500;
        $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
        $font = null;
        $size = 7;
        $color = array(0,0,0);
        $word_space = 0.0;  //  default
        $char_space = 0.0;  //  default
        $angle = 0.0;   //  default
        $pdf->page_text($x, $y, $text, $font, $size, $color);
    }
    @endphp --}}
        <header>
            <table style="width: 97%; table-layout: fixed;">
                <tr>
                    <td rowspan="6" style="width: 22.5%; text-align: right;">
                        <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="90px">
                    </td>
                    <td style="width: 55%; font-weight: bold; text-align: center; font-size: 15px;">
                        {{DB::table('schoolinfo')->first()->schoolname}}
                    </td>
                    <td rowspan="6" style="border: 1px solid black; text-align: center; padding: 0px !important; vertical-align: top;">
                    
                        @if (file_exists(base_path().'/public/'.$studinfo->picurl) && $studinfo->picurl)
                            <img src="{{base_path()}}/public/{{$studinfo->picurl}}" width="150px" style="margin: 0px;">
                        @else
                            @php
                            
                                if(strtoupper($studinfo->gender) == 'FEMALE'){
                                    $avatar = 'avatar/S(F) 1.png';
                                }
                                else{
                                    $avatar = 'avatar/S(M) 1.png';
                                }
                            @endphp
                            {{-- {{base_path()}}/public/{{$avatar}} --}}
                            <img src="{{base_path()}}/public/{{$avatar}}" alt="student" width="150px" style="margin: 0px;">
                        @endif
                        <div style="font-size: 9px; margin: 0px; padding: 0px;">{{$studinfo->lastname}}, {{$studinfo->firstname}} {{$studinfo->suffix}} {{isset($studinfo->middlename[0]) ? $studinfo->middlename[0].'.' : ''}}</div>
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 12px; text-align: center; font-weight: bold;">{{DB::table('schoolinfo')->first()->address}}</td>
                </tr>
                <tr>
                    <td style="font-size: 11px; text-align: center; padding-top: 5px;">Tel. No. (064) 572-4020</td>
                </tr>
                <tr>
                    <td style="font-size: 13px; text-align: center; padding-top: 0px;">OFFICE OF THE REGISTRAR</td>
                </tr>
                <tr>
                    <td style="font-size: 13px; text-align: center;">&nbsp;</td>
                </tr>
                <tr style="font-weight: bold; text-align: center; font-size: 15px; font-weight: bold;">
                    <td>STUDENT ACADEMIC RECORD</td>
                </tr>
                <tr style="font-weight: bold; text-align: center; font-size: 15px; font-weight: bold;">
                    <td colspan="3">W/ WEIGHTED AVERAGE COMPUTATION</td>
                </tr>    
                <tr>
                    <td colspan="3">&nbsp;</td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 12px; table-layout: fixed; margin-top: 10px;">
                <tr>
                    <td style="width: 50%;">Name: {{$studinfo->lastname}}, {{$studinfo->firstname}} {{$studinfo->suffix}} {{isset($studinfo->middlename[0]) ? $studinfo->middlename[0].'.' : ''}}</td>
                    <td style="width: 35%;">Course: {{collect($records)->reverse()->first()->coursecode ?? collect($records)->first()->coursecode ?? null}}</td>
                    <td>Date:</td>
                </tr>
            </table>
        </header>
        <footer>
            <br/>
            <br/>
            <table style="width: 100%; font-size: 11px;">
                <tr>
                    <td></td>
                    <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">{{auth()->user()->name}}</td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align: center;">Registrar</td>
                </tr>
            </table>
        </footer>
        <main>
        <table style="width: 100%; font-size: 11px;" border="1">
            <thead>
                <tr>
                    <th style="vertical-align: middle;">SUBJECT CODE</th>
                    {{-- <th style="vertical-align: bottom;">NO.</th> --}}
                    <th style="width: 50%; vertical-align: middle;" colspan="2">DESCRIPTIVE TITLE</th>
                    <th style="vertical-align: middle;">GRADE</th>
                    <th style="vertical-align: middle;">HPA EQUIV</th>
                    <th style="vertical-align: middle;">CREDIT</th>
                    <th style="vertical-align: middle;">HPA/<br/>WEIGHTED<br/>AVERAGE</th>
                </tr>
            </thead>
            @if(count($records)>0)
                @php
                    $initschoolname = null;   
                @endphp
                @foreach($records as $recordkey=>$record)
                    @php
                        $record->totalunits = collect($record->subjdata)->sum('subjcredit');
                        $record->totalhpagrade = 0;
                        $num_firstrow -=2;
                    @endphp
                    @if($recordkey == 0)
                    @php
                        $initschoolname = $record->schoolname;
                    @endphp
                    <tr>
                        <td style="border-bottom: none !important;"></td>
                        {{-- <td style="border-bottom: none !important;"></td> --}}
                        <td colspan="2" style="border-bottom: none !important; text-align: center; font-weight: bold;">{{$record->schoolname}} - {{$record->schooladdress}}</td>
                        <td style="border-bottom: none !important;"></td>
                        <td style="border-bottom: none !important;"></td>
                        <td style="border-bottom: none !important;"></td>
                        <td style="border-bottom: none !important;"></td>
                    </tr>
                    @else
                        @if($initschoolname != $record->schoolname)
                            @php
                                $initschoolname = $record->schoolname;
                            @endphp
                            <tr>
                                <td style="border-bottom: none !important; border-top: none !important;"></td>
                                {{-- <td style="border-bottom: none !important; border-top: none !important;"></td> --}}
                                <td colspan="2" style="border-bottom: none !important; border-top: none !important; text-align: center; font-weight: bold;">{{$initschoolname}} - {{$record->schooladdress}}</td>
                                <td style="border-bottom: none !important; border-top: none !important;"></td>
                                <td style="border-bottom: none !important; border-top: none !important;"></td>
                                <td style="border-bottom: none !important; border-top: none !important;"></td>
                                <td style="border-bottom: none !important; border-top: none !important;"></td>
                            </tr>
                        @else
                            <tr>
                                <td style="border-bottom: none !important; border-top: none !important;"></td>
                                {{-- <td style="border-bottom: none !important; border-top: none !important;"></td> --}}
                                <td colspan="2" style="border-bottom: none !important; border-top: none !important; text-align: center; font-weight: bold;"></td>
                                <td style="border-bottom: none !important; border-top: none !important;"></td>
                                <td style="border-bottom: none !important; border-top: none !important;"></td>
                                <td style="border-bottom: none !important; border-top: none !important;"></td>
                                <td style="border-bottom: none !important; border-top: none !important;"></td>
                            </tr>
                        @endif
                    @endif
                    <tr>
                        <td style="border-bottom: none !important; border-top: none !important;"></td>
                        {{-- <td style="border-bottom: none !important; border-top: none !important;"></td> --}}
                        <td colspan="2" style="border-bottom: none !important; border-top: none !important; text-align: center;"><u>@if($record->semid == 1) 1ST SEMESTER @elseif($record->semid == 2) 2ND SEMESTER @else SUMMER @endif - {{$record->sydesc}}</u></td>
                        <td style="border-bottom: none !important; border-top: none !important;"></td>
                        <td style="border-bottom: none !important; border-top: none !important;"></td>
                        <td style="border-bottom: none !important; border-top: none !important;"></td>
                        <td style="border-bottom: none !important; border-top: none !important;"></td>
                    </tr>
                    @if(count($record->subjdata)>0)
                        @foreach($record->subjdata as $eachsubject)
                            @php
                                $hpaequiv = 0;
                            @endphp
                            <tr>
                                {{-- <td style="border-bottom: none !important; border-top: none !important; text-align: left;">{{preg_replace("/[^a-zA-Z]+/", "", $eachsubject->subjcode)}}</td> --}}
                                <td style="border-bottom: none !important; border-top: none !important; text-align: left;">
                                    {{ preg_replace("/[^a-zA-Z0-9]/", "", $eachsubject->subjcode) }}
                                </td>
                                {{-- <td style="border-bottom: none !important; border-top: none !important; text-align: center;">{{filter_var($eachsubject->subjcode, FILTER_SANITIZE_NUMBER_INT)}}</td> --}}
                                <td colspan="2" style="border-bottom: none !important; border-top: none !important; padding: 0px 4px;">{{$eachsubject->subjdesc}}</td>
                                <td style="border-bottom: none !important; border-top: none !important; text-align: center;">{{$eachsubject->subjgrade}}</td>
                                <td style="border-bottom: none !important; border-top: none !important; text-align: center;">@if(count($transmutations) > 0) {{collect($transmutations)->where('hpaeqto','<=',$eachsubject->subjgrade)->where('hpaeq','>=',$eachsubject->subjgrade)->first()->honorpointeq ?? null}} 
                                    @php
                                    $hpaequiv =collect($transmutations)->where('hpaeqto','<=',$eachsubject->subjgrade)->where('hpaeq','>=',$eachsubject->subjgrade)->first()->honorpointeq ?? null;
                                    @endphp    
                                @endif</td>
                                <td style="border-bottom: none !important; border-top: none !important; text-align: center;">{{$eachsubject->subjcredit}}</td>
                                <td style="border-bottom: none !important; border-top: none !important; text-align: center;">{{$hpaequiv > 0 ? $hpaequiv * $eachsubject->subjcredit : null}}</td>
                            </tr>
                            @php
                                $record->totalhpagrade+=($hpaequiv*$eachsubject->subjcredit);
                                $eachsubject->display = 1;
                                // $done_first+= 1;
                                // if($num_firstrow == 0)
                                // {
                                //     break;
                                // }else{
                                //     $num_firstrow -=1;
                                // }
                            @endphp
                        @endforeach
                    @endif
                    @php
                        // if(collect($record->subjdata)->where('display','1')->count() == count($record->subjdata))
                        // {
                        //     $record->display = 1;
                        // }
                        // if($num_firstrow == 0)
                        // {
                        //     break;
                        // }
                    @endphp
                @endforeach
            @endif
            {{-- @if(collect($records)->where('display','0')->count() == 0) --}}
                <tr>
                    <td style="border-bottom: none; border-right: none;"></td>
                    {{-- <td style="border-bottom: none; border-right: none; border-left: none;"></td> --}}
                    <th style="text-align: center; border-bottom: none; border-left: none; font-size: 9px; vertical-align: top;" colspan="2">x-x-x-x Closed x-x-x-x</th>
                    <th style="padding: 3px;">TOTAL</th>
                    <th></th>
                    <th>{{collect($records)->sum('totalunits')}}</th>
                    <th>{{collect($records)->sum('totalhpagrade')}}</th>
                </tr>
                
                @php
                $gwa = null;
                $backgroundcolor = 'background-color: #99d6ff;';
                if(collect($records)->sum('totalhpagrade') > 0 && collect($records)->sum('totalunits')> 0)
                {
                $gwa = number_format(collect($records)->sum('totalhpagrade')/collect($records)->sum('totalunits'),2);
                }
                @endphp
                <tr>
                    <td style="border-top: none; border-right: none; padding-left: 20px; vertical-align: top;" colspan="2" rowspan="9">
                        <table style="width: 90%; font-size: 9px;" border="1">
                            <tr>
                                <th colspan="3">Grading System</th>
                            </tr>
                            <tr>
                                <th>Numerical Equivalent</th>
                                <th>Percentage Equivalent</th>
                                <th>Honor/Grade<br/>Point</th>
                            </tr>
                            <tr>
                                <td style="text-align: center;{{($gwa <= 5) && ($gwa >= 4.5) ? $backgroundcolor : ''}}">1.00</td>
                                <td style="text-align: center;{{($gwa <= 5) && ($gwa >= 4.5) ? $backgroundcolor : ''}}">98-100</td>
                                <td style="text-align: center;{{($gwa <= 5) && ($gwa >= 4.5) ? $backgroundcolor : ''}}">5.00</td>
                            </tr>
                            <tr>
                                <td style="text-align: center;{{($gwa <= 4.5) && ($gwa >= 4) ? $backgroundcolor : ''}}">1.25</td>
                                <td style="text-align: center;{{($gwa <= 4.5) && ($gwa >= 4) ? $backgroundcolor : ''}}">95-97</td>
                                <td style="text-align: center;{{($gwa <= 4.5) && ($gwa >= 4) ? $backgroundcolor : ''}}">4.50</td>
                            </tr>
                            <tr>
                                <td style="text-align: center;{{($gwa <= 4) && ($gwa >= 3.5) ? $backgroundcolor : ''}}">1.50</td>
                                <td style="text-align: center;{{($gwa <= 4) && ($gwa >= 3.5) ? $backgroundcolor : ''}}">92-94</td>
                                <td style="text-align: center;{{($gwa <= 4) && ($gwa >= 3.5) ? $backgroundcolor : ''}}">4.00</td>
                            </tr>
                            <tr>
                                <td style="text-align: center;{{($gwa <= 3.5) && ($gwa >= 3) ? $backgroundcolor : ''}}">1.75</td>
                                <td style="text-align: center;{{($gwa <= 3.5) && ($gwa >= 3) ? $backgroundcolor : ''}}">89-91</td>
                                <td style="text-align: center;{{($gwa <= 3.5) && ($gwa >= 3) ? $backgroundcolor : ''}}">3.50</td>
                            </tr>
                            <tr>
                                <td style="text-align: center;{{($gwa <= 3) && ($gwa >= 2.5) ? $backgroundcolor : ''}} ">2.00</td>
                                <td style="text-align: center;{{($gwa <= 3) && ($gwa >= 2.5) ? $backgroundcolor : ''}}">86-88</td>
                                <td style="text-align: center;{{($gwa <= 3) && ($gwa >= 2.5) ? $backgroundcolor : ''}}">3.00</td>
                            </tr>
                            <tr>
                                <td style="text-align: center;{{($gwa <= 2.5) && ($gwa >= 2) ? $backgroundcolor : ''}}">2.25</td>
                                <td style="text-align: center;{{($gwa <= 2.5) && ($gwa >= 2) ? $backgroundcolor : ''}}">83-85</td>
                                <td style="text-align: center;{{($gwa <= 2.5) && ($gwa >= 2) ? $backgroundcolor : ''}}">2.50</td>
                            </tr>
                            <tr>
                                <td style="text-align: center;{{($gwa <= 2) && ($gwa >= 1.5) ? $backgroundcolor : ''}}">2.50</td>
                                <td style="text-align: center;{{($gwa <= 2) && ($gwa >= 1.5) ? $backgroundcolor : ''}}">80-82</td>
                                <td style="text-align: center;{{($gwa <= 2) && ($gwa >= 1.5) ? $backgroundcolor : ''}}">2.00</td>
                            </tr>
                            <tr>
                                <td style="text-align: center;{{($gwa <= 1.5) && ($gwa >= 1) ? $backgroundcolor : ''}}">2.75</td>
                                <td style="text-align: center;{{($gwa <= 1.5) && ($gwa >= 1) ? $backgroundcolor : ''}}">77-79</td>
                                <td style="text-align: center;{{($gwa <= 1.5) && ($gwa >= 1) ? $backgroundcolor : ''}}">1.50</td>
                            </tr>
                            <tr>
                                <td style="text-align: center;{{($gwa <= 1) && ($gwa >= 0) ? $backgroundcolor : ''}}">3.00</td>
                                <td style="text-align: center;{{($gwa <= 1) && ($gwa >= 0) ? $backgroundcolor : ''}}">75-76</td>
                                <td style="text-align: center;{{($gwa <= 1) && ($gwa >= 0) ? $backgroundcolor : ''}}">1.00</td>
                            </tr>
                            <tr>
                                <td style="text-align: center;">5.00</td>
                                <td style="text-align: center;">Below 75</td>
                                <td style="text-align: center;">Failure</td>
                            </tr>
                            <tr>
                                <td style="text-align: center;">W/Dr</td>
                                <td style="text-align: center;">Withdrawn/D</td>
                                <td style="text-align: center;"></td>
                            </tr>
                            <tr>
                                <td style="text-align: center;">NG</td>
                                <td style="text-align: center;">No Grade</td>
                                <td style="text-align: center;"></td>
                            </tr>
                        </table>
                    </td>
                    <td colspan="2" style="border-top: none; border-bottom: none; border-right: none; border-left: none; text-align: right; padding: 0px !important;">
                        Grade Weighted Average =
                    </td>
                    <td colspan="3" style="border-top: none; border-left: none; border-bottom: 1px solid black; text-align: center;">
                        Total HPA / Weighted Ave
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="border-top: none; border-bottom: none; border-right: none; border-left: none; text-align: right;">
                        &nbsp;
                    </td>
                    <td colspan="3" style="border-top: none; border-bottom: none; border-left: none; text-align: center;">
                        Total Units
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="border-top: none; border-bottom: none; border-right: none; border-left: none; text-align: right;">
                        =
                    </td>
                    <td colspan="3" style="border-top: none; border-bottom: 1px solid black;;  border-left: none; text-align: center; font-weight: bold;">
                        {{$gwa}}
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="border-top: none; border-bottom: none; border-right: none; border-left: none; text-align: right; ">
                        
                    </td>
                    <td colspan="3" style="border-top: none;border-bottom: none;  border-left: none; text-align: center;">
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="border-top: none;border-bottom: none;  border-right: none; border-left: none; text-align: right; ">
                        
                    </td>
                    <td colspan="3" style="border-top: none;border-bottom: none;  border-left: none; text-align: center;">
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="border-top: none;border-bottom: none;  border-right: none; border-left: none; text-align: right; ">
                        
                    </td>
                    <td colspan="3" style="border-top: none;border-bottom: none;  border-left: none; text-align: center;">
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="border-top: none;border-bottom: none;  border-right: none; border-left: none; text-align: right; ">
                        
                    </td>
                    <td colspan="3" style="border-top: none;border-bottom: none;  border-left: none; text-align: center;">
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="border-top: none;border-bottom: none;  border-right: none; border-left: none; text-align: right; ">
                        
                    </td>
                    <td colspan="3" style="border-top: none;border-bottom: none;  border-left: none; text-align: center;">
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="border-top: none; border-right: none; border-left: none; text-align: right;">
                        
                    </td>
                    <td colspan="3" style="border-top: none; border-left: none; text-align: center;">
                    </td>
                </tr>
            {{-- @else
            @endif --}}
            </table>
        </main>
        {{-- <div style="width: 100%; margin-top: 10px; font-size: 14px; padding-left: 20px; border-bottom: 1px dashed black; line-height: 10px; font-weight: bold;">
            {{$studinfo->sid}} {{$studinfo->lastname}}, {{$studinfo->firstname}} {{$studinfo->middlename}}<br/>&nbsp;
        </div> --}}
        {{-- <table style="width: 100%; font-size: 12px; table-layout: fixed;">
            <thead>
                <tr>
                    <th colspan="2" style="width: 20%; border-bottom: 1px dashed black;">Subject Code</th>
                    <th style="width: 45%; border-bottom: 1px dashed black;">Description</th>
                    <th style="border-bottom: 1px dashed black;">Grade</th>
                    <th style="border-bottom: 1px dashed black;">Units</th>
                    <th style="width: 10%; border-bottom: 1px dashed black;">Remarks</th>
                    <td style="border-bottom: 1px dashed black;"></td>
                </tr>
            </thead>
            @foreach($records as $record)
                @if(count($record->subjects)>0)
                <tr>
                    <th colspan="2">{{DB::table('semester')->where('id', $record->semid)->first()->semester}} {{DB::table('sy')->where('id', $record->syid)->first()->sydesc}}</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @foreach($record->subjects as $eachsubject)
                    <tr>
                        <td>&nbsp;</td>
                        <td style="width: 15% !important; vertical-align: top;">{{$eachsubject->subjectcode}}</td>
                        <td style="vertical-align: top;">{{ucwords(strtolower($eachsubject->subjectname))}}</td>
                        <td style="text-align: center; vertical-align: top;">{{$eachsubject->eqgrade}}</td>
                        <td style="text-align: center; vertical-align: top;">{{$eachsubject->units}}</td>
                        <td style="text-align: center; vertical-align: top;">@if($eachsubject->eqgrade != null) {{$eachsubject->eqgrade >= 5.0 ? 'FAILED' : 'PASSED'}} @endif</td>
                        <td></td>
                    </tr>
                @endforeach
                <tr>
                    <th colspan="2">&nbsp;</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @endif
            @endforeach
        </table> --}}
        {{-- <br/>
        <br/>
        <table style="width: 100%; font-size: 12px;">
            <tr>
                <td></td>
                <td style="width: 30%; font-weight: bold; text-align: center;">&nbsp;{{$schoolregistrar}}&nbsp;</td>
            </tr>
            <tr>
                <td></td>
                <td style="text-align: center;">Registrar(Consultant)</td>
            </tr>
        </table>
        <br/>
        <br/>
        <table style="width: 100%; font-size: 10.5px;">
            <tr>
                <td style="width: 25%; vertical-align: top;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;GRADING SYSTEM USED:</td>
                <td>1.0 - 100 1.1 - 99 1.2 - 98 1.3 - 97 1.4 - 96 1.5 - 95 1.6 - 94 1.7 - 93 1.8 - 92 1.9 - 91 2.0 - 90 2.1 - 89 2.2 - 88 2.3 - 87 2.4 - 86 2.5 - 85 2.6 - 84 2.7 - 83 2.8 - 82 2.9 - 81 3.0 - 80 3.1 - 79 3.2 - 78  3.3 - 77 3.4 - 76 3.5 - 75 5.0 - Failed 9.0 - Dropped</td>
            </tr>
        </table> --}}
    </body>
</html>