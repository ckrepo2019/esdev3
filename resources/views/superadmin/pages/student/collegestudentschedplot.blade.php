@if(count($schedule) > 0)

      @foreach($schedule as $sched)

                  @php  
                        $schedstatus = $sched->schedstatus != 'REGULAR' ? $sched->schedstatus : '' ;
                        $dropped = $schedstatus == 'DROPPED' ? 'dropped' : '';
                  @endphp
      
            @if(count($sched->schedule) > 0)
                  @php
                        $first = true;
                        $first_id = null;
                  @endphp
                  <tr style="font-size:11px !important" class="schedtr {{$dropped}}" data-id="{{$sched->schedid}}">
                        @if($sched->sectionDesc != 'Schedule is not assigned to a section.')
                              <td class="align-middle stud_sect" data-id="{{$sched->sectionID}}" rowspan="{{count($sched->schedule)}}">
                                    @if(!$dropped)
                                          <a href="javascript:void(0)" data-id="{{$sched->sectionID}}" class="mark_as_setionid">{{$sched->sectionDesc}}</a>
                                    @else
                                          <span>{{$sched->sectionDesc}}</span>
                                    @endif
                              </td>
                        @else
                              <td class="align-middle stud_sect" rowspan="{{count($sched->schedule)}}">
                                    <span>{{$sched->sectionDesc}}</span>
                              </td>
                        @endif
                        <td class="p-2 align-middle" rowspan="{{count($sched->schedule)}}">
                              <p class="mb-0">{{$sched->subjDesc}}</p>
                              <p class="text-muted mb-0" style="font-size:.7rem">{{$sched->subjCode}} <span class="badge badge-info">{{$schedstatus}}</span></p>
                        </td>
                        <td class="p-2 align-middle text-center" rowspan="{{count($sched->schedule)}}">
                              <p class="mb-0">{{$sched->units}}</p>
                        </td>
                        @foreach ($sched->schedule as $item)
                              @if($first)
                                    <td class="p-2 text-center align-middle">
                                          @if($item->classification != null)
                                                <span class="text-primary text-bold">{{$item->classification}}</span>
                                                <br>
                                          @endif
                                          {{$item->day}}
                                    </td>
                                    <td class="p-2 text-center align-middle">{{$item->start}}<br>{{$item->end}}</td>
                                    <td class="p-2 text-center align-middle">{{$item->roomname}}</td>
                                    <td class="p-2 text-center align-middle" rowspan="{{count($sched->schedule)}}">
                                          <p class="mb-0">{{$sched->teacher}}</p>
                                          <p class="text-muted mb-0" style="font-size:.7rem">{{$sched->teacherid}}</p>
                                    </td>
                                    @php
                                          $first_id = $item->sched_count;
                                          $first = false;
                                    @endphp
                              
                              @endif
                        @endforeach
                        <td class="align-middle text-center" rowspan="{{count($sched->schedule)}}">
                              {{-- @if(!$dropped) --}}
                                    <a href="javascript:void(0)" class="remove_schedule" data-id="{{$sched->schedid}}" data-schedstat="{{$sched->schedstatus}}"><i class="far fa-trash-alt text-danger"></i></a>
                              {{-- @endif --}}
                        </td>
                  </tr>
                  @foreach (collect($sched->schedule)->where('sched_count','!=',$first_id)->values() as $item)
                        <tr style="font-size:11px !important" class="schedtr {{$dropped}}" data-id="{{$sched->schedid}}">
                              <td class="p-2 text-center align-middle">
                                    @if($item->classification != null)
                                          <span class="text-primary text-bold">{{$item->classification}}</span>
                                          <br>
                                    @endif
                                    {{$item->day}}
                              </td>
                              <td class="p-2 text-center align-middle"> {{$item->start}}<br>{{$item->end}}</td>
                              <td class="p-2 text-center align-middle">{{$item->roomname}}</td>
                        </tr>
                  @endforeach
            @else
                  <tr style="font-size:11px !important" class="schedtr {{$dropped}}" data-id="{{$sched->schedid}}">
                        @if($sched->sectionDesc != 'Schedule is not assigned to a section.')
                              <td class="align-middle stud_sect" data-id="{{$sched->sectionID}}">
                                    @if(!$dropped)
                                          <a href="javascript:void(0)" data-id="{{$sched->sectionID}}" class="mark_as_setionid">{{$sched->sectionDesc}}</a>
                                    @else
                                          <span>{{$sched->sectionDesc}}</span>
                                    @endif
                              </td>
                        @else
                              <td class="align-middle stud_sect">
                                    <span>{{$sched->sectionDesc}}</span>
                              </td>
                        @endif
                        
                        <td  class="p-2 align-middle">
                              <p class="mb-0">{{$sched->subjDesc}}</p>
                              <p class="text-muted mb-0" style="font-size:.7rem">{{$sched->subjCode}} <span class="badge badge-info">{{$schedstatus}}</span></p>
                        </td>
                        <td class="p-2  align-middle text-center">
                              <p class="mb-0">{{$sched->units}}</p>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="p-2 text-center align-middle">
                              <p class="mb-0">{{$sched->teacher}}</p>
                              <p class="text-muted mb-0" style="font-size:.7rem">{{$sched->teacherid}}</p>
                        </td>
                        <td class=" text-center align-middle">
                              {{-- @if(!$dropped) --}}
                                    <a href="javascript:void(0)" class="remove_schedule" data-id="{{$sched->schedid}}" data-schedstat="{{$sched->schedstatus}}"><i class="far fa-trash-alt text-danger"></i></a>
                              {{-- @endif --}}
                        </td>
                  <tr>

            @endif
      @endforeach
      <tr style="font-size:11px !important">
            <th class="text-right" colspan="2">Total Units</th>
            <td class="text-center">{{collect($schedule)->where('schedstatus','!=','DROPPED')->sum('units')}}</td>
            <td colspan="5"></td>
      </tr>

      
@else
      <tr>
            <td colspan="8" class="text-center align-middle">NO SCHEDULE ADDED</td>
      </tr>

@endif

<script>
      var studsched = @json($schedule)

      $.each(all_sched,function(a,b){
            var check = studsched.filter(x=>x.schedid == b.dataid)
            if(check.length > 0){
                  b.selected = 1;
            }else{
                  b.selected = 0;
            }
      })

      // display_sched_csl()
     
</script>