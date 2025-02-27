@extends('finance.layouts.app')

@section('content')
<!-- Select2 -->
{{-- <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
<style>
    
    .select2-container {
            z-index: 9999;
            margin: 0px;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #007bff;
    border-color: #006fe6;
    color: #fff;
    padding: 0 10px;
    margin-top: .31rem;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: rgba(255,255,255,.7);
    float: right;
    margin-left: 5px;
    margin-right: -2px;
}
</style> --}}
<div class="row form-group">
    <div class="col-md-12">
        <h3 class="">Accounting Setup</h3>
    </div>
</div>
<div class="row form-group">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary">
                
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <button id="asgradelevel" class="btn btn-primary btn-block">
                            Grade Level
                        </button>
                    </div>  
                    <div class="col-md-2">
                        <button id="asoa" class="btn btn-secondary btn-block">
                            Old Accounts
                        </button>
                    </div>  
                    <div class="col-md-2">
                        <button id="ascashier" class="btn btn-warning btn-block">
                            Cashier
                        </button>
                    </div>  
                    <div class="col-md-2">
                        <button id="aspayroll" class="btn btn-info btn-block">
                            Payroll
                        </button>
                    </div>  
                    <div class="col-md-2">
                        <button id="asbank" class="btn btn-danger btn-block">
                            Bank
                        </button>
                    </div>  
                    <div class="col-md-2">
                        <button id="asexpenses" class="btn btn-dark btn-block">
                            Expenses
                        </button>
                    </div>  
                    <div class="col-md-4 pt-1">
                        <button id="asitems" class="btn btn-info btn-block">
                            Expenses/Disbursement Items
                        </button>
                    </div>  
                    <div class="col-md-2 pt-1">
                        <button id="assupplier" class="btn btn-danger btn-block">
                            Supplier
                        </button>
                    </div>  
                </div>
            </div>
        </div>
    </div>
</div>
@if(db::table('schoolinfo')->first()->accountingmodule == 1)
    <div class="row form-group">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary">
                    
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div id="setup_coa" class="small-box bg-primary p-4">
                                <h4> Chart of <br> Accounts </h4>
                                <div class="icon">
                                    <i class="fas fa-th-list"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div id="setup_mapping" class="small-box bg-info p-4">
                                <h4> Mapping <br> &nbsp; </h4>
                                <div class="icon">
                                    <i class="fas fa-layer-group"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="row form-group">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary">
                    <h4>Accounting Report Setup</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div id="setup_is" class="small-box bg-success p-4">
                                <h4> Income <br> Statement </h4>
                                <div class="icon">
                                    <i class="fas fa-hand-holding-usd"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div id="setup_bs" class="small-box bg-pink p-4">
                                <h4> Balance <br> Sheet </h4>
                                <div class="icon">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
@endif
@endsection
@section('modal')
    <div class="modal fade show" id="modal-gradelevel" aria-modal="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title">Grade Level Setup</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-1 float-right">
                          
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                          <table width="100%" id="level_data" class="table table-hover table-sm text-sm display nowrap"  style="">
                            <thead>
                                <th>Level</th>
                                <th>First Sem</th>
                                <th>2nd Sem</th>
                                <th>Summer</th>
                            </thead>
                            <tbody id="level_list" style="cursor: pointer;">
                              
                            </tbody>
                          </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer float-right">
                    <div class="">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                          Close
                        </button>
                    </div>
                    <div>
                        {{-- <button id="btndisapprove" type="button" class="btn btn-danger" data-dismiss="modal" data-toggle="tooltip" title="Disapprove">
                          <i class="fas fa-thumbs-down"></i>
                        </button>
                        <button id="btnapprove" type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="tooltip" title="Approve">
                          <i class="fas fa-thumbs-up"></i>
                        </button> --}}
                    </div>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>
    <div class="modal fade show" id="modal-editlevel" aria-modal="true" style="display: none;">
        <div class="modal-dialog modal-md">
            <div class="modal-content" style="margin-top: 13em;">
                <div class="modal-header bg-primary">
                    <h4 id="level_name" class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row form-group">
                        <div class="col-md-12">
                            <label>First Semester</label>
                            <select id="gl_1" class="select2" style="width: 100% !important;">
                                <option value="0">Select GL Account</option>
                                @foreach(db::table('acc_coa')->where('deleted', 0)->orderBy('code')->orderBy('account')->get() as $gl)
                                    <option value="{{$gl->id}}">{{$gl->code}} - {{$gl->account}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-12">
                            <label>Second Semester</label>
                            <select id="gl_2" class="select2" style="width: 100% !important;">
                                <option value="0">Select GL Account</option>
                                @foreach(db::table('acc_coa')->where('deleted', 0)->orderBy('code')->orderBy('account')->get() as $gl)
                                    <option value="{{$gl->id}}">{{$gl->code}} - {{$gl->account}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-12">
                            <label>Summer</label>
                            <select id="gl_3" class="select2" style="width: 100% !important;">
                                <option value="0">Select GL Account</option>
                                @foreach(db::table('acc_coa')->where('deleted', 0)->orderBy('code')->orderBy('account')->get() as $gl)
                                    <option value="{{$gl->id}}">{{$gl->code}} - {{$gl->account}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label>Credit</label>
                            <select id="gl_credit" class="select2" style="width: 100% !important;">
                                <option value="0">Select Account</option>
                                @foreach(db::table('acc_coa')->where('deleted', 0)->orderBy('code')->orderBy('account')->get() as $gl)
                                    <option value="{{$gl->id}}">{{$gl->code}} - {{$gl->account}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer float-right">
                    <div class="">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                          Close
                        </button>
                    </div>
                    <div>
                        {{-- <button id="btndisapprove" type="button" class="btn btn-danger" data-dismiss="modal" data-toggle="tooltip" title="Disapprove">
                          <i class="fas fa-thumbs-down"></i>
                        </button> --}}
                        <button id="editlevel_save" type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="tooltip" title="Save">
                          Save
                        </button>
                    </div>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade show" id="modal-editoa" aria-modal="true" style="display: none;">
        <div class="modal-dialog modal-md">
            <div class="modal-content" style="margin-top: 13em;">
                <div class="modal-header bg-primary">
                    <h4 id="old_name" class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row form-group">
                        <div class="col-md-12">
                            <select id="oa_gl" class="select2" style="width: 100% !important;">
                                <option value="0">Select GL Account</option>
                                @foreach(db::table('acc_coa')->where('deleted', 0)->orderBy('code')->orderBy('account')->get() as $gl)
                                    <option value="{{$gl->id}}">{{$gl->code}} - {{$gl->account}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer float-right">
                    <div class="">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                          Close
                        </button>
                    </div>
                    <div>
                        {{-- <button id="btndisapprove" type="button" class="btn btn-danger" data-dismiss="modal" data-toggle="tooltip" title="Disapprove">
                          <i class="fas fa-thumbs-down"></i>
                        </button> --}}
                        <button id="editoa_save" type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="tooltip" title="Save">
                          Save
                        </button>
                    </div>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade" id="modal-editcashier" aria-modal="true" style="display: none;">
        <div class="modal-dialog modal-md">
            <div class="modal-content" style="margin-top: 13em;">
                <div class="modal-header bg-primary">
                    <h4 id="old_name" class="modal-title">Cashier Setup</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row form-group">
                        <div class="col-md-12">
                            <select id="cashier_gl" class="select2" style="width: 100% !important;">
                                <option value="0">Select GL Account</option>
                                @foreach(db::table('acc_coa')->where('deleted', 0)->orderBy('code')->orderBy('account')->get() as $gl)
                                    <option value="{{$gl->id}}">{{$gl->code}} - {{$gl->account}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer float-right">
                    <div class="">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                          Close
                        </button>
                    </div>
                    <div>
                        {{-- <button id="btndisapprove" type="button" class="btn btn-danger" data-dismiss="modal" data-toggle="tooltip" title="Disapprove">
                          <i class="fas fa-thumbs-down"></i>
                        </button> --}}
                        <button id="editcashier_save" type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="tooltip" title="Save">
                          Save
                        </button>
                    </div>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>
    <div class="modal fade" id="modal-aspayroll" aria-modal="true" style="display: none;">
        <div class="modal-dialog modal-md">
            <div class="modal-content" style="margin-top: 13em;">
                <div class="modal-header bg-primary">
                    <h4 id="old_name" class="modal-title">Payroll Setup</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row form-group">
                        <div class="col-md-12">
                            <label>Debit</label>
                            <select id="payroll_gldebit" class="select2" style="width: 100% !important;">
                                <option value="0">Select GL Account</option>
                                @foreach(db::table('acc_coa')->where('deleted', 0)->orderBy('code')->orderBy('account')->get() as $gl)
                                    <option value="{{$gl->id}}">{{$gl->code}} - {{$gl->account}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-md-12">
                            <label>Credit</label>
                            <select id="payroll_glcredit" class="select2" style="width: 100% !important;">
                                <option value="0">Select GL Account</option>
                                @foreach(db::table('acc_coa')->where('deleted', 0)->orderBy('code')->orderBy('account')->get() as $gl)
                                    <option value="{{$gl->id}}">{{$gl->code}} - {{$gl->account}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer float-right">
                    <div class="">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                          Close
                        </button>
                    </div>
                    <div>
                        {{-- <button id="btndisapprove" type="button" class="btn btn-danger" data-dismiss="modal" data-toggle="tooltip" title="Disapprove">
                          <i class="fas fa-thumbs-down"></i>
                        </button> --}}
                        <button id="payroll_save" type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="tooltip" title="Save">
                          Save
                        </button>
                    </div>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade show" id="modal-asexpenses" aria-modal="true" style="display: none;">
        <div class="modal-dialog modal-md">
            <div class="modal-content" style="margin-top: 13em;">
                <div class="modal-header bg-primary">
                    <h4 id="old_name" class="modal-title">Expenses Setup</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row form-group">
                        <div class="col-md-12">
                            <label>Credit</label>
                            <select id="expense_glcredit" class="select2" style="width: 100% !important;">
                                <option value="0">Select GL Account</option>
                                @foreach(db::table('acc_coa')->where('deleted', 0)->orderBy('code')->orderBy('account')->get() as $gl)
                                    <option value="{{$gl->id}}">{{$gl->code}} - {{$gl->account}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer float-right">
                    <div class="">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                          Close
                        </button>
                    </div>
                    <div>
                        {{-- <button id="btndisapprove" type="button" class="btn btn-danger" data-dismiss="modal" data-toggle="tooltip" title="Disapprove">
                          <i class="fas fa-thumbs-down"></i>
                        </button> --}}
                        <button id="expense_save" type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="tooltip" title="Save">
                          Save
                        </button>
                    </div>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade show" id="modal-setup_is" aria-modal="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h4 class="modal-title">Income Statement Setup</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-1 float-right">
                          
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 table-responsive" style="height: 15em">
                            <label>Revenue</label>
                            <table width="100%" id="" class="table table-striped table-sm text-sm display nowrap"  style="">
                                <thead>
                                    <th>Account</th>
                                    <th class="text-right" style="width: 20em">
                                        <button id="is_revenue_add" class="btn btn-sm btn-primary" style="width: 7em">Add</button>
                                    </th>
                                </thead>
                                <tbody id="is_revenue_list" style="cursor: pointer;">
                                
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 table-responsive" style="height: 15em">
                            <label>Expenses</label>
                            <table width="100%" id="level_data" class="table table-striped table-sm text-sm display nowrap"  style="">
                                <thead>
                                    <th>Account</th>
                                    <th class="text-right" style="width: 20em">
                                        <button id="is_expense_add" class="btn btn-sm btn-primary" style="width: 7em">Add</button>
                                    </th>
                                </thead>
                                <tbody id="is_expense_list" style="cursor: pointer;">
                                
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer float-right">
                    <div class="">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                          Close
                        </button>
                    </div>
                    <div>
                        {{-- <button id="btndisapprove" type="button" class="btn btn-danger" data-dismiss="modal" data-toggle="tooltip" title="Disapprove">
                          <i class="fas fa-thumbs-down"></i>
                        </button>
                        <button id="btnapprove" type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="tooltip" title="Approve">
                          <i class="fas fa-thumbs-up"></i>
                        </button> --}}
                    </div>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade show" id="modal-setup_isdetail" aria-modal="true" style="display: none;">
        <div class="modal-dialog modal-md mt-5">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h4 class="modal-title">Income Statement Setup Detail</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row form-group">
                        <div id="" class="col-md-6">
                            <div id="is_detail_viewmap">
                                <label>
                                    Mapping
                                </label>
                                <select id="is_detail_map" class="select2" style="width: 100%">
                                    @foreach(db::table('acc_map')->where('deleted', 0)->get() as $map)
                                        <option value="{{$map->id}}">{{$map->mapname}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div id="is_detail_viewheader" style="display: none">
                                <label>
                                    Header Title
                                </label>
                                {{-- <select id="is_gl" class="select2" style="width: 100%">
                                    @foreach(db::table('acc_map')->where('deleted', 0)->get() as $map)
                                        <option value="{{$map->id}}">{{$map->mapname}}</option>
                                    @endforeach
                                </select> --}}

                                <input id="is_detail_title" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label>Sort</label>
                            <input id="is_detail_sort" type="number" class="form-control" placeholder="0">
                        </div>
                        <div class="col-md-3" style="margin-top: 2.5em">
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="is_detail_header">
                                    <label for="is_detail_header">
                                        Header
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        
                    </div>
                </div>
                <div class="modal-footer float-right">
                    <div class="">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                          Close
                        </button>
                    </div>
                    <div>
                        <button id="is_delete" type="button" class="btn btn-danger" data-toggle="tooltip" title="Delete">
                          Delete
                        </button>
                        <button id="is_save" data-id="0" type="button" class="btn btn-primary" data-toggle="tooltip" title="Save">
                          Save
                        </button>
                    </div>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

    

    <div class="modal fade show" id="modal-setup_bs" aria-modal="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-pink">
                    <h4 class="modal-title">Balance Sheet Setup</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-1 float-right">
                          
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 table-responsive" style="height: 11em">
                            <label>Assets</label>
                            <table width="100%" id="" class="table table-striped table-sm text-sm display nowrap"  style="">
                                <thead>
                                    <th>Account</th>
                                    <th class="text-right" style="width: 20em">
                                        <button id="bs_assets_add" class="btn btn-sm btn-primary" style="width: 7em">Add</button>
                                    </th>
                                </thead>
                                <tbody id="bs_assets_list" style="cursor: pointer;">
                                
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 table-responsive" style="height: 11em">
                            <label>Liabilities</label>
                            <table width="100%" id="level_data" class="table table-striped table-sm text-sm display nowrap"  style="">
                                <thead>
                                    <th>Account</th>
                                    <th class="text-right" style="width: 20em">
                                        <button id="bs_liabilities_add" class="btn btn-sm btn-primary" style="width: 7em">Add</button>
                                    </th>
                                </thead>
                                <tbody id="bs_liabilities_list" style="cursor: pointer;">
                                
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 table-responsive" style="height: 11em">
                            <label>Equity</label>
                            <table width="100%" id="level_data" class="table table-striped table-sm text-sm display nowrap"  style="">
                                <thead>
                                    <th>Account</th>
                                    <th class="text-right" style="width: 20em">
                                        <button id="bs_equity_add" class="btn btn-sm btn-primary" style="width: 7em">Add</button>
                                    </th>
                                </thead>
                                <tbody id="bs_equity_list" style="cursor: pointer;">
                                
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer float-right">
                    <div class="">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                          Close
                        </button>
                    </div>
                    <div>
                        {{-- <button id="btndisapprove" type="button" class="btn btn-danger" data-dismiss="modal" data-toggle="tooltip" title="Disapprove">
                          <i class="fas fa-thumbs-down"></i>
                        </button>
                        <button id="btnapprove" type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="tooltip" title="Approve">
                          <i class="fas fa-thumbs-up"></i>
                        </button> --}}
                    </div>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade" id="modal-setup_bsdetail" aria-modal="true" style="display: none;">
        <div class="modal-dialog modal-md mt-5">
            <div class="modal-content">
                <div class="modal-header bg-pink">
                    <h4 class="modal-title">Balance Sheet Setup Detail</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row form-group">
                        <div id="" class="col-md-6">
                            <div id="bs_detail_viewmap">
                                <label>
                                    Mapping
                                </label>
                                <select id="bs_detail_map" class="select2" style="width: 100%">
                                    @foreach(db::table('acc_map')->where('deleted', 0)->get() as $map)
                                        <option value="{{$map->id}}">{{$map->mapname}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div id="bs_detail_viewheader" style="display: none">
                                <label>
                                    Header Title
                                </label>
                                {{-- <select id="is_gl" class="select2" style="width: 100%">
                                    @foreach(db::table('acc_map')->where('deleted', 0)->get() as $map)
                                        <option value="{{$map->id}}">{{$map->mapname}}</option>
                                    @endforeach
                                </select> --}}

                                <input id="bs_detail_title" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label>Sort</label>
                            <input id="bs_detail_sort" type="number" class="form-control" placeholder="0">
                        </div>
                        <div class="col-md-3" style="margin-top: 2.5em">
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="bs_detail_header">
                                    <label for="bs_detail_header">
                                        Header
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        
                    </div>
                </div>
                <div class="modal-footer float-right">
                    <div class="">
                        <button id="bs_closemodal" type="button" class="btn btn-default" data-dismiss="modal">
                          Close
                        </button>
                    </div>
                    <div>
                        <button id="bs_delete" type="button" class="btn btn-danger" data-toggle="tooltip" title="Delete">
                          Delete
                        </button>
                        <button id="bs_save" data-id="0" type="button" class="btn btn-primary" data-toggle="tooltip" title="Save">
                          Save
                        </button>
                    </div>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade show" id="modal-bank" aria-modal="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h4 class="modal-title">Bank Setup</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-1 float-right">
                          
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-12 text-right"><button id="bank_add" class="btn btn-primary btn-sm text-sm">Add Bank</button></div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 table-responsive" style="height: 22em;">
                            <table width="100%" id="level_data" class="table table-hover table-sm text-sm display nowrap"  style="">
                                <thead>
                                    <th>Bank Name</th>
                                    <th>Account No.</th>
                                    <th>Branch</th>
                                    <th>Address</th>
                                </thead>
                                <tbody id="bank_list" style="cursor: pointer;">
                              
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer float-right">
                    <div class="">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                          Close
                        </button>
                    </div>
                    <div>
                        {{-- <button id="btndisapprove" type="button" class="btn btn-danger" data-dismiss="modal" data-toggle="tooltip" title="Disapprove">
                          <i class="fas fa-thumbs-down"></i>
                        </button>
                        <button id="btnapprove" type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="tooltip" title="Approve">
                          <i class="fas fa-thumbs-up"></i>
                        </button> --}}
                    </div>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>
    <div class="modal fade show" id="modal-bankedit" aria-modal="true" style="display: none;">
        <div class="modal-dialog modal-md">
            <div class="modal-content" style="margin-top: 4em;">
                <div class="modal-header bg-danger">
                    <h4 id="old_name" class="modal-title">Bank Setup</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row form-group">
                        <div class="col-md-12">
                            <label>Bank Name</label>
                            <input type="" name="" id="bank_name" class="form-control">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-12">
                            <label>Account Number</label>
                            <input type="" name="" id="bank_accno" class="form-control">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-12">
                            <label>Branch</label>
                            <input type="" name="" id="bank_branch" class="form-control">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-12">
                            <label>Address</label>
                            <input type="" name="" id="bank_address" class="form-control">
                        </div>
                    </div>

                </div>
                <div class="modal-footer float-right">
                    <div class="">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                          Close
                        </button>
                    </div>
                    <div>
                        <button id="bank_remove" type="button" class="btn btn-danger">
                          {{-- <i class="fas fa-thumbs-down"></i> --}} Delete
                        </button>
                        <button id="bank_save" type="button" class="btn btn-primary" data-id="0">
                          Save
                        </button>
                    </div>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade show" id="modal-asitems" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title">Items</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body text-sm">
                    <div id="div_items" class="row" style="display: block">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-7">
                                    </div>
                                    <div class="col-md-5">
                                        <div class="input-group mb-2">
                                            <input id="item_filter" type="text" class="form-control" placeholder="Search Items" onkeyup="this.value = this.value.toUpperCase();">
                                            <div class="input-group-append">
                                                <button id="item_create" class="btn btn-primary">Create</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 table-responsive table_setup" style="height: 18em">
                                        <table width="100%" id="" class="table table-hover table-sm text-sm display nowrap" style="">
                                            <thead>
                                                <tr>
                                                    <th>Item Code</th>
                                                    <th>Item Name</th>
                                                    <th>Item Quantity</th>
                                                </tr>
                                            </thead>
                                            <tbody id="items_list" style="cursor: pointer;"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>  
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade show" id="modal-itemdetail" aria-modal="true" style="padding-right: 17px; margin-top:1em; display: none;">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title">Items</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body text-sm" style="overflow:auto; max-height: 350px">
                    <form class="form-horizontal">
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="class-desc" class="col-sm-3 col-form-label">Code</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm" id="item_code" placeholder="" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-desc" class="col-sm-3 col-form-label">Description</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm item_val" id="item_description" placeholder="" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-desc" class="col-sm-3 col-form-label">Amount</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control form-control-sm text-right" id="item_cost" placeholder="0.00" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-desc" class="col-sm-3 col-form-label">Classification</label>
                                <div class="col-sm-9">
                                    <select id="item_class" class="select2" style="width: 100%">
                                        <option value="0">SELECT CLASSIFICATION</option>
                                        @foreach(db::table('itemclassification')->where('deleted', 0)->orderBy('description')->get() as $class)
                                        <option value="{{$class->id}}">{{$class->description}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-desc" class="col-sm-3 col-form-label">Account</label>
                                <div class="col-sm-9">
                                    <select id="item_account" class="select2" style="width: 100%">
                                        <option value="0">SELECT ACCOUNT</option>
                                        @foreach(db::table('acc_coa')->where('deleted', 0)->orderBy('code')->get() as $coa)
                                        <option value="{{$coa->id}}">{{$coa->code}} - {{$coa->account}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-desc" class="col-sm-3 col-form-label">Item Type</label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="icheck-primary d-inline">
                                                <input type="radio" id="non_inventory" name="inventory" checked>
                                                <label for="non_inventory">Non Inventory
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="icheck-primary d-inline">
                                                <input type="radio" id="inventory" name="inventory">
                                                <label for="inventory">Inventory
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row" style="display: none" id="qtyContainer">
                                <label for="class-desc" class="col-sm-3 col-form-label">Quantity</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control form-control-sm" id="item_quantity" placeholder="0" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                </div>
                                <label for="class-desc" class="col-sm-3 col-form-label">Min Qty</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control form-control-sm" id="item_min_quantity" placeholder="0" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="p-3">
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                        <div class="col-md-8 text-right">
                        <button id="item_delete" type="button" class="btn btn-danger" style="display: none">Delete</button>
                        <button id="item_save" type="button" class="btn btn-primary" data-id="0">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>
    
    <div class="modal fade show" id="modal-assupplier" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h4 class="modal-title">Supplier Masterlist</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body text-sm">
                
                    <br>
                    <div id='div_supplier' class="row" style="display: block">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-7">
                                        
                                    </div>
                                    <div class="col-md-5">
                                        <div class="input-group mb-2">
                                            <input id="sup_filter" type="text" class="form-control" placeholder="Search Supplier" onkeyup="this.value = this.value.toUpperCase();">
                                            <div class="input-group-append">
                                                {{-- <span class="input-group-text"><i class="fas fa-search"></i></span> --}}
                                                <button id="sup_create" class="btn btn-primary">Create</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 table-responsive table_setup" style="height: 18em;">
                                        <table class="table table-sm text-sm table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Supplier Name</th>
                                                    <th>Contact No.</th>
                                                    <th>Address</th>
                                                </tr>
                                            </thead>
                                            <tbody id="supplier_list" style="cursor: pointer;"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade show" id="modal-supplierdetail" aria-modal="true" style="padding-right: 17px; margin-top:5em; display: none;">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h4 class="modal-title">Supplier</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body text-sm">
                    <form class="form-horizontal">
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="class-desc" class="col-sm-3 col-form-label">Supplier</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm validation" id="sup_name" placeholder="Pay To" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-desc" class="col-sm-3 col-form-label">Address</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm validation" id="sup_address" placeholder="Address" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-desc" class="col-sm-3 col-form-label">Contact No.</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm validation" id="sup_contactno" placeholder="Contact Number" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-gl" class="col-sm-3 col-form-label">Account</label>
                                <div class="col-sm-9">
                                    <select id="sup_gl" class="select2 form->control">
                                        <option value="0">SELECT ACCOUNTS</option>
                                        @foreach(db::table('acc_coa')->where('deleted', 0)->get() as $coa)
                                            <option value="{{$coa->id}}">{{$coa->code}} - {{$coa->account}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="p-3">
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                        <div class="col-md-8 text-right">
                            <button id="sup_delete" type="button" class="btn btn-danger" style="display: none">Delete</button>
                            <button id="sup_save" type="button" class="btn btn-primary" data-id="0">Save</button>
                        </div>
                    </div>        
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

    
@endsection
@section('jsUP')
    <style>
        <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
    </style>
@endsection
@section('js')
<script >
    $(document).ready(function(){
        $('.select2').select2({
            theme: 'bootstrap4'
        });

        var rpttype = '';

        function getlevel()
        {
            $.ajax({
                url: '{{route('accsetup_loadlevel')}}',
                type: 'GET',
                dataType: 'json',
                // data: {param1: 'value1'},
                success:function(data)
                {
                    // var level = data;
                    // console.log(data);

                    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                        $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
                    });

                    $('#level_data').DataTable({
                        paging: false,
                        lengthChange: false,
                        searching: true,
                        ordering: false,
                        info: false,
                        autoWidth: true,
                        responsive: true,
                        paging:false,
                        scrollY: '280px',
                        destroy:true,
                        // scrollX: true,
                        scrollCollapse:true,
                        data:data,
                        columns:[
                            {data: 'levelname'},
                            {data: 'account1'},
                            {data: 'account2'},
                            {data: 'account3'},  
                        ],
                        columnDefs: [{
                            'targets': 0,
                            'orderable': false, 
                            'createdCell':  function (td, cellData, rowData, row, col) 
                            {
                                
                                // $(td).attr('id', rowData.id)
                                $(td).text(rowData.levelname)

                            }
                        },
                        {
                            'targets': 1,
                            'orderable': false, 
                            'createdCell':  function (td, cellData, rowData, row, col) 
                            {       
                                // console.log(rowData)                          
                                // $(td).attr('id', rowData.id)
                                if(rowData.code1 == null || rowData.account1 == null)
                                {
                                    $(td).text('')
                                }
                                else
                                {
                                    $(td).text(rowData.code1 + ' - ' + rowData.account1)
                                }

                            }
                        },
                        {
                            'targets': 2,
                            'orderable': false, 
                            'createdCell':  function (td, cellData, rowData, row, col) 
                            {       
                                // console.log(rowData)                          
                                $(td).attr('id', rowData.id)
                                if(rowData.code2 == null || rowData.account2 == null)
                                {
                                    $(td).text('')
                                }
                                else
                                {
                                    $(td).text(rowData.code2 + ' - ' + rowData.account2)
                                }

                            }
                        },
                        {
                            'targets': 3,
                            'orderable': false, 
                            'createdCell':  function (td, cellData, rowData, row, col) 
                            {       
                                // console.log(rowData)                          
                                $(td).attr('id', rowData.id)
                                if(rowData.code3 == null || rowData.account3 == null)
                                {
                                    $(td).text('')
                                }
                                else
                                {
                                    $(td).text(rowData.code3 + ' - ' + rowData.account3)
                                }

                            }
                        }],
                        createdRow: function (row, data, dataIndex) {        
                          $(row).attr("data-id",data.id);
                          // $(row).attr("data-preregid",data.id);
                        },
                    });

                    // $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
                    //     $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
                    // });

                    // $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
                    //     $($.fn.dataTable.tables( true ) ).css('width', '100%');
                    //     $($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();
                    // } );

                    // $('#modal-gradelevel').modal('show');

                    
                }
            });
        }

        $(document).on('click', '#level_list tr', function(){
            var levelid = $(this).attr('data-id');

            $.ajax({
                url: '{{route('accsetup_getlevel')}}',
                type: 'GET',
                dataType: 'json',
                data: {
                    levelid:levelid
                },
                success:function(data)
                {
                    $('#gl_1').val(data.gl_1).change()
                    $('#gl_2').val(data.gl_2).change()
                    $('#gl_3').val(data.gl_3).change()
                    $('#gl_credit').val(data.gl_credit).change()
                    $('#editlevel_save').attr('data-id', levelid)
                    $('#modal-editlevel').modal('show')
                }
            });
            
        })

        $(document).on('click', '#asgradelevel', function(){
            $('#modal-gradelevel').modal('show');
            getlevel();
        })

        $(document).on('click', '#ascashier', function(){
            $.ajax({
                type: "GET",
                url: "{{route('accsetup_cashiertrans_load')}}",
                // data: "",
                // dataType: "dataType",
                success: function (data) {
                    $('#cashier_gl').val(data).trigger('change')
                    $('#modal-editcashier').modal('show')
                }
            });
        })

        $(document).on('click', '#editlevel_save', function(){
            var gl_1 = $('#gl_1').val();
            var gl_2 = $('#gl_2').val();
            var gl_3 = $('#gl_3').val();
            var gl_credit = $('#gl_credit').val()
            var dataid = $(this).attr('data-id');

            $.ajax({
                url: '{{route('accsetup_update')}}',
                type: 'GET',
                // dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
                data: {
                    gl_1:gl_1,
                    gl_2:gl_2,
                    gl_3:gl_3,
                    gl_credit:gl_credit,
                    dataid:dataid
                },
                success:function(data)
                {
                    getlevel()
                    const Toast = Swal.mixin({
                      toast: true,
                      position: 'top-end',
                      showConfirmButton: false,
                      timer: 3000,
                      timerProgressBar: true,
                      didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                      }
                    })

                    Toast.fire({
                      type: 'success',
                      title: 'Saved'
                    })
                }
            });
            
        });

        $(document).on('click', '#asoa', function(){
            $.ajax({
                type: "GET",
                url: "{{route('accsetup_oaload')}}",
                // data: "data",
                // dataType: "dataType",
                success: function (data) {
                    $('#old_name').text('OLD ACCOUNT')
                    $('#oa_gl').val(data).change()
                    $('#modal-editoa').modal('show')
                }
            });
        })

        $(document).on('click', '#editoa_save', function(){
            var glid = $('#oa_gl').val();
            console.log(glid)
            $.ajax({
                type: "GET",
                url: "{{route('accsetup_oaupdate')}}",
                data: {
                    glid:glid
                },
                // dataType: "dataType",
                success: function (data) {
                    const Toast = Swal.mixin({
                      toast: true,
                      position: 'top-end',
                      showConfirmButton: false,
                      timer: 3000,
                      timerProgressBar: true,
                      didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                      }
                    })

                    Toast.fire({
                      type: 'success',
                      title: 'Saved'
                    })
                }
            });
        })

        $(document).on('click', '#is_save', function(){
            var mapid = $('#is_detail_map').val()
            var dataid = $('#is_save').attr('data-id')
            var sortid = $('#is_detail_sort').val()
            var title = $('#is_detail_title').val()

            if($('#is_detail_header').prop('checked') == true)
            {
                var header = 1
            }
            else{
                var header = 0
            }

            

            $.ajax({
                type: "GET",
                url: "{{route('issetup_add')}}",
                data: {
                    mapid:mapid,
                    sortid:sortid,
                    header:header,
                    dataid:dataid,
                    title:title,
                    type:type
                },
                // dataType: "dataType",
                success: function (data) {
                    if(data == 'done')
                    {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        })

                        Toast.fire({
                            type: 'success',
                            title: 'Chart of account successfully saved'
                        })

                        issetup_load()        
                        $('#modal-setup_isdetail').modal('hide')
                    }
                    else{
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        })

                        Toast.fire({
                            type: 'error',
                            title: 'Chart of Account already exist'
                        })
                    }
                }
            });
        })

        issetup_load()

        function issetup_load()
        {
            $.ajax({
                type: "GET",
                url: "{{route('issetup_load')}}",
                // data: "data",
                // dataType: "dataType",
                success: function (data) {
                    $('#is_revenue_list').empty()
                    $('#is_expense_list').empty()

                    $.each(data, function (index, val) { 
                        console.log(val.header)
                        if(val.rpttype == 'revenue')
                        {
                            if(val.header == 1)
                            {
                                $('#is_revenue_list').append(`
                                    <tr data-id="`+val.id+`">
                                        <td colspan="2" class="text-bold">`+val.title+`</td>
                                    </tr>
                                `)
                            }
                            else{
                                $('#is_revenue_list').append(`
                                    <tr data-id="`+val.id+`">
                                        <td colspan="2">`+val.mapname+`</td>
                                    </tr>
                                `)
                            }
                        }
                        else{
                            if(val.header == 1)
                            {
                                $('#is_expense_list').append(`
                                    <tr data-id="`+val.id+`">
                                        <td colspan="2" class="text-bold">`+val.title+`</td>
                                    </tr>
                                `)    
                            }
                            else{
                                $('#is_expense_list').append(`
                                    <tr data-id="`+val.id+`">
                                        <td colspan="2">`+val.mapname+`</td>
                                    </tr>
                                `)
                            }

                            
                        }
                    });
                }
            });
        }

        $(document).on('click', '#is_revenue_add', function(){
            $('#modal-setup_isdetail').modal('show')
            $('#is_save').attr('data-id', 0)
            type = 'revenue'
        })

        $(document).on('click', '#is_expense_add', function(){
            $('#modal-setup_isdetail').modal('show')
            $('#is_save').attr('data-id', 0)
            type = 'expenses'
        })

        $(document).on('click', '#is_list tr', function(){
            var dataid = $(this).attr('data-id')

            $.ajax({
                type: "GET",
                url: "{{route('issetup_read')}}",
                data: {
                    dataid:dataid
                },
                // dataType: "dataType",
                success: function (data) {
                    $('#is_gl').val(data).trigger('change')
                    $('#is_save').attr('data-id', dataid)
                    $('#modal-setup_isdetail').modal('show')
                }
            });
        })

        $(document).on('click', '#is_delete', function(){
            dataid = $('#is_save').attr('data-id')

            Swal.fire({
                title: 'Income Statement Setup',
                text: "Delete Chart of Account?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Delete'
            }).then((result) => {
                if (result.value=true) {
                    $.ajax({
                        type: "GET",
                        url: "{{route('issetup_delete')}}",
                        data: {
                            dataid:dataid
                        },
                        // dataType: "dataType",
                        success: function (data) {
                            Swal.fire(
                                'Deleted!',
                                'Chart of account has been deleted',
                                'success'
                            ) 

                            $('#modal-setup_isdetail').modal('hide')
                            issetup_load()
                        }
                    });                    
                }
            })  
        })

        $(document).on('click', '#setup_is', function(){
            issetup_load()
            $('#modal-setup_is').modal('show')
        })

        $(document).on('click', '#editcashier_save', function(){
            var glid = $('#cashier_gl').val()
            var transtype = 'cashier'

            $.ajax({
                type: "GET",
                url: "{{route('accsetup_cashiertrans')}}",
                data: {
                    glid:glid,
                    transtype:transtype
                },
                // dataType: "dataType",
                success: function (data) {
                    const Toast = Swal.mixin({
                      toast: true,
                      position: 'top-end',
                      showConfirmButton: false,
                      timer: 3000,
                      timerProgressBar: true,
                      didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                      }
                    })

                    Toast.fire({
                      type: 'success',
                      title: 'Saved'
                    })
                    $('#modal-editcashier').modal('hide')
                }
            });

        })

        $(document).on('click', '#setup_coa', function(){
            window.location = '{{route('chartofaccounts')}}'
        })

        $(document).on('click', '#setup_mapping', function(){
            window.location = '{{route('coamapping')}}';
        })

        $(document).on('click', '#is_detail_header', function(){
            if($(this).prop('checked') == false)
            {
                $('#is_detail_viewmap').show()
                $('#is_detail_viewheader').hide()
            }
            else{
                $('#is_detail_viewmap').hide()
                $('#is_detail_viewheader').show()
            }
        })

        $(document).on('click', '#is_revenue_list tr', function(){
            var dataid = $(this).attr('data-id')

            $.ajax({
                type: "GET",
                url: "{{route('issetup_revenue_read')}}",
                data: {
                    dataid:dataid
                },
                // dataType: "dataType",
                success: function (data) {
                    if(data.header == 1)
                    {
                        $('#is_detail_header').prop('checked', true)
                        $('#is_detail_viewmap').hide()
                        $('#is_detail_viewheader').show()

                        $('#is_detail_title').val(data.title)
                    }
                    else{
                        $('#is_detail_header').prop('checked', false)
                        $('#is_detail_viewmap').show()
                        $('#is_detail_viewheader').hide()

                        $('#is_detail_map').val(data.mapid).trigger('change')
                    }

                    type = data.rpttype
                    $('#is_save').attr('data-id', dataid)
                    $('#is_detail_sort').val(data.sortid)
                    $('#modal-setup_isdetail').modal('show')
                }
            });
        })

        $(document).on('click','#setup_bs', function(){
            bssetup_load()
            $('#modal-setup_bs').modal('show');
        })

        // $(document).on('click', '#bs_closemodal', function(){
        //     console.log('aaa');
        //     $('#modal-setup_bsdetail').modal('hide')
        // })

        $(document).on('click', '#bs_assets_add', function(){
            $('#modal-setup_bsdetail').modal('show')
            $('#bs_save').attr('data-id', 0)
            type = 'assets'
        })

        $(document).on('click', '#bs_liabilities_add', function(){
            $('#modal-setup_bsdetail').modal('show')
            $('#bs_save').attr('data-id', 0)
            type = 'liability'
        })

        $(document).on('click', '#bs_equity_add', function(){
            $('#modal-setup_bsdetail').modal('show')
            $('#bs_save').attr('data-id', 0)
            type = 'equity'
        })

        $(document).on('click', '#bs_detail_header', function(){
            if($(this).prop('checked') == false)
            {
                $('#bs_detail_viewmap').show()
                $('#bs_detail_viewheader').hide()
            }
            else{
                $('#bs_detail_viewmap').hide()
                $('#bs_detail_viewheader').show()
            }
        })

        $(document).on('click', '#bs_save', function(){
            var mapid = $('#bs_detail_map').val()
            var dataid = $('#bs_save').attr('data-id')
            var sortid = $('#bs_detail_sort').val()
            var title = $('#bs_detail_title').val()

            if($('#bs_detail_header').prop('checked') == true)
            {
                var header = 1
            }
            else{
                var header = 0
            }

            

            $.ajax({
                type: "GET",
                url: "{{route('bssetup_add')}}",
                data: {
                    mapid:mapid,
                    sortid:sortid,
                    header:header,
                    dataid:dataid,
                    title:title,
                    type:type
                },
                // dataType: "dataType",
                success: function (data) {
                    if(data == 'done')
                    {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        })

                        Toast.fire({
                            type: 'success',
                            title: 'Map successfully saved'
                        })

                        bssetup_load()        
                        $('#modal-setup_bsdetail').modal('hide')
                    }
                    else{
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        })

                        Toast.fire({
                            type: 'error',
                            title: 'Map already exist'
                        })
                    }
                }
            });
        })

        function bssetup_load()
        {
            $.ajax({
                type: "GET",
                url: "{{route('bssetup_load')}}",
                // data: "data",
                // dataType: "dataType",
                success: function (data) {
                    $('#bs_assets_list').empty()
                    $('#bs_liabilities_list').empty()
                    $('#bs_equity_list').empty()

                    $.each(data, function (index, val) { 
                        // console.log(val.header)
                        if(val.rpttype == 'assets')
                        {
                            if(val.header == 1)
                            {
                                $('#bs_assets_list').append(`
                                    <tr data-id="`+val.id+`">
                                        <td colspan="2" class="text-bold">`+val.title+`</td>
                                    </tr>
                                `)
                            }
                            else{
                                console.log(val.mapname)
                                $('#bs_assets_list').append(`
                                    <tr data-id="`+val.id+`">
                                        <td colspan="2">`+val.mapname+`</td>
                                    </tr>
                                `)
                            }
                        }
                        else if(val.rpttype == 'liability')
                        {
                            if(val.header == 1)
                            {
                                $('#bs_liabilities_list').append(`
                                    <tr data-id="`+val.id+`">
                                        <td colspan="2" class="text-bold">`+val.title+`</td>
                                    </tr>
                                `)    
                            }
                            else{
                                $('#bs_liabilities_list').append(`
                                    <tr data-id="`+val.id+`">
                                        <td colspan="2">`+val.mapname+`</td>
                                    </tr>
                                `)
                            }
                        }
                        else{
                            if(val.header == 1)
                            {
                                $('#bs_equity_list').append(`
                                    <tr data-id="`+val.id+`">
                                        <td colspan="2" class="text-bold">`+val.title+`</td>
                                    </tr>
                                `)    
                            }
                            else{
                                $('#bs_equity_list').append(`
                                    <tr data-id="`+val.id+`">
                                        <td colspan="2">`+val.mapname+`</td>
                                    </tr>
                                `)
                            }
                        }
                    });
                }
            });
        }

        $(document).on('click', '#bs_assets_list tr', function(){
            var dataid = $(this).attr('data-id')
            bs_read(dataid)
            
        })

        $(document).on('click', '#bs_liabilities_list tr', function(){
            var dataid = $(this).attr('data-id')
            bs_read(dataid)
            
        })

        $(document).on('click', '#bs_equity_list tr', function(){
            var dataid = $(this).attr('data-id')
            bs_read(dataid)
            
        })

        function bs_read(dataid)
        {
            $.ajax({
                type: "GET",
                url: "{{route('bssetup_read')}}",
                data: {
                    dataid:dataid
                },
                // dataType: "dataType",
                success: function (data) {
                    if(data.header == 1)
                    {
                        $('#bs_detail_header').prop('checked', true)
                        $('#bs_detail_viewmap').hide()
                        $('#bs_detail_viewheader').show()
                        $('#bs_detail_title').val(data.title)
                    }
                    else{
                        $('#bs_detail_header').prop('checked', false)
                        $('#bs_detail_viewmap').show()
                        $('#bs_detail_viewheader').hide()
                        $('#bs_detail_map').val(data.mapid).trigger('change')
                    }

                    type = data.rpttype
                    $('#bs_save').attr('data-id', dataid)
                    $('#bs_detail_sort').val(data.sortid)
                    $('#modal-setup_bsdetail').modal('show')
                }
            });
        }

        $(document).on('click', '#asbank', function(){
            bank_load()
            $('#modal-bank').modal('show')
        })

        function bank_load()
        {
            $.ajax({
                type: "GET",
                url: "{{route('accsetup_bank_load')}}",
                data: {

                },
                // dataType: "dataType",
                success: function (data){
                    $('#bank_list tr').empty()

                    $.each(data, function(index, val) {
                        $('#bank_list').append(`
                            <tr data-id="`+val.id+`">
                                <td>`+val.bankname+`</td>
                                <td>`+val.accountno+`</td>
                                <td>`+val.branch+`</td>
                                <td>`+val.address+`</td>
                            </tr>
                        `)
                    });
                        
                }
            })
        }

        $(document).on('click', '#bank_add', function(){
            adddisplay()
            $('#modal-bankedit').modal('show')
        })

        $(document).on('click', '#bank_save', function(){
            var bankname = $('#bank_name').val()
            var accountno = $('#bank_accno').val()
            var branch = $('#bank_branch').val()
            var address = $('#bank_address').val()
            var id = $(this).attr('data-id')

            $.ajax({
                type: "GET",
                url: "{{route('accsetup_bank_create')}}",
                data: {
                    bankname:bankname,
                    accountno:accountno,
                    branch:branch,
                    address:address,
                    id:id
                },
                // dataType: "dataType",
                success: function (data){
                    if(data == 'done')
                    {
                        const Toast = Swal.mixin({
                          toast: true,
                          position: 'top-end',
                          showConfirmButton: false,
                          timer: 3000,
                          timerProgressBar: true,
                          didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                          }
                        })

                        Toast.fire({
                          type: 'success',
                          title: 'Bank successfully saved'
                        })

                        bank_load()
                        $('#modal-bankedit').modal('hide')
                    }
                    else{
                        const Toast = Swal.mixin({
                          toast: true,
                          position: 'top-end',
                          showConfirmButton: false,
                          timer: 3000,
                          timerProgressBar: true,
                          didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                          }
                        })

                        Toast.fire({
                          type: 'error',
                          title: 'Bank account exist'
                        })
                    }
                }
            })
        })

        $(document).on('click', '#bank_list tr', function(){
            var id = $(this).attr('data-id')

            $.ajax({
                type: "GET",
                url: "{{route('accsetup_bank_read')}}",
                data: {
                    id:id
                },
                // dataType: "dataType",
                success: function (data){
                    $('#bank_name').val(data['bankname'])
                    $('#bank_accno').val(data['accountno'])
                    $('#bank_branch').val(data['branch'])
                    $('#bank_address').val(data['address'])

                    $('#bank_save').attr('data-id', data['id'])
                    $('#bank_remove').show()
                    $('#modal-bankedit').modal('show')
                }
            })
        })

        function adddisplay()
        {
            $('#bank_name').val('')
            $('#bank_accno').val('')
            $('#bank_branch').val('')
            $('#bank_address').val('')
            $('#bank_save').attr('data-id', 0)
            $('#bank_remove').hide()
        }

        $(document).on('click', '#bank_remove', function(){
           var id = $('#bank_save').attr('data-id')

           Swal.fire({
              title: 'Delete Bank?',
              text: "",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Delete'
            }).then((result) => {
              if (result.value == true) {
                $.ajax({
                    type: "GET",
                    url: "{{route('accsetup_bank_delete')}}",
                    data: {
                        id:id
                    },
                    // dataType: "dataType",
                    success: function (data){
                        const Toast = Swal.mixin({
                          toast: true,
                          position: 'top-end',
                          showConfirmButton: false,
                          timer: 3000,
                          timerProgressBar: true,
                          didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                          }
                        })

                        Toast.fire({
                          type: 'success',
                          title: 'Bank account has been deleted'
                        })

                        bank_load()
                        $('#modal-bankedit').modal('hide')
                    }
                   })     
                }
            })           
        })

        $(document).on('click', '#aspayroll', function(){
            $.ajax({
                type: "GET",
                url: "{{route('accsetup_payroll_load')}}",
                data: {

                },
                // dataType: "dataType",
                success: function (data){
                    $('#payroll_gldebit').val(data.debitgl).trigger('change')
                    $('#payroll_glcredit').val(data.creditgl).trigger('change')

                    $('#modal-aspayroll').modal('show')

                }
            })
        })

        $(document).on('click', '#payroll_save', function(){
            var gldebit = $('#payroll_gldebit').val()
            var glcredit = $('#payroll_glcredit').val()

            $.ajax({
                type: "GET",
                url: "{{route('accsetup_payroll_save')}}",
                data: {
                    gldebit:gldebit,
                    glcredit:glcredit
                },
                // dataType: "dataType",
                success: function (data){
                    const Toast = Swal.mixin({
                      toast: true,
                      position: 'top-end',
                      showConfirmButton: false,
                      timer: 3000,
                      timerProgressBar: true,
                      didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                      }
                    })

                    Toast.fire({
                      type: 'success',
                      title: 'Saved'
                    })
                }
            })
        })

        $(document).on('click', '#is_expense_list tr', function(){
            var dataid = $(this).attr('data-id')

            $.ajax({
                type: "GET",
                url: "{{route('issetup_revenue_read')}}",
                data: {
                    dataid:dataid
                },
                // dataType: "dataType",
                success: function (data) {
                    if(data.header == 1)
                    {
                        $('#is_detail_header').prop('checked', true)
                        $('#is_detail_viewmap').hide()
                        $('#is_detail_viewheader').show()

                        $('#is_detail_title').val(data.title)
                    }
                    else{
                        $('#is_detail_header').prop('checked', false)
                        $('#is_detail_viewmap').show()
                        $('#is_detail_viewheader').hide()

                        $('#is_detail_map').val(data.mapid).trigger('change')
                    }

                    type = data.rpttype
                    $('#is_save').attr('data-id', dataid)
                    $('#is_detail_sort').val(data.sortid)
                    $('#modal-setup_isdetail').modal('show')
                }
            });
        })

        $(document).on('click', '#bs_delete', function(){
            var id = $('#bs_save').attr('data-id')

            Swal.fire({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Delete'
            }).then((result) => {
              if (result.value == true) {
                $.ajax({
                    type: "GET",
                    url: "{{route('bssetup_delete')}}",
                    data: {
                        id:id
                    },
                    // dataType: "dataType",
                    success: function (data){
                        const Toast = Swal.mixin({
                          toast: true,
                          position: 'top-end',
                          showConfirmButton: false,
                          timer: 3000,
                          timerProgressBar: true,
                          didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                          }
                        })

                        Toast.fire({
                          type: 'success',
                          title: 'Deleted'
                        })
                        
                        bssetup_load()
                    }
                })    
              }
            })
        })

        $(document).on('click', '#expense_save', function(){
            var glcredit = $('#expense_glcredit').val()

            $.ajax({
                type: "POST",
                url: "{{route('accsetup_expense_save')}}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    glcredit:glcredit
                },
                // dataType: "dataType",
                success: function (data) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })

                    Toast.fire({
                        type: 'success',
                        title: 'Setup successfully saved'
                    })
                }
            });
        });

        $(document).on('click', '#asexpenses', function(){
            $.ajax({
                type: "GET",
                url: "{{route('accsetup_expense_load')}}",
                success: function (data) {
                    $('#expense_glcredit').val(data).trigger('change');
                    $('#modal-asexpenses').modal('show')
                }
            });
        })

        $(document).on('click', '#item_create', function(){
            $('#item_code').val('')
            $('#item_description').val('')
            $('#item_cost').val('')
            $('#item_class').val(0).trigger('change')
            $('#item_account').val(0).trigger('change')
            $('#item_save').attr('data-id', 0)
            $('#item_quantity').val('')
            $('#item_min_quantity').val('')

            $('#modal-itemdetail').modal('show')
        })

        function getItems()
        {
            var filter = $('#item_filter').val()

            $.ajax({
                type: "GET",
                url: "{{route('expenses_items')}}",
                data: {
                    filter:filter
                },
                // dataType: "dataType",
                success: function (data) {
                    $('#items_list').empty()

                    $.each(data, function (index, val) { 
                        var itemcode = ''
                        var itemdesc = ''
                        var itemqty = ''
						console.log(val);

                        if(val.itemcode != null)
                        {
                            itemcode = val.itemcode
                        }
                        else{
                            itemcode = ''
                        }

                        if(val.description != null)
                        {
                            itemdesc = val.description
                        }
                        else{
                            itemdesc = ''
                        }

                        if(val.qty != 0 && val.qty != null)
                        {
                            itemqty = val.qty
                        }
                        else{
                            itemqty = ''
                        }

                        $('#items_list').append(`
                            <tr data-id="`+val.id+`">
                                <td>`+itemcode+`</td>
                                <td>`+itemdesc+`</td>
                                <td>`+itemqty+`</td>
                            </tr>
                        `)
                    });

                    $('#modal-asitems').modal('show');
                }
            });
        }

        $(document).on('click', '#asitems', function(){
            getItems()
        })

        $(document).on('click', '#item_save', function(){
            
            $('#item_save').prop('disabled', true);
            var code = $('#item_code').val()
            var description = $('#item_description').val()
            var cost = $('#item_cost').val()
            var classid = $('#item_class').val()
            var coa = $('#item_account').val()
            var qty = $('#item_quantity').val()
            var min_qty = $('#item_min_quantity').val()
            var dataid = $(this).attr('data-id')
            var itemType = 0;

            if($('#inventory').is(':checked')){
                itemType = 1;
            }

            $.ajax({
                type: "GET",
                url: "{{route('expenses_items_create')}}",
                data: {
                    code:code,
                    description:description,
                    cost:cost,
                    classid:classid,
                    coa:coa,
                    qty:qty,
                    min_qty:min_qty,
                    itemType:itemType,
                    dataid:dataid
                },
                // dataType: "dataType",
                success: function (data) {

                    $('#item_save').prop('disabled', false);

                    if(data == 'done')
                    {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        })

                        Toast.fire({
                            type: 'success',
                            title: 'Item saved'
                        })

                        getItems()
                        $('#modal-itemdetail').modal('hide')
                    }
                    else{
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        })

                        Toast.fire({
                            type: 'error',
                            title: 'Item already exist'
                        })
                    }
                },
                error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr);
                        $('#item_save').prop('disabled', false);
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        })

                        Toast.fire({
                            type: 'error',
                            title: 'Something went wrong!'
                        })

                    }
            });
        })

        $(document).on('click', '#items_list tr', function(){
            var dataid = $(this).attr('data-id')

            $.ajax({
                type: "GET",
                url: "{{route('expenses_items_read')}}",
                data: {
                    dataid:dataid
                },
                // dataType: "dataType",
                success: function (data) {
					console.log(data);
                    $('#item_code').val(data.code)
                    $('#item_description').val(data.description)
                    $('#item_cost').val(data.cost)
                    $('#item_class').val(data.classid).trigger('change')
                    $('#item_account').val(data.glid).trigger('change')
                    $('#item_quantity').val(data.qty);
                    $('#item_min_quantity').val(data.minimum_qty);
                    if(data.itemtype == 'non_inventotry'){
                        $('#non_inventory').prop('checked', true);
                        $('#qtyContainer').hide();
                    }else{
                        $('#inventory').prop('checked', true);
                        $('#qtyContainer').show();
                    }
                    $('#item_save').attr('data-id', dataid)
                    $('#item_delete').show();

                    $('#modal-itemdetail').modal('show');
                }
            });
        })

        $(document).on('click', '#sup_create', function(){
            $('#sup_name').val('');
            $('#sup_address').val('')
            $('#sup_contactno').val('')
            $('#sup_save').attr('data-id', 0)
            $('#sup_gl').val(0).trigger('change')
            $('#sup_delete').hide()

            $('#modal-supplierdetail').modal('show')
        })

        $(document).on('click', '#sup_save', function(){
            var company = $('#sup_name').val()
            var address = $('#sup_address').val()
            var contactno = $('#sup_contactno').val()
            var dataid = $('#sup_save').attr('data-id')
            var glid = $('#sup_gl').val()
            
            if(dataid == 0)
            {
                $.ajax({
                    type: "GET",
                    url: "{{route('expenses_supplier_create')}}",
                    data: {
                        company:company,
                        address:address,
                        contactno:contactno,
                        glid:glid
                    },
                    // dataType: "dataType",
                    success: function (data) {
                        if(data == 'done')
                        {
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer)
                                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                                }
                            })

                            Toast.fire({
                                type: 'success',
                                title: 'Supplier successfully saved'
                            })

                            $('#modal-supplierdetail').modal('hide')
                            getSupplier()
                        }
                        else{
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer)
                                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                                }
                            })

                            Toast.fire({
                                type: 'error',
                                title: 'Supplier already exist'
                            })
                        }
                    }
                })
            }
            else{
                $.ajax({
                    type: "GET",
                    url: "{{route('expenses_supplier_update')}}",
                    data: {
                        dataid:dataid,
                        company:company,
                        address:address,
                        contactno:contactno,
                        glid:glid
                    },
                    // dataType: "dataType",
                    success: function (data) {
                        if(data == 'done')
                        {
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer)
                                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                                }
                            })

                            Toast.fire({
                                type: 'success',
                                title: 'Supplier successfully saved'
                            })

                            $('#modal-supplierdetail').modal('hide')
                            getSupplier()
                        }
                        else{
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer)
                                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                                }
                            })

                            Toast.fire({
                                type: 'error',
                                title: 'Supplier already exist'
                            })
                        }
                    }
                })
            }
        })

        function getSupplier()
        {
            var filter = $('#sup_filter').val()

            $.ajax({
                type: "GET",
                url: "{{route('expenses_supplier')}}",
                data: {
                    filter:filter
                },
                // dataType: "dataType",
                success: function (data) {
                    $('#supplier_list').empty()

                    $.each(data, function (index, value) { 
                        var address = (value.address == null) ? '' : value.address
                        var contactno = (value.contactno == null) ? '' : value.contactno

                        $('#supplier_list').append(`
                            <tr data-id="`+value.id+`">
                                <td>`+value.companyname+`</td>
                                <td>`+contactno+`</td>
                                <td>`+address+`</td>
                            </tr>
                        `)

                        $('#modal-assupplier').modal('show')
                    });
                }
            });
        }

        $(document).on('click', '#assupplier' , function(){
            getSupplier()
        })

        $(document).on('click', '#supplier_list tr', function(){
            var dataid = $(this).attr('data-id')

            $.ajax({
                type: "GET",
                url: "{{route('expenses_supplier_read')}}",
                data: {
                    dataid:dataid
                },
                // dataType: "dataType",
                success: function (data) {
                    $('#sup_name').val(data.name)
                    $('#sup_address').val(data.address)
                    $('#sup_contactno').val(data.contactno)
                    $('#sup_gl').val(data.glid).trigger('change')
                    $('#sup_save').attr('data-id', dataid)
                    $('#sup_delete').show()

                    $('#modal-supplierdetail').modal('show')
                }
            });
        })

        $(document).on('keyup', '#sup_filter', function(){
            getSupplier()
        })

        $(document).on('change', 'input[name="inventory"]', function(){
            if($(this).is(':checked') && $(this).attr('id') === 'inventory'){
                // Perform your action here when 'Non Inventory' radio button is checked
                $('#qtyContainer').show();
                // Example action: Changing background color of the div with class 'icheck-primary d-inline'
            }else{

                $('#qtyContainer').hide();
            }
        })

    });

</script>
@endsection

