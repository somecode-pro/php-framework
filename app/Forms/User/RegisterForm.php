<?php

namespace App\Forms\User;

class RegisterForm
{
    private ?string $name;

    private string $email;

    private string $password;

    private string $passwordConfirmation;

    public function setFields(string $email, string $password, string $passwordConfirmation, string $name = null): void
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->passwordConfirmation = $passwordConfirmation;
    }

    public function getValidationErrors(): array
    {
        $errors = [];

        if (! empty($this->name) && strlen($this->name) > 50) {
            $errors[] = 'Максимальная длинна имени 50 символов';
        }

        if (empty($this->email) || ! filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Неверный формат электронной почты';
        }

        if (empty($this->password) || strlen($this->password) < 8) {
            $errors[] = 'Минимальная длинна пароля 8 символов';
        }

        if ($this->password !== $this->passwordConfirmation) {
            $errors[] = 'Пароли не совпадают';
        }

        return $errors;
    }

    public function hasValidationErrors(): bool
    {
        return ! empty($this->getValidationErrors());
    }
}
