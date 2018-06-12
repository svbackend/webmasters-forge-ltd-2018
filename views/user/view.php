<?php

/**
 * @var $this \League\Plates\Engine
 * @var $user array
 */
$title = $this->e($user['login']);
$this->layout('layouts/default', [
    'title' => $title
]);
?>

    <div class="container">

        <div class="center-block">

            <div class="card card-container card-login">
                <img id="profile-img" class="profile-img-card" src="<?= $this->image("/public/files/avatars/{$user['id']}.jpg")->crop(96, 96) ?>" />

                <div class="text-center">
                    <?= $this->e($user['first_name']) ?>
                    "<?= $this->e($user['login']) ?>"
                    <?= $this->e($user['last_name']) ?>
                </div>

                <hr>

                <?php if (!empty($user['information'])): ?>
                    <?= $this->e($user['information']) ?>
                    <hr>
                <?php endif ?>

                <a href="/logout" class="btn btn-danger">
                    <?= $this->t('app', 'Logout') ?>
                </a>
            </div><!-- /card-container -->

        </div>

    </div><!-- /container -->