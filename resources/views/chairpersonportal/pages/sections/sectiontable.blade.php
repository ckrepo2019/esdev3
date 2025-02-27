@if(count($sections) > 0)

<table class="table table-striped table-hover">
      <thead>
            <tr>
                  <th width="5%"></th>
                  <th width="50%">SECTIONS  ({{collect($sections)->count()}})</th>
                  <th width="25%" class="text-center">ENROLLED ({{collect($sections)->sum('count')}})</td>
                  <th width="20%"></>Course</th>
            </tr>
      </thead>
      <tbody>
            @foreach ($sections as $item)
                  <tr>
                        <td>
                              <div class="dropdown">
                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                          <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                          <a class="dropdown-item udpateSection" href="#" data-id="{{$item->id}}"><i class="fas fa-edit"></i> Edit Section</a>
                                          <a class="dropdown-item removeSection" href="#" data-id="{{$item->id}}"><i class="fas fa-trash-alt"></i> Remove Section</a>
                                    </div>
                              </div>
                        </td>
                        <td><a href="/chairperson/sections/show/{{Str::slug($item->sectionDesc)}}">{{$item->sectionDesc}}<a></td>
                        <td class="text-center">{{$item->count}}</td>
                        <td>{{$item->courseabrv}}</td>
                  </tr>
            @endforeach
      </tbody>
</table>

@else
      <table class="table table-striped">
            <thead>
                  <tr>
                        <td width="5%"></td>
                        <td width="65%"></>SECTIONS</>
                        <td width="30%"></>Course</td>
                  </tr>
            </thead>
            <tbody>
            <tr>
                  <td colspan="3" class="text-center">NO SECTION AVAILABLE</td>
            </tr>
            </tbody>
      </table>

@endif