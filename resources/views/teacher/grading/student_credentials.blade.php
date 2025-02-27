
@php

    if(!Auth::check()){ 
        header("Location: " . URL::to('/'), true, 302);
        exit();
    }

    if(Session::get('currentPortal') == 1){
        $extend = 'teacher.layouts.app';
    }else if(auth()->user()->type == 6){
        $extend = 'adminPortal.layouts.app2';
    }else if(auth()->user()->type == 17){
        $extend = 'superadmin.layouts.app2';
    }else if(Session::get('currentPortal') == 3){
        $extend = 'registrar.layouts.app';
    }else{
        header("Location: " . URL::to('/'), true, 302);
        exit();
      }
@endphp

@extends($extend)

@section('content')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
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
    $schoolinfo = DB::table('schoolinfo')->first();
   $sy = DB::table('sy')->orderBy('sydesc')->get(); 
@endphp


<div class="modal fade" id="reset_pass_modal" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-sm">
          <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" style="font-size: 1.1rem !important">Reset Password</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span></button>
                </div>
               
                <div class="modal-body pt-0">
                    <div class="row">
                        <div class="col-md-12">
                            <span id="learner_password_text"></span>
                        </div>
                    </div>
                    {{-- <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-primary btn-sm btn-block" id="gen_default_password">Default Password</button>
                        </div>
                    </div> --}}
                    <div class="row">
                        <div class="col-md-12 mt-2">
                            <button class="btn btn-primary btn-sm btn-block" id="gen_sysgen_password">System Generated Password</button>
                        </div>
                    </div>
                </div>
          </div>
    </div>
</div>   


<div class="modal fade" id="reset_all_pass_modal" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-sm">
          <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title" style="font-size: 1.1rem !important">Reset All <span id="all_password_text"></span> Password</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <button class="btn btn-primary btn-sm btn-block generate_all_pass" gen-type="default">Default Password</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mt-2">
                            <button class="btn btn-primary btn-sm btn-block generate_all_pass" gen-type="sygen">System Generated Password</button>
                        </div>
                    </div>
                </div>
          </div>
    </div>
</div>   


<section class="content-header pt-0">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Student Credentials</h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item active">Student Credentials</li>
            </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
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
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="">School year</label>
                                    <select class="form-control select2" id="input_sy">
                                          {{-- <option value="" selected="selected">School Year</option> --}}
                                          @foreach ($sy as $item)
                                                @php
                                                    $active = $item->isactive == 1 ? 'selected="seleted"' : ''
                                                @endphp
                                                <option value="{{$item->id}}" {{$active}}>{{$item->sydesc}}</option>
                                          @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2" id="section_Holder" hidden>
                                    <label for="">Section</label>
                                    <select class="form-control select2" id="input_section">
                                        <option value="" selected="selected">Select Section</option>
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
                        <div class="card-body">
                            <div class="col-md-12">
                                <table class="table table-sm" id="datatable_1">
                                    <thead>
                                        <tr>
                                            <th width="50%">Student Name</th>
                                            <th width="25%">Student Credentials</th>
                                            <th width="25%">Parent Credentials</th>
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
<script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>

<script>
    $(document).ready(function(){

        var selected_id = null
        var selected_studid = null
        var selected_type = null
        var selected_passwordtype = null
        var selected_usertype = null
        var selected_gentype = null
        var portal = @json(Session::get('currentPortal'))

        if(portal != 3){
            $('#section_Holder').removeAttr('hidden')
        }

        var school_setup = @json($schoolinfo);
        var isonline = false;

        if(school_setup.projectsetup == 'online' &&  school_setup.processsetup == 'hybrid1'){
            enable_button = false;
        }else{
            if(school_setup.projectsetup == 'offline' && ( school_setup.processsetup == 'hybrid1' || school_setup.processsetup == 'all' ) ){
                isonline = true
                // $('#online_connection_holder').removeAttr('hidden')
                check_online_connection()
            }
        
        }

        function check_online_connection(){
            $.ajax({
                  type:'GET',
                  url: school_setup.es_cloudurl+'/checkconnection',
                  success:function(data) {
                    connected_stat = true
                    // get_last_index('users',true)
                    // get_last_index('teacher',true)
                    // get_last_index('teacheracadprog',true)
                    // get_last_index('faspriv',true)
                    $('#online_status').text('Connected')
                  }, 
                  error:function(){
                    $('#online_status').text('Not Connected')
                  }
            })
         }

        $('.select2').select2()

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
            })

            var all_sections = []
            var temp_data = []
            if(portal != 3){
                get_sections()
            }
            

            // var table = subjectplot_datatable(temp_data)
            // table.state.clear();
            // table.destroy();

            $(document).on('change','#input_sy',function(){
                // var temp_data = []
                // subjectplot_datatable(temp_data)
                if(portal != 3){
                    get_sections()
                }
                get_student_credentials()
            })

            $(document).on('change','#input_section',function(){
                // if($(this).val() == ""){
                //     var temp_data = []
                //     subjectplot_datatable(temp_data)
                //     return false
                // }
                get_student_credentials()
            })

            $(document).on('click','.genrate_password',function(){
                selected_id = $(this).attr('data-id');
                selected_studid = $(this).attr('data-studid');
                selected_type = $(this).attr('data-type');
                var temp_learner = all_account.filter(x=>x.id == selected_studid)
                if(selected_type == 'student'){
                    $('#learner_password_text').text(temp_learner[0].student+' (Student)')
                }else{
                    $('#learner_password_text').text(temp_learner[0].student+' (Parent)')
                }
                

                $('#reset_pass_modal').modal()
             
            })


            $(document).on('click','#gen_sysgen_password',function(){
                selected_passwordtype = 3
                update_password(selected_id,selected_studid,selected_type)
            })

            $(document).on('click','#gen_default_password',function(){
                selected_passwordtype = 1
                update_password(selected_id,selected_studid,selected_type)
            })

            $(document).on('click','.generate_stud_acct',function(){
                var temp_studid = $(this).attr('data-studid');
                generate_student_account(temp_studid)
            })

            $(document).on('click','.generate_parent_acct',function(){
                var temp_studid = $(this).attr('data-studid');
                generate_parent_account(temp_studid)
            })


            $(document).on('click','#reset_all_student_pass',function(){
                $('#all_password_text').text('Student')
                $('#reset_all_pass_modal').modal()
                selected_usertype = 'student'
            })

            $(document).on('click','#reset_all_parents_pass',function(){
                $('#all_password_text').text('Parent')
                $('#reset_all_pass_modal').modal()
                selected_usertype = 'parent'
            })
            

            $(document).on('click','.generate_all_pass',function(){
                selected_gentype = $(this).attr('gen-type')
                update_all_password()
            })

            $(document).on('change','#data_from',function(){
                get_student_credentials()
            })

            function update_all_password(){

                var temp_sectionid = $('#input_section').val()
                var temp_sectioninfo = all_sections.filter(x=>x.id == temp_sectionid)

                var url = '/teacher/student/reset/all'

                if(isonline){
                    url = school_setup.es_cloudurl+'/teacher/student/reset/all'
                }

                $.ajax({
					type:'GET',
					url: url,
                    data:{
                        syid:$('#input_sy').val(),
                        sectionid:temp_sectioninfo[0].id,
                        levelid:temp_sectioninfo[0].levelid,
                        usertype:selected_usertype,
                        gentype:selected_gentype
                    },
					success:function(data) {
                        if(data[0].status == 1){
                            Toast.fire({
                                type: 'success',
                                title: 'Account Updated!'
                            })
                            get_student_credentials()
                        }else{
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }
					}
			    })

            }

            function get_sections(){

                var url = '/teacher/student/credential/advisory'

                if(isonline){
                    url = school_setup.es_cloudurl+'/teacher/student/credential/advisory'
                }


                $.ajax({
					type:'GET',
					url: url,
                    data:{
                        syid:$('#input_sy').val()
                    },
					success:function(data) {
                        all_sections = data
                        if(data.length > 0){
                            $("#input_section").empty()
                            $("#input_section").append('<option value="">Select Section</option>')
                            $("#input_section").select2({
                                    data: all_sections,
                                    allowClear: true,
                                    placeholder: "Select Section",
                            })

                            Toast.fire({
                                type: 'success',
                                title: all_sections.length+' sections found!'
                            })

                        }else{

                            Toast.fire({
                                type: 'warning',
                                title: 'No section found!'
                            })
                            
                            $("#input_section").empty()
                            $("#input_section").select2({
                                    data: all_sections,
                                    allowClear: true,
                                    placeholder: "Select Section",
                            })
                        }
					}
			    })
            }

            // var all_account = []

            // function get_student_credentials(){

            //     var temp_sectionid = $('#input_section').val()
            //     var temp_sectioninfo = all_sections.filter(x=>x.id == temp_sectionid)

            //     $.ajax({
			// 		type:'GET',
			// 		url: '/teacher/student/credential/list',
            //         data:{
            //             syid:$('#input_sy').val(),
            //             sectionid:temp_sectioninfo[0].id,
            //             levelid:temp_sectioninfo[0].levelid,
            //         },
			// 		success:function(data) {
            //             all_account = data
            //             if(data.length > 0){
            //                 Toast.fire({
            //                     type: 'success',
            //                     title: data.length+' students found!'
            //                 })
            //                 subjectplot_datatable(data)
            //             }else{
            //                 Toast.fire({
            //                     type: 'warning',
            //                     title: 'No student found!'
            //                 })
            //                 subjectplot_datatable(data)
            //             }
			// 		}
			//     })
            // }

            function update_password(id,studid,type){

                var url = '/teacher/student/generate/password'

                if(isonline){
                    url = school_setup.es_cloudurl+'/teacher/student/generate/password'
                }

                $.ajax({
                    type:'GET',
                    url: url,
                    data:{
                        id:id,
                        passwordtype:selected_passwordtype
                    },
                    success:function(data) {
                        if(data[0].status == 1){
                            Toast.fire({
                                type: 'success',
                                title: 'Password Generated!'
                            })
                            if(type == 'parent'){
                                get_student_credentials()
                            }else if(type == 'student'){
                                get_student_credentials()
                            }
                        }
                    }
                })
            }

            function generate_parent_account(studid){

                var temp_index = all_account.findIndex(x=>x.id == studid)
                var temp_account = all_account[temp_index]

                var url = '/teacher/student/generate/parentaccount'

                if(isonline){
                    url = school_setup.es_cloudurl+'/teacher/student/generate/parentaccount'
                }

                $.ajax({
                    type:'GET',
                    url: url,
                    data:{
                        sid:temp_account.sid
                    },
                    success:function(data) {
                        if(data[0].status == 1){
                            Toast.fire({
                                type: 'success',
                                title: 'Account Generated!'
                            })
                            get_student_credentials()
                        }else{
                            Toast.fire({
                                type: 'success',
                                title: data[0].message
                            })
                        }
                    }
                })
            }

            function generate_student_account(studid){

                var temp_index = all_account.findIndex(x=>x.id == studid)
                var temp_account = all_account[temp_index]

                var url = '/teacher/student/generate/studentaccount'

                if(isonline){
                    url = school_setup.es_cloudurl+'/teacher/student/generate/studentaccount'
                }

                $.ajax({
                    type:'GET',
                    url: url,
                    data:{
                        id:studid,
                        sid:temp_account.sid,
                        student:temp_account.student,
                    },
                    success:function(data) {
                        if(data[0].status == 1){
                            Toast.fire({
                                type: 'success',
                                title: 'Account Generated!'
                            })
                            get_student_credentials()
                        }else{
                            Toast.fire({
                                type: 'success',
                                title: data[0].message
                            })
                        }
                    }
                })
            }

            // test_clip()

            // function test_clip(){
            //     $temp.val("sdsdf").select();
            //     document.execCommand("copy");
            //     $temp.remove();

            // }

            var all_account = []
            get_student_credentials()

            function get_student_credentials(){

                var url = '/teacher/student/credential/list'

                if(isonline){
                    url = school_setup.es_cloudurl+'/teacher/student/credential/list'
                }

                $("#datatable_1").DataTable({
                    destroy: true,
                    stateSave: true,
                    lengthChange: false,
                    columns: [
                            { "data": "studentname" },
                            { "data": null },
                            { "data": null },
                    ],
                    serverSide: true,
                    processing: true,
                    ajax:{
                            url: url,
                            type: 'GET',
                            data: {
                                syid:$('#input_sy').val(),
                                sectionid:$('#input_section').val(),
                            },
                            dataSrc: function ( json ) {
                                
                                all_account = json.data
                                return json.data;
                            }
                    },
                    columnDefs: [
                            {
                                'targets': 0,
                                'orderable': false, 
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                        $(td).addClass('align-middle')
                                        $(td)[0].innerHTML = rowData.studentname+'<p class="text-muted mb-0" style="font-size:.7rem">'+rowData.sid+'</p>'
                                }
                            },
                            {
                                'targets': 1,
                                'orderable': false, 
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                    var text = ''
                                    if(rowData.student_credentials.length > 0){

                                       

                                        // if(rowData.student_credentials[0].passwordstr != null){
                                        //     p_pass = rowData.student_credentials[0].passwordstr+'  '+'<a href="javascript:void(0)" class="genrate_password text-danger"  data-id="'+rowData.student_credentials[0].id+'" data-studid="'+rowData.id+'" data-type="student">Generate</a>'
                                        // }

                                        var p_pass = ''

                                        if(rowData.student_credentials[0].isDefault == 3){
                                            p_pass = rowData.student_credentials[0].passwordstr+'  '+'<a href="javascript:void(0)" class="genrate_password text-danger"  data-id="'+rowData.student_credentials[0].id+'" data-studid="'+rowData.id+'" data-type="student">Reset</a>'
                                        }else if(rowData.student_credentials[0].isDefault == 1){
                                            p_pass = 'Default Password '+'<a href="javascript:void(0)" class="genrate_password text-danger"  data-id="'+rowData.student_credentials[0].id+'" data-studid="'+rowData.id+'" data-type="student">Generate</a>'
                                        }else if(rowData.student_credentials[0].isDefault == 0){
                                            p_pass = '<i class="text-danger ">Password Changed</i> - <a href="javascript:void(0)" class="genrate_password"  data-id="'+rowData.student_credentials[0].id+'" data-studid="'+rowData.id+'" data-type="student">Reset</a>'
                                        }
                                        
                                        text = '<a class="mb-0">'+rowData.student_credentials[0].email+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+p_pass+'</p>';
                                    }else{
                                        text = '<a class="mb-0 text-danger"><i>No Student Account</i></a><p class="text-muted mb-0" style="font-size:.7rem"><a href="javascript:void(0)" class="generate_stud_acct" data-studid="'+rowData.id+'">Generate Student Account</a></p>';
                                    }
                                    
                                    $(td)[0].innerHTML =  text
                                    $(td).addClass('align-middle')
                                }
                            },
                            {
                                'targets': 2,
                                'orderable': false, 
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                    var text = ''
                                    if(rowData.parent_credentials.length > 0){

                                        // var p_pass = '<i class="text-danger ">No password</i> - <a href="javascript:void(0)" class="genrate_password"  data-id="'+rowData.parent_credentials[0].id+'" data-studid="'+rowData.id+'" data-type="parent">Generate Parent Password</a>'

                                        // if(rowData.parent_credentials[0].passwordstr != null){
                                        //     p_pass = rowData.parent_credentials[0].passwordstr+'  '+'<a href="javascript:void(0)" class="genrate_password text-danger"  data-id="'+rowData.parent_credentials[0].id+'" data-studid="'+rowData.id+'" data-type="parent">Generate</a>'
                                        // }


                                        
                                        var p_pass = ''

                                        if(rowData.parent_credentials[0].isDefault == 3){
                                            p_pass = rowData.parent_credentials[0].passwordstr+'  '+'<a href="javascript:void(0)" class="genrate_password text-danger"  data-id="'+rowData.parent_credentials[0].id+'" data-studid="'+rowData.id+'" data-type="parent">Reset</a>'
                                        }else if(rowData.parent_credentials[0].isDefault == 1){
                                            p_pass = 'Default Password '+'<a href="javascript:void(0)" class="genrate_password text-danger"  data-id="'+rowData.parent_credentials[0].id+'" data-studid="'+rowData.id+'" data-type="parent">Generate</a>'
                                        }else if(rowData.parent_credentials[0].isDefault == 0){
                                             p_pass = '<i class="text-danger ">Password Changed</i> - <a href="javascript:void(0)" class="genrate_password"  data-id="'+rowData.parent_credentials[0].id+'" data-studid="'+rowData.id+'" data-type="parent">Reset</a>'

                                           
                                        }

                                        text = '<a class="mb-0">'+rowData.parent_credentials[0].email+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+p_pass+'</p>';

                                    }else{
                                        text = '<a class="mb-0 text-danger"><i>No Parent Account</i></a><p class="text-muted mb-0" style="font-size:.7rem"><a href="javascript:void(0)" class="generate_parent_acct" data-studid="'+rowData.id+'">Generate Parent Account</a></p>';
                                    }
                                    
                                    $(td)[0].innerHTML =  text
                                    $(td).addClass('align-middle')
                                }
                            }
                            
                    ]
                    
                });

                if($('#input_section').val() != "" && $('#input_section').val() != null){
                    var label_text = $($('#datatable_1_wrapper')[0].children[0])[0].children[0]
                              // $(label_text)[0].innerHTML = '<button class="btn btn-primary btn-sm add_student_to_prereg">Add Student to Preregistration</button><button class="btn btn-primary btn-sm ml-2" id="reservation_list">Reservation List</button>'
                    $(label_text)[0].innerHTML = ' <button class="btn btn-primary btn-sm" id="reset_all_student_pass"><i class="fas fa-sync"></i> Reset All Students Password</button><button class="btn btn-primary btn-sm ml-2" id="reset_all_parents_pass" ><i class="fas fa-sync" ></i> Reset All Students Password</button>'
                }

               


            }


    })
</script>
    

@endsection