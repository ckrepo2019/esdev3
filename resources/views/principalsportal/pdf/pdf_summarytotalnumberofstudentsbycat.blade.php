
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
    <div style="width:100%; page-break-inside: avoid ">
        <table class="header">
            <tr>
                <td style="width: 30%;" rowspan="2"><img src="{{base_path()}}/public/{{$schoolinfo->picurl}}" alt="school" width="70px"></td>
                <td><strong>{{$schoolinfo->schoolname}}</strong> </td>
                <td style="text-align:right;"><strong>Total number of Students by Category</strong></strong><br><small>S.Y  <strong>{{$sy->sydesc}}</strong></small></td>
            </tr>
        </table>
        <br>
        @if($displaytype == 'all')
            <table style="width:100%; border: 1px solid; page-break-inside: avoid ">
                <thead style="text-align: center;">
                    <tr>
                        <th>Students</th>
                        {{-- <th>Grade Level</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @if(isset($regularstudents))
                        @if(count($regularstudents) > 0)
                            @foreach($regularstudents as $regularstudent)
                                <tr>
                                    <td>{{$regularstudent->firstname}} {{$regularstudent->middlename[0].'.'}} {{$regularstudent->lastname}} {{$regularstudent->suffix}}</td>
                                    {{-- <td style="text-align: center;">{{$regularstudent->levelname}}</td> --}}
                                </tr>
                                @php
                                    $totalnumberofstudents += 1;
                                @endphp
                            @endforeach
                        @endif
                    @endif
                    @if(isset($escstudents))
                        @if(count($escstudents) > 0)
                            @foreach($escstudents as $escstudent)
                                <tr>
                                    <td>{{$escstudent->firstname}} {{$escstudent->middlename[0].'.'}} {{$escstudent->lastname}} {{$escstudent->suffix}}</td>
                                    {{-- <td style="text-align: center;">{{$escstudent->levelname}}</td> --}}
                                </tr>
                                @php
                                    $totalnumberofstudents += 1;
                                @endphp
                            @endforeach
                        @endif
                    @endif
                    @if(isset($voucherstudents))
                        @if(count($voucherstudents) > 0)
                            @foreach($voucherstudents as $voucherstudent)
                                <tr>
                                    <td>{{$voucherstudent->firstname}} {{$voucherstudent->middlename[0].'.'}} {{$voucherstudent->lastname}} {{$voucherstudent->suffix}}</td>
                                    {{-- <td style="text-align: center;">{{$voucherstudent->levelname}}</td> --}}
                                </tr>
                                @php
                                    $totalnumberofstudents += 1;
                                @endphp
                            @endforeach
                        @endif
                    @endif
                </tbody>
            </table>
        @elseif(strtolower($displaytype) == 'grantee')
            @if(isset($regularstudents))
                @if(count($regularstudents) > 0)
                    <table style="width:100%; border: 1px solid; page-break-inside: avoid ">
                        <thead style="text-align: center;">
                            <tr>
                                <th colspan="2">REGULAR</th>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <th>Grade Level</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($regularstudents as $regularstudent)
                                <tr>
                                    <td>{{$regularstudent->firstname}} {{$regularstudent->middlename[0].'.'}} {{$regularstudent->lastname}} {{$regularstudent->suffix}}</td>
                                    <td style="text-align: center;">{{$regularstudent->levelname}}</td>
                                </tr>
                                @php
                                    $totalnumberofstudents += 1;
                                @endphp
                            @endforeach
                        </tbody>
                    </table>
                @endif
            @endif
            @if(isset($escstudents))
                @if(count($escstudents) > 0)
                    <table style="width:100%; border: 1px solid; page-break-inside: avoid ">
                        <thead style="text-align: center;">
                            <tr>
                                <th colspan="2">ESC GRANTEE</th>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <th>Grade Level</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($escstudents as $escstudent)
                                <tr>
                                    <td>{{$escstudent->firstname}} {{$escstudent->middlename[0].'.'}} {{$escstudent->lastname}} {{$escstudent->suffix}}</td>
                                    <td style="text-align: center;">{{$escstudent->levelname}}</td>
                                </tr>
                                @php
                                    $totalnumberofstudents += 1;
                                @endphp
                            @endforeach
                        </tbody>
                    </table>
                @endif
            @endif
            @if(isset($voucherstudents))
                @if(count($voucherstudents) > 0)
                    <table style="width:100%; border: 1px solid; page-break-inside: avoid ">
                        <thead style="text-align: center;">
                            <tr>
                                <th colspan="2">VOUCHER</th>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <th>Grade Level</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($voucherstudents as $voucherstudent)
                                <tr>
                                    <td>{{$voucherstudent->firstname}} {{$voucherstudent->middlename[0].'.'}} {{$voucherstudent->lastname}} {{$voucherstudent->suffix}}</td>
                                    <td style="text-align: center;">{{$voucherstudent->levelname}}</td>
                                </tr>
                                @php
                                    $totalnumberofstudents += 1;
                                @endphp
                            @endforeach
                        </tbody>
                    </table>
                @endif
            @endif
        @elseif(strtolower($displaytype) == 'gradelevel')
            @foreach($gradelevels as $gradelevel)
                @php
                    $gradelevelstudents = 0;   
                @endphp
                <table style="width:100%; border: 1px solid; page-break-inside: avoid ">
                    <thead style="text-align: center;">
                        <tr>
                            <th>{{$gradelevel->levelname}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($enrolledstuds as $enrolledstud)
                            @if($enrolledstud->levelid == $gradelevel->id)
                                @php
                                    $totalnumberofstudents += 1;
                                    $gradelevelstudents += 1;
                                @endphp
                                <tr>
                                    <td>{{$gradelevelstudents}} {{$enrolledstud->firstname}} {{$enrolledstud->middlename[0].'.'}} {{$enrolledstud->lastname}} {{$enrolledstud->suffix}}</td>
                                    {{-- <td style="text-align: center;">{{$enrolledstud->levelname}}</td> --}}
                                </tr>
                            @endif
                        @endforeach
                        @foreach($shenrolledstuds as $shenrolledstud)
                            @if($shenrolledstud->levelid == $gradelevel->id)
                                @php
                                    $totalnumberofstudents += 1;
                                    $gradelevelstudents += 1;
                                @endphp
                                <tr>
                                    <td>{{$gradelevelstudents}} {{$shenrolledstud->firstname}} {{$shenrolledstud->middlename[0].'.'}} {{$shenrolledstud->lastname}} {{$shenrolledstud->suffix}}</td>
                                    {{-- <td style="text-align: center;">{{$enrolledstud->levelname}}</td> --}}
                                </tr>
                            @endif
                        @endforeach
                        @if($gradelevelstudents == 0)
                            <tr>
                                <td>NO STUDENTS ENROLLED</td>
                                {{-- <td style="text-align: center;">{{$enrolledstud->levelname}}</td> --}}
                            </tr>
                        @endif
                    </tbody>
                </table>
                <br/>
            @endforeach
        @elseif(strtolower($displaytype) == 'gender')
            @php
                $malestudents = 0;
            @endphp
            <table style="width:100%; border: 1px solid; page-break-inside: avoid ">
                <thead style="text-align: center;">
                    <tr>
                        <th colspan="2">MALE</th>
                    </tr>
                    <tr>
                        <th>Students</th>
                        <th>Grade Level</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($enrolledstuds as $enrolledstud)
                        @if(strtolower($enrolledstud->gender) == "male")
                            @php
                                $totalnumberofstudents += 1;
                                $malestudents += 1;
                            @endphp
                            <tr>
                                <td>{{$malestudents}} {{$enrolledstud->firstname}} {{$enrolledstud->middlename[0].'.'}} {{$enrolledstud->lastname}} {{$enrolledstud->suffix}}</td>
                                <td style="text-align: center;">{{$enrolledstud->levelname}}</td>
                            </tr>
                        @endif
                    @endforeach
                    @foreach($shenrolledstuds as $shenrolledstud)
                        @if(strtolower($shenrolledstud->gender) == "male")
                            @php
                                $totalnumberofstudents += 1;
                                $malestudents += 1;
                            @endphp
                            <tr>
                                <td>{{$malestudents}} {{$shenrolledstud->firstname}} {{$shenrolledstud->middlename[0].'.'}} {{$shenrolledstud->lastname}} {{$shenrolledstud->suffix}}</td>
                                <td style="text-align: center;">{{$enrolledstud->levelname}}</td>
                            </tr>
                        @endif
                    @endforeach
                    @if($malestudents == 0)
                        <tr>
                            <td colspan="2">NO MALE STUDENTS ENROLLED</td>
                            {{-- <td style="text-align: center;">{{$enrolledstud->levelname}}</td> --}}
                        </tr>
                    @endif
                </tbody>
            </table>
            <br/>
            @php
                $femalestudents = 0;
            @endphp
            <table style="width:100%; border: 1px solid; page-break-inside: avoid ">
                <thead style="text-align: center;">
                    <tr>
                        <th colspan="2">FEMALE</th>
                    </tr>
                    <tr>
                        <th>Students</th>
                        <th>Grade Level</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($enrolledstuds as $enrolledstud)
                        @if(strtolower($enrolledstud->gender) == "female")
                            @php
                                $totalnumberofstudents += 1;
                                $femalestudents += 1;
                            @endphp
                            <tr>
                                <td>{{$femalestudents}} {{$enrolledstud->firstname}} {{$enrolledstud->middlename[0].'.'}} {{$enrolledstud->lastname}} {{$enrolledstud->suffix}}</td>
                                <td style="text-align: center;">{{$enrolledstud->levelname}}</td>
                            </tr>
                        @endif
                    @endforeach
                    @foreach($shenrolledstuds as $shenrolledstud)
                        @if(strtolower($shenrolledstud->gender) == "female")
                            @php
                                $totalnumberofstudents += 1;
                                $femalestudents += 1;
                            @endphp
                            <tr>
                                <td>{{$femalestudents}} {{$shenrolledstud->firstname}} {{$shenrolledstud->middlename[0].'.'}} {{$shenrolledstud->lastname}} {{$shenrolledstud->suffix}}</td>
                                <td style="text-align: center;">{{$enrolledstud->levelname}}</td>
                            </tr>
                        @endif
                    @endforeach
                    @if($femalestudents == 0)
                        <tr>
                            <td colspan="2">NO FEMALE STUDENTS ENROLLED</td>
                            {{-- <td style="text-align: center;">{{$enrolledstud->levelname}}</td> --}}
                        </tr>
                    @endif
                </tbody>
            </table>
        @endif
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
    </div>