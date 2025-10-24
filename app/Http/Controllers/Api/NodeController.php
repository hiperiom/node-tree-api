<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNodeRequest;
use App\Http\Resources\NodeResource;
use App\Models\Node;
use App\Services\NodeCreatorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
    public function index(Request $request)
    {
        $timezone = $request->header('X-Timezone') ?? 'UTC';
        app()->instance('current.timezone', $timezone);

        $validator = Validator::make($request->query(), [
            'depth' => ['nullable','integer','min:1','max:5'], // max 5 para proteger el rendimiento
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Invalid depth parameter', 'errors' => $validator->errors()], 400);
        }

        $depth = (int) $request->query('depth', 1);

        $relations = [];
        $relation = 'children';
        for ($i = 1; $i <= $depth; $i++) {
            $relations[] = $relation;
            $relation .= '.children';
        }

        $nodes = Node::with($relations)
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
     * Display the specified resource.
     */
    public function show(string $id)
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
