<?php

declare(strict_types=1);

namespace App\Modules\Contact\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Contact\Models\ContactMessage;
use App\Modules\Contact\Contracts\ContactServiceInterface;
use App\Modules\Contact\Http\Requests\UpdateContactStatusRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function __construct(
        private ContactServiceInterface $contactService
    ) {}

    public function index(Request $request): View
    {
        Gate::authorize('viewAny', ContactMessage::class);

        $query = ContactMessage::query();

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $messages = $query->latest()->paginate(10)->withQueryString();

        return view('contact::admin.index', compact('messages'));
    }

    public function show(ContactMessage $contact): View
    {
        Gate::authorize('view', $contact);

        // Auto-mark as read if it is currently unread
        if ($contact->status === 'unread') {
            $this->contactService->updateStatus($contact, 'read');
            $contact->refresh();
        }

        return view('contact::admin.show', compact('contact'));
    }

    public function updateStatus(UpdateContactStatusRequest $request, ContactMessage $contact): RedirectResponse
    {
        Gate::authorize('update', $contact);

        $this->contactService->updateStatus($contact, $request->input('status'));

        return redirect()->route('admin.contacts.show', $contact->id)
            ->with('success', 'Status pesan berhasil diperbarui.');
    }

    public function destroy(ContactMessage $contact): RedirectResponse
    {
        Gate::authorize('delete', $contact);

        $this->contactService->deleteMessage($contact);

        return redirect()->route('admin.contacts.index')
            ->with('success', 'Pesan berhasil dihapus.');
    }
}
