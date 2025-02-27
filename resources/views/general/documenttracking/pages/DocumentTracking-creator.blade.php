@php
    $check_refid = DB::table('usertype')
        ->where('id', Session::get('currentPortal'))
        ->where('deleted', 0)
        ->select('refid', 'resourcepath')
        ->first();

    if (Session::get('currentPortal') == 14) {
        $extend = 'deanportal.layouts.app2';
    } elseif (auth()->user()->type == 17) {
        $extend = 'superadmin.layouts.app2';
    } elseif (Session::get('currentPortal') == 3) {
        $extend = 'registrar.layouts.app';
    } elseif (Session::get('currentPortal') == 8) {
        $extend = 'admission.layouts.app2';
    } elseif (Session::get('currentPortal') == 1) {
        $extend = 'teacher.layouts.app';
    } elseif (Session::get('currentPortal') == 2) {
        $extend = 'principalsportal.layouts.app2';
    } elseif (Session::get('currentPortal') == 4) {
        $extend = 'finance.layouts.app';
    } elseif (Session::get('currentPortal') == 15) {
        $extend = 'finance.layouts.app';
    } elseif (Session::get('currentPortal') == 18) {
        $extend = 'ctportal.layouts.app2';
    } elseif (Session::get('currentPortal') == 10) {
        $extend = 'hr.layouts.app';
    } elseif (Session::get('currentPortal') == 16) {
        $extend = 'chairpersonportal.layouts.app2';
    } elseif (auth()->user()->type == 16) {
        $extend = 'chairpersonportal.layouts.app2';
    } else {
        if (isset($check_refid->refid)) {
            if ($check_refid->resourcepath == null) {
                $extend = 'general.defaultportal.layouts.app';
            } elseif ($check_refid->refid == 26) {
                $extend = 'hr.layouts.app';
            } elseif ($check_refid->refid == 27) {
                $extend = 'academiccoor.layouts.app2';
            } elseif ($check_refid->refid == 22) {
                $extend = 'principalcoor.layouts.app2';
            } elseif ($check_refid->refid == 29) {
                $extend = 'idmanagement.layouts.app2';
            } elseif ($check_refid->refid == 23) {
                $extend = 'clinic.index';
            } elseif ($check_refid->refid == 24) {
                $extend = 'clinic_nurse.index';
            } elseif ($check_refid->refid == 25) {
                $extend = 'clinic_doctor.index';
            } elseif ($check_refid->refid == 31) {
                $extend = 'guidanceV2.layouts.app2';
            } elseif ($check_refid->refid == 35) {
                $extend = 'tesda.layouts.app2';
            } else {
                $extend = 'general.defaultportal.layouts.app';
            }
        } else {
            $extend = 'general.defaultportal.layouts.app';
        }
    }
@endphp
@extends($extend)

@section('pagespecificscripts')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Source+Sans+3:ital,wght@0,200..900;1,200..900&display=swap"
        rel="stylesheet">

    <style>
        body {
            /* font-family: "Roboto", sans-serif; */
            /* font-family: "Poppins", sans-serif; */
        }

        img {
            border-radius: 0px !important;
        }

        .alert {
            width: 80px;
            border-radius: 20px;
            text-align: center;
        }

        .button-edit {
            width: 80px;
            border-radius: 20px;
            text-align: center;
        }

        .button-create {
            width: 35%;
            border-radius: 10px;
            text-align: center;
            background-color: #003687;
        }

        .sig-button {
            width: 50%;
            border-radius: 20px;
            text-align: center;
            background-color: #003687;
            position: flex;
        }

        .input {
            border-color: black
        }

        .modal-header-sm {
            padding: 0.5rem;
            border-bottom: 1px solid #dee2e6;
            border-top-left-radius: calc(0.3rem - 1px);
            border-top-right-radius: calc(0.3rem - 1px);
        }

        .modal-header-sm .modal-title {
            font-size: 0.875rem;
            /* Adjust the font size as needed */
        }

        .activeimage {
            border: 2px solid skyblue;
        }

        .align-middle td {
            vertical-align: middle;
        }

        .rounded-badge {
            border-radius: 20px;
            text-align: center;
        }

        .badge-light {
            background-color: #CFCFCF;
        }

        .custom-bg-lightgray {
            background-color: #d3d3d3;
            color: black;
        }

        .border-blue {
            border-radius: 20px;
            background-color: #CFCFCF;
        }

        .rounded-left {
            border-radius: 20px 0px 0px 20px !important;
        }

        .rounded-right {
            border-radius: 0px 20px 20px 0px !important;
        }

        .outlined-row {
            /* outline: 2px solid #101b92 !important; */
            border-radius: 15px !important;
            border-bottom: 3px solid #003687;
            /* outline: 2px solid #003687 !important; */
        }

        .timeline::before {
            border-radius: .25rem;
            background-color: #003687;
            bottom: 0;
            content: "";
            left: 31px;
            margin: 0;
            position: absolute;
            top: 0;
            width: 4px;
        }

        .shadow-sm {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        }

        .shadow {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }

        .shadow-lg {
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
        }

        .shadow-none {
            box-shadow: none !important;
        }

        .card {
            /* box-shadow: 1px 1px 4px #272727c9 !important; */
            border: none !important;
        }
    </style>
@endsection

@section('content')
    <div class="content">
        <div class="container">
            <ul class="nav nav-tabs " id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="DocuTracking-tab" data-toggle="tab" href="#DocuTracking" role="tab"
                        aria-controls="DocuTracking" aria-selected="true">Document Tracking<span
                            class="badge badge-pill badge-warning" id="countAllDocs">0 </span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="OpenDocu-tab" data-toggle="tab" href="#OpenDocu" role="tab"
                        aria-controls="OpenDocu" aria-selected="false">Open Document<span
                            class="badge badge-pill badge-warning" id="countOpenDocs">0 </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="ClosedDocu-tab" data-toggle="tab" href="#ClosedDocu" role="tab"
                        aria-controls="ClosedDocu" aria-selected="false">Closed Document<span
                            class="badge badge-pill badge-warning" id="countClosedDocs">0 </span>
                    </a>
                </li>
                <button type="button" class="btn btn-primary btn-sm m-2 float-right btn_create_signatory"
                    data-toggle="modal" data-target="#CreateSignatories">
                    + Create Document Tracking
                </button>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="DocuTracking" role="tabpanel" aria-labelledby="DocuTracking-tab">
                    <div class="mt-5">
                        <h3>All Document</h3>
                        <div class="table-responsive">
                            <table class="table align-middle" id="docTrackingTable"
                                style="background-color: #f4f6f9; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Document Type</th>
                                        <th>Document name</th>
                                        <th>Issued By</th>
                                        <th>Created Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="documentList">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="OpenDocu" role="tabpanel" aria-labelledby="OpenDocu-tab">
                    <div class="container mt-5">
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="OpDocu" role="tabpanel"
                                aria-labelledby="OpDocu-tab">
                                <h3>Open Document</h3>
                                <div class="table-responsive">
                                    <table id="OpendocTrackingTable" class="table align-middle table-borderless"
                                        style="background-color: #f4f6f9; padding:10px !important;">
                                        <thead>
                                            <tr>
                                                <th>Type</th>
                                                <th>Document Type</th>
                                                <th>Document name</th>
                                                <th>Issued By</th>
                                                <th>Created Date</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="openDocumentList">
                                            <!-- Your data rows here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="ClosedDocu" role="tabpanel" aria-labelledby="ClosedDocu-tab">
                    <div class="container mt-5">
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="CloDocu" role="tabpanel"
                                aria-labelledby="CloDocu-tab">
                                <h3>Closed Document</h3>
                                <div class="table-responsive">
                                    <table id="CloseddocTrackingTable" class="table align-middle table-borderless"
                                        style="background-color: #f4f6f9;">
                                        <thead>
                                            <tr>
                                                <th>Type</th>
                                                <th>Document Type</th>
                                                <th>Document name</th>
                                                <th>Issued By</th>
                                                <th>Created Date</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="closeDocumentList">

                                            <!-- Your data rows here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="CreateSignatories" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-sm">
                        <h5 class="modal-title-" id="exampleModalLabel">Create Signatories</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card shadow">
                                    <div class="card-header">
                                    </div>
                                    <div class="card-body" id="iframe">
                                        <iframe
                                            src="https://docs.google.com/viewer?url=https://example.com/your-document.pdf&embedded=true"
                                            width="100%" height="600px" frameborder="0"></iframe>
                                    </div>

                                    <div class="card-body" id="carousel" hidden>
                                        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                                            <ol class="carousel-indicators">
                                                {{-- <li data-target="#carouselExampleIndicators" data-slide-to="0"
                                                    class="active"></li>
                                                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                                                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li> --}}
                                            </ol>
                                            <div class="carousel-inner">
                                                {{-- <div class="carousel-item active">
                                                    <img class="d-block w-100"
                                                        src="https://placehold.it/900x500/39CCCC/ffffff&text=I+Love+Bootstrap"
                                                        alt="First slide">
                                                </div>
                                                <div class="carousel-item">
                                                    <img class="d-block w-100"
                                                        src="https://placehold.it/900x500/3c8dbc/ffffff&text=I+Love+Bootstrap"
                                                        alt="Second slide">
                                                </div>
                                                <div class="carousel-item">
                                                    <img class="d-block w-100"
                                                        src="https://placehold.it/900x500/f39c12/ffffff&text=I+Love+Bootstrap"
                                                        alt="Third slide">
                                                </div> --}}
                                            </div>
                                            <a class="carousel-control-prev" href="#carouselExampleIndicators"
                                                role="button" data-slide="prev">
                                                <span class="carousel-control-custom-icon" aria-hidden="true">
                                                    <i class="fas fa-chevron-left"></i>
                                                </span>
                                                <span class="sr-only">Previous</span>
                                            </a>
                                            <a class="carousel-control-next" href="#carouselExampleIndicators"
                                                role="button" data-slide="next">
                                                <span class="carousel-control-custom-icon" aria-hidden="true">
                                                    <i class="fas fa-chevron-right"></i>
                                                </span>
                                                <span class="sr-only">Next</span>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="card-footer d-flex carousel-footer">
                                        {{-- <img class="d-block mx-1"
                                            src="https://placehold.it/900x500/39CCCC/ffffff&text=I+Love+Bootstrap"
                                            alt="image slide" style="height: 50px; width: 50px;">
                                        <img class="d-block mx-1"
                                            src="https://placehold.it/900x500/39CCCC/ffffff&text=I+Love+Bootstrap"
                                            alt="image slide" style="height: 50px; width: 50px;">
                                        <img class="d-block mx-1"
                                            src="https://placehold.it/900x500/39CCCC/ffffff&text=I+Love+Bootstrap"
                                            alt="image slide" style="height: 50px; width: 50px;"> --}}
                                        {{-- <div class="btn btn-default">Prev</div>
                                        <div class="btn btn-default">Next</div> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <form action="/upload" method="post" enctype="multipart/form-data">
                                    <div class=" p-3">
                                        <div class="form-group border p-2 bg-secondary-emphasis bg-opacity-10 border border-secondary-emphasis border-start-0"
                                            style="border-radius: 20px;">
                                            <input type="file" class="form-control-file" id="document"
                                                name="document" placeholder="Upload Document" multiple>
                                        </div>
                                        <div class="form-group">
                                            <label for="documentType" class="mb-1">Document Type <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-control" id="documentType" name="documentType"
                                                style="width: 100%;">
                                                <optgroup label="Document Type">
                                                </optgroup>
                                            </select>
                                            <span class="invalid-feedback" role="alert">
                                                <strong>Document Type is required!</strong>
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <label for="documentName" class="mb-1">Document Name <span
                                                    class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" id="documentName"
                                                onkeyup="this.value=this.value.toUpperCase();" name="documentName"
                                                placeholder="Enter Document name">
                                            <span class="invalid-feedback" role="alert">
                                                <strong>Document Name is required!</strong>
                                            </span>
                                        </div>

                                        <div class="form-group">
                                            <label for="issuedBy" class="mb-1">Issued By <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="issuedByID"
                                                value="{{ auth()->user()->id }}" disabled hidden>
                                            <input type="text" class="form-control" id="issuedBy" name="issuedBy"
                                                value="{{ auth()->user()->name }}" placeholder="Name of the creator"
                                                disabled>
                                        </div>

                                        <div class="form-group">
                                            <label for="dateCreated" class="mb-1">Date Created <span
                                                    class="text-danger">*</span>
                                            </label>
                                            <input type="date" class="form-control" id="dateCreated"
                                                name="dateCreated" placeholder="Date created" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="remarks" class="form-label" class="mb-1">Remarks:</label>
                                            <textarea class="form-control" id="remarks" rows="3"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="selectViewer" class="mb-1">Select Viewers
                                            </label>
                                            <select class="form-control select2" id="selectViewer" style="width: 100%;">
                                                <optgroup label="Viewers">
                                                    @foreach (DB::table('teacher')->where('deleted', 0)->get() as $item)
                                                        <option value="{{ $item->userid }}">{{ $item->lastname }},
                                                            {{ $item->firstname }}</option>
                                                    @endforeach
                                                </optgroup>
                                            </select>

                                        </div>

                                        <div class="form-group">
                                            <label for="selectSignee" class="mb-1">Select Signees <span
                                                    class="text-danger">*</span>
                                            </label>
                                            <select class="form-control select2" id="selectSignee" style="width: 100%;">
                                                <optgroup label="Signees">
                                                    @foreach (DB::table('teacher')->where('teacher.deleted', 0)->join('usertype', 'teacher.usertypeid', '=', 'usertype.id')->select('teacher.*', 'usertype.utype')->get() as $item)
                                                        <option value="{{ $item->userid }}">{{ $item->lastname }},
                                                            {{ $item->firstname }} - {{ $item->utype }} </option>
                                                    @endforeach
                                                </optgroup>
                                            </select>
                                            <span class="invalid-feedback" role="alert">
                                                <strong>Signees are required!</strong>
                                            </span>
                                        </div>

                                        <div class="form-group">
                                            <label class="mb-1">Signatories</label>
                                            <div id="tb_signatories">
                                                <span class="text-center col-12">No Selected Signee!
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary button-create" style="height:50%;">Create
                            Signatories</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="ManageSignatories">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-sm">
                        <h5 class="modal-title-" id="exampleModalLabel"> <span id="modalTitle">Manage Signatories</span>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card shadow">
                                    <div class="card-header">
                                    </div>
                                    <div class="card-body">
                                        <div id="carouselExampleIndicators2" class="carousel slide" data-ride="carousel">
                                            <ol class="carousel-indicators">
                                                {{-- <li data-target="#carouselExampleIndicators2" data-slide-to="0"
                                                class="active"></li>
                                            <li data-target="#carouselExampleIndicators2" data-slide-to="1"></li>
                                            <li data-target="#carouselExampleIndicators2" data-slide-to="2"></li> --}}
                                            </ol>
                                            <div class="carousel-inner">
                                                {{-- <div class="carousel-item active">
                                                <img class="d-block w-100"
                                                    src="https://placehold.it/900x500/39CCCC/ffffff&text=I+Love+Bootstrap"
                                                    alt="First slide">
                                            </div>
                                            <div class="carousel-item">
                                                <img class="d-block w-100"
                                                    src="https://placehold.it/900x500/3c8dbc/ffffff&text=I+Love+Bootstrap"
                                                    alt="Second slide">
                                            </div>
                                            <div class="carousel-item">
                                                <img class="d-block w-100"
                                                    src="https://placehold.it/900x500/f39c12/ffffff&text=I+Love+Bootstrap"
                                                    alt="Third slide">
                                            </div> --}}
                                            </div>
                                            <a class="carousel-control-prev" href="#carouselExampleIndicators2"
                                                role="button" data-slide="prev">
                                                <span class="carousel-control-custom-icon" aria-hidden="true">
                                                    <i class="fas fa-chevron-left"></i>
                                                </span>
                                                <span class="sr-only">Previous</span>
                                            </a>
                                            <a class="carousel-control-next" href="#carouselExampleIndicators2"
                                                role="button" data-slide="next">
                                                <span class="carousel-control-custom-icon" aria-hidden="true">
                                                    <i class="fas fa-chevron-right"></i>
                                                </span>
                                                <span class="sr-only">Next</span>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="card-footer d-flex carousel-footer">

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <form action="/upload" method="post" enctype="multipart/form-data">
                                    <div class="p-3">
                                        <div class="form-group border p-2 bg-secondary-emphasis bg-opacity-10 border border-secondary-emphasis border-start-0"
                                            style="border-radius: 20px;">
                                            <input type="file" class="form-control-file" id="document2"
                                                name="document2" placeholder="Upload Document" multiple>
                                        </div>
                                        <div class="form-group">
                                            <label for="documentType2" class="mb-1">Document Type <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-control" id="documentType2" name="documentType2"
                                                style="width: 100%;">
                                                <optgroup label="Document Type">
                                                </optgroup>
                                            </select>
                                            <span class="invalid-feedback" role="alert">
                                                <strong>Document Type is required!</strong>
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <label for="documentName2" class="mb-1">Document Name <span
                                                    class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" id="documentName2"
                                                onkeyup="this.value=this.value.toUpperCase();" name="documentName2"
                                                placeholder="Enter signatory name">
                                            <span class="invalid-feedback" role="alert">
                                                <strong>Document Name is required!</strong>
                                            </span>
                                        </div>

                                        <div class="form-group">
                                            <label for="issuedBy2" class="mb-1">Issued By <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="issuedByID2"
                                                value="{{ auth()->user()->id }}" disabled hidden>
                                            <input type="text" class="form-control" id="issuedBy2" name="issuedBy2"
                                                value="{{ auth()->user()->name }}" placeholder="Name of the creator"
                                                disabled>
                                        </div>

                                        <div class="form-group">
                                            <label for="dateCreated2" class="mb-1">Date Created <span
                                                    class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" id="dateCreated2"
                                                name="dateCreated2" placeholder="Date created" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="remarks2" class="form-label" class="mb-1">Remarks:</label>
                                            <textarea class="form-control" id="remarks2" rows="3"></textarea>
                                        </div>
                                        <div class="form-group" hidden id="viewer_wrapper">
                                            <label for="selectViewer2" class="mb-1"> Select Viewers <span
                                                    class="text-danger">*</span>
                                            </label>
                                            <select class="form-control select2" id="selectViewer2" style="width: 100%;">
                                                <optgroup label="Viewers">
                                                    @foreach (DB::table('teacher')->where('deleted', 0)->get() as $item)
                                                        <option value="{{ $item->userid }}">{{ $item->lastname }},
                                                            {{ $item->firstname }}</option>
                                                    @endforeach
                                                </optgroup>
                                            </select>
                                            <span class="invalid-feedback" role="alert">
                                                <strong>Viewers are required!</strong>
                                            </span>
                                        </div>

                                        <div class="form-group" hidden id="signee_wrapper">
                                            <label for="edit-select-signee" class="mb-1">Select Signees <span
                                                    class="text-danger">*</span>
                                            </label>
                                            <select class="form-control select2" id="edit-select-signee"
                                                style="width: 100%;">
                                                <optgroup label="Signees">
                                                    @foreach (DB::table('teacher')->where('teacher.deleted', 0)->where('teacher.userid', '!=', auth()->user()->id)->join('usertype', 'teacher.usertypeid', '=', 'usertype.id')->select('teacher.*', 'usertype.utype')->get() as $item)
                                                        <option value="{{ $item->userid }}">{{ $item->lastname }},
                                                            {{ $item->firstname }} - {{ $item->utype }} </option>
                                                    @endforeach
                                                </optgroup>
                                            </select>
                                            <span class="invalid-feedback" role="alert">
                                                <strong>Signees are required!</strong>
                                            </span>
                                        </div>

                                        <div class="form-group">
                                            <label class="mb-1">Signatories</label>
                                            <div id="tb_signatories2">
                                                <span class="text-center col-12">No Selected Signee!
                                                </span>
                                            </div>
                                        </div>


                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>

                    <div class="modal-footer" id="manageSignatoryFooter"hidden>
                        <div class="col-md-6"></div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6 btn_wrap" hidden>
                                    <button type="button" class="btn btn-danger ShowRejectModal"
                                        style="width:100%">Reject</button>
                                </div>
                                <div class="col-md-6 btn_wrap" hidden>
                                    <button type="button" class="btn btn-primary btn_received"
                                        style="width:100%">Received</button>
                                </div>
                                <div class="col-md-6 btn_wrap" hidden>
                                    <button type="button" class="btn btn-success btn_forward"
                                        style="width:100%">Forward</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer" id="manageSignatoryFooter2" hidden>
                        <div class="col-md-6"></div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-default" style="width:100%"
                                        data-dismiss="modal" aria-label="Close">Cancel</button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-primary btn_update_docu"
                                        style="width:100%">Update</button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="RejectDocument" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-sm">
                        <h5 class="modal-title" id="exampleModalLabel">Reject</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="" class="mb-1">Remarks</label>
                                <textarea name="" id="remarks_rejected" rows="5" class="form-control"></textarea>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="" class="mb-1">Forward To</label>
                                    <select class="form-control" id="selectForwardTo" style="width: 100%;">
                                        <optgroup label="Signees">
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <div class="">
                            <button type="button" class="btn btn-danger btn_reject" style="width:100%">Reject</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="ForwardDocument" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-sm">
                        <h5>Forward</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="" class="mb-1">Remarks</label>
                                <textarea name="" id="remarks_forward" rows="5" class="form-control"></textarea>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="" class="mb-1">Forward To</label>
                                    <select class="form-control" id="selectForwardTo2" style="width: 100%;">
                                        <optgroup label="Signees">
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <div class="">
                            <button type="button" class="btn btn-primary btn_forward2"
                                style="width:100%">Forward</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>
@endsection

@section('footerjavascript')
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.all.js') }}"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/select2.min.js') }}"></script>
    <script>
        $('body').addClass('sidebar-collapse')
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000
        });
        var documentType = $('#documentType')
        var documentName = $('#documentName')
        var issuedBy = $('#issuedBy')
        var issuedByID = $('#issuedByID')
        var dateCreated = $('#dateCreated')
        var remarks = $('#remarks')
        var viewers = $('#selectViewer')
        var signee = $('#selectSignee');


        $(document).ready(function() {
            const currentDate = new Date().toISOString().split('T')[0];
            getAllDocuments()
            getalldoctype();
            signee.val([])
            dateCreated.val(currentDate)

            $(document).on('click', '.carouselimages', function() {
                $(this).closest('.carousel-footer').find('.carouselimages').removeClass('activeimage')
                $(this).addClass('activeimage')
            });

            $('.btn_create_signatory').on('click', function() {
                console.log('HELLO');
                $('.carousel-footer').empty()
                $('#documentName').val('')
                $('#remarks').val('')
                $('#selectSignee').val('').change()
            })

            $('#document').on('change', function() {
                // Get the file name
                var fileName = $(this).val().split('\\').pop();
                // Update the label text
                // $('.custom-file-label').text(fileName);


                // Check if a file is selected
                if (this.files) {
                    var files = this.files
                    var validFileTypes = ['image/jpeg', 'image/png', 'image/gif',
                        'image/bmp'
                    ]; // Add more types if needed

                    console.log(files);

                    $('.carousel-inner').empty()
                    $('.carousel-indicators').empty()
                    $('.carousel-footer').empty()

                    for (let index = 0; index < files.length; index++) {
                        const element = files[index];

                        // Check if the file is an image
                        if (!validFileTypes.includes(element.type)) {
                            notify('warning', 'Only image type is allowed!')
                            continue; // Skip the non-image file
                        }

                        console.log(element.name)

                        // Create a FileReader object to read the file
                        var reader = new FileReader();

                        // Set the callback function when the file is loaded
                        reader.onload = function(e) {
                            $('.carousel-indicators').append(`
                                <li data-target="#carouselExampleIndicators" data-slide-to="${index}"
                                                    class="${index == 0 ? 'active' : ''}"></li>
                            `)

                            $('.carousel-inner').append(`
                                <div class="carousel-item ${index == 0 ? 'active' : ''}">
                                    <img class="d-block w-100"
                                        src="${e.target.result}"
                                        alt="slide pic"
                                        style="max-height: 600px;border-radius: 0px !important;"
                                        >
                                </div>
                            `)

                            $('.carousel-footer').append(`
                                <img class="d-block mx-1 carouselimages "
                                    src="${e.target.result}"
                                    alt="image slide" style="height: 70px; width: 70px;border-radius: 0px !important;"
                                    data-target="#carouselExampleIndicators" data-slide-to="${index}">
                            `)

                            // Update the src attribute of the img element with the data URL
                            // $('#document_cover').attr('src', e.target.result).show();
                            $('#document_cover').removeAttr('hidden', false);
                            $('#iframe').attr('hidden', true);
                            $('#carousel').attr('hidden', false);
                        };

                        // Read the file as a data URL
                        reader.readAsDataURL(element);
                    }

                } else {
                    // Hide the image if no file is chosen
                    $('#document_cover').attr('src', '').hide();
                }
            });

            $('.button-create').on('click', function() {
                store_document()
            });

            $('#selectSignee').on('change', function() {
                console.log($(this).val());
                $.ajax({
                    type: 'GET',
                    url: '{{ route('get.teachers') }}',
                    data: {
                        signee: JSON.stringify(signee.val())
                    },
                    success: function(data) {
                        console.log(data)
                        $('#tb_signatories').empty()
                        if (data.length > 0) {
                            data.forEach((element, key) => {
                                $('#tb_signatories').append(`
                                <div class="bg-gray p-2 rounded mb-1">
                                    <div class="d-flex align-items-center">
                                        <strong>${key + 1}</strong> | Name
                                        <span class="ml-2">
                                            <i class="fas fa-user-circle mr-1"></i>
                                            ${element.lastname}, ${element.firstname}
                                        </span>
                                        <span class="ml-auto text-warning">
                                            ${element.utype}
                                        </span>
                                    </div>
                                </div>

                                `)
                            });
                        } else {
                            $('#tb_signatories').empty()
                            $('#tb_signatories').append(
                                `<tr>
                                    <td class="text-center">No Selected Signee!
                                    </td>
                                </tr>`
                            )

                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseJSON);
                    }
                })
            });

            $('#edit-select-signee').on('change', function() {
                console.log($(this).val());
                $.ajax({
                    type: 'GET',
                    url: '{{ route('get.teachers') }}',
                    data: {
                        signee: JSON.stringify($('#edit-select-signee').val())
                    },
                    success: function(data) {
                        console.log(data)
                        $('#tb_signatories2').empty()
                        if (data.length > 0) {
                            data.forEach((element, key) => {
                                $('#tb_signatories2').append(`
                            <div class="bg-gray p-2 rounded mb-1">
                                <div class="d-flex align-items-center">
                                    <strong>${key + 1}</strong> | Name
                                    <span class="ml-2">
                                        <i class="fas fa-user-circle mr-1"></i>
                                        ${element.lastname}, ${element.firstname}
                                    </span>
                                    <span class="ml-auto text-warning">
                                        ${element.utype}
                                    </span>
                                </div>
                            </div>

                            `)
                            });
                        } else {
                            $('#tb_signatories2').empty()
                            $('#tb_signatories2').append(
                                `<tr>
                                <td class="text-center">No Selected Signee!
                                </td>
                            </tr>`
                            )

                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseJSON);
                    }
                })
            });

            $('#documentList').on('click', '.document-row', function() {
                // Remove the 'bg-gray' class from all rows within '#documentList'
                $('#documentList .document-row').not(this).removeClass('outlined-row');


                // Toggle the 'bg-gray' class on the clicked row
                $(this).addClass('outlined-row');
                $(this).find('td:first-child').addClass('rounded-left')
                $(this).find('td:last-child').addClass('rounded-right')

                var targetCollapse = $(this).data('target');
                $('.collapse').not(targetCollapse).collapse('hide');
            });

            $('#openDocumentList').on('click', '.document-row', function() {
                // Remove the 'bg-gray' class from all rows within '#documentList'
                $('#openDocumentList .document-row').not(this).removeClass('outlined-row');


                // Toggle the 'bg-gray' class on the clicked row
                $(this).addClass('outlined-row');
                $(this).find('td:first-child').addClass('rounded-left')
                $(this).find('td:last-child').addClass('rounded-right')

                var targetCollapse = $(this).data('target');
                $('.collapse').not(targetCollapse).collapse('hide');
            });

            $('#closeDocumentList').on('click', '.document-row', function() {
                // Remove the 'bg-gray' class from all rows within '#documentList'
                $('#closeDocumentList .document-row').not(this).removeClass('outlined-row');


                // Toggle the 'bg-gray' class on the clicked row
                $(this).addClass('outlined-row');
                $(this).find('td:first-child').addClass('rounded-left')
                $(this).find('td:last-child').addClass('rounded-right')

                var targetCollapse = $(this).data('target');
                $('.collapse').not(targetCollapse).collapse('hide');
            });


            $(document).on('click', '.btn_manage', function(event) {
                event.stopPropagation(); // Prevent the event from propagating to the parent <tr>
                var id = $(this).data('id');
                $('#modalTitle').text('Manage Signatories')
                $('#manageSignatoryFooter2').attr('hidden', true)
                $('#viewer_wrapper').attr('hidden', true)
                $('#signee_wrapper').attr('hidden', true)
                $.ajax({
                    type: 'GET',
                    url: '{{ route('get.documenttracking') }}',
                    data: {
                        id: id,
                    },
                    success: function(data) {
                        console.log(data)
                        if (data.document_status == 'close') {
                            $('#manageSignatoryFooter').attr('hidden', true)
                        } else {
                            $('#manageSignatoryFooter').attr('hidden', false)
                        }
                        $('#document2').attr('disabled', true);
                        $('#documentType2').val(data.document_type_id).change().attr('disabled',
                            true)
                        $('#documentName2').val(data.document_name).attr('disabled', true)
                        $('#issuedBy2').val(data.issuedby_name).attr('disabled', true)
                        $('#dateCreated2').val(data.formatted_created_at).attr('disabled', true)
                        if (data.document_viewers != null) {
                            $('#selectViewer2').val(data.document_viewers.split(',')).change()
                                .attr(
                                    'disabled', true)
                        }
                        $('#selectSignee2').val(data.signees).change().attr('disabled', true)
                        $('#remarks2').val(data.document_remarks).attr('disabled', true)

                        if (data.picurl.length > 0) {
                            $('.carousel-inner').empty()
                            $('.carousel-indicators').empty()
                            $('.carousel-footer').empty()
                            data.picurl.forEach((url, index) => {
                                $('.carousel-indicators2').append(`
                                    <li data-target="#carouselExampleIndicators2" data-slide-to="${index}" class="${index === 0 ? 'active' : ''}"></li>
                                `);

                                $('.carousel-inner').append(`
                                    <div class="carousel-item ${index === 0 ? 'active' : ''}">
                                        <a href="${url.picurl}" target="_blank">
                                            <img class="d-block w-100" src="${url.picurl}" alt="slide pic" style="max-height: 600px;border-radius: 0px !important;">
                                        </a>
                                    </div>
                           
                                `);

                                $('.carousel-footer').append(`
                                    <img class="d-block mx-1 carouselimages" 
                                    src="${url.picurl}" alt="image slide" 
                                    style="height: 70px; width: 70px;border-radius: 0px !important;" 
                                    data-target="#carouselExampleIndicators2" data-slide-to="${index}">
                                    
                                `);

                                $('#carousel2').attr('hidden', false);
                            });
                        }

                        // populate signatories
                        if (data.signeedetails.length > 0) {
                            $('#tb_signatories2').empty()
                            data.signeedetails.forEach((element, key) => {
                                $('#tb_signatories2').append(`
                                <div class="${element.status == "Forwarded" ? 'bg-success' : element.status == 'Onhand' ? 'bg-primary': element.status == 'Rejected' ? 'bg-danger' : 'bg-warning'} p-2 rounded mb-1">
                                    <div class="d-flex align-items-center">
                                        <strong>${key + 1}</strong> | Name
                                        <span class="ml-2">
                                            <i class="fas fa-user-circle mr-1"></i>
                                            ${element.name}
                                        </span>
                                        <span class="ml-auto">
                                            ${element.utype}
                                        </span>
                                    </div>
                                </div>
                                `)
                            });
                        } else {
                            $('#tb_signatories2').empty()
                            $('#tb_signatories2').append(
                                `<tr>
                                    <td class="text-center">No Selected Signee!
                                    </td>
                                </tr>`
                            )
                        }

                        if (data.signee_user) {
                            if (data.signee_user.status == "Onhand") {
                                $('.modal-footer .btn_wrap').eq(0).attr('hidden',
                                    false); // Show .btn_reject parent div
                                $('.modal-footer .btn_wrap').eq(1).attr('hidden',
                                    true); // Hide .btn_received parent div
                                $('.modal-footer .btn_wrap').eq(2).attr('hidden',
                                    false); // Show .btn_forward parent div
                            } else if (data.signee_user.status == "Forwarded") {
                                $('.modal-footer .btn_wrap').eq(0).attr('hidden',
                                    true); // Show .btn_reject parent div
                                $('.modal-footer .btn_wrap').eq(1).attr('hidden',
                                    true); // Hide .btn_received parent div
                                $('.modal-footer .btn_wrap').eq(2).attr('hidden',
                                    true); // Show .btn_forward parent div
                            } else {
                                $('.modal-footer .btn_wrap').eq(0).attr('hidden',
                                    false); // Show .btn_reject parent div
                                $('.modal-footer .btn_wrap').eq(1).attr('hidden',
                                    false); // Hide .btn_received parent div
                                $('.modal-footer .btn_wrap').eq(2).attr('hidden',
                                    true); // Show .btn_forward parent div
                            }
                        } else {
                            $('.modal-footer .btn_wrap').eq(0).attr('hidden',
                                true); // Show .btn_reject parent div
                            $('.modal-footer .btn_wrap').eq(1).attr('hidden',
                                true); // Hide .btn_received parent div
                            $('.modal-footer .btn_wrap').eq(2).attr('hidden',
                                true); // Show .btn_forward parent div
                        }

                        $('.btn_received, .btn_forward, .ShowRejectModal').data('id', id);

                        $('#ManageSignatories').modal()

                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseJSON);
                    }
                })

            });

            $('.btn_received').on('click', function() {
                var id = $(this).data('id')
                console.log(id);

                $.ajax({
                    type: 'GET',
                    data: {
                        trackingid: id
                    },
                    url: '{{ route('receive.document') }}',
                    success: function(data) {
                        console.log(data);
                        if (data.status == 'success') {
                            $('.modal-footer .btn_wrap').eq(2).attr('hidden',
                                false); // Show .btn_forward parent div
                            $('.modal-footer .btn_wrap').eq(1).attr('hidden',
                                true); // Show .btn_forward parent div
                        }
                        notify(data.status, data.message)
                        getAllDocuments()
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseJSON);
                    }
                })

            })

            $('.btn_forward').on('click', function() {
                var id = $(this).data('id')
                console.log(id);

                $.ajax({
                    type: 'GET',
                    data: {
                        trackingid: id
                    },
                    url: '{{ route('get.Available.Signee') }}',
                    success: function(data) {
                        console.log(data);

                        $('#selectForwardTo2').select2({
                            data: data,
                            theme: 'bootstrap4',
                        })

                        $('#ForwardDocument').modal()

                        $('.btn_forward2').on('click', function() {
                            $.ajax({
                                type: 'GET',
                                data: {
                                    trackingid: id,
                                    forwardedto: $('#selectForwardTo2').val(),
                                    remarks: $('#remarks_forward').val()
                                },
                                url: '{{ route('forward.document') }}',
                                success: function(data) {
                                    console.log(data);
                                    notify(data.status, data.message)
                                    if (data.status == 'success') {
                                        Swal.fire({
                                            type: data.status,
                                            title: data.message,
                                            text: `${data.next_signee}`,
                                            showCancelButton: false,
                                            confirmButtonColor: '#3085d6',
                                            cancelButtonColor: '#d33',
                                            confirmButtonText: 'Ok'
                                        }).then((result) => {
                                            getAllDocuments()
                                        });
                                    } else {
                                        Swal.fire({
                                            type: data.status,
                                            title: data.message,
                                            showCancelButton: false,
                                            confirmButtonColor: '#3085d6',
                                            cancelButtonColor: '#d33',
                                            confirmButtonText: 'Ok'
                                        }).then((result) => {
                                            getAllDocuments()
                                        });
                                    }

                                    $('#ForwardDocument').modal('hide')
                                    $('#ManageSignatories').modal('hide')
                                    getAllDocuments()
                                    Swal.fire({
                                        type: data.status,
                                        title: data.message,
                                        text: `${data.next_signee ? data.next_signee : ''}`,
                                        showCancelButton: false,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                        confirmButtonText: 'Ok'
                                    })
                                },
                                error: function(xhr, status, error) {
                                    console.log(xhr.responseJSON);
                                }
                            })
                        })
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseJSON);
                    }
                })

            })

            $('.ShowRejectModal').on('click', function() {
                var id = $(this).data('id')
                console.log(id)
                $.ajax({
                    type: 'GET',
                    data: {
                        trackingid: id
                    },
                    url: '{{ route('get.Available.Signee') }}',
                    success: function(data) {
                        console.log(data);

                        $('#selectForwardTo').select2({
                            data: data,
                            theme: 'bootstrap4',
                        })

                        $('#RejectDocument').modal()

                        $('.btn_reject').on('click', function() {
                            $.ajax({
                                type: 'GET',
                                data: {
                                    trackingid: id,
                                    forwardedto: $('#selectForwardTo').val(),
                                    remarks: $('#remarks_rejected').val()
                                },
                                url: '{{ route('reject.document') }}',
                                success: function(data) {
                                    console.log(data);
                                    notify(data.status, data.message)
                                    if (data.status == 'success') {
                                        $('#RejectDocument').modal('hide')
                                        $('#ManageSignatories').modal(
                                            'hide')
                                        getAllDocuments()
                                        Swal.fire({
                                            type: data.status,
                                            title: data.message,
                                            text: `${data.next_signee ? data.next_signee : ''}`,
                                            showCancelButton: false,
                                            confirmButtonColor: '#3085d6',
                                            cancelButtonColor: '#d33',
                                            confirmButtonText: 'Ok'
                                        })
                                    }

                                },
                                error: function(xhr, status, error) {
                                    console.log(xhr.responseJSON);
                                }
                            })
                        })
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseJSON);
                    }
                })
            })

            $(document).on('click', '.btn_close', function(event) {
                event.stopPropagation();
                var id = $(this).data('id')
                closeDocument(id)
            })


            $(document).on('click', '.btn_edit', function(event) {
                event.stopPropagation(); // Prevent the event from propagating to the parent <tr>
                var id = $(this).data('id');
                $('#modalTitle').text('Edit Signatories')
                $.ajax({
                    type: 'GET',
                    url: '{{ route('get.documenttracking') }}',
                    data: {
                        id: id,
                    },
                    success: function(data) {
                        console.log(data)
                        if (data.document_status == 'close') {
                            $('#manageSignatoryFooter').attr('hidden', true)
                        } else {
                            $('#manageSignatoryFooter').attr('hidden', true)
                        }
                        $('#document2').attr('disabled', true);
                        $('#documentType2').val(data.document_type_id).change().attr('disabled',
                            false)
                        $('#documentName2').val(data.document_name).attr('disabled', false)
                        $('#issuedBy2').val(data.issuedby_name).attr('disabled', true)
                        $('#dateCreated2').val(data.formatted_created_at).attr('disabled', true)
                        if (data.document_viewers != null) {
                            $('#selectViewer2').val(data.document_viewers.split(',')).change()
                                .attr(
                                    'disabled', false)
                        }
                        $('#selectSignee2').val(data.signees).change().attr('disabled', false)
                        $('#remarks2').val(data.document_remarks).attr('disabled', false)

                        if (data.picurl.length > 0) {
                            $('.carousel-inner').empty()
                            $('.carousel-indicators').empty()
                            $('.carousel-footer').empty()
                            data.picurl.forEach((url, index) => {
                                $('.carousel-indicators2').append(`
                                    <li data-target="#carouselExampleIndicators2" data-slide-to="${index}" class="${index === 0 ? 'active' : ''}"></li>
                                `);

                                $('.carousel-inner').append(`
                                    <div class="carousel-item ${index === 0 ? 'active' : ''}">
                                        <a href="${url.picurl}" target="_blank">
                                        <img class="d-block w-100" src="${url.picurl}" alt="slide pic" style="max-height: 600px;border-radius: 0px !important;">
                                        </a>
                                    </div>
                                `);

                                $('.carousel-footer').append(`
                                    <img class="d-block mx-1 carouselimages" 
                                    src="${url.picurl}" alt="image slide" 
                                    style="height: 70px; width: 70px;border-radius: 0px !important;" 
                                    data-target="#carouselExampleIndicators2" data-slide-to="${index}">
                                `);

                                $('#carousel2').attr('hidden', false);
                            });
                        }

                        // populate signatories
                        if (data.signeedetails.length > 0) {
                            $('#tb_signatories2').empty()
                            data.signeedetails.forEach((element, key) => {
                                $('#tb_signatories2').append(`
                                <div class="${element.status == "Forwarded" ? 'bg-success' : element.status == 'Onhand' ? 'bg-primary': element.status == 'Rejected' ? 'bg-danger' : 'bg-warning'} p-2 rounded mb-1">
                                    <div class="d-flex align-items-center">
                                        <strong>${key + 1}</strong> | Name
                                        <span class="ml-2">
                                            <i class="fas fa-user-circle mr-1"></i>
                                            ${element.name}
                                        </span>
                                        <span class="ml-auto">
                                            ${element.utype}
                                        </span>
                                    </div>
                                </div>
                                `)
                            });
                        } else {
                            $('#tb_signatories2').empty()
                            $('#tb_signatories2').append(
                                `<tr>
                                    <td class="text-center">No Selected Signee!
                                    </td>
                                </tr>`
                            )
                        }

                        $('#manageSignatoryFooter2').attr('hidden', false)
                        $('#viewer_wrapper').attr('hidden', false)
                        $('#signee_wrapper').attr('hidden', false)
                        $('#ManageSignatories').modal()

                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseJSON);
                    }
                })

            });

        });

        function updateDocument(id, signee) {
            $.ajax({
                type: 'POST',
                data: {
                    id: id,
                    document_signee: JSON.stringify(signee)
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('update.document') }}',
                success: function(data) {
                    console.log(data)
                    notify(data.status, data.message);
                    getAllDocuments()
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseJSON);
                }
            })
        }

        function closeDocument(id) {
            Swal.fire({
                type: 'question',
                title: 'Are you sure to close this Document ?',
                text: `You can't undo this process.`,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: 'GET',
                        data: {
                            id: id
                        },
                        url: '{{ route('close.document') }}',
                        success: function(data) {
                            console.log(data)
                            notify(data.status, data.message);
                            getAllDocuments()
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr.responseJSON);
                        }
                    })
                }
            });

        }

        function getAllDocuments() {
            $.ajax({
                type: 'GET',
                url: '/doctrack/getAllDocuments',
                success: function(data) {
                    console.log(data)
                    loaddocumentlist(data.alldocs)
                    loadOpenDocument(data.opendocs)
                    loadCloseDocument(data.closedocs)

                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseJSON);
                }
            })
        }

        function loaddocumentlist(data) {
            $('#countAllDocs').text(data.length);
            $('#documentList').empty();
            data.forEach(element => {
                $('#documentList').append(`
                <tr class="document-row" style="cursor: pointer;" data-toggle="collapse" data-target="#collapse-${element.id}" data-id="${element.id}" >
                    <td>
                        <div data-toggle="collapse" data-target="#collapse-${element.id}" >
                            <button class="btn btn-link"><i class="fas fa-file-image text-lg text-danger"></i></button>
                        </div>
                    </td>
                    <td>
                        <div >
                            <span>${element.doctype}</span>
                        </div>
                    </td>
                    <td>
                        <div >
                            <span> ${element.document_name}</span>
                        </div>
                    </td>
                    <td>
                        <div >
                            <span>${element.issuedby_name}</span>
                        </div>
                    </td>
                    <td>
                        <div >
                            <span>${element.formatted_created_at}</span>
                        </div>
                    </td>
                    <td>
                        <div>
                            ${element.document_status === 'open' ?
                            `<span class="rounded-badge text-success" style="font-weight: bold">Open</span>` :
                            `<span class="rounded-badge text-danger" style="font-weight: bold">Closed</span>`}
                        </div>
                    </td>

                    <td>
                        <div>
                            <span class="rounded-badge badge p-2 badge-primary btn_manage" data-id="${element.id}">${element.document_status == 'open' && element.privelege == 'editor' ? 'Manage' : 'View'}</span>
                            <span class="rounded-badge badge p-2 badge-danger btn_close " data-id="${element.id}" ${element.document_status == 'close' || element.privelege == 'viewer' ||  element.privelege == 'creator'  ? 'hidden' : '' } >Close</span>
                            <span class="rounded-badge badge p-2 badge-warning btn_edit" data-id="${element.id}" ${element.privelege == 'creator'  ? '' : 'hidden' } > ${element.privelege == 'creator'  ? 'Edit' : '' } </span>
                        </div>
                    </td>
                </tr>

                <tr class=" second_row">
                    <td colspan="7" class="p-0 m-0">
                        <div id="collapse-${element.id}" class="collapse">
                            <div class="timeline">
                                <div class="time-label">
                                    <span hidden></span>
                                </div>
                                ${populateTimeline(element)}
                            </div>
                        </div>
                    </td>
                </tr>
               
                `);
            });

        }

        function loadOpenDocument(data) {
            $('#countOpenDocs').text(data.length);
            $('#openDocumentList').empty();
            data.forEach((element, key) => {
                $('#openDocumentList').append(`
                <tr class="document-row" style="cursor: pointer;" data-toggle="collapse" data-target="#collapse-${element.id}${key}" >
                    <td>
                        <div>
                            <button class="btn btn-link"><i class="fas fa-file-image text-lg text-danger"></i></button>
                        </div>
                    </td>
                    <td>
                        <div >
                            <span>${element.doctype}</span>
                        </div>
                    </td>
                    <td>
                        <div >
                            <a href="#">${element.document_name}</a>
                        </div>
                    </td>
                    <td>
                        <div >
                            <span>${element.issuedby_name}</span>
                        </div>
                    </td>
                    <td>
                        <div >
                            <span>${element.formatted_created_at}</span>
                        </div>
                    </td>
                    <td>
                        <div>
                            <span class="rounded-badge ${element.document_status === 'open' ? 'text-success' : 'text-danger'}"  style="font-weight: bold">${element.document_status.charAt(0).toUpperCase() + element.document_status.slice(1)}</span>
                        </div>
                    </td>
                    <td>
                        <div>
                            <span class="rounded-badge badge p-2 badge-primary btn_manage" data-id="${element.id}">${element.document_status == 'open' && element.privelege == 'editor' ? 'Manage' : 'View'}</span>
                            <span class="rounded-badge badge p-2 badge-danger btn_close " data-id="${element.id}" ${element.document_status == 'close' || element.privelege == 'viewer' ||  element.privelege == 'creator'  ? 'hidden' : '' } >Close</span>
                        </div>
                    </td>
                </tr>

                <tr class="badge-light2 second_row">
                    <td colspan="7" class="p-0 m-0">
                        <div id="collapse-${element.id}${key}" class="collapse">
                            <div class="timeline">
                                <div class="time-label">
                                    <span hidden></span>
                                </div>
                                ${populateTimeline2(element)}
                            </div>
                        </div>
                    </td>
                </tr>
               
                `);
            });

        }

        function loadCloseDocument(data) {
            $('#countClosedDocs').text(data.length);
            $('#closeDocumentList').empty();
            data.forEach((element, key) => {
                $('#closeDocumentList').append(`
                <tr class="document-row " style="cursor: pointer;" data-toggle="collapse" data-target="#collapse-${element.id}${key}close" >
                    <td>
                        <div>
                            <button class="btn btn-link"><i class="fas fa-file-image text-lg text-danger"></i></button>
                        </div>
                    </td>
                    <td>
                        <div >
                            <span>${element.doctype}</span>
                        </div>
                    </td>
                    <td>
                        <div >
                            <a href="#">${element.document_name}</a>
                        </div>
                    </td>
                    <td>
                        <div >
                            <span>${element.issuedby_name}</span>
                        </div>
                    </td>
                    <td>
                        <div >
                            <span>${element.formatted_created_at}</span>
                        </div>
                    </td>
                    <td>
                        <div>
                            ${element.document_status === 'open' ?
                            `<span class="rounded-badge text-success" style="font-weight: bold">Open</span>` :
                            `<span class="rounded-badge text-danger" style="font-weight: bold">Closed</span>`}
                        </div>
                    </td>

                    <td>
                        <div>
                            <span class="rounded-badge badge p-2 badge-primary btn_manage" data-id="${element.id}">${element.document_status == 'open' ? 'Manage' : 'View'}</span>
                            <span class="rounded-badge badge p-2 badge-danger btn_close " data-id="${element.id}" ${element.document_status == 'close' ? 'hidden' : '' } >Close</span>
                        </div>
                    </td>
                </tr>

                <tr class="badge-light2 second_row">
                    <td colspan="7" class="p-0 m-0">
                        <div id="collapse-${element.id}${key}close" class="collapse">
                            <div class="timeline">
                                <div class="time-label">
                                    <span hidden></span>
                                </div>
                                ${populateTimeline3(element)}
                            </div>
                        </div>
                    </td>
                </tr>
               
                `);
            });

        }

        function populateTimeline(params) {
            var history = params.history;
            if (history.length > 0) {
                return history.map((item, key) => {
                    var renderHTML = `
                    <div key=${key}>
                        <i class="fas fa-hashtag text-white" style="background-color:#003687;"></i>
                        <div class="timeline-item bg-light">
                            <table class="table table-striped" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Signatory</th>
                                        <th>Sign Status</th>
                                        <th>Remarks</th>
                                        <th>Date Received</th>
                                        <th>Date Forwarded</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="width:220px;">
                                            <i class="fas fa-user-circle text-md mr-1"></i>
                                            <span>${item.status == 'Creator' ? item.forwarded_by_name : item.forwarded_to_name}</span>
                                        </td>
                                        <td>
                                            ${key == history.length - 1 && item.status == 'Forwarded' || key == history.length - 1 && params.document_status == 'close' ? 
                                                `<span class="badge p-2 badge-danger rounded-badge">Completed</span>` :
                                                `<span class="badge ${item.status == 'Rejected' ? 'badge-danger' : item.status == 'Pending' ? 'badge-warning' : item.status == 'Onhand' ?'badge-primary' : item.status == 'Forwarded' ? 'badge-success' : 'bg-purple' } p-2 rounded-badge">${item.status}</span>`
                                            }
                                        </td>
                                        <td>
                                            ${
                                            params.document_status == 'close' && key == history.length -1 ? 
                                            `<span class="badge p-2 badge-light rounded-badge">Completed transaction</span>` :
                                            item.status == 'Creator' ? 
                                            `<span class="badge p-2 badge-light rounded-badge">Forwarded to Signatory</span>` :
                                            item.status == 'Pending' ?
                                            `<span class="badge p-2 badge-light rounded-badge">${item.remarks ? item.remarks : 'Pending Signatory'}</span>` :
                                            item.status == 'Forwarded' && key < history.length - 1? 
                                            `<span class="badge p-2 badge-light rounded-badge">${item.remarks ? item.remarks : 'Forwarded to Next Signatory'}</span>` :
                                            item.status == 'Forwarded' && key == history.length - 1 ? 
                                            `<span class="badge p-2 badge-light rounded-badge">${item.remarks ? item.remarks : 'Completed transaction'}</span>` :

                                            `<span class="badge p-2 badge-light rounded-badge">${item.remarks ? item.remarks : 'Onhand transaction'}</span>` 
                                            }
                                        </td>
                                        <td>
                                            <span>${
                                                params.document_status == 'close' && key == history.length -1 ? 'Completed'
                                                 :  item.receiveddate ? item.formatted_receiveddate : 'Pending...'
                                            }</span>
                                        </td>
                                        <td>
                                            ${  params.document_status == 'close' && key == history.length -1 ? 'Completed' :
                                                item.status == 'Forwarded' && key == history.length - 1 ? 
                                                `<span>Completed</span>` :
                                                `<span>${item.forwarddate ? item.formatted_forwarddate : 'Pending...'}</span>`
                                            }
                                            
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>`;
                    return renderHTML;
                }).join(''); // Added .join('') to join the array elements into a single string
            } else {
                return ''; // Return an empty string if history is empty
            }
        }

        function populateTimeline2(params) {
            var history = params.history;
            if (history.length > 0) {
                return history.map((item, key) => {
                    var renderHTML = `
                    <div key=${key}>
                        <i class="fas fa-hashtag text-white" style="background-color:#003687;"></i>
                        <div class="timeline-item bg-light">
                            <table class="table table-striped" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Signatory</th>
                                        <th>Sign Status</th>
                                        <th>Remarks</th>
                                        <th>Date Received</th>
                                        <th>Date Forwarded</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="width:220px;">
                                            <i class="fas fa-user-circle text-md mr-1"></i>
                                            <span>${item.status == 'Creator' ? item.forwarded_by_name : item.forwarded_to_name}</span>
                                        </td>
                                        <td>
                                            ${key == history.length - 1 && item.status == 'Forwarded' || key == history.length - 1 && params.document_status == 'close' ? 
                                                `<span class="badge p-2 badge-danger rounded-badge">Completed</span>` :
                                                `<span class="badge ${item.status == 'Rejected' ? 'badge-danger' : item.status == 'Pending' ? 'badge-warning' : item.status == 'Onhand' ?'badge-primary' : item.status == 'Forwarded' ? 'badge-success' : 'bg-purple' } p-2 rounded-badge">${item.status}</span>`
                                            }
                                        </td>
                                        <td>
                                            ${item.status == 'Creator' ? 
                                            `<span class="badge p-2 badge-light rounded-badge">Forwarded to Signatory</span>` :
                                            item.status == 'Pending' ?
                                            `<span class="badge p-2 badge-light rounded-badge">${item.remarks ? item.remarks : 'Pending Signatory'}</span>` :
                                            item.status == 'Forwarded' && key < history.length - 1? 
                                            `<span class="badge p-2 badge-light rounded-badge">${item.remarks ? item.remarks : 'Forwarded to Next Signatory'}</span>` :
                                            item.status == 'Forwarded' && key == history.length - 1 ? 
                                            `<span class="badge p-2 badge-light rounded-badge">${item.remarks ? item.remarks : 'Completed transaction'}</span>` :

                                            `<span class="badge p-2 badge-light rounded-badge">${item.remarks ? item.remarks : 'Onhand transaction'}</span>` 
                                            }
                                        </td>
                                        <td>
                                            <span>${
                                                params.document_status == 'close' && key == history.length -1 ? 'Completed'
                                                 :  item.receiveddate ? item.formatted_receiveddate : 'Pending...'
                                            }</span>
                                        </td>
                                        <td>
                                            ${  params.document_status == 'close' && key == history.length -1 ? 'Completed' :
                                                item.status == 'Forwarded' && key == history.length - 1 ? 
                                                `<span>Completed</span>` :
                                                `<span>${item.forwarddate ? item.formatted_forwarddate : 'Pending...'}</span>`
                                            }
                                            
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>`;
                    return renderHTML;
                }).join(''); // Added .join('') to join the array elements into a single string
            } else {
                return ''; // Return an empty string if history is empty
            }
        }

        function populateTimeline3(params) {
            var history = params.history;
            if (history.length > 0) {
                return history.map((item, key) => {
                    var renderHTML = `
                    <div key=${key}>
                        <i class="fas fa-hashtag text-white" style="background-color:#003687;"></i>
                        <div class="timeline-item bg-light">
                            <table class="table table-striped" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Signatory</th>
                                        <th>Sign Status</th>
                                        <th>Remarks</th>
                                        <th>Date Received</th>
                                        <th>Date Forwarded</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="width:220px;">
                                            <i class="fas fa-user-circle text-md mr-1"></i>
                                            <span>${item.status == 'Creator' ? item.forwarded_by_name : item.forwarded_to_name}</span>
                                        </td>
                                        <td>
                                            ${key == history.length - 1 && item.status == 'Forwarded' || key == history.length - 1 && params.document_status == 'close' ? 
                                                `<span class="badge p-2 badge-danger rounded-badge">Completed</span>` :
                                                `<span class="badge ${item.status == 'Rejected' ? 'badge-danger' : item.status == 'Pending' ? 'badge-warning' : item.status == 'Onhand' ?'badge-primary' : item.status == 'Forwarded' ? 'badge-success' : 'bg-purple' } p-2 rounded-badge">${item.status}</span>`
                                            }
                                        </td>
                                        <td>
                                            ${item.status == 'Creator' ? 
                                            `<span class="badge p-2 badge-light rounded-badge">Forwarded to Signatory</span>` :
                                            item.status == 'Pending' ?
                                            `<span class="badge p-2 badge-light rounded-badge">${item.remarks ? item.remarks : 'Pending Signatory'}</span>` :
                                            item.status == 'Forwarded' && key < history.length - 1? 
                                            `<span class="badge p-2 badge-light rounded-badge">${item.remarks ? item.remarks : 'Forwarded to Next Signatory'}</span>` :
                                            item.status == 'Forwarded' && key == history.length - 1 ? 
                                            `<span class="badge p-2 badge-light rounded-badge">${item.remarks ? item.remarks : 'Completed transaction'}</span>` :

                                            `<span class="badge p-2 badge-light rounded-badge">${item.remarks ? item.remarks : 'Onhand transaction'}</span>` 
                                            }
                                        </td>
                                        <td>
                                            <span>${
                                                params.document_status == 'close' && key == history.length -1 ? 'Completed'
                                                 :  item.receiveddate ? item.formatted_receiveddate : 'Pending...'
                                            }</span>
                                        </td>
                                        <td>
                                            ${  params.document_status == 'close' && key == history.length -1 ? 'Completed' :
                                                item.status == 'Forwarded' && key == history.length - 1 ? 
                                                `<span>Completed</span>` :
                                                `<span>${item.forwarddate ? item.formatted_forwarddate : 'Pending...'}</span>`
                                            }
                                            
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>`;
                    return renderHTML;
                }).join(''); // Added .join('') to join the array elements into a single string
            } else {
                return ''; // Return an empty string if history is empty
            }
        }

        function store_document() {
            if (
                !validateInput(documentType, 'Document Type') ||
                !validateInput(documentName, 'Document Name') ||
                !validateInput(issuedBy, 'Issued By') ||
                !validateInput(dateCreated, 'Date Created') ||
                !validateInput(signee, 'Signee')
                // !validateInput(remarks, 'Remarks')
            ) {
                return;
            }

            // Get the input element for file selection
            var imageInputs = $('#document')[0].files;

            if (imageInputs.length == 0) {
                notify('error', 'Add atleast one File or Image!')
                return
            }
            // Create FormData object
            var formData = new FormData();
            formData.append('document_type_id', documentType.val().trim());
            formData.append('document_name', documentName.val().trim());
            formData.append('document_issuedby', issuedByID.val().trim());
            formData.append('document_createddate', dateCreated.val().trim());
            formData.append('document_viewers', viewers.val().join(','));
            formData.append('document_signee', JSON.stringify(signee.val()));
            formData.append('document_remarks', remarks.val() ? remarks.val().trim() : '');

            for (var i = 0; i < imageInputs.length; i++) {
                var file = imageInputs[i];
                console.log('File ' + (i + 1) + ':', file.name);
                formData.append('files[]', file);
            }

            console.log(formData)


            // Ajax request
            $.ajax({
                type: 'POST', // Assuming your route uses POST method
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('store.document') }}',
                success: function(data) {
                    console.log(data);
                    notify(data.status, data.message);
                    getAllDocuments()
                    $('#documentType').val('').change();
                    $('#documentName').val('');
                    $('#selectViewer').val('').change();
                    $('#selectSignee').val('').change();
                    $('#CreateSignatories').modal('hide')
                }
            });
        }

        function validateInput(value, fieldName) {
            if (fieldName == 'Signee') {
                var signee = value.val().join(',')
                if (!signee) {
                    value.addClass('is-invalid')
                    notify('error', `${fieldName} is required`);
                    return false;
                } else {
                    value.removeClass('is-invalid')
                }
            }
            if (fieldName == 'Viewers') {
                var viewer = value.val().join(',')
                if (!viewer) {
                    value.addClass('is-invalid')
                    notify('error', `${fieldName} is required`);
                    return false;
                } else {
                    value.removeClass('is-invalid')
                }
            }
            if (!value.val()) {
                value.addClass('is-invalid')
                notify('error', `${fieldName} is required`);
                return false;
            } else {
                value.removeClass('is-invalid')
            }
            return true;
        }

        function getalldoctype() {
            $.ajax({
                type: 'GET',
                url: '{{ route('getalldoctype') }}',
                success: function(response) {
                    console.log(response);
                    $('#documentType, #documentType2').select2({
                        data: response,
                        theme: 'bootstrap4',
                        allowClear: true,
                        placeholder: 'Select Type'
                    });

                    $('#selectViewer, #selectViewer2').select2({
                        theme: 'bootstrap4',
                        allowClear: true,
                        placeholder: 'Select Viewer',
                        multiple: true
                    });

                    $('#selectSignee, #edit-select-signee').select2({
                        theme: 'bootstrap4',
                        allowClear: true,
                        placeholder: 'Select Signee',
                        multiple: true
                    });

                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseJSON);
                }
            })
        }

        function notify(code, message) {
            Toast.fire({
                type: code,
                title: message,
            });

        }
    </script>
@endsection
