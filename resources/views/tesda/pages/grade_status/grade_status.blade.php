@extends('tesda.layouts.app2')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
@endsection

@section('content')


    @php

    $buildings = DB::table('building')->where('deleted', 0)->get();
    $schoolinfo = DB::table('schoolinfo')->first()->abbreviation;

    $sy = DB::table('sy')->orderBy('sydesc')->get(); 
    $semester = DB::table('semester')->get(); 

        if(auth()->user()->type == 16){

                $teacher = DB::table('teacher')
                                ->where('userid',auth()->user()->id)
                                ->first();

                $courses = DB::table('college_courses')
                                ->where('courseChairman',$teacher->id)
                                ->where('college_courses.deleted',0)
                                ->get();

        }
        else if(auth()->user()->type == 14){

                $teacher = DB::table('teacher')
                                ->where('userid',auth()->user()->id)
                                ->first();

                $courses = DB::table('college_colleges')
                                ->join('college_courses',function($join){
                                        $join->on('college_colleges.id','=','college_courses.collegeid');
                                        $join->where('college_courses.deleted',0);
                                })
                                ->where('dean',$teacher->id)
                                ->where('college_colleges.deleted',0)
                                ->select('college_courses.*')
                                ->get();

        }
        else{

                $courses = DB::table('college_courses')
                            ->where('college_courses.deleted',0)
                            ->get();

        }
            
        $gradelevel = DB::table('gradelevel')
                            ->where('acadprogid',6)
                            ->where('deleted',0)
                            ->orderBy('sortid')
                            ->select('gradelevel.*','levelname as text')
                            ->get(); 

    @endphp

    <div class="content-header">
        <div class="container-fluid">
            <div class="mb-2">
                <div class="">
                    <h1><i class="fa fa-cog"></i> Grade Status Summary</h1>
                </div>
                <div class="ml-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active font-weight-bold">Grade Status Summary</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content pt-0">
        <div class="container-fluid">
              <div class="row">
                    <div class="col-md-12">
                          <div class="row">
                                <div class="col-md-12">
                                      <div class="info-box shadow-lg">
                                            <div class="info-box-content">
                                                  <div class="row">
                                                        <div class="col-md-2  form-group  mb-2">
                                                              <label for="">School Year</label>
                                                              <select class="form-control select2" id="filter_sy">
                                                              </select>
                                                        </div>
                                                        <div class="col-md-2 form-group mb-2" >
                                                              <label for="">Semester</label>
                                                              <select class="form-control  select2" id="filter_semester">

                                                              </select>
                                                        </div>
                                                        <div class="col-md-3 form-group mb-2" >
                                                              <label for="">Course</label>
                                                              <select class="form-control  select2" id="filter_course">
                                                              </select>
                                                        </div>
                                                        <div class="col-md-2 form-group mb-2" >
                                                              <label for="">Academic Level</label>
                                                              <select class="form-control  select2" id="filter_gradelevel">
                                                                    {{-- @foreach ($semester as $item)
                                                                          <option value="{{$item->id}}">{{$item->semester}}</option>
                                                                    @endforeach --}}
                                                              </select>
                                                        </div>
                                                  </div>
                                            </div>
                                      </div>
                                </div>
                          </div>
                    </div>
              </div>
              <div class="row">
                    <div class="col-md-12">
                          <div class="card shadow">
                                <div class="card-body p-0 pl-2">
                                      <small>Status: <span id="p_status">Proccessing...</span></small>
                                </div>
                          </div>
                    </div>
              </div>
              <div class="row">
                    <div class="col-md-6">
                          <div class="card shadow" >
                                <div class="card-body">
                                      <div class="row">
                                            <table class="table table-bordered table-sm"  style="font-size:.9rem !important">
                                                  <tr>
                                                        <th width="60%">Status (Section)</th>
                                                        <th width="10%" class="text-center" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}>Prelim</th>
                                                        <th width="10%" class="text-center" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}>Midterm</th>
                                                        <th width="10%" class="text-center" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}>PreFinal</th>
                                                        <th width="10%" class="text-center" >Final</th>
                                                  </tr>
                                                  <tr>
                                                        <td width="60%">Unsubmitted</td>
                                                        <td width="10%" class="section_unsubmitted text-center" data-status="1" data-term="1" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                        <td width="10%" class="section_unsubmitted text-center" data-status="1" data-term="2" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                        <td width="10%" class="section_unsubmitted text-center" data-status="1" data-term="3" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                        <td width="10%" class="section_unsubmitted text-center" data-status="1" data-term="4" ></td>
                                                  </tr>
                                                  <tr>
                                                        <td width="60%">Submitted</td>
                                                        <td width="10%" class="section_submitted text-center" data-status="1" data-term="1" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                        <td width="10%" class="section_submitted text-center" data-status="1" data-term="2" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                        <td width="10%" class="section_submitted text-center" data-status="1" data-term="3" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                        <td width="10%" class="section_submitted text-center" data-status="1" data-term="4" ></td>
                                                  </tr>
                                                  <tr>
                                                        <td width="60%">Approved</td>
                                                        <td width="10%" class="section_approved text-center" data-term="1" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                        <td width="10%" class="section_approved text-center" data-term="2" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                        <td width="10%" class="section_approved text-center" data-term="3" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                        <td width="10%" class="section_approved text-center" data-term="4"></td>
                                                  </tr>
                                                  <tr>
                                                        <td width="60%">Pending</td>
                                                        <td width="10%" class="section_pending text-center" data-term="1" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                        <td width="10%" class="section_pending text-center" data-term="2" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                        <td width="10%" class="section_pending text-center" data-term="3" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                        <td width="10%" class="section_pending text-center" data-term="4"></td>
                                                  </tr>
                                                  <tr>
                                                        <td width="60%">Posted</td>
                                                        <td width="10%" class="section_posted text-center" data-term="1" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                        <td width="10%" class="section_posted text-center" data-term="2" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                        <td width="10%" class="section_posted text-center" data-term="3" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                        <td width="10%" class="section_posted text-center" data-term="4"></td>
                                                  </tr>
                                                  <tr>
                                                        <td width="60%">INC</td>
                                                        <td width="10%" class="section_inc text-center" data-term="1" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                        <td width="10%" class="section_inc text-center" data-term="2" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                        <td width="10%" class="section_inc text-center" data-term="3" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                        <td width="10%" class="section_inc text-center" data-term="4"></td>
                                                  </tr>
                                                  <tr>
                                                        <td width="60%">Dropped</td>
                                                        <td width="10%" class="section_drop text-center" data-term="1" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                        <td width="10%" class="section_drop text-center" data-term="2" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                        <td width="10%" class="section_drop text-center" data-term="3" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                        <td width="10%" class="section_drop text-center" data-term="4"></td>
                                                  </tr>
                                            </table>
                                      </div>
                                </div>
                          </div>
                    </div>
                    <div class="col-md-6">
                          <div class="card shadow" >
                                <div class="card-body">
                                      <div class="row">
                                            <table class="table table-bordered table-sm"  style="font-size:.9rem !important">
                                                  <tr>
                                                        <th width="60%">Status (Student)</th>
                                                        <th width="10%" class="text-center" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}>Prelim</th>
                                                        <th width="10%" class="text-center" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}>Midterm</th>
                                                        <th width="10%" class="text-center" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}>PreFinal</th>
                                                        <th width="10%" class="text-center">Final</th>
                                                  </tr>
                                                  <tr>
                                                        <td width="60%">Unsubmitted</td>
                                                        <td width="10%" class="unsubmitted text-center" data-status="1" data-term="1" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                        <td width="10%" class="unsubmitted text-center" data-status="1" data-term="2" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                        <td width="10%" class="unsubmitted text-center" data-status="1" data-term="3" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                        <td width="10%" class="unsubmitted text-center" data-status="1" data-term="4"></td>
                                                  </tr>
                                                  <tr>
                                                        <td width="60%">Submitted</td>
                                                        <td width="10%" class="submitted text-center" data-status="1" data-term="1" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                        <td width="10%" class="submitted text-center" data-status="1" data-term="2" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                        <td width="10%" class="submitted text-center" data-status="1" data-term="3" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                        <td width="10%" class="submitted text-center" data-status="1" data-term="4"></td>
                                                  </tr>
                                                  <tr>
                                                        <td width="60%">Approved</td>
                                                        <td width="10%" class="approved text-center" data-term="1" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                        <td width="10%" class="approved text-center" data-term="2" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                        <td width="10%" class="approved text-center" data-term="3" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                        <td width="10%" class="approved text-center" data-term="4"></td>
                                                  </tr>
                                                  <tr>
                                                        <td width="60%">Pending</td>
                                                        <td width="10%" class="pending text-center" data-term="1" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                        <td width="10%" class="pending text-center" data-term="2" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                        <td width="10%" class="pending text-center" data-term="3" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                        <td width="10%" class="pending text-center" data-term="4"></td>
                                                  </tr>
                                                  <tr>
                                                        <td width="60%">Posted</td>
                                                        <td width="10%" class="posted text-center" data-term="1" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                        <td width="10%" class="posted text-center" data-term="2" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                        <td width="10%" class="posted text-center" data-term="3" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                        <td width="10%" class="posted text-center" data-term="4"></td>
                                                  </tr>
                                                  <tr>
                                                        <td width="60%">INC</td>
                                                        <td width="10%" class="inc text-center" data-term="1" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                        <td width="10%" class="inc text-center" data-term="2" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                        <td width="10%" class="inc text-center" data-term="3" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                        <td width="10%" class="inc text-center" data-term="4"></td>
                                                  </tr>
                                                  <tr>
                                                        <td width="60%">Dropped</td>
                                                        <td width="10%" class="drop text-center" data-term="1" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                        <td width="10%" class="drop text-center" data-term="2" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                        <td width="10%" class="drop text-center" data-term="3" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                        <td width="10%" class="drop text-center" data-term="4"></td>
                                                  </tr>
                                            </table>
                                      </div>
                                </div>
                          </div>
                    </div>
              </div>
              <div class="row">
                    <div class="col-md-12">
                          <div class="card shadow">
                                <div class="card-body"  style="font-size:.8rem !important">
                                      <div class="row">
                                            <div class="col-md-2">
                                                  <label for="">Grades Status (Subject)</label>
                                                  {{-- <select name="" class="form-control form-control-sm select2" id="filter_status_by_subject">
                                                        <option value="uns">Unsubmitted</option>
                                                        <option value="sub">Submitted</option>
                                                        <option value="app">Approved</option>
                                                        <option value="pen">Pending</option>
                                                        <option value="inc">INC</option>
                                                        <option value="drop">Dropped</option>
                                                  </select> --}}
                                            </div>
                                      </div>
                                      <div class="row">
                                            <div class="col-md-12">
                                                  <table class="table table-sm table-bordered" id="datatable_7">
                                                        <thead>
                                                              <tr>
                                                                    <th width="10%">Section</th>
                                                                    <th width="25%">Teacher</th>
                                                                    <th width="25%">Subject</th>
                                                                    {{-- <th width="10%">Course</th> --}}
                                                                    <th width="10%"  class="text-center p-0 align-middle" >Prelim</th>
                                                                    <th width="10%"  class="text-center p-0 align-middle" >Midterm</th>
                                                                    <th width="10%"  class="text-center p-0 align-middle" >Semi-Final</th>
                                                                    <th width="10%"  class="text-center p-0 align-middle" >Final</th>
                                                                    {{-- <th width="10%" class="text-center p-0 align-middle" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}>Prelim</th>
                                                                    <th width="10%" class="text-center p-0 align-middle" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}>Midterm</th>
                                                                    <th width="10%" class="text-center p-0 align-middle" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}>PreFinal</th>
                                                                    <th width="10%" class="text-center p-0 align-middle">Final</th> --}}
                                                              </tr>
                                                        </thead>
                                                  </table>
                                            </div>
                                      </div>
                                      
                                </div>
                          </div>
                         
                    </div>
              </div>
        </div>
  </section>

@endsection

@section('footerjavascript')
@endsection
