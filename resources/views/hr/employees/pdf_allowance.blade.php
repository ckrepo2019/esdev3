<style>
	.watermark {
		opacity: .1;
		position: absolute;
		left: 30%;
		bottom: 60%;
	}
	.watermark2 {
		opacity: .1;
		position: absolute;
		left: 30%;
		top: 62%;
	}
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
   .p-1 {
        padding-top: 3px!important;
        padding-bottom: 3px!important;
   }
   .p-2 {
        padding-top: 8px!important;
        padding-bottom: 8px!important;
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
        transform-origin: 10 10;
        transform: rotate(-90deg);
    }
    .trhead {
        background-color: rgb(167, 223, 167); 
        color: #000; font-size
    }
    .trhead td {
        border: 3px solid #000;
    }

    body { 
        margin: 0px 0px;
        padding: 0px 0px;
    }
    
     .check_mark {
           font-family: ZapfDingbats, sans-serif;
        }
    @page{ size: 8.5in 13in; margin: 20px 20px;}
    
</style>
<div>
	<div class="row watermark">
		<div class="col-md-12">
			<img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="300px">
		</div>
	</div>
</div>
<table width="100%" class="table table-sm mb-0" style="padding-top: 10px; table-layout: fixed">
    <tr>
        <td>
            @php
                date_default_timezone_set('Asia/Manila');
                $currentDateTime = date('F j, Y g:i:s A');
                echo $currentDateTime;
            @endphp
        </td>
    </tr>
    <tr>
        <td class="p-0 text-center" style="">
            <div style="font-size: 20px!important;"><b>{{$schoolinfo[0]->schoolname}}</b></div>
            <div style="font-size: 13px!important;">{{$schoolinfo[0]->address}}</div>
            {{-- <div style="font-size: 13px!important;">, 111</div> --}}
        </td>
    </tr>

</table>
<table width="100%" class="table table-sm mb-0" style="padding-top: 30px; table-layout: fixed">
    <tr>
         <td class="p-0 text-center">
             <div style="font-size: 18px!important;"><b>ACCOUNT LISTING - {{$allowanceinfo->description}}</b></div>
         </td>   
    </tr>
     <tr>
         <td class="p-0 text-center" style="font-size: 11px;">
             <b>
                 @if ($payrollinfo)
                     {{ date('mdY', strtotime($payrollinfo->dateto)) }} - {{ date('F j, Y', strtotime($payrollinfo->datefrom)) . ' to ' . date('F j, Y', strtotime($payrollinfo->dateto)) }}
                 @else
                     No Payroll Info
                 @endif
             </b>
         </td>
     </tr>
 </table>
<table width="100%" class="table table-sm mb-0" style="padding-top: 30px; table-layout: fixed">
    <tr>
        <th width="15%" class="text-left" style="border-top: 1px solid #000; border-bottom: 1px solid #000;"><b>CUTOFF</b></th> 
        <th width="50%" class="text-left" style="border-top: 1px solid #000; border-bottom: 1px solid #000;"><b>NAME</b></th> 
        <th width="20%" class="text-left" style="border-top: 1px solid #000; border-bottom: 1px solid #000;"><b>ALLOWANCE CODE</b></th> 
        <th width="15%" class="text-right" style="border-top: 1px solid #000; border-bottom: 1px solid #000;"><b>AMOUNT</b></th> 
    </tr>

    @foreach ($particulars as $particular)
        <tr>
            <td class="p-0" style="padding: 5px 0px 5px 5px !important;">{{ date('mdY', strtotime($payrollinfo->dateto)) }}</td>
            <td class="p-0">{{$particular->full_name}}</td>
            <td class="text-left p-0">{{$allowanceinfo->description}}</td>
            <td class="text-right p-0">{{number_format($particular->amountpaid,2)}}</td>
        </tr>
    @endforeach
    
</table>
<table width="100%" class="table table-sm mb-0" style="padding-top: 30px; table-layout: fixed;border-top: 1px solid #000; ">
    <tr>
        <td class="text-right"><span><b>TOTAL &nbsp;&nbsp;>> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;</b></span><span style=""><b>{{number_format($totalpaidallowance,2)}}</b></span></td>
    </tr>
</table>