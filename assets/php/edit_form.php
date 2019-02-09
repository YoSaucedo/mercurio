<script src="<?php echo url(); ?>/assets/ckeditor/ckeditor.js"></script>
<script src="<?php echo url(); ?>/assets/js/jquery.autoSave.min.js"></script>
<title>Editar historia</title>
<?php 
$id = $_GET['edit'];
$query = $conn->query("SELECT * FROM posts WHERE id = '$id'");
while ($post = $query->fetch()){ ?>
    <form class="write" method="POST" action="" enctype="multipart/form-data">
        <input class="post_title" id="title" type="text" name="title" placeholder="Título" value="<?php echo $post['title'] ?>">
            <?php if (empty($post['cover'])) { ?>
                <div class="user_banner">
                    <input class="file cover" id="file" type="file" name="cover" accept=".jpg, .png, .gif, .webp">
                    <label class="button avatar" for="file"><i class="im im-upload"></i></label>
                    <span class="username">Subir portada.</span>
                    <span class="username">Tamaño recomendado 780x320px.</span>
                    <span class="username">Peso máximo de archivo <?php mroMaxFileSize(); ?>MB.</span>
                </div>
            <?php } else { ?>
                <div class="user_banner" style="background-image: url(<?php echo $post['cover']; ?>);">
                    <input type="hidden" name="excover" value="<?php echo $post['cover']; ?>">
                    <input class="file cover" id="file" type="file" name="cover" accept=".jpg, .png, .gif, .webp">
                    <label class="button avatar" for="file"><i class="im im-upload"></i></label>
                    <span class="username">Subir portada.</span>
                    <span class="username">Tamaño recomendado 780x320px.</span>
                    <span class="username">Peso máximo de archivo <?php mroMaxFileSize(); ?>MB.</span>
                </div>
            <?php } ?>
        <script type="text/javascript">
            $('#file').change(function() {
                $(this).next('label').append("<p>Tu archivo ha sido seleccionado.</p>");
            })
        </script>
        <textarea id="editor" name="text_body"><?php echo $post['body']; ?></textarea>
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
                <option disabled>Sección:</option>
                <?php $section = $conn->query("SELECT * FROM sections WHERE adminonly = '0'");
                while ($option = $section->fetch()) { ?>
                    <option value="<?php echo $option['id']; ?>" <?php if ($post['section'] == $option['id']) { echo "selected"; } ?>>
                        <?php echo $option['name']; ?>
                    </option>
                <?php } ?>
            </select>
        <?php } else { ?>
            <select id="section" name="section">
                <option disabled>Sección:</option>
                <?php $section = $conn->query("SELECT * FROM sections WHERE adminonly = '0'");
                while ($option = $section->fetch()) { ?>
                    <option value="<?php echo $option['id']; ?>" <?php if ($post['section'] == $option['id']) { echo "selected"; } ?>>
                        <?php echo $option['name']; ?>
                    </option>
                <?php } ?>
            </select>
       <?php } ?>
       <button type="submit" name="post_update">Actualizar</button>
       <button type="submit" name="post_delete" class="delete">Borrar</button>
       <input type="hidden" name="post_id" id="post_id" value="<?php echo $_GET['edit']; ?>">
    </form>
<?php } ?>
<div id="autoSave"></div>
<script type="text/javascript">
$(document).ready(function(){  
	function autoSave()  { 
		var post_title = $('#title').val();  
		var post_text = $('#copyarea').val();
		var post_section = $('#section option:selected').text();
		var post_id = $('#post_id').val();
		if(post_title != '' && post_text != '')  {  
			$.ajax({  
				url:"../assets/php/autosave.php",  
				method:"POST",  
				data:{title:post_title, text_body:post_text, section:post_section, post_id:post_id},  
				dataType:"text",  
				success:function(data)  {  
					$('#autoSave').html("<img src='../assets/img/saved_draft.png'>");  
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