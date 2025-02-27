
@extends('registrar.layouts.app')
@section('content')
    <link rel="stylesheet" href="{{asset('assets/adminlte/plugins/jquery-year-picker/css/yearpicker.css')}}" />
    <style>
        td{
            border-bottom: hidden;
        }
        input[type=text], .input-group-text, .select{
            background-color: white !important;
            border: hidden;
            border-bottom: 2px solid #ddd;
            font-size: 12px !important;
        }
        .input-group-text{
            border-bottom: hidden;
        }
        .fontSize{
            font-size: 12px;
        }
        .container{
            overflow-x: scroll !important;
        }
        table{
            width: 100%;
        }
        .inputClass{
            width: 100%;
        }
        .tdInputClass{
            padding: 0px !important;
        }
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
        }
    </style>
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-10">
                <h4>Learner's Permanent Academic Record</h4>
                <em>(Formerly Form 137)</em>
            </div>
        </div>
    </div>
    <div class="row">
        &nbsp;
        <div class="col-md-12">
            <form action="/juniorhigh/dashboard" method="GET">
                @csrf
                <input type="hidden" name="studid" value="{{$studid}}"/>
                <button type="submit" class="btn btn-sm btn-default"><i class="fa fa-arrow-left"></i> Back</button> 
            </form>
        </div>
        <div class="col-md-12">
            <!-- we are adding the .class so bootstrap.js collapse plugin detects it -->
            <form action="/junior/editform10/savechanges" method="GET">
                @csrf
                <input type="hidden" name="student_id" value="{{$studid}}"/>
                <input type="hidden" name="recordid" value="{{$recordid}}"/>
                <div class="card card-primary">
                    <div class="card-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-2">
                                    <label>
                                        <strong>School ID</strong>
                                    </label>
                                    <br>
                                    <input type="hidden" class="form-control form-control-sm" name="recordschoolid" value="{{$schoolinfo[0]->recordschoolid}}"/>
                                    <input type="text" class="form-control form-control-sm" name="schoolid" value="{{$schoolinfo[0]->schoolid}}"/>
                                </div>
                                <div class="col-md-5">
                                    <label>
                                        <strong>School Name</strong>
                                    </label>
                                    <br>
                                    <input type="text" class="form-control form-control-sm" name="schoolname" value="{{$schoolinfo[0]->schoolname}}"/>
                                </div>
                                <div class="col-md-5">
                                    <label>
                                        <strong>School Address</strong>
                                    </label>
                                    <br>
                                    <input type="text" class="form-control form-control-sm" name="schooladdress" value="{{$schoolinfo[0]->schooladdress}}"/>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-4">
                                    <label>
                                        <strong>Section</strong>
                                    </label>
                                    <br>
                                    <input type="text" class="form-control form-control-sm" name="section" value="{{$schoolinfo[0]->sectionname}}"/>
                                </div>
                                <div class="col-md-4">
                                    <label>
                                        <strong>Adviser</strong>
                                    </label>
                                    <br>
                                    <input type="text" class="form-control form-control-sm" name="adviser" value="{{$schoolinfo[0]->adviser}}"/>
                                </div>
                            </div>
                            <br>
                            <table class="table table-bordered fontSize">
                                <thead>
                                    <tr>
                                        <th width="30%">SUBJECT</th>
                                        <th>1</th>
                                        <th>2</th>
                                        <th>3</th>
                                        <th>4</th>
                                        <th>FINAL RATING</th>
                                        <th width="15%">ACTION TAKEN</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $old = 0;
                                    @endphp
                                    @foreach($grades as $grade)
                                        <tr>
                                            <td class="tdInputClass">
                                                <input type="hidden" class="form-control" name="old{{$old}}[]" value="{{$grade->id}}"/>
                                                <input type="text" class="form-control" name="old{{$old}}[]" value="{{$grade->subj_desc}}"/>
                                            </td>
                                            <td class="tdInputClass">
                                                <input type="number" class="form-control" name="old{{$old}}[]" value="{{$grade->quarter1}}"/>
                                            </td>
                                            <td class="tdInputClass">
                                                <input type="number" class="form-control" name="old{{$old}}[]" value="{{$grade->quarter2}}"/>
                                            </td>
                                            <td class="tdInputClass">
                                                <input type="number" class="form-control" name="old{{$old}}[]" value="{{$grade->quarter3}}"/>
                                            </td>
                                            <td class="tdInputClass">
                                                <input type="number" class="form-control" name="old{{$old}}[]" value="{{$grade->quarter4}}"/>
                                            </td>
                                            <td class="tdInputClass">
                                                <input type="number" class="form-control" name="old{{$old}}[]" value="{{$grade->finalrating}}"/>
                                            </td>
                                            <td class="tdInputClass">
                                                <input type="text" class="form-control" name="old{{$old}}[]" value="{{$grade->action}}"/>
                                            </td>
                                            <td></td>
                                        </tr>
                                        @php
                                            $old += 1;
                                        @endphp
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="7">
                                        </td>
                                        <td id="addrow" class="bg-success">
                                            <center>
                                                <i class="fa fa-plus"></i>
                                            </center>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="30%" class="tdInputClass">
                                            @if(count($genave) == 0)
                                            <input type="hidden" class="form-control" name="genAve[]" value=""/>
                                            @else
                                            <input type="hidden" class="form-control" name="genAve[]" value="{{$genave[0]->id}}"/>
                                            @endif
                                            <input type="text" class="form-control" value="General Average" readonly/>
                                        </td>
                                        <td colspan="4">
                                        </td>
                                        <td class="tdInputClass">
                                            @if(count($genave) == 0)
                                                <input type="number" class="form-control" name="genAve[]" value=""/>
                                            @else
                                                <input type="number" class="form-control" name="genAve[]" value="{{$genave[0]->genave}}"/>
                                            @endif
                                        </td>
                                        <td class="tdInputClass">
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        &nbsp;
                        <br>
                        <button type="submit" class="btn btn-sm btn-warning float-right">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- jQuery -->
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('plugins/jquery-year-picker/js/yearpicker.js')}}"></script>
    <script>
        $( document ).ready(function() {
            var newSubj = 0;
            $('#addrow').on('click', function(){
                var closestTable = $(this).closest("table");
                closestTable.append(
                    '<tr>'+
                        '<td class="tdInputClass"><input type="text" class="form-control" name="new'+newSubj+'[]" required/></td>'+
                        '<td class="tdInputClass"><input type="number" class="form-control" name="new'+newSubj+'[]" required/></td>'+
                        '<td class="tdInputClass"><input type="number" class="form-control" name="new'+newSubj+'[]" required/></td>'+
                        '<td class="tdInputClass"><input type="number" class="form-control" name="new'+newSubj+'[]" required/></td>'+
                        '<td class="tdInputClass"><input type="number" class="form-control" name="new'+newSubj+'[]" required/></td>'+
                        '<td class="tdInputClass"><input type="number" class="form-control" name="new'+newSubj+'[]" required/></td>'+
                        '<td class="tdInputClass"><input type="text" class="form-control" name="new'+newSubj+'[]" required/></td>'+
                        '<td class="removebutton"><center><i class="fa fa-trash text-gray"></i></center></td>'+
                    '</tr>'
                );
                newSubj+=1;
            });
            $(document).on('click', '.removebutton', function () {
                $(this).closest('tr').remove();
                return false;
            });
            $(".yearpicker").yearpicker({
                    endYear: 2030
                });
            $('#backRecord').on('click', function(){
                $('form[name=backRecord]').submit();
            })
            $('#backStudents').on('click', function(){
                $('form[name=backStudents]').submit();
            })
        });
    </script>
@endsection
