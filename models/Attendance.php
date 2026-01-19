<?php

require_once __DIR__ . '/BaseModel.php';

class Attendance extends BaseModel
{
    protected string $table = 'attendance';

    /* =========================
     * CHECK IN / OUT
     * ========================= */

    public function checkIn(int $userId): bool
    {
        // cek apakah user sudah check-in hari ini
        if ($this->getTodayByUser($userId)) {
            return false; // sudah check-in hari ini
        }

        // insert tanpa 'id', biarkan AUTO_INCREMENT
        return $this->insert([
            'user_id' => $userId,
            'check_in' => date('Y-m-d H:i:s')
        ]);
    }

    public function checkOut(int $userId): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE attendance 
            SET check_out = NOW()
            WHERE id = :id
            AND check_out IS NULL"
        );

        return $stmt->execute(['id' => $userId]);
    }

    /* =========================
     * QUERY
     * ========================= */

    public function hasOpenAttendance(int $userId): bool
    {
        $stmt = $this->db->prepare(
            "SELECT id FROM attendance
            WHERE id = :id
            AND check_out IS NULL"
        );
        $stmt->execute(['id' => $userId]);

        return (bool) $stmt->fetch();
    }

    public function getByUser(int $userId): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM attendance
            WHERE id = :id
            ORDER BY check_in DESC"
        );
        $stmt->execute(['id' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll(): array
    {
        $stmt = $this->db->query(
            "SELECT a.*, u.name, u.email
            FROM attendance a
            JOIN users u ON u.id = a.id
            ORDER BY a.check_in DESC"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTodayByUser(int $userId): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM attendance
            WHERE id = :id
            AND DATE(check_in) = CURDATE()"
        );
        $stmt->execute(['id' => $userId]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function countTodayCheckIns(int $userId): int
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) as count FROM attendance
            WHERE user_id = :user_id
            AND DATE(check_in) = CURDATE()"
        );
        $stmt->execute(['user_id' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) $result['count'];
    }
}

