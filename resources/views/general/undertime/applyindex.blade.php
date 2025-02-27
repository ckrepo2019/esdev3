@extends($extends)

@section('headerjavascript')
    <link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css')}}">
    <!-- Bootstrap4 Duallistbox -->
    <link rel="stylesheet" href="{{asset('plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/fullcalendar/main.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/fullcalendar-daygrid/main.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/fullcalendar-timegrid/main.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/fullcalendar-bootstrap/main.min.css')}}">
    {{-- <link rel="stylesheet" href="{{asset('plugins/fullcalendar-interaction/main.min.css')}}"> --}}
    <!-- Ekko Lightbox -->
    <link rel="stylesheet" href="{{asset('plugins/ekko-lightbox/ekko-lightbox.css')}}">
    {{-- <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}"> --}}
    
<link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
<link href="{{asset('plugins/bootstrap-datepicker/1.2.0/css/datepicker.min.css')}}" rel="stylesheet">
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">

@endsection
@section('content')
<style>
    .thumb{
        /* margin: 10px 5px 0 0; */
        width: 100%;
    } 
    table td{
        padding: 2px !important;
    }
    .card{
        border: none;
    }
</style>

<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3>
                    Undertime Application
                </h3>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item active">Undertime Application</li>
            </ol>
            </div>
        </div>
    </div>
</section>
<section class="content-body">
    <div class="row">        
        <div class="col-md-4">
            @if(count($approvals)>0)
                <div class="info-box" style="box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; min-height: 40px;">
                    <div class="row">
                        <div class="col-md-12">
                            <h5>Approvals</h5>
                        </div>
                        <div class="col-md-12">
                            @foreach($approvals as $approval)
                                <small class="text-muted"> {{$approval->firstname}} {{$approval->lastname}},</small>
                            @endforeach
                        </div>
                    </div>
                </div>    
            @endif    
        </div>
        <div class="col-md-4">
            <div class="info-box" style="box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; min-height: 40px;">
                
            </div>        
        </div>
        <div class="col-md-4 text-right">
            <button type="button" class="btn btn-outline-primary" style="box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;" id="btn-apply-modal">Apply Undertime</button>
            {{-- <div class="info-box bg-info" style="box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; min-height: 50px;">
                    
            </div>         --}}
        </div>
    </div>
    <div class="card" style="box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
        {{-- <div class="card-header"></div> --}}
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-hover" id="example2" style="font-size: 12px;">
                        <thead>
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 25%;">Date Applied</th>
                                {{-- <th>Time</th> --}}
                                <th style="width: 25%;">Remarks</th>
                                <th style="width: 20%;">Date Submitted</th>
                                <th style="width: 20%;">Approvals</th>
                                <th style="width: 5%;">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($applications as $key=> $application)
                                <tr>
                                    <td style="vertical-align: top;">{{$key+1}}</td>
                                    <td style="vertical-align: top;">{{date('M d, Y',strtotime($application->udate))}} {{date('h:i A',strtotime($application->timefrom))}} - {{date('h:i A',strtotime($application->timeto))}}</td>
                                    <td style="vertical-align: top;">{{$application->remarks}}</td>
                                    <td style="vertical-align: top;">{{date('M d, Y h:i A',strtotime($application->createddatetime))}}</td>
                                    <td>
                                        <div class="row">
                                            @if(collect($application->approvals)->count() > 0)
                                                @foreach($application->approvals as $eachapproval)
                                                    <div class="col-md-12 mb-2" style="border: 1px solid #ddd; border-radius: 5px;">
                                                        <span class="badge @if($eachapproval->status == 0) badge-warning @elseif($eachapproval->status == 1) badge-success @elseif($eachapproval->status == 2) badge-danger  @endif">{{$eachapproval->firstname}} {{$eachapproval->lastname}}</span>
                                                        @if($eachapproval->status == 2)<br/><span>Reason for Disapproval: {{$eachapproval->reason}}</span>@endif
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-right" style="vertical-align: top;">
                                        @if(collect($application->approvals)->where('status','>',0)->count() == 0)
                                            {{-- <button type="button" class="btn btn-sm btn-outline-warning pt-0 pr-1 pb-0 pl-1"><i class="fa fa-edit"></i></button> --}}
                                            <button type="button" class="btn btn-sm btn-outline-danger pt-0 pr-1 pb-0 pl-1 btn-block each-delete-app" data-id="{{$application->id}}"><i class="fa fa-trash-alt"></i></button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="modal fade" id="modal-sm">
    <div class="modal-dialog modal-sm">
    <div class="modal-content">
    <div class="modal-header">
    <h4 class="modal-title">Small Modal</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
    </div>
    <div class="modal-body">
    <p>One fine body&hellip;</p>
    </div>
    <div class="modal-footer justify-content-between">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="button" class="btn btn-primary">Save changes</button>
    </div>
    </div>
    
    </div>
    
    </div> --}}
    <div class="modal fade" id="modal-apply">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Apply Undertime</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="/undertime/apply" method="GET" class="p-0 m-0">
                    @csrf
                    <input name="action"  value="apply" hidden/>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label>Date</label>
                                <input type="date" class="form-control" name="apply-date" id="apply-date" required/>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label>Time From</label>
                                <input type="time" class="form-control" name="apply-timefrom" id="apply-timefrom" required/>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label>Time To</label>
                                <input type="time" class="form-control" name="apply-timeto" id="apply-timeto" required/>
                            </div>
                            <div class="col-md-12">
                                <label>Remarks</label>
                                <textarea class="form-control" name="apply-remarks" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>        
        </div>        
    </div>
</section>
@endsection
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
@section('footerjavascript')
    <script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
    <!-- bootstrap color picker -->
    <script src="{{asset('plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js')}}"></script>
    <!-- Bootstrap4 Duallistbox -->
    <script src="{{asset('plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js')}}"></script>
    <script src="{{asset('plugins/fullcalendar/main.min.js')}}"></script>
    <script src="{{asset('plugins/fullcalendar-daygrid/main.min.js')}}"></script>
    <script src="{{asset('plugins/fullcalendar-timegrid/main.min.js')}}"></script>
    <script src="{{asset('plugins/fullcalendar-interaction/main.min.js')}}"></script>
    <script src="{{asset('plugins/fullcalendar-bootstrap/main.min.js')}}"></script>
    <script src="{{asset('plugins/ekko-lightbox/ekko-lightbox.min.js')}}"></script>
    <script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('plugins/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/jszip/jszip.min.js')}}"></script>
    <script src="{{asset('plugins/pdfmake/pdfmake.min.js')}}"></script>
    <script src="{{asset('plugins/pdfmake/vfs_fonts.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
    <!-- Filterizr-->
    {{-- <script src="{{asset('plugins/filterizr/jquery.filterizr.min.js')}}"></script> --}}
    <script type="text/javascript">
    
        $(document).ready(function(){
            
            $('#example2').DataTable({
                // "paging": false,
                // "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": false,
                "responsive": true
            });
            $('#btn-apply-modal').on('click', function(){
                $('#modal-apply').modal('show')
            })
            $('.each-delete-app').on('click', function(){
                var appid = $(this).attr('data-id');
                Swal.fire({
                    title: 'Deleting application...',
                    html: "Would you like to continue? <br/> You won't be able to revert this.",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Delete',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.value) {
                            
                            $.ajax({
                                url: '/undertime/apply',
                                type:"GET",
                                dataType:"json",
                                data:{
                                    action   :  'delete',
                                    appid   :  appid
                                },
                            // headers: { 'X-CSRF-TOKEN': token },,
                            success: function(data){
                                if(data == 1)
                                {
                                    toastr.success('Deleted successfully!')
                                    window.location.reload()
                                }else{
                                    toastr.error('Something went wrong!')
                                }
                            }
                        })
                    }else if(result.dismiss == 'cancel'){
                            thisselect.val(statorigval)
                            
                    }
                })
            })
        })
    </script>
@endsection