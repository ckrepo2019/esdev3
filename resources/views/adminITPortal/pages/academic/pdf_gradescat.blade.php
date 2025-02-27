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

        $description = '';
        if($category == 'A')
        {
            $description = 'ADVANCED (90% and above)';
        }
        if($category == 'P')
        {
            $description = 'PROFICIENT (85% -89%)';
        }
        if($category == 'AP')
        {
            $description = 'APPROACHING PROFICIENCY (80%-84%)';
        }
        if($category == 'D')
        {
            $description = 'DEVELOPING (75%-79%)';
        }

        if($category == 'B')
        {
            $description = 'BEGINNING (74% and below)';
        }
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
                    {{$levelname}}
                </td>
                <td style="text-align: right;">{{date('D, F d, Y')}}</td>
            </tr>
            <tr>
                <td></td>
                <th>{{$description}}</th>
                <td style="text-align: right;">Admin Portal</td>
            </tr>
        </table>
        
        <table style="width: 100%; margin-top: 10px;" border="1">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Student Name</th>
                    <th>Gender</th>
                    <th>General Average</th>
                    <th>Section</th>
                </tr>
            </thead>
            @foreach($students as $key=>$student)
                <tr>
                    <td style="text-align: center;">{{$key+1}}</td>
                    <td>&nbsp;{{$student->lastname}}, {{$student->firstname}}</td>
                    <td style="text-align: center;">{{strtoupper($student->gender)}}</td>
                    <th>{{$student->generalaverage}}</th>
                    <th>{{$student->sectionname}}</th>
                </tr>
            @endforeach
            {{-- <tr>
                <th>TOTAL</th>
                <th>{{collect($gradelevels)->sum('male')}}</th>
                <th>{{collect($gradelevels)->sum('female')}}</th>
                <th>{{collect($gradelevels)->sum('total')}}</th>
            </tr> --}}
        </table>   
    </body>
</html>