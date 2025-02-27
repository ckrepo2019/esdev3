@extends('layouts.borrower')

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
       
        @php
            
            $userid= 0;
            $utype= DB::table('usertype')->where('id', auth()->user()->type)->value('utype');
            if(auth()->user()->type == 7){
                $userid = DB::table('studinfo')->where('userid', auth()->user()->id)->value('id');
            }else{
                $userid = DB::table('teacher')->where('userid', auth()->user()->id )->value('id');
            }


            $circulations = \DB::table('library_circulation')
                ->leftJoin('library_books', 'library_circulation.circulation_book_id', '=', 'library_books.id')
                ->join('library_status', 'library_circulation.circulation_status', '=', 'library_status.id')
                ->where('circulation_deleted', 0)
                ->where('circulation_members_id', $userid)
                ->where('circulation_utype', $utype)
                ->whereIn('circulation_status', [1, 2, 3, 4])
                ->select('library_circulation.*', 'library_books.book_title', 'library_status.status_name')
                ->get();

            foreach ($circulations as $item) {
                $item->circulation_due_date = new \DateTime($item->circulation_due_date);
                $item->circulation_due_date = $item->circulation_due_date->format('F d, Y');

                $item->circulation_date_borrowed = new \DateTime($item->circulation_date_borrowed);
                $item->circulation_date_borrowed = $item->circulation_date_borrowed->format('F d, Y');

                if ($item->circulation_date_returned) {
                    $item->circulation_date_returned = new \DateTime($item->circulation_date_returned);
                    $item->circulation_date_returned = $item->circulation_date_returned->format('F d, Y');
                }
            }

            $issued = $circulations->where('circulation_status', 1)->values();
            $borrowed = $circulations->where('circulation_status', 2)->values();
            $returned = $circulations->where('circulation_status', 3)->values();
            $lost = $circulations->where('circulation_status', 4)->values();

        @endphp

        <!-- Page Content -->
        <div class="content ">
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
                                        {{ auth()->user()->name }}</h3>
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
                                                {{ DB::table('usertype')->where('id', auth()->user()->type)->value('utype') }}
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
                            ->where('circulation_members_id', $userid)
                            ->where('circulation_utype', $utype)
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
                    <div class="block block-rounded">
                        <ul class="nav nav-tabs nav-tabs-alt justify-content-end" data-toggle="tabs" role="tablist">
                            <li class="nav-item mr-auto">
                                <a class="nav-link">
                                    <i class="si si-book-open"></i>
                                </a>
                            </li>
                            @foreach (['Returned', 'Lost', 'Issued', 'Borrowed'] as $tab)
                                <li class="nav-item">
                                    <a class="nav-link{{ $loop->last ? ' active' : '' }}"
                                        href="#tab_{{ strtolower($tab) }}">{{ $tab }}</a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="block-content tab-content pb-3">
                            @foreach (['Returned', 'Lost', 'Issued', 'Borrowed'] as $tab)
                                <div class="tab-pane fade{{ $loop->last ? ' show active' : '' }}"
                                    id="tab_{{ strtolower($tab) }}" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-borderless table-striped table-vcenter js-dataTable-full" id="tb_{{ strtolower($tab) }}"
                                            style="width: 100%;">
                                            <thead class="bg-primary text-light">
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
                            @endforeach
                        </div>
                    </div>
                    <div class="block block-rounded">
                        <div class="block-header">
                            <h3 class="block-title text-warning"><i class="si si-pin mr-2 text-dark"></i>Requested books</h3>
                        </div>
                        <div class="block-content pb-4">
                            <div class="row">
                                @foreach (
                                    DB::table('library_requested_books')
                                    ->where('requested_deleted', 0) 
                                    ->where('requested_userid', auth()->user()->id) 
                                    ->join('library_books', 'library_requested_books.requested_bookid', '=', 'library_books.id')
                                    ->select(
                                        'library_requested_books.*',
                                        'library_books.book_img',
                                        'library_books.book_title',
                                    )
                                    ->get() as $item)
                                    <div class="col-md-4 col-sm-6">
                                        <div class="m-1 position-relative image-wrap">
                                            <p class="text-center font-size-sm font-w500" style="white-space: nowrap;">{{ $item->book_title }}</p>
                                            <div class="d-flex justify-content-center align-items-center position-relative">
                                                <i class="far fa-trash-alt text-light p-2 bg-danger trash-icon" data-id="{{ $item->id }}" data-toggle="tooltip" title="Remove"
                                                    style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"></i>
                                                <img class="shadow" src="{{ asset($item->book_img ?? 'books/default.png') }}"
                                                    style="height:150px; width:130px;" />
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
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

        console.log(borrowed)
    
        $(document).ready(function() {
            loadDataTable('tb_borrowed', borrowed);
            loadDataTable('tb_issued', issued);
            loadDataTable('tb_lost', lost);
            loadDataTable('tb_returned', returned);
    
            $('.trash-icon').on('click', function() {
                var id = $(this).data('id');
                var trashIcon = $(this);
                trashIcon.tooltip('dispose');
    
                $.ajax({
                    type: 'GET',
                    data: { id: id },
                    url: '{{ route('delete.pin') }}',
                    success: function(data) {
                        notify(data.status, data.message);
                        trashIcon.closest('.image-wrap').remove();
                    },
                });
            });
        });
    
        function loadDataTable(tableId, data) {
            $('#' + tableId).DataTable({
                autowidth: false,
                destroy: true,
                responsive: true,
                stateSave: true,
                data: data,
                language: { emptyTable: 'No Records Found' },
                columns: [
                    { data: 'book_title', render: titleRenderer },
                    { data: 'circulation_date_borrowed', className: 'text-center', render: dateRenderer },
                    { data: 'circulation_due_date', className: 'text-center', render: dateRenderer },
                    { data: 'circulation_date_returned', className: 'text-center', render: dateRenderer },
                    { data: 'circulation_penalty', className: 'text-center', render: penaltyRenderer },
                    { data: 'status_name', className: 'text-right', render: statusRenderer },
                ],
            });
        }
    
        function titleRenderer(type, data, row) {
            return `<span class="font-size-sm font-w500" style="white-space:nowrap;">${capitalizeFirstLetter(row.book_title)}</span>`;
        }
    
        function dateRenderer(type, data, row) {
            return `<span class="font-size-sm" style="white-space:nowrap;">${row.circulation_date_borrowed}</span>`;
        }
    
        function penaltyRenderer(data, type, row) {
            var penalty = parseFloat(row.circulation_penalty).toFixed(2);
            return `<span class="font-size-sm font-w600 text-danger">₱${penalty}</span>`;
        }
    
        function statusRenderer(type, data, row) {
            return `<span class="font-size-sm">${row.status_name}</span>`;
        }
    
        function capitalizeFirstLetter(string) {
            return string.toLowerCase().replace(/\b\w/g, match => match.toUpperCase());
        }
    </script>
    
@endsection
