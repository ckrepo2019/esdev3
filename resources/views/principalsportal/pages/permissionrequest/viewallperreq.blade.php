@extends('principalsportal.layouts.app2')


@section('pagespecificscripts')
    
   

@endsection

@section('modalSection')
<div class="modal fade" id="reponse" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <form id="subjectform" action="/updateResponse" method="GET">
            <input type="hidden" name="perreq" id="perreq">
            <div class="modal-body">
                <p id="description"></p>
                <div class="form-group">
                    <label>Select Response</label>
                    <select class="form-control" name="response" id="selectresponse">
                        <option value="">No Response</option>
                        <option value="{{Crypt::encrypt(1)}}">Approve</option>
                        <option value="{{Crypt::encrypt(2)}}">Disapprove</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer justify-content-between sb">
                <button onClick="this.form.submit(); this.disabled=true; " type="submit" class="btn btn-info savebutton">Proceed</button>
            </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
            <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000"><i class="fas fa-window-restore nav-icon"></i> REQUESTS</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">Promotion</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="card">
        <div class="card-header bg-info">
            <span class="" style="font-size: 16px"><b><i class="fas fa-window-restore nav-icon"></i> REQUEST LIST</b></span>
           
        </div>
        <div class="car-body">
            <table class="table">
                <tr class="bg-warning">
                    <th width="45%">Request Detail</th>
                    <th width="10%">From</th>
                    <th width="10%" class="text-center">Status</th>
                    <th width="20%" class="text-center">Response</th>
                    <th width="20%" class="text-right">Date Requested</th>
                </tr>
                @foreach ($perreq as $item)
                    <tr>
                        <td>
                            @if($item->perreqtype == 1)
                                Requesting permission to activate school year <b class="text-primary">{{$item->sydesc}}</b>
                            @elseif($item->perreqtype == 2)
                                Requesting permission to activate  <b class="text-primary">
                                    @if($item->reqid == 2)
                                        2nd
                                    @else
                                        1st
                                    @endif

                                    semester
                                </b>
                            @endif
                        </td>
                        <td>{{strtoupper($item->sendername)}}</td>
                        <td class=" align-middle text-center">
                            @if($item->status == 3)
                                <span class="badge badge-danger d-block">Cancelled</span>
                            @elseif($item->status == 1)
                                <span class="badge badge-success d-block">Approved</span>
                            @else
                                <span class="badge badge-warning d-block">Waiting</span>
                            @endif
                        </td>
                        <td  class=" align-middle text-center pr-4 pl-4">
                            @if($item->status == 0)
                                @if($item->response == 1)
                                    <button perrep="Approve" class="btn btn-sm btn-success btn-xs w-100 response" data-toggle="modal"  data-target="#reponse" title="response" data-widget="chat-pane-toggle" id="{{Crypt::encrypt($item->perreqdetialid)}}"></i>Approved</button>
                                @elseif($item->response == 2)
                                    <button perrep="Disapprove" class="btn btn-sm btn-danger btn-xs w-100 response"  data-toggle="modal"  data-target="#reponse" title="response" data-widget="chat-pane-toggle" id="{{Crypt::encrypt($item->perreqdetialid)}}"></i>Dissapproved</button>
                                @else
                                    <button perrep="No Response"  class="btn btn-sm btn-info btn-xs w-100 response" data-toggle="modal"  data-target="#reponse" title="response" data-widget="chat-pane-toggle" id="{{Crypt::encrypt($item->perreqdetialid)}}">
                                        WAITING FOR RESPONSE
                                    </button>
                                @endif
                            @else
                                @if($item->response == 2)
                                    <span class="badge badge-danger d-block">Disapproved</span>
                                @elseif($item->response == 1)
                                    <span class="badge badge-success d-block">Approved</span>
                                @else
                                    <span class="badge badge-warning d-block">No Response</span>
                                @endif
                            @endif
                            
                        </td>
                        <td class="text-right">
                            {{\Carbon\Carbon::create($item->createddatetime)->isoFormat('MMM DD, YYYY')}}
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</section>
@endsection


@section('footerjavascript')

    <script>
         $(document).ready(function(){
            
            $(document).on('click','.response',function(){
               
                $('#perreq').val($(this).attr('id'))
                $('#selectresponse').val($(this).attr('perrep'))

                for(var x=0 ; x < $('#selectresponse')[0].options.length; x++){
                    if($(this).attr('perrep') == $('#selectresponse')[0].options[x].innerHTML){
                        console.log($('#selectresponse')[0].options[x].selected = true)
                    }
                }
                
                $('#description')[0].innerHTML = $(this).closest('tr')[0].cells[0].innerHTML
            })

         })
    </script>


@endsection

