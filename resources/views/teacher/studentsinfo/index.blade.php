
@extends('teacher.layouts.app')

@section('content')
<div>
    <nav class="" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="active breadcrumb-item">Students Information</li>
            <li class="active breadcrumb-item" aria-current="page">Advisory</li>
        </ol>
    </nav>
</div>
<div class="card" style="border: none;">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <label>Select S.Y.</label>
                <select class="form-control" id="select-syid">
                    @foreach(collect(DB::table('sy')->get())->sortByDesc('sydesc')->values() as $eachsy)
                        <option value="{{$eachsy->id}}" @if($eachsy->isactive == 1) selected @endif>{{$eachsy->sydesc}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Select Semester</label>
                <select class="form-control" id="select-semid">
                    @foreach(DB::table('semester')->get() as $eachsemester)
                        <option value="{{$eachsemester->id}}" @if($eachsemester->isactive == 1) selected @endif>{{$eachsemester->semester}}</option>
                    @endforeach
                </select>
            </div>
            {{-- <div class="col-md-3" style="vertical-align: bottom;">
                <select class="form-control" id="select-semid">
                    @foreach(DB::table('semester')->get() as $eachsemester)
                        <option value="{{$eachsemester->id}}" @if($eachsemester->isactive == 1) selected @endif>{{$eachsemester->semester}}</option>
                    @endforeach
                </select>
            </div> --}}
            <div class="col-md-6 text-right">
                {{-- <label>&nbsp;</label><br/>
                <button type="button" class="btn btn-primary" id="btn-generate"><i class="fa fa-sync"></i> Generate</button> --}}
            </div>
        
        </div>
    </div>
</div>
<div id="results-container"></div>
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
@endsection
@section('footerscripts')
<script>
    $(document).ready(function(){
        function filter()
        {
            
            Swal.fire({
                title: 'Fetching data...',
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
                allowOutsideClick: false
            })

            $.ajax({
                url: '/students/advisory',
                type: 'GET',
                data: {
                    action                  : 'getsections',
                    syid                    : $('#select-syid').val(),
                    semid                    : $('#select-semid').val(),
                    action                  : 'filter'
                },
                success:function(data){
                    $('#results-container').empty();
                    $('#results-container').append(data)
                    $(".swal2-container").remove();
                    $('body').removeClass('swal2-shown')
                    $('body').removeClass('swal2-height-auto')
                    
                }
            })
        }
        filter()
        $('#select-syid').on('change', function(){
            filter()
        })
        $('#select-semid').on('change', function(){
            filter()
        })

        $('#btn-generate').on('click', function(){
            
        })
    })
</script>
@endsection
