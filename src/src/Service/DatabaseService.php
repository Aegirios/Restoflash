<?php
namespace App\Service;

use Doctrine\DBAL\Connection;

use PDO;
use PDOException;

class DatabaseService
{
    public function testConnectionParameters(array $data): array
    {
        try {
            // Create a replacement string for the database parameters
            $pdo = new \PDO(
                $this->buildDsn($data),
                $data['user'],
                $data['password']
            );
            // Si la connexion réussit, renvoyer un tableau avec le statut et éventuellement d'autres informations
            return ['success' => true, 'message' => 'La connexion à la base de données a réussi.'];
        } catch (PDOException $e) {
            // Si la connexion échoue, renvoyer un tableau avec le statut et le message d'erreur
            return ['success' => false, 'message' => 'La connexion à la base de données a échoué. Message d\'erreur : ' . $e->getMessage()];
        }
    }

    private function buildDsn(array $data): string
    {
        // Construire le DSN (Data Source Name) en fonction du type de base de données
        switch ($data['sgbd']) {
            case 'mysql':
                return "mysql:host={$data['host']};dbname={$data['database']};port={$data['port']}";
            case 'pgsql':
                return "pgsql:host={$data['host']};dbname={$data['database']};port={$data['port']}";
            default:
                throw new \InvalidArgumentException('Driver de base de données non pris en charge.');
        }
    }
}