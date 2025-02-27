@if(collect($students)->count() == 0)
      <div class="modal-body p-0 table-responsive" style="height: 400px">
            <div class="card mr-2 ml-2" style="top:35%"> 
                  <div class="card-body bg-success text-center text-lg text-bold">
                        NO STUDENTS ENROLLED
                  </div>
            </div>                 
      </div>

@else
     
      @php
            $totalColCount = 0;
      @endphp

      @if(count($gradesetup) > 0 && collect($gradesetup)->sum('percentage') == 100)
           
                  @foreach($gradesetup as $item)
                        @php
                              $totalColCount = $totalColCount + ( $item->items + 2 );
                        @endphp
                  @endforeach

                  <table class="table table-bordered gradetable table-hover table-striped table-sm" style="min-width:400px; font-size:13px; ">
                        <thead>
                             
                              <tr >
                                    <th class="header_three align-middle">TERM: <span id="termTextHolder"></span></th>
                                    <th class="header_three align-middle">STATUS: <span id="statusHolder"></span></th>
                                    {{-- <th class="header_three" colspan="{{$totalColCount - 4}}" ></th>
                                    <th class="header_three text-center">
                                          <div class="dropdown">
                                                <button class="btn btn-primary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                      <i class="fas fa-tasks"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                      <a class="dropdown-item" href="#" id="changeTerm">CHANGE TERM</a>
                                                      <a class="dropdown-item" href="#" id="submit_term_grade">SUBMIT TERM</a>
                                                </div>
                                          </div>
                                    </th> --}}
                              </tr>
                              {{-- <tr>
                                    <th class="header_two"></th>
                                    @foreach($gradesetup as $item)
                                          <th colspan="{{$item->items + 2}}" class="text-center header_two" style="font-size:11px">{{Str::limit($item->setupDesc, $limit = 15, $end = '...')}} ( {{$item->percentage}} %)</th>
                                          
                                    @endforeach
                                    <th class="header_two"></th>
                              </tr>
                              <tr >
                                    <th style="z-index: 1 !important " class="header_one"></th>
                                    @foreach($gradesetup as $item)
                                          @for($x = 0; $x < $item->items; $x ++)
                                                <th class="text-center p-1 align-middle header_one"  style="font-size:11px" style="min-width: 40px;">{{$x+1}}</th>
                                                
                                          @endfor
                                          <th class="bg-secondary text-center align-middle header_one" style="font-size:11px">TS</th>
                                          <th class="bg-success text-center align-middle header_one" style="font-size:11px">WS</th>
                                    @endforeach
                        
                                    <th class="text-center header_one">FG</th>
                              </tr> --}}
                              
                               {{-- --------------------- V1 ---------------- --}}

                               <th>Student</th>
                               <th>Final Grade</th>
                             
                        </thead>
                        <tbody>

                              {{-- <tr id="hps" class="hps" name="hpsdetail">
                                    <th class="isHPS" style="z-index: 1 !important" >HIGHEST SCORE  <button class="btn btn-primary btn-xs float-right" id="updateGradeList"><i class="fas fa-copy" ></i></button></th>

                                    @foreach($gradesetup as $item)
                                          @php
                                                $total = 0;
                                         
                                          @endphp
                                          @for($x = 1; $x <= $item->items; $x ++)
                                                @php
                                                      $fieldstring = 'sg'.$x;
                                                      $total += $item->$fieldstring;
                                                @endphp
                                                <td class="text-center p-2 align-middle tdhpsdetail isHPS" style="min-width: 40px;" data-value="{{$item->id}} {{$fieldstring}}" data-hd="{{$item->id}}" data-fd="{{$fieldstring}}">{{$item->$fieldstring}}</td>
                                          @endfor
                                          @php
                                                $item->total = $total;
                                           
                                          @endphp
                                          <th  class="bg-secondary text-center align-middle isHPS" style="min-width: 40px;">{{$total}}</th>
                                          <th  class="bg-success text-center align-middle isHPS" style="min-width: 40px;">{{$item->percentage}}</th>
                                       
                                    @endforeach
                                    <th class="isHPS text-center align-middle">100</th>
                              
                              </tr>
                        
                              @php
                                    $male = 0;
                                    $female = 0;
                              @endphp
                        
                              @foreach ($students as $item)

                                    @if($male == 0 && $item->gender == 'MALE')
                                          <tr class="bg-secondary">
                                                <th class="text-dark bg-secondary">MALE</th>
                                                @foreach($gradesetup as $gsitem)
                                                      @for($x = 1; $x <= $gsitem->items; $x ++)
                                                            <th></th>
                                                      @endfor
                                                      <th></th>
                                                      <th></th>
                                                @endforeach
                                                <th class="bg-secondary"></th>
                                          </tr>
                                          @php
                                                $male = 1;
                                          @endphp
                                    @elseif($female == 0  && $item->gender == 'FEMALE')
                                          <tr class="bg-secondary">
                                                <th class="text-dark bg-secondary">FEMALE</th>
                                                @foreach($gradesetup as $gsitem)
                                                      @for($x = 1; $x <= $gsitem->items; $x ++)
                                                            <th></th>
                                                      @endfor
                                                      <th></th>
                                                      <th></th>
                                                @endforeach
                                                <th class="bg-secondary"></th>
                                          </tr>
                                          @php
                                                $female = 1;
                                          @endphp
                                    @endif
                                    <tr name="gradesdetail" class="gradedetail">
                                          <th >{{Str::limit($item->lastname.', '. $item->firstname, $limit = 20, $end = '...') }} 
                                                @if(collect($studentgrades)->where('studid',$item->studid)->count() == 0)
                                                <button class="btn btn-primary btn-xs float-right updateGradeDetail"   data-value="{{$item->studid}}" hidden><i class="fas fa-copy" ></i></button>
                                                @endif
                                          </th>
                                          @php
                                                $index = 0;
                                                $fg = 0;
                                          @endphp

                                          @foreach(collect($studentgrades)->where('studid',$item->studid) as $gradeitem)

                                                @php
                                                      $studentGradeTotal = 0;
                                                      $fg = $gradeitem->ig
                                                @endphp
                                                
                                                @for($x = 1; $x <= $gradeitem->items; $x ++)

                                                      @php
                                                            $fieldstring = 'sg'.$x;
                                                            $studentGradeTotal += $gradeitem->$fieldstring;
                                                      @endphp

                                                      <td class="text-center tddetail p-2 align-middle" data-value="{{$gradeitem->headerid}} {{$fieldstring}} {{$item->studid}}" data-studid="{{$item->studid}}" data-hd="{{$gradeitem->headerid}}" data-fld="{{$fieldstring}}" style="min-width: 40px;">{{$gradeitem->$fieldstring}}</td>

                                                @endfor
                                                <td class="bg-secondary text-center align-middle ts" data-studid="{{$item->studid}}" data-hd="{{$gradeitem->headerid}}" style="min-width: 40px;">{{$gradeitem->ts}}</td>
                                                <td class="bg-success text-center align-middle ws" data-studid="{{$item->studid}}" data-hd="{{$gradeitem->headerid}}" style="min-width: 40px;">{{$gradeitem->ws}}</td>
                                          
                                          @endforeach

                                          @if(collect($studentgrades)->where('studid',$item->studid)->count() > 0)
                                                <th class="text-center align-middle fg" data-studid="{{$item->studid}}">{{number_format($fg,2)}}</th>
                                          @endif
                                    </tr>
                              @endforeach --}}

                              {{-- ------------------ V1 ---------------------------------- --}}

                              @php
                                    $male = 0;
                                    $female = 0;
                              @endphp
                              @foreach ($students as $item)
                                    @if($male == 0 && $item->gender == 'MALE')
                                          <tr class="bg-secondary">
                                                <th class="text-dark bg-secondary">MALE</th>
                                                <th class="bg-secondary"></th>
                                          </tr>
                                          @php
                                                $male = 1;
                                          @endphp
                                    @elseif($female == 0  && $item->gender == 'FEMALE')
                                          <tr class="bg-secondary">
                                                <th class="text-dark bg-secondary">FEMALE</th>
                                                <th class="bg-secondary"></th>
                                          </tr>
                                          @php
                                                $female = 1;
                                          @endphp
                                    @endif
                                    <tr>
                                          <td>{{$item->lastname.', '. $item->firstname}}</td>
                                          @php
                                                $first_loop = 0;
                                                $ig = 0;
                                                $info = array();
                                          @endphp
                                          @foreach(collect($studentgrades)->where('studid',$item->studid) as $gradeitem)
                                                @if($first_loop == 0)
                                                      @if($gradeitem->ig != 0)
                                                            @php  
                                                                  $info = $gradeitem;
                                                                  $ig = number_format($gradeitem->ig);
                                                            @endphp
                                                      @endif
                                                      @php
                                                            $first_loop = 1;
                                                      @endphp
                                                @endif
                                          @endforeach 
                                          <td>

                                              {{$ig}} - {{collect($info)}}
                                          </td>
                                    </tr>
                              @endforeach
                        </tbody>
                  </table>

            </div>


      @if($canEdit)

            <script>
                  $(document).ready(function(){

                        var canUpdateGrade = true
                        var isSaved = false;
                        var isvalidHPS = true;
                        var hps = []
                        var currentIndex 
                        var string = ''
                        var inputIndex
                        var totalig = 0;
                        var igdata = [];
                        var selectedQuarter = $('#termTextHolder').attr('data-id')
                        var required_saved = false
                        var update_click = false
            

                        const Toast = Swal.mixin({
                              toast: true,
                              position: 'top-end',
                              showConfirmButton: false,
                              timer: 3000
                        });

                        $('#updateGrade').attr('hidden','hidden')

                        var grade_setup = @json($gradesetup)

                        $(document).on('click','#updateGrade',function(){

                              var data = []
                              var hps = []
                              string = ''
                              var check_new_hps = checkHPS()

                              console.log(check_new_hps)

                              var count = 0;

                              if(!check_new_hps){

                                    Toast.fire({
                                          type: 'error',
                                          title: 'HPS is lower than student highest score!'
                                    })

                              }else{

                                    if(required_saved){

                                          $('#proccess_count').empty()
                                          temp_counter = 0
                                          update_click = true
                                          $('#proccess_count_modal').modal()
                                          $('#proccess_done').attr('hidden','hidden')
                                         
                                          update_hps()

                                    }

                                   
                              }

                            

                        })

                        function checkHPS(){

                              validHPS = true;

                              var max = hps.find(o => o.field === $(currentIndex).attr('class'))
                              try {

                                    var highest = 0;

                                    $('.gradedetail').each(function(){

                                          if( $(this)[0].cells[$(currentIndex)[0].cellIndex].innerText > highest ){

                                                highest = $(this)[0].cells[$(currentIndex)[0].cellIndex].innerText; 

                                          }
                                    
                                    })

                                    if( parseInt($('#hps')[0].cells[$(currentIndex)[0].cellIndex].innerText) < highest){

                                          validHPS  = false

                                    }

                                    if(!validHPS){

                                          Toast.fire({
                                                type: 'error',
                                                title: 'HPS is lower than student highest score!'
                                          })
                                          
                                    }

                                    return validHPS;

                              }
                              catch(err) {

                                    validHPS = true;

                                    return validHPS;

                              }


                        }

                       

                        $('.gradetable tbody td').unbind().click(function(){

                              if($(this).hasClass('isHPS')){

                                    

                              }
                              if(required_saved && !$(this).hasClass('isHPS')){

                                    Swal.fire({
                                          type: 'warning',
                                          text: 'Please click "UPDATE GRADES" button to proceed!',
                                    })

                                    return false

                              }

                              if(canUpdateGrade){

                                    if(currentIndex != undefined){

                                          if( $(currentIndex).hasClass('isHPS') ){

                                                isvalidHPS = checkHPS()

                                          }else{

                                                isSaved = true;

                                          }
                                          
                                          if(isvalidHPS){
                                    
                                                      string = $(this).text();
                                                      currentIndex = this;

                                                      if($('#start').length > 0 ){

                                                            dotheneedful(this);

                                                      }

                                                      $('td').removeAttr('style');
                                                      $('#start').removeAttr('id')
                                                      
                                                      $(this).attr('id','start')

                                                      $('td').css('min-width','40px')

                                                      var start = document.getElementById('start');
                                                                        start.focus();
                                                                        start.style.backgroundColor = 'green';
                                                                        start.style.color = 'white';

                                          }
                                    }
                                    else{

                                    
                                                string = $(this).text();

                                                currentIndex = this;

                                                if($('#start').length > 0 ){

                                                      dotheneedful(this);

                                                }

                                                $('td').removeAttr('style');

                                                $('#start').removeAttr('id')
                                                
                                                $(this).attr('id','start')

                                                var start = document.getElementById('start');
                                                                  start.focus();
                                                                  start.style.backgroundColor = 'green';
                                                                  start.style.color = 'white';
                                                                  

                                                $('td').css('min-width','40px')

                                    }

                              }

                        })

                        document.onkeydown = checkKey;

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
                                    $('#message').empty();
                                    string = $(currentIndex)[0].innerText

                              }


                        }


                        var storeheader;
                        var storetudid;
                        var storets;
                        var storews;
                        var storeig;
                        var storeterm;
                        var storevalue;
                        var storefield;
                        var temp_data = [];

                        function calculate_final(){

                              var header = $(currentIndex).attr('data-hd')
                              var studid = $(currentIndex).attr('data-studid')
                              var total_score = 0
                              var student_total = 0
                              var percentage = grade_setup.filter(x=> x.id == header)[0].percentage
                              var ws = 0
                              var fg = 0
                              var value = $(currentIndex).text()

                              storefield = $(currentIndex).attr('data-fld')

                              $('.isHPS[data-hd="'+header+'"]').each(function(){
                                    total_score += parseInt($(this).text())
                              })

                              $('.tddetail[data-hd="'+header+'"][data-studid="'+studid+'"]').each(function(){
                                    student_total += parseInt($(this).text())
                              })

                            

                              ws = ( ( student_total / total_score ) * 100 ) * ( percentage / 100 )

                              $('.ts[data-hd="'+header+'"][data-studid="'+studid+'"]').text(student_total)



                              $('.ws[data-hd="'+header+'"][data-studid="'+studid+'"]').text(ws.toFixed(2))

                              $('.ws[data-studid="'+studid+'"]').each(function(){

                                    fg += parseFloat($(this).text())

                              })

                              $('.fg[data-studid="'+studid+'"]').text(fg.toFixed(2))
                           
                              storeheader = header;
                              storetudid = studid;
                              storets = student_total;
                              storews = ws.toFixed(2);
                              storeig = fg.toFixed(2);
                              storeterm = selectedQuarter;
                              storevalue = value

                              

                              if(!required_saved){

                                    console.log('a')
                                    temp_data.push({
                                          storeheader:storeheader,
                                          storetudid:storetudid,
                                          storets:storets,
                                          storews:storews,
                                          storeig:storeig,
                                          storeterm:storeterm,
                                          storevalue:storevalue,
                                          storefield:storefield,
                                          status:0,
                                       
                                    })

                                    store_grade()

                              }else if(update_click){
                                
                                    store_grade_continues()
                                    
                              }

                             

                        }

                        var hps_storeheader;
                        var hps_storevalue;
                        var hps_storefield;
                        var hps_storeterm;
                        var temp_max;
                        var temp_counter = 0;
                        var temp_currentIndex;

                        function calculate_hps(){
                              
                           
                              var header = $(currentIndex).attr('data-hd')
                              temp_currentIndex = currentIndex
                              var temp_header = currentIndex
                              var counter = 0;
                              var field = $(currentIndex).attr('data-fd')
                              var tdlength = $('.tddetail[data-hd="'+header+'"][data-fld="'+field+'"]').length

                              temp_max = tdlength;
                            
                              $('.tddetail[data-hd="'+header+'"][data-fld="'+field+'"]').each(function(){
                                    currentIndex = $(this)
                                    calculate_final()
                                    counter += 1
                                   
                                    if(tdlength == counter){

                                          currentIndex = temp_header
                                         
                                    }
                              })

                        }

                        var is_done = true;

                        function store_grade(){

                              temp_data = temp_data.filter(x => x.status == 0)

                              if(is_done){


                                    $.each(temp_data,function(a,b){

                                          if(is_done){

                                                $.ajax({
                                                      type:'GET',
                                                      url:'/college/teacher/store/gradesv2',
                                                      data:{
                                                            field:b.storefield,
                                                            value:b.storevalue,
                                                            studid:b.storetudid,
                                                            headerid:b.storeheader,
                                                            ig:b.storeig,
                                                            term:b.storeterm,
                                                            ws:b.storews,
                                                            ts:b.storets,
                                                      },
                                                      success:function(data) {

                                                            b.status = 1
                                                            is_done = true;
                                                            store_grade()

                                                            temp_counter += 1

                                                            $('#proccess_count').text(temp_counter +' / '+temp_max)

                                                            if(temp_counter == temp_max){

                                                                  $('#proccess_done').removeAttr('hidden')

                                                            }

                                                      }

                                                })

                                          }

                                          is_done = false

                                    })

                              }

                        }

                        function store_grade_continues(){

                              $.ajax({
                                    type:'GET',
                                    url:'/college/teacher/store/gradesv2',
                                    data:{
                                          field:storefield,
                                          value:storevalue,
                                          studid:storetudid,
                                          headerid:storeheader,
                                          ig:storeig,
                                          term:storeterm,
                                          ws:storews,
                                          ts:storets,
                                    },
                                    success:function(data) {

                                          temp_counter += 1

                                          $('#proccess_count').text(temp_counter +' / '+temp_max)

                                          if(temp_counter == temp_max){

                                                $('#proccess_done').removeAttr('hidden')
                                                update_click = false
                                                required_saved = false
                                                currentIndex = temp_currentIndex
                                                $('#updateGrade').attr('hidden','hidden')

                                          }
                                    }
                              })


                        }

                        function update_hps(){

                              var selected_hps_length = $('.selected_hps').length
                              var hps_proccess_counter = 0;

                              $('.selected_hps').each(function(a,b){

                                    hps_storeheader = $(this).attr('data-hd')
                                    hps_storevalue = $(this).text()
                                    hps_storefield = $(this).attr('data-fd')
                                    hps_storeterm = selectedQuarter

                                    var hps_cell = $(this)

                                    $.ajax({
                                          type:'GET',
                                          url:'/college/teacher/update/hps',
                                          data:{
                                                field:hps_storefield,
                                                value:hps_storevalue,
                                                headerid:hps_storeheader,
                                                term:storeterm,
                                          },
                                          success:function(data) {
                                                
                                                hps_proccess_counter += 1;
                                                $(hps_cell).removeClass('selected_hps')
                                                if(selected_hps_length == hps_proccess_counter){

                                                      hps_storeheader = null
                                                      hps_storevalue = null
                                                      hps_storefield = null
                                                      hps_storeterm = null

                                                      calculate_hps()
                                                }
                                                
                                          
                                          }

                                    })

                              })

                            


                        }




                        function checkKey(e) {

                              if(canUpdateGrade){

                                    var higher = false;
                                          
                                    e = e || window.event;

                                    if (e.keyCode == '38' && currentIndex != undefined)  {

                                          if( $(currentIndex).hasClass('isHPS')){
                                                isvalidHPS = checkHPS()
                                                if(isvalidHPS){
                                                      
                                                }
                                          }else{
                                                isSaved = true;
                                          }
                                          
                                          if(isvalidHPS){

                                                var idx = start.cellIndex;
                                                var nextrow = start.parentElement.previousElementSibling;
                                                $('#curText').text(string)
                                          
                                                if (nextrow != null) {
                                                      
                                                      var sibling = nextrow.cells[idx];
                                                      string = sibling.innerText;
                                                      dotheneedful(sibling);
                                                }

                                          }


                                    } else if (e.keyCode == '40' && currentIndex != undefined) {

                                          if(required_saved){
                                                
                                                Swal.fire({
                                                      type: 'warning',
                                                      text: 'Please click "UPDATE GRADES" button to proceed!',
                                                })

                                                return false;

                                          }

                                          if( $(currentIndex).hasClass('isHPS')){
                                                isvalidHPS = checkHPS()
                                                if(isvalidHPS){
                                                      
                                                }
                                          }else{
                                                isSaved = true;
                                          }
                                          
                                          if(isvalidHPS){

                                                var idx = start.cellIndex;
                                                var nextrow = start.parentElement.nextElementSibling;
                                                $('#curText').text(string)

                                                if (nextrow != null) {
                                                      var sibling = nextrow.cells[idx];
                                                      string = sibling.innerText;
                                                      dotheneedful(sibling);
                                                }

                                          }


                                    } else if (e.keyCode == '37' && currentIndex != undefined) {


                                          if( $(currentIndex).hasClass('isHPS')){
                                                isvalidHPS = checkHPS()
                                                if(isvalidHPS){
                                                      
                                                }
                                          }else{
                                                isSaved = true;
                                          }
                                          
                                          if(isvalidHPS){

                                                var sibling = start.previousElementSibling;
                                                $('#curText').text(string)
                                          
                                          
                                                if($(sibling)[0].cellIndex != 0){
                                                      string = sibling.innerText;
                                                      dotheneedful(sibling);
                                                }

                                          }


                                    } else if (e.keyCode == '39' && currentIndex != undefined) {

                                          if( $(currentIndex).hasClass('isHPS')){
                                                isvalidHPS = checkHPS()
                                                if(isvalidHPS){
                                                      
                                                }
                                          }else{
                                                isSaved = true;
                                          }
                                          
                                          if(isvalidHPS){

                                                var sibling = start.nextElementSibling;
                                                
                                                $('#curText').text(string)

                                                if($(sibling)[0].cellIndex != 0){
                                                      string = sibling.innerText;
                                                      dotheneedful(sibling);
                                                }

                                          }

                                    
                                    }
                                    
                                    else if( e.key == "Backspace" && currentIndex != undefined){

                                          
                                          
                                        

                                          // if(string.length == 0){

                                          //       string = '0';
                                          //       currentIndex.innerText = 0

                                          // }else{
                                          //       currentIndex.innerText = parseInt(string)
                                          //       inputIndex = currentIndex
                                          // }

                                          // if($(currentIndex).hasClass('isHPS')){

                                          //       $(currentIndex).addClass('selected_hps')
                                          //       required_saved = true
                                          //       $('#updateGrade').removeAttr('hidden')
                                          //       checkHPS()
                                          //       calculate_hps()

                                          // }
                                          // else{

                                          //       calculate_final()

                                          // }

                                          // --------------- v1 -------------------

                                          string = currentIndex.innerText

                                          string = string.slice(0 , -1);

                                          if(string.length == 0){
                                                string = 0
                                          }

                                          currentIndex.innerText = parseInt(string)
                                    }

                                    else if ( e.key >= 0 && e.key <= 9 && currentIndex != undefined) {

                                          string += e.key;
                                          if(string <= 100){
                                                currentIndex.innerText = parseInt(string)
                                          }
                                         


                                          // if($(currentIndex)[0].tagName == 'TD'){


                                          //       if(currentIndex != inputIndex){
                                                
                                          //             string = ''

                                          //             if(parseInt($('#hps')[0].cells[$(currentIndex)[0].cellIndex].innerText) < parseInt(string+e.key) && $(currentIndex).parent().index() != 0
                                          //             ){
                                                
                                          //                   higher = true;

                                          //                   Toast.fire({
                                          //                         type: 'error',
                                          //                         title: 'Score is higher than his!'
                                          //                   })

                                          //             }

                                          //             inputIndex = currentIndex
                                          //       }
                                          //       else {
                                                

                                          //             if(parseInt($('#hps')[0].cells[$(currentIndex)[0].cellIndex].innerText) < parseInt(string+e.key)  && $(currentIndex).parent().index() != 0  
                                          //             ){
                                                      
                                          //                   higher = true;
                                          //                   string = ''

                                          //                   Toast.fire({
                                          //                         type: 'error',
                                          //                         title: 'Score is higher than highest score!!'
                                          //                   })
                                                            

                                          //             }

                                          //       }

                                          //       if(!higher){
                                                
                                          //             string += e.key;

                                          //             if(string == 0){

                                          //                   string = ''
                                          //                   currentIndex.innerText = 0

                                          //             }
                                          //             else{
                                                            
                                          //                   currentIndex.innerText = parseInt(string)
                                                            
                                                            
                                          //             }



                                          //             if($(currentIndex).hasClass('isHPS')){

                                          //                   $(currentIndex).addClass('selected_hps')
                                          //                   required_saved = true
                                          //                   $('#updateGrade').removeAttr('hidden')
                                          //                   checkHPS()
                                          //                   calculate_hps()

                                          //             }
                                          //             else{

                                          //                   calculate_final()

                                          //             }
                                                      
                                                
                                          //       }
                                          //       else{

                                          //             string =  parseInt($('#hps')[0].cells[$(currentIndex)[0].cellIndex].innerText)

                                          //             currentIndex.innerText = string
                                          //             calculate_final()


                                          //       }

                                          // }

                                          // $('#curText').text(string)

                                    }

                              }
                        
                              
                        }
                  })

            </script>

      @endif

      @elseif(count($gradesetup) > 0 && collect($gradesetup)->sum('percentage') != 100)

            
            <div class="modal-body p-0" style="height: 400px">
                  <div class="card mr-2 ml-2" style="top:30%"> 
                        <div class="card-body bg-success text-center text-lg text-bold">
                              <span class="text-xl text-bold">GRADE SETUP IS NOT EQUAL TO 100.</span > <p style="font-size:18px">PLEASE MAKE SURE THAT THE TOTAL GRADE SETUP PERCENTAGE IS EQUAL TO 100 </p>
                        </div>
                  </div>                 
            </div>

      @else
           
            <div class="modal-body p-0 table-responsive" style="height: 400px">
                  <div class="card mr-2 ml-2" style="top:35%"> 
                        <div class="card-body bg-success text-center text-lg text-bold">
                              PLEASE CREATE GRADE SETUP
                        </div>
                  </div>                 
            </div>

      @endif

@endif



