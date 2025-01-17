<?php

namespace App\Business\Products;

use App\Domain\Products\Picture;
use App\Domain\Products\Product;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductsBusiness
{
    private IProductsRepository $repository;
    
    public function __construct(IProductsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getProduct(int $id): array
    {
        $product = $this->repository->getProduct($id);
        
        if (!empty($product)) {
            $product['foto_atual'] = $product['foto'] ?? '';
        }
        
        return $product;
    }
    
    public function getProducts(): Paginator
    {
        return $this->repository->getProducts();
    }
    
    public function createProduct(array $dto): array
    {
        $picture = new Picture($dto['foto'] ?? '');
        $product = new Product(0, $dto['nome'], $dto['descricao'], $dto['valor'], $picture);
        
        return $this->repository->create($this->mountProduct($product));
    }

    public function updateProduct(array $dto): int
    {
        if (isset($dto['foto']) && $dto['foto'] instanceof UploadedFile) {
            $this->deleteCurrentPicture($dto['foto_atual']);
        }

        $picture = new Picture($dto['foto'] ?? $dto['foto_atual'] ?? '');
        $product = new Product($dto['id'], $dto['nome'], $dto['descricao'], $dto['valor'], $picture);

        return $this->repository->update($product->getId(), $this->mountProduct($product));
    }
    
    public function deleteProduct(int $id): ?bool
    {
        $product = $this->repository->getProduct($id);
        
        $this->deleteCurrentPicture($product['foto']);

        return $this->repository->delete($id);
    }

    private function deleteCurrentPicture($foto)
    {
        if (empty($foto)) {
            return;
        }

        $file = explode('/', $foto);
        Storage::delete($file[1] . '/' . $file[2]);
    }
    
    private function mountProduct(Product $product): array
    {
        return [
            'nome' => $product->getNome(),
            'descricao' => $product->getDescricao(),
            'valor' => $product->getValor(),
            'foto' => $product->getFoto(),
        ];
    }
}