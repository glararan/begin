<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>Články <small></small></h1>

    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Domů</a></li>
        <li class="active">Články</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Přehled článků</h3>
        </div>

        <?php
            $webDB = new Database(DB_WEB);

            $query = $webDB->toArray($webDB->select("SELECT n.*, acc.username as author FROM ".DB_WEB.".news AS n INNER JOIN ".DB_AUTH.".account AS acc ON acc.id = n.author ORDER by n.id DESC"));
        ?>

        <div class="box-body">
            <table id="articleTable" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titulek</th>
                        <th>Autor</th>
                        <th>Datum</th>
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
                            echo    '<td>'.$query[$i]["title"].'</td>';
                            echo    '<td>'.ucfirst(strtolower($query[$i]["author"])).'</td>';
                            echo    '<td>'.(new DateTime($query[$i]["date"]))->format("d.m.Y H:i").'</td>';
                            echo    '<td><button class="btn btn-primary editArticle" data-article="'.$query[$i]["id"].'">Upravit</button></td>';

                            if(User::getInstance()->getType() == UserType::Admin)
                                echo '<td><button class="btn btn-danger deleteArticle" data-article="'.$query[$i]["id"].'">Smazat</button></td>';

                            echo '</tr>';
                        }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Titulek</th>
                        <th>Autor</th>
                        <th>Datum</th>
                        <th></th>
                        <?php if(User::getInstance()->getType() == UserType::Admin) echo '<th width="1%"></th>'; ?>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Přidat článek</h3>
        </div>

        <div class="box-body">
            <div class="form-group">
                <label for="articleTitle">Titulek</label>
                <input type="text" name="articleTitle" id="articleTitle" placeholder="Nadpis článku" class="form-control">
            </div>

            <textarea id="article" name="content"></textarea>
            <button class="btn btn-success pull-right addArticle btn-lg">Přidat</button>
        </div>
    </div>
    
    <div class="modal fade" tabindex="-1" role="dialog" id="modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Úprava článku</h4>
                </div>
                
                <div class="modal-body">
                    <div class="form-group">
                        <label for="articleTitleEdit">Datum</label>
                        <input type="text" name="articleTitleEdit" id="articleTitleEdit" placeholder="Nadpis článku" class="form-control">
                        <input type="hidden" name="articleID" id="articleID">
                    </div>

                    <textarea id="articleEdit" name="content"></textarea>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Zavřít</button>
                    <button type="button" class="btn btn-primary saveArticle" data-dismiss="modal">Uložit</button>
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
        $('#articleTable').DataTable();
        $('#article').froalaEditor(
        {
            heightMin: 400
        });
        $('#articleEdit').froalaEditor(
        {
            heightMin: 400
        });
        
        <?php
            if(User::getInstance()->getType() == UserType::Admin)
            {
        ?>
        $(document).on("click", ".deleteArticle", function(e)
        {
            var id = $(this).data("article");
            
            if(confirm("Jste si jistý? Tato akce je nezvratná!"))
            {
                $.post("/admin/php/controllers/article.php", {type: 0, article: id}, function(data)
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
        $(document).on("click", ".addArticle", function(e)
        {
            var title   = $("#articleTitle").val();
            var content = $("#article").froalaEditor('html.get', true);
            
            $.post("/admin/php/controllers/article.php", {type: 1, title: title, content: content}, function(data)
            {
                if(data.success == 1)
                    location.reload();
                else
                {
                    alert(data.error);
                }
            }, "json");
        });
        
        $(document).on("click", ".editArticle", function(e)
        {
            var id = $(this).data("article");
            
            $.post("/admin/php/controllers/article.php", {type: 3, id: id}, function(data)
            {
                if(data.success == 1)
                {
                    $("#articleTitleEdit").val(data.article.title);
                    $("#articleEdit").froalaEditor('html.set', data.article.content);
                    $("#articleID").val(id);
                    
                    $('#modal').modal('show');
                }
                else
                    alert(data.error);
            }, "json");
        });
        
        $(document).on("click", ".saveArticle", function(e)
        {
            var id      = $("#articleID").val();
            var title   = $("#articleTitleEdit").val();
            var content = $("#articleEdit").froalaEditor('html.get', true);
            
            $.post("/admin/php/controllers/article.php", {type: 2, article: id, title: title, content: content}, function(data)
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