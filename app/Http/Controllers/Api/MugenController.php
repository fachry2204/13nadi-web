<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\MugenFormField;
use App\Models\MugenSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MugenController extends Controller
{
    private const TYPES = ['text', 'email', 'tel', 'url', 'textarea', 'select', 'radio', 'checkbox'];

    public function form()
    {
        return response()->json(['data' => MugenFormField::where('is_active', true)->orderBy('sort_order')->get()]);
    }

    public function submit(Request $request)
    {
        $fields = MugenFormField::where('is_active', true)->orderBy('sort_order')->get();
        $rules = [];
        foreach ($fields as $field) {
            $rule = [$field->is_required ? 'required' : 'nullable'];
            $rule[] = $field->type === 'checkbox' ? 'boolean' : 'string';
            if ($field->type === 'email') $rule[] = 'email:rfc';
            if ($field->type === 'url') $rule[] = 'url';
            if (in_array($field->type, ['select', 'radio'], true) && $field->options) $rule[] = Rule::in($field->options);
            if ($field->type !== 'checkbox') $rule[] = 'max:2000';
            $rules['answers.'.$field->key] = $rule;
        }
        $validated = $request->validate(['answers' => ['required', 'array']] + $rules);
        $allowed = $fields->pluck('key')->all();
        $answers = collect($validated['answers'])->only($allowed)->all();
        $submission = MugenSubmission::create(['answers' => $answers, 'ip_address' => $request->ip()]);
        return response()->json(['data' => ['id' => $submission->id], 'message' => 'Pendaftaran MUGGEN berhasil dikirim.'], 201);
    }

    public function adminIndex()
    {
        return response()->json([
            'data' => [
                'fields' => MugenFormField::orderBy('sort_order')->get(),
                'submissions' => MugenSubmission::latest()->paginate(50),
            ],
        ]);
    }

    public function saveFields(Request $request)
    {
        $data = $request->validate([
            'fields' => ['required', 'array', 'min:1'],
            'fields.*.id' => ['nullable', 'integer'],
            'fields.*.key' => ['nullable', 'string', 'max:80'],
            'fields.*.label' => ['required', 'string', 'max:160'],
            'fields.*.type' => ['required', Rule::in(self::TYPES)],
            'fields.*.placeholder' => ['nullable', 'string', 'max:255'],
            'fields.*.options' => ['nullable', 'array'],
            'fields.*.options.*' => ['string', 'max:160'],
            'fields.*.is_required' => ['boolean'],
            'fields.*.is_active' => ['boolean'],
        ]);

        $kept = [];
        foreach ($data['fields'] as $order => $input) {
            $field = !empty($input['id']) ? MugenFormField::find($input['id']) : null;
            $field ??= new MugenFormField;
            $baseKey = $input['key'] ?? Str::slug($input['label'], '_');
            $key = $baseKey ?: 'field_'.($order + 1);
            if (!$field->exists) {
                $candidate = $key;
                $suffix = 2;
                while (MugenFormField::where('key', $candidate)->exists()) $candidate = $key.'_'.$suffix++;
                $key = $candidate;
            } else {
                $key = $field->key;
            }
            $field->fill([
                'key' => $key,
                'label' => $input['label'],
                'type' => $input['type'],
                'placeholder' => $input['placeholder'] ?? null,
                'options' => array_values(array_filter($input['options'] ?? [], fn ($value) => trim($value) !== '')),
                'is_required' => $input['is_required'] ?? false,
                'is_active' => $input['is_active'] ?? true,
                'sort_order' => $order,
            ])->save();
            $kept[] = $field->id;
        }
        MugenFormField::whereNotIn('id', $kept)->delete();
        ActivityLog::create(['user_id' => $request->user()->id, 'action' => 'mugen_form_updated', 'ip_address' => $request->ip()]);
        return $this->adminIndex();
    }

    public function destroySubmission(Request $request, MugenSubmission $submission)
    {
        $submission->delete();
        ActivityLog::create(['user_id' => $request->user()->id, 'action' => 'mugen_submission_deleted', 'subject_id' => $submission->id, 'ip_address' => $request->ip()]);
        return response()->json([], 204);
    }
}
