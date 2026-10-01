<?php

test('home page loads', function () {
    $this->get('/')->assertOk();
});

test('login page loads', function () {
    $this->get('/login')->assertOk();
});

test('register page loads', function () {
    $this->get('/register')->assertOk();
});

test('shop page loads', function () {
    $this->get('/shop')->assertOk();
});

test('running club page loads', function () {
    $this->get('/running')->assertOk();
});
