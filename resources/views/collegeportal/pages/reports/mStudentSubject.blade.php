<table>
      <tr>
            <td>No.</td>
            <td>NAME OF STUDENTS</td>
            @foreach ($college_classsched as $item)
                <td>SUBJECT CODE</td>
                <td>UNITS</td>
            @endforeach
      </tr>
      @foreach ($students as $item)
            <tr>
                  <td>{{$item->lastname.', '.$item->firstname}}</td>
                  @foreach ($item->sched as $scheditem)
                        <td>{{$scheditem->subjCode}}</td>
                        <td>{{$scheditem->lecunits + $scheditem->labunits}}</td>
                  @endforeach
            </tr>
      @endforeach
</table>