<?php

namespace App\Heaps;

use SplMaxHeap;

class PostHeap extends SplMaxHeap
{
    protected function compare($post1, $post2)
    {
        return $post1['score'] <=> $post2['score'];
    }
}
