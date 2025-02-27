<div class="row mb-2">
    <div class="col-md-9">
        <select class="form-control select-header form-control-sm">
            @foreach($accclasses as $accclass)
                <option value="{{$accclass->id}}">{{$accclass->classification}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <button type="button" class="btn btn-success save-button btn-submitaddheader btn-sm"><i class="fa fa-share"></i></button>
        <button type="button" class="btn btn-danger btn-removeadd btn-sm"><i class="fa fa-times"></i></button>
    </div>
</div>