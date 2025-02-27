<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <style>
            *   {                    
                    font-family: 'Bookman', 'URW Bookman L', serif;
                }
                table{
                    border-collapse: collapse;
                }
                body{
                    margin: 5px 0px;
                }
                td{
                    padding: 0px;
                }
        </style>
    </head>
    <body>
        <table style="width: 100%;">
            <tr>
                <td style="width: 50%; border: 1px solid black; vertical-align: top;">
                    <div style="line-height: 15px;">&nbsp;</div>
                    <table style="width: 100%; table-layout: fixed;">
                        <tr style="text-transform: justify;">
                            <td style="width: 22% !important; text-align: right;">
                                <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="60px">
                            </td>
                            <td style="width: 78% !important;">&nbsp;&nbsp;<span style="font-size: 14px; font-weight: bold;">{{DB::table('schoolinfo')->first()->schoolname}}</span><br/>&nbsp;&nbsp;&nbsp;<span style="font-size: 9px;">{{ucwords(strtolower(DB::table('schoolinfo')->first()->address))}}</span></td>
                        </tr>
                    </table>
                    <table style="width: 100%; table-layout: fixed;">
                        <tr>
                            <td colspan="2">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: center; font-weight: bold; font-size: 14px;">CERTIFICATE OF REGISTRATION</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: center; font-weight: bold; font-size: 14px;">SY {{$sydesc}}</td>
                        </tr>
                        <tr>
                            <td colspan="2">&nbsp;</td>
                        </tr>
                    </table>
                    <table style="width: 100%; font-size: 14px;">
                        <tr>
                            <td style="width: 20%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Name:</td>
                            <td style="width: 68%; border-bottom: 1px solid black;">{{$studinfo->firstname}} {{$studinfo->middlename[0]}}. {{$studinfo->lastname}} {{$studinfo->suffix}}</td>
                            <td style="width: 12%;">&nbsp;</td>
                        </tr>
                    </table>
                    <table style="width: 100%; font-size: 14px;">
                        <tr>
                            <td style="width: 30%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Grade Level:</td>
                            <td style="width: 58%; border-bottom: 1px solid black;">{{$levelname}}</td>
                            <td style="width: 12%;">&nbsp;</td>
                        </tr>
                    </table>
                    <table style="width: 100%; font-size: 14px;">
                        <tr>
                            <td style="width: 40%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date of Enrolment:</td>
                            <td style="width: 48%; border-bottom: 1px solid black;">{{$enrolleddate}}</td>
                            <td style="width: 12%;">&nbsp;</td>
                        </tr>
                    </table>
                    <div style="line-height: 10px;">&nbsp;</div>
                    <div style="line-height: 10px;">&nbsp;</div>
                    <div style="line-height: 5px;">&nbsp;</div>
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 20%;" colspan="3"></td>
                            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'pss')
                            <td style="width: 60%; border-bottom: 1px solid black; text-align: center; font-weight: bold; font-size: 14px;">JOSEPHINE Z. SADICON</td>
                            @else
                            <td style="width: 60%; border-bottom: 1px solid black;"></td>
                            @endif
                            <td style="width: 20%;"></td>
                        </tr>
                        <tr>
                            <td style="width: 2%;"></td>
                            <td style="width: 13%; border: 1px solid black; text-align: center; height: 25px; vertical-align: center;"></td>
                            <td style="width: 5%;"></td>
                            <td style="width: 60%; text-align: center;">Officer in Charge</td>
                            <td style="width: 20%;"></td>
                        </tr>
                    </table>
                    <div style="line-height: 10px;">&nbsp;</div>
                </td>
                <td style="width: 50%;"></td>
            </tr>
        </table>
    </body>
</html>