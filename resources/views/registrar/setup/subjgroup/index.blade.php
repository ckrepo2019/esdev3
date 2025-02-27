
@extends('registrar.layouts.app')
@section('content')
<!-- DataTables -->
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
    <!-- jQuery -->
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <!-- DataTables  & Plugins -->
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/jszip/jszip.min.js')}}"></script>
    <script src="{{asset('plugins/pdfmake/pdfmake.min.js')}}"></script>
    <script src="{{asset('plugins/pdfmake/vfs_fonts.js')}}"></script>
    <style>
        
        .select2-container .select2-selection--single {
            height: 40px !important;
        }
        
        #modal-adddata .modal-dialog{
            max-width: 800px
        }
        td, th {
            padding: 1px !important;
        }
    </style>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 class="m-0 text-dark">Subject Groupings</h3>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Subject Groupings</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div>
    </section>
    <div class="row">
        <div class="col-md-12 text-right">
            <button type="button" class="btn btn-sm btn-primary mb-2" id="btn-addnewgroup"><i class="fa fa-plus"></i> Add New Subject Group</button>
        </div>
    </div>
	{{-- <h3 class="m-0"><strong>Subject Groupings</strong></h3>
     <br/>
	<button type="button" class="btn btn-sm btn-default mb-2" id="btn-addnewgroup"><i class="fa fa-plus"></i> Add New Subject Group</button> --}}
     {{-- <div class="card" style="border:none !important; box-shadow: 0 4px 8px 0 rgb(0 0 0 / 20%) !important;">
         <div class="card-header">

         </div>
     </div> --}}
    <div id="div-results">
    </div>
    <div id="div-newcards">
    </div>
    
    <script>
        
        $(function () {
            $('.select2').select2()
            $('#example2').DataTable({
              "paging": false,
              "lengthChange": true,
              "searching": true,
              "ordering": false,
              "info": true,
              "autoWidth": false,
              "responsive": true,
            });
        });
        $(document).ready(function(){
            function getgroups()
            {
                
                Swal.fire({
                        title: 'Generating...',
                        allowOutsideClick: false,
                        closeOnClickOutside: false,
                        onBeforeOpen: () => {
                            Swal.showLoading()
                        }
                })
                $.ajax({
                        url: '/setup/subjgrouping',
                        type: 'GET',
                        data: {
                            action: 'getgroups'
                        },
                        success:function(data)
                        {
                            $('#div-results').empty()
                            $('#div-results').append(data)
                            $(".swal2-container").remove();
                            $('body').removeClass('swal2-shown')
                            $('body').removeClass('swal2-height-auto')
                        }
                }); 
            }
            getgroups()
            $(document).on('click','input', function(){
                $('#div-results').find('input').prop('readonly', true);
                $(this).closest('.info-box').find('input').removeAttr('readonly');
            })
            $('#btn-addnewgroup').on('click', function(){
                $('#div-newcards').prepend(
                    '<div class="info-box">'+
                        '<div class="info-box-content p-1">'+
                            '<div class="row">'+
                                '<div class="col-md-2">'+
                                    '<label>Num. Order</label>'+
                                    '<input type="text" class="form-control form-control-sm input-num" name="input-num" placeholder="I / II / III / IV / V"/>'+
                                '</div>'+
                                '<div class="col-md-5">'+
                                    '<label>Subject Group</label>'+
                                    '<input type="text" class="form-control form-control-sm input-group" name="input-group"/>'+
                                '</div>'+
                                '<div class="col-md-3">'+
                                    '<label>Units Required</label>'+
                                    '<input type="text" class="form-control form-control-sm input-units" name="input-units"/>'+
                                '</div>'+
                                '<div class="col-md-2">'+
                                    '<label>Actions</label><br/>'+
                                    '<button type="button" class="btn btn-sm btn-outline-danger btn-removecard"><i class="fa fa-times"></i></button>'+
                                    '<button type="button" class="btn btn-sm btn-outline-success btn-addgroup"><i class="fa fa-check"></i></button>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                    '</div>'
                );
            })
            $(document).on('click','.btn-removecard', function(){
                $(this).closest('.info-box').remove()
            })
            $(document).on('click','.btn-addgroup', function(){
                var thiscontainer = $(this).closest('.info-box');
                var validation = 0;
                var num   = $(this).closest('.info-box').find('.input-num').val();
                var group = $(this).closest('.info-box').find('.input-group').val();
                var units = $(this).closest('.info-box').find('.input-units').val();
                if($(this).closest('.info-box').find('.input-num').val().replace(/^\s+|\s+$/g, "").length == 0)
                {
                    validation = 1;
                    $(this).closest('.info-box').find('.input-num').css('border','1px solid red')
                }
                if($(this).closest('.info-box').find('.input-group').val().replace(/^\s+|\s+$/g, "").length == 0)
                {
                    validation = 1;
                    $(this).closest('.info-box').find('.input-group').css('border','1px solid red')
                }
                if($(this).closest('.info-box').find('.input-units').val().replace(/^\s+|\s+$/g, "").length == 0)
                {
                    validation = 1;
                    $(this).closest('.info-box').find('.input-units').css('border','1px solid red')
                }                
                if(validation == 0)
                {
                    $.ajax({
                        url: '/setup/subjgrouping',
                        type: 'GET',
                        data: {
                            action      : 'addgroup',
                            subjnum    :   num,
                            subjgroup    :   group,
                            subjunit    :   units
                        },
                        success:function(data){
                            if(data == 'error')
                            {
                                toastr.error('Something went wrong!', 'New Subject Group')
                            }else if(data == 0){
                                toastr.warning('Subject Group exists!', 'New Subject Group')
                            }else{
                                getgroups()
                                thiscontainer.remove()
                                toastr.success('Added successfully!', 'New Subject Group')
                            }
                        }
                    })
                }else{                    
                    toastr.warning('Please fill in required fields!', 'New Subject Group')
                }
            })
            $(document).on('click','.btn-deletegroup', function(){
                var id = $(this).attr('data-id');
                var thiscontainer = $(this).closest('.info-box');
                Swal.fire({
                    title: 'Are you sure you want to delete this record?',
                    // text: "You won't be able to revert this!",
                    html:
                        "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                        url: '/setup/subjgrouping',
                            type:"GET",
                            data:{
                                action      : 'deletegroup',
                                id :   id
                            },
                            // headers: { 'X-CSRF-TOKEN': token },,
                            success: function(data){
                                if(data == 1)
                                {
                                    toastr.success('Deleted successfully!', 'Delete Group')
                                    thiscontainer.remove()
                                }else{
                                    toastr.warning('Something went wrong!', 'Delete Group')
                                }
                            }
                        })
                    }
                })

            })
            $(document).on('change','#select-sy', function(){
                if($(this).val() == 0)
                {
                    $('#div-customsy').show()
                }else{
                    $('#div-customsy').hide()
                }
            })
            $(document).on('change','#select-course', function(){
                if($(this).val() == 0)
                {
                    $('#div-customcourse').show()
                }else{
                    $('#div-customcourse').hide()
                }
            })

            $(document).on('click','#btn-submit-addnewrecord', function(){
                var checkvalidation     = 0;

                var schoolid            = $('#input-schoolid').val();
                var schoolname          = $('#input-schoolname').val();
                var schooladdress       = $('#input-schooladdress').val();

                var syid                = $('#select-sy').val();
                var customsy            = $('#input-sy').val();
                if(syid == 0 && customsy.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    console.log('syid')
                    $('#input-sy').css('border','1px solid red');
                    $('#small-selectsy').show();
                    checkvalidation = 1;
                }else{
                    $('#input-sy').removeAttr('style')
                    $('#small-selectsy').hide();
                }

                var semid               = $('#select-sem').val();

                var courseid            = $('#select-course').val();
                var customcourse        = $('#input-coursename').val();
                if(courseid == 0 && customcourse.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    console.log('course')
                    $('#input-coursename').css('border','1px solid red');
                    $('#small-selectcourse').show();
                    checkvalidation = 1;
                }else{
                    $('#small-selectcourse').hide();
                }
                if(checkvalidation == 1)
                {
                    toastr.warning('Please fill in required fields!', 'Add new record')
                }else{
                    $(this).prop('disabled',true)
                    $.ajax({
                        url: '{{route('toraddnewrecord')}}',
                        type: 'GET',
                        dataType: '',
                        data: {
                            studid          :   $('#select-student').val(),
                            schoolid        :   schoolid,
                            schoolname      :   schoolname,
                            schooladdress   :   schooladdress,
                            syid            :   syid,
                            customsy        :   customsy,
                            semid           :   semid,
                            courseid        :   courseid,
                            customcourse    :   customcourse
                        },
                        success:function(data)
                        {
                            if(data == 1)
                            {
                                toastr.success('Added successfully!', 'Add new record')
                                $('#btn-close-addnewrecord').click();
                                $('#modal-newrecord').find('input,select').val('')
                                $('#btn-generate').click()
                            }else{
                                toastr.warning('The same form already exists!', 'Add new record')
                            }
                        }
                    }); 
                }
            })
            var torid = 0;
            $(document).on('click','.btn-adddata', function(){
                torid = $(this).attr('data-torid');
                $('#modal-adddata').modal('show')
            })
            $(document).on('click','.btn-editdata-save', function(){
                var subjgradeid = $(this).attr('data-subjgradeid');
                if($(this).closest('tr').find('.input-subjdesc').val().replace(/^\s+|\s+$/g, "").length > 0 && $(this).closest('tr').find('.input-subjgrade').val().replace(/^\s+|\s+$/g, "").length > 0)
                {
                    var thisbutton  = $(this);
                    var subjcode    = $(this).closest('tr').find('.input-subjcode').val();
                    var subjunit    = $(this).closest('tr').find('.input-subjunit').val();
                    var subjdesc    = $(this).closest('tr').find('.input-subjdesc').val();
                    var subjgrade   = $(this).closest('tr').find('.input-subjgrade').val();
                    var subjcredit  = $(this).closest('tr').find('.input-subjcredit').val();

                    $.ajax({
                        url: '{{route('toreditsubjgrade')}}',
                        type: 'GET',
                        data: {
                            torid       :   torid,
                            subjgradeid :   subjgradeid,
                            studid      :   $('#select-student').val(),
                            subjcode    :   subjcode,
                            subjunit    :   subjunit,
                            subjdesc    :   subjdesc,
                            subjgrade   :   subjgrade,
                            subjcredit  :   subjcredit
                        },
                        success:function(data){
                            if(data == 1)
                            {
                                toastr.success('Updated successfully!', 'Update data')
                            }else{
                                toastr.warning('The same form already exists!', 'Update data')
                            }
                        }
                    })
                }else{
                    $(this).closest('tr').find('.input-subjdesc').css('border','1px solid red');
                    $(this).closest('tr').find('.input-subjgrade').css('border','1px solid red');
                    toastr.warning('Please fill in required fields!', 'Update data')
                }
            })
            $(document).on('click', '.btn-delete-subjdata', function(){
                var subjgradeid = $(this).attr('data-subjgradeid');
                var thisbutton  = $(this);
                Swal.fire({
                    title: 'Are you sure you want to delete this row?',
                    // text: "You won't be able to revert this!",
                    html:
                        "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                        url: '{{route('tordeletesubjgrade')}}',
                            type:"GET",
                            data:{
                                subjgradeid :   subjgradeid
                            },
                            // headers: { 'X-CSRF-TOKEN': token },,
                            success: function(data){
                                if(data == 1)
                                {
                                    toastr.success('Deleted successfully!', 'Delete data')
                                    thisbutton.closest('tr').remove()
                                }else{
                                    toastr.warning('Something went wrong!', 'Delete data')
                                }
                            }
                        })
                    }
                })
            })
            $(document).on('click','.btn-editdata', function(){
                $(this).closest('tr').find('input,button').prop('disabled',false)
            })
            $("#modal-adddata").on("hidden.bs.modal", function () {
                $('#btn-generate').click();
            });
            $(document).on('click','#btn-exporttopdf', function(){
                window.open('/schoolform/tor/exporttopdf?studid='+$('#select-student').val(),'_blank')
            })
        })
   
    </script>
@endsection

                                        

                                        
                                        