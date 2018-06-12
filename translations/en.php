<?php

return [
    'app' => [
        'Login & Registration test app' => 'Login & Registration test app',
        'Nickname hint' => 'Can be used for authorization, it will be your main identity on the site, should consist only of letters, numbers, underscores and hyphens if this is not the first or last character.',
        'Profile picture hint' => 'It will be displayed near the nickname and on your profile page. Allowed jpg, png and gif formats.',
        'Password hint' => 'At least 4 characters. We recommend to choose a strong password to protect your account from cracking.',
        'Email hint' => 'Use only your own Email address, in case the password is lost it will be sent an email with instructions to reset.',
        'Login hint' => 'You can use Email or Login',
        'Login placeholder' => 'Email or login',
        'Information hint' => 'Tell other users a bit about yourself',
    ],
    'validation' => [
        'First Name required' => '"First Name" required.',
        'First Name length' => '"First Name" can not be less than 2 and more than 30 characters.',
        'Last Name required' => '"Last Name" required.',
        'Last Name length' => '"Last Name" can not be less than 2 and more than 30 characters.',
        'Information length' => '"Information" can not be more than 250 characters.',
        'Email email' => 'It looks like that your Email is not correct, check please.',
        'Password length' => '"Password" can not be less than 4 characters',
        'Login length' => '"Username" can not be less than 3 and more than 50 characters, must consist of letters, and you can use also numbers, dashes and underscores.',
        'File ext' => 'Profile Picture should be the image format jpg, png or gif.',
        'Login unique' => 'Sorry, but the nickname is already in use by another user. Select another, please.',
        'Email unique' => 'This email address already registered with us, now you can sign in to your account using it and your password.',
    ]
];