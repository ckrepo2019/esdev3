@extends('finance.layouts.app')

<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
@section('content')
	<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <!-- <h1>Finance</h1> -->
          
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Salary Rate Elevation</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <section class="content pt-0">
    <div class="row">
      <div class="col-md-12">
        <div class="main-card card">
      		<div class="card-header text-lg bg-info">
            <div class="row">
              <div class="col-md-8">
                <!-- Expenses -->
                <h4 class="text-warning" style="text-shadow: 1px 1px 1px gray">
            <!-- <i class="fa fa-chart-line nav-icon"></i>  -->
            <b>SALARY RATE ELEVATION</b></h4>
              </div>
            </div>
      		</div>
          <div class="card-body">
            <div class="row">
                <div class="col-sm-12">
                    <table id="example1"  class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                        <thead>
                            <tr>
                                {{-- <th>#</th> --}}
                                <th>Employee</th>
                                <th>Previous Salary Rate</th>
                                <th>Proposed Salary Rate</th>
                                <th>Requested Date</th>
                                {{-- <th>Requested By</th> --}}
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rateelevation as $rateelev)
                                @if($rateelev->status == 0)                           
                                    <tr id="{{$rateelev->teacherid}}">                        
                                @elseif($rateelev->status == 1)                        
                                    <tr style="background-color: #c2f0c2" id="{{$rateelev->teacherid}}">                           
                                @elseif($rateelev->status == 2)                         
                                    <tr style="background-color: #ffb3b3" id="{{$rateelev->teacherid}}">  
                                @endif 
                                    <td>
                                        {{$rateelev->firstname}} {{$rateelev->middlename[0]}}. {{$rateelev->lastname}} {{$rateelev->suffix}}
                                        <br>
                                        <span class="text-muted">{{$rateelev->utype}}</span>
                                    </td>
                                    <td>&#8369; {{number_format($rateelev->oldsalary,2,'.',',')}}</td>
                                    <td>&#8369; {{number_format($rateelev->newsalary,2,'.',',')}}</td>
                                    <td>{{$rateelev->submitteddate}}</td>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <button type="button" class="btn btn-sm btn-success btn-block requestaction" action="approve" data-id="{{$rateelev->id}}"><i class="fa fa-check"></i></button>
                                            </div>
                                            <div class="col-md-6">
                                                <button type="button" class="btn btn-sm btn-danger btn-block requestaction" action="reject" data-id="{{$rateelev->id}}"><i class="fa fa-ban"></i></button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
          </div>
        </div>  
      </div>
    </div>
  </section>

@endsection
@section('js')
  <script type="text/javascript">

        $(function () {
            $("#example1").DataTable({
                pageLength : 10,
                lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Show All']]
            });
        })
        $('.requestaction').click(function() {
            var employeeid = $(this).closest('tr').attr('id');
            var action = $(this).attr('action');
            var id = $(this).attr('data-id');
            console.log(action)
            if(action == 'approve'){
                var message = "You are approving this request!";
                var messagebutton = "Approve";
                var successmessage = "Approved";
                var successmessagesub = "Selected request has been approved.";
            }else{
                var message = "You are rejecting this request!";
                var messagebutton = "Reject";
                var successmessage = "Rejected";
                var successmessagesub = "Selected request has been rejected.";
            }
            Swal.fire({
                title: 'Are you sure?',
                text: message,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: messagebutton,
                allowOutsideClick: false
                }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/finance/salaryrateelevation/'+action,
                        type:"GET",
                        dataType:"json",
                        data:{
                            employeeid: employeeid,
                            action: action,
                            id: id
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        complete: function(){
                            Swal.fire({
                                title: successmessage,
                                text: successmessagesub,
                                type: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK!',
                                allowOutsideClick: false
                            }).then((confirm) => {
                                if (confirm.value) {
                                    window.location.reload();
                                }
                            })
                        }
                    })
                }
            })
        });
  </script>
@endsection
