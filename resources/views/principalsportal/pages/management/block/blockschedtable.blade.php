@foreach($subjects as $item)

            @php
                  $countSched = collect($schedulesummary)->where('subjid',$item->id)->count();
                  $first = true;
            @endphp
            
            @if($countSched > 0)
         
                  @foreach (collect($schedulesummary)->where('subjid',$item->id) as $scheditem)
                        <tr style="font-size:11px">
                              @if($first)
                                    @if($item->acadprogid == 5)
                                          @if($item->type==1)
                                                <td rowspan="{{$countSched}}" class="pl-2 text-center text-white align-middle bg-red-50" sort-id="1">C</td>
                                          @elseif($item->type==2)
                                                <td rowspan="{{$countSched}}" class="pl-2 text-center text-white align-middle bg-blue-50" sort-id="3">SP</td>
                                          @else
                                                <td rowspan="{{$countSched}}" class="pl-2 text-center text-white align-middle bg-green-50" sort-id="2">AS</td>
                                          @endif
                                    @else
                                          <td sort-id="1"></td>
                                    @endif
                                    <td rowspan="{{$countSched}}" class="align-middle">
                                          {{$item->subjcode}} - {{$item->subjdesc}} <br>
                                          <button class="btn btn-primary btn-xs add_block_sched" data-id="{{$item->id}}"><i class="fas fa-plus"></i> Add Schedule</button>
                                    </td>
                                    @php
                                          $first = false;
                                    @endphp
                              @endif
                              <td class="text-center align-middle" style="font-size:11px">
                                    <span class="text-primary text-bold">{{$scheditem->subjinfo->schedclass}}</span>
                                    <br>{{$scheditem->daysum}}<br>
                                    {{-- {{collect($scheditem)}} --}}
                                   
                              </td>
                              <td class="text-center align-middle">{{\Carbon\Carbon::create($scheditem->subjinfo->stime)->isoFormat('hh:mm a')}}<br>{{\Carbon\Carbon::create($scheditem->subjinfo->etime)->isoFormat('hh:mm a')}}</td>
                              <td class="text-center align-middle">{{$scheditem->subjinfo->roomname}}</td>
                              <td class="text-center align-middle">{{$scheditem->subjinfo->firstname}}<br>{{$scheditem->subjinfo->lastname}}</td>

                              <td class="text-center align-middle">
                                    <div class="dropdown block_dropdown" >
                                          <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                          </button>
                                          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item remove_block_sched" data-id="{{\Crypt::encrypt($scheditem->subjinfo->id)}}"><i class="fas fa-trash-alt text-danger pr-1"></i> Remove Schedule</a>
                                                {{-- <a class="dropdown-item update_teacher" href="#" data-id="{{$scheditem->subjinfo->teacherid}}" data-subjid="{{$scheditem->subjinfo->subjid}}"><i class="fas fa-user text-danger pr-1"></i> Update Teacher</a> --}}
                                                <a    class="dropdown-item update_sched" 
                                                      href="#" 
                                                      data-id="{{$scheditem->subjinfo->teacherid}}" 
                                                      data-subjid="{{$scheditem->subjinfo->subjid}}" data-type="block"
                                                      data-headerid="{{$scheditem->subjinfo->detailid}}"
                                                      >
                                                      <i class="fas fa-edit text-primary pr-1" ></i> 
                                                      Update Schedule
                                                </a>
                                          </div>
                                    </div>
                              </td>
                              
                        </tr>
                  @endforeach
           
            @else
                  <tr style="font-size:11px">
                        @if($item->acadprogid == 5)
                              @if($item->type==1)
                                    <td  class="pl-2 text-center text-white align-middle bg-red-50" sort-id="1">C1</td>
                              @elseif($item->type==2)
                                    <td class="pl-2 text-center text-white align-middle bg-blue-50" sort-id="3">SP</td>
                              @else
                                    <td class="pl-2 text-center text-white align-middle bg-green-50" sort-id="2">AS</td>
                              @endif
                        @else
                              <td sort-id="1"></td>
                        @endif
                  <td class="align-middle">
                        {{$item->subjcode}} - {{$item->subjdesc}} <br>
                        <button class="btn btn-primary btn-xs add_block_sched"  data-id="{{$item->id}}" data-type="block"><i class="fas fa-plus"  "></i> Add Schedule</button>
                  </td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>

            </tr>
            @endif
          
  


@endforeach