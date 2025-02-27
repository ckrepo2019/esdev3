
                <div class="modal-header">
                    <h4 class="modal-title" id="student-name">UPLOAD {{$reqname}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    </button>
                </div>
                
                <form action="/registrar/studentrequirementsuploadphoto" method="post" name="submitfiles"  enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row mb-2">
                            <h5>STUDENT NAME: <strong>{{$studname->lastname}}, {{$studname->firstname}}</strong></h5>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" name="studid" value="{{$studid}}"/>
                                <input type="hidden" name="reqid" value="{{$reqid}}"/>
                                <input type="hidden" name="queuecoderef" value="{{$queuecoderef}}"/>
                                @if($photoinfo)
                                    <img src="{{asset($photoinfo->picurl)}}" onerror="this.onerror = null, this.src='{{asset($photoinfo->otherpicurl)}}'" style="border-radius: unset !important; width:100%;" alt="User Image"/>
                                @else
                                    <input type="file" class="form-control"  accept="image/*" name="input-photo" required/>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if(!$photoinfo)
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal" id="btn-modal-close">Close</button>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </div>
                    @else
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal" id="btn-modal-close">Close</button>
                            <button type="button" class="btn btn-danger delete-subreq" data-id="{{$photoinfo->id}}">Delete</button>
                        </div>
                    @endif
                </form>