
@extends('chairpersonportal.layouts.app2')

@section('pagespecificscripts')

@endsection

@section('content')
      <section class="content">
            <div class="card">
                  <div class="card-header">
                        PROSPECTUS
                  </div>
                  <div class="card-body p-0">
                        @foreach (DB::table('college_year')->get() as $year)
                      
                              @foreach (DB::table('semester')->where('deleted','0')->get() as $semester)
                                    @php
                                          $subjects = collect($prospectus)->where('yearDesc',$year->yearDesc)->where('semester',$semester->semester);
                                    @endphp
                                    @if(count($subjects) > 0 )
                                    
                                          <table class="table table-sm border-top border-bottom table-striped">
                                                <thead>
                                                      <tr>
                                                            <th colspan="3" class="p-2 text-primary">{{$year->yearDesc}} - {{$semester->semester}}</th>
                                                      </tr>
                                                      <tr >
                                                            <td width="20%">Code</td>
                                                            <td width="70%">Description</td>
                                                            <td width="5%" class="text-center">Units</td>
                                                          
                                                      </tr>
                                                </thead>
                                                <tbody >
                                                      @foreach ($subjects as $item)
                                                            <tr>
                                                                  <td class="align-middle">{{$item->subjCode}}</td>
                                                                  <td class="align-middle">{{$item->subjDesc}}</td>
                                                                  <td class="align-middle text-center pr-1">{{$item->subjectUnit}}</td>
                                                                  
                                                            </tr>
                                                      @endforeach   
                                                </tbody>
                                                <tfoot>
                                                      <tr>
                                                            <td></td>
                                                            <td class="text-right">Total Unit:</td>
                                                            <td class="text-center">{{collect($subjects)->sum('subjectUnit')}}</td>
                                                           
                                                      </tr>
                                                </tfoot>
                                          </table>
                                    @endif 
                              @endforeach
                  @endforeach
                  </div>
            </div>
      </section>
@endsection

@section('footerjavascript')
     
@endsection

