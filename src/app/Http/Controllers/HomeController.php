<?php

namespace App\Http\Controllers;

use App\Service\FeedReaderServiceInterface;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Feed as FeedModel;
use Illuminate\Support\Facades\Redirect;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @param FeedReaderServiceInterface $reader
     * @return Renderable
     */
    public function index(FeedReaderServiceInterface $reader)
    {
        $feeds = FeedModel::all();
        $rss = [];
        foreach($feeds as $feed) {
            $rss[$feed->id] = $reader->parseFeed($feed->url);
        }

        return view('home', ['feeds' => $feeds, 'rss' => $rss]);
    }

    /**
     * Show the feed index.
     *
     * @return Renderable
     */
    public function list()
    {
        return view('list', ['feeds' => FeedModel::all()]);
    }

    /**
     * Show the posts for the given feed.
     *
     * @param FeedReaderServiceInterface $reader
     * @param int $id
     * @return View
     */
    public function show(FeedReaderServiceInterface $reader, int $id)
    {
        $feed = FeedModel::findOrFail($id);
        $rss = $reader->parseFeed($feed->url);

        return view('feed', ['feed' => $feed, 'rss' => $rss]);
    }

    /**
     * Show form for adding a new feed to the system.
     *
     * @return View
     */
    public function add()
    {
        return view('add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:5',
            'url' => 'required|url|unique:feeds'
        ], [
            'name.required' => 'Name is required',
            'url.required' => 'Url is required'
        ]);

        $feed = new FeedModel([
            'name' => $request->name,
            'url' => $request->url,
        ]);
        $user = auth()->user();
        $user->feeds()->save($feed);

        return Redirect::to('home')->with('status', 'Feed created successfully.');
    }
}
