<html>
    <header>
        <style>
            * { font-family: Arial, Helvetica, sans-serif; }
            table{
                font-size: 12px;
                border-collapse: collapse;
            }
        </style>
    </header>
    @php
        $schoolinfo = Db::table('schoolinfo')
            ->select(
                'schoolinfo.schoolid',
                'schoolinfo.schoolname',
                'schoolinfo.authorized',
                'refcitymun.citymunDesc as city',
                'schoolinfo.district',
                'schoolinfo.address',
                'schoolinfo.picurl',
                'refregion.regDesc as region'
            )
            ->leftJoin('refregion','schoolinfo.region','=','refregion.regCode')
            ->leftJoin('refcitymun','schoolinfo.division','=','refcitymun.citymunCode')
            ->first();

        $studentstatus = DB::table('studentstatus')
            ->whereIn('id', $statusids)
            ->get();

        if(count($studentstatus) == 1)
        {
            $admstatus = $studentstatus[0]->description;
        }else{
            $admstatus = implode(", ", collect($studentstatus)->pluck('description')->toArray());
        }

    @endphp
    <body>
        <table style="width: 100%; table-layout: fixed;">
            <tr>
                <td rowspan="4" style="text-align: left; vertical-align: top !important;">
                    <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="70px">
                </td>
                <td style="width: 60%; text-align: center; font-weight: bold;">
                    {{$schoolinfo->schoolname}}
                </td>
                <td></td>
            </tr>
            <tr>
                <td style="width: 60%; text-align: center;">
                    {{$schoolinfo->address}}
                </td>
                <td></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td></td>
            </tr>
            <tr>
                <td style="width: 60%; text-align: center; font-weight: bold;">
                    Number of Students
                </td>
                <td style="text-align: right;">{{date('D, F d, Y')}}</td>
            </tr>
        </table>
        <br/>
        <table style="width: 30%; table-layout: fixed;">
            <tr>
                <td>School Year</td>
                <td>: {{$syinfo->sydesc}}</td>
            </tr>
            <tr>
                <td>Semester</td>
                <td>: {{$semesterinfo->semester}}</td>
            </tr>
            @if($gender  == 'female' || $gender == 'male')
                <tr>
                    <td>Gender</td>
                    <td>: {{strtoupper($gender)}}</td>
                </tr>
            @endif
        </table>
        <table style="width: 100%; table-layout: fixed;">
            <tr>
                <td style="width: 15%;">ADM Status</td>
                <td>: {{$admstatus}}</td>
            </tr>
            <tr>
                <td>Student Type</td>
                <td>: {{strtoupper($studtype)}}</td>
            </tr>
        </table>
        <br/>
        <table style="width: 100%;" border="1">
            @foreach($gradelevels as $gradelevel)
                <tr>
                    <td style="text-align: center;">{{$gradelevel->levelinfo->levelname}}</td>
                    <th>{{$gradelevel->studcount}}</th>
                </tr>
            @endforeach
            <tr>
                <th>TOTAL</th>
                <th>{{collect($gradelevels)->sum('studcount')}}</th>
            </tr>
        </table>   
    </body>
</html>