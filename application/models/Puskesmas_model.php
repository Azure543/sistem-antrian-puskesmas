<?php

class Puskesmas_model extends CI_Model
{
	public function get_data($table, $id = null)
	{
		if ($id) {
			return $this->db->get_where($table, ['id' => $id])->result();
		} else {
			return $this->db->get($table)->result();
		}
	}

	public function insert_data($data, $table)
	{
		$this->db->insert($table, $data);
	}

	public function update_data($table, $data, $where)
	{
		$this->db->where('id', $where)->update($table, $data);
	}

	public function delete_data($id, $table)
	{
		return $this->db->where('id', $id)->delete($table);
	}

	public function check_data($table, $where = null)
	{
		return $this->db->get_where($table, $where);
	}
}
