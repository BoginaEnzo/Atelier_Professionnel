<?php
namespace App\Controllers;

use App\Models\EvenementModel;
use App\Models\ConcertModel;
use App\Models\FestivalModel;

class EvenementController extends BaseController
{
    public function index()
    {
        $model = new EvenementModel();
        $data['evenements'] = $model->findAll();
        return view('Evenements/evenements_liste', $data);
    }

    public function create()
    {
        return view('Evenements/create');
    }

    public function store()
    {
        $evenementModel = new EvenementModel();
        $concertModel = new ConcertModel();
        $festivalModel = new FestivalModel();

        $validation = $this->validate([
            'nom' => 'required|min_length[3]',
            'description' => 'required|min_length[3]',
            'date_heure_debut' => 'required',
            'date_heure_fin' => 'required',
            'nb_place' => 'required|integer',
            'lieu' => 'required|min_length[3]',
            'type' => 'required|in_list[concert,festival]',
            'image' => 'uploaded[image]|is_image[image]|max_size[image,2048]',
        ]);

        if (!$validation) {
            return view('Evenements/create', ['validation' => $this->validator]);
        }

        $type = $this->request->getPost('type');

        // Gestion de l'image
        $fileImage = $this->request->getFile('image');
        $imageName = null;
        if ($fileImage->isValid() && !$fileImage->hasMoved()) {
            $imageName = $fileImage->getRandomName();
            $fileImage->move(ROOTPATH . 'public/images', $imageName);
        }

        $dataEvenement = [
            'nom' => $this->request->getPost('nom'),
            'description' => $this->request->getPost('description'),
            'date_heure_debut' => $this->request->getPost('date_heure_debut'),
            'date_heure_fin' => $this->request->getPost('date_heure_fin'),
            'nb_place' => $this->request->getPost('nb_place'),
            'lieu' => $this->request->getPost('lieu'),
            'type' => $type,
            'image' => $imageName,
        ];

        $evenementModel->save($dataEvenement);
        $idEvenement = $evenementModel->insertID();

        $fileAudio = $this->request->getFile('audio');
        $audioName = null;
        if ($fileAudio->isValid() && !$fileAudio->hasMoved()) {
            $audioName = $fileAudio->getRandomName();
            $fileAudio->move(ROOTPATH . 'public/Ressource', $audioName);
        }

        if ($type === 'concert') {
            $concertModel->save([
                'id_evenement' => $idEvenement,
                'artiste' => $this->request->getPost('artiste'),
                'style_musique' => $this->request->getPost('style_musique'),
                'duree_minutes' => $this->request->getPost('duree_minutes'),
                'audio' => $audioName,
            ]);
        } elseif ($type === 'festival') {
            $festivalModel->save([
                'id_evenement' => $idEvenement,
                'duree_jours' => $this->request->getPost('duree_jours'),
                'nombre_scenes' => $this->request->getPost('nombre_scenes'),
            ]);
        }


        return redirect()->to('/evenements')->with('success', 'Événement ajouté avec succès.');
    }

    public function edit($id)
    {
        $evenementModel = new EvenementModel();
        $concertModel = new ConcertModel();
        $festivalModel = new FestivalModel();

        $evenement = $evenementModel->find($id);
        if (!$evenement) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Événement introuvable');
        }

        $details = null;
        if ($evenement['type'] === 'concert') {
            $details = $concertModel->find($id);
        } elseif ($evenement['type'] === 'festival') {
            $details = $festivalModel->find($id);
        }

        return view('Evenements/edit', [
            'evenement' => $evenement,
            'details' => $details,
        ]);
    }

    public function update($id)
    {
        $evenementModel = new EvenementModel();
        $concertModel = new ConcertModel();
        $festivalModel = new FestivalModel();

        $validation = $this->validate([
            'nom' => 'required|min_length[3]',
            'description' => 'required|min_length[3]',
            'date_heure_debut' => 'required',
            'date_heure_fin' => 'required',
            'nb_place' => 'required|integer',
            'lieu' => 'required|min_length[3]',
            'type' => 'required|in_list[concert,festival]',
            'image' => 'is_image[image]|max_size[image,2048]',
        ]);

        if (!$validation) {
            $evenement = $evenementModel->find($id);
            $details = null;
            if ($evenement['type'] === 'concert') {
                $details = $concertModel->find($id);
            } elseif ($evenement['type'] === 'festival') {
                $details = $festivalModel->find($id);
            }
            return view('Evenements/edit', [
                'evenement' => $evenement,
                'details' => $details,
                'validation' => $this->validator,
            ]);
        }

        $type = $this->request->getPost('type');

        $dataEvenement = [
            'nom' => $this->request->getPost('nom'),
            'description' => $this->request->getPost('description'),
            'date_heure_debut' => $this->request->getPost('date_heure_debut'),
            'date_heure_fin' => $this->request->getPost('date_heure_fin'),
            'nb_place' => $this->request->getPost('nb_place'),
            'lieu' => $this->request->getPost('lieu'),
            'type' => $type,
        ];

        // Si nouvelle image uploadée
        $fileImage = $this->request->getFile('image');
        if ($fileImage && $fileImage->isValid() && !$fileImage->hasMoved()) {
            $newName = $fileImage->getRandomName();
            $fileImage->move(ROOTPATH . 'public/images', $newName);
            $dataEvenement['image'] = $newName;
        }

        // Si nouveau fichier audio uploadé
        $fileAudio = $this->request->getFile('audio');
        if ($fileAudio && $fileAudio->isValid() && !$fileAudio->hasMoved()) {
            $newNameAudio = $fileAudio->getRandomName();
            $fileAudio->move(ROOTPATH . 'public/Ressource', $newNameAudio);
            $dataEvenement['audio'] = $newNameAudio;
        }

        $evenementModel->update($id, $dataEvenement);

        // Gestion concert/festival
        if ($type === 'concert') {
            $concertData = [
                'id_evenement' => $id,
                'artiste' => $this->request->getPost('artiste'),
                'style_musique' => $this->request->getPost('style_musique'),
                'duree_minutes' => $this->request->getPost('duree_minutes'),
                'audio' => $dataEvenement['audio'] ?? null,
            ];
            if ($concertModel->find($id)) {
                $concertModel->update($id, $concertData);
            } else {
                $concertModel->insert($concertData);
                $festivalModel->delete($id);
            }
        } elseif ($type === 'festival') {
            $festivalData = [
                'id_evenement' => $id,
                'duree_jours' => $this->request->getPost('duree_jours'),
                'nombre_scenes' => $this->request->getPost('nombre_scenes'),
            ];
            if ($festivalModel->find($id)) {
                $festivalModel->update($id, $festivalData);
            } else {
                $festivalModel->insert($festivalData);
                $concertModel->delete($id);
            }
        }

        return redirect()->to('/evenements')->with('success', 'Événement mis à jour avec succès.');
    }

    public function delete($id)
    {
        $evenementModel = new EvenementModel();
        $concertModel = new ConcertModel();
        $festivalModel = new FestivalModel();

        $evenement = $evenementModel->find($id);
        if (!$evenement) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Événement introuvable');
        }

        // Supprimer d'abord dans concert ou festival selon le type
        if ($evenement['type'] === 'concert') {
            $concertModel->where('id_evenement', $id)->delete();
        } elseif ($evenement['type'] === 'festival') {
            $festivalModel->where('id_evenement', $id)->delete();
        }

        // Supprimer ensuite l'événement
        $evenementModel->delete($id);

        return redirect()->to('/evenements')->with('success', 'Événement supprimé avec succès.');
    }


    public function show($id)
    {
        $evenementModel = new EvenementModel();
        $concertModel = new ConcertModel();
        $festivalModel = new FestivalModel();

        $evenement = $evenementModel->find($id);
        if (!$evenement) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Événement introuvable');
        }

        $details = null;
        if ($evenement['type'] === 'concert') {
            $details = $concertModel->find($id);
        } elseif ($evenement['type'] === 'festival') {
            $details = $festivalModel->find($id);
        }

        return view('Evenements/show', [
            'evenement' => $evenement,
            'details' => $details,
        ]);
    }

    public function concerts()
    {
        $model = new EvenementModel();
        
        // Utilise la clause WHERE pour ne récupérer que les lignes où 'type' est 'concert'
        $data = [
            'evenements'  => $model->where('type', 'concert')->findAll(),
        ];

        // IMPORTANT : Charger la nouvelle vue concerts.php
        return view('Evenements/concerts', $data);
    }

    public function festivals()
    {
        $model = new EvenementModel();
        
        // Utilise la clause WHERE pour ne récupérer que les lignes où 'type' est 'concert'
        $data = [
            'evenements'  => $model->where('type', 'festival')->findAll(),
        ];

        // IMPORTANT : Charger la nouvelle vue concerts.php
        return view('Evenements/festivals', $data);
    }

    public function admin()
    {
        $model = new EvenementModel();
        
        // Utilise la clause WHERE pour ne récupérer que les lignes où 'type' est 'concert'
        $data = [
            'evenements'  => $model->findAll(),
        ];

        // IMPORTANT : Charger la nouvelle vue concerts.php
        return view('Evenements/admin', $data);
    }

    public function contact()
    {
        $model = new EvenementModel();
        $data = ['evenements' => $model->findAll()];

        helper(['form']); // Pour gérer le formulaire
        $emailSent = false;

        if ($this->request->getMethod() === 'post') {
            // Récupérer les données du formulaire
            $name = $this->request->getPost('name');
            $email = $this->request->getPost('email');
            $message = $this->request->getPost('message');

            // Valider les champs (optionnel mais recommandé)
            $validation = \Config\Services::validation();
            $validation->setRules([
                'name' => 'required|min_length[2]',
                'email' => 'required|valid_email',
                'message' => 'required|min_length[5]'
            ]);

            if ($validation->withRequest($this->request)->run()) {
                // Configurer l'email
                $emailService = \Config\Services::email();
                $emailService->setFrom($email, $name);
                $emailService->setTo('enzo.bogina30@gmail.com'); // Ton adresse de réception
                $emailService->setSubject('Formulaire Contact - EventDuZ');
                $emailService->setMessage("Nom : $name\nEmail : $email\nMessage : $message");

                if ($emailService->send()) {
                    $data['success'] = "Votre message a bien été envoyé !";
                } else {
                    $data['error'] = "Erreur lors de l'envoi du message.";
                }
            } else {
                $data['error'] = $validation->listErrors();
            }
        }

        return view('Evenements/contact', $data);
    }
}
