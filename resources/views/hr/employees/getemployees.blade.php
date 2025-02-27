
          <div class="row d-flex align-items-stretch">
            <div class="col-sm-4">
              <div class="card" style=" border-radius: 5px; box-shadow:0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%) !important;">
                <div class="card-body h-100 p-0">
                  <div class="row ml-0 mr-2">
                    <div class="col-md-4 p-2" style="background-color: #044254;  border-top-left-radius: 5px;  border-bottom-left-radius: 5px;">
                      <div class="row">
                        <div class="col-md-12 text-center mb-1">
                          <img src="{{asset('assets/images/avatars/unknown.png')}}" onerror="this.onerror = null, this.src='{{asset('assets/images/avatars/unknown.png')}}'" style="width: 60px; border: 2px solid white; border-radius: 10px;">
                        </div>
                        <div class="col-md-12 text-center">
                          <a type="button" {{--href="/hr/employees/profile/index?employeeid={{$employee->id}}"--}} class="btn btn-block btn-sm text-center btn-default p-1 text-white" style="font-size: 11px; background-color: #044254;border: 2px solid white; border-radius: 10px;">View Profile</a>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-8">
                      <div class="row">
                        <div class="col-md-12">
                          <span style="font-size: 14px; font-weight: bold;">employee name</span>
                        </div>
                        <div class="col-md-12">
                          <span class="badge badge-success">Status</span>
                          <span class="badge badge-primary">Status</span>
                        </div>
                        <div class="col-md-12">
                          <span style="font-size: 14px; font-weight: bold;">designation</span>
                        </div>
                        <div class="col-md-12">
                          <small class="text-muted"><i class="fa fa-phone"></i> &nbsp;Employee phone number</small>
                        </div>
                        <div class="col-md-12">
                          <small class="text-muted"><i class="fa fa-address-book"></i> &nbsp;Employee address</small>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-4"></div>
            <div class="col-4"></div>
          </div>