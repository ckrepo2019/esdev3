<html>
    <header>
        <style>
            * { font-family: Arial, Helvetica, sans-serif; }
            @page{
                margin: 1.2in 0.2in 1.5in 0.2in;
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
    header { position: fixed; top: -100px; left: 0px; right: 0px; height: 250px; }
    footer { position: fixed; bottom: -40px; left: 0px; right: 0px; height: 50px; }
        </style>
    </header>
    
    {{-- {{round($eachcell)}} --}}
    <body>
        <header>
            <table style="width: 100%;">
                <tr>
                    <td  style="text-align: left; vertical-align: top; width: 15%;"><img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="80px"></td>
                    <td style="wudth: 70%; text-align: center;">
                        {{-- @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndsc') --}}
                        <div style="width: 100%; font-weight: bold; font-size: 15px;">{{DB::table('schoolinfo')->first()->schoolname}}</div>
                        <div style="width: 100%; font-weight: bold; font-size: 15px !important;">{{DB::table('schoolinfo')->first()->address}}</div>
                        <div style="width: 100%; font-weight: bold; font-size: 15px !important;">&nbsp;</div>
                        <div style="width: 100%; font-weight: bold; font-size: 15px !important;">Students' Vaccination Status
                        </div>
                        <div style="width: 100%; font-size: 12px !important;">
                            {{-- @if($seminfo->id == 1)FIRST SEMESTER @elseif($seminfo->id == 2)SECOND SEMESTER @endif, SY {{$syinfo->sydesc}} --}}
                            {{$sydesc}}
                        </div>
                        <div style="width: 100%; font-size: 12px !important;">
                            {{$levelname}}
                        </div>
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
                        <img src="{{base_path()}}/public/assets/images/ndsc/logo_archdiocese.jpg" alt="school" width="80px">
                        @else
                        <img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="80px">
                        @endif
                    </td>
                </tr>
            </table>
        </header>
        <footer>
          <table style="width: 100%; text-align: left !important; font-size: 12px;">
              <thead>
                  <tr>
                      <td width="15%">Prepared by:</td>
                      <td width="25%"></td>
					  <td width="60%">&nbsp;</td>
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
                  <td style="border-bottom: solid 1px black; text-align: center !important">{{auth()->user()->name}}</td>
				  <td>&nbsp;</td>
              </tr>
              <tr>
                  <td style="padding: 0 !important"></td>
                  <td style="padding: 0 !important; text-align: center !important">Registrar</td>
              </tr>
          </table>
          
        </footer>
        <main>
            {{-- {{count($students)}} --}}
            @if(count($students)>0)
                <table style="width: 100%; font-size: 10.5px; margin-top: 20px;" border="1">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No.</th>
                            <th style="width: 10%;">Student ID</th>
                            <th style="width: 10%;">LRN</th>
                            <th style="">Student Name</th>
                            <th style="width: 8%;">Vaccination<br/>Status</th>
                            <th style="width: 12%;">Vaccination<br/>Type</th>
                            <th style="width: 12%;">Vaccination<br/>Card ID</th>
                            <th style="width: 10%; font-size: 8px;">First Dose</th>
                            <th style="width: 10%; font-size: 8px;">Second Dose</th>
                        </tr>
                    </thead>
                    @foreach($students as $key=>$student)
                        {{-- @php
                            $studentcount+=1;
                        @endphp --}}
                        <tr>
                            <td style="text-align: center;">{{$key+1}}</td>
                            <td>{{$student->sid}}</td>
                            <td>{{$student->lrn}}</td>
                            <td>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}}</td>
                            <td style="text-align: center; @if($student->vaccstatus != 1) background-color: #f0d1d1; @endif">@if($student->vaccstatus == 1)Vaccinated @endif</td>
                            @if(isset($student->medinfo))
                            <td>{{ucwords(strtolower($student->medinfo->vacc_type))}}</td>
                            <td>{{$student->medinfo->vacc_card_id}}</td>
                            <td>{{$student->medinfo->dose_date_1st != null ?  date('M d, Y', strtotime($student->medinfo->dose_date_1st)) : null}}</td>
                            <td>{{$student->medinfo->dose_date_2nd != null ?  date('M d, Y', strtotime($student->medinfo->dose_date_2nd)) : null}}</td>
                            @else
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            @endif
                        </tr>
                    @endforeach
                </table>
            @endif
        </main>
    </body>
</html>