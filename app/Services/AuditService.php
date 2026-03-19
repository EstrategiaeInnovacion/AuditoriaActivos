<?php

namespace App\Services;

use App\Models\AuditLog;

class AuditService
{
    public static function log(
        string $action,
        ?string $entityType = null,
        ?int $entityId = null,
        ?array $metadata = null
    ): AuditLog {
        $request = request();

        return AuditLog::log(
            action: $action,
            entityType: $entityType,
            entityId: $entityId,
            metadata: $metadata,
            ipAddress: $request?->ip(),
            userAgent: $request?->userAgent()
        );
    }

    public static function credentialAccessed(int $deviceId, string $deviceName): AuditLog
    {
        return self::log(
            action: 'credential_accessed',
            entityType: 'Device',
            entityId: $deviceId,
            metadata: ['device_name' => $deviceName]
        );
    }

    public static function documentDownloaded(int $documentId, string $fileName): AuditLog
    {
        return self::log(
            action: 'document_downloaded',
            entityType: 'DeviceDocument',
            entityId: $documentId,
            metadata: ['file_name' => $fileName]
        );
    }

    public static function documentDeleted(int $documentId, string $fileName): AuditLog
    {
        return self::log(
            action: 'document_deleted',
            entityType: 'DeviceDocument',
            entityId: $documentId,
            metadata: ['file_name' => $fileName]
        );
    }

    public static function deviceDeleted(int $deviceId, string $deviceName): AuditLog
    {
        return self::log(
            action: 'device_deleted',
            entityType: 'Device',
            entityId: $deviceId,
            metadata: ['device_name' => $deviceName]
        );
    }

    public static function credentialExport(int $deviceCount): AuditLog
    {
        return self::log(
            action: 'credentials_exported',
            metadata: ['device_count' => $deviceCount]
        );
    }

    public static function deviceAssigned(int $deviceId, int $userId, string $userName): AuditLog
    {
        return self::log(
            action: 'device_assigned',
            entityType: 'Device',
            entityId: $deviceId,
            metadata: ['assigned_to_user_id' => $userId, 'assigned_to_name' => $userName]
        );
    }

    public static function deviceReturned(int $deviceId): AuditLog
    {
        return self::log(
            action: 'device_returned',
            entityType: 'Device',
            entityId: $deviceId
        );
    }
}
