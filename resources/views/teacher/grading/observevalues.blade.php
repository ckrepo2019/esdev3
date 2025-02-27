
@extends('teacher.layouts.app')

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
                <h1>Observed Values</h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item active">Observed Values</li>
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
                                    Observed Values
                                </h3>
                              </div>
                              <div class="card-body">
                                    <div class="row ">
                                          <div class="col-md-12">
                                                <table class="table table-striped table-sm table-bordered table-head-fixed nowrap display p-0" width="100%">
                                                      <thead>
                                                            <tr>
                                                                  <th width="68%" class="align-middle"></th>
                                                                  <th width="8%" class="text-center q1">Q1</th>
                                                                  <th width="8%" class="text-center q2">Q2</th>
                                                                  <th width="8%" class="text-center q3">Q3</th>
                                                                  <th width="8%" class="text-center q4">Q4</th>
                                                            </tr>
                                                      </thead>
                                                      <tbody id="data">

                                                      </tbody>
                                                </table>
                                          </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 text-center" id="legend_holder">

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
            
            
            $("#input_student").select2({
                data: [],
                allowClear: true,
                placeholder: "Select Student",
            })

            $(document).on('change','.grade_option',function(){
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
                                title: 'Saved Successfully'
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
                    var gsdid = $(this).attr('id')
                    var quarter = $(this).attr('quarter')
                    var temp_updated = $(this)
                    $.ajax({
                        type:'GET',
                        url: '/grade/observedvalues/advisory/grades/save',
                        data:{
                            syid:$('#input_sy').val(),
                            'gsdid':gsdid,
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
                                                title: 'Saved Successfully'
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
                $('#data').empty()
                $('#save_button_1').attr('disabled','disabled')
                $('.q1').removeAttr('hidden')
                $('.q2').removeAttr('hidden')
                $('.q3').removeAttr('hidden')
                $('.q4').removeAttr('hidden')
                get_sections()
            })

            function get_sections(){
                $.ajax({
					type:'GET',
					url: '/grade/observedvalues/advisory',
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

                $('.q1').removeAttr('hidden')
                $('.q2').removeAttr('hidden')
                $('.q3').removeAttr('hidden')
                $('.q4').removeAttr('hidden')
                $('#save_button_1').attr('disabled','disabled')
                selected_section = $(this).val() 
                var levelid = all_sections.filter(x=>x.id == selected_section)[0].levelid
                if(levelid == 14 || levelid == 15){
                    setup_shs()
                }
                setup(levelid)
            })

            $(document).on('change','#input_sem',function(){
                setup_shs()
            })


            function setup_shs(){
                $('.sem_holder').removeAttr('hidden')
                if($('#input_sem').val() == 1){
                    $('.q1').removeAttr('hidden')
                    $('.q2').removeAttr('hidden')
                    $('.q3').attr('hidden','hidden')
                    $('.q4').attr('hidden','hidden')
                }else{
                    $('.q3').removeAttr('hidden')
                    $('.q4').removeAttr('hidden')
                    $('.q1').attr('hidden','hidden')
                    $('.q2').attr('hidden','hidden')
                }
            }

            var observedvalues = []
            var all_rating = []

            function setup(levelid) {
                $.ajax({
                    type:'GET',
                    url: '/superadmin/setup/observed/values/list',
                    data:{
                        syid:$('#input_sy').val(),
                        gradelevel:levelid
                    },
                    success:function(data) {
                        if(data.length == 0){
                            Toast.fire({
                                type: 'info',
                                title: 'No Observed Values Setup.'
                            })
                            $('.grade_option').attr('disabled','disabled')
                            $('#save_button_1').attr('disabled','disabled')
                            $('.updated').removeClass('updated')
                            display_students()
                        }else{
                            observedvalues = data
                            rating(levelid)
                        }
                    }
                })
            }

            function rating(levelid) {
                $.ajax({
                    type:'GET',
                    url: '/superadmin/setup/ratingvalue/list',
                    data:{
                        syid:$('#input_sy').val(),
                        gradelevel:levelid
                    },
                    success:function(data) {
                        if(data.length == 0){
                            Toast.fire({
                                type: 'info',
                                title: 'No Rating Setup.'
                            })
                            $('.grade_option').attr('disabled','disabled')
                            $('#save_button_1').attr('disabled','disabled')
                            $('.updated').removeClass('updated')
                        }else{
                            all_rating = data

                            var legend_text = '| '
                            $.each(all_rating,function(a,b){
                                legend_text += '<span class="badge badge-primary">'+b.value+'</span> - '+b.description + ' | '
                            })
                            $('#legend_holder')[0].innerHTML = legend_text
                            plot_setup(levelid)
                        }
                        
                    }
                })
            }

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

            $(document).on('change','#input_student',function(){
                $('.grade_option').val("").change()

                if($(this).val() == ""){
                    $('.grade_option').attr('disabled','disabled')
                    $('#save_button_1').attr('disabled','disabled')
                    return false
                }


          

                $.ajax({
					type:'GET',
					url: '/grade/observedvalues/advisory/grades',
                    data:{
                        syid:$('#input_sy').val(),
                        'studid':$('#input_student').val()
                    },
					success:function(data) {
					    
                        var selected_sy = sy.filter(x=>x.id == $('#input_sy').val())
                        console.log(selected_sy)
					    if(data.length > 0){
					        $.each(data,function(a,b){
                                $('.grade_option[quarter="1"][id="'+b.gsdid+'"]').val(b.q1eval).change()
                                $('.grade_option[quarter="2"][id="'+b.gsdid+'"]').val(b.q2eval).change()
                                $('.grade_option[quarter="3"][id="'+b.gsdid+'"]').val(b.q3eval).change()
                                $('.grade_option[quarter="4"][id="'+b.gsdid+'"]').val(b.q4eval).change()
                            })
                            $('.grade_option').removeAttr('disabled')
                            $('#save_button_1').removeAttr('disabled')
                            $('.updated').removeClass('updated')
					    }else{
					        Toast.fire({
                                type: 'info',
                                title: 'No grades available.'
                            })
                            $('.grade_option').removeAttr('disabled')
                            $('#save_button_1').removeAttr('disabled')
                            $('.updated').removeClass('updated')
					    }


                        if(selected_sy.length > 0 ){
                            if(selected_sy[0].ended == 1){
                                $('.grade_option').attr('disabled','disabled')
                                $('#save_button_1').attr('disabled','disabled')
                                $('.updated').removeClass('updated')
                            }
                        }
					}
			    })
               
            })
    



            function plot_setup(levelid) {
                $('#data').empty();
                var option = '<option value=""></option>'
                $.each(all_rating,function(a,b){
                    option += '<option value="'+b.id+'">'+b.value+'</option>'
                })
                $.each(observedvalues,function(a,b){
                    $('#data').append('<tr><td>'+b.description+'</td><td class="q1"><select id="'+b.id+'" class="form-control form-control-sm grade_option" quarter="1">'+option+'</select></td><td class="q2"><select id="'+b.id+'" class="form-control form-control-sm grade_option" quarter="2">'+option+'</select></td><td class="q3"><select id="'+b.id+'" class="form-control form-control-sm grade_option" quarter="3">'+option+'</select></td><td class="q4"><select id="'+b.id+'" class="form-control form-control-sm grade_option" quarter="4">'+option+'</select></td></tr>')
                })
                $('.grade_option').attr('disabled','disabled')
                display_students()
                if(levelid == 14 || levelid == 15){
                    setup_shs()
                }
            }



    })
</script>
    

@endsection