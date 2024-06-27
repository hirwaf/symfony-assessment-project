<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class ProductService
 */
class ProductService
{
    private EntityManagerInterface $entityManager;
    private ProductRepository $productRepository;

    public function __construct(EntityManagerInterface $entityManager, ProductRepository $productRepository)
    {
        $this->entityManager = $entityManager;
        $this->productRepository = $productRepository;
    }

    /**
     * Returns all products from the database.
     *
     * @return array
     */
    public function findAll(): array
    {
        return $this->productRepository->findAll();
    }

    /**
     * Finds a product by ID.
     *
     * @param string $id The ID of the product.
     * @return null|Product Returns the found product, or null if not found.
     */
    public function find(string $id): null|Product
    {
        return $this->productRepository->find($id);
    }

    /**
     *  Create a product
     *
     * @param Product $product
     * @return void
     */
    public function create(Product $product): void
    {
        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }

    /**
     * Updates a product.
     *
     * @param Product $product The product to be updated.
     * @return void
     */
    public function update(Product $product): void
    {
        $this->entityManager->flush();
    }

    /**
     * Deletes a product.
     *
     * @param Product $product The product to be deleted.
     * @return void
     */
    public function delete(Product $product): void
    {
        $this->entityManager->remove($product);
        $this->entityManager->flush();
    }
}
