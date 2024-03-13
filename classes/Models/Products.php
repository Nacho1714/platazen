<?php

namespace App\Models;

use App\Database\DBConexion;
use App\Models\ProductStates;
use App\Models\Categories;
use App\Models\User;
use App\Pagination\Paginator;
use PDO;

class Products extends BaseModel
{

    protected int $idProduct;
    protected int $fkCategory;
    protected int $fkUser;
    protected int $fkProductState;
    protected string $name;
    protected string $description;
    protected float $price;
    protected int $stock;
    protected string $image;
    protected string $imageDescription;
    protected int $featured;
    protected int $cant;

    protected Categories $category;
    protected User $user;
    protected ProductStates $state;
    protected Paginator $paginator;

    protected string $table = "products";
    protected string $primaryKey = "idProduct";

    protected array $properties = [
        'idProduct',
        'fkCategory',
        'fkUser',
        'fkProductState',
        'name',
        'description',
        'price',
        'stock',
        'image',
        'imageDescription',
        'featured',
    ];

    /**
     * Obtiene todas los productos. 
     * 
     * @param array $arrayParams Un arreglo asociativo con los parámetros de la consulta.
     * 
     * @return Products[] 
     */
    public function getAll(array $arrayParams = []): array
    {
        
        if (empty($arrayParams['LIMIT'])) {
        
            [$paramsQuery, $executeParams] = $this->buildQueryParams($arrayParams);
            
            $totalRecords = $this->numberOfRecords($paramsQuery, $executeParams);
            
            $this->paginator = new Paginator($totalRecords);
            
        } else {

            
            $this->paginator = new Paginator($arrayParams['LIMIT']['cant']);
            
            [$paramsQuery, $executeParams] = $this->buildQueryParams($arrayParams);

        }     

        // De alguna forma debo mandar en "$arrayParams" la cantidad de registros a obtener y la página actual, pero de tal forma que no afecte a las demas funciones como "published", "lastAdded" y "featured". Si hago la modificacion aca:

        // $arrayParams['LIMIT'] = ['cant' => $this->paginator->getRecordsPerPage()];

        // Se va a ver afectado el resto de las funciones, ya que se va a estar aplicando el LIMIT a todas las consultas.

        
        $db = DBConexion::getConexion();
        // $query = "SELECT p.*, c.idCategory, c.name AS 'category', c.description, c.image AS 'categoryImage', c.imageDescription, u.idUser, u.email, u.password, u.name AS 'user', u.surname, ps.idProductState, ps.name AS 'state' FROM products p INNER JOIN categories c ON p.fkCategory = c.idCategory INNER JOIN product_states ps ON p.fkProductState = ps.idProductState INNER JOIN users u ON p.fkUser = u.idUser {$paramsQuery};";
        $query = 
        "SELECT 
            p.*, 	c.idCategory, 				u.idUser, 			ps.idProductState,
                    c.name AS 'category', 		u.name AS 'user', 	ps.name AS 'state',
                    c.description, 				u.email,
                    c.image AS 'categoryImage', u.password,
                    c.imageDescription, 		u.surname
        FROM 		products p 
        INNER JOIN categories c 		ON p.fkCategory 	= c.idCategory 
        INNER JOIN product_states ps 	ON p.fkProductState = ps.idProductState 
        INNER JOIN users u 				ON p.fkUser 		= u.idUser 
        {$paramsQuery};";
        
        $stmt = $db->prepare($query);
        $stmt->execute($executeParams);

        $products = [];

        while ($obj = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $products[] = $this->generateProductRow($obj);
        }


        $totalRecords = $totalRecords ?? $this->numberOfRecords($paramsQuery, $executeParams);
        
        $this->paginator->setTotalRecords($totalRecords);

        return $products;
    }

    protected function numberOfRecords($paramsQuery, $executeParams)
    {
        $db = DBConexion::getConexion();

        $queryPagination = 
        "SELECT count(*) AS 'total'
        FROM 	   products p 
        INNER JOIN categories c 		ON p.fkCategory 	= c.idCategory 
        INNER JOIN product_states ps 	ON p.fkProductState = ps.idProductState 
        INNER JOIN users u 				ON p.fkUser 		= u.idUser 
        {$paramsQuery};";

        $stmtPage = $db->prepare($queryPagination);
        $stmtPage->execute($executeParams);

        $rowPage = $stmtPage->fetch();

        // echo "<pre>";
        // print_r($queryPagination);
        // print_r($executeParams);
        // echo "</pre>";
        // exit;

        return $rowPage['total'];
    }

    /**
     * Genera una instancia de la clase Products con los datos de la fila obtenida de la query.
     * Este método esta pensado para ser utilizado dentro del método Products::getAll().
     * 
     * @param array $row Un arreglo asociativo con los datos de la fila obtenida de la query.
     * @return Products Una instancia de la clase Products con los datos de la fila obtenida de la query.
     */
    protected function generateProductRow(array $row): self
    {
        $product    = new self;
        $category   = new Categories;
        $user       = new User;
        $state      = new ProductStates;

        $product->assignProperties($row);
        $category->assignProperties($this->arrayMapping($row, [
            'name' => $row['category'],
            'image' => $row['categoryImage'],
        ]));
        $user->assignProperties($this->arrayMapping($row, [
            'name' => $row['user'],
        ]));
        $state->assignProperties($this->arrayMapping($row, [
            'name' => $row['state'],
        ]));

        $product->setCategory($category);
        $product->setUser($user);
        $product->setState($state);

        $products[] = $product;

        return $product;
    }

    /**
     * Construye la cláusula WHERE, ORDER BY y LIMIT de la consulta SQL.
     * 
     * @param array $arrayParams Un arreglo asociativo con los parámetros de la consulta. 
     * @return array Un arreglo con la cláusula WHERE, ORDER BY y LIMIT de la consulta SQL y los parámetros a ejecutar.
     */
    private function buildQueryParams(array $arrayParams): array
    {
        $paramsQuery = [];

        if (!empty($arrayParams['WHERE'])) {
            $whereConditions = [];
            foreach ($arrayParams['WHERE'] as $key => $value) {
                $whereConditions[] = "{$key} = :{$key}";
            }
            $paramsQuery[] = "WHERE " . implode(" AND ", $whereConditions);
        }

        if (!empty($arrayParams['ORDER_BY'])) {
            $key = array_key_first($arrayParams['ORDER_BY']);
            $orderByDirection = $arrayParams['ORDER_BY'][$key];
            $paramsQuery[] = "ORDER BY {$key} " . ($orderByDirection === 'DESC' ? 'DESC' : 'ASC');
            unset($arrayParams['ORDER_BY']);
        }

        if (!empty($arrayParams['LIMIT'])) {            
            
            $paramsQuery[] = 
            "LIMIT {$this->paginator->getRecordsPerPage()} OFFSET {$this->paginator->getInitialRecord()}";
            
            unset($arrayParams['LIMIT']);
        }

        $paramsQuery = implode(' ', $paramsQuery);
        $executeParams = array_reduce($arrayParams, 'array_merge', []);

        // echo "<pre>";
        // print_r($paramsQuery);
        // echo "</pre>";
        
        // exit;

        return [
            $paramsQuery,
            $executeParams
        ];
    }

    /**
     * Obtiene todos los productos publicados.
     * 
     * @param int|null $cant (Opcional) El número máximo de productos a obtener. Si se establece en null, se obtendrán todos los productos.
     * @return Products[]
     */
    public function published(?int $cant = null): array
    {
        $arrayParams = ['WHERE' => ['fkProductState' => 2]];

        $cant ? $arrayParams['LIMIT'] = ['cant' => $cant] : null;
        
        return $this->getAll($arrayParams);

        // return $this->getAll([
        //     'WHERE' => [
        //         'fkProductState' => 2,
        //     ],
        //     // 'LIMIT' => [
        //     //     'cant' => $queryLimit
        //     // ],
        // ]);
    }

    /**
     * Obtiene los últimos productos agregados a la tabla "Products" según un límite especificado. Por defecto, el límite es todos los productos.
     * 
     * @param int|null $cant (Opcional) El número máximo de productos a obtener. Si se establece en null, se obtendrán todos los productos.
     * @return Products[] Un arreglo de objetos Product con los últimos productos agregados.
     */
    public function lastAdded(?int $cant = null): array
    {
        $arrayParams = ['ORDER_BY' => ['created_at' => 'DESC']];

        $cant ? $arrayParams['LIMIT'] = ['cant' => $cant] : null;
        
        return $this->getAll($arrayParams);
    }

    /**
     * Obtiene los productos destacados. Por defecto, el límite es todos los productos.
     * 
     * @param int|null $cant (Opcional) El número máximo de productos a obtener. Si se establece en null, se obtendrán todos los productos.
     * @return Products[] Un arreglo de objetos Product con los últimos productos agregados.
     */
    public function featured(?int $cant = null): array
    {
        $arrayParams = ['WHERE' => ['featured' => 1]];

        $cant ? $arrayParams['LIMIT'] = ['cant' => $cant] : null;
        
        return $this->getAll($arrayParams);
    }

    /**
     * Obtiene los productos de una categoría, excluyendo uno en particular si se especifica.
     *
     * @param int $idCategory El ID de la categoría de productos.
     * @param int|null $idProductToExclude (Opcional) El ID del producto a excluir.
     * @return Products[]
     */
    public function getByCategory($idCategory, $idProductToExclude = null): array
    {
        $db = DBConexion::getConexion();
        $query = "SELECT * FROM products WHERE fkCategory = :idCategory";

        if ($idProductToExclude !== null) {
            $query .= " AND idProduct != :idProductToExclude";
        }

        $stmt = $db->prepare($query);
        $stmt->bindParam(':idCategory', $idCategory);

        if ($idProductToExclude !== null) {
            $stmt->bindParam(':idProductToExclude', $idProductToExclude);
        }

        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
        $products = $stmt->fetchAll();
        return $products;
    }

    /**
     *
     * Obtiene los últimos productos agregados a la tabla "Products" según un límite especificado. Por defecto, el límite es todos los productos.
     *
     * @param int|null $limit El número máximo de productos a obtener. Si se establece en null, se obtendrán todos los productos.
     * @return Products[] Un arreglo de objetos Product con los últimos productos agregados.
     */
    // public function getLastAddedProducts(?int $limit = null): array
    // {
    //     $db = DBConexion::getConexion();
    //     $query = "SELECT * FROM products ORDER BY idProduct DESC";

    //     if ($limit !== null) {
    //         $query .= " LIMIT :limit";
    //     }

    //     $stmt = $db->prepare($query);

    //     if ($limit !== null) {
    //         $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    //     }

    //     $stmt->execute();
    //     $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
    //     $products = $stmt->fetchAll();

    //     return $products;
    // }

    /**
     * Crea un nuevo producto en la tabla "Products".
     *
     * @param array $data Un arreglo asociativo con los datos del producto a crear.
     * @return void
     * @throws PDOException Si ocurre un error al crear el producto.
     */
    public function create(array $data): void
    {
        date_default_timezone_set('America/Argentina/Buenos_Aires');

        $db = DBConexion::getConexion();
        $query = 
        "INSERT INTO products 
                (fkCategory, fkUser, fkProductState, name, description, price, stock, image, imageDescription, featured, created_at) 
        VALUES  (:fkCategory, :fkUser, :fkProductState, :name, :description, :price, :stock, :image, :imageDescription, :featured, :created_at)";
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':fkCategory'           => $data['category'],
            ':fkUser'               => $data['user'],
            ':fkProductState'       => $data['productState'],
            ':name'                 => $data['name'],
            ':description'          => $data['description'],
            ':price'                => $data['price'],
            ':stock'                => $data['stock'],
            ':image'                => $data['image'],
            ':imageDescription'     => $data['imageDescription'],
            ':featured'             => $data['featured'],
            ':created_at'           => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Elimina un producto de la tabla "Products".
     * 
     * @return void
     * @throws PDOException Si ocurre un error al eliminar el producto.
     */
    public function delete(): void
    {
        $db = DBConexion::getConexion();
        $query = "DELETE FROM products WHERE idProduct = :idProduct";
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':idProduct' => $this->idProduct
        ]);
    }

    /**
     * Edita un producto de la tabla "Products".
     *
     * @param int|null $idProduct El ID del producto a editar.
     * @param array $data Un arreglo asociativo con los datos del producto a editar.
     * @return void
     * @throws PDOException Si ocurre un error al editar el producto.
     */
    public function edit(?int $idProduct, array $data): void
    {
        date_default_timezone_set('America/Argentina/Buenos_Aires');

        $db = DBConexion::getConexion();
        $query = "UPDATE products 
                SET fkCategory          = :fkCategory, 
                    fkUser              = :fkUser,
                    fkProductState      = :fkProductState,
                    name                = :name, 
                    description         = :description, 
                    price               = :price, 
                    stock               = :stock, 
                    image               = :image, 
                    imageDescription    = :imageDescription, 
                    featured            = :featured, 
                    created_at          = :created_at
                WHERE idProduct = :idProduct";
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':idProduct'            => $idProduct,
            ':fkCategory'           => $data['category'],
            ':fkUser'               => $data['user'],
            ':fkProductState'       => $data['productState'],
            ':name'                 => $data['name'],
            ':description'          => $data['description'],
            ':price'                => $data['price'],
            ':stock'                => $data['stock'],
            ':image'                => $data['image'],
            ':imageDescription'     => $data['imageDescription'],
            ':featured'             => $data['featured'],
            ':created_at'           => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Calcula el subtotal del producto. 
     * 
     * @return float|null
     */
    public function getSubtotal(): ?float
    {
        return $this->price * $this->cant ?? null;
    }
















    // Getters y Setters

    /**
     * Get the value of idProduct
     */
    public function getIdProduct()
    {
        return $this->idProduct;
    }

    /**
     * Set the value of idProduct
     *
     * @return  self
     */
    public function setIdProduct($idProduct)
    {
        $this->idProduct = $idProduct;

        return $this;
    }

    /**
     * Get the value of fkCategory
     */
    public function getFkCategory()
    {
        return $this->fkCategory;
    }

    /**
     * Set the value of fkCategory
     *
     * @return  self
     */
    public function setFkCategory($fkCategory)
    {
        $this->fkCategory = $fkCategory;

        return $this;
    }

    /**
     * Get the value of fkUser
     */
    public function getFkUser()
    {
        return $this->fkUser;
    }

    /**
     * Set the value of fkUser
     *
     * @return  self
     */
    public function setFkUser($fkUser)
    {
        $this->fkUser = $fkUser;

        return $this;
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @return  self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of price
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set the value of price
     *
     * @return  self
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get the value of stock
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Set the value of stock
     *
     * @return  self
     */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get the value of image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set the value of image
     *
     * @return  self
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get the value of imageDescription
     */
    public function getImageDescription()
    {
        return $this->imageDescription;
    }

    /**
     * Set the value of imageDescription
     *
     * @return  self
     */
    public function setImageDescription($imageDescription)
    {
        $this->imageDescription = $imageDescription;

        return $this;
    }

    /**
     * Get the value of featured
     */
    public function getFeatured()
    {
        return $this->featured;
    }

    /**
     * Set the value of featured
     *
     * @return  self
     */
    public function setFeatured($featured)
    {
        $this->featured = $featured;

        return $this;
    }


    /**
     * Get the value of fkProductState
     */
    public function getFkProductState()
    {
        return $this->fkProductState;
    }

    /**
     * Set the value of fkProductState
     *
     * @return  self
     */
    public function setFkProductState($fkProductState)
    {
        $this->fkProductState = $fkProductState;

        return $this;
    }

    /**
     * Get the value of state
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set the value of state
     *
     * @return  self
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get the value of category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set the value of category
     *
     * @return  self
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get the value of user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the value of user
     *
     * @return  self
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get the value of user
     */
    public function getPaginator()
    {
        return $this->paginator;
    }

    /**
     * Set the value of user
     *
     * @return  self
     */
    public function setPaginator($paginator)
    {
        $this->paginator = $paginator;

        return $this;
    }

    /**
     * Get the value of cant
     */ 
    public function getCant()
    {
        return $this->cant;
    }

    /**
     * Set the value of cant
     *
     * @return  self
     */ 
    public function setCant($cant)
    {
        $this->cant = $cant;

        return $this;
    }
}
