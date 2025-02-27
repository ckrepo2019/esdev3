<div class="modal fade" id="{{$modalInfo->modalName}}" data-backdrop="static" data-keyboard="false" style="display: none;" aria-hidden="true">
      <div class="modal-dialog" >
            <div class="modal-content">
                  @if($modalInfo->crud == 'CREATE')
                        <div class="modal-header bg-primary">
                  @else
                        <div class="modal-header bg-success">
                  @endif
                      
                              <h5 class="modal-title">{{$modalInfo->modalheader}} FORM</h5>
                       

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                        </button>
                  </div>
                  <form method="GET" action="{{$modalInfo->method}}" id="{{$modalInfo->modalName}}Form" enctype="multipart/form-data">
                      
                        <div class="modal-body pb-2">
                              @foreach($inputs as $input)
                                    
                                    @if($input->type == 'input')
                                         
                                          <div class="form-group ">
                                                <label>{{$input->label}}</label>
                                                @php
                                                      if($errors->any()){

                                                            $value = old($input->name);

                                                      }
                                                      else{

                                                            $value = $input->value;

                                                      }
                                                @endphp

                                                <input value="{{$value}}" 
                                                name="{{$input->name}}" 
                                                class="form-control @error($input->name) is-invalid @enderror @if(isset($input->class)) {{$input->class}} @endif col-md-12 {{$input->name}}" placeholder="{{$input->label}}" 
                                                style="text-transform:uppercase"
                                                id="{{$input->name}}"
                                                {{-- onkeypress="return ((event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || event.charCode == 8 || event.charCode == 32 || (event.charCode >= 48 && event.charCode <= 57) || (event.charCode > 185 && event.charCode < 189 )) ;  " --}}
                                                @if(isset($input->attr)) {{$input->attr}} @endif
                                                >

                                                <script>
                                                      $(document).ready(function(){
                                                            $(document).on('keypress','#'+'{{$input->name}}',function(e){
                                                                 
                                                                  if(e.charCode == 45){
                                                                        return false;
                                                                  }
                                                            })
                                                      })
                                                    
                                                </script>

                                                @if($errors->has($input->name))
                                                      <span class="invalid-feedback" role="alert" id="{{$input->name}}Error">
                                                            <strong>{{ $errors->first($input->name) }}</strong>
                                                      </span>
                                                @else
                                                      <span class="invalid-feedback" role="alert" id="{{$input->name}}Error">
                                                            <strong>{{ $errors->first($input->name) }}</strong>
                                                      </span>
                                                @endif
                                          </div>

                                    @elseif($input->type == 'checkbox')
                                    
                                          <div class="icheck-success d-inline col-md-2 @if(isset($input->class)) {{$input->class}} @endif">
                                                <input 
                                                      type="checkbox" 
                                                      id="{{$input->label}}" 
                                                      value="{{$input->value}}"
                                                      name="{{$input->name}}"
                                                      class="{{$input->name}}"
                                                     
                                                >
                                                <label for="{{$input->label}}">{{$input->label}}</i>
                                                </label>
                                          </div>

                                    @elseif($input->type == 'timepicker')
                                    
                                         
                                                <label>{{$input->label}}</label>
                                                <input 
                                                      class="@if(isset($input->class)){{$input->class}}@endif {{$input->name}}"
                                                      width="50%"
                                                      name="{{$input->name}}" 
                                                      id="{{$input->name}}"
                                                      data-id="@if(isset($input->id)){{$input->id}}@endif"
                                                      value="00:00" 
                                                />
                                       
                                    @else
                                    
                                          <div class="form-group">
                                                <label>{{$input->label}}</label>
                                                <select class="form-control select2 @error($input->name) is-invalid @enderror {{$input->name}}" 
                                                      @if(isset($input->attr)) 
                                                            {{$input->attr}} 
                                                      @endif 
                                                      name="{{$input->name}}" 
                                                      data-placeholder="SELECT {{$input->label}}" 
                                                      style="width: 100%;"
                                                      id="{{$input->name}}"
                                                      >
                                                      <option value="">SELECT {{$input->label}}</option>

                                                      @if($input->table != null)
                                                     
                                                            @foreach (DB::table($input->table)->where('deleted','0')->get() as $item)
                                                                  
                                                                  @php
                                                                        $value = $input->selectValue;

                                                                        if(isset($input->selectOption)){
                                                                              $option = $input->selectOption;
                                                                        }
                                                                        else{
                                                                              $option = $input->selectValue;
                                                                        }
                                                                       
                                                                  @endphp

                                                                        @if(old($input->name) == null && Str::slug($item->$value,'-') == $input->value)
                                                                              <option selected  value="{{Str::slug($item->$value,'-')}}">{{$item->$option}}</option>
                                                                        @elseif(old($input->name) != null && old($input->name) == Str::slug($item->$value,'-'))
                                                                              <option selected  value="{{Str::slug($item->$value,'-')}}">{{$item->$option}}</option>
                                                                        @else
                                                                              <option  value="{{Str::slug($item->$value,'-')}}">{{$item->$option}}</option>
                                                                        @endif
                                                            @endforeach

                                                      @else
                                                     
                                                            @foreach ($input->data as $item)
                                                                  @php
                                                                        $value = $input->selectValue;

                                                                        if(isset($input->selectOption)){
                                                                              $option = $input->selectOption;
                                                                        }
                                                                        else{
                                                                              $option = $input->selectValue;
                                                                        }

                                                                  @endphp

                                                                        @if(old($input->name) == null && Str::slug($item->$value,'-') == $input->value)

                                                                              <option selected  value="{{Str::slug($item->$value,'-')}}">{{$item->$option}}</option>

                                                                        @elseif(old($input->name) != null && old($input->name) == Str::slug($item->$value,'-'))

                                                                              <option selected  value="{{Str::slug($item->$value,'-')}}">{{$item->$option}}</option>

                                                                        @else
                                                                              <option  value="{{Str::slug($item->$value,'-')}}">{{$item->$option}}</option>
                                                                        @endif

                                                            @endforeach

                                                      @endif
                                             
                                                      
                                                </select>
                                                <span class="invalid-feedback" role="alert" id="{{$input->name}}Error">
                                                      <strong></strong>
                                                </span>

                                                @if($errors->has($input->name))
                                                      <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first($input->name) }}</strong>
                                                      </span>
                                                @endif
                                          </div>
                                    @endif
                             
                              @endforeach
                              
                             
                        </div>

                        <div class="modal-footer justify-content-between">
                           @if($modalInfo->crud == 'CREATE')  
                              <button onClick="this.form.submit(); this.disabled=true; " type="submit" class="btn btn-primary savebutton" >{{$modalInfo->crud}}</button>
                           @elseif($modalInfo->crud == 'UPDATE')
                              <button onClick="this.form.submit(); this.disabled=true;" type="submit"  class="btn btn-success udpatebutton" >{{$modalInfo->crud}}</button>
                           @endif
                        </div>
                  </form>
            </div>
      </div>
</div>


<script>
      $(document).ready(function(){

           
            $('input').on('input',function(){

                  if($(this).attr('type') != 'checkbox'){

                        let p = this.selectionStart; 
                        this.value = this.value.toUpperCase();
                        this.setSelectionRange(p, p);

                  }
            })
      })
</script>