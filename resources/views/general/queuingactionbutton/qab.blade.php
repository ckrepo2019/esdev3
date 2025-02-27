
<link rel="stylesheet" href="{{asset('dist/css/floating-button.css')}}">



<style>
    .swal2-header {
        border-bottom: 0px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        margin-top: -9px;
    }

    .hidden{

        display: none;
    }
    
</style>


<div id="QAB" class="hidden">
    <!-- Window Configuration Modal -->
    <div class="modal fade" id="selectWindow" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Window Configuration</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <div class="bg-warning p-2" style="border-radius: 5px">
                    <p class="m-0"><i class="fas fa-exclamation-circle"></i> NOTE:  Please make sure to select the respective window number you are assigned, to prevent window number confusion.</p>
                </div>

                <br>
                <br>
                <div class="mb-5 name">
                    <input type="hidden" id="departmentid" value="">
                    <label for="windows" class="form-label">Window Number</label>
                    <select class="form-control" id="windows"></select>
                    <div class='validation-windownum hidden ml-2 mt-2' style='color:red;margin-bottom: 20px;'>No Windows available. Please contact your system admin.</div>
                </div>
                <br>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-success window_asign">Assign</button>
            </div>
        </div>
        </div>
    </div>

    <!-- Wait List Modal -->
    <div class="modal fade" id="waitlist" tabindex="-1" role="dialog" aria-labelledby="waitlistLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="waitlistLabel">Waiting List</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <div class="bg-primary p-2" style="border-radius: 5px">
                    <p class="m-0"><i class="fas fa-exclamation-circle"></i> NOTE:  This is the list of all queues that got marked as pending.</p>
                </div>
                <br>
                
                <div class="row mt-2">
                    <div class="col-md-12" style="font-size:.9rem !important">
                        <table class="table-hover table table-striped table-sm table-bordered table-head-fixed nowrap display compact" id="waitlist_table" width="100%" >
                            <thead>
                                <tr>
                                    <th width="70%">Name</th>
                                    <th width="20%" class="text-center">Queue</th>
                                    <th width="10%" class="align-middle"></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

                <br>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
        </div>
    </div>
    

    <div class="floating_button">
        <img src="\assets\icons\collapse-arrow-30.png" alt="">
    </div>

    <div class="box">
        <a class="item window_config bg-warning"><img src="\assets\icons\tv-24.png" alt=""></a>
        <a class="item annouce bg-danger"><img src="\assets\icons\commercial-24.png" alt=""></a>
        <a class="item nextque bg-success"><img src="\assets\icons\arrow-24.png" alt=""></a>
        <a class="item waitlist bg-primary"><img src="\assets\icons\user-24.png" alt=""></a>
    </div>

    <div class="box2 bg-info">
        <div class="currentque" style="flex-basis: 60%">
            <a id="studname">Not Current Serving</a>
        </div>
        <div class="currentque" style="flex-basis: 40%">
            <a id="studid">Not Serving</a>
        </div>
        
    </div>
</div>


@section('qab_sript')
    <script>

        var window_g = {};
        var waitlist = null;
        var currentservingName = null;
        var currentservingQueunum = null;
        var assignedwindow = null;
        var isSetupEmpty = false;
        var isServing = false;

        $(document).ready(function(){

            // FLOATING BUYTTON FUNCTION

                onLoad();
                
                // Floating Action Button Function
                $(document).on('click', '.floating_button', function() {  
                    
                    document.querySelector('.box').classList.toggle('box-active');
                    document.querySelector('.box2').classList.toggle('box2-active');

                    document.querySelector('.floating_button').classList.toggle('floating_button-active');
                });

                // Window Config Function
                $(document).on('click', '.window_config', function(){

                    $('#selectWindow').modal();

                });

                // Annouce Que Function
                $(document).on('click', '.annouce', function () {

                    if(isSetupEmpty == true){

                        Swal.fire({
                            type: "warning",
                            title:  "No Queuing Setup Available!",
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                        });
                    }

                    if(assignedwindow == 0){

                        Swal.fire({
                            type: "warning",
                            title:  "No window is assigned to this user!",
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                        });

                    }else{

                        $.ajax({
                        type:'GET',
                        url: '{{ route("annouce.next.que") }}',
                        data: {
                            window_number: assignedwindow,
                        },
                        success:function(res) {

                            console.log(res);

                            if(res[0].status == 401){

                                Swal.fire({
                                    type: res[0].statusCode,
                                    title:  res[0].message,
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000,
                                });
                            }

                            $.ajax({
                                type:'GET',
                                url: 'http://queuing.ck/queuing/announce-que',
                                data: {
                                    studname: res[0].data.studname,
                                    quenumber: res[0].data.que_number,
                                    window: res[0].window,
                                    department: res[0].data.department,
                                    prevque: currentservingQueunum,
                                },
                            });

                        }
                    });
                    }


                
                });

                // Mark Done Function
                $(document).on('click', '.btn_done', function () {
                
                    next_que_done(1);
                    Swal.close()
                });

                // Mark Deny Function
                $(document).on('click', '.btn_deny', function () {
                
                    next_que_done(2);
                    Swal.close()
                });
                
                // Cancel Function
                $(document).on('click', '.btn_cancel', function () {

                    Swal.clickCancel();
                });
                
                // Next Que Function
                $(document).on('click', '.nextque', function () {

                    if(isSetupEmpty == true){

                        Swal.fire({
                            type: "warning",
                            title:  "No Queuing Setup Available!",
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                        });
                    }

                    if(assignedwindow == 0){

                        Swal.fire({
                            type: "warning",
                            title:  "No window is assigned to this user!",
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                        });

                    }else{

                        if(!isServing){

                            next_que_done(0);

                        }else{

                            Swal.fire({
                                title: 'Done or Pending?',
                                type: 'warning',
                                showConfirmButton: false,
                                html: '<div>'
                                        +'<h5>Indicate '+currentservingName+' as done?</h5>'
                                        +'<div class="d-flex justify-content-center mt-4">'
                                            +'<button type="button" class="swal2-styled btn-success btn-sm btn_done" style="padding: 0.3em 1em;font-size: 1.0625em; display: inline-block; border: 0; border-radius: 0.25em; color: #fff;">Done</button>'
                                            +'<button type="button" class="swal2-styled btn-danger btn-sm btn_deny" style="padding: 0.3em 1em;font-size: 1.0625em; display: inline-block; border: 0; border-radius: 0.25em; color: #fff;">Pending</button>'
                                            +'<button type="button" class="swal2-styled btn-secondary btn-sm btn_cancel" style="padding: 0.3em 1em;font-size: 1.0625em; display: inline-block; border: 0; border-radius: 0.25em; color: #fff;">Cancel</button>'
                                        +'</div>'
                                    +'</div>'
                            })

                        }
                    }

                });

                // Assign Window Function
                $(document).on('click', '.window_asign', function () {

                    let departmentid = $('#departmentid').val(); 
                    var windowid = $('#windows').val(); 

                    $.ajax({
                        type:'GET',
                        url: '{{ route("assign.window") }}',
                        data: {
                            departmentid: departmentid,
                            windowid: windowid,
                        },
                        success:function(data) {

                            Swal.fire({
                                type: data[0].statusCode,
                                title: data[0].message,
                                showConfirmButton: true,
                            })
                            assignedwindow = data[0].window;
                            get_windows()

                        }
                    });
                
                });

                // Get Waiting List Function
                $(document).on('click', '.waitlist', function () {

                    $('#waitlist').modal();
                    $.ajax({
                        type:'GET',
                        url: '{{ route("get.waitlist") }}',
                        data: {
                            windowid: assignedwindow,
                        },
                        success:function(data) {

                            waitlist = data;
                            load_waitlist()
                        }
                    });
                
                });

                // Waiting Mark Done Function
                $(document).on('click', '.mark_done', function () {

                    que_number = $(this).attr('data-id');

                    $.ajax({
                        type:'GET',
                        url: '{{ route("waitlist.markdone") }}',
                        data: {
                            que_number: que_number,
                            windowid: assignedwindow,
                        },
                        success:function(data) {

                            waitlist = data;
                            load_waitlist()
                        }
                    });
                
                });
                

                // Get all windows Function
                function get_windows(){

                    $('#windows').empty()
                    $('#windows').append('<option value="">Select Sections</option>')
                    $('#windows').append('<option value="0">Unassigned</option>')
                    $("#windows").select2({
                        data: window_g,
                        placeholder: "Select Sections",
                    })

                    if(assignedwindow != null){

                        $('#windows').val(assignedwindow).change()
                    }
                }

                function get_current_serving(){

                    $.ajax({
                        type:'GET',
                        url: '{{ route("get.current.serving") }}',
                        data:{
                            window_number: assignedwindow,
                        },
                        success:function(data) {
                            
                            if(data.length != 0){

                                currentservingName = data[0].studname;
                                currentservingQueunum = data[0].que_number;
                                isServing = true;  
                                
                                $('#studname').html(currentservingName)
                                $('#studid').html("SID:   "+data[0].sid)
                                
                            }else{

                                currentservingName = null;
                                currentservingQueunum = null;
                                isServing = false; 

                            }

                        }
                    });
                }

                function next_que_done(status){

                    console.log(currentservingQueunum);

                    $.ajax({
                        type:'GET',
                        url: '{{ route("get.next.que") }}',
                        data: {
                            window_number: assignedwindow,
                            doneQue: currentservingQueunum,
                            status: status,
                        },
                        success:function(res) {

                            if(res[0].status == 401){

                                Swal.fire({
                                    type: res[0].statusCode,
                                    title:  res[0].message,
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000,
                                });

                            }else if(res[0].status == 402){

                                $('#studname').html('Not Current Serving');
                                $('#studid').html('Not Current Serving');
                                $.ajax({
                                    type:'GET',
                                    url: 'http://queuing.ck/queuing/last-que',
                                    data: {
                                        quenumber: res[0].lastquenumber,
                                    },
                                    success:function() {
                                        
                                        get_current_serving();
                                    }
                                });

                            }else{

                                $.ajax({
                                    type:'GET',
                                    url: 'http://queuing.ck/queuing/next-que',
                                    data: {
                                        studname: res[0].data.studname,
                                        quenumber: res[0].data.que_number,
                                        window: res[0].window,
                                        department: res[0].data.department,
                                        prevque: currentservingQueunum,
                                    },
                                    success:function() {
                                        get_current_serving();

                                    }
                                });

                            }

                        }
                    });
                }

                function load_waitlist() {
                    
                    $("#waitlist_table").DataTable({
                        destroy: true,
                        data: waitlist,
                        lengthChange : false,
                        searching: false,
                        paging: false,
                        info: false,
                        columns: [
                                    { "data": "studname" },
                                    { "data": "que_number" },
                                    { "data": null },
                            ],
                        columnDefs: [
                            {
                                'targets': 1,
                                'orderable': false, 
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                    var quenumber = rowData.que_number;
                                    
                                    if(quenumber < 10){
                                        quenumber = "00"+quenumber;
                                    }else if(quenumber < 100){
                                        quenumber = "0"+quenumber;
                                    }else if(quenumber <= 1000){
                                        quenumber = quenumber;
                                    }

                                    var buttons = '<a style="cursor: pointer"  class="text-primary">'+quenumber+'</a>';
                                    $(td)[0].innerHTML =  buttons;
                                    $(td).addClass('text-center')
                                    

                                }
                            },

                            {
                                'targets': 2,
                                'orderable': false, 
                                'createdCell':  function (td, cellData, rowData, row, col) {

                                    var buttons = '<a href="javascript:void(0)" class="mark_done" data-id="'+rowData.que_number+'"><i class="fas fa-check-circle"></i></a>';
                                    $(td)[0].innerHTML =  buttons;
                                    $(td).addClass('text-center');
                                    $(td).addClass('align-middle');
                                }
                            },
                        ]
                            
                    });
                }

                function onLoad(){

                    $.ajax({
                        type:'GET',
                        url: '{{ route("on.load") }}',
                        success:function(data) {

                            if(data[0].status == 401){

                                Swal.fire({
                                    type: data[0].code,
                                    title:  data[0].message,
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000,
                                });

                                isSetupEmpty = true;

                            }else if(data[0].status == 200){

                                var window = data[0].windows;

                                if(window == null){

                                    $('.validation-windownum').removeClass('hidden');
                                    $('#windows').prop("disabled", true);
                                }

                                if(data[0].isQueuingAvailable){
                                    $('#QAB').removeClass('hidden')
                                }

                                window_g = window;

                                assignedwindow = data[0].assigned_windowid;

                                $('#departmentid').val(data[0].department)

                                isSetupEmpty = false;

                                get_windows();
                                get_current_serving();

                            }
                        }
                    });


                }

            // FLOATING BUYTTON FUNCTION
        });

    </script>
@endsection