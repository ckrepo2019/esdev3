
<div class="row mt-2">
    @foreach($days as $eachday)
    <div class="col-md-12 mt-2">
        <h6 class="mt-0 text-dark op-8 font-weight-bold">
          {{date('l', strtotime($eachday))}}, {{date('M jS', strtotime($eachday))}}
          </h6>
          <button type="button" class="btn btn-sm btn-default btn-addrow" data-date="{{$eachday}}" ><i class="fa fa-plus"></i> Add Time Availability</button>
          <ul class="list-timeline list-timeline-primary" id="day-{{$eachday}}">
            @if(count($timeavailabilities)>0)
                @foreach(collect($timeavailabilities)->where('scheddate',$eachday)->values() as $eachtimesched)
                    <li class="list-timeline-item p-0 pb-3 pb-lg-4 d-flex flex-wrap flex-column" style="padding-bottom: 0px !important;">
                        <p class="my-0 text-muted flex-fw text-sm text-uppercase">{{date('h:i A', strtotime($eachtimesched->timefrom))}} - {{date('h:i A', strtotime($eachtimesched->timeto))}}  @if(count($eachtimesched->appointments)>0) <a href="/home" class="getappointments" data-schedid="{{$eachtimesched->id}}">({{count($eachtimesched->appointments)}} Appointment(s))</a> @else <i class="fa fa-trash-alt text-danger delete-time" style="cursor: pointer;" data-id="{{$eachtimesched->id}}" data-date="{{$eachday}}"></i>@endif</p>
                    </li>
                @endforeach
            @endif
            {{-- <li class="list-timeline-item p-0 pb-3 pb-lg-4 d-flex flex-wrap flex-column" style="padding-bottom: 0px !important;">
              <p class="my-0 text-muted flex-fw text-sm text-uppercase">09:00 - 10:00 - Registration</p>
            </li>
            <li class="list-timeline-item p-0 pb-3 pb-lg-4 d-flex flex-wrap flex-column" style="padding-bottom: 0px !important;" data-toggle="collapse" data-target="#day-3-item-2">
              <p class="my-0 text-muted flex-fw text-sm text-uppercase">10:00 - 12:00 - Jesscia Lawrence</p>
              <p class="my-0 collapse flex-fw text-uppercase text-xs text-dark op-8" id="day-3-item-2"> Talk: Ninja coding / <span class="text-primary">Room 31 </p>
            </li>
            <li class="list-timeline-item p-0 pb-3 pb-lg-4 d-flex flex-wrap flex-column" style="padding-bottom: 0px !important;">
              <p class="my-0 text-muted flex-fw text-sm text-uppercase">12:00 - 13:00 - Lunch Break</p>
            </li>
            <li class="list-timeline-item p-0 pb-3 pb-lg-4 d-flex flex-wrap flex-column" style="padding-bottom: 0px !important;" data-toggle="collapse" data-target="#day-3-item-4">
              <p class="my-0 text-dark flex-fw text-sm text-uppercase">13:00 - 15:00 - Anthony Jonas</p>
              <p class="my-0 collapse flex-fw text-uppercase text-xs text-dark op-8" id="day-3-item-4"> Talk: OpenData / <span class="text-primary">Room 31 </p>
            </li>
            <li class="list-timeline-item p-0 pb-3 pb-lg-4 d-flex flex-wrap flex-column" style="padding-bottom: 0px !important;">
              <p class="my-0 text-muted flex-fw text-sm text-uppercase">15:00 - 16:00 - Coffee Break</p>
            </li>
            <li class="list-timeline-item p-0 pb-3 pb-lg-4 d-flex flex-wrap flex-column" style="padding-bottom: 0px !important;" data-toggle="collapse" data-target="#day-3-item-6">
              <p class="my-0 text-muted flex-fw text-sm text-uppercase">16:00 - 18:00 - Anthony Jonas</p>
              <p class="my-0 collapse flex-fw text-uppercase text-xs text-dark op-8" id="day-3-item-6"> Talk: OpenData / <span class="text-primary">Room 31 </p>
            </li>
            <li class="list-timeline-item p-0 pb-3 d-flex flex-wrap flex-column" style="padding-bottom: 0px !important;">
              <p class="my-0 text-muted flex-fw text-sm text-uppercase">18:00 - 23:00 - After conference</p>
            </li> --}}
          </ul>
    </div>
    @endforeach
</div>

<script>
    $('.btn-addrow').on('click', function(){
        var thisdate = $(this).attr('data-date');
        var count = $('.flex-column[data-date="'+thisdate+'"]').length
        $('#day-'+thisdate).append(
            `<li class="list-timeline-item p-0 pb-3 pb-lg-4 d-flex flex-wrap flex-column" style="padding-bottom: 0px !important;" data-date="`+thisdate+`" count=`+count+`>
              <div class="row">
                <div class="col-md-3">
                    <label>FROM</label>
                    <input type="time" class="form-control p-1 timefrom" style="height: 30px;"/>
                </div>
                <div class="col-md-3">
                    <label>TO</label>
                    <input type="time" class="form-control p-1 timeto" style="height: 30px;"/>
                </div>
                <div class="col-md-6">
                    <br>
                    <button type="button" class="btn btn-default btn-sm btn-savetime mt-2" data-date="`+thisdate+`"><i class="fa fa-share text-success m-1"></i></button>
                    <button type="button" class="btn btn-default btn-sm btn-remove-row mt-2" data-date="`+thisdate+`" count=`+count+`><i class="fa fa-trash text-danger m-1"></i></button>
                </div>
          </div>
          </li>`
          )

    })


    $(document).on('click','.btn-remove-row', function(){
      var count = $(this).attr('count')
      var thisdate = $(this).attr('data-date')
      $('.flex-column[data-date="'+thisdate+'"][count="'+count+'"]').remove()
    })
</script>