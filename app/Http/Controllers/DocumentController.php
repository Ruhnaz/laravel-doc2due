<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Models\Category;
use App\Models\Contractor;
use App\Models\Document;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentController extends Controller
{
    use AuthorizesRequests;
    public function index(): View
    {
        $this->authorize('viewAny', Document::class);

        return view('documents.index', [
            'documents' => Document::query()
                ->with(['category', 'contractor'])
                ->filtered(request()->only(['search', 'status', 'category', 'contractor']))
                ->orderBy('expiry_date')
                ->paginate(10)
                ->withQueryString(),
            'categories' => Category::query()->orderBy('name')->get(),
            'contractors' => Contractor::query()->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Document::class);

        return view('documents.create', $this->formData());
    }

    public function store(StoreDocumentRequest $request): RedirectResponse
    {
        $company = $request->user()->company()->with('plan')->first();
        $used = $company->documents()->count();
        $limit = $company->plan->document_limit;

        if ($used >= $limit) {
            return back()->withInput()->with('error', "Your {$company->plan->name} plan allows {$limit} documents ({$used}/{$limit}).");
        }

        $file = $request->file('file');

        Document::create([
            'company_id' => $request->user()->company_id,
            'category_id' => $request->integer('category_id'),
            'contractor_id' => $request->input('contractor_id'),
            'title' => $request->string('title')->toString(),
            'expiry_date' => $request->date('expiry_date'),
            'notes' => $request->input('notes'),
            'file_path' => $file->store('documents/'.$request->user()->company_id, 'private'),
            'file_name' => $file->getClientOriginalName(),
        ]);

        return redirect()->route('documents.index')->with('success', 'Document added.');
    }

    public function edit(Document $document): View
    {
        $this->authorize('update', $document);

        return view('documents.edit', ['document' => $document] + $this->formData());
    }

    public function update(UpdateDocumentRequest $request, Document $document): RedirectResponse
    {
        $data = [
            'category_id' => $request->integer('category_id'),
            'contractor_id' => $request->input('contractor_id'),
            'title' => $request->string('title')->toString(),
            'expiry_date' => $request->date('expiry_date'),
            'notes' => $request->input('notes'),
        ];

        if ($request->hasFile('file')) {
            Storage::disk('private')->delete($document->file_path);
            $file = $request->file('file');
            $data['file_path'] = $file->store('documents/'.$request->user()->company_id, 'private');
            $data['file_name'] = $file->getClientOriginalName();
        }

        $document->update($data);

        return redirect()->route('documents.index')->with('success', 'Document updated.');
    }

    public function destroy(Document $document): RedirectResponse
    {
        $this->authorize('delete', $document);

        $document->delete();

        return redirect()->route('documents.index')->with('success', 'Document removed.');
    }

    public function download(Document $document): StreamedResponse
    {
        $this->authorize('view', $document);

        abort_unless(Storage::disk('private')->exists($document->file_path), 404);

        return Storage::disk('private')->download($document->file_path, $document->file_name);
    }

    public function export(): StreamedResponse
    {
        $this->authorize('viewAny', Document::class);

        return response()->streamDownload(function (): void {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Title', 'Category', 'Contractor', 'Expiry date', 'Status']);

            Document::query()
                ->with(['category', 'contractor'])
                ->filtered(request()->only(['search', 'status', 'category', 'contractor']))
                ->orderBy('expiry_date')
                ->lazy()
                ->each(function (Document $document) use ($handle): void {
                    fputcsv($handle, [
                        $document->title,
                        $document->category->name,
                        $document->contractor->name ?? '',
                        $document->expiry_date->toDateString(),
                        $document->statusLabel(),
                    ]);
                });

            fclose($handle);
        }, 'documents.csv', ['Content-Type' => 'text/csv']);
    }

    /**
     * @return array<string, mixed>
     */
    private function formData(): array
    {
        return [
            'categories' => Category::query()->orderBy('name')->get(),
            'contractors' => Contractor::query()->orderBy('name')->get(),
        ];
    }
}
