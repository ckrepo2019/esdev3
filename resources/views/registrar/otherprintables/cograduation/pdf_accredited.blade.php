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
        <br/>
        <div style="text-align: center; font-size: 25px; font-weight: bold;"><em>C &nbsp;&nbsp;E &nbsp;&nbsp;R &nbsp;&nbsp;T &nbsp;&nbsp;I &nbsp;&nbsp;F &nbsp;&nbsp;I &nbsp;&nbsp;C &nbsp;&nbsp;A &nbsp;&nbsp;T &nbsp;&nbsp;I &nbsp;&nbsp;O &nbsp;&nbsp;N</em></div>
        <br/>
        <br/>
        <div style="font-weight: 22px !important;">
            <p style="margin-bottom: 40px;">To Whom It May Concern:</p>
            <p style="text-align: justify; line-height: 22px;"><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>This is to certify that <strong>{{$studentinfo->lastname}}, {{$studentinfo->firstname}} @if($studentinfo->middlename != null){{$studentinfo->middlename[0]}}.@endif {{$studentinfo->suffix}}</strong>, is a graduate of this institution with a degree of {{$studentinfo->strandname}} ({{$studentinfo->strandcode}}) as of {{$studcertinfo->dategraduated ? ($studcertinfo->dategraduated != null ? date('F d, Y', strtotime($studcertinfo->dategraduated)) : ' ') : ' '}}.</p>
            <p style="text-align: justify; line-height: 22px;"><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>This is to certify further that he earned Honor Point/General Weighted Average of <u>{{$studcertinfo->gwagrade ?? '__________' }}</u> equivalent to <u>{{$studcertinfo->percentgrade ?? '__________' }}</u> % on the said course.</p>
            <p style="text-align: justify; line-height: 22px;"><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>This further certifies that the Education Program of {{ucwords(strtolower(DB::table('schoolinfo')->first()->schoolname))}} is an ACSCU-AAI accredited / FAAP - certified Level II status and is, therefore, exempted from Special Order Requirement (CHED Order No. 31, s. 1995 dated {{$studcertinfo->seriesdate ? ($studcertinfo->seriesdate != null ? date('F d, Y', strtotime($studcertinfo->seriesdate)) : ' ') : ' '}}).</p>
            <p style="text-align: justify; line-height: 22px;"><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>This certification is issued for {{$studcertinfo->certipurpose ?? '_______________________________' }}.</p>
            <p style="text-align: justify; line-height: 22px;"><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>Done this <u>{{date('jS', strtotime($studcertinfo->dateissued))}} day of {{date('F', strtotime($studcertinfo->dateissued))}}, {{date('Y', strtotime($studcertinfo->dateissued))}}</u> as {{ucwords(strtolower(DB::table('schoolinfo')->first()->schoolname))}}, {{ucwords(strtolower(DB::table('schoolinfo')->first()->address))}}.</p>
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