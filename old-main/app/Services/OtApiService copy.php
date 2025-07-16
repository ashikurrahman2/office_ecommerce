<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class OtApiService
{
    protected $baseUrl;
    protected $instanceKey;
    protected $signature;
    protected $timestamp;

    public function __construct()
    {
        $this->baseUrl = env('OT_API_BASE_URL');
        $this->instanceKey = env('OT_API_INSTANCE_KEY');
        $this->signature = env('OT_API_SIGNATURE');
        $this->timestamp = env('OT_API_TIMESTAMP');

    }

    public function request($endpoint, $params = [])
    {
        $response = Http::get($this->baseUrl . $endpoint, array_merge([
            'instanceKey' => $this->instanceKey,
            'signature' => $this->signature,
            'timestamp' => $this->timestamp,
        ], $params));
        
        return $response->json();
    }

     /**
     * Retrieve root categories from the OT API.
     *
     * @param string $language
     * @return array
     */
    public function getCategories($language = 'en')
    {
        // Prepare the parameters for the request
        $params = [
            'language' => $language,
        ];
        
        $result = $this->request('GetRootCategoryInfoList', $params);
        return $result['CategoryInfoList']['Content'];
    }

    /**
     * Retrieve subcategories from the OT API.
     *
     * @param string $parentCategoryId
     * @param string $language
     * @return array
     */
    public function getSubcategories($parentCategoryId, $language = 'en')
    {
        $params = [
            'parentCategoryId' => $parentCategoryId,
            'language' => $language,
        ];
        $result = $this->request('GetCategorySubcategoryInfoList', $params);
        return $result['CategoryInfoList']['Content'];
    }

    /**
     * Search for items based on various parameters.
     *
     * @param array $searchParams
     * @param string $language
     * @param int $framePosition
     * @param int $frameSize
     * @param string $blockList
     * @return array
     */
    public function searchItems(array $searchParams, $language = 'en', $framePosition, $frameSize, $blockList = '')
    {
        // Validate at least one search parameter is provided
        if (empty($searchParams)) {
            throw new \InvalidArgumentException('At least one search parameter is required: CategoryId, VendorName, VendorId, ItemTitle, BrandId, or Configurators.');
        }

        // Build XML parameters from search params
        $xmlParameters = '<SearchItemsParameters>';
        foreach ($searchParams as $key => $value) {
            if (!empty($value)) {
                $xmlParameters .= "<{$key}>{$value}</{$key}>";
            }
        }
        $xmlParameters .= '</SearchItemsParameters>';
        
        // Prepare the parameters for the request
        $params = [
            'xmlParameters' => $xmlParameters,
            'framePosition' => $framePosition,
            'frameSize' => $frameSize,
            'blockList' => $blockList,
            'language' => $language,
        ];

        // Make the request to BatchSearchItemsFrame
        return $this->request('BatchSearchItemsFrame', $params);
    }

    /**
     * Function to retrieve items from BatchSearchItemsFrame.
     *
     * @param string $categoryId
     * @param string $language
     * @param int $framePosition
     * @param int $frameSize
     * @param string $blockList
     * @return array
     */
    public function batchSearchItemsFrame($categoryId, $language, $framePosition, $frameSize, $blockList)
    {
        $xmlParameters = "<SearchItemsParameters><CategoryId>{$categoryId}</CategoryId></SearchItemsParameters>";
        
        // Prepare the parameters to be sent in the request
        $params = [
            'xmlParameters' => $xmlParameters,
            'framePosition' => $framePosition,
            'frameSize' => $frameSize,
            'blockList' => $blockList,
            'language' => $language,
        ];

        // Call the request method with the appropriate endpoint and parameters
        return $this->request('BatchSearchItemsFrame', $params);
    }

    public function vendorWiseProducts($CategoryId, $framePosition = 0, $frameSize, $blockList = '')
    {
        $xmlParameters = "<SearchItemsParameters><VendorId>{$CategoryId}</VendorId></SearchItemsParameters>";
        $params = [
            'xmlParameters' => $xmlParameters,
            'framePosition' => $framePosition,
            'frameSize' => $frameSize,
            'blockList' => $blockList
        ];

        return $this->request('BatchSearchItemsFrame', $params);
    } 
    
    /**
     * Get full item info for a specific product.
     */
    public function getFullItemInfo($itemId)
    {
        $params = [
            'itemId' => $itemId,
            'blockList' => 'Description',
            'language' => 'en',
        ];
        
        return $this->request('BatchGetItemFullInfo', $params);
    }
    
    public function getReviews($itemId, $framePosition = 0, $frameSize = 20)
    {
        // Use productId, offset, and page size as part of the cache key
        $cacheKey = "reviews_{$itemId}_{$framePosition}_{$frameSize}";

        // Cache the result for a longer period (e.g., 24 hours, or 1440 minutes)
        return Cache::remember($cacheKey, 1440, function () use ($itemId, $framePosition, $frameSize) {
            $xmlSearchParameters = "<SearchParameters><ItemId>{$itemId}</ItemId><Source>Provider</Source></SearchParameters>";

            $params = [
                'xmlSearchParameters' => $xmlSearchParameters,
                'framePosition' => $framePosition,
                'frameSize' => $frameSize,
            ];

            try {
                $response = $this->request('SearchItemReviews', $params);

                // Ensure the result is valid before caching it
                if (isset($response['Result']) && isset($response['Result']['Content'])) {
                    return $response;
                }

                // If the response is invalid, log an error and return null
                Log::error('Invalid response from API in getReviews', [
                    'itemId' => $itemId,
                    'framePosition' => $framePosition,
                    'frameSize' => $frameSize,
                    'response' => $response,
                ]);

                return null;

            } catch (\Exception $e) {
                // Log the exception in case of an error
                Log::error('API request failed in getReviews', [
                    'itemId' => $itemId,
                    'framePosition' => $framePosition,
                    'frameSize' => $frameSize,
                    'error' => $e->getMessage(),
                ]);

                return null;
            }
        });
    }

    public function getTrendingProducts($categoryId = 0, $itemRatingType = 'Popular', $framePosition = 0, $frameSize = 5)
    {
        $xmlSearchParameters = "<RatingListItemSearchParameters>
                                    <CategoryId>{$categoryId}</CategoryId>
                                    <ItemRatingType>{$itemRatingType}</ItemRatingType>
                                </RatingListItemSearchParameters>";

        $params = [
            'language' => 'en',
            'xmlSearchParameters' => $xmlSearchParameters,
            'framePosition' => $framePosition,
            'frameSize' => $frameSize,
        ];

        $result = $this->request('SearchRatingListItems', $params);
        return $result['OtapiItemInfoSubList']['Content'];
    }
}

