<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $activeTaskIds = auth()->user()->activeTaskTimers()->pluck('task_id')->toArray();

        return [
            'id' => $this->id,
            'code' => $this->code,
            'title' => $this->title,
            'description' => $this->description,
            'project' => [
                'id' => $this->project?->id,
                'name' => $this->project?->name,
            ],
            'users' => UserResource::collection($this->users) ?? [],
            'subtasks' => TaskResource::collection($this->subtasks) ?? [],
            'duedate' => $this->duedate?->format('Y-m-d'),
            'start_date' => $this->start_date?->format('Y-m-d'),
            'limit_date' => $this->limit_date,
            'total_hours' => $this->total_hours,
            'limit_hours' => $this->limit_hours,
            'timer' => $this->timer,
            'priority' => $this->priority,
            'status' => [
                'id' => $this->status->id,
                'title' => $this->status->title,
                'styles' => [
                    'color' => $this->status->data['color'],
                    'background-color' => $this->status->data['background'],
                ],
            ],
            'comments' => CommentResource::collection($this->comments) ?? [],
            'labels' => StatusResource::collection($this->labels) ?? [],
            'files' => FileResource::collection($this->files) ?? [],
            'expanded' => (bool) $this->subtasks->pluck('id')->intersect($activeTaskIds)->isNotEmpty(),
            'task_id' => $this->task_id,
            'is_clockIn' => $this->is_clockIn,
            'is_overdue' => $this->is_overdue,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
