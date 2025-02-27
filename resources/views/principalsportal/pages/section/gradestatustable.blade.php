
@if(count($classassignsubj) > 0)
      @foreach($classassignsubj as $key=>$subject)
            <tr id="{{$key}}">
                  <td class="pt-2 pb-2">{{$subject->subjcode}}</td>
                  @for($x = 1 ; $x <=4; $x++)
                        @php
                        $availablegrade = 0;
                        @endphp

                        @if(auth()->user()->type == 2 || (isset($refid->refid) && $refid->refid == 22))

                        @foreach($subject->gradestatus as $gstatus)

                              @if(($gstatus->status=='0' || $gstatus->status=='1')  && $gstatus->quarter == $x && $gstatus->submitted == 1)
                                    <td  class="pt-2 pb-2"><div class=" badge badge-success w-100">Submitted</div></td>
                                    @php
                                    $availablegrade+=1;  
                                    break;   
                                    @endphp
                              @elseif($gstatus->status=='3' && $gstatus->quarter == $x)
                                    <td  class="pt-2 pb-2"><div class=" badge badge-warning w-100">Pending</div></td>
                                    @php
                                    $availablegrade+=1;  
                                    break;   
                                    @endphp
                              @elseif($gstatus->status=='4' && $gstatus->quarter == $x)
                                    <td  class="pt-2 pb-2"><div class=" badge badge-info w-100">Posted</div></td>
                                    @php
                                    $availablegrade+=1;  
                                    break;   
                                    @endphp
                              @elseif($gstatus->status=='2' && $gstatus->quarter == $x)
                                    <td  class="pt-2 pb-2"><div class=" badge badge-primary w-100">Approved</div></td>
                                    @php
                                    $availablegrade+=1;  
                                    break;   
                                    @endphp
                              @endif
                        @endforeach
                        @if($availablegrade==0)
                              <td></td>
                        @endif
                        @else
                        <td></td>
                        @endif

                  @endfor
            </tr>
      @endforeach
@else
      <tr><td colspan="5" class="text-center">NO SUBJECTS ADDED</td></tr>
@endif
      