
            @if(count($statustypes)>0)
            @foreach($statustypes as $statustypekey => $statustype)
            <div class="info-box bg-success p-1 card collapsed-card" style="border: none; box-shadow: unset !important;">
              {{-- <span class="info-box-icon">{{$offensekey+1}}</span> --}}
  
              <div class="info-box-content card-header">
                <span class="info-box-number">{{$statustype->description}} <span class="badge badge-warning float-right">{{$statustype->count}}</span></span>
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-sm btn-default btn-delete-offense" data-id="{{$statustype->id}}" data-title="{{$statustype->description}}"><i class="fa fa-trash-alt"></i></button>
                        <button type="button" class="btn btn-sm btn-default btn-edit-offense" data-id="{{$statustype->id}}" data-title="{{$statustype->description}}" data-card-widget="collapse"><i class="fa fa-edit"></i></button>
                    </div>
                </div>
              </div>
              <div class="card-body p-1">
                  <div class="row">
                      <div class="col-md-12 mb-2">
                          <label>Title</label>
                          <input type="text" class="form-control form-control-sm" id="input-edit-title{{$statustype->id}}" value="{{$statustype->description}}"/>
                      </div>
                      <div class="col-md-12 text-right">
                          <button type="button" class="btn btn-sm btn-success btn-submit-edit-empstatus" data-id="{{$statustype->id}}">Save changes</button>
                      </div>
                  </div>
              </div>
              <!-- /.info-box-content -->
            </div>
            @endforeach
          @endif