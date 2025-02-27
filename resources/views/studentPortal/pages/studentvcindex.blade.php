@extends('studentPortal.layouts.app2')
@section('content')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<style>
    /* .color-palette                  { display: block; height: 35px; line-height: 35px; text-align: left; padding-left: .75rem; }
    .color-palette.disabled         { text-align: center; padding-right: 0; display: block; }
    .color-palette-set              { margin-bottom: 15px; }
    .color-palette.disabled span    { display: block; text-align: left; padding-left: .75rem; }
    .color-palette-box h4           { position: absolute; left: 1.25rem; margin-top: .75rem; color: rgba(255, 255, 255, 0.8); font-size: 12px; display: block; z-index: 7; } */
</style>
<div>
    <nav class="" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="active breadcrumb-item" aria-current="page">Virtual Classrooms</li>
        </ol>
    </nav>
</div>
@if(count($classrooms)>0)
<div class="row">
    @foreach($classrooms as $classroom)
    <div class="col-lg-3 col-6">
        <!-- small card -->
        <div class="small-box bg-info">
          <div class="inner">
            {{-- <h3>150</h3> --}}

            <p>{{$classroom->classroomname}}</p>
          </div>
          <div class="icon">
            <i class="fas fa-users"></i>
          </div>
          
         <form action="/virtualclassroomvisit" method="get">
            @csrf
            <input type="hidden" name="classroomid" value="{{Crypt::encrypt($classroom->id)}}"/>
            <button type="submit" class="btn btn-warning btn-sm small-box-footer btn-block"> More info <i class="fas fa-arrow-circle-right"></i></button>
        </form>
          {{-- <a href="#" class="small-box-footer">
            More info <i class="fas fa-arrow-circle-right"></i>
          </a> --}}
        </div>
      </div>
    @endforeach
</div>
@endif
{{-- <div class="card">
    @if(count($classrooms)>0)
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4" >
                <div class="row">
                    <div class="col-sm-12">
                        <table id="example1" style="font-size: 12px" class="table table-bordered table-striped dataTable text-uppercase" role="grid" aria-describedby="example1_info">
                            <thead>
                                <tr>
                                    <th>Class Name</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($classrooms as $classroom)
                                    <tr>
                                        <td>{{$classroom->classroomname}}</td>
                                        <td>
                                            <form action="/virtualclassroomvisit" method="get">
                                                @csrf
                                                <input type="hidden" name="classroomid" value="{{Crypt::encrypt($classroom->id)}}"/>
                                                <button type="submit" class="btn btn-warning btn-sm">Visit</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div> --}}
<script type="text/javascript" src="{{asset('assets/scripts/main.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>
<script src="{{asset('assets/scripts/gijgo.min.js')}}" ></script>
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<script>
    $(document).ready(function(){
        
        $("#example1").DataTable({
            pageLength : 10,
            lengthMenu: ['Show All'],
            "paging"    : false,
            "bfilter": false
        });
    })
    
</script>
@endsection