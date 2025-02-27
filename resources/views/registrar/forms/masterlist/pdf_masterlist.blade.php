<html>
    <head>
        <title>ESC Certificate</title>
        <style>
            html{                
            font-family: Arial, Helvetica, sans-serif;
            }
        .logo{
            width: 100%;
            table-layout: fixed;
        }
        .header{
            width: 100%;
        }
        .studentsMale th, .studentsMale td, .studentsFemale th, .studentsFemale td{
            border: 1px solid black;
        }
        .logo td ,
        .header td {
            /* border: 1px solid black; */
        }
        .studentsMale{
            font-size: 11px;
            table-layout: fixed;
            font-family: Arial, Helvetica, sans-serif;
            float: left;
            border-spacing: 0;
        }
        .studentsFemale{
            font-size: 11px;
            table-layout: fixed;
            font-family: Arial, Helvetica, sans-serif;
            float: right;
            border-spacing: 0;
        }
        .studentsFemale td, .studentsMale td{
            border-top: hidden;
        }
        .studentsFemale th, .studentsMale th{
            text-align: center;
        }
        .total{
            text-align: left;
            font-size: 11px;
            width: 20%;
            table-layout: fixed;
            font-family: Arial, Helvetica, sans-serif;
            border-spacing: 0;
        }
        .total td{
            border: 1px solid black;
            text-align: center;
        }
        .clear:after {
            clear: both;
            content: "";
            display: table;
            border: 1px solid black;
        }
        @media print {
           button.download {
              display:none;
           }
        }  footer {
                        position: fixed; 
                        bottom: -50px; 
                        left: 0px; 
                        right: 0px;
                        height: 100px; 
        
                        /** Extra personal styles **/
                        color: black;
                        text-align: left;
                        line-height: 20px;
                    }
                    @page{
                        margin: 50px;
                    }
        </style>
    </head>
    <body>
        {{-- @php

        $signatories = DB::table('signatory')
                ->where('form','report_masterlist')
                ->where('syid', $syid)
                ->where('deleted','0')
                ->where('acadprogid',$acadprogid)
                ->get();
        
        if(count($signatories) == 0)
        {
            $signatories = DB::table('signatory')
                ->where('form','report_masterlist')
                ->where('syid', $syid)
                ->where('deleted','0')
                ->where('acadprogid',0)
                ->get();
        
            if(count($signatories)>0)
            {
                if(collect($signatories)->where('levelid', $levelid)->count() == 0)
                {
                    $signatories = collect($signatories)->where('levelid',0)->values();
                }else{
                    $signatories = collect($signatories)->where('levelid', $levelid)->values();
                }
            }
        
            
        }else{
            if(collect($signatories)->where('levelid', $levelid)->count() == 0)
            {
                $signatories = collect($signatories)->where('levelid',0)->values();
            }else{
                $signatories = collect($signatories)->where('levelid', $levelid)->values();
            }
        }
        @endphp --}}
        <div style="width: 100%; font-size: 12px; font-weight: bold; text-align: center;">REPUBLIC OF THE PHILIPPINES</div>
        <div style="width: 100%; font-size: 12px; font-weight: bold; text-align: center;">DEPARTMENT OF EDUCATION</div>
         <br/>
         <div style="width: 100%; font-size: 12px; font-weight: bold; text-align: center;">Education Service Contracting (ESC) Program</div>
         <div style="width: 100%; font-size: 12px; font-weight: bold; text-align: center;">ESC Certificate</div>
         <br/>
         <br/>
         <div style="width: 100%; font-size: 12.5px; text-align: justify;">
            {{$studinfo->lastname}}, {{$studinfo->firstname}} {{$studinfo->middlename}} {{$studinfo->suffix}} is a {{$levelname}} Completer of the Education Service Contracting Program of the Department of Education.
        </div>
        <br/>
        <br/>
        <table style="width: 100%; font-size: 12.5px; table-layout: fixed;">
            <tr>
                <td>ESC School ID:</td>
                <td style="width: 40%; border-bottom: 1px solid black;"></td>
                <td></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>{{ucwords(strtolower($acadprogname))}}:</td>
                <td style="width: 40%; border-bottom: 1px solid black;"></td>
                <td></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>City/Municipality/Province:</td>
                <td style="width: 40%; border-bottom: 1px solid black;"></td>
                <td></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>ESC Student ID:</td>
                <td style="width: 40%; border-bottom: 1px solid black;"></td>
                <td></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>LRN:</td>
                <td style="width: 40%; border-bottom: 1px solid black;"></td>
                <td></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>ESC Student Birth Date:</td>
                <td style="width: 40%; border-bottom: 1px solid black;"></td>
                <td></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3">
                    <div>Notes:</div>
                    <div style="text-align: justify;">The ESC Certificate may be used only when you enroll in a Non-DepEd Senior High School provider or in state/local colleges and universities in school year 2022-2023.</div>
                    <div style="text-align: justify;">ESC Benefeciaries are expected to comply with the admission requirements of Non-DepEd Senior High School provider they are applying to.</div>
                </td>
            </tr>
        </table>
    </body>
</html>