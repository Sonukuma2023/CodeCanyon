<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Community;
use App\Events\CommunityCreated;

class DeveloperController extends Controller
{
    public function dashboard()
	{
		return view('developer.dashboard');
	}
	
	public function viewCommunities()
	{
		return view('developer.viewCommunities');
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
				'complaint' => '<span class="text-xs fw-bold text-dark">' . htmlspecialchars($item->complaint) . '</span>',
				'comment' => '<span class="text-xs text-muted">' . nl2br(e($item->comment)) . '</span>',
				'user' => '<span class="text-xs fw-bold text-primary">' . ($item->user?->name ?? 'Unknown') . '</span>',
				'created_at_human' => '<span class="text-xs text-secondary">' . $item->created_at->diffForHumans() . '</span>',
				'action' => '<a href="' . route('developer.replyCommunityForm', ['id' => $item->id]) . '" class="btn btn-sm btn-primary">Reply</a>',
			];
		}

		return response()->json([
			'data' => $data_arr,
		]);
	}

	public function replyCommunityForm($id)
	{
		$community = Community::with('user')->findOrFail($id);
		return view('developer.community-reply', compact('community'));
	}
	
	public function replyCommunity(Request $request, $id)
	{
		$request->validate([
			'developer_reply' => 'required|string',
		]);

		$community = Community::findOrFail($id);
		$community->developer_reply = $request->developer_reply;
		$community->save();
		
		event(new CommunityCreated($community));

		return response()->json([
			'message' => 'Reply saved successfully.'
		]);
	}
}
