<html>
    <header>
        <style>
            * { font-family: Arial, Helvetica, sans-serif; }
            @page{
                margin: 1.8in 0.5in 0.8in 0.5in;
                size: 13in 8.5in;
            }
            td, th{
                padding: 0px 2px;
            }
            table{
                border-collapse: collapse;
            }
    #watermark1 {
                position: absolute;
                /* bottom:   0px; */
                /* left:     20px; */
                /** The width and height may change 
                    according to the dimensions of your letterhead
                **/
                /* width:    100%; */
                height:   19cm;
                opacity: 0.6;
                /** Your watermark should be behind every content**/
                z-index:  -2000;
            }
    .rotate {
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        width: 1.5em;
        padding: 10px !important;
        font-size: 11px;
    }
    .rotate div {
        -moz-transform: rotate(90.0deg);  /* FF3.5+ */
        -o-transform: rotate(90.0deg);  /* Opera 10.5 */
        -webkit-transform: rotate(90.0deg);  /* Saf3.1+, Chrome */
                filter:  progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083);  /* IE6,IE7 */
            -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083)"; /* IE8 */
            margin-left: -10em;
            margin-right: -10em;
    }
    header { position: fixed; top: -90px; left: 0px; right: 0px; height: 150px; }
    footer { position: fixed; bottom: -10px; left: 0px; right: 0px; height: 100px; }
        </style>
    </header>
    @php
    
    @endphp
    {{-- {{round($eachcell)}} --}}
    <body>
        <script type="text/php">
            if (isset($pdf)) {
                $x = 850;
                $y = 560;
                $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
                $font = null;
                $size = 9;
                $color = array(0,0,0);
                $word_space = 0.0;  //  default
                $char_space = 0.0;  //  default
                $angle = 0.0;   //  default
                $pdf->page_text($x, $y, $text, $font, $size, $color);
            }
        </script>
        <header>
            <table style="width: 100%;">
                <tr>
                    <td  style="text-align: left; vertical-align: top; width: 15%;">
                        {{-- <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="100px"> --}}
                    </td>
                    <td style="wudth: 70%; text-align: center;">
                        {{-- @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndsc') --}}
                        <div style="width: 100%; font-weight: bold; font-size: 15px;">{{DB::table('schoolinfo')->first()->schoolname}}</div>
                        <div style="width: 100%; font-size: 11px !important;">{{DB::table('schoolinfo')->first()->address}}</div>
                        {{-- <div style="width: 100%; font-weight: bold; font-size: 15px !important;">&nbsp;</div> --}}
                        <div style="width: 100%; font-size: 11px !important;">{{$semester}} {{$sydesc}}</div>
                        {{-- @else
                        <div style="width: 100%;">Republic of the Philippines</div>
                        <div style="width: 100%; font-size: 20px !important;">Department of Education</div>
                        <div style="width: 100%; font-size: 15px !important;">{{DB::table('schoolinfo')->first()->regiontext}}</div>
                        <div style="width: 100%; font-size: 15px !important;">{{DB::table('schoolinfo')->first()->divisiontext}}</div>
                        <div style="width: 100%; font-size: 15px !important;">{{DB::table('schoolinfo')->first()->districttext}}</div>
                        @endif --}}
                    </td>
                    <td style="vertical-align: top; text-align: right; width: 15%;">
                        {{-- @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndsc')
                        <img src="{{base_path()}}/public/assets/images/ndsc/logo_archdiocese.jpg" alt="school" width="100px">
                        @else
                        <img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="100px">
                        @endif --}}
                    </td>
                </tr>
            </table>
        </header>
        <footer>
          <table style="width: 100%; text-align: left !important; font-size: 12px;">
              <thead>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    {{-- <td colspan="2" style="width: 5%;"></td> --}}
                    <td style="width: 18%; font-weight: bold;">Prepared by:</td>
                    <td></td>
                    <td>Noted by:</td>
                    <td></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                @if(count($signatories) == 0)
                    <tr>
                        <td style="width: 18%; border-bottom: 1px solid black; text-align: center !important; font-weight: bold;">{{auth()->user()->name}}</td>
                        <td></td>
                        <td style="width: 18%; border-bottom: 1px solid black; text-align: center !important; font-weight: bold;"></td>
                        <td></td>
                    </tr>
                @else
                    <tr>
                        @foreach($signatories as $signatory)
                        <td style="width: 18%; border-bottom: 1px solid black; text-align: center !important; font-weight: bold;">{{$signatory->name}}</td>
                        <td></td>
                        @endforeach
                    </tr>
                @endif
              </thead>
              <tr>
                  <td style="text-align: center !important;">Registrar</td>
                  <td>&nbsp;</td>
                  <td style="text-align: center !important;">President</td>
                  <td>&nbsp;</td>
              </tr>
          </table>
          
        </footer>
        <main>
            <table style="width: 100%; table-layout: fixed; font-size: 11px;" border="1">
                <thead>
                    <tr>
                        <th rowspan="3" style="width: 3%;">No.</th>
                        <th rowspan="3" style="width: 30%;">Course</th>
                        <th colspan="14">NUMBER OF ENROLLEES</th>
                        <th>&nbsp;</th>
                        {{-- @for($x = 1; $x <= 4; $x++)
                            <th colspan="3">{{$x}}@if($x == 1)st @elseif($x == 2)nd @elseif($x == 3)rd @else th @endif YEAR</th>
                        @endfor
                        <th colspan="3">TOTAL</th> --}}
                    </tr>
                    <tr>
                        @for($x = 1; $x <= 4; $x++)
                        <th colspan="2">{{$x}}@if($x == 1)st @elseif($x == 2)nd @elseif($x == 3)rd @else th @endif YEAR</th>
                        <th rowspan="2">TOTAL</th> 
                        @endfor
                        <th colspan="2">GRAND TOTAL</th>
                        <th rowspan="2">Overall</th> 
                    </tr>
                    <tr>
                        @for($x = 1; $x <= 4; $x++)
                        <th>Male</th>
                        <th>Female</th>
                        @endfor
                        <th>Male</th>
                        <th>Female</th>
                    </tr>
                    {{-- <tr>
                        @for($x = 1; $x <= 4; $x++)
                            <th colspan="3">{{$x}}@if($x == 1)st @elseif($x == 2)nd @elseif($x == 3)rd @else th @endif YEAR</th>
                        @endfor
                    </tr>
                    <tr>
                        @for($x = 1; $x <= 5; $x++)
                            <th>M</th>
                            <th>F</th>
                            <th>T</th>
                        @endfor
                    </tr> --}}
                </thead>
                @if(count($courses)>0)
                    @php
                        $count = 1;
                    @endphp
                    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc')
                        @foreach($courses as $course)
                            <tr>
                                <td style="text-align: center;">{{$count}}</td>
                                <td>{{ucwords(strtolower($course->courseDesc))}}</td>
                                @for($x = 1; $x <= 4; $x++)
                                    <td style="text-align: center;">{{collect($course->students)->where('yearid', $x)->where('gender','male')->count()}}</td>
                                    <td style="text-align: center;">{{collect($course->students)->where('yearid', $x)->where('gender','female')->count()}}</td>
                                    <td style="text-align: center;">{{collect($course->students)->where('yearid', $x)->count()}}</td>
                                @endfor
                                <td style="text-align: center;">{{collect($course->students)->where('gender','male')->count()}}</td>
                                <td style="text-align: center;">{{collect($course->students)->where('gender','female')->count()}}</td>
                                <td style="text-align: center;">{{collect($course->students)->count()}}</td>
                            </tr>
                            @php
                                $count += 1;
                            @endphp
                        @endforeach
                    @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndsc')
                        @foreach($courses as $course)
                            <tr>
                                <td style="text-align: center;">{{$count}}</td>
                                <td>{{ucwords(strtolower($course->courseDesc))}}</td>
                                @for($x = 1; $x <= 4; $x++)
                                    <td style="text-align: center;">{{collect($course->students)->where('yearid', $x)->where('gender','male')->count()}}</td>
                                    <td style="text-align: center;">{{collect($course->students)->where('yearid', $x)->where('gender','female')->count()}}</td>
                                    <td style="text-align: center;">{{collect($course->students)->where('yearid', $x)->count()}}</td>
                                @endfor
                                <td style="text-align: center;">{{collect($course->students)->where('gender','male')->count()}}</td>
                                <td style="text-align: center;">{{collect($course->students)->where('gender','female')->count()}}</td>
                                <td style="text-align: center;">{{collect($course->students)->count()}}</td>
                            </tr>
                            @php
                                $count += 1;
                            @endphp
                        @endforeach
                    @else
                    @foreach($courses as $course)
                        <tr>
                            <td style="text-align: center;">{{$count}}</td>
                            <td>{{ucwords(strtolower($course->courseDesc))}}</td>
                            @for($x = 1; $x <= 4; $x++)
                                <td style="text-align: center;">{{collect($course->students)->where('yearid', $x)->where('gender','male')->count()}}</td>
                                <td style="text-align: center;">{{collect($course->students)->where('yearid', $x)->where('gender','female')->count()}}</td>
                                <td style="text-align: center;">{{collect($course->students)->where('yearid', $x)->count()}}</td>
                            @endfor
                            <td style="text-align: center;">{{collect($course->students)->where('gender','male')->count()}}</td>
                            <td style="text-align: center;">{{collect($course->students)->where('gender','female')->count()}}</td>
                            <td style="text-align: center;">{{collect($course->students)->count()}}</td>
                        </tr>
                        @php
                            $count += 1;
                        @endphp
                    @endforeach
                    @endif
                @endif
            </table>
        </main>
    </body>
</html>