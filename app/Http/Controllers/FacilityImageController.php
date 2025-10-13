<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\FacilityImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FacilityImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $images = FacilityImage::with(['facility', 'user'])
            ->latest()
            ->paginate(20);

        return view('admin.facility-images.index', compact('images'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $facilities = Facility::where('status', 'active')->get();
        return view('facility-images.create', compact('facilities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string|max:1000',
            'issue_type' => 'required|in:damage,maintenance,improvement,other',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $imagePath = $request->file('image')->store('facility-images', 'public');

        FacilityImage::create([
            'facility_id' => $request->facility_id,
            'user_id' => auth()->id(),
            'image_path' => $imagePath,
            'description' => $request->description,
            'issue_type' => $request->issue_type,
            'priority' => $request->priority,
        ]);

        return redirect()->route('facility-images.create')
            ->with('success', 'Gambar fasilitas berhasil diupload. Terima kasih atas laporan Anda!');
    }

    /**
     * Display the specified resource.
     */
    public function show(FacilityImage $facilityImage)
    {
        $facilityImage->load(['facility', 'user']);
        return view('admin.facility-images.show', compact('facilityImage'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FacilityImage $facilityImage)
    {
        $facilities = Facility::where('status', 'active')->get();
        return view('admin.facility-images.edit', compact('facilityImage', 'facilities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FacilityImage $facilityImage)
    {
        $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string|max:1000',
            'issue_type' => 'required|in:damage,maintenance,improvement,other',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:pending,in_review,resolved,rejected',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            // Delete old image
            Storage::disk('public')->delete($facilityImage->image_path);
            
            // Store new image
            $data['image_path'] = $request->file('image')->store('facility-images', 'public');
        }

        $facilityImage->update($data);

        return redirect()->route('facility-images.index')
            ->with('success', 'Data gambar fasilitas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FacilityImage $facilityImage)
    {
        // Delete image file
        Storage::disk('public')->delete($facilityImage->image_path);
        
        $facilityImage->delete();

        return redirect()->route('facility-images.index')
            ->with('success', 'Gambar fasilitas berhasil dihapus.');
    }

    /**
     * Update status of facility image
     */
    public function updateStatus(Request $request, FacilityImage $facilityImage)
    {
        $request->validate([
            'status' => 'required|in:pending,in_review,resolved,rejected',
        ]);

        $facilityImage->update(['status' => $request->status]);

        return redirect()->back()
            ->with('success', 'Status berhasil diperbarui.');
    }
}
