<script src="<?php echo url(); ?>/inc/ckeditor/ckeditor.js"></script>
<script src="<?php echo url(); ?>/inc/js/jquery.autoSave.min.js"></script>
<title>Editar borrador</title>
<?php 
$id = $_GET['draft'];
$query = $conn->query("SELECT * FROM drafts WHERE id = '$id'");
while ($post = $query->fetch()){ ?>
    <form class="write" method="POST" action="" enctype="multipart/form-data">
        <input class="post_title" id="title" type="text" name="title" placeholder="Título" value="<?php echo $post['title'] ?>">
        <textarea id="editor" name="text_body"><?php echo $post['body']; ?></textarea>
        <input id="copyarea" type="hidden" name="copyarea">
        <script>
            CKEDITOR.replace('text_body');
            setInterval(function() {
                var value = CKEDITOR.instances['editor'].getData()
                $('#copyarea').val(value);
            }, 1000);
        </script>
       <button type="submit" name="subpost_post">Publicar</button>
       <button type="submit" name="post_save">Guardar</button>
       <button type="submit" name="draft_delete" class="delete">Borrar</button>
       <input type="hidden" name="draft_id" id="draft_id" value="<?php echo $id; ?>">
    </form>
<?php } ?>
<div id="autoSave"></div>
<script type="text/javascript">
$(document).ready(function(){  
	function autoSave()  { 
		var post_title = $('#title').val();  
		var post_text = $('#copyarea').val();
		var draft_id = $('#draft_id').val();
		if(post_title != '' && post_text != '')  {  
			$.ajax({  
				url:"../inc/php/autosave.php",  
				method:"POST",  
				data:{title:post_title, text_body:post_text, draft_id:draft_id},  
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