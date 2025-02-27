<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2000,
    })

    $(document).ready(function() {
        console.log('hello');
        var filter_date = document.getElementById('filter_date');
        if (filter_date) {
            filter_date.valueAsDate = new Date();
        }

        $('.select2').select2()

        var strand = @json($subj_strand);
        var utype = usertype = @json(auth()->user()->type);
        var students = [];
        var sections = []

        if ($(window).width() < 500) {
            $('.search').addClass('w-100 mt-2')
            $('.acadid').addClass('w-100')
            $('.col-md-2, .col-md-8').addClass('mb-3')
            $('.search').removeClass('w-25')
        }

        $('#quarter').select2({
            data: [],
            placeholder: 'Quarter',
        })

        $("#section").select2({
            data: [],
            placeholder: "Section",
            allowClear: true
        })

        $('#gradelevel').select2({
            placeholder: 'Grade Level',
        })

        get_sections()


        $('#syid').on('change', function() {
            get_sections()
            $('#gradelevel').val(null).trigger('change');
            $('#section').val(null).trigger('change');
        })

        function get_sections() {
            $.ajax({
                type: 'GET',
                url: '/sections/info/list',
                data: {
                    syid: $('#syid').val()
                },
                success: data => sections = data.map(b => ({
                    ...b,
                    id: b.sectionid
                }))
            });
            // console.log('SYID', $('#syid').val());
            // console.log('SECTIONSLIST', sections);
        }

        $(document).on('change', '#gradelevel', function() {
            get_sections();
            var temp_sections = sections.filter(x => x.levelid == $(this).val())
            // console.log('LVLID', $(this).val());
            // console.log('TEMP_SECT',temp_sections);
            // console.log('ALL_SECT',sections);

            $("#section").empty()
                .append('<option value="">Section</option>')
                .select2({
                    data: temp_sections,
                    placeholder: "Section",
                    allowClear: true
                })
            students = [];
            // loaddatatable([])
        })

        $(document).on('change', '#quarter', function() {
            data = students
            loaddatatable(data)
        })


        function loaddatatable(data) {

            var new_data = data.filter(x => x.student !== 'SUBJECTS' && (!($('#gradelevel').val() == 14 || $(
                '#gradelevel').val() == 15) || x.semid == $('#semester').val()));

            if (new_data.some(x => x.quarter_award === undefined || x.quarter_award.genave === 0 || x
                    .quarter_award.genave === null || x.quarter_award.genave === '')) {
                Swal.fire({
                    type: 'info',
                    title: 'No general average available!',
                    text: 'Awards cannot be generated at this time. Please ensure all grades are finalized and try again.',
                    showCancelButton: false,
                    confirmButtonText: `OK`,
                }).then((result) => {
                    if (result.value) {
                        return false;
                    }
                });
            } else {
                $("#student_list").DataTable({
                    destroy: true,
                    data: new_data,
                    scrollX: true,
                    fixedColumns: {
                        leftColumns: 1,
                        rightColumns: 1
                    },
                    "order": [
                        [3, "desc"]
                    ],
                    "columns": [{
                            "data": "quarter_award.name"
                        },
                        {
                            "data": "sectionname"
                        },
                        {
                            "data": "quarter_award.genave"
                        },
                        {
                            "data": "quarter_award.temp_comp"
                        },
                        {
                            "data": "quarter_award.award"
                        },
                        {
                            "data": "quarter_award.lowest"
                        },
                        {
                            "data": "rank"
                        },
                        {
                            "data": null
                        },

                    ],
                    columnDefs: [

                        {
                            'targets': [2, 3, 4, 5, 1, 6],
                            'orderable': true,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).addClass('text-center')
                                $(td).addClass('align-middle')
                            }
                        },
                        {
                            'targets': 7,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                var buttons = '<a href="#" data-rank="' + rowData.rank +
                                    '" class="print_cert" data-studid="' + rowData.studid +
                                    '"><i class="fas fa-print"></i></a>';
                                $(td)[0].innerHTML = buttons
                                $(td).addClass('text-center')
                            }
                        },
                    ]

                });

                var label_text = $($('#student_list_wrapper')[0].children[0])[0].children[0]
                $(label_text)[0].innerHTML =
                    '<button class="btn btn-primary btn-sm" id="filter"> <i class="fas fa-filter"></i> Filter</button><button class="btn btn-default btn-sm ml-2" id="print_student_ranking"><i class="fas fa-print"></i> Student Ranking</button>'

            }
        }


        $(document).on('click', '.print_cert', function() {

            var gradelevel = $('#gradelevel').val();
            var sectionid = $('#section').val();
            var quarter = $('#quarter').val();
            var syid = $('#syid').val();
            var semid = $('#semester').val();
            var studid = $(this).attr('data-studid')
            var date = $('#filter_date').val();
            var rank = $(this).attr('data-rank')

            if (date == null || date == "") {
                Swal.fire({
                    type: 'warning',
                    text: 'No Date Selected!',
                    timer: 1500
                });
                return false;
            }

            window.open("/studentawards/printcert?gradelevel=" + gradelevel + "&sectionid=" +
                sectionid + "&quarter=" + quarter + "&syid=" + syid + "&strand=" + $("#strand")
                .val() + "&semid=" + semid + "&studid=" + studid + "&date=" + date + "&rank=" + rank
            );
        })

        $(document).on('change', '#gradelevel', function() {
            $('.strand_holder_header').text($(this).val() == 14 || $(this).val() == 15 ?
                'Section / Strand' : 'Section');
            $('.strand_holder').prop('hidden', !($(this).val() == 14 || $(this).val() == 15));
        })

        $(document).on('change', '#semester , #gradelevel', function() {
            $('#quarter').empty();
            $('#quarter').append('<option value="">Select Quarter</option>')
            if ($('#gradelevel').val() == 14 || $('#gradelevel').val() == 15) {
                if ($('#semester').val() == 1) {
                    $('#quarter').append('<option value="5">1st Sem Gen. Ave.</option>')
                    $('#quarter').append('<option value="1">1st Quarter</option>')
                    $('#quarter').append('<option value="2">2nd Quarter</option>')
                } else {
                    $('#quarter').append('<option value="6">1st & 2nd Sem Gen. Ave.</option>')
                    $('#quarter').append('<option value="5">2nd Sem Gen. Ave.</option>')
                    $('#quarter').append('<option value="3">3rd Quarter</option>')
                    $('#quarter').append('<option value="4">4th Quarter</option>')
                }
            } else {
                $('#quarter').append('<option value="1">1st Quarter</option>')
                $('#quarter').append('<option value="2">2nd Quarter</option>')
                $('#quarter').append('<option value="3">3rd Quarter</option>')
                $('#quarter').append('<option value="4">4th Quarter</option>')
                $('#quarter').append('<option value="5">Gen. Ave.</option>')
            }
        })

        $(document).on('change', '#section', function() {
            var temp_section = $(this).val()
            var temp_sy = $('#syid').val()
            var temp_strand = strand.filter(x => x.sectionid == temp_section && x.syid == temp_sy)
            $("#strand").empty()
            $.each(temp_strand, function(a, b) {
                b.text = b.strandcode
                b.id = b.strandid
            })
            $("#strand").append('<option value="">Select a strand</option>')
            $("#strand").select2({
                data: temp_strand,
                allowClear: true,
                placeholder: "Select a strand",
            })

            data = []
            loaddatatable(data)
            $('#print_student_ranking').attr('disabled', 'disabled')
        })



        $(document).on('click', '#filter', function() {

            if ($('#gradelevel').val() == '' || $('#quarter').val() == '') {
                Swal.fire({
                    type: 'info',
                    title: $('#gradelevel').val() == '' ? 'Please select grade level!' :
                        'Please select quarter',
                });
                return false;
            }

            $.ajax({
                type: 'GET',
                url: '/searchStudentWithHonors',
                data: {
                    students: 'students',
                    gradelevel: $('#gradelevel').val(),
                    section: $('#section').val(),
                    quarter: $('#quarter').val(),
                    ranking: $('#awardtype').val(),
                    from: $('#gradefrom').val(),
                    to: $('#gradeto').val(),
                    sy: $('#syid').val(),
                    semid: ($('#gradelevel').val() == 14 || $('#gradelevel').val() == 15) ? $(
                        '#semester').val() : 1,
                    strand: $('#strand').val()
                },
                success: function(data) {
                    console.log('STUDENT_HONORS', data);

                    if (data.status == 'warning') {
                        Toast.fire({
                            type: data.status,
                            title: data.message
                        });
                        loaddatatable([])
                        return;
                    }

                    var count_student = data.filter(x => x.student != 'SUBJECTS')
                    var subject_list = data.filter(x => x.student == 'SUBJECTS')
                    $('#subject_list').empty()
                    if (count_student.length == 0) {
                        Swal.fire({
                            type: 'info',
                            title: 'No student enrolled!',
                        });
                        data = []
                        loaddatatable(data)
                        $('#print_student_ranking').attr('disabled', 'disabled')
                    } else {
                        $('#print_student_ranking').removeAttr('disabled')
                        students = data
                        loaddatatable(data)
                    }
                }
            })
        })

    })
</script>

<script>
    $(document).ready(function() {
        var keysPressed = {};
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
        })
        document.addEventListener('keydown', (event) => {
            keysPressed[event.key] = true;
            if (keysPressed['p'] && event.key == 'v') {
                Toast.fire({
                    type: 'warning',
                    title: 'Date Version: 11/26/2021'
                })
            }
        });
        document.addEventListener('keyup', (event) => {
            delete keysPressed[event.key];
        });
    })
</script>
