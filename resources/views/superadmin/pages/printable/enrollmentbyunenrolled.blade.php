<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title></title>
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

            
            .small-text td{
                padding-top: .1rem;
                padding-bottom: .1rem;
                font-size: .55rem !important;
            }

            .studentinfo td{
                padding-top: .1rem;
                padding-bottom: .1rem;
            
            }

            .text-red{
                color: red;
                border: solid 1px black;
            }


            
            .page_break { page-break-before: always; }
            @page { size: 8.5in 11in; margin: .25in;  }
            
        </style>
    </head>
    <body>
        <table class="table mb-0 table-sm header" style="font-size:13px;">
            <tr>
                <td width="20%" rowspan="2" class="text-right align-middle p-0">
                    <img src="{{base_path()}}/public/{{$schoolinfo->picurl}}" alt="school" width="70px">
                </td>
                <td width="60%" class="p-0 text-center" >
                    <h3 class="mb-0" style="font-size:18px !important">{{$schoolinfo->schoolname}}</h3>
                </td>
                <td width="20%" rowspan="2" class="text-right align-middle p-0">
                
                </td>
            </tr>
            <tr>
                <td class="p-0 text-center">
                    {{$schoolinfo->address}}
                </td>
            </tr>
        </table>

        <table class="table mb-0 table-sm">
            <tr>
                <td class="text-center">
                    <b style="font-size:1rem !important">Enrolment by Unenrolled Learners</b>
                </td>
            </tr>
        </table>

        <table class="table mb-0 table-sm">
            <tr>
                <td width="50%">
                    <b>School Year: </b>{{$syinfo->sydesc}}
                </td>
                <td width="50%"></td>
            </tr>
            <tr>
                <td >
                    <b>Semester: </b>{{$seminfo->semester}}
                </td>
                <td></td>
            </tr>
        </table>

        <table class="table mb-0 table-sm">
            <tr>
                <td width="100%">
                  <i>Names with strikethrough are either dropped out, withdrawn or transfered out students.</i>
                </td>
            </tr>
        </table>

        <table class="table mb-0 table-sm table-bordered">
            <tr>
                <td class="text-center align-middle" rowspan="2" width="28%">Students</td>
                <td class="text-center" colspan="2">Unenrolled Info.</td>
                <td class="text-center" colspan="3">Enrolled Info.</td>
            </tr>
            <tr>
                <td width="15%">Grade Level</td>
                <td  width="15%">Course</td>

                <td width="14%">Status</td>
                <td width="14%">Grade Level</td>
                <td width="14%">Course</td>
            </tr>
            @php
                $count = 1;
            @endphp
            @foreach($students_unenrolled as $item)
                <tr>
                    @if($item->en_studstatus == 1 || $item->en_studstatus == 2 || $item->en_studstatus == 4 )
                        <td>{{$count}}.{{$item->studentname}}</td>
                        <td>{{str_replace(' COLLEGE','',collect($gradelevel)->where('id',$item->levelid)->first()->levelname)}}</td>
                        <td>
                            @if(isset($item->courseid))
                                {{collect($courses)->where('id',$item->courseid)->first()->courseabrv}}
                            @endif
                        </td>
                    @else
                        <td><s>{{$count}}.{{$item->studentname}}</s></td>
                        <td><s>{{str_replace(' COLLEGE','',collect($gradelevel)->where('id',$item->levelid)->first()->levelname)}}</s></td>
                        <td>
                            @if(isset($item->courseid))
                                <s>{{collect($courses)->where('id',$item->courseid)->first()->courseabrv}}</s>
                            @endif
                        </td>
                    @endif

                    @if($item->en_enrollment_status == 'Enrolled')
                        <td>{{$item->en_enrollment_status}}</td>
                        <td>{{str_replace(' COLLEGE','',collect($gradelevel)->where('id',$item->en_gradelevel)->first()->levelname)}}</td>
                        <td>
                            @if(isset($item->en_course))
                                @if(isset(collect($courses)->where('id',$item->en_course)->first()->courseabrv))
                                    {{collect($courses)->where('id',$item->en_course)->first()->courseabrv}}
                                @endif
                            @endif
                        </td>
                    @else
                        <td colspan="3" class="text-center" style="background-color: red">{{$item->en_enrollment_status}}</td>
                    @endif
                   
                </tr>

                @php
                    $count += 1;
                @endphp

            @endforeach
        </table>


    </body>
</html>