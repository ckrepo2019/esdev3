@php
    $extends = 'library.layouts.borrower';

    $check_refid = DB::table('usertype')
        ->where('id', Session::get('currentPortal'))
        ->select('refid', 'resourcepath')
        ->first();

    if (isset($check_refid->refid) && $check_refid->refid == 34) {
        $extends = 'library.layouts.backend';
    }
@endphp

@extends($extends)

@section('css_before')
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables/buttons-bs4/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
@endsection

@section('content')
    <div class="content">

        <!-- REPORTS CIRCULATION -->
        <div class="block block-rounded" hidden id="tb_circulation">
            <div class="block-header d-flex">
                <h3 class="block-title">Reports <small>Circulation</small></h3>
                <ul class="nav nav-pills ml-auto px-2 font-size-sm">
                    <li class="nav-item "><a class="nav-link"
                            href="/library/admin/report/circulation?action=circulation">Circulation</a>
                    </li>
                    <li class="nav-item"><a class="nav-link"
                            href="/library/admin/report/borrower?action=borrower">Borrower</a></li>
                    <li class="nav-item"><a class="nav-link"
                            href="/library/admin/report/hardreference?action=hardref">HardRef</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="/library/admin/report/e-reference?action=eref">E-Ref</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="/library/admin/report/overdue?action=overdue">Overdue</a>
                    </li>
                </ul>
            </div>
            <div class="block-content block-content-full">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter" style="width: 100%;"
                        id="report_circulation">
                        <thead class="thead-dark">
                            <tr>
                                <th>Card #</th>
                                <th>Borrower Name</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Borrowed Date</th>
                                <th>Due Date</th>
                                <th>Library</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <!-- REPORTS BORROWER -->
        <div class="block block-rounded" hidden id="tb_borrower">
            <div class="block-header d-flex">
                <h3 class="block-title">Reports <small>Borrower</small></h3>
                <ul class="nav nav-pills ml-auto px-2 font-size-sm">
                    <li class="nav-item"><a class="nav-link"
                            href="/library/admin/report/circulation?action=circulation">Circulation</a>
                    </li>
                    <li class="nav-item"><a class="nav-link"
                            href="/library/admin/report/borrower?action=borrower">Borrower</a></li>
                    <li class="nav-item"><a class="nav-link"
                            href="/library/admin/report/hardreference?action=hardref">HardRef</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="/library/admin/report/e-reference?action=eref">E-Ref</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="/library/admin/report/overdue?action=overdue">Overdue</a>
                    </li>
                </ul>
            </div>
            <div class="block-content block-content-full">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter" style="width: 100%;"
                        id="report_borrower">
                        <thead class="thead-dark">
                            <tr>
                                <th>Card #</th>
                                <th>Borrower Name</th>
                                <th>Class/Position</th>
                                <th>Borrowed</th>
                                <th>Returned</th>
                                <th>Issued</th>
                                <th>Lost</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <!-- REPORTS HARD REFERENCE -->
        <div class="block block-rounded" hidden id="tb_hardref">
            <div class="block-header d-flex">
                <h3 class="block-title">Reports <small>Hard Reference</small></h3>
                <ul class="nav nav-pills ml-auto px-2 font-size-sm">
                    <li class="nav-item"><a class="nav-link"
                            href="/library/admin/report/circulation?action=circulation">Circulation</a>
                    </li>
                    <li class="nav-item"><a class="nav-link"
                            href="/library/admin/report/borrower?action=borrower">Borrower</a></li>
                    <li class="nav-item"><a class="nav-link"
                            href="/library/admin/report/hardreference?action=hardref">HardRef</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="/library/admin/report/e-reference?action=eref">E-Ref</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="/library/admin/report/overdue?action=overdue">Overdue</a>
                    </li>
                </ul>
            </div>
            <div class="block-content block-content-full">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter" style="width: 100%;"
                        id="report_hardreference">
                        <thead class="thead-dark">
                            <tr>
                                <th>Book Title</th>
                                <th>Author</th>
                                <th>Publishing</th>
                                <th>Edition</th>
                                <th>Copyright</th>
                                <th>Categories</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

        </div>
        <!-- REPORTS E-REFERENCE -->
        <div class="block block-rounded" hidden id="tb_eref">
            <div class="block-header d-flex">
                <h3 class="block-title">Reports <small>E-Reference</small></h3>
                <ul class="nav nav-pills ml-auto px-2 font-size-sm">
                    <li class="nav-item"><a class="nav-link"
                            href="/library/admin/report/circulation?action=circulation">Circulation</a>
                    </li>
                    <li class="nav-item"><a class="nav-link"
                            href="/library/admin/report/borrower?action=borrower">Borrower</a></li>
                    <li class="nav-item"><a class="nav-link"
                            href="/library/admin/report/hardreference?action=hardref">HardRef</a>
                    </li>
                    <li class="nav-item"><a class="nav-link"
                            href="/library/admin/report/e-reference?action=eref">E-Ref</a></li>
                    <li class="nav-item"><a class="nav-link"
                            href="/library/admin/report/overdue?action=overdue">Overdue</a></li>
                </ul>
            </div>
            <div class="block-content block-content-full">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter" style="width: 100%;"
                        id="report_ereference">
                        <thead class="thead-dark">
                            <tr>
                                <th>Book Title</th>
                                <th>Author</th>
                                <th>Publishing</th>
                                <th>Edition</th>
                                <th>Copyright</th>
                                <th>Categories</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <!-- REPORTS OVERDUE -->
        <div class="block block-rounded" hidden id="tb_overdue">
            <div class="block-header d-flex">
                <h3 class="block-title">Reports <small>Overdues</small></h3>
                <ul class="nav nav-pills ml-auto px-2 font-size-sm">
                    <li class="nav-item"><a class="nav-link"
                            href="/library/admin/report/circulation?action=circulation">Circulation</a>
                    </li>
                    <li class="nav-item"><a class="nav-link"
                            href="/library/admin/report/borrower?action=borrower">Borrower</a>
                    </li>
                    <li class="nav-item"><a class="nav-link"
                            href="/library/admin/report/hardreference?action=hardref">HardRef</a></li>
                    <li class="nav-item"><a class="nav-link"
                            href="/library/admin/report/e-reference?action=eref">E-Ref</a></li>
                    <li class="nav-item"><a class="nav-link"
                            href="/library/admin/report/overdue?action=overdue">Overdue</a></li>
                </ul>
            </div>
            <div class="block-content block-content-full">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter" style="width: 100%;"
                        id="report_overdue">
                        <thead class="thead-dark">
                            <tr>
                                <th>Due Date</th>
                                <th>Borrower Name</th>
                                <th>Class/Position</th>
                                <th>Library</th>
                                <th>Book Title</th>
                                <th>Author</th>
                                <th>Call No.</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
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
        var jsonData = {!! json_encode($jsonData) !!};
        var action = {!! json_encode($action) !!};

        var btnExport = [{
            extend: "copy",
            className: "btn btn-sm btn-alt-primary"
        }, {
            extend: "csv",
            className: "btn btn-sm btn-alt-primary"
        }, {
            extend: "print",
            className: "btn btn-sm btn-alt-primary"
        }];

        $(document).ready(function() {
            console.log(jsonData);
            if (action == 'circulation') {
                $('#tb_circulation').prop('hidden', false)
                load_circulation_datatable(jsonData)
                $('a[href="/library/admin/report/circulation?action=circulation"]').addClass('active');
            } else if (action == 'borrower') {
                $('#tb_borrower').prop('hidden', false)
                load_borrower_datatable(jsonData)
                $('a[href="/library/admin/report/borrower?action=borrower"]').addClass('active');
            } else if (action == 'hardref') {
                $('#tb_hardref').prop('hidden', false)
                load_hardref_datatable(jsonData)
                $('a[href="/library/admin/report/hardreference?action=hardref"]').addClass('active');
            } else if (action == 'eref') {
                $('#tb_eref').prop('hidden', false)
                load_eref_datatable(jsonData)
                $('a[href="/library/admin/report/e-reference?action=eref"]').addClass('active');
            } else if (action == 'overdue') {
                $('#tb_overdue').prop('hidden', false)
                load_overdue_datatable(jsonData)
                $('a[href="/library/admin/report/overdue?action=overdue"]').addClass('active');
            } else {
                // $('#tb_miscelaneous').prop('hidden', false)
            }

        });

        function load_circulation_datatable(data) {
            var table = $('#report_circulation').DataTable({
                data: data,
                stateSave: true,
                destroy: true,
                lengthChange: true,
                responsive: true,
                pageLength: 5,
                lengthMenu: [
                    [5, 10, 15, 20],
                    [5, 10, 15, 20]
                ],
                autoWidth: !1,
                buttons: [{
                    extend: "copy",
                    className: "btn btn-sm btn-alt-primary"
                }, {
                    extend: "csv",
                    className: "btn btn-sm btn-alt-primary"
                }, {
                    extend: "print",
                    className: "btn btn-sm btn-alt-primary"
                }],
                dom: "<'row'<'col-sm-12'<'text-center bg-body-light py-2 mb-2'B>>><'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                columns: [{
                        // data: 'cardid',
                        className: 'text-center',
                        render: function(type, data, row) {
                            return `<span class="font-size-sm font-w600" > ${row.cardid} </span>`
                        }
                    },
                    {
                        data: 'circulation_name',
                        className: 'text-center',
                        render: function(type, data, row) {
                            return `<a class="font-size-sm font-w600" >${row.circulation_name}</a>`
                        }
                    },
                    {
                        data: "book_title",
                        className: 'text-center',
                        render: function(type, data, row) {
                            return `<span class="font-size-sm" >${row.book_title}</span>`
                        }
                    },
                    {
                        data: "book_author",
                        className: 'text-center',
                        render: function(type, data, row) {
                            return `<span class="font-size-sm" >${row.book_author}</span>`
                        }
                    },
                    {
                        data: "circulation_date_borrowed",
                        className: 'text-center',
                        render: function(type, data, row) {
                            return `<span class="font-size-sm" >${row.circulation_date_borrowed}</span>`
                        }
                    },
                    {
                        data: "circulation_due_date",
                        className: 'text-center',
                        render: function(type, data, row) {
                            return `<span class="font-size-sm" >${row.circulation_due_date}</span>`
                        }
                    },
                    {
                        data: "library_name",
                        className: 'text-center',
                        render: function(type, data, row) {
                            return `<a class="font-size-sm" >${row.library_name}</a>`
                        }
                    },
                ],
            });
        }

        function load_borrower_datatable(data) {
            $("#report_borrower").DataTable({
                data: data,
                stateSave: true,
                destroy: true,
                lengthChange: true,
                responsive: true,
                pageLength: 5,
                lengthMenu: [
                    [5, 10, 15, 20],
                    [5, 10, 15, 20]
                ],
                autoWidth: !1,
                buttons: btnExport,
                dom: "<'row'<'col-sm-12'<'text-center bg-body-light py-2 mb-2'B>>><'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                columns: [{

                        className: 'text-center',
                        render: function(type, data, row) {
                            var renderHtml =
                                `<span class="font-w600 font-size-sm">${row.cardid} </span>`;
                            return renderHtml;
                        }
                    },
                    {
                        data: 'max_circulation_name',
                        className: 'text-center',
                        render: function(type, data, row) {
                            var renderHtml =
                                `<a class="font-w600 font-size-sm"> ${row.max_circulation_name} </a>`;
                            return renderHtml;
                        }
                    },
                    {
                        data: 'class',
                        className: 'text-center',
                        render: function(type, data, row) {
                            var renderHtml =
                                `<span class="font-size-sm"> ${row.class} </span>`;
                            return renderHtml;
                        }
                    },
                    {
                        data: 'borrowed',
                        className: 'text-center',
                    },
                    {
                        data: 'returned',
                        className: 'text-center',
                    },
                    {
                        data: 'issued',
                        className: 'text-center',
                    },
                    {
                        data: 'lost',
                        className: 'text-center',
                    },

                ],

            });
        }

        function load_hardref_datatable(data) {
            $("#report_hardreference").DataTable({
                data: data,
                stateSave: true,
                destroy: true,
                lengthChange: true,
                responsive: true,
                pageLength: 5,
                lengthMenu: [
                    [5, 10, 15, 20],
                    [5, 10, 15, 20]
                ],
                autoWidth: !1,
                buttons: btnExport,
                dom: "<'row'<'col-sm-12'<'text-center bg-body-light py-2 mb-2'B>>><'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                columns: [{
                        data: 'book_title',
                        className: 'text-center',
                        render: function(type, data, row) {
                            var renderHtml =
                                `<span class="font-w600 font-size-sm"> ${row.book_title} </span>`;
                            return renderHtml;
                        }
                    },
                    {
                        data: 'book_author',
                        className: 'text-center',
                        render: function(type, data, row) {
                            var renderHtml =
                                `<a class="font-w600 font-size-sm"> ${row.book_author.toUpperCase() ?? 'Not Specified'} </a>`;
                            return renderHtml;
                        }
                    },
                    {
                        data: 'book_publisher',
                        className: 'text-center',
                        render: function(type, data, row) {
                            var renderHtml =
                                `<span class="font-size-sm"> ${row.book_publisher.toUpperCase() ?? 'Not Specified'} </span>`;
                            return renderHtml;
                        }
                    },
                    {
                        data: 'book_edition',
                        className: 'text-center',
                        render: function(type, data, row) {
                            var renderHtml =
                                `<span class="font-size-sm text-muted"> ${row.book_edition ?? 'Not Specified'} </span>`;
                            return renderHtml;
                        }
                    },
                    {
                        data: 'book_copyright',
                        className: 'text-center',
                        render: function(type, data, row) {
                            var renderHtml =
                                `<span class="font-size-sm text-muted"> ${row.book_copyright ?? 'Not Specified'} </span>`;
                            return renderHtml;
                        }
                    },
                    {
                        data: 'category_name',
                        className: 'text-center',
                        render: function(type, data, row) {
                            var renderHtml =
                                `<span class="font-size-sm"> ${row.category_name} </span>`;
                            return renderHtml;
                        }
                    },
                    {
                        data: 'book_qty',
                        className: 'text-center',
                        render: function(type, data, row) {
                            var renderHtml =
                                `<span class="font-size-sm font-w600"> ${row.book_qty} </span>`;
                            return renderHtml;
                        }
                    },

                ],

            });
        }

        function load_eref_datatable(data) {
            $('#tb_eref').prop('hidden', false);
            $("#report_ereference").DataTable({
                data: data,
                stateSave: true,
                destroy: true,
                lengthChange: true,
                responsive: true,
                pageLength: 5,
                lengthMenu: [
                    [5, 10, 15, 20],
                    [5, 10, 15, 20]
                ],
                autoWidth: !1,
                buttons: btnExport,
                dom: "<'row'<'col-sm-12'<'text-center bg-body-light py-2 mb-2'B>>><'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                columns: [{
                        data: 'book_title',
                        className: 'text-center',
                        render: function(type, data, row) {
                            var renderHtml =
                                `<span class="font-w600 font-size-sm"> ${row.book_title} </span>`;
                            return renderHtml;
                        }
                    },
                    {
                        data: 'book_author',
                        className: 'text-center',
                        render: function(type, data, row) {
                            var renderHtml =
                                `<a class="font-w600 font-size-sm"> ${row.book_author.toUpperCase() ?? 'Not Specified'} </a>`;
                            return renderHtml;
                        }
                    },
                    {
                        data: 'book_publisher',
                        className: 'text-center',
                        render: function(type, data, row) {
                            var renderHtml =
                                `<span class="font-size-sm"> ${row.book_publisher.toUpperCase() ?? 'Not Specified'} </span>`;
                            return renderHtml;
                        }
                    },
                    {
                        data: 'book_edition',
                        className: 'text-center',
                        render: function(type, data, row) {
                            var renderHtml =
                                `<span class="font-size-sm text-muted"> ${row.book_edition ?? 'Not Specified'} </span>`;
                            return renderHtml;
                        }
                    },
                    {
                        data: 'book_copyright',
                        className: 'text-center',
                        render: function(type, data, row) {
                            var renderHtml =
                                `<span class="font-size-sm text-muted"> ${row.book_copyright ?? 'Not Specified'} </span>`;
                            return renderHtml;
                        }
                    },
                    {
                        data: 'category_name',
                        className: 'text-center',
                        render: function(type, data, row) {
                            var renderHtml =
                                `<span class="font-size-sm"> ${row.category_name} </span>`;
                            return renderHtml;
                        }
                    },
                    {
                        data: 'book_qty',
                        className: 'text-center',
                        render: function(type, data, row) {
                            var renderHtml =
                                `<span class="font-size-sm font-w600"> ${row.book_qty} </span>`;
                            return renderHtml;
                        }
                    },

                ],

            });
        }

        function load_overdue_datatable(data) {
            var table = $('#report_overdue').DataTable({
                data: data,
                stateSave: true,
                destroy: true,
                lengthChange: true,
                responsive: true,
                pageLength: 5,
                lengthMenu: [
                    [5, 10, 15, 20],
                    [5, 10, 15, 20]
                ],
                autoWidth: !1,
                buttons: btnExport,
                dom: "<'row'<'col-sm-12'<'text-center bg-body-light py-2 mb-2'B>>><'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                columns: [{
                        data: 'circulation_due_date',
                        className: 'text-center',
                        render: function(type, data, row) {
                            return `<span class="font-size-sm text-danger font-w600">${row.circulation_due_date}</span>`
                        }
                    },
                    {
                        data: 'circulation_name',
                        className: 'text-center',
                        render: function(type, data, row) {
                            return `<a class="font-size-sm font-w600" >${row.circulation_name}</a>`
                        }
                    },
                    {
                        data: "circulation_utype",
                        className: 'text-center',
                        render: function(type, data, row) {
                            return `<span class="font-size-sm" >${row.circulation_utype ?? 'Unkown'}</span>`
                        }
                    },
                    {
                        data: "library_name",
                        className: 'text-center',
                        render: function(type, data, row) {
                            return `<span class="font-size-sm" >${row.library_name}</span>`
                        }
                    },
                    {
                        data: "book_title",
                        className: 'text-center',
                        render: function(type, data, row) {
                            return `<span class="font-size-sm" >${row.book_title}</span>`
                        }
                    },
                    {
                        data: "book_author",
                        className: 'text-center',
                        render: function(type, data, row) {
                            return `<span class="font-size-sm" >${row.book_author ?? 'Not Specified'}</span>`
                        }
                    },
                    {
                        data: "book_callnum",
                        className: 'text-center',
                        render: function(type, data, row) {
                            return `<a class="font-size-sm" >${row.book_callnum ?? 'Not Specified'}</a>`
                        }
                    },
                ],
            });
        }
    </script>
@endsection
