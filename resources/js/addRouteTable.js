// Author: Maude Issolah
// Place: ETML Lausanne
// Last update: 11.01.2018
// Subject: Ajax code to manage route table

$(document).ready(function(){
    $('#add_button').click(function(){
        $('#user_form')[0].reset();
        $('.modal-title').text("Nouvel itinéraire");
        $('#action').val("Ajout");
        $('#operation').val("Add");
    });

    // Create table
    var dataTable = $('#user_data').DataTable({
        "processing":true,
        "serverSide":true,
        "order":[],
        "ajax":{
            url:"index.php?controller=route&action=listAjax&boolAjax=true",
            type:"POST"
        },
        "columnDefs":[
            {
                "targets": [ 0 ],
                "visible": false, // Hide col 0
                "searchable": false
            },
            {
                "targets":[4, 5, 6, 7],
                "orderable":false,
            },
        ],

    });

    // Submit button
    $(document).on('submit', '#user_form', function(event){
        event.preventDefault();
            $.ajax({
                url:"index.php?controller=route&action=formAjax&boolAjax=true",
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
        var route_id = $(this).attr("id");
        $.ajax({
            url:"index.php?controller=route&action=updateAjax&boolAjax=true",
            method:"POST",
            data:{route_id:route_id},
            dataType:"json",
            success:function(data)
            {
                $('#userModal').modal('show');
                $('#name').val(data.name);
                $('#place').val(data.place);
                $('#describ').val(data.description);
                $('#dropPos').val(data.dropPos);
                $('#dropNeg').val(data.dropNeg);
                $('#maxAlt').val(data.maxAlt);
                $('#nbClient').val(data.nbClient);
                $('#gps').val(data.gps);
                $('#duration').val(data.duration);
                $('#danger').val(data.danger);
                $('#altern').val(data.altern);
                $('#id').val(data.id);

                //Select sport and difficulty option
                $('#ddSport').val(data.ddSport).prop('selected', true);
                $('#ddDiff').val(data.ddDiff).prop('selected', true);

                $('.modal-title').text("Modifier cet itinéraire");
                $('#route_id').val(route_id);
                $('#action').val("Editer");
                $('#operation').val("Edit");
            }
        })
    });

    // Delete button
    $(document).on('click', '.delete', function(){
        var route_id = $(this).attr("id");
        if(confirm("Are you sure you want to delete this?"))
        {
            $.ajax({
                url:"index.php?controller=route&action=deleteAjax&boolAjax=true",
                method:"POST",
                data:{route_id:route_id},
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