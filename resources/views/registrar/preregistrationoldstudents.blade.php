<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Pre-registation</title>

    <link href="{{asset('assets/css/gijgo.min.css')}}" rel="stylesheet" />

    <link href="{{asset('assets/css/main.css')}}" rel="stylesheet">

    <script type="text/javascript" src="{{asset('assets/scripts/jquery-3.3.1.min.js')}}"></script>

    <script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>

    <script src="{{asset('assets/scripts/gijgo.min.js')}}" ></script>

    <script src="{{asset('assets/scripts/bootstrap.min.js')}}" ></script>

    <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">

    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">

    <style>
        label{ font-size: 12px; }

        .chevron { display: inline-block; min-width: 150px; text-align: center; padding: 15px 0; margin-right: -30px; /* background: #9e5b5b  ; */ background: rgb(158,91,91); background: linear-gradient(90deg, rgba(158,91,91,1) 10%, rgba(241,168,168,1) 100%); -webkit-clip-path: polygon(0 0, 100% 0%, 75% 100%, 0% 100%); clip-path: polygon(0 0, 100% 0%, 75% 100%, 0% 100%); }

        .fixed-top{ position: sticky; padding-top: 0px; }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button { /* display: none; <- Crashes Chrome on hover */ -webkit-appearance: none; margin: 0; /* <-- Apparently some margin are still there even though it's hidden */ }

        input[type=number] { -moz-appearance:textfield; /* Firefox */ }

        .card-header{ background: rgb(158,91,91); background: linear-gradient(90deg, rgba(158,91,91,1) 10%, rgba(241,168,168,1) 100%); }

        @media screen and (max-width: 1000px) {
            h1{ font-size: 20px !important; }

            .fixed-top{ position: relative; }

            .chevron { display: inline-block;  min-width: 150px; text-align: center; padding:0px; margin: 0px !important; margin-right: -30px; -webkit-clip-path: polygon(0 0, 100% 0%, 100% 100%, 0% 100%); clip-path: polygon(0 0, 100% 0%, 100% 100%, 0% 100%); }

            .next { display: inline-block; min-width: 150px; text-align: center; padding: 15px 0; margin: 0px !important; margin-right: -30px; -webkit-clip-path: polygon(0 0, 100% 0%, 100% 100%, 0% 100%); clip-path: polygon(0 0, 100% 0%, 100% 100%, 0% 100%); }
        }

         /* Style the form */
         #regForm {
        background-color: #ffffff;
        /* margin: 100px auto; */
        /* padding: 40px; */
        width: 100%;
        min-width: 300px;
        }

        /* Style the input fields */
        input {
        padding: 10px;
        width: 100%;
        font-size: 17px;
        font-family: Raleway;
        border: 1px solid #aaaaaa;
        }

        /* Mark input boxes that gets an error on validation: */
        input.invalid {
        background-color: #ffdddd;
        }

        /* Hide all steps by default: */
        .tab {
        display: none;
        }

        /* Make circles that indicate the steps of the form: */
        .step {
        height: 15px;
        width: 15px;
        margin: 0 2px;
        background-color: #bbbbbb;
        border: none;
        border-radius: 50%;
        display: inline-block;
        opacity: 0.5;
        }

        /* Mark the active step: */
        .step.active {
        opacity: 1;
        }

        /* Mark the steps that are finished and valid: */
        .step.finish {
        background-color: #4CAF50;
        } 
    </style>
</head>
<body class="hold-transition lockscreen">
    <div class="container" style="background-color: #eee">
        <div class="app-main">
            <div class="app-main__outer">
                <div class="app-main__inner">
                        <div class="app-page-title fixed-top">
                            <div class="page-title-wrapper " style="background-color:#bb7272 ;">
                                <div class="chevron col-md-10 col-xs-10 tag-wrap" >
                                    <div class="page-title-heading " style="padding:30px">
                                        <div class="page-title-icon" style="color:black">
                                            <i class="fa fa-align-justify" >
                                            </i>
                                        </div>
                                        <div class="text-white">
                                            <h1>Pre-Enrollment for old students</h1>
                                            <div class="page-title-subheading">
                                                For queueing code recovery, please ask for assistance at the school's Registrar's Office.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                            <div class="alert fade show p-3" role="alert" id="review" style="border: 2px solid #bb7272">
                                <div class="row">
                                    <div class="col-md-12">
                                        <span style="font-size:25px;" class="headertext">Please fill in all the fields.</span>
                                        {{-- <button type="button" id="nextBtn"  class="btn-shadow btn btn-warning btn-outline-white btn-lg float-right " type="submit" >
                                            Next
                                        <button id="subButton" class="btn-shadow btn btn-warning btn-outline-white btn-lg float-right " type="submit" >
                                        <span class="btn-icon-wrapper pr-2 opacity-7">
                                            <i class="fa fa-upload"></i>
                                                Submit Form
                                        </span>
                                    </button> --}}
                                    </div>
                                </div>
                            </div>

                            <!-- One "tab" for each step in the form: -->
                                
                            @if(session()->has('message'))
                            <div class="alert alert-danger alert-dismissible col-12">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h5><i class="icon fas fa-ban"></i> Alert!</h5>
                                {{ session()->get('message') }} already exists!
                            </div>
                            @endif
                                <form action="/storeprereg/{{Crypt::encrypt('oldstudent')}}" method="get">
                                    @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="main-card mb-3 card">
                                            <div class="card-header text-white" >
                                                Personal Information
                                            </div>
                                            <div class="card-body">
                                                <div class="form-row " id="alertmessage"></div>
                                                <div>
                                                    <div class="form-row">
                                                        <div class="col-md-4">
                                                            <div class="position-relative form-group ">
                                                                <label for="fname" >First Name</label>
                                                                <div class="input-group input-group-sm">
                                                                    <input name="fname" id="fname" type="text" class="form-control form-control-sm" required>
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
                                                                <div class="input-group input-group-sm">
                                                                    <input name="mname" id="mname" type="text" class="form-control form-control-sm">
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
                                                        <div class="col-md-4">
                                                            <div class="position-relative form-group">
                                                                <label for="lname" class="">Last Name</label>
                                                                <div class="input-group input-group-sm">
                                                                    <input name="lname" id="lname" type="text" class="form-control form-control-sm" required>
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
                                                            <div class="position-relative form-group"><label for="suffix" class="">Suffix</label><input name="suffix" id="suffix" type="text" class="form-control form-control-sm"></div>
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="col-md-4">
                                                            <div class="position-relative form-group">
                                                                <label for="dob" class="">Date of Birth</label>
                                                                <div class="input-group input-group-sm">
                                                                    <input name="dob" id="dob" type="date" class="form-control form-control-sm" min="1900-01-01" required>
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
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="col-md-4">
                                                            <div class="position-relative form-group">
                                                                <label for="dob" class="">Grade Level (Last attended)</label>
                                                                <select name="lastgradelevelid" class="form-control form-control-sm" disabled>
                                                                    <option value=""></option>
                                                                    @foreach ($gradelevels as $gradelevel)
                                                                        <option value="{{$gradelevel->id}}">{{$gradelevel->levelname}}</option>
                                                                        
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="col-md-12 payablescontainer">
                                                        </div>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </form>
                                
                            {{-- <div class="row">
                                <div class="col-md-12">
                                    <div class="main-card mb-3 card">
                                        <button type="button" class="btn btn-warning btn-block" id="previewValidate"><h2>Save</h2></button>
                                    </div>
                                </div>
                            </div> --}}
                            {{-- <div style="overflow:auto;">
                                <div style="float:right;">
                                    <button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
                                    <button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>
                                </div>
                            </div> --}}
                            
                            <!-- Circles which indicates the steps of the form: -->
                            {{-- <div style="text-align:center;margin-top:40px;">
                                <span class="step"></span>
                                <span class="step"></span>
                                <span class="step"></span>
                            </div> --}}
                        </div>

                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="{{asset('assets/scripts/main.js')}}"></script>
    <script src="{{asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
    <script>
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
        $(document).on('change','select[name=lastgradelevelid]', function(){
            
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
                // headers: { 'X-CSRF-TOKEN': token },
                success:function(data) {
                    if(data =='0' ){
                        $('#alertmessage').empty();
                        $('#alertmessage').append(
                            '<div class="alert alert-danger alert-dismissible col-12">'+
                                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'+
                                '<h5><i class="icon fas fa-ban"></i> No records shown!</h5>'+
                            '</div>'
                        )
                    }else{
                        
                        console.log(data);
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
                        $('.payablescontainer').append(
                            '<button type="submit" class="btn btn-block btn-md proceed text-white" style="background-color: #bb7272"><h3>Next</h3></button>'
                        );
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
                }
            })
        })
        var answeredquestions = 0
        $(document).on('click','.examradiobuttons', function(){
            $('#nextBtn').attr('disabled',false)
        })
        
    $(document).ready(function () {
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
    }); 
    </script>
</body>
</html>
{{-- @endsection --}}
