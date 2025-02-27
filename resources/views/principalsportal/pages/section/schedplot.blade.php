@php
      $schedule = $schedule;
@endphp

@if(count($schedule) > 0)

      @foreach($schedule as $sched)

                  @php  

                        $schoolinfo = DB::table('schoolinfo')->select('abbreviation')->first();

                        $comp = '';
                        $consolidate = '';
                        $spec = '';
                        $type = '';
                        $strand = '';
                        $iscon = 0;
                        if($sched->datatype == 'juniorhigh'){
                             
                              if($sched->isCon == 1){
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
                                    $iscon = 1;
                              
                              }
                        }else{
                              if($sched->type == 1){
                                    $type = '- Core';
                              }else if($sched->type == 2){
                                    $type = '- Specialized';
                              }else if($sched->type == 3){
                                    $type = '- Applied';
                              }

                              if(strtolower($schoolinfo->abbreviation) == 'apmc'){

                                    $strand .= '[ ';
                                    foreach ($sched->subj_strand as $item) {
                                          $strand .= $item->strand.', ';
                                    }

                                    $strand = substr($strand,0,-2);

                                    $strand .= ' ]';

                              }


                        }

                  
                  @endphp
      
            @if(count($sched->schedule) > 0)
                  @php
                        $first = true;
                        $first_id = null;
                  @endphp
                  <tr style="font-size:11px !important">
                        <td class="p-2 align-middle" rowspan="{{count($sched->schedule)}}">
                              <p class="mb-0">{{$sched->subjdesc}} <i class="text-danger">{{$comp}}</i></p>
                              <p class="text-muted mb-0">{{$sched->subjcode}}  <i class="text-danger">{{$spec}}</i><i class="text-danger">{{$type}}</i> <span class="text-success">{{$strand}}</span> </p>
                              @if($isactive->isactive == 1)
                                    @if(isset($sched->isCon))
                                          @if($sched->isCon == 0)
                                                <button class="btn btn-primary btn-xs" id="add_sched" data-id="{{$sched->subjid}}" iscon="{{$iscon}}"><i class="fas fa-plus" ></i> Add Schedule</button>
                                          @endif
                                    @else
                                          <button class="btn btn-primary btn-xs" id="add_sched" data-id="{{$sched->subjid}}" iscon="{{$iscon}}"><i class="fas fa-plus" ></i> Add Schedule</button>
                                    @endif
                              @endif
                        </td>
                        @foreach ($sched->schedule as $item)
                              @if($first)
                                    <td class="p-2 text-center align-middle">
                                          <span class="text-primary text-bold">{{$item->classification}}</span>
                                          <br>{{$item->day}}
                                    </td>
                                    <td class="p-2 text-center align-middle">{{$item->start}}<br>{{$item->end}}</td>
                                    <td class="p-2 text-center align-middle">{{$item->roomname}}</td>
                                    <td class="p-2 text-center align-middle" rowspan="{{count($sched->schedule)}}">
                                          <p class="mb-0">{{$item->teacher}}</p>
                                          <p class="text-muted mb-0">{{$item->tid}}</p>
                                    </td>
                                    @php
                                          $first_id = $item->sched_count;
                                          $first = false;
                                    @endphp
                                    <td class="p-2 text-center align-middle">
                                          @if($isactive->isactive == 1)
                                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                      </button>
                                                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            <a class="dropdown-item update_sched" href="#" data-type="{{$sched->datatype}}" data-subjid="{{$sched->subjid}}" data-headerid="{{$item->detailid}}" data-id="{{$item->teacherid}}" iscon="{{$iscon}}"><i class="fas fa-edit text-primary pr-1"></i> Update Schedule</a>
                                                            {{-- <a class="dropdown-item remove_sched" href="#" data-id="{{$item->detailid}}"><i class="fas fa-trash-alt text-danger pr-1"></i> Remove Schedule</a> --}}
                                                      </div>
                                                </div>
                                          @endif
                                    </td>
                              @endif
                        @endforeach
                  </tr>
                  @foreach (collect($sched->schedule)->where('sched_count','!=',$first_id)->values() as $item)
                        <tr style="font-size:11px !important">
                              <td class="p-2 text-center align-middle">
                                    <span class="text-primary text-bold">{{$item->classification}}</span>
                                    <br>{{$item->day}}
                              </td>
                              <td class="p-2 text-center align-middle"> {{$item->start}}<br>{{$item->end}}</td>
                              <td class="p-2 text-center align-middle">{{$item->roomname}}</td>
                              <td class="p-2 text-center align-middle">
                                    @if($isactive->isactive == 1)
                                          <div class="dropdown">
                                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                      <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                      <a class="dropdown-item update_sched" href="#"  data-type="{{$sched->datatype}}" data-subjid="{{$sched->subjid}}" data-headerid="{{$item->detailid}}" data-id="{{$item->teacherid}}" iscon="{{$iscon}}"><i class="fas fa-edit text-primary pr-1"></i> Update Schedule</a>
                                                      {{-- <a class="dropdown-item remove_sched" href="#" data-id="{{$item->detailid}}"><i class="fas fa-trash-alt text-danger pr-1"></i> Remove Schedule</a> --}}
                                                
                                                </div>
                                          </div>
                                    @endif
                              </td>
                        </tr>
                  @endforeach
            @else
                  <tr style="font-size:11px !important">
                        <td  class="p-2 align-middle">
                              <p class="mb-0">{{$sched->subjdesc}} <i class="text-danger">{{$comp}}</i></p>
                              <p class="text-muted mb-0" >{{$sched->subjcode}}  <i class="text-danger">{{$spec}}</i><i class="text-danger">{{$type}}</i> <span class="text-success">{{$strand}}</span></p>
                              @if($isactive->isactive == 1)
                                    @if(isset($sched->isCon))
                                          @if($sched->isCon == 0)
                                                <button class="btn btn-primary btn-xs" id="add_sched" data-id="{{$sched->subjid}}" iscon="{{$iscon}}"><i class="fas fa-plus" ></i> Add Schedule</button>
                                          @endif
                                    @else
                                          <button class="btn btn-primary btn-xs" id="add_sched" data-id="{{$sched->subjid}}" iscon="{{$iscon}}"><i class="fas fa-plus"></i> Add Schedule</button>
                                    @endif
                              @endif
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="p-2 text-center align-middle">
                              @if(strtolower($schoolinfo->abbreviation) == 'apmc')
                                    <div class="dropdown">
                                          <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                          </button>
                                          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item update_subject" data-subjid="{{$sched->subjid}}" href="#"><i class="far fa-edit text-success pr-1"></i> Update Subject</a>
                                          </div>
                                    </div>
                              @endif
                        </td>
                  </tr>

            @endif
      @endforeach
@else
      <tr>
            <td colspan="6"><i>No subjects added. Please visit <a href="/setup/subject/plot" target="_blank">Subject Plot</a> to add subject.</i></td>
      </tr>

@endif