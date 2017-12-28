$(document).ready(function(){
    $('#add_button').click(function(){
        $('#user_form')[0].reset();
        $('.modal-title').text("Ajout");
        $('#action').val("Ajout");
        $('#operation').val("Add");
    });

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
                "visible": false,
                "searchable": false
            },
            {
                "targets":[3, 4],
                "orderable":false,
            },
        ],

    });

    $(document).on('submit', '#user_form', function(event){
        event.preventDefault();
        var firstName = $('#firstname').val();
        var lastName = $('#lastname').val();
        var street = $('#street').val();
        var streetNb = $('#streetNb').val();
        var city = $('#city').val();
        var npa = $('#npa').val();
        var cliPhone = $('#cliPhone').val();
        var urgencyPhone = $('#urgencyPhone').val();
        var email = $('#email').val();

        if(firstName != '' && lastName != '')
        {
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
        }
        else
        {
            alert("All Fields are Required");
        }
    });

    $(document).on('click', '.update', function(){
        var user_id = $(this).attr("id");
        $.ajax({
            url:"index.php?controller=client&action=updateAjax&boolAjax=true",
            method:"POST",
            data:{user_id:user_id},
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

                var idSickChecked = "#sickness";
                var resultSick = "";

                for (var i=0; i<data.sickness.length; i++) {
                    resultSick = idSickChecked.concat(data.sickness[i]['idSickness']);
                    $(resultSick).val(data.sickness[i]['sicName']).prop('checked', true);
                }

                var idMedChecked = "#medicament";
                var resultMed = "";

                for (var i=0; i<data.medicament.length; i++) {
                    resultMed = idMedChecked.concat(data.medicament[i]['idMedicament'])
                    $(resultMed).val(data.medicament[i]['medName']).prop('checked', true);
                    console.log("res: "+resultMed);
                }

                $('.modal-title').text("Modifier ce client");
                $('#user_id').val(user_id);
                $('#action').val("Editer");
                $('#operation').val("Edit");
            }
        })
    });

    $(document).on('click', '.delete', function(){
        var user_id = $(this).attr("id");
        if(confirm("Are you sure you want to delete this?"))
        {
            $.ajax({
                url:"index.php?controller=client&action=deleteAjax&boolAjax=true",
                method:"POST",
                data:{user_id:user_id},
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