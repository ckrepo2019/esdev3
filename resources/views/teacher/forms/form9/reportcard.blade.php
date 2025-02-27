@extends('teacher.layouts.app')

@section('content')
<style>
    td  { text-transform: uppercase; }
    td, th {
        padding: 3px 5px !important;
    }
</style>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h5 class="m-0 text-dark">School Form 9 <br/> <small><em>Learner's Progress Report Card</small></em></h5>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item"><a href="/forms/index/form9">School Form 9</a></li>
                    <li class="breadcrumb-item active"><a href="/forms/index/form9">{{$info[0]->levelname}} - {{$info[0]->sectionname}}</a></li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>
</section>
@if(isset($students))
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
                
            <div class="card card-primary card-student card-shadow" style="border: none; box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important;
        }"  data-string="{{$student->lastname}}, {{$student->firstname}}<">
                <div class="card-header ">
                      <div class="row">
                          <div class="col-12" style="font-size: 13px;">
                            <div class="row">
                                <div class="col-8">
                                    <span data-studid="{{$student->id}}"><strong>{{$student->lastname}}</strong>, {{$student->firstname}}</span>
                                </div>
                                <div class="col-4 text-right">
                                    <span class="text-muted">{{$student->sid}}</span>
                                </div>
                            </div>
                            <div class="card-tools mt-2">
                                <form action="/prinsf9print/{{$student->id}}" method="get" target="_blank">
                                {{-- <form action="/forms/form9" method="get"> --}}
                                    <input type="hidden" name="action" value="preview"/>
                                    <input type="hidden" name="studentid" value="{{$student->id}}"/>
                                    <input type="hidden" name="sectionid" value="{{$info[0]->sectionid}}"/>
                                    <input type="hidden" name="syid" value="{{$syid}}"/>
                                    <button type="submit"  class="btn btn-sm btn-default toModal" id="{{$student->id}}">View Report card</button>
                                </form>
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
                                    <th style="width: 40%;" class="text-right">Student Contact No. :</th>
                                    <td>{{$student->contactno}}</td>
                                </tr>
                                <tr>
                                    <th class="text-right">Parent/Guardian :</th>
                                    <td>
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
                                    <th class="text-right">Contact No. :</th>
                                    <td>
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
                
            <div class="card card-primary card-student card-shadow" style="border: none; box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important;
        }"  data-string="{{$student->lastname}}, {{$student->firstname}}<">
                <div class="card-header ">
                      <div class="row">
                          <div class="col-12" style="font-size: 13px;">
                            <div class="row">
                                <div class="col-8">
                                    <span data-studid="{{$student->id}}"><strong>{{$student->lastname}}</strong>, {{$student->firstname}}</span>
                                </div>
                                <div class="col-4 text-right">
                                    <span class="text-muted">{{$student->sid}}</span>
                                </div>
                            </div>
                            <div class="card-tools mt-2">
                                <form action="/prinsf9print/{{$student->id}}" method="get" target="_blank">
                                    <input type="hidden" name="action" value="preview"/>
                                    <input type="hidden" name="studentid" value="{{$student->id}}"/>
                                    <input type="hidden" name="sectionid" value="{{$info[0]->sectionid}}"/>
                                    <input type="hidden" name="syid" value="{{$syid}}"/>
                                    <button type="submit"  class="btn btn-sm btn-default toModal" id="{{$student->id}}">View Report card</button>
                                </form>
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
                                    <th style="width: 40%;" class="text-right">Student Contact No. :</th>
                                    <td>{{$student->contactno}}</td>
                                </tr>
                                <tr>
                                    <th class="text-right">Parent/Guardian :</th>
                                    <td>
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
                                    <th class="text-right">Contact No. :</th>
                                    <td>
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
@endif
    {{-- <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="main-card mb-3 card ">
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Contact Number</th>
                                <th>Parent/Guardian</th>
                                <th>Contact Number</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($students))
                            @foreach ($students as $student)
                            <tr>
                                <td>
                                    <form action="/forms/form9" method="get">
                                        <input type="hidden" name="action" value="preview"/>
                                        <input type="hidden" name="studentid" value="{{$student[0]->id}}"/>
                                        <input type="hidden" name="sectionid" value="{{$info[0]->sectionid}}"/>
                                        <input type="hidden" name="syid" value="{{$syid}}"/>
                                        <button type="submit" class="btn btn-light btn-block">{{$student[0]->lastname}}, {{$student[0]->firstname}}</button>
                                    </form>
                                </td>
                                <td>{{$student[0]->contactno}}</td>
                                <td>
                                    @if($student[0]->ismothernum == 1)
                                        {{$student[0]->mothername}}
                                    @endif
                                    @if($student[0]->isfathernum == 1)
                                        {{$student[0]->fathername}}
                                    @endif
                                    @if($student[0]->isguardannum == 1)
                                        {{$student[0]->guardianname}}
                                    @endif
                                </td>
                                <td>
                                    @if($student[0]->ismothernum == 1)
                                        {{$student[0]->mcontactno}}
                                    @endif
                                    @if($student[0]->isfathernum == 1)
                                        {{$student[0]->fcontactno}}
                                    @endif
                                    @if($student[0]->isguardannum == 1)
                                        {{$student[0]->gcontactno}}
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> --}}
    <script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>
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
@endsection