@php

        if(!Auth::check()){ 
                header("Location: " . URL::to('/'), true, 302);
                exit();
        }
        $check_refid = DB::table('usertype')->where('id',Session::get('currentPortal'))->select('refid')->first();
        if(Session::get('currentPortal') == 17){
                $extend = 'superadmin.layouts.app2';
        }else if(Session::get('currentPortal') == 3){
                $extend = 'registrar.layouts.app';
        }else if(Session::get('currentPortal') == 6){
                $extend = 'adminPortal.layouts.app2';
        }else if(Session::get('currentPortal') == 2){
                $extend = 'principalsportal.layouts.app2';
        }
        else if(Session::get('currentPortal') == 8){
                $extend = 'admission.layouts.app2';
        }
        
        else{
                if(isset($check_refid->refid)){
                        if($check_refid->refid == 27){
                                $extend = 'academiccoor.layouts.app2';
                        }
                        if($check_refid->refid == 31){
                                $extend = 'guidance.layouts.app2';
                        }
                }else{
                header("Location: " . URL::to('/'), true, 302);
                exit();
                }
                
        }
@endphp



@section('modalSection')


        
        <div class="modal fade" id="applicant_form_modal" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-m">
                        <div class="modal-content">
                                <div class="modal-header pb-2 pt-2 border-0">
                                        <h4 class="modal-title">Applicant Form</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span></button>
                                </div>
                                <div class="modal-body">
                                        <div class="message"></div>
                                        <div class="form-group">
                                                <label>Applicant Name</label>
                                                <input id="applicantName"  name="applicantName" class="form-control form-control-sm" placeholder="Applicant Name" onkeyup="this.value = this.value.toUpperCase();">
                                        </div>
                                        <div class="form-group">
                                                <label>Address</label>
                                                <input id="applicantaddress" placeholder="Applicant Address" name="aplicantAddress" class="form-control form-control-sm" onkeyup="this.value = this.value.toUpperCase();" >
                                        </div>
                                        <div class="form-group">
                                                <label>Desired Program</label>

                                                @php
                                                
                                                        $courses = DB::table('college_courses')
                                                                        ->where('deleted', 0)
                                                                        ->get()
                                                
                                                @endphp
                                                <select name="course" id="course" class="form-control select2">
                                                        @foreach($courses as $course)
                                                                <option selected value="{{$course->id}}">{{$course->courseabrv}} - {{$course->courseDesc}}</option>
                                                        @endforeach
                                                </select>
                                        </div>
                                        <div class="form-group">
                                                <label>SHS GWA</label>
                                                <input id="shsgrades" placeholder="Senior High General Weighted Average" name="shsgrades" class="form-control form-control-sm" >
                                        </div>
                                        <div class="form-group">
                                                <label>Birthday</label>
                                                <input type="date" id="birthday" name="birthday" class="form-control form-control-sm" >
                                        </div>
                                </div>
                                <div class="modal-footer justify-content-end">
                                        <button  type="button" class="btn btn-primary btn-sm" id="add_applicant">Save <i class="fas fa-save"></i>  </button>
                                        
                                </div>
                        </div>
                </div>
        </div>

        <div class="modal fade" id="view_category_modal" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-l">
                        <div class="modal-content">
                                <div class="modal-header pb-2 pt-2 border-0">
                                        <h4 class="modal-title">Score by Category</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span></button>
                                </div>
                                <div class="modal-body">
                                        <div class="w-100" id="categorymodaltable"> </div>
                                </div>
                        </div>
                </div>
        </div>


@endsection


@extends($extend)




@section('content')
        <section class="content-header">
        <div class="container-fluid">
                <div class="row mb-2">
                <div class="col-sm-6">
                        <h1>Applicant</h1>
                </div>
                <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Applicant</li>
                </ol>
                </div>
                </div>
        </div>
        </section>
        <section class="content p-0">
        <div class="container-fluid">
                <div class="card shadow">
                <div class="card-body" style="font-size:.8rem !important">
                <table class="table table-sm table-bordered table-hovered table-hover " id="applicants_datatable">
                <thead>
                        <tr>
                        <th width="5%" class="text-center">#</th>
                        <th width="25%">Applicant Name</th>
                        <th width="10%">Pooling Number</th>
                        <th width="15%">Address</th>
                        <th width="10%">Desired Program</th>
                        <th width="10%">Birthday</th>
                        <th width="15%">Result</th>
                        <th width="10%"> </th>
                        </tr>
                </thead>
                <tbody>
                </tbody>
                </table>
                </div>
        </div>
        </div>
        </section>


@endsection




@section('footerjavascript')
<script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
<script>
                
                
        var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
        })

        var all_applicants;
        var  applicant_id;

        $(document).ready(function(){

                function all_applicantsmain(){


                        $.ajax({
                                type:'GET',
                                url: '/applicants/get',
                                success:function(data) {
                                        all_applicants = data
                                        console.log("This");
                                        console.log(data.lenght);
                                        applicants_datatable();

                                }
                        })


                }


                function applicants_datatable(){

                        $("#applicants_datatable").DataTable({
                                destroy: true,
                                data:all_applicants,
                                lengthChange : false,
                                stateSave: true,
                                autoWidth: false,
                                columns: [
                                        { "data": null },
                                        { "data": null },
                                        { "data": null },
                                        { "data": null },
                                        { "data": null },
                                        { "data": null },
                                        { "data": null },
                                        { "data": "search" },
                                ],
                                columnDefs: [
                                {
                                'targets': 0,
                                'orderable': false, 
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                        var text = '<a class="mb-0">'+(row+1)+'</a>'
                                        $(td)[0].innerHTML =  text
                                        $(td).addClass('align-middle')
                                        $(td).addClass('text-center')
                                }
                                },

                                {
                                'targets': 1,
                                'orderable': false, 
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                        $(td).text(rowData.applicantname + '(SHS GWA: ' + rowData.shsgrades + '%)');
                                }
                                },


                                {
                                'targets': 2,
                                'orderable': false, 
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                // var buttons = '<button style="font-size:.7rem !important" class="view_sched btn btn-sm btn-primary btn-block" data-id="'+rowData.id+'">View Sched</button>';
                                // $(td)[0].innerHTML =  buttons
                                $(td).text(rowData.poolingnumber)

                                }
                                },

                                {
                                'targets': 3,
                                'orderable': false, 
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                // var buttons = '<button class="btn btn-primary btn-sm" data-id="'+rowData.id+'">View Schedule</button>';
                                // $(td)[0].innerHTML =  buttons
                                // $(td).addClass('text-center')
                                // $(td).addClass('align-middle')
                                $(td).text(rowData.address)

                                }
                                },
                                {
                                'targets': 4,
                                'orderable': false, 
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                // var buttons = '<a href="javascript:void(0)" class="edit_room" data-id="'+rowData.id+'"><i class="far fa-edit"></i></a>';
                                // $(td)[0].innerHTML =  buttons
                                // $(td).addClass('text-center')
                                // $(td).addClass('align-middle')
                                $(td).text(rowData.program + (rowData.status ? ' (' + rowData.status + ')' : ''));

                                }
                                },
                                {
                                'targets': 5,
                                'orderable': false, 
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                        $(td).text(rowData.birthday)
                                }
                                },
                                {
                                'targets': 6,
                                'orderable': false, 
                                'createdCell':  function (td, cellData, rowData, row, col) {

                                        if(rowData.teststatus != null && rowData.teststatus == 1){

                                                var buttons = '<a href="/admission/viewtestresponse/' + rowData.recordid + '/' + rowData.admissiontestid + '" class="view-response" data-recordid="'+rowData.recordid+'" data-testid="'+rowData.admissiontestid+'">View response</i></a>';
                                                $(td)[0].innerHTML =  buttons

                                        }else{
                                                // $(td).html(rowData.totalscore
                                                // ? rowData.totalscore + '/' + rowData.maxpoints + ' (' + rowData.percentage + '%) <button class="btn btn-link" data-recordid="'+rowData.recordid+'" data-testid="'+rowData.admissiontestid+'" id="view_category_button"><i class="fas fa-columns"></i></button>  '
                                                // : 'Result Not Available');
                                                $(td).html(rowData.totalscore !== null && rowData.totalscore !== undefined
                                                ? rowData.totalscore + '/' + rowData.maxpoints + ' (' + rowData.percentage + '%) <button class="btn btn-link" data-recordid="'+rowData.recordid+'" data-testid="'+rowData.admissiontestid+'" id="view_category_button"><i class="fas fa-columns"></i></button>'
                                                : 'Result Not Available'
                                                );



                                        }
                                }
                                },
                                {
                                'targets': 7,
                                'orderable': false, 
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                        var buttons = '<button class="btn btn-link edit-button" data-recordid="'+rowData.recordid+'" data-id="'+ rowData.id+'" data-testid="'+rowData.admissiontestid+'" ><i class="far fa-edit"></i> </button>  ';
                                        buttons += '<button class="btn btn-link delete-button" data-recordid="'+rowData.recordid+'" data-id="'+ rowData.id+'" data-testid="'+rowData.admissiontestid+'" ><i class="fas fa-trash" style="color: #f40606;"></i> </button>  ';
                                        
                                        // if(rowData.totalscore != null){
                                        //         buttons += '<button class="btn btn-link" data-recordid="'+rowData.recordid+'" data-testid="'+rowData.admissiontestid+'" id="view_category_button"><i class="fas fa-flag"></i> </button>';
                                        // }
                                        
                                        
                                        $(td)[0].innerHTML =  buttons


                                
                                }
                                },


                                ],
                        });

                        var label_text = $($('#applicants_datatable_wrapper')[0].children[0])[0].children[0]
                        $(label_text)[0].innerHTML = '<button class="btn btn-sm btn-primary" title="Applicant" id="add_applicant_button"> <i class="fas fa-plus"></i> Add Applicant</button>'

                }


                function getscorecategory(recordid, testid){

                        $.ajax({
                                type:'GET',
                                url: '/applicants/score/category',
                                data : {
                                        recordid : recordid,
                                        testid : testid
                                },
                                success:function(data) {
                                        rendertablecategory(data);

                                }
                        })
                }

                function deleteSetup(id){



                        Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                        }).then((result) => {
                        if (result.value == true) {
                                $.ajax({
                                type:'GET',
                                url: '/applicants/delete',
                                data:{
                                        id    : id,
                                },
                                success: function(data) {

                                        all_applicantsmain()
                                        Swal.fire(
                                        'Deleted!',
                                        'The Applicant has been deleted',
                                        'success'
                                        )

                                }
                                })
                        }
                        })






                }

                function rendertablecategory(response) {

                                console.log(response);

                                if(response.length > 0) {
                                        var html = `<table class="table table-sm table-striped table-bordered table-hovered table-hover" id="applicants_datatable">
                                                        <thead class="thead-dark">
                                                        <tr>`

                                        for (var i = 0; i < response.length; i++) {
                                                html += `<th>${response[i].category}</th>`;
                                        }

                                                        
                                                                
                                                                
                                        html += `</tr>
                                                </thead>
                                                <tbody>
                                                <tr>`;

                                        
                                        for (var i = 0; i < response.length; i++) {
                                                html += `<td>${response[i].score}/${response[i].sum} (${(response[i].score/response[i].sum * 100).toFixed(2)}%) </td>`;

                                        }
                                        
                                        html += `<tr>
                                                </tbody>
                                                </table>`;

                                        $("#categorymodaltable").empty().append(html);
                                }else{

                                        var html = `<h2> No Category has been set up. </h2>`
                                        $("#categorymodaltable").empty().append(html);
                                }
                }



                //init
                all_applicantsmain();
                

                $(document).on('click','#add_applicant_button',function(){
                        $('.modal-title').text('Applicant Form');
                        $('#add_applicant').text("Save");
                        $('#add_applicant').attr('id', 'add_applicant');
                        $('.form-control').val("");
                        $('#applicant_form_modal').modal()
                })

                $(document).on('click','#view_category_button',function(){

                        var recordid = $(this).data('recordid');
                        var testid = $(this).data('testid');

                        getscorecategory(recordid, testid);

                        $('#view_category_modal').modal()
                })

                $(document).on('click','.delete-button',function(){
                        
                        var applicant_id = $(this).data('id');
                        console.log(applicant_id);
                        deleteSetup(applicant_id);
                

                });

                $(document).on('click','.edit-button',function(){
                        
                        applicant_id = $(this).data('id');

                        var data = all_applicants.filter(x=>x.id==applicant_id);

                        $('#applicantName').val(data[0].applicantname);
                        $('#applicantaddress').val(data[0].address);
                        $('#course').val(data[0].desiredprogramid);
                        $('#birthday').val(data[0].birthday);
                        $('#shsgrades').val(data[0].shsgrades);
                        
                        $('.modal-title').text(data[0].applicantname);
                        $('#add_applicant').text("Update");
                        $('#add_applicant').attr('id', 'update_applicant');
                        

                        $('#applicant_form_modal').modal()
                        

                });

                $(document).on('click','#add_applicant',function(){
                        // $('#applicant_form_modal').modal()
                        var name     = $('#applicantName').val();
                        var address  = $('#applicantaddress').val();
                        var course   = $('#course').val();
                        var birthday = $('#birthday').val();
                        var shsgrades = $('#shsgrades').val();


                        console.log(name);
                        console.log(address);
                        console.log(course);
                        console.log(birthday);

                        $('#add_applicant').prop('disabled',true);


                        if (!name || !address || !course || !birthday || !shsgrades) {
                                Swal.fire({
                                        type: 'error',
                                        title: 'Hello...',
                                        text: 'Please input all fields, thank you'
                                        })
								$('#add_applicant').prop('disabled',false);
                                return;
                        }

                        $.ajax({
                                type:'GET',
                                url: '/admissiontest/addApplicant',
                                data:{
                                        name        : name,
                                        address     : address,
                                        course      : course,
                                        birthday    : birthday,
                                        shsgrades   : shsgrades,
                                },
                                success: function(data) {
                                        $("#applicant_form_modal .close").click()
                                        $('#add_applicant').prop('disabled',false);
                                        all_applicantsmain();
                                        Toast.fire({
                                                type: 'success',
                                                title: 'Applicant Added successfully',
                                                timer: 2000,
                                        })

                                }
                        })


                })

                $(document).on('click','#update_applicant',function(){
                        // $('#applicant_form_modal').modal()
                        console.log(applicant_id)
                        var name     = $('#applicantName').val();
                        var address  = $('#applicantaddress').val();
                        var course   = $('#course').val();
                        var birthday = $('#birthday').val();
                        var shsgrades = $('#shsgrades').val();


                        console.log(name);
                        console.log(address);
                        console.log(course);
                        console.log(birthday);

                        $('#add_applicant').prop('disabled',true);


                        if (!name || !address || !course || !birthday || !shsgrades) {
                                Swal.fire({
                                        type: 'error',
                                        title: 'Hello...',
                                        text: 'Please input all fields, thank you'
                                        })
                                return;
                        }

                        $.ajax({
                                type:'GET',
                                url: '/admissiontest/updateApplicant',
                                data:{
                                        id          : applicant_id,
                                        name        : name,
                                        address     : address,
                                        course      : course,
                                        birthday    : birthday,
                                        shsgrades   : shsgrades,
                                },
                                success: function(data) {
                                        $("#applicant_form_modal .close").click()
                                        all_applicantsmain();
                                        Toast.fire({
                                                type: 'success',
                                                title: 'Applicant Update successfully',
                                                timer: 2000,
                                        })

                                }
                        })


                })


        });



</script>


@endsection


