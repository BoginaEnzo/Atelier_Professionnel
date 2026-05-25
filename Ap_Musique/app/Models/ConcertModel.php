<?php
namespace App\Models;

use CodeIgniter\Model;

class ConcertModel extends Model
{
    protected $table = 'concert';
    protected $primaryKey = 'id_evenement'; // clé primaire, même que la table parent
    public $useAutoIncrement = false; // car id_evenement vient de evenement et n’est pas auto-incrémenté ici

    protected $allowedFields = [
        'id_evenement',
        'artiste',
        'style_musique',
        'duree_minutes',
        'audio',
    ];
}
