<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Client;
use Exception;

class ClientController extends Controller
{
public function index()
    {
        $clients = (new Client())->all();
        $search = trim((string)($_GET['q'] ?? ''));

        if ($search !== '') {
            $clients = array_values(array_filter($clients, static function ($client) use ($search) {
                $haystack = trim(
                    (string)($client['nom'] ?? '') . ' ' .
                    (string)($client['prenom'] ?? '') . ' ' .
                    (string)($client['email'] ?? '')
                );
                return stripos($haystack, $search) !== false;
            }));
        }

        usort($clients, static function ($a, $b) {
            $nameA = mb_strtolower(trim((string)($a['nom'] ?? '') . ' ' . (string)($a['prenom'] ?? '')));
            $nameB = mb_strtolower(trim((string)($b['nom'] ?? '') . ' ' . (string)($b['prenom'] ?? '')));
            return $nameA <=> $nameB;
        });

        $openClientId = isset($_GET['open']) ? (int) $_GET['open'] : null;

        // Pour chaque client, récupérer ses comptes et contrats
        foreach ($clients as &$client) {
            $client['comptes'] = (new \App\Models\Compte())->all();
            $client['contrats'] = (new \App\Models\Contrat())->all();
        }
        
        $this->render('client/index', ['items' => $clients, 'openClientId' => $openClientId, 'search' => $search]);
    }

    public function show(int $id)
    {
        $data = (new Client())->find($id);
        $this->render('client/show', compact('data'));
    }

    public function create()
    {
        $this->render('client/form', get_defined_vars());
    }

    public function store()
    {
        try {
            // Debug: log the received data
            error_log("Store called with POST data: " . print_r($_POST, true));

            (new Client())->create($_POST);

            // Clean output buffer if any
            if (ob_get_length() !== false && ob_get_length() > 0) {
                ob_end_clean();
            }

            // Redirect to index with success message
            header("Location: ?controller=client&action=index&success=1");
            exit;
        } catch (Exception $e) {
            error_log("Error in store: " . $e->getMessage());
            die("Erreur lors de l'enregistrement: " . $e->getMessage());
        }
    }

    public function edit(int $id)
    {
        $data = (new Client())->find($id);
        $this->render('client/form', get_defined_vars());
    }

    public function update(int $id)
    {
        (new Client())->update($id, $_POST);
        header("Location: ?controller=client&action=index&open={$id}");
    }

    public function delete(int $id)
    {
        (new Client())->delete($id);
        header("Location: ?controller=client&action=index");
    }
}




