<style>
    /* Add padding and background color for the button container */
    .row.mb-2 {
        padding: 10px;
        background-color: #f5f5f5;
        /* Light gray background */
        border-radius: 5px;
    }

    /* Styling for each button */
    #btn-export-sl,
    #btn-export-sl-xml,
    #btn-export-sl-json,
    #btn-export-sl-excel {
        color: #fff;
        /* White text */
        background-color: #007bff;
        /* Bootstrap's primary blue */
        border-color: #007bff;
        margin-right: 10px;
        /* Spacing between buttons */
        transition: background-color 0.3s ease;
        /* Smooth hover transition */
    }

    #btn-export-sl:hover,
    #btn-export-sl-xml:hover,
    #btn-export-sl-json:hover,
    #btn-export-sl-excel:hover {
        background-color: #0056b3;
        /* Darker shade on hover */
        border-color: #0056b3;
    }

    /* Optional: icon styling */
    #btn-export-sl i,
    #btn-export-sl-xml i,
    #btn-export-sl-json i,
    #btn-export-sl-excel i {
        margin-right: 5px;
        /* Spacing between icon and text */
    }
</style>

<button type="button" class="btn btn-default" id="btn-export-sl-excel"><i class="fa fa-file-excel"></i> Export to
    EXCEL</button>
<button type="button" class="btn btn-default" id="btn-export-sl-xml"><i class="fa fa-file-code"></i> Export to
    XML</button>
<button type="button" class="btn btn-default" id="btn-export-sl-json"><i class="fa fa-file-code"></i> Export to
    JSON</button>

<script>
    $(document).ready(function() {
        $(document).on('click', '#btn-export-sl-xml', function() {
            window.open('/registrar/studentlist?type=sl&action=exportxml&syid=' + $('#sl-syid').val() +
                '&semid=' + $('#sl-semid').val(), '_blank')
        })
        $(document).on('click', '#btn-export-sl-json', function() {
            window.open('/registrar/studentlist?type=sl&action=exportjson&syid=' + $('#sl-syid').val() +
                '&semid=' + $('#sl-semid').val(), '_blank')
        })
        $(document).on('click', '#btn-export-sl-excel', function() {
            window.open('/registrar/studentlist?type=sl&action=exportexcel&syid=' + $('#sl-syid')
                .val() +
                '&semid=' + $('#sl-semid').val(), '_blank')
        })

    });
</script>
