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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mark.js/8.11.1/mark.min.js"></script>

    <style>
        .hover-primary:hover {
            color: #007bff;
            cursor: pointer;
        }

        .highlight {
            background-color: yellow;
            /* You can customize the background color */
            font-weight: bold;
            /* You can customize the font weight or any other styles */
        }
    </style>
@endsection
@section('content')
    <div class="content">
        <!-- DETAIL MODAL -->
        <div class="modal fade" id="modal-block-fromleft" tabindex="-1" role="dialog" aria-labelledby="modal-block-fromleft"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-fromleft modal-xl" role="document">
                <div class="modal-content">
                    <div class="block block-rounded block-themed block-transparent mb-0">
                        <div class="block-header bg-primary-dark">
                            <h3 class="block-title modal-title-view">Book details</h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                    <i class="fa fa-fw fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <div class="block-content font-size-sm pb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="block">
                                        <div class="block-content">
                                            <img src="" alt="Cover Image" id="cover_img"
                                                style="height: 270px; width: 100%; object-fit: contain; object-position: center; ">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <strong>ISBN</strong>
                                    <p class="font-w600 text-primary" id="visbn"></p>
                                    <strong>Author</strong>
                                    <p class="text-muted" id="vauthor"></p>
                                    <strong>Publication</strong>
                                    <p class="text-muted" id="vpublisher"></p>
                                    <strong>Genre</strong>
                                    <p class="text-muted" id="vgenre"></p>
                                    <strong>Date Received</strong>
                                    <p class="text-muted" id="vdatereceived"></p>
                                </div>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Title</strong>
                                            <p class="text-muted" id="vtitle"></p>
                                            <strong>Edition</strong>
                                            <p class="text-muted" id="vedition"></p>
                                            <strong>Copyright</strong>
                                            <p class="text-muted" id="vcopyright"></p>
                                            <strong>Category</strong>
                                            <p class="text-muted" id="vcategory"></p>
                                            <strong>Call Number</strong>
                                            <p class="text-muted" id="vcallnumber"></p>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Price</strong>
                                            <p class="text-muted" id="vprice"></p>
                                            <strong>Quantity</strong>
                                            <p class="text-muted" id="vquantity"></p>
                                            <strong>Available</strong>
                                            <p class="text-muted" id="vavailable"></p>
                                            <strong>Description</strong>
                                            <p class="text-muted pr-1" id="vdescription"></p>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END DETAIL MODAL -->

        <!-- TABLE BOOK -->
        <div class="block block-rounded">
            <div class="block-header bg-primary">
                <h3 class="block-title font-w700" style="font-size: 16px"><i class="fa fa-book"></i> CATALOGING </h3>
                <div class="block-options d-flex align-items-center my-1">
                    <div class="mr-2">
                        <i class="si si-size-fullscreen font-w600" id="fullscreenButton" data-toggle="tooltip"
                            title="Fullscreen"></i>
                    </div>
                    <div class="mr-2">
                        <select class="form-control form-control-sm" id="select-genre" style="width:100%;">
                            <option value=""> Select Genre </option>
                            @foreach (DB::table('library_genres')->where('deleted', 0)->get() as $item)
                                <option value="{{ $item->id }}">{{ $item->genre_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <select class="form-control form-control-sm" id="select-category" style="width:100%;">
                            <option value=""> Select Category </option>
                            @foreach (DB::table('library_categories')->where('deleted', 0)->get() as $item)
                                <option value="{{ $item->id }}">{{ $item->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="block-content block-content-full">
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-borderless js-dataTable-full" id="tb_catalogue"
                        style="width: 100%;">
                        <thead>
                            <tr>
                                <th width="5%"></th>
                                <th width="20%"></th>
                                <th width="70%"></th>
                                <th hidden>Book Author</th>
                                <th width="5%"></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('js_after')
    {{-- <script src="{{ asset('js/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/buttons/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/buttons/buttons.print.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/buttons/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/buttons/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/buttons/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('js/pages/tables_datatables.js') }}"></script> --}}

    <script>
        $("#fullscreenButton").click();

        $(document).ready(function() {
            books();
            $("#fullscreenButton").on("click", function() {
                fullscreen();
            });
            $(document).on('click', '.view_book', function() {
                var id = $(this).attr('data-id');
                $.ajax({
                    type: 'GET',
                    data: {
                        id: id
                    },
                    url: '{{ route('get.masterlist.book') }}',
                    success: function(data) {
                        console.log(data)
                        $('#cover_img').attr('src', data.book_img).attr('onerror',
                            "this.onerror=null;this.src='{{ asset('assets/lms/lost.png') }}';"
                        );
                        $('#visbn').text(data.book_isbn ?? '--');
                        $('#vtitle').text(data.book_title ?? '--');
                        $('#vauthor').text(data.book_author ?? '--');
                        $('#vedition').text(data.book_edition ?? '--');
                        $('#vavailable').text(data.book_available ?? '--');
                        $('#vpublisher').text(data.book_publisher ?? '--');
                        $('#vgenre').text(data.genre_name ?? '--');
                        $('#vcategory').text(data.category_name ?? '--');
                        $('#vbranch').text(data.library_branch ?? '--');
                        $('#vdescription').text(data.book_description_short ?? '--');
                        $('#vdatereceived').text(data.book_received ?? '--');
                        $('#vcopyright').text(data.book_copyright ?? '--');
                        $('#vcallnumber').text(data.book_callnum ?? '--');
                        $('#vquantity').text(data.book_qty ?? '--');
                        $('#vprice').text(parseFloat(data.book_price ?? '--').toFixed(
                            2));

                        $('#modal-block-fromleft').modal();
                    }
                });
            });

            $(document).on('change', '#select-category', function() {
                // Get the selected text
                var selectedText = $(this).find('option:selected').text().toLowerCase();
                var svalue = $(this).val();
                books(svalue, $('#select-genre').val());
            });

            $(document).on('change', '#select-genre', function() {
                // Get the selected text
                var selectedText = $(this).find('option:selected').text().toLowerCase();
                var svalue = $(this).val();
                books($('#select-category').val(), svalue);
            });

            $(document).on('click', '.pin_book', function() {
                var id = $(this).attr('data-id');
                $.ajax({
                    type: 'GET',
                    data: {
                        userid: {{ auth()->user()->id }},
                        bookid: id
                    },
                    url: '{{ route('request.pinbook') }}',
                    success: function(data) {
                        console.log(data)
                        notify(data.status, data.message);
                    }
                })
            })

        })

        function fullscreen() {
            var element = document.documentElement;

            if (element.requestFullscreen) {
                element.requestFullscreen();
            } else if (element.mozRequestFullScreen) { // Firefox
                element.mozRequestFullScreen();
            } else if (element.webkitRequestFullscreen) { // Chrome, Safari and Opera
                element.webkitRequestFullscreen();
            } else if (element.msRequestFullscreen) { // IE/Edge
                element.msRequestFullscreen();
            }
        }

        function books(categoryid, genreid) {
            $.ajax({
                type: 'GET',
                data: {
                    genreid: genreid ?? null,
                    categoryid: categoryid ?? null,
                    action: 'getall'
                },
                url: '{{ route('lib.books') }}',
                success: function(data) {
                    console.log(data)
                    load_books_datatable(data);
                }
            });
        }

        function load_books_datatable(data) {
            var table = $('#tb_catalogue').DataTable({
                destroy: true,
                responsive: true,
                stateSave: true,
                language: {
                    emptyTable: "No Records Found"
                },
                data: data,
                lengthMenu: [
                    [5, 10, 15, 20],
                    [5, 10, 15, 20]
                ],
                autoWidth: !1,
                columns: [{
                        data: null,
                        orderable: false,
                        sortable: false,
                        render: function(data, type, row) {
                            var renderHtml =
                                ` <a type="button" class="view_book" data-id="${row.id}">
                                    <i class="si si-info"></i>
                                </a>`
                            return renderHtml;
                        }
                    },
                    {
                        data: "book_img",
                        sortable: false,
                        className: 'text-center align-middle',
                        render: function(data, type, row) {
                            var img =
                                `<img class="shadow my-2 align-self-center" src="/${row.book_img}" style="height:124px; width:110px;" onerror="this.onerror=null;this.src='{{ asset('assets/lms/lost.png') }}';" />`
                            return img;
                        }
                    },
                    {
                        data: "book_title",
                        className: 'align-middle',
                        render: function(data, type, row) {
                            var capitalizeFirstLetter = function(string) {
                                return string.toLowerCase().replace(/\b\w/g, function(match) {
                                    return match.toUpperCase();
                                });
                            };

                            var title =
                                `
                                <div> <a href="#" class="h4 view_book" data-id="${row.id}" >${row.book_title ? `${capitalizeFirstLetter(row.book_title)}`: '' }</a> </div>
                                <div class="mt-1"> <span class="font-size-sm text-primary">${row.book_author ? `${capitalizeFirstLetter(row.book_author)}` : ''} ${ row.book_copyright ? `- &copy;${capitalizeFirstLetter(row.book_copyright)}` : '' } </span> </div>
                                <div class="font-size-sm text-muted mt-3"> Available at <em class="text-info"> ${row.library_branch.length > 0 ? `${capitalizeFirstLetter(row.library_branch.join(','))}` : '' } </em> </div>
                                `;
                            return title;
                        }
                    },
                    {
                        data: "book_author",
                        visible: false
                    },
                    {
                        data: null,
                        sortable: false,
                        render: function(data, type, row) {
                            var renderHtml =
                                `<p class="d-flex"><i class="si si-pin mr-3 hover-primary ${ '{{ auth()->user()->type != 5 }}' ? 'pin_book' : ''}" data-id="${row.id}"></i><i class="fa fa-ellipsis-h hover-primary"></i></p>`
                            return renderHtml;
                        }
                    }
                ],

            });

            // Handle the search event to highlight results in the book title column
            $('#tb_catalogue_filter input').on('input', function() {
                var searchTerm = $(this).val().toLowerCase();

                var capitalizeFirstLetter = function(string) {
                    return string.toLowerCase().replace(/\b\w/g, function(match) {
                        return match.toUpperCase();
                    });
                };

                table.rows().every(function() {
                    var rowData = this.data();

                    // Clone the original HTML content to avoid direct manipulation
                    var originalTitle = capitalizeFirstLetter(rowData.book_title);
                    var originalAuthor = capitalizeFirstLetter(rowData.book_author);
                    var highlightedTitle = originalTitle.replace(new RegExp(searchTerm, 'gi'),
                        function(match) {
                            return '<span class="highlight">' + match + '</span>';
                        });
                    var highlightedAuthor = originalAuthor.replace(new RegExp(searchTerm,
                            'gi'),
                        function(match) {
                            return '<span class="highlight">' + match + '</span>';
                        });

                    var title =
                        `
                            <div> <a href="#" class="h4 view_book" data-id="${rowData.id}" >${ highlightedTitle }</a> </div>
                            <div class="mt-1"> <span class="font-size-sm text-primary"> ${highlightedAuthor} ${ rowData.book_copyright ? `- &copy;${capitalizeFirstLetter(rowData.book_copyright)}` : '' } </span> </div>
                            <div class="font-size-sm text-muted mt-3"> Available at <em class="text-info"> ${ rowData.library_branch.length > 0 ? `${capitalizeFirstLetter(rowData.library_branch.join(','))}` : '' } </em> </div>
                            `;

                    $('td:eq(2)', this.node()).html(title);
                });
            });
        }
    </script>
@endsection
