@if($data[0]->count > 0)

      @php
            $width = 80;
            $subjCount = count($college_classsched);
            $minwidth = ( 43 *  $subjCount ) + 100;
            if($subjCount > 0){
                  $unitWidth = ( ( $width / $subjCount ) / 3 ) * 1;
                  $subjCodeWidth = ( ( $width / $subjCount ) / 3 ) * 2;
            }
      @endphp

      <input type="hidden" value="{{$data[0]->count}}" id="searchCount">

      <table class="table table-bordered" style="min-width:2000px; font-size:11px" >
            <tr>
                  <td width="5%" class="text-center align-middle">No.</td>
                  <td width="15%" colspan="2"  class="text-center align-middle">NAME OF STUDENTS</td>
            
                  @foreach ($college_classsched as $item)
                  <td class="text-center" width="{{$subjCodeWidth}}%">SUBJECT CODE</td>
                  <td class="text-center align-middle" width="{{$unitWidth}}%">UNITS</td>
                  @endforeach
            </tr>
            <tr>
            @foreach ($data[0]->data as $key=>$item)
                  <tr>
                        <td>{{ ( $key + 1 ) + $skip}}</td>
                        <td>{{$item->lastname}}</td>
                        <td>{{$item->firstname}}</td>

                        @foreach ($college_classsched as $scheditem)
                              @php
                                    $matchedSched = collect($item->sched)
                                                            ->where('schedid',$scheditem->schedid)
                                                            ->first()
                                                            ;
                              @endphp
                              @if( isset($matchedSched->subjCode))
                                    <td class="text-center align-middle bg-success" >{{$matchedSched->subjCode}}</td>
                                    <td class="text-center align-middle bg-success">{{$matchedSched->lecunits + $matchedSched->labunits}}</td>
                              @else
                                    <td class="text-center align-middle bg-danger" >N/A</td>
                                    <td class="text-center align-middle bg-danger">N/A</td>
                              @endif
                        @endforeach

                  </tr>
            @endforeach
      </table>

@else
      <table width="100%">
            <tr>
                  <th class="text-center bg-danger">No students enrolled</th>
            </tr>
      </table>
            
@endif