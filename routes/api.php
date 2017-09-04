<?php

use Illuminate\Http\Request;

// TODO Permet d'ouvrir l'accès aux routes api par les "voies externes"
header( 'Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-type' );
header('Access-Control-Allow-Origin: *');

// TODO "localhost" doit être remplacé par la valeur de ton serveur

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// TODO Un group donne, pour un ensemble d'appels, une propriété commune
// Dans les deux cas présents, je donne un préfix à mes appels API, et dans le
// deuxième cas, une protection de middleware.

// TODO $this->type : type de route api créé (principalement : get / post / put / delete)
// $this->get('routeDéterminée', "ControllerAppelé@FonctionDuController") : le controller se situe dans App/Http/Controllers

// TODO Utilisation d'api "publiques", puisqu'elles n'ont aucun middleware à franchir
Route::group(['prefix' => 'user'], function(){
    $this->post('/register', 'UserApi@register'); // Soit : localhost/api/user/register
    $this->post('/connexion', 'UserApi@connexion'); // Soit : localhost/api/user/connexion
});


// TODO Utilisation d'api "privées", puisqu'elles nécessitent
// de passer le middleware "auth:api" qui est géré par Passport, un package OAuth2
// que j'ai installé moi-même, qui n'est pas de base)
Route::group(['prefix' => 'user', 'middleware' => 'auth:api'], function () {
    $this->get('/informations', 'UserApi@getOwnInformations');
});

// TODO Utilisation d'api "publiques", puisqu'elles n'ont aucun middleware à franchir
Route::group(['prefix' => 'test'], function(){
    $this->post('/sayHello', 'UserApi@sayHello'); // Soit : localhost/api/user/sayHello
});
