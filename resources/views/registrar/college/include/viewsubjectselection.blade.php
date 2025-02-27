
<script>
    var $ = jQuery;
    $(document).ready(function(){
        $(".filter").on("keyup", function() {
            var input = $(this).val().toUpperCase();
            var visibleCards = 0;
            var hiddenCards = 0;

            $(".container").append($("<div class='card-group card-group-filter'></div>"));


            $(".card").each(function() {
                if ($(this).data("string").toUpperCase().indexOf(input) < 0) {

                $(".card-group.card-group-filter:first-of-type").append($(this));
                $(this).hide();
                hiddenCards++;

                } else {

                $(".card-group.card-group-filter:last-of-type").prepend($(this));
                $(this).show();
                visibleCards++;

                if (((visibleCards % 4) == 0)) {
                    $(".container").append($("<div class='card-group card-group-filter'></div>"));
                }
                }
            });

        });
    })
</script>
<section class="content-header">
<div class="row mb-2">
    <div class="col-md-4">
        <input class="filter form-control" placeholder="Search subject" />
    </div>
</div>
<div class="row d-flex align-items-stretch text-uppercase">
    @foreach($subjects as $subject)
        <div class="card col-md-4" style="border: none !important;box-shadow: none !important;" data-string="{{$subject->subjectcode}} {{$subject->subjectname}}<">
            <a href="#" class="info-box selectsubject" style="color: inherit;" id="{{$subject->subjectid}}">
                <span class="info-box-icon bg-warning" style="">
                    <i class="fa fa-book"></i>
                </span>

                <div class="info-box-content" style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">
                <span class="info-box-text"><strong>{{$subject->subjectcode}}</strong></span>
                <span class="info-box-number">
                    <small>{{$subject->subjectname}}</small>
                    </span>
                </div>
            </a>
        </div>
    @endforeach
</div>