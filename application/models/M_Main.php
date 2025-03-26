<?php
// --
class M_Main extends Model
{
    // --
    public function __construct()
    {
        parent::__construct();
    }

    // --
    public function get_document_types()
    {
        // --
        try {
            // --
            $sql = 'SELECT id, description, status FROM document_type';
            // --
            $result = $this->pdo->fetchAll($sql);
            // --
            if ($result) {
                // --
                $response = array('status' => 'OK', 'result' => $result);
            } else {
                // --
                $response = array('status' => 'ERROR', 'result' => array());
            }
        } catch (PDOException $e) {
            // --
            $response = array('status' => 'EXCEPTION', 'result' => $e);
        }
        // --
        return $response;
    }


    public function get_voucher_type()
    {
        /** Función Modificada */
        try {
            $sql = 'SELECT id, description, status FROM voucher_type ORDER BY id DESC';
            $result = $this->pdo->fetchAll($sql);

            if ($result) {
                $response = array('status' => 'OK', 'result' => $result);
            } else {
                $response = array('status' => 'ERROR', 'result' => array());
            }
        } catch (PDOException $e) {
            $response = array('status' => 'EXCEPTION', 'result' => $e);
        }

        return $response;
    }

    // --
    public function get_transfer_type()
    {
        /** Función Modificada */
        try {
            $sql = 'SELECT 
                        id, 
                        description, 
                        status 
                    FROM transfer_type;';
            $result = $this->pdo->fetchAll($sql);

            if ($result) {
                $response = array('status' => 'OK', 'result' => $result);
            } else {
                $response = array('status' => 'ERROR', 'result' => array());
            }
        } catch (PDOException $e) {
            $response = array('status' => 'EXCEPTION', 'result' => $e);
        }

        return $response;
    }

    //--
    public function get_payment_type()
    {
        // --
        try {
            // --
            $sql = 'SELECT 
                        id, 
                        description, 
                        status 
                    FROM payment_type;';
            // --
            $result = $this->pdo->fetchAll($sql);
            // --
            if ($result) {
                // --
                $response = array('status' => 'OK', 'result' => $result);
            } else {
                // --
                $response = array('status' => 'ERROR', 'result' => array());
            }
        } catch (PDOException $e) {
            // --
            $response = array('status' => 'EXCEPTION', 'result' => $e);
        }
        // --
        return $response;
    }

    public function get_payment_method()
    {
        // --
        try {
            // --
            $sql = 'SELECT 
                        id, 
                        description, 
                        status 
                    FROM payment_shape;';
            // --
            $result = $this->pdo->fetchAll($sql);
            // --
            if ($result) {
                // --
                $response = array('status' => 'OK', 'result' => $result);
            } else {
                // --
                $response = array('status' => 'ERROR', 'result' => array());
            }
        } catch (PDOException $e) {
            // --
            $response = array('status' => 'EXCEPTION', 'result' => $e);
        }
        // --
        return $response;
    }
    // --
    public function get_campus()
    {
        // Resto del código permanece igual
        try {
            $sql = 'SELECT 
                        id, 
                        description, 
                        status 
                    FROM payment_type;';
            $result = $this->pdo->fetchAll($sql);
            if ($result) {
                $response = array('status' => 'OK', 'result' => $result);
            } else {
                $response = array('status' => 'ERROR', 'result' => array());
            }
        } catch (PDOException $e) {
            $response = array('status' => 'EXCEPTION', 'result' => $e);
        }
        return $response;
    }
    // --    

    //--
    public function get_coins()
    {
        // --
        try {
            // --
            $sql = 'SELECT 
                        id,
                        code, 
                        description, 
                        status 
                    FROM coin
            ';
            // --
            $result = $this->pdo->fetchAll($sql);
            // --
            if ($result) {
                // --
                $response = array('status' => 'OK', 'result' => $result);
            } else {
                // --
                $response = array('status' => 'ERROR', 'result' => array());
            }
        } catch (PDOException $e) {
            // --
            $response = array('status' => 'EXCEPTION', 'result' => $e);
        }
        // --
        return $response;
    }

    //--
    public function get_igv()
    {
        // --
        try {
            // --
            $sql = 'SELECT 
                        id, 
                        value, 
                        status 
                    FROM igv
            ';
            // --
            $result = $this->pdo->fetchAll($sql);
            // --
            if ($result) {
                // --
                $response = array('status' => 'OK', 'result' => $result);
            } else {
                // --
                $response = array('status' => 'ERROR', 'result' => array());
            }
        } catch (PDOException $e) {
            // --
            $response = array('status' => 'EXCEPTION', 'result' => $e);
        }
        // --
        return $response;
    }

    public function get_role()
    {
        // --
        try {
            // --
            $sql = 'SELECT 
                        id, 
                        description,
                        status
                    FROM roleperson
            ';
            // --
            $result = $this->pdo->fetchAll($sql);
            // --
            if ($result) {
                // --
                $response = array('status' => 'OK', 'result' => $result);
            } else {
                // --
                $response = array('status' => 'ERROR', 'result' => array());
            }
        } catch (PDOException $e) {
            // --
            $response = array('status' => 'EXCEPTION', 'result' => $e);
        }
        // --
        return $response;
    }
}
