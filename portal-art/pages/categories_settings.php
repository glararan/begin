<?php
	session_start();

	if(!isset($_SESSION['rank']) || $_SESSION['rank'] != "admin")
	{
?>
<div class="page-header">
	<h3>Přístup zamítnut!</h3>
</div>

<div class="article">
	<div class="title"><h4>Error</h4></div>
	
	<p>Nemáte povolený přístup k této stránce, možná ani neexistuje.</p>
</div>
<?php
	}
	else
	{
		// Připojení k databázi
		$mysqli = new mysqli($hostnameServer, $usernameServer, $passwordServer, $dbnameServer);
		$mysqli->set_charset('utf8');
		
		if (mysqli_connect_errno()) 
			die(mysqli_connect_errno());
			
		function getCategories()
		{
			global $mysqli;
			
			$query = $mysqli->stmt_init();
			
			if($query = $mysqli->prepare("SELECT id, name FROM categories"))
			{
				$query->execute();
				$query->bind_result($categoryID, $categoryName);
				
				while($query->fetch())
					echo "<tr><td>".$categoryID."</td><td>".$categoryName."</td><td class='width8p'><button class='btn btn-primary editCategory'>Upravit</button></td></tr>";
					
				$query->close();
			}
		}
?>
<script>
	$(document).ready(function()
	{
		$("#alert_bar").fadeOut(0);
		
		$("#dialog_editCategory").dialog(
		{
			autoOpen: false,
			height: 300,
			width: 350,
			modal: true,
			resizable: false,
			position: 'center',
			closeOnEsc: true,
			draggable: false,
			closeText: "X",
			buttons:
			{
				"Upravit": function()
				{
					var catID = $("input[id=categoryID_editCategory]").val();
					var catName = $("input[id=categoryName_editCategory]").val();
					
					if($.isEmptyObject(catID))
					{
						alert("ID kategorie je prázdné!");
						return;
					}
					
					if($.isEmptyObject(catName))
					{
						alert("Název kategorie je prázdný!");
						return;
					}
					
					var serialData = {category: catName, categoryID: catID, type: 2};
					
					$.post("/php/categories.php", serialData,
					function(data)
					{
						if($("#alert_bar").hasClass("alert-error"))
							$("#alert_bar").removeClass("alert-error");
							
						if($("#alert_bar").hasClass("alert-sucess"))
							$("#alert_bar").removeClass("alert-sucess");
										
						$("#alert_bar").addClass(data["bar-color"]);
						
						$("#alert_info").empty();
						$("#alert_info").append(data["alert-info"]);
						
						$("#alert_text").empty();
						$("#alert_text").append(data["alert-text"]);
						
						$("#alert_bar").fadeIn("slow");
						
						if(data["categoryTableEdit_status"] == 0)	
						{						
							$('#categoryTable tbody tr').each(function()
							{
								var columnValue = $(this).find('td:eq(0)').html();
								
								if(columnValue == catID)
								{
									var $row = $(this).parents('tr');
									
									$(this).find('td:eq(1)').html(catName);
									
									return false;
								}
							});
						}
					}, 'json');
					
					$(this).dialog("close");
				},
				"Zpět": function()
				{
					$(this).dialog("close");
				}
			}
		});
		
		$("#dialog_addCategory").dialog(
		{
			autoOpen: false,
			height: 300,
			width: 350,
			modal: true,
			resizable: false,
			position: 'center',
			closeOnEsc: true,
			draggable: false,
			closeText: "X",
			buttons:
			{
				"Přidat": function()
				{
					var catName = $("input[id=categoryName_addCategory]").val();
					
					if(catName == "" || $.isEmptyObject(catName))
					{
						alert("Název kategorie je prázdný!");
						return;
					}
					
					var serialData = {category: catName, type: 0};
					
					$.post("/php/categories.php", serialData,
					function(data)
					{				
						if($("#alert_bar").hasClass("alert-error"))
							$("#alert_bar").removeClass("alert-error");
							
						if($("#alert_bar").hasClass("alert-sucess"))
							$("#alert_bar").removeClass("alert-sucess");
										
						$("#alert_bar").addClass(data["bar-color"]);
						
						$("#alert_info").empty();
						$("#alert_info").append(data["alert-info"]);
						
						$("#alert_text").empty();
						$("#alert_text").append(data["alert-text"]);
						
						$("#alert_bar").fadeIn("slow");
						
						if(data["categoryTableAdd_status"] == 0)
						{
							$("#categoryTable tbody").append("<tr><td>" + data["categoryTableID"] + "</td><td>" + catName + "</td><td class='width8p'><button class='btn btn-primary editCategory'>Upravit</button></td></tr>");
							
							loadEditButtons();
						}
					}, 'json');
					
					$(this).dialog("close");
				},
				"Zpět": function()
				{
					$(this).dialog("close");
				}
			}
		});
		
		$("#dialog_deleteCategory").dialog(
		{
			autoOpen: false,
			height: 300,
			width: 350,
			modal: true,
			resizable: false,
			position: 'center',
			closeOnEsc: true,
			draggable: false,
			closeText: "X",
			buttons:
			{
				"Odebrat": function()
				{
					var catID = $("input[id=categoryID_deleteCategory]").val();
					
					if($.isEmptyObject(catID))
					{
						alert("ID kategorie je prázdné!");
						return;
					}
					
					var serialData = {category: catID, type: 1};
					
					$.post("/php/categories.php", serialData,
					function(data)
					{
						if($("#alert_bar").hasClass("alert-error"))
							$("#alert_bar").removeClass("alert-error");
							
						if($("#alert_bar").hasClass("alert-sucess"))
							$("#alert_bar").removeClass("alert-sucess");
										
						$("#alert_bar").addClass(data["bar-color"]);
						
						$("#alert_info").empty();
						$("#alert_info").append(data["alert-info"]);
						
						$("#alert_text").empty();
						$("#alert_text").append(data["alert-text"]);
						
						$("#alert_bar").fadeIn("slow");
						
						if(data["categoryTableDelete_status"] == 0)	
						{
							$('#categoryTable tbody tr').each(function()
							{
								var columnValue = $(this).find('td:eq(0)').html();
								
								if(columnValue == catID)
								{
									$(this).closest('tr').remove();
									
									return false;
								}
							});
						}
					}, 'json');
					
					$(this).dialog("close");
				},
				"Zpět": function()
				{
					$(this).dialog("close");
				}
			}
		});
		
		$(".editCategory").click(function()
		{
			editCategory($(this));
		});
		
		$(".addCategory").click(function()
		{
			$('#categoryName_addCategory').val("");
	
			$("#dialog_addCategory").dialog("open");
			
			return false;
		});
		
		$(".deleteCategory").click(function()
		{
			$('#categoryID_deleteCategory').val("");
	
			$("#dialog_deleteCategory").dialog("open");
			
			return false;
		});
		
		$("#close_alert").click(function()
		{
			$("#alert_bar").fadeOut("slow");
		});
	});
	
	function editCategory(parent)
	{
		var $row = $(parent).parents('tr');
		var $table = $(parent).closest('table');
		
		$('#categoryName_editCategory').val($row.find('td:eq(1)').html());
		$('#categoryID_editCategory').val($row.find('td:eq(0)').html());
	
		$("#dialog_editCategory").dialog("open");
		
		return false;
	}
	
	function loadEditButtons()
	{
		$("#categoryTable > tbody > tr").each(function()
		{
			$(this).delegate(".editCategory", "click", function()
			{
				editCategory($(this));
			});
		});
	}
</script>

<div class="page-header">
	<h3>Administrace</h3>
</div>

<div class="article">
	<div class="title"><h4>Kategorie pro zařízení</h4></div>

	<table class="table table-bordered table-striped table-hover users-table" id="categoryTable">
		<tr>
			<th class="width2p">ID</th>
			<th>Název kategorie</th>
			<th class="borderLeftNone"></th>
		</tr>
		
		<?php getCategories(); ?>
	</table>

	<div class="controls text-right">
		<button class="btn btn-primary addCategory">Přidat</button>
		<button class="btn btn-primary deleteCategory">Odebrat</button>
	</div>
</div>

<div id="dialog_editCategory" class="dialog-form" title="Upravení kategorie">
	<form class="text-center">
		<fieldset>
		  <label for="categoryName_editCategory">Název kategorie</label>
		  <input type="text" name="categoryName_editCategory" id="categoryName_editCategory">
		  <input type="text" name="categoryID_editCategory" id="categoryID_editCategory" class="hide">
		</fieldset>
	</form>
</div>

<div id="dialog_addCategory" class="dialog-form" title="Přidání kategorie">
	<form class="text-center">
		<fieldset>
		  <label for="categoryName_addCategory">Název kategorie</label>
		  <input type="text" name="categoryName_addCategory" id="categoryName_addCategory" placeholder="Tablety...">
		</fieldset>
	</form>
</div>

<div id="dialog_deleteCategory" class="dialog-form" title="Odebrání kategorie">
	<form class="text-center">
		<fieldset>
		  <label for="categoryID_deleteCategory">ID kategorie</label>
		  <input type="text" name="categoryID_deleteCategory" id="categoryID_deleteCategory" placeholder="123...">
		</fieldset>
	</form>
</div>

<div class="alert" id="alert_bar">
	<a class="close" id="close_alert">×</a>
	<strong id="alert_info" class="alert_info"></strong>
	<div id="alert_text"></div>
</div>
<?php
	}
?>