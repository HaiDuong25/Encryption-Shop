<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use Illuminate\Http\Request;

class AttributeValueController extends Controller
{
    public function storeAjax(Request $request, Attribute $attribute)
    {
        $request->validate([
            'value' => 'required|string|max:100',
        ]);
        $value = $attribute->values()->firstOrCreate(['value' => $request->value]);
        return response()->json(['id' => $value->id, 'value' => $value->value]);
    }
}
