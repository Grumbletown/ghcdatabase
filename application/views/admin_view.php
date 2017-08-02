<div class="container">
    <div class="container">
        <div class="form-group text-center" id="succmsg" >
            <h4><b><span aria-hidden="true" id="successmsg"></span></b></h4>
        </div>
    <?php



    if($_SESSION['Rep'] ==  0){
        ?>
        <div class="alert alert-warning alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <p>Gib deine Reputation in den Einstellungen an, damit wir für dich relevante IPs anzeigen können!</p>
        </div>
    <?php }
    ?>
    <script type="text/javascript">
        $(document).ready(function(){

            $('.datepicker').datepicker({
                autoclose: true,
                format: "yyyy-mm-dd",
                todayHighlight: true,
                orientation: "top auto",
                todayBtn: false,
                todayHighlight: true,
            });


            var role = "<?php echo $_SESSION['Role'];?>";

            $('#myTable').DataTable({

                "processing": true,
                "keys": true,
                "serverSide": true,
                "order": [
                    [1, "asc" ]
                ],
                'rowCallback': function(row, data, index){
                    $(row).find('td:eq(8)').css('background-color', '#222222');
                    $(row).find('td:eq(8)').css('border', '0px');

                },
                "createdRow": function( row, data, dataIndex ) {
                    if ( data[5] == "Abgelaufen" ) {
                        $(row).addClass('danger');

                    }
                    if ( data[5] == "Gültig" ) {
                        $(row).addClass('info');

                    }
                    if (data[5] == "Admin" || data[5] == "Moderator"){
                        $(row).addClass('success');
                    }
                    
                },
                "columnDefs": [
                    {
                        "targets": 'nosort',
                        "orderable": false,

                    },
                    
                    {

                        "width": "6%",
                        targets: 8,
                        render: function (data, type, row, meta) {
                            if (type === 'display') {

                                if (role === 'Admin' || role === 'Moderator'){
                                    data = '<a id="edituserlink"  onclick="edit_user('+row[0]+')" data-placement="top" data-toggle="tooltip" title="Edit" ><button id="edituser'+row[0]+'" class="btn btn-primary btn-xs" ><span class="glyphicon glyphicon-pencil" id="editipclass"></span></button></a>';



                                }
                            }
                            return data;

                        }}






                ],

                "ajax": {
                    url : "<?php echo site_url("admintab/user_page") ?>",
                    type : 'GET'

                },







                "dom": "<'row'<'col-sm-6'l><'col-sm-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row text-center'<'col-md-8 text-center'p>>",




            });




        });

        function edit_user(id)
        {
            save_method = 'update';




            $('#UserForm')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string





            //Ajax Load data from ajax
            $.ajax({
                url : "<?php echo site_url('admintab/user_edit')?>/" + id + "/",
                type: "GET",
                dataType: "JSON",
                success: function(data)
                {
                    $("#sel1").val(data.Role);
                    $('[name="id"]').val(data.ID);
                    $('[name="expire"]').val(data.ExpireDate);
                    $('[name="name"]').val(data.Username);
                    $('[name="reputation"]').val(data.Reputation);
                    $('[name="discord"]').val(data.DiscordName);
                    $('#EditUser').modal('show'); // show bootstrap modal when complete loaded
                    $('.modal-title').text('Edit User'); // Set title to Bootstrap modal title

                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error get data from ajax');
                }
            });
        }
        function delete_user()
        {

            if(confirm('Are you sure delete this data?'))
            {
                // ajax delete data to database
                $.ajax({
                    url : "<?php echo site_url('admintab/ajax_delete/')?>",
                    type: "POST",
                    data: $('#UserForm').serialize(),
                    dataType: "JSON",
                    success: function(data)
                    {
                        if(data.status){
                            //if success reload ajax table
                            $('#EditUser').modal('hide');
                            $('#myTable').DataTable().ajax.reload(null, false);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        alert('Error deleting data');
                    }
                });

            }
        }
        var csrfName = '<?php echo $this->security->get_csrf_token_name();?>';
        var csrfHash = '<?php echo $this->security->get_csrf_hash();?>';
        function save()
        {
            $('#btnSave').text('saving...'); //change button text
            $('#btnSave').attr('disabled',true); //set button disable
            var url;

            
                url = "<?php echo site_url('admintab/ajax_update')?>";
                
            

            // ajax adding data to database
            $.ajax({
                url : url,
                type: "POST",
                data: $('#UserForm').serialize(),

                dataType: "JSON",
                success: function(data)
                {

                    if(data.status) //if success close modal and reload ajax table
                    {

                            $('#EditUser').modal('hide');
                            $('#myTable').DataTable().ajax.reload();
                        $('#successmsg').parent().addClass('text-success');
                        $('#successmsg').text('Daten erfolgreich gespeichert!');


                    }
                    else
                    {
                        for (var i = 0; i < data.inputerror.length; i++)
                        {
                            $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                            $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                        }
                    }
                    $('#btnSave').text('save'); //change button text
                    $('#btnSave').attr('disabled',false); //set button enable


                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    //alert('Error adding / update data');
                    alert('An error occurred... Look at the console (F12 or Ctrl+Shift+I, Console tab) for more information!');

                    $('#result').html('<p>status code: '+jqXHR.status+'</p><p>errorThrown: ' + errorThrown + '</p><p>jqXHR.responseText:</p><div>'+jqXHR.responseText + '</div>');
                    console.log('jqXHR:');
                    console.log(jqXHR);
                    console.log('textStatus:');
                    console.log(textStatus);
                    console.log('errorThrown:');
                    console.log(errorThrown);
                    $('#btnSave').text('save'); //change button text
                    $('#btnSave').attr('disabled',false); //set button enable

                }
            });
        }


    </script>
    
    <div id="tableInfo" style="margin-top: 15px;">
        <h4>Um die Tabelle nach mehreren Spalten gleichzeitig zu sortieren, halte 'Shift' und wähle die anderen Spalten aus.</h4>
        <h4>Du kannst eine IP kopieren, indem du auf sie klickst.</h4>


    </div>







    <!-- Modal -->
    <div class="modal fade" id="EditUser" tabindex="-1" role="dialog" aria-labelledby="EditUserLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="EditUserLabel">User Bearbeiten</h4>
                </div>
                <div class="modal-body">
                    <form id="UserForm" action="" method="post">
                        <input type="hidden" value="" name="id">
                    <div class="form-group" id="nameDiv">
                        <label for="inputName">Name</label>
                        <input type="text" name="name" class="form-control" id="inputName" placeholder="MaxDerHacker" ">
                        <span id="nameMessage" class="help-block"></span>
                    </div>
                    <div class="form-group" id="selectDiv">
                        <label for="sel1">Select list</label>
                        <select class="form-control" id="sel1" name="role">


                            <option><div data-value="User">User</div></option>
                            <option><div data-value="Moderator">Moderator</div></option>
                            <option><div data-value="Admin">Admin</div></option>
                        </select>
                    </div>
                    <div class="form-group" id="reputationDiv">
                        <label for="inputReputation">Reputation</label>
                        <input type="number" name="reputation" class="form-control" id="inputReputation" placeholder="42">
                        <span id="reputationMessage" class="help-block"></span>
                    </div>
                    <div class="form-group" id="expireDIV">
                        <label for="datepicker">Expires</label>

                            <input name="expire" placeholder="yyyy-mm-dd" class="form-control datepicker" readonly type="text" >
                            <span class="help-block"></span>


                    </div>
                        <div class="form-group" id="emailDiv">
                            <label for="inputEmail">Email</label>
                            <input type="text" name="email" class="form-control" id="inputEmail" placeholder="a@ghc">
                            <span id="EmailMessage" class="help-block"></span>
                        </div>
                    <div class="form-group" id="discordDiv">
                        <label for="inputDiscord">Discord</label>
                        <input type="number" name="discord" class="form-control" id="inputDiscord" placeholder="42">
                        <span id="DiscordMessage" class="help-block"></span>
                    </div>
                        </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick='delete_user()'>Löschen</button>
                    <button type="button" class="btn btn-default" onclick='' data-dismiss="modal">Schließen</button>
                    <button type="button" class="btn btn-primary" onclick='save()'>Speichern</button>
                </div>

            </div>
        </div>
    </div>



    <div class="page">
        <div class="table-responsive">
            <center><b><caption class="btn btn-danger">User Table</caption></b></center>
            <table class="table dt-responsive nowrap table-bordered table-condensed " id="myTable" style="margin-top: 25px;">
                <thead>
                <tr>
                    <th class='col-md-1' style="padding-right: 20px;">ID</th>
                    <th class="col-md-1" style="padding-right: 20px;">Name</th>

                    <th class="col-md-1" style="padding-right: 20px;">Rolle</th>
                    <th class="col-md-1" style="padding-right: 20px;">Reputation</th>
                    <th class="col-md-1" style="padding-right: 20px;">Last Login (Y-M-D H:M:S)</th>

                    <th class="nosort" style="padding-right: 20px;">Gültigkeit</th>
                    <th class="nosort" style="padding-right: 20px;">Email</th>
                    <th class="nosort" style="padding-right: 20px;">Discord</th>

                    <th class='nosort' width="40%" valign="top" border="0px">Edit</th>
                </tr>
                </thead>
                <tbody id="tbody">


                </tbody>
            </table>
        </div>
    </div>

</div> <!-- /Container-->





