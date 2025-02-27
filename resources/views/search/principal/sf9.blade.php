<input type="hidden" value="{{$count}}" id="searchCount">

@if($count > 0)
    <table class="table table-sm" style="min-width:500px; table-layout:fixed;">
        <thead>
            <tr>
                <th width="20%">ID</th>
                <th width="45%">Student</th>
                <th width="15%" class="appadd">Grade Level</th>
                <th width="20%">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data[0]->data as $item)
                <tr>
                    <td >{{$item->sid}}</td>
                    <td class="appadd">{{$item->lastname}}, {{$item->firstname}}</td>
                    <td>{{$item->enlevelname}}</td>
                    <td><a href="/prinsf9print/{{$item->id}}" class="btn btn-sm btn-info" type="submit"  id="hello" target="_blank">View SF9</a></td>

                    {{-- <td><a class="btn btn-sm btn-info" type="submit"  id="hello" target="_blank">View SF9</a></td> --}}
                </tr>
            @endforeach
           
        </tbody>
    </table>

    {{-- <script>
        $(document).ready(function(){

        })
    </script> --}}
    
@elseif($count == 0 )
    <p class="w-100 text-center p-2 m-0">No Student Enrolled</p>
@else
    <p class="w-100 text-center p-2 m-0">Select Academic Program</p>
@endif