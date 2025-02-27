<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescription</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header img {
            max-width: 80px;
            max-height: 80px;
            border-radius: 50%;
        }

        .school-info, .doctor-info {
            width: 45%;
        }

        .doctor-info {
            text-align: left;
            font-size: 14px;
        }
        .school-info {
            text-align: right;
		margin-left: auto;
        }

        .doctor-name {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .doctor-info p {
            margin: 2px 0;
        }

        .patient-info {
            margin-top: 30px;
            font-size: 16px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }

        .patient-info p {
            margin: 5px 0;
        }

        h4 {
            margin-top: 30px;
            text-align: center;
            font-size: 22px;
            font-weight: bold;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 14px;
        }

        .table th, .table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        .table th {
            background-color: #f0f0f0;
        }

        .table td {
            text-align: center;
        }

        .additional-instructions {
            margin-top: 20px;
            font-size: 14px;
        }

        .signature {
            margin-top: 30px;
            text-align: right;
        }

        .signature img {
            max-width: 150px;
            max-height: 150px;
        }
      
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
		<div class="doctor-info">
			<p class="doctor-name">{{$data[0]->doctorname}}</p>
			<p>{{$doctor->degree}} | {{$doctor->specialty}}</p>
			<p>Reg. No: {{$doctor->regnum}}</p>
			<p>Phone: {{$doctor->phone}}</p>
			<p>Address: {{$doctor->address}}</p>
			<p>Email: {{$doctor->email}}</p>
		</div>
		<div class="school-info">
			<img src="{{asset(DB::table('schoolinfo')->first()->picurl)}}" alt="School Logo">
			<p><strong>{{DB::table('schoolinfo')->first()->abbreviation}} Clinic</strong></p>
			<p>School ID: {{DB::table('schoolinfo')->first()->schoolid}}</p>
		</div>
        </div>

        <div class="patient-info">
            <p><strong>Patient Name:</strong> 
                {{ $complaint->benefeciaryname ? $complaint->benefeciaryname : $details->name_showlast ?? 'Not Specified' }}
            </p>
            <p><strong>Complaint:</strong> {{$complaint->description}}</p>
            <p><strong>Date:</strong> {{date('d-M-Y, h:m A', strtotime($data[0]->createddatetime))}}</p>
        </div>

        <h4>Prescription</h4>

        <div class="medication-info">
            <table class="table">
                <thead>
                    <tr>
                        <th>Medicine Name</th>
                        <th>Dosage</th>
                        <th>Duration</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $dat)
                    <tr>
                        <td>{{$dat->medicinename}}</td>
                        <td>{{$dat->dosage}}</td>
                        <td>{{$dat->duration}} Days (Total: {{$dat->quantity}})</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <p class="additional-instructions"><strong>Additional Advice:</strong> {{$data[0]->advice}}</p>
        <p class="additional-instructions"><strong>Follow-up:</strong> {{date('M d, Y', strtotime($data[0]->followup))}}</p>

        <div class="signature">
            @if (Storage::exists($doctor->signature))
                <img src="{{asset('storage/' . $doctor->signature)}}" alt="Doctor's Signature">
            @endif
            <p>Doctor's Signature</p>
        </div>
    </div>
</body>
</html>
