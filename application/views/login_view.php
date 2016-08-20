<?php $this->load->view('header'); ?>



<div id="login">

<h1>login<h1>
	<?php 
		echo validation_errors(); // ak nastanu chyby validacie tu sa vypise error
	
		echo form_open('auth/login');
		echo form_input('meno',set_value('meno','Meno'));
		echo form_password('heslo');
		echo form_submit('submit','Prihlas sa');
		echo form_close();
		
	
	?>
</div>

<?php $this->load->view('footer'); ?>