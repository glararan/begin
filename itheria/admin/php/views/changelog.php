<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>Changelog <small>Novinky</small></h1>

    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Domů</a></li>
        <li class="active">Changelog</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Přehled changelogů</h3>
        </div>

        <?php
            $webDB = new Database(DB_WEB);

            $query = $webDB->toArray($webDB->select("SELECT c.*, acc.username as author FROM ".DB_WEB.".changelog AS c INNER JOIN ".DB_AUTH.".account AS acc ON acc.id = c.author ORDER by c.id DESC"));
        ?>

        <div class="box-body">
            <table id="changelogTable" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Datum</th>
                        <th>Autor</th>
                        <th width="1%"></th>
                        <?php if(User::getInstance()->getType() == UserType::Admin) echo '<th width="1%"></th>'; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php                
                        for($i = 0; $i < count($query); $i++)
                        {
                            echo '<tr>';
                            echo    '<td>'.$query[$i]["id"].'</td>';
                            echo    '<td>'.(new DateTime($query[$i]["date"]))->format("d.m.Y").'</td>';
                            echo    '<td>'.$query[$i]["author"].'</td>';
                            echo    '<td><button class="btn btn-primary editChangelog" data-changelog="'.$query[$i]["id"].'">Upravit</button></td>';

                            if(User::getInstance()->getType() == UserType::Admin)
                                echo '<td><button class="btn btn-danger deleteChangelog" data-changelog="'.$query[$i]["id"].'">Smazat</button></td>';

                            echo '</tr>';
                        }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Datum</th>
                        <th>Autor</th>
                        <th></th>
                        <?php if(User::getInstance()->getType() == UserType::Admin) echo '<th width="1%"></th>'; ?>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Přidat changelog</h3>
        </div>

        <div class="box-body">
            <div class="form-group">
                <label for="changelogDate">Datum</label>
                <input type="date" name="changelogDate" id="changelogDate" value="<?php echo date("Y-m-d"); ?>" class="form-control">
            </div>

            <textarea id="changelog" name="content"></textarea>
            <button class="btn btn-success pull-right addChangelog btn-lg">Přidat</button>
        </div>
    </div>
    
    <div class="modal fade" tabindex="-1" role="dialog" id="modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Úprava changelogu</h4>
                </div>
                
                <div class="modal-body">
                    <div class="form-group">
                        <label for="changelogDateEdit">Datum</label>
                        <input type="date" name="changelogDateEdit" id="changelogDateEdit" placeholder="<?php echo date("Y-m-d"); ?>" class="form-control">
                        <input type="hidden" name="changelogID" id="changelogID">
                    </div>

                    <textarea id="changelogEdit" name="content"></textarea>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Zavřít</button>
                    <button type="button" class="btn btn-primary saveChangelog" data-dismiss="modal">Uložit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</section>
<!-- /.content -->

<script type="text/javascript">
    $(document).ready(function()
    {        
        $('#changelogTable').DataTable();
        $('#changelog').froalaEditor(
        {
            heightMin: 400,
            inlineStyles:
            {
                "Changelog title": "color:rgb(255, 243, 165);font-size:medium;font-weight:bold;"
            }
        });
        $('#changelogEdit').froalaEditor(
        {
            heightMin: 400,
            inlineStyles:
            {
                "Changelog title": "color:rgb(255, 243, 165);font-size:medium;font-weight:bold;"
            }
        });
        
        <?php
            if(User::getInstance()->getType() == UserType::Admin)
            {
        ?>
        $(document).on("click", ".deleteChangelog", function(e)
        {
            var id = $(this).data("changelog");
            
            if(confirm("Jste si jistý? Tato akce je nezvratná!"))
            {
                $.post("/admin/php/controllers/changelog.php", {type: 0, changelog: id}, function(data)
                {
                    if(data.success == 1)
                        location.reload();
                    else
                    {
                        alert(data.error);
                    }
                }, "json");
            }
        });
        <?php
            }
            
            if(User::getInstance()->getType() == UserType::Admin || User::getInstance()->getType() == UserType::GM)
            {
        ?>
        $(document).on("click", ".addChangelog", function(e)
        {
            var date    = $("#changelogDate").val();
            var content = $("#changelog").froalaEditor('html.get', true);
            
            $.post("/admin/php/controllers/changelog.php", {type: 1, date: date, content: content}, function(data)
            {
                if(data.success == 1)
                    location.reload();
                else
                    alert(data.error);
            }, "json");
        });
        
        $(document).on("click", ".editChangelog", function(e)
        {
            var id = $(this).data("changelog");
            
            $.post("/admin/php/controllers/changelog.php", {type: 3, id: id}, function(data)
            {
                if(data.success == 1)
                {
                    var date = data.changelog.date.split(' ')[0].split('-');
                    
                    $("#changelogDateEdit").val(date[0] + "-" + date[1] + "-" + date[2]);
                    $("#changelogEdit").froalaEditor('html.set', data.changelog.content);
                    $("#changelogID").val(id);
                    
                    $('#modal').modal('show');
                }
                else
                    alert(data.error);
            }, "json");
        });
        
        $(document).on("click", ".saveChangelog", function(e)
        {
            var id      = $("#changelogID").val();
            var date    = $("#changelogDateEdit").val();
            var content = $("#changelogEdit").froalaEditor('html.get', true);
            
            $.post("/admin/php/controllers/changelog.php", {type: 2, changelog: id, date: date, content: content}, function(data)
            {
                if(data.success == 1)
                    location.reload();
                else
                    alert(data.error);
            }, "json");
        });
        <?php
            }
        ?>
    });
</script>