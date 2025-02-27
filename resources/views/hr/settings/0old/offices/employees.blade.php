
<div class="row">
    <div class="col-md-12">
        
        <div class="form-group mb-1">
            <div class="input-group">
                <input type="text" class="form-control" id="input-officename-update" value="{{$officeinfo->officename}}"/>
                <div class="input-group-append">
                    <div class="input-group-text bg-warning" style="cursor: pointer;" id="btn-update-office" data-officeid="{{$officeid}}"><i class="fa fa-edit"></i></div>
                    <div class="input-group-text bg-danger" style="cursor: pointer;" id="btn-delete-office" data-officeid="{{$officeid}}"><i class="fa fa-trash-alt"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        
        <div class="form-group mb-0">
            <div class="input-group">
                <select class="select2 form-control mt-1" id="select-personnels" multiple="multiple" style="border-left: hidden;border-right: hidden;border-top: hidden;" data-placeholder="">
                    @foreach ($unassigned as $eachunassigned)
                        <option value="{{$eachunassigned->id}}">{{ucwords($eachunassigned->lastname)}}, {{ucwords($eachunassigned->firstname)}}</option>
                    @endforeach
                </select>
                <div class="input-group-append">
                    <div class="input-group-text" style="cursor: pointer;" id="btn-submit-personnel" data-officeid="{{$officeid}}">Add Personnel</div>
                </div>
            </div>
        </div>
    </div>
</div>
@if(count($employees)>0)
<div class="row">
    <div class="col-md-12"><h4>Employees</h4></div>
</div>
<input type="text" class="form-control mt-2 mb-2" id="input-search-employees" placeholder="Search..."/>
<div class="row">
    @foreach($employees as $employee)    
        <div class="col-md-12 div-each-employee" data-string="{{$employee->lastname}}, {{$employee->firstname}} {{$employee->suffix}} {{$employee->presposition}}<">
            <div class="card shadow-lg" style="border: none;">
                <div class="card-header p-2">
                    <div class="post">
                        <div class="user-block m-0">
                            @php
                              $number = rand(1,3);
                              if(strtoupper($employee->gender) == 'FEMALE'){
                                  $avatar = 'avatar/T(F) '.$number.'.png';
                              }
                              else{
                                  $avatar = 'avatar/T(M) '.$number.'.png';
                              }
                            @endphp
              
                            <img class="img-circle img-bordered-sm" src="{{asset($employee->picurl)}}" 
                            onerror="this.onerror = null, this.src='{{asset($avatar)}}'" alt="user image">
                            <span class="username">
                                <a href="#">{{$employee->lastname}}, {{$employee->firstname}} {{$employee->middlename}}</a>
                            </span>
                            <span>
                                <ul class="nav pl-3">
                                    <li class="nav-item">
                                        <a class="nav-link active btn-sm p-0" href="#activity{{$employee->id}}" data-toggle="tab">Profile</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link btn-sm p-0" href="#settings{{$employee->id}}" data-toggle="tab"><i class="fa fa-edit"></i> Edit</a>
                                    </li>
                                </ul>
                            </span>
                        </div>  
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="tab-content">
                        <div class="active tab-pane" id="activity{{$employee->id}}">            
                            <div class="post">         
                                <div class="row m-0">
                                    <div class="col-md-12 p-0">
                                        <table class="table table-bordered mb-2" style="width: 100%; font-size: 11px;">
                                            <tr>
                                                <td class="p-0" style="width: 30%;">&nbsp;&nbsp;&nbsp;Title/Degree</td>
                                                <td class="p-0">{{$employee->title}}</td>
                                            </tr>
                                            <tr>
                                                <td class="p-0" style="width: 30%;">&nbsp;&nbsp;&nbsp;Major-Minor</td>
                                                <td class="p-0">{{$employee->majorin}}</td>
                                            </tr>
                                            <tr>
                                                <td class="p-0" style="width: 30%;">&nbsp;&nbsp;&nbsp;Where Obtained</td>
                                                <td class="p-0">{{$employee->degreewhere}}</td>
                                            </tr>
                                            <tr>
                                                <td class="p-0" style="width: 30%;">&nbsp;&nbsp;&nbsp;MA/MBA</td>
                                                <td class="p-0">{{$employee->ma_mba}}</td>
                                            </tr>
                                            <tr>
                                                <td class="p-0" style="width: 30%;">&nbsp;&nbsp;&nbsp;Where Obtained</td>
                                                <td class="p-0">{{$employee->ma_mbawhere}}</td>
                                            </tr>
                                            <tr>
                                                <td class="p-0" style="width: 30%;">&nbsp;&nbsp;&nbsp;Doctorate Degree</td>
                                                <td class="p-0">{{$employee->doctoratedegree}}</td>
                                            </tr>
                                            <tr>
                                                <td class="p-0" style="width: 30%;">&nbsp;&nbsp;&nbsp;Where Obtained</td>
                                                <td class="p-0">{{$employee->doctoratedegreewhere}}</td>
                                            </tr>
                                            <tr>
                                                <td class="p-0" style="width: 30%;">&nbsp;&nbsp;&nbsp;Previous Position</td>
                                                <td class="p-0">{{$employee->prevposition}}</td>
                                            </tr>
                                            <tr>
                                                <td class="p-0" style="width: 30%;">&nbsp;&nbsp;&nbsp;Experience</td>
                                                <td class="p-0">{{$employee->prevpositionexp}}</td>
                                            </tr>
                                            <tr>
                                                <td class="p-0" style="width: 30%;">&nbsp;&nbsp;&nbsp;Present Position</td>
                                                <td class="p-0">{{$employee->presposition}}</td>
                                            </tr>
                                            <tr>
                                                <td class="p-0" style="width: 30%;">&nbsp;&nbsp;&nbsp;Experience</td>
                                                <td class="p-0">{{$employee->prespositionexp}}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>  
                        </div>            
                        <div class="tab-pane" id="settings{{$employee->id}}">
                            <div class="row m-0">
                                <div class="col-md-12 p-0">
                                    <table class="table table-bordered mb-0" style="width: 100%; font-size: 12px;">
                                        <tr>
                                            <td class="p-0" style="width: 30%;">&nbsp;&nbsp;&nbsp;Title/Degree</td>
                                            <td class="p-0">
                                                <input type="text" class="form-control form-control-sm p-0 m-0 input-title" style="height: 20px;" value="{{$employee->title}}"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="p-0" style="width: 30%;">&nbsp;&nbsp;&nbsp;Major-Minor</td>
                                            <td class="p-0">
                                                <input type="text" class="form-control form-control-sm p-0 m-0 input-major" style="height: 20px;" value="{{$employee->majorin}}"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="p-0" style="width: 30%;">&nbsp;&nbsp;&nbsp;Where Obtained</td>
                                            <td class="p-0">
                                                <input type="text" class="form-control form-control-sm p-0 m-0 input-where" style="height: 20px;" value="{{$employee->title}}"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="p-0" style="width: 30%;">&nbsp;&nbsp;&nbsp;MA/MBA</td>
                                            <td class="p-0">
                                                <input type="text" class="form-control form-control-sm p-0 m-0 input-mamba" style="height: 20px;" value="{{$employee->ma_mba}}"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="p-0" style="width: 30%;">&nbsp;&nbsp;&nbsp;Where Obtained</td>
                                            <td class="p-0">
                                                <input type="text" class="form-control form-control-sm p-0 m-0 input-mambawhere" style="height: 20px;" value="{{$employee->ma_mbawhere}}"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="p-0" style="width: 30%;">&nbsp;&nbsp;&nbsp;Doctorate Degree</td>
                                            <td class="p-0">
                                                <input type="text" class="form-control form-control-sm p-0 m-0 input-doctorate" style="height: 20px;" value="{{$employee->doctoratedegree}}"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="p-0" style="width: 30%;">&nbsp;&nbsp;&nbsp;Where Obtained</td>
                                            <td class="p-0">
                                                <input type="text" class="form-control form-control-sm p-0 m-0 input-doctoratewhere" style="height: 20px;" value="{{$employee->doctoratedegreewhere}}"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="p-0" style="width: 30%;">&nbsp;&nbsp;&nbsp;Previous Position</td>
                                            <td class="p-0">
                                                <input type="text" class="form-control form-control-sm p-0 m-0 input-prevposition" style="height: 20px;" value="{{$employee->prevposition}}"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="p-0" style="width: 30%;">&nbsp;&nbsp;&nbsp;Experience</td>
                                            <td class="p-0">
                                                <input type="text" class="form-control form-control-sm p-0 m-0 input-prevpositionexp" style="height: 20px;" value="{{$employee->prevpositionexp}}"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="p-0" style="width: 30%;">&nbsp;&nbsp;&nbsp;Present Position</td>
                                            <td class="p-0">
                                                <input type="text" class="form-control form-control-sm p-0 m-0 input-presposition" style="height: 20px;" value="{{$employee->presposition}}"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="p-0" style="width: 30%;">&nbsp;&nbsp;&nbsp;Experience</td>
                                            <td class="p-0">
                                                <input type="text" class="form-control form-control-sm p-0 m-0 input-prespositionexp" style="height: 20px;" value="{{$employee->prespositionexp}}"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="p-1"><button type="button" class="btn btn-sm btn-block btn-success m-0 p-0 btn-update-data" dataid="{{$employee->id}}" data-officeid="{{$employee->officeid}}"><i class="fa fa-share"></i> Save Changes</button></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>            
                    </div>            
                </div>
            </div>     
        </div>
    @endforeach
</div>
<script>
    $("#input-search-employees").on("keyup", function() {
        var input = $(this).val().toUpperCase();
        var visibleCards = 0;
        var hiddenCards = 0;

        $(".container").append($("<div class='card-group card-group-filter'></div>"));

        $(".div-each-employee").each(function() {
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