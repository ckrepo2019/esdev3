
                            <div class="row d-flex align-items-stretch">
                                @foreach($medicines as $medicine)
                                    <div class="col-md-4 eachmed"  data-string="{{$medicine->brandname}} {{$medicine->genericname}} {{$medicine->dosage}} {{$medicine->description}}<">
                                        <div class="info-box mb-3 
                                          @if($medicine->quantity == 0)
                                          bg-secondary
                                          @else
                                            @if($medicine->expirydate<date('Y-m-d'))
                                            bg-danger
                                            @elseif($medicine->expirydate>date("Y-m-d", strtotime('sunday last  week')) && $medicine->expirydate<date("Y-m-d", strtotime('sunday this week')))
                                            bg-warning
                                            @else
                                            bg-success
                                            @endif
                                          @endif
                                        ">
                                          <span class="info-box-icon"><i class="fas fa-capsules"></i></span>
                            
                                          <div class="info-box-content">
                                            <span class="info-box-text">{{$medicine->brandname}}</span>
                                            <span class="info-box-text">{{$medicine->genericname}} </span>
                                            <span class="info-box-number">{{$medicine->quantity}} left</span>
                                            <span class="info-box-text"><small>Expiry date: {{date('m/d/Y', strtotime($medicine->expirydate))}}
                                            </small></span>
                                            <span class="info-box-text text-right">
                                              <button type="button" class="btn btn-sm btn-default p-0 m-1 btn-deletemed" data-id="{{$medicine->id}}"><i class="fa fa-trash m-0"></i></button>
                                              <button type="button" class="btn btn-sm btn-default p-0 m-1 btn-editmed" data-id="{{$medicine->id}}"><i class="fa fa-edit m-0"></i></button>
                                            </span>
                                          </div>
                                      
                                          <!-- /.info-box-content -->
                                        </div>
                                    </div>
                                @endforeach
                            </div>