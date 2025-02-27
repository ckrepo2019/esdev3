@if(count($signatories) == 0)

    {{-- @if($formid == 'form1') --}}
    <div class="card">
        <div class="card-header">
            <em class="text-bold">Note: The signatories will be reflected to their respective school forms.</em>
        </div>
        <div class="card-body pb-1">
            @if($formid == 'form6')
            <div class="row mb-2" data-id="0">
                <div class="col-md-3">
                    <input type="text" class="form-control input-title" placeholder="E.g. Prepared and Submitted by" value="Prepared and Submitted by" readonly/>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control input-name" placeholder="Name"/>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control input-label" placeholder="E.g. SCHOOL HEAD" value="SCHOOL HEAD" readonly/>
                </div>
                <div class="col-md-2 p-1 text-right">
                    <button type="button" class="btn btn-sm btn-success btn-save"><i class="fa fa-check"></i></button>
                    <button type="button" class="btn btn-sm btn-danger btn-delete ml-2" data-id="0"><i class="fa fa-trash"></i></button>
                </div>
            </div>
            <div class="row mb-2" data-id="0">
                <div class="col-md-3">
                    <input type="text" class="form-control input-title" placeholder="E.g. Reviewed & Validated by" value="Reviewed & Validated by" readonly/>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control input-name" placeholder="Name"/>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control input-label" placeholder="E.g. DIVISION REPRESENTATIVE" value="DIVISION REPRESENTATIVE" readonly/>
                </div>
                <div class="col-md-2 p-1 text-right">
                    <button type="button" class="btn btn-sm btn-success btn-save"><i class="fa fa-check"></i></button>
                    <button type="button" class="btn btn-sm btn-danger btn-delete ml-2" data-id="0"><i class="fa fa-trash"></i></button>
                </div>
            </div>
            <div class="row mb-2" data-id="0">
                <div class="col-md-3">
                    <input type="text" class="form-control input-title" placeholder="E.g.  Noted by" value="Noted by" readonly/>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control input-name" placeholder="Name"/>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control input-label" placeholder="E.g. SCHOOLS DIVISION SUPERINTENDENT" value="SCHOOLS DIVISION SUPERINTENDENT" readonly/>
                </div>
                <div class="col-md-2 p-1 text-right">
                    <button type="button" class="btn btn-sm btn-outline-success btn-save"><i class="fa fa-check"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-danger btn-delete ml-2" data-id="0"><i class="fa fa-trash"></i></button>
                </div>
            </div>
            @else
            <div class="row mb-2" data-id="0">
                <div class="col-md-3">
                    <input type="text" class="form-control input-title" placeholder="E.g. Certified Correct"/>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control input-name" placeholder="Name"/>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control input-label" placeholder="E.g. School Head"/>
                </div>
                <div class="col-md-2 p-1 text-right">
                    <button type="button" class="btn btn-sm btn-outline-success btn-save"><i class="fa fa-check"></i></button>
                </div>
            </div>
            @endif
            <div class="container-new-signatories">
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-md-12 text-right">
                    <button type="button" class="btn btn-sm btn-default btn-add-signatory">
                        <i class="fa fa-plus"></i> Add signatory
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- @elseif($formid == 'form2')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">                    
                    <label>Attested by</label>
                    <input type="text" class="form-control" placeholder="Name" title="Attested" description="" data-id="0"/>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-md-12 text-right">
                    <button type="button" class="btn btn-sm btn-success" id="btn-savechanges"><i class="fa fa-share"></i> Save Changes</button>
                </div>
            </div>
        </div>
    </div>
    @elseif($formid == 'form4')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">                    
                    <label>Prepared and Submitted by</label>
                    <input type="text" class="form-control" placeholder="Name" title="Prepared and Submitted" description="School Head" data-id="0"/>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-md-12 text-right">
                    <button type="button" class="btn btn-sm btn-success" id="btn-savechanges"><i class="fa fa-share"></i> Save Changes</button>
                </div>
            </div>
        </div>
    </div>
    @elseif($formid == 'form5')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">                    
                    <label>CERTIFIED CORRECT & SUBMITTED by</label>
                    <input type="text" class="form-control" placeholder="Name" title="CERTIFIED CORRECT & SUBMITTED" description="School Head" data-id="0"/>
                    <center><sup>School Head</sup></center>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">                    
                    <label>REVIEWED BY:
                    </label>
                    <input type="text" class="form-control" placeholder="Name" title="REVIEWED" description="Division Representative" data-id="0"/>
                    <center><sup> Division Representative</sup></center>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-md-12 text-right">
                    <button type="button" class="btn btn-sm btn-success" id="btn-savechanges"><i class="fa fa-share"></i> Save Changes</button>
                </div>
            </div>
        </div>
    </div>
    @elseif($formid == 'form5a')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">                    
                    <label>CERTIFIED CORRECT & SUBMITTED BY</label>
                    <input type="text" class="form-control" placeholder="Name" title="CERTIFIED CORRECT & SUBMITTED" description="School Head" data-id="0"/>
                    <center><sup>School Head</sup></center>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">                    
                    <label>REVIEWED BY:
                    </label>
                    <input type="text" class="form-control" placeholder="Name" title="REVIEWED" description="Division Representative" data-id="0"/>
                    <center><sup> Division Representative</sup></center>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-md-12 text-right">
                    <button type="button" class="btn btn-sm btn-success" id="btn-savechanges"><i class="fa fa-share"></i> Save Changes</button>
                </div>
            </div>
        </div>
    </div>
    @elseif($formid == 'form5b')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">                    
                    <label>CERTIFIED CORRECT by</label>
                    <input type="text" class="form-control" placeholder="Name" title="CERTIFIED CORRECT" description="School Head" data-id="0"/>
                    <center><sup>School Head</sup></center>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">                    
                    <label>REVIEWED BY:
                    </label>
                    <input type="text" class="form-control" placeholder="Name" title="REVIEWED" description="Division Representative" data-id="0"/>
                    <center><sup> Division Representative</sup></center>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-md-12 text-right">
                    <button type="button" class="btn btn-sm btn-success" id="btn-savechanges"><i class="fa fa-share"></i> Save Changes</button>
                </div>
            </div>
        </div>
    </div>
    @elseif($formid == 'form6')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">                    
                    <label>Prepared and  Submitted by</label>
                    <input type="text" class="form-control" placeholder="Name" title="Prepared and  Submitted" description="School Head" data-id="0"/>
                    <center><sup>School Head</sup></center>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">                    
                    <label>Reviewed & Validated by</label>
                    <input type="text" class="form-control" placeholder="Name" title="Reviewed & Validated" description="Division Representative" data-id="0"/>
                    <center><sup> Division Representative</sup></center>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">                    
                    <label>Noted by</label>
                    <input type="text" class="form-control" placeholder="Name" title="Noted" description="SCHOOLS DIVISION SUPERINTENDENT" data-id="0"/>
                    <center><sup> SCHOOLS DIVISION SUPERINTENDENT</sup></center>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-md-12 text-right">
                    <button type="button" class="btn btn-sm btn-success" id="btn-savechanges"><i class="fa fa-share"></i> Save Changes</button>
                </div>
            </div>
        </div>
    </div>
    @elseif($formid == 'form10')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">                    
                    <label>School Head</label>
                    <input type="text" class="form-control" placeholder="Name" title="School Head" description="School Head" data-id="0"/>
                    <center><sup>School Head</sup></center>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">                    
                    <label>Records In-Charge</label>
                    <input type="text" class="form-control" placeholder="Name" title="Records In-Charge" description="Records In-Charge" data-id="0"/>
                    <center><sup> Records In-Charge</sup></center>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-md-12 text-right">
                    <button type="button" class="btn btn-sm btn-success" id="btn-savechanges"><i class="fa fa-share"></i> Save Changes</button>
                </div>
            </div>
        </div>
    </div>
    @endif --}}
    
@else

<div class="card">
    <div class="card-header">
        <em>Note: The signatories will be reflected to their respective school forms.</em>
    </div>
    <div class="card-body pb-1">
        @if($formid == 'form6')
            @if(count($signatories)>0)
                @foreach($signatories as $signatory)
                <div class="row mb-2" data-id="{{$signatory->id}}">
                    <div class="col-md-3">
                        <input type="text" class="form-control input-title" placeholder="E.g. Certified Correct" value="{{$signatory->title}}"/>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control input-name" placeholder="Name" value="{{$signatory->name}}"/>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control input-label" placeholder="E.g. School Head" value="{{$signatory->description}}"/>
                    </div>
                    <div class="col-md-2 p-1 text-right">
                        <button type="button" class="btn btn-sm btn-success btn-save"><i class="fa fa-check"></i></button>
                        <button type="button" class="btn btn-sm btn-danger btn-delete ml-2" data-id="{{$signatory->id}}"><i class="fa fa-trash"></i></button>
                    </div>
                </div>
                @endforeach
            @else
            <div class="row mb-2" data-id="0">
                <div class="col-md-3">
                    <input type="text" class="form-control input-title" placeholder="E.g. Prepared and Submitted by" value="Prepared and Submitted by" readonly/>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control input-name" placeholder="Name"/>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control input-label" placeholder="E.g. SCHOOL HEAD" value="SCHOOL HEAD" hidden/>
                </div>
                <div class="col-md-2 p-1 text-right">
                    <button type="button" class="btn btn-sm btn-success btn-save"><i class="fa fa-check"></i></button>
                    <button type="button" class="btn btn-sm btn-danger btn-delete ml-2" data-id="0"><i class="fa fa-trash"></i></button>
                </div>
            </div>
            <div class="row mb-2" data-id="0">
                <div class="col-md-3">
                    <input type="text" class="form-control input-title" placeholder="E.g. Reviewed & Validated by" value="Reviewed & Validated by" readonly/>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control input-name" placeholder="Name"/>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control input-label" placeholder="E.g. DIVISION REPRESENTATIVE" value="DIVISION REPRESENTATIVE" readonly/>
                </div>
                <div class="col-md-2 p-1 text-right">
                    <button type="button" class="btn btn-sm btn-success btn-save"><i class="fa fa-check"></i></button>
                    <button type="button" class="btn btn-sm btn-danger btn-delete ml-2" data-id="0"><i class="fa fa-trash"></i></button>
                </div>
            </div>
            <div class="row mb-2" data-id="0">
                <div class="col-md-3">
                    <input type="text" class="form-control input-title" placeholder="E.g.  Noted by" value="Noted by" readonly/>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control input-name" placeholder="Name"/>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control input-label" placeholder="E.g. SCHOOLS DIVISION SUPERINTENDENT" value="SCHOOLS DIVISION SUPERINTENDENT" readonly/>
                </div>
                <div class="col-md-2 p-1 text-right">
                    <button type="button" class="btn btn-sm btn-outline-success btn-save"><i class="fa fa-check"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-danger btn-delete ml-2" data-id="0"><i class="fa fa-trash"></i></button>
                </div>
            </div>
            @endif
        @else
            @foreach($signatories as $signatory)
            <div class="row mb-2" data-id="{{$signatory->id}}">
                <div class="col-md-3">
                    <input type="text" class="form-control input-title" placeholder="E.g. Certified Correct" value="{{$signatory->title}}"/>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control input-name" placeholder="Name" value="{{$signatory->name}}"/>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control input-label" placeholder="E.g. School Head" value="{{$signatory->description}}"/>
                </div>
                <div class="col-md-2 p-1 text-right">
                    <button type="button" class="btn btn-sm btn-outline-success btn-save"><i class="fa fa-check"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-danger btn-delete ml-2" data-id="{{$signatory->id}}"><i class="fa fa-trash"></i></button>
                </div>
            </div>
            @endforeach
        @endif
        <div class="container-new-signatories">
        </div>
    </div>
    <div class="card-footer">
        <div class="row">
            <div class="col-md-12 text-right">
                <button type="button" class="btn btn-sm btn-default btn-add-signatory">
                    <i class="fa fa-plus"></i> Add signatory
                </button>
            </div>
        </div>
    </div>
</div>
    {{-- <div class="card">
        <div class="card-body">
            @foreach($signatories as $signatory)
            <div class="row">
                <div class="col-md-6">                    
                    <label>{{$signatory->title}}</label>
                    <input type="text" class="form-control" placeholder="Name" title="{{$signatory->title}}" description="{{$signatory->description}}" data-id="{{$signatory->id}}" value="{{$signatory->name}}"/>
                    <center><sup>{{$signatory->description}}</sup></center>
                </div>
            </div>
            @endforeach
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-md-12 text-right">
                    <button type="button" class="btn btn-sm btn-success" id="btn-savechanges"><i class="fa fa-share"></i> Save Changes</button>
                </div>
            </div>
        </div>
    </div> --}}

@endif
<script>

    
			@if(count($signatories)>0)
				toastr.success('{{count($signatories)}}'+' signatorie(s) are found!')
			@else
				toastr.warning('No signatories found!')
			@endif
</script>
