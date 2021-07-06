<?php

namespace Tests\Feature;

use Illuminate\Http\Response;
use Tests\TestCase;

class ApiTest extends TestCase
{
    /**
     * Test check connection
     *
     * @return void
     */
    public function test_check_connection()
    {
        $response = $this->get('/');

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * Test connection
     *
     * @return void
     */
    public function test_get_images_request()
    {
        $response = $this->get('/api/images');

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * Get images response in valid format
     *
     * @return void
     */

    public function test_get_images_response_in_valid_format()
    {
        $this->json('get', 'api/images')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'current_page',
                'data' => [
                     '*' => [
                        'id',
                        'title',
                        'src',
                        'actual_src',
                        'height',
                        'width',
                        'mime_type',
                        'created_at',
                        'updated_at',
                        'image_src',
                        'meta',
                    ],
                ],
                'first_page_url',
                'from',
                'last_page',
                'last_page_url',
                'links',
                'next_page_url',
            ]);
    }


    /**
     * Get  an image response in valid format
     *
     * @return void
     */

    public function test_get_an_images_response_in_valid_format()
    {
        $this->json('get', 'api/images/1')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'id',
                'title',
                'src',
                'actual_src',
                'height',
                'width',
                'mime_type',
                'created_at',
                'updated_at',
                'image_src',
                'meta',
            ]);
    }
}
