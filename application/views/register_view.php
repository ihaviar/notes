<?php $this->load->view('header'); ?>



<div id="auth">

<h1>registracia<h1>
	<?php 
		echo validation_errors(); // ak nastanu chyby validacie tu sa vypise error
	
		echo form_open('auth/register');
		echo form_input('meno',set_value('meno','Meno'));
		echo form_input('priezvisko',set_value('priezvisko','Priezvisko'));
		echo form_input('email',set_value('email','Emailova adresa'));
		echo form_password('heslo');
		echo form_password('heslo2');
		echo form_submit('submit','Registruj');
		echo form_close();
		
	
	?>
</div>

<?php $this->load->view('footer'); ?>