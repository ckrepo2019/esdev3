<input type="hidden" value="{{$students[0]->count}}" id="searchCount">

<table class="table">
      <thead>
            <tr>
                  <th>SID</th>
                  <th>STUDENT</th>
                  <th>GRADE LEVEL</th>
                  <th></th>
            </tr>
      </thead>
      <tbody>
            @foreach ($students[0]->data as $item)
                  <tr>
                        <td>{{$item->sid}}</td>
                        <td>{{$item->lastname.','.$item->firstname}}</td>
                        <td>{{$item->levelname}}</td>
                        <td><a href="/printcor/{{Crypt::encrypt($item->id)}}" class="btn btn-sm btn-primary" target="_blank"><i class="fas fa-print"></i> PRINT COR</a></td>
                  </tr>
            @endforeach
         
      </tbody>

</table>