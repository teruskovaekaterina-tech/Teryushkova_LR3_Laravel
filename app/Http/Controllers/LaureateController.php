<?php

namespace App\Http\Controllers;

use App\Models\Laureate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LaureateController extends Controller
{
    public function index(): View
    {
        $laureates = Laureate::with('user')->orderBy('award_year')->get();

        return view('laureates.index', compact('laureates'));
    }

    public function create(): View
    {
        return view('laureates.form', ['laureate' => new Laureate()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $image = $data['image'] ?? null;
        unset($data['image']);

        if ($image) {
            $data['image_path'] = $this->storeResizedImage($image);
        }

        $request->user()->laureates()->create($data);

        return redirect()->route('users.show', $request->user());
    }

    public function show(Laureate $laureate): View
    {
        $laureate->load('user');

        return view('laureates.show', compact('laureate'));
    }

    public function edit(Laureate $laureate): View
    {
        Gate::authorize('manage-laureate', $laureate);

        return view('laureates.form', compact('laureate'));
    }

    public function update(Request $request, Laureate $laureate): RedirectResponse
    {
        Gate::authorize('manage-laureate', $laureate);

        $data = $this->validatedData($request);
        $image = $data['image'] ?? null;
        unset($data['image']);

        $oldImage = $laureate->image_path;

        if ($image) {
            $data['image_path'] = $this->storeResizedImage($image);
        }

        $laureate->update($data);

        if ($image && $oldImage) {
            Storage::disk('public')->delete($oldImage);
        }

        return redirect()->route('laureates.show', $laureate);
    }

    public function destroy(Laureate $laureate): RedirectResponse
    {
        Gate::authorize('manage-laureate', $laureate);
        $laureate->delete();

        return redirect()->route('laureates.index');
    }

    public function restore(Laureate $laureate): RedirectResponse
    {
        Gate::authorize('restore-laureate', $laureate);
        abort_unless($laureate->trashed(), 404);
        $laureate->restore();

        return redirect()->route('users.show', $laureate->user);
    }

    public function forceDestroy(Laureate $laureate): RedirectResponse
    {
        Gate::authorize('force-delete-laureate', $laureate);
        $owner = $laureate->user;
        $laureate->forceDelete();

        return $owner
            ? redirect()->route('users.show', $owner)
            : redirect()->route('laureates.index');
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date'],
            'citizenship' => ['required', 'string', 'max:255'],
            'award_year' => ['required', 'integer', 'between:1901,2100'],
            'works_text' => ['required', 'string', 'max:5000', 'regex:/\\S/u'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
        ]);

        $data['works'] = array_values(array_filter(
            array_map('trim', preg_split('/\\R/u', $data['works_text'])),
            fn ($work) => $work !== ''
        ));

        unset($data['works_text']);

        return $data;
    }

    private function storeResizedImage(UploadedFile $image): string
    {
        $source = imagecreatefromstring(file_get_contents($image->getRealPath()));
        $width = imagesx($source);
        $height = imagesy($source);
        $scale = min(1, 1200 / max($width, $height));
        $newWidth = (int) round($width * $scale);
        $newHeight = (int) round($height * $scale);
        $resized = imagecreatetruecolor($newWidth, $newHeight);
        $white = imagecolorallocate($resized, 255, 255, 255);
        imagefill($resized, 0, 0, $white);
        imagecopyresampled($resized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        Storage::disk('public')->makeDirectory('laureates');
        $path = 'laureates/'.Str::uuid().'.jpg';
        imagejpeg($resized, Storage::disk('public')->path($path), 85);
        imagedestroy($source);
        imagedestroy($resized);

        return $path;
    }
}
