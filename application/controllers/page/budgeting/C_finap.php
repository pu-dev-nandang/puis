<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_finap extends Budgeting_Controler {
    public $Msg = array(
            'Duplicate' => 'The data duplicate, Please check',
            'NotAction' => 'The data has been used for transaction, Cannot be action',
            'Error' => 'Error connection',
    );

    public function __construct()   
    {
        parent::__construct();
    }

    public function menu_horizontal($page)
    {
    	$data['content'] = $page;
    	$content = $this->load->view('global/budgeting/finap/menu_horizontal',$data,true);
    	$this->temp($content);
    }

    public function index()
    {
		$page = $this->load->view('global/budgeting/finap/list',$this->data,true);
		$this->menu_horizontal($page);
    }

    public function create_ap()
    {
        $page = $this->load->view('global/budgeting/finap/create_ap',$this->data,true);
        $this->menu_horizontal($page);
    }

    public function list_server_side()
    {
         //check action
        $fieldaction = ', pay.ID_payment,pay.Status as StatusPay,pay.Departement as DepartementPay,pay.JsonStatus as JsonStatus3,pay.Code as CodeSPB,pay.CreatedBy as PayCreatedBy,e_spb.Name as PayNameCreatedBy,if(pay.Status = 0,"Draft",if(pay.Status = 1,"Issued & Approval Process",if(pay.Status =  2,"Approval Done",if(pay.Status = -1,"Reject","Cancel") ) )) as StatusNamepay,t_spb_de.NameDepartement as NameDepartementPay,pay.Perihal,pay.Type as TypePay,pay.CreatedAt as PayCreateAt,pay.StatusPayFin,pay.CreateBYPayFin,e_PayFin.Name as PayFinNameCreatedBy,pay.ID_payment_fin,pay.RealisasiTotal,pay.RealisasiStatus ';
        $joinaction = ' right join (
                                 select a.ID as ID_payment_,a.Type,a.Code,a.Code_po_create,a.Departement,a.UploadIOM,a.NoIOM,a.JsonStatus,a.Notes,a.Status,a.Print_Approve,a.CreatedBy,a.CreatedAt,a.LastUpdatedBy,a.LastUpdatedAt,b.*,c.Status as StatusPayFin 
                                 ,c.CreatedBy as CreateBYPayFin,c.ID as ID_payment_fin
                                 from db_payment.payment as a join
                                 ( select ID_payment,Perihal,1 as RealisasiTotal,2 as RealisasiStatus  from db_payment.spb
                                   UNION 
                                   select a.ID_payment,a.Perihal,(select count(*) as total from db_payment.bank_advance_realisasi where ID_bank_advance = a.ID  ) as RealisasiTotal,b.Status as RealisasiStatus from db_payment.bank_advance as a
                                   left join db_payment.bank_advance_realisasi as b on a.ID = b.ID_bank_advance
                                   UNION 
                                   select a.ID_payment,a.Perihal,(select count(*) as total from db_payment.cash_advance_realisasi where ID_cash_advance = a.ID  ) as RealisasiTotal,b.Status as RealisasiStatus from db_payment.cash_advance  as a
                                   left join db_payment.cash_advance_realisasi as b on a.ID = b.ID_cash_advance
                                   UNION 
                                   select a.ID_payment,a.Perihal,(select count(*) as total from db_payment.petty_cash_realisasi where ID_petty_cash = a.ID  ) as RealisasiTotal,b.Status as RealisasiStatus  from db_payment.petty_cash 
                                   as a
                                   left join db_payment.petty_cash_realisasi as b on a.ID = b.ID_petty_cash
                                 )
                 as b on a.ID = b.ID_payment
                 join db_budgeting.ap as c on a.ID = c.ID_payment
                  )
                         as pay on pay.Code_po_create = a.Code
                        left join db_employees.employees as e_PayFin on e_PayFin.NIP = pay.CreateBYPayFin
                        left join db_employees.employees as e_spb on e_spb.NIP = pay.CreatedBy
                        join (
                        select * from (
                        select CONCAT("AC.",ID) as ID, NameEng as NameDepartement from db_academic.program_study where Status = 1
                        UNION
                        select CONCAT("NA.",ID) as ID, Division as NameDepartement from db_employees.division where StatusDiv = 1
                        UNION
                        select CONCAT("FT.",ID) as ID, NameEng as NameDepartement from db_academic.faculty where StBudgeting = 1
                        ) aa
                        ) as t_spb_de on pay.Departement = t_spb_de.ID
                     ';
        $whereaction = ' and StatusPay = 2';

         // get Department
         $WhereFiltering = '';
          
         $requestData = $_REQUEST;
         $StatusQuery = ' and Status = 2';
         $sqltotalData = 'select count(*) as total  from (
                     select if(a.TypeCreate = 1,"PO","SPK") as TypeCode,a.Code,a.ID_pre_po_supplier,b.CodeSupplier,
                         c.NamaSupplier,c.PICName as PICSupplier,c.Alamat as AlamatSupplier,
                         a.JsonStatus,
                         if(a.Status = 0,"Draft",if(a.Status = 1,"Issued & Approval Process",if(a.Status =  2,"Approval Done",if(a.Status = -1,"Reject","Cancel") ) )) as StatusName,a.CreatedBy,d.Name as NameCreateBy,a.CreatedAt,a.PostingDate,g.PRCode,h.JsonStatus as JsonStatus2,h.Departement,a.Status'.$fieldaction.'
                     from db_purchasing.po_create as a
                     left join db_purchasing.pre_po_supplier as b on a.ID_pre_po_supplier = b.ID
                     left join db_purchasing.m_supplier as c on b.CodeSupplier = c.CodeSupplier
                     left join db_employees.employees as d on a.CreatedBy = d.NIP
                     left join db_purchasing.po_detail as e on a.Code = e.Code
                     left join db_purchasing.pre_po_detail as f on e.ID_pre_po_detail = f.ID
                     left join db_budgeting.pr_detail as g on f.ID_pr_detail = g.ID
                     left join db_budgeting.pr_create as h on h.PRCode = g.PRCode
                     '.$joinaction.'
                 )aa
                ';

         $sqltotalData.= ' where (Code LIKE "%'.$requestData['search']['value'].'%" or TypeCode LIKE "'.$requestData['search']['value'].'%" or NamaSupplier LIKE "%'.$requestData['search']['value'].'%" or CodeSupplier LIKE "'.$requestData['search']['value'].'%"
               or PayNameCreatedBy LIKE "'.$requestData['search']['value'].'%" or PayCreatedBy LIKE "'.$requestData['search']['value'].'%" 
               or PRCode LIKE "'.$requestData['search']['value'].'%"  or CodeSPB LIKE "'.$requestData['search']['value'].'%"
               or TypePay LIKE "'.$requestData['search']['value'].'%" or NameDepartementPay LIKE "'.$requestData['search']['value'].'%"
               or Perihal LIKE "'.$requestData['search']['value'].'%"
             ) '.$StatusQuery.$WhereFiltering.$whereaction ;
        
         $querytotalData = $this->db->query($sqltotalData)->result_array();
         $totalData = $querytotalData[0]['total'];

         $sql = 'select * from (
                     select a.ID as ID_po_create,if(a.TypeCreate = 1,"PO","SPK") as TypeCode,a.Code,a.ID_pre_po_supplier,b.CodeSupplier,
                         c.NamaSupplier,c.PICName as PICSupplier,c.Alamat as AlamatSupplier,
                         a.JsonStatus,
                         if(a.Status = 0,"Draft",if(a.Status = 1,"Issued & Approval Process",if(a.Status =  2,"Approval Done",if(a.Status = -1,"Reject","Cancel") ) )) as StatusName,a.CreatedBy,d.Name as NameCreateBy,a.CreatedAt,a.PostingDate,g.PRCode,h.JsonStatus as JsonStatus2,h.Departement,a.Status'.$fieldaction.'
                     from db_purchasing.po_create as a
                     left join db_purchasing.pre_po_supplier as b on a.ID_pre_po_supplier = b.ID
                     left join db_purchasing.m_supplier as c on b.CodeSupplier = c.CodeSupplier
                     left join db_employees.employees as d on a.CreatedBy = d.NIP
                     left join db_purchasing.po_detail as e on a.Code = e.Code
                     left join db_purchasing.pre_po_detail as f on e.ID_pre_po_detail = f.ID
                     left join db_budgeting.pr_detail as g on f.ID_pr_detail = g.ID
                     left join db_budgeting.pr_create as h on h.PRCode = g.PRCode
                     '.$joinaction.'
                 )aa
                ';

         $sql.= ' where (Code LIKE "%'.$requestData['search']['value'].'%" or TypeCode LIKE "'.$requestData['search']['value'].'%" or NamaSupplier LIKE "%'.$requestData['search']['value'].'%" or CodeSupplier LIKE "'.$requestData['search']['value'].'%"
               or PayNameCreatedBy LIKE "'.$requestData['search']['value'].'%" or PayCreatedBy LIKE "'.$requestData['search']['value'].'%" 
               or PRCode LIKE "'.$requestData['search']['value'].'%" or CodeSPB LIKE "'.$requestData['search']['value'].'%" 
               or TypePay LIKE "'.$requestData['search']['value'].'%" or NameDepartementPay LIKE "'.$requestData['search']['value'].'%"
               or Perihal LIKE "'.$requestData['search']['value'].'%"
             ) '.$StatusQuery.$WhereFiltering.$whereaction ;
         $sql.= ' ORDER BY ID_payment_fin Desc LIMIT '.$requestData['start'].' , '.$requestData['length'].' ';
         // print_r($sql);die();
         $query = $this->db->query($sql)->result_array();

         $No = $requestData['start'] + 1;
         
         $data = array();
         for($i=0;$i<count($query);$i++){
             $nestedData=array();
             $row = $query[$i];
             $nestedData[] = $No;
             $nestedData[] = $row['Code'];
             $nestedData[] = $row['NameDepartementPay'];
             // $nestedData[] = $row['CodeSupplier'].' || '.$row['NamaSupplier'];
             $nestedData[] = $row['StatusNamepay'];
             $nestedData[] = '';
             $nestedData[] = $row['PayFinNameCreatedBy'];
             // find PR in po_detail
                 $arr_temp = array();
                 $sql_get_pr = 'select a.ID,a.ID_m_catalog,b.Item,c.ID as ID_pre_po_detail,d.Code,a.PRCode
                 from db_budgeting.pr_detail as a join db_purchasing.m_catalog as b on a.ID_m_catalog = b.ID
                 left join db_purchasing.pre_po_detail as c on a.ID = c.ID_pr_detail
                 left join db_purchasing.po_detail as d on c.ID = d.ID_pre_po_detail
                 where d.Code = ?
                 ';
                 $query_get_pr=$this->db->query($sql_get_pr, array($row['Code']))->result_array();
                 for ($j=0; $j < count($query_get_pr); $j++) { 
                     if (count($arr_temp) == 0) {
                         $arr_temp[] = $query_get_pr[$j]['PRCode'];
                     }
                     else
                     {
                         // check exist
                         $bool = true;
                         for ($k=0; $k < count($arr_temp); $k++) { 
                             if ($arr_temp[$k]==$query_get_pr[$j]['PRCode']) {
                                 $bool = false;    
                                 break;
                             }
                         }

                         if ($bool) {
                             $arr_temp[] = $query_get_pr[$j]['PRCode'];
                         }

                     }
                 }
                 // pass data spb
                 $arr_temp[] = array(
                     'CodeSPB' => $row['CodeSPB'],
                     'StatusPay' => $row['StatusPay'],
                     'TypePay' => $row['TypePay'],
                     'ID_payment' => $row['ID_payment'],
                     'Perihal' => $row['Perihal'],
                     'StatusPayFin' => $row['StatusPayFin'],
                     'ID_payment_fin' => $row['ID_payment_fin'],
                     'RealisasiTotal' => $row['RealisasiTotal'],
                     'RealisasiStatus' => $row['RealisasiStatus'],
                 );

             $nestedData[] = $arr_temp;
             $data[] = $nestedData;
             $No++;
         }

         $json_data = array(
             "draw"            => intval( $requestData['draw'] ),
             "recordsTotal"    => intval($totalData),
             "recordsFiltered" => intval($totalData ),
             "data"            => $data,
         );
         echo json_encode($json_data);
    }

    public function global_view_finap($token)
    {
        try {
            $key = "UAP)(*";
            $token = $this->jwt->decode($token,$key);
            $ID_payment_fin = $token;
            $G_payment_ap = $this->m_master->caribasedprimary('db_budgeting.ap','ID',$ID_payment_fin);
            if (count($G_payment_ap) > 0) {
                $ID_payment = $G_payment_ap[0]['ID_payment'];
                // check by po atau tidak
                $G_payment = $this->m_master->caribasedprimary('db_payment.payment','ID',$ID_payment);
                $Code_po_create = $G_payment[0]['Code_po_create'];
                $TypePay = $G_payment[0]['Type'];
                $CodeSPB =  $G_payment[0]['Code'];
                $G_po = $this->m_pr_po->Get_data_po_by_Code($Code_po_create);
                $PRCode = $G_po['po_detail'][0]['PRCode'];
                $data = array(
                    'ID_payment_fin' => $ID_payment_fin,
                    'ID_payment' => $ID_payment,
                    'Code_po_create' => $Code_po_create,
                    'TypePay' => $TypePay,
                    'CodeSPB' => $CodeSPB,
                    'PRCode' => $PRCode,
                    'po_data' => $G_po,
                );

                $content = $this->load->view('global/budgeting/finap/InfoFinap',$data,true);
                $this->temp($content);
            }
            

        } 
        catch (Exception $e) {
            show_404($log_error = TRUE); 
        }
    }

}
