<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-12 text-right">
                <button type="button" class="btn btn-sm btn-default btn-add-signatory">
                    <i class="fa fa-plus"></i> Add signatory
                </button>
            </div>
        </div>
    </div>
    <div class="card-body pb-1" id="signatorytable">
            @foreach($signatures as $signatory)
            <div class="row mb-2" data-id="{{$signatory->id}}">
                <div class="col-md-3">
                    <input type="text" class="form-control input-title" id="input-title" placeholder="E.g. Certified Correct" value="{{$signatory->title}}" oninput="this.value = this.value.replace(/[^a-zA-Z0-9-_. ]/g, ``);"/>
                </div>
                <div class="col-md-4">
                    <select class="form-control select2 select-name" id="select-name">
                        {{-- <option selected value="{{$signatory->id}}">{{$signatory->firstname}} {{$signatory->middlename}} {{$signatory->lastname}} </option> --}}
                        {{-- <option value="" selected>Select name</option> --}}
                        @foreach(DB::table('teacher')->where('deleted', '0')->where('id', '!=', '{{$signatory->id}}')->get() as $eachteacher)
                            <option value="{{$eachteacher->id}}">{{$eachteacher->lastname}}, {{$eachteacher->firstname}}</option>
                            {{-- <option value="{{$eachteacher->id}}">{{$eachteacher->lastname}}</option> --}}
                        @endforeach
                    </select>
                    {{-- <input type="text" class="form-control input-name" id="input-name" placeholder="Name" value="{{$signatory->name}}" onkeyup="this.value = this.value.toUpperCase();" oninput="this.value = this.value.replace(/[^a-zA-Z0-9-_. ]/g, ``);"/> --}}
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control input-label" id="input-label" placeholder="E.g. School Head" value="{{$signatory->description}}" oninput="this.value = this.value.replace(/[^a-zA-Z0-9-_. ]/g, ``);" />
                </div>
                <div class="col-md-2 p-1 text-right">
                    <button type="button" class="btn btn-sm btn-success btn-save" ><i class="fa fa-save"></i></button>
                    <button type="button" class="btn btn-sm btn-danger btn-delete ml-2" data-id="{{$signatory->id}}"><i class="fa fa-trash"></i></button>
                </div>
            </div>
            @endforeach
        <div class="container-new-signatories">
        </div>
    </div>
</div>
