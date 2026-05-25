<?php
namespace App\Models;

use CodeIgniter\Model;

class FestivalModel extends Model
{
    protected $table = 'festival';
    protected $primaryKey = 'id_evenement';
    public $useAutoIncrement = false;

    protected $allowedFields = [
        'id_evenement',
        'duree_jours',
        'nombre_scenes',
    ];
}
