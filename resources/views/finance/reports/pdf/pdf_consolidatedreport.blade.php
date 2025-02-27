<!DOCTYPE html>
<html>
    <head>
        <style>
            *   {                    
                    font-family: Arial, Helvetica, sans-serif;
                }

            .text-right{
                text-align: right;
            }

            .text-bold{
                font-weight: bold;
            }

            .gentotal{
                height: 30px;
            }

            .td-code{
                width: 35px;
            }

            .td-desc{
                width: 150px;
            }

            
        </style>
    </head>
    <body>
        <table style="width: 100%; font-size: 10px; text-align: center;">
            <tr>
                <th style="font-weight: bold;">{{DB::table('schoolinfo')->first()->schoolname}}</th>
            </tr>
            <tr>
                <td>{{DB::table('schoolinfo')->first()->address}}</td>
            </tr>
            <tr>
                <th>Consolidated Report</th>
            </tr>
            <tr>
                <th>@if($datefrom == $dateto){{date('F d, Y', strtotime($datefrom))}}@else{{date('F d, Y', strtotime($datefrom))}} - {{date('F d, Y', strtotime($dateto))}}@endif</th>
            </tr>
        </table>
        <div style="line-height: 10px;"></div>
        <table style="width: 100%; font-size: 10px; line-height: 20px;">
            
        </table>
        <div style="line-height: 2px;"></div>
        <div style="line-height: 2px; font-weight: bold; font-size: 10px;">OFFICIAL RECEIPT NO.: {{$rangeOR}}</div>
        
        <table style="width: 100%; font-size: 10px;">
            <thead>
                <tr style="text-align: right;">
                    <th colspan="2">&nbsp;</th>
                    <th style="font-weight: bold; text-right: center;">College</th>
                    <th style="font-weight: bold;">SHS</th>
                    <th style="font-weight: bold;">HS</th>
                    <th style="font-weight: bold;">GS</th>
                    <th style="font-weight: bold;">GENERAL</th>
                    <th style="font-weight: bold;">Total</th>
                </tr>
            </thead>
            <tbody>
                {!! $cashtransaction !!}
            


            {{-- foreach here --}}
            {{-- <tr>
                <td colspan="7" style="width: 100%; font-weight: bold;">Example: Tuition Income</td>
            </tr>
            <tr>
                <td style="width: 10%;">Ex: 421</td>
                <td style="width: 30%;">Ex: Tuition Fee</td>
                <td style="width: 12%;"></td>
                <td style="width: 12%;"></td>
                <td style="width: 12%;"></td>
                <td style="width: 12%;"></td>
                <td style="width: 12%;"></td>
            </tr>
            <tr>
                <td colspan="2" style="width: 40%; font-weight: bold; text-align: right;">Ex: TOTAL TUITION INCOME:</td>
                <td style="width: 12%;"></td>
                <td style="width: 12%;"></td>
                <td style="width: 12%;"></td>
                <td style="width: 12%;"></td>
                <td style="width: 12%;"></td>
            </tr>
            <tr>
                <td colspan="7" style="width: 100%; font-weight: bold;">&nbsp;</td>
            </tr> --}}
            {{-- end foreach --}}
                <tr style="text-align: right;">
                    <th colspan="2" class="text-bold">TOTAL RECEIPT:</th>
                    <th style="font-weight: bold;">{{number_format($gentotalcol, 2)}}</th>
                    <th style="font-weight: bold;">{{number_format($gentotalshs, 2)}}</th>
                    <th style="font-weight: bold;">{{number_format($gentotalhs, 2)}}</th>
                    <th style="font-weight: bold;">{{number_format($gentotalgs, 2)}}</th>
                    <th style="font-weight: bold;">{{number_format($gentotalgen, 2)}}</th>
                    <th style="font-weight: bold;">{{number_format($gentotal, 2)}}</th>
                </tr>
                
            </tbody>
            <tfoot>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td class="gentotal" colspan="2" style="width: 40%; font-weight: bold; text-align: right;">GRAND TOTAL:</td>
                    <td class="text-center text-bold" colspan="3" style="border-bottom: 1px solid black; padding-top: 180px;">
                        {{number_format($gentotal, 2)}}
                    </td>
                    <td style="width: 12%;"></td>
                    <td style="width: 12%;"></td>
                </tr>
            </tfoot>
        </table>
		@php
            $sig = db::table('finance_sigs')
                ->where('report_description', 'CONSOLIDATED')
                ->first();
        @endphp
        <div style="line-height: 5px;"></div>
        <div style="line-height: 5px; font-weight: bold; font-size: 10px; float: right;">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$sig->title_1}}:
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

            {{$sig->title_2}}:
        </div>
        
        <div style="line-height: 5px;"></div>
        <table style="width: 100%; table-layout: fixed; font-size: 10px; font-weight: bold;">
            <tr>
                <td style="width: 10%;"></td>
                <td style="width: 30%; border-bottom: 1px solid black; text-align: center">{{$sig->sig_1}}</td>
                <td style="width: 20%;"></td>
                <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">{{$sig->sig_2}}</td>
                <td style="width: 10%;"></td>
            </tr>
            <tr>
                <td style="width: 10%;"></td>
                <td style="width: 30%; text-align: center;">{{$sig->designation_1}}</td>
                <td style="width: 20%;"></td>
                <td style="width: 30%; text-align: center;">{{$sig->designation_2}}</td>
                <td style="width: 10%;"></td>
            </tr>
            {{-- <tr>
                <td style="width: 10%;"></td>
                <td style="width: 30%; text-align: center;"></td>
                <td style="width: 20%; text-align: right;">CHECKED BY:</td>
                <td style="width: 30%; text-align: center;"></td>
                <td style="width: 10%;"></td>
            </tr>
            <tr>
                <td style="width: 10%;"></td>
                <td style="width: 30%;"></td>
                <td style="width: 20%;"></td>
                <td style="width: 30%; border-bottom: 1px solid black;"></td>
                <td style="width: 10%;"></td>
            </tr>
            <tr>
                <td style="width: 10%;"></td>
                <td style="width: 30%; text-align: center;"></td>
                <td style="width: 20%;"></td>
                <td style="width: 30%; text-align: center;">Director of Finance</td>
                <td style="width: 10%;"></td>
            </tr> --}}
        </table>


    </body>
</html>