<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Enrollment Details</h4>
</div>
<div class="border-bottom pb-3 mb-3" style="max-height: 400px;">
    <div>
        <h5>School Year: <span class="ps_school_year"></span> <span class="semester"></span></h5>
    </div>
    <div class="row mb-3" style="font-size:10px">
        <div class="col-md-6">
            <label for="academicLevel">School Name:</label>
            <input type="text" class="form-control" id="ps_academic_level" name="academicLevel" readonly>
        </div>
        {{-- <div class="col-md-3">
            <label for="course">Course:</label>
            <input type="text" class="form-control" id="ps_course"
                name="course" readonly>
        </div>
        <div class="col-md-3">
            <label for="section">Section:</label>
            <input type="text" class="form-control" id="ps_section"
                name="section" readonly>
        </div> --}}
        <div class="col-md-6">
            <label for="dateEnrolled">Date Enrolled:</label>
            <input type="text" class="form-control" id="ps_date_enrolled" name="dateEnrolled" readonly>
        </div>
    </div>
</div>
<div class="border-bottom pb-3 mb-3" style="max-height: 400px;">
    <div>
        <h5>School Year: <span class="gs_school_year"></span> <span class="semester"></span></h5>
    </div>
    <div class="row mb-3" style="font-size:10px">
        <div class="col-md-6">
            <label for="academicLevel">School Name:</label>
            <input type="text" class="form-control" id="gs_academic_level" name="academicLevel" readonly>
        </div>
        {{-- <div class="col-md-3">
            <label for="course">Course:</label>
            <input type="text" class="form-control" id="gs_course"
                name="course" readonly>
        </div>
        <div class="col-md-3">
            <label for="section">Section:</label>
            <input type="text" class="form-control" id="gs_section"
                name="section" readonly>
        </div> --}}
        <div class="col-md-6">
            <label for="dateEnrolled">Date Enrolled:</label>
            <input type="text" class="form-control" id="gs_date_enrolled" name="dateEnrolled" readonly>
        </div>
    </div>
</div>
<div class="border-bottom pb-3 mb-3" style="max-height: 400px;">
    <div>
        <h5>School Year: <span class="jhs_school_year"></span> <span class="semester"></span></h5>
    </div>
    <div class="row mb-3" style="font-size:10px">
        <div class="col-md-6">
            <label for="academicLevel">School Name:</label>
            <input type="text" class="form-control" id="jhs_academic_level" name="academicLevel" readonly>
        </div>
        {{-- <div class="col-md-3">
            <label for="course">Course:</label>
            <input type="text" class="form-control" id="jhs_course"
                name="course" readonly>
        </div>
        <div class="col-md-3">
            <label for="section">Section:</label>
            <input type="text" class="form-control" id="jhs_section"
                name="section" readonly>
        </div> --}}
        <div class="col-md-6">
            <label for="dateEnrolled">Date Enrolled:</label>
            <input type="text" class="form-control" id="jhs_date_enrolled" name="dateEnrolled" readonly>
        </div>
    </div>
</div>
<div class="border-bottom pb-3 mb-3" style="max-height: 400px;">
    <div>
        <h5>School Year: <span class="shs_school_year"></span> <span class="semester"></span></h5>
    </div>
    <div class="row mb-3" style="font-size:10px">
        <div class="col-md-6">
            <label for="academicLevel">School Name:</label>
            <input type="text" class="form-control" id="shs_academic_level" name="academicLevel" readonly>
        </div>
        <div class="col-md-3">
            <label for="course">Strand:</label>
            <input type="text" class="form-control" id="shs_course" name="course" readonly>
        </div>
        {{-- <div class="col-md-3">
            <label for="section">Section:</label>
            <input type="text" class="form-control" id="shs_section"
                name="section" readonly>
        </div> --}}
        <div class="col-md-3">
            <label for="dateEnrolled">Date Enrolled:</label>
            <input type="text" class="form-control" id="shs_date_enrolled" name="dateEnrolled" readonly>
        </div>
    </div>
</div>
<div class="border-bottom pb-3 mb-3" style="max-height: 400px;">
    <div>
        <h5>School Year: <span class="co_school_year"></span> <span class="co_school_semester"></span></h5>
    </div>
    <div class="row mb-3" style="font-size:10px">
        <div class="col-md-3">
            <label for="academicLevel">School Name:</label>
            <input type="text" class="form-control" id="co_academic_level" name="academicLevel" readonly>
        </div>
        <div class="col-md-3">
            <label for="course">Course:</label>
            <input type="text" class="form-control" id="co_course" name="course" readonly>
        </div>
        <div class="col-md-3">
            <label for="section">Section:</label>
            <input type="text" class="form-control" id="co_section" name="section" readonly>
        </div>
        <div class="col-md-3">
            <label for="dateEnrolled">Date Enrolled:</label>
            <input type="text" class="form-control" id="co_date_enrolled" name="dateEnrolled" readonly>
        </div>
    </div>
</div>

<script>
    function loadEnrollmentDetailsContent() {
        var studentId = $('#studentLoadingModal').data('student-id');

        // Clear all input fields before loading the another data of the student
        $('#enrollment-details-inputs input, #enrollment-details-inputs select').val('');

        $.ajax({
            url: '/student/loading/student-details',
            method: 'GET',
            data: {
                studentId: studentId
            },
            success: function(response) {
                console.log(response, 'ehhhh');

                if (response.studinfo_more) {
                    // Helper function to handle null values
                    function getValue(value) {
                        return value ? value : ''; // Return empty string if value is null or undefined
                    }

                    // Populate Primary School (PS) details
                    $('.ps_school_year').text(getValue(response.studinfo_more.pssy));
                    $('#ps_academic_level').val(getValue(response.studinfo_more.psschoolname));
                    $('#ps_date_enrolled').val(getValue(response.studinfo_more.pssy));

                    // Populate Grade School (GS) details
                    $('.gs_school_year').text(getValue(response.studinfo_more.gssy));
                    $('#gs_academic_level').val(getValue(response.studinfo_more.gsschoolname));
                    $('#gs_date_enrolled').val(getValue(response.studinfo_more.gssy));

                    // Populate Junior High School (JHS) details
                    $('.jhs_school_year').text(getValue(response.studinfo_more.jhssy));
                    $('#jhs_academic_level').val(getValue(response.studinfo_more.jhsschoolname));
                    $('#jhs_date_enrolled').val(getValue(response.studinfo_more.jhssy));

                    // Populate Senior High School (SHS) details
                    $('.shs_school_year').text(getValue(response.studinfo_more.shssy));
                    $('#shs_academic_level').val(getValue(response.studinfo_more.shsschoolname));
                    $('#shs_date_enrolled').val(getValue(response.studinfo_more.shssy));

                    // Populate College (CO) details
                    $('.co_school_year').text(getValue(response.studinfo_more.collegesy));
                    $('.co_school_semester').text(getValue(response.studinfo_more.semester));

                    $('#co_academic_level').val(getValue(response.studinfo_more.levelname));
                    $('#co_course').val(getValue(response.studinfo_more.collegecourse));
                    $('#co_section').val(getValue(response.studinfo_more.sectionDesc));
                    $('#co_date_enrolled').val(getValue(response.studinfo_more.sydesc));
                } else {
                    console.error("No data found in studinfo_more.");
                }
            },
            error: function(error) {
                console.error("Error fetching student details:", error);
            }
        });

    }
</script>
