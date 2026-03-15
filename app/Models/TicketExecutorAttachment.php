<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class TicketExecutorAttachment extends Model
{
    use SoftDeletes;

    protected $table = 'ticket_executor_attachments';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'ticket_id',
        'executor_id',
        'file_name',
        'file_path',
        'original_name',
        'mime_type',
        'size',
        'status',
        'drive_file_id',
        'drive_folder_id',
        'web_view_link',
        'web_content_link',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->id ??= (string) Str::uuid();
        });
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Tickets::class);
    }

    public function executor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'executor_id');
    }

    public function getHumanSizeAttribute(): string
    {
        if ($this->size >= 1_048_576) {
            return round($this->size / 1_048_576, 2) . ' MB';
        }
        return round($this->size / 1024, 2) . ' KB';
    }

    public function getIsImageAttribute(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }
}