

@extends('principalsportal.layouts.app2')


@section('pagespecificscripts')
    <style>
        @media(min-width: 769px)
        {
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
<section class="content-header">
    <div class="container-fluid">
    <div class="row">
        <div class="col-sm-6">
        </div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item active">School Form 6</li>
        </ol>
        </div>
    </div>
    </div>
</section>
<section >

        <div class="main-card mb-3 card principalform">
            <div class="card-header">
                <a href="/sf6pdf"  target="_blank" class="btn btn-danger btn-sm">Convert into PDF</a>
            </div>
            <div class="card-body ">
                <div class="table-responsive" style="height: 400px;" id="formtable">
                    @include('search.principal.sf6')
                </div>
            </div>
        </div>
    
</section>
@endsection
