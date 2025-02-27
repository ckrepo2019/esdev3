
@extends('teacher.layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<style>
    .shadow {
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
        border: 0 !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        margin-top: -9px;
    }
   
</style>

<section class="content pt-0">
      <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="row">
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
                                    TEACHER’S COMMENTS/REMARKS
                                </h3>
                                <div class="card-tools">
                                <button class="btn btn-primary btn-sm save_button_1"><i class="fas fa-save"></i> Save</button>
                                    <button class="btn btn-sm btn-primary" id="view_pdf">View PDF</button>
                                </div>
                            </div>
                            <div class="card-body">
                                    <div class="row ">
                                        <div class="col-md-12">
                                                <table class="table table-striped table-sm table-bordered table-head-fixed nowrap display p-0" width="100%">
                                                    <tbody id="data_3">

                                                    </tbody>
                                                </table>
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
                                  Kinder Age
                              </h3>
                              <div class="card-tools">
                                <button class="btn btn-primary btn-sm save_button_1"><i class="fas fa-save"></i> Save</button>
                                  <button class="btn btn-sm btn-primary" id="view_pdf">View PDF</button>
                              </div>
                            </div>
                            <div class="card-body">
                                  <div class="row ">
                                        <div class="col-md-12">
                                              <table class="table table-striped table-sm table-bordered table-head-fixed nowrap display p-0" width="100%">
                                                    <thead>
                                                          <tr>
                                                                <th width="84%" class="align-middle"></th>
                                                                <th width="8%" class="text-center">Year</th>
                                                                <th width="8%" class="text-center">Month</th>
                                                          </tr>
                                                    </thead>
                                                    <tbody id="data_2">

                                                    </tbody>
                                              </table>
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
                                    Kinder Grading
                                </h3>
                                <div class="card-tools">
                                    <button class="btn btn-primary btn-sm save_button_1"><i class="fas fa-save"></i> Save</button>
                                    <button class="btn btn-sm btn-primary" id="view_pdf">View PDF</button>
                                </div>
                              </div>
                              <div class="card-body">
                                    <div class="row ">
                                          <div class="col-md-12">
                                                <table class="table table-striped table-sm table-bordered table-head-fixed nowrap display p-0" width="100%">
                                                      <thead>
                                                            <tr>
                                                                  <th width="68%" class="align-middle"></th>
                                                                  <th width="8%" class="text-center">Q1</th>
                                                                  <th width="8%" class="text-center">Q2</th>
                                                                  <th width="8%" class="text-center">Q3</th>
                                                                  <th width="8%" class="text-center">Q4</th>
                                                            </tr>
                                                      </thead>
                                                      <tbody id="data">

                                                      </tbody>
                                                </table>
                                          </div>
                                    </div>
                              </div>
                              <div class="card-footer border-0">
                                    <button class="btn btn-primary btn-sm save_button_1"><i class="fas fa-save"></i> Save</button>
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
        get_preschool_setup()
        var all_setup = []

        function get_preschool_setup(){
            $.ajax({
                type:'GET',
                url: '/grade/preschool/setup/remarks/list',
                data:{
                    syid:3
                },
                success:function(data) {
                        plot_setup(data)
                }
            })
        }

        function plot_setup(data) {
            all_setup = data
            $('#data_3').empty()
            for(var x = 1; x <= 4 ; x++){
                $('#data_3').append('<tr><td>Quarter '+x+'</td></tr><tr><td><textarea id="'+data[0].id+'" class="form-control form-control-sm grade_option" quarter="'+x+'"></textarea></td></tr>')
            }
        }
    })
</script>

<script>
    $(document).ready(function(){
        get_preschool_setup()
        var all_setup = []

        function get_preschool_setup(){
            $.ajax({
                type:'GET',
                url: '/grade/preschool/setup/age/list',
                data:{
                    syid:3
                },
                success:function(data) {
                        plot_setup(data)
                }
            })
        }

        function plot_setup(data) {
            all_setup = data
            $('#data_2').empty()
            $.each(data,function(a,b){
                    var padding = ""
                    var header = ""
                    var button = ""

                    padding = (b.group.length*2)+"rem;"
                    $('#data_2').append('<tr class="'+header+' "><td class="align-middle" style="padding-left:'+padding+'">'+b.description+'</td><i class="fas fa-edit text-primary"></i></a></td><td class="align-middle text-center"><input type="text" id="'+b.id+'" class="form-control form-control-sm grade_option age" quarter="1" ></td><td class="text-center align-middle"><input type="text" id="'+b.id+'" class="form-control form-control-sm grade_option age" quarter="2" ></td></tr>')
                   

                    $('.grade_option').attr('disabled','disabled')
                    $('.save_button_1').attr('disabled','disabled')

            })
        }
    })
</script>

<script>
    $(document).ready(function(){

        $('.select2').select2()

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
            })

            get_preschool_setup()
            var all_setup = []

            function get_preschool_setup(){
                $.ajax({
					type:'GET',
					url: '/grade/preschool/setup/list',
                              data:{
                                    syid:3
                              },
					success:function(data) {
                            plot_setup(data)
					}
			    })
            }

   
            $(document).on('change','.grade_option',function(){
                
                $(this).addClass('updated')
            })

            $(document).on('click','#view_pdf',function(){
                window.open('/grade/preschool/pdf?studid='+$('#input_student').val()+'&syid='+3, '_blank');
            })

            $(document).on('click','.save_button_1',function(){

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
                    var value = $(this).prop('checked') == true ? 1 : 0 ;
                    var value = $(this).val();
                    var gsdid = $(this).attr('id')
                    var quarter = $(this).attr('quarter')
                    var temp_updated = $(this)
                    $.ajax({
                        type:'GET',
                        url: '/grade/preschool/savegrades',
                        data:{
                            syid:3,
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
            get_sections()
            function get_sections(){
                $.ajax({
					type:'GET',
					url: '/grade/preschool/sections',
                    data:{
                        syid:3
                    },
					success:function(data) {
                        all_sections = data
                        $("#input_section").select2({
                                data: all_sections,
                                allowClear: true,
                                placeholder: "Select Section",
                        })
					}
			    })
            }

           

            $(document).on('change','#input_section',function(){

                if($(this).val() == ""){
                    var temp_students = []
                    $('.grade_option').val("").change()
                    $('.grade_option').attr('disabled','disabled')
                    $('.save_button_1').attr('disabled','disabled')
                }else{
                    var temp_id = $(this).val()
                    var temp_students = all_sections.filter(x=>x.id == temp_id)[0].students
                }
                $("#input_student").empty();
                $("#input_student").append('<option value="" selected="selected">Select Student</option>')
                $("#input_student").select2({
                        data: temp_students,
                        allowClear: true,
                        placeholder: "Select Student",
                })
            })

            $(document).on('change','#input_student',function(){
              

                $('.grade_option').val("").change()
                if($(this).val() == ""){
                    $('.grade_option').attr('disabled','disabled')
                    $('.save_button_1').attr('disabled','disabled')
                    return false
                }else{
                  
                    $('.save_button_1').removeAttr('disabled')
                }

                $.ajax({
					type:'GET',
					url: '/grade/preschool/getgrades',
                    data:{
                        syid:3,
                        'studid':$('#input_student').val()
                    },
					success:function(data) {
                       
                        $.each(data,function(a,b){
                            $('.grade_option[quarter="1"][id="'+b.gsdid+'"]').val(b.q1evaltext).change()
                            $('.grade_option[quarter="2"][id="'+b.gsdid+'"]').val(b.q2evaltext).change()
                            $('.grade_option[quarter="3"][id="'+b.gsdid+'"]').val(b.q3evaltext).change()
                            $('.grade_option[quarter="4"][id="'+b.gsdid+'"]').val(b.q4evaltext).change()
                        })
                        $('.updated').removeClass('updated')
					}
			    })

                $.ajax({
                    type:'GET',
                    url:'/principal/ps/gradestatus/list',
                    data:{
                        'sectionid':$('#input_section').val(),
                        'syid':3,
                        'studid':$('#input_student').val()
                    },
                    success:function(data) {
                        if(data.length > 0){
                            if(data[0].q1status == null){
                                $('.grade_option[quarter="1"]').removeAttr('disabled')
                            }else{
                                $('.grade_option[quarter="1"]').attr('disabled','disabled')
                            }
                            if(data[0].q2status == null){
                                $('.grade_option[quarter="2"]').removeAttr('disabled')
                            }else{
                                $('.grade_option[quarter="2"]').attr('disabled','disabled')
                            }
                            if(data[0].q3status == null){
                                $('.grade_option[quarter="3"]').removeAttr('disabled')
                            }else{
                                $('.grade_option[quarter="3"]').attr('disabled','disabled')
                            }
                            if(data[0].q4status == null){
                                $('.grade_option[quarter="4"]').removeAttr('disabled')
                            }else{
                                $('.grade_option[quarter="4"]').attr('disabled','disabled')
                            }

                            $('.age').removeAttr('disabled')
                            
                        }
                    },
                })

               
               
            })

            function plot_setup(data) {
                all_setup = data
                $('#data').empty()
                $.each(data,function(a,b){
                        var padding = ""
                        var header = ""
                        var button = ""

                        var option = '<option value=""></option>'+
                                    '<option value="A">A</option>'+
                                    '<option value="B">B</option>'+
                                    '<option value="C">C</option>'+
                                    '<option value="D">D</option>'

                        if(b.value == 0){
                            header = 'font-weight-bold'
                            if(b.sort.length > 1){
                                    padding = (b.group.length*2)+"rem;"
                            }
                            $('#data').append('<tr class="'+header+' "><td class="align-middle" style="padding-left:'+padding+'">'+b.description+'</td><i class="fas fa-edit text-primary"></i></a></td><td class="align-middle text-center"></td><td class="text-center"></td><td class="text-center"></td><td class="text-center"></td></tr><')
                        }else{
                            padding = (b.group.length*2)+"rem;"
                            $('#data').append('<tr class="'+header+' "><td class="align-middle" style="padding-left:'+padding+'">'+b.description+'</td><i class="fas fa-edit text-primary"></i></a></td><td class="align-middle text-center"><select id="'+b.id+'" class="form-control form-control-sm grade_option" quarter="1">'+option+'</select></td><td class="text-center align-middle"><select id="'+b.id+'" class="form-control form-control-sm grade_option" quarter="2">'+option+'</select></td><td class="text-center align-middle"><select id="'+b.id+'" class="form-control form-control-sm grade_option" quarter="3">'+option+'</select></td><td class="text-center align-middle"><select id="'+b.id+'" class="form-control form-control-sm grade_option" quarter="4">'+option+'</select></td></tr><')
                        }

                        $('.grade_option').attr('disabled','disabled')
                        $('.save_button_1').attr('disabled','disabled')

                })
            }




                 



    })
</script>
    

@endsection