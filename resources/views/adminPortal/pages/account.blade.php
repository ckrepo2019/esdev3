@php

      if(!Auth::check()){ 
            header("Location: " . URL::to('/'), true, 302);
            exit();
      }

      if(Session::get('currentPortal') == 17){
            $extend = 'superadmin.layouts.app2';
      }else if(Session::get('currentPortal') == 3){
            $extend = 'registrar.layouts.app';
      }else if(Session::get('currentPortal') == 6){
            $extend = 'adminPortal.layouts.app2';
      }else{
        header("Location: " . URL::to('/'), true, 302);
        exit();
      }
@endphp


@extends($extend)

@section('pagespecificscripts')
  
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/pagination.css')}}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">

    <style>
      
      [class*=icheck-]>input:first-child:checked+input[type=hidden]+label::after, [class*=icheck-]>input:first-child:checked+label::after {
          top: -2px;
          left: -2px;
          width: 5px;
          height: 8px;
      }

      [class*=icheck-]>input:first-child+input[type=hidden]+label::before, [class*=icheck-]>input:first-child+label::before {
          width: 15px;
          height: 15px;
          margin-left: -18px;
      }

      [class*=icheck-]>label {
        padding-left: 18px !important;
        line-height: 18px;
        min-height: 4px;
      }

      .shadow {
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
            border: 0;
      }
      .view_image:hover,
      .view_image:focus {
        color: #bbb;
        text-decoration: none;
        cursor: pointer;
      }

      .select2-container--default .select2-selection--single .select2-selection__rendered {
                  margin-top: -9px;
            }
            .shadow {
                  box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
                  border: 0 !important;
            }
            /* img{
                  border-radius: 0 !important
            } */
            .myFont{
                  font-size:.8rem !important;
            }
            .tableFixHead {
                  overflow: auto;
                  height: 100px;
            }

            .tableFixHead thead th {
                  position: sticky;
                  top: 0;
                  background-color: #fff;
                  outline: 2px solid #dee2e6;
                  outline-offset: -1px;
            
            }

            .ribbon-wrapper.ribbon-lg .ribbon {
                  right: -16px;
                  top: 4px;
                  width: 160px;
            }

            .enroll , .view_enrollment {
                  cursor: pointer;
            }

            .form-control-sm-form {
                  height: calc(1.4rem + 1px);
                  padding: 0.75rem 0.3rem;
                  font-size: .875rem;
                  line-height: 1.5;
                  border-radius: 0.2rem;
            }
            /* input[type=search]{
                  height: calc(1.7em + 2px) !important;
            } */

            /* Add asterisk to required input */
            .required:after {
              content:" *";
              color: red;
            }

            /* dispaly the line that separates the more info input field */
            @media only screen and (min-width: 769px){
              .hidden {
                padding-right:20px; 
                border-right:1px solid 
                #ccc;
              }
            }

    </style>

@endsection

@section('modalSection')


  @php
    $academic_prog = DB::table('academicprogram')->select('id','acadprogcode','acadprogcode as text')->get();
    $schoolinfo = DB::table('schoolinfo')->first();
    $usertype = DB::table('usertype')->where('deleted',0)->where('constant',1)->get();
    $sy = DB::table('sy')->select('id','sydesc as text','isactive','sydesc')->orderBy('sydesc','desc')->get();
    $courses = DB::table('college_courses')->where('cisactive',1)->where('deleted',0)->orderBy('courseDesc')->get();
    $colleges = DB::table('college_colleges')->where('cisactive',1)->where('deleted',0)->orderBy('collegeDesc')->get();
    $utype = DB::table('usertype')->orderBy('utype')->where('with_acad',1)->select('id','utype','utype as text')->get(); 
  @endphp


    <div class="modal fade" id="add-faculty" style="display: none; padding-right: 17px;" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header pb-2 pt-2 border-0">
                  <h4 class="modal-title" style="font-size: 1.1rem !important">FAS Form</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body pt-0" style="font-size: .8rem! important">
              <div class="row">
                <div class="hidden col-md-4 span6">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="exampleInputEmail1" class="mb-1">Title</label>
                        <input type="text" placeholder="Title" class="form-control form-control-sm form-control-sm-form" id="title"  name="title" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="exampleInputEmail1" class="required mb-1" id="labelfn">First Name</label>
                        <input type="text" placeholder="First name" class="form-control form-control-sm form-control-sm-form" id="fn"  name="fn" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();">
                        <span class="invalid-feedback" role="alert">
                            <strong>First name is required.</strong>
                        </span>
                        <ul id="same_account" class="mb-0" ></ul>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="exampleInputEmail1" class="mb-1">Middle Name</label>
                        <input type="text" placeholder="Middle name" class="form-control form-control-sm form-control-sm-form" id="mn"  name="mn" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();">
                          <span class="invalid-feedback" role="alert">
                              <strong>Middle Name is required.</strong>
                          </span>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="exampleInputEmail1" class="required mb-1" id="labelln">Last Name</label>
                        <input type="text" placeholder="Last name" class="form-control form-control-sm form-control-sm-form" id="ln"  name="ln" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();">
                        <span class="invalid-feedback" role="alert">
                            <strong>Last Name is required.</strong>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="exampleInputEmail1" class="mb-1">Suffix</label>
                        <input type="text" placeholder="Suffix" class="form-control form-control-sm form-control-sm-form" id="suffix"  name="suffix" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group mb-2">
                        <label for="" class="mb-1">Academic Title</label>
                        <input type="text" class="form-control form-control-sm form-control-sm-form"  id="acadtitle" placeholder="Academic Title">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="exampleInputEmail1" class="required mb-1" id="labelut">User Type</label>
                        <select class="form-control teacher select2 form-control-sm form-control-sm-form" id="ut" name="ut">
                            <option value="" selected>Select User Type</option>
                            @foreach($usertype as $item)
                                <option value="{{$item->id}}">{{$item->utype}}</option>
                            @endforeach
                        </select>
                        <span class="invalid-feedback" role="alert">
                            <strong>User type is required.</strong>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group" id="input_acad_holder" hidden>
                      <label>Academic Program</label>
                      <select class="form-control select2" multiple="multiple" id="acadprog">
                        
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-8 span6">
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="exampleInputEmail1" class="mb-1" id="labeldob">Date of Birth</label>
                        <input class="form-control form-control-sm form-control-sm-form" data-inputmask-alias="mm/dd/yyyy" data-val="true" data-val-required="Required" id="dob"  name="dob" placeholder="mm/dd/yyyy" type="text" value="" />
                        <span class="invalid-feedback" role="alert">
                          <strong>Invalid date of birth.</strong>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="exampleInputEmail1" class="mb-1" id="labelg">Gender</label>
                        <select class="form-control teacher select2  form-control-sm-form" id="g"  name="g">
                          <option value="" selected>Select Gender</option>
                          <option value="M">Male</option>
                          <option value="F">Female</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="exampleInputEmail1" class="mb-1" id="labeln">Nationality</label>
                        <select class="form-control teacher select2  form-control-sm-form" id="n"  name="n">
                          <option value="" selected>Select Nationality</option>
                          @foreach($nationality as $national)
                            <option value="{{ $national->id }}">{{ $national->nationality }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="exampleInputEmail1" class="mb-1" id="labelms">Marital Status</label>
                        <select class="form-control teacher select2  form-control-sm-form" id="ms"  name="ms">
                          <option value="" selected>Select Marital Status</option>
                          @foreach($civilstatus as $cs)
                            <option value="{{ $cs->id }}">{{ $cs->civilstatus }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="exampleInputEmail1" class="mb-1" id="labelmobile">Mobile No</label>
                        {{-- <input type="text" placeholder="09XX-XXX-XXXX" class="form-control form-control-sm form-control-sm-form" id="mobile"  name="mobile"> --}}
                        <input class="form-control form-control-sm form-control-sm-form" data-inputmask-mask="9999-999-9999" data-val="true" data-val-required="Required" id="mobile"  name="mobile" placeholder="09XX-XXX-XXXX" type="text" value="" />
                        <span class="invalid-feedback" role="alert">
                          <strong>Invalid mobile number.</strong>
                        </span>
                      </div>
                    </div>
                    <div class="col-md-8">
                      <div class="form-group">
                        <label for="exampleInputEmail1" class="mb-1" id="labelemail">Email Address</label>
                        <input type="email" placeholder="Email address" class="form-control form-control-sm form-control-sm-form" id="email"  name="email" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();">
                        <span class="invalid-feedback" role="alert">
                          <strong>Invalid email address.</strong>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="exampleInputEmail1" class="mb-1" id="labelad">Address</label>
                        <input placeholder="Address" class="form-control form-control-sm form-control-sm-form" id="ad"  name="ad" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer justify-content-between mf" >
                <button class="btn btn-primary" id="save_faculty" style="font-size:.8rem !important"><i class="fa fa-save"></i> Save</button>
            </div>
        </div>
      </div>
    </div>

  <div class="modal fade" id="view_image_modal" style="display: none; padding-right: 17px;" aria-hidden="true">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                  <div class="col-md-10 p-1" >
                    <label for="" id="fn_image_label"></label>
                  </div>
                  <div class="col-md-2 text-right">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <img src="" alt="" id="view_image" width="100%">
                  </div>
                </div>
            </div>
          
        </div>
      </div>
  </div>

  <div class="modal fade" id="fas_acadprog_modal" style="display: none; padding-right: 17px;" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header pb-2 pt-2 border-0">
              <h4 class="modal-title" style="font-size: 1.1rem !important">FAS Academic Program</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body pt-0">
          <div class="row">
            <div class="col-md-12">
                  <div class="card shadow">
                        <div class="card-body">
                              <div class="row">
                                    <div class="col-md-4">
                                        <h5><i class="fa fa-filter"></i> Filter</h5> 
                                    </div>
                                    <div class="col-md-8">
                                          <h5 class="float-right">Active S.Y.: {{collect($sy)->where('isactive',1)->first()->sydesc}}</h5>
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="col-md-2  form-group  mb-0">
                                          <label for="" class="mb-1">School Year</label>
                                          <select class="form-control select2 form-control-sm" id="filter_sy">
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
                                          <label for="" class="mb-1">User Type</label>
                                          <select class="form-control select2 form-control-sm" id="filter_utype">
                                                @foreach ($utype as $item)
                                                      @if($item->id == 1)
                                                            <option value="{{$item->id}}" selected="selected">{{$item->utype}}</option>
                                                      @else
                                                            <option value="{{$item->id}}">{{$item->utype}}</option>
                                                      @endif
                                                @endforeach
                                          </select>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
          </div>
          <div class="row" id="teacher_type_holder">
                <div class="col-md-12">
                      <di class="card shadow">
                            <div class="card-body p-1">
                                  <div class="col-md-12">
                                        <ul class="mb-0 pl-2" style="list-style-type:none;">
                                              <li> <p class="mb-0" style="font-size:.9rem !important">Please check advisory or schedule assignmnet if unable to remove academic program for selected school year.</p></li>
                                        </ul>
                                      
                                  </div>
                            </div>
                      </di>
                </div>
          </div>
          <div class="row">
                <div class="col-md-12">
                      <div class="card mb-0 shadow">
                            <div class="card-body" style="font-size:.8rem !important">
                                  <div class="row">
                                        <div class="col-md-12">
                                              <table class="table-hover table table-striped table-sm table-bordered" id="fasacadprog_datatable" width="100%" >
                                                    <thead>
                                                          <tr>
                                                                <th width="15%" class="align-middle prereg_head" data-id="0">TID #</th>
                                                                <th width="25%" class="align-middle prereg_head" data-id="1">Teacher</th>
                                                                <th width="60%" class="align-middle prereg_head" data-id="1">Academic Program</th>
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
      </div>
    </div>
</div>


<div class="modal fade" id="copy_fasacad_modal" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-sm">
        <div class="modal-content">
        <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" style="font-size: 1.1rem !important">Copy FAS Academic Program</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body pt-0">
              <div class="row form-group">
                    <div class="col-md-12 ">
                          <label for="">Copy From</label>
                          <select class="form-control select2 form-control-sm" id="copy_sy_from">
                                <option value="">Select S.Y.</option>
                                @foreach ($sy as $item)
                                      <option value="{{$item->id}}">{{$item->sydesc}}</option>
                                @endforeach
                          </select>
                    </div>
              </div>
              <div class="row">
                    <div class="col-md-12">
                          <button class="btn btn-sm btn-primary" id="copy_fasacadprog_button"><i class="fa fa-copy"></i> Copy</button>
                    </div>
              </div>
        </div>
        </div>
  </div>
</div>


<div class="modal fade" id="fasacad_modal" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-sm">
        <div class="modal-content">
        <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" style="font-size: 1.1rem !important">FAS Academic Program Form</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body pt-0">
              <div class="row form-group">
                    <div class="col-md-12 ">
                          <label for="">Teacher</label>
                          <select name="" id="teacherid" class="form-contol select2 form-control-sm"></select>
                    </div>
              </div>
              <div class="row">
                    <div class="col-md-12 form-group">
                          <label for="">Academic Program</label>
                          <select name="" id="acadprog_fas" class="form-contol select2" multiple></select>
                    </div>
              </div>
              <div class="row">
                    <div class="col-md-12">
                          <button class="btn btn-sm btn-primary" id="save_fasacadprog_button"><i class="fa fa-save"></i> Save</button>
                    </div>
              </div>
        </div>
        </div>
  </div>
</div>

  <div class="modal fade" id="view_fasinfo_modal" style="display: none; padding-right: 17px;" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header pb-2 pt-2 border-0">
              <h4 class="modal-title" style="font-size: 1.1rem !important">FAS Information : <span id="fas_name"></span></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span></button>
        </div>
          <div class="modal-body pt-0">
              <div class="row">
                <div class="col-md-2">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="card shadow h-100">
                        <div class="card-body p-2" style="font-size: .8rem! important">
                          <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-8">
                              <img alt="" id="view_image_edit" width="100%" class="img-fluid img-circle">
                            </div> 
                            <div class="col-md-2"></div>
                          </div>
                          <div class="row mt-2">
                            <div class="col-md-12 form-group mb-2">
                              <label for="" class="mb-1">Title</label>
                              <input type="text" class="form-control form-control-sm form-control-sm-form" id="edit_title_view"  placeholder="Title" disabled>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-12 form-group mb-2">
                              <label for="" class="mb-1">First Name</label>
                              <input type="text" class="form-control form-control-sm form-control-sm-form"  id="edit_firstname_view" placeholder="First Name" disabled>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-12 form-group mb-2">
                              <label for="" class="mb-1">Middle Name</label>
                              <input type="text" class="form-control form-control-sm form-control-sm-form"  id="edit_middlename_view" placeholder="Middle Name" disabled>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-12 form-group mb-2">
                              <label for="" class="mb-1">Last Name</label>
                              <input type="text" class="form-control form-control-sm form-control-sm-form"  id="edit_lastname_view" placeholder="Last Name" disabled>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-12 form-group mb-2">
                              <label for="" class="mb-1">Suffix</label>
                              <input type="text" class="form-control form-control-sm form-control-sm-form"  id="edit_suffix_view" placeholder="Suffix" disabled>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-12 form-group mb-2">
                              <label for="" class="mb-1">Academic Title</label>
                              <input type="text" class="form-control form-control-sm form-control-sm-form"  id="edit_acadtitle_view" placeholder="Academic Title" disabled>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-12 form-group">
                              <label for="" class="mb-1">User Type</label>
                              {{-- <select class="form-control form-control-sm teacher select2"  id="edit_usertype_view" disabled>
                                <option value="" selected>Select User Type</option>
                                @foreach($usertype as $item)
                                    <option value="{{$item->id}}">{{$item->utype}}</option>
                                @endforeach
                              </select> --}}
                              <input type="text" class="form-control form-control-sm form-control-sm-form"  id="edit_usertype_view" placeholder="User Type" disabled>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-12">
                              <button class="btn btn-primary btn-sm btn-block" data-toggle="modal" data-target="#more-info" data-widget="chat-pane-toggle" id="edit_more_info" style="font-size:.8rem !important">
                                <i class="fa fa-edit"></i> More Information
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="row  h-100">
                    <div class="col-md-12">
                      <div class="card shadow h-100">
                        <div class="card-body p-2">
                          <div class="row">
                            <div class="col-md-12">
                              <label for="" style="font-size:.8rem !important">Other Portal (<span id="other_portal_count">0</span>)</label>
                            </div>
                          </div>
                          <div class="row">
                            @foreach ($usertype as $item)
                                <div class="col-md-12 fas_priv_holder" data-id="{{$item->id}}">
                                  <div class="icheck-success d-inline">
                                      <input  type="checkbox" id="fas_priv{{$item->id}}" class="fas_priv" data-id="{{$item->id}}"> 
                                      <label for="fas_priv{{$item->id}}" style="font-size:.65rem !important"> {{$item->utype}}</label>
                                  </div>
                                </div>
                            @endforeach
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="card shadow">
                        <div class="card-body p-2">
                          <div class="row">
                            <div class="col-md-7">
                              <button type="button" class="btn btn-sm btn-outline-danger status" style="font-size:.8rem !important" data-status="0" hidden><i class="fa fa-ban mr-1" ></i>Deactivate Account</button>
                              <span><button type="button" class="btn btn-sm btn-outline-success status" style="font-size:.8rem !important" data-status="1" hidden><i class="fa fa-ban mr-1" ></i>Activate Account</button>
                              <button type="button" class="btn btn-sm btn-outline-danger" style="font-size:.8rem !important" id="remove_account"><i class="fa fa-trash mr-1" ></i>Remove Account</button>
                            </div>
                            <div class="col-md-2 text-right">
                              <label for="" style="font-size:.9rem !important">School Year: </label> 
                            </div>
                            <div class="col-md-3">
                                <select class="form-control form-control-sm teacher select2"  id="filter_acad_sy">
                                    @foreach ($sy as $item)
                                          @if($item->isactive == 1)
                                                <option value="{{$item->id}}" selected="selected">{{$item->text}}</option>
                                          @else
                                                <option value="{{$item->id}}">{{$item->text}}</option>
                                          @endif
                                    @endforeach
                                </select>
                            </div>
                            
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="card shadow">
                        <div class="card-body  p-2">
                          <div class="row">
                            <div class="col-md-7">
                              <label for="" style="font-size:.9rem !important" class="mb-0" >Academic Program</label>
                            </div>
                            
                          </div>
                          <div class="row">
                            <div class="col-md-12">
                                <table class="table-hover table table-striped table-sm table-bordered mb-0" width="100%" style="font-size:.7rem !important; ">
                                      <thead>
                                            <tr>
                                                  <th width="35%">User Type</th>
                                                  <th width="13%" class="text-center">Preschool</th>
                                                  <th width="13%" class="text-center">GS</th>
                                                  <th width="13%" class="text-center">HS</th>
                                                  <th width="13%" class="text-center" >SHS</th>
                                                  <th width="13%" class="text-center">COLLEGE</th>
                                            </tr>
                                      </thead>
                                      <tbody  id="acadprog_table">

                                      </tbody>
                                </table>
                            </div>
                          </div>
                          
                        </div>
                      </div>
                    </div>
                  </div>
                  {{-- <div class="row">
                    <div class="col-md-6" id="college_holder" hidden>
                      <div class="card shadow">
                        <div class="card-body p-2">
                          <div class="row">
                            <div class="col-md-12">
                              <label for="" style="font-size:.9rem !important" class="mb-0" >Colleges (<span id="college_count">0</span>)</label>
                            </div>
                          </div>
                          <div class="row mt-2">
                            @foreach ($colleges as $item)
                                <div class="col-md-12 fas_priv_holder" data-id="{{$item->id}}">
                                  <div class="icheck-success d-inline">
                                      <input  type="checkbox" id="fas_college{{$item->id}}" class="fas_college" data-id="{{$item->id}}"> 
                                      <label for="fas_college{{$item->id}}" style="font-size:.7rem !important"> {{$item->collegeDesc}}</label>
                                  </div>
                                </div>
                            @endforeach
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6" id="course_holder" hidden>
                      <div class="card shadow">
                        <div class="card-body p-2">
                          <div class="row">
                            <div class="col-md-12">
                              <label for="" style="font-size:.9rem !important" class="mb-0" >Courses (<span id="course_count">0</span>)</label>
                            </div>
                          </div>
                          <div class="row  mt-2">
                            @foreach ($courses as $item)
                                <div class="col-md-12 fas_priv_holder" data-id="{{$item->id}}">
                                  <div class="icheck-success d-inline">
                                      <input  type="checkbox" id="fas_course{{$item->id}}" class="fas_course" data-id="{{$item->id}}"> 
                                      <label for="fas_course{{$item->id}}" style="font-size:.65rem !important"> {{$item->courseDesc}}</label>
                                  </div>
                                </div>
                            @endforeach
                          </div>
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

    {{-- Edit faculty info --}}
    <div class="modal fade" id="more-info" style="display: none; padding-right: 17px;" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
            {{-- <div class="modal-header pb-2 pt-2 border-0">
                  <h4 class="modal-title">Faculty & Staff Form</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
            </div> --}}
            <div class="modal-header pb-2 pt-2 border-0">
                  <h4 class="modal-title" style="font-size: 1.1rem !important">FAS Form</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body pt-0" style="font-size: .8rem! important">
              <div class="row">
                <div class="hidden col-md-4 span6">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="exampleInputEmail1" class="mb-1">Title</label>
                        <input placeholder="Title" class="form-control form-control-sm form-control-sm-form" id="edit_title"  name="edit_title" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="exampleInputEmail1" class="required mb-1" id="labelfn">First Name</label>
                        <input type="text" placeholder="First name" class="form-control form-control-sm form-control-sm-form" id="edit_firstname"  name="edit_firstname" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();">
                        <span class="invalid-feedback" role="alert">
                            <strong>First name is required.</strong>
                        </span>
                        <ul id="same_account" class="mb-0" ></ul>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="exampleInputEmail1" class="mb-1">Middle Name</label>
                        <input placeholder="Middle name" class="form-control form-control-sm form-control-sm-form" id="edit_middlename"  name="edit_middlename" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();">
                          <span class="invalid-feedback" role="alert">
                              <strong>Middle Name is required.</strong>
                          </span>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="exampleInputEmail1" class="required mb-1" id="labelln">Last Name</label>
                        <input placeholder="Last name" class="form-control form-control-sm form-control-sm-form" id="edit_lastname"  name="edit_lastname" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();">
                        <span class="invalid-feedback" role="alert">
                            <strong>Last Name is required.</strong>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="exampleInputEmail1" class="mb-1">Suffix</label>
                        <input placeholder="Suffix" class="form-control form-control-sm form-control-sm-form" id="edit_suffix"  name="edit_suffix" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group mb-2">
                        <label for="" class="mb-1">Academic Title</label>
                        <input type="text" class="form-control form-control-sm form-control-sm-form"  id="edit_acadtitle" placeholder="Academic Title" >
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="exampleInputEmail1" class="required mb-1" id="labelut">User Type</label>
                        <select class="form-control teacher select2 form-control-sm form-control-sm-form" id="edit_usertype" name="edit_usertype">
                            <option value="" selected>Select User Type</option>
                            @foreach($usertype as $item)
                                <option value="{{$item->id}}">{{$item->utype}}</option>
                            @endforeach
                        </select>
                        <span class="invalid-feedback" role="alert">
                            <strong>User type is required.</strong>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group" id="input_acad_holder" hidden>
                      <label>Academic Program</label>
                      <select class="form-control select2" multiple="multiple" id="acadprog">
                        
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-8 span6">
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="exampleInputEmail1" class="mb-1" id="labeldob">Date of Birth</label>
                        <input class="form-control form-control-sm form-control-sm-form" data-inputmask-alias="mm/dd/yyyy" data-val="true" data-val-required="Required" id="edit_dob"  name="edit_dob" placeholder="mm/dd/yyyy" type="text" value="" />
                        <span class="invalid-feedback" role="alert">
                          <strong>Invalid date of birth.</strong>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="exampleInputEmail1" class="mb-1" id="labelg">Gender</label>
                        <select class="form-control teacher select2  form-control-sm-form" id="edit_g"  name="edit_g">
                          <option value="" selected>Select Gender</option>
                          <option value="M">Male</option>
                          <option value="F">Female</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="exampleInputEmail1" class="mb-1" id="labeln">Nationality</label>
                        <select class="form-control teacher select2  form-control-sm-form" id="edit_n"  name="edit_n">
                          <option value="" selected>Select Nationality</option>
                          @foreach($nationality as $national)
                            <option value="{{ $national->id }}">{{ $national->nationality }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="exampleInputEmail1" class="mb-1" id="labelms">Marital Status</label>
                        <select class="form-control teacher select2  form-control-sm-form" id="edit_ms"  name="edit_ms">
                          <option value="" selected>Select Marital Status</option>
                          @foreach($civilstatus as $cs)
                            <option value="{{ $cs->id }}">{{ $cs->civilstatus }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="exampleInputEmail1" class="mb-1" id="labelmobile">Mobile No</label>
                        <input class="form-control form-control-sm form-control-sm-form" data-inputmask-mask="9999-999-9999" data-val="true" data-val-required="Required" id="edit_mobile"  name="edit_mobile" placeholder="09XX-XXX-XXXX" type="text" value="" />
                        <span class="invalid-feedback" role="alert">
                          <strong>Invalid mobile number.</strong>
                        </span>
                      </div>
                    </div>
                    <div class="col-md-8">
                      <div class="form-group">
                        <label for="exampleInputEmail1" class="mb-1" id="labelemail">Email Address</label>
                        <input placeholder="Email address" class="form-control form-control-sm form-control-sm-form" id="edit_email"  name="edit_email" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();">
                        <span class="invalid-feedback" role="alert">
                          <strong>Invalid email address.</strong>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="exampleInputEmail1" class="mb-1" id="labelad">Address</label>
                        <input placeholder="Address" class="form-control form-control-sm form-control-sm-form" id="edit_ad"  name="edit_ad" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer justify-content-between mf" >
                <button class="btn btn-success" id="update_information" style="font-size:.8rem !important"><i class="fa fa-save"></i> Update Information</button>
            </div>
        </div>
      </div>
    </div>
    {{-- Edit faculty info close--}}


@endsection


@section('content')

<section class="content-header">
  <div class="container-fluid">
        <div class="row mb-2">
              <div class="col-sm-6">
                    <h1>Accounts</h1>
              </div>
              <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">Accounts</li>
              </ol>
              </div>
        </div>
  </div>
</section>
<section class="content p-0">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
            <div class="info-box shadow-lg">
              <div class="info-box-content" style="font-size:.9rem !important">
                  <div class="row">
                        <div class="col-md-4">
                            <h5><i class="fa fa-filter"></i> Filter</h5> 
                        </div>
                        <div class="col-md-8">
                            <h5 class="float-right">Active S.Y.: {{collect($sy)->where('isactive',1)->first()->sydesc}}</h5>
                        </div>
                  </div>
                  <div class="col-md-2  form-group  mb-0">
                    <label for="" class="mb-1">Status</label>
                    <select class="form-control select2 form-control-sm" id="filter_status" name="filter_status">
                      <option value="">All</option>
                      <option value="1" selected>Activated</option>
                      <option value="0">Deactivated</option>
                    </select>
                  </div>
              </div>
            </div>
      </div>
    </div>
  </div>
</section>
<section class="content p-0">
    <div class="container-fluid">
      <div class="row" hidden id="online_connection_holder">
        <div class="col-md-12">
          <div class="card shadow">
            <div class="card-body p-1 pl-3">
              <div class="row">
                <div class="col-md-6 pt-1" style="font-size:.9rem !important">
                  <label for="" class="mb-0">Online Connection: </label> <i><span id="online_status">Cheking...</span></i>
                </div>
                <div class="col-md-4 pt-1 text-right" style="font-size:.9rem !important">
                  <label for="" class="mb-0">Data From: </label> 
                </div>
                <div class="col-md-2">
                  <select name="" class="form-control form-control-sm" id="data_from">
                    <option value="local" selected="selected">Local</option>
                    <option value="online">Online</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <div class="card shadow">
            <div class="card-body">
              {{-- <div class="row mb-3">
                <div class="col-md-12">
                    Toggle column: <a class="toggle-vis" data-column="0">Name</a> - <a class="toggle-vis" data-column="1" href="#">Position</a> - <a class="toggle-vis" data-column="2">Office</a> - <a class="toggle-vis" data-column="3">Age</a> - <a class="toggle-vis" data-column="4">Start date</a> - <a class="toggle-vis" data-column="5">Salary</a>
                </div>
              </div> --}}
              <div class="row">
                <div class="col-md-12" style="font-size:.8rem !important">
                  <table class="table-hover table border-0" id="faculty_account_table" width="100%" >
                        <thead class="thead-light">
                              <tr>
                                    <th width="5%" class="pl-3" ></th>
                                    <th width="25%">Teacher</th>
                                    <th width="15%">Account</th>
                                    <th width="15%" class=" align-middle">Acad. Prog.</th>
                                    <th width="30%">Portals</th>
                                    <th width="10%"></th>
                              </tr>
                        </thead>
                  </table>
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
  <script src="{{asset('plugins/moment/moment.min.js') }}"></script>//new
  <script src="{{asset('plugins/inputmask-date/jquery.inputmask.bundle.js')}}"></script>//

  <script>

    var school_setup = @json($schoolinfo);

      var enable_button = true
      var first = true;
      var connected_stat = false

      if(school_setup.projectsetup == 'online' &&  school_setup.processsetup == 'hybrid1'){
          enable_button = false;
      }else{
        if(school_setup.projectsetup == 'offline' && school_setup.processsetup == 'hybrid1'){
          $('#online_connection_holder').removeAttr('hidden')
          check_online_connection()
        }
      }

      function check_online_connection(){
            $.ajax({
                  type:'GET',
                  url: school_setup.es_cloudurl+'/checkconnection',
                  success:function(data) {
                    connected_stat = true
                    get_last_index('users',true)
                    get_last_index('teacher',true)
                    get_last_index('teacheracadprog',true)
                    get_last_index('faspriv',true)
                    $('#online_status').text('Connected')
                  }, 
                  error:function(){
                    $('#online_status').text('Not Connected')
                  }
            })
      }

      function get_last_index(tablename,first=false){
            if(!connected_stat){
              return false
            }
            $.ajax({
                  type:'GET',
                  url: '/administrator/setup/accounts/getnewinfo',
                  data:{
                        tablename: tablename
                  },
                  success:function(data) {
                      process_create(tablename,data,first)
                  },
            })
      }

      function process_create(tablename,createdata,first=false){
            if(createdata.length == 0){
                  if(first){
                    get_updated(tablename)
                    get_deleted(tablename)
                  }
                  return false;
            }
            var b = createdata[0]
            $.ajax({
                  type:'GET',
                  url: school_setup.es_cloudurl+'/administrator/setup/accounts/synnew',
                  data:{
                        tablename: tablename,
                        data:b
                  },
                  success:function(data) {
                        createdata = createdata.filter(x=>x.id != b.id)
                        update_local_status(tablename,createdata,b,'create',first)
                  },
                  error:function(){
                        createdata = createdata.filter(x=>x.id != b.id)
                        update_local_status(tablename,createdata,b,'create',first)
                  }
            })
      }

      //get_updated
      function get_updated(tablename){
            if(!connected_stat){
              return false
            }
            $.ajax({
                  type:'GET',
                  url: '/administrator/setup/accounts/getupdated',
                  data:{
                        tablename: tablename,
                  },
                  success:function(data) {
                        process_update(tablename,data)
                  }
            })
      }

      function process_update(tablename , updated_data){
            if (updated_data.length == 0){
                  return false
            }
            var b = updated_data[0]
            $.ajax({
                  type:'GET',
                  url:  school_setup.es_cloudurl+'/administrator/setup/accounts/syncupdate',
                  data:{
                        tablename: tablename,
                        data:b
                  },
                  success:function(data) {
                        updated_data = updated_data.filter(x=>x.id != b.id)
                        update_local_status(tablename,updated_data,b,'update')
                  },
            })
      }

      function update_local_status(tablename,alldata,info,status,first=false){
            $.ajax({
                  type:'GET',
                  url:  '/administrator/setup/accounts/updatestat',
                  data:{
                        tablename: tablename,
                        data:info
                  },
                  success:function(data) {
                    if(status == 'delete'){
                      process_update(tablename,alldata)
                    }else if(status == 'update'){
                      process_update(tablename,alldata)
                    }else if(status == 'create'){
                      process_create(tablename,alldata,data,first)
                    }
                  
                  },
            })
      }

      //get deleted
      function get_deleted(tablename){
            if(!connected_stat){
              return false
            }
                $.ajax({
                    type:'GET',
                    url: '/administrator/setup/accounts/getdeleted',
                    data:{
                            tablename: tablename
                    },
                    success:function(data) {
                        process_deleted(tablename,data)
                    }
                })
            }
            
    
      function process_deleted(tablename , deleted_data){
          if (deleted_data.length == 0){
                  return false
          }
          var b = deleted_data[0]
          $.ajax({
              type:'GET',
              url: school_setup.es_cloudurl+'/administrator/setup/accounts/syncdelete',
              data:{
                  tablename: tablename,
                  data:b
              },
              success:function(data) {
                  deleted_data = deleted_data.filter(x=>x.id != b.id)
                  update_local_status(tablename,deleted_data,b,'delete')
              },
          })
      }

      var faculty_account = []
      var selectedid = null
      var updateuserid = @json(auth()->user()->id);

      // function get_faculty_account(){
      //   $.ajax({
      //       type:'GET',
      //       url:'/administrator/setup/accounts/list',
      //       success:function(data) {
      //         faculty_account = data
      //         faculty_acount_datatable()
      //       },
      //     })
      // }
      
      function DateofBirth(input){ // Convert string to Date object (yyyy/mm/dd)

        function formatDate(date) {
        var year = date.getFullYear();
        var month = (date.getMonth() + 1).toString().padStart(2, "0");
        var day = date.getDate().toString().padStart(2, "0");
        return year + "-" + month + "-" + day;
        }

        var inputdob = $(input).val();
        var dateArray = inputdob.split('/');
        var rearrangedob = [dateArray[2], dateArray[0], dateArray[1]];
        var modifieddob = rearrangedob.join('/');
        var datedob = new Date(modifieddob);
        var dob = formatDate(datedob)
        return dob;
      }

      $(":input[data-inputmask-mask]").inputmask();
      $(":input[data-inputmask-alias]").inputmask();

      $('#view_fasinfo_modal').on('hidden.bs.modal', function () {
        selectedid = null
              $("#edit_title").val("")
              $('#edit_firstname').val("")
              $('#edit_lastname').val("")
              $('#edit_middlename').val("")
              $('#edit_suffix').val("")
              $('#edit_lcn').val("")
              $('#edit_acadtitle').val("")
              $('#edit_usertype').val("").change()
              $('#edit_acadprog').val([]).change()
              $('#edit_same_account').empty()
              $(':input').removeClass('is-valid')
              $(':input').removeClass('is-invalid')
      })

      $('#more-info').on('hide.bs.modal', function () {
              $("#edit_title").val("")
              $('#edit_firstname').val("")
              $('#edit_lastname').val("")
              $('#edit_middlename').val("")
              $('#edit_suffix').val("")
              $('#edit_lcn').val("")
              $('#edit_acadtitle').val("")
              $('#edit_usertype').val("").change()
              $('#edit_acadprog').val([]).change()
              $('#edit_same_account').empty()
              $('#edit_dob').val("").change()
              $('#edit_g').val("").change()
              $('#edit_n').val("").change()
              $('#edit_ms').val("").change()
              $('#edit_mobile').val("")
              $('#edit_email').val("")
              $('#edit_ad').val("")
              $(':input').removeClass('is-valid') //new
              $(':input').removeClass('is-invalid')
              $('#update_information').removeAttr('disabled')
              view_more_info() // new
      })

      $('#data_from').on('change', function () {
        faculty_acount_datatable()
      })

      $(document).on('change','#filter_status',function(){
      var statusRequestTable = $('#faculty_account_table').DataTable();
        
          statusRequestTable.state.clear();
          statusRequestTable.destroy();
          faculty_acount_datatable()
      })

      faculty_acount_datatable()
      
      function faculty_acount_datatable(){

          var onerror_url = @json(asset('dist/img/download.png'));
          var usertype = @json(auth()->user()->type);
          var activesy = @json($sy).filter(x=>x.isactive == 1)[0].id;

          var url = '/administrator/setup/accounts/list'

          if($('#data_from').val() == 'online'){
            url = school_setup.es_cloudurl+'/administrator/setup/accounts/list'
          }

          

          var table = $("#faculty_account_table").DataTable({
            destroy: true,
            lengthChange: false,
            deferRender: true,
            autoWidth: false,
            stateSave: true,
            serverSide: true,
            processing: true,
            ajax:{
                  url: url,
                  type: 'GET',
                  data: {
                    status:$('#filter_status').val(),
                  },
                  dataSrc: function ( json ) {
                        faculty_account = json.data
                        if(selectedid != null){
                          view_fas_info()
                        }
                        return json.data;
                  }
            },
            columns: [
                  { "data": null},
                  { "data": null },
                  { "data": null},
                  { "data": null},
                  { "data": null},
                  { "data": null}
                  
            ],
            columnDefs: [
                  {
                    'targets': 0,
                    'orderable': false, 
                    'createdCell':  function (td, cellData, rowData, row, col) {
                        if(rowData.picurl == null){
                          var text = '<img width="75%" src="'+onerror_url+'" alt="" class="img-circle img-fluid view_image" data-id="'+rowData.id+'">'
                        }else{
                          var text = '<img width="75%" src="'+rowData.picurl+'" onerror="this.src=\''+onerror_url+'\'" alt="" class="img-circle img-fluid view_image" data-id="'+rowData.id+'">'
                        }
                      
                        $(td)[0].innerHTML =  text 
                        $(td).addClass('text-center')
                        $(td).addClass('align-middle')
                    }
                  },
                  {
                    'targets': 2,
                    'orderable': false, 
                    'createdCell':  function (td, cellData, rowData, row, col) {
                        var text = ''
                        var status = ''
                        if(rowData.length > 1){
                          text = '<a class="mb-0 text-danger"><i>Multiple Account</i></a>';
                        }
                        else if(rowData.user.length > 0){
                            if(rowData.user[0].isDefault == 1){
                              text = '<a class="mb-0">'+rowData.user[0].email+'</a><p class="text-muted mb-0" style="font-size:.7rem">Default Password</p>';
                            }else if(rowData.user[0].isDefault == 0){
                              var p_pass = '<a href="javascript:void(0)" class="genrate_password text-danger" data-id="'+rowData.user[0].email+'">Reset Password</a>'
                              text = '<a class="mb-0">'+rowData.user[0].email+status+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+p_pass+'</p>';
                            }

                            if(rowData.isactive == 0){
                              text = '<span class="badge badge-danger" style="font-size:.7rem !important">Deactivated</span>';
                            }
                        }else{
                          text = 'No Account';
                          if(rowData.isactive == 0){
                              text = '<span class="badge badge-danger" style="font-size:.7rem !important">Deactivated</span>';
                            }
                        }



                        $(td)[0].innerHTML =  text
                        $(td).addClass('align-middle')
                    }
                  },
                  {
                    'targets': 1,
                    'orderable': false, 
                    'createdCell':  function (td, cellData, rowData, row, col) {
                        var access = '';
                        // if(usertype == 17){
                        //   access = '<a href="changeUser/'+rowData.userid+'" class="mb-0">View Portal</a><p class="text-muted mb-0" style="font-size:.7rem">'
                        // }

                        // if($('#data_from').val() == 'local'){
                        //   if(enable_button){
                        //     var text = '<a href="javascript:void(0)" class="mb-0 view_fas_info" data-id="'+rowData.id+'">'+rowData.fullname+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.tid+' '+access+'</p>';
                        //   }else{
                        //     var text = '<span>'+rowData.fullname+'</span><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.tid+' '+access+'</p>';
                        //   }
                        // }else{
                        //   var text = '<span>'+rowData.fullname+'</span><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.tid+' '+access+'</p>';
                        // }

                        if(usertype == 17){
                          access = '<a href="changeUser/'+rowData.userid+'" class="mb-0">View Portal</a><p class="text-muted mb-0" style="font-size:.7rem">'
                        }

                        if($('#data_from').val() == 'local'){
                          if(enable_button){
                            var text = '<span href="javascript:void(0)" class="mb-0 " data-id="'+rowData.id+'">'+rowData.fullname+'</span><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.tid+' '+access+'</p>';
                          }else{
                            var text = '<span>'+rowData.fullname+'</span><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.tid+' '+access+'</p>';
                          }
                        }else{
                          var text = '<span>'+rowData.fullname+'</span><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.tid+' '+access+'</p>';
                        }

                        $(td)[0].innerHTML =  text 
                    }
                  },
                  {
                    'targets': 3,
                    'orderable': false, 
                    'createdCell':  function (td, cellData, rowData, row, col) {
                        var acad = ''
                        var college = ''
                        var course = ''

                        $.each(rowData.acad.filter(x=>x.syid == activesy && x.acadprogutype == rowData.usertypeid),function(a,b){
                          acad += '<span class="badge badge-info">'+b.acadprogcode+'</span> '
                            
                        })


                        if(rowData.colleges.length  > 0){
                          $.each(rowData.colleges,function(c,d){
                            college += '<span class="badge badge-secondary">'+d.collegeabrv+'</span> '
                          })
                          college = '<p class="text-muted mb-0" style="font-size:.7rem">'+college+'</p>';
                        }
                        if(rowData.courses.length  > 0){
                          $.each(rowData.courses,function(c,d){
                            course += '<span class="badge badge-warning">'+d.courseabrv+'</span> '
                          })
                          course = '<p class="text-muted mb-0" style="font-size:.7rem">'+course+'</p>';
                        }


                        var text = '<p class="text-muted mb-0" style="font-size:.7rem">'+acad+'</p>';
                        text += college
                        text += course


                        $(td)[0].innerHTML =  text 
                    }
                  },
                  {
                    'targets': 4,
                    'orderable': false, 
                    'createdCell':  function (td, cellData, rowData, row, col) {
                        var acad = ''
                        // var utype = 'Main: ' + rowData.utype+ ' '+ acad
                        // var length = rowData.faspriv.length 
                        // if(length > 0){
                        //   utype += '<p class="text-muted mb-0" style="font-size:.7rem">Other Portal: '
                        //   $.each(rowData.faspriv,function(a,b){
                        //     utype += b.utype
                        //     if(length > a+1){
                        //       utype += ' , '
                        //     }
                        //   })
                        //   utype += '</p>'
                        // }else{
                        //   utype += '<p class="text-muted mb-0" style="font-size:.7rem">Other Portal: <span class="text-danger">None</span></p>'
                        // }
                        var utype = rowData.utype+ ' '+ acad
                        var length = rowData.faspriv.length 
                        if(length > 0){
                          utype += '<p class="text-muted mb-0" style="font-size:.7rem">'
                          $.each(rowData.faspriv,function(a,b){
                            utype += b.utype
                            if(length > a+1){
                              utype += ' , '
                            }
                          })
                          utype += '</p>'
                        }
                        
                        $(td)[0].innerHTML =  utype 

                    }
                  },
                  {
                    'targets': 5,
                    'orderable': false, 
                    'createdCell':  function (td, cellData, rowData, row, col) {
                      var buttons = '<a href="#" class="view_fas_info" data-id="'+rowData.id+'"><i class="far fa-edit"></i></a>';
                      $(td)[0].innerHTML =  buttons
                      $(td).addClass('text-center')
                      $(td).addClass('align-middle')

                    }
                  },
                  
            ]
            
        });

        // $('a.toggle-vis').on('click', function (e) {
        //     e.preventDefault();
        //     var column = table.column($(this).attr('data-column'));
        //     column.visible(!column.visible());
        // });


        if( ( school_setup.projectsetup == 'online' &&  school_setup.processsetup == 'all' ) || school_setup.projectsetup == 'offline' ){

          if($('#data_from').val() == 'local'){
            var label_text = $($("#faculty_account_table_wrapper")[0].children[0])[0].children[0]
            $(label_text)[0].innerHTML = '<button class="btn btn-primary btn-sm " data-toggle="modal" data-target="#add-faculty" data-widget="chat-pane-toggle" id="button_to_modal"><i class="fas fa-plus"></i> Add Account</button><button class="ml-2 btn btn-primary btn-sm "  id="fas_acad_prog" hidden>FAS Acad. Prog</button>'
          }

        }
        
      }

      function view_fas_info(){
        $('#course_holder').attr('hidden','hidden')
        $('#college_holder').attr('hidden','hidden')

        var temp_info = faculty_account.filter(x=>x.id == selectedid)

        if(temp_info.length > 0){
          $('#edit_title_view').val(temp_info[0].title)
          $('#edit_firstname_view').val(temp_info[0].firstname)
          $('#edit_middlename_view').val(temp_info[0].middlename)
          $('#edit_lastname_view').val(temp_info[0].lastname)
          $('#edit_suffix_view').val(temp_info[0].suffix)
          $('#edit_acadtitle_view').val(temp_info[0].acadtitle)
          
          // $('#edit_usertype_view').val(temp_info[0].usertypeid).change()
          $('#edit_usertype_view').val(temp_info[0].utype)

          $('#fas_name').text(' [ ' + temp_info[0].tid + ' ]' + temp_info[0].fullname)

          $('.fas_priv').prop('checked',false)
          $('#other_portal_count').text(0)

          $('.fas_priv_holder').removeAttr('hidden')
          $('.fas_priv_holder[data-id="'+temp_info[0].usertypeid+'"]').attr('hidden','hidden')

          $.each(temp_info[0].faspriv,function(a,b){
            if(b.usertype == 14){
              $('#college_holder').removeAttr('hidden')
            }else if(b.usertype == 16){
              $('#course_holder').removeAttr('hidden')
            }
            $('.fas_priv[data-id="'+b.usertype+'"]').prop('checked',true)
          })

          if(temp_info[0].usertypeid == 14){
            $('#college_holder').removeAttr('hidden')
          }else if(temp_info[0].usertypeid == 16){
            $('#course_holder').removeAttr('hidden')
          }

          $('#other_portal_count').text(temp_info[0].faspriv.length)

          update_acad_display(temp_info)

          var onerror_url = @json(asset('dist/img/download.png'));

          if(temp_info[0].picurl == null){
            $('#view_image_edit').attr('src',onerror_url)
          }else{
            $('#view_image_edit').attr('src',temp_info[0].picurl)
          }

          if(temp_info[0].isactive == 1){
            $('.status[data-status="1"]').attr('hidden','hidden')
            $('.status[data-status="0"]').removeAttr('hidden')
          }else{
            $('.status[data-status="0"]').attr('hidden','hidden')
            $('.status[data-status="1"]').removeAttr('hidden')
          }
        }
        

      }

      // fetch more info open
      function view_more_info(){
        var url = '/administrator/setup/accounts/getmoreinfo'

        if($('#data_from').val() == 'online'){
          url = school_setup.es_cloudurl+'/administrator/setup/accounts/getmoreinfo'
        }

        var temp_info = faculty_account.filter(x=>x.id == selectedid)

        if(temp_info.length > 0){
            $('#edit_title').val(temp_info[0].title)
            $('#edit_firstname').val(temp_info[0].firstname)
            $('#edit_middlename').val(temp_info[0].middlename)
            $('#edit_lastname').val(temp_info[0].lastname)
            $('#edit_suffix').val(temp_info[0].suffix)
            $('#edit_acadtitle').val(temp_info[0].acadtitle)
            $('#edit_usertype').val(temp_info[0].usertypeid).change()

        }
         
          
        $.ajax({
              url: url,
              type: 'GET',
              data: {
                id: selectedid,
              },
              success:function(data){
                  if(data.length != 0){
                      $('#edit_title').val(data[0].title)
                      $('#edit_firstname').val(data[0].firstname)
                      $('#edit_middlename').val(data[0].middlename)
                      $('#edit_lastname').val(data[0].lastname)
                      $('#edit_suffix').val(data[0].suffix)
                      $('#edit_acadtitle').val(data[0].acadtitle)
                      $('#edit_usertype').val(data[0].usertypeid).change()

                      var dbdate = data[0].dob; // rearrange date format
                      if(dbdate !== "0000-00-00"){
                        var dbdatesplit = dbdate.split('-');
                        var rearrangedArray = [dbdatesplit[1], dbdatesplit[2], dbdatesplit[0]];
                        var dbdatejoin = rearrangedArray.join('-');
                        $('#edit_dob').val(dbdatejoin)
                      }else{
                        $('#edit_dob').val("").change()
                      }

                      $('#edit_g').val(data[0].gender).change()
                      $('#edit_n').val(data[0].nationalityid).change()
                      $('#edit_ms').val(data[0].maritalstatusid).change()
                      $('#edit_mobile').val(data[0].contactnum)
                      $('#edit_email').val(data[0].email)
                      $('#edit_ad').val(data[0].address)
                  }else{
                      $('#edit_dob').val("").change()
                      $('#edit_g').val("").change()
                      $('#edit_n').val("").change()
                      $('#edit_ms').val("").change()
                      $('#edit_mobile').val("")
                      $('#edit_email').val("")
                      $('#edit_ad').val("")
                  }
                }
            })
      }

        function update_acad_display(temp_info){

        $('#acadprog_table').empty()
        var temp_faspriv = []

        $.each(temp_info[0].faspriv.filter(x=>x.with_acad == 1),function(a,b){
          temp_faspriv.push(b)
        })

        if(temp_info[0].with_acad == 1){
          temp_faspriv.push({
            usertype: temp_info[0].usertypeid, 
            privelege: 2, 
            utype: temp_info[0].utype, 
            userid: temp_info[0].userid, 
            with_acad: temp_info[0].with_acad
          })
        }
      
        $('.fas_college').prop('checked',false)
        if(temp_info[0].colleges.filter(x=>x.syid == $('#filter_acad_sy').val()).length > 0){
          $('#college_count').text(temp_info[0].colleges.length)
        }else{
          $('#college_count').text(0)
        }
        $.each(temp_info[0].colleges.filter(x=>x.syid == $('#filter_acad_sy').val()),function(a,b){
          $('.fas_college[data-id="'+b.collegeid+'"]').prop('checked',true)
        })

        $('.fas_course').prop('checked',false)
        if(temp_info[0].courses.filter(x=>x.syid == $('#filter_acad_sy').val()).length > 0){
          $('#course_count').text(temp_info[0].courses.length)
        }
        $.each(temp_info[0].courses.filter(x=>x.syid == $('#filter_acad_sy').val()),function(a,b){
          $('.fas_course[data-id="'+b.courseid+'"]').prop('checked',true)
        })

        $.each(temp_faspriv,function(a,b){

          $('#acadprog_table').append('<tr><td>'+b.utype+'</td>'+
                                        '<td class="text-center"><div class="icheck-success d-inline"><input  type="checkbox" id="acad'+2+'_'+b.usertype+'" class="acad" data-acad="'+2+'" data-utype="'+b.usertype+'"> <label for="acad'+2+'_'+b.usertype+'" style="font-size:.65rem !important">&nbsp;</label></div></td>'+
                                        '<td class="text-center"><div class="icheck-success d-inline"><input  type="checkbox" id="acad'+3+'_'+b.usertype+'" class="acad" data-acad="'+3+'" data-utype="'+b.usertype+'"> <label for="acad'+3+'_'+b.usertype+'" style="font-size:.65rem !important">&nbsp;</label></div></td></td>'+
                                        '<td class="text-center"><div class="icheck-success d-inline"><input  type="checkbox" id="acad'+4+'_'+b.usertype+'" class="acad" data-acad="'+4+'" data-utype="'+b.usertype+'"> <label for="acad'+4+'_'+b.usertype+'" style="font-size:.65rem !important">&nbsp;</label></div></td></td>'+
                                      '<td class="text-center"><div class="icheck-success d-inline"><input  type="checkbox" id="acad'+5+'_'+b.usertype+'" class="acad" data-acad="'+5+'" data-utype="'+b.usertype+'"> <label for="acad'+5+'_'+b.usertype+'" style="font-size:.65rem !important">&nbsp;</label></div></td></td>'+
                                      '<td class="text-center"><div class="icheck-success d-inline"><input  type="checkbox" id="acad'+6+'_'+b.usertype+'" class="acad" data-acad="'+6+'" data-utype="'+b.usertype+'"> <label for="acad'+6+'_'+b.usertype+'" style="font-size:.65rem !important">&nbsp;</label></div></td></td>'+
                                        '</tr>')
        })

        if(temp_faspriv.length > 0 ){
          var temp_acad = temp_info[0].acad.filter(x=>x.syid == $('#filter_acad_sy').val())
          $.each(temp_acad,function(a,b){
            $('.acad[data-acad="'+b.id+'"][data-utype="'+b.acadprogutype+'"]').prop('checked',true)
          })
        }else{
          $('#acadprog_table').append('<tr><td colspan="6">No data available. Please check user other privelege portal.</td></tr>')
        }

      }
  </script>

  <script>
    var fasid = ""
    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })

    $(document).ready(function(){

      $('.select2').select2()

      var temp_usertype = @json($usertype)

      var acad = @json($academic_prog);

      $(document).on('click','.view_fas_info',function(){
        var fasid = $(this).attr('data-id')
        selectedid = fasid
        view_fas_info()
        $('#view_fasinfo_modal').modal()
      })

      $(document).on('click','#edit_more_info',function(){
        // dynamic form validation for update-information
        dynamic_validate('#edit_firstname', '#update_information', function(isValid) {
          return isValid
        })

        dynamic_validate('#edit_lastname', '#update_information', function(isValid) {
          return isValid
        })

        dynamic_validate('#edit_usertype', '#update_information', function(isValid) {
          return isValid
        })

        dynamic_validate_dob('#edit_dob', '#update_information', function(isValid) {
          return isValid
        })

        dynamic_validate_email('#edit_email', '#update_information', function(isValid) {
          return isValid
        })

        dynamic_validate_mobile('#edit_mobile', '#update_information', function(isValid) {
          return isValid
        })
    
        function dynamic_validate_reset(selectors) {
          selectors.forEach(function(selector) {
                $(selector).removeClass('is-valid')
                $(selector).removeClass('is-invalid');
          });
        }

        var temp_info = faculty_account.filter(x=>x.id == selectedid)

        var temp_info = faculty_account.filter(x=>x.id == selectedid)
        view_more_info() // On click edit more info button with the view_fas_info 
        $('#more-info').modal()
      })

      $(document).on('change','#filter_acad_sy',function(){
          var temp_info = faculty_account.filter(x=>x.id == selectedid)
          update_acad_display(temp_info)
          
        })

      $("#acadprog").select2({
                              data: acad,
                              placeholder: "Select a academic program",
                              theme: 'bootstrap4'
                        })

      $(document).on('click','.view_image',function(){
          var temp_src = $(this).attr('src')
          var temp_id = $(this).attr('data-id')
          var temp_data = faculty_account.filter(x=>x.id == temp_id)
          $('#fn_image_label').text(temp_data[0].fullname)
          $('#view_image_modal').modal()
          $('#view_image').attr('src',temp_src)
      })

      $(document).on('click','.genrate_password',function(){
        var temp_tid = $(this).attr('data-id')

        Swal.fire({
            text: 'Are you sure you reset password?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Reset'
        }).then((result) => {
              if (result.value) {
                reset_password(temp_tid,'offline')
              }
        })
      })

      function reset_password(tid,connection){

        var url = '/administrator/setup/accounts/updatepass'

        if($('#data_from').val() == 'online'){
          url = school_setup.es_cloudurl+'/administrator/setup/accounts/updatepass'
        }

        $.ajax({
          type:'GET',
          url:url,
          data:{
                tid:tid
          },
          success:function(data) {
                if(data[0].status == 0){
                      Toast.fire({
                            type: 'warning',
                            title: data[0].message
                      })
                }else{
                      Toast.fire({
                            type: 'success',
                            title: data[0].message
                      })
                      faculty_acount_datatable()
                }
          },error:function(){
                Toast.fire({
                      type: 'error',
                      title: 'Something went wrong!'
                })
          }
        })
      }
      

      $(document).on('click','#button_to_modal',function(){
              selectedid = null
              $("#title").val("")
              $('#fn').val("")
              $('#ln').val("")
              $('#mn').val("")
              $('#suffix').val("")
              $('#lcn').val("")
              $('#acadtitle').val("")
              $('#ut').val("").change()
              $('#acadprog').val([]).change()
              $('#same_account').empty()
              $('#dob').val("").change()
              $('#g').val("").change()
              $('#n').val("").change()
              $('#ms').val("").change()
              $('#mobile').val("")
              $('#email').val("")
              $('#ad').val("")
              $(':input').removeClass('is-valid')
              $(':input').removeClass('is-invalid')
      })

      $(document).on('input','#fn',function(){
            var text = $(this).val()
            var check_dup = faculty_account.filter(x=>x.fullname.includes(text.toUpperCase()) ).slice(0,5)
            $('#same_account').empty()
            if(check_dup.length > 0 && $(this).val() != ""){
                  var duplicate = ''
                  $.each(check_dup,function(a,b){
                        duplicate += '<li>'+b.fullname+'</li>'
                        
                  })
                  $('#same_account')[0].innerHTML = duplicate

                  Toast.fire({
                        type: 'warning',
                        title: 'Account already exist!'
                  })
            }
          
      })

      dynamic_validate_dob('#dob', '#save_faculty', function(isValid) {
        return isValid
      })//

      dynamic_validate_mobile('#mobile', '#save_faculty', function(isValid) {
        return isValid
      })//

      dynamic_validate_email('#email', '#save_faculty', function(isValid) {
        return isValid
      })//

      dynamic_validate('#fn', '#save_faculty', function(isValid) {
        return isValid
      })

      dynamic_validate('#ln', '#save_faculty', function(isValid) {
        return isValid
      })

      dynamic_validate('#ut', '#save_faculty', function(isValid) {
        return isValid
      })

      //Date of Birth dynamic validation
      function dynamic_validate_dob(inputSel, btnSel, callback) {

        function validateInput_dob(input, select) {
          if (input.val() == ""){
              $(btnSel).prop("disabled", false);
              input.removeClass("is-invalid");
              input.removeClass("is-valid");
          }else if(input.val().includes('m') || input.val().includes('d') || input.val().includes('y') ){
              // disable add button when error input
              $(btnSel).prop("disabled", true);
              input.removeClass("is-valid").addClass("is-invalid");
          }else{
              $(btnSel).prop("disabled", false);
              input.removeClass("is-invalid").addClass("is-valid");
          }
        };

        // need to finish typing before validating
        $(inputSel).on("keyup", (e) => {
            var isValid = validateInput_dob($(inputSel));
            // return the true if valid false if not to callback
            callback(isValid);
        });

        // need to finish typing before validating
        $(inputSel).on("change", (e) => {
            var isValid = validateInput_dob($(inputSel));
            // return the true if valid false if not to callback
            callback(isValid);
        });
      }

      //Mobile Number dynamic validation
      function dynamic_validate_mobile(inputSel, btnSel, callback) {

        function validateInput_mobile(input, select) {
            if (input.val() == ""){
              $(btnSel).prop("disabled", false);
              input.removeClass("is-invalid");
              input.removeClass("is-valid")
            }else if(input.val().includes('_') || !input.val().startsWith(0) || input.val().charAt(1) != 9 ){
              // disable add button when error input
              $(btnSel).prop("disabled", true);
              input.removeClass("is-valid").addClass("is-invalid");
            }else{
              $(btnSel).prop("disabled", false);
              input.removeClass("is-invalid").addClass("is-valid");
            }
          };

        // need to finish typing before validating
        $(inputSel).on("keyup", (e) => {
            var isValid = validateInput_mobile($(inputSel));
            // return the true if valid false if not to callback
            callback(isValid);
        });

        // need to finish typing before validating
        $(inputSel).on("change", (e) => {
            var isValid = validateInput_mobile($(inputSel));
            // return the true if valid false if not to callback
            callback(isValid);
        });

      }

      //Email dynamic validation
      function dynamic_validate_email(inputSel, btnSel, callback) {

          function validateInput_email(input, select) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
              if (input.val().length == 0 ) {
                $(btnSel).prop("disabled", false);
                input.removeClass("is-invalid")
                input.removeClass("is-valid")
              }else if(!emailRegex.test(input.val())){
                // disable add button when error input
                $(btnSel).prop("disabled", true);
                input.removeClass("is-valid").addClass("is-invalid");
              }else {
                $(btnSel).prop("disabled", false);
                input.removeClass("is-invalid").addClass("is-valid");
              }
            };
        // need to finish typing before validating
        $(inputSel).on("input", (e) => {
              var isValid = validateInput_email($(inputSel));
              // return the true if valid false if not to callback
              callback(isValid);
        });

        // need to finish typing before validating
        $(inputSel).on("change", (e) => {
              var isValid = validateInput_email($(inputSel));

              // return the true if valid false if not to callback
              callback(isValid);
        });
      }//

      //dynamic validation
      function dynamic_validate(inputSel, btnSel, callback) {

        function validateInput(input, select) {
              if (!input.val().trim()) {
                    // disable add button when error input
                    $(btnSel).prop("disabled", true);
                    input.removeClass("is-valid").addClass("is-invalid");
              }else{
                    $(btnSel).prop("disabled", false);
                    input.removeClass("is-invalid").addClass("is-valid");
              }
        }

        // need to finish typing before validating
        $(inputSel).on("input", (e) => {
              var isValid = validateInput($(inputSel));
              // return the true if valid false if not to callback
              callback(isValid);
        });

        // need to finish typing before validating
        $(inputSel).on("change", (e) => {
              var isValid = validateInput($(inputSel));
              // return the true if valid false if not to callback
              callback(isValid);
        });
      }

      function dynamic_validate_reset(selectors) {
        selectors.forEach(function(selector) {
              $(selector).removeClass('is-valid')
              $(selector).removeClass('is-invalid');
        });
      }//
      
// Example starter JavaScript for disabling form submissions if there are invalid fields
    // (function () {
    //   'use strict'

    //   // Fetch all the forms we want to apply custom Bootstrap validation styles to
    //   var forms = document.querySelectorAll('.needs-validation')

    //   // Loop over them and prevent submission
    //   Array.prototype.slice.call(forms)
    //     .forEach(function (form) {
    //       form.addEventListener('submit', function (event) {
    //         if (!form.checkValidity()) {
    //           event.preventDefault()
    //           event.stopPropagation()
    //         }

    //         form.classList.add('was-validated')
    //       }, false)
    //     })
    // })()
//////////////////////////////////

      var userid = @json(auth()->user()->id);
        // if($('#ut').val() == 1 || $('#ut').val() == 2){
        //   if($('#acadprog').val() == ""){
        //     Toast.fire({
        //           type: 'warning',
        //           title: 'Academic program is required!'
        //     })
        //     valid_input = false
        //   }
        // }

        $(document).on('click','#save_faculty',function(){
          var valid_input = true
          $(':input').removeClass('is-valid')

          //Email
          if($('#email').hasClass('is-invalid')){
            Toast.fire({
                    type: 'warning',
                    title: 'Email is invalid!'
              })
              valid_input = false
          }
          //Mobile
          if($('#mobile').hasClass('is-invalid')){
            Toast.fire({
                    type: 'warning',
                    title: 'Mobile number is invalid!'
              })
              valid_input = false
          }
          //Date of Birth
          if($('#dob').hasClass('is-invalid')){
            Toast.fire({
                    type: 'warning',
                    title: 'Date of Birth is invalid!'
              })
              valid_input = false
          }
          //Usertype
          if($('#ut').val() == ""){
            Toast.fire({
                    type: 'warning',
                    title: 'User type is required!'
              })
              valid_input = false
          }
          //Lastname
          if($('#ln').val() == ""){
            Toast.fire({
                    type: 'warning',
                    title: 'Last Name is required!'
              })
              valid_input = false
          }
          //Firstname
          if($('#fn').val() == ""){
            Toast.fire({
                    type: 'warning',
                    title: 'First name is required!'
              })
              valid_input = false
          }

          var temp_id = $('#ut').val()
          var usertype_info = temp_usertype.filter(x=>x.id == temp_id)
          var needs_acad = false
          if(usertype_info.length > 0){
            if(usertype_info[0].with_acad == 1 ){
              needs_acad = true
            }
          }

          if(valid_input){
            var url = '/administrator/setup/accounts/create/account'
            $.ajax({
              type:'GET',
              url:url,
              data:{
                title:$("#title").val(),
                fname:$('#fn').val(),
                lname:$('#ln').val(),
                mname:$('#mn').val(),
                suffix:$('#suffix').val(),
                acadtitle:$('#acadtitle').val(),
                lcn:$('#lcn').val(),
                utype:$('#ut').val(),
                bdate: DateofBirth('#dob'),
                gender:$('#g').val(),
                national:$('#n').val(),
                marital:$('#ms').val(),
                mobile:$('#mobile').val(),
                email:$('#email').val(),
                address:$('#ad').val(),
                acad:$('#acadprog').val(),
                userid:userid,
              },
              success:function(data) {
                $('#add-faculty').modal('hide')

                if(data[0].status == 1){
                  Toast.fire({
                    type: 'success',
                    title: data[0].data
                  })
                    if(school_setup.setup == 1 && ( school_setup.processsetup == 'hybrid1' || school_setup.processsetup == 'hybrid2' ) ){
                      get_last_index('teacher')
                      get_last_index('users')
                    }

                    faculty_acount_datatable()
                }else{
                  Toast.fire({
                    type: 'error',
                    title: data[0].data
                  })
                }
              },
              error:function(){
                Toast.fire({
                    type: 'error',
                    title: 'Something went wrong!'
                  })
              }
            }) 
          }

        })
        
    })
  </script>

  <script>
    $(document).ready(function(){
      $(document).on('click','#update_information',function(){
        update_info()
        view_fas_info()// new
        $(':input').removeClass('is-valid')
      })

      function update_info(){
        var valid_input = true
        //Email
        if($('#edit_email').hasClass('is-invalid')){
            Toast.fire({
                    type: 'warning',
                    title: 'Invalid email!'
              })
            valid_input = false
        }
        //Mobile Number
        if($('#edit_mobile').hasClass('is-invalid')){
            Toast.fire({
                    type: 'warning',
                    title: 'Invalid mobile number!'
              })
            valid_input = false
        }
        //Date of Birth
        if($('#edit_dob').hasClass('is-invalid')){
            Toast.fire({
                    type: 'warning',
                    title: 'Date of Birth is invalid!'
              })
              valid_input = false
        }
        // Usertype
        if($('#edit_usertype').val() == ""){
            Toast.fire({
                type: 'warning',
                title: 'User type is required!'
            })
            valid_input = false
        }
        // Lastname
        if($('#edit_lastname').val() == ""){
            Toast.fire({
                type: 'warning',
                title: 'Last Name is required!'
            })
            valid_input = false
        }
        // Firstname
        if($('#edit_firstname').val() == ""){
            Toast.fire({
                type: 'warning',
                title: 'First name is required!'
            })
            valid_input = false
        }

        var url = '/administrator/setup/accounts/update/information'

        var temp_info = faculty_account.filter(x=>x.id == selectedid)

          if(valid_input){
            $.ajax({
                type:'GET',
                url: url,
                data :{
                  'teacher':selectedid,
                  'title':$("#edit_title").val(),
                  'fname':$("#edit_firstname").val(),
                  'lname':$("#edit_lastname").val(),
                  'mname':$("#edit_middlename").val(),
                  'suffix':$("#edit_suffix").val(),
                  'acadtitle':$("#edit_acadtitle").val(),
                  'utype':$("#edit_usertype").val(),
                  'bdate': DateofBirth('#edit_dob'),
                  'gender':$('#edit_g').val(),
                  'national':$('#edit_n').val(),
                  'marital':$('#edit_ms').val(),
                  'mobile':$('#edit_mobile').val(),
                  'email':$('#edit_email').val(),
                  'address':$('#edit_ad').val(),
                },
                success:function(data) {
                    if(data[0].status == 1){
                        Toast.fire({
                            type: 'success',
                            title: data[0].data,
                        })
                      
                        if(school_setup.setup == 1 && ( school_setup.processsetup == 'hybrid1' || school_setup.processsetup == 'hybrid2' )){
                            get_updated('teacher')
                            get_updated('users')
                        }

                        faculty_acount_datatable()
                    
                    }else{
                        Toast.fire({
                            type: 'error',
                            title: data[0].data
                        })
                    }
                },
                error:function(){
                    Toast.fire({
                        type: 'error',
                        title: 'Something went wrong!'
                    })
                }
            })

            $.ajax({
                type:'GET',
                url:'/administrator/setup/accounts/update/accountinfo',
                data:{
                    utype:$('#edit_usertype').val(),
                    fname:$('#edit_firstname').val(),
                    lname:$('#edit_lastname').val(),
                    teacher:selectedid,
                    updateuserid:temp_info[0].userid
                },
                success:function(data) {
                }
            })
        }

      }  

    })
  </script>

  <script>
    //privelege
    $(document).ready(function(){
      $(document).on('click','.fas_priv',function(){
        $('.fas_priv').attr('disabled','disabled')
        if( school_setup.projectsetup == 'online' &&  school_setup.processsetup != 'all' ){
            Toast.fire({
                type: 'info',
                title: 'Access Denied!'
            })
            return false
        }

        var status = 1
        usertype = $(this).attr('data-id')

        if($(this).prop('checked') == false){
            status = 0
        }

        var url = '/administrator/setup/accounts/update/privilege'

        var temp_info = faculty_account.filter(x=>x.id == selectedid)

          $.ajax({
              type:'GET',
              url:url,
              data:{
                  userid:temp_info[0].userid,
                  usertype:usertype,
                  status:status,
                  updateuserid:updateuserid
              },
              success:function(data) {
                  if(data[0].status == 1){
                      Toast.fire({
                          type: 'success',
                          title: 'Privilege Updated!'
                      })

                      if(school_setup.setup == 1 && ( school_setup.processsetup == 'hybrid1' || school_setup.processsetup == 'hybrid2' ) ){
                        if(status == 1){
                          get_last_index('faspriv')
                        }else{
                          get_deleted('faspriv')
                        }
                      }

                      $('.fas_priv').removeAttr('disabled')
                      faculty_acount_datatable()
                  }else{
                      Toast.fire({
                          type: 'error',
                          title: 'Something went wrong!'
                      })
                  }
              }
          })
        })
    })
  </script>
  
  
  <script>
    //acad prog
    $(document).ready(function(){

      var selected_acad = []
      var selected_usertype = null

      $(document).on('click','.acad',function(a,b){
        selected_acad = []
        $('.acad').attr('disabled','disabled')
        selected_usertype = $(this).attr('data-utype')
        $('.acad[data-utype="'+selected_usertype+'"]').each(function(a,b){
          if($(this).prop('checked')){
            selected_acad.push($(b).attr('data-acad'))
          }
        })
        save_acadprog()
      })

      function save_acadprog(){
      
        $.ajax({
              type:'GET',
              url:'/administrator/setup/accounts/update/fasacadprog',
              data:{
                    syid:$('#filter_acad_sy').val(),
                    usertype:selected_usertype,
                    teacherid:selectedid,
                    acadprog:selected_acad
              },
              success:function(data) {
                    if(data[0].status == 1){
                        if(school_setup.setup == 1 && ( school_setup.processsetup == 'hybrid1' || school_setup.processsetup == 'hybrid2' ) ){
                          get_last_index('teacheracadprog',true)
                        }
                          Toast.fire({
                                type: 'warning',
                                title: data[0].data
                          })
                          faculty_acount_datatable()
                    }else{
                          Toast.fire({
                                type: 'warning',
                                title: data[0].data
                          })
                    }
              }
        })
      }
    })
  </script>

  <script>
    //update course
    $(document).ready(function(){
      $(document).on('click','.fas_course',function(){
          var status = 1
          courseid = $(this).attr('data-id')
          if($(this).prop('checked') == false){
              status = 0
          }
          update_course_assignment(courseid,status)
      })

      function update_course_assignment(course,status){
          $.ajax({
              type:'GET',
              url:'/administrator/setup/accounts/update/chairperson',
              data:{
                  teacher:selectedid,
                  courseid:course,
                  status:status,
                  syid:$('#filter_acad_sy').val()
              },
              success:function(data) {
                if(data[0].status == 1){
                    Toast.fire({
                        type: 'success',
                        title: 'Updated Successfully!'
                    })
                    faculty_acount_datatable()
                }else{
                    Toast.fire({
                        type: 'error',
                        title: 'Something went wrong!'
                    })
                }
              }
          })
      }
    })
  </script>

  <script>
    //update dean
    $(document).ready(function(){
      $(document).on('click','.fas_college',function(){
          var status = 1
          collegeid = $(this).attr('data-id')
          if($(this).prop('checked') == false){
              status = 0
          }
          update_college_assignment(collegeid,status)
      })

      function update_college_assignment(collegeid,status){
          $.ajax({
              type:'GET',
              url:'/administrator/setup/accounts/update/dean',
              data:{
                  teacher:selectedid,
                  collegeid:collegeid,
                  status:status,
                  syid:$('#filter_acad_sy').val()
              },
              success:function(data) {
                if(data[0].status == 1){
                    Toast.fire({
                        type: 'success',
                        title: 'Updated Successfully!'
                    })
                    faculty_acount_datatable()
                }else{
                    Toast.fire({
                        type: 'error',
                        title: 'Something went wrong!'
                    })
                }
              }
          })
      }
    })
  </script>
  <script>
    //deactivate
    $(document).ready(function(){
      $(document).on('click','.status',function(){

          var url = '/administrator/setup/accounts/update/active'

          // if(school_setup.setup == 1 && ( school_setup.processsetup == 'hybrid1' || school_setup.processsetup == 'hybrid2' )){
          //     url = school_setup.es_cloudurl+'administrator/setup/accounts/update/active'
          // }

          var status = $(this).attr('data-status')

          var teacherid = $(this).attr('id')

          $.ajax({
              type:'GET',
              url:url,
              data:{
                  teacher:selectedid,
                  status:status
              },
              success:function(data) {
                  if(data[0].status == 1){

                      if(school_setup.setup == 1 && ( school_setup.processsetup == 'hybrid1' || school_setup.processsetup == 'hybrid2' )){
                        get_updated('teacher')
                      }
                      
                      Toast.fire({
                          type: 'success',
                          title: data[0].data
                      })
                      $('#view_fasinfo_modal').modal('hide')
                      faculty_acount_datatable()
                  }else{
                      Toast.fire({
                          type: 'error',
                          title: 'Something went wrong!'
                      })
                  }
              }
          })

        })

    })
  </script>

  <script>
    $(document).ready(function(){

      $('#filter_sy').select2()
      $('#filter_utype').select2()
      $('.select2').select2()

      const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
      })
      
      var fas_acadprog = []
      var fas_teacher = []
      var all_teacher = []
      var selected_teacher = null
      var enable_button = true

      if(school_setup.projectsetup == 'online' &&  school_setup.processsetup == 'hybrid1'){
            enable_button = false;
      }
      
      $(document).on('change','#filter_sy',function(){
            display_acad()
            get_all_teachers()
      })

      $(document).on('change','#filter_utype',function(){
            if($(this).val() == 1){
                  $('#teacher_type_holder').removeAttr('hidden')
            }else{
                  $('#teacher_type_holder').attr('hidden','hidden')
            }
            display_acad()
            get_all_teachers()
      })

      $(document).on('change','#teacherid',function(){
            if(selected_teacher == null){
                  var temp_teacherid = $(this).val()

                  if(temp_teacherid == null){
                        return false
                  }

                  var temp_acadprog = []
                  var filtered_acadprog = fas_acadprog.filter(x=>x.teacherid == temp_teacherid)
                  $.each(filtered_acadprog,function(a,b){
                        temp_acadprog.push(b.acadprogid)
                  })
                  $('#acadprog_fas').val(temp_acadprog).change()
            }
      })

      $(document).on('click','#fasacad_modal_create',function(){

            $('#teacherid').empty()
            $("#teacherid").append('<option value="">Select Teacher</option>');
            $("#teacherid").select2({
                  data: all_teacher,
                  placeholder: "Select Teacher",
                  allowClear:true
            })


            $('#teacherid').val("").change();
            $('#acadprog_fas').val("").change();
            $('#teacherid').removeAttr('disabled')
            selected_teacher = null
            $('#fasacad_modal').modal()
      })

      $(document).on('click','.update_fas',function(){
            var temp_teacherid = $(this).attr('data-id')

            var temp_teachers = all_teacher
            var temp_info = fas_teacher.filter(x=>x.teacherid == temp_teacherid)
            var temp_teachers  = [{
                  id:temp_teacherid,
                  text:temp_info[0].tid + ' - ' +temp_info[0].teachername
            }]

            $('#teacherid').empty()
            $("#teacherid").append('<option value="">Select Teacher</option>');
            $("#teacherid").select2({
                  data: temp_teachers,
                  placeholder: "Select Teacher",
                  allowClear:true
            })


            selected_teacher = temp_teacherid
            $('#teacherid').val(temp_teacherid).change();
            $('#teacherid').attr('disabled','disabled')
            var temp_acadprog = []
            var filtered_acadprog = fas_acadprog.filter(x=>x.teacherid == temp_teacherid)
            $.each(filtered_acadprog,function(a,b){
                  temp_acadprog.push(b.acadprogid)
            })

            

            $('#acadprog_fas').val(temp_acadprog).change()
            $('#fasacad_modal').modal()
      })

      $(document).on('click','#fasacad_modal_copy',function(){
          
            $('#copy_fasacad_modal').modal()
      })

      var acad = @json($academic_prog);

      $("#acadprog_fas").select2({
            data: acad,
            placeholder: "Select a academic program",
            theme: 'bootstrap4'
      })

      

      $(document).on('click','#save_fasacadprog_button',function(){
            save_acadprog()
      })

      $(document).on('click','#fas_acad_prog',function(){
        display_acad()
        get_all_teachers()
        $('#fas_acadprog_modal').modal()
      })

      $(document).on('click','#remove_account',function(){

        Swal.fire({
              text: 'Are you sure you remove account?',
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Remove'
        }).then((result) => {
          if (result.value) {
            $.ajax({
              type:'GET',
              url:'/administrator/setup/accounts/remove',
              data:{
                    teacher:selectedid,
              },
              success:function(data) {
                    if(data[0].status == 1){
                          selectedid = null
                          $('#view_fasinfo_modal').modal('hide')
                          get_deleted('teacher')
                          get_deleted('faspriv')
                          get_deleted('teacheracadprog')
                          faculty_acount_datatable()
                          Toast.fire({
                                type: 'success',
                                title: data[0].message
                          })
                    }else{
                          Toast.fire({
                                type: 'error',
                                title: data[0].message
                          })
                    }
              }
            })
          }
        })
      
      })

      $(document).on('click','#copy_fasacadprog_button',function(){
        if($('#copy_sy_from').val() == ""){
              Toast.fire({
                    type: 'info',
                    title: "No S.Y. Selected"
              })
              return false
        }
        $.ajax({
          type:'GET',
          url:'/setup/useracadprog/copy',
          data:{
                syid_to:$('#filter_sy').val(),
                syid_from:$('#copy_sy_from').val(),
                utype:$('#filter_utype').val()
          },
          success:function(data) {
                if(data[0].status == 1){
                      if(school_setup.setup == 1 && ( school_setup.processsetup == 'hybrid1' || school_setup.processsetup == 'hybrid2' ) ){
                        get_last_index('teacheracadprog',true)
                      }
                      display_acad()
                      faculty_acount_datatable()

                      Toast.fire({
                            type: 'warning',
                            title: data[0].data
                      })
                }else{
                      Toast.fire({
                            type: 'warning',
                            title: data[0].data
                      })
                }
          }
        })
      })

      function save_acadprog(){
        $.ajax({
          type:'GET',
          url:'/setup/useracadprog/create',
          data:{
                syid:$('#filter_sy').val(),
                usertype:$('#filter_utype').val(),
                teacherid:$('#teacherid').val(),
                acadprog:$('#acadprog_fas').val()
          },
          success:function(data) {
            if(data[0].status == 1){
                  if(school_setup.setup == 1 && ( school_setup.processsetup == 'hybrid1' || school_setup.processsetup == 'hybrid2' ) ){
                    get_last_index('teacheracadprog',true)
                  }
                  display_acad()
                  faculty_acount_datatable()
                  Toast.fire({
                        type: 'warning',
                        title: data[0].data
                  })
            }else{
                  Toast.fire({
                        type: 'warning',
                        title: data[0].data
                  })
            }
          }
        })
      }

      

      function get_all_teachers(){
        $.ajax({
          type:'GET',
          url:'/setup/useracadprog/teachers',
          data:{
                usertype:$('#filter_utype').val(),
                syid:$('#filter_sy').val()
          },
          success:function(data) {
            all_teacher = data
            $('#teacherid').empty()
            $("#teacherid").append('<option value="">Select Teacher</option>');
            $("#teacherid").select2({
                  data: all_teacher,
                  placeholder: "Select Teacher",
                  allowClear:true
            })
          }
        })
      }

      function display_acad(){
        $("#fasacadprog_datatable").DataTable({
          destroy: true,
          autoWidth: false,
          stateSave: true,
          serverSide: true,
          processing: true,
          ajax:{
            url: '/setup/useracadprog/list',
            type: 'GET',
            data: {
                  syid:$('#filter_sy').val(),
                  usertype:$('#filter_utype').val()
            },
            dataSrc: function ( json ) {
                  fas_teacher = json.data,
                  fas_acadprog = json.acadprog
                  return json.data;
            }
          },
          columns: [
                  { "data": "tid" },
                  { "data": "teachername" },
                  { "data": null},
                ],
              columnDefs: [
                    {
                          'targets': 0,
                          'orderable': false, 
                    },
                    {
                          'targets': 1,
                          'orderable': false, 
                    },
                    {
                          'targets': 2,
                          'orderable': false, 
                          'createdCell':  function (td, cellData, rowData, row, col) {
                                var filter_acad = fas_acadprog.filter(x=>x.teacherid == rowData.teacherid)
                                var text = ''
                                $.each(filter_acad,function(a,b){
                                      text += '<span class="badge badge-info ml-2">'+b.acadprogcode+'</span>'
                                })
                                $(td)[0].innerHTML = text
                          }
                    },
              ],
              createdRow: function (row, data, dataIndex) {
                    if(enable_button){
                          $(row).attr("data-id",data.teacherid);
                          $(row).addClass("update_fas");
                    }
                  
              },
        })
        if(enable_button){
              var label_text = $($('#fasacadprog_datatable_wrapper')[0].children[0])[0].children[0]
              $(label_text)[0].innerHTML = ' <button class="btn btn-primary btn-sm mr-2" id="fasacad_modal_create"><i class="fa fa-plus"></i> Add</button><button class="btn btn-warning btn-sm" id="fasacad_modal_copy"><i class="fa fa-copy"></i> Copy</button>'
        }else{
              var label_text = $($('#fasacadprog_datatable_wrapper')[0].children[0])[0].children[0]
              $(label_text)[0].innerHTML = ''
        }
      }
    })
  </script>

  <script>
    $(document).ready(function(){

          var keysPressed = {};

          document.addEventListener('keydown', (event) => {
                keysPressed[event.key] = true;
                if (keysPressed['p'] && event.key == 'v') {
                      Toast.fire({
                                  type: 'warning',
                                  title: 'Date Version: 08/30/2022'
                            })
                }
          });

          document.addEventListener('keyup', (event) => {
                delete keysPressed[event.key];
          });

          const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
          })
    })
</script>

@endsection


