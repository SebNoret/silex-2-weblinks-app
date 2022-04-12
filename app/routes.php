<?php

// Home page
$app->get('/', 'WebLinks\Controller\HomeController::indexACtion')->bind('home');

// Login page
$app->get('/login','WebLinks\Controller\HomeController::LoginAction')->bind('login');

// Add new link
$app->match('link/submit', 'WebLinks\Controller\adminController::addLinkAction')->bind("link_submit");

// Admin page

$app->get('/admin', 'WebLinks\Controller\AdminController::indexAction')->bind('admin');
$app->match('/admin/link/edit/{id}', 'WebLinks\Controller\AdminController::editLinkAction')->bind('admin_link_edit');
$app->get('/admin/link/delete/{id}', 'WebLinks\Controller\AdminController::deleteLinkAction')->bind('admin_link_delete');

$app->match('/admin/user/add', 'WebLinks\Controller\AdminController::addUserAction')->bind('admin_user_add');
$app->match('/admin/user/edit/{id}', 'WebLinks\Controller\AdminController::editUserAction')->bind('admin_user_edit');
$app->get('/admin/user/delete/{id}', 'WebLinks\Controller\AdminController::deleteUserAction')->bind('admin_user_delete');



// API : get all links

$app->get('/api/links/', 'WebLinks\Controller\ApiController::getLinksAction')->bind('api/links');

// API : get one link selected by id
$app->get('/api/link/{id}', 'WebLinks\Controller\ApiController::getLinkAction')->bind('api/link');










