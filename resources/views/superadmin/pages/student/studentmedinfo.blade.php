@php
if(auth()->user()->type == 17){
      $extend = 'superadmin.layouts.app2';
}else if(auth()->user()->type == 3){
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
      </style>
@endsection


@section('content')

@php
      $sy = DB::table('sy')->orderBy('sydesc')->get(); 
      $gradelevel = DB::table('gradelevel')->where('deleted',0)->orderBy('sortid')->get(); 
@endphp

<section class="content-header">
      <div class="container-fluid">
            <div class="row mb-2">
                  <div class="col-sm-6">
                        <h1>Student Medical Information</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Student Medical Information</li>
                  </ol>
                  </div>
            </div>
      </div>
</section>
    
<section class="content pt-0">
    
      <div class="container-fluid">
            <div class="row">
                  
                  <div class="col-md-12">
                        <div class="info-box shadow-lg">
                          <div class="info-box-content">
                              <div class="row">
                                    <div class="col-md-2  form-group mb-0">
                                          <label for="">School Year</label>
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
                                    <div class="col-md-6">
                                          <label for="">Student</label>
                                          <select class="form-control select2" id="filter_student">
                                               <option value="">Select a student</option>
                                          </select>
                                    </div>
                              </div>
                          </div>
                        </div>
                  </div>
            </div>
          
            <div class="row">
                  <div class="col-md-3">
                        <div class="card shadow">
                              <div class="card-body box-profile">
                                    <h3 class="profile-username text-center" id="student_fullname">Student Name</h3>
                                    <p class="text-muted text-center" id="cur_glevel" hidden>Grade Level</p>
                                    <ul class="list-group list-group-unbordered mb-3">
                                          <li class="list-group-item">
                                            <b>SID</b> <a class="float-right" id="label_sid"></a>
                                          </li>
                                    </ul>
                              </div>
                        </div>
                  </div>
                  <div class="col-md-9">
                        <div class="card shadow">
                             <div class="card-body">
                                    <div class="row">
                                         <div class="col-md-2">
                                               <div class="row">
                                                     <div class="col-md-12">
                                                           <label>Vaccinated</label>
                                                     </div>
                                               </div>
                                               <div class="row">
                                                     <div class="col-md-6 form-group">
                                                            <div class="icheck-primary d-inline pt-2">
                                                                  <input type="radio" id="input_vacc_type_yes"  name="vacc" class="vacc" value="1">
                                                                  <label for="input_vacc_type_yes">Yes</label>
                                                            </div> 
                                                     </div>
                                                      <div class="col-md-6">
                                                            <div class="icheck-primary d-inline pt-2">
                                                                  <input type="radio" id="input_vacc_type_no"  name="vacc" class="vacc" value="0">
                                                                  <label for="input_vacc_type_no">No</label>
                                                            </div> 
                                                      </div>
                                               </div>
                                          </div>
                                    </div>
                                    <div class="row">
                                          <div class="col-md-3 form-group">
                                                <label>Type of Vaccine</label>
                                                <input id="vacc_type" class="form-control form-control-sm">
                                          </div>
                                          <div class="col-md-3 form-group">
                                                <label>Vaccination Card #</label>
                                                <input id="vacc_card_id" class="form-control form-control-sm">
                                          </div>
                                          <div class="col-md-3 form-group">
                                                <label>1st Dose Date</label>
                                                <input type="date" id="dose_date_1st" class="form-control form-control-sm">
                                          </div>
                                          <div class="col-md-3 form-group">
                                                <label>2nd Dose Date</label>
                                                <input type="date" id="dose_date_2nd" class="form-control form-control-sm">
                                          </div>
                                    </div>
                                    <div class="row">
                                          <div class="col-md-3 form-group">
                                                <label>Philhealth ID Number</label>
                                                <input id="philhealth" class="form-control form-control-sm">
                                          </div>
                                          <div class="col-md-3 form-group">
                                                <label>Blood Type</label>
                                                <input id="bloodtype" class="form-control form-control-sm">
                                          </div>
                                          <div class="col-md-3 form-group">
                                                <label>Allergy</label>
                                                <input id="allergy" class="form-control form-control-sm">
                                          </div>
                                        
                                    </div>
                                    <div class="row">
                                          <div class="col-md-12 form-group">
                                                <label>Other Medical Information</label>
                                                <input id="other_med_info" class="form-control form-control-sm">
                                          </div>
                                    </div>
                                    <div class="row">
                                          <div class="col-md-12">
                                                <button class="btn btn-sm btn-primary" id="update_info" disabled>Update</button>
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

                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })

                  $('.select2').select2()

                  var all_students = []
                  var x = null
                  load_all_student()

                  $(document).on('change','#filter_student',function(){
                        $('#update_info').removeAttr('disabled')
                        var temp_student = all_students.filter(x=>x.id == $(this).val())
                        $('#label_sid').text(temp_student[0].sid)
                        $('#student_fullname').text(temp_student[0].student)
                        load_student_med_info()
                  })

                  $(document).on('change','#filter_sy',function(){
                        if($('#filter_student').val() != ""){
                              load_student_med_info()
                        }
                  })

                  $(document).on('click','#update_info',function(){
                        if($(this).attr('button_type') == 'create'){
                              create_student_med_info()
                        }else{
                              update_student_med_info()
                        }
                  })

                  function load_all_student(){
                        $.ajax({
                              type:'GET',
                              url:'/superadmin/student/information/all',
                              data:{
                                    syid:$('#filter_sy').val()
                              },
                              success:function(data) {
                                    all_students = data;
                                    $("#filter_student").select2({
                                          data: all_students,
                                          placeholder: "Select a student",
                                    })
                              }
                        })
                  }

                  function create_student_med_info(){
                        $.ajax({
                              type:'GET',
                              url:'/student/medinfo/create',
                              data:{
                                    vacc:$('input[name="vacc"]:checked').val(),
                                    studid:$("#filter_student").val(),
                                    semid:$("#filter_sem").val(),
                                    syid:$('#filter_sy').val(),
                                    vacc_type:$('#vacc_type').val(),
                                    vacc_card_id:$('#vacc_card_id').val(),
                                    dose_date_1st:$('#dose_date_1st').val(),
                                    dose_date_2nd:$('#dose_date_2nd').val(),
                                    philhealth:$('#philhealth').val(),
                                    bloodtype:$('#bloodtype').val(),
                                    allergy:$('#allergy').val(),
                                    other_med_info:$('#other_med_info').val(),
                              },
                              success:function(data) {
                                    if(data[0].status == 1){
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
                              },error:function(){
                                    Toast.fire({
                                          type: 'error',
                                          title: 'Something went wrong!'
                                    })
                              }
                        })
                  }

                  function update_student_med_info(){

                        console.log($('input[name="vacc"]:checked').val())
                        $.ajax({
                              type:'GET',
                              url:'/student/medinfo/update',
                              data:{
                                    id:midinfo_id,
                                    vacc:$('input[name="vacc"]:checked').val(),
                                    studid:$("#filter_student").val(),
                                    semid:$("#filter_sem").val(),
                                    syid:$('#filter_sy').val(),
                                    vacc_type:$('#vacc_type').val(),
                                    vacc_card_id:$('#vacc_card_id').val(),
                                    dose_date_1st:$('#dose_date_1st').val(),
                                    dose_date_2nd:$('#dose_date_2nd').val(),
                                    philhealth:$('#philhealth').val(),
                                    bloodtype:$('#bloodtype').val(),
                                    allergy:$('#allergy').val(),
                                    other_med_info:$('#other_med_info').val(),
                              },
                              success:function(data) {
                                    if(data[0].status == 1){
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
                              },error:function(){
                                    Toast.fire({
                                          type: 'error',
                                          title: 'Something went wrong!'
                                    })
                              }
                        })
                  }

                  function load_student_med_info(){

                        $.ajax({
                              type:'GET',
                              url:'/student/medinfo/list',
                              data:{
                                    studid:$("#filter_student").val(),
                                    semid:$("#filter_sem").val(),
                                    syid:$('#filter_sy').val()
                              },
                              success:function(data) {
                                    if(data.length > 0){
                                          $('input[name="vacc"][value="'+data[0].vacc+'"]').prop('checked',true)
                                          midinfo_id = data[0].id
                                          $('#vacc_type').val(data[0].vacc_type)
                                          $('#vacc_card_id').val(data[0].vacc_card_id)
                                          $('#dose_date_1st').val(data[0].dose_date_1st)
                                          $('#dose_date_2nd').val(data[0].dose_date_2nd)
                                          $('#philhealth').val(data[0].philhealth)
                                          $('#bloodtype').val(data[0].bloodtype)
                                          $('#allergy').val(data[0].allergy)
                                          $('#other_med_info').val(data[0].other_med_info)
                                          $('#update_info').attr('button_type','update')
                                          Toast.fire({
                                                type: 'success',
                                                title: 'Medical Information Found!'
                                          })
                                    }else{
                                          $('input[name="vacc"][value="0"]').prop('checked',true)
                                          $('#vacc_type').val("")
                                          $('#vacc_card_id').val("")
                                          $('#dose_date_1st').val("")
                                          $('#dose_date_2nd').val("")
                                          $('#philhealth').val("")
                                          $('#bloodtype').val("")
                                          $('#allergy').val("")
                                          $('#other_med_info').val("")
                                          midinfo_id = null
                                          $('#update_info').attr('button_type','create')
                                          Toast.fire({
                                                type: 'warning',
                                                title: 'No Medical Information Found!'
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
      </script>


@endsection


