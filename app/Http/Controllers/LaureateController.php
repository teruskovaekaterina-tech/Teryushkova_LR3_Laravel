<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laureate;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LaureateController extends Controller
{
    public function index()
    {
        $laureates = Laureate::orderBy('award_year')->get();

        return view('laureates.index', compact('laureates'));
    }

    public function create()
    {
        return view('laureates.form', [
            'laureate' => new Laureate()
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'citizenship' => 'required|string|max:255',
            'award_year' => 'required|integer|between:1901,2100',
            'works_text' => 'required|string|max:5000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:5120'
        ]);

        $image = $data['image'] ?? null;
        unset($data['image']);

        $works = preg_split('/\R/u', $data['works_text']);

        $data['works'] = array_values(array_filter(
            array_map('trim', $works),
            fn($work) => $work !== ''
        ));

        unset($data['works_text']);

        if ($image) {
            $data['image_path'] = $this->storeResizedImage($image);
        }

        Laureate::create($data);

        return redirect()->route('laureates.index');
    }

    public function show(Laureate $laureate)
    {
        return view('laureates.show', compact('laureate'));
    }

    public function edit(Laureate $laureate)
    {
        return view('laureates.form', compact('laureate'));
    }

    public function update(Request $request, Laureate $laureate)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'citizenship' => 'required|string|max:255',
            'award_year' => 'required|integer|between:1901,2100',
            'works_text' => 'required|string|max:5000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:5120'
        ]);

        $image = $data['image'] ?? null;
        unset($data['image']);

        $works = preg_split('/\R/u', $data['works_text']);

        $data['works'] = array_values(array_filter(
            array_map('trim', $works),
            fn($work) => $work !== ''
        ));

        unset($data['works_text']);

        $oldImage = $laureate->image_path;

        if ($image) {
            $data['image_path'] = $this->storeResizedImage($image);
        }

        $laureate->update($data);

        if ($image && $oldImage) {
            Storage::disk('public')->delete($oldImage);
        }

        return redirect()->route('laureates.index');
    }

    public function destroy(Laureate $laureate)
    {
        $laureate->delete();

        return redirect()->route('laureates.index');
    }

    private function storeResizedImage(UploadedFile $image): string
    {
        $source = imagecreatefromstring(
            file_get_contents($image->getRealPath())
        );

        $width = imagesx($source);
        $height = imagesy($source);

        $scale = min(1, 1200 / max($width, $height));

        $newWidth = (int) round($width * $scale);
        $newHeight = (int) round($height * $scale);

        $resized = imagecreatetruecolor($newWidth, $newHeight);

        $white = imagecolorallocate($resized, 255, 255, 255);
        imagefill($resized, 0, 0, $white);

        imagecopyresampled(
            $resized,
            $source,
            0, 0, 0, 0,
            $newWidth, $newHeight,
            $width, $height
        );

        Storage::disk('public')->makeDirectory('laureates');

        $path = 'laureates/' . Str::uuid() . '.jpg';

        imagejpeg(
            $resized,
            Storage::disk('public')->path($path),
            85
        );

        imagedestroy($source);
        imagedestroy($resized);

        return $path;
    }
}
