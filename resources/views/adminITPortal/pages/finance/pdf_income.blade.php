<html>
    <header>
        <style>
            * { font-family: Arial, Helvetica, sans-serif; }
            table{
                font-size: 12px;
                border-collapse: collapse;
            }
        </style>
    </header>
    @php
        $schoolinfo = Db::table('schoolinfo')
            ->select(
                'schoolinfo.schoolid',
                'schoolinfo.schoolname',
                'schoolinfo.authorized',
                'refcitymun.citymunDesc as city',
                'schoolinfo.district',
                'schoolinfo.address',
                'schoolinfo.picurl',
                'refregion.regDesc as region'
            )
            ->leftJoin('refregion','schoolinfo.region','=','refregion.regCode')
            ->leftJoin('refcitymun','schoolinfo.division','=','refcitymun.citymunCode')
            ->first();
    @endphp
    <body>
        <table style="width: 100%:">
            <tr>
                <td></td>
                <td style="width: 60%; text-align: center; font-weight: bold;">
                    {{DB::table('schoollist')->where('id',Session::get('schoolid'))->first()->schoolname}}
                </td>
                <td></td>
            </tr>
            {{-- <tr>
                <td></td>
                <td style="width: 60%; text-align: center;">
                    {{$schoolinfo->address}}
                </td>
                <td></td>
            </tr> --}}
            <tr>
                <td></td>
                <td>&nbsp;</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td style="width: 60%; text-align: center; font-weight: bold;">
                    {{$seminfo->semester}} {{$syinfo->sydesc}}
                </td>
                <td style="text-align: right;">{{date('D, F d, Y')}}</td>
            </tr>
            <tr>
                <td></td>
                <th>
                    Income
                </th>
                <td style="text-align: right;">Admin Portal</td>
            </tr>
        </table>
        
        <table style="width: 100%; margin-top: 10px;" border="1">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Month</th>
                    <th>Year</th>
                    <th>Total Receivables</th>
                </tr>
            </thead>
            @foreach($months as $key=>$month)
                <tr>
                    <td style="text-align: center; width: 10%;">{{$key+1}}</td>
                    <td style=" width: 30%;">&nbsp;{{$month->monthname}}</td>
                    <td style=" width: 30%;">&nbsp;{{$month->year}}</td>
                    <td style="text-align: right; padding-right: 10px;">{{$month->totalincome > 0 ? number_format($month->totalincome,2,'.',',') : '0.00'}}</td>
                </tr>
            @endforeach
            <tr>
                <th colspan="3" style="text-align: right; padding-right: 10px;">TOTAL</th>
                <th style="text-align: right; padding-right: 10px;">{{collect($months)->where('totalincome','<','0')->count() > 0 ? number_format(collect($months)->where('totalincome','>','0')->sum('totalincome'),2,'.',',') : number_format(collect($months)->sum('totalincome'),2,'.',',')}}</th>
            </tr>
        </table>   
    </body>
</html>