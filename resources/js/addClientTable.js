// Author: Maude Issolah
// Place: ETML Lausanne
// Last update: 08.01.2018
// Subject: Ajax code to manage client table

$(document).ready(function(){
    $('#add_button').click(function(){
        $('#user_form')[0].reset();
        $('.modal-title').text("Nouveau client");
        $('#action').val("Ajout");
        $('#operation').val("Add");
    });

    // Create table
    var dataTable = $('#user_data').DataTable({
        "processing":true,
        "serverSide":true,
        "order":[],
        "ajax":{
            url:"index.php?controller=client&action=listAjax&boolAjax=true",
            type:"POST"
        },
        "columnDefs":[
            {
                "targets": [ 0 ],
                "visible": false, // Hide col 0
                "searchable": false
            },
            {
                "targets":[3, 4],
                "orderable":false,
            },
        ],

    });

    // Submit button
    $(document).on('submit', '#user_form', function(event){
        event.preventDefault();
            $.ajax({
                url:"index.php?controller=client&action=formAjax&boolAjax=true",
                method:'POST',
                data:new FormData(this),
                contentType:false,
                processData:false,
                success:function(data)
                {
                    alert(data);
                    $('#user_form')[0].reset();
                    $('#userModal').modal('hide');
                    dataTable.ajax.reload();
                }
            });
    });

    // Update button
    $(document).on('click', '.update', function(){
        var client_id = $(this).attr("id");
        $.ajax({
            url:"index.php?controller=client&action=updateAjax&boolAjax=true",
            method:"POST",
            data:{client_id:client_id},
            dataType:"json",
            success:function(data)
            {
                $('#userModal').modal('show');
                $('#firstname').val(data.firstname);
                $('#lastname').val(data.lastname);
                $('#city').val(data.city);
                $('#email').val(data.email);
                $('#cliPhone').val(data.cliPhone);
                $('#npa').val(data.npa);
                $('#street').val(data.street);
                $('#streetNb').val(data.streetNb);
                $('#urgencyPhone').val(data.urgencyPhone);

                //To uncheck all checkboxes
                $('.chBox').prop("checked",false);

                // Sickness checkboxes
                var idSickChecked = "#sickness";
                var resultSick = "";

                for (var i=0; i<data.sickness.length; i++) {
                    resultSick = idSickChecked.concat(data.sickness[i]['idSickness']);
                    $(resultSick).val(data.sickness[i]['sicName']).prop('checked', true);
                }

                // Medicament checkboxes
                var idMedChecked = "#medicament";
                var resultMed = "";

                for (var i=0; i<data.medicament.length; i++) {
                    resultMed = idMedChecked.concat(data.medicament[i]['idMedicament'])
                    $(resultMed).val(data.medicament[i]['medName']).prop('checked', true);
                    console.log("res: "+resultMed);
                }

                $('.modal-title').text("Modifier ce client");
                $('#client_id').val(client_id);
                $('#action').val("Editer");
                $('#operation').val("Edit");
            }
        })
    });

    // Delete button
    $(document).on('click', '.delete', function(){
        var client_id = $(this).attr("id");
        if(confirm("Are you sure you want to delete this?"))
        {
            $.ajax({
                url:"index.php?controller=client&action=deleteAjax&boolAjax=true",
                method:"POST",
                data:{client_id:client_id},
                success:function(data)
                {
                    alert(data);
                    dataTable.ajax.reload();
                }
            });
        }
        else
        {
            return false;
        }
    });


});