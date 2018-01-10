// Author: Maude Issolah
// Place: ETML Lausanne
// Last update: 08.01.2018
// Subject: Ajax code to manage accompaniste table

$(document).ready(function(){
    $('#add_button').click(function(){
        $('#user_form')[0].reset();
        $('.modal-title').text("Nouvel accompagnateur");
        $('#action').val("Ajout");
        $('#operation').val("Add");
        $('#accPwd').show();
        $('.pwdHide').show();
    });

    // Create table
    var dataTable = $('#user_data').DataTable({
        "processing":true,
        "serverSide":true,
        "order":[],
        "ajax":{
            url:"index.php?controller=accompanist&action=listAjax&boolAjax=true",
            type:"POST"
        },
        "columnDefs":[
            {
                "targets": [ 0 ],
                "visible": false,
                "searchable": false
            },
            {
                "targets":[5, 6, 7],
                "orderable":false,
            },
        ],

    });

    // Submit button
    $(document).on('submit', '#user_form', function(event){
        event.preventDefault();
        var firstName = $('#firstname').val();
        var lastName = $('#lastname').val();
        var accRight = $('#right').val();
        var login = $('#login').val();
        var pwd = $('#password').val();

        // if(firstName != '' && lastName != '' && accRight != '' && login != '')
        // {
            $.ajax({
                url:"index.php?controller=accompanist&action=formAjax&boolAjax=true",
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
        // }
        // else
        // {
        //     alert("All Fields are Required");
        // }
    });

    // Update button
    $(document).on('click', '.update', function(){
        var acc_id = $(this).attr("id");
        $.ajax({
            url:"index.php?controller=accompanist&action=updateAjax&boolAjax=true",
            method:"POST",
            data:{acc_id:acc_id},
            dataType:"json",
            success:function(data)
            {
                $('#userModal').modal('show');
                $('#firstname').val(data.firstname);
                $('#lastname').val(data.lastname);
                $('#right').val(data.accRight);
                $('#login').val(data.login);
                $('#acc_id').val(acc_id);

                $('.pwdHide').show();
                $('#accPwd').hide();
                $('.modal-title').text("Modifier cet accompagnateur");
                $('#action').val("Editer");
                $('#operation').val("Edit");
            }
        })
    });

// Reset password button
    $(document).on('click', '.reset', function(){
        var acc_id = $(this).attr("id");
        $.ajax({
            url:"index.php?controller=accompanist&action=updateAjax&boolAjax=true",
            method:"POST",
            data:{acc_id:acc_id},
            dataType:"json",
            success:function(data)
            {
                $('#userModal').modal('show');
                $('.pwdHide').hide();
                $('#accPwd').show();
                $('#id').val(data.id);

                $('.modal-title').text("Nouveau mot de passe");
                $('#acc_id').val(acc_id);
                $('#action').val("Editer");
                $('#operation').val("NewPwd");
            }
        });
    });

    // Delete button
    $(document).on('click', '.delete', function(){
        var acc_id = $(this).attr("id");
        if(confirm("Are you sure you want to delete this?"))
        {
            $.ajax({
                url:"index.php?controller=accompanist&action=deleteAjax&boolAjax=true",
                method:"POST",
                data:{acc_id:acc_id},
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