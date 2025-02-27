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

        if($levelid==null)
        {
            $levelinfo = (object)array(
                'levelname' => null
            );
        }else{
            $levelinfo = DB::table('gradelevel')
                ->where('id', $levelid)->first();
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
                    {{$levelinfo->levelname}}
                </td>
                <td></td>
            </tr>
        </table>
        <br/>
        
        @foreach($students as $student)
            <table style="width: 100%;" border="1">
                <thead>
                    <tr>
                        <td colspan="{{count($student->requirements)+1}}" style="vertical-align: top;" >
                                <span style="font-size: 13px; font-weight: bold;">{{ucwords(strtolower($student->lastname.', '.$student->firstname.' '.$student->middlename.'. '.$student->suffix))}}</span>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <small >LRN : {{$student->lrn}} &nbsp;&nbsp;SID : {{$student->sid}}</small>
                        </td>                       
                    </tr>
                </thead>
                <tr>
                    @if(count($student->requirements)>0)
                        @foreach($student->requirements as  $req)
                            <td style="text-align: center; vertical-align: top;">
                                <div style="width: 100%;">
                                    <input type="checkbox" @if($req->status == 1) checked @endif style="margin-top: 5px;"/>
                                    <label >{{$req->description}}</label>
                                </div>
                            </td>
                        @endforeach
                        <td style="text-align: center; width: 10%;">
                            @if(collect($student->requirements)->where('status','1')->count() == count($student->requirements))
                                <strong>Completed</strong>
                            @else   
                                Incomplete
                            @endif
                        </td>
                    @endif
                </tr>
            </table>
            <br/>
        @endforeach
        {{-- <table style="width: 100%;" border="1">
            <thead>
                <tr>
                    <th style="width: 30%;" rowspan="2">Students</th>
                    <th colspan="{{count($requirementslist)}}">Requirements</th>
                    <th rowspan="2">
                    </th>
                </tr>
                <tr>
                    @if(count($requirementslist)>0)
                        @foreach($requirementslist as $requirement)
                            <th>{{ucwords(strtolower($requirement->description))}}</th>
                        @endforeach
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                    <tr>
                        <td style="padding-left: 2px;">{{ucwords(strtolower($student->lastname.', '.$student->firstname.' '.$student->middlename[0].'. '.$student->suffix))}}
                            <br/>
                            <small>LRN : {{$student->lrn}} &nbsp;&nbsp;SID : {{$student->sid}}</small>
                        </td>
                        @if(count($student->requirements)>0)
                            @foreach($student->requirements as  $req)
                                <td style="text-align: center; vertical-align: middle; padding-top: 2px;">
                                    <input type="checkbox" @if($req->status == 1) checked @endif style="margin-left: 40%;"/>
                                </td>
                            @endforeach
                        @endif
                        <td style="text-align: center;">
                            @if(collect($student->requirements)->where('status','1')->count() == count($student->requirements))
                                <strong>Completed</strong>
                            @else   
                                Incomplete
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table> --}}
    </body>
</html>