<?php
    $auth = @fsockopen(SERVER_HOST, SERVER_PORT_AUTH);
    $world = @fsockopen(SERVER_HOST, SERVER_PORT_WORLD);
    
    $charDB = new Database(DB_CHAR);
    $authDB = new Database(DB_AUTH);
    $webDB  = new Database(DB_WEB);

    $playersOnline = count($charDB->toArray($charDB->select("SELECT guid FROM characters WHERE online = 1")));
    $uptime = $authDB->toArray($authDB->select("SELECT * FROM uptime ORDER by starttime DESC LIMIT 1"))[0]["uptime"];
    $lastUpdate = $webDB->toArray($webDB->select("SELECT date FROM changelog ORDER by id DESC LIMIT 1"));

    if($uptime > 86400 * 2)
        $uptime = round($uptime / 24 / 60 / 60)." dní";
    else if($uptime > 86400)
        $uptime = round($uptime / 24 / 60 / 60)." den";
    else if($uptime > 3600 * 2)
        $uptime = round($uptime / 60 / 60)." hodin";
    else if($uptime > 3600)
        $uptime = round($uptime / 60 / 60)." hodina";
    else if($uptime > 60 * 2)
        $uptime = round($uptime / 60)." minut";
    else
        $uptime = round($uptime / 60)." minuta";

    if(count($lastUpdate) < 1)
        $lastUpdate = "-";
    else
        $lastUpdate = (new DateTime($lastUpdate[0]["date"]))->format("d.m.Y");
?>

<div class="col-md-3" id="rightPanel">
    <?php
        if(!User::isLogged())
        {
    ?>
    <a href="./jaksepripojit#registrace"><button id="registerNow" class="btn btn-info">Registrovat účet</button></a>

    <div class="brownPanel panel panel-default col-sm-6 col-md-12">
        <div class="panel-heading">
            <h3 class="panel-title">Přihlásit se</h3>
        </div>

        <div class="panel-body">
            <form method="post" action="" data-toggle="validator" role="form" id="loginForm">
                <div class="form-group">
                    <input type="text" name="account" class="form-control" placeholder="Account Name" required>
                </div>
                
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="*******" required>
                </div>
                
                <div class="form-group">
                    <input type="submit" class="form-control"value="Přihlásit se">
                </div>
            </form>
        </div>
    </div>
    <?php
        }
        else
        {
    ?>
    <!-- <a href="./logout"><button id="registerNow" class="btn btn-info">Odhlásit se</button></a> -->
    
    <div class="brownPanel panel panel-default col-sm-6 col-md-12">
        <div class="panel-heading">
            <h3 class="panel-title">Vítejte <b><?php echo User::getInstance()->getProfileName(); ?></b></h3>
        </div>

        <div class="panel-body">
            <a href="./profil#zmenitheslo" class="btn brownBtn btn-block">Změnit heslo</a>
            <a href="./logout" class="btn brownBtn btn-block">Odhlásit se</a>
        </div>
    </div>
    <?php
        }
    ?>
    
    <div class="brownPanel panel panel-default col-sm-offset-1 col-sm-5 col-md-12 col-md-offset-0" id="infoPanel">
        <div class="panel-heading">
            <h3 class="panel-title">Informace</h3>
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 serverStatus">
                    <div class="col-xs-10">
                        <img src="images/cata.png">Login
                    </div>
                    
                    <div class="col-xs-2">
                        <div class="status-icon <?php echo $auth ? 'up' : ''; ?>"></div>
                    </div>
                </div>
                
                <div class="col-xs-12 serverStatus">
                    <div class="col-xs-10">
                        <img src="images/cata.png">World
                    </div>
                    
                    <div class="col-xs-2">
                        <div class="status-icon <?php echo $world ? 'up' : ''; ?>"></div>
                    </div>
                </div>
                
                <hr class="col-xs-12">
                
                <div class="col-xs-12">
                    <div class="col-xs-10">Celkem online hráčů:</div>
                    <div class="col-xs-2 text-right"><b><?php echo $playersOnline; ?></b></div>
                </div>
                
                <div class="col-xs-12">
                    <div class="col-xs-8">Uptime:</div>
                    <div class="col-xs-4 text-right"><b><?php echo $uptime; ?></b></div>
                </div>
                
                <div class="col-xs-12">
                    <div class="col-xs-8">Poslední update:</div>
                    <div class="col-xs-4 text-right"><b><?php echo $lastUpdate; ?></b></div>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="col-md-12">
        <img src="images/bear2attack_interpolated.png" id="bear">
    </div>-->
</div>