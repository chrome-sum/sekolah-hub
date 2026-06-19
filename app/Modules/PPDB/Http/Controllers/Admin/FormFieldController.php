<?php

declare(strict_types=1);

namespace App\Modules\PPDB\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\PPDB\Models\AdmissionTrack;
use App\Modules\PPDB\Models\AdmissionFormField;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\View\View;

class FormFieldController extends Controller
{
    public function index(AdmissionTrack $track): View
    {
        Gate::authorize('ppdb.manage');
        $fields = $track->fields;
        return view('ppdb::admin.form-fields.index', compact('track', 'fields'));
    }

    public function create(AdmissionTrack $track): View
    {
        Gate::authorize('ppdb.manage');
        $types = [
            'text' => 'Teks Pendek (Text)',
            'textarea' => 'Teks Panjang (Textarea)',
            'number' => 'Angka (Number)',
            'date' => 'Tanggal (Date)',
            'email' => 'Email',
            'phone' => 'Telepon / HP',
            'select' => 'Pilihan (Dropdown/Select)',
            'radio' => 'Pilihan Tunggal (Radio)',
            'checkbox' => 'Pilihan Ganda (Checkbox)',
            'file' => 'Unggah File (File)',
            'heading' => 'Judul Bagian (Heading)',
            'description' => 'Teks Penjelasan (Description)',
        ];
        return view('ppdb::admin.form-fields.create', compact('track', 'types'));
    }

    public function store(AdmissionTrack $track, Request $request): RedirectResponse
    {
        Gate::authorize('ppdb.manage');

        $validated = $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'field_key' => ['nullable', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:text,textarea,number,date,email,phone,select,radio,checkbox,file,heading,description'],
            'placeholder' => ['nullable', 'string', 'max:255'],
            'help_text' => ['nullable', 'string', 'max:255'],
            'is_required' => ['nullable', 'boolean'],
            'options' => ['nullable', 'string'], // Raw text separated by newline
            'validation_rules' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $fieldKey = $validated['field_key'] ? Str::slug($validated['field_key'], '_') : Str::slug($validated['label'], '_');
        $originalFieldKey = $fieldKey;
        $count = 1;
        while (AdmissionFormField::where('track_id', $track->id)->where('field_key', $fieldKey)->exists()) {
            $fieldKey = $originalFieldKey . '_' . $count++;
        }

        // Process options from newline to array
        $optionsArray = null;
        if (!empty($validated['options'])) {
            $optionsArray = array_values(array_filter(array_map('trim', explode("\n", $validated['options']))));
        }

        // Get max sort order
        $maxSort = (int) AdmissionFormField::where('track_id', $track->id)->max('sort_order');

        AdmissionFormField::create([
            'track_id' => $track->id,
            'field_key' => $fieldKey,
            'label' => $validated['label'],
            'type' => $validated['type'],
            'placeholder' => $validated['placeholder'] ?? null,
            'help_text' => $validated['help_text'] ?? null,
            'is_required' => isset($validated['is_required']) ? (bool) $validated['is_required'] : false,
            'options' => $optionsArray,
            'validation_rules' => $validated['validation_rules'] ?? null,
            'sort_order' => $maxSort + 1,
            'is_active' => isset($validated['is_active']) ? (bool) $validated['is_active'] : true,
        ]);

        return redirect()
            ->route('admin.ppdb.tracks.form-fields.index', $track->id)
            ->with('success', 'Field form berhasil ditambahkan.');
    }

    public function edit(AdmissionTrack $track, AdmissionFormField $formField): View
    {
        Gate::authorize('ppdb.manage');
        $types = [
            'text' => 'Teks Pendek (Text)',
            'textarea' => 'Teks Panjang (Textarea)',
            'number' => 'Angka (Number)',
            'date' => 'Tanggal (Date)',
            'email' => 'Email',
            'phone' => 'Telepon / HP',
            'select' => 'Pilihan (Dropdown/Select)',
            'radio' => 'Pilihan Tunggal (Radio)',
            'checkbox' => 'Pilihan Ganda (Checkbox)',
            'file' => 'Unggah File (File)',
            'heading' => 'Judul Bagian (Heading)',
            'description' => 'Teks Penjelasan (Description)',
        ];

        // Format options array back to text for textarea
        $optionsText = '';
        if (is_array($formField->options)) {
            $optionsText = implode("\n", $formField->options);
        }

        return view('ppdb::admin.form-fields.edit', compact('track', 'formField', 'types', 'optionsText'));
    }

    public function update(AdmissionTrack $track, AdmissionFormField $formField, Request $request): RedirectResponse
    {
        Gate::authorize('ppdb.manage');

        $validated = $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'field_key' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:text,textarea,number,date,email,phone,select,radio,checkbox,file,heading,description'],
            'placeholder' => ['nullable', 'string', 'max:255'],
            'help_text' => ['nullable', 'string', 'max:255'],
            'is_required' => ['nullable', 'boolean'],
            'options' => ['nullable', 'string'],
            'validation_rules' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $fieldKey = Str::slug($validated['field_key'], '_');
        $originalFieldKey = $fieldKey;
        $count = 1;
        while (AdmissionFormField::where('track_id', $track->id)->where('field_key', $fieldKey)->where('id', '!=', $formField->id)->exists()) {
            $fieldKey = $originalFieldKey . '_' . $count++;
        }

        $optionsArray = null;
        if (!empty($validated['options'])) {
            $optionsArray = array_values(array_filter(array_map('trim', explode("\n", $validated['options']))));
        }

        $formField->update([
            'field_key' => $fieldKey,
            'label' => $validated['label'],
            'type' => $validated['type'],
            'placeholder' => $validated['placeholder'] ?? null,
            'help_text' => $validated['help_text'] ?? null,
            'is_required' => isset($validated['is_required']) ? (bool) $validated['is_required'] : false,
            'options' => $optionsArray,
            'validation_rules' => $validated['validation_rules'] ?? null,
            'is_active' => isset($validated['is_active']) ? (bool) $validated['is_active'] : true,
        ]);

        return redirect()
            ->route('admin.ppdb.tracks.form-fields.index', $track->id)
            ->with('success', 'Field form berhasil diperbarui.');
    }

    public function destroy(AdmissionTrack $track, AdmissionFormField $formField): RedirectResponse
    {
        Gate::authorize('ppdb.manage');
        $formField->delete();

        return redirect()
            ->route('admin.ppdb.tracks.form-fields.index', $track->id)
            ->with('success', 'Field form berhasil dihapus.');
    }

    public function reorder(AdmissionTrack $track, Request $request): RedirectResponse
    {
        Gate::authorize('ppdb.manage');
        $validated = $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['required', 'integer', 'exists:admission_form_fields,id'],
        ]);

        foreach ($validated['order'] as $index => $id) {
            AdmissionFormField::where('id', $id)
                ->where('track_id', $track->id)
                ->update(['sort_order' => $index]);
        }

        return redirect()
            ->route('admin.ppdb.tracks.form-fields.index', $track->id)
            ->with('success', 'Urutan field form berhasil disimpan.');
    }
}
