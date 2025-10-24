<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexNodeRequest;
use App\Http\Requests\StoreNodeRequest;
use App\Http\Resources\NodeResource;
use App\Models\Node;
use App\Services\NodeCreatorService;

/**
 * @OA\Schema(
 *   schema="NodeResource",
 *   type="object",
 *   @OA\Property(property="id", type="integer"),
 *   @OA\Property(property="name", type="string"),
 *   @OA\Property(property="parent_id", type="integer", nullable=true),
 *   @OA\Property(property="children", type="array", @OA\Items(ref="#/components/schemas/NodeResource")),
 *   @OA\Property(property="created_at", type="string", format="date-time"),
 *   @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *   schema="NodeInput",
 *   type="object",
 *   @OA\Property(property="name", type="string"),
 *   @OA\Property(property="parent_id", type="integer", nullable=true)
 * )
 * @OA\Schema(
 * schema="NodeCreationRequest",
 * title="Parámetros de Creación de Nodos",
 * required={"number_of_parent", "number_of_children"},
 * @OA\Property(
 * property="number_of_parent",
 * type="integer",
 * description="Número de nodos padre a crear.",
 * example=2
 * ),
 * @OA\Property(
 * property="number_of_children",
 * type="integer",
 * description="Número de hijos para cada nodo padre creado.",
 * example=2
 * ),
 
 * )
 * @OA\SecurityScheme(
 * securityScheme="bearerAuth", 
 * type="http",
 * scheme="bearer",
 * bearerFormat="JWT", 
 * description="Introduzca el token JWT en el formato Bearer {token}"
 * )
 */
class NodeController extends Controller
{
    protected $nodeCreator;

    public function __construct(NodeCreatorService $nodeCreator)
    {
        $this->nodeCreator = $nodeCreator;
    }
    /**
     * @OA\Get(
     * path="/api/nodes",
     * summary="Obtiene la lista de nodos raíz con hijos anidados.",
     * @OA\Parameter(
     *   name="depth",
     *   in="query",
     *   description="Nivel de profundidad para cargar las relaciones de hijos.",
     *   required=false,
     *   @OA\Schema(type="integer", format="int32", minimum=1, maximum=5, default=1)
     * ),
     * @OA\Response(
     *   response=200,
     *   description="Operación exitosa",
     *   @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/NodeResource"))
     * ),
     * @OA\Response(response=400, description="Parámetros inválidos")
     * )
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
     * @OA\Post(
     *   path="/api/nodes",
     *   summary="Crea uno o varios nodos.",
     * @OA\Parameter(
     * name="Accept-Language",
     * in="header",
     * description="El código de idioma preferido para las respuestas (e.g., es, en).",
     * required=true,
     * @OA\Schema(
     * type="string",
     * default="es"
     * )
     * ),
    * @OA\RequestBody(
     * required=true,
     * description="Datos para la creación de nuevos nodos.",
     * @OA\JsonContent(ref="#/components/schemas/NodeCreationRequest")
     * ),
     *   @OA\Response(
     *     response=201,
     *     description="Nodos creados",
     *     @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/NodeResource"))
     *   ),
     *   @OA\Response(response=422, description="Validación fallida")
     * )
     */
    public function store(StoreNodeRequest $request)
    {
        $createdNodes = $this->nodeCreator->createNodes($request->validated());

        return NodeResource::collection($createdNodes)
            ->response()
            ->setStatusCode(201);
   
    }

    /**
     * @OA\Delete(
     *   path="/api/nodes/{id}",
     *   summary="Elimina un nodo si no tiene hijos.",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Nodo eliminado",
     *     @OA\JsonContent(@OA\Property(property="message", type="string"))
     *   ),
     *   @OA\Response(response=400, description="No se puede eliminar porque tiene hijos"),
     *   @OA\Response(response=404, description="No encontrado")
     * )
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
