
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
        @page { size: 13in 8.5in; margin: .15in .1in;}
    
</style>
{{-- <div>
	<div class="row watermark">
		<div class="col-md-12">
			<img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="300px">
		</div>
	</div>
</div> --}}
<table width="100%" class="table table-sm mb-0" style="table-layout: fixed">
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
            <div style="font-size: 13px!important; color: red"><b>INACTIVE EMPLOYEES</b></div>
        </td>
    </tr>

</table>
<table style="width: 100%; font-size: 10px; table-layout: fixed; padding-top: 20px;" border="1">
    <thead>
        <tr>
            <th style="width: 1%;">#</th>
            <th style="width: 5%;">ID</th>
            <th style="width: 14%;">Name</th>
            <th style="width: 3%;">Gender</th>
            <th style="width: 5%;">Birthdate</th>
            <th style="width: 12%;">Address</th>
            <th style="width: 5%;">Contact No.</th>
            <th style="width: 11%;">Email Address</th>
            <th style="width: 5%;">SSS</th>
            <th style="width: 5%;">Philhealth</th>
            <th style="width: 5%;">Pag-ibig</th>
            <th style="width: 5%;">TIN</th>
            <th style="width: 5%;">Nationality</th>
            <th style="width: 5%;">Religion</th>
            <th style="width: 5%;">Marital status</th>
            <th style="width: 6%;">Employment of spouse</th>
            <th style="width: 3%;">No. of children</th>
        </tr>
    </thead>
    @foreach($employees as $key=>$employee)
        <tr>
            <td style="text-align: center;">{{$key+1}}</td>
            <td style="text-align: center;">{{$employee->tid}}</td>
            <td style="text-transform: uppercase;">&nbsp;{{$employee->lastname}}, {{$employee->firstname}} @if($employee->middlename != null){{$employee->middlename}}@endif {{$employee->suffix}}</td>
            <td style="text-align: center;">
                @if(strtoupper($employee->gender) == 'MALE')
                    M
                @elseif(strtoupper($employee->gender) == 'FEMALE')
                    F
                @else
                    {{ strtoupper($employee->gender) }}
                @endif
            </td>
            <td style="text-align: center;">@if($employee->dob != null){{date('M d, Y', strtotime($employee->dob))}}@endif</td>
            <td>{{$employee->address}}</td>
            <td style="text-align: center;">{{$employee->contactnum}}</td>
            <td style="text-align: center;">{{$employee->email}}</td>
            <td style="text-align: center;">{{$employee->sssid}}</td>
            <td style="text-align: center;">{{$employee->philhealtid}}</td>
            <td style="text-align: center;">{{$employee->pagibigid}}</td>
            <td style="text-align: center;">{{$employee->tinid}}</td>
            <td style="text-align: center;">{{$employee->nationality}}</td>
            <td style="text-align: center;">{{$employee->religion}}</td>
            <td style="text-align: center;">{{$employee->civilstatus}}</td>
            <td>{{$employee->spouseemployment}}</td>
            <td style="text-align: center;">{{$employee->numberofchildren}}</td>
        </tr>
    @endforeach
</table>