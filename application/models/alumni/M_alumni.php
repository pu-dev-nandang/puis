<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_alumni extends CI_Model {
    private $callback = ['status' => 0,'msg' => '','callback' => array() ];
    function __construct()
    {
        parent::__construct();
        $this->load->model('master/m_master');
        $this->load->model('m_rest_global');
        $this->load->library('JWT');
    }

    public function registration($action,$data){
        $tbl = 'db_alumni.registration';
        switch ($action) {
            case 'delete':
                $this->db->db_debug=false;
                $this->db->where('NPM',$data['NPM']);
                $query = $this->db->delete($tbl);
                if( !$query )
                {
                   $this->callback['msg'] = json_encode($this->db->error());
                }
                else
                {
                 $this->callback['status'] = 1; 
                }
                break;
            case 'create':
                $this->db->db_debug=false;
                $dataSave = [
                    'NPM' => $data['NPM'],
                    'UpdateAt' => date('Y-m-d H:i:s'),
                    'UpdateBy' => $data['UpdateBy'],
                ];
                $query = $this->db->insert($tbl,$dataSave);
                if( !$query )
                {
                   $this->callback['msg'] = json_encode($this->db->error());
                }
                else
                {
                 $this->callback['status'] = 1; 
                }
                break;
            default:
                # code...
                break;
        }

        $this->db->db_debug=true;
        return $this->callback;
    }

    public function authLogin($data){
        $NPM = $data['NPM'];
        $Password = $data['Password'];
        $dataMHS = $this->db->query('select ID,NPM,Year from db_academic.auth_students where NPM = "'.$NPM.'" and Password = "'.$Password.'" ')->result_array();
        $dataAlumni = $this->db->query('select count(*) as total from db_alumni.registration where NPM = "'.$NPM.'"  ')->result_array();
        if (count($dataMHS) > 0 && $dataAlumni[0]['total'] > 0 ) {
             $this->callback = $this->setUserSession($dataMHS[0]);
        }

        return $this->callback;
    }

    private function setUserSession($dataAuth){

        $table = 'ta_'.$dataAuth['Year'].'.students';
        $dataUser = $this->getDataUser($dataAuth['Year'],$table,$dataAuth['NPM']);

        $newdata = array(
            'alumni_NPM'  => $dataUser[0]['NPM'],
            'alumni_Name'  => $dataUser[0]['Name'],
            'alumni_Gender'  => $dataUser[0]['Gender'],
            'alumni_HP' => $dataUser[0]['HP'],
            'alumni_Email' => $dataUser[0]['Email'],
            'alumni_DateOfBirth' => $dataUser[0]['DateOfBirth'],
            'alumni_PlaceOfBirth' => $dataUser[0]['PlaceOfBirth'],
            'alumni_EmailPU'  => $dataUser[0]['EmailPU'],
            'alumni_Faculty'  => $dataUser[0]['Faculty'],
            'alumni_ProdiID'  => $dataUser[0]['ProdiID'],
            'alumni_ProdiGroupID'  => $dataUser[0]['ProdiGroupID'],
            'alumni_ProdiGroup'  => $dataUser[0]['ProdiGroup'],
            'alumni_ProdiCode'  => $dataUser[0]['ProdiCode'],
            'alumni_CurriculumID'  => $dataUser[0]['CurriculumID'],
            'alumni_ProgramCampusID'  => $dataUser[0]['ProgramID'],
            'alumni_SemesterID'  => $dataUser[0]['SemesterID'],
            'alumni_Semester'  => $dataUser[0]['SemesterNow'],
            'alumni_Year'  => $dataUser[0]['Year'],
            'alumni_SemesterCode'  => $dataUser[0]['SemesterCode'],
            'alumni_Photo'  => $dataUser[0]['Photo'],
            'alumni_StatusStudentID'  => $dataUser[0]['StatusStudentID'],
            'alumni_ClassOf'  => $dataAuth['Year'],
            'alumni_AcademicMentor'  => $dataUser[0]['AcademicMentor'],
            'alumni_MentorName'  => $dataUser[0]['MentorName'],
            'alumni_MentorEmailPU'  => $dataUser[0]['MentorEmailPU'],
            'alumni_Token'  => $dataUser[0]['Token'],
            'alumni_DB'  => 'ta_'.$dataAuth['Year'],
            'alumni_loggedIn' => TRUE
        );

        return $newdata;
    }

    private function getDataUser($year,$table,$NPM) {
        $data = $this->db->query('SELECT s.*, ast.Password AS Token, ast.EmailPU, ast.ProdiGroupID, pg.Code AS ProdiGroup, ma.NIP AS AcademicMentor, 
                                    e.Name AS MentorName, e.EmailPU AS MentorEmailPU, 
                                    ps.Code AS ProdiCode, fc.Name AS Faculty FROM 
                                    '.$table.' s
                                    LEFT JOIN db_academic.auth_students ast ON (s.NPM = ast.NPM)
                                    LEFT JOIN db_academic.prodi_group pg ON (pg.ID = ast.ProdiGroupID)
                                    LEFT JOIN db_academic.mentor_academic ma ON (ma.NPM = s.NPM AND ma.Status="1")
                                    LEFT JOIN db_employees.employees e ON (e.NIP = ma.NIP)
                                    LEFT JOIN db_academic.program_study ps ON (ps.ID = s.ProdiID)
                                    LEFT JOIN db_academic.faculty fc ON (fc.ID = ps.FacultyID)
                                    WHERE s.NPM = "'.$NPM.'"')->result_array();

        $dataSemester = $this->db->query('SELECT ID FROM db_academic.curriculum 
                                              WHERE Year = "'.$year.'" LIMIT 1')->result_array()[0];

        $dataTotalSmt = $this->db->query('SELECT s.* FROM db_academic.semester s 
                                                    WHERE s.ID >= (SELECT ID FROM db_academic.semester s2 
                                                    WHERE s2.Year="'.$year.'" 
                                                    LIMIT 1)')->result_array();

        $smt = 0;
        $Year='';
        $SemesterCode='';
        $SemesterID = 0;
        $bool = true;
        for($s=0;$s<count($dataTotalSmt);$s++){
            
                $smt += 1;
                $Year = $dataTotalSmt[$s]['Name'];
                $SemesterCode=$dataTotalSmt[$s]['Code'];
                $SemesterID = $dataTotalSmt[$s]['ID'];

            if($dataTotalSmt[$s]['Status']=='1'){
                $bool = false;
                
               
                break;
            } 
        }

        if ($bool) {
            $s = 0;
            $smt = 1;
            $Year = $dataTotalSmt[$s]['Name'];
            $SemesterCode=$dataTotalSmt[$s]['Code'];
            $SemesterID = $dataTotalSmt[$s]['ID'];
        }

        $data[0]['CurriculumID'] = $dataSemester['ID'];
        $data[0]['SemesterNow'] = $smt;
        $data[0]['SemesterID'] = $SemesterID;
        $data[0]['Year'] = $Year;
        $data[0]['SemesterCode'] = $SemesterCode;

        return $data;
    }

    public function change_photo($dataToken){
        if (array_key_exists('Photo', $_FILES)) {
            $data = $dataToken['data'];
            $NPM = $data['NPM'];
            $filename = $_FILES['Photo']['name'][0];
            $varFiles = 'Photo';
            $path = './uploads/document/'.$NPM;
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
                copy("./uploads/index.html",$path.'/index.html');
                copy("./uploads/index.php",$path.'/index.php');
            }

            if (file_exists($path.'/'.$filename)) {
                unlink($path.'/'.$filename);
            }
            $proc = $this->m_master->uploadDokumenSetFileName($filename,$varFiles,$path);
            if (!empty($proc)) {

                $filenameDB = $proc[0];
                $G_dt = $this->m_master->caribasedprimary('db_alumni.biodata','NPM',$NPM);
                if (count($G_dt) > 0) {
                    $this->db->db_debug=false;
                    $this->db->where('NPM',$NPM);
                    $query = $this->db->update('db_alumni.biodata',[
                        'Photo' => $filenameDB,
                        ]+$data
                    );

                    if( !$query )
                    {
                       $this->callback['msg'] = json_encode($this->db->error());
                    }
                    else
                    {
                     $this->callback['status'] = 1; 
                    }
                }
                else
                {
                    $query = $this->db->insert('db_alumni.biodata',[
                        'Photo' => $filenameDB,
                        ]+$data
                    );

                    if( !$query )
                    {
                       $this->callback['msg'] = json_encode($this->db->error());
                    }
                    else
                    {
                     $this->callback['status'] = 1; 
                    }

                }
            }
            
        }
        $this->db->db_debug=true;
        return $this->callback;
    }

    public function save_biodata($dataToken){
        $tbl = 'db_alumni.biodata';
        $action = $dataToken['action'];
        switch ($action) {
            case 'add':
                $data = $dataToken['data'];
                $this->db->db_debug=false;
                $query = $this->db->insert($tbl,$data);
                if( !$query )
                {
                   $this->callback['msg'] = json_encode($this->db->error());
                }
                else
                {
                 $this->callback['status'] = 1; 
                }
                break;
            case 'edit':
                $data = $dataToken['data'];
                $NPM = $dataToken['NPM'];
                $this->db->db_debug=false;
                $this->db->where('NPM',$NPM);
                $query = $this->db->update($tbl,$data);
                if( !$query )
                {
                   $this->callback['msg'] = json_encode($this->db->error());
                }
                else
                {
                 $this->callback['status'] = 1; 
                }
                break;
            default:
                # code...
                break;
        }

        $this->db->db_debug=true;
        return $this->callback;
    }

    public function upd_tbl_ta($dataToken){
        $tbl = $dataToken['tbl'];
        $data = $dataToken['data'];
        $NPM = $dataToken['NPM'];
        $this->db->db_debug=false;
        $this->db->where('NPM',$NPM);
        $query = $this->db->update($tbl,$data);
        if( !$query )
        {
           $this->callback['msg'] = json_encode($this->db->error());
        }
        else
        {
         $this->callback['status'] = 1; 
        }

        $this->db->db_debug=true;
        return $this->callback;
        
    }

    public function load_data_education($dataToken){
        $data = $dataToken['data'];
        $NPM = $data['NPM'];
        $sql = 'select a.ID,a.NPM,a.ID_university,un.Name_University,jud.GraduationDate as Jud_GraduationDate,a.edu_code,ed.edu_name,a.ctr_code,ctr.ctr_name,
            a.ID_major_programstudy_employees,mjr.Name_MajorProgramstudy, a.IPK, a.Description,auts.GraduationDate as GraduationDate
            from db_alumni.education as a
            join db_research.university as un on a.ID_university = un.ID
            join db_academic.judiciums_list as judlst on judlst.NPM = a.NPM
            join db_academic.judiciums as jud on jud.ID = judlst.JID
            join db_admission.education as ed on ed.edu_code = a.edu_code
            join db_admission.country as ctr on ctr.ctr_code = a.ctr_code
            join db_employees.major_programstudy_employees as mjr on mjr.ID = a.ID_major_programstudy_employees
            join db_academic.auth_students as auts on auts.NPM = a.NPM
            where a.NPM = "'.$NPM.'"
            group by a.ID
            order by ed.edu_sort asc
            ';
        $query = $this->db->query(
            $sql
        )->result_array();

        if (count($query) == 0) {
            // insert lulusan PU
                // get IPK
                $dataMHS = $this->m_rest_global->api_Biodata_MHS($NPM);


            $dataSave = [
                'NPM' => $NPM,
                'ID_university' => 52, // podomoro unversity
                'Date_graduation' => $this->__Date_graduation($NPM),
                'edu_code' =>  $this->__education_lulusan($NPM),
                'ctr_code' => '001', // indonesia
                'ID_major_programstudy_employees' => $this->__major_programstudy_employees($NPM),
                'IPK' => number_format($dataMHS[0]->IPK_data['dataIPK']['IPK'], 2),
            ];

            // print_r($dataSave);die();

            $this->db->insert('db_alumni.education',$dataSave);

            $query = $this->db->query(
                $sql
            )->result_array();
        }

        $this->callback['status'] = 1; 
        $this->callback['callback'] = $query;
        return  $this->callback;
    }

    private function __major_programstudy_employees($NPM){
        $query = $this->db->query(
            'select mps.ID from db_academic.auth_students as auts
             join db_academic.program_study as pst on auts.ProdiID = pst.ID
             join db_employees.major_programstudy_employees as mps on mps.LinkIDProgramStudy = pst.ID
             where auts.NPM = "'.$NPM.'"
             '
        )->result_array();

        return $query[0]['ID'];
    }

    private function __education_lulusan($NPM){
        $query = $this->db->query(
            'select ed.edu_code from db_academic.auth_students as auts
             join db_academic.program_study as pst on auts.ProdiID = pst.ID
             join db_academic.education_level as edlev on edlev.ID = pst.EducationLevelID
             join db_admission.education as ed on ed.edu_name = edlev.Name
             where auts.NPM = "'.$NPM.'"
            '
        )->result_array();

        return $query[0]['edu_code'];
    }

    private function __Date_graduation($NPM){
        $query = $this->db->query(
            'select b.GraduationDate from db_academic.judiciums_list as a 
             join db_academic.judiciums as b on a.JID = b.ID
             where a.NPM = "'.$NPM.'"   
             '
        )->result_array();

        return $query[0]['GraduationDate'];
    }

    public function submit_education($dataToken){
        $tbl = 'db_alumni.education';
        $action = $dataToken['action'];
        switch ($action) {
            case 'add':
                $data = $dataToken['data'];
                $this->db->db_debug=false;
                $query = $this->db->insert($tbl,$data);
                if( !$query )
                {
                   $this->callback['msg'] = json_encode($this->db->error());
                }
                else
                {
                 $this->callback['status'] = 1; 
                }
                $this->db->db_debug=true;
                break;
            case 'edit':
                $data = $dataToken['data'];
                $ID = $dataToken['ID'];
                $this->db->db_debug=false;
                $this->db->where('ID',$ID);
                $query = $this->db->update($tbl,$data);
                if( !$query )
                {
                   $this->callback['msg'] = json_encode($this->db->error());
                }
                else
                {
                 $this->callback['status'] = 1; 
                }
                $this->db->db_debug=true;
                break;
            case 'delete':
                $ID = $dataToken['ID'];
                $this->db->db_debug=false;
                $this->db->where('ID',$ID);
                $query = $this->db->delete($tbl);
                if( !$query )
                {
                   $this->callback['msg'] = json_encode($this->db->error());
                }
                else
                {
                 $this->callback['status'] = 1; 
                }
                $this->db->db_debug=true;
                break;
            default:
                # code...
                break;
        }

        return $this->callback;
    }

    public function load_data_skills($dataToken){
        $data = $dataToken['data'];
        $NPM = $data['NPM'];
        $rs = [];
        $Level = ['Tingkat Lanjut','Menengah','Pemula'];
        for ($i=0; $i < count($Level); $i++) { 
            $sql = 'select * from db_alumni.skill where NPM = "'.$NPM.'" and Level = "'.$Level[$i].'"
                        ';
                    $query = $this->db->query(
                        $sql
                    )->result_array();
            $rs[] = [
                'level' => $Level[$i],
                'data' => $query,
            ];
        }

        $this->callback['status'] = 1; 
        $this->callback['callback'] = $rs;
        return  $this->callback;
    }

    public function submit_skills($dataToken){
        $data = $dataToken['data'];
        $tbl = 'db_alumni.skill';
        if (count($data) > 0) {
            // delete first
            $NPM = $data[0]['NPM'];
            $this->db->where('NPM',$NPM);
            $this->db->delete($tbl);

            // insert batch
            $this->db->insert_batch($tbl, $data);

            $this->callback['status'] = 1; 
        }

        return $this->callback;
    }

    public function load_data_forum_server_side($dataToken){
        $requestData = $dataToken['data']['REQUEST'];
        $NPM = $dataToken['data']['NPM']; // NPM or NIP

        $AddwherePost = [];
        $where='';

        $AddwherePost[] = array('field'=>'(`'.'a`.`CreateBy'.'`','data'=>' = "'.$NPM.'"' ,'filter' =>' AND ');  
        $AddwherePost[] = array('field'=>'`'.'b`.`UserID'.'`','data'=>' = "'.$NPM.'")' ,'filter' =>' or ');  

        if(!empty($requestData['search']['value']) ) {
            $search = $requestData['search']['value'];
            $AddwherePost[] = array('field'=>'(`'.'a`.`Topic'.'`','data'=>' like "'.$search.'%")' ,'filter' =>' AND ');     
        }

        $sql_select = 'select a.* ';
        $sql_from  = ' from db_alumni.forum as a
                       join db_alumni.forum_user as b on a.ForumID = b.ForumID

        ';

        $sqlCon = ' group by a.ForumID';

        if(!empty($AddwherePost)){
          $where = ' WHERE ';
          $counter = 0;
          foreach ($AddwherePost as $key => $value) {
              if($counter==0){
                  $where = $where.$value['field']." ".$value['data'];
              }
              else{
                  $where = $where.$value['filter']." ".$value['field']." ".$value['data'];
              }
              $counter++;
          }
        }

        $Totaldata = $this->db->query('select count(*) as total from (
                                              select 1 '.$sql_from.$where.$sqlCon.'

                                        ) temp

                 ')->row()->total;

        $queryData = $this->db->query($sql_select.$sql_from.$where.$sqlCon.' LIMIT '.$requestData['start'].' , '.$requestData['length'].' ')->result_array();

        $No = (int)$requestData['start'] + 1;
        $data = array();

        for ($i=0; $i < count($queryData); $i++) { 
          $row = $queryData[$i];
          $nestedData = array();
          $nestedData[] = $No;
          // get Name by TypeUserID
          if ($row['TypeUserID'] == 1) {
              $G_dt = $this->m_master->caribasedprimary('db_academic.auth_students','NPM',$row['CreateBy']);
          }
          else
          {
            $G_dt = $this->m_master->caribasedprimary('db_employees.employees','NIP',$row['CreateBy']);
          }
          $nestedData[] = $G_dt[0]['Name'];
          $nestedData[] = $row['Topic'];
          $nestedData[] = $row['CreateAt'];

          //  get user
          $G_user = $this->db->query('
                            select a.*,b.Name,b.DivisionName from db_alumni.forum_user as a
                            join  (
                                    select NPM as UserID,Name, "Student" as DivisionName from db_academic.auth_students
                                    UNION
                                    select emp.NIP as UserID,emp.Name,divi.Division as DivisionName
                                    from db_employees.employees as emp
                                    join db_employees.division as divi on SPLIT_STR(emp.PositionMain, ".", 1) = divi.ID
                                ) as b on b.UserID = a.UserID
                                where a.ForumID = '.$row['ForumID'].'
                            ')->result_array();
          $row['G_user'] = $G_user;

          // get Comment
          $G_comment =  $this->db->query('
                            select a.*,b.Name,b.DivisionName from db_alumni.forum_comment as a
                            join  (
                                    select a.NPM as UserID,a.Name, "Student" as DivisionName from db_academic.auth_students as a
                                    join (
                                       '.$this->queryGetMHS().'
                                    ) ta_std on ta_std.NPM = a.NPM
                                    UNION
                                    select emp.NIP as UserID,emp.Name,divi.Division as DivisionName
                                    from db_employees.employees as emp
                                    join db_employees.division as divi on SPLIT_STR(emp.PositionMain, ".", 1) = divi.ID
                                ) as b on b.UserID = a.UserID
                                where a.ForumID = '.$row['ForumID'].'
                        ')->result_array();
          $row['G_comment'] = $G_comment;
          $nestedData[] = $row['ForumID'];
          $tokenRow = $this->jwt->encode($row,"UAP)(*");
          $nestedData['data'] = $tokenRow;
          $nestedData['tokenURL'] = $this->jwt->encode((int) $row['ForumID'],"UAP)(*");
          $data[] = $nestedData;
          $No++;
        }

        $this->callback['status'] = 1; 
        $this->callback['callback'] = array(
            "draw"            => intval( $requestData['draw'] ),
            "recordsTotal"    => intval($Totaldata ),
            "recordsFiltered" => intval( $Totaldata ),
            "data"            => $data,
        );

        return $this->callback;

    }

    public function send_notif($data){
        $url = url_pas.'rest2/__send_notif_browser';
        $token = $this->jwt->encode($data,"UAP)(*");
        $this->m_master->apiservertoserver($url,$token);
    }

    private function __UserEMP_NPM($UserID) // UserID = NIP or NPM
    {
        $sql =' select * from 
                (
                    select NPM as UserID,Name, "Student" as DivisionName from db_academic.auth_students
                    UNION
                    select emp.NIP as UserID,emp.Name,divi.Division as DivisionName
                    from db_employees.employees as emp
                    join db_employees.division as divi on SPLIT_STR(emp.PositionMain, ".", 1) = divi.ID
                ) xx where UserID = "'.$UserID.'"    
                ';
        return $this->db->query($sql)->result_array();
    }

    public function submit_forum_alumni($dataToken){
        $tbl1 = 'db_alumni.forum';
        $tbl2 = 'db_alumni.forum_comment';
        $tbl3 = 'db_alumni.forum_user';
        $action = $dataToken['action'];
        switch ($action) {
            case 'add':
                $data_forum = $dataToken['data']['forum'];
                $this->db->insert($tbl1,$data_forum);
                $ForumID = $this->db->insert_id();

                $data_forum_user =  $dataToken['data']['forum_user'];
                for ($i=0; $i < count($data_forum_user); $i++) { 
                   $dataSave = [
                    'ForumID' => $ForumID,
                    'UserID' => $data_forum_user[$i],
                   ];

                   $this->db->insert($tbl3,$dataSave);
                }

                $tokenURL = $this->jwt->encode($ForumID,"UAP)(*");
                // send notif
                $UserIDCreateBy = $data_forum['CreateBy'];
                $GetDataCreateBy = $this->__UserEMP_NPM($UserIDCreateBy);
                $URLDirect = 'student-life/alumni/forum/detail/'.$tokenURL;
                $URLDirectAlumni = 'forum/detail-topic/'.$tokenURL;

                $dataNotif = [
                    'auth' => 's3Cr3T-G4N',
                    'Logging' => array(
                                    'Title' => '<i class="fa fa-check-circle margin-right" style="color:green;"></i> Forum alumni was created by '.$GetDataCreateBy[0]['Name'],
                                    'Description' => 'Title : '.$data_forum['Topic'],
                                    'URLDirect' => $URLDirect,
                                    'URLDirectAlumni' => $URLDirectAlumni,
                                    'CreatedBy' => $UserIDCreateBy,
                                    'CreatedName' => $GetDataCreateBy[0]['Name'],
                                  ),
                    'To' => array(
                              'NIP' => $data_forum_user,
                            ),
                    'Email' => 'No'
                ];

                $this->send_notif($dataNotif);

                $this->callback['status'] = 1; 
                return $this->callback;

                break;
            
            default:
                # code...
                break;
        }
    }

    private function queryGetMHS(){
       $arr = $this->m_master->ShowDBLikes();
       $str = 'select NPM,Name,Photo from '.$arr[0].'.students';
       for ($i=1; $i < count($arr); $i++) { 
           $str .= ' 
                    UNION
                    select NPM,Name,Photo from '.$arr[$i].'.students    
                ';
       }

       return $str;
    }

    public function get_detail_topic($dataToken){
        $ForumID = $dataToken['data']['ID'];
        $UserID = $dataToken['data']['UserID'];
        $rs = $this->m_master->caribasedprimary('db_alumni.forum','ForumID',$ForumID);
        if (count($rs) > 0) {
           for ($i=0; $i < count($rs); $i++) { 
               $row = $rs[$i];

               if ($row['TypeUserID'] == 1) {
                   $G_dt = $this->m_master->caribasedprimary('db_academic.auth_students','NPM',$row['CreateBy']);
               }
               else
               {
                 $G_dt = $this->m_master->caribasedprimary('db_employees.employees','NIP',$row['CreateBy']);
               }

               $rs[$i]['NameCreateBy'] = $G_dt[0]['Name'];

               //  get user
               $G_user = $this->db->query('
                                 select a.*,b.Name,b.DivisionName,b.Photo,b.Year from db_alumni.forum_user as a
                                 join  (
                                         select a.NPM as UserID,a.Name, "Student" as DivisionName,ta_std.Photo,a.Year as Year from db_academic.auth_students as a
                                         join (
                                            '.$this->queryGetMHS().'
                                         ) ta_std on ta_std.NPM = a.NPM
                                         UNION
                                         select emp.NIP as UserID,emp.Name,divi.Division as DivisionName,emp.Photo,""
                                         from db_employees.employees as emp
                                         join db_employees.division as divi on SPLIT_STR(emp.PositionMain, ".", 1) = divi.ID
                                     ) as b on b.UserID = a.UserID
                                     where a.ForumID = '.$row['ForumID'].'
                                 ')->result_array();
               $rs[$i]['G_user'] = $G_user;

               // get Comment
               $G_comment =  $this->db->query('
                                 select a.*,b.Name,b.DivisionName,b.Photo,b.Year from db_alumni.forum_comment as a
                                 join  (
                                         select a.NPM as UserID,a.Name, "Student" as DivisionName,ta_std.Photo,a.Year from db_academic.auth_students as a
                                         join (
                                            '.$this->queryGetMHS().'
                                         ) ta_std on ta_std.NPM = a.NPM
                                         UNION
                                         select emp.NIP as UserID,emp.Name,divi.Division as DivisionName,emp.Photo,""
                                         from db_employees.employees as emp
                                         join db_employees.division as divi on SPLIT_STR(emp.PositionMain, ".", 1) = divi.ID
                                     ) as b on b.UserID = a.UserID
                                     where a.ForumID = '.$row['ForumID'].' and a.ParentCommentID is NULL
                             ')->result_array();
               $G_comment = $this->get_recursive_comment($G_comment);


               $rs[$i]['G_comment'] = $G_comment;

           }

           // update read comment
           $this->db->where('ForumID',$ForumID);
           $this->db->where('UserID',$UserID);
           $this->db->update('db_alumni.forum_user',[
            'ReadComment' => 1,
           ]);

            $this->callback['status'] = 1; 
            $this->callback['callback'] = $rs;
        }
        
         return $this->callback;
    }

    public function get_recursive_comment($data){
        for ($i=0; $i < count($data); $i++) { 
             $data[$i] = $this->recursive_comment($data[$i]);
        }

        return $data;
    }

    public function recursive_comment($row){
        $Forum_CommentID =  $row['Forum_CommentID'];
        $Q = $this->db->query('
                select a.*,b.Name,b.DivisionName,b.Photo,b.Year from db_alumni.forum_comment as a
                join  (
                        select a.NPM as UserID,a.Name, "Student" as DivisionName,ta_std.Photo,a.Year from db_academic.auth_students as a
                        join (
                           '.$this->queryGetMHS().'
                        ) ta_std on ta_std.NPM = a.NPM
                        UNION
                        select emp.NIP as UserID,emp.Name,divi.Division as DivisionName,emp.Photo,""
                        from db_employees.employees as emp
                        join db_employees.division as divi on SPLIT_STR(emp.PositionMain, ".", 1) = divi.ID
                    ) as b on b.UserID = a.UserID
                    where a.ParentCommentID = '.$Forum_CommentID.' 
                 ')->result_array();
        $row['comment_child'] = $this->get_recursive_comment($Q);
        return $row;
    }

    public function submit_comment_forum($dataToken){
        $tbl = 'db_alumni.forum_comment';
        $dataSave = $dataToken['data'];
        $this->db->db_debug=false;
        $query = $this->db->insert($tbl,$dataSave);
        if( !$query )
        {
           $this->callback['msg'] = json_encode($this->db->error());
        }
        else
        {
         $this->callback['status'] = 1; 
        }
        $this->db->db_debug=true;

        // send notif
        $ForumID = $dataSave['ForumID'];
        $tokenURL = $this->jwt->encode($ForumID,"UAP)(*");
        $UserIDCreateBy = $dataSave['UserID'];
        $GetDataCreateBy = $this->__UserEMP_NPM($UserIDCreateBy);
        $URLDirect = 'student-life/alumni/forum/detail/'.$tokenURL;
        $URLDirectAlumni = 'forum/detail-topic/'.$tokenURL;

        $data_forum_user = [];
        $data_forum_user[] = $UserIDCreateBy;

        $DepartmentID = 16; // kemahasiswaan
        $G_dt = $this->m_master->getEmployeeByDepartment($DepartmentID);
        for ($i=0; $i < count($G_dt); $i++) { 
            $data_forum_user[]= $G_dt[$i]['NIP'];
        }


        // update to unread
        $dataSaveUser = [
         'ReadComment' => 0,
        ];
        $this->db->where('ForumID',$ForumID);
        $this->db->update('db_alumni.forum_user',$dataSaveUser);


        $dataNotif = [
            'auth' => 's3Cr3T-G4N',
            'Logging' => array(
                            'Title' => '<i class="fa fa-check-circle margin-right" style="color:green;"></i> Comment forum alumni was created by '.$GetDataCreateBy[0]['Name'],
                            'Description' => substr($dataSave['Comment'], 0,20),
                            'URLDirect' => $URLDirect,
                            'URLDirectAlumni' => $URLDirectAlumni,
                            'CreatedBy' => $UserIDCreateBy,
                            'CreatedName' => $GetDataCreateBy[0]['Name'],
                          ),
            'To' => array(
                      'NIP' => $data_forum_user,
                    ),
            'Email' => 'No'
        ];

        $this->send_notif($dataNotif);
        return $this->callback;
    }

    public function Testimony($dataToken){
        $action = $dataToken['action'];
        switch ($action) {
            case 'read':
                $rs = ['data' => [], 'info' => [] ];
                $query_data = $this->db->query(
                    'select a.*,b.Name as NameNPM,c.Name as NameApproved,d.Name as NameUpdateBy 
                     from db_alumni.testimony as a
                     left join  (
                             select NPM as UserID,Name, "Student" as DivisionName from db_academic.auth_students
                             UNION
                             select emp.NIP as UserID,emp.Name,divi.Division as DivisionName
                             from db_employees.employees as emp
                             join db_employees.division as divi on SPLIT_STR(emp.PositionMain, ".", 1) = divi.ID
                         ) as b on a.NPM = b.UserID
                    left join  (
                            select NPM as UserID,Name, "Student" as DivisionName from db_academic.auth_students
                            UNION
                            select emp.NIP as UserID,emp.Name,divi.Division as DivisionName
                            from db_employees.employees as emp
                            join db_employees.division as divi on SPLIT_STR(emp.PositionMain, ".", 1) = divi.ID
                        ) as c on a.NIP_Approved = c.UserID
                    left join  (
                            select NPM as UserID,Name, "Student" as DivisionName from db_academic.auth_students
                            UNION
                            select emp.NIP as UserID,emp.Name,divi.Division as DivisionName
                            from db_employees.employees as emp
                            join db_employees.division as divi on SPLIT_STR(emp.PositionMain, ".", 1) = divi.ID
                        ) as d on a.UpdateBy = d.UserID
                        where a.NPM = "'.$dataToken['NPM'].'" limit 1
                    '
                )->result_array();

                $query_info = [];
                if (count($query_data) > 0) {
                   $ID_testimony = $query_data[0]['ID'];
                   $query_info = $this->db->query(
                       'select a.*,b.Name as CreateBy from db_alumni.testimony_info as a
                        left join (
                                select NPM as UserID,Name, "Student" as DivisionName from db_academic.auth_students
                                UNION
                                select emp.NIP as UserID,emp.Name,divi.Division as DivisionName
                                from db_employees.employees as emp
                                join db_employees.division as divi on SPLIT_STR(emp.PositionMain, ".", 1) = divi.ID
                            ) as b on a.CreateBy = b.UserID 

                       '
                   )->result_array();
                }
                

                $rs = ['data' => $query_data, 'info' => $query_info ];
                $this->callback['status'] = 1; 
                $this->callback['callback'] = $rs;
                break;
            case 'submit' : 
                $G_dt = $this->m_master->caribasedprimary('db_alumni.testimony','NPM',$dataToken['NPM']);
                $actionSet = (count($G_dt) > 0 ) ? 'Edit' : 'Create';
                $data = $dataToken['data'];
                $data['NPM'] = $dataToken['NPM'];
                $data['Status'] = 0;
                if ($actionSet == 'Edit') {
                    $this->db->where('NPM',$data['NPM']);
                    $this->db->update('db_alumni.testimony',$data);
                    $ID_testimony = $G_dt[0]['ID'];
                }
                else{
                    $this->db->insert('db_alumni.testimony',$data);
                    $ID_testimony = $this->db->insert_id();
                }

                // insert to info
                $this->__insert_info_testimony($ID_testimony,$actionSet,$data['NPM']);

                // send notification
                    $tokenURL = $this->jwt->encode($ID_testimony,"UAP)(*");
                    $URLDirect = 'student-life/alumni/testimony/detail/'.$tokenURL;
                    $URLDirectAlumni = 'portal/testimony';
                    $G_dt_User = $this->__UserEMP_NPM($data['UpdateBy']);
                    $data_to_user = [];

                    $DepartmentID = 16; // kemahasiswaan
                    $G_dt = $this->m_master->getEmployeeByDepartment($DepartmentID);
                    for ($i=0; $i < count($G_dt); $i++) { 
                        $data_to_user[]= $G_dt[$i]['NIP'];
                    }
                    $dataNotif = [
                        'auth' => 's3Cr3T-G4N',
                        'Logging' => array(
                                        'Title' => '<i class="fa fa-check-circle margin-right" style="color:green;"></i> Testimony was '.$actionSet.' by '.$G_dt_User[0]['Name'],
                                        'Description' => substr($data['Testimony'], 0,20),
                                        'URLDirect' => $URLDirect,
                                        'URLDirectAlumni' => $URLDirectAlumni,
                                        'CreatedBy' => $data['UpdateBy'],
                                        'CreatedName' => $G_dt_User[0]['Name'],
                                      ),
                        'To' => array(
                                  'NIP' => $data_to_user,
                                ),
                        'Email' => 'No'
                    ];

                    $this->send_notif($dataNotif);
                // end send notification

                $this->callback['status'] = 1; 
                $this->callback['callback'] = 1;
                break;
        }

        return $this->callback;
    }

    private function __insert_info_testimony($ID_testimony,$Info,$CreateBy){
        $dataSave = [
            'ID_testimony' => $ID_testimony,
            'Info' => $Info,
            'CreateBy' => $CreateBy,
            'CreateAt' => date('Y-m-d H:i:s'),
        ];

        $this->db->insert('db_alumni.testimony_info',$dataSave);
    }
  
}
