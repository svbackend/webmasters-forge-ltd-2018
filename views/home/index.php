<?php

/**
 * @var $this \League\Plates\Template\Template
 * @var $thumb \Base\Service\ThumbnailService
 */

$this->layout('layouts/default', [
    'title' => 'Login & Registration test app',
]);

?>

    <img id="profile-img" class="profile-img-card"
         src="<?= $this->image('/files/avatars/default.png')->crop(128, 128) ?>"/>