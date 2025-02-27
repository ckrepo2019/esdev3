
@php

$check_refid = DB::table('usertype')->where('id',Session::get('currentPortal'))->select('refid')->first();

if(Session::get('currentPortal') == 3){
      $extend = 'registrar.layouts.app';
}else if(auth()->user()->type == 17){
      $extend = 'superadmin.layouts.app2';
}else if(auth()->user()->type == 10){
      $extend = 'hr.layouts.app';
}else if(Session::get('currentPortal') == 7){
      $extend = 'studentPortal.layouts.app2';
}else if(Session::get('currentPortal') == 9){
      $extend = 'parentsportal.layouts.app2';
}else if(Session::get('currentPortal') == 2){
      $extend = 'principalsportal.layouts.app2';
}else if(Session::get('currentPortal') == 18){
      $extend = 'ctportal.layouts.app2';
}else if(Session::get('currentPortal') == 1){
      $extend = 'teacher.layouts.app';
}else{
      if(isset($check_refid->refid)){
            if($check_refid->refid == 27){
                  $extend = 'academiccoor.layouts.app2';
            }
      }else{
            $extend = 'general.defaultportal.layouts.app';
      }
}
@endphp

@extends($extend)

@section('pagespecificscripts')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
<link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-v5-11-3/main.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-v5-11-3/main.min.css')}}">

<style>
    /* select2 */
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        margin-top: -9px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice{

        background-color: #007bff;
        border: 1px solid #007bff;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove{

        color: white;
    }
    .shadow {
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
        border: 0;
    }
    input[type=search]{
        height: calc(1.7em + 2px) !important;
    }

    .form-control {
        display: block;
        width: 100%;
        height: calc(1.7rem + 2px);
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        box-shadow: inset 0 0 0 transparent;
        transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    }

    .form-control-sm-form {
            height: calc(1.4rem + 1px);
            padding: 0.75rem 0.3rem;
            font-size: .875rem;
            line-height: 1.5;
            border-radius: 0.2rem;
    }

    label{
        
        font-size: 0.8rem;
    }

    .card-sm{
        width: 13.5rem;
    }

    .terms-wapper {
        display: flex;
        justify-content: space-evenly;
    }

    label.form-check-label.terms,
    label.form-check-label.termsEdit {
        width: 85px;
        height: 35px;
        background: #4a474a;
        color: white;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 5px;
        cursor: pointer;
    }

    label.form-check-label.terms:hover{

        border: 1px solid #0a48a5;
        background: #0a48a5;
    }

    input#prelim:checked ~ label,
    input#midterm:checked ~ label,
    input#prefi:checked ~ label,
    input#final:checked ~ label
    {

        background-color: green;
        border-color: green;
    }

    input#prelimEdit:checked ~ label,
    input#midtermEdit:checked ~ label,
    input#prefiEdit:checked ~ label,
    input#finalEdit:checked ~ label
    {

        background-color: green;
        border-color: green;
    }

    .select-wrapper {
        margin-bottom: 15px;
    }

    .form-check.form-switch {
        margin-bottom: 15px;
    }

    .hidden{

        display: none;
    }

    .result{

        max-height: 250px;
        height: auto;
        background: white;
        left: 8px;
        top: 4px;
        position: absolute;
        overflow-y: scroll;
        border: 1px solid #aaa;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        z-index: 1;

    }

    .result_list li{
        padding: 6px 12px;
        cursor: pointer;
    }

    .result_list li:hover{

        background: #0074f0;
    }

    .clearBtn {
        position: absolute;
        top: 111px;
        right: 15px;
        transition: right 0.2s;
        font-size: 20px;
        font-weight: 500;
        cursor: pointer;
        color: black;
    }


</style>
@endsection

@section('content')

    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel='stylesheet' href="{{asset('plugins/fullcalendar-v5-11-3/main.css')}}" />



    <!-- Add Modal -->
    <div class="modal fade addModal" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModal">Add New Setup</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="addmodalHolder">
                    <form id="form" autocomplete="off">

                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6 form-group mb-3 select-wrapper">
                                    <label>School Year</label>
                                    <select class="form-control select2 sy" aria-label="Default select example" id="sy"></select>
                                </div>
            
                                <div class="col-md-6 form-group mb-3 select-wrapper">
                                    <label>Semester</label>
                                    <select class="form-control select2 semester" aria-label="Default select example" id="semester"></select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mb-0">
                            <label for="setupDesc">Setup Description</label>
                            <input type="text" class="inputs form-control" id="setupDesc" placeholder="Setup Description">
                            <span style="font-size: 12px;" class="ml-2 text-danger hidden" id="setupDescError"></span>
                        </div>
                        
                        <label>Select Term Grade:</label>
                        <div class="terms-wapper mb-3">
    
                            <div>
                                <input hidden onClick="isChecked('prelim', 'prelim-div')" class="form-check-input checkbox" type="checkbox" id="prelim" value="1">
                                <label class="form-check-label terms" for="prelim">Prelim</label>
                            </div>
                            <div>
                                <input hidden onClick="isChecked('midterm', 'midterm-div')" class="form-check-input checkbox" type="checkbox" id="midterm" value="1">
                                <label class="form-check-label terms" for="midterm">Midterm</label>
                            </div>
                            <div>
                                <input hidden onClick="isChecked('prefi', 'prefi-div')" class="form-check-input checkbox" type="checkbox" id="prefi" value="1">
                                <label class="form-check-label terms" for="prefi">Pre-Final</label>
                            </div>
                            <div>
                                <input hidden onClick="isChecked('final', 'final-div')" class="form-check-input checkbox" type="checkbox" id="final" value="1">
                                <label class="form-check-label terms" for="final">Finals</label>
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="row">
                       
                                <div class="col-md-6 form-group  mb-3 select-wrapper">
                                    <label>Grading Scale</label>
                                    <select class="form-control select2" aria-label="Default select example" id="isPointScaled">
                                        <option value="1"selected>Decimal Point Scale</option>
                                        <option value="0">Numerical Grade Scale</option>
                                    </select>
                                </div>
    
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6  form-group mb-3">
                                            <label for="prelim">Passsing Grade</label>
                                            <input type="number" class="inputs form-control" id="passingRate" placeholder="1-5" value="3">
                                        </div>
                                        <div class="col-md-6  form-group mb-3">
                                            <label for="decimalPoint"> Decimal Place</label>
                                            <input type="number" class="inputs form-control" id="decimalPoint" placeholder=".00">
                                        </div>
                                    </div>
                                </div>
    
                            </div>
                        </div>
    


                        <div class="col-md-12  form-group mb-0">
                            <label for="formula">Formula</label>
                            <p class="mb-1" >Make sure to add ($) in every variable or check the variable spellings.</p>
                            <label class="mb-2 font-weight-bold">Grading Formula</label>
                      
                            
                            <span class="result hidden" for="formula">
                                <ul class="result_list p-0 m-0">
                                    <li>
                                        <a data-value="( $prelim + $midterm + $prefi + $final ) / 4" class="formula_item">( $prelim + $midterm + $prefi + $final ) / 4</a>
                                    </li>
                                    <li>
                                        <a data-value="( $midterm + $final ) / 2" class="formula_item">( $midterm + $final ) / 2</a>
                                    </li>
                                    <li>
                                        <a data-value="( $midterm * .3 ) + ( $final * .7 )" class="formula_item">( $midterm * .3 ) + ( $final * .7 )</a>
                                    </li>
                                </ul>
                            </span>

                            <input class="inputs form-control formula" id="formula" optionselected="0" placeholder="eg. ($prelim + $miterm)/2">
                            <span id="clearFormula" class="clearBtn hidden">×</span>
                            <span style="font-size: 12px;" class="ml-2 text-danger hidden" id="formulaError"></span>

                            <div class="mb-2 mt-2 ml-2">
                                <div class="row">
                                    <div class="col-md-3 hidden"  id="prelimvariable">
                                        <a id="prelimVar" style="cursor: pointer" data-value="$prelim" class="m-0 text-info text-sm">$prelim</a>
                                    </div>
                                    <div class="col-md-3 hidden" id="midtermvariable">
                                        <a id="midtermVar" style="cursor: pointer" data-value="$midterm" class="m-0 text-info text-sm">$midterm</a>
                                    </div>
                                    <div class="col-md-3 hidden" id="prefivariable" >
                                        <a id="prefiVar" style="cursor: pointer" data-value="$prefi" class="m-0 text-info text-sm">$prefi</a>
                                    </div>
                                    <div class="col-md-3 hidden" id="finalvariable" >
                                        <a id="finalVar" style="cursor: pointer" data-value="$final" class="mb-0 text-info text-sm">$final</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-check form-switch pl-2">
                            <input onClick="isPerecentageSpecified()" hidden class="form-check-input" type="checkbox" role="switch" id="checkboxPercentage">
                            <label class="form-check-label" for="checkboxPercentage"><i class="fas fa-sort-down align-middle" style="padding-b mt-1ottom: 7px;margin-right: 10px;font-size:9px"></i> Terms Advance Setup</label>
                        </div>

                        <div class="row">
                            <div class="col-md-6" style="display:none" id="prelim-div">
                                {{--  --}}
                                <div>
                                    <div class="card card-sm">
                                        <label class="card-header">Prelim Setup</label>
                                        <div class="card-body d-flex flex-column" style="padding: 0.60rem;">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="input-labels" for="prelim">Transmutation </label>
                                                </div>
                                                <div class="col-md-6">
                                                    <button id="prelimviewTrans" type="button" class="hidden btn btn-outline-primary btn-sm float-right mt-1" style="padding: 0rem 0.5rem;font-size:9px">View</button>
                                                </div>
                                            </div>
                                            <select id="prelimTransmutID" name="faculty" class="transmutation_select form-control select2"></select>
            
                                            <div class="form-check form-switch mt-1">
                                                <input class="form-check-input" type="checkbox" role="switch" id="isPrelimDisplay">
                                                <label class="form-check-label" for="isPrelimDisplay">Student Display</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" style="display:none" id="midterm-div">
                                {{--  --}}
                                <div>
                                    <div class="card card-sm">
                                        <label class="card-header">Midterm Setup</label>
                                        <div class="card-body d-flex flex-column" style="padding: 0.60rem;">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="input-labels">Transmutation </label>
                                                </div>
                                                <div class="col-md-6">
                                                    <button id="midtermviewTrans" type="button" class="hidden btn btn-outline-primary btn-sm float-right view_transmute mt-1" style="padding: 0rem 0.5rem;font-size:9px">View</button>
                                                </div>
                                            </div>
                                            <select id="midtermTransmutID" name="faculty" class="transmutation_select form-control select2"></select>
            
                                            <div class="form-check form-switch mt-1">
                                                <input class="form-check-input" type="checkbox" role="switch" id="isMidtermDisplay">
                                                <label class="form-check-label" for="isMidtermDisplay">Student Display</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="col-md-6" style="display:none" id="prefi-div">
                                {{--  --}}
                                <div>
                                    <div class="card card-sm">
                                        <label class="card-header">Pre-Final Setup</label>
                                        <div class="card-body d-flex flex-column" style="padding: 0.60rem;">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="input-labels">Transmutation </label>
                                                </div>
                                                <div class="col-md-6">
                                                    <button id="prelimviewTrans" type="button" class="hidden btn btn-outline-primary btn-sm float-right view_transmute mt-1" style="padding: 0rem 0.5rem;font-size:9px">View</button>
                                                </div>
                                            </div>
                                            <select id="prefiTransmutID" name="faculty" class="transmutation_select form-control select2"></select>
            
                                            <div class="form-check form-switch mt-1">
                                                <input class="form-check-input" type="checkbox" role="switch" id="isPrefiDisplay">
                                                <label class="form-check-label" for="isPrefiDisplay">Student Display</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="col-md-6" style="display:none" id="final-div">
                                {{--  --}}
                                <div>
                                    <div class="card card-sm">
                                        <label class="card-header">Final Setup</label>
                                        <div class="card-body d-flex flex-column" style="padding: 0.60rem;">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="input-labels">Transmutation </label>
                                                </div>
                                                <div class="col-md-6">
                                                    <button id="finalviewTrans" type="button" class="hidden btn btn-outline-primary btn-sm float-right view_transmute mt-1" style="padding: 0rem 0.5rem;font-size:9px">View</button>
                                                </div>
                                            </div>
                                            <select id="finalTransmutID" name="faculty" class="transmutation_select form-control select2"></select>
            
                                            <div class="form-check form-switch mt-1">
                                                <input class="form-check-input" type="checkbox" role="switch" id="isFinalDisplay">
                                                <label class="form-check-label" for="isFinalDisplay">Student Display</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" style="display:none" id="finalgrade-div">
                                {{--  --}}
                                <div>
                                    <div class="card card-sm">
                                        <label class="card-header">Final Grade Setup</label>
                                        <div class="card-body d-flex flex-column" style="padding: 0.60rem;">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="input-labels">Transmutation </label>
                                                </div>
                                                <div class="col-md-6">
                                                    <button id="finalgradeviewTrans" type="button" class="hidden btn btn-outline-primary btn-sm float-right view_transmute mt-1" style="padding: 0rem 0.5rem;font-size:9px">View</button>
                                                </div>
                                            </div>
                                            <select id="finalgradeTransmutID" name="faculty" class="transmutation_select form-control select2"></select>
            
                                            <div class="form-check form-switch mt-1">
                                                <input checked disabled class="form-check-input" type="checkbox" role="switch" id="isFinalGradeDisplay">
                                                <label class="form-check-label" for="isFinalGradeDisplay">Student Display</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-add btn-primary">Add</button>
            </div>
            </div>
        </div>
    </div>
    <!-- Add Modal END-->

    <!-- Edit Modals -->
    <div class="modal fade editModal" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModal">Edit Semester Setup</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEdit" autocomplete="off">

                    <input type="hidden" id="id-input-edit">

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 form-group mb-3 select-wrapper">
                                <label>School Year</label>
                                <select class="form-control select2 sy" aria-label="Default select example" id="syEdit"></select>
                            </div>
        
                            <div class="col-md-6 form-group mb-3 select-wrapper">
                                <label>Semester</label>
                                <select class="form-control select2 semester" aria-label="Default select example" id="semesterEdit"></select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="setupDescEdit">Setup Description</label>
                        <input type="text" class="inputs form-control" id="setupDescEdit" placeholder="Setup Description">
                        <span style="font-size: 12px" class="ml-2 text-danger hidden" id="setupDescEditError"></span>
                    </div>

                    
                    <label>Select Term Grade:</label>
                    <div class="terms-wapper mb-3">

                        <div>
                            <input hidden onClick="isCheckedEdit('prelimEdit', 'prelim-div-edit')" class="form-check-input checkbox" type="checkbox" id="prelimEdit" value="1">
                            <label class="form-check-label termsEdit" for="prelimEdit">Prelim</label>
                        </div>
                        <div>
                            <input hidden onClick="isCheckedEdit('midtermEdit', 'midterm-div-edit')" class="form-check-input checkbox" type="checkbox" id="midtermEdit" value="1">
                            <label class="form-check-label termsEdit" for="midtermEdit">Midterm</label>
                        </div>
                        <div>
                            <input hidden onClick="isCheckedEdit('prefiEdit', 'prefi-div-edit')" class="form-check-input checkbox" type="checkbox" id="prefiEdit" value="1">
                            <label class="form-check-label termsEdit" for="prefiEdit">Pre-Final</label>
                        </div>
                        <div>
                            <input hidden onClick="isCheckedEdit('finalEdit', 'final-div-edit')" class="form-check-input checkbox" type="checkbox" id="finalEdit" value="1">
                            <label class="form-check-label termsEdit" for="finalEdit">Finals</label>
                        </div>
                    </div>

                    
                    <div class="col-md-12">
                        <div class="row">
                   
                            <div class="col-md-6 form-group  mb-3 select-wrapper">
                                <label>Grading Scale</label>
                                <select class="form-control select2" aria-label="Default select example" id="isPointScaledEdit">
                                    <option value="1"selected>Decimal Point Scale</option>
                                    <option value="0">Numerical Grade Scale</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6  form-group mb-3">
                                        <label for="passingRateEdit">Passsing Grade</label>
                                        <input type="number" class="inputs form-control" id="passingRateEdit" placeholder="70 (%)">
                                    </div>
                                    <div class="col-md-6  form-group mb-3">
                                        <label for="decimalPointEdit"> Decimal Place</label>
                                        <input type="number" class="inputs form-control" id="decimalPointEdit" placeholder=".00">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-md-12  form-group mb-3">
                        <label for="formulaEdit">Formula</label>
                        <p>Note: Make sure to add ($) in every variable.</p>
                        <p class="mb-2 text-sm font-weight-bold">Grading Formula</p>
                       
                        <input class="inputs form-control formulaEdit" id="formulaEdit" placeholder="eg. ($prelim + $miterm)/2">
                        <span style="font-size: 12px;" class="ml-2 text-danger hidden" id="formulaErrorEdit"></span>

                        <div class="mb-2 mt-2 ml-2">
                            <div class="row">
                                <div id="prelimEditvariable" class="col-md-3 hidden">
                                    <a id="prelimEditVar" style="cursor: pointer" data-value="$prelim"  class="m-0 text-info text-sm ">$prelim</a>
                                </div>
                                <div id="midtermEditvariable"  class="col-md-3 hidden">
                                    <a id="midtermEditVar" style="cursor: pointer" data-value="$midterm"  class="m-0 text-info text-sm ">$midterm</a>
                                </div>
                                <div id="prefiEditvariable" class="col-md-3 hidden">
                                    <a id="prefiEditVar" style="cursor: pointer" data-value="$prefi"  class="m-0 text-info text-sm">$prefi</a>
                                </div>
                                <div id="finalEditvariable" class="col-md-3 hidden">
                                    <a id="finalEditVar" style="cursor: pointer" data-value="$final"   class="mb-0 text-info text-sm">$final</a>
                                </div>
                                
                            </div>
                        </div>
                    </div>

                    <div class="form-check form-switch pl-2">
                        <input onClick="isPerecentageSpecifiedEdit()" hidden class="form-check-input" type="checkbox" role="switch" id="checkboxPercentageEdit">
                        <label class="form-check-label" for="checkboxPercentageEdit"><i class="fas fa-sort-down align-middle" style="padding-bottom: 7px;margin-right: 10px;"></i> Terms Advance Setup</label>
                    </div>
                    
                    <div class="row">

                        {{--  --}}
                        <div  class="col-md-6" style="display:none" style="display:none" id="prelim-div-edit">
                            <div class="card">
                                <label class="card-header">Prelim Setup</;>
                                <div class="card-body d-flex flex-column" style="padding: 0.60rem;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="input-labels">Prelim </label>
                                        </div>
                                        <div class="col-md-6">
                                            <button id="prelimviewTransEdit" type="button" class="hidden btn btn-outline-primary btn-sm float-right view_transmute" style="padding: 0rem 0.5rem;font-size: 9px;">View</button>
                                        </div>
                                    </div>
                                    <select id="prelimTransmutIDEdit" name="faculty" class="transmutation_select form-control select2"></select>

                                    <div class="form-check form-switch mt-3">
                                        <input class="form-check-input" type="checkbox" role="switch" id="isPrelimDisplayEdit">
                                        <label class="form-check-label" for="isPrelimDisplayEdit">Student Display</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--  --}}
                        <div class="col-md-6" style="display:none" id="midterm-div-edit">
                            <div class="card">
                                <label class="card-header">Midterm Setup</label>
                                <div class="card-body d-flex flex-column" style="padding: 0.60rem;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="input-labels">Midterm </label>
                                        </div>
                                        <div class="col-md-6">
                                            <button id="midtermviewTransEdit" type="button" class="hidden btn btn-outline-primary btn-sm float-right view_transmute" style="padding: 0rem 0.5rem;font-size: 9px;">View</button>
                                        </div>
                                    </div>
                                    <select id="midtermTransmutIDEdit" name="faculty" class="transmutation_select form-control select2"></select>

                                    <div class="form-check form-switch mt-3">
                                        <input class="form-check-input" type="checkbox" role="switch" id="isMidtermDisplayEdit">
                                        <label class="form-check-label" for="isMidtermDisplayEdit">Student Display</label>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        {{--  --}}
                        <div  class="col-md-6" style="display:none" id="prefi-div-edit">
                            <div class="card">
                                <label class="card-header">Pre-Final Setup</label>
                                <div class="card-body d-flex flex-column" style="padding: 0.60rem;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="input-labels">Prefinal </label>
                                        </div>
                                        <div class="col-md-6">
                                            <button id="prefiviewTransEdit" type="button" class="hidden btn btn-outline-primary btn-sm float-right view_transmute" style="padding: 0rem 0.5rem;font-size: 9px;">View</button>
                                        </div>
                                    </div>
                                    <select id="prefiTransmutIDEdit" name="faculty" class="transmutation_select form-control select2"></select>

                                    <div class="form-check form-switch mt-3">
                                        <input class="form-check-input" type="checkbox" role="switch" id="isPrefiDisplayEdit">
                                        <label class="form-check-label" for="isPrefiDisplayEdit">Student Display</label>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        {{--  --}}
                        <div  class="col-md-6" style="display:none" id="final-div-edit">
                            <div class="card">
                                <label class="card-header">Final Setup</label>
                                <div class="card-body d-flex flex-column" style="padding: 0.60rem;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="input-labels">Final </label>
                                        </div>
                                        <div class="col-md-6">
                                            <button id="finalviewTransEdit" type="button" class="hidden btn btn-outline-primary btn-sm float-right view_transmute" style="padding: 0rem 0.5rem;font-size: 9px;">View</button>
                                        </div>
                                    </div>
                                    <select id="finalTransmutIDEdit" name="faculty" class="transmutation_select form-control select2"></select>

                                    <div class="form-check form-switch mt-3">
                                        <input class="form-check-input" type="checkbox" role="switch" id="isFinalDisplayEdit">
                                        <label class="form-check-label" for="isFinalDisplayEdit">Student Display</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--  --}}
                        <div  class="col-md-6" style="display:none" id="finalgrade-div-edit">
                            <div class="card">
                                <label class="card-header">Final Grade Setup</label>
                                <div class="card-body d-flex flex-column" style="padding: 0.60rem;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="input-labels">Final Grade </label>
                                        </div>
                                        <div class="col-md-6">
                                            <button id="finalgradeviewTransEdit" type="button" class="hidden btn btn-outline-primary btn-sm float-right view_transmute" style="padding: 0rem 0.5rem;font-size: 9px;">View</button>
                                        </div>
                                    </div>
                                    <select id="finalgradeTransmutIDEdit" name="faculty" class="transmutation_select form-control select2"></select>

                                    <div class="form-check form-switch mt-3">
                                        <input checked disabled class="form-check-input" type="checkbox" role="switch" id="isFinalGradeDisplayEdit">
                                        <label class="form-check-label" for="isFinalGradeDisplayEdit">Student Display</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-save btn-primary">Save</button>
            </div>
            </div>
        </div>
    </div>
    <!-- Edit Modals END -->

    <div class="modal fade addTransmutation" id="addTransmutation" tabindex="-1" data-toggle="modal" aria-labelledby="addTransmutationLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTransmutationLabel">New Transmutation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="addtranmutationHolder">
                    <div class="form-group mb-3">
                        <label for="transDesc" >Description</label>
                        <input readonly type="text" class="inputs form-control transDesc" id="transDesc">
                    </div>
    
                    <hr class="mb-1 mt-0">
                    
                    <div class="row ml-2">
                        <div class="col-md-4">
                            <label for="initial1">Initial 1</label>
                        </div>
                        <div class="col-md-4">
                            <label for="initial2">Initial 2</label>
                        </div>
                        <div class="col-md-4">
                            <label  for="final">Final</label>
                        </div>
                    </div>
                    
                    <div>
                        <form id="form_values_setup">
                            <div class="row" id="row_1">
                                <div style="padding-top: 5px;cursor:pointer;">
                                    {{-- <span class="remove_row">×</span> --}}
                                </div>
                                <div class="col-md-4  form-group mb-1">
                                    <input readonly type="number" name="initial1" class="inputs form-control initial1" id="initial1">
                                </div>
                                <div class="col-md-4 ">
                                    <input readonly type="number" name="initial2" class="inputs form-control initial2" id="initial2">
                                </div>
                                <div class="col-md-4 ">
                                    <input readonly type="number"  name="final" class="inputs form-control final" id="final">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- <button type="button" class="btn link-primary add_row">
                    <i class="fas fa-plus mr-1"></i>Add Row
                </button>
                 --}}
            </div>
            <div class="modal-footer" id="createfooter">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn add_transmutation btn-primary" id="add_transmutation">Create</button>
            </div>
            <div class="modal-footer justify-content-between hidden" id="viewfooter">
                {{-- <div>
                    <button type="button" class="btn edit_transmutation btn-primary">Edit</button>
                    <button type="button" class="btn delete_transmutation btn-danger">Delete</button>
                </div> --}}
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>

    {{-- <div class="modal fade transmutation" id="transmutation" tabindex="-1" data-toggle="modal" aria-labelledby="transmutationLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transmutationLabel">New Transmutation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="form-group mb-3">
                    <label for="transDesc">Description</label>
                    <input type="text" class="inputs form-control transDescView" id="transDescView">
                </div>

                <hr class="mb-1 mt-0">
                
                <div class="row ml-2">
                    <div class="col-md-4">
                        <label for="initial1">Initial 1</label>
                    </div>
                    <div class="col-md-4">
                        <label for="initial2">Initial 2</label>
                    </div>
                    <div class="col-md-4">
                        <label  for="final">Final</label>
                    </div>
                </div>
                    
                    <div>
                        <form id="form_values_setup">
                            <div class="row" id="element_1">
                                <div style="padding-top: 5px;cursor:pointer;">
                                    <span class="remove_row">×</span>
                                </div>
                                <div class="col-md-4  form-group mb-1">
                                    <input type="number" name="viewinitial1" class="inputs form-control initial1" id="viewinitial1">
                                </div>
                                <div class="col-md-4 ">
                                    <input type="number" name="viewinitial2" class="inputs form-control initial2" id="viewinitial2">
                                </div>
                                <div class="col-md-4 ">
                                    <input type="number" name="viewfinal" class="inputs form-control final" id="viewfinal">
                                </div>
                            </div>
                        </form>
                    </div>

                <button type="button" class="btn link-primary add_row">
                    <i class="fas fa-plus mr-1"></i>Add Row
                </button>
                
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-8">
                        <button type="button" class="btn edit_transmutation btn-primary">Edit</button>
                        <button type="button" class="btn delete_transmutation btn-primary">Delete</button>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div> --}}

    
    <!-- BODY -->
    <div class="contents">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Grade Setup</h1>
                        </div>
                        <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/home">Home</a></li>
                            <li class="breadcrumb-item active">Grade Setup</li>
                        </ol>
                        </div>
                </div>
            </div>
        </section>
        <section class="content pt-0">
            
            <div class="container-fluid">
                <div class="card" style="font-size: 0.9rem">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fa fa-filter"></i> Filter</h3> 
                    </div>
                    <div class="card-body p-0">
    
                        <div style="padding: 12px 12px 20px 12px">
                            
                            <form id="selection_form">
    
                                <div class="row">
                                    <div class="col-md-2  form-group mb-0">
                                        <div class="select_container_attendance">
                                            <label for="syidFilter">School Year</label>
                                            <select id="syidFilter" name="syidFilter" class="sy form-control form-control-sm select2">
                                        
                                            </select>
                                        </div>
                                    
                                    </div>
                                    
                                    <div class="col-md-2  form-group mb-0">
                                        <div class="select_container_attendance">
                                            <label for="semidFilter">Semester</label>
                                            <select id="semidFilter" name="semidFilter" class="semester form-control form-control-sm select2"></select>
                                        </div>
                                        
                                    </div>
                    
                                </div>
                
                                
                            </form>
                        </div>
                    </div>
                </div>
    
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="card shadow">
                            <div class="card-body  p-3">
                                <div class="row">
                                    <div class="col-md-12" style="font-size:.9rem !important">
                                        <table class="table-hover table table-striped table-sm table-bordered table-head-fixed nowrap display compact" style="font-size: 13px;" dataid="subscbr-tbl" id="semestertable" width="100%" >
                                            <thead>
                                                <tr>
                                                    <th width="20%" class="text-left">Description</th>
                                                    <th width="10%" class="text-center">SY / Sem</th>

                                                    <th width="10%" class="text-center">Terms</th>


                                                    <th width="5%" class="text-center">Passsing</th>
                                                    <th width="5%" class="text-center">Scaling</th>
                                                    <th width="10%" class="text-center">GD</th>

                                                    <th width="5%" class="text-center">Decimal</th>
                                                    <th width="20%" class="text-center">Formula</th>
                                                    <th width="5%" class="text-center">Active</th>
                                                    <th width="10%" class="text-center"> </th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>


    <script src="{{asset('plugins/fullcalendar-v5-11-3/main.js') }}"></script>
    <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>

    <script>

        $(document).ready(function () {

            var syidActive = '<?php echo DB::table('sy')->where('isactive',1)->first()->id; ?>';
            var semidActive = '<?php echo DB::table('semester')->where('isactive',1)->first()->id; ?>';
            var selectedSY;
            var selectedSEM;
            var counter = 1;
            var semestersetup_g = @json($data);

            load_setup_datatable();
            getTransmutations();
            getSemester();
            getSchoolyear();
            getGradeScale();


            $('#checkboxPercentage').change(function () {
                var isChecked = $(this).is(':checked');

                $('#prelim-input').attr('disabled', !isChecked);
                $('#midterm-input').attr('disabled', !isChecked);
                $('#prefi-input').attr('disabled', !isChecked);
                $('#final-input').attr('disabled', !isChecked);

            });

            $('#checkboxPercentageEdit').change(function () {
                var isChecked = $(this).is(':checked');

                $('#prelim-input-edit').attr('disabled', !isChecked);
                $('#midterm-input-edit').attr('disabled', !isChecked);
                $('#prefi-input-edit').attr('disabled', !isChecked);
                $('#final-input-edit').attr('disabled', !isChecked);

            });

            $('.transmutation_select').change(function () {

                // if($(this).val() == 0){

                //     $('#addTransmutation').modal();
                // }
           
            })

            $('#prelimTransmutID').change(function () {

                if($(this).val() > 0){

                    $('#prelimviewTrans').removeClass('hidden');
                    $('#prelimviewTrans').attr('data-id', $(this).val());

                }else{
                    $('#prelimviewTrans').addClass('hidden');
                }
            })

            $('#midtermTransmutID').change(function () {

                if($(this).val() != 0){

                    $('#midtermviewTrans').removeClass('hidden')
                    $('#midtermviewTrans').attr('data-id', $(this).val());


                }
            })

            $('#prefiTransmutID').change(function () {

                if($(this).val() > 0){

                    $('#prefiviewTrans').removeClass('hidden')
                    $('#prefiviewTrans').attr('data-id', $(this).val());

                }else{
                    $('#prefiviewTrans').addClass('hidden');
                }
            })

            $('#finalTransmutID').change(function () {

                if($(this).val() > 0){

                    $('#finalviewTrans').removeClass('hidden')
                    $('#finalviewTrans').attr('data-id', $(this).val());
                }else{
                    $('#finalviewTrans').addClass('hidden');
                }
            })

            $('#finalgradeTransmutID').change(function () {

                if($(this).val() > 0){

                    $('#finalgradeviewTrans').removeClass('hidden')
                    $('#finalgradeviewTrans').attr('data-id', $(this).val());
                }else{
                    $('#finalgradeviewTrans').addClass('hidden');
                }
            })

            
            $('#prelimTransmutIDEdit').change(function () {

                if($(this).val() > 0){

                    $('#prelimviewTransEdit').removeClass('hidden');
                    $('#prelimviewTransEdit').attr('data-id', $('#prelimTransmutIDEdit').val());
                }else{
                    $('#prelimviewTransEdit').addClass('hidden');
                }
            })

            $('#midtermTransmutIDEdit').change(function () {

                if($(this).val() > 0){

                    $('#midtermviewTransEdit').removeClass('hidden')
                    $('#midtermviewTransEdit').attr('data-id', $('#midtermTransmutIDEdit').val());

                }else{
                    $('#midtermviewTransEdit').addClass('hidden');
                }
            })

            $('#prefiTransmutIDEdit').change(function () {

                if($(this).val() > 0){

                    $('#prefiviewTransEdit').removeClass('hidden')
                    $('#prefiviewTransEdit').attr('data-id', $('#prefiTransmutIDEdit').val());

                }else{
                    $('#prefiviewTransEdit').addClass('hidden');
                }
            })

            $('#finalTransmutIDEdit').change(function () {

                if($(this).val() > 0){

                    $('#finalviewTransEdit').removeClass('hidden')
                    $('#finalviewTransEdit').attr('data-id', $('#finalTransmutIDEdit').val());

                }else{
                    $('#finalviewTransEdit').addClass('hidden');
                }
            })

            $('#finalgradeTransmutIDEdit').change(function () {

                if($(this).val() > 0){

                    $('#finalgradeviewTransEdit').removeClass('hidden')
                    $('#finalgradeviewTransEdit').attr('data-id', $('#finalgradeTransmutIDEdit').val());
                    
                }else{
                    $('#finalgradeviewTransEdit').addClass('hidden');
                }
            })

            function getSemester(){

                var semester = @json($semester)


                $('.semester').empty()
                $('.semester').append('<option value="">Select Semester</option>')
                $(".semester").select2({
                    data: semester,
                    allowClear: false,
                    placeholder: "Select Semester",
                })

                if(semidActive != null){

                    $('.semester').val(semidActive).change()
                }
                $('#semester').select2({
                    disabled:true,
                });
  
      
            }

            function getSchoolyear(){

                var sy_g = @json($sy);

                $('.sy').empty()
                $('.sy').append('<option value="">Select SY</option>')
                $(".sy").select2({
                    data: sy_g,
                    allowClear: false,
                    placeholder: "Select School Year",
                })

                if(syidActive != null){

                    $('.sy').val(syidActive).change()
                }

                $('#sy').select2({
                    disabled:true,
                })


            }

            function getTransmutations() {
                
                var transmutation = @json($transmutation);

                console.log(transmutation);

                $('.transmutation_select').empty()
                $('.transmutation_select').append('<option value="">Select Transmutation</option>')
                // $('.transmutation_select').append('<option value="0">New</option>')
                $(".transmutation_select").select2({
                    data: transmutation,
                    allowClear: true,
                    placeholder: "Select Transmutation",
                })
            }
            
            function getGradeScale() {
                
                var gradeScale = [
                    {id:'1', text:'Decimal Point Scale'},
                    {id:'0', text:'Numerical Grade Scale'},
                ];

                $('#isPointScaled').empty()
                $('#isPointScaled').append('<option value="">Select Transmutation</option>')
                $("#isPointScaled").select2({
                    data: gradeScale,
                    // allowClear: true,
                    placeholder: "Select Grade Scale",
                })

                $("#isPointScaled").val(1).change();

                $('#isPointScaledEdit').empty()
                $('#isPointScaledEdit').append('<option value="">Select Transmutation</option>')
                $("#isPointScaledEdit").select2({
                    data: gradeScale,
                    // allowClear: true,
                    placeholder: "Select Grade Scale",
                })

                $("#isPointScaledEdit").val(1).change();

            }


            
            function get_setup_data(){

                let syid = $('#syidFilter').val();
                let semid = $('#semidFilter').val();

                $.ajax({

                    url: '{{ route("semester.getsetupdata") }}',
                    method:'GET',
                    data: {
                        syid:syid,
                        semid:semid,
                    },
                    success:function(response){
                        
                        semestersetup_g = response;
                        load_setup_datatable();
                    },
                    error:function(error){
                        console.log(error)
                    }
                
                });

            }

            function load_setup_datatable(){

                $("#semestertable").DataTable({
                    scrollX: true,
                    destroy: true,
                    data: semestersetup_g,
                    lengthChange : false,
                    columns: [
                                { "data": 'setup_desc' },
                                { "data": 'id' },
                               
                                { "data": null },
                                { "data": 'passingRate' },
                                { "data": null },
                                { "data": null },
                                { "data": 'decimalPoint' },
                                { "data": null },
                                { "data": 'id' },
                                { "data": 'id' },
                        ],
                    columnDefs: [
 
                        {
                            'targets': 0,
                            'orderable': false, 
                            'createdCell':  function (td, cellData, rowData, row, col) {
                                $(td).addClass('align-middle');
                                
                            }
                        },
                        {
                            'targets': 1,
                            'orderable': false, 
                            'createdCell':  function (td, cellData, rowData, row, col) {
                                var buttons = 
                                    '<div class="d-flex" style="flex-direction:column">'
                                        +'<label class="m-0 text-info">'+rowData.sydesc+'</label>'
                                        +'<p value ="'+rowData.id+'" class="mb-0">'+rowData.semester+'</p>'
                                    +'</div>'
                                $(td)[0].innerHTML =  buttons;
                                $(td).addClass('text-center');
                                $(td).addClass('align-middle');
                                
                            }
                        },
                        {
                            'targets': 2,
                            'orderable': false, 
                            'createdCell':  function (td, cellData, rowData, row, col) {
             
                                var array = [rowData.prelim, rowData.midterm, rowData.prefi, rowData.final, 1]
                                var tems = ['Prelim', 'Midterm', 'Prefinal', 'Final', 'Final Grade']
                                var button = '<div style="white-space: normal;">';
                                for (let index = 0; index < array.length; index++) {

                                    if(array[index] != 0){
                                        button += '<span class="badge badge-primary mr-2">'+tems[index]+'</span>';
                                    }
                                    
                                }
                                button += '</div>';
                                $(td)[0].innerHTML = button;
                                $(td).addClass('text-center');
                                $(td).addClass('align-middle');

                            }
                        },
                        {
                            'targets': 3,
                            'orderable': false, 
                            'createdCell':  function (td, cellData, rowData, row, col) {
                                
                                $(td).addClass('text-center');
                                $(td).addClass('align-middle');
                            }
                        },
                        {
                            'targets': 4,
                            'orderable': false, 
                            'createdCell':  function (td, cellData, rowData, row, col) {
                                pointscale = "";

                                if(rowData.isPointScaled == 1){
                                    pointscale = "DPS";
                                }else{
                                    pointscale = "NPS";
                                }

                                var buttons = '<p class="m-0 view_que_setup" >'+pointscale+'</p>';
                                $(td)[0].innerHTML =  buttons;

                                $(td).addClass('text-center');
                                $(td).addClass('align-middle');
                                
                            }
                        },

                        {
                            'targets': 5,
                            'orderable': false, 
                            'createdCell':  function (td, cellData, rowData, row, col) {
                                var array = [rowData.isPrelimDisplay, rowData.isMidtermDisplay, rowData.isPrefiDisplay, rowData.isFinalDisplay, 1]
                                var tems = ['Prelim', 'Midterm', 'Prefinal', 'Final', 'Final Grade']
                                var button = '<div style="white-space: normal;">';
                                for (let index = 0; index < array.length; index++) {

                                    if(array[index] != 0){
                                        button += '<span class="badge badge-primary mr-2">'+tems[index]+'</span>';
                                    }
                                    
                                }
                                button += '</div>';
                                $(td)[0].innerHTML = button;
                                $(td).addClass('text-center');
                                $(td).addClass('align-middle');
                                
                            }
                        },

                        {
                            'targets': 6,
                            'orderable': false, 
                            'createdCell':  function (td, cellData, rowData, row, col) {
                              
                                $(td).addClass('text-center');
                                $(td).addClass('align-middle');
                                
                            }
                        },


                        {
                            'targets': 7,
                            'orderable': false, 
                            'createdCell':  function (td, cellData, rowData, row, col) {
                              
                                var buttons = 
                                    '<p style="white-space: normal; font-size:11px" class="badge badge-info m-0 p-1">'+rowData.f_backend+'</p>'
                                $(td)[0].innerHTML =  buttons;
                                $(td).addClass('text-center');
                                $(td).addClass('align-middle');
                                
                            }
                        },

                        {
                            'targets': 9,
                            'orderable': false, 
                            'createdCell':  function (td, cellData, rowData, row, col) {

                                var isActive = '';

                                if(rowData.isActivated == 1){
                                    isActive = 'checked'
                                }else{
                                    isActive = ''
                                }

                                var buttons = 
                                    '<button type="button" value ="'+rowData.id+'" class="editBtn btn btn-sm btn-outline-primary mr-2"><i class="fas fa-edit"></i></button>'
                                    +'<button type="button" value ="'+rowData.id+'" class="deleteBtn btn btn-sm btn-outline-danger"><i class="fas fa-trash-alt"></i></button>'
                                $(td)[0].innerHTML =  buttons;
                                $(td).addClass('text-center');
                                $(td).addClass('align-middle');
                            }
                        },

                        {
                            'targets': 8,
                            'orderable': false, 
                            'createdCell':  function (td, cellData, rowData, row, col) {

                                var isActive = '';

                                if(rowData.activestatus == 1){
                                    isActive = 'checked'
                                }else{
                                    isActive = ''
                                }

                                var buttons = '<div class="custom-control custom-switch">'
                                    +'<input type="checkbox" class="custom-control-input setActive" id="switch'+rowData.id+'" data-id="'+rowData.id+'" '+isActive+'>'
                                    +'<label class="custom-control-label" for="switch'+rowData.id+'"></label>'
                                    +'</div>';
                                $(td)[0].innerHTML =  buttons;
                                $(td).addClass('text-center');
                                $(td).addClass('align-middle');
                            }
                        },
                    ]
                        
                });

                var button = '<div class="row" style="flex-direction: column">'
                    +'<div class="col-md-12">'
                            +'<button type="button" class="btn btn-success btn-sm  mb-2 add"><i class="fas fa-plus"></i> New Setup</button>'
                        +'</div>'
                        +'<div class="col-md-12 d-flex">'
                            +'<label class="mr-3 mb-0"><span class="badge mr-2 badge-primary">DPS</span>Decimal Point Scale</label>'
	                        +'<label class="mr-3 m-0"><span class="badge mr-2 badge-primary">NGS</span>Numerical Grade Scale</label>'
	                        +'<label class="m-0"><span class="badge mr-2 badge-primary">GD</span>Grade Display</label>'
                        +'</div>'
                    +'</div>';
                var label_text = $($('#semestertable_wrapper')[0].children[0])[0].children[0];
                $(label_text)[0].innerHTML = button;

                $($($('#semestertable_wrapper')[0].children[0])[0].children[0]).css('margin-bottom', '0.5rem');
                $($($('#semestertable_wrapper')[0].children[0])[0].children[1]).css('margin-bottom', '0.5rem');
            }

            function addRow(initial1, initial2, final) {
                var element = $('#row_1').clone().attr('id', 'row_' + ++counter).appendTo('#form_values_setup');
                $($(element[0].children[0])[0].children[0]).attr('data-id', 'row_' + +counter)

                $($(element[0].children[1])[0].children[0]).val(initial1)
                $($(element[0].children[2])[0].children[0]).val(initial2)
                $($(element[0].children[3])[0].children[0]).val(final)
            }
            
            $(document).ready(function(){

                $(document).on('click', '.view_transmute', function(event){

                    var id = $(this).attr('data-id');
                    $('#addTransmutation').modal();

                    $.ajax({
                        url:"{{ route('get.transmutation') }}",
                        type:"GET",
                        data:{
                            id:id
                        },
                        success:function(data){

                            console.log(data);
                            $('#transDesc').val(data[0]['transmutdetails'][0]['trans_desc']);

                            $('#createfooter').addClass('hidden');
                            $('#viewfooter').removeClass('hidden');
                            $('#edit_transmutation').val(data[0]['transmutdetails'][0]['id']);
                            $('#delete_transmutation').val(data[0]['transmutdetails'][0]['id']);
                            
                            for (let i = 1; i < data[0]['transmutation'].length; i++) {
                                
                                addRow(data[0]['transmutation'][i]['initial1'], data[0]['transmutation'][i]['initial2'], data[0]['transmutation'][i]['final']);

                                $('initial')
                            }

                            $($($('#row_1')[0].children[1])[0].children[0]).val(data[0]['transmutation'][0]['initial1']);
                            $($($('#row_1')[0].children[2])[0].children[0]).val(data[0]['transmutation'][0]['initial2']);
                            $($($('#row_1')[0].children[3])[0].children[0]).val(data[0]['transmutation'][0]['final']);

                            
                        }
                    });
                    
                });
            });

            $(document).ready(function(){

                $(document).on('click', '.remove_row', function(event){

                    var id = $(this).attr('data-id');

                    if(id == null){

                        notify('error', 'Base row cannot be deleted.');
                    }else{

                        $("#"+id).remove();
                    }

                });
            });

            $(document).ready(function(){

                $(document).on('click', '.add_row', function(event){

                    addRow('','','');

                });
            });

            $(document).ready(function(){

                $(document).on('click', '.add_transmutation', function(event){

                    let array = $('#form_values_setup').serializeArray();
                    let transdesc = $('#transDesc').val();
                    let schoolyear = $("#sy").val();
                    let semester = $("#semester").val();
                    $.ajax({

                        url: '{{ route("create.transmute") }}' ,
                        method:'GET',
                        data: {
                            array: array,
                            transdesc: transdesc,
                            schoolyear:schoolyear,
                            semester:semester
                        },

                        success:function(response){
                            if(response[0].status == 200){

                                notify(response[0].code, response[0].message);
                                get_setup_data();


                            }else{
                                
                            }
                        },
                        error:function(error){
                            console.log(error)
                        }
                    
                    });

                });
            });

            $(document).ready(function(){
                $('#addTransmutation').on('hidden.bs.modal', function (e) {           

                    $('.addtranmutationHolder').load(window.location.href +' .addtranmutationHolder');
                    $('.inputs').css('box-shadow', '0px 0px 0px red');
                    $('#setupDescError').addClass('hidden')
                    $('#formulaError').addClass('hidden');
                })
            });

            $(document).ready(function(){
                $('#addModal').on('hidden.bs.modal', function (e) {           

                    $('.inputs').css('box-shadow', '0px 0px 0px red');
                    $('#setupDescError').addClass('hidden')
                    $('#formulaError').addClass('hidden');

                })
            });
            
            $(document).ready(function(){

                $(document).on('change', '#syidFilter', function(event){

                    get_setup_data();

                });
            });

            $(document).ready(function(){

                $(document).on('change', '#semidFilter', function(event){

                    get_setup_data();

                });
            });

            $(document).ready(function(){

                $(document).on('change', '#isPointScaled', function(event){

                    if($(this).val() == 1){
                        $('#passingRate').val(3);
                        $('#passingRate').attr('placeholder', '1-5');
                        
                    }else{
                        $('#passingRate').val(75);
                        $('#passingRate').attr('placeholder', '60-100');
                    }

                });
            });

            $(document).ready(function(){

                $(document).on('input', '#passingRate', function(event){

                    var val = $(this).val();
                    var scale = $('#isPointScaled').val();
                    var highest = 0;

                    if(scale == 1){
                        highest = 5;
                    }else{
                        highest = 100;
                    }

                    if(val > highest){
                        $(this).val(highest);
                    }

                });
            });

            $(document).ready(function(){

                $(document).on('input', '#formula', function(event){
                     if($(this).val() == ""){
                        $('.result').addClass('hidden');
                        $('#formula').attr('optionselected', 0)
                    }else{
                        if($(this).attr('optionselected') == 0){
                            var width = $(this).width()+13;
                            $('.result').removeClass('hidden');
                            $('#clearFormula').removeClass('hidden');
                            $('.result_list').width(width); 
                        }
                    }
                   
                });
            });
            
            $(document).ready(function(){

                $(document).on('click', '#clearFormula', function(event){
                    $('#formula').val("")
                    $('.result').addClass('hidden');
                    $('#formula').attr('optionselected', 0);
                    $(this).addClass('hidden');
                });
            });

            $(document).ready(function(){

                $(document).on('click', '.formula_item', function(event){
                    var value = $(this).attr('data-value');
                    $('#formula').val(value);
                    $('#formula').attr('optionselected', 1);
                    $('.result').addClass('hidden');
                });
            });

            $(document).on('click', '#prelimVar', function(event){
                   
                var value = $(this).attr('data-value');
                $('#formula').val(value);

                var width = $('#formula').width()+13;
                $('.result').removeClass('hidden');
                $('#clearFormula').removeClass('hidden');
                $('.result_list').width(width); 
            });

            $(document).on('click', '#midtermVar', function(event){
                   
                var value = $(this).attr('data-value');
                $('#formula').val(value);
                
                var width = $('#formula').width()+13;
                $('.result').removeClass('hidden');
                $('#clearFormula').removeClass('hidden');
                $('.result_list').width(width); 
            });

            $(document).on('click', '#prefiVar', function(event){
                   
                var value = $(this).attr('data-value');
                $('#formula').val(value);
                
                var width = $('#formula').width()+13;
                $('.result').removeClass('hidden');
                $('#clearFormula').removeClass('hidden');
                $('.result_list').width(width); 
            });

            $(document).on('click', '#finalVar', function(event){
                   
                var value = $(this).attr('data-value');
                $('#formula').val(value);
                
                var width = $('#formula').width()+13;
                $('.result').removeClass('hidden');
                $('#clearFormula').removeClass('hidden');
                $('.result_list').width(width); 
            });




            $(document).on('click', '#prelimEditVar', function(event){
                   
                var value = $(this).attr('data-value');
                $('#formulaEdit').val(value);

                var width = $('#formulaEdit').width()+13;
                $('.result').removeClass('hidden');
                $('#clearFormula').removeClass('hidden');
                $('.result_list').width(width); 
            });

            $(document).on('click', '#midtermEditVar', function(event){
                    
                var value = $(this).attr('data-value');
                $('#formulaEdit').val(value);
                
                var width = $('#formulaEdit').width()+13;
                $('.result').removeClass('hidden');
                $('#clearFormula').removeClass('hidden');
                $('.result_list').width(width); 
            });

            $(document).on('click', '#prefiEditVar', function(event){
                    
                var value = $(this).attr('data-value');
                $('#formulaEdit').val(value);
                
                var width = $('#formulaEdit').width()+13;
                $('.result').removeClass('hidden');
                $('#clearFormula').removeClass('hidden');
                $('.result_list').width(width); 
            });

            $(document).on('click', '#finalEditVar', function(event){
                    
                var value = $(this).attr('data-value');
                $('#formulaEdit').val(value);
                
                var width = $('#formulaEdit').width()+13;
                $('.result').removeClass('hidden');
                $('#clearFormula').removeClass('hidden');
                $('.result_list').width(width); 
            });
   


                                
  
            ///////////////ADD MODAL TOGGLE/////////////////////////
            $(document).ready(function(){

                $(document).on('click', '.add', function(event){

                    var sy = $('#syidFilter').val();
                    var sem = $('#semidFilter').val();

                    $('#sy').val(sy).change();
                    $('#semester').val(sem).change();

                    $("#addModal").modal();

                });
            });

            $(document).ready(function(){
                $('#search').on('keyup',function(){
                    var search= $(this).val();

                    event.preventDefault();

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({

                        url:"{{ route('semester-setup.search') }}",
                        type:"GET",
                        data:{
                            "_token": "{{ csrf_token() }}",
                            'search':search
                        },
                        success:function(data){

                            $('#search_div').html(data);
                        }
                    });
                //end of ajax call
                });
            });

            $(document).ready(function(){
                $(document).on('click', '.setActive', function (event) {
                    
                    var id = $(this).attr("data-id");
                    var isSetActive = "";
                    var isSetActiveStat = null;
                    var syid = $('#syidFilter').val();
                    var semid = $('#semidFilter').val();
                    
                    if($(this).prop('checked')){

                        isSetActive = "Activate";
                        isSetActiveStat = 1;

                    }else{
                        isSetActive = "Deactivate";
                        isSetActiveStat = 0;

                    }

                    Swal.fire({
                        title: 'You want '+isSetActive+' this setup?',
                        type: 'info',
                        text: `This process can be undone.`,
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: isSetActive
                    })
                    .then((result) => {

                        if (result.value) {
                            
                            $.ajax({
                                url:'{{ route("semester.setactive") }}',
                                type:"GET",
                                data:{
                                    id: id,
                                    syid:syid,
                                    semid:semid,
                                    isSetActiveStat: isSetActiveStat
                                },
                                success:function(data){

                            
                                    notify(data[0].statusCode, data[0].message);
                                    get_setup_data();

                                }
                            });
                            
                        }else{

                            get_setup_data();
                        }
                    })

                })
            });

            ////////// ADD AJAX /////////////
            $(document).ready(function(){
                $(document).on('click', '.btn-add', function(event){

                    let isPrelimDisplay = 0;
                    let isMidtermDisplay = 0;
                    let isPrefiDisplay = 0;
                    let isFinalDisplay = 0;

                    let setupDesc = $("#setupDesc").val();
                    let prelim = $("#prelim:checked").val();
                    let midterm = $("#midterm:checked").val();
                    let prefi = $("#prefi:checked").val();
                    let final = $("#final:checked").val();

                    let prelimTransmutationID = $("#prelimTransmutID").val();
                    let midtermTransmutationID = $("#midtermTransmutID").val();
                    let prefiTransmutationID = $("#prefiTransmutID").val();
                    let finalTransmutationID = $("#finalTransmutID").val();
                    let finalGradeTransmutationID = $("#finalgradeTransmutID").val();

                    let isPointScaled = $("#isPointScaled").val();
                    let schoolyear = $("#sy").val();
                    let semester = $("#semester").val();
                    let isTransmuted = $("#isTransmuted").val();
                    let passingRate = $("#passingRate").val();
                    let decimalPoint = $("#decimalPoint").val();
                    let formula = $('#formula').val();


                    let percentageSpecify = false;

                    if(schoolyear == null || schoolyear == ""){

                        $('#sy').css('box-shadow', '0px 0px 7px red');
                        return false;
                        

                    }else{

                        $('#sy').css('box-shadow', '0px 0px 0px red');
                        
                    }

                    if(semester == null || semester == ""){

                        $('#semester').css('box-shadow', '0px 0px 7px red');
                        return false;
                        
                    }else{

                        $('#semester').css('box-shadow', '0px 0px 0px red');
                        
                    }

                    if(setupDesc == null || setupDesc == ""){

                        $('#setupDescError').removeClass('hidden');
                        $('#setupDescError').text('Setup description is required.');
                        $('#setupDesc').css('box-shadow', '0px 0px 7px red');
                        $('#setupDesc').focus();
                        return false;
                        

                    }else{

                        $('#setupDescError').addClass('hidden');
                        $('#setupDesc').css('box-shadow', '0px 0px 0px red');
                        
                    }

                    if(passingRate == null || passingRate == ""){

                        $('#passingRate').css('box-shadow', '0px 0px 7px red');
                        return false;
                        
                    }else{

                        $('#passingRate').css('box-shadow', '0px 0px 0px red');
                        
                    }

                    if(decimalPoint == null || decimalPoint == ""){

                        $('#decimalPoint').css('box-shadow', '0px 0px 7px red');
                        $('#decimalPoint').focus();

                        return false;
                        
                    }else{

                        $('#decimalPoint').css('box-shadow', '0px 0px 0px red');
                        
                    }

                    if(formula == null || formula == ""){

                        $('#formula').css('box-shadow', '0px 0px 7px red');
                        $('#formula').focus();
                        $('#formulaError').removeClass('hidden');
                        $('#formulaError').text('Formula is required.');
                        return false;
                        
                    }else{

                        $('#formula').css('box-shadow', '0px 0px 0px red');
                        $('#formulaError').addClass('hidden');
                        
                    }

                    if($('#isPrelimDisplay').is(":checked")){
                        isPrelimDisplay = 1;
                    }
                    if($('#isMidtermDisplay').is(":checked")){
                        isMidtermDisplay = 1;
                    }
                    if($('#isPrefiDisplay').is(":checked")){
                        isPrefiDisplay = 1;
                    }
                    if($('#isFinalDisplay').is(":checked")){
                        isFinalDisplay = 1;
                    }

                    if(prelimTransmutationID == null){
                        prelimTransmutationID = 0;
                    }
                    if(midtermTransmutationID == null){
                        midtermTransmutationID = 0;
                    }
                    if(prefiTransmutationID == null){
                        prefiTransmutationID = 0;
                    }
                    if(finalTransmutationID == null){
                        finalTransmutationID = 0;
                    }if(finalGradeTransmutationID == null){
                        finalGradeTransmutationID = 0;
                    }

                    if(prelim == null){
                        prelim = 0;
                    }
                    if(midterm == null){
                        midterm = 0;
                    }
                    if(prefi == null){
                        prefi = 0;
                    }
                    if(final == null){
                        final = 0;
                    }

                    if($('#checkboxPercentage').is(':checked')){

                        percentageSpecify = 1;

                    }else{
                        
                        percentageSpecify = 0;
                    }
                    
                    
                    if($('#prelim-div').css('display') == 'none')
                    {
                        if(prelim == 0){
                            prelimPercentage = 0;
                        }else{
                            prelimPercentage = 100;
                        }
                    }

                    if($('#midterm-div').css('display') == 'none')
                    {
                        if(midterm == 0){
                            midtermPercentage = 0;
                        }else{
                            midtermPercentage = 100;
                        }
                    }

                    if($('#prefi-div').css('display') == 'none')
                    {
                        if(prefi == 0){

                            prefiPercentage = 0;

                        }else{
                            prefiPercentage = 100;
                        }
                    }

                    if($('#final-div').css('display') == 'none')
                    {
                        if(final == 0){
                            finalPercentage = 0;
                        }else{
                            finalPercentage = 100;
                        }
                    } 



                    $.ajax({
                        method:'GET',
                        url: '{{ route("semester-setup.add") }}' ,
                        data: 
                        {
                            setupDesc: setupDesc,
                            prelim:prelim,
                            midterm:midterm,
                            prefi:prefi,
                            final:final,

                            isPrelimDisplay:isPrelimDisplay,
                            isMidtermDisplay:isMidtermDisplay,
                            isPrefiDisplay:isPrefiDisplay,
                            isFinalDisplay:isFinalDisplay,

                            prelimTransmutationID:prelimTransmutationID,
                            midtermTransmutationID:midtermTransmutationID,
                            prefiTransmutationID:prefiTransmutationID,
                            finalTransmutationID:finalTransmutationID,
                            finalGradeTransmutationID:finalGradeTransmutationID,  

                            isPointScaled:isPointScaled,
                            schoolyear:schoolyear,
                            semester:semester,
                            isTransmuted:isTransmuted,
                            passingRate:passingRate,
                            decimalPoint:decimalPoint,
                            
                            percentageSpecify:percentageSpecify,
                            formula:formula,

                        },
        
                        success:function(response){
                            if(response.status == 200){
                                
                                notify(response.code, response.message);
                            
                                $("#form")[0].reset();
                                
                                $(".modal-body-add").load(location.href + " .modal-body-add");
                                $(".row-parent").load(location.href + " .row-parent");
                                $('#formulaError').addClass('hidden');
                                $('.inputs').css('box-shadow', '0px 0px 0px red');
                                $(function () {
                                    $('#addModal').modal('hide');
                                });
                                get_setup_data();

                            }else if(response.status == 400){

                                $('.inputs').css('box-shadow', '0px 0px 7px red');

                            }else if(response.status == 505){

                                $('#formulaError').removeClass('hidden');
                                $('#formulaError').text(response.message);
                                $('#formula').css('box-shadow', '0px 0px 7px red');

                            }else{
                                
                                $('#formulaError').addClass('hidden');
                                notify(response.code, response.message);
                                get_setup_data();
                            }
                        },
                        error:function(error){
                            console.log(error)
                        }
                    
                    });

                });
                
            });

            //////////EDIT GET DATA//////////////
            $(document).ready(function(){
                $(document).on('click', '.editBtn', function(e){

                event.preventDefault();

                id = $(this).val();
                $("#editModal").modal("show");

                $.ajax({

                    url: '{{ route("semester-setup.getEdit") }}' ,
                    method:'GET',
                    data: {
                        "id": id,
                    },

                    success:function(response){

                        if(response[0].status == 200){
                            $(document).ready(function(event){


                            var prelim = response[0].semesterSetup[0].prelim;
                            var midterm = response[0].semesterSetup[0].midterm;
                            var prefi = response[0].semesterSetup[0].prefi;
                            var final = response[0].semesterSetup[0].final;


                            var prelimTransmute = response[0].semesterSetup[0].prelimTransmuteID;
                            var midtermTransmute = response[0].semesterSetup[0].midtermTransmuteID;
                            var prefiTransmute = response[0].semesterSetup[0].prefiTransmuteID;
                            var finalTransmute = response[0].semesterSetup[0].finalTransmuteID;
                            var finalGradeTransmute = response[0].semesterSetup[0].finalGradeTransmuteID;

                            $('#syEdit').val(response[0].semesterSetup[0].sy).change();
                            $('#semesterEdit').val(response[0].semesterSetup[0].semester).change();

                            isPointScaled = $("#isPointScaledEdit").val(response[0].semesterSetup[0].isPointScaled).change();
                            $("#setupDescEdit").val(response[0].semesterSetup[0].setup_desc);
                            
                            $('#isPointScaledEdit  option[value="'+isPointScaled+'"]').prop("selected", true);

                            $('#schoolyearEdit  option[value="'+response[0].semesterSetup[0].sy+'"]').prop("selected", true);

                            $("#id-input-edit").val(response[0].semesterSetup[0].id);

                            $("#passingRateEdit").val(response[0].semesterSetup[0].passingRate);
                            $("#decimalPointEdit").val(response[0].semesterSetup[0].decimalPoint);
                            $("#formulaEdit").val(response[0].semesterSetup[0].f_backend);


                            if(response[0].semesterSetup[0].isPrelimDisplay == 1){
                                $('#isPrelimDisplayEdit').prop('checked', true);
                            }else{
                                $('#isPrelimDisplayEdit').prop('checked', false);
                            }
                            if(response[0].semesterSetup[0].isMidtermDisplay == 1){
                                $('#isMidtermDisplayEdit').prop('checked', true);
                            } else{
                                $('#isMidtermDisplayEdit').prop('checked', false);
                            }
                            if(response[0].semesterSetup[0].isPrefiDisplay == 1){
                                $('#isPrefiDisplayEdit').prop('checked', true);
                            }else{
                                $('#isPrefiDisplayEdit').prop('checked', false);
                            }
                            if(response[0].semesterSetup[0].isFinalDisplay == 1){
                                $('#isFinalDisplayEdit').prop('checked', true);
                            }else{
                                $('#isFinalDisplayEdit').prop('checked', false);
                            }
                                
                            /////PRELIM EDIT////
                            if(prelim == 1){

                                $('#prelimEdit').prop('checked', true);
                            
                                isCheckedEdit('prelimEdit','prelim-div-edit');
                                $('#checkboxPercentageEdit').prop('checked', true);
                                $('#prelim-input-edit').attr('disabled', false);
                                $("#prelim-input-edit").val(response[0].semesterSetup[0].prelimPercentage);


                                if(prelimTransmute > 0){
                                    $("#prelimTransmutIDEdit").val(prelimTransmute).change();
                                }else{
                                    $("#prelimTransmutIDEdit").val(" ").change();
                                }
                                
                            }else{

                                $('#prelimEdit').prop('checked', false);
                                // $('#prelimEdit').val(prelim);
                                isCheckedEdit('prelimEdit','prelim-div-edit');

                            }

                            /////MIDTERM EDIT////
                            if(midterm == 1){
                                
                                $('#midtermEdit').prop('checked', true);
                                // $('#midtermEdit').val(midterm);
                                
                                    isCheckedEdit('midtermEdit','midterm-div-edit');
                                    $('#checkboxPercentageEdit').prop('checked', true);
                                    $('#midterm-input-edit').attr('disabled', false);
                                    $("#midterm-input-edit").val(response[0].semesterSetup[0].midtermPercentage);

                                    if(midtermTransmute > 0){
                                        $("#midtermTransmutIDEdit").val(midtermTransmute).change();
                                    }else{
                                        $("#midtermTransmutIDEdit").val(" ").change();
                                    }

                            }else{

                                $('#midtermEdit').prop('checked', false);
                                // $('#midtermEdit').val(midterm);
                                isCheckedEdit('midtermEdit','midterm-div-edit');

                            }

                            /////PRE-FINAL EDIT////
                            if(prefi == 1){

                                    $('#prefiEdit').prop('checked', true);
                                    // $('#prefiEdit').val(prefi);
                                    
                                    isCheckedEdit('prefiEdit','prefi-div-edit');
                                    $('#checkboxPercentageEdit').prop('checked', true);
                                    $('#prefi-input-edit').attr('disabled', false);
                                    $("#prefi-input-edit").val(response[0].semesterSetup[0].prefiPercentage);


                                    if(prefiTransmute > 0){
                                        $("#prefiTransmutIDEdit").val(prefiTransmute).change();
                                    }else{
                                        $("#prefiTransmutIDEdit").val(" ").change();
                                    }

                
                            }else{

                                $('#prefiEdit').prop('checked', false);
                                // $('#prefiEdit').val(prefi);
                                isCheckedEdit('prefiEdit','prefi-div-edit');

                            }

                            /////FINAL EDIT////
                            if(final == 1){

                                    $('#finalEdit').prop('checked', true);
                                    // $('#finalEdit').val(final);
                                
                                    isCheckedEdit('finalEdit','final-div-edit');
                                    $('#checkboxPercentageEdit').prop('checked', true);
                                    $('#final-input-edit').attr('disabled', false);
                                    $("#final-input-edit").val(response[0].semesterSetup[0].finalPercentage);


                                    if(finalTransmute > 0){
                                        $("#finalTransmutIDEdit").val(finalTransmute).change();
                                    }else{
                                        $("#finalTransmutIDEdit").val(" ").change();
                                    }
                            
                            }else{

                                $('#finalEdit').prop('checked', false);
                                // $('#finalEdit').val(final);
                                isCheckedEdit('finalEdit','final-div-edit');

                            }

                            /////FINAL GRADE EDIT////
                            document.getElementById('finalgrade-div-edit').style.display = "block";
                            if(finalGradeTransmute > 0){
                                $("#finalgradeTransmutIDEdit").val(finalGradeTransmute).change();
                            }else{
                                $("#finalgradeTransmutIDEdit").val(" ").change();
                            }

                        });
                        
                        }
                        },
                        error:function(error){
                            console.log(error)
                        }
                
                    });

                });
            });

            /////////EDIT//////////////
            $(document).ready(function(){
                $(document).on('click', '.btn-save', function(event){

                    event.preventDefault();

                    let id = $("#id-input-edit").val();

                    let isPrelimDisplay = 0;
                    let isMidtermDisplay = 0;
                    let isPrefiDisplay = 0;
                    let isFinalDisplay = 0;

                    let setupDesc = $("#setupDescEdit").val();
                    let prelim = $("#prelimEdit:checked").val();
                    let midterm = $("#midtermEdit:checked").val();
                    let prefi = $("#prefiEdit:checked").val();
                    let final = $("#finalEdit:checked").val();

                    let prelimTransmutationID = $("#prelimTransmutIDEdit").val();
                    let midtermTransmutationID = $("#midtermTransmutIDEdit").val();
                    let prefiTransmutationID = $("#prefiTransmutIDEdit").val();
                    let finalTransmutationID = $("#finalTransmutIDEdit").val();
                    let finalGradeTransmutationID = $("#finalgradeTransmutIDEdit").val();

                    let isPointScaled = $("#isPointScaledEdit").val();
                    let schoolyear = $("#syEdit").val();
                    let semester = $("#semesterEdit").val();
                    let isTransmuted = $("#isTransmutedEdit").val();
                    let passingRate = $("#passingRateEdit").val();
                    let decimalPoint = $("#decimalPointEdit").val();
                    let formula = $('#formulaEdit').val();


                    if(setupDesc == null || setupDesc == ""){

                        $('#setupDescEditError').removeClass('hidden');
                        $('#setupDescEditError').text('Setup description is required.');
                        $('#setupDescEdit').css('box-shadow', '0px 0px 7px red');
                        $('#setupDescEdit').focus();
                        return false;

                    }else{

                        $('#setupDescEditError').addClass('hidden');
                        $('#setupDescEdit').css('box-shadow', '0px 0px 0px red');
                        true;
                    }

                    if(passingRate == null || passingRate == ""){

                        $('#passingRateEdit').css('box-shadow', '0px 0px 7px red');
                        return false;
                        
                    }else{

                        $('#passingRateEdit').css('box-shadow', '0px 0px 0px red');
                        
                    }

                    if(decimalPoint == null || decimalPoint == ""){

                        $('#decimalPointEdit').css('box-shadow', '0px 0px 7px red');
                        $('#decimalPointEdit').focus();
                        return false;
                        
                    }else{

                        $('#decimalPointEdit').css('box-shadow', '0px 0px 0px red');
                        
                    }

                    if(formula == null || formula == ""){

                        $('#formulaEdit').css('box-shadow', '0px 0px 7px red');
                        $('#formulaErrorEdit').removeClass('hidden');
                        $('#formulaErrorEdit').text('Formula is required.');
                        $('#formulaErrorEdit').focus();
                        return false;
                        
                    }else{

                        $('#formulaEdit').css('box-shadow', '0px 0px 0px red');
                        $('#formulaErrorEdit').addClass('hidden');
                        
                    }

                    if(prelimTransmutationID == null){
                        prelimTransmutationID = 0;
                    }
                    if(midtermTransmutationID == null){
                        midtermTransmutationID = 0;
                    }
                    if(prefiTransmutationID == null){
                        prefiTransmutationID = 0;
                    }
                    if(finalTransmutationID == null){
                        finalTransmutationID = 0;
                    }if(finalGradeTransmutationID == null){
                        finalGradeTransmutationID = 0;
                    }

                    if(prelim == null){
                        prelim = 0;
                    }
                    if(midterm == null){
                        midterm = 0;
                    }
                    if(prefi == null){
                        prefi = 0;
                    }
                    if(final == null){
                        final = 0;
                    }

                    let percentageSpecify = false;

                    if($('#isPrelimDisplayEdit').is(":checked")){
                        isPrelimDisplay = 1;
                    }
                    if($('#isMidtermDisplayEdit').is(":checked")){
                        isMidtermDisplay = 1;
                    }
                    if($('#isPrefiDisplayEdit').is(":checked")){
                        isPrefiDisplay = 1;
                    }
                    if($('#isFinalDisplayEdit').is(":checked")){
                        isFinalDisplay = 1;
                    }


                    if($('#checkboxPercentageEdit').is(':checked')){

                        percentageSpecify = 1;

                    }else{
                        
                        percentageSpecify = 0;
                    }


                    if($('#prelim-div-edit').css('display') == 'none')
                    {
                        if(prelim == 0){
                            prelimPercentage = 0;
                        }else{
                            prelimPercentage = 100;
                        }
                    }

                    if($('#midterm-div-edit').css('display') == 'none')
                    {
                        if(midterm == 0){
                            midtermPercentage = 0;
                        }else{
                            midtermPercentage = 100;
                        }
                    }

                    if($('#prefi-div-edit').css('display') == 'none')
                    {
                        if(prefi == 0){

                            prefiPercentage = 0;

                        }else{
                            prefiPercentage = 100;
                        }
                    }

                    if($('#final-div-edit').css('display') == 'none')
                    {
                        if(final == 0){
                            finalPercentage = 0;
                        }else{
                            finalPercentage = 100;
                        }
                    } 
                    
                    

                    $.ajax({
                        url: '{{ route("semester-setup.edit") }}' ,
                        method:'GET',
                        data: {
                            id:id,
                            setupDesc: setupDesc,
                            prelim:prelim,
                            midterm:midterm,
                            prefi:prefi,
                            final:final,

                            isPrelimDisplay:isPrelimDisplay,
                            isMidtermDisplay:isMidtermDisplay,
                            isPrefiDisplay:isPrefiDisplay,
                            isFinalDisplay:isFinalDisplay,

                            prelimTransmutationID:prelimTransmutationID,
                            midtermTransmutationID:midtermTransmutationID,
                            prefiTransmutationID:prefiTransmutationID,
                            finalTransmutationID:finalTransmutationID,
                            finalGradeTransmutationID:finalGradeTransmutationID,  

                            isPointScaled:isPointScaled,
                            schoolyear:schoolyear,
                            semester:semester,
                            isTransmuted:isTransmuted,
                            passingRate:passingRate,
                            decimalPoint:decimalPoint,
                            
                            percentageSpecify:percentageSpecify,
                            formula:formula,
                            
                        },
                        success:function(response){
                            if(response.status == 200){ 
                                
                                notify(response.code, response.message);
                                $(function () {
                                    $('#editModal').modal('toggle');
                                });
                                get_setup_data();

                                
                            }else if(response.status == 400){ 

                                $('.inputs').css('box-shadow', '0px 0px 7px red');

                            }else if(response.status == 505){

                                $('#formulaErrorEdit').removeClass('hidden');
                                $('#formulaErrorEdit').text(response.message);
                                $('#formulaEdit').css('box-shadow', '0px 0px 7px red');

                            }else{

                                notify(response.code, response.message);
                            }
                        },
                        error:function(error){
                            console.log(error)
                        }
                    
                    });
                    
                });
                
            });
            
            //////////DELETE///////////
            $(document).ready(function(){
                $(document).on('click', '.deleteBtn', function(e){
                
                    id = $(this).val();


                    Swal.fire({
                        title: 'Delete this Setup?',
                        type: 'info',
                        text: `This process can't be undone.`,
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes'
                    })
                    .then((result) => {

                        if (result.value) {
                            
                            $.ajax({

                                url: '{{ route("semester-setup.destroy") }}' ,
                                method:'GET',
                                data: {
                                    "id": id,
                                },

                                success:function(response){
                                    if(response[0].status == 200){

                                        notify(response[0].code, response[0].message);
                                        get_setup_data();


                                    }else{
                                        
                                    }
                                },
                                error:function(error){
                                    console.log(error)
                                }
                            
                            });

                            
                        }else{

                            get_setup_data();
                        }
                    });


                });

            });

            /////////////SWEET ALERT///////////////
            function notify(code, message){
                Swal.fire({
                    type: code,
                    title: message,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                });

            }

        });
        

        function isCheckedEdit(sem, sem_select) {


            var term = document.getElementById(sem);

            var select = document.getElementById(sem_select);
            var checkboxEdit = document.getElementById('checkboxPercentageEdit');

            if(checkboxEdit.checked == true){

                if (term.checked == true){
                    select.style.display = "block";

                } else {
                    select.style.display = "none";
                }

            }else{

                select.style.display = "none";
            }

            if (term.checked == true){
                $('#'+sem+'variable').removeClass('hidden');
            } else {
                $('#'+sem+'variable').addClass('hidden');
            }

        }

        function isChecked(sem, sem_select) {

            var term = document.getElementById(sem);

            var select = document.getElementById(sem_select);
            var checkbox = document.getElementById('checkboxPercentage');

            if(checkbox.checked == true){

                if (term.checked == true){
                    select.style.display = "block";
                } else {
                    select.style.display = "none";
                }

            }else{

                select.style.display = "none";
            }

            if (term.checked == true){
                $('#'+sem+'variable').removeClass('hidden');
            } else {
                $('#'+sem+'variable').addClass('hidden');
            }
        
        }
        
        function isPerecentageSpecified() {

            var checkbox = document.getElementById('checkboxPercentage');

            if (checkbox.checked == false){

                document.getElementById('prelim-div').style.display = "none";
                document.getElementById('midterm-div').style.display = "none";
                document.getElementById('prefi-div').style.display = "none";
                document.getElementById('final-div').style.display = "none";
                document.getElementById('finalgrade-div').style.display = "none";

            }else{

                isChecked('prelim', 'prelim-div');
                isChecked('midterm', 'midterm-div');
                isChecked('prefi', 'prefi-div');
                isChecked('final', 'final-div');
                document.getElementById('finalgrade-div').style.display = "block";
                

            }
        }

        function isPerecentageSpecifiedEdit() {

            var checkbox = document.getElementById('checkboxPercentageEdit');

            if (checkbox.checked == false){

                document.getElementById('prelim-div-edit').style.display = "none";
                document.getElementById('midterm-div-edit').style.display = "none";
                document.getElementById('prefi-div-edit').style.display = "none";
                document.getElementById('final-div-edit').style.display = "none";
                document.getElementById('finalgrade-div-edit').style.display = "none";

            }else{

                isCheckedEdit('prelimEdit', 'prelim-div-edit');
                isCheckedEdit('midtermEdit', 'midterm-div-edit');
                isCheckedEdit('prefiEdit', 'prefi-div-edit');
                isCheckedEdit('finalEdit', 'final-div-edit');
                document.getElementById('finalgrade-div-edit').style.display = "block";

            }
        }
            
    </script>

@endsection
