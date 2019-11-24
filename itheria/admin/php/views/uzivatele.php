<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>Accounts</h1>

    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Domů</a></li>
        <li class="active">Accounts</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Accounts</h3>
        </div>

        <?php
            $authDB = new Database(DB_AUTH);

            $query = $authDB->toArray($authDB->select("SELECT acc.*, banned.active as ban, rbac.permissionId as permission, rbac.granted FROM account AS acc LEFT JOIN account_banned AS banned ON banned.id = acc.id LEFT JOIN rbac_account_permissions AS rbac ON rbac.accountId = acc.id"));
        ?>

        <!-- /.box-header -->
        <div class="box-body">
            <table id="accountTable" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Název účtu</th>
                        <th>Email</th>
                        <th width="1%">GM level</th>
                        <th>IP</th>
                        <th>Poslední připojení</th>
                        <th>Online</th>
                        <th>Ban</th>
                        <?php if(User::getInstance()->getType() == UserType::Admin) echo '<th width="1%"></th>'; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php                
                        for($i = 0; $i < count($query); $i++)
                        {
                            $gmLevelLabel = "Hráč";
                            $gmLevel = 0;

                            if($query[$i]["permission"] == "192" && $query[$i]["granted"] == 1)
                            {
                                $gmLevelLabel = "Administrátor";
                                $gmLevel = 2;
                            }
                            else if($query[$i]["permission"] == "193" && $query[$i]["granted"] == 1)
                            {
                                $gmLevelLabel = "Game Master";
                                $gmLevel = 1;
                            }

                            echo '<tr class="'.($gmLevel == 2 ? "success" : "").($gmLevel == 1 ? "info" : "").($query[$i]["ban"] == 1 ? "danger" : "").'">';
                            echo    '<td>'.$query[$i]["id"].'</td>';
                            echo    '<td>'.ucfirst(strtolower($query[$i]["username"])).'</td>';
                            echo    '<td>'.$query[$i]["email"].'</td>';
                            echo    '<td>';

                            if(User::getInstance()->getType() != UserType::Admin)
                                echo $gmLevelLabel;
                            else
                            {
                            echo        '<div class="btn-group" style="width:100%;">
                                            <button style="width:100%;" type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.$gmLevelLabel.' <span class="caret"></span></button>
                                            <ul class="dropdown-menu">';
                            echo                $gmLevel != 0 ? '<li><a href="#" class="setAccountLevel" data-gmlevel="0" data-account="'.$query[$i]["id"].'">Hráč</a></li>' : '';
                            echo                $gmLevel != 1 ? '<li><a href="#" class="setAccountLevel" data-gmlevel="1" data-account="'.$query[$i]["id"].'">Game Master</a></li>' : '';
                            echo                $gmLevel != 2 ? '<li><a href="#" class="setAccountLevel" data-gmlevel="2" data-account="'.$query[$i]["id"].'">Administrator</a></li>' : '';
                            echo            '</ul>
                                        </div>';
                            }
                            echo    '</td>';
                            echo    '<td>'.$query[$i]["last_ip"].'</td>';
                            echo    '<td>'.(new DateTime($query[$i]["last_login"]))->format("d.m.Y H:i:s").'</td>';
                            echo    '<td>'.($query[$i]["online"] == 1 ? "Ano" : "Ne").'</td>';
                            echo    '<td>'.($query[$i]["ban"] == 1 ? "Ano" : "Ne").'</td>';

                            if(User::getInstance()->getType() == UserType::Admin)
                                echo '<td><button class="btn btn-danger deleteAccount" data-account="'.$query[$i]["id"].'">Smazat</button></td>';

                            echo '</tr>';
                        }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Název účtu</th>
                        <th>Email</th>
                        <th>GM level</th>
                        <th>IP</th>
                        <th>Poslední připojení</th>
                        <th>Online</th>
                        <th>Ban</th>
                        <?php if(User::getInstance()->getType() == UserType::Admin) echo '<th></th>'; ?>
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
        $('#accountTable').DataTable();
        
        <?php
            if(User::getInstance()->getType() == UserType::Admin)
            {
        ?>
        $(document).on("click", ".deleteAccount", function(e)
        {
            var id = $(this).data("account");
            
            if(confirm("Jste si jistý? Tato akce je nezvratná!"))
            {
                $.post("/admin/php/controllers/account.php", {type: 0, account: id}, function(data)
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
        
        $(document).on("click", ".setAccountLevel", function(e)
        {
            var id = $(this).data("account");
            var gmlevel = $(this).data("gmlevel");
            
            $.post("/admin/php/controllers/account.php", {type: 1, account: id, level: gmlevel}, function(data)
            {
                if(data.success == 1)
                    location.reload();
                else
                {
                    alert(data.error);
                }
            }, "json");
        });
        <?php
            }
        ?>
    });
</script>