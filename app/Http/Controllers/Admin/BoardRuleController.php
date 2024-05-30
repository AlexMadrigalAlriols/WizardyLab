<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ApiResponse;
use App\Helpers\BoardAutomationHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\BoardRules\StoreRequest;
use App\Http\Requests\BoardRules\UpdateRequest;
use App\Models\BoardRule;
use App\Models\Project;
use App\UseCases\BoardRules\StoreUseCase;
use App\UseCases\BoardRules\UpdateUseCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BoardRuleController extends Controller
{
    public function index(Request $request, Project $project)
    {
        $user = Auth::user();
        $projectRules = $project->boardRules()->pluck('id')->toArray();
        $boardRules = BoardRule::where('type', 'TRIGGER')->get();

        $boardRules = $boardRules->sortBy(function ($rule) use ($projectRules) {
            return in_array($rule->id, $projectRules) ? 0 : 1;
        });
        $boards = $user->projects;

        return view('dashboard.projects.board.automation.index', compact('project', 'user', 'boardRules', 'boards'));
    }

    public function create(Request $request, Project $project) {
        $rule = new BoardRule();
        $user = Auth::user();
        [$triggers, $triggerTypes, $actions, $actionTypes] = $this->getData();

        return view('dashboard.projects.board.automation.create', compact(
            'project',
            'rule',
            'user',
            'triggers',
            'triggerTypes',
            'actions',
            'actionTypes'
        ));
    }

    public function store(StoreRequest $request, Project $project) {
        $boardRule = (new StoreUseCase(
            $project,
            $request->trigger['sentence'],
            'TRIGGER',
            $request->trigger,
            $request->actions
        ))->action();

        toast('Rule created', 'success');
        return ApiResponse::done('Rule created');
    }

    public function edit(Request $request, Project $project, BoardRule $automation) {
        $user = Auth::user();
        [$triggers, $triggerTypes, $actions, $actionTypes] = $this->getData();
        $formData = ['trigger' => $automation->data, 'actions' => $automation->actions->pluck('data')->toArray()];
        $formData = json_encode($formData, JSON_HEX_QUOT);
        $copy = false;

        return view('dashboard.projects.board.automation.edit', compact(
            'project',
            'automation',
            'user',
            'triggers',
            'triggerTypes',
            'actions',
            'actionTypes',
            'formData',
            'copy'
        ));
    }

    public function update(UpdateRequest $request, Project $project, BoardRule $automation) {
        $boardRule = (new UpdateUseCase(
            $automation,
            $project,
            $request->trigger['sentence'],
            'TRIGGER',
            $request->trigger,
            $request->actions
        ))->action();

        toast('Rule updated', 'success');
        return ApiResponse::done('Rule updated');
    }

    public function copy(Request $request, Project $project, BoardRule $automation) {
        $user = Auth::user();
        [$triggers, $triggerTypes, $actions, $actionTypes] = $this->getData();
        $formData = ['trigger' => $automation->data, 'actions' => $automation->actions->pluck('data')->toArray()];
        $formData = json_encode($formData, JSON_HEX_QUOT);
        $copy = true;

        return view('dashboard.projects.board.automation.edit', compact(
            'project',
            'automation',
            'user',
            'triggers',
            'triggerTypes',
            'actions',
            'actionTypes',
            'formData',
            'copy'
        ));
    }

    public function destroy(Project $project, BoardRule $automation) {
        if($automation->projects()->count() > 1) {
            $automation->projects()->detach($project);
            toast('Rule disabled on board', 'success');
        } else {
            $automation->forceDelete();
            toast('Rule deleted', 'success');
        }

        return back();
    }

    public function changeActive(Request $request, Project $project, BoardRule $automation) {
        if($request->input('active') == 'false') {
            $project->boardRules()->detach($automation);
        } else {
            $project->boardRules()->attach($automation);
        }

        return ApiResponse::done($request->input('active') == 'false' ? 'Rule disabled on board' : 'Rule enabled on board');
    }

    private function getData(): array
    {
        $triggers = json_encode(BoardAutomationHelper::TRIGGERS, JSON_HEX_QUOT);
        $triggerTypes = json_encode(BoardAutomationHelper::TRIGGERS_TYPE, JSON_HEX_QUOT);
        $actions = json_encode(BoardAutomationHelper::ACTIONS, JSON_HEX_QUOT);
        $actionTypes = json_encode(BoardAutomationHelper::ACTIONS_TYPE, JSON_HEX_QUOT);

        return [$triggers, $triggerTypes, $actions, $actionTypes];
    }
}
