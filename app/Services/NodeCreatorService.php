<?php
namespace App\Services;

use App\Models\Node;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;

class NodeCreatorService
{
    
    public function createNodes(array $data): array
    {
        $numParents = $data['number_of_parent'];
        $numChildren = $data['number_of_children'];
        $existingNodeId = $data['existing_node_id'] ?? null;
        
        $locale = App::getLocale(); 

        $createdNodes = [];

        DB::transaction(function () use ($numParents, $numChildren, $existingNodeId, $locale, &$createdNodes) {
            
            for ($p = 1; $p <= $numParents; $p++) {
                
                $parent = Node::create([
                    'title' => numberToWords($p, $locale),
                    'parent_id' => $existingNodeId,
                ]);
                
                if ($numChildren > 0) {
                    $childrenData = [];
                    for ($c = 1; $c <= $numChildren; $c++) {
                        $childrenData[] = [
                            'title' => numberToWords($c, $locale),
                            'parent_id' => $parent->id,
                            'created_at' => now(), 
                            'updated_at' => now(),
                        ];
                    }
                    
                    Node::insert($childrenData); 
                    
                    $parent->load('children');
                }
                
                $createdNodes[] = $parent;
            }
        });

        return $createdNodes;
    }
}