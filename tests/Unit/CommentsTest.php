<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentsTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        
        $this->visit('/addVideo');
        $this->submitForm(['video_id'=> 44, 'comment_text'=>'124132251', 'parent_comment'=>null]);
    }
}
