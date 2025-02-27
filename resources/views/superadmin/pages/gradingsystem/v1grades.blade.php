<table class="table">
      <thead>
            <tr>
                  <th>Section</th>
                  <th>Subject</th>
                  <th>Quarter</th>
                  <th>Transfer</th>
                  <th>Date Transfered</th>
            </tr>
      </thead>
      <tbody>
            @foreach ($available_grades as $item)
                  <tr>
                        <td>{{$item->sectionname}} - {{$item->levelname}}</td>
                        <td>{{$item->subjdesc}}</td>
                        <td>
                              @if($item->quarter == 1)
                                    1st
                              @elseif($item->quarter == 2)
                                    2nd
                              @elseif($item->quarter == 3)
                                    3rd
                              @elseif($item->quarter == 4)
                                    4th
                              @endif
                        </td>
                        <td>
                              @if($item->transfered == 0)
                                    <button class="btn btn-primary btn-sm transfer" data-id="{{$item->id}}">Transfer</button>
                              @else

                              @endif
                        </td>
                        <td>
                              @if($item->transfered == 1)
                                    {{\Carbon\Carbon::create($item->datetransfered)->isoFormat('MM/DD/YY hh:mm a')}}
                              @endif
                        </td>
                  </tr>
            @endforeach
      </tbody>

</table>