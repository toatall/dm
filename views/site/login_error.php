<h1>Ошибка аутентификации!</h1>

<div class="dialog dialog-error">
	<p>Во время прохождения процедуры аутентификации произошла ошибка.</p>
	<p>Логин: <?= isset($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER'] : 'отсутствует' ?></p>
</div>
<?php


