// Author: Maude Issolah
// Place: ETML Lausanne
// Last update: 10.01.2018
// Subject: Ajax code to manage season table

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
            url:"index.php?controller=season&action=listAjax&boolAjax=true",
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

    $(document).on('submit', '#user_form', function(event){
        event.preventDefault();
            $.ajax({
                url:"index.php?controller=season&action=formAjax&boolAjax=true",
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

    $(document).on('click', '.update', function(){
        var sea_id = $(this).attr("id");
        $.ajax({
            url:"index.php?controller=season&action=updateAjax&boolAjax=true",
            method:"POST",
            data:{sea_id:sea_id},
            dataType:"json",
            success:function(data)
            {
                $('#userModal').modal('show');
                $('#name').val(data.name);
                $('#id').val(data.id);

                $('.modal-title').text("Modifier cette saison");
                $('#action').val("Editer");
                $('#operation').val("Edit");
            }
        })
    });

    $(document).on('click', '.delete', function(){
        var sea_id = $(this).attr("id");
        if(confirm("Are you sure you want to delete this?"))
        {
            $.ajax({
                url:"index.php?controller=season&action=deleteAjax&boolAjax=true",
                method:"POST",
                data:{sea_id:sea_id},
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