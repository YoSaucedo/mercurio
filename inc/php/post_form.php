<script src="<?php echo url(); ?>/inc/ckeditor/ckeditor.js"></script>
<script src="<?php echo url(); ?>/inc/js/jquery.autoSave.min.js"></script>
<title>Escribir nueva historia</title>
<form class="write" method="POST" action="" enctype="multipart/form-data">
	<input class="post_title" id="title" type="text" name="title" placeholder="Título">
	<div class="user_banner">
        <input class="file" id="file" type="file" name="cover" accept=".jpg, .png, .gif, .webp">
        <label class="button avatar" for="file"><i class="im im-upload"></i></label>
        <span class="username">Subir portada.</span>
        <span class="username">Tamaño recomendado 780x320px.</span>
        <span class="username">Peso máximo de archivo <?php mroMaxFileSize(); ?>MB.</span>
    </div>
	<script type="text/javascript">
        $('#file').change(function() {
            $(this).next('label').append("<p>Tu archivo ha sido seleccionado.</p>");
        })
    </script>
    <textarea id="editor" name="text_body"></textarea>
	<input id="copyarea" type="hidden" name="copyarea">
	<script>
        CKEDITOR.replace('text_body');
        setInterval(function() {
		    var value = CKEDITOR.instances['editor'].getData()
		    $('#copyarea').val(value);
		}, 1000);
    </script>
    <?php if (uAdmin()) { ?>
    	<select id="section" name="section">
    		<option selected disabled>Sección:</option>
    		<?php $section = $conn->query("SELECT * FROM sections");
    		while ($option = $section->fetch()) { ?>
    			<option value="<?php echo $option['id'] ?>" <?php if (isset($_GET['section'])&& $_GET['section'] == $option['name']) { echo "selected"; } ?>>
    				<?php echo $option['name']; ?>
    			</option>
    		<?php } ?>
    	</select>
    <?php } else { ?>
    	<select id="section" name="section">
    		<option selected disabled>Sección:</option>
    		<?php $section = $conn->query("SELECT * FROM sections WHERE adminonly = '0'");
    		while ($option = $section->fetch()) { ?>
    			<option value="<?php echo $option['id'] ?>" <?php if (isset($_GET['section']) && $_GET['section'] == $option['name']) { echo "selected"; } ?>>
    				<?php echo $option['name']; ?>
    			</option>
    		<?php } ?>
    	</select>
   <?php } ?>
   <div class="import">
        <p>Puedes importar tu texto desde un documento de word (.docx) <a href="#" id="importcancel">Cancelar</a>.</p>
        <input type="file" name="import" accept=".docx">
        <button type="submit" name="post_import">Importar</button>
        <p>Si tu texto es muy largo es mejor que lo separes en capítulos.</p>
   </div>
   <button type="submit" name="post_publish">Publicar</button>
   <button type="submit" name="post_save">Guardar</button>
   <a href="#import" id="import">Importar</a>
   <input type="hidden" name="draft_id" id="draft_id" value="<?php echo $_SESSION['username'].uniqid(); ?>">
</form>
<div id="autoSave"></div>
<script type="text/javascript">
$('.import').hide();
$('#import').click(function(){
    $('.import').show();
});
$('#importcancel').click(function(){
    $('.import').hide();
});
$(document).ready(function(){ 
	function autoSave()  { 
		var post_title = $('#title').val();  
		var post_text = $('#copyarea').val();
		var post_section = $('#section option:selected').text();
		var draft_id = $('#draft_id').val();
		if(post_title != '' && post_text != '')  {  
			$.ajax({  
				url:"../inc/php/autosave.php",  
				method:"POST",  
				data:{title:post_title, text_body:post_text, section:post_section, draft_id:draft_id},  
				dataType:"text",  
				success:function(data)  {  
					$('#autoSave').html("<img src='../inc/img/saved_draft.png'>");  
					setTimeout(function(){  
						$('#autoSave').html('');  
					}, 1000);  
				}  
			});  
		}            
	}
	setInterval(function(){   
		autoSave();   
	}, 25000);  
});
</script>