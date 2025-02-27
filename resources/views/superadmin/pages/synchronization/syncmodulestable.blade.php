
@if(!$enabled)
      <input type="hidden" value="{{$data[0]->count}}" id="searchCount">
@else
      <input type="hidden" value="{{$data[0]->count}}" id="searchCountEnabled">
@endif

@if(!$enabled)
      <table class="table table-head-fixed table-hover"  >
            <thead>
                  <tr>  
                        <th width="50%">Table Name</th>
                        <th width="10%" class="text-center">ALL</th>
                        <th width="10%" class="text-center">CREATE</th>
                        <th width="10%" class="text-center">UPDATE</th>
                        <th width="10%" class="text-center">DELETE</th>
                        <th width="10%" class="text-center">DELETED</th>
                  </tr>
            </thead>
            <tbody>
                  @foreach ($data[0]->data as $item)
                        <tr>
                              <td>{{$item->tablename}}</td>
                              @if($item->delete == 1 && $item->create == 1 && $item->update == 1)
                                    <td class="text-center">
                                          <div class="form-group mb-0">
                                                <div class="icheck-success d-inline">
                                                <input type="checkbox" id="{{$item->tablename}}all" name="{{$item->tablename}}all" value="true" checked data-value="all" data-table="{{$item->tablename}}">
                                                <label for="{{$item->tablename}}all"></label>
                                                </div>
                                          </div>
                                    </td>
                              @else
                                    <td class="text-center">
                                          <div class="form-group mb-0">
                                                <div class="icheck-success d-inline">
                                                <input type="checkbox" id="{{$item->tablename}}all" name="{{$item->tablename}}all" value="true" data-value="all" data-table="{{$item->tablename}}">
                                                <label for="{{$item->tablename}}all"></label>
                                                </div>
                                          </div>
                                    </td>
                              @endif
                              @if($item->create == 0)
                                    <td class="text-center">
                                          <div class="form-group  mb-0">
                                                <div class="icheck-success d-inline">
                                                <input type="checkbox" id="{{$item->tablename}}create" name="{{$item->tablename}}create" value="true" data-value="create" data-table="{{$item->tablename}}">
                                                <label for="{{$item->tablename}}create"></label>
                                                </div>
                                          </div>
                                    </td>
                              @else
                                    <td class="text-center">
                                          <div class="form-group mb-0">
                                                <div class="icheck-success d-inline">
                                                <input type="checkbox" id="{{$item->tablename}}create" name="{{$item->tablename}}create" value="true" checked data-value="create" data-table="{{$item->tablename}}">
                                                <label for="{{$item->tablename}}create"></label>
                                                
                                                </div>
                                          </div>
                                    </td>
                              @endif
                              @if($item->update == 0)
                                    <td class="text-center">
                                          <div class="form-group mb-0">
                                                <div class="icheck-success d-inline">
                                                <input type="checkbox" id="{{$item->tablename}}update" name="{{$item->tablename}}update" value="true" data-value="update" data-table="{{$item->tablename}}">
                                                <label for="{{$item->tablename}}update"></label>
                                                </div>
                                          </div>
                                    </td>
                              @else
                                    <td class="text-center">
                                          <div class="form-group mb-0">
                                                <div class="icheck-success d-inline">
                                                <input type="checkbox" id="{{$item->tablename}}update" name="{{$item->tablename}}update" value="true" checked data-value="update" data-table="{{$item->tablename}}">
                                                <label for="{{$item->tablename}}update"></label>
                                                </div>
                                          </div>
                                    </td>
                              @endif
                              @if($item->delete == 0)
                                    <td class="text-center">
                                          <div class="form-group mb-0">
                                                <div class="icheck-success d-inline">
                                                <input type="checkbox" id="{{$item->tablename}}delete" name="{{$item->tablename}}delete" value="true" data-value="delete" data-table="{{$item->tablename}}">
                                                <label for="{{$item->tablename}}delete"></label>
                                                </div>
                                          </div>
                                    </td>
                              @else
                                    <td class="text-center">
                                          <div class="form-group mb-0">
                                                <div class="icheck-success d-inline">
                                                <input type="checkbox" id="{{$item->tablename}}delete" name="{{$item->tablename}}delete" value="true" checked data-value="delete" data-table="{{$item->tablename}}">
                                                <label for="{{$item->tablename}}delete"></label>
                                                </div>
                                          </div>
                                    </td>
                              @endif
                              @if($item->deleted == 0)
                                    <td class="text-center">
                                          <div class="form-group mb-0">
                                                <div class="icheck-success d-inline">
                                                <input type="checkbox" id="{{$item->tablename}}deleted" name="{{$item->tablename}}deleted" value="true" data-value="deleted" data-table="{{$item->tablename}}">
                                                <label for="{{$item->tablename}}deleted"></label>
                                                </div>
                                          </div>
                                    </td>
                              @else
                                    <td class="text-center">
                                          <div class="form-group mb-0">
                                                <div class="icheck-success d-inline">
                                                <input type="checkbox" id="{{$item->tablename}}deleted" name="{{$item->tablename}}deleted" value="true" checked data-value="deleted" data-table="{{$item->tablename}}">
                                                <label for="{{$item->tablename}}deleted"></label>
                                                </div>
                                          </div>
                                    </td>
                              @endif
                        </tr>
                  @endforeach
            </tbody>
      </table>
@else

      <table class="table table-head-fixed table-hover"  >
            <thead>
                  <tr>  
                        <th width="50%">Table Name</th>
                        <th width="10%" class="text-center">ALL</th>
                        <th width="10%" class="text-center">CREATE</th>
                        <th width="10%" class="text-center">UPDATE</th>
                        <th width="10%" class="text-center">DELETE</th>
                        <th width="10%" class="text-center">DELETED</th>
                  </tr>
            </thead>
            <tbody>
                  @foreach ($data[0]->data as $item)
                        <tr>
                              <td>{{$item->tablename}}</td>
                              @if($item->delete == 1 && $item->create == 1 && $item->update == 1)
                                    <td class="text-center">
                                          <div class="form-group mb-0">
                                                <div class="icheck-success d-inline">
                                                <input type="checkbox" checked >
                                                <label for="{{$item->tablename}}all"></label>
                                                </div>
                                          </div>
                                    </td>
                              @else
                                    <td class="text-center">
                                          <div class="form-group mb-0">
                                                <div class="icheck-success d-inline">
                                                <input type="checkbox" >
                                                <label for="{{$item->tablename}}all"></label>
                                                </div>
                                          </div>
                                    </td>
                              @endif
                              @if($item->create == 0)
                                    <td class="text-center">
                                          <div class="form-group  mb-0">
                                                <div class="icheck-success d-inline">
                                                <input type="checkbox">
                                                <label for="{{$item->tablename}}create"></label>
                                                </div>
                                          </div>
                                    </td>
                              @else
                                    <td class="text-center">
                                          <div class="form-group mb-0">
                                                <div class="icheck-success d-inline">
                                                <input type="checkbox" checked>
                                                <label for="{{$item->tablename}}create"></label>
                                                
                                                </div>
                                          </div>
                                    </td>
                              @endif
                              @if($item->update == 0)
                                    <td class="text-center">
                                          <div class="form-group mb-0">
                                                <div class="icheck-success d-inline">
                                                <input type="checkbox">
                                                <label for="{{$item->tablename}}update"></label>
                                                </div>
                                          </div>
                                    </td>
                              @else
                                    <td class="text-center">
                                          <div class="form-group mb-0">
                                                <div class="icheck-success d-inline">
                                                <input type="checkbox" checked>
                                                <label for="{{$item->tablename}}update"></label>
                                                </div>
                                          </div>
                                    </td>
                              @endif
                              @if($item->delete == 0)
                                    <td class="text-center">
                                          <div class="form-group mb-0">
                                                <div class="icheck-success d-inline">
                                                <input type="checkbox">
                                                <label for="{{$item->tablename}}delete"></label>
                                                </div>
                                          </div>
                                    </td>
                              @else
                                    <td class="text-center">
                                          <div class="form-group mb-0">
                                                <div class="icheck-success d-inline">
                                                <input type="checkbox" checked>
                                                <label for="{{$item->tablename}}delete"></label>
                                                </div>
                                          </div>
                                    </td>
                              @endif
                              @if($item->deleted == 0)
                                    <td class="text-center">
                                          <div class="form-group mb-0">
                                                <div class="icheck-success d-inline">
                                                <input type="checkbox" >
                                                <label for="{{$item->tablename}}deleted"></label>
                                                </div>
                                          </div>
                                    </td>
                              @else
                                    <td class="text-center">
                                          <div class="form-group mb-0">
                                                <div class="icheck-success d-inline">
                                                <input type="checkbox" checked>
                                                <label for="{{$item->tablename}}deleted"></label>
                                                </div>
                                          </div>
                                    </td>
                              @endif
                        </tr>
                  @endforeach
            </tbody>
      </table>

@endif