

@if(count($sections) == 0)
    <div class="card">
        <div class="card-header">
            <h4></h4>
        </div>
    </div>
@else
    <div class="row mb-2">
        <div class="col-md-4">

            <input class="filter form-control" placeholder="Search..." />
        </div>
    </div>
    @foreach($sections as $section)
        <div class="card shadow eachadvisory" style="border: none !important; box-shadow: 0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%) !important;" data-string="{{$section->levelname}}, {{$section->sectionname}} {{$section->firstname}} {{$section->middlename}} {{$section->lastname}} {{$section->suffix}}<">
            <div class="card-header" >
                <div class="row">
                    <div class="col-md-6 p-0">
                        <button type="button" class="btn btn-sm btn-default m-0 p-0 text-left" style="border: none !important;">
                            <span class="text-bold">{{$section->levelname}} - {{$section->sectionname}}</span> , ADVISER: <u>{{$section->firstname}} {{$section->middlename}} {{$section->lastname}} {{$section->suffix}}</u>
                        </button>
                        {{-- <h6 class="card-title" style="font-size: 13px !important;"><span class="text-bold">{{$section->levelname}} - {{$section->sectionname}}</span> - <u>{{$section->firstname}} {{$section->middlename}} {{$section->lastname}} {{$section->suffix}}</u></h6> --}}
                    </div>
                    <div class="col-md-6 text-right">
                        <button type="button" class="btn btn-sm btn-default m-0 p-0 @if(count($section->setups) == 0) text-danger @else text-success @endif" style="border: none !important;">
                            @if(count($section->setups) == 0) 
                            No setup created
                            @else
                            Date Created : {{date('M d, Y',strtotime($section->setups[0]->createddatetime))}} &nbsp;&nbsp;&nbsp;&nbsp;
                            @endif
                        </button>
                    {{-- </div>
                    <div class="col-md-3 text-right"> --}}
                        @if(count($section->setups) > 0) 
                            @if($section->setups[0]->lockstatus == 0)
                            <button type="button" class="btn btn-sm btn-default btn-lock" data-setupid="{{$section->setups[0]->id}}">
                                <i class="fa fa-unlock"></i> &nbsp;&nbsp;Lock SF2
                            </button>
                            @else
                            <button type="button" class="btn btn-sm btn-outline-danger btn-lock" data-setupid="{{$section->setups[0]->id}}">
                                <i class="fa fa-lock"></i> Locked SF2
                            </button>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
            {{-- <div class="card-body">
                <div class="row">
                    <div class="col-md-6"></div>
                    <div class="col-md-6 text-right">
                        
                    </div>
                </div>
            </div> --}}
        </div>
    @endforeach
    <script>
        
        $(".filter").on("keyup", function() {
            var input = $(this).val().toUpperCase();
            var visibleCards = 0;
            var hiddenCards = 0;

            $(".container").append($("<div class='card-group card-group-filter'></div>"));


            $(".eachadvisory").each(function() {
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
    </script>
@endif
