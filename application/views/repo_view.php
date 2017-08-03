<div class="container">
    <div class="text-right">
        <a href="#" data-toggle="modal" data-target="#TableInfo" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-question-sign" ></span></a>


    </div>
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
        var rep = "<?php echo $_SESSION['Rep'];?>";
        var role = "<?php echo $_SESSION['Role'];?>";
        var save_method;
        var favtab = "IPUserFav";
        var repotab = "IPUserReport";
        $(document).ready(function(){



            $("#neueip").on('click', function(){
                console.log("hey");
                $("#loeschen").hide();
                $("#speichern").show();
            });




            $('#myTable').DataTable({

                "processing": true,
                "keys": true,
                "serverSide": true,
                "order": [
                    [1, "asc" ]
                ],
                'rowCallback': function(row, data, index){
                    $(row).find('td:eq(7)').css('background-color', '#222222');
                    $(row).find('td:eq(7)').css('border', '0px');

                },
                "createdRow": function( row, data, dataIndex ) {
                    if ( data[2] > rep * 0.75 ) {
                        $(row).addClass('info');

                    }
                    if ( data[2] < rep * 0.25 ) {
                        $(row).addClass('warning');

                    }

                },
                "columnDefs": [
                    {
                        "targets": 'nosort',
                        "orderable": false,

                    },
                    {
                        targets: 0,
                        render: function (data, type, row, meta) {
                            if (type === 'display') {
                                data = "<button class='btn btn-link btn-xs'data-clipboard-text = '" + row[0] + "' > " + row[0] + " </button>"
                            }
                            return data;
                        }
                    },
                    {

                        "width": "6%",
                        targets: -1,
                        render: function (data, type, row, meta) {
                            if (type === 'display') {

                                if (row[7] === '1') {
                                    data = '<a id="' + row[8] + '" href="javascript:void(0)" onclick="favreport_js('+row[8]+',0,\''+favtab+'\')" data-placement="top" data-toggle="tooltip" title="UnFavourite" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-star"></span></a>'
                                }
                                else {
                                    data = '<a id="' + row[8] + '" href="javascript:void(0)" onclick="favreport_js('+row[8]+',1,\''+favtab+'\')" data-placement="top" data-toggle="tooltip" title="Favourite" class="btn btn-info btn-xs"><span class="glyphicon glyphicon glyphicon-star-empty"></span></a>'
                                }
                                if (row[9] === '1') {
                                    data += '<a id="' + row[8] + '" onclick="favreport_js('+row[8]+',0,\''+repotab+'\')" data-placement="top" data-toggle="tooltip" title="UnReport" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-ok"></span></a>'
                                }
                                else {
                                    data += '<a id="' + row[8] + '" onclick="favreport_js('+row[8]+',1,\''+repotab+'\')" data-placement="top" data-toggle="tooltip" title="Report" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon glyphicon-alert"></span></a>'
                                }

                                if (role === 'Admin' || role === 'Moderator'){
                                    data += '<a id="editiplink"  onclick="edit_ip('+row[8]+')" data-placement="top" data-toggle="tooltip" title="Edit" ><button id="editip'+row[8]+'" class="btn btn-primary btn-xs" ><span class="glyphicon glyphicon-pencil" id="editipclass"></span></button></a>';



                                }
                            }
                            return data;

                        }}






                ],

                "ajax": {
                    url : "<?php echo site_url("repotab/ips_page") ?>",
                    type : 'GET'

                },







                "dom": "<'row'<'col-sm-6'l><'col-sm-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row text-center'<'col-md-8 text-center'p>>",




            });




        });

        function add_ip()
        {
            save_method = 'add';



            $('#IPForm')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string


            //$('#IPModal').append('<button type="button" class="btn btn-default btn-ok" >Delete</button>' );
            $('#IPModal').modal('show'); // show bootstrap modal
            $('.modal-title').text('Neue IP hinzufügen'); // Set Title to Bootstrap modal title
        }

        function favreport_js(id, switches, whattab)
        {


            //Ajax Load data from ajax
            $.ajax({
                url : "<?php echo site_url('repotab/fav_repo')?>/" + id + "/" + switches + "/" + whattab + "/",
                type: "GET",
                dataType: "JSON",
                success: function(data)
                {
                    if(data.status) //if success close modal and reload ajax table
                    {
                        $('#myTable').DataTable().ajax.reload(null, false);
                        
                    }

                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error get data from ajax');
                }
            });
        }
        function delete_ip()
        {

            if(confirm('Are you sure delete this data?'))
            {
                // ajax delete data to database
                $.ajax({
                    url : "<?php echo site_url('repotab/ajax_delete/')?>",
                    type: "POST",
                    data: $('#IPForm').serialize(),
                    dataType: "JSON",
                    success: function(data)
                    {
                        if(data.status){
                            //if success reload ajax table
                            $('#IPModal').modal('hide');
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


        function edit_ip(id)
        {
            save_method = 'update';

            console.log("Open modal | edit_ip");
            $("#loeschen").show();
            $("#speichern").hide();


            $('#IPForm')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string





            //Ajax Load data from ajax
            $.ajax({
                url : "<?php echo site_url('repotab/ip_edit')?>/" + id + "/",
                type: "GET",
                dataType: "JSON",
                success: function(data)
                {

                    $('[name="id"]').val(data.ID);
                    $('[name="IP"]').val(data.IP);
                    $('[name="name"]').val(data.Name);
                    $('[name="reputation"]').val(data.Reputation);
                    $('[name="miners"]').val(data.Miners);
                    $('[name="clan"]').val(data.Clan);
                    $('[name="description"]').val(data.Description);
                    $('#IPModal').modal('show'); // show bootstrap modal when complete loaded
                    $('.modal-title').text('Edit IP'); // Set title to Bootstrap modal title

                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error get data from ajax');
                }
            });
        }
        var csrfName = '<?php echo $this->security->get_csrf_token_name();?>';
        var csrfHash = '<?php echo $this->security->get_csrf_hash();?>';
        function save(weiter)
        {
            $('#btnSave').text('saving...'); //change button text
            $('#btnSave').attr('disabled',true); //set button disable
            var url;

            if(save_method == 'add') {
                url = "<?php echo site_url('repotab/ajax_add/')?>";
                msgtext = "hinzugefügt";
            } else {
                url = "<?php echo site_url('repotab/ajax_update')?>";
                msgtext = "geändert!";
            }

            // ajax adding data to database
            $.ajax({
                url : url,
                type: "POST",
                data: $('#IPForm').serialize(),

                dataType: "JSON",
                success: function(data)
                {

                    if(data.status) //if success close modal and reload ajax table
                    {
                        if(weiter == 1){
                            $('#IPModal').modal('hide');
                            $('#myTable').DataTable().ajax.reload();
                            $('#successmsg').parent().addClass('text-success');
                            $('#successmsg').text('Daten erfolgreich gespeichert!');

                        }

                        if(weiter == 2){
                            save_method = 'add';
                            $('#IPForm')[0].reset(); // reset form on modals
                            $('.form-group').removeClass('has-error'); // clear error class
                            $('.help-block').empty();
                            $('#myTable').DataTable().ajax.reload();
                            $('#successmsg').parent().addClass('"text-success');
                            $('#successmsg').text('Daten erfolgreich gespeichert!');

                        }
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
    <!-- Modal begins here -->
    <div class="modal fade" id="IPModal" tabindex="-1" role="dialog" aria-labelledby="addIPLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="addIPLabel">Neue IP hinzufügen</h4>
                </div>
                <div class="modal-body">
                    <form id="IPForm" action="" method="post">
                        <input type="hidden" value="" name="id">
                        <div class="form-group" id="IPDiv">
                            <label for="inputIP">IP</label>
                            <input autofocus type="text" name="IP" id="inputIP" class="form-control" placeholder="123.123.123.123" ">
                            <span id="IPMessage" class="help-block"></span>
                        </div>
                        <div class="form-group" id="nameDiv">
                            <label for="inputName">Name</label>
                            <input type="text" name="name" class="form-control" id="inputName" placeholder="MaxDerHacker" ">
                            <span id="nameMessage" class="help-block"></span>
                        </div>
                        <div class="form-group" id="reputationDiv">
                            <label for="inputReputation">Reputation</label>
                            <input type="number" name="reputation" class="form-control" id="inputReputation" placeholder="42">
                            <span id="reputationMessage" class="help-block"></span>
                        </div>
                        <div class="form-group" id="minersDiv">
                            <label for="inputMiners">Miners</label>
                            <input type="number" name="miners" class="form-control" id="inputMiners" placeholder="42">
                            <span id="minersMessage" class="help-block"></span>
                        </div>
                        <div class="form-group" id="clanDiv">
                            <label for="inputClan">Clan</label>
                            <input type="text" name="clan" class="form-control" id="inputClan" placeholder="ABC">
                            <span id="clanMessage" class="help-block"></span>
                        </div>
                        <div class="form-group" id="descriptionDiv">
                            <label for="inputDescription">Description</label>
                            <textarea type="text" name="description" class="form-control" rows="5" id="inputDescription" placeholder="i = inaktiv"></textarea>
                            <span id="descriptionMessage" class="help-block"></span>
                        </div>
                    </form>
                </div> <!-- Modal Body -->
                <div class="modal-footer">
                    <button type="button" id="loeschen" onclick="delete_ip()" class="btn btn-danger" form="IPForm">Löschen</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
                    <button type="button" onclick="save(1)" class="btn btn-primary" form="IPForm">Speichern > Schließen!</button>
                    <button type="button" id="speichern" onclick="save(2)" class="btn btn-primary" form="IPForm">Speichern > Weiter!</button>

                </div> <!-- Modal Footer -->
            </div> <!-- Modal Content -->
        </div> <!-- Modal-Dialog -->
    </div> <!-- Modal -->
    <div class="page">
        <div class="table-responsive">
            <center><b><caption class="btn btn-danger">Reported IPs Table</caption></b></center>
            <table class="table dt-responsive nowrap table-bordered table-condensed " id="myTable" style="margin-top: 25px;">
                <thead>
                <tr>
                    <th class="col-md-1" style="padding-right: 20px;">IP</th>
                    <th class="col-md-1" style="padding-right: 20px;">Name</th>
                    <th class="col-md-1" style="padding-right: 20px;">Rep</th>

                    <th class="col-md-1" style="padding-right: 20px;">Beschreibung</th>
                    <th class="col-md-1" style="padding-right: 20px;">Miners</th>
                    <th class="col-md-1" style="padding-right: 20px;">Gilde</th>
                    <th class="col-md-2" style="padding-right: 20px;">Updated</th>
                    <!--    <th class="col-md-2" style="padding-right: 20px;">Added By</th> -->
                    <th class='nosort' width="40%" valign="top" border="0px">



                        <form class="form-inline">


                            <button type="button" id="neueip" class="btn btn-primary" onclick="add_ip()" style="height: 25px; line-height: 1px;">Neue IP</button>
                        </form>




                    </th>



                    <?php
                    /*if($_SESSION["Role"] == "Moderator" || $_SESSION["Role"] == "Admin"){
                        echo "<th class='nosort'>Edit</th>";
                    }*/
                    ?>

                </tr>
                <thead>
                <tbody id="tbody">
                <?php
                /*
    
                //Einfärben der gemeldeten IPs
                if($row->CountIPRepo >= 5){
                   $class = "danger";
                }
    
                */
                ?>

                </tbody>
            </table>
        </div>
    </div>

</div> <!-- /Container-->
<script type="text/javascript" src="<?php echo base_url('assets/js/clipboard.min.js') ?>"></script>
<script>
    var clipboard = new Clipboard('.btn');
    clipboard.on('success', function(e) {
        console.log(e);
    });
    clipboard.on('error', function(e) {
        console.log("Fehler oderso :" + e);
    });
</script>




