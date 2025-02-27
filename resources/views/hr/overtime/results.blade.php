
        <div class="row mb-2">
            <div class="col-md-12">
                <button type="button" class="btn btn-sm btn-default">Pending <span class="right badge badge-warning">{{$countpending}}</span></button>
                <button type="button" class="btn btn-sm btn-default">Approved <span class="right badge badge-success">{{$countapproved}}</span></button>
                <button type="button" class="btn btn-sm btn-default">Disapprove <span class="right badge badge-danger">{{$countdisapproved}}</span></button>
            </div>
        </div>
@if(count($overtimes)>0)
@foreach($overtimes as $overtime)
    <div class="card eachovertime" style="border: none;">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 p-0 text-uppercase">
                    <label>{{$overtime->lastname}}, {{$overtime->firstname}}</label>
                </div>
                <div class="col-md-2 ">
                    <small class="text-muted"><label>Date:</label> <u>{{date('M d Y', strtotime($overtime->datefrom))}}</u></small>
                </div>
                <div class="col-md-3">
                    <small class="text-muted"><label>Time:</label> <u>{{date('h:i A', strtotime($overtime->timefrom))}} - {{date('h:i A', strtotime($overtime->timeto))}}</u></small>
                </div>
                <div class="col-md-4 text-muted">
                    <small class="text-muted"><label>Remarks: </label></small>
                    <small><u>{{$overtime->remarks}}</u></small>
                </div>
                <div class="col-md-3 text-right">
                    @if($overtime->overtimestatus == 0)
                        @if($overtime->createdby == auth()->user()->id)
                            <button type="button" class="btn btn-sm btn-default btn-deleteovertime" data-id="{{$overtime->id}}" style="display: inline;"><i class="fa fa-trash-alt"></i></button>
                        @endif
                        <div class="dropdown" style="display: inline;">
                            <button class="btn btn-default dropdown-toggle p-1" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Pending
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item dropdown-approve" href="#" data-id="{{$overtime->id}}">Approve</a>                                   <a class="dropdown-item dropdown-disapprove" href="#" data-id="{{$overtime->id}}">Disapprove</a>
                            </div>
                        </div>
                    @elseif($overtime->overtimestatus == 1)
                        <div class="dropdown" style="display: inline;">
                            <button class="btn btn-default dropdown-toggle p-1" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Approved
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item dropdown-pending" href="#" data-id="{{$overtime->id}}">Pending</a>
                                <a class="dropdown-item dropdown-disapprove" href="#" data-id="{{$overtime->id}}">Disapprove</a>
                            </div>
                        </div>
                    @elseif($overtime->overtimestatus == 2)
                        <div class="dropdown" style="display: inline;">
                            <button class="btn btn-default dropdown-toggle p-1" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Disapproved
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item dropdown-pending" href="#" data-id="{{$overtime->id}}">Pending</a>
                                <a class="dropdown-item dropdown-approve" href="#" data-id="{{$overtime->id}}">Approve</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endforeach
@endif