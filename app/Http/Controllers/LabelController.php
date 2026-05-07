<?php
namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LabelController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'color' => 'nullable|string|max:20'
        ]);

        // Kiểm tra độc nhất, phân biệt hoa/thường (BINARY) cho user hiện tại
        $exists = Label::where('user_id', Auth::id())
                       ->whereRaw('BINARY name = ?', [$request->name])->exists();

        if ($exists) {
            return back()->with('error', 'Nhãn "'.$request->name.'" đã tồn tại!');
        }

        Label::create([
            'name'    => $request->name,
            'color'   => $request->color ?? '#3b82f6',
            'user_id' => Auth::id()
        ]);
        return back()->with('status', 'Đã thêm nhãn mới!');
    }

    public function update(Request $request, Label $label)
    {
        if ($label->user_id !== Auth::id()) abort(403);

        $request->validate(['name' => 'required|string|max:255', 'color' => 'nullable|string|max:20']);

        // Kiểm tra trùng tên với nhãn KHÁC
        $exists = Label::where('user_id', Auth::id())
                       ->where('id', '!=', $label->id)
                       ->whereRaw('BINARY name = ?', [$request->name])->exists();
                       
        if ($exists) return back()->with('error', 'Nhãn "'.$request->name.'" đã tồn tại!');

        $label->update(['name' => $request->name, 'color' => $request->color]);
        return back()->with('status', 'Đã cập nhật nhãn!');
    }

    public function destroy(Label $label)
    {
        if ($label->user_id === Auth::id()) $label->delete();
        return back()->with('status', 'Đã xóa nhãn!');
    }
}