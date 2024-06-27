<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\ProductService;
use App\Util\ResponseUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/products', name: 'api_products')]
class ProductController extends AbstractController
{
    private ProductService $productService;
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;

    public function __construct(
        ProductService $productService,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        $this->productService = $productService;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    #[Route('', name: '_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $products = $this->productService->findAll();
        return $this->json(ResponseUtil::success($products));
    }

    #[Route('', name: '_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $data = $request->getContent();
        $product = $this->serializer->deserialize($data, Product::class, 'json');

        $errors = $this->validator->validate($product);
        if (count($errors) > 0) {
            return $this->json(ResponseUtil::validationError($errors), Response::HTTP_BAD_REQUEST);
        }

        $this->productService->create($product);

        return $this->json(ResponseUtil::success($product), Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: '_show', methods: ['GET'])]
    public function show(string $id): Response
    {
        $product = $this->productService->find($id);
        if (!$product) {
            return $this->json(ResponseUtil::notFound('Product not found'), Response::HTTP_NOT_FOUND);
        }

        return $this->json(ResponseUtil::success($product));
    }

    #[Route('/{id}', name: '_update', methods: ['PUT'])]
    public function update(string $id, Request $request): Response
    {
        $product = $this->productService->find($id);
        if (!$product) {
            return $this->json(ResponseUtil::notFound('Product not found'), Response::HTTP_NOT_FOUND);
        }

        $data = $request->getContent();
        $this->serializer->deserialize($data, Product::class, 'json', ['object_to_populate' => $product]);

        $errors = $this->validator->validate($product);
        if (count($errors) > 0) {
            return $this->json(ResponseUtil::validationError($errors), Response::HTTP_BAD_REQUEST);
        }

        $this->productService->update($product);

        return $this->json(ResponseUtil::success($product));
    }

    #[Route('/{id}', name: '_delete', methods: ['DELETE'])]
    public function delete(string $id): Response
    {
        $product = $this->productService->find($id);
        if (!$product) {
            return $this->json(ResponseUtil::notFound('Product not found'), Response::HTTP_NOT_FOUND);
        }

        $this->productService->delete($product);

        return $this->json(ResponseUtil::success());
    }
}
