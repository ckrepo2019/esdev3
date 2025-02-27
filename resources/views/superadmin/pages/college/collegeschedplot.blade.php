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
                  <td class=" p-0 text-center align-middle" rowspan="{{count($sched->schedule)}}">

                        @if($sched->issubjsched == 0)
                              <a href="#sched_plot_holder" class="mb-2 remove_subject mt-1" data-id="{{$sched->id}}"><i class="far fa-trash-alt text-danger"></i></a>
                        @endif
                  </td>
                  <td class=" p-0 text-center align-middle" rowspan="{{count($sched->schedule)}}">
                        <a style="font-size:.75rem !important"  href="#sched_plot_holder" class="add_sched" data-id="{{$sched->id}}" data-subjdesc="{{$sched->subjDesc}}">Add Sched</a>
                        {{-- @if($sectionttype == 2)
                              <a style="font-size:9px !important"  href="#sched_plot_holder" class="mt-1 remove_subject  btn-sm btn-danger  btn-block" data-id="{{$sched->id}}">Remove Subject</a>
                        @endif --}}
                        {{-- <a style="font-size:9px !important"  href="#sched_plot_holder" class="mb-2 mt-1 remove_subject  btn-sm btn-danger  btn-block" data-id="{{$sched->id}}">Remove Subject</a> --}}
                  </td>
                  <td class=" p-0 align-middle  pl-2" rowspan="{{count($sched->schedule)}}">
                        <p class="mb-0" style="font-size:.85rem !important">{{$sched->subjDesc}}</p>
                        <p class="text-muted mb-0" style="font-size:.7rem">{{$sched->subjCode}} <i class="text-danger">{{$type}}</i></p>
                  </td>
                  <td class=" p-0 align-middle text-center" rowspan="{{count($sched->schedule)}}">{{$sched->units}} a</td>
                  @foreach ($sched->schedule as $item)
                        @if($first)
                              <td class="text-center align-middle p-0">
                                    @if(isset($item->classification))
                                          <span class="text-primary text-bold">{{$item->classification}}</span><br>
                                    @endif
                                    {{$item->day}}
                              </td>
                              <td class=" text-center align-middle p-0">{{$item->start}}<br>{{$item->end}}</td>
                              <td class=" text-center align-middle p-0">{{$item->roomname}}</td>
                              <td class=" text-center align-middle p-0" rowspan="{{count($sched->schedule)}}">
                                    <p class="mb-0">{{$sched->teacher}}</p>
                                    <p class="text-muted mb-0" style="font-size:.7rem">{{$sched->teacherid}}</p>
                              </td>
                              <td class=" p-0 align-middle text-center" rowspan="{{count($sched->schedule)}}"> <a href="javascript:void(0)" data-id="{{$sched->id}}" data-text="{{$sched->subjCode}} - {{$sched->subjDesc}}" class="edit_capacity">{{$sched->capacity != null ? $sched->capacity : 0}}</a></td>

                              <td class="text-center align-middle p-0" rowspan="{{count($sched->schedule)}}"><a href="javascript:void(0)" class="sched_list_students" data-id="{{$sched->id}}" data-text="{{$sched->subjCode}} - {{$sched->subjDesc}}">{{$sched->studentcount}}</a> / <a href="javascript:void(0)" data-id="{{$sched->id}}" data-text="{{$sched->subjCode}} - {{$sched->subjDesc}}" class="sched_list_loaded_students">{{$sched->studentcountloaded}}</a></td>
                             
                              <td class="text-center align-middle p-0"> 
                                    <a href="javascript:void(0)" class="update_schedule" data-id="{{$item->detailid}}" data-header="{{$sched->id}}"><i class="far fa-edit text-primary"></i></a>
                              </td>
                              <td class="text-center align-middle p-0"> 
                                    <a href="javascript:void(0)" class="remove_schedule" data-id="{{$item->detailid}}"><i class="far fa-trash-alt text-danger"></i></a>
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
                        <td class="text-center align-middle  p-0">
                              @if(isset($item->classification))
                                    <span class="text-primary text-bold">{{$item->classification}}</span><br>
                              @endif
                              {{$item->day}}
                        </td>
                        <td class="text-center align-middle  p-0"> {{$item->start}}<br>{{$item->end}}</td>
                        <td class="text-center align-middle  p-0">{{$item->roomname}}</td>
                        <td class="text-center align-middle p-0"> 
                              <a href="javascript:void(0)" class="update_schedule" data-id="{{$item->detailid}}" data-header="{{$sched->id}}"><i class="far fa-edit text-primary"></i></a>
                        </td>
                        <td class="text-center align-middle  p-0"> 
                            <a href="javascript:void(0)" class="remove_schedule" data-id="{{$item->detailid}}" ><i class="far fa-trash-alt text-danger"></i></a>
                        </td>
                  </tr>
            @endforeach
      @else
            <tr  style="font-size:11px !important">
                  <td  class="text-center align-middle p-0">
                        @if($sched->issubjsched == 0)
                              <a href="#sched_plot_holder" class="mb-2 remove_subject mt-1" data-id="{{$sched->id}}"><i class="far fa-trash-alt text-danger"></i></a>
                        @endif
                  </td>
                  <td class="text-center align-middle p-0">
                        <a  style="font-size:.75rem !important"   href="#sched_plot_holder" class="add_sched " data-id="{{$sched->id}}" data-subjdesc="{{$sched->subjDesc}}">Add Sched</a><br>
                        {{-- <a style="font-size:9px !important"  href="#sched_plot_holder" class="mb-2 remove_subject mt-1" data-id="{{$sched->id}}">Remove Subject</a> --}}
                        {{-- @if($sectionttype == 2)
                              <a style="font-size:9px !important"  href="#sched_plot_holder" class="remove_subject mt-1 btn-sm btn-danger  btn-block" data-id="{{$sched->id}}">Remove Subject</a>
                        @endif --}}
                  </td>
                  <td  class="align-middle  p-0 pl-2" style="font-size:11px !important">
                        <p class="mb-0"  style="font-size:.85rem !important">{{$sched->subjDesc}}</p>
                        <p class="text-muted mb-0" style="font-size:.7rem">{{$sched->subjCode}} <i class="text-danger">{{$type}}</i></p>
                  </td>
                  <td class="text-center align-middle  p-0">{{$sched->units}} b</td>
                  <td class=" p-0"></td>
                  <td class=" p-0"></td>
                  <td class=" p-0"></td>
                  <td class=" p-0 text-center align-middle">
                        <p class="mb-0">{{$sched->teacher}}</p>
                        <p class="text-muted mb-0" style="font-size:.7rem">{{$sched->teacherid}}</p>
                  </td>
                  <td class=" p-0 align-middle text-center"><a href="javascript:void(0)" data-id="{{$sched->id}}" data-text="{{$sched->subjCode}} - {{$sched->subjDesc}}" class="edit_capacity">{{$sched->capacity != null ? $sched->capacity : 0}}</a></td>

                  <td class="text-center align-middle"><a class="sched_list_students" href="javascript:void(0)" data-id="{{$sched->id}}" data-text="{{$sched->subjCode}} - {{$sched->subjDesc}}">{{$sched->studentcount}}</a> / <a href="javascript:void(0)" data-id="{{$sched->id}}" data-text="{{$sched->subjCode}} - {{$sched->subjDesc}}" class="sched_list_loaded_students">{{$sched->studentcountloaded}}</a></td>

                  <td class=" p-0"></td>
                  <td class=" p-0"></td>
            <tr>
      @endif
@endforeach
<tr style="font-size:11px !important">
      <th class="text-right" colspan="3">Total Units</th>
      <td class="text-center">{{collect($schedule)->sum('units')}}</td>
      <td colspan="5"></td>
</tr>
