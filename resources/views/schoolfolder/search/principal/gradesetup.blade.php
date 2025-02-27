
<input type="hidden" value="{{$data[0]->count}}" id="searchCount" >

    @if($data[0]->count > 0)
        <table class="table table-sm" style="min-width:800px; table-layout:fixed;">
            <thead class="text-center">
                <tr>
                    <th width="12%" class="appadd text-left">Grade Level</th>
                    <th width="36%" class="text-left">Subject</th>
                    <th width="12%" class="text-left">Code</th>
                    <th width="7%">WW</th>
                    <th width="7%">PT</th>
                    <th width="5%">QA</th>
                    <th width="5%">Q1</th>
                    <th width="5%">Q2</th>
                    <th width="5%">Q3</th>
                    <th width="6%">Q4</th>
                </tr>
            </thead>
            <tbody class="border-bottom text-center" id="gradesetupholder">
                @foreach ($data[0]->data as $key=>$item)
                    <tr id="{{$key}}">
                        <td id="{{$item->levelid}}" class="text-left">{{$item->levelname}}</td>
                       
                        @if($item->acadprogid==5)
                            <td id="{{$item->subjid}}" class="appadd text-left">{{$item->subjtitle}}</td>
                        @else
                            <td id="{{$item->subjid}}" class="appadd text-left" >{{$item->subjdesc}}</td>
                        @endif
                        <td class="text-left">{{$item->subjcode}}</td>
                        <td>{{$item->writtenworks}}</td>
                        <td>{{$item->performancetask}}</td>
                        <td>{{$item->qassesment}}</td>
                        @if($item->first==1)
                            <td id="1"><i class="fas fa-check-square text-success"></i></td>
                        @else
                            <td id="0"><i class="fas fa-times-circle text-danger"></i></td>
                        @endif

                        @if($item->second==1)
                            <td id="1"><i class="fas fa-check-square text-success"></i></td>
                        @else
                            <td id="0"><i class="fas fa-times-circle text-danger"></i></td>
                        @endif

                        @if($item->third==1)
                            <td id="1"><i class="fas fa-check-square text-success"></i></td>
                        @else
                            <td id="0"><i class="fas fa-times-circle text-danger"></i></td>
                        @endif

                        @if($item->fourth==1)
                            <td id="1"><i class="fas fa-check-square text-success"></i></td>
                        @else
                            <td id="0"><i class="fas fa-times-circle text-danger"></i></td>
                        @endif
                    
                    </tr>  
                @endforeach
            </tbody>
        </table>
    @else
        <p class="w-100 text-center">No Results Found</p>
    @endif
