
            @if(count($offenses)>0)
            @foreach($offenses as $offensekey => $offense)
            <div class="info-box bg-success p-1 card collapsed-card" style="border: none; box-shadow: unset !important;">
              {{-- <span class="info-box-icon">{{$offensekey+1}}</span> --}}
  
              <div class="info-box-content card-header">
                <span class="info-box-number">{{$offense->title}}</span>
                <span class="progress-description">
                    {{$offense->description}}
                </span>
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-sm btn-default btn-delete-offense" data-id="{{$offense->id}}" data-title="{{$offense->title}}" data-description="{{$offense->description}}"><i class="fa fa-trash-alt"></i></button>
                        <button type="button" class="btn btn-sm btn-default btn-edit-offense" data-id="{{$offense->id}}" data-title="{{$offense->title}}" data-description="{{$offense->description}}" data-card-widget="collapse"><i class="fa fa-edit"></i></button>
                    </div>
                </div>
              </div>
              <div class="card-body p-1">
                  <div class="row">
                      <div class="col-md-12 mb-2">
                          <label>Title</label>
                          <input type="text" class="form-control form-control-sm" id="input-edit-title{{$offense->id}}" value="{{$offense->title}}"/>
                      </div>
                      <div class="col-md-12 mb-2">
                          <label>Description</label>
                          <input type="text" class="form-control form-control-sm" id="input-edit-description{{$offense->id}}" value="{{$offense->description}}"/>
                      </div>
                      <div class="col-md-12 text-right">
                          <button type="button" class="btn btn-sm btn-success btn-submit-edit-offense" data-id="{{$offense->id}}">Save changes</button>
                      </div>
                  </div>
              </div>
              <!-- /.info-box-content -->
            </div>
            @endforeach
          @endif