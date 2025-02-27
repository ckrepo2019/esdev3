<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <title>{{$student->firstname.' '.$student->middlename[0].' '.$student->lastname}}</title> -->
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
        @page { size: 11in 8.5in; margin: 10px 20px;  }
        
    </style>
</head>
<body>
<table style="width: 100%; table-layout: fixed;">
    <tr>
        <td width="50%" style="vertical-align: top; padding-right: 50px;">
            <table width="100%" style="table-layout: fixed;">
                <tr>
                    <td class="text-center" style="font-size: 23px"> <b>ATTENDANCE RECORD</b></td>
                </tr>
            </table>
            <table width="100%" class="table-bordered" style="table-layout: fixed;">
                <tr>
                    <td width="60%" class="text-center" style="padding-bottom: 10px;">&nbsp;</td>
                    <td width="10%" class="text-center" style="vertical-align: top;"><b>Q1</b></td>
                    <td width="10%" class="text-center" style="vertical-align: top;"><b>Q2</b></td>
                    <td width="10%" class="text-center" style="vertical-align: top;"><b>Q3</b></td>
                    <td width="10%" class="text-center" style="vertical-align: top;"><b>Q4</b></td>
                </tr>
                <tr>
                    <td width="60%" class="text-center" style="font-size: 14px; padding-bottom: 10px;">Days Present</td>
                    <td width="10%" class="text-center" style=""></td>
                    <td width="10%" class="text-center" style=""></td>
                    <td width="10%" class="text-center" style=""></td>
                    <td width="10%" class="text-center" style=""></td>
                </tr>
                <tr>
                    <td width="60%" class="text-center" style="font-size: 14px; padding-bottom: 10px;">Days Absent</td>
                    <td width="10%" class="text-center" style=""></td>
                    <td width="10%" class="text-center" style=""></td>
                    <td width="10%" class="text-center" style=""></td>
                    <td width="10%" class="text-center" style=""></td>
                </tr>
                <tr>
                    <td width="60%" class="text-center" style="font-size: 14px; padding-bottom: 10px;">Days Tardy</td>
                    <td width="10%" class="text-center" style=""></td>
                    <td width="10%" class="text-center" style=""></td>
                    <td width="10%" class="text-center" style=""></td>
                    <td width="10%" class="text-center" style=""></td>
                </tr>
                <tr>
                    <td width="60%" class="text-center" style="font-size: 14px; padding-bottom: 10px;">No. Schools of Days</td>
                    <td width="10%" class="text-center" style=""></td>
                    <td width="10%" class="text-center" style=""></td>
                    <td width="10%" class="text-center" style=""></td>
                    <td width="10%" class="text-center" style=""></td>
                </tr>
            </table>
            <table width="100%" style="table-layout: fixed; padding-left: 20px; padding-right: 20px; margin-top: 50px; font-size: 15.5px;">
                <tr>
                    <td width="35%" class="text-left p-0" style="">This is to certify that</td>
                    <td width="65%" class="text-center p-0" style="border-bottom: 1px solid #000;"></td>
                </tr>
            </table>
            <table width="100%" style="table-layout: fixed; padding-left: 60px; padding-right: 60px; font-size: 15.5px;">
                <tr>
                    <td width="6%" class="text-left p-0" style="">Of</td>
                    <td width="34%" class="text-center p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="60%" class="text-left p-0" style="">&nbsp;has developed the general</td>
                </tr>
            </table>
            <table width="100%" style="table-layout: fixed; padding-left: 20px; padding-right: 20px; font-size: 15.5px;">
                <tr>
                    <td width="100%" class="text-left p-0" style="">competencies based on the Kindergarten Curriculum Guide</td>
                </tr>
            </table>
            <table width="100%" style="table-layout: fixed; padding-left: 80px; padding-right: 80px; font-size: 15.5px; margin-top: 170px;">
                <tr>
                    <td width="100%" class="text-center p-0" style="border-bottom: 1px solid #000;"></td>
                </tr>
                <tr>
                    <td width="100%" class="text-center p-0" style="padding-top: 10px!important;"><b>Teacher's Signature</b></td>
                </tr>
            </table>
            <table width="100%" style="table-layout: fixed; padding-left: 80px; padding-right: 80px; font-size: 15.5px; margin-top: 150px;">
                <tr>
                    <td width="100%" class="text-center p-0" style=""><b><u>Sr. Josephine C. Sambrano, O.P.</u></b></td>
                </tr>
                <tr>
                    <td width="100%" class="text-center p-0" style="">School Principal & Signature</td>
                </tr>
            </table>
        </td>
        <td width="50%" style="vertical-align: top;">
            <table width="100%" style="table-layout: fixed; margin-top: 30px; font-size: 11px">
                <tr>
                    <td width="15%" class="text-left" valign="middle" style="margin-top: 15px!important;">
                        <!-- <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="130px"> -->
                        <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="75px">
                    </td>
                    <td width="85%" valign="top">
                        <div class="text-center">Republic of the Philippines</div>
                        <div class="text-center" style="font-size: 14px!important">Department of Education</div>
                        <div class="text-center">Division of Zamboanga City</div>
                        <div class="text-center" style="font-size: 23px!important;"><b>Saint Joseph School Foundation Inc.</b></div>
                        <div class="text-center">Gov. Camins Avenue, Zamboanga City</div>
                    </td>
                </tr>
                
                
            </table>
            <table width="100%" style="table-layout: fixed; margin-top: 10px;">
                <tr>
                    <td width="18%" class="text-left" valign="bottom">
                    </td>
                    <td width="72%" valign="top">
                        <div class="text-center" style="font-size: 24px; color: green;"><b>Kindergarten Progress Report</b></div>
                        <div class="text-center" style=" margin-top: 30px; font-size: 25px; border-bottom: 1px solid #000;">{{$schoolyear->sydesc}}</div>
                        <div class="text-center" style="font-size: 15px;">School Year</div>
                    </td>
                    <td width="10%" class="text-left" valign="bottom">
                    </td>
                </tr>
            </table>
            <table width="100%" style="table-layout: fixed; margin-top: 40px;">
                <tr>
                    <td width="15%" valign="top">
                    </td>
                    <td width="75%" class="text-center" valign="top">
                        <img src="{{base_path()}}/public/assets/images/sjsfi/kids.PNG" alt="school" width="300px">
                    </td>
                    <td width="10%" class="text-left" valign="bottom">
                    </td>
                </tr>
            </table>
            <br>
            <table width="100%" style="table-layout: fixed;">
                <tr>
                    <td width="10%" class="text-left" valign="top">
                    </td>
                    <td width="80%" valign="top">
                        <div class="text-center" style="margin-top: 20px; font-size: 25px; border-bottom: 1px solid #000;">{{$student->firstname.' '.$student->middlename[0].'. '.$student->lastname}}</div>
                        <div class="text-center" style="margin-top: 10px; font-size: 20px;"><b>Name</b></div>
                        <div class="text-center" style="margin-top: 60px; font-size: 25px; border-bottom: 1px solid #000;">{{$section->sectionname}}</div>
                        <div class="text-center" style="margin-top: 10px; font-size: 20px;"><b>Section</b></div>
                        <div class="text-center" style="margin-top: 70px; font-size: 25px; border-bottom: 1px solid #000;">{{$adviser}}</div>
                        <div class="text-center" style="margin-top: 10px; font-size: 20px;"><b>Teacher</b></div>
                    </td>
                    <td width="10%" class="text-left" valign="bottom">
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed;">
    <tr>
        <td width="50%" style="vertical-align: top; padding-right: 30px">
            <table width="100%" class="table-bordered" style="table-layout: fixed; font-size: 8.3px;">
                <tr style="background-color: #9ee4f0;">
                    <td width="60%" class="text-center" style="font-size: 10px!important; letter-spacing: 0px;"><b>Health, Well-Being, and Motor Development</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px!important;"><b>Q1</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px!important;"><b>Q2</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px!important;"><b>Q3</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px!important;"><b>Q4</b></td>
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="padding-left: 1.5px;">Demonstrates health habits that keep one clean and sanitary</td>
                    @foreach(collect($setup)->where('sort','AA')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="padding-left: 1.5px;">Demonstrates behaviors that promote personal safety</td>
                    @foreach(collect($setup)->where('sort','AB')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="padding-left: 1.5px;">Demonstrates locomotor skills (walking, running, skipping, jumping, climbing correctly during play, dance or exercise activities)</td>
                    @foreach(collect($setup)->where('sort','AC')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="padding-left: 1.5px;">Demonstrates non-locomotor skills (pushing, pulling turning, swaying, bending throwing, catching, etc.)</td>
                    @foreach(collect($setup)->where('sort','AD')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="padding-left: 1.5px;">Demonstrates fine motor skills needed for self-care/self-help (toothbrushing, buttoning, screwing lids, using spoon and fork, etc.)</td>
                    @foreach(collect($setup)->where('sort','AE')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="padding-left: 1.5px;">Traces, copies, or writes letters & numerals</td>
                    @foreach(collect($setup)->where('sort','AF')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr style="background-color: #9ee4f0;">
                    <td width="60%" class="text-center p-0" style="font-size: 10px!important; letter-spacing: 0px;"><b>Socio Emotional Developent</b></td>
                    <td width="10%" class="text-center p-0" style="font-size: 12px!important;"><b>Q1</b></td>
                    <td width="10%" class="text-center p-0" style="font-size: 12px!important;"><b>Q2</b></td>
                    <td width="10%" class="text-center p-0" style="font-size: 12px!important;"><b>Q3</b></td>
                    <td width="10%" class="text-center p-0" style="font-size: 12px!important;"><b>Q4</b></td>
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="padding-left: 1.5px;">States personal information (name, age, gender, birthday, etc.)</td>
                    @foreach(collect($setup)->where('sort','BA')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="padding-left: 1.5px;">Expresses personal interest and needs</td>
                    @foreach(collect($setup)->where('sort','BB')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="padding-left: 1.5px;">Demonstrates readiness in trying out new experiences, and self confidence in doing tasks independently</td>
                    @foreach(collect($setup)->where('sort','BC')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="padding-left: 1.5px;">Expresses feelings in appropriate ways and in different situations</td>
                    @foreach(collect($setup)->where('sort','BD')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="padding-left: 1.5px; font-size: 8px;">Follow school rules willingly and executes school task and routines well</td>
                    @foreach(collect($setup)->where('sort','BE')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="padding-left: 1.5px;">Shows respect in dealing with peers and adults</td>
                    @foreach(collect($setup)->where('sort','BF')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="padding-left: 1.5px;">Identifies members of one's family</td>
                    @foreach(collect($setup)->where('sort','BG')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="padding-left: 1.5px;">Identifies people and places in the school and community</td>
                    @foreach(collect($setup)->where('sort','BH')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr style="background-color: #9ee4f0;">
                    <td width="60%" class="text-center p-0" style="font-size: 13px!important; letter-spacing: 0px;"><b>Language, Literacy and Communication</b></td>
                    <td width="10%" class="text-center p-0" style="font-size: 12px!important;"><b>Q1</b></td>
                    <td width="10%" class="text-center p-0" style="font-size: 12px!important;"><b>Q2</b></td>
                    <td width="10%" class="text-center p-0" style="font-size: 12px!important;"><b>Q3</b></td>
                    <td width="10%" class="text-center p-0" style="font-size: 12px!important;"><b>Q4</b></td>
                </tr>
                <tr>
                    <td width="60%" colspan="5" class="text-center" style="padding-left: 1.5px; font-size: 9px!important;"><b>Listening and Viewing</b></td>
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="padding-left: 1.5px;">Distinguishes between elements of sounds (pitch, volume, etc.)</td>
                    @foreach(collect($setup)->where('sort','CAA')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="padding-left: 1.5px;">Listens attentively to stories/poems/songs</td>
                    @foreach(collect($setup)->where('sort','CAB')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="padding-left: 1.5px;">Recalls details from stories/poems/songs listened to</td>
                    @foreach(collect($setup)->where('sort','CAC')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="padding-left: 1.5px;">Relate story events to personal experiences</td>
                    @foreach(collect($setup)->where('sort','CAD')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="padding-left: 1.5px;">Sequence events from as story listened to</td>
                    @foreach(collect($setup)->where('sort','CAE')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="padding-left: 1.5px;">Infer character traits and feelings</td>
                    @foreach(collect($setup)->where('sort','CAF')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="padding-left: 1.5px;">Identify simple cause and effect and problem solution ralationship of events in a story listened to or in a familiar situation</td>
                    @foreach(collect($setup)->where('sort','CAG')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="padding-left: 1.5px;">Predict story outcomes</td>
                    @foreach(collect($setup)->where('sort','CAH')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="padding-left: 1.5px;">Discriminate objects/pictures as same and different, identifies missing parts, and identifies which objects do not belong to a group</td>
                    @foreach(collect($setup)->where('sort','CAI')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" colspan="5" class="text-center" style="padding-left: 1.5px; font-size: 9px!important;"><b>Speaking</b></td>
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="padding-left: 1.5px; font-size: 7.7px!important;">Uses proper expressions and polite greetings in appropriate situations</td>
                    @foreach(collect($setup)->where('sort','CBA')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="padding-left: 1.5px;">Talks about details of objects, people, etc, using appropriate speaking vocabulary</td>
                    @foreach(collect($setup)->where('sort','CBB')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="padding-left: 1.5px;">Participates actively in class activities and discussions by responding to questions accordingly</td>
                    @foreach(collect($setup)->where('sort','CBC')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="padding-left: 1.5px;">Gives 1 to 2 steps directions</td>
                    @foreach(collect($setup)->where('sort','CBD')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="padding-left: 1.5px;">Retells simple stories or stories or narrates personal experiences</td>
                    @foreach(collect($setup)->where('sort','CBE')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr style="background-color: #9ee4f0;">
                    <td width="60%" class="text-center p-0" style="font-size: 13px!important; letter-spacing: 0px;"></td>
                    <td width="10%" class="text-center p-0" style="font-size: 12px!important;"><b>Q1</b></td>
                    <td width="10%" class="text-center p-0" style="font-size: 12px!important;"><b>Q2</b></td>
                    <td width="10%" class="text-center p-0" style="font-size: 12px!important;"><b>Q3</b></td>
                    <td width="10%" class="text-center p-0" style="font-size: 12px!important;"><b>Q4</b></td>
                </tr>
                <tr>
                    <td width="60%" colspan="5" class="text-center" style="padding-left: 1.5px; font-size: 9px!important;"><b>Reading</b></td>
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="padding-left: 1.5px;">Identifies sounds of letters (using the alphabet of the Mother Tongue)</td>
                    @foreach(collect($setup)->where('sort','CCA')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="padding-left: 1.5px;">Names uppercase and lower case letters(using the alphabet of the Mother Tongue)</td>
                    @foreach(collect($setup)->where('sort','CCB')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="padding-left: 1.5px;">Matches uppercase and lower case letters (Mother Tongue alphabet)</td>
                    @foreach(collect($setup)->where('sort','CCC')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="padding-left: 1.5px;">Identifies beginning sound of a given word</td>
                    @foreach(collect($setup)->where('sort','CCD')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="padding-left: 1.5px;">Distinguishes words that rhyme</td>
                    @foreach(collect($setup)->where('sort','CCE')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="padding-left: 1.5px;">Count syllables in a given word</td>
                    @foreach(collect($setup)->where('sort','CCF')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="padding-left: 1.5px;">Identifies parts of the book (front, back, title, author, etc.)</td>
                    @foreach(collect($setup)->where('sort','CCG')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="padding-left: 1.5px;">Shows interest in reading by browsing through books, predicting what the story is all about and demonstrating proper book handling behaviour</td>
                    @foreach(collect($setup)->where('sort','CCH')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="padding-left: 1.5px;">Interprets information from simple pictographs, maps, and other environment print</td>
                    @foreach(collect($setup)->where('sort','CCI')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" colspan="5" class="text-center" style="padding-left: 1.5px; font-size: 9px!important;"><b>Writing</b></td>
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="padding-left: 1.5px;">Express simple ideas through symbols (drawing, invented spelling)</td>
                    @foreach(collect($setup)->where('sort','CDA')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="padding-left: 1.5px;">Write one's given name</td>
                    @foreach(collect($setup)->where('sort','CDB')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="padding-left: 1.5px;">Writes lower case and upper case letters</td>
                    @foreach(collect($setup)->where('sort','CDC')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>

            </table>
        </td>
        <td width="50%" style="vertical-align: top; padding-left: 20px;">
            <table width="100%" class="table-bordered" style="table-layout: fixed; font-size: 9px;">
                <tr style="background-color: #9ee4f0;">
                    <td width="60%" class="text-center" style="font-size: 13px!important;"><b>Mathematics</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px!important;"><b>Q1</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px!important;"><b>Q2</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px!important;"><b>Q3</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px!important;"><b>Q4</b></td>
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="">Identifies colors</td>
                    @foreach(collect($setup)->where('sort','DA')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="">Identifies shapes</td>
                    @foreach(collect($setup)->where('sort','DB')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="">Sorts objects according to shape, size, and color</td>
                    @foreach(collect($setup)->where('sort','DC')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="">Compares and arranges objects according to a specific  attribute (size, shape, length, quantity, etc.)</td>
                    @foreach(collect($setup)->where('sort','DD')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="">Recognizes and extends patterns</td>
                    @foreach(collect($setup)->where('sort','DE')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="">Tells the name of the days in a week</td>
                    @foreach(collect($setup)->where('sort','DF')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="">Tells the months of the year</td>
                    @foreach(collect($setup)->where('sort','DG')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="">Distinguishes the time of day and tells time by the hour (analog clock)</td>
                    @foreach(collect($setup)->where('sort','DH')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="">Rote counts up to 20 (recognize numbers)</td>
                    @foreach(collect($setup)->where('sort','DI')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="">Counts objects up to 10</td>
                    @foreach(collect($setup)->where('sort','DJ')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="">Writes numerals up to 10</td>
                    @foreach(collect($setup)->where('sort','DK')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="">Sequences numbers</td>
                    @foreach(collect($setup)->where('sort','DL')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="">Identify the placement of objects iin a given set (1st, 2nd, 3rd...)</td>
                    @foreach(collect($setup)->where('sort','DM')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="">Solve simple addition problem</td>
                    @foreach(collect($setup)->where('sort','DN')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="">Solve simple substraction problems</td>
                    @foreach(collect($setup)->where('sort','DO')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="">Group sets of concrete objects of equal quantities up to 10 (beginning division)</td>
                    @foreach(collect($setup)->where('sort','DP')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="">Separates sets of concrete objects of equal quantities (beginning division)</td>
                    @foreach(collect($setup)->where('sort','DQ')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="">Measures length, capacity, and mass objects using non-standard measuring tools</td>
                    @foreach(collect($setup)->where('sort','DR')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="">Recognizes coins and bills up to P20</td>
                    @foreach(collect($setup)->where('sort','DS')->first()->grade as $item)
                        <td width="10%" class="text-center" style="">{{$item->q1grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q2grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q3grade}}</td>
                        <td width="10%" class="text-center" style="">{{$item->q4grade}}</td>
                    @endforeach
                </tr>
            </table>
            <table width="100%" class="" style="table-layout: fixed; margin-top: 10px;">
                <tr>
                    <td width="100%" class="text-center" style="font-size: 24px;"><b>Rating Scale</b></td>
                </tr>
            </table>
            <table width="100%" class="table-bordered" style="table-layout: fixed; padding-left: 20px; padding-right: 20px; margin-top: 10px;">
                <tr>
                    <td width="100%" class="text-center" style="font-size: 13px; background-color: #9ee4f0;">
                        <div><b>Beginning (B)</b></div>
                        <div>Rarely Demonstrates the expected competency;</div>
                        <div>Rarely participates in class activities and/or initiates independent work</div>
                        <div>shows interest in doing tasks but needs close supervision</div>
                    </td>
                </tr>
                <tr>
                    <td width="100%" class="text-center" style="font-size: 13px; background-color: #2ea0b4;">
                        <div><b>Developing (D)</b></div>
                        <div>Sometimes demontrates the competency</div>
                        <div>Sometimes participate, minimal supervision</div>
                        <div>Progresses continuously in doing assigned tasks.</div>
                    </td>
                </tr>
                <tr>
                    <td width="100%" class="text-center" style="font-size: 13px; background-color: #3d307d;">
                        <div><b>Consistent (C)</b></div>
                        <div>Always demonstrates the expected competency;</div>
                        <div>Always participates in the different activities, works independently, Always</div>
                        <div>performs task, advanced in some aspects.</div>
                    </td>
                </tr>
            </table> 
        </td>
    </tr>
</table>
</body>
</html>