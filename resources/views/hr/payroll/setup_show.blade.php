{{-- @if(count(collect($particulars)->where('type','1')) == 0) --}}
<div class="col-md-12">
    <em>The selected options will display upon generating the payroll summary</em>
</div>
<div class="col-md-12">
    <label>STANDARD DEDUCTIONS</label>
    <br/>
    @if(count(collect($particulars)->where('type','1'))>0)
        <div class="form-group clearfix">
            @foreach($particulars as $particular)
                @if($particular->type == 1)
                    <div class="icheck-primary">
                        <input type="checkbox" class="particulars" data-id="{{$particular->id}}" data-type="{{$particular->type}}" data-desc="{{$particular->description}}" checked="" disabled>
                        <label >
                            {{$particular->description}}
                        </label>
                    </div>
                @endif
            @endforeach
        </div>
    @else
    <em>No standard deductions</em>
    @endif
</div>
{{-- <div class="col-md-12">
    <label>OTHER DEDUCTIONS</label>
    <br/>
    @if(count(collect($particulars)->where('type','2'))>0)
        <div class="form-group clearfix">
            @foreach($particulars as $particular)
                @if($particular->type == 2)
                    <div class="icheck-primary">
                        <input type="checkbox" class="particulars" data-id="{{$particular->id}}" data-type="{{$particular->type}}" data-desc="{{$particular->description}}" checked=""disabled>
                        <label>
                            {{$particular->description}}
                        </label>
                    </div>
                @endif
            @endforeach
        </div>
    @else
    <em>No other deductions</em>
    @endif
</div> --}}
<div class="col-md-12">
    <label>STANDARD ALLOWANCES</label>
    <br/>
    @if(count(collect($particulars)->where('type','3'))>0)
        <div class="form-group clearfix">
            @foreach($particulars as $particular)
                @if($particular->type == 3)
                    <div class="icheck-primary">
                        <input type="checkbox" class="particulars" data-id="{{$particular->id}}" data-type="{{$particular->type}}" data-desc="{{$particular->description}}" checked=""disabled>
                        <label>
                            {{$particular->description}}
                        </label>
                    </div>
                @endif
            @endforeach
        </div>
    @else
    <em>No standard allowances</em>
    @endif
</div>
{{-- <div class="col-md-12">
    <label>OTHER ALLOWANCES</label>
    <br/>
    @if(count(collect($particulars)->where('type','4'))>0)
        <div class="form-group clearfix">
            @foreach($particulars as $particular)
                @if($particular->type == 4)
                    <div class="icheck-primary">
                        <input type="checkbox" class="particulars" data-id="{{$particular->id}}" data-type="{{$particular->type}}" data-desc="{{$particular->description}}" checked=""disabled>
                        <label >
                            {{$particular->description}}
                        </label>
                    </div>
                @endif
            @endforeach
        </div>
    @else
    <em>No other allowances</em>
    @endif
</div> --}}
{{-- <br/>
<br/>
<br/>
<div class="col-md-12">
    <div class="form-group clearfix">
        @foreach($particulars as $particular)
            @if($particular->type == 5)
                <div class="icheck-primary">
                    <input type="checkbox" class="particulars" data-id="{{$particular->id}}" data-type="{{$particular->type}}" data-desc="{{$particular->description}}" checked=""disabled>
                    <label >
                        {{$particular->description}}
                    </label>
                </div>
            @endif
        @endforeach
    </div>
</div> --}}
<script>
    @if($releasestatus == 1)
        $('#btn-delete-setup').prop('disabled', true)
    @else
        $('#btn-delete-setup').prop('disabled', false)
    @endif
</script>

