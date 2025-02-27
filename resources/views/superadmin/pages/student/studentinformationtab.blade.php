<style>
    .scrollable-content {
        max-height: calc(100vh - 240px);
        overflow-y: auto;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .scrollable-content::-webkit-scrollbar {
        display: none;
    }
</style>
<div class="scrollable-content" style=" overflow-y: auto;">
    <!-- Student Information content -->
    <div class="d-flex justify-content-between align-items-center">
        <h5 class="mb-3 mt-2">Student Details</h5>
        <button class="btn btn-primary btn-sm mr-2" data-id="" id="printStudInfo"><i class="fa fa-print"
                aria-hidden="true"></i> Print Student Info</button>
    </div>
    <form style="font-size: 12px;">
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="studentIdNo">Student ID No.</label>
                <input type="text" class="form-control" id="studentIdNo" value="1120003221" readonly>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="firstName">First Name</label>
                <input type="text" class="form-control" id="firstName" value="Raymund Jake" readonly>
            </div>
            <div class="col-md-4">
                <label for="middleName">Middle Name</label>
                <input type="text" class="form-control" id="middleName" value="Tagana" readonly>
            </div>
            <div class="col-md-3">
                <label for="lastName">Last Name</label>
                <input type="text" class="form-control" id="lastName" value="Abcede" readonly>
            </div>
            <div class="col-md-1">
                <label for="suffix">Suffix</label>
                <input type="text" class="form-control" id="suffix" readonly>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="dateOfBirth">Date of Birth</label>
                <input type="text" class="form-control" id="dateOfBirth" value="2002-09-11" readonly>
            </div>
            <div class="col-md-4">
                <label for="placeOfBirth">Place of Birth</label>
                <input type="text" class="form-control" id="placeOfBirth" value="Iligan City" readonly>
            </div>
            <div class="col-md-4">
                <label for="gender">Gender</label>
                <input type="text" class="form-control" id="gender" readonly>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="mobileNumber">Mobile Number</label>
                <input type="tel" class="form-control" id="mobileNumber" value="09760856671" readonly>
            </div>
            <div class="col-md-8">
                <label for="emailAddress">Email Address</label>
                <input type="email" class="form-control" id="emailAddress" value="abcederj@gmail.com" readonly>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="religion">Religion</label>
                <input type="text" class="form-control" id="religion" value="Catholic" readonly>
            </div>
            <div class="col-md-4">
                <label for="nationality">Nationality</label>
                <input type="text" class="form-control" id="nationality" value="Filipino" readonly>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <label for="permanentAddress">Permanent Address</label>
                <input type="text" class="form-control" id="permanentAddress" value="Ditucalan, Iligan City"
                    readonly>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <label for="presentAddress">Present Address</label>
                <input type="text" class="form-control" id="presentAddress"
                    value="Mountain View Homes, Balulong Cagayan De Oro City" readonly>
            </div>
        </div>
        <h5 class="mb-4 mt-3">Parents / Guardian Information</h5>
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="fatherFirstName">Father's First Name</label>
                <input type="text" class="form-control" id="fatherFirstName" value="Jake" readonly>
            </div>
            <div class="col-md-4">
                <label for="fatherMiddleName">Father's Middle Name</label>
                <input type="text" class="form-control" id="fatherMiddleName" value="Tual" readonly>
            </div>
            <div class="col-md-3">
                <label for="fatherLastName">Father's Last Name</label>
                <input type="text" class="form-control" id="fatherLastName" value="Abcede" readonly>
            </div>
            <div class="col-md-1">
                <label for="fatherSuffix">Suffix</label>
                <input type="text" class="form-control" id="fatherSuffix" readonly>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-3">
                <label for="fContactNumber">Contact Number</label>
                <input type="text" class="form-control" id="fContactNumber" value="Jake" readonly>
            </div>
            <div class="col-md-2">
                <label for="fOccupation">Occupation</label>
                <input type="text" class="form-control" id="fOccupation" value="Tual" readonly>
            </div>
            <div class="col-md-5">
                <label for="fHAE">High Educational Attainment</label>
                <input type="text" class="form-control" id="fHAE" value="Abcede" readonly>
            </div>
            <div class="col-md-2">
                <label for="fEthnicity">Ethnicity</label>
                <input type="text" class="form-control" id="fEthnicity" readonly>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="motherFirstName">Mother's First Name</label>
                <input type="text" class="form-control" id="motherFirstName" value="Jane" readonly>
            </div>
            <div class="col-md-4">
                <label for="motherMiddleName">Mother's Middle Name</label>
                <input type="text" class="form-control" id="motherMiddleName" value="Doe" readonly>
            </div>
            <div class="col-md-3">
                <label for="motherLastName">Mother's Last Name</label>
                <input type="text" class="form-control" id="motherLastName" value="Smith" readonly>
            </div>
            <div class="col-md-1">
                <label for="motherSuffix">Suffix</label>
                <input type="text" class="form-control" id="motherSuffix" readonly>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-3">
                <label for="mContactNumber">Contact Number</label>
                <input type="text" class="form-control" id="mContactNumber" value="1234567890" readonly>
            </div>
            <div class="col-md-2">
                <label for="mOccupation">Occupation</label>
                <input type="text" class="form-control" id="mOccupation" value="Teacher" readonly>
            </div>
            <div class="col-md-5">
                <label for="mHAE">High Educational Attainment</label>
                <input type="text" class="form-control" id="mHAE" value="Master's Degree" readonly>
            </div>
            <div class="col-md-2">
                <label for="mEthnicity">Ethnicity</label>
                <input type="text" class="form-control" id="mEthnicity" readonly>
            </div>
        </div>
        <h5 class="mb-4 mt-3">Guardian Information</h5>
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="guardianFirstName">Guardian's First Name</label>
                <input type="text" class="form-control" id="guardianFirstName" value="John" readonly>
            </div>
            <div class="col-md-4">
                <label for="guardianMiddleName">Guardian's Middle Name</label>
                <input type="text" class="form-control" id="guardianMiddleName" value="Doe" readonly>
            </div>
            <div class="col-md-3">
                <label for="guardianLastName">Guardian's Last Name</label>
                <input type="text" class="form-control" id="guardianLastName" value="Smith" readonly>
            </div>
            <div class="col-md-1">
                <label for="guardianSuffix">Suffix</label>
                <input type="text" class="form-control" id="guardianSuffix" readonly>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-3">
                <label for="gContactNumber">Contact Number</label>
                <input type="text" class="form-control" id="gContactNumber" value="0987654321" readonly>
            </div>
            <div class="col-md-2">
                <label for="gOccupation">Occupation</label>
                <input type="text" class="form-control" id="gOccupation" value="Engineer" readonly>
            </div>
            <div class="col-md-5">
                <label for="gHAE">High Educational Attainment</label>
                <input type="text" class="form-control" id="gHAE" value="Bachelor's Degree" readonly>
            </div>
            <div class="col-md-2">
                <label for="gEthnicity">Ethnicity</label>
                <input type="text" class="form-control" id="gEthnicity" readonly>
            </div>
        </div>
        <h5 class="mb-4 mt-3">Educational Information</h5>
        <div class="row mb-3" style="font-size: 12px">
            <div class="col-md-3">
                <label for="lastSchoolAttended">Last School Attended</label>
                <input type="text" class="form-control" id="lastSchoolAttended" value="John" readonly>
            </div>
            <div class="col-md-3">
                <label for="lastGradeLevelCompleted">Last Grade Level Completed</label>
                <input type="text" class="form-control" id="lastGradeLevelCompleted" value="Doe" readonly>
            </div>
            <div class="col-md-3">
                <label for="schoolContactNo">School's Contact No.</label>
                <input type="text" class="form-control" id="schoolContactNo" value="Smith" readonly>
            </div>
            <div class="col-md-3">
                <label for="schoolMailingAddress">Last School Mailing Address</label>
                <input type="text" class="form-control" id="schoolMailingAddress" readonly>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="schoolTypeLabel"></label>
            </div>
            <div class="col-md-3">
                <label for="schoolName">School Name</label>
            </div>
            <div class="col-md-3">
                <label for="schoolYearGraduated">School Year Graduated</label>
            </div>
            <div class="col-md-3">
                <label for="schoolType">School Type</label>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="preSchool">Pre-School</label>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" id="preSchoolName" value="Doe" readonly>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" id="preSchoolYearGraduated" value="Smith" readonly>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" id="preSchoolType" readonly>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="gradeSchool">Grade School</label>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" id="gradeSchoolName" value="Doe" readonly>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" id="gradeSchoolYearGraduated" value="Smith" readonly>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" id="gradeSchoolType" readonly>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="juniorHighSchool">Junior High School</label>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" id="juniorHighSchoolName" value="Doe" readonly>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" id="juniorHighSchoolYearGraduated" value="Smith"
                    readonly>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" id="juniorHighSchoolType" readonly>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="seniorHighSchool">Senior High School</label>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" id="seniorHighSchoolName" value="Doe" readonly>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" id="seniorHighSchoolYearGraduated" value="Smith"
                    readonly>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" id="seniorHighSchoolType" readonly>
            </div>
        </div>
        <h5 class="mb-4 mt-3">Medical Information</h5>
        <div class="row mb-3">
            <div class="col-md-2">
                <label for="Height">Height (Meters)</label>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" id="Height" value="" readonly>
            </div>
            <div class="col-md-2">
                <label for="Weight">Weight (kgs)</label>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" id="Weight" readonly>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="otherMed">Any Current Medications, Specify </label>
            </div>
            <div class="col-md-8">
                <input type="text" class="form-control" id="otherMed" value="" readonly>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-2">
                <label for="anyAllergies">Any Allergies</label>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" id="anyAllergies" value="" readonly>
            </div>
            <div class="col-md-2">
                <label for="medAllergies"> Medications to allergies</label>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" id="medAllergies" value="" readonly>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-2">
                <label for="medHistory">Medical History</label>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" id="medHistory" value="" readonly>
            </div>
            <div class="col-md-2">
                <label for="otherMedInfo">Other Medical Information</label>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" id="otherMedInfo" value="" readonly>
            </div>
        </div>
    </form>
</div>

<script>
    $('#printStudInfo').on('click', function() {
        var studid = $(this).attr('data-id');
        window.open('/registrar/studentinfo/print?studid=' + studid, '_blank');
    });
</script>
