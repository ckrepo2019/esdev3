<input type="hidden" value="{{$data[0]->count}}" id="searchCount">

<table class="table table-striped" >
      <thead>
            <tr>
                  <th width="20%">Student Name</th>
                  <th width="20%">Student Contact</th>
                  <th width="20%">Mother Info.</td>
                  <th width="20%">Father Info.</td>
                  <th width="20%">Guardian Info.</td>
            </tr>
      </thead>
      <tbody>
            @foreach ($data[0]->data as $item)
                <tr>
                      <td>{{ Str::limit($item->lastname.', '.$item->firstname, 15 , '...')}}<br>{{$item->sid}}</td>
                      <td>{{$item->contactno}}</td>
                      <td class="@if($item->isfathernum == 1) text-success @endif">{{Str::limit( $item->fathername, 15 , '...')}}<br>{{$item->fcontactno}}</td>
                      <td class="@if($item->ismothernum == 1) text-success @endif">{{Str::limit( $item->mothername, 15 , '...')}}<br>{{$item->mcontactno}}</td>
                      <td class="@if($item->isguardannum == 1) text-success @endif">{{Str::limit( $item->guardianname, 15 , '...')}}<br>{{$item->gcontactno}}</td>
                </tr>
            @endforeach
      </tbody>
</table>
