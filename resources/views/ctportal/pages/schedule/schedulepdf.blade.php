@extends('ctportal.layouts.pdf')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">


    <style>
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-top: -9px;
        }

        .font-20 {
            font-size: 20px !
        }
    </style>
@endsection



@section('content')

    @php
        $schoollogo = DB::table('schoolinfo')->first();
        $teacher = DB::table('teacher')->where('userid', auth()->user()->id)->first();
    @endphp
    <div class="p-5">
        <table width="100%">
            <tr>
                <td width="30%" class="text-right">
                    <img src="{{asset(DB::table('schoolinfo')->first()->picurl)}}" alt="school" width="70px">
                </td>
                <td width="40%" class="text-center">
                    <div>{{$schoollogo->schoolname}}</div>
                    <div>{{$schoollogo->address}}</div>
                </td>
                <td width="30%" class="text-right">
                  
                </td>
            </tr>
        </table>
        <div class="text-center mt-4">Teaching Loads</div>
        <div class="text-center">SCHOOL YEAR {{$school_year->sydesc}}</div>
        <div class="text-center">{{$semester->semester}}</div>
        <div class="mt-3">College Instructor: {{$teacher->firstname}} {{$teacher->lastname}}</div>
        <table width="100%" class= "table table-sm table-bordered table-striped">
            <tr>
                <td colspan="5">Monday</td>
            </tr>
            <tr>
                <td width="10%">Level</td>
                <td width="20%">Section</td>
                <td width="35%">Subject</td>
                <td width="20%">Time</td>
                <td width="15%">Room</td>
            </tr>
            @foreach($schedule as $item)
                @if($item->description == 'Monday')
                    <tr>
                        <td>{{$item->yearDesc}}</td>
                        <td>{{$item->sectionDesc}}</td>
                        <td>{{$item->subjDesc}}</td>
                        <td>{{$item->schedotherclass}} {{$item->schedtime}}</td>
                        <td>{{$item->roomname}}</td>
                    </tr>
                @endif
            @endforeach
        </table>
        <table width="100%" class= "table table-sm table-bordered table-striped mt-2">
            <tr>
                <td colspan="5">Tuesday</td>
            </tr>
            <tr>
                <td width="10%">Level</td>
                <td width="20%">Section</td>
                <td width="35%">Subject</td>
                <td width="20%">Time</td>
                <td width="15%">Room</td>
            </tr>
            @foreach($schedule as $item)
                @if($item->description == 'Tuesday')
                    <tr>
                        <td>{{$item->yearDesc}}</td>
                        <td>{{$item->sectionDesc}}</td>
                        <td>{{$item->subjDesc}}</td>
                        <td>{{$item->schedotherclass}} {{$item->schedtime}}</td>
                        <td>{{$item->roomname}}</td>
                    </tr>
                @endif
            @endforeach
        </table>
        <table width="100%" class= "table table-sm table-bordered table-striped mt-2">
            <tr>
                <td colspan="5">Wednesday</td>
            </tr>
            <tr>
                <td width="10%">Level</td>
                <td width="20%">Section</td>
                <td width="35%">Subject</td>
                <td width="20%">Time</td>
                <td width="15%">Room</td>
            </tr>
            @foreach($schedule as $item)
                @if($item->description == 'Wednesday')
                    <tr>
                        <td>{{$item->yearDesc}}</td>
                        <td>{{$item->sectionDesc}}</td>
                        <td>{{$item->subjDesc}}</td>
                        <td>{{$item->schedotherclass}} {{$item->schedtime}}</td>
                        <td>{{$item->roomname}}</td>
                    </tr>
                @endif
            @endforeach
        </table>
        <table width="100%" class= "table table-sm table-bordered table-striped mt-2">
            <tr>
                <td colspan="5">Thursday</td>
            </tr>
            <tr>
                <td width="10%">Level</td>
                <td width="20%">Section</td>
                <td width="35%">Subject</td>
                <td width="20%">Time</td>
                <td width="15%">Room</td>
            </tr>
            @foreach($schedule as $item)
                @if($item->description == 'Thursday')
                    <tr>
                        <td>{{$item->yearDesc}}</td>
                        <td>{{$item->sectionDesc}}</td>
                        <td>{{$item->subjDesc}}</td>
                        <td>{{$item->schedotherclass}} {{$item->schedtime}}</td>
                        <td>{{$item->roomname}}</td>
                    </tr>
                @endif
            @endforeach
        </table>
        <table width="100%" class= "table table-sm table-bordered table-striped mt-2">
            <tr>
                <td colspan="5">Friday</td>
            </tr>
            <tr>
                <td width="10%">Level</td>
                <td width="20%">Section</td>
                <td width="35%">Subject</td>
                <td width="20%">Time</td>
                <td width="15%">Room</td>
            </tr>
            @foreach($schedule as $item)
                @if($item->description == 'Friday')
                    <tr>
                        <td>{{$item->yearDesc}}</td>
                        <td>{{$item->sectionDesc}}</td>
                        <td>{{$item->subjDesc}}</td>
                        <td>{{$item->schedotherclass}} {{$item->schedtime}}</td>
                        <td>{{$item->roomname}}</td>
                    </tr>
                @endif
            @endforeach
        </table>
        <table width="100%" class= "table table-sm table-bordered table-striped mt-2">
            <tr>
                <td colspan="5">Saturday</td>
            </tr>
            <tr>
                <td width="10%">Level</td>
                <td width="20%">Section</td>
                <td width="35%">Subject</td>
                <td width="20%">Time</td>
                <td width="15%">Room</td>
            </tr>
            @foreach($schedule as $item)
                @if($item->description == 'Saturday')
                    <tr>
                        <td>{{$item->yearDesc}}</td>
                        <td>{{$item->sectionDesc}}</td>
                        <td>{{$item->subjDesc}}</td>
                        <td>{{$item->schedotherclass}} {{$item->schedtime}}</td>
                        <td>{{$item->roomname}}</td>
                    </tr>
                @endif
            @endforeach
        </table>

        @if(count($schedule->where('description', null)) > 0)
            <table width="100%" class= "table table-sm table-bordered table-striped mt-2">
                <tr>
                    <td colspan="5">No Assigned Day</td>
                </tr>
                <tr>
                    <td width="10%">Level</td>
                    <td width="20%">Section</td>
                    <td width="35%">Subject</td>
                    <td width="20%">Time</td>
                    <td width="15%">Room</td>
                </tr>
                @foreach($schedule as $item)
                    @if($item->description == null)
                        <tr>
                            <td>{{$item->yearDesc}}</td>
                            <td>{{$item->sectionDesc}}</td>
                            <td>{{$item->subjDesc}}</td>
                            <td>{{$item->schedotherclass}} {{$item->schedtime}}</td>
                            <td>{{$item->roomname}}</td>
                        </tr>
                    @endif
                @endforeach
            </table>
        @endif
    </div>
    


@endsection

@section('footerscript')

@endsection
