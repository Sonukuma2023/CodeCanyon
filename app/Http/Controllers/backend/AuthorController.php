<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class AuthorController extends Controller
{
    public function dashboard() {
        return view('author.dashboard');
    }

    public function addproduct()
    {
        $categories = Category::all();
        return view('author.addProduct', compact('categories'));
    }

    public function storeproduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'regular_license_price' => 'required|numeric',
            'extended_license_price' => 'required|numeric',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:102400',
            'inline_preview' => 'required|image|mimes:jpeg,png,jpg,gif|max:102400',
            'main_files.*' => 'required|mimes:zip|max:102400',
            'preview.*' => 'required|mimes:zip|max:102400',
            'live_preview.*' => 'nullable|mimes:zip|max:102400',
        ]);

        $mainFilePaths = [];
        $previewPaths = [];
        $livePreviewPaths = [];

        $thumbnailPath = $request->hasFile('thumbnail') ? $request->file('thumbnail')->store('uploads/thumbnails', 'public') : null;
        $inlinePreviewPath = $request->hasFile('inline_preview') ? $request->file('inline_preview')->store('uploads/inline_previews', 'public') : null;

        if ($request->hasFile('main_files')) {
            foreach ($request->file('main_files') as $file) {
                if (!$this->scanFile($file)) {
                    \Log::error("Main file scan failed: " . $file->getClientOriginalName());
                    return redirect()->back()->with('error', 'One of the main files contains a virus or invalid file type.');
                }

                $mainFilePaths[] = $file->store('uploads/main_files', 'public');
            }
        }

        if ($request->hasFile('preview')) {
            foreach ($request->file('preview') as $file) {
                if (!$this->scanFile($file)) {
                    \Log::error("Preview file scan failed: " . $file->getClientOriginalName());
                    return redirect()->back()->with('error', 'One of the preview files contains a virus or invalid file type.');
                }

                $previewPaths[] = $file->store('uploads/previews', 'public');
            }
        }

        if ($request->hasFile('live_preview')) {
            foreach ($request->file('live_preview') as $file) {
                if (!$this->scanFile($file)) {
                    \Log::error("Live preview file scan failed: " . $file->getClientOriginalName());
                    return redirect()->back()->with('error', 'One of the live preview files contains a virus or invalid file type.');
                }

                $livePreviewPaths[] = $file->store('uploads/live_previews', 'public');
            }
        }

        try {
            Product::create([
                'user_id' => Auth::id(),
                'name' => $request->name,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'regular_license_price' => $request->regular_license_price,
                'extended_license_price' => $request->extended_license_price,
                'thumbnail' => $thumbnailPath,
                'inline_preview' => $inlinePreviewPath,
                'main_files' => json_encode($mainFilePaths),
                'preview' => json_encode($previewPaths),
                'live_preview' => json_encode($livePreviewPaths),
                'status' => 'pending',
            ]);
        } catch (\Exception $e) {
            \Log::error('Product creation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'There was an error creating the product. Please try again.');
        }

        return redirect()->route('author.viewproduct')->with('success', 'Product added successfully!');
    }


    public function scanFile(UploadedFile $file)
    {
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file->getRealPath());

        $allowedMimeTypes = [
            'application/zip',
        ];

        if (!in_array($mimeType, $allowedMimeTypes)) {
            \Log::error('File type not allowed: ' . $file->getClientOriginalName());
            return false;
        }

        $maliciousExtensions = [
            '.exe', '.bat', '.sh', '.asp', '.jsp', '.dll',
        ];

        $fileExtension = strtolower($file->getClientOriginalExtension());
        if (in_array($fileExtension, $maliciousExtensions)) {
            \Log::error('Malicious file extension detected: ' . $file->getClientOriginalName());
            return false;
        }

        if ($this->containsMaliciousContent($file)) {
            \Log::error('Malicious content found in file: ' . $file->getClientOriginalName());
            return false;
        }

        return true;
    }


    public function containsMaliciousContent(UploadedFile $file)
    {
        $fileContent = file_get_contents($file->getRealPath());

        $maliciousPatterns = [
            'eval(',
            'base64_decode(',
            'shell_exec(',
            'system(',
            'exec(',
            'passthru(',
            'phpinfo(',
            'proc_open(',
            'popen(',
            'curl(',
            'fopen(',
            'file_get_contents(',
            'file_put_contents(',
        ];

        foreach ($maliciousPatterns as $pattern) {
            if (strpos($fileContent, $pattern) !== false) {
                \Log::error('Malicious pattern detected in file: ' . $file->getClientOriginalName());
                return true;
            }
        }

        return false;
    }



    public function viewproduct()
    {
        $products = Product::where('user_id', Auth::id())
        ->with('category')
        ->latest()
        ->get();
        $products = $products->map(function ($product) {
            if ($product->status == 'pending') {
                $product->status_message = 'Your product is under review';
            } else {
                $product->status_message = null;
            }
            return $product;
        });

        return view('author.viewProduct', compact('products'));
    }

    public function editproduct($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('author.editProduct', compact('product', 'categories'));
    }

    public function updateproduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'regular_license_price' => 'required|numeric',
            'extended_license_price' => 'required|numeric',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:102400',
            'inline_preview' => 'required|image|mimes:jpeg,png,jpg,gif|max:102400',
            'main_files.*' => 'required|mimes:zip|max:102400',
            'preview.*' => 'required|mimes:zip|max:102400',
            'live_preview.*' => 'nullable|mimes:zip|max:102400',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($product->thumbnail && Storage::disk('public')->exists($product->thumbnail)) {
                Storage::disk('public')->delete($product->thumbnail);
            }
            $product->thumbnail = $request->file('thumbnail')->store('uploads/thumbnails', 'public');
        }

        if ($request->hasFile('inline_preview')) {
            if ($product->inline_preview && Storage::disk('public')->exists($product->inline_preview)) {
                Storage::disk('public')->delete($product->inline_preview);
            }
            $product->inline_preview = $request->file('inline_preview')->store('uploads/inline_previews', 'public');
        }

        if ($request->hasFile('main_files')) {
            $oldMainFiles = json_decode($product->main_files, true) ?? [];
            foreach ($oldMainFiles as $filePath) {
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
            }
            $mainFilePaths = [];
            foreach ($request->file('main_files') as $file) {
                if ($this->scanFile($file)) {
                    $mainFilePaths[] = $file->store('uploads/main_files', 'public');
                } else {
                    return back()->with('error', 'One of the main files contains a virus or invalid file type.');
                }
            }
            $product->main_files = json_encode($mainFilePaths);
        }

        if ($request->hasFile('preview')) {
            $oldPreviews = json_decode($product->preview, true) ?? [];
            foreach ($oldPreviews as $filePath) {
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
            }
            $previewPaths = [];
            foreach ($request->file('preview') as $file) {
                if ($this->scanFile($file)) {
                    $previewPaths[] = $file->store('uploads/previews', 'public');
                } else {
                    return back()->with('error', 'One of the preview files contains a virus or invalid file type.');
                }
            }
            $product->preview = json_encode($previewPaths);
        }

        if ($request->hasFile('live_preview')) {
            $oldLivePreviews = json_decode($product->live_preview, true) ?? [];
            foreach ($oldLivePreviews as $filePath) {
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
            }
            $livePreviewPaths = [];
            foreach ($request->file('live_preview') as $file) {
                if ($this->scanFile($file)) {
                    $livePreviewPaths[] = $file->store('uploads/live_previews', 'public');
                } else {
                    return back()->with('error', 'One of the live preview files contains a virus or invalid file type.');
                }
            }
            $product->live_preview = json_encode($livePreviewPaths);
        }

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'regular_license_price' => $request->regular_license_price,
            'extended_license_price' => $request->extended_license_price,
        ]);

        return redirect()->route('author.viewproduct')->with('success', 'Product updated and files replaced successfully!');
    }

    public function deleteproduct($id)
    {
        $product = Product::findOrFail($id);

        if ($product->thumbnail && Storage::disk('public')->exists($product->thumbnail)) {
            Storage::disk('public')->delete($product->thumbnail);
        }

        if ($product->inline_preview && Storage::disk('public')->exists($product->inline_preview)) {
            Storage::disk('public')->delete($product->inline_preview);
        }

        $mainFiles = json_decode($product->main_files, true) ?? [];
        foreach ($mainFiles as $filePath) {
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
        }

        $previews = json_decode($product->preview, true) ?? [];
        foreach ($previews as $filePath) {
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
        }

        $livePreviews = json_decode($product->live_preview, true) ?? [];
        foreach ($livePreviews as $filePath) {
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
        }

        $product->delete();

        return redirect()->route('author.viewproduct')->with('success', 'Product deleted and files deleted successfully!');
    }

    public function viewreview()
    {
        $reviews = Review::with(['user', 'product'])->latest()->get();
        return view('author.viewReview', compact('reviews'));
    }

    public function approveReview($id)
    {
        $review = Review::findOrFail($id);
        $review->update(['status' => 'approved']);

        return redirect()->back()->with('success', 'Review approved successfully.');
    }

    public function rejectReview($id)
    {
        $review = Review::findOrFail($id);
        $review->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Review rejected successfully.');
    }

}
