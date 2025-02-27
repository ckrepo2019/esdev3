@if(count($schedule) > 0)
      @php
            $totalunits = 0;
      @endphp
      @foreach($schedule as $sched)

                  @php  
                        $comp = '';
                        $consolidate = '';
                        $spec = '';
                        $type = '';
                        $con_hidden = '';
                        $units = '';
                        if($sched->datatype == 'juniorhigh'){
                              if($sched->isCon == 1){
                                    $con_hidden = 'hidden="hidden"' ;
                                    $consolidate = '- <i class="text-success">Consolidated</i>';
                                    $temp_subjcom = collect($schedule)->where('subjCom',$sched->subjid)->values();
                                    if(count($temp_subjcom) > 0){
                                          $comp .= '[ ';
                                          foreach($temp_subjcom as $item){
                                                $comp .= $item->subjcode . ', ';
                                          }
                                          $comp = substr($comp,0,-2);
                                          $comp .= ' ]';
                                    }
                              }
                              if($sched->isSP == 1){
                                    $spec = '- Specialization';
                              }
                              if($sched->subjCom != null){
                                    $comp_subj = collect($schedule)->where('subjid',$sched->subjCom)->values();
                                    if(count($comp_subj) > 0){
                                          $spec = '-  '.$comp_subj[0]->subjcode.'  Component';
                                    }
                              
                              }
                        }else if($sched->datatype == 'college'){
                              $totalunits += $sched->units;
                              $units = '( Units:'.$sched->units.' )';
                        }
                        else{
                              if($sched->type == 1){
                                    $type = '- Core';
                              }else if($sched->type == 2){
                                    $type = '- Specialized';
                              }else if($sched->type == 3){
                                    $type = '- Applied';
                              }

                        }
                  @endphp
      
            @if(count($sched->schedule) > 0)
                  @php
                        $first = true;
                        $first_id = null;
                  @endphp
                  <tr style="font-size:.8rem !important" {{$con_hidden}}>
                        <td class="p-2 align-middle" rowspan="{{count($sched->schedule)}}">
                              <p class="mb-0">{{$sched->subjdesc}} <i class="text-danger">{{$comp}}</i></p>
                              <p class="text-muted mb-0" style="font-size:.8rem">{{$sched->subjcode}} <i class="text-danger">{{$units}}</i> <i class="text-danger">{{$type}}</i></p>
                        </td>
                        @foreach ($sched->schedule as $item)
                              @if($first)
                                    <td class="p-2 text-center align-middle" style="font-size:.7rem">
                                          <span class="text-primary text-bold">{{$item->classification}}</span>
                                          <br>{{$item->day}}
                                    </td>
                                    <td class="p-2 text-center align-middle">{{$item->start}}<br>{{$item->end}}</td>
                                    <td class="p-2 text-center align-middle">{{$item->roomname}}</td>
                                    <td class="p-2 text-center align-middle" rowspan="{{count($sched->schedule)}}">
                                          <p class="mb-0">{{$item->teacher}}</p>
                                    </td>
                                    @php
                                          $first_id = $item->sched_count;
                                          $first = false;
                                    @endphp
                              
                              @endif
                        @endforeach
                  </tr>
                  @foreach (collect($sched->schedule)->where('sched_count','!=',$first_id)->values() as $item)
                        <tr style="font-size:.8rem !important" {{$con_hidden}}>
                              <td class="p-2 text-center align-middle" style="font-size:.7rem">
                                    <span class="text-primary text-bold">{{$item->classification}}</span>
                                    <br>{{$item->day}}
                              </td>
                              <td class="p-2 text-center align-middle"> {{$item->start}}<br>{{$item->end}}</td>
                              <td class="p-2 text-center align-middle">{{$item->roomname}}</td>
                        </tr>
                  @endforeach
            @else
                  <tr {{$con_hidden}}>
                        <td  class="p-2 align-middle">
                              <p class="mb-0">{{$sched->subjdesc}} <i class="text-danger">{{$comp}}</i></p>
                              <p class="text-muted mb-0" style="font-size:.8rem">{{$sched->subjcode}} <i class="text-danger">{{$units}}</i> <i class="text-danger">{{$type}}</i></p>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                  
                  <td>

            @endif
      @endforeach

      <script>
            $(document).ready(function(){
                  var totalunits = @json($totalunits);
                  if(totalunits != 0){
                        $('#total_unit').text('( Units:'+totalunits+' )')
                  }else{
                        $('#total_unit').text('')
                  }
            })
      </script>
@else
      <tr >
           <td colspan="5"><i>No available class schedule.</i></td>
      <tr>

@endif