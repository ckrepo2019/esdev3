@php
      if(auth()->user()->type == 17){
            $extend = 'superadmin.layouts.app2';
      }else if(auth()->user()->type == 3 || Session::get('currentPortal') == 3){
            $extend = 'registrar.layouts.app';
      }
@endphp


@extends($extend)

@section('pagespecificscripts')
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
      <style>
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                  margin-top: -9px;
            }
            .shadow {
                  box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
                  border: 0 !important;
            }
            input[type=search]{
                  height: calc(1.7em + 2px) !important;
            }
            select[name=students_male_length]{
                  height: calc(1.7em + 2px) !important;
            }
            select[name=students_female_length]{
                  height: calc(1.7em + 2px) !important;
            }
            .custom-select-sm {
                  padding-top: 0.1rem;
            }
            .page-link {
                  line-height: .6;
                  font-size: .7rem !important;
            }
            div.dataTables_wrapper div.dataTables_info {
                  padding-top: 0.4em;
                  white-space: nowrap;
                  font-size: .7rem !important;
            }
      </style>
@endsection


@section('content')

@php
      $sy = DB::table('sy')->orderBy('sydesc')->get(); 
      $semester = DB::table('semester')->get(); 
      // $gradelevel = DB::table('gradelevel')->where('deleted',0)->orderBy('sortid')->get(); 
      $active_sy = DB::table('sy')->where('isactive',1)->first()->id;

      if(auth()->user()->type == 17){
            $acadprog = DB::table('academicprogram')
                                    ->select('id')
                                    ->get();
      }
      else{

            $teacherid = DB::table('teacher')
                              ->where('tid',auth()->user()->email)
                              ->select('id')
                              ->first()
                              ->id;

            if(auth()->user()->type == 2 || Session::get('currentPortal') == 2){

                  $acadprog = DB::table('academicprogram')
                                    ->where('principalid',$teacherid)
                                    ->get();

            }else{

                  $acadprog = DB::table('teacheracadprog')
                              ->where('teacherid',$teacherid)
                              ->where('syid',$active_sy)
                              ->whereIn('acadprogutype',[3,8])
                              ->where('deleted',0)
                              ->select('acadprogid as id')
                              ->distinct('acadprogid')
                              ->get();
            }
      }


      $acadprog_list = array();
      foreach($acadprog as $item){
            array_push($acadprog_list,$item->id);
      }

      $gradelevel = DB::table('gradelevel')
                              ->where('deleted',0)
                              ->whereIn('acadprogid',$acadprog_list)
                              ->orderBy('sortid')
                              ->select(
                                    'id',
                                    'levelname as text',
                                    'levelname'
                              )
                              ->get();

@endphp


<div class="modal fade" id="subjectplot_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-sm">
            <div class="modal-content">
                  <div class="modal-body">
                    
                  </div>
                  <div class="modal-footer border-0">
                        <div class="col-md-6">
                              <button class="btn btn-success btn-sm" id="subjectplot_to_create"><i class="fas fa-plus"></i> Add</button>
                        </div>
                        <div class="col-md-6 text-right">
                              <button class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                        </div>
                  </div>
            </div>
      </div>
</div>   

<section class="content-header">
      <div class="container-fluid">
            <div class="row mb-2">
                  <div class="col-sm-6">
                        <h1>Student Promotion</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Student Promotion</li>
                  </ol>
                  </div>
            </div>
      </div>
</section>
    
<section class="content pt-0">
    
      <div class="container-fluid">
            <div class="row">
                  <div class="col-md-8">
                        <div class="info-box shadow-lg">
                          <div class="info-box-content">
                              <div class="row">
                                    <div class="col-md-4 form-group">
                                          <label for="">School Year</label>
                                          <select class="form-control select2" id="filter_syid">
                                                @foreach ($sy as $item)
                                                      @if($item->isactive == 1)
                                                            <option value="{{$item->id}}" selected="selected">{{$item->sydesc}}</option>
                                                      @else
                                                            <option value="{{$item->id}}">{{$item->sydesc}}</option>
                                                      @endif
                                                @endforeach
                                          </select>
                                    </div>
                                    <div class="col-md-4 form-group">
                                          <label for="">Grade Level</label>
                                          <select class="form-control select2" id="filter_gradelevel">
                                                <option value="">Select Grade Level</option>
                                                @foreach ($gradelevel as $item)
                                                      <option value="{{$item->id}}">{{$item->levelname}}</option>
                                                @endforeach
                                          </select>
                                    </div>
                                    <div class="col-md-4 form-group" id="semester_holder" hidden>
                                          <label for="">Semester</label>
                                          <select class="form-control select2" id="filter_semid" disabled>
                                                @foreach ($semester as $item)
                                                      <option value="{{$item->id}}">{{$item->semester}}</option>
                                                @endforeach
                                          </select>
                                    </div>
                                  
                              </div>
                              <div class="row for-ibed" hidden>
                                    <div class="col-md-12">
                                          <div class="row">
                                                <div class="col-md-4 form-group clearfix">
                                                      <div class="icheck-primary d-inline">
                                                            <input type="radio" id="withoutg" name="condition" checked value="2">
                                                            <label for="withoutg">
                                                                  Exclude Grades
                                                            </label>
                                                      </div>
                                                </div>
                                                <div class="col-md-4 form-group clearfix">
                                                      <div class="icheck-primary d-inline">
                                                            <input type="radio" id="withg" name="condition" value="1">
                                                            <label for="withg">
                                                                  Include Grades
                                                            </label>
                                                      </div>
                                                </div>
                                                <div class="col-md-4 form-group auto_enroll_holder" hidden>
                                                      <div class="icheck-primary d-inline pt-2">
                                                          <input type="checkbox" id="aut_enroll" >
                                                          <label for="aut_enroll">Auto Enroll
                                                          </label>
                                                      </div>
                                                </div>
                                               
                                          </div>
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="col-md-4 ">
                                          <button class="btn btn-primary btn-sm" id="button_filter"><i class="fas fa-filter"></i> Filter</button>
                                    </div>
                                    <div class="col-md-8 text-right">
                                          <button class="btn btn-danger btn-sm" id="button_unpromote" disabled><i class="fas fa-caret-square-up" ></i> Unpromote All</button>
                                          <button class="btn btn-primary btn-sm" id="button_promote" disabled><i class="fas fa-caret-square-up" ></i> Promote All</button>
                                    </div>
                              </div>
                          </div>
                        </div>
                  </div>
                  <div class="col-md-4 for-ibed" hidden>
                        <div class="row">
                              <div class="col-md-12">
                                    <div class="card">
                                          <div class="card-body p-2">
                                                <table class="table table-sm table-bordered" style="font-size: .7rem !important">
                                                      <thead>
                                                            <tr>
                                                                  <th colspan="2">System Promotion Status</th>
                                                            </tr>
                                                      </thead>
                                                      <tbody>
                                                            <tr>
                                                                  <th width="50%"><b>Promoted to Next Grade Level</b></th>
                                                                  <td id="total_promoted" width="50%"></td>
                                                            </tr>
                                                            <tr>
                                                                  <th ><b>Retained from Grade Level</b></th>
                                                                  <td id="total_unpromoted"></td>
                                                            </tr>
                                                      </tbody>
                                                </table>
                                          </div>
                                    </div>
                              </div>
                              
                        </div>
                        <div class="row">
                              <div class="col-md-12">
                                    <div class="card shadow" style="font-size: .7rem !important">
                                          <div class="card-body p-2">
                                                <label class="mb-0">Note:</label>
                                                <p class="mb-0 pl-1"><i>*Click the student name to the student Report Card.</i></p>
                                          </div>
                                    </div>
                              </div>
                              {{-- <div class="col-md-6">
                                    <div class="info-box shadow-lg">
                                    <span class="info-box-icon bg-danger"><i class="fas fa-caret-square-down"></i></span>
                                    <div class="info-box-content">
                                    <span class="info-box-text">Unpromoted</span>
                                    <span class="info-box-number" id="total_unpromoted">0</span>
                                    </div>
                                    </div>
                              </div>
                              <div class="col-md-6">
                                    <div class="info-box shadow-lg">
                                    <span class="info-box-icon bg-success"><i class="fas fa-caret-square-up"></i></span>
                                    <div class="info-box-content">
                                    <span class="info-box-text">Promoted</span>
                                    <span class="info-box-number" id="total_promoted">0</span>
                                    </div>
                                    </div>
                              </div> --}}
                        </div>
                        {{-- <div class="row">
                              <div class="col-md-12">
                                    <div class="card shadow">
                                          <div class="card-body p-1">
                                                <div class="row">
                                                      <div class="col-md-12 text-right">
                                                            <button class="btn btn-primary btn-sm" id="button_promote" disabled><i class="fas fa-caret-square-up" ></i> Promote All Student</button>
                                                      </div>
                                                </div>
                                          </div>
                                    </div>
                              </div>
                        </div> --}}
                  </div>
            </div>
          
            <div class="row ">
                  <div class="col-md-8">
                        
                        <div class="row">
                              <div class="col-md-12">
                                    <div class="card shadow">
                                          <div class="card-body pace-primary p-2">
                                                <div class="row">
                                                      <div class="col-md-12">
                                                            <label for="">Male</label>
                                                      </div>
                                                </div>
                                                <div class="row">
                                                      <div class="col-md-12">
                                                            <table class="table table-striped table-bordered table-sm p-0" id="students_male" width="100%" style="font-size:.8rem !important">
                                                                  <thead>
                                                                        <tr> 
                                                                              <th width="10%" class="align-middle text-center">Sys. Status</th>
                                                                              <th width="10%" class=" align-middle">LRN</th>
                                                                              <th width="30%" class="align-middle">Student Name</th>
                                                                              <th width="8%" class="text-center align-middle tbh0  p-0" style="font-size: .7rem !important">Grade</th>
                                                                              <th width="12%" style="font-size: .5rem !important" class="text-center align-middle tbh1"></th>
                                                                              <th style="font-size: .5rem !important" width="18%" class="text-center align-middle tbh2"></th>
                                                                              <th style="font-size: .5rem !important " width="12%" class="text-center align-middle tbh3 is_senior_high" hidden></th>
                                                                        </tr>
                                                                  </thead>
                                                            </table>
                                                      </div>
                                                </div>
                                          </div>
                                    </div>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12">
                                    <div class="card shadow">
                                          <div class="card-body pace-primary p-2">
                                                <div class="row">
                                                      <div class="col-md-12">
                                                            <label for="">Female</label>
                                                      </div>
                                                </div>
                                                <div class="row">
                                                      <div class="col-md-12">
                                                            <table class="table table-striped table-bordered table-sm p-0" id="students_female" width="100%" style="font-size:.8rem !important">
                                                                  <thead>
                                                                        <tr> 
                                                                              <th width="10%" class="align-middle text-center">Sys. Status</th>
                                                                              <th width="10%" class=" align-middle">LRN</th>
                                                                              <th width="30%" class="align-middle">Student Name</th>
                                                                              <th width="8%" class="text-center align-middle tbh0 p-0" style="font-size: .7rem !important">Grade</th>
                                                                              <th width="12%" style="font-size: .5rem !important" class="text-center align-middle tbh1"></th>
                                                                              <th style="font-size: .5rem !important" width="18%" class="text-center align-middle tbh2"></th>
                                                                              <th style="font-size: .5rem !important " width="12%" class="text-center align-middle tbh3 is_senior_high" hidden></th>
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
                  <div class="col-md-4 for-ibed" hidden>
                        <div class="row is_senior_high" hidden>
                              <div class="col-md-12">
                                    <div class="card shadow">
                                          <div class="card-body p-2">
                                                <table class="table table-sm table-bordered" style="font-size:.65rem !important">
                                                      <thead>
                                                            <tr>
                                                                  <td colspan="4" class="text-center"><b>SUMMARY TABLE 1ST SEM</b></td>
                                                            </tr>
                                                      </thead>
                                                      <tbody>
                                                            <tr>
                                                                  <td width="30%"  class="text-center p-0 p-1 align-middle"><b>STATUS</b></td>
                                                                  <td width="15%"  class="text-center"><strong>MALE</strong></td>
                                                                  <td width="15%"  class="text-center"><strong>FEMALE</strong></td>
                                                                  <td width="40%"  class="text-center"><strong>TOTAL</strong></td>
                                                            </tr>
                                                            <tr>
                                                                  <td class="text-center p-0 p-1 align-middle"><b>COMPLETE</b></td>
                                                                  <td class="comp_1st_sum text-center summary" data-id="male"></td>
                                                                  <td class="comp_1st_sum text-center summary" data-id="female"></td>
                                                                  <td class="comp_1st_sum text-center summary" data-id="total"></td>
                                                            </tr>
                                                            <tr>
                                                                  <td class="text-center p-0 p-1 align-middle"><b>INCOMPLETE</b></td>
                                                                  <td class="inc_1st_sum text-center summary" data-id="male"></td>
                                                                  <td class="inc_1st_sum text-center summary" data-id="female"></td>
                                                                  <td class="inc_1st_sum text-center summary" data-id="total"></td>
                                                            </tr>
                                                            <tr>
                                                                  <td class="text-center p-0 p-1 align-middle"><b>TOTAL</b></td>
                                                                  <td class="total_1st_sum text-center summary" data-id="male"></td>
                                                                  <td class="total_1st_sum text-center summary" data-id="female"></td>
                                                                  <td class="total_1st_sum text-center summary" data-id="total"></td>
                                                            </tr>
                                                      </tbody>
                                                </table>
                                          </div>
                                    </div>
                              </div>
                        </div>
                        <div class="row is_senior_high" hidden>
                              <div class="col-md-12">
                                    <div class="card shadow">
                                          <div class="card-body p-2">
                                                <table class="table table-sm table-bordered" style="font-size:.65rem !important">
                                                      <thead>
                                                            <tr>
                                                                  <td colspan="4" class="text-center"><b>SUMMARY TABLE 2ND SEM</b></td>
                                                            </tr>
                                                      </thead>
                                                      <tbody>
                                                            <tr>
                                                                  <td width="30%"  class="text-center p-0 p-1 align-middle"><b>STATUS</b></td>
                                                                  <td width="15%"  class="text-center"><strong>MALE</strong></td>
                                                                  <td width="15%"  class="text-center"><strong>FEMALE</strong></td>
                                                                  <td width="40%"  class="text-center"><strong>TOTAL</strong></td>
                                                            </tr>
                                                            <tr>
                                                                  <td class="text-center p-0 p-1 align-middle"><b>COMPLETE</b></td>
                                                                  <td class="comp_2nd_sum text-center summary" data-id="male"></td>
                                                                  <td class="comp_2nd_sum text-center summary" data-id="female"></td>
                                                                  <td class="comp_2nd_sum text-center summary" data-id="total"></td>
                                                            </tr>
                                                            <tr>
                                                                  <td class="text-center p-0 p-1 align-middle"><b>INCOMPLETE</b></td>
                                                                  <td class="inc_2nd_sum text-center summary" data-id="male"></td>
                                                                  <td class="inc_2nd_sum text-center summary" data-id="female"></td>
                                                                  <td class="inc_2nd_sum text-center summary" data-id="total"></td>
                                                            </tr>
                                                            <tr>
                                                                  <td class="text-center p-0 p-1 align-middle"><b>TOTAL</b></td>
                                                                  <td class="total_2nd_sum text-center summary" data-id="male"></td>
                                                                  <td class="total_2nd_sum text-center summary" data-id="female"></td>
                                                                  <td class="total_2nd_sum text-center summary" data-id="total"></td>
                                                            </tr>
                                                      </tbody>
                                                </table>
                                          </div>
                                    </div>
                              </div>
                        </div>
                        <div class="row is_senior_high" hidden>
                              <div class="col-md-12">
                                    <div class="card shadow">
                                          <div class="card-body p-2">
                                                <table class="table table-sm table-bordered" style="font-size:.65rem !important">
                                                      <thead>
                                                            <tr>
                                                                  <td colspan="4" class="text-center"><b>SUMMARY TABLE (End of the School Year)</b></td>
                                                            </tr>
                                                      </thead>
                                                      <tbody>
                                                            <tr>
                                                                  <td width="30%"  class="text-center p-0 p-1 align-middle"><b>STATUS</b></td>
                                                                  <td width="15%"  class="text-center"><strong>MALE</strong></td>
                                                                  <td width="15%"  class="text-center"><strong>FEMALE</strong></td>
                                                                  <td width="40%"  class="text-center"><strong>TOTAL</strong></td>
                                                            </tr>
                                                            <tr>
                                                                  <td class="text-center p-0 p-1 align-middle"><b>REGULAR</b></td>
                                                                  <td class="regular_sum text-center summary" data-id="male"></td>
                                                                  <td class="regular_sum text-center summary" data-id="female"></td>
                                                                  <td class="regular_sum text-center summary" data-id="total"></td>
                                                            </tr>
                                                            <tr>
                                                                  <td class="text-center p-0 p-1 align-middle"><b>IRREGULAR</b></td>
                                                                  <td class="irregular_sum text-center summary" data-id="male"></td>
                                                                  <td class="irregular_sum text-center summary" data-id="female"></td>
                                                                  <td class="irregular_sum text-center summary" data-id="total"></td>
                                                            </tr>
                                                            <tr>
                                                                  <td class="text-center p-0 p-1 align-middle"><b>TOTAL</b></td>
                                                                  <td class="end_total text-center summary" data-id="male"></td>
                                                                  <td class="end_total text-center summary" data-id="female"></td>
                                                                  <td class="end_total text-center summary" data-id="total"></td>
                                                            </tr>
                                                      </tbody>
                                                </table>
                                          </div>
                                    </div>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12">
                                    <div class="card shadow">
                                          <div class="card-body p-2">
                                                <table class="table table-sm table-bordered" style="font-size:.65rem !important">
                                                      <thead>
                                                            <tr>
                                                                  <td colspan="4" class="text-center"><b>SUMMARY TABLE</b></td>
                                                            </tr>
                                                      </thead>
                                                      <tbody>
                                                            <tr>
                                                                  <td width="30%"  class="text-center p-0 p-1 align-middle"><b>STATUS</b></td>
                                                                  <td width="15%"  class="text-center"><strong>MALE</strong></td>
                                                                  <td width="15%"  class="text-center"><strong>FEMALE</strong></td>
                                                                  <td width="40%"  class="text-center"><strong>TOTAL</strong></td>
                                                            </tr>
                                                            <tr>
                                                                  <td class="text-center p-0 p-1 align-middle"><b>PROMOTED</b></td>
                                                                  <td class="promoted_sum text-center summary" data-id="male"></td>
                                                                  <td class="promoted_sum text-center summary" data-id="female"></td>
                                                                  <td class="promoted_sum text-center summary" data-id="total"></td>
                                                            </tr>
                                                            <tr>
                                                                  <td class="text-center p-0 p-1 align-middle"><b>*Conditional</b></td>
                                                                  <td class="conditional_sum text-center summary" data-id="male"></td>
                                                                  <td class="conditional_sum text-center summary" data-id="female"></td>
                                                                  <td class="conditional_sum text-center summary" data-id="total"></td>
                                                            </tr>
                                                            <tr>
                                                                  <td class="text-center p-0 p-1 align-middle"><b>RETAINED</b></td>
                                                                  <td class="retained_sum text-center summary" data-id="male"></td>
                                                                  <td class="retained_sum text-center summary" data-id="female"></td>
                                                                  <td class="retained_sum text-center summary" data-id="total"></td>
                                                            </tr>
                                                      </tbody>
                                                </table>
                                          </div>
                                    </div>
                              </div>
                        </div>
                        <div class="row for-ibed"hidden>
                              <div class="col-md-12">
                                    <div class="card shadow">
                                          <div class="card-body p-2">
                                                <table class="table table-sm table-bordered" style="font-size:.6rem !important">
                                                      <thead>
                                                            <tr>
                                                                  <td colspan="4" class="text-center" style=""><strong>LEARNING PROGRESS AND ACHIEVEMENT (Based on Learners' General Average)</strong></td>
                                                            </tr>
                                                      </thead>
                                                      <tbody>
                                                            <tr>
                                                                  <td width="30%"  class=" text-center p-0 p-1 align-middle"><b>Descriptors & <br>Grading Scale</b></td>
                                                                  <td width="15%"  class="text-center align-middle"><strong>MALE</strong></td>
                                                                  <td width="15%"  class="text-center  align-middle"><strong>FEMALE</strong></td>
                                                                  <td width="40%"  class="text-center  align-middle"><strong>TOTAL</strong></td>
                                                            </tr>
                                                            <tr>
                                                                  <td class="text-center p-0 p-1 align-middle"><b>Did Not Meet <br>Expectations<br>( 74 and below)</b></td>
                                                                  <td class="dnme_sum text-center align-middle summary" data-id="male"></td>
                                                                  <td class="dnme_sum text-center align-middle summary" data-id="female"></td>
                                                                  <td class="dnme_sum text-center align-middle summary" data-id="total"></td>
                                                            </tr>
                                                            <tr>
                                                                  <td class="text-center p-0 p-1 align-middle"><b>Fairly Satisfactory <br>( 75-79)</b></td>
                                                                  <td class="fs_sum text-center align-middle summary" data-id="male"></td>
                                                                  <td class="fs_sum text-center align-middle summary" data-id="female"></td>
                                                                  <td class="fs_sum text-center align-middle summary" data-id="total"></td>
                                                            </tr>
                                                            <tr>
                                                                  <td class="text-center p-0 p-1 align-middle"><b>Satisfactory <br>( 80-84)</b></td>
                                                                  <td class="s_sum text-center align-middle summary" data-id="male"></td>
                                                                  <td class="s_sum text-center align-middle summary" data-id="female"></td>
                                                                  <td class="s_sum text-center align-middle summary" data-id="total"></td>
                                                            </tr>
                                                            <tr>
                                                                  <td class="text-center p-0 p-1 align-middle"><b>Very Satisfactory <br>( 85 -89)</b></td>
                                                                  <td class="vs_sum text-center align-middle summary" data-id="male"></td>
                                                                  <td class="vs_sum text-center align-middle summary" data-id="female"></td>
                                                                  <td class="vs_sum text-center align-middle summary" data-id="total"></td>
                                                            </tr>
                                                            <tr>
                                                                  <td class="text-center p-0 p-1 align-middle"><b>Outstanding <br>( 90 -100)</b></td>
                                                                  <td class="o_sum text-center align-middle summary" data-id="male"></td>
                                                                  <td class="o_sum text-center align-middle summary" data-id="female"></td>
                                                                  <td class="o_sum text-center align-middle summary" data-id="total"></td>
                                                            </tr>
                                                      </tbody>
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
      <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
      <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
      <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
      <script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>


      <script>
            $(document).ready(function(){

                  $('.select2').select2()

                  $('#filter_gradelevel').select2({
                        placeholder:'Select Grade Level'
                  })

                  var all_students = []
                  var check_all = 0;

                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })
             
                  loaddatatable()

                  $(document).on('click','#button_filter',function(){

                        if($('#filter_gradelevel').val() == ""){
                              Toast.fire({
                                    type: 'warning',
                                    title: "No grade level selected!"
                              });
                              return false
                        }


                        if($('#filter_gradelevel').val() >= 17 && $('#filter_gradelevel').val() <= 21){
                              $('.for-ibed').attr('hidden','hidden')
                        }else{
                              $('.for-ibed').removeAttr('hidden','hidden')
                        }
                        load_student()
                  })

                  $(document).on('click','#checkall',function(){
                        var is_all = 1;
                        if($(this).prop('checked') == false){
                              is_all = 0;
                        }
                        $.each(all_students,function(a,b){
                              b.checked = is_all;
                        })
                        check_all = is_all;
                        loaddatatable()
                  })

                  $(document).on('click','.select_prom',function(){
                        if($(this).prop('checked') == false){
                              $('#checkall').prop('checked',false)
                        }
                        check_all = 0;
                  })

                  $(document).on('click','#button_promote',function(){
                        selected_student = null
                        Swal.fire({
                              title: 'Do you want to promote all students?',
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Promote'
                        }).then((result) => {
                              if (result.value) {
                                    promote_student()
                              }
                        })
                  })

                  $(document).on('click','#button_unpromote',function(){
                        selected_student = null
                        Swal.fire({
                              title: 'Do you want to unpromote all students?',
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Promote'
                        }).then((result) => {
                              if (result.value) {
                                    unpromote_student()
                              }
                        })
                  })

                  var selected_student
                  $(document).on('click','.promot_one_student',function(){
                        selected_student = $(this).attr('data-studid')
                        Swal.fire({
                              title: 'Do you want to promote student?',
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Promote'
                        }).then((result) => {
                              if (result.value) {
                                    promote_student()
                              }
                        })
                  })

                  $(document).on('click','.unpromot_one_student',function(){
                        selected_student = $(this).attr('data-studid')
                        Swal.fire({
                              title: 'Do you want to unpromote student?',
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Unpromote'
                        }).then((result) => {
                              if (result.value) {
                                    unpromote_student()
                              }
                        })
                  })

                  if($('#filter_gradelevel').val() == 14 || $('#filter_gradelevel').val() == 15){
                        $('#filter_semid').removeAttr('disabled')
                        $('.is_senior_high').removeAttr('hidden')
                        $('#withg').removeAttr('disabled')
                        if($('#filter_semid').val() == 1){
                              $('.auto_enroll_holder').removeAttr('hidden')
                              $('#aut_enroll').removeAttr('disabled')
                              $('#aut_enroll').prop('checked',true)
                        }else{
                              $('.auto_enroll_holder').attr('hidden','hidden')
                              $('#aut_enroll').attr('disabled','disabled')
                              $('#aut_enroll').prop('checked',false)
                        }
                  }else{
                        $('#filter_semid').attr('disabled','disabled')
                        $('#withg').attr('disabled','disabled')
                        $('input[value="2"]').prop('checked',true)
                  }

                  $(document).on('change','#filter_gradelevel',function(){
                        
                        $('.auto_enroll_holder').attr('hidden','hidden')
                        $('#aut_enroll').attr('disabled','disabled')
                        $('#aut_enroll').prop('checked',false)
                        $('.is_senior_high').attr('hidden','hidden')
                        $('#button_promote').attr('disabled','disabled')
                        $('#button_unpromote').attr('disabled','disabled')
                        $('#semester_holder').attr('hidden','hidden')
                        
                        if($(this).val() == 14 || $(this).val() == 15){

                              // $('.tbh1').text('ACTION TAKEN: PROMOTED, CONDITIONAL, or RETAINED')
                              
                              $('#filter_semid').removeAttr('disabled')
                              $('.is_senior_high').removeAttr('hidden')
                              $('#semester_holder').removeAttr('hidden')
                              $('#withg').removeAttr('disabled')
                              if($('#filter_semid').val() == 1){
                                    $('.auto_enroll_holder').removeAttr('hidden')
                                    $('#aut_enroll').removeAttr('disabled')
                                    $('#aut_enroll').prop('checked',true)
                              }else{
                                    $('.auto_enroll_holder').attr('hidden','hidden')
                                    $('#aut_enroll').attr('disabled','disabled')
                                    $('#aut_enroll').prop('checked',false)
                              }

                              $('.tbh1').text('Gen. Ave.')
                              $('.tbh1').text('BACK SUBJECT/S List down subjects where learner obtained a rating below 75%)')
                              $('.tbh1').attr('width','18%')
                              $('.tbh2').attr('width','12%')
                              $('.tbh2').text('END OF SEMESTER STATUS (Complete/ Incomplete)')
                              $('.tbh3').text('END OF SCHOOL YEAR STATUS (Regular/ Irregular)')

                        }else if($(this).val() == 17 || $(this).val() == 18 || $(this).val() == 19 || $(this).val() == 20 || $(this).val() == 21){
                              $('#filter_semid').removeAttr('disabled')
                              $('#withg').attr('disabled','disabled')
                              $('input[value="2"]').prop('checked',true)
                              $('.tbh1').text('')
                              $('.tbh1').attr('')
                              $('.tbh2').attr('')
                              $('#semester_holder').removeAttr('hidden')
                        }
                        else{
                              $('#filter_semid').val(1).change()
                              $('.tbh1').text('ACTION TAKEN: PROMOTED, CONDITIONAL, or RETAINED')
                              $('.tbh2').text('Did Not Meet Expectations of the ff. Learning Area/s as of end of current School Year ')
                              $('.tbh1').attr('width','12%')
                              $('.tbh2').attr('width','18%')
                              $('#filter_semid').attr('disabled','disabled')
                              $('#withg').removeAttr('disabled')
                        }

                        all_students = []
                        loaddatatable()
                       
                  })


                  $(document).on('change','#filter_semid',function(){
                        $('#button_promote').attr('disabled','disabled')
                        $('#button_unpromote').attr('disabled','disabled')
                        if($(this).val() == 1){
                              if($('#filter_gradelevel').val() == 14 || $('#filter_gradelevel').val() == 15){
                                    $('.auto_enroll_holder').removeAttr('hidden')
                                    $('#aut_enroll').removeAttr('disabled')
                                    $('#aut_enroll').prop('checked',true)
                              }else{
                                    $('.auto_enroll_holder').attr('hidden','hidden')
                                    $('#aut_enroll').attr('disabled','disabled')
                                    $('#aut_enroll').prop('checked',false)
                              }
                        }else{
                              $('.auto_enroll_holder').attr('hidden','hidden')
                              $('#aut_enroll').attr('disabled','disabled')
                              $('#aut_enroll').prop('checked',false)
                        }
                  })


                  
                  function load_student(){
                        var condition = $('input[name="condition"]:checked').val()
                        $.ajax({
                              type:'GET',
                              url:'/superadmin/student/promotion/students',
                              data:{
                                    syid:$('#filter_syid').val(),
                                    semid:$('#filter_semid').val(),
                                    levelid:$('#filter_gradelevel').val(),
                                    condition:condition
                                    
                              },
                              success:function(data) {
                                    if(data.length == 0){
                                          $('#button_promote').removeAttr('disabled','disabled')
                                          $('#button_unpromote').removeAttr('disabled','disabled')
                                          Toast.fire({
                                                type: 'info',
                                                title: "No student found!"
                                          });
                                          all_students = [];
                                          loaddatatable()
                                          $('#button_promote').attr('disabled','disabled')
                                          $('#button_unpromote').attr('disabled','disabled')
                                          if( ( $('#filter_gradelevel').val() == 14 || $('#filter_gradelevel').val() == 15 ) && $('#filter_semid').val() == 1){
                                                $('#auto_enroll').removeAttr('disabled')
                                          }else{
                                                $('#auto_enroll').attr('disabled','disabled')
                                                $('#auto_enroll').prop('checked',false)
                                          }

                                    }else{
                                          Toast.fire({
                                                type: 'info',
                                                title: data.length+" student(s) found!"
                                          });

                                          if( ( $('#filter_gradelevel').val() == 14 || $('#filter_gradelevel').val() == 15 ) && $('#filter_semid').val() == 1){
                                                $('#auto_enroll').removeAttr('disabled')
                                          }else{
                                                $('#auto_enroll').attr('disabled','disabled')
                                                $('#auto_enroll').prop('checked',false)
                                          }

                                          $('#button_promote').removeAttr('disabled')
                                          $('#button_unpromote').removeAttr('disabled')
                                          all_students = data;
                                          loaddatatable()
                                    }
                              },
                              error:function(){
                                    Toast.fire({
                                          type: 'info',
                                          title: "Something went wrong!"
                                    });
                              }
                        })
                  }

                  var pdiv = 50;

                  function promote_student(){
                        var auto_enroll = false
                        var condition = $('input[name="condition"]:checked').val()
                        if($('#aut_enroll').prop('checked') == true){
                              auto_enroll = true
                        }

                        $.ajax({
                              type:'GET',
                              url:'/superadmin/student/promotion/students/promote',
                              data:{
                                    syid:$('#filter_syid').val(),
                                    semid:$('#filter_semid').val(),
                                    levelid:$('#filter_gradelevel').val(),
                                    condition:condition,
                                    check_all:check_all,
                                    studid:selected_student,
                                    auto_enroll:auto_enroll
                              },
                              success:function(data) {
                                    if(data[0].status == 1){
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].data
                                          })
                                          all_students = data[0].info
                                          loaddatatable()
                                    }else{
                                          Toast.fire({
                                                type: 'error',
                                                title: data[0].data
                                          })
                                    }
                              }
                        })
                  }


                  function unpromote_student(){
                        var auto_enroll = false
                        var condition = $('input[name="condition"]:checked').val()
                        if($('#aut_enroll').prop('checked') == true){
                              auto_enroll = true
                        }

                        $.ajax({
                              type:'GET',
                              url:'/superadmin/student/promotion/students/unpromote',
                              data:{
                                    syid:$('#filter_syid').val(),
                                    semid:$('#filter_semid').val(),
                                    levelid:$('#filter_gradelevel').val(),
                                    condition:condition,
                                    check_all:check_all,
                                    studid:selected_student,
                                    auto_enroll:auto_enroll
                              },
                              success:function(data) {
                                    if(data[0].status == 1){
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].data
                                          })
                                          all_students = data[0].info
                                          loaddatatable()
                                    }else{
                                          Toast.fire({
                                                type: 'error',
                                                title: data[0].data
                                          })
                                    }
                              }
                        })
                  }

                
                  function loaddatatable(){

                        $('#total_promoted').text(null)
                        $('#total_unpromoted').text(null)
                        $('.summary').text(null)
                        
                        $('#total_promoted').text(all_students.filter(x=>x.promotedtonextgradelevel).length)
                        $('#total_unpromoted').text(all_students.filter(x=>!x.promotedtonextgradelevel).length)
                        var condition = $('input[name="condition"]:checked').val()

                        $('.promoted_sum[data-id="male"]').text(all_students.filter(x=>x.gender.toUpperCase() == 'MALE' && x.actiontaken == "PROMOTED").length)
                        $('.promoted_sum[data-id="female"]').text(all_students.filter(x=>x.gender.toUpperCase() == 'FEMALE' && x.actiontaken == "PROMOTED").length)
                        $('.promoted_sum[data-id="total"]').text(all_students.filter(x=>x.actiontaken == "PROMOTED").length)
                        
                        $('.conditional_sum[data-id="male"]').text(all_students.filter(x=>x.gender.toUpperCase() == 'MALE' && x.actiontaken == "CONDITIONAL").length)
                        $('.conditional_sum[data-id="female"]').text(all_students.filter(x=>x.gender.toUpperCase() == 'FEMALE' && x.actiontaken == "CONDITIONAL").length)
                        $('.conditional_sum[data-id="total"]').text(all_students.filter(x=>x.actiontaken == "CONDITIONAL").length)
                        
                        $('.retained_sum[data-id="male"]').text(all_students.filter(x=>x.gender.toUpperCase() == 'MALE' && x.actiontaken == "RETAINED").length)
                        $('.retained_sum[data-id="female"]').text(all_students.filter(x=>x.gender.toUpperCase() == 'FEMALE' && x.actiontaken == "RETAINED").length)
                        $('.retained_sum[data-id="total"]').text(all_students.filter(x=>x.actiontaken == "RETAINED").length)

                        $('.dnme_sum[data-id="male"]').text(all_students.filter(x=>x.gender.toUpperCase() == 'MALE' && parseFloat(x.genave) < 75 ).length)
                        $('.dnme_sum[data-id="female"]').text(all_students.filter(x=>x.gender.toUpperCase() == 'FEMALE' && parseFloat(x.genave) < 75 ).length)
                        $('.dnme_sum[data-id="total"]').text(all_students.filter(x=>parseFloat(x.genave) < 75 ).length)

                        $('.fs_sum[data-id="male"]').text(all_students.filter(x=>x.gender.toUpperCase() == 'MALE' && ( parseFloat(x.genave) >= 75 && parseFloat(x.genave) <= 79 ) ).length)
                        $('.fs_sum[data-id="female"]').text(all_students.filter(x=>x.gender.toUpperCase() == 'FEMALE' && ( parseFloat(x.genave) >= 75 && parseFloat(x.genave) <= 79 ) ).length)
                        $('.fs_sum[data-id="total"]').text(all_students.filter(x=> ( parseFloat(x.genave) >= 75 && parseFloat(x.genave) <= 79 ) ).length)

                        $('.s_sum[data-id="male"]').text(all_students.filter(x=>x.gender.toUpperCase() == 'MALE' && ( parseFloat(x.genave) >= 80 && parseFloat(x.genave) <= 84 ) ).length)
                        $('.s_sum[data-id="female"]').text(all_students.filter(x=>x.gender.toUpperCase() == 'FEMALE' && ( parseFloat(x.genave) >= 80 && parseFloat(x.genave) <= 84 ) ).length)
                        $('.s_sum[data-id="total"]').text(all_students.filter(x=> ( parseFloat(x.genave) >= 80 && parseFloat(x.genave) <= 84 ) ).length)

                        $('.vs_sum[data-id="male"]').text(all_students.filter(x=>x.gender.toUpperCase() == 'MALE' && ( parseFloat(x.genave) >= 85 && parseFloat(x.genave) <= 89 ) ).length)
                        $('.vs_sum[data-id="female"]').text(all_students.filter(x=>x.gender.toUpperCase() == 'FEMALE' && ( parseFloat(x.genave) >= 85 && parseFloat(x.genave) <= 89 ) ).length)
                        $('.vs_sum[data-id="total"]').text(all_students.filter(x=> ( parseFloat(x.genave) >= 85 && parseFloat(x.genave) <= 89 ) ).length)

                        $('.o_sum[data-id="male"]').text(all_students.filter(x=>x.gender.toUpperCase() == 'MALE' && ( parseFloat(x.genave) >= 90 && parseFloat(x.genave) <= 100 ) ).length)
                        $('.o_sum[data-id="female"]').text(all_students.filter(x=>x.gender.toUpperCase() == 'FEMALE' && ( parseFloat(x.genave) >= 90 && parseFloat(x.genave) <= 100 ) ).length)
                        $('.o_sum[data-id="total"]').text(all_students.filter(x=> ( parseFloat(x.genave) >= 90 && parseFloat(x.genave) <= 100 ) ).length)

                        $('.comp_1st_sum[data-id="male"]').text(all_students.filter(x=>x.gender.toUpperCase() == 'MALE' && x.ess1 == 'COMPLETE' ).length)
                        $('.comp_1st_sum[data-id="female"]').text(all_students.filter(x=>x.gender.toUpperCase() == 'FEMALE' && x.ess1 == 'COMPLETE' ).length)
                        $('.comp_1st_sum[data-id="total"]').text(all_students.filter(x=>x.ess1 == 'COMPLETE' ).length)

                        $('.inc_1st_sum[data-id="male"]').text(all_students.filter(x=>x.gender.toUpperCase() == 'MALE' && x.ess1 == 'INCOMPLETE' ).length)
                        $('.inc_1st_sum[data-id="female"]').text(all_students.filter(x=>x.gender.toUpperCase() == 'FEMALE' && x.ess1 == 'INCOMPLETE' ).length)
                        $('.inc_1st_sum[data-id="total"]').text(all_students.filter(x=>x.ess1 == 'INCOMPLETE' ).length)

                        $('.total_1st_sum[data-id="male"]').text(all_students.filter(x=>x.gender.toUpperCase() == 'MALE' ).length)
                        $('.total_1st_sum[data-id="female"]').text(all_students.filter(x=>x.gender.toUpperCase() == 'FEMALE').length)
                        $('.total_1st_sum[data-id="total"]').text(all_students.length)

                        if($('#filter_semid').val() == 2){
                              $('.comp_2nd_sum[data-id="male"]').text(all_students.filter(x=>x.gender.toUpperCase() == 'MALE' && x.ess2 == 'COMPLETE' ).length)
                              $('.comp_2nd_sum[data-id="female"]').text(all_students.filter(x=>x.gender.toUpperCase() == 'FEMALE' && x.ess2 == 'COMPLETE' ).length)
                              $('.comp_2nd_sum[data-id="total"]').text(all_students.filter(x=>x.ess2 == 'COMPLETE' ).length)

                              $('.inc_2nd_sum[data-id="male"]').text(all_students.filter(x=>x.gender.toUpperCase() == 'MALE' && x.ess2 == 'INCOMPLETE' ).length)
                              $('.inc_2nd_sum[data-id="female"]').text(all_students.filter(x=>x.gender.toUpperCase() == 'FEMALE' && x.ess2 == 'INCOMPLETE' ).length)
                              $('.inc_2nd_sum[data-id="total"]').text(all_students.filter(x=>x.ess2 == 'INCOMPLETE' ).length)

                              $('.total_2nd_sum[data-id="male"]').text(all_students.filter(x=>x.gender.toUpperCase() == 'MALE' ).length)
                              $('.total_2nd_sum[data-id="female"]').text(all_students.filter(x=>x.gender.toUpperCase() == 'FEMALE').length)
                              $('.total_2nd_sum[data-id="total"]').text(all_students.length)

                              $('.regular_sum[data-id="male"]').text(all_students.filter(x=>x.gender.toUpperCase() == 'MALE' && x.esys == 'REGULAR' ).length)
                              $('.regular_sum[data-id="female"]').text(all_students.filter(x=>x.gender.toUpperCase() == 'FEMALE' && x.esys == 'REGULAR' ).length)
                              $('.regular_sum[data-id="total"]').text(all_students.filter(x=>x.esys == 'REGULAR' ).length)

                              $('.irregular_sum[data-id="male"]').text(all_students.filter(x=>x.gender.toUpperCase() == 'MALE' && x.esys == 'IRREGULAR' ).length)
                              $('.irregular_sum[data-id="female"]').text(all_students.filter(x=>x.gender.toUpperCase() == 'FEMALE' && x.esys == 'IRREGULAR' ).length)
                              $('.irregular_sum[data-id="total"]').text(all_students.filter(x=>x.esys == 'IRREGULAR' ).length)

                              $('.end_total[data-id="male"]').text(all_students.filter(x=>x.gender.toUpperCase() == 'MALE' ).length)
                              $('.end_total[data-id="female"]').text(all_students.filter(x=>x.gender.toUpperCase() == 'FEMALE').length)
                              $('.end_total[data-id="total"]').text(all_students.length)
                        }

                        $("#students_male").DataTable({
                              destroy: true,
                              scrollX: true,
                              autoWidth: false,
                              data:all_students.filter(x=>x.gender.toUpperCase() == 'MALE'),
                              columns: [
                                    { "data": "promotiondesc"},
                                    { "data": "lrn"},
                                    { "data": "student" },
                                    { "data": "genave" },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                              ],
                              order: [
                                    [ 2, "asc" ]
                              ],
                              columnDefs: [
                                    {
                                          'targets': 0,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                if(rowData.promotionstatus == 0 || rowData.promotionstatus == null){
                                                      $(td)[0].innerHTML = '<a href="#" class="btn-sm btn btn-primary promot_one_student btn-block text-white" data-studid="'+rowData.studid+'" style="font-size:.6rem">Promote</a>'
                                                }else{
                                                      $(td)[0].innerHTML = '<a href="#" class="btn-sm btn btn-danger unpromot_one_student btn-block text-white" data-studid="'+rowData.studid+'" style="font-size:.6rem">Unpromote</a>'
                                                }
                                          }
                                    },
                                    {
                                          'targets': 1,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 2,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 4,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).attr('style','font-size:.7rem !important')
                                                if($('#filter_gradelevel').val() == 14 || $('#filter_gradelevel').val() == 15){
                                                      $(td).attr('style','font-size:.6rem !important')
                                                      $(td).text(rowData.fail_subj)
                                                }else{
                                                      
                                                      $(td).text(rowData.actiontaken)
                                                }
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 3,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                if(condition == 1){
                                                      $(td).text(rowData.genave)
                                                }else{
                                                      $(td).text('')
                                                }
                                                $(td).addClass('align-middle')
                                                $(td).addClass('text-center')
                                          }
                                    },
                                    {
                                          'targets': 5,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).attr('style','font-size:.7rem !important')
                                                $(td).addClass('align-middle')
                                                if($('#filter_gradelevel').val() == 14 || $('#filter_gradelevel').val() == 15){
                                               
                                                      if(condition == 1){
                                                            if($('#filter_semid').val() == 1){
                                                                  $(td).text(rowData.ess1)
                                                            }else{
                                                                  $(td).text(rowData.ess2)
                                                            }
                                                      }
                                                      else{
                                                            $(td).text(null)
                                                      }
                                                }else{
                                                      $(td).attr('style','font-size:.6rem !important')
                                                      $(td).text(rowData.fail_subj)
                                                }
                                                
                                          }
                                    },
                                    {
                                          'targets': 6,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).attr('style','font-size:.7rem !important')
                                                $(td).addClass('align-middle')
                                                if($('#filter_gradelevel').val() == 14 || $('#filter_gradelevel').val() == 15){
                                                      if(condition == 1){
                                                            $(td).removeAttr('hidden')
                                                            $(td).text(rowData.esys)
                                                      }else{
                                                            $(td).text(null)
                                                      }
                                                }else{
                                                      $(td).attr('hidden','hidden')
                                                      $(td).text(null)
                                                }
                                          }
                                    },
                              ]
                        });

                        $("#students_female").DataTable({
                              destroy: true,
                              scrollX: true,
                              autoWidth: false,
                              data:all_students.filter(x=>x.gender.toUpperCase() == 'FEMALE'),
                              columns: [
                                    { "data": "promotiondesc"},
                                    { "data": "lrn"},
                                    { "data": "student" },
                                    { "data": "genave" },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                              ],
                              order: [
                                    [ 2, "asc" ]
                              ],
                              columnDefs: [
                                    {
                                          'targets': 0,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                if(rowData.promotionstatus == 0 || rowData.promotionstatus == null){
                                                      $(td)[0].innerHTML = '<a href="#" class="btn-sm btn btn-primary promot_one_student btn-block text-white" data-studid="'+rowData.studid+'" style="font-size:.6rem">Promote</a>'
                                                }else{
                                                      // $(td)[0].innerHTML = 'Processed'
                                                      $(td)[0].innerHTML = '<a href="#" class="btn-sm btn btn-danger unpromot_one_student btn-block text-white" data-studid="'+rowData.studid+'" style="font-size:.6rem">Unpromote</a>'
                                                }
                                          }
                                    },
                                    {
                                          'targets': 1,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 2,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 4,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                if($('#filter_gradelevel').val() == 14 || $('#filter_gradelevel').val() == 15){
                                                      $(td).text(rowData.fail_subj)
                                                }else{
                                                      $(td).text(rowData.actiontaken)
                                                }
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 3,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                if(condition == 1){
                                                      $(td).text(rowData.genave)
                                                }else{
                                                      $(td).text('')
                                                }
                                                $(td).addClass('align-middle')
                                                $(td).addClass('text-center')
                                          }
                                    },
                                    {
                                          'targets': 5,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).attr('style','font-size:.7rem !important')
                                                $(td).addClass('align-middle')
                                                if($('#filter_gradelevel').val() == 14 || $('#filter_gradelevel').val() == 15){
                                                      if(condition == 1){
                                                            if($('#filter_semid').val() == 1){
                                                                  $(td).text(rowData.ess1)
                                                            }else{
                                                                  $(td).text(rowData.ess2)
                                                            }
                                                      }
                                                      else{
                                                            $(td).text(null)
                                                      }
                                                }else{
                                                      $(td).text(rowData.fail_subj)
                                                }
                                                
                                          }
                                    },
                                    {
                                          'targets': 6,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                if($('#filter_gradelevel').val() == 14 || $('#filter_gradelevel').val() == 15){
                                                      if(condition == 1){
                                                            $(td).removeAttr('hidden')
                                                            $(td).text(rowData.esys)
                                                      }else{
                                                            $(td).text(null)
                                                      }
                                                }else{
                                                      $(td).attr('hidden','hidden')
                                                      $(td).text(null)
                                                }
                                          }
                                    },
                              ]
                        });
                  }

            })
      </script>


@endsection


