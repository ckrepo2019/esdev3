

@extends('hr.layouts.app')
@section('content')
<section class="content-header p-0">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h4>Add new employee</h4>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item"><a href="/hr/employees/index">Employees</a></li>
            <li class="breadcrumb-item active">Add new employee</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
</section>
<hr class="m-1"/>
@php
    $offices = DB::table('hr_offices')
        ->select('hr_offices.*','sy.sydesc')
        ->join('sy','hr_offices.syid','=','sy.id')
        ->where('hr_offices.deleted','0')
        ->get();
    $departments = DB::table('hr_departments')
        ->where('deleted','0')
        ->get();

    $usertypes = DB::table('usertype')
        ->where('deleted','0')
        ->where('id','!=','7')
        ->where('id','!=','9')
        ->get();
@endphp
<form action="/hr/employees/addnewemployee/save" method="get" class="m-0 p-0">
    <div class="row mb-2">
        <div class="col-md-12 text-right">
            <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Submit</button>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="card shadow" style="border: 1px solid #ddd; box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
                <div class="card-header p-2 bg-warning">
                    <h5 class="m-0">Personal Information</h5>
                </div>
                <div class="card-body p-2">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control form-control-sm"/>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>Last Name</label>
                            <input type="text" name="lastname" class="form-control form-control-sm" required/>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>First Name</label>
                            <input type="text" name="firstname" class="form-control form-control-sm" required/>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>Middle Name</label>
                            <input type="text" name="middlename" class="form-control form-control-sm"/>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>Suffix</label>
                            <input type="text" name="suffix" class="form-control form-control-sm"/>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>Gender</label>
                            <select name="gender" class="form-control form-control-sm text-uppercase">
                                <option value="male">MALE</option>
                                <option value="female">FEMALE</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>Date of Birth</label>
                            <input type="date" name="dob" class="form-control form-control-sm"/>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>Civil Status</label>
                            <select name="civilstatus" class="form-control form-control-sm text-uppercase">
                                @foreach($civilstatus as $status)
                                    <option value="{{$status->id}}">{{$status->civilstatus}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>Contact No.</label>
                            <input type="text" name="contactnumber" class="form-control form-control-sm" minlength="11" maxlength="11" data-inputmask-clearmaskonlostfocus="true"/>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>Email Address</label>
                            <input type="text" name="emailaddress" class="form-control form-control-sm"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow" style="border: 1px solid #ddd; box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
                <div class="card-header p-2 bg-warning">
                    <h5 class="m-0">Present Address</h5>
                </div>
                <div class="card-body p-2">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label>Street</label>
                            <input type="text" name="presstreet" class="form-control form-control-sm"/>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>Barangay</label>
                            <input type="text" name="presbarangay" class="form-control form-control-sm"/>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>City</label>
                            <input type="text" name="prescity" class="form-control form-control-sm"/>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>Province</label>
                            <input type="text" name="presprovince" class="form-control form-control-sm"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card shadow" style="border: 1px solid #ddd; box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
                <div class="card-header p-2 bg-warning">
                    <h5 class="m-0">Emergency Contact Info</h5>
                </div>
                <div class="card-body p-2">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label>Name</label>
                            <input type="text" name="emername" class="form-control form-control-sm"/>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>Relation</label>
                            <input type="text" name="emerrelation" class="form-control form-control-sm"/>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>Contact No.</label>
                            <input type="text" name="emercontactno" class="form-control form-control-sm" minlength="11" maxlength="11" data-inputmask-clearmaskonlostfocus="true"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow" style="border: 1px solid #ddd; box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
                <div class="card-header p-2 bg-warning">
                    <h5 class="m-0">Other Info</h5>
                </div>
                <div class="card-body p-2">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label>Spouse Employment</label>
                            <input type="text" name="spouseemp" class="form-control form-control-sm"/>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>No. of children</label>
                            <input type="number" name="noofchildren" class="form-control form-control-sm"/>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>Religion</label>
                            <select name="religionid" class="form-control form-control-sm text-uppercase">
                                <option>Select Religion</option>
                                @foreach($religions as $religion)
                                    <option value="{{$religion->id}}">{{$religion->religionname}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>Nationality</label>
                            <select name="nationalityid" class="form-control form-control-sm text-uppercase">
                                <option value="0">None</option>
                                @foreach($nationalities as $nationality)
                                    <option value="{{$nationality->id}}" {{$nationality->nationality == "Filipino" ? 'selected' : ''}}>{{$nationality->nationality}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>Office assigned to</label>
                            <select name="officeid" class="form-control form-control-sm text-uppercase">
                                <option value="0">None</option>
                                @foreach($offices as $office)
                                    <option value="{{$office->id}}">{{$office->sydesc}} - {{$office->officename}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>Department</label>
                            <select name="departmentid" class="form-control form-control-sm text-uppercase">
                                <option value="0">None</option>
                                @foreach($departments as $department)
                                    <option value="{{$department->id}}">{{$department->department}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>Designation</label>
                            <select name="designationid" class="form-control form-control-sm text-uppercase">
                                <option value="0">None</option>
                                @foreach($usertypes as $usertype)
                                    <option value="{{$usertype->id}}">{{$usertype->utype}}</option>
                                @endforeach
                            </select>
                        </div>
                            <div class="col-md-12">
                                <div class="form-group clearfix" id="academicprogram"></div>
                            </div>
                        <div class="col-md-12 mb-2">
                            <label>License No.</label>
                            <input type="text" name="licensenumber" class="form-control form-control-sm"/>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>Date Hired</label>
                            <input type="date" name="datehired" class="form-control form-control-sm"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<div id="addmoretables" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-2">
                    <div class="col-12 text-success mb-3">
                        New employee added successfully!
                    </div>
                    <div class="col-12">
                        Do you want to add another employee?
                    </div>
                </div>
                <div class="submit-section">
                    <a href="/hr/employees/index" class="btn btn-secondary submit-btn float-left text-white">No</a>
                    <a href="/hr/employees/addnewemployee/index" class="btn btn-primary submit-btn float-right text-white">Yes</a>
                </div>
            </div>
        </div>
    </div>
</div> 
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
<script>
    @if(session()->has('feedback'))
        @if(session()->get('feedback') == '1')
            $("#addmoretables").modal("toggle");
        @endif
    @endif
    $(document).ready(function(){
        $('input.form-control').on('input', function(){            
            if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
            {
                $(this).removeClass('is-valid')
            }else{
                $(this).addClass('is-valid')
            }
        })
    })
    // $(document).on('change','select[name="departmentid"]', function(){
    //     $.ajax({
    //         url: '/hr/employees/getdesignations',
    //         type:"GET",
    //         dataType:"json",
    //         data:{
    //             departmentid:$(this).val(),
    //         },
    //         success:function(data) {
    //             $('select[name="designationid"]').empty();
    //             if(data.length == '0'){
    //                 $('select[name="designationid"]').append(
    //                         '<option value="">NO DESIGNATIONS ASSIGNED!</option>'
    //                 );

    //             }else{
    //                 $('select[name="designationid"]').append(
    //                     '<option>Select Designation</option>'
    //                 );
    //                 $.each(data, function(key, value){
    //                     $('select[name="designationid"]').append(
    //                         '<option value="'+value.id+'">'+value.utype+'</option>'
    //                     );
    //                 });
    //             }
    //         }
    //     });
    // });
    $(document).on('change','select[name="designationid"]', function(){
        $('#academicprogram').empty();
        if($(this)[0].selectedOptions[0].text == 'TEACHER' || $(this)[0].selectedOptions[0].text == 'PRINCIPAL'){
            $('.submit-btn').attr('id','submitbutton');
            $('.submit-btn').prop('type','button');
            $.ajax({
                url: '/hr/employees/getacademicprogram',
                type:"GET",
                dataType:"json",
                success:function(data) {
                    $.each(data, function(key, value){
                        $('#academicprogram').append(
                            '<div class="icheck-primary d-inline">'+
                                '<input type="checkbox" id="checkboxPrimary'+value.id+'" name="academicprogram[]" value="'+value.id+'" checked="">'+
                                '<label for="checkboxPrimary'+value.id+'">'+
                                value.progname+
                                '</label>'+
                            '</div>'+
                            '<br>'
                        );
                    })
                }
            });
        }
    });
    $(document).ready(function(){
        
        // $('body').addClass('sidebar-collapse')
        $('input[name="contactno"]').inputmask({mask: "9999-999-9999"});
        $('input[name="emercontactno"]').inputmask({mask: "9999-999-9999"});
        
    });
    $(document).on('click','#submitbutton', function(){
        if($('input[name="academicprogram[]"]:checked').length == 0){
            $('#academicprogram').prepend(
                '<div class="row">'+
                    '<div class="text-danger">'+
                        'Please select an academic program!'+
                    '</div>'+
                '</div>'
            )
        }else{
            $(this).prop('type','submit');
        }
    })
    $(document).on("input", 'input[name="noofchildren"]', function() {
        this.value = this.value.replace(/\D/g,'');
    });
    // $(document).on("input", 'input[name="licensenumber"]', function() {
    //     this.value = this.value.replace(/\D/g,'');
    // });
</script>
@endsection
