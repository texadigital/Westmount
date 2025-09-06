<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\PageContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Display the contact page
     */
    public function index()
    {
        // Get dynamic content for contact page
        $content = PageContent::getPageContent('contact');
        
        return view('public.contact', compact('content'));
    }

    /**
     * Send contact message
     */
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|in:general,membership,payment,contribution,sponsorship,technical,other',
            'message' => 'required|string|max:2000',
            'privacy' => 'required|accepted',
        ], [
            'first_name.required' => 'Le prénom est requis.',
            'last_name.required' => 'Le nom est requis.',
            'email.required' => 'L\'email est requis.',
            'email.email' => 'L\'email doit être valide.',
            'subject.required' => 'Le sujet est requis.',
            'message.required' => 'Le message est requis.',
            'message.max' => 'Le message ne peut pas dépasser 2000 caractères.',
            'privacy.required' => 'Vous devez accepter l\'utilisation de vos données personnelles.',
            'privacy.accepted' => 'Vous devez accepter l\'utilisation de vos données personnelles.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // In a real application, you would send an email here
            // For now, we'll just store the message in the session
            $messageData = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'subject' => $request->subject,
                'message' => $request->message,
                'created_at' => now(),
            ];

            // Here you would typically:
            // 1. Send an email to the admin
            // 2. Store the message in the database
            // 3. Send an auto-reply to the user

            // For demonstration, we'll just return a success message
            return back()->with('success', 'Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Une erreur est survenue lors de l\'envoi de votre message. Veuillez réessayer.'])->withInput();
        }
    }
}
