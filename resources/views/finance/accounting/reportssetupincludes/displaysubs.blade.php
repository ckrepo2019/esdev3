
<div id="subcontainer{{$headerid}}">
    @if(count($subs) == 0)
     @else
         @foreach($subs as $sub)
             <div class="row mt-2">
                <div class="col-md-2">&nbsp;</div>
                 <div class="col-md-8">
                     <div class="input-group  input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text form-control form-control-sm">Subheader</span>
                            </div>
                         <input type="text" value="{{$sub->group}}" class="form-control form-control-sm" style="background-color: #f1f5bf" subid="{{$sub->id}}" disabled/>
                         <div class="input-group-append">
                             <button type="button" class="btn btn-sm btn-default m-0 viewsub"><i class="fa fa-eye text-success"></i></button>
                             <button type="button" class="btn btn-sm btn-default m-0 deletesub"><i class="fa fa-times text-danger"></i></button>
                         </div>
                     </div>
                 </div>
             </div>
             
            <div id="detailcontainerheader{{$sub->id}}">
                
            </div>
         @endforeach
     @endif
 </div>
<div class="row mb-2">
    <div class="col-md-2">&nbsp;</div>
    <div class="col-md-2">
        <button type="button" class="btn btn-default btn-block btn-sm addsub mt-2" headerid="{{$headerid}}"><i class="fa fa-plus"></i> Add Sub</button>
    </div>
</div>