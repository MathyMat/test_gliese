<?php 
// --
class M_Proforma extends Model {
    // --
    public function __construct() {
		parent::__construct();
    }

    // --
    public function get_proforma() {
      // --
      try {
          // --
          $sql = 'SELECT 
                  p.id AS id_proforma,
                  c.business_name AS clients,
                  u.user AS user,
                  vt.description AS voucher_type,
                  p.igv,
                  p.date_issue,
                  p.correlative,
                  p.reference,
                  p.total_sale,
                  p.delivery_time,
                  p.offer_validity,
                  p.status
              FROM proforma p
              INNER JOIN voucher_type vt ON vt.id = p.id_voucher_type
              INNER JOIN clients c ON c.id = p.id_clients
              INNER JOIN user u ON u.id = p.id_user';
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
  public function get_proforma_by_id($bind) {
      // --
      try {
          // --
          $sql = 'SELECT 
                  p.id AS id_proforma,
                  c.business_name AS clients,
                  u.user AS user,
                  vt.description AS voucher_type,
                  p.igv,
                  p.date_issue,
                  p.correlative,
                  p.reference,
                  p.total_sale,
                  p.delivery_time,
                  p.offer_validity,
                  p.status
              FROM proforma p
              INNER JOIN voucher_type vt ON vt.id = p.id_voucher_type
              INNER JOIN clients c ON c.id = p.id_client
              INNER JOIN user u ON u.id = p.id_user
              WHERE c.id = :id_proforma';
          // --
          $result = $this->pdo->fetchOne($sql, $bind);
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
  public function create_proforma($bind) {
      // --
      try {
          // --
          $sql = 'INSERT INTO proforma
          (
              id_document_type,
              name,
              document_number,
              address,
              phone,
              business_name,
              email
          ) 
          VALUES 
          (
              :id_document_type,
              :name,
              :document_number,
              :address,
              :phone,
              :business_name,
              :email   
          )';
          // --
          $result = $this->pdo->perform($sql, $bind);
          // --
          if ($result) {
              // --
              $response = array('status' => 'OK', 'result' => array());
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
  public function update_proforma($bind) {
      // --
      try {
          // --
          $sql = 'UPDATE proforma 
              SET
                  id_document_type = :id_document_type,
                  name = :name,
                  document_number = :document_number,
                  address = :address,
                  phone = :phone,
                  email = :email,
                  business_name = :business_name
              WHERE id = :id_proforma';
          // --
          $result = $this->pdo->perform($sql, $bind);
          // --
          if ($result) {
              // --
              $response = array('status' => 'OK', 'result' => array());
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
  public function delete_proforma($bind) {
      // --
      try {
          // --
          $sql = 'DELETE FROM proforma 
          where id = :id_proforma';
          // --
          $result = $this->pdo->perform($sql, $bind);
          // --
          if ($result) {
              // --
              $response = array('status' => 'OK', 'result' => array());
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