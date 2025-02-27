<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <title>{{$student->firstname.' '.$student->middlename[0].' '.$student->lastname}}</title> --}}
    <style>

        .table {
            /* width: 100%; */
            /* margin-bottom: 1rem; */
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
        .trhead {
            background-color: #48b4e0; 
            color: #fff; font-size;
        }
       
        body {
            /* background-image: url({{url('assets/images/sjaes/sjaes.png')}}); */
            background-size: cover;
        }

      

        @page { size: 297mm 210mm; 
                /* margin: 10px 15px; */
                margin: 0px 0px;
            }
            #watermark1 {
        opacity: 1;
                position: absolute;
                /* bottom:   0px; */
                /* left:     -70px; */
                /** The width and height may change 
                    according to the dimensions of your letterhead
                **/
                width:    25cm;
                height:   18.5cm;

                /** Your watermark should be behind every content**/
                z-index:  -2000;
            }
    </style>
</head>
<body>  
	@php
		$acadtext = '';
		if($student->acadprogid == 3){
			$acadtext = 'GRADE SCHOOL';
		}else if($student->acadprogid == 4){
			$acadtext = 'Junior High School';
		} else if($student->acadprogid == 5){
            $acadtext = 'Senior High School';
        }
	@endphp
    <div id="watermark1" style="padding-top: 100px;">
        <img src="{{base_path()}}/public/assets/images/sjaes/sjaes3.png" height="100%" width="100%" />
    </div>
    <table width="100%"  style="table-layout: fixed;margin-right: 10px;">
        <tr>
            <td width="20%" class="p-0"></td>
            <td width="45%" class="p-0"></td>
            <td width="35%" class="text-right p-0" style="font-size: 30px;">Katunayan ng Pagkumpleto</td>
        </tr>
        <tr>
            <td width="20%" class="p-0"></td>
            <td width="45%" class="p-0"></td>
            <td width="35%" class="text-right p-0" style="font-size: 30px; border-bottom: 3px solid #000"><i>Certificate of Completion</i></td>
        </tr>
    </table>
    <table width="100%"  style="table-layout: fixed; margin-top: 3px; margin-right: 10px;">
        <tr>
            <td width="20%" class="p-0"></td>
            <td width="45%" class="p-0"></td>
            <td width="35%" class="text-right p-0" style="font-size: 20px;">ito ay ibinigay kay</td>
        </tr>
        <tr>
            <td width="20%" class="p-0"></td>
            <td width="45%" class="p-0"></td>
            <td width="35%" class="text-right p-0" style="font-size: 20px;"><i>is hereby given to</i></td>
        </tr>
    </table>
    <table width="100%"  style="table-layout: fixed; margin-top: 80px; margin-right: 10px;">
        <tr>
            <td width="20%;" class="p-0"></td>
            <td width="80%; font-size:20px;" class="text-center p-0">Pinatunayan nito na si</td>
        </tr>
        <tr>
            <td width="20%;" class="p-0"></td>
            <td width="80%; font-size:20px;" class="text-center p-0"><i>This certifies that</i></td>
        </tr>
    </table>
    <table width="100%"  style="table-layout: fixed; margin-top: 40px; margin-right: 10px;">
        <tr>
            <td width="20%;" class="p-0"></td>
            <td width="80%; font-size:45px;" class="text-center p-0"><u><b>{{$student->student}}</b></u></td>
        </tr>
    </table>
    <table width="100%"  style="table-layout: fixed; margin-top: 35px; margin-right: 10px;">
        <tr>
            <td width="20%;" class="p-0"></td>
            <td width="80%; font-size:20px;" class="text-center p-0"><i>LRN: </i><u><b>{{$student->lrn}}</b></u></td>
        </tr>
        <tr>
            <td width="20%;" class="p-0"></td>
            <td width="80%; font-size:15.5px;" class="text-center p-0">
                <span>ay kasiya-siyang nakatupad sa mga kinakailangan sa pagtatapos ng Junior High School na itinakda para sa</span><br>
                <span>has satisfactorily completed the requirements for {{$acadtext }} as prescribed for</span><br>
                <span>Mataas na Paaralan ng Kagawaran ng Edukasyon, kaya siya'y karapatdapat na tumanggap nitong</span><br>
                <span>Secondary Schools of the Republic of the Philippines, and is therefore entitled to this</span>
            </td>
        </tr>
        <tr>
            <td width="20%;" class="p-0"></td>
            <td width="80%; font-size:20px;" class="text-center p-0"><b>KATUNAYAN</b></td>
        </tr>
        <tr>
            <td width="20%;" class="p-0"></td>
            <td width="80%; font-size:20px;" class="text-center p-0"><i>CERTIFICATE</i></td>
        </tr>
        <tr>
            <td width="20%;" class="p-0"></td>
            <td width="80%; font-size:15.5px;" class="text-center p-0">
                <span>Nilagdaan sa <u>St. Joseph Academy of El Salvador, El Salvador City</u>, Pilipinas nitong ika 7 ng Hunyo 2020</span><br>
                <span>Signed in <u>St. Joseph Academy of El Salvador, El Salvador City</u>, Philippines this 7th day of June 2020<br>
            </td>
        </tr>
    </table>
    <table width="100%"  style="table-layout: fixed; margin-top: 70px; margin-right: 10px;">
        <tr>
            <td width="35%;" class="p-0"></td>
            {{-- <td width="80%; font-size:20px;" class="text-center p-0"><u><b>{{$principal_info[0]->name}}</b></u></td> --}}
            <td width="35%; font-size:21px;" class="text-center p-0"><u><b>ELDA C. GEMINEZ, MAEM</b></u></td>
            <td width="5%;" class="p-0"></td>
            <td width="35%; font-size:22px;" class="text-center p-0"><u><b>REV. FR. MAX V. CEBALLOS, SSJV</b></u></td>

        </tr>
        <tr>
            <td width="25%;" class="p-0"></td>
            {{-- <td width="80%; font-size:20px;" class="text-center p-0">{{$principal_info[0]->title}}</td> --}}
            <td width="38%; font-size:21px;" class="text-center p-0"><i>School Principal</i></td>
            <td width="2%;" class="p-0"></td>
            <td width="42%; font-size:22px;" class="text-center p-0"><i>School Director</i></td>
            <td width="3%;" class="p-0"></td>
        </tr>
    </table>

</body>
</html>