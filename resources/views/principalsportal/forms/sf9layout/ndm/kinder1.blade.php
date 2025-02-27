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
        @page { size: 14in 8.5in; margin: 20px 20px;  }
        
    </style>
</head>
<body>
<table style="width: 100%; table-layout: fixed;">
    <tr>
        <td width="50%" style="vertical-align: top; padding-right: 50px!important;">
            <table style="width: 100%; margin-top: 5px;">
                <tr>
                    <td  width="100%" class="p-0">
                        @php
                            $width = count($attendance_setup) != 0? 55 / count($attendance_setup) : 0;
                        @endphp
                        <table style="width: 100%; table-layout: fixed;font-size: 16px; margin-top: 30px; margin-bottom: 30px">
                            <tr>
                                <td width="100%" class="text-center p-0"><b>ATTENDANCE RECORD</b></td>
                            </tr>
                        </table>
                        <table class="table table-bordered table-sm grades mb-0" width="100%">
                            <tr>
                                <td width="18%" style="padding-top: 13px!important;border: 1px solid #000; text-align: center;"></td>
                                @foreach ($attendance_setup as $item)
                                    <td class="aside text-center align-middle;" width="{{$width}}%"><span style="text-transform: uppercase;">{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</span></td>
                                @endforeach
                                <td class="text-center" width="10%" style="vertical-align: top; font-size: 12px!important;"><span>TOTAL</span></td>
                            </tr>
                            <tr class="table-bordered">
                                <td >Number of School <br> Days</td>
                                @foreach ($attendance_setup as $item)
                                    <td class="text-center align-middle">{{$item->days != 0 ? $item->days : '' }}</td>
                                @endforeach
                                <td class="text-center align-middle">{{collect($attendance_setup)->sum('days')}}</td>
                            </tr>
                            <tr class="table-bordered">
                                <td>Number of School Days <br> Present</td>
                                @foreach ($attendance_setup as $item)
                                    <td class="text-center align-middle">{{$item->days != 0 ? $item->present : ''}}</td>
                                @endforeach
                                <td class="text-center align-middle" >{{collect($attendance_setup)->where('days','!=',0)->sum('present')}}</td>
                            </tr>
                            <tr class="table-bordered">
                                <td>Number of School Days <br> Absent</td>
                                @foreach ($attendance_setup as $item)
                                    <td class="text-center align-middle" >{{$item->days != 0 ? $item->absent : ''}}</td>
                                @endforeach
                                <td class="text-center align-middle" >{{collect($attendance_setup)->sum('absent')}}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
        <td width="50%" style="vertical-align: top; padding-right: 50px!important; padding-left: 30px!important;">
            <table style="width: 100%; table-layout: fixed;">
                <tr>
                    <td width="100%" class="text-left p-0">K-1</td>
                </tr>
            </table>
            <table style="width: 100%; table-layout: fixed;">
                <tr>
                   <td width="20%" style="text-align: center;">
                       <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="80px">
                   </td>
                   <td width="60%" style="vertical-align: top;">
                       <table style="width: 100%; table-layout: fixed;font-size: 15px;">
                           <tr>
                               <td>
                                   <div class="text-center">REPUBLIKA NG PILIPINAS</div>
                                   <div class="text-center">KAGAWARAN NG EDUKASYON</div>
                                   <div class="text-center" style="margin-top: 5px;"><b>{{$schoolinfo[0]->schoolname}}</b></div>
                                   <div class="text-center"><b>{{$schoolinfo[0]->address}}</b></div>
                                   <div class="text-center" style="margin-top: 7px;"><b>ULAT SA PAG-UNLAD NG MAG-AARAL</b></div>
                               </td>
                           </tr>
                       </table>
                   </td>
                   <td width="20%">
                        <img src="{{base_path()}}/public/assets/images/deped_logo.png" alt="school" width="100px">
                   </td>
                </tr>
            </table>
            <table style="width: 100%; table-layout: fixed; margin-top: 10px;">
                <tr>
                    <td width="13%" class="text-left p-0">Pangalan:</td>
                    <td width="72%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="15%" class="text-left p-0"></td>
                </tr>
            </table>
            <table style="width: 100%; table-layout: fixed;">
                <tr>
                    <td width="30%" class="text-left p-0">Gulang Pampaaralan:</td>
                    <td width="5%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="20%" class="text-left p-0"></td>
                    <td width="13%" class="text-left p-0">Kasarian:</td>
                    <td width="28%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="14%" class="text-left p-0"></td>
                </tr>
            </table>
            <table style="width: 100%; table-layout: fixed;">
                <tr>
                    <td width="24%" class="text-left p-0">Taong Panuruan:</td>
                    <td width="15%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="8%" class="text-left p-0"></td>
                    <td width="29%" class="text-left p-0">Baitang at Pangkat:</td>
                    <td width="34%" class="text-left p-0"></td>
                </tr>
            </table>
            <table style="width: 100%; table-layout: fixed; margin-top: 20px;">
                <tr>
                    <td width="100%" class="text-left p-0">Mahal na Magulang,</td>
                </tr>
            </table>
            <table style="width: 100%; table-layout: fixed; margin-top: 22px;">
                <tr>
                    <td width="100%" class="p-0" style="text-align: justify!important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ipinakikita po ng ulat na ito ang kakayahan at ang pag-unlad ng inyong anak. ito ay maaaring gamiting gabay sa pagkilala ng inyong anak at sa kalaunan ay
                        makagawa ng angkop na pag-aalaga, pagtuturo at paggabay sa kanilang paglaki at pag-unlad. Bukas ang aming paaralan sa inyong pagdalaw kung nais po ninyong alamin ang kalagayan ng inyong anak.
                    </td>
                </tr>
            </table>
            <table style="width: 100%; table-layout: fixed; margin-top: 20px;">
                <tr>
                    <td width="100%" class="text-right p-0" style="font-size: 17px"><b><u>MADONNA J. DELICANO</u></b></td>
                </tr>
                <tr>
                    <td width="100%" class="text-right p-0" style="padding-right: 35px!important;">Gurong Tagapayo</td>
                </tr>
            </table>
            <table style="width: 100%; table-layout: fixed; margin-top: 22px;">
                <tr>
                    <td width="100%" class="p-0" style="text-align: justify!important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hinahangad po naming makatutulong kayo sa pagsubaybay sa pag-aaral ng
                        inyong anak upang lalo siyang umunlad. Dito inaasahan po naming makikipanayam kayo sa guro tungkol sa ulat na ito at sa mga suliraning may kinalaman sa pag-unlad ng inyong anak. Maraming salamat po sa inyong pag mamalasakit at pakikipagtulungan para sa higit na ikauunlad at ikakakabuti ng inyong anak.
                    </td>
                </tr>
            </table>
            <table style="width: 100%; table-layout: fixed; margin-top: 20px;">
                <tr>
                    <td width="100%" class="text-right p-0" style="padding-right: 80px!important;">Lubos na gumagalang</td>
                </tr>
            </table>
            <table style="width: 100%; table-layout: fixed; margin-top: 20px;">
                <tr>
                    <td width="100%" class="text-right p-0" style="font-size: 17px"><b><u>SR. ELVIE G. BORLADO, P.M</u></b></td>
                </tr>
                <tr>
                    <td width="100%" class="text-right p-0" style="padding-right: 70px!important;">Punong-Guro</td>
                </tr>
            </table>
            <table style="width: 100%; table-layout: fixed; margin-top: 20px;">
                <tr>
                    <td width="95%" class="text-right p-0"  style="border-bottom: 1px solid #000;"></td>
                    <td width="5%" class="text-right p-0"></td>
                </tr>
            </table>
            <table style="width: 100%; table-layout: fixed;">
                <tr>
                    <td width="100%" class="text-center p-0"  style="font-size: 16px;"><b>KATIBAYAN SA PAGLIPAT</b></td>
                </tr>
            </table>
            <table style="width: 100%; table-layout: fixed;">
                <tr>
                    <td width="25%" class="text-left p-0"  style="font-size: 16px;">Inilapat sa Baitang</td>
                    <td width="45%" class="text-right p-0"  style="border-bottom: 1px solid #000;"></td>
                    <td width="30%" class="p-0"></td>
                </tr>
            </table>
            <table style="width: 100%; table-layout: fixed;">
                <tr>
                    <td width="23%" class="text-left p-0"  style="font-size: 16px;">Maaaring ilipat sa</td>
                    <td width="17%" class="text-right p-0"  style="border-bottom: 1px solid #000;"></td>
                    <td width="60%" class="p-0"></td>
                </tr>
            </table>
            <table style="width: 100%; table-layout: fixed;">
                <tr>
                    <td width="7%" class="text-left p-0"  style="font-size: 16px;">Petsa</td>
                    <td width="15%" class="text-right p-0"  style="border-bottom: 1px solid #000;"></td>
                    <td width="78%" class="p-0"></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<br><br><br>
<table width="100%" style="table-layout: fixed; margin-top: 30px;">
    <tr>
        <td width="50%" style="vertical-align: top;">
            <table with="100%" class="table table-bordered" style="table-layout: fixed; padding-right: 50px;">
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 16px; padding-left: 5px!important;"><b>Health, Well-Being, and Motor Development</b></td>
                    <td width="6%" class="text-center p-0" style="font-size: 16px;"><b>1Q</b></td>
                    <td width="6%" class="text-center p-0" style="font-size: 16px;"><b>2Q</b></td>
                    <td width="6%" class="text-center p-0" style="font-size: 16px;"><b>3Q</b></td>
                    <td width="6%" class="text-center p-0" style="font-size: 16px;"><b>4Q</b></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 13px; padding-left: 5px!important;">Demonstrates health habits that keep one clean sanitary</td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 13px; padding-left: 5px!important;">Demonstrates behaviors that promote personal safety</td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 13px; padding-left: 5px!important;">Demonstrates locomotor skills such as walking, running, skipping, jumping, climbing correctly during play, dance or exercice activities</td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 13px; padding-left: 5px!important;">Demonstrates non-locomotor skills such as pushing, pulling, turning, swaying, bending, throwing, catching, and kicking correctly during play, dance or exercise activities</td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 13px; padding-left: 5px!important;">Demonstrates fine motor skills needed for self-care/self-help such as toothbrushing, buttoning, screwing and unscrewing lids, using spoon and fork correctly, etc.</td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 13px; padding-left: 5px!important;">Demonstrates fine motor skills needed for creative self-expression /art activities, such as tearing, cutting, pasting, copying, drawing, coloring, molding, painting, lacing, etc.</td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 13px; padding-left: 5px!important;">Traces, copies, or writes letters and numerals</td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 16px; padding-left: 5px!important;"><b>Socio-emotional Development</b></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 13px; padding-left: 5px!important;">States personal information (name, gender, age, birthday)</td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 13px; padding-left: 5px!important;">Expresses personal interests and needs</td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 13px; padding-left: 5px!important;">Demonstrates readiness in trying out new experiences, and self- confidence in doing tasks independently</td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 13px; padding-left: 5px!important;">Follows school rules willingly and executes school tasks and routines well</td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 13px; padding-left: 5px!important;">Recognizes different emotions, acknowledges the feeling of others, and shows willingness to help</td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 13px; padding-left: 5px!important;">Shows respect in dealing with peers and adults </td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 13px; padding-left: 5px!important;">Identifies members of one’s family</td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 16px; padding-left: 5px!important;"><b>Language, Literacy and Communication</b></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 14px; padding-left: 5px!important;"><b>Listening and Viewing</b></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 13px; padding-left: 5px!important;">Distinguishes between elements of sounds e.g. pitch (low and high), volume <br> (loud and soft)</td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 13px; padding-left: 5px!important;">Listens attentively to stories/ poems/songs</td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 13px; padding-left: 5px!important;">Recalls details from stories/poems/songs listened to</td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 13px; padding-left: 5px!important;">Relate story events to personal experiences</td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 13px; padding-left: 5px!important;">Sequence events from a story listened to</td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 13px; padding-left: 5px!important;">Infer character traits and feelings</td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
            </table>
        </td>
        <td width="50%" style="vertical-align: top; padding-left: 30px!important;">
            <table with="100%" class="table table-bordered" style="table-layout: fixed;">
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 16px; padding-left: 5px!important;"><b>Speaking</b><br>&nbsp;</td>
                    <td width="6%" class="text-center p-0" style="font-size: 16px;"><b>1Q</b></td>
                    <td width="6%" class="text-center p-0" style="font-size: 16px;"><b>2Q</b></td>
                    <td width="6%" class="text-center p-0" style="font-size: 16px;"><b>3Q</b></td>
                    <td width="6%" class="text-center p-0" style="font-size: 16px;"><b>4Q</b></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 13px; padding-left: 5px!important;">Uses proper expressions in  and polite greetings in appropriate situations</td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 13px; padding-left: 5px!important;">Talks about details of object, people, etc. using appropriate speaking vocabulary</td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 13px; padding-left: 5px!important;">Participates actively in class activities (e.g., reciting poems, rhymes, etc.) and discussions by responding to questions accordingly</td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 13px; padding-left: 5px!important;">Asks simple questions ( who, what, where, when, why )</td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 13px; padding-left: 5px!important;">Gives 1 to 2 step directions</td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 13px; padding-left: 5px!important;">Retells simple stories or narrates personal experiences</td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 16px; padding-left: 5px!important;"><b>Reading</b></td>
                    <td width="6%" class="text-center p-0" style="font-size: 16px;"></td>
                    <td width="6%" class="text-center p-0" style="font-size: 16px;"></td>
                    <td width="6%" class="text-center p-0" style="font-size: 16px;"></td>
                    <td width="6%" class="text-center p-0" style="font-size: 16px;"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 13px; padding-left: 5px!important;">Identifies sounds of letters</td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 13px; padding-left: 5px!important;">Names uppercase and lower case letters</td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 13px; padding-left: 5px!important;">Matches the uppercase and lower case letters</td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 13px; padding-left: 5px!important;">Identifies beginning sound of  a given word</td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 13px; padding-left: 5px!important;">Distinguishes the words that rhyme</td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 13px; padding-left: 5px!important;">Shows interest in reading by browsing through books, predicting what the story is all about and demonstrating proper book handling behavior</td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 16px; padding-left: 5px!important;"><b>Writing</b></td>
                    <td width="6%" class="text-center p-0" style="font-size: 16px;"></td>
                    <td width="6%" class="text-center p-0" style="font-size: 16px;"></td>
                    <td width="6%" class="text-center p-0" style="font-size: 16px;"></td>
                    <td width="6%" class="text-center p-0" style="font-size: 16px;"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 13px; padding-left: 5px!important;">Writes one’s given name</td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 13px; padding-left: 5px!important;">Writes lowercase and uppercase letters</td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 13px; padding-left: 5px!important;">Express simple ideas through symbols (e.g., drawings, invented spelling)</td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 16px; padding-left: 5px!important;"><b>Mathematics</b></td>
                    <td width="6%" class="text-center p-0" style="font-size: 16px;"></td>
                    <td width="6%" class="text-center p-0" style="font-size: 16px;"></td>
                    <td width="6%" class="text-center p-0" style="font-size: 16px;"></td>
                    <td width="6%" class="text-center p-0" style="font-size: 16px;"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 13px; padding-left: 5px!important;">Identifies colors</td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 13px; padding-left: 5px!important;">Identifies shapes</td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 13px; padding-left: 5px!important;">Sorts objects according to shape, size, and color</td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 13px; padding-left: 5px!important;">Recognizes and extends patterns</td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 13px; padding-left: 5px!important;">Rote count up to 10</td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 13px; padding-left: 5px!important;">Counts objects up tp 10</td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 13px; padding-left: 5px!important;">Recognize numerals up to 10</td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 13px; padding-left: 5px!important;">Writes numerals up to 10</td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 13px; padding-left: 5px!important;">Sequence numbers</td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
                <tr>
                    <td width="76%" class="text-left p-0" style="font-size: 16px; padding-left: 5px!important;"><b>Character and Values Development</b></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                    <td width="6%" class="text-center p-0"></td>
                </tr>
            </table>
            <table with="100%" class="table" style="table-layout: fixed; padding-right: 30px; margin-top: 20px">
                <tr>
                    <td width="15%" class="text-left p-0"></td>
                    <td width="10%" class="text-left p-0" style="font-size: 16px;"><b>Legend:</b></td>
                    <td width="20%" class="text-left p-0" style="font-size: 16px;"><b>B - Beginning</b></td>
                    <td width="20%" class="text-left p-0" style="font-size: 16px;"><b>D - Developing</b></td>
                    <td width="20%" class="text-left p-0" style="font-size: 16px;"><b>C - Consistent</b></td>
                    <td width="15%" class="text-left p-0"></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>