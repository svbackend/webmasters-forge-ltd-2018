<?php

declare(strict_types=1);

namespace Base\Controller;

use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Response;

final class ErrorController extends Controller
{
    public function exceptionAction(FlattenException $exception)
    {
        // Todo use some template maybe
        return new Response($exception->getMessage(), $exception->getStatusCode());
    }
}