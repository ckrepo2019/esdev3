

<style>
      #header{ /* border: 1px solid #ddd; */ font-size: 11px; border-spacing: 0; width:100%; font-family: Arial, Helvetica, sans-serif; }

      #header td,  #header th{ /* border: 1px solid black; */ }

      #grades{ /* border: 1px solid #ddd; */ border-bottom:1px solid black;  font-size: 13px; border-collapse: collapse;width:100%; font-family: Arial, Helvetica, sans-serif; margin: 5px 25px 0px 0px; }

      #grades td,  #grades th{ border: 1px solid black;  }
      #grades td:nth-child(2){
            text-transform: uppercase;
      }
      #grades td{ text-align: center; font-size: 11px; }

      .page_break { page-break-before: always; }

      .page_break_table { page-break-inside: auto; }

      input[type=text]{ padding: 3px; font-size: 11px; text-align: center; border: 1px solid black; }

      .left{ float: left; width : 45%; /* height : 100px; */ /* border: solid 1px black; */ /* display : inline-block; */ }

      h1,sup{ padding:0px; /* border: 1px solid black; */ margin:0px; }

      .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            background-color: transparent;
      }

  </style>

  <table id="header">
      <tr>
          <td rowspan="3" style="width:5%">
                  <img src="{{asset($schoolinfo[0]->picurl)}}" alt="school" width="70px">
          </td>
          <th colspan="7" style="padding-left:13%">
              <center>
                  <h1>Class Record</h1>
                  <em><sup>(Pursuant to Deped Order 8 series of 2015)</sup></em>
              </center>
          </th>
          <th rowspan="2" style="text-align:right;width:20px">
              <img src="{{base_path()}}/public/assets/images/deped_logo.png" alt="school" width="100px">
          </th>
      </tr>
      <tr>
          <td style="text-align:right">REGION</td>
          <td width="15px"><input type="text" value="{{$schoolinfo[0]->region}}"/></td>
          <td width="10px">DIVISION</td>
          <td><input type="text" value="{{$schoolinfo[0]->division}}"/></td>
          <td width="80px" style="text-align:right">DISTRICT</td>
          <td><input type="text" value="{{$schoolinfo[0]->district}}"/></td>
          <td></td>
      </tr>
      <tr>
          <td style="text-align:right">SCHOOL NAME</td>
          <td colspan="3"><input type="text" value="{{$schoolinfo[0]->schoolname}}" style="width:100%"/></td>
          <td style="text-align:right">SCHOOL ID</td>
          <td><input type="text" value="{{$schoolinfo[0]->schoolid}}"/></td>
          <td style="text-align:right">SCHOOL YEAR</td>
          <td><input type="text" value="{{$schoolyear[0]->sydesc}}"/></td>
      </tr>
  </table>
  
@php
      $totalItems = 0;
      $statusfield = 'q'.$quarter.'status';
      $submittedField = 'q'.$quarter.'datesubmitted';
      $postedField = 'q'.$quarter.'dateposted';
@endphp

@foreach ($grading_system_detail as $gsditem)
      @php
            $totalItems = $totalItems + $gsditem->items + 3;
      @endphp
@endforeach

@php
      $tableWidth = ( $totalItems * 80 ) + 10 ;
@endphp
      

<table class="table" id="grades">
      <thead>
            <tr>
                  <td colspan="2" style="font-size:12px; padding:4px">
                        @if($quarter == 1)
                              FIRST QUARTER
                        @elseif($quarter == 2)
                              SECOND QUARTER
                        @elseif($quarter == 3)
                              THIRD QUARTER
                        @elseif($quarter == 4)
                              FOURTH QUARTER
                        @endif
                  </td>

                
                  <td colspan="8" style="font-size:12px; padding:4px">
                        GRADE & SECTION: {{$section->levelname }} {{$section->sectionname}}
                  </td>
                  <td colspan="8" style="font-size:12px; padding:4px">
                        TEACHER: {{$teacher->firstname }} {{$teacher->lastname}}
                  </td>
                  <td colspan="8" style="font-size:12px; padding:4px">
                        SUBJECT: {{$subjectStatus[0]->subjdesc}}
                  </td>
                  <td colspan="{{($totalItems - 24 ) + 2}}">

                  </td>
            </tr>
            <tr>
                  <td></td>
                  <th style="min-width:210px !important; z-index:101;background-color:#fff">LEARNER'S NAMES</th>
                  @foreach ($grading_system_detail as $item)
                        <th colspan="{{$item->items + 3}}" class="text-center border" style="font-size: 11px">{{$item->description}} ( {{$item->value}} %)</th>
                  @endforeach
      
                  <th style=" z-index:100;background-color:#fff" class="ig"></th>
                  <th style=" z-index:100;background-color:#fff"></th>
            </tr>
            <tr>
                  <td></td>
                  <th style="min-width:210px !important; z-index:100;background-color:#fff; top: 38px;"></th>
                  @foreach ($grading_system_detail as $gsditem)
                        @for ($x = 1; $x <= $gsditem->items; $x++)
                              <th style="top: 38px; background-color:#fff;" class="text-center align-middle border" >{{$x}}</th>
                        @endfor
                        <th style="top: 38px; background-color:#fff; " class="text-center align-middle border ps" >Total</th>
                        <th style="top: 38px; background-color:#fff;" class="text-center align-middle border ps" >PS</th>
                        <th style="top: 38px; background-color:#fff;" class="text-center align-middle border ws">WS</th>
                  @endforeach

                  <th style="width:60px; top: 38px; z-index:100;background-color:#fff" class="text-center align-middle ig">IG</th>
                  <th style="width:60px; top: 38px; z-index:100;background-color:#fff" class="text-center align-middle">QG</th>
      </thead>
      <tbody>
            <tr data-gs="{{$gsheader->nogs}}"  data-id="{{$gsheader->id}}" class="studtr">
                  <th></th>
                  <th style="text-align:left !important;padding-left: 3px !important;">Highest Possible Score</th>
                  @if(count($gsheader->gsdget) > 0)
                  @php
                        $withData = false;
                  @endphp
                        @foreach ($grading_system_detail as $gsditem)
                                    @php
                                          $counter = 1; 
                                    @endphp
                                    @foreach (collect($gsheader->gsdget)->where('gsdid',$gsditem->id) as $studgsditem)
                                          @for ($x = 1; $x <= $gsditem->items; $x++)
                                                @php
                                                      $field = 'g'.$x.'q'.$quarter;
                                                @endphp
                                                @if($studgsditem->$field == 0)
                                                      <th></th>
                                                @else
                                                      <th class="align-middle border  hps isHPS">{{$studgsditem->$field}}</th>
                                                @endif
                                                 
                                          @endfor
                                    @endforeach
                                    @php
                                          $psfield = 'psq'.$quarter;
                                          $wsfiled = 'wsq'.$quarter;
                                          $qtotal = 'q'.$quarter.'total';
                                    @endphp
                                    @if($withData)
                                          <th class="text-center align-middle border hpstotal isHPS" 
                                                      data-id="{{$studgsditem->id}}" 
                                                      data-field="{{$field}}"
                                                      data-studid="{{$gsheader->id}}"
                                                      data-gsid="{{$studgsditem->gsdid}}"
                                                      style=" z-index:100;background-color:#fff"   
                                                            >{{$studgsditem->$qtotal}}</th>
                                          <th class="text-center align-middle border hpsps isHPS"
                                                      data-id="{{$studgsditem->id}}" 
                                                      data-studid="{{$gsheader->id}}"
                                                      data-gsid="{{$studgsditem->gsdid}}"
                                                      style=" z-index:100;background-color:#fff"
                                                            >100</th>
                                          <th class="text-center align-middle border hpsws isHPS"
                                                      data-id="{{$studgsditem->id}}" 
                                                      data-field="{{$field}}"
                                                      data-gsid="{{$studgsditem->gsdid}}"
                                                      style=" z-index:100;background-color:#fff"
                                                      data-per="{{$gsditem->value}}" 
                                                      >{{$gsditem->value}}%</th>
                                    @else
                                          <th></th>
                                          <th></th>
                                          <th></th>
                                    @endif
                        @endforeach
                        @if($withData)
                              <th style="width:60px; z-index:100;background-color:#fff" class="text-center align-middle border ig isHPS">100</th>
                              <th style="width:60px; z-index:100;background-color:#fff" class="isHPS">100</th>
                        @else
                              <th></th>
                              <th></th>
                        @endif
                  @else
                        <th colspan="{{ $totalItems}}" class="border text-left text-danger isHPS">Grades detail is not yet generated.</th>
                        <th class="text-center align-middle border ig isHPS" style="width:60px; top: 38px; z-index:100;background-color:#fff"></th>
                        <th class="text-center align-middle border isHPS" style="width:60px; top: 38px; z-index:100;background-color:#fff"></th>
                  @endif
            
            </tr>
            @php
                  $male = 0;
                  $female = 0;
                  $count = 0;
            @endphp

            @foreach ($students as $item)
                  @php
                        $count += 1;
                  @endphp
                  @if($male == 0 && $item->gender == 'MALE')
                        <tr class="bg-secondary">
                              <td></td>
                              <th class="text-dark bg-secondary">MALE</th>
                              @foreach ($grading_system_detail as $gsditem)
                                    <th colspan="{{$gsditem->items + 3}}" class="text-center border" style="font-size: 11px"></th>
                              @endforeach
                              <th class="text-center align-middle border ig  bg-secondary" ></th>
                              <th class="text-center align-middle border bg-secondary"></th>
                        </tr>
                        @php
                              $male = 1;
                        @endphp
                  @elseif($female == 0  && $item->gender == 'FEMALE')
                        <tr class="bg-secondary">
                              <td></td>
                              <th class="text-dark bg-secondary">FEMALE</th>
                              @foreach ($grading_system_detail as $gsditem)
                                    <th colspan="{{$gsditem->items + 3}}" class="text-center border" style="font-size: 11px"></th>
                              @endforeach
                              <th class="text-center align-middle border ig  bg-secondary"></th>
                              <th class="text-center align-middle border bg-secondary" ></th>
                        </tr>
                      
                        @php
                              $count = 1;
                              $female = 1;
                        @endphp
                        
                  @endif
                  <tr data-gs="{{$item->nogs}}"  data-id="{{$item->id}}" class="studtr studtrgrading">
                        <td>{{$count}}</td>
                        <td style="text-align:left !important; padding-left: 3px !important">{{$item->lastname.', '.$item->firstname}}</td>
                        @if(count($item->gsdget) > 0)

                              @php
                                    $withData = false;
                              @endphp
                              @foreach ($grading_system_detail as $gsditem)

                                    @php
                                          $counter = 1;
                                    @endphp

                                    @foreach (collect($item->gsdget)->where('gsdid',$gsditem->id) as $studgsditem)
                                    
                                          @for ($x = 1; $x <= $gsditem->items; $x++)
                                                @php
                                                      $field = 'g'.$x.'q'.$quarter;
                                                      $header = collect($gsheader->gsdget)->where('gsdid',$gsditem->id)->first();
                                                @endphp
                                                @if($studgsditem->$field != 0)
                                                      @php
                                                            $withData = true
                                                      @endphp
                                                      <td class="text-center align-middle border studgs" 
                                                      data-id="{{$studgsditem->id}}" 
                                                      data-field="{{$field}}"
                                                      data-gsid="{{$studgsditem->gsdid}}"
                                                      data-studid="{{$item->id}}"
                                                      >{{$studgsditem->$field}}</td>
                                                @else
                                                      @if(isset($header->gsdid)>0)
                                                            @if($header->$field != 0)
                                                                  <td>0</td>
                                                            @else
                                                                  <td></td>
                                                            @endif
                                                      @else
                                                            <td></td>
                                                      @endif
                                                      
                                                @endif
                                               
                                          @endfor
                                          @php
                                                $psfield = 'psq'.$quarter;
                                                $wsfiled = 'wsq'.$quarter;
                                                $qtotal = 'q'.$quarter.'total';
                                                $igfield = 'igq'.$quarter;
                                                $qgfield = 'qgq'.$quarter;
                                                $ig =  $studgsditem->$igfield;
                                                $qg =  $studgsditem->$qgfield;
                                          @endphp
                                          @if($withData)
                                                <th class="text-center align-middle border ps istotal" 
                                                                  data-id="{{$studgsditem->id}}" 
                                                                  data-field="{{$field}}"
                                                                  data-gsid="{{$studgsditem->gsdid}}"
                                                                  data-studid="{{$item->id}}"
                                                                        >{{$studgsditem->$qtotal}}</th>

                                                <th class="text-center align-middle border ps isps" 
                                                                  data-id="{{$studgsditem->id}}" 
                                                                  data-field="{{$field}}"
                                                                  data-gsid="{{$studgsditem->gsdid}}"
                                                                  data-studid="{{$item->id}}"
                                                                        >{{$studgsditem->$psfield}}</th>

                                                <th class="text-center align-middle border ws isws"
                                                                  data-id="{{$studgsditem->id}}" 
                                                                  data-field="{{$field}}"
                                                                  data-gsid="{{$studgsditem->gsdid}}"
                                                                  data-studid="{{$item->id}}"
                                                                        >{{$studgsditem->$wsfiled}}</th>
                                          @else
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                          @endif

                                    @endforeach
                              @endforeach
                              @if($withData)
                                    <th class="text-center align-middle border ig"
                                          data-studid="{{$item->id}}"
                                                >{{$ig}}</th>
                                    <th class="text-center align-middle border qg"
                                          data-studid="{{$item->id}}"
                                                >{{$qg}}</th>
                              @else
                                    <th></th>
                                    <th></th>
                              @endif
                        @else
                              <th colspan="{{ $totalItems }}" class="border text-left text-danger">Grades detail is not yet generated.</th>
                              <th class="text-center align-middle border ig"></th>
                              <th class="text-center align-middle border "></th>
                        @endif
                  </tr>
            @endforeach
      </tbody>
</table>
  