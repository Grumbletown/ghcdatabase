<div class="container">
    <div class="row" id="PWReset">
        <div class="col-md-4 col-md-offset-4 well">
            <form method="post" id="PWResetForm">
                <input type="hidden" value="<?php echo $id; ?>" name="id">
                <div class="form-group ">
                    <label class="control-label " for="newpw">
                        Neues Passwort
                    </label>
                    <input class="form-control" id="newpw" name="newpw" type="password">

                </div>
                <div class="form-group ">
                    <label class="control-label " for="newpwrepeat">
                        Wiederholen
                    </label>
                    <input class="form-control" id="newpwrepeat" name="newpwrepeat" type="password">
                    <span id="passMessage" class="help-block"></span>
                </div>
                <div class="form-group "><button type="button" onclick="save()" class="btn btn-success" form="PWResetForm">Ändern</button></div>
            </form>
        </div>
    </div>
</div>
<div class="form-group text-center" id="PWSuccess" >
    <h4><b>Passwort erfolgreich geändert! Du kannst dich nun <a href="<?php echo base_url('index.php/login'); ?>">Einloggen</a>!</b></h4>
</div>
<script type="text/javascript">

    $(document).ready(function(){
        $('#PWSuccess').hide();
    });
function save()
{

var url;


url = "<?php echo site_url('login/set_pw/')?>";



// ajax adding data to database
$.ajax({
url : url,
type: "POST",
data: $('#PWResetForm').serialize(),

dataType: "JSON",
success: function(data)
{

if(data.status) //if success close modal and reload ajax table
{

    $('#PWReset').hide();
    $('#PWSuccess').show();


}
else
{
for (var i = 0; i < data.inputerror.length; i++)
{
$('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
$('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
}
}



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
