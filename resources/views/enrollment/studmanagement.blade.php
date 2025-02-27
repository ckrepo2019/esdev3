@extends('enrollment.layouts.app')

@section('content')
  <section class="content">
  			<!-- Payment Items -->
        <div class="row mb-2 ml-2 p-0">
          <h3 class="m-0 text-dark">Student Management</h3>
        </div>
        <div class="row form-group">
          {{-- <div class="col-md-7 col-sm-10">
            <button class="btn btn-outline-success active">ENROLLED</button>
            <button class="btn btn-outline-primary ">READY TO ENROLL</button>
            <button class="btn btn-outline-warning ">ONLINE ENROLLMENT</button>
            <button class="btn btn-outline-secondary ">NOT ENROLLED</button>
          </div> --}}
            {{-- <div class="col-md-2 form-group">
                <button id="filter_enrolled" class="btn btn-outline-success btn-block active">ENROLLED</button>
            </div> --}}
            <div class="col-md-2 form-group">
                <button id="filter_ready" class="btn btn-outline-primary btn-block status2">READY TO ENROLL</button>
            </div>
            <div class="col-md-2 form-group">
                <button id="filter_online" class="btn btn-outline-info btn-block status2">ONLINE</button>
            </div>
            <div class="col-md-2 form-group">
                <button id="filter_notenrolled" class="btn btn-outline-secondary btn-block status2">NOT ENROLLED</button>
            </div>
            <div class="col-md-4">
                <div class="input-group" style="width: 100%;">
                    <input id="filter_search" type="text" class="form-control" placeholder="Search" onkeyup="this.value = this.value.toUpperCase();">
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="fas fa-search text-sm"></i></span>
                    </div>
                    
                </div>
            </div>
            <div class="col-md-2">
                <button id="filter_create" class="btn btn-primary" id="item_create">
                    Register A Student
                </button>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-3 form-group">
                <select id="filter_status" class="select2 filter_trigger" style="width: 100%;">
                  <option value="0">STUDENT STATUS (ALL)</option>
                    @foreach(DB::table('studentstatus')->where('id', '!=', 0)->get() as $status)
                        @if($status->id == 1)
                            <option value="{{$status->id}}" selected>{{$status->description}}</option>
                        @else
                            <option value="{{$status->id}}">{{$status->description}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 form-group">
                <select id="filter_levelid" class="select2 filter_trigger" style="width: 100%;">
                  <option value="0">GRADE | YEAR LEVEL</option>
                    @foreach(DB::table('gradelevel')->where('deleted', 0)->orderBy('sortid')->get() as $level)
                        <option value="{{$level->id}}">{{$level->levelname}}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 form-group">
                <select id="filter_syid" class="select2 filter_trigger" style="width: 100%;">
                    @foreach(db::table('sy')->get() as $sy)
                        @if($sy->isactive == 1)
                            <option value="{{$sy->id}}" selected>{{$sy->sydesc}}</option>
                        @else
                            <option value="{{$sy->id}}">{{$sy->sydesc}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select id='filter_semid' class="select2 filter_trigger" style="width: 100%;">
                    @foreach(db::table('semester')->where('deleted', 0)->get() as $sem)
                        @if($sem->isactive == 1)
                            <option value="{{$sem->id}}" selected>{{$sem->semester}}</option>
                        @else
                            <option value="{{$sem->id}}">{{$sem->semester}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            
        </div>
		<div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                    </div>
                    <div class="card-body p-0">
                        <div id="main_table" class="table-responsive p-0">
                            <table class="table table-hover table-head-fixed table-sm text-sm">
                                <thead>
                                  <tr class="">
                                    <th>NAME</th>
                                    <th>GRADE LEVEL</th>
                                    <th>SECTION</th>
                                    <th>GRANTEE</th>
                                    <th>STATUS</th>
                                    <th>PAYMENT</th>
                                  </tr>
                                </thead> 
                                <tbody id="sm_list" style="cursor: pointer;"></tbody>             
                            </table>
                          
                        </div>
                    </div>
                </div>
            </div>          
        </div>
  	</div>
  </section>
@endsection

@section('modal')
    <div class="modal fade show" id="modal-studinfo" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="margin-top: -24px;">
                <div class="modal-header bg-info">
                  <h4 class="modal-title">Student Information</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                </div>
                <div class="row form-group pt-2 pr-2 pl-2 pb-0">
                    <div class="col-md-2">
                        <button id="studinfo_dpstatus" class="btn btn-default btn-block" style="display: block;">No DP</button>
                    </div>
                    
					<div class="col-md-2">
                        <button id="studinfo_print" class="btn btn-default btn-block"><i class="fa fa-print"></i> Print</button>
                    </div>
                    <div class="col-md-2">
                        <button id="studinfo_leaf" class="btn btn-info btn-block" disabled>LEAF</button>
                    </div>
                    <div class="col-md-4" style="display:none">
                        <button id="studinfo_enrollmentinfo" class="btn btn-primary btn-block" disabled>View Enrollment Information <i class="fas fa-angle-double-right"></i></button>
                    </div>
                </div>
                <div id="studinfo_modal" class="modal-body pt-0" style="overflow-y: scroll;">
                    <form class="form-horizontal">
                        <div id="studinfo_card" class="card-body">
                            <div class="form-group row">
                                <h4><u>Information</u></h4>
                            </div>
                            <div class="form-group row">
                                <label for="class-desc" class="col-sm-3 col-form-label">LRN (for Basic Ed. only)</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control validation studinfo-input" autocomplete="doNotAutoComplete" id="studinfo_lrn" placeholder="" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-desc" class="col-sm-3 col-form-label">Grade | Year Level</label>
                                <div class="col-sm-9">
                                    <select id="studinfo_levelid" class="select2 is-invalid validation" value="" style="width: 100%;">
                                        <option value="0"></option>
                                        @foreach(db::table('gradelevel')->where('deleted', 0)->orderBy('sortid')->get() as $l)
                                            <option value="{{$l->id}}">{{$l->levelname}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row div-course">
                                <label for="studinfo_course" class="col-sm-3 col-form-label">Course</label>
                                <div class="col-sm-9">
                                    <select id="studinfo_course" class="select2" value="" style="width: 100%;">
                                        <option value="0">Courses</option>
                                        @foreach(DB::table('college_courses')->where('deleted', 0)->get() as $course)
                                            <option value="{{$course->id}}">{{$course->courseabrv}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row div-grantee">
                                <label for="studinfo_grantee" class="col-sm-3 col-form-label">Grantee</label>
                                <div class="col-sm-9">
                                    <select id="studinfo_grantee" class="form-control" value="">
                                        <option value="1" selected>REGULAR</option>
                                        <option value="2">ESC</option>
                                        <option value="3">VOUCHER</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-desc" class="col-sm-3 col-form-label">Modality</label>
                                <div class="col-sm-9">
                                    <select id="studinfo_mol" class="form-control " value="">
                                        <option value=""></option>
                                        @foreach(db::table('modeoflearning')->where('deleted', 0)->get() as $mol)
                                            <option value="{{$mol->id}}">{{$mol->description}}</option>
                                        @endforeach
                                    </select>   
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-desc" class="col-sm-3 col-form-label">Student Type</label>
                                <div class="col-sm-9">
                                    <select id="studinfo_studtype" class="form-control select2">
                                        <option value="new">NEW</option>
                                        <option value="old">OLD</option>
                                        <option value="transferee">TRANSFEREE</option>
                                        <option value="returnee">RETURNEE</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-desc" class="col-sm-3 col-form-label">First Name</label>
                                <div class="col-sm-9">
                                  <input type="text" class="form-control validation studinfo-input is-invalid validation" autocomplete="doNotAutoComplete" id="studinfo_firstname" placeholder="" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-desc" class="col-sm-3 col-form-label">Middle Name</label>
                                <div class="col-sm-9">
                                  <input type="text" class="form-control validation studinfo-input" autocomplete="doNotAutoComplete" id="studinfo_middlename" placeholder="" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-desc" class="col-sm-3 col-form-label">Last Name</label>
                                <div class="col-sm-9">
                                  <input type="text" class="form-control validation studinfo-input is-invalid validation" autocomplete="doNotAutoComplete" id="studinfo_lastname" placeholder="" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-desc" class="col-sm-3 col-form-label">Suffix</label>
                                <div class="col-sm-9">
                                  <input type="text" class="form-control validation studinfo-input" autocomplete="doNotAutoComplete" id="studinfo_suffix" placeholder="Jr., III" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-glid" class="col-sm-3 col-form-label">Date of Birth</label>
                                <div class="col-sm-9">
                                  <input type="date" id="studinfo_dob" value="" class="form-control validation is-invalid validation" data-inputmask-alias="datetime" data-inputmask-inputformat="mm/dd/yyyy" data-mask="" im-insert="false">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="class-desc" class="col-sm-3 col-form-label">Gender</label>
                                <div class="col-sm-9">
                                  <select id="studinfo_gender" class="form-control" value="">
                                    <option selected="">MALE</option>
                                    <option>FEMALE</option>                             
                                  </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-glid" class="col-sm-3 col-form-label">Contact No.</label>
                                <div class="col-sm-9">
                                  <input type="text" id="studinfo_contactno" class="form-control studinfo-input" value="" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="class-glid" class="col-sm-3 col-form-label">Religion</label>
                                <div class="col-sm-9">
                                  <select id="studinfo_religion" class="form-control" value="">
                                        <option value="0" selected=""></option>
                                        @foreach(db::table('religion')->where('deleted', 0)->get() as $rel)
                                            <option value="{{$rel->id}}">{{$rel->religionname}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-glid" class="col-sm-3 col-form-label">Mother Tongue</label>
                                <div class="col-sm-9">
                                  <select id="studinfo_mt" class="form-control" value="">
                                        <option value="0" selected=""></option>
                                        @foreach(db::table('mothertongue')->where('deleted', 0)->get() as $mt)
                                            <option value="{{$mt->id}}">{{$mt->mtname}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-glid" class="col-sm-3 col-form-label">Ethnic Group</label>
                                <div class="col-sm-9">
                                  <select id="studinfo_eg" class="form-control" value="">
                                        <option value="0" selected=""></option>
                                        @foreach(db::table('ethnic')->where('deleted', 0)->get() as $eg)
                                            <option value="{{$eg->id}}">{{$eg->egname}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="class-glid" class="col-sm-3 col-form-label">Nationality</label>
                                <div class="col-sm-9">
                                    <select id="studinfo_nationality" class="form-control select2bs4" value="">
                                        <option value="0" selected=""></option>
                                        @foreach(db::table('nationality')->where('deleted', 0)->get() as $nat)
                                            @if($nat->id == 77)
                                                <option value="{{$nat->id}}" selected>{{$nat->nationality}}</option>
                                            @else
                                                <option value="{{$nat->id}}">{{$nat->nationality}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <h4><u>Home Address</u></h4>
                            </div>

                            <div class="form-group row">
                                <label name="add1" class="col-sm-3 col-form-label">Street</label>
                                <div class="col-sm-9">
                                  <input name="add1" type="text" id="studinfo_street" class="form-control studinfo-input" autocomplete="doNotAutoComplete" value="" onkeyup="this.value = this.value.toUpperCase();" placeholder="House No. / Street Name / Subdivision">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-glid" class="col-sm-3 col-form-label">Barangay</label>
                                <div class="col-sm-9">
                                  <input type="text" id="studinfo_barangay" class="form-control studinfo-input" autocomplete="doNotAutoComplete" value="" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-glid" class="col-sm-3 col-form-label">District</label>
                                <div class="col-sm-9">
                                  <input type="text" id="studinfo_district" class="form-control studinfo-input" autocomplete="doNotAutoComplete" value="" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-glid" class="col-sm-3 col-form-label">City</label>
                                <div class="col-sm-9">
                                  <input type="text" id="studinfo_city" class="form-control studinfo-input" autocomplete="doNotAutoComplete" value="" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-glid" class="col-sm-3 col-form-label">Province</label>
                                <div class="col-sm-9">
                                  <input type="text" id="studinfo_province" class="form-control studinfo-input" autocomplete="doNotAutoComplete" value="" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-glid" class="col-sm-3 col-form-label">Region</label>
                                <div class="col-sm-9">
                                  <input type="text" id="studinfo_region" class="form-control studinfo-input" autocomplete="doNotAutoComplete" value="" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                            </div>

                            <div class="form-group row">
                                <h4><u>Parents|Guardian</u></h4>
                            </div>
                            <div class="form-group row">
                                <h5>Father's Information</h5>
                            </div>

                            <div class="form-group row">
                                <label for="class-glid" class="col-sm-3 col-form-label">Name</label>
                                <div class="col-sm-9">
                                  <input type="text" id="studinfo_father_name" class="form-control studinfo-input" autocomplete="doNotAutoComplete" value="" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-glid" class="col-sm-3 col-form-label">Occupation</label>
                                <div class="col-sm-9">
                                  <input type="text" id="studinfo_father_occupation" class="form-control studinfo-input" autocomplete="doNotAutoComplete" value="" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-glid" class="col-sm-3 col-form-label">Contact No</label>
                                <div class="col-sm-6">
                                  <input type="text" id="studinfo_father_contactno" class="form-control studinfo-input" value="" autocomplete="doNotAutoComplete" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                                <div class="col-sm-3">
                                    <div class="icheck-primary">
                                        <input type="radio" id="studinfo_father_default" name="r1">
                                        <label for="studinfo_father_default">
                                          Default Contact
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <h5>Mother's Information</h5>
                            </div>

                            <div class="form-group row">
                                <label for="class-glid" class="col-sm-3 col-form-label">Name</label>
                                <div class="col-sm-9">
                                  <input type="text" id="studinfo_mother_name" class="form-control studinfo-input" autocomplete="doNotAutoComplete" value="" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-glid" class="col-sm-3 col-form-label">Occupation</label>
                                <div class="col-sm-9">
                                  <input type="text" id="studinfo_mother_occupation" class="form-control studinfo-input" value="" autocomplete="doNotAutoComplete" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-glid" class="col-sm-3 col-form-label">Contact No</label>
                                <div class="col-sm-6">
                                  <input type="text" id="studinfo_mother_contactno" class="form-control studinfo-input" value=""  onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                                <div class="col-sm-3">
                                    <div class="icheck-primary">
                                        <input type="radio" id="studinfo_mother_default" name="r1">
                                        <label for="studinfo_mother_default">
                                          Default Contact
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <h5>Guardian's Information</h5>
                            </div>

                            <div class="form-group row">
                                <label for="class-glid" class="col-sm-3 col-form-label">Name</label>
                                <div class="col-sm-9">
                                  <input type="text" id="studinfo_guardian_name" class="form-control studinfo-input" autocomplete="doNotAutoComplete" value="" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-glid" class="col-sm-3 col-form-label">Relation</label>
                                <div class="col-sm-9">
                                  <input type="text" id="studinfo_guardian_relation" class="form-control studinfo-input" autocomplete="doNotAutoComplete" value="" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-glid" class="col-sm-3 col-form-label">Contact No</label>
                                <div class="col-sm-6">
                                  <input type="text" id="studinfo_guardian_contactno" class="form-control studinfo-input" autocomplete="doNotAutoComplete" value="" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                                <div class="col-sm-3">
                                    <div class="icheck-primary">
                                        <input type="radio" id="studinfo_guardian_default" name="r1">
                                        <label for="studinfo_guardian_default">
                                          Default Contact
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <h4><u>Medical Information</u></h4>
                            </div>

                            <div class="form-group row">
                                <label for="class-glid" class="col-sm-3 col-form-label">Blood Type</label>
                                <div class="col-sm-9">
                                  <input type="text" id="studinfo_bt" class="form-control studinfo-input" value="" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-glid" class="col-sm-3 col-form-label">Allergy</label>
                                <div class="col-sm-9">
                                  <input type="text" id="studinfo_allergy" class="form-control studinfo-input" value="" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-glid" class="col-sm-3 col-form-label">Other Medical Info</label>
                                <div class="col-sm-9">
                                  <input type="text" id="studinfo_medoth" class="form-control studinfo-input" value="" autocomplete="doNotAutoComplete" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                            </div>

                            <div class="form-group row">
                                <h4><u>Other Information</u></h4>
                            </div>

                            <div class="form-group row">
                                <label for="class-glid" class="col-sm-3 col-form-label">Last School Attended</label>
                                <div class="col-sm-9">
                                  <input type="text" id="studinfo_lastschool" class="form-control studinfo-input" value="" autocomplete="doNotAutoComplete" placeholder="School Name" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-glid" class="col-sm-3 col-form-label">School Year</label>
                                <div class="col-sm-9">
                                  <input type="text" id="studinfo_lastsy" class="form-control studinfo-input" value="" autocomplete="doNotAutoComplete" placeholder="e.g: 2020-2021" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-glid" class="col-sm-3 col-form-label">RFID</label>
                                <div class="col-sm-6">
                                  <input type="text" id="studinfo_rfid" class="form-control studinfo-input" value="" autocomplete="doNotAutoComplete"  onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                                <div class="col-sm-3">
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox" id="studinfo_4p">
                                        <label for="studinfo_4p">
                                            4P's(Pantawid)
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-12 text-right">
                                    <button type="button" class="btn btn-default btn-lg" data-dismiss="modal">Close</button>
                                    <button id="studinfo_save" type="button" class="btn btn-primary btn-lg" data-id="0">Save</button>
                                </div>
                            </div>
                            
                        </div>
                    </form>
                </div>
                {{-- <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="updateItem" type="button" class="btn btn-primary" data-dismiss="modal" data-id="">Save</button>
                </div> --}}
            </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade show" id="modal-enrollmentinfo" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="margin-top: -24px;">
                <div class="modal-header bg-primary">
                  <h4 class="modal-title">Enrollment Information</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                </div>
                <div class="row form-group pt-2 pr-2 pl-2 pb-0">
                    <div class="col-md-2">
                        <button id="enrollmentinfo_dpstatus" class="btn btn-default btn-block" style="display: block;">No DP</button>
                    </div>
                    <div class="col-md-2">
                        <button id="enrollmentinfo_cor" class="btn btn-danger btn-block" toggle="tooltip" title="Certificate of Enrollment">COR</button>
                    </div>
                    <div class="col-md-2">
                        <button id="enrollmentinfo_requirements" class="btn btn-info">Requirements</button>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-warning btn-block">History</button>
                    </div>
                    <div class="col-md-4">
                        <button id="enrollmentinfo_enrollstudent" class="btn btn-primary btn-block" disabled>Enroll</button>
                    </div>
                </div>
                <div class="row  pl-2 pb-0 align-items-center">
                    <label for="class-desc" class="col-sm-2 col-form-label">LRN</label>
                    <div id="enrollmentinfo_lrn" class="col-sm-4">
                        
                    </div>
                    {{-- <label for="class-desc" class="col-sm-2 col-form-label">DATE ENROLLED</label>
                    <div id="" class="col-sm-4">
                        -
                    </div> --}}
                </div>
                <div class="row  pl-2 pb-0 align-items-center">
                    <label for="class-desc" class="col-sm-2 col-form-label">ID NO.</label>
                    <div id="enrollmentinfo_sid" class="col-sm-9">
                        
                    </div>
                </div>
                <div class="row pl-2 pb-0 align-items-center">
                    <label for="class-desc" class="col-sm-2 col-form-label">NAME</label>
                    <div id="enrollmentinfo_studname" class="col-sm-6">
                        
                    </div>
                </div>
                <div id="enrollmentinfo_modal" class="modal-body pt-0" style="overflow-y: scroll;">
                    <form class="form-horizontal">
                        <div id="studinfo_card" class="card-body">
                            <div class="form-group row">
                                <label for="enrollmentinfo_syid" class="col-sm-3 col-form-label">School Year</label>
                                <div class="col-sm-9">
                                    <select id="enrollmentinfo_syid" class="select2 validation" value="" style="width: 100%;">
                                        @foreach(db::table('sy')->get() as $sy)
                                            @if($sy->isactive == 1)
                                                <option value="{{$sy->id}}" selected>{{$sy->sydesc}}</option>
                                            @else
                                                <option value="{{$sy->id}}">{{$sy->sydesc}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row college sh">
                                <label for="enrollmentinfo_semid" class="col-sm-3 col-form-label">Semester</label>
                                <div class="col-sm-9">
                                    <select id="enrollmentinfo_semid" class="select2 validation" value="" style="width: 100%;">
                                        @foreach(db::table('semester')->where('deleted', 0)->get() as $sem)
                                            @if($sem->isactive == 1)
                                                <option value="{{$sem->id}}" selected>{{$sem->semester}}</option>
                                            @else
                                                <option value="{{$sem->id}}">{{$sem->semester}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-desc" class="col-sm-3 col-form-label">Grade | Year Level</label>
                                <div class="col-sm-9">
                                    <select id="enrollmentinfo_levelid" class="select2 validation" value="" style="width: 100%;">
                                        @foreach(db::table('gradelevel')->where('deleted', 0)->orderBy('sortid')->get() as $l)
                                            <option value="{{$l->id}}">{{$l->levelname}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row college">
                                <label for="enrollmentinfo_course" class="col-sm-3 col-form-label">Course</label>
                                <div class="col-sm-9">
                                    <select id="enrollmentinfo_course" class="select2" value="" disabled style="width: 100%;">
                                        
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="enrollmentinfo_sectionid" class="col-sm-3 col-form-label">Section</label>
                                <div class="col-sm-9">
                                    <select id="enrollmentinfo_sectionid" class="select2 validation" value="" style="width: 100%;">
                                        
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row sh sh_nosem">
                                <label for="class-desc" class="col-sm-3 col-form-label">Strand</label>
                                <div class="col-sm-9">
                                    <select id="enrollmentinfo_strand" class="form-control" value="">
                                        
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row basic sh sh_nosem">
                                <label for="class-desc" class="col-sm-3 col-form-label">Grantee</label>
                                <div class="col-sm-9">
                                    <select id="enrollmentinfo_grantee" class="form-control" value="">
                                        <option value="1" selected>REGULAR</option>
                                        <option value="2">ESC</option>
                                        <option value="3">VOUCHER</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-desc" class="col-sm-3 col-form-label">Modality</label>
                                <div class="col-sm-9">
                                    <select id="enrollmentinfo_mol" class="form-control " value="">
                                        <option value=""></option>
                                        @foreach(db::table('modeoflearning')->where('deleted', 0)->get() as $mol)
                                            <option value="{{$mol->id}}">{{$mol->description}}</option>
                                        @endforeach
                                    </select>   
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-desc" class="col-sm-3 col-form-label">Student Type</label>
                                <div class="col-sm-9">
                                    <select id="enrollmentinfo_studtype" class="form-control select2">
                                        <option value="new">NEW</option>
                                        <option value="old">OLD</option>
                                        <option value="transferee">TRANSFEREE</option>
                                        <option value="returnee">RETURNEE</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-desc" class="col-sm-3 col-form-label">Student Status</label>
                                <div class="col-sm-5">
                                    <select id="enrollmentinfo_studstatus" class="form-control" value="">
                                        @foreach(db::table('studentstatus')->get() as $status)
                                            <option value="{{$status->id}}">{{$status->description}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <input id="enrollmentinfo_dateenrolled" type="date" name="" class="form-control">
                                </div>
                            </div>                            
                        </div>
                    </form>
                </div>
                {{-- <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="updateItem" type="button" class="btn btn-primary" data-dismiss="modal" data-id="">Save</button>
                </div> --}}
            </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade" id="modal-requirements" style="display: none;" aria-modal="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                  <h4 class="modal-title">Submitted Requirements</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                </div>
                <div class="modal-body">
                    <div class="row" id="req-img">
                        
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
    <!-- /.modal-dialog -->
    </div>


    @php
        $schoolinfo = db::table('schoolinfo')->first();
    @endphp  

  
@endsection

@section('js')
  
  <script type="text/javascript">

    var school_setup = @json($schoolinfo);

    function get_last_index(tablename){
        $.ajax({
            type:'GET',
            url: '/monitoring/tablecount',
            data:{
                tablename: tablename
            },
            success:function(data) {
                lastindex = data[0].lastindex
                        update_local_table_display(tablename,lastindex)
            },
        })
    }

    function update_local_table_display(tablename,lastindex){
        $.ajax({
            type:'GET',
            url: school_setup.es_cloudurl+'/monitoring/table/data',
            data:{
                tablename:tablename,
                tableindex:lastindex
            },
            success:function(data) {
                if(data.length > 0){
                    process_create(tablename,0,data)
                }
            },
            error:function(){
                $('td[data-tablename="'+tablename+'"]')[0].innerHTML = 'Error!'
            }
        })
    }

    function process_create(tablename,process_count,createdata){
        if(createdata.length == 0){        
            const Toast = Swal.mixin({
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 3000,
              timerProgressBar: true,
              didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
              }
            })

            Toast.fire({
              type: 'success',
              title: 'Save successfully'
            })
            return false;
        }
        
        var b = createdata[0]
        $.ajax({
            type:'GET',
            url: '/synchornization/insert',
            data:{
                tablename: tablename,
                data:b
            },
            success:function(data) {
                process_count += 1
                createdata = createdata.filter(x=>x.id != b.id)
                process_create(tablename,process_count,createdata)
            },
            error:function(){
                process_count += 1
                createdata = createdata.filter(x=>x.id != b.id)
                process_create(tablename,process_count,createdata)
            }
        })
    }
    

    $(document).ready(function(){
        var searchgo = '';
        loadstudents();

        $('.select2').select2({
            theme: 'bootstrap4'
        });

        screenadjust();

        function screenadjust()
        {
            var screen_height = $(window).height();

            $('#main_table').css('height', screen_height - 330);
            $('#studinfo_modal').css('height', screen_height - 150);
            $('#enrollmentinfo_modal').css('height', screen_height - 275);
            // $('.screen-adj').css('height', screen_height - 223);
        }

        function loadstudents(status2 = '')
        {
            var filter = $('#filter_search').val();
            var levelid = $('#filter_levelid').val();
            var status = $('#filter_status').val();
            var syid = $('#filter_syid').val();
            var semid = $('#filter_semid').val();

            $.ajax({
                url: '{{route('sm_loadstudents')}}',
                type: 'GET',
                dataType: 'json',
                data: {
                    filter:filter,
                    status:status,
                    status2:status2,
                    levelid:levelid,
                    syid:syid,
                    semid:semid
                },
                success:function(data)
                {
                    $('#sm_list').html(data.studlist);
                }
            });
            
        }

        function studinfo_clearinput()
        {
            $('.studinfo-input').val('');
            $('#studinfo_levelid').val(0);
            $('#studinfo_levelid').trigger('change');
            $('#studinfo_studtype').val('new')
            $('#studinfo_studtype').trigger('change');
            $('#studinfo_enrollmentinfo').prop('disabled', true);
            $('#studinfo_lesf').prop('disabled', true);
            $('#studinfo_dpstatus').text('NO DP');
            $('#studinfo_dpstatus').removeClass('bg-success');
            $('#studinfo_dpstatus').removeClass('bg-info');
            $('#studinfo_dpstatus').addClass('bg-default');
            $("#studinfo_dob").val('');
            $('#studinfo_district').val('');
            $('#studinfo_region').val('');
            $('#studinfo_firstname').trigger('keyup');
            $('#studinfo_lastname').trigger('keyup');
            $('#studinfo_dob').trigger('change');

            $('#studinfo_save').attr('data-id', 0);
            
        }

        function studinfo_checkinputs()
        {
            var count = 0;
            $('.validation').each(function(){
                if($(this).hasClass('is-invalid'))
                {
                    count += 1;
                }
            });

            if(count > 0)
            {
                $('#studinfo_save').prop('disabled', true)
            }
            else
            {
                $('#studinfo_save').prop('disabled', false)   
            }
        }

        var sstatus = @json(\Request::get('sstatus'));

        if(sstatus == 1)
        {
            $('#filter_status').val(sstatus).change();
            $('#filter_status').trigger('select2:close');
        }
        else if(sstatus == 2)
        {
            $('#filter_status').val(0).change();

            setTimeout(function(){
                $('#filter_ready').trigger('click');
            }, 500)
                
        }        
        else if(sstatus == 3)
        {
            $('#filter_status').val(0).change();

            setTimeout(function(){
                $('#filter_online').trigger('click');
            }, 500)
        }

        $(document).on('select2:close', '#filter_status', function(){
            loadstudents();
            $('.status2').removeClass('active');
        });

        $(document).on('select2:close', '#filter_levelid', function(){
            if($('#filter_ready').hasClass('active'))
            {
                searchgo = 'ready';
            }
            else if($('#filter_online').hasClass('active'))
            {
                searchgo = 'online';
            }
            else if($('#filter_notenrolled').hasClass('active'))
            {
                searchgo = 'notenrolled';
            }
            else
            {
                searchgo = '';
            }

            loadstudents(searchgo);
        });

        $(document).on('keyup', '#filter_search', function(){
            setTimeout(function(){
                loadstudents(searchgo);
            }, 500)
        })

        $(document).on('click', '#filter_ready', function(){
            console.log('aa');
            if($(this).hasClass('active'))
            {
                $(this).removeClass('active');
                searchgo = '';
            }
            else
            {
                $('.status2').removeClass('active');
                $(this).addClass('active');
                $('#filter_status').val(0);
                $('#filter_status').trigger('change');
                searchgo = 'ready';

                loadstudents(searchgo);
            }
        });

        $(document).on('click', '#filter_online', function(){
            if($(this).hasClass('active'))
            {
                $(this).removeClass('active');
                searchgo = '';
            }
            else
            {
                $('.status2').removeClass('active');
                $(this).addClass('active');
                $('#filter_status').val(0);
                $('#filter_status').trigger('change');
                searchgo = 'online';

                loadstudents(searchgo);
            }
        });

        $(document).on('click', '#filter_notenrolled', function(){
            if($(this).hasClass('active'))
            {
                $(this).removeClass('active');
                searchgo = '';
            }
            else
            {
                $('.status2').removeClass('active');
                $(this).addClass('active');
                $('#filter_status').val(0);
                $('#filter_status').trigger('change');
                searchgo = 'notenrolled';

                loadstudents(searchgo);
            }
        });

        $(document).on('change', '#filter_status', function(){
            if($(this).val() != '0')
            {
                $('.status2').removeClass('active');
            }
        });

        $(document).on('click', '#sm_list tr', function(){
            var dataid = $(this).attr('data-id');

            $('#enrollmentinfo_enrollstudent').attr('data-id', 0);

            if(dataid > 0)
            {
                $.ajax({
                    url: '{{route('sm_viewstud')}}',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        dataid:dataid
                    },
                    success:function(data)
                    {
                        $('#studinfo_lrn').val(data.lrn);
                        $('#studinfo_levelid').val(data.levelid);
                        $('#studinfo_levelid').trigger('change');
                        $('#studinfo_grantee').val(data.grantee);
                        $('#studinfo_course').val(data.courseid).change();
                        $('#studinfo_mol').val(data.mol);
                        $('#studinfo_studtype').val(data.studtype);
                        $('#studinfo_studtype').trigger('change');
                        $('#studinfo_firstname').val(data.firstname);
                        $('#studinfo_firstname').trigger('keyup');
                        $('#studinfo_middlename').val(data.middlename);
                        $('#studinfo_lastname').val(data.lastname);
                        $('#studinfo_lastname').trigger('keyup');
                        $('#studinfo_suffix').val(data.suffix);
                        $('#studinfo_dob').val(data.dob);
                        $('#studinfo_dob').trigger('change');
                        $('#studinfo_gender').val(data.gender);
                        $('#studinfo_contactno').val(data.contactno);
                        $('#studinfo_religion').val(data.religion);
                        $('#studinfo_mt').val(data.mt);
                        $('#studinfo_eg').val(data.eg);
                        $('#studinfo_nationality').val(data.nationality);

                        $('#studinfo_street').val(data.street);
                        $('#studinfo_barangay').val(data.barangay);
                        $('#studinfo_city').val(data.city);
                        $('#studinfo_province').val(data.province);
                        $('#studinfo_district').val(data.district);
                        $('#studinfo_region').val(data.region);

                        $('#studinfo_father_name').val(data.fathername);
                        $('#studinfo_father_occupation').val(data.fatheroccupation);
                        $('#studinfo_father_contactno').val(data.fathercontactno);

                        $('#studinfo_mother_name').val(data.mothername);
                        $('#studinfo_mother_occupation').val(data.motheroccupation);
                        $('#studinfo_mother_contactno').val(data.mothercontactno);

                        $('#studinfo_guardian_name').val(data.guardianname);
                        $('#studinfo_guardian_occupation').val(data.guardianoccupation);
                        $('#studinfo_guardian_contactno').val(data.guardiancontactno);


                        if(data.fatherdefault == 0)
                        {
                            $('#studinfo_father_default').prop('checked', false);
                        }
                        else
                        {
                            $('#studinfo_father_default').prop('checked', true);
                        }

                        if(data.motherdefault == 0)
                        {
                            $('#studinfo_mother_default').prop('checked', false);
                        }
                        else
                        {
                            $('#studinfo_mother_default').prop('checked', true);
                        }

                        if(data.guardiandefault == 0)
                        {
                            $('#studinfo_guardian_default').prop('checked', false);
                        }
                        else
                        {
                            $('#studinfo_guardian_default').prop('checked', true);
                        }

                        if(data.pantawid == 0)
                        {
                            $('#studinfo_4p').prop('checked', false);
                        }
                        else
                        {
                            $('#studinfo_4p').prop('checked', true);
                        }

                        if(data.dp == 'paid')
                        {
                            $('#studinfo_dpstatus').removeClass('bg-default');
                            $('#studinfo_dpstatus').addClass('bg-success');
                            $('#studinfo_dpstatus').text('DP PAID');
                        }
                        else
                        {
                            $('#studinfo_dpstatus').addClass('bg-default');
                            $('#studinfo_dpstatus').removeClass('bg-success');
                            $('#studinfo_dpstatus').text('NO DP');   
                        }

                        if(data.levelid >= 17 && data.levelid <= 20)
                        {
                            $('.div-grantee').hide();
                            $('.div-course').show();
                        }
                        else
                        {
                            $('.div-grantee').show();
                            $('.div-course').hide();
                        }

                        $('#studinfo_bt').val(data.bt);
                        $('#studinfo_allergy').val(data.allergy);
                        $('#studinfo_medoth').val(data.medoth);

                        $('#studinfo_lastschool').val(data.lastschool);
                        $('#studinfo_lastsy').val(data.lastsy);
                        $('#studinfo_rfid').val(data.rfid);

                        $('#studinfo_save').attr('data-id', dataid);
                        $('#studinfo_leaf').prop('disabled', false);
                        $('#studinfo_enrollmentinfo').prop('disabled', false);

                        $('#modal-studinfo').modal('show');
                    }
                });
            }
        });

        $(document).on('click', '#filter_create', function(){
            studinfo_clearinput();
            studinfo_checkinputs();
            $('#modal-studinfo').modal('show');
        });

        $(document).on('change', '#studinfo_levelid', function(){
            if($(this).val() != 0)
            {
                $(this).removeClass('is-invalid');
                $(this).addClass('is-valid');
            }
            else
            {
                $(this).addClass('is-invalid');
                $(this).removeClass('is-valid');   
            }
            studinfo_checkinputs();
        });

        $(document).on('keyup', '#studinfo_firstname', function(){
           if($(this).val() != '')
            {
                $(this).removeClass('is-invalid');
                $(this).addClass('is-valid');
            }
            else
            {
                $(this).addClass('is-invalid');
                $(this).removeClass('is-valid');   
            } 
            studinfo_checkinputs();
        })

        $(document).on('keyup', '#studinfo_lastname', function(){
           if($(this).val() != '')
            {
                $(this).removeClass('is-invalid');
                $(this).addClass('is-valid');
            }
            else
            {
                $(this).addClass('is-invalid');
                $(this).removeClass('is-valid');   
            } 
            studinfo_checkinputs();
        })

        $(document).on('change', '#studinfo_dob', function(){
           if($(this).val() != '')
            {
                $(this).removeClass('is-invalid');
                $(this).addClass('is-valid');
            }
            else
            {
                $(this).addClass('is-invalid');
                $(this).removeClass('is-valid');   
            } 
            studinfo_checkinputs();
        });

        $(document).on('click', '#studinfo_save', function(){
            var dataid = $(this).attr('data-id');
            if($('#studinfo_father_default').prop('checked') == true)
            {
                var fatherdefault = 1;
            }
            else
            {
                var fatherdefault = 0;
            }

            if($('#studinfo_mother_default').prop('checked') == true)
            {
                var motherdefault = 1;
            }
            else
            {
                var motherdefault = 0;
            }

            if($('#studinfo_guardian_default').prop('checked') == true)
            {
                var guardiandefault = 1;
            }
            else
            {
                var guardiandefault = 0;
            } 

            if($('#studinfo_4p').prop('checked') == true)
            {
                var pantawid = 1;
            }
            else
            {
                var pantawid = 0;
            }

            studinfo_registerurl = '{{db::table('schoolinfo')->first()->es_cloudurl}}';
            regURL = '';
            regstyle = '{{db::table('schoolinfo')->first()->studinfo_crud}}';

            if(regstyle == 'online')
            {
                if(dataid == 0)
                {
                    regURL = studinfo_registerurl + 'api/student_insert';
                }
                else
                {
                    regURL = 'sm_savestudent';       
                }
            }
            else
            {
                regURL = 'sm_savestudent';
            }

            $.ajax({
                url: regURL,
                type: 'GET',
                dataType: 'json',
                data: {
                    dataid:dataid,
                    lrn:$('#studinfo_lrn').val(),
                    levelid:$('#studinfo_levelid').val(),
                    grantee:$('#studinfo_grantee').val(),
                    modality:$('#studinfo_mol').val(),
                    studtype:$('#studinfo_studtype').val(),
                    firstname:$('#studinfo_firstname').val(),
                    middlename:$('#studinfo_middlename').val(),
                    lastname:$('#studinfo_lastname').val(),
                    suffix:$('#studinfo_suffix').val(),
                    dob:$('#studinfo_dob').val(),
                    gender:$('#studinfo_gender').val(),
                    contactno:$('#studinfo_contactno').val(),
                    religion:$('#studinfo_religion').val(),
                    mt:$('#studinfo_mt').val(),
                    eg:$('#studinfo_eg').val(),
                    nationality:$('#studinfo_nationality').val(),
                    street:$('#studinfo_street').val(),
                    barangay:$('#studinfo_barangay').val(),
                    city:$('#studinfo_city').val(),
                    province:$('#studinfo_province').val(),
                    fathername:$('#studinfo_father_name').val(),
                    fatheroccupation:$('#studinfo_father_occupation').val(),
                    fathercontactno:$('#studinfo_father_contactno').val(),
                    fatherdefault:fatherdefault,
                    mothername:$('#studinfo_mother_name').val(),
                    motheroccupation:$('#studinfo_mother_occupation').val(),
                    mothercontactno:$('#studinfo_mother_contactno').val(),
                    motherdefault:motherdefault,
                    guardianname:$('#studinfo_guardian_name').val(),
                    guardianrelation:$('#studinfo_guardian_relation').val(),
                    guardiancontactno:$('#studinfo_guardian_contactno').val(),
                    guardiandefault:guardiandefault,
                    bt:$('#studinfo_bt').val(),
                    allergy:$('#studinfo_allergy').val(),
                    medoth:$('#studinfo_medoth').val(),
                    lastschool:$('#studinfo_lastschool').val(),
                    lastsy:$('#studinfo_lastsy').val(),
                    rfid:$('#studinfo_rfid').val(),
                    pantawid:pantawid,
                    courseid:$('#studinfo_course').val(),
                    district:$('#studinfo_district').val(),
                    region:$('#studinfo_region').val()
                },
                success:function(data)
                {
                    if(data.return == 'done')
                    {


                        // if(regstyle == 'online')
                        // {
                        //     studid = data.studid;
                        //     cloudsid = data.sid;
                        //     $('#studinfo_save').attr('data-id', data.studid);  
                        //     $('#studinfo_lesf').prop('disabled', false);
                        //     $('#studinfo_enrollmentinfo').prop('disabled', false);

                        //     $.ajax({
                        //         url: '{route('sm_savestudent')}}',
                        //         type: 'GET',
                        //         dataType: 'json',
                        //         data: {
                        //             dataid:dataid,
                        //             studid:studid,
                        //             cloudsid:cloudsid,
                        //             lrn:$('#studinfo_lrn').val(),
                        //             levelid:$('#studinfo_levelid').val(),
                        //             grantee:$('#studinfo_grantee').val(),
                        //             modality:$('#studinfo_mol').val(),
                        //             studtype:$('#studinfo_studtype').val(),
                        //             firstname:$('#studinfo_firstname').val(),
                        //             middlename:$('#studinfo_middlename').val(),
                        //             lastname:$('#studinfo_lastname').val(),
                        //             suffix:$('#studinfo_suffix').val(),
                        //             dob:$('#studinfo_dob').val(),
                        //             gender:$('#studinfo_gender').val(),
                        //             contactno:$('#studinfo_contactno').val(),
                        //             religion:$('#studinfo_religion').val(),
                        //             mt:$('#studinfo_mt').val(),
                        //             eg:$('#studinfo_eg').val(),
                        //             nationality:$('#studinfo_nationality').val(),
                        //             street:$('#studinfo_street').val(),
                        //             barangay:$('#studinfo_barangay').val(),
                        //             city:$('#studinfo_city').val(),
                        //             province:$('#studinfo_province').val(),
                        //             fathername:$('#studinfo_father_name').val(),
                        //             fatheroccupation:$('#studinfo_father_occupation').val(),
                        //             fathercontactno:$('#studinfo_father_contactno').val(),
                        //             fatherdefault:fatherdefault,
                        //             mothername:$('#studinfo_mother_name').val(),
                        //             motheroccupation:$('#studinfo_mother_occupation').val(),
                        //             mothercontactno:$('#studinfo_mother_contactno').val(),
                        //             motherdefault:motherdefault,
                        //             guardianname:$('#studinfo_guardian_name').val(),
                        //             guardianrelation:$('#studinfo_guardian_relation').val(),
                        //             guardiancontactno:$('#studinfo_guardian_contactno').val(),
                        //             guardiandefault:guardiandefault,
                        //             bt:$('#studinfo_bt').val(),
                        //             allergy:$('#studinfo_allergy').val(),
                        //             medoth:$('#studinfo_medoth').val(),
                        //             lastschool:$('#studinfo_lastschool').val(),
                        //             lastsy:$('#studinfo_lastsy').val(),
                        //             rfid:$('#studinfo_rfid').val(),
                        //             pantawid:pantawid,
                        //             courseid:$('#studinfo_course').val(),
                        //             district:$('#studinfo_district').val(),
                        //             region:$('#studinfo_region').val()      
                        //         },
                        //         success:function(data)
                        //         {
                        //             loadstudents(searchgo);

                        //             const Toast = Swal.mixin({
                        //               toast: true,
                        //               position: 'top-end',
                        //               showConfirmButton: false,
                        //               timer: 3000,
                        //               timerProgressBar: true,
                        //               didOpen: (toast) => {
                        //                 toast.addEventListener('mouseenter', Swal.stopTimer)
                        //                 toast.addEventListener('mouseleave', Swal.resumeTimer)
                        //               }
                        //             })

                        //             Toast.fire({
                        //               type: 'success',
                        //               title: 'Save successfully'
                        //             })  
                        //         }
                        //     });
                        // }

                        if(regstyle == 'online')
                        {
                            if(dataid == 0)
                            {
                                get_last_index('studinfo');
                            }
                            else
                            {
                                const Toast = Swal.mixin({
                                  toast: true,
                                  position: 'top-end',
                                  showConfirmButton: false,
                                  timer: 3000,
                                  timerProgressBar: true,
                                  didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer)
                                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                                  }
                                })

                                Toast.fire({
                                  type: 'success',
                                  title: 'Save successfully'
                                })  
                            }
                        }
                        else
                        {
                            loadstudents(searchgo);

                            const Toast = Swal.mixin({
                              toast: true,
                              position: 'top-end',
                              showConfirmButton: false,
                              timer: 3000,
                              timerProgressBar: true,
                              didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                              }
                            })

                            Toast.fire({
                              type: 'success',
                              title: 'Save successfully'
                            })  
                        }
                    }
                    else if(data.return == 'lacking')
                    {
                        const Toast = Swal.mixin({
                          toast: true,
                          position: 'top-end',
                          showConfirmButton: false,
                          timer: 3000,
                          timerProgressBar: true,
                          didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                          }
                        })

                        Toast.fire({
                          type: 'warning',
                          title: 'Please fill up all required fields'
                        })   
                    }
                    else
                    {
                        const Toast = Swal.mixin({
                          toast: true,
                          position: 'top-end',
                          showConfirmButton: false,
                          timer: 3000,
                          timerProgressBar: true,
                          didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                          }
                        })

                        Toast.fire({
                          type: 'error',
                          title: 'Student Existed'
                        })
                    }
                },
                error:function()
                {
                    const Toast = Swal.mixin({
                      toast: true,
                      position: 'top-end',
                      showConfirmButton: false,
                      timer: 3000,
                      timerProgressBar: true,
                      didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                      }
                    })

                    Toast.fire({
                      type: 'error',
                      title: 'Something went wrong'
                    })
                }
            });
        });

        $(document).on('click', '#studinfo_enrollmentinfo', function(){
            var dataid = $('#studinfo_save').attr('data-id');
            var syid = $('#enrollmentinfo_syid').val();
            var semid = $('#enrollmentinfo_semid').val();

            $.ajax({
                url: '{{route('sm_loadenrollmentinfo')}}',
                type: 'GET',
                dataType: 'json',
                data: {
                    dataid:dataid,
                    syid:syid,
                    semid:semid
                },
                success:function(data)
                {
                    
                    $('#enrollmentinfo_studname').text(data.name);
                    $('#enrollmentinfo_lrn').text(data.lrn);
                    $('#enrollmentinfo_sid').text(data.sid);
                    $('#enrollmentinfo_levelid').val(data.levelid);
                    $('#enrollmentinfo_levelid').trigger('change');
                    $('#enrollmentinfo_grantee').val(data.grantee);
                    $('#enrollmentinfo_mol').val(data.mol);
                    $('#enrollmentinfo_studtype').val(data.studtype);
                    $('#enrollmentinfo_studtype').trigger('change');
                    $('#enrollmentinfo_sectionid').html(data.sectionlist);
                    $('#enrollmentinfo_course').html(data.courselist);
                    $('#enrollmentinfo_enrollstudent').attr('data-id', data.studid);
                    $('#enrollmentinfo_enrollstudent').attr('data-enroll', data.enrollid);
                    $('#enrollmentinfo_dateenrolled').val(data.dateenrolled);
                    $('#enrollmentinfo_strand').html(data.strandlist);
                    $('#enrollmentinfo_enrollstudent').attr('curlevelid', data.levelid);

                    // console.log('sectionid' + data.sectionid)
                    console.log($('#enrollmentinfo_sectionid').val());

                    if(data.acad == 'basic')
                    {
                        $('.college').hide();
                        $('.sh').hide();
                        $('.basic').show();
                        $('#enrollmentinfo_sectionid').prop('disabled', false);
                    }
                    else if(data.acad == 'sh')
                    {
                        $('.college').hide();
                        $('.basic').hide();
                        $('.sh').show();
                        $('#enrollmentinfo_sectionid').prop('disabled', false);
                    }
                    else if(data.acad == 'sh_nosem')
                    {
                        $('.college').hide();
                        $('.sh_nosem').show();
                        $('#enrollmentinfo_sectionid').prop('disabled', false);

                    }
                    else if(data.acad == 'college')
                    {
                        $('.sh').hide();
                        $('.basic').hide();
                        $('.college').show();
                        $('#enrollmentinfo_sectionid').prop('disabled', true);
                    }

                    if(data.dp == 'paid')
                    {
                        $('#enrollmentinfo_dpstatus').removeClass('bg-default');
                        $('#enrollmentinfo_dpstatus').addClass('bg-success');
                        $('#enrollmentinfo_dpstatus').text('DP PAID');
                        $('#enrollmentinfo_dpstatus').addClass('dp-1');
                        $('#enrollmentinfo_enrollstudent').prop('disabled', false);
                    }
                    else if(data.dp == 'allownodp')
                    {
                        $('#enrollmentinfo_dpstatus').removeClass('bg-default');
                        $('#enrollmentinfo_dpstatus').addClass('bg-success');
                        $('#enrollmentinfo_dpstatus').text('Allow no DP');   
                        $('#enrollmentinfo_dpstatus').addClass('dp-1');
                        $('#enrollmentinfo_enrollstudent').prop('disabled', false);
                    }
                    else
                    {
                        $('#enrollmentinfo_dpstatus').addClass('bg-default');
                        $('#enrollmentinfo_dpstatus').removeClass('bg-success');
                        $('#enrollmentinfo_dpstatus').text('No DP');
                        $('#enrollmentinfo_dpstatus').removeClass('dp-1');
                        $('#enrollmentinfo_enrollstudent').prop('disabled', true);
                    }

                    if(data.studstatus == 0)
                    {
                        $('#enrollmentinfo_studstatus').prop('disabled', true);
                        $('#enrollmentinfo_dateenrolled').prop('disabled', true);
                        $('#enrollmentinfo_studstatus').val(0);
                        $('#enrollmentinfo_semid').prop('disabled', false);
                        $('#enrollmentinfo_syid').prop('disabled', false);

                        if(data.sectionid == '' || data.sectionid == 0)
                        {
                            onchangeactive = 0;

                            $('#enrollmentinfo_sectionid').val($('#enrollmentinfo_sectionid option:eq(0)').val()).change();

                            setTimeout(function(){
                                onchangeactive = 1
                            }, 2000)
                            // $('#studinfo_enrollmentinfo').trigger('click');
                        }
                        else
                        {
                            $('#enrollmentinfo_sectionid').val(data.sectionid).change();
                        }

                        if(data.strandid > 0)
                        {
                            $('#enrollmentinfo_strand').val(data.strandid);
                        }
                    }
                    else
                    {
                        if(data.acad == 'basic')
                        {
                            $('#enrollmentinfo_sectionid').val(data.sectionid);
                            $('#enrollmentinfo_sectionid').trigger('change');
                        }
                        else if(data.acad == 'sh_nosem')
                        {
                            $('#enrollmentinfo_sectionid').val(data.sectionid);
                            $('#enrollmentinfo_sectionid').trigger('change');   
                            $('#enrollmentinfo_strand').val(data.strandid);
                        }

                        $('#enrollmentinfo_studstatus').val(data.studstatus);
                        $('#enrollmentinfo_studstatus').prop('disabled', false);
                        $('#enrollmentinfo_dateenrolled').prop('disabled', false);
                        $('#enrollmentinfo_enrollstudent').prop('disabled', true);
                        $('#enrollmentinfo_semid').prop('disabled', true);
                        $('#enrollmentinfo_syid').prop('disabled', true);

                    }

                    strandcheck();
                    
                    // $('#modal-studinfo').modal('hide');
                    $('#modal-enrollmentinfo').modal('show');
                }
            });
        });

        $(document).on('click', '#enrollmentinfo_enrollstudent', function(){
            
            // if(if($('#enrollmentinfo_levelid').val() >= 17 && $('#enrollmentinfo_levelid').val() <= 20) && )

            if($('#enrollmentinfo_dpstatus').hasClass('dp-1'))
            {
                var studid = $(this).attr('data-id');
                var levelid = $('#enrollmentinfo_levelid').val();
                var grantee = $('#enrollmentinfo_grantee').val();
                var mol = $('#enrollmentinfo_mol').val();
                var studtype = $('#enrollmentinfo_studtype').val();
                var sectionid = $('#enrollmentinfo_sectionid').val();
                var strand = $('#enrollmentinfo_strand').val();
                var syid = $('#enrollmentinfo_syid').val();
                var semid = $('#enrollmentinfo_semid').val();

                if($('#enrollmentinfo_levelid').val() == 14 || $('#enrollmentinfo_levelid').val() == 15)
                {
                    if($('#enrollmentinfo_strand').hasClass('is-invalid'))
                    {
                        const Toast = Swal.mixin({
                          toast: true,
                          position: 'top-end',
                          showConfirmButton: false,
                          timer: 3000,
                          timerProgressBar: true,
                          didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                          }
                        })

                        Toast.fire({
                          type: 'error',
                          title: 'Please fill all the required field(s)'
                        })
                    }
                    else
                    {
                        Swal.fire({
                            title: 'Enroll student?',
                            text: "Student Name: " + $('#enrollmentinfo_studname').text(),
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Enroll'
                        }).then((result) => {
                            
                            if (result.value == true) {
                                $.ajax({
                                    url: '{{route('sm_enrollstudent')}}',
                                    type: 'GET',
                                    dataType: 'json',
                                    data: {
                                        studid:studid,
                                        levelid:levelid,
                                        grantee:grantee,
                                        mol:mol,
                                        studtype:studtype,
                                        sectionid:sectionid,
                                        strand:strand,
                                        syid:syid,
                                        semid:semid
                                    },
                                    success:function(data)
                                    {
                                        if(data.return == 'done')
                                        {
                                            $('#enrollmentinfo_enrollstudent').prop('disabled', true);
                                            $('#enrollmentinfo_dpstatus').removeClass('dp-1');
                                            $('#enrollmentinfo_studstatus').prop('disabled', false);

                                            $('#studinfo_enrollmentinfo').trigger('click');

                                            var feesid = data.feesid;

                                            const Toast = Swal.mixin({
                                              toast: true,
                                              position: 'top-end',
                                              showConfirmButton: false,
                                              timer: 3000,
                                              timerProgressBar: true,
                                              didOpen: (toast) => {
                                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                                              }
                                            })

                                            Toast.fire({
                                              type: 'success',
                                              title: 'Student successfully enrolled'
                                            })

                                            $.ajax({
                                                url: '/api_updateledger',
                                                type: 'GET',
                                                data: {
                                                    studid:studid,
                                                    syid:syid,
                                                    semid:semid,
                                                    feesid:feesid
                                                },
                                                success:function(data)
                                                {

                                                }
                                            });
                                            
                                        }
                                        else
                                        {
                                            const Toast = Swal.mixin({
                                              toast: true,
                                              position: 'top-end',
                                              showConfirmButton: false,
                                              timer: 3000,
                                              timerProgressBar: true,
                                              didOpen: (toast) => {
                                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                                              }
                                            })

                                            Toast.fire({
                                              type: 'error',
                                              title: 'Something went wrong'
                                            })
                                        }

                                        loadstudents(searchgo);
                                    }
                                });        
                            }
                        })
                    }
                }
                else if($('#enrollmentinfo_levelid').val() >= 17 || $('#enrollmentinfo_levelid').val() <= 20)
                {
                    if($('#enrollmentinfo_sectionid').val() != null)
                    {
                        Swal.fire({
                            title: 'Enroll student?',
                            text: "Student Name: " + $('#enrollmentinfo_studname').text(),
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Enroll'
                        }).then((result) => {
                            
                            if (result.value == true) {
                                $.ajax({
                                    url: '{{route('sm_enrollstudent')}}',
                                    type: 'GET',
                                    dataType: 'json',
                                    data: {
                                        studid:studid,
                                        levelid:levelid,
                                        grantee:grantee,
                                        mol:mol,
                                        studtype:studtype,
                                        sectionid:sectionid,
                                        strand:strand,
                                        syid:syid,
                                        semid:semid
                                    },
                                    success:function(data)
                                    {
                                        if(data.return == 'done')
                                        {
                                            $('#enrollmentinfo_enrollstudent').prop('disabled', true);
                                            $('#enrollmentinfo_dpstatus').removeClass('dp-1');
                                            $('#enrollmentinfo_studstatus').prop('disabled', false);

                                            $('#studinfo_enrollmentinfo').trigger('click');

                                            var feesid = data.feesid;

                                            const Toast = Swal.mixin({
                                              toast: true,
                                              position: 'top-end',
                                              showConfirmButton: false,
                                              timer: 3000,
                                              timerProgressBar: true,
                                              didOpen: (toast) => {
                                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                                              }
                                            })

                                            Toast.fire({
                                              type: 'success',
                                              title: 'Student successfully enrolled'
                                            })

                                            $.ajax({
                                                url: '/api_updateledger',
                                                type: 'GET',
                                                data: {
                                                    studid:studid,
                                                    syid:syid,
                                                    semid:semid,
                                                    feesid:feesid
                                                },
                                                success:function(data)
                                                {

                                                }
                                            });
                                            
                                        }
                                        else
                                        {
                                            const Toast = Swal.mixin({
                                              toast: true,
                                              position: 'top-end',
                                              showConfirmButton: false,
                                              timer: 3000,
                                              timerProgressBar: true,
                                              didOpen: (toast) => {
                                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                                              }
                                            })

                                            Toast.fire({
                                              type: 'error',
                                              title: 'Something went wrong'
                                            })
                                        }

                                        loadstudents(searchgo);
                                    }
                                });        
                            }
                        })
                    }
                    else
                    {
                        const Toast = Swal.mixin({
                          toast: true,
                          position: 'top-end',
                          showConfirmButton: false,
                          timer: 3000,
                          timerProgressBar: true,
                          didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                          }
                        })

                        Toast.fire({
                          type: 'error',
                          title: 'No Section. Please refer to subject loading'
                        })
                    }
                }
                else
                {
                    Swal.fire({
                        title: 'Enroll student?',
                        text: "Student Name: " + $('#enrollmentinfo_studname').text(),
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Enroll'
                    }).then((result) => {
                        
                        if (result.value == true) {
                            $.ajax({
                                url: '{{route('sm_enrollstudent')}}',
                                type: 'GET',
                                dataType: 'json',
                                data: {
                                    studid:studid,
                                    levelid:levelid,
                                    grantee:grantee,
                                    mol:mol,
                                    studtype:studtype,
                                    sectionid:sectionid,
                                    strand:strand,
                                    syid:syid,
                                    semid:semid
                                },
                                success:function(data)
                                {
                                    if(data.return == 'done')
                                    {
                                        $('#enrollmentinfo_enrollstudent').prop('disabled', true);
                                        $('#enrollmentinfo_dpstatus').removeClass('dp-1');
                                        $('#enrollmentinfo_studstatus').prop('disabled', false);

                                        $('#studinfo_enrollmentinfo').trigger('click');

                                        var feesid = data.feesid;

                                        const Toast = Swal.mixin({
                                          toast: true,
                                          position: 'top-end',
                                          showConfirmButton: false,
                                          timer: 3000,
                                          timerProgressBar: true,
                                          didOpen: (toast) => {
                                            toast.addEventListener('mouseenter', Swal.stopTimer)
                                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                                          }
                                        })

                                        Toast.fire({
                                          type: 'success',
                                          title: 'Student successfully enrolled'
                                        })

                                        $.ajax({
                                            url: '/api_updateledger',
                                            type: 'GET',
                                            data: {
                                                studid:studid,
                                                syid:syid,
                                                semid:semid,
                                                feesid:feesid
                                            },
                                            success:function(data)
                                            {

                                            }
                                        });
                                        
                                    }
                                    else
                                    {
                                        const Toast = Swal.mixin({
                                          toast: true,
                                          position: 'top-end',
                                          showConfirmButton: false,
                                          timer: 3000,
                                          timerProgressBar: true,
                                          didOpen: (toast) => {
                                            toast.addEventListener('mouseenter', Swal.stopTimer)
                                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                                          }
                                        })

                                        Toast.fire({
                                          type: 'error',
                                          title: 'Something went wrong'
                                        })
                                    }

                                    loadstudents(searchgo);
                                }
                            });        
                        }
                    })
                }
            }
            else
            {
                const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 3000,
                  timerProgressBar: true,
                  didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                  }
                })

                Toast.fire({
                  type: 'error',
                  title: 'Student not qualified to enroll'
                })
            }
        })
        
        $(document).on('select2:close', '#studinfo_levelid', function(){
            if($(this).val() >= 17 && $(this).val() <= 20)
            {
                console.log($(this).val());
                $('.div-grantee').hide();
                $('.div-course').show();
            }
            else
            {
                $('.div-grantee').show();
                $('.div-course').hide();   
            }
        });

        var onchangeactive = 0;

        $(document).on('change', '#enrollmentinfo_studstatus', function(){
            var studstatus = $(this).val();
            var levelid = $('#enrollmentinfo_levelid').val();
            var enrollid = $('#enrollmentinfo_enrollstudent').attr('data-enroll');
            var studid = $('#enrollmentinfo_enrollstudent').attr('data-id');

            $.ajax({
                url: '{{route('sm_update_studstatus')}}',
                type: 'GET',
                data: {
                    studstatus:studstatus,
                    levelid:levelid,
                    enrollid:enrollid,
                    studid:studid
                },
                success:function(data)
                {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })

                    Toast.fire({
                        type: 'success',
                        title: 'Student Status Saved'
                    });

                    $('#filter_status').trigger('select2:close');
                    // $('#studinfo_enrollmentinfo').trigger('click');
                }
            });
        });

        $(document).on('change', '#enrollmentinfo_dateenrolled', function(){
            var levelid = $('#enrollmentinfo_levelid').val();
            var enrollid = $('#enrollmentinfo_enrollstudent').attr('data-enroll');
            var studid = $('#enrollmentinfo_enrollstudent').attr('data-id');
            var studstatdate = $(this).val();

            $.ajax({
                url: '{{route('sm_update_studstatdate')}}',
                type: 'GET',
                data: {
                    levelid:levelid,
                    enrollid:enrollid,
                    studid:studid,
                    studstatdate:studstatdate
                },
                success:function(data)
                {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })

                    Toast.fire({
                        type: 'success',
                        title: 'Saved'
                    });

                    // $('#studinfo_enrollmentinfo').trigger('click');
                }
            });
        });

        $(document).on('change', '#enrollmentinfo_studtype', function(){
            var levelid = $('#enrollmentinfo_levelid').val();
            var enrollid = $('#enrollmentinfo_enrollstudent').attr('data-enroll');
            var studid = $('#enrollmentinfo_enrollstudent').attr('data-id');
            var studtype = $(this).val();

            $.ajax({
                url: '{{route('sm_update_studtype')}}',
                type: 'GET',
                data: {
                    levelid:levelid,
                    enrollid:enrollid,
                    studid:studid,
                    studtype:studtype
                },
                success:function()
                {
                    if(onchangeactive == 1)
                    {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        })

                        Toast.fire({
                            type: 'success',
                            title: 'Saved'
                        });

                        $('#studinfo_studtype').val(studtype).change();
                    }
                }
            });
            
        });

        $(document).on('change', '#enrollmentinfo_mol', function(){
            var levelid = $('#enrollmentinfo_levelid').val();
            var enrollid = $('#enrollmentinfo_enrollstudent').attr('data-enroll');
            var studid = $('#enrollmentinfo_enrollstudent').attr('data-id');
            var mol = $(this).val();

            $.ajax({
                url: '{{route('sm_update_mol')}}',
                type: 'GET',
                data: {
                    levelid:levelid,
                    enrollid:enrollid,
                    studid:studid,
                    mol:mol
                },
                success:function()
                {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })

                    Toast.fire({
                        type: 'success',
                        title: 'Saved'
                    });

                    $('#studinfo_mol').val(mol);
                }
            });
        });

        $(document).on('change', '#enrollmentinfo_grantee', function(){
            var levelid = $('#enrollmentinfo_levelid').val();
            var enrollid = $('#enrollmentinfo_enrollstudent').attr('data-enroll');
            var studid = $('#enrollmentinfo_enrollstudent').attr('data-id');
            var grantee = $(this).val();
            var syid = $('#enrollmentinfo_syid').val();
            var semid = $('#enrollmentinfo_semid').val()

            $.ajax({
                url: '{{route('sm_update_grantee')}}',
                type: 'GET',
                data: {
                    levelid:levelid,
                    enrollid:enrollid,
                    studid:studid,
                    grantee:grantee,
                    syid:syid,
                    semid:semid
                },
                success:function(data)
                {
                    console.log(data);
                    if(data != 0)
                    {
                        feesid = data;
                        $.ajax({
                            url: '/api_updateledger',
                            type: 'GET',
                            data: {
                                studid:studid,
                                syid:syid,
                                semid:semid,
                                feesid:feesid
                            },
                            success:function(data)
                            {

                            }
                        });   
                    }

                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })

                    Toast.fire({
                        type: 'success',
                        title: 'Saved'
                    });
                }
            });
        });

        $(document).on('change', '#enrollmentinfo_levelid', function(){
            var levelid = $('#enrollmentinfo_levelid').val();
            var enrollid = $('#enrollmentinfo_enrollstudent').attr('data-enroll');
            var studid = $('#enrollmentinfo_enrollstudent').attr('data-id');
            var dateenrolled = $('#enrollmentinfo_dateenrolled').val();
            var syid = $('#enrollmentinfo_syid').val();
            var semid = $('#enrollmentinfo_semid').val();
            var curlevelid = $('#enrollmentinfo_enrollstudent').attr('curlevelid');

            if(onchangeactive == 1)
            {
                $.ajax({
                    url: '{{route('sm_update_level')}}',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        levelid:levelid,
                        enrollid:enrollid,
                        studid:studid,
                        syid:syid,
                        semid:semid,
                        curlevelid:curlevelid
                    },
                    success:function(data)
                    {
                        if(data.return == 'done')
                        {
                            if(data.acad == 'basic')
                            {
                                $('.college').hide();
                                $('.sh').hide();
                                $('.basic').show();
                                $('#enrollmentinfo_sectionid').prop('disabled', false);
                            }
                            else if(data.acad == 'sh')
                            {
                                $('.college').hide();
                                $('.basic').hide();
                                $('#enrollmentinfo_sectionid').prop('disabled', false);
                            }
                            else if(data.acad == 'sh_nosem')
                            {
                                $('.college').hide();
                                $('#enrollmentinfo_sectionid').prop('disabled', false);

                            }
                            else if(data.acad == 'college')
                            {
                                $('.sh').hide();
                                $('.basic').hide();
                                $('.college').show();
                                $('#enrollmentinfo_sectionid').prop('disabled', true);
                            }

                            $.ajax({
                                url: '/api_updateledger',
                                type: 'GET',
                                data: {
                                    studid:studid,
                                    syid:syid,
                                    semid:semid,
                                    feesid:data.feesid
                                },
                                success:function(data)
                                {

                                }
                            });

                            $('#studinfo_enrollmentinfo').trigger('click');
                        }
                        else if(data.return == 'not allowed')
                        {
                            $('#enrollmentinfo_levelid').val(curlevelid);
                            $('#enrollmentinfo_levelid').trigger('change');

                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer)
                                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                                }
                            })

                            Toast.fire({
                                type: 'error',
                                title: 'Not allowed to change grade level'
                            });
                        }
                    }
                });
            }
        });

        $(document).on('hidden.bs.modal', '#modal-enrollmentinfo', function(){
            onchangeactive = 0;
            console.log('changeActive: ' + onchangeactive);
        })

        $(document).on('show.bs.modal', '#modal-enrollmentinfo', function(){
            setTimeout(function(){
                onchangeactive = 1;
                console.log('changeActive: ' + onchangeactive);
            }, 1000)
        });

        $(document).on('change', '#enrollmentinfo_strand', function(){
            var levelid = $('#enrollmentinfo_levelid').val();
            var enrollid = $('#enrollmentinfo_enrollstudent').attr('data-enroll');
            var studid = $('#enrollmentinfo_enrollstudent').attr('data-id');
            var strandid = $(this).val();

            strandcheck();
            if(onchangeactive == 1)
            {
                $.ajax({
                    url: '{{route('sm_update_strand')}}',
                    type: 'GET',
                    data: {
                        levelid:levelid,
                        enrollid:enrollid,
                        studid:studid,
                        strandid:strandid
                    },
                    success:function(data)
                    {
                        if(data == 'done')
                        {
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer)
                                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                                }
                            })

                            Toast.fire({
                                type: 'success',
                                title: 'Saved'
                            });
                        }
                    }
                }); 
            }
        });

        function strandcheck()
        {
            console.log('strand: ' + $('#enrollmentinfo_strand').val());
            if($('#enrollmentinfo_strand').val() > 0 && $('#enrollmentinfo_strand').val() != '')
            {
                $('#enrollmentinfo_strand').addClass('is-valid');
                $('#enrollmentinfo_strand').removeClass('is-invalid');
            }
            else
            {
                $('#enrollmentinfo_strand').removeClass('is-valid');
                $('#enrollmentinfo_strand').addClass('is-invalid');
            }
        }

        $(document).on('change', '#enrollmentinfo_sectionid', function(){
            var levelid = $('#enrollmentinfo_levelid').val();
            var enrollid = $('#enrollmentinfo_enrollstudent').attr('data-enroll');
            var studid = $('#enrollmentinfo_enrollstudent').attr('data-id');
            var sectionid = $(this).val();

            console.log(enrollid);

            
            
            if(onchangeactive == 1)
            {
                $.ajax({
                    url: '{{route('sm_update_section')}}',
                    type: 'GET',
                    data: {
                        levelid:levelid,
                        enrollid:enrollid,
                        studid:studid,
                        sectionid:sectionid
                    },
                    success:function(data)
                    {
                        if(data == 'done')
                        {
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer)
                                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                                }
                            })

                            Toast.fire({
                                type: 'success',
                                title: 'Saved'
                            });

                            onchangeactive = 0;

                            $('#studinfo_enrollmentinfo').trigger('click');

                            setTimeout(function(){
                                onchangeactive = 1
                            }, 2000)
                        }
                    }
                });
            }
        });

        $(document).on('click', '#enrollmentinfo_requirements', function(){
            var studid = $('#enrollmentinfo_enrollstudent').attr('data-id');
            $.ajax({
                url:"{{ route('viewreq') }}",
                method:'GET',
                data:{
                    studid:studid
                },
                dataType:'json',
                success:function(data)
                {
                    console.log('test');
                    $('#req-img').html(data.list);
                    $('#modal-requirements').modal('show');         
                }
            });
        });

        $(document).on('click', '#enrollmentinfo_cor', function(){
            var syid = $('#enrollmentinfo_syid').val();
            var semid = $('#enrollmentinfo_semid').val();
            var studid = $('#enrollmentinfo_enrollstudent').attr('data-id');
            var date = '{{date_format(date_create(App\RegistrarModel::getServerDateTime()), 'Y-m-d')}}'


            // window.open('printable/certification/generate?export=pdf&template=jhs&syid='+syid+'&studid='+studid+'&givendate='+date+'&schoolregistrar=', '_blank');
            window.open('/printcor/'+studid+'?syid='+syid+'&semid='+semid+'&studid='+studid, '_blank');
        });

        $(document).on('click', '#studinfo_leaf', function(){
            var studid = $('#studinfo_save').attr('data-id');
            var syid = '{{App\RegistrarModel::getSYID()}}'

            window.open('/registrar/leaf?syid='+syid+'&studid=' + studid);
        })
		
		$(document).on('click', '#studinfo_print', function(){
            var studid = $('#studinfo_save').attr('data-id');

            window.open('/registrar/studentinfo/print?studid='+studid, '_blank');
        })

    });

  </script>
  
@endsection