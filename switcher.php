<?php
	// Creates form for switching the mode 
	function switchForm($modes, $type) {
	?>
		<form name="switch">
			<input type="hidden" name="mode" id="mode">
			
		<?php
			foreach ($modes as $mode => $value) {
				if (true) {
				?>
    				<input type="submit" <?php echo 'value="'.$value.'" onclick="switchMode(\''.$mode.'\')"'.((isset($_GET['mode']) and $_GET['mode'] == $mode) ? ' style="color:#000080;"': "").''; ?>>	
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