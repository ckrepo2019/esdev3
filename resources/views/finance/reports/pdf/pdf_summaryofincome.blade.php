<!DOCTYPE html>
<html>
    <head>
        <style>
            *   {                    
                    font-family: Arial, Helvetica, sans-serif;
                }
        </style>
    </head>
    <body>
        <table style="width: 100%; font-size: 10px; text-align: right;">
            <tr>
                <th style="font-weight: bold;">{{DB::table('schoolinfo')->first()->schoolname}}</th>
            </tr>
            <tr>
                <td>{{DB::table('schoolinfo')->first()->address}}</td>
            </tr>
            {{-- <tr>
                <th>Consolidated Report</th>
            </tr>
            <tr>
                <th>@if($datefrom == $dateto){{date('F d, Y', strtotime($datefrom))}}@else{{date('F d, Y', strtotime($datefrom))}} - {{date('F d, Y', strtotime($dateto))}}@endif</th>
            </tr> --}}
        </table>
        <div style="line-height: 7px; font-weight: bold; font-size: 10px;">Summary of Income {{collect($monthsarray)->first()->monthandyearstr}} - {{collect($monthsarray)->last()->monthandyearstr}}</div>
        <div style="width: 100%; line-height: 3px;">&nbsp;</div>
        <table style="width: 100%; font-size: 10px;" border="1">
            <thead>
                <tr style="text-align: center;">
                    <th style="width: 12%; font-weight: bold;">Code</th>
                    <th style="width: 20%; font-weight: bold;">Particulars</th>
                    @if(count($monthsarray)>0)
                        @foreach($monthsarray as $month)
                            <th style="width: {{68/count($monthsarray)}}%">{{$month->monthstr}}</th>
                        @endforeach
                    @endif
                </tr>
            </thead>
            <tr>
                <td style="width: 12%;"></td>
                <td style="width: 20%;"></td>
                @if(count($monthsarray)>0)
                    @foreach($monthsarray as $month)
                        <td style="width: {{68/count($monthsarray)}}%"></td>
                    @endforeach
                @endif
            </tr>
            
        </table>
        <div style="line-height: 5px;"></div>
    </body>
</html>