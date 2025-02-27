<!DOCTYPE html>
<html>
<head>
    <title>Email Title</title>
    <style>
        body {
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }
        .email-container {
            text-align: left;
            max-width: 600px;
            background-color: #fff;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            margin-bottom: 20px;
        }
        .email-header h1 {
            font-size: 25px;
            margin: 0;
        }
        .email-body {
            margin-bottom: 20px;
        }
        .email-body p {
            line-height: 1.5;
            font-size: 15px;
        }
    </style>
</head>
<body>
    <div class="row">
        <div class="col-md-12">
            <div class="email-container">
                <div class="email-header">
                    <h1>Subjects: {{$details['subject']}}</h1>
                </div>
                <div class="email-body">
                    <p>{{$details['content']}}</p>
                </div>
                <div class="actions">
                    <h3>Needs Immediate Attention</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h3>Thank you</h3>
            <h3>Sincerely!</h3>
            <h3 class="mt-5">HR DEPARTMENT <br> LDCU <br> 0913456788989/ +088 004 4858</h3>
        </div>
    </div>
</body>
</html>
