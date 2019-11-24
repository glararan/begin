<?php
    $auth = fsockopen(SERVER_HOST, SERVER_PORT_AUTH);
    $world = fsockopen(SERVER_HOST, SERVER_PORT_WORLD);

    $charDB = new Database(DB_CHAR);

    $query2 = $charDB->toArray($charDB->select("SELECT * FROM characters WHERE account <> 0")); // race and class statistic
    
    // race and class statistic
    $race = array("Human" => 0, "Dwarf" => 0, "NightElf" => 0, "Gnome" => 0, "Draenei" => 0, "Worgen" => 0, "Orc" => 0, "Undead" => 0, "Tauren" => 0, "Troll" => 0, "BloodElf" => 0, "Goblin" => 0);
    $class = array("Druid" => 0, "Warlock" => 0, "Mage" => 0, "Shaman" => 0, "DK" => 0, "Priest" => 0, "Rogue" => 0, "Hunter" => 0, "Paladin" => 0, "Warrior" => 0);
    $faction = array("Alliance" => 0, "Horde" => 0);

    for($i = 0; $i < count($query2); $i++)
    {
        switch($query2[$i]["class"])
        {
            case 11: // Druid
                $class["Druid"]++;

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

        switch($query2[$i]["race"])
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
?>

<table class="brownTable table">
    <tr>
        <th>Postava</th>
        <th>Povolání</th>
        <th>Rasa</th>
        <th>Frakce</th>
        <th>Level</th>
    </tr>
    
    <?php
        $query = $charDB->toArray($charDB->select("SELECT * FROM characters WHERE online = 1 ORDER by name"));
    
        for($i = 0; $i < count($query); $i++)
        {
            $name = $query[$i]['name'];
            $qClass = "Warrior";
            $qRace = "Human";
            $qFaction = "Alliance";
            $level = $query[$i]['level'];
            $gender = $query[$i]['gender'] == 0 ? "" : "f";
            
            switch($query[$i]["class"])
            {
                case 11:
                    $qClass = "Druid";
                    break;
                    
                case 9:
                    $qClass = "Warlock";
                    break;
                    
                case 8:
                    $qClass = "Mage";
                    break;
                    
                case 7:
                    $qClass = "Shaman";
                    break;
                    
                case 6:
                    $qClass = "DeathKnight";
                    break;
                    
                case 5:
                    $qClass = "Priest";
                    break;
                    
                case 4:
                    $qClass = "Rogue";
                    break;
                    
                case 3:
                    $qClass = "Hunter";
                    break;
                
                case 2:
                    $qClass = "Paladin";
                    break;
                    
                case 1:
                    $qClass = "Warrior";
                    break;
            }
            
            switch($query[$i]["race"])
            {
                // Alliance
                case 22:
                    $qRace = "Worgen";
                    $qFaction = "Alliance";
                    break;
                    
                case 11:
                    $qRace = "Draenei";
                    $qFaction = "Alliance";
                    break;
                    
                case 7:
                    $qRace = "Gnome";
                    $qFaction = "Alliance";
                    break;
                    
                case 4:
                    $qRace = "NightElf";
                    $qFaction = "Alliance";
                    break;
                    
                case 3:
                    $qRace = "Dwarf";
                    $qFaction = "Alliance";
                    break;
                    
                case 1:
                    $qRace = "Human";
                    $qFaction = "Alliance";
                    break;
                    
                // Horde
                case 10:
                    $qRace = "BloodElf";
                    $qFaction = "Horde";
                    break;
                    
                case 9:
                    $qRace = "Goblin";
                    $qFaction = "Horde";
                    break;
                    
                case 8:
                    $qRace = "Troll";
                    $qFaction = "Horde";
                    break;
                    
                case 6:
                    $qRace = "Tauren";
                    $qFaction = "Horde";
                    break;
                    
                case 5:
                    $qRace = "Undead";
                    $qFaction = "Horde";
                    break;
                    
                case 2:
                    $qRace = "Orc";
                    $qFaction = "Horde";
                    break;
            }
            
            $qClass = strtolower($qClass);
            $qFaction = strtolower($qFaction);
            $qRace = strtolower($qRace);
            
            echo "<tr>";
            echo    "<td>$name</td>";
            echo    "<td><div class='class $qClass'></div></td>";
            echo    "<td><div class='race $gender$qRace'></div></td>";
            echo    "<td><img src='/images/$qFaction.png' width='32' height='32'></td>";
            echo    "<td>$level</td>";
            echo "</tr>";
        }
    ?>
</table>

<div class="col-md-12">
    <div class="col-md-6 member">
        <div class="brownPanel panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Statistika herních ras</h3>
            </div>

            <div class="panel-body">
                <div class="col-md-8">
                    <div class="chart-responsive">
                        <canvas id="raceChart" height="250"></canvas>
                    </div>
                </div>

                <div class="col-md-4">
                    <ul class="chart-legend clearfix">
                        <li><i class="fa fa-circle-o text-red"></i> Lidé</li>
                        <li><i class="fa fa-circle-o text-green"></i> Traslíci</li>
                        <li><i class="fa fa-circle-o text-yellow"></i> Noční elfové</li>
                        <li><i class="fa fa-circle-o text-aqua"></i> Gnomové</li>
                        <li><i class="fa fa-circle-o text-light-blue"></i> Draeneiové</li>
                        <li><i class="fa fa-circle-o text-gray"></i> Worgeni</li>
                        <li><i class="fa fa-circle-o text-teal"></i> Orkové</li>
                        <li><i class="fa fa-circle-o text-purple"></i> Taureni</li>
                        <li><i class="fa fa-circle-o text-orange"></i> Trollové</li>
                        <li><i class="fa fa-circle-o text-maroon"></i> Krvavý elfové</li>
                        <li><i class="fa fa-circle-o text-lime"></i> Goblini</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 member">
        <div class="brownPanel panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Statistika herních povolání</h3>
            </div>

            <div class="panel-body">
                <div class="col-md-8">
                    <div class="chart-responsive">
                        <canvas id="classChart" height="250"></canvas>
                    </div>
                </div>

                <div class="col-md-4">
                    <ul class="chart-legend clearfix">
                        <li><i class="fa fa-circle-o text-orange"></i> Druid</li>
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
            </div>
        </div>
    </div>
</div>

<?php
    View::show("footer");
?>

<script type="text/javascript">
    $(document).ready(function()
    {
        var pieChartOptions = {
        //Boolean - Whether we should show a stroke on each segment
        segmentShowStroke: true,
        //String - The colour of each segment stroke
        segmentStrokeColor: "#501A00",
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
    });
</script>