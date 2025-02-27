@php
      $canEdit = true;

      $field = 'q'.$quarter.'val';
      $comfield = 'q'.$quarter.'com';
      $countEval = collect($evaluations)->where($field,'!=',null)->count();
      if($countEval > 0){
            $canEdit = false;
      }

@endphp

<div class="row">
      <div class="col-12 col-sm-6 col-md-4" hidden>
            <div class="info-box">
              <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-chart-pie"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Quarter</span>
                  <span class="info-box-number" id="selected_teacher_box">
                        {{-- {{$quarter_string}} --}}
                  </span>
              </div>
            </div>
      </div>
      <div class="col-12 col-sm-6 col-md-5">
            <div class="info-box">
              <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-layer-group"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Subjects</span>
                  <span class="info-box-number" id="selected_subject_box">
                  </span>
              </div>
            </div>
      </div>
      <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
           @if($canEdit)
                  <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-thumbs-down"></i></span>
           @else
                  <span class="info-box-icon bg-success elevation-1"><i class="fas fa-thumbs-up"></i></span>
           @endif
              

              <div class="info-box-content">
                <span class="info-box-text">Status</span>
                  <span class="info-box-number">
                       @if($canEdit)
                              NOT EVALUATED
                       @else
                              EVALUATED
                       @endif
                  </span>
              </div>
            </div>
      </div>
</div>


<div class="row">
      <div class="col-md-12">
            <table class="table table-sm"  id="evaluation_table" data-head="{{$headerid}}">
                  <thead>
                        <tr>
                              <th width="90%">Question</th>
                              <th width="10%">Evaluation</th>
                        </tr>
                  </thead>
                  <tbody>
                        @foreach ($evalquestions as $item)
                              <tr>
                                    <td>{{$item->description}}</td>
                                    <td class="text-center align-middle">
                                          @if($canEdit)
                                                <select name="" id="" class="rating form-control form-control-sm" data-id="{{$item->id}}">
                                                      @foreach ($ratingValue as $ratingitem)
                                                            <option value="{{$ratingitem->value}}">{{$ratingitem->description}}</option>
                                                      @endforeach
                                                </select>
                                          @else
                                            @php
                                                $temp_eval = collect($evaluations)->where('gsid',$item->id)->first();
                                            @endphp
                                            @if(isset($temp_eval->$field))
                                                {{$temp_eval->$field}}
                                            @endif
                                            
                                            
                                              
                                          @endif
                                          
                                    </td>
                              </tr>
                        @endforeach
                  </tbody>
            </table>
            <div class="row">
                  <div class="col-md-12 form-group">
                        <label for="">Comments and suggestions.</label>
                        @if($canEdit)
                              <textarea type="text" class="form-control rating" data-id="comment"></textarea>
                        @else
                              <textarea type="text" class="form-control rating" data-id="comment" readonly>{{$ratingComment[0]->$comfield}}</textarea>
                        @endif
                  </div>
            </div>
      </div>
</div>
@if($canEdit)
      <div class="row">
            <div class="col-md-3">
                  <button class="btn btn-success" id="submit_eval" hidden>SUBMIT EVALUATION</button>
            </div>
      </div>
@endif

     