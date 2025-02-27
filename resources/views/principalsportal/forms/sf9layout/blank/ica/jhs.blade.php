<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            font-size: .7rem !important;
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
		 .check_mark {
               font-family: ZapfDingbats, sans-serif;
            }
        @page { size: 11in 8.5in; margin: .5in .40in;}
    </style>
</head>
<body>
<table style="width: 100%; table-layout: fixed;">
    <tr>
        <td width="50%" class="p-0" style="vertical-align: top; font-size: 13px; padding-right: .40in!important;">
            <table style="width: 100%;">
                <tr>
                    <td class="text-center"><b>ATTENDANCE RECORD</b></td>
                </tr>
            </table>
            <table style="width: 100%; margin-top: 5px;">
                <tr>
                    <td  width="100%" class="p-0">
                        <table width="100%" class="table table-bordered table-sm grades mb-0" style="table-layout: fixed;">
                            <tr>
                                <td width="20%"></td>
                                <td width="8%">Aug</td>
                                <td width="8%">Sep</td>
                                <td width="8%">Oct</td>
                                <td width="8%">Nov</td>
                                <td width="8%">Dec</td>
                                <td width="8%">Jan</td>
                                <td width="8%">Feb</td>
                                <td width="8%">Mar</td>
                                <td width="8%">Apr</td>
                                <td width="8%">Total</td>
                            </tr>
                            <tr class="table-bordered">
                                <td>No. of school days</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr class="table-bordered">
                                <td>No. of days present</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr class="table-bordered">
                                <td>No. of days absent</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <table width="100%" style="font-size: 13px; margin-top: 50px;">
                <tr>
                    <td width="100%" class="p-0 text-center"><b>PARENT/GUARDIAN'S SIGNATURE</b></td>
                </tr>
            </table>
            <table width="100%" style="font-size: 14px; margin-top: 15px;">
                <tr>
                    <td width="24%"class="p-0"></td>
                    <td width="20%" class="p-0 text-left">1<sup style="font-size: 11px;">st</sup> Quarter</td>
                    <td width="30%" class="p-0 text-center" style="border-bottom: 1px solid #000"></td>
                    <td width="26%" class="p-0"></td>
                </tr>
            </table>
            <table width="100%" style="font-size: 14px; margin-top: 15px;">
                <tr>
                    <td width="24%"class="p-0"></td>
                    <td width="20%" class="p-0 text-left">2<sup style="font-size: 11px;">nd</sup> Quarter</td>
                    <td width="30%" class="p-0 text-center" style="border-bottom: 1px solid #000"></td>
                    <td width="26%" class="p-0"></td>
                </tr>
            </table>
            <table width="100%" style="font-size: 14px; margin-top: 15px;">
                <tr>
                    <td width="24%"class="p-0"></td>
                    <td width="20%" class="p-0 text-left">3<sup style="font-size: 11px;">rd</sup> Quarter</td>
                    <td width="30%" class="p-0 text-center" style="border-bottom: 1px solid #000"></td>
                    <td width="26%" class="p-0"></td>
                </tr>
            </table>
            <table width="100%" style="font-size: 14px; margin-top: 15px;">
                <tr>
                    <td width="24%"class="p-0"></td>
                    <td width="20%" class="p-0 text-left">4<sup style="font-size: 11px;">th</sup> Quarter</td>
                    <td width="30%" class="p-0 text-center" style="border-bottom: 1px solid #000"></td>
                    <td width="26%" class="p-0"></td>
                </tr>
            </table>
            <table width="100%" style="text-center; margin-top: 50px;">
                <tr>
                    <td class="text-center" style="font-size: 13px;"><b>Certificate of Transfer</b></td>
                </tr>
            </table>
            <table width="100%" style="font-size: 13px; margin-top: 25px;">
                <tr>
                    <td width="27%" class="p-0">Admitted to Grade:</td>
                    <td width="35%" class="p-0 text-center" style="border-bottom: 1px solid #000"></td>
                    <td width="12%" class="p-0 text-center">Section:</td>
                    <td width="26%" class="p-0 text-center" style="border-bottom: 1px solid #000"><b></b></td>
                </tr>
            </table>
            <table width="100%" style="font-size: 13px;">
                <tr>
                    <td width="45%" class="p-0 text-left">Eligibility for Admission to Grade:</td>
                    <td width="55%" class="p-0 text-center" style="border-bottom: 1px solid #000"></td>
                </tr>
            </table>
            <table width="100%" style="font-size: 13px; margin-top: 15px;">
                <tr>
                    <td width="100%" class="p-0 text-left">Approved:</td>
                </tr>
            </table>
            <table width="100%" style="margin-top: 15px;">
                <tr>
                    <td class="p-0 text-center" width="45%" style="border-bottom: 1px solid #000; font-size: 13px;"><b>Principal Name</b></td>
                    <td class="p-0" width="10%" style=""></td>
                    <td class="p-0 text-center" width="45%" style="border-bottom: 1px solid #000; font-size: 13px;"><b>Adviser Name</b></td>
                </tr>
                <tr>
                    <td class="p-0" width="45 %" style="text-align: center; font-size: 13px;">Principal</td>
                    <td class="p-0" width="10%" style=""></td>
                    <td class="p-0" width="45%" style="text-align: center; font-size: 13px;">Teacher</td>
                </tr>
            </table>
            <table width="100%" style="text-center; margin-top: 30px;">
                <tr>
                    <td class="text-center" style="font-size: 13px;"><b>Cancellation of Eligibility to Transfer</b></td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 13px;">
                <tr>
                    <td width="17%" class="p-0">Admitted in:</td>
                    <td width="24%" class="p-0 text-center" style="border-bottom: 1px solid #000"></td>
                    <td width="59%" class="p-0"></td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 13px;">
                <tr>
                    <td width="9%" class="p-0">Date:</td>
                    <td width="33%" class="p-0 text-center" style="border-bottom: 1px solid #000"></td>
                    <td width="15%" class="p-0"></td>
                    <td width="43%" class="p-0 text-center" style="border-bottom: 1px solid #000; font-size: 12px;"><b>Principal Name</b></td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 13px;">
                <tr>
                    <td width="15%" class="p-0"></td>
                    <td width="27%" class="p-0"></td>
                    <td width="15%" class="p-0"></td>
                    <td width="43%" class="text-center p-0">Principal</td>
                </tr>
            </table>
        </td>
        <td width="50%" class="p-0" style="vertical-align: top; padding-left: .40in!important;">
            <table class="table mb-0 mt-0" style="width: 100%; table-layout: fixed;">
                <tr>
                    <td width="25%" class="p-0 text-center" style="font-size: 10px;"><b>REPORT CARD (SF9)</b></td>
                    <td width="50%" class="p-0"></td>
                    <td width="25%" class="p-0"></td>
                </tr>
            </table>
            <table width="100%" class="table mt-0 mb-0" style="table-layout: fixed;">
                <tr>
                    <td width="25%" style="text-align: center;">
                        <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="70px">
                    </td>
                    <td width="50%" style="text-align: center; font-size: 15px;">
                        <div>Republic of the Philippines</div>
                        <div>Department of Education</div>
                        <div>Region I</div>
                        <div>DIVISION OF SCHOOL CITY</div>
                        <div>District 1</div>
                    </td>
                    <td width="25%" class="text-center">
                        <div><img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="70px"></div>
                        <div style="padding-top: 5px;"><img src="{{base_path()}}/public/assets/images/ica/deped.png" alt="school" width="60px" height="30px"></div>
                    </td>
                </tr>
            </table>
            <table width="100%" class="table mt-0" style="table-layout: fixed;">
                <tr>
                    <td class="text-center p-0" style="font-size: 15px;"><b>{{$schoolinfo[0]->schoolname}}</b></td>
                </tr>
                <tr>
                    <td class="text-center p-0" style="font-size: 12px;">{{$schoolinfo[0]->address}}</td>
                </tr>
            </table>
            <table width="100%" class="table mt-0" style="table-layout: fixed; margin-top: 25px;">
                <tr>
                    <td class="text-center p-0" style="font-size: 15px;"><b>LEARNER'S PROGRESS REPORT CARD</b></td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 13px; margin-top: 35px;" >
                <tr>
                    <td width="12%" class="p-0">Name:</td>
                    <td width="88%" class="p-0 text-left" style=""><b><u>JUAN DELA CRUZ</u></b></td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 13px; margin-top: 5px;">
                <tr>
                    <td width="12%" class="p-0">LRN:</td>
                    <td width="88%" class="p-0 text-left" style=""><b><u>00123456789</u></b></td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 13px; margin-top: 5px;">
                <tr>
                    <td width="12%" class="p-0">Age:</td>
                    <td width="38%" class="p-0 text-left" style=""><b><u>11</u></b></td>
                    <td width="15%" class="p-0">Sex:</td>
                    <td width="35%" class="p-0 text-left" style=""><b><u>MALE</u></b></td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 13px; margin-top: 5px;">
                <tr>
                    <td width="12%" class="p-0">Grade:</td>
                    <td width="18%" class="p-0 text-left" style=""><b><u>7</u></b></td>
                    <td width="20%" class="p-0"></td>
                    <td width="15%" class="p-0">Section:</td>
                    <td width="35%" class="p-0 text-left" style=""><b><u>MAKAPAGAL</u></b></td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 13px; margin-top: 5px;">
                <tr>
                    <td width="19%" class="p-0">School Year:</td>
                    <td width="81%" class="p-0 text-left" style=""><b><u>2022-2023</u></b></td>
                </tr>
            </table>
            
            <br>
            <table style="font-size: 13px; margin-top: 50px;">
                <tr>
                    <td width="15%" class="p-0">Dear Parent:</td>
                </tr>
            </table>
            <table style="font-size: 13px; margin-top: 20px;">
                <tr>
                    <td width="100%" class="p-0" style="text-indent: 50px;">This report card shows the ability and progress your child has made in the different learning areas well as his/her core values.</td>
                </tr>
            </table>
            <table style="font-size: 13px; margin-top: 20px;">
                <tr>
                    <td width="100%" class="p-0" style="text-indent: 50px;">The school welcomes you should you desire to know more about your child's progress.</td>
                </tr>
            </table>
            <br>

            <table style="width: 100%; padding-top: 45px; text-align: center;">
                <tr>
                    <td class="p-0" width="45%" style="font-size: 12px;"><b><u>Principal Name</u></b></td>
                    <td class="p-0" width="10%" style=""></td>
                    <td class="p-0" width="45%" style="font-size: 12px;"><b><u>Adviser Name</u></b></td>
                </tr>
                <tr>
                    <td class="p-0" width="45%" style="text-align: center; font-size: 12px;">Principal</td>
                    <td class="p-0" width="10%" style=""></td>
                    <td class="p-0" width="45%" style="text-align: center; font-size: 12px;">Adviser</td>
                </tr>
            </table>
        </td>
    </tr>
    </table>
    <table style="width: 100%; table-layout: fixed;">
    <tr>
        <td width="50%" class="p-0" style="height; 100px; vertical-align: top; padding-right: .40in!important;">
            <table width="100%" class="table table-sm" style="font-size: 12px;">
                <tr>
                    <td width="100%" class="p-0">
                        <table class="table-sm" width="100%">
                            <tr>
                                <td colspan="6" class="align-middle text-center" style="font-size: 13px!important;"><b>REPORTS ON LEARNER'S OBSERVED VALUES</b></td>
                            </tr>
                        </table>
                        <table class="table-sm table table-bordered" width="100%" style="margin-top: 10px;">
                            <tr>
                                <td rowspan="2" class="align-middle text-center" style="font-size: 12px;"><b>Core Values</b></td>
                                <td rowspan="2" class="align-middle text-center" style="font-size: 12px;"><b>Behavior Statements</b></td>
                                <td colspan="5" class="cellRight" style="font-size: 12px;"><center><b>QUARTER</b></center></td>
                            </tr>
                            <tr>
                                <td width="7.3%"><center><b>1</b></center></td>
                                <td width="7.3%"><center><b>2</b></center></td>
                                <td width="7.3%"><center><b>3</b></center></td>
                                <td width="7.3%"><center><b>4</b></center></td>
                                <td width="7.3%"><center><b>FR</b></center></td>
                            </tr>
                            <tr>
                                <td rowspan="2" width="23.5%" class="text-center align-middle">1. Maka-Diyos</td>
                                <td width="40%">Expresses one's spiritual beliefs while respecting the spiritual beliefs of others</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Shows adherence to ethical principles by upholding truth</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td rowspan="2" width="23.5%" class="text-center align-middle">2. Makatao</td>
                                <td width="40%">is sensitive to individual, social and cultural difference</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Demonstrates contributions toward solidarity</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td width="23.5%" class="text-center align-middle">3. Makakalikasan</td>
                                <td width="40%">Cares for the environment and utilizes resources wisely, judiciously and economically</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td rowspan="2" width="23.5%" class="text-center align-middle">4. Makabansa</td>
                                <td width="40%">Demonstrates pride in being a Filipino; exercises the rights and responsibilities of a Filipino citizen</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Demonstrates appropriate behaviour in carrying out activities in the school, community and country</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                        <table width="100%" class="table-bordered table-sm mb-0 mt-0 tex" style="padding: 50px 95px 0px 95px">
                            <tr>
                                <td width="35%" class="text-center" style="font-size: 12px!important;"><b>MARKING</b></td>
                                <td width="65%" class="" style="font-size: 12px!important;"><b>NON-NUMERICAL RATING</b></td>
                            </tr>
                            <tr>
                                <td width="35%" class="text-center">AO</td>
                                <td width="65%" class="">Always Observed</td>
                            </tr>
                            <tr>
                                <td width="35%" class="text-center">SO</td>
                                <td width="30%" class="">Sometimes Observed</td>
                            </tr>
                            <tr>
                                <td width="35%" class="text-center">RO</td>
                                <td width="65%" class="">Rarely Observed</td>
                            </tr>
                            <tr>
                                <td width="35%" class="text-center">NO</td>
                                <td width="65%" class="">Not Observed</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
        <td width="50%" class="p-0" style="height; 100px; vertical-align: top; padding-left: .40in!important;">
            <table width="100%" class="table table-sm" style="font-size: 12px!important;">
                <tr>
                    <td width="100%" class="p-0">
                    <table class="" width="100%" style="font-size: 13px!important;">
                        <tr>
                            <td class="text-center"><b>REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</b></td>
                        </tr>
                    </table>
                    <table width="100%" class="table table-sm table-bordered grades" style="table-layout: fixed; margin-top: 10px;">
                        <thead>
                            <tr>
                                <td rowspan="2"  class="align-middle text-center" width="47%" style="font-size: 11px!important;"><b>LEARNING AREAS</b></td>
                                <td colspan="4"  class="text-center align-middle" style="font-size: 11px!important;"><b>QUARTER</b></td>
                                <td rowspan="2" class="text-center align-middle" width="11%" style="font-size: 11px!important;"><b>FINAL <br> RATING</b></td>
                                <td rowspan="2"  class="text-center align-middle" width="14%" style="font-size: 11px!important;"><b>REMARKS</b></span></td>
                            </tr>
                            <tr>
                                <td class="text-center align-middle" width="7%"><b>1</b></td>
                                <td class="text-center align-middle" width="7%"><b>2</b></td>
                                <td class="text-center align-middle" width="7%"><b>3</b></td>
                                <td class="text-center align-middle" width="7%"><b>4</b></td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="font-size: 11px!important; padding: 5px 0px 5px 4px !important"><b>Mother Tongue</b></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td style="font-size: 11px!important; padding: 5px 0px 5px 4px !important"><b>Filipino</b></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td style="font-size: 11px!important; padding: 5px 0px 5px 4px !important"><b>English</b></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td style="font-size: 11px!important; padding: 5px 0px 5px 4px !important"><b>Mathematics</b></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td style="font-size: 11px!important; padding: 5px 0px 5px 4px !important"><b>Araling Panlipunan</b></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td style="font-size: 11px!important; padding: 5px 0px 5px 4px !important"><b>MAPEH</b></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td style="font-size: 11px!important; padding: 5px 0px 5px 15px !important"><b>Music</b></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td style="font-size: 11px!important; padding: 5px 0px 5px 15px !important"><b>Arts</b></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td style="font-size: 11px!important; padding: 5px 0px 5px 15px !important"><b>Physical Education</b></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td style="font-size: 11px!important; padding: 5px 0px 5px 15px !important"><b>Health</b></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td style="font-size: 11px!important; padding: 5px 0px 5px 4px !important"><b>ESP</b></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td style="font-size: 11px!important; padding: 5px 0px 5px 4px !important"><b>Robotics</b></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td style="font-size: 11px!important; padding: 5px 0px 5px 4px !important"><b>CLE</b></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="text-right" style="font-size: 12px!important;"><b>General Average</b></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table-bordered mb-0 mt-0" width="100%" style="table-layout: fixed; font-size: 13px; margin: 60px 20px 0px 20px;">
                        <tr>
                            <td width="38%" class="text-center" style="font-size: 13px!important"><b>DESCRIPTORS</b></td>
                            <td width="32%" class="text-center" style="font-size: 13x!important"><b>GRADING SCALE</b></td>
                            <td width="30%" class="text-center" style="font-size: 13px!important"><b>REMARKS</b></td>
                        </tr>
                        <tr>
                            <td class="">Outstanding</td>
                            <td class="text-center">90 - 100</td>
                            <td class="text-center">Passed</td>
                        </tr>
                        <tr>
                            <td class="">Very Satisfactory</td>
                            <td class="text-center">85 - 89</td>
                            <td class="text-center">Passed</td>
                        </tr>
                        <tr>
                            <td class="">Satisfactory</td>
                            <td class="text-center">80 - 84</td>
                            <td class="text-center">Passed</td>
                        </tr>
                        <tr>
                            <td class="">Fairly Satisfactory</td>
                            <td class="text-center">75 - 79</td>
                            <td class="text-center">Passed</td>
                        </tr>
                        <tr>
                            <td class="">Did Not Meet Expectations</td>
                            <td class="text-center">Below 75</td>
                            <td class="text-center">Failed</td>
                        </tr>
                    </table>
                    </td>
                </tr>    
            </table>
        </td>
    </tr>
</table>
</body>
</html>