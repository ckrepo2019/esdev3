<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
      <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
</head>
<body>
      @if($isGradeSchoool)
            <div class="row">
                  <div class="col-md-4">
                        @foreach ($sf10gradeschool as $item)
                              <table>
                                    <tr>
                                          <td>CLASSIFIED AS : {{$item[0]->levelname}}</td>
                                          <td>School:  {{$item[0]->schoolname}}</td>
                                          <td>SchoolYear:  {{$item[0]->schoolyear}}</td>
                                    </tr>
                              </table>

                              <table class="mb-0 table table-bordered mt-4">
                                    <thead>
                                          <tr>
                                                <td class="p-1 align-middle text-center" rowspan="2" width="60%"><small>SUBJECTS</small></td>
                                                <td class="p-1 align-middle" align="center" colspan="4" width="20%"><small>PERIODIC RATINGS</small></td>
                                                <td class="p-1 align-middle" align="center" rowspan="2" width="10%"><small>FR</small></td>
                                                <td class="p-1 align-middle" align="center" rowspan="2" width="10%"><small>AT</small></td>

                                          </tr>
                                          <tr align="center">
                                                <td class="p-1"><small>1</small></td>
                                                <td class="p-1"><small>2</small></td>
                                                <td class="p-1"><small>3</small></td>
                                                <td class="p-1"><small>4</small></td>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          @foreach($item as $gradeitem)
                                   
                                                <tr>
                                                      <td>{{$gradeitem->subj_desc}}</td>
                                                      <td>{{$gradeitem->quarter1}}</td>
                                                      <td>{{$gradeitem->quarter2}}</td>
                                                      <td>{{$gradeitem->quarter3}}</td>
                                                      <td>{{$gradeitem->quarter4}}</td>
                                                      <td>{{$gradeitem->finalrating}}</td>
                                                      <td>{{$gradeitem->action}}</td>
                                                </tr>

                                          @endforeach
                                    </tbody>
                                    
                              </table>

                        @endforeach
                  </div>
            </div>
      @endif

</body>

</html>
