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

        $locale = app()->getLocale();
        $numberValues = [1,2];
        foreach ($numberValues as $keyParent => $valueParent) {
            $parentCreated = Node::create([
                'title' => 'Parent ' . numberToWords($valueParent, $locale),
                'parent_id' => null,
            ]);
            if ($valueParent === 1) {
                foreach ($numberValues as $keyChild =>  $valueChild) {
                    $childCreated = Node::create([
                        'title' => 'Child ' .numberToWords($valueChild, $locale). ' of Parent ' .numberToWords($valueParent, $locale),
                        'parent_id' => $parentCreated->id,
                    ]);

                    if ($valueChild === 1) {
                        foreach ($numberValues as $valueGrandChild) {
                            Node::create([
                                'title' => 'GrandChild ' .numberToWords($valueGrandChild, $locale). ' of ParentChild ' .numberToWords($valueChild, $locale),
                                'parent_id' => $childCreated->id,
                            ]);
                            
                        }
                    }
                }
            }
        }
        
    }
}
