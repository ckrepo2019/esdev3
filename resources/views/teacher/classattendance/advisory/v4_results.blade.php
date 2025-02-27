<div class="card shadow" style="box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
    <div class="card-header">
        <div class="row">
            <div class="col-12 mb-2">
                {!! $calendar !!}
            </div>
            <div class="col-md-4">
                @if(count($setup) == 0)
                <button type="button" class="btn btn-sm btn-outline-secondary mt-2">No Setup found from SF2</button>
                @else
                
                @endif
            </div>
            <div class="col-md-8 p-2" style="border: 1px solid #ddd; border-radius: 10px;">
                <div class="row">
                    <div class="col-9">
                        <small>Note: <span class="bg-green">&nbsp;&nbsp;Green means it is <strong>selected</strong>&nbsp;&nbsp;</span>. Please don't forget to click this button when you are done selecting the dates.</small>
                    </div>
                    <div class="col-3 text-right">
					{{-- <button type="button" class="btn btn-sm btn-outline-success" id="btn-getattendance">Get Attendance</button> --}}
                    </div>
                </div>
            </div>
            <div class="col-9"></div>
        </div>
        <div class="row">
            <div class="col-12 text-right">
                {{-- <button type="button" class="btn btn-info" id="btn-reload"><i class="fa fa-sync"></i> Reload</button> --}}
            </div>
        </div>
    </div>
    <div id="container-attendance">
        
    </div>
</div>
<script>
    var selecteddates = <?php echo json_encode($selecteddates); ?>;
    $('.calendar-day').on('click', function(){
		return false
        $('#selected-dates-container').empty()
        var idx = $.inArray($(this).attr('data-id'), selecteddates);
        if (idx == -1) {
            $(this).addClass('selected-date');
            selecteddates.push($(this).attr('data-id'));
        } else {
            selecteddates.splice(idx, 1);
            $(this).removeClass('selected-date');
        }
        selecteddates.sort(function(a, b) {
            return a - b;
        });
        if(selecteddates.length == 0)
        {
            $('#btn-getattendance').hide();
        }else{
            $('#btn-getattendance').show();
        }
    })
   
	
	function get_attendance(){
		selecteddates = []
        var selectedyear = $('#selectedyear').val();
        var selectedmonth = $('#selectedmonth').val();
        var selectedschoolyear = $('#selectedschoolyear').val();
        var selectedsemester = $('#selectedsemester').val();
        var selectedstrand = $('#selectedstrand').val();
        $('.calendar-row').find('.selected-date').each(function(){
            selecteddates.push($(this).text())
        })
        console.log(selecteddates)
        $.ajax({
            url: '/classattendance/viewsection_v4',
            type: 'GET',
            data: {
                action          : 'getattendance',
                levelid  : '{{$gradelevelinfo->id}}',
                sectionid: '{{$sectioninfo->id}}',
                selectedschoolyear      : '{{$syid}}',
                selectedsemester      : '{{$semid}}',
                dates           : selecteddates,
                selectedstrand           : selectedstrand,
                selectedyear    : '{{$year}}',
                selectedmonth   : '{{$month}}'
            }, success:function(data){
                $('#container-attendance').empty()
                $('#container-attendance').append(data)
                $('#container-attendance').show()
            }
        })
	}
	get_attendance()
   
</script>