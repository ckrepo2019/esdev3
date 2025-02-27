<style>
      .table thead th:last-child  { 
         position: sticky; 
         right: 0; 
         background-color: #fff; 
         outline: 2px solid #dee2e6;
         outline-offset: -1px;
     }

     .table tbody th:last-child  { 
         position: sticky; 
         right: 0; 
         background-color: #fff; 
         outline: 2px solid #dee2e6;
         outline-offset: -1px;
         }

     .table tbody th:first-child  {  
         position: sticky; 
         left: 0; 
         background-color: #fff; 
         width: 150px !important;
         background-color: #fff; 
         outline: 2px solid #dee2e6;
         outline-offset: -1px;
     }

     .table thead th:first-child  { 
             position: sticky; left: 0; 
             width: 150px !important;
             background-color: #fff; 
             outline: 2px solid #dee2e6;
             outline-offset: -1px;
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
         top: 0;
         background-color: #fff;
         outline: 2px solid #dee2e6;
         outline-offset: -1px;
        
     }

     .isHPS {

         position: sticky;
         top: 59px !important;
         background-color: #fff;
         outline: 2px solid #dee2e6 ;
         outline-offset: -1px;
        
     }

     .ecr-date {
         width:80px;
         top: 80px;
         left: 12px;
         position: absolute;
         transform-origin: 0 0;
         transform: rotate(-90deg);
     }

</style>

@php
      $get_coor = DB::table('usertype')
                        ->where('id',Session::get('currentPortal'))
                        ->first();

      if(isset($get_coor->refid)){
            $get_coor = $get_coor->refid;
      }else{
            $get_coor = 0;
      }
                        
@endphp

<div class=" table-responsive tableFixHead mt-1" style="height: 400px; font-size:11px !important" >
   <table  class="table table-bordered mb-0 table-sm" disabled>
         <thead>
               <tr style="font-size:12px">
                     <th style="min-width:234px !important; z-index:100;background-color:#fff">&nbsp;</th>
                     <th style="min-width:40px;" class="text-center align-middle student_view"></th>
                     <th colspan="{{$schoolinfo->snr == 'spct' ? 14:13}}" class="text-center">
                           Written Works <span class="text-danger">( {{$percentage[0]->ww}}% )</span>
                     </th>
                     <th colspan="{{$schoolinfo->snr == 'spct' ? 14:13}}" class="text-center">
                           Performance Task <span class="text-danger">( {{$percentage[0]->pt}}% )</span>
                     </th>
                     @if($percentage[0]->qa != null || $percentage[0]->qa != 0)
                           @if($schoolinfo->snr == 'spct')
                                 <th colspan="6"></th>
                           @else
                                 <th colspan="5">QTLY. Assesment <span class="text-danger">( {{$percentage[0]->qa}}% )</th>
                           @endif
                     @endif
                     <th ></th>
                     <th style="z-index:100;background-color:#fff"> </th>
               </tr>
               <tr>
                     <th style="top: 31px; z-index: 100 !important " class="text-center align-middle">STUDENT</th>
                     <th style="top: 31px; min-width:40px;" class="text-center align-middle student_view"></th>
                     @for($x = 0 ; $x < 10; $x++)
                           <th style="top: 31px; min-width:40px;" class="text-center align-middle">{{$x+1}}</th>
                     @endfor
                     @if($schoolinfo->snr == 'spct')
                           <th style="top: 31px; min-width:40px;" class="text-center align-middle"></th>
                     @endif
                     <th style="top: 31px;" class="text-center align-middle">TOTAL</th>
                     <th style="top: 31px;" class="text-center align-middle">PS</th>
                     <th style="top: 31px;" class="text-center align-middle">WS</th>
                     @for($x = 0 ; $x < 10; $x++)
                           <th style="top: 31px; min-width:40px;" class="text-center align-middle">{{$x+1}}</th>
                     @endfor
                     @if($schoolinfo->snr == 'spct')
                           <th style="top: 31px; min-width:40px;" class="text-center align-middle"></th>
                     @endif
                     <th style="top: 31px;" class="text-center align-middle">TOTAL</th>
                     <th style="top: 31px;" class="text-center align-middle">PS</th>
                     <th style="top: 31px;" class="text-center align-middle">WS</th>
                     @if($percentage[0]->qa != null || $percentage[0]->qa != 0)
                           @if($schoolinfo->snr == 'spct')
                                 <th style="top: 31px; min-width:40px;" class="text-center align-middle">1</th>
                                 <th style="top: 31px; min-width:40px;" class="text-center align-middle">2</th>
                                 <th style="top: 31px; min-width:40px;" class="text-center align-middle"></th>
                                 <th style="top: 31px; min-width:40px;" class="text-center align-middle">TOTAL</th>
                                 <th style="top: 31px; min-width:40px;" class="text-center align-middle">PS</th>
                                 <th style="top: 31px; min-width:40px;" class="text-center align-middle">WS</th>
                           @else
                                 <th style="top: 31px; min-width:40px;" class="text-center align-middle">1</th>
                                 <th style="top: 31px; min-width:40px;" class="text-center align-middle">2</th>
                                 <th style="top: 31px; min-width:40px;" class="text-center align-middle">Total</th>
                                 <th style="top: 31px; min-width:40px;" class="text-center align-middle">PS</th>
                                 <th style="top: 31px; min-width:40px;" class="text-center align-middle">WS</th>
                           @endif
                     @endif
                     <th style="top: 31px;" class="text-center align-middle">IG</th>
                     <th style="min-width:60px; top: 31px;z-index:100" class="text-center align-middle">FG</th>
               </tr>
               @foreach($gradesdates as $gradesdate)
                  <tr>
                        <th class="text-center align-middle" style="z-index:100;background-color:#fff">Dates</th>
                        <th class="text-center align-middle"></th>
                        @for($x = 1 ; $x < 10; $x++)
                              @php
                                    $wwhrString = 'dww'.$x;  
                              @endphp
                              <th class="text-center align-middle " style="height: 80px;"><span class="ecr-date">{{$gradesdate->$wwhrString != null && $gradesdate->$wwhrString != '0000-00-00' ? \Carbon\Carbon::create($gradesdate->$wwhrString)->isoFormat('MMM DD, YYYY') : ''}}</span></th>
                              {{-- <th></th> --}}
                        @endfor
                        <th class="text-center align-middle" style="height: 80px;"><span class="ecr-date">{{$gradesdate->dww0 != null && $gradesdate->dww0 != '0000-00-00' ? \Carbon\Carbon::create($gradesdate->dww0)->isoFormat('MMM DD, YYYY') : ''}}</span></th>
                        <th class="text-center align-middle "></th>
                        <th class="text-center align-middle"></th>
                        <th class="text-center align-middle " ></th>
                        @for($x = 1 ; $x < 10; $x++)
                              @php
                                    $pthrString = 'dpt'.$x;  
                              @endphp
                              <th class="text-center align-middle " style="height: 80px;"><span class="ecr-date">{{$gradesdate->$pthrString != null && $gradesdate->$pthrString != '0000-00-00' ? \Carbon\Carbon::create($gradesdate->$pthrString)->isoFormat('MMM DD, YYYY') : ''}}</span></th>
                        @endfor
                        <th class="text-center align-middle " style="height: 80px;"><span class="ecr-date">{{$gradesdate->dpt0 != null && $gradesdate->dpt0 != '0000-00-00' ? \Carbon\Carbon::create($gradesdate->dpt0)->isoFormat('MMM DD, YYYY') : ''}}</span></th>
                        <th class="text-center align-middle "></th>
                        <th class="text-center align-middle " ></th>
                        <th class="text-center align-middle"></th>
            

                        @for($x = 1 ; $x < 4; $x++)
                              @php
                                    $qahrString = 'dqa'.$x;  
                              @endphp
                              <th class="text-center align-middle" style="height: 80px;"><span class="ecr-date">{{$gradesdate->$qahrString != null && $gradesdate->$qahrString != '0000-00-00' ? \Carbon\Carbon::create($gradesdate->$qahrString)->isoFormat('MMM DD, YYYY') : ''}}</span></th>
                        @endfor
                       
                        <th class="text-center align-middle " ></th>
                        <th class="text-center align-middle " ></th>
                        <th class="text-center align-middle " data-field="wwws"></th>
                        <th class="text-center align-middle " id="fg" style="z-index: 1;"></th>
                  </tr>
            @endforeach
         </thead>
         <tbody>

               @foreach($hps as $hp)
                     <tr>
                           <th style="z-index: 100 !important" class="isHPS">HIGHEST POSSIBLE SCORE</th>
                           <th style="min-width:40px;" class="text-center align-middle isHPS student_view perst"></th>
                           @for($x = 1 ; $x < 10; $x++)
                                 @php
                                       $wwhrString = 'wwhr'.$x;  
                                 @endphp
                                 <td class="text-center align-middle isHPS">{{$hp->$wwhrString}}</td>
                           @endfor
                           <td class="text-center align-middle isHPS">{{$hp->wwhr0}}</td>
                           @if($schoolinfo->snr == 'spct')
                                 <th class="text-center align-middle isHPS">&nbsp;</th>
                           @endif
                           <th class="text-center align-middle isHPS" >{{$hp->wwhrtotal}}</th>
                           <th class="text-center align-middle isHPS">100.00</th>
                           <th class="text-center align-middle isHPS" data-field="wwws">{{$percentage[0]->ww}}%</th>
                           @for($x = 1 ; $x < 10; $x++)
                                 @php
                                       $pthrString = 'pthr'.$x; 
                                 @endphp
                                 <td class="text-center align-middle isHPS">{{$hp->$pthrString}}</td>
                           @endfor
                           <td class="text-center align-middle isHPS">{{$hp->pthr0}}</td>
                           @if($schoolinfo->snr == 'spct')
                                 <th class="text-center align-middle isHPS">&nbsp;</th>
                           @endif
                           <th class="text-center align-middle isHPS" >{{$hp->pthrtotal}}</th>
                           <th class="text-center align-middle isHPS">100.00</th>
                           <th class="text-center align-middle isHPS" data-field="wwws">{{$percentage[0]->pt}}%</th>
                           @if($percentage[0]->qa != null || $percentage[0]->qa != 0)
                                 @if($schoolinfo->snr == 'spct')
                                       <td style="top: 31px;" class="text-center align-middle isHPS">{{$hp->qahr1}}</td>
                                       <td style="top: 31px;" class="text-center align-middle isHPS">{{$hp->qahr2}}</td>
                                       <th style="top: 31px;" class="text-center align-middle isHPS"></th>
                                       <th style="top: 31px;" class="text-center align-middle isHPS">{{$hp->qahrtotal}}</th>
                                       <th style="top: 31px;" class="text-center align-middle isHPS">PS</th>
                                       <th style="top: 31px;" class="text-center align-middle isHPS">{{$percentage[0]->qa}}%</th>
                                 @else
                                       <th class="text-center align-middle isHPS" >{{$hp->qahr1}}</th>
                                       <th class="text-center align-middle isHPS" >{{$hp->qahr2}}</th>
                                       <th class="text-center align-middle isHPS">{{$hp->qahrtotal}}</th>
                                       <th class="text-center align-middle isHPS">100.00</th>
                                       <th class="text-center align-middle isHPS" data-field="wwws">{{$percentage[0]->qa}}%</th>
                                 @endif
                           @endif
                           <th class="text-center align-middle isHPS" id="igtotal">100</th>
                           <th class="text-center align-middle isHPS" id="fg" style="z-index: 1;">100</th>
                     </tr>
               @endforeach
               @if(count($grades) > 0)
                     @php
                           $male = 0;
                           $female = 0;
                           $count = 0;
                     @endphp
                     @foreach($grades as $grade)
                           @if($male == 0 && $grade->gender == 'MALE')
                                 <tr class="bg-secondary">
                                       <th class="text-dark bg-secondary">MALE</th>
                                       <th style="top: 31px; min-width:40px;" class="text-center align-middle student_view"></th>
                                       @for($x = 0 ; $x < 10; $x++)
                                             <th></th>
                                       @endfor
                                       @if($schoolinfo->snr == 'spct')
                                             <th></th>
                                       @endif
                                       <th></th>
                                       <th></th>
                                       <th></th>
                                       @for($x = 0 ; $x < 10; $x++)
                                             <th></th>
                                       @endfor
                                       @if($schoolinfo->snr == 'spct')
                                             <th></th>
                                       @endif
                                       <th></th>
                                       <th></th>
                                       <th></th>
                                       @if($percentage[0]->qa != null || $percentage[0]->qa != 0)
                                             @if($schoolinfo->snr == 'spct')
                                                   <th colspan="6"></th>
                                             @else
                                                   <th colspan="5"></th>
                                             @endif
                                       @endif
                                       
                                       <th class="text-center align-middle" id="igtotal"></th>
                                       <th class="text-center align-middle" id="fg" style="z-index: 1;"></th>
                                 </tr>
                                 @php
                                       $male = 1;
                                       $count = 1;
                                 @endphp
                           @elseif($female == 0  && $grade->gender == 'FEMALE')
                                 <tr class="bg-secondary">
                                       <th class="text-dark bg-secondary">FEMALE</th>
                                       <th style="top: 31px; min-width:40px;" class="text-center align-middle student_view"></th>
                                       @for($x = 0 ; $x < 10; $x++)
                                             <th></th>
                                       @endfor
                                       @if($schoolinfo->snr == 'spct')
                                             <th></th>
                                       @endif
                                       <th></th>
                                       <th></th>
                                       <th></th>
                                       @for($x = 0 ; $x < 10; $x++)
                                             <th></th>
                                       @endfor
                                       @if($schoolinfo->snr == 'spct')
                                             <th></th>
                                       @endif
                                       <th></th>
                                       <th></th>
                                       <th></th>
                                       @if($percentage[0]->qa != null || $percentage[0]->qa != 0)
                                             @if($schoolinfo->snr == 'spct')
                                                   <th colspan="6"></th>
                                             @else
                                                   <th colspan="5"></th>
                                             @endif
                                       @endif
                                       <th class="text-center align-middle" id="igtotal"></th>
                                       <th class="text-center align-middle" id="fg" style="z-index: 1;"></th>
                                 </tr>
                                 @php
                                       $female = 1;
                                       $count = 1;
                                 @endphp
                           @endif

                           <tr data-value="{{$grade->id}}" class="gradedetail" >
                                 <th>
                                       {{$count.'.'}} {{$grade->student}}
                                       @if($grade->gdstatus == 1)
                                          <span class="badge badge-success float-right gd_status" data-studid="{{$grade->studid}}">Submitted</span>
                                       @elseif($grade->gdstatus == 2)
                                          <span class="badge badge-primary float-right gd_status" data-studid="{{$grade->studid}}">Approved</span>
                                       @elseif($grade->gdstatus == 3)
                                          <span class="badge badge-warning float-right gd_status" data-studid="{{$grade->studid}}">Pending</span>
                                       @elseif($grade->gdstatus == 4)
                                          <span class="badge badge-info float-right gd_status" data-studid="{{$grade->studid}}">Posted</span>
                                       @endif
                                 </th>
                                 <th  class="student_view text-center align-middle perst" data-studid="{{$grade->studid}}" data-id="{{$grade->id}}" data-status="{{$grade->gdstatus}}"></th>
                                 @for($x = 1 ; $x < 10; $x++)
                                       @php
                                             $wsstring = 'ww'.$x;  
                                       @endphp
                                       <td class="text-center align-middle">{{$grade->$wsstring}}</td>
                                 @endfor
                                 <td class="text-center align-middle">{{$grade->ww0}}</td>
                                 <th class="text-center align-middle">{{$grade->wwtotal}}</th>
                                 @if($schoolinfo->snr == 'spct')
                                       <th class="text-center align-middle">{{$grade->wwhps}}</th>
                                 @endif
                                 <th class="text-center align-middle">{{number_format($grade->wwps,2)}}</th>
                                 <th class="text-center align-middle">{{number_format($grade->wwws,2)}}</th>
                                 @for($x = 1 ; $x < 10; $x++)
                                       @php
                                             $ptstring = 'pt'.$x;  
                                       @endphp
                                       <td class="text-center align-middle">{{$grade->$ptstring}}</td>
                                 @endfor
                                 <td class="text-center align-middle">{{$grade->pt0}}</td>
                                 <th class="text-center align-middle">{{$grade->pttotal}}</th>
                                 @if($schoolinfo->snr == 'spct')
                                       <th class="text-center align-middle">{{$grade->pthps}}</th>
                                 @endif
                                 <th class="text-center align-middle">{{number_format($grade->ptps,2)}}</th>
                                 <th class="text-center align-middle">{{number_format($grade->ptws,2)}}</th>
                                 @if($percentage[0]->qa != null || $percentage[0]->qa != 0)
                                       @if($schoolinfo->snr == 'spct')
                                             <td style="top: 31px;" class="text-center align-middle">{{$grade->qa1}}</td>
                                             <td style="top: 31px;" class="text-center align-middle">{{$grade->qa2}}</td>
                                             <th style="top: 31px;" class="text-center align-middle">{{$grade->qatotal}}</th>
                                             <th style="top: 31px;" class="text-center align-middle">{{$grade->qahps}}</th>
                                             <th style="top: 31px;" class="text-center align-middle">{{number_format($grade->qaps,2)}}</th>
                                             <th style="top: 31px;" class="text-center align-middle">{{number_format($grade->qaws,2)}}</th>
                                       @else
                                             <td style="top: 31px;" class="text-center align-middle">{{$grade->qa1}}</td>
                                             <td style="top: 31px;" class="text-center align-middle">{{$grade->qa2}}</td>
                                             <td style="top: 31px;" class="text-center align-middle">{{$grade->qatotal}}</td>
                                             <th style="top: 31px;" class="text-center align-middle">{{number_format($grade->qaps,2)}}</th>
                                             <th style="top: 31px;" class="text-center align-middle">{{number_format($grade->qaws,2)}}</th>
                                       @endif
                                 @endif

                                 

                                 <th class="text-center align-middle">{{number_format ( $grade->ig , 2)}}</th>
                                 <th  class="text-center align-middle {{$grade->qg >= 75 ? 'bg-success':'bg-danger'}}">{{$grade->qg}}</th>
                                 
                           </tr>
                           @php
                              $count += 1;
                           @endphp
                     @endforeach
               @else
                     <tr>
                           <th></th>
                           <td colspan="30"><i class="text-danger">No file upload. Please try uploading a file.</i></td>
                     </tr>

               @endif
         </tbody>
   </table>
</div>

<div class="row mt-2">
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
            <p class="text-muted mb-0 pl-3">Not Submitted : {{collect($grades)->where('gdstatus',0)->count()}}</p>
            <p class="text-muted mb-0 pl-3">Submitted : {{collect($grades)->where('gdstatus',1)->count()}}</p>
            <p class="text-muted mb-0 pl-3">Approved: {{collect($grades)->where('gdstatus',2)->count()}}</p>
      </div>
      <div class="col-md-2">
            <strong></i>&nbsp;</strong>
            <p class="text-muted mb-0 pl-3">Pending : {{collect($grades)->where('gdstatus',3)->count()}}</p>
            <p class="text-muted mb-0 pl-3">Posted: {{collect($grades)->where('gdstatus',4)->count()}}</p>
      </div>
</div>

<script>
   $(document).ready(function(){

            var hps = @json($hps)[0];
            var utype = @json(auth()->user()->type);
            var get_coor = @json($get_coor);

            $('#label_dateuploaded').text(hps.uploadeddatetime)
            $('#approve_grade').removeAttr('disabled')
            $('#post_grade').removeAttr('disabled')
            $('#pending_grade').removeAttr('disabled')
            $('#unpost_grade').removeAttr('disabled')
            $('.student_view').attr('hidden','hidden')

            if(hps.submitted == 0){
                  $('#label_status').text('Not submitted')
                  $('#input_ecr').removeAttr('disabled')
                  $('#upload_ecr_button').removeAttr('disabled')
                  if(hps.status == 3){
                        $('#label_status, #ecr_status').text('Pending')
                  }
                  $('.student_view').removeAttr('hidden')
                  $('.student_view.perst').each(function(){
                        var temp_checked = 'checked="checked"'
                        var temp_disabled = ''
                        var temp_text = $(this).html()
                        var dataid = $(this).attr('data-id')
                        var studid = $(this).attr('data-studid')
                        if($(this).attr('data-id') == undefined){
                              $(this)[0].innerHTML = '<input type="checkbox" checked="checked" class="exclude select_all">'
                        }else{
                              $(this)[0].innerHTML = '<input type="checkbox" '+temp_checked+' '+temp_disabled+' class="exclude" data-id="'+dataid+'" data-studid="'+studid+'">'
                        }
                  })
            }else{
               $('#input_ecr').attr('disabled','disabled')
               $('#upload_ecr_button').attr('disabled','disabled')
               if(hps.status == 1 || hps.status == 0){
                     if(hps.coorapp == null || hps.coorapp == 0){
                        $('#label_status, #ecr_status').text('Submitted')
                        $('#label_status').text('Submitted')
                     }
                     else{
                        $('#label_status, #ecr_status').text('Coor Approved')
                        $('#label_status').text('Coor Approved')
                     }
               }else if(hps.status == 4){
                     $('#label_status, #ecr_status').text('Posted')
               }else if(hps.status == 2){
                        $('#label_status, #ecr_status').text('Principal Approved')
               }

               if(utype == 2 || get_coor == 22 || get_coor == 27){
                  $('.student_view').removeAttr('hidden')
                  $('.student_view.perst').each(function(){
                    
                        var temp_checked = 'checked="checked"'
                        var temp_disabled = ''
                        if($(this).attr('data-status') == 3){
                              temp_checked = ''
                              temp_disabled = 'disabled="disabled"'
                        }
                        var temp_text = $(this).html()
                        var dataid = $(this).attr('data-id')
                        var studid = $(this).attr('data-studid')
                        if($(this).attr('data-id') == undefined){
                              $(this)[0].innerHTML = '<input type="checkbox" checked="checked" class="exclude select_all">'
                        }else{
                              $(this)[0].innerHTML = '<input type="checkbox" '+temp_checked+' '+temp_disabled+' class="exclude" data-id="'+dataid+'" data-studid="'+studid+'">'
                        }
                  })
               }

               $('#label_datesubmitted').text(hps.date_submitted)
         }

         var grades = @json($grades)

         const Toast = Swal.mixin({
                     toast: true,
                     position: 'top',
                     showConfirmButton: false,
                     timer: 2000,
               })

         if(grades.length == 0){
               Toast.fire({
                     type: 'warning',
                     title: 'No records found.'
               })
               $('#ecr_submit').attr('disabled','disabled')
         }else{
               Toast.fire({
                     type: 'info',
                     title: 'Grades found.'
               })
               if(hps.submitted == 0){
                     try{
                           $('#ecr_submit').removeAttr('disabled')
                           $('#ecr_submit')[0].innerHTML = '<i class="far fa-share-square"></i> Submit'
                     }catch(err){

                     }
               }else{
                     try{
                           $('#ecr_submit').attr('disabled','disabled')
                           $('#ecr_submit')[0].innerHTML = '<i class="fas fa-check"></i> Submitted'
                     }catch(err){

                     }
               }
         }

   })

</script>