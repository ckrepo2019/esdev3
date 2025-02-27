<div class="col-md-4">
      <div class="card">
            <div class="card-header bg-primary">
                  <h3 class="card-title">BY GRADELEVEL</h3>
            </div>
            <div class="card-body p-0 table-responsive" style="height: 300px">
                  <table class="table" >
                        <thead>
                              <tr>
                                    <th width="80%">Gradelevel</th>
                                    <th width="20%" class="text-center">Count</th>
                              </tr>
                        </thead>
                        <tbody>
                              @foreach (DB::table('gradelevel')->where('deleted',0)->orderBy('sortid')->get() as $item)

                                    @if($item->acadprogid == 3 || $item->acadprogid == 4 || $item->acadprogid == 5)
                                          <tr>
                                                <td>{{$item->levelname}}</td>
                                                <td class="text-center">{{collect($students)->get('g'.preg_replace('/[^0-9]/', '', $item->levelname))}}</td>
                                          </tr>
                                    @elseif($item->acadprogid == 2)

                                          @if($item->levelname == 'KINDER 1')
                                                <tr>
                                                      <td>{{$item->levelname}}</td>
                                                      <td class="text-center">{{collect($students)->get('k1')}}</td>
                                                </tr>

                                          @elseif($item->levelname == 'KINDER 2')

                                                <tr>
                                                      <td>{{$item->levelname}}</td>
                                                      <td class="text-center">{{collect($students)->get('k2')}}</td>
                                                </tr>

                                          @elseif($item->levelname == 'NURSERY')

                                                <tr>
                                                      <td>{{$item->levelname}}</td>
                                                      <td class="text-center">{{collect($students)->get('n')}}</td>
                                                </tr>
                                                
                                          @endif

                                    @endif
                              @endforeach
                             

                        </tbody>
                  </table>
            </div>
            <div class="card-footer pt-0 pb-0">
                  <table class="table mb-0">
                        <tbody>
                              <tr>
                                    <th width="80%" style="border-top:0">Total</th>
                                    <th width="20%" style="border-top:0" class="text-center">{{collect($students)->get('totalenrolledstudents')}}</th>
                              </tr>
                        </tbody>
                  </table>
            </div>
      </div>
</div>

<div class="col-md-8">
      <div class="card">
            <div class="card-header bg-primary">
                  <h3 class="card-title">BY ACADEMIC PROGRAM</h3>
            </div>
            <div class="card-body p-0">
                  <table class="table">
                        <thead>
                              <th width="80%">Acadeimic Program</th>
                              <th width="20%" class="text-center">Count</th>
                        </thead>
                        <tbody>
                              @foreach (DB::table('academicprogram')->get() as $item)
                                    @if($item->id == 2)
                                          <tr class="@if(collect($students)->get('nursery') > 0) bg-info @endif" >
                                                <td>{{$item->progname}}</td>
                                                <td class="text-center">{{collect($students)->get('nursery')}}</td>
                                          </tr>

                                    @elseif($item->id == 3)
                                          <tr class="@if(collect($students)->get('gradeschool') > 0) bg-info @endif">
                                                <td>{{$item->progname}}</td>
                                                <td class="text-center">{{collect($students)->get('gradeschool')}}</td>
                                          </tr>
                                    @elseif($item->id == 4)
                                          <tr class="@if(collect($students)->get('juniorhigh') > 0) bg-info @endif">
                                                <td>{{$item->progname}}</td>
                                                <td class="text-center">{{collect($students)->get('juniorhigh')}}</td>
                                          </tr>
                                    @elseif($item->id == 5)
                                          <tr class="@if(collect($students)->get('enrrolledseniorhigh') > 0) bg-info @endif">
                                                <td>{{$item->progname}}</td>
                                                <td class="text-center">{{collect($students)->get('enrrolledseniorhigh')}}</td>
                                          </tr>
                                    @elseif($item->id == 6)
                                          <tr class="@if(collect($students)->get('college') > 0) bg-info @endif">
                                                <td>{{$item->progname}}</td>
                                                <td class="text-center">{{collect($students)->get('college')}}</td>
                                          </tr>
                                    @endif
                              @endforeach
                        </tbody>
                  </table>
            </div>
            <div class="card-footer pt-0 pb-0">
                  <table class="table mb-0" >
                        <tbody>
                              <tr >
                                    <th width="80%" style="border-top:0">Total</th>
                                    <th width="20%" style="border-top:0" class="text-center">{{collect($students)->get('totalenrolledstudents')}}</th>
                              </tr>
                        </tbody>
                  </table>
            </div>
      </div>
</div>
<div class="col-md-12">
      <div class="card">
            <div class="card-header bg-primary">
                  <h3 class="card-title">STUDENT LIST</h3>
            </div>
            <div class="card-body p-0 table-resposive" style="height: 182px">
                  <table class="table" id="table-studens">
                        <thead>
                              <tr>
                                    <th>Student</th>
                                    <th>Grade Level</th>
                                    <th>Section</th>
                                    <th>Teacher</th>
                              </tr>
                        </thead>
                        <tbody>
                              
                        </tbody>
                  </table>
            </div>
            
      </div>
</div>