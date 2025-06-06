<?php

namespace App\Http\Controllers\Admin;

use App\Models\Contact;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contacts = Contact::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('admin.contacts.index', compact('contacts'));
    }

    public function show(Contact $contact)
    {
        $contact->load('user');
        return view('admin.contacts.show', compact('contact'));
    }

    public function destroy(Contact $contact)
    {
        try {
            $contact->delete();
            return redirect()->route('admin.contacts.index')
                ->with('success', 'Liên hệ (ID: ' . $contact->id . ') đã được xóa thành công!');
        } catch (\Exception $e) {
            return redirect()->route('admin.contacts.index')
                ->with('error', 'Có lỗi xảy ra khi xóa liên hệ. Vui lòng thử lại.');
        }
    }
}
