<?php

describe("Функция validateFormLogin", function() {
    context("если передан корректный логин", function() {
        it("возвращает NULL", function() {
            $login = 'userTest';
            $result = validateFormLogin($login);
            expect($result)->toBe(NULL);
        });
    });

    context("если передан логин длиннее 50 символов", function() {
        it("возвращает ошибку", function() {
            $login = 'userTestuserTestuserTestuserTestuserTestuserTestuserTest';
            $result = validateFormLogin($login);
            expect($result)->toBe('Превышено количество символов!');
        });
    });

    context("если передан пустой логин", function() {
        it("возвращает ошибку", function() {
            $login = '';
            $result = validateFormLogin($login);
            expect($result)->toBe('Необходимо заполнить логин!');
        });
    });
});

