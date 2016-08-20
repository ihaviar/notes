<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Notes</title>
	<link rel="stylesheet" href="<?=base_url() ?>assets/style.css">
	 <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
     



<?php echo $map['js']; ?>
	
</head>

<body>


<!--<input type="text" name"place" id="myPlaceTextBox" />-->



<div id="container">

<div id="topbar">
<?php echo $logged; ?> ste prihlaseny/a.
	<div id="logout"> <?php echo form_open('auth/logout');
							echo form_submit('submit','Logout');
							echo form_close();
						?>

	</div>
</div>

<?php echo $map['html']; ?>

<div id="panel">
	<div id="vodic">
		<h1 class="napis">Vodič</h1>
			<?php echo validation_errors();?> 
			<?php echo form_open('notes/addVodic');?>
			Aktuálna pozícia:<br><?php echo form_input('input_pozicia','','id="myPlaceTextBox2"');?></br>
			<div id="radius">
			Rádius:<br><?php echo form_input('input_radius','','');?></br>
			Farba rádiusu:<input type = "color" name="input_color" id="color"/>
			</div>
			Meno:<br><?php echo form_input('input_meno','','id="myform"');?></br>
			Priezvisko:<br><?php echo form_input('input_priezvisko','');?></br>
			Tel.Číslo:<br><?php echo form_input('input_telcislo','');?></br>
			Počet miest:<br><?php echo form_input('input_pmiest','');?></br>
			ŠPZ:<br><?php echo form_input('input_spz','');?></br>
			<br><?php echo form_submit('submit','Pridaj vodica');?></br>
			<?php echo form_close();?>
			
			<?php echo form_open('notes/delVodic');?>
			<div id="mselect"><br><?php echo form_multiselect('input_mselect[]',$mselectV,'id="students-out"');?></br>
			<?php echo form_submit('submit','Vymaz vodica');?>
			<?php echo form_close();?>
			
			</div>
			
			
			
			
	</div>
	
	<div id="cestujuci">
		<h1 class="napis">Cestujúci</h1>
			<?php echo validation_errors();?> 
			<?php echo form_open('notes/addCestujuci');?>
			Aktuálna pozícia:<br><?php echo form_input('input_pozicia','','id="myPlaceTextBox"');?></br>
			Meno:<br><?php echo form_input('input_meno','','id="myform"');?></br>
			Priezvisko:<br><?php echo form_input('input_priezvisko','');?></br>
			Tel.Číslo:<br><?php echo form_input('input_telcislo','');?></br>
			Poplatok:<br><?php echo form_input('input_poplatok','');?></br>
			<br><?php echo form_submit('submit','Pridaj cestujuceho');?></br>
			<?php echo form_close();?>
			
			
			<?php echo form_open('notes/delCestujuci');?>
			<div id="mselect"><br><?php echo form_multiselect('input_mselect[]',$mselectC);?></br>
			<?php echo form_submit('submit','Vymaz cestujuceho');?>
			<?php echo form_close();?>
	</div>
	
	<div id="Navigacia">
		<h1 class="napis">Navigácia</h1>
			<?php echo validation_errors();?> 
			<?php echo form_open('notes/naviguj');?>
			Štart:<br><?php echo form_dropdown('input_start',$mselectV);?></br>
			<div class="table-responsive">  
                               <table class="table table-bordered" id="dynamic_field">  
                                    <tr>  
                                         <td><input type="text" name="waypoint[]" placeholder="Pozicia cestujuceho" class="form-control name_list" /></td>  
                                         <td><button type="button" name="add" id="add" class="btn btn-success">Add More</button></td>  
                                    </tr>  
                               </table>  
			</div>  
			Cieľ:<br><?php echo form_input('input_ciel','','id="myform"');?></br>
			Zapni/Vypni navigaciu:<input type="checkbox" name="navON" value=1 checked="checked">
			<br><?php echo form_submit('submit','Naviguj');?></br>
			<?php echo form_close();?>
	</div>
</div>

	
</div>

<div id="directionsDiv"></div> <!--instrukcie navigacie-->

</body>
		<script>  
 $(document).ready(function(){  
      var i=1;  
      $('#add').click(function(){  
           i++;
			if(i<=5){
			$('#dynamic_field').append('<tr id="row'+i+'"><td><input type="text" name="waypoint[]" placeholder="Pozicia cestujuceho" class="form-control name_list" /></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');}
      });  
      $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();  
      });  
      $('#submit').click(function(){            
           $.ajax({  
                url:"name.php",  
                method:"POST",  
                data:$('#add_name').serialize(),  
                success:function(data)  
                {  
                     alert(data);  
                     $('#add_name')[0].reset();  
                }  
           });  
      });  
 });  
 </script>  
	<script src="<?php echo base_url('assets/js/autocomplete.js'); ?>"></script>
</html>