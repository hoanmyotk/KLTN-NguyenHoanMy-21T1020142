<?php           
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Markdown;

class MarkdownController extends Controller
{
    public function index()
    {
        $markdowns = Markdown::with(['doctor', 'specialty', 'clinic'])->get();
        return view('markdown.index', compact('markdowns'));
    }
}
