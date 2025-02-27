<script>
    $('#detailcontainer').append(
        '<h2 class="table-avatar">'+
            '<a href="">'+data[0].employee_info[0].firstname+' '+data[0].employee_info[0].middlename[0]+'. '+data[0].employee_info[0].lastname+'</a>'+
        '</h2>'+
        '<br>'+
        '<div class="row" id="salarysummaryheader">'+
        '</div>'+
        '<br>'
    );
    $('#detailcontainer').append(
        
        '<div class="row">'+
            '<div class="col-md-12">'+
                    '<div class="alert alert-warning alert-dismissible">'+
                        '<h5><i class="icon fas fa-info"></i> Alert!</h5>'+
                        '<ol>'+
                            '<li><strong>Basic Salary Information not yet set.</strong>'+
                                '<br>'+
                                '> Click the employee\'s name from the table'+
                                '<br>'+
                                '> From the "Basic Salary Information" tab, configure the selected employee\'s basic salary information'+
                            '</li>'+
                        '</ol>'+
                    '</div>'+
            '</div>'+
        '</div>'
    );
</script>