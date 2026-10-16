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

test('about us page loads', function () {
    $this->get('/about')
        ->assertOk()
        ->assertSee('More than a café.')
        ->assertSee('Our community.');
});

test('contact us page loads', function () {
    $this->get('/contact')->assertOk()->assertSee('Contact us');
});

test('footer links to about us and contact us', function () {
    $this->get('/')->assertSee(route('about'))->assertSee(route('contact'));
});
