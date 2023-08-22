<?php

namespace App\Controllers;

use App\Forms\User\RegisterForm;
use App\Services\UserService;
use Somecode\Framework\Controller\AbstractController;
use Somecode\Framework\Http\RedirectResponse;
use Somecode\Framework\Http\Response;

class RegisterController extends AbstractController
{
    public function __construct(
        private UserService $userService
    ) {
    }

    public function form(): Response
    {
        return $this->render('register.html.twig');
    }

    public function register()
    {
        // 1. Создайте модель формы, которая будет:
        $form = new RegisterForm($this->userService);

        $form->setFields(
            $this->request->input('email'),
            $this->request->input('password'),
            $this->request->input('password_confirmation'),
            $this->request->input('name'),
        );

        // 2. Валидация
        // Если есть ошибки валидации, добавить в сессию и перенаправить на форму

        if ($form->hasValidationErrors()) {
            foreach ($form->getValidationErrors() as $error) {
                $this->request->getSession()->setFlash('error', $error);
            }

            return new RedirectResponse('/register');
        }

        // 3. Зарегистрировать пользователя, вызвав $form->save()

        $user = $form->save();

        // 4. Добавить сообщение об успешной регистрации

        $this->request->getSession()->setFlash('success', "Пользователь {$user->getEmail()} успешно зарегистрирован");

        // 5. Войти в систему под пользователем

        // 6. Перенаправить на нужную страницу

        return new RedirectResponse('/register');
    }
}