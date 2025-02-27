
        @if(count($sections) == 0)
        <div class="col-md-12">
            <div class="alert alert-danger" role="alert">
                No sections assigned!
            </div>
        </div>
    @else
    @foreach($sections as $section)
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <span style="font-size: 15px"><strong>{{$section->numberofstudents}}</strong></span>  Students

                    <p>{{$section->levelname}} - {{$section->sectionname}}</p>
                    <div class="row">
                        <div class="col-6">
                            <small>Enrolled: {{$section->numberofenrolled}}</small><br/>
                            <small>Late Enrolled: {{$section->numberoflateenrolled}}</small><br/>
                            <small>Transferred In: {{$section->numberoftransferredin}}</small><br/>
                        </div>
                        <div class="col-6">
                            <small>Transferred Out: {{$section->numberoftransferredout}}</small><br/>
                            <small>Dropped Out: {{$section->numberofdroppedout}}</small><br/>
                            <small>Withdrawn: {{$section->numberofwithdraw}}</small>
                        </div>
                    </div>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                
                <form action="/forms/form2shsindex" method="get" class="small-box-footer">
                    <input type="hidden" name="action" value="index"/>
                    <input type="hidden" name="sectionid" value="{{$section->sectionid}}"/>
                    <input type="hidden" name="levelid" value="{{$section->levelid}}"/>
                    <input type="hidden" name="formtype" value="{{$formtype}}"/>
                    <input type="hidden" name="syid" value="{{$syid}}"/>
                    <input type="hidden" name="acadprogid" value="{{$acadprogid}}"/>
                    <button type="submit" class=" btn btn-sm btn-block">More info <i class="fas fa-arrow-circle-right"></i></button>
                </form>
                {{-- <form action="/forms/{{$formtype}}index" method="get" class="small-box-footer">
                    <input type="hidden" name="action" value="show"/>
                    @csrf
                    <input type="hidden" name="sectionid" value="{{$section->sectionid}}"/>
                    <input type="hidden" name="syid" value="{{$syid}}"/>
                    <input type="hidden" name="levelid" value="{{$section->levelid}}"/>
                    <input type="hidden" name="currentmonth" value="{{\Carbon\Carbon::now()->month}}"/>
                    <button type="submit" class=" btn btn-sm btn-block">More info <i class="fas fa-arrow-circle-right"></i></button>
                </form> --}}
                    {{-- <form action="/forms/{{$formtype}}" method="get" class="small-box-footer" target="_blank">
                        <input type="hidden" name="action" value="export"/>
                        @csrf
                        <input type="hidden" name="sectionid" value="{{$section->sectionid}}"/>
                        <input type="hidden" name="levelid" value="{{$section->levelid}}"/>
                        <input type="hidden" name="syid" value="{{$syid}}"/>
                        <input type="hidden" name="exporttype"/>
                        <input type="hidden" name="currentmonth" value="{{\Carbon\Carbon::now()->month}}"/>
                        <button type="button" class=" btn btn-sm btn-block dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">Export As <span class="sr-only">Toggle Dropdown</span>
                            <div class="dropdown-menu" role="menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(-1px, 37px, 0px); top: 0px; left: 0px; will-change: transform;">
                            <a class="dropdown-item" href="#" data-id="exportpdf">PDF</a>
                            <a class="dropdown-item" href="#" data-id="exportexcel">EXCEL</a>
                            </div>
                        </button>
                    </form> --}}
            </div>
            
        </div>
    @endforeach
    <script>
        $('[data-id="exportpdf"]').on('click', function(){
            $(this).closest('form').find('input[name="exporttype"]').val('pdf')
            $(this).closest('form').submit();
        })
        $('[data-id="exportexcel"]').on('click', function(){
            $(this).closest('form').find('input[name="exporttype"]').val('excel')
            $(this).closest('form').submit();
        })
    </script>
@endif