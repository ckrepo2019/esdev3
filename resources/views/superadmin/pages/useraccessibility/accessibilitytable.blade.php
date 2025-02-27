<table class="table">
      <thead>
            <tr>
                  <th>MODULE</th>
                  <th>URL</th>
            </tr>
      </thead>
      <tbody>
            @foreach ($useraccesslist as $item)
                  <tr>
                        <td>{{$item->module}}</td>
                        <td>{{$item->url}}</td>
                  </tr>
            @endforeach
           
      </tbody>
</table>