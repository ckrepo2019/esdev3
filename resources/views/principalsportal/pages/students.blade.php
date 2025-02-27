@extends('principalsportal.layouts.app2')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{asset('css/pagination.css')}}">
@endsection()

@section('content')
<section class="content-header p-2">
    <div class="container-fluid">
    <div class="row">
        <div class="col-sm-6">
        
        <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000"><i class="nav-icon fas fa-child"></i> STUDENTS</h4>
        </div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item active">Students</li>
        </ol>
        </div>
    </div>
    </div>
</section>


<section class="content ">
    <!-- Default box -->
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline principalstudents ">
                <div class="card-header  border-0 bg-info">
                    <h3 class="card-title acadid pr-3">
                        <select class="form-control form-control-sm" id="acadid">
                            <option selected value="Crypt::encrypt(0)" disabled>Select Academic Program</option>
                            @foreach (App\Models\Principal\SPP_AcademicProg::getPrincipalAcadProg(Session::get('prinInfo')->id) as $item)
                                @if($item->id == 2)
                                    {{$studcount = Session::get('psstudentcount')}}
                                @elseif($item->id == 3)
                                    {{$studcount = Session::get('gsstudentcount')}}
                                @elseif($item->id == 4)
                                    {{$studcount = Session::get('jhstudentcount')}}
                                @elseif($item->id == 5)
                                    {{$studcount = Session::get('shstudentcount')}}
                                @endif
                                <option id="acadids" class="{{ $studcount > 0 ? 'text-success':'text-danger'}}" value="{{Crypt::encrypt($item->id)}}">{{$item->progname}} <p style="background-color:#FF0055;">( {{$studcount}} )</p></option>
                            @endforeach
                        </select>
                        
                    </h3>
                    <h3 class="card-title aglevels">
                        <select class="form-control form-control-sm" id="aglevels">
                            <option selected>Select Grade Level</option>
                        </select>
                        
                    </h3>
                    <div class="input-group input-group-sm w-25 float-right search">
                        <input type="text" id="search" name="table_search" class="form-control float-right" placeholder="Search">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                    
                </div>
                <div class="card-body p-0" id="studentholder">
                    @include('search.principal.student')
                </div>
                <div class="card-footer  pt-1 pb-1 pl-2  bg-white d-flex justify-content-center">
                    <div id="data-container"></div>
                </div>
            </div>
    </div>
</section>
    


@endsection

@section('footerjavascript')

    <script src="{{asset('js/pagination.js')}}"></script>

    <script>
        $(document).ready(function(){
            $(document).on('change','.acadid',function() {
                $.ajax({
                type:'GET',
                url:'/searchbygradelevel',
                data:{
                    data:$(this).val(),
                    apid:$('#acadid').val()
                    },
                    success:function(data) {
                        $('#aglevels').empty();
                        $('#aglevels').append('<option >Select Grade Level</option>')
                        $.each(data,function(key,value){
                            $('#aglevels').append('<option value='+value.id+'>'+value.levelname+'</option>')
                            
                        })
                    }
                })

            });

            $(document).on('change','.aglevels',function() {
                
                $.ajax({
                type:'GET',
                url:'/searchbygradelevelid',
                data:{
                    data:$(this).val(),
                    aglevel:$('#aglevels').val()
                    }
                })

            });
            
        })
    </script>
    <script>
        $(document).ready(function(){

            if($(window).width()<500){
                $('.search').addClass('w-100 mt-2')
                $('.acadid').addClass('w-100')
                $('.col-md-2, .col-md-8').addClass('mb-3')
                $('.search').removeClass('w-25')
            }

        pagination('{{$data[0]->count}}',false);

        function pagination(itemCount,pagetype){
            var result = [];
            for (var i = 0; i < itemCount; i++) {
            result.push(i);
            }
            $('#data-container').pagination({
            dataSource: result,
            pageSize: 6,
            hideWhenLessThanOnePage: true,
            callback: function(data, pagination) {
                if(pagetype){
                $.ajax({
                    type:'GET',
                    url:'/searchstudentajax',
                    data:{
                    data:$("#search").val(),
                    pagenum:pagination.pageNumber,
                    apid:$('#acadid').val()
                    },
                    success:function(data) {
                    $('#studentholder').empty();
                    $('#studentholder').append(data);
                    }
                })
                }
                pagetype=true;
            }
            })
        }

        $("#search" ).keyup(function() {
            $.ajax({
            type:'GET',
            url:'/searchstudentajax',
            data:{
                data:$(this).val(),
                pagenum:'1',
                apid:$('#acadid').val()
                },
            success:function(data) {
                $('#studentholder').empty();
                $('#studentholder').append(data);
                pagination($('#searchCount').val())
            }
            })
        });

            $(document).on('change','#acadid',function() {
                $.ajax({
                type:'GET',
                url:'/searchstudentajax',
                data:{
                    data:$("#search").val(),
                    pagenum:'1',
                    apid:$(this).val()
                    },
                success:function(data) {
                    $('#studentholder').empty();
                    $('#studentholder').append(data);
                    pagination($('#searchCount').val())
                }
                })
            });

            $(document).on('change','#aglevels',function() {
                $.ajax({
                    type:'GET',
                    url:'/searchstudentajax',
                    data:{
                            data:$("#search").val(),
                            pagenum:'1',
                            apid:$('#acadid').val(),
                            gl:$(this).val()
                        },
                    success:function(data) {
                        $('#studentholder').empty();
                        $('#studentholder').append(data);
                        pagination($('#searchCount').val())
                    }
                })
            });
        })
    </script>



@endsection
