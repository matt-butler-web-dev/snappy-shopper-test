<?php

namespace Tests\Feature;

use Illuminate\Http\Response;
use Tests\TestCase;
use App\Models\Shop;

class ShopControllerTest extends TestCase {

    // Test api/shop GET request return format
    public function testAllShopsReturnsDataInValidFormat() {

        $this->json('get', 'api/shop')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'postcode',
                            'lat',
                            'long',
                            'opening_time',
                            'closing_time',
                            'max_delivery_km',
                            'shop_type_id'
                        ]
                    ]
                ]
            );
    }

    // Test api/shop/{$id} GET request format
    public function testGetShopByIdReturnsDataInValidFormat() {
        $this->json('get', 'api/shop/1')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    'data' => [
                        'id',
                        'name',
                        'postcode',
                        'lat',
                        'long',
                        'opening_time',
                        'closing_time',
                        'max_delivery_km',
                        'shop_type_id'
                    ]
                ]
            );
    }

    // Test api/shop/{$id} GET request shop not found
    public function testShopDoesNotExistReturns404() {
        $this->json('get', 'api/shop/0')
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    // Test api/shop POST creates
    public function testShopPostRequestCreates() {
        $payload = [
            'name' => 'Test New Shop',
            'postcode' => 'PE4 5BY',
            'opening_time' => '09:00',
            'closing_time' => '17:00',
            'max_delivery_km' => 10,
            'shop_type_id' => 1
        ];

        $this->json('post', 'api/shop', $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(
                [
                    'data' => [
                        'id',
                        'name',
                        'postcode',
                        'lat',
                        'long',
                        'opening_time',
                        'closing_time',
                        'max_delivery_km',
                        'shop_type_id'
                    ]
                ]
            );
    }

    // Test api/shop POST request validation error
    public function testShopCreateEmptyPayloadValidationError() {

        $this->json('post', 'api/shop', [])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['errors']);
    }

    // Test api/shop POST postcode incorrect error
    public function testShopCreateInvalidPostcode() {
        $payload = [
            'name' => 'Test New Shop',
            'postcode' => 'WRONG',
            'opening_time' => '09:00',
            'closing_time' => '17:00',
            'max_delivery_km' => 10,
            'shop_type_id' => 1
        ];

        $this->json('post', 'api/shop', $payload)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    // Test api/shop POST shop type incorrect error
    public function testShopCreateInvalidShopType() {
        $payload = [
            'name' => 'Test New Shop',
            'postcode' => 'WRONG',
            'opening_time' => '09:00',
            'closing_time' => '17:00',
            'max_delivery_km' => 10,
            'shop_type_id' => 1
        ];

        $this->json('post', 'api/shop', $payload)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    // Test api/shop/{$id} DELETE works
    public function testShopIsDestroyed()
    {
        $shop = Shop::orderBy('created_at', 'desc')->first();
        $this->json('delete', "api/shop/$shop->id")
            ->assertNoContent();
        $this->assertDatabaseMissing('shops', $shop->toArray());
    }

    // Test api/shop/{$id} DELETE user doesn't exist error
    public function testDestroyForMissingStore() {

        $this->json('delete', 'api/store/0')
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

}
