<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>Dashboard <small>Přehled</small></h1>

    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Domů</a></li>
        <li class="active">Dashboard</li>
    </ol>
</section>

<?php
    $charDB = new Database(DB_CHAR);
    $authDB = new Database(DB_AUTH);

    $query = $charDB->toArray($charDB->select("SELECT * FROM characters WHERE online = 1")); // char online
    $query2 = array_reverse($authDB->toArray($authDB->select("SELECT * FROM uptime ORDER by starttime DESC LIMIT 30"))); // uptime
    $query3 = $charDB->toArray($charDB->select("SELECT * FROM characters WHERE account <> 0")); // race and class statistic
    $query4 = $charDB->toArray($charDB->select("SELECT * FROM characters WHERE account <> 0 ORDER by guid DESC LIMIT 5")); // last 5 characters created
    $query5 = $authDB->toArray($authDB->select("SELECT * FROM account ORDER by id DESC LIMIT 5")); // last 5 accounts created
    $query6 = $charDB->toArray($charDB->select("SELECT c.*, a.username, a.email FROM ".DB_CHAR.".characters AS c INNER JOIN ".DB_AUTH.".account AS a ON a.id = c.account LEFT JOIN ".DB_AUTH.".rbac_account_permissions AS rbac ON rbac.accountId = a.id WHERE rbac.permissionId IS NULL ORDER by c.totaltime DESC LIMIT 1")); // highest played char
    $query7 = $charDB->toArray($charDB->select("SELECT c.*, a.username, a.email FROM ".DB_CHAR.".characters AS c INNER JOIN ".DB_AUTH.".account AS a ON a.id = c.account LEFT JOIN ".DB_AUTH.".rbac_account_permissions AS rbac ON rbac.accountId = a.id LEFT JOIN ".DB_AUTH.".account_banned AS b ON b.id = a.id WHERE rbac.permissionId IS NULL AND (b.active IS NULL OR b.active = 0) ORDER by c.money DESC LIMIT 1")); // richest char

    // uptime
    $uptimeLabel = "";
    $uptimeData  = "";

    for($i = 0; $i < count($query2); $i++)
    {
        $uptime = round($query2[$i]["uptime"] / 60 / 60);
        
        if($i == count($query2) - 1)
        {
            $uptimeLabel .= '""';//'"'.$i.'"';
            $uptimeData  .= $uptime;
        }
        else
        {
            $uptimeLabel .= '"", ';//'"'.$i.'", ';
            $uptimeData  .= $uptime.", ";
        }
    }

    // race and class statistic
    $race = array("Human" => 0, "Dwarf" => 0, "NightElf" => 0, "Gnome" => 0, "Draenei" => 0, "Worgen" => 0, "Orc" => 0, "Undead" => 0, "Tauren" => 0, "Troll" => 0, "BloodElf" => 0, "Goblin" => 0);
    $class = array("Druid" => 0, "Warlock" => 0, "Mage" => 0, "Shaman" => 0, "DK" => 0, "Priest" => 0, "Rogue" => 0, "Hunter" => 0, "Paladin" => 0, "Warrior" => 0);
    $faction = array("Alliance" => 0, "Horde" => 0);

    for($i = 0; $i < count($query3); $i++)
    {
        switch($query3[$i]["class"])
        {
            case 11: // Druid
                $class["Druid"]++;
                break;

            case 9: // Warlock
                $class["Warlock"]++;
                break;

            case 8: // Mage
                $class["Mage"]++;
                break;

            case 7: // Shaman
                $class["Shaman"]++;
                break;

            case 6: // Death Knight
                $class["DK"]++;
                break;

            case 5: // Priest
                $class["Priest"]++;
                break;

            case 4: // Rogue
                $class["Rogue"]++;
                break;

            case 3: // Hunter
                $class["Hunter"]++;
                break;

            case 2: // Paladin
                $class["Paladin"]++;
                break;

            case 1: // Warrior
                $class["Warrior"]++;
                break;
        }

        switch($query3[$i]["race"])
        {
            // Alliance
            case 22:
                $race["Worgen"]++;
                $faction["Alliance"]++;
                break;

            case 11:
                $race["Draenei"]++;
                $faction["Alliance"]++;
                break;

            case 7:
                $race["Gnome"]++;
                $faction["Alliance"]++;
                break;

            case 4:
                $race["NightElf"]++;
                $faction["Alliance"]++;
                break;

            case 3:
                $race["Dwarf"]++;
                $faction["Alliance"]++;
                break;

            case 1:
                $race["Human"]++;
                $faction["Alliance"]++;
                break;

            // Horde
            case 10:
                $race["BloodElf"]++;
                $faction["Horde"]++;
                break;

            case 9:
                $race["Goblin"]++;
                $faction["Horde"]++;
                break;

            case 8:
                $race["Troll"]++;
                $faction["Horde"]++;
                break;

            case 6:
                $race["Tauren"]++;
                $faction["Horde"]++;
                break;

            case 5:
                $race["Undead"]++;
                $faction["Horde"]++;
                break;

            case 2:
                $race["Orc"]++;
                $faction["Horde"]++;
                break;
        }
    }

    // last 5 characters
    $lastCharacters = array();

    for($i = 0; $i < count($query4); $i++)
    {
        $char = array();
        
        switch($query4[$i]["class"])
        {
            case 11: // Druid
                $char["class"] = "Druid";
                break;

            case 9: // Warlock
                $char["class"] = "Warlock";
                break;

            case 8: // Mage
                $char["class"] = "Mage";
                break;

            case 7: // Shaman
                $char["class"] = "Shaman";
                break;

            case 6: // Death Knight
                $char["class"] = "DeathKnight";
                break;

            case 5: // Priest
                $char["class"] = "Priest";
                break;

            case 4: // Rogue
                $char["class"] = "Rogue";
                break;

            case 3: // Hunter
                $char["class"] = "Hunter";
                break;

            case 2: // Paladin
                $char["class"] = "Paladin";
                break;

            case 1: // Warrior
                $char["class"] = "Warrior";
                break;
        }

        switch($query4[$i]["race"])
        {
            // Alliance
            case 22:
                $char["race"] = "Worgen";
                $char["faction"] = "Alliance";
                break;

            case 11:
                $char["race"] = "Draenei";
                $char["faction"] = "Alliance";
                break;

            case 7:
                $char["race"] = "Gnom";
                $char["faction"] = "Alliance";
                break;

            case 4:
                $char["race"] = "Noční elf";
                $char["faction"] = "Alliance";
                break;

            case 3:
                $char["race"] = "Trpaslík";
                $char["faction"] = "Alliance";
                break;

            case 1:
                $char["race"] = "Člověk";
                $char["faction"] = "Alliance";
                break;

            // Horde
            case 10:
                $char["race"] = "Krvavý elf";
                $char["faction"] = "Horda";
                break;

            case 9:
                $char["race"] = "Goblin";
                $char["faction"] = "Horda";
                break;

            case 8:
                $char["race"] = "Troll";
                $char["faction"] = "Horda";
                break;

            case 6:
                $char["race"] = "Tauren";
                $char["faction"] = "Horda";
                break;

            case 5:
                $char["race"] = "Nmertvý";
                $char["faction"] = "Horda";
                break;

            case 2:
                $char["race"] = "Ork";
                $char["faction"] = "Horda";
                break;
        }
        
        $char["name"] = $query4[$i]["name"];
        $char["level"] = $query4[$i]["level"];
        $char["gender"] = $query4[$i]["gender"];
        
        array_push($lastCharacters, $char);
    }

    // last 5 accounts
    $lastAccounts = array();

    for($i = 0; $i < count($query5); $i++) // it not neccessary to use this at all, simplify => $query5 at print
        array_push($lastAccounts, $query5[$i]);

    // highest played char
    $highestPlayedChar = $query6[0];

    function calculatePlayed($time)
    {
        $timeLabel = "";
        
        if($time > 86400 * 2) // days
        {
            $timeLabel .= floor($time / 24 / 60 / 60)." dní ";
            $time -= floor($time / 24 / 60 / 60) * 86400;
        }
        if($time > 86400) // day
        {
            $timeLabel .= floor($time / 24 / 60 / 60)." den ";
            $time -= floor($time / 24 / 60 / 60) * 86400;
        }
        if($time > 3600 * 2) // hours
        {
            $timeLabel .= floor($time / 60 / 60)." hodin ";
            $time -= floor($time / 60 / 60) * 3600;
        }
        if($time > 3600) // hour
        {
            $timeLabel .= floor($time / 60 / 60)." hodina ";
            $time -= floor($time / 60 / 60) * 3600;
        }
        if($time > 60 * 2) // minut
        {
            $timeLabel .= floor($time / 60)." minut ";
            $time -= floor($time / 60) * 60;
        }
        if($time > 60) // minuta
        {
            $timeLabel .= floor($time / 60)." minuta ";
            $time -= floor($time / 60) * 60;
        }        
        
        return $timeLabel;
    }
    
    // richest player
    $richestChar = $query7[0];

    function calculateMoney($money)
    {
        $moneyLabel = ""; // 1c - 99c 100 - 9999 (=> 1-99s) 10000 - infinity (1g - ..)

        if($money >= 10000)
        {
            $money /= 10000;

            $moneyLabel = floor($money)."<img src='../../images/gold.png'> ";

            $money = ($money - floor($money)) * 10000;
        }

        if($money >= 100)
        {
            $money /= 100;

            $moneyLabel .= floor($money)."<img src='../../images/silver.png'> ";

            $money = ($money - floor($money)) * 100;
        }

        if($money > 0)
            $moneyLabel .= round($money)."<img src='../../images/cooper.png'>";
        
        return $moneyLabel;
    }

    // map
    $mapsCount  = array("Azeroth", "Outland", "Northrend");
    $mapsPoints = "0,1,530,571,609";
    

    /* TODO
    */
?>

<!-- Main content -->
<section class="content">
    <!-- Info boxes -->
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-battery-4"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Hráč s největším played</span>
                    <span class="info-box-number"><?php echo $highestPlayedChar["name"]; ?>: <?php echo calculatePlayed($highestPlayedChar["totaltime"]); ?> <small>(<?php echo ucfirst(strtolower($highestPlayedChar["username"]))." - ".$highestPlayedChar["email"]; ?>)</small></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Hráč s nejvíce penězi</span>
                    <span class="info-box-number"><?php echo $richestChar["name"]; ?>: <?php echo calculateMoney($richestChar["money"]); ?> <small>(<?php echo ucfirst(strtolower($richestChar["username"]))." - ".$richestChar["email"]; ?>)</small></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <!-- <div class="clearfix visible-sm-block"></div> betwenn 2 -->
    </div>
    <!-- /.row -->

    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Uptime Report</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-8">
                            <p class="text-center">
                                <strong>Server Uptime [hodin]</strong>
                            </p>
                            
                            <div class="chart">
                                <canvas id="uptimeChart" style="height:180px;"></canvas>
                            </div>
                        </div>
                        <!-- /.col -->
                        
                        <?php
                            $alliance = 0;
                            $horde = 0;
                        
                            for($i = 0; $i < count($query); $i++)
                            {
                                if(in_array($query[$i]["race"], array(22, 11, 7, 4, 3, 2, 1)))
                                    $alliance++;
                                else
                                    $horde++;
                            }
                        ?>
                        
                        <div class="col-md-4">
                            <p class="text-center">
                                <strong>Stav hráčů</strong>
                            </p>
                            <div class="progress-group">
                                <span class="progress-text">Aliance</span>
                                <span class="progress-number"><b><?php echo $alliance; ?></b>/100</span>
                                <div class="progress sm">
                                    <div class="progress-bar progress-bar-aqua" style="width: <?php echo $alliance; ?>%"></div>
                                </div>
                            </div>
                            <!-- /.progress-group -->
                            <div class="progress-group">
                                <span class="progress-text">Horda</span>
                                <span class="progress-number"><b><?php echo $horde; ?></b>/100</span>
                                <div class="progress sm">
                                    <div class="progress-bar progress-bar-red" style="width: <?php echo $horde; ?>%"></div>
                                </div>
                            </div>
                            <!-- /.progress-group -->
                            <div class="progress-group">
                                <span class="progress-text">Celkem</span>
                                <span class="progress-number"><b><?php echo count($query); ?></b>/100</span>
                                <div class="progress sm">
                                    <div class="progress-bar progress-bar-green" style="width: <?php echo count($query); ?>%"></div>
                                </div>
                            </div>
                            <!-- /.progress-group -->
                            <div class="progress-group">
                                <span class="progress-text">Maximum online</span>
                                <span class="progress-number"><b><?php $temp = array_values($query2); echo end($temp)["maxplayers"]; ?></b>/100</span>
                                <div class="progress sm">
                                    <div class="progress-bar progress-bar-yellow" style="width: <?php echo end($temp)["maxplayers"]; ?>%"></div>
                                </div>
                            </div>
                            <!-- /.progress-group -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- ./box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
    
    <div class="row">
        <div class="col-md-4">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Statistika frakcí</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="chart-responsive">
                                <canvas id="factionChart" height="250"></canvas>
                            </div>
                            <!-- ./chart-responsive -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-4">
                            <ul class="chart-legend clearfix">
                                <li><i class="fa fa-circle-o text-light-blue"></i> Aliance</li>
                                <li><i class="fa fa-circle-o text-red"></i> Horda</li>
                            </ul>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        
        <div class="col-md-4">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Statistika herních ras</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="chart-responsive">
                                <canvas id="raceChart" height="250"></canvas>
                            </div>
                            <!-- ./chart-responsive -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-4">
                            <ul class="chart-legend clearfix">
                                <li><i class="fa fa-circle-o text-red"></i> Lidé</li>
                                <li><i class="fa fa-circle-o text-green"></i> Traslíci</li>
                                <li><i class="fa fa-circle-o text-yellow"></i> Noční elfové</li>
                                <li><i class="fa fa-circle-o text-aqua"></i> Gnomové</li>
                                <li><i class="fa fa-circle-o text-light-blue"></i> Draeneiové</li>
                                <li><i class="fa fa-circle-o text-gray"></i> Worgeni</li>
                                <li><i class="fa fa-circle-o text-teal"></i> Orkové</li>
                                <li><i class="fa fa-circle-o text-navy"></i> Nemrtvý</li>
                                <li><i class="fa fa-circle-o text-purple"></i> Taureni</li>
                                <li><i class="fa fa-circle-o text-orange"></i> Trollové</li>
                                <li><i class="fa fa-circle-o text-maroon"></i> Krvavý elfové</li>
                                <li><i class="fa fa-circle-o text-lime"></i> Goblini</li>
                            </ul>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        
        <div class="col-md-4">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Statistika herních povolání</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="chart-responsive">
                                <canvas id="classChart" height="250"></canvas>
                            </div>
                            <!-- ./chart-responsive -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-4">
                            <ul class="chart-legend clearfix">
                                <li><i class="fa fa-circle-o text-orange"></i> Druid</li>
                                <li><i class="fa fa-circle-o text-red"></i> Death Knight</li>
                                <li><i class="fa fa-circle-o text-green"></i> Hunter</li>
                                <li><i class="fa fa-circle-o text-aqua"></i> Mage</li>
                                <li><i class="fa fa-circle-o text-maroon"></i> Paladin</li>
                                <li><i class="fa fa-circle-o text-teal"></i> Priest</li>
                                <li><i class="fa fa-circle-o text-yellow"></i> Rogue</li>
                                <li><i class="fa fa-circle-o text-light-blue"></i> Shaman</li>
                                <li><i class="fa fa-circle-o text-navy"></i> Warlock</li>
                                <li><i class="fa fa-circle-o text-gray"></i> Warrior</li>
                            </ul>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>

    <!-- Main row -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Herní mapa</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                
                <!-- /.box-header -->
                <div class="box-body no-padding">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pad">
                                <!-- Map will be created here -->
                                <!-- <div id="world-map-markers" style="height: 325px;"></div>-->
                                <div id="mapWrapper">
                                    <div id="mapContainer">
                                        <div id="pointsAzeroth" class=""></div>
                                        <div id="pointsNorthrend" class="hidden"></div>
                                        <div id="pointsGrizzlyHills" class="hidden"></div>

                                        <div id="azeroth" data-points="#pointsAzeroth" data-original-width="7313" data-original-height="4931" class="map"></div>
                                        <div id="northrend" data-points="#pointsNorthrend" data-original-width="2501" data-original-height="1952" class="map hidden"></div>
                                        <div id="grizzlyhills" data-points="#pointsGrizzlyHills" data-original-width="1252" data-original-height="844" class="map hidden"></div>
                                        
                                        <!-- <img src="../../../images/northrend.jpg"> -->
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="btn-group mapBtn-group col-md-12" role="group" aria-label="...">
                                        <button type="button" class="btn btn-info col-md-4 mapBtn active" data-switch="#azeroth">
                                            <div class="col-xs-12 text-center">
                                                <img src="../../../images/azeroth_icon.png">
                                            </div>
                                            
                                            <div class="col-xs-12">Azeroth</div>
                                        </button>
                                        
                                        <button type="button" class="btn btn-info col-md-4 mapBtn" data-switch="#northrend">
                                            <div class="col-xs-12 text-center">
                                                <img src="../../../images/northrend_icon.png">
                                            </div>
                                            
                                            <div class="col-xs-12">Northrend</div>
                                        </button>
                                        
                                        <button type="button" class="btn btn-info col-md-4 mapBtn" data-switch="#grizzlyhills">
                                            <div class="col-xs-12 text-center">
                                                <img src="../../../images/grizzlyhillls_icon.png">
                                            </div>
                                            
                                            <div class="col-xs-12">Grizzly Hills</div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.box-body -->
            </div>
            
            <!-- /.box -->
            <div class="row">
                <div class="col-md-6">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Naposledy vytvořené postavy</h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>

                        <!-- /.box-header -->
                        <div class="box-body">
                            <ul class="products-list product-list-in-box">
                                <?php
                                    for($i = 0; $i < count($lastCharacters); $i++)
                                    {
                                ?>
                                        <li class="item">
                                            <div class="product-img" style="width:60px;">
                                                <div class="class <?php echo strtolower($lastCharacters[$i]["class"]); ?>"></div>
                                            </div>
                                            
                                            <div class="product-info">
                                                <a href="/admin/postavy" class="product-title"><?php echo $lastCharacters[$i]["name"]; ?> <span class="label label-info pull-right">Level <?php echo $lastCharacters[$i]["level"]; ?></span></a>
                                                <span class="product-description"><?php echo $lastCharacters[$i]["race"]." (".$lastCharacters[$i]["faction"].")"; ?></span>
                                            </div>
                                        </li>
                                <?php
                                    }
                                ?>
                            </ul>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer text-center">
                            <a href="/admin/postavy" class="uppercase">Zobrazit všechny postavy</a>
                        </div>
                        <!-- /.box-footer -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
                
                <div class="col-md-6">
                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <h3 class="box-title">Naposledy zaregistrovaní</h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>

                        <!-- /.box-header -->
                        <div class="box-body">
                            <ul class="products-list product-list-in-box">
                                <?php
                                    for($i = 0; $i < count($lastAccounts); $i++)
                                    {                                        
                                ?>
                                        <li class="item">
                                            <div class="product-info" style="margin-left:0;">
                                                <a href="/admin/uzivatele" class="product-title"><?php echo ucfirst(strtolower($lastAccounts[$i]["username"])); ?> <span class="label label-success pull-right"><?php echo (new DateTime($lastAccounts[$i]["joindate"]))->format("d.m.Y"); ?></span></a>
                                                <span class="product-description"><?php echo $lastAccounts[$i]["email"]; ?></span>
                                            </div>
                                        </li>
                                <?php
                                    }
                                ?>
                            </ul>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer text-center">
                            <a href="/admin/uzivatele" class="uppercase">Zobrazit všechny uživatele</a>
                        </div>
                        <!-- /.box-footer -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>

<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="/admin/dist/js/pages/dashboard2.js"></script>
    <!-- AdminLTE for demo purposes -->

<script type="text/javascript">
    $(document).ready(function()
    {
        // Uptime chart
       // Get context with jQuery - using jQuery's .get() method.
        var uptimeChartCanvas = $("#uptimeChart").get(0).getContext("2d");
        // This will get the first returned node in the jQuery collection.
        var uptimeChart = new Chart(uptimeChartCanvas); 
        
        var areaChartData =
        {
          
          labels: [<?php echo $uptimeLabel; ?>],
          datasets:
          [
            {
              label: "Digital Goods",
              fillColor: "#ade2fe",
              strokeColor: "#40bcfd",
              pointColor: "#ade2fe",
              pointStrokeColor: "#40bcfd",
              pointHighlightFill: "#fff",
              pointHighlightStroke: "rgba(60,141,188,1)",
              data: [<?php echo $uptimeData ?>]
            }
          ]
        };

        var areaChartOptions =
        {
          //Boolean - If we should show the scale at all
          showScale: true,
          //Boolean - Whether grid lines are shown across the chart
          scaleShowGridLines: false,
          //String - Colour of the grid lines
          scaleGridLineColor: "rgba(0,0,0,.05)",
          //Number - Width of the grid lines
          scaleGridLineWidth: 2,
          //Boolean - Whether to show horizontal lines (except X axis)
          scaleShowHorizontalLines: true,
          //Boolean - Whether to show vertical lines (except Y axis)
          scaleShowVerticalLines: true,
          //Boolean - Whether the line is curved between points
          bezierCurve: true,
          //Number - Tension of the bezier curve between points
          bezierCurveTension: 0,
          //Boolean - Whether to show a dot for each point
          pointDot: false,
          //Number - Radius of each point dot in pixels
          pointDotRadius: 4,
          //Number - Pixel width of point dot stroke
          pointDotStrokeWidth: 2,
          //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
          pointHitDetectionRadius: 20,
          //Boolean - Whether to show a stroke for datasets
          datasetStroke: true,
          //Number - Pixel width of dataset stroke
          datasetStrokeWidth: 5,
          //Boolean - Whether to fill the dataset with a color
          datasetFill: true,
          //String - A legend template
          legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
          //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
          maintainAspectRatio: true,
          //Boolean - whether to make the chart responsive to window resizing
          responsive: true
        };

        //Create the line chart
        uptimeChart.Line(areaChartData, areaChartOptions);
        
        // Faction chart
        var factionChartCanvas = $("#factionChart").get(0).getContext("2d");
        var factionChart = new Chart(factionChartCanvas);
      var factionData = 
      [
          {
          value: <?php echo $faction["Alliance"]; ?>,
          color: "#3c8dbc",
          highlight: "#3c8dbc",
          label: "Aliance"
        },
        {
          value: <?php echo $faction["Horde"]; ?>,
          color: "#f56954",
          highlight: "#f56954",
          label: "Horda"
        }
      ];
    
      var pieChartOptions = {
        //Boolean - Whether we should show a stroke on each segment
        segmentShowStroke: true,
        //String - The colour of each segment stroke
        segmentStrokeColor: "#fff",
        //Number - The width of each segment stroke
        segmentStrokeWidth: 1,
        //Number - The percentage of the chart that we cut out of the middle
        percentageInnerCutout: 50, // This is 0 for Pie charts
        //Number - Amount of animation steps
        animationSteps: 100,
        //String - Animation easing effect
        animationEasing: "easeOutBounce",
        //Boolean - Whether we animate the rotation of the Doughnut
        animateRotate: true,
        //Boolean - Whether we animate scaling the Doughnut from the centre
        animateScale: false,
        //Boolean - whether to make the chart responsive to window resizing
        responsive: true,
        // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
        maintainAspectRatio: false,
        //String - A legend template
        legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>",
        //String - A tooltip template
        tooltipTemplate: "<%=label%>: <%=value %>"
      };
      //Create pie or douhnut chart
      // You can switch between pie and douhnut using the method below.
      factionChart.Doughnut(factionData, pieChartOptions);
        
        // Race chart
        var raceChartCanvas = $("#raceChart").get(0).getContext("2d");
        var raceChart = new Chart(raceChartCanvas);
      var raceData = 
      [
        {
          value: <?php echo $race["Human"]; ?>,
          color: "#f56954",
          highlight: "#f56954",
          label: "Lidé"
        },
        {
          value: <?php echo $race["Dwarf"]; ?>,
          color: "#00a65a",
          highlight: "#00a65a",
          label: "Trpaslíci"
        },
        {
          value: <?php echo $race["NightElf"]; ?>,
          color: "#f39c12",
          highlight: "#f39c12",
          label: "Noční elfové"
        },
        {
          value: <?php echo $race["Gnome"]; ?>,
          color: "#00c0ef",
          highlight: "#00c0ef",
          label: "Gnomové"
        },
        {
          value: <?php echo $race["Draenei"]; ?>,
          color: "#3c8dbc",
          highlight: "#3c8dbc",
          label: "Draeneiové"
        },
        {
          value: <?php echo $race["Worgen"]; ?>,
          color: "#d2d6de",
          highlight: "#d2d6de",
          label: "Worgeni"
        },
          {
          value: <?php echo $race["Orc"]; ?>,
          color: "#39cccc",
          highlight: "#39cccc",
          label: "Orkové"
        },
        {
          value: <?php echo $race["Undead"]; ?>,
          color: "#001f3f",
          highlight: "#001f3f",
          label: "Nemrtvý"
        },
        {
          value: <?php echo $race["Tauren"]; ?>,
          color: "#605ca8",
          highlight: "#605ca8",
          label: "Taureni"
        },
        {
          value: <?php echo $race["Troll"]; ?>,
          color: "#ff851b",
          highlight: "#ff851b",
          label: "Trollové"
        },
        {
          value: <?php echo $race["BloodElf"]; ?>,
          color: "#d81b60",
          highlight: "#d81b60",
          label: "Krvavý elfové"
        },
        {
          value: <?php echo $race["Goblin"]; ?>,
          color: "#01ff70",
          highlight: "#01ff70",
          label: "Goblini"
        }
      ];
        
      //Create pie or douhnut chart
      // You can switch between pie and douhnut using the method below.
      raceChart.Doughnut(raceData, pieChartOptions);
        
        // Class chart
        var classChartCanvas = $("#classChart").get(0).getContext("2d");
        var classChart = new Chart(classChartCanvas);
      var classData = 
      [
        {
          value: <?php echo $class["Druid"]; ?>,
          color: "#ff851b",
          highlight: "#ff851b",
          label: "Druid"
        },
        {
          value: <?php echo $class["DK"]; ?>,
          color: "#dd4b39",
          highlight: "#dd4b39",
          label: "Death Knight"
        },
        {
          value: <?php echo $class["Hunter"]; ?>,
          color: "#00a65a",
          highlight: "#00a65a",
          label: "Hunter"
        },
        {
          value: <?php echo $class["Mage"]; ?>,
          color: "#00c0ef",
          highlight: "#00c0ef",
          label: "Mage"
        },
        {
          value: <?php echo $class["Paladin"]; ?>,
          color: "#d81b60",
          highlight: "#d81b60",
          label: "Paladin"
        },
        {
          value: <?php echo $class["Priest"]; ?>,
          color: "#39cccc",
          highlight: "#39cccc",
          label: "Priest"
        },
          {
          value: <?php echo $class["Rogue"]; ?>,
          color: "#f39c12",
          highlight: "#f39c12",
          label: "Rogue"
        },
        {
          value: <?php echo $class["Shaman"]; ?>,
          color: "#3c8dbc",
          highlight: "#3c8dbc",
          label: "Shaman"
        },
        {
          value: <?php echo $class["Warlock"]; ?>,
          color: "#001f3f",
          highlight: "#001f3f",
          label: "Warlock"
        },
        {
          value: <?php echo $class["Warrior"]; ?>,
          color: "#d2d6de",
          highlight: "#d2d6de",
          label: "Warrior"
        }
      ];
        
      //Create pie or douhnut chart
      // You can switch between pie and douhnut using the method below.
      classChart.Doughnut(classData, pieChartOptions);
        
        // map
        $(function()
        {
            $(".map").each(function(index)
            {
                var width = $(this).data("original-width");
                var height = $(this).data("original-height");
                
                var points = $($(this).data("points"));
                
                $(this).css(
                {
                    width: width,
                    height: height
                });
                
                points.css(
                {
                    width: width,
                    height: height
                });
            });
            
            if(mapContainer)
                mapContainer.refreshMap();
        });
        
        function playerPosition(x, y, map, location)
        {
            x = Math.round(x);
            y = Math.round(y);
            
            var where_530 = 0;
            var xPos = 0;
            var yPos = 0;
            var pos = [];
            
            if(map == 530) // outland
            {
                if(y < -1000 && y > -10000 && x > 5000) //BE
                {
                    x = x - 10349;
                    y = y + 6357;
                    where_530 = 1;
                }
                else if(y < -7000 && x < 0) //Dr
                {
                    x = x + 3961;
                    y = y + 13931;
                    where_530 = 2;
                }
                else //Outland
                {
                    x = x - 3070;
                    y = y - 1265;
                    where_530 = 3;
                }
            }
            
            if(where_530 == 3) //Outland
            {
                xPos = Math.round(x * 0.051446);
                yPos = Math.round(y * 0.051446);
            }
            else if(map == 571) //Northrend
            {
                if(location == "grizzly")
                {
                    xPos = Math.round(x * 0.25);//0.050085);
                    yPos = Math.round(y * 0.25);//0,050085);
                }
                else if(location == "azeroth")
                {
                    xPos = Math.round(x * 0.16);
                    yPos = Math.round(y * 0.16);
                }
                else
                {
                    xPos = Math.round(x * 0.155);//0.050085);
                    yPos = Math.round(y * 0.155);//0,050085);
                }
            }
            else if(map == 1) // Kalimdor
            {
                xPos = Math.round(x * 0.14075);
                yPos = Math.round(y * 0.14075);
            }
            else if(map == 0) // EA
            {
                xPos = Math.round(x * 0.1470); // 0.025140
                yPos = Math.round(y * 0.1470);
            }
            
            switch(parseInt(map))
            {
                case 530:
                    {
                        if(where_530 == 1)
                            pos = [858 - yPos, 84 - xPos];
                        else if(where_530 == 2)
                            pos = [103 - yPos, 261 - xPos];
                        else if(where_530 == 3)
                            pos = [684 - yPos, 229 - xPos];
                    }
                    break;
                    
                case 571: // Northrend
                    {
                        if(location == "grizzly")
                            pos = [-300 - yPos, 1350 - xPos];
                        else if(location == "azeroth")
                            pos = [4085 - yPos, 1807 - xPos];
                        else
                            pos = [1345 - yPos, 1717 - xPos];//pos = [505 - yPos, 642 - xPos];
                    }
                    break;
                    
                case 0: // EA
                    pos = [5810 - yPos, 2490 - xPos];
                    break;
                    
                case 1: // Kalimdor
                default:
                    pos = [1345 - yPos, 2910 - xPos];
                    break;
            }
                
            return pos;
        }
        
        function addPlayer(player, location = null)
        {
            var position = playerPosition(player.position.x, player.position.y, player.map, location);
            
            var size = mapContainer.getSize();
            
            position[0] = position[0] / (mapTarget.data("original-width") / size.x);
            position[1] = position[1] / (mapTarget.data("original-height") / size.y);
            
            return '<img src="/admin/dist/img/' + (player.faction ? "horde.gif" : "alliance.gif") + '" style="position:absolute;left:' + position[0] + 'px;top:' + position[1] + 'px;" data-name=' + player.name + ' data-toggle="tooltip" data-placement="top" title="' + player.name + '">';
        }
        
        function showMap(init = false)
        {
            $.get("/admin/php/controllers/map.php", function(data)
            {
                if(!data)
                    return;
                
                $("#pointsAzeroth, #pointsNorthrend, #pointsGrizzlyHills").html("");
                
                for(var i = 0; i < data.players.length; i++)
                {
                    var player = data.players[i];
                    
                    // 530 == Outland
                    if(player.map == 0 || player.map == 1 || player.map == 571) // EA or Kalimdor
                        $("#pointsAzeroth").append(addPlayer(player, "azeroth"));
                    
                    if(player.map == 571)
                    {
                        $("#pointsNorthrend").append(addPlayer(player));
                        $("#pointsGrizzlyHills").append(addPlayer(player, "grizzly"));
                    }
                }
            }, "json");
            
            if(init)
            {
                setTimeout(function()
                {
                    showMap(true);
                }, 5000);
            }
        }
        
        var mapContainer = $("#mapContainer").MapViewer(
        {
            maxZoom: 750
        });
        
        var mapTarget = mapContainer.getMap();
        
        showMap(true);
        
        $(".mapBtn").on("click", function(e)
        {
            e.preventDefault();
        
            var target = $($(this).data("switch"));
            var targetPoints = $(target.data("points"));
            
            $(".map").each(function(index)
            {
                if(!$(this).hasClass("hidden"))
                    $(this).addClass("hidden");
                
                var points = $($(this).data("points"));
                
                if(!points.hasClass("hidden"))
                    points.addClass("hidden");
            });
            
            target.removeClass("hidden");
            targetPoints.removeClass("hidden");
            
            if(target.data("points") == "#pointsGrizzlyHills")
            {
                $("#mapWrapper").css("background", "none");
                mapContainer.setMaximumZoom(200);
            }
            else
            {
                $("#mapWrapper").css("background", "#011d28");
                mapContainer.setMaximumZoom(750);
            }
            
            mapTarget = target;
            mapContainer.refreshMap();
            
            showMap();
            
            $(".mapBtn").each(function(index)
            {
                if($(this).hasClass("active"))
                    $(this).removeClass("active");
            });
            
            $(this).addClass("active");
        });
    });
</script>