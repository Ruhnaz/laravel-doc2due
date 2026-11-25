<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContractorRequest;
use App\Http\Requests\UpdateContractorRequest;
use App\Models\Contractor;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContractorController extends Controller
{
    use AuthorizesRequests;
    public function index(): View
    {
        $this->authorize('viewAny', Contractor::class);

        return view('contractors.index', [
            'contractors' => Contractor::query()->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Contractor::class);

        return view('contractors.create');
    }

    public function store(StoreContractorRequest $request): RedirectResponse
    {
        Contractor::create([
            'company_id' => $request->user()->company_id,
            'name' => $request->string('name')->toString(),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'notes' => $request->input('notes'),
        ]);

        return redirect()->route('contractors.index')->with('success', 'Contractor added.');
    }

    public function edit(Contractor $contractor): View
    {
        $this->authorize('update', $contractor);

        return view('contractors.edit', ['contractor' => $contractor]);
    }

    public function update(UpdateContractorRequest $request, Contractor $contractor): RedirectResponse
    {
        $contractor->update([
            'name' => $request->string('name')->toString(),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'notes' => $request->input('notes'),
        ]);

        return redirect()->route('contractors.index')->with('success', 'Contractor updated.');
    }

    public function destroy(Contractor $contractor): RedirectResponse
    {
        $this->authorize('delete', $contractor);

        $contractor->delete();

        return redirect()->route('contractors.index')->with('success', 'Contractor removed.');
    }
}
