<?php

namespace App\Http\Controllers\Admin;

use App\Models\Contact;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        $contacts = Contact::with('user')->latest()->paginate(20);
        return view('admin.contacts.index', compact('contacts'));
    }

    public function show(Contact $contact): View
    {
        $contact->load('user');
        return view('admin.contacts.show', compact('contact'));
    }

    public function destroy(Contact $contact): RedirectResponse
    {
        try {
            $contact->delete();
            return redirect()->route('contacts.index')->with('success', "Liên hệ (ID: {$contact->id}) đã được xóa thành công!");
        } catch (\Throwable $e) {
            return redirect()->route('contacts.index')->with('error', 'Có lỗi xảy ra khi xóa liên hệ. Vui lòng thử lại.');
        }
    }
}

