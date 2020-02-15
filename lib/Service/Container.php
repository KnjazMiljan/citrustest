<?php

namespace Service;

use Model\Product;
use PDO;
use Model\Comment;
use ReflectionException;

class Container
{
    private $configuration;

    private $pdo;

    private $comment;

    private $product;

    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @return PDO
     */
    public function getPDO()
    {
        if ($this->pdo === null) {
            $this->pdo = new PDO(
                $this->configuration['db_dsn'],
                $this->configuration['db_user'],
                $this->configuration['db_pass']
            );

            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return $this->pdo;
    }


    /**
     * @return Comment
     * @throws ReflectionException
     */
    public function getComment()
    {
        if ($this->comment === null) {
            $this->comment = new Comment($this->pdo);
        }

        return $this->comment;
    }

    /**
     * @return Product
     * @throws ReflectionException
     */
    public function getProduct()
    {
        if ($this->product === null) {
            $this->product = new Product($this->pdo);
        }

        return $this->product;
    }
}