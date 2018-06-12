<?php

/**
 * @var $this \League\Plates\Template\Template
 */

$this->layout('layouts/default', [
    'title' => $this->t('app', 'Login & Registration test app'),
]);

?>

    <div class="container row">

        <div class="col-4">

            <div class="card card-container card-login">
                <img id="profile-img" class="profile-img-card" src="<?= $this->image('/public/files/avatars/default.png')->crop(128, 128) ?>"/>
                <?php $loginErrors = $errors['login-form'] ?? []; ?>
                <form action="/login" class="form signin" method="post">
                    <input type="hidden" name="form-name" value="login">

                    <div class="form-group">
                        <label for="loginInputEmail"><?= $this->t('app', 'Login') ?></label>
                        <input type="text" name="login" class="form-control" id="loginInputEmail"
                               value="<?= isset($user['login']) ? $this->e($user['login']) : '' ?>"
                               placeholder="<?= $this->t('app', 'Login placeholder') ?>" required aria-required="true">

                        <small class="help-block">
                            <?php if (isset($loginError)): ?>
                                <div class="validationErrorMessage">
                                    <?= $this->t('validation', 'User not exists') ?>
                                </div>
                            <?php endif ?>
                            <?= $this->t('app', 'Login hint') ?>
                        </small>
                    </div>

                    <label for="loginInputPassword">
                        <?= $this->t('app', 'Password') ?>
                    </label>
                    <div class="input-group">
                        <input type="password" name="password" class="form-control" id="loginInputPassword"
                               placeholder="<?= $this->t('app', 'Password') ?>" required aria-required="true">
                        <span class="input-group-addon toggle-password" id="login-toggle-password"
                              title="<?= $this->t('app', 'Show password') ?>">
                            <i class="glyphicon glyphicon-eye-open"></i>
                        </span>
                    </div>

                    <small class="help-block">
                        <?php if (isset($passwordError)): ?>
                            <div class="validationErrorMessage">
                                <?= $this->t('validation', 'Invalid password') ?>
                            </div>
                        <?php endif ?>
                    </small>

                    <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit" name="login">
                        <?= $this->t('app', 'Sign in') ?>
                    </button>
                </form><!-- /form -->
            </div><!-- /card-container -->

        </div>

        <div class="col-8">

            <div class="card card-container card-registration">

                <div class="card-title">
                    <?= $this->t('app', 'Registration') ?>

                    <div class="pull-right">
                        <div class="btn-group">
                            <a class="btn btn-primary" href="/lang/ru">
                                <?= $this->t('app', 'Russian')?>
                            </a>
                            <a class="btn btn-success" href="/lang/en">
                                <?= $this->t('app', 'English')?>
                            </a>
                        </div>
                    </div>

                </div>
                <div class="clearfix"></div>

                <?php $regErrors = $errors['registration-form'] ?? []; ?>
                <form action="/registration" method="post" class="form signup" id="registrationForm" enctype="multipart/form-data">
                    <input type="hidden" name="form-name" value="registration">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="inputFirstName" class="required">
                                <?= $this->t('app', 'First Name') ?>
                            </label>
                            <input type="text" id="inputFirstName" class="form-control" required
                                   value="<?= isset($model->first_name) ? $this->e($model->first_name) : '' ?>"
                                   name="first_name"
                                   placeholder="<?= $this->t('app', 'Jane') ?>">

                            <?php if (isset($regErrors['first_name']) && $error = $regErrors['first_name'][0]): ?>
                                <div id="errorsinputFirstName">
                                    <div class="validationErrorMessage">
                                        <?= $error ?>
                                    </div>
                                </div>
                            <?php endif ?>

                        </div>
                        <div class="col-md-6">
                            <label for="inputLastName" class="required">
                                <?= $this->t('app', 'Last Name') ?>
                            </label>
                            <input type="text" id="inputLastName" class="form-control" required
                                   value="<?= isset($model->last_name) ? $this->e($model->last_name) : '' ?>"
                                   name="last_name"
                                   placeholder="<?= $this->t('app', 'Doe') ?>">

                            <?php if (isset($regErrors['last_name']) && $error = $regErrors['last_name'][0]): ?>
                                <div id="errorsinputLastName">
                                    <div class="validationErrorMessage">
                                        <?= $error ?>
                                    </div>
                                </div>
                            <?php endif ?>

                        </div>
                    </div>

                    <label id="inputGender" class="required">
                        <?= $this->t('app', 'Gender') ?>
                    </label>
                    <div class="radio">
                        <label>
                            <input type="radio" name="gender" value="<?= 0 ?>"
                                <?= isset($model->gender) && $model->gender != $model::GENDER_FEMALE || !isset($model->gender)
                                    ? 'checked'
                                    : ''
                                ?>
                            >
                            <?= $this->t('app', 'Male') ?>
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="gender" value="<?= 1 ?>"
                                <?= isset($model->gender) && $model->gender == $model::GENDER_FEMALE ? 'checked' : '' ?>
                            >
                            <?= $this->t('app', 'Female') ?>
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="inputLogin" class="required">
                            <?= $this->t('app', 'Your nickname') ?>
                        </label>
                        <input type="text" id="inputLogin" class="form-control"
                               value="<?= isset($model->login) ? $this->e($model->login) : '' ?>"
                               name="login"
                               placeholder="<?= $this->t('app', 'JaneDoe') ?>" required aria-required="true">

                        <?php if (isset($regErrors['login']) && $error = $regErrors['login'][0]): ?>
                            <div id="errorsinputLogin">
                                <div class="validationErrorMessage">
                                    <?= $error ?>
                                </div>
                            </div>
                        <?php endif ?>

                        <small class="help-block">
                            <?= $this->t('app', 'Nickname hint') ?>
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="inputFile">
                            <?= $this->t('app', 'Profile picture') ?>
                        </label>
                        <input type="file" id="inputFile" name="picture">

                        <?php if (isset($regErrors['picture']) && $error = $regErrors['picture'][0]): ?>
                            <div id="errorsinputFile">
                                <div class="validationErrorMessage">
                                    <?= $error ?>
                                </div>
                            </div>
                        <?php endif ?>

                        <small class="help-block">
                            <?= $this->t('app', 'Profile picture hint') ?>
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="inputInformation">
                            <?= $this->t('app', 'Information') ?>
                        </label>
                        <textarea class="form-control full-width" name="information" id="inputInformation"
                                  cols="4"><?= isset($model->information) ? $this->e($model->information) : '' ?></textarea>
                        <small class="help-block">
                            <?= $this->t('app', 'Information hint') ?>
                        </small>
                    </div>

                    <label for="inputEmail" class="required"><?= $this->t('app', 'Email') ?></label>
                    <input type="email" id="inputEmail" class="form-control"
                           value="<?= isset($model->email) ? $this->e($model->email) : '' ?>"
                           name="email"
                           placeholder="<?= $this->t('app', 'jane.doe@example.com') ?>" required aria-required="true">

                    <?php if (isset($regErrors['email']) && $error = $regErrors['email'][0]): ?>
                        <div id="errorsinputEmail">
                            <div class="validationErrorMessage">
                                <?= $error ?>
                            </div>
                        </div>
                    <?php endif ?>

                    <small class="help-block">
                        <?= $this->t('app', 'Email hint') ?>
                    </small>

                    <label for="inputPassword" class="required">
                        <?= $this->t('app', 'Password') ?>
                    </label>
                    <div class="input-group">
                        <input type="password" id="inputPassword" class="form-control"
                               value="<?= isset($model->password) ? $this->e($model->password) : '' ?>"
                               name="password"
                               placeholder="<?= $this->t('app', 'Password') ?>" required aria-required="true">
                        <span class="input-group-addon toggle-password" id="toggle-password"
                              title="<?= $this->t('app', 'Show password') ?>">
                        <i class="glyphicon glyphicon-eye-open"></i>
                    </span>
                    </div>

                    <?php if (isset($regErrors['password']) && $error = $regErrors['password'][0]): ?>
                        <div id="errorsinputPassword">
                            <div class="validationErrorMessage">
                                <?= $error ?>
                            </div>
                        </div>
                    <?php endif ?>

                    <small class="help-block">
                        <?= $this->t('app', 'Password hint') ?>
                    </small>

                    <hr>

                    <button class="btn btn-lg btn-success btn-block btn-signup" type="submit" name="registration">
                        <?= $this->t('app', 'Sign Up!') ?>
                    </button>
                </form><!-- /form -->
            </div><!-- /card-container -->

        </div>

    </div><!-- /container -->

<?php $this->start('body') ?>
    <script>
        function togglePass(button, field) {
            button.on('click', function () {
                if (field.attr('type') == 'password') {
                    button.html('<i class="glyphicon glyphicon-eye-close"></i>');
                    button.attr('title', '<?= $this->t('app', 'Hide password') ?>');
                    field.attr('type', 'text');
                } else {
                    button.html('<i class="glyphicon glyphicon-eye-open"></i>');
                    button.attr('title', '<?= $this->t('app', 'Show password') ?>');
                    field.attr('type', 'password');
                }
            })
        }
        var regToggleButton = $('#toggle-password');
        var regField = $('#inputPassword');
        var loginToggleButton = $('#login-toggle-password');
        var loginField = $('#loginInputPassword');
        togglePass(regToggleButton, regField);
        togglePass(loginToggleButton, loginField);
        $(document).ready(function () {
            addValidationRule('inputFirstName', ['required', '<?= $this->t('validation', 'First Name required') ?>']);
            addValidationRule('inputFirstName', ['string', 2, 30, '<?= $this->t('validation', 'First Name length') ?>']);
            addValidationRule('inputLastName', ['required', '<?= $this->t('validation', 'Last Name required') ?>']);
            addValidationRule('inputLastName', ['string', 2, 30, '<?= $this->t('validation', 'Last Name length') ?>']);
            addValidationRule('inputInformation', ['string', 0, 255, '<?= $this->t('validation', 'Information length') ?>']);
            addValidationRule('inputEmail', ['email', '<?= $this->t('validation', 'Email email') ?>', '<?= $this->t('validation', 'Email unique') ?>']);
            addValidationRule('inputPassword', ['string', 4, 255, '<?= $this->t('validation', 'Password length') ?>']);
            addValidationRule('inputLogin', ['login', 3, 50, '<?= $this->t('validation', 'Login length') ?>', '<?= $this->t('validation', 'Login unique') ?>']);
            addValidationRule('inputFile', ['file', 'jpg|jpeg|png|gif', '<?= $this->t('validation', 'File ext') ?>']);
            formValidation('registrationForm');
        });
    </script>
<?php $this->stop() ?>