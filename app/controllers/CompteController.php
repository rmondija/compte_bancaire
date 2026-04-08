<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Compte;
use Throwable;

class CompteController extends Controller
{
public function index()
    {
        $comptes = (new Compte())->all();
        $contrats = (new \App\Models\Contrat())->all();
        $clients = (new \App\Models\Client())->all();
        $search = trim((string)($_GET['q'] ?? ''));
        $openCompteId = isset($_GET['open']) ? (int)$_GET['open'] : null;
        
        // Créer un mapping des clients par ID pour un accès rapide
        $clientsMap = [];
        foreach ($clients as $client) {
            $clientsMap[$client['id']] = $client;
        }

        if ($search !== '') {
            $comptes = array_values(array_filter($comptes, static function ($compte) use ($clientsMap, $search) {
                $client = $clientsMap[$compte['client_id']] ?? [];
                $haystack = trim(
                    (string)($client['nom'] ?? '') . ' ' .
                    (string)($client['prenom'] ?? '') . ' ' .
                    (string)($client['email'] ?? '')
                );
                return stripos($haystack, $search) !== false;
            }));
        }

        usort($comptes, static function ($a, $b) use ($clientsMap) {
            $nameA = mb_strtolower(trim(
                (string)($clientsMap[$a['client_id']]['nom'] ?? '') . ' ' .
                (string)($clientsMap[$a['client_id']]['prenom'] ?? '')
            ));
            $nameB = mb_strtolower(trim(
                (string)($clientsMap[$b['client_id']]['nom'] ?? '') . ' ' .
                (string)($clientsMap[$b['client_id']]['prenom'] ?? '')
            ));

            if ($nameA === $nameB) {
                return ((string)($a['numero_compte'] ?? '')) <=> ((string)($b['numero_compte'] ?? ''));
            }

            return $nameA <=> $nameB;
        });
        
        $this->render('compte/index', [
            'items' => $comptes,
            'clientsMap' => $clientsMap,
            'contrats' => $contrats,
            'search' => $search,
            'openCompteId' => $openCompteId,
        ]);
    }

    public function show(int $id)
    {
        $data = (new Compte())->find($id);
        $client = (new \App\Models\Client())->find($data['client_id'] ?? 0);
        $this->render('compte/show', ['data' => $data, 'client' => $client]);
    }

    public function create()
    {
        $clients = (new \App\Models\Client())->all();
        $data = $_SESSION['old'] ?? null;
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['old'], $_SESSION['error']);

        $this->render('compte/form', compact('clients', 'data', 'error'));
    }

    public function store()
    {
        try {
            $payload = [
                'client_id' => (int) ($_POST['client_id'] ?? 0),
                'numero_compte' => trim((string) ($_POST['numero_compte'] ?? '')),
                'solde' => $this->normalizeAmount($_POST['solde'] ?? '0'),
                'type_compte' => trim((string) ($_POST['type_compte'] ?? '')),
            ];

            if ($payload['client_id'] <= 0 || $payload['numero_compte'] === '' || $payload['type_compte'] === '') {
                throw new \InvalidArgumentException('Veuillez remplir tous les champs obligatoires.');
            }

            $typeCompte = mb_strtolower($payload['type_compte']);
            if (in_array($typeCompte, ['épargne', 'epargne'], true) && $payload['solde'] < 100) {
                throw new \InvalidArgumentException('Le solde doit etre superieur ou egal a 100,00 EUR pour un compte epargne.');
            }

            (new Compte())->create($payload);
            header("Location: ?controller=compte&action=index");
            exit;
        } catch (Throwable $e) {
            $_SESSION['error'] = "Erreur lors de l'enregistrement du compte : " . $e->getMessage();
            $_SESSION['old'] = $_POST;
            header("Location: ?controller=compte&action=create");
            exit;
        }
    }

    public function edit(int $id)
    {
        $data = (new Compte())->find($id);
        $clients = (new \App\Models\Client())->all();

        if (isset($_SESSION['old']) && is_array($_SESSION['old'])) {
            $data = array_merge($data ?? [], $_SESSION['old']);
            unset($_SESSION['old']);
        }

        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']);

        $this->render('compte/form', get_defined_vars());
    }

    public function update(int $id)
    {
        try {
            $payload = [
                'client_id' => (int) ($_POST['client_id'] ?? 0),
                'numero_compte' => trim((string) ($_POST['numero_compte'] ?? '')),
                'solde' => $this->normalizeAmount($_POST['solde'] ?? '0'),
                'type_compte' => trim((string) ($_POST['type_compte'] ?? '')),
            ];

            if ($payload['client_id'] <= 0 || $payload['numero_compte'] === '' || $payload['type_compte'] === '') {
                throw new \InvalidArgumentException('Veuillez remplir tous les champs obligatoires.');
            }

            $typeCompte = mb_strtolower($payload['type_compte']);
            if (in_array($typeCompte, ['épargne', 'epargne'], true) && $payload['solde'] < 100) {
                throw new \InvalidArgumentException('Le solde doit etre superieur ou egal a 100,00 EUR pour un compte epargne.');
            }

            (new Compte())->update($id, $payload);
            header("Location: ?controller=compte&action=index");
            exit;
        } catch (Throwable $e) {
            $_SESSION['error'] = "Erreur lors de la mise à jour du compte : " . $e->getMessage();
            $_SESSION['old'] = $_POST;
            header("Location: ?controller=compte&action=edit&id={$id}");
            exit;
        }
    }

    public function delete(int $id)
    {
        (new Compte())->delete($id);
        header("Location: ?controller=compte&action=index");
    }

    private function normalizeAmount(string $rawValue): float
    {
        $cleaned = preg_replace('/[^0-9,.-]/', '', $rawValue) ?? '0';
        $normalized = str_replace(',', '.', $cleaned);
        return (float) $normalized;
    }
}




