<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <title>{{$student->firstname.' '.$student->middlename[0].' '.$student->lastname}}</title> --}}
    <style>

        .table {
            background-color: transparent;
            vertical-align: middle;
            table-layout: fixed;
        }

        table {
            border-collapse: collapse;
        }
        
        .table thead th {
            vertical-align: bottom;
        }
        
        .table td, .table th {
            /* padding-top: 5px; */
            padding-bottom: 2px;
            /* vertical-align: top; */
        }
        
        .table-bordered {
            border: 1px solid #00000;
        }

        .table-bordered td, .table-bordered th {
            border: 1px solid #00000;
        }

        .table-sm td, .table-sm th {
            padding: 5px!important;
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
            padding-top: 0!important;
            padding-bottom: 0!important;
            padding-right: 0!important;
            padding-left: 0!important;
        }
        .p-0 td{
            padding-top: 0!important;
            padding-bottom: 0!important;
        }
        .p-5{
            padding-top: 13px!important;
            padding-bottom: 13px!important;
            padding-right: 2px!important;
            padding-left: 2px!important;
        }
       .pb-0{
            padding-bottom: 0!important;
       }
       .pb-5{
            padding-bottom: 25px!important;
       }
        .pl-3{
            padding-left: 1rem !important;
        }

        .mb-0{
            margin-bottom: 0;
        }
        .mt-0{
            margin-top: 0!important;
        }
        .mt-3{
            margin-top: 1rem!important;
        }
        .mb-1, .my-1 {
            margin-bottom: .25rem!important;
        }

        body{
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
            font-size: 9pt;
        }
        p{
            margin: 0;
        }
        .align-top{
            vertical-align: top!important;
        }
        .align-middle{
            vertical-align: middle !important;    
        }
        .align-bottom{
            vertical-align: bottom!important;
        }
         
        .grades td{
            padding-top: 5px;
            padding-bottom: 5px;
            font-size: 9pt;
        }

        .studentinfo td{
            padding-top: .1rem;
            padding-bottom: .1rem;
          
        }
        .bold{
            font-weight: bold!important;
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
        .small-font{
            font-size: 9pt!important;
        }
        .smaller-font{
            font-size: 8pt!important;
        }
        .smallest-font{
            font-size: 7pt!important;
        }
        h4{
            margin-bottom: 10px;
        }
        .suptitle{
            font-size: 15pt;
        }
        .round-bordered{
            border: 2px solid rgb(71, 237, 246);
            border-radius: 10px!important;
        }
        .rounded-bordered{
            border: 2px solid black;
            border-radius: 30px!important;
        }
        .title{
            font-size: 17pt!important;
        }
        .subtitle{
            font-size: 13pt!important;
        }
        .small-font2{
            font-size: 9.5pt!important;
        }
        .subspace{
            margin-top: 3px!important
        }
        .space{
            margin-top: 13px!important
        }
        .spacing{
            margin-top: 25px!important;
        }
        .superspace{
            margin-top: 1.3in!important;
        }
        .overline{
            border-top: 1px solid black;
        }
        .underline{
            border-bottom: 1px solid black;
        }
        .timesnew{
            font-family: 'Times New Roman', Times, serif;
        }
        #space{
            margin-top: .2in!important
        }
        .right-margin{
            margin-right: .5in!important;
        }
        .left-margin{
            margin-left: .15in!important;
        }
        .smallcompressed{
            margin-right: .2in!important;
            margin-left: .2in!important;
        }
        .compressed{
            margin-left: .15in!important;
            margin-right: .15in!important;
        }
        .supcompressed{
            margin-right: 1.3in!important;
            margin-left: 1.3in!important;
        }
        .border-top-bottom{
            border-top: 1px solid black;
            border-bottom: 1px solid black;
        }
        .border{
            border: 1px solid black;
            /* text-align: center!important; */
        }
        .border2{
            border: 2px solid black;
            /* text-align: center!important; */
        }
        .border-bot{
            border-bottom: 3px solid black;
        }
        .border-top{
            border-top: 3px solid black;
        }
        .indent{
            text-indent: .5in;
        }
        .small-indent{
            text-indent: .3in
        }
        .new-page{
            page-break-after: always;
        }
        .padding{
            padding-bottom: 20px !important;
            padding-top: 20px !important;
        }
        .m-tb{
            margin-top: 25px!important;
            margin-bottom: 25px!important;
        }
        .no-border{
            border-top: 1px solid white!important;
            border-bottom: 1px solid white!important;
        }
        .no-right{
            border-right: 1px solid white!important;
        }
        .no-left{
            border-left: 1px solid white !important;
        }
        .no-top{
            border-top: 1px solid white!important;
        }
        .no-bottom{
            border-bottom: 1px solid white!important;
        }
        .relative{
            position: relative;
        }
        .absolute{
            position: absolute;
            top: 11.5in;
        }
        .word-space{
            word-spacing: 8px;
        }
        .mr-0{
            margin-right: 0!important;
        }
        .ml-0{
            margin-left: 0!important;
        }
        .yellow-back{
            background-color: yellow;
        }
        .light-green{
            background-color: #92D050;
        }
        .white{
            color: rgb(255, 255, 255);
        }
        .blue-border{
            border-bottom: 2px solid rgb(170, 215, 250);
            border-top: 2px solid rgb(150, 204, 246);
        }
        .uppercase{
            text-transform: uppercase!important;
        }
        .justify{
            text-align: justify!important;
        }
        .gigaspace{
            margin-top: 1.7in;
        }
        .paragraph{
            font-size: 8.5pt;
        }
        .px-2{
            padding-left: 2rem;
            padding-right: 2rem;
        }
        .u{
            text-decoration: underline;
        }
        .italic{
            font-style: italic;
        }
		 .check_mark {
            font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
            }


        .collegeTable {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .collegeTable table, .collegeTable th, .collegeTable td {
            border: 1px solid black;
        }
        .collegeTable th, .collegeTable td {
            padding: 8px;
            text-align: center;
        }
        .collegeTable th {
            background-color: #f2f2f2;
        }

        
        @page { size: 8.5in 13in; margin: 1in .4in;}
    </style>
</head>
@php

        $onemale = 0;
        $onefemale = 0;
        $twomale = 0;
        $twofemale = 0;
        $threemale = 0;
        $threefemale = 0;
        $fourmale = 0;
        $fourfemale = 0;
        $totalall = 0;


@endphp
<body>
    <div width="100%" class="text-center">
        <div>HOLY CROSS COLLEGE OF CALINAN, INC.</div>
        <div>Davao-Bukidnon National Highway, Calinan, Davao City</div>
        <div class="bold u">SUMMARY OF ENROLLMENT</div>
        <div>SY 2023-2024</div>
    </div>
    @foreach($acadprogs as $acadprog)
    
        @if ($acadprog->id < 5)
            <div class="bold u space text-center">{{$acadprog->progname}}  DEPARTMENT</div>
            <table width="100%" class="space table">
                <tr>
                    <td width="8.6%"></td>
                    <td width="17.6%"></td>
                    <td width="19.6%" class="text-center bold">BOYS</td>
                    <td width="19.6%" class="text-center bold">GIRLS</td>
                    <td width="19.6%" class="text-center bold">TOTAL</td>
                <td width="14.6%"></td>
            </tr>
            @foreach($acadprog->gradelevels as $gradelevel)
                @foreach($gradelevel->sections as $section)
                    <tr>
                        <td colspan="2">{{$gradelevel->levelname}}  {{$section->sectionname}}</td>
                        <td class="text-center">{{count($section->studentmale)}}</td>
                        <td class="text-center">{{count($section->studentfemale)}}</td>
                        <td class="text-center">{{count($section->total)}}</td>
                    </tr>
                @endforeach
            @endforeach
                <tr>
                    <td></td>
                    <td class="bold text-center">TOTAL</td>
                    <td class="text-center px-2"><div class="overline ">{{$acadprog->totalmale}}</div></td>
                    <td class="text-center px-2"><div class="overline ">{{$acadprog->totalfemale}}</div></td>
                    <td class="text-center px-2"><div class="overline ">{{$acadprog->total}}</div></td>
                    <td class="text-center bold">{{$acadprog->total}}</td>
                </tr>
            </table> 
        @endif
        @if ($acadprog->id == 5)
            @foreach($acadprog->sem as $sem)
                <div class="bold u space text-center">{{$acadprog->progname}} - {{$sem->semester}}</div>
                <table width="100%" class="space table">
                    <tr>
                        <td width="8.6%"></td>
                        <td width="17.6%"></td>
                        <td width="19.6%" class="text-center bold">BOYS</td>
                        <td width="19.6%" class="text-center bold">GIRLS</td>
                        <td width="19.6%" class="text-center bold">TOTAL</td>
                    <td width="14.6%"></td>
                </tr>
                @foreach($sem->sections as $section)
                    <tr>
                        <td colspan="2"> {{$section->sectionname}}</td>
                        <td class="text-center">{{count($section->studentmale)}}</td>
                        <td class="text-center">{{count($section->studentfemale)}}</td>
                        <td class="text-center">{{count($section->total)}}</td>
                    </tr>
                @endforeach
            @endforeach
            <tr>
                    <td></td>
                    <td class="bold text-center">TOTAL</td>
                    <td class="text-center px-2"><div class="overline ">{{$acadprog->totalmale}}</div></td>
                    <td class="text-center px-2"><div class="overline ">{{$acadprog->totalfemale}}</div></td>
                    <td class="text-center px-2"><div class="overline ">{{$acadprog->total}}</div></td>
                    <td class="text-center bold">{{$acadprog->total}}</td>
                </tr>
            </table> 
        @endif
        @if ($acadprog->id == 6)
            @foreach($acadprog->sem as $sem)
                <div class="bold u space text-center">{{$acadprog->progname}} - {{$sem->semester}}</div>
                <table  width="100%" class="collegeTable">
                    <thead>
                        <tr>
                            <th rowspan="2">PROGRAM / MAJOR</th>
                            <th colspan="2">FIRST YEAR</th>
                            <th colspan="2">SECOND YEAR</th>
                            <th colspan="2">THIRD YEAR</th>
                            <th colspan="2">FOURTH YEAR</th>
                            <th rowspan="2">Total</th>
                        </tr>
                        <tr>
                            <th>MALE</th>
                            <th>FEMALE</th>
                            <th>MALE</th>
                            <th>FEMALE</th>
                            <th>MALE</th>
                            <th>FEMALE</th>
                            <th>MALE</th>
                            <th>FEMALE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sem->college as $college)
                            <tr>
                                <td colspan="10" style="text-align:left;"><strong>{{$college->collegeDesc}}</strong></td>
                            </tr>
                            @foreach($college->courses as $course)
                                @php
                                    $onemale += collect($course->studentmale)->where('yearLevel', 17)->count();
                                    $onefemale += collect($course->studentfemale)->where('yearLevel', 17)->count();
                                    $twomale += collect($course->studentmale)->where('yearLevel', 18)->count();
                                    $twofemale += collect($course->studentfemale)->where('yearLevel', 18)->count();
                                    $threemale +=  collect($course->studentmale)->where('yearLevel', 19)->count();
                                    $threefemale += collect($course->studentfemale)->where('yearLevel', 19)->count();
                                    $fourmale +=  collect($course->studentmale)->where('yearLevel', 20)->count();
                                    $fourfemale += collect($course->studentfemale)->where('yearLevel', 20)->count();
                                    $totalall += count($course->total);

                                @endphp
                                <tr>
                                    <td>{{$course->courseabrv}}</td>
                                    <td>{{collect($course->studentmale)->where('yearLevel', 17)->count()}}</td>
                                    <td>{{collect($course->studentfemale)->where('yearLevel', 17)->count()}}</td>
                                    <td>{{collect($course->studentmale)->where('yearLevel', 18)->count()}}</td>
                                    <td>{{collect($course->studentfemale)->where('yearLevel', 18)->count()}}</td>
                                    <td>{{collect($course->studentmale)->where('yearLevel', 19)->count()}}</td>
                                    <td>{{collect($course->studentfemale)->where('yearLevel', 19)->count()}}</td>>
                                    <td>{{collect($course->studentmale)->where('yearLevel', 20)->count()}}</td>
                                    <td>{{collect($course->studentfemale)->where('yearLevel', 20)->count()}}</td>>
                                    <td>{{count($course->total)}} </td>
                                </tr>
                            @endforeach
                        @endforeach
                        <tr>
                            <td><strong>GRAND TOTAL</strong></td>
                            <td><strong>{{$onemale}}</strong></td>
                            <td><strong>{{$onefemale}}</strong></td>
                            <td><strong>{{$twomale}}</strong></td>
                            <td><strong>{{$twofemale}}</strong></td>
                            <td><strong>{{$threemale}}</strong></td>
                            <td><strong>{{$threefemale}}</strong></td>
                            <td><strong>{{$fourmale}}</strong></td>
                            <td><strong>{{$fourfemale}}</strong></td>
                            <td><strong>{{$totalall}}</strong></td>
                        </tr>
                    </tbody>
                </table>
            @endforeach
        @endif
    @endforeach

    <table width="100%" class="superspace">
        <tr>
            <td width="30%">Prepared by:</td>
            <td width="70%"></td>
        </tr>
        <tr>
            <td class="text-center underline space">{{auth()->user()->name}}</td>
            <td></td>
        </tr>
        <tr>
            <td class="text-center">Registrar</td>
            <td></td>
        </tr>
    </table> 

    {{-- <table width="100%" class="space table">
        <tr>
            <td width="8.6%"></td>
            <td width="17.6%"></td>
            <td width="19.6%" class="text-center bold">BOYS</td>
            <td width="19.6%" class="text-center bold">GIRLS</td>
            <td width="19.6%" class="text-center bold">TOTAL</td>
            <td width="14.6%"></td>
        </tr>
        <tr>
            <td colspan="2">Kinder</td>
            <td class="text-center">12</td>
            <td class="text-center">6</td>
            <td class="text-center">18</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td class="bold text-center">TOTAL</td>
            <td class="text-center px-2"><div class="overline ">12</div></td>
            <td class="text-center px-2"><div class="overline ">6</div></td>
            <td class="text-center px-2"><div class="overline ">18</div></td>
            <td class="text-center bold">36</td>
        </tr>
    </table> --}}
    {{-- <div class="bold u space text-center">JUNIOR HIGH SCHOOL DEPARTMENT</div>
    <table width="100%" class="space table">
        <tr>
            <td width="8.6%"></td>
            <td width="17.6%"></td>
            <td width="19.6%" class="text-center bold">BOYS</td>
            <td width="19.6%" class="text-center bold">GIRLS</td>
            <td width="19.6%" class="text-center bold">TOTAL</td>
            <td width="14.6%"></td>
        </tr>
        <tr>
            <td colspan="2">Gr. 7 - St. George</td>
            <td class="text-center">12</td>
            <td class="text-center">6</td>
            <td class="text-center">18</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td class="bold text-center">TOTAL</td>
            <td class="text-center px-2"><div class="overline ">12</div></td>
            <td class="text-center px-2"><div class="overline ">6</div></td>
            <td class="text-center px-2"><div class="overline ">18</div></td>
            <td class="text-center bold">36</td>
        </tr>
    </table>
    <table width="100%" class="space table">
        <tr>
            <td width="8.6%"></td>
            <td width="17.6%"></td>
            <td width="19.6%" class="text-center bold">BOYS</td>
            <td width="19.6%" class="text-center bold">GIRLS</td>
            <td width="19.6%" class="text-center bold">TOTAL</td>
            <td width="14.6%"></td>
        </tr>
        <tr>
            <td colspan="2">Gr. 8 - St. John Baptist</td>
            <td class="text-center">12</td>
            <td class="text-center">6</td>
            <td class="text-center">18</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td class="bold text-center">TOTAL</td>
            <td class="text-center px-2"><div class="overline ">12</div></td>
            <td class="text-center px-2"><div class="overline ">6</div></td>
            <td class="text-center px-2"><div class="overline ">18</div></td>
            <td class="text-center bold">36</td>
        </tr>
    </table>
    <table width="100%" class="space table">
        <tr>
            <td width="8.6%"></td>
            <td width="17.6%" class="bold text-center ">JHS TOTAL</td>
            <td width="58.8"></td>
            <td width="14.6%" class="bold text-center ">72</td>
        </tr>
    </table>
    <div class="bold u space text-center">SENIOR HIGH SCHOOL DEPARTMENT</div>
    <table width="100%" class="space table">
        <tr>
            <td width="8.6%"></td>
            <td width="17.6%"></td>
            <td width="19.6%" class="text-center bold">BOYS</td>
            <td width="19.6%" class="text-center bold">GIRLS</td>
            <td width="19.6%" class="text-center bold">TOTAL</td>
            <td width="14.6%"></td>
        </tr>
        <tr>
            <td colspan="2">STEM 11-ST</td>
            <td class="text-center">12</td>
            <td class="text-center">6</td>
            <td class="text-center">18</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td class="bold text-center">TOTAL-Grade 11</td>
            <td class="text-center px-2"><div class="overline ">12</div></td>
            <td class="text-center px-2"><div class="overline ">6</div></td>
            <td class="text-center px-2"><div class="overline ">18</div></td>
            <td class="text-center bold">36</td>
        </tr>
    </table> --}}
    {{-- <table width="100%" class="space table">
        <tr>
            <td width="8.6%"></td>
            <td width="17.6%"></td>
            <td width="19.6%" class="text-center bold">BOYS</td>
            <td width="19.6%" class="text-center bold">GIRLS</td>
            <td width="19.6%" class="text-center bold">TOTAL</td>
            <td width="14.6%"></td>
        </tr>
        <tr>
            <td colspan="2">STEM 12-OLA</td>
            <td class="text-center">12</td>
            <td class="text-center">6</td>
            <td class="text-center">18</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td class="bold text-center">TOTAL-Grade 12</td>
            <td class="text-center px-2"><div class="overline ">12</div></td>
            <td class="text-center px-2"><div class="overline ">6</div></td>
            <td class="text-center px-2"><div class="overline ">18</div></td>
            <td class="text-center bold">36</td>
        </tr>
    </table>
    <table width="100%" class="space table">
        <tr>
            <td width="8.6%"></td>
            <td width="17.6%" class="bold text-center ">SHS TOTAL</td>
            <td width="58.8"></td>
            <td width="14.6%" class="bold text-center ">36</td>
        </tr>
    </table>
    <table width="100%" class="text-center space bold">
        <tr>
            <td width="84.8%">T O T A L . . . . . . . . . . . . . Grade School + Junior High  + Senior High</td>
            <td width="14.6%" class="bold text-center "><div class="underline ">144</div></td>
        </tr>
        <tr>
            <td></td>
            <td width="14.6%" class="bold text-center "><div class="subspace underline "></div></td>
        </tr>
    </table>
    <table width="100%" class="space">
        <tr>
            <td width="60%"></td>
            <td width="40%" class=" text-center ">as of August 31, 2023</td>
        </tr>
    </table>
    <table width="100%" class="superspace">
        <tr>
            <td width="30%">Prepared by:</td>
            <td width="70%"></td>
        </tr>
        <tr>
            <td class="text-center underline space"></td>
            <td></td>
        </tr>
        <tr>
            <td class="text-center">B. ed Registrar</td>
            <td></td>
        </tr>
    </table> --}}
</body>
</html>