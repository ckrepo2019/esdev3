@extends('principalsportal.layouts.app2')
@section('content')

<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Reports</h1>
                <h6>Total Number of Students</h6>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">Total Number of Students</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>
</section>
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-header">
                <i class="fa fa-filter"></i> Filter Students
            </div>
            <div class="card-body">
                <form action="/summarytotalnumberofstudents/filter" method="get">
                    <label>Select Category</label>
                    <select name="selectedcategory" class="form-control form-control-sm">
                        <option value="all" {{"all" == $selectedcategory ? 'selected' : ''}}>All</option>
                        <option value="1" {{"1" == $selectedcategory ? 'selected' : ''}}>Regular</option>
                        <option value="2" {{"2" == $selectedcategory ? 'selected' : ''}}>ESC Grantee</option>
                        <option value="3" {{"3" == $selectedcategory ? 'selected' : ''}}>Voucher</option>
                    </select>
                    <input type="hidden" name="selectedgender" value="{{$selectedgender}}"/>
                    <input type="hidden" name="selectedgradelevel" value="{{$selectedgradelevel}}"/>
                </form>
                <br>
                <form action="/summarytotalnumberofstudents/filter" method="get">
                    <label>Select Gradelevel</label>
                    <select name="selectedgradelevel" class="form-control form-control-sm">
                        <option value="all" {{"all" == $selectedgradelevel ? 'selected' : ''}}>All</option>
                        @foreach($gradelevels as $gradelevel)
                            <option value="{{$gradelevel->id}}" {{$gradelevel->id == $selectedgradelevel ? 'selected' : ''}}>{{$gradelevel->levelname}}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="selectedcategory" value="{{$selectedcategory}}"/>
                    <input type="hidden" name="selectedgender" value="{{$selectedgender}}"/>
                </form>
                <br>
                <form action="/summarytotalnumberofstudents/filter" method="get">
                    <label>Select Gender</label>
                    <select name="selectedgender" class="form-control form-control-sm">
                        <option value="all" {{"all" == $selectedgender ? 'selected' : ''}}>All</option>
                        <option value="male" {{"male" == $selectedgender ? 'selected' : ''}}>Male</option>
                        <option value="female" {{"female" == $selectedgender ? 'selected' : ''}}>Female</option>
                    </select>
                    <input type="hidden" name="selectedcategory" value="{{$selectedcategory}}"/>
                    <input type="hidden" name="selectedgradelevel" value="{{$selectedgradelevel}}"/>
                </form>
                <br>

                <button type="button" class="btn btn-block btn-sm btn-primary" data-toggle="modal" data-target="#selectprintclassification"><i class="fa fa-print"></i> Print</button>
                <div id="selectprintclassification" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title employeename"></h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- <label>Title</label> -->
                                <form action="/summarytotalnumberofstudents/print" method="get" target="_blank">
                                    <input type="hidden" name="displaytype" value="grantee"/>
                                    <input type="hidden" name="selectedcategory" value="{{$selectedcategory}}"/>
                                    <input type="hidden" name="selectedgender" value="{{$selectedgender}}"/>
                                    <input type="hidden" name="selectedgradelevel" value="{{$selectedgradelevel}}"/>
                                    <button type="submit" class="btn btn-block btn-sm btn-default">By Grantee</button>
                                </form>
                                <br>
                                <form action="/summarytotalnumberofstudents/print" method="get" target="_blank">
                                    <input type="hidden" name="displaytype" value="gradelevel"/>
                                    <input type="hidden" name="selectedcategory" value="{{$selectedcategory}}"/>
                                    <input type="hidden" name="selectedgender" value="{{$selectedgender}}"/>
                                    <input type="hidden" name="selectedgradelevel" value="{{$selectedgradelevel}}"/>
                                    <button type="submit" class="btn btn-block btn-sm btn-default">By Grade Level</button>
                                </form>
                                <br>
                                <form action="/summarytotalnumberofstudents/print" method="get" target="_blank">
                                    <input type="hidden" name="displaytype" value="gender"/>
                                    <input type="hidden" name="selectedcategory" value="{{$selectedcategory}}"/>
                                    <input type="hidden" name="selectedgender" value="{{$selectedgender}}"/>
                                    <input type="hidden" name="selectedgradelevel" value="{{$selectedgradelevel}}"/>
                                    <button type="submit" class="btn btn-block btn-sm btn-default">By Gender</button>
                                </form>
                                <br>
                                <form action="/summarytotalnumberofstudents/print" method="get" target="_blank">
                                    <input type="hidden" name="displaytype" value="all"/>
                                    <input type="hidden" name="selectedcategory" value="{{$selectedcategory}}"/>
                                    <input type="hidden" name="selectedgender" value="{{$selectedgender}}"/>
                                    <input type="hidden" name="selectedgradelevel" value="{{$selectedgradelevel}}"/>
                                    <button type="submit" class="btn btn-block btn-sm btn-default">List of All Students</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Grade level</th>
                        </tr>
                    </thead>
                    <tbody class="studentscontainer text-uppercase">
                        @if(isset($regularstudents))
                            @foreach($regularstudents as $regularstudent)
                                <tr>
                                    <td>
                                        <span class="right badge badge-primary">
                                            @if($regularstudent->grantee == '1')
                                            REGULAR
                                            @elseif($regularstudent->grantee == '2')
                                            ESC GRANTEE
                                            @elseif($regularstudent->grantee == '3')
                                            VOUCHER
                                            @endif
                                        </span>
                                        {{$regularstudent->lastname}}, {{$regularstudent->firstname}} {{$regularstudent->middlename[0].'.'}} {{$regularstudent->suffix}}
                                    </td>
                                    <td>
                                        {{$regularstudent->gender}}
                                    </td>
                                    <td>
                                        {{$regularstudent->levelname}}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        @if(isset($escstudents))
                            @foreach($escstudents as $escstudent)
                                <tr>
                                    <td>
                                        <span class="right badge badge-warning">
                                            @if($escstudent->grantee == '1')
                                            REGULAR
                                            @elseif($escstudent->grantee == '2')
                                            ESC GRANTEE
                                            @elseif($escstudent->grantee == '3')
                                            VOUCHER
                                            @endif
                                        </span>
                                        {{$escstudent->lastname}}, {{$escstudent->firstname}} {{$escstudent->middlename[0].'.'}} {{$escstudent->suffix}}
                                    </td>
                                    <td>
                                        {{$escstudent->gender}}
                                    </td>
                                    <td>
                                        {{$escstudent->levelname}}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        @if(isset($voucherstudents))
                            @foreach($voucherstudents as $voucherstudent)
                                <tr>
                                    <td>
                                        <span class="right badge badge-success">
                                            @if($voucherstudent->grantee == '1')
                                            REGULAR
                                            @elseif($voucherstudent->grantee == '2')
                                            ESC GRANTEE
                                            @elseif($voucherstudent->grantee == '3')
                                            VOUCHER
                                            @endif
                                        </span>
                                        {{$voucherstudent->lastname}}, {{$voucherstudent->firstname}} {{$voucherstudent->middlename[0].'.'}} {{$voucherstudent->suffix}}
                                    </td>
                                    <td>
                                        {{$voucherstudent->gender}}
                                    </td>
                                    <td>
                                        {{$voucherstudent->levelname}}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{asset('assets/scripts/main.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<script>

    $(function () {
        $("#example1").DataTable({
            pageLength : 10,
            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Show All']]
        });
    });

    $(document).on('change','select[ name="selectedcategory"]', function(){
        $(this).closest('form').submit();
    });

    $(document).on('change','select[ name="selectedgradelevel"]', function(){
        $(this).closest('form').submit();
    });

    $(document).on('change','select[ name="selectedgender"]', function(){
        $(this).closest('form').submit();
    });
</script>
@endsection