@extends('admission.layouts.test')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <style>
        .uppercase {
            text-transform: uppercase;
        }
    </style>
@endsection


@section('content')
    <!-- MODAL POOLING -->
    <div class="modal fade" id="modalPooling" tabindex="-1" role="dialog" aria-labelledby="modalPooling" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content shadow">
                <div class="modal-header">
                    <h5 class="modal-title" style="text-align: center; width: 100%">Welcome To Admission</h5>
                    {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>×</span>
                    </button> --}}
                </div>
                <div class="modal-body">
                    <div class="row justify-content-center">
                        <div class="form-group">
                            <input type="text" class="form-control" onkeyup="this.value = this.value.toUpperCase();"
                                id="poolingnumber" name="poolingnumber" placeholder="Enter Pooling Number" required
                                style="text-align: center">
                            <span class="invalid-feedback" role="alert">
                                <strong>Pooling Number is required!</strong>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    {{-- <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button> --}}
                    <button type="submit" class="btn btn-primary" id="submit_pooling"><i
                            class="far fa-paper-plane mr-1"></i>ENTER</button>
                </div>
            </div>
        </div>
    </div>

    <div class="content-header">
        <div class="container">
            <div class="card shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            @if ($studinfo)
                                <h3>{{ $studinfo->lname }}, {{ $studinfo->fname }}</h3>
                                <p>Level to Enroll: <strong> {{ $studinfo->levelname }}</strong></p>
                            @endif
                        </div>
                        <div class="col-md-6 text-right">
                            @if ($studinfo)
                                <h3> CODE:<strong class="text-warning"> {{ $poolingnumber }}</strong></h3>
                                <div>
                                    <p>Desired Course: <strong> {{ $studinfo->courseDesc }}</strong></p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @if (session('status') && session('message'))
                    <div class="card-footer pb-0">
                        <div class="alert alert-{{ session('status') == 'error' ? 'danger' : 'success' }}">
                            {{ session('message') }}
                        </div>
                    </div>
                @endif
            </div>
        </div><!-- /.container-fluid -->
    </div>

    <div class="content">
        <div class="container">
            <div class="card shadow">
                <div class="card-header">
                    <p class="card-title"><strong>Instruction:</strong> Select test below to start.</p>
                </div>
                <div class="card-body ">
                    <div class="row justify-content-center mt-4">
                        @if (count($subjects) > 0)
                            @foreach ($subjects as $item)
                                <div class="col-lg-4 col-md-6">
                                    <div class="card shadow {{ $item->isfinished ? 'bg-gray' : 'bg-gray' }}"
                                        style="height: 190px;">
                                        <div class="card-body" style="overflow: hidden;">
                                            <h4 class="text-center uppercase"> {{ $item->examTitle }} </h4>
                                            <p class="text-center text-sm"> Examdate: {{ $studinfo->examdate }} </p>
                                        </div>
                                        <div class="card-footer">
                                            <button type="button" data-pool="{{ $poolingnumber }}"
                                                data-id="{{ $item->examtitleId }}"
                                                data-msg="{{ $studinfo->examdateStat }}"
                                                data-stat="{{ $studinfo->examdate_stat }}"
                                                class="btn btn-block btn-default btn_test"
                                                {{ $item->isfinished ? 'disabled' : null }}>
                                                <i class="fas text-success fa-check mr-1"
                                                    {{ $item->isfinished ? '' : 'hidden' }}></i>
                                                {{ $item->category_timelimit_hrs }} hrs
                                                {{ $item->category_timelimit_min }}
                                                min <i class="fas fa-arrow-circle-right ml-1"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="mt-3">
                                <h3 class="text-center">No Available Test!</h3>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footerjavascript')
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        var subjects = {!! json_encode($subjects) !!}
        $(document).ready(function() {
            console.log(subjects);
            $('.btn_test').on('click', function() {
                var id = $(this).data('id')
                var examStat = $(this).data('stat')
                var msg = $(this).data('msg')
                var poolingnumber = $(this).data('pool')

                if (examStat == 'expired') {
                    Swal.fire({
                        type: 'warning',
                        title: msg,
                        text: `The exam date has expired! `,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Okay'
                    })
                    return false;
                } else if (examStat == 'soon') {
                    Swal.fire({
                        type: 'warning',
                        title: msg,
                        text: `Please wait for the exam date to start! `,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Okay'
                    })
                    return false;
                }

                Swal.fire({
                    type: 'warning',
                    title: 'Are you sure you want to take the test now ?',
                    text: `Theres no going back when the test starts! `,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Start'
                }).then((result) => {
                    if (result.value) {
                        window.location.href =
                            `/admission/gototest?id=${id}&poolingnumber=${poolingnumber}`;
                        // $.ajax({
                        //     type: 'GET',
                        //     url: `/admission/gototest?id=${id}&poolingnumber=${poolingnumber}`,
                        //     data: {
                        //         id: id,
                        //         poolingnumber: poolingnumber
                        //     },
                        //     success: function(response) {
                        //         console.log(response);
                        //         notify(response.status, response.message);
                        //         if (response.status == "success") {

                        //         }
                        //     }
                        // })
                    }
                });

            })

        })
    </script>
@endsection
