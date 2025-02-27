<div class="row form-group">
    <div class="col-md-4 form-group">
        <select id="fees_level" class="select2" style="width: 100%;">
            @foreach($levels as $l)
                @if($l->id == $levelid)
                    <option value="{{$l->id}}" selected>{{$l->levelname}}</option>
                @else
                    <option value="{{$l->id}}">{{$l->levelname}}</option>
                @endif
            @endforeach
        </select>
    </div>
    <div class="col-md-6 form-group">
        <select id="fees_header" class="select2" style="width: 100%;">
        </select>
    </div>
</div>
<div class="row form-group">
    <div class="col-md-12 table-responsive">
        <table class="table table-striped table-sm text-sm">
            <thead>
                <tr>
                    <th>Particulars</th>
                    <th class="text-center">Amount</th>
                </tr>
            </thead>
            <tbody id="tuition_list">
                <tr>
                    <td>
                        
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    $('.select2').select2({
        theme: 'bootstrap4'
    });

    $(document).ready(function(){
        $('#fees_level').trigger('change');
    })

    $(document).on('change', '#fees_level', function(){
        feesheader($(this).val(), 3, 1);
    });

    // loadlevel(13);



    function loadlevel(levelid)
    {
        $.ajax({
            url: '{{route('u_loadlevel')}}',
            type: 'GET',
            data: {
                levelid:levelid
            },
            success:function(data)
            {
                alert(data);
                // $('#fees_level').html(data);
                // $('#viewtui').append(data);
            }
        });
        
    }

    function feesheader(levelid, syid, semid)
    {
        console.log(syid);
        
        $.ajax({
            url: '{{route('u_loadtuitionheader')}}',
            type: 'GET',
            data: {
                levelid:levelid,
                syid:syid,
                semid:semid
            },
            success:function(data)
            {
                $('#fees_header').html(data).change();
            }
        }); 
    }

    function feesdetail(feesid)
    {
        $.ajax({
            url: '{{route('u_viewtuitiondetails')}}',
            type: 'GET',
            data: {
                feesid:feesid
            },
            success:function(data)
            {
                $('#tuition_list').html(data);
            }
        });
        
    }

    $(document).on('change', '#fees_header', function(){
        feesdetail($(this).val());
    })

</script>
    