{{-- @extends('guidanceV2.layouts.app2')

@section('pagespecificscripts')
@endsection



@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Acknowledgement</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item "> <a href="/home">Home</a> </li>
                        <li class="breadcrumb-item "> <a href="/guidance/referral">Referral</a> </li>
                        <li class="breadcrumb-item active">Acknowledgement</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card shadow">
                <div class="card-header">
                    <h1 class="card-title"><strong>Referral Acknowledgement Slip</strong> </h1>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="mb-1">Date Received</label>
                            <input class="form-control" id="datereceived" type="date">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="mb-1">Received by</label>
                            <select name="" id="select_teacher" class="form-control" style="width:100%;">
                                <option value="">Select Person</option>
                                @foreach (DB::table('teacher')->where('deleted', 0)->get() as $item)
                                    <option value="{{ $item->id }}"> {{ $item->lastname }},
                                        {{ $item->firstname }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="mb-1">Position</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="form-group col-md-12">
                            <label class="mb-1">Remarks</label>
                            <textarea class="form-control" name="" id="" rows="10"></textarea>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="mb-1">Counseling Schedule</label>
                            <input class="form-control" id="counselingdate" type="date">
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-danger" id="cancelButton">Cancel</button>
                    <button type="button" class="btn btn-primary"><i class="far fa-paper-plane mr-1"></i>Submit</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footerjavascript')
    <script>
        $('#cancelButton').on('click', function() {
            window.history.back(); // Go back to the previous page
        });
    </script>
@endsection --}}
