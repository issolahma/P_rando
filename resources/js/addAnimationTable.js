// Author: Maude Issolah
// Place: ETML Lausanne
// Last update: 11.01.2018
// Subject: Ajax code to manage animation table

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
            url:"index.php?controller=anim&action=listAjax&boolAjax=true",
            type:"POST"
        },
        "columnDefs":[
            {
                "targets": [ 0 ],
                "visible": false,
                "searchable": false
            },
            {
                "targets":[4, 5],
                "orderable":false,
            },
        ],

    });

    $(document).on('submit', '#user_form', function(event){
        event.preventDefault();
        var name = $('#name').val();
        var owner = $('#owner').val();
        var duration = $('#duration').val();
        var matList = $('#matList').val();

        if(name != '' && owner != '' && duration != '' && matList != '')
        {
            $.ajax({
                url:"index.php?controller=anim&action=formAjax&boolAjax=true",
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
        var anim_id = $(this).attr("id");
        $.ajax({
            url:"index.php?controller=anim&action=updateAjax&boolAjax=true",
            method:"POST",
            data:{anim_id:anim_id},
            dataType:"json",
            success:function(data)
            {
                $('#userModal').modal('show');
                $('#name').val(data.name);
                $('#owner').val(data.owner);
                $('#duration').val(data.duration);
                $('#matList').val(data.matList);

                $('.modal-title').text("Modifier cet animation");
                $('#anim_id').val(anim_id);
                $('#action').val("Editer");
                $('#operation').val("Edit");
            }
        })
    });

    $(document).on('click', '.delete', function(){
        var anim_id = $(this).attr("id");
        if(confirm("Are you sure you want to delete this?"))
        {
            $.ajax({
                url:"index.php?controller=anim&action=deleteAjax&boolAjax=true",
                method:"POST",
                data:{anim_id:anim_id},
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