<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexNodeRequest;
use App\Http\Requests\StoreNodeRequest;
use App\Http\Resources\NodeResource;
use App\Models\Node;
use App\Services\NodeCreatorService;


class NodeController extends Controller
{
    protected $nodeCreator;

    public function __construct(NodeCreatorService $nodeCreator)
    {
        $this->nodeCreator = $nodeCreator;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(IndexNodeRequest $request)
    {
        
        $depth = (int) $request->validated('depth', 1);

        $nodes = Node::withDepth($depth)
            ->whereNull('parent_id')
            ->get();

        return NodeResource::collection($nodes)
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNodeRequest $request)
    {
        $createdNodes = $this->nodeCreator->createNodes($request->validated());

        return NodeResource::collection($createdNodes)
            ->response()
            ->setStatusCode(201);
   
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $node = Node::find($id);

        if (! $node) {
            return response()->json(['message' => 'Node not found'], 404);
        }

        if ($node->children()->exists()) {
            return response()->json([
                'message' => 'A node with children cannot be deleted.'
            ], 400);
        }

        $node->delete();

        return response()->json([
                'message' => 'Nodo deleted'
            ], 200);
    }
}
