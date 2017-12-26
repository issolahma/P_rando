$(document).ready(function(){
    $('#add_button').click(function(){
        $('#user_form')[0].reset();
        $('.modal-title').text("Ajout d'un nouveau thème");
        $('#action').val("Ajout");
        $('#operation').val("Add");
    });

    var dataTable = $('#user_data').DataTable({
        "processing":true,
        "serverSide":true,
        "order":[],
        "ajax":{
            url:"index.php?controller=theme&action=listAjax&boolAjax=true",
            type:"POST"
        },
        "columnDefs":[
            {
                "targets":[1, 2],
                "orderable":false,
            },
        ],

    });

    $(document).on('submit', '#user_form', function(event){
        event.preventDefault();
        var theName = $('#name').val();

        if(theName != '')
        {
            $.ajax({
                url:"index.php?controller=theme&action=formAjax&boolAjax=true",
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
            url:"index.php?controller=theme&action=updateAjax&boolAjax=true",
            method:"POST",
            data:{user_id:user_id},
            dataType:"json",
            success:function(data)
            {
                $('#userModal').modal('show');
                $('#name').val(data.name);

                $('.modal-title').text("Modifier cet accompagnateur");
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
                url:"index.php?controller=theme&action=deleteAjax&boolAjax=true",
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