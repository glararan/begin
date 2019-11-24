<?php
	session_start();
	
	if (!isset($_SESSION['rank']) && !isset($_SESSION['user_email']))
		exit($lang['dontHaveAccess']);
		
	function isEmail($email)
	{
		if(filter_var($email, FILTER_VALIDATE_EMAIL))
			return true;
		else
			return false;
	}
		
	if(!isEmail($_SESSION['user_email']))
		exit($lang['invalidEmail']);
	
	// Připojení k databázi
	$mysqli = new mysqli($hostnameServer, $usernameServer, $passwordServer, $dbnameServer);
	$mysqli->set_charset('utf8');
	
	if (mysqli_connect_errno()) 
		die(mysqli_connect_errno());
	
	function getPublicCode()
	{
		global $mysqli;
		global $lang;
		
		if(isset($_SESSION['rank']) && $_SESSION['rank'] == "admin")
		{
			$thCheckboxDelete = "<th class='borderLeftNone width2p'></th>";
			
			echo "<form>";
		}
		
		echo "<table class='table table-bordered table-striped table-hover users-table' id='publicTable'><tr><th>Název</th><th class='width13p'>Přidáno</th><th class='width5p'>Syntax</th><th class='width20p'>Autor</th>".$thCheckboxDelete."</tr>";
		
		$query = $mysqli->stmt_init();
		
		if($query = $mysqli->prepare("SELECT id, name, date, syntax, author, public FROM sharecode"))
		{
			$query->execute();
			$query->store_result();
			$query->bind_result($pID, $name, $date, $syntax, $author, $publication);
			
			while($query->fetch())
			{
				switch($syntax)
				{
					case "bash":
						$syntax = "Bash";
						break;
						
					case "applescript":
						$syntax = "AppleScript";
						break;
					
					case "actionscript":
						$syntax = "ActionScript";
						break;
						
					case "vbscript":
						$syntax = "VBScript";
						break;
						
					case "cs":
						$syntax = "C#";
						break;
						
					case "cpp":
						$syntax = "C++";
						break;
						
					case "java":
						$syntax = "Java";
						break;
						
					case "glsl":
						$syntax = "GLSL";
						break;
						
					case "css":
						$syntax = "CSS";
						break;
						
					case "xml":
						$syntax = "HTML/XML";
						break;
						
					case "php":
						$syntax = "PHP";
						break;
						
					case "javascript":
						$syntax = "Javascript";
						break;
						
					case "sql":
						$syntax = "SQL";
						break;
						
					case "json":
						$syntax = "JSON";
						break;
				}
				
				if((time() - strtotime($date)) / 60 < 1)
					$date = "Do minuty";
				else if((time() - strtotime($date)) / 3600 < 1)
					$date = "Do hodiny";
				else
					$date = date("H:i d. m. Y", strtotime($date));
					
				if(isset($_SESSION['rank']) && $_SESSION['rank'] == "admin")
					$tdCheckboxDelete = "<td><input type='checkbox' id='deletePastes[]' value='".$pID."'></td>";
				
				if($publication == 0 || $author == $_SESSION['user_email']) // 0 = public, 1 = unlisted, 2 = private
					echo "<tr><td><a href='/pastecode/".$pID."/'>".$name."</a></td><td>".$date."</td><td>".$syntax."</td><td>".$author."</td>".$tdCheckboxDelete."</tr>";
			}
			
			$query->close();
		}
		
		echo "</table>";
		
		if(isset($_SESSION['rank']) && $_SESSION['rank'] == "admin")
		{
			echo "</form>";

			echo "<div class='text-right'><button class='btn btn-primary deleteThesePastes'>Smazat</button>";
			echo "<button class='btn btn-primary Deselect'>Odznačit vše</button></div>";
			echo '<div class="alert alert-error hide" id="alert_bar2"><strong id="alert_info2" class="alert_info"></strong><div id="alert_text2"></div></div>';
		}
	}
?>

<script>
	$(document).ready(function()
	{		
		$(function()
		{
			$("#tabs").tabs();
		});
		
		$("#tab1-click").click(function()
		{
			if(!$(this).hasClass("active"))
			{
				$(this).addClass("active");
				
				if($("#tab2-click").hasClass("active"))
					$("#tab2-click").removeClass("active");
			}
		});
		
		$("#tab2-click").click(function()
		{
			if(!$(this).hasClass("active"))
			{
				$(this).addClass("active");
				
				if($("#tab1-click").hasClass("active"))
					$("#tab1-click").removeClass("active");
			}
		});
		
		<?php
			if(empty($param[2]))
			{
		?>
		$("#alert_bar").fadeOut(0);
		
		$(".sendCode").click(function()
		{
			var pTitle = $("#pasteTitle").val();
			var syntaxL = $("#syntaxOption").val();
			var sourceCode = $("#inputcode").val();
			var publicO = $("select[id=publicationOption]").val();
			
			if(pTitle.length < 4)
			{
				alert("Délka názvu musí být minimálně 5 znaků!")
				return;
			}
			else if(pTitle.length == 0)
			{
				alert("Název vložení je prázdný!");
				return;
			}
			
			if(sourceCode.length < 10)
			{
				alert("Délka zdrojového kódu musí být větší jak 10!");
				return;
			}
			else if(sourceCode.length == 0)
			{
				alert("Zdrojový kód je prázdný!");
				return;
			}
			
			if(syntaxL == "none")
			{
				alert("Nemohli jsme pokračovat, jelikož nebyl zvolen Syntax!");
				return;
			}
			
			var serialData = {title: $("#pasteTitle").val(), syntax: $("#syntaxOption").val(), publication: $("select[id=publicationOption]").val(), code: $("#inputcode").val()};
			
			$.post("/php/pastecode.php", serialData,
			function(data)
			{
				if(data["success"] == 1)
					$("#alert_bar").html(data['success_data']);
				else
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
				}
			}, 'json');
		});
		
		$("#close_alert").click(function()
		{
			$("#alert_bar").fadeOut("slow");
		});
		<?php
			}
			else
			{
		?>
		$(function()
		{
			var firstTDContent = "";
			
			var lineHeight = 22;
			
			var lines = $(".outputcode code").html().split("\n");
			var lineCount = lines.length;
			
			for(var i = 0; i < lineCount; i++)
			{
				var lineNumber = i + 1;
				
				if(lines[i] == "")
					lines[i] = "<br>";

				firstTDContent += "<tr id='line_number_" + lineNumber + "'><td>" + lineNumber + ".</td></tr>";
			}
			
			var firstTD = "<pre><table class='widthFull' id='nums_table'><tbody>" + firstTDContent + "</tbody></table></pre>";
			
			var table = "<table><tbody><tr><td id='table-numbers'>" + firstTD + "</td><td id='table-lines'>" + $(".outputcode code").html() + "</td></tr></tbody></table>";
			
			$(".outputcode code").html(table);
			
			var code_lang = $(".outputcode code").attr("id");
			
			$(".outputcode code[id=" + code_lang + "] table tbody tr td[id=table-lines]").html(hljs.highlight(code_lang, $(".outputcode code[id=" + code_lang + "] table tbody tr td[id=table-lines]").html()).value);

			var fTDh = $(".outputcode code[id=" + code_lang + "] table tbody tr td[id=table-numbers]").height();
			fTDh /= 22;
			
			firstTDContent = "";
			
			for(var i = 0; i < fTDh; i++)
			{
				var lineNumber = i + 1;
				
				firstTDContent += "<tr id='line_number_" + lineNumber + "'><td>" + lineNumber + ".</td></tr>";
			}
			
			firstTD = "<pre><table class='widthFull' id='nums_table'><tbody>" + firstTDContent + "</tbody></table></pre>";
			table = "<table><tbody><tr><td id='table-numbers'>" + firstTD + "</td><td id='table-lines'>" + $(".outputcode code[id=" + code_lang + "] table tbody tr td[id=table-lines]").html() + "</td></tr></tbody></table>";
			$(".outputcode code").html(table);
		});
		<?php
			}
		?>
		
		<?php
			if(isset($_SESSION['rank']) && $_SESSION['rank'] == "admin")
			{
		?>
		$('#dialog_deletePastes').dialog(
		{
			autoOpen: false,
			height: 300,
			width: 350,
			modal: true,
			resizable: false,
			position: 'center',
			closeOnEsc: true,
			draggable: false,
			closeText: 'X',
			buttons:
			{
				'Smazat': function()
				{
					var checkArray = [];
					
					$("#publicTable input[type=checkbox]").each(function()
					{
						if($(this).is(":checked"))
							checkArray.push($(this).val());
					});
					
					var serialData = {ids: checkArray};
					
					$.post('/php/pastesdelete.php', serialData,
					function(data)
					{
						if(data['success'] == 1)
						{
							$("#publicTable input[type=checkbox]").each(function()
							{
								if($(this).is(":checked"))
									$(this).closest("tr").remove();
							});
						}
					}, 'json');
					
					$(this).dialog('close');
				},
				'Zpět': function()
				{
					$(this).dialog('close');
				}
			}
		});
		
		$(".deleteThesePastes").click(function()
		{
			var emptys = true;
			
			$("#publicTable input[type=checkbox]").each(function()
			{
				if($(this).is(":checked"))
					emptys = false;
			});
			
			if(emptys)
			{
				alert("Neoznačili jste žádný řádek!");
				return false;
			}
			
			$("#dialog_deletePastes").dialog("open");
			
			return false;
		});
		
		$(".Deselect").click(function()
		{
			$("#publicTable input[type=checkbox]").each(function()
			{
				$(this).attr("checked", false);
			});
		});
		<?php
			}
		?>
	});
</script>

<div class="page-header">
	<h3>Sdílení kódu</h3>
</div>

<div class="article">
	<div class="title"><h4>Přehled</h4></div>
	
	<div id="tabs">
		<ul class="nav nav-tabs">
			<?php
				$nazevTab1 = empty($param[2]) ? "Vložit kód" : "Ukázka kódu";
			?>
		
			<li id="tab1-click" class="active"><a href="#tabs-1"><?php echo $nazevTab1; ?></a></li>
			<li id="tab2-click"><a href="#tabs-2">Přehled</a></li>
		</ul>
		
		<div id="tabs-1">
			<?php
				if(empty($param[2]))
				{
			?>
			<textarea id="inputcode"></textarea>
			<br>
			<div class="pasteBotPanel">
				<input type="text" id="pasteTitle" placeholder="Název vložení...">
				<select id="syntaxOption">
					<option selected value="none">Zvolte jazyk</option>
					<option value="bash">Bash</option>
					<option value="aps">AppleScript</option>
					<option value="acs">ActionScript</option>
					<option value="vbs">VBScript</option>
					<option value="cs">C#</option>
					<option value="cpp">C++</option>
					<option value="java">Java</option>
					<option value="glsl">GLSL</option>
					<option value="css">CSS</option>
					<option value="htmlxml">HTML/XML</option>
					<option value="php">PHP</option>
					<option value="js">JavaScript</option>
					<option value="sql">SQL</option>
					<option value="json">JSON</option>
				</select>
				<select id="publicationOption">
					<option selected value="0">Veřejný</option>
					<option value="1">Skrytý</option>
					<option value="2">Privátní</option>
				</select>
			</div>
			<button class="btn btn-primary sendCode">Odeslat</button>
			
			<div class="alert" id="alert_bar">
				<a class="close" id="close_alert">×</a>
				<strong id="alert_info" class="alert_info"></strong>
				<div id="alert_text"></div>
			</div>
			<?php
				}
				else
				{
					if(is_numeric($param[2]))
					{
						$query = $mysqli->stmt_init();
						
						if($query = $mysqli->prepare("SELECT name, date, syntax, author, code, public FROM sharecode WHERE id = ?"))
						{
							$query->bind_param("i", $param[2]);
							$query->execute();
							$query->store_result();
							$query->bind_result($name, $date, $syntax, $author, $code, $publication);
							$query->fetch();
							
							if(empty($name) && empty($date) && empty($syntax) && empty($author) && empty($code) && empty($publication))
								echo "<meta http-equiv='refresh' content='0; url=/pastecode/'>";
							
							if($author == $_SESSION['user_email'])
								$authorIsTrue = true;
							else
							{
								if($publication == 2)
									echo "<meta http-equiv='refresh' content='0; url=/pastecode/'>";
							}
							
							$syntaxC = $syntax;
							
							switch($syntax)
							{
								case "bash":
									$syntax = "Bash";
									break;
									
								case "applescript":
									$syntax = "AppleScript";
									break;
								
								case "actionscript":
									$syntax = "ActionScript";
									break;
									
								case "vbscript":
									$syntax = "VBScript";
									break;
									
								case "cs":
									$syntax = "C#";
									break;
									
								case "cpp":
									$syntax = "C++";
									break;
									
								case "java":
									$syntax = "Java";
									break;
									
								case "glsl":
									$syntax = "GLSL";
									break;
									
								case "css":
									$syntax = "CSS";
									break;
									
								case "xml":
									$syntax = "HTML/XML";
									break;
									
								case "php":
									$syntax = "PHP";
									break;
									
								case "javascript":
									$syntax = "Javascript";
									break;
									
								case "sql":
									$syntax = "SQL";
									break;
									
								case "json":
									$syntax = "JSON";
									break;
							}
							
							echo "<div class='article marginBot30'><div class='title'><h4>".$name."</h4></div>Od: <b>".$author."</b> dne ".date("d. m. Y v H:i", strtotime($date))." | Jazyk: ".$syntax."</div>";
							echo '<pre class="outputcode"><code id="'.$syntaxC.'">'.$code.'</code></pre>';
							
							if($authorIsTrue)
							{
								echo "<script>
								$(document).ready(function()
								{
									$('#dialog_deletePaste').dialog(
									{
										autoOpen: false,
										height: 300,
										width: 350,
										modal: true,
										resizable: false,
										position: 'center',
										closeOnEsc: true,
										draggable: false,
										closeText: 'X',
										buttons:
										{
											'Smazat': function()
											{			
												var serialData = {id: ".$param[2]."};
												
												$.post('/php/pastedelete.php', serialData,
												function(data)
												{
													if(data['success'] == 1)
														$('#tabs-2').html(data['result']);
												}, 'json');
												
												$(this).dialog('close');
											},
											'Zpět': function()
											{
												$(this).dialog('close');
											}
										}
									});
									
									$('.deleteThisPaste').click(function()
									{
										$('#dialog_deletePaste').dialog('open');
										
										return false;
									});
								});
								</script>";
								echo "<button class='btn btn-primary deleteThisPaste'>Smazat</button>";
							}
							
							$query->close();
						}
						else
							echo '<div class="alert alert-error" id="alert_bar"><strong id="alert_info" class="alert_info">Chyba:</strong><div id="alert_text">Nepodařilo se nám z databáze vybrat kód!</div></div>';
					}
					else
						echo '<div class="alert alert-error" id="alert_bar"><strong id="alert_info" class="alert_info">Chyba:</strong><div id="alert_text">URL parametr je nesprávný!</div></div>';
				}
			?>
		</div>
		<div id="tabs-2">
			<?php getPublicCode(); ?>
		</div>
	</div>
</div>

<?php
	if($authorIsTrue)
	{
?>
<div id="dialog_deletePaste" class="dialog-form" title="Smazání kódu">
	<p>Opravdu chcete smazat tento kód?</p>
</div>
<?php
	}
	
	if(isset($_SESSION['rank']) && $_SESSION['rank'] == "admin")
	{
?>
<div id="dialog_deletePastes" class="dialog-form" title="Hromadné smazání kódu">
	<p>Opravdu chcete smazat tyto kódy?</p>
</div>
<?php
	}
?>