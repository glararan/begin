<?php
    if(!User::isLogged())
        header("location: ./");
?>

<div class="col-md-9">
    <div class="brownPanel panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Změna hesla</h3>
        </div>

        <div class="panel-body">
            <form data-toggle="validator" role="form" id="zmenitheslo">
                <div class="form-group">
                    <label for="oldPassword" class="control-label">Staré heslo</label>
                    <input type="password" class="form-control" id="oldPassword" name="oldPassword" placeholder="******" required>
                </div>

                <div class="form-group">
                    <label for="newPassword" class="control-label">Nové heslo</label>
                    <input type="password" class="form-control" id="newPassword" name="newPassword" placeholder="******" required data-minlength="6">
                    <div class="help-block">Minimálně 6 znaků</div>
                </div>

                <div class="form-group">
                    <label for="newPassword2" class="control-label">Heslo</label>
                    <input type="password" class="form-control" id="newPassword2" name="newPassword2" placeholder="******" required data-match="#newPassword" data-match-error="Whoops, hesla se neshodují">
                    <div class="help-block with-errors"></div>
                </div>

                <div class="form-group">
                    <input type="submit" class="form-control" value="Odeslat">
                </div>
            </form>
        </div>
    </div>
    
    <?php
        View::show("footer");
    ?>
</div>

<?php
    View::show("infomenu");
?>

<script type="text/javascript">
    $(document).ready(function()
    {
        $("#zmeniheslo").validator().on('submit', function(e)
        {
            if(e.isDefaultPrevented())
            {
                // eerror
            }
            else
            {
                // good
                $.post("php/controllers/profil.php", {oldPass: $("input[name=oldPassword]").val(), newPass: $("input[name=newPassword]").val(), newPass2: $("input[name=newPassword2]").val()}, function(data)
                {
                    if(data.success == 1)
                    {
                        alert("Změna hesla úspěšná.");
                        
                        location.reload();
                    }
                    else
                        alert(data.error);
                }, "json");
            }
        });
    });
</script>