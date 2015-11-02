<input type="hidden" name="id" value=<?php htmlout($id);?>>
<input type="submit" class="button" value=<?php htmlout($button);?>>
<input type="button" class="button" value="Назад" onClick=<?php echo 'location.replace("http://'.$_SERVER["HTTP_HOST"].'/'.$_SERVER["PHP_SELF"].'");'?>>
