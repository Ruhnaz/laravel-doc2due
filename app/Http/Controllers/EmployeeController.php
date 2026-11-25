<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    use AuthorizesRequests;

    public function index(): View
    {
        $this->authorize('viewAny', User::class);

        $employees = User::query()->orderBy('name')->get();

        return view('employees.index', [
            'employees' => $employees,
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', User::class);

        return view('employees.create');
    }

    public function store(StoreEmployeeRequest $request): RedirectResponse
    {
        User::create([
            'company_id' => $request->user()->company_id,
            'name' => $request->string('name')->toString(),
            'email' => $request->string('email')->toString(),
            'role' => $request->enum('role', UserRole::class),
            'password' => $request->string('password')->toString(),
        ]);

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee added.');
    }

    public function edit(User $employee): View
    {
        $this->authorize('update', $employee);

        return view('employees.edit', [
            'employee' => $employee,
        ]);
    }

    public function update(UpdateEmployeeRequest $request, User $employee): RedirectResponse
    {
        $data = [
            'name' => $request->string('name')->toString(),
            'email' => $request->string('email')->toString(),
            'role' => $employee->is($request->user())
                ? UserRole::Admin
                : $request->enum('role', UserRole::class),
        ];

        if ($request->filled('password')) {
            $data['password'] = $request->string('password')->toString();
        }

        $employee->update($data);

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee updated.');
    }

    public function destroy(User $employee): RedirectResponse
    {
        $this->authorize('delete', $employee);

        $employee->delete();

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee removed.');
    }
}
