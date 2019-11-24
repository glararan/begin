<div class="col-md-9">
    <?php
        $webDB = new Database(DB_WEB);
    
        $query = $webDB->toArray($webDB->select("SELECT n.*, ad.profileImage, acc.username as authorName FROM ".DB_WEB.".news AS n LEFT JOIN ".DB_WEB.".account_details AS ad ON ad.account = n.author INNER JOIN ".DB_AUTH.".account AS acc ON acc.id = n.author ORDER by n.id DESC"));
    
        for($i = 0; $i < count($query); $i++)
        {
    ?>
    
    <div class="article brownPanel panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><?php echo $query[$i]["title"]; ?></h3>
        </div>

        <div class="panel-body">
            <?php
                if(!is_null($query[$i]["profileImage"]))
                    echo '<div class="avatar"><img src="'.$query[$i]["profileImage"].'"></div>';
                
                echo $query[$i]["content"];
            ?>
        </div>

        <div class="panel-footer">
            <p class="pull-left">Napsal: <?php echo ucfirst(strtolower($query[$i]["authorName"])); ?></p>
            <p class="pull-right"><?php echo (new DateTime($query[$i]["date"]))->format("d.m.Y H:i"); ?></p>
            <div class="clearfix"></div>
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