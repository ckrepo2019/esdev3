
<div class="row">
      @php
            $colors = array(
                  'bg-danger',
                  'bg-info',
                  'bg-success'
            )
      @endphp
      @foreach (DB::table('rfidschoolist')->where('deleted',0)->get() as $item)
            @php
                  $count = DB::table('rfidcard')->where('deleted',0)->where('rfidschoolid',$item->id)->count();
            @endphp
            <div class="col-12 col-sm-6 col-md-3">
                  <div class="info-box">
                  <span class="info-box-icon {{$colors[array_rand($colors)]}} elevation-1 p-1">
                        <img alt="" src="{{asset('schoollistlogo/bct.png')}}">
                  </span>
                        <div class="info-box-content">
                              <span class="info-box-text h3 ">{{$item->schoolabrv}}</span>
                              <span class="info-box-number">
                              {{$count}} PC (S)
                              </span>
                        </div>
                  </div>
            </div>
            
      @endforeach
</div>
                  