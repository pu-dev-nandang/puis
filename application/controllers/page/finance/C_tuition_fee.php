<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_tuition_fee extends Finnance_Controler {

    private $data = array();

    function __construct()
    {
        parent::__construct();
        $this->data['department'] = parent::__getDepartement();
        $this->load->model('finance/m_finance');
        $this->load->model('m_sendemail');
        $this->load->model('master/m_master');
    }


    public function temp($content)
    {
        parent::template($content);
    }


    public function index()
    {
        $data['department'] = parent::__getDepartement();
        $content = "test";
        $this->temp($content);
    }

    public function tuition_fee(){
        if ($this->input->is_ajax_request()) {

            // action bintang CRUD
            $dataToken =  $this->getInputToken();
            $action = $dataToken['action'];
            switch ($action) {
                case 'read':
                    $dataQuery = $this->m_master->showData_array('db_finance.tuition_fee_schema');
                    echo json_encode($dataQuery);
                    break;
                case 'add' :
                    $rs = ['status' => 0,'msg' => ''];
                    $dataForm = $dataToken['dataForm'];
                    $ID_bintang = $dataForm->ID_bintang;
                    $search = $this->m_master->caribasedprimary('db_finance.tuition_fee_schema','ID_bintang',$ID_bintang);
                    if (count($search) > 0) {
                        $rs['msg'] = 'Duplicate Data';
                    }
                    else
                    {
                        $this->db->insert('db_finance.tuition_fee_schema',$dataForm);
                        $rs['status'] = 1;
                    }

                    echo json_encode($rs);
                    break;
                case 'edit' :
                    $rs = ['status' => 0,'msg' => ''];
                    $dataForm = $dataToken['dataForm'];
                    $ID_bintang = $dataForm->ID_bintang;
                    $idData = $dataToken['idData'];
                    $boolCheck = true;
                    if ($idData != $ID_bintang) {
                        $search = $this->db->query(
                            'select * from db_finance.tuition_fee_schema 
                             where ID_bintang != '.$ID_bintang.'   
                            ')->result_array();
                        if (count($search) > 0) {
                            $rs['msg'] = 'Duplicate Data';
                            $boolCheck =  false;
                        }

                        if ($boolCheck) {
                            $queryFind = $this->db->query(
                                '
                                select 1 from db_academic.auth_students where Pay_Cond = '.$idData.'   
                                order by ID desc limit 1
                                '
                            )->result_array();
                            if (count($queryFind) > 0) {
                               $rs['msg'] = 'The data have been used for transaction, cannot edit <b>Bintang</b> ';
                               $boolCheck =  false;
                            }
                        }
                    }
                    
                    if ($boolCheck) {
                        $this->db->where('ID_bintang',$idData);
                        $this->db->update('db_finance.tuition_fee_schema',$dataForm);
                        $rs['status'] = 1;
                    }
                    echo json_encode($rs);   
                    break;
                case 'delete' : 
                    $rs = ['status' => 0,'msg' => ''];
                    $dataForm = $dataToken['dataForm'];
                    $idData = $dataToken['idData'];
                    $boolCheck = true;

                    $queryFind = $this->db->query(
                        '
                        select 1 from db_academic.auth_students where Pay_Cond = '.$idData.'   
                        order by ID desc limit 1
                        '
                    )->result_array();

                    if (count($queryFind) > 0) {
                       $rs['msg'] = 'The data have been used for transaction, cannot delete ';
                       $boolCheck =  false;
                    }

                    if ($boolCheck) {
                        $this->db->where('ID_bintang',$idData);
                        $this->db->delete('db_finance.tuition_fee_schema');
                        $rs['status'] = 1;
                    }
                    echo json_encode($rs);
                    break; 
                default:
                    # code...
                    break;
            }

        }
        else
        {
            $content = $this->load->view('page/'.$this->data['department'].'/master/tuition_fee',$this->data,true);
            $this->temp($content);
        }
       
    }

    public function modal_tagihan_mhs()
    {
        $input = $this->getInputToken();
        $this->data['action'] = $input['Action'];
        $this->data['id'] = $input['CDID'];
        $this->data['selectCurriculum'] = $input['selectCurriculum'];
        $this->data['selectBintang'] = $this->m_master->showData_array('db_finance.tuition_fee_schema');
        echo $this->load->view('page/'.$this->data['department'].'/master/modalform_tuition_fee',$this->data,true);
    }

    public function modal_tagihan_mhs_submit()
    {
        $input = $this->getInputToken();
        // check data master tagihan exist
        if ($this->m_finance->checkMasterTagihanExisting($input['TypePembayaran'],$input['Prodi'],$input['ClassOf'],$input['Pay_Cond'])) {
             $this->m_finance->inserData_master_tagihan_mhs($input['TypePembayaran'],$input['Prodi'],$input['Cost'],$input['ClassOf'],$input['Pay_Cond']);
             echo json_encode('');
        }
        else
        {
            echo json_encode('Data telah ada pada database');
        }
       
    }

    public function edited_tagihan_mhs_submit()
    {
        $input = $this->getInputToken();
        $this->m_finance->updateTagihanMhsList($input);
    }

    public function deleted_tagihan_mhs_submit()
    {
        $input = $this->getInputToken();
        $this->m_finance->deleteTagihanMHSByProdiYear($input);
    }

    public function SwitchBintang($tokenURL){
        if ($this->input->is_ajax_request()) {
            $dataToken = $this->getInputToken();
            $action = $dataToken['action'];
            switch ($action) {
                case 'LoadBintangOption':
                    $rs = [];
                    $dataParam = $dataToken['data'];
                    $NPM = $dataParam->NPM;
                    $ProdiID = $dataParam->ProdiID;
                    $Classof = $dataParam->Classof;
                    $rs['auth_std'] = $this->m_master->caribasedprimary('db_academic.auth_students','NPM',$NPM)[0];
                    $rs['OptionBintang'] = $this->db->query(
                        'select distinct(Pay_Cond) from db_finance.tuition_fee 
                         where ProdiID = '.$ProdiID.' and Classof = '.$Classof.' 
                        '
                    )->result_array();
                    echo json_encode($rs);
                    break;
                case 'LoadloG' :
                    $dataParam = $dataToken['data'];
                    $NPM = $dataParam->NPM;
                    // $query = $this->m_master->caribasedprimary('db_finance.m_tuition_fee_updated','NPM',$NPM);
                    $query = $this->db->query(
                            'select a.*,b.Name AS NameEMP from db_finance.m_tuition_fee_updated as a
                             join db_employees.employees as b on a.UpdateBy = b.NIP
                             where a.NPM  = "'.$NPM.'"

                            '  
                    )->result_array();
                    $rs = [];
                    for ($i=0; $i < count($query); $i++) { 
                        $nestedData=array();
                        $row = $query[$i];
                        foreach ($row as $key => $value) {
                            $nestedData[] = $value;
                        }
                        $nestedData['token'] = $this->jwt->encode($row,'UAP)(*');
                        $rs[] = $nestedData;
                    }

                    $json_data = array(
                        "draw"            => intval(count($query)),
                        "recordsTotal"    => intval(count($query)),
                        "recordsFiltered" => intval(count($query)),
                        "data"            => $rs,
                    );
                    echo json_encode($json_data);
                    break;
                case 'LoadTuitionFeeMhs' :
                    $dataParam = $dataToken['data'];
                    $NPM = $dataParam->NPM;
                    $dataQuery = $this->db->query(
                        'select a.Semester,b.Abbreviation as PaymentType,b.ID as PTID, a.Invoice from db_finance.m_tuition_fee  as a 
                         join db_finance.payment_type as b on a.PTID = b.ID
                         where a.NPM = "'.$NPM.'" order by Semester asc,PTID asc
                        '
                    )->result_array();
                   
                    $rs = [
                        'data' => $dataQuery,
                        'draw' => count($dataQuery),
                        'recordsFiltered' => 0,
                        'recordsTotal' => count($dataQuery),
                    ];
                    echo json_encode($rs);   
                    break;
                case 'LoadTuitionFeeAfter' :
                    $rs = ['status' => 0,'msg' => '','callback' => []]; 
                    $dataParam = $dataToken['data'];
                    $NPM = $dataParam->NPM;
                    $Classof = $dataParam->Classof;
                    $ProdiID = $dataParam->ProdiID;
                    $ProdiName = $dataParam->ProdiName;
                    $Pay_Cond = $dataParam->Pay_Cond;

                    // check Pay_CondNow
                    $Pay_CondNow = $this->m_master->caribasedprimary('db_academic.auth_students','NPM',$NPM)[0]['Pay_Cond'];
                    if ($Pay_Cond == $Pay_CondNow) {
                        $rs['msg'] = 'Tidak ada perubahan data';
                    }
                    else
                    {
                        $dataPTID = $this->m_master->caribasedprimary('db_finance.payment_type','Type','0');
                        $temp = [];
                        $BoolCheckingAllPaymentType =  true;
                        for ($i=0; $i < count($dataPTID); $i++) { 
                            $PTID = $dataPTID[$i]['ID']; // 4 PTID
                           $sql1 = 'select * from db_finance.tuition_fee where PTID = ? and ProdiID = ? and ClassOf = ? and Pay_Cond = ? ';
                           $query1=$this->db->query($sql1, array($PTID,$ProdiID,$Classof,$Pay_Cond))->result_array();
                           if (count($query1) == 0) {
                               $BoolCheckingAllPaymentType = false;
                               break;
                           }
                           else
                           {
                                for ($k=1; $k <= 14; $k++) {
                                        $st = $k;
                                        switch ($PTID) {
                                            case 1:
                                            case 4:
                                                if ($k == 1) {
                                                    $Invoice = $query1[0]['Cost'];
                                                    $st = 15;
                                                }
                                                break;
                                            case 2:
                                            case 3:
                                                $Invoice = $query1[0]['Cost'];
                                                break;
                                            default:
                                                $Invoice = 0;
                                                break;
                                        }
                                       $Semester = $k;
                                       $temp[] = [
                                        'Semester' => $Semester,
                                        'PaymentType' => $dataPTID[$i]['Abbreviation'],
                                        'PTID' => $dataPTID[$i]['ID'],
                                        'Invoice' => $Invoice 
                                       ];
                                       $k = $st;
                                }
                           }
                        }

                        if (!$BoolCheckingAllPaymentType) {
                            $rs['msg'] = 'Data tuition fee tahun '.$Classof.' Prodi '.$ProdiName.' tidak lengkap, mohon check master tagihan mahasiswa';
                        }
                        else{
                           $rs['callback'] = $temp;
                           $rs['status'] = 1;
                        }
                    }

                    $dataRS = [
                        'data' => $rs['callback'],
                        'draw' => count($rs['callback']),
                        'recordsFiltered' => 0,
                        'recordsTotal' => count($rs['callback']),
                        'result' => $rs
                    ];
                    echo json_encode($dataRS);
                    break;
                case 'tuitionFeeUpdate' :
                    $dataParam = $dataToken['data'];
                    $NPM = $dataParam->NPM;
                    $Pay_CondTo = $dataParam->Pay_Cond;
                    $Pay_CondFrom = $this->m_master->caribasedprimary('db_academic.auth_students','NPM',$NPM)[0]['Pay_Cond'];
                    $dataExist = $dataParam->dataExist;
                    $dataChange = $dataParam->dataChange;
                    $dataSaveUpdate = [
                        'NPM' => $NPM,
                        'BintangFrom' => $Pay_CondFrom ,
                        'BintangTo' => $Pay_CondTo,
                        'JsonDataChanged' => json_encode([
                                                'before' => $dataExist,
                                                'after' => $dataChange
                                            ]) ,
                        'UpdateAt' => date('Y-m-d H:i:s') ,
                        'UpdateBy' => $this->session->userdata('NIP'),
                    ];

                    for ($i=0; $i < count($dataChange); $i++) { 
                        $PTID = $dataChange[$i]->PTID;
                        $Semester = $dataChange[$i]->Semester;
                        $Invoice = $dataChange[$i]->Invoice;
                        $this->db->where('PTID',$PTID);
                        $this->db->where('Semester',$Semester);
                        $this->db->where('NPM',$NPM);
                        $this->db->update('db_finance.m_tuition_fee',['Invoice' => $Invoice]);
                    }

                    $this->db->insert('db_finance.m_tuition_fee_updated',$dataSaveUpdate);

                    // update auth student
                    $this->db->where('NPM',$NPM);
                    $this->db->update('db_academic.auth_students',['Pay_Cond' => $Pay_CondTo]);
                    echo json_encode(1);
                    break;
                default:
                    # code...
                    break;
            }
        }
        else
        {
            try {
                $this->data['tokenURL'] = $tokenURL;
                $dataDecodeToken = $this->jwt->decode($tokenURL,'UAP)(*');
                $this->data['NPM'] = $dataDecodeToken->NPM;
                $this->data['Name'] = $dataDecodeToken->Namemhs;
                $this->data['Prodiname'] = $dataDecodeToken->Prodiname;
                $content = $this->load->view('page/'.$this->data['department'].'/master/SwitchBintang',$this->data,true);
                $this->temp($content);
            } catch (Exception $e) {
                print_r('No Token auth');
            }

        }
       
    }

}
