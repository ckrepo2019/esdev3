<html>
    <header>
        <style>
            * { font-family: Arial, Helvetica, sans-serif; }
            @page{
                margin: 1.5in 0.5in 0.8in 0.5in;
                size: 8.5in 13in;
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
        $colleges = collect($colleges)->sortBy('sortid')->all();
        $firstyearmale = 0;
        $firstyearfemale = 0;
        $secondyearmale = 0;
        $secondyearfemale = 0;
        $thirdyearmale = 0;
        $thirdyearfemale = 0;
        $fourthyearmale = 0;
        $fourthyearfemale = 0;
        $fifthyearmale = 0;
        $fifthyearfemale = 0;


        $allmale = 0;
        $allfemale = 0;
        $allunspecified = 0;
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
            <table style="width: 100%; table-layout: fixed;">
                <tr>
                    <td  style="text-align: left; vertical-align: top; text-align: right;">
                        <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="50px">
                    </td>
                    <td style="width: 30%; text-align: center; vertical-align: top;">
                        <div style="width: 100%; font-weight: bold; font-size: 15px;">{{DB::table('schoolinfo')->first()->schoolname}}</div>
                        <div style="width: 100%; font-size: 11px !important; font-weight: bold;">{{ucwords(strtolower(DB::table('schoolinfo')->first()->address))}}</div>
                    </td>
                    <td style="vertical-align: top; text-align: right;">
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align: center;">
                        <div style="width: 100%; font-size: 12px !important; font-weight: bold;">SUMMARY OF ENROLLMENT</div>
                        <div style="width: 100%; font-size: 11px !important; font-weight: bold;">{{$semester}} AY:{{$sydesc}}</div>
                    </td>
                    <td></td>
                </tr>
            </table>
        </header>
        {{-- <footer>
          <table style="width: 100%; text-align: left !important; font-size: 12px;">
              <thead>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
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
                  <tr>
                      <td style="width: 18%; border-bottom: 1px solid black; text-align: center !important; font-weight: bold;">{{auth()->user()->name}}</td>
                      <td></td>
                      <td style="width: 18%; border-bottom: 1px solid black; text-align: center !important; font-weight: bold;"></td>
                      <td></td>
                  </tr>
              </thead>
              <tr>
                  <td style="text-align: center !important;">Registrar</td>
                  <td>&nbsp;</td>
                  <td style="text-align: center !important;">President</td>
                  <td>&nbsp;</td>
              </tr>
          </table>
          
        </footer> --}}
        <main>
            <table style="width: 100%; table-layout: fixed; font-size: 12px; border: 1px solid black;" border="1">
                <thead>
                    <tr>
                        <th style="width: 20%;">Course & Year</th>
                        <th style="width: 25%;">Dept/School</th>
                        <th>Male</th>
                        <th>Female</th>
                        <th>Total</th>
                        <th>Grand Total</th>
                    </tr>
                </thead>
                <tbody style="page-break-inside: avoid;" >
                    @foreach($colleges as $college)
                        @if(count($college->courses)>0)
                            @php
                                $collectcourses = collect($college->courses)->sortBy('sortid')->all();
                                $collectcourses = collect($collectcourses)->groupBy('id');
                            @endphp
                            @foreach($collectcourses as $allcourses)
                                @foreach(collect($allcourses)->sortBy('yearid')->values()->all() as $coursekey=>$eachcourse)
                                    {{-- @if($eachcourse->studentcount > 0) --}}
                                        <tr style="page-break-inside: avoid;" nobr="true">
                                            <td style="text-align: center;">{{$eachcourse->courseandyear}}</td>
                                            <td style="text-align: center;">{{str_replace('School of', '',$college->collegeDesc)}}</td>
                                            <td style="text-align: center;">{{$eachcourse->malecount}}</td>
                                            <td style="text-align: center;">{{$eachcourse->femalecount}}</td>
                                            <td style="text-align: center;">{{$eachcourse->studentcount}}</td>
                                            @if($coursekey == 0)
                                            <td rowspan="{{collect($allcourses)->count()+1}}" style="text-align: center; vertical-align: middle; border: 2px solid black; page-break-inside: avoid; font-size: 20px;">{{collect($allcourses)->sum('studentcount')}}</td>
                                            @endif
                                        </tr>
                                        @php
                                            $allmale += $eachcourse->malecount;
                                            $allfemale += $eachcourse->femalecount;
                                            if($eachcourse->yearid == 1)
                                            {
                                                $firstyearmale += $eachcourse->malecount;
                                                $firstyearfemale += $eachcourse->femalecount;
                                            }
                                            if($eachcourse->yearid == 2)
                                            {
                                                $secondyearmale += $eachcourse->malecount;
                                                $secondyearfemale += $eachcourse->femalecount;
                                            }
                                            if($eachcourse->yearid == 3)
                                            {
                                                $thirdyearmale += $eachcourse->malecount;
                                                $thirdyearfemale += $eachcourse->femalecount;
                                            }
                                            if($eachcourse->yearid == 4)
                                            {
                                                $fourthyearmale += $eachcourse->malecount;
                                                $fourthyearfemale += $eachcourse->femalecount;
                                            }
                                            if($eachcourse->yearid == 5)
                                            {
                                                $fifthyearmale += $eachcourse->malecount;
                                                $fifthyearfemale += $eachcourse->femalecount;
                                            }
                                        @endphp
                                    {{-- @endif --}}
                                @endforeach
                                <tr>
                                    <th style="text-align: center; border-bottom: 2px solid black;">Sub Total</th>
                                    <td style="text-align: center; border-bottom: 2px solid black;"></td>
                                    <th style="text-align: center; border-bottom: 2px solid black;">{{collect($allcourses)->sum('malecount')}}</th>
                                    <th style="text-align: center; border-bottom: 2px solid black;">{{collect($allcourses)->sum('femalecount')}}</th>
                                    <th style="text-align: center; border-bottom: 2px solid black;">{{collect($allcourses)->sum('studentcount')}}</th>
                                </tr>
                            @endforeach
                        @endif
                    @endforeach
                    <tr>
                        <th rowspan="2" style="border: 2px solid black; background-color: #99d6ff;">Year Level Total</th>
                        <th style="border: 2px solid black; background-color: #99d6ff;">1st Year</th>
                        <th style="border: 2px solid black; background-color: #99d6ff;">2nd Year</th>
                        <th style="border: 2px solid black; background-color: #99d6ff;">3rd Year</th>
                        <th style="border: 2px solid black; background-color: #99d6ff;">4th Year</th>
                        <th rowspan="3" style="border: 2px solid black; font-size: 20px; background-color: #99d6ff;">{{$allmale+$allfemale}}</th>
                    </tr>
                    <tr>
                        <th style="border: 2px solid black; background-color: #99d6ff;">{{$firstyearmale+$firstyearfemale}}</th>
                        <th style="border: 2px solid black; background-color: #99d6ff;">{{$secondyearmale+$secondyearfemale}}</th>
                        <th style="border: 2px solid black; background-color: #99d6ff;">{{$thirdyearmale+$thirdyearfemale}}</th>
                        <th style="border: 2px solid black; background-color: #99d6ff;">{{$fourthyearmale+$fourthyearfemale}}</th>
                    </tr>
                    <tr>
                        <th style="border: 2px solid black; background-color: #99d6ff;">GRAND TOTAL</th>
                        <th style="border: 2px solid black; background-color: #99d6ff;"></th>
                        <th style="border: 2px solid black; background-color: #99d6ff;">{{$allmale}}</th>
                        <th style="border: 2px solid black; background-color: #99d6ff;">{{$allfemale}}</th>
                        <th style="border: 2px solid black; background-color: #99d6ff;">{{$allmale+$allfemale}}</th>
                    </tr>
                </tbody>
            </table>
            <br/>
            <br/>
            <table style="width: 100%; font-size: 12px; table-layout: fixed;">
                <tr>
                    <td style="width: 15%;">Prepared by:</td>
                    <td></td>
                    <td style="width: 15%;">Noted By:</td>
                    <td></td>
                    <td style="width: 15%;"></td>
                </tr>
                <tr>
                    <td colspan="5">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="5">&nbsp;</td>
                </tr>
                <tr>
                    <td></td>
                    <td style="border-bottom: 1px solid black;text-align: center;">{{auth()->user()->name}}</td>
                    <td></td>
                    <td style="border-bottom: 1px solid black;text-align: center;">{{collect($signatories)->where('title','Registrar')->first()->name ?? ''}}</td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align: center;">OFFICE STAFF</td>
                    <td></td>
                    <td style="text-align: center;">OIC-COLLEGE REGISTRAR</td>
                    <td></td>
                </tr>
            </table>
            {{-- <table style="width: 100%; table-layout: fixed; font-size: 11px;" border="1">
                <thead>
                    <tr>
                        <th rowspan="3" style="width: 3%;">No.</th>
                        <th rowspan="3" style="width: 30%;">Course</th>
                        <th colspan="14">NUMBER OF ENROLLEES</th>
                        <th>&nbsp;</th>
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
                </thead>
                @if(count($courses)>0)
                    @php
                        $count = 1;
                    @endphp
                    @foreach($courses as $coursekey => $course)
                        <tr>
                            <td style="text-align: center;">{{$count}}</td>
                            <td>{{$coursekey}}</td>
                            @for($x = 1; $x <= 4; $x++)
                                <td style="text-align: center;">{{collect($course)->where('yearid', $x)->where('gender','male')->count()}}</td>
                                <td style="text-align: center;">{{collect($course)->where('yearid', $x)->where('gender','female')->count()}}</td>
                                <td style="text-align: center;">{{collect($course)->where('yearid', $x)->count()}}</td>
                            @endfor
                            <td style="text-align: center;">{{collect($course)->where('gender','male')->count()}}</td>
                            <td style="text-align: center;">{{collect($course)->where('gender','female')->count()}}</td>
                            <td style="text-align: center;">{{collect($course)->count()}}</td>
                        </tr>
                        @php
                            $count += 1;
                        @endphp
                    @endforeach
                @endif
            </table> --}}
        </main>
    </body>
</html>