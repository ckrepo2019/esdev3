<table class="table table-bordered" style="min-width:1400px;">
      <thead>
            <tr>  
                  <th rowspan="2" class="align-middle" width="31%">Description</th>
                  <th colspan="4" class="text-center p-1" >TERM</th>
                  <th colspan="2" class="text-center p-1" style="font-size: 10px">PRELIM PERCENTAGE</th>
                  <th colspan="3" class="text-center p-1" style="font-size: 10px" >MIDTERM <br>PERCENTAGE</th>
                  <th colspan="4" class="text-center p-1" style="font-size: 10px" >SEMI FINAL<br>PERCENTAGE</th>
                  <th colspan="5" class="text-center p-1" style="font-size: 10px"">FINAL TERM<br>PERCENTAGE</th>
                  <th colspan="5" class="text-center p-1" style="font-size: 10px"">FINAL GRADE<br>PERCENTAGE</th>
            </tr>
            <tr style="font-size: 11px">
                  <th class="p-1 text-center" width="3%">Prelim</th>
                  <th class="p-1 text-center" width="3%">Mid</th>
                  <th class="p-1 text-center" width="3%">Semi</th>
                  <th class="p-1 text-center" width="3%">Final</th>

                  <th class="p-1 text-center" width="3%">Type</th>
                  <th class="p-1 text-center" width="3%">Prelim</th>

                  <th class="p-1 text-center" width="3%">Type</th>
                  <th class="p-1 text-center" width="3%">Prelim</th>
                  <th class="p-1 text-center" width="3%">Mid</th>
                 

                  <th class="p-1 text-center" width="3%">Type</th>
                  <th class="p-1 text-center" width="3%">Prelim</th>
                  <th class="p-1 text-center" width="3%">Mid</th>
                  <th class="p-1 text-center" width="3%">Semi</th>

                  <th class="p-1 text-center" width="3%">Type</th>
                  <th class="p-1 text-center" width="3%">Prelim</th>
                  <th class="p-1 text-center" width="3%">Mid</th>
                  <th class="p-1 text-center" width="3%">Semi</th>
                  <th class="p-1 text-center" width="3%">Final</th>

                  <th class="p-1 text-center" width="3%">Type</th>
                  <th class="p-1 text-center" width="3%">Prelim</th>
                  <th class="p-1 text-center" width="3%">Mid</th>
                  <th class="p-1 text-center" width="3%">Semi</th>
                  <th class="p-1 text-center" width="3%">Final</th>
            </tr>
      </thead>
      <tbody style="font-size: 11px">
            @foreach ($gradetermsetup as $item)
                  <tr>
                        <td class="align-middle">  
                              @if($item->isactive == 1)
                                    <span class="badge badge-pill badge-success">&nbsp;</span> {{$item->description}}</td>
                              @else
                                    <span class="badge badge-pill badge-danger">&nbsp;</span> {{$item->description}}</td>
                              @endif
                             
                        <td class="text-center align-middle">
                              @if($item->withpre == 1)
                                    <i class="fas fa-check-circle text-success"></i>
                              @else
                                    <i class="fas fa-times-circle text-danger"></i>
                              @endif
                        </td>
                        <td class="text-center align-middle">
                              @if($item->withmid == 1)
                                    <i class="fas fa-check-circle text-success"></i>
                              @else
                                    <i class="fas fa-times-circle text-danger"></i>
                              @endif
                        </td>
                        <td class="text-center align-middle">
                              @if($item->withsemi == 1)
                                    <i class="fas fa-check-circle text-success"></i>
                              @else
                                    <i class="fas fa-times-circle text-danger"></i>
                              @endif
                        </td>
                        <td class="text-center align-middle">
                              @if($item->withfinal == 1)
                                    <i class="fas fa-check-circle text-success"></i>
                              @else
                                    <i class="fas fa-times-circle text-danger"></i>
                              @endif
                        </td>
                        <td class="text-center align-middle">
                              @if($item->pttype == 2)
                                    Per
                              @else
                                    Ave
                              @endif
                        </td>
                        <td class="text-center align-middle">{{$item->ptptper}}</td>

                        <td class="text-center align-middle">
                              @if($item->mttype == 2)
                                    Per
                              @else
                                    Ave
                              @endif
                        </td>
                        <td class="text-center align-middle">{{$item->mtptper}}</td>
                        <td class="text-center align-middle">{{$item->mtmtper}}</td>

                        <td class="text-center align-middle">
                              @if($item->sttype == 2)
                                    Per
                              @else
                                    Ave
                              @endif
                        </td>
                        <td class="text-center align-middle">{{$item->stptper}}</td>
                        <td class="text-center align-middle">{{$item->stmtper}}</td>
                        <td class="text-center align-middle">{{$item->ststper}}</td>

                        <td class="text-center align-middle">
                              @if($item->fttype == 2)
                                    Per
                              @else
                                    Ave
                              @endif
                        </td>
                        <td class="text-center align-middle">{{$item->ftptper}}</td>
                        <td class="text-center align-middle">{{$item->ftmtper}}</td>
                        <td class="text-center align-middle">{{$item->ftstper}}</td>
                        <td class="text-center align-middle">{{$item->ftftper}}</td>

                        <td class="text-center align-middle">
                              @if($item->fgtype == 2)
                                    Per
                              @else
                                    Ave
                              @endif
                        </td>
                        <td class="text-center align-middle">{{$item->fgptper}}</td>
                        <td class="text-center align-middle">{{$item->fgmtper}}</td>
                        <td class="text-center align-middle">{{$item->fgstper}}</td>
                        <td class="text-center align-middle">{{$item->fgftper}}</td>
                        
                  </tr>

            @endforeach
           
      </tbody>
</table>