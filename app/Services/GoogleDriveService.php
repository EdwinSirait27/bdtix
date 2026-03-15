<?php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class GoogleDriveService
{
    protected Client $client;
    protected Drive $driveService;

    const ROOT_FOLDER_NAME     = 'Ticketing Attachments';
    const USER_FOLDER_NAME     = 'User Attachments';
    const EXECUTOR_FOLDER_NAME = 'Executor Attachments';

    const VALID_CATEGORIES = [
        'Hardware & Software',
        'Account & Access',
        'Network',
        'Others',
    ];

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setClientId(config('filesystems.disks.google.clientId'));
        $this->client->setClientSecret(config('filesystems.disks.google.clientSecret'));
        $this->client->refreshToken(config('filesystems.disks.google.refreshToken'));
        $this->client->addScope(Drive::DRIVE);
        $this->driveService = new Drive($this->client);
    }

    /**
     * Upload attachment ke Google Drive
     */
    public function uploadAttachment(
        UploadedFile $file,
        string $folderIdentity,
        string $category,
        string $type = 'user',
        ?string $filePrefix = null
    ): array
    {
        if (!in_array($category, self::VALID_CATEGORIES)) {
            throw new \InvalidArgumentException("Invalid category: {$category}");
        }

        $folderId = $this->getOrCreateFolder($folderIdentity, $category, $type);

        $fileName = $this->buildFileName($filePrefix ?: $folderIdentity, $file->getClientOriginalName());

        $fileMetadata = new DriveFile([
            'name'    => $fileName,
            'parents' => [$folderId],
        ]);

        $content = file_get_contents($file->getRealPath());

        $uploaded = $this->driveService->files->create($fileMetadata, [
            'data'       => $content,
            'mimeType'   => $file->getMimeType(),
            'uploadType' => 'multipart',
            'fields'     => 'id, name, mimeType, size, webViewLink, webContentLink',
        ]);

        $this->setPublicReadPermission($uploaded->id);

        $typeFolder = $type === 'executor' ? self::EXECUTOR_FOLDER_NAME : self::USER_FOLDER_NAME;

        return [
            'drive_file_id'    => $uploaded->id,
            'original_name'    => $uploaded->name,
            'mime_type'        => $uploaded->mimeType,
            'size'             => $uploaded->size,
            'web_view_link'    => $uploaded->webViewLink,
            'web_content_link' => $uploaded->webContentLink,
            'folder_id'        => $folderId,
            'folder_path'      => self::ROOT_FOLDER_NAME . "/{$folderIdentity}/{$category}/{$typeFolder}",
        ];
    }

    /**
     * Upload attachment dari path file sementara
     */
    public function uploadFromPath(
        string $filePath,
        string $fileName,
        string $mimeType,
        string $folderIdentity,
        string $category,
        string $type = 'user',
        ?string $filePrefix = null
    ): array {
        $folderId = $this->getOrCreateFolder($folderIdentity, $category, $type);

        $fileMetadata = new DriveFile([
            'name'    => $this->buildFileName($filePrefix ?: $folderIdentity, $fileName),
            'parents' => [$folderId],
        ]);

        $content = file_get_contents($filePath);

        $uploaded = $this->driveService->files->create($fileMetadata, [
            'data'       => $content,
            'mimeType'   => $mimeType,
            'uploadType' => 'multipart',
            'fields'     => 'id, name, mimeType, size, webViewLink, webContentLink',
        ]);

        $this->setPublicReadPermission($uploaded->id);

        return [
            'drive_file_id'    => $uploaded->id,
            'original_name'    => $fileName,
            'mime_type'        => $uploaded->mimeType,
            'size'             => $uploaded->size,
            'web_view_link'    => $uploaded->webViewLink,
            'web_content_link' => $uploaded->webContentLink,
            'folder_id'        => $folderId,
        ];
    }

    /**
     * Hapus file dari Google Drive
     */
    public function deleteFile(string $driveFileId): bool
    {
        try {
            $this->driveService->files->delete($driveFileId);
            return true;
        } catch (\Exception $e) {
            Log::error('Gagal hapus file dari Drive: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Dapatkan atau buat folder sesuai struktur:
     *
     * Ticketing Attachments/
     * └── {NIP}/
     *     └── {Category}/
     *         ├── User Attachments/
     *         └── Executor Attachments/
     */
    protected function getOrCreateFolder(string $nip, string $category, string $type = 'user'): string
    {
        // Step 1: Cari atau buat folder "Ticketing Attachments" (di root Drive)
        $rootId = $this->findOrCreateFolder(self::ROOT_FOLDER_NAME, null);

        // Step 2: Cari atau buat folder {NIP} di dalam "Ticketing Attachments"
        $nipId = $this->findOrCreateFolder($nip, $rootId);

        // Step 3: Cari atau buat folder {Category} di dalam {NIP}
        $categoryId = $this->findOrCreateFolder($category, $nipId);

        // Step 4: Cari atau buat folder "User Attachments" atau "Executor Attachments"
        $typeFolderName = $type === 'executor' ? self::EXECUTOR_FOLDER_NAME : self::USER_FOLDER_NAME;
        $typeId = $this->findOrCreateFolder($typeFolderName, $categoryId);

        return $typeId;
    }

    protected function buildFileName(string $prefix, string $originalName): string
    {
        return $prefix . '_' . $originalName;
    }

    /**
     * Cari atau buat folder (idempotent — tidak duplikat)
     */
    protected function findOrCreateFolder(string $name, ?string $parentId = null): string
    {
        $escapedName = addslashes($name);
        $query       = "name='{$escapedName}' and mimeType='application/vnd.google-apps.folder' and trashed=false";

        if ($parentId) {
            $query .= " and '{$parentId}' in parents";
        }

        $results = $this->driveService->files->listFiles([
            'q'      => $query,
            'fields' => 'files(id, name)',
        ]);

        if (count($results->getFiles()) > 0) {
            return $results->getFiles()[0]->getId();
        }

        $folderMetadata = new DriveFile([
            'name'     => $name,
            'mimeType' => 'application/vnd.google-apps.folder',
        ]);

        if ($parentId) {
            $folderMetadata->setParents([$parentId]);
        }

        $folder = $this->driveService->files->create($folderMetadata, [
            'fields' => 'id',
        ]);

        return $folder->getId();
    }

    /**
     * Set file bisa diakses siapapun dengan link
     */
    protected function setPublicReadPermission(string $fileId): void
    {
        $permission = new Drive\Permission([
            'type' => 'anyone',
            'role' => 'reader',
        ]);

        $this->driveService->permissions->create($fileId, $permission);
    }
}
