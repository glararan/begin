<div class="reservation">
	<div class="reservation-title">
		<h2>Rezervace</h2>
	</div>
	
	
	<h3>1. Vyberte si datum pro rezervaci</h3>
	
	<div class="step-1">
		<script type="text/javascript">
			$(document).ready(function()
			{
				$(function()
				{
					$("#calendar").datepicker(
					{
						onSelect: function(date)
						{
							$("#dateInput").val(date);
						},
						minDate: 0
					});
				});
			});
		</script>
		
		<div id="calendar"></div>
	</div>
	
	<h3>2. Zvolte čas a ostatní detaily pro Vaší rezervaci</h3>
	<p>Po rezervaci Vás budeme kontaktovat, zda je Váš termín volný.</p>
	
	<div class="step-2">
		<form method="post" action="" class="resForm">
			<div class="group">
				<div class="left-box">
					<label>Datum:</label>
				</div>
				
				<div class="right-box">
					<input type="text" id="dateInput" name="dateInput" required readonly> 
				</div>
				
				<div class="clear"></div>
			</div>
			
			<div class="group">
				<div class="left-box">
					<label>Čas:</label>
				</div>
				
				<div class="right-box">
					<div class="from">
						<p>
							<span>Od:</span>
							
							<select>
								<option selected value="">Hodina</option>
								<option value="">8</option>
								<option value="">9</option>
								<option value="">10</option>
								<option value="">11</option>
								<option value="">12</option>
								<option value="">13</option>
								<option value="">14</option>
								<option value="">15</option>
								<option value="">16</option>
								<option value="">17</option>
								<option value="">18</option>
								<option value="">19</option>
								<option value="">20</option>
							</select>
							<b>:</b>
							<select>
								<option selected value="">Minut</option>
								<option value="">00</option>
								<option value="">15</option>
								<option value="">30</option>
								<option value="">45</option>
							</select>
						</p>
					</div>
					
					<div class="to">
						<p>
							<span>Do:</span>
							
							<select>
								<option selected value="">Hodina</option>
								<option value="">8</option>
								<option value="">9</option>
								<option value="">10</option>
								<option value="">11</option>
								<option value="">12</option>
								<option value="">13</option>
								<option value="">14</option>
								<option value="">15</option>
								<option value="">16</option>
								<option value="">17</option>
								<option value="">18</option>
								<option value="">19</option>
								<option value="">20</option>
							</select>
							<b>:</b>
							<select>
								<option selected value="">Minut</option>
								<option value="">00</option>
								<option value="">15</option>
								<option value="">30</option>
								<option value="">45</option>
							</select>
						</p>
					</div>
					
					<div class="clear"></div>
				</div>
				
				<div class="clear"></div>
			</div>
			
			<div class="group">
				<div class="left-box">
					<label>Jméno:</label>
				</div>
				
				<div class="right-box">
					<input type="text" required> 
				</div>
				
				<div class="clear"></div>
			</div>
			
			<div class="group">
				<div class="left-box">
					<label>Telefon:</label>
				</div>
				
				<div class="right-box">
					<input type="tel" required> 
				</div>
				
				<div class="clear"></div>
			</div>
			
			<div class="group">
				<div class="left-box">
					<label>Poznámky:</label>
				</div>
				
				<div class="right-box">
					<textarea></textarea>
				</div>
				
				<div class="clear"></div>
			</div>
			
			<div class="group">
				<div class="left-box">
				</div>
				
				<div class="right-box">
					<input type="submit" value="Odeslat rezervaci">
				</div>
			</div>
		</form>
	</div>
</div>