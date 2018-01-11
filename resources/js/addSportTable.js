// Author: Maude Issolah
// Place: ETML Lausanne
// Last update: 11.01.2018
// Subject: Ajax code to manage sport table

$(document).ready(function(){
    $('#add_button').click(function(){
        $('#user_form')[0].reset();
        $('.modal-title').text("Nouveau sport");
        $('#action').val("Ajout");
        $('#operation').val("Add");
    });

    // Create table
    var dataTable = $('#user_data').DataTable({
        "processing":true,
        "serverSide":true,
        "order":[],
        "ajax":{
            url:"index.php?controller=sport&action=listAjax&boolAjax=true",
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
                url:"index.php?controller=sport&action=formAjax&boolAjax=true",
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
        var sport_id = $(this).attr("id");
        $.ajax({
            url:"index.php?controller=sport&action=updateAjax&boolAjax=true",
            method:"POST",
            data:{sport_id:sport_id},
            dataType:"json",
            success:function(data)
            {
                $('#userModal').modal('show');
                $('#name').val(data.name);
                $('#id').val(data.id);

                $('.modal-title').text("Modifier ce sport");
                $('#action').val("Editer");
                $('#operation').val("Edit");
            }
        })
    });

    // Delete button
    $(document).on('click', '.delete', function(){
        var sport_id = $(this).attr("id");
        if(confirm("Are you sure you want to delete this?"))
        {
            $.ajax({
                url:"index.php?controller=sport&action=deleteAjax&boolAjax=true",
                method:"POST",
                data:{sport_id:sport_id},
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