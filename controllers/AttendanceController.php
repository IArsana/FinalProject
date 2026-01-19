<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Attendance.php';

class AttendanceController extends BaseController
{
    private Attendance $attendance;

    public function __construct()
    {
        $this->attendance = new Attendance();
    }

    public function checkIn(): void
    {
        $user = $this->auth();

        $todayCount = $this->attendance->countTodayCheckIns($user['id']);

        if ($todayCount > 0) {
            jsonResponse(
                false,
                "Already checked in {$todayCount} time(s) today",
                400
            );
        }

        $this->attendance->checkIn($user['id']);
        jsonResponse(true, 'Check-in success');
    }

    public function checkOut(): void
    {
        $user = $this->auth();
        $this->attendance->checkOut($user['id']);

        jsonResponse(true, 'Check-out success');
    }

    public function me(): void
    {
        $user = $this->auth();
        $data = $this->attendance->getByUser($user['id']);

        jsonResponse(true, 'Attendance data', 200, $data);
    }

    public function all(): void
    {
        $this->auth('admin');
        $data = $this->attendance->getAll();

        jsonResponse(true, 'All attendance', 200, $data);
    }

}