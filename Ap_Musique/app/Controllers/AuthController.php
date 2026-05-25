<?php
namespace App\Controllers;
use App\Models\UtilisateurModel;

class AuthController extends BaseController
{
    public function login()
    {
        return view('auth/login');
    }

    public function auth()
    {
        $session = session();
        $model = new UtilisateurModel();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $model->where('email', $email)->first();

        if ($user && password_verify($password, $user['mot_de_passe'])) {
            $session->set([
                'userid' => $user['id_utilisateur'],
                'email' => $user['email'],
                'role' => $user['role'],
                'isLoggedIn' => true
            ]);
            return redirect()->to('/evenements')->with('success', 'Connexion réussie !');
        } else {
            $session->setFlashdata('error', 'Email ou mot de passe incorrect.');
            return redirect()->back()->withInput();
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Vous avez été déconnecté.');
    }

    public function register()
    {
        return view('auth/register');
    }

    public function store()
    {
        $session = session();
        $model = new UtilisateurModel();

        $rules = [
            'prenom' => 'required|min_length[2]|max_length[100]',
            'nom' => 'required|min_length[2]|max_length[100]',
            'email' => 'required|valid_email|is_unique[utilisateur.email]',
            'password' => 'required|min_length[8]'
        ];

        if (!$this->validate($rules)) {
            return view('auth/register', [
                'validation' => $this->validator,
            ]);
        }

        $passwordHash = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);

        $data = [
            'prenom' => $this->request->getPost('prenom'),
            'nom' => $this->request->getPost('nom'),
            'email' => $this->request->getPost('email'),
            'mot_de_passe' => $passwordHash,
            'role' => 'client'  // rôle client par défaut à la création du compte
        ];

        $model->insert($data);

        $session->setFlashdata('success', 'Compte créé avec succès ! Veuillez vous connecter avec votre email et mot de passe.');

        return redirect()->to('/login');
    }
}
