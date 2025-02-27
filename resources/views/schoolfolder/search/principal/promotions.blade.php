<input type="hidden" value="{{$count}}" id="searchCount">
@if($count > 0)
    <table class="table table-sm" style="min-width:500px; table-layout:fixed;">
        <thead>
            <tr>
                <th width="1%"></th>
                <th width="20%">ID</th>
                <th width="25%">Student</th>
                <th width="5%">S</th>
                <th width="5%">P</th>
                <th width="5%">F</th>
                <th width="15%"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($finalGrades as $item)
                <tr>
                    @if($item->studsatus == 1)
                        <td class="bg-success"></td>
                    @elseif($item->studsatus == 2)
                        <td class="bg-warning"></td>
                    @elseif($item->studsatus == 3)
                        <td class="bg-danger"></td>
                    @else
                        <td></td>
                    @endif
                    <td><a href="/principalPortalStudentProfile/{{Crypt::encrypt($item->id)}}/{{$apid}}">{{$item->studid}}</a></td>
                    <td id="appadd">{{$item->name}}</td>
                    <td>{{$item->totalSubjects}}</td>
                    <td>{{$item->passedSubjects}}</td>
                    <td>{{$item->failedSubjects}}</td>
                   
                    @if($item->promstatus == 1)
                        <td>PROMOTED</td>
                    @elseif($item->promstatus == 2)
                        <td>CONDITIONAL</td>
                    @elseif($item->promstatus == 3)
                        <td>RETAINED</td>
                    @else
                        <td></td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
    
@elseif($count == 0)
    <div class="container h-100">
        <div class="row align-items-center h-100">
            <div class="mx-auto">
                NO STUDENT FOUND
            </div>
        </div>
    </div>
@else
<div class="container h-100">
    <div class="row align-items-center h-100">
        <div class="mx-auto">
            SELECT ACADEMIC PROGRAM
        </div>
    </div>
</div>
@endif