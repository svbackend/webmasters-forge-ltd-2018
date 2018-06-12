<?php

declare(strict_types=1);

namespace Home\Controller;

use Base\Controller\Controller;
use Base\Exception\UniqueConstraint;
use Base\Service\ValidatorService;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use User\Service\RegistrationService;

class IndexController extends Controller
{
    private $validator;
    private $registrationService;

    public function __construct(ValidatorService $validator, RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
        $this->validator = $validator;
        // todo auth service
    }
    
    public function indexAction(array $errors = []): Response
    {
        $response = $this->getTemplate()->make('home/index');
        $response->data([
            'errors' => $errors
        ]);
        return new Response($response);
    }

    //todo Auth/Register Services, Validator
    public function loginAction(Request $request)
    {
        $login = $request->get('login');
        $password = $request->get('password');

        $this->authService->login($login, $password);
        $errors = $this->authService->getErrors();

        if (count($errors)) {
            $errors = ['login' => $errors];
            return $this->forward('indexAction', [
                'errors' => $errors
            ]);
        }

        $user = $this->authService->getUser();
        return new RedirectResponse("/user/{$user['id']}");
    }

    //todo
    public function registrationAction(Request $request)
    {
        $first_name = $request->get('first_name');
        $last_name = $request->get('last_name');
        $login = $request->get('login');
        $email = $request->get('email');
        $password = $request->get('password');
        $information = (string)$request->get('information', '');
        $gender = (int)$request->get('gender', 0);
        $picture = $request->files->get('picture');

        $validator = $this->addRegistrationRules($this->validator);
        $validator->setData(
            compact('first_name', 'last_name', 'login', 'email', 'password', 'information', 'gender', 'picture')
        );
        if ($validator->validate() === false) {
            $errors = ['registration-form' => $validator->getErrors()];
            return $this->forward('\Home\Controller\IndexController::indexAction', [
                'errors' => $errors
            ]);
        }

        try {
            $this->registrationService->register($first_name, $last_name, $login, $email, $password, $information, $gender);
        } catch (UniqueConstraint $exception) {
            if ($exception->getField() === 'login') {
                $validator->addError('login', 'Login unique');
            } else {
                $validator->addError('email', 'Email unique');
            }

            $errors = ['registration-form' => $validator->getErrors()];
            return $this->forward('\Home\Controller\IndexController::indexAction', [
                'errors' => $errors
            ]);
        }

        $user = $this->registrationService->getRegisteredUser();

        // Todo move it to service like UserAvatarUploader or something
        /** @var $picture UploadedFile */
        $picture->move(APP_ROOT . '/public/files/avatars', "{$user['id']}.jpg");

        return new RedirectResponse("/user/{$user['id']}");
    }

    private function addRegistrationRules(ValidatorService $validator): ValidatorService
    {
        $validator->addRule('first_name', [
            'name' => 'string',
            'min' => 2,
            'max' => 30,
            'message' => 'First Name length',
        ]);
        $validator->addRule('last_name', [
            'name' => 'string',
            'min' => 2,
            'max' => 30,
            'message' => 'Last Name length'
        ]);
        $validator->addRule('information', [
            'name' => 'string',
            'min' => 0,
            'max' => 255,
            'message' => 'Information length'
        ]);
        $validator->addRule('email', [
            'name' => 'email',
            'message' => 'Email email'
        ]);

        $validator->addRule('password', [
            'name' => 'string',
            'min' => 4,
            'max' => 255,
            'message' => 'Password length'
        ]);
        $validator->addRule('login', [
            'name' => 'login',
            'min' => 3,
            'max' => 50,
            'message' => 'Login length'
        ]);
        $validator->addRule('picture', [
            'name' => 'file',
            'ext' => ['jpg', 'jpeg', 'png', 'gif'],
            'message' => 'File ext'
        ]);

        return $validator;
    }
}