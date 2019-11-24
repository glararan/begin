<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>Úkoly</h1>

    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Domů</a></li>
        <li class="active">Úkoly</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Přehled úkolů</h3>
        </div>

        <?php
            $webDB = new Database(DB_WEB);

            $query = $webDB->toArray($webDB->select("SELECT * FROM tasks WHERE status <> 2 ORDER by priority DESC, id DESC"));
        ?>

        <div class="box-body">
            <div class="box-group" id="tasks">
                <?php
                    for($i = 0; $i < count($query); $i++)
                    {
                        $priority = "";
                        $priorityLabel = "";
                        $priorityColor = "";

                        switch($query[$i]["priority"])
                        {
                            default:
                            case 0:
                                $priority = "text-aqua";
                                $priorityLabel = "Low";
                                $priorityColor = "info";
                                break;

                            case 1:
                                $priority = "text-yellow";
                                $priorityLabel = "Medium";
                                $priorityColor = "warning";
                                break;

                            case 2:
                                $priority = "text-red";
                                $priorityLabel = "High";
                                $priorityColor = "danger";
                                break;
                        }

                        echo '<div class="panel box task '.($query[$i]["status"] == 1 ? "ended box-success" : "box-".$priorityColor).'" data-id="'.$query[$i]["id"].'" data-priority="'.$priorityColor.'">';
                        echo    '<div class="box-header with-border">';
                        echo        '<h4 class="box-title">';
                        
                        echo            '<span class="switch switch-square switch-green" data-id="'.$query[$i]["id"].'">';
                        echo                '<input type="checkbox" data-id="'.$query[$i]["id"].'" '.($query[$i]["status"] == 1 ? "checked" : "").' id="box'.$query[$i]["id"].'">';
                        echo                '<label for="box'.$query[$i]["id"].'" data-off="O" data-on="I">';
                        echo            '</span>';
                        
                        echo            '<a data-toggle="collapse" data-parent="#tasks" href="#collapse'.$query[$i]["id"].'" class="collapsed" aria-expanded="false">'.$query[$i]["title"].'</a>';
                        echo            '<span class="label label-'.$priorityColor.'">'.$priorityLabel.'</span>';
                        echo        '</h4>';
                        
                        if(User::getInstance()->getType() == UserType::Admin)
                            echo '<button class="btn btn-danger pull-right archiveTask" data-id="'.$query[$i]["id"].'" style="'.($query[$i]["status"] == 1 ? "" : "display:none;").'">Archivovat</button>';
                        
                        echo    '</div>';
                        
                        // comment
                        echo    '<div id="collapse'.$query[$i]["id"].'" class="panel-collapse collapse" aria-expanded="false" style="height:0px;">';
                        echo        '<div class="box-body">';
                        echo            '<div class="form-group">';
                        echo                '<label>Název úkolu</label>';
                        echo                '<input type="text" placeholder="Název úkolu" class="form-control taskEditTitle" data-id="'.$query[$i]["id"].'" value="'.$query[$i]["title"].'">';
                        echo            '</div>';
                        
                        echo            '<div class="form-group">';
                        echo                '<label>Priorita</label>';
                        echo                '<div class="radio"><input type="radio" name="priority'.$query[$i]["id"].'" class="taskEditPriority" data-id="'.$query[$i]["id"].'" value="0" '.($query[$i]["priority"] == 0 ? "checked=\"checked\"" : "").'>Low</div>';
                        echo                '<div class="radio"><input type="radio" name="priority'.$query[$i]["id"].'" class="taskEditPriority" data-id="'.$query[$i]["id"].'" value="1" '.($query[$i]["priority"] == 1 ? "checked=\"checked\"" : "").'>Medium</div>';
                        echo                '<div class="radio"><input type="radio" name="priority'.$query[$i]["id"].'" class="taskEditPriority" data-id="'.$query[$i]["id"].'" value="2" '.($query[$i]["priority"] == 2 ? "checked=\"checked\"" : "").'>High</div>';
                        echo            '</div>';
                        
                        echo            '<textarea class="commentBox">'.$query[$i]["comment"].'</textarea>';
                        echo            '<button class="btn btn-primary pull-right saveTask" data-id="'.$query[$i]["id"].'">Uložit</button>';
                        echo        '</div>';
                        echo    '</div>';

                        echo '</div>';
                    }
                ?>
            </div>
        </div>
    </div>

    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Přidat úkol</h3>
        </div>

        <div class="box-body">
            <div class="form-group">
                <label for="taskTitle">Název</label>
                <input type="text" name="taskTitle" id="taskTitle" placeholder="Název úkolu" class="form-control">
            </div>
            
            <div class="form-group">
                <label>Priorita</label>
                <div class="radio taskRadio"><input type="radio" name="priority" class="taskPriority" value="0" checked>Low</div>
                <div class="radio taskRadio"><input type="radio" name="priority" class="taskPriority" value="1">Medium</div>
                <div class="radio taskRadio"><input type="radio" name="priority" class="taskPriority" value="2">High</div>
            </div>

            <textarea id="taskComment" name="content"></textarea>
            <button class="btn btn-success pull-right addTask btn-lg">Přidat</button>
        </div>
    </div>
</section>
<!-- /.content -->

<script type="text/javascript">
    $(document).ready(function()
    {        
        //$('#changelogTable').DataTable();
        $('#taskComment').froalaEditor(
        {
            heightMin: 400
        });
        $('.commentBox').froalaEditor(
        {
            heightMin: 200
        });
        
        <?php
            if(User::getInstance()->getType() == UserType::Admin)
            {
        ?>
        $(document).on("click", ".archiveTask", function(e)
        {
            var id = $(this).data("id");
            
            if(confirm("Jste si jistý? Tato akce je nezvratná!"))
            {
                $.post("/admin/php/controllers/tasks.php", {type: 0, task: id, status: 2}, function(data)
                {
                    if(data.success == 1)
                        $(".task[data-id='" + id + "']").slideToggle();
                    else
                        alert(data.error);
                }, "json");
            }
        });
        
        $(document).on("click", ".switch", function(e)
        {
            var id = $(this).data("id");
            
            var target = $(".archiveTask[data-id='" + id + "']");
            var task = $(this).closest(".task");
            
            if($(this).find("input[type=checkbox]").prop("checked"))
            {
                target.slideDown();
                
                task.addClass("ended").removeClass("box-" + task.data("priority")).addClass("box-success");
                
                $.post("/admin/php/controllers/tasks.php", {type: 0, task: id, status: 1}, function(data)
                {
                    if(data.success != 1)
                        alert(data.error);
                }, "json");
            }
            else
            {
                target.slideUp();
                
                task.removeClass("ended").removeClass("box-success").addClass("box-" + task.data("priority"));
                
                $.post("/admin/php/controllers/tasks.php", {type: 0, task: id, status: 0}, function(data)
                {
                    if(data.success != 1)
                        alert(data.error);
                }, "json");
            }
        });
        <?php
            }
            
            if(User::getInstance()->getType() == UserType::Admin || User::getInstance()->getType() == UserType::GM)
            {
        ?>
        $(document).on("click", ".addTask", function(e)
        {
            var title   = $("#taskTitle").val();
            var content = $("#taskComment").froalaEditor('html.get', true);
            var priority = $("input[name='priority']:checked").val();
            
            $.post("/admin/php/controllers/tasks.php", {type: 1, title: title, priority: priority, comment: content}, function(data)
            {
                if(data.success == 1)
                    location.reload();
                else
                    alert(data.error);
            }, "json");
        });
        
        $(document).on("click", ".saveTask", function(e)
        {
            var id      = $(this).data("id");
            var title   = $("#collapse" + id + " .taskEditTitle").val();
            var content = $("#collapse" + id + " .commentBox").froalaEditor('html.get', true);
            var priority = $("input[name='priority" + id + "']:checked").val();
            
            $.post("/admin/php/controllers/tasks.php", {type: 2, task: id, title: title, priority: priority, comment: content}, function(data)
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