@extends(''.$extends.'')
@section('content')

  <!-- Ekko Lightbox -->
  <link rel="stylesheet" href="{{asset('plugins/ekko-lightbox/ekko-lightbox.css')}}">
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
<style>
.gallery{margin: 10px 0;display: block;}
.imgdiv
{
    position: relative;
    width: 100px;
    height: 115px;
}
img{
        border-radius: 0% !important;
    }
.imgdiv img{width: 100%;height: 115px;}
div.ekko-lightbox-container{height: 475px !important; }
.ekko-lightbox.modal.fade.in div.modal-dialog{
  max-width:40% !important;
  /* height: 475px */
;}
.img-fluid{
    height: 100% !important;
}
</style>
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-md-12">
            <h3 class="page-title">Overtime Application</h3>
            <ul class="breadcrumb col-md-12">
                <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                <li class="breadcrumb-item active">Overtime Application</li>
            </ul>
            {{-- <div class="col-md-2 float-right ml-auto">
                <a href="#" class="btn btn-block" data-toggle="modal" data-target="#add_leave"><i class="fa fa-plus"></i> Add Overtime</a>
            </div> --}}
        </div>
    </div>
</div>
{{-- <div class="row">
  <div class="col-md-12">
      <div class="alert alert-warning alert-dismissible">
          <h5><i class="icon fas fa-exclamation"></i> Alert!</h5>
          This page is under maintenance.
      </div>
  </div>
</div> --}}
<button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#requestovertime">Request for overtime</button>
<div class="modal fade" id="requestovertime" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <form enctype="multipart/form-data" action="/applyovertimerequest" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Request for overtime</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <strong>Date from - Date to</strong>
                    <input type="text" class="form-control" id="reservation" name="overtimeon" required>
                    {{-- <strong>Date</strong>
                    <input type="text" class="currentDate form-control" id="currentDate" name="overtimeon"/> --}}
                    <br>
                    <br>
                    <span><strong>Total number of hours</strong></span>
                    <br>
                    <input type="number" class="form-control" name="numberofhours" required>
                    <br>
                    <strong>Reasons for overtime</strong>
                    <textarea {{-- id="compose-textarea" --}} class="form-control" name="remarks" style="height: 200px" required></textarea>
                    <br>
                    <strong>Attachments</strong>
                    <br>
                    <em class="text-success">
                        <small>Files supported: JPG, PNG, GIF or WebP files.</small>
                    </em>
                    <br>
                    <input type="file" id="profileImage" name="attachments[]" class="form-control form-control-sm" multiple accept="image/jpeg,image/gif,image/png,application/pdf" />
                    <div id="preview_img"></div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default btncancel" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Request</button>
                </div>
            </div>
        </form>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<br>&nbsp;
@if(isset($message))
    <div class="alert alert-warning alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-ban"></i> Alert!</h5>
        {{$message}}
    </div>
@endif
@if(session()->has('messageAdd'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-check"></i> Alert!</h5>
        {{ session()->get('messageAdd') }}
    </div>
@endif
@if(session()->has('messageExists'))
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-ban"></i> Alert!</h5>
        {{ session()->get('messageExists') }}
    </div>
@endif
<div class="card">
    {{-- <div class="card-header">
        <h3 class="card-title">My Overtme</h3>
    </div> --}}
    <div class="card-body">
        <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4" style="overflow: scroll">
            <div class="row">
                <div class="col-sm-12">
                    <table id="example1" style="font-size: 13px;" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                        <thead>
                            <tr>
                                <th style="width: 70%;">Details</th>
                                {{-- <th style="width:15%">Date Submitted</th>
                                <th style="width:12%">From</th>
                                <th style="width:12%">To</th>
                                <th style="width:20%">Remarks</th> --}}
                                {{-- <th>Attachments</th> --}}
                                <th >Status</th>
                                <th >Actions</th>
                                {{-- <th style="width:10%">Actions</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                                @foreach ($overtimeapplications as $overtimeapplication)
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <div class="col-4">
                                                    <p>Date submitted</p>
                                                </div>
                                                <div class="col-8">
                                                    : {{$overtimeapplication->createddatetime}}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-4">
                                                    <p>From</p>
                                                </div>
                                                <div class="col-8">
                                                    : {{$overtimeapplication->datefromdisplay}}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-4">
                                                    <p>To</p>
                                                </div>
                                                <div class="col-8">
                                                    : {{$overtimeapplication->datetodisplay}}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-4">
                                                    <p>No. of Hours</p>
                                                </div>
                                                <div class="col-8">
                                                    : {{$overtimeapplication->numofhours}}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-4">
                                                    <p>Reason</p>
                                                </div>
                                                <div class="col-8">
                                                    : {{$overtimeapplication->reason}}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-4">
                                                    <p>Attachments</p>
                                                </div>
                                                <div class="col-8">
                                                    :
                                                    @if(count($overtimeapplication->attachments) == 0)
                                                    <center>--------------</center>
                                                    @else
                                                        @foreach($overtimeapplication->attachments as $attachments)
                                                            <a  href="{{asset($attachments->picurl)}}" data-toggle="lightbox" data-title="Attachments" style="display: inline;width: 25% !important;">
                                                                <img src="{{asset($attachments->picurl)}}" class="mb-2 attachmentimg" alt="white sample" style="width: 10% !important;" style="display: inline;"/>
                                                            </a>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        {{-- <td>{{$overtimeapplication->createddatetime}}</td>
                                        <td> {{$overtimeapplication->datefromdisplay}}</td>
                                        <td> {{$overtimeapplication->datetodisplay}}</td>
                                        <td class="tdreason text-uppercase">
                                            {{$overtimeapplication->reason}}
                                        </td> --}}
                                        {{-- <td class="tdreason text-uppercase"> --}}
                                            {{-- @if(count($overtimeapplication->attachments) == 0)
                                            <center>--------------</center>
                                            @else
                                                @foreach($overtimeapplication->attachments as $attachments)
                                                    <a  href="{{asset($attachments->picurl)}}" data-toggle="lightbox" data-title="Attachments" style="display: inline;width: 25% !important;">
                                                        <img src="{{asset($attachments->picurl)}}" class="mb-2 attachmentimg" alt="white sample" style="width: 30% !important;" style="display: inline;"/>
                                                    </a>
                                                @endforeach
                                            @endif --}}
                                        {{-- </td> --}}
                                        <td>
                                            @if($overtimeapplication->status == '2')
                                                <span class="right badge badge-warning col-12"><strong>Pending</strong></span>
                                            @elseif($overtimeapplication->status == '3')
                                                <span class="right badge badge-danger col-12">Disapproved</span>
                                            @elseif($overtimeapplication->status == '1')
                                                <span class="right badge badge-success col-12">Approved</span>
                                            @endif
                                            <br/>
                                            <br/>
                                            @if(count($overtimeapplication->approvals)>0)
                                                @foreach ($overtimeapplication->approvals as $approve)
                                                    @if($approve->status == 1)
                                                    <span class="right badge badge-success"><i class="fa fa-check"></i></span>
                                                    {{-- @elseif($approve->status == 2) --}}
                                                    @elseif($approve->status == 3)
                                                    <span class="right badge badge-danger"><i class="fa fa-times"></i></span>
                                                    @else
                                                    <span class="right badge badge-warning"> . . .</span>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </td>
                                        <td class="m-0">
                                            @if($overtimeapplication->status == '2')
                                                <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editrequest{{$overtimeapplication->id}}"><i class="fa fa-edit"></i></button>
                                                <div class="modal fade" id="editrequest{{$overtimeapplication->id}}" style="display: none;" aria-hidden="true">
                                                    <div class="modal-dialog modal-md">
                                                        <form action="/applyovertimeupdate/{{Crypt::encrypt('edit')}}" method="get">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">Edit Request</h4>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">×</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <input type="hidden" value="{{$overtimeapplication->id}}" name="requestid"/>
                                                                    <br>
                                                                    <label>Date</label>
                                                                    <input type="text" class="form-control" value="{{$overtimeapplication->datefrom}} - {{$overtimeapplication->dateto}}" id="editreservation" name="editdaterequest" required>
                                                                    <br>
                                                                    <label>Total number of hours</label>
                                                                    <input type="text" class="form-control" value="{{$overtimeapplication->numofhours}}" name="editnumberofhours"/>
                                                                    <br>
                                                                    <label>Remarks</label>
                                                                    <textarea id="editcompose-textarea" class="form-control" name="editremarks" style="height: 300px" required>{{$overtimeapplication->reason}}</textarea>
                                                                </div>
                                                                <div class="modal-footer justify-content-between">
                                                                    <button type="button" class="btn btn-default btncancel" data-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn btn-warning"><strong>Update Request</strong></button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                      <!-- /.modal-content -->
                                                    </div>
                                                </div>
                                                <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleterequest{{$overtimeapplication->id}}"><i class="fa fa-trash"></i></button>
                                                <div class="modal fade" id="deleterequest{{$overtimeapplication->id}}" style="display: none;" aria-hidden="true">
                                                    <div class="modal-dialog modal-md">
                                                        <form action="/applyovertimeupdate/{{Crypt::encrypt('delete')}}" method="get">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">Delete Request</h4>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">×</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <input type="hidden" value="{{$overtimeapplication->id}}" name="requestid"/>
                                                                    <p>
                                                                        Are you sure you want to delete this request?
                                                                    </p>
                                                                </div>
                                                                <div class="modal-footer justify-content-between">
                                                                    <button type="button" class="btn btn-default btncancel" data-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                      <!-- /.modal-content -->
                                                    </div>
                                                </div>
                                            @elseif($overtimeapplication->status == '3')
                                            @elseif($overtimeapplication->status == '1')
                                            @endif
                                        </td>
                                        {{-- <td></td> --}}
                                    </tr>
                                @endforeach
                        </tbody>
                    </table>
                    {{-- <table id="example1" style="font-size: 13px;" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                        <thead class="text-center">
                            <tr>
                                <th style="width:20%">Overtime Date</th>
                                <th style="width:15%">Total number<br>of<br>hours</th>
                                <th style="width:30%">Reasons for overtime</th>
                                <th style="width:10%">Attachments</th>
                                <th style="width:10%">Status</th>
                                <th style="width:10%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($overtimeapplications as $overtimeapplication)
                                <tr>
                                    <td>
                                        <center>{{$overtimeapplication->datefromdisplay}} - {{$overtimeapplication->datetodisplay}}</center>
                                    </td>
                                    <td><center>{{$overtimeapplication->numofhours}} </center></td>
                                    <td class="tdreason">
                                        {{$overtimeapplication->reason}}
                                    </td>
                                    <td>
                                        @if(count($overtimeapplication->attachments) == 0)
                                        <center>--------------</center>
                                        @else
                                            @foreach($overtimeapplication->attachments as $attachments)
                                                <a  href="{{asset($attachments->picurl)}}" data-toggle="lightbox" data-title="Attachments" style="display: inline;width: 25% !important;">
                                                    <img src="{{asset($attachments->picurl)}}" class="mb-2 attachmentimg" alt="white sample" style="width: 30% !important;" style="display: inline;"/>
                                                </a>
                                            @endforeach
                                        @endif
                                    </td>
                                    @if($overtimeapplication->status == '2')
                                    <td>
                                            <button type="button" class="btn btn-block btn-warning btn-sm"><strong>Pending</strong></button>
                                    </td>
                                    <td class="m-0">
                                            <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editrequest{{$overtimeapplication->id}}"><i class="fa fa-edit"></i></button>
                                            <div class="modal fade" id="editrequest{{$overtimeapplication->id}}" style="display: none;" aria-hidden="true">
                                                <div class="modal-dialog modal-md">
                                                    <form action="/applyovertimeupdate/{{Crypt::encrypt('edit')}}" method="get">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Edit Request</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">×</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <input type="hidden" value="{{$overtimeapplication->id}}" name="requestid"/>
                                                                <br>
                                                                <label>Date</label>
                                                                <input type="text" class="form-control" value="{{$overtimeapplication->datefrom}} - {{$overtimeapplication->dateto}}" id="editreservation" name="editdaterequest" required>
                                                                <br>
                                                                <label>Total number of hours</label>
                                                                <input type="text" class="form-control" value="{{$overtimeapplication->numofhours}}" name="editnumberofhours"/>
                                                                <br>
                                                                <label>Remarks</label>
                                                                <textarea id="editcompose-textarea" class="form-control" name="editremarks" style="height: 300px" required>{{$overtimeapplication->reason}}</textarea>
                                                            </div>
                                                            <div class="modal-footer justify-content-between">
                                                                <button type="button" class="btn btn-default btncancel" data-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-warning"><strong>Update Request</strong></button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <!-- /.modal-content -->
                                                </div>
                                            </div>
                                            <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleterequest{{$overtimeapplication->id}}"><i class="fa fa-trash"></i></button>
                                            <div class="modal fade" id="deleterequest{{$overtimeapplication->id}}" style="display: none;" aria-hidden="true">
                                                <div class="modal-dialog modal-md">
                                                    <form action="/applyovertimeupdate/{{Crypt::encrypt('delete')}}" method="get">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Delete Request</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">×</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <input type="hidden" value="{{$overtimeapplication->id}}" name="requestid"/>
                                                                <br>
                                                                <h5>Are you sure you want to delete this request?</h5><br>
                                                                <h5>
                                                                <label>Date: </label>
                                                                <span class="text-danger">{{$overtimeapplication->datefromdisplay}} - {{$overtimeapplication->datetodisplay}}</span>
                                                                <br>
                                                                <label>Number of Hours: </label>
                                                                <span class="text-danger">{{$overtimeapplication->numofhours}} hour/s</span>
                                                                </h5>
                                                            </div>
                                                            <div class="modal-footer justify-content-between">
                                                                <button type="button" class="btn btn-default btncancel" data-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-danger">Delete</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <!-- /.modal-content -->
                                                </div>
                                            </div>
                                    </td>
                                    @elseif($overtimeapplication->status == '3')
                                        <td colspan="2">
                                            <button type="button" class="btn btn-block btn-secondary btn-sm"><strong>Disapproved by {{$overtimeapplication->updatedby}}</strong></button>
                                        </td>
                                    @elseif($overtimeapplication->status == '1')
                                        <td colspan="2">
                                            <button type="button" class="btn btn-block btn-success btn-sm"><strong>Approved by<br>{{$overtimeapplication->updatedby}}</strong></button>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table> --}}
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{asset('assets/scripts/main.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>
<script src="{{asset('assets/scripts/gijgo.min.js')}}" ></script>
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<!-- InputMask -->
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
<!-- date-range-picker -->
<script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
<!-- Ekko Lightbox -->
<script src="{{asset('plugins/ekko-lightbox/ekko-lightbox.min.js')}}"></script>
<script>

// $('#currentDate').datepicker({
//             format: 'yyyy-mm-dd',
//             value: '{{$currentdate}}'
//             });
    $(function () {

    // if()
        $('#reservation').daterangepicker({
            locale: {
                format: 'YYYY-MM-DD'
            }
        });
        $('#editreservation').daterangepicker({
            locale: {
                format: 'YYYY-MM-DD'
            }
        });
    });
// $('#editdate').datepicker({
//             format: 'yyyy-mm-dd',
//             });
    $(document).on('click','.btncancel', function(){
        window.location.reload();
    })
    window.setTimeout(function () {
        $(".alert-success").fadeTo(500, 0).slideUp(500, function () {
            $(this).remove();
        });
    }, 5000);
    window.setTimeout(function () {
        $(".alert-danger").fadeTo(500, 0).slideUp(500, function () {
            $(this).remove();
        });
    }, 5000);
   $(document).ready(function(){
    $('body').addClass('sidebar-collapse')
    $(function () {
        $("#example1").DataTable({
            // pageLength : 10,
            // lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Show All']]
            "bPaginate": false,
            "bInfo" : false
        });
    })
    $(function () {
        $('.editreason').summernote({
            height: 200,
            toolbar: [
                [ 'style', [ 'style' ] ],
                [ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
                [ 'fontname', [ 'fontname' ] ],
                [ 'fontsize', [ 'fontsize' ] ],
                [ 'color', [ 'color' ] ],
                [ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
                [ 'table', [ 'table' ] ],
                [ 'insert', [ 'link'] ],
                [ 'view', [ 'undo', 'redo', 'fullscreen', 'help' ] ]
            ]
        });
        $('.compose-textarea-table').summernote({
            
            toolbar: []
        });
        $('#compose-textarea').summernote({
            height: 200,
            toolbar: [
                [ 'style', [ 'style' ] ],
                [ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
                [ 'fontname', [ 'fontname' ] ],
                [ 'fontsize', [ 'fontsize' ] ],
                [ 'color', [ 'color' ] ],
                [ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
                [ 'table', [ 'table' ] ],
                [ 'insert', [ 'link'] ],
                [ 'view', [ 'undo', 'redo', 'fullscreen', 'help' ] ]
            ]
        })
        $('#editcompose-textarea').summernote({
            height: 200,
            toolbar: [
                [ 'style', [ 'style' ] ],
                [ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
                [ 'fontname', [ 'fontname' ] ],
                [ 'fontsize', [ 'fontsize' ] ],
                [ 'color', [ 'color' ] ],
                [ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
                [ 'table', [ 'table' ] ],
                [ 'insert', [ 'link'] ],
                [ 'view', [ 'undo', 'redo', 'fullscreen', 'help' ] ]
            ]
        })
    })
   })
   
   $(document).ready(function() {
        $('#profileImage').on('change', function(){
            if (window.File && window. FileReader && window.FileList && window.Blob){
                var data = $(this)[0].files;
                $.each(data, function(index,file){
                    if(/ (\.|\/)(gif|jpe?g|png) $/i.test(file.type)){
                        var fRead = new FileReader();
                        fRead.onload = (function(file){
                            return function(e){
                                var img = $('<img/>').addClass('thumb').attr('src', e.target.result);
                                $('#preview_img').append(img);
                            };
                        })(file);
                        fRead.readAsDataURL(file);
                    }
                });
            }else{
            console.log('asd')
                alert("Your browser doesn't support FILE API!");
            }
        })
    });
  $(function () {
    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
      event.preventDefault();
      $(this).ekkoLightbox({
        alwaysShowClose: true
      });
    });

    // $('.filter-container').filterizr({gutterPixels: 3});
    // $('.btn[data-filter]').on('click', function() {
    //   $('.btn[data-filter]').removeClass('active');
    //   $(this).addClass('active');
    // });
  })
  </script>
@endsection