@foreach($classassignsubj as $key=>$subject)
      <tr id="{{$key}}">
            <td class="pt-2 pb-2">{{$subject->subjcode}}</td>
            @for($x = 1 ; $x <=4; $x++)
                  @php
                        $availablegrade = 0;
                  @endphp
                  @foreach($subject->gradestatus as $gstatus)

                        @if(($gstatus->status=='0' || $gstatus->status=='1')  && $gstatus->quarter == $x && $gstatus->submitted == 1)
                              <td  class="pt-2 pb-2"><a href="/principalPortalGradeInformation/{{$gstatus->gradeid}}"class=" d-block nav-link p-0 "><div class=" badge badge-success w-100">Submitted</div></a></td>
                              @php
                                    $availablegrade+=1;  
                                    break;   
                              @endphp
                        @elseif($gstatus->status=='3' && $gstatus->quarter == $x)
                              <td  class="pt-2 pb-2"><a href="/principalPortalGradeInformation/{{$gstatus->gradeid}}"class=" d-block nav-link p-0"><div class=" badge badge-warning w-100">Pending</div></a></td>
                              @php
                                    $availablegrade+=1;  
                                    break;   
                              @endphp
                        @elseif($gstatus->status=='4' && $gstatus->quarter == $x)
                              <td  class="pt-2 pb-2"><a href="/principalPortalGradeInformation/{{$gstatus->gradeid}}"class=" d-block nav-link p-0"><div class=" badge badge-info w-100">Posted</div></a></td>
                              @php
                                    $availablegrade+=1;  
                                    break;   
                              @endphp
                        @elseif($gstatus->status=='2' && $gstatus->quarter == $x)
                        <td  class="pt-2 pb-2"><a href="/principalPortalGradeInformation/{{$gstatus->gradeid}}"class=" d-block nav-link p-0"><div class=" badge badge-primary w-100">Approved</div></a</td>
                              @php
                                    $availablegrade+=1;  
                                    break;   
                              @endphp
                        @endif
                  @endforeach
                  @if($availablegrade==0)
                        <td></td>
                  @endif
            @endfor
      </tr>
@endforeach