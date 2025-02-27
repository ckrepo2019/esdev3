

@php
    // $levelname = '';

    // $level = db::table('gradelevel')
    //     ->where('id', $levelid)
    //     ->first();

    // if($level)
    // {
    //     $levelname = $level->levelname;
    // }

    $sinfo = db::table('schoolinfo')->first();

    $schoolname = $sinfo->schoolname;
    $schooladdress = $sinfo->address;
    $picurl = explode('?', $sinfo->picurl);

    $cursy = '';

    $oldclassid = db::table('balforwardsetup')->first()->classid;
    $oldamount = 0;
    $totalcharges = 0;
    $totalbalance = 0;
    
    $sydesc = db::table('sy')
        ->where('id', $syid)
        ->first()
        ->sydesc;

    $semdesc = db::table('semester')
        ->where('id', $semid)
        ->first()
        ->semester;

    $cursy = $sydesc . ' - ' . $semdesc;

@endphp
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        .roundtd{
            border: solid #6CC57EFF;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    @foreach($studarray as $stud)
        <table style="font-size: 14; font-weight: bold; font-family: Sans-serif; padding-top: 5px; width: 100%;">
            <tr>
                <td rowspan="2" style="width: 12%; padding-left: 50px;">
                    <img src="{{$picurl[0]}}" style="width: 100%;">
                </td>
                <td style="text-align: left; width: 100%; padding-top: 20px;">
                    {{$schoolname}}
                </td>
            </tr>
            <tr>
                <td style="text-align: left; width: 100%; font-size: 8; font-weight: normal; padding-bottom: 30px;">
                    {{$schooladdress}}
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;">
                    STATEMENT OF ACCOUNT <br> 
                    <span style="font-size: 9; font-weight: normal;">
                        {{$cursy}}
                    </span>
                </td>
            </tr>
        </table>


        <table style="font-family: Sans-serif; font-size: 11px; font-weight: bold; padding-top: 5px; width: 100%;">
            <tr>
                <td>Student ID: {{$stud->sid}}</td>
                <td style="text-align: right;">
                    Date: {{date_format(date_create(\App\FinanceModel::getServerDateTime()), 'm/d/Y h:i A')}}
                </td>
            </tr>
            <tr>
                <td>Student Name: {{$stud->name}}</td>
            </tr>
            @if($stud->levelid >= 17 && $stud->levelid <= 21)
                <td>Level|Course: {{$stud->level}}
            @else
                <td>Level|Section: {{$stud->level}}
            @endif
                
        </table>
        <br/>
        <table  cellspacing="0" cellpadding="1" style="font-size: 12px; width: 100%; font-family: Sans-serif; border-top: solid; border-bottom: solid;">
            <tr>
                <td style="width: 50%; text-align: center; border-right: solid; vertical-align: top;">
                    <table style="width: 100%;">
                        <thead>
                            <tr>
                                <td colspan="3" class="roundtd" style="width: 100%; text-align: center; font-weight: bold; padding: 5px; background-color: #6CC57EFF;">
                                    OLD ACCOUNTS             
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: left; width: 100%; font-weight: bold;">
                                    DATE
                                </td>
                                <td style="text-align: center; width: 100%; font-weight: bold;">
                                    DESCRIPTION
                                </td>
                                <td style="text-align: center; width: 100%; font-weight: bold;">
                                    AMOUNT
                                </td>
                            </tr>
                        </thead>
                        <tbody style="font-size: 9px;">

                            @foreach($stud->ledgerold as $l)
                                <tr>
                                    <td>{{(date_format(date_create($l->createddatetime), 'm-d-Y'))}}</td>
                                    <td>{{strtoupper($l->particulars)}}</td>
                                    <td style="text-align: right;">{{$l->amount}}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="2" style="text-align: right; font-weight: bold; padding-top: 10px;">TOTAL: </td>
                                <td style="text-align: right; font-weight: bold; border-top: solid; padding-top: 10px;">{{$stud->oldtotal}}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td style="width: 50%; text-align: center;">
                    <table style="width: 100%;">
                        <thead>
                            <tr>
                                <td colspan="3" class="roundtd" style="width: 100%; text-align: center; font-weight: bold; padding: 5px; background-color: #6CC57EFF;">
                                    TOTAL CHARGES
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: left; width: 3em; font-weight: bold;">
                                    DATE
                                </td>
                                <td style="text-align: center; width: 12em; font-weight: bold;">
                                    DESCRIPTION
                                </td>
                                <td style="text-align: center; width: 3em; font-weight: bold;">
                                    AMOUNT
                                </td>
                            </tr>
                        </thead>
                        <tbody style="font-size: 9px;">
                            @foreach($stud->ledgercharges as $l)
                                <tr>
                                    <td>{{(date_format(date_create($l->createddatetime), 'm-d-Y'))}}</td>
                                    <td>{{strtoupper($l->particulars)}}</td>
                                    <td style="text-align: right;">{{$l->amount}}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="2" style="text-align: right; font-weight: bold;padding-top: 10px;">TOTAL: </td>
                                <td style="text-align: right; font-weight: bold; border-top: solid; padding-top: 10px;">{{$stud->chargestotal}}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
        <br>
        <table  cellspacing="0" cellpadding="1" style="font-size: 12px; width: 100%; font-family: Sans-serif;">
            <tr>
                <td style="width: 50%;">
                    
                </td>
                <td style="width: 50%;">
                    <table style="width: 100%;">
                        <tbody style="font-size: 13;">
                            <tr>
                                <td colspan="2" style="text-align: center;">
                                    TOTAL CHARGES: <br> <span style="font-size: 9px;">(Old Account + Total Charges)</span>

                                </td>
                                <td style="text-align: right; font-weight: bold; vertical-align: top;">
                                    {{$stud->totaldebit}}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align: center; font-size: 9px; padding-top: -10px;">
                                
                            </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
        <br/>

        <table  cellspacing="0" cellpadding="1" style="font-size: 12px; width: 100%; font-family: Sans-serif;">
            <tr>
                <td colspan="5" class="roundtd" style="width: 100%; text-align: center; font-weight: bold; padding: 5px; background-color: #6CC57EFF;">
                    PAYMENT
                </td>
            </tr>
        </table>
        <br>
        <table  cellspacing="0" cellpadding="1" style="font-size: 12px; width: 100%; font-family: Sans-serif; border-top: solid; border-bottom: solid;">
            

            <thead>
                <tr>
                    <td style="text-align: left; width: 10%; font-weight: bold;">
                        DATE
                    </td>
                    <td style="text-align: left; width: 10%; font-weight: bold;">
                        OR #
                    </td>
                    <td style="text-align: center; width: 50%; font-weight: bold;">
                        DESCRIPTION
                    </td>
                    <td style="text-align: center; width: 10%; font-weight: bold;">
                        PAYMENT
                    </td>
                </tr>
            </thead>
            <tbody style="font-size: 9px;">
                @foreach($stud->ledgerpayment as $pay)
                    <tr>
                        <td>{{(date_format(date_create($pay->createddatetime), 'm-d-Y'))}}</td>
                        <td>{{strtoupper($pay->ornum)}}</td>
                        <td>{{strtoupper($pay->particulars)}}</td>
                        <td style="text-align: right;">{{$pay->payment}}</td>
                    </tr>
                @endforeach
                
            </tbody>
        </table>
        <br>
        <table  cellspacing="0" cellpadding="1" style="font-size: 12px; width: 100%; font-family: Sans-serif;">
            <tr>
                <td style="width: 50%;">
                    
                </td>
                <td style="width: 50%;">
                    <table style="width: 100%;">
                        <tbody style="font-size: 13;">
                            <tr>
                                <td colspan="2" style="text-align: center;">
                                    TOTAL PAYMENT: <br> <span style="font-size: 9px;"></span>
                                </td>
                                <td style="text-align: right; font-weight: bold; vertical-align: top;">
                                    {{$stud->totalcredit}}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align: center;">
                                    TOTAL DUE: <br> <span style="font-size: 9px;"></span>
                                </td>
                                <td style="text-align: right; font-weight: bold; vertical-align: top;">
                                    {{$stud->totaldue}}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>

        <table  cellspacing="0" cellpadding="1" style="font-size: 12px; width: 50%; font-family: Sans-serif;">
            <tbody style="font-size: 13;">
                <tr>
                    <td colspan="2" style="text-align: center; background-color: #6CC57EFF; padding: 10px; width: 85%;">
                        TOTAL DUE FOR THE MONTH:<br> 
                        <span style="font-size: 12px; font-weight: bold;">
                            ({{$monthinword}})
                        </span>

                    </td>
                    <td colspan="2" style="text-align: right; background-color: #6CC57EFF; padding: 10px; width: 15%; vertical-align: top;">
                        {{-- @php
                            $amountdue = 0;
                            foreach($monthdue as $due)
                            {
                                $amountdue += str_replace(',', '', $due->amount);
                            }
                        @endphp --}}
                        <b>{{$stud->monthdue}}</b>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center; font-size: 9px; padding-top: -10px;">
                        
                    </td>
                </tr>
            </tbody>
        </table>
        <br>
        <table  cellspacing="0" cellpadding="1" style="font-size: 12px; font-family: Sans-serif;" width="100%">
            <thead>
                <tr>
                    <th style="font-weight: bold; text-align: left;">Prepared By:</th>
                    <th style="font-weight: bold; text-align: left;">Received By:</th>
                </tr>
            </thead>
            <tr>
                <td>
                    <table style="width: 80%"  cellpadding="5" >
                        <tr>
                            <td style="border-bottom: 1px solid black;height: 25px;">
                            
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center;text-transform: uppercase; font-weight: bold;">
                                {{-- @if($preparedby)
                                {{$preparedby->firstname.' '.$preparedby->lastname.' '.$preparedby->suffix}}
                                @endif --}}
                            </td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table style="width: 80%"  cellpadding="5" >
                        <tr>
                            <td style="border-bottom: 1px solid black;height: 25px;">
                            
                            </td>
                        </tr>
                        <tr>
                            <td style="text-transform: uppercase; font-weight: bold; border-bottom: 1px solid black;">
                                Date: 
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <div style="page-break-before: always;"></div>
    @endforeach
</body>