<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>Postavy</h1>

    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Domů</a></li>
        <li class="active">Postavy</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Herní postavy</h3>
        </div>

        <?php
            $charDB = new Database(DB_CHAR);
        
            $query = $charDB->toArray($charDB->select("SELECT c.*, a.username FROM ".DB_CHAR.".characters AS c INNER JOIN ".DB_AUTH.".account AS a ON a.id = c.account"));
        ?>
        
        <!-- /.box-header -->
        <div class="box-body">
            <table id="characterTable" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>GUID</th>
                        <th>Název postavy</th>
                        <th>Název účtu</th>
                        <th>Povolání</th>
                        <th>Rasa</th>
                        <th>Frakce</th>
                        <th>Level</th>
                        <th>Peněz</th>
                    </tr>
                </thead>
                <tbody>
                    <?php                    
                        for($i = 0; $i < count($query); $i++)
                        {
                            $name = $query[$i]['name'];
                            $class = "Warrior";
                            $race = "Human";
                            $faction = "Alliance";
                            $level = $query[$i]['level'];
                            $gender = $query[$i]['gender'] == 0 ? "" : "f";

                            switch($query[$i]["class"])
                            {
                                case 11:
                                    $class = "Druid";
                                    break;

                                case 9:
                                    $class = "Warlock";
                                    break;

                                case 8:
                                    $class = "Mage";
                                    break;

                                case 7:
                                    $class = "Shaman";
                                    break;

                                case 6:
                                    $class = "DeathKnight";
                                    break;

                                case 5:
                                    $class = "Priest";
                                    break;

                                case 4:
                                    $class = "Rogue";
                                    break;

                                case 3:
                                    $class = "Hunter";
                                    break;

                                case 2:
                                    $class = "Paladin";
                                    break;

                                case 1:
                                    $class = "Warrior";
                                    break;
                            }

                            switch($query[$i]["race"])
                            {
                                // Alliance
                                case 22:
                                    $race = "Worgen";
                                    $faction = "Alliance";
                                    break;

                                case 11:
                                    $race = "Draenei";
                                    $faction = "Alliance";
                                    break;

                                case 7:
                                    $race = "Gnome";
                                    $faction = "Alliance";
                                    break;

                                case 4:
                                    $race = "NightElf";
                                    $faction = "Alliance";
                                    break;

                                case 3:
                                    $race = "Dwarf";
                                    $faction = "Alliance";
                                    break;

                                case 1:
                                    $race = "Human";
                                    $faction = "Alliance";
                                    break;

                                // Horde
                                case 10:
                                    $race = "BloodElf";
                                    $faction = "Horde";
                                    break;

                                case 9:
                                    $race = "Goblin";
                                    $faction = "Horde";
                                    break;

                                case 8:
                                    $race = "Troll";
                                    $faction = "Horde";
                                    break;

                                case 6:
                                    $race = "Tauren";
                                    $faction = "Horde";
                                    break;

                                case 5:
                                    $race = "Undead";
                                    $faction = "Horde";
                                    break;

                                case 2:
                                    $race = "Orc";
                                    $faction = "Horde";
                                    break;
                            }

                            $class = strtolower($class);
                            $faction = strtolower($faction);
                            $race = strtolower($race);
                            
                            $money = $query[$i]["money"];
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
                            
                            echo '<tr>';
                            echo    '<td>'.$query[$i]["guid"].'</td>';
                            echo    '<td>'.$query[$i]["name"].'</td>';
                            echo    '<td>'.ucfirst(strtolower($query[$i]["username"])).'</td>';
                            echo    '<td><div class="class '.$class.'"></div></td>';
                            echo    '<td><div class="race '.$gender.$race.'"></div></td>';
                            echo    '<td class="text-center"><img src="../../images/'.$faction.'.png" width="32" height="32"></div></td>';
                            echo    '<td>'.$query[$i]["level"].'</td>';
                            echo    '<td>'.$moneyLabel.'</td>';
                            echo '</tr>';
                        }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>GUID</th>
                        <th>Název postavy</th>
                        <th>Název účtu</th>
                        <th>Povolání</th>
                        <th>Rasa</th>
                        <th>Frakce</th>
                        <th>Level</th>
                        <th>Peněz</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</section>
<!-- /.content -->

<script type="text/javascript">
    $(document).ready(function()
    {
       $('#characterTable').DataTable(); 
    });
</script>