<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Essentiel</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <!-- <link rel="stylesheet" href="{{asset('assets\css\bootstrap.min.css')}}" > -->
    <link rel="stylesheet" href="{{asset('dist/css/select2-bootstrap4.min.css')}}">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <script type="text/javascript" src="{{asset('assets/scripts/jquery-3.3.1.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{asset('assets/css/main.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assets\css\login.css')}}">
    <style>
            
            table {
                  border-collapse: collapse;
                  border: 1px solid black;
            }

            table td {
                  border: 1px solid black;
                  padding: 10px;
                  text-align: center;
            }

      
      </style>
   

</head>
<body>

      <section class="content">
            <div id="message">

            </div>
            <table>
                  <thead>
                        <tr>
                              <td colspan="13" colspan="text-center">Written Works</td>
                              <td colspan="13" colspan="text-center">Performance Task</td>
                              <td colspan="4" colspan="text-center">Quarterly Assesment</td>
                              <td></td>
                        </tr>
                        <tr>
                              @for($x = 0 ; $x < 10; $x++)
                                    <td>{{$x+1}}</td>
                              @endfor
                              <td>TOTAL</td>
                              <td>PS</td>
                              <td>WS</td>
                              @for($x = 0 ; $x < 10; $x++)
                                    <td>{{$x+1}}</td>
                              @endfor
                              <td>TOTAL</td>
                              <td>PS</td>
                              <td>WS</td>
                              <td>QA</td>
                              <td>TOTAL</td>
                              <td>PS</td>
                              <td>WS</td> 
                              <td>IG</td>
                        </tr>
                  </thead>
                  <tbody>
                        
                        @foreach($hps as $hp)
                              @php
                                    $hpstotalwws = 0;
                                    $hpstotalpt = 0;
                                    $hpstotalqa = $hp->qahr1;
                              @endphp
                              <tr  data-value="{{$hp->id}}" id="hps" class="hps">
                                    @for($x = 0 ; $x < 10; $x++)
                                          @php
                                                $wwhrString = 'wwhr'.$x;  
                                                $hpstotalwws += $hp->$wwhrString
                                          @endphp
                                          <td class="{{$wwhrString}}">{{$hp->$wwhrString}}</td>
                                    @endfor

                                    <td id="wwhpstotal">{{$hpstotalwws}}</td>
                                    <td>100.00</td>
                                    <td>{{$hpstotalwws}}</td>

                                    @for($x = 0 ; $x < 10; $x++)
                                          @php
                                                $pthrString = 'pthr'.$x; 
                                                $hpstotalpt += $hp->$pthrString
                                          @endphp
                                          <td class="{{$pthrString}}">{{$hp->$pthrString}}</td>
                                    @endfor

                                    <td id="hpstotalpt">{{$hpstotalpt}}</td>
                                    <td>100.00</td>
                                    <td>{{$hpstotalpt}}</td>

                                    <td class="qahr1">{{$hp->qahr1}}</td>
                                    <td id="hpstotalqa">{{$hp->qahr1}}</td>
                                    <td>100.00</td>
                                    <td>{{$hp->qahr1}}</td>
                                    <td id="igtotal"></td>
                                    
                              </tr>
                        @endforeach
                      
                        @foreach($grades as $grade)
                              @php
                                    $totalwws = 0;
                                    $totalpt = 0;
                                    $wwhpsSum = 0;7
                              @endphp
                              <tr data-value="{{$grade->id}}" class="gradedetail">
                                    @for($x = 0 ; $x < 10; $x++)
                                          @php
                                                $wsstring = 'ww'.$x;  
                                                $totalwws += $grade->$wsstring;
                                          @endphp
                                          <td class="{{$wsstring}}">{{$grade->$wsstring}}</td>
                                    @endfor
                                    <td>{{$totalwws}}</td>
                                    @php
                                          if($hpstotalwws!=0){
                                                $wwhpsSum = round( ( $totalwws / $hpstotalwws ) * 100 , 2);
                                          }
                                    @endphp
                                    <td>{{number_format($wwhpsSum, 2, '.', '')}}</td>

                                    <td>{{number_format($wwhpsSum * ( $gradesetup->writtenworks  / 100), 2, '.', '')}}</td>

                                    @for($x = 0 ; $x < 10; $x++)
                                          @php
                                                $ptstring = 'pt'.$x;  
                                                $totalpt += $grade->$ptstring;
                                          @endphp
                                          <td class="{{$ptstring}}">{{$grade->$ptstring}}</td>
                                    @endfor

                                    <td>{{$totalpt}}</td>
                                    @php
                                          if($hpstotalpt!=0){
                                                $pthpsSum = round( ( $totalpt / $hpstotalpt ) * 100 , 2);
                                          }else{
                                                $pthpsSum = 0;
                                          }
                                    @endphp
                                    <td>{{number_format($pthpsSum, 2, '.', '')}}</td>
                                    <td>{{number_format( $pthpsSum * ( $gradesetup->performancetask  / 100) , 2, '.', '')}}</td>

                                    <td class="qa1">{{$grade->qa1}}</td>
                                    <td >{{$grade->qa1}}</td>

                                    @if($hpstotalqa!=0)
                                          <td>{{ number_format( round( ( $grade->qa1 /  $hp->qahr1 ) * 100 , 2), 2, '.', '') }}</td>
                                    @else
                                          <td>0</td>
                                    @endif

                                    @if($hpstotalqa!=0)
                                          <td>{{ number_format ( number_format( round( ( $grade->qa1 /  $hp->qahr1 ) * 100 , 2), 2, '.', '') * ( $gradesetup->qassesment  / 100 ), 2, '.', '' )}}</td>
                                    @else
                                          <td>0</td>
                                    @endif
                                    <td>{{ number_format ( $grade->ig , 2, '.', '' )}}</td>

                              </tr>
                        @endforeach
                      
                  </tbody>
            </table>
      </section>
</div>
      <script>

            $(document).ready(function(){


            
                  var currentIndex 
                  var string = ''
                  var inputIndex
                  var totalig = 0;
                  var igdata = [];
                  
                  $(document).on('click','td',function(){
                        
                        string = $(this).text();
                        currentIndex = this;

                        if($('#start').length > 0){
                              dotheneedful(this);
                        }

                        $('td').removeAttr('style');
                        $('#start').removeAttr('id')
                        
                        $(this).attr('id','start')

                        var start = document.getElementById('start');
                                          start.focus();
                                          start.style.backgroundColor = 'green';
                                          start.style.color = 'white';

                        
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
                              $('#message').empty();
                              string = ''
                        }

                  
                  }

              

                  document.onkeydown = checkKey;

                  

                  function checkKey(e) {

                        var higher = false;
                        
                        e = e || window.event;

                        if (e.keyCode == '38') {
                              
                              var idx = start.cellIndex;
                              var nextrow = start.parentElement.previousElementSibling;
                              
                              if (nextrow != null) {
                                    
                                    var sibling = nextrow.cells[idx];
                                    string = sibling.innerText;
                                    dotheneedful(sibling);
                              }

                        } else if (e.keyCode == '40') {
                  
                              var idx = start.cellIndex;
                              var nextrow = start.parentElement.nextElementSibling;

                              if (nextrow != null) {
                                    var sibling = nextrow.cells[idx];
                                    string = sibling.innerText;
                        
                                    dotheneedful(sibling);
                              }
                        } else if (e.keyCode == '37') {
                  
                              var sibling = start.previousElementSibling;
                              string = sibling.innerText;
                              
                              dotheneedful(sibling);

                        } else if (e.keyCode == '39') {
                  
                              var sibling = start.nextElementSibling;
                              string = sibling.innerText;
                              dotheneedful(sibling);

                        }
                        
                        else if( e.key == "Backspace"){
                              
                              string = currentIndex.innerText

                              string = string.slice(0 , -1);

                              if(string.length == 0){
                                    string = 0;
                              }

                              currentIndex.innerText = parseInt(string)

                              inputIndex = currentIndex

                              if($(currentIndex).parent().index() == 0){

                                    ajaxsupdatehps()

                              }
                              else if($(currentIndex).parent().index() != 0 && !higher){

                                    ajaxsupdategrades()

                              }
                              else{
                                    string = $('#hps')[0].cells[currentIndex].innerText
                                    ajaxsupdategrades()
                              }

                        }

                        else if ( e.key >= 0 && e.key <= 9) {
                        
                              if(currentIndex != inputIndex){
                              
                                    string = ''
                              
                                    if(parseInt($('#hps')[0].cells[$(currentIndex)[0].cellIndex].innerText) < parseInt(string+e.key) && $(currentIndex).parent().index() != 0
                                    ){
                              
                                          higher = true;
                                          $('#message').text('score is higher than highest possible score')

                                    }
                                    inputIndex = currentIndex
                              }
                              else {
                              
                                    if(parseInt($('#hps')[0].cells[$(currentIndex)[0].cellIndex].innerText) < parseInt(string+e.key)  && $(currentIndex).parent().index() != 0  
                                    ){
                                    
                                          higher = true;
                                          string = ''
                                          $('#message').text('score is higher than highest possible score')

                                    }
                              }

                              if(!higher){
                              
                                    string += e.key;

                                    if(string == 0){

                                          string = ''
                                          currentIndex.innerText = 0

                                    }
                                    else{
                                          
                                          currentIndex.innerText = parseInt(string)

                                    }

                              
                              }
                              else{

                                    string = ''
                                    currentIndex.innerText = parseInt($('#hps')[0].cells[$(currentIndex)[0].cellIndex].innerText)


                              }

                              if($(currentIndex).parent().index() == 0){

                                    ajaxsupdatehps()
                              
                              }
                              else if($(currentIndex).parent().index() != 0 && !higher){
                                   
                                    ajaxsupdategrades()
                              
                              }
                              else{
                                    
                                    string = parseInt($('#hps')[0].cells[$(currentIndex)[0].cellIndex].innerText)
                                    ajaxsupdategrades()
                                    string = ''
                              
                              }
                              

                        }

                              
                  }
            

                  function ajaxsupdategrades(){

                        updateTotal()

                        $.ajax({
                              type:'GET',
                              url:'/teacher/update/grades',
                              data:{
                                    a: $(currentIndex).parent().attr('data-value'),
                                    b: $(currentIndex).attr('class'),
                                    c: string,
                                    d: $totalig
                              },
                        })
                  }

                  function ajaxsupdatehps(){

                        updatehrs()

                        $.ajax({
                              type:'GET',
                              url:'/teacher/update/hps',
                              data:{
                                    a: $(currentIndex).parent().attr('data-value'),
                                    b: $(currentIndex).attr('class'),
                                    c: string,
                                    d: igdata
                              },
                              success:function(data) {
                                    igdata = []
                              }
                        })
                  }

                  function updateTotal(){

                        var wwhrtotal = 0
                        var pthrtotal = 0
                        var qahrtotal = $('#hpstotalqa')[0].innerText

                        for(var x = 0; x < 10; x++){

                              wwhrtotal += parseInt($('#hps')[0].cells[x].innerText);

                        }

                        for(var x = 13; x < 23; x++){

                              pthrtotal += parseInt($('#hps')[0].cells[x].innerText);

                        }

                        var totalww = 0;
                        var totalpt = 0;

                        for(var x = 0; x < 10; x++){

                              totalww += parseInt($(currentIndex).parent()[0].cells[x].innerText);

                        }

                        for(var x = 13; x < 23; x++){

                              totalpt += parseInt($(currentIndex).parent()[0].cells[x].innerText);

                        }

                        totalqa = parseInt($(currentIndex).parent()[0].cells[26].innerText)

                        if(totalww){
                              $(currentIndex).parent()[0].cells[$('#wwhpstotal')[0].cellIndex].innerText = totalww
                              $(currentIndex).parent()[0].cells[$('#wwhpstotal')[0].cellIndex + 1].innerText = ( ( totalww /wwhrtotal ) * 100 ).toFixed(2)
                              $(currentIndex).parent()[0].cells[$('#wwhpstotal')[0].cellIndex + 2].innerText = ( (  ( ( totalww /wwhrtotal ) * 100 ).toFixed(2) ) *  '{{ $gradesetup->writtenworks / 100}}' ).toFixed(2)
                        }

                        if(totalpt){
                              $(currentIndex).parent()[0].cells[$('#hpstotalpt')[0].cellIndex].innerText = totalpt
                              $(currentIndex).parent()[0].cells[$('#hpstotalpt')[0].cellIndex + 1].innerText = ( ( totalpt /pthrtotal ) * 100 ).toFixed(2)
                              $(currentIndex).parent()[0].cells[$('#hpstotalpt')[0].cellIndex + 2].innerText = ( (  ( ( totalpt /pthrtotal ) * 100 ).toFixed(2) ) *  '{{ $gradesetup->performancetask / 100}}' ).toFixed(2)
                        }

                        if(totalqa){
                              $(currentIndex).parent()[0].cells[$('#hpstotalqa')[0].cellIndex].innerText = totalqa;
                              $(currentIndex).parent()[0].cells[$('#hpstotalqa')[0].cellIndex + 1].innerText = ( ( totalqa /qahrtotal ) * 100 ).toFixed(2)
                              $(currentIndex).parent()[0].cells[$('#hpstotalqa')[0].cellIndex + 2].innerText = ( ( ( ( totalqa /qahrtotal ) * 100 ).toFixed(2) ) *  '{{ $gradesetup->qassesment / 100}}' ).toFixed(2)
                        }

                       

                        $totalig = (
                                    ( ( ( totalww / wwhrtotal ) * 100 ) * '{{ $gradesetup->writtenworks / 100}}' ) +
                                    ( ( ( totalpt / pthrtotal ) * 100 ) * '{{ $gradesetup->performancetask / 100}}' ) +
                                    ( ( ( totalqa / qahrtotal ) * 100 ) * '{{ $gradesetup->qassesment / 100}}' )
                                    ).toFixed(2)


                        $('.gradedetail').each(function(){

                              var totalww = 0;
                              var totalpt = 0;
                              totalqa = parseInt($(this)[0].cells[26].innerText);


                              for(var x = 0; x < 10; x++){
                                    totalww += parseInt($(this)[0].cells[x].innerText);
                              }

                            

                              for(var x = 13; x < 23; x++){

                                    totalpt += parseInt($(this)[0].cells[x].innerText);

                              }

                              $(this)[0].cells[$('#igtotal')[0].cellIndex].innerText = (
                              ( ( ( totalww / wwhrtotal ) * 100 ) * '{{ $gradesetup->writtenworks / 100}}' ) +
                              ( ( ( totalpt / pthrtotal ) * 100 ) * '{{ $gradesetup->performancetask / 100}}' ) +
                              ( ( ( totalqa / qahrtotal ) * 100 ) * '{{ $gradesetup->qassesment / 100}}' )
                              ).toFixed(2)
                        })

                  }

                  function updatehrs(){

                        var totalwwhr = 0;
                        var pthrtotal = 0
                        

                        for(var x = 0; x < 10; x++){

                              totalwwhr += parseInt($(currentIndex).parent()[0].cells[x].innerText);

                        }

                        for(var x = 13; x < 23; x++){

                              pthrtotal += parseInt($(currentIndex).parent()[0].cells[x].innerText);

                        }
                       
                   

                        totalqa = parseInt($(currentIndex).parent()[0].cells[26].innerText)

                        var qahrtotal = $('#hpstotalqa')[0].innerText

                        if(totalwwhr != 0){
                              $(currentIndex).parent()[0].cells[$('#wwhpstotal')[0].cellIndex].innerText = totalwwhr
                        }
                        if(hpstotalpt != 0){
                              $(currentIndex).parent()[0].cells[$('#hpstotalpt')[0].cellIndex].innerText = pthrtotal
                        }

                        if(totalqa != 0){

                              $(currentIndex).parent()[0].cells[$('#hpstotalqa')[0].cellIndex].innerText = totalqa
                       
                        }
                        else{
                              $(currentIndex).parent()[0].cells[$('#hpstotalqa')[0].cellIndex].innerText = 0
                        }
                        

                        

                        $('.gradedetail').each(function(){

                              

                              var totalww = 0;
                              var totalpt = 0;

                              for(var x = 0; x < 10; x++){
                                    totalww += parseInt($(this)[0].cells[x].innerText);
                              }

                              for(var x = 13; x < 23; x++){

                                    totalpt += parseInt($(this)[0].cells[x].innerText);

                              }

                              totalqa = parseInt($(this)[0].cells[26].innerText);

                              if(totalwwhr != 0){
                                    $(this)[0].cells[$('#wwhpstotal')[0].cellIndex + 1].innerText = ( ( totalww /totalwwhr ) * 100 ).toFixed(2)
                              }
                              if(pthrtotal != 0){
                                    $(this)[0].cells[$('#hpstotalpt')[0].cellIndex + 1].innerText = ( ( totalpt /pthrtotal ) * 100 ).toFixed(2)
                              }

                              if(qahrtotal != 0){

                                    $(this)[0].cells[$('#hpstotalqa')[0].cellIndex + 1].innerText = ( ( totalqa / qahrtotal ) * 100 ).toFixed(2)

                              }
                              
                              $totalig = (
                                    ( ( ( totalww / totalwwhr ) * 100 ) * '{{ $gradesetup->writtenworks / 100}}' ) +
                                    ( ( ( totalpt / pthrtotal ) * 100 ) * '{{ $gradesetup->performancetask / 100}}' ) +
                                    ( ( ( totalqa / qahrtotal ) * 100 ) * '{{ $gradesetup->qassesment / 100}}' )
                                    ).toFixed(2)

                              if($totalig != 0){
                                    $(this)[0].cells[$('#igtotal')[0].cellIndex].innerText = $totalig
                              }

                              igdata.push({'id':$(this).attr('data-value'), 'ig':$totalig})
                                         
                        })

                  }



         
            })
        </script>
</body>


</html>
