@if (count($students) > 0)

      @php
            $totalItems = 0;
            $statusfield = 'q'.$quarter.'status';
            $submittedField = 'q'.$quarter.'datesubmitted';
            $postedField = 'q'.$quarter.'dateposted';
      @endphp
      @foreach ($grading_system_detail[0]->gsdetail as $gsditem)
            @php
                  $totalItems = $totalItems + $gsditem->items + 3;
            @endphp
      @endforeach
      @php
            $tableWidth = ( $totalItems * 80 ) + 10 ;
      @endphp
            

      <style>
      
            .gradestable{width:{{ $tableWidth}}px; font-size:90%; text-transform: uppercase; }
      
            .gradestable thead th:last-child  { 
                  position: sticky; 
                  right: 0; 
                  background-color: #fff; 
                  outline: 2px solid #dee2e6;
                  outline-offset: -1px;
            }
      
            .gradestable tbody th:last-child  { 
                  position: sticky; 
                  right: 0; 
                  background-color: #fff; 
                  outline: 2px solid #dee2e6;
                  outline-offset: -1px;
                  min-width: 60px;
            }
      
            .gradestable tbody th:first-child  {  
                  position: sticky; 
                  left: 0; 
                  background-color: #fff; 
                  width: 300px !important;
                  background-color: #fff; 
                  outline: 2px solid #dee2e6;
                  outline-offset: -1px;
            }
      
            .gradestable thead th:first-child  { 
                  position: sticky; left: 0; 
                  width: 300px !important;
                  background-color: #fff; 
                  outline: 2px solid #dee2e6;
                  outline-offset: -1px;
            }
            
            .gradestable td, .ps, .ws{
                  
                  min-width:50px !important;
                  text-align: center;
                  cursor: pointer;
                  vertical-align: middle !important;
            }
      
            .ps, .ws{
                  
                  min-width:60px !important;
                  text-align: center;
                  cursor: pointer;
                  vertical-align: middle !important;
            }

            .ig{

                  position: sticky; 
                  right: 60px; 
                  background-color: #fff; 
                  outline: 2px solid #dee2e6;
                  outline-offset: -1px;
                  min-width: 66px;

            }

            .toast-top-right {
                  top: 20%;
                  margin-right: 21px;
            }
      
            .tableFixHead {
                  overflow: auto;
                  height: 100px;
            }
      
            .tableFixHead thead th {
                  position: sticky;
                  top: -1px;
                  background-color: #fff;
                  outline: 2px solid #dee2e6;
                  outline-offset: -1px;
            
            }
      
            .isHPS {
      
                  position: sticky;
                  top: 82px !important;
                  background-color: #fff;
                  outline: 2px solid #dee2e6 ;
                  outline-offset: -1px;
            
            }
            
      </style>
      <div class="modal fade" id="grade_logs" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                        <div class="modal-header bg-primary">
                              <h4 class="modal-title">Grade Logs</h4>
                              <button type="button" class="close" id="close_logs">
                              <span aria-hidden="true">×</span></button>
                        </div>
                        <div class="modal-body table-responsive" style="height: 400px">
                              <div class="col-md-12">
                                    <div class="timeline">
                                          @if(count($gradelogs) > 0)
                                                @foreach ($gradelogs as $item)
                                                      <div class="time-label">
                                                      <span class="
                                                            @if($item->status == 1)
                                                                  bg-success
                                                            @elseif($item->status == 2)
                                                                  bg-primary
                                                            @elseif($item->status == 3)
                                                                  bg-secondary
                                                            @elseif($item->status == 4)
                                                                  bg-warning
                                                            @endif
                                                      ">{{\Carbon\Carbon::create($item->createddatetime)->isoFormat('MMMM DD, YYYY')}}</span>
                                                      </div>
                                                      <div>
                                                            <i class="fas fa-user bg-green"></i>
                                                            <div class="timeline-item">
                                                                  <span class="time"><i class="fas fa-clock"></i> {{\Carbon\Carbon::create($item->createddatetime)->isoFormat('hh:mm a')}}</span>
                                                                  <h3 class="timeline-header no-border"><a href="#">{{$item->name}}
                                                                  </a>
                                                                        @if($item->status == 1)
                                                                              submitted grades
                                                                        @elseif($item->status == 2)
                                                                              approved grades
                                                                        @elseif($item->status == 3)
                                                                              posted grades
                                                                        @elseif($item->status == 4)
                                                                              add grades to pending
                                                                        @endif
                                                                  </h3>
                                                            </div>
                                                      </div>
                                                @endforeach
                                          @else
                                                <div>
                                                      <div class="timeline-item">
                                                            <h3 class="timeline-header no-border">No Available logs</h3>
                                                      </div>
                                                </div>
                                          @endif
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
      <script>
            $(document).ready(function(){
                  $(document).on('click','#view_logs',function(){
                        $('#grade_logs').modal()
                  })
                  $(document).on('click','#close_logs',function(){
                        $('#grade_logs').modal('hide')
                  })
            })
      </script>
       <table class="table table-bordered">
            <tr>
                  <td></td>
                  <td colspan="2">
                        <button class="btn btn-primary btn-sm float-right" id="view_logs"><i class="fas fa-history"></i> Grade Satus Logs</button>
                  </td>
            </tr>
            <tr>
                  <th>Status</th>
                  <th>Date Submitted</th>
                  <th>Date Posted</th>
            </tr>
            <tr>
                  <td>
                        @if($checkStatus->$statusfield == 1)
                              Submitted
                        @elseif($checkStatus->$statusfield == 2)
                              Approved
                        @elseif($checkStatus->$statusfield == 3)
                              Posted
                        @elseif($checkStatus->$statusfield == 4)
                              Pending
                        @else
                              Not Submitted
                        @endif
                  </td>
                  <td>
                        @if($checkStatus->$submittedField != null)
                              {{\Carbon\Carbon::create($checkStatus->$submittedField)->isoFormat('MMM DD, YYYY hh:mm a')}}
                        @endif
                  </td>
                  <td>
                        @if($checkStatus->$postedField != null)
                              {{\Carbon\Carbon::create($checkStatus->$postedField)->isoFormat('MMM DD, YYYY hh:mm a')}}
                        @endif
                  </td>
            </tr>
      </table>
      @foreach ($gs as $gsitem)
            <table class="table mt-4 mb-0">
                  <tr>
                        <td>{{$gsitem->description}}
                              @if($gsitem->trackid == 1)
                                    - Academic
                              @elseif($gsitem->trackid == 2)
                                    - TVL
                              @endif
                        </td>
            </table>

            <div class="col-md-12 table-responsive p-0 " style="height: 470px" id="pstable_holder">
                  @php
                        $gsdetail = collect($grading_system_detail)->where('trackid',$gsitem->trackid)->first()->gsdetail;
                  @endphp
                
                  <table class="gradestable table tableFixHead">
                        <thead>
                              <tr>
                                    <th style="min-width:210px !important; z-index:101;background-color:#fff"></th>
                                    @foreach ($gsdetail as $item)
                                          <th colspan="{{$item->items + 3}}" class="text-center border" style="font-size: 11px">{{$item->description}} ( {{$item->value}} %)</th>
                                    @endforeach
                        
                                    <th style=" z-index:100;background-color:#fff" class="ig"></th>
                                    <th style=" z-index:100;background-color:#fff"></th>
                              </tr>
                              <tr>
                                    <th style="min-width:210px !important; z-index:100;background-color:#fff; top: 38px;"></th>
                                    @foreach ($gsdetail as $gsditem)
                                          @for ($x = 1; $x <= $gsditem->items; $x++)
                                                <th style="top: 38px; background-color:#fff;" class="text-center align-middle border" >{{$x}}</th>
                                          @endfor
                                          <th style="top: 38px; background-color:#fff; " class="text-center align-middle border ps" >Total</th>
                                          <th style="top: 38px; background-color:#fff;" class="text-center align-middle border ps" >PS</th>
                                          <th style="top: 38px; background-color:#fff;" class="text-center align-middle border ws">WS</th>
                                    @endforeach
      
                                    <th style="width:60px; top: 38px; z-index:100;background-color:#fff" class="text-center align-middle ig">IG</th>
                                    <th style="width:60px; top: 38px; z-index:100;background-color:#fff" class="text-center align-middle">QG</th>
                              </tr>   
                        </thead>
                        <tbody>
                              <tr data-gs="{{collect($gsheader)->first()->gsheader->nogs}}"  data-id="{{collect($gsheader)->first()->gsheader->id}}" class="studtr" data-gsid="{{$gsitem->id}}">
                                    <th style="width:60px; z-index:101;background-color:#fff" class="isHPS">Highes Possible Score</th>
                                    @if(count(collect($gsheader)->where('trackid',$gsitem->trackid)->first()->gsheader->gsdget) > 0)
                                          @foreach ($gsdetail as $gsditem)
                                          
                                                      @php
                                                            $counter = 1;
                                                      @endphp
                                                      @foreach (collect(collect($gsheader)->where('trackid',$gsitem->trackid)->first()->gsheader->gsdget)->where('gsdid',$gsditem->id) as $studgsditem)
                                                            @for ($x = 1; $x <= $gsditem->items; $x++)
                                                                  @php
                                                                        $field = 'g'.$x.'q'.$quarter;
                                                                  @endphp
                                                                  <td class="text-center align-middle border  hps isHPS" 
                                                                        data-id="{{$studgsditem->id}}" 
                                                                        data-field="{{$field}}"
                                                                        data-studid="{{collect($gsheader)->where('trackid',$gsitem->trackid)->first()->gsheader->id}}"
                                                                        data-gsid="{{$studgsditem->gsdid}}"
                                                                        style=" z-index:100;background-color:#fff"
                                                                        >{{$studgsditem->$field}}</td>
                                                            @endfor
                                                      @endforeach
                                                      @php
                                                            $psfield = 'psq'.$quarter;
                                                            $wsfiled = 'wsq'.$quarter;
                                                            $qtotal = 'q'.$quarter.'total';
                                                      @endphp
                                                  
                                                      <th class="text-center align-middle border ps isHPS" >{{$studgsditem->$qtotal}}</th>
                                                      <th class="text-center align-middle border ps isHPS" >100</th>
                                                      <th class="text-center align-middle border ws isHPS">{{$gsditem->value}}%</th>
                                          @endforeach
                                          <th style="width:60px; z-index:100;background-color:#fff" class="text-center align-middle border ig isHPS">100</th>
                                          <th style="width:60px; z-index:100;background-color:#fff" class="isHPS">100</th>
                                    @else
                                          <th colspan="{{ $totalItems}}" class="border text-left text-danger isHPS">Grades detail is not yet generated.</th>
                                          <th class="text-center align-middle border ig isHPS" style="width:60px; top: 38px; z-index:100;background-color:#fff"></th>
                                          <th class="text-center align-middle border isHPS" style="width:60px; top: 38px; z-index:100;background-color:#fff"></th>
                                    @endif
                              
                              </tr>
                              @foreach (collect($students)->where('trackid',$gsitem->trackid) as $item)
                                    <tr data-gs="{{$item->nogs}}"  data-id="{{$item->id}}" class="studtr" data-gsid="{{$gsitem->id}}">
                                          <th>{{$item->lastname.', '.$item->firstname}}</th>
                                          @if(count($item->gsdget) > 0)
                                                @foreach ($gsdetail as $gsditem)
      
                                                      @php
                                                            $counter = 1;
                                                      @endphp
      
                                                      @foreach (collect($item->gsdget)->where('gsdid',$gsditem->id) as $studgsditem)
                                                            @for ($x = 1; $x <= $gsditem->items; $x++)
                                                                  @php
                                                                        $field = 'g'.$x.'q'.$quarter;
                                                                  @endphp
                                                                  <td class="text-center align-middle border" 
                                                                        data-id="{{$studgsditem->id}}" 
                                                                        data-field="{{$field}}"
                                                                        data-gsid="{{$studgsditem->gsdid}}"
                                                                        data-studid="{{$item->id}}"
                                                                        >{{$studgsditem->$field}}</td>
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
                                                            <th class="text-center align-middle border ps" >{{$studgsditem->$qtotal}}</th>
                                                            <th class="text-center align-middle border ps" >{{$studgsditem->$psfield}}</th>
                                                            <th class="text-center align-middle border ws">{{$studgsditem->$wsfiled}}</th>
                                                      @endforeach
                                                @endforeach
                                                <th class="text-center align-middle border ig">{{$ig}}</th>
                                                <th class="text-center align-middle border">{{$qg}}</th>
                                          @else
                                                <th colspan="{{ $totalItems }}" class="border text-left text-danger">Grades detail is not yet generated.</th>
                                                <th class="text-center align-middle border ig"></th>
                                                <th class="text-center align-middle border "></th>
                                          @endif
                                          
                                    </tr>
                              
                              @endforeach
                        </tbody>
                  </table>

                
           
  
            {{-- <table class="gradestable table tableFixHead" data-id="{{$gs->id}}" data-desc="{{$gs->description}}">
                  <thead>
                        <tr>
                              <th style="min-width:210px !important; z-index:101;background-color:#fff"></th>
                              @foreach ($grading_system_detail as $item)
                                    <th colspan="{{$item->items + 3}}" class="text-center border" style="font-size: 11px">{{$item->description}} ( {{$item->value}} %)</th>
                              @endforeach
                  
                              <th style=" z-index:100;background-color:#fff" class="ig"></th>
                              <th style=" z-index:100;background-color:#fff"></th>
                        </tr>
                        <tr>
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
                        </tr>   
                  </thead>
                  <tbody>
                        <tr data-gs="{{$gsheader->nogs}}"  data-id="{{$gsheader->id}}" class="studtr">
                              <th style="width:60px; z-index:101;background-color:#fff" class="isHPS">Highes Possible Score</th>
                              @if(count($gsheader->gsdget) > 0)
                                    @foreach ($grading_system_detail as $gsditem)
                                    
                                                @php
                                                      $counter = 1;
                                                @endphp
                                                @foreach (collect($gsheader->gsdget)->where('gsdid',$gsditem->id) as $studgsditem)
                                                      @for ($x = 1; $x <= $gsditem->items; $x++)
                                                            @php
                                                                  $field = 'g'.$x.'q'.$quarter;
                                                            @endphp
                                                            <td class="text-center align-middle border  hps isHPS" 
                                                                  data-id="{{$studgsditem->id}}" 
                                                                  data-field="{{$field}}"
                                                                  data-studid="{{$gsheader->id}}"
                                                                  data-gsid="{{$studgsditem->gsdid}}"
                                                                  style=" z-index:100;background-color:#fff"
                                                                  >{{$studgsditem->$field}}</td>
                                                      @endfor
                                                @endforeach
                                                @php
                                                      $psfield = 'psq'.$quarter;
                                                      $wsfiled = 'wsq'.$quarter;
                                                      $qtotal = 'q'.$quarter.'total';
                                                @endphp
                                                <th class="text-center align-middle border ps isHPS" >{{$studgsditem->$qtotal}}</th>
                                                <th class="text-center align-middle border ps isHPS" >100</th>
                                                <th class="text-center align-middle border ws isHPS">{{$gsditem->value}}%</th>
                                    @endforeach
                                    <th style="width:60px; z-index:100;background-color:#fff" class="text-center align-middle border ig isHPS">100</th>
                                    <th style="width:60px; z-index:100;background-color:#fff" class="isHPS">100</th>
                              @else
                                    <th colspan="{{ $totalItems}}" class="border text-left text-danger isHPS">Grades detail is not yet generated.</th>
                                    <th class="text-center align-middle border ig isHPS" style="width:60px; top: 38px; z-index:100;background-color:#fff"></th>
                                    <th class="text-center align-middle border isHPS" style="width:60px; top: 38px; z-index:100;background-color:#fff"></th>
                              @endif
                        
                        </tr>
                        @foreach ($students as $item)
                              <tr data-gs="{{$item->nogs}}"  data-id="{{$item->id}}" class="studtr">
                                    <th>{{$item->lastname.', '.$item->firstname}}</th>
                                    @if(count($item->gsdget) > 0)
                                          @foreach ($grading_system_detail as $gsditem)

                                                @php
                                                      $counter = 1;
                                                @endphp

                                                @foreach (collect($item->gsdget)->where('gsdid',$gsditem->id) as $studgsditem)
                                                      @for ($x = 1; $x <= $gsditem->items; $x++)
                                                            @php
                                                                  $field = 'g'.$x.'q'.$quarter;
                                                            @endphp
                                                            <td class="text-center align-middle border" 
                                                                  data-id="{{$studgsditem->id}}" 
                                                                  data-field="{{$field}}"
                                                                  data-gsid="{{$studgsditem->gsdid}}"
                                                                  data-studid="{{$item->id}}"
                                                                  >{{$studgsditem->$field}}</td>
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
                                                      <th class="text-center align-middle border ps" >{{$studgsditem->$qtotal}}</th>
                                                      <th class="text-center align-middle border ps" >{{$studgsditem->$psfield}}</th>
                                                      <th class="text-center align-middle border ws">{{$studgsditem->$wsfiled}}</th>
                                                @endforeach
                                          @endforeach
                                          <th class="text-center align-middle border ig">{{$ig}}</th>
                                          <th class="text-center align-middle border">{{$qg}}</th>
                                    @else
                                          <th colspan="{{ $totalItems }}" class="border text-left text-danger">Grades detail is not yet generated.</th>
                                          <th class="text-center align-middle border ig"></th>
                                          <th class="text-center align-middle border "></th>
                                    @endif
                                    
                              </tr>
                        
                        @endforeach
                  </tbody>
            </table> --}}

      </div>
      @endforeach
@else

      <table class="table">
            <tr><th class="text-center">No Students Enrolled</th></tr>
      </table>

@endif

@if($checkStatus->$statusfield == null || $checkStatus->$statusfield == 4)
      <script>
            $(document).ready(function(){

                  @if($nogscount > 0)

                        $('#g_gsd_h').removeAttr('hidden')
                        $('#no_gs_count').text('{{$nogscount}}')

                  @else

                        $('#g_gsd_h').attr('hidden','hidden')
                        $('#no_gs_count').text('{{$nogscount}}')

                  @endif


                  var currentIndex 
                  var start;
                  var string

                  $('.gradestable td').unbind().click(function(){

                        $('td[data-toggle="start"]').removeAttr('style')
                        $('td[data-toggle="start"]').removeAttr('data-toggle')
                        $(currentIndex).removeAttr('style')
                  
                        $(this).attr('data-toggle','start')

                        start = $('td[data-toggle="start"]')[0];
                              start.focus();
                              start.style.backgroundColor = 'green';
                              start.style.color = 'white';

                        dotheneedful(this);

                  })

                  function dotheneedful(sibling) {

                        if (sibling != null) {

                              currentIndex = sibling
                              start.focus();
                              start.style.backgroundColor = '';
                              start.style.color = '';
                              sibling.focus();
                              sibling.style.backgroundColor = 'green';
                              sibling.style.color = 'white';
                              start = sibling;
                              string = $(currentIndex)[0].innerText

                        
                        }


                  }

                  document.onkeydown = checkKey;

                  function checkKey(e) {

                        e = e || window.event;

                        if (e.keyCode == '38' && currentIndex != undefined)  {

                              var idx = start.cellIndex;
                              var nextrow = start.parentElement.previousElementSibling;
                              $('#curText').text(string)
                        
                              if (nextrow != null) {
                                    var sibling = nextrow.cells[idx];
                                    string = sibling.innerText;
                                    dotheneedful(sibling);
                              }

                              


                        } else if (e.keyCode == '40' && currentIndex != undefined) {

                              var idx = start.cellIndex;
                              var nextrow = start.parentElement.nextElementSibling;

                              if (nextrow != null) {
                                    var sibling = nextrow.cells[idx];
                                    string = sibling.innerText;
                                    dotheneedful(sibling);
                              }


                        } else if (e.keyCode == '37' && currentIndex != undefined) {

                              var sibling = start.previousElementSibling;
                        
                              if($(sibling)[0].cellIndex != 0){
                                    string = sibling.innerText;
                                    dotheneedful(sibling);
                              }


                              

                        } else if (e.keyCode == '39' && currentIndex != undefined) {

                              var sibling = start.nextElementSibling;

                              if($(sibling)[0].cellIndex != 0){
                                    string = sibling.innerText;
                                    dotheneedful(sibling);
                              }

                        }
                        else if( e.key == "Backspace" && currentIndex != undefined){
                                          
                              string = currentIndex.innerText

                              string = string.slice(0 , -1);

                              if(string.length == 0){
                                    string = '0';
                                    currentIndex.innerText = 0
                              }else{
                                    currentIndex.innerText = parseInt(string)
                                    inputIndex = currentIndex
                              }

                              if($(currentIndex).hasClass('hps')){

                                    $('td[data-field="'+$(currentIndex).attr('data-field')+'"][data-gsid="'+$(currentIndex).attr('data-gsid')+'"]').addClass('ge')

                              }else{

                                    $(currentIndex).addClass('ge')
                                    
                              }
                        

                        }
                        else if ( e.key >= 0 && e.key <= 9 && currentIndex != undefined) {

                              if($(currentIndex).is('td')){

                                    if($(currentIndex).hasClass('hps')){

                                          $('td[data-field="'+$(currentIndex).attr('data-field')+'"][data-gsid="'+$(currentIndex).attr('data-gsid')+'"]').addClass('ge')

                                    }else{

                                          $(currentIndex).addClass('ge')
                                    }

                                    if(string == 0){
                                          string = ''
                                    }
                                    string += e.key;
                                    
                                    currentIndex.innerText = string

                              }

                        

                        }
                              
                              
                  }

            })
      </script>
@else

      <script>
            $(document).ready(function(){
                  $('#save_grades_hs').attr('hidden','hidden')
            })
      </script>

@endif