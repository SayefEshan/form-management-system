<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Requests\FormStoreRequest;

class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $form = Form::all();

        return Inertia::render('Forms/Index', [
            'forms' => $form,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Forms/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FormStoreRequest $request)
    {
        $validated = $request->validated();

        $fields = $validated['fields'];

        $form = Form::create([
            'title' => $validated['title'],
            'method' => $validated['method'],
            'action' => $validated['action'],
            'fields' => $fields,
        ]);

        return redirect()->route('forms.index')->with('success', 'Form created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Form $form)
    {
        return Inertia::render('Forms/Show', [
            'form' => $form
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Form $form)
    {
        return Inertia::render('Forms/Edit', [
            'form' => $form
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FormStoreRequest $request, Form $form)
    {
        $validated = $request->validated();

        $form->update($validated);

        return redirect()->route('forms.index')->with('success', 'Form updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Form $form)
    {
        $form->delete();
        return redirect()->route('forms.index')->with('success', 'Form deleted successfully');
    }

    /**
     * Import JSON configuration.
     */
    public function importJson(Request $request)
    {
        $validated = $request->validate([
            'json_data' => 'required|json',
        ]);

        $config = json_decode($validated['json_data'], true);

        return response()->json([
            'config' => $config,
            'message' => 'JSON configuration parsed successfully'
        ]);
    }

    /**
     * Update form structure using drag and drop.
     */
    public function updateStructure(Request $request, Form $form)
    {
        $validated = $request->validate([
            'configuration' => 'required|array',
        ]);

        $form->update([
            'configuration' => $validated['configuration'],
        ]);

        return response()->json([
            'message' => 'Form structure updated successfully'
        ]);
    }
}
