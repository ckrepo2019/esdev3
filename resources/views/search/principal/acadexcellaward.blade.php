<input type="hidden" value="{{$data[0]->count}}" id="searchCount">
<style>
      
      .acad2{
            animation:blinkingText 1.5s infinite;
            font-size: 20px;
      }
      @keyframes blinkingText{
            0%{		color: #ffc107;}
            49%{	color: #a67e04;}
            50%{	color: #ffc107;	}
            99%{	color:#a67e04;	}
            100%{	color: #ffc107;}
      }
      
</style>
@if($data[0]->count > 0)
      <table class="table">
            <tr>
                  <th>Student</th>
                  <th>Grade Level</th>
                  <th>Section</th>
                  <th>Gen. Ave</th>
                  {{-- <th>Award</th> --}}
            </tr>
            
            @foreach($data[0]->data as $item)
                  <tr>
                        <td>{{$item->student}}</td>
                        <td>{{$item->gradelevel}}</td>
                        <td>{{$item->section}}</td>
                        <td>{{$item->genAve}}</td>
                        {{-- <td>{{$item->award}}</td> --}}
                  </tr>
            @endforeach
     
      </table>
@elseif($data[0]->count == 0)
    <div class="container h-100" >
        <div class="row align-items-center h-100" style="min-height:430px; !important">
            <div class="mx-auto text-danger" style="font-size: 20px">
                  <b>NO AWARDIES FOR THIS QUARTER</b>
            </div>
        </div>
    </div>
@elseif($data[0]->count == -1)
      <div class="container h-100" >
            <div class="row align-items-center h-100" style="min-height:430px; !important">
            <div class="mx-auto acad2">
            <b>!!! PLEASE SELECT ACADEMIC PROGRAM</b>
            </div>
            </div>
      </div>
@else
      <div class="container h-100" >
            <div class="row align-items-center h-100" style="min-height:430px; !important">
            <div class="mx-auto acad2">
                  <b>!!! PLEASE SELECT QUARTER</b>
            </div>
            </div>
      </div>
@endif