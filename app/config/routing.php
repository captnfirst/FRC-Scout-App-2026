<?php

//AGR SKOR Routing
App::get('/', false);
App::get('/default/index', true);
App::get('/default/login', false);
App::get('/default/logout', false);
App::get('/default/adminekle', true);
App::get('/default/deleteadmin/([\d]+)',true);
App::get('/default/tournaments', true);
App::get('/default/teams/([a-zA-Z0-9_-]+)', true);
App::get('/default/matches/([a-zA-Z0-9_-]+)/([a-zA-Z0-9_-]+)', true);
App::get('/default/scout/([a-zA-Z0-9_-]+)/([a-zA-Z0-9_-]+)/([a-zA-Z0-9_-]+)', true);

App::get('/default/pit_tournaments', true);
App::get('/default/pit_teams/([a-zA-Z0-9_-]+)', true);
App::get('/default/pit_scout/([a-zA-Z0-9_-]+)/([a-zA-Z0-9_-]+)', true);

App::get('/default/analysis_tournaments_list', true);
App::get('/default/analysis_tournament/([a-zA-Z0-9_-]+)', true);

App::get('/default/team_analysis/([a-zA-Z0-9_-]+)/([a-zA-Z0-9_-]+)', true);

App::get('/default/score_weights', true);

App::get('/default/simulator/([a-zA-Z0-9_-]+)/([a-zA-Z0-9_-]+)', true);

App::post('/default/save_weights', true);
App::post('/default/login', false);
App::post('/default/addadmin',true);
App::post('/default/savescout', true);
App::post('/default/savepitscout', true);

App::get('/default/practice_matches/([a-zA-Z0-9_-]+)/([a-zA-Z0-9_-]+)', true);
App::post('/default/add_practice_match', true);

?>