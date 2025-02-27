<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title></title>

	<style>
	.body{
		font-family: Arial, Helvetica, sans-serif;
		font-size: 10px;
	}
	hr {
	  box-sizing: content-box;
	  height: 0;
	  overflow: visible;
	}

	.table {
	  width: 100%;
	  margin-bottom: 1rem;
	  color: #212529;
	}





.table th,
.table td {
  /*padding: 0.25rem;*/
  padding: 3px;
  vertical-align: top;
  border-top: 1px solid #dee2e6;
}

.table thead th {
  vertical-align: bottom;
  border-bottom: 0px solid #dee2e6;
}

.table tbody + tbody {
  border-top: 2px solid #dee2e6;
}

.table-sm th,
.table-sm td {
  padding: 0.3rem;
}

.table-bordered {
  border: 1px solid #dee2e6;
}

.table-bordered th,
.table-bordered td {
  border: 1px solid #dee2e6;
}

.table-bordered thead th,
.table-bordered thead td {
  border-bottom-width: 1px;
}

.table-borderless th,
.table-borderless td,
.table-borderless thead th,
.table-borderless tbody + tbody {
  border: 0;
}

.table-striped tbody tr:nth-of-type(odd) {
  background-color: rgba(0, 0, 0, 0.05);
}

.table-hover tbody tr:hover {
  color: #212529;
  background-color: rgba(0, 0, 0, 0.075);
}

.table-primary,
.table-primary > th,
.table-primary > td {
  background-color: #b8daff;
}

.table-primary th,
.table-primary td,
.table-primary thead th,
.table-primary tbody + tbody {
  border-color: #7abaff;
}

.table-hover .table-primary:hover {
  background-color: #9fcdff;
}

.table-hover .table-primary:hover > td,
.table-hover .table-primary:hover > th {
  background-color: #9fcdff;
}

.table-secondary,
.table-secondary > th,
.table-secondary > td {
  background-color: #d6d8db;
}

.table-secondary th,
.table-secondary td,
.table-secondary thead th,
.table-secondary tbody + tbody {
  border-color: #b3b7bb;
}

.table-hover .table-secondary:hover {
  background-color: #c8cbcf;
}

.table-hover .table-secondary:hover > td,
.table-hover .table-secondary:hover > th {
  background-color: #c8cbcf;
}

.table-success,
.table-success > th,
.table-success > td {
  background-color: #c3e6cb;
}

.table-success th,
.table-success td,
.table-success thead th,
.table-success tbody + tbody {
  border-color: #8fd19e;
}

.table-hover .table-success:hover {
  background-color: #b1dfbb;
}

.table-hover .table-success:hover > td,
.table-hover .table-success:hover > th {
  background-color: #b1dfbb;
}

.table-info,
.table-info > th,
.table-info > td {
  background-color: #bee5eb;
}

.table-info th,
.table-info td,
.table-info thead th,
.table-info tbody + tbody {
  border-color: #86cfda;
}

.table-hover .table-info:hover {
  background-color: #abdde5;
}

.table-hover .table-info:hover > td,
.table-hover .table-info:hover > th {
  background-color: #abdde5;
}

.table-warning,
.table-warning > th,
.table-warning > td {
  background-color: #ffeeba;
}

.table-warning th,
.table-warning td,
.table-warning thead th,
.table-warning tbody + tbody {
  border-color: #ffdf7e;
}

.table-hover .table-warning:hover {
  background-color: #ffe8a1;
}

.table-hover .table-warning:hover > td,
.table-hover .table-warning:hover > th {
  background-color: #ffe8a1;
}

.table-danger,
.table-danger > th,
.table-danger > td {
  background-color: #f5c6cb;
}

.table-danger th,
.table-danger td,
.table-danger thead th,
.table-danger tbody + tbody {
  border-color: #ed969e;
}

.table-hover .table-danger:hover {
  background-color: #f1b0b7;
}

.table-hover .table-danger:hover > td,
.table-hover .table-danger:hover > th {
  background-color: #f1b0b7;
}

.table-light,
.table-light > th,
.table-light > td {
  background-color: #fdfdfe;
}

.table-light th,
.table-light td,
.table-light thead th,
.table-light tbody + tbody {
  border-color: #fbfcfc;
}

.table-hover .table-light:hover {
  background-color: #ececf6;
}

.table-hover .table-light:hover > td,
.table-hover .table-light:hover > th {
  background-color: #ececf6;
}

.table-dark,
.table-dark > th,
.table-dark > td {
  background-color: #c6c8ca;
}

.table-dark th,
.table-dark td,
.table-dark thead th,
.table-dark tbody + tbody {
  border-color: #95999c;
}

.table-hover .table-dark:hover {
  background-color: #b9bbbe;
}

.table-hover .table-dark:hover > td,
.table-hover .table-dark:hover > th {
  background-color: #b9bbbe;
}

.table-active,
.table-active > th,
.table-active > td {
  background-color: rgba(0, 0, 0, 0.075);
}

.table-hover .table-active:hover {
  background-color: rgba(0, 0, 0, 0.075);
}

.table-hover .table-active:hover > td,
.table-hover .table-active:hover > th {
  background-color: rgba(0, 0, 0, 0.075);
}

.table .thead-dark th {
  color: #fff;
  background-color: #343a40;
  border-color: #454d55;
}

.table .thead-light th {
  color: #495057;
  background-color: #e9ecef;
  border-color: #dee2e6;
}

.table-dark {
  color: #fff;
  background-color: #343a40;
}

.table-dark th,
.table-dark td,
.table-dark thead th {
  border-color: #454d55;
}

.table-dark.table-bordered {
  border: 0;
}

.table-dark.table-striped tbody tr:nth-of-type(odd) {
  background-color: rgba(255, 255, 255, 0.05);
}

.table-dark.table-hover tbody tr:hover {
  color: #fff;
  background-color: rgba(255, 255, 255, 0.075);
}

@media (max-width: 575.98px) {
  .table-responsive-sm {
    display: block;
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }
  .table-responsive-sm > .table-bordered {
    border: 0;
  }
}

@media (max-width: 767.98px) {
  .table-responsive-md {
    display: block;
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }
  .table-responsive-md > .table-bordered {
    border: 0;
  }
}

@media (max-width: 991.98px) {
  .table-responsive-lg {
    display: block;
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }
  .table-responsive-lg > .table-bordered {
    border: 0;
  }
}

@media (max-width: 1199.98px) {
  .table-responsive-xl {
    display: block;
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }
  .table-responsive-xl > .table-bordered {
    border: 0;
  }
}

.table-responsive {
  display: block;
  width: 100%;
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
}

.table-responsive > .table-bordered {
  border: 0;
}

.text-left {
  text-align: left !important;
}

.text-bold {
  font-weight: bold !important;
}

.text-right {
  text-align: right !important;
}

.text-center {
  text-align: center !important;
}

.text-danger {
  color: #dc3545 !important;
}

.text-warning {
  color: #ffc107 !important;
}

.text-info {
  color: #17a2b8 !important;
}

.text-success {
  color: #28a745 !important;
}

.text-primary {
  color: #007bff !important;
}
.page-break {
    page-break-inside: avoid;
    page-break-after: auto; 
}
.print_border-bottom{
	border-bottom: solid 1px;
	padding-right: 80px;
}
.print_border-top{
	border-top: solid 1px;
	padding-right: 5px;
}
.print_border-left{
	border-left: solid 1px;
	padding-right: 5px;
}
.print_border-right{
	border-right: solid 1px;
	padding-right: 5px;
}





</style>
	
</head>
<body>
	<script type="text/php">
    
	    if (isset($pdf)) {
	        $x = 30;
	        $y = 570;
	        $text = "Page {PAGE_NUM} of {PAGE_COUNT} pages";
	        $font = null;
	        $size = 7;
	        $color = array(0,0,0);
	        $word_space = 0.0;  //  default
	        $char_space = 0.0;  //  default
	        $angle = 0.0;   //  default
	        $pdf->page_text($x, $y, $text, $font, $size, $color);
	    }
	    
	    	if (isset($pdf)) {
	        $x = 865;
	        $y = 570;
	        $text = "Date Printed: " . '{{date_format(date_create(\App\FinanceModel::getServerDateTime()), 'm-d-Y h:i')}}';
	        $font = null;
	        $size = 7;
	        $color = array(0,0,0);
	        $word_space = 0.0;  //  default
	        $char_space = 0.0;  //  default
	        $angle = 0.0;   //  default
	        $pdf->page_text($x, $y, $text, $font, $size, $color);
	    }
	 

	</script>

	@php
		$schoolinfo = db::table('schoolinfo')->first();
		$picurl = explode('?', $schoolinfo->picurl);
		$picurl = $picurl[0];
	@endphp

	<div class="body">
		<table  cellpadding="0" style="width: 100%;">
			<tr>
				<td style="width: 40%; text-align: right;">
          <img src="{{$picurl}}" width="90px">
        </td>
				<td class="text-center" style="width: 20%;">
					<span style="font-size: 12; font-weight: bold">{{$schoolinfo->schoolname}}</span><br>
					<span style="font-size: 10; font-weight: ">{{$schoolinfo->address}}</span>
				</td>
				<td style="width: 40%; text-align: right;">
          {{-- <img src="{{db::table('schoolinfo')->first()->picurl}}" width="90px"> --}}
        </td>
			</tr>
			<tr>
				<td colspan="3" class="text-center" style="font-size: 12; font-weight: bold;">Year-end Summary Report</td>
			</tr>
		</table>


		<table  cellpadding="0" style="width: 100%; border-collapse: collapse;">
			<thead>
				{!!$headerlist!!}	
			</thead>
			<tbody>
				{!!$bodylist!!}	
			</tbody>
		</table>
		
		

		{{-- <div class="table-responsive"> --}}
				
		@php
			$sig = db::table('finance_sigs')
				->where('id', 5)
				->first();
		@endphp
		@if($sig)
			<div class="page-break">
				<table cellpadding="0" style="width: 100%;">
					<tr>
						@if($sig->title_1 != null || $sig->title_1 != '')
							<td width="40%">{{$sig->title_1}}: </td>
						@endif
						@if($sig->title_2 != null || $sig->title_2 != '')
							<td width="40%">{{$sig->title_2}}: </td>
						@endif
						@if($sig->title_3 != null || $sig->title_3 != '')
							<td width="40%">{{$sig->title_3}}: </td>
						@endif
					</tr>
					<tr>
						@if($sig->title_1 != null || $sig->title_1 != '')
							<td width="40%" class="text-bold">
								<br>
								{{$sig->sig_1}} 
							</td>
						@endif
						@if($sig->title_2 != null || $sig->title_2 != '')
							<td width="40%" class="text-bold">
								<br>
								{{$sig->sig_2}} 
							</td>
						@endif
						@if($sig->title_3 != null || $sig->title_3 != '')
							<td width="40%" class="text-bold">
								<br>
								{{$sig->sig_3}} 
							</td>
						@endif
					</tr>
					<tr>
						@if($sig->title_1 != null || $sig->title_1 != '')
							<td width="40%">{{$sig->designation_1}}: </td>
						@endif
						@if($sig->title_2 != null || $sig->title_2 != '')
							<td width="40%">{{$sig->designation_2}}: </td>
						@endif
						@if($sig->title_3 != null || $sig->title_3 != '')
							<td width="40%">{{$sig->designation_3}}: </td>
						@endif
					</tr>
				</table>
			</div>
		@endif

			{{-- <div>
				<br><br><br>
				Prepared by: {{auth()->user()->name}}
			</div> --}}

		{{-- </div> --}}

	</div>
</body>
</html>