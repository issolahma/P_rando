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
                "targets":[3, 4],
                "orderable":false,
            },
        ],

    });

    $(document).on('submit', '#user_form', function(event){
        event.preventDefault();
        var firstName = $('#firstname').val();
        var lastName = $('#lastname').val();
        var accRight = $('#right').val();
        var login = $('#login').val();
        var pwd = $('#password').val();

        if(firstName != '' && lastName != '' && accRight != '' && login != '')
        {
            $.ajax({
                url:"index.php?controller=accompanist&action=formAjax&boolAjax=true",
                method:'POST',
                data:new FormData(this),
                contentType:false,
                processData:false,
                success:function(data)
                {
                    $('#accPwd').show();
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
            url:"index.php?controller=accompanist&action=updateAjax&boolAjax=true",
            method:"POST",
            data:{user_id:user_id},
            dataType:"json",
            success:function(data)
            {
                $('#userModal').modal('show');
                $('#firstname').val(data.firstname);
                $('#lastname').val(data.lastname);
                $('#right').val(data.accRight);
                $('#login').val(data.login);
                $('#id').val(data.id);
                $('#accPwd').hide();

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
                url:"index.php?controller=accompanist&action=deleteAjax&boolAjax=true",
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