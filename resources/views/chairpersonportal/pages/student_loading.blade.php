@extends('chairpersonportal.layouts.app2')


@section('headerscript')
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
     
@endsection

@section('content')
      <section class="content-header">
            <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                  <h1>Schedule Coding</h1>
                </div>
                <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">Schedule Coding</li>
                </ol>
                </div>
            </div>
            </div>
      </section>
      <div class="container-fluid">
            <div class="row">
                  <div class="col-md-12" id="schedule_holder">
                  
                  </div>
            </div>
      </div>
@endsection


@section('footerjavascript')

      <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
	<script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
	<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
	<script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>



      <script>
            $(document).ready(function(){
            
                  var studid = @json($studid);
                  get_schedulecoding()

           

                  function get_schedulecoding(){
                        console.log("sdfsf")
                        $.ajax({
                              type:'GET',
                              url: '/registrar/college/student/loading/view',
                              data:{
                                    studid:studid,
                              },
                              success:function(data) {
                                    $('#schedule_holder').append(data);
                                    
                              }
                        })
                  }


            })
      </script>

@endsection
