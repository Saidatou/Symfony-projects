<?php

namespace App\Services;

use Mailjet\Client;
use App\Entity\User;
use Mailjet\Resources;
use App\Entity\EmailModel;

class EmailSender
{

    // methode qui va nous permettre d'envoyer un mail avec en paramètre d'une part user $user à qui on 
    //on envoie le mail et d'autre part les informations sur le mail qu'on souhaite envoyer
    public function sendEmailNotificationByMailJet(User $user, EmailModel $email)
    {
        $mj = new Client(getenv('MJ_APIKEY_PUBLIC'), getenv('MJ_APIKEY_PRIVATE'), true, ['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [ //la partie from c'est la partie de celui qui envoie le mail
                    'From' => [
                        'Email' => "babasaidatou@yahoo.fr",
                        'Name' => "MC SPORTIFS"
                    ],
                    //le to c'est la personne à qui on envoie le mail
                    'To' => [
                        [
                            //modification 'Email' => "passenger1@example.com",'Name' => "passenger 1"
                            'Email' => $user->getEmail,
                            'Name' => $user->getFullName
                        ]
                    ],
                    'TemplateID' => 3166656,
                    'TemplateLanguage' => true,
                    //modification 'Subject' => "Nouveau message de MC SPORTIFS",
                    'Subject' => $email->getSubject(),
                    // modif de Variables json_decode('{"title": "Bienvenu sur MC SPORTIFS", "content": ""}', true)
                    'Variables' => [
                        'title' => $email->getTitle(),
                        'content' => $email->getContent()
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success() && dd($response->getData());
    }
}
