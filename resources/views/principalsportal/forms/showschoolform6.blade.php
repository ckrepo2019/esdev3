
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        html{
             font-family: Arial, Helvetica, sans-serif;
     
         }
         table {
             font-size: 8px !important;
             border: 1px solid black;
             table-layout: fixed;
             width: 1250px !important;
             border-top:hidden !important;
         }
         table .t-lg{
             width: 250px !important;
         }
         table .t-md{
             width: 150px !important;
         }
 
         table .t-sm{
             width: 100px !important;
         }
 
         table tr td{
             vertical-align: middle !important;
             font-size: 8px !important;
             border:solid black 1px;
             padding: 4px !important;
 
         }
 
         table th{
             font-size: 8px !important;
             border:solid black 1px;
             padding:4px !important;
         }
 
 
 
         table{
             border-spacing: 0
         }
 
         td{
             border-left: hidden !important;
             border-top: hidden !important;
         }
 
         th{
        
             border-left: hidden !important;
             border-top: hidden !important;
         }
         
 
         .text-center {
             text-align: center!important;
         }      
         .align-middle {
             vertical-align: middle!important;
         } 
         
 
         .text-right {
             text-align: right!important;
         }
         .text-left {
             text-align: left!important;
         }
 
         .border-0 {
             border: 0!important;
         }
 
         .border-bottom {
             border-bottom: 1px solid black!important;
         }
         .border-left {
             border-left: 1px solid black!important;
         }
         .border-top {
             border-top: 1px solid black!important;
         }
 
         .font-italic {
             font-style: italic!important;
         }
         img {
             width:50%;
             display: block;
         }
 
         footer {
                 position: fixed; 
                 bottom: -30px; 
                 height: 50px; 
                 text-align: center;
                 line-height: 35px;
                 font-size:8px;
             }

        .border-bottom-0{
            border-bottom: 0!important;
        }

        #sf6{
            margin-top: -35px;
        }
         
        
     </style>
</head>

<body>
    <table  class="border-0">
        <tr>
            <td style="padding:0 !important; margin:0  !important;" style="width: 13%" rowspan="4" colspan="4" class="text-center align-middle border-0" >
                {{-- /public/assets/images/department_of_Education.png --}}
                <img  src="{{asset('assets/images/department_of_Education.png')}}" alt="school" >
            </td>
            <td  colspan="37" class="text-center border-0" style="font-size:20px !important;">School Form 6 (SF6)<br><span class="text-center border-0 " style="font-size:20px !important; padding:0 !important">Summarized Report on Promotion and Level of Proficiency</span>
                <br><span class="font-italic" style="font-size:10px !important; padding:0 !important">(This replaces Form 20)</span>
            </td>
        </tr>
       
        <tr>
            <td colspan="37" class="border-0 text-right" >&nbsp;</td>
        </tr>
        <tr>
            <td colspan="37" class="border-0 text-right" >&nbsp;</td>
        </tr>
        <tr>
            <td colspan="37" class="border-0 text-right" >&nbsp;</td>
        </tr>
         
       
       
       <tr>
            <td style="width: 5%" colspan="2" class="border-0 "></td>
            <td style="width: 8%" class="text-right border-0" colspan="2"></td>
            <td colspan="2" class="border-0 text-right">School ID</td>
            <td colspan="4" class="border-bottom border-left border-top">{{$schoolinfo[0]->schoolid}}</td>
            <td colspan="4" class="border-0 text-right">Region</td>
            <td colspan="5" class="border-bottom border-left border-top">{{$schoolinfo[0]->regDesc}}</td>
            <td colspan="2" class="border-0 text-right">Division</td>
            <td colspan="13" class="border-bottom border-left border-top">{{$schoolinfo[0]->citymunDesc}}</td>
    
            <td colspan="7" class="border-0"></td>
        </tr>
          
        <tr>
            <td  class="border-0" colspan="41" style="padding:0 !important">&nbsp;</td>
        </tr>
        <tr>
            <td style="width: 5%" colspan="2" class="border-0 "></td>
            <td style="width: 8%" class="text-right border-0" colspan="2">School Name</td>
            <td colspan="15" class="border-bottom border-left border-top">{{$schoolinfo[0]->schoolname}}</td>
            <td colspan="2" class="border-0 text-right">District</td>
            <td colspan="8" class="border-bottom border-left border-top">{{$schoolinfo[0]->district}}</td>
            <td colspan="7" class="border-0 text-right">School Year</td>
            <td colspan="5" class="border-bottom border-left border-top">{{Session::get('schoolYear')->sydesc}}</td>
           
        </tr>
    </table>
    @include('search.principal.sf6')
</body>
</html>

