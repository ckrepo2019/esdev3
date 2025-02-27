<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Calendar PDF</title>
    <style>

        
        table {
            border-collapse: collapse;
            margin-bottom: 1rem;
            background-color: transparent;
            font-size:11px ;
        }

        .table-bordered {
            border: 1px solid #00000;
        }

        .table-bordered td, .table-bordered th {
            border: 1px solid #00000;
            padding: .5rem!important;
        }

        .content-wrapper{

            width: 100%;
        }

        .logo{

            text-align: center;
        }

        .text-center{
            
            text-align: center;
        }

        .p-0{

            padding: 0px!important;
        }

        .p-1{

            padding: .25rem!important;
        }

        .p-2{

            padding: .5rem!important;
        }

        .p-3{

            padding: 1rem!important;
        }
        
        .m-0{

            margin: 0px!important;
        }


        /* color */

        .bg-dark{

            background: #444445;
            color: white;
        }

        .page_break { page-break-before: always; }

        @page { size: 8.5in 11in; margin: .25in;  }
        
    </style>
</head>
<body>

    <div class="content-wrapper">

        <table style="width: 100%;">
            <tr>
                <td width="30%" style="padding-left: 120px">
                    <div class="logo text-center">
                        <img src="{{base_path()}}/public/{{$schoolinfo->picurl}}" alt="school" width="120px">

                    </div>
                </td>

                <td width="70%" style="padding-right: 230px">
                    <div style="text-align: center">
                        <h5 class="m-0" style="font-size: 15px">{{$schoolinfo->schoolname}}</h5>
                        <p class="m-0" style="font-size: 11px;">{{$schoolinfo->address}} </p>
                        <br>
                        <h4 class="m-0">INSTITUTIONAL CALENDAR OF ACTIVITIES</h5>
                        <h4 class="m-0">SY {{$schoolyear->sydesc}}</h5>
                    </div>
                </td>
            </tr>
        </table>

        <table width="100%" class="table-bordered">
            <thead>
                <tr>
                    <th width="10%">Date</th>
                    <th width="10%">Day</th>
                    <th width="25%">Activity</th>
                    <th width="20%">Time/Venue</th>
                    <th width="20%">Person Responsible/Involve</th>
                    <th width="15%">Remarks</th>
                </tr>
            </thead>
            <tbody>

                <tr>
                    <th class="bg-light" colspan="6">January</th>
                </tr>
                @foreach($events as $event)
                    <?php  
                        $month = date_create($event->start); 
                        $date = date_create($event->start); 
                        $dayS = date_create($event->start); 
                        $dayE = date_create($event->end); 

                        $startday = date_format(date_create($event->start),"d"); 
                        $endday = date_format(date_create($event->end),"d"); 
                    ?>
                    @if( date_format($month,"m") == "01")
                        <tr>
                            @if($startday == $endday)
                                <td class="text-center p-1">{{date_format($dayS,"d")}}</td>
                                <td class="text-center p-1">{{date_format($date,"D")}}</td>

                            @else
                                <td class="text-center p-1">{{$startday}}-{{$endday}}</td>
                                <td class="text-center p-1">{{date_format($dayS,"D")}} - {{date_format($dayE,"D")}}</td>

                            @endif
                            <td class="text-center p-1">{{$event->title}}</td>
                            <td class="text-center p-1">{{$event->venue}}</td>
                            <td class="text-center p-1">{{$event->involve}}</td>
                            <td class="text-center p-1"></td>
                        </tr>

                    @endif

                @endforeach

                <tr>
                    <th class="bg-light" colspan="6">Febuary</th>
                </tr>

                @foreach($events as $event)
                    <?php  
                        $month = date_create($event->start); 
                        $date = date_create($event->start); 
                        $dayS = date_create($event->start); 
                        $dayE = date_create($event->end); 

                        $startday = date_format(date_create($event->start),"d"); 
                        $endday = date_format(date_create($event->end),"d"); 
                    ?>
                    @if( date_format($month,"m") == "02")
                        <tr>
                            @if($startday == $endday)
                                <td class="text-center p-1">{{date_format($dayS,"d")}}</td>
                                <td class="text-center p-1">{{date_format($date,"D")}}</td>

                            @else
                                <td class="text-center p-1">{{$startday}}-{{$endday}}</td>
                                <td class="text-center p-1">{{date_format($dayS,"D")}} - {{date_format($dayE,"D")}}</td>

                            @endif
                            <td class="text-center p-1">{{$event->title}}</td>
                            <td class="text-center p-1">{{$event->venue}}</td>
                            <td class="text-center p-1">{{$event->involve}}</td>
                            <td class="text-center p-1"></td>
                        </tr>

                    @endif

                @endforeach

                <tr>
                    <th class="bg-light" colspan="6">March</th>
                </tr>

                @foreach($events as $event)
                    <?php  
                        $month = date_create($event->start); 
                        $date = date_create($event->start); 
                        $dayS = date_create($event->start); 
                        $dayE = date_create($event->end); 

                        $startday = date_format(date_create($event->start),"d"); 
                        $endday = date_format(date_create($event->end),"d"); 
                    ?>
                    @if( date_format($month,"m") == "03")
                        <tr>
                            @if($startday == $endday)
                                <td class="text-center p-1">{{date_format($dayS,"d")}}</td>
                                <td class="text-center p-1">{{date_format($date,"D")}}</td>

                            @else
                                <td class="text-center p-1">{{$startday}}-{{$endday}}</td>
                                <td class="text-center p-1">{{date_format($dayS,"D")}} - {{date_format($dayE,"D")}}</td>

                            @endif
                            <td class="text-center p-1">{{$event->title}}</td>
                            <td class="text-center p-1">{{$event->venue}}</td>
                            <td class="text-center p-1">{{$event->involve}}</td>
                            <td class="text-center p-1"></td>
                        </tr>

                    @endif

                @endforeach

                <tr>
                    <th class="bg-light" colspan="6">April</th>
                </tr>

                @foreach($events as $event)
                    <?php  
                        $month = date_create($event->start); 
                        $date = date_create($event->start); 
                        $dayS = date_create($event->start); 
                        $dayE = date_create($event->end); 

                        $startday = date_format(date_create($event->start),"d"); 
                        $endday = date_format(date_create($event->end),"d"); 
                    ?>
                    @if( date_format($month,"m") == "04")
                        <tr>
                            @if($startday == $endday)
                                <td class="text-center p-1">{{date_format($dayS,"d")}}</td>
                                <td class="text-center p-1">{{date_format($date,"D")}}</td>

                            @else
                                <td class="text-center p-1">{{$startday}}-{{$endday}}</td>
                                <td class="text-center p-1">{{date_format($dayS,"D")}} - {{date_format($dayE,"D")}}</td>

                            @endif
                            <td class="text-center p-1">{{$event->title}}</td>
                            <td class="text-center p-1">{{$event->venue}}</td>
                            <td class="text-center p-1">{{$event->involve}}</td>
                            <td class="text-center p-1"></td>
                        </tr>

                    @endif

                @endforeach

                <tr>
                    <th class="bg-light" colspan="6">May</th>
                </tr>

                @foreach($events as $event)
                    <?php  
                        $month = date_create($event->start); 
                        $date = date_create($event->start); 
                        $dayS = date_create($event->start); 
                        $dayE = date_create($event->end); 

                        $startday = date_format(date_create($event->start),"d"); 
                        $endday = date_format(date_create($event->end),"d"); 
                    ?>
                    @if( date_format($month,"m") == "05")
                        <tr>
                            @if($startday == $endday)
                                <td class="text-center p-1">{{date_format($dayS,"d")}}</td>
                                <td class="text-center p-1">{{date_format($date,"D")}}</td>

                            @else
                                <td class="text-center p-1">{{$startday}}-{{$endday}}</td>
                                <td class="text-center p-1">{{date_format($dayS,"D")}} - {{date_format($dayE,"D")}}</td>

                            @endif
                            <td class="text-center p-1">{{$event->title}}</td>
                            <td class="text-center p-1">{{$event->venue}}</td>
                            <td class="text-center p-1">{{$event->involve}}</td>
                            <td class="text-center p-1"></td>
                        </tr>

                    @endif

                @endforeach

                <tr>
                    <th class="bg-light" colspan="6">June</th>
                </tr>


                @foreach($events as $event)
                    <?php  
                        $month = date_create($event->start); 
                        $date = date_create($event->start); 
                        $dayS = date_create($event->start); 
                        $dayE = date_create($event->end); 

                        $startday = date_format(date_create($event->start),"d"); 
                        $endday = date_format(date_create($event->end),"d"); 
                    ?>
                    @if( date_format($month,"m") == "06")
                        <tr>
                            @if($startday == $endday)
                                <td class="text-center p-1">{{date_format($dayS,"d")}}</td>
                                <td class="text-center p-1">{{date_format($date,"D")}}</td>

                            @else
                                <td class="text-center p-1">{{$startday}}-{{$endday}}</td>
                                <td class="text-center p-1">{{date_format($dayS,"D")}} - {{date_format($dayE,"D")}}</td>

                            @endif
                            <td class="text-center p-1">{{$event->title}}</td>
                            <td class="text-center p-1">{{$event->venue}}</td>
                            <td class="text-center p-1">{{$event->involve}}</td>
                            <td class="text-center p-1"></td>
                        </tr>

                    @endif

                @endforeach

                <tr>
                    <th class="bg-light" colspan="6">July</th>
                </tr>


                @foreach($events as $event)
                    <?php  
                        $month = date_create($event->start); 
                        $date = date_create($event->start); 
                        $dayS = date_create($event->start); 
                        $dayE = date_create($event->end); 

                        $startday = date_format(date_create($event->start),"d"); 
                        $endday = date_format(date_create($event->end),"d"); 
                    ?>
                    @if( date_format($month,"m") == "07")
                        <tr>
                            @if($startday == $endday)
                                <td class="text-center p-1">{{date_format($dayS,"d")}}</td>
                                <td class="text-center p-1">{{date_format($date,"D")}}</td>

                            @else
                                <td class="text-center p-1">{{$startday}}-{{$endday}}</td>
                                <td class="text-center p-1">{{date_format($dayS,"D")}} - {{date_format($dayE,"D")}}</td>

                            @endif
                            <td class="text-center p-1">{{$event->title}}</td>
                            <td class="text-center p-1">{{$event->venue}}</td>
                            <td class="text-center p-1">{{$event->involve}}</td>
                            <td class="text-center p-1"></td>
                        </tr>

                    @endif

                @endforeach

                <tr>
                    <th class="bg-light" colspan="6">August</th>
                </tr>
                

                @foreach($events as $event)
                    <?php  
                        $month = date_create($event->start); 
                        $date = date_create($event->start); 
                        $dayS = date_create($event->start); 
                        $dayE = date_create($event->end); 

                        $startday = date_format(date_create($event->start),"d"); 
                        $endday = date_format(date_create($event->end),"d"); 
                    ?>
                    @if( date_format($month,"m") == "08")
                        <tr>
                            @if($startday == $endday)
                                <td class="text-center p-1">{{date_format($dayS,"d")}}</td>
                                <td class="text-center p-1">{{date_format($date,"D")}}</td>

                            @else
                                <td class="text-center p-1">{{$startday}}-{{$endday}}</td>
                                <td class="text-center p-1">{{date_format($dayS,"D")}} - {{date_format($dayE,"D")}}</td>

                            @endif
                            <td class="text-center p-1">{{$event->title}}</td>
                            <td class="text-center p-1">{{$event->venue}}</td>
                            <td class="text-center p-1">{{$event->involve}}</td>
                            <td class="text-center p-1"></td>
                        </tr>

                    @endif

                @endforeach

                <tr>
                    <th class="bg-light" colspan="6">September</th>
                </tr>

                @foreach($events as $event)
                    <?php  
                        $month = date_create($event->start); 
                        $date = date_create($event->start); 
                        $dayS = date_create($event->start); 
                        $dayE = date_create($event->end); 

                        $startday = date_format(date_create($event->start),"d"); 
                        $endday = date_format(date_create($event->end),"d"); 
                    ?>
                    @if( date_format($month,"m") == "09")
                        <tr>
                            @if($startday == $endday)
                                <td class="text-center p-1">{{date_format($dayS,"d")}}</td>
                                <td class="text-center p-1">{{date_format($date,"D")}}</td>

                            @else
                                <td class="text-center p-1">{{$startday}}-{{$endday}}</td>
                                <td class="text-center p-1">{{date_format($dayS,"D")}} - {{date_format($dayE,"D")}}</td>

                            @endif
                            <td class="text-center p-1">{{$event->title}}</td>
                            <td class="text-center p-1">{{$event->venue}}</td>
                            <td class="text-center p-1">{{$event->involve}}</td>
                            <td class="text-center p-1"></td>
                        </tr>

                    @endif

                @endforeach

                <tr>
                    <th class="bg-light" colspan="6">October</th>
                </tr>

                @foreach($events as $event)
                    <?php  
                        $month = date_create($event->start); 
                        $date = date_create($event->start); 
                        $dayS = date_create($event->start); 
                        $dayE = date_create($event->end); 

                        $startday = date_format(date_create($event->start),"d"); 
                        $endday = date_format(date_create($event->end),"d"); 
                    ?>
                    @if( date_format($month,"m") == "10")
                        <tr>
                            @if($startday == $endday)
                                <td class="text-center p-1">{{date_format($dayS,"d")}}</td>
                                <td class="text-center p-1">{{date_format($date,"D")}}</td>

                            @else
                                <td class="text-center p-1">{{$startday}}-{{$endday}}</td>
                                <td class="text-center p-1">{{date_format($dayS,"D")}} - {{date_format($dayE,"D")}}</td>

                            @endif
                            <td class="text-center p-1">{{$event->title}}</td>
                            <td class="text-center p-1">{{$event->venue}}</td>
                            <td class="text-center p-1">{{$event->involve}}</td>
                            <td class="text-center p-1"></td>
                        </tr>

                    @endif

                @endforeach

                <tr>
                    <th class="bg-light" colspan="6">November</th>
                </tr>


                @foreach($events as $event)
                    <?php  
                        $month = date_create($event->start); 
                        $date = date_create($event->start); 
                        $dayS = date_create($event->start); 
                        $dayE = date_create($event->end); 

                        $startday = date_format(date_create($event->start),"d"); 
                        $endday = date_format(date_create($event->end),"d"); 
                    ?>
                    @if( date_format($month,"m") == "11")
                        <tr>
                            @if($startday == $endday)
                                <td class="text-center p-1">{{date_format($dayS,"d")}}</td>
                                <td class="text-center p-1">{{date_format($date,"D")}}</td>

                            @else
                                <td class="text-center p-1">{{$startday}}-{{$endday}}</td>
                                <td class="text-center p-1">{{date_format($dayS,"D")}} - {{date_format($dayE,"D")}}</td>

                            @endif
                            <td class="text-center p-1">{{$event->title}}</td>
                            <td class="text-center p-1">{{$event->venue}}</td>
                            <td class="text-center p-1">{{$event->involve}}</td>
                            <td class="text-center p-1"></td>
                        </tr>

                    @endif

                @endforeach

                <tr>
                    <th class="bg-light" colspan="6">December</th>
                </tr>

                @foreach($events as $event)
                    <?php  
                        $month = date_create($event->start); 
                        $date = date_create($event->start); 
                        $dayS = date_create($event->start); 
                        $dayE = date_create($event->end); 

                        $startday = date_format(date_create($event->start),"d"); 
                        $endday = date_format(date_create($event->end),"d"); 
                    ?>
                    @if( date_format($month,"m") == "12")
                        <tr>
                            @if($startday == $endday)
                                <td class="text-center p-1">{{date_format($dayS,"d")}}</td>
                                <td class="text-center p-1">{{date_format($date,"D")}}</td>

                            @else
                                <td class="text-center p-1">{{$startday}}-{{$endday}}</td>
                                <td class="text-center p-1">{{date_format($dayS,"D")}} - {{date_format($dayE,"D")}}</td>

                            @endif
                            <td class="text-center p-1">{{$event->title}}</td>
                            <td class="text-center p-1">{{$event->venue}}</td>
                            <td class="text-center p-1">{{$event->involve}}</td>
                            <td class="text-center p-1"></td>
                        </tr>

                    @endif

                @endforeach


            </tbody>
        </table>
    </div>

</body>
</html>