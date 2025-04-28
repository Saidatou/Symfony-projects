<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Service\PasswordGenerator;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PagesController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function home(): Response
    {
        return $this->render('pages/home.html.twig', [
            'password_default_length' => $this->getParameter('app.password_default_length'),
            'password_min_length' => $this->getParameter('app.password_min_length'),
            'password_max_length' => $this->getParameter('app.password_max_length')
        ]);
    }

    #[Route('/generate-password', name: 'app_generate_password', methods: ['GET'])]
    public function generatePassword(Request $request, PasswordGenerator $passwordGenerator): Response
    {
        // We make sure that the password length is always 
        // at minimum {app.password_min_length} 
        // and at maximum {app.password_max_length}.
        $length = max(
            min($request->query->getInt('length'), $this->getParameter('app.password_max_length')),
            $this->getParameter('app.password_min_length')
        );
        $uppercaseLetters = $request->query->getBoolean('uppercase_letters');
        $digits = $request->query->getBoolean('digits');
        $specialCharacters = $request->query->getBoolean('special_characters');

        $password = $passwordGenerator->generate(
            $length,
            $uppercaseLetters,
            $digits,
            $specialCharacters,
        );

        $response = $this->render('pages/password.html.twig', compact('password'));

        $this->setPasswordPreferencesAsCookies(
            $response, $length, $uppercaseLetters, $digits, $specialCharacters
        );

        return $response;
    }

    private function setPasswordPreferencesAsCookies(Response $response, int $length, bool $uppercaseLetters, bool $digits, bool $specialCharacters): void
    {
        $fiveYearsFromNow = new DateTimeImmutable('+5 years');

        $response->headers->setCookie(
            new Cookie('app_length', $length, $fiveYearsFromNow)
        );

        $response->headers->setCookie(
            new Cookie('app_uppercase_letters', $uppercaseLetters ? '1' : '0', $fiveYearsFromNow)
        );

        $response->headers->setCookie(
            new Cookie('app_digits', $digits ? '1' : '0', $fiveYearsFromNow)
        );

        $response->headers->setCookie(
            new Cookie('app_special_characters', $specialCharacters ? '1' : '0', $fiveYearsFromNow)
        );
    }
}

// namespace App\Controller;

// use DateTimeImmutable;
// use App\Service\PasswordGenerator;
// use Symfony\Component\HttpFoundation\Cookie;
// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


// class PagesController extends AbstractController
// {
//     // const app.password_DEFAULT_LENGTH = 12;
//     // const app.password_MIN_LENGTH = 8;
//     // const app.password_MAX_LENGTH = 60;

//     #[Route('/', name: 'app_home', methods: ['GET'])]
    // public function home(): Response
//     {

//         // dd($request->getSession()->get('toto_charabia'));
//         // mt_srand(2);//seed
//         // for ($i=0; $i < 5; $i++) { 
//         //     // dump(mt_rand(0,45));
//         //     dump(random_int(0,45));
//         // }
//         // die();
//             // $options = [] ;

//             // $options[]=[1,2,3];
//             // $options[]=[4,5,6];
//             // $options[]=[7,8,9];

//             // dd(array_merge(...$options));
//             //modification au niveau de la session
//             // 'password_default_length'=>$request->getSession()->get('app.length', 
//             // $this->getParameter('app.password_default_length')),


//         return $this->render('pages/home.html.twig',[
//             'password_default_length'=>$this->getParameter('app.password_default_length'),
//             'password_min_length'=>$this->getParameter('app.password_min_length'),
//             'password_max_length'=>$this->getParameter('app.password_max_length'),
          
//         ]);
//     }

// #[Route('/generate-password', name: 'app_generate_password', methods: ['GET'])]
// public function generatePassword(Request $request, PasswordGenerator $passwordGenerator): Response

//     
//     {
//         // dump($request->query->all());
//         // $length = $request->query->getInt('length');
//         // $uppercaseLetters = $request->query->getBoolean('uppercase_letters');
//         // $digits = $request->query->getBoolean('digits');
//         // $specialCharacters = $request->query->getBoolean('special_characters');

//         $passwordGenerator = new PasswordGenerator;
       
//         // we make sure that the password length is alwals at minimum {app.password_min_length}
//         // and maximum at {app.password_max_length}
//         $length= max(
//             min($request->query->getInt('length'),$this->getParameter('app.password_max_length')),
//             $this->getParameter('app.password_min_length')
//         );
//         // les infos de la session en dessous
//         $uppercaseLetters= $request->query->getBoolean('uppercase_letters');
//         $digits= $request->query->getBoolean('digits');
//         $specialCharacters= $request->query->getBoolean('special_characters');


//         // $session=$request->getSession();
//         // $session->set('app.length', $length);
//         // $session->set('app.uppercase_letters', $uppercaseLetters);
//         // $session->set('app.digits', $digits);
//         // $session->set('app.special_characters', $specialCharacters);
        
//         $password = $passwordGenerator->generate(
//             $length,
//             $uppercaseLetters,
//             $digits,
//             $specialCharacters,
//             // $request->query->getBoolean('uppercase_letters'),
//             // $request->query->getBoolean('digits'),
//             // $request->query->getBoolean('special_characters'),
            
//         );
//         // return $this->render('pages/password.html.twig', compact('password'));
//         //on sauvegarde la reponse dans une variable
//         $response = $this->render('pages/password.html.twig', compact('password'));
//         $this->setPasswordPreferencesAsCookies($response, $uppercaseLetters, $length, $digits, $specialCharacters);
       
//         // on retourne la reponse
//         return $response;
//     }
//     private function setPasswordPreferencesAsCookies(Response $response, int $length,bool $uppercaseLetters,bool $specialCharacters,bool $digits):void
//     {
//         $fiveYearsFromNow=new DateTimeImmutable('+5 years');
//         // on fait ll'appel de la reponse à travers le headers ->setCookie newCookie
//         $response->headers->setCookie(new Cookie('app_length',$length, $fiveYearsFromNow ));
//         $response->headers->setCookie(new Cookie('app_uppercase_letters',$uppercaseLetters ? '1':'0',  $fiveYearsFromNow));
//         $response->headers->setCookie(new Cookie('app_digits',$digits ? '1':'0',  $fiveYearsFromNow));
//         $response->headers->setCookie(new Cookie('app_special_characters',$specialCharacters ? '1':'0',  $fiveYearsFromNow));
//     }
// }
