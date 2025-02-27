<div class="row mb-2">
    <input type="hidden" class="form-control form-control-sm" value="{{$studinfo->id}}" id="input-studid"/>
    <div class="col-md-3">
        <label>First Name</label>
        <input type="text" class="form-control form-control-sm" value="{{$studinfo->firstname}}" id="input-firstname"/>
    </div>
    <div class="col-md-3">
        <label>Middle Name</label>
        <input type="text" class="form-control form-control-sm" value="{{$studinfo->middlename}}" id="input-middlename"/>
    </div>
    <div class="col-md-3">
        <label>Last Name</label>
        <input type="text" class="form-control form-control-sm" value="{{$studinfo->lastname}}" id="input-lastname"/>
    </div>
    <div class="col-md-3">
        <label>Suffix</label>
        <input type="text" class="form-control form-control-sm" value="{{$studinfo->suffix}}" id="input-suffix"/>
    </div>
</div>
<div class="row mb-2">
    <div class="col-md-3">
        <label>Gender</label>
        <select class="form-control form-control-sm" id="select-gender">
            <option value="MALE" {{strtolower($studinfo->gender) == 'male' ? 'selected' : ''}}>MALE</option>
            <option value="FEMALE" {{strtolower($studinfo->gender) == 'female' ? 'selected' : ''}}>FEMALE</option>
        </select>
    </div>
    <div class="col-md-3">
        <label>Date of Birth</label>
        <input type="date" class="form-control form-control-sm" value="{{$studinfo->dob}}" id="input-dob"/>
    </div>
    <div class="col-md-3">
        <label>Nationality</label>
        <select class="form-control form-control-sm" id="select-nationality">
            @foreach($nationalities as $nationality)
                @if($studinfo->nationalityid == 0 || $studinfo->nationalityid == null)
                <option value="{{$nationality->id}}" {{$nationality->nationality == 'Filipino' ? 'selected' : ''}}>{{$nationality->nationality}}</option>
                @else
                <option value="{{$nationality->id}}" {{$studinfo->id == $nationality->id ? 'selected' : ''}}>{{$nationality->nationality}}</option>
                @endif
            @endforeach
        </select>
        {{-- <input type="text" class="form-control form-sontrol-sm" value="{{$studinfo->nationalityid}}"/> --}}
    </div>
    <div class="col-md-3">
        <label>Contact No.</label>
        <div class="input-group input-group-sm">
        <div class="input-group-prepend">
        <span class="input-group-text"><i class="fas fa-phone"></i></span>
        </div>
        <input type="text" class="form-control" data-inputmask='"mask": "(+63) 9999999999"' data-mask value="{{$studinfo->contactno}}" id="input-contactno">
        </div>
    </div>
</div>
<hr/>
<div class="row mb-2">
    <div class="col-md-12">
        <label>Address</label>
    </div>
    <div class="col-md-3">
        <label>Street</label>
        <input type="text" class="form-control form-sontrol-sm" value="{{$studinfo->street}}" id="input-street"/>
    </div>
    <div class="col-md-3">
        <label>Barangay</label>
        <input type="text" class="form-control form-sontrol-sm" value="{{$studinfo->barangay}}" id="input-barangay"/>
    </div>
    <div class="col-md-3">
        <label>City</label>
        <input type="text" class="form-control form-sontrol-sm" value="{{$studinfo->city}}" id="input-city"/>
    </div>
    <div class="col-md-3">
        <label>Province</label>
        <input type="text" class="form-control form-sontrol-sm" value="{{$studinfo->province}}" id="input-province"/>
    </div>
</div>
<hr/>
<div class="row mb-2">
    <div class="col-md-5">
        <label>Father's Name</label>
        <input type="text" class="form-control form-sontrol-sm" value="{{$studinfo->fathername}}" id="input-fathername"/>
    </div>
    <div class="col-md-3">
        <label>Occupation</label>
        <input type="text" class="form-control form-sontrol-sm" value="{{$studinfo->foccupation}}" id="input-foccupation"/>
    </div>
    <div class="col-md-4">
        <label>Contact No.</label>
        <div class="input-group">
        <div class="input-group-prepend">
        <span class="input-group-text"><i class="fas fa-phone"></i></span>
        </div>
        <input type="text" class="form-control" data-inputmask='"mask": "(+63) 9999999999"' data-mask value="{{$studinfo->fcontactno}}" id="input-fcontactno">
        </div>
        {{-- <input type="text" class="form-control form-sontrol-sm" value="{{$studinfo->fcontactno}}"/> --}}
    </div>
</div>
<div class="row mb-2">
    <div class="col-md-5">
        <label>Mother's Name</label>
        <input type="text" class="form-control form-sontrol-sm" value="{{$studinfo->mothername}}" id="input-mothername"/>
    </div>
    <div class="col-md-3">
        <label>Occupation</label>
        <input type="text" class="form-control form-sontrol-sm" value="{{$studinfo->moccupation}}" id="input-moccupation"/>
    </div>
    <div class="col-md-4">
        <label>Contact No.</label>
        <div class="input-group">
        <div class="input-group-prepend">
        <span class="input-group-text"><i class="fas fa-phone"></i></span>
        </div>
        <input type="text" class="form-control" data-inputmask='"mask": "(+63) 9999999999"' data-mask value="{{$studinfo->mcontactno}}" id="input-mcontactno">
        </div>
    </div>
</div>
<div class="row mb-2">
    <div class="col-md-5">
        <label>Guardian's Name</label>
        <input type="text" class="form-control form-sontrol-sm" value="{{$studinfo->guardianname}}" id="input-guardianname"/>
    </div>
    <div class="col-md-3">
        <label>Relationship</label>
        <input type="text" class="form-control form-sontrol-sm" value="{{$studinfo->guardianrelation}}" id="input-guardianrelation"/>
    </div>
    <div class="col-md-4">
        <label>Contact No.</label>
        <div class="input-group">
        <div class="input-group-prepend">
        <span class="input-group-text"><i class="fas fa-phone"></i></span>
        </div>
        <input type="text" class="form-control" data-inputmask='"mask": "(+63) 9999999999"' data-mask value="{{$studinfo->gcontactno}}" id="input-gcontactno">
        </div>
    </div>
</div>
<div class="row mb-2">
    <div class="col-md-12">
        <label>Who to contact in case of emergency:</label>
    </div>
    <div class="col-md-4">
        <div class="form-group clearfix">
            <div class="icheck-primary d-inline">
                <input type="radio" id="radioPrimary1" name="emergencyperson" @if($studinfo->ismothernum == 1) checked value="1" @else value="0" @endif>
                <label for="radioPrimary1">
                    Mother {{$studinfo->ismothernum }}
                </label>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group clearfix">
            <div class="icheck-primary d-inline">
                <input type="radio" id="radioPrimary2" name="emergencyperson" @if($studinfo->isfathernum == 1) checked value="1" @else value="0" @endif>
                <label for="radioPrimary2">
                    Father {{$studinfo->isfathernum}}
                </label>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group clearfix">
            <div class="icheck-primary d-inline">
                <input type="radio" id="radioPrimary3" name="emergencyperson" @if($studinfo->isguardiannum == 1) checked value="1" @else value="0" @endif>
                <label for="radioPrimary3">
                    Guardian {{$studinfo->isguardiannum}}
                </label>
            </div>
        </div>
    </div>
</div>
<script>    
    $('[data-mask]').inputmask()
</script>