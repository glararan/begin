<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>Profil <small><?php echo User::getInstance()->getProfileName(); ?></small></h1>

    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Domů</a></li>
        <li class="active">Profil</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-3">
            <!-- Profile Image -->
            <div class="box box-primary">
                <div class="box-body box-profile">
                    <img class="profile-user-img img-responsive img-circle" src="/images/medivh.jpg" alt="User profile picture">
                    <h3 class="profile-username text-center"><?php echo User::getInstance()->getProfileName(); ?></h3>
                    <p class="text-muted text-center"><?php echo User::getInstance()->getTypeName(); ?></p>
                    
                    <?php
                        $charDB = new Database(DB_CHAR);
                    
                        $query = $charDB->toArray($charDB->select("SELECT * FROM characters WHERE account = :id", array(":id" => User::getInstance()->getID())));
                    
                        $totaltime = 0;
                    
                        for($i = 0; $i < count($query); $i++)
                            $totaltime = $totaltime + $query[$i]["totaltime"];
                    
                        $totaltime /= 3600;
                    
                        if($totaltime >= 24)
                            $totaltime = round($totaltime / 24)." dní";
                        else
                            $totaltime = round($totaltime)."h";
                    ?>

                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item">
                            <b>Celkem postav</b> <a class="pull-right"><?php echo count($query); ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Celkem played</b> <a class="pull-right"><?php echo $totaltime; ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Registrován dne</b> <a class="pull-right"><?php echo User::getInstance()->getRegistrationDate()->format("d.m.Y"); ?></a>
                        </li>
                    </ul>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        
        <!-- /.col -->
        <div class="col-md-9">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#timeline" data-toggle="tab">Timeline</a></li>
                    <li><a href="#settings" data-toggle="tab">Nastavení</a></li>
                </ul>
                
                <?php
                    $webDB = new Database(DB_WEB);
                
                    $query2 = $webDB->toArray($webDB->select("SELECT * FROM news WHERE author = :id ORDER by date", array(":id" => User::getInstance()->getID())));
                    $query3 = $webDB->toArray($webDB->select("SELECT * FROM changelog WHERE author = :id ORDER by date", array(":id" => User::getInstance()->getID())));
                
                    $timeline = array();
                    $timelineIndex = 0;
                
                    function findIndex($timeline, $date)
                    {
                        foreach($timeline as $index => $line)
                        {
                            if($line["date"] == $date)
                                return $index;
                        }
                        
                        return false;
                    }
                
                    for($i = 0; $i < count($query2); $i++)
                    {
                        $date  = (new DateTime($query2[$i]["date"]))->format("d.m.Y");
                        $index = findIndex($timeline, $date);
                        
                        if(!$index)
                        {
                            $timeline[$timelineIndex]["date"] = (new DateTime($query2[$i]["date"]))->format("d.m.Y");
                            $timeline[$timelineIndex++]["data"] = array(0 => array("type" => "news", "title" => $query2[$i]["title"]));
                        }
                        else
                            array_push($timeline[$index]["data"], array("type" => "news", "title" => $query2[$i]["title"]));
                    }
                
                    for($i = 0; $i < count($query3); $i++)
                    {
                        $date  = (new DateTime($query3[$i]["date"]))->format("d.m.Y");
                        $index = findIndex($timeline, $date);
                        
                        if(!$index)
                        {
                            $timeline[$timelineIndex]["date"] = (new DateTime($query3[$i]["date"]))->format("d.m.Y");
                            $timeline[$timelineIndex++]["data"] = array(0 => array("type" => "changelog", "title" => ""));
                        }
                        else
                            array_push($timeline[$index]["data"], array("type" => "changelog", "title" => ""));
                    }
                
                    function compareByDate($item1, $item2)
                    {
                        return strtotime($item2["date"]) - strtotime($item1["date"]);
                    }
                
                    usort($timeline, "compareByDate");
                ?>
                
                <div class="tab-content">
                    <!-- /.tab-pane -->
                    <div class="tab-pane active" id="timeline">
                        <!-- The timeline -->
                        <ul class="timeline timeline-inverse">
                            <?php
                                $timeLabel = array("bg-red", "bg-green", "bg-blue");
                                $timeLabelIndex = 0;
                            
                                if(count($timeline) < 1)
                                {
                            ?>
                            <!-- timeline time label -->
                            <li class="time-label">
                                <span class="<?php echo $timeLabel[$timeLabelIndex++]; ?>"><?php echo date("d.m.Y"); ?></span>
                            </li>
                            <!-- /.timeline-label -->
                            <?php
                                }
                            
                                for($i = 0; $i < count($timeline); $i++)
                                {
                            ?>
                            <!-- timeline time label -->
                            <li class="time-label">
                                <span class="<?php echo $timeLabel[$timeLabelIndex++]; ?>"><?php echo $timeline[$i]["date"]; ?></span>
                            </li>
                            <!-- /.timeline-label -->
                            <?php
                                    for($j = 0; $j < count($timeline[$i]["data"]); $j++)
                                    {
                                        switch($timeline[$i]["data"][$j]["type"])
                                        {
                                            case "news":
                                                {
                                                    $type = "fa-file-text bg-aqua";
                                                    $content = "Napsal(a) jsi článek <b>".$timeline[$i]["data"][$j]["title"]."</b>";
                                                }
                                                break;
                                                
                                            case "changelog":
                                                {
                                                    $type = "fa-code-fork bg-blue";
                                                    $content = "Vydal(a) jsi changelog";
                                                }
                                                break;
                                        }
                                        
                            ?>
                            <!-- timeline item -->
                            <li>
                                <i class="fa <?php echo $type; ?>"></i>
                                <div class="timeline-item">
                                    <h3 class="timeline-header no-border"><?php echo $content; ?></h3>
                                </div>
                            </li>
                            <!-- END timeline item -->
                            <?php
                                    }
                                    
                                    if($timeLabelIndex >= 3)
                                        $timeLabelIndex = 0;
                                }
                            ?>
                            
                            <li>
                                <i class="fa fa-clock-o bg-gray"></i>
                            </li>
                        </ul>
                    </div>
                    <!-- /.tab-pane -->

                    <div class="tab-pane" id="settings">
                        <form class="form-horizontal">
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label">Avatar</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control" id="inputName" placeholder="Name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail" class="col-sm-2 control-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control" id="inputEmail" placeholder="Email">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-info">Upravit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div>
            <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->