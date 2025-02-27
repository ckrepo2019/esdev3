<!DOCTYPE html>
<html>
<head>
	<title>Prescription</title>
	<style type="text/css">
		body {
			font-family: Arial, sans-serif;
			margin: 0;
			padding: 0;
            width: 100%;
			height: 100%;
		}

		.doctor-info {
			clear: both;
			text-align: left;
			width: 50%;
			font-size: 20px;
			line-height: 0.1;
		}

		.doctor-name {
			font-size: 15px;
			color: rgba(0, 0, 0, 0.493);
			font-weight: bold;
			margin-b: 0;
		} */
		.degree,
		.reg-no,
		.phone,
		.email {
			font-size: 15px;
			line-height: 0.2;
		}

		.school-info {
			text-align: left;
			/* padding-left: 300px */
			width: 50%;
			font-size: 20px;
			line-height: 0.2;
		}

		

		.medication-info {
			clear: both;
			margin-top: 0px;
			font-size: 12px;
		}

		.date{
			clear: both;
			padding-top: 20px !important;
		}

		.patient-info {
			margin-top: 30px;
			font-size: 15px;
		}

		.patient-name,
		.date {
			width: 50%;
			font-size: 15px;
			line-height: 0.5;
		}


		.signature {
			margin-top: 50px;
			text-align: left;
		}

		.signature p {
			margin-left: 45px ;
		}
		.table-bordered td, .table-bordered th {
			border: 2px solid #3d3d3d;
		}

		.table {
			width: 100%;
			margin-bottom: 1rem;
			background-color: transparent;
			font-size:15px ;
		}


		.p-1{
			padding: .25rem !important;
			font-size:15px ;
		}

		table {
			border-collapse: collapse;
		}
			.table td, .table th {
			padding: .75rem;
			vertical-align: top;
		}
		.table td, .table th {
			padding: .75rem;
			vertical-align: top;
		}
		.image-container {
			/* display: flex;
			justify-content: center; */
			height: 20px;
			width: 20px;
			padding-left: 550px
						}

		#dosage{
			text-align: center;
		}

	</style>
</head>
<body>
		<div class="image-container">
			
			<img src="{{asset(DB::table('schoolinfo')->first()->picurl)}}"
            class="brand-image img-circle elevation-3"
            style="opacity: .8; max-width: 100px; max-height: 100px; border-radius: 90% ">
		</div>
		<p class="date">Date: {{date('d-M-Y, h:m A', strtotime($data[0]->createddatetime))}} </p>


		<div class="school-info">
			<p class="School-name">{{DB::table('schoolinfo')->first()->abbreviation}} Clinic</p>
			<p class="schoolid">School ID: {{DB::table('schoolinfo')->first()->schoolid}}</p>
		</div>


		<div class="doctor-info">
			<p class="doctor-name">{{$data[0]->doctorname}}</p>
            <p class="degree">{{$doctor->degree}} | {{$doctor->specialty}}</p>
			<p class="reg-no">Registration Number: {{$doctor->regnum}}</p>
			<p class="phone">Mobile Number: {{$doctor->phone}}</p>
			<p class="phone">Address: {{$doctor->address}}</p>
			<p class="email">Email: {{$doctor->email}}</p>
		</div>

		<hr>

		<div class="patient-info">
			@if($complaint->benefeciaryname =="" || $complaint->benefeciaryname ==NULL)
			<a class="patient-name">Patient Name:
                        {{$complaint->name_showlast}}<br/>  
                    </a>
			<a class="patient-complaints">Complaint:
                        {{$complaint->description}}  </a>
			@else
			<a class="patient-name">Patient Name:  
                        {{$complaint->benefeciaryname}} </a><br/>
			<a class="patient-complaints">Complaints: 
                        {{$complaint->description}} </a>
			@endif
		</div>
		<h4>R<h4>
		<div class="medication-info">
		<table width="100%"  class="table table-bordered mb-0">
			<tbody>
				<tr>
						<th  class="p-1 text-left">Medicine Name</th>
						<th class="p-1 text-left">Dosage</th>
						<th class="p-1 text-center">Duration</th>
				</tr>

						@foreach($data as $dat)

							<tr>

								<td class="p-1" width="8%" >
									{{$dat->medicinename}}
								</td>
								
								<td class="p-1" id="dosage" width="8%" >
									{{$dat->dosage}}
								</td>
								<td class="p-1" width="8%" >
									{{$dat->duration}} Days &nbsp; <br/> (Total:{{$dat->quantity}} Medicine)
								</td>
							</tr>
						@endforeach

				
				</tbody>
		</table>

		</div>
			<strong class="additional-instructions">Additional Advice Given:</strong>
			<p class="additional-instructions">*{{$data[0]->advice}}</p>
			<strong class="additional-instructions">Follow up: {{date('M d, Y', strtotime($data[0]->followup))}}</strong>
		<div class="signature">
			<img  src="{{asset('storage/' . $doctor->signature)}}" alt="Signature" style="max-width: 200px; max-height: 200px;"><br/>
			<p class= "sig1">Doctor's signature</p>
		</div>
	</div>
</body>