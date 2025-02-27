<input type="hidden" value="{{$students[0]->count}}" id="searchCount">

<table class="table">
      <thead>
            <tr>
                  <th>SID</th>
                  <th>STUDENT</th>
                  <th>GRADE LEVEL</th>
            </tr>
      </thead>
      <tbody>
            @foreach ($students[0]->data as $item)
                  <tr>
                        <td ><a href="#" data-id="{{$item->id}}" class="view_stud_info">{{$item->sid}}</a></td>
                        <td>{{$item->lastname.','.$item->firstname}}</td>
                        <td>{{$item->levelname}}</td>
                  </tr>
            @endforeach
         
      </tbody>

</table>