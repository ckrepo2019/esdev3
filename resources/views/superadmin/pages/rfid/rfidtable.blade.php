<input type="hidden" value="{{$data[0]->count}}" id="searchCount">

<table class="table">
      <thead>
            <tr>
                  <th>RFID</th>
                  <th>School</th>
            </tr>
      </thead>
      <tbody>
            @foreach ($data[0]->data as $item)
                  <tr>
                        <td>{{$item->rfidcode}}</td>
                        <td>{{$item->schoolabrv}}</td>
                  </tr>
            @endforeach
      </tbody>
</table>