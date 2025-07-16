<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

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
        try {
            $response = Http::get($this->baseUrl . $endpoint, array_merge([
                'instanceKey' => $this->instanceKey,
                'signature' => $this->signature,
                'timestamp' => $this->timestamp,
            ], $params));

            // Check if the request was successful
            if ($response->successful()) {
                return $response->json();
            }

            // Return a default response if the request fails
            return [
                'error' => 'API request failed',
                'status' => $response->status(),
                'message' => 'Unable to process the request at this time.'
            ];

        } catch (\Exception $e) {
            // Return a default error response in case of exception
            return [
                'error' => 'Exception occurred',
                'message' => $e->getMessage()
            ];
        }
    }

    public function getCategories($language = 'en')
    {
        $params = [
            'language' => $language,
        ];

        $result = $this->request('GetRootCategoryInfoList', $params);

        // Check for errors in the response
        if (isset($result['error'])) {
            return []; // Return an empty array if an error occurred
        }

        return $result['CategoryInfoList']['Content'] ?? [];
    }

    public function getSubcategories($parentCategoryId, $language = 'en')
    {
        $params = [
            'parentCategoryId' => $parentCategoryId,
            'language' => $language,
        ];

        $result = $this->request('GetCategorySubcategoryInfoList', $params);

        if (isset($result['error'])) {
            return []; // Return an empty array if an error occurred
        }

        return $result['CategoryInfoList']['Content'] ?? [];
    }
    public function getParentCategories($CategoryId, $language = 'en')
    {
        $params = [
            'categoryId' => $CategoryId,
            'language' => $language,
        ];
        $result = $this->request('GetCategoryRootPath', $params);
        return $result['CategoryInfoList']['Content'];
    }
    public function searchItems(array $searchParams, $language = 'en', $framePosition, $frameSize, $blockList = '')
    {
        if (empty($searchParams)) {
            throw new \InvalidArgumentException('At least one search parameter is required: CategoryId, VendorName, VendorId, ItemTitle, BrandId, or Configurators.');
        }
// dd($searchParams);
        $xmlParameters = '<SearchItemsParameters>';
        foreach ($searchParams as $key => $value) {
            if (!empty($value)) {
                $xmlParameters .= "<{$key}>{$value}</{$key}>";
            }
        }
        $xmlParameters .= '</SearchItemsParameters>';

        $params = [
            'xmlParameters' => $xmlParameters,
            'framePosition' => $framePosition,
            'frameSize' => $frameSize,
            'blockList' => $blockList,
            'language' => $language,
        ];

        return $this->request('BatchSearchItemsFrame', $params);
    }

    public function batchSearchItemsFrame($categoryId, $language, $framePosition, $frameSize, $blockList)
    {
       // dd("hit");
        $xmlParameters = "<SearchItemsParameters><CategoryId>{$categoryId}</CategoryId></SearchItemsParameters>";

        $params = [
            'xmlParameters' => $xmlParameters,
            'framePosition' => $framePosition,
            'frameSize' => $frameSize,
            'blockList' => $blockList,
            'language' => $language,
        ];

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

    public function getFullItemInfo($itemId)
    {
        //dd($itemId);
        $params = [
            'itemId' => $itemId,
            'blockList' => 'Description',
            'language' => 'en',
        ];

        return $this->request('BatchGetItemFullInfo', $params);
    }

    public function getReviews($itemId, $framePosition = 0, $frameSize = 20)
    {
        $cacheKey = "reviews_{$itemId}_{$framePosition}_{$frameSize}";

        return Cache::remember($cacheKey, 1440, function () use ($itemId, $framePosition, $frameSize) {
            $xmlSearchParameters = "<SearchParameters><ItemId>{$itemId}</ItemId><Source>Provider</Source></SearchParameters>";

            $params = [
                'xmlSearchParameters' => $xmlSearchParameters,
                'framePosition' => $framePosition,
                'frameSize' => $frameSize,
            ];

            $response = $this->request('SearchItemReviews', $params);

            if (isset($response['Result']) && isset($response['Result']['Content'])) {
                return $response;
            }

            return ['error' => 'Invalid response']; // Return error if response is invalid
        });
    }

    public function getTrendingProducts($categoryId = 0, $itemRatingType = 'Popular', $framePosition = 0, $frameSize = 5)
    {
      // dd("hit tr");
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
        return $result['OtapiItemInfoSubList']['Content'] ?? [];
    }

    // public function getTopSellers($language = 'en', $limit = 20)
    // {
    //     // Prepare the XML search parameters
    //     $xmlSearchParameters = urlencode('<BatchRatingListSearchParameters><RatingLists><RatingList><CategoryId>0</CategoryId><ItemRatingType>Best</ItemRatingType><IsRandomSearch>false</IsRandomSearch><ContentType>Vendor</ContentType><FramePosition>0</FramePosition><FrameSize>' . $limit . '</FrameSize></RatingList></RatingLists></BatchRatingListSearchParameters>');

    //     // Prepare the request parameters
    //     $params = [
    //         'instanceKey' => $this->instanceKey,
    //         'language' => $language,
    //         'signature' => $this->signature,
    //         'timestamp' => $this->timestamp,
    //         'applicationType' => '', // Set if needed
    //         'xmlSearchParameters' => $xmlSearchParameters,
    //     ];

    //     // Make the API request
    //     $result = $this->request('BatchSearchRatingLists', $params);

    //     // Check for errors in the response
    //     if (isset($result['error'])) {
    //         return []; // Return an empty array if an error occurred
    //     }

    //     return $result['Result']['Vendors'] ?? [];
    // }


    public function getBestSellers($framePosition = 0, $frameSize = 5)
    {
        $xmlSearchParameters = "<BatchRatingListSearchParameters>
                                    <RatingLists>
                                        <RatingList>
                                            <CategoryId>0</CategoryId>
                                            <ItemRatingType>Best</ItemRatingType>
                                            <IsRandomSearch>false</IsRandomSearch>
                                            <ContentType>Vendor</ContentType>
                                            <FramePosition>{$framePosition}</FramePosition>
                                            <FrameSize>{$frameSize}</FrameSize>
                                        </RatingList>
                                    </RatingLists>
                                </BatchRatingListSearchParameters>";

        $params = [
            'language' => 'en',
            'xmlSearchParameters' => $xmlSearchParameters,
        ];

        $result = $this->request('BatchSearchRatingLists', $params);
        return $result['Result']['Vendors'] ?? [];
    }

    // public function getBestSellers($framePosition = 0, $frameSize = 5)
    // {
    //     $cacheKey = "best_sellers_{$framePosition}_{$frameSize}";

    //     // Attempt to retrieve from cache
    //     return Cache::remember($cacheKey, 3600, function () use ($framePosition, $frameSize) {
    //         $xmlSearchParameters = "<BatchRatingListSearchParameters>
    //                                     <RatingLists>
    //                                         <RatingList>
    //                                             <CategoryId>0</CategoryId>
    //                                             <ItemRatingType>Best</ItemRatingType>
    //                                             <IsRandomSearch>false</IsRandomSearch>
    //                                             <ContentType>Vendor</ContentType>
    //                                             <FramePosition>{$framePosition}</FramePosition>
    //                                             <FrameSize>{$frameSize}</FrameSize>
    //                                         </RatingList>
    //                                     </RatingLists>
    //                                 </BatchRatingListSearchParameters>";

    //         $params = [
    //             'language' => 'en',
    //             'xmlSearchParameters' => $xmlSearchParameters,
    //         ];

    //         $result = $this->request('BatchSearchRatingLists', $params);

    //         // Return the content, or an empty array if not found
    //         return $result['Result']['Vendors'] ?? [];
    //     });
    // }


}
