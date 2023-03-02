<?php

?>

<script>
function getCache(cs){
	window.location ='/refresh.php?'+cs;
	return false;
}
</script>

Run bare displays this page.<br/>
<a href='/refresh.php?all'>Run with '?all'</a> = refresh all caches on normal schedule<br>
<a href='/refresh.php?force_all'>Run with ?force_all</a> = force refresh of all caches<br/>
Select cache <select name='cache' id ='cselect' onChange='getCache(this.value)'><?=$coptions?>s</select>to force refresh that cache only.
