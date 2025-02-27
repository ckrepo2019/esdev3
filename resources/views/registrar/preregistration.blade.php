

@extends('layouts.app')


@section('headerscript')
    {{-- <link href="{{asset('assets/css/gijgo.min.css')}}" rel="stylesheet" />

    <link href="{{asset('assets/css/main.css')}}" rel="stylesheet">

    <script type="text/javascript" src="{{asset('assets/scripts/jquery-3.3.1.min.js')}}"></script>

    <script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>

    <script src="{{asset('assets/scripts/gijgo.min.js')}}" ></script>

    <script src="{{asset('assets/scripts/bootstrap.min.js')}}" ></script>

    <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">--}}

    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}"> 
@endsection

@section('content')
    <section class="content ">
        <form action="/storeprereg/{{Crypt::encrypt('newstudent')}}" method="get" class="needs-validation" id="regform" >
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-4">
                    <div class="icheck-primary d-inline">
                        <input type="radio" id="newstudent" name="studentstatus" value="new" checked="" >
                        <label for="newstudent">
                            <h5>New Student</h5>
                        </label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="icheck-primary d-inline">
                        <input type="radio" id="transstudent" name="studentstatus" value="transferee" >
                        <label for="transstudent">
                            <h5>Transferee Student</h5>
                        </label>
                    </div>
                </div>
                {{-- <div class="col-md-4"> --}}
                    {{-- <div class="icheck-primary d-inline">
                        <input type="radio" id="oldstudent" name="studentstatus" value="old" >
                        <label for="oldstudent">
                            <h5>Old Student</h5>
                        </label>
                    </div> --}}
                {{-- </div> --}}
                <div class="col-md-2"></div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="main-card mb-3 card" >
                        <div class="card-header">
                         
                                <h1 class="card-title m-0 text-white col-md-10">PRE-REGISTRATION</h1>
                                {{-- <button type="button" class="float-right btn-danger btn col-md-2" id="cancel" >CANCEL </button> --}}
                          
                           
                        </div>
                        <div class="card-body">
                            <div class="form-row " id="alertmessage"></div>
                            <div id="formfields">
                                <div class="form-row">
                                    <div class="col-md-4">
                                        <div class="position-relative form-group">
                                            <label for="fname" >First Name</label>
                                            <div class="input-group ">
                                                <input name="fname" id="fname" type="text" class="form-control " required autocomplete="off" onkeyup="this.value = this.value.toUpperCase();">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="fa fa-exclamation-circle"></i>
                                                    </span>
                                                </div>
                                                <div class="invalid-feedback">
                                                    This section is required!
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="mname" class="">Middle Name</label>
                                            <div class="input-group ">
                                                <input name="mname" id="mname" type="text" class="form-control " autocomplete="off" onkeyup="this.value = this.value.toUpperCase();">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="position-relative form-group">
                                            <label for="lname" class="">Last Name</label>
                                            <div class="input-group ">
                                                <input name="lname" id="lname" type="text" class="form-control " required autocomplete="off" onkeyup="this.value = this.value.toUpperCase();">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="fa fa-exclamation-circle"></i>
                                                    </span>
                                                </div>
                                                <div class="invalid-feedback">
                                                    This section is required!
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="position-relative form-group"><label for="suffix" class="">Suffix</label><input name="suffix" id="suffix" type="text" class="form-control " autocomplete="off"�></div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-4">
                                        <div class="position-relative form-group">
                                            <label for="dob" class="">Date of Birth</label>
                                            <div class="input-group ">
                                                <input name="dob" id="dob" type="date" class="form-control " min="1900-01-01" required autocomplete="off">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="fa fa-exclamation-circle"></i>
                                                    </span>
                                                </div>
                                                <div class="invalid-feedback">
                                                    This section is required!
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group"><label for="gender" class="">Gender</label>
                                            <div class="input-group ">
                                                <select name="gender" id="gender"  class="form-control " required>
                                                    <option value=""></option>
                                                    <option value="FEMALE">Female</option>
                                                    <option value="MALE">Male</option>
                                                </select>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="fa fa-exclamation-circle"></i>
                                                    </span>
                                                </div>
                                                <div class="invalid-feedback">
                                                    This section is required!
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="position-relative form-group"><label for="stud_contact_no" class="">Contact No</label>
                                            <div class="input-group ">
                                                <input name="student_contact_no" type="text" id="stud_contact_num"  class="form-control " minlength="13" maxlength="13" data-inputmask-clearmaskonlostfocus="true" required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="fa fa-exclamation-circle"></i>
                                                    </span>
                                                </div>
                                                <div class="invalid-feedback">
                                                    Your contact number is not valid.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-4">
                                        <label>Select Grade Level</label>
                                        <select class="form-control " name="gradelevel" id="gradelevel" >
                                                <option></option>
                                            @foreach($gradelevels as $gradelevel)
                                                <option value="{{$gradelevel->id}}">{{$gradelevel->levelname}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-5">

                                    </div>
                                    <div class="col-md-3 pt-4">
                                        
                                    </div>
                                </div>
                            </div>
                                <hr>
                                <div class="form-group payablescontainer pt-3">
                                </div>
                                <div class="form-group examcontainer pt-3">
                                </div>
                                
                                <button type="button" class="btn btn-lg float-right"  id="previewValidate" style="background-color: #88b14b; color: #fff"><strong>SUBMIT PRE-REGISTRATION!</strong></button>
                                <button type="button" class="btn btn-lg  float-right"  id="olstudbuttonsubmit" style="background-color: #88b14b; color: #fff"><strong>SUBMIT</strong></button>

                        </div>
                    </div>
                </div>
            </div>
    </section>
   
@endsection

@section('footerscript')
                
    {{-- <script type="text/javascript" src="{{asset('assets/scripts/main.js')}}"></script> --}}
    <script src="{{asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
    <script src="{{asset('plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>
    <script>

        
    $('#previewValidate').hide();
        
        $(document).ready(function(){
            
            $('#olstudbuttonsubmit').hide();
            $("#stud_contact_num").inputmask({mask: "9999-999-9999"});

            // $(document).on('click','#cancel',function(){

            //     const swalWithBootstrapButtons = Swal.mixin({
            //         customClass: {
            //                 confirmButton: 'btn-block btn btn-success',
            //                 cancelButton: 'btn-block btn btn-danger'
            //                 },
            //                 buttonsStyling: false
            //         })
            //     swalWithBootstrapButtons.fire({
            //         text: 'Are you sure want to pre-registration?',
            //         // text: "You won't be able to revert this!",
            //         type: 'info',
            //         showCancelButton: true,
            //         confirmButtonColor: '#3085d6',
            //         cancelButtonColor: '#d33',
            //         confirmButtonText: 'Yes, cancel receipt pre-registration!',
            //         cancelButtonText: 'No, continue receipt pre-registration!!',
            //     }).then((result) => {
            //     if (result.value) {
            //         window.setTimeout(function () { 
            //                 window.location.replace('{{Request::root()}}'+'/login');
            //         }, 0); 
            //     }
            //     })
            // })

        });

        (function($) {
            $.fn.inputFilter = function(inputFilter) {
                return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function() {
                if (inputFilter(this.value)) {
                    this.oldValue = this.value;
                    this.oldSelectionStart = this.selectionStart;
                    this.oldSelectionEnd = this.selectionEnd;
                } else if (this.hasOwnProperty("oldValue")) {
                    this.value = this.oldValue;
                    this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
                } else {
                    this.value = "";
                }
                });
            };
        }(jQuery));
        $(document).ready(function() {
            // $('#review').hide();
            // $("#stud_contact_num").inputFilter(function(value) {
            //     return /^\d*$/.test(value);    // Allow digits only, using a RegExp
            // });
            dob.max = new Date().toISOString().split("T")[0];
        });

        function refreshPage(){
            window.location.reload();
        }

        var activeRadio = 0;

        $('input[name="studentstatus"]').each(function(){

            if($(this).attr('checked') == 'checked'){
       
                activeRadio = $(this)

            }

        })
        
        console.log(activeRadio)

        $(document).on('click','input[name="studentstatus"]', function(){

            var inputsavailable = false
            var proceed = false;

            var allInputs = $( "#regform input" );
        
            allInputs.each(function(){
               
                if($(this).attr('name') != "studentstatus"){
                   
                    if($(this).val() != null){

                        inputsavailable = true

                    }
                }

            })

            console.log(inputsavailable)

            if(inputsavailable){

                Swal.fire({
                    title: 'Are you sure want to change form?',
                    text: "Input fields will be cleared!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, proceed!'
                }).then((result) => {
                    if (result.value) {

                        proceed = true;

                        $(this).prop("checked", true)
                        activeRadio = $(this)

                        $('#formfields').empty();
                        $('.payablescontainer').empty();
                        $('#previewValidate').hide();
                        $('#regform').removeClass('was-validated');

                        if( ( $(this).val() == 'new' || $(this).val() == 'transferee' )){
                            $('#formfields').append(
                                '<div class="form-row">'+
                                    '<div class="col-md-4">'+
                                        '<div class="position-relative form-group ">'+
                                            '<label for="fname" >First Name</label>'+
                                            '<div class="input-group ">'+
                                                '<input name="fname" id="fname" type="text" class="form-control " required autocomplete="off" onkeyup="this.value = this.value.toUpperCase();">'+
                                                '<div class="input-group-append">'+
                                                    '<span class="input-group-text">'+
                                                        '<i class="fa fa-exclamation-circle"></i>'+
                                                    '</span>'+
                                                '</div>'+
                                                '<div class="invalid-feedback">'+
                                                    'This section is required!'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="col-md-3">'+
                                        '<div class="position-relative form-group">'+
                                            '<label for="mname" class="">Middle Name</label>'+
                                            '<div class="input-group ">'+
                                                '<input name="mname" id="mname" type="text" class="form-control " autocomplete="off" onkeyup="this.value = this.value.toUpperCase();">'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="col-md-4">'+
                                        '<div class="position-relative form-group">'+
                                            '<label for="lname" class="">Last Name</label>'+
                                            '<div class="input-group ">'+
                                                '<input name="lname" id="lname" type="text" class="form-control " required autocomplete="off" onkeyup="this.value = this.value.toUpperCase();">'+
                                                '<div class="input-group-append">'+
                                                    '<span class="input-group-text">'+
                                                        '<i class="fa fa-exclamation-circle"></i>'+
                                                    '</span>'+
                                                '</div>'+
                                                '<div class="invalid-feedback">'+
                                                    'This section is required!'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="col-md-1">'+
                                        '<div class="position-relative form-group"><label for="suffix" class="">Suffix</label><input name="suffix" id="suffix" type="text" class="form-control " autocomplete="off"></div>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="form-row">'+
                                    '<div class="col-md-4">'+
                                        '<div class="position-relative form-group">'+
                                            '<label for="dob" class="">Date of Birth</label>'+
                                            '<div class="input-group ">'+
                                                '<input name="dob" id="dob" type="date" class="form-control " min="1900-01-01" required autocomplete="off">'+
                                                '<div class="input-group-append">'+
                                                    '<span class="input-group-text">'+
                                                        '<i class="fa fa-exclamation-circle"></i>'+
                                                    '</span>'+
                                                '</div>'+
                                                '<div class="invalid-feedback">'+
                                                    'This section is required!'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="col-md-3">'+
                                        '<div class="position-relative form-group"><label for="gender" class="">Gender</label>'+
                                            '<div class="input-group ">'+
                                                '<select name="gender" id="gender"  class="form-control " required>'+
                                                    '<option value=""></option>'+
                                                    '<option value="FEMALE">Female</option>'+
                                                    '<option value="MALE">Male</option>'+
                                                '</select>'+
                                                '<div class="input-group-append">'+
                                                    '<span class="input-group-text">'+
                                                        '<i class="fa fa-exclamation-circle"></i>'+
                                                    '</span>'+
                                                '</div>'+
                                                '<div class="invalid-feedback">'+
                                                    'This section is required!'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="col-md-5">'+
                                        '<div class="position-relative form-group"><label for="stud_contact_no" class="">Contact No</label>'+
                                            '<div class="input-group ">'+
                                                '<input name="student_contact_no" type="text" id="stud_contact_num"  class="form-control " minlength="13" maxlength="13" data-inputmask-clearmaskonlostfocus="true" required autocomplete="off">'+
                                                '<div class="input-group-append">'+
                                                    '<span class="input-group-text">'+
                                                        '<i class="fa fa-exclamation-circle"></i>'+
                                                    '</span>'+
                                                '</div>'+
                                                '<div class="invalid-feedback">'+
                                                    'This section is required!'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="form-row">'+
                                    '<div class="col-md-4">'+
                                        '<label>Select Grade Level</label>'+
                                        '<select class="form-control " name="gradelevel" id="gradelevel" >'+
                                                '<option></option>'+
                                            @foreach($gradelevels as $gradelevel)
                                                '<option value="{{$gradelevel->id}}">{{$gradelevel->levelname}}</option>'+
                                            @endforeach
                                        '</select>'+
                                    '</div>'+
                                    '<div class="col-md-5">'+
                                    '</div>'+
                                   
                                '</div>'
                                );
                        $("#stud_contact_num").inputmask({mask: "9999-999-9999"});

                        }
                        else if( ( $(this).val() == 'old' )){
                            $('#formfields').append(
                                '<div class="form-row " id="alertmessage"></div>'+
                                    '<div class="form-row">'+
                                        '<div class="col-md-4">'+
                                            '<div class="position-relative form-group ">'+
                                                '<label for="fname" >First Name</label>'+
                                                '<div class="input-group ">'+
                                                    '<input name="fname" id="fname" type="text" class="form-control " required autocomplete="off">'+
                                                    '<div class="input-group-append">'+
                                                        '<span class="input-group-text">'+
                                                            '<i class="fa fa-exclamation-circle"></i>'+
                                                        '</span>'+
                                                    '</div>'+
                                                '<div class="invalid-feedback">'+
                                                    'This section is required!'+
                                                '</div>'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                        '<div class="col-md-3">'+
                                            '<div class="position-relative form-group">'+
                                                '<label for="mname" class="">Middle Name</label>'+
                                                '<div class="input-group ">'+
                                                    '<input name="mname" id="mname" type="text" class="form-control " autocomplete="off">'+
                                                    '<div class="input-group-append">'+
                                                        '<span class="input-group-text">'+
                                                            '<i class="fa fa-exclamation-circle"></i>'+
                                                        '</span>'+
                                                    '</div>'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                        '<div class="col-md-4">'+
                                            '<div class="position-relative form-group">'+
                                                '<label for="lname" class="">Last Name</label>'+
                                                '<div class="input-group ">'+
                                                    '<input name="lname" id="lname" type="text" class="form-control " required autocomplete="off">'+
                                                    '<div class="input-group-append">'+
                                                        '<span class="input-group-text">'+
                                                            '<i class="fa fa-exclamation-circle"></i>'+
                                                        '</span>'+
                                                    '</div>'+
                                                '<div class="invalid-feedback">'+
                                                    'This section is required!'+
                                                '</div>'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                        '<div class="col-md-1">'+
                                            '<div class="position-relative form-group"><label for="suffix" class="">Suffix</label><input name="suffix" id="suffix" type="text" class="form-control " autocomplete="off"></div>'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="form-row">'+
                                        '<div class="col-md-4">'+
                                            '<div class="position-relative form-group">'+
                                                '<label for="dob" class="">Date of Birth</label>'+
                                                '<div class="input-group ">'+
                                                    '<input name="dob" id="dob" type="date" class="form-control " min="1900-01-01" required autocomplete="off">'+
                                                    '<div class="input-group-append">'+
                                                        '<span class="input-group-text">'+
                                                            '<i class="fa fa-exclamation-circle"></i>'+
                                                        '</span>'+
                                                    '</div>'+
                                                '<div class="invalid-feedback">'+
                                                    'This section is required!'+
                                                '</div>'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="form-row">'+
                                        '<div class="col-md-4">'+
                                            '<div class="position-relative form-group">'+
                                                '<label for="dob" class="">Grade Level (Last attended)</label>'+
                                                '<select name="lastgradelevelid" class="form-control " id="lastgradelevelid" disabled>'+
                                                    '<option value=""></option>'+
                                                    @foreach ($gradelevels as $gradelevel)
                                                        '<option value="{{$gradelevel->id}}">{{$gradelevel->levelname}}</option>'+
                                                        
                                                    @endforeach
                                                '</select>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'
                            );

                            var fnameverified = 0;
                            var mnameverified = 0;
                            var lnameverified = 0;
                            var dobverified = 0;

                            $(document).on('click','input[name=fname]', function(){
                                fnameverified+=1;
                                if(fnameverified > 0 && mnameverified > 0 && lnameverified > 0 && dobverified > 0){
                                    $('select[name=lastgradelevelid]').attr('disabled',false);
                                }
                            })
                            $(document).on('click','input[name=mname]', function(){
                                mnameverified+=1;
                                if(fnameverified > 0 && mnameverified > 0 && lnameverified > 0 && dobverified > 0){
                                    $('select[name=lastgradelevelid]').attr('disabled',false);
                                }
                            })
                            $(document).on('click','input[name=lname]', function(){
                                lnameverified+=1;
                                if(fnameverified > 0 && mnameverified > 0 && lnameverified > 0 && dobverified > 0){
                                    $('select[name=lastgradelevelid]').attr('disabled',false);
                                }
                            })
                            $(document).on('click','input[name=dob]', function(){
                                console.log('asds')
                                dobverified+=1;
                                if(fnameverified > 0 && mnameverified > 0 && lnameverified > 0 && dobverified > 0){
                                    $('select[name=lastgradelevelid]').attr('disabled',false);
                                }
                            })
                            $("#stud_contact_num").inputmask({mask: "9999-999-9999"});
                        }

                    }
                    else{
                        activeRadio.prop("checked", true)
                    }
                })
            }
            
           

          
               
            
        })

        $(document).on('change','#gradelevel', function(){

            $('.payablescontainer').empty();
            $('.examcontainer').empty();

            // $('#previewValidate').text('TAKE QUALIFYING TEST NOW!')
            $('#previewValidate').attr('type','submit')

            $('#previewValidate').attr('disabled',false)

            var selectedgradeleveltext = $(this)[0].selectedOptions[0].text;
            $.ajax({
                url: '/getpayables/{{Crypt::encrypt('newstudent')}}',
                type:"GET",
                dataType:"json",
                data:{
                    gradelevel: $(this).val()
                },
                // headers: { 'X-CSRF-TOKEN': token },
                success:function(data) {
                    if(data =='0' ){
                        $('#alertmessage').empty();
                        $('#alertmessage').append(
                            '<div class="alert alert-danger alert-dismissible col-12">'+
                                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">�</button>'+
                                '<h5><i class="icon fas fa-ban"></i> No records shown!</h5>'+
                            '</div>'
                        )
                    }else{
                        $('input[name="selectsession"]').val('selected')
                        $('#nextBtn').attr('disabled',false)
                        $('.payablescontainer').empty();
                        $('.payablescontainer').append(
                            '<table class="table table-bordered text-uppercase">'+
                                '<thead id="theader">'+
                                    '<tr>'+
                                        '<th>Description</th>'+
                                        '<th>Amount</th>'+
                                    '</tr>'+
                                '</thead>'+
                                '<tbody id="payablescontainer">'+
                                '</tbody>'+
                            '</table>'
                        );
                            $('#theader').prepend(
                                '<tr>'+
                                    '<td>'+
                                        'Payment Schedule for '+selectedgradeleveltext+
                                    '</td>'+
                                    '<td>'+
                                        'SY {{$sy->sydesc}}'+
                                    '</td>'+
                                '</tr>' 
                            );
                        var totalpayment = 0;
                        $.each(data, function(key, value){
                            $('#payablescontainer').append(
                                '<tr>'+
                                    '<td>'+
                                        value.description+
                                    '</td>'+
                                    '<td>'+
                                        '&#8369;'+value.amount+
                                    '</td>'+
                                '</tr>' 
                            );
                            totalpayment+=value.amount;
                        });
                        $('#previewValidate').show();
                    }
                 
                }
            })
        });

        $(document).on('change','#lastgradelevelid', function(){

            $('.payablescontainer').empty();

            selectedgradeleveltext = $(this)[0].selectedOptions[0].text;

            $.ajax({
                url: '/getpayables/{{Crypt::encrypt('oldstudent')}}',
                type:"GET",
                dataType:"json",
                data:{
                    fname:              $('input[name=fname]').val(),
                    mname:              $('input[name=mname]').val(),
                    lname:              $('input[name=lname]').val(),
                    dob:                $('input[name=dob]').val(),
                    lastgradelevelid:   $(this).val()
                },
              
                success:function(data) {
                    if(data =='0' ){
                        $('#alertmessage').empty();
                        $('#alertmessage').append(
                            '<div class="alert alert-danger alert-dismissible col-12">'+
                                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">�</button>'+
                                '<h5><i class="icon fas fa-ban"></i> No records shown!</h5>'+
                            '</div>'
                        )
                    }
                    else if(data =='1' ){
                        $('#alertmessage').empty();
                        $('#alertmessage').append(
                            '<div class="alert alert-warning alert-dismissible col-12">'+
                                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">�</button>'+
                                '<h5><i class="icon fas fa-exclamation-triangle"></i> You already filed for this grade level!</h5>'+
                            '</div>'
                        )
                    }else{
                        
                        $('.payablescontainer').empty();
                        
                        $('.payablescontainer').append(
                            '<table class="table table-bordered text-center">'+
                                '<thead id="theader">'+
                                    '<tr>'+
                                        '<th>Description</th>'+
                                        '<th>Amount</th>'+
                                    '</tr>'+
                                '</thead>'+
                                '<tbody id="payablescontainer">'+
                                '</tbody>'+
                            '</table>'
                        );
                            $('#theader').prepend(
                                '<tr>'+
                                    '<th colspan="2">'+
                                        data[0][0].description+
                                    '</th>'+
                                '</tr>'
                            );
                        var totalpayment = 0;
                        $.each(data[1], function(key, value){
                            $('#payablescontainer').append(
                                '<tr>'+
                                    '<td>'+
                                        value.description+
                                    '</td>'+
                                    '<td>'+
                                        '&#8369;'+value.amount+
                                    '</td>'+
                                '</tr>'
                            );
                            totalpayment+=value.amount;
                        });
                        $('#olstudbuttonsubmit').show();
                    }
                    window.setTimeout(function () {
                        $(".alert-success").fadeTo(500, 0).slideUp(500, function () {
                            $(this).remove();
                        });
                    }, 5000);
                    window.setTimeout(function () {
                        $(".alert-danger").fadeTo(500, 0).slideUp(500, function () {
                            $(this).remove();
                        });
                    }, 5000);
                    window.setTimeout(function () {
                        $(".alert-warning").fadeTo(500, 0).slideUp(500, function () {
                            $(this).remove();
                        });
                    }, 5000);
                }
            })
        });
        var questioncount = 0;
        var questions = 0;
</script>           
                        
    
@endsection