<?php

/**
 * @var $this \League\Plates\Template\Template
 * @var $thumb \Base\Service\ThumbnailService
 */

$this->layout('layouts/default', [
    'title' => $this->t('app', 'default_title'),
]);

?>

    <img id="profile-img" class="profile-img-card"
         src="<?= $this->image('/public/files/avatars/default.png')->crop(128, 128) ?>"/>