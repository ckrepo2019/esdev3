<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <title>{{$student->firstname.' '.$student->middlename[0].' '.$student->lastname}}</title> --}}
    <style>

        .table {
            width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
            font-size:11px ;
        }

        table {
            border-collapse: collapse;
        }
        
        .table thead th {
            vertical-align: bottom;
        }
        
        .table td, .table th {
            padding: .75rem;
            vertical-align: top;
        }
        .table td, .table th {
            padding: .75rem;
            vertical-align: top;
        }
        
        .table-bordered {
            border: 1px solid #00000;
        }

        .table-bordered td, .table-bordered th {
            border: 1px solid #00000;
        }

        .table-sm td, .table-sm th {
            padding: .3rem;
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
            padding: 0 !important;
        }
       
        .pl-3{
            padding-left: 1rem !important;
        }

        .mb-0{
            margin-bottom: 0;
        }

        .border-bottom{
            border-bottom:1px solid black;
        }

        .mb-1, .my-1 {
            margin-bottom: .25rem!important;
        }

        body{
            font-family: Calibri, sans-serif;
        }
        
        .align-middle{
            vertical-align: middle !important;    
        }

         
        .grades td{
            padding-top: .1rem;
            padding-bottom: .1rem;
            font-size: 10px !important;
        }

        .studentinfo td{
            padding-top: .1rem;
            padding-bottom: .1rem;
          
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
            /* line-height: 15px; */
            /* height: 35px; */
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
            /* transform-origin: 10 10; */
            /* transform: rotate(-90deg); */
        }
        .trhead {
            background-color: rgb(167, 223, 167); 
            color: #000; font-size;
        }
        .trhead td {
            border: 1px solid #000;
        }
        @page {  
            margin:20px 20px;
            
        }
        body { 
            /* margin:0px 10px; */
            
        }
		
		 .check_mark {
               font-family: ZapfDingbats, sans-serif;
            }
        @page { size: 8.5in 14in; margin: 10px 30px;  }
        
    </style>
</head>
<body>  
<table style="width: 100%; table-layout: fixed;">
    <tr>
        <td width="20%" style="text-align: left; vertical-align: top;">
            <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="80px">
        </td>
        <td style="width: 60%; text-align: center;">
            <div style="width: 100%; font-weight: bold;"><b>{{$schoolinfo[0]->schoolname}}</b></div>
            <div style="width: 100%; font-size: 12px;">Founded in 1965 by the Oblates of Mary Immaculate (OMI)</div>
            {{-- <div style="width: 100%; font-size: 12px;">{{$schoolinfo[0]->address}}</div> --}}
            <div style="width: 100%; font-size: 12px;">Owned by the Archdiocese of Cotabato</div>
            <div style="width: 100%; font-size: 12px;">Administered by the Diocesan Clergy of Cotabatp (DCC)</div>
            <div style="width: 100%; font-size: 11px;"><i>- Service for the Love of God through Mary</i></div>
            <div style="width: 100%; font-size: 12px;"><b>(B.E.S.T)</b></div>
            {{-- <div style="width: 100%; font-weight: bold; font-size: 11px; font-style: italic;">SENIOR HIGH SCHOOL (GRADE)</div>
            <div style="width: 100%; font-size: 11px;">Government Permit No. 096, s. 2020</div>
            <div style="width: 100%; font-weight: bold; font-size: 13px; line-height: 5px;">&nbsp;</div>
            <div style="width: 100%; font-weight: bold; font-size: 11px;">OFFICIAL REPORT CARD (SF 9)</div>
            <div style="width: 100%; font-size: 12px; margin-top: 15px;"><b>Report on Learning Progress and Achievements</b></div>
            <div style="width: 100%; font-size: 12px;">AY: {{$schoolyear->sydesc}}</div> --}}
        </td>
        <td width="20%" style="text-align: right; vertical-align: top;">
            {{-- <img src="{{base_path()}}/public/assets/images/faai/logo.PNG" alt="school" width="100px"> --}}
            <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="80px">
        </td>
    </tr>
    {{-- <hr>
    <tr>
        <table style="width: 100%; font-size: 11px; margin-top: 5px;" >
            <tr>
                <td width="50%" class="text-left p-0"><b>{{$student->student}}</b></td>
                <td width="10%" class="text-left p-0"></td>
                <td width="20%" class="text-right p-0">LRN1:</td>
                <td width="20%" class="text-right p-0"><b>{{$student->lrn}}</b></td>
            </tr>
            <tr>
                <td width="50%" class="text-left p-0">{{$student->levelname}} - {{$student->sectionname}}</td>
                <td width="10%" class="text-left p-0"></td>
                <td width="20%" class="text-right p-0"></td>
                <td width="20%" class="text-right p-0"></td>
            </tr>
            <tr>
                <td width="30%" class="text-left p-0">{{$student->gender}} / Age:  {{$student->age}}</td>
                <td width="10%" class="text-left p-0"></td>
                <td width="60%" class="text-right p-0" colspan="2">Curriculum: K12 Basic Education Curriculum</td>
            </tr>
            <tr>
                <td colspan="2" style="width: 20%;">Name : {{$student->student}}</td>
                <td  style="width: 20%;">Gender : {{$student->gender}}</td>
                <td width="60%">Grade & Section : {{$student->levelname}} - {{$student->sectionname}}</td>
            </tr>
            <tr>
                <td width="25%">LRN : {{$student->lrn}}</td>
                <td width="25%">Track :  Academic</td>
                <td width="15%">Strand : {{$strandInfo->strandcode}}</td>
                <td width="35%">Adviser : {{$adviser}}</td>
            </tr>
        </table>
    </tr> --}}
</table>
<table style="width: 100%; table-layout: fixed; border-top: 5px solid green;">
    <tr>
        <td></td>
    </tr>
</table>

<table width="100%" style=" table-layout: fixed;margin-top: 5px; font-size: 11px" >
    <tr>
        <td width="26%" class="p-0">
            <table width="100%" style=" table-layout: fixed; font-size: 11px">
                <tr>
                    <td width="42%" class="text-left p-0">&nbsp;</td>
                    <td width="58%" class="p-0">&nbsp;</td>
                </tr>
            </table>
            <table width="100%" style=" table-layout: fixed; font-size: 11px">
                <tr>
                    <td width="42%" class="text-left p-0">Grade & Section</td>
                    <td width="58%" class="p-0" style="border-bottom: 1px solid #000;">{{$student->levelname}} - {{$student->sectionname}}</td>
                </tr>
            </table>
            <table width="100%" style=" table-layout: fixed; font-size: 11px">
                <tr>
                    <td width="25%" class="text-left p-0">Returning</td>
                    <td width="15%" class="p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="30%" class="text-left p-0">Transferee</td>
                    <td width="20%" class="p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="10%" class="text-left p-0"></td>
                </tr>
            </table>
        </td>
        <td width="4%" class="p-0"></td>
        <td width="40%" class="p-0">
            <table width="100%" style=" table-layout: fixed; font-size: 11px">
                <tr>
                    <td class="text-center p-0" style="font-size: 13px;"><b><u>CERTIFICATE</u> <u>OF</u> <u>REGISTRATION</u></b></td>
                </tr>
                
            </table>
            <table width="100%" style=" table-layout: fixed; font-size: 11px">
                <tr>
                    <td class="text-center p-0" style="font-size: 13px;"><b><i><u>JUNIOR HIGH SCHOOL</u></i></b></td>
                </tr>
            </table>
            <table width="100%" style=" table-layout: fixed; font-size: 11px">
                <tr>
                    <td width="30%" class="p-0"></td>
                    <td width="10%" class="text-center p-0" style="font-size: 13px;"><b>SY</b></td>
                    <td width="30%" class="p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="30%" class="p-0"></td>
                </tr>
            </table>
        </td>
        <td width="4%" class="p-0"></td>
        <td width="26%" class="p-0">
            <table width="100%" style=" table-layout: fixed; font-size: 11px">
                <tr>
                    <td width="80%" class="p-0">Registrar's Copy</td>
                    <td width="10%" class="p-0"></td>
                    <td width="10%" class="p-0"></td>
                </tr>
            </table>
            <table width="100%" style=" table-layout: fixed; font-size: 11px">
                <tr>
                    <td width="20%" class="p-0">ID No.:</td>
                    <td width="70%" class="p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="10%" class="p-0"></td>
                </tr>
            </table>
            <table width="100%" style=" table-layout: fixed; font-size: 11px">
                <tr>
                    <td width="20%" class="p-0">Date :</td>
                    <td width="70%" class="p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="10%" class="p-0"></td>
                </tr>
            </table>
        </td>
    </tr>
</table>


<table width="100%" style="table-layout: fixed;margin-top: 5px; font-size: 11px" >
    <tr>
        <td width="7%" class="text-center p-0">NAME: </td>
        <td width="31%" class="text-center p-0" style="border-bottom: 1px solid #000;"></td>
        <td width="31%" class="text-center p-0" style="border-bottom: 1px solid #000;"></td>
        <td width="31%" class="text-center p-0" style="border-bottom: 1px solid #000;"></td>
    </tr>
    <tr>
        <td width="7%" class="text-center p-0"></td>
        <td width="31%" class="text-center p-0" style=""><i>Last Name</i></td>
        <td width="31%" class="text-center p-0" style=""><i>First Name</i></td>
        <td width="31%" class="text-center p-0" style=""><i>Middle Name</i></td>
    </tr>
</table>

<table width="100%" style="table-layout: fixed;margin-top: 2px; font-size: 11px; border: 1px solid #000;" >
    <tr>
       <td>
            <table width="100%" style="table-layout: fixed; font-size: 11px; border: 1px solid #000;" >
                <tr>
                   <td width="100%" class="text-center" style="color: red;"><i>To be accomplished by Grade VII Student or Transferee only</i></td>
                </tr>
            </table>
            <table width="100%" style="table-layout: fixed;font-size: 11px" >
                <tr>
                    <td width="12%" class="text-left p-0">Birth Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
                    <td width="28%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="13%" class="text-left p-0" style="padding-left: 5px!important;">Birth Place &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
                    <td width="47%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                </tr>
            </table>
            <table width="100%" style="table-layout: fixed;font-size: 11px" >
                <tr>
                    <td width="12%" class="text-left p-0">Gender &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
                    <td width="28%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="13%" class="text-left p-0" style="padding-left: 5px!important;">Age &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
                    <td width="12%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="15%" class="text-left p-0" style="padding-left: 5px!important;">Tribe &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
                    <td width="20%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                </tr>
            </table>
            <table width="100%" style="table-layout: fixed;font-size: 11px" >
                <tr>
                    <td width="23%" class="text-left p-0">Previous School Attended &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
                    <td width="42%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="15%" class="text-left p-0" style="padding-left: 4px!important;">School Year&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
                    <td width="20%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                </tr>
            </table>
            <table width="100%" style="table-layout: fixed;font-size: 11px" >
                <tr>
                    <td width="23%" class="text-left p-0">Address of School &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
                    <td width="77%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
            </table>
            <table width="100%" style="table-layout: fixed;font-size: 11px" >
                <tr>
                    <td width="23%" class="text-left p-0">Parent/Guardian &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
                    <td width="77%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
            </table>
            <table width="100%" style="table-layout: fixed;font-size: 11px" >
                <tr>
                    <td width="23%" class="text-left p-0">Occupation &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
                    <td width="21%" class="text-left p-0" style="border-bottom: 1px solid #000;border-right: 1px solid #000;"></td>
                    <td width="11%" class="text-left p-0" style="border-bottom: 1px solid #000;padding-left: 5px!important;">No. of Siblings:</td>
                    <td width="10%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="15%" class="text-left p-0" style="padding-left: 5px!important;">Monthly Income &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
                    <td width="20%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                </tr>
            </table>
            <table width="100%" style="table-layout: fixed;font-size: 11px" >
                <tr>
                    <td width="23%" class="text-left p-0">Parent/Guardian's Address &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
                    <td width="21%" class="text-left p-0"></td>
                    <td width="11%" class="text-left p-0"></td>
                    <td width="10%" class="text-left p-0"></td>
                    <td width="15%" class="text-left p-0" style="padding-left: 5px!important;">Contact Number &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
                    <td width="20%" class="text-left p-0" style=""></td>
                </tr>
            </table>
            <table width="100%" style="table-layout: fixed;font-size: 11px; padding-bottom: 5px;" >
                <tr>
                    <td width="23%" class="text-left p-0">CREDENTIALS SUBMITTED &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
                    <td width="3%" class="text-left p-0"></td>
                    <td width="4%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="13%" class="text-left p-0" style="padding-left: 5px!important;">Report Card/Ave.</td>
                    <td width="5%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="4%" class="text-left p-0"></td>
                    <td width="4%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="15%" class="text-left p-0" style="padding-left: 5px!important;">Cert. of Good Moral</td>
                    <td width="2%" class="text-left p-0"></td>
                    <td width="4%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="15%" class="text-left p-0" style="padding-left: 5px!important;">Cert. of Live Birth</td>
                    <td width="9%" class="text-left p-0"></td>
                </tr>
            </table>
       </td>
    </tr>
</table>
{{-- 

<br>
<br>
<br>
<table width="100%" style="table-layout: fixed;margin-top: 5px; font-size: 11px" >
    <tr>
        <td width="11%" class="text-left p-0">LRN &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
        <td width="24%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
        <td width="12%" class="text-left p-0">Birth Date &nbsp;&nbsp;:</td>
        <td width="23%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
        <td width="13%" class="text-left p-0">Sex &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
        <td width="17%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed;margin-top: 5px; font-size: 11px" >
    <tr>
        <td width="22%" class="text-left p-0">JHS Computer (please check):</td>
        <td width="12%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
        <td width="15%" class="text-left p-0">Date of Completion:</td>
        <td width="22%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
        <td width="13%" class="text-left p-0">Gen. Ave. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
        <td width="17%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed;margin-top: 5px; font-size: 11px" >
    <tr>
        <td width="12%" class="text-left p-0">Name of School:</td>
        <td width="48%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
        <td width="7%" class="text-left p-0">Address:</td>
        <td width="33%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed;margin-top: 5px; font-size: 11px" >
    <tr>
        <td width="12%" class="text-left p-0">Parent/Guardian:</td>
        <td width="45%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
        <td width="3%" class="text-left p-0"></td>
        <td width="7%" class="text-left p-0">Address:</td>
        <td width="33%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed;margin-top: 5px; font-size: 11px" >
    <tr>
        <td width="12%" class="text-left p-0">Contact Number:</td>
        <td width="48%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
        <td width="40%" class="text-left p-0"></td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed;margin-top: 5px; font-size: 11px" >
    <tr>
        <td width="20%" class="text-left p-0">Credentials Submitted:</td>
        <td width="10%" class="text-left p-0">Report Card</td>
        <td width="5%" class="text-left p-0"><input type="checkbox"></td>
        <td width="15%" class="text-left p-0">Cert. of Good Moral</td>
        <td width="10%" class="text-left p-0"><input type="checkbox"></td>
        <td width="15%" class="text-left p-0">Cert. of Completion</td>
        <td width="5%" class="text-left p-0"><input type="checkbox"></td>
        <td width="15%" class="text-left p-0">Cert. of Live Birth</td>
        <td width="5%" class="text-left p-0"><input type="checkbox"></td>
    </tr>
</table> --}}
<table width="100%" style="table-layout: fixed;margin-top: 15px; font-size: 11px" >
    <tr>
        <td width="32%" class="text-center p-0" style="border-bottom: 1px solid #000;"></td>
        <td width="2%" class="text-center p-0"></td>
        <td width="32%" class="text-center p-0" style="border-bottom: 1px solid #000;"></td>
        <td width="2%" class="text-center p-0"></td>
        <td width="32%" class="text-center p-0" style="border-bottom: 1px solid #000;"><b><i>JINGLE ASUERO-PADAYAO</i></b></td>
    </tr>
    <tr>
        <td width="32%" class="text-center p-0"><i>Student's Signature</i></td>
        <td width="2%" class="text-center p-0"></td>
        <td width="32%" class="text-center p-0"><i>Signature of Parent Over Printed Name</i></td>
        <td width="2%" class="text-center p-0"></td>
        <td width="32%" class="text-center p-0"><i>Registrar</i></td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed;margin-top: 15px; font-size: 11px" >
    <tr>
        <td width="100%" class="text-center p-0">---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td>
    </tr>
</table>
<table style="width: 100%; table-layout: fixed;">
    <tr>
        <td width="20%" style="text-align: left; vertical-align: top;">
            <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="80px">
        </td>
        <td style="width: 60%; text-align: center;">
            <div style="width: 100%; font-weight: bold;"><b>{{$schoolinfo[0]->schoolname}}</b></div>
            <div style="width: 100%; font-size: 12px;">Founded in 1965 by the Oblates of Mary Immaculate (OMI)</div>
            <div style="width: 100%; font-size: 12px;">Owned by the Archdiocese of Cotabato</div>
            <div style="width: 100%; font-size: 12px;">Administered by the Diocesan Clergy of Cotabatp (DCC)</div>
            <div style="width: 100%; font-size: 11px;"><i>- Service for the Love of God through Mary</i></div>
            <div style="width: 100%; font-size: 12px;"><b>(B.E.S.T)</b></div>
        </td>
        <td width="20%" style="text-align: right; vertical-align: top;">
            <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="80px">
        </td>
    </tr>
</table>
<table style="width: 100%; table-layout: fixed; border-top: 5px solid green;">
    <tr>
        <td></td>
    </tr>
</table>
<table width="100%" style=" table-layout: fixed;margin-top: 5px; font-size: 11px" >
    <tr>
        <td width="26%" class="p-0">
            <table width="100%" style=" table-layout: fixed; font-size: 11px">
                <tr>
                    <td width="42%" class="text-left p-0">Grade & Section</td>
                    <td width="58%" class="p-0" style="border-bottom: 1px solid #000;">{{$student->levelname}} - {{$student->sectionname}}</td>
                </tr>
            </table>
            <table width="100%" style=" table-layout: fixed; font-size: 11px">
                <tr>
                    <td width="42%" class="text-left p-0">Track & Strand</td>
                    <td width="58%" class="p-0" style="border-bottom: 1px solid #000;"></td>
                </tr>
            </table>
            <table width="100%" style=" table-layout: fixed; font-size: 11px">
                <tr>
                    <td width="25%" class="text-left p-0">Returning</td>
                    <td width="15%" class="p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="30%" class="text-left p-0">Transferee</td>
                    <td width="20%" class="p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="10%" class="text-left p-0"></td>
                </tr>
            </table>
        </td>
        <td width="4%" class="p-0"></td>
        <td width="40%" class="p-0">
            <table width="100%" style=" table-layout: fixed; font-size: 11px">
                <tr>
                    <td class="text-center p-0" style="font-size: 13px;"><b><u>CERTIFICATE</u> <u>OF</u> <u>REGISTRATION</u></b></td>
                </tr>
                
            </table>
            <table width="100%" style=" table-layout: fixed; font-size: 11px">
                <tr>
                    <td class="text-center p-0" style="font-size: 13px;"><b><i><u>JUNIOR HIGH SCHOOL</u></i></b></td>
                </tr>
            </table>
            <table width="100%" style=" table-layout: fixed; font-size: 11px">
                <tr>
                    <td width="30%" class="p-0"></td>
                    <td width="10%" class="text-center p-0" style="font-size: 13px;"><b>SY</b></td>
                    <td width="30%" class="p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="30%" class="p-0"></td>
                </tr>
            </table>
        </td>
        <td width="4%" class="p-0"></td>
        <td width="26%" class="p-0">
            <table width="100%" style=" table-layout: fixed; font-size: 11px">
                <tr>
                    <td width="80%" class="p-0">Registrar's Copy</td>
                    <td width="10%" class="p-0"></td>
                    <td width="10%" class="p-0"></td>
                </tr>
            </table>
            <table width="100%" style=" table-layout: fixed; font-size: 11px">
                <tr>
                    <td width="20%" class="p-0">ID No.:</td>
                    <td width="70%" class="p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="10%" class="p-0"></td>
                </tr>
            </table>
            <table width="100%" style=" table-layout: fixed; font-size: 11px">
                <tr>
                    <td width="20%" class="p-0">Date :</td>
                    <td width="70%" class="p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="10%" class="p-0"></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed;margin-top: 15px; font-size: 11px" >
    <tr>
        <td width="11%" class="text-left p-0"><b><i>LAST NAME:</i></b></td>
        <td width="24%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
        <td width="12%" class="text-left p-0"><b><i>FIRST NAME:</i></b></td>
        <td width="23%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
        <td width="13%" class="text-left p-0"><b><i>MIDDLE NAME:</i></b></td>
        <td width="17%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
    </tr>
</table>
<table width="100%" class="p-0" style="table-layout: fixed;margin-top: 15px; font-size: 11px;">
    <tr>
       <td width="73.5%" style="vertical-align: top;">
            <table width="100%" style="table-layout: fixed; margin-top: 5px;  border: 1px solid #000;">
                <tr>
                    <td width="33%" class="text-left p-0" style="padding-left: 5px!important; padding-top: 5px!important">Total Annual Payable</td>
                    <td width="5%" class="text-left p-0" style=""></td>
                    <td width="20%" class="text-left p-0" style=""></td>
                    <td width="7%" class="text-left p-0" style=""></td>
                    <td width="15%" class="text-left p-0" style=""></td>
                    <td width="2%" class="text-center p-0" style="padding-top: 5px!important">P</td>
                    <td width="13%" class="text-left p-0" style="border-bottom: 1px solid #000;padding-top: 5px!important;"></td>
                    <td width="5%" class="text-left p-0"></td>
                </tr>
                <tr>
                    <td width="33%" class="text-left p-0" style="padding-left: 5px!important">Less: Scholarship Grant</td>
                    <td width="5%" class="text-left p-0" style=""></td>
                    <td width="20%" class="text-left p-0" style=""></td>
                    <td width="7%" class="text-left p-0" style=""></td>
                    <td width="15%" class="text-left p-0" style=""></td>
                    <td width="2%" class="text-left p-0"></td>
                    <td width="13%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="5%" class="text-left p-0"></td>
                </tr>
                <tr>
                    <td width="33%" class="text-left p-0" style="padding-left: 5px!important">Annual Payable</td>
                    <td width="5%" class="text-left p-0" style=""></td>
                    <td width="20%" class="text-left p-0" style=""></td>
                    <td width="7%" class="text-left p-0" style=""></td>
                    <td width="15%" class="text-left p-0" style=""></td>
                    <td width="2%" class="text-center p-0">P</td>
                    <td width="13%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="5%" class="text-left p-0"></td>
                </tr>
                <tr>
                    <td width="33%" class="text-left p-0" style="padding-left: 5px!important">Less: Down payment</td>
                    <td width="5%" class="text-left p-0" style="">Date:</td>
                    <td width="20%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="7%" class="text-center p-0" style="">OR #:</td>
                    <td width="15%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="2%" class="text-center p-0"></td>
                    <td width="13%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="5%" class="text-left p-0"></td>
                </tr>
                <tr>
                    <td width="33%" class="text-left p-0" style="padding-top: 7px!important;padding-left: 5px!important;">Balance</td>
                    <td width="5%" class="text-left p-0" style="padding-top: 7px!important;"></td>
                    <td width="20%" class="text-left p-0" style="padding-top: 7px!important;"></td>
                    <td width="7%" class="text-center p-0" style="padding-top: 7px!important;"></td>
                    <td width="15%" class="text-left p-0" style="padding-top: 7px!important;"></td>
                    <td width="2%" class="text-center p-0" style="padding-top: 7px!important;">P</td>
                    <td width="13%" class="text-left p-0" style="padding-top: 7px!important; border-bottom-style: double"></td>
                    <td width="5%" class="text-left p-0" style="padding-top: 7px!important;"></td>
                </tr>
                <tr>
                    <td width="33%" class="text-left p-0" style="padding-top: 7px!important;padding-left: 5px!important;">Monthly Payable</td>
                    <td width="5%" class="text-left p-0" style="padding-top: 7px!important;"></td>
                    <td width="20%" class="text-left p-0" style="padding-top: 7px!important;"></td>
                    <td width="7%" class="text-center p-0" style="padding-top: 7px!important;"></td>
                    <td width="15%" class="text-left p-0" style="padding-top: 7px!important;"></td>
                    <td width="2%" class="text-center p-0" style="padding-top: 7px!important;">P</td>
                    <td width="13%" class="text-left p-0" style="padding-top: 7px!important; border-bottom-style: double"></td>
                    <td width="5%" class="text-left p-0" style="padding-top: 7px!important;"></td>
                </tr>
                <tr>
                    <td width="100%" colspan="8">&nbsp;</td>
                </tr>
            </table>
            <table width="100%" style="table-layout: fixed;margin-top: 30px; font-size: 11px" >
                <tr>
                    <td width="37.5%" class="text-center p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="37.5%" class="text-center p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="15%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="37.5%" class="text-center p-0"><i>Student's Signature</i></td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="37.5%" class="text-center p-0"><i>Cashier/Finance Officer</i></td>
                    <td width="15%" class="text-center p-0"></td>
                </tr>
            </table>
       </td>
       <td width=".1%"></td>
       <td width="26.4%" style="vertical-align: top;">
            <table width="100%" style="table-layout: fixed; border: 1px solid #000;">
                <tr>
                    <td width="60%">Previous Account</td>
                    <td width="8%">P</td>
                    <td width="30%" style="border-bottom-style: double;"></td>
                    <td width="2%"></td>
                </tr>
                <tr>
                    <td width="60%">Tuition Fee</td>
                    <td width="8%"></td>
                    <td width="30%" style="border-bottom: 1px double solid #000;"></td>
                    <td width="2%"></td>
                </tr>
                <tr>
                    <td width="60%">Miscellaneous Fees</td>
                    <td width="8%"></td>
                    <td width="30%" style="border-bottom: 1px double solid #000;"></td>
                    <td width="2%"></td>
                </tr>
                <tr>
                    <td width="60%">Other Fees</td>
                    <td width="8%"></td>
                    <td width="30%" style="border-bottom: 1px double solid #000;"></td>
                    <td width="2%"></td>
                </tr>
                <tr>
                    <td width="60%">Computer Fee</td>
                    <td width="8%"></td>
                    <td width="30%" style="border-bottom: 1px double solid #000;"></td>
                    <td width="2%"></td>
                </tr>
                <tr>
                    <td width="60%">Graduation Fee</td>
                    <td width="8%"></td>
                    <td width="30%" style="border-bottom: 1px double solid #000;"></td>
                    <td width="2%"></td>
                </tr>
                <tr>
                    <td width="60%">Alumni Fee</td>
                    <td width="8%"></td>
                    <td width="30%" style="border-bottom: 1px double solid #000;"></td>
                    <td width="2%"></td>
                </tr>
                <tr>
                    <td width="60%">P.E. Uniform</td>
                    <td width="8%"></td>
                    <td width="30%" style="border-bottom: 1px double solid #000;"></td>
                    <td width="2%"></td>
                </tr>
                <tr>
                    <td width="60%">Shirt</td>
                    <td width="8%"></td>
                    <td width="30%" style="border-bottom: 1px double solid #000; padding-right: 10px!important;"></td>
                    <td width="2%"></td>
                </tr>
                <tr>
                    <td width="60%"><b>TOTAL</b></td>
                    <td width="8%">P</td>
                    <td width="30%" style="border-bottom-style: double;"></td>
                    <td width="2%"></td>
                </tr>
                <tr>
                    <td width="60%"></td>
                    <td width="8%"></td>
                    <td width="30%"></td>
                    <td width="2%"></td>
                </tr>
                <tr>
                    <td width="60%"></td>
                    <td width="8%"></td>
                    <td width="30%"></td>
                    <td width="2%"></td>
                </tr>
            </table>
       </td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed;margin-top: 15px; font-size: 11px" >
    <tr>
        <td width="100%" class="text-center p-0">---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td>
    </tr>
</table>
<table style="width: 100%; table-layout: fixed;">
    <tr>
        <td width="20%" style="text-align: left; vertical-align: top;">
            <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="80px">
        </td>
        <td style="width: 60%; text-align: center;">
            <div style="width: 100%; font-weight: bold;"><b>{{$schoolinfo[0]->schoolname}}</b></div>
            <div style="width: 100%; font-size: 12px;">Founded in 1965 by the Oblates of Mary Immaculate (OMI)</div>
            <div style="width: 100%; font-size: 12px;">Owned by the Archdiocese of Cotabato</div>
            <div style="width: 100%; font-size: 12px;">Administered by the Diocesan Clergy of Cotabatp (DCC)</div>
            <div style="width: 100%; font-size: 11px;"><i>- Service for the Love of God through Mary</i></div>
            <div style="width: 100%; font-size: 12px;"><b>(B.E.S.T)</b></div>
        </td>
        <td width="20%" style="text-align: right; vertical-align: top;">
            <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="80px">
        </td>
    </tr>
</table>
<table style="width: 100%; table-layout: fixed; border-top: 5px solid green;">
    <tr>
        <td></td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed;margin-top: 5px; font-size: 11px" >
    <tr>
        <td width="26%">
            <table width="100%" style="font-size: 11px;">
                <tr>
                    <td width="42%" class="text-left p-0">&nbsp;</td>
                    <td width="58%" class="p-0">&nbsp;</td>
                </tr>
            </table>
            <table width="100%" style="font-size: 11px;">
                <tr>
                    <td width="15%" class="text-left p-0"><i>Date</i></td>
                    <td width="58%" class="p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="27%"></td>
                </tr>
            </table>
            <table width="100%" style="font-size: 11px">
                <tr>
                    <td width="25%" class="text-left p-0">&nbsp;</td>
                    <td width="15%" class="p-0">&nbsp;</td>
                    <td width="30%" class="text-left p-0">&nbsp;</td>
                    <td width="20%" class="p-0">&nbsp;</td>
                    <td width="10%" class="text-left p-0">&nbsp;</td>
                </tr>
            </table>
        </td>
        <td width="4%" class="p-0"></td>
        <td width="40%" class="p-0">
            <table width="100%" style=" table-layout: fixed; font-size: 11px">
                <tr>
                    <td class="text-center p-0" style="font-size: 13px;"><b><u>CERTIFICATE</u> <u>OF</u> <u>REGISTRATION</u></b></td>
                </tr>
                
            </table>
            <table width="100%" style=" table-layout: fixed; font-size: 11px">
                <tr>
                    <td class="text-center p-0" style="font-size: 13px;"><b><i><u>JUNIOR HIGH SCHOOL</u></i></b></td>
                </tr>
            </table>
            <table width="100%" style=" table-layout: fixed; font-size: 11px">
                <tr>
                    <td width="30%" class="p-0"></td>
                    <td width="10%" class="text-center p-0" style="font-size: 13px;"><b>SY</b></td>
                    <td width="30%" class="p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="30%" class="p-0"></td>
                </tr>
            </table>
        </td>
        <td width="4%" class="p-0"></td>
        <td width="26%" class="p-0">
            <table width="100%" style=" table-layout: fixed; font-size: 11px">
                <tr>
                    <td width="80%" class="p-0"><i>Student's Copy</i></td>
                    <td width="10%" class="p-0"></td>
                    <td width="10%" class="p-0"></td>
                </tr>
            </table>
            <table width="100%" style=" table-layout: fixed; font-size: 11px">
                <tr>
                    <td width="20%" class="p-0"><i>ID No.:</i></td>
                    <td width="70%" class="p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="10%" class="p-0"></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table width="100%" class="p-0" style="table-layout: fixed;margin-top: 15px; font-size: 11px;">
    <tr>
       <td width="73.5%" style="vertical-align: top;">
            <table width="100%" style="table-layout: fixed; font-size: 11px" >
                <tr>
                    <td width="3%" class="p-0"></td>
                    <td width="19%" class="p-0"><i>This is to certify that</i></td>
                    <td width="48%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="25%" class="p-0"><i>is officially enrolled in</i></td>
                    <td width="5%" class="p-0"></td>
                </tr>
            </table>
            <table width="100%" style="table-layout: fixed; font-size: 11px" >
                <tr>
                    <td width="17%" class="text-left p-0"><i>(Grade & section)</i></td>
                    <td width="23%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="17%" class="p-0"><i>, (track & strand)</i></td>
                    <td width="31%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="12%" class="p-0"></td>
                </tr>
            </table>
            <table width="100%" style="table-layout: fixed; font-size: 11px" >
                <tr>
                    <td width="18%" class="text-left p-0"><i>for the school year</i></td>
                    <td width="22%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="60%" class="text-left p-0">.</td>
                </tr>
            </table>
            <table width="100%" style="table-layout: fixed;margin-top: 30px; font-size: 11px" >
                <tr>
                    <td width="37.5%" class="text-center p-0"></td>
                    <td width="25%" class="text-center p-0"></td>
                    <td width="37.5%" class="text-center p-0" style="border-bottom: 1px solid #000; font-size: 15px"><i><b>{{$principal_info[0]->name}}</b></i></td>
                </tr>
                <tr>
                    <td width="37.5%" class="text-center p-0"></td>
                    <td width="25%" class="text-center p-0"></td>
                    <td width="37.5%" class="text-center p-0"><i>{{$principal_info[0]->title}}</i></td>
                    
                </tr>
            </table>
            <table width="100%" style="table-layout: fixed; margin-top: 5px;  border: 1px solid #000;">
                <tr>
                    <td width="33%" class="text-left p-0" style="padding-left: 5px!important; padding-top: 5px!important">Total Annual Payable</td>
                    <td width="5%" class="text-left p-0" style=""></td>
                    <td width="20%" class="text-left p-0" style=""></td>
                    <td width="7%" class="text-left p-0" style=""></td>
                    <td width="15%" class="text-left p-0" style=""></td>
                    <td width="2%" class="text-center p-0" style="padding-top: 5px!important">P</td>
                    <td width="13%" class="text-left p-0" style="border-bottom: 1px solid #000;padding-top: 5px!important;"></td>
                    <td width="5%" class="text-left p-0"></td>
                </tr>
                <tr>
                    <td width="33%" class="text-left p-0" style="padding-left: 5px!important">Less: Scholarship Grant</td>
                    <td width="5%" class="text-left p-0" style=""></td>
                    <td width="20%" class="text-left p-0" style=""></td>
                    <td width="7%" class="text-left p-0" style=""></td>
                    <td width="15%" class="text-left p-0" style=""></td>
                    <td width="2%" class="text-left p-0"></td>
                    <td width="13%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="5%" class="text-left p-0"></td>
                </tr>
                <tr>
                    <td width="33%" class="text-left p-0" style="padding-left: 5px!important">Annual Payable</td>
                    <td width="5%" class="text-left p-0" style=""></td>
                    <td width="20%" class="text-left p-0" style=""></td>
                    <td width="7%" class="text-left p-0" style=""></td>
                    <td width="15%" class="text-left p-0" style=""></td>
                    <td width="2%" class="text-center p-0">P</td>
                    <td width="13%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="5%" class="text-left p-0"></td>
                </tr>
                <tr>
                    <td width="33%" class="text-left p-0" style="padding-left: 5px!important">Less: Down payment</td>
                    <td width="5%" class="text-left p-0" style="">Date:</td>
                    <td width="20%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="7%" class="text-center p-0" style="">OR #:</td>
                    <td width="15%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="2%" class="text-center p-0"></td>
                    <td width="13%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="5%" class="text-left p-0"></td>
                </tr>
                <tr>
                    <td width="33%" class="text-left p-0" style="padding-top: 7px!important;padding-left: 5px!important;">Balance</td>
                    <td width="5%" class="text-left p-0" style="padding-top: 7px!important;"></td>
                    <td width="20%" class="text-left p-0" style="padding-top: 7px!important;"></td>
                    <td width="7%" class="text-center p-0" style="padding-top: 7px!important;"></td>
                    <td width="15%" class="text-left p-0" style="padding-top: 7px!important;"></td>
                    <td width="2%" class="text-center p-0" style="padding-top: 7px!important;">P</td>
                    <td width="13%" class="text-left p-0" style="padding-top: 7px!important; border-bottom-style: double"></td>
                    <td width="5%" class="text-left p-0" style="padding-top: 7px!important;"></td>
                </tr>
                <tr>
                    <td width="33%" class="text-left p-0" style="padding-top: 7px!important;padding-left: 5px!important;">Monthly Payable</td>
                    <td width="5%" class="text-left p-0" style="padding-top: 7px!important;"></td>
                    <td width="20%" class="text-left p-0" style="padding-top: 7px!important;"></td>
                    <td width="7%" class="text-center p-0" style="padding-top: 7px!important;"></td>
                    <td width="15%" class="text-left p-0" style="padding-top: 7px!important;"></td>
                    <td width="2%" class="text-center p-0" style="padding-top: 7px!important;">P</td>
                    <td width="13%" class="text-left p-0" style="padding-top: 7px!important; border-bottom-style: double"></td>
                    <td width="5%" class="text-left p-0" style="padding-top: 7px!important;"></td>
                </tr>
                <tr>
                    <td width="100%" colspan="8">&nbsp;</td>
                </tr>
            </table>
            {{-- <table width="100%" style="table-layout: fixed;margin-top: 30px; font-size: 11px" >
                <tr>
                    <td width="37.5%" class="text-center p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="37.5%" class="text-center p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="15%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="37.5%" class="text-center p-0"><i>Student's Signature</i></td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="37.5%" class="text-center p-0"><i>Cashier/Finance Officer</i></td>
                    <td width="15%" class="text-center p-0"></td>
                </tr>
            </table> --}}
       </td>
       <td width=".1%"></td>
       <td width="26.4%" style="vertical-align: top;">
            <table width="100%" style="table-layout: fixed; margin-top: 20px; border: 1px solid #000;">
                <tr>
                    <td width="60%">Previous Account</td>
                    <td width="8%">P</td>
                    <td width="30%" style="border-bottom-style: double;"></td>
                    <td width="2%"></td>
                </tr>
                <tr>
                    <td width="60%">Tuition Fee</td>
                    <td width="8%"></td>
                    <td width="30%" style="border-bottom: 1px double solid #000;"></td>
                    <td width="2%"></td>
                </tr>
                <tr>
                    <td width="60%">Miscellaneous Fees</td>
                    <td width="8%"></td>
                    <td width="30%" style="border-bottom: 1px double solid #000;"></td>
                    <td width="2%"></td>
                </tr>
                <tr>
                    <td width="60%">Other Fees</td>
                    <td width="8%"></td>
                    <td width="30%" style="border-bottom: 1px double solid #000;"></td>
                    <td width="2%"></td>
                </tr>
                <tr>
                    <td width="60%">Computer Fee</td>
                    <td width="8%"></td>
                    <td width="30%" style="border-bottom: 1px double solid #000;"></td>
                    <td width="2%"></td>
                </tr>
                <tr>
                    <td width="60%">Graduation Fee</td>
                    <td width="8%"></td>
                    <td width="30%" style="border-bottom: 1px double solid #000;"></td>
                    <td width="2%"></td>
                </tr>
                <tr>
                    <td width="60%">Alumni Fee</td>
                    <td width="8%"></td>
                    <td width="30%" style="border-bottom: 1px double solid #000;"></td>
                    <td width="2%"></td>
                </tr>
                <tr>
                    <td width="60%">P.E. Uniform</td>
                    <td width="8%"></td>
                    <td width="30%" style="border-bottom: 1px double solid #000;"></td>
                    <td width="2%"></td>
                </tr>
                <tr>
                    <td width="60%">Shirt</td>
                    <td width="8%"></td>
                    <td width="30%" style="border-bottom: 1px double solid #000; padding-right: 10px!important;"></td>
                    <td width="2%"></td>
                </tr>
                <tr>
                    <td width="60%"><b>TOTAL</b></td>
                    <td width="8%">P</td>
                    <td width="30%" style="border-bottom-style: double;"></td>
                    <td width="2%"></td>
                </tr>
                <tr>
                    <td width="60%"></td>
                    <td width="8%"></td>
                    <td width="30%"></td>
                    <td width="2%"></td>
                </tr>
                <tr>
                    <td width="60%"></td>
                    <td width="8%"></td>
                    <td width="30%"></td>
                    <td width="2%"></td>
                </tr>
            </table>
       </td>
    </tr>
</table>
</body>
</html>