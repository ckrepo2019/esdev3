

<table class="table">
      <thead>
            <tr>
                  <th width="5%"></th>
                  <th width="95%">DESCRIPTION</th>
            </tr>
      </thead>    
      <tbody>
            @foreach ($usertype as $item)
                  <tr>
                        <td></td>
                        <td>{{$item->utype}}</td>
                        
                  </tr>
            @endforeach
            
      </tbody>
</table>