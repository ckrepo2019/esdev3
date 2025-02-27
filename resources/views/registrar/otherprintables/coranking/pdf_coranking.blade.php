<html>
    <header>
        <style>
            @page{
                margin: 0.5in 1in;
            }
            td, th{
                padding: 0px;
            }
        </style>
    </header>
    <body>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="text-align: right; vertical-align: top; width: 15%;"><img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="110px"></td>
                <td style="wudth: 70%; text-align: center; vertical-align: top;">
                    <div style="width: 100%; font-weight: bold; font-size: 20px !important;">{{DB::table('schoolinfo')->first()->schoolname}}</div>
                    <div style="width: 100%; font-size: 14px !important;">{{DB::table('schoolinfo')->first()->address}}</div>
                    <div style="width: 100%; font-size: 16px !important;">Tel. No. (064) 572-4020</div>
                    <div style="width: 100%; font-size: 17px !important;">OFFICE OF THE REGISTRAR</div>
                </td>
                <td style="vertical-align: middle; text-align: left; width: 15%;">
                    {{-- <img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="80px"> --}}
                </td>
            </tr>
            {{-- <tr>
                <td style="font-size: 13px; text-align: center; vertical-align: top;">
                    &nbsp;
                </td>
            </tr> --}}
            {{-- <tr>
                <td colspan="3" >&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3" >&nbsp;</td>
            </tr> --}}
            <tr>
                <td colspan="3" style="border-bottom: 1px solid black;">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3" style="border-bottom: 3px solid black; line-height: 2px;">&nbsp;</td>
            </tr>
        </table>
        <div style="width: 100%; text-align: justify;">
            <div style="width: 100%; text-align: center; font-size: 20px; margin-top: 15px;"><u>C</u>&nbsp;&nbsp; <u>E</u>&nbsp;&nbsp; <u>R</u>&nbsp;&nbsp; <u>T</u>&nbsp;&nbsp; <u>I</u>&nbsp;&nbsp; <u>F</u>&nbsp;&nbsp; <u>I</u>&nbsp;&nbsp; <u>C</u>&nbsp;&nbsp; <u>A</u>&nbsp;&nbsp; <u>T</u>&nbsp;&nbsp; <u>I</u>&nbsp;&nbsp; <u>O</u>&nbsp;&nbsp; <u>N</u></div>
            <br/>
            <br/>
            <p style="margin-bottom: 40px;">TO WHOM IT MAY CONCERN:</p>
            <p style="text-align: justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify that <strong>{{strtoupper($studinfo->lastname)}}, {{ucwords(strtolower($studinfo->firstname))}} @if($studinfo->middlename != null){{ucwords(strtolower($studinfo->middlename[0]))}}.@endif {{ucwords(strtolower($studinfo->suffix))}}</strong>, graduated from this institution with the degree of {{$studinfo->coursename}} as of {{date('F d, Y', strtotime($dateasof))}}.</p>
            <p style="text-align: justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify further that she graduated rank 117 out of 325 graduates with honor point average of 2.84.</p>
            <p style="text-align: justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This further certifies that the  {{$program}} Program of {{ucwords(strtolower(DB::table('schoolinfo')->first()->schoolname))}} is an ACSCU-AAI accredited / FAAP- certified Level II status and is, therefore, exempted from Special Order Requirement (CHED Order No. 31, s. 1995 dated September 25, 1995). </p>
            <p style="text-align: justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This certification is issued for employment purposes.</p>
            <p style="text-align: justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Issued this <u>{{date('jS', strtotime($dateissued))}} day of {{date('F', strtotime($dateissued))}} {{date('Y', strtotime($dateissued))}}</u> at {{ucwords(strtolower(DB::table('schoolinfo')->first()->schoolname))}}, {{ucwords(strtolower(DB::table('schoolinfo')->first()->address))}}.</p>
            <br>
            <br>
            <br>
            <div style="width: 100%; text-align: left; padding-left: 65%;">
                <sub style="width: 35%; font-size: 18px; font-weight: bold; text-align: left; padding: 0px;">{{$schoolregistrar}}</sub>
            </div>
            <br/>
            <div style="width: 100%; text-align: left; padding-left: 65%;">
                <sup style="width: 35%; text-align: left; padding: 0px; font-size: 15px;">Registrar</sup>
            </div>
            <br/>
            <div style="margin: 0px; width: 100%; font-size: 12px !important;">
                SCHOOL SEAL
            </div>
        </div>
    </body>
</html>