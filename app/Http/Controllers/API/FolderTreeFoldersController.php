<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\FolderTreeFoldersRessource;
use App\Models\FolderTreeFolders;
use Illuminate\Http\Request;

class FolderTreeFoldersController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $folder_tree_folders = FolderTreeFolders::all();
        return $this->sendResponse(FolderTreeFoldersRessource::collection($folder_tree_folders), 'folder tree folders retrieved successfully.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
