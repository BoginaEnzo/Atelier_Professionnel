<?php
namespace App\Controllers;

use App\Models\EvenementModel;
use App\Models\InscriptionModel;
use CodeIgniter\Controller;

class InscriptionController extends BaseController
{
    public function formulaire($id_evenement)
    {
        $evenementModel = new EvenementModel();
        $evenement = $evenementModel->find($id_evenement);

        if (!$evenement) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Événement introuvable');
        }

        $places_restantes = $evenement['nb_place'] - ($evenement['places_reservees'] ?? 0);

        return view('inscriptions/formulaire', [
            'evenement' => $evenement,
            'places_restantes' => $places_restantes,
        ]);
    }

    public function reserver($id_evenement)
    {
        $session = session();

        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Vous devez être connecté pour réserver.');
        }

        $evenementModel = new EvenementModel();
        $inscriptionModel = new InscriptionModel();

        $evenement = $evenementModel->find($id_evenement);
        if (!$evenement) {
            return redirect()->to('/evenements')->with('error', 'Événement non trouvé.');
        }

        $nombre_places = (int) $this->request->getPost('nombre_places');
        $nom = $this->request->getPost('nom');
        $prenom = $this->request->getPost('prenom');  
        $id_utilisateur = $session->get('userid');

        $places_restantes = $evenement['nb_place'] - ($evenement['places_reservees'] ?? 0);

        $validationRules = [
            'nom' => 'required|min_length[2]',
            'prenom' => 'required|min_length[2]',
            'nombre_places' => 'required|integer|greater_than[0]|less_than_equal_to[' . $places_restantes . ']',
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $inscriptionModel->insert([
            'id_utilisateur' => $id_utilisateur,
            'id_evenement' => $id_evenement,
            'nombre_places' => $nombre_places,
        ]);

        // Envoi email
        $email = service('email');
        $utilisateurModel = new \App\Models\UtilisateurModel();
        $utilisateur = $utilisateurModel->find($id_utilisateur);

        $email->setTo($utilisateur['email']);
        $email->setFrom('no-reply@tonsite.com', 'Ton Site');
        $email->setSubject('Confirmation de réservation');
        $email->setMessage(view('emails/confirmation_reservation', [
            'utilisateur' => $utilisateur,
            'evenement' => $evenement,
            'nombre_places' => $nombre_places,
        ]));

        if (!$email->send()) {
            log_message('error', 'Erreur envoi mail : ' . $email->printDebugger(['headers']));
        }

        return redirect()->to('/inscriptions/confirmation');
    }

    public function confirmation()
    {
        return view('inscriptions/confirmation');
    }
}
