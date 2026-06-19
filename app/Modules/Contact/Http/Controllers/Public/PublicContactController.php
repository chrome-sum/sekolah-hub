<?php

declare(strict_types=1);

namespace App\Modules\Contact\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Modules\Contact\Contracts\ContactServiceInterface;
use App\Modules\System\Contracts\SystemServiceInterface;
use App\Modules\Contact\Http\Requests\SubmitContactRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PublicContactController extends Controller
{
    public function __construct(
        private ContactServiceInterface $contactService,
        private SystemServiceInterface $systemService
    ) {}

    public function showForm(): View
    {
        $turnstileSiteKey = $this->systemService->getSetting('cloudflare.turnstile.site_key', '');

        return view('contact::public.show', compact('turnstileSiteKey'));
    }

    public function submit(SubmitContactRequest $request): RedirectResponse
    {
        $this->contactService->submitMessage($request->validated());

        return redirect()->route('public.contact.show')
            ->with('success', 'Pesan Anda berhasil dikirim. Terima kasih!');
    }
}
