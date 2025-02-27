<div class="row">
    <div class="col-md-12">
        <button type="button" class="btn btn-default" id="btn-export"><i class="fa fa-file-excel"></i> EXCEL</button>
    </div>
    <div class="col-md-12">
        <table class="table table-bordered table-striped" id="userstable">
            <thead>
                <tr>
                    <th style="width: 35% !important;">Name</th>
                    <th style="width: 15% !important;">Username</th>
                    <th style="width: 25% !important; " class="text-center">
                        Password STR
                        <br/>
                        <button type="button" class="btn btn-primary btn-sm" id="btn-generateall"><i class="fa fa-sync"></i> Generate All</button>
                        {{-- <button type="button" class="btn btn-primary btn-sm" id="btn-submitall"><i class="fa fa-save"></i></button> --}}
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td style="width: 30% !important;">
                            {{$user->name}}
                        </td>
                        <td class="text-center">
                            <span class="right badge badge-info">{{$user->usertype}}</span><br/>
                            {{$user->email}}
                        </td>
                        <td>
                            <div class="form-group">
                                <div class="input-group">
                                  <div class="input-group-prepend generate" data-id="{{$user->userid}}">
                                    <span class="input-group-text"><i class="fa fa-spinner"></i></span>
                                  </div>
                                  <input type="text" class="form-control passwordstr" generated="0" value="{{$user->passwordstr}}" data-id="{{$user->userid}}" disabled/>
                                  {{-- <div class="input-group-append">
                                    <span class="input-group-text"><i class="far fa-save"></i></span>
                                  </div> --}}
                                </div>
                              </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>