<html>
    <header>
        <style>
            @page{
                margin: 0.5in 1in;
                size: 8.5in 11in;
            }
            td, th{
                padding: 0px;
            }
            table {
                border-collapse: collapse;
            }
        </style>
    </header>
    <body>
        <table style="width: 100%; table-layout: fixed;">
            <tr>
                <td style="text-align: right;"><img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="110px"/></td>
                <td style="width: 60%; text-align: center;">
                    <div style="width: 100%; font-size: 20px; font-weight: bold;">{{DB::table('schoolinfo')->first()->schoolname}}</div>
                    <div style="width: 100%; font-size: 15px;">{{DB::table('schoolinfo')->first()->address}}</div>
                    <div style="width: 100%; font-size: 15px;">Tel. No. (064) 572-4020</div>
                    <div style="width: 100%; font-size: 18px;">OFFICE OF THE REGISTRAR</div>
                </td>
                <td>&nbsp;</td>
            </tr>
        </table>
        <br/>
        <div style="border-top: 1px solid black; border-bottom: 2px solid black; line-height: 2px;">&nbsp;</div>
        <br/>
        <div style="text-align: center; font-size: 20px;"><u>C</u>&nbsp;&nbsp;&nbsp;&nbsp;<u>E</u>&nbsp;&nbsp;&nbsp;&nbsp;<u>R</u>&nbsp;&nbsp;&nbsp;&nbsp;<u>T</u>&nbsp;&nbsp;&nbsp;&nbsp;<u>I</u>&nbsp;&nbsp;&nbsp;&nbsp;<u>F</u>&nbsp;&nbsp;&nbsp;&nbsp;<u>I</u>&nbsp;&nbsp;&nbsp;&nbsp;<u>C</u>&nbsp;&nbsp;&nbsp;&nbsp;<u>A</u>&nbsp;&nbsp;&nbsp;&nbsp;<u>T</u>&nbsp;&nbsp;&nbsp;&nbsp;<u>I</u>&nbsp;&nbsp;&nbsp;&nbsp;<u>O</u>&nbsp;&nbsp;&nbsp;&nbsp;<u>N</u></div>
        <br/>
        <br/>
        <br/>
        <div style="font-weight: 20px !important;">
            <p style="margin-bottom: 40px;">TO WHOM IT MAY CONCERN:</p>
            <p style="text-align: justify; line-height: 22px;"><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>This is to certify that <span style="font-weight: bold !important;">{{$studentinfo->lastname}}, {{$studentinfo->firstname}} @if($studentinfo->middlename != null){{$studentinfo->middlename[0]}}.@endif {{$studentinfo->suffix}}</span>, graduated from this institution with the degree of {{$studentinfo->strandname}} ({{$studentinfo->strandcode}}) as of {{$studcertinfo->dategraduated ? ($studcertinfo->dategraduated != null ? date('F d, Y', strtotime($studcertinfo->dategraduated)) : ' ') : ' '}} with Special Order No. {{$studcertinfo->specialorderno ?? '________________' }}, Series {{$studcertinfo->yearseries ?? '________' }}, dated {{$studcertinfo->seriesdate ? ($studcertinfo->seriesdate != null ? date('F d, Y', strtotime($studcertinfo->seriesdate)) : ' ') : ' '}}.</p>
            <p style="text-align: justify; line-height: 22px;"><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>This certification is issued for {{$studcertinfo->certipurpose ?? '_______________________________' }}.</p>
            <p style="text-align: justify; line-height: 22px;"><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>Issued this <u>{{date('jS', strtotime($studcertinfo->dateissued))}} day of {{date('F', strtotime($studcertinfo->dateissued))}}, {{date('Y', strtotime($studcertinfo->dateissued))}}</u> as {{ucwords(strtolower(DB::table('schoolinfo')->first()->schoolname))}}, {{ucwords(strtolower(DB::table('schoolinfo')->first()->address))}}.</p>
        </div>
        <br/>
        <br/>
        <br/>
        <br/>
        <table style="width: 100%; font-size: 15px;">
            <tr>
                <td>&nbsp;</td>
                <td style="width: 40%; text-align: center; font-weight: bold; border-bottom: 1px solid black;">{{$signatory->name ?? ' '}}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td style="text-align: center; font-weight: bold;">Registrar</td>
            </tr>
        </table>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <div>School Seal</div>
    </body>
</html>