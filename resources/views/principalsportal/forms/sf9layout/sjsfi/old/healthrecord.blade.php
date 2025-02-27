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

        body { 
            /* margin:0px 10px; */
            
        }
		
		 .check_mark {
               font-family: ZapfDingbats, sans-serif;
            }
        @page { size: 8.5in 14in; margin: 10px 80px;  }
        
    </style>
</head>
<body> 
<table style="width: 100%; table-layout: fixed;">
     <tr>
        <td width="100%" style="text-align: center;">
            <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="80px">
        </td>
     </tr>
     <tr>
        <td width="100%" style="text-align: center;">
            <span style="width: 100%; font-weight: bold; font-size: 15px;"><b>{{$schoolinfo[0]->schoolname}}</b></span>
        </td>
     </tr>
</table>
<table style="width: 100%; table-layout: fixed; margin-top: 15px">
    <tr>
       <td width="4%" class="text-center p-0"></td>
       <td width="96%" class="text-left p-0" style="font-size: 13px"><i><u>PERSONAL DATA</u></i></td>
    </tr>
    <tr>
       <td width="4%" class="text-center p-0"></td>
        <td width="96%" class="text-left p-0" style="font-size: 12px">Child's Name:</td>
     </tr>
</table>
<br>
<table style="width: 100%; table-layout: fixed; font-size: 11px">
    <tr>
        <td width="4%" class="text-center p-0"></td>
        <td width="29%" class="text-center p-0" style="border-bottom: 1px solid #000;"></td>
        <td width="7%" class="text-left p-0">,</td>
        <td width="20%" class="text-center p-0" style="border-bottom: 1px solid #000;"></td>
        <td width="7%" class="text-center p-0"></td>
        <td width="7%" class="text-center p-0" style="border-bottom: 1px solid #000;"></td>
        <td width="7%" class="text-center p-0"></td>
        <td width="19%" class="text-center p-0" style="border-bottom: 1px solid #000;"></td>
    </tr>
    <tr>
        <td width="4%" class="text-center p-0"></td>
        <td width="29%" class="text-center p-0">Last Name</td>
        <td width="7%" class="text-left p-0"></td>
        <td width="20%" class="text-center p-0">First Name</td>
        <td width="7%" class="text-center p-0"></td>
        <td width="7%" class="text-center p-0">M.I.</td>
        <td width="7%" class="text-center p-0"></td>
        <td width="19%" class="text-center p-0">Date of Birth</td>
     </tr>
</table>
<table style="width: 100%; table-layout: fixed;">
    <tr>
        <td width="4%" class="text-center p-0"></td>
        <td width="96%" class="text-left p-0" style="font-size: 13px">Address:</td>
    </tr>
</table>
<table style="width: 100%; table-layout: fixed; font-size: 11px; margin-top: 15px">
    <tr>
        <td width="4%" class="text-center p-0"></td>
        <td width="29%" class="text-center p-0" style="border-bottom: 1px solid #000;"></td>
        <td width="7%" class="text-left p-0"></td>
        <td width="29%" class="text-center p-0" style="border-bottom: 1px solid #000;"></td>
        <td width="7%" class="text-center p-0"></td>
        <td width="20%" class="text-center p-0" style="border-bottom: 1px solid #000;"></td>
        <td width="7%" class="text-center p-0"></td>
    </tr>
    <tr>
        <td width="4%" class="text-center p-0"></td>
        <td width="29%" class="text-center p-0">Number & Street</td>
        <td width="7%" class="text-left p-0"></td>
        <td width="29%" class="text-center p-0">City</td>
        <td width="7%" class="text-center p-0"></td>
        <td width="20%" class="text-center p-0">Zip Code</td>
        <td width="7%" class="text-center p-0"></td>
     </tr>
</table>
<table style="width: 100%; table-layout: fixed; margin-top: 15px">
    <tr>
        <td width="4%" class="text-center p-0"></td>
        <td width="96%" class="text-left p-0" style="font-size: 13px">Parent/Guardian:</td>
    </tr>
</table>
<table style="width: 100%; table-layout: fixed; font-size: 11px">
    <tr>
        <td width="4%" class="text-center p-0"></td>
        <td width="29%" class="text-center p-0" style="border-bottom: 1px solid #000;"></td>
        <td width="7%" class="text-left p-0">,</td>
        <td width="20%" class="text-center p-0" style="border-bottom: 1px solid #000;"></td>
        <td width="7%" class="text-center p-0"></td>
        <td width="11%" class="text-left p-0">Tel/Cel No.:</td>
        <td width="22%" class="text-center p-0" style="border-bottom: 1px solid #000;"></td>
    </tr>
    <tr>
        <td width="4%" class="text-center p-0"></td>
        <td width="29%" class="text-center p-0">Last Name</td>
        <td width="7%" class="text-left p-0"></td>
        <td width="20%" class="text-center p-0">First Name</td>
        <td width="7%" class="text-center p-0"></td>
        <td width="11%" class="text-center p-0"></td>
        <td width="22%" class="text-center p-0"></td>
     </tr>
</table>
<table style="width: 100%; table-layout: fixed; margin-top: 15px">
    <tr>
        <td width="4%" class="text-center p-0"></td>
        <td width="96%" class="text-left p-0" style="font-size: 13px">Address:</td>
    </tr>
</table>
<table style="width: 100%; table-layout: fixed; font-size: 11px">
    <tr>
        <td width="4%" class="text-center p-0"></td>
        <td width="29%" class="text-center p-0" style="border-bottom: 1px solid #000;"></td>
        <td width="7%" class="text-left p-0">,</td>
        <td width="20%" class="text-center p-0" style="border-bottom: 1px solid #000;"></td>
        <td width="7%" class="text-center p-0"></td>
        <td width="11%" class="text-left p-0">Tel/Cel No.:</td>
        <td width="22%" class="text-center p-0" style="border-bottom: 1px solid #000;"></td>
    </tr>
    <tr>
        <td width="4%" class="text-center p-0"></td>
        <td width="29%" class="text-center p-0">Number & Street</td>
        <td width="7%" class="text-left p-0"></td>
        <td width="20%" class="text-center p-0">City</td>
        <td width="7%" class="text-center p-0"></td>
        <td width="11%" class="text-center p-0"></td>
        <td width="22%" class="text-center p-0"></td>
     </tr>
</table>
<table style="width: 100%; table-layout: fixed; margin-top: 15px">
    <tr>
        <td width="4%" class="text-center p-0"></td>
        <td width="96%" class="text-left p-0" style="font-size: 13px">In case of Emergency:</td>
    </tr>
</table>
<table style="width: 100%; table-layout: fixed; font-size: 11px">
    <tr>
        <td width="4%" class="text-center p-0"></td>
        <td width="6%" class="text-left p-0">Name:</td>
        <td width="23%" class="text-center p-0" style="border-bottom: 1px solid #000;"></td>
        <td width="4%" class="text-left p-0"></td>
        <td width="25%" class="text-center p-0">Telephone/Cellphone No.:</td>
        <td width="23%" class="text-center p-0" style="border-bottom: 1px solid #000;"></td>
        <td width="15%" class="text-left p-0"></td>
    </tr>
</table>
<table style="width: 100%; table-layout: fixed; margin-top: 15px">
    <tr>
        <td width="4%" class="text-center p-0"></td>
        <td width="96%" style="text-align: center; font-size: 15px"><b>SECTION I - FAMILY HEALTH HISTORY</b></td>
    </tr>
</table>
<table style="width: 100%; table-layout: fixed; font-size: 13px; margin-top: 15px">
    <tr>
        <td width="4%">
            <table>
                <tr>
                    <td width="100%"></td>
                </tr>
            </table>
        </td>
        <td width="96%">
            <table class="table-bordered" style="width: 100%; table-layout: fixed;">
                <tr>
                    <td class="text-center" width="12%">DISEASE</td>
                    <td class="text-center" width="12%">NO</td>
                    <td class="text-center" width="12%">YES</td>
                    <td class="text-center" width="12%">Relationship</td>
                    <td class="text-center" width="12%">DISEASE</td>
                    <td class="text-center" width="12%">NO</td>
                    <td class="text-center" width="12%">YES</td>
                    <td class="text-center" width="12%">Relationship</td>
                </tr>
                <tr>
                    <td class="text-center" width="12%" style="font-size: 10px">Cancer</td>
                    <td class="text-center" width="12%"></td>
                    <td class="text-center" width="12%"></td>
                    <td class="text-center" width="12%"></td>
                    <td class="text-center" width="12%" style="font-size: 10px">Tuberculosis</td>
                    <td class="text-center" width="12%"></td>
                    <td class="text-center" width="12%"></td>
                    <td class="text-center" width="12%"></td>
                </tr>
                <tr>
                    <td class="text-center" width="12%" style="font-size: 10px">Heart <br> Problem</td>
                    <td class="text-center" width="12%"></td>
                    <td class="text-center" width="12%"></td>
                    <td class="text-center" width="12%"></td>
                    <td class="text-center" width="12%" style="font-size: 10px">Asthma</td>
                    <td class="text-center" width="12%"></td>
                    <td class="text-center" width="12%"></td>
                    <td class="text-center" width="12%"></td>
                </tr>
                <tr>
                    <td class="text-center" width="12%" style="font-size: 10px">High Blood</td>
                    <td class="text-center" width="12%"></td>
                    <td class="text-center" width="12%"></td>
                    <td class="text-center" width="12%"></td>
                    <td class="text-center" width="12%" style="font-size: 10px">Bleeding <br> Tendencies</td>
                    <td class="text-center" width="12%"></td>
                    <td class="text-center" width="12%"></td>
                    <td class="text-center" width="12%"></td>
                </tr>
                <tr>
                    <td class="text-center" width="12%" style="font-size: 10px">Diabetes</td>
                    <td class="text-center" width="12%"></td>
                    <td class="text-center" width="12%"></td>
                    <td class="text-center" width="12%"></td>
                    <td class="text-center" width="12%" style="font-size: 10px">Mental <br> trouble</td>
                    <td class="text-center" width="12%"></td>
                    <td class="text-center" width="12%"></td>
                    <td class="text-center" width="12%"></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table style="width: 100%; table-layout: fixed; margin-top: 15px">
    <tr>
        <td width="100%" style="text-align: left; font-size: 12px">FAMILY BACKGROUND (Please check the space and underline the coresponding answer)</td>
    </tr>
</table>
<table style="width: 100%; table-layout: fixed; margin-top: 15px">
    <tr>
        <td width="48%">
            <table style="width: 100%; table-layout: fixed;">
                <tr>
                    <td width="50%" class="text-left p-0" style="font-size: 10px">Occupation of Parent / Guardian</td>
                    <td width="50%" class="text-center p-0" style="border-bottom: 1px solid #000;"></td>
                </tr>
            </table>
            <table style="width: 100%; table-layout: fixed;">
                <tr>
                    <td class="text-center p-0" width="10%" style="border-bottom: 1px solid #000;"></td>
                    <td width="90%" class="text-left p-0" style="font-size: 10px">Orphan (both parent's deceased)</td>
                </tr>
                <tr>
                    <td class="text-center p-0" width="10%" style="border-bottom: 1px solid #000;"></td>
                    <td width="90%" class="text-left p-0" style="font-size: 10px">Living with both parents</td>
                </tr>
                <tr>
                    <td class="text-center p-0" width="10%" style="border-bottom: 1px solid #000;"></td>
                    <td width="90%" class="text-left p-0" style="font-size: 10px">Living with both parents (Father/mother)</td>
                </tr>
            </table>
            <table style="width: 100%; table-layout: fixed;">
                <tr>
                    <td class="text-center p-0" width="10%" style="border-bottom: 1px solid #000;"></td>
                    <td width="61%" class="text-left p-0" style="font-size: 10px">Living with Guardian (specify relationship </td>
                    <td width="28%" class="text-center p-0" width="10%" style="border-bottom: 1px solid #000;"></td>
                    <td width="1%" class="text-right p-0" width="10%" style="font-size: 10px">)</td>
                </tr>
            </table>
        </td>
        <td width="7%"></td>
        <td width="45%" style="vertical-align: top;">
            <table style="width: 100%; table-layout: fixed; ">
                <tr>
                    <td width="45%" class="text-left p-0" style="font-size: 10px">No. of household members:</td>
                    <td width="20%" class="text-center p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="35%" class="text-center p-0"></td>
                </tr>
            </table>
            <table style="width: 100%; table-layout: fixed; ">
                <tr>
                    <td width="25%" class="text-left p-0" style="font-size: 10px">No. of siblings:</td>
                    <td width="30%" class="text-center p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="45%" class="text-center p-0"></td>
                </tr>
            </table>
            <table style="width: 100%; table-layout: fixed; ">
                <tr>
                    <td width="32%" class="text-left p-0" style="font-size: 10px">Order in the Family:</td>
                    <td width="23%" class="text-center p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="45%" class="text-center p-0"></td>
                </tr>
            </table>
            <table style="width: 100%; table-layout: fixed; ">
                <tr>
                    <td width="50%" class="text-left p-0" style="font-size: 10px">Is there a smoker in the family?</td>
                    <td width="15%" class="text-center p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="5%" class="text-left p-0" style="font-size: 10px">Yes</td>
                    <td width="7%" class="text-center p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="5%" class="text-left p-0" style="font-size: 10px">No</td>
                    <td width="7%" class="text-center p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="11%" class="text-left p-0"></td>
                </tr>
            </table>
            <table width="100%" style="table-layout: fixed; ">
                <tr>
                    <td width="50%" class="text-left p-0" style="font-size: 10px">Is there an alcohol drinker?</td>
                    <td width="15%" class="text-center p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="5%" class="text-left p-0" style="font-size: 10px">Yes</td>
                    <td width="7%" class="text-center p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="5%" class="text-left p-0" style="font-size: 10px">No</td>
                    <td width="7%" class="text-center p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="11%" class="text-left p-0"></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed; margin-top: 15px">
    <tr>
        <td width="100%" style="text-align: center; font-size: 14px"><b>SECTION II - CHILD PAST MEDICAL HISTORY , Has the child suffered from any of the following:</b></td>
    </tr>
</table>
<table width="100%" class="table table-bordered" style="table-layout: fixed; margin-top: 15px">
    <tr>
        <td width="20%" class="text-center p-0">Disease</td>
        <td width="5%" class="text-center p-0">NO</td>
        <td width="5%" class="text-center p-0">YES</td>
        <td width="20%" class="text-center p-0">Disease</td>
        <td width="5%" class="text-center p-0">NO</td>
        <td width="5%" class="text-center p-0">YES</td>
        <td width="30%" class="text-center p-0">Disease</td>
        <td width="5%" class="text-center p-0">NO</td>
        <td width="5%" class="text-center p-0">YES</td>
    </tr>
    <tr>
        <td width="20%" class="text-left p-0" style="padding-left: 5px!important;">Allergy</td>
        <td width="5%" class="text-left p-0" style="padding-left: 5px!important;"></td>
        <td width="5%" class="text-left p-0" style="padding-left: 5px!important;"></td>
        <td width="20%" class="text-left p-0" style="padding-left: 5px!important;">Chicken Pox</td>
        <td width="5%" class="text-left p-0" style="padding-left: 5px!important;"></td>
        <td width="5%" class="text-left p-0" style="padding-left: 5px!important;"></td>
        <td width="30%" class="text-left p-0" style="padding-left: 5px!important;">Heart Disease</td>
        <td width="5%" class="text-left p-0" style="padding-left: 5px!important;"></td>
        <td width="5%" class="text-left p-0" style="padding-left: 5px!important;"></td>
    </tr>
    <tr>
        <td width="20%" class="text-left p-0" style="padding-left: 5px!important;">Asthma</td>
        <td width="5%" class="text-left p-0" style="padding-left: 5px!important;"></td>
        <td width="5%" class="text-left p-0" style="padding-left: 5px!important;"></td>
        <td width="20%" class="text-left p-0" style="padding-left: 5px!important;">Dengue Fever</td>
        <td width="5%" class="text-left p-0" style="padding-left: 5px!important;"></td>
        <td width="5%" class="text-left p-0" style="padding-left: 5px!important;"></td>
        <td width="30%" class="text-left p-0" style="padding-left: 5px!important;">Kidney Disease</td>
        <td width="5%" class="text-left p-0" style="padding-left: 5px!important;"></td>
        <td width="5%" class="text-left p-0" style="padding-left: 5px!important;"></td>
    </tr>
    <tr>
        <td width="20%" class="text-left p-0" style="padding-left: 5px!important;"></td>
        <td width="5%" class="text-left p-0" style="padding-left: 5px!important;"></td>
        <td width="5%" class="text-left p-0" style="padding-left: 5px!important;"></td>
        <td width="20%" class="text-left p-0" style="padding-left: 5px!important;"></td>
        <td width="5%" class="text-left p-0" style="padding-left: 5px!important;"></td>
        <td width="5%" class="text-left p-0" style="padding-left: 5px!important;"></td>
        <td width="30%" class="text-left p-0" style="padding-left: 5px!important;">Others, specify:</td>
        <td width="5%" class="text-left p-0" style="padding-left: 5px!important;"></td>
        <td width="5%" class="text-left p-0" style="padding-left: 5px!important;"></td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed; margin-top: 15px">
    <tr>
        <td width="40%" class="text-left p-0" style="font-size: 12px"><b><i>if answer is YES, please give relevant details</i></b></td>
        <td width="60%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed;">
    <tr>
        <td width="51%" class="text-left p-0" style="font-size: 12px"><b><i>Other information (may use additional sheet if necessary)</i></b></td>
        <td width="49%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
    </tr>
</table>
<table width="100%" class="table table-bordered" style="table-layout: fixed; margin-top: 15px">
    <tr>
        <td width="57%" class="text-left p-0" style="padding-left: 5px!important;"><b>Any SPECIAL MEDICATIONS?</b></td>
        <td width="48%" class="text-left p-0" style="padding-left: 5px!important;"><b>Allergy to MEDICINES?</b></td>
    </tr>
    <tr>
        <td width="57%" class="text-left p-0" style="padding-left: 5px!important;"><b>Requires SPECIAL CARE?</b></td>
        <td width="48%" class="text-left p-0" style="padding-left: 5px!important;"><b>OTHERS</b></td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed; margin-top: 15px">
    <tr>
        <td width="100%" style="text-align: left; font-size: 13px"><u>IMMUNIZATION HISTORY</u></td>
    </tr>
</table>
<table width="100%" class="table table-bordered" style="table-layout: fixed; margin-top: 15px">
    <tr>
        <td width="30%" class="text-center p-0"><b>IMMUNIZATION</b></td>
        <td width="20%" class="text-center p-0"><b>DATE</b></td>
        <td width="30%" class="text-center p-0"><b>IMMUNIZATION</b></td>
        <td width="20%" class="text-center p-0"><b>DATE</b></td>
    </tr>
    <tr>
        <td width="30%" class="text-left p-0" style="padding-left: 5px!important;">BCG</td>
        <td width="20%" class="text-left p-0" style="padding-left: 5px!important;"></td>
        <td width="30%" class="text-left p-0" style="padding-left: 5px!important;">MEASLES</td>
        <td width="20%" class="text-left p-0" style="padding-left: 5px!important;"></td>
    </tr>
    <tr>
        <td width="30%" class="text-left p-0" style="padding-left: 5px!important;">DPT/OPV 1</td>
        <td width="20%" class="text-left p-0" style="padding-left: 5px!important;"></td>
        <td width="30%" class="text-left p-0" style="padding-left: 5px!important;">Hepatitis B 1</td>
        <td width="20%" class="text-left p-0" style="padding-left: 5px!important;"></td>
    </tr>
    <tr>
        <td width="30%" class="text-left p-0" style="padding-left: 5px!important;"></td>
        <td width="20%" class="text-left p-0" style="padding-left: 5px!important;"></td>
        <td width="30%" class="text-left p-0" style="padding-left: 5px!important;">Others, specify:</td>
        <td width="20%" class="text-left p-0" style="padding-left: 5px!important;"></td>
    </tr>
</table>

</body>
</html>