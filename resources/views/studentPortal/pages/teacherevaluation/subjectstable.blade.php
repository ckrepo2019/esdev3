@php
      $quarter_string = '';

      if($quarter == 1){
            $quarter_string = 'First Quarter';
      }
      elseif($quarter == 2){
            $quarter_string = 'Second Quarter';
      }
      elseif($quarter == 3){
            $quarter_string = 'Third Quarter';
      }
      elseif($quarter == 4){
            $quarter_string = 'Fourth Quarter';
      }
@endphp




<div class="row">
      <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-chart-pie"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Quarter</span>
                  <span class="info-box-number">
                        {{$quarter_string}}
                  </span>
              </div>
            </div>
      </div>
      <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-layer-group"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Subjects</span>
                  <span class="info-box-number">
                        {{collect($subjects)->count()}}
                  </span>
              </div>
            </div>
      </div>
      <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-thumbs-up"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Answered</span>
                  <span class="info-box-number">
                        {{collect($subjects)->where('status',1)->count()}}
                  </span>
              </div>
            </div>
      </div>
      <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-thumbs-down"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Not Answered</span>
                  <span class="info-box-number">
                        {{collect($subjects)->where('status',0)->count()}}
                  </span>
              </div>
            </div>
      </div>
</div>

<div class="row">
      <div class="col-md-12">
            
            <table class="table table-sm">
                  <thead>
                        <tr>
                              <th width="50%">Subject</th>
                              <th width="40%">Teacher</th>
                              <th width="10%"></th>
                        </tr>
                  </thead>
                  <tbody>
                        @foreach ($subjects as $item)
                              <tr data-subj="{{$item->subjid}}" data-tid="{{$item->teacherid}}" class="subj_tr">
                                    <td>{{$item->subject}}</td>
                                    <td>{{$item->teacher}}</td>
                                    @if($item->status == 1)
                                          <td><button class="btn btn-primary evalbutton btn-block btn-sm" data-subj="{{$item->subjid}}" data-tid="{{$item->teacherid}}" data-status="1" data-teacher-name="{{$item->lastname.', '.$item->firstname}}" data-subjdesc="{{$item->subject}}">EVALUATED</button></td>
                                    @elseif($item->status == 0)
                                          <td><button class="btn btn-success evalbutton btn-block btn-sm" data-subj="{{$item->subjid}}" data-tid="{{$item->teacherid}}" data-status="0" data-teacher-name="{{$item->lastname.', '.$item->firstname}}" data-subjdesc="{{$item->subject}}">EVALUATE</button></td>
                                    @endif
                              </tr>
                        @endforeach
                        
                  </tbody>
            
            </table>
      </div>
</div>

