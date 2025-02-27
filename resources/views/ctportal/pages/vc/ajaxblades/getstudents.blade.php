<div class="tab-pane fade show active" id="custom-tabs-one-students" role="tabpanel" aria-labelledby="custom-tabs-one-students-tab">
    
<script src="{{asset('plugins/jquery/jquery-3-3-1.min.js')}}"></script>

<script>
    var $ = jQuery;
    $(document).ready(function(){
        $(".filtermale").on("keyup", function() {
            var input = $(this).val().toUpperCase();
            var visibleCards = 0;
            var hiddenCards = 0;

            $(".container").append($("<div class='card-group card-group-filter'></div>"));


            $(".studentcardmale").each(function() {
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
    })
    $(document).ready(function(){
        $(".filterfemale").on("keyup", function() {
            var input = $(this).val().toUpperCase();
            var visibleCards = 0;
            var hiddenCards = 0;

            $(".container").append($("<div class='card-group card-group-filter'></div>"));


            $(".studentcardfemale").each(function() {
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
    })
</script>
<div class="row">
    <div class="col-6">
        <div class="row">
            <div class="col-2">
                <label>MALE</label>
            </div>
            <div class="col-10">
                <input class="filtermale form-control mb-2" placeholder="Search student" />
            </div>
        </div>
        
        <div class="row">
        @foreach(collect($students)->sortBy('lastname') as $student)
            @if(strtolower($student->gender) == 'male')
                <div class="card studentcardmale text-center col-6" style="border: none !important;box-shadow: none !important;" data-string="{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}<">
                    <div class="card-body p-0" style="border: 1px solid#ddd;background: #e8e8e8;">
                        {{-- <small>{{$employee->utype}}</small> --}}
                        <p class="card-text text-center m-0">
                        <div class="widget-user-image">
                
                            <img class="img-circle elevation-2" src="{{asset($student->picurl)}}" 
                                onerror="this.onerror = null, this.src='{{asset($student->alt)}}'"
                                alt="User Avatar" style="width: 40% !important"
                                >
                      
                                {{-- <a href="/hr/employeeprofile?employeeid={{$student->id}}"> --}}
                                    <h6>{{$student->lastname}}, {{$student->firstname}} {{$student->suffix}}</h6>
                                {{-- </a> --}}
                        </div>
                        </p>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
    </div>
    <div class="col-6">
        <div class="row">
            <div class="col-2">
                <label>FEMALE</label>
            </div>
            <div class="col-10">
                <input class="filterfemale form-control mb-2" placeholder="Search student" />
            </div>
        </div>
        
        <div class="row">
            @foreach(collect($students)->sortBy('lastname') as $student)
                @if(strtolower($student->gender) == 'female')
                    <div class="card studentcardfemale text-center col-6" style="border: none !important;box-shadow: none !important;" data-string="{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}<">
                        <div class="card-body p-0" style="border: 1px solid#ddd;background: #e8e8e8;">
                            {{-- <small>{{$employee->utype}}</small> --}}
                            <p class="card-text text-center m-0">
                            <div class="widget-user-image">
                    
                                <img class="img-circle elevation-2" src="{{asset($student->picurl)}}" 
                                    onerror="this.onerror = null, this.src='{{asset($student->alt)}}'"
                                    alt="User Avatar" style="width: 40% !important"
                                    >
                        
                                    {{-- <a href="/hr/employeeprofile?employeeid={{$student->id}}"> --}}
                                        <h6>{{$student->lastname}}, {{$student->firstname}} {{$student->suffix}}</h6>
                                    {{-- </a> --}}
                            </div>
                            </p>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>