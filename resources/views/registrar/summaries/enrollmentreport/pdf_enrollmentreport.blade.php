<html>
    <header>
        <style>
            * { font-family: Arial, Helvetica, sans-serif; }
            @page{
                margin: 1.2in 0.2in 1.5in 0.2in;
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
    header { position: fixed; top: -100px; left: 0px; right: 0px; height: 250px; }
    footer { position: fixed; bottom: -40px; left: 0px; right: 0px; height: 50px; }
        </style>
    </header>
    @php
        if($maxsubjects == 0)
        {
            $eachcell = 65;
        }else{
            $eachcell  = (65/$maxsubjects);
        }
        $eachpage  = array_chunk(collect($students)->toArray(), 30);
        $studentcount = 0;
    @endphp
    {{-- {{round($eachcell)}} --}}
    <body>
        <header>
            <table style="width: 100%;">
                <tr>
                    <td  style="text-align: left; vertical-align: top; width: 15%;"><img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="100px"></td>
                    <td style="wudth: 70%; text-align: center;">
                        {{-- @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndsc') --}}
                        <div style="width: 100%; font-weight: bold; font-size: 15px;">{{DB::table('schoolinfo')->first()->schoolname}}</div>
                        <div style="width: 100%; font-weight: bold; font-size: 15px !important;">{{DB::table('schoolinfo')->first()->address}}</div>
                        <div style="width: 100%; font-weight: bold; font-size: 15px !important;">&nbsp;</div>
                        <div style="width: 100%; font-weight: bold; font-size: 15px !important;">ENROLMENT REPORT</div>
                        <div style="width: 100%; font-weight: bold; font-size: 15px !important;">@if($seminfo->id == 1)FIRST SEMESTER @elseif($seminfo->id == 2)SECOND SEMESTER @endif, SY {{$syinfo->sydesc}}</div>
                        {{-- @else
                        <div style="width: 100%;">Republic of the Philippines</div>
                        <div style="width: 100%; font-size: 20px !important;">Department of Education</div>
                        <div style="width: 100%; font-size: 15px !important;">{{DB::table('schoolinfo')->first()->regiontext}}</div>
                        <div style="width: 100%; font-size: 15px !important;">{{DB::table('schoolinfo')->first()->divisiontext}}</div>
                        <div style="width: 100%; font-size: 15px !important;">{{DB::table('schoolinfo')->first()->districttext}}</div>
                        @endif --}}
                    </td>
                    <td style="vertical-align: top; text-align: right; width: 15%;">
                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndsc')
                        <img src="{{base_path()}}/public/assets/images/ndsc/logo_archdiocese.jpg" alt="school" width="100px">
                        @else
                        <img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="100px">
                        @endif
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
                </tr>
                  <tr>
                      <td style="width: 2%;"></td>
                      <td style="width: 68%;">Prepared by:</td>
                      <td style="width: 30%;">Noted by:</td>
                  </tr>
              </thead>
              <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
              </tr>
              <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
              </tr>
              <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
              </tr>
              <tr style="font-size: 15px; font-weight: bold;">
                  <td>&nbsp;</td>
                  <td>{{$registrar}}</td>
                  <td>{{$president}}</td>
              </tr>
              <tr>
                  <td></td>
                  <td>Registrar</td>
                  <td>President</td>
              </tr>
          </table>
          
        </footer>
        <main>
            {{-- @if(count($eachpage)>0)
                @foreach($eachpage as $key=>$each)
                    @if($key > 0)
                        <div style="page-break-after: always">&nbsp;</div>
                    @endif
                    @if($key == 0)
                    <table style="width: 100%; font-size: 10.5px;" border="1">
                    @else
                    <table style="width: 100%; font-size: 10.5px; margin-top: 20px;" border="1">
                    @endif
                        <thead>
                            <tr>
                                <th style="width: 2%;">No.</th>
                                <th style="width: 15%;">Name<br/>(Surname, FIrst Name, Middle Name)<br/>in Alphabetical Order</th>
                                <th style="width: 2%;">S<br/>E<br/>X</th>
                                <th style="width: 5%;">COURSE</th>
                                <th style="width: 2%;">Y<br/>E<br/>A<br/>R</th>
                                <th style="width: 5%; font-size: 8px;">BIRTHDATE</th>
                                @for($x = 0; $x < $maxsubjects; $x++)
                                <th style="width: {{$eachcell-2}}%; font-size: 8px;">Subject-{{$x+1}}</th>
                                <th style="width: 2%; font-size: 8px;">U<br/>n<br/>i<br/>t<br/>s</th>
                                @endfor
                                <th style="width: 3%; font-size: 8px;" class='rotate'><div>Total Units</div></th>
                            </tr>
                        </thead>
                        @foreach($each as $key=>$student)
                            @php
                                $studentcount+=1;
                            @endphp
                            <tr>
                                <td style="text-align: center;">{{$studentcount}}</td>
                                <td style=" font-size: 10px;">{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}}</td>
                                <td style="text-align: center;">{{$student->gender[0]}}</td>
                                <td>{{$student->courseabrv}}</td>
                                <td style="text-align: center;">{{$student->yearid}}</td>
                                <td>@if($student->dob != null){{date('m/d/Y', strtotime($student->dob))}}@endif</td>
                                @for($x = 0; $x < $maxsubjects; $x++)
                                    <td style=" font-size: 8px;">@if(isset($student->subjects[$x])) {{$student->subjects[$x]->subjcode}} @endif</td>
                                    <td style="text-align: center; font-size: 8px;">@if(isset($student->subjects[$x])) {{$student->subjects[$x]->subjunit}} @endif</td>
                                @endfor
                                <td style="text-align: center;">{{collect($student->subjects)->sum('subjunit')}}</td>
                            </tr>
                        @endforeach
                    </table>
                @endforeach
            @endif --}}
            @if(count($students)>0)
                <table style="width: 100%; font-size: 10.5px; margin-top: 20px;" border="1">
                    <thead>
                        <tr>
                            <th style="width: 2%;">No.</th>
                            <th style="width: 15%;">Name<br/>(Surname, FIrst Name, Middle Name)<br/>in Alphabetical Order</th>
                            <th style="width: 2%;">S<br/>E<br/>X</th>
                            <th style="width: 5%;">COURSE</th>
                            <th style="width: 2%;">Y<br/>E<br/>A<br/>R</th>
                            <th style="width: 5%; font-size: 8px;">BIRTHDATE</th>
                            @for($x = 0; $x < $maxsubjects; $x++)
                            <th style="width: {{$eachcell-2}}%; font-size: 8px;">Subject-{{$x+1}}</th>
                            <th style="width: 2%; font-size: 8px;">U<br/>n<br/>i<br/>t<br/>s</th>
                            @endfor
                            <th style="width: 3%; font-size: 8px;" class='rotate'><div>Total Units</div></th>
                        </tr>
                    </thead>
                    @foreach($students as $key=>$student)
                        @php
                            $studentcount+=1;
                        @endphp
                        <tr>
                            <td style="text-align: center;">{{$studentcount}}</td>
                            <td style=" font-size: 10px;">{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}}</td>
                            <td style="text-align: center;">{{$student->gender[0]}}</td>
                            <td>{{$student->courseabrv}}</td>
                            <td style="text-align: center;">{{$student->yearid}}</td>
                            <td>@if($student->dob != null){{date('m/d/Y', strtotime($student->dob))}}@endif</td>
                            @for($x = 0; $x < $maxsubjects; $x++)
                                <td style=" font-size: 8px;">@if(isset($student->subjects[$x])) {{$student->subjects[$x]->subjcode ?? $student->subjects[$x]->subjectcode}} @endif</td>
                                <td style="text-align: center; font-size: 8px;">@if(isset($student->subjects[$x])) {{$student->subjects[$x]->subjunit}} @endif</td>
                            @endfor
                            <td style="text-align: center;">{{collect($student->subjects)->sum('subjunit')}}</td>
                        </tr>
                    @endforeach
                </table>
            @endif
        </main>
    </body>
</html>