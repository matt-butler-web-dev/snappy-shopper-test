<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Shop;
use App\Models\Postcode;
use Illuminate\Support\Str;
use Carbon\Carbon;
class ShopController extends Controller
{
    /**
     * Retrieves all Shops, actionable by GET request to /api/shop
     */
    public function index()
    {
        $shops = Shop::all();
        return response()->json([
            'status' => true,
            'message' => 'Shops retrieved successfully',
            'data' => $shops
        ], 200);
    }

    /**
     * Retrieves a specific shop by id, actionable by GET request /api/shop/{$id}
     */
    public function show($id)
    {
        $shop = shop::findOrFail($id);
        return response()->json([
            'status' => true,
            'message' => 'Shop found successfully',
            'data' => $shop
        ], 200);
    }

    /**
     * Creates a new shop, actionable by POST request to /api/shop
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'postcode' => 'required|string|max:8',
            'opening_time' => 'required|string',
            'closing_time' => 'required|string',
            'shop_type_id'=> 'required|int',
            'max_delivery_km' => 'required|int'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if the provided postcode exists in our table, if not, return error.
        if (!$postcode = Postcode::where('postcode', Str::replace(' ', '', $request->input('postcode')))->first()) {
            return response()->json([
                'status' => false,
                'message' => 'The entered postcode does not exist',
            ], 422);
        }

        $shop = new Shop;
        $shop->name = $request->input('name');
        $shop->postcode = Str::replace(' ', '', $request->input('postcode'));
        $shop->lat = $postcode->lat;
        $shop->long = $postcode->long;
        $shop->opening_time = $request->input('opening_time');
        $shop->closing_time = $request->input('closing_time');
        $shop->shop_type_id = $request->input('shop_type_id');
        $shop->max_delivery_km = $request->input('max_delivery_km');
        $shop->save();

        return response()->json([
            'status' => true,
            'message' => 'New Shop created successfully',
            'data' => $shop
        ], 201);
    }

    /**
     * Deletes a specific shop by id, actionable by DELETE request to /api/shop/{$id}
     */
    public function destroy($id)
    {
        $shop = Shop::findOrFail($id);
        $shop->delete();

        return response()->json([
            'status' => true,
            'message' => 'Shop deleted successfully'
        ], 204);
    }

    /**
     * Searches for shops within a certain distance of a provided postcode.
     * Actionable by POST request to /api/shop/localShops
     */
    public function localShops(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'postcode' => 'required|string|max:8',
            'distance' => 'int'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if the provided postcode exists in our table, if not, return error.
        if (!$postcode = Postcode::where('postcode', Str::replace(' ', '', $request->input('postcode')))->first()) {
            return response()->json([
                'status' => false,
                'message' => 'The entered postcode does not exist',
            ], 422);
        }

        // distance() uses ScopeDistance on the Shop model to add a select for the distance
        // from provided postcode's lat and long values to the shop location
        $localShops = Shop::distance($postcode->lat, $postcode->long)
            ->having('distance', '<=', $request->input('distance') ?? 10)
            ->get();

        if ($localShops->count() > 0) {
            return response()->json([
                'status' => true,
                'message' => 'Shops found successfully',
                'data' => $localShops
            ], 200);
        }

        return response()->json([
            'status' => true,
            'message' => 'No shops available for provided postcode',
        ], 204);
    }

    /**
     * Searches for shops that are open and will deliver to a provided postcode.
     * Actionable by POST request to /api/shop/availableLocalShops
     */
    public function availableLocalShops(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'postcode' => 'required|string|max:8'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if the provided postcode exists in our table, if not, return error.
        if (!$postcode = Postcode::where('postcode', Str::replace(' ', '', $request->input('postcode')))->first()) {
            return response()->json([
                'status' => false,
                'message' => 'The entered postcode does not exist',
            ], 422);
        }

        $currentTime = Carbon::now()->format('H:i:s');

        // distance() uses ScopeDistance on the Shop model to add a select for the distance
        // from provided postcode's lat and long values to the shop location
        $availableLocalShops = Shop::distance($postcode->lat, $postcode->long)
            ->where('opening_time', '<=', $currentTime)
            ->where('closing_time', '>=', $currentTime)
            ->havingRaw('distance <= max_delivery_km')
            ->get();

        if ($availableLocalShops->count() > 0) {
            return response()->json([
                'status' => true,
                'message' => 'Shops found successfully',
                'data' => $availableLocalShops
            ], 200);
        }

        return response()->json([
            'status' => true,
            'message' => 'No shops available for provided postcode',
        ], 204);
    }
}
