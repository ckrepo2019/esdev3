<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-12 text-right">
                <button type="button" class="btn btn-primary" id="btn-exportpdf"><i class="fa fa-file-pdf"></i> Export to PDF</button>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 10%;">Code</th>
                            <th style="width: 30%;">Particulars</th>
                            @if(count($monthsarray)>0)
                                @foreach($monthsarray as $month)
                                    <th>{{$month->monthstr}}</th>
                                @endforeach
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            @if(count($monthsarray)>0)
                                @foreach($monthsarray as $month)
                                    <td>&nbsp;</td>
                                @endforeach
                            @endif
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
