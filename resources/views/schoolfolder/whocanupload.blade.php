
@if($info)
    <div class="row">
            <div class="col-md-3">
                <div class="icheck-primary">
                    <input type="radio" id="checkbox-limit-who1" name="checkbox-limit-who" value="all" @if($info->type==1) checked @endif>
                    <label for="checkbox-limit-who1">
                        All
                    </label>
                </div>
            </div>
            <div class="col-md-5">
                <div class="icheck-primary">
                    <input type="radio" id="checkbox-limit-who2" name="checkbox-limit-who" value="bydesignation" @if($info->type==2) checked @endif>
                    <label for="checkbox-limit-who2">
                        By Designation
                    </label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="icheck-primary">
                    <input type="radio" id="checkbox-limit-who3" name="checkbox-limit-who" value="custom" @if($info->type==3) checked @endif>
                    <label for="checkbox-limit-who3">
                        Custom
                    </label>
                </div>
            </div>
            <div class="row" id="checkbox-all-selection">
                <div class="col-md-12">
                    <div class="icheck-primary d-inline">
                        <input type="checkbox" id="checkbox-usertype">
                        <label for="checkbox-usertype">Select All</label>
                    </div>
                </div>
            </div>
            @if($info->type == 1)
            <div class="row" id="people-who-can-upload-container" style="width: -webkit-fill-available !important;">
                
            </div>
            @elseif($info->type == 2)
                @if(count($info->detail) > 0)
                    <script>
                        $('#checkbox-all-selection').show()
                    </script>
                    <div class="row" id="people-who-can-upload-container" style="width: -webkit-fill-available !important;height: 600px; overflow-y: scroll;">
                        @foreach($info->detail as $detail)
                            <div class="col-md-12">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="checkbox-usertype-{{$detail->id}}" name="who-can-usertypes[]" value="{{$detail->id}}" @if($detail->checked == 1)  checked @endif>
                                    <label for="checkbox-usertype-{{$detail->id}}">{{$detail->name}}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="row" id="people-who-can-upload-container" style="width: -webkit-fill-available !important;">
                    </div>
                @endif
            @elseif($info->type == 3)
                @if(count($info->detail) > 0)
                    <script>
                        $('#checkbox-all-selection').show()
                    </script>
                    <div class="row mb-2" id="users-control-container">
                        <div class="col-12">
                            @foreach($info->detail as $detail)
                                <button type="button" class="btn btn-sm btn-default mb-1">{{$detail->name}}</button>
                            @endforeach
                        </div>
                    </div>
                    <div class="row" id="people-who-can-upload-container" style="width: -webkit-fill-available !important;">
                    </div>
                    {{-- <div class="row" id="people-who-can-upload-container" style="width: -webkit-fill-available !important;height: 600px; overflow-y: scroll;">
                        @foreach($info->detail as $detail)
                            <div class="col-md-12">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="checkbox-usertype-{{$detail->id}}" name="who-can-usertypes[]" value="{{$detail->id}}" @if($detail->checked == 1)  checked @endif>
                                    <label for="checkbox-usertype-{{$detail->id}}">{{$detail->name}}</label>
                                </div>
                            </div>
                        @endforeach
                    </div> --}}
                @else
                    <div class="row" id="people-who-can-upload-container" style="width: -webkit-fill-available !important;">
                    </div>
                @endif
            @endif
    </div>
@else
<div class="row">
        <div class="col-md-3">
            <div class="icheck-primary">
                <input type="radio" id="checkbox-limit-who1" name="checkbox-limit-who" value="all" checked>
                <label for="checkbox-limit-who1">
                    All
                </label>
            </div>
        </div>
        <div class="col-md-5">
            <div class="icheck-primary">
                <input type="radio" id="checkbox-limit-who2" name="checkbox-limit-who" value="bydesignation">
                <label for="checkbox-limit-who2">
                    By Designation
                </label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="icheck-primary">
                <input type="radio" id="checkbox-limit-who3" name="checkbox-limit-who" value="custom">
                <label for="checkbox-limit-who3">
                    Custom
                </label>
            </div>
        </div>
        <div class="row" id="checkbox-all-selection">
            <div class="col-md-12">
                <div class="icheck-primary d-inline">
                    <input type="checkbox" id="checkbox-usertype">
                    <label for="checkbox-usertype">Select All</label>
                </div>
            </div>
        </div>
        <div class="row" id="people-who-can-upload-container" style="width: -webkit-fill-available !important;">
            
        </div>
</div>
@endif