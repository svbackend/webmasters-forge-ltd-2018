<?php

declare(strict_types=1);

namespace Home\Controller;

use Base\Controller\Controller;
use Base\Exception\UniqueConstraint;
use Base\Service\ValidatorService;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use User\Repository\UserRepository;
use User\Service\AuthService;
use User\Service\RegistrationService;

class IndexController extends Controller
{
    private $validator;
    private $registrationService;
    private $authService;

    public function __construct(ValidatorService $validator, RegistrationService $registrationService, AuthService $authService)
    {
        $this->authService = $authService;
        $this->registrationService = $registrationService;
        $this->validator = $validator;
    }
    
    public function indexAction(array $errors = [], array $user = []): Response
    {
        $response = $this->getTemplate()->make('home/index');
        $response->data([
            'errors' => $errors,
            'user' => $user,
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
            $errors = ['login-form' => $errors];
            return $this->forward('\Home\Controller\IndexController::indexAction', [
                'errors' => $errors
            ]);
        }

        $user = $this->authService->getAuthenticatedUser();
        return new RedirectResponse("/user/{$user['id']}");
    }

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

        $user = compact('first_name', 'last_name', 'login', 'email', 'password', 'information', 'gender', 'picture');
        $validator = $this->addRegistrationRules($this->validator);
        $validator->setData($user);

        if ($validator->validate() === false) {
            $errors = ['registration-form' => $validator->getErrors()];
            return $this->forward('\Home\Controller\IndexController::indexAction', [
                'errors' => $errors,
                'user' => $user,
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
                'errors' => $errors,
                'user' => $user,
            ]);
        }

        $user = $this->registrationService->getRegisteredUser();

        // Todo move it to service like UserAvatarUploader or something
        /** @var $picture UploadedFile */
        $picture->move(APP_ROOT . '/public/files/avatars', "{$user['id']}.jpg");

        return new RedirectResponse("/user/{$user['id']}");
    }

    // todo move to UserController
    public function userAction(int $id)
    {
        // This action should be moved to another controller so I decided to get repository directly through container
        // at this time, but it's bad practice and should be refactored
        /** @var $userRepository UserRepository */
        $userRepository = $this->container->get('user_repository');

        if (null === $user = $userRepository->find($id)) {
            throw new NotFoundHttpException("User with id {$id} not found");
        }

        $response = $this->getTemplate()->make('user/view');
        $response->data([
            'user' => $user,
        ]);
        return new Response($response);
    }

    // todo move to LangController
    public function langAction(string $locale, Request $request)
    {
        $localeCookie = new Cookie('locale', $locale);
        $response = new RedirectResponse('/');
        $response->headers->setCookie($localeCookie);
        return $response;
    }

    // todo move to UserController
    public function logoutAction()
    {
        $this->authService->logout();
        return new RedirectResponse('/');
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