<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Contrat;
use Throwable;

class ContratController extends Controller
{
public function index()
    {
        $items = (new Contrat())->all();
        $search = trim((string)($_GET['q'] ?? ''));
        $comptes = (new \App\Models\Compte())->all();
        $clients = (new \App\Models\Client())->all();

        $comptesMap = [];
        foreach ($comptes as $compte) {
            $comptesMap[$compte['id']] = $compte;
        }

        $clientsMap = [];
        foreach ($clients as $client) {
            $clientsMap[$client['id']] = $client;
        }

        foreach ($items as &$item) {
            $clientName = '';
            if (isset($comptesMap[$item['compte_id']])) {
                $compte = $comptesMap[$item['compte_id']];
                $clientId = $compte['client_id'];
                $clientName = isset($clientsMap[$clientId]) ? $clientsMap[$clientId]['nom'] . ' ' . $clientsMap[$clientId]['prenom'] : '';
            }
            $item['client_name'] = $clientName;
        }
        unset($item);

        if ($search !== '') {
            $items = array_values(array_filter($items, static function ($item) use ($search) {
                return stripos((string)($item['client_name'] ?? ''), $search) !== false;
            }));
        }

        usort($items, static function ($a, $b) {
            $nameA = mb_strtolower(trim((string)($a['client_name'] ?? '')));
            $nameB = mb_strtolower(trim((string)($b['client_name'] ?? '')));

            if ($nameA === $nameB) {
                return ((int)($a['id'] ?? 0)) <=> ((int)($b['id'] ?? 0));
            }

            return $nameA <=> $nameB;
        });

        $this->render('contrat/index', compact('items', 'search'));
    }

    public function show(int $id)
    {
        $data = (new Contrat())->find($id);
        $clientName = '';

        if ($data) {
            $compte = (new \App\Models\Compte())->find($data['compte_id'] ?? 0);
            if ($compte) {
                $client = (new \App\Models\Client())->find($compte['client_id'] ?? 0);
                if ($client) {
                    $clientName = $client['nom'] . ' ' . $client['prenom'];
                }
            }
        }

        $this->render('contrat/show', compact('data', 'clientName'));
    }

    public function create()
    {
        $comptes = (new \App\Models\Compte())->all();
        $clients = (new \App\Models\Client())->all();

        $comptes = $this->buildComptesWithClientName($comptes, $clients);
        $data = $_SESSION['old'] ?? null;
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['old'], $_SESSION['error']);

        $this->render('contrat/form', compact('comptes', 'data', 'error'));
    }

    public function store()
    {
        try {
            $noExpiration = isset($_POST['no_expiration']);
            $payload = [
                'compte_id' => (int) ($_POST['compte_id'] ?? 0),
                'type_contrat' => trim((string) ($_POST['type_contrat'] ?? '')),
                'montant' => $this->normalizeAmount($_POST['montant'] ?? '0'),
                'date_signature' => trim((string) ($_POST['date_signature'] ?? '')),
                'date_expiration' => $this->normalizeOptionalDate($_POST['date_expiration'] ?? null),
            ];

            if (
                $payload['compte_id'] <= 0 ||
                $payload['type_contrat'] === '' ||
                $payload['date_signature'] === ''
            ) {
                throw new \InvalidArgumentException('Veuillez remplir tous les champs obligatoires.');
            }

            if ($payload['montant'] < 100) {
                throw new \InvalidArgumentException('Le montant doit etre superieur ou egal a 100,00 EUR.');
            }

            if (!$noExpiration && $payload['date_expiration'] === null) {
                throw new \InvalidArgumentException("La date d'expiration est obligatoire si l'option \"Pas de date d'expiration\" n'est pas cochée.");
            }

            (new Contrat())->create($payload);
            header("Location: ?controller=contrat&action=index");
            exit;
        } catch (Throwable $e) {
            $_SESSION['error'] = "Erreur lors de l'enregistrement du contrat : " . $e->getMessage();
            $_SESSION['old'] = $_POST;
            header("Location: ?controller=contrat&action=create");
            exit;
        }
    }

    public function edit(int $id)
    {
        $data = (new Contrat())->find($id);
        $comptes = (new \App\Models\Compte())->all();
        $clients = (new \App\Models\Client())->all();

        $comptes = $this->buildComptesWithClientName($comptes, $clients);

        if (isset($_SESSION['old']) && is_array($_SESSION['old'])) {
            $data = array_merge($data ?? [], $_SESSION['old']);
            unset($_SESSION['old']);
        }

        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']);

        $this->render('contrat/form', compact('data', 'comptes', 'error'));
    }

    public function update(int $id)
    {
        try {
            $noExpiration = isset($_POST['no_expiration']);
            $payload = [
                'compte_id' => (int) ($_POST['compte_id'] ?? 0),
                'type_contrat' => trim((string) ($_POST['type_contrat'] ?? '')),
                'montant' => $this->normalizeAmount($_POST['montant'] ?? '0'),
                'date_signature' => trim((string) ($_POST['date_signature'] ?? '')),
                'date_expiration' => $this->normalizeOptionalDate($_POST['date_expiration'] ?? null),
            ];

            if (
                $payload['compte_id'] <= 0 ||
                $payload['type_contrat'] === '' ||
                $payload['date_signature'] === ''
            ) {
                throw new \InvalidArgumentException('Veuillez remplir tous les champs obligatoires.');
            }

            if ($payload['montant'] < 100) {
                throw new \InvalidArgumentException('Le montant doit etre superieur ou egal a 100,00 EUR.');
            }

            if (!$noExpiration && $payload['date_expiration'] === null) {
                throw new \InvalidArgumentException("La date d'expiration est obligatoire si l'option \"Pas de date d'expiration\" n'est pas cochée.");
            }

            (new Contrat())->update($id, $payload);
            header("Location: ?controller=contrat&action=index");
            exit;
        } catch (Throwable $e) {
            $_SESSION['error'] = "Erreur lors de la mise à jour du contrat : " . $e->getMessage();
            $_SESSION['old'] = $_POST;
            header("Location: ?controller=contrat&action=edit&id={$id}");
            exit;
        }
    }

    public function delete(int $id)
    {
        (new Contrat())->delete($id);
        header("Location: ?controller=contrat&action=index");
    }

    private function normalizeAmount(string $rawValue): float
    {
        $cleaned = preg_replace('/[^0-9,.-]/', '', $rawValue) ?? '0';
        $normalized = str_replace(',', '.', $cleaned);
        return (float) $normalized;
    }

    private function normalizeOptionalDate($rawValue): ?string
    {
        $value = trim((string) ($rawValue ?? ''));

        return $value === '' ? null : $value;
    }

    private function buildComptesWithClientName(array $comptes, array $clients): array
    {
        $clientsMap = [];
        foreach ($clients as $client) {
            $clientsMap[$client['id']] = $client;
        }

        foreach ($comptes as &$compte) {
            $clientName = isset($clientsMap[$compte['client_id']])
                ? $clientsMap[$compte['client_id']]['nom'] . ' ' . $clientsMap[$compte['client_id']]['prenom']
                : '';
            $compte['client_name'] = $clientName;
        }

        return $comptes;
    }
}




