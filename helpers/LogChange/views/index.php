<?php
	

foreach ($logArray as $row)
{
	if (count($row)<=1)	continue;
	
	echo implode(' - ', $row);
?>
	<br />
<?php	
}
	