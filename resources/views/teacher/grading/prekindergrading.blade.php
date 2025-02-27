
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
    .grade_option_checkbox {
        height: calc(1rem + 1px);
    }
</style>

@php
     $sy = DB::table('sy')
                  ->orderBy('sydesc','desc')
                  ->select(
                        'id',
                        'sydesc',
                        'sydesc as text',
                        'isactive',
                        'ended'
                  )
                  ->get(); 
@endphp

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Pre-school Grading</h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item active">Pre-school Grading</li>
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
                                    <label for="">School Year</label>
                                    <select class="form-control select2" id="filter_sy">
                                        <option value="" selected="selected">Select School Year</option>
                                        @foreach ($sy as $item)
                                                @if($item->isactive == 1)
                                                    <option value="{{$item->id}}" selected="selected">{{$item->sydesc}}</option>
                                                @else
                                                    <option value="{{$item->id}}">{{$item->sydesc}}</option>
                                                @endif
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
                            </div>
                            <div class="row mt-3" id="setup_holder">
                                         
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

<script>
          
</script>


<script>
    $(document).ready(function(){
       
        $(document).on('click','.view_pdf',function(){
            
            var levelid = all_sections.filter(x=>x.id == $('#input_section').val())[0].levelid
            var syid = $('#filter_sy').val()

            window.open('/grade/preschool/pdf?studid='+$('#input_student').val()+'&syid='+syid+'&levelid='+levelid, '_blank');
        })

        function get_setup(syid,levelid){
            $.ajax({
                    type:'GET',
                    url: '/preschool/gradingsystem/list',
                    data:{
                            syid:syid,
                            levelid:levelid
                    },
                    success:function(data) {
                        $('#setup_holder').empty()
                        $.each(data,function(a,b){

                            var type = ''
                            var columns = ''
                            var col_display = ''

                            if(b.type == 1){
                                type = '( 3 Term/Quarter Checklist )'
                                columns = 3
                            }else if(b.type == 2){
                                type = '( 4 Term/Quarter Checklist )'
                                columns = 4
                            }else if(b.type == 3){
                                type = '( 3 Term/Quarter Rating )'
                                columns = 3
                            }else if(b.type == 4){
                                type = '( 4 Term/Quarter Rating )'
                                columns = 4
                            }else if(b.type == 5){
                                columns = 2
                            }

                            var colwidth = 35 / columns

                            for(var x = 0; x < columns; x++){
                                if(x == (columns-1)){
                                    if(b.type == 5){
                                        col_display += '<th width="40%" class="text-center">'+'  <button class="btn btn-sm btn-primary view_pdf mr-2" disabled="disabled">View PDF</button><button class="btn btn-primary btn-sm save_button_1" disabled="disabled" style="font-size.6rem !important"><i class="fas fa-save"></i> Save</button></th>'
                                    }else{
                                        col_display += '<th width="'+colwidth+'%" class="text-center">'+'  <button class="btn btn-primary btn-sm save_button_1" disabled="disabled" style="font-size.6rem !important"><i class="fas fa-save"></i> Save</button></th>'
                                    }
                                }else if(x == (columns-2)){
                                    if(b.type == 5){
                                        col_display += '<th width="10%" class="text-center">Default</button></th>'
                                    }else{
                                        col_display += '<th width="'+colwidth+'%" class="text-center">'+'  <button class="btn btn-sm btn-primary view_pdf" disabled="disabled">View PDF</button></th>'
                                    }
                                   
                                }else{
                                    col_display += '<th width="'+colwidth+'%" class="text-center"></th>'
                                }
                                
                            }


                            var height = '600px'

                            if(b.type == 5){
                                height = '300px'
                                width = '50%'
                            }else{
                                width = '65%'
                            }



                            var text = `<div class="col-md-12  table-responsive mb-4" data-id="`+b.id+`"  style="height: `+height+`;">
                                            <table class="table table-striped table-sm table-bordered table-head-fixed nowrap display p-0" width="100%">
                                                    <thead>
                                                        <tr>
                                                                <th width="`+width+`">`+b.description+` `+type +`</th> `+col_display+`
                                                        </tr>
                                                    </thead>
                                                    <tbody id="data" data-id="`+b.id+`">

                                                    </tbody>
                                            </table>
                                        </div>`

                            if(b.type != 7){
                                $('#setup_holder').append(text)
                            }
                            
                            
                            get_setup_detail(syid,levelid,b.id,b.type,columns,b)
                        })

                    }
            })
        }

        function get_setup_detail(syid,levelid,headerid,type,columns,headerinfo){
            $.ajax({
                type:'GET',
                url: '/grade/preschool/setup/list',
                data:{
                    syid:syid,
                    levelid:levelid,
                    dataheaderid:headerid
                },
                success:function(data) {
                    plot_setup(data,headerid,type,columns,headerinfo)
                }
            })
        }

        function plot_setup(data,headerid,type,columns,headerinfo) {
            all_setup = data
            $('#data[data-id="'+headerid+'"]').empty()
            var rating = data[0].ratingvalue
            var option ='<option value=""></option>'

            $.each(rating,function(a,b){
                option += '<option value="'+b.value+'">'+b.description+'</option>'
            })

            if(headerinfo.type == 5){
                if(data[0].detail.length <= 10){
                    $('.table-responsive[data-id="'+headerid+'"]').removeAttr('style')
                    $('.table-responsive[data-id="'+headerid+'"]').removeClass('table-responsive')
                }
            }
            


            $.each(data[0].detail,function(a,b){
                    var padding = ""
                    var header = ""
                    var button = ""
                    if(b.value == 0){
                        header = 'font-weight-bold'
                        button = ''

                        if(b.sort.length > 1){
                                padding = (b.group.length*2)+"rem;"
                        }
                        
                    }else{
                        padding = ((b.group.length+1)*2)+"rem;"
                    }
                    
                    var col_display = ''
                    var group = b.group;
                    for(var x = 0; x < columns; x++){
                        if(type == 1 || type == 2){
                            if(b.value == 0){
                                col_display += '<th class="text-center">Q'+(x+1)+'</th>'
                            }else{
                                if(b.description == 'TOTAL'){
                                    col_display += '<th class="align-middle total_grade text-center" data-group="'+group+'" data-quarter="'+(x+1)+'"></th>'
                                }else if (b.description == 'SCALED SCORE'){
                                    col_display += '<th class="align-middle"><input type="text" id="'+b.id+'" class="form-control form-control-sm grade_option" quarter="'+(x+1)+'" data-group="'+group+'"></td>'
                                }else if (b.description == 'SUM OF STANDARD SCORE'){
                                    col_display += '<th class="align-middle text-center sum_of_standard_score" data-quarter="'+(x+1)+'"></th>'
                                }else if (b.description == 'SUM OF SCALED SCORE'){
                                    col_display += '<th class="align-middle text-center sum_of_scaled_score" data-quarter="'+(x+1)+'"></th>'
                                }else{
                                    col_display += '<th class="align-middle"><input type="checkbox" id="'+b.id+'" class="form-control form-control-sm grade_option grade_option_checkbox" quarter="'+(x+1)+'" data-group="'+group+'"></th>'
                                }
                            }
                        }else{
                            col_display += '<th><select id="'+b.id+'" class="form-control form-control-sm grade_option" quarter="'+(x+1)+'">'+option+'</select></th>'
                        }
                     
                    }

                   if(type == 5){
                        col_display = ''
                        var readonly = 'readonly="readonly"'
                        if(b.withdefault == 1){
                            col_display += '<td class="align-middle text-center"><input checked="checked" type="checkbox" id="'+b.id+'" class="form-control form-control-sm grade_option grade_option_checkbox" quarter="1" ></td>'
                           
                        }else{
                            col_display += '<td></td>'
                            readonly = ''
                        }

                        col_display += '<td class="align-middle"><input '+readonly+' type="text" id="'+b.id+'" class="form-control form-control-sm grade_option" quarter="1" data-group="'+group+'"></td>'
                     
                   }

                    $('#data[data-id="'+headerid+'"]').append('<tr class="'+header+' "><td class="align-middle" style="padding-left:'+padding+'">'+b.description+'</td>'+col_display+'</tr>')
            })

            $('.grade_option').attr('disabled','disabled')
            $('input[type="checkbox"]').attr('disabled','disabled')
        }

        $('.select2').select2()

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
        })

        var all_sections = []

        get_sections()

        function get_sections(){
            $.ajax({
                type:'GET',
                url: '/grade/preschool/sections',
                data:{
                    syid:$('#filter_sy').val()
                },
                success:function(data) {
                    all_sections = data
                    $("#input_section").empty();
                    $("#input_section").append('<option value="">Select Section</option>')
                    $("#input_section").select2({
                            data: all_sections,
                            allowClear: true,
                            placeholder: "Select Section",
                    })

                    $("#input_student").empty();
                    $("#input_student").append('<option value="" >Select Student</option>')
                    $("#input_student").select2({
                            data: [],
                            allowClear: true,
                            placeholder: "Select Student",
                    })
                    
                }
            })
        }

        $(document).on('change','#filter_sy',function(){
            $('#setup_holder').empty()
            get_sections()
        })

        
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


        $(document).on('change','#input_section',function(){
            $('.total_grade').text("")
            $('#setup_holder').empty()

            $('.view_pdf').attr('disabled','disabled')
            $('.save_button_1').attr('disabled','disabled')

            if($(this).val() == ""){
                var temp_students = []
                $('.grade_option[type="checkbox"]').prop('checked',false)
                $('.grade_option[type="select"]').val("").change()
                $('.grade_option[type="text"]').val("")

                $('.grade_option').attr('disabled','disabled')
                $('.save_button_1').attr('disabled','disabled')
            }else{
                var temp_id = $(this).val()
                var sectiondetail = all_sections.filter(x=>x.id == temp_id)
                var students = sectiondetail[0].students
            }

            

            $("#input_student").empty();
            $("#input_student").append('<option value="" >Select Student</option>')
            $("#input_student").select2({
                    data: students,
                    allowClear: true,
                    placeholder: "Select Student",
            })


            get_setup($('#filter_sy').val(),sectiondetail[0].levelid)
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
            save_grades()
        })

        function save_grades(){

            var updated_length = $('.updated').length

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
                    url: '/grade/preschool/savegrades',
                    data:{
                        syid:$('#filter_sy').val(),
                        'gsdid':gsdid,
                        'value':value,
                        'quarter':quarter,
                        'studid':$('#input_student').val()
                    },
                    success:function(data) {
                        if(data[0].status == 1){
                            temp_updated.removeClass('updated')
                        
                            if($('.updated').length == 0){
                                Toast.fire({
                                        type: 'success',
                                        title: 'Updated Successfully'
                                })
                                return false
                            }
                            save_grades()
                        }else{
                            Toast.fire({
                                    type: 'error',
                                    title: 'Something went wrong'
                            })
                            return false
                        }
                    
                    },
                    error:function(){
                        Toast.fire({
                            type: 'error',
                            title: 'Something went wrong.'
                        })
                    }
                })

                return false

            })
        }

        $(document).on('change','#input_student',function(){
            $('.total_grade').text("")
            $('.grade_option[type="checkbox"]').prop('checked',false)
            $('.grade_option[type="select"]').val("").change()
            $('.grade_option[type="text"]').val("")

            if($(this).val() == "" || $(this).val() == null){
                $('.grade_option').attr('disabled','disabled')
                $('.save_button_1').attr('disabled','disabled')
            }else{
                $('.grade_option').removeAttr('disabled')
                $('.save_button_1').removeAttr('disabled')
                $('.view_pdf').removeAttr('disabled')
            }

            var levelid = all_sections.filter(x=>x.id == $('#input_section').val())[0].levelid

            $.ajax({
                type:'GET',
                url: '/grade/preschool/getgrades',
                data:{
                    syid:$('#filter_sy').val(),
                    'studid':$('#input_student').val(),
                    levelid:levelid
                },
                success:function(data) {

                    $.each(data,function(a,b){

                        
                        if(b.type == 1 || b.type == 2){
                            if(b.detaildesc == 'SCALED SCORE'){
                                $('.grade_option[quarter="1"][id="'+b.gsdid+'"]').val(b.q1evaltext )
                                $('.grade_option[quarter="2"][id="'+b.gsdid+'"]').val(b.q2evaltext )
                                $('.grade_option[quarter="3"][id="'+b.gsdid+'"]').val(b.q3evaltext )
                                $('.grade_option[quarter="3"][id="'+b.gsdid+'"]').val(b.q4evaltext )
                            }else {
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
                        }else if(b.type == 3 || b.type == 4){

                            $('.grade_option[quarter="1"][id="'+b.gsdid+'"]').val(b.q1evaltext).change()
                            $('.grade_option[quarter="2"][id="'+b.gsdid+'"]').val(b.q2evaltext).change()
                            $('.grade_option[quarter="3"][id="'+b.gsdid+'"]').val(b.q3evaltext).change()



                        }else if(b.type == 5){
                            $('.grade_option[quarter="1"][id="'+b.gsdid+'"]').val(b.q1evaltext)
                            $('.grade_option[quarter="2"][id="'+b.gsdid+'"]').val(b.q2evaltext)
                            $('.grade_option[quarter="3"][id="'+b.gsdid+'"]').val(b.q3evaltext)
                            $('.grade_option[quarter="3"][id="'+b.gsdid+'"]').val(b.q4evaltext)
                        }
                        // $('.grade_option[quarter="1"][id="'+b.gsdid+'"]').val(b.q1evaltext).change()
                        // $('.grade_option[quarter="2"][id="'+b.gsdid+'"]').val(b.q2evaltext).change()
                        // $('.grade_option[quarter="3"][id="'+b.gsdid+'"]').val(b.q3evaltext).change()
                        // if(b.description == 'Prekinder CL'){
                        //     $('.grade_option[quarter="1"][id="'+b.gsdid+'"]').val(b.q1evaltext).change()
                        //     $('.grade_option[quarter="2"][id="'+b.gsdid+'"]').val(b.q2evaltext).change()
                        //     $('.grade_option[quarter="3"][id="'+b.gsdid+'"]').val(b.q3evaltext).change()
                        // }
                        // else if(b.description == 'Prekinder Summary' || b.description == 'Perkinder Age/Date'){
                        //     $('.grade_option[quarter="1"][id="'+b.gsdid+'"]').val(b.q1evaltext)
                        //     $('.grade_option[quarter="2"][id="'+b.gsdid+'"]').val(b.q2evaltext)
                        //     $('.grade_option[quarter="3"][id="'+b.gsdid+'"]').val(b.q3evaltext)
                            
                        // }
                        // else{
                        //     if(b.q1evaltext == 1){
                        //         $('.grade_option[quarter="1"][id="'+b.gsdid+'"]').prop('checked','checked')
                        //     }
                        //     if(b.q2evaltext == 1){
                        //         $('.grade_option[quarter="2"][id="'+b.gsdid+'"]').prop('checked','checked')
                        //     }
                        //     if(b.q3evaltext == 1){
                        //         $('.grade_option[quarter="3"][id="'+b.gsdid+'"]').prop('checked','checked')
                        //     }
                        //     if(b.q4evaltext == 1){
                        //         $('.grade_option[quarter="4"][id="'+b.gsdid+'"]').prop('checked','checked')
                        //     }
                        // }


                    })
                    
                    // $('.updated').removeClass('updated')

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

                    // $.ajax({
                    //     type:'GET',
                    //     url:'/principal/ps/gradestatus/list',
                    //     data:{
                    //         'sectionid':$('#input_section').val(),
                    //         'syid':3,
                    //         'studid':$('#input_student').val()
                    //     },
                    //     success:function(data) {
                    //         if(data.length > 0){
                    //             if(data[0].q1status == null){
                    //                 $('.grade_option[quarter="1"]').removeAttr('disabled')
                    //             }else{
                    //                 $('.grade_option[quarter="1"]').attr('disabled','disabled')
                    //             }
                    //             if(data[0].q2status == null){
                    //                 $('.grade_option[quarter="2"]').removeAttr('disabled')
                    //             }else{
                    //                 $('.grade_option[quarter="2"]').attr('disabled','disabled')
                    //             }
                    //             if(data[0].q3status == null){
                    //                 $('.grade_option[quarter="3"]').removeAttr('disabled')
                    //             }else{
                    //                 $('.grade_option[quarter="3"]').attr('disabled','disabled')
                    //             }
                    //             if(data[0].q4status == null){
                    //                 $('.grade_option[quarter="4"]').removeAttr('disabled')
                    //             }else{
                    //                 $('.grade_option[quarter="4"]').attr('disabled','disabled')
                    //             }

                    //             $('.age').removeAttr('disabled')
                                
                    //         }
                    //     },
                    // })
                }
            })
            
        })

    })
</script>
    

@endsection