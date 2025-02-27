<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-12 text-right">
                <button type="button" class="btn btn-sm btn-default" id="btn-exporttopdf"><i class="fa fa-file-pdf"></i> Export to PDF</button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-2">
            <div class="col-md-12">
                <p>TO WHOM IT MAY CONCERN:</p>
                <br/>
                <p>This is to certify that <strong>{{$studinfo->lastname}}, {{$studinfo->firstname}} @if($studinfo->middlename != null){{$studinfo->middlename[0]}}.@endif {{$studinfo->suffix}}</strong>, graduated from this institution with the degree of {{$studinfo->strandname}} ({{$studinfo->strandcode}}) as of <input type="date" id="input-graduatedasof" value="{{$studcertinfo->dategraduated ?? null}}" style="border: none; border-bottom: 1px solid black;"/> with Special Order No. <input type="text" id="input-sono" value="{{$studcertinfo->specialorderno ?? '50-464101-05257'}}" style="border: none; border-bottom: 1px solid black;"/>, Series <input type="number" id="input-series" value="{{$studcertinfo->yearseries ?? date('Y')}}" style="border: none; border-bottom: 1px solid black;"/>, dated <input type="date" id="input-dated" value="{{$studcertinfo->seriesdate ?? date('Y-m-d')}}" style="border: none; border-bottom: 1px solid black;"/>.</p>
                <p>This certification is issued for <input type="text" id="input-certipurpose" style="border: none; border-bottom: 1px solid black;" placeholder="E.g. Employment purposes" value="{{$studcertinfo->certipurpose ?? ' '}}"/>.</p>

                <p>Issued this <input type="date" id="input-issueddate" style="border: none; border-bottom: 1px solid black;" value="{{$studcertinfo ? date('Y-m-d', strtotime($studcertinfo->dateissued)) : date('Y-m-d')}}"/> at {{DB::table('schoolinfo')->first()->schoolname}}, {{DB::table('schoolinfo')->first()->address}}.</p>
            </div>
        </div>
        <br/>
        <br/>
        <div class="row">
            <div class="col-md-4">
                <label>Registrar</label>
                <input type="text" class="form-control form-control-sm" id="input-registrar" value="{{$signatory->name ?? ''}}"/>
            </div>
        </div>
    </div>
</div>
<script>
    
    $('#btn-exporttopdf').on('click', function(){            
        var studid = $('#select-student').val();
        var graduatedasof = $('#input-graduatedasof').val();
        var sono = $('#input-sono').val();
        var series = $('#input-series').val();
        var dated = $('#input-dated').val();
        var certipurpose = $('#input-certipurpose').val();
        var issueddate = $('#input-issueddate').val();
        var registrar = $('#input-registrar').val();
        
        var validation = 0;
        if(graduatedasof.replace(/^\s+|\s+$/g, "").length == 0)
        {
            validation+=1;
            $('#input-graduatedasof').css('border','1px solid red')
            toastr.warning('Please fill in required field!','Date Graduated')
        }
        if(certipurpose.replace(/^\s+|\s+$/g, "").length == 0)
        {
            validation+=1;
            $('#input-certipurpose').css('border','1px solid red')
            toastr.warning('Please fill in required field!','Purpose')
        }
        if(issueddate.replace(/^\s+|\s+$/g, "").length == 0)
        {
            validation+=1;
            $('#input-issueddate').css('border','1px solid red')
            toastr.warning('Please fill in required field!','Date Issued')
        }
        if(validation == 0)
        {
            window.open("/printable/certification/certofgraduation?action=export&template=0&studid="+studid+"&registrar="+registrar+"&graduatedasof="+graduatedasof+"&sono="+sono+"&series="+series+"&dated="+dated+"&certipurpose="+certipurpose+"&issueddate="+issueddate,'_blank');
        }
    })
</script>