<?php

declare(strict_types=1);

namespace Home\Controller;

use Base\Controller\Controller;
use Base\Service\ValidatorService;
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
        $login = $request->get('login');
        $email = $request->get('email');
        $password = $request->get('password');
        $about = $request->get('about');
        $gender = $request->get('gender');

        try {
            $this->registrationService->register($login, $email, $password, $about, $gender);
        } catch (\PDOException $exception) {
            // todo error response
            return;
        }
        $errors = $this->authService->getErrors();

        if (count($errors)) {
            $errors = ['login' => $errors];
            return $this->forward('indexAction', [
                'errors' => $errors
            ]);
        }

        $user = $this->registrationService->getRegisteredUser();
        return new RedirectResponse("/user/{$user['id']}");
    }
}