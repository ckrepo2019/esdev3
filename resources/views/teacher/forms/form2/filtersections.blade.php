
@if(count($sections) == 0)
<div class="col-md-12">
    <div class="alert alert-danger" role="alert">
        No sections assigned!
    </div>
</div>
@else

@if(count($sections) == 0)
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
@foreach($sections as $section)
@if($section->acadprogid == 5)
@php
    $studentsgroup = collect($section->students)->groupBy('strandcode');

@endphp
    @if(count($studentsgroup)>0)
        @foreach($studentsgroup as $keygroup=>$allstudents)
            @if(strtolower($section->acadprogcode) == 'shs')
                @php
                    $strands = collect($allstudents)->sortBy('strandcode')->groupBy('strandcode');
                @endphp
                @if(count($strands) > 0)
                    @foreach($strands as $eachstrandkey=>$eachstrand)
                        <div class="col-md-4 eachsection" data-string="{{$section->levelname}} {{$section->sectionname}}  {{$eachstrandkey}}<">
                            <!-- small box -->
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h6>Grade Level: {{preg_replace('/[^0-9]/', '', $section->levelname)}}</h6>
                                    <h6>Section: {{$section->sectionname}}</h6>
                                    <h6>Strand: {{$eachstrandkey}}</h6>
                                    <span style="font-size: 15px"><strong>{{count($allstudents)}}</strong></span>  Students
                                    @if($section->semester == 1) - 1st Semester @elseif($section->semester == 2)- 2nd Semester @endif</p>
                                    <div class="row">
                                        <div class="col-6">
                                            <small>Enrolled: {{collect($eachstrand)->where('enrolledstudstatus','1')->count()}}</small><br/>
                                            <small>Late Enrolled: {{collect($eachstrand)->where('enrolledstudstatus','2')->count()}}</small><br/>
                                            <small>Transferred In: {{collect($eachstrand)->where('enrolledstudstatus','4')->count()}}</small><br/>
                                        </div>
                                        <div class="col-6">
                                            <small>Transferred Out: {{collect($eachstrand)->where('enrolledstudstatus','5')->count()}}</small><br/>
                                            <small>Dropped Out: {{collect($eachstrand)->where('enrolledstudstatus','3')->count()}}</small><br/>
                                            {{-- <small>Withdrawn: {{collect($allstudents)->where('enrolledstudstatus','6')->count()}}</small> --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <form action="/forms/{{$formtype}}" method="get" class="small-box-footer">
                                    <input type="hidden" name="action" value="index"/>
                                    <input type="hidden" name="sectionid" value="{{$section->sectionid}}"/>
                                    <input type="hidden" name="levelid" value="{{$section->levelid}}"/>
                                    <input type="hidden" name="semid" value="{{$section->semester}}"/>
                                    <input type="hidden" name="strandid" value="{{$eachstrand[0]->strandid}}"/>
                                    <input type="hidden" name="formtype" value="{{$formtype}}"/>
                                    <input type="hidden" name="syid" value="{{$syid}}"/>
                                    <input type="hidden" name="selectedmonth" value="{{date('m')}}"/>
                                    <button type="submit" class=" btn btn-sm btn-block">More info <i class="fas fa-arrow-circle-right"></i></button>
                                </form>
                            </div>
                            
                        </div>
                    @endforeach
                @endif
            @else
                <div class="col-md-4 eachsection" data-string="{{$section->levelname}} {{$section->sectionname}}<">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h6>Grade Level: {{preg_replace('/[^0-9]/', '', $section->levelname)}}</h6>
                            <h6>Section: {{$section->sectionname}}</h6>
                            <h6>Strand: {{$keygroup}}</h6>
                            <span style="font-size: 15px"><strong>{{count($allstudents)}}</strong></span>  Students
                            @if($section->semester == 1) - 1st Semester @elseif($section->semester == 2)- 2nd Semester @endif</p>
                            <div class="row">
                                <div class="col-6">
                                    <small>Enrolled: {{collect($allstudents)->where('enrolledstudstatus','1')->count()}}</small><br/>
                                    <small>Late Enrolled: {{collect($allstudents)->where('enrolledstudstatus','2')->count()}}</small><br/>
                                    <small>Transferred In: {{collect($allstudents)->where('enrolledstudstatus','4')->count()}}</small><br/>
                                </div>
                                <div class="col-6">
                                    <small>Transferred Out: {{collect($allstudents)->where('enrolledstudstatus','5')->count()}}</small><br/>
                                    <small>Dropped Out: {{collect($allstudents)->where('enrolledstudstatus','3')->count()}}</small><br/>
                                    {{-- <small>Withdrawn: {{collect($allstudents)->where('enrolledstudstatus','6')->count()}}</small> --}}
                                </div>
                            </div>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <form action="/forms/{{$formtype}}" method="get" class="small-box-footer">
                            <input type="hidden" name="action" value="index"/>
                            <input type="hidden" name="sectionid" value="{{$section->sectionid}}"/>
                            <input type="hidden" name="levelid" value="{{$section->levelid}}"/>
                            <input type="hidden" name="semid" value="{{$section->semester}}"/>
                            <input type="hidden" name="syid" value="{{$syid}}"/>
                            <input type="hidden" name="selectedmonth" value="{{date('m')}}"/>
                            <button type="submit" class=" btn btn-sm btn-block">More info <i class="fas fa-arrow-circle-right"></i></button>
                        </form>
                    </div>
                    
                </div>
            @endif
        @endforeach
    @endif
@else
    <div class="col-md-4 eachsection" data-string="{{$section->levelname}} {{$section->sectionname}}<">
        <!-- small box -->
        <div class="small-box bg-success">
            <div class="inner">
                <h6>{{$section->levelname}} - {{$section->sectionname}}</h6>
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
                        {{-- <small>Withdrawn: {{$section->numberofwithdraw}}</small> --}}
                    </div>
                </div>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            
            @if(strtolower($section->acadprogcode) == 'shs')
            <form action="/forms/{{$formtype}}" method="get" class="small-box-footer">
                <input type="hidden" name="action" value="index"/>
                <input type="hidden" name="sectionid" value="{{$section->sectionid}}"/>
                <input type="hidden" name="levelid" value="{{$section->levelid}}"/>
                <input type="hidden" name="strandid" value="{{$section->strandid}}"/>
                <input type="hidden" name="semid" value="{{$section->semester}}"/>
                <input type="hidden" name="formtype" value="{{$formtype}}"/>
                <input type="hidden" name="syid" value="{{$syid}}"/>
                <input type="hidden" name="selectedmonth" value="{{date('m')}}"/>
                <button type="submit" class=" btn btn-sm btn-block">More info <i class="fas fa-arrow-circle-right"></i></button>
            </form>
        @else
            <form action="/forms/{{$formtype}}" method="get" class="small-box-footer">
                <input type="hidden" name="action" value="index"/>
                <input type="hidden" name="sectionid" value="{{$section->sectionid}}"/>
                <input type="hidden" name="levelid" value="{{$section->levelid}}"/>
                <input type="hidden" name="semid" value="{{$section->semester}}"/>
                <input type="hidden" name="syid" value="{{$syid}}"/>
                <input type="hidden" name="selectedmonth" value="{{date('m')}}"/>
                <button type="submit" class=" btn btn-sm btn-block">More info <i class="fas fa-arrow-circle-right"></i></button>
            </form>
        @endif
        </div>
        
    </div>
@endif
@endforeach
<div class="modal fade" id="modal-getsf1">
<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="text-section"></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body pt-0" id="container-sf1">
            
        </div>
        {{-- <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
        </div> --}}
    </div>

</div>

</div>

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
$('#btn-getsf1').on('click', function(){
    $('#text-section').empty()
    $('#container-sf1').empty()
    $.ajax({
        url: '/forms/form1',
        type: 'GET',
        data: {
            syid                    : '{{$syid}}',
            semid                   : $(this).attr('data-semid'),
            levelid                  : $(this).attr('data-levelid'),
            strandid                : $(this).attr('data-strandid'),
            sectionid               : $(this).attr('data-sectionid'),
            action                  : 'getsf1'
        },
        success:function(data){                        
            $('#container-sf1').append(data)
        }
    })
})
</script>
@endif