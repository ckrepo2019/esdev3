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
            padding-bottom: .4px !important;
            padding-top: .4px !important;
        }
        .tdleft {
            padding-left: 5px!important;
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
            transform-origin: 14 26;
            transform: rotate(-90deg);
        }
        .asidetotal {
            /* background: #48b4e0; */
            color: #000;
            line-height: 15px;
            height: 35px;
            border: 1px solid #000!important;
            
        }
        .asidetotal span {
            /* Abs positioning makes it not take up vert space */
            /* position: absolute; */
            top: 0;
            left: 0;

            /* Border is the new background */
            background: none;

            /* Rotate from top left corner (not default) */
            transform-origin: 24 16;
            transform: rotate(-90deg);
        }
        .asideno {
            /* background: #48b4e0; */
            color: #000;
            line-height: 15px;
            height: 35px;
            border: 1px solid #000!important;
            
        }
        .asideno span {
            /* Abs positioning makes it not take up vert space */
            /* position: absolute; */
            top: 0;
            left: 0;

            /* Border is the new background */
            background: none;

            /* Rotate from top left corner (not default) */
            transform-origin: 25 16;
            transform: rotate(-90deg);
        }
        .trhead {
            background-color: rgb(167, 223, 167); 
            color: #000; font-size;
        }
        .trhead td {
            border: 1px solid #000;
        }
		 .check_mark {
               font-family: ZapfDingbats, sans-serif;
            }
        @page { size: 210mm 297mm; margin: .1in 0in;}
        
    </style>
</head>
<body>
    <table class="table table-sm mb-0" style="width: 100%; table-layout: fixed; margin-left: .8in; margin-right: .1in;">
        <tr>
            <td class="" width="20%" style="text-align: left; padding-top: 25px!important;">
                <img style="" src="{{base_path()}}/public/assets/images/mslu/msllogo.png" alt="school" width="110px">
            </td>
            <td width="50%" class="" style="text-align: center; font-size: 14px;">
                <div style="width: 100%;"><b>REPUBLIC OF THE PHILIPPINES</b></div>
                <div style="width: 100%;"><b>DEPARTMENT OF EDUCATION</b></div>
                <div style="width: 100%;"><u><b>XI</b></u></div>
                <div style="width: 100%; font-size: 9px;">Region</div>
                <div style="width: 100%; margin-top: 2px;"><u><b>DAVAO ORIENTAL</b></u></div>
                <div style="width: 100%; font-size: 9px;">Division</div>
                <div style="width: 100%; font-size: 18px; margin-top: 5px;"><u><b>MARYKNOLL SCHOOL OF LUPON, INC.</b></u></div>
                <div style="width: 100%; font-size: 9px;">School</div>
                <div style="width: 100%; font-size: 16px; margin-top: 20px;"><b>KINDERGARTEN PROGRESS REPORT</b></div>
                <div style="width: 100%; font-size: 9px;"><b>SY {{$schoolyear->sydesc}}</b></div>
            </td>
            <td width="30%">
                <div style="width: 100%; font-size: 9px; border: 1px solid #000; font-size: 14px; height: 20px;"><span style="line-height: 18px">&nbsp;LRN: {{$student->lrn}}</span></div>
            </td>
        </tr>
    </table>
    <table class="table table-sm mb-0" style="width: 100%; table-layout: fixed; margin-left: .8in; margin-right: .8in; margin-top: 20px;">
        <tr>
            <td width="6.5%" class="text-left p-0" style="font-size: 13px;">Name: </td>
            <td width="50.5%" class="text-left p-0" style="font-size: 13px; border-bottom: 1px solid #000;">{{$student->firstname.' '.$student->middlename[0].'. '.$student->lastname}}</td>
            <td width="43%" class="text-left p-0" style="font-size: 13px;">&nbsp;Grade and Section: <u>{{$student->levelname}} {{$student->sectionname}}</u></td>
        </tr>
    </table>
    <table class="table table-sm mb-0" style="width: 100%; table-layout: fixed; margin-left: .8in; margin-right: .8in; margin-top: 4px;">
        <tr>
            <td width="8.5%" class="text-left p-0" style="font-size: 13px;">Teacher: </td>
            <td width="91.5%" class="text-left p-0" style="font-size: 13px;"><u>SR. MA. GILDA B. GUARDIANO, OP</u></td>
        </tr>
    </table>
    <table class="table table-sm mb-0" style="width: 100%; table-layout: fixed; margin-left: .8in; margin-right: .8in; margin-top: 4px;">
        <tr>
            <td width="43%" class="text-left p-0" style="font-size: 13px;">Age of Child at the Beginning of the SY:</td>
            <td width="5%" class="text-left p-0" style="font-size: 13px;">Year</td>
            <td width="5%" class="text-left p-0" style="font-size: 13px; border-bottom: 1px solid #000;"></td>
            <td width="5%" class="text-left p-0" style="font-size: 13px;"></td>
            <td width="7%" class="text-left p-0" style="font-size: 13px;">Months</td>
            <td width="5%" class="text-left p-0" style="font-size: 13px; border-bottom: 1px solid #000;"></td>
            <td width="30%" class="text-left p-0" style="font-size: 13px;"></td>
        </tr>
    </table>
    <table class="table table-sm mb-0" style="width: 100%; table-layout: fixed; margin-left: .8in; margin-right: .8in; margin-top: 4px;">
        <tr>
            <td width="43%" class="text-left p-0" style="font-size: 13px;">Age of Child at the End of the SY:</td>
            <td width="5%" class="text-left p-0" style="font-size: 13px;">Year</td>
            <td width="5%" class="text-left p-0" style="font-size: 13px; border-bottom: 1px solid #000;"></td>
            <td width="5%" class="text-left p-0" style="font-size: 13px;"></td>
            <td width="7%" class="text-left p-0" style="font-size: 13px;">Months</td>
            <td width="5%" class="text-left p-0" style="font-size: 13px; border-bottom: 1px solid #000;"></td>
            <td width="30%" class="text-left p-0" style="font-size: 13px;"></td>
        </tr>
    </table>
    <table class="table table-sm mb-0" style="width: 100%; table-layout: fixed; margin-left: .8in; margin-right: .8in; margin-top: 8px; padding-left: 5px!important;">
        <tr>
            <td style="font-size: 11.8px;">
                <div style="text-align: center; border: 1px solid #000; padding-top: 20px!important; padding-bottom: 60px!important; padding-left: 15px!important; padding-right: 10px!important;">
                    <i>
                        <span>The purpose of this progress report is to inform parents about their child’s learning achievement based on the</span><br>
                        <span>Kindergarten Curriculum Guide. This reflects a summary of your child’s learning performance. It identifies</span><br>
                        <span>your child’s levels of progress in different domains of development (not necessarily academic) every ten (10)</span>
                        <span>weeks or quarter so that we know if additional time and follow-up are needed to make your child achieve the</span>
                        competencies expected of a five (5) year old.
                    </i>
                </div>
            </td>
        </tr>
    </table>
    <table class="table table-sm mb-0" style="width: 100%; table-layout: fixed; margin-left: .8in; margin-right: .8in;">
        <tr>
            <td width="100%" class="text-left p-0" style="font-size: 15px;"><b>&nbsp;&nbsp;&nbsp;&nbsp;Each competency will be marked with: Beginning (B);Developing (D) or; Consistent (C)</b></td>
        </tr>
    </table>
    <table class="table table-sm table-bordered mb-0" style="width: 100%; table-layout: fixed; margin-left: .84in; margin-right: .84in; margin-top: 14px; padding-left: 5px!important;">
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 12.8px;"><b>Health, Well-Being, and Motor Development</b></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"><b>Q1</b></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"><b>Q2</b></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"><b>Q3</b></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"><b>Q4</b></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Demonstrates health habits that keep one clean and sanitary</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Demonstrates behaviors that promote personal safety</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Demonstrates locomotor skills such as walking, running, skipping, jumping, climbing correctly
                during play, dance or exercise activities</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Demonstrates non-locomotor skills such as pushing, pulling, turning, swaying, bending,
                throwing, catching, and kicking correctly during play, dance or exercise activities
                </td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Demonstrates fine motor skills needed for self-care/self-help such as toothbrushing, buttoning,
                screwing and unscrewing lids, using spoon and fork correctly, etc.</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Demonstrates fine motor skills needed for creative self-expression/art activities, such as
                tearing, cutting, pasting, copying, drawing, coloring, molding, painting, lacing, atc.</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Traces, copies, or writes letters and numerals</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0" style="font-size: 12.8px;"><b>&nbsp;Socioemotional Development</b></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"><b>Q1</b></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"><b>Q2</b></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"><b>Q3</b></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"><b>Q4</b></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">States personal information (name, gender, age, birthday)</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Expresses personal interests and needs</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Demonstrates readiness in trying out new experiences, and self-confidence in doing tasks
                independently</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Expresses feelings in appropriate ways and in different situations</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Follows school rules willingly and executes school tasks and routines well</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Recognizes different emotions, acknowledges the feelings of others, and shows willingness tohelp</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Shows respect in dealing with peers and adults</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Identifies members of one’s family</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Identifies people and places in the school and community</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0" style="font-size: 12.8px;"><b>&nbsp;Language, Literacy, and Communication</b></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"><b>Q1</b></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"><b>Q2</b></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"><b>Q3</b></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"><b>Q4</b></td>
        </tr>
        <tr>
            <td class="text-left p-0" style="font-size: 12.8px;"><b><i>&nbsp;Listening and Viewing</i></b></td>
            <td colspan="4" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Distinguishes between elements of sounds e.g. pitch (low, high), volume (loud and soft)</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Listens attentively to stories/poems/songs</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Recalls details from stories/poems/songs listened to</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Relate story events to personal experiences</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Sequence events from a story listened to</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Infer character traits and feelings</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Identify simple cause-and-effect and problem-solution relationship of events in a story listened
                to or in a familiar situation</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Predict story outcomes</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Discriminates objects/pictures as same and different, identifies missing parts of
                objects/pictures, and identifies which objects do not belong to the group</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td class="text-left p-0" style="font-size: 12.8px;"><b><i>&nbsp;Speaking</i></b></td>
            <td colspan="4" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Uses proper expressions in and polite greetings in appropriate situations</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Talk about details of objects, people, etc. using appropriate speaking vocabulary</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Participates actively in class activities (e.g., reciting poems, rhymes, etc.) and discussions by
                responding to questions accordingly</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Asks simple questions (who, what, where, when, why)</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Gives 1 to 2 step directions</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Retells simple stories or narrates personal experiences</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td class="text-left p-0" style="font-size: 12.8px;"><b><i>&nbsp;Reading</i></b></td>
            <td colspan="4" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Identifies sounds of letters (using the alphabet of the Mother Tongue)</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td colspan="5" class="text-center p-0" style="font-size: 11.8px;">
                <span>The child can identify the following letter sounds:</span><br>
                <span>/a/ /b/ /c/ /d/ /e/ /f/ /g/ /h/ /i/ /j/ /k/ /l/ /m/ /n/ /ñ/ /ng/ /o/ /p/ /q/ /r/ /s/ /t/ /u/ /v/ /w/ /x/ /y/ /z/</span>
            </td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Names uppercase and lower case letters (using the alphabet of the Mother Tongue)</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td colspan="5" class="text-center p-0" style="font-size: 11.8px;">
                <span>This child can name the following uppercase and lower case letters:</span><br>
                <span style="font-size: 12px;">A B C D E F G H I J K L M N Ñ NG O P Q R S T U V W X Y Z a b c d e f g h I j k l m n ñ ng o p q r s t u v w x y z</span>
            </td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Matches uppercase and lower case letters (using the alphabet of the Mother Tongue)</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Identifies beginning sound of a given word</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0" style="font-size: 11.8px;"><b></b></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"><b>Q1</b></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"><b>Q2</b></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"><b>Q3</b></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"><b>Q4</b></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Distinguishes words that rhyme</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Counts syllables in a given word</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Identifies parts of the book (front and back, title, author, illustrator, etc.)</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Shows interest in reading by browsing through books, predicting what the story is all about
                and demonstrating proper book handling behavior (e.g., flip pages sequentially, browses from
                left to right, etc.)</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Interprets information from simple pictographs, maps, and other environmental print</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td class="text-left p-0" style="font-size: 12.8px;"><b><i>&nbsp;Writing</i></b></td>
            <td colspan="4" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Writes one’s given name</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Writes lower case and upper case letters</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Express simple ideas through symbols (e.g., drawings, invented spelling)</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td class="text-left p-0" style="font-size: 12.8px;"><b>&nbsp;Mathematics</b></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"><b>Q1</b></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"><b>Q2</b></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"><b>Q3</b></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"><b>Q4</b></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Identifies colors</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Identifies shapes</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Sorts objects according to shape, size, and/or color</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Compares and arrange objects according to a specific attribute (e.g., size, length, quantity, or
                duration)</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Recognizes and extends patterns</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Tells the names of days in a week</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Tells the months of the year</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Distinguishes the time of day and tells time by the hour (using analog clock)</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Rote counts up to 20</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td colspan="5" class="text-left p-0" style="font-size: 11.8px;">The child can count up to: 1 2 3 4 5 6 7 8 9 10 11 12 13 14 15 16 17 18 19 20 Others: ____________</td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Counts objects up to 10</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td colspan="5" class="text-left p-0" style="font-size: 11.8px;">The child can count up to: 1 2 3 4 5 6 7 8 9 10 Others: ____________</td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Recognize numerals up to 10</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td colspan="5" class="text-left p-0" style="font-size: 11.8px;">The child can recognize numerals up to: 1 2 3 4 5 6 7 8 9 10 Others: ____________</td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Writes numerals up to 10</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td colspan="5" class="text-left p-0" style="font-size: 11.8px;">The child can write numerals up to: 1 2 3 4 5 6 7 8 9 10 Others: ____________</td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Sequences numbers</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Identify the placement of objects (e.g. 1st, 2nd, 3rd, etc.) in a given set</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Solves simple addition problems</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td class="text-left p-0" style="font-size: 11.8px;"><b></b></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"><b>Q1</b></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"><b>Q2</b></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"><b>Q3</b></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"><b>Q4</b></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Solves simple subtraction problems</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Groups sets of concrete objects of equal quantities up to 10 (i.e., beginning multiplication)</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Separates sets of concrete objects of equal quantities up to 10 (i.e., beginning division)</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Measures length, capacity, and mass of objects using nonstandard measuring tools</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Recognizes coins and bills (up to PHP 20)</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td colspan="5" class="text-center p-0" style="font-size: 11.8px;">
                <span>The child can recognize the following coins and bills:</span><br>
                <span>5 centavos 10 centavos 25 centavos 1 peso 5 pesos 10 pesos 20 pesos</span>
            </td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Understanding the Physical and Natural Environment</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0" style="font-size: 12.8px;"><b>&nbsp;Identifies body parts and their functions </b></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"><b>Q1</b></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"><b>Q2</b></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"><b>Q3</b></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"><b>Q4</b></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Records observations and data with pictures, numbers and/or symbols</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Identifies parts of plant and animals</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Classifies animals according to shared characteristics</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Describes the basic needs and ways to care for plants, animals and the environment</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="81%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Identify different kinds of weather</td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="4.75%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
    </table>
    <table class="table table-sm mb-0" style="width: 100%; table-layout: fixed; margin-left: .84in; margin-right: .84in; margin-top: 14px; padding-left: 5px!important;">
        <tr>
            <td width="100%" class="text-center" style="font-size: 16px;"><b>RATING SCALE</b></td>
        </tr>
    </table>
    <table class="table table-sm table-bordered mb-0" style="width: 100%; table-layout: fixed; margin-left: 1in; margin-right: 1in; margin-top: 14px; padding-left: 5px!important;">
        <tr>
            <td width="25%" class="text-center" style="font-size: 12.8px;"><b>Rating</b></td>
            <td width="75%" class="text-center" style="font-size: 12.8px;"><b>Indicators</b></td>
        </tr>
        <tr>
            <td width="25%" rowspan="3" class="text-center" style="font-size: 11.8px; vertical-align: middle;"><b>Beginning (B)</b></td>
            <td width="75%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Rarely demonstrates the expected competency</td>
        </tr>
        <tr>
            <td width="75%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Rarely participates in class activities and/or initiates independent works</td>
        </tr>
        <tr>
            <td width="75%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Shows interest in doing tasks but needs close supervision</td>
        </tr>
        <tr>
            <td width="25%" rowspan="3" class="text-center" style="font-size: 11.8px; margin-top: 20px; vertical-align: middle;"><b>Developing (D)</b></td>
            <td width="75%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Sometimes demonstrates the competency</td>
        </tr>
        <tr>
            <td width="75%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Sometimes participates, minimal supervision</td>
        </tr>
        <tr>
            <td width="75%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Progresses continuously in doing assigned tasks</td>
        </tr>
        <tr>
            <td width="25%" rowspan="3" class="text-center" style="font-size: 11.8px; vertical-align: middle;"><b>Consistent (C)</b></td>
            <td width="75%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Always demonstrates the expected competency</td>
        </tr>
        <tr>
            <td width="75%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Always participates in the different activities, works independently</td>
        </tr>
        <tr>
            <td width="75%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Always performs tasks, advanced in some aspects</td>
        </tr>
    </table>
    <table class="table table-sm mb-0" style="width: 100%; table-layout: fixed; margin-left: .84in; margin-right: .84in; margin-top: 14px; padding-left: 5px!important;">
        <tr>
            <td width="100%" class="text-center" style="font-size: 16px;"><b>TEACHER’S COMMENTS/REMARKS</b></td>
        </tr>
    </table>
    <table class="table table-sm mb-0" style="width: 100%; table-layout: fixed; margin-left: 1.7in; margin-right: 1.7in; margin-top: 14px; padding-left: 5px!important;">
        <tr>
            <td width="50%" class="text-center" style="font-size: 16px;margin-top: 20px; border: 1px solid #000;">
                <table class="table table-sm mb-0" style="width: 100%; table-layout: fixed;">
                    <tr>
                        <td width="100%" class="text-center" style="font-size: 12.8px;"><b>First Quarter (Weeks 1 – 10)</b></td>
                    </tr>
                    <tr>
                        <td width="100%" style="border-bottom: 1.5px solid #000; padding-top: -5px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="100%" style="border-bottom: 1.5px solid #000;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="100%" style="border-bottom: 1.5px solid #000;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="100%" style="border-bottom: 1.5px solid #000;">&nbsp;</td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="width: 100%; table-layout: fixed; padding-left: 50px!important; padding-right: 50px!important;">
                    <tr>
                        <td width="100%" style="border-bottom: 1.5px solid #000;">&nbsp;</td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="width: 100%; table-layout: fixed; padding-left: 20px!important; padding-right: 20px!important;">
                    <tr>
                        <td width="100%" style="">Parent or Guardian’s Signature</td>
                    </tr>
                </table>
                <br>
            </td>
            <td width="50%" class="text-center" style="margin-top: 20px; border: 1px solid #000;">
                <table class="table table-sm mb-0" style="width: 100%; table-layout: fixed;">
                    <tr>
                        <td width="100%" class="text-center" style="font-size: 12.8px; margin-top: 20px;"><b>Second Quarter (Weeks 11-20)</b></td>
                    </tr>
                    <tr>
                        <td width="100%" style="border-bottom: 1.5px solid #000; padding-top: -5px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="100%" style="border-bottom: 1.5px solid #000;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="100%" style="border-bottom: 1.5px solid #000;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="100%" style="border-bottom: 1.5px solid #000;">&nbsp;</td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="width: 100%; table-layout: fixed; padding-left: 50px!important; padding-right: 50px!important;">
                    <tr>
                        <td width="100%" style="border-bottom: 1.5px solid #000;">&nbsp;</td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="width: 100%; table-layout: fixed; padding-left: 20px!important; padding-right: 20px!important;">
                    <tr>
                        <td width="100%" style="">Parent or Guardian’s Signature</td>
                    </tr>
                </table>
                <br>
            </td>
        </tr>
        <tr>
            <td width="50%" class="text-center" style="font-size: 16px;margin-top: 20px; border: 1px solid #000;">
                <table class="table table-sm mb-0" style="width: 100%; table-layout: fixed;">
                    <tr>
                        <td width="100%" class="text-center" style="font-size: 12.8px; margin-top: 20px;"><b>Third Quarter (Weeks 21 – 30)</b></td>
                    </tr>
                    <tr>
                        <td width="100%" style="border-bottom: 1.5px solid #000; padding-top: -5px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="100%" style="border-bottom: 1.5px solid #000;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="100%" style="border-bottom: 1.5px solid #000;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="100%" style="border-bottom: 1.5px solid #000;">&nbsp;</td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="width: 100%; table-layout: fixed; padding-left: 50px!important; padding-right: 50px!important;">
                    <tr>
                        <td width="100%" style="border-bottom: 1.5px solid #000;">&nbsp;</td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="width: 100%; table-layout: fixed; padding-left: 20px!important; padding-right: 20px!important;">
                    <tr>
                        <td width="100%" style="">Parent or Guardian’s Signature</td>
                    </tr>
                </table>
                <br>
            </td>
            <td width="50%" class="text-center" style="margin-top: 20px; border: 1px solid #000;">
                <table class="table table-sm mb-0" style="width: 100%; table-layout: fixed;">
                    <tr>
                        <td width="100%" class="text-center" style="font-size: 12.8px; margin-top: 20px;"><b>Fourth Quarter (Weeks 31-40)</b></td>
                    </tr>
                    <tr>
                        <td width="100%" style="border-bottom: 1.5px solid #000; padding-top: -5px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="100%" style="border-bottom: 1.5px solid #000;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="100%" style="border-bottom: 1.5px solid #000;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="100%" style="border-bottom: 1.5px solid #000;">&nbsp;</td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="width: 100%; table-layout: fixed; padding-left: 50px!important; padding-right: 50px!important;">
                    <tr>
                        <td width="100%" style="border-bottom: 1.5px solid #000;">&nbsp;</td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="width: 100%; table-layout: fixed; padding-left: 20px!important; padding-right: 20px!important;">
                    <tr>
                        <td width="100%" style="">Parent or Guardian’s Signature</td>
                    </tr>
                </table>
                <br>
            </td>
        </tr>
    </table>
    <table class="table table-sm mb-0" style="width: 100%; table-layout: fixed; margin-left: .84in; margin-right: .84in; padding-left: 5px!important;">
        <tr>
            <td width="100%" class="text-center" style="font-size: 16px; margin-top: 20px;"><b>ATTENDANCE RECORD</b></td>
        </tr>
    </table>
    <table class="table table-sm tab mb-0 table-bordered" style="width: 100%; table-layout: fixed; margin-left: 2.3in; margin-right: 2.3in; margin-top: 10px; padding-left: 5px!important;">
        <tr>
            <td width="40%" class="text-left p-0 tdleft" style="font-size: 11.8px; margin-top: 20px;"></td>
            <td width="15%" class="text-center p-0" style="font-size: 11.8px;"><b>Q1</b></td>
            <td width="15%" class="text-center p-0" style="font-size: 11.8px;"><b>Q2</b></td>
            <td width="15%" class="text-center p-0" style="font-size: 11.8px;"><b>Q3</b></td>
            <td width="15%" class="text-center p-0" style="font-size: 11.8px;"><b>Q4</b></td>
        </tr>
        <tr>
            <td width="40%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Days Present</td>
            <td width="15%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="15%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="15%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="15%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="40%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Days Absent</td>
            <td width="15%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="15%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="15%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="15%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="40%" class="text-left p-0 tdleft" style="font-size: 11.8px;">Days Tardy</td>
            <td width="15%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="15%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="15%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="15%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="40%" class="text-left p-0 tdleft" style="font-size: 11.8px; margin-top: 20px;">Days Incomplete</td>
            <td width="15%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="15%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="15%" class="text-center p-0" style="font-size: 11.8px;"></td>
            <td width="15%" class="text-center p-0" style="font-size: 11.8px;"></td>
        </tr>
    </table>
    <table class="table table-sm mb-0" style="width: 100%; table-layout: fixed; margin-left: .84in; margin-right: .84in; margin-top: 14px; padding-left: 5px!important;">
        <tr>
            <td width="18%" class="text-left p-0" style="font-size: 11.8px;">This is to certify that</td>
            <td width="37%" class="text-left p-0" style="font-size: 11.8px; border-bottom: 1px solid #000;"></td>
            <td width="4%" class="text-left p-0" style="font-size: 11.8px;">&nbsp;&nbsp;of&nbsp;</td>
            <td width="17%" class="text-left p-0" style="font-size: 11.8px; border-bottom: 1px solid #000;"></td>
            <td width="24%" class="text-left p-0" style="font-size: 11.8px;">&nbsp;has developed the general</td>
        </tr>
        <tr>
            <td colspan="5" class="text-left p-0" style="font-size: 11.8px;">competencies based on the Kindergarten Curriculum Guide.</td>
        </tr>
    </table>
    <table class="table table-sm mb-0" style="width: 100%; table-layout: fixed; margin-left: .84in; margin-right: .84in; margin-top: 20px; padding-left: 5px!important;">
        <tr>
            <td width="7.5%" class="text-left p-0" style="font-size: 11.8px;"></td>
            <td width="34.5%" class="text-left p-0" style="font-size: 11.8px; border-bottom: 1px solid #000;"></td>
            <td width="3%" class="text-left p-0" style="font-size: 11.8px;"></td>
            <td width="14.5%" class="text-left p-0" style="font-size: 11.8px; border-bottom: 1px solid #000;"></td>
            <td width="40.5%" class="text-left p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="7.5%" class="text-left p-0" style="font-size: 11.8px;"></td>
            <td width="34.5%" class="text-center p-0" style="font-size: 11.8px;">Teacher's Signature</td>
            <td width="3%" class="text-left p-0" style="font-size: 11.8px;"></td>
            <td width="14.5%" class="text-center p-0" style="font-size: 11.8px;">Date</td>
            <td width="40.5%" class="text-left p-0" style="font-size: 11.8px;"></td>
        </tr>
    </table>
    <table class="table table-sm mb-0" style="width: 100%; table-layout: fixed; margin-left: .84in; margin-right: .84in; margin-top: 20px; padding-left: 5px!important;">
        <tr>
            <td width="7.5%" class="text-left p-0" style="font-size: 11.8px;"></td>
            <td width="34.5%" class="text-left p-0" style="font-size: 11.8px; border-bottom: 1px solid #000;"></td>
            <td width="3%" class="text-left p-0" style="font-size: 11.8px;"></td>
            <td width="14.5%" class="text-left p-0" style="font-size: 11.8px; border-bottom: 1px solid #000;"></td>
            <td width="40.5%" class="text-left p-0" style="font-size: 11.8px;"></td>
        </tr>
        <tr>
            <td width="7.5%" class="text-left p-0" style="font-size: 11.8px;"></td>
            <td width="34.5%" class="text-center p-0" style="font-size: 11.8px;">School Head's Signature</td>
            <td width="3%" class="text-left p-0" style="font-size: 11.8px;"></td>
            <td width="14.5%" class="text-center p-0" style="font-size: 11.8px;">Date</td>
            <td width="40.5%" class="text-left p-0" style="font-size: 11.8px;"></td>
        </tr>
    </table>
</body>
</html>