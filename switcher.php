<?php
	// Creates form for switching the mode 
	function switchForm($modes, $type) {
	?>
		<form name="switch">
			<!-- Hidden mode value -->
			<input type="hidden" name="mode" id="mode">
			
		<?php
			// Create a button for every mode based on logged in account type
			foreach ($modes as $mode => $value) {
				if (true) {
				?>
    				<input type="submit" <?php echo 'value="'.$value.'" onclick="switchMode(\''.$mode.'\')" style="'.((isset($_GET['mode']) and $_GET['mode'] == $mode) ? 'color:#000080; ': "").'margin-top:10px; margin-right:10px; height:25px; width:180px;"'; ?>><br>	
    			<?php
				}
    		}
    	?>
		</form>
	<?php
	}
?>

<script>
	// Switches mode value
	function switchMode(mode) {
		document.getElementById('mode').value = mode;
	}
</script>