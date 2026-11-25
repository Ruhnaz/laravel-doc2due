<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    use AuthorizesRequests;
    public function index(): View
    {
        $this->authorize('viewAny', Category::class);

        return view('categories.index', [
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Category::class);

        return view('categories.create');
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        Category::create([
            'company_id' => $request->user()->company_id,
            'name' => $request->string('name')->toString(),
        ]);

        return redirect()->route('categories.index')->with('success', 'Category added.');
    }

    public function edit(Category $category): View
    {
        $this->authorize('update', $category);

        return view('categories.edit', ['category' => $category]);
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update(['name' => $request->string('name')->toString()]);

        return redirect()->route('categories.index')->with('success', 'Category updated.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $this->authorize('delete', $category);

        try {
            $category->delete();
        } catch (QueryException) {
            return back()->with('error', 'Remove this category’s documents before deleting it.');
        }

        return redirect()->route('categories.index')->with('success', 'Category removed.');
    }
}
