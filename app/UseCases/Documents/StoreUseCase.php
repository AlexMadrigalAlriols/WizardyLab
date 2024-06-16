<?php

namespace App\UseCases\Documents;

use App\Models\Document;
use App\Models\DocumentFolder;
use App\Models\Note;
use App\Models\User;
use App\UseCases\Core\UseCase;
use Carbon\Carbon;

class StoreUseCase extends UseCase
{
    public function __construct(
        protected DocumentFolder $folder,
        protected User $user_submitted,
        protected ?User $user,
        protected string $title,
        protected string $path,
        protected float $size,
        protected string $mime_type,
        protected array $data = []
    ) {
    }

    public function action(): Document
    {
        $document = Document::create([
            'document_folder_id' => $this->folder->id,
            'user_id' => $this->user?->id,
            'user_upload_id' => $this->user_submitted->id,
            'title' => $this->title,
            'path' => $this->path,
            'size' => $this->size,
            'mime_type' => $this->mime_type,
            'data' => $this->data
        ]);

        return $document;
    }
}
