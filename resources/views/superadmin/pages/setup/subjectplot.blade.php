@php
      $check_refid = DB::table('usertype')->where('id',Session::get('currentPortal'))->select('refid')->first();
      if(Session::get('currentPortal') == 3){
            $extend = 'registrar.layouts.app';
      }else if(auth()->user()->type == 17){
            $extend = 'superadmin.layouts.app2';
      }else if(Session::get('currentPortal') == 2){
            $extend = 'principalsportal.layouts.app2';
      }else{
            if(isset($check_refid->refid)){
                  if($check_refid->refid == 27){
                        $extend = 'academiccoor.layouts.app2';
                  }
            }else{
                  $extend = 'general.defaultportal.layouts.app';
            }
      }
@endphp

@extends($extend)

@section('pagespecificscripts')
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
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
            .no-border-col{
                  border-left: 0 !important;
                  border-right: 0 !important;
            }
            input[type=search]{
                  height: calc(1.7em + 2px) !important;
            }
      </style>
@endsection


@section('content')

@php
   $sy = DB::table('sy')
            ->select(
                  'sy.sydesc',
                  'sy.id',
                  'sy.isactive'
            )
            ->orderBy('sydesc','desc')
            ->get(); 

   $semester = DB::table('semester')
                  ->select(
                        'semester.id',
                        'semester.semester',
                        'semester.isactive'
                  )
                  ->get(); 

   $strand = DB::table('sh_strand')
                  ->orderBy('sh_strand.strandname')
                  ->where('sh_strand.active',1)
                  ->where('sh_strand.deleted',0)
                  ->select(
                        'sh_strand.strandname',
                        'sh_strand.strandcode',
                        'sh_strand.id'
                  )
                  ->get(); 
   
      // if(auth()->user()->type == 2){
      //       $acad = array();
            
      //       $teacherid = DB::table('teacher')
      //                         ->where('deleted',0)
      //                         ->where('userid',auth()->user()->id)
      //                         ->first();

      //       $temp_acad = DB::table('academicprogram')
      //                   ->where('principalid',$teacherid->id)
      //                   ->select('id')
      //                   ->get();

      //       foreach($temp_acad as $item){
      //             array_push($acad,$item->id);
      //       }

      //       $gradelevel = DB::table('gradelevel')
      //                   ->where('deleted',0)
      //                   ->whereIn('acadprogid',$acad)
      //                   ->where('gradelevel.acadprogid','!=',6)
      //                   ->orderBy('sortid')
      //                   ->select(
      //                         'gradelevel.levelname',
      //                         'gradelevel.id',
      //                         'acadprogid'
      //                   )
      //                   ->get(); 

      // }else{

      //       $teacherid = DB::table('teacher')
      //                               ->where('tid',auth()->user()->email)
      //                               ->select('id')
      //                               ->first()
      //                               ->id;

            
      //       $acadprog = DB::table('teacheracadprog')
      //                               ->where('teacherid',$teacherid)
      //                               ->where('acadprogutype',Session::get('currentPortal'))
      //                               ->where('deleted',0)
      //                               ->select('acadprogid as id')
      //                               ->distinct('acadprogid')
      //                               ->get();

      //       $gradelevel = DB::table('gradelevel')
      //                   ->where('deleted',0)
      //                   ->where('gradelevel.acadprogid','!=',6)
      //                   ->whereIn('gradelevel.acadprogid',collect($acadprog)->pluck('id'))
      //                   ->orderBy('sortid')
      //                   ->select(
      //                         'gradelevel.levelname',
      //                         'gradelevel.id',
      //                         'acadprogid'
      //                   )
      //                   ->get(); 
      // }

      $subject_gradessetup = DB::table('subject_gradessetup')
                              ->where('deleted',0)
                              ->select(
                                    'description as text',
                                    'id'
                              )
                              ->get();

      // $teachers = DB::table('faspriv')
      //                   ->where('faspriv.deleted',0)
      //                   ->join('usertype',function($join){
      //                         $join->on('faspriv.usertype','=','usertype.id');
      //                         $join->where('usertype.deleted',0);
      //                         $join->where('usertype.refid',22);
      //                   })
      //                   ->join('teacher',function($join){
      //                         $join->on('faspriv.userid','=','teacher.userid');
      //                         $join->where('teacher.deleted',0);
      //                   })
      //                   ->select(
      //                         'teacher.lastname',
      //                         'teacher.firstname',
      //                         'teacher.tid',
      //                         'teacher.id'
      //                   )
      //                   ->get();
      
@endphp

<div class="modal fade" id="subjectplot_modal" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-sm">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title" style="font-size: 1.1rem !important">Add Subject</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body">
                        <div class="row">
                              <div class="col-md-12 form-group mb-0">
                                    <label for="">Subject</label>
                                    <select class="form-control" id="input_subject">
                                          <option value="">Select subject</option>
                                    </select>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12">
                                    <i><p class="text-danger ml-2 mb-0" style="font-size:.9rem !important" id="subj_type_holder"></p></i>
                                    <ul id="subj_comp_holder" style="font-size:.9rem !important">
                                        
                                    </ul>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12 form-group mb-1">
                                    <label for="">Component Percentage</label>
                                    <select class="form-control select2" id="input_gradessetup">
                                          <option value="">Select component</option>
                                    </select>
                              </div>
                        </div>
                        <div class="col-md-12 mb-2" id="applytoothercom" hidden="hidden">
                              <div class="icheck-primary d-inline mr-3">
                                  <input type="checkbox" id="apptocon">
                                  <label for="apptocon">Apply to all components.</label>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Subject Coordinator</label>
                                    <select class="form-control select2" id="input_subjcoor">
                                          {{-- <option value="">Select Subject Coordinator</option> --}}
                                          {{-- @foreach ($teachers as $item)
                                                <option value="{{$item->id}}">{{$item->tid.' - '.$item->lastname.', '.$item->firstname}}</option>
                                          @endforeach --}}
                                    </select>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Sort</label>
                                    <input type="text" id="input_sort" class="form-control form-control-sm" onkeyup="this.value = this.value.toUpperCase();" autocomplete="off">
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <div class="icheck-primary d-inline pt-2">
                                          <input type="checkbox" id="input_issp" >
                                          <label for="input_issp">For Special Section
                                          </label>
                                    </div>
                              </div>
                        </div>
                        
                        <div class="row">
                              <div class="col-md-12">
                                    <button class="btn btn-primary btn-sm btn-block" id="miss_com_to_add" hidden>Add Missing Components</button>
                              </div>
                        </div>
                  </div>
                  <div class="modal-footer border-0">
                        {{-- <div class="col-md-6">
                              <button class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                        </div> --}}
                        <div class="col-md-6 text-right">
                              <button class="btn btn-success btn-sm" id="subjectplot_to_create"><i class="fas fa-plus"></i> Add</button>
                        </div>
                  </div>
            </div>
      </div>
</div>   

<div class="modal fade" id="subjectplot_modal_copy" style="display: none;" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-sm">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title" style="font-size: 1.1rem !important">Copy Subject Plot</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body">
                        <div class="row">
                              <div class="col-md-6 form-group">
                                    <div class="icheck-primary d-inline pt-2">
                                        <input type="checkbox" id="copy_to_gradelevel" >
                                        <label for="copy_to_gradelevel">Grade Level
                                        </label>
                                    </div>
                              </div>
                              <div class="col-md-6 form-group">
                                    <div class="icheck-primary d-inline pt-2">
                                        <input type="checkbox" id="copy_to_schoolyear" >
                                        <label for="copy_to_schoolyear">School Year
                                        </label>
                                    </div>
                              </div>
                              <div class="col-md-6 form-group is_senior">
                                    <div class="icheck-primary d-inline pt-2">
                                        <input type="checkbox" id="copy_to_strand" >
                                        <label for="copy_to_strand">Strand
                                        </label>
                                    </div>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Copy to school year</label>
                                    <select class="form-control select2" id="input_sy" disabled>
                                          <option value="" selected="selected">Select School Year</option>
                                          @foreach ($sy as $item)
                                                <option value="{{$item->id}}">{{$item->sydesc}}</option>
                                          @endforeach
                                    </select>
                              </div>
                              <div class="col-md-12 form-group">
                                    <label for="">Copy to grade level</label>
                                    <select class="form-control select2" id="input_gradelevel" disabled>
                                    </select>
                              </div>
                              <div class="col-md-12 is_senior" hidden>
                                    <label for="">Strand</label>
                                    <select class="form-control  select2" id="input_strand" disabled>
                                          <option value="">Select strand</option>
                                          @foreach ($strand as $item)
                                                <option value="{{$item->id}}">{{$item->strandcode}}</option>
                                          @endforeach
                                    </select>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12">
                                    <button class="btn btn-primary btn-sm btn-block" id="miss_com_to_add" hidden>Add Missing Components</button>
                              </div>
                        </div>
                  </div>
                  <div class="modal-footer border-0">
                        <div class="col-md-6">
                              <button class="btn btn-warning btn-sm" id="subjectplot_to_copy"><i class="fas fa-copy"></i> COPY</button>
                        </div>
                        <div class="col-md-6 text-right">
                              {{-- <button class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fas fa-times"></i> Close</button> --}}
                        </div>
                  </div>
            </div>
      </div>
</div>   

<div class="modal fade" id="modal_2" style="display: none;" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-lg">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title" style="font-size: 1.1rem !important">Subject Component Percentage</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body">
                       <div class="row">
                             <div class="col-md-12" style="font-size:.9rem">
                                    <table class="table  table-sm table-bordered" id="datatable_2" width="100%">
                                          <thead>
                                                <tr>
                                                      <th width="42%">Description</th>
                                                      <th width="12%">WW</th>
                                                      <th width="12%">PT</th>
                                                      <th width="12%">QA</th>
                                                      <th width="12%">CG</th>
                                                      <th width="5%"></th>
                                                      <th width="5%"></th>
                                                </tr>
                                          </thead>
                                    </table>
                             </div>
                       </div>
                  </div>
            </div>
      </div>
</div>   


<div class="modal fade" id="strand_subjects_modal" style="display: none;" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-lg">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title" style="font-size: 1.1rem !important"><span id="subj_strand"></span> Subjects</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body">
                        <div class="row border-top pt-2">
                             <div class="col-md-12 " style="font-size:.9rem">
                                    <label for="">Grade 11 - 1st Semester</label>
                                    <table class="table  table-sm table-bordered" id="" width="100%">
                                          <thead class="thead-light">
                                                <tr>
                                                      <th width="12%">Type</th>
                                                      <th width="12%">Code</th>
                                                      <th width="60%">Subject Description</th>
                                                      <th width="16%">Pre-requisite</th>
                                                </tr>
                                          </thead>
                                          <tbody id="subj_11_1">


                                          </tbody>
                                    </table>
                             </div>
                        </div>
                        <div class="row ">
                              <div class="col-md-12 border-top pt-2" style="font-size:.9rem">
                                    <label for="">Grade 11 - 2nd Semester</label>
                                    <table class="table  table-sm table-bordered" width="100%">
                                          <thead class="thead-light">
                                                <tr>
                                                      <th width="12%">Type</th>
                                                      <th width="12%">Code</th>
                                                      <th width="60%">Subject Description</th>
                                                      <th width="16%">Pre-requisite</th>
                                                </tr>
                                          </thead>
                                          <tbody id="subj_11_2">


                                          </tbody>
                                    </table>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12 border-top pt-2" style="font-size:.9rem">
                                    <label for="">Grade 12 - 1st Semester</label>
                                     <table class="table  table-sm table-bordered" id="" width="100%">
                                           <thead class="thead-light">
                                                <tr>
                                                      <th width="12%">Type</th>
                                                      <th width="12%">Code</th>
                                                      <th width="60%">Subject Description</th>
                                                      <th width="16%">Pre-requisite</th>
                                                </tr>
                                           </thead>
                                           <tbody id="subj_12_1">


                                           </tbody>
                                     </table>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12 border-top pt-2" style="font-size:.9rem">
                                    <label for="">Grade 12 - 2nd Semester</label>
                                     <table class="table  table-sm table-bordered" id="" width="100%">
                                           <thead class="thead-light">
                                                <tr>
                                                      <th width="12%">Type</th>
                                                      <th width="12%">Code</th>
                                                      <th width="60%">Subject Description</th>
                                                      <th width="16%">Pre-requisite</th>
                                                </tr>
                                           </thead>
                                           <tbody id="subj_12_2">


                                           </tbody>
                                     </table>
                               </div>
                         </div>
                  </div>
            </div>
      </div>
</div>   

<div class="modal fade" id="modal_3" style="display: none;" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-sm">
            <div class="modal-content">
                  <div class="modal-body">
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Written Works</label>
                                    <input class="form-control form-control-sm" id="comp1" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                              </div>
                              <div class="col-md-12 form-group">
                                    <label for="">Performance Task</label>
                                    <input class="form-control form-control-sm" id="comp2" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                              </div>
                              <div class="col-md-12 form-group">
                                    <label for="">Quarterly Assesment</label>
                                    <input class="form-control form-control-sm" id="comp3" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                              </div>
                              <div class="col-md-12 form-group">
                                    <label for="">Character Grade</label>
                                    <input class="form-control form-control-sm" id="comp4" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                              </div>
                        </div>
                  </div>
                  <div class="modal-footer border-0">
                        <div class="col-md-6">
                              <button class="btn btn-primary btn-sm" id="create_component_percentage"> Create</button>
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
                        <h1>Subject Plot</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Subject Plot</li>
                  </ol>
                  </div>
            </div>
      </div>
</section>
    
<section class="content pt-0">
      <div class="container-fluid">
            <div class="row">
                  <div class="col-md-8">
                        <div class="row">
                              <div class="col-md-12">
                                    <div class="info-box shadow-lg">
                                          {{-- <span class="info-box-icon bg-primary"><i class="fas fa-calendar-check"></i></span> --}}
                                          <div class="info-box-content">
                                                <div class="row">
                                                      <div class="col-md-3  form-group  mb-0">
                                                            <label for="">School Year</label>
                                                            <select class="form-control select2" id="filter_sy">
                                                                  @foreach ($sy as $item)
                                                                        @if($item->isactive == 1)
                                                                              <option value="{{$item->id}}" selected="selected">{{$item->sydesc}}</option>
                                                                        @else
                                                                              <option value="{{$item->id}}">{{$item->sydesc}}</option>
                                                                        @endif
                                                                  @endforeach
                                                            </select>
                                                      </div>
                                                      <div class="col-md-3  form-group  mb-0">
                                                            <label for="">Grade Level</label>
                                                            <select class="form-control select2" id="filter_gradelevel">
                                                            </select>
                                                      </div>
                                                      <div class="col-md-3 form-group is_senior mb-0" hidden>
                                                            <label for="">Semester</label>
                                                            <select class="form-control select2" id="filter_semester" disabled>
                                                                  <option value="">Select semester</option>
                                                                  @foreach ($semester as $item)
                                                                        <option {{$item->isactive == 1 ? 'checked' : ''}} value="{{$item->id}}">{{$item->semester}}</option>
                                                                  @endforeach
                                                            </select>
                                                      </div>
                                                      <div class="col-md-3 form-group  mb-0 is_senior" hidden>
                                                            <label for="">Strand</label>
                                                            <select class="form-control  select2" id="filter_strand" disabled>
                                                                  <option value="">Select strand</option>
                                                                  @foreach ($strand as $item)
                                                                        <option value="{{$item->id}}">{{$item->strandcode}}</option>
                                                                  @endforeach
                                                            </select>
                                                      </div>
                                                </div>
                                                {{-- <div class="row">
                                                      <div class="col-md-9">
                                                            <div class="row">
                                                                  <div class="col-md-5">
                                                                        <button class="btn btn-primary mt-2 btn-block btn-sm" id="grade_component">Component Percentage</button>
                                                                  </div>
                                                                  <div class="col-md-3">
                                                                        <button class="btn btn-warning mt-2 btn-block btn-sm" id="subjectplot_to_modal_copy" disabled="disabled"><i class="fas fa-copy"></i> Copy</button>
                                                                  </div>
                                                                 
                                                                  <div class="col-md-3">
                                                                        <button class="btn btn-success mt-2  btn-block btn-sm" id="subjectplot_to_modal" disabled="disabled"><i class="fas fa-plus"></i> Add Subject</button>
                                                                  </div>
                                                            </div>
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
                        <div class="card shadow" >
                              <div class="card-body">
                                    <div class="row ">
                                          <div class="col-md-12" style="font-size:14px">
                                                <table class="table table-sm display table-striped p-0 table-bordered" id="subjectplot_table" width="100%">
                                                      <thead>
                                                            <tr>
                                                                  <th width="3%">Sort</th>
                                                                  <th width="8%">Code</th>
                                                                  <th width="37%">Subject</th>
                                                                  <th width="7%"  class="text-center p-0 align-middle">Units</th>
                                                                  <th width="15%">Subject Coordinator</th>
                                                                  <th width="24%" class="text-center">Component Percentage</th>
                                                                  <th width="3%"></th>
                                                                  <th width="3%"></th>
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
      <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
      <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
      <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
      <script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>

      <script>

            var gradelevel = []

            $(document).ready(function(){

                

                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })

                  $('.select2').select2()

                  var all_subjects = []
                  var subjplot = []
                  var selected_subjplot

                  process = 'create'
                  subjectplot_datatable()

                  $(document).on('change','#input_subject',function(){
                        var temp_subject_info = all_subjects.filter(x=>x.id == $(this).val())
                        $('#subj_type_holder').text("")
                        $('#input_gradessetup').removeAttr('disabled')
                        $('#input_gradessetup').val("").change()
                        $('#subj_comp_holder').empty()
                        if($('#filter_gradelevel').val() == 14 || $('#filter_gradelevel').val() == 15){

                        }else{
                              if(temp_subject_info.length > 0){
                         
                                    if(temp_subject_info[0].isCon == 1){
                                          var comp_text = ''
                                          var comp = all_subjects.filter(x=>x.subjCom == $(this).val())
                                          $.each(comp,function(a,b){
                                                $('#subj_comp_holder').append('<li>'+b.subjdesc+'</li>')
                                          })
                                          $('#subj_type_holder')[0].innerHTML = 'Consolidated<br><span class="pl-2">Components:</span>'
                                          $('#input_gradessetup').attr('disabled','disabled')
                                    }
                              }
                        }

                  })

                  $(document).on('change','#filter_gradelevel',function(){
                       
                        $('#input_subject').empty()

                        if($('#filter_gradelevel').val() == 14 || $('#filter_gradelevel').val() == 15){
                              $('#filter_semester').removeAttr('disabled')
                              $('#filter_strand').removeAttr('disabled')
                              $('.is_senior').removeAttr('hidden')
                              filter_change()
                        }else{
                              $('#filter_semester').val("").change()
                              $('#filter_strand').val("").change()
                              $('#filter_semester').attr('disabled','disabled')
                              $('#filter_strand').attr('disabled','disabled')
                              $('.is_senior').attr('hidden','hidden')
                              filter_change()
                        }

                        

                        // $('#subjectplot_to_modal').attr('disabled','disabled')
                        // $('#subjectplot_to_modal_copy').attr('disabled','disabled')

                        // subjplot = []
                        // subjectplot_datatable()
                  })


                  $(document).on('change','#filter_semester',function(){
                        $('#subjectplot_to_modal').attr('disabled','disabled')
                        $('#subjectplot_to_modal_copy').attr('disabled','disabled')
                        if($('#filter_gradelevel').val() == 14 || $('#filter_gradelevel').val() == 15){
                              if($(this).val() == ""){
                                    subjplot = []
                                    subjectplot_datatable()
                              }else{
                                    filter_change() 
                              }
                        }
                  })

                  $(document).on('change','#filter_strand',function(){
                        if($('#filter_gradelevel').val() == 14 || $('#filter_gradelevel').val() == 15){
                              if($(this).val() == ""){
                                    subjplot = []
                                    subjectplot_datatable()
                              }else{
                                    filter_change() 
                              }
                              
                        }
                  })

                  $(document).on('click','#subjectplot_to_modal_copy',function(){

                        $('#copy_to_schoolyear').prop('checked',false)
                        $('#copy_to_strand').prop('checked',false)
                        $('#copy_to_gradelevel').prop('checked',false)


                        $('#input_strand').attr('disabled','disabled')
                        $('#input_gradelevel').attr('disabled','disabled')
                        $('#input_sy').attr('disabled','disabled')

                        $('#input_strand').val("").change()
                        $('#input_gradelevel').val("").change()
                        $('#input_sy').val("").change()

                        $('#subjectplot_modal_copy').modal()

                  })

                  //copy to subject
                  $(document).on('click','#copy_to_gradelevel',function(){
                        if($(this).prop('checked') == true){
                              $('#input_gradelevel').removeAttr('disabled','disabled')

                              $('#copy_to_schoolyear').prop('checked',false)
                              $('#copy_to_strand').prop('checked',false)

                              $('#input_sy').attr('disabled','disabled')
                              $('#input_strand').attr('disabled','disabled')

                              $('#input_sy').val("").change()
                              $('#input_strand').val("").change()
                        }
                       
                  })

                  $(document).on('click','#copy_to_schoolyear',function(){
                        if($(this).prop('checked') == true){

                              $('#input_sy').removeAttr('disabled','disabled')
                              $('#copy_to_gradelevel').prop('checked',false)
                              $('#copy_to_strand').prop('checked',false)

                              $('#input_gradelevel').attr('disabled','disabled')
                              $('#input_strand').attr('disabled','disabled')

                              $('#input_gradelevel').val("").change()
                              $('#input_strand').val("").change()
                        }
                  })

                  $(document).on('click','#copy_to_strand',function(){
                        if($(this).prop('checked') == true){

                              $('#input_strand').removeAttr('disabled','disabled')

                              $('#copy_to_gradelevel').prop('checked',false)
                              $('#copy_to_schoolyear').prop('checked',false)

                              $('#input_sy').attr('disabled','disabled')
                              $('#input_gradelevel').attr('disabled','disabled')

                              $('#input_gradelevel').val("").change()
                              $('#input_sy').val("").change()
                        }
                  })

                  

                  $(document).on('click','#subjectplot_to_copy',function(){




                        $.ajax({
					type:'GET',
					url: '/superadmin/setup/subject/plot/copyto',
                              data:{
                                    syid_to:$('#input_sy').val(),
                                    syid_from:$('#filter_sy').val(),
                                    gradelevel_to:$('#input_gradelevel').val(),
                                    gradelevel_from:$('#filter_gradelevel').val(),
                                    strandid:$('#filter_strand').val(),
                                    strand_to:$('#input_strand').val(),
                                    semid:$('#filter_semester').val(),
                              },
					success:function(data) {
                                    Toast.fire({
                                          type: 'info',
                                          title: data[0].data
                                    })
					}
				})

                  })
                  //copy to subject

                  var gsetup = @json($subject_gradessetup);
                  $("#input_gradessetup").select2({
                        data: gsetup,
                        allowClear: true,
                        placeholder: "Select Component",
                  })

                  function get_all_subject(){
                        var temp_acad = gradelevel.filter(x=>x.id == $('#filter_gradelevel').val())[0].acadprogid
                        $.ajax({
					type:'GET',
					url: '/superadmin/setup/subject/plot/subjects',
                              data:{
                                    acadprog:temp_acad, 
                                    syid:$('#filter_sy').val(),
                                    strandid:$('#filter_strand').val(),
                                    module:'subjectplot',
                                    issp:true
                              },
					success:function(data) {
						all_subjects = data
                                    subjectplot_list()
                                    $.each(all_subjects,function(a,b){
                                          var subj_num = 'S'+('000'+b.id).slice (-3)
                                          b.text = subj_num + ' - ' + b.text
                                    })
                                    // temp_subj = all_subjects
                              
                                    temp_subj = all_subjects.filter(x=>x.subjCom == null)
                                    
                                    $("#input_subject").empty()
                                    $("#input_subject").append('<option value="">Select a subject</option>')
                                    $("#input_subject").select2({
                                          data: temp_subj,
                                          allowClear: true,
                                          placeholder: "Select a subject",
                                    })
					}
				})
                  }


                  $(document).on('click','#subjectplot_to_create',function(){
                       
                        $(this).removeClass('btn-primary')
                        $(this).addClass('btn-success')
				$('#subjectplot_to_create').attr('disabled','disabled')
                        if(process == 'create'){
                              var valid_input = true
                              var check = subjplot.filter(x=>x.subjid == $('#input_subject').val())
                              if(check.length > 0){
                                    valid_input = false
                                    Toast.fire({
                                          type: 'info',
                                          title: 'Subject already exist!'
                                    })
                              }
                              if(valid_input){
                                    subjectplot_create()
                                    $('#subjectplot_to_create').removeAttr('disabled')
                              }
                        }else if(process == 'edit'){
                              subjectplot_update() 
                              $('#subjectplot_to_create').removeAttr('disabled')
                        }
                 })

                  function filter_change(){
                        var valid_fiter = true;
                        if($('#filter_gradelevel').val() == ""){
                              var valid_fiter = false;
                              Toast.fire({
                                    type: 'warning',
                                    title: 'Please grade level!'
                              })
                        }
                        if($('#filter_gradelevel').val() == 14 || $('#filter_gradelevel').val() == 15){
                              if($('#filter_semester').val() == ""){
                                    var valid_fiter = false;
                                    Toast.fire({
                                          type: 'warning',
                                          title: 'Please select semester!'
                                    })
                              }else if($('#filter_strand').val() == ""){
                                    var valid_fiter = false;
                                    Toast.fire({
                                          type: 'warning',
                                          title: 'Please select strand!'
                                    })
                              }
                        }
                        if(valid_fiter){
                              // $('#grade_component').removeAttr('disabled')
                              // $('#subjectplot_to_modal').removeAttr('disabled')
                              // $('#subjectplot_to_modal_copy').removeAttr('disabled')
                              // $('#input_subject').empty()
                              get_all_subject()
                        }else{
                              subjplot = []
                              subjectplot_datatable()
                        }
                        
                  }


                  // component percentage
                  var component_percentage = []

                  $(document).on('click','#grade_component',function(){
                        $('#modal_2').modal()
                  })

                  $(document).on('click','#to_modad_3',function(){
                        $('#comp1').val("")
                        $('#comp2').val("")
                        $('#comp3').val("")
                        $('#comp4').val("")
                        $('#create_component_percentage').text('Create')
                        $('#create_component_percentage').attr('data-type',1)
                        $('#modal_3').modal()
                  })

                  $(document).on('click','#create_component_percentage',function(){
                        if($(this).attr('data-type') == 1){
                              create_component_percentage()
                        }else{
                              var temp_id = $(this).attr('data-id')
                              update_percentage(temp_id)
                        }
                  })

                  $(document).on('click','.delete_comp',function(){
                        var temp_id = $(this).attr('data-id')
                        Swal.fire({
                              title: 'Do you want to remove component percentage?',
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Remove'
                        }).then((result) => {
                              if (result.value) {
                                    delete_percentage(temp_id)
                              }
                        })
                       
                  })

                  $(document).on('click','.edit_comp',function(){
                        var temp_id = $(this).attr('data-id')
                        var temp_data = component_percentage.filter(x=>x.id == temp_id)
                        $('#comp1').val(temp_data[0].ww)
                        $('#comp2').val(temp_data[0].pt)
                        $('#comp3').val(temp_data[0].qa)
                        $('#comp4').val(temp_data[0].comp4)
                        $('#create_component_percentage').text('Update')
                        $('#create_component_percentage').attr('data-type',2)
                        $('#create_component_percentage').attr('data-id',temp_id)
                        $('#modal_3').modal()
                  })


                  $(document).on('input','#comp1, #comp2, #comp3 , #comp4',function(){
                        var total = 0
                        total += $('#comp1').val() != "" ? parseInt($('#comp1').val()) : 0
                        total += $('#comp2').val() != "" ? parseInt($('#comp2').val()) : 0
                        total += $('#comp3').val() != "" ? parseInt($('#comp3').val()) : 0
                        total += $('#comp4').val() != "" ? parseInt($('#comp4').val()) : 0

                        if(total > 100){
                              Toast.fire({
                                    type: 'warning',
                                    title: 'Exceeds 100%'
                              })
                        }
                  })

                  get_component_percentage()

                  function get_component_percentage(){
                        $.ajax({
                              type:'GET',
                              url: '/setup/subject/componentpercentage',
                              success:function(data) {
                                    
                                    component_percentage = data
                                    $("#input_gradessetup").empty()
                                    $("#input_gradessetup").append('<option value="">Component Percentage</option>')
                                    $("#input_gradessetup").select2({
                                          data: component_percentage,
                                          allowClear: true,
                                          placeholder: "Select Component",
                                    })
                                    setup_datatable_2()
                              }
                        })
                  }

                  function delete_percentage(id){
                        $.ajax({
                              type:'GET',
                              url: '/setup/subject/componentpercentage/delete',
                              data:{
                                    id:id,
                              },
                              success:function(data) {
                                    if(data[0].status == 0){
                                          Toast.fire({
                                                type: 'error',
                                                title: data[0].message
                                          })
                                    }else{
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          })
                                          get_component_percentage()
                                    }
                              }
                        })
                  }

                  function update_percentage(id){

                        var total = parseInt(0)
                        total += $('#comp1').val() != "" ? parseInt($('#comp1').val()) : 0
                        total += $('#comp2').val() != "" ? parseInt($('#comp2').val()) : 0
                        total += $('#comp3').val() != "" ? parseInt($('#comp3').val()) : 0
                        total += $('#comp4').val() != "" ? parseInt($('#comp4').val()) : 0

                        if(total != 100){
                              Toast.fire({
                                    type: 'warning',
                                    title: 'Should equal to 100'
                              })
                              return false;
                        }
                        
                        $.ajax({
                              type:'GET',
                              url: '/setup/subject/componentpercentage/update',
                              data:{
                                    id:id,
                                    ww_input:$('#comp1').val(),
                                    pt_input:$('#comp2').val(),
                                    qa_input:$('#comp3').val(),
                                    ct_input:$('#comp4').val()
                              },
                              success:function(data) {
                                    if(data[0].status == 0){
                                          Toast.fire({
                                                type: 'error',
                                                title: data[0].message
                                          })
                                    }else{
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          })
                                          get_component_percentage()
                                    }
                              }
                        })
                  }

                  function create_component_percentage(){

                        var total = parseInt(0)
                        total += $('#comp1').val() != "" ? parseInt($('#comp1').val()) : 0
                        total += $('#comp2').val() != "" ? parseInt($('#comp2').val()) : 0
                        total += $('#comp3').val() != "" ? parseInt($('#comp3').val()) : 0
                        total += $('#comp4').val() != "" ? parseInt($('#comp4').val()) : 0

                        if(total != 100){
                              Toast.fire({
                                    type: 'warning',
                                    title: 'Should equal to 100'
                              })
                              return false;
                        }

                        $.ajax({
                              type:'GET',
                              url: '/setup/subject/componentpercentage/create',
                              data:{
                                    ww_input:$('#comp1').val(),
                                    pt_input:$('#comp2').val(),
                                    qa_input:$('#comp3').val(),
                                    ct_input:$('#comp4').val()
                              },
                              success:function(data) {
                                    if(data[0].status == 0){
                                          Toast.fire({
                                                type: 'error',
                                                title: data[0].message
                                          })
                                    }else{
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          })

                                          $('#ww_input').val("")
                                          $('#pt_input').val("")
                                          $('#qa_input').val("")

                                          get_component_percentage()
                                    }
                              }
                        })
                  }

                  function setup_datatable_2(){
                        $("#datatable_2").DataTable({
                              destroy: true,
                              data:component_percentage,
                              lengthChange: false,
                              columns: [
                                    { "data": "description" },
                                    { "data": "ww" },
                                    { "data": "pt" },
                                    { "data": "qa" },
                                    { "data": "comp4" },
                                    { "data": null },
                                    { "data": null },
                              ],
                              columnDefs: [
                                    {
                                          'targets': 0,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).addClass('align-middle')
                                                $(td).text(rowData.description)
                                          }
                                    },
                                    {
                                          'targets': 1,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 2,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 3,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 4,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 5,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var buttons = '<a href="javascript:void(0)" class="edit_comp" data-id="'+rowData.id+'"><i class="far fa-edit"></i></a>';
                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                                
                                          }
                                    },
                                    {
                                          'targets': 6,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var disabled = '';
                                                var buttons = '<a href="javascript:void(0)" '+disabled+' class="delete_comp" data-id="'+rowData.id+'"><i class="far fa-trash-alt text-danger"></i></a>';
                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                          }
                                    },
                              ]
                        });

                        var label_text = $($('#datatable_2_wrapper')[0].children[0])[0].children[0]
                        $(label_text)[0].innerHTML = '<button class="btn btn-info btn-sm" id="to_modad_3"><i class="fas fa-plus"></i> Create Component Percentage</button>'

                  }



                  // component percentage

                  $(document).on('click','#subjectplot_to_modal',function(){

                        
                        display_allsubjects()

                        $('#subjectplot_modal').modal()
                        $('#subjectplot_to_create')[0].innerHTML = '<i class="fas fa-plus"></i> Add'
                        process = 'create'
                        $('#input_subject').removeAttr('disabled')
                        $('#input_subject').val("").change()
                        $('#input_gradessetup').val("").change()
                        $('#input_sort').val("")

                  })

                  function display_allsubjects(){
                        $("#input_subject").empty()
                        temp_subj = all_subjects.filter(x=>x.subjCom == null)

                        $("#input_subject").select2({
                              data: temp_subj,
                              allowClear: true,
                              placeholder: "Select a subject",
                        })
                  }

                  $(document).on('click','.delete',function(){
                        selected_subjplot = $(this).attr('data-id')
                        subjplot_delete()
                  })

                  $(document).on('click','.edit_subjplot',function(){
                        selected_subjplot = $(this).attr('data-id')
                        temp_subj = subjplot.filter(x=>x.id == selected_subjplot)

                        temp_subjcomp = subjplot.filter(x=>x.subjid == temp_subj[0].subjid)

                        if(temp_subjcomp.length > 0){
                              var newOption = new Option(temp_subjcomp[0].text, temp_subjcomp[0].subjid, false, false);
                              $('#input_subject').append(newOption)
                              $('#input_subject').val(temp_subjcomp[0].subjid).change()
                        }

                        if(temp_subj[0].isCon == 1){
                              $('#miss_com_to_add').removeAttr('hidden')
                              $('#input_gradessetup').attr('disabled','disabled')
                              $('#input_subjcoor').attr('disabled','disabled')
                        }else{
                              $('#miss_com_to_add').attr('hidden','hidden')
                              $('#input_gradessetup').removeAttr('disabled')
                              $('#input_subjcoor').removeAttr('disabled')
                        }


                        $('#input_subject').val(temp_subj[0].subjid).change()
                       
                        $('#input_subjcoor').val(temp_subj[0].subjcoor).change()
                        $('#input_gradessetup').val(temp_subj[0].gradessetup).change()

                        if(temp_subj[0].isforsp == 1 ){
                              $('#input_issp').prop('checked',true)
                        }else{
                              $('#input_issp').prop('checked',false)
                        }

                        $('#apptocon').prop('checked',false)

                        
                        $('#input_sort').val(temp_subj[0].plotsort)
                        $('#input_subject').attr('disabled','disabled')
                        process = 'edit'
                        $('#subjectplot_modal').modal()   
                        $('#subjectplot_to_create').text('Update')   

                        $('#subjectplot_to_create')[0].innerHTML = '<i class="far fa-edit"></i> Update'
                        $('#subjectplot_to_create').addClass('btn-primary')
                        $('#subjectplot_to_create').removeClass('btn-success')

                      
                  })

                  $(document).on('change','#input_gradessetup',function(){
                        temp_subj = subjplot.filter(x=>x.id == selected_subjplot)
                        if(temp_subj.length > 0){
                              if(temp_subj[0].subjCom != null){
                                    $('#applytoothercom').removeAttr('hidden')
                              }else{
                                    $('#applytoothercom').attr('hidden','hidden')
                              }
                        }else{
                              $('#applytoothercom').attr('hidden','hidden')
                        }
                        
                  })
                  
                  $(document).on('click','#miss_com_to_add',function(){
                        subjectplot_update() 
                  })
                  
                  

                  function subjplot_delete(){

                        temp_subj = subjplot.filter(x=>x.id == selected_subjplot)

                        Swal.fire({
                              title: 'Do you want to remove plot?',
                              text: 'You are trying to remove '+temp_subj[0].subjcode+ ' - ' +  temp_subj[0].subjdesc + ' from this plot',
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Remove'
                        }).then((result) => {
                              if (result.value) {
                                    $.ajax({
                                          type:'GET',
                                          url: '/superadmin/setup/subject/plot/delete',
                                          data:{
                                                id:selected_subjplot,
                                                syid:$('#filter_sy').val(),
                                                levelid:$('#filter_gradelevel').val(),
                                                strandid:$('#filter_strand').val(),
                                                semid:$('#filter_semester').val(),
                                                notallow:0
                                          },
                                          success:function(data) {
                                                if(data[0].status == 1){
                                                      Toast.fire({
                                                            type: 'success',
                                                            title: 'Deleted Successfully!'
                                                      })
                                                      subjplot = data[0].info
                                                      get_all_subject()
                                                      subjectplot_datatable()
                                                
                                                }else{
                                                      if(data[0].status == 3){
                                                            Swal.fire({
                                                                  title: 'Subject contains schedule!',
                                                                  text: 'You are trying to remove '+temp_subj[0].subjcode+ ' - ' +  temp_subj[0].subjdesc + ' from this plot',
                                                                  type: 'warning',
                                                                  showCancelButton: true,
                                                                  confirmButtonColor: '#3085d6',
                                                                  cancelButtonColor: '#d33',
                                                                  confirmButtonText: 'Proceed'
                                                            }).then((result) => {
                                                                  if (result.value) {
                                                                        remove_on_conflict()
                                                                  }
                                                            })
                                                      }else{
                                                            Toast.fire({
                                                                  type: 'error',
                                                                  title: data[0].data
                                                            })
                                                      }
                                                }
                                          }
                                    })
                              }
                        })
                  }

                  function remove_on_conflict(){
                        $.ajax({
                              type:'GET',
                              url: '/superadmin/setup/subject/plot/delete',
                              data:{
                                    id:selected_subjplot,
                                    syid:$('#filter_sy').val(),
                                    levelid:$('#filter_gradelevel').val(),
                                    strandid:$('#filter_strand').val(),
                                    semid:$('#filter_semester').val(),
                                    notallow:1
                              },
                              success:function(data) {
                                    if(data[0].status == 1){
                                          Toast.fire({
                                                type: 'success',
                                                title: 'Deleted Successfully!'
                                          })
                                          subjplot = data[0].info
                                          get_all_subject()
                                          subjectplot_datatable()
                                    
                                    }else{
                                          Toast.fire({
                                                type: 'error',
                                                title: data[0].data
                                          })
                                    }
                              }
                        })
                  }

                  function subjectplot_create(){

                        var isforsp = 0;

                        if($('#input_issp').prop('checked') == true){
                              isforsp = 1;
                        }

                        $.ajax({
					type:'GET',
					url: '/superadmin/setup/subject/plot/create',
                              data:{
                                    subjid:$('#input_subject').val(),
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_semester').val(),
                                    strandid:$('#filter_strand').val(),
                                    levelid:$('#filter_gradelevel').val(),
                                    setupid:$('#input_gradessetup').val(),
                                    sort:$('#input_sort').val(),
                                    isforsp:isforsp
                              },
							success:function(data) {
						  			$('#subjectplot_to_create').removeAttr('disabled')
                                    if(data[0].status == 1){
                                          subjplot = data[0].info
                                          get_all_subject()
                                          subjectplot_datatable()
                                          Toast.fire({
                                                type: 'success',
                                                title: 'Create Successfully!'
                                          })
                                          
                                    }else{
                                          Toast.fire({
                                                type: 'error',
                                                title: data[0].data
                                          })
                                    }
					}
				})
                  }

                 

                  function subjectplot_update(){

                        var isforsp = 0;
                        var apptocon = 0;

                        if($('#input_issp').prop('checked') == true){
                              isforsp = 1;
                        }
                        
                        if($('#apptocon').prop('checked') == true){
                              apptocon = 1;
                        }
                       
                        $.ajax({
					type:'GET',
					url: '/superadmin/setup/subject/plot/update',
                              data:{
                                    id:selected_subjplot,
                                    syid:$('#filter_sy').val(),
                                    sort:$('#input_sort').val(),
                                    levelid:$('#filter_gradelevel').val(),
                                    setupid:$('#input_gradessetup').val(),
                                    subjcoor:$('#input_subjcoor').val(),
                                    apptocon:apptocon,
                                    isforsp:isforsp
                              },
					success:function(data) {
                                    if(data[0].status == 1){
                                          subjplot = data[0].info
                                          subjectplot_datatable()
                                          $('#subjectplot_modal').modal('hide')
                                          Toast.fire({
                                                type: 'success',
                                                title: 'Updated Successfully!'
                                          })
                                    }else{
                                          Toast.fire({
                                                type: 'error',
                                                title: 'Something went wrong!'
                                          })
                                    }
					}
				})
                  }

              
                  function subjectplot_list(){
                        $.ajax({
                              type:'GET',
                              url: '/superadmin/setup/subject/plot/list',
                              data:{
                                    syid:$('#filter_sy').val(),
                                    // subjid:$('#input_subject').val(),
                                    levelid:$('#filter_gradelevel').val(),
                                    semid:$('#filter_semester').val(),
                                    strandid:$('#filter_strand').val(),
                                    issp:true
                              },
                              success:function(data) {
                                    subjplot = data
                                    $.each(subjplot,function(a,b){
                                          var subj_num = 'S'+('000'+b.id).slice (-3)
                                          b.text = subj_num + ' - ' + b.subjcode+' - '+b.subjdesc
                                    })
                                    subjectplot_datatable()
                                    if(subjplot.length == 0){
                                          Toast.fire({
                                                type: 'warning',
                                                title: 'No subjects found!'
                                          })
                                    }else{
                                          Toast.fire({
                                                type: 'warning',
                                                title: subjplot.length+' subjects found!'
                                          })
                                    }
                              }
                        })
                  }

                  function subjectplot_datatable(){

                        $('#total_subjects').text(subjplot.length)

                        $("#subjectplot_table").DataTable({
                              destroy: true,
                              data:subjplot,
                              lengthChange: false,
                              scrollX: true,
                              autoWidth: false,
                              paging: false,
                              order: [
                                          [ 0, "asc" ]
                                    ],
                              columns: [
                                    { "data": "plotsort" },
                                    { "data": "subjcode" },
                                    { "data": "subjdesc" },
                                    { "data": "subjunit" },
                                    { "data": "tid" },
                                    { "data": null },
                                    { "data": null },
                                    { "data": "search" }
                              ],
                              columnDefs: [
                                    {
                                          'targets': 0,
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                                $(td).text(rowData.plotsort)
                                          }
                                    },
                                    {
                                          'targets': 1,
                                          'createdCell':  function (td, cellData, rowData, row, col) {

                                                var subj_num = 'S'+('000'+rowData.subjid).slice (-3)
                                                var text = '<a >'+rowData.subjcode+'</a><p class="text-muted mb-0" style="font-size:.7rem ">'+subj_num+'</p>';
                                                $(td)[0].innerHTML =  text
                                          }
                                    },
                                    {
                                          'targets': 2,
                                          'orderable':false,
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var comp = '';
                                                var consolidate = ''
                                                var spec = ''
                                                var type = ''
                                                var percentage = ''
                                                var visDis = ''
                                                var padding = ''
                                                var isSP = ''
                                                var incomputation = '<span class="badge badge-success ml-1">C</span>'
                                                
                                                if($('#filter_gradelevel').val() != 14 && $('#filter_gradelevel').val() != 15){
                                                      if(rowData.isCon == 1){
                                                            consolidate = '<span class="badge badge-success ml-1" >CONSOLIDATED</span>'
                                                            var temp_subjcom = all_subjects.filter(x=>x.subjCom == rowData.subjid)
                                                            if(temp_subjcom.length > 0){
                                                                  comp += '<span class="text-danger"><i>[ '
                                                                  $.each(temp_subjcom,function(a,b){
                                                                        comp += b.subjcode + ', '
                                                                  })
                                                                  comp = comp.slice(0,-2)
                                                                  comp += ' ]<i/i></span>'
                                                            }
                                                      }

                                                      if(rowData.isSP == 1){
                                                            isSP = '<span class="badge badge-secondary ml-1" style="font-size:.6rem">SP</span>'
                                                      }
                                                      if(rowData.inSF9 == 0){
                                                            incomputation = '<span class="badge badge-danger ml-1" >C</span>'
                                                      }

                                                      if(rowData.subjCom != null){
                                                            var comp_subj = all_subjects.filter(x=>x.id == rowData.subjCom)
                                                            if(comp_subj.length > 0){
                                                                  spec = ' <span class="badge badge-info" style="font-size:.6rem">  CMPT: '+comp_subj[0].subjcode+'</span>'
                                                            }

                                                            padding = 'pl-3'
                                                            
                                                            
                                                            if(comp_subj.length > 0){
                                                                if(comp_subj[0].isCon == 1 && comp_subj[0].subjCom != null){
                                                                    padding = 'pl-5'
                                                                }
                                                            }
                                                            
                                                      }

                                                      if(rowData.subj_per != 0){
                                                            percentage = '<span class="badge badge-success" style="font-size:.6rem">'+rowData.subj_per+'%</span>'
                                                      }

                                                      var visDis = '<span class="badge badge-success">V</span>'
                                                      if(rowData.isVisible == 0){
                                                            visDis = '<span class="badge badge-danger badge-danger">V</span>'
                                                      }

                                                }else{
                                                      if(rowData.type == 1){
                                                            type = '<span class="badge badge-danger">CORE</span>'
                                                      }else if(rowData.type == 2){
                                                            type = '<span class="badge badge-danger">SPECIALIZED</span>'
                                                      }else if(rowData.type == 3){
                                                            type = '<span class="badge badge-danger">APPLIED</span>'
                                                      }else if(rowData.type == 4){
                                                            type = '<span class="badge badge-danger">ADADEMIC</span>'
                                                      }else if(rowData.type == 5){
                                                            type = '<span class="badge badge-danger">INSTITUTIONAL</span>'
                                                      }
                                                }

                                                var isforsp = ''

                                                if(rowData.isforsp == 1){
                                                      isforsp = '<span class="badge badge-primary" style="font-size:.6rem">For Special Class</span>'
                                                }

                                                var subj_num = 'S'+('000'+rowData.subjid).slice (-3)


                                                $(td).addClass('pl-3')
                                                var text = '<a class="mb-0 '+padding+'">'+rowData.subjdesc+' '+comp+' </a><p class="text-muted mb-0 '+padding+'" style="font-size:.7rem ">'+visDis+incomputation+consolidate+' '+spec+isSP+' '+type+' '+percentage+' '+isforsp+'</p>';
                                                $(td)[0].innerHTML =  text
                                                
                                          }
                                    },
                                    {
                                          'targets': 3,
                                          'orderable':false,
                                          'createdCell':  function (td, cellData, rowData, row, col) {

                                                $(td).addClass("text-center")
                                                $(td).addClass("align-middle")
                                          }
                                    },
                                    {
                                          'targets': 4,
                                          'orderable':false,
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var teacher = ''
                                                var tid = ''
                                                if(rowData.tid != null){
                                                      teacher = rowData.lastname+', '+rowData.firstname
                                                      tid = rowData.tid
                                                }
                                                var text = '<a class="mb-0">'+teacher+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+tid+'</p>';
                                                $(td)[0].innerHTML =  text
                                                
                                          }
                                    },
                                    {
                                          'targets': 5,
                                          'orderable':false,
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                if(rowData.isCon  == 0 || ( rowData.levelid == 14 || rowData.levelid == 15 ) ){
                                                      var text = ''
                                                      
                                                      var ww = rowData.ww != null ? rowData.ww : 0;
                                                      var pt = rowData.pt != null ? rowData.pt : 0;
                                                      var qa = rowData.qa != null ? rowData.qa : 0;
                                                      var cg = rowData.comp4 != null ? rowData.comp4 : 0;

                                                      text += ww != 0 ?'<span class="badge badge-info mr-2" style="font-size:.7rem">WW - '+ww+'</span>' : ''
                                                      text += pt != 0 ? '<span class="badge badge-success mr-2" style="font-size:.7rem">PT - '+pt+'</span>' : ''
                                                      text += qa != 0 ? '<span class="badge badge-warning mr-2" style="font-size:.7rem">QA - '+qa+'</span>' : ''
                                                      text += cg != 0 ? '<span class="badge badge-secondary" style="font-size:.7rem">CG - '+cg+'</span>' : ''

                                                      $(td)[0].innerHTML = text
                                                }else{
                                                      $(td).text(null)
                                                }

                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 6,
                                          'orderable':false,
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var buttons = '<a href="javascript:void(0)" class="edit_subjplot" data-id="'+rowData.id+'"><i class="far fa-edit"></i></a>';
                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                                
                                          }
                                    },
                                    {
                                          'targets': 7,
                                          'orderable':false,
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var disabled = '';
                                                var buttons = '<a href="javascript:void(0)" '+disabled+' class="delete" data-id="'+rowData.id+'"><i class="far fa-trash-alt text-danger"></i></a>';
                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                          }
                                    },
                              ],
                              "fnInitComplete": function (oSettings, json) {
                                    var label_text = $($('#subjectplot_table_wrapper')[0].children[0])[0].children[0]

                                    // // if($('#filter_type').val() != ""){
                                    $(label_text)[0].innerHTML = '<button class="btn btn-primary btn-sm" id="grade_component"><i class="fas fa-percent  fa-1"></i> Component Percentage</button><button class="ml-2 btn btn-warning btn-sm" id="subjectplot_to_modal_copy" disabled="disabled"><i class="fas fa-copy"></i> Copy</button><button class="ml-2 btn btn-success  btn-sm" id="subjectplot_to_modal" disabled="disabled"><i class="fas fa-plus"></i> Add Subject</button><button class="ml-2 btn btn-default btn-sm" id="view_strand_subject" disabled="disabled" hidden> View Strand Subject</button>'


                                    var valid_fiter = true;
                                    if($('#filter_gradelevel').val() == "" || $('#filter_gradelevel').val() == null){
                                          var valid_fiter = false;
                                    }
                                    if($('#filter_gradelevel').val() == 14 || $('#filter_gradelevel').val() == 15){
                                          $('#view_strand_subject').removeAttr('hidden')
                                          if($('#filter_semester').val() == ""){
                                                var valid_fiter = false;
                                          }else if($('#filter_strand').val() == ""){
                                                var valid_fiter = false;
                                          }
                                    }else{
                                          $('#view_strand_subject').attr('hidden','hidden')
                                    }

                                    if(valid_fiter){
                                          $('#grade_component').removeAttr('disabled')
                                          $('#subjectplot_to_modal').removeAttr('disabled')
                                          $('#subjectplot_to_modal_copy').removeAttr('disabled')
                                          // $('#input_subject').empty()
                                          $('#view_strand_subject').removeAttr('disabled')
                                    }
                              }
                              
                        });

                       
                        
                  }


                  $(document).on('click','#view_strand_subject',function(){
                        $('#strand_subjects_modal').modal()
                        $('#subj_11_1').empty();
                        $('#subj_11_2').empty();
                        $('#subj_12_1').empty();
                        $('#subj_12_2').empty();

                        $('#subj_strand').text($('#filter_strand option:selected').text())

                        var temp_acad = gradelevel.filter(x=>x.id == $('#filter_gradelevel').val())[0].acadprogid

                        $.ajax({
                              type:'GET',
                              url: '/superadmin/setup/subject/plot/list',
                              data:{
                                    acadprog:temp_acad,
                                    syid:$('#filter_sy').val(),
                                    strandid:$('#filter_strand').val(),
                              },
                              success:function(data) {
                                    $.each(data,function(a,b){
                                          var type = ''
                                          if(b.type == 1){
                                                type = '<span class="badge badge-danger">CORE</span>'
                                          }else if(b.type == 2){
                                                type = '<span class="badge badge-danger">SPECIALIZED</span>'
                                          }else if(b.type == 3){
                                                type = '<span class="badge badge-danger">APPLIED</span>'
                                          }else if(b.type == 4){
                                                type = '<span class="badge badge-danger">ADADEMIC</span>'
                                          }else if(b.type == 5){
                                                type = '<span class="badge badge-danger">INSTITUTIONAL</span>'
                                          }

                                          if(b.levelid == 14){
                                                if(b.semid == 1){
                                                      $('#subj_11_1').append('<tr><td>'+type+'</td><td>'+b.subjcode+'</td><td>'+b.subjdesc+'</td></tr>')
                                                }else if(b.semid == 2){
                                                      $('#subj_11_2').append('<tr><td>'+type+'</td><td>'+b.subjcode+'</td><td>'+b.subjdesc+'</td></tr>')
                                                }
                                          }else if(b.levelid == 15){
                                                if(b.semid == 1){
                                                      $('#subj_12_1').append('<tr><td>'+type+'</td><td>'+b.subjcode+'</td><td>'+b.subjdesc+'</td></tr>')
                                                }else if(b.semid == 2){
                                                      $('#subj_12_2').append('<tr><td>'+type+'</td><td>'+b.subjcode+'</td><td>'+b.subjdesc+'</td></tr>')
                                                }
                                          }
                                    })

                                    if(data.filter(x=>x.semid == 1 && x.levelid == 14).length == 0){
                                          $('#subj_11_1').append('<tr><td colspan="4">No Subject Added</td></tr>')
                                    }
                                    if(data.filter(x=>x.semid == 2 && x.levelid == 14).length == 0){
                                          $('#subj_11_2').append('<tr><td colspan="4">No Subject Added</td></tr>')
                                    }
                                    if(data.filter(x=>x.semid == 1 && x.levelid == 15).length == 0){
                                          $('#subj_12_1').append('<tr><td colspan="4">No Subject Added</td></tr>')
                                    }
                                    if(data.filter(x=>x.semid == 2 && x.levelid == 15).length == 0){
                                          $('#subj_12_2').append('<tr><td colspan="4">No Subject Added</td></tr>')
                                    }
                              }
                        })
                  })
                  
                        
                  $(document).on('change','#filter_sy',function(){
                        subjplot = []
                        subjectplot_datatable()

                        $('#filter_semester').val("").change()
                        $('#filter_strand').val("").change()
                        $('#filter_semester').attr('disabled','disabled')
                        $('#filter_strand').attr('disabled','disabled')
                        $('.is_senior').attr('hidden','hidden')

                        $('#filter_gradelevel').empty()
                        get_gradelevel()
                  })


                  $(document).on('change','#filter_gradelevel',function(){
                        get_subjcoor()
                  })

                  get_gradelevel()

                  function get_gradelevel(){

                        $.ajax({
                              type:'GET',
                              url: '/superadmin/setup/subject/plot/getgradelevel',
                              data:{
                                    syid:$('#filter_sy').val(),
                              },
                              success:function(data) {

                                    gradelevel = data
                                    $('#filter_gradelevel').empty()
                                    $("#filter_gradelevel").append('<option value="">Select Grade Level</option>');
                                    $("#filter_gradelevel").select2({
                                          data: gradelevel,
                                          placeholder: "Select Grade Level",
                                          allowClear:true
                                    })

                                    $('#input_gradelevel').empty()
                                    $("#input_gradelevel").append('<option value="">Select Grade Level</option>');
                                    $("#input_gradelevel").select2({
                                          data: gradelevel,
                                          placeholder: "Select Grade Level",
                                          allowClear:true
                                    })

                                    
                              }
                        })

                  }

                 

                  function get_subjcoor(){

                        $.ajax({
                              type:'GET',
                              url: '/superadmin/setup/subject/plot/subjcoor',
                              data:{
                                    syid:$('#filter_sy').val(),
                                    levelid:$('#filter_gradelevel').val(),
                              },
                              success:function(data) {

                                    $('#input_subjcoor').empty()
                                    $("#input_subjcoor").append('<option value="">Select Subject Coor.</option>');
                                    $("#input_subjcoor").select2({
                                          data: data,
                                          placeholder: "Select Subject Coor.",
                                          allowClear:true
                                    })

                              }
                        })

                  }

            })
      </script>

      {{-- <script>
            $(document).ready(function(){

                 

            })
      </script> --}}

      <script>
            $(document).ready(function(){
                  var keysPressed = {};
                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })
                  document.addEventListener('keydown', (event) => {
                        keysPressed[event.key] = true;
                        if (keysPressed['p'] && event.key == 'v') {
                              Toast.fire({
                                          type: 'warning',
                                          title: 'Date Version: 07/28/2021 14:34'
                                    })
                        }
                  });
                  document.addEventListener('keyup', (event) => {
                        delete keysPressed[event.key];
                  });
            })
      </script>


@endsection


