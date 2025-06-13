<?php

interface AuthInterface {
    public function login(string $login, string $haslo): bool;
}

class User implements AuthInterface {
    private string $login;
    private string $haslo;

    public function __construct(string $login, string $haslo) {
        $this->login = $login;
        $this->haslo = $haslo;
    }

    public function login(string $login, string $haslo): bool {
        return $this->login === $login && $this->haslo === $haslo;
    }
}
