<div class="col-md-9">
    <?php
        $webDB = new Database(DB_WEB);
    
        $query = $webDB->toArray($webDB->select("SELECT * FROM changelog ORDER by id DESC"));
    
        for($i = 0; $i < count($query); $i++)
        {
    ?>
            <div class="brownPanel panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo (new DateTime($query[$i]["date"]))->format("d.m.Y"); ?></h3>
                </div>

                <div class="panel-body">
                    <pre>
                        <?php echo $query[$i]["content"]; ?>
                    </pre>
                </div>
            </div>
    <?php
        }
    
        View::show("footer");
    ?>
</div>

<?php
    View::show("infomenu");
?>