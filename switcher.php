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
				// All can use account info and search
				// Admin can use manage, doctor, generate and analysis 
				// Radiologist can use upload
				if ($mode == "account" or $mode == "search" or ($type == "a" and ($mode == "manage" or $mode == "doctor" or $mode == "generate" or $mode == "analysis")) or ($type == "r" and $mode == "upload")) {
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