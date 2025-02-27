
<div class="card">
    <div class="card-body">
        <table class="table" id="table-results">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Student Name</th>
                    <th>Grade Level</th>
                    <th>Strand</th>
                    <th>Course</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @if(count($earlybirds)>0)
                    @foreach($earlybirds as $earlybird)
                        <tr id="earlybird{{$earlybird->id}}">
                            <td>
                            
                            </td>
                            <td>
                                {{$earlybird->name_showlast}}<br/>
                                <small>Date Added : {{date('M d, Y', strtotime($earlybird->createddatetime))}}</small>
                            </td>
                            <td>
                                {{$earlybird->levelname}}
                            </td>
                            <td>
                                {{$earlybird->strandcode}}
                            </td>
                            <td>
                                {{$earlybird->coursename}}
                            </td>
                            <td>
                                <button type="button" class="btn btn-default btn-deleteearlybird" data-id="{{$earlybird->id}}"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>