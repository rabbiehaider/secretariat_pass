<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Billing_model extends CI_Model
{

	public function __construct()
	{
		$this->BRANCHid = $this->session->userdata('BRANCHid');
	}

	// Get all details ehich store in "products" table in database.
	public function get_all()
	{
		$query = $this->db->get('products');
		return $query->result_array();
	}

	// ==========================Sales Return==========================================
	public function SalesReturn($table, $data)
	{
		$this->db->insert($table, $data);
		$id = $this->db->insert_id();
		return (isset($id)) ? $id : FALSE;
	}

	public function selectProduct($pCategory, $brand, $BRANCHid)
	{
		$this->db->SELECT("tbl_product.*, tbl_category.*, tbl_color.*,tbl_brand.* FROM tbl_product LEFT JOIN tbl_category on tbl_category.Category_SlNo= tbl_product.ProductCategory_ID LEFT JOIN tbl_color ON tbl_color.color_SiNo=tbl_product.color LEFT JOIN tbl_brand on tbl_brand.brand_SiNo=tbl_product.Brand_ID where tbl_product.Brand_ID='$brand' AND tbl_product.ProductCategory_ID='$pCategory' AND tbl_product.branch_id='$BRANCHid' order by tbl_product.Product_Code desc");
		$query = $this->db->get();
		$result = $query->result();
		return $result;
	}

	public function select_by_id($table, $id, $field)
	{
		$this->db->select('*');
		$this->db->from($table);
		$this->db->where($field, $id);
		$query = $this->db->get();
		$result = $query->row();
		return $result;
	}

	public function company_branch_profile($id)
	{
		$company = $this->db->query("select * from tbl_company order by Company_SlNo desc limit 1")->row();
		$branch = $this->db->query("select * from tbl_outlet where branch_id = ?", $id)->row();

		return (object)[
			'Currency_Name'     => $company->Currency_Name,
			'SubCurrency_Name'  => $company->SubCurrency_Name,
			'InvoiceHeder'      => $company->InvoiceHeder,
			'dueStatus'         => $company->dueStatus,
			'Company_Logo_thum' => $company->Company_Logo_thum,
			'Company_Logo_org'  => $company->Company_Logo_org,
			'Company_Name'      => $branch->Branch_title,
			'Branch_phone'      => $branch->Branch_phone,
			'Repot_Heading'     => $branch->Branch_address,
			'InvoiceNote'       => $company->InvoiceNote,
			'print_type'        => $company->print_type
		];
	}

	public function getCurrentBranch()
	{
		$branchInfo = $this->db->query("select * from tbl_outlet where branch_id = ?", $this->session->userdata('BRANCHid'))->row();
		return $branchInfo;
	}

	public function cash_transaction($startdate, $enddate)
	{
		$res = $this->db->where('branch_id', $this->BRANCHid)
			->where('Tr_date BETWEEN "' . date('Y-m-d', strtotime($startdate)) . '" and "' . date('Y-m-d', strtotime($enddate)) . '"')
			->get('tbl_cash_transactions')->result();

		return $res;
	}
}
