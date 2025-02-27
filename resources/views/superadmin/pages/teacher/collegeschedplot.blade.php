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
                  <tr style="font-size:.7rem !important">
                        <td class="p-2 align-middle text-center" rowspan="{{count($sched->schedule)}}">
                              <input type="checkbox" class="sched_list" data-id="{{$sched->id}}" data-tid="{{$sched->tid}}">
                        </td>
                        <td class="p-2 align-middle" rowspan="{{count($sched->schedule)}}">
                              <p class="mb-0">{{$sched->sectionDesc}}</p>
                              <p class="text-muted mb-0" style="font-size:.7rem">{{$sched->levelname}} @if(isset($sched->courseabrv)) - {{$sched->courseabrv}} @endif</p>
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
                                    <td class="p-2 align-middle" rowspan="{{count($sched->schedule)}}">
                                          <p class="mb-0">{{$sched->teacher}}</p>
                                          <p class="text-muted mb-0" style="font-size:.7rem">{{$sched->teacherid}}</p>
                                    </td>
                                    <td class="p-2 align-middle text-center" rowspan="{{count($sched->schedule)}}">
                                          {{$sched->studentcount}}
                                    </td>
                                    @php
                                          $first_id = $item->sched_count;
                                          $first = false;
                                    @endphp
                              @endif
                        @endforeach
                  </tr>
                  @foreach (collect($sched->schedule)->where('sched_count','!=',$first_id)->values() as $item)
                        <tr style="font-size:11px !important">
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
                  <tr  style="font-size:11px !important">
                        <td class="text-center align-middle"></td>
                        <td  class="p-2 align-middle" style="font-size:11px !important">
                              <p class="mb-0">{{$sched->sectionDesc}}</p>
                              <p class="text-muted mb-0" style="font-size:.7rem">{{$sched->levelname}}</p>
                        </td>
                        <td class="text-center align-middle">{{$sched->units}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="p-2 align-middle">
                              <p class="mb-0">{{$sched->teacher}}</p>
                              <p class="text-muted mb-0" style="font-size:.7rem">{{$sched->teacherid}}</p>
                        </td>
                        <td class="p-2 text-center align-middle">{{$sched->studentcount}}</td>
                  <tr>
            @endif
      @endforeach
@else
      <tr><td colspan="7"><i class="text-danger">No schedule found.</i></td></tr>
@endif