
<div class="row mb-2">
    <div class="col-md-12 text-center">
        <label>Who can approve their request?</label>
        <select class="select2bs4" multiple="multiple" style="width: 100%;" id="select-moreapprovals">
            @foreach ($employees as $employee)
                <option value="{{$employee->userid}}">{{ucwords(strtolower($employee->lastname))}}, {{ucwords(strtolower($employee->firstname))}}</option>
            @endforeach
        </select>   
    </div>
</div>

@if(count($approvals) > 0)
    <div class="row mb-2">
        <div class="col-md-12 text-center">
            <table class="table">
                @foreach($approvals as $approval)
                    <tr>
                        <td>{{$approval->lastname}}, {{$approval->firstname}} {{$approval->middlename}}</td>
                        <td><button type="button" class="btn btn-sm btn-default btn-delete-approval" data-id="{{$approval->apprid}}"><i class="fa fa-trash"></i> Remove</button></td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endif