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

                <table class="table table-bordered" id="consolidated_list">
                    <thead>
                        <tr>
                            <th colspan="7"><strong>OFFICIAL RECEIPT NO.: {{$rangeOR}}</strong></th>
                        </tr>
                        <tr>
                            <th style="width: 10%;"></th>
                            <th style="width: 30%;"></th>
                            <th class="text-right">College</th>
                            <th class="text-right">SHS</th>
                            <th class="text-right">HS</th>
                            <th class="text-right">GS</th>
                            {{-- <th class="text-right">GENERAL</th> --}}
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        {!! $cashtransaction !!}
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2" class="text-right">TOTAL RECEIPT:</th>
                            <th class="text-right text-bold">
                                {{number_format($gentotalcol, 2)}}
                            </th>
                            <th class="text-right text-bold">
                                {{number_format($gentotalshs, 2)}}
                            </th>
                            <th class="text-right text-bold">
                                {{number_format($gentotalhs, 2)}}
                            </th>
                            <th class="text-right text-bold">
                                {{number_format($gentotalgs, 2)}}
                            </th>
                            {{-- <th class="text-right text-bold">
                                {{number_format($gentotalgen, 2)}}
                            </th> --}}
                            <th class="text-right text-bold">
                                {{number_format($gentotal, 2)}}
                            </th>
                        </tr>
                        <tr>
                            <th colspan="2" class="text-right">GRAND TOTAL:</th>
                            <th colspan="3" style="border-bottom: 1px solid black; font-weight: bold; font-size: 12px;">{{number_format($gentotal, 2)}}</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
