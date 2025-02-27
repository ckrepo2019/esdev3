<html>
    
    <head>
        <meta http-equiv="Content-Type" content="charset=utf-8" />

        <style>
            
            @page{
                margin: 0.5in;
                size: 8.5in 13in;
            }
            td, th{
                padding: 0px;
            }
            table {
                border-collapse: collapse;
            }
            #scissor{
                font-family:"DeJaVu Sans Mono",monospace;
                content: "\2702";

            }
        </style>
    </head>
    <body style="border: 1px solid black;">
        {{-- <table style="width: 100%;" border="1">
            <tr>
                <td style="height: 33%; vertical-align: top;">                    
                </td>
            </tr>
            <tr>
                <td style="height: 33%;"></td>
            </tr>
            <tr>
                <td style="height: 33%;"></td>
            </tr>
        </table> --}}
        <div style="width: 100%; padding: 0px 5px; font-size: 16px; border-bottom: 1px solid black; padding-top: 5px;">
            <div style="width: 100%; font-weight: bold; text-align: center;">{{DB::table('schoolinfo')->first()->schoolname}}</div>
            <div style="width: 100%; font-weight: bold; text-align: center;">{{ucwords(strtolower(DB::table('schoolinfo')->first()->address))}}</div>
            <div style="width: 100%; font-weight: bold; text-align: center;">OFFICE OF THE REGISTRAR</div>
            <br/>
            <div style="width: 100%; font-weight: bold; text-align: center;">CERTIFICATE OF ELIGIBILITY TO TRANSFER</div>
            <br/>
            <table style="width: 100%;">
                <tr>
                    <td style="width: 60%;">No. {{$studtransferelig->transferno}}</td>
                    <td style="width: 5%%;">Date</td>
                    <td style="border-bottom: 1px solid black; text-align: center;">{{date('F d, Y',strtotime($studtransferelig->transferdate))}}</td>
                </tr>
            </table>
            <br/>
            <p style="text-align: justify;">
                <u style="font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$studinfo->lastname}}, {{$studinfo->firstname}} {{$studinfo->middlename[0]}}.&nbsp;&nbsp;</u> &nbsp;&nbsp;is hereby granted Transfer Credential/honorable Dismissal from the <u>{{$studinfo->collegename}} ({{$studinfo->coursename}})</u> effective today.
            </p>
            <br/>
            <table style="width: 100%;">
                <tr>
                    <td></td>
                    <td style="width: 40%; text-align: center; font-size: 16px;">@if(count($signatory)>0){{strtoupper($signatory[0]->name)}}@endif</td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align: center;">Registrar</td>
                </tr>
                <tr>
                    <td>(Registrar's Copy)</td>
                    <td></td>
                </tr>
            </table>
            <br/>
        </div>
        <div style="width: 100%; padding: 0px 5px; font-size: 16px; border-bottom: 1px dashed black; margin-top: 5px;">
            <div style="width: 100%; font-weight: bold; text-align: center;">{{DB::table('schoolinfo')->first()->schoolname}}</div>
            <div style="width: 100%; font-weight: bold; text-align: center;">{{ucwords(strtolower(DB::table('schoolinfo')->first()->address))}}</div>
            <div style="width: 100%; font-weight: bold; text-align: center;">OFFICE OF THE REGISTRAR</div>
            <br/>
            <div style="width: 100%; font-weight: bold; text-align: center;">CERTIFICATE OF ELIGIBILITY TO TRANSFER</div>
            <br/>
            <table style="width: 100%;">
                <tr>
                    <td style="width: 60%;">No. {{$studtransferelig->transferno}}</td>
                    <td style="width: 5%%;">Date</td>
                    <td style="border-bottom: 1px solid black; text-align: center;">{{date('F d, Y',strtotime($studtransferelig->transferdate))}}</td>
                </tr>
            </table>
            <br/>
            <p style="text-align: justify;">
                <u style="font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$studinfo->lastname}}, {{$studinfo->firstname}} {{$studinfo->middlename[0]}}.&nbsp;&nbsp;</u> &nbsp;&nbsp;is hereby granted Transfer Credential/honorable Dismissal from the <u>{{$studinfo->collegename}} ({{$studinfo->coursename}})</u> effective today.
            </p>
            <br/>
            <table style="width: 100%;">
                <tr>
                    <td></td>
                    <td style="width: 40%; text-align: center; font-size: 16px;">@if(count($signatory)>0){{strtoupper($signatory[0]->name)}}@endif</td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align: center;">Registrar</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                </tr>
            </table>
            <span id="scissor" style="font-size: 35px; padding: 0px; margin: 0px;">&#9986;</span>
        </div>
        <div style="width: 100%; padding: 0px 5px; font-size: 16px; margin-top: 5px;  font-family: Arial, Helvetica, sans-serif;">
            <div style="width: 100%; text-align: center;">REQUEST CARD</div>
            <table style="width: 100%; line-height: 25px;">
                <tr>
                    <td style="width: 50%;">No. {{$studtransferelig->transferno}}</td>
                    <td style="width: 17%;">Name of School</td>
                    <td style="border-bottom: 1px solid black;">:</td>
                </tr>
                <tr>
                    <td></td>
                    <td>Address</td>
                    <td style="border-bottom: 1px solid black;">:</td>
                </tr>
                <tr>
                    <td>The Registrar</td>
                    <td>Date</td>
                    <td style="border-bottom: 1px solid black;">:</td>
                </tr>
                <tr>
                    <td>{{ucwords(strtolower(DB::table('schoolinfo')->first()->schoolname))}}</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>{{ucwords(strtolower(DB::table('schoolinfo')->first()->address))}}</td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
            <br/>
            <div style="width: 100%; margin-top: 5px;">Sir/Madam:</div>
            <p style="text-align: justify;  text-justify: inter-word;
            ">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I have the honor to request you to send us the &nbsp;&nbsp;Transcript of Record of&nbsp;&nbsp;<u style="font-weight: bold;">{{$studinfo->lastname}}, {{$studinfo->firstname}} {{$studinfo->middlename[0]}}.</u> who has been temporarily enrolled in this school for the <span style="border-bottom: 1px solid black; width: 10px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> semester/summer, 
            {{-- </p> <p style="text-align: justify;  text-justify: inter-word;
            "> --}}
            <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u> upon presentation of his/her Certificate of Eligibility to Transfer.
            </p>
            <table style="width: 100%; line-height: 25px;">
                <tr>
                    <td style="width: 48%;"></td>
                    <td>Very respectfully, </td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td style="width: 32%; border-bottom: 1px solid black;"></td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td style="text-align: center;">Registrar</td>
                </tr>
            </table>
            <br/>
            <br/>
            <p style="margin: 0px; padding: 0px;">Note: No request for Transcript of Record may be honored w/out the school's dry seal</p>
        </div>
    </body>
</html>