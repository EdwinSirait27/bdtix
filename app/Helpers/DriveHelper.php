<?php

namespace App\Helpers;

use App\Models\User;

class DriveHelper
{
    /**
     * Ambil identitas folder (NIP / employee_pengenal)
     */
    public static function getFolderIdentity(?User $user): string
    {
        if (!$user) {
            return 'UNKNOWN_guest';
        }

        // ✅ Cek employee_pengenal dulu (NIP)
        if ($user->employee && !empty($user->employee->employee_pengenal)) {
            return $user->employee->employee_pengenal;
        }

        // ✅ Fallback ke employee_name jika NIP kosong
        if ($user->employee && !empty($user->employee->employee_name)) {
            return $user->employee->employee_name;
        }

        // Fallback terakhir
        return 'UNKNOWN_' . $user->id;
    }

    /**
     * Prefix nama file (biar file tetap identifiable)
     */
    public static function getFilePrefix(?User $user): string
    {
        return self::getFolderIdentity($user);
    }
}