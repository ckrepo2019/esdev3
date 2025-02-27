<table class="table">
      <thead>
            <tr>
                  <th>Student</th>
                  @if($term == 'mid')
                        <th class="text-center">Midtern Grade</th>
                  @elseif($term == 'final')
                        <th class="text-center">Final Grade</th>
                  @endif
            </tr>
      </thead>
      <tbody>
            @foreach ($gradeinfo as $item)
                <tr>
                      <td>{{$item->name}}</td>
                      @if($term == 'mid')
                              <td class="text-center">{{$item->midterm}}</td>
                      @elseif($term == 'final')
                              <td class="text-center">{{$item->finalterm}}</td>
                      @endif
                </tr>
            @endforeach
      </tbody>

</table>