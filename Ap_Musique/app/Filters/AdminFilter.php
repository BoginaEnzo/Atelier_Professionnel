<?php 
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        // Vérifie si l'utilisateur est connecté ET s'il a le rôle 'admin'
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin')
        {
            // Redirection vers la liste publique des événements
            return redirect()->to('/evenements')->with('error', 'Accès administrateur requis pour cette action.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Rien ici
    }
}