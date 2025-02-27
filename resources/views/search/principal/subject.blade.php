<input type="hidden" value="{{$data[0]->count}}" id="searchCount">
@if(Crypt::decrypt($acadid)==5)
    
    <table class="table  table-sm" style="min-width:800px; table-layout:fixed;">
        <thead>
            <tr>
               
                <th width="5%" class="text-center">Type</th>
                <th width="10%"class="text-center">CODE</th>
                <th width="40%">SUBJECT NAME</th>
                <th width="10%"class="text-center">Semester</th>
              
                <th width="10%"class="text-center">TRACK</th>
           
                <th width="25%"width="5%"class="text-center">PRE-REQUISITE</th>
                {{-- <th width="10%"class="text-center">STRAND</th> --}}
            </tr>
        </thead>
        <tbody class="border-bottom" id="searchSHSubjects">
            @foreach ($data[0]->data as $item)
                    <tr>
                    @if($item->type==1)
                        <td class="p-0  text-center text-white align-middle bg-red-50"><b>C</b></td>
                    @elseif($item->type==2)
                        <td class="p-0  text-center text-white align-middle bg-blue-50"><b>SP</b></td>
                    @else
                        <td class="p-0  text-center text-white align-middle bg-green-50"><b>AS</b></td>
                    @endif
                    <td class="text-center align-middle">{{$item->subjcode}}</td>
                    <td class="align-middle appadd"><a href="/viewsubjectInfo/{{Crypt::encrypt($item->id)}}}/{{$acadid}}">{{$item->subjtitle}}</a></td>
                    
                    @if($item->semid == 1 )
                        <td class="text-center align-middle">1st</td>
                    @elseif($item->semid == 2 )
                        <td class="text-center align-middle">2nd</td>
                    @else
                        <td class="text-center align-middle"></td>
                    @endif
                    
                    
                    @if($item->subjtrackid == 1 )
                        <td class="text-center align-middle">Academic</td>
                    @elseif($item->subjtrackid == 2 )
                        <td class="text-center align-middle">TVL</td>
                    @else
                        <td class="text-center align-middle"></td>
                    @endif
                    
                   
                    <td class="text-center align-middle" style="font-size:9px">
                        @if($item->type==2 || $item->type==3)
                            @foreach (App\Models\Principal\SPP_Prerequisite::loadSHSubjectPrerequisiteBySubject($item->id) as $prereq)
                                {{$prereq->subjcode}} / 
                            @endforeach
                        @endif
                    </td>
                    {{-- <td class="text-center align-middle" style="font-size:9px">{{$item->strandcode}}</td> --}}
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <table class="table table-sm">
        <thead class="bg-warning">
            <tr>
                <th width="50%">Subject Name</th>
                <th width="50%" >Subject Code</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data[0]->data as $item)
                <tr>
                    <td class="appadd pr-4"><a href="/viewsubjectInfo/{{Crypt::encrypt($item->id)}}}/{{$acadid}}">{{$item->subjdesc}}</a></td>
                    <td>{{$item->subjcode}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
  
   
@endif