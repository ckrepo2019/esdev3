

@extends('hr.layouts.app')
@section('content')
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Add new employee</h1>
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
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="/hr/employees/addnewemployee/save" method="get" class="p-2">
                    <div class="row mb-2">
                        <div class="col-md-3">
                            <label>First Name</label>
                            <input type="text" name="firstname" class="form-control form-control-sm text-uppercase" required/>
                        </div>
                        <div class="col-md-3">
                            <label>Middle Name</label>
                            <input type="text" name="middlename" class="form-control form-control-sm text-uppercase"/>
                        </div>
                        <div class="col-md-3">
                            <label>Last Name</label>
                            <input type="text" name="lastname" class="form-control form-control-sm text-uppercase" required/>
                        </div>
                        <div class="col-md-3">
                            <label>Suffix</label>
                            <input type="text" name="suffix" class="form-control form-control-sm text-uppercase"/>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3">
                            <label>Date of Birth</label>
                            <input type="date" name="dob" class="form-control form-control-sm text-uppercase" required/>
                        </div>
                        <div class="col-md-3">
                            <label>Gender</label>
                            <select name="gender" class="form-control form-control-sm text-uppercase">
                                <option value="male">MALE</option>
                                <option value="female">FEMALE</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Civil Status</label>
                            <select name="civilstatus" class="form-control form-control-sm text-uppercase">
                                @foreach($civilstatus as $status)
                                    <option value="{{$status->id}}">{{$status->civilstatus}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Contact #</label>
                            <input type="text" name="contactnumber" minlength="13" maxlength="13" data-inputmask-clearmaskonlostfocus="true" class="form-control form-control-sm text-uppercase" />
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label>Home Address</label>
                            <input type="text" name="homeaddress" class="form-control form-control-sm text-uppercase" required/>
                        </div>
                        <div class="col-md-6">
                            <label>Email Address</label>
                            <input type="email" name="emailaddress" class="form-control form-control-sm"/>
                        </div>
                    </div>
                    <hr style="border: 1px solid #007bff;">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label>Spouse Employment</label>
                            <input type="text" name="spouseemployment" class="form-control form-control-sm text-uppercase"/>
                        </div>
                        <div class="col-md-3">
                            <label>No. of children</label>
                            <input type="number" name="numofchildren" class="form-control form-control-sm text-uppercase"/>
                        </div>
                    </div>
                    <hr style="border: 1px solid #007bff;">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label>Emergency Contact Name</label>
                            <input type="text" name="emergencycontactname" class="form-control form-control-sm text-uppercase"/>
                        </div>
                        <div class="col-md-3">
                            <label>Relation</label>
                            <input type="text" name="emergencycontactrelation" class="form-control form-control-sm text-uppercase"/>
                        </div>
                        <div class="col-md-3">
                            <label>Emergency contact #</label>
                            <input type="text" name="emergencycontactnumber" minlength="13" maxlength="13" data-inputmask-clearmaskonlostfocus="true" class="form-control form-control-sm text-uppercase"/>
                        </div>
                    </div>
                    <hr style="border: 1px solid #007bff;">
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <label>Religion</label>
                            <select name="religionid" class="form-control form-control-sm text-uppercase">
                                <option>Select Religion</option>
                                @foreach($religions as $religion)
                                    <option value="{{$religion->id}}">{{$religion->religionname}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Nationality</label>
                            <select name="nationalityid" class="form-control form-control-sm text-uppercase">
                                {{-- <option>Select Nationality</option> --}}
                                @foreach($nationalities as $nationality)
                                    <option value="{{$nationality->id}}" {{$nationality->nationality == "Filipino" ? 'selected' : ''}}>{{$nationality->nationality}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <hr style="border: 1px solid #007bff;">
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <label>Office</label>
                            <select name="departmentid" class="form-control form-control-sm text-uppercase">
                                <option>Select Office</option></option>
                                @foreach($departments as $department)
                                    <option value="{{$department->id}}">{{$department->department}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Designation</label>
                            <select name="designationid" class="form-control form-control-sm text-uppercase" required>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <div class="form-group clearfix" id="academicprogram"></div>
                        </div>
                    </div>
                    <hr style="border: 1px solid #007bff;">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label>License Number</label>
                            <input type="text" name="licensenumber" class="form-control form-control-sm text-uppercase" />
                        </div>
                        <div class="col-md-3">
                        </div>
                        <div class="col-md-3">
                            <label>Date Hired</label>
                            <input type="date" name="datehired" class="form-control form-control-sm text-uppercase" required/>
                        </div>
                    </div>
                    <br>
                    <div class="submit-section">
                        <button type="submit" class="btn btn-primary btn-block submit-btn float-right">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
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
    $(document).on('change','select[name="departmentid"]', function(){
        $.ajax({
            url: '/hr/employees/getdesignations',
            type:"GET",
            dataType:"json",
            data:{
                departmentid:$(this).val(),
            },
            success:function(data) {
                $('select[name="designationid"]').empty();
                if(data.length == '0'){
                    $('select[name="designationid"]').append(
                            '<option value="">NO DESIGNATIONS ASSIGNED!</option>'
                    );

                }else{
                    $('select[name="designationid"]').append(
                        '<option>Select Designation</option>'
                    );
                    $.each(data, function(key, value){
                        $('select[name="designationid"]').append(
                            '<option value="'+value.id+'">'+value.utype+'</option>'
                        );
                    });
                }
            }
        });
    });
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
        $('input[name="contactnumber"]').inputmask({mask: "9999-999-9999"});
        $('input[name="emergencycontactnumber"]').inputmask({mask: "9999-999-9999"});
        
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
    $(document).on("input", 'input[name="numofchildren"]', function() {
        this.value = this.value.replace(/\D/g,'');
    });
    // $(document).on("input", 'input[name="licensenumber"]', function() {
    //     this.value = this.value.replace(/\D/g,'');
    // });
</script>
@endsection
