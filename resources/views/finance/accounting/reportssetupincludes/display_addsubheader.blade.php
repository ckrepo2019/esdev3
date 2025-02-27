<div class="row mb-2">
    <div class="col-md-1">&nbsp;</div>
    <div class="col-md-8">
        <select class="form-control select-subheader form-control-sm">
            @foreach($groups as $group)
                <option value="{{$group->id}}">{{$group->group}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <button type="button" class="btn btn-success save-button btn-submitaddsubheader btn-sm" data-id="{{$headerid}}"><i class="fa fa-share"></i></button>
        <button type="button" class="btn btn-danger btn-removeadd btn-sm"><i class="fa fa-times"></i></button>
    </div>
</div>