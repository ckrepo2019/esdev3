
            @if(count($scholarships) > 0)
            
            <div class="modal-body" id="programcontainer">
                <div class="form-group clearfix">
                    @foreach($scholarships as $scholarship)
                        @if($scholarship->granted == 1)
                            <div class="row mb-2">
                                <div class="col-md-5">
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox" class="mb-2 selectedscholarhips" id="checkboxPrimary{{$studentid}}-{{$scholarship->id}}" checked="" value="{{$scholarship->id}}">
                                        <label for="checkboxPrimary{{$studentid}}-{{$scholarship->id}}" >
                                            {{$scholarship->abbreviation}}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control scholarshiptype">
                                        <option value="0" @if($scholarship->type == 0) selected @endif>Custom</option>
                                        <option value="1" @if($scholarship->type == 1) selected @endif>Full</option>
                                        <option value="2" @if($scholarship->type == 2) selected @endif>Half</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="number" step="any" class="form-control mb-2" value="{{$scholarship->amount}}" @if($scholarship->type == 1 || $scholarship->type == 2) readonly @endif/>
                                </div>
                            </div>
                        @else
                            <div class="row mb-2">
                                <div class="col-md-5">
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox" class="mb-2 selectedscholarhips" id="checkboxPrimary{{$studentid}}-{{$scholarship->id}}" value="{{$scholarship->id}}">
                                        <label for="checkboxPrimary{{$studentid}}-{{$scholarship->id}}">
                                            {{$scholarship->abbreviation}}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control scholarshiptype" hidden>
                                        <option value="0">Custom</option>
                                        <option value="1">Full</option>
                                        <option value="2">Half</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="number" step="any" class="form-control mb-2" hidden value="0.00"/>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
          
            <div class="modal-footer justify-content-between" id="grant-scholarship-footer" 
            @if(count(collect($scholarships)->where('granted','1')) == 0) hidden @endif>
                <button type="button" class="btn btn-default" data-dismiss="modal" id="submit-scholarshipclose">Close</button>
                <div class="text-right">
                    {{-- <button type="button" class="btn btn-danger" id="submit-deleteprogram">Delete</button> --}}
                    <button type="button" class="btn btn-primary" id="submit-scholarship" data-id="{{$studentid}}">Update</button>
                </div>
            </div>
            @else
            <div class="modal-body" id="programcontainer">
                No Scholarship Programs shown!
            </div>
            @endif