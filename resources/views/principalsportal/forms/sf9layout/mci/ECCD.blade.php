<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$student->firstname.' '.$student->middlename[0].' '.$student->lastname}}</title>
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
        @page { size: 14in 8.5in; margin: 5px 20px;  }
        
    </style>
</head>
<body>
<table width="100%" style="table-layout: fixed;">
    <tr>
        <td width="33.3333333333%" style="vertical-align:top; padding-left: 5px; padding-right: 5px;">
            <table class="table table-bordered mb-0" style="width: 100%; table-layout: fixed;">
                <tr style="background-color:#D9E2F3;">
                    <td colspan="2" class="text-center p-0" style="font-size: 14px;"><b>Social Emotional Domain</b></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"><b>1st</b></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"><b>2nd</b></td>
                </tr>
                <tr>
                    <td width="8%" class="text-center p-0" style="font-size: 12px;">1</td>
                    <td width="72%" class="text-left p-0" style="font-size: 12px; padding-left: 5px!important;">Lumalapit sa mga hindi kakilala ngunit sa una ay maaaring maging mahiyain o hindi mapalagay.</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="8%" class="text-center p-0" style="font-size: 12px;">2</td>
                    <td width="72%" class="text-left p-0" style="font-size: 12px; padding-left: 5px!important;">Natutuwang nanonood ng mga ginagawa ng mga tao o hayop sa malapit na lugar.</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="8%" class="text-center p-0" style="font-size: 12px;">3</td>
                    <td width="72%" class="text-left p-0" style="font-size: 12px; padding-left: 5px!important;">Naglalarong mag-isa ngunit gustong malapit sa mga pamilyar na nakatatanda o kapatid.</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="8%" class="text-center p-0" style="font-size: 12px;">4</td>
                    <td width="72%" class="text-left p-0" style="font-size: 12px; padding-left: 5px!important;">Tumatawa/tumitili nang malakas sa paglalaro</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="8%" class="text-center p-0" style="font-size: 12px;">5</td>
                    <td width="72%" class="text-left p-0" style="font-size: 12px; padding-left: 5px!important;">Naglalaro ng “bulaga”<br> &nbsp;</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="8%" class="text-center p-0" style="font-size: 12px;">6</td>
                    <td width="72%" class="text-left p-0" style="font-size: 12px; padding-left: 5px!important;">Pinapagulong ang bola sa kalaro o tagapag-alaga<br> &nbsp;</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="8%" class="text-center p-0" style="font-size: 12px;">7</td>
                    <td width="72%" class="text-left p-0" style="font-size: 12px; padding-left: 5px!important;">Niyayakap ang mga laruan<br> &nbsp;</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="8%" class="text-center p-0" style="font-size: 12px;">8</td>
                    <td width="72%" class="text-left p-0" style="font-size: 12px; padding-left: 5px!important;">Ginagaya ang mga ginagawa ng mga nakatatanda (hal. pagluluto, paghuhugas)</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="8%" class="text-center p-0" style="font-size: 12px;">9</td>
                    <td width="72%" class="text-left p-0" style="font-size: 12px; padding-left: 5px!important;">Marunong maghintay (hal. sa paghuhugas ng kamay, sa pagkuha ng pagkain)</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="8%" class="text-center p-0" style="font-size: 12px;">10</td>
                    <td width="72%" class="text-left p-0" style="font-size: 12px; padding-left: 5px!important;">Humihingi ng permiso na laruin ang mga laruan na ginagamit ng ibang bata.</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="8%" class="text-center p-0" style="font-size: 12px;">11</td>
                    <td width="72%" class="text-left p-0" style="font-size: 12px; padding-left: 5px!important;">Pinahihiram ang sariling laruan sa iba</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="8%" class="text-center p-0" style="font-size: 12px;">12</td>
                    <td width="72%" class="text-left p-0" style="font-size: 12px; padding-left: 5px!important;">Naglalaro ng maayos sa mga pang-grupong  laro 
                        (hal. hindi nandadaya para manalo)
                        </td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="8%" class="text-center p-0" style="font-size: 12px;">13</td>
                    <td width="72%" class="text-left p-0" style="font-size: 12px; padding-left: 5px!important;">Binabantayan ang mga pag-aari ng may determinasyon</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="8%" class="text-center p-0" style="font-size: 12px;">14</td>
                    <td width="72%" class="text-left p-0" style="font-size: 12px; padding-left: 5px!important;">Nagpupursige kung may problema o hadlang sa kanyang gusto.</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="8%" class="text-center p-0" style="font-size: 12px;">15</td>
                    <td width="72%" class="text-left p-0" style="font-size: 12px; padding-left: 5px!important;">Interesado sa kanyang kapaligiran ngunit alam kung kailan kailangang huminto sa pagtatanong</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="8%" class="text-center p-0" style="font-size: 12px;">16</td>
                    <td width="72%" class="text-left p-0" style="font-size: 12px; padding-left: 5px!important;">Inalo/ Inaaliw ang mga kalaro o kapatid kung may problema</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="8%" class="text-center p-0" style="font-size: 12px;">17</td>
                    <td width="72%" class="text-left p-0" style="font-size: 12px; padding-left: 5px!important;">Nakikipagtulungan  sa mga pang-grupong sitwasyon upang maiwasan ang mga away o problema<br> &nbsp;</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="8%" class="text-center p-0" style="font-size: 12px;">18</td>
                    <td width="72%" class="text-left p-0" style="font-size: 12px; padding-left: 5px!important;">Naikukwento ang mga mabigat na nararamdaman 
                        (Hal, galit, lungkot)
                        </td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="8%" class="text-center p-0" style="font-size: 12px;">19</td>
                    <td width="72%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Gumagamit ng mga kilos na nararapat sa kultura na hindi na hinihiling/dinidiktahan (hal. pagmamano, paghalik)</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="8%" class="text-center p-0" style="font-size: 12px;">20</td>
                    <td width="72%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Nagpapakita ng respeto sa nakatatanda gamit ang “Opo” o “Po” (o anumang katumbas nito) sa halip na kanilang pangalan</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="8%" class="text-center p-0" style="font-size: 12px;">21</td>
                    <td width="72%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Natutukoy ang nararamdaman ng iba</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="8%" class="text-center p-0" style="font-size: 12px;"></td>
                    <td width="72%" class="text-left p-0" style="font-size: 11x; padding-left: 5px!important;">Tumutulong sa mga gawaing pambahay 
                        (hal. nagpupunas ng mesa, nagdidilig ng mga halaman)
                        </td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="8%" class="text-center p-0" style="font-size: 12px;">22</td>
                    <td width="72%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Responsableng nagbabantay sa mga nakababatang kapatid/ibang miyembro ng pamilya<br> &nbsp;</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="8%" class="text-center p-0" style="font-size: 12px;">23</td>
                    <td width="72%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Tinatanggap ang isang kasunduang ginawa ng tagapag-alaga (hal. Linisin muna ang kuwarto bago maglaro sa labas).</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr style="background-color:#D9E2F3;">
                    <td colspan="2" class="text-center p-0" style="font-size: 11px; padding-left: 5px!important;"><b>Bilang ng Iskor</b></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
            </table>
        </td>
        <td width="33.3333333333%" style="vertical-align:top; padding-left: 5px; padding-right: 5px; padding-right: 20px!important;">
            <table class="table mb-0" style="width: 100%; table-layout: fixed; margin-top: 10px!imporant;">
                <tr>
                    <td width="100%" class="text-center p-0" style="font-size: 18px; color: rgb(2, 68, 119);"><b>Sociodemographic Profile</b></td>
                </tr>
            </table>
            <table class="table mb-0" style="width: 100%; table-layout: fixed; padding-left: 10px!important; margin-top: 20px!imporant;">
                <tr>
                    <td width="30%" class="text-left p-0" style="font-size: 14px;">Pangalan ng Bata:</td>
                    <td width="70%" class="text-left p-0" style="font-size: 14px; border-bottom: 1px solid #000;"></td>
                </tr>
            </table>
            <table class="table mb-0" style="width: 100%; table-layout: fixed; padding-left: 10px!important; margin-top: 3px!imporant;">
                <tr>
                    <td width="15%" class="text-left p-0" style="font-size: 14px;">Tirahan:</td>
                    <td width="51%" class="text-left p-0" style="font-size: 14px; border-bottom: 1px solid #000;"></td>
                    <td width="44%" class="text-left p-0"></td>
                </tr>
            </table>
            <table class="table mb-0" style="width: 100%; table-layout: fixed; padding-left: 10px!important; margin-top: 3px!imporant;">
                <tr>
                    <td width="45%" class="text-left p-0" style="font-size: 14px;">Gamit na kamay ng bata:</td>
                    <td width="8%" class="text-left p-0" style="font-size: 14px; border-bottom: 1px solid #000;">&nbsp;</td>
                    <td width="15%" class="text-center p-0" style="font-size: 14px;">Kanan</td>
                    <td width="8%" class="text-left p-0" style="font-size: 14px; border-bottom: 1px solid #000;">&nbsp;</td>
                    <td width="12%" class="text-center p-0" style="font-size: 14px;">&nbsp;Kaliwa</td>
                    <td width="22%" class="text-left p-0"></td>
                </tr>
            </table>
            <table class="table mb-0" style="width: 100%; table-layout: fixed; padding-left: 10px!important; margin-top: 3px!imporant;">
                <tr>
                    <td width="17%" class="text-left p-0" style="font-size: 14px;">Relihiyon:</td>
                    <td width="45%" class="text-left p-0" style="font-size: 14px; border-bottom: 1px solid #000;"></td>
                    <td width="48%" class="text-left p-0"></td>
                </tr>
            </table>
            <table class="table mb-0" style="width: 100%; table-layout: fixed; padding-left: 10px!important; margin-top: 3px!imporant;">
                <tr>
                    <td width="65%" class="text-left p-0" style="font-size: 14px;">Child is normally/physically healthy:</td>
                    <td width="40%" class="text-left p-0" style="font-size: 14px;">&nbsp;[ &nbsp;&nbsp;Yes &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; No&nbsp;&nbsp; ]</td>
                    <td width="5%" class="text-left p-0"></td>
                </tr>
            </table>
            <table class="table mb-0" style="width: 100%; table-layout: fixed; padding-left: 10px!important; margin-top: 3px!imporant;">
                <tr>
                    <td width="48%" class="text-left p-0" style="font-size: 14px;">Child has talent in/ good in:</td>
                    <td width="46%" class="text-left p-0" style="font-size: 14px; border-bottom: 1px solid #000;"></td>
                    <td width="16%" class="text-left p-0"></td>
                </tr>
            </table>
            <table class="table mb-0" style="width: 100%; table-layout: fixed; padding-left: 10px!important; margin-top: 3px!imporant;">
                <tr>
                    <td width="35%" class="text-left p-0" style="font-size: 14px;">Pangalan ng Tatay:</td>
                    <td width="59%" class="text-left p-0" style="font-size: 14px; border-bottom: 1px solid #000;"></td>
                    <td width="16%" class="text-left p-0"></td>
                </tr>
            </table>
            <table class="table mb-0" style="width: 100%; table-layout: fixed; padding-left: 10px!important; margin-top: 3px!imporant;">
                <tr>
                    <td width="11%" class="text-left p-0" style="font-size: 14px;">Edad:</td>
                    <td width="18%" class="text-left p-0" style="font-size: 14px; border-bottom: 1px solid #000;"></td>
                    <td width="13%" class="text-left p-0"></td>
                    <td width="17%" class="text-left p-0" style="font-size: 14px;">Trabaho:</td>
                    <td width="31%" class="text-left p-0" style="font-size: 14px; border-bottom: 1px solid #000;"></td>
                    <td width="20%" class="text-left p-0"></td>
                </tr>
            </table>
            <table class="table mb-0" style="width: 100%; table-layout: fixed; padding-left: 10px!important; margin-top: 3px!imporant;">
                <tr>
                    <td width="42%" class="text-left p-0" style="font-size: 14px;">Inabot na pinag-aralan:</td>
                    <td width="44%" class="text-left p-0" style="font-size: 14px; border-bottom: 1px solid #000;"></td>
                    <td width="24%" class="text-left p-0"></td>
                </tr>
            </table>
            <table class="table mb-0" style="width: 100%; table-layout: fixed; padding-left: 10px!important; margin-top: 3px!imporant;">
                <tr>
                    <td width="37%" class="text-left p-0" style="font-size: 14px;">Pangalan ng Nanay:</td>
                    <td width="57%" class="text-left p-0" style="font-size: 14px; border-bottom: 1px solid #000;"></td>
                    <td width="16%" class="text-left p-0"></td>
                </tr>
            </table>
            <table class="table mb-0" style="width: 100%; table-layout: fixed; padding-left: 10px!important; margin-top: 3px!imporant;">
                <tr>
                    <td width="11%" class="text-left p-0" style="font-size: 14px;">Edad:</td>
                    <td width="18%" class="text-left p-0" style="font-size: 14px; border-bottom: 1px solid #000;"></td>
                    <td width="17%" class="text-left p-0" style="font-size: 14px;">Trabaho:</td>
                    <td width="38%" class="text-left p-0" style="font-size: 14px; border-bottom: 1px solid #000;"></td>
                    <td width="26%" class="text-left p-0"></td>
                </tr>
            </table>
            <table class="table mb-0" style="width: 100%; table-layout: fixed; padding-left: 10px!important; margin-top: 3px!imporant;">
                <tr>
                    <td width="42%" class="text-left p-0" style="font-size: 14px;">Inabot na pinag-aralan:</td>
                    <td width="48%" class="text-left p-0" style="font-size: 14px; border-bottom: 1px solid #000;"></td>
                    <td width="20%" class="text-left p-0"></td>
                </tr>
            </table>
            <table class="table mb-0" style="width: 100%; table-layout: fixed; margin-top: 20px!imporant;">
                <tr>
                    <td width="100%" class="text-center p-0" style="font-size: 15px; color: rgb(2, 68, 119);"><b>Summary of Assessment</b></td>
                </tr>
            </table>
            <table class="table table-bordered mb-0" style="width: 100%; table-layout: fixed; margin-top: 20px!imporant; padding-right: 30px!important;">
                <tr>
                    <td width="26%" rowspan="2" class="text-center p-0" style="font-size: 14px; padding-top: 10px!important;"><b style="background-color:#D9E2F3;">Domains</b></td>
                    <td width="32%" colspan="2" class="text-center p-0" style="font-size: 12px;"><b>Assessment</b></td>
                    <td width="32%" colspan="2" class="text-center p-0" style="font-size: 12px;"><b>Post Assessment</b></td>
                </tr>
                <tr>
                    <td width="16%" class="text-center p-0" style="font-size: 14px;">Raw <br> Score</td>
                    <td width="16%" class="text-center p-0" style="font-size: 14px;">Raw <br> Score</td>
                    <td width="16%" class="text-center p-0" style="font-size: 14px;">Raw <br> Score</td>
                    <td width="16%" class="text-center p-0" style="font-size: 14px;">Raw <br> Score</td>
                </tr>
                <tr>
                    <td width="26%" class="text-left p-0" style="font-size: 11px; padding-bottom: 3px!important;">&nbsp;&nbsp;Gross-Motor</td>
                    <td width="16%" class="text-center p-0" style="font-size: 12px;"></td>
                    <td width="16%" class="text-center p-0" style="font-size: 12px;"></td>
                    <td width="16%" class="text-center p-0" style="font-size: 12px;"></td>
                    <td width="16%" class="text-center p-0" style="font-size: 12px;"></td>
                </tr>
                <tr>
                    <td width="26%" class="text-left p-0" style="font-size: 11px; padding-bottom: 3px!important;">&nbsp;&nbsp;Fine Motor</td>
                    <td width="16%" class="text-center p-0" style="font-size: 12px;"></td>
                    <td width="16%" class="text-center p-0" style="font-size: 12px;"></td>
                    <td width="16%" class="text-center p-0" style="font-size: 12px;"></td>
                    <td width="16%" class="text-center p-0" style="font-size: 12px;"></td>
                </tr>
                <tr>
                    <td width="26%" class="text-left p-0" style="font-size: 11px; padding-bottom: 3px!important;">&nbsp;&nbsp;Self-Help</td>
                    <td width="16%" class="text-center p-0" style="font-size: 12px;"></td>
                    <td width="16%" class="text-center p-0" style="font-size: 12px;"></td>
                    <td width="16%" class="text-center p-0" style="font-size: 12px;"></td>
                    <td width="16%" class="text-center p-0" style="font-size: 12px;"></td>
                </tr>
                <tr>
                    <td width="26%" class="text-left p-0" style="font-size: 11px;">&nbsp;&nbsp;Receptive <br>&nbsp;&nbsp;Language</td>
                    <td width="16%" class="text-center p-0" style="font-size: 12px;"></td>
                    <td width="16%" class="text-center p-0" style="font-size: 12px;"></td>
                    <td width="16%" class="text-center p-0" style="font-size: 12px;"></td>
                    <td width="16%" class="text-center p-0" style="font-size: 12px;"></td>
                </tr>
                <tr>
                    <td width="26%" class="text-left p-0" style="font-size: 11px;">&nbsp;&nbsp;Expressive <br>&nbsp;&nbsp;Language</td>
                    <td width="16%" class="text-center p-0" style="font-size: 12px;"></td>
                    <td width="16%" class="text-center p-0" style="font-size: 12px;"></td>
                    <td width="16%" class="text-center p-0" style="font-size: 12px;"></td>
                    <td width="16%" class="text-center p-0" style="font-size: 12px;"></td>
                </tr>
                <tr>
                    <td width="26%" class="text-left p-0" style="font-size: 11px; padding-bottom: 3px!important;">&nbsp;&nbsp;Cognitive</td>
                    <td width="16%" class="text-center p-0" style="font-size: 12px;"></td>
                    <td width="16%" class="text-center p-0" style="font-size: 12px;"></td>
                    <td width="16%" class="text-center p-0" style="font-size: 12px;"></td>
                    <td width="16%" class="text-center p-0" style="font-size: 12px;"></td>
                </tr>
                <tr>
                    <td width="26%" class="text-left p-0" style="font-size: 11px; padding-bottom: 3px!important;">&nbsp;&nbsp;Socio-Emotional</td>
                    <td width="16%" class="text-center p-0" style="font-size: 12px;"></td>
                    <td width="16%" class="text-center p-0" style="font-size: 12px;"></td>
                    <td width="16%" class="text-center p-0" style="font-size: 12px;"></td>
                    <td width="16%" class="text-center p-0" style="font-size: 12px;"></td>
                </tr>
                <tr>
                    <td width="26%" class="text-left p-0" style="font-size: 11px; padding-bottom: 3px!important;">&nbsp;&nbsp;<b>Scaled Score</b></td>
                    <td width="16%" class="text-center p-0" style="font-size: 12px;"></td>
                    <td width="16%" class="text-center p-0" style="font-size: 12px;"></td>
                    <td width="16%" class="text-center p-0" style="font-size: 12px;"></td>
                    <td width="16%" class="text-center p-0" style="font-size: 12px;"></td>
                </tr>
                <tr>
                    <td width="26%" class="text-left p-0" style="font-size: 11px; padding-bottom: 8px!important;">&nbsp;&nbsp;<b>Standard Score</b></td>
                    <td width="16%" class="text-center p-0" style="font-size: 12px;"></td>
                    <td width="16%" class="text-center p-0" style="font-size: 12px;"></td>
                    <td width="16%" class="text-center p-0" style="font-size: 12px;"></td>
                    <td width="16%" class="text-center p-0" style="font-size: 12px;"></td>
                </tr>
            </table>
            <table class="table mb-0" style="width: 100%; table-layout: fixed; margin-top: 25px!imporant;">
                <tr>
                    <td width="100%" class="text-center p-0" style="font-size: 15px; color: rgb(2, 68, 119); font-weight: 300">Interpretation</td>
                </tr>
            </table>
            <table class="table mb-0" style="width: 100%; table-layout: fixed; margin-top: 10px!imporant;">
                <tr>
                    <td width="100%" class="text-left p-0" style="font-size: 15px;">&nbsp;&nbsp;<i><b>Assessment:</b></i></td>
                </tr>
            </table>
            <table class="table mb-0" style="width: 100%; table-layout: fixed; margin-top: 10px!imporant;">
                <tr>
                    <td width="100%" class="text-left p-0" style="font-size: 16px;">&nbsp;&nbsp;<i><b>Post Assessment:</b></i></td>
                </tr>
            </table>
            <table class="table mb-0" style="width: 100%; table-layout: fixed; margin-top: 42px!imporant;">
                <tr>
                    <td width="100%" class="text-center p-0" style="font-size: 14px; color: rgb(2, 68, 119);"><b>Lagda ng Magulang</b></td>
                </tr>
            </table>
            <table class="table mb-0" style="width: 100%; table-layout: fixed; padding-left: 50px!important; padding-right: 50px!important;">
                <tr>
                    <td width="100%" class="text-center p-0" style="font-size: 14px; border-bottom: 1px solid #000;">&nbsp;</td>
                </tr>
                <tr>
                    <td width="100%" class="text-center p-0" style="font-size: 14px; border-bottom: 1px solid #000;">&nbsp;</td>
                </tr>
            </table>
        </td>
        <td width="33.3333333333%" style="vertical-align:top; padding-left: 5px; padding-right: 5px;">
            <table class="table" style="width: 100%; table-layout: fixed;">
                <tr>
                    <td width="20%" class="text-center p-0" style="font-size: 15px; vertical-align: middle;">
                        <img src="{{base_path()}}/public/assets/images/mci/doe.png" alt="school" width="60px">
                    </td>
                    <td width="80%" class="text-center p-0" style="font-size: 13px;">
                        <div>Republic of the Philippines</div>
                        <div><b>DEPARTMENT OF EDUCATION</b></div>
                        <div>Region IX, Zamboanga Peninsula</div>
                        
                        <div style="margin-top: 10px; font-size: 18px;"><b>MARIAN COLLEGE, INC.</b></div>
                        <div style="font-size: 17px;"><b>SY {{$schoolyear->sydesc}}</b></div>
                    </td>
                    <td width="20%" class="text-center p-0" style="font-size: 15px; vertical-align: middle;">
                        <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="80px">
                    </td>
                </tr>
            </table>
            <table class="table" style="width: 100%; table-layout: fixed;">
                <tr>
                    <td class="text-center p-0"><img src="{{base_path()}}/public/assets/images/mci/kindergarten.png" alt="school" width="220px"></td>
                </tr>
                <tr>
                    <td class="text-center p-0"><img src="{{base_path()}}/public/assets/images/mci/kids.png" alt="school" width="320px"></td>
                </tr>
                <tr>
                    <td width="100%" class="text-center p-0" style="font-size: 15px;"><b>Early Childhood Care and Development <br> (ECCD) Checklist</b></td>
                </tr>
            </table>
            <table class="table" style="width: 100%; table-layout: fixed;">
                <tr>
                    <td width="19%" class="text-left p-0" style="font-size: 14px;"><b>&nbsp;&nbsp;Pangalan: </b></td>
                    <td width="81%" class="text-lect p-0" style="font-size: 14px;"><b><u>{{$student->firstname.' '.$student->middlename[0].' '.$student->lastname}}</u></b></td>
                </tr>
            </table>
            <table class="table" style="width: 100%; table-layout: fixed;">
                <tr>
                    <td width="17%" class="text-left p-0" style="font-size: 14px;"><b>&nbsp;&nbsp;Kasarian: </b></td>
                    <td width="17%" class="text-left p-0" style="font-size: 14px; border-bottom: 1px solid #000;"></td>
                    <td width="12%" class="text-left p-0" style="font-size: 14px;"></td>
                    <td width="15%" class="text-left p-0" style="font-size: 14px;"><b>Kasarian: </b></td>
                    <td width="34%" class="text-lect p-0" style="font-size: 14px; border-bottom: 1px solid #000;"></td>
                    <td width="5%" class="text-left p-0" style="font-size: 14px;"></td>
                </tr>
            </table>
            <table class="table table-bordered" style="width: 100%; table-layout: fixed;">
                <tr>
                    <td width="17%" class="text-left p-0" style="font-size: 9px; vertical-align: top!important; padding-top: 5px!important; padding-left: 5px!important; padding-bottom: 40px!important;"><b>Date of 1st<br>Administration</b></td>
                    <td width="17%" class="text-center p-0" style="vertical-align: middle;">11-03-21</td>
                    <td width="12%" class="text-left p-0" style="font-size: 9px; vertical-align: top!important; padding-top: 5px!important; padding-left: 5px!important; padding-bottom: 40px!important;"><b>Child's <br> Age</b></td>
                    <td width="16%" class="text-center p-0" style="vertical-align: middle;">4-00-18</td>
                    <td width="17%" class="text-left p-0" style="font-size: 9px; vertical-align: top!important; padding-top: 5px!important; padding-left: 5px!important; padding-bottom: 40px!important;"><b>Overall <br>Interpretation</b></td>
                    <td width="17%" class="text-center p-0" style="vertical-align: middle;">AD</td>
                </tr>
                <tr>
                    <td width="17%" class="text-left p-0" style="font-size: 9px; vertical-align: top!important; padding-top: 5px!important; padding-left: 5px!important; padding-bottom: 40px!important;"><b>Date of 2nd<br>Administration</b></td>
                    <td width="17%" class="text-center p-0" style="vertical-align: middle;">04-04-22</td>
                    <td width="12%" class="text-left p-0" style="font-size: 9px; vertical-align: top!important; padding-top: 5px!important; padding-left: 5px!important; padding-bottom: 40px!important;"><b>Child's <br> Age</b></td>
                    <td width="16%" class="text-center p-0" style="vertical-align: middle;">5-10-19</td>
                    <td width="17%" class="text-left p-0" style="font-size: 9px; vertical-align: top!important; padding-top: 5px!important; padding-left: 5px!important; padding-bottom: 40px!important;"><b>Overall <br>Interpretation</b></td>
                    <td width="17%" class="text-center p-0" style="vertical-align: middle;">AD</td>
                </tr>
            </table>
            <table class="table mb-0" style="width: 100%; table-layout: fixed; padding-left: 8px; margin-top: 10px;">
                <tr>
                    <td width="100%"  class="text-left p-0"style="font-size: 14px;"><b>Para sa mga Magulang</b></td>
                </tr>
            </table>
            <table class="table" style="width: 100%; table-layout: fixed; padding-left: 8px;">
                <tr>
                    <td width="100%"  class="p-0"style="font-size: 14px; text-align: justify; line-height: 20px;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ang Philippine Early Childhood Checklist (Form 2) ay 
                        nagtataglay ng mga kakayahan, ugali at kaalaman ng mga 
                        batang 3 taon hanggang 5.11 taon. Ito ay maaaring gamiting 
                        gabay sa pagkilala ng inyong mga anak at sa kalaunan ay
                        makagawa ng angkop na pag-aalaga, pagtuturo at 
                        paggabay sa kanilang pagpapalaki at pag-unlad.
                    </td>
                </tr>
            </table>
            <table class="table" style="width: 100%; table-layout: fixed; padding-left: 8px; margin-top: 10px;">
                <tr>
                    <td width="50%"  class="text-left p-0"></td>
                    <td width="50%"  class="text-center p-0" style="font-size: 14px; border-bottom: 1px solid #000;"><b>JAMAICA E. JAVELLANA, LPT</b></td>
                </tr>
                <tr>
                    <td width="50%"  class="text-left p-0"></td>
                    <td width="50%"  class="text-center p-0" style="font-size: 14px;">Adviser</td>
                </tr>
            </table>
            <table class="table" style="width: 100%; table-layout: fixed; padding-left: 8px;">
                <tr>
                    <td width="45%"  class="text-center p-0" style="font-size: 14px; border-bottom: 1px solid #000;"><b>CYNTHIA H. ORTEGA, MA</b></td>
                    <td width="55%"  class="text-left p-0"></td>
                </tr>
                <tr>
                    <td width="45%"  class="text-center p-0" style="font-size: 14px;">Principal</td>
                    <td width="55%"  class="text-left p-0"></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed;">
    <tr>
        <td width="33.3333333333%" style="vertical-align:top; padding-left: 5px; padding-right: 5px;">
            <table class="table table-bordered mb-0" style="width: 100%; table-layout: fixed;">
                <tr style="background-color:#D9E2F3;">
                    <td colspan="2" class="text-center p-0" style="font-size: 12px;"><b>Gross Motor Domain</b></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"><b>1st</b></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"><b>2nd</b></td>
                </tr>
                <tr>
                    <td width="8%" class="text-left p-0" style="font-size: 12px;">&nbsp;&nbsp;1</td>
                    <td width="72%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Umaakyat ng mga silya</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="8%" class="text-left p-0" style="font-size: 12px;">&nbsp;&nbsp;2</td>
                    <td width="72%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Lumalakad ng paurong</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="8%" class="text-left p-0" style="font-size: 12px;">&nbsp;&nbsp;3</td>
                    <td width="72%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Bumababa ng hagdan habang hawak ng tagapag-alaga nag isang kamay</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="8%" class="text-left p-0" style="font-size: 12px;">&nbsp;&nbsp;4</td>
                    <td width="72%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Umaakyat ng hagdan na salitan ang mga paa bawat baiting, habang humahawak sa gabay ng hagdan</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="8%" class="text-left p-0" style="font-size: 12px;">&nbsp;&nbsp;5</td>
                    <td width="72%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Umaakyat ng hagdan na salitan ang mga paa na hindi humahawak sa gabay ng hagdan.</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="8%" class="text-left p-0" style="font-size: 12px;">&nbsp;&nbsp;6</td>
                    <td width="72%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Bumababa ng hagdan na salitan ang mga paa na hindi humahawak sa gabay ng hagdan.</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="8%" class="text-left p-0" style="font-size: 12px;">&nbsp;&nbsp;7</td>
                    <td width="72%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Tumatakbo na hindi nadadapa</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="8%" class="text-left p-0" style="font-size: 12px;">&nbsp;&nbsp;8</td>
                    <td width="72%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Tumatalon</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="8%" class="text-left p-0" style="font-size: 12px;">&nbsp;&nbsp;9</td>
                    <td width="72%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Lumulundag ng 1-3 beses gamit ang mas gustong paa</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="8%" class="text-left p-0" style="font-size: 12px;">&nbsp;&nbsp;10</td>
                    <td width="72%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Tumatalon at umiikot</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="8%" class="text-left p-0" style="font-size: 12px;">&nbsp;&nbsp;11</td>
                    <td width="72%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Ginagalaw ang mga parte ng katawan kapag inuutusan</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="8%" class="text-left p-0" style="font-size: 12px;">&nbsp;&nbsp;12</td>
                    <td width="72%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Sumasayaw/sumusunod sa mga hakbang sa sayaw, grupong gawain ukol sa kilos at galaw.</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="8%" class="text-left p-0" style="font-size: 12px;">&nbsp;&nbsp;13</td>
                    <td width="72%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Hinahagis ang bola paitaas na may direksyon.</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr style="background-color:#D9E2F3;">
                    <td colspan="2" class="text-center p-0" style="font-size: 11px; padding-left: 5px!important; padding-bottom: 5px!important;"><b>Bilang ng Iskor</b></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
            </table>

            <table class="table table-bordered mb-0" style="width: 100%; table-layout: fixed; margin-top: 10px;">
                <tr style="background-color:#D9E2F3;">
                    <td colspan="2" class="text-center p-0" style="font-size: 12px;"><b>Fine Motor Domain</b></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"><b>1st</b></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"><b>2nd</b></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 12px;">1</td>
                    <td width="71%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Nagpapakita ng higit na pagkagusto sa paggamit ng particular na kamay</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 12px;">2</td>
                    <td width="71%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Kinakabig ang mga laruan o pagkain</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 12px;">3</td>
                    <td width="71%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Kinukuha ang mga bagay gamit ang hinlalaki at hintuturo</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 12px;">4</td>
                    <td width="71%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Nailalagay/Tinatanggal ang maliliit na bagay mula sa lalagyan</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 12px;">5</td>
                    <td width="71%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Tinatanggal ang takip ng bote/lalagyan, inaalis ang balot ng mga pagkain.</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 12px;">6</td>
                    <td width="71%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Hinahawakan ang krayola gamit ang nakasarang palad.</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 12px;">7</td>
                    <td width="71%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Kusang gumuguhit-guhit</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 12px;">8</td>
                    <td width="71%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Gumuguhit ng patayo at pahalang na marka</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 12px;">9</td>
                    <td width="71%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Kusang gumuguhit ng bilog na hugis</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 12px;">10</td>
                    <td width="71%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Gumuguhit ng larawan ng tao (ulo, mata, katawan, braso, kamay, hita, paa)</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 12px;">11</td>
                    <td width="71%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Gumuguhit ng bahay gamit ang iba’t-ibang uri ng hugis (parisukat, tatsulok)</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr style="background-color:#D9E2F3;">
                    <td colspan="2" class="text-center p-0" style="font-size: 11px; padding-left: 5px!important;"><b>Bilang ng Iskor</b></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
            </table>
            <table class="table table-bordered mb-0" style="width: 100%; table-layout: fixed;">
                <tr style="background-color:#D9E2F3;">
                    <td colspan="2" class="text-center p-0" style="font-size: 12px;"><b>Self Help Domain</b></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"><b>1st</b></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"><b>2nd</b></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 12px;">1</td>
                    <td width="71%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Pinapakain ang sarili ng mga pagkain tulad ng biskwit at tinapay (finger food)</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 12px;">2</td>
                    <td width="71%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Pinapakain ang sarili gamit ang kutsara ngunit may natatapong pagkain</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 12px;">3</td>
                    <td width="71%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Pinapakain ang sarili gamit ang kutsara ngunit walang natatapong pagkain</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 12px;">4</td>
                    <td width="71%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Pinapakain ang sarili ng ulam at kanin gamit ang mga daliri na may natatapong pagkainan</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 12px;">5</td>
                    <td width="71%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Pinapakain ang sarili ng ulam at kanin gamit ang mga daliri na walang natatapong pagkain</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 12px;">6</td>
                    <td width="71%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Kumakaing hindi na kailangang subuan pa</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 12px;">7</td>
                    <td width="71%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Tumutulong sa paghawak ng baso/tasa sa pag-inom</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-center p-0" style="font-size: 12px;">8</td>
                    <td width="71%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Umiinom sa baso ngunit may natatapon</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
            </table>
        </td>
        <td width="33.3333333333%" style="vertical-align:top; padding-left: 5px; padding-right: 5px;">
            <table class="table table-bordered mb-0" style="width: 100%; table-layout: fixed;">
                <tr>
                    <td width="8%" class="text-center p-0" style="font-size: 12px;">9</td>
                    <td width="72%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Umiinom sa baso na walang umaalalay</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="8%" class="text-center p-0" style="font-size: 12px;">10</td>
                    <td width="72%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Kumukuha ng inumin ng mag-isa</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="8%" class="text-center p-0" style="font-size: 12px;">11</td>
                    <td width="72%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Binubuhos ang tubig (o anumang likido) mula sa pitsel na walang natatapon</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="8%" class="text-center p-0" style="font-size: 12px;">12</td>
                    <td width="72%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Naghahanda ng sariling pagkain/meryenda</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="8%" class="text-center p-0" style="font-size: 12px;">13</td>
                    <td width="72%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Naghahanda ng pagkain para sa nakababatang kapatid/ibang miyembro ng pamilya kung walang matanda sa bahay</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr style="background-color:#D9E2F3;">
                    <td colspan="2" class="text-center p-0" style="font-size: 11px; padding-left: 5px!important;"><b>Bilang ng Iskor</b></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
            </table>
            <table class="table table-bordered mb-0" style="width: 100%; table-layout: fixed; margin-top: 5px;">
                <tr>
                    <td width="9%" class="text-right p-0" style="font-size: 12px;">14&nbsp;&nbsp;</td>
                    <td width="72%" class="text-left p-0" style="font-size: 12px; padding-left: 5px!important;">Nakikipagtulungan kung binibihisan (hal. Itinataas ang mga kamay at paa)</td>
                    <td width="9.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="9.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-right p-0" style="font-size: 12px;">15&nbsp;&nbsp;</td>
                    <td width="72%" class="text-left p-0" style="font-size: 12px; padding-left: 5px!important;">Hinuhubad ang shorts na may garter</td>
                    <td width="9.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="9.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-right p-0" style="font-size: 12px;">16&nbsp;&nbsp;</td>
                    <td width="72%" class="text-left p-0" style="font-size: 12px; padding-left: 5px!important;">Hinuhubad ang sando</td>
                    <td width="9.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="9.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-right p-0" style="font-size: 12px;">17&nbsp;&nbsp;</td>
                    <td width="72%" class="text-left p-0" style="font-size: 12px; padding-left: 5px!important;">Binibihisan ang sarili na walang tumutulong maliban sa pagbubutones at pagtatali</td>
                    <td width="9.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="9.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-right p-0" style="font-size: 12px;">18&nbsp;&nbsp;</td>
                    <td width="72%" class="text-left p-0" style="font-size: 12px; padding-left: 5px!important;">Binibihisan ang sarili na walang tumutulong kasama na ang pagbubutones at pagtatali</td>
                    <td width="9.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="9.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td colspan="3" class="text-center p-0" style="font-size: 12px; padding-left: 5px!important;"><b>Toilet Training: Sub-Domain</b></td>
                    <td width="9.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-right p-0" style="font-size: 12px;">19&nbsp;&nbsp;</td>
                    <td width="72%" class="text-left p-0" style="font-size: 12px; padding-left: 5px!important;">Ipinapakita o ipinapahiwatig na naihi o nadumi sa shorts</td>
                    <td width="9.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="9.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-right p-0" style="font-size: 12px;">20&nbsp;&nbsp;</td>
                    <td width="72%" class="text-left p-0" style="font-size: 12px; padding-left: 5px!important;">Pinapaalam sa tagapag-alaga ang pangangailangang umihi o dumumi  upang makapunta sa tamang lugar( hal. banyo, CR)</td>
                    <td width="9.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="9.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-right p-0" style="font-size: 12px;">21&nbsp;&nbsp;</td>
                    <td width="72%" class="text-left p-0" style="font-size: 12px; padding-left: 5px!important;">Pumupunta sa tamang lugar upang umihi o dumumi ngunit paminsan-minsan ay may pagkakataong hindi mapigilang maihi o madumi sa shorts</td>
                    <td width="9.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="9.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-right p-0" style="font-size: 12px;">22&nbsp;&nbsp;</td>
                    <td width="72%" class="text-left p-0" style="font-size: 12px; padding-left: 5px!important;">Matagumpay na pumupunta sa tamang lugar upang umihi o dumumi</td>
                    <td width="9.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="9.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-right p-0" style="font-size: 12px;">23&nbsp;&nbsp;</td>
                    <td width="72%" class="text-left p-0" style="font-size: 12px; padding-left: 5px!important;">Pinupunasan ang sarili pagkatapos dumumi</td>
                    <td width="9.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="9.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-right p-0" style="font-size: 12px;">24&nbsp;&nbsp;</td>
                    <td width="72%" class="text-left p-0" style="font-size: 12px; padding-left: 5px!important;">Nakikipagtulungan kung pinapaliguan (hal. kinukuskos ang mga braso)</td>
                    <td width="9.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="9.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-right p-0" style="font-size: 12px;">25&nbsp;&nbsp;</td>
                    <td width="72%" class="text-left p-0" style="font-size: 12px; padding-left: 5px!important;">Naliligo ng walang tumutulong</td>
                    <td width="9.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="9.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-right p-0" style="font-size: 12px;">26&nbsp;&nbsp;</td>
                    <td width="72%" class="text-left p-0" style="font-size: 12px; padding-left: 5px!important;">Naghuhugas at nagpupunas ng mga kamay ng walang tumutulong</td>
                    <td width="9.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="9.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="9%" class="text-right p-0" style="font-size: 12px;">27&nbsp;&nbsp;</td>
                    <td width="72%" class="text-left p-0" style="font-size: 12px; padding-left: 5px!important;">Naghihilamos ng mukha ng walang tumutulong</td>
                    <td width="9.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="9.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr style="background-color:#D9E2F3;">
                    <td colspan="2" class="text-center p-0" style="font-size: 12px; padding-left: 5px!important;"><b>Bilang ng Iskor</b></td>
                    <td width="9.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="9.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
            </table>
            <table class="table table-bordered mb-0" style="width: 100%; table-layout: fixed; margin-top: 5px;">
                <tr style="background-color:#D9E2F3;">
                    <td colspan="2" class="text-center p-0" style="font-size: 13px;"><b>Receptive Language Domain</b></td>
                    <td width="8.5%" class="text-center p-0 align-middle" style="font-size: 11px;"><b>1st</b></td>
                    <td width="9.5%" class="text-center p-0 align-middle" style="font-size: 11px;"><b>2nd</b></td>
                </tr>
                <tr>
                    <td width="7%" class="text-right p-0" style="font-size: 12px;">1&nbsp;&nbsp;</td>
                    <td width="75%" class="text-left p-0" style="font-size: 12px; padding-left: 5px!important;">Tinuturo ang mga kapamilya o pamilyar  na bagay kapag ipinaturo</td>
                    <td width="8.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="9.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="7%" class="text-right p-0" style="font-size: 12px;">2&nbsp;&nbsp;</td>
                    <td width="75%" class="text-left p-0" style="font-size: 12px; padding-left: 5px!important;">Tinuturo ang 5 parte ng katawan kung inuutusan</td>
                    <td width="8.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="9.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="7%" class="text-right p-0" style="font-size: 12px;">3&nbsp;&nbsp;</td>
                    <td width="75%" class="text-left p-0" style="font-size: 12px; padding-left: 5px!important;">Tinuturo ang 5 napangalanang larawan ng mga <br> bagay</td>
                    <td width="8.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="9.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="7%" class="text-right p-0" style="font-size: 12px;">4&nbsp;&nbsp;</td>
                    <td width="75%" class="text-left p-0" style="font-size: 12px; padding-left: 5px!important;">Sumusunod sa isang lebel na utos na may simpleng pang-ukol (hal. sa ibabaw, sa ilalim)</td>
                    <td width="8.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="9.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="7%" class="text-right p-0" style="font-size: 12px;">5&nbsp;&nbsp;</td>
                    <td width="75%" class="text-left p-0" style="font-size: 12px; padding-left: 5px!important;">Sumusunod sa dalawang lebel na utos na may simpleng pang-ukol</td>
                    <td width="8.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="9.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr style="background-color:#D9E2F3;">
                    <td colspan="2" class="text-center p-0" style="font-size: 13px; padding-left: 5px!important;"><b>Bilang ng Iskor</b></td>
                    <td width="8.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="9.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
            </table>
            <table class="table table-bordered mb-0" style="width: 100%; table-layout: fixed;">
                <tr style="background-color:#D9E2F3;">
                    <td colspan="2" class="text-center p-0" style="font-size: 13px;"><b>Expressive Language Domain</b></td>
                    <td width="8.5%" class="text-center p-0 align-middle" style="font-size: 11px;"><b>1st</b></td>
                    <td width="9.5%" class="text-center p-0 align-middle" style="font-size: 11px;"><b>2nd</b></td>
                </tr>
                <tr>
                    <td width="7%" class="text-right p-0" style="font-size: 12px;">1&nbsp;&nbsp;</td>
                    <td width="75%" class="text-left p-0" style="font-size: 12px; padding-left: 5px!important;">Gumagamit ng 5-20 nakikilalang salita <br>&nbsp;</td>
                    <td width="8.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="9.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="7%" class="text-right p-0" style="font-size: 12px;">2&nbsp;&nbsp;</td>
                    <td width="75%" class="text-left p-0" style="font-size: 12px; padding-left: 5px!important;">Napapangalanan ang mga bagay na nakikita sa larawan </td>
                    <td width="8.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="9.5%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
            </table>
        </td>
        <td width="33.3333333333%" style="vertical-align:top; padding-left: 5px; padding-right: 5px;">
            <table class="table table-bordered mb-0" style="width: 100%; table-layout: fixed; margin-left: 5px;">
                <tr>
                    <td width="7%" class="text-right p-0" style="font-size: 12px;">3&nbsp;&nbsp;</td>
                    <td width="75%" class="text-left p-0" style="font-size: 12px; padding-left: 5px!important;">Gumagamit ng 2-3 kombinasyon ng pandiwa-pantangi (verb-noun combinations) (Hal. hingi pera)</td>
                    <td width="8%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="7%" class="text-right p-0" style="font-size: 12px;">4&nbsp;&nbsp;</td>
                    <td width="75%" class="text-left p-0" style="font-size: 12px; padding-left: 5px!important;">Gumagamit ng panghalip (hal. ako, akin) <br> &nbsp;</td>
                    <td width="8%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="7%" class="text-right p-0" style="font-size: 12px;">5&nbsp;&nbsp;</td>
                    <td width="75%" class="text-left p-0" style="font-size: 12px; padding-left: 5px!important;">Nagsasalita sa tamang pangungusap na may 2-3 salita.</td>
                    <td width="8%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="7%" class="text-right p-0" style="font-size: 12px;">6&nbsp;&nbsp;</td>
                    <td width="75%" class="text-left p-0" style="font-size: 12px; padding-left: 5px!important;">Kinukwento ang mga katatapos na karanasan (kapag tinanong/ diniktahan) na naaayon sa pagkasunod-sunod ng pangyayari </td>
                    <td width="8%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="7%" class="text-right p-0" style="font-size: 12px;">7&nbsp;&nbsp;</td>
                    <td width="75%" class="text-left p-0" style="font-size: 12px; padding-left: 5px!important;">Nagtatanong ng ano.</td>
                    <td width="8%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="7%" class="text-right p-0" style="font-size: 12px;">8&nbsp;&nbsp;</td>
                    <td width="75%" class="text-left p-0" style="font-size: 12px; padding-left: 5px!important;">Nagtatanong ng sino at bakit</td>
                    <td width="8%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr style="background-color:#D9E2F3;">
                    <td colspan="2" class="text-center p-0" style="font-size: 13px; padding-left: 5px!important;"><b>Bilang ng Iskor</b></td>
                    <td width="8%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
            </table>
            <table class="table table-bordered mb-0" style="width: 100%; table-layout: fixed; margin-top: 5px;">
                <tr style="background-color:#D9E2F3;">
                    <td colspan="2" class="text-center p-0" style="font-size: 12px;"><b>Cognitive Domain</b></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"><b>1st</b></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"><b>2nd</b></td>
                </tr>
                <tr>
                    <td width="7%" class="text-left p-0" style="font-size: 11px;">&nbsp;&nbsp;1</td>
                    <td width="73%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Tinitingnan ang direksyon ng nahuhulog na bagay <br>&nbsp;</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="7%" class="text-left p-0" style="font-size: 11px;">&nbsp;&nbsp;2</td>
                    <td width="73%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Hinahanap ang mga bagay na bahagyang nakatago<br>&nbsp;</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="7%" class="text-left p-0" style="font-size: 11px;">&nbsp;&nbsp;3</td>
                    <td width="73%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Hinahanap ang mga bagay na lubusang nakatago<br>&nbsp;</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="7%" class="text-left p-0" style="font-size: 11px;">&nbsp;&nbsp;4</td>
                    <td width="73%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Binibigay ang mga bagay ngunit hindi ito binibitawan<br>&nbsp;</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="7%" class="text-left p-0" style="font-size: 11px;">&nbsp;&nbsp;5</td>
                    <td width="73%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Ginagaya ang mga kilos na kakakita pa lamang<br>&nbsp;</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="7%" class="text-left p-0" style="font-size: 11px;">&nbsp;&nbsp;6</td>
                    <td width="73%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Naglalaro ng kunwari-kunwarian<br>&nbsp;</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="7%" class="text-left p-0" style="font-size: 11px;">&nbsp;&nbsp;7</td>
                    <td width="73%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Tinutugma ang mga bagay<br>&nbsp;</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="7%" class="text-left p-0" style="font-size: 11px;">&nbsp;&nbsp;8</td>
                    <td width="73%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Tinutugma ang mga 2-3 kulay<br>&nbsp;</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="7%" class="text-left p-0" style="font-size: 11px;">&nbsp;&nbsp;9</td>
                    <td width="73%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Tinutugma ang mga larawan<br>&nbsp;</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="7%" class="text-left p-0" style="font-size: 11px;">&nbsp;&nbsp;10</td>
                    <td width="73%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Nakikilala ang magkakapareho at magkakaibang hugis<br>&nbsp;</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="7%" class="text-left p-0" style="font-size: 11px;">&nbsp;&nbsp;11</td>
                    <td width="73%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Inaayos ang mga bagay ayon sa dalawang katangian(hal. laki at hugis)</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="7%" class="text-left p-0" style="font-size: 11px;">&nbsp;&nbsp;12</td>
                    <td width="73%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Inaayos ang mga bagay mula sa dalawang katangian (laki/kulay)</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="7%" class="text-left p-0" style="font-size: 11px;">&nbsp;&nbsp;13</td>
                    <td width="73%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Pinapangalanan ang 4-6 na kulay<br>&nbsp;</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="7%" class="text-left p-0" style="font-size: 11px;">&nbsp;&nbsp;14</td>
                    <td width="73%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Gumuguhit/ ginagaya ang isang disenyo<br>&nbsp;</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="7%" class="text-left p-0" style="font-size: 11px;">&nbsp;&nbsp;15</td>
                    <td width="73%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Pinapangalanan ang 3 hayop o gulay kapag tinatanong<br>&nbsp;</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="7%" class="text-left p-0" style="font-size: 11px;">&nbsp;&nbsp;16</td>
                    <td width="73%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Sinasabi ang mga gamit ng mga bagay sa bahay<br>&nbsp;</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="7%" class="text-left p-0" style="font-size: 11px;">&nbsp;&nbsp;17</td>
                    <td width="73%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Nakakabuo ng isang simpleng puzzle</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="7%" class="text-left p-0" style="font-size: 11px;">&nbsp;&nbsp;18</td>
                    <td width="73%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Naiintindihan ang magkakasalungat na mga salita sa pamamagitan ng pagkumpleto ng pangungusap. (Hal. Ang aso ay malaki. Ang daga ay______________) </td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="7%" class="text-left p-0" style="font-size: 11px;">&nbsp;&nbsp;19</td>
                    <td width="73%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Tinuturo ang kaliwa at kanang bahagi ng katawan</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="7%" class="text-left p-0" style="font-size: 11px;">&nbsp;&nbsp;20</td>
                    <td width="73%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Nasasabi kung ano ang mali sa larawan (hal. Ano ang mali sa larawan?)</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr>
                    <td width="7%" class="text-left p-0" style="font-size: 11px;">&nbsp;&nbsp;21</td>
                    <td width="73%" class="text-left p-0" style="font-size: 11px; padding-left: 5px!important;">Tinutugma ang malalaki sa maliliit na titik.</td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
                <tr style="background-color:#D9E2F3;">
                    <td colspan="2" class="text-center p-0" style="font-size: 12px; padding-left: 5px!important;"><b>Bilang ng Iskor</b></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                    <td width="10%" class="text-center p-0 align-middle" style="font-size: 11px;"></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>