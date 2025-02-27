@extends('ctportal.layouts.app2')

@section('pagespecificscripts')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/rowgroup/1.1.0/css/rowGroup.dataTables.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .table-responsive {
            overflow: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        .table th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        .table thead th {
            background-color: #fff;
            z-index: 100;
        }

        .table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .table td b {
            font-weight: bold;
        }

        .align-middle {
            vertical-align: middle;
        }

        .text-center {
            text-align: center;
        }

        .comp_1 {
            background-color: #e7e7e7;
        }

        .comp_2 {
            background-color: #f9f9f9;
        }

        .comp_3 {
            background-color: #d1d1d1;
        }

        .comp_4 {
            background-color: #b4c6e7;
        }

        .comp_5 {
            background-color: #f8cbad;
        }

        .comp_6 {
            background-color: #aad08e;
        }



        .comp_7 {
            background-color: #e7e7e7;
        }

        .table th[colspan] {
            text-align: center;
        }

        .table th[rowspan] {
            vertical-align: middle;
        }

        .wide-header {
            width: 350px;
            /* Adjust width as needed */
        }

        .table-fixed {
            table-layout: auto;
            width: 125%;
            /* table-layout: auto; */
            /* width: 100%; */
            /* Ensure table takes full width */
        }

        /* //////////////////////////////// */
        .tableFixHead thead th {
            position: sticky;
            top: 0;
            background-color: #fff;
            outline: 2px solid #dee2e6;
            outline-offset: -1px;

        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-top: -9px;
        }

        .grade_td {
            cursor: pointer;
            vertical-align: middle !important;
        }

        #dropdown-item {
            background-color: green;
            color: white;
            cursor: pointer;
            border-radius: 5%;
            margin-bottom: 2px;
            width: 120px;
            font-size: 13px;
            padding: 6px;
            text-align: center;
        }

        .sort-icon {
            font-size: 14px;
            color: black;
            /* Example color */
        }

        .sort-icon:hover {
            color: blue;
            /* Example hover color */
        }

        #grade_submissions {
            cursor: pointer;
            background-color: rgb(60, 114, 181);
            color: white;
        }

        #grade_submissions:hover {
            background-color: rgba(29, 62, 103, 0.859);
        }

        .btn-actions-pane-lefy {
            padding: 15px;

        }

        .btn-group-sm .btn {
            margin: 5px;
            /* border-radius: 5px; */
        }

        .btn-group-sm .btn p {
            margin: 0;
            font-size: 11px;
            background-color: #218838;
            /* color: #6c757d; */
        }

        .btn-group-sm .btn-success {
            background-color: #9d7fd5;
            border-color: #8d8f8d;
        }

        .btn-group-sm .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }


        /* .disabled {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                opacity: 0.5;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                pointer-events: none;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                /* Prevents mouse interactions */
        /* } */


        /* .submit_prelim {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        border-radius: 50%;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        font-size: 4px;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        float: right;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    }

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    .fa-telegram-plane {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        font-size: 12px;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        float: right;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        color: white;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        cursor: pointer;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        background-color: green;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        border-radius: 50%;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        width: 18px;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        height: 15px;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        padding: 2px;

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    } */




        /* .dropdown-menu {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        min-width: 200px;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          background-color: green;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        color: white;

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    }

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    .dropdown-item {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        cursor: pointer;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        width: 200px;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    } */

        /* .dropdown-item:hover {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         color: black;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            } */
    </style>
@endsection

@section('content')
    @php
        $sy = DB::table('sy')->orderBy('sydesc', 'desc')->get();
        $semester = DB::table('semester')->get();
        $schoolinfo = DB::table('schoolinfo')->first()->abbreviation;
        $dean = DB::table('college_colleges')
            ->join('teacher', function ($join) {
                $join->on('college_colleges.dean', '=', 'teacher.id');
                $join->where('teacher.deleted', 0);
            })
            ->where('college_colleges.deleted', 0)
            ->select('teacher.id', DB::raw("CONCAT(teacher.lastname,', ',teacher.firstname) as text"))
            ->distinct()
            ->get();

        $gradesetup = DB::table('semester_setup')->where('deleted', 0)->first();

    @endphp



    <div class="modal fade" id="modal_1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0 bg-dark">
                    <h4 class="modal-title">
                        <span class="mt-1" id="sectionNameHeader"></span>
                    </h4>
                    <div class="d-flex align-items-center ml-auto">
                        <a class="btn btn-primary btn-sm ml-2" id="view_pdf" data-id = "" href="#">
                            <i class="far fa-file-pdf mr-1"></i> PRINT PDF
                        </a>
                        <button type="button" class="close pb-2" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                </div>
                <div class="container-fluid headerText p-3">
                    <div class="row py-3">
                        <!-- Teacher -->
                        <div class="col-md-3">
                            <div class="d-flex flex-column text-center">
                                <span class="font-weight-bold h6"><i class="fas fa-chalkboard-teacher"></i> Teacher</span>
                                <span id="teacherName">{{ auth()->user()->name }}</span>
                                <a class="text-primary" id="teacherID">{{ auth()->user()->email }}</a>
                            </div>
                        </div>
                        <!-- Subject -->
                        <div class="col-md-3">
                            <div class="d-flex flex-column text-center">
                                <span class="font-weight-bold h6"><i class="fas fa-book"></i> Subject</span>
                                <span id="subjectDescs"></span>
                                <a class="text-primary" id="subjectCodes"></a>
                            </div>
                        </div>
                        <!-- Level -->
                        <div class="col-md-3">
                            <div class="d-flex flex-column text-center">
                                <span class="font-weight-bold h6"><i class="fas fa-graduation-cap"></i> Level</span>
                                <span id="collegeLevels"></span>
                            </div>
                        </div>
                        <!-- Section -->
                        <div class="col-md-3">
                            <div class="d-flex flex-column text-center">
                                <span class="font-weight-bold h6"><i class="fas fa-building"></i> Section</span>
                                <span id="sections"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-body" style="font-size:.8rem">
                    <table class="table table-sm table-striped" width="100%">
                        <thead>
                            <tr id="datatable_2_row">
                                <th width="10%">Student ID No.</th>
                                <th width="15%">Student Name</th>
                                <th width="10%">Academic Level</th>
                                <th width="12%">Course</th>
                                <th width="10%">Contact No.</th>
                                {{-- <th width="10%">Email Address</th> --}}
                                {{-- <th width="auto">Address</th> --}}
                            </tr>
                        </thead>
                        <tbody id="datatable_2">
                            <!-- Rows will be dynamically inserted here -->
                        </tbody>
                    </table>

                </div>


            </div>
        </div>
    </div>


    <div class="modal fade" id="modal_2" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    {{-- <h4 class="modal-title">Grades</h4> --}}
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="container-fluid headerText p-3">
                        <div class="row py-3">
                            <!-- Teacher -->
                            <div class="col-md-3">
                                <div class="d-flex flex-column text-center">
                                    <span class="font-weight-bold h6"><i class="fas fa-chalkboard-teacher"></i>
                                        Teacher</span>
                                    <span id="teacherName">{{ auth()->user()->name }}</span>
                                    <a class="text-primary" id="teacherID">{{ auth()->user()->email }}</a>
                                </div>
                            </div>
                            <!-- Subject -->
                            <div class="col-md-3">
                                <div class="d-flex flex-column text-center">
                                    <span class="font-weight-bold h6"><i class="fas fa-book"></i> Subject</span>
                                    <span id="subjectDesc"></span>
                                    <a class="text-primary" id="subjectCode"></a>
                                </div>
                            </div>
                            <!-- Level -->
                            <div class="col-md-3">
                                <div class="d-flex flex-column text-center">
                                    <span class="font-weight-bold h6"><i class="fas fa-graduation-cap"></i> Level</span>
                                    <span id="collegeLevel"></span>
                                </div>
                            </div>
                            <!-- Section -->
                            <div class="col-md-3">
                                <div class="d-flex flex-column text-center">
                                    <span class="font-weight-bold h6"><i class="fas fa-building"></i> Section</span>
                                    <span id="section"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="row" id="grades_setup_holder">
                        <div class="col-md-3">
                            <label for="" class="mb-0">Term</label>
                            <div id="setup_term_holder"></div>
                        </div>
                        <div class="col-md-4">
                            <label for="" class="mb-0">Final Grade Computation</label>
                            <div id="setup_fgc_holder"></div>
                        </div>
                        <div class="col-md-3">
                            <label for="" class="mb-0">Grading Scale</label>
                            <div id="setup_gs_holder"></div>
                        </div>
                        <div class="col-md-2">
                            <label for="" class="mb-0">Decimal Places</label>
                            <div id="setup_dp_holder"></div>
                        </div>
                    </div> --}}
                    {{-- <div class="row mt-2" id="input_period_holder">
                        </div> --}}
                    <div class="row">
                        <div class="col-md-8">
                            <p class="mb-2"><i>Note: Press <b class="text-danger">I</b> mark to student as Incomplete.
                                    Press <b class="text-danger">D</b> to mark student as Dropped.</i></p>
                        </div>
                        <div class="col-md-4 text-right">
                            <button class="btn btn-primary btn-sm" id="print_grades_to_modal"
                                style="font-size:.7rem !important">Print</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-sm table-striped mb-0" style="font-size:.9rem">
                                @if (strtoupper($schoolinfo) == 'SPCT' || strtoupper($schoolinfo) == 'GBBC')
                                    <tr>
                                        <th id="subject" width="70%"></th>
                                        <th id="section" width="30%" hidden></th>
                                    </tr>
                                @else
                                    <tr>
                                        <th id="section" width="30%"></th>
                                        <th id="subject" width="70%"></th>
                                    </tr>
                                @endif
                            </table>


                        </div>


                        <div class="col-md-12 table-responsive tableFixHead" style="height: 420px;">
                            <table class="table table-sm table-striped table-bordered mb-0 table-head-fixed table-hover"
                                style="font-size:.8rem" width="100%">
                                <thead>
                                    <tr id="deadline_holder">

                                    </tr>
                                    <tr>
                                        <th width="3%" class="text-center">#</th>
                                        <th width="30%">Student <input type="checkbox"
                                                style="margin-top:1px; float:right;" id="studentFilter">

                                            <i class="sort-iconn sort-asc" data-all-gender="all-gender" data-sort="name"
                                                style="cursor: pointer;float:right;color:black;margin-right:10px;"><svg
                                                    xmlns="http://www.w3.org/2000/svg" width="13" height="13"
                                                    fill="currentColor" class="bi bi-arrow-down-up" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd"
                                                        d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5" />
                                                </svg></i>
                                            <i class="sort-iconn sort-desc" data-all-gender="all-gender" data-sort="name"
                                                style="cursor: pointer; float:right; display: none; color:black;margin-right:10px;"><svg
                                                    xmlns="http://www.w3.org/2000/svg" width="13" height="13"
                                                    fill="currentColor" class="bi bi-arrow-down-up" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd"
                                                        d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5" />
                                                </svg></i>

                                        </th>
                                        {{-- <th width="17%">Course</th> --}}
                                        <th width="8%" class="text-center  grade_submissions_prelim"
                                            id="grade_submissions" style="cursor:pointer;" data-value="1"
                                            data-term="1">Submit
                                        </th>
                                        <th width="8%" class="text-center  grade_submissions_midterm"
                                            id="grade_submissions" data-value="2" data-term="2">Submit
                                        </th>
                                        <th width="8%" class="text-center  grade_submissions_semifinal"
                                            id="grade_submissions" data-value="3" data-term="3">Submit
                                        </th>
                                        <th width="8%" class="text-center  grade_submissions_final"
                                            id="grade_submissions" data-value="4" data-term="4">Submit
                                        </th>
                                        {{-- <th width="8%" class="text-center term_holder" data-term="1">PRELIM
                                        </th>
                                        <th width="8%" class="text-center term_holder" data-term="2">MIDTERM</th>
                                        <th width="8%" class="text-center term_holder" data-term="3">SEMI-FINAL</th>
                                        <th width="8%" class="text-center term_holder" data-term="4">FINAL</th> --}}
                                        <th width="14%" class="text-center term_holder" data-term="5" rowspan="2"
                                            style="padding: 12px;">General Average
                                        </th>
                                        <th width="10%" class="text-center term_holder" data-term="6" rowspan="2"
                                            style="padding: 12px;">Remarks</th>
                                    </tr>
                                    <tr>
                                        <th width="3%" class="text-center"></th>
                                        <th width="30%"></th>
                                        <th width="8%" class="text-center term_holder" data-term="1">PRELIM
                                        </th>
                                        <th width="8%" class="text-center term_holder" data-term="2">MIDTERM</th>
                                        <th width="8%" class="text-center term_holder" data-term="3">SEMI-FINAL</th>
                                        <th width="8%" class="text-center term_holder" data-term="4">FINAL</th>

                                    </tr>
                                </thead>
                                <tbody id="student_list_grades">

                                </tbody>
                            </table>
                            {{-- <div class="d-flex">
                                <div style="margin-left: 15%;">
                                    <br>
                                    <span class="font-weight-bold" style="font-size: 15px;"><i class="fas fa-book"></i>
                                        Number
                                        of Students
                                        Enrolled</span>
                                    <div class="d-flex">
                                        <p style="font-size:13px;">Male:</p>
                                        <span style="font-size:13px;margin-left:2px;" id="maleCount"></span>
                                    </div>
                                    <div class="d-flex" style="margin-top:-5%;">
                                        <p style="font-size:13px;">Female:</p>
                                        <span style="font-size:13px;margin-left:2px;" id="femaleCount"></span>
                                    </div>
                                    <div class="d-flex" style="margin-top:-5%;">
                                        <p style="font-size:13px;">Total:</p>
                                        <span style="font-size:13px;margin-left:2px;" id="totalCount"></span>
                                    </div>

                                </div>

                                <div style="margin-left: 6%;">
                                    <br>
                                    <span class="font-weight-bold" style="font-size: 15px;"><i class="fas fa-book"></i>
                                        Grade Remarks</span>
                                    <div class="d-flex">
                                        <p style="font-size:13px;">Passed:</p>
                                        <span style="font-size:13px;margin-left:2px;" id="passedCount"></span>
                                    </div>
                                    <div class="d-flex" style="margin-top:-8%;">
                                        <p style="font-size:13px;">Failed:</p>
                                        <span style="font-size:13px;margin-left:2px;" id="failedCount"></span>
                                    </div>


                                </div>
                            </div> --}}

                        </div>



                        <div class="col-md-12">
                            <button id="save_grades" class="btn btn-info btn-sm" disabled hidden>Save Grades</button>


                            <div class="dropdown" style="float: right;">
                                <button class="btn btn-primary btn-sm dropdown-toggle" type="button"
                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    SUBMIT Grades
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                                    style="background-color: transparent; border:none;">
                                    <a class="dropdown-item submit_all_btn" id="dropdown-item"
                                        style="background-color: blue" data-value="5" data-term="5">SUBMIT All</a>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                    </div>
                </div>
                <div class="modal-footer pt-1 pb-1" style="font-size:.7rem">
                    <i id="message_holder"></i>
                </div>
            </div>
        </div>
    </div>



    <div class="row" style="display: none;">
        <div class="col-md-12 table-responsive tableFixHead" style="height: 422px;">
            <table class="table table-sm table-striped table-bordered mb-0 table-head-fixed" style="font-size:.9rem"
                width="100%">
                <thead>
                    <tr>
                        <th width="5%"><input type="checkbox" disabled checked="checked" class="select_all"> </th>
                        <th width="20%">SID</th>
                        <th width="60%">Student</th>
                        <th width="15%" class="text-centerv">Grade</th>
                    </tr>
                </thead>
                <tbody id="datatable_4">

                </tbody>
            </table>
        </div>
    </div>


    <div class="modal fade" id="dean_holder_modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" style="font-size:.9rem">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="">Dean</label>
                            <select class="form-control select2" id="printable_dean">

                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-primary btn-sm" id="print_grades">Print</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Student Grades</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Student Grades</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content pt-0">
        <div class="container-fluid">
            <div class="row">
                <!-- Tab Navigation -->
                <div class="col-md-12">
                    <div class="info-box shadow-lg">
                        <div class="info-box-content">
                            <div class="row">

                                <div class="col-md-10">
                                    <ul class="nav nav-tabs nav-tabs-alt" data-toggle="tabs" role="tablist">
                                        <li class="nav-item col-md-3 bg-primary-lighter mr-2 mb-2">
                                            <a
                                                class="nav-link active"href="{{ url('college/teacher/student/view/systemgrading') }}">System
                                                Grading</a>
                                        </li>
                                        <li class="nav-item col-md-3 bg-primary-lighter mr-2 mb-2">
                                            <a class="nav-link " href="#excel_grading">Excel Grading</a>
                                        </li>
                                        <li class="nav-item col-md-3 bg-primary-lighter mr-2 mb-2">
                                            <a class="nav-link" href="{{ url('college/teacher/student/grades') }}">Final
                                                Grading</a>

                                        </li>
                                    </ul>
                                </div>

                                <div class="col-md-3">

                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Filters -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="info-box shadow-lg">
                                <div class="info-box-content">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="filter_sy">School Year</label>
                                            <select class="form-control form-control-sm select2" id="filter_sy">
                                                @foreach ($sy as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ $item->isactive == 1 ? 'selected' : '' }}>
                                                        {{ $item->sydesc }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="filter_semester">Semester</label>
                                            <select class="form-control form-control-sm select2" id="filter_semester">
                                                <option value="">Select semester</option>
                                                @foreach ($semester as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ $item->isactive == 1 ? 'selected' : '' }}>
                                                        {{ $item->semester }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Table -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card shadow">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12" style="font-size: .9rem">
                                            <table class="table table-sm table-striped" id="datatable_1">
                                                <thead>
                                                    <tr>
                                                        <th width="12%">Section</th>
                                                        <th width="33%">Subject</th>
                                                        <th width="18%">Level</th>
                                                        <th width="29%">Time Schedule</th>
                                                        <th width="13%">Day</th>
                                                        <th width="35%">Room</th>
                                                        <th width="43%">Enrolled</th>
                                                        <th width="43%">Action</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Optional Grade Status Section -->
                    {{-- <div class="row">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-header">
                            <h3 class="card-title">Grade Status</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12" style="font-size: .9rem">
                                    <table class="table table-sm table-striped" id="datatable_3">
                                        <thead>
                                            <tr>
                                                <th width="20%">Section</th>
                                                <th width="35%">Subject</th>
                                                <th width="10%" class="text-center">Prelim</th>
                                                <th width="10%" class="text-center" {{ strtoupper($schoolinfo) == 'SPCT' ? 'hidden' : '' }}>Midterm</th>
                                                <th width="10%" class="text-center" {{ strtoupper($schoolinfo) == 'SPCT' ? 'hidden' : '' }}>PreFinal</th>
                                                <th width="10%" class="text-center">Final</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
                </div>
                {{-- //////////////////////////////////////////////////////////////////////////////// --}}
                {{-- ////////////////////////////////////////////////////////////////// --}}
                <div class="modal fade" id="modal_4" tabindex="-1" role="dialog" aria-labelledby="modal_1Label"
                    data-backdrop="static" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header pb-2 pt-2 border-0 bg-gray">
                                <h6 class="modal-title">
                                    <span class="mt-1" id="subjectCodeHeader"></span> - <span class="mt-1"
                                        id="subjectNameHeader"></span>
                                </h6>
                                <div class="d-flex align-items-center ml-auto">

                                    <button type="button" class="close pb-2" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                            </div>
                            <div class="container-fluid headerText p-3">
                                <div class="row py-3">
                                    <!-- Teacher -->
                                    <div class="col-md-3">
                                        <div class="d-flex flex-column text-center">
                                            <span class="font-weight-bold h6"><i class="fas fa-chalkboard-teacher"></i>
                                                Teacher</span>
                                            <span id="teacherName">{{ auth()->user()->name }}</span>
                                            <a class="text-primary" id="teacherID">{{ auth()->user()->email }}</a>
                                        </div>
                                    </div>
                                    <!-- Subject -->
                                    <div class="col-md-3">
                                        <div class="d-flex flex-column text-center">
                                            <span class="font-weight-bold h6"><i class="fas fa-book"></i> Subject</span>
                                            <span id="subjectDescsSystem"></span>
                                            <a class="text-primary" id="subjectCodesSystem"></a>
                                        </div>
                                    </div>
                                    <!-- Level -->
                                    <div class="col-md-3">
                                        <div class="d-flex flex-column text-center">
                                            <span class="font-weight-bold h6"><i class="fas fa-graduation-cap"></i>
                                                Level</span>
                                            <span id="collegeLevelsSystem"></span>
                                        </div>
                                    </div>
                                    <!-- Section -->
                                    <div class="col-md-3">
                                        <div class="d-flex flex-column text-center">
                                            <span class="font-weight-bold h6"><i class="fas fa-building"></i>
                                                Section</span>
                                            <span id="sectionsSystem"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="row">
                                <div class="col-md-6"></div>
                                <div class="col-md-6">
                                    <div class="btn-actions-pane-lefy">
                                        <div role="group" class="btn-group-sm btn-group float-right">

                                            <button name="quarter" value="1" class="btn btn-success">PRELIM
                                                <p style="font-size: 11px;">Not
                                                    Submitted</p>
                                            </button>
                                            <button name="quarter" value="2" class="btn btn-success">MIDTERM
                                                <p style="font-size: 11px;">Not
                                                    Submitted</p>
                                            </button>

                                            <button name="quarter" value="3" class="btn btn-success">SEMI-FINAL
                                                <p style="font-size: 11px;">Not
                                                    Submitted</p>
                                            </button>
                                            <button name="quarter" value="4" class="btn btn-success">FINAL
                                                <p style="font-size: 11px;">Not
                                                    Submitted</p>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-6"></div>
                                    <div class="col-md-6">
                                        <div class="btn-actions-pane-lefy">
                                            <div role="group" class="btn-group-sm btn-group float-right">
                                                <button name="quarter" value="1" class="btn btn-success"
                                                    style="width: 129px;">PRELIM
                                                    <p>Not Submitted</p>
                                                </button>
                                                <button name="quarter" value="2" class="btn btn-success"
                                                    style="width: 129px;">MIDTERM
                                                    <p>Not Submitted</p>
                                                </button>
                                                <button name="quarter" value="3" class="btn btn-success"
                                                    style="width: 129px;">SEMI-FINAL
                                                    <p>Not Submitted</p>
                                                </button>
                                                <button name="quarter" value="4" class="btn btn-success"
                                                    style="width: 129px;">FINAL
                                                    <p>Not Submitted</p>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <br>

                            <div class="table-responsive" style="height: 460px;">
                                <table class="table table-sm table-bordered table-striped table-fixed">
                                    <thead>
                                        <tr>
                                            <th rowspan="3" class="text-center align-middle wide-header"
                                                style="text-align: left; position: sticky; left: 0;right: 0; z-index: 1; min-width: 150px; max-width: 150px; width: 350px; background-color: #fff;">
                                                &nbsp&nbsp&nbsp&nbspSTUDENT'S&nbsp&nbsp&nbsp&nbsp</th>

                                            <td colspan="13" class="text-center comp">OTHER REQUIREMENTS (20%)</td>
                                            <td colspan="13" class="text-center comp">PERFORMANCE TASKS (60%)</td>
                                            <td colspan="4" class="text-center comp">TERM EXAMINATION (20%)</td>
                                            {{-- <th class="text-center align-middle" rowspan="3">TOTAL<br>AVE</th> --}}
                                            <th class="text-center align-middle comp_6" rowspan="3"
                                                style="writing-mode: vertical-rl; transform: rotate(180deg); vertical-align: middle;color:green; background-color: #aad08e;">
                                                Total Average</th>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-center comp_1">ACTIVITIES (FORMATIVE)<br> 50%
                                            </td>
                                            {{-- <td rowspan="2" class="text-center comp_3">TOTAL</td> --}}
                                            <td rowspan="2"
                                                style="writing-mode: vertical-rl; transform: rotate(180deg); vertical-align: middle;">
                                                Total</td>
                                            <td rowspan="2" class="comp_3"
                                                style="writing-mode: vertical-rl; transform: rotate(180deg); vertical-align: middle;">
                                                Average</td>
                                            <td colspan="3" class="text-center comp_1">ASSESSMENT (SUMMATIVE) 50%</td>
                                            <td rowspan="2"
                                                style="writing-mode: vertical-rl; transform: rotate(180deg); vertical-align: middle;">
                                                Total</td>
                                            <td rowspan="2" class="comp_3"
                                                style="writing-mode: vertical-rl; transform: rotate(180deg); vertical-align: middle;">
                                                Average</td>
                                            <td rowspan="2" class="comp_4"
                                                style="writing-mode: vertical-rl; transform: rotate(180deg); vertical-align: middle;">
                                                Weighted Average</td>
                                            <td rowspan="2" class="text-center comp_5"
                                                style="writing-mode: vertical-rl; transform: rotate(180deg); vertical-align: middle;">
                                                %</td>

                                            <td colspan="4" class="text-center comp_1">UNIT REQUIREMENTS 50%</td>
                                            <td rowspan="2"
                                                style="writing-mode: vertical-rl; transform: rotate(180deg); vertical-align: middle;">
                                                Total</td>
                                            <td rowspan="2" class="comp_3"
                                                style="writing-mode: vertical-rl; transform: rotate(180deg); vertical-align: middle;">
                                                Average</td>
                                            <td colspan="3" class="text-center comp_1">TERMINAL REQUIREMENTS 50%</td>
                                            <td rowspan="2"
                                                style="writing-mode: vertical-rl; transform: rotate(180deg); vertical-align: middle;">
                                                Total</td>
                                            <td rowspan="2" class="comp_3"
                                                style="writing-mode: vertical-rl; transform: rotate(180deg); vertical-align: middle;">
                                                Average</td>
                                            <td rowspan="2" class="comp_4"
                                                style="writing-mode: vertical-rl; transform: rotate(180deg); vertical-align: middle;">
                                                Weighted Average</td>
                                            <td rowspan="2" class="text-center comp_5"
                                                style="writing-mode: vertical-rl; transform: rotate(180deg); vertical-align: middle;">
                                                %</td>


                                            <td rowspan="1" class="text-center comp_1">PRELIM</td>
                                            <td rowspan="2"
                                                style="writing-mode: vertical-rl; transform: rotate(180deg); vertical-align: middle;">
                                                Total</td>
                                            <td rowspan="2" class="comp_4"
                                                style="writing-mode: vertical-rl; transform: rotate(180deg); vertical-align: middle;">
                                                Weighted Average</td>
                                            <td rowspan="2" class="text-center comp_5"
                                                style="writing-mode: vertical-rl; transform: rotate(180deg); vertical-align: middle;">
                                                %</td>



                                        </tr>
                                        <tr>
                                            <td class="text-center comp_2">F1</td>
                                            <td class="text-center comp_2">F2</td>
                                            <td class="text-center comp_2">F3</td>
                                            <td class="text-center comp_2">F4</td>
                                            {{-- <td class="text-center comp_2">F5</td>
                                            <td class="text-center comp_2">F6</td> --}}

                                            <td class="text-center comp_2">S1</td>
                                            <td class="text-center comp_2">S2</td>
                                            <td class="text-center comp_2">S3</td>
                                            {{-- <td class="text-center comp_2">S4</td>
                                            <td class="text-center comp_2">S5</td>
                                            <td class="text-center comp_2">S6</td> --}}

                                            <td class="text-center comp_2">UR1</td>
                                            <td class="text-center comp_2">UR2</td>
                                            <td class="text-center comp_2">UR3</td>
                                            <td class="text-center comp_2">UR4</td>


                                            <td class="text-center comp_2">TR1</td>
                                            <td class="text-center comp_2">TR2</td>
                                            <td class="text-center comp_2">TR3</td>

                                            <td class="text-center comp_2">EXAM</td>
                                        </tr>
                                        <tr>
                                            <th class="comp_7 wide-header"
                                                style="text-align: left; position: sticky; left: 0; z-index: 1; min-width: 150px; max-width: 150px; width: 150px; background-color: #fff;">
                                                Highest
                                                Score</th>
                                            {{-- align-middle --}}

                                            <td class="formativeHighestScore" data-component="F1HighestScore"
                                                id="F1HighestScore" contenteditable="true"></td>
                                            <td class="formativeHighestScore" data-component="F2HighestScore"
                                                id="F2HighestScore" contenteditable="true"></td>
                                            <td class="formativeHighestScore" data-component="F3HighestScore"
                                                id="F3HighestScore" contenteditable="true"></td>
                                            <td class="formativeHighestScore" data-component="F4HighestScore"
                                                id="F4HighestScore" contenteditable="true"></td>

                                            <td class="formative_totalHighestScore" id="formative_totalHighestScore"></td>
                                            <td class="formative_averageHighestScore comp_3"
                                                id="formative_averageHighestScore">
                                            </td>

                                            <td class="summativeHighestScore" data-component="S1HighestScore"
                                                id="S1HighestScore" contenteditable="true"></td>
                                            <td class="summativeHighestScore" data-component="S2HighestScore"
                                                id="S2HighestScore" contenteditable="true"></td>
                                            <td class="summativeHighestScore" data-component="S3HighestScore"
                                                id="S3HighestScore" contenteditable="true"></td>

                                            <td class="summative_totalHighestScore" id="summative_totalHighestScore"></td>
                                            <td class="summative_averageHighestScore comp_3"
                                                id="summative_averageHighestScore">
                                            </td>
                                            <td class="other_requirements_geHighestScore comp_4"
                                                id="other_requirements_geHighestScore"></td>
                                            <td class="other_requirements_percentHighestScore comp_5"
                                                id="other_requirements_percentHighestScore"></td>

                                            <td class="unitHighestScore" data-component="UR1HighestScore"
                                                id="UR1HighestScore" contenteditable="true"></td>
                                            <td class="unitHighestScore" data-component="UR2HighestScore"
                                                id="UR2HighestScore" contenteditable="true"></td>
                                            <td class="unitHighestScore" data-component="UR3HighestScore"
                                                id="UR3HighestScore" contenteditable="true"></td>
                                            <td class="unitHighestScore" data-component="UR4HighestScore"
                                                id="UR4HighestScore" contenteditable="true"></td>
                                            <td class="unit_requirements_totalHighestScore"></td>
                                            <td class="unit_requirements_avgHighestScore comp_3"></td>

                                            <td class="terminalHighestScore" data-component="TR1HighestScore"
                                                id="TR1HighestScore" contenteditable="true"></td>
                                            <td class="terminalHighestScore" data-component="TR2HighestScore"
                                                id="TR2HighestScore" contenteditable="true"></td>
                                            <td class="terminalHighestScore" data-component="TR3HighestScore"
                                                id="TR3HighestScore" contenteditable="true"></td>
                                            <td class="terminal_totalHighestScore"></td>
                                            <td class="terminal_averageHighestScore comp_3"></td>
                                            <td class="performace_tasks_general_averageHighestScore comp_4"></td>
                                            <td class="performace_tasks_percentageHighestScore comp_5"></td>


                                            <td class="term_examHighestScore" contenteditable="true"
                                                id="term_examHighestScore"></td>
                                            <td class="term_exam_totalHighestScore"></td>
                                            <td class="term_exam_general_averageHighestScore comp_4"></td>
                                            <td class="term_percentageHighestScore comp_5"></td>
                                            <td class="TOTAL_AVERAGEHighestScore"
                                                style="color:green; background-color: #aad08e;"></td>

                                        </tr>
                                    </thead>
                                    <tbody id="student_list_gradess">

                                    </tbody>

                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-12 mt-3">
                                <button id="save_grades2" class="btn btn-primary btn-sm">Save Grades</button>


                                <div class="dropdown" style="float: right;">
                                    <button class="btn btn-success btn-sm" type="button" id="dropdownMenuButton2">
                                        SUBMIT Grades
                                    </button>
                                    {{-- <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                                        style="background-color: transparent; border:none;">
                                        <a class="dropdown-item submit_all_btn" id="dropdown-item"
                                            style="background-color: blue" data-value="5" data-term="5">SUBMIT All</a>
                                    </div> --}}
                                </div>

                            </div>
                            <br>
                            <br>


                        </div>
                    </div>
                </div>
    </section>
@endsection

@section('footerscript')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="https://cdn.datatables.net/rowgroup/1.1.0/js/dataTables.rowGroup.min.js"></script>
    {{-- <script>
            $(document).ready(function () {

                  $(document).on('click','.submit_grades',function(){
                  
                  })
            })
      </script> --}}

    <script>
        $('#filter_sy').select2()
        $('#filter_semester').select2()
    </script>

    <script>
        $(document).ready(function() {

            var school = @json($schoolinfo);



            var isSaved = false;
            var isvalidHPS = true;
            var hps = []
            var currentIndex
            var can_edit = true




            $(document).on('click', '.input_grades', function() {

                var term = $(this).attr('data-term')
                var checkDateSetup = []

                if (term == 1) {
                    checkDateSetup = inputperiod.filter(x => x.term == 'prelim')
                } else if (term == 2) {
                    checkDateSetup = inputperiod.filter(x => x.term == 'midterm')
                } else if (term == 3) {
                    checkDateSetup = inputperiod.filter(x => x.term == 'prefi')
                } else if (term == 4) {
                    checkDateSetup = inputperiod.filter(x => x.term == 'finalterm')
                }

                if (checkDateSetup.length > 0) {
                    if (!checkDateSetup[0].isended) {
                        Toast.fire({
                            type: 'warning',
                            title: 'Deadline Ended.'
                        })
                        return false
                    }
                }

                // if(inputperiod.length == 0){
                //       Toast.fire({
                //             type: 'warning',
                //             title: 'Grade input is not yet open.'
                //       })
                //       return false
                // }

                if (school == 'spct'.toUpperCase() && $(this).attr('data-term') == 'finalgrade') {
                    return false;
                }

                if (currentIndex != undefined) {
                    if (isvalidHPS) {
                        if (can_edit) {
                            string = $(this).text();
                            currentIndex = this;
                            $('#start').length > 0 ? dotheneedful(this) : false
                            $('td').removeAttr('style');
                            $('#start').removeAttr('id')
                            $(this).attr('id', 'start')
                            $(currentIndex).removeClass('bg-danger')
                            $(currentIndex).removeClass('bg-warning')
                            var start = document.getElementById('start');
                            start.focus();
                            start.style.backgroundColor = 'green';
                            start.style.color = 'white';
                        }
                    }
                } else {
                    if (can_edit) {
                        string = $(this).text();
                        currentIndex = this;
                        $('#start').length > 0 ? dotheneedful(this) : false
                        $('td').removeAttr('style');
                        $('#start').removeAttr('id')
                        $(this).attr('id', 'start')
                        $(currentIndex).removeClass('bg-danger')
                        $(currentIndex).removeClass('bg-warning')
                        var start = document.getElementById('start');
                        start.focus();
                        start.style.backgroundColor = 'green';
                        start.style.color = 'white';

                    }
                }
            })


            function dotheneedful(sibling) {

                var term = $(sibling).attr('data-term')

                if (term == 1) {
                    checkDateSetup = inputperiod.filter(x => x.term == 'prelim')
                } else if (term == 2) {
                    checkDateSetup = inputperiod.filter(x => x.term == 'midterm')
                } else if (term == 3) {
                    checkDateSetup = inputperiod.filter(x => x.term == 'prefi')
                } else if (term == 4) {
                    checkDateSetup = inputperiod.filter(x => x.term == 'finalterm')
                }

                if (checkDateSetup.length > 0) {
                    if (!checkDateSetup[0].isended) {
                        Toast.fire({
                            type: 'warning',
                            title: 'Deadline Ended.'
                        })
                        return false
                    }
                }

                if (sibling != null) {
                    currentIndex = sibling
                    $(sibling).removeClass('bg-danger')
                    $(sibling).removeClass('bg-warning')

                    if ($(start).text() == 'DROPPED') {
                        $(start).addClass('bg-danger')
                    } else if ($(start).text() == 'INC' || $(start).attr('data-status') == 3) {
                        $(start).addClass('bg-warning')
                    }

                    start.style.backgroundColor = '';
                    start.style.color = '';
                    sibling.focus();
                    sibling.style.backgroundColor = 'green';
                    sibling.style.color = 'white';
                    start = sibling;



                    $('#message').empty();
                    string = $(currentIndex)[0].innerText
                }
            }

            document.onkeydown = checkKey;

            function checkKey(e) {

                e = e || window.event;
                if (e.keyCode == '38' && currentIndex != undefined) {
                    var idx = start.cellIndex;
                    var nextrow = start.parentElement.previousElementSibling;
                    if (nextrow == null || !$(nextrow.cells[idx]).hasClass('input_grades')) {
                        return false;
                    }
                    if (school == 'spct'.toUpperCase() && $(nextrow.cells[idx]).attr('data-term') == 'finalgrade') {
                        return false;
                    } else {
                        $('#curText').text(string)
                        var sibling = nextrow.cells[idx];
                        if (sibling == undefined) {
                            return false;
                        }
                        string = sibling.innerText;
                        dotheneedful(sibling);
                    }
                } else if (e.keyCode == '40' && currentIndex != undefined) {
                    var idx = start.cellIndex;
                    var nextrow = start.parentElement.nextElementSibling;
                    if (nextrow == null || !$(nextrow.cells[idx]).hasClass('input_grades')) {
                        return false;
                    }
                    if (school == 'spct'.toUpperCase() && $(nextrow.cells[idx]).attr('data-term') == 'finalgrade') {
                        return false;
                    } else {
                        $('#curText').text(string)
                        var sibling = nextrow.cells[idx];
                        if (sibling == undefined) {
                            return false;
                        }
                        string = sibling.innerText;
                        dotheneedful(sibling);
                    }
                } else if (e.keyCode == '37' && currentIndex != undefined) {
                    var sibling = start.previousElementSibling;
                    if (sibling == null || !$(sibling).hasClass('input_grades')) {
                        return false;
                    } else if ($(sibling)[0].nodeName != "TD") {
                        return false;
                    }
                    if (school == 'spct'.toUpperCase() && $(sibling).attr('data-term') == 'finalgrade') {
                        return false;
                    }
                    $('#curText').text(string)
                    if ($(sibling)[0].cellIndex != 0) {
                        string = sibling.innerText;
                        dotheneedful(sibling);
                    }

                } else if (e.keyCode == '39' && currentIndex != undefined) {
                    var sibling = start.nextElementSibling;
                    if (sibling == null || !$(sibling).hasClass('input_grades')) {
                        return false;
                    } else if ($(sibling)[0].nodeName != "TD") {
                        return false;
                    }
                    if (school == 'spct'.toUpperCase() && $(sibling).attr('data-term') == 'finalgrade') {
                        return false;
                    }
                    $('#curText').text(string)
                    if ($(sibling)[0].cellIndex != 0) {
                        string = sibling.innerText;
                        dotheneedful(sibling);
                    }
                } else if (e.keyCode == '73' && currentIndex != undefined) {
                    $(currentIndex).text("INC")
                    $(currentIndex).addClass('updated')
                    $('#save_grades').removeAttr('disabled')
                    $('#grade_submit').attr('disabled', 'disabled')
                } else if (e.keyCode == '68' && currentIndex != undefined) {
                    $(currentIndex).text("DROPPED")
                    $(currentIndex).addClass('updated')
                    $('#save_grades').removeAttr('disabled')
                    $('#grade_submit').attr('disabled', 'disabled')
                } else if (e.key == "Backspace" && currentIndex != undefined) {

                    // if(currentIndex.innerText == 'INC' || currentIndex.innerText == 'DROPPED'){
                    //       $(currentIndex).text('')
                    //       $('#curText').text("")
                    //       string = ''
                    //       $(currentIndex).addClass('updated')
                    //       $('#grade_submit').attr('disabled','disabled')
                    //       $('#save_grades').removeAttr('disabled')
                    //       return false
                    // }

                    if (currentIndex.innerText == 'INC' || currentIndex.innerText == 'DROPPED') {
                        string = ''
                    } else {
                        string = currentIndex.innerText
                        string = string.slice(0, -1);
                    }



                    if (string.length == 0) {
                        string = '';
                        currentIndex.innerText = string
                    } else {
                        currentIndex.innerText = parseInt(string)
                        inputIndex = currentIndex
                    }



                    $(currentIndex).addClass('updated')
                    $('#save_grades').removeAttr('disabled')
                    $('#grade_submit').attr('disabled', 'disabled')

                    $(currentIndex).text(string)
                    $('#curText').text(string)

                    var temp_studid = $(currentIndex).attr('data-studid')
                    var prelim = parseFloat($('.grade_td[data-studid="' + temp_studid + '"][data-term="1"]')
                        .text());
                    var midterm = parseFloat($('.grade_td[data-studid="' + temp_studid + '"][data-term="2"]')
                        .text());
                    var prefi = parseFloat($('.grade_td[data-studid="' + temp_studid + '"][data-term="3"]').text());
                    var final = parseFloat($('.grade_td[data-studid="' + temp_studid + '"][data-term="4"]').text());

                    if (gradesetup.f_frontend != '' || gradesetup.f_frontend != null) {

                        var fg = eval(gradesetup.f_frontend).toFixed(gradesetup.decimalPoint)
                        if (!isNaN(fg)) {
                            $('th[data-studid="' + temp_studid + '"][data-term="5"]').text(fg)
                            $('th[data-studid="' + temp_studid + '"][data-term="5"]').addClass('updated')
                            $('th[data-studid="' + temp_studid + '"][data-term="6"]').addClass('updated')

                            if (gradesetup.isPointScaled == 0) {
                                if (fg >= gradesetup.passingRate) {
                                    $('th[data-studid="' + temp_studid + '"][data-term="6"]').text('PASSED')
                                } else {
                                    $('th[data-studid="' + temp_studid + '"][data-term="6"]').text('FAILED')
                                }
                            } else {
                                if (fg <= gradesetup.passingRate) {
                                    $('th[data-studid="' + temp_studid + '"][data-term="6"]').text('PASSED')
                                } else {
                                    $('th[data-studid="' + temp_studid + '"][data-term="6"]').text('FAILED')
                                }
                            }

                        } else {
                            $('th[data-studid="' + temp_studid + '"][data-term="5"]').addClass('updated')
                            $('th[data-studid="' + temp_studid + '"][data-term="6"]').addClass('updated')

                            $('th[data-studid="' + temp_studid + '"][data-term="5"]').text(null)
                            $('th[data-studid="' + temp_studid + '"][data-term="6"]').text(null)
                        }
                    }

                } else if (((e.key >= 0 && e.key <= 9) || e.key == '.') && currentIndex != undefined) {




                    //check ForPoint
                    if (e.key == '.') {
                        if (gradesetup.decimalPoint == 0) {
                            return false
                        }
                        var checkForPoint = string.includes('.')
                        if (checkForPoint) {
                            return false
                        }
                    }

                    var check_string = string + e.key;
                    var decimalcount = count_decimal(check_string)



                    if (decimalcount <= gradesetup.decimalPoint) {
                        string += e.key;
                    } else {
                        string = string;
                    }




                    if (gradesetup.isPointScaled == 0) {
                        if (check_string > 100) {
                            string = 100
                        }
                    } else {
                        if (check_string > 5) {
                            return false
                        }
                    }


                    if (currentIndex.innerText == 'INC' || currentIndex.innerText == 'DROPPED') {
                        string = ''
                    }

                    $(currentIndex).addClass('updated')
                    $('#save_grades').removeAttr('disabled')
                    $('#grade_submit').attr('disabled', 'disabled')

                    $(currentIndex).text(string)
                    $('#curText').text(string)

                    var temp_studid = $(currentIndex).attr('data-studid')
                    var prelim = parseFloat($('.grade_td[data-studid="' + temp_studid + '"][data-term="1"]')
                        .text());
                    var midterm = parseFloat($('.grade_td[data-studid="' + temp_studid + '"][data-term="2"]')
                        .text());
                    var prefi = parseFloat($('.grade_td[data-studid="' + temp_studid + '"][data-term="3"]').text());
                    var final = parseFloat($('.grade_td[data-studid="' + temp_studid + '"][data-term="4"]').text());

                    if (gradesetup.f_frontend != '' || gradesetup.f_frontend != null) {

                        var fg = eval(gradesetup.f_frontend).toFixed(gradesetup.decimalPoint)

                        if (!isNaN(fg)) {
                            $('th[data-studid="' + temp_studid + '"][data-term="5"]').text(fg)
                            $('th[data-studid="' + temp_studid + '"][data-term="5"]').addClass('updated')
                            $('th[data-studid="' + temp_studid + '"][data-term="6"]').addClass('updated')

                            if (gradesetup.isPointScaled == 0) {
                                if (fg >= gradesetup.passingRate) {
                                    $('th[data-studid="' + temp_studid + '"][data-term="6"]').text('PASSED')
                                } else {
                                    $('th[data-studid="' + temp_studid + '"][data-term="6"]').text('FAILED')
                                }
                            } else {
                                if (fg <= gradesetup.passingRate) {
                                    $('th[data-studid="' + temp_studid + '"][data-term="6"]').text('PASSED')
                                } else {
                                    $('th[data-studid="' + temp_studid + '"][data-term="6"]').text('FAILED')
                                }
                            }


                        } else {
                            $('th[data-studid="' + temp_studid + '"][data-term="5"]').text('')
                            $('th[data-studid="' + temp_studid + '"][data-term="6"]').text('')
                            $('th[data-studid="' + temp_studid + '"][data-term="5"]').text(null)
                            $('th[data-studid="' + temp_studid + '"][data-term="6"]').text(null)
                        }
                    }

                }

            }

        })

        function count_decimal(num) {
            const converted = num.toString();
            if (converted.includes('.')) {
                return converted.split('.')[1].length;
            };
            return 0;
        }
    </script>

    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
        })

        var gradesetup = [];

        getgradesetup()

        function getgradesetup() {
            $.ajax({
                type: 'GET',
                url: '/semester-setup/getactive-setup',
                async: false,
                data: {
                    syid: $('#filter_sy').val(),
                    semid: $('#filter_semester').val(),
                },
                success: function(data) {
                    gradesetup = data
                    if (gradesetup.length == 0) {
                        $('#grades_setup_holder').attr('hidden', 'hidden')
                        $('#grades_setup_holder')[0].innerHTML =
                            '<div class="col-md-12"><p class="mb-0 text-danger">* No available grade setup.</p></div>'
                    } else {

                        $('#grades_setup_holder').removeAttr('hidden', 'hidden')
                        gradesetup = gradesetup[0]

                        var termtext = ''
                        if (gradesetup.prelim == 1) {
                            termtext += '<span class="badge badge-primary ml-1">Prelim</span>'
                        }
                        if (gradesetup.midterm == 1) {
                            termtext += '<span class="badge badge-primary ml-1">Midterm</span>'
                        }
                        if (gradesetup.prefi == 1) {
                            termtext += '<span class="badge badge-primary ml-1">Prefi</span>'
                        }
                        if (gradesetup.final == 1) {
                            termtext += '<span class="badge badge-primary ml-1">Final</span>'
                        }
                        $('#setup_term_holder')[0].innerHTML = termtext
                        $('#setup_fgc_holder').text(gradesetup.f_frontend)
                        $('#setup_dp_holder').text(gradesetup.decimalPoint)


                        if (gradesetup.isPointScaled == 1) {
                            $('#setup_gs_holder').text('Decimal Point Scale ( 1 - 5 )')
                        } else {
                            $('#setup_gs_holder').text('Numerical Point Scale ( 60 - 100 )')
                        }

                        // $('#grades_setup_holder').remove()
                    }
                }
            })
        }

        var inputperiod = []
        getinputperiod()

        function getinputperiod() {
            $.ajax({
                type: 'GET',
                url: '/college/inputperiods/get/active',
                async: false,
                data: {
                    syid: $('#filter_sy').val(),
                    semid: $('#filter_semester').val(),
                },
                success: function(data) {
                    inputperiod = data

                    $.each(inputperiod, function(a, b) {
                        var pastDate = moment(b.dateend);
                        var dDiff = moment().isBefore(pastDate);
                        b.isended = dDiff
                    })


                    // if(inputperiod.length == 0){
                    //       $('#input_period_holder').attr('hidden','hidden')
                    //       // $('#input_period_holder').empty()
                    //       $('#input_period_holder')[0].innerHTML = '<div class="col-md-12"><p class="mb-0 text-danger">* No available input Period.</p></div>'
                    // }else{
                    //       $('#input_period_holder').removeAttr('hidden')
                    //       $('#input_period_holder')[0].innerHTML = '<div class="col-md-12"><label>Input Period: </label>'+inputperiod[0].startformat2 + ' - ' + inputperiod[0].endformat2+'</div>'
                    // }
                }
            })
        }



        $(document).ready(function() {



            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // $(document).on('click', '#grade_submit', function() {
            //     $('#quarter_select').val("")
            //     $('.grade_submission_student').empty()
            //     $('.select').attr('disabled', 'disabled')
            //     $('.select').removeAttr('data-id')
            //     $('.select_all').attr('disabled', 'disabled')
            //     $('.select').prop('checked', true)
            //     $('.select_all').prop('checked', true)
            //     $('#submit_selected_grade').attr('disabled', 'disabled')
            //     // $('#submit_selected_grade').removeAttr('class')
            //     // $('#submit_selected_grade').addClass('btn btn-primary float-right btn-sm')
            //     // $('#submit_selected_grade').text('Submit Grades')
            //     // $('#submit_selected_grade').attr('data-id',1)
            //     $('#modal_3').modal()
            // })
            /////////////////////////////////////////////////////////////////////////////////
            $(document).on('click', '.select_all', function() {
                if ($(this).prop('checked') == true) {
                    $('.select').prop('checked', true)
                } else {
                    $('.select').each(function() {
                        if ($(this).attr('disabled') == undefined) {
                            $(this).prop('checked', false)
                        }
                    })
                }
            })
            ////////////////////////////////////////////////////////////////////

            $(document).on('change', '#quarter_select', function() {
                var term = $(this).val()
                if (term == "") {
                    $('.select_all').attr('disabled', 'disabled')
                    $('.select').attr('disabled', 'disabled')
                    $('.grade_submission_student').text()
                    $('#submit_selected_grade').attr('disabled', 'disabled')
                    $('.select').removeAttr('data-id')
                    $('.grade_submission_student').empty()
                    return false
                }
                $('#submit_selected_grade').removeAttr('disabled')
                $('.select_all').removeAttr('disabled')
                $('.select').removeAttr('disabled')
                $('.grade_td[data-term="' + term + '"]').each(function(a, b) {
                    if ($(this).attr('data-status') == 1 || $(this).attr('data-status') == 7 || $(
                            this).attr('data-status') == 8 || $(this).attr('data-status') == 9 || $(
                            this).attr('data-status') == 2 || $(this).attr('data-status') == 4) {
                        $('.select[data-studid="' + $(this).attr('data-studid') + '"]').attr(
                            'disabled', 'disabled')
                    }
                    $('.grade_submission_student[data-studid="' + $(this).attr('data-studid') +
                        '"]').text($(this).text())
                    $('.select[data-studid="' + $(this).attr('data-studid') + '"]').attr('data-id',
                        $(this).attr('data-id'))
                })
            })



            function get_term(term) {
                if (term == 1) {
                    return "prelemgrade"
                } else if (term == 2) {
                    return "midtermgrade"
                } else if (term == 3) {
                    return "prefigrade"
                } else if (term == 4) {
                    return "finalgrade"
                } else if (term == 5) {
                    return "submitall"
                }

            }

            function submit_grade(clickedElement) {

                var selected = []

                var term = $(clickedElement).data('value'); // Get data-value from clicked element

                console.log(term);

                var dterm = term
                term = get_term(term)

                $('.select').each(function() {
                    if ($(this).prop('checked') == true && $(this).attr('disabled') == undefined && $(this)
                        .attr('data-id') != undefined) {
                        selected.push($(this).attr('data-id'))
                    }
                })



                if (selected.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        // title: 'No Student Selected',
                        text: 'Please select at least one student before submitting.',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });
                    return; // Exit the function if no items are selected
                }




                Swal.fire({
                    html: '<h4>Are you sure you want <br>' +
                        'to submit grades?</h4>',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Submit Grades!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: '/college/teacher/student/new/grades/submit',
                            data: {
                                syid: $('#filter_sy').val(),
                                semid: $('#filter_semester').val(),
                                term: term,
                                selected: selected,
                            },
                            success: function(data) {
                                if (data[0].status == 1) {

                                    Toast.fire({
                                        icon: 'success',
                                        title: 'Submitted!'
                                    });



                                    $.each(selected, function(a, b) {
                                        var inputSelector = '.input_grades[data-id="' +
                                            b + '"][data-term="' + dterm + '"]';
                                        $(inputSelector).removeClass('bg-warning')
                                            .addClass('bg-success').removeClass(
                                                'input_grades');
                                        ////////////////////////////////////


                                        $(inputSelector).attr('data-status', 1);

                                        // Update all_grades array and replot if needed
                                        var temp_id = all_grades.findIndex(x => x.id ==
                                            b);
                                        if (dterm == 1) {
                                            all_grades[temp_id].prelemstatus = 1;



                                        } else if (dterm == 2) {
                                            all_grades[temp_id].midtermstatus = 1;


                                        } else if (dterm == 3) {
                                            all_grades[temp_id].prefistatus = 1;

                                        } else if (dterm == 4) {
                                            all_grades[temp_id].finalstatus = 1;


                                        } else if (dterm == 5) {
                                            all_grades[temp_id].prelemstatus = 1;

                                            all_grades[temp_id].midtermstatus = 1;
                                            all_grades[temp_id].prefistatus = 1;
                                            all_grades[temp_id].finalstatus = 1;


                                        }

                                        plot_subject_grades(all_grades);
                                    });

                                } else {
                                    Toast.fire({
                                        icon: 'error',
                                        title: 'Something went wrong!'
                                    });
                                }
                            },
                            error: function() {
                                Toast.fire({
                                    icon: 'error',
                                    title: 'Something went wrong!'
                                });
                            }
                        });
                    }
                });



            }



            function inc_grade() {

                var selected = []
                var students = []
                var term = $('#quarter_select').val()
                term = get_term(term)

                $('.select').each(function() {
                    if ($(this).prop('checked') == true && $(this).attr('disabled') == undefined) {
                        selected.push($(this).attr('data-id'))
                        students.push($(this).attr('data-id'))
                    }
                })

                Swal.fire({
                    html: '<h4>Are you sure you want <br>' +
                        'to mark student as INC?</h4>',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Submit Grades!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: '/college/teacher/student/grades/inc',
                            data: {
                                syid: $('#filter_sy').val(),
                                semid: $('#filter_semester').val(),
                                term: term,
                                selected: selected,
                            },
                            success: function(data) {
                                if (data[0].status == 1) {
                                    Toast.fire({
                                        type: 'success',
                                        title: 'Submitted Successfully!'
                                    })
                                    $.each(selected, function(a, b) {
                                        $('.select[data-id="' + b + '"]').attr(
                                            'disabled', 'disabled')
                                        $('.input_grades[data-id="' + b +
                                            '"][data-term="' + term + '"]').attr(
                                            'data-status', 1)
                                        $('.input_grades[data-id="' + b +
                                                '"][data-term="' + term + '"]')
                                            .addClass('bg-success')
                                        $('.input_grades[data-id="' + b +
                                                '"][data-term="' + term + '"]')
                                            .removeClass('input_grades')
                                        var temp_id = all_grades.findIndex(x => x.id ==
                                            b)

                                        if (term == 'prelemgrade') {
                                            all_grades[temp_id].prelemstatus = 1
                                        } else if (term == 'midtermgrade') {
                                            all_grades[temp_id].midtermstatus = 1
                                        } else if (term == 'prefigrade') {
                                            all_grades[temp_id].prefistatus = 1
                                        } else if (term == 'finalgrade') {
                                            all_grades[temp_id].finalstatus = 1
                                        }
                                        plot_subject_grades(all_grades)

                                    })
                                } else {
                                    Toast.fire({
                                        type: 'error',
                                        title: 'Something went wrong!'
                                    })
                                }
                            },
                            error: function() {
                                Toast.fire({
                                    type: 'error',
                                    title: 'Something went wrong!'
                                })
                            }
                        })
                    }
                })

            }

            $(document).on('click', '#save_grades', function() {

                $('#save_grades').text('Saving Grades...')
                $('#save_grades').removeClass('btn-primary')
                $('#save_grades').addClass('btn-secondary')
                $('#save_grades').attr('disabled', 'disabled')

                if ($('.updated[data-term="1"]').length == 0) {
                    save_midterm()
                }

                $('.updated[data-term="1"]').each(function(a, b) {
                    var studid = $(this).attr('data-studid')
                    var term = $(this).attr('data-term')
                    var courseid = $(this).attr('data-course')
                    var sectionid = $(this).attr('data-section')
                    var pid = $(this).attr('data-pid')
                    var termgrade = $(this).text()
                    var td = $(this)
                    $.ajax({
                        type: 'POST',
                        url: '/college/teacher/student/grades/save',
                        data: {
                            syid: $('#filter_sy').val(),
                            semid: $('#filter_semester').val(),
                            term: "prelemgrade",
                            sectionid: sectionid,
                            termgrade: termgrade,
                            studid: studid,
                            courseid: courseid,
                            pid: pid,
                        },
                        success: function(data) {
                            $(td).removeClass('updated')
                            if ($('.updated[data-term="1"]').length == 0) {
                                save_midterm()

                            }
                        }
                    })
                })


            })

            function save_midterm() {
                if ($('.updated[data-term="2"]').length == 0) {
                    save_prefi()
                }
                $('.updated[data-term="2"]').each(function(a, b) {
                    var studid = $(this).attr('data-studid')
                    var term = $(this).attr('data-term')
                    var courseid = $(this).attr('data-course')
                    var sectionid = $(this).attr('data-section')
                    var pid = $(this).attr('data-pid')
                    var termgrade = $(this).text()
                    var td = $(this)
                    $.ajax({
                        type: 'POST',
                        url: '/college/teacher/student/grades/save',
                        data: {
                            syid: $('#filter_sy').val(),
                            semid: $('#filter_semester').val(),
                            term: "midtermgrade",
                            sectionid: sectionid,
                            termgrade: termgrade,
                            studid: studid,
                            courseid: courseid,
                            pid: pid,
                        },
                        success: function(data) {
                            $(td).removeClass('updated')
                            if ($('.updated[data-term="2"]').length == 0) {
                                save_prefi()
                            }
                        }
                    })
                })

            }

            function save_prefi() {
                if ($('.updated[data-term="3"]').length == 0) {
                    save_final()
                }
                $('.updated[data-term="3"]').each(function(a, b) {
                    var studid = $(this).attr('data-studid')
                    var term = $(this).attr('data-term')
                    var courseid = $(this).attr('data-course')
                    var sectionid = $(this).attr('data-section')
                    var pid = $(this).attr('data-pid')
                    var termgrade = $(this).text()
                    var td = $(this)
                    $.ajax({
                        type: 'POST',
                        url: '/college/teacher/student/grades/save',
                        data: {
                            syid: $('#filter_sy').val(),
                            semid: $('#filter_semester').val(),
                            term: "prefigrade",
                            sectionid: sectionid,
                            termgrade: termgrade,
                            studid: studid,
                            courseid: courseid,
                            pid: pid,
                        },
                        success: function(data) {
                            $(td).removeClass('updated')
                            if ($('.updated[data-term="3"]').length == 0) {
                                save_final()
                            }
                        }
                    })
                })

            }

            function save_final() {
                if ($('.updated[data-term="4"]').length == 0) {
                    save_fg()
                }
                $('.updated[data-term="4"]').each(function(a, b) {
                    var studid = $(this).attr('data-studid')
                    var term = $(this).attr('data-term')
                    var courseid = $(this).attr('data-course')
                    var sectionid = $(this).attr('data-section')
                    var pid = $(this).attr('data-pid')
                    var termgrade = $(this).text()
                    var td = $(this)
                    $.ajax({
                        type: 'POST',
                        url: '/college/teacher/student/grades/save',
                        data: {
                            syid: $('#filter_sy').val(),
                            semid: $('#filter_semester').val(),
                            term: "finalgrade",
                            sectionid: sectionid,
                            termgrade: termgrade,
                            studid: studid,
                            courseid: courseid,
                            pid: pid,
                        },
                        success: function(data) {
                            $(td).removeClass('updated')
                            if ($('.updated[data-term="4"]').length == 0) {
                                save_final()
                            }
                        }
                    })
                })
            }

            function save_fg() {
                if ($('.updated[data-term="5"]').length == 0) {
                    save_fgremarks()
                }
                $('.updated[data-term="5"]').each(function(a, b) {
                    var studid = $(this).attr('data-studid')
                    var term = $(this).attr('data-term')
                    var courseid = $(this).attr('data-course')
                    var sectionid = $(this).attr('data-section')
                    var pid = $(this).attr('data-pid')
                    var termgrade = $(this).text()
                    var td = $(this)
                    $.ajax({
                        type: 'POST',
                        url: '/college/teacher/student/grades/save',
                        data: {
                            syid: $('#filter_sy').val(),
                            semid: $('#filter_semester').val(),
                            term: "fg",
                            sectionid: sectionid,
                            termgrade: termgrade,
                            studid: studid,
                            courseid: courseid,
                            pid: pid,
                        },
                        success: function(data) {
                            $(td).removeClass('updated')
                            if ($('.updated[data-term="5"]').length == 0) {
                                save_final()
                            }
                        }
                    })
                })
            }

            function save_fgremarks() {
                if ($('.updated[data-term="6"]').length == 0) {
                    Toast.fire({
                        type: 'success',
                        title: 'Saved Successfully!'
                    })
                    $('#save_grades').attr('disabled', 'disabled')
                    $('#save_grades').removeClass('btn-secondary')
                    $('#save_grades').addClass('btn-primary')
                    $('#save_grades').text('Save Grades')
                    $('#grade_submit').removeAttr('disabled')

                    var temp_students = all_subject.filter(x => x.schedid == schedid)
                    get_grades(schedid, false, temp_students[0].students)

                }
                $('.updated[data-term="6"]').each(function(a, b) {
                    var studid = $(this).attr('data-studid')
                    var term = $(this).attr('data-term')
                    var courseid = $(this).attr('data-course')
                    var sectionid = $(this).attr('data-section')
                    var pid = $(this).attr('data-pid')
                    var termgrade = $(this).text()
                    var td = $(this)
                    $.ajax({
                        type: 'POST',
                        url: '/college/teacher/student/grades/save',
                        data: {
                            syid: $('#filter_sy').val(),
                            semid: $('#filter_semester').val(),
                            term: "fgremarks",
                            sectionid: sectionid,
                            termgrade: termgrade,
                            studid: studid,
                            courseid: courseid,
                            pid: pid,
                        },
                        success: function(data) {
                            $(td).removeClass('updated')
                            if ($('.updated[data-term="6"]').length == 0) {
                                Toast.fire({
                                    type: 'success',
                                    title: 'Saved Successfully!'
                                })
                                $('#save_grades').attr('disabled', 'disabled')
                                $('#save_grades').removeClass('btn-secondary')
                                $('#save_grades').addClass('btn-primary')
                                $('#save_grades').text('Save Grades')
                                $('#grade_submit').removeAttr('disabled')
                                var temp_students = all_subject.filter(x => x.schedid ==
                                    schedid)
                                get_grades(schedid, false, temp_students[0].students)
                                get_grades(schedid, false, temp_students[0].students)
                            }
                        }
                    })
                })
            }




            var school = @json($schoolinfo);



            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var all_subject = []
            get_subjects()

            var schedid = null;
            $(document).on('click', '.submit_grade', function() {
                var temp_button = $(this)
                temp_button.attr('disabled', 'disabled')
                var term = $(this).attr('data-term')
                $.ajax({
                    type: 'POST',
                    url: '/college/teacher/student/new/grades/submit',
                    data: {
                        schedid: schedid,
                        term: term,
                    },
                    success: function(data) {
                        if (data[0].status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: 'Submitted Successfully!'
                            })
                            temp_button.removeAttr('disabled')
                        } else {
                            temp_button.removeAttr('disabled')
                            Toast.fire({
                                type: 'danger',
                                title: 'Something went wrong!'
                            })
                        }
                    },
                    error: function() {
                        temp_button.removeAttr('disabled')
                        Toast.fire({
                            type: 'danger',
                            title: 'Something went wrong!'
                        })
                    }
                })
            })


            $(document).on('change', '#filter_sy , #filter_semester', function(data) {
                all_gradestatus = []
                datatable_3()
                all_subject = []
                getinputperiod()
                getgradesetup()
                datatable_1(data)
                get_subjects()
            })

            $(document).on('change', '#term', function(data) {
                datatable_3()
                datatable_1(data)
            })

            function get_subjects() {
                $.ajax({
                    type: 'GET',
                    url: '/college/teacher/schedule/get',
                    data: {
                        syid: $('#filter_sy').val(),
                        semid: $('#filter_semester').val(),
                        teacherid: 73
                    },
                    success: function(data) {
                        if (data.length == 0) {
                            Toast.fire({
                                type: 'warning',
                                title: 'No records Found!'
                            })
                        } else {
                            all_subject = data
                            all_students = data


                            // get_enrolled()
                            // grade_status()
                            // datatable_1(all_subject)
                            get_student_all(data)
                            get_selected_student(data)
                        }
                    }
                })
            }

            // function get_enrolled() {
            //     var syid = $('#filter_sy').val();
            //     var semid = $('#filter_semester').val();

            //     $.each(all_subject, function(a, b) {
            //         $.ajax({
            //             type: 'GET',
            //             url: `/college/teacher/student/grades/students/${syid}/${semid}/${b.schedid}`,
            //             success: function(data) {
            //                 datatable_1(data);
            //             }
            //         });
            //     });
            // }

            function get_student_all(data) {
                let requests = [];

                $.each(data, function(a, b) {
                    let request = $.ajax({
                        type: 'GET',
                        url: `/college/teacher/student-list-for-all/${$('#filter_sy').val()}/${$('#filter_semester').val()}/` +
                            b.schedid,
                        success: function(studentData) {
                            b.students = studentData;
                            all_subject = b


                        }
                    });
                    requests.push(request);
                });

                // Wait for all requests to complete
                $.when.apply($, requests).then(function() {
                    datatable_1(data); // Pass the full schedule data with students
                    all_subject = data;
                });
            }


            function get_selected_student(data) {
                let requests = [];

                $.each(data, function(a, b) {
                    let request = $.ajax({
                        type: 'GET',
                        url: `/college/teacher/student-list-for-all/${$('#filter_sy').val()}/${$('#filter_semester').val()}/` +
                            b.schedid,
                        success: function(studentData) {
                            b.students = studentData.students;
                            all_students = b

                            // console.log(b,
                            //     'data for each students'
                            // ); // Check if `courseabrv` is present here


                        }
                    });
                    requests.push(request);
                });

                // Wait for all requests to complete
                $.when.apply($, requests).then(function() {

                    all_students = data;

                    console.log(all_students, 'all students data'); // Check if `courseabrv` is present here
                });
            }

            var all_gradestatus = []

            function grade_status() {
                $.ajax({
                    type: 'GET',
                    url: '/college/teacher/student/grades/status/get',
                    data: {
                        syid: $('#filter_sy').val(),
                        semid: $('#filter_semester').val(),
                    },
                    success: function(data) {
                        all_gradestatus = data
                        datatable_3()
                    }
                })


            }
            ////////////////////
            $(document).on('click', '.view_students', function() {
                $('#modal_1').modal()
                temp_id = $(this).attr('data-id')
                var students = all_subject.filter(x => x.schedid == temp_id)
                datatable_2(students[0].students)
            })
            ///////////////////////////////////////////////////

            // $(document).on('click', '#view_grades_modal', function() {
            //     $('#modal_4').modal('show');

            //     // Ensure select checkboxes are checked
            //     setTimeout(function() {
            //         $('.select').prop('checked', true);
            //     }, 0);



            //     if (gradesetup.length == 0) {
            //         $('.term_holder[data-term=1]').remove()
            //         $('.term_holder[data-term=2]').remove()
            //         $('.term_holder[data-term=3]').remove()
            //         $('.term_holder[data-term=4]').remove()
            //     }

            //     $('#message_holder').text('')
            //     $('#save_grades').attr('hidden', 'hidden')

            //     temp_id = $(this).attr('data-id')
            //     schedid = temp_id



            //     $('.with_submission_info').remove()
            //     $('.submit_grade').attr('hidden', 'hidden')


            //     var students = all_subject.filter(x => x.schedid == temp_id)

            //     var selected_students = all_students.filter(x => x.schedid == temp_id)

            //     $('#subjectDesc').text(students[0].subjDesc)
            //     $('#subjectCode').text(students[0].subjCode)
            //     $('#collegeLevel').text(students[0].yearDesc)
            //     $('#section').text(students[0].sectionDesc)

            //     $('#subjectDescsSystem').text(students[0].subjDesc)
            //     $('#subjectCodesSystem').text(students[0].subjCode)
            //     $('#collegeLevelsSystem').text(students[0].yearDesc)
            //     $('#sectionsSystem').text(students[0].sectionDesc)






            //     var female = 0;
            //     var male = 0;
            //     var count = 1;
            //     var pid = students[0].subjectid

            //     var sectionid = students[0].sectionid

            //     if (students[0].students.length == 0) {
            //         Toast.fire({
            //             type: 'warning',
            //             title: 'No student Found!'
            //         })
            //         return false
            //     } else {
            //         $('#save_grades').removeAttr('hidden')
            //     }

            //     selected_students[0].students.sort((a, b) => {
            //         // Prioritize 'MALE' before 'FEMALE'
            //         if (a.gender === 'MALE' && b.gender === 'FEMALE') {
            //             return -1; // a comes before b
            //         } else if (a.gender === 'FEMALE' && b.gender === 'MALE') {
            //             return 1; // b comes before a
            //         } else {
            //             return a.gender.localeCompare(b
            //                 .gender); // Sort alphabetically within the same gender
            //         }
            //     });
            //     //////////////////////////////////////////////////////////
            //     // Separate students by gender
            //     var maleStudents = students.filter(student => student.gender === 'MALE')
            //     var femaleStudents = students.filter(student => student.gender === 'FEMALE')
            //     //////////////////////////////////////////////////////////

            //     $('#student_list_gradess').empty()

            //     var genderDisplayed = {
            //         MALE: false,
            //         FEMALE: false
            //     };



            //     $('.student_count').text(students[0].students.length)
            //     var colspan = 9;

            //     var disprelim = 0
            //     var dismidterm = 0
            //     var disprefi = 0
            //     var disfinal = 0


            //     if (gradesetup != null) {
            //         disprelim = gradesetup.prelim
            //         dismidterm = gradesetup.midterm
            //         disprefi = gradesetup.prefi
            //         disfinal = gradesetup.final
            //     }

            //     if (disprelim == 0) {
            //         $('#quarter_select option[value="1"]').attr('hidden', 'hidden')
            //         $('.term_holder[data-term=1]').remove()
            //         colspan -= 1
            //     }

            //     if (dismidterm == 0) {
            //         $('#quarter_select option[value="2"]').attr('hidden', 'hidden')
            //         $('.term_holder[data-term=2]').remove()
            //         colspan -= 1
            //     }

            //     if (disprefi == 0) {
            //         $('#quarter_select option[value="3"]').attr('hidden', 'hidden')
            //         $('.term_holder[data-term=3]').remove()
            //         colspan -= 1
            //     }

            //     if (disfinal == 0) {
            //         $('#quarter_select option[value="4"]').attr('hidden', 'hidden')
            //         $('.term_holder[data-term=4]').remove()
            //         colspan -= 1
            //     }

            //     $('#quarter_select').select2()

            //     $('#deadline_holder').empty()
            //     var deadlinetext =
            //         '<td colspan="2" class="text-right align-middle p-0"><b>Date Deadline</b></td>'
            //     if (disprelim == 1) {
            //         checkDateSetup = inputperiod.filter(x => x.term == 'prelim')
            //         if (checkDateSetup.length > 0) {
            //             var bg = 'bg-success'
            //             if (!checkDateSetup[0].isended) {
            //                 var bg = 'bg-danger'
            //             }
            //             deadlinetext += '<td class=" p-0 text-center ' + bg + '">' + checkDateSetup[0]
            //                 .endformat2 + '</td>'
            //         } else {
            //             deadlinetext += '<td class=" p-0 text-center align-middle">Not Set</td>'
            //         }
            //     }
            //     if (dismidterm == 1) {
            //         checkDateSetup = inputperiod.filter(x => x.term == 'midterm')
            //         if (checkDateSetup.length > 0) {
            //             var bg = 'bg-success'
            //             if (!checkDateSetup[0].isended) {
            //                 var bg = 'bg-danger'
            //             }
            //             deadlinetext += '<td class=" p-0 text-center ' + bg + '">' + checkDateSetup[0]
            //                 .endformat2 + '</td>'
            //         } else {
            //             deadlinetext += '<td class=" p-0 text-center align-middle">Not Set</td>'
            //         }
            //     }
            //     if (disprefi == 1) {
            //         checkDateSetup = inputperiod.filter(x => x.term == 'prefi')
            //         if (checkDateSetup.length > 0) {
            //             var bg = 'bg-success'
            //             if (!checkDateSetup[0].isended) {
            //                 var bg = 'bg-danger'
            //             }
            //             deadlinetext += '<td class=" p-0 text-center ' + bg + '">' + checkDateSetup[0]
            //                 .endformat2 + '</td>'
            //         } else {
            //             deadlinetext += '<td class=" p-0 text-center align-middle">Not Set</td>'
            //         }
            //     }
            //     if (disfinal == 1) {
            //         checkDateSetup = inputperiod.filter(x => x.term == 'finalterm')


            //         if (checkDateSetup.length > 0) {
            //             var bg = 'bg-success'
            //             if (!checkDateSetup[0].isended) {
            //                 var bg = 'bg-danger'
            //             }

            //             deadlinetext += '<td class=" p-0 text-center ' + bg + '">' + checkDateSetup[0]
            //                 .endformat2 + '</td>'
            //         } else {
            //             deadlinetext += '<td class=" p-0 text-center align-middle">Not Set</td>'
            //         }


            //         console.log(deadlinetext)

            //     }
            //     deadlinetext += '<td class=" p-0"></td><td class=" p-0"></td>'

            //     $('#deadline_holder').append(deadlinetext)

            //     $.each(selected_students[0].students, function(a, b) {

            //         $('#student_list_gradess').append(
            //             '<tr>' +
            //             '<td style="font-size:10px; padding: 5px;">' +
            //             '<div style="display: flex; align-items: center;">' +
            //             '<span style="margin-right: 10px;">' +
            //             b.lastname + ',' + b.firstname + ' ' + b.middlename +
            //             '</span>' +
            //             '<input checked="checked" type="checkbox" class="select2" data-studid="' +
            //             b.sid + '" data-id="' + b.id + '">' +
            //             '</div>' +
            //             '</td>' +
            //             '<td class="formative" data-component="F1" contenteditable="true"></td>' +
            //             '<td class="formative" data-component="F2" contenteditable="true"></td>' +
            //             '<td class="formative" data-component="F3" contenteditable="true"></td>' +
            //             '<td class="formative" data-component="F4" contenteditable="true"></td>' +
            //             '<td class="formative_total"></td>' +
            //             '<td class="formative_average"></td>' +
            //             '<td class="summative" data-component="S1" contenteditable="true"></td>' +
            //             '<td class="summative" data-component="S2" contenteditable="true"></td>' +
            //             '<td class="summative" data-component="S3" contenteditable="true"></td>' +
            //             '<td class="summative_total"></td>' +
            //             '<td class="summative_average"></td>' +
            //             '<td class="other_requirements_ge"></td>' +
            //             '<td class="other_requirements_percent"></td>' +
            //             '<td class="unit" data-component="UR1" contenteditable="true"></td>' +
            //             '<td class="unit" data-component="UR2" contenteditable="true"></td>' +
            //             '<td class="unit" data-component="UR3" contenteditable="true"></td>' +
            //             '<td class="unit" data-component="UR4" contenteditable="true"></td>' +
            //             '<td class="unit_requirements_total"></td>' +
            //             '<td class="unit_requirements_avg"></td>' +
            //             '<td class="terminal" data-component="TR1" contenteditable="true"></td>' +
            //             '<td class="terminal" data-component="TR2" contenteditable="true"></td>' +
            //             '<td class="terminal" data-component="TR3" contenteditable="true"></td>' +
            //             '<td class="terminal_total"></td>' +
            //             '<td class="terminal_average"></td>' +
            //             '<td class="performace_tasks_general_average"></td>' +
            //             '<td class="performace_tasks_percentage"></td>' +
            //             '<td class="term_exam" contenteditable="true"></td>' +
            //             '<td class="term_exam_total"></td>' +
            //             '<td class="term_exam_general_average"></td>' +
            //             '<td class="term_percentage"></td>' +
            //             '<td class="TOTAL_AVERAGE"></td>' +
            //             '</tr>'
            //         );

            //         //    '</td><td data-studid="' + b.studid +
            //         //     '" class="grade_submission_student text-center"></td>'

            //     })



            //     // $('#maleCount').text(maleCount);
            //     // $('#femaleCount').text(femaleCount);
            //     // $('#totalCount').text(selected_students[0].students.length)
            //     $('.grade_td').addClass('text-center')
            //     $('.grade_td').addClass('align-middle')
            //     get_grades(temp_id, true, students[0].students);

            // });

            ////////////////////////////////////
            $(document).on('click', '#view_grades_modal', function(data) {
                $('#modal_4').modal('show');

                // Ensure select checkboxes are checked
                setTimeout(function() {
                    $('.select').prop('checked', true);
                }, 0);



                $('#message_holder').text('');
                $('#save_grades').attr('hidden', 'hidden');
                temp_id = $(this).attr('data-id');
                schedid = temp_id;

                $('.with_submission_info').remove();
                $('.submit_grade').attr('hidden', 'hidden');

                var students = all_subject.filter(x => x.schedid == temp_id);
                var selected_students = all_students.filter(x => x.schedid == temp_id);

                $('#subjectNameHeader').text(students[0].subjDesc);
                $('#subjectCodeHeader').text(students[0].subjCode);

                $('#subjectDesc').text(students[0].subjDesc);
                $('#subjectCode').text(students[0].subjCode);
                $('#collegeLevel').text(students[0].yearDesc);
                $('#section').text(students[0].sectionDesc);

                $('#subjectDescsSystem').text(students[0].subjDesc);
                $('#subjectCodesSystem').text(students[0].subjCode);
                $('#collegeLevelsSystem').text(students[0].yearDesc);
                $('#sectionsSystem').text(students[0].sectionDesc);

                if (students[0].students.length == 0) {
                    Toast.fire({
                        type: 'warning',
                        title: 'No student Found!'

                    });
                    // $('#student_list_gradess').DataTable().clear().draw();
                    $('#student_list_gradess').show();
                    $('#student_list_gradess').empty().append(
                        '<tr><td colspan="100%" class="text-center">No data to show</td></tr>');
                    return false;
                } else {
                    $('#save_grades').removeAttr('hidden');
                    $('#student_list_gradess').show();
                }

                // Separate students by gender
                var maleStudents = selected_students[0].students.filter(student => student.gender ===
                    'MALE');
                var femaleStudents = selected_students[0].students.filter(student => student.gender ===
                    'FEMALE');

                // Sort students
                maleStudents.sort((a, b) => a.firstname.localeCompare(b.firstname));
                femaleStudents.sort((a, b) => a.firstname.localeCompare(b.firstname));

                $('#student_list_gradess').empty();

                // Add these functions after the existing code

                function sortStudents(students, order = 'asc') {
                    return students.sort((a, b) => {
                        if (order === 'asc') {
                            return a.firstname.localeCompare(b.firstname);
                        } else {
                            return b.firstname.localeCompare(a.firstname);
                        }
                    });
                }

                function updateStudentList(students, gender) {
                    let html = '';
                    $.each(students, function(a, b) {
                        html += '<tr>' +
                            '<td style="text-align: left; position: sticky; left: 0; z-index: 1; min-width: 150px; max-width: 150px; width: 350px; background-color: #fff;font-size:10px; padding: 5px;">' +
                            '<div style="display: flex; justify-content: space-between; align-items: center;">' +
                            '<span>' +
                            b.lastname + ',' + b.firstname + ' ' + b.middlename +
                            '</span>' +
                            '<input checked="checked" type="checkbox" class="select2 student-checkbox" data-studid="' +
                            b.sid + '" data-id="' + b.id + '">' +
                            '</div>' +
                            '</td>' +
                            '<td class="formative" data-component="F1" id="F1" contenteditable="true"></td>' +
                            '<td class="formative" data-component="F2" id="F2" contenteditable="true"></td>' +
                            '<td class="formative" data-component="F3" id="F3" contenteditable="true"></td>' +
                            '<td class="formative" data-component="F4" id="F4" contenteditable="true"></td>' +
                            '<td class="formative_total"></td>' +
                            '<td class="formative_average comp_3"></td>' +
                            '<td class="summative" data-component="S1" id="S1" contenteditable="true"></td>' +
                            '<td class="summative" data-component="S2" id="S2"contenteditable="true"></td>' +
                            '<td class="summative" data-component="S3" id="S3" contenteditable="true"></td>' +
                            '<td class="summative_total"></td>' +
                            '<td class="summative_average comp_3"></td>' +
                            '<td class="other_requirements_ge comp_4"></td>' +
                            '<td class="other_requirements_percent comp_5"></td>' +
                            '<td class="unit" data-component="UR1" id="UR1" contenteditable="true"></td>' +
                            '<td class="unit" data-component="UR2" id="UR2" contenteditable="true"></td>' +
                            '<td class="unit" data-component="UR3" id="UR3" contenteditable="true"></td>' +
                            '<td class="unit" data-component="UR4" id="UR4"contenteditable="true"></td>' +
                            '<td class="unit_requirements_total"></td>' +
                            '<td class="unit_requirements_avg comp_3"></td>' +
                            '<td class="terminal" data-component="TR1" id="TR1" contenteditable="true"></td>' +
                            '<td class="terminal" data-component="TR2" id="TR2"contenteditable="true"></td>' +
                            '<td class="terminal" data-component="TR3" id="TR3" contenteditable="true"></td>' +
                            '<td class="terminal_total"></td>' +
                            '<td class="terminal_average comp_3"></td>' +
                            '<td class="performace_tasks_general_average comp_4"></td>' +
                            '<td class="performace_tasks_percentage comp_5"></td>' +
                            '<td class="term_exam" data-component="term_exam" id="term_exam" contenteditable="true"></td>' +
                            '<td class="term_exam_total"></td>' +
                            '<td class="term_exam_general_average comp_4"></td>' +
                            '<td class="term_percentage comp_5"></td>' +
                            '<td class="TOTAL_AVERAGE" data-component="total_ave" id="total_ave" style="color:green; background-color: #aad08e;"></td>' +
                            '</tr>';
                    });
                    $(`#student_list_gradess tr:has(td:contains(${gender}))`).nextUntil(
                        'tr:has(td:contains(Female))').remove();
                    $(`#student_list_gradess tr:has(td:contains(${gender}))`).after(html);
                }

                $(document).on('click', '#male_sort', function() {
                    let order = $(this).hasClass('sort-asc') ? 'desc' : 'asc';
                    $(this).toggleClass('sort-asc sort-desc');
                    maleStudents = sortStudents(maleStudents, order);
                    updateStudentList(maleStudents, 'Male');
                });

                $(document).on('click', '#female_sort', function() {
                    let order = $(this).hasClass('sort-asc') ? 'desc' : 'asc';
                    $(this).toggleClass('sort-asc sort-desc');
                    femaleStudents = sortStudents(femaleStudents, order);
                    updateStudentList(femaleStudents, 'Female');
                });

                // Add headers for male and female students
                if (maleStudents.length > 0) {
                    $('#student_list_gradess').append(
                        '<tr class="bg-secondary male_section" id="male_section">' +
                        '<td class="text-left font-weight-bold" style="text-align: left; position: sticky; left: 0;right: 0; z-index: 1;color:black; min-width: 150px; max-width: 150px; width: 150px; background-color: #fff;">' +
                        'Male' +

                        '<input checked="checked" type="checkbox" id="malecheckbox" style="float:right;margin-right:-3px;">' +
                        '<span class="sort-icon sort-asc" style="cursor:pointer;float:right;margin-right:15px;" id="male_sort">' +
                        '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-arrow-down-up" viewBox="0 0 16 16">' +
                        '<path fill-rule="evenodd" d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5" />' +
                        '</svg>' +
                        // '<span class="sort-icon sort-desc" style="margin-left:70px;cursor:pointer; display: none;" id="male_sort">' +
                        // '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-arrow-down-up" viewBox="0 0 16 16">' +
                        // '<path fill-rule="evenodd" d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5" />' +
                        // '</svg>' +
                        '</span>' +
                        '</td>' +
                        '<td colspan="31" style="background-color: #f8f9fa;">' +
                        '</td>' +
                        '</tr>'
                    );
                    updateStudentList(maleStudents, 'Male');


                }

                if (femaleStudents.length > 0) {
                    $('#student_list_gradess').append(
                        '<tr class="bg-secondary female_section" id="female_section">' +
                        '<td class="text-left font-weight-bold" style="text-align: left;right: 0; position: sticky; left: 0; z-index: 1;color:black; min-width: 150px; max-width: 150px; width: 150px; background-color: #fff;">' +
                        'Female' +

                        '<input checked="checked" type="checkbox" id="femalecheckbox" style="float:right;margin-right:-3px;">' +
                        '<span class="sort-icon sort-asc" style="cursor:pointer;float:right;margin-right:15px;" id="female_sort">' +
                        '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-arrow-down-up" viewBox="0 0 16 16">' +
                        '<path fill-rule="evenodd" d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5" />' +
                        '</svg>' +
                        // '<span class="sort-icon sort-desc" style="margin-left:70px;cursor:pointer;display: none;" id="female_sort">' +
                        // '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-arrow-down-up" viewBox="0 0 16 16">' +
                        // '<path fill-rule="evenodd" d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5" />' +
                        // '</svg>' +
                        '</span>' +
                        // style="margin-left:20px;"
                        '</td>' +
                        '<td colspan="31" style="background-color: #f8f9fa;">' +
                        '</td>' +
                        '</tr>'
                    );
                    updateStudentList(femaleStudents, 'Female');

                }

                $('.grade_td').addClass('text-center');
                $('.grade_td').addClass('align-middle');

                $(document).on('input', '[contenteditable="true"]', function() {
                    var regex = /^[0-9]*$/; // Only allow numeric input
                    var text = $(this).text();

                    if (!regex.test(text)) {
                        $(this).text(text.replace(/[^0-9]/g, '')); // Remove non-numeric characters
                    }
                });




                $(document).on('input', '[contenteditable="true"]', function() {
                    var $row = $(this).closest('tr');
                    var F1Score = parseFloat($row.find('[data-component="F1"]').text()) || 0;
                    var F2Score = parseFloat($row.find('[data-component="F2"]').text()) || 0;
                    var F3Score = parseFloat($row.find('[data-component="F3"]').text()) || 0;
                    var F4Score = parseFloat($row.find('[data-component="F4"]').text()) || 0;

                    var S1Score = parseFloat($row.find('[data-component="S1"]').text()) || 0;
                    var S2Score = parseFloat($row.find('[data-component="S2"]').text()) || 0;
                    var S3Score = parseFloat($row.find('[data-component="S3"]').text()) || 0;


                    var uR1Score = parseFloat($row.find('[data-component="UR1"]').text()) || 0;
                    var uR2Score = parseFloat($row.find('[data-component="UR2"]').text()) || 0;
                    var uR3Score = parseFloat($row.find('[data-component="UR3"]').text()) || 0;
                    var uR4Score = parseFloat($row.find('[data-component="UR4"]').text()) || 0;

                    var TR1Score = parseFloat($row.find('[data-component="TR1"]').text()) || 0;
                    var TR2Score = parseFloat($row.find('[data-component="TR2"]').text()) || 0;
                    var TR3Score = parseFloat($row.find('[data-component="TR3"]').text()) || 0;

                    var term_exam = parseFloat($row.find('[data-component="term_exam"]').text()) ||
                        0;

                    var rowId = $row.attr(
                        'data-row-id'); // Assuming each row has a unique data-row-id

                    // Send data to the server for calculation
                    $.ajax({
                        type: 'GET',
                        url: '/getcalculation/formativeHighestScore', // Adjust URL as needed
                        data: {
                            rowId: rowId,
                            F1Score: F1Score,
                            F2Score: F2Score,
                            F3Score: F3Score,
                            F4Score: F4Score,

                            S1Score: S1Score,
                            S2Score: S2Score,
                            S3Score: S3Score,

                            uR1Score: uR1Score,
                            uR2Score: uR2Score,
                            uR3Score: uR3Score,
                            uR4Score: uR4Score,

                            TR1Score: TR1Score,
                            TR2Score: TR2Score,
                            TR3Score: TR3Score,
                            term_exam: term_exam,

                            F1HighestScore: $('#F1HighestScore').text(),
                            F2HighestScore: $('#F2HighestScore').text(),
                            F3HighestScore: $('#F3HighestScore').text(),
                            F4HighestScore: $('#F4HighestScore').text(),

                            S1HighestScore: $('#S1HighestScore').text(),
                            S2HighestScore: $('#S2HighestScore').text(),
                            S3HighestScore: $('#S3HighestScore').text(),

                            UR1HighestScore: $('#UR1HighestScore').text(),
                            UR2HighestScore: $('#UR2HighestScore').text(),
                            UR3HighestScore: $('#UR3HighestScore').text(),
                            UR4HighestScore: $('#UR4HighestScore').text(),

                            TR1HighestScore: $('#TR1HighestScore').text(),
                            TR2HighestScore: $('#TR2HighestScore').text(),
                            TR3HighestScore: $('#TR3HighestScore').text(),

                            term_examHighestScore: $('#term_examHighestScore').text()

                        },
                        success: function(response) {
                            $row.find('.formative_total').text(parseFloat(response
                                .totalScore).toFixed(2));
                            $row.find('.formative_average').text(parseFloat(response
                                .generalAverageScore).toFixed(2));
                            $row.find('.summative_total').text(parseFloat(response
                                .totalScore2).toFixed(2));
                            $row.find('.summative_average').text(parseFloat(response
                                .generalAverageScore2).toFixed(2));
                            $row.find('.other_requirements_ge').text(parseFloat(response
                                .other_requirements_geHighestScoreGE2).toFixed(
                                2));
                            $row.find('.other_requirements_percent').text(parseFloat(
                                    response
                                    .other_requirements_geHighestScorePercent2)
                                .toFixed(2));

                            $row.find('.unit_requirements_total').text(parseFloat(
                                    response.totalUnitRequirementHighestScore)
                                .toFixed(2));
                            $row.find('.unit_requirements_avg').text(parseFloat(response
                                .UnitRequirementgeneralAverageScore).toFixed(2));
                            $row.find('.terminal_total').text(parseFloat(response
                                .totalTerminalRequirementHighestScore).toFixed(
                                2));
                            $row.find('.terminal_average').text(parseFloat(response
                                    .TerminalRequirementgeneralAverageScore)
                                .toFixed(2));
                            $row.find('.performace_tasks_general_average')
                                .text(parseFloat(response
                                    .performance_tasks_geHighestScoreGE2).toFixed(
                                    2));
                            $row.find('.performace_tasks_percentage').text(
                                parseFloat(response
                                    .performance_tasks_geHighestScorePercent2)
                                .toFixed(2));

                            $row.find('.term_exam_total').text(parseFloat(response
                                    .totalExamScore2)
                                .toFixed(2));
                            $row.find('.term_exam_general_average')
                                .text(parseFloat(response
                                    .term_exam_generalAverageHighestScore2).toFixed(
                                    2));
                            $row.find('.term_percentage').text(
                                parseFloat(response
                                    .term_exam_percentage2)
                                .toFixed(2));

                            $row.find('.TOTAL_AVERAGE').text(
                                parseFloat(response
                                    .total_scorepercentage)
                                .toFixed(2));



                            // Update highest scores
                            $('.formative_totalHighestScore').text(response
                                .totalHighestScore1);
                            $('.formative_averageHighestScore').text(response
                                .generalAverageHighestScore1);

                            $('.summative_totalHighestScore').text(response
                                .totalHighestScore2);
                            $('.summative_averageHighestScore').text(response
                                .generalAverageHighestScore2);

                            $('.other_requirements_geHighestScore').text(response
                                .other_requirements_geHighestScoreGE);
                            $('.other_requirements_percentHighestScore').text(response
                                .other_requirements_geHighestScorePercent);

                            $('.unit_requirements_totalHighestScore').text(response
                                .maxUnitRequirementScore);
                            $('.unit_requirements_avgHighestScore').text(response
                                .percentageUnitRequirement);

                            $('.terminal_totalHighestScore').text(response
                                .maxTerminalRequirementScore);
                            $('.terminal_averageHighestScore').text(response
                                .percentageTerminalRequirement);

                            $('.performace_tasks_general_averageHighestScore').text(
                                response
                                .performance_tasks_geHighestScoreGE);
                            $('.performace_tasks_percentageHighestScore').text(response
                                .performance_tasks_geHighestScorePercent);


                            $('.term_exam_totalHighestScore').text(response
                                .totalExamScore);
                            $('.term_exam_general_averageHighestScore').text(response
                                .term_exam_generalAverageHighestScore);
                            $('.term_percentageHighestScore').text(response
                                .term_exam_percentage);

                            $('.TOTAL_AVERAGEHighestScore').text(response
                                .total_highest_scorepercentage);




                        },
                        error: function(xhr) {
                            console.error('An error occurred:', xhr.responseText);
                        }
                    });
                });


                get_grades(temp_id, true, students[0].students);

            })
            ///////////////////////////////////////////////




            var all_grades = []
            var dean = @json($dean)

            $('#printable_dean').select2({
                'data': dean,
                'placeholder': 'Select Dean'
            })


            $(document).on('click', '#print_grades_to_modal', function() {
                $('#dean_holder_modal').modal()
            })


            $(document).on('click', '#print_grades', function() {
                print_grades()
            })

            function print_grades() {

                var pid = []
                var sectionid = []
                var students = all_subject.filter(x => x.schedid == schedid)[0].students
                var temp_pid = [...new Map(students.map(item => [item['pid'], item])).values()]
                var temp_sectionid = [...new Map(students.map(item => [item['sectionid'], item])).values()]


                $.each(temp_pid, function(a, b) {
                    pid.push(b.pid)
                })
                $.each(temp_sectionid, function(a, b) {
                    sectionid.push(b.sectionid)
                })

                var temp_subjid = temp_pid[0].pid

                var syid = $('#filter_sy').val()
                var semid = $('#filter_semester').val()
                var pid = pid
                var sectionid = sectionid
                var dean = $('#printable_dean').val()

                window.open('/college/teacher/student/grades/print?&syid=' + syid + '&semid=' + semid + '&pid=' +
                    pid + '&sectionid=' + sectionid + '&schedid=' + schedid + '&subjid=' + temp_subjid +
                    '&dean=' + dean, '_blank');

            }

            // function get_grades(schedid, prompt = true, students) {
            //     // console.log('HELLO');
            //     console.log(schedid, 'schedid ni')
            //     console.log(students, 'students ni')


            //     // console.log(Array.isArray(students)); // Should log true if students is an array

            //     // var pid = []
            //     // console.log(pid, "pid ni")
            //     // var sectionid = []
            //     // console.log(sectionid, "sectionid ni")
            //     // console.log('STUDENTS', students);
            //     // var temp_pid = [...new Map(students.students.map(item => [item['pid'], item])).values()]
            //     // var temp_sectionid = [...new Map(students.students.map(item => [item['sectionid'], item])).values()]

            //     // $.each(temp_pid, function(a, b) {
            //     //     pid.push(b.pid)

            //     // })
            //     // $.each(temp_sectionid, function(a, b) {
            //     //     sectionid.push(b.sectionid)
            //     // })

            //     var pid = [];
            //     var sectionid = [];

            //     // Process the students array
            //     students.forEach(student => {
            //         if (student.pid && !pid.includes(student.pid)) {
            //             pid.push(student.pid);
            //         }
            //         if (student.sectionid && !sectionid.includes(student.sectionid)) {
            //             sectionid.push(student.sectionid);
            //         }
            //     });


            //     // }

            //     $('.p_count').text(0)
            //     $('.f_count').text(0)
            //     $('.ng_count').text(0)

            //     $('.drop_count').text(0)
            //     $('.inc_count').text(0)
            //     $('.pen_count').text(0)
            //     $('.sub_count').text(0)
            //     $('.app_count').text(0)

            //     $.ajax({
            //         type: 'GET',
            //         url: '/college/teacher/student/grades/get',
            //         data: {

            //             syid: $('#filter_sy').val(),
            //             semid: $('#filter_semester').val(),
            //             pid: pid,
            //             sectionid: sectionid
            //         },
            //         success: function(data) {
            //             console.log(data);
            //             $('.grade_td').addClass('input_grades')
            //             all_grades = data

            //             if (data.length == 0) {
            //                 // Toast.fire({
            //                 //       type: 'warning',
            //                 //       title: 'No grades found!'
            //                 // })
            //                 // $('#message_holder').text('No grades found. Please input student grades.')
            //             } else {

            //                 $('.drop_count[data-stat="1"]').text(data.filter(x => x.prelemstatus == 9)
            //                     .length)
            //                 $('.drop_count[data-stat="2"]').text(data.filter(x => x.midtermstatus == 9)
            //                     .length)
            //                 $('.drop_count[data-stat="3"]').text(data.filter(x => x.prefistatus == 9)
            //                     .length)
            //                 $('.drop_count[data-stat="4"]').text(data.filter(x => x.finalstatus == 9)
            //                     .length)

            //                 $('.inc_count[data-stat="1"]').text(data.filter(x => x.prelemstatus == 8)
            //                     .length)
            //                 $('.inc_count[data-stat="2"]').text(data.filter(x => x.midtermstatus == 8)
            //                     .length)
            //                 $('.inc_count[data-stat="3"]').text(data.filter(x => x.prefistatus == 8)
            //                     .length)
            //                 $('.inc_count[data-stat="4"]').text(data.filter(x => x.finalstatus == 8)
            //                     .length)

            //                 $('.pen_count[data-stat="1"]').text(data.filter(x => x.prelemstatus == 3)
            //                     .length)
            //                 $('.pen_count[data-stat="2"]').text(data.filter(x => x.midtermstatus == 3)
            //                     .length)
            //                 $('.pen_count[data-stat="3"]').text(data.filter(x => x.prefistatus == 3)
            //                     .length)
            //                 $('.pen_count[data-stat="4"]').text(data.filter(x => x.finalstatus == 3)
            //                     .length)

            //                 $('.sub_count[data-stat="1"]').text(data.filter(x => x.prelemstatus == 1)
            //                     .length)
            //                 $('.sub_count[data-stat="2"]').text(data.filter(x => x.midtermstatus == 1)
            //                     .length)
            //                 $('.sub_count[data-stat="3"]').text(data.filter(x => x.prefistatus == 1)
            //                     .length)
            //                 $('.sub_count[data-stat="4"]').text(data.filter(x => x.finalstatus == 1)
            //                     .length)

            //                 $('.app_count[data-stat="1"]').text(data.filter(x => x.prelemstatus == 2 ||
            //                     x.prelemstatus == 7).length)
            //                 $('.app_count[data-stat="2"]').text(data.filter(x => x.midtermstatus == 2 ||
            //                     x.midtermstatus == 7).length)
            //                 $('.app_count[data-stat="3"]').text(data.filter(x => x.prefistatus == 2 || x
            //                     .prefistatus == 7).length)
            //                 $('.app_count[data-stat="4"]').text(data.filter(x => x.finalstatus == 2 || x
            //                     .finalstatus == 7).length)


            //                 $('.p_count[data-stat="1"]').text(data.filter(x => x.prelemgrade != null &&
            //                     x.prelemgrade >= 75).length)
            //                 $('.p_count[data-stat="2"]').text(data.filter(x => x.midtermgrade != null &&
            //                     x.midtermgrade >= 75).length)
            //                 $('.p_count[data-stat="3"]').text(data.filter(x => x.prefigrade != null && x
            //                     .prefigrade >= 75).length)
            //                 $('.p_count[data-stat="4"]').text(data.filter(x => x.finalgrade != null && x
            //                     .finalgrade >= 75).length)

            //                 $('.f_count[data-stat="1"]').text(data.filter(x => x.prelemgrade != null &&
            //                     x.prelemgrade < 75).length)
            //                 $('.f_count[data-stat="2"]').text(data.filter(x => x.midtermgrade != null &&
            //                     x.midtermgrade < 75).length)
            //                 $('.f_count[data-stat="3"]').text(data.filter(x => x.prefigrade != null && x
            //                     .prefigrade < 75).length)
            //                 $('.f_count[data-stat="4"]').text(data.filter(x => x.finalgrade != null && x
            //                     .finalgrade < 75).length)

            //                 if (school == 'spct'.toUpperCase()) {
            //                     $('.ng_count[data-stat="2"]').text(parseInt($(
            //                         '.student_count[data-stat="2"]').text()) - (parseInt($(
            //                         '.p_count[data-stat="2"]').text()) + parseInt($(
            //                         '.f_count[data-stat="2"]').text())))
            //                     $('.ng_count[data-stat="3"]').text(parseInt($(
            //                         '.student_count[data-stat="2"]').text()) - (parseInt($(
            //                         '.p_count[data-stat="3"]').text()) + parseInt($(
            //                         '.f_count[data-stat="3"]').text())))
            //                     $('.ng_count[data-stat="4"]').text(parseInt($(
            //                         '.student_count[data-stat="2"]').text()) - (parseInt($(
            //                         '.p_count[data-stat="4"]').text()) + parseInt($(
            //                         '.f_count[data-stat="4"]').text())))
            //                 } else {
            //                     $('.ng_count[data-stat="1"]').text(parseInt($(
            //                         '.student_count[data-stat="1"]').text()) - (parseInt($(
            //                         '.p_count[data-stat="1"]').text()) + parseInt($(
            //                         '.f_count[data-stat="1"]').text())))
            //                     $('.ng_count[data-stat="2"]').text(parseInt($(
            //                         '.student_count[data-stat="1"]').text()) - (parseInt($(
            //                         '.p_count[data-stat="2"]').text()) + parseInt($(
            //                         '.f_count[data-stat="2"]').text())))
            //                     $('.ng_count[data-stat="3"]').text(parseInt($(
            //                         '.student_count[data-stat="1"]').text()) - (parseInt($(
            //                         '.p_count[data-stat="3"]').text()) + parseInt($(
            //                         '.f_count[data-stat="3"]').text())))
            //                     $('.ng_count[data-stat="4"]').text(parseInt($(
            //                         '.student_count[data-stat="1"]').text()) - (parseInt($(
            //                         '.p_count[data-stat="4"]').text()) + parseInt($(
            //                         '.f_count[data-stat="4"]').text())))
            //                 }

            //                 plot_subject_grades(data)
            //                 if (prompt) {
            //                     Toast.fire({
            //                         type: 'success',
            //                         title: 'Grades found!'
            //                     })
            //                     $('#message_holder').text('Grades found.')
            //                 }


            //             }

            //         },
            //         error: function() {
            //             Toast.fire({
            //                 type: 'error',
            //                 title: 'Something went wrong!'
            //             })
            //             $('#message_holder').text('Unable to load grades.')
            //         }
            //     })
            // }

            function get_grades(schedid, prompt = true, students) {

                // var sched = all_subject.filter(x=>x.schedid == schedid)
                // var pid = sched[0].pid
                // var sectionid = sched[0].sectionID

                // if(school == 'gbbc'.toUpperCase()){
                var pid = []
                var sectionid = []
                var temp_pid = [...new Map(students.map(item => [item['pid'], item])).values()]
                var temp_sectionid = [...new Map(students.map(item => [item['sectionid'], item])).values()]
                $.each(temp_pid, function(a, b) {
                    pid.push(b.pid)
                })
                $.each(temp_sectionid, function(a, b) {
                    sectionid.push(b.sectionid)
                })
                // }
                var failedCount = 0;
                var passedCount = 0;

                $('.p_count').text(0)
                $('.f_count').text(0)
                $('.ng_count').text(0)

                $('.drop_count').text(0)
                $('.inc_count').text(0)
                $('.pen_count').text(0)
                $('.sub_count').text(0)
                $('.app_count').text(0)

                $.ajax({
                    type: 'GET',
                    url: '/college/teacher/student/new/grades/get',
                    data: {

                        syid: $('#filter_sy').val(),
                        semid: $('#filter_semester').val(),
                        pid: pid,
                        sectionid: sectionid
                    },
                    success: function(data) {

                        $('.grade_td').addClass('input_grades')
                        all_grades = data

                        if (data.length == 0) {
                            // Toast.fire({
                            //       type: 'warning',
                            //       title: 'No grades found!'
                            // })
                            // $('#message_holder').text('No grades found. Please input student grades.')
                        } else {

                            $('.drop_count[data-stat="1"]').text(data.filter(x => x.prelemstatus == 9)
                                .length)
                            $('.drop_count[data-stat="2"]').text(data.filter(x => x.midtermstatus == 9)
                                .length)
                            $('.drop_count[data-stat="3"]').text(data.filter(x => x.prefistatus == 9)
                                .length)
                            $('.drop_count[data-stat="4"]').text(data.filter(x => x.finalstatus == 9)
                                .length)

                            $('.inc_count[data-stat="1"]').text(data.filter(x => x.prelemstatus == 8)
                                .length)
                            $('.inc_count[data-stat="2"]').text(data.filter(x => x.midtermstatus == 8)
                                .length)
                            $('.inc_count[data-stat="3"]').text(data.filter(x => x.prefistatus == 8)
                                .length)
                            $('.inc_count[data-stat="4"]').text(data.filter(x => x.finalstatus == 8)
                                .length)

                            $('.pen_count[data-stat="1"]').text(data.filter(x => x.prelemstatus == 3)
                                .length)
                            $('.pen_count[data-stat="2"]').text(data.filter(x => x.midtermstatus == 3)
                                .length)
                            $('.pen_count[data-stat="3"]').text(data.filter(x => x.prefistatus == 3)
                                .length)
                            $('.pen_count[data-stat="4"]').text(data.filter(x => x.finalstatus == 3)
                                .length)

                            $('.sub_count[data-stat="1"]').text(data.filter(x => x.prelemstatus == 1)
                                .length)
                            $('.sub_count[data-stat="2"]').text(data.filter(x => x.midtermstatus == 1)
                                .length)
                            $('.sub_count[data-stat="3"]').text(data.filter(x => x.prefistatus == 1)
                                .length)
                            $('.sub_count[data-stat="4"]').text(data.filter(x => x.finalstatus == 1)
                                .length)

                            $('.app_count[data-stat="1"]').text(data.filter(x => x.prelemstatus == 2 ||
                                x.prelemstatus == 7).length)
                            $('.app_count[data-stat="2"]').text(data.filter(x => x.midtermstatus == 2 ||
                                x.midtermstatus == 7).length)
                            $('.app_count[data-stat="3"]').text(data.filter(x => x.prefistatus == 2 || x
                                .prefistatus == 7).length)
                            $('.app_count[data-stat="4"]').text(data.filter(x => x.finalstatus == 2 || x
                                .finalstatus == 7).length)


                            $('.p_count[data-stat="1"]').text(data.filter(x => x.prelemgrade != null &&
                                x.prelemgrade >= 75).length)
                            $('.p_count[data-stat="2"]').text(data.filter(x => x.midtermgrade != null &&
                                x.midtermgrade >= 75).length)
                            $('.p_count[data-stat="3"]').text(data.filter(x => x.prefigrade != null && x
                                .prefigrade >= 75).length)
                            $('.p_count[data-stat="4"]').text(data.filter(x => x.finalgrade != null && x
                                .finalgrade >= 75).length)

                            $('.f_count[data-stat="1"]').text(data.filter(x => x.prelemgrade != null &&
                                x.prelemgrade < 75).length)
                            $('.f_count[data-stat="2"]').text(data.filter(x => x.midtermgrade != null &&
                                x.midtermgrade < 75).length)
                            $('.f_count[data-stat="3"]').text(data.filter(x => x.prefigrade != null && x
                                .prefigrade < 75).length)
                            $('.f_count[data-stat="4"]').text(data.filter(x => x.finalgrade != null && x
                                .finalgrade < 75).length)

                            if (school == 'spct'.toUpperCase()) {
                                $('.ng_count[data-stat="2"]').text(parseInt($(
                                    '.student_count[data-stat="2"]').text()) - (parseInt($(
                                    '.p_count[data-stat="2"]').text()) + parseInt($(
                                    '.f_count[data-stat="2"]').text())))
                                $('.ng_count[data-stat="3"]').text(parseInt($(
                                    '.student_count[data-stat="2"]').text()) - (parseInt($(
                                    '.p_count[data-stat="3"]').text()) + parseInt($(
                                    '.f_count[data-stat="3"]').text())))
                                $('.ng_count[data-stat="4"]').text(parseInt($(
                                    '.student_count[data-stat="2"]').text()) - (parseInt($(
                                    '.p_count[data-stat="4"]').text()) + parseInt($(
                                    '.f_count[data-stat="4"]').text())))
                            } else {
                                $('.ng_count[data-stat="1"]').text(parseInt($(
                                    '.student_count[data-stat="1"]').text()) - (parseInt($(
                                    '.p_count[data-stat="1"]').text()) + parseInt($(
                                    '.f_count[data-stat="1"]').text())))
                                $('.ng_count[data-stat="2"]').text(parseInt($(
                                    '.student_count[data-stat="1"]').text()) - (parseInt($(
                                    '.p_count[data-stat="2"]').text()) + parseInt($(
                                    '.f_count[data-stat="2"]').text())))
                                $('.ng_count[data-stat="3"]').text(parseInt($(
                                    '.student_count[data-stat="1"]').text()) - (parseInt($(
                                    '.p_count[data-stat="3"]').text()) + parseInt($(
                                    '.f_count[data-stat="3"]').text())))
                                $('.ng_count[data-stat="4"]').text(parseInt($(
                                    '.student_count[data-stat="1"]').text()) - (parseInt($(
                                    '.p_count[data-stat="4"]').text()) + parseInt($(
                                    '.f_count[data-stat="4"]').text())))
                            }

                            plot_subject_grades(data)
                            if (prompt) {
                                Toast.fire({
                                    type: 'success',
                                    title: 'Grades found!'
                                })
                                $('#message_holder').text('Grades found.')
                            }


                        }

                    },
                    error: function() {
                        Toast.fire({
                            type: 'error',
                            title: 'Something went wrong!'
                        })
                        $('#message_holder').text('Unable to load grades.')
                    }
                })
            }



            // function getUniqueValues(arr, key) {
            //     if (!Array.isArray(arr)) {
            //         console.error('Expected an array but received:', arr);
            //         return [];
            //     }

            //     const seen = new Set();
            //     const uniqueArr = [];

            //     for (const item of arr) {
            //         if (item && item[key] !== undefined && !seen.has(item[key])) {
            //             seen.add(item[key]);
            //             uniqueArr.push(item);
            //         }
            //     }

            //     return uniqueArr;
            // }

            // function get_grades(schedid, prompt = true, response) {
            //     console.log(schedid, 'schedid ni');
            //     console.log(response, 'response ni');

            //     const students = response.students;

            //     if (!Array.isArray(students)) {
            //         console.error('Expected students to be an array but received:', students);
            //         return;
            //     }

            //     const uniquePids = getUniqueValues(students, 'pid').map(item => item.pid);
            //     const uniqueSectionIds = getUniqueValues(students, 'sectionid').map(item => item.sectionid);

            //     console.log(uniquePids, "pid ni");
            //     console.log(uniqueSectionIds, "sectionid ni");

            //     $('.p_count, .f_count, .ng_count, .drop_count, .inc_count, .pen_count, .sub_count, .app_count')
            //         .text(0);

            //     $.ajax({
            //         type: 'GET',
            //         url: '/college/teacher/student/grades/get',
            //         data: {
            //             syid: $('#filter_sy').val(),
            //             semid: $('#filter_semester').val(),
            //             pid: uniquePids,
            //             sectionid: uniqueSectionIds
            //         },
            //         success: function(data) {
            //             $('.grade_td').addClass('input_grades');
            //             const all_grades = data; // Make sure this variable is used appropriately

            //             if (data.length === 0) {
            //                 if (prompt) {
            //                     Toast.fire({
            //                         type: 'warning',
            //                         title: 'No grades found!'
            //                     });
            //                     $('#message_holder').text(
            //                         'No grades found. Please input student grades.');
            //                 }
            //                 return;
            //             }

            //             const updateCounts = (statusField, className) => {
            //                 [1, 2, 3, 4].forEach(stat => {
            //                     $(`.${className}[data-stat="${stat}"]`).text(data.filter(
            //                         x => x[statusField] === stat).length);
            //                 });
            //             };

            //             updateCounts('prelemstatus', 'drop_count');
            //             updateCounts('midtermstatus', 'inc_count');
            //             updateCounts('prefistatus', 'pen_count');
            //             updateCounts('finalstatus', 'sub_count');

            //             const updateGradeCounts = (gradeField, className) => {
            //                 [1, 2, 3, 4].forEach(stat => {
            //                     $(`.${className}[data-stat="${stat}"]`).text(data.filter(
            //                         x => x[gradeField] != null && x[gradeField] >=
            //                         75).length);
            //                 });
            //             };

            //             updateGradeCounts('prelemgrade', 'p_count');
            //             updateGradeCounts('midtermgrade', 'f_count');
            //             updateGradeCounts('prefigrade', 'p_count');
            //             updateGradeCounts('finalgrade', 'f_count');

            //             $('.app_count[data-stat="1"]').text(data.filter(x => [2, 7].includes(x
            //                 .prelemstatus)).length);
            //             $('.app_count[data-stat="2"]').text(data.filter(x => [2, 7].includes(x
            //                 .midtermstatus)).length);
            //             $('.app_count[data-stat="3"]').text(data.filter(x => [2, 7].includes(x
            //                 .prefistatus)).length);
            //             $('.app_count[data-stat="4"]').text(data.filter(x => [2, 7].includes(x
            //                 .finalstatus)).length);

            //             // Update 'ng_count' based on school type
            //             const school = 'spct'; // Ensure this value is correctly set
            //             [1, 2, 3, 4].forEach(stat => {
            //                 const studentCount = parseInt($(
            //                     `.student_count[data-stat="${stat}"]`).text(), 10);
            //                 const passedCount = parseInt($(`.p_count[data-stat="${stat}"]`)
            //                     .text(), 10);
            //                 const failedCount = parseInt($(`.f_count[data-stat="${stat}"]`)
            //                     .text(), 10);
            //                 const ngCount = studentCount - (passedCount + failedCount);

            //                 $(`.ng_count[data-stat="${stat}"]`).text(ngCount);
            //             });

            //             plot_subject_grades(data);

            //             if (prompt) {
            //                 Toast.fire({
            //                     type: 'success',
            //                     title: 'Grades found!'
            //                 });
            //                 $('#message_holder').text('Grades found.');
            //             }
            //         },
            //         error: function() {
            //             Toast.fire({
            //                 type: 'error',
            //                 title: 'Something went wrong!'
            //             });
            //             $('#message_holder').text('Unable to load grades.');
            //         }
            //     });
            // }






            // function plot_subject_grades(data) {
            //     $.each(data, function(a, b) {

            //         var q1status = 'input_grades'
            //         var q2status = 'input_grades'
            //         var q3status = 'input_grades'
            //         var q4status = 'input_grades'

            //         $('.input_grades[data-studid="' + b.studid + '"][data-term="1"]').text(b.prelemgrade !=
            //             null ? b.prelemgrade : '')
            //         $('.input_grades[data-studid="' + b.studid + '"][data-term="2"]').text(b.midtermgrade !=
            //             null ? b.midtermgrade : '')
            //         $('.input_grades[data-studid="' + b.studid + '"][data-term="3"]').text(b.prefigrade !=
            //             null ? b.prefigrade : '')
            //         $('.input_grades[data-studid="' + b.studid + '"][data-term="4"]').text(b.finalgrade !=
            //             null ? b.finalgrade : '')


            //         $('.input_grades[data-studid="' + b.studid + '"][data-term="1"]').attr('data-id', b.id)
            //         $('.input_grades[data-studid="' + b.studid + '"][data-term="2"]').attr('data-id', b.id)
            //         $('.input_grades[data-studid="' + b.studid + '"][data-term="3"]').attr('data-id', b.id)
            //         $('.input_grades[data-studid="' + b.studid + '"][data-term="4"]').attr('data-id', b.id)

            //         $('.input_grades[data-studid="' + b.studid + '"][data-term="1"]').attr('data-status', b
            //             .prelemstatus)
            //         $('.input_grades[data-studid="' + b.studid + '"][data-term="2"]').attr('data-status', b
            //             .midtermstatus)
            //         $('.input_grades[data-studid="' + b.studid + '"][data-term="3"]').attr('data-status', b
            //             .prefistatus)
            //         $('.input_grades[data-studid="' + b.studid + '"][data-term="4"]').attr('data-status', b
            //             .finalstatus)

            //         if (b.prelemstatus == 1) {
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="1"]').addClass(
            //                 'bg-success')
            //         } else if (b.prelemstatus == 7) {
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="1"]').addClass(
            //                 'bg-primary')
            //         } else if (b.prelemstatus == 9) {
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="1"]').addClass(
            //                 'bg-danger')
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="1"]').text('DROPPED')
            //         } else if (b.prelemstatus == 8) {
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="1"]').addClass(
            //                 'bg-warning')
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="1"]').text('INC')
            //         } else if (b.prelemstatus == 3) {
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="1"]').addClass(
            //                 'bg-warning')
            //         } else if (b.prelemstatus == 4) {
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="1"]').addClass(
            //                 'bg-info')
            //         } else if (b.prelemstatus == 2) {
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="1"]').addClass(
            //                 'bg-secondary')
            //         } else {
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="1"]').removeAttr(
            //                 'class')
            //             $('td[data-studid="' + b.studid + '"][data-term="1"]').addClass(
            //                 'grade_td text-center align-middle input_grades')
            //         }

            //         if (b.midtermstatus == 1) {
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="2"]').addClass(
            //                 'bg-success')
            //         } else if (b.midtermstatus == 7) {
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="2"]').addClass(
            //                 'bg-primary')
            //         } else if (b.midtermstatus == 9) {
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="2"]').addClass(
            //                 'bg-danger')
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="2"]').text('DROPPED')
            //         } else if (b.midtermstatus == 8) {
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="2"]').addClass(
            //                 'bg-warning')
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="2"]').text('INC')
            //         } else if (b.midtermstatus == 4) {
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="2"]').addClass(
            //                 'bg-info')
            //         } else if (b.midtermstatus == 3) {
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="2"]').addClass(
            //                 'bg-warning')
            //         } else if (b.midtermstatus == 2) {
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="2"]').addClass(
            //                 'bg-secondary')
            //         } else {
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="2"]').removeAttr(
            //                 'class')
            //             $('td[data-studid="' + b.studid + '"][data-term="2"]').addClass(
            //                 'grade_td text-center align-middle input_grades')
            //         }

            //         if (b.prefistatus == 1) {
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="3"]').addClass(
            //                 'bg-success')
            //         } else if (b.prefistatus == 7) {
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="3"]').addClass(
            //                 'bg-primary')
            //         } else if (b.prefistatus == 4) {
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="3"]').addClass(
            //                 'bg-info')
            //         } else if (b.prefistatus == 9) {
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="3"]').addClass(
            //                 'bg-danger')
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="3"]').text('DROPPED')
            //         } else if (b.prefistatus == 8) {
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="3"]').addClass(
            //                 'bg-warning')
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="3"]').text('INC')
            //         } else if (b.prefistatus == 3) {
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="3"]').addClass(
            //                 'bg-warning')
            //         } else if (b.prefistatus == 2) {
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="3"]').addClass(
            //                 'bg-secondary')
            //         } else {
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="3"]').removeAttr(
            //                 'class')
            //             $('td[data-studid="' + b.studid + '"][data-term="3"]').addClass(
            //                 'grade_td text-center align-middle input_grades')
            //         }

            //         if (b.finalstatus == 1) {
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="4"]').addClass(
            //                 'bg-success')
            //             $('th[data-studid="' + b.studid + '"][data-term="5"]').addClass('bg-success')
            //             if (b.fgremarks == 'FAILED') {
            //                 $('th[data-studid="' + b.studid + '"][data-term="6"]').addClass('bg-danger')
            //             } else {
            //                 $('th[data-studid="' + b.studid + '"][data-term="6"]').addClass('bg-success')
            //             }
            //         } else if (b.finalstatus == 7) {
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="4"]').addClass(
            //                 'bg-primary')
            //             $('th[data-studid="' + b.studid + '"][data-term="5"]').addClass('bg-primary')
            //             $('th[data-studid="' + b.studid + '"][data-term="6"]').addClass('bg-primary')
            //         } else if (b.finalstatus == 9) {
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="4"]').addClass(
            //                 'bg-danger')
            //             $('th[data-studid="' + b.studid + '"][data-term="5"]').addClass('bg-danger')
            //             $('th[data-studid="' + b.studid + '"][data-term="6"]').addClass('bg-danger')
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="4"]').text('DROPPED')

            //         } else if (b.finalstatus == 8) {
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="4"]').addClass(
            //                 'bg-warning')
            //             $('th[data-studid="' + b.studid + '"][data-term="5"]').addClass('bg-warning')
            //             $('th[data-studid="' + b.studid + '"][data-term="6"]').addClass('bg-warning')
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="4"]').text('INC')
            //         } else if (b.finalstatus == 4) {
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="4"]').addClass(
            //                 'bg-info')
            //             $('th[data-studid="' + b.studid + '"][data-term="5"]').addClass('bg-info')
            //             $('th[data-studid="' + b.studid + '"][data-term="6"]').addClass('bg-info')
            //         } else if (b.finalstatus == 3) {
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="4"]').addClass(
            //                 'bg-warning')
            //             $('th[data-studid="' + b.studid + '"][data-term="5"]').addClass('bg-warning')
            //             $('th[data-studid="' + b.studid + '"][data-term="6"]').addClass('bg-warning')
            //         } else if (b.finalstatus == 2) {
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="4"]').addClass(
            //                 'bg-secondary')
            //             $('th[data-studid="' + b.studid + '"][data-term="5"]').addClass('bg-secondary')
            //             $('th[data-studid="' + b.studid + '"][data-term="6"]').addClass('bg-secondary')
            //         } else {
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="4"]').removeAttr(
            //                 'class')
            //             $('td[data-studid="' + b.studid + '"][data-term="4"]').addClass(
            //                 'grade_td text-center align-middle input_grades')
            //         }

            //         $('th[data-studid="' + b.studid + '"][data-term="5"]').text(b.fg != null ? b.fg : '')
            //         $('th[data-studid="' + b.studid + '"][data-term="6"]').text(b.fgremarks != null ? b
            //             .fgremarks : '')
            //         $('.select[data-studid="' + b.studid + '"]').attr('data-id', b.id)


            //         if (b.finalstatus == 9) {
            //             $('th[data-studid="' + b.studid + '"][data-term="5"]').text('DROPPED')
            //             $('th[data-studid="' + b.studid + '"][data-term="6"]').text('DROPPED')
            //         } else if (b.finalstatus == 8) {
            //             $('th[data-studid="' + b.studid + '"][data-term="5"]').text('INC')
            //             $('th[data-studid="' + b.studid + '"][data-term="6"]').text('INC')
            //         }

            //     })

            // }

            // function plot_subject_grades(data) {
            //     $.each(data, function(a, b) {

            //         // Define default status classes
            //         var statuses = {
            //             1: 'bg-success',
            //             2: 'bg-secondary',
            //             3: 'bg-warning',
            //             4: 'bg-info',
            //             7: 'bg-primary',
            //             8: 'bg-warning',
            //             9: 'bg-danger'
            //         };

            //         // Update grades for each term
            //         $('.input_grades[data-studid="' + b.studid + '"][data-term="1"]').text(b.prelemgrade !=
            //             null ? b.prelemgrade : '');
            //         $('.input_grades[data-studid="' + b.studid + '"][data-term="2"]').text(b.midtermgrade !=
            //             null ? b.midtermgrade : '');
            //         $('.input_grades[data-studid="' + b.studid + '"][data-term="3"]').text(b.prefigrade !=
            //             null ? b.prefigrade : '');
            //         $('.input_grades[data-studid="' + b.studid + '"][data-term="4"]').text(b.finalgrade !=
            //             null ? b.finalgrade : '');

            //         $('.input_grades[data-studid="' + b.studid + '"][data-term="1"]').attr('data-id', b.id);
            //         $('.input_grades[data-studid="' + b.studid + '"][data-term="2"]').attr('data-id', b.id);
            //         $('.input_grades[data-studid="' + b.studid + '"][data-term="3"]').attr('data-id', b.id);
            //         $('.input_grades[data-studid="' + b.studid + '"][data-term="4"]').attr('data-id', b.id);

            //         $('.input_grades[data-studid="' + b.studid + '"][data-term="1"]').attr('data-status', b
            //             .prelemstatus);
            //         $('.input_grades[data-studid="' + b.studid + '"][data-term="2"]').attr('data-status', b
            //             .midtermstatus);
            //         $('.input_grades[data-studid="' + b.studid + '"][data-term="3"]').attr('data-status', b
            //             .prefistatus);
            //         $('.input_grades[data-studid="' + b.studid + '"][data-term="4"]').attr('data-status', b
            //             .finalstatus);

            //         // Handle status classes for each term
            //         function updateStatus(term, status) {
            //             var element = $('.input_grades[data-studid="' + b.studid + '"][data-term="' + term +
            //                 '"]');
            //             element.removeClass(
            //                 'bg-success bg-secondary bg-warning bg-info bg-primary bg-danger');
            //             if (status in statuses) {
            //                 element.addClass(statuses[status]);
            //                 if (status === 9) {
            //                     element.text('DROPPED');
            //                 } else if (status === 8) {
            //                     element.text('INC');
            //                 }
            //             } else {
            //                 element.text('');

            //             }
            //         }

            //         // Update status for each term
            //         updateStatus("1", b.prelemstatus);
            //         updateStatus("2", b.midtermstatus);
            //         updateStatus("3", b.prefistatus);
            //         updateStatus("4", b.finalstatus);

            //         // Handle additional information
            //         $('th[data-studid="' + b.studid + '"][data-term="5"]').text(b.fg != null ? b.fg : '');
            //         $('th[data-studid="' + b.studid + '"][data-term="6"]').text(b.fgremarks != null ? b
            //             .fgremarks : '');
            //         $('.select[data-studid="' + b.studid + '"]').attr('data-id', b.id);

            //         // Update final status remarks
            //         if (b.finalstatus === 9) {
            //             $('th[data-studid="' + b.studid + '"][data-term="5"]').text('DROPPED');
            //             $('th[data-studid="' + b.studid + '"][data-term="6"]').text('DROPPED');
            //         } else if (b.finalstatus === 8) {
            //             $('th[data-studid="' + b.studid + '"][data-term="5"]').text('INC');
            //             $('th[data-studid="' + b.studid + '"][data-term="6"]').text('INC');
            //         }

            //     });
            // }

            ///////////////////////////
            function plot_subject_grades(data) {

                // temp_id = $(this).attr('data-id')
                // schedid = temp_id

                var failedCount = 0;
                var passedCount = 0;
                $.each(data, function(index, record) {


                    // Define default status classes
                    var statuses = {
                        1: 'bg-success',
                        2: 'bg-secondary',
                        3: 'bg-warning',
                        4: 'bg-info',
                        7: 'bg-primary',
                        8: 'bg-warning',
                        9: 'bg-danger'
                    };

                    // Flag to check if any term status is DROPPED or INC
                    var hasFailedStatus = false;


                    // Update grades for each term
                    $('.input_grades[data-studid="' + record.studid + '"][data-term="1"]').text(record
                        .prelemgrade != null ? record.prelemgrade : '');
                    $('.input_grades[data-studid="' + record.studid + '"][data-term="2"]').text(record
                        .midtermgrade != null ? record.midtermgrade : '');
                    $('.input_grades[data-studid="' + record.studid + '"][data-term="3"]').text(record
                        .prefigrade != null ? record.prefigrade : '');
                    $('.input_grades[data-studid="' + record.studid + '"][data-term="4"]').text(record
                        .finalgrade != null ? record.finalgrade : '');

                    $('.input_grades[data-studid="' + record.studid + '"][data-term="1"]').attr('data-id',
                        record.id);
                    $('.input_grades[data-studid="' + record.studid + '"][data-term="2"]').attr('data-id',
                        record.id);
                    $('.input_grades[data-studid="' + record.studid + '"][data-term="3"]').attr('data-id',
                        record.id);
                    $('.input_grades[data-studid="' + record.studid + '"][data-term="4"]').attr('data-id',
                        record.id);

                    $('.input_grades[data-studid="' + record.studid + '"][data-term="1"]').attr(
                        'data-status', record.prelemstatus);
                    $('.input_grades[data-studid="' + record.studid + '"][data-term="2"]').attr(
                        'data-status', record.midtermstatus);
                    $('.input_grades[data-studid="' + record.studid + '"][data-term="3"]').attr(
                        'data-status', record.prefistatus);
                    $('.input_grades[data-studid="' + record.studid + '"][data-term="4"]').attr(
                        'data-status', record.finalstatus);

                    // Function to handle status classes for each term
                    function updateStatus(term, status) {
                        var element = $('.input_grades[data-studid="' + record.studid + '"][data-term="' +
                            term + '"]');
                        element.removeClass(
                            'bg-success bg-secondary bg-warning bg-info bg-primary bg-danger');
                        // $('.student-checkbox[data-studid="' + record.studid + '"]').prop('disabled',
                        //     false);
                        if (status in statuses) {
                            element.addClass(statuses[status]);

                            $('th[data-studid="' + record.studid + '"][data-term="5"]').addClass(
                                'bg-success');
                            // $('th[data-studid="' + record.studid + '"][data-term="6"]').addClass(
                            //     'bg-success');

                            if (status === 9) {
                                element.text('DROPPED');
                                hasFailedStatus = true; // Mark as failed




                            } else if (status === 8) {
                                element.text('INC');
                                hasFailedStatus = true; // Mark as failed

                            }
                        }
                    }

                    // Update status for each term
                    updateStatus("1", record.prelemstatus);
                    updateStatus("2", record.midtermstatus);
                    updateStatus("3", record.prefistatus);
                    updateStatus("4", record.finalstatus);
                    updateStatus("5", record.fg);
                    updateStatus("6", record.fgremarks);

                    // Handle additional information
                    $('th[data-studid="' + record.studid + '"][data-term="5"]').text(record.fg != null ?
                        record.fg : '');
                    $('th[data-studid="' + record.studid + '"][data-term="6"]').text(record.fgremarks !=
                        null ? record.fgremarks : '');
                    $('.select[data-studid="' + record.studid + '"]').attr('data-id', record.id);

                    // // Update final status remarks based on any failed status
                    // if (hasFailedStatus) {
                    //     $('th[data-studid="' + record.studid + '"][data-term="5"]').text('FAILED');
                    //     $('th[data-studid="' + record.studid + '"][data-term="6"]').text('FAILED');
                    //     $('.student-checkbox[data-studid="' + record.studid + '"]').prop('disabled', true);

                    // } else {

                    //     $('.student-checkbox[data-studid="' + record.studid + '"]').prop('disabled',
                    //         false);

                    // }
                    // Handle additional information and final status remarks
                    var finalStatusText = record.fgremarks != null ? record.fgremarks : '';
                    var finalStatusElement = $('th[data-studid="' + record.studid + '"][data-term="6"]');

                    if (hasFailedStatus) {
                        $('th[data-studid="' + record.studid + '"][data-term="5"]').text('---');
                        $('th[data-studid="' + record.studid + '"][data-term="6"]').text('---');
                        finalStatusElement.text('FAILED');
                        finalStatusElement.addClass('bg-danger'); // Red background for FAILED
                        $('.student-checkbox[data-studid="' + record.studid + '"]').prop('disabled', true);

                        failedCount++;
                    } else {
                        $('th[data-studid="' + record.studid + '"][data-term="5"]').text(record.fg != null ?
                            record.fg : '');
                        finalStatusElement.text(finalStatusText);

                        if (finalStatusText === 'PASSED') {
                            finalStatusElement.removeClass(
                                'bg-danger'); // Remove background colors if not PASSED
                            finalStatusElement.addClass('bg-success'); // Green background for PASSED
                            passedCount++;
                        } else {
                            finalStatusElement.removeClass(
                                'bg-success'); // Remove background colors if not PASSED
                            finalStatusElement.addClass('bg-danger'); // Green background for PASSED
                        }

                        $('.student-checkbox[data-studid="' + record.studid + '"]').prop('disabled', false);
                    }




                });
                $('#passedCount').text(passedCount);
                $('#failedCount').text(failedCount);

            }
            ///////////////////////////////////////////////////
            // function plot_subject_grades(data) {
            //     $.each(data, function(index, record) {

            //         // Define default status classes
            //         var statuses = {
            //             1: 'bg-success',
            //             2: 'bg-secondary',
            //             3: 'bg-warning',
            //             4: 'bg-info',
            //             7: 'bg-primary',
            //             8: 'bg-warning',
            //             9: 'bg-danger'
            //         };

            //         // Flag to check if any term status is DROPPED or INC
            //         var hasFailedStatus = false;

            //         // Update grades for each term
            //         $('.input_grades[data-studid="' + record.studid + '"][data-term="1"]').text(record
            //             .prelemgrade != null ? record.prelemgrade : '');
            //         $('.input_grades[data-studid="' + record.studid + '"][data-term="2"]').text(record
            //             .midtermgrade != null ? record.midtermgrade : '');
            //         $('.input_grades[data-studid="' + record.studid + '"][data-term="3"]').text(record
            //             .prefigrade != null ? record.prefigrade : '');
            //         $('.input_grades[data-studid="' + record.studid + '"][data-term="4"]').text(record
            //             .finalgrade != null ? record.finalgrade : '');

            //         $('.input_grades[data-studid="' + record.studid + '"][data-term="1"]').attr('data-id',
            //             record.id);
            //         $('.input_grades[data-studid="' + record.studid + '"][data-term="2"]').attr('data-id',
            //             record.id);
            //         $('.input_grades[data-studid="' + record.studid + '"][data-term="3"]').attr('data-id',
            //             record.id);
            //         $('.input_grades[data-studid="' + record.studid + '"][data-term="4"]').attr('data-id',
            //             record.id);

            //         $('.input_grades[data-studid="' + record.studid + '"][data-term="1"]').attr(
            //             'data-status', record.prelemstatus);
            //         $('.input_grades[data-studid="' + record.studid + '"][data-term="2"]').attr(
            //             'data-status', record.midtermstatus);
            //         $('.input_grades[data-studid="' + record.studid + '"][data-term="3"]').attr(
            //             'data-status', record.prefistatus);
            //         $('.input_grades[data-studid="' + record.studid + '"][data-term="4"]').attr(
            //             'data-status', record.finalstatus);

            //         // Function to handle status classes for each term
            //         function updateStatus(term, status) {
            //             var element = $('.input_grades[data-studid="' + record.studid + '"][data-term="' +
            //                 term + '"]');
            //             element.removeClass(
            //                 'bg-success bg-secondary bg-warning bg-info bg-primary bg-danger');
            //             if (status in statuses) {
            //                 element.addClass(statuses[status]);
            //                 if (status === 9) {
            //                     element.text('DROPPED');
            //                     hasFailedStatus = true; // Mark as failed
            //                 } else if (status === 8) {
            //                     element.text('INC');
            //                     hasFailedStatus = true; // Mark as failed
            //                 }
            //             } else {
            //                 element.text(''); // Clear text if status is not recognized
            //             }
            //         }

            //         // Update status for each term
            //         updateStatus("1", record.prelemstatus);
            //         updateStatus("2", record.midtermstatus);
            //         updateStatus("3", record.prefistatus);
            //         updateStatus("4", record.finalstatus);

            //         // Handle additional information
            //         $('th[data-studid="' + record.studid + '"][data-term="5"]').text(record.fg != null ?
            //             record.fg : '');
            //         $('th[data-studid="' + record.studid + '"][data-term="6"]').text(record.fgremarks !=
            //             null ? record.fgremarks : '');
            //         $('.select[data-studid="' + record.studid + '"]').attr('data-id', record.id);

            //         // Update final status remarks based on any failed status
            //         if (hasFailedStatus) {
            //             $('th[data-studid="' + record.studid + '"][data-term="5"]').text('FAILED');
            //             $('th[data-studid="' + record.studid + '"][data-term="6"]').text('FAILED');
            //         } else if (record.finalstatus === 9) {
            //             $('th[data-studid="' + record.studid + '"][data-term="5"]').text('DROPPED');
            //             $('th[data-studid="' + record.studid + '"][data-term="6"]').text('DROPPED');
            //         } else if (record.finalstatus === 8) {
            //             $('th[data-studid="' + record.studid + '"][data-term="5"]').text('INC');
            //             $('th[data-studid="' + record.studid + '"][data-term="6"]').text('INC');
            //         } else {
            //             $('th[data-studid="' + record.studid + '"][data-term="5"]').text(
            //             ''); // Clear text if no status
            //             $('th[data-studid="' + record.studid + '"][data-term="6"]').text(
            //             ''); // Clear text if no status
            //         }
            //     });
            // }




            // $(document).on('click', '.view_students', function() {
            //     $('#modal_1').modal()
            //     temp_id = $(this).attr('data-id')
            //     var students = all_subject.filter(x => x.schedid == temp_id)
            //     datatable_2(students[0].students)
            // })


            // function datatable_2(students) {

            //     $("#datatable_2").DataTable({
            //         destroy: true,
            //         data: students,
            //         lengthChange: false,
            //         autoWidth: false,
            //         columns: [{
            //                 "data": "search"
            //             },
            //             {
            //                 "data": "levelname"
            //             },
            //             {
            //                 "data": "courseabrv"
            //             },
            //             {
            //                 "data": "gender"
            //             },
            //         ],
            //         columnDefs: [{
            //                 'targets': 0,
            //                 'orderable': true,
            //                 'createdCell': function(td, cellData, rowData, row, col) {
            //                     $(td)[0].innerHTML = rowData.student
            //                     $(td).addClass('align-middle')
            //                 }
            //             },
            //             {
            //                 'targets': 1,
            //                 'orderable': true,
            //                 'createdCell': function(td, cellData, rowData, row, col) {
            //                     $(td)[0].innerHTML = rowData.levelname.replace('COLLEGE', '')
            //                     $(td).addClass('align-middle')
            //                 }
            //             },
            //         ]
            //     })

            // }

            $(document).on('click', '#view_pdf', function() {
                var temp_id = $(this).attr('data-id');
                window.open(
                    `/college/teacher/student-list/print/${$('#filter_sy').val()}/${$('#filter_semester').val()}/${temp_id}`,
                    '_blank');

            })

            function datatable_2(data) {
                // Check if data is an object and contains the students array
                if (typeof data === 'object' && Array.isArray(data.students)) {
                    // Extract students array
                    const students = data.students;


                    // Group students by gender
                    const groupedByGender = students.reduce((acc, student) => {
                        // Fallback for undefined gender
                        const gender = student.gender || 'Unspecified';
                        if (!acc[gender]) {
                            acc[gender] = [];
                        }
                        acc[gender].push(student);
                        return acc;
                    }, {});



                    // Clear the table body
                    $("#datatable_2").empty();

                    // Create an array of gender keys and sort it to prioritize 'Male'
                    const genderKeys = Object.keys(groupedByGender);
                    genderKeys.sort((a, b) => {
                        if (a.toLowerCase() === 'male') return -1;
                        if (b.toLowerCase() === 'male') return 1;
                        return 0;
                    });
                    // Loop through each gender group in the sorted order
                    for (let gender of genderKeys) {
                        // Determine background color based on gender
                        let backgroundColor = gender.toLowerCase() === 'male' ? '#8ec9fd' : '#fd8ec9';

                        // Add a header row for each gender
                        $("#datatable_2").append(`
                            <tr style="background-color: ${backgroundColor};">
                                <td colspan="7" style="text-align: left;"><strong>${gender.toUpperCase()}</strong></td>
                            </tr>
                        `);
                        // <td colspan="7"><strong>${gender.toUpperCase()}</strong></td>

                        // Add rows for each student in the gender group
                        groupedByGender[gender].forEach(student => {
                            $("#datatable_2").append(`
                    <tr>
                        <td>${student.sid || ''}</td>
                        <td>${student.lastname || ''}, ${student.firstname || ''}, ${student.middlename || ''}, ${student.suffix || ''}</td>
                        <td>${student.levelname || ''}</td>
                        <td>${student.courseabrv || ''}</td>a
                        <td>${student.contactno || ''}</td>
                    </tr>
                `);
                        });
                    }

                    // Initialize DataTable
                    $("#datatable_2_table").DataTable({
                        destroy: true,
                        lengthChange: false,
                        autoWidth: false,
                        ordering: true,
                        columnDefs: [{
                                targets: [0, 2, 5, 6],
                                orderable: true
                            },
                            {
                                targets: [3, 4],
                                orderable: false
                            }
                        ]
                    });

                } else {
                    console.error('Invalid data format:', data);
                }
            }



            function get_student(temp_id) {
                $.ajax({
                    type: 'GET',
                    url: `/college/teacher/student-list-for-all/${$('#filter_sy').val()}/${$('#filter_semester').val()}/${temp_id}`,
                    success: function(data) {
                        // Call datatable_2 with the fetched data
                        datatable_2(data);
                    }
                });
            }

            $(document).on('click', '.view_students', function() {
                temp_id = $(this).attr('data-id')
                $('#modal_1').modal()
                get_student(temp_id)
            })









            ////////////////////////////////////


            // function datatable_1() {

            //     // var all_data = all_subject
            //     // if($('#term').val() != ""){
            //     //       if($('#term').val() == "Whole Sem"){
            //     //             all_data = all_subject.filter(x=>x.schedotherclass == null)
            //     //       }else{
            //     //             all_data = all_subject.filter(x=>x.schedotherclass == $('#term').val())
            //     //       }
            //     // }
            //     if (school == 'sait'.toUpperCase()) {
            //         var temp_subjects = all_subject
            //     } else {
            //         var temp_subjects = all_subject
            //     }


            //     $("#datatable_1").DataTable({
            //         destroy: true,
            //         data: temp_subjects,
            //         lengthChange: false,
            //         scrollX: true,
            //         autoWidth: false,
            //         columns: [{
            //                 "data": "sectionDesc"
            //             },
            //             {
            //                 "data": "subjDesc"
            //             },
            //             {
            //                 "data": "levelname"
            //             },
            //             {
            //                 "data": "schedotherclass"
            //             },
            //             {
            //                 "data": null
            //             },
            //             {
            //                 "data": null
            //             },
            //             {
            //                 "data": null
            //             },
            //             {
            //                 "data": null
            //             },
            //         ],
            //         columnDefs: [{
            //                 'targets': 0,
            //                 'orderable': true,
            //                 'createdCell': function(td, cellData, rowData, row, col) {
            //                     var text = '';
            //                     $.each(rowData.sections, function(a, b) {
            //                         text +=
            //                             '<span class="badge badge-primary mt-1" style="font-size:.65rem !important; white-space:normal">' +
            //                             b.schedgroupdesc + '</span> <br>';
            //                     });
            //                     $(td)[0].innerHTML = text;
            //                     $(td).addClass('align-middle');
            //                 }
            //             },
            //             {
            //                 'targets': 1,
            //                 'orderable': true,
            //                 'createdCell': function(td, cellData, rowData, row, col) {
            //                     var text = '';
            //                     if (school == 'spct'.toUpperCase() || school == 'gbbc'
            //                         .toUpperCase()) {
            //                         text = rowData.subjDesc;
            //                     } else {
            //                         text = '<a class="mb-0">' + rowData.subjDesc +
            //                             '</a><p class="text-muted mb-0" style="font-size:.7rem">' +
            //                             rowData.subjCode + '</p>';
            //                     }
            //                     $(td)[0].innerHTML = text;
            //                     $(td).addClass('align-middle');
            //                 }
            //             },

            //             {
            //                 'targets': 2,
            //                 'orderable': false,
            //                 'createdCell': function(td, cellData, rowData, row, col) {

            //                     var text =
            //                         '<a href="javascript:void(0)"  class=" text-muted" data-id="' +
            //                         rowData.levelname + '">' + rowData.levelname + '</a>';
            //                     $(td)[0].innerHTML = text
            //                     $(td).addClass('align-middle  text-left')
            //                     $(td).css('vertical-align', 'middle !important')


            //                 }
            //             },
            //             {
            //                 'targets': 3,
            //                 'orderable': false,
            //                 'createdCell': function(td, cellData, rowData, row, col) {

            //                     var text =
            //                         '<a href="javascript:void(0)"  class=" text-muted" data-id="' +
            //                         rowData.schedotherclass + '">' + rowData.schedotherclass +
            //                         '</a>';
            //                     $(td)[0].innerHTML = text
            //                     $(td).addClass('align-middle  text-left')
            //                     $(td).css('vertical-align', 'middle !important')


            //                     // var scheduleHtml = '';
            //                     // $.each(rowData.schedotherclass, function(index, schedItem) {
            //                     //     scheduleHtml +=
            //                     //         '<div>' +
            //                     //         '<span>' + '' + ' - ' + '' +
            //                     //         '</span>' +
            //                     //         '<span>' + schedotherclass + '</span>' +
            //                     //         '</div>';
            //                     // });
            //                     // $(td)[0].innerHTML = scheduleHtml;
            //                     // $(td).addClass('align-middle text-left');



            //                 }
            //             },
            //             {
            //                 'targets': 4,
            //                 'orderable': false,
            //                 'createdCell': function(td, cellData, rowData) {
            //                     var daysHtml = '';schedule
            //                     if (rowData.schedule && rowData..length > 0) {
            //                         $.each(rowData.schedule, function(index, schedule) {
            //                             daysHtml += '<div>' +
            //                                 '<span>' + schedule.day + '</span><br>' +
            //                                 '</div><br>';
            //                         });
            //                     } else {
            //                         daysHtml = '<span>No days available</span>';
            //                     }
            //                     $(td).html(daysHtml);
            //                     $(td).addClass('align-middle text-left');
            //                     $(td).css('vertical-align', 'middle !important');
            //                 }
            //             },
            //             {
            //                 'targets': 5,
            //                 'orderable': false,
            //                 'createdCell': function(td, cellData, rowData, row, col) {

            //                     $(td).html('');



            //                 }
            //             },
            //             {
            //                 'targets': 6,
            //                 'orderable': false,
            //                 'createdCell': function(td, cellData, rowData, row, col) {
            //                     var link =
            //                         '<a href="#" class="text-primary view_students" data-id="' +
            //                         rowData.schedid +
            //                         '"><i class="fas fa-user-circle"></i> <i>(' + rowData.students
            //                         .length + ')</i></a>';
            //                     $(td)[0].innerHTML = link;
            //                     $(td).addClass('text-center align-middle');
            //                 }
            //             },
            //             {
            //                 'targets': 7,
            //                 'orderable': false,
            //                 'createdCell': function(td, cellData, rowData, row, col) {
            //                     var link =
            //                         '<a href="#" style="color: #blue; text-decoration: underline;" class="view_grades" data-id="' +
            //                         rowData.schedid +
            //                         '"> Grading</a>';
            //                     $(td)[0].innerHTML = link;
            //                     $(td).addClass('text-center align-middle');
            //                 }
            //             }


            //         ]
            //     });

            // }

            // function datatable_1() {
            //     var temp_subjects = all_subject;

            //     console.log(temp_subjects);

            //     $("#datatable_1").DataTable({
            //         destroy: true,
            //         data: temp_subjects,
            //         lengthChange: false,
            //         scrollX: true,
            //         autoWidth: false,
            //         columns: [{
            //                 "data": "sectionDesc"
            //             },
            //             {
            //                 "data": "subjDesc"
            //             },
            //             {
            //                 "data": "levelname"
            //             },
            //             {
            //                 "data": "schedotherclass"
            //             },
            //             {
            //                 "data": "schedule"
            //             },
            //             {
            //                 "data": null
            //             },
            //             {
            //                 "data": null
            //             },
            //             {
            //                 "data": null
            //             }
            //         ],
            //         columnDefs: [{
            //                 'targets': 0,
            //                 'orderable': true,
            //                 'createdCell': function(td, cellData, rowData) {
            //                     var text = '';
            //                     $.each(rowData.sections, function(a, b) {
            //                         text +=
            //                             '<span class="badge badge-primary mt-1" style="font-size:.65rem !important; white-space:normal">' +
            //                             b.schedgroupdesc + '</span> <br>';
            //                     });
            //                     $(td).html(text).addClass('align-middle');
            //                 }
            //             },
            //             {
            //                 'targets': 1,
            //                 'orderable': true,
            //                 'createdCell': function(td, cellData, rowData) {
            //                     var text = '';
            //                     if (school == 'SPCT'.toUpperCase() || school == 'GBBC'
            //                         .toUpperCase()) {
            //                         text = rowData.subjDesc;
            //                     } else {
            //                         text = '<a class="mb-0">' + rowData.subjDesc +
            //                             '</a><p class="text-muted mb-0" style="font-size:.7rem">' +
            //                             rowData.subjCode + '</p>';
            //                     }
            //                     $(td).html(text).addClass('align-middle');
            //                 }
            //             },
            //             {
            //                 'targets': 2,
            //                 'orderable': false,
            //                 'createdCell': function(td, cellData, rowData) {
            //                     var text =
            //                         '<a href="javascript:void(0)" class="text-muted" data-id="' +
            //                         rowData.levelname + '">' + rowData.levelname + '</a>';
            //                     $(td).html(text).addClass('align-middle text-left').css(
            //                         'vertical-align', 'middle');
            //                 }
            //             },
            //             {
            //                 'targets': 3,
            //                 'orderable': false,
            //                 'createdCell': function(td, cellData, rowData) {
            //                     var text =
            //                         '<a href="javascript:void(0)" class="text-muted" data-id="' +
            //                         rowData.schedotherclass + '">' + rowData.schedotherclass +
            //                         '</a>';
            //                     $(td).html(text).addClass('align-middle text-left').css(
            //                         'vertical-align', 'middle');
            //                 }
            //             },
            //             {
            //                 'targets': 4,
            //                 'orderable': false,
            //                 'createdCell': function(td, cellData) {
            //                     // var daysHtml = '';
            //                     // if (cellData && cellData.length > 0) {
            //                     //     $.each(cellData, function(index, schedule) {
            //                     //         daysHtml += '<div>' +
            //                     //             '<span>' + schedule.day + '</span><br>' +
            //                     //             '</div><br>';
            //                     //     });
            //                     // } else {
            //                     //     daysHtml = '<span>No days available</span>';
            //                     // }
            //                     // $(td).html(daysHtml).addClass('align-middle text-left').css(
            //                     //     'vertical-align', 'middle');
            //                     // var daysHtml = '';
            //                     // if (Array.isArray(cellData) && cellData.length > 0) {
            //                     //     $.each(cellData, function(index, schedule) {
            //                     //         daysHtml += '<div>' +
            //                     //             '<span>' + schedule.day + '</span><br>' +
            //                     //             '</div><br>';
            //                     //     });
            //                     // } else {
            //                     //     daysHtml = '<span>No days available</span>';
            //                     // }
            //                     // $(td).html(daysHtml).addClass('align-middle text-left').css(
            //                     //     'vertical-align', 'middle');
            //                     var daysHtml = '';
            //                     if (Array.isArray(cellData) && cellData.length > 0) {
            //                         daysHtml = cellData.map(function(schedule) {
            //                             return '<div><span>' + schedule.day +
            //                                 '</span><br></div><br>';
            //                         }).join('');
            //                     } else {
            //                         daysHtml = '<span>No days available</span>';
            //                     }
            //                     $(td).html(daysHtml).addClass('align-middle text-left').css(
            //                         'vertical-align', 'middle');
            //                 }
            //             },
            //             {
            //                 'targets': 5,
            //                 'orderable': false,
            //                 'createdCell': function(td, cellData) {
            //                     $(td).html('');
            //                 }
            //             },
            //             {
            //                 'targets': 6,
            //                 'orderable': false,
            //                 'createdCell': function(td, cellData, rowData) {
            //                     var link =
            //                         '<a href="#" class="text-primary view_students" data-id="' +
            //                         rowData.schedid + '"><i class="fas fa-user-circle"></i> <i>(' +
            //                         rowData.students.length + ')</i></a>';
            //                     $(td).html(link).addClass('text-center align-middle');
            //                 }
            //             },
            //             {
            //                 'targets': 7,
            //                 'orderable': false,
            //                 'createdCell': function(td, cellData, rowData) {
            //                     var link =
            //                         '<a href="#" style="color: blue; text-decoration: underline;" class="view_grades" data-id="' +
            //                         rowData.schedid + '"> Grading</a>';
            //                     $(td).html(link).addClass('text-center align-middle');
            //                 }
            //             }
            //         ]
            //     });
            // }

            function datatable_1(data) {
                var temp_subjects = data;

                console.log(temp_subjects, 'temporary ra ni nga data');

                $("#datatable_1").DataTable({
                    destroy: true,
                    data: temp_subjects,
                    lengthChange: false,
                    scrollX: true,
                    autoWidth: false,
                    columns: [{
                            "data": "sectionDesc"
                        },
                        {
                            "data": "subjDesc"
                        },
                        {
                            "data": "yearDesc"
                        },
                        {
                            "data": "schedtime"
                        },
                        {
                            "data": "days"
                        }, // Changed to match data
                        {
                            "data": "roomname"
                        },
                        {
                            "data": null
                        },
                        {
                            "data": null
                        }
                    ],
                    columnDefs: [{
                            'targets': 0,
                            'orderable': true,
                            'createdCell': function(td, cellData, rowData) {
                                // var text = '';
                                // $.each(rowData.sections, function(a, b) {
                                //     text +=
                                //         '<span class="badge badge-primary mt-1" style="font-size:.65rem !important; white-space:normal">' +
                                //         b.schedgroupdesc + '</span> <br>';
                                // });
                                // $(td)[0].innerHTML = text;
                                // $(td).addClass('align-middle');
                                var text = '<a class="badge badge-primary text-white mb-0">' +
                                    rowData.sectionDesc + '</a>';
                                $(td).html(text).addClass('align-middle');
                            }
                        },
                        // {
                        //     'targets': 1, // Adjust this based on the target column index
                        //     'orderable': true,
                        //     'createdCell': function(td, cellData, rowData) {
                        //         var text = '';

                        //         // Check if the school is either 'spct' or 'gbbc'
                        //         if (school == 'spct'.toUpperCase() || school == 'gbbc'
                        //             .toUpperCase()) {
                        //             // If yes, display only the subject description and the warning icon
                        //             text = '<div>' +
                        //                 '<a class="mb-0">' + (rowData.subjDesc || 'N/A') + '</a>' +
                        //                 '<div>' +
                        //                 '<i class="fa fa-exclamation-triangle text-warning" data-toggle="tooltip" title="Conflict Schedule"></i>' +
                        //                 '</div>' +
                        //                 '</div>';
                        //         } else {
                        //             // For other schools, display the subject description, subject code, and the warning icon
                        //             text = '<div>' +
                        //                 '<a class="mb-0">' + (rowData.subjDesc || 'N/A') + '</a>' +
                        //                 '<p class="text-muted mb-0" style="font-size:.7rem">' + (
                        //                     rowData.subjCode || '') + '</p>' +
                        //                 '<div>' +
                        //                 '<i class="fa fa-exclamation-triangle text-warning" data-toggle="tooltip" title="Conflict Schedule"></i>' +
                        //                 '</div>' +
                        //                 '</div>';
                        //         }

                        //         $(td)[0].innerHTML = text;
                        //         $(td).addClass('align-middle');

                        //         // Initialize tooltips for the icons
                        //         $(td).find('[data-toggle="tooltip"]').tooltip();
                        //     }
                        // },

                        {
                            'targets': 1,
                            'orderable': true,
                            'createdCell': function(td, cellData, rowData) {
                                // var text = '';
                                // var conflictIcon =
                                //     '<i class="fa fa-exclamation-triangle text-warning" data-toggle="tooltip" title="Conflict Schedule"></i>';
                                // var time = rowData.schedule.map(s =>
                                //     s.time
                                // )


                            }
                        },
                        // {
                        //     'targets': 1,
                        //     'orderable': true,
                        //     'createdCell': function(td, cellData, rowData) {
                        //         var conflictIcon =
                        //             '<i class="fa fa-exclamation-triangle text-warning" data-toggle="tooltip" title="Conflict Schedule"></i>';

                        //         // Extract the first time entry for conflict checking
                        //         var time = rowData.schedule[0] ? rowData.schedule[0].time : [];
                        //         var day = rowData.schedule[0] ? rowData.schedule[0].day : [];
                        //         var room = rowData.schedule[0] ? rowData.schedule[0].room : [];

                        //         console.log('time', time);

                        //         $.ajax({
                        //             url: '/college/teacher/schedule/conflict',
                        //             method: 'GET',
                        //             data: {
                        //                 sectionID: rowData.sectionID,
                        //                 room: room,
                        //                 day: day,
                        //                 time: time,
                        //                 syid: $('#syid').val(),
                        //                 semid: $('#semester').val(),
                        //             },
                        //             success: function(conflictData) {
                        //                 var hasConflict = conflictData.length > 0;
                        //                 var text = '<div>' +
                        //                     '<a class="mb-0">' + (rowData.subjDesc ||
                        //                         'N/A') + '</a>' +
                        //                     '<div>' +
                        //                     (hasConflict ? conflictIcon : '') +
                        //                     '</div>' +
                        //                     '</div>';

                        //                 if (!['SPCT', 'GBBC'].includes(school
                        //                         .toUpperCase())) {
                        //                     text = '<div>' +
                        //                         '<a class="mb-0">' + (rowData
                        //                             .subjDesc || 'N/A') + '</a>' +
                        //                         '<p class="text-muted mb-0" style="font-size:.7rem">' +
                        //                         (rowData.subjCode || '') + '</p>' +
                        //                         '<div>' +
                        //                         (hasConflict ? conflictIcon : '') +
                        //                         '</div>' +
                        //                         '</div>';
                        //                 }

                        //                 $(td).html(text).addClass('align-middle');
                        //                 $(td).find('[data-toggle="tooltip"]').tooltip();
                        //             }

                        //             //   success: function(conflictData) {
                        //             //     var hasConflict = conflictData.length > 0;
                        //             //     if (['SPCT', 'GBBC'].includes(school
                        //             //             .toUpperCase())) {
                        //             //         text = '<div>' +
                        //             //             '<a class="mb-0">' + (rowData
                        //             //                 .subjDesc || 'N/A') + '</a>' +
                        //             //             '<div>' +
                        //             //             (hasConflict ? conflictIcon : '') +
                        //             //             '</div>' +
                        //             //             '</div>';
                        //             //     } else {
                        //             //         text = '<div>' +
                        //             //             '<a class="mb-0">' + (rowData
                        //             //                 .subjDesc || 'N/A') + '</a>' +
                        //             //             '<p class="text-muted mb-0" style="font-size:.7rem">' +
                        //             //             (rowData.subjCode || '') + '</p>' +
                        //             //             '<div>' +
                        //             //             (hasConflict ? conflictIcon : '') +
                        //             //             '</div>' +
                        //             //             '</div>';
                        //             //     }
                        //             //     $(td).html(text).addClass('align-middle');
                        //             //     $(td).find('[data-toggle="tooltip"]').tooltip();
                        //             // }
                        //         });
                        //     }
                        // },


                        {
                            'targets': 2,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData) {
                                // var text =
                                //     '<a href="javascript:void(0)" class="text-muted" data-id="' +
                                //     rowData.yearDesc + '">' + rowData.yearDesc + '</a>';
                                // $(td)[0].innerHTML = text;
                                // $(td).addClass('align-middle text-left');
                                // $(td).css('vertical-align', 'middle !important', 'width:25px;');
                                var text = '<a class="mb-0">' + rowData.yearDesc + '</a>';
                                $(td).html(text).addClass('align-middle');
                            }
                        },
                        {
                            'targets': 3,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData) {
                                // var daysHtml = '';
                                // if (rowData.schedule && rowData.schedule.length > 0) {
                                //     $.each(rowData.schedule, function(index, s) {
                                //         daysHtml += '<div>' +
                                //             '<div class="text-danger"><i>' + (rowData
                                //                 .schedotherclass || 'N/A') + '</i></div>' +
                                //             '<div>' +
                                //             '<span>' + s.start + ' - ' + s
                                //             .end + '</span>' +
                                //             '</div><br>';
                                //     });
                                // } else {
                                //     daysHtml = '<span>No days available</span>';
                                // }
                                // $(td).html(daysHtml);
                                // $(td).addClass('align-middle text-left');
                                // $(td).css('vertical-align', 'middle !important');
                                // var scheduleTime = rowData.schedtime.length > 0 ? rowData
                                // .schedtime : '';
                                var conflictIcon =
                                    '<i class="fa fa-exclamation-triangle text-warning" data-toggle="tooltip" title="Conflict Schedule"></i>';

                                // Extract the first time entry for conflict checking
                                // var time = (rowData.schedtime && Array.isArray(rowData.schedtime) &&
                                //         rowData.schedtime.length > 0) ? rowData.schedtime[0].time :
                                //     [];
                                // var day = (rowData.schedule && Array.isArray(rowData.schedule) &&
                                //     rowData.schedule.length > 0) ? rowData.schedule[0].days : [];
                                // var room = (rowData.schedule && Array.isArray(rowData.schedule) &&
                                //     rowData.schedule.length > 0) ? rowData.schedule[0].room : [];
                                var schedotherclass = rowData.schedotherclass || '';
                                var scheduleTime = rowData.schedtime;
                                var time = rowData.schedtime;
                                var day = rowData.days.split('/').map(function(day) {
                                    switch (day.trim().toLowerCase()) {
                                        case 'mon':
                                            return '1';
                                        case 'tue':
                                            return '2';
                                        case 'wed':
                                            return '3';
                                        case 'thu':
                                            return '4';
                                        case 'fri':
                                            return '5';
                                        case 'sat':
                                            return '6';
                                        case 'sun':
                                            return '7';
                                        default:
                                            return '';
                                    }
                                }).filter(Boolean);
                                var room = rowData.roomname;

                                var subjDesc = rowData.subjDesc;
                                var semid = rowData.semid; // Assume schedule contains day
                                var schedid = rowData.schedid;


                                $.ajax({
                                    url: '/college/teacher/schedule/conflict',
                                    method: 'GET',
                                    data: {
                                        // sectionID: rowData.sectionID,
                                        // room: rowData.room,
                                        // sectionID: sectionid,
                                        syid: $('#filter_sy').val(),
                                        semid: $('#filter_semester').val(),
                                        room: room,
                                        schedid: schedid,
                                        time: time,
                                        day: day // Assume schedule contains day
                                        // Assume schedule contains start and end time

                                    },

                                    success: function(conflictData) {
                                        var hasConflict = conflictData.length > 0;
                                        var conflictIcon =
                                            '<i class="fa fa-exclamation-triangle text-warning" data-toggle="tooltip" title="Conflict Schedule"></i>';

                                        var text = '<div>' +
                                            '<a class="mb-0" style="color:#1583fc; font-style:italic;">' +
                                            schedotherclass + '</a><br>' +
                                            '<span style="font-size: 12px;">' +
                                            scheduleTime + '&nbsp&nbsp' +
                                            (hasConflict ? ' ' + conflictIcon : '') +
                                            '</span>';

                                        text += '</div>';

                                        $(td).html(text).addClass('align-middle');
                                        $(td).find('[data-toggle="tooltip"]').tooltip();
                                    }
                                });


                                // var schedotherclass = rowData.schedotherclass || '';
                                // var scheduleTime = rowData.schedtime;

                            }
                        },
                        {
                            'targets': 4,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData) {
                                // var daysHtml = '';
                                // if (rowData.schedule && rowData.schedule.length > 0) {
                                //     $.each(rowData.schedule, function(index, s) {
                                //         daysHtml += '<div>' +
                                //             '<span>' + s.day + '</span><br>' +
                                //             '</div><br>';
                                //     });
                                // } else {
                                //     daysHtml = '<span>No days available</span>';
                                // }
                                // $(td).html(daysHtml);
                                // $(td).addClass('align-middle text-left');
                                // $(td).css('vertical-align', 'middle !important', 'padding:5px;');
                                var text =
                                    '<a class="mb-0" style="display: block; text-align: center;">' +
                                    rowData.days + '</a>';
                                $(td).html(text).addClass('align-middle');
                            }
                        },
                        {
                            'targets': 5,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData) {
                                // Check if rowData.room is not empty and not null
                                // var text = (rowData.room && rowData.room.trim() !== '') ?
                                //     '<a href="javascript:void(0)" class="text-muted" data-id="' +
                                //     rowData.room + '">' + rowData.room + '</a>' :
                                //     ' '; // Display a space if room is empty or null

                                // $(td)[0].innerHTML = text;
                                // $(td).addClass('align-middle text-left');
                                // $(td).css('vertical-align', 'middle !important');
                                var roomname = rowData.roomname || 'Not assigned';
                                var text = '<a class="mb-0" style="' + (roomname ===
                                        'Not assigned' ? 'color:red; font-style:italic;' : '') +
                                    '">' +
                                    roomname + '</a>';
                                $(td).html(text).addClass('align-middle');
                            }
                        },
                        {
                            'targets': 6,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData) {
                                // var link =
                                //     '<a href="#" style="font-size:20px;font-weight: bold;" class="text-primary view_students" data-id="' +
                                //     rowData.schedid +
                                //     '">' +
                                //     rowData.students
                                //     .length + '</a>';
                                // $(td).html(link).addClass('text-center align-middle');
                                var studentCount = rowData.students.studentCount;
                                var text =
                                    '<a href="#" class="text-primary view_students" data-id="' +
                                    rowData.schedid +
                                    '" style="font-size: 20px; display: block; text-align: center; font-weight:bold">' +
                                    studentCount + '</a>';
                                $(td).html(text).addClass('align-middle');
                            }
                        },
                        {
                            'targets': 7,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData) {
                                var link =
                                    '<a href="#" style="color: #blue; text-decoration: underline;font-weight: bold;" id="view_grades_modal" data-id="' +
                                    rowData.schedid +
                                    '"> Grading</a>';
                                $(td)[0].innerHTML = link;
                                $(td).addClass('text-center align-middle');
                            }
                        }
                    ]
                });
            }



            function datatable_3() {

                var all_data = all_gradestatus
                if ($('#term').val() != "") {
                    if ($('#term').val() == "Whole Sem") {
                        all_data = all_gradestatus.filter(x => x.schedotherclass == null)
                    } else {
                        all_data = all_gradestatus.filter(x => x.schedotherclass == $('#term').val())
                    }
                }

                $("#datatable_3").DataTable({
                    destroy: true,
                    data: all_data,
                    lengthChange: false,
                    scrollX: true,
                    autoWidth: false,
                    columns: [{
                            "data": "sectionDesc"
                        },
                        {
                            "data": "subjDesc"
                        },
                        {
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
                    columnDefs: [{
                            'targets': 0,
                            'orderable': true,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                var text = '<a class="mb-0">' + rowData.sectionDesc +
                                    '</a><p class="text-muted mb-0" style="font-size:.7rem">' +
                                    rowData.levelname.replace('COLLEGE', '') + ' - ' + rowData
                                    .courseabrv + '</p>';
                                $(td)[0].innerHTML = text
                                $(td).addClass('align-middle')
                            }
                        },
                        {
                            'targets': 1,
                            'orderable': true,
                            'createdCell': function(td, cellData, rowData, row, col) {


                                var schedotherclass = ''

                                var text = '<a class="mb-0">' + rowData.subjDesc +
                                    '</a><p class="text-muted mb-0" style="font-size:.7rem">' +
                                    rowData.subjCode +
                                    ' - <i class="mb-0 text-danger" style="font-size:.7rem">' +
                                    schedotherclass + '</i></p>';
                                $(td)[0].innerHTML = text
                                $(td).addClass('align-middle')
                            }
                        },
                        {
                            'targets': 2,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                if (rowData.gradestatus.length == 0) {
                                    var text = '<a class="mb-0">Not Submitted</a>';
                                } else {
                                    var status = ''
                                    if (rowData.gradestatus[0].prelimstatus == null) {
                                        status = 'Not Submitted'
                                    } else if (rowData.gradestatus[0].prelimstatus == 1) {
                                        status = 'Submitted'
                                    }
                                    var text = '<a class="mb-0">' + status +
                                        '</a><p class="text-muted mb-0" style="font-size:.7rem">' +
                                        rowData.gradestatus[0].prelimdate + '</p>';
                                }

                                $(td)[0].innerHTML = text
                                $(td).addClass('align-middle')
                                $(td).addClass('text-center')
                                if (school == 'spct'.toUpperCase()) {
                                    $(td).attr('hidden', 'hidden')
                                }
                            }
                        },
                        {
                            'targets': 3,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                if (rowData.gradestatus.length == 0) {
                                    var text = '<a class="mb-0">Not Submitted</a>';
                                } else {
                                    var status = ''
                                    if (rowData.gradestatus[0].midtermstatus == null) {
                                        status = 'Not Submitted'
                                    } else if (rowData.gradestatus[0].midtermstatus == 1) {
                                        status = 'Submitted'
                                    }
                                    var text = '<a class="mb-0">' + status +
                                        '</a><p class="text-muted mb-0" style="font-size:.7rem">' +
                                        rowData.gradestatus[0].midtermdate + '</p>';
                                }

                                $(td)[0].innerHTML = text
                                $(td).addClass('align-middle')
                                $(td).addClass('text-center')
                                if (school == 'spct'.toUpperCase()) {
                                    $(td).attr('hidden', 'hidden')
                                }
                            }
                        },
                        {
                            'targets': 4,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                if (rowData.gradestatus.length == 0) {
                                    var text = '<a class="mb-0">Not Submitted</a>';
                                } else {
                                    var status = ''
                                    if (rowData.gradestatus[0].prefistatus == null) {
                                        status = 'Not Submitted'
                                    } else if (rowData.gradestatus[0].prefistatus == 1) {
                                        status = 'Submitted'
                                    }
                                    var text = '<a class="mb-0">' + status +
                                        '</a><p class="text-muted mb-0" style="font-size:.7rem">' +
                                        rowData.gradestatus[0].prefidate + '</p>';
                                }

                                $(td)[0].innerHTML = text
                                $(td).addClass('align-middle')
                                $(td).addClass('text-center')
                            }
                        },
                        {
                            'targets': 5,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                if (rowData.gradestatus.length == 0) {
                                    var text = '<a class="mb-0">Not Submitted</a>';
                                } else {
                                    var status = ''
                                    if (rowData.gradestatus[0].finalstatus == null) {
                                        status = 'Not Submitted'
                                    } else if (rowData.gradestatus[0].finalstatus == 1) {
                                        status = 'Submitted'
                                    }
                                    var text = '<a class="mb-0">' + status +
                                        '</a><p class="text-muted mb-0" style="font-size:.7rem">' +
                                        rowData.gradestatus[0].finaldate + '</p>';
                                }
                                $(td)[0].innerHTML = text
                                $(td).addClass('align-middle')
                                $(td).addClass('text-center')
                            }
                        }

                    ]
                })
            }

            $(document).on('click', '.sort-iconn', function() {
                var $this = $(this);
                var sortDirection = $this.hasClass('sort-asc') ? 'desc' : 'asc';
                var column = $this.data('sort');
                $('#male_label').hide();
                $('#female_label').hide();

                // Toggle icon visibility
                $this.toggle();
                $this.siblings('.sort-iconn').toggle();

                // Sort rows based on the column
                var rows = $('#student_list_grades tr').get();
                rows.sort(function(a, b) {
                    var keyA = $(a).find('td').eq(1).text().toUpperCase();
                    var keyB = $(b).find('td').eq(1).text().toUpperCase();

                    if (sortDirection === 'asc') {
                        return keyA.localeCompare(keyB);
                    } else {
                        return keyB.localeCompare(keyA);
                    }
                });

                $.each(rows, function(index, row) {
                    $('#student_list_grades').append(row);
                });
            });

            ////////////////////////// goods na ni
            // $(document).on('click', '.sort-icon', function() {
            //     var $this = $(this);
            //     var sortDirection = $this.hasClass('sort-asc') ? 'desc' : 'asc';
            //     var column = $this.data('sort');
            //     var gender = $this.data('gender');

            //     // Toggle icon visibility
            //     $this.toggle();
            //     $this.siblings('.sort-icon').toggle();

            //     // Identify the starting point for sorting (the row immediately after the gender header)
            //     var $headerRow = $this.closest('tr');
            //     var $nextRow = $headerRow.next();
            //     var rowsToSort = [];

            //     // Collect rows to be sorted
            //     while ($nextRow.length && !$nextRow.hasClass('bg-secondary')) {
            //         rowsToSort.push($nextRow.get(0));
            //         $nextRow = $nextRow.next();
            //     }

            //     // Sort rows based on the column
            //     rowsToSort.sort(function(a, b) {
            //         var keyA = $(a).find('td').eq(1).text().toUpperCase();
            //         var keyB = $(b).find('td').eq(1).text().toUpperCase();

            //         if (sortDirection === 'asc') {
            //             return keyA.localeCompare(keyB);
            //         } else {
            //             return keyB.localeCompare(keyA);
            //         }
            //     });

            //     // Insert sorted rows back into the table
            //     $.each(rowsToSort, function(index, row) {
            //         $headerRow.after(row);
            //         $headerRow = $(row); // Update headerRow to be the last inserted row
            //     });
            // });

            ////////////////////////////////////////////////////////////////////
            // Event listener for the studentFilter checkbox
            $('#studentFilter').on('change', function() {
                // Check or uncheck all student checkboxes based on the state of studentFilter
                if ($(this).is(':checked')) {
                    // Check all student checkboxes
                    $('.select').prop('checked', true);
                } else {
                    // Uncheck all student checkboxes
                    $('.select').prop('checked', false);
                }
            });
            /////////////////////////////////////////////////


            // Update malecheckbox event to only affect checkboxes in the male section
            $(document).on('change', '#malecheckbox', function() {
                var isChecked = $(this).is(':checked');
                // Only affect checkboxes within the male section
                $('#student_list_gradess').find('tr.male_section').nextUntil('tr.bg-secondary').find(
                    'input.student-checkbox').prop('checked', isChecked);
            });

            // Update femalecheckbox event to only affect checkboxes in the female section
            $(document).on('change', '#femalecheckbox', function() {
                var isChecked = $(this).is(':checked');
                // Only affect checkboxes within the female section
                $('#student_list_gradess').find('tr.female_section').nextUntil('tr.bg-secondary').find(
                    'input.student-checkbox').prop('checked', isChecked);
            });



            $(document).on('click',
                '.submit_all_btn, .grade_submissions_prelim, .grade_submissions_midterm, .grade_submissions_semifinal, .grade_submissions_final',
                function() {
                    // submit_grade();
                    submit_grade(this); // Pass the clicked element to the function
                });



            // $(document).on('click', '.submit_all_btn', function() {
            //     submit_grade2(this)
            // })

            function filterInput(event) {
                const validChars = /^[0-9]*$/;
                const content = event.target.innerText;

                // If content does not match the allowed characters, revert to the previous valid content
                if (!validChars.test(content)) {
                    event.target.innerText = content.replace(/[^0-9]/g, ''); // Remove invalid characters
                }
            }

            // Attach the event listener to all contenteditable cells
            document.querySelectorAll(
                '.formativeHighestScore, .summativeHighestScore, .unitHighestScore, .terminalHighestScore, .term_examHighestScore'
            ).forEach(cell => {
                cell.addEventListener('input', filterInput);
            });

            $(document).on('input',
                '.formativeHighestScore[contenteditable="true"], .summativeHighestScore[contenteditable="true"], .unitHighestScore[contenteditable="true"], .terminalHighestScore[contenteditable="true"], .term_examHighestScore[contenteditable="true"]',
                function() {

                    var text = $(this).text().replace(/\D/g, '').slice(0, 3);
                    $(this).text(text);
                    // Move cursor to end
                    window.getSelection().collapse(this.firstChild, text.length);
                });




            // function FormativecalculateHighestScores() {
            //     $.ajax({
            //         type: 'GET',
            //         url: '/getcalculation/formativeHighestScore', // The route defined in Laravel
            //         data: {
            //             F1HighestScore: $('#F1HighestScore').text(),
            //             F2HighestScore: $('#F2HighestScore').text(),
            //             F3HighestScore: $('#F3HighestScore').text(),
            //             F4HighestScore: $('#F4HighestScore').text(),
            //             S1HighestScore: $('#S1HighestScore').text(),
            //             S2HighestScore: $('#S2HighestScore').text(),
            //             S3HighestScore: $('#S3HighestScore').text(),

            //         },
            //         success: function(response) {
            //             // Update your HTML with the calculated values
            //             $('.formative_totalHighestScore').text(response.totalHighestScore1);
            //             $('.formative_averageHighestScore').text(response.generalAverageHighestScore1);
            //             $('.summative_totalHighestScore').text(response.totalHighestScore2);
            //             $('.summative_averageHighestScore').text(response.generalAverageHighestScore2);
            //             $('.other_requirements_geHighestScore').text(response
            //                 .other_requirements_geHighestScoreGE);
            //             $('.other_requirements_percentHighestScore').text(response
            //                 .other_requirements_geHighestScorePercent);
            //         },
            //         error: function(xhr) {
            //             console.error('An error occurred:', xhr.responseText);
            //         }
            //     });
            // }

            // // Attach input event handler to the editable cells
            // $('.formativeHighestScore, .summativeHighestScore').on('input', function() {
            //     FormativecalculateHighestScores();
            // });


            // // Optional: Trigger initial calculation if needed
            // FormativecalculateHighestScores();

            // function SummativetivecalculateHighestScores() {
            //     $.ajax({
            //         type: 'GET',
            //         url: '/getcalculation/formativeHighestScore', // The route defined in Laravel
            //         data: {
            //             S1HighestScore: $('#S1HighestScore').text(),
            //             S2HighestScore: $('#S2HighestScore').text(),
            //             S3HighestScore: $('#S3HighestScore').text(),

            //         },
            //         success: function(response) {
            //             // Update your HTML with the calculated values
            //             $('.summative_totalHighestScore').text(response.totalHighestScore2);
            //             $('.summative_averageHighestScore').text(response.generalAverageHighestScore2);
            //             $('.other_requirements_geHighestScore').text(response
            //                 .other_requirements_geHighestScoreGE);
            //         },
            //         error: function(xhr) {
            //             console.error('An error occurred:', xhr.responseText);
            //         }
            //     });
            // }

            // // Attach input event handler to the editable cells
            // $('.summativeHighestScore').on('input', function() {
            //     SummativetivecalculateHighestScores();
            // });

            // // Optional: Trigger initial calculation if needed
            // SummativetivecalculateHighestScores();



            // function FormativeGEcalculateHighestScores() {

            //     // Check if neither value is empty
            //     // if (formativeValue !== '' && summativeValue !== '') {
            //     $.ajax({
            //         type: 'GET',
            //         url: '/getcalculation/otherrequirements_GEHighestScore', // The route defined in Laravel
            //         data: {
            //             formative_totalHighestScore: $('#formative_totalHighestScore').text(),
            //             summative_totalHighestScore: $('#summative_totalHighestScore').text(),
            //         },
            //         success: function(response) {
            //             // Update your HTML with the calculated value
            //             $('.other_requirements_geHighestScore').text(response
            //                 .GE_Other_requirementsHighestScore);
            //         },
            //         error: function(xhr) {
            //             console.error('An error occurred:', xhr.responseText);
            //         }
            //     });
            //     // }
            // }

            // // Attach input event handler to the elements
            // $('#formative_totalHighestScore, #summative_totalHighestScore').on('DOMSubtreeModified', function() {
            //     FormativeGEcalculateHighestScores();
            // });

            // // Optional: Trigger initial calculation if needed
            // FormativeGEcalculateHighestScores();






            // function UnitcalculateHighestScores() {
            //     $.ajax({
            //         type: 'GET',
            //         url: '/getcalculation/unitHighestScore', // The route defined in Laravel
            //         data: {
            //             UR1HighestScore: $('#UR1HighestScore').text(),
            //             UR2HighestScore: $('#UR2HighestScore').text(),
            //             UR3HighestScore: $('#UR3HighestScore').text(),
            //             UR4HighestScore: $('#UR4HighestScore').text()

            //         },
            //         success: function(response) {
            //             // Update your HTML with the calculated values
            //             $('.unit_requirements_totalHighestScore').text(response.totalHighestScore);
            //             $('.unit_requirements_avgHighestScore').text(response
            //                 .generalAverageHighestScore);
            //         },
            //         error: function(xhr) {
            //             console.error('An error occurred:', xhr.responseText);
            //         }
            //     });
            // }

            // // Attach input event handler to the editable cells
            // $('.unitHighestScore').on('input', function() {
            //     UnitcalculateHighestScores();
            // });

            // // Optional: Trigger initial calculation if needed
            // UnitcalculateHighestScores();

            // function TerminalcalculateHighestScores() {
            //     $.ajax({
            //         type: 'GET',
            //         url: '/getcalculation/terminalHighestScore', // The route defined in Laravel
            //         data: {
            //             TR1HighestScore: $('#TR1HighestScore').text(),
            //             TR2HighestScore: $('#TR2HighestScore').text(),
            //             TR3HighestScore: $('#TR3HighestScore').text()


            //         },
            //         success: function(response) {
            //             // Update your HTML with the calculated values
            //             $('.terminal_totalHighestScore').text(response.totalHighestScore);
            //             $('.terminal_averageHighestScore').text(response.generalAverageHighestScore);
            //         },
            //         error: function(xhr) {
            //             console.error('An error occurred:', xhr.responseText);
            //         }
            //     });
            // }

            // // Attach input event handler to the editable cells
            // $('.terminalHighestScore').on('input', function() {
            //     TerminalcalculateHighestScores();
            // });

            // // Optional: Trigger initial calculation if needed
            // TerminalcalculateHighestScores();




            // Function to limit the input value based on the highest score
            function limitInputValue(inputElement, highestScoreElement) {
                var highestScore = parseFloat($(highestScoreElement).text()) || 0;
                var currentValue = parseFloat($(inputElement).text()) || 0;

                // If the current value exceeds the highest score, set it to the highest score
                if (currentValue > highestScore) {
                    $(inputElement).text("");
                }
            }

            // Bind the event to all input elements with contenteditable="true" and specific IDs
            $(document).on('input', '[contenteditable="true"]', function() {
                var id = $(this).attr('id');

                // Define the mapping between input elements and highest score elements
                var highestScoreMappings = {
                    'F1': '#F1HighestScore',
                    'F2': '#F2HighestScore',
                    'F3': '#F3HighestScore',
                    'F4': '#F4HighestScore',
                    'S1': '#S1HighestScore',
                    'S2': '#S2HighestScore',
                    'S3': '#S3HighestScore',
                    'UR1': '#UR1HighestScore',
                    'UR2': '#UR2HighestScore',
                    'UR3': '#UR3HighestScore',
                    'UR4': '#UR4HighestScore',
                    'TR1': '#TR1HighestScore',
                    'TR2': '#TR2HighestScore',
                    'TR3': '#TR3HighestScore',
                    'term_exam': '#term_examHighestScore'
                };

                if (highestScoreMappings[id]) {
                    limitInputValue(this, highestScoreMappings[id]);
                }
            });





        })
    </script>
@endsection
