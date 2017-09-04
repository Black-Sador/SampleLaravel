<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\City_ref;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserApi extends Controller
{

    // TODO Permet de faire une demande de contenu à un tableau : si le tableau n'est pas rempli selon cette règle, le validator renvoie une erreur
    private function validatorRegistration($informations)
    {
        return Validator::make($informations,
            [
                'gender' => 'required',
                'name' => 'required|max:255',
                'first_name' => 'required|max:255',
                'email' => 'required|email|max:255',
                'password' => 'required|min:6'
            ]);
    }


    public function register(Request $request)
    {
        // TODO Preparation du message de renvoi
        $response = ['response' => "Account created"];

        // TODO On vérifie que les informations envoyées soit stockées dans le tableau souhaité
        if ($request->informations == null) {
            $response = ['response' => "No informations"];
            return response(json_encode($response), 400);
        }

        // TODO On demande au validator de faire attention au contenu du tableau et renvoie les erreurs voulues
        $validator = $this->validatorRegistration($request->informations);
        if ($validator->fails()) {
            $response = ['response' => $validator->errors()];
            return response(json_encode($response), 200);
        }

        // TODO J'appelle mon model User, en lui demandant un model contenant toutes les information d'une personne suivant son Email
        $user = User::getByMail($request->informations['email']);
        if ($user == null) {
            // TODO Si ce user n'existe pas, alors on en crée un, avec les informations contenues dans ce fameux tableau
            User::create([
                'gender' => $request->informations['gender'],
                'name' => $request->informations['name'],
                'first_name' => $request->informations['first_name'],
                'email' => $request->informations['email'],
                'password' => bcrypt($request->informations['password']),
            ]);
        } else
            $response = ['response' => "Email address already used"];
        return response(json_encode($response), 200);
    }

    public function connexion(Request $request)
    {

        // TODO Vérification des champs requis
        if ($request->email == null) {
            $result = ['response' => "Need email."];
            return response(json_encode($result), 402);
        }
        if ($request->password == null) {
            $result = ['response' => "Need password."];
            return response(json_encode($result), 402);
        }

        // TODO Vérification de l'utilisateur
        $user = User::getByMail($request->email);
        if ($user == null) {
            $response = "Bad email-address.";
            return response(json_encode(['response' => $response]), 200);
        }

        // TODO Vérification du mot de passe
        $verification = Hash::check($request->password, $user->password);
        if ($verification == false) {
            $response = "Bad password.";
            return response(json_encode(['response' => $response]), 200);
        }

        // TODO Utilisation de OAuth en local
        $http = new Client();
        // TODO Récupération de la clé de mon client numéro 2 (permet de différencier les appels API en fonction des clients)
        $client = DB::table('oauth_clients')->where('id', 2)->first();
        // TODO récupération de la clé api personnelle via le mot de passe et l'email de l'utilisateur
        $response = $http->post(env('APP_ENV').'/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => 2,
                'client_secret' => $client->secret,
                'password' => $request->password,
                "username" => $request->email
            ],
        ]);
        $token = json_decode((string)$response->getBody(), true);
        $tokenToSend = [
            'token' => $token['access_token'],
        ];


        return response(json_encode(['response' => $tokenToSend]), 200);
    }

    public function getOwnInformations(Request $request)
    {
        // TODO Permet de récupérer les informations de la personne "authentifée"
        $user = Auth::user();


        $response = [
            "gender" => $user->gender,
            "name" => $user->name,
            "first_name" => $user->first_name,
        ];
        return response(json_encode(['response' => $response]), 200);
    }

    // TODO En php, les arguments sont pas forcément obligatoires si tu les utilises pas
    public function sayHello()
    {
        return response(json_encode(['response' => "Hello"]), 200); // TODO Attention : le status est important. (Cf: google/status-http)
        // TODO response permet de mettre en forme la réponse. Il doit contenir du json (d'ou le "json_encode")
    }
}
