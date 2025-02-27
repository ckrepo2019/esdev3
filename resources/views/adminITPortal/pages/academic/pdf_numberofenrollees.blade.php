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


    @endphp
    <body>
        <table style="width: 100%:">
            <tr>
                <td></td>
                <td style="width: 60%; text-align: center; font-weight: bold;">
                    {{$schoolinfo->schoolname}}
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td style="width: 60%; text-align: center;">
                    {{$schoolinfo->address}}
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td>&nbsp;</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td style="width: 60%; text-align: center; font-weight: bold;">
                    Number of Students
                </td>
                <td style="text-align: right;">{{date('D, F d, Y')}}</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="text-align: right;">Admin Portal</td>
            </tr>
        </table>
        {{-- <table style="width: 30%; table-layout: fixed;">
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
        <br/> --}}
        <table style="width: 100%;" border="1">
            <thead>
                <tr>
                    <th>Grade Level Name</th>
                    <th>Male</th>
                    <th>Female</th>
                    <th>Total</th>
                </tr>
            </thead>
            @foreach($gradelevels as $gradelevel)
                <tr>
                    <td style="text-align: center;">{{$gradelevel->levelname}}</td>
                    <th>{{$gradelevel->male}}</th>
                    <th>{{$gradelevel->female}}</th>
                    <th>{{$gradelevel->total}}</th>
                </tr>
            @endforeach
            <tr>
                <th>TOTAL</th>
                <th>{{collect($gradelevels)->sum('male')}}</th>
                <th>{{collect($gradelevels)->sum('female')}}</th>
                <th>{{collect($gradelevels)->sum('total')}}</th>
            </tr>
        </table>   
    </body>
</html>