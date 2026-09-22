<?php

namespace App\Http\Controllers;

use App\Models\ContactInquiry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactInquiryController extends Controller
{
    /**
     * Store a new contact inquiry from the website.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        $inquiry = ContactInquiry::create([
            'name' => trim($validated['name']),
            'email' => strtolower(trim($validated['email'])),
            'phone' => isset($validated['phone']) ? trim($validated['phone']) : null,
            'subject' => isset($validated['subject']) && !empty(trim($validated['subject'])) ? trim($validated['subject']) : 'General Inquiry',
            'message' => trim($validated['message']),
            'status' => 'unread',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for reaching out! We have received your inquiry and our team will get back to you shortly.',
            'data' => $inquiry,
        ], 201);
    }

    /**
     * List inquiries (API endpoint for admin).
     */
    public function index(Request $request): JsonResponse
    {
        $query = ContactInquiry::query()->latestFirst();

        if ($request->has('status') && in_array($request->status, ['unread', 'read', 'responded'])) {
            $query->where('status', $request->status);
        }

        return response()->json($query->paginate(20));
    }

    /**
     * Update inquiry status or admin notes.
     */
    public function updateStatus(Request $request, ContactInquiry $inquiry): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:unread,read,responded',
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $inquiry->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Inquiry updated successfully.',
            'data' => $inquiry,
        ]);
    }

    /**
     * Delete an inquiry.
     */
    public function destroy(ContactInquiry $inquiry): JsonResponse
    {
        $inquiry->delete();

        return response()->json([
            'success' => true,
            'message' => 'Inquiry deleted successfully.',
        ]);
    }
}
