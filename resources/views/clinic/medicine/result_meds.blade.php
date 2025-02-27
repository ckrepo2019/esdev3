
  <div class="row d-flex align-items-stretch">
      @foreach($medicines as $medicine)
          <a href="#" class="col-md-4 eachmed  btn-editmed" data-id="{{$medicine->id}}">
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
                  <span >{{$medicine->brandname}}</span>
                  <span>{{$medicine->genericname}}</span>
                  <span class="info-box-number">{{$medicine->quantityleft}} left</span>
                  <span class="info-box-text"><small>Expiry date: {{date('m/d/Y', strtotime($medicine->expirydate))}}
                  </small></span>
                  <span class="info-box-text text-right">
                  </span>
                </div>
              </div>
          </a>
      @endforeach
  </div>