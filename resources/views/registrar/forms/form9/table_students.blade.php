

@if(count($students) == 0)
<div class="alert alert-warning" role="alert">No sections assigned in the selected grade level</div>
@else
    <div class="row mb-2">
        <div class="col-md-6">
            <input class="filter form-control" placeholder="Search student" />
        </div>
        <div class="col-md-6 text-right">
            
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">        
            <label>MALE</label>
            @foreach (collect($students)->values() as $student)
                @if(strtolower($student->gender) == 'male')
                    
                <div class="card card-student" data-string="{{$student->lastname}}, {{$student->firstname}}<">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-12" style="font-size: 13px;">
                                <div class="row">
                                    <div class="col-6">
                                        <span data-studid="{{$student->id}}"><strong>{{$student->lastname}}</strong>, {{$student->firstname}}</span><br/>
                                        <span class="text-muted">Student ID: {{$student->sid}}</span>
                                    </div>
                                    <div class="col-6 text-right">
                                        <form action="/prinsf9print/{{$student->id}}" method="get" target="_blank" class="m-0 p-0" style="display: inline;">
                                            <input type="hidden" name="action" value="preview"/>
                                            <input type="hidden" name="studentid" value="{{$student->id}}"/>
                                            <input type="hidden" name="sectionid" value="{{$student->sectionid}}"/>
                                            <input type="hidden" name="syid" value="{{$syid}}"/>
                                            <button type="submit"  class="btn btn-sm btn-default toModal" id="{{$student->id}}">View SF 9</button>
                                        </form>
                                        @if($acadprogid == 5 && strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sma')
                                        
                                        <form action="/reports/form9/view" method="get" target="_blank" class="m-0 p-0" style="display: inline;">
                                            <input type="hidden" name="action" value="preview"/>
                                            <input type="hidden" name="export" value="1"/>
                                            <input type="hidden" name="studentid" value="{{$student->id}}"/>
                                            <input type="hidden" name="selectedsectionid" value="{{$student->sectionid}}"/>
                                            <input type="hidden" name="selectedschoolyear" value="{{$syid}}"/>
                                            <input type="hidden" name="selectedsemester" value="{{$semid}}"/>
                                            <input type="hidden" name="selectedgradelevel" value="{{$levelid}}"/>
                                            <button type="submit"  class="btn btn-sm btn-default toModal" id="{{$student->id}}">SF 9 B</button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!-- /.card-tools -->
                    </div>
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table m-0" style="font-size: 11px;">
                                    <tr>
                                        <th style="width: 40%;" class="text-right p-1">Student Contact No. :</th>
                                        <td class="p-1">{{$student->contactno}}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-right p-1">Parent/Guardian :</th>
                                        <td class="p-1">
                                            @if($student->ismothernum == 1)
                                                {{$student->mothername}}
                                            @endif
                                            @if($student->isfathernum == 1)
                                                {{$student->fathername}}
                                            @endif
                                            @if($student->isguardannum == 1)
                                                {{$student->guardianname}}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right p-1">Contact No. :</th>
                                        <td class="p-1">
                                            @if($student->ismothernum == 1)
                                                {{$student->mcontactno}}
                                            @endif
                                            @if($student->isfathernum == 1)
                                                {{$student->fcontactno}}
                                            @endif
                                            @if($student->isguardannum == 1)
                                                {{$student->gcontactno}}
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                </div>
                @endif
            @endforeach
        </div>
        <div class="col-md-6">        
            <label>FEMALE</label>
            @foreach (collect($students)->values() as $student)
                @if(strtolower($student->gender) == 'female')
                    
                <div class="card card-student" data-string="{{$student->lastname}}, {{$student->firstname}}<">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-12" style="font-size: 13px;">
                                <div class="row">
                                    <div class="col-6">
                                        <span data-studid="{{$student->id}}"><strong>{{$student->lastname}}</strong>, {{$student->firstname}}</span><br/>
                                        <span class="text-muted">Student ID: {{$student->sid}}</span>
                                    </div>
                                    <div class="col-6 text-right">
                                        <form action="/prinsf9print/{{$student->id}}" method="get" target="_blank" class="m-0 p-0" style="display: inline;">
                                            <input type="hidden" name="action" value="preview"/>
                                            <input type="hidden" name="studentid" value="{{$student->id}}"/>
                                            <input type="hidden" name="sectionid" value="{{$student->sectionid}}"/>
                                            <input type="hidden" name="syid" value="{{$syid}}"/>
                                            <button type="submit"  class="btn btn-sm btn-default toModal" id="{{$student->id}}">View SF 9</button>
                                        </form>
                                        @if($acadprogid == 5 && strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sma')
                                        
                                        <form action="/reports/form9/view" method="get" target="_blank" class="m-0 p-0" style="display: inline;">
                                            <input type="hidden" name="action" value="preview"/>
                                            <input type="hidden" name="export" value="1"/>
                                            <input type="hidden" name="studentid" value="{{$student->id}}"/>
                                            <input type="hidden" name="selectedsectionid" value="{{$student->sectionid}}"/>
                                            <input type="hidden" name="selectedschoolyear" value="{{$syid}}"/>
                                            <input type="hidden" name="selectedsemester" value="{{$semid}}"/>
                                            <input type="hidden" name="selectedgradelevel" value="{{$levelid}}"/>
                                            <button type="submit"  class="btn btn-sm btn-default toModal" id="{{$student->id}}">SF 9 B</button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                        </div>
                    </div>
                    <!-- /.card-tools -->
                    </div>
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table m-0" style="font-size: 11px;">
                                    <tr>
                                        <th style="width: 40%;" class="text-right p-1">Student Contact No. :</th>
                                        <td class="p-1">{{$student->contactno}}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-right p-1">Parent/Guardian :</th>
                                        <td class="p-1">
                                            @if($student->ismothernum == 1)
                                                {{$student->mothername}}
                                            @endif
                                            @if($student->isfathernum == 1)
                                                {{$student->fathername}}
                                            @endif
                                            @if($student->isguardannum == 1)
                                                {{$student->guardianname}}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right p-1">Contact No. :</th>
                                        <td class="p-1">
                                            @if($student->ismothernum == 1)
                                                {{$student->mcontactno}}
                                            @endif
                                            @if($student->isfathernum == 1)
                                                {{$student->fcontactno}}
                                            @endif
                                            @if($student->isguardannum == 1)
                                                {{$student->gcontactno}}
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                </div>
                @endif
            @endforeach
        </div>
    </div>
    <script>
        $(document).ready(function(){
            
        $(".filter").on("keyup", function() {
            var input = $(this).val().toUpperCase();
            var visibleCards = 0;
            var hiddenCards = 0;

            $(".container").append($("<div class='card-group card-group-filter'></div>"));

            $(".card-student").each(function() {
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
@endif