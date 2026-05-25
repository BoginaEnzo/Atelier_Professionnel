<?php
namespace App\Models;
use CodeIgniter\Model;

class UtilisateurModel extends Model
{
    protected $table = 'utilisateur';
    protected $primaryKey = 'id_utilisateur';

    protected $allowedFields = [
        'prenom', 'nom', 'email', 'mot_de_passe', 'role'
    ];

    protected $useTimestamps = false;
}
