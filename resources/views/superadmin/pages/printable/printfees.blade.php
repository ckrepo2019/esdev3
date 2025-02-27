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

		.grades td{
            padding-top: .1rem;
            padding-bottom: .1rem;
            font-size: .7rem !important;
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

        <table class="table mb-0 table-sm header" style="font-size:13px;">
            <tr>
                <td width="50%" >
                   Student : {{$studinfo->fullname}}
                </td>
				<td width="50%" class="text-right" >
                   Current Fee  : {{isset($tuitionheader->description) ? isset($tuitionheader->description) : ''}}
                </td>
            </tr>
        </table>
        <table class="table mb-0 table-sm grades table-bordered" style="font-size:13px;">
			@php
				$grandtotal = 0;
			@endphp
            @foreach($tuition as $item)
				@php
					$totaldetail = 0;
				@endphp
				<tr >
					<td colspan="2"><b>{{$item->classname}}</b></td>
				</tr>
				@if(count($item->details) > 1)
				  @foreach($item->details as $details)
				  	<tr>
						<td style="padding-left:1.5rem !important">{{$details->itemname}}</td>
						<td  class="text-right">{{number_format($details->amount,2)}}</td>
					</tr>
					@php
						$totaldetail += $details->amount;
						$grandtotal += $details->amount;
					@endphp
				  @endforeach
					<tr>
						<td class="text-right"><b>TOTAL:   </b></td>
						<td class="text-right">{{number_format($totaldetail,2)}}</td>
					</tr>
				@else
					@foreach($item->details as $details)
						<tr>
							<td style="padding-left:1.5rem !important">{{$details->itemname}}</td>
							<td class="text-right">{{number_format($details->amount,2)}}</td>
						</tr>
						@php
							$grandtotal += $details->amount;
						@endphp
					@endforeach
					
					
				@endif
				
			@endforeach
			<tr>
				<td>&nbsp;</td>
				<td></td>
			</tr>
			<tr>
				<td class="text-right"><b>GRAND TOTAL:</b></td>
				<td class="text-right">{{number_format($grandtotal,2)}}</td>
			</tr>
        </table>
       
    </body>
</html>