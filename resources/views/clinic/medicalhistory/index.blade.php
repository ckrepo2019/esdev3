
@extends($extends.'.layouts.app')

<style>
    .dataTable                  { font-size:80%; }
    .tschoolschedule .card-body { height:250px; }
    .tschoolcalendar            { font-size: 12px; }
    .tschoolcalendar .card-body { height: 250px; overflow-x: scroll; }
    .teacherd ul li a           { color: #fff; -webkit-transition: .3s; }
    .teacherd ul li             { -webkit-transition: .3s; border-radius: 5px; background: rgba(173, 177, 173, 0.3); margin-left: 2px; }
    .sf5                        { background: rgba(173, 177, 173, 0.3)!important; border: none!important; }
    .sf5menu a:hover            { background-color: rgba(173, 177, 173, 0.3)!important; }
    .teacherd ul li:hover       { transition: .3s; border-radius: 5px; padding: none; margin: none; }

    .small-box                  { box-shadow: 1px 2px 2px #001831c9; overflow-y: auto scroll; }

    .small-box h5               { text-shadow: 1px 1px 2px gray; }

    img{
        border-radius: unset !important;
    }

    .select2-container .select2-selection--single {
            height: 40px !important;
        }
</style>
@section('content')
    @php
        use \Carbon\Carbon;
        $now = Carbon::now();
        $comparedDate = $now->toDateString();
    @endphp

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 class="m-0">Medical History</h3>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Medical History</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-7">
                        <label>Search</label>
                            <select class="form-control select2" style="width: 100%;" id="select-user">
                                <option>Select Student/Personnel</option>
                                @foreach($users as $user)
                                    <option value="{{$user->userid}}">{{$user->name_showlast}}</option>
                                    @endforeach
                                </select>
                    </div>

                    <div class="col-md-5">
                        <label>&nbsp;</label>
                        <button type="button" class="btn btn-dark btn-block" id="Experiences"><i class="fa fa-plus"></i> Experiences</button>
                    </div>
            </div>

        </div>

        {{-- <div class="card-body p-0">
            <table class="table table-striped projects text-center">
                <thead>
                    <tr>
                        <th style="width: 10px;">
                            #
                        </th>
                        <th class="text-left">
                            Patient
                        </th>
                        <th style="width: 30%">
                            Details
                        </th>
                            <th>
                                Project Progress
                            </th> 
                            <th style="width: 8%" class="text-center">
                                Status
                        </th>
                        <th style="width: 25%">
                            Doctor
                        </th>
                    </tr>

                </thead>
                <tbody>
                </tbody>
            </table>
            <table class="table table-striped projects text-center" id ="resultscontainer">
                <tbody>
                </tbody>
            </table> --}}
    </section>

    
        <section class="content">
            <div class="container-fluid">
                <div class="card shadow">
                <div class="card-body" style="font-size:.8rem !important">
                    <h5><strong>Appointment history<strong></h6>
                    <table class="table table-sm table-striped table-bordered table-hovered table-hover " style = "font-size: 16px" id="medicals_datatable">
                    <thead>
                        <tr>
                        <th width="10%">#</th>
                        <th width="30%">Patient</th>
                        <th width="25%" class="p-0 text-center align-middle">Details</th>
                        <th width="20%">Status</th>
                        <th width="15%">Physician</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    </table>
                    <br>
                    <br>
                    <h5><strong>Complaint history<strong></h6>
                    <table class="table table-sm table-striped table-bordered table-hovered table-hover " style = "font-size: 16px" id="complaints_datatable">
                    <thead>
                        <tr>
                        <th width="10%">#</th>
                        <th width="30%">Date</th>
                        <th width="25%" class="p-0 text-center align-middle">Complaint</th>
                        <th width="20%">Action Taken</th>
                        <th width="15%">Medicine given</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    </table>
                    <br>
                    <br>
                    <h5><strong>Health History</strong></h5>
                    <form class= "row">
                        <div class="col-md-12 mb-2">
                            <label for="name">List All Hospitalizations / Surgeries (include dates)</label>
                            <textarea class="form-control" id="hospitalization" name="hospitalization" rows="3"></textarea>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="lastName">Family History of Illness</label>
                            <textarea class="form-control" id="history" name="history" rows="3"></textarea>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label>Do You or Have You Ever Smoked?</label>
                            <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="smoke" name="smoke" value="male">
                            <label class="form-check-label" for="male">Yes</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="age">Age started smoking</label>
                            <input type="number" class="form-control" id="age" name="age" placeholder="Leave blank if not smoking">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="age">Age quit</label>
                            <input type="number" class="form-control" id="age1" name="age1" placeholder="Leave blank if not smoking">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label >Pack per day</label>
                            <input type="number" class="form-control" id="pack" name="pack" placeholder="Leave blank if not smoking">
                        </div>

                        <div class="col-md-3 mb-2">
                            <label for="married">Do You Drink Alcohol?</label>
                            <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="alcohol" name="alcohol" value="yes">
                            <label class="form-check-label" for="married">Yes</label>
                            </div>
                        </div>
                        <div class="col-md-9 mb-2">
                            <label for="salary">Average Drink per day:</label>
                            <input type="number" class="form-control" id="average" name="average" placeholder="Leave blank if not drinking">
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="dream">Current Medications (include strength & dosage)</label>
                            <textarea class="form-control" id="medication" name="medication" rows="3"></textarea>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="allergies">Allergies:</label>
                            <textarea class="form-control" id="allergies" name="allergies" rows="3"></textarea>
                        </div>
                        <div class="col-md-3 mb-2">
                            <button type="button" class="btn btn-primary" id ="update">Update</button>
                        </div>
                        </form>

                </div>
            </div>
            </div>
        </section>

    <div class="modal fade" id="modal_experience">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Experiences</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="p-1">
                    <button class="btn btn-sm btn-primary" id="btn_addexperience" data-toggle="modal" data-target="#modal-addexperience">Add Experiences</button>
                </div>
                <div class="p-1">
                    <small class="badge badge-danger">Question: Please Check if You Have Experienced Any of the Following:</small>
                </div>
                <div class="p-1">
                    <table class="table table-sm table-striped  table-bordered table-responsive-sm w-100" id="table_experience">
                        <thead>
                            <tr>
                                <th>Option</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="modal-footer justify-content-between">
            </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="modal-addexperience">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Option</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Option</label><br/>
                        <input type="text" class="form-control" placeholder="Add option" id="input-addoption"/>
                    </div>
                </div>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="btn-closeexperience">Close</button>
                <button type="button" class="btn btn-success" id="btn-updateoption" hidden>Update</button>
                <button type="button" class="btn btn-primary" id="btn-addoption">Add</button>
            </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    @endsection
    @section('footerjavascript')
    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2()
        
            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })
            // $('#input-daterange').daterangepicker()
        })  
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        function gethistory(userid)
        {
            
            Swal.fire({
                title: 'Fetching data...',
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
                allowOutsideClick: false
            })

            $.ajax({
                url: '/clinic/medicalhistory/gethistory',
                type: 'GET',
                data: {
                    userid  : userid
                },
                success:function(data){
                    $('#resultscontainer').empty();
                    $('#resultscontainer').append(data)
                    $(".swal2-container").remove();
                    $('body').removeClass('swal2-shown')
                    $('body').removeClass('swal2-height-auto')
                }
            })
        }
        gethistory($('#select-user').val())
        var all_history = []
        var all_complaints = []
        var name = '';
        var markdate = '';
        var markdate2 = '';
        key = 0;

        function medicals_datatable(){
        key = 0;
            $("#medicals_datatable").DataTable({
               // destroy: true,
                    destroy: true,
                    lengthChange : false,
                    stateSave: true,
                    searching: false,
                   // autoWidth: false,
                    data:all_history,
        
                columns: [
                    { "data": null },
                    {  "data": null},
                    { "data": null },
                    { "data": null },
                    { "data": null },

            ],
            columnDefs: [
                    
                    {
                    'targets': 0,
                    'orderable': false, 
                    'createdCell':  function (td, cellData, rowData, row, col) {
                            $(td)[0].innerHTML = key +=1
                    }
                    },
                    {
                    'targets': 1,
                    'orderable': false, 
                    'createdCell':  function (td, cellData, rowData, row, col) {
                            var date2 =  new Date(Date.parse(rowData.createddatetime));
                            markdate = date2.toLocaleDateString("en-US", {month: "long", year: "numeric", day: "numeric", hour: "numeric", minute: "numeric"});
                            $(td)[0].innerHTML = name +'<br/>' + '<b>' + "Submitted: " + markdate + '</b>'
                    }
                    },
                    {

                    'targets': 2,
                    'orderable': false, 
                    'createdCell':  function (td, cellData, rowData, row, col) {
                            $(td)[0].innerHTML = rowData.description
                    }
                    },
                    {
                    'targets': 3,
                    'orderable': false, 
                    'createdCell':  function (td, cellData, rowData, row, col) {
                            if (rowData.label == 1){
                            var date3 =  new Date(Date.parse(rowData.labeldatetime))
                            markdate2 = date3.toLocaleDateString("en-US", {month: "long", year: "numeric", day: "numeric", hour: "numeric", minute: "numeric"});
                            $(td)[0].innerHTML = "Marked as Done" + '<br/>' + '<b>' + markdate2 + '</b>' 
                            }else{
                            $(td)[0].innerHTML = "Incomplete"
                            $(td).addClass('align-middle')    
                            } 
                            

                    }
                    },
                    {
                    
                    'targets': 4,
                    'orderable': false, 
                    'createdCell':  function (td, cellData, rowData, row, col) {
                            $(td)[0].innerHTML = 'Dr.'+ rowData.firstname  + " "+  rowData.lastname



                    }
                    },
                ],
            });
        }

        function complaints(){
        key = 0;
                $("#complaints_datatable").DataTable({
                // destroy: true,
                        destroy: true,
                        lengthChange : false,
                        stateSave: true,
                        searching: false,
                    // autoWidth: false,
                        data:all_complaints,
            
                    columns: [
                        { "data": null },
                        {  "data": null},
                        { "data": null },
                        { "data": null },
                        { "data": null },

                ],
                columnDefs: [
                        
                        {
                        'targets': 0,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                                $(td)[0].innerHTML = key +=1
                        }
                        },
                        {
                        'targets': 1,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                                var date2 =  new Date(Date.parse(rowData.cdate));
                                markdate = date2.toLocaleDateString("en-US", {month: "long", day: "numeric",  year: "numeric"});
                                $(td)[0].innerHTML = markdate
                        }
                        },
                        {

                        'targets': 2,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                                $(td)[0].innerHTML = rowData.description
                        }
                        },
                        {
                        'targets': 3,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                                $(td)[0].innerHTML = rowData.actiontaken
                        }
                        },
                        {
                        
                        'targets': 4,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                                $(td)[0].innerHTML = rowData.med 



                        }
                        },
                    ],
                });
            }
        var id1;

        $(document).ready(function(){
            medicals_datatable();
            complaints();



            $('#Experiences').on('click', function(){
                $('#modal_experience').modal('show')
                get_options();

            })

            $('#btn-addoption').on('click', function(){

                var newoption  = $('#input-addoption').val();
                console.log(newoption);

                $.ajax({
                url: '/clinic/appointment/createexperience',
                type: 'GET',
                data: {
                    newoption:   newoption
                },
                success:function(data){
                    if(data == 0){
                        Toast.fire({
                                    type: 'success',
                                    title: 'Option allready exist!'
                                })
                    }else{
                        Toast.fire({
                                    type: 'success',
                                    title: 'Added successfully!'
                                })

                    $('#btn-closeexperience').click();

                    }
                    get_options();
                }
            })


            })


            $('#update').on('click', function(){
            console.log(id1);
            var smokedstatus;
            var drinkstatus;

            var hospitalization  = $('#hospitalization').val();
            var history      = $('#history').val();
            var age          = $('#age').val();
            var age1         = $('#age1').val();
            var average      = $('#average').val();
            var medication   = $('#medication').val();
            var allergies    = $('#allergies').val();
            var pack    = $('#pack').val();

            var i = document.querySelector('#smoke:checked') !== null;
            var j = document.querySelector('#alcohol:checked') !== null;
            
            if(i==true){
                            smokedstatus = 0;
                        }else{
                            smokedstatus = 1;
                        }
            if(j==true){
                            drinkstatus = 0;
                        }else{
                            drinkstatus = 1;
                        }
            
            $.ajax({
                url: '/clinic/medicalhistory/update',
                type: 'GET',
                data: {
                    id              : id1,
                    hospitalization : hospitalization, 
                    history         : history,
                    age             : age,
                    age1            : age1,
                    average         : average,
                    medication      : medication,
                    allergies       : allergies,
                    smokedstatus    : smokedstatus,
                    drinkstatus     : drinkstatus,
                    pack            : pack
                },
                success:function(data){
                    if(data == 0){
                        holder(id1);
                        Toast.fire({
                                    type: 'success',
                                    title: 'Updated successfully!'
                                })
                    }else{
                        holder(id1);
                        Toast.fire({
                                    type: 'success',
                                    title: 'Added successfully!'
                                })

                    }
                }
            })

            })

            $('#select-user').on('change',function(){
                var userid   = $(this).val();
                var id = Number(userid);
                id1 = id;
                holder(id);
                
            })

            function holder(id){

                var checkbox = document.getElementById("smoke");
                var checkbox2 = document.getElementById("alcohol");
                name=  jQuery("#select-user").find("option[value='" + jQuery("#select-user").val() + "']").text()
                
                $.ajax({
                    url:'/clinic/medicalhistory/gethistory2',
                            type:'GET',
                            dataType: 'json',
                            data: {
                                id      :  id
                            },
                            success:function(data1) {
                                all_history = data1;
                                console.log(all_history);
                                medicals_datatable();
        
                            }
            })

            $.ajax({
                    url:'/clinic/medicalhistory/getcomplaint2',
                            type:'GET',
                            dataType: 'json',
                            data: {
                                id      :  id
                            },
                            success:function(data1) {
                                all_complaints = data1;
                                console.log(all_complaints);
                                complaints()
        
                            }
            })
                
                
                $.ajax({
                    url:'/clinic/medicalhistory/gethistory',
                            type:'GET',
                            dataType: 'json',
                            data: {
                                id      :  id
                            },
                            success:function(data1) {
                                if(data1.length> 0){
                                    console.log(data1);
                                    $('#hospitalization').val(data1[0].hospitalization);
                                    $('#history').val(data1[0].familyhistory);
                                    $('#age').val(data1[0].agestarted);
                                    $('#age1').val(data1[0].agequit);
                                    $('#pack').val(data1[0].packs);
                                    if(data1[0].smoke == 0){
                                        checkbox.checked = true;
                                    }
                                    if(data1[0].alcohol == 0){
                                        checkbox2.checked = true;
                                    }
                                    $('#average').val(data1[0].averagedrink);
                                    $('#medication').val(data1[0].currentMedications);
                                    $('#allergies').val(data1[0].allergies);
                                }else{
                                    $('#hospitalization').val("");
                                    $('#history').val("");
                                    $('#age').val("");
                                    $('#age1').val("");
                                    $('#pack').val("");
                                    checkbox.checked = false;
                                    checkbox2.checked = false;
                                    $('#average').val("");
                                    $('#medication').val("");
                                    $('#allergies').val("");
                                }
        
                            }
            })
            }
            // filter($('#input-daterange').val());

            // $('#btn-filter').on('click', function(){

            //     var selecteddaterange = $('#input-daterange').val();
            //     filter(selecteddaterange);

            // })

            // $(document).on('click','.btn-appointmentadmit', function(){
            //     var appointmentid   = $(this).attr('data-id');
            //     Swal.fire({
            //         title: 'You are going to accept/admit this appointment.',
            //         text: 'Would you like to continue?',
            //         type: 'info',
            //         showCancelButton: true,
            //         confirmButtonColor: '#3085d6',
            //         cancelButtonColor: '#d33',
            //         confirmButtonText: 'Continue'
            //     })
            //     .then((result) => {
            //         if (result.value) {
            //             $.ajax({
            //                 url:'/clinic/appointment/admitaccept',
            //                 type:'GET',
            //                 dataType: 'json',
            //                 data: {
            //                     id      :  appointmentid
            //                 },
            //                 success:function(data) {
            //                     if(data == 1)
            //                     {
            //                         Toast.fire({
            //                             type: 'success',
            //                             title: 'Admitted successfully!'
            //                         })
            //                         filter($('#input-daterange').val());
            //                     }else if(data == 2){
            //                         Toast.fire({
            //                             type: 'warning',
            //                             title: 'Appointment is admitted already!'
            //                         })
            //                         filter($('#input-daterange').val());
            //                     }else{
            //                         Toast.fire({
            //                             type: 'error',
            //                             title: 'Something went wrong!'
            //                         })
            //                     }
            //                 }
            //             })
            //         }
            //     })
            // })
            // $(document).on('click','.btn-appointmentcancel', function(){
            //     var appointmentid   = $(this).attr('data-id');
            //     Swal.fire({
            //         title: 'You are going to drop this appointment.',
            //         text: 'Would you like to continue?',
            //         type: 'info',
            //         showCancelButton: true,
            //         confirmButtonColor: '#3085d6',
            //         cancelButtonColor: '#d33',
            //         confirmButtonText: 'Continue'
            //     })
            //     .then((result) => {
            //         if (result.value) {
            //             $.ajax({
            //                 url:'/clinic/appointment/admitcancel',
            //                 type:'GET',
            //                 dataType: 'json',
            //                 data: {
            //                     id      :  appointmentid
            //                 },
            //                 success:function(data) {
            //                     if(data == 1)
            //                     {
            //                         Toast.fire({
            //                             type: 'success',
            //                             title: 'Dropped successfully!'
            //                         })
            //                         filter($('#input-daterange').val());
            //                     }else{
            //                         Toast.fire({
            //                             type: 'error',
            //                             title: 'Something went wrong!'
            //                         })
            //                     }
            //                 }
            //             })
            //         }
            //     })
            // }
            

            function experience_datatable(data){
                console.log(data);
                $('#table_experience').DataTable({
                    destroy: true,
                    data:data,
                    paging: false,
                    searching: false,
                    order: false,
                    columns:[
                        { data : 'description' },
                        { data : null},
  
                    ],
                    columnDefs: [
                        {
                                targets: 0,
                                orderable: false,
                                createdCell: function(td, cellData, rowData) {

                                    $(td).html(`<p  class="section_link mb-0 ">${rowData.description}</p>`)
                                    .addClass('align-middle fw-900')
                                    .css('vertical-align', 'middle');

                                }
                        },
                        {
                                targets: 1,
                                orderable: false,
                                createdCell: function(td, cellData, rowData) {
                                    $(td).html(`<a href="javascript:void(0)" class="section_link mb-0" style="white-space: nowrap" data-id="">
                                        <button class="btn btn-sm btn-primary edit_option" id="edit_option"  data-description="${rowData.description}" data-toggle="modal" data-target="#modal-addexperience" data-id="${rowData.id}"><i class="fas fa-edit"></i></button>
                                        <button class="btn btn-sm btn-danger delete_option" id="delete_option"  data-id="${rowData.id}"><i class="fas fa-trash"></i></button></a>`)
                                    .addClass('align-middle text-center')
                                    .css('vertical-align', 'middle');

                                }
                        },
                        
                    ]
                })
            }

            function get_options(){
                $.ajax({
                    url:'/clinic/medicalhistory/experiences/get',
                    type:'GET',
                    dataType: 'json',
                    success:function(data) {
                        console.log(data);
                        experience_datatable(data);
                    }
                })
            }
            function show_modal(update){
                if(update == 1){
                    $('#btn-updateoption').removeAttr('hidden');
                    $('#btn-addoption').attr('hidden', 'hidden');
                }else{
                    $('#btn-addoption').removeAttr('hidden');
                    $('#btn-updateoption').attr('hidden', 'hidden');
                }
            }
            var update;

            $(document).on('click', '#btn_addexperience', function(){
                update = 0;
                show_modal(update)
            })
            var exid
            $(document).on('click', '.edit_option', function(){
                exid = $(this).attr('data-id');
                var desc = $(this).attr('data-description');
                $('#input-addoption').val(desc);
                update = 1;
                show_modal(update)
                
            })

            $(document).on('click', '#btn-updateoption', function(){
                var option = $('#input-addoption').val();
                $.ajax({
                    type:'get',
                    url: '/clinic/medicalhistory/experiences/update',
                    data:{
                        id : exid,
                        option : option
                    },
                    success:function(data){
                        get_options();
                        Toast.fire({
                            type: 'success',
                            title: 'Succesfully Updated',
                        })
                        $('#modal-addexperience').modal('hide')
                    }
                })
            })

            $(document).on('click', '.delete_option', function(){
                var id = $(this).attr('data-id');
                $.ajax({
                    type:'get',
                    url: '/clinic/medicalhistory/experiences/delete',
                    data:{
                        id : id
                    },
                    success:function(data){
                        get_options();
                        Toast.fire({
                            type: 'success',
                            title: 'Succesfully Deleted',
                        })
                    }
                })
            })
        })
    </script>
@endsection
