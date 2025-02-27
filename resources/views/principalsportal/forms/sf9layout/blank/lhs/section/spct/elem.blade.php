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
            font-size: 9px !important;
        }

        .studentinfo td{
            padding-top: .1rem;
            padding-bottom: .1rem;
          
        }

        .bg-red{
            color: red;
            border: solid 1px black !important;
        }

        @page {  
            margin:30px 50px;
            
        }
        body { 
            /* margin:0px 10px; */
            
        }

        /* @page { size: 5.5in 8.5in; margin: 10px 40px;  } */
        
    </style>
</head>
<body>  

    <table style="width: 100%; table-layout: fixed;">
        <tr>
            <td style="text-align: right; vertical-align: top;">
                <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="100px">
            </td>
            <td style="width: 50%; text-align: center;">
                <div style="width: 100%; font-weight: bold; font-size: 18px;">ST. PETER'S COLLEGE OF TORIL, INC.</div>
                <div style="width: 100%; font-weight: bold; font-size: 11px;">Mac Arthur Highway, Crossing Bayabas, Toril, Davao City</div>
                <div style="width: 100%; font-weight: bold; font-size: 13px;">Elementary Department</div>
                <div style="width: 100%; font-size: 12px;">(Government Recognition No. 275, s. 1965)</div>
                <div style="width: 100%; font-weight: bold; font-size: 12px;">SCHOOL ID - 405492</div>
                <div style="width: 100%; font-weight: bold; font-size: 12px;">(PAASCU ACCREDITED LEVEL 2)</div>
                <div style="width: 100%; font-weight: bold; font-size: 13px; line-height: 5px;">&nbsp;</div>
                <div style="width: 100%; font-weight: bold; font-size: 13px;">REPORT CARD (SF 9)</div>
                <div style="width: 100%; font-weight: bold; font-size: 13px;">School Year</div>
            </td>
            <td></td>
        </tr>
    </table>
    <br/>
    <table style="width: 100%; font-size: 12px; text-align: left !important;">
        <tr>
            <th colspan="2" style="width: 15%;">Student Name:</th>
            <td colspan="3"></td>
            <th colspan="2" style="width: 15%;">Grade & Section:</th>
            <td></td>
        </tr>
        <tr>
            <td colspan="8">&nbsp;</td>
        </tr>
        <tr>
            <th>Gender:</th>
            <td></td>
            <th style="width: 5%;">LRN:</th>
            <td colspan="2"></td>
            <th>Adviser:</th>
            <td colspan="2"></td>
        </tr>
    </table>
    <table style="width: 100%; margin: 20px 40PX; font-size: 12px;" border="1">
        <thead>
            <tr>
                <th rowspan="2" style="width: 45%;">ACADEMIC ACHIEVEMENT<br/>LEARNING AREAS</th>
                <th colspan="4">Quarter</th>
                <th rowspan="2">AVERAGE</th>
                <th rowspan="2">REMARK</th>
            </tr>
            <tr>
                <th>1</th>
                <th>2</th>
                <th>3</th>
                <th>4</th>
            </tr>
        </thead>
        <tr>
            <td>&nbsp;</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <th>GENERAL AVERAGE</th>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>
    <table style="width: 100%; margin: 20px 60px; font-size: 12px;" border="1">
        <thead>
            <tr>
                <th rowspan="2" style="width: 45%;">REPORT ON STUDENT'S OBSERVED VALUES</th>
                <th colspan="4">Quarter</th>
            </tr>
            <tr>
                <th>1</th>
                <th>2</th>
                <th>3</th>
                <th>4</th>
            </tr>
        </thead>
        <tr>
            <td>Christ-Centeredness</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Competence</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Civic-Mindedness</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Community Service</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Compassion</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>
    
    <div style="margin: 0px 40px; font-size: 12px; font-weight: bold;">REPORT ON ATTENDANCE</div>
    <table style="width: 100%; margin: 0px 40px; font-size: 12px;" border="1">
        <thead>
            <tr>
                <th style="width: 25%;">Attendance Record</th>
                <th>Jun</th>
                <th>Jul</th>
                <th>Aug</th>
                <th>Sept</th>
                <th>Oct</th>
                <th>Nov</th>
                <th>Dec</th>
                <th>Jan</th>
                <th>Feb</th>
                <th>Mar</th>
                <th>Apr</th>
                <th>Total</th>
            </tr>
        </thead>
        <tr>
            <td>No. of School Days</td>
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
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>No. of Days Present</td>
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
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>No. of Days Absent</td>
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
            <td></td>
            <td></td>
        </tr>
    </table>
    <br/>
    <table style="width: 100%; font-size: 12px;">
        <tr style=" font-weight: bold;">
            <td style="width: 60%;">Academic Rating</td>
            <td style="width: 2%;"></td>
            <td>Observed Values</td>
        </tr>
        <tr>
            <td style="border: 1px solid black; vertical-align: top;">
                <table style="width: 100%;">
    <tr style=" font-style: italic;">
        <th style="text-align: left !mportant; width: 40%;">Descriptors</th>
        <th>Grading Scale</th>
        <th>Remark</th>
    </tr>
    <tr>
        <td>Outstanding</td>
        <td style="text-align: center;">90-100</td>
        <td style="text-align: center;">Passed</td>
    </tr>
    <tr>
        <td>Very Satisfactory</td>
        <td style="text-align: center;">85-89</td>
        <td style="text-align: center;">Passed</td>
    </tr>
    <tr>
        <td>Satisfactory</td>
        <td style="text-align: center;">80-84</td>
        <td style="text-align: center;">Passed</td>
    </tr>
    <tr>
        <td>Fairly Satisfactory</td>
        <td style="text-align: center;">75-79</td>
        <td style="text-align: center;">Passed</td>
    </tr>
    <tr>
        <td>Did Not Meet Expectations</td>
        <td style="text-align: center;">Beloww 75</td>
        <td style="text-align: center;">Failed</td>
    </tr>
                </table>
            </td>
            <td></td>
            <td style="border: 1px solid black; vertical-align: top;">
                <table style="width: 100%;">
                    <tr style=" font-style: italic;">
                        <th style="width: 40%;">Marking</th>
                        <th>Non-Numerical Ratings</th>
                    </tr>
                    <tr>
                        <td style="text-align: center;">AO</td>
                        <td style="text-align: center;">Always Observed</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">SO</td>
                        <td style="text-align: center;">Sometimes Observed</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">RO</td>
                        <td style="text-align: center;">Rarely Observed</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">NO</td>
                        <td style="text-align: center;">Not Observed</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table style="width: 100%; margin: 20px 120px; font-size: 12px;">
        <tr>
            <td style="width: 45%;">Eligible for transfer and admission to</td>
            <td style="border-bottom: 1px solid black;"></td>
        </tr>
    </table>
    <table style="width: 100%; margin: 40px 120px; font-size: 12px;">
        <tr>
            <td style="width: 45%; border-bottom: 1px solid black; text-align: center;">{{$principal}}</td>
            <td style="width: 5%;"></td>
            <td style="border-bottom: 1px solid black;"></td>
        </tr>
        <tr>
            <td style="text-align: center;">Principal</td>
            <td></td>
            <td style="text-align: center;">Date</td>
        </tr>
    </table>
</body>
</html>