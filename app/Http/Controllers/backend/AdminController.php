<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Models\Category;
use App\Models\User;
use App\Models\Product;
use App\Models\Messages;
use App\Models\Notifications;
use App\Models\Community;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;
use App\Events\CommunityCreated;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function dashboard() {
        return view('admin.dashboard');
    }

    public function adduser() {
        return view('admin.addUsers');
    }

    public function storeuser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:6',
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'role' => 'required|in:admin,author,user',
            'status' => 'required|in:active,inactive',
        ]);

        $imagePath = null;

        if ($request->hasFile('file')) {
            $image = $request->file('file');
            $imagePath = $image->store('uploads/profile_images', 'public');
        }

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'image' => $imagePath,
            'role' => $request->role,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.viewusers')->with('success', 'User created successfully!');
    }

    public function viewusers() {
        $users = User::latest()->get();
        return view('admin.viewUsers', compact('users'));
    }

    public function edituser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.editUser', compact('user'));
    }

    public function updateuser(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required|string|max:20',
            'role' => 'required|in:admin,author,user',
            'status' => 'required|in:active,inactive',
            'file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = User::findOrFail($id);

        if ($request->hasFile('file')) {
            if ($user->image && \Storage::disk('public')->exists($user->image)) {
                \Storage::disk('public')->delete($user->image);
            }

            $image = $request->file('file');
            $imagePath = $image->store('uploads/profile_images', 'public');

            $user->image = $imagePath;
        }

        $user->update([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.viewusers')->with('success', 'User updated successfully!');
    }


    public function deleteuser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.viewusers')->with('success', 'User deleted successfully!');
    }

    public function addcategory() {
        return view('admin.addCategory');
    }

    public function storecategory(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:255',
        ]);

        try {
            Category::create([
                'name' => $request->category,
            ]);

            return redirect()->route('admin.viewcategory')->with('success', 'Category added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to add category.');
        }
    }

    public function viewcategory() {
        $categories = Category::latest()->get();
        return view('admin.viewCategory', compact('categories'));
    }

    public function editcategory($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.editCategory', compact('category'));
    }

    public function updatecategory(Request $request, $id)
    {
        $request->validate([
            'category' => 'required|string|max:255',
        ]);

        try {
            $category = Category::findOrFail($id);
            $category->name = $request->category;
            $category->save();

            return redirect()->route('admin.viewcategory')->with('success', 'Category updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update category.');
        }
    }

    public function deletecategory($id)
    {
        try {
            Category::findOrFail($id)->delete();
            return redirect()->route('admin.viewcategory')->with('success', 'Category deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete category.');
        }
    }

    public function addproduct()
    {
        $categories = Category::all(); 
        return view('admin.addProduct', compact('categories'));
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
            'status' => 'required|in:approved,pending',
        ]);

        $mainFilePaths = [];
        $previewPaths = [];
        $livePreviewPaths = [];

        // $thumbnailPath = $request->hasFile('thumbnail') ? $request->file('thumbnail')->store('uploads/thumbnails', 'public') : null;
		
		if ($request->hasFile('thumbnail')) {
			$thumbnailName = time() . '---' . $request->file('thumbnail')->getClientOriginalName();
			$file = $request->file('thumbnail');
			$file->move(public_path('storage/uploads/thumbnails'), $thumbnailName);
			$thumbnailPath = 'storage/uploads/thumbnails/' . $thumbnailName;
		} else {
			$thumbnailPath = null;
			$thumbnailName = null;
		}
		
        // $inlinePreviewPath = $request->hasFile('inline_preview') ? $request->file('inline_preview')->store('uploads/inline_previews', 'public') : null;

        if ($request->hasFile('inline_preview')) {
			$inlinePreviewName = time() . '---' . $request->file('inline_preview')->getClientOriginalName();
			$inlineFile = $request->file('inline_preview');
			$inlineFile->move(public_path('storage/uploads/inline_previews'), $inlinePreviewName);
			$inlinePreviewPath = 'storage/uploads/inline_previews/' . $inlinePreviewName;
		} else {
			$inlinePreviewPath = null;
			$inlinePreviewName = null;
		}

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
                'thumbnail' => $thumbnailName,
                'inline_preview' => $inlinePreviewName,
                'main_files' => json_encode($mainFilePaths),
                'preview' => json_encode($previewPaths),
                'live_preview' => json_encode($livePreviewPaths),
                'status' => $request->status,
            ]);
        } catch (\Exception $e) {
            \Log::error('Product creation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'There was an error creating the product. Please try again.');
        }

        return redirect()->route('admin.viewproduct')->with('success', 'Product added successfully!');
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
        $products = Product::with('category')->latest()->get();
        return view('admin.viewProduct', compact('products'));
    }

    public function editproduct($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.editProduct', compact('product', 'categories'));
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
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:102400',
            'inline_preview' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:102400',
            'main_files.*' => 'required|mimes:zip|max:102400',
            'preview.*' => 'required|mimes:zip|max:102400',
            'live_preview.*' => 'nullable|mimes:zip|max:102400',
            'status' => 'required|in:approved,pending',
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
            'status' => $request->status,
        ]);

        return redirect()->route('admin.viewproduct')->with('success', 'Product updated successfully!');
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

        return redirect()->route('admin.viewproduct')->with('success', 'Product deleted successfully!');
    }
	
	public function messagePage($id)
	{
		$user = User::find($id);

		if (!$user) {
			abort(404, 'User not found');
		}

		return view('admin.messages', compact('user'));
	}
	
	public function messageSave(Request $request,$id)
	{
		$request->validate([
			'message_content' => 'required|string|max:1000',
		]);

		$message = Messages::create([
			'sender_id'   => auth()->id(),
			'receiver_id' => $id,
			'message'     => $request->message_content,	
			'sent_at'     => now(),
		]);
		
		/* $url = route('user.messagePage');
		
		$notification = Notifications::create([
			'sender_id'   => auth()->id(),
			'receiver_id' => $id, 
			'content'     => 'New message from: ' . auth()->user()->name,
			'url'         => $url,
			'sent_at'     => now(),
		]); */
		
		event(new MessageSent($message));
		
		return response()->json([
			'success' => true,
			'message' => 'Message and notification saved successfully'
		]);
	}
	
	public function fetchMessages(Request $request, $id)
	{
		$authId = auth()->id();

		$messages = Messages::where(function ($query) use ($authId, $id) {
			$query->where('sender_id', $authId)
				  ->where('receiver_id', $id);
		})
		->orWhere(function ($query) use ($authId, $id) {
			$query->where('sender_id', $id)
				  ->where('receiver_id', $authId);
		})
		->orderBy('sent_at', 'asc')
		->get();

		return response()->json([
			'messages' => $messages
		]);
	}
	
	public function adminProfile()
	{
		$user = User::find(auth()->id());
		return view('admin.profile', compact('user'));
	}
	
	public function updateProfile(Request $request)
	{
		$user = auth()->user();

		$rules = [
			'name'  => 'required|string|max:255',
			'email' => 'required|email|max:255|unique:users,email,' . $user->id,
		];

		if ($request->filled('password')) {
			$rules['password'] = 'required|string|min:6|confirmed';
		}

		$validator = Validator::make($request->all(), $rules);

		if ($validator->fails()) {
			return response()->json([
				'errors' => $validator->errors()
			], 422);
		}

		$user->name = $request->name;
		$user->email = $request->email;

		if ($request->filled('password')) {
			$user->password = Hash::make($request->password);
		}

		$user->save();

		return response()->json([
			'success' => true,
			'message' => 'Profile updated successfully.'
		]);
	}
	
	public function fetchNotifications()
	{
		$notifications = Notifications::where('receiver_id', auth()->id())->orderBy('created_at', 'desc')->take(5)->get();

		return response()->json(['notifications' => $notifications]);
	}
	
	public function allNotificationPage()
	{
		return view('admin.notifications');
	}
	
	public function fetchAllNotifications()
	{
		$allNotifications = Notifications::where('receiver_id', auth()->id())->orderBy('created_at', 'desc')->get();
		
		return response()->json(['allNotifications' => $allNotifications]);
	}
	
	public function markReadAsNotifications()
	{
		$notifications = Notifications::where('receiver_id', auth()->id())->whereNull('read_at')->get();

		foreach ($notifications as $notification) {
			$notification->update(['read_at' => now()]);
		}

		return response()->json([
			'status' => 'success',
			'message' => 'All notifications have been marked as read.'
		]);
	}
	public function viewCommunities()
	{
		return view('admin.viewCommunities');
	}

	public function fetchCommunities()
	{
		$communities = Community::with('user')
			->orderBy('id', 'desc')
			->get();

		$data_arr = [];

		foreach ($communities as $item) {
			$data_arr[] = [
				'id' => '<span class="text-xs fw-bold">' . $item->id . '</span>',
				'complaint' => '<span class="text-xs fw-bold text-muted">' . htmlspecialchars($item->complaint) . '</span>',
				'comment' => '<span class="text-xs text-muted">' . nl2br(e($item->comment)) . '</span>',
				'user' => '<span class="text-xs fw-bold text-primary">' . ($item->user?->name ?? 'Unknown') . '</span>',
				'created_at_human' => '<span class="text-xs text-secondary">' . $item->created_at->diffForHumans() . '</span>',
				'action' => '<a href="' . route('admin.replyCommunityForm', ['id' => $item->id]) . '" class="btn btn-sm btn-primary">Reply</a>',
			];
		}

		return response()->json([
			'data' => $data_arr,
		]);
	}

	public function replyCommunityForm($id)
	{
		$community = Community::with('user')->findOrFail($id);
		return view('admin.community-reply', compact('community'));
	}
	
	public function replyCommunity(Request $request, $id)
	{
		$request->validate([
			'admin_reply' => 'required|string',
		]);

		$community = Community::findOrFail($id);
		$community->admin_reply = $request->admin_reply;
		$community->save();
		
		event(new CommunityCreated($community));

		return response()->json([
			'message' => 'Reply saved successfully.'
		]);
	}

}
