<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Account;
use Illuminate\Http\Request;

use App\Models\Club; 

use App\Models\Adherent;
use App\Models\Member;
use App\Models\Raid;
use App\Models\Race;

class AccountController extends Controller
{
   
    public function LoginFromPost(Request $request){
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['COM_PSEUDO' => $credentials['username'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            return redirect()->intended(route('home.index'));
        }

        return back()->with('error', 'Identifiants incorrects');
    }


    public function Logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home.index');
    }

    public function CreateAccountFromPost(Request $request){ //function to make an account from the posted arguments of the signup form
        $validated = $request->validate([
            'username' => 'required|string|max:64|unique:VIK_COMPTE,com_pseudo',
            'surname' => 'required|string|max:32',
            'name' => 'required|string|max:32',
            'birthdate' => 'required|date|before_or_equal:today',
            'address' => 'required|string|max:128',
            'phone' => 'required|string|digits_between:10,12',
            'email' => 'required|string|max:255',
            'password' => 'required|string|min:6|max:64|confirmed',
            'adherentCheck' => 'nullable',
            'license_number' => 'nullable|required_if:adherentCheck,on|string|min:0',
            'chip_code' => 'nullable|required_if:adherentCheck,on|numeric|min:0',

        ],[
            'username.required' => "Le nom d'utilisateur est obligatoire",
            'username.string' => "Le nom d'utilisateur doit être une chaîne de caractères",
            'username.max' => "Le nom d'utilisateur ne peut pas dépasser 64 caractères",
            'username.unique' => "Ce nom d'utilisateur est déjà utilisé",

            'surname.required' => "Le prénom est obligatoire",
            'surname.string' => "Le prénom doit être une chaîne de caractères",
            'surname.max' => "Le prénom ne peut pas dépasser 32 caractères",

            'name.required' => "Le nom est obligatoire",
            'name.string' => "Le nom doit être une chaîne de caractères",
            'name.max' => "Le nom ne peut pas dépasser 32 caractères",

            'birthdate.required' => "La date de naissance est obligatoire",
            'birthdate.date' => "La date de naissance doit être une date valide",
            'birthdate.before_or_equal' => "La date de naissance ne peut pas être postérieure à aujourd’hui",
            
            'address.required' => "L'adresse est obligatoire",
            'address.string' => "L'adresse doit être une chaîne de caractères",
            'address.max' => "L'adresse ne peut pas dépasser 128 caractères",

            'phone.required' => "Le numéro de téléphone est obligatoire",
            'phone.digits_between' => "Le numéro de téléphone doit contenir entre 10 et 12 chiffres",

            'email.required' => "L'adresse email est obligatoire",
            'email.string' => "L'adresse email doit être une chaîne de caractères",
            'email.max' => "L'adresse email ne peut pas dépasser 255 caractères",
            
            'password.required' => "Le mot de passe est obligatoire",
            'password.string' => "Le mot de passe doit être une chaîne de caractères",
            'password.min' => "Le mot de passe doit contenir au moins 6 caractères",
            'password.max' => "Le mot de passe ne peut pas dépasser 64 caractères",
            'password.confirmed' => "La confirmation du mot de passe ne correspond pas",

            'license_number.required_if' => "Le numéro de licence est obligatoire si vous êtes licencié.",
            'chip_code.required_if' => "Le numéro de puce est obligatoire si vous êtes licencié.",

            'license_number.string' => "Le numéro de licence doit être une chaine de caracteres.",
            'chip_code.numeric' => "Le numéro de puce doit être un nombre.",

        ]);

        $hashedPassword = bcrypt($validated['password']);
            
        $account = Account::create([
            'COM_PSEUDO' => $validated['username'],
            'COM_NOM' => $validated['name'],
            'COM_PRENOM' => $validated['surname'],
            'COM_DATE_NAISSANCE' => $validated['birthdate'],
            'COM_ADRESSE' => $validated['address'],
            'COM_TELEPHONE' => $validated['phone'],
            'COM_MAIL' => $validated['email'],
            'COM_MDP' => $hashedPassword,
        ]);

        if (!empty($validated['adherentCheck'])){
            Adherent::create([
                'COM_ID' => $account->COM_ID,
                'ADH_NUM_LICENCIE' => $validated['license_number'],
                'ADH_NUM_PUCE' => $validated['chip_code'],
            ]);
        }

        Auth::login($account);

        return redirect()->route('home.index');
    }
}
