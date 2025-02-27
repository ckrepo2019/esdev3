
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
    /* input:checked {
        height: calc(1rem + 1px);
    } */
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
                                    Age / Evaluation Date
                                </h3>
                                <div class="card-tools">
                                    <button class="btn btn-primary btn-sm save_button_1" ><i class="fas fa-save"></i> Save</button>
                                    <button class="btn btn-sm btn-primary" id="view_pdf">View PDF</button>
                                </div>
                            </div>
                            <div class="card-body">
                                  <div class="row ">
                                        <div class="col-md-12">
                                              <table class="table table-striped table-sm table-bordered table-head-fixed nowrap display p-0" width="100%">
                                                    <thead>
                                                          <tr>
                                                                <th width="76%" class="align-middle"></th>
                                                                <th width="8%" class="text-center">1</th>
                                                                <th width="8%" class="text-center">2</th>
                                                                <th width="8%" class="text-center">3</th>
                                                          </tr>
                                                    </thead>
                                                    <tbody id="data_4">
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
                                        Christian Living Education
                                </h3>
                                <div class="card-tools">
                                    <button class="btn btn-primary btn-sm save_button_1" ><i class="fas fa-save"></i> Save</button>
                                    <button class="btn btn-sm btn-primary" id="view_pdf">View PDF</button>
                                </div>
                            </div>
                            <div class="card-body">
                                  <div class="row ">
                                        <div class="col-md-12">
                                              <table class="table table-striped table-sm table-bordered table-head-fixed nowrap display p-0" width="100%">
                                                    <thead>
                                                          <tr>
                                                                <th width="76%" class="align-middle"></th>
                                                                <th width="8%" class="text-center">1</th>
                                                                <th width="8%" class="text-center">2</th>
                                                                <th width="8%" class="text-center">3</th>
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
                                Summary
                            </h3>
                            <div class="card-tools">
                                <button class="btn btn-primary btn-sm save_button_1" ><i class="fas fa-save"></i> Save</button>
                                <button class="btn btn-sm btn-primary" id="view_pdf">View PDF</button>
                            </div>
                        </div>
                        <div class="card-body">
                              <div class="row ">
                                    <div class="col-md-12">
                                          <table class="table table-striped table-sm table-bordered table-head-fixed nowrap display p-0" width="100%">
                                                <thead>
                                                        <tr>
                                                                <th width="52%" class="align-middle"></th>
                                                                <th class="text-center" colspan="2">1</th>
                                                                <th class="text-center" colspan="2">2</th>
                                                                <th class="text-center" colspan="2">3</th>
                                                        </tr>
                                                      <tr>
                                                            <th class="align-middle">Domain</th>
                                                            <th width="8%" class="text-center">Raw</th>
                                                            <th width="8%" class="text-center">Scaled</th>
                                                            <th width="8%" class="text-center">Raw</th>
                                                            <th width="8%" class="text-center">Scaled</th>
                                                            <th width="8%" class="text-center">Raw</th>
                                                            <th width="8%" class="text-center">Scaled</th>
                                                      </tr>
                                                </thead>
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
                                    Pre-kinder Grading
                                </h3>
                                <div class="card-tools">
                                    <button class="btn btn-primary btn-sm save_button_1" ><i class="fas fa-save"></i> Save</button>
                                    <button class="btn btn-sm btn-primary" id="view_pdf">View PDF</button>
                                </div>
                              </div>
                              <div class="card-body">
                                    <div class="row ">
                                          <div class="col-md-12">
                                                <table class="table table-striped table-sm table-bordered table-head-fixed nowrap display p-0" width="100%">
                                                      <thead>
                                                            <tr>
                                                                  <th width="76%" class="align-middle"></th>
                                                                  <th width="8%" class="text-center">1</th>
                                                                  <th width="8%" class="text-center">2</th>
                                                                  <th width="8%" class="text-center">3</th>
                                                            </tr>
                                                      </thead>
                                                      <tbody id="data">

                                                      </tbody>
                                                </table>
                                          </div>
                                    </div>
                              </div>
                              <div class="card-footer border-0">
                                    <button class="btn btn-primary btn-sm save_button_1" ><i class="fas fa-save"></i> Save</button>
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
        get_preschool_summary_setup()
        function get_preschool_summary_setup(){
            $.ajax({
                type:'GET',
                url: '/grade/prekinder/ageevaldate/setup/list',
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
            $('#data_4').empty()
            $.each(data,function(a,b){
                var header = ""
                var button = ""
                var group = b.group
                type = 'type="text"'
                if(b.group == 'B'){
                    type = 'type="date"'
                }
               
                $('#data_4').append('<tr class="'+header+' "><td class="align-middle" >'+b.description+'</td><td class="align-middle text-center"><input '+type+' id="'+b.id+'" class="form-control form-control-sm grade_option" quarter="1" ></td><td class="text-center align-middle"><input '+type+' id="'+b.id+'" class="form-control form-control-sm grade_option" quarter="2" ></td><td class="text-center align-middle"><input '+type+' id="'+b.id+'" class="form-control form-control-sm grade_option" quarter="3" ></td></tr><')
               
            })
        }

    })
</script>

<script>
    $(document).ready(function(){
        get_preschool_summary_setup()
        function get_preschool_summary_setup(){
            $.ajax({
                type:'GET',
                url: '/grade/prekinder/summary/setup/list',
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
            $.each(data,function(a,b){
                var header = ""
                var button = ""
                var group = b.group
              

                $('#data_3').append('<tr class="'+header+' "><td class="align-middle" >'+b.description+'</td><td class="total_grade text-center align-middle" data-group="'+group+'" data-quarter="1"></td><td><input type="text" id="'+b.id+'" class="form-control form-control-sm grade_option" quarter="1" ></td><td class="total_grade text-center align-middle" data-group="'+group+'" data-quarter="2"></td><td><input type="text" id="'+b.id+'" class="form-control form-control-sm grade_option" quarter="2" ></td><td class="total_grade text-center align-middle" data-group="'+group+'" data-quarter="3"></td><td><input type="text" id="'+b.id+'" class="form-control form-control-sm grade_option" quarter="3" ></td></tr><')
            })
        }

    })
</script>

<script>
    $(document).ready(function(){
        get_prekinder_cl_setup()

        function get_prekinder_cl_setup(){
            $.ajax({
                type:'GET',
                url: '/grade/prekinder/cl/setup/list',
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
                var header = ""
                var button = ""
                var option = '<option value=""></option>'+
                            '<option value="AO">AO</option>'+
                            '<option value="SO">SO</option>'+
                            '<option value="RO">RO</option>'

                $('#data_2').append('<tr class="'+header+' "><td class="align-middle" >'+b.description+'</td><i class="fas fa-edit text-primary"></i></a></td><td class="align-middle text-center"><select type="select" id="'+b.id+'" class="form-control form-control-sm grade_option" quarter="1">'+option+'</select></td><td class="text-center align-middle"><select type="select" id="'+b.id+'" class="form-control form-control-sm grade_option" quarter="2">'+option+'</select></td><td class="text-center align-middle"><select type="select" id="'+b.id+'" class="form-control form-control-sm grade_option" quarter="3">'+option+'</select></td></tr><')
            })

            $('select[type="select"]').attr('disabled','disabled')
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

            get_prekinder_setup()
            var all_setup = []

            function get_prekinder_setup(){
                $.ajax({
					type:'GET',
					url: '/grade/prekinder/setup/list',
                              data:{
                                    syid:3
                              },
					success:function(data) {
                                    plot_setup(data)
					}
			    })
            }

   
            $(document).on('change','.grade_option',function(){

                $('.total_grade').each(function(){
                    var temp_group = $(this).attr('data-group')
                    var quarter = $(this).attr('data-quarter')
                    var count = 0;
                    $('.grade_option[quarter="'+quarter+'"][data-group="'+temp_group+'"]').each(function(){
                        if($(this).prop('checked')){
                            count += 1
                        }
                    })

                    if(count != 0 ){
                        $(this).text(count)
                    }else{
                        $(this).text("")
                    }
                })

                $(this).addClass('updated')
            })

            $(document).on('click','.save_button_1',function(){
                var count = 0;
                var updated_length = $('.updated').length

                if(updated_length == 0){
                    Toast.fire({
                        type: 'info',
                        title: 'No changes made'
                    })
                    return false
                }

                $('.updated').each(function(){

                    if($(this).is('select')){
                        var value = $(this).val();
                    }
                    else if($(this).is('input[type="text"]') || $(this).is('input[type="date"]')){
                        var value = $(this).val();
                    }
                    else{
                        var value = $(this).prop('checked') == true ? 1 : 0 ;
                    }
                    
                    var gsdid = $(this).attr('id')
                    var quarter = $(this).attr('quarter')
                    var temp_updated = $(this)



                    $.ajax({
                        type:'GET',
                        url: '/grade/prekinder/savegrades',
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
                                    Toast.fire({
                                            type: 'success',
                                            title: 'Updated Successfully'
                                    })
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
            })

            var all_sections = []
            get_sections()
            function get_sections(){
                $.ajax({
					type:'GET',
					url: '/grade/prekinder/sections',
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
                $('.total_grade').text("")
                if($(this).val() == ""){
                    var temp_students = []
                    $('.grade_option[type="checkbox"]').prop('checked',false)
                    $('.grade_option[type="select"]').val("").change()
                    $('.grade_option[type="text"]').val("")

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
                $('.total_grade').text("")
                $('.grade_option[type="checkbox"]').prop('checked',false)
                $('.grade_option[type="select"]').val("").change()
                $('.grade_option[type="text"]').val("")

                if($(this).val() == ""){
                    $('.grade_option').attr('disabled','disabled')
                    $('.save_button_1').attr('disabled','disabled')
                    return false
                }else{
                    //$('.grade_option').removeAttr('disabled')
                    $('.save_button_1').removeAttr('disabled')
                }

                $.ajax({
					type:'GET',
					url: '/grade/prekinder/getgrades',
                    data:{
                        syid:3,
                        'studid':$('#input_student').val()
                    },
					success:function(data) {

                        $.each(data,function(a,b){

                            if(b.description == 'Prekinder CL'){
                                $('.grade_option[quarter="1"][id="'+b.gsdid+'"]').val(b.q1evaltext).change()
                                $('.grade_option[quarter="2"][id="'+b.gsdid+'"]').val(b.q2evaltext).change()
                                $('.grade_option[quarter="3"][id="'+b.gsdid+'"]').val(b.q3evaltext).change()
                            }
                            else if(b.description == 'Prekinder Summary' || b.description == 'Perkinder Age/Date'){
                                $('.grade_option[quarter="1"][id="'+b.gsdid+'"]').val(b.q1evaltext)
                                $('.grade_option[quarter="2"][id="'+b.gsdid+'"]').val(b.q2evaltext)
                                $('.grade_option[quarter="3"][id="'+b.gsdid+'"]').val(b.q3evaltext)
                                
                            }
                            else{
                                if(b.q1evaltext == 1){
                                    $('.grade_option[quarter="1"][id="'+b.gsdid+'"]').prop('checked','checked')
                                }
                                if(b.q2evaltext == 1){
                                    $('.grade_option[quarter="2"][id="'+b.gsdid+'"]').prop('checked','checked')
                                }
                                if(b.q3evaltext == 1){
                                    $('.grade_option[quarter="3"][id="'+b.gsdid+'"]').prop('checked','checked')
                                }
                                if(b.q4evaltext == 1){
                                    $('.grade_option[quarter="4"][id="'+b.gsdid+'"]').prop('checked','checked')
                                }
                            }


                        })
                       
                        $('.updated').removeClass('updated')

                        $('.total_grade').each(function(){
                            var temp_group = $(this).attr('data-group')
                            var quarter = $(this).attr('data-quarter')
                            var count = 0;
                            $('.grade_option[quarter="'+quarter+'"][data-group="'+temp_group+'"]').each(function(){
                                if($(this).prop('checked')){
                                    count += 1
                                }
                            })
                            if(count != 0 ){
                                $(this).text(count)
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
                        
					}
			    })
               
            })


            $(document).on('click','#view_pdf',function(){
                window.open('/grade/prekinder/pdf?studid='+$('#input_student').val()+'&syid='+3, '_blank');
            })

                function plot_setup(data) {
                        all_setup = data
                        $('#data').empty()
                        var next_group = false;
                        var group = ""

                        $.each(data,function(a,b){

                              var padding = ""
                              var header = ""
                              var button = ""
                              var option = ""

                              if(b.value == 0){
                                if(next_group){
                                    $('#data').append('<tr><td class="align-middle text-right pr-4"><b>TOTAL</b></td><i class="fas fa-edit text-primary"></i></a></td><td class="align-middle text-center total_grade" data-group="'+group+'" data-quarter="1"></td><td class="text-center total_grade" data-group="'+group+'" data-quarter="2"></td><td class="text-center total_grade" data-group="'+group+'" data-quarter="3"></td><tr>')
                                    
                                }
                              }

                              if(b.value == 0){

                                    next_group = true

                                    header = 'font-weight-bold'
                                    if(b.sort.length > 1){
                                          padding = (b.group.length*2)+"rem;"
                                    }
                                    $('#data').append('<tr class="'+header+' "><td class="align-middle" style="padding-left:'+padding+'">'+b.description+'</td><i class="fas fa-edit text-primary"></i></a></td><td class="align-middle text-center"></td><td class="text-center"></td><td class="text-center"></td></tr><')
                              }else{
                                    group = b.group
                                    padding = (b.group.length*2)+"rem;"
                                    $('#data').append('<tr class="'+header+' "><td class="align-middle" style="padding-left:'+padding+'">'+b.description+'</td><i class="fas fa-edit text-primary"></i></a></td><td class="align-middle text-center"><input type="checkbox" id="'+b.id+'" class="form-control form-control-sm grade_option" quarter="1" data-group="'+group+'"></td><td class="text-center aling-middle"><input type="checkbox" id="'+b.id+'" class="form-control form-control-sm grade_option" quarter="2" data-group="'+group+'"></td><td class="text-center align-middle"><input type="checkbox" id="'+b.id+'" class="form-control form-control-sm grade_option" quarter="3" data-group="'+group+'"></td></tr><')
                              }

                              $('.grade_option').attr('disabled','disabled')
                              $('.save_button_1').attr('disabled','disabled')

                        })

                        $('#data').append('<tr><td class="align-middle text-right pr-4"><b>TOTAL</b></td><i class="fas fa-edit text-primary"></i></a></td><td class="align-middle text-center total_grade" data-group="'+group+'" data-quarter="1"></td><td class="text-center total_grade" data-group="'+group+'" data-quarter="2"></td><td class="text-center total_grade" data-group="'+group+'" data-quarter="3"></td><tr>')

                        

                  }

                  


    })
</script>
    

@endsection