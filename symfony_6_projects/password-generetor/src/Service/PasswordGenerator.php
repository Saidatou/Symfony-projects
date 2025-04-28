<?php

namespace App\Service;



class PasswordGenerator
{

    public function generate(int $length, bool $uppercaseLetters = false, bool $digits = false, bool $specialCharacters = false):string
    {

        
        $lowercaseLettersAlphabet = range('a', 'z');
        $uppercaseLettersAlphabet = range('A', 'Z');
        $digitsAlphabet = range(0, 9);
        $specialCharactersAlphabet = array_merge(
            range('!', '/'),
            range(':', '@'),
            range('[', "'"),
            range('{', '~'),
        );

        $finalAlphabet = [$lowercaseLettersAlphabet];

        //On rajoute une lettre en minuscule choisie de manière aléatoire
        $password = [$this->pickRandomItemFromAlphabet($lowercaseLettersAlphabet)];

        // if ($uppercaseLetters) {

        //     // $finalAlphabet = array_merge($finalAlphabet, $uppercaseLettersAlphabet);
        //     //On rajoute une lettre en majuscule choisie de manière aléatoire
        //     $password[] = $this->pickRandomItemFromAlphabet($uppercaseLettersAlphabet);
        // }
        // if ($digits) {

        //     // $finalAlphabet = array_merge($finalAlphabet, $digitsAlphabet);
        //     //On rajoute un chiffre entre 0 et 9 choisi de manière aléatoire
        //     $password[] = $this->pickRandomItemFromAlphabet($digitsAlphabet);
        // }
        // if ($specialCharacters) {

        //     // $finalAlphabet = array_merge($finalAlphabet, $specialCharactersAlphabet);
        //     //On rajoute un caractere special choisi de manière aléatoire
        //     $password[] = $this->pickRandomItemFromAlphabet($specialCharactersAlphabet);
        // }

        // $mapping = [
        //     'uppercaseLetters'=>$uppercaseLettersAlphabet,
        //     'digits'=>$digitsAlphabet,
        //     'specialCharacters'=>$specialCharactersAlphabet,
        // ];
        
        // on fait le mapping des contraintes
        $constraintMapping = [
            [$uppercaseLetters, $uppercaseLettersAlphabet],
            [$digits, $digitsAlphabet],
            [$specialCharacters, $specialCharactersAlphabet],
        ];
        // foreach ($mapping as $constraintEnabled =>$constraintAlphabet)
        //we make sure that the final password contains at least one 
        //{uppercase letter and/or digit and/or special character}
        //based on user's requested constraints.
        //we also grow at the same time the final aphabet with
        //the alphabet of requested constraint
        foreach ($constraintMapping  as [$constraintEnabled, $constraintAlphabet])
        {
            if($constraintEnabled ){
                //  $finalAlphabet = array_merge($finalAlphabet, $constraintAlphabet);
                 $finalAlphabet [] = $constraintAlphabet;
                 $password[] = $this->pickRandomItemFromAlphabet($constraintAlphabet);
            }
        }
        $finalAlphabet = array_merge(...$finalAlphabet);
       
       

        
        //    $numberOfCharactersRemaining = $length - mb_strlen($password);
        $numberOfCharactersRemaining = $length - count($password);


        // dd(mt_rand(0,9));
        // dump($finalAlphabet);

        for ($i = 0; $i < $numberOfCharactersRemaining; $i++) {
            //    $password =$password. $finalAlphabet[mt_rand(0, count($finalAlphabet)-1)];
            // $password .= $finalAlphabet[array_rand($finalAlphabet)];
            // $password .= $finalAlphabet[random_int(0, count($finalAlphabet)-1)];
            $password[] = $this->pickRandomItemFromAlphabet($finalAlphabet);
        }
        //le mot de passe était une chaîne de caractère puis à ce niveau on le transforme en tableau
        // $password = str_split($password);
        // ici en tableau securisé
        $password = $this->secureShuffle($password);
        // dump($password);
        // pour le tranformer en chaîne de charcatère on utilise "implode
        return implode('', $password);
        // dump($password);
        // die();

    }

    private function secureShuffle(array $arr): array
    {
        //https://github.com/lamansky/secure-shuffle/blob/master/src/functions.php
        $length = count($arr);
        for ($i = $length - 1; $i > 0; $i--) {
            $j = random_int(0, $i);
            $temp = $arr[$i];
            $arr[$i] = $arr[$j];
            $arr[$j] = $temp;
        }
        return $arr;
    }
    private function pickRandomItemFromAlphabet(array $alphabet): string
    {
        return $alphabet[random_int(0, count($alphabet) - 1)];
    }

}