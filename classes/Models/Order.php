<?php

namespace App\Models;

use App\Database\DBConexion;
use PDO;

class Order extends BaseModel
{
    protected int $idOrder;
    protected int $fkUser;
    protected int $fkOrderState;
    protected string $orderDate;
    protected string $shippingDate;
    protected string $shippingAddress;
    protected string $paymentMethod;
    protected float $total;

    /** @var array|Products[] */
    protected array $products = [];

    protected string $table = "orders";
    protected string $primaryKey = "idOrder";

    protected array $properties = [
        "idOrder",
        "fkUser",
        "fkOrderState",
        "orderDate",
        "shippingDate",
        "shippingAddress",
        "paymentMethod",
        "total"
    ];

    /**
     * Carga todos los productos de la orden
     * 
     * @return void
     */
    public function chargeProducts()
    {
        $db = DBConexion::getConexion();
        // $query = 
        //     "SELECT 
        //         p.*, c.idCategory, c.name AS 'category', c.description, c.image AS 'categoryImage', c.imageDescription, u.idUser, u.email, u.password, u.name AS 'user', u.surname, ps.idProductState, ps.name AS 'state', pho.cant 
        //     FROM products p  
        //     INNER JOIN categories c             ON p.fkCategory     = c.idCategory  
        //     INNER JOIN product_states ps        ON p.fkProductState = ps.idProductState  
        //     INNER JOIN users u                  ON p.fkUser         = u.idUser 
        //     INNER JOIN products_has_orders pho  ON p.idProduct      = pho.fkProduct 
        //     WHERE pho.fkOrder = ?;";
        $query = 
        "SELECT 		
                    p.*, c.idCategory, 	c.name AS 'category', 		u.name AS 'user', 	ps.name AS 'state', pho.cant,
                                        c.description, 				u.idUser,			ps.idProductState,
                                        c.image AS 'categoryImage', u.email,
                                        c.imageDescription, 		u.password,
                                                                    u.surname
        FROM 		products_has_orders pho
        INNER JOIN	products p 			ON p.idProduct 			= pho.fkProduct
        INNER JOIN 	categories c 		ON c.idCategory 		= p.fkCategory
        INNER JOIN 	product_states ps   ON ps.idProductState 	= p.fkProductState  
        INNER JOIN 	users u             ON u.idUser 			= p.fkUser         	
        WHERE 		pho.fkOrder = ?;";

        $stmt = $db->prepare($query);
        $stmt->execute([$this->getIdOrder()]);

        $products = [];

        while ($obj = $stmt->fetch()) {

            $product = new Products;
            $category = new Categories;
            $user = new User;
            $state = new ProductStates;

            $product->assignProperties($obj);
            $category->assignProperties($this->arrayMapping($obj, [
                'name' => $obj['category'],
                'image' => $obj['categoryImage'],
            ]));
            $user->assignProperties($this->arrayMapping($obj, [
                'name' => $obj['user'],
            ]));
            $state->assignProperties($this->arrayMapping($obj, [
                'name' => $obj['state'],
            ]));

            $product->setCategory($category);
            $product->setUser($user);
            $product->setState($state);
            $product->setCant($obj['cant']);

            $products[] = $product;
        }

        $this->setProducts($products);
    }





    // Getters y Setters

    /**
     * Get the value of idOrder
     */
    public function getIdOrder()
    {
        return $this->idOrder;
    }

    /**
     * Set the value of idOrder
     *
     * @return  self
     */
    public function setIdOrder($idOrder)
    {
        $this->idOrder = $idOrder;

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
     * Get the value of fkOrderState
     */
    public function getFkOrderState()
    {
        return $this->fkOrderState;
    }

    /**
     * Set the value of fkOrderState
     *
     * @return  self
     */
    public function setFkOrderState($fkOrderState)
    {
        $this->fkOrderState = $fkOrderState;

        return $this;
    }

    /**
     * Get the value of orderDate
     */
    public function getOrderDate()
    {
        return $this->orderDate;
    }

    /**
     * Set the value of orderDate
     *
     * @return  self
     */
    public function setOrderDate($orderDate)
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    /**
     * Get the value of shippingDate
     */
    public function getShippingDate()
    {
        return $this->shippingDate;
    }

    /**
     * Set the value of shippingDate
     *
     * @return  self
     */
    public function setShippingDate($shippingDate)
    {
        $this->shippingDate = $shippingDate;

        return $this;
    }

    /**
     * Get the value of shippingAddress
     */
    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }

    /**
     * Set the value of shippingAddress
     *
     * @return  self
     */
    public function setShippingAddress($shippingAddress)
    {
        $this->shippingAddress = $shippingAddress;

        return $this;
    }

    /**
     * Get the value of paymentMethod
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * Set the value of paymentMethod
     *
     * @return  self
     */
    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    /**
     * Get the value of total
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set the value of total
     *
     * @return  self
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get the value of products
     */ 
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Set the value of products
     *
     * @return  self
     */ 
    public function setProducts($products)
    {
        $this->products = $products;

        return $this;
    }
}
