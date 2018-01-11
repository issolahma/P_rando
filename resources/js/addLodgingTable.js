// Author: Maude Issolah
// Place: ETML Lausanne
// Last update: 11.01.2018
// Subject: Ajax code to manage lodging table

$(document).ready(function(){
    $('#add_button').click(function(){
        $('#user_form')[0].reset();
        $('.modal-title').text("Nouveau logement");
        $('#action').val("Ajout");
        $('#operation').val("Add");
    });

    // Create table
    var dataTable = $('#user_data').DataTable({
        "processing":true,
        "serverSide":true,
        "order":[],
        "ajax":{
            url:"index.php?controller=lodg&action=listAjax&boolAjax=true",
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

    // Submit button
    $(document).on('submit', '#user_form', function(event){
        event.preventDefault();
        var name = $('#name').val();

        if(name != '')
        {
            $.ajax({
                url:"index.php?controller=lodg&action=formAjax&boolAjax=true",
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
        var lodg_id = $(this).attr("id");
        $.ajax({
            url:"index.php?controller=lodg&action=updateAjax&boolAjax=true",
            method:"POST",
            data:{lodg_id:lodg_id},
            dataType:"json",
            success:function(data)
            {
                $('#userModal').modal('show');
                $('#name').val(data.name);
                $('#place').val(data.place);
                $('#id').val(data.id);

                $('.modal-title').text("Modifier cette difficulté");
                $('#action').val("Editer");
                $('#operation').val("Edit");
            }
        })
    });

    // Delete button
    $(document).on('click', '.delete', function(){
        var lodg_id = $(this).attr("id");
        if(confirm("Are you sure you want to delete this?"))
        {
            $.ajax({
                url:"index.php?controller=lodg&action=deleteAjax&boolAjax=true",
                method:"POST",
                data:{lodg_id:lodg_id},
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