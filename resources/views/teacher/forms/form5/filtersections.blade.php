
        {{-- @if(count($sections) == 0)
        <div class="col-md-12">
            <div class="alert alert-danger" role="alert">
                No sections assigned!
            </div>
        </div>
    @else
    @foreach($sections as $section)
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <span style="font-size: 15px"><strong>{{$section->numberofstudents}}</strong></span>  Students

                    <p>{{$section->levelname}} - {{$section->sectionname}}</p>
                    <div class="row">
                        <div class="col-6">
                            <small>Enrolled: {{$section->numberofenrolled}}</small><br/>
                            <small>Late Enrolled: {{$section->numberoflateenrolled}}</small><br/>
                            <small>Transferred In: {{$section->numberoftransferredin}}</small><br/>
                        </div>
                        <div class="col-6">
                            <small>Transferred Out: {{$section->numberoftransferredout}}</small><br/>
                            <small>Dropped Out: {{$section->numberofdroppedout}}</small><br/>
                            <small>Withdrawn: {{$section->numberofwithdraw}}</small>
                        </div>
                    </div>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                    <form action="/forms/{{$formtype}}" method="get" class="small-box-footer" target="_blank">
                        <input type="hidden" name="action" value="export"/>
                        @csrf
                        <input type="hidden" name="sectionid" value="{{$section->sectionid}}"/>
                        <input type="hidden" name="levelid" value="{{$section->levelid}}"/>
                        <input type="hidden" name="syid" value="{{$syid}}"/>
                        <input type="hidden" name="exporttype"/>
                        <input type="hidden" name="currentmonth" value="{{\Carbon\Carbon::now()->month}}"/>
                        <button type="button" class=" btn btn-sm btn-block dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">Export As <span class="sr-only">Toggle Dropdown</span>
                            <div class="dropdown-menu" role="menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(-1px, 37px, 0px); top: 0px; left: 0px; will-change: transform;">
                            <a class="dropdown-item" href="#" data-id="exportpdf">PDF</a>
                            <a class="dropdown-item" href="#" data-id="exportexcel">EXCEL</a>
                            </div>
                        </button>
                    </form>
            </div>
            
        </div>
    @endforeach
    <script>
        $('[data-id="exportpdf"]').on('click', function(){
            $(this).closest('form').find('input[name="exporttype"]').val('pdf')
            $(this).closest('form').submit();
        })
        $('[data-id="exportexcel"]').on('click', function(){
            $(this).closest('form').find('input[name="exporttype"]').val('excel')
            $(this).closest('form').submit();
        })
    </script>
@endif --}}

@if(collect($sections)->where('semester','0')->count() == 0)
<div class="col-md-12">
    <div class="alert alert-danger" role="alert">
        No sections assigned!
    </div>
</div>
@else

@if(collect($sections)->where('semester','0')->count() == 0)
<div class="col-md-12">
<div class="alert alert-danger" role="alert">
    No sections assigned!
</div>
</div>
@else
@endif
<div class="row mb-2">
    <div class="col-md-12">
        
        <input class="filter form-control" placeholder="Search section" />
    </div>
</div>
<div class="row">
    @foreach(collect($sections)->where('semester','0')->values() as $section)
    <div class="col-lg-3 col-4 eachsection" data-string="{{$section->levelname}} {{$section->sectionname}}<">
        <!-- small box -->
        <div class="small-box bg-success">
            <div class="inner">
                <h6>{{preg_replace('/[^0-9]/', '', $section->levelname)}} - {{$section->sectionname}}</h6>
                <span style="font-size: 15px"><strong>{{$section->numberofstudents}}</strong></span>  Students
                @if($section->semester == 1) - 1st Semester @elseif($section->semester == 2)- 2nd Semester @endif</p>
                <div class="row">
                    <div class="col-6">
                        <small>Enrolled: {{$section->numberofenrolled}}</small><br/>
                        <small>Late Enrolled: {{$section->numberoflateenrolled}}</small><br/>
                        <small>Transferred In: {{$section->numberoftransferredin}}</small><br/>
                    </div>
                    <div class="col-6">
                        <small>Transferred Out: {{$section->numberoftransferredout}}</small><br/>
                        <small>Dropped Out: {{$section->numberofdroppedout}}</small><br/>
                        <small>Withdrawn: {{$section->numberofwithdraw}}</small>
                    </div>
                </div>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <form action="/forms/{{$formtype}}" method="get" class="small-box-footer" target="_blank">
                <input type="hidden" name="action" value="show"/>
                <input type="hidden" name="exporttype"/>
                @csrf
                <input type="hidden" name="sectionid" value="{{$section->sectionid}}"/>
                <input type="hidden" name="levelid" value="{{$section->levelid}}"/>
                <input type="hidden" name="syid" value="{{$syid}}"/>
                <input type="hidden" name="semid" value="{{$section->semester}}"/>
                <input type="hidden" name="currentmonth" value="{{\Carbon\Carbon::now()->month}}"/>
                {{-- <button type="button" class=" btn btn-sm btn-block dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">Export As <span class="sr-only">Toggle Dropdown</span>
                    <div class="dropdown-menu" role="menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(-1px, 37px, 0px); top: 0px; left: 0px; will-change: transform;">
                    <a class="dropdown-item" href="#" data-id="exportpdf">PDF</a>
                    <a class="dropdown-item" href="#" data-id="exportexcel">EXCEL</a>
                    </div>
                </button> --}}
            </form>
        </div>
        
    </div>
    @endforeach
</div>
<script>
    $(".filter").on("keyup", function() {
        var input = $(this).val().toUpperCase();
        var visibleCards = 0;
        var hiddenCards = 0;

        $(".container").append($("<div class='card-group card-group-filter'></div>"));


        $(".eachsection").each(function() {
            if ($(this).data("string").toUpperCase().indexOf(input) < 0) {

            $(".card-group.card-group-filter:first-of-type").append($(this));
            $(this).hide();
            hiddenCards++;

            } else {

            $(".card-group.card-group-filter:last-of-type").prepend($(this));
            $(this).show();
            visibleCards++;

            if (((visibleCards % 4) == 0)) {
                $(".container").append($("<div class='card-group card-group-filter'></div>"));
            }
            }
        });

    });
$('[data-id="exportpdf"]').on('click', function(){
    $(this).closest('form').find('input[name="exporttype"]').val('pdf')
    $(this).closest('form').submit();
})
$('[data-id="exportexcel"]').on('click', function(){
    $(this).closest('form').find('input[name="exporttype"]').val('excel')
    $(this).closest('form').submit();
})
</script>
@endif