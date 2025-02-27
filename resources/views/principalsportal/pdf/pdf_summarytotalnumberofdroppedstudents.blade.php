
<style>
    * {
        font-family: Arial, Helvetica, sans-serif;
       }
        .header, .header td{
            width: 100%;
            table-layout: fixed;
            border: hidden !important;
            /* border: 1px solid black; */
        }
        tr{
            padding: 0px;
            font-size: 12px;
            
        }
        td{
            padding: 2px;
            border: 1px solid black !important;
        }
        table{
            border-collapse: collapse
        }
        header {
            position: fixed;
            top: -60px;
            left: 0px;
            right: 0px;
            height: 50px;
    
            /** Extra personal styles **/
            background-color: #03a9f4;
            color: white;
            text-align: center;
            line-height: 35px;
        }
    
        footer {
            border-top: 2px solid #ddd;
            position: fixed; 
            bottom: -60px; 
            left: 0px; 
            right: 0px;
            height: 100px; 
            font-size: 11px !important;
            /** Extra personal styles **/
            /* background-color: #03a9f4; */
            color: black;
            /* text-align: center; */
            line-height: 20px;
        }
    </style>
    @php
        $totalnumberofstudents = 0;
    @endphp
    <table class="header">
        <tr>
            <td style="width: 30%;" rowspan="2"><img src="{{base_path()}}/public/{{$schoolinfo->picurl}}" alt="school" width="70px"></td>
            <td><strong>{{$schoolinfo->schoolname}}</strong> </td>
            <td style="text-align:right;"><strong>Dropped Students</strong></strong><br><small>S.Y  <strong>{{$sy->sydesc}}</strong></small></td>
        </tr>
    </table>
    <br>
    <table style="width:100%; border: 1px solid; page-break-inside: avoid ">
        <thead style="text-align: center;">
            <tr>
                <th>Students</th>
                <th>Grade Level</th>
            </tr>
        </thead>
        <tbody>
            @foreach($droppedstudents as $droppedstudent)
                {{-- @if(strtolower($enrolledstud->gender) == "female") --}}
                    @php
                        $totalnumberofstudents += 1;
                    @endphp
                    <tr>
                        <td>{{$totalnumberofstudents}} {{$droppedstudent->firstname}} {{$droppedstudent->middlename[0].'.'}} {{$droppedstudent->lastname}} {{$droppedstudent->suffix}}</td>
                        <td style="text-align: center;">{{$droppedstudent->levelname}}</td>
                    </tr>
                {{-- @endif --}}
            @endforeach
        </tbody>
    </table>
    <footer>
        <table style="width: 50%; float: left;">
            <tr style="border: none !important;">
                <td style="border: none !important; width: 20px;">PREPARED BY :</td>
                <td style="border: none !important; width: 50px;border-bottom: 1px solid black;"><center>{{$preparedby->firstname}} {{$preparedby->middlename[0].'.'}} {{$preparedby->lastname}} {{$preparedby->suffix}}</center></td>
            </tr>
            <tr style="border: none !important;">
                <td style="border: none !important;"></td>
                <td style="border: none !important;"><center>HR</center></td>
            </tr>
            <tr style="border: none !important;">
                <td style="border: none !important;">DATE & TIME :</td>
                <td style="border: none !important;"><center> {{$dateprepared}}</center></td>
            </tr>
        </table>
        <table style="width: 30%; float: right;">
            <tr style="border: none !important;">
                <td style="border: none !important;">TOTAL NO. OF STUDENTS :</td>
                <td style="border: none !important;"><center> <strong>{{$totalnumberofstudents}}</strong> </center></td>
            </tr>
        </table>
    </footer>