<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_studentlife extends CI_Model {


    function getDetailCompanyByID($ID){

        $data = $this->db->query('SELECT * FROM db_studentlife.master_company mc WHERE mc.ID = "'.$ID.'" ')->result_array();

        return $data;

    }


    public function fetchWorkExperience($data){
		$this->db->select("a.*, b.Name as CompanyName, b.Brand as CompanyBrand, c.ID as CompanyIndustryID, c.name as CompanyIndustryName, b.Industry as CompanyIndustryOth, b.Phone as CompanyPhone, b.Website as CompanyWebsite, b.Facebook as CompanyFacebook, b.Instagram as CompanyInstagram, b.Address as CompanyAddress, b.ProvinceID as CompanyProvinceID, e.ProvinceName as CompanyProvinceName, b.RegionID as CompanyRegionID, f.RegionName as CompanyRegionName, b.DistrictID as CompanyDistrictID, g.DistrictName as CompanyDistrictName, b.CountryID as CompanyCountryID, d.ctr_name as CompanyCountryName, b.Postcode as CompanyPostcode, (case when (a.JobType = 1) then 'Worker' when (a.JobType = 2) then 'Entrepreneur' else 'Unknown' end) as JobTypeName, h.Description as JobLevelName, i.Description as PositionLevelName, (MONTHNAME(STR_TO_DATE(a.StartMonth, '%m'))) as StartMonthName, (MONTHNAME(STR_TO_DATE(a.EndMonth, '%m'))) as EndMonthName, (case when (a.WorkSuitability = '0') then 'Low' when (a.WorkSuitability = '1') then 'Medium' when (a.WorkSuitability = '2')  then 'High' else 'Unknown' end) as WorkSuitabilityName, (case when (a.Status = '0') then 'Resign' when (a.Status = '1') then 'Current Position' else 'Unknown' end ) as StatusEmployee");
		$this->db->from("db_studentlife.alumni_experience a");
		$this->db->join("db_studentlife.master_company b","a.CompanyID = b.ID","left");
		$this->db->join("db_employees.master_industry_type c","c.ID = b.IndustryTypeID","left");
		$this->db->join("db_admission.country d","d.ctr_code = b.CountryID","left");
		$this->db->join("db_admission.province e","e.ProvinceID = b.ProvinceID","left");
		$this->db->join("db_admission.region f","f.RegionID = b.RegionID","left");
		$this->db->join("db_admission.district g","g.DistrictID = b.DistrictID","left");
		$this->db->join("db_studentlife.job_level h","h.ID = a.JobLevelID","left");
		$this->db->join("db_studentlife.position_level i","i.ID = a.PositionLevelID","left");
		$this->db->where($data);
		$query = $this->db->get();
		return $query;
	}
	

	public function fetchCompany($data=array()){
		$this->db->select("b.*, b.Name as CompanyName, b.Brand as CompanyBrand, c.ID as CompanyIndustryID, c.name as CompanyIndustryName, b.Industry as CompanyIndustryOth, b.Phone as CompanyPhone, b.Website as CompanyWebsite, b.Facebook as CompanyFacebook, b.Instagram as CompanyInstagram, b.Address as CompanyAddress, b.ProvinceID as CompanyProvinceID, e.ProvinceName as CompanyProvinceName, b.RegionID as CompanyRegionID, f.RegionName as CompanyRegionName, b.DistrictID as CompanyDistrictID, g.DistrictName as CompanyDistrictName, b.CountryID as CompanyCountryID, d.ctr_name as CompanyCountryName, b.Postcode as CompanyPostcode");
		$this->db->from("db_studentlife.master_company b");
		$this->db->join("db_employees.master_industry_type c","c.ID = b.IndustryTypeID","left");
		$this->db->join("db_admission.country d","d.ctr_code = b.CountryID","left");
		$this->db->join("db_admission.province e","e.ProvinceID = b.ProvinceID","left");
		$this->db->join("db_admission.region f","f.RegionID = b.RegionID","left");
		$this->db->join("db_admission.district g","g.DistrictID = b.DistrictID","left");
		$this->db->where($data);
		$this->db->order_by("ID","desc");
		$query = $this->db->get();
		return $query;
	}

}
