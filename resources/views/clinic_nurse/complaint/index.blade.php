@php

    $check_refid = DB::table('usertype')->where('id', Session::get('currentPortal'))->select('refid')->first();

    if (Session::get('currentPortal') == 3) {
        $extend = 'registrar.layouts.app';
    } elseif (auth()->user()->type == 17) {
        $extend = 'superadmin.layouts.app2';
    } elseif (Session::get('currentPortal') == 2) {
        $extend = 'principalsportal.layouts.app2';
    } else {
        if ($check_refid->refid == 23) {
            // return view('clinic.index');
            $extend = 'clinic.layouts.app';
        } elseif ($check_refid->refid == 24) {
            // return view('clinic_nurse.index');
            $extend = 'clinic_nurse.layouts.app';
        } elseif ($check_refid->refid == 25) {
            // return view('clinic_doctor.index');
            $extend = 'clinic_doctor.layouts.app';
        } else {
            $extend = 'general.defaultportal.layouts.app';
        }
    }
@endphp

@extends($extend)

<!-- Select2 -->
{{-- <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}"> --}}

@section('content')
    <style>
        .select2-container .select2-selection--single {
            height: 40px !important;
        }

        .select2-selection__choice {
            font-size: 12px;
            /* Change the font size */
            background-color: #ffffff !important;
            /* Change the background color */
            color: black !important;
            border-radius: 5px;
            /* Add rounded corners */
            padding: 2px 8px;
            /* Add some padding */
            margin-right: 5px;
            /* Add some space between items */
        }

        .shadow {
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
            border: 0;
        }
    </style>

    @php
        use Carbon\Carbon;
        $now = Carbon::now();
        $comparedDate = $now->toDateString();
        date_default_timezone_set('Asia/Manila');
    @endphp

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 class="m-0">Complaints</h3>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Complaint</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <section class="content">
        <div class="container-fluid">
            <!-- Info boxes -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-2">
                                    <label>&nbsp;</label><br />
                                    <button type="button" class="btn btn-primary btn-block" id="btn-create"><i
                                            class="fa fa-plus"></i> Create</button>
                                </div>

                                <div class="col-md-4">
                                    <label>Date range</label>
                                    <input type="text" class="form-control float-right" id="reservation">
                                </div>
                                <div class="col-md-2">
                                    <label>&nbsp;</label><br />
                                    <button type="button" class="btn btn-primary btn-block" id="btn-generate"><i
                                            class="fa fa-sync"></i> Generate</button>
                                </div>
                                <div class="col-md-4 ">
                                    @php
                                        use App\Models\SchoolClinic\SchoolClinic;
                                        $users = SchoolClinic::users();
                                    @endphp
                                    <label>&nbsp;</label><br />
                                    <select class="form-control select2" id="select-user">
                                        <option>Select Student/Personnel</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->userid }}">{{ $user->name_showlast }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="results-container" class="row"></div>
        </div>
    </section>
    <div class="modal" id="modal-addcomplaint">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Complaint</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label>Complainant <span class="text-danger" id="type_holder"></span></label><br />
                            <select class="form-control select2" style="width: 100%;" id="addcomplaint-complainant">
                            </select>
                        </div>
                        <div class="col-md-7 mb-2">
                            <label>Beneficiary <span class="text-danger"></span></label><br />
                            <input type="text" class="form-control" id="addcomplaint-benefeciary"
                                placeholder="Leave blank if for self" />
                        </div>
                        <div class="col-md-5 mb-2" id="relationship_holder" style="display:none">
                            <label>Relationship <span class="text-danger"></span></label><br />
                            <select class="form-control select2" style="width: 100%;" id="addcomplaint-relationship">
                                <option>Select Relationship</option>
                                <option>Spouse</option>
                                <option>Child</option>
                                <option>Parent</option>
                                <option>Grandparent</option>
                                <option>Sibling</option>
                                <option>Niece/nephew</option>
                                <option>Cousin</option>
                                <option>Friend</option>
                                <option>Legal guardian</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>Complains <span class="text-danger">*</span></label><br />
                            <textarea class="form-control" id="addcomplaint-description"></textarea>
                        </div>

                        <div class="col-md-6">
                            <label>Date <span class="text-danger">*</span></label><br />
                            <input type="date" class="form-control" id="addcomplaint-date" value="{{ date('Y-m-d') }}" />
                        </div>
                        <div class="col-md-6">
                            <label>Time <span class="text-danger">*</span></label><br />
                            <input type="time" class="form-control" id="addcomplaint-time" value="{{ date('H:i') }}" />
                        </div>
                        <div class="col-md-12 mt-2">
                            <label>Action Taken <span class="text-danger"></span></label><br />
                            <textarea class="form-control" id="addcomplaint-actiontaken"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal"
                        id="btn-close-addcomplaint">Close</button>
                    <button type="button" class="btn btn-primary" id="btn-submit-addcomplaint">Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="modal" id="modal-editcomplaint">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Complaint</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label>Complainant <span class="text-danger">*</span></label><br />
                            <select class="form-control select2" style="width: 100%;" id="editcomplaint-complainant">
                            </select>
                            {{-- <input type="text" class="form-control" placeholder="Add option" id="input--add-"/> --}}
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>Complains <span class="text-danger">*</span></label><br />
                            <textarea class="form-control" id="editcomplaint-description"></textarea>
                        </div>
                        <div class="coChangel-md-6">
                            <label>Date <span class="text-danger">*</span></label><br />
                            <input type="date" class="form-control" id="editcomplaint-date" />
                        </div>
                        <div class="col-md-6">
                            <label>Time <span class="text-danger">*</span></label><br />
                            <input type="time" class="form-control" id="editcomplaint-time" />
                        </div>
                        <div class="col-md-12 mt-2">
                            <label>Action Taken <span class="text-danger"></span></label><br />
                            <textarea class="form-control" id="editcomplaint-actiontaken"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal"
                        id="btn-close-editcomplaint">Close</button>
                    <button type="button" class="btn btn-primary" id="btn-submit-editcomplaint">Save Changes</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="modal" id="modal-addmedication">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Medication</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 mb-2">
                            <label>Drug name <span class="text-danger">*</span></label><br />
                            <select class="form-control select2" style="width: 100%;" id="addmedication-drugid">

                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label>Quantity <span class="text-danger">*</span></label><br />
                            <input type="number" class="form-control" id="addmedication-quantity" value="0" />
                        </div>
                        <div class="col-md-12 mt-2">
                            <label>Remarks</label><br />
                            <textarea class="form-control" id="addmedication-remarks"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal"
                        id="btn-close-addmedication">Close</button>
                    <button type="button" class="btn btn-primary" id="btn-submit-addmedication"><i
                            class="fa fa-share"></i> Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="modal" id="modal-editmedication">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Medication</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 mb-2">
                            <label>Drug name <span class="text-danger">*</span></label><br />
                            <select class="form-control select2" style="width: 100%;" id="editmedication-drugid">

                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label>Quantity <span class="text-danger">*</span></label><br />
                            <input type="number" class="form-control" id="editmedication-quantity" value="0" />
                        </div>
                        <div class="col-md-12 mt-2">
                            <label>Remarks</label><br />
                            <textarea class="form-control" id="editmedication-remarks"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-md-4 text-left">
                        <button type="button" class="btn btn-default" data-dismiss="modal"
                            id="btn-close-editmedication">Close</button>
                    </div>
                    <div class="col-md-8 text-right">
                        <button type="button" class="btn btn-danger" id="btn-delete-editmedication"><i
                                class="fa fa-trash"></i> Delete</button>
                        <button type="button" class="btn btn-primary" id="btn-submit-editmedication"><i
                                class="fa fa-edit"></i> Save Changes</button>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal" id="modal-viewPrescription">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Doctor Prescription</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        id="viewDescriptionclose">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <section class="content">
                        <div class="container-fluid">
                            <div class="card shadow">
                                <div class="card-body" style="font-size:.8rem !important">
                                    <div class="d-flex align-items-center">
                                        <p id = "doctorname1"><strong></strong></p><br />
                                        <button type="button" class="btn btn-info btn-sm"
                                            id="btn-approve-prescription"><i class="fa fa-check"
                                                aria-hidden="true">Approve</i></button>
                                    </div>
                                    <p id = "patientname"><strong></strong></p>
                                    <div class="d-flex align-items-center">

                                        <strong id = "status2">Status:&nbsp;</strong>
                                        <strong id = "status1"></span>
                                    </div>
                                    <p id = "date1">Date: </p>

                                    <table class="table table-sm table-striped table-bordered table-hovered table-hover "
                                        id="prescription_datatable">
                                        <thead>
                                            <tr>
                                                <th width="10%">#</th>
                                                <th width="30%"> Name</th>
                                                <th width="25%">Dosage</th>
                                                <th width="20%">Duration</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                    <h6 class="font-weight-bold">Advice Given: </h4>
                                        <p class="font-weight-normal" id = "advice1"></p>
                                        <h6 class="font-weight-bold">Follow up: </h4>
                                            <p class="font-weight-normal" id = "follow"></p>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="modal-footer justify-content-right">
                    <button type="button" class="btn btn-primary text-light" id="btn-export-prescription"><i
                            class="fa fa-download"></i> Download PDF</button>
                    <button type="button" class="btn btn-danger " id="btn-delete-prescriptiom"><i
                            class="fa fa-trash"></i> Delete Prescription</button>


                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="modal-addprescription">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Prescription</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        id = "addprescriptionclose">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 mb-2">
                            <label>Prescribed by: <span class="text-danger">*</span></label><br />
                            <select class="select2" style="width: 100%;" id="selectdoctor-docid">
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label>Duration(Days) <span class="text-danger">*</span></label><br />
                            <input type="number" class="form-control" id="addprescription-duration" />
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>Medicine Name <span class="text-danger">*</span></label><br />
                            <input type="text" class="form-control" id="addprescription-name" />

                        </div>
                        <div class="col-md-5 mb-2">
                            <label>Quantity <span class="text-danger">*</span></label><br />
                            <input type="number" class="form-control" id="addprescription-quantity" />
                        </div>
                        <div class="col-md-7 mb-2">
                            <label>Taken at: <span class="text-danger">*</span></label>
                            <input type="checkbox" id="taken_at" name="taken_at" value="Yes">
                            <label for="taken_at">After Meal</label>
                            <br />
                            <select class="form-control select2" multiple="multiple" id="select-dosage">
                                <option>Morning </option>
                                <option>Afternoon </option>
                                <option>Night</option>
                                <option>Evening </option>
                            </select>

                        </div>
                        <div class="col-md-5 mb-2">
                            <label>Follow up <span class="text-danger">*</span></label><br />
                            <input type="date" class="form-control" id="followup-date" value="{{ date('Y-m-d') }}" />
                        </div>

                        <div class="col-md-12 mt-2">
                            <label>Advice Given: </label><br />
                            <textarea class="form-control" id="addprescription-advice"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default text-right" data-dismiss="modal"
                        id="btn-close-addprescription">Close</button>
                    <button type="button" class="btn btn-primary " id="btn-submit-medicineadd"><i
                            class="fa fa-plus"></i> Add medicine</button>
                    <button type="button" class="btn btn-primary" id="btn-submit-addprescription"><i
                            class="fa fa-share"></i> Submit</button>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>

    <div class=" modal" id="modal-addmedprescription">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Medicine</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="addmedclose">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label>Duration(Days) <span class="text-danger">*</span></label><br />
                            <input type="number" class="form-control" id="addprescription-duration2" />
                        </div>
                        <div class="col-md-8 mb-2">
                            <label>Medicine Name <span class="text-danger">*</span></label><br />
                            <input type="text" class="form-control" id="addprescription-name2" />

                        </div>
                        <div class="col-md-5 mb-2">
                            <label>Quantity <span class="text-danger">*</span></label><br />
                            <input type="number" class="form-control" id="addprescription-quantity2" />
                        </div>
                        <div class="col-md-7 mb-2">
                            <label>Taken at: <span class="text-danger">*</span></label>
                            <input type="checkbox" id="taken_at" name="taken_at2" value="Yes">
                            <label for="taken_at">After Food</label>
                            <br />
                            <select class="form-control select2" multiple="multiple" id="select-dosage2">
                                <option>Morning </option>
                                <option>Afternoon </option>
                                <option>Night</option>
                                <option>Evening </option>
                            </select>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary " id="btn-proceed"> Proceed</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- /.modal-content -->
@endsection

@section('footerjavascript')
    <script>
        var a = {};
        var size = Object.keys(a).length
        var bolean = 0;
        var disabled;

        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2()
            $('.select').select2()

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })

            $('#reservation').daterangepicker()
        })
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });

        var medicines = [];
        var approvalcomplain;
        $(document).ready(function() {


            $(document).on('click', '#btn-export-prescription', function() {
                var complainid = approvalcomplain;
                window.open('/pdf/download?complainid=' + complainid, '_blank')
            })


            $(document).on('input', '#addcomplaint-benefeciary', function() {
                var status = $('#addcomplaint-relationship').data('id') ?? 0;

                var value = $(this).val();
                if (value.length > 0 && status == 1) {
                    document.getElementById("addcomplaint-relationship").disabled = false;
                } else {
                    document.getElementById("addcomplaint-relationship").disabled = true;
                }

            })


            $('#btn-create').on('click', function() {

                $('#addcomplaint-relationship').val($('#addcomplaint-relationship option:first').val())
                    .trigger('change');

                $('#modal-addcomplaint').modal('show')
                $.ajax({
                    url: '/clinic/complaints/getallusers',
                    type: 'GET',
                    success: function(data) {
                        $('#addcomplaint-complainant').empty()
                        $('#addcomplaint-complainant').append(data)
                        $('#addcomplaint-complainant').val($(
                            '#addcomplaint-complainant option:first').val()).trigger(
                            'change');
                    }
                })
            })

            getcomplaints($('#reservation').val())

            function getcomplaints(selecteddaterange) {
                console.log(selecteddaterange)
                // $('#select-user').val($('#select-user option:first').val()).trigger('change');
                Swal.fire({
                    title: 'Fetching data...',
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: false
                })

                $.ajax({
                    url: '/clinic/complaints/getcomplaints',
                    type: 'GET',
                    data: {
                        selecteddaterange: selecteddaterange
                    },
                    success: function(data) {
                        // console.log(data);

                        $('#results-container').empty()
                        $('#results-container').append(data)
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')
                    }
                })
            }

            function getcomplaints2(id) {
                Swal.fire({
                    title: 'Fetching data...',
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: false
                })

                $.ajax({
                    url: '/clinic/complaints/getusercomplaints',
                    type: 'GET',
                    data: {
                        id: id
                    },
                    success: function(data) {
                        $('#results-container').empty()
                        $('#results-container').append(data)
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')
                    }
                })
            }


            $(document).on('click', '#btn-download-prescription', function() {

                $.ajax({
                    url: '/pdf/download',
                    type: 'GET',
                    success: function(response) {

                        // Create a new download link with the PDF file as the href
                        var link = document.createElement('a');
                        link.href = response.file;
                        link.download = response.filename;


                        // Trigger the download by programmatically clicking the link
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });

            });

            var all_prescription = [];
            var hidden = false;
            $(document).on('click', '#btn-delete-prescriptiom', function() {
                id = approvalcomplain;
                $.ajax({
                    url: '/clinic/complaints/delpres',
                    type: 'GET',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        id: id
                    },
                    complete: function() {
                        Toast.fire({
                            type: 'success',
                            title: 'Prescription deleted successfully!'
                        })
                        $('#viewDescriptionclose').click();
                        getcomplaints($('#reservation').val())


                    }
                })


            })
            $(document).on('click', '#btn-approve-prescription', function() {
                id = approvalcomplain;
                var data1 = {{ $check_refid->refid }};

                if (data1 != 25) {
                    alert("Oppppsss Issue")
                } else {
                    $.ajax({
                        url: '/clinic/complaints/update',
                        type: 'GET',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            id: id
                        },
                        complete: function() {
                            Toast.fire({
                                type: 'success',
                                title: 'Status update successfully!'
                            })
                            $('#viewDescriptionclose').click();

                        }
                    })
                }
                window.location.reload();

                console.log('Approved!');
            })

            $(document).on('click', '.btn-complaint-viewprescription', function() {

                $('#modal-viewPrescription').modal('show');
                var complaintid = $(this).attr('data-id');
                approvalcomplain = complaintid;
                // Get the element
                var user = 'username' + complaintid
                const element = document.getElementById(user);

                const text = element.textContent;
                console.log('text', text);



                console.log(complaintid);
                $.ajax({
                    url: '/clinic/complaints/viewPres',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        complaintid: complaintid
                    },
                    success: function(data) {
                        all_prescription = data;
                        // console.log(all_prescription);
                        var date2 = new Date(Date.parse(data[0].createddatetime));
                        var date3 = new Date(Date.parse(data[0].followup));
                        var markdate = date2.toLocaleDateString("en-US", {
                            month: "long",
                            year: "numeric",
                            day: "numeric"
                        });
                        var markdate2 = date3.toLocaleDateString("en-US", {
                            month: "long",
                            year: "numeric",
                            day: "numeric"
                        });
                        // Get the element with id "doctorname1"
                        var doctorNameElement = document.getElementById("doctorname1");
                        var patientNameElement = document.getElementById("patientname");
                        var dateElement = document.getElementById("date1");
                        var adviceElement = document.getElementById("advice1");
                        var followupElement = document.getElementById("follow");
                        dateElement.innerHTML = "Date:" + " " + markdate;
                        doctorNameElement.innerHTML = 'Prescribed by: ' + '<strong>' + data[0]
                            .doctorname + '</strong>';
                        if (data[0].benefeciaryname != null || data[0].benefeciaryname == "") {
                            patientNameElement.innerHTML = "<strong>" + " Patient Name:" +
                                "</strong>" + '&nbsp;' + data[0].benefeciaryname + '&nbsp' +
                                "(Beneficiary of " + text.replace(/^\s+|\s+$/g, "") + ')';
                        } else {
                            patientNameElement.innerHTML = "<strong>" + "Patient name:" +
                                "</strong>" + '&nbsp;' + text.replace(/^\s+|\s+$/g, "");

                        }
                        followupElement.innerHTML = "<strong>" + "Date: " + markdate2 +
                            "</strong>";
                        var data1 = {{ $check_refid->refid }};
                        var data2 = {{ auth()->user()->id }};
                        var statuseElement = document.getElementById("status1");
                        if (data1 == 25) {
                            if (data[0].docid != data2) {
                                if (data[0].Approve == 0) {
                                    document.getElementById('btn-approve-prescription').style
                                        .visibility = 'hidden';
                                    statuseElement.innerHTML =
                                        '<span class= "badge badge-success">' +
                                        'Prescription Approved by the doctor' + '</span>';
                                } else {
                                    document.getElementById('btn-approve-prescription').style
                                        .visibility = 'hidden';
                                    statuseElement.innerHTML =
                                        '<span class= "badge badge-warning">' +
                                        'Pending Approval by the Doctor' + '</span>';
                                }
                            } else {
                                if (data[0].Approve == 0) {
                                    document.getElementById('btn-approve-prescription').style
                                        .visibility = 'hidden';
                                    statuseElement.innerHTML =
                                        '<span class= "badge badge-success">' +
                                        'Prescription Approved by the doctor' + '</span>';

                                } else {

                                    document.getElementById('btn-approve-prescription').style
                                        .visibility = 'visible';
                                    statuseElement.innerHTML =
                                        '<span class= "badge badge-warning">' +
                                        'Pending Approval by the Doctor' + '</span>';
                                }

                            }
                        }
                        if (data1 == 24) {
                            if (data[0].Approve == 0) {
                                document.getElementById('btn-approve-prescription').style
                                    .visibility = 'hidden';
                                document.getElementById("btn-export-prescription").style
                                    .visibility = 'visible';
                                statuseElement.innerHTML =
                                    '<span class= "badge badge-success">' +
                                    'Prescription Approved by the doctor' + '</span>';
                            } else {
                                statuseElement.innerHTML =
                                    '<span class= "badge badge-warning">' +
                                    'Pending Approval by the Doctor' + '</span>';
                                document.getElementById('btn-approve-prescription').style
                                    .visibility = 'hidden';
                                document.getElementById("btn-export-prescription").style
                                    .visibility = 'hidden';
                            }

                        }
                        // Update its innerHTML to a new value
                        // Get the element with id "doctorname1"
                        //document.getElementById('btn-approve-prescription').style.visibility = 'hidden';

                        adviceElement.innerHTML = "*" + data[0].advice;
                        console.log(data);
                        prescription_datatable();

                        //medicals_datatable();

                    }
                })
            })

            function prescription_datatable() {
                key = 0;
                $("#prescription_datatable").DataTable({
                    // destroy: true,
                    destroy: true,
                    responsive: true,
                    editable: true,
                    lengthChange: false,
                    stateSave: true,
                    searching: false,
                    // autoWidth: false,
                    data: all_prescription,

                    columns: [{
                            "data": null
                        },
                        {
                            "data": null
                        },
                        {
                            "data": null
                        },
                        {
                            "data": null
                        }

                    ],
                    columnDefs: [

                        {
                            'targets': 0,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td)[0].innerHTML = key += 1
                            }
                        },
                        {
                            'targets': 1,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td)[0].innerHTML = rowData.medicinename
                            }
                        },
                        {

                            'targets': 2,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td)[0].innerHTML = rowData.dosage + "&nbsp";
                            }
                        },
                        {
                            'targets': 3,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                if (rowData.duration == 1) {
                                    $(td)[0].innerHTML = rowData.duration + " " + "Day"
                                } else {
                                    $(td)[0].innerHTML = rowData.duration + " " + "Days" + "<br/>" +
                                        " Total Medicine:" + "&nbsp" + rowData.quantity
                                }
                            }
                        },

                    ],
                });
            }

            var valid = 1;
            $(document).on('click', '.btn-complaint-addprescription', function() {
                $('#select-dosage').val([]).trigger("change");
                var docid = {{ auth()->user()->id }};
                var data1 = {{ $check_refid->refid }};
                $('#viewDescriptionclose').click();
                $('#modal-viewPrescription').modal('hide');
                $('#modal-addprescription').modal('show')
                var complaintid = $(this).attr('data-id');
                $('#btn-submit-addprescription').attr('data-id', complaintid)
                for (var member in a) delete a[member];
                var modalBody = $('#modal-addprescription .modal-body');

                $.ajax({
                    url: '/clinic/complaints/getadoc',
                    type: 'GET',
                    success: function(data) {
                        $('#selectdoctor-docid').empty()
                        $('#selectdoctor-docid').append(data)

                        if (data1 == 25) {
                            $('#selectdoctor-docid option[value="' + docid + '"]').prop(
                                'selected', true);

                        }
                    }
                })
                $('#doctorname').val("");
                $('#addprescription-name').val("");
                $('#addprescription-advice').val("");
                $('#addprescription-dosage').val("");
                $('#addprescription-duration').val("");
                $('#addprescription-quantity').val("");

            })

            $('#select-dosage').on('change', function() {
                var select_dosage = ($('#select-dosage').val());
                var size1 = Object.keys(select_dosage).length;
                var duration = Number($('#addprescription-duration').val());
                var placeholder;
                var quantity1 = $('#addprescription-quantity').val();
                console.log(valid);

                if (quantity1.replace(/^\s+|\s+$/g, "").length == 0 || valid == 0) {
                    if (size1 == 1) {
                        placeholder = duration * size1
                        $('#addprescription-quantity').val(placeholder);
                        valid = 0;

                    }
                    if (size1 == 2) {

                        placeholder = duration * size1
                        $('#addprescription-quantity').val(placeholder);


                    }
                    if (size1 == 3) {
                        placeholder = duration * size1
                        $('#addprescription-quantity').val(placeholder);

                    }
                    if (size1 == 4) {
                        placeholder = duration * size1
                        $('#addprescription-quantity').val(placeholder);
                    }
                } else {
                    valid = 1;
                }
                //console.log(size1);

            })

            $('#select-dosage2').on('change', function() {
                var select_dosage = ($('#select-dosage2').val());
                var size1 = Object.keys(select_dosage).length;
                var duration = Number($('#addprescription-duration2').val());
                var placeholder;
                var quantity1 = $('#addprescription-quantity2').val();
                console.log(valid);

                if (quantity1.replace(/^\s+|\s+$/g, "").length == 0 || valid == 0) {
                    if (size1 == 1) {
                        placeholder = duration * size1
                        $('#addprescription-quantity2').val(placeholder);
                        valid = 0;

                    }
                    if (size1 == 2) {

                        placeholder = duration * size1
                        $('#addprescription-quantity2').val(placeholder);


                    }
                    if (size1 == 3) {
                        placeholder = duration * size1
                        $('#addprescription-quantity2').val(placeholder);

                    }
                    if (size1 == 4) {
                        placeholder = duration * size1
                        $('#addprescription-quantity2').val(placeholder);
                    }
                } else {
                    valid = 1;
                }
                //console.log(size1);

            })


            $('#addcomplaint-complainant').on('change', function() {

                console.log("Hello");
                var id = $('#addcomplaint-complainant').val();

                $.ajax({
                    type: 'GET',
                    url: '/clinic/complaints/getausertype',
                    data: {
                        id: id,
                    },
                    success: function(data) {
                        var type = data[0].utype;
                        if (type == "STUDENT") {
                            document.getElementById("addcomplaint-benefeciary").readOnly = true;
                            document.getElementById("addcomplaint-relationship").disabled =
                                true;
                            disabled = 0;
                            $('#type_holder').text('Student');
                            $('#relationship_holder').hide();

                        } else {

                            document.getElementById("addcomplaint-benefeciary").readOnly =
                                false;
                            document.getElementById("addcomplaint-relationship").setAttribute(
                                "data-id", 1);
                            disabled = 1;
                            $('#type_holder').text('Employee');
                            $('#relationship_holder').show();


                        }

                    }

                })

            })

            var a = {};
            $('#btn-submit-addprescription').on('click', function() {

                //complain ID
                var complaintid = $(this).attr('data-id');

                //Name of the selected Doctor Name
                name1 = jQuery("#selectdoctor-docid").find("option[value='" + jQuery("#selectdoctor-docid")
                    .val() + "']").text();

                //Doctor ID
                var e = document.getElementById("selectdoctor-docid");

                //Follow Up Date
                var adddate = $('#followup-date').val();

                //Long Advice
                var advice = $('#addprescription-advice').val();

                //Name of Medicine
                var name = $('#addprescription-name').val();

                //Contain Morning,Afternoon,and Night
                var select_dosage = ($('#select-dosage').val());
                console.log(select_dosage);
                //To determine how many times a day the medicine will be taken 
                var size1 = Object.keys(select_dosage).length;

                //Total Quantity of Medicine
                var quantity1 = $('#addprescription-quantity').val();
                var quantity = Number($('#addprescription-quantity').val());

                //Set Dosage as null
                var dosage = '';

                //Total number of days the medicine should be taken
                var duration = Number($('#addprescription-duration').val());
                var duration1 = $('#addprescription-duration').val();

                //to check of the medicine will be taken at before food of after. Check mean Afterfood
                var x = document.querySelector('#taken_at:checked') !== null;

                //TO determine how many times per meal the medicine should be taken
                var each = quantity / duration;

                //To round the amount taken to whole number if it was not 1/2
                if ((each / size1) == 0.5) {

                    var total = each / size1;
                } else {
                    var total = String(Math.round(each / size1));
                }
                var approval;
                //To check if the doctor is making the prescription
                var data1 = {{ $check_refid->refid }};
                console.log('DATA DATA...', data1);
                if (data1 == 24) {
                    console.log('Nurse!');
                    approval = 1;
                    console.log(approval);

                } else {
                    console.log('Doctor!');
                    approval = 0;
                    console.log(approval);
                }
                //ends here
                //Loop to make the dosage formatted to string
                for (var i = 0; i < size1; i++) {
                    //console.log(select_dosage[i]);

                    if (total == '0') {
                        dosage += 'Every';
                    } else {
                        dosage += total;
                    }
                    dosage += " ";
                    dosage += select_dosage[i];
                    if (i != size1 - 1) {
                        dosage += ",";
                    }

                }
                //Ends here

                if (x == true) {
                    dosage += "(After Food)";
                } else {
                    dosage += "(Before Food)";
                }

                size = Object.keys(a).length

                var checkvalidation = 0;
                if (quantity1.replace(/^\s+|\s+$/g, "").length == 0) {
                    checkvalidation = 1;
                    $('#addprescription-quantity').css('border', '1px solid red')
                } else {
                    $('#addprescription-quantity').removeAttr('style')
                }
                if (name.replace(/^\s+|\s+$/g, "").length == 0) {
                    checkvalidation = 1;
                    $('#addprescription-name').css('border', '1px solid red')
                } else {
                    $('#addprescription-name').removeAttr('style')
                }
                if (advice.replace(/^\s+|\s+$/g, "").length == 0) {
                    checkvalidation = 1;
                    $('#addprescription-advice').css('border', '1px solid red')
                } else {
                    $('#addprescription-advice').removeAttr('style')
                }
                if (select_dosage.length == 0) {
                    checkvalidation = 1;
                    $('.select2-search__field').css('border', '1px solid red')
                } else {
                    $('select2-search__field').removeAttr('style')
                }
                if (duration1.replace(/^\s+|\s+$/g, "").length == 0) {
                    checkvalidation = 1;
                    $('#addprescription-duration').css('border', '1px solid red')
                } else {
                    $('#addprescription-duration').removeAttr('style')
                }

                if (checkvalidation == 0) {

                    //removing border color
                    $('#addprescription-duration').removeAttr('style')
                    $('#addprescription-name').removeAttr('style')
                    $('#addprescription-dosage').removeAttr('style')
                    $('#addprescription-quantity').removeAttr('style')
                    $('#addprescription-advice').removeAttr('style')
                    //ends here
                    a[size + 1] = {
                        medicine: name,
                        duration: duration,
                        dosage: dosage,
                        quantity: quantity
                    }
                }

                if (checkvalidation == 0) {
                    //console.log(a);
                    for (var i = 1; i <= size + 1; i++) {
                        console.log(a[i].medicine);
                        $.ajax({
                            type: 'GET',
                            url: '/clinic/complaints/addPres',
                            data: {
                                complaintid: complaintid,
                                docid: e.value,
                                adddate: adddate,
                                doctor: name1,
                                medname: a[i].medicine,
                                dosage: a[i].dosage,
                                duration: a[i].duration,
                                quantity: a[i].quantity,
                                advice: advice,
                                approval: approval
                            },
                            success: function(data) {
                                if (data == 1) {
                                    $('#addprescriptionclose').click();
                                    Toast.fire({
                                        type: 'success',
                                        title: 'Added successfully!'
                                    })

                                    getcomplaints($('#reservation').val())

                                }

                            }

                        })
                    }
                    // window.location.reload();
                }


                // console.log(a);
            })

            $('#btn-submit-medicineadd').on('click', function() {
                $('#select-dosage2').val([]).trigger("change");
                $('#addprescription-dosage2').val("");
                $('#addprescription-duration2').val("");
                $('#addprescription-name2').val("");
                $('#addprescription-quantity2').val("");

                $('#modal-addmedprescription').modal({
                    backdrop: 'static',
                });

                $('#modal-addmedprescription').modal('show');

            })
            $('#btn-proceed').on('click', function() {


                //Name of Medicine
                var name2 = $('#addprescription-name2').val();

                //Contain Morning,Afternoon,and Night
                var select_dosage = ($('#select-dosage2').val());

                //To determine how many times a day the medicine will be taken 
                var size1 = Object.keys(select_dosage).length;

                //Total Quantity of Medicine
                var quantity = Number($('#addprescription-quantity2').val());

                //Set Dosage as null
                var dosage = '';

                //Total number of days the medicine should be taken
                var duration = Number($('#addprescription-duration2').val())

                //to check of the medicine will be taken at before food of after. Check mean Afterfood
                var x = document.querySelector('#taken_at2:checked') !== null;

                //TO determine how many times per meal the medicine should be taken
                var each = quantity / duration;

                //To round the amount taken to whole number if it was not 1/2
                if ((each / size1) == 0.5) {

                    var total = each / size1;
                } else {
                    var total = String(Math.round(each / size1));
                }
                //Loop to make the dosage formatted to string
                for (var i = 0; i < size1; i++) {
                    //console.log(select_dosage[i]);

                    if (total == '0') {
                        dosage += 'Every';
                    } else {
                        dosage += total;
                    }
                    dosage += " ";
                    dosage += select_dosage[i];
                    if (i != size1 - 1) {
                        dosage += ",";
                    }
                }
                //Ends here

                if (x == true) {
                    dosage += "(After Food)";
                } else {
                    dosage += "(Before Food)";
                }
                size = Object.keys(a).length

                a[size + 1] = {
                    medicine: name2,
                    duration: duration,
                    dosage: dosage,
                    quantity: quantity
                }
                console.log(a);

                $('#addmedclose').click();

            })

            $('#btn-submit-addcomplaint').on('click', function() {
                var checkvalidation = 0;
                var addcomplainant = $('#addcomplaint-complainant').val();
                var adddescription = $('#addcomplaint-description').val();
                var adddate = $('#addcomplaint-date').val();
                var addtime = $('#addcomplaint-time').val();
                var addactiontaken = $('#addcomplaint-actiontaken').val();
                var beneficiary = $('#addcomplaint-benefeciary').val();
                var relationship = $('#addcomplaint-relationship').val();
                var selectElement = document.getElementById("addcomplaint-relationship");


                if (disabled == 1) {
                    if (relationship == "Select Relationship" && beneficiary.replace(/^\s+|\s+$/g, "")
                        .length > 0) {
                        alert('Please Enter Relationship');
                        checkvalidation = 1;

                    } else {
                        checkvalidation = 0;

                    }
                }



                if (adddescription.replace(/^\s+|\s+$/g, "").length == 0) {
                    checkvalidation = 1;
                    $('#addcomplaint-description').css('border', '1px solid red')
                }
                if (adddate.replace(/^\s+|\s+$/g, "").length == 0) {
                    checkvalidation = 1;
                    $('#addcomplaint-date').css('border', '1px solid red')
                }
                if (addtime.replace(/^\s+|\s+$/g, "").length == 0) {
                    checkvalidation = 1;
                    $('#addcomplaint-time').css('border', '1px solid red')
                }
                if (checkvalidation == 0) {
                    $.ajax({
                        url: '/clinic/complaints/add',
                        type: 'GET',
                        dataType: 'json',
                        data: {
                            addcomplainant: addcomplainant,
                            adddescription: adddescription,
                            adddate: adddate,
                            addtime: addtime,
                            addactiontaken: addactiontaken,
                            beneficiary: beneficiary,
                            relationship: relationship

                        },
                        success: function(data) {
                            if (data == 0) {
                                Toast.fire({
                                    type: 'warning',
                                    title: 'Complainant for the selected date already exists!'
                                })
                            } else if (data == 1) {

                                Toast.fire({
                                    type: 'success',
                                    title: 'Added successfully!'
                                })
                                $('#modal-addcomplaint').find('input').val('')
                                $('#modal-addcomplaint').find('textarea').val('')
                                $('#btn-close-addcomplaint').click();
                                getcomplaints($('#reservation').val())
                            } else {
                                Toast.fire({
                                    type: 'error',
                                    title: 'Something went wrong!'
                                })
                            }
                        }
                    })
                } else {
                    Toast.fire({
                        type: 'warning',
                        title: 'Please fill in the required fields!'
                    })
                }
            })

            $('#btn-generate').on('click', function() {
                getcomplaints($('#reservation').val())
                bolean = 0;
            })
            var id = null;
            $('#select-user').on('change', function() {
                var userid = $(this).val();
                id = userid ? Number(userid) : null;
                console.log(userid, id);
                getcomplaints2(id);
                bolean = 1;
                console.log(id)
            })


            $(document).on('click', '.btn-complaint-edit', function() {
                var complaintid = $(this).attr('data-id');
                $('#btn-submit-editcomplaint').attr('data-id', complaintid)
                var userid;
                $('#modal-editcomplaint').modal('show')
                $.ajax({
                    url: '/clinic/complaints/getinfo',
                    type: 'GET',
                    dateType: 'json',
                    data: {
                        id: complaintid
                    },
                    success: function(data) {
                        userid = data.userid;
                        $('#editcomplaint-description').val(data.description)
                        $('#editcomplaint-date').val(data.cdate)
                        $('#editcomplaint-time').val(data.ctime)
                        $('#editcomplaint-actiontaken').val(data.actiontaken)
                    }
                })
                $.ajax({
                    url: '/clinic/complaints/getallusers',
                    type: 'GET',
                    success: function(data) {

                        $('#editcomplaint-complainant').empty()
                        $('#editcomplaint-complainant').append(data)
                        $('#editcomplaint-complainant option[value="' + userid + '"]').prop(
                            'selected', true)
                        // $('#editcomplaint-complainant').val(userid)
                    }
                })
            })

            $(document).on('click', '#btn-submit-editcomplaint', function() {
                var checkvalidation = 0;
                var complaintid = $(this).attr('data-id');
                var editcomplainant = $('#editcomplaint-complainant').val();
                var editdescription = $('#editcomplaint-description').val();
                var editdate = $('#editcomplaint-date').val();
                var edittime = $('#editcomplaint-time').val();
                var editactiontaken = $('#editcomplaint-actiontaken').val();
                if (editdescription.replace(/^\s+|\s+$/g, "").length == 0) {
                    checkvalidation = 1;
                    $('#editcomplaint-description').css('border', '1px solid red')
                }
                if (editdate.replace(/^\s+|\s+$/g, "").length == 0) {
                    checkvalidation = 1;
                    $('#editcomplaint-date').css('border', '1px solid red')
                }
                if (edittime.replace(/^\s+|\s+$/g, "").length == 0) {
                    checkvalidation = 1;
                    $('#editcomplaint-time').css('border', '1px solid red')
                }
                if (checkvalidation == 0) {
                    $.ajax({
                        url: '/clinic/complaints/edit',
                        type: 'GET',
                        dataType: 'json',
                        data: {
                            complaintid: complaintid,
                            editcomplainant: editcomplainant,
                            editdescription: editdescription,
                            editdate: editdate,
                            edittime: edittime,
                            editactiontaken: editactiontaken
                        },
                        success: function(data) {
                            if (data == 0) {
                                Toast.fire({
                                    type: 'warning',
                                    title: 'Complainant for the selected date already exists!'
                                })
                            } else if (data == 1) {

                                Toast.fire({
                                    type: 'success',
                                    title: 'Added successfully!'
                                })
                                $('#modal-addcomplaint input, textarea').val('')
                                $('#btn-close-editcomplaint').click();
                                if (bolean == 0) {
                                    getcomplaints($('#reservation').val())

                                } else {

                                    getcomplaints2(id);
                                    console.log(id);
                                }

                            } else {
                                Toast.fire({
                                    type: 'error',
                                    title: 'Something went wrong!'
                                })
                            }
                        }
                    })
                } else {
                    Toast.fire({
                        type: 'warning',
                        title: 'Please fill in the required fields!'
                    })
                }
            })

            $(document).on('click', '.btn-complaint-delete', function() {
                var complaintid = $(this).attr('data-id');
                Swal.fire({
                        title: 'Do you want to delete this complaint?',
                        type: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Delete'
                    })
                    .then((result) => {
                        if (result.value) {
                            $.ajax({
                                url: '/clinic/complaints/delete',
                                type: 'GET',
                                data: {
                                    '_token': '{{ csrf_token() }}',
                                    id: complaintid
                                },
                                complete: function() {
                                    Toast.fire({
                                        type: 'success',
                                        title: 'Deleted successfully!'
                                    })
                                    if (bolean == 0) {
                                        getcomplaints($('#reservation').val())

                                    } else {

                                        getcomplaints2(id);
                                    }
                                }
                            })
                        }
                    })
            })
            $(document).on('click', '.btn-complaint-addmedication', function() {
                var complaintid = $(this).attr('data-id');
                console.log(complaintid)

                $('#btn-submit-addmedication').attr('data-id', complaintid)
                $('#modal-addmedication').modal('show')
                $.ajax({
                    url: '/clinic/complaints/getdrugs',
                    type: 'GET',
                    dateType: 'json',
                    data: {
                        id: complaintid
                    },
                    success: function(data) {
                        $('#addmedication-drugid').empty();
                        medicines = data;
                        if (data.length > 0) {
                            $.each(data, function(key, value) {
                                if (value.quantityleft > 0) {
                                    $('#addmedication-drugid').append(
                                        '<option value="' + value.id + '">(' + value
                                        .condition + ') ' + value.genericname +
                                        ' - ' + value.brandname + ' (' + value
                                        .quantityleft + '/' + value.quantity +
                                        ')</option>'
                                    );
                                }
                            })
                        }

                    }
                })

            })
            $(document).on('click', '#btn-submit-addmedication', function() {
                var complaintid = $(this).attr('data-id');
                var drugid = $('#addmedication-drugid').val();
                var quantity = $('#addmedication-quantity').val()
                var remarks = $('#addmedication-remarks').val()
                var checkvalidation = 0;
                var quantityleft2 = medicines.filter(x => x.id == drugid);
                var quantityleft3 = Number(quantityleft2.map(x => x.quantityleft));
                var num = Number(quantity);

                var checkvalidation = 0;
                if (quantity.replace(/^\s+|\s+$/g, "").length == 0 || quantity == 0) {
                    checkvalidation = 1;
                    $('#addmedication-quantity').css('border', '1px solid red')
                } else {
                    checkvalidation = 0;
                    $('#addmedication-quantity').removeAttr('style')
                }

                if (quantityleft3 < num) {
                    checkvalidation = 1;
                    $('#addmedication-quantity').css('border', '1px solid red')
                    Toast.fire({
                        type: 'error',
                        title: ' Invalid Quantity!'
                    });
                } else {
                    $('#addmedication-quantity').removeAttr('style')
                    checkvalidation = 0;

                }

                if (num == quantityleft3) {
                    $('#addmedication-quantity').removeAttr('style')
                    checkvalidation = 0;
                    quantity = $('#addmedication-quantity').val()
                }
                if (checkvalidation == 0) {
                    store(complaintid, drugid, quantity, remarks);
                    $('#btn-close-addmedication').click();
                }



            })


            function store(complaintid, drugid, quantity, remarks) {
                console.log(quantity);
                $.ajax({
                    url: '/clinic/complaints/addmed',
                    type: 'GET',
                    dateType: 'json',
                    data: {
                        complaintid: complaintid,
                        drugid: drugid,
                        quantity: quantity,
                        remarks: remarks
                    },
                    success: function(data) {
                        if (data == 1) {

                            Toast.fire({
                                type: 'success',
                                title: 'Added successfully!'
                            })
                            if (bolean == 0) {
                                getcomplaints($('#reservation').val())

                            } else {

                                getcomplaints2(id);
                            }

                            // $('#modal-addcomplaint input, textarea').val('');
                            $('#modal-addmedication').find('input').val('')
                            $('#modal-addmedication').find('textarea').val('')
                        } else {
                            Toast.fire({
                                type: 'error',
                                title: 'Something went wrong!'
                            })
                        }
                    }
                })
            }


            $(document).on('click', '.u-complaint-editmedication', function() {
                var complaintid = $(this).attr('data-id');
                //console.log(complaintid);
                console.log(complaintid);
                $('#btn-submit-editmedication').attr('data-id', complaintid)
                $('#btn-delete-editmedication').attr('data-id', complaintid)
                $('#modal-editmedication').modal('show')
                $.ajax({
                    url: '/clinic/complaints/getdrugs',
                    type: 'GET',
                    dateType: 'json',
                    data: {
                        id: complaintid
                    },
                    success: function(data) {
                        $('#editmedication-drugid').empty();
                        medicines = data;
                        if (data.length > 0) {
                            $.each(data, function(key, value) {
                                var selectedoption = '';
                                if (value.selected == 1) {
                                    var selectedoption = 'selected';
                                }
                                if (value.quantityleft == 0) {
                                    if (value.selected == 1) {
                                        $('#editmedication-drugid').append(
                                            '<option value="' + value.id + '" ' +
                                            selectedoption + '>(' + value
                                            .condition + ') ' + value.genericname +
                                            ' - ' + value.brandname + ' (' + value
                                            .quantityleft + '/' + value.quantity +
                                            ')</option>'
                                        );
                                        $('#editmedication-quantity').val(value
                                            .quantityadded)
                                        $('#editmedication-remarks').val(value.remarks)
                                    }
                                } else {
                                    if (value.selected == 1) {
                                        $('#editmedication-drugid').append(
                                            '<option value="' + value.id + '" ' +
                                            selectedoption + '>(' + value
                                            .condition + ') ' + value.genericname +
                                            ' - ' + value.brandname + ' (' + value
                                            .quantityleft + '/' + value.quantity +
                                            ')</option>'
                                        );
                                        $('#editmedication-quantity').val(value
                                            .quantityadded)
                                        $('#editmedication-remarks').val(value.remarks)
                                    } else {
                                        $('#editmedication-drugid').append(
                                            '<option value="' + value.id + '" ' +
                                            selectedoption + '>(' + value
                                            .condition + ') ' + value.genericname +
                                            ' - ' + value.brandname + ' (' + value
                                            .quantityleft + '/' + value.quantity +
                                            ')</option>'
                                        );
                                    }
                                }
                            })
                        }

                        // userid = data.userid;
                        // $('#editcomplaint-description').val(data.description)
                        // $('#editcomplaint-date').val(data.cdate)
                        // $('#editcomplaint-time').val(data.ctime)
                        // $('#editcomplaint-actiontaken').val(data.actiontaken)
                    }
                })

            })
            $(document).on('click', '#btn-submit-editmedication', function() {
                var complaintid = $(this).attr('data-id');
                console.log('1:', complaintid);
                var drugid = $('#editmedication-drugid').val();
                var quantity = $('#editmedication-quantity').val()
                var remarks = $('#editmedication-remarks').val()
                var checkvalidation = 0;

                if (quantity.replace(/^\s+|\s+$/g, "").length == 0 || quantity == 0) {
                    checkvalidation = 1;
                    $('#editmedication-quantity').css('border', '1px solid red')
                } else {
                    checkvalidation = 0;
                    $('#editmedication-quantity').removeAttr('style')
                }

                if (checkvalidation == 0) {
                    $.ajax({
                        url: '/clinic/complaints/editmed',
                        type: 'GET',
                        dateType: 'json',
                        data: {
                            complaintid: complaintid,
                            drugid: drugid,
                            quantity: quantity,
                            remarks: remarks
                        },
                        success: function(data) {
                            if (data == 1) {

                                Toast.fire({
                                    type: 'success',
                                    title: 'Updated successfully!'
                                })
                                if (bolean == 0) {
                                    getcomplaints($('#reservation').val())

                                } else {

                                    getcomplaints2(id);
                                }
                                $('#btn-close-editmedication').click()
                                // $('#modal-addcomplaint input, textarea').val('');
                                $('#modal-editmedication').find('input').val('')
                                $('#modal-editmedication').find('textarea').val('')
                            } else {
                                Toast.fire({
                                    type: 'error',
                                    title: 'Something went wrong!'
                                })
                            }
                        }
                    })
                }

            })
            $(document).on('click', '#btn-delete-editmedication', function() {
                var complaintid = $(this).attr('data-id');
                Swal.fire({
                        title: 'Do you want to delete this medication?',
                        type: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Delete'
                    })
                    .then((result) => {
                        if (result.value) {
                            $.ajax({
                                url: '/clinic/complaints/deletemed',
                                type: 'GET',
                                dataType: 'json',
                                data: {
                                    id: complaintid
                                },
                                success: function(data) {
                                    if (data == 1) {
                                        Toast.fire({
                                            type: 'success',
                                            title: 'Deleted successfully!'
                                        })
                                        if (bolean == 0) {
                                            getcomplaints($('#reservation').val())

                                        } else {

                                            getcomplaints2(id);
                                        }
                                        $('#btn-close-addmedication').click();
                                        $('#btn-close-editmedication').click();
                                        $('#modal-editmedication').find('input').val('')
                                        $('#modal-editmedication').find('textarea').val('')
                                        $('#modal-addmedication').find('input').val('')
                                        $('#modal-addmedication').find('textarea').val('')
                                    } else {
                                        Toast.fire({
                                            type: 'error',
                                            title: 'Something went wrong!'
                                        })
                                    }
                                }
                            })
                        }
                    })
            })
        })
    </script>
@endsection
