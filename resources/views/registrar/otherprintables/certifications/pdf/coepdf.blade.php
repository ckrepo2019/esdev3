<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <title>{{$student->firstname.' '.$student->middlename[0].' '.$student->lastname}}</title> --}}
    <style>

        .table {
            background-color: transparent;
            vertical-align: middle;
            table-layout: fixed;
        }

        table {
            border-collapse: collapse;
        }
        
        .table thead th {
            vertical-align: bottom;
        }
        
        .table td, .table th {
            /* padding-top: 5px; */
            padding-bottom: 2px;
            /* vertical-align: top; */
        }
        
        .table-bordered {
            border: 1px solid #00000;
        }

        .table-bordered td, .table-bordered th {
            border: 1px solid #00000;
        }

        .table-sm td, .table-sm th {
            padding: 5px!important;
        }

        .text-center{
            text-align: center !important;
        }
        
        .text-right{
            text-align: right !important;
        }
        
        .text-left{
            text-align: left !important;
        }
        
        .p-0{
            padding-top: 0!important;
            padding-bottom: 0!important;
            padding-right: 0!important;
            padding-left: 0!important;
        }
        .p-0 td{
            padding-top: 0!important;
            padding-bottom: 0!important;
        }
        .p-5{
            padding-top: 13px!important;
            padding-bottom: 13px!important;
            padding-right: 2px!important;
            padding-left: 2px!important;
        }
    .pb-0{
            padding-bottom: 0!important;
    }
    .pb-5{
            padding-bottom: 25px!important;
    }
        .pl-3{
            padding-left: 1rem !important;
        }

        .mb-0{
            margin-bottom: 0;
        }
        .mt-0{
            margin-top: 0!important;
        }
        .mt-3{
            margin-top: 1rem!important;
        }
        .mb-1, .my-1 {
            margin-bottom: .25rem!important;
        }

        body{
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
            font-size: 11pt;
            color:#2a2a2a
        }
        p{
            margin: 0;
        }
        .align-top{
            vertical-align: top!important;
        }
        .align-middle{
            vertical-align: middle !important;    
        }
        .align-bottom{
            vertical-align: bottom!important;
        }
        
        .grades td{
            padding-top: 5px;
            padding-bottom: 5px;
            font-size: 9pt;
        }

        .studentinfo td{
            padding-top: .1rem;
            padding-bottom: .1rem;
        
        }
        .bold{
            font-weight: bold!important;
        }
        .bg-red{
            color: red;
            border: solid 1px black !important;
        }

        td{
            padding-left: 5px;
            padding-right: 5px;
        }
        .aside {
            /* background: #48b4e0; */
            color: #000;
            line-height: 15px;
            height: 35px;
            border: 1px solid #000!important;
            
        }
        .aside span {
            /* Abs positioning makes it not take up vert space */
            /* position: absolute; */
            top: 0;
            left: 0;

            /* Border is the new background */
            background: none;

            /* Rotate from top left corner (not default) */
            transform-origin: 10 10;
            transform: rotate(-90deg);
        }
        .trhead {
            background-color: rgb(167, 223, 167); 
            color: #000; font-size;
        }
        .trhead td {
            border: 1px solid #000;
        }
        /* @page {  
            margin:20px 20px;
            
        } */
        body { 
            /* margin:0px 10px; */
        }
        .small-font{
            font-size: 10pt!important;
        }
        .smaller-font{
            font-size: 8pt!important;
        }
        .smallest-font{
            font-size: 7pt!important;
        }
        h4{
            margin-bottom: 10px;
        }
        .suptitle{
            font-size: 20pt;
        }
        .round-bordered{
            border: 2px solid rgb(71, 237, 246);
            border-radius: 10px!important;
        }
        .rounded-bordered{
            border: 2px solid black;
            border-radius: 30px!important;
        }
        .title{
            font-size: 17pt!important;
        }
        .subtitle{
            font-size: 12pt!important;
        }
        .small-font2{
            font-size: 8pt!important;
        }
        .subspace{
            margin-top: 7px!important
        }
        .space{
            margin-top: 13px!important
        }
        .spacing{
            margin-top: 25px!important;
        }
        .superspace{
            margin-top: .8in!important;
        }
        .underline{
            border-bottom: 1px solid black;
        }
        .timesnew{
            font-family: 'Times New Roman', Times, serif;
        }
        #space{
            margin-top: .2in!important
        }
        .right-margin{
            margin-right: .5in!important;
        }
        .left-margin{
            margin-left: .15in!important;
        }
        .smallcompressed{
            margin-right: .2in!important;
            margin-left: .2in!important;
        }
        .compressed{
            margin-left: .15in!important;
            margin-right: .15in!important;
        }
        .supcompressed{
            margin-right: 1.3in!important;
            margin-left: 1.3in!important;
        }
        .border-top-bottom{
            border-top: 1px solid black;
            border-bottom: 1px solid black;
        }
        .border{
            border: 1px solid black;
            /* text-align: center!important; */
        }
        .border2{
            border: 2px solid black;
            /* text-align: center!important; */
        }
        .border-bot{
            border-bottom: 1px solid black;
        }
        .border-top{
            border-top: 3px solid black;
        }
        .indent{
            text-indent: .5in;
        }
        .small-indent{
            text-indent: .3in
        }
        .new-page{
            page-break-after: always;
        }
        .padding{
            padding-bottom: 20px !important;
            padding-top: 20px !important;
        }
        .m-tb{
            margin-top: 25px!important;
            margin-bottom: 25px!important;
        }
        .no-border{
            border-top: 1px solid white!important;
            border-bottom: 1px solid white!important;
        }
        .no-right{
            border-right: 1px solid white!important;
        }
        .no-left{
            border-left: 1px solid white !important;
        }
        .no-top{
            border-top: 1px solid white!important;
        }
        .no-bottom{
            border-bottom: 1px solid white!important;
        }
        .relative{
            position: relative;
        }
        .absolute{
            position: absolute;
            top: 11.5in;
        }
        .word-space{
            word-spacing: 8px;
        }
        .mr-0{
            margin-right: 0!important;
        }
        .ml-0{
            margin-left: 0!important;
        }
        .yellow-back{
            background-color: yellow;
        }
        .light-green{
            background-color: #92D050;
        }
        .white{
            color: rgb(255, 255, 255);
        }
        .blue-border{
            border-bottom: 2px solid rgb(170, 215, 250);
            border-top: 2px solid rgb(150, 204, 246);
        }
        .uppercase{
            text-transform: uppercase!important;
        }
        .justify{
            text-align: justify!important;
        }
        .gigaspace{
            margin-top: 1.7in;
        }
        .paragraph{
            font-size: 8.5pt;
        }
        .u{
            text-decoration: underline;
        }
        .italic{
            font-style: italic;
        }
        .check_mark {
            font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
            }
        @page { size: 8.5in 13in; margin: .2in .4in;}
    </style>
</head>
<body>
    <table width="100%" class="mt-3">
        <tr>
            <td width="100%" class="" style="margin-right: 30px"><img src="{{base_path()}}/public/assets/images/hccc/hcccheader.png" alt="school" width="650px"></td>
        </tr>
    </table>
    <table width="100%" class="pt-2">
        <tr>
            <td class="border-bot">&nbsp;</td>
        </tr>
    </table>
    <table width="100%" class="spacing">
        <tr>
            <td class="text-center suptitle bold ">CERTIFICATE OF ENROLLMENT</td>
        </tr>
    </table>
    {{-- @dd($semester) --}}
    <table width="100%" class="spacing subtitle">
        <tr>
            <td>
                <p class="indent">This is to certify that {{$studentinfo->lastname}}, {{$studentinfo->firstname}} {{isset($studentinfo->middlename) ? $studentinfo->middlename[0] : ''}}, is a bonafide 2nd Year student in the {{isset($studentinfo->collegename) ? $studentinfo->collegename : ''}} for the school year SY {{$schoolyear}}.</p>
            </td>
        </tr>
        <tr>
            <td><p class="indent space">This is to certify further that the above-mentioned student is enrolled in the following subjects for the {{$semester}}, S.Y {{$schoolyear}} indicated below, to with:</p></td>
        </tr>
    </table>
    <table width="100%" class="small-font bold spacing"> 
        <tr>
            <td width="25%">ID NO.:</td>
            <td width="75%">{{$studentinfo->sid}}</td>
        </tr>
        <tr>
            <td>Name:</td>
            <td>{{$studentinfo->lastname}}, {{$studentinfo->firstname}} {{$studentinfo->middlename[0] ?? ''}}</td>
        </tr>
        <tr>
            <td>Program and Major:</td>
            <td>{{isset($studentinfo->coursename) ? $studentinfo->coursename : ''}}</td>
        </tr>
    </table>
    <table width="100%" class="spacing" style="font-size: 14px;">
        <tr class="text-left bold subtitle">
            <td width="25%">{{$semester}}</td>
            <td width="25%">Code</td>
            <td width="15%">Units</td>
            <td width="35%">Descriptive Title</td>
        </tr>

        @foreach (isset($subjects) ? $subjects : [] as $item)
            <tr>
                <td width="25%">&nbsp;</td>
                <td width="25%">{{$item->subjcode}}</td>
                <td width="25%">{{$item->labunits + $item->lecunits}}</td>
                <td width="25%">{{$item->subjdesc}}</td>
            </tr>
        @endforeach
    </table>
    <table width="100%" class="spacing">
        <tr>
            <td width="50%" class="text-center small-font">Not Valid as Transfer Credential</td>
            <td width="50%" class="text-center small-font ">Warning: Any alteration render this null and void.</td>
        </tr>
    </table>
    <table width="100%" class="text-center space">
        <tr>
            <td width="100%">
                <div class="subtitle">This certification is issued upon request for whatever purposes it may serve him/her best</div>
                <div class="subtitle">Issued on this at {{$schoolinfo->address}}</div>
            </td>
        </tr>
    </table>
    <table width="100%" class="superspace small-font">
        <tr>
            <td width="50%" class="text-center">
                <div>School Seal</div>
                <div>Computer Generalized Report</div>
            </td>
            <td width="50%" class="text-center">
                <div>{{$registrar}}</div>
                <div>Registrar</div>
            </td>
        </tr>
    </table>
</body>
</html>