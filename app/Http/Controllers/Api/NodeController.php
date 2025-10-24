<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Node;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), 
            [
                "existing_node_id" => "nullable|integer|exists:nodes,id",
                'number_of_parent' => 'required|integer|min:1',
                'number_of_children'=> 'required|min:0',
            ],
            [
                'existing_node_id.exists' => 'The specified existing node does not exist.',
            ] 
        );

        if ($validator->fails()) {
            return Response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ]);
        }

        $locale = $request->header('Accept-Language', config('app.locale'));

        $createdNodes = [];

        for ($number_of_parent=1; $number_of_parent <= $request->number_of_parent; $number_of_parent++) {

            $existing_node_id = isset($request->existing_node_id)
                                ? $request->existing_node_id 
                                : null;

            $parentCreated = Node::create([
                'title' => numberToWords($number_of_parent, $locale),
                'parent_id' => $existing_node_id,
            ]);
            
            if($request->number_of_children > 0){
                $childrens = [];
                for ($childIndex = 1; $childIndex <= $request->number_of_children; $childIndex++) {

                    $childCreated = Node::create([
                        'title' => numberToWords($childIndex, $locale),
                        'parent_id' => $parentCreated->id,
                    ]);
                    $childrens[] = $childCreated;
                }
                $parentCreated->childrens = $childrens;
            }

            $createdNodes[] = $parentCreated;
        }

        return Response()->json([
            'message' => 'Nodes created successfully',
            'nodes' => $createdNodes,
            'status' => 201,
        ], 201);
   
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
