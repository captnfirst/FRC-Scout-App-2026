<?php

// Application Routes
App::get('/', false);
App::get('/default/index', true);
App::get('/default/login', false);
App::get('/default/register', false);
App::get('/default/forgot_password', false);
App::get('/default/reset_password', false);
App::get('/default/reset_password/([a-zA-Z0-9]+)', false);
App::get('/default/logout', false);
App::get('/default/set_language/([a-zA-Z_]+)', false);

App::get('/default/profile', true);
App::get('/default/members', true);
App::get('/default/adminekle', true);
App::get('/default/delete_member/([\d]+)', true);
App::get('/default/deleteadmin/([\d]+)', true);
App::get('/default/approve_transfer/([\d]+)', true);
App::get('/default/reject_transfer/([\d]+)', true);

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
App::get('/default/settings', true);

App::get('/default/simulator/([a-zA-Z0-9_-]+)/([a-zA-Z0-9_-]+)', true);

// POST Actions
App::post('/default/register', false);
App::post('/default/login', false);
App::post('/default/forgot_password', false);
App::post('/default/reset_password', false);

App::post('/default/update_profile', true);
App::post('/default/change_password', true);
App::post('/default/request_transfer', true);

App::post('/default/save_weights', true);
App::post('/default/save_settings', true);
App::post('/default/test_tba', true);
App::post('/default/add_member', true);
App::post('/default/addadmin', true);
App::post('/default/savescout', true);
App::post('/default/savepitscout', true);

App::post('/default/addPracticeMatch', true);
App::post('/default/updateMentorData', true);

?>