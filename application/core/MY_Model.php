<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model {

	/**
	 * @name: table name
	 */
	public $table = '';

	/**
	 * @name: primary key
	 */
	public $key = 'id';

	/**
	 * @name: default order
	 * @usage: $order = array('id', 'desc))
	 */
	public $order = '';

	/**
	 * @name: default select field on get_list
	 * @usage: $select = 'id, name'
	 */
	public $select = '';

	/**
	 * @method: create new row
	 * @param: $data - data to insert
	 */
	public function create($data = array()) {
		if($this->db->insert($this->table, $data))
		{
		   return TRUE;
		}else{
			return FALSE;
		}
	}

	/**
	 * @method: update row by id
	 * @param: $id - primary key of row
	 * @param: $data - data array to change
	 */
	public function update($id, $data)	{
		if (!$id)
		{
			return FALSE;
		}

		$where = array();
	 	$where[$this->key] = $id;
	    $this->update_rule($where, $data);

	 	return TRUE;
	}

	/**
	 * @method: delete row by id
	 * @param: $id - primary key of row
	 */
	public function delete($id) {
		if (!$id)
		{
			return FALSE;
		}
		// if number
		if(is_numeric($id))
		{
			$where = array($this->key => $id);
		}else
		{
		    // delete multi-row
		    // $id = 1,2,3...
			$where = $this->key . " IN (".$id.") ";
		}
	 	$this->del_rule($where);

		return TRUE;
	}

	/**
	 * @method: get row info by id
	 * @param: $id - id of info
	 * @param: $field - seleted column
	 */
	public function get_info($id, $field = '')
	{
		if (!$id)
		{
			return FALSE;
		}

	 	$where = array();
	 	$where[$this->key] = $id;

	 	return $this->get_info_rule($where, $field);
	}

	/**
	 * @method: count all rows
	 */
	public function get_total($input = array())
	{
		$this->get_list_set_input($input);

		$query = $this->db->get($this->table);

		return $query->num_rows();
	}

	/**
	 * @method: get list
	 * @param: $input - array data to get
	 */
	public function get_list($input = array())
	{
	    // set input property
		$this->get_list_set_input($input);

		// execute query
		$query = $this->db->get($this->table);
		// echo $this->db->last_query();
		return $query->result();
	}

	/**
	 * @method: set input properties on get list
	 * @param: $input - array data to get
	 */

	protected function get_list_set_input($input = array())
	{
		// add condition to query via $input['where']
		// e.g., $input['where'] = array('email' => 'example@domain.com')
		if ((isset($input['where'])) && $input['where'])
		{
			$this->db->where($input['where']);
		}
	}

	/**
	 * @method: validate data by condition
	 * @param: $where - condition input
	 */
  public function check_exists($where = array())
    {
	    $this->db->where($where);
	    // execute query
		$query = $this->db->get($this->table);

		if($query->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}
}
?>
