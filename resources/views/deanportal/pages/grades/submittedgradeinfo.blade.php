<table  class="table table-striped">
      <tr>
            <th width="20%">Section</th>
            <td width="80%" colspan="2">{{$gradelogs->sectionDesc}}</td>
      </tr>
      <tr>
            <th width="20%">Subject</th>
            <td colspan="2">{{$gradelogs->subjDesc}}</td>
      </tr>
      <tr>
            <th width="20%" class="align-middle">Status</th>
            @if($gradeStatus == 1)
                  <td class="align-middle">Submitted</td>
                  <td class="align-middle"> <button class="btn btn-primary btn-sm post_grade" data-id="{{$gradelogs->id}}" data-term="{{$gradelogs->term}}" data-logid="@if(isset($gradelogs->logid)){{$gradelogs->logid}}@endif">Post Grades</button></td>
            @elseif($gradeStatus == 2)
                  <td colspan="2">
                        @if($gradeStatus == 1)
                              Submitted
                        @elseif($gradeStatus == 2)
                              Posted
                        @endif
                  </td>
            @endif
      </tr>
</table>


<table class="table table-striped">
      <thead>
            <tr>
                  <th width="60%">Student</th>
                  <th width="20%" class="text-center">Raw Grade</th>
                  <th width="20%" class="text-center">Trasmuted Grade</th>
            </tr>
      </thead>
      <tbody>
            @php
                  $trasmutation = DB::table('college_gradetransmutation')->get();
            @endphp
            @foreach ($studentstermgrades as $item)
                 

                  @php
                        $trangrade = null;

                        $trangrade = collect($trasmutation)
                                          ->where('gfrom','<=',$item->termgrade)
                                          ->where('gto','>=',$item->termgrade)
                                          ->first();

                        try{
                              $trangrade =  collect($trangrade)['transmutation'];
                        } catch (\Exception $e){ }

                  @endphp

                  <tr>
                        <td>{{$item->lastname.', '.$item->firstname}}</td>
                        <td class="text-center">{{$item->termgrade}}</td>
                        <td class="text-center">{{$trangrade}}</td>
                  </tr>
            @endforeach
           
      </tbody>
   

</table>