<?php

use phpDocumentor\Reflection\DocBlock\Description;

class DbOperation
{
    private $con;
    function __construct()
    {
        require_once dirname(__FILE__) . '/dbconnect.php';
        $db = new DbConnect();
        $this->con = $db->connect();
    }
    function userlogin($user_id)
    {

        $stmt = $this->con->prepare("SELECT `user_id` FROM `profile` WHERE `user_id` = ?");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
         $stmt->store_result();
         return $stmt->num_rows > 0;
    }

function updatetoken($user_id,$token)
    {
        $stmt=$this->con->prepare("UPDATE `profile` SET `other`= ? WHERE `user_id`= ?");
        $stmt->bind_param("ss",$token,$user_id);
        if ($stmt->execute())
        {
            return PROFILE_UPDATED;
        }
        return PROFILE_NOT_UPDATED;
    }

function profileupdate($user_id,$full_name,$address, $cnic,$phone ,$image, $token)
    {
        if(isset($image)) {
         date_default_timezone_set("Asia/Karachi");
                $time=date("ymd");
                $id=$time.'_'.microtime(true);
                 $upload_path="../images/$id.jpg";
                  $upload = substr($upload_path, 3);
        }
        else
         $upload_path=null;
        $stmt=$this->con->prepare("UPDATE `profile` SET `full_name`=?,`address`=? ,`cnic`=?,`phone`=?,`image`= ? , `other`= ? WHERE `user_id`= ?");
        $stmt->bind_param("ssiisss",$full_name,$address, $cnic,$phone ,$upload, $token ,$user_id);
        if ($stmt->execute())
        {
            file_put_contents($upload_path,base64_decode($image));
            return PROFILE_UPDATED;
        }
        return PROFILE_NOT_UPDATED;
    }


function getprofilebyid1($user_id)
    {
       $stmt = $this->con->prepare("SELECT * FROM `profile` WHERE `user_id` = ? ;");
              $stmt->bind_param("s", $user_id);
              $stmt->execute();
              $stmt->bind_result($user_id,$full_name,$address, $cnic,$phone ,$image, $other);
              $stmt->fetch();
              if($image==null)
              $imgurl =  'http://' .'localhost'.'/draw/' . $image;
              else
              $imgurl =  'http://' .'localhost'.'/draw/' . $image;
              $profile = array();
              $profile['user_id']=$user_id;
              $profile['full_name'] = $full_name;
              $profile['address'] = $address;
              $profile['cnic']=$cnic;
              $profile['phone']=$phone;
              $profile['cnic']=$cnic;
              $profile['image']=$imgurl;
              $profile['token']=$other;
              return $profile;
    }

    function add_draw($name, $description, $price, $image, $start_date , $to_date)
    {
    date_default_timezone_set("America/Los_Angeles");
               $time = date("ymd");
               $id = '_' . $time . '_' . microtime(true);
               $upload_path = "../images/$id.jpg";
               $upload = substr($upload_path, 3);
        $stmt=$this->con->prepare("INSERT INTO `draw`(`name`, `description`, `price`, `image`, `start_date`, `to_date`) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("ssssss", $name, $description, $price, $upload, $start_date , $to_date);
        if ($stmt->execute())
        {
        file_put_contents($upload_path, base64_decode($image));
            return PROFILE_CREATED;
        }
        return PROFILE_NOT_CREATED;
    }

    function delete_draw($d_id)
    {
        $stmt=$this->con->prepare("DELETE FROM `draw` Where d_id = ? ");
        $stmt->bind_param("i",$d_id);
        if ($stmt->execute())
        {
            return PROFILE_DELETED;
        }
        return PROFILE_NOT_DELETED;
    }


function update_draw($d_id,$name,$description,$price,$image,$start_date,$to_date)
    {
        if(isset($image)) {
         date_default_timezone_set("Asia/Karachi");
                $time=date("ymd");
                $id=$time.'_'.microtime(true);
                 $upload_path="../images/$id.jpg";
                  $upload = substr($upload_path, 3);
        }
        else
         $upload_path=null;
        $stmt=$this->con->prepare("UPDATE `profile` SET `name`=?,`description`=? ,`price`=?,`image`=?,`start_date`= ? , `to_date`= ? WHERE `d_id`= ?");
        $stmt->bind_param("ssssssi",$name,$description,$price,$image,$start_date,$to_date,$d_id);
        if ($stmt->execute())
        {
            file_put_contents($upload_path,base64_decode($image));
            return PROFILE_UPDATED;
        }
        return PROFILE_NOT_UPDATED;
    }

function add_participation($d_id, $user_id, $ammount, $date)
    {
          date_default_timezone_set("Asia/Karachi");
          $time = date("ymd");
        $stmt=$this->con->prepare("INSERT INTO `participation`(`d_id`, `user_id`, `ammount` , `date`) VALUES (?,?,?,?)");
        $stmt->bind_param("iiis", $d_id, $user_id, $ammount, $date);
        if ($stmt->execute())
        {
        file_put_contents($upload_path, base64_decode($image));
            return PROFILE_CREATED;
        }
        return PROFILE_NOT_CREATED;
    }

    // Add Customers Post Method
    function addCustomers($CustomerName, $ContactName, $Address, $City, $PostalCode , $Country)
    {

        $stmt=$this->con->prepare("INSERT INTO `customers`(`CustomerName`, `ContactName`, `Address`, `City`, `PostalCode`, `Country`) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("ssssss", $CustomerName, $ContactName, $Address, $City, $PostalCode , $Country);
        if ($stmt->execute())
        {
            return PROFILE_CREATED;
        }
        return PROFILE_NOT_CREATED;
    }
    // Get Cutomers Get Method
    function customers()
    {
        $stmt = $this->con->prepare ("SELECT *  FROM `staff` ");
        $stmt->execute();
        $stmt->bind_result($id,$name,$no,$age, $gender,$catagory ,$shedule, $salary);
        $cat = array();
            while ($stmt->fetch()) {
            $staff = array();
            $staff['id'] = $id ;
            $staff['name'] = $name ;
            $staff['no'] = $no ;
            $staff['age'] = $age ;
            $staff['gender'] = $gender ;
            $staff['catagory'] = $catagory ;
            $staff['shedule'] = $shedule ;
            $staff['salary'] = $salary ;
            array_push($cat,$staff);
            }
            return $cat;

    }
    // Update Customer
    function updateCustomers($customerID, $CustomerName, $ContactName, $Address, $City, $PostalCode , $Country)
    {

        $stmt=$this->con->prepare("UPDATE `customers` SET `CustomerName`=?,`ContactName`=?,`Address`=?,`City`=?,`PostalCode`=?,`Country`=? WHERE customerID = ?");
        $stmt->bind_param("ssssssi",  $CustomerName, $ContactName, $Address, $City, $PostalCode , $Country, $customerID);
        if ($stmt->execute())
        {
            return PROFILE_CREATED;
        }
        return PROFILE_NOT_CREATED;
    }
    // Delete Customer
    function deleteCustomers($customerID)
    {

        $stmt=$this->con->prepare("DELETE FROM `customers` WHERE customerID = ?");
        $stmt->bind_param("i",$customerID);
        if ($stmt->execute())
        {
            return PROFILE_CREATED;
        }
        return PROFILE_NOT_CREATED;
    }
    // Get Total Cutomers Get Method
    function totalCustomers($customerID)
    {
    $stmt = $this->con->prepare ("SELECT COUNT(customerID) as total FROM `customers` ");
    $stmt->execute();
    $stmt->bind_result($total);

      $cat = array();
             while ($stmt->fetch()) {
                 $customer = array();
                 $customer['total_customers'] = $total ;

     array_push($cat, $customer);
    }
    return $cat;
    }

    // Get Total Absentee Get Method Get Cutomers Get Method
    function absentee($monthName, $s_id)
    {

        $stmt = $this->con->prepare ("SELECT staff_attendence.id, (SELECT COUNT(staff_attendence.id) FROM staff_attendence WHERE staff_attendence.attendence = 'A' && staff.id = staff_attendence.id) AS absents, (SELECT COUNT(staff_attendence.id) FROM staff_attendence WHERE staff_attendence.attendence = 'P' && staff.id = staff_attendence.id) AS presents, (SELECT COUNT(staff_attendence.id) FROM staff_attendence WHERE staff_attendence.attendence = 'L' && staff.id = staff_attendence.id) AS leaves, MONTHNAME(staff_attendence.date) as month, staff.name, staff.salary/30 as salary , staff.salary AS totalSalary, (SELECT COUNT(DISTINCT(staff_attendence.date)) FROM staff_attendence) as workingDays, staff_attendence.date as date, (SELECT COUNT(staff_attendence.replacement_id) FROM staff_attendence WHERE staff_attendence.replacement_id = staff.id) as totalReplacements FROM staff_attendence JOIN staff ON staff.id = staff_attendence.id WHERE MONTHNAME(staff_attendence.date) = ? && staff.id = ?  GROUP BY staff.name ORDER BY staff.name;");
        $stmt->execute();
        $stmt-> bind_param('si', $monthName, $s_id);
        $stmt->bind_result($id,$absents,$presents,$leaves,$month,$name,$salary,$totalSalary, $workingDays, $date, $totalReplacements);
        if ($stmt->execute()) {
            $cat = array();
            while ($stmt->fetch()) {
                $absentee = array();
                $absentee['id'] = $id;
                $absentee['absents'] = $absents;
                $absentee['presents'] = $presents;
                $absentee['leave'] = $leaves;
                $absentee['name'] = $name;
                $absentee['absents_deduction'] = $salary * $absents;
                $absentee['month'] = $month;
                $absentee['salary-package'] = $totalSalary;
                $absentee['payable-salary'] = ($presents * $salary) - ($salary * $absents) + ($salary * $totalReplacements);
                $absentee['working-days'] = $workingDays;
                $absentee['date'] = $date;
                $absentee['total-replacements'] = $totalReplacements;
                $absentee['replacements-bonus'] = ($salary * $totalReplacements);

                array_push($cat, $absentee);
            }
            return $cat;
        }

    }

    // Add Signup
    function addSignup($first_name, $last_name, $email, $password)
    {

        $stmt=$this->con->prepare("INSERT INTO `signup`(`first_name`, `last_name`, `email`, `password`) VALUES (?,?,?,?)");
        $stmt->bind_param("ssss", $first_name, $last_name, $email, $password);
        if ($stmt->execute())
        {
            return PROFILE_CREATED;
        }
        return PROFILE_NOT_CREATED;
    }
    // Get Signup
    function getsignup($email, $password)
    {
        $stmt = $this->con->prepare ("SELECT * FROM signup WHERE signup.email = ? && signup.password = ?;");
        $stmt->execute();
        $stmt-> bind_param("ss", $email, $password );
        $stmt->bind_result($id, $first_name, $last_name, $e, $p);
        if ($stmt->execute()) {
            $cat = array();
            while ($stmt->fetch()) {
                $user = array();
                $user['id'] = $id;
                $user['first_name'] = $first_name;
                $user['last_name'] = $last_name;
                $user['email'] = $email;
                $user['password'] = $password;
                array_push($cat, $user);
            }
            return $cat;
        }
    }
    // Get Already Exists Signup
    function getALreadySignup($email)
    {
        $stmt = $this->con->prepare ("SELECT * FROM signup WHERE signup.email = ?;");
        $stmt->execute();
        $stmt-> bind_param("s", $email);
        $stmt->bind_result($id, $first_name, $last_name, $email, $password);
        if ($stmt->execute()) {
            $cat = array();
            while ($stmt->fetch()) {
                $user = array();
                $user['id'] = $id;
                $user['first_name'] = $first_name;
                $user['last_name'] = $last_name;
                $user['email'] = $email;
                $user['password'] = $password;
                array_push($cat, $user);
            }
            return $cat;
        }
    }
    // Update Customer
    function updateSignup($id, $first_name, $last_name, $email, $password)
    {

        $stmt=$this->con->prepare("UPDATE `signup` SET `first_name`=?,`last_name`=?,`email`=?,`password`=? WHERE id = ?");
        $stmt->bind_param("ssssi",  $first_name, $last_name, $email, $password, $id);
        if ($stmt->execute())
        {
            return PROFILE_UPDATED;
        }
        return PROFILE_NOT_UPDATED;
    }
    // Delete Signup
    function deleteSignup($id)
    {

        $stmt=$this->con->prepare("DELETE FROM `signup` WHERE id = ?");
        $stmt->bind_param("i",$id);
        if ($stmt->execute())
        {
            return PROFILE_DELETED;
        }
        return PROFILE_NOT_DELETED;
    }



}
