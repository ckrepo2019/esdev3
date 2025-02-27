@extends('teacher.layouts.app')

@section('headerjavascript')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('content')

<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
  
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <nav class="" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active"><a href="/forms/index/form9">School Form 9</a></li>
                        <li class="breadcrumb-item active">{{$student->student}} </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="main-card mb-3 card ">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <a class="btn btn-primary btn-sm text-white" target="_blank" href="/prinsf9print/{{$studentid}}?syid={{$syid}}style="font-size:.8rem"><i class="fas fa-file-pdf"></i> SF9</a>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <h5>REPORT ON ATTENDANCE</h5>
                        </div>
                        @php
                            $width = count($attendance_setup) != 0? 75 / count($attendance_setup) : 0;
                        @endphp
                        <div class="col-md-12 mt-2">
                            <table class="table table-bordered table-sm" width="100%">
                                <tr class=" ">
                                    <th width="15%"></th>
                                    @foreach ($attendance_setup as $item)
                                        <th class="text-center align-middle" width="{{$width}}%">{{$item->days != 0 ? \Carbon\Carbon::create(null, $item->month)->isoFormat('MMM') : ''}}</th>
                                    @endforeach
                                    <th class="text-center text-center" width="10%">Total</th>
                                </tr>
                                <tr class="table-bordered">
                                    <td >No. of School Days</td>
                                    @foreach ($attendance_setup as $item)
                                        <td class="text-center align-middle">{{$item->days != 0 ? $item->days : '' }}</td>
                                    @endforeach
                                    <th class="text-center align-middle">{{collect($attendance_setup)->sum('days')}}</td>
                                </tr>
                                <tr class="table-bordered">
                                    <td>No. of Days Present</td>
                                    @foreach ($attendance_setup as $item)
                                        <td class="text-center align-middle">{{$item->days != 0 ? $item->present : ''}}</td>
                                    @endforeach
                                    <th class="text-center align-middle" >{{collect($attendance_setup)->where('days','!=',0)->sum('present')}}</th>
                                </tr>
                                <tr class="table-bordered">
                                    <td>No. of Day Absent</td>
                                    @foreach ($attendance_setup as $item)
                                        <td class="text-center align-middle" >{{$item->days != 0 ? $item->absent : ''}}</td>
                                    @endforeach
                                    <th class="text-center align-middle" >{{collect($attendance_setup)->sum('absent')}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</h5>
                        </div>
                        <div class="col-md-12 mt-2">
                            @if($student->acadprogid != 5)
                                <table class="table table-sm table-bordered grades" width="100%">
                                    <tr>
                                        <td width="50%" rowspan="2"  class="align-middle text-center"><b>SUBJECTS</b></td>
                                        <td width="30%" colspan="4"  class="text-center align-middle"><b>PERIODIC RATINGS</b></td>
                                        <td width="10%" rowspan="2"  class="text-center align-middle"><b>FINAL RATING</b></td>
                                        <td width="10%" rowspan="2"  class="text-center align-middle"><b>ACTION TAKEN</b></span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center align-middle"><b>1</b></td>
                                        <td class="text-center align-middle"><b>2</b></td>
                                        <td class="text-center align-middle"><b>3</b></td>
                                        <td class="text-center align-middle"><b>4</b></td>
                                    </tr>
                                    @foreach ($grades as $item)
                                        <tr>
                                            <td style="padding-left:{{$item->subjCom != null ? '2rem':'.25rem'}}" >{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                            <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                            <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                            <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                            <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                            <td class="text-center align-middle">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                                            <td class="text-center align-middle">{{$item->actiontaken != null ? $item->actiontaken:''}}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
										<td class="text-right" colspan="5">GENERAL AVERAGE</td>
										<td class="text-center ">{{collect($finalgrade)->first()->finalrating}}</td>
										<td class="text-center">{{collect($finalgrade)->first()->actiontaken}}</td>
									</tr>
                                </table>
                            @else
                                @for ($x=1; $x <= 2; $x++)
                                    <table class="table table-sm table-bordered grades" width="100%">
                                        <tr>
                                            <td colspan="5"  class="align-middle text-center"><b>{{$x == 1 ? '1ST SEMESTER' : '2ND SEMESTER'}}</b></td>
                                        </tr>
                                        <tr>
                                            <td width="60%" rowspan="2"  class="align-middle text-center"><b>SUBJECTS</b></td>
                                            <td width="20%" colspan="2"  class="text-center align-middle" ><b>PERIODIC RATINGS</b></td>
                                            <td width="10%" rowspan="2"  class="text-center align-middle" ><b>FINAL RATING</b></td>
                                            <td width="10%" rowspan="2"  class="text-center align-middle"><b>ACTION TAKEN</b></td>
                                        </tr>
                                        <tr>
                                            @if($x == 1)
                                                <td class="text-center align-middle"><b>1</b></td>
                                                <td class="text-center align-middle"><b>2</b></td>
                                            @elseif($x == 2)
                                                <td class="text-center align-middle"><b>3</b></td>
                                                <td class="text-center align-middle"><b>4</b></td>
                                            @endif
                                        </tr>
                                        @foreach (collect($grades)->where('semid',$x) as $item)
                                            <tr>
                                                <td>{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                                @if($x == 1)
                                                    <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                                    <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                                @elseif($x == 2)
                                                    <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                                    <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                                @endif
                                                <td class="text-center align-middle">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                                                <td class="text-center align-middle">{{$item->actiontaken != null ? $item->actiontaken:''}}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            @php
                                                $genave = collect($finalgrade)->where('semid',$x)->first();
                                            @endphp
                                            <td colspan="3"><b>GENERAL AVERAGE</b></td>
                                            <td class="text-center align-middle">{{ isset($genave->finalrating) ? $genave->finalrating != null ? $genave->finalrating:'' :''}}</td>
                                            <td class="text-center align-middle">{{ isset($genave->actiontaken) ? $genave->actiontaken != null ? $genave->actiontaken:'' :''}}</td>
                                        </tr>
                                    </table>
                                @endfor
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-sm table-bordered" width="100%">
                                <tr>
                                  
                                    <td width="40%"><b>DESCRIPTORS</b></td>
                                    <td width="30%" class=" text-center"><b>GRADING SCALE</b></td>
                                    <td width="30%" class=" text-center"><b>REMARKS</b></td>
                                </tr>
                                <tr>
                                    <td>Outstanding</td>
                                    <td class=" text-center">90-100</td>
                                    <td class=" text-center">Passed</td>
                                </tr>
                                <tr>
                                    <td>Very Satisfactory</td>
                                    <td class=" text-center">85-89</td>
                                    <td class=" text-center">Passed</td>
                                </tr>
                                <tr>
                                    <td>Satisfactory</td>
                                    <td class=" text-center">80-84</td>
                                    <td class=" text-center">Passed</td>
                                </tr>
                                <tr>
                                    <td>Fairly Satisfactory</td>
                                    <td class=" text-center">75-79</td>
                                    <td class=" text-center">Passed</td>
                                </tr>
                                <tr>
                                    <td>Did Not Meet Expectations</td>
                                    <td class=" text-center">Below 75</td>
                                    <td class=" text-center">Failed</td>
                                </tr>
                            </table>    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection
