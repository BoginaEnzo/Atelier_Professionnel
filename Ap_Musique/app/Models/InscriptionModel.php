<?php
namespace App\Models;

use CodeIgniter\Model;

class InscriptionModel extends Model
{
    protected $table = 'inscription';
    protected $primaryKey = 'id_inscription';  // adapte si différent
    protected $allowedFields = ['id_utilisateur', 'id_evenement', 'nombre_places'];
}
