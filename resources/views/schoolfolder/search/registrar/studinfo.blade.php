<input type="hidden" value="{{$data[0]->count}}" id="searchCount">


<table id="example2" class="table table-striped dataTable" role="grid" aria-describedby="example2_info">
    <thead>
        <tr>
        <th>ID No.</th>
        <th>Student Name</th>
        <th>Gender</th>
        <th>Grade Level</th>
        <th>Section</th>
        <th>ESC</th>
        <th></th>
        </tr>
    </thead>
    <tbody id="studlist_body">
        @foreach ($data[0]->data as $item)
            <tr>

                <td>
                    @if($item->studstatus == 1)
                    
                        <span style="width:1px height:100%" class="bg-success">&nbsp;</span>
                    
                    @elseif($item->studstatus == 2)
                    
                        <span style="width:1px height:100%" class="bg-primary">&nbsp;</span>	
                    
                    @elseif($item->studstatus == 3)
                    
                        <span style="width:1px height:100%" class="bg-danger">&nbsp;</span>
                    
                    
                    @elseif($item->studstatus == 4)
                    
                        <span style="width:1px height:100%" class="bg-warning">&nbsp;</span>
                    
                    @elseif($item->studstatus == 5)
                    
                        <span style="width:1px height:100%" class="bg-secondary">&nbsp;</span>	
                    
                    @else
                    
                        <span style="width:1px height:100%" class="bg-light">&nbsp;</span>		
                    @endif
                </td>


                <td id="studname"><a href="studentinfo/edit/{{$item->id}}">{{strtoupper($item->lastname)}} {{strtoupper($item->firstname)}}</a></td>
                <td>{{$item->gender}}</td>
                <td>{{$item->levelname}}</td>
                <td>{{$item->secname}}</td>

                <td>
                    @if($item->esc == 1)
                    
                        <i class="fas fa-check text-primary"></i>;
                    
                    @else
                
                    @endif
                </td>


                <td><button id="cmdenroll" class="btn btn-info btn-block" data-toggle="modal" data-value="{{$item->id}}" data-sid="{{$item->sid}}" data-target="#enrollstud">Enroll</button></td>
                <td><button class="btn btn-danger btn-block">Delete</button></td>

           </tr>
       @endforeach
    </tbody>
</table>