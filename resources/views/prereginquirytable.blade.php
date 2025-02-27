<input type="hidden" value="{{$count}}" id="searchCount">
 
 <table class="table">
      <thead>
            <tr>
                  <th>Student Name</th>
                  <th>Grade Level</th>
            </tr>
      </thead>
      <tbody>
            @foreach ($prereg as $item)
                  <tr>
                        <td>{{strtoupper($item->last_name)}}, {{strtoupper($item->first_name)}}</td>
                        <td>{{$item->levelname}}</td>
                  </tr>
            @endforeach
            
      </tbody>
</table>