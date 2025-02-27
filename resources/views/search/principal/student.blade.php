<input type="hidden" value="{{$data[0]->count}}" id="searchCount">
@if($data[0]->count > 0)
    <div class="row d-flex align-items-stretch pt-2 pr-4 pl-4 pb-2">
        @if(!$isTableForm)
            @foreach ($data[0]->data as $student)
                <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretc">
                    <div class="card bg-light w-100 mb-2">
                        <div class="card-body p-2 ">
                            <div class="row">
                                <div class="col-7 text-sm">
                                    <h2 class="lead appadd" style="height:20px !important">
                                        <b> {{strtoupper(explode(' ',trim($student->lastname))[0])}}</b>
                                    </h2>
                                    <h6 class="appadd" style="height:20px !important">
                                        {{strtoupper($student->firstname)}}
                                    </h6>
                                    <p class="mt-2">{{$student->sid}}</p>
                                    {{$student->enlevelname}} 
                                    <br>
                                    {{$student->ensectname}}
                                </div>
                                @php
                                    $randomnum = rand(1, 4);

                                    if($student->gender == 'FEMALE'){
                                        $avatar = 'avatars/S(F) '.$randomnum.'.png';
                                    }
                                    else{
                                        $avatar = 'avatars/S(M) '.$randomnum.'.png';
                                    }
                                @endphp
                            
                                    <div class="col-5 text-center">
                                        <img 
                                        src="{{asset($student->picurl)}}" 
                                        alt="" 
                                        onerror="this.src='{{asset($avatar)}}'"
                                        class="img-circle img-fluid">
                                    </div>
                            
                            
                            </div>
                        </div>
                        <a href="/principalPortalStudentProfile/{{Crypt::encrypt($student->id)}}/{{Crypt::encrypt($student->acadprogid)}}" class="card-footer bg-info text-center pt-1 pb-1"><span class="text-white">More info </span><i class=" text-white fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            @endforeach 
        @else
          
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Grade Level</th>
                        <th>Gender</th>
                        <th>Grantee</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data[0]->data as $student)
                        <tr>
                            <td>{{$student->firstname}} {{$student->lastname}}</td>
                            <td>{{$student->levelname}}</td>
                            <td>{{$student->gender}}</td>
                            <td>
                                @if($student->grantee == 1)
                                    REGULAR
                                @elseif($student->grantee == 2)
                                    ESC
                                @else
                                    VOUCHER
                                @endif
                            </td>
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
        @endif
    </div>
@elseif($data[0]->count == 0)
    <div class="container h-100" >
        <div class="row align-items-center h-100" style="min-height:430px; !important">
            <div class="mx-auto">
                NO STUDENT FOUND
            </div>
        </div>
    </div>
@else
    <div class="container h-100" >
        <div class="row align-items-center h-100" style="min-height:430px; !important">
            <div class="mx-auto">
                SELECT ACADEMIC PROGRAM
            </div>
        </div>
    </div>
@endif