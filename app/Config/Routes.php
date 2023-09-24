<?php

use CodeIgniter\Router\RouteCollection;

$routes->get('/', 'MusicController::index');
$routes->post('music/createPlaylist', 'MusicController::createPlaylist');
$routes->post('music/upload', 'MusicController::uploadMusic');
$routes->post('music/getPlaylistMusic', 'MusicController::getPlaylistMusic');
$routes->post('music/addToPlaylist', 'MusicController::addToPlaylist');
