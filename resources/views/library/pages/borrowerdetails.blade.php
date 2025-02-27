@php
    $extends = '';
    if (auth()->user()->type == 5) {
        $extends = 'layouts.backend';
    }
@endphp

@extends($extends)

@section('css_before')
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables/buttons-bs4/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
    <style>
         th {
            white-space: nowrap;
        }
    </style>
@endsection

@section('content')
    <!-- Main Container -->
    <main>
        <!-- Page Content -->
        <div class="content">
            <p>
                <a class="mb-2 font-w600 text-primary-dark" href="{{ URL::previous() }}"> <i class="fa fa-arrow-left mr-1"></i>
                    Back</a>
            </p>

            <div class="row">
                <div class="col-lg-4 mb-2">
                    <div class="mb-1 bg-gray p-2">
                        <img src="https://tse1.mm.bing.net/th?id=OIP.-9N4K3Syg-OgbET8dgDwqAHaHa&pid=Api&rs=1&c=1&qlt=95&w=123&h=123"
                            alt="QR CODE">
                    </div>

                    <h3 class="block-title font-size-sm mb-0">Member</h3>
                    <div class="bg-image" style="background-image: url('{{ asset('media/photos/photo8@2x.jpg') }}');">
                        <div class="bg-black-75">
                            <div class="content content-full d-flex align-items-center">
                                <div class="my-3">
                                    <img class="img-avatar img-avatar-thumb" src="{{ asset('media/avatars/avatar13.jpg') }}"
                                        alt="" style="height: 80px; width: 80px;">
                                </div>
                                <div class="ml-3" style="width: 100%; overflow: hidden;">
                                    <h3 class="text-white text-start mb-0" style="white-space: nowrap;">
                                        {{ $borrower->name }}</h3>
                                    <table style="width: 100%;">
                                        <tr>
                                            <th class="text-white font-w500 mb-0">Card ID</th>
                                            <td class="text-white font-w500 mb-0">
                                                00000
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-white font-w500 mb-0">Class/Position</th>
                                            <td class="text-white font-w500 mb-0">
                                                {{ $borrower->utype }}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="block block-rounded d-flex flex-column mt-4">
                        @php
                             $jsonData = \DB::table('library_circulation')
                                ->leftJoin('library_books', 'library_circulation.circulation_book_id', '=', 'library_books.id')
                                ->join('library_status', 'library_circulation.circulation_status', '=', 'library_status.id')
                                ->join('libraries', 'library_books.library_branch', '=', 'libraries.id')
                                ->where('library_circulation.circulation_deleted', 0)
                                ->where('library_circulation.circulation_utype', $borrower->utype)
                                ->where('library_circulation.circulation_members_id', $borrower->id)
                                ->where('library_circulation.circulation_status', '!=', 3)
                                ->whereNotNull('library_circulation.circulation_due_date') // Ensure there is a due date
                                ->whereDate('library_circulation.circulation_due_date', '<', now()) // Filter overdue items
                                ->select(
                                    'library_circulation.*', 
                                    'library_books.book_title', 
                                    'library_books.book_author', 
                                    'libraries.library_name', 
                                    'library_status.status_name')
                                ->get();

                            foreach ($jsonData as $item) {
                                $item->circulation_due_date = new \DateTime($item->circulation_due_date);
                                $item->circulation_due_date = $item->circulation_due_date->format('F d, Y');
                            }
                        @endphp

                        <div
                            class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                            <dl class="mb-0">
                                <dt class="font-size-h2 font-w700">{{ $jsonData->count() }}</dt>
                                <dd class="text-muted mb-0">OVERDUE BOOK</dd>
                            </dl>
                            <div class="item item-rounded bg-body">
                                <img src="{{ asset('assets/lms/issued.png') }}" alt="book img"
                                    style="height: 50px; width: 50px;">
                            </div>
                        </div>
                        <div class="block-content block-content-full block-content-sm bg-body-light font-size-sm">

                            @foreach ($jsonData as $item)
                                <div class="d-flex">
                                    <p class="text-danger font-w600">{{ $item->book_title }}</p>
                                    <span class="font-size-sm ml-auto"> {{ $item->circulation_due_date }} </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
                <div class="col-lg-8">
                    <!-- MY BORROWED BOOKS -->
                    <div class="block block-rounded">
                        <div class="block-header">
                            <h3 class="block-title"> <i class="fa fa-book mr-1"></i> BOOK BORROWED</h3>
                        </div>
                        <div class="block-content pb-3">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover table-striped table-vcenter js-dataTable-full" id="tb_borrowed"
                                    style="width: 100%;">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Book Title</th>
                                            <th>Date Borrowed</th>
                                            <th>Due Date</th>
                                            <th>Date Returned</th>
                                            <th>Penalty</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- MY ISSUED BOOKS -->
                    <div class="block block-rounded">
                        <div class="block-header">
                            <h3 class="block-title"> <i class="fa fa-book mr-1"></i> BOOK ISSUED</h3>
                        </div>
                        <div class="block-content pb-3">
                            <div class="table-responsive">
                                <table class="table table-sm table-vcenter js-dataTable-full" id="tb_issued"
                                    style="width: 100%;">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Book Title</th>
                                            <th>Date Borrowed</th>
                                            <th>Due Date</th>
                                            <th>Date Returned</th>
                                            <th>Penalty</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- END ISSUED BOOKS -->

                    <!-- MY LOST BOOKS -->
                    <div class="block block-rounded">
                        <div class="block-header">
                            <h3 class="block-title"> <i class="fa fa-book mr-1"></i> BOOK LOST</h3>
                        </div>
                        <div class="block-content pb-3">
                            <div class="table-responsive">
                                <table class="table table-sm table-vcenter js-dataTable-full" id="tb_lost"
                                    style="width: 100%;">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Book Title</th>
                                            <th>Date Borrowed</th>
                                            <th>Due Date</th>
                                            <th>Date Returned</th>
                                            <th>Penalty</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- END LOST BOOKS -->

                    <!-- MY RETURN BOOKS -->
                    <div class="block block-rounded">
                        <div class="block-header">
                            <h3 class="block-title"> <i class="fa fa-book mr-1"></i> BOOK RETURNED</h3>
                        </div>
                        <div class="block-content pb-3">
                            <div class="table-responsive">
                                <table class="table table-sm table-vcenter js-dataTable-full" id="tb_returned"
                                    style="width: 100%;">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Book Title</th>
                                            <th>Date Borrowed</th>
                                            <th>Due Date</th>
                                            <th>Date Returned</th>
                                            <th>Penalty</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- END RETURN BOOKS -->
                </div>
            </div>

        </div>
        <!-- END Page Content -->
    </main>
    <!-- END Main Container -->
@endsection

@section('js_after')
    <script src="{{ asset('js/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/buttons/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/buttons/buttons.print.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/buttons/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/buttons/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/buttons/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('js/pages/tables_datatables.js') }}"></script>
    <script>
        var issued = {!! json_encode($issued) !!};
        var borrowed = {!! json_encode($borrowed) !!};
        var returned = {!! json_encode($returned) !!};
        var lost = {!! json_encode($lost) !!};
        var borrower = {!! json_encode($borrower) !!};
        console.log(borrower);
        $(document).ready(function() {
            load_borrowed_datatable(borrowed)
            load_issued_datatable(issued)
            load_lost_datatable(lost)
            load_returned_datatable(returned)
        });

        function load_borrowed_datatable(data) {
            console.log(data)
            var table = $('#tb_borrowed').DataTable({
                autowidth: false,
                destroy: true,
                responsive: true,
                stateSave: true,
                data: data,
                language: {
                    emptyTable: "No Records Found"
                },
                columns: [{
                        data: 'book_title',
                        render: function(type, data, row) {
                            var capitalizeFirstLetter = function (string) {
                                return string.toLowerCase().replace(/\b\w/g, function (match) {
                                    return match.toUpperCase();
                                });
                            };
                            return `<span class="font-size-sm font-w500" >${ capitalizeFirstLetter(row.book_title)}</span>`
                        }
                    },
                    {
                        data: "circulation_date_borrowed",
                        render: function(type, data, row) {
                            return `<span class="font-size-sm" >${row.circulation_date_borrowed}</span>`
                        }
                    },
                    {
                        data: "circulation_due_date",
                        render: function(type, data, row) {
                            return `<span class="font-size-sm" >${row.circulation_due_date}</span>`
                        }
                    },
                    {
                        data: "circulation_date_returned",
                        render: function(type, data, row) {
                            return `<span class="font-size-sm" >${row.circulation_date_returned ?? ''}</span>`
                        }
                    },
                    {
                        data: "circulation_penalty",
                        className: 'text-right',
                        render: function(data, type, row) {
                            var penalty = parseFloat(row.circulation_penalty).toFixed(2);
                            var renderHtml =
                                `<span class="font-size-sm text-danger">₱${penalty}  
                            </span>`;

                            return renderHtml;
                        }
                    },
                    {
                        data: "status_name",
                        className: 'text-right',
                        render: function(type, data, row) {
                            return `<a class="font-size-sm font-w600" >${row.status_name}</a>`
                        }
                    },

                ],
            });
        }

        function load_issued_datatable(data) {
            var table = $('#tb_issued').DataTable({
                autowidth: false,
                destroy: true,
                responsive: true,
                stateSave: true,
                data: data,
                language: {
                    emptyTable: "No Records Found"
                },
                columns: [{
                        data: 'book_title',
                        render: function(type, data, row) {
                            var capitalizeFirstLetter = function (string) {
                                return string.toLowerCase().replace(/\b\w/g, function (match) {
                                    return match.toUpperCase();
                                });
                            };
                            return `<span class="font-size-sm font-w500" >${ capitalizeFirstLetter(row.book_title)}</span>`
                        }
                    },
                    {
                        data: "circulation_date_borrowed",
                        render: function(type, data, row) {
                            return `<span class="font-size-sm" >${row.circulation_date_borrowed}</span>`
                        }
                    },
                    {
                        data: "circulation_due_date",
                        render: function(type, data, row) {
                            return `<span class="font-size-sm" >${row.circulation_due_date}</span>`
                        }
                    },
                    {
                        data: "circulation_date_returned",
                        render: function(type, data, row) {
                            return `<span class="font-size-sm" >${row.circulation_date_returned ?? ''}</span>`
                        }
                    },
                    {
                        data: "circulation_penalty",
                        className: 'text-right',
                        render: function(data, type, row) {
                            var penalty = parseFloat(row.circulation_penalty).toFixed(2);
                            var renderHtml =
                                `<span class="font-size-sm text-danger">₱${penalty}  
                            </span>`;

                            return renderHtml;
                        }
                    },
                    {
                        data: "status_name",
                        className: 'text-right',
                        render: function(type, data, row) {
                            return `<a class="font-size-sm font-w600" >${row.status_name}</a>`
                        }
                    },

                ],
            });
        }

        function load_lost_datatable(data) {
            var table = $('#tb_lost').DataTable({
                autowidth: false,
                destroy: true,
                responsive: true,
                stateSave: true,
                data: data,
                language: {
                    emptyTable: "No Records Found"
                },
                columns: [{
                        data: 'book_title',
                        render: function(type, data, row) {
                            var capitalizeFirstLetter = function (string) {
                                return string.toLowerCase().replace(/\b\w/g, function (match) {
                                    return match.toUpperCase();
                                });
                            };
                            return `<span class="font-size-sm font-w500" >${ capitalizeFirstLetter(row.book_title)}</span>`
                        }
                    },
                    {
                        data: "circulation_date_borrowed",
                        render: function(type, data, row) {
                            return `<span class="font-size-sm" >${row.circulation_date_borrowed}</span>`
                        }
                    },
                    {
                        data: "circulation_due_date",
                        render: function(type, data, row) {
                            return `<span class="font-size-sm" >${row.circulation_due_date}</span>`
                        }
                    },
                    {
                        data: "circulation_date_returned",
                        render: function(type, data, row) {
                            return `<span class="font-size-sm" >${row.circulation_date_returned ?? ''}</span>`
                        }
                    },
                    {
                        data: "circulation_penalty",
                        className: 'text-right',
                        render: function(data, type, row) {
                            var penalty = parseFloat(row.circulation_penalty).toFixed(2);
                            var renderHtml =
                                `<span class="font-size-sm text-danger">₱${penalty}  
                            </span>`;

                            return renderHtml;
                        }
                    },
                    {
                        data: "status_name",
                        className: 'text-right',
                        render: function(type, data, row) {
                            return `<a class="font-size-sm font-w600" >${row.status_name}</a>`
                        }
                    },

                ],
            });
        }

        function load_returned_datatable(data) {
            var table = $('#tb_returned').DataTable({
                autowidth: false,
                destroy: true,
                responsive: true,
                stateSave: true,
                data: data,
                language: {
                    emptyTable: "No Records Found"
                },
                columns: [{
                        data: 'book_title',
                        render: function(type, data, row) {
                            var capitalizeFirstLetter = function (string) {
                                return string.toLowerCase().replace(/\b\w/g, function (match) {
                                    return match.toUpperCase();
                                });
                            };
                            return `<span class="font-size-sm font-w500" >${ capitalizeFirstLetter(row.book_title)}</span>`
                        }
                    },
                    {
                        data: "circulation_date_borrowed",
                        render: function(type, data, row) {
                            return `<span class="font-size-sm" >${row.circulation_date_borrowed}</span>`
                        }
                    },
                    {
                        data: "circulation_due_date",
                        render: function(type, data, row) {
                            return `<span class="font-size-sm" >${row.circulation_due_date}</span>`
                        }
                    },
                    {
                        data: "circulation_date_returned",
                        render: function(type, data, row) {
                            return `<span class="font-size-sm" >${row.circulation_date_returned ?? ''}</span>`
                        }
                    },
                    {
                        data: "circulation_penalty",
                        className: 'text-right',
                        render: function(data, type, row) {
                            var penalty = parseFloat(row.circulation_penalty).toFixed(2);
                            var renderHtml =
                                `<span class="font-size-sm text-danger">₱${penalty}  
                            </span>`;

                            return renderHtml;
                        }
                    },
                    {
                        data: "status_name",
                        className: 'text-right',
                        render: function(type, data, row) {
                            return `<a class="font-size-sm font-w600" >${row.status_name}</a>`
                        }
                    },

                ],
            });
        }
    </script>
@endsection
