// Author: Maude Issolah
// Place: ETML Lausanne
// Last update: 10.01.2018
// Subject: Ajax code to manage difficulty table

$(document).ready(function(){
    $('#add_button').click(function(){
        $('#user_form')[0].reset();
        $('.modal-title').text("Nouvelle difficulté");
        $('#action').val("Ajout");
        $('#operation').val("Add");
    });

    // Create table
    var dataTable = $('#user_data').DataTable({
        "processing":true,
        "serverSide":true,
        "order":[],
        "ajax":{
            url:"index.php?controller=diff&action=listAjax&boolAjax=true",
            type:"POST"
        },
        "columnDefs":[
            {
                "targets": [ 0 ],
                "visible": false,
                "searchable": false
            },
            {
                "targets":[2, 3],
                "orderable":false,
            },
        ],
    });

    // Submit button
    $(document).on('submit', '#user_form', function(event){
        event.preventDefault();
        var name = $('#name').val();

         if(name != '')
         {
        $.ajax({
            url:"index.php?controller=diff&action=formAjax&boolAjax=true",
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

    // Update button
    $(document).on('click', '.update', function(){
        var dif_id = $(this).attr("id");
        $.ajax({
            url:"index.php?controller=diff&action=updateAjax&boolAjax=true",
            method:"POST",
            data:{dif_id:dif_id},
            dataType:"json",
            success:function(data)
            {
                $('#userModal').modal('show');
                $('#name').val(data.name);
                $('#dif_id').val(data.id);

                $('.modal-title').text("Modifier cette difficulté");
                $('#action').val("Editer");
                $('#operation').val("Edit");
            }
        })
    });

// Reset password button
    $(document).on('click', '.reset', function(){
        var dif_id = $(this).attr("id");
        $.ajax({
            url:"index.php?controller=diff&action=updateAjax&boolAjax=true",
            method:"POST",
            data:{dif_id:dif_id},
            dataType:"json",
            success:function(data)
            {
                $('#userModal').modal('show');
                $('.pwdHide').hide();
                $('#accPwd').show();
                $('#id').val(data.id);

                $('.modal-title').text("Nouveau mot de passe");
                $('#dif_id').val(dif_id);
                $('#action').val("Editer");
                $('#operation').val("NewPwd");
            }
        });
    });

    // Delete button
    $(document).on('click', '.delete', function(){
        var dif_id = $(this).attr("id");
        if(confirm("Are you sure you want to delete this?"))
        {
            $.ajax({
                url:"index.php?controller=diff&action=deleteAjax&boolAjax=true",
                method:"POST",
                data:{dif_id:dif_id},
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