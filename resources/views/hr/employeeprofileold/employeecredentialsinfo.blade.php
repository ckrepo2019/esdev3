
@if(session()->has('linkid'))
    @if( session()->get('linkid') == 'custom-content-above-credentials')
        <div class="tab-pane fade show active" id="custom-content-above-credentials" role="tabpanel" aria-labelledby="custom-content-above-credentials-tab">
    @else
        <div class="tab-pane fade" id="custom-content-above-credentials" role="tabpanel" aria-labelledby="custom-content-above-credentials-tab">
    @endif
@else
    <div class="tab-pane fade" id="custom-content-above-credentials" role="tabpanel" aria-labelledby="custom-content-above-credentials-tab">
@endif
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Credentials</strong>
                    </div>
                </div>
            </div>
            @if(count($credentials) > 0)
                <div class="card-body">
                    <table class="table table-bordered" style="table-layout: fixed;">
                        <tbody>
                            @foreach($credentials as $credential)
                                <tr>
                                    <td>{{$credential->description}}</td>
                                    <td>
                                        @if(count($employeecredentials) > 0)
                                            @php
                                                $match = 0;   
                                                $extension = "";   
                                                $filepath = "#";   
                                            @endphp
                                            @foreach($employeecredentials as $employeecredential)
                                                @if($credential->id == $employeecredential->credentialtypeid)
                                                    @php
                                                        $match = 1;
                                                        $extension = $employeecredential->extension;
                                                        $filepath = asset($employeecredential->filepath);
                                                    @endphp
                                                @endif
                                            @endforeach
                                            @if($match == 0)
                                                <button type="button" class="btn btn-sm text-success text-center" data-toggle="modal" data-target="#addcredential{{$credential->id}}">
                                                    <i class="fa fa-plus"></i>&nbsp; Add {{$credential->description}}
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-sm  text-center btn-primary credentialsviewbutton mb-2" data-toggle="modal" data-target="#viewCredential{{$credential->id}}">
                                                    View
                                                </button>
                                                <div id="viewCredential{{$credential->id}}" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"><strong>{{$credential->description}}</strong></h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">×</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                @if($extension == 'pdf')
                                                                    <embed src="{{$filepath}}" style="width:100%;min-height:640px;">
                                                                @elseif($extension == 'png' || $extension == 'jpeg' || $extension == 'jpg')
                                                                    <img src="{{$filepath}}" style="width: 100%" class="credentialimg"/>
                                                                @else
                                                                    Word document is not yet supported.
                                                                @endif
                                                            </div>
                                                            <div class="modal-footer">
                                                                {{-- <a href="{{asset($filepath)}}" class="btn btn-primary float-right btn-sm  text-white">Download {{$credential->description}}</a> --}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-sm  text-center btn-danger credentialdelete credentialsdeletebutton mb-2" description="{{$credential->description}}" credentialid="{{$credential->id}}">
                                                    Remove
                                                </button>
                                            @endif
                                        @else
                                            <button type="button" class="btn btn-sm text-success text-center" data-toggle="modal" data-target="#addcredential{{$credential->id}}">
                                                <i class="fa fa-plus"></i>&nbsp; Add {{$credential->description}}
                                            </button>
                                        @endif
                                        <div id="addcredential{{$credential->id}}" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"><strong>Add {{$credential->description}}</strong></h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <br>
                                                        <form action="/employeecredential" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <input type="hidden" name="credentialid" value="{{$credential->id}}"/>
                                                            <input type="hidden" name="employeeid" value="{{$profile->id}}"/>
                                                            <input type="hidden" name="linkid" value="custom-content-above-credentials"/>
                                                            {{-- <input type="file" name="credential" accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint,text/plain, application/pdf"> --}}
                                                            <input type="file" name="credential" class="form-control form-control-sm" accept=
                                                            "application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint,
                                                            text/plain, application/pdf, image/*">
                                                            <br>
                                                            <br>
                                                            <br>
                                                                <button type="submit" class="btn btn-primary btn-sm float-right text-white ">Upload {{$credential->description}}</button>   
                                                        </form>                                                                 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

@if(session()->has('linkid'))
    @if( session()->get('linkid') == 'custom-content-above-credentials')
        </div>
    @else
        </div>
    @endif
@else
    </div>
@endif