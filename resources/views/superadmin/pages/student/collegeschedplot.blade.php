@if(count($schedule) > 0)
      @foreach($schedule as $sched)

                  @php  
                        $comp = '';
                        $consolidate = '';
                        $spec = '';
                        $type = '';
                  @endphp
      
            @if(count($sched->schedule) > 0)
                  @php
                        $first = true;
                        $first_id = null;
                  @endphp
                  <tr style="font-size:11px !important">
                        <td class="text-center align-middle" rowspan="{{count($sched->schedule)}}">  
                              <a style="font-size:16px !important"  href="#sched_plot_holder" class="add_sched" data-id="{{$sched->id}}"><i class="fas fa-plus-square"></i></a>
                        </td>
                        <td class="align-middle">{{$sched->sectionDesc}}</td>
                        <td class="p-2 align-middle" rowspan="{{count($sched->schedule)}}">
                              <p class="mb-0">{{$sched->subjDesc}}</p>
                              <p class="text-muted mb-0" style="font-size:.7rem">{{$sched->subjCode}} <i class="text-danger">{{$type}}</i></p>
                        </td>
                        <td class="p-2 align-middle text-center" rowspan="{{count($sched->schedule)}}">
                              <p class="mb-0">{{$sched->units}}</p>
                        </td>
                        @foreach ($sched->schedule as $item)
                              @if($first)
                                    <td class="p-2 text-center align-middle">
                                          <span class="text-primary text-bold">{{$item->classification}}</span>
                                          <br>{{$item->day}}
                                    </td>
                                    <td class="p-2 text-center align-middle">{{$item->start}}<br>{{$item->end}}</td>
                                    <td class="p-2 text-center align-middle">{{$item->roomname}}</td>
                                   
                                    @php
                                          $first_id = $item->sched_count;
                                          $first = false;
                                    @endphp
                                    <td class="p-2 text-center align-middle" rowspan="{{count($sched->schedule)}}">
                                          <p class="mb-0">{{$sched->teacher}}</p>
                                          <p class="text-muted mb-0" style="font-size:.7rem">{{$sched->teacherid}}</p>
                                    </td>
                              @endif
                        @endforeach
                       
                  </tr>
                  @foreach (collect($sched->schedule)->where('sched_count','!=',$first_id)->values() as $item)
                        <tr style="font-size:11px !important">
                              <td class="align-middle">{{$sched->sectionDesc}}</td>
                              <td class="p-2 text-center align-middle">
                                    <span class="text-primary text-bold">{{$item->classification}}</span>
                                    <br>{{$item->day}}
                              </td>
                              <td class="p-2 text-center align-middle"> {{$item->start}}<br>{{$item->end}}</td>
                              <td class="p-2 text-center align-middle">{{$item->roomname}}</td>
                        </tr>
                  @endforeach
            @else
                  <tr style="font-size:11px !important">
                        <td class="text-center align-middle">  
                              <a style="font-size:16px !important" href="#sched_plot_holder" class="add_sched" data-id="{{$sched->id}}"><i class="fas fa-plus-square"></i></a>
                        </td>
                        <td class="align-middle">{{$sched->sectionDesc}}</td>
                        <td  class="p-2 align-middle">
                              <p class="mb-0">{{$sched->subjDesc}}</p>
                              <p class="text-muted mb-0" style="font-size:.7rem">{{$sched->subjCode}} <i class="text-danger">{{$type}}</i></p>
                        </td>
                        <td class="p-2 align-middle text-center">
                              <p class="mb-0">{{$sched->units}}</p>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="p-2 text-center align-middle">
                              <p class="mb-0">{{$sched->teacher}}</p>
                              <p class="text-muted mb-0" style="font-size:.7rem">{{$sched->teacherid}}</p>
                        </td>
                  <tr>

            @endif
      @endforeach
@else
      <tr>
            <td colspan="8" class="text-center align-middle">NO AVAILABLE SCHEDULE</td>
      </tr>

@endif