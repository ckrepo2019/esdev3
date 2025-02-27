
@extends('ctportal.layouts.app2')

@section('pagespecificscripts')
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <style>
            .tableFixHead thead th {
                  position: sticky;
                  top: 0;
                  background-color: #fff;
                  outline: 2px solid #dee2e6;
                  outline-offset: -1px;
            
            }
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                  margin-top: -9px;
            }

            .grade_td{
                  cursor: pointer;
                  vertical-align: middle !important;
            }
      </style>
@endsection

@section('content')

@php
   $sy = DB::table('sy')->orderBy('sydesc','desc')->get(); 
   $semester = DB::table('semester')->get(); 
   $schoolinfo = DB::table('schoolinfo')->first()->abbreviation;
   
   $dean = DB::table('college_colleges')
                  ->join('teacher',function($join){
                        $join->on('college_colleges.dean','=','teacher.id');
                        $join->where('teacher.deleted',0);
                  })
                  ->where('college_colleges.deleted',0)
                  ->select(
                        'teacher.id',
                        DB::raw("CONCAT(teacher.lastname,', ',teacher.firstname) as text")
                  )
                  ->get();
@endphp

<div class="modal fade" id="modal_1" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-lg">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title">Student List</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body" style="font-size:.9rem">
                        <table class="table table-sm" id="datatable_2"  width="100%">
                              <thead>
                                    <tr>
                                          <th width="60%">Student</th>
                                          <th width="15%">Grade Level</th>
                                          <th width="15%">Course</th>
                                          <th width="10%">Gender</th>
                                    </tr>
                              </thead>
                        </table>
                  </div>
            </div>
      </div>
</div>   


<div class="modal fade" id="modal_2" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-lg">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title">Grades</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body pt-0">
                        <div class="row">
                              <div class="col-md-8">
                                    <p><i>Note: Press  <b class="text-danger">I</b> to student as Incomplete. Press <b class="text-danger">D</b> to mark student as Dropped.</i></p>
                              </div>
                              <div class="col-md-4 text-right">
                                    <button class="btn btn-primary btn-sm" id="print_grades_to_modal" style="font-size:.7rem !important">Print</button>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12">
                                    <table class="table table-sm table-striped mb-0"  style="font-size:.9rem">
                                          @if(strtoupper($schoolinfo) == 'SPCT' || strtoupper($schoolinfo) == 'GBBC')
                                                <tr>
                                                      <th id="subject" width="70%"></th>
                                                      <th id="section" width="30%" hidden></th>
                                                </tr>
                                          @else
                                                <tr>
                                                      <th id="section" width="30%"></th>
                                                      <th id="subject" width="70%"></th>
                                                </tr>
                                          @endif
                                    </table>
                              </div>
                              <div class="col-md-12 table-responsive tableFixHead" style="height: 420px;">
                                    <table class="table table-sm table-striped table-bordered mb-0 table-head-fixed table-hover"  style="font-size:.8rem" width="100%">
                                          <thead>
                                                <tr>
                                                      @if(strtoupper($schoolinfo) == 'SPCT')
                                                            <th width="3%" class="text-center">#</th>
                                                            <th width="39%">Student</th>
                                                            <th width="23%">Course</th>
                                                            <th width="10%" class="text-center">Prelim</th>
                                                            <th width="10%" class="text-center">Final</th>
                                                            <th width="15%" class="text-center" >Term Grade</th>
                                                      @elseif(strtoupper($schoolinfo) == 'HCCSI')
                                                            <th width="3%" class="text-center">#</th>
                                                            <th width="39%">Student</th>
                                                            <th width="23%">Course</th>
                                                            <th width="10%" class="text-center">Prelim</th>
                                                            <th width="10%" class="text-center">Final</th>
                                                            <th width="15%" class="text-center" >Term Grade</th>
                                                      @elseif(strtoupper($schoolinfo) == 'APMC')
                                                            <th width="3%" class="text-center">#</th>
                                                            <th width="37%">Name of students</th>
                                                            <th width="10%" class="text-center">Prelim</th>
                                                            <th width="10%" class="text-center">Midterm</th>
                                                            <th width="10%" class="text-center">Semi</th>
                                                            <th width="10%" class="text-center" >Final</th>
                                                            <th width="10%" class="text-center" >FG</th>
                                                            <th width="10%" class="text-center" >Remarks</th>
                                                      @elseif(strtoupper($schoolinfo) == 'GBBC')
                                                            <th width="3%" class="text-center">#</th>
                                                            <th width="47%">Name of students</th>
                                                            <th width="40%">Course</th>
                                                            <th width="10%" class="text-center" >Final</th>
                                                      @else
                                                            <th width="3%" class="text-center">#</th>
                                                            <th width="35%">Student</th>
                                                            <th width="22%">Course</th>
                                                            <th width="10%" class="text-center">Prelim</th>
                                                            <th width="10%" class="text-center">Midterm</th>
                                                            <th width="10%" class="text-center">PreFinal</th>
                                                            <th width="10%" class="text-center" >Final</th>
                                                      @endif
                                                      
                                                </tr>
                                          </thead>
                                          <tbody id="student_list_grades">
            
                                          </tbody>
                                    </table>
                              </div>

                              <div class="col-md-12 mt-2">
                                    <button id="save_grades" class="btn btn-info btn-sm" disabled hidden>Save Grades</button>
                                    <button id="grade_submit" class="btn btn-primary btn-sm">Submit Grades</button>
                              </div>
                              {{-- <div class="col-md-6 mt-2">
                                    <table class="table table-sm table-bordered" width="98%" style="font-size:.7rem !important">
                                          @if(strtoupper($schoolinfo) == 'SPCT')
                                                <tr>
                                                      <td width="55%" colspan="2"  class="p-0 pl-1"></td>
                                                      <td width="15%" class="text-center p-0" >Prelim</td>
                                                      <td width="15%" class="text-center p-0">Final</td>
                                                      <td width="15%" class="text-center p-0">Term Grade</td>
                                                </tr>
                                                <tr>
                                                      <td width="55%" colspan="2"  class="p-0 pl-1">Students</td>
                                                      <td width="15%" class="text-center p-0 student_count" data-stat="2"></td>
                                                      <td width="15%" class="text-center p-0 student_count"></td>
                                                      <td width="15%" class="text-center p-0 student_count"></td>
                                                </tr>
                                                <tr>
                                                      <td width="40%" colspan="2" class="p-0 pl-1">Passed</td>
                                                      <td width="15%" class="text-center p-0 p_count" data-stat="2"></td>
                                                      <td width="15%" class="text-center p-0 p_count" data-stat="3"></td>
                                                      <td width="15%" class="text-center p-0 p_count" data-stat="4"></td>
                                                </tr>
                                                <tr>
                                                      <td width="40%" colspan="2" class="p-0 pl-1">Failed</td>
                                                      <td width="15%" class="text-center p-0 f_count" data-stat="2"></td>
                                                      <td width="15%" class="text-center p-0 f_count" data-stat="3"></td>
                                                      <td width="15%" class="text-center p-0 f_count" data-stat="4"></td>
                                                </tr>
                                                <tr>
                                                      <td width="40%" colspan="2" class="p-0 pl-1">No Grade</td>
                                                      <td width="15%" class="text-center p-0 ng_count" data-stat="2"></td>
                                                      <td width="15%" class="text-center p-0 ng_count" data-stat="3"></td>
                                                      <td width="15%" class="text-center p-0 ng_count" data-stat="4"></td>
                                                </tr>
                                          @elseif(strtoupper($schoolinfo) == 'APMC')
                                                <th id="section" width="3%" class="text-center">#</th>
                                                <th id="section" width="37%">Name of students</th>
                                                <th width="10%" class="text-center">Prelim</th>
                                                <th width="10%" class="text-center">Midterm</th>
                                                <th width="10%" class="text-center">Semi</th>
                                                <th width="10%" class="text-center" >Final</th>
                                                <th width="10%" class="text-center" >FG</th>
                                                <th width="10%" class="text-center" >Remarks</th>
                                          @else
                                                <tr>
                                                      <td width="40%" colspan="2"  class="p-0 pl-1"></td>
                                                      <td width="15%" class="text-center p-0" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}>Prelim</td>
                                                      <td width="15%" class="text-center p-0" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}>Midterm</td>
                                                      <td width="15%" class="text-center p-0" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}>PreFinal</td>
                                                      <td width="15%" class="text-center p-0">Final</td>
                                                </tr>
                                                <tr>
                                                      <td width="40%" colspan="2"  class="p-0 pl-1">Students</td>
                                                      <td width="15%" class="text-center p-0 student_count" data-stat="1" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="15%" class="text-center p-0 student_count" data-stat="2" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="15%" class="text-center p-0 student_count" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="15%" class="text-center p-0 student_count" ></td>
                                                </tr>
                                                <tr>
                                                      <td width="40%" colspan="2" class="p-0 pl-1">Passed</td>
                                                      <td width="15%" class="text-center p-0 p_count" data-stat="1" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="15%" class="text-center p-0 p_count" data-stat="2" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="15%" class="text-center p-0 p_count" data-stat="3" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="15%" class="text-center p-0 p_count" data-stat="4"></td>
                                                </tr>
                                                <tr>
                                                      <td width="40%" colspan="2" class="p-0 pl-1">Failed</td>
                                                      <td width="15%" class="text-center p-0 f_count" data-stat="1" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="15%" class="text-center p-0 f_count" data-stat="2" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="15%" class="text-center p-0 f_count" data-stat="3" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="15%" class="text-center p-0 f_count" data-stat="4"></td>
                                                </tr>
                                                <tr>
                                                      <td width="40%" colspan="2" class="p-0 pl-1">No Grade</td>
                                                      <td width="15%" class="text-center p-0 ng_count" data-stat="1" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="15%" class="text-center p-0 ng_count" data-stat="2" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="15%" class="text-center p-0 ng_count" data-stat="3" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                      <td width="15%" class="text-center p-0 ng_count" data-stat="4"></td>
                                                </tr>
                                               
                                          @endif
                                    </table>
                              </div> --}}
                              {{-- <div class="col-md-6 mt-2" style="padding-right: 1rem" >
                                    <table class="table table-sm table-bordered" width="98%" style="font-size:.7rem !important">
                                          <tr>
                                                <td width="40%" colspan="2" class="p-0 pl-1"></td>
                                                <td width="15%" class="text-center p-0" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}>Prelim</td>
                                                <td width="15%" class="text-center p-0" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}>Midterm</td>
                                                <td width="15%" class="text-center p-0" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}>PreFinal</td>
                                                <td width="15%" class="text-center p-0">Final</td>
                                          </tr>
                                          <tr>
                                                <td width="40%" colspan="2" class="p-0 pl-1">Students</td>
                                                <td width="15%" class="text-center p-0 student_count" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                <td width="15%" class="text-center p-0 student_count" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                <td width="15%" class="text-center p-0 student_count" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                <td width="15%" class="text-center p-0 student_count"></td>
                                          </tr>
                                          <tr>
                                                <td width="40%" colspan="2" class="p-0 pl-1">Submitted</td>
                                                <td width="15%" class="text-center p-0 sub_count" data-stat="1" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                <td width="15%" class="text-center p-0 sub_count" data-stat="2" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                <td width="15%" class="text-center p-0 sub_count" data-stat="3" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                <td width="15%" class="text-center p-0 sub_count" data-stat="4"></td>
                                          </tr>
                                          <tr>
                                                <td width="40%" colspan="2" class="p-0 pl-1">Pending</td>
                                                <td width="15%" class="text-center p-0 pen_count"  data-stat="1" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                <td width="15%" class="text-center p-0 pen_count"  data-stat="2" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                <td width="15%" class="text-center p-0 pen_count"  data-stat="3" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                <td width="15%" class="text-center p-0 pen_count"  data-stat="4"></td>
                                          </tr>
                                          <tr>
                                                <td width="40%" colspan="2" class="p-0 pl-1">Approved</th>
                                                <td width="15%" class="text-center p-0 app_count" data-stat="1" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                <td width="15%" class="text-center p-0 app_count" data-stat="2" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                <td width="15%" class="text-center p-0 app_count" data-stat="3" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                <td width="15%" class="text-center p-0 app_count" data-stat="4" ></td>
                                          </tr>
                                          <tr>
                                                <td width="40%" colspan="2" class="p-0 pl-1">INC</th>
                                                <td width="15%" class="text-center p-0 inc_count" data-stat="1" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                <td width="15%" class="text-center p-0 inc_count" data-stat="2" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                <td width="15%" class="text-center p-0 inc_count" data-stat="3" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                <td width="15%" class="text-center p-0 inc_count" data-stat="4"></td>
                                          </tr>
                                          <tr>
                                                <td width="40%" colspan="2" class="p-0 pl-1">Dropped</td>
                                                <td width="15%" class="text-center p-0 drop_count" data-stat="1" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                <td width="15%" class="text-center p-0 drop_count" data-stat="2" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                <td width="15%" class="text-center p-0 drop_count" data-stat="3" {{strtoupper($schoolinfo) == 'GBBC' ? 'hidden="hidden"' : ''}}></td>
                                                <td width="15%" class="text-center p-0 drop_count" data-stat="4"></td>
                                          </tr>
                                    </table>
                                   
                              </div> --}}
                             
                        </div>
                        <div class="row">

                        </div>
                  </div>
                  <div class="modal-footer pt-1 pb-1"  style="font-size:.7rem">
                        <i id="message_holder"></i>
                  </div>
            </div>
      </div>
</div>   


<div class="modal fade" id="modal_3" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title">Grade Submission</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body " style="font-size:.9rem">
                        <div class="row">
                              <div class="col-md-6 form-group mb-0">
                                    <select name="quarter_select" id="quarter_select" class="form-control form-control-sm">
                                          <option value="">Select Term</option>
                                          @if(strtoupper($schoolinfo) == 'SPCT')
                                                <option value="midtermgrade">Prelim</option>
                                                <option value="prefigrade">Final</option>
                                                <option value="finalgrade">Term Grade</option>
                                          @elseif(strtoupper($schoolinfo) == 'APMC')
                                                <option value="prelemgrade">Prelim</option>
                                                <option value="midtermgrade">Midterm</option>
                                                <option value="prefigrade">Semi</option>
                                                <option value="finalgrade">Final</option>
                                          @elseif(strtoupper($schoolinfo) == 'GBBC')
                                                <option value="finalgrade">Final</option>
                                          @else
                                                <option value="prelemgrade">Prelim</option>
                                                <option value="midtermgrade">Midterm</option>
                                                <option value="prefigrade">PreFinal</option>
                                                <option value="finalgrade">Final</option>
                                          @endif
                                    </select>
                                    <small class="text-danger"><i>Select a term to view and submit grades.</i></small>
                              </div>
                              <div class="col-md-6">
                                    <button class="btn btn-primary float-right btn-sm" id="submit_selected_grade">Submit</button>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12 table-responsive tableFixHead" style="height: 422px;">
                                    <table class="table table-sm table-striped table-bordered mb-0 table-head-fixed"  style="font-size:.9rem" width="100%">
                                          <thead>
                                                <tr>
                                                      <th width="5%"><input type="checkbox" disabled checked="checked" class="select_all"> </th>
                                                      <th width="20%">SID</th>
                                                      <th width="60%">Student</th>
                                                      <th width="15%" class="text-centerv">Grade</th>
                                                </tr>
                                          </thead>
                                          <tbody id="datatable_4">

                                          </tbody>
                                    </table>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
</div>   



<div class="modal fade" id="dean_holder_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-sm">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body" style="font-size:.9rem">
                       <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Dean</label>
                                    <select class="form-control select2" id="printable_dean">

                                    </select>
                              </div>
                       </div>
                       <div class="row">
                              <div class="col-md-12">
                                    <button class="btn btn-primary btn-sm" id="print_grades">Print</button>
                              </div>
                       </div>
                  </div>
            </div>
      </div>
</div>  

<section class="content-header">
      <div class="container-fluid">
          <div class="row mb-2">
              <div class="col-sm-6">
                  <h1>Student Grades</h1>
              </div>
              <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="/home">Home</a></li>
                  <li class="breadcrumb-item active">Student Grades</li>
              </ol>
              </div>
          </div>
      </div>
</section>
<section class="content pt-0">
      <div class="container-fluid">
            <div class="row">
                  <div class="col-md-6">
                        <div class="row">
                              <div class="col-md-12">
                                    <div class="info-box shadow-lg">
                                          <div class="info-box-content">
                                                <div class="row">
                                                      <div class="col-md-4">
                                                            <label for="">School Year</label>
                                                            <select class="form-control form-control-sm select2" id="filter_sy">
                                                                  @foreach ($sy as $item)
                                                                        @if($item->isactive == 1)
                                                                              <option value="{{$item->id}}" selected="selected">{{$item->sydesc}}</option>
                                                                        @else
                                                                              <option value="{{$item->id}}">{{$item->sydesc}}</option>
                                                                        @endif
                                                                  @endforeach
                                                            </select>
                                                      </div>
                                                      <div class="col-md-4" >
                                                            <label for="">Semester</label>
                                                            <select class="form-control form-control-sm  select2" id="filter_semester">
                                                                  <option value="">Select semester</option>
                                                                  @foreach ($semester as $item)
                                                                        <option {{$item->isactive == 1 ? 'selected' : ''}} value="{{$item->id}}">{{$item->semester}}</option>
                                                                  @endforeach
                                                            </select>
                                                      </div>
                                                </div>
                                                {{-- <div class="row">
                                                      <div class="col-md-4">
                                                            <button class="btn btn-primary btn-block btn-sm" id="filter_button_1"><i class="fas fa-filter"></i> Filter</button>
                                                      </div>
                                                </div> --}}
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
            <div class="row">
                  <div class="col-md-12">
                        <div class="card shadow">
                              <div class="card-body">
                                    <div class="row">
                                          <div class="col-md-12" style="font-size:.9rem">
                                                <table class="table table-sm table-striped" id="datatable_1" >
                                                      <thead>
                                                            <tr>
                                                                  <th width="20%">{{ strtoupper($schoolinfo) == 'SPCT' ? 'Subject Code' : 'Section' }}</th>
                                                                  <th width="35%">{{ strtoupper($schoolinfo) == 'SPCT' ? 'Subject Description' : 'Subject' }}</th>
                                                                  <th width="40%" class="text-center"></th>
                                                            </tr>
                                                      </thead>
                                                </table>
                                          </div>
                                    </div>
                              </div>
                        </div>      
                  </div>
            </div>
            {{-- <div class="row">
                  <div class="col-md-12">
                        <div class="card shadow">
                              <div class="card-header">
                                    <h3 class="card-title">Grade Status</h3>
                              </div>
                              <div class="card-body">
                                    <div class="row">
                                          <div class="col-md-12" style="font-size:.9rem">
                                                <table class="table table-sm table-striped" id="datatable_3" >
                                                      <thead>
                                                            <tr>
                                                                  <th width="20%">Section</th>
                                                                  <th width="35%">Subject</th>
                                                                  <th width="10%" class="text-center">Prelim</th>
                                                                  <th width="10%" class="text-center" {{strtoupper($schoolinfo) == 'SPCT' ? 'hidden' : ''}}>Midterm</th>
                                                                  <th width="10%" class="text-center"  {{strtoupper($schoolinfo) == 'SPCT' ? 'hidden' : ''}}>PreFinal</th>
                                                                  <th width="10%" class="text-center">Final</th>
                                                            </tr>
                                                      </thead>
                                                </table>
                                          </div>
                                    </div>
                              </div>
                        </div>      
                  </div>
            </div> --}}
      </div>
  </section>
  
     
@endsection

@section('footerscript')

      <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
      <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
      <script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
      <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
      {{-- <script>
            $(document).ready(function () {

                  $(document).on('click','.submit_grades',function(){
                  
                  })
            })
      </script> --}}

      <script>
            $('#filter_sy').select2()
            $('#filter_semester').select2()
      </script>

      <script>
            $(document).ready(function () {

                  var school = @json($schoolinfo);

                  var isSaved = false;
                  var isvalidHPS = true;
                  var hps = []
                  var currentIndex 
                  var can_edit = true
                  
                  $(document).on('click','.input_grades',function(){

                        if(school == 'spct'.toUpperCase() && $(this).attr('data-term') == 'finalgrade'){
                              return false;
                        }

                        if(currentIndex != undefined){
                              if(isvalidHPS){
                                    if(can_edit){
                                          string = $(this).text();
                                          currentIndex = this;
                                          $('#start').length > 0 ? dotheneedful(this) : false
                                          $('td').removeAttr('style');
                                          $('#start').removeAttr('id')
                                          $(this).attr('id','start')
                                          $(currentIndex).removeClass('bg-danger')
                                          $(currentIndex).removeClass('bg-warning')
                                          var start = document.getElementById('start');
                                                            start.focus();
                                                            start.style.backgroundColor = 'green';
                                                            start.style.color = 'white';
                                    }
                              }
                        }
                        else{
                              if(can_edit){
                                    string = $(this).text();
                                    currentIndex = this;
                                    $('#start').length > 0 ? dotheneedful(this) : false
                                    $('td').removeAttr('style');
                                    $('#start').removeAttr('id')
                                    $(this).attr('id','start')
                                    $(currentIndex).removeClass('bg-danger')
                                    $(currentIndex).removeClass('bg-warning')
                                    var start = document.getElementById('start');
                                                      start.focus();
                                                      start.style.backgroundColor = 'green';
                                                      start.style.color = 'white';

                              }
                        }
                  })


                  function dotheneedful(sibling) {
                        if (sibling != null) {
                              currentIndex = sibling
                              $(sibling).removeClass('bg-danger')
                              $(sibling).removeClass('bg-warning')

                              if($(start).text() == 'DROPPED'){
                                    $(start).addClass('bg-danger')
                              }else if($(start).text() == 'INC' || $(start).attr('data-status') == 3){
                                    $(start).addClass('bg-warning')
                              }
                             
                              start.style.backgroundColor = '';
                              start.style.color = '';
                              sibling.focus();
                              sibling.style.backgroundColor = 'green';
                              sibling.style.color = 'white';
                              start = sibling;

                             
                             
                              $('#message').empty();
                              string = $(currentIndex)[0].innerText
                        }
                  }

                  document.onkeydown = checkKey;

                  function checkKey(e) {

                        e = e || window.event;
                        if (e.keyCode == '38' && currentIndex != undefined)  {
                              var idx = start.cellIndex;
                              var nextrow = start.parentElement.previousElementSibling;
                              if(nextrow == null || !$(nextrow.cells[idx]).hasClass('input_grades')){
                                    return false;
                              }
                              if(school == 'spct'.toUpperCase() && $(nextrow.cells[idx]).attr('data-term') == 'finalgrade'){
                                    return false;
                              }
                              else{
                                    $('#curText').text(string)
                                    var sibling = nextrow.cells[idx];
                                    if(sibling == undefined){
                                          return false;
                                    }
                                    string = sibling.innerText;
                                    dotheneedful(sibling);
                              }
                        } else if (e.keyCode == '40' && currentIndex != undefined) {
                              var idx = start.cellIndex;
                              var nextrow = start.parentElement.nextElementSibling;
                              if(nextrow == null || !$(nextrow.cells[idx]).hasClass('input_grades')){
                                    return false;
                              }
                              if(school == 'spct'.toUpperCase() && $(nextrow.cells[idx]).attr('data-term') == 'finalgrade'){
                                    return false;
                              }
                              else{
                                    $('#curText').text(string)
                                    var sibling = nextrow.cells[idx];
                                    if(sibling == undefined){
                                          return false;
                                    }
                                    string = sibling.innerText;
                                    dotheneedful(sibling);
                              }
                        } else if (e.keyCode == '37' && currentIndex != undefined) {
                              var sibling = start.previousElementSibling;
                              if(sibling == null || !$(sibling).hasClass('input_grades')){
                                    return false;
                              }
                              else if($(sibling)[0].nodeName != "TD" ){
                                    return false;
                              }
                              if(school == 'spct'.toUpperCase() && $(sibling).attr('data-term') == 'finalgrade'){
                                    return false;
                              }
                              $('#curText').text(string)
                              if($(sibling)[0].cellIndex != 0){
                                    string = sibling.innerText;
                                    dotheneedful(sibling);
                              }

                        } else if (e.keyCode == '39' && currentIndex != undefined) {
                              var sibling = start.nextElementSibling;
                              if(sibling == null || !$(sibling).hasClass('input_grades')){
                                    return false;
                              }
                              else if($(sibling)[0].nodeName != "TD" ){
                                    return false;
                              }
                              if(school == 'spct'.toUpperCase() && $(sibling).attr('data-term') == 'finalgrade'){
                                    return false;
                              }
                              $('#curText').text(string)
                              if($(sibling)[0].cellIndex != 0){
                                    string = sibling.innerText;
                                    dotheneedful(sibling);
                              }
                        }
                        else if (e.keyCode == '73' && currentIndex != undefined) {
                              $(currentIndex).text("INC")
                              $(currentIndex).addClass('updated')
                              $('#save_grades').removeAttr('disabled')
                              $('#grade_submit').attr('disabled','disabled')
                        }
                        else if (e.keyCode == '68' && currentIndex != undefined) {
                              $(currentIndex).text("DROPPED")
                              $(currentIndex).addClass('updated')
                              $('#save_grades').removeAttr('disabled')
                              $('#grade_submit').attr('disabled','disabled')
                        }
                        else if( e.key == "Backspace" && currentIndex != undefined){

                              if(currentIndex.innerText == 'INC' || currentIndex.innerText == 'DROPPED'){
                                    $(currentIndex).text('')
                                    $('#curText').text("")
                                    string = ''
                                    $(currentIndex).addClass('updated')
                                    $('#grade_submit').attr('disabled','disabled')
                                    $('#save_grades').removeAttr('disabled')
                                    return false
                              }

                              string = currentIndex.innerText
                              string = string.slice(0 , -1);

                              if(string.length == 0){
                                    string = '';
                                    currentIndex.innerText = string
                              }else{
                                    currentIndex.innerText = parseInt(string)
                                    inputIndex = currentIndex
                              }

                              $(currentIndex).addClass('updated')
                              $('#grade_submit').attr('disabled','disabled')
                              $('#save_grades').removeAttr('disabled')

                              if(school == 'spct'.toUpperCase()){

                                    $(currentIndex).text(string)
                                    $('#curText').text(string)

                                    var temp_studid = $(currentIndex).attr('data-studid')
                                    var average = parseFloat( ( parseInt ( $('.input_grades[data-studid="'+temp_studid+'"][data-term="midtermgrade"]').text()) + parseInt($('.input_grades[data-studid="'+temp_studid+'"][data-term="prefigrade"]').text() ) ) / 2 ).toFixed()
                                    if(average != 'NaN'){
                                          $('.input_grades[data-studid="'+temp_studid+'"][data-term="finalgrade"]').text(average)
                                          $('.input_grades[data-studid="'+temp_studid+'"][data-term="finalgrade"]').addClass('updated')
                                    }

                              }else if(school == 'apmc'.toUpperCase()){

                                    $(currentIndex).text(string)
                                    $('#curText').text(string)

                                    var temp_studid = $(currentIndex).attr('data-studid')

                                    var average = parseFloat( ( parseFloat ( $('.input_grades[data-studid="'+temp_studid+'"][data-term="prelemgrade"]').text()) + parseFloat($('.input_grades[data-studid="'+temp_studid+'"][data-term="midtermgrade"]').text() )  + parseFloat($('.input_grades[data-studid="'+temp_studid+'"][data-term="prefigrade"]').text() )  + parseFloat( $('.input_grades[data-studid="'+temp_studid+'"][data-term="finalgrade"]').text() ) ) / 4 ).toFixed(2)

                                    if(average != 'NaN'){
                                          $('th[data-studid="'+temp_studid+'"][data-term="fg"]').text(average)

                                          if(average >= 3){
                                                $('th[data-studid="'+temp_studid+'"][data-term="remarks"]').text('FAILED')
                                          }else{
                                                $('th[data-studid="'+temp_studid+'"][data-term="remarks"]').text('PASSED')
                                          }
                                    }else{
                                          $('th[data-studid="'+temp_studid+'"][data-term="fg"]').text('')
                                          $('th[data-studid="'+temp_studid+'"][data-term="remarks"]').text('')
                                    }

                              }else{

                                    $(currentIndex).text(string)
                                    $('#curText').text(string)

                              }

                        }
                        else if ( ( ( e.key >= 0 && e.key <= 9 ) || e.key == '.' ) && currentIndex != undefined) {


                              if(currentIndex.innerText == 'INC' || currentIndex.innerText == 'DROPPED'){
                                    string = ''
                              }

                              string += e.key;
                              if(string > 100){
                                    string = 100 
                              }
                              $(currentIndex).addClass('updated')
                              $('#save_grades').removeAttr('disabled')
                              $('#grade_submit').attr('disabled','disabled')

                              if(school == 'spct'.toUpperCase()){

                                    $(currentIndex).text(string)
                                    $('#curText').text(string)

                                    var temp_studid = $(currentIndex).attr('data-studid')
                                    var average = parseFloat( ( parseInt ( $('.grade_td[data-studid="'+temp_studid+'"][data-term="midtermgrade"]').text()) + parseInt($('.grade_td[data-studid="'+temp_studid+'"][data-term="prefigrade"]').text() ) ) / 2 ).toFixed()

                                    if(average != 'NaN'){
                                          $('.grade_td[data-studid="'+temp_studid+'"][data-term="finalgrade"]').text(average)
                                          $('.grade_td[data-studid="'+temp_studid+'"][data-term="finalgrade"]').addClass('updated')
                                    }
                                  
                                  
                              }else if(school == 'apmc'.toUpperCase()){

                                    $(currentIndex).text(string)
                                    $('#curText').text(string)

                                    var temp_studid = $(currentIndex).attr('data-studid')
                                    var average = parseFloat( ( parseFloat ( $('.grade_td[data-studid="'+temp_studid+'"][data-term="prelemgrade"]').text()) + parseFloat($('.grade_td[data-studid="'+temp_studid+'"][data-term="midtermgrade"]').text() )  + parseFloat($('.grade_td[data-studid="'+temp_studid+'"][data-term="prefigrade"]').text() )  + parseFloat( $('.grade_td[data-studid="'+temp_studid+'"][data-term="finalgrade"]').text() ) ) / 4 ).toFixed(2)

                                    if(average != 'NaN'){
                                          $('th[data-studid="'+temp_studid+'"][data-term="fg"]').text(average)

                                          if(average >= 3){
                                                $('th[data-studid="'+temp_studid+'"][data-term="remarks"]').text('FAILED')
                                          }else{
                                                $('th[data-studid="'+temp_studid+'"][data-term="remarks"]').text('PASSED')
                                          }
                                    }else{
                                          $('th[data-studid="'+temp_studid+'"][data-term="fg"]').text('')
                                          $('th[data-studid="'+temp_studid+'"][data-term="remarks"]').text('')
                                    }

                              }else{

                                    $(currentIndex).text(string)
                                    $('#curText').text(string)

                              }

                           
                             
                        }
                      
                  }

            })


      </script>

      <script>
            $(document).ready(function (){

                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })

                  $.ajaxSetup({
                        headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                  });

                  $(document).on('click','#grade_submit',function() {
                        $('#quarter_select').val("")
                        $('.grade_submission_student').empty()
                        $('.select').attr('disabled','disabled')
                        $('.select').removeAttr('data-id')
                        $('.select_all').attr('disabled','disabled')
                        $('.select').prop('checked',true)
                        $('.select_all').prop('checked',true)
                        $('#submit_selected_grade').attr('disabled','disabled')
                        // $('#submit_selected_grade').removeAttr('class')
                        // $('#submit_selected_grade').addClass('btn btn-primary float-right btn-sm')
                        // $('#submit_selected_grade').text('Submit Grades')
                        // $('#submit_selected_grade').attr('data-id',1)
                        $('#modal_3').modal()
                  })

                  $(document).on('click','.select_all',function() {
                        if($(this).prop('checked') == true){
                              $('.select').prop('checked',true)
                        }else{
                              $('.select').each(function(){
                                    if($(this).attr('disabled') == undefined){
                                          $(this).prop('checked',false)
                                    }
                              })
                        }
                  })

                  $(document).on('change','#quarter_select',function() {
                        var term = $(this).val()
                        if(term == ""){
                              $('.select_all').attr('disabled','disabled')
                              $('.select').attr('disabled','disabled')
                              $('.grade_submission_student').text()
                              $('#submit_selected_grade').attr('disabled','disabled')
                              $('.select').removeAttr('data-id')
                              $('.grade_submission_student').empty()
                              return false
                        }
                        $('#submit_selected_grade').removeAttr('disabled')
                        $('.select_all').removeAttr('disabled')
                        $('.select').removeAttr('disabled')
                        $('.grade_td[data-term="'+term+'"]').each(function(a,b){
                              if($(this).attr('data-status') == 1 || $(this).attr('data-status') == 7 || $(this).attr('data-status') == 8 || $(this).attr('data-status') == 9 || $(this).attr('data-status') == 2 || $(this).attr('data-status') == 4){
                                    $('.select[data-studid="'+$(this).attr('data-studid')+'"]').attr('disabled','disabled')
                              }
                              $('.grade_submission_student[data-studid="'+$(this).attr('data-studid')+'"]').text($(this).text())
                              $('.select[data-studid="'+$(this).attr('data-studid')+'"]').attr('data-id',$(this).attr('data-id'))
                        })
                  })

                  $(document).on('click','#submit_selected_grade',function() {
                        // if($(this).attr('data-id') == 1){
                              submit_grade()
                        // }
                        

                  })

                  function submit_grade(){

                        var selected = []
                        var term = $('#quarter_select').val()

                        $('.select').each(function(){
                              if($(this).prop('checked') == true && $(this).attr('disabled') == undefined && $(this).attr('data-id') != undefined){
                                    selected.push($(this).attr('data-id'))
                              }
                        })

                        if(selected.length == 0){
                              Toast.fire({
                                    type: 'info',
                                    title: 'No student selected'
                              })
                              return false
                        }

                        Swal.fire({
                              html:
                                    '<h4>Are you sure you want <br>' +
                                    'to submit grades?</h4>',
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Submit Grades!'
                        }).then((result) => {
                              if (result.value) {
                                    $.ajax({
                                          type:'POST',
                                          url: '/college/teacher/student/grades/submit',
                                          data:{
                                                syid:$('#filter_sy').val(),
                                                semid:$('#filter_semester').val(),
                                                term:term,
                                                selected:selected,
                                          },
                                          success:function(data) {
                                                if(data[0].status == 1){
                                                      Toast.fire({
                                                            type: 'success',
                                                            title: 'Submitted Successfully!'
                                                      })
                                                      $.each(selected,function(a,b){

                                                            $('.input_grades[data-id="'+b+'"][data-term="'+term+'"]').removeClass('bg-warning')
                                                            $('.select[data-id="'+b+'"]').attr('disabled','disabled')
                                                            $('.input_grades[data-id="'+b+'"][data-term="'+term+'"]').attr('data-status',1)
                                                            $('.input_grades[data-id="'+b+'"][data-term="'+term+'"]').addClass('bg-success')
                                                            $('.input_grades[data-id="'+b+'"][data-term="'+term+'"]').removeClass('input_grades')
                                                            var temp_id = all_grades.findIndex(x=>x.id == b)
                                                            if(term == 'prelemgrade'){
                                                                  all_grades[temp_id].prelemstatus = 1
                                                            }else if(term == 'midtermgrade'){
                                                                  all_grades[temp_id].midtermstatus = 1
                                                            }else if(term == 'prefigrade'){
                                                                  all_grades[temp_id].prefistatus = 1
                                                            }else if(term == 'finalgrade'){
                                                                  all_grades[temp_id].finalstatus = 1
                                                            }
                                                            plot_subject_grades(all_grades)
                                                      })
                                                }else{
                                                      Toast.fire({
                                                            type: 'error',
                                                            title: 'Something went wrong!'
                                                      })
                                                }
                                          },error:function(){
                                                Toast.fire({
                                                      type: 'error',
                                                      title: 'Something went wrong!'
                                                })
                                          }
                                    })
                              }
                        })

                  }

                  function inc_grade(){

                        var selected = []
                        var students = []
                        var term = $('#quarter_select').val()

                        $('.select').each(function(){
                              if($(this).prop('checked') == true && $(this).attr('disabled') == undefined){
                                    selected.push($(this).attr('data-id'))
                                    students.push($(this).attr('data-id'))
                              }
                        })

                        Swal.fire({
                              html:
                                    '<h4>Are you sure you want <br>' +
                                    'to mark student as INC?</h4>',
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Submit Grades!'
                        }).then((result) => {
                              if (result.value) {
                                    $.ajax({
                                          type:'POST',
                                          url: '/college/teacher/student/grades/inc',
                                          data:{
                                                syid:$('#filter_sy').val(),
                                                semid:$('#filter_semester').val(),
                                                term:term,
                                                selected:selected,
                                          },
                                          success:function(data) {
                                                if(data[0].status == 1){
                                                      Toast.fire({
                                                            type: 'success',
                                                            title: 'Submitted Successfully!'
                                                      })
                                                      $.each(selected,function(a,b){
                                                            $('.select[data-id="'+b+'"]').attr('disabled','disabled')
                                                            $('.input_grades[data-id="'+b+'"][data-term="'+term+'"]').attr('data-status',1)
                                                            $('.input_grades[data-id="'+b+'"][data-term="'+term+'"]').addClass('bg-success')
                                                            $('.input_grades[data-id="'+b+'"][data-term="'+term+'"]').removeClass('input_grades')
                                                            var temp_id = all_grades.findIndex(x=>x.id == b)

                                                            if(term == 'prelemgrade'){
                                                                  all_grades[temp_id].prelemstatus = 1
                                                            }else if(term == 'midtermgrade'){
                                                                  all_grades[temp_id].midtermstatus = 1
                                                            }else if(term == 'prefigrade'){
                                                                  all_grades[temp_id].prefistatus = 1
                                                            }else if(term == 'finalgrade'){
                                                                  all_grades[temp_id].finalstatus = 1
                                                            }
                                                            plot_subject_grades(all_grades)
                                                            
                                                      })
                                                }else{
                                                      Toast.fire({
                                                            type: 'error',
                                                            title: 'Something went wrong!'
                                                      })
                                                }
                                          },error:function(){
                                                Toast.fire({
                                                      type: 'error',
                                                      title: 'Something went wrong!'
                                                })
                                          }
                                    })
                              }
                        })

                  }

                  $(document).on('click','#save_grades',function() {

                        $('#save_grades').text('Saving Grades...')
                        $('#save_grades').removeClass('btn-primary')
                        $('#save_grades').addClass('btn-secondary')
                        $('#save_grades').attr('disabled','disabled')

                        if( $('.updated[data-term="prelemgrade"]').length == 0){
                              save_midterm()
                        }

                        $('.updated[data-term="prelemgrade"]').each(function(a,b){
                              var studid = $(this).attr('data-studid')
                              var term = $(this).attr('data-term')
                              var courseid = $(this).attr('data-course')
                              var sectionid = $(this).attr('data-section')
                              var pid = $(this).attr('data-pid')
                              var termgrade = $(this).text()
                              var td = $(this)
                              $.ajax({
                                    type:'POST',
                                    url: '/college/teacher/student/grades/save',
                                    data:{
                                          syid:$('#filter_sy').val(),
                                          semid:$('#filter_semester').val(),
                                          term:term,
                                          sectionid:sectionid,
                                          termgrade:termgrade,
                                          studid:studid,
                                          courseid:courseid,
                                          pid:pid,
                                    },
                                    success:function(data) {
                                         $(td).removeClass('updated')
                                         if($('.updated[data-term="prelemgrade"]').length == 0){
                                                save_midterm()
                                         }
                                    }
                              })
                        })


                  })

                  function save_midterm(){
                        if( $('.updated[data-term="midtermgrade"]').length == 0){
                              save_prefi()
                        }
                        $('.updated[data-term="midtermgrade"]').each(function(a,b){
                              var studid = $(this).attr('data-studid')
                              var term = $(this).attr('data-term')
                              var courseid = $(this).attr('data-course')
                              var sectionid = $(this).attr('data-section')
                              var pid = $(this).attr('data-pid')
                              var termgrade = $(this).text()
                              var td = $(this)
                              $.ajax({
                                    type:'POST',
                                    url: '/college/teacher/student/grades/save',
                                    data:{
                                          syid:$('#filter_sy').val(),
                                          semid:$('#filter_semester').val(),
                                          term:term,
                                          sectionid:sectionid,
                                          termgrade:termgrade,
                                          studid:studid,
                                          courseid:courseid,
                                          pid:pid,
                                    },
                                    success:function(data) {
                                         $(td).removeClass('updated')
                                         if($('.updated[data-term="midtermgrade"]').length == 0){
                                                save_prefi()
                                         }
                                    }
                              })
                        })

                  }

                  function save_prefi(){
                        if( $('.updated[data-term="prefigrade"]').length == 0){
                              save_final()
                        }
                        $('.updated[data-term="prefigrade"]').each(function(a,b){
                              var studid = $(this).attr('data-studid')
                              var term = $(this).attr('data-term')
                              var courseid = $(this).attr('data-course')
                              var sectionid = $(this).attr('data-section')
                              var pid = $(this).attr('data-pid')
                              var termgrade = $(this).text()
                              var td = $(this)
                              $.ajax({
                                    type:'POST',
                                    url: '/college/teacher/student/grades/save',
                                    data:{
                                          syid:$('#filter_sy').val(),
                                          semid:$('#filter_semester').val(),
                                          term:term,
                                          sectionid:sectionid,
                                          termgrade:termgrade,
                                          studid:studid,
                                          courseid:courseid,
                                          pid:pid,
                                    },
                                    success:function(data) {
                                         $(td).removeClass('updated')
                                         if($('.updated[data-term="prefigrade"]').length == 0){
                                                save_final()
                                         }
                                    }
                              })
                        })

                  }

                  function save_final(){
                        if( $('.updated[data-term="finalgrade"]').length == 0){
                              Toast.fire({
                                    type: 'success',
                                    title: 'Saved Successfully!'
                              })
                              $('#save_grades').attr('disabled','disabled')
                              $('#save_grades').removeClass('btn-secondary')
                              $('#save_grades').addClass('btn-primary')
                              $('#save_grades').text('Save Grades')
                              $('#grade_submit').removeAttr('disabled')

                              var temp_students = all_subject.filter(x=>x.schedid == schedid)
                              get_grades(schedid,false,temp_students[0].students)
                             
                        }
                        $('.updated[data-term="finalgrade"]').each(function(a,b){
                              var studid = $(this).attr('data-studid')
                              var term = $(this).attr('data-term')
                              var courseid = $(this).attr('data-course')
                              var sectionid = $(this).attr('data-section')
                              var pid = $(this).attr('data-pid')
                              var termgrade = $(this).text()
                              var td = $(this)
                              $.ajax({
                                    type:'POST',
                                    url: '/college/teacher/student/grades/save',
                                    data:{
                                          syid:$('#filter_sy').val(),
                                          semid:$('#filter_semester').val(),
                                          term:term,
                                          sectionid:sectionid,
                                          termgrade:termgrade,
                                          studid:studid,
                                          courseid:courseid,
                                          pid:pid,
                                    },
                                    success:function(data) {
                                         $(td).removeClass('updated')
                                         if($('.updated[data-term="finalgrade"]').length == 0){
                                                Toast.fire({
                                                      type: 'success',
                                                      title: 'Saved Successfully!'
                                                })
                                                $('#save_grades').attr('disabled','disabled')
                                                $('#save_grades').removeClass('btn-secondary')
                                                $('#save_grades').addClass('btn-primary')
                                                $('#save_grades').text('Save Grades')
                                                $('#grade_submit').removeAttr('disabled')
                                                var temp_students = all_subject.filter(x=>x.schedid == schedid)
                                                get_grades(schedid,false,temp_students[0].students)
                                                get_grades(schedid,false,temp_students[0].students)
                                         }
                                    }
                              })
                        })
                  }




                  var school = @json($schoolinfo);

                  // const Toast = Swal.mixin({
                  //       toast: true,
                  //       position: 'top-end',
                  //       showConfirmButton: false,
                  //       timer: 2000,
                  // })

                  $.ajaxSetup({
                        headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                  });

                  var all_subject = []
                  get_subjects()

                  var schedid = null;
                  $(document).on('click','.submit_grade',function(){
                        var temp_button = $(this)
                        temp_button.attr('disabled','disabled')
                        var term = $(this).attr('data-term')
                        $.ajax({
                              type:'POST',
                              url: '/college/teacher/student/grades/submit',
                              data:{
                                    schedid:schedid,
                                    term:term,
                              },
                              success:function(data) {
                                    if(data[0].status == 1){
                                          Toast.fire({
                                                type: 'success',
                                                title: 'Submitted Successfully!'
                                          })
                                          temp_button.removeAttr('disabled')
                                    }else{
                                          temp_button.removeAttr('disabled')
                                          Toast.fire({
                                                type: 'danger',
                                                title: 'Something went wrong!'
                                          })
                                    }
                              },
                              error:function(){
                                    temp_button.removeAttr('disabled')
                                    Toast.fire({
                                          type: 'danger',
                                          title: 'Something went wrong!'
                                    })
                              }
                        })
                  })


                  $(document).on('change','#filter_sy , #filter_semester',function (){
                        all_gradestatus = []
                        // datatable_3()
                        all_subject = []
                        datatable_1()
                        get_subjects()
                  })

                  $(document).on('change','#term',function (){
                        // datatable_3()
                        datatable_1()
                  })

                  function get_subjects() {
                        $.ajax({
                              type:'GET',
                              url: '/college/teacher/student/grades/subject',
                              data:{
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_semester').val(),
                                    teacherid:73
                              },
                              success:function(data) {
                                    if(data.length == 0){
                                          Toast.fire({
                                                type: 'warning',
                                                title: 'No records Found!'
                                          })
                                    }else{
                                          all_subject = data
                                          // get_enrolled()
                                          // grade_status()
                                          datatable_1()
                                    }
                              }
                        })
                  }

                  function get_enrolled(){
                        $.each(all_subject,function(a,b){
                              $.ajax({
                                    type:'GET',
                                    url: '/college/teacher/student/grades/students',
                                    data:{
                                          syid:$('#filter_sy').val(),
                                          semid:$('#filter_semester').val(),
                                          schedid:b.schedid,
                                          subjid:b.subjectID
                                    },
                                    success:function(data) {
                                          datatable_1()
                                    }
                              })
                        })
                  }


                  var all_gradestatus = []
                  function grade_status(){
                        // $.ajax({
                        //       type:'GET',
                        //       url: '/college/teacher/student/grades/status/get',
                        //       data:{
                        //             syid:$('#filter_sy').val(),
                        //             semid:$('#filter_semester').val(),
                        //       },
                        //       success:function(data) {
                        //             all_gradestatus = data
                        //             datatable_3()
                        //       }
                        // })


                  }

                  $(document).on('click','.view_students',function(){
                        $('#modal_1').modal()
                        temp_id = $(this).attr('data-id')
                        var students = all_subject.filter(x=>x.schedid == temp_id)
                        datatable_2(students[0].students)
                  })

                  $(document).on('click','.view_grades',function(){

                        $('#message_holder').text('')
                        $('#save_grades').attr('hidden','hidden')
                        $('#modal_2').modal()
                        temp_id = $(this).attr('data-id')
                        schedid = temp_id

                        $('.with_submission_info').remove()
                        $('.submit_grade').attr('hidden','hidden')
                       
                        var students = all_subject.filter(x=>x.schedid == temp_id)

                        $('#section')[0].innerHTML = '<a class="mb-0">'+students[0].sectionDesc+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+students[0].levelname.replace('COLLEGE','')+' - '+students[0].courseabrv+'</p>'

                        $('#subject')[0].innerHTML = '<a class="mb-0">'+students[0].subjDesc+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+students[0].subjCode+'</p>'

                        $('#student_list_grades').empty()
                        var female = 0;
                        var male = 0;
                        var count = 1;
                        var pid = students[0].pid
                        var sectionid = students[0].sectionID

                        if(students[0].students.length == 0){
                              Toast.fire({
                                    type: 'warning',
                                    title: 'No student Found!'
                              })
                              return false
                        }else{
                              $('#save_grades').removeAttr('hidden')
                        }

                        $('#datatable_4').empty()

                        $('.student_count').text(students[0].students.length)

                        $.each(students[0].students,function (a,b) {

                              var q1hidden = ''
                              var q2hidden = ''
                              var q3hidden = ''
                              var q4hidden = ''

                              if(school == 'spct'.toUpperCase()){
                                    q1hidden = 'hidden="hidden"'
                              }
                              else{

                                    var colspan = 7
                                    if(school == 'apmc'.toUpperCase()){
                                          colspan = 8
                                    }else if(school == 'gbbc'.toUpperCase()){
                                          colspan = 4
                                    }


                                    if(male == 0 && b.gender == 'MALE'){
                                          $('#student_list_grades').append('<tr class="bg-secondary"><th colspan="'+colspan+'">MALE</th></tr>')
                                          $('#datatable_4').append('<tr class="bg-secondary"><th colspan="4">MALE</th></tr>')
                                          male = 1
                                          count = 0
                                    }else if(female == 0 && b.gender == 'FEMALE'){
                                          $('#student_list_grades').append('<tr class="bg-secondary"><th colspan="'+colspan+'">FEMALE</th></tr>')
                                          $('#datatable_4').append('<tr class="bg-secondary"><th colspan="4">FEMALE</th></tr>')
                                          female = 1
                                          count = 0
                                    }

                              }

                              count += 1

                              if(!school == 'spct'.toUpperCase()){
                                    $('#student_list_grades').append('<tr><td class="text-center">'+count+'</td><td>'+b.student+'</td><td>'+b.courseabrv+'</td><td data-studid="'+b.studid+'" data-course="'+b.courseid+'" data-pid="'+pid+'" data-section="'+sectionid+'" class="grade_td" data-term="prelemgrade" '+q1hidden+'></td><td data-studid="'+b.studid+'" data-course="'+b.courseid+'" data-pid="'+pid+'" data-section="'+sectionid+'" class="grade_td" data-term="midtermgrade" '+q2hidden+'></td><td data-studid="'+b.studid+'" data-course="'+b.courseid+'" data-pid="'+pid+'" data-section="'+sectionid+'" class="grade_td" data-term="prefigrade" '+q3hidden+'></td><td data-studid="'+b.studid+'" data-course="'+b.courseid+'" data-pid="'+pid+'" data-section="'+sectionid+'" class="grade_td" data-term="finalgrade" '+q4hidden+'></td></tr>')
                              }else{

                                    var gradelevel = null
                                    if(b.levelid == 17){
                                          gradelevel = 1
                                    }else if(b.levelid == 18){
                                          gradelevel = 2
                                    }else if(b.levelid == 19){
                                          gradelevel = 3
                                    }else if(b.levelid == 20){
                                          gradelevel = 4
                                    }else if(b.levelid == 21){
                                          gradelevel = 5
                                    }

                                    if(school == 'apmc'.toUpperCase()){
                                          $('#student_list_grades').append('<tr><td class="text-center">'+count+'</td><td>'+b.student+'</td><td data-studid="'+b.studid+'" data-course="'+b.courseid+'" data-pid="'+pid+'" data-section="'+sectionid+'" class="grade_td" data-term="prelemgrade" '+q1hidden+'></td><td data-studid="'+b.studid+'" data-course="'+b.courseid+'" data-pid="'+pid+'" data-section="'+sectionid+'" class="grade_td" data-term="midtermgrade" '+q1hidden+'></td><td data-studid="'+b.studid+'" data-course="'+b.courseid+'" data-pid="'+pid+'" data-section="'+sectionid+'" class="grade_td" data-term="prefigrade" '+q2hidden+'></td><td data-studid="'+b.studid+'" data-course="'+b.courseid+'" data-pid="'+pid+'" data-section="'+sectionid+'" class="grade_td" data-term="finalgrade" '+q3hidden+'></td><th class="text-center align-middle" data-studid="'+b.studid+'" data-course="'+b.courseid+'" data-pid="'+pid+'" data-section="'+sectionid+'" data-term="fg" '+q1hidden+'></th><th class="text-center align-middle" data-studid="'+b.studid+'" data-course="'+b.courseid+'" data-pid="'+pid+'" data-section="'+sectionid+'" data-term="remarks" '+q1hidden+'></th></tr>')
                                    }else if(school == 'gbbc'.toUpperCase()){
                                          pid = b.pid
                                          sectionid = b.sectionid

                                          $('#student_list_grades').append('<tr><td class="text-center">'+count+'</td><td>'+b.student+'</td><td>'+b.courseabrv+' '+gradelevel+'</td><td data-studid="'+b.studid+'" data-course="'+b.courseid+'" data-pid="'+pid+'" data-section="'+sectionid+'" class="grade_td" data-term="finalgrade" '+q4hidden+'></td></tr>')
                                    }
                                    else{
                                          $('#student_list_grades').append('<tr><td class="text-center">'+count+'</td><td>'+b.student+'</td><td>'+b.courseabrv+' '+gradelevel+'</td><td data-studid="'+b.studid+'" data-course="'+b.courseid+'" data-pid="'+pid+'" data-section="'+sectionid+'" class="grade_td" data-term="prelemgrade" '+q1hidden+'></td><td data-studid="'+b.studid+'" data-course="'+b.courseid+'" data-pid="'+pid+'" data-section="'+sectionid+'" class="grade_td" data-term="midtermgrade" '+q2hidden+'></td><td data-studid="'+b.studid+'" data-course="'+b.courseid+'" data-pid="'+pid+'" data-section="'+sectionid+'" class="grade_td" data-term="prefigrade" '+q3hidden+'></td><td data-studid="'+b.studid+'" data-course="'+b.courseid+'" data-pid="'+pid+'" data-section="'+sectionid+'" class="grade_td" data-term="finalgrade" '+q4hidden+'></td></tr>')

                                    }

                              }

                              $('#datatable_4').append('<tr><td><input disabled checked="checked" type="checkbox" class="select" data-studid="'+b.studid+'"></td><td>'+b.sid+'</td><td>'+b.student+'</td><td data-studid="'+b.studid+'" class="grade_submission_student text-center"></td></tr>')
                        })

                        $('.grade_td').addClass('text-center')
                        $('.grade_td').addClass('align-middle')
                        get_grades(temp_id,true, students[0].students);

                  })


                  var all_grades = []

                  
                  var dean = @json($dean)

                  $('#printable_dean').select2({
                        'data':dean,
                        'placeholder':'Select Dean'
                  })

                  
                  $(document).on('click','#print_grades_to_modal',function(){
                        $('#dean_holder_modal').modal()
                  })


                  $(document).on('click','#print_grades',function(){
                        print_grades()
                  })

                  function print_grades() {
                       
                       var pid = []
                       var sectionid = []
                       var students = all_subject.filter(x=>x.schedid == schedid)[0].students
                       var temp_pid = [...new Map(students.map(item => [item['pid'], item])).values()]
                       var temp_sectionid = [...new Map(students.map(item => [item['sectionid'], item])).values()]

                       $.each(temp_pid,function(a,b){
                             pid.push(b.pid)
                       })
                       $.each(temp_sectionid,function(a,b){
                             sectionid.push(b.sectionid)
                       })

                       var temp_subjid = temp_pid[0].pid

                       var syid = $('#filter_sy').val()
                       var semid = $('#filter_semester').val()
                       var pid = pid
                       var sectionid = sectionid
                       var dean = $('#printable_dean').val()

                       window.open('/college/teacher/student/grades/print?&syid='+syid+'&semid='+semid+'&pid='+pid+'&sectionid='+sectionid+'&schedid='+schedid+'&subjid='+temp_subjid+'&dean='+dean, '_blank');

                 }

                  function get_grades(schedid, prompt = true, students) {

                        // var sched = all_subject.filter(x=>x.schedid == schedid)
                        // var pid = sched[0].pid
                        // var sectionid = sched[0].sectionID

                        // if(school == 'gbbc'.toUpperCase()){
                              var pid = []
                              var sectionid = []
                              var temp_pid = [...new Map(students.map(item => [item['pid'], item])).values()]
                              var temp_sectionid = [...new Map(students.map(item => [item['sectionid'], item])).values()]
                              $.each(temp_pid,function(a,b){
                                    pid.push(b.pid)
                              })
                              $.each(temp_sectionid,function(a,b){
                                    sectionid.push(b.sectionid)
                              })
                        // }

                        $('.p_count').text(0)
                        $('.f_count').text(0)
                        $('.ng_count').text(0)

                        $('.drop_count').text(0)
                        $('.inc_count').text(0)
                        $('.pen_count').text(0)
                        $('.sub_count').text(0)
                        $('.app_count').text(0)

                        $.ajax({
                              type:'GET',
                              url: '/college/teacher/student/grades/get',
                              data:{

                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_semester').val(),
                                    pid:pid,
                                    sectionid:sectionid
                              },
                              success:function(data) {

                                    $('.grade_td').addClass('input_grades')
                                    all_grades = data

                                    if(data.length == 0){
                                          // Toast.fire({
                                          //       type: 'warning',
                                          //       title: 'No grades found!'
                                          // })
                                          // $('#message_holder').text('No grades found. Please input student grades.')
                                    }else{

                                          $('.drop_count[data-stat="1"]').text(data.filter(x=>x.prelemstatus == 9).length)
                                          $('.drop_count[data-stat="2"]').text(data.filter(x=>x.midtermstatus == 9).length)
                                          $('.drop_count[data-stat="3"]').text(data.filter(x=>x.prefistatus == 9).length)
                                          $('.drop_count[data-stat="4"]').text(data.filter(x=>x.finalstatus == 9).length)

                                          $('.inc_count[data-stat="1"]').text(data.filter(x=>x.prelemstatus == 8).length)
                                          $('.inc_count[data-stat="2"]').text(data.filter(x=>x.midtermstatus == 8).length)
                                          $('.inc_count[data-stat="3"]').text(data.filter(x=>x.prefistatus == 8).length)
                                          $('.inc_count[data-stat="4"]').text(data.filter(x=>x.finalstatus == 8).length)

                                          $('.pen_count[data-stat="1"]').text(data.filter(x=>x.prelemstatus == 3).length)
                                          $('.pen_count[data-stat="2"]').text(data.filter(x=>x.midtermstatus == 3).length)
                                          $('.pen_count[data-stat="3"]').text(data.filter(x=>x.prefistatus == 3).length)
                                          $('.pen_count[data-stat="4"]').text(data.filter(x=>x.finalstatus == 3).length)

                                          $('.sub_count[data-stat="1"]').text(data.filter(x=>x.prelemstatus == 1).length)
                                          $('.sub_count[data-stat="2"]').text(data.filter(x=>x.midtermstatus == 1).length)
                                          $('.sub_count[data-stat="3"]').text(data.filter(x=>x.prefistatus == 1).length)
                                          $('.sub_count[data-stat="4"]').text(data.filter(x=>x.finalstatus == 1).length)

                                          $('.app_count[data-stat="1"]').text(data.filter(x=>x.prelemstatus == 2 || x.prelemstatus == 7).length)
                                          $('.app_count[data-stat="2"]').text(data.filter(x=>x.midtermstatus == 2  || x.midtermstatus == 7).length)
                                          $('.app_count[data-stat="3"]').text(data.filter(x=>x.prefistatus == 2  || x.prefistatus == 7).length)
                                          $('.app_count[data-stat="4"]').text(data.filter(x=>x.finalstatus == 2  || x.finalstatus == 7).length)


                                          $('.p_count[data-stat="1"]').text(data.filter(x=>x.prelemgrade != null && x.prelemgrade >= 75).length)
                                          $('.p_count[data-stat="2"]').text(data.filter(x=>x.midtermgrade != null && x.midtermgrade >= 75).length)
                                          $('.p_count[data-stat="3"]').text(data.filter(x=>x.prefigrade != null && x.prefigrade >= 75).length)
                                          $('.p_count[data-stat="4"]').text(data.filter(x=>x.finalgrade != null && x.finalgrade >= 75).length)

                                          $('.f_count[data-stat="1"]').text(data.filter(x=>x.prelemgrade != null && x.prelemgrade < 75).length)
                                          $('.f_count[data-stat="2"]').text(data.filter(x=>x.midtermgrade != null && x.midtermgrade < 75).length)
                                          $('.f_count[data-stat="3"]').text(data.filter(x=>x.prefigrade != null && x.prefigrade < 75).length)
                                          $('.f_count[data-stat="4"]').text(data.filter(x=>x.finalgrade != null && x.finalgrade < 75).length)

                                          if(school == 'spct'.toUpperCase()){
                                                $('.ng_count[data-stat="2"]').text(parseInt($('.student_count[data-stat="2"]').text()) - ( parseInt($('.p_count[data-stat="2"]').text()) + parseInt($('.f_count[data-stat="2"]').text()) ))
                                                $('.ng_count[data-stat="3"]').text(parseInt($('.student_count[data-stat="2"]').text()) - ( parseInt($('.p_count[data-stat="3"]').text()) + parseInt($('.f_count[data-stat="3"]').text()) ))
                                                $('.ng_count[data-stat="4"]').text(parseInt($('.student_count[data-stat="2"]').text()) - ( parseInt($('.p_count[data-stat="4"]').text()) + parseInt($('.f_count[data-stat="4"]').text()) ))
                                          }
                                          else{
                                                $('.ng_count[data-stat="1"]').text(parseInt($('.student_count[data-stat="1"]').text()) - ( parseInt($('.p_count[data-stat="1"]').text()) + parseInt($('.f_count[data-stat="1"]').text()) )) 
                                                $('.ng_count[data-stat="2"]').text(parseInt($('.student_count[data-stat="1"]').text()) - ( parseInt($('.p_count[data-stat="2"]').text()) + parseInt($('.f_count[data-stat="2"]').text()) ))
                                                $('.ng_count[data-stat="3"]').text(parseInt($('.student_count[data-stat="1"]').text()) - ( parseInt($('.p_count[data-stat="3"]').text()) + parseInt($('.f_count[data-stat="3"]').text()) ))
                                                $('.ng_count[data-stat="4"]').text(parseInt($('.student_count[data-stat="1"]').text()) - ( parseInt($('.p_count[data-stat="4"]').text()) + parseInt($('.f_count[data-stat="4"]').text()) ))
                                          }
                                          
                                         

                                          // $('.uns_count[data-stat="1"]').text($('.input_grades[data-status="null"][data-term="prelemgrade"]').length)
                                          // $('.uns_count[data-stat="2"]').text($('.input_grades[data-status="null"][data-term="midtermgrade"]').length)
                                          // $('.uns_count[data-stat="3"]').text($('.input_grades[data-status="null"][data-term="prefigrade"]').length)
                                          // $('.uns_count[data-stat="4"]').text($('.input_grades[data-status="null"][data-term="finalgrade"]').length)


                                          plot_subject_grades(data)
                                          if(prompt){
                                                Toast.fire({
                                                      type: 'success',
                                                      title: 'Grades found!'
                                                })
                                                $('#message_holder').text('Grades found.')
                                          }

                                         
                                    }

                              },
                              error:function(){
                                    Toast.fire({
                                          type: 'error',
                                          title: 'Something went wrong!'
                                    })
                                    $('#message_holder').text('Unable to load grades.')
                              }
                        })
                  }

                  function plot_subject_grades(data){

                        $.each(data,function(a,b){
                                                
                              var q1status = 'input_grades'
                              var q2status = 'input_grades'
                              var q3status = 'input_grades'
                              var q4status = 'input_grades'

                              if(school == 'spct'.toUpperCase()){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prelemgrade"]').text(b.prelemgrade != null ? parseFloat(b.prelemgrade).toFixed() : '')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="midtermgrade"]').text(b.midtermgrade != null ? parseFloat(b.midtermgrade).toFixed() : '')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prefigrade"]').text(b.prefigrade != null ? parseFloat(b.prefigrade).toFixed() : '')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="finalgrade"]').text(b.finalgrade != null ? parseFloat(b.finalgrade).toFixed() : '')
                              }else{
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prelemgrade"]').text(b.prelemgrade != null ? parseFloat(b.prelemgrade).toFixed(2) : '')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="midtermgrade"]').text(b.midtermgrade != null ? parseFloat(b.midtermgrade).toFixed(2) : '')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prefigrade"]').text(b.prefigrade != null ? parseFloat(b.prefigrade).toFixed(2) : '')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="finalgrade"]').text(b.finalgrade != null ? parseFloat(b.finalgrade).toFixed(2) : '')
                              }

                              $('.input_grades[data-studid="'+b.studid+'"][data-term="prelemgrade"]').attr('data-id',b.id)
                              $('.input_grades[data-studid="'+b.studid+'"][data-term="midtermgrade"]').attr('data-id',b.id)
                              $('.input_grades[data-studid="'+b.studid+'"][data-term="prefigrade"]').attr('data-id',b.id)
                              $('.input_grades[data-studid="'+b.studid+'"][data-term="finalgrade"]').attr('data-id',b.id)

                              $('.input_grades[data-studid="'+b.studid+'"][data-term="prelemgrade"]').attr('data-status',b.prelemstatus)
                              $('.input_grades[data-studid="'+b.studid+'"][data-term="midtermgrade"]').attr('data-status',b.midtermstatus)
                              $('.input_grades[data-studid="'+b.studid+'"][data-term="prefigrade"]').attr('data-status',b.prefistatus)
                              $('.input_grades[data-studid="'+b.studid+'"][data-term="finalgrade"]').attr('data-status',b.finalstatus)

                              if(b.prelemstatus == 1){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prelemgrade"]').addClass('bg-success')
                              }else if(b.prelemstatus == 7){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prelemgrade"]').addClass('bg-primary')
                              }else if(b.prelemstatus == 9){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prelemgrade"]').addClass('bg-danger')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prelemgrade"]').text('DROPPED')
                              }else if(b.prelemstatus == 8){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prelemgrade"]').addClass('bg-warning')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prelemgrade"]').text('INC')
                              }else if(b.prelemstatus == 3){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prelemgrade"]').addClass('bg-warning')
                              }else if(b.prelemstatus == 4){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prelemgrade"]').addClass('bg-info')
                              }else if(b.prelemstatus == 2){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prelemgrade"]').addClass('bg-secondary')
                              }else{
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prelemgrade"]').removeAttr('class')
                                    $('td[data-studid="'+b.studid+'"][data-term="prelemgrade"]').addClass('grade_td text-center align-middle input_grades')
                              }

                              if(b.midtermstatus == 1){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="midtermgrade"]').addClass('bg-success')
                              }else if(b.midtermstatus == 7){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="midtermgrade"]').addClass('bg-primary')
                              }else if(b.midtermstatus == 9){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="midtermgrade"]').addClass('bg-danger')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="midtermgrade"]').text('DROPPED')
                              }else if(b.midtermstatus == 8){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="midtermgrade"]').addClass('bg-warning')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="midtermgrade"]').text('INC')
                              }else if(b.midtermstatus == 4){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="midtermgrade"]').addClass('bg-info')
                              }else if(b.midtermstatus == 3){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="midtermgrade"]').addClass('bg-warning')
                              }else if(b.midtermstatus == 2){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="midtermgrade"]').addClass('bg-secondary')
                              }else{
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="midtermgrade"]').removeAttr('class')
                                    $('td[data-studid="'+b.studid+'"][data-term="midtermgrade"]').addClass('grade_td text-center align-middle input_grades')
                              }

                              if(b.prefistatus == 1){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prefigrade"]').addClass('bg-success')
                              }else if(b.prefistatus == 7){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prefigrade"]').addClass('bg-primary')
                              }else if(b.prefistatus == 4){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prefigrade"]').addClass('bg-info')
                              }else if(b.prefistatus == 9){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prefigrade"]').addClass('bg-danger')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prefigrade"]').text('DROPPED')
                              }else if(b.prefistatus == 8){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prefigrade"]').addClass('bg-warning')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prefigrade"]').text('INC')
                              }else if(b.prefistatus == 3){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prefigrade"]').addClass('bg-warning')
                              }else if(b.prefistatus == 2){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prefigrade"]').addClass('bg-secondary')
                              }else{
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prefigrade"]').removeAttr('class')
                                    $('td[data-studid="'+b.studid+'"][data-term="prefigrade"]').addClass('grade_td text-center align-middle input_grades')
                              }

                              if(b.finalstatus == 1){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="finalgrade"]').addClass('bg-success')
                              }else if(b.finalstatus == 7){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="finalgrade"]').addClass('bg-primary')
                              }else if(b.finalstatus == 9){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="finalgrade"]').addClass('bg-danger')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="finalgrade"]').text('DROPPED')
                              }else if(b.finalstatus == 8){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="finalgrade"]').addClass('bg-warning')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="finalgrade"]').text('INC')
                              }else if(b.finalstatus == 4){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="finalgrade"]').addClass('bg-info')
                              }else if(b.finalstatus == 3){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="finalgrade"]').addClass('bg-warning')
                              }else if(b.finalstatus == 2){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="finalgrade"]').addClass('bg-secondary')
                              }else{
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="finalgrade"]').removeAttr('class')
                                    if(school == 'spct'.toUpperCase()){
                                          $('th[data-studid="'+b.studid+'"][data-term="finalgrade"]').addClass('grade_td text-center align-middle input_grades')
                                    }else{
                                          $('td[data-studid="'+b.studid+'"][data-term="finalgrade"]').addClass('grade_td text-center align-middle input_grades')
                                    }
                              }

                              if(b.prelemstatus == 1 || b.prelemstatus == 7 || b.prelemstatus == 2 || b.prelemstatus == 4){
                                    $('.select[data-studid="'+b.studid+'"][data-term="prelemgrade"]').attr('data-status',b.prelemstatus)
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prelemgrade"]').removeClass('input_grades')
                              }

                              if(b.midtermstatus == 1 || b.midtermstatus == 7 || b.midtermstatus == 2 || b.midtermstatus == 4){
                                    $('.select[data-studid="'+b.studid+'"][data-term="prelemgrade"]').attr('disabled','disabled')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="midtermgrade"]').removeClass('input_grades')
                              }

                              if(b.prefistatus == 1 || b.prefistatus == 7 || b.prefistatus == 2 || b.prefistatus == 4){
                                    $('.select[data-studid="'+b.studid+'"][data-term="prefigrade"]').attr('disabled','disabled')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="prefigrade"]').removeClass('input_grades')
                              }

                              if(b.finalstatus == 1 || b.finalstatus == 7 || b.finalstatus == 2 || b.finalstatus == 4){
                                    $('.select[data-studid="'+b.studid+'"][data-term="finalgrade"]').attr('disabled','disabled')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="finalgrade"]').removeClass('input_grades')
                              }

                              var temp_studid = b.studid
                              var average = parseFloat( ( parseFloat ( $('.grade_td[data-studid="'+temp_studid+'"][data-term="prelemgrade"]').text()) + parseFloat($('.grade_td[data-studid="'+temp_studid+'"][data-term="midtermgrade"]').text() )  + parseFloat($('.grade_td[data-studid="'+temp_studid+'"][data-term="prefigrade"]').text() )  + parseFloat( $('.grade_td[data-studid="'+temp_studid+'"][data-term="finalgrade"]').text() ) ) / 4 ).toFixed(2)

                              if(average != 'NaN'){
                                    $('th[data-studid="'+temp_studid+'"][data-term="fg"]').text(average)

                                    if(average >= 3){
                                          $('th[data-studid="'+temp_studid+'"][data-term="remarks"]').text('FAILED')
                                    }else{
                                          $('th[data-studid="'+temp_studid+'"][data-term="remarks"]').text('PASSED')
                                    }
                              }else{
                                    $('th[data-studid="'+temp_studid+'"][data-term="fg"]').text('')
                                    $('th[data-studid="'+temp_studid+'"][data-term="remarks"]').text('')
                              }
                        })

                  }

                  // function plot_grade_status(schedid){
                  //       $('.submit_grade').attr('data-id',schedid)
                  //       $('.grades_status_holder').addClass('text-center')
                  //       $('.grades_status_holder').addClass('align-middle')
                  //       $('.with_submission_info').remove()
                  //       $('.submit_grade').removeAttr('hidden')
                  //       temp_gradestatus = all_gradestatus.filter(x=>x.schedid == schedid)[0].gradestatus
                  //       $.each(temp_gradestatus,function(a,b){
                  //             if(b.prelimstatus != null ){
                  //                   $('.input_grades[data-term="prelemgrade"]').removeClass('input_grades')
                  //                   $('.submit_grade[data-term="prelimstatus"]').attr('hidden','hidden')
                  //                   $('.grades_status_holder[data-term="prelimstatus"]').append('<span class="with_submission_info"><a class="mb-0">Submitted</a><p class="text-muted mb-0" style="font-size:.7rem">'+b.prelimdate+'</p></span>')
                  //             }
                  //             if(b.midtermstatus != null){
                  //                   $('.input_grades[data-term="midtermgrade"]').removeClass('input_grades')
                  //                   $('.submit_grade[data-term="midtermstatus"]').attr('hidden','hidden')
                  //                   $('.grades_status_holder[data-term="midtermstatus"]').append('<span class="with_submission_info"><a class="mb-0">Submitted</a><p class="text-muted mb-0" style="font-size:.7rem">'+b.midtermdate+'</p></span>')
                  //             }
                  //             if(b.prefistatus != null){
                  //                   $('.input_grades[data-term="prefigrade"]').removeClass('input_grades')
                  //                   $('.submit_grade[data-term="prefistatus"]').attr('hidden','hidden')
                  //                   $('.grades_status_holder[data-term="prefistatus"]').append('<span class="with_submission_info"><a class="mb-0">Submitted</a><p class="text-muted mb-0" style="font-size:.7rem">'+b.prefidate+'</p></span>')
                  //             }
                  //             if(b.finalstatus != null){
                  //                   $('.input_grades[data-term="finalgrade"]').removeClass('input_grades')
                  //                   $('.submit_grade[data-term="finalstatus"]').attr('hidden','hidden')
                  //                   $('.grades_status_holder[data-term="finalstatus"]').append('<span class="with_submission_info"><a class="mb-0">Submitted</a><p class="text-muted mb-0" style="font-size:.7rem">'+b.finaldate+'</p></span>')
                  //             }
                  //       })
                  // }

                  
                  $(document).on('click','.view_students',function(){
                        $('#modal_1').modal()
                        temp_id = $(this).attr('data-id')
                        var students = all_subject.filter(x=>x.schedid == temp_id)
                        datatable_2(students[0].students)
                  })


                  function datatable_2(students){

                        $("#datatable_2").DataTable({
                              destroy: true,
                              data:students,
                              lengthChange: false,
                              autoWidth: false,
                              columns: [
                                    { "data": "search"},
                                    { "data": "levelname"},
                                    { "data": "courseabrv"},
                                    { "data": "gender"},
                              ],
                              columnDefs: [
                                    {
                                          'targets': 0,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td)[0].innerHTML =  rowData.student
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 1,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td)[0].innerHTML =  rowData.levelname.replace('COLLEGE','')
                                                $(td).addClass('align-middle')
                                          }
                                    },
                              ]
                        })

                  }



                  function datatable_1(){

                        // var all_data = all_subject
                        // if($('#term').val() != ""){
                        //       if($('#term').val() == "Whole Sem"){
                        //             all_data = all_subject.filter(x=>x.schedotherclass == null)
                        //       }else{
                        //             all_data = all_subject.filter(x=>x.schedotherclass == $('#term').val())
                        //       }
                        // }
                        if(school == 'sait'.toUpperCase()){
                              var temp_subjects = all_subject
                        }else{
                              var temp_subjects = all_subject
                        }
                   

                        $("#datatable_1").DataTable({
                              destroy: true,
                              data:temp_subjects,
                              lengthChange: false,
                              scrollX: true,
                              autoWidth: false,
                              columns: [
                                    { "data": "sectionDesc"},
                                    { "data": "subjDesc" },
                                    { "data": null }
                              ],
                              columnDefs: [
                                    {
                                          'targets': 0,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {

                                                if(school == 'spct'.toUpperCase() || school == 'gbbc'.toUpperCase()){
                                                      var text = rowData.subjCode
                                                }else{
                                                      if(rowData.levelname == undefined){
                                                            rowData.levelname = 'COLLEGE';
                                                      }
                                                      var text = '<a class="mb-0">'+rowData.sectionDesc+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.levelname.replace('COLLEGE','')+' - '+rowData.courseabrv+'</p>';
                                                }
                                                $(td)[0].innerHTML =  text
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 1,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                
                                                var schedotherclass = ''
                                                if(school == 'spct'.toUpperCase() || school == 'gbbc'.toUpperCase()){
                                                      var text = rowData.subjDesc
                                                }else{
                                                      var text = '<a class="mb-0">'+rowData.subjDesc+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.subjCode+' - <i class="mb-0 text-danger" style="font-size:.7rem">'+schedotherclass+'</i></p>';
                                                }

                                                $(td)[0].innerHTML =  text
                                                $(td).addClass('align-middle')

                                               
                                          }
                                    },
                                    {
                                          'targets': 2,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var buttons = '<button class="btn btn-sm btn-primary mr-1 view_students" data-id="'+rowData.schedid+'"><i class="fas fa-user-circle"></i> Students <i>('+rowData.students.length+')</i></button>'

                                                buttons += '<button class="btn btn-sm btn-secondary mr-1 view_grades" data-id="'+rowData.schedid+'"><i class="fas fa-chart-bar"></i> Grades</button>'
                                                $(td)[0].innerHTML = buttons
                                                $(td).addClass('text-right')
                                                $(td).addClass('align-middle')
                                                
                                          }
                                    }

                              ]
                        })
                  }

                  function datatable_3(){

                        var all_data = all_gradestatus
                        if($('#term').val() != ""){
                              if($('#term').val() == "Whole Sem"){
                                    all_data = all_gradestatus.filter(x=>x.schedotherclass == null)
                              }else{
                                    all_data = all_gradestatus.filter(x=>x.schedotherclass == $('#term').val())
                              }
                        }

                        $("#datatable_3").DataTable({
                              destroy: true,
                              data:all_data,
                              lengthChange: false,
                              scrollX: true,
                              autoWidth: false,
                              columns: [
                                    { "data": "sectionDesc"},
                                    { "data": "subjDesc" },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null }
                              ],
                              columnDefs: [
                                    {
                                          'targets': 0,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var text = '<a class="mb-0">'+rowData.sectionDesc+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.levelname.replace('COLLEGE','')+' - '+rowData.courseabrv+'</p>';
                                                $(td)[0].innerHTML =  text
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 1,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {

                                                // var schedotherclass = rowData.schedotherclass != null ? rowData.schedotherclass : 'Whole Semester'
                                                var schedotherclass = ''

                                                var text = '<a class="mb-0">'+rowData.subjDesc+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.subjCode+' - <i class="mb-0 text-danger" style="font-size:.7rem">'+schedotherclass+'</i></p>';
                                                $(td)[0].innerHTML =  text
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 2,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                if(rowData.gradestatus.length == 0){
                                                      var text = '<a class="mb-0">Not Submitted</a>';
                                                }else{
                                                      var status = ''
                                                      if(rowData.gradestatus[0].prelimstatus == null){
                                                            status = 'Not Submitted'
                                                      }else if(rowData.gradestatus[0].prelimstatus == 1){
                                                            status = 'Submitted'
                                                      }
                                                      var text = '<a class="mb-0">'+status+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.gradestatus[0].prelimdate+'</p>';
                                                }
                                             
                                                $(td)[0].innerHTML =  text
                                                $(td).addClass('align-middle')
                                                $(td).addClass('text-center')
                                                if(school == 'spct'.toUpperCase()){
                                                      $(td).attr('hidden','hidden')
                                                }
                                          }
                                    },
                                    {
                                          'targets': 3,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                if(rowData.gradestatus.length == 0){
                                                      var text = '<a class="mb-0">Not Submitted</a>';
                                                }else{
                                                      var status = ''
                                                      if(rowData.gradestatus[0].midtermstatus == null){
                                                            status = 'Not Submitted'
                                                      }else if(rowData.gradestatus[0].midtermstatus == 1){
                                                            status = 'Submitted'
                                                      }
                                                      var text = '<a class="mb-0">'+status+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.gradestatus[0].midtermdate+'</p>';
                                                }
                                                
                                                $(td)[0].innerHTML =  text
                                                $(td).addClass('align-middle')
                                                $(td).addClass('text-center')
                                                if(school == 'spct'.toUpperCase()){
                                                      $(td).attr('hidden','hidden')
                                                }
                                          }
                                    },
                                    {
                                          'targets': 4,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                if(rowData.gradestatus.length == 0){
                                                      var text = '<a class="mb-0">Not Submitted</a>';
                                                }else{
                                                      var status = ''
                                                      if(rowData.gradestatus[0].prefistatus == null){
                                                            status = 'Not Submitted'
                                                      }else if(rowData.gradestatus[0].prefistatus == 1){
                                                            status = 'Submitted'
                                                      }
                                                      var text = '<a class="mb-0">'+status+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.gradestatus[0].prefidate+'</p>';
                                                }
                                               
                                                $(td)[0].innerHTML =  text
                                                $(td).addClass('align-middle')
                                                $(td).addClass('text-center')
                                          }
                                    },
                                    {
                                          'targets': 5,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                if(rowData.gradestatus.length == 0){
                                                      var text = '<a class="mb-0">Not Submitted</a>';
                                                }else{
                                                      var status = ''
                                                      if(rowData.gradestatus[0].finalstatus == null){
                                                            status = 'Not Submitted'
                                                      }else if(rowData.gradestatus[0].finalstatus == 1){
                                                            status = 'Submitted'
                                                      }
                                                      var text = '<a class="mb-0">'+status+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.gradestatus[0].finaldate+'</p>';
                                                }
                                                $(td)[0].innerHTML =  text
                                                $(td).addClass('align-middle')
                                                $(td).addClass('text-center')
                                          }
                                    }

                              ]
                        })
                  }
            })
      </script>
@endsection

