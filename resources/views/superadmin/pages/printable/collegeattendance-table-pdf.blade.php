<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Attendance PDF</title>
    <style>

        table {
            border-collapse: collapse;
            margin-bottom: 1rem;
            background-color: transparent;
            font-size:11px ;
        }
        
        .table thead th {
            vertical-align: middle;
        }
        
        .table td, .table th {
            padding-left: 5px;
            padding-right: 5px;
            padding-top: 2px;
            padding-bottom: 2px;
            font-size: 9px;
            text-align: center;
        }


        h5{
            font-size: 13px;
            padding: 0px;
            margin: 0px;

        }

        h4{
            font-size: 15px;
            padding: 0px;
            margin: 0px;

        }

        h3{
            font-size: 17px;
            padding: 0px;
            margin: 0px;

        }

        p{
            
            font-size: 12px;
            padding: 0px;
            margin: 0px;
        }

  

        h5, h4, h3 {

            line-height: 1.3;

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

        .p-1{
            padding: .25rem !important;
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
        
        .ml-1 {

            margin-left: 20px!important;
        }

        .mr-1 {

            margin-right: 20px!important;
        }

        body{
            font-family: Calibri, sans-serif;
        }
        
        .align-middle{
            vertical-align: middle !important;    
        }

        .text-red{
            color: red;
            border: solid 1px black;
        }

        .float-child-left{
            width: 90%;
            float: left;
        } 
        
        .float-child-right{
            width: 10%;
            float: right;

        }  

        .rating_ul p{

            font-size: 11px;
        }

        .section{

            float: right; 
           

        }

        .quarter{

            float: left; 
            padding: 0px;
        }

        .quarter p, .section p{

            padding: 0px;
            margin: 0px;
            font-weight: bold;
            font-size: 12px;
        }

        .base_rating{
         
            list-style: none;
        }

        .li_p{

            margin-top: 3px;
        }

        .values{

            text-align: center; 
            line-height: 0.5; 
            float: left;
        }

        .signatory td p{

            margin: 2px;
        }


        .page_break { page-break-before: always; }

        @page { size: 11in 8.5in; margin: .25in;  }
        
    </style>
</head>
<body>

    @php
        $monthNames = ['January', 'Febuary', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
    @endphp


    @if (count($months) != 1)

        @for ($m=0;$m < count($months); $m++)


            <div style="height: 120px">
                <table style="width: 100%">
                    <tr>
                        <td width="30%" style="padding-left: 200px">
                            <div>
                                <img src="{{base_path()}}/public/{{$schoolinfo->picurl}}" alt="school" width="120px">
                            </div>
                        </td>
        
                        <td width="70%" style="padding-right: 300px">
                            <div style="text-align: center">
                                <h3>{{$schoolinfo->schoolname}}</h3>
                                <p style="font-size: 12px">{{$schoolinfo->address}} </p>
                                <br>
                                <h4>College Attendance</h4>
                                <h5>{{$schoolyear->sydesc}}</h5>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <br>
            <div style="height: 25px; margin-bottom: 10px;">
                
                <div class="quarter">
                    <p>{{$sections->sectionname}} - {{$gradelvl->levelname}}</p>
                    @if($semid == 1)
                        <p>First Semester</p>
                    @elseif($semid == 2)
                        <p>Second Semester</p>
                    @endif
                </div>
                
                
                <div class="section">
                    @php 
        
                        if($gradelvl->acadprogid == 2){
        
                            echo $gradelvl->levelname;
        
                        }else{
        
                            echo str_replace('GRADE ', '', $gradelvl->levelname);
                        }
        
                    @endphp
                    </p>
        
                </div>
        
            </div>
        
            <div>   
                
                
            @php
                $date = new DateTime("1-".$months[$m]."-2023");
                $date->modify("last day of this month");
            @endphp
                

            <table width="100%" class="table table-bordered">

                <thead>
                    <tr>
                        <th rowspan="2">Learner's Name</th>
                        <th colspan="{{ $date->format("d") }}">Month of {{ $monthNames[$months[$m]-1]}}</th>
                        <th colspan="3">Total</th>
                        <th rowspan="2">Remarks</th>
                    </tr>
                    @php
                        
                    @endphp
    
                    <tr>
                        @for ($i=1; $i <= $date->format("d"); $i++)
                            <th>{{ $i }}</th>
                        @endfor
                        <th>Present</th>
                        <th>Absent</th>
                        <th>Tardy</th>
                    </tr>
  
                </thead>
    
                <tbody>
                    @php
                        
                        $datas = collect($attendance)
                            ->where('monthid', $months[$m]);
                    @endphp

                    @foreach ($enrollstudents as $student)
                        
                        <tr>
                            <td class="text-left" width="20%">{{$student->lastname}}, {{$student->firstname}} 
                                @if($student->middlename == '-' || $student->middlename == null) 
                                    
                                    <?php echo " "; ?>
                                @else
    
                                    {{substr($student->middlename, 0, 1)}}. 
    
                                @endif
                            </td>

                            @php
                                $totalP = 0;
                                $totalA = 0;
                                $totalL = 0;

                                $collectedAttndc = collect($attendance)
                                    ->where('studid', $student->studid)
                                    ->where('monthid', $months[$m])
                                    // ->where('yearid', $element['yearid'])
                                    ->first();
                            @endphp

                            @if (isset($collectedAttndc->monthid))
                                
                                @if ($months[$m] == $collectedAttndc->monthid)
                                    @for ($i=1; $i <= $date->format("d"); $i++)
                                        @php 
                                            $colname = "day".$i; 
                                
                                        @endphp
                                        @if ($collectedAttndc->$colname == 1)
                                            <td>P</td>
                                            @php $totalP += 1; @endphp
                                        @elseif ($collectedAttndc->$colname == 2)
                                            <td>A</td>
                                            @php $totalA += 1; @endphp
                                        @elseif ($collectedAttndc->$colname == 3)
                                            <td>L</td>
                                            @php $totalL += 1; @endphp
                                        @elseif ($collectedAttndc->$colname == 4)
                                            <td>E</td>
                                        @else
                                            <td></td>
                                        @endif
                                    @endfor
                                @else

                                    @for ($i=1; $i <= $date->format("d"); $i++)

                                        <td></td>
                                        
                                    @endfor
                                            
                                @endif
                                
                            @else

                                @for ($i=1; $i <= $date->format("d"); $i++)

                                    <td></td>
                                    
                                @endfor
                                
                            @endif
                           
                    
        
                            <td>{{ $totalP }}</td>
                            <td>{{ $totalA }}</td>
                            <td>{{ $totalL }}</td>
                            
                            <td width="7%"></td>
        
        
                        </tr>

                    @endforeach

                </tbody>
                
            </table>
    
            <br>
            <!-- Page Break 2-->
            <div class="page_break"></div>
        
            
        @endfor
        
    @else

        
    @endif




</body>
</html>