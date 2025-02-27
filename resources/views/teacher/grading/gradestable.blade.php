<div class=" table-responsive tableFixHead" style="height: 400px">
      <table  class="table table-bordered mb-0 table-sm table-hover" disabled>
            <thead>
                  <tr style="font-size:11px">
                        <th style="min-width:273px !important; z-index:100;background-color:#fff"></th>
                        <th colspan="13" class="text-center">Written Works <span class="text-danger">( {{$gradesetup->writtenworks}}% )</span></th>
                        <th colspan="13" class="text-center">Performance Task <span class="text-danger">( {{$gradesetup->performancetask}}% )</span></th>
                        @if($gradesetup->qassesment == 0 || $gradesetup->qassesment == null)
                              <th colspan="4" class="text-center" hidden></th>
                        @else
                              <th colspan="4" class="text-center">Quarter Assesment <span class="text-danger">( {{$gradesetup->qassesment}}% )</span></th>
                        @endif
                        <th></th>
                        <th style="z-index:100"></th>
                        
                  </tr>
                  <tr style="font-size:11px">
                        <th style="top: 28px;z-index:100" class="text-center align-middle">STUDENT</th>
                        @for($x = 0 ; $x < 10; $x++)
                              <th style="top: 28px; min-width:30px" class="text-center align-middle">{{$x+1}}</th>
                        @endfor
                        <th style="top: 28px;  min-width:30px important;" class="text-center align-middle">TOTAL</th>
                        <th style="top: 28px;  min-width:30px" class="text-center align-middle">PS</th>
                        <th style="top: 28px;  min-width:30px" class="text-center align-middle">WS</th>
                        @for($x = 0 ; $x < 10; $x++)
                              <th style="top: 28px; min-width:30px" class="text-center align-middle">{{$x+1}}</th>
                        @endfor
                        <th style="top: 28px;  min-width:30px;" class="text-center align-middle">TOTAL</th>
                        <th style="top: 28px;  min-width:30px;" class="text-center align-middle">PS</th>
                        <th style="top: 28px;  min-width:30px;" class="text-center align-middle">WS</th>
                        @if($gradesetup->qassesment == 0 || $gradesetup->qassesment == null)
                              <th hidden style="top: 28px; min-width:30px" class="text-center align-middle">1</th>
                              <th hidden style="top: 28px; min-width:30px" class="text-center align-middle">2</th>
                              <th hidden style="top: 28px;  min-width:30px" class="text-center align-middle">TOTAL</th>
                              <th hidden style="top: 28px;  min-width:30px" class="text-center align-middle">PS</th>
                              <th hidden style="top: 28px;  min-width:30px" class="text-center align-middle">WS</th>
                        @else
                              <th style="top: 28px; min-width:30px" class="text-center align-middle">1</th>
                              <th style="top: 28px; min-width:30px" class="text-center align-middle">2</th>
                              <th style="top: 28px;  min-width:30px" class="text-center align-middle">TOTAL</th>
                              <th style="top: 28px;  min-width:30px" class="text-center align-middle">PS</th>
                              <th style="top: 28px;  min-width:30px" class="text-center align-middle">WS</th>
                        @endif 
                        <th style="top: 28px;  min-width:30px" class="text-center align-middle">IG</th>
                        <th style="min-width:30px; top: 28px;z-index:100" class="text-center align-middle">FG</th>
                  </tr>
            </thead>
            <tbody>
      
                  @foreach($hps as $hp)
                        @php
                              $hpstotalwws = 0;
                              $hpstotalpt = 0;
                              $hpstotalqa = $hp->qahr1;
                        @endphp
                        <tr  data-value="{{$hp->id}}" id="hps" class="hps" style="font-size:11px">
                              <th class="isHPS" style="z-index: 1 !important ">HIGHEST POSSIBLE SCORE</th>

                              @for($x = 1 ; $x < 10; $x++)
                                    @php
                                          $wwhrString = 'wwhr'.$x;  
                                    @endphp
                                    <td class="{{$wwhrString}} isHPS ww" data-id="hps" data-field="{{$wwhrString}}">{{$hp->$wwhrString != null ? $hp->$wwhrString : 0}}</td>
                              @endfor
                              <td class="wwhr0 isHPS ww" data-id="hps" data-field="wwhr0">{{$hp->wwhr0 != null ? $hp->wwhr0 : 0}}</td>

                              <th class="text-center align-middle isHPS" data-id="hps" id="wwhpstotal"  data-field="wwhrtotal">
                                    {{$hp->wwhrtotal != null ? $hp->wwhrtotal : 0}}</th>
                              <th class="text-center align-middle isHPS" style="background-color: #b4c6e7;">100.00</th>
                              <th class="text-center align-middle isHPS" data-field="wwws" style="background-color: #f8cbad;">{{$gradesetup->writtenworks}}</th>

                              @for($x = 1 ; $x < 10; $x++)
                                    @php
                                          $pthrString = 'pthr'.$x; 
                                    @endphp
                                    <td class="{{$pthrString}} isHPS pt"  data-id="hps" data-field="{{$pthrString}}">{{$hp->$pthrString != null ? $hp->$pthrString : 0}}</td>
                              @endfor
                              <td class="pthr0 isHPS pt" data-id="hps" data-field="pthr0">{{$hp->pthr0 != null ? $hp->pthr0 : 0}}</td>
                              <th class="text-center align-middle isHPS pt"  data-id="hps" id="hpstotalpt" data-field="pthrtotal">
                                    {{$hp->pthrtotal != null ? $hp->pthrtotal : 0}}
                              </th>
                              <th class="text-center align-middle isHPS" style="background-color: #b4c6e7;">100.00</th>
                              <th class="text-center align-middle isHPS" data-field="ptws" style="background-color: #f8cbad;">{{$gradesetup->performancetask}}</th>
                              
                              @if($gradesetup->qassesment == 0 || $gradesetup->qassesment == null)
                                    <td hidden class="qahr1 isHPS qa" data-id="hps"  data-field="qahr1">0</td>
                                    <td hidden class="qahr1 isHPS qa" data-id="hps"  data-field="qahr2">0</td>
                                    <th hidden class="text-center align-middle isHPS"  data-id="hps" id="hpstotalqa"  data-field="qahrtotal">0</th>
                                    <th hidden class="text-center align-middle isHPS" style="background-color: #b4c6e7;">100.00</th>
                                    <th hidden class="text-center align-middle isHPS" data-field="qaws">0</th>
                              @else
                                    <td class="qahr1 isHPS qa" data-id="hps"  data-field="qahr1">{{$hp->qahr1 != null ? $hp->qahr1 : 0}}</td>
                                    <td class="qahr1 isHPS qa" data-id="hps"  data-field="qahr2">{{$hp->qahr2 != null ? $hp->qahr2 : 0}}</td>
                                    <th class="text-center align-middle isHPS"  data-id="hps" id="hpstotalqa"  data-field="qahrtotal">{{$hp->qahrtotal != null ? $hp->qahrtotal : 0}}</th>
                                    <th class="text-center align-middle isHPS" style="background-color: #b4c6e7;">100.00</th>
                                    <th class="text-center align-middle isHPS" data-field="qaws" style="background-color: #f8cbad;">{{$gradesetup->qassesment}}</th>
                              @endif
                              <th class="text-center align-middle isHPS" id="igtotal">100</th>
                              <th class="text-center align-middle isHPS" id="fg" style="z-index: 1; background-color: #aad08e;" >100</th>

                        </tr>
                  @endforeach
                  @php
                        $male = 0;
                        $female = 0;
                        $count = 1;
                  @endphp
                  @foreach($grades as $grade)
                        @php
                              $totalwws = 0;
                              $totalpt = 0;
                              $wwhpsSum = 0;
                        @endphp
                        @if($male == 0 && $grade->gender == 'MALE')
                              <tr class="bg-secondary" style="font-size:11px">
                                    <th class="text-dark bg-secondary">MALE</th>
                                    @for($x = 0 ; $x < 10; $x++)
                                          <th></th>
                                    @endfor
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    @for($x = 0 ; $x < 10; $x++)
                                          <th></th>
                                    @endfor
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    @if($gradesetup->qassesment == 0 || $gradesetup->qassesment == null)
                                          <th hidden></th>
                                          <th hidden></th>
                                          <th hidden></th>
                                          <th hidden></th>
                                          <th hidden></th>
                                    @else
                                          <th></th>
                                          <th></th>
                                          <th></th>
                                          <th></th>
                                          <th></th>
                                    @endif
                                    
                                    <th class="text-center align-middle" id="igtotal"></th>
                                    <th class="text-center align-middle" id="fg" style="z-index: 1; background-color: #aad08e;" class="text-dark bg-secondary"></th>
                              </tr>
                              @php
                                    $male = 1;
                                    $count = 1;
                              @endphp
                        @elseif($female == 0  && $grade->gender == 'FEMALE')
                              <tr class="bg-secondary" style="font-size:11px">
                                    <th class="text-dark bg-secondary">FEMALE</th>
                                    @for($x = 0 ; $x < 10; $x++)
                                          <th></th>
                                    @endfor
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    @for($x = 0 ; $x < 10; $x++)
                                          <th></th>
                                    @endfor
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    @if($gradesetup->qassesment == 0 || $gradesetup->qassesment == null)
                                          <th hidden></th>
                                          <th hidden></th>
                                          <th hidden></th>
                                          <th hidden></th>
                                          <th hidden></th>
                                    @else
                                          <th></th>
                                          <th></th>
                                          <th></th>
                                          <th></th>
                                          <th></th>
                                    @endif
                                    <th class="text-center align-middle" id="igtotal"></th>
                                    <th class="text-center align-middle" id="fg" style="z-index: 1; background-color: #aad08e;"></th>
                              </tr>
                              @php
                                    $female = 1;
                                    $count = 1;
                              @endphp
                        @endif
                        

                        <tr data-value="{{$grade->id}}" class="gradedetail" style="font-size:11px">
                              <th>
                                    <input <input type="checkbox" checked="checked" class="exclude mr-1" data-studid="{{$grade->studid}}" hidden>{{$count}}. <span>{{$grade->student}} <span class="badge badge-success float-right">{{$grade->stradname}}</span></span>
                              </th>
                              @php
                                    $count += 1;
                              @endphp
                              @for($x = 1 ; $x < 10; $x++)
                                    @php
                                          $wsstring = 'ww'.$x;  
                                    @endphp
                                    <td class="{{$wsstring}} ww" data-id="{{$grade->id}}" data-field="{{$wsstring}}" data-studid="{{$grade->studid}}">{{$grade->$wsstring != null ? $grade->$wsstring : 0}}</td>
                              @endfor
                              <td class="ww0 ww" data-id="{{$grade->id}}"" data-field="ww0" data-studid="{{$grade->studid}}">{{$grade->ww0 != null ? $grade->ww0 : 0}}</td>

                              <th class="text-center align-middle input_cell" data-field="wwtotal" data-id="{{$grade->id}}" data-studid="{{$grade->studid}}" >{{$grade->wwtotal != null ? $grade->wwtotal : 0}}</th>
                              <th class="text-center align-middle input_cell" data-field="wwps"  data-id="{{$grade->id}}" data-studid="{{$grade->studid}}" style="background-color: #b4c6e7;">{{number_format($grade->wwps,2)}}</th>
                              <th class="text-center align-middle ws input_cell" data-field="wwws"  data-id="{{$grade->id}}" data-studid="{{$grade->studid}}" style="background-color: #f8cbad;">{{number_format($grade->wwws,2)}}</th>

                              @for($x = 1 ; $x < 10; $x++)
                                    @php
                                          $ptstring = 'pt'.$x;  
                                    @endphp
                                    <td class="{{$ptstring}} pt" data-id="{{$grade->id}}" data-studid="{{$grade->studid}}" data-field="{{$ptstring}}">{{$grade->$ptstring != null ? $grade->$ptstring : 0}}</td>
                              @endfor
                              <td class="pt0 pt" data-id="{{$grade->id}}" data-studid="{{$grade->studid}}"  data-field="pt0">{{$grade->pt0  != null ? $grade->pt0 : 0}}</td>

                              <th class="text-center align-middle input_cell" data-field="pttotal"  data-id="{{$grade->id}}" data-studid="{{$grade->studid}}">{{$grade->pttotal != null ? $grade->pttotal : 0}}</th>

                              <th class="text-center align-middle input_cell" data-field="ptps"  data-id="{{$grade->id}}" data-studid="{{$grade->studid}}" style="background-color: #b4c6e7;">{{number_format($grade->ptps,2)}}</th>
                              <th class="text-center align-middle ws input_cell" data-field="ptws" data-id="{{$grade->id}}" data-studid="{{$grade->studid}}" style="background-color: #f8cbad;">{{number_format($grade->ptws,2)}}</th>

                              @if($gradesetup->qassesment == 0 || $gradesetup->qassesment == null)
                                    <td hidden class="qa1 qa" data-id="{{$grade->id}}" data-field="qa1" data-studid="{{$grade->studid}}">0</td>
                                    <td hidden class="qa2 qa" data-id="{{$grade->id}}" data-field="qa2" data-studid="{{$grade->studid}}">0</td>
                                    <th hidden class="text-center align-middle input_cell" data-field="qatotal"  data-id="{{$grade->id}}" data-studid="{{$grade->studid}}">0</th>
                                    <th hidden class="text-center align-middle input_cell" data-field="qaps"  data-id="{{$grade->id}}" data-studid="{{$grade->studid}}"  style="background-color: #b4c6e7;">0</th>
                                    <th hidden class="text-center align-middle ws input_cell" data-field="qaws" data-id="{{$grade->id}}" data-studid="{{$grade->studid}}">0</th>
                              @else
                                    <td class="qa1 qa" data-id="{{$grade->id}}" data-field="qa1" data-studid="{{$grade->studid}}">{{$grade->qa1 != null ?$grade->qa1 : 0}}</td>
                                    <td class="qa2 qa" data-id="{{$grade->id}}" data-field="qa2" data-studid="{{$grade->studid}}">{{$grade->qa2 != null ?$grade->qa2 : 0 }}</td>
                                    <th class="text-center align-middle input_cell" data-field="qatotal"  data-id="{{$grade->id}}" data-studid="{{$grade->studid}}" >{{$grade->qatotal}}</th>
                                    <th class="text-center align-middle input_cell" data-field="qaps"  data-id="{{$grade->id}}" data-studid="{{$grade->studid}}"  style="background-color: #b4c6e7;">{{number_format($grade->qaps,2)}}</th>
                                    <th class="text-center align-middle ws input_cell" data-field="qaws" data-id="{{$grade->id}}" data-studid="{{$grade->studid}}" style="background-color: #f8cbad;">{{number_format($grade->qaws,2)}}</th>
                              @endif
                        
                              <th class="text-center align-middle" data-field="ig" data-id="{{$grade->id}}" data-studid="{{$grade->studid}}">{{ number_format ( $grade->ig , 2)}}</th>
                              <th  class="text-center align-middle" data-field="qg" data-id="{{$grade->id}}" data-studid="{{$grade->studid}}" style="background-color: #aad08e;">{{$grade->qg}}</th>

                        </tr>
                  @endforeach
            </tbody>
      </table>
</div>
<div class="row mt-2" style="font-size:11px !important">
      <div class="col-md-3">
            <strong><i class="fas fa-book mr-1"></i> Number of Students</strong>
            <p class="text-muted mb-0 pl-3">Male : {{collect($grades)->where('gender','MALE')->count()}}</p>
            <p class="text-muted mb-0 pl-3">Female: {{collect($grades)->where('gender','FEMALE')->count()}}</p>
            <p class="text-muted mb-0 pl-3">Total: {{collect($grades)->count()}}</p>
      </div>
      <div class="col-md-3">
            <strong><i class="fas fa-book mr-1"></i>Grade Remarks</strong>
            <p class="text-muted mb-0 pl-3">PASSED : {{collect($grades)->where('qg','>=',75)->count()}}</p>
            <p class="text-muted mb-0 pl-3">FAILED: {{collect($grades)->where('qg','<',75)->count()}}</p>
            <p class="text-muted mb-0 pl-3"></p>
      </div>
      <div class="col-md-2">
            <strong><i class="fas fa-book mr-1"></i>Grade Status</strong>
            <p class="text-muted mb-0 pl-3">Submitted : {{collect($grades)->where('gdstatus',1)->count()}}</p>
            <p class="text-muted mb-0 pl-3">Approved: {{collect($grades)->where('gdstatus',2)->count()}}</p>
      </div>
      <div class="col-md-2">
            <strong></i>&nbsp;</strong>
            <p class="text-muted mb-0 pl-3">Pending : {{collect($grades)->where('gdstatus',3)->count()}}</p>
            <p class="text-muted mb-0 pl-3">Posted: {{collect($grades)->where('gdstatus',4)->count()}}</p>
      </div>
</div>

<script id="gradescript">

      $(document).ready(function(){

            var hps = @json($hps)[0];
            var grades = @json($grades);

            const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })

            if(grades.length == 0){
                  Toast.fire({
                        type: 'warning',
                        title: 'No student enrolled.'
                  })
            }
                              

            $('#label_dateuploaded').text(hps.uploadeddatetime)
            $('#label_datesubmitted').text("--")
            if(hps.submitted == 0){
                  $('#label_status').text('Not submitted')
                  if(hps.status == 3){
                        $('#label_status').text('Pending')
                  }
                  $('.exclude').removeAttr('hidden')
            }else{
                  if(hps.status == 1 || hps.status == 0){
                        $('#label_status').text('Submitted')
                  }else if(hps.status == 4){
                        $('#label_status, #ecr_status').text('Posted')
                  }else if(hps.status == 2){
                        $('#label_status, #ecr_status').text('Approved')
                  }
                  $('#label_datesubmitted').text(hps.date_submitted)
            }

            var curQuarter = @json($hps[0]->quarter);
            var can_edit = true

            var hps_check = @json($hps);
            $('#option_holder').attr('hidden','hidden')
            
            if(hps_check[0].submitted == 1){
                  $('#btnSubmit').attr('disabled','disabled')
                  $('#updateGrade').attr('disabled','disabled')
                  can_edit = false;

                  if(hps_check[0].status == 0 || hps_check[0].status == 1){
                        $('#gradeRibbon').removeAttr('hidden');
                        $('#gradeRibbonMessage').text('Submitted');
                  }
                  else if(hps_check[0].status == 2){
                        $('#gradeRibbon').removeAttr('hidden');
                        $('#gradeRibbonMessage').text('APPROVED');
                  }
                  else if(hps_check[0].status == 3){
                        $('#gradeRibbon').removeAttr('hidden');
                        $('#gradeRibbonMessage').text('PENDING');
                  }
                  else if(hps_check[0].status == 4){
                        $('#gradeRibbon').removeAttr('hidden');
                        $('#gradeRibbonMessage').text('POSTED');
                  }
            }else{
                  $('#option_holder').removeAttr('hidden')
                  if(hps_check[0].status == 3){
                        $('#gradeRibbon').removeAttr('hidden')
                        $('#gradeRibbonMessage').text('PENDING')
                  }
                  else{
                        $('#gradeRibbon').attr('hidden','hidden')
                  }
            }
    
            var isSaved = false;
            var isvalidHPS = true;
            var hps = []
            var currentIndex 
            var string = ''
            var inputIndex
            var totalig = 0;
            var igdata = [];
            var failed_count = 0;
        
            $.each(hps_check,function(a,b){
                  for(var x = 0; x < 10 ; x++){
                        var wwhrString = 'wwhr'+x;  
                        var info = {
                              'field': wwhrString,
                              'score': b[wwhrString]
                        }
                        hps.push(info)

                        var pthrString = 'pthr'+x;  
                        var info = {
                              'field': pthrString,
                              'score': b[pthrString]
                        }
                        hps.push(info)
                  }
                  var info = {
                              'field':'qahr1',
                              'score':b['qahr1']
                        }
                  hps.push(info)
            })

            $(document).on('click','td',function(){
                  if(currentIndex != undefined){
                        if( $(currentIndex).hasClass('isHPS') ){
                              isvalidHPS = checkHPS()
                        }else{
                              isSaved = true;
                        }
                        if(isvalidHPS){
                              if(can_edit){
                                    string = $(this).text();
                                    currentIndex = this;
                                    $('#start').length > 0 ? dotheneedful(this) : false
                                    $('td').removeAttr('style');
                                    $('#start').removeAttr('id')
                                    $(this).attr('id','start')
                                    var start = document.getElementById('start');
                                                      start.focus();
                                                      start.style.backgroundColor = 'green';
                                                      start.style.color = 'white';
                              }
                        }
                  }
                  else{
                        if(can_edit){
                              string = $(this).text();
                              currentIndex = this;
                              $('#start').length > 0 ? dotheneedful(this) : false
                              $('td').removeAttr('style');
                              $('#start').removeAttr('id')
                              $(this).attr('id','start')
                              var start = document.getElementById('start');
                                                start.focus();
                                                start.style.backgroundColor = 'green';
                                                start.style.color = 'white';

                        }
                  }
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
                        string = $(currentIndex)[0].innerText
                        $('#updateGrade')[0].innerHTML = '<i class="fas fa-save mr-1"></i> Update'
                        $('#updateGrade').removeClass('btn-success')
                        $('#updateGrade').addClass('btn-danger')
                  }
            }

            document.onkeydown = checkKey;

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
                                    type: 'warning',
                                    title: 'Highest Possible Score is lower than student highest score!'
                              })
                        }
                        return validHPS;
                  }
                  catch(err) {
                        validHPS = true;
                        return validHPS;
                  }
            }

            function checkKey(e) {
                  $('#btnSubmit').attr('disabled','disabled')
				  $('#updateGrade').removeAttr('disabled')
                  if($('#btnSubmit').length == 1){
                        var higher = false;
                        e = e || window.event;
                        if (e.keyCode == '38' && currentIndex != undefined)  {
                              $(currentIndex).hasClass('isHPS') ? isvalidHPS = checkHPS()  : false
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
                              $(currentIndex).hasClass('isHPS') ? isvalidHPS = checkHPS()  : false
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
                              $(currentIndex).hasClass('isHPS') ? isvalidHPS = checkHPS()  : false
                              if(isvalidHPS){
                                    var sibling = start.previousElementSibling;
                                    if($(sibling)[0].nodeName != "TD"){
                                          return false;
                                    }
                                    $('#curText').text(string)
                                    if($(sibling)[0].cellIndex != 0){
                                          string = sibling.innerText;
                                          dotheneedful(sibling);
                                    }
                              }

                        } else if (e.keyCode == '39' && currentIndex != undefined) {
                              $(currentIndex).hasClass('isHPS') ? isvalidHPS = checkHPS()  : false
                              if(isvalidHPS){
                                    var sibling = start.nextElementSibling;
                                    if($(sibling)[0].nodeName != "TD"){
                                          return false;
                                    }
                                    $('#curText').text(string)
                                    if($(sibling)[0].cellIndex != 0){
                                          string = sibling.innerText;
                                          dotheneedful(sibling);
                                    }
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
                              $(currentIndex).hasClass('isHPS') ? updatehrs() : updateTotal()
                        }
                        else if ( e.key >= 0 && e.key <= 9 && currentIndex != undefined) {
                              $('#updateGrade')[0].innerHTML = '<i class="fas fa-save mr-1"></i> Update'
                              $('#updateGrade').removeClass('btn-success')
                              $('#updateGrade').addClass('btn-danger')
                              if($(currentIndex)[0].tagName == 'TD'){
                                    var cell_index = $(currentIndex)[0].cellIndex
                                    var parent_index = $(currentIndex).parent().index()
                                    var cell_hps = parseInt($('#hps')[0].cells[cell_index].innerText)
                                    if(currentIndex != inputIndex){
                                          string = ''
                                          if( cell_hps < parseInt(string+e.key) && parent_index != 0){
                                                higher = true;
                                                Toast.fire({
                                                      type: 'warning',
                                                      title: 'Inputed score is higher than highest possible score!'
                                                })
                                          }
                                          inputIndex = currentIndex
                                    }
                                    else {
                                          if( cell_hps < parseInt(string+e.key)  && parent_index != 0){
                                                higher = true;
                                                string = ''
                                                Toast.fire({
                                                      type: 'warning',
                                                      title: 'Inputed score is higher than highest possible score!'
                                                })
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
                                          $(currentIndex).hasClass('isHPS') ? updatehrs() : updateTotal()
                                    }
                                    else{
                                          string =  parseInt(cell_hps)
                                          currentIndex.innerText = string
                                          updateTotal()
                                    }
                              }
                              $('#curText').text(string)
                        }
                  }
            }

               

            //cell update
            var transmutation = @json($transmutation);
            var newwwhpstotal = parseInt($('#wwhpstotal')[0].innerText)
            var newpthpstotal = parseInt($('#hpstotalpt')[0].innerText)
            var newqahpstotal = parseInt($('#hpstotalqa')[0].innerText)
            var iswww = false;
            var ispt = false;
            var isqa = false;

            function updateTotal(){
                  $(currentIndex).addClass('edited')
                  isww = $(currentIndex).hasClass('ww') ? true : false;
                  ispt = $(currentIndex).hasClass('pt') ? true : false;
                  isqa = $(currentIndex).hasClass('qa') ? true : false;
                  newwwhpstotal = parseInt($('#wwhpstotal')[0].innerText)
                  newpthpstotal = parseInt($('#hpstotalpt')[0].innerText)
                  newqahpstotal = parseInt($('#hpstotalqa')[0].innerText)
                  calc_grade(currentIndex)
            }

            function updatehrs(){
                  $(currentIndex).addClass('edited')
                  isww = $(currentIndex).hasClass('ww') ? true : false;
                  ispt = $(currentIndex).hasClass('pt') ? true : false;
                  isqa = $(currentIndex).hasClass('qa') ? true : false;
                  if(isww){
                        var totalwwhr = 0;
                        for(var x = 1; x < 11; x++){
                              totalwwhr += parseInt($(currentIndex).parent()[0].cells[x].innerText);
                              console.log($(currentIndex).parent()[0].cells[x].innerText)
                        }
                        $(currentIndex).parent()[0].cells[$('#wwhpstotal')[0].cellIndex].innerText = totalwwhr
                        $($(currentIndex).parent()[0].cells[$('#wwhpstotal')[0].cellIndex]).addClass('edited')
                  }
                  if(ispt){
                        var pthrtotal = 0
                        for(var x = 14; x < 24; x++){
                              pthrtotal += parseInt($(currentIndex).parent()[0].cells[x].innerText);
                        }
                        $(currentIndex).parent()[0].cells[$('#hpstotalpt')[0].cellIndex].innerText = pthrtotal
                        $($(currentIndex).parent()[0].cells[$('#hpstotalpt')[0].cellIndex]).addClass('edited')
                  }
                  if(isqa){
                        var totalqa = 0;
                        for(var x = 27; x <= 28; x++){
                              totalqa += parseInt($(currentIndex).parent()[0].cells[x].innerText);
                        }
                        $(currentIndex).parent()[0].cells[$('#hpstotalqa')[0].cellIndex].innerText = totalqa
                        $($(currentIndex).parent()[0].cells[$('#hpstotalqa')[0].cellIndex]).addClass('edited')
                  }

                  newwwhpstotal = parseInt($('#wwhpstotal')[0].innerText)
                  newpthpstotal = parseInt($('#hpstotalpt')[0].innerText)
                  newqahpstotal = parseInt($('#hpstotalqa')[0].innerText)
                  
                  $('.gradedetail').each(function(a,b){
                        calc_grade(b)
                  })
            }


            function round(num, precision) {
                  var base = 10 ** precision;
                  return (Math.round(num * base) / base).toFixed(precision);
            }

            function calc_grade(curcell){
                  
                  if($(curcell).hasClass('gradedetail')){
                        var id = $(curcell).attr('data-value')
                  }else{
                        var id = $(curcell).attr('data-id')
                  }

                  var totalww = 0;
                  var totalpt = 0;
                  if(isww){
                        if(!$(curcell).hasClass('gradedetail')){
                              var wwtotal = 0
                              $('.ww[data-id="'+id+'"]').each(function(a,b){
                                    wwtotal += parseInt($(b).text())
                                    $('.input_cell[data-id="'+id+'"][data-field="wwtotal"]')[0].innerText = wwtotal
                                    $('.input_cell[data-id="'+id+'"][data-field="wwtotal"]').addClass('edited')
                              })
                        }
                        var hps_ww_ws = parseInt (parseInt($('.isHPS[data-field="wwws"]').text()) ) / 100
                        var wwtotal = parseInt( $('.input_cell[data-id="'+id+'"][data-field="wwtotal"]').text() )
                        var wwps = parseFloat( ( wwtotal / newwwhpstotal ) * 100).toFixed(2);
                        var wwws = round( parseFloat( wwps * hps_ww_ws).toFixed(3) , 2 );

                        $('.input_cell[data-id="'+id+'"][data-field="wwps"]')[0].innerText = wwps != 'NaN' ? wwps : parseFloat(0).toFixed(2)
                        $('.input_cell[data-id="'+id+'"][data-field="wwws"]')[0].innerText = wwws != 'NaN' ? wwws : parseFloat(0).toFixed(2)
                        $('.input_cell[data-id="'+id+'"][data-field="wwps"]').addClass('edited')
                        $('.input_cell[data-id="'+id+'"][data-field="wwws"]').addClass('edited')
                        
                  }
                  if(ispt){
                        if(!$(curcell).hasClass('gradedetail')){
                              var pttotal = 0
                              $('.pt[data-id="'+id+'"]').each(function(a,b){
                                    pttotal += parseInt($(b).text())
                                    $('.input_cell[data-id="'+id+'"][data-field="pttotal"]')[0].innerText = pttotal
                                    $('.input_cell[data-id="'+id+'"][data-field="pttotal"]').addClass('edited')
                              })
                        }
                        var hps_pt_ws = parseInt (parseInt($('.isHPS[data-field="ptws"]').text()) ) / 100
                        var pttotal = parseInt( $('.input_cell[data-id="'+id+'"][data-field="pttotal"]').text() )
                        var ptps = parseFloat( ( pttotal / newpthpstotal ) * 100).toFixed(2);
                        var ptws = round( parseFloat( ptps * hps_pt_ws).toFixed(3), 2 );

                        $('.input_cell[data-id="'+id+'"][data-field="ptps"]')[0].innerText = ptps != 'NaN' ? ptps : parseFloat(0).toFixed(2)
                        $('.input_cell[data-id="'+id+'"][data-field="ptws"]')[0].innerText = ptws != 'NaN' ? ptws : parseFloat(0).toFixed(2)
                        $('.input_cell[data-id="'+id+'"][data-field="ptps"]').addClass('edited')
                        $('.input_cell[data-id="'+id+'"][data-field="ptws"]').addClass('edited')
                  }
                  if(isqa){
                        var qatotal = 0
                        $('.qa[data-id="'+id+'"]').each(function(a,b){
                              qatotal += parseInt($(b).text())
                              $('.input_cell[data-id="'+id+'"][data-field="qatotal"]')[0].innerText = qatotal
                              $('.input_cell[data-id="'+id+'"][data-field="qatotal"]').addClass('edited')
                        })
                        
                        var hps_qa_ws = parseInt (parseInt($('.isHPS[data-field="qaws"]').text()) ) / 100
                        var qatotal = parseInt( $('.input_cell[data-id="'+id+'"][data-field="qatotal"]').text() )
                        var qaps = parseFloat( ( qatotal / newqahpstotal ) * 100).toFixed(3);
                        var qaws = parseFloat( qaps * hps_qa_ws);
                        var qaws = round( parseFloat( qaps * hps_qa_ws).toFixed(3) , 2 );

                        $('.input_cell[data-id="'+id+'"][data-field="qaps"]')[0].innerText = qaps != 'NaN' ? parseFloat(qaps).toFixed(2) : parseFloat(0).toFixed(2)
                        $('.input_cell[data-id="'+id+'"][data-field="qaws"]')[0].innerText = qaws != 'NaN' ? qaws : parseFloat(0).toFixed(2)
                        $('.input_cell[data-id="'+id+'"][data-field="qaps"]').addClass('edited')
                        $('.input_cell[data-id="'+id+'"][data-field="qaws"]').addClass('edited')
                  }
                  var totalig = 0 ;
                  $('.ws[data-id="'+id+'"]').each(function(c,d){
                        totalig += parseFloat($(d).text())
                  })
                  totalig = totalig.toFixed(2)
                  fgfound = 0;
                  var last = null
                  
                  $.each(transmutation,function(a,b){
                        if(parseFloat(b.gfrom) >= parseFloat(totalig) && fgfound == 0){
                              $.each(transmutation,function(c,d){
                                    if(parseFloat(d.gto) >= parseFloat(totalig) && fgfound == 0){
                                          fgfound = 1;
                                          fg = d.gvalue
                                    }
                              })
                        }
                        last = b;
                  })

                  if(totalig >= 100){
                        fg = last.gvalue
                  }

                  $('th[data-field="qg"][data-id="'+id+'"]')[0].innerText = fg
                  $('th[data-field="ig"][data-id="'+id+'"]')[0].innerText = totalig
                  $('th[data-field="qg"][data-id="'+id+'"]').addClass('edited')
                  $('th[data-field="ig"][data-id="'+id+'"]').addClass('edited')

            }
            
      })
  </script>

 