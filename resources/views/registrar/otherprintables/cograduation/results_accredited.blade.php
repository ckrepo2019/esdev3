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
                <p>This is to certify that <strong>{{$studinfo->lastname}}, {{$studinfo->firstname}} @if($studinfo->middlename != null){{$studinfo->middlename[0]}}.@endif {{$studinfo->suffix}}</strong>, is a graduate of this institution with a degree of {{$studinfo->strandname}} ({{$studinfo->strandcode}}) as of <input type="date" id="input-graduatedasof" value="{{$studcertinfo->dategraduated ?? null}}"/> .</p>
                
                <p>This is to certify further that he earned Honor Point/General Weighted Average of <input type="number" id="input-gwagrade" style="width: 60px; border: none; border-bottom: 1px solid black; text-align: center; font-weight: bold;"value="{{$studcertinfo->gwagrade ?? null}}"/> equivalent to <input type="number" id="input-percentgrade" style="width: 60px; border: none; border-bottom: 1px solid black; text-align: center; font-weight: bold;"value="{{$studcertinfo->percentgrade ?? null}}"/> % on the said course.</p>
                
                <p>This further certifies that the Education Program of {{ucwords(strtolower(DB::table('schoolinfo')->first()->schoolname))}} is an ACSCU-AAI accredited / FAAP - certified Level II status and is, therefore, exempted from Special Order Requirement (CHED Order No. 31, s. 1995 dated <input type="date" id="input-dated" value="{{$studcertinfo->seriesdate ?? date('Y-m-d')}}"/>).</p>
                <p>This certification is issued for <input type="text" id="input-certipurpose" placeholder="E.g. Employment purposes" value="{{$studcertinfo->certipurpose ?? null}}" style="width: 400px; border: none; border-bottom: 1px solid black;"/>.</p>

                
                <p>Done this <input type="date" id="input-issueddate" value="{{$studcertinfo ? date('Y-m-d', strtotime($studcertinfo->dateissued)) : date('Y-m-d')}}"/> at {{DB::table('schoolinfo')->first()->schoolname}}, {{DB::table('schoolinfo')->first()->address}}.</p>
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
        var gwagrade = $('#input-gwagrade').val();
        var percentgrade = $('#input-percentgrade').val();
        var certipurpose = $('#input-certipurpose').val();
        var dated = $('#input-dated').val();
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
            window.open("/printable/certification/certofgraduation?action=export&template=1&studid="+studid+"&registrar="+registrar+"&graduatedasof="+graduatedasof+"&gwagrade="+gwagrade+"&percentgrade="+percentgrade+"&certipurpose="+certipurpose+"&dated="+dated+"&issueddate="+issueddate,'_blank');
        }
    })
</script>