<input type="hidden" value="{{$data[0]->count}}" id="searchCount">

<table class="table table-bordered table-sm">
      <thead>
            <tr>
                  <th width="20%">Section</th>
                  <th width="40%">Subject</th>
                  <th width="40%">Schedule</th>
            </tr>
      </thead>
      <tbody>
            @foreach ($data[0]->data as $item)
                  @php
                        $first = true;
                  @endphp
                  @foreach ($item as $leveltwoitem)
                        @if(count($item) > 0 && $first)
                              <tr>  
                                    <td  rowspan="{{count($item)}}" class="align-middle"><span style="font-size: 20px">{{$leveltwoitem->sectionDesc}}</span><br>{{$leveltwoitem->courseabrv}}</td>
                                    <td class="text-left align-middle pl-3"><span class="text-danger">{{$leveltwoitem->subjcode}}</span> - {{$leveltwoitem->subjDesc}}</td>
                                    <td class="text-left align-middle">{{$leveltwoitem->description}} {{$leveltwoitem->ftime}}</td>
                              </tr>
                              @php
                                    $first = false;
                              @endphp
                        @elseif(count($item) > 0 && !$first)
                              <tr>  
                                    <td class="text-left align-middle pl-3"><span class="text-danger">{{$leveltwoitem->subjcode}}</span> - {{$leveltwoitem->subjDesc}}</td>
                                    <td class="text-left align-middle">{{$leveltwoitem->description}} {{$leveltwoitem->ftime}}</td>
                              </tr>
                        @endif
                  @endforeach
            @endforeach
      </tbody>

</table>