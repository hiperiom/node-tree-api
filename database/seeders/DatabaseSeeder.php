<?php

namespace Database\Seeders;

use App\Models\Node;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        foreach (["One","Two"] as $keyParent => $valueParent) {
            $parentCreated = Node::create([
                'title' => 'Parent ' .$valueParent,
                'parent_id' => null,
            ]);
            if ($valueParent === 'One') {
                foreach (["One","Two"] as $keyChild =>  $valueChild) {
                    $childCreated = Node::create([
                        'title' => 'Child ' .$valueChild. ' of Parent ' .$valueParent,
                        'parent_id' => $parentCreated->id,
                    ]);

                    if ($valueChild === 'One') {
                        foreach (["One","Two"] as $valueGrandChild) {
                            Node::create([
                                'title' => 'GrandChild ' .$valueGrandChild. ' of ParentChild ' .$valueChild,
                                'parent_id' => $childCreated->id,
                            ]);
                            if ($valueChild === 'One') {

                            }
                        }
                    }
                }
            }
        }
        
    }
}
