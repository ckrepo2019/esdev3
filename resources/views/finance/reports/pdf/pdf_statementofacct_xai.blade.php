<style>
    * {
        font-size: 11px;
    }
    table{
        /* border-spacing: 0; */
        border:1px solid black;
        table-layout: fixed;
    }
    td{
        border:1px solid black;
    }
    .header{
        border-top: 1px solid black;
        border-bottom: 1px solid black;
    }
</style>
{{-- <table style="font-size: 9px; font-weight: bold; padding-top: 5px;">
    <tr>
        <td>S.Y {{$selectedschoolyear}}
        
            @if($selectedmonth != null)
            <br/>AS OF : {{strtoupper($selectedmonth)}};
            @endif
        </td>
        <td>
            @if($selectedsemester != null)
                SEMESTER : {{strtoupper($selectedsemester)}}
            @endif
        </td>
    </tr>
    <tr>
        <td colspan="2">STUDENT: {{$studinfo->lastname.', '.$studinfo->firstname.' '.$studinfo->middlename.' '.$studinfo->suffix}}</td>
    </tr>
</table>
<br/> --}}

<table style="width: 100%;">
    <thead>
        <tr>
            <th colspan="2" style="text-align: center; font-weight: bold; background-color: #d3f5f8;">
                
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #d3f5f8;">
                <img  src="{{base_path()}}/public/{{$schoolinfo->picurl}}" alt="school" >    
            </th>
            <th colspan="4" style="text-align: center; font-weight: bold; background-color: #d3f5f8; vertical-align: middle;">
                <div style="font-size: 15px;">{{$schoolinfo->schoolname}}</div>
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #d3f5f8;">
                <img  src="{{base_path()}}/public/assets/images/xai/years.jpg" alt="school" >    
            </th>
            <th colspan="2" style="text-align: center; font-weight: bold; background-color: #d3f5f8;"></th>
        </tr>
        <tr>
            <th colspan="3" style="text-align: center; font-weight: bold; background-color: #f5eed6;" class="header">
                
            </th>
            <th colspan="4" style="text-align: center; font-weight: bold; background-color: #f5eed6; vertical-align: middle;" class="header">STATEMENT OF ACCOUNT</th>
            <th colspan="3" style="text-align: center; font-weight: bold; background-color: #f5eed6;" class="header">
                
            </th>
        </tr>
    </thead>
    <tbody style="font-size: 12px;">
        <tr>
            <td colspan="2" width="20%" style="text-align: center;">NAME:</td>
            <td colspan="6" width="60%" style="text-align: center; font-weight: bold; font-size: 15px;">{{$studinfo->lastname.', '.$studinfo->firstname.' '.$studinfo->middlename.' '.$studinfo->suffix}}</td>
            <td colspan="2" width="20%" style="text-align: center; color: #147a85; font-weight: bold;">PRIVATE</td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;">GRADE & SECTION</td>
            <td colspan="6" style="text-align: center; font-weight: bold;">{{$studinfo->levelname}} - {{$studinfo->sectionname}}</td>
            <td colspan="2" style="text-align: center;"></td>
        </tr>
        <tr>
            <td colspan="3" width="35%" style="text-align: center; color: #147a85; font-weight: bold;">WHOLE YEAR PAYABLES</td>
            <td colspan="3" width="25%" style="text-align: center; color: #147a85; font-weight: bold;">BACK ACCOUNT</td>
            <td colspan="3" width="30%" style="text-align: center; color: #147a85; font-weight: bold;">PAYMENT STATUS</td>
            <td width="10%" style="text-align: center; color: #147a85; font-weight: bold;">BALANCE</td>
        </tr>
        <tr>
            <td colspan="2" width="20%" style="text-align: center;">TUITION:</td>
            <td width="15%" style="text-align: right;">
                {{number_format(collect($ledger)->where('groupname','TUI')->sum('amount'),2)}}
            </td>
            <td style="text-align: center; width: 12%;"></td>
            <td colspan="2" width="13%" style="text-align: center;"></td>
            <td colspan="2" width="20%" style="text-align: right;">TUITION:</td>
            <td width="10%" style="text-align: right;">{{number_format(collect($ledger)->where('groupname','TUI')->sum('payment'),2)}}</td>
            <td style="text-align: right;">{{number_format(collect($ledger)->where('groupname','TUI')->sum('amount')-collect($ledger)->where('groupname','TUI')->sum('payment'),2)}}</td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;">MISCELLANEOUS:</td>
            <td style="text-align: right;">
                {{number_format(collect($ledger)->where('groupname','MISC')->sum('amount'),2)}}
            </td>
            <td style="text-align: center;"></td>
            <td colspan="2" style="text-align: center;"></td>
            <td colspan="2" style="text-align: right;">MISCELLANEOUS:</td>
            <td style="text-align: right;">{{number_format(collect($ledger)->where('groupname','MISC')->sum('payment'),2)}}</td>
            <td style="text-align: right;">{{number_format(collect($ledger)->where('groupname','MISC')->sum('amount')-collect($ledger)->where('groupname','MISC')->sum('payment'),2)}}</td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;">TOTAL:</td>
            <td style="text-align: right;">
                {{number_format(collect($ledger)->where('groupname','TUI')->sum('amount')+collect($ledger)->where('groupname','MISC')->sum('amount'),2)}}
            </td>
            <td style="text-align: center;"></td>
            <td colspan="2" style="text-align: center;"></td>
            <td colspan="2" style="text-align: right;">TOTAL:</td>
            <td style="text-align: right;">{{number_format(collect($ledger)->where('groupname','TUI')->sum('payment')+collect($ledger)->where('groupname','MISC')->sum('payment'),2)}}</td>
            <td style="text-align: right;"></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: left; font-weight: bold; color:#cc5200;">BALANCE:</td>
            <td style="text-align: right; font-weight: bold; color:#cc5200;">{{number_format((collect($ledger)->where('groupname','TUI')->sum('amount')-collect($ledger)->where('groupname','TUI')->sum('payment'))+(collect($ledger)->where('groupname','MISC')->sum('amount')-collect($ledger)->where('groupname','MISC')->sum('payment')),2)}}</td>
            <td style="text-align: center;"></td>
            <td colspan="2" style="text-align: center;"></td>
            <td colspan="3" style="text-align: right; font-weight: bold; color:#cc5200;">MONTHLY PAYMENT:({{$monthname}})</td>
            <td style="text-align: right;">
                {{
                    number_format( (collect($ledger)->where('groupname','TUI')->sum('amount') + collect($ledger)->where('groupname','MISC')->sum('amount') - collect($ledger)->where('groupname','!=','TUI')->where('groupname','!=','MISC')->sum('payment')),2)
                }}
            </td>
        </tr>
        <tr>
            <td colspan="10" style="text-align: center; background-color: #ffffb3; font-size: 13px; color:#cc5200; font-weight: bold;">PAYMENT UPDATE</td>
        </tr>
        <tr>
            <td colspan="2" width="20%" style="text-align: center;"></td>
            <td colspan="2" width="27%" style="text-align: center; font-weight: bold;">AMOUNT</td>
            <td rowspan="{{(collect($ledger)->where('groupname','!=','TUI')->where('groupname','!=','MISC')->count())+4}}" style="text-align: center; width: 1%;"></td>
            <td colspan="4" width="42%" style="text-align: center; font-weight: bold; color: #ff6666;">OTHERS</td>
            <td width="10%" style="text-align: center; font-weight: bold;">BALANCE</td>
        </tr>
        <tr style="font-size: 9px;">
            <td style="text-align: center; font-weight: bold;">DATE</td>
            <td style="text-align: center; font-weight: bold;">O.R #</td>
            <td width="15%" style="text-align: center; font-weight: bold;">TUITION</td>
            <td width="12%"style="text-align: center; font-weight: bold;">MISC</td>
            
            <td style="text-align: center; font-weight: bold;">DATE</td>
            <td style="text-align: center; font-weight: bold;">O.R #</td>
            <td style="text-align: center; font-size: 8px; font-weight: bold;">PARTICULARS</td>
            <td style="text-align: center; font-weight: bold;">AMOUNT</td>
            <td style="text-align: center;"></td>
        </tr>
        @if(collect($ledger)->where('groupname','!=','TUI')->where('groupname','!=','MISC')->count() == 0)
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        @else
            @foreach($ledger as $ledge)
                @if(strtolower($ledge->groupname)!='tui' && strtolower($ledge->groupname)!='misc')
                    <tr style="font-size: 9px;">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>

                        <td>{{date('m/d/Y', strtotime($ledge->createddatetime))}}</td>
                        <td>{{$ledge->ornum}}</td>
                        <td>{{$ledge->particulars}}</td>
                        <td style="text-align: right;">{{$ledge->payment}}</td>
                        <td></td>
                    </tr>
                @endif
            @endforeach
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        @endif
        <tr style="font-size: 9px;">
            <td>TOTAL</td>
            <td></td>
            <td>-</td>
            <td>-</td>
            <td>TOTAL</td>
            <td></td>
            <td></td>
            <td style="text-align: right;">{{number_format(collect($ledger)->where('groupname','!=','TUI')->where('groupname','!=','MISC')->sum('payment'),2)}}</td>
            <td></td>
        </tr>
        <tr style="font-size: 8px;">
            <td colspan="5" style="text-align: center; border: none;">
                <br/>
                <span style="font-size: 8px;">Prepared by:</span>
                <br/>
                <br/>
                @php
                    $cashierinfo = DB::table('teacher')
                        ->where('userid', auth()->user()->id)
                        ->first();
                @endphp
                <u style="font-size: 8px; font-weight: bold;">{{$cashierinfo->firstname}} {{$cashierinfo->middlename[0]}}. {{$cashierinfo->lastname}} {{$cashierinfo->suffix}}</u>
                <br/>
                <span style="font-size: 8px;">Cashier</span>
            </td>
            <td colspan="5" style="border: none; text-align: center; line-height: 6px;">
                <p style="font-size: 8px;">For any concern and inquiries please contact +639678773772</p>
                <p style="font-size: 8px;">or you may visit to our finance office. Thank you!</p>
            </td>
        </tr>
    </tbody>
</table>