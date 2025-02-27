
@if(count($filedleaves) == 0)
<div class="row">
    <div class="col-md-12 text-right">
        No filed leaves shown
    </div>
</div>
@else

@php
$approved = 0;
$pending = 0;
$disapproved = 0;
@endphp
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
{{-- <style>
thead{
    background-color: #eee !important;
}

/* .table                      {width:1500px; font-size:90%; text-transform: uppercase; } */
.table thead th:first-child  { 
    position: sticky; 
    left: 0; 
    background-color: #fff; 
    outline: 2px solid #dee2e6;
    outline-offset: -1px;
    z-index: 999 !important
}
.table thead th:last-child  { 
    position: sticky !important; 
    right: 0; 
    background-color: #fff; 
    outline: 2px solid #dee2e6;
    outline-offset: -1px;
    z-index: 999 !important
}
/* .table thead {
    
    z-index: 999
} */

.table tbody td:last-child  { 
    position: sticky; 
    right: 0; 
    background-color: #fff; 
    outline: 2px solid #dee2e6;
    outline-offset: -1px;
    /* z-index: 999 */
    }

.table tbody td:first-child  {  
    position: sticky; 
    left: 0; 
    background-color: #fff; 
    width: 150px !important;
    background-color: #fff; 
    outline: 2px solid #dee2e6;
    outline-offset: -1px;
}

.table thead th:first-child  { 
        position: sticky; left: 0; 
        width: 150px !important;
        background-color: #fff; 
        outline: 2px solid #dee2e6;
        outline-offset: -1px;
}
td{
    word-break: break-all !important;
    overflow-wrap: break-word !important;
    word-wrap: break-word !important;
    hyphens: auto !important;
}
</style>
<div class="card" style="border: 1px solid #ddd;">
    <div class="card-body table-responsive p-0">
        <table class="table table-head-fixed text-nowrap table-bordered" style="table-layout: fixed !important;">
            <thead class="text-muted" style="font-size: 13px;">
                <tr class="text-center">
                    <th>Date Applied</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Dates Covered</th>
                    <th>#of leaves left</th>
                    <th>Purpose/Reason</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            @foreach($filedleaves as $leavekey => $filedleave)
                <tr style="font-size: 12px;">
                    <td class="p-2 text-center">{{date('M d, Y', strtotime($filedleave->createddatetime))}}<br/>{{date(' h:i A', strtotime($filedleave->createddatetime))}}</td>
                    <td>{{ucwords(strtolower($filedleave->lastname))}}, {{ucwords(strtolower($filedleave->firstname))}}</td>
                    <td>{{$filedleave->leave_type}}</td>
                    <td class="text-center">
                        @if(count($filedleave->dates)>0)
                            @if(date('D , M d, Y', strtotime(collect($filedleave->dates)->first()->ldate)) == date('D , M d, Y', strtotime(collect($filedleave->dates)->last()->ldate)))
                                {{date('D , M d, Y', strtotime(collect($filedleave->dates)->first()->ldate))}}
                            @else
                                {{date('D , M d, Y', strtotime(collect($filedleave->dates)->first()->ldate))}}<br/> to {{date('D , M d, Y', strtotime(collect($filedleave->dates)->last()->ldate))}}
                            @endif
                        @endif
                    </td>
                    <td class="text-center">{{$filedleave->numdaysleft}}/{{$filedleave->days}}</td>
                    <td>{{$filedleave->remarks}}</td>
                    <td class="p-2">
                        @foreach($filedleave->approvals as $approval)
                            @if($approval->userid == auth()->user()->id)
                                @if($approval->appstatus == 0)
                                <button type="button" class="btn btn-sm btn-warning btn-viewdetails" data-id="{{$filedleave->id}}" data-toggle="collapse" href="#collapseOne{{$filedleave->id}}">Pending</button>
                                @elseif($approval->appstatus == 1)
                                <button type="button" class="btn btn-sm btn-warning btn-viewdetails" data-id="{{$filedleave->id}}" data-toggle="collapse" href="#collapseOne{{$filedleave->id}}">Approved</button>
                                @elseif($approval->appstatus == 2)
                                <button type="button" class="btn btn-sm btn-warning btn-viewdetails" data-id="{{$filedleave->id}}" data-toggle="collapse" href="#collapseOne{{$filedleave->id}}">Disapproved</button>
                                @endif
                            @endif
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="5">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="collapseOne{{$filedleave->id}}" class="collapse">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid.
                                    3
                                    wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt
                                    laborum
                                    eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee
                                    nulla
                                    assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred
                                    nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft
                                    beer
                                    farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus
                                    labore sustainable VHS.
                                </div>
                            </div>
                        </div>
                    </td>
                    <td></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div> --}}
<style>
td.details-control {
/* background: url('../resources/details_open.png') no-repeat center center; */
cursor: pointer;
}
tr.details td.details-control {
    /* background: url('../resources/details_close.png') no-repeat center center; */
}
li span{
    color: unset !important;
}
.dtr-title{
    width: 25%;
}
.dtr-data{
    width: 75%;
}
.dtr-details {
    width: 100%;
}
</style>
<table id="example" class="display table-bordered" style="width:100%; font-size: 14px!important;">
<thead>
    <tr class="text-center">
        <th style="width: 10%;">Date Applied</th>
        <th style="width: 20%;">Name</th>
        <th>Type</th>
        <th style="width: 15%;">Dates Covered</th>
        <th class="text-left">&nbsp;&nbsp;Purpose/Reason</th>
        <th style="width: 10%;">Status</th>
        <th width="8%"></th>
    </tr>
</thead>
<tbody>
    @foreach($filedleaves as $leavekey => $filedleave)
        <tr>
            <td class="p-0 text-left {{--details-control--}}" style="vertical-align: top;">&nbsp;<small>{{date('m/d/Y', strtotime($filedleave->createddatetime))}}</small><br>&nbsp;<small>{{date('h:i A', strtotime($filedleave->createddatetime))}}</small>
            </td>
            <td style="vertical-align: top; border-right: none; padding-left: 10px;">{{ucwords(strtolower($filedleave->lastname))}}, {{ucwords(strtolower($filedleave->firstname))}}</td>
            <td style="vertical-align: top; border-right: none;">{{$filedleave->leave_type}}</td>
            <td class="text-right pr-2" style="vertical-align: top; font-weight: bold; border-right: none;">
                @php
                    $leaveDatesArray = [];
                @endphp

                @if(count($filedleave->dates)>0)
                    @foreach($filedleave->dates as $eachdate)
                        <small class="leavedates" leavedates="{{$eachdate->id}}">{{date('D , M d, Y', strtotime($eachdate->ldate))}}<br/><span class="text-info">{{$eachdate->daystatus}}</span></small><br>
                        @php
                            $leaveDatesArray[] = $eachdate->id;
                        @endphp
                    @endforeach
                @endif
            </td>
            <td style="vertical-align: top; border-right: none;">
                @php
                    $dataExists = false;
                @endphp
            
                @if(count($filedleave->dates) > 0)
                    @foreach($filedleave->dates as $eachdate)
                        @if(!empty($eachdate->remarks))
                            - <small>{{$eachdate->remarks}}</small> <br><span>&nbsp;</span><br>
                            @php
                                $dataExists = true;
                            @endphp
                        @endif
                    @endforeach
                @endif
            
                @if(!$dataExists)
                    - <small>{{$filedleave->remarks}}</small>
                @endif
            </td>
            <td class="text-center" style="vertical-align: top; border-right: none;">
                @if(collect($filedleave->approvals)->where('userid', auth()->user()->id)->count() > 0)
                    @foreach($filedleave->approvals as $approval)
                        @if($approval->userid == auth()->user()->id)
                            @if($approval->appstatus == 0)
                                @php
                                    $pending += 1;
                                @endphp
                                Pending
                            @elseif($approval->appstatus == 1)
                                @php
                                    $approved += 1;
                                @endphp
                                Approved
                            @elseif($approval->appstatus == 2)
                                @php
                                    $disapproved += 1;
                                @endphp
                                <button type="button" class="btn btn-sm btn-outline-danger pr-1 pl-1 pt-0 pb-0" data-toggle="tooltip" data-placement="left" title="Reason for disapproval: {{$approval->remarks}}"> Rejected</button>
                            @endif
                        @endif
                    @endforeach
                
                @endif
            </td>
            <th class="text-center" style="vertical-align: top; border-right: none;">
                @if(collect($filedleave->approvals)->where('userid', auth()->user()->id)->count() > 0)
                    @foreach(collect($filedleave->approvals)->where('userid', auth()->user()->id)->values() as $approval)
                        @if($filedleave->display == 1)
                            @if($approval->appstatus == 0)
                            <button type="button" class="btn btn-sm btn-success btn-modalstatus pr-1 pl-1 pt-0 pb-0" data-status="1" data-remarks="{{$approval->remarks}}" data-id="{{$filedleave->id}}" data-toggle="tooltip" data-placement="left" title="Approve"><i class="fas fa-thumbs-up"></i></button>
                            <button type="button" class="btn btn-sm btn-dark btn-modalstatus pr-1 pl-1 pt-0 pb-0" data-status="2" data-remarks="{{$approval->remarks}}" data-id="{{$filedleave->id}}" data-toggle="tooltip" data-placement="left" title="Reject"><i class="far fa-thumbs-down"></i></button>
                            <button type="button" class="btn btn-sm btn-danger btn-modalstatus pr-1 pl-1 pt-0 pb-0" data-status="3" data-remarks="{{$approval->remarks}}" data-id="{{$filedleave->id}}" data-toggle="tooltip" data-placement="left" title="delete"><i class="fas fa-trash-alt"></i></button>
                            @elseif($approval->appstatus == 1)
                            <button type="button" class="btn btn-sm btn-warning btn-modalstatus pr-1 pl-1 pt-0 pb-0" data-status="0" data-remarks="{{$approval->remarks}}" data-id="{{$filedleave->id}}" data-toggle="tooltip" data-placement="left" title="Pending"><i class="fa fa-undo"></i></button>
                            @elseif($approval->appstatus == 2)
                            <button type="button" class="btn btn-sm btn-warning btn-modalstatus pr-1 pl-1 pt-0 pb-0" data-status="0" data-remarks="{{$approval->remarks}}" data-id="{{$filedleave->id}}" data-toggle="tooltip" data-placement="left" title="Pending"><i class="fa fa-undo"></i></button>
                            @endif
                        @endif
                    @endforeach
                @endif
            </th>
        </tr>
        @if(collect($filedleave->approvals)->where('userid','!=', auth()->user()->id)->count() > 0)
        
            <tr>
                <th style="border-top: none;"></th>
                <th style="border: none; padding-left: 10px;">Approvals</td>
                <td style="border: none;"></td>
                <td style="border: none;"></td>
                <td style="border: none;"></td>
                <td style="border: none;"></td>
                <td style="border: none;"></td>
            </tr>
            @foreach(collect($filedleave->approvals)->values() as $eachapproval)
                <tr>
                    <td style="border-top: none;"><span style="display:none;">{{$eachapproval->lastname}}, {{$eachapproval->firstname}}</span></td>
                    <td style="border: none; font-size: 12px!important;font-weight: 500; padding-left: 10px;">{{$eachapproval->lastname}}, {{$eachapproval->firstname}}</td>
                    <td style="border: none;">
                        @if($eachapproval->appstatus == 0)
                            <span class="badge badge-warning">Pending</span>
                        @elseif($eachapproval->appstatus == 1)
                            <span class="badge badge-success">Approved</span>
                        @elseif($eachapproval->appstatus == 2)
                            <span class="badge badge-danger">Rejected</span>
                        @endif
                    </td>
                    <td style="border: none;">
                        @if($eachapproval->appstatus == 2) Reason for disappoval: {{$eachapproval->remarks}}@endif
                    </td>
                    <td style="border: none;"></td>
                    <td style="border: none;"></td>
                    <td style="border: none;"></td>
                </tr>
            @endforeach
        @endif
    @endforeach
</tbody>
</table>

<div class="modal fade" id="modal-changestatus">
<div class="modal-dialog">
    <div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title">Change Status</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12" hidden>
                <label>Status:</label>                    
                <div class="form-group clearfix">
                    <div class="icheck-primary d-inline">
                      <input type="radio" name="r1" id="input-status-pending" class="input-status" value="0">
                      <label for="input-status-pending">
                          Pending
                      </label>
                    </div>
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <div class="icheck-primary d-inline">
                      <input type="radio" name="r1" id="input-status-approve" class="input-status" value="1">
                      <label for="input-status-approve">
                          Approve
                      </label>
                    </div>
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <div class="icheck-primary d-inline">
                      <input type="radio" name="r1" id="input-status-disapprove" class="input-status" value="2">
                      <label for="input-status-disapprove">
                        Disapprove
                      </label>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <label>Reason for disapproval:</label>
                <textarea class="form-control" id="textarea-reason"></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default btn-close" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="btn-submitstatus">Save changes</button>
    </div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
{{-- <script src="{{asset('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/jszip/jszip.min.js')}}"></script>
<script src="{{asset('plugins/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{asset('plugins/pdfmake/vfs_fonts.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script> --}}
<script>
    $(document).ready(function (){
        var table = $('#example').DataTable({
            // 'responsive': true,
        paging: false,
        ordering: false,
        info: false,
            'bSort' : false
        });
        
        $('[data-toggle="tooltip"]').tooltip()
        $('#spanbox-submitted').text('{{count($filedleaves)}}')
        $('#spanbox-pending').text('{{$pending}}')
        $('#spanbox-approved').text('{{$approved}}')
        $('#spanbox-disapproved').text('{{$disapproved}}')

        
        // $('#btn-submitstatus').on('click', function(){
        //     var employeeleaveid = $(this).attr('data-id');
        //     var selectedstatus  = $('.input-status:checked').val();
        //     var reason = $('#textarea-reason').val();
        //     Swal.fire({
        //         title: 'Changing Status...',
        //         text: 'Would you like to continue?',
        //         type: 'warning',
        //         showCancelButton: true,
        //         confirmButtonColor: '#3085d6',
        //         cancelButtonColor: '#d33',
        //         confirmButtonText: 'Continue',
        //         allowOutsideClick: false
        //     }).then((result) => {
        //         if (result.value) {
        //             console.log(selectedstatus)
        //             $.ajax({
        //                 url: '/hr/leaves/changestatus',
        //                 type:"GET",
        //                 dataType:"json",
        //                 data:{
        //                     id: employeeleaveid,
        //                     selectedstatus: selectedstatus,
        //                     reason: reason
        //                 },
        //                 success: function(data){
        //                     $('.btn-close').click();
        //                     if(data == 1)
        //                     {
        //                         $('#textarea-reason').val('');
        //                         toastr.success('Pending successfully!')
        //                         $('#btn-generate').click();
        //                     }else{
        //                         toastr.error('Something went wrong!')
        //                     }
        //                 }
        //             })
        //         }
        //     })
        // })

    })
</script>
@endif