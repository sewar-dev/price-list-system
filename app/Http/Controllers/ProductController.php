<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Repositories\ProductRepository;

class ProductController extends Controller
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository   = $productRepository;
    }

    public function index(ProductRequest $request)
    {
        return  sendResponse(
           true,
            __('messages.get-data'),
            ProductResource::collection($this->productRepository->getProductsWithPrices($request->getData())),
            200
        );

    }


    public function show(ProductRequest $request,string $id)
    {
        return  sendResponse(
            true,
             __('messages.get_data'),
             new ProductResource($this->productRepository->getProductsWithPrices($request->getData(),$id)),
             200
         );
    }


}
