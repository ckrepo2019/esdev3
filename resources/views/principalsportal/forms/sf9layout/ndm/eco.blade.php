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
        @page { size: 14in 8.5in; margin: 20px 20px;  }
        
    </style>
</head>
<body>
<table width="100%" style="table-layout: fixed;">
    <tr>
        <td width="33.3333333333%" style="vertical-align:top; padding-left: 5px; padding-right: 5px; border: 1px solid #000;">
            <table width="100%" class="table" style="table-layout: fixed; margin-top: 45px;">
                <tr>
                    <td width="25%%" class="text-center" style="border: 1px solid #000; vertical-align: middle;">DOMAIN</td>
                    <td colspan="2" class="p-0" style="text-align: left; border: 1px solid #000; vertical-align: top;">
                        <table width="100%" style="table-layout: fixed; font-size: 12px;">
                            <tr>
                                <td width="100%" class="text-left p-0" style="padding-left: 5px!important; padding-top: 1!important;">1<sup>st</sup> Evaluation</td>
                            </tr>
                        </table>
                        <table width="100%" style="table-layout: fixed; font-size: 12px;">
                            <tr>
                                <td width="27%" class="text-left p-0" style="padding-left: 5px!important;">Date:</td>
                                <td width="63%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                                <td width="10%" class="text-left p-0" style=""></td>
                            </tr>
                        </table>
                        <table width="100%" style="table-layout: fixed; font-size: 12px; padding-bottom: 3px;">
                            <tr>
                                <td width="50%" class="text-left p-0" style="padding-left: 5px!important;">Child's Age:</td>
                                <td width="40%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                                <td width="10%" class="text-left p-0" style=""></td>
                            </tr>
                        </table>
                    </td>
                    <td colspan="2" class="p-0" style="text-align: left; border: 1px solid #000;">
                        <table width="100%" style="table-layout: fixed; font-size: 12px;">
                            <tr>
                                <td width="100%" class="text-left p-0" style="padding-left: 5px!important; padding-top: 1!important;">1<sup>st</sup> Evaluation</td>
                            </tr>
                        </table>
                        <table width="100%" style="table-layout: fixed; font-size: 12px;">
                            <tr>
                                <td width="22%" class="text-left p-0" style="padding-left: 5px!important;">Date:</td>
                                <td width="63%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                                <td width="15%" class="text-left p-0" style=""></td>
                            </tr>
                        </table>
                        <table width="100%" style="table-layout: fixed; font-size: 12px; padding-bottom: 3px;">
                            <tr>
                                <td width="42%" class="text-left p-0" style="padding-left: 5px!important;">Child's Age:</td>
                                <td width="43%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                                <td width="15%" class="text-left p-0" style=""></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td width="25%%" class="text-left p-0" style="border: 1px solid #000; vertical-align: bottom; padding-left: 5px!important;">1. Gross Motor</td>
                    <td width="17.5%" class="p-0"style="text-align: left; border: 1px solid #000; vertical-align: top;"></td>
                    <td width="17.5%" style="text-align: left; border: 1px solid #000;"></td>
                    <td width="20%" style="text-align: left; border: 1px solid #000;"></td>
                    <td width="20%" style="text-align: left; border: 1px solid #000;"></td>
                </tr>
                <tr>
                    <td width="25%%" class="text-left p-0" style="border: 1px solid #000; vertical-align: bottom; padding-left: 5px!important;">2. Fine Motor</td>
                    <td width="17.5%" class="p-0"style="text-align: left; border: 1px solid #000; vertical-align: top;"></td>
                    <td width="17.5%" style="text-align: left; border: 1px solid #000;"></td>
                    <td width="20%" style="text-align: left; border: 1px solid #000;"></td>
                    <td width="20%" style="text-align: left; border: 1px solid #000;"></td>
                </tr>
                <tr>
                    <td width="25%%" class="text-left p-0" style="border: 1px solid #000; vertical-align: bottom; padding-left: 5px!important;">3. Self Help.</td>
                    <td width="17.5%" class="p-0"style="text-align: left; border: 1px solid #000; vertical-align: top;"></td>
                    <td width="17.5%" style="text-align: left; border: 1px solid #000;"></td>
                    <td width="20%" style="text-align: left; border: 1px solid #000;"></td>
                    <td width="20%" style="text-align: left; border: 1px solid #000;"></td>
                </tr>
                <tr>
                    <td width="25%%" class="text-left p-0" style="border: 1px solid #000; vertical-align: bottom; padding-left: 5px!important;">4. Receptive <br> &nbsp;&nbsp;&nbsp;&nbsp;Language</td>
                    <td width="17.5%" class="p-0"style="text-align: left; border: 1px solid #000; vertical-align: top;"></td>
                    <td width="17.5%" style="text-align: left; border: 1px solid #000;"></td>
                    <td width="20%" style="text-align: left; border: 1px solid #000;"></td>
                    <td width="20%" style="text-align: left; border: 1px solid #000;"></td>
                </tr>
                <tr>
                    <td width="25%%" class="text-left p-0" style="border: 1px solid #000; vertical-align: bottom; padding-left: 5px!important;">5. Expressive <br> &nbsp;&nbsp;&nbsp;&nbsp;Language</td>
                    <td width="17.5%" class="p-0"style="text-align: left; border: 1px solid #000; vertical-align: top;"></td>
                    <td width="17.5%" style="text-align: left; border: 1px solid #000;"></td>
                    <td width="20%" style="text-align: left; border: 1px solid #000;"></td>
                    <td width="20%" style="text-align: left; border: 1px solid #000;"></td>
                </tr>
                <tr>
                    <td width="25%%" class="text-left p-0" style="border: 1px solid #000; vertical-align: bottom; padding-left: 5px!important;">6. Cognitive</td>
                    <td width="17.5%" class="p-0"style="text-align: left; border: 1px solid #000; vertical-align: top;"></td>
                    <td width="17.5%" style="text-align: left; border: 1px solid #000;"></td>
                    <td width="20%" style="text-align: left; border: 1px solid #000;"></td>
                    <td width="20%" style="text-align: left; border: 1px solid #000;"></td>
                </tr>
                <tr>
                    <td width="25%%" class="text-left p-0" style="border: 1px solid #000; vertical-align: bottom; padding-left: 5px!important;">7. Socio- <br> &nbsp;&nbsp;&nbsp;&nbsp;Emotional</td>
                    <td width="17.5%" class="p-0"style="text-align: left; border: 1px solid #000; vertical-align: top;"></td>
                    <td width="17.5%" style="text-align: left; border: 1px solid #000;"></td>
                    <td width="20%" style="text-align: left; border: 1px solid #000;"></td>
                    <td width="20%" style="text-align: left; border: 1px solid #000;"></td>
                </tr>
                <tr>
                    <td width="25%%" rowspan="3" class="text-left p-0" style="border: 1px solid #000; vertical-align: top; padding-left: 5px!important;"><b>Sum of Scaled <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Score</b></td>
                    <td width="17.5%" rowspan="3" class="p-0"style="text-align: left; border: 1px solid #000; vertical-align: top;"></td>
                    <td width="17.5%" rowspan="3" style="text-align: left; border: 1px solid #000;"></td>
                    <td width="20%" rowspan="3" style="text-align: left; border: 1px solid #000;"></td>
                    <td width="20%" rowspan="3" style="text-align: left; border: 1px solid #000;"></td>
                </tr>
                <tr></tr>
                <tr></tr>
                <tr>
                    <td width="25%%" class="text-left p-0" style="border: 1px solid #000; vertical-align: middle; padding-left: 5px!important;"><b>Standard Score</b></td>
                    <td width="17.5%" class="p-0"style="text-align: left; border: 1px solid #000; vertical-align: top;"></td>
                    <td width="17.5%" style="text-align: left; border: 1px solid #000;"></td>
                    <td width="20%" style="text-align: left; border: 1px solid #000;"></td>
                    <td width="20%" style="text-align: left; border: 1px solid #000;"></td>
                </tr>
                <tr>
                    <td width="25%%" class="text-left p-0" style="border: 1px solid #000; vertical-align: middle; padding-left: 5px!important;"><b>Interpretation</b></td>
                    <td width="17.5%" colspan="2" class="p-0"style="text-align: left; border: 1px solid #000; vertical-align: top;"></td>
                    <td width="20%" colspan="2" style="text-align: left; border: 1px solid #000;"></td>
                </tr>
                <tr>
                    <td width="25%%" class="text-left p-0" style="border: 1px solid #000; vertical-align: middle; padding-left: 5px!important;"><b>Parent's <br>Signature</b></td>
                    <td width="17.5%" colspan="2" class="p-0"style="text-align: left; border: 1px solid #000; vertical-align: top;"></td>
                    <td width="20%" colspan="2" style="text-align: left; border: 1px solid #000;"></td>
                </tr>
            </table>
            <table width="100%" style="table-layout: fixed; font-size: 12px; padding-bottom: 3px; margin-top: 50px">
                <tr>
                   <td class="text-center"><b>INTERPRETATION OF STANDARD SCORE</b></td>
                </tr>
            </table>
            <table width="100%" class="table table-bordered" style="table-layout: fixed; font-size: 12px; padding-bottom: 3px; margin-top: 20px">
                <tr>
                   <td width="25%" class="text-center p-0" style="vertical-align: top; padding-bottom: 5px!important;"><b>STANDARD <br> SCORE</b></td>
                   <td width="75%" class="text-center p-0"><b>INTERPRETATION</b></td>
                </tr>
                <tr>
                    <td width="25%" class="text-left p-0" style="padding-left: 5px!important;">69 and below</td>
                    <td width="75%" class="text-left p-0" style="padding-left: 5px!important;">Suggest significant delay in overall development</td>
                 </tr>
                 <tr>
                    <td width="25%" class="text-left p-0" style="padding-left: 5px!important;">70-79</td>
                    <td width="75%" class="text-left p-0" style="padding-left: 5px!important;">Suggest slight delay in overall developmet</td>
                 </tr>
                 <tr>
                    <td width="25%" class="text-left p-0" style="padding-left: 5px!important;">80-119</td>
                    <td width="75%" class="text-left p-0" style="padding-left: 5px!important;">Average overall developmet</td>
                 </tr>
                 <tr>
                    <td width="25%" class="text-left p-0" style="padding-left: 5px!important;">120-129</td>
                    <td width="75%" class="text-left p-0" style="padding-left: 5px!important;">Suggest slightly advanced development</td>
                 </tr>
                 <tr>
                    <td width="25%" class="text-left p-0" style="padding-left: 5px!important;">130 and above</td>
                    <td width="75%" class="text-left p-0" style="padding-left: 5px!important;">Suggest highly advanced developmet</td>
                 </tr>
            </table>
        </td>
        <td width="33.3333333333%" style="vertical-align:top; padding-left: 5px; padding-right: 5px; border: 1px solid #000;">
            <table width="100%" style="table-layout: fixed; margin-top: 40px;">
                <tr>
                    <td width="100%" style="text-align: left; "><b>SOCIO-DEMOGRAPHIC PROFILE</b></td>
                </tr>
            </table>
            <table width="100%" style="table-layout: fixed; margin-top: 20px;">
                <tr>
                    <td width="24%" class="text-left p-0">Child's Name:</td>
                    <td width="61%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="15%" class="text-left p-0"></td>
                </tr>
            </table>
            <table width="100%" style="table-layout: fixed;">
                <tr>
                    <td width="9%" class="text-left p-0">Sex:</td>
                    <td width="13%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="23%" class="text-left p-0">Date of Birth:</td>
                    <td width="40%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="15%" class="text-left p-0"></td>
                </tr>
            </table>
            <table width="100%" style="table-layout: fixed;">
                <tr>
                    <td width="15%" class="text-left p-0">Address:</td>
                    <td width="69%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="16%" class="text-left p-0"></td>
                </tr>
            </table>
            <table width="100%" style="table-layout: fixed;">
                <tr>
                    <td width="13%" class="text-left p-0"></td>
                    <td width="78%" class="text-left p-0">Child's Handedness: ( Check Appropriate Box)</td>
                    <td width="9%" class="text-left p-0"></td>
                </tr>
            </table>
            <table width="100%" style="table-layout: fixed;">
                <tr>
                    <td width="13%" class="text-left p-0"></td>
                    <td width="78%" class="text-left p-0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( &nbsp;&nbsp;) Right &nbsp;&nbsp;( ) Left &nbsp;&nbsp; ( ) Not yet established</td>
                    <td width="9%" class="text-left p-0"></td>
                </tr>
            </table>
            <table width="100%" style="table-layout: fixed; margin-top: 20px;">
                <tr>
                    <td width="27%" class="text-left p-0">Father's Name:</td>
                    <td width="63%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="10%" class="text-left p-0"></td>
                </tr>
            </table>
            <table width="100%" style="table-layout: fixed;">
                <tr>
                    <td width="21%" class="text-left p-0">Occupation:</td>
                    <td width="71%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="8%" class="text-left p-0"></td>
                </tr>
            </table>
            <table width="100%" style="table-layout: fixed;">
                <tr>
                    <td width="40%" class="text-left p-0">Educational Attainment:</td>
                    <td width="50%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="10%" class="text-left p-0"></td>
                </tr>
            </table>
            <table width="100%" style="table-layout: fixed;">
                <tr>
                    <td width="27%" class="text-left p-0">Mother's Name:</td>
                    <td width="35%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="38%" class="text-left p-0"></td>
                </tr>
            </table>
            <table width="100%" style="table-layout: fixed;">
                <tr>
                    <td width="21%" class="text-left p-0">Occupation:</td>
                    <td width="48%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="31%" class="text-left p-0"></td>
                </tr>
            </table>
            <table width="100%" style="table-layout: fixed;">
                <tr>
                    <td width="40%" class="text-left p-0">Educational Attainment:</td>
                    <td width="45%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="15%" class="text-left p-0"></td>
                </tr>
            </table>
            <table width="100%" style="table-layout: fixed;">
                <tr>
                    <td width="90%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="10%" class="text-left p-0">&nbsp;</td>
                </tr>
            </table>
            <table width="100%" style="table-layout: fixed; margin-top: 20px;">
                <tr>
                    <td width="100%" class="text-left p-0"><b>Dear Parents</b></td>
                </tr>
            </table>
            <table width="100%" style="table-layout: fixed; margin-top: 15px;">
                <tr>
                    <td width="100%" class="p-0" style="text-align: justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This checklist shows the ability and progress your child has made in different domain,pms as well his/her progress in character development.</td>
                </tr>
                <tr>
                    <td width="100%" class="p-0" style="text-align: justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The school welcomes you if you desire to know more about the progress of your child.</td>
                </tr>
            </table>
            <table width="100%" style="table-layout: fixed; margin-top: 20px;">
                <tr>
                    <td width="55%" class="p-0"></td>
                    <td width="37%" class="text-center p-0" style="border-bottom: 1px solid #000; font-size: 13px!important;">MADONNA J. DELICANO</td>
                    <td width="8%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="55%" class="p-0"></td>
                    <td width="37%" class="text-center p-0" style="font-size: 13px!important;">Adviser</td>
                    <td width="8%" class="text-center p-0"></td>
                </tr>
            </table>
            <table width="100%" style="table-layout: fixed; margin-top: 20px;">
                <tr>
                    <td width="42%" class="text-center p-0" style="border-bottom: 1px solid #000; font-size: 13px!important;">SR. ELVIE G. BORLADO, PM</td>
                    <td width="58%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="42%" class="text-center p-0" style="font-size: 13px!important;">Director/Principal</td>
                    <td width="58%" class="text-center p-0"></td>
                </tr>
            </table>
            <table width="100%" style="table-layout: fixed; margin-top: 10px;">
                <tr>
                    <td width="100%" class="text-center p-0" style="border-bottom: 1px solid #000; font-size: 13px!important;"></td>
                </tr>
            </table>
            <table width="100%" style="table-layout: fixed; margin-top: 10px;">
                <tr>
                    <td width="33%" class="text-left p-0" style="font-size: 13px!important;">Eligible for admission to</td>
                    <td width="55%" class="text-left p-0" style="border-bottom: 1px solid #000; font-size: 13px!important;"></td>
                    <td width="12%" class="text-left p-0" style=""></td>
                </tr>
            </table>
            <table width="100%" style="table-layout: fixed; margin-top: 20px;">
                <tr>
                    <td width="60%" class="p-0"></td>
                    <td width="37%" class="text-center p-0" style="border-bottom: 1px solid #000; font-size: 13px!important;">MADONNA J. DELICANO</td>
                    <td width="3%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="60%" class="p-0"></td>
                    <td width="37%" class="text-center p-0" style="font-size: 13px!important;">Adviser</td>
                    <td width="3%" class="text-center p-0"></td>
                </tr>
            </table>
            <table width="100%" style="table-layout: fixed; margin-top: 20px;">
                <tr>
                    <td width="42%" class="text-center p-0" style="border-bottom: 1px solid #000; font-size: 13px!important;">SR. ELVIE G. BORLADO, PM</td>
                    <td width="58%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="42%" class="text-center p-0" style="font-size: 13px!important;">Director/Principal</td>
                    <td width="58%" class="text-center p-0"></td>
                </tr>
            </table>
        </td>
        <td width="33.3333333333%" style="vertical-align:top; padding-left: 5px; padding-right: 5px; border: 1px solid #000;">
            <table width="100%" style="table-layout: fixed; margin-top: 40px;">
                <tr>
                    <td width="25%" style="text-align: right; ">
                        <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="80px">
                    </td>
                    <td width="50%" style="vertical-align:top;">
                        {{-- <div class="text-center" style="margin-top: 5px;">{{$schoolinfo[0]->schoolname}}</div>
                        <div class="text-center">{{$schoolinfo[0]->address}}</div>
                        <div class="text-center">SY {{$schoolyear->sydesc}}</div> --}}
                        <div class="text-center" style="margin-top: 5px;">NOTRE DAME OF MLANG</div>
                        <div class="text-center">MLANG COTABATO</div>
                        <div class="text-center">SY {{$schoolyear->sydesc}}</div>
                    </td>
                    <td width="25%"></td>
                </tr>
            </table>
            <table width="100%" style="table-layout: fixed; font-size: 15px;">
                <tr>
                    <td class="text-center" width="100%"><b>Early Childhood Development (ECO) Checklist</b></td>
                </tr>
            </table>
            <table width="100%" style="table-layout: fixed; margin-top: 20px; font-size: 14px;">
                <tr>
                    <td width="23%" class="text-left p-0">Child's Name:</td>
                    <td width="77%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="10%" class="text-left p-0"></td>
                </tr>
            </table>
            <table width="100%" style="table-layout: fixed; font-size: 14px;">
                <tr>
                    <td width="24%" class="text-left p-0">Date of Birth:</td>
                    <td width="78%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="8%" class="text-left p-0"></td>
                </tr>
            </table>
            <table width="100%" style="table-layout: fixed; font-size: 14px;">
                <tr>
                    <td width="7%" class="text-left p-0">Sex:</td>
                    <td width="13%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="45%" class="text-left p-0"></td>
                    <td width="8%" class="text-left p-0">LRN:</td>
                    <td width="21%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="6%" class="text-left p-0"></td>
                </tr>
            </table>
            <table width="100%" class="table table-bordered" style="table-layout: fixed;  margin-top: 5px; font-size: 14px;">
                <tr>
                    <td class="text-center p-0" colspan="2"><b>FINE MOTOR DOMAIN</b></td>
                    <td width="10%" class="text-center p-0">PRE</td>
                    <td width="12%" class="text-center p-0">POST</td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">1</td>
                    <td width="69%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important; vertical-align: middle;">Uses all 5 fingers to get food/toys place on flat surface</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">2</td>
                    <td width="69%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important; vertical-align: middle;">Picks up objects with thumb and index finger</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">3</td>
                    <td width="69%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important; vertical-align: middle;">Displays a define hand preference</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">4</td>
                    <td width="69%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important; vertical-align: middle;">Puts small objects in/out of container</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">5</td>
                    <td width="69%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important; vertical-align: middle;">Holds crayon with all the fingers of his hand making a fist</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">6</td>
                    <td width="69%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important; vertical-align: middle;">Unscrews lid of container or unwraps food</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">7</td>
                    <td width="69%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important; vertical-align: middle;">Scribbles vertical ang horizontal lines</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">8</td>
                    <td width="69%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important; vertical-align: middle;">Draws circle purposely</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">9</td>
                    <td width="69%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important; vertical-align: middle;">Draw a human figure (head, eyes, trunk, arms, <br>hands/fingers)</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">10</td>
                    <td width="69%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important; vertical-align: middle;">Uses all 5 fingers to get food/toys place on flat surface</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">11</td>
                    <td width="69%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important; vertical-align: middle;">Uses all 5 fingers to get food/toys place on flat surface</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
            </table>
            <table width="100%" class="table table-bordered" style="table-layout: fixed;  margin-top: 15px; font-size: 14px;">
                <tr>
                    <td class="text-center p-0" colspan="2"><b>GROSS MOTOR DOMAIN</b></td>
                    <td width="10%" class="text-center p-0">PRE</td>
                    <td width="12%" class="text-center p-0">POST</td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">1</td>
                    <td width="69%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important; vertical-align: middle;">Climbs on chair and other elevated place or furniture like a bed without help</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">2</td>
                    <td width="69%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important; vertical-align: middle;">Walks backwards</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">3</td>
                    <td width="69%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important; vertical-align: middle;">Runs without tripping or falling</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">4</td>
                    <td width="69%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important; vertical-align: middle;">Walks downstairs, 2 feet on each step, with one hand held</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">5</td>
                    <td width="69%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important; vertical-align: middle;">Walks upstairs holding handrail, 2 feet, on each step</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">6</td>
                    <td width="69%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important; vertical-align: middle;">Walks upstairs with alternate feet w/o holding handrail</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">7</td>
                    <td width="69%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important; vertical-align: middle;">Walks upstairs with alternate feet with holding handrail</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">8</td>
                    <td width="69%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important; vertical-align: middle;">Moves body part as directed</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">9</td>
                    <td width="69%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important; vertical-align: middle;">Jumps up</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">10</td>
                    <td width="69%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important; vertical-align: middle;">Throws ball overhead with direction</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">11</td>
                    <td width="69%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important; vertical-align: middle;">Hops 1 to 3 steps on preferred foot</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">12</td>
                    <td width="69%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important; vertical-align: middle;">Jumps and turns</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">13</td>
                    <td width="69%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important; vertical-align: middle;">Dance patterns/ join group movements activities</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;"></td>
                    <td width="69%" class="text-right p-0" style="font-size: 11px; padding-right: 8px!important; vertical-align: middle;"><b>Total Score</b></td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed;">
    <tr>
        <td width="33.3333333333%" style="vertical-align:top; padding-left: 5px; padding-right: 5px; border: 1px solid #000;">
            <table width="100%" class="table table-bordered" style="table-layout: fixed;  margin-top: 5px; font-size: 14px;">
                <tr>
                    <td width="9%" class="text-center p-0"></td>
                    <td width="69%" class="text-center p-0"><b>SELF-HELP DOMAIN</b></td>
                    <td width="10%" class="text-center p-0">PRE</td>
                    <td width="12%" class="text-center p-0">POST</td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">1.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Feeds self with finger food (e.g biscuits, bread) using fingers</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">2.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Feeds self using finger with spillage</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">3.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Feeds self using spoon with spillage</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">4.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Feeds self using finger without spillage</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">5.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Feeds self using spoon without spillage</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">6.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Eat without need of spoon feeding during any meal</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">7.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Helps hold cup for drinking</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">8.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Drinks from cup with spilage</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">9.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Drinks from cup unassisted</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">10.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Gets drinks for self unassisted</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">11.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Pours from pitcher without spillage</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">12.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Prepares own food/snack</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">13.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Prepares meals for younger siblings/family members when no adult is around</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">14.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Participates when being dressed (e.g. raises arms or lifts leg)</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">15.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Pulls down gartered short pants</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">16.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Removes sando</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">17.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Dresses without assistance except for buttons and tying</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">18.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Dresses without assistance including buttons and tying</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">19.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Informs the adult only after he
                        has already urinated or
                        moved his bowels in his
                        underpants</td>
                    <td width="10%" class="text-center p-0" style="font-size: 11px;"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">20.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Informs adult of need to urinate
                        or move bowels so he can be brought to a
                        designated place</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">21.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Goes to the designated place to
                        urinate or move bowels
                        but sometimes still does
                        this in his underpants anymore</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">22.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Wipes/Cleans self after a bowel movement</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0"></td>
                    <td width="69%" class="text-center p-0" style=""><b>Bathing sub-domain</b></td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">24.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Participates when bathing (e.g. rubbing arms with soap)</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">25.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Washes and dries hands without any help</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">26.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Washes face without any help</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">27.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Bathes without any help</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">24.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Participates when bathing (e.g. rubbing arms with soap)</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;"></td>
                    <td width="69%" class="text-right p-0" style="padding-right: 5px!important; font-size: 11px;"><b>Total Score</b></td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;"></td>
                    <td width="69%" class="text-right p-0" style="padding-right: 5px!important; font-size: 11px;">&nbsp;</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0"></td>
                    <td width="69%" class="text-center p-0" style=""><b>RECEPTIVE LANGUAGE DOMAIN</b></td>
                    <td width="10%" class="text-center p-0">PRE</td>
                    <td width="12%" class="text-center p-0">POST</td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">1</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Points to family member when asked to do so</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">2</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Points to 5 body parts on himself when asked to do so</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">3</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Points to 5 named pictured objects when asked to do so</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">4</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Follows one-step instructions that
                        include simple prepositions (e.g., in,
                        on, under, etc.) 
                        </td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">5</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Follows 2-step instructions that
                        include simple prepositions</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;"></td>
                    <td width="69%" class="text-right p-0" style="padding-right: 5px!important; font-size: 11px;"><b>Total Score</b></td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
            </table>
        </td>
        <td width="33.3333333333%" style="vertical-align:top; padding-left: 5px; padding-right: 5px; border: 1px solid #000;">
            <table width="100%" class="table table-bordered" style="table-layout: fixed;  margin-top: 5px; font-size: 14px;">
                <tr>
                    <td width="9%" class="text-center p-0"></td>
                    <td width="69%" class="text-left p-0" style="">EXPRESSIVE LANGUAGE DOMAIN</td>
                    <td width="10%" class="text-center p-0">PRE</td>
                    <td width="12%" class="text-center p-0">POST</td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">1.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Uses 5-20 recognizable words</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">2.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Uses pronouns (e.g. I, me, ako, akin)</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">3.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Uses 2-3 words verb-noun
                        combinations (e.g. hingi gatas)</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">4.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Names objects in pictures</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">5.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Speaks in grammatically correct 2-
                        3 word sentences</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">6.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Asks “what” questions</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">7.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Asks “who”' and “why” questions</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">8.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Gives account of recent
                        experiences (with prompting) in
                        order of occurrence using past
                        tense</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;"></td>
                    <td width="69%" class="text-right p-0" style="padding-right: 5px!important; font-size: 11px;"><b>Total Score</b></td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;"></td>
                    <td width="69%" class="text-right p-0" style="padding-right: 5px!important; font-size: 11px;">&nbsp;</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0"></td>
                    <td width="69%" class="text-left p-0" style=""><b>COGNITIVE LANGUAGE</b></td>
                    <td width="10%" class="text-center p-0">PRE</td>
                    <td width="12%" class="text-center p-0">POST</td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">1.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Looks at direction of fallen object</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">2.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Looks form partially hidden objects</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">3.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Imitates behavior just seen a few
                        minutes earlier</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">4.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Offers object but will not release it</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">5.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Looks for completely hidden objects</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">6.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Exhibits simple pretend play (feed,
                        put doll To sleep)</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">7.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Matches objects</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">8.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Matches 2 – 3 colors</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">9.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Match pictures</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">10.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Sorts objects based on shapes</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">11.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Sorts objects based on 2 attributes
                        (e.g., size and color)</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">12.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Arranges objects according to size
                        from smallest to biggest</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">13.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Names 4 – 6 colors</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">14.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Copies shapes</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">15.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Names 3 animals or vegetables
                        when asked</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">16.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">States what common household
                        items are used for</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">17.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Can assemble simple puzzles</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">18.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Demonstrates an understanding of
                        opposites by completing a
                        statement (e.g., Ang aso ay
                        malaki, ang daga ay _____”)</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">19.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Points to left and right sides of
                        body</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">20.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Can state what is silly or wrong
                        with pictures</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">21.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Matches upper and lower case
                        letters</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;"></td>
                    <td width="69%" class="text-right p-0" style="padding-right: 5px!important; font-size: 11px;"><b>Total Score</b></td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;"></td>
                    <td width="69%" class="text-right p-0" style="padding-right: 5px!important; font-size: 11px;">&nbsp;</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0"></td>
                    <td width="69%" class="text-left p-0" style=""><b>SOCIAL-EMOTIONAL DOMAIN</b></td>
                    <td width="10%" class="text-center p-0">PRE</td>
                    <td width="12%" class="text-center p-0">POST</td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">1.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Enjoys watching activities of
                        nearby people or animals</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">2.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Friendly with strangers but initially
                        may show slight anxiety or shyness</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">3.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Plays alone but likes to be near
                        familiar adults or brothers and
                        sisters</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">4.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Laughs or squeals aloud in play</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">5.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Plays peek-a-boo (bulaga)</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
            </table>
        </td>
        <td width="33.3333333333%" style="vertical-align:top; padding-left: 5px; padding-right: 5px; border: 1px solid #000;">
            <table width="100%" class="table table-bordered" style="table-layout: fixed;  margin-top: 5px; font-size: 14px;">
                <tr>
                    <td width="9%" class="text-center p-0"></td>
                    <td width="69%" class="text-left p-0" style=""></td>
                    <td width="10%" class="text-center p-0">PRE</td>
                    <td width="12%" class="text-center p-0">POST</td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">6.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Rolls ball interactively with
                        caregiver/examiner</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">7.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Hugs or cuddles toys</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">8.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Demonstrates respect for elders using terms like “po” and “opo”</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">9.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Shares toys with others</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">10.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Imitates adult activities (e.g.,
                        cooking, washing)</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">11.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Identifies feelings in others</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">12.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Appropriately uses cultural
                        gestures of greeting without much
                        prompting (e.g., mano, bless, kiss,
                        etc.)</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">13.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Comforts playmates/siblings in
                        distress</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">14.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Persists when faced with a
                        problem or obstacle to his wants</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">15.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Helps with family chores (e.g.,
                        wiping tables, watering plants, etc.)</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">16.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Curious about environment but
                        knows when to stop asking
                        questions of adults</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">17.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Waits for turn</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">18.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Asks permission to play with toy
                        being used by another</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">19.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Defends possessions with
                        determination</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">20.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Plays organized group games fairly</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">21.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Can talk about difficult feelings
                        (e.g., anger, sadness, worry) he
                        experiences.</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">22.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Honors a simple bargain with
                        caregiver (e.g., can play outside
                        only after cleaning / finishing his
                        room)</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">23.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Watches responsibly over younger
                        siblings/family members</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">24.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Cooperates with adults and peers
                        in group situations to minimize
                        quarrels and conflicts</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;"></td>
                    <td width="69%" class="text-right p-0" style="padding-right: 5px!important; font-size: 11px;"><b>Total Score</b></td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;"></td>
                    <td width="69%" class="text-right p-0" style="padding-right: 5px!important; font-size: 11px;">&nbsp;</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;"></td>
                    <td width="69%" class="text-right p-0" style="padding-right: 5px!important; font-size: 11px;">&nbsp;</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;"></td>
                    <td width="69%" class="text-right p-0" style="padding-right: 5px!important; font-size: 11px;">&nbsp;</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0"></td>
                    <td width="69%" class="text-center p-0" style=""><b>CHRISTIAN LIVING EDUCATION</b></td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">1.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Makes the sign of the cross correctly</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">2.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Recites the basic prayers</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">3.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Engages in spiritual activities</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">4.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Listens to the word of God attentively</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 11px;">5.</td>
                    <td width="69%" class="text-left p-0" style="padding-left: 5px!important; font-size: 11px;">Shows willingness to help</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="12%" class="text-center p-0"></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>