
@extends('hr.layouts.app')
@section('content')
@php
function capitalize($word)
    {
        return \App\Http\Controllers\SchoolFormsController::capitalize_word($word);
    }
@endphp
<style>
    #table-employees td { padding: 5px;}
    div.dataTables_wrapper {
        /* width: 800px; */
        /* margin: 0 auto; */
    }
    
#table-employees thead th:first-child  { 
    position: sticky; 
    left: 0; 
    background-color: #fff; 
    outline: 2px solid #dee2e6;
    outline-offset: -1px;
}

#table-employees tbody td:first-child  {  
    position: sticky; 
    left: 0; 
    background-color: #fff; 
    background-color: #fff; 
    outline: 2px solid #dee2e6;
    outline-offset: -1px;
}

#table-employees thead th:first-child  { 
        position: sticky; left: 0; 
        background-color: #fff; 
        outline: 2px solid #dee2e6;
        outline-offset: -1px;
}
#table-standard-allowances td, #table-standard-allowances th {
    padding: 3px;
}
#table-other-allowances td, #table-other-allowances th {
    padding: 3px;
}
</style>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Allowances</h4>
                <!-- <h1>Attendance</h1> -->
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">Allowance Setup</li>
                </ol>
            </div>
      </div>
    </div><!-- /.container-fluid -->
</section>

<div class="row">
    <div class="col-12">
    
        <div class="card shadow" style="box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
            <div class="card-header d-flex p-0">
                {{-- <h3 class="card-title p-3">Deductions</h3> --}}
                {{-- <ul class="nav nav-pills ml-auto p-2">
                    <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Standard</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">Others</a></li>
                </ul> --}}                     
                <ul class="nav nav-pills ml-auto p-2">
                    <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Employees</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">Settings</a></li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    {{-- <div class="tab-pane active" id="tab_1">
                        <div class="row">
                            <div class="col-md-12">                                
                                <ul class="nav nav-pills ml-auto p-2">
                                    <li class="nav-item"><a class="nav-link active" href="#tab_1_1" data-toggle="tab">Employees</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#tab_1_2" data-toggle="tab">Settings</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="tab-content"> --}}
                            <div class="tab-pane active" id="tab_1">
                                <div class="row">
                                    <div class="col-md-6">
                                        <span class="badge badge-secondary" style="font-size: 12px;" >Disabled</span>
                                        <span class="badge badge-success" style="font-size: 12px;" >Default Amount</span>
                                        <span class="badge badge-info" style="font-size: 12px;" >Custom Amount</span>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <span class="badge badge-warning" style="font-size: 12px;" >Standard allowances</span>
                                        {{-- <span class="badge badge-default border-1" style="font-size: 12px; border: 1px solid #ddd;" >Other allowances</span> --}}
                                    </div>
                                    <div class="col-md-12">      
                                        <table class="table-head-fixed text-nowrap table-bordered" id="table-employees" style="font-size: 13px; width: 100%;">   
                                            <thead>
                                                <tr>
                                                    <th style="width: 25%;">Employee</th>
                                                    <th style="width: 10%;">Rate</th>
                                                    @if(count($standardallowances)>0)
                                                        @foreach($standardallowances as $eachstandard)   
                                                            <th class="text-center bg-warning">{{$eachstandard->description}}</th>
                                                        @endforeach
                                                    @endif
                                                    {{-- @if(count($otherdeductions)>0)
                                                        @foreach($otherdeductions as $eachother)   
                                                            <th class="text-center">{{$eachother->description}}</th>
                                                        @endforeach
                                                    @endif --}}
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($employees as $eachemployee)
                                                    <tr>
                                                        <td>{{capitalize($eachemployee->sortname)}}</td>
                                                        <td>{{$eachemployee->amount > 0 ? number_format($eachemployee->amount,2,'.',',').'/'.$eachemployee->salarytype : ''}}</td>
                                                        @if(count($eachemployee->allowances)>0)
                                                            @foreach($eachemployee->allowances as $eachparticular)
                                                            <td class="text-right">
                                                                @if($eachparticular->automatic == 1)
                                                                <span class="badge badge-success each-badge-amount" style="width: 100%; font-size: 12px; cursor: pointer;" data-particularstatus="{{$eachparticular->status}}" data-particularid="{{$eachparticular->id}}" data-employeeid="{{$eachemployee->employeeid}}" data-name="{{$eachemployee->sortname}}" data-default="{{$eachparticular->cont_amount_default}}" data-amount="{{$eachparticular->cont_amount}}">{{$eachparticular->cont_amount}}</span>
                                                                @else
                                                                <span class="badge @if($eachparticular->status == 1)badge-info @else badge-secondary @endif each-badge-amount" style="width: 100%; font-size: 12px; cursor: pointer;" data-particularid="{{$eachparticular->id}}" data-particularstatus="{{$eachparticular->status}}" data-employeeid="{{$eachemployee->employeeid}}" data-name="{{$eachemployee->sortname}}" data-default="{{$eachparticular->cont_amount_default}}" data-amount="{{$eachparticular->cont_amount}}"> {{$eachparticular->cont_amount}}</span>
                                                                @endif
                                                            </td>
                                                            @endforeach
                                                        @endif
                                                        {{-- @if(count($otherdeductions)>0)
                                                            @foreach($otherdeductions as $eachother)   
                                                                @if(collect($eachemployee->othercontributions)->where('id',$eachother->id)->count()>0)
                                                                    @php
                                                                        $otherinfo = collect($eachemployee->othercontributions)->where('id',$eachother->id)->first();
                                                                    @endphp
                                                                    <td>
                                                                        <span class="badge @if($otherinfo->status == 1) {{$otherinfo->custom == 1 ? 'badge-info' : 'badge-success'}} @else badge-secondary  @endif each-badge-otherdeduction-amount-edit" style="width: 100%; font-size: 12px; cursor: pointer; border: 1px solid #ddd;" data-particularid="{{$eachother->id}}" data-particularstatus="{{$otherinfo->status}}" data-employeeid="{{$eachemployee->employeeid}}" data-name="{{$eachemployee->sortname}}" data-default="{{$otherinfo->cont_amount_default}}" data-amount="{{$otherinfo->cont_amount}}">{{$otherinfo->cont_amount}}</span>
                                                                    </td>
                                                                @else
                                                                    <td>
                                                                        <span class="badge each-badge-otherdeduction-amount" style="width: 100%; font-size: 12px; cursor: pointer; border: 1px solid #ddd;" data-particularid="{{$eachother->id}}" data-particulardesc="{{$eachother->description}}" data-employeeid="{{$eachemployee->employeeid}}" data-name="{{$eachemployee->sortname}}"  data-default="{{$eachother->amount}}" data-amount="{{$eachother->amount}}"><i class="fa fa-plus"></i>Add</span>
                                                                    </td>
                                                                @endif
                                                            @endforeach
                                                        @endif --}}
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_2">
                                <div class="row">
                                    <div class="col-md-12">                                
                                        <ul class="nav nav-tabs mb-3" id="tab-allowances" role="tablist">
                                            <li class="nav-item"><a class="nav-link active" id="tab_2_1-tab" href="#tab_2_1" data-toggle="pill" role="tab" aria-controls="tab_2_1" aria-selected="true">Standard Allowances</a></li>
                                            {{-- <li class="nav-item"><a class="nav-link" id="tab_2_2-tab" href="#tab_2_2" data-toggle="pill" role="tab" aria-controls="tab_2_2" aria-selected="false">Other Deductions</a></li> --}}
                                        </ul>                                        
                                    </div>
                                    <div class="col-md-12">  
                                        <div class="tab-content" id="tab-deductionsContent">
                                            <div class="tab-pane fade show active" id="tab_2_1" role="tabpanel" aria-labelledby="tab_2_1-tab"> 
                                                <div class="row">
                                                    {{-- <div class="col-12 text-right mb-2">
                                                        <button type="button" class="btn btn-outline-primary"><i class="fa fa-plus"></i> Add particular</button>
                                                    </div> --}}
                                                    <div class="col-12">
                                                        <table class="table" id="table-standard-allowances" >
                                                            <thead>
                                                                <tr>
                                                                    <th style="">Particulars</th>
                                                                    <th style="" class="text-right pr-5">Amount</th>
                                                                    <th style="" class="text-center">Brackets</th>
                                                                    {{-- <th>Disable</th> --}}
                                                                    <th style="" class="text-center">Delete</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($standardallowances as $eachstandard)
                                                                    <tr>
                                                                        <td style="vertical-align: middle; width: 50%;">{{$eachstandard->description}}</td>
                                                                        <td style="vertical-align: middle;  width:  15%;" class="td-amount-{{$eachstandard->id}} text-right pr-5">{{$eachstandard->amount ?? '0.00'}}</td>
                                                                        <td style="vertical-align: middle;  width:  10%;"><button type="button" class="btn btn-outline-warning btn-sm btn-block btn-allowance-standard-edit" data-id="{{$eachstandard->id}}" data-desc="{{$eachstandard->description}}" data-amount="{{$eachstandard->amount ?? '0.00'}}"><small class="text-bold">Edit</small></button></td>
                                                                        {{-- <td><button type="button" class="btn btn-outline-secondary btn-sm btn-block"><i class="fa fa-ban"></i> </button></td> --}}
                                                                        <td class="text-right">
                                                                            <button type="button" class="btn btn-outline-danger btn-sm btn-allowance-standard-delete" data-id="{{$eachstandard->id}}"><i class="fa fa-trash-alt"></i> </button>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <div class="tab-pane fade" id="tab_2_2" role="tabpanel" aria-labelledby="tab_2_2-tab">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <table class="table" id="table-other-deductions" >
                                                            <thead>
                                                                <tr>
                                                                    <th style="">Particulars</th>
                                                                    <th style="" class="text-right pr-5">Full Amount</th>
                                                                    <th style="" class="text-center">Edit</th>
                                                                    <th style="" class="text-center">Delete</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($otherdeductions as $eachother)
                                                                    <tr>
                                                                        <td style="vertical-align: middle; width: 50%;">{{$eachother->description}}</td>
                                                                        <td style="vertical-align: middle;  width:  20%;" class="td-amount-{{$eachother->id}} text-right pr-5">{{$eachother->amount ?? '0.00'}}</td>
                                                                        <td style="vertical-align: middle;  width:  10%;"><button type="button" class="btn btn-outline-warning btn-sm btn-block btn-deduction-other-edit" data-id="{{$eachother->id}}" data-desc="{{$eachother->description}}" data-amount="{{$eachother->amount ?? '0.00'}}"><small class="text-bold">Edit</small></button></td>
                                                                   
                                                                        <td class="text-right">
                                                                            <button type="button" class="btn btn-outline-danger btn-sm btn-deduction-other-delete" data-id="{{$eachother->id}}"><i class="fa fa-trash-alt"></i> </button>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div> --}}
                                        </div>      
                                    </div>
                                </div>
                            </div>
                        {{-- </div>
                    </div> --}}
                    
                    {{-- <div class="tab-pane" id="tab_2">
                    The European languages are members of the same family. Their separate existence is a myth.
                    For science, music, sport, etc, Europe uses the same vocabulary. The languages only differ
                    in their grammar, their pronunciation and their most common words. Everyone realizes why a
                    new common language would be desirable: one could refuse to pay expensive translators. To
                    achieve this, it would be necessary to have uniform grammar, pronunciation and more common
                    words. If several languages coalesce, the grammar of the resulting language is more simple
                    and regular than that of the individual languages.
                    </div> --}}
                    
                
                </div>
                
            </div>
        </div>
    
    </div>
    
</div> 
    
 <div class="modal fade" id="modal-each-amount">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Monthly Allowance</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/hr/setup/allowances/custom" method="GET" id="form-custom-contribution">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 id="text-employeename"></h5>
                        </div>
                        <div class="col-md-6">
                            <label>Default Amount</label>
                            <input type="number" class="form-control" value="" id="input-default-amount" name="default-amount" step=".01" min="0"  onkeydown="return event.keyCode !== 69" disabled />
                        </div>
                        <div class="col-md-6">
                            <label>Custom Amount</label>
                            <input type="number" class="form-control" value="" id="input-new-amount" name="custom-amount" step=".01" min="0"  onkeydown="return event.keyCode !== 69" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row" style="width: 100%;">
                        <div class="col-3 text-left p-0" id="container-custom-buttons">
                            
                            {{-- <button type="button" class="btn btn-sm btn-default" data-dismiss="modal" class="btn-close">Close</button> --}}
                        </div>
                        <div class="col-9 text-right">
                            <button type="button" class="btn btn-sm btn-outline-success" id="btn-custom-default">Set to default</button>
                            <button type="button" class="btn btn-sm btn-info" id="btn-custom-save">Save changes</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    
    </div>
    
</div>
<div class="modal fade" id="modal-standard-add">
    <div class="modal-dialog modal-default">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span id="modal-standard-add-particular-desc">Standard allowance</span> </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal-standard-add-modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <label>Particular</label>
                        <input type="text" class="form-control"  id="input-standard-add-desc"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Amount</label>
                        <input type="number" class="form-control"  id="input-standard-add-amount" placeholder="0.00" name="rangeto" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn-modal-standard-add-save">Submit</button>
            </div>
        </div>    
    </div>    
</div>
<div class="modal fade" id="modal-standard-edit">
    <div class="modal-dialog modal-default">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span id="modal-standard-edit-particular-desc"></span> </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal-standard-edit-modal-body">
                {{-- <div class="row">
                    <div class="col-md-12">
                        <label>Particular</label>
                        <input type="text" class="form-control"  id="input-standard-edit-desc"/>
                    </div>
                </div> --}}
                <div class="row">
                    <div class="col-md-12">
                        <label>Amount</label>
                        <input type="number" class="form-control"  id="input-standard-edit-amount" placeholder="0.00" name="rangeto" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn-modal-standard-edit-save">Save changes</button>
            </div>
        </div>    
    </div>    
</div>
<div class="modal fade" id="modal-other-add">
    <div class="modal-dialog modal-default">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span id="modal-other-add-particular-desc">Other allowance</span> </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal-other-add-modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <label>Particular</label>
                        <input type="text" class="form-control"  id="input-other-add-desc"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Amount</label>
                        <input type="number" class="form-control"  id="input-other-add-amount" placeholder="0.00" name="rangeto" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn-modal-other-add-save">Submit</button>
            </div>
        </div>    
    </div>    
</div>
<div class="modal fade" id="modal-other-edit">
    <div class="modal-dialog modal-default">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span id="modal-other-edit-particular-desc"></span> </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal-other-edit-modal-body">
                {{-- <div class="row">
                    <div class="col-md-12">
                        <label>Particular</label>
                        <input type="text" class="form-control"  id="input-standard-edit-desc"/>
                    </div>
                </div> --}}
                <div class="row">
                    <div class="col-md-12">
                        <label>Amount</label>
                        <input type="number" class="form-control"  id="input-other-edit-amount" placeholder="0.00" name="rangeto" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn-modal-other-edit-save">Save changes</button>
            </div>
        </div>    
    </div>    
</div>
<div class="modal fade" id="modal-add-year">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span id="particular-desc"></span> Add Year</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal-body-addyear">
                <input type="number" class="form-control" step="0" min="0"  onkeydown="return event.keyCode !== 69" id="input-add-year" name="input-add-year"/>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn-add-year">Add year</button>
            </div>
        </div>
    
    </div>
    
</div>
<div class="modal fade" id="modal-other-allowance-add">
    <div class="modal-dialog modal-default">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span id="other-particular-desc"></span> </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <h5 id="other-text-employeename"></h5>
                    </div>
                    <div class="col-md-6">
                        <label>Default Amount</label>
                        <input type="number" class="form-control" value="" id="input-other-default-amount" name="default-amount" step=".01" min="0"  onkeydown="return event.keyCode !== 69" disabled />
                    </div>
                    <div class="col-md-6">
                        <label>Custom Amount</label>
                        <input type="number" class="form-control" value="" id="input-other-new-amount" name="custom-amount" step=".01" min="0"  onkeydown="return event.keyCode !== 69" />
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn-modal-other-addemployee-save">Add allowance</button>
            </div>
        </div>
    
    </div>
    
</div>
<div class="modal fade" id="modal-other-allowance-edit">
    <div class="modal-dialog modal-default">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span id="other-particular-desc-edit"></span> </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row" style="width: 100%;">
                    <div class="col-md-12">
                        <h5 id="other-text-employeename-edit"></h5>
                    </div>
                    <div class="col-md-6">
                        <label>Default Amount</label>
                        <input type="number" class="form-control" value="" id="input-other-default-amount-edit" name="default-amount" step=".01" min="0"  onkeydown="return event.keyCode !== 69" disabled />
                    </div>
                    <div class="col-md-6">
                        <label>Custom Amount</label>
                        <input type="number" class="form-control" value="" id="input-other-new-amount-edit" name="custom-amount" step=".01" min="0"  onkeydown="return event.keyCode !== 69" />
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="display: unset;">
                <div class="row" style="width: 100%;">
                    <div class="col-md-6" id="container-otherdeductions-buttons"></div>
                    <div class="col-md-6 text-right">
                        <button type="button" class="btn btn-primary" id="btn-modal-other-editemployee-save">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    
    </div>
    
</div>

@endsection
@section('footerscripts')
<script>
    $(document).ready(function(){
        var table_standard = $('#table-standard-allowances').DataTable({
            paging:         false,
            "info": false,
            "ordering": false,
            "aaSorting": []
        }) 
        
        var table_other = $('#table-other-allowances').DataTable({
            paging:         false,
            "info": false,
            "ordering": false,
            "aaSorting": []
        }) 
        var oTable = $('#table-employees').DataTable({
            "columnDefs": [
                { "width": "300px", "targets": 0 }
            ],
            fixedColumns: true,
            scrollY:        500,
            scrollX:        true,
            scrollCollapse: true,
            paging:         false,
            fixedColumns:   true,
            "info": false,
            "ordering": false,
            "aaSorting": []
        })   //using Capital D, which is mandatory to retrieve "api" datatables' object, latest jquery Datatable
    
        $('#table-employees_filter').closest('.row').find('div').first().append('<em>Note: Click the amount badges to see the details.</em>')
        function getemployees(){
            
            $('#table-employees').DataTable({
                // "paging": false,
                // "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "destroy": true,
                serverSide: true,
                processing: true,
                // ajax:'/student/preregistration/list',
                ajax:{
                    url: '/hr/attendance/indexv2',
                    type: 'GET',
                    data: {
                        action : 'getemployees',
                        changedate : $('#select-date').val()
                    }
                },
                columns: [
                    { "data": null },
                    { "data": null },
                    { "data": null },
                    { "data": null },
                    { "data": null },
                    { "data": null },
                    { "data": null }
                ],
                columnDefs: [
                    {
                        'targets': 0,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            $(td)[0].innerHTML = ' <div class="row">'+
                                '<div class="col-md-3">'+
                                    '<img src="/'+rowData.picurl+'" class="" alt="User Image" onerror="this.src=\''+onerror_url+'\'" width="40px"/>'+

                                    '</div>'+
                                    '<div class="col-md-9">'+
                                        '<div class="row">'+
                                            '<div class="col-md-12">'+rowData.lastname+', '+rowData.firstname+'</div>   ' +
                                            '<div class="col-md-12">'+ '<small class="text-primary">'+rowData.tid+'</small></div>   ' +
                                        '</div>'+
                                        
                                    
                                    '</div>'+
                                '</div>'
                                // $(td).addClass('align-middle')
                        }
                    },
                    {
                        'targets': 1,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            $(td)[0].innerHTML = rowData.amin
                                $(td).addClass('align-middle')
                        }
                    },
                    {
                        'targets': 2,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            $(td)[0].innerHTML = rowData.amout
                                $(td).addClass('align-middle')
                        }
                    },
                    {
                        'targets': 3,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            $(td)[0].innerHTML = rowData.pmin
                                $(td).addClass('align-middle')
                        }
                    },
                    {
                        'targets': 4,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            $(td)[0].innerHTML = rowData.pmout
                                $(td).addClass('align-middle')
                        }
                    },
                    {
                        'targets': 5,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            $(td)[0].innerHTML = rowData.remarks
                                $(td).addClass('align-middle')
                        }
                    },
                    {
                        'targets': 6,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            $(td)[0].innerHTML = '<button type="button" class="btn btn-sm btn-default timelogs" data-id="'+rowData.id+'">'+
                                    
                                    'Logs</button>'
                                $(td).addClass('align-middle')
                        }
                    }
                ]
            });
            $(document).on('click', '.timelogs', function(){
                var employeeid = $(this).attr('data-id')
                $('#modal-timelogs').modal('show')
                var selecteddate =  $('#select-date').val()
                $.ajax({
                    url: '/hr/attendance/gettimelogs',
                    type:"GET",
                    data:{
                        employeeid: employeeid,
                        selecteddate: selecteddate
                    },
                    success:function(data) {
                        $('#timelogsdetails').empty()
                        $('#timelogsdetails').append(data)
                    }
                });
            })
        }
        // getemployees();
        var employeeid;
        var employeename;
        var particularid;
        var particularname;
        var thisbadge;
        var defaultcont;
        var customcont;
        $('.each-badge-amount').on('click', function(){
            
            thisbadge = $(this);
            defaultcont = $(this).attr('data-default');
            customcont = $(this).attr('data-amount');
            $('#modal-each-amount').modal('show')
            $('#input-default-amount').val(defaultcont)
            $('#input-new-amount').val(customcont)
            $('#text-employeename').text($(this).attr('data-name'))
            employeeid = $(this).attr('data-employeeid')
            particularid = $(this).attr('data-particularid');
            var particularstatus = $(this).attr('data-particularstatus');
            if(particularstatus == 1)
            {
                $('#container-custom-buttons').empty()
                $('#container-custom-buttons').append(`<button type="button" class="btn btn-sm btn-outline-secondary" id="btn-allowance-disable">Disable</button>`)
            }else if(particularstatus == 0)
            {
                $('#container-custom-buttons').empty()
                $('#container-custom-buttons').append(`<button type="button" class="btn btn-sm btn-outline-secondary" id="btn-allowance-enable">Enable</button>`)
            }
        })
        $('#btn-custom-save').on('click', function(){
            Swal.fire({
                    title: 'Saving changes...',
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: false
                }) 
            if($('#input-new-amount').val().replace(/^\s+|\s+$/g, "").length > 0)
            {
                var thisamount = $('#input-new-amount').val();
                $.ajax({
                    url: '/hr/setup/allowances/custom',
                    type: 'GET',
                    data: {
                        action        : 'custom_add',
                        employeeid    : employeeid,
                        particularid  : particularid,
                        amount        : thisamount,
                    },
                    success:function(data) {
                        if(data == 1)
                        {
                            customcont = thisamount;
                            thisbadge.text(thisamount)
                            thisbadge.removeClass('badge-success');
                            thisbadge.addClass('badge-info');
                            $('#modal-each-amount').modal('toggle');
                            $(".swal2-container").remove();
                            $('body').removeClass('swal2-shown')
                            $('body').removeClass('swal2-height-auto')
                            toastr.success('Updated successfully!','Monthly Allowance')
                        }
                    }
                    ,error:function(){
                        toastr.error('Something went wrong!','Monthly Allowance')
                    }
                })
            }else{
                toastr.warning('This field cannot be empty!','Monthly Allowance')
            }
        })
        $('#btn-custom-default').on('click', function(){
            Swal.fire({
                    title: 'Saving changes...',
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: false
                }) 
                $.ajax({
                    url: '/hr/setup/allowances/custom',
                    type: 'GET',
                    data: {
                        action        : 'custom_reset',
                        amount    : $('#input-default-amount').val(),
                        employeeid    : employeeid,
                        particularid  : particularid
                    },
                    success:function(data) {
                        if(data == 1)
                        {
                            thisbadge.text(defaultcont)
                            thisbadge.addClass('badge-success');
                            thisbadge.removeClass('badge-info');
                            $('#modal-each-amount').modal('toggle');
                            $(".swal2-container").remove();
                            $('body').removeClass('swal2-shown')
                            $('body').removeClass('swal2-height-auto')
                            toastr.success('Reset successfully!','Monthly Allowance')
                        }
                    }
                    ,error:function(){
                        toastr.error('Something went wrong!','Monthly Allowance')
                    }
                })
        })
        $(document).on('click','#btn-allowance-disable', function(){
            var thisbutton = $(this);
            Swal.fire({
                    title: 'Disabling allowance...',
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: false
                }) 
                $.ajax({
                    url: '/hr/setup/allowances/custom',
                    type: 'GET',
                    data: {
                        action        : 'custom_disable',
                        employeeid    : employeeid,
                        particularid  : particularid
                    },
                    success:function(data) {
                        if(data == 1)
                        {
                            thisbutton.text('Enable')
                            thisbutton.attr('id','btn-allowance-enable')
                            thisbadge.addClass('badge-secondary');
                            thisbadge.attr('data-particularstatus','0')
                            thisbadge.removeClass('badge-info');
                            thisbadge.removeClass('badge-success');
                            $('#modal-each-amount').modal('toggle');
                            $(".swal2-container").remove();
                            $('body').removeClass('swal2-shown')
                            $('body').removeClass('swal2-height-auto')
                            toastr.success('Disabled successfully!','Monthly Allowance')
                        }
                    }
                    ,error:function(){
                        toastr.error('Something went wrong!','Monthly Allowance')
                    }
                })
        })
        $(document).on('click','#btn-allowance-enable', function(){
            var thisbutton = $(this);
            Swal.fire({
                    title: 'Enabling allowance...',
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: false
                }) 
                $.ajax({
                    url: '/hr/setup/allowances/custom',
                    type: 'GET',
                    data: {
                        action        : 'custom_enable',
                        employeeid    : employeeid,
                        particularid  : particularid
                    },
                    success:function(data) {
                        if(data == 1)
                        {
                            thisbutton.text('Disable')
                            thisbadge.text(defaultcont)
                            thisbadge.attr('data-particularstatus','1')
                            thisbadge.addClass('badge-info');
                            thisbadge.removeClass('badge-secondary');
                            $('#modal-each-amount').modal('toggle');
                            $(".swal2-container").remove();
                            $('body').removeClass('swal2-shown')
                            $('body').removeClass('swal2-height-auto')
                            toastr.success('Enabled successfully!','Monthly Allowance')
                        }
                    }
                    ,error:function(){
                        toastr.error('Something went wrong!','Monthly Allowance')
                    }
                })
        })
        $(document).on('click','.each-badge-otherdeduction-amount', function(){
            particularid = $(this).attr('data-particularid');
            employeeid = $(this).attr('data-employeeid');            
            employeename = $(this).attr('data-name');            
            particularname = $(this).attr('data-particulardesc');            
            defaultcont = $(this).attr('data-default');       
            customcont = $(this).attr('data-amount');       
            thisbadge = $(this);  
            $('#other-particular-desc').text(particularname)
            $('#other-text-employeename').text(employeename)
            $('#input-other-default-amount').val(defaultcont)
            $('#input-other-new-amount').val(customcont)
            $('#modal-other-allowance-add').modal('show')
        })
        $('#btn-modal-other-addemployee-save').on('click', function(){
            var newamount = $('#input-other-new-amount').val();
            if(newamount.replace(/^\s+|\s+$/g, "").length == 0)
            {
                $('#input-other-new-amount').css('border','1px solid red');
                toastr.warning('Please fill in required field!')
            }else{
                $.ajax({
                    url: '/hr/setup/allowances/otherdeductions',
                    type: 'get',
                    data: 
                        {
                        action        : 'add_otherdeductiontoemployee',
                        employeeid          : employeeid,
                        particularid          : particularid,
                        desc        : particularname,
                        amount        : newamount,
                        fullamount        : defaultcont
                        },
                    success:function(data) {
                        var thistd =  thisbadge.closest('td');
                        thistd.empty();
                        thistd.append(
                            `<span class="badge `+(defaultcont == newamount ? 'badge-success' : 'badge-info')+` each-badge-otherdeduction-amount-edit" style="width: 100%; font-size: 12px; cursor: pointer; border: 1px solid #ddd;" data-particularid="`+particularid+`" data-particularstatus="1" data-employeeid="`+employeeid+`" data-name="`+employeename+`" data-default="`+defaultcont+`" data-amount="`+newamount+`">`+newamount+`</span>`
                        )
                        
                        $('#modal-other-allowance-add').modal('toggle');
                    }
                    ,error:function(){
                        toastr.error('Something went wrong!')
                    }
                })
            }
        })
        $(document).on('click','.each-badge-otherdeduction-amount-edit', function(){
            particularid = $(this).attr('data-particularid');
            employeeid = $(this).attr('data-employeeid');            
            employeename = $(this).attr('data-name');            
            particularname = $(this).attr('data-particulardesc');            
            particularstatus = $(this).attr('data-particularstatus');            
            defaultcont = $(this).attr('data-default');       
            customcont = $(this).attr('data-amount');       
            thisbadge = $(this);  
            $('#other-particular-desc-edit').text(particularname)
            $('#other-text-employeename-edit').text(employeename)
            $('#input-other-default-amount-edit').val(defaultcont)
            $('#input-other-new-amount-edit').val(customcont)
            $('#modal-other-allowance-edit').modal('show')
            $('#container-otherdeductions-buttons').empty()
            $('#container-otherdeductions-buttons').append(`<button type="button" class="btn btn-sm btn-outline-danger mr-2" id="btn-otherdeduction-toemployee-delete"><i class="fa fa-trash-alt"></i></button>`)
            if(particularstatus == 1)
            {
                $('#container-otherdeductions-buttons').append(`<button type="button" class="btn btn-sm btn-outline-secondary" id="btn-otherdeduction-disable">Disable</button>`)
            }else if(particularstatus == 0)
            {
                $('#container-otherdeductions-buttons').append(`<button type="button" class="btn btn-sm btn-outline-secondary" id="btn-otherdeduction-enable">Enable</button>`)
            }
        })
        $('#btn-modal-other-editemployee-save').on('click', function(){
            var newamount = $('#input-other-new-amount-edit').val();
            if(newamount.replace(/^\s+|\s+$/g, "").length == 0)
            {
                $('#input-other-new-amount-edit').css('border','1px solid red');
                toastr.warning('Please fill in required field!')
            }else{
                $.ajax({
                    url: '/hr/setup/allowances/otherdeductions',
                    type: 'get',
                    data: 
                        {
                        action        : 'edit_otherdeductiontoemployee',
                        employeeid    : employeeid,
                        particularid  : particularid,
                        amount        : newamount
                        },
                    success:function(data) {
                        var thistd =  thisbadge.closest('td');
                        thistd.empty();
                        thistd.append(
                            `<span class="badge `+(defaultcont == newamount ? 'badge-success' : 'badge-info')+` each-badge-otherdeduction-amount-edit" style="width: 100%; font-size: 12px; cursor: pointer; border: 1px solid #ddd;" data-particularid="`+particularid+`" data-particularstatus="1" data-employeeid="`+employeeid+`" data-name="`+employeename+`" data-default="`+defaultcont+`"data-amount="`+newamount+`">`+newamount+`</span>`
                        )
                        
                        $('#modal-other-allowance-edit').modal('toggle');
                    }
                    ,error:function(){
                        toastr.error('Something went wrong!')
                    }
                })
            }
        })
        $(document).on('click','#btn-otherdeduction-disable', function(){
            var thisbutton = $(this);
            Swal.fire({
                    title: 'Disabling allowance...',
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: false
                }) 
                $.ajax({
                    url: '/hr/setup/allowances/otherdeductions',
                    type: 'GET',
                    data: {
                        action        : 'otherdeduction_disable',
                        employeeid    : employeeid,
                        particularid  : particularid
                    },
                    success:function(data) {
                        if(data == 1)
                        {
                            thisbutton.text('Enable')
                            thisbutton.attr('id','btn-otherdeduction-enable')
                            thisbadge.addClass('badge-secondary');
                            thisbadge.attr('data-particularstatus','0')
                            thisbadge.removeClass('badge-info');
                            thisbadge.removeClass('badge-success');
                            $('#modal-other-allowance-edit').modal('toggle');
                            $(".swal2-container").remove();
                            $('body').removeClass('swal2-shown')
                            $('body').removeClass('swal2-height-auto')
                            toastr.success('Disabled successfully!')
                        }
                    }
                    ,error:function(){
                        toastr.error('Something went wrong!','Monthly Allowance')
                    }
                })
        })
        $(document).on('click','#btn-otherdeduction-enable', function(){
            var thisbutton = $(this);
            Swal.fire({
                    title: 'Enabling allowance...',
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: false
                }) 
                $.ajax({
                    url: '/hr/setup/allowances/otherdeductions',
                    type: 'GET',
                    data: {
                        action        : 'otherdeduction_enable',
                        employeeid    : employeeid,
                        particularid  : particularid
                    },
                    success:function(data) {
                        if(data == 1)
                        {
                            thisbutton.text('Disable')
                            thisbutton.attr('id','btn-otherdeduction-disable')
                            thisbadge.removeClass('badge-secondary');
                            if(defaultcont == customcont)
                            {
                            thisbadge.addClass('badge-success');
                            }else{
                            thisbadge.addClass('badge-info');
                            }
                            thisbadge.attr('data-particularstatus','1')
                            $('#modal-other-allowance-edit').modal('toggle');
                            $(".swal2-container").remove();
                            $('body').removeClass('swal2-shown')
                            $('body').removeClass('swal2-height-auto')
                            toastr.success('Enabled successfully!')
                        }
                    }
                    ,error:function(){
                        toastr.error('Something went wrong!')
                    }
                })
        })
        $(document).on('click','#btn-otherdeduction-toemployee-delete', function(){
            var thisbutton = $(this);
            Swal.fire({
                title: 'Are you sure you want to delete this allowance for this employee?',
                type: 'warning',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Delete',
                showCancelButton: true,
                allowOutsideClick: false,
            }).then((confirm) => {
                    if (confirm.value) {
                        
                        $.ajax({
                            url: '/hr/setup/allowances/otherdeductions',
                            type: 'get',
                            dataType: 'json',
                            data: {
                                action  :  'delete_otherdeduction',
                                employeeid    : employeeid,
                                particularid  : particularid
                            },
                            success: function(data){
                                if(data == 1)
                                {
                                    var thistd = thisbadge.closest('td');
                                    thistd.empty()
                                    thistd.append(`
                                    <span class="badge each-badge-otherdeduction-amount" style="width: 100%; font-size: 12px; cursor: pointer; border: 1px solid #ddd;" data-particularid="`+particularid+`" data-particulardesc="`+particularname+`" data-employeeid="`+employeeid+`" data-name="`+employeename+`"  data-default="`+defaultcont+`" data-amount="`+defaultcont+`"><i class="fa fa-plus"></i>Add</span>`)
                                    toastr.success('Deleted successfully!')
                                    $('#modal-other-allowance-edit').modal('toggle');
                                }else{
                                    toastr.error('Something went wronf!')
                                }
                            }
                        })
                    }
                })
        })
        $('#table-standard-allowances_filter').closest('.row').find('div').first().append('<button type="button" class="btn btn-sm btn-outline-primary" id="btn-standard-allowance-add"><i class="fa fa-plus"></i> Add particular</button>')
        $('.btn-bracket').on('click', function(){
            var partid = $(this).attr('data-id');
            var partdesc = $(this).attr('data-desc');
            var parttype = $(this).attr('data-type');
            $('#particular-desc').text(partdesc)
            $('#modal-xl').modal('show')
            var paramet = {
                partid  : partid,
                partdesc    : partdesc,
                parttype    : parttype
            }
            $.ajax({

                    url: '/hr/setup/allowances/bracketing',
                    type: 'get',
                    data: $.param(paramet),
                    success:function(data) {
                        $('#container-brackets').empty()
                        $('#container-brackets').append(data)
                    }
                    ,error:function(){
                        toastr.error('Something went wrong!')
                    }
            })
        })
        $('#btn-standard-allowance-add').on('click', function(){
            $('#modal-standard-add').modal('show')
        })
        $('#btn-modal-standard-add-save').on('click', function(){
            var desc = $('#input-standard-add-desc').val();
            var amount = $('#input-standard-add-amount').val();
            if(desc.replace(/^\s+|\s+$/g, "").length == 0)
            {
                $('#input-standard-add-desc').css('border','1px solid red');
                toastr.warning('Please fill in required field!')
            }else{
                $.ajax({
                    url: '/hr/setup/allowances',
                    type: 'get',
                    data: 
                        {
                        action        : 'standardallowance_add',
                        desc          : desc,
                        amount        : amount
                        },
                    success:function(data) {
                        $('#table-standard-allowances tbody').append(
                            `
                            <tr>
                                <td style="vertical-align: middle; width: 50%;">`+desc+`</td>
                                <td style="vertical-align: middle;  width:  15%;" class="td-amount-`+data+` text-right pr-5">`+amount+`</td>
                                <td style="vertical-align: middle;  width:  10%;"><button type="button" class="btn btn-outline-warning btn-sm btn-block btn-allowance-standard-edit" data-id="`+data+`" data-desc="`+desc+`" data-amount="`+amount+`" data-type="0"><small class="text-bold">Edit</small></button></td>
                                <td class="text-right">
                                    <button type="button" class="btn btn-outline-danger btn-sm btn-allowance-standard-delete" data-id="`+data+`"><i class="fa fa-trash-alt"></i> </button>
                                </td>
                            </tr>`
                        )
                        $('#modal-standard-add-modal-body').find('input').val('')
                        toastr.success('Added successfully!','Standard Allowance')
                        $('#modal-standard-add').modal('toggle');
                    }
                    ,error:function(){
                        toastr.error('Something went wrong!')
                    }
                })
            }
        })
        $(document).on('click','.btn-allowance-standard-edit', function(){
            $('#modal-standard-edit-particular-desc').text($(this).attr('data-desc'))
            $('#btn-modal-standard-edit-save').attr('data-id', $(this).attr('data-id'))
            
            // input-standard-edit-amount
            // $('#input-standard-edit-desc').val($(this).attr('data-desc'))
            $('#input-standard-edit-amount').val($(this).attr('data-amount'))
            $('#modal-standard-edit').modal('show')
        })
        $('#btn-modal-standard-edit-save').on('click', function(){
            var id = $(this).attr('data-id');
            var desc = $('#input-standard-edit-desc').val();
            var amount = $('#input-standard-edit-amount').val();
            // if(desc.replace(/^\s+|\s+$/g, "").length == 0)
            // {
            //     toastr.warning('Please fill in required field!')
            // }else{
                $.ajax({
                    url: '/hr/setup/allowances',
                    type: 'get',
                    data: 
                        {
                        action        : 'standardallowance_edit',
                        id          : id,
                        desc          : desc,
                        amount        : amount
                        },
                    success:function(data) {
                        if(data == 0)
                        {
                            toastr.warning('Particular exists!')
                        }else if(data == 1){
                            $('.btn-allowance-standard-edit[data-id="'+id+'"]').attr('data-amount', amount)
                            $('#table-standard-allowances .td-amount-'+id).text((amount>0 ? amount : '0.00'))
                            $('#modal-standard-edit-modal-body').find('input').val('')
                            toastr.success('Updated successfully!','Standard Allowance')
                            $('#modal-standard-edit').modal('toggle');
                        }
                    }
                    ,error:function(){
                        toastr.error('Something went wrong!')
                    }
                })
            // }
        })
        $(document).on('click','.btn-allowance-standard-delete', function(){
            var id = $(this).attr('data-id');
            var thisbutton = $(this);
            Swal.fire({
                title: 'Are you sure you want to delete this standard allowance?',
                type: 'warning',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Delete',
                showCancelButton: true,
                allowOutsideClick: false,
            }).then((confirm) => {
                    if (confirm.value) {
                        
                        $.ajax({
                            url: '/hr/setup/allowances',
                            type: 'get',
                            dataType: 'json',
                            data: {
                                action  :  'standardallowance_delete',
                                id: id
                            },
                            success: function(data){
                                thisbutton.closest('tr').remove()
                                toastr.success('Deleted successfully!','Standard Allowance')
                            }
                        })
                    }
                })
        })
        $('#table-other-deductions_filter').closest('.row').find('div').first().append('<button type="button" class="btn btn-sm btn-outline-primary" id="btn-other-allowance-add"><i class="fa fa-plus"></i> Add particular</button>')
        
        $('#btn-other-allowance-add').on('click', function(){
            $('#modal-other-add').modal('show')
        })
        $('#btn-modal-other-add-save').on('click', function(){
            var desc = $('#input-other-add-desc').val();
            var amount = $('#input-other-add-amount').val();
            if(desc.replace(/^\s+|\s+$/g, "").length == 0)
            {
                $('#input-other-add-desc').css('border','1px solid red');
                toastr.warning('Please fill in required field!')
            }else{
                $.ajax({
                    url: '/hr/setup/allowances',
                    type: 'get',
                    data: 
                        {
                        action        : 'add_otherdeduction',
                        desc          : desc,
                        amount        : amount
                        },
                    success:function(data) {
                        $('#table-other-allowances tbody').append(
                            `
                            <tr>
                                <td style="vertical-align: middle; width: 50%;">`+desc+`</td>
                                <td style="vertical-align: middle;  width:  15%;" class="td-amount-`+data+` text-right pr-5">`+amount+`</td>
                                <td style="vertical-align: middle;  width:  10%;"><button type="button" class="btn btn-outline-warning btn-sm btn-block btn-allowance-other-edit" data-id="`+data+`" data-desc="`+desc+`" data-amount="`+amount+`" data-type="0"><small class="text-bold">Edit</small></button></td>
                                <td class="text-right">
                                    <button type="button" class="btn btn-outline-danger btn-sm btn-allowance-other-delete" data-id="`+data+`"><i class="fa fa-trash-alt"></i> </button>
                                </td>
                            </tr>`
                        )
                        $('#modal-other-add-modal-body').find('input').val('')
                        toastr.success('Added successfully!','Other Allowance')
                        $('#modal-other-add').modal('toggle');
                    }
                    ,error:function(){
                        toastr.error('Something went wrong!')
                    }
                })
            }
        })
        $(document).on('click','.btn-allowance-other-edit', function(){
            $('#modal-other-edit-particular-desc').text($(this).attr('data-desc'))
            $('#btn-modal-other-edit-save').attr('data-id', $(this).attr('data-id'))
            
            // input-standard-edit-amount
            // $('#input-standard-edit-desc').val($(this).attr('data-desc'))
            $('#input-other-edit-amount').val($(this).attr('data-amount'))
            $('#modal-other-edit').modal('show')
        })
        $('#btn-modal-other-edit-save').on('click', function(){
            var id = $(this).attr('data-id');
            var desc = $('#input-other-edit-desc').val();
            var amount = $('#input-other-edit-amount').val();
            // if(desc.replace(/^\s+|\s+$/g, "").length == 0)
            // {
            //     toastr.warning('Please fill in required field!')
            // }else{
                $.ajax({
                    url: '/hr/setup/allowances',
                    type: 'get',
                    data: 
                        {
                        action        : 'update_otherdeduction',
                        id          : id,
                        desc          : desc,
                        amount        : amount
                        },
                    success:function(data) {
                        if(data == 0)
                        {
                            toastr.warning('Particular exists!')
                        }else if(data == 1){
                            $('.btn-allowance-other-edit[data-id="'+id+'"]').attr('data-amount', amount)
                            $('#table-other-allowances .td-amount-'+id).text((amount>0 ? amount : '0.00'))
                            $('#modal-other-edit-modal-body').find('input').val('')
                            toastr.success('Updated successfully!','Other Allowance')
                            $('#modal-other-edit').modal('toggle');
                        }
                    }
                    ,error:function(){
                        toastr.error('Something went wrong!')
                    }
                })
            // }
        })
        $(document).on('click','.btn-allowance-other-delete', function(){
            var id = $(this).attr('data-id');
            var thisbutton = $(this);
            Swal.fire({
                title: 'Are you sure you want to delete this other allowance?',
                type: 'warning',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Delete',
                showCancelButton: true,
                allowOutsideClick: false,
            }).then((confirm) => {
                    if (confirm.value) {
                        
                        $.ajax({
                            url: '/hr/setup/allowances',
                            type: 'get',
                            dataType: 'json',
                            data: {
                                action  :  'delete_otherdeduction',
                                id: id
                            },
                            success: function(data){
                                thisbutton.closest('tr').remove()
                                toastr.success('Deleted successfully!','Other Allowance')
                            }
                        })
                    }
                })
        })
    })
</script>
@endsection

