<style>
    .alert-danger {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
}
</style>
@if(count($sections) == 0)
<div class="alert alert-danger" role="alert">
  No sections assigned!
</div>
@else
<div class="row">
@foreach($sections as $section)
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-success">
                <div class="inner">
                    <p>{{$section->levelname}} - {{$section->sectionname}}</p>
                    <div class="row">
                        <div class="col-6">
                            <small>Enrolled: {{$section->numberofenrolled}}</small><br/>
                            <small>Late Enrolled: {{$section->numberoflateenrolled}}</small><br/>
                            <small>Transferred In: {{$section->numberoftransferredin}}</small><br/>
                        </div>
                        <div class="col-6">
                            <small>Transferred Out: {{$section->numberoftransferredout}}</small><br/>
                            <small>Dropped Out: {{$section->numberofdroppedout}}</small>
                        </div>
                    </div>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                {{-- <form action="/classattendance/viewsection_v1" method="get" class="small-box-footer"> --}}
                {{-- <form action="/classattendance/viewsection_v2" method="get" class="small-box-footer"> --}}
                <form action="/students/advisorygetstudents" method="get" class="small-box-footer">
                    @csrf
                    <input type="hidden" name="sectionid" value="{{$section->sectionid}}"/>
                    <input type="hidden" name="levelid" value="{{$section->levelid}}"/>
                    <input type="hidden" name="syid" value="{{$syid}}"/>
                    <input type="hidden" name="semid" value="{{$semid}}"/>
                    <button type="submit" class=" btn btn-sm btn-block">More info <i class="fas fa-arrow-circle-right"></i></button>
                </form>
                </div>
            </div>
@endforeach
</div>
@endif