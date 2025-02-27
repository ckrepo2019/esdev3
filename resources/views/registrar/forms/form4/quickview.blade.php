<style>
    .small-box {
        box-shadow: unset;
        border: 2px solid gainsboro;
    }
    th, td {
        padding: 3px !important;
    }
</style>
<div class="row mb-2">
    <div class="col-md-6">
        <input class="filter form-control" placeholder="Search" />
    </div>
    <div class="col-6 text-right">
        <button type="button" class="btn btn-primary" id="btn-reload"><i class="fa fa-sync"></i> Reload</button>
        <button type="button" class="btn btn-default" id="btn-export-pdf"><i class="fa fa-file-pdf"></i> PDF</button>
        <button type="button" class="btn btn-default" id="btn-export-excel"><i class="fa fa-file-pdf"></i> EXCEL</button>
    </div>
</div>
<div class="row">
    @if(count($data)>0)
        @foreach($data as $dataval)
            
          <div class="col-md-12 eachdata" data-string="{{$dataval->levelname}} - {{$dataval->sectionname}}<">
            <!-- small card -->
            <div class="small-box">
              <div class="inner">
                <h4>{{$dataval->levelname}} - {{$dataval->sectionname}}</h4>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table">
                            <tr>
                                <td style="width: 30%;">REGISTERED LEARNERS</td>
                                <td colspan="2">Male : {{$dataval->registered->male}}</td>
                                <td colspan="2">Female : {{$dataval->registered->female}}</td>
                            </tr>
                            <tr>
                                <td>ATTENDANCE (Daily Average)</td>
                                <td colspan="2">Male : {{$dataval->attendance->male}}</td>
                                <td colspan="2">Female : {{$dataval->attendance->female}}</td>
                            </tr>
                            <tr>
                                <td rowspan="2" style="vertical-align: middle; text-align: center;">DROPPED OUT</td>
                                <td colspan="2" style="vertical-align: middle; text-align: center;">A</td>
                                <td colspan="2" style="vertical-align: middle; text-align: center;">B</td>
                            </tr>
                            <tr>
                                <td>Male : {{$dataval->dropped_out_a->male}}</td>
                                <td>Female : {{$dataval->dropped_out_a->female}}</td>
                                <td>Male : {{$dataval->dropped_out_b->male}}</td>
                                <td>Female : {{$dataval->dropped_out_b->female}}</td>
                            </tr>
                            <tr>
                                <td style="vertical-align: middle; text-align: center;">TRANSFERRED OUT</td>
                                <td>Male : {{$dataval->transferred_out_a->male}}</td>
                                <td>Female : {{$dataval->transferred_out_a->female}}</td>
                                <td>Male : {{$dataval->transferred_out_b->male}}</td>
                                <td>Female : {{$dataval->transferred_out_b->female}}</td>
                            </tr>
                            <tr>
                                <td style="vertical-align: middle; text-align: center;">TRANSFERRED IN</td>
                                <td>Male : {{$dataval->transferred_in_a->male}}</td>
                                <td>Female : {{$dataval->transferred_in_a->female}}</td>
                                <td>Male : {{$dataval->transferred_in_b->male}}</td>
                                <td>Female : {{$dataval->transferred_in_b->female}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                {{-- <div class="row mb-2">
                    <div class="col-md-4 text-center">
                        <label>REGISTERED LEARNERS</label>
                        <div class="row">
                            <div class="col-12">M : {{$dataval->registered->male}}</div>
                            <div class="col-12">F : {{$dataval->registered->female}}</div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <label>ATTENDANCE (Daily Average)</label>
                        <div class="row">
                            <div class="col-12">M : {{$dataval->attendance->male}}</div>
                            <div class="col-12">F : {{$dataval->attendance->female}}</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 text-center">
                        <label>DROPPED OUT</label>
                        <div class="row">
                            <div class="col-md-6">
                                <label>A</label><br/>
                                M : {{$dataval->dropped_out_a->male}}<br/>
                                F : {{$dataval->dropped_out_a->female}}
                            </div>
                            <div class="col-md-6">
                                <label>B</label><br/>
                                M : {{$dataval->dropped_out_b->male}}<br/>
                                F : {{$dataval->dropped_out_b->female}}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <label>TRANSFERRED OUT</label>
                        <div class="row">
                            <div class="col-md-6">
                                <label>A</label><br/>
                                M : {{$dataval->transferred_out_a->male}}<br/>
                                F : {{$dataval->transferred_out_a->female}}
                            </div>
                            <div class="col-md-6">
                                <label>B</label><br/>
                                M : {{$dataval->transferred_out_b->male}}<br/>
                                F : {{$dataval->transferred_out_b->female}}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <label>TRANSFERRED</label>
                        <div class="row">
                            <div class="col-md-6">
                                <label>A</label><br/>
                                M : {{$dataval->transferred_in_a->male}}<br/>
                                F : {{$dataval->transferred_in_a->female}}
                            </div>
                            <div class="col-md-6">
                                <label>B</label><br/>
                                M : {{$dataval->transferred_in_b->male}}<br/>
                                F : {{$dataval->transferred_in_b->female}}
                            </div>
                        </div>
                    </div>
                </div> --}}
              </div>
              {{-- <div class="icon">
                <i class="fas fa-door-open"></i>
              </div> --}}
            </div>
          </div>
        @endforeach
    @endif
</div>