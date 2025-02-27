<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$section_detail->levelname. ' - ' . $section_detail->sectionname . ' '. $schoolyear_detail->sydesc.' QUARTER '.$quarter}}</title>
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

        .text-red{
            color: red;
            border: solid 1px black;
        }



        .btn-secondary {
            background-color: #d6d8db;
            border-color: #6c757d;
            box-shadow: none;
        }

        .bg-info {
            background-color: #b8daff!important;
            border-color: #6c757d;
            box-shadow: none;
        }


        @page { size: 11in 8.5in; margin: .25in .25in  }
        
    </style>
    
  
    
</head>
<body>  
            <table class="table mb-0 table-sm header" style="font-size:13px;">
                <tr>
                    <td width="20%" rowspan="2" class="text-right align-middle p-0">
                        <img src="{{base_path()}}/public/{{$schoolinfo[0]->picurl}}" alt="school" width="70px">
                    </td>
                    <td width="60%" class="p-0 text-center" >
                        <h3 class="mb-0" style="font-size:20px !important">{{$schoolinfo[0]->schoolname}}</h3>
                    </td>
                    <td width="20%" rowspan="2" class="text-right align-middle p-0">
                        
                    </td>
                </tr>
                <tr>
                    <td class="p-0 text-center">
                        {{$schoolinfo[0]->address}}
                    </td>
                </tr>
            </table>

            <table class="table mb-0 table-sm" style="font-size:13px;">
                <tr>
                    <td width="100%" class="text-center p-0"><b>{{$section_detail->levelname}} - {{$section_detail->sectionname}}</b></td>
                </tr>
                <tr>
                    <td width="100%" class="text-center p-0">MAPEH Grading Sheet School Year {{$schoolyear_detail->sydesc}}</td>
                </tr>
            </table>
            <br>
            <table class="table mb-0 table-sm" >
                    <tr>
                        <td width="50%"><b>Subject Teacher: </b> {{$section_detail->lastname}},  {{$section_detail->firstname}}</td>
                        <td width="50%" class="text-right"></td>
                    </tr>
                
            </table>
            <table class="table table-sm table-bordered" width="100%" style="font-size:.6rem !important">
                <tr>
                    <td ></td>
                    @php
                        $colspan = collect($subjects)->where('subjCom',$subjid)->count() + 1;
                    @endphp
                    <td colspan="{{$colspan}}" class="text-center">1st Grading</td>
                    <td colspan="{{$colspan}}" class="text-center">2nd Grading</td>
                    <td colspan="{{$colspan}}" class="text-center">3rd Grading</td>
                    <td colspan="{{$colspan}}" class="text-center">4th Grading</td>
                    <td colspan="2" class="text-center" >Final Grading</td>
                </tr>
                <tr>
                    <td></td>
                    @php
                        $headersubj = collect($subjects)->where('subjCom',$subjid)->values();
                        $headersubj_cons = collect($subjects)->where('subjid',$subjid)->first();
                    @endphp
                    @for($x = 1; $x <= 4; $x++)
                        @foreach($headersubj as $item)
                            <td  class="text-center">{{$item->subjcode}}</td>
                        @endforeach
                        <td class="text-center btn-secondary">{{$headersubj_cons->subjcode}}</td>
                    @endfor
                    <td class="text-center bg-info">FR</td>
                    <td >Remarks</td>
                </tr>
                @php
                        $male = 0;
                        $female = 0;
                        $count = 1;
                @endphp
                @foreach ($students as $item)
                    @if($male == 0 && strtoupper($item->gender) == 'MALE')
                        <tr class="bg-gray">
                            <td style="padding-left: 5px !important" colspan="{{ ($colspan * 4 ) + 3}}">MALE</td>
                            @php
                                $male = 1;
                            @endphp
                        </tr>
                    @elseif($female == 0  && strtoupper($item->gender) == 'FEMALE')
                        <tr>
                            <td style="padding-left: 5px !important" colspan="{{ ($colspan * 4 ) + 3}}">&nbsp;</td>
                            @php
                                $female = 1;
                                $count = 1;
                            @endphp
                        </tr>
                        <tr class="bg-gray">
                            <td style="padding-left: 5px !important" colspan="{{ ($colspan * 4 ) + 3}}">FEMALE</td>
                            @php
                                $female = 1;
                                $count = 1;
                            @endphp
                        </tr>
                    @endif
                    <tr>
                        @php
                            $comp_subjects = collect($item->grades)->where('subjCom',$subjid)->values();
                        @endphp
                        <td style="padding-left: 5px !important" >{{$count}}. {{$item->student}}</td>

                        @for($x = 1; $x <= 4; $x++)
                            @php
                                $tempvar = 'q'.$x;
                            @endphp

                            @foreach($comp_subjects as $comp_item)
                                <td class="text-center">{{$comp_item->$tempvar}}</td>
                            @endforeach
                            <td class="btn-secondary text-center">{{collect($item->grades)->where('subjid',$subjid)->first()->$tempvar}}</td>
                        @endfor


                    
                        <td class="bg-info text-center">{{collect($item->grades)->where('subjid',$subjid)->first()->finalrating}}</td>
                        <td class="text-center">{{collect($item->grades)->where('subjid',$subjid)->first()->actiontaken}}</td>
                    </tr>
                    @php
                        $count += 1;
                    @endphp
                @endforeach
                <tr>
                    <td style="padding-left: 5px !important" colspan="{{ ($colspan * 4 ) + 3}}">&nbsp;</td>
                </tr>
                <tr>
                    <td width="30%">Teacher Initial</td>
                    <td colspan="{{$colspan}}" class="text-center"></td>
                    <td colspan="{{$colspan}}" class="text-center"></td>
                    <td colspan="{{$colspan}}" class="text-center"></td>
                    <td colspan="{{$colspan}}" class="text-center"></td>
                    <td colspan="2" class="text-center"></td>
                </tr>
            
            </table>
            <i style="font-size:.5rem !important">Date Generated: {{\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMMM DD, YYYY hh:mm A')}}</i>
        </tbody>
</body>
</html>