
@php
    
    $check_refid = DB::table('usertype')->where('id',Session::get('currentPortal'))->select('refid')->first();

    if(Session::get('currentPortal') == 14){    
        $extend = 'deanportal.layouts.app2';
    }else if(Session::get('currentPortal') == 3){
        $extend = 'registrar.layouts.app';
    }else if(Session::get('currentPortal') == 8){
        $extend = 'admission.layouts.app2';
    }else if(Session::get('currentPortal') == 1){
        $extend = 'teacher.layouts.app';
    }else if(Session::get('currentPortal') == 2){
        $extend = 'principalsportal.layouts.app2';
    }else if(Session::get('currentPortal') == 4){
        $extend = 'finance.layouts.app';
    }else if(Session::get('currentPortal') == 15){
        $extend = 'finance.layouts.app';
    }else if(Session::get('currentPortal') == 18){
        $extend = 'ctportal.layouts.app2';
    }else if(Session::get('currentPortal') == 10){
        $extend = 'hr.layouts.app';
    }else if(Session::get('currentPortal') == 16){
        $extend = 'chairpersonportal.layouts.app2';
    }else if(Session::get('currentPortal') == 16){
        $extend = 'chairpersonportal.layouts.app2';
    }else if(Session::get('currentPortal')== 17){
        $extend = 'superadmin.layouts.app2';
    }else{
        if(isset($check_refid->refid)){
            if($check_refid->refid == 27){
                $extend = 'academiccoor.layouts.app2';
            }else if($check_refid->refid == 22){
                $extend = 'principalcoor.layouts.app2';
            }else if($check_refid->refid == 29){
                $extend = 'idmanagement.layouts.app2';
            }else{
                $extend = 'general.defaultportal.layouts.app';
            }
        }else{
            $extend = 'general.defaultportal.layouts.app';
        }
    }
    @endphp

@extends($extend)

@section('content')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<style>
    .shadow {
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
        border: 0 !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        margin-top: -9px;
    }
</style>

@php
   $sy = DB::table('sy')->orderBy('sydesc')->get(); 
   $semester = DB::table('semester')->orderBy('semester')->where('id','!=',3)->get(); 
@endphp

<section class="content-header pt-0">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Quarter Remarks</h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item active">Quarter Remarks</li>
            </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
      <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="">School year</label>
                                    <select class="form-control select2" id="input_sy">
                                          @foreach ($sy as $item)
                                                @php
                                                    $active = $item->isactive == 1 ? 'selected="seleted"' : ''
                                                @endphp
                                                <option value="{{$item->id}}" {{$active}}>{{$item->sydesc}}</option>
                                          @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="">Section</label>
                                    <select class="form-control select2" id="input_section">
                                        <option value="" selected="selected">Select Section</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Student</label>
                                    <select class="form-control select2" id="input_student">
                                        <option value="" selected="selected">Select Student</option>
                                    </select>
                                </div>
                                <div class="col-md-2 sem_holder" hidden >
                                    <label for="">Semester</label>
                                    <select class="form-control select2" id="input_sem">
                                          @foreach ($semester as $item)
                                                @php
                                                    $active = $item->isactive == 1 ? 'selected="seleted"' : ''
                                                @endphp
                                                <option value="{{$item->id}}" {{$active}}>{{$item->semester}}</option>
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
                        <div class="card shadow" >
                              <div class="card-header">
                                <h3 class="card-title">
                                    Quarter Remarks
                                </h3>
                              </div>
                              <div class="card-body">
                                    <div class="row ">
                                            <div class="col-md-12">
                                                <label for="">Quarter 1</label>  
                                                <textarea name="" id="" rows="3" class="form-control form-control-sm grade_input" quarter="1"></textarea>
                                            </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-md-12">
                                            <label for="">Quarter 2</label>  
                                            <textarea name="" id="" rows="3" class="form-control form-control-sm grade_input" quarter="2"></textarea>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-md-12">
                                            <label for="">Quarter 3</label>  
                                            <textarea name="" id="" rows="3" class="form-control form-control-sm grade_input" quarter="3"></textarea>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-md-12">
                                            <label for="">Quarter 4</label>  
                                            <textarea name="" id="" rows="3" class="form-control form-control-sm grade_input" quarter="4"></textarea>
                                        </div>
                                    </div>
                              </div>
                              <div class="card-footer border-0">
                                    <button class="btn btn-primary btn-sm" id="save_button_1" disabled><i class="fas fa-save" ></i> Save</button>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
</section>

@endsection

@section('footerjavascript')
<script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    $(document).ready(function(){

        $('.select2').select2()

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
            })
            
            $('.grade_input').attr('readonly','readonly')
            
            $("#input_student").select2({
                data: [],
                allowClear: true,
                placeholder: "Select Student",
            })

            
            $(document).on('change','#input_student',function(){
                if($(this).val() == "" ){
                    $('.grade_input').attr('readonly','readonly')
                    $('.grade_input[quarter="1"]').val("")
                    $('.grade_input[quarter="2"]').val("")
                    $('.grade_input[quarter="3"]').val("")
                    $('.grade_input[quarter="4"]').val("")
                    $('#save_button_1').attr('disabled','disabled')
                }else{
                    $('.grade_input').removeAttr('readonly')
                    $('#save_button_1').removeAttr('disabled')
                    get_grades()
                }
              
            })

            function get_grades(){
                var syid = $('#input_sy').val()
                var studid =  $('#input_student').val()
                $.ajax({
                        type:'GET',
                        url: '/reportcard/quarterremarks/advisory/grades',
                        data:{
                            'syid' : syid,
                            'studid': studid
                        },
                        success:function(data) {
                            if(data.length > 0){
                                $('.grade_input[quarter="1"]').val(data[0].q1remarks)
                                $('.grade_input[quarter="2"]').val(data[0].q2remarks)
                                $('.grade_input[quarter="3"]').val(data[0].q3remarks)
                                $('.grade_input[quarter="4"]').val(data[0].q4remarks)
                            }else{
                                $('.grade_input[quarter="1"]').val("")
                                $('.grade_input[quarter="2"]').val("")
                                $('.grade_input[quarter="3"]').val("")
                                $('.grade_input[quarter="4"]').val("")
                            }
                        }
                });
            }

            $(document).on('input','.grade_input',function(){
                $(this).addClass('updated')
            })



            $(document).on('click','#save_button_1',function(){
                var count = 0;
                var updated_length = $('.updated').length

                if(updated_length == 0){
                    Toast.fire({
                        type: 'info',
                        title: 'No changes made'
                    })
                    return false
                }
                
                temp_quarter = 1
                store_grades()
                
            })
            
            var temp_quarter = 1
            function store_grades(){
                
                if($('.updated[quarter="'+temp_quarter+'"]').length == 0){
                    if(temp_quarter == 4){
                        Toast.fire({
                                type: 'success',
                                title: 'Updated Successfully'
                        })
                        return false;
                    }else{
                        temp_quarter += 1;
                        store_grades()
                        return false;
                    }
                }

                var updated_length = $('.updated[quarter="'+temp_quarter+'"]').length
                var count = 0
                
                $('.updated[quarter="'+temp_quarter+'"]').each(function(){
                    var value = $(this).val();
                    var quarter = $(this).attr('quarter')
                    var temp_updated = $(this)
                    $.ajax({
                        type:'GET',
                        url: '/reportcard/quarterremarks/advisory/grades/save',
                        data:{
                            syid:$('#input_sy').val(),
                            'value':value,
                            'quarter':quarter,
                            'studid':$('#input_student').val()
                        },
                        success:function(data) {
                            if(data[0].status == 1){
                                temp_updated.removeClass('updated')
                                count += 1
                                if(count == updated_length){
                                    if(temp_quarter == 4){
                                        Toast.fire({
                                                type: 'success',
                                                title: 'Updated Successfully'
                                        })
                                    }else{
                                        temp_quarter += 1;
                                        store_grades()
                                    }
                                }
                            }else{
                                Toast.fire({
                                        type: 'error',
                                        title: 'Something went wrong'
                                })
                            }
                          
                        },
                        error:function(){
                            Toast.fire({
                                type: 'error',
                                title: 'Something went wrong.'
                            })
                        }
                    })

                })
                
            }

            var all_sections = []
            var section_levelid = null
            get_sections()

            $(document).on('change','#input_sy',function(){
                $('.sem_holder').attr('hidden','hidden')
                $('.grade_input').attr('readonly','readonly')
                selected_section = null
                var temp_students = []
                $("#input_student").empty();
                $("#input_student").append('<option value="" selected="selected">Select Student</option>')
                $("#input_student").select2({
                        data: temp_students,
                        allowClear: true,
                        placeholder: "Select Student",
                })
                all_sections = []
                $("#input_section").empty()
                $("#input_section").append('<option value="">Section Section</option>')
                $("#input_section").select2({
                        data: all_sections,
                        allowClear: true,
                        placeholder: "Select Section",
                })
                $('#save_button_1').attr('disabled','disabled')
                get_sections()
            })

            function get_sections(){
                $.ajax({
					type:'GET',
					url: '/reportcard/quarterremarks/advisory',
                    data:{
                        syid:$('#input_sy').val()
                    },
					success:function(data) {
                        all_sections = data
                        if(data.length > 0){
                            Toast.fire({
                                type: 'warning',
                                title: data.length+' section(s) found!'
                            })
                            $("#input_section").select2({
                                    data: all_sections,
                                    allowClear: true,
                                    placeholder: "Select Section",
                            })
                        }else{
                            Toast.fire({
                                type: 'warning',
                                title: 'No section found!'
                            })
                            $("#input_section").empty()
                            $("#input_section").append('<option value="">Section Section</option>')
                            $("#input_section").select2({
                                    data: all_sections,
                                    allowClear: true,
                                    placeholder: "Select Section",
                            })
                        }
					}
			    })
            }

            var selected_section

            $(document).on('change','#input_section',function(){
                $('.sem_holder').attr('hidden','hidden')
                $('.grade_input').attr('readonly','readonly')
                $('#data').empty();
                if($(this).val() == ""){
                    var temp_students = []
                    selected_section = null
                    $('.grade_option').val("").change()
                    $('.grade_option').attr('disabled','disabled')
                    $('#save_button_1').attr('disabled','disabled')
                    display_students()
                    return false
                }else{
                    var temp_id = $(this).val()
                }

                var temp_students = []
                $("#input_student").empty();
                $("#input_student").append('<option value="" selected="selected">Select Student</option>')
                $("#input_student").select2({
                        data: temp_students,
                        allowClear: true,
                        placeholder: "Select Student",
                })

                $('#save_button_1').attr('disabled','disabled')
                selected_section = $(this).val() 
                var levelid = all_sections.filter(x=>x.id == selected_section)[0].levelid
                display_students()
            })

            var observedvalues = []
            var all_rating = []

            function display_students(){

                if(selected_section != "" && selected_section != null){
                    var temp_students = all_sections.filter(x=>x.id == selected_section)[0].students
                    $("#input_student").empty();
                    $("#input_student").append('<option value="" selected="selected">Select Student</option>')
                    $("#input_student").select2({
                            data: temp_students,
                            allowClear: true,
                            placeholder: "Select Student",
                    })
                }else{
                    var temp_students = []
                    $("#input_student").empty();
                    $("#input_student").select2({
                            data: temp_students,
                            allowClear: true,
                            placeholder: "Select Student",
                    })
                }

            }

            var sy = @json($sy)




    })
</script>
    

@endsection