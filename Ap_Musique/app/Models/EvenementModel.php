<?php 
namespace App\Models;

use CodeIgniter\Model;

class EvenementModel extends Model
{
    protected $table = 'Evenement';
    protected $primaryKey = 'id_evenement';
    protected $allowedFields = ['nom', 'description', 'date_heure_debut', 'date_heure_fin', 'nb_place', 'lieu', 'type', 'image'];

}
