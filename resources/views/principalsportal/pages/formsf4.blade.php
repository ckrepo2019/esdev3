
@extends('principalsportal.layouts.app2')

@section('pagespecificscripts')
    
    <style>
        @media(min-width: 769px)
        {
            /* .table-responsive {
                width:950px !important;
            } */
            table {
                font-size: 13px !important;
                border: 1px solid black;
                table-layout: fixed;
                width: 1250px !important;
            }
            table .t-lg{
                width: 250px !important;
            }
            table .t-md{
                width: 150px !important;
            }
            table .t-sm{
                width: 100px !important;
            }

            table tr td{
                vertical-align: middle !important;
                font-size: 9px !important;
                border:solid black 1px !important;
                padding: 4px !important;

            }
            table th{
                font-size: 9px !important;
                border:solid black 1px !important;
                padding:4px !important;

            }
        }

</style>
  
@endsection

@section('content')
@php
    $selectedMonth = \Carbon\Carbon::now()->isoFormat('M');
@endphp
<section class="content-header">
    <div class="container-fluid">
    <div class="row">
        <div class="col-sm-6">
        </div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item active">School Form 4</li>
        </ol>
        </div>
    </div>
    </div>
</section>
<section >
    <div class="main-card card principalform">
        <div class="card-header">
            <div class="card-tools">
                <select class="form-control form-control-sm" id="selectmonth">
                    @for ($x = 1 ; $x<=12;$x++)
                        <option {{\Carbon\Carbon::now()->isoFormat('M') == $x ? 'selected':''}} value="{{$x}}">{{\Carbon\Carbon::create(0,$x)->isoFormat('MMMM')}}</option>
                    @endfor
                </select>
            </div>
            <a href="/dynamic_pdf/{{$selectedMonth}}" target="_blank" class="btn btn-danger btn-sm" id="sf4pdf">Convert into PDF</a>
        </div>
        <div class="card-body ">
            <div class="table-responsive" style="height: 400px;" id="formtable">
                @include('search.principal.sf4')
            </div>
        </div>
    </div>
</section>
@endsection

@section('footerjavascript')

<script>
    $(document).ready(function(){

       

        $(document).on('change','#selectmonth',function() {

            $("#sf4pdf").attr("href", '/dynamic_pdf/'+$(this).val());

            $.ajax({
            type:'GET',
            url:'/sf4changemonth',
            data:{
                data:$(this).val(),
            },
            success:function(data) {
                $('#formtable').empty();
               
                $('#formtable').append(data);
            }
            })
        });
    });
</script>

@endsection
