<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\MemberDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    /**
     * Display member documents
     */
    public function index(Request $request)
    {
        $member = $request->attributes->get('current_member');
        $documents = $member->documents()->orderBy('created_at', 'desc')->get();
        
        return view('member.documents.index', compact('member', 'documents'));
    }

    /**
     * Show document upload form
     */
    public function create(Request $request)
    {
        $member = $request->attributes->get('current_member');
        $documentTypes = MemberDocument::getDocumentTypes();
        
        return view('member.documents.create', compact('member', 'documentTypes'));
    }

    /**
     * Store uploaded document
     */
    public function store(Request $request)
    {
        $member = $request->attributes->get('current_member');
        
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:' . implode(',', array_keys(MemberDocument::getDocumentTypes())),
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240', // 10MB max
        ], [
            'name.required' => 'Le nom du document est requis.',
            'type.required' => 'Le type de document est requis.',
            'document.required' => 'Le fichier est requis.',
            'document.mimes' => 'Le fichier doit être un PDF, JPG, JPEG ou PNG.',
            'document.max' => 'Le fichier ne peut pas dépasser 10MB.',
        ]);

        $file = $request->file('document');
        $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('member-documents/' . $member->id, $fileName, 'public');

        MemberDocument::create([
            'member_id' => $member->id,
            'name' => $request->name,
            'type' => $request->type,
            'file_path' => $filePath,
            'file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
        ]);

        return redirect()->route('member.documents.index')
            ->with('success', 'Document téléchargé avec succès. Il sera examiné par l\'administration.');
    }

    /**
     * Download document
     */
    public function download(Request $request, MemberDocument $document)
    {
        $member = $request->attributes->get('current_member');
        
        // Verify the document belongs to the member
        if ($document->member_id !== $member->id) {
            abort(403, 'Accès non autorisé.');
        }

        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'Fichier non trouvé.');
        }

        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }

    /**
     * Delete document
     */
    public function destroy(Request $request, MemberDocument $document)
    {
        $member = $request->attributes->get('current_member');
        
        // Verify the document belongs to the member
        if ($document->member_id !== $member->id) {
            abort(403, 'Accès non autorisé.');
        }

        // Only allow deletion of pending documents
        if ($document->status !== 'pending') {
            return redirect()->route('member.documents.index')
                ->with('error', 'Seuls les documents en attente peuvent être supprimés.');
        }

        // Delete file from storage
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return redirect()->route('member.documents.index')
            ->with('success', 'Document supprimé avec succès.');
    }
}
