
<div class="card shadow" style="border: none !important; box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
    {{-- <div class="card-header">    
        <div class="row">
            <div class="col-md-4">
                <div class="icheck-primary d-inline">
                    <input type="checkbox" id="checkboxMapeh" value="mapeh" checked>
                    <label for="checkboxMapeh">
                    MAPEH
                    </label>
                </div>
            </div>
            <div class="col-md-8 text-right">
                @foreach($components as $component)
                    <div class="icheck-primary d-inline mr-2">
                        <input type="checkbox" class="components" id="checkboxMapeh{{$component->subjid}}" value="{{$component->subjid}}" checked disabled>
                        <label for="checkboxMapeh{{$component->subjid}}">
                            {{$component->subjdesc}}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>    
    </div> --}}
    
<div class="card-header d-flex p-0">
    {{-- <h3 class="card-title p-3">Tabs</h3> --}}
    <ul class="nav nav-pills ml-auto p-2">
        <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">MAPEH</a></li>
        @foreach($components as $key=>$subject)
        <li class="nav-item"><a class="nav-link" href="#tab_{{$subject->subjid}}" data-toggle="tab" data-subjid="{{$subject->subjid}}">{{$subject->subjdesc}}</a></li>
        @endforeach
    </ul>
</div>
    <div class="card-body  table-responsive p-0" id="results-students-container" >
    </div>
    
</div>

<script>
    function getstudents(subjectid)
    {
        var selectedyear = $('#selectedyear').val();
        var selectedmonth = $('#selectedmonth').val();
        var selectedschoolyear = $('#selectedschoolyear').val();
        var selectedsemester = $('#selectedsemester').val();
        var selectedstrand = $('#selectedstrand').val();
        var selectedgradelevel = $('#selectedgradelevel').val();
        var selectedsection = $('#selectedsection').val();
        var subjects = [];
        $('.components:checked').each(function(){
            subjects.push($(this).val())
        })
        $.ajax({
            url: '/beadleAttendance/getstudents',
            type: 'GET',
            data: {
                mapehattendance: '1',
                version: '3',
                selectedschoolyear      : selectedschoolyear,
                selectedsemester      : selectedsemester,
                dates           : '{{collect($dates)}}',
                selectedyear    : '{{$year}}',
                selectedstrand    : selectedstrand,
                selectedmonth   : '{{$month}}',
                selectedgradelevel   : selectedgradelevel,
                selectedsection   : selectedsection,
                subjects   : JSON.stringify(subjects),
                subjectid   : subjectid
            }, success:function(data){
                $('#results-students-container').empty()
                $('#results-students-container').append(data)
            }
        })
    }
    getstudents('mapeh')
    // $('#checkboxMapeh').on('click', function(){
    //     if($(this).is(":checked"))
    //     {
    //         $('.components').removeAttr('checked')
    //         $('.components').removeAttr('disabled')
    //         $('.components').prop('checked', true)
    //         $('.components').prop('disabled', true)
    //         getstudents()
    //     }else{
    //         $('.components').prop('disabled',false)
    //         $('.components').prop('checked',false)
    //         $('#results-students-container').empty()
    //     }
    // })
    // $('.components').on('click', function(){
    //     if($(this).is(":checked"))
    //     {
    //         if('{{count($components)}}' == $('.components:checked').length)
    //         {
    //             $('.components').removeAttr('checked')
    //             $('.components').removeAttr('disabled')
    //             $('.components').prop('checked', true)
    //             $('.components').prop('disabled', true)
    //             $('#checkboxMapeh').prop('checked', true)
    //         }
    //     }
    // getstudents()
    // })
    $("[data-toggle=tab]").on('click', function(){
        getstudents($(this).attr('data-subjid'))
    })
</script>