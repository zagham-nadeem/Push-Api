<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


require __DIR__ . '/../vendor/autoload.php';

require_once '../includes/dboperation.php';
$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true
    ]
]);

$app->post('/login',function (Request $request,Response $response)
{
      $requestData = json_decode($request->getBody());
        $user_id=$requestData->user_id;
        $token=$requestData->token;
        $db = new DbOperation();
        $responseData = array();
        if ($db->userlogin($user_id)){
        $responseData['error'] = false;
        $responseData['profileData'] = $db->updatetoken($user_id , $token );
         $responseData['user'] = 'User Already exits';
    } else {
        $responseData['error'] = true;
        $responseData['message'] = 'User not exit';
    }
    $response->getBody()->write(json_encode($responseData));
});


$app->post('/profileupdate', function (Request $request, Response $response) {
    $requestData = json_decode($request->getBody());
    $user_id =$requestData->user_id ;
    $full_name=$requestData->full_name;
    $address=$requestData->address;
    $cnic=$requestData->cnic;
    $phone=$requestData->phone;
    $image=$requestData->image;
    $token=$requestData->token;
    $db = new DbOperation();
    $result=$db->profileupdate($user_id,$full_name,$address, $cnic,$phone ,$image,$token);
    $responseData=array();

    if ($result == PROFILE_UPDATED) {
        $responseData['error'] = false;
        $responseData['message'] = 'your profile updated';
        $responseData['profileData'] = $db->getprofilebyid1($user_id);
    } elseif ($result == PROFILE_NOT_UPDATED) {
        $responseData['error'] = true;
        $responseData['message'] = 'profile not updated';
    }
$response->getBody()->write(json_encode($responseData));
});

$app->post('/add_draw',function (Request $request,Response $response)
{
      $requestData = json_decode($request->getBody());
        $name=$requestData->name;
        $description=$requestData->description;
        $price=$requestData->price;
        $image=$requestData->image;
        $start_date=$requestData->start_date;
        $to_date=$requestData->to_date;
        $db = new DbOperation();
        $responseData = array();
        if ($db->add_draw($name, $description, $price, $image, $start_date , $to_date)){
        $responseData['error'] = false;
        $responseData['message'] = 'data inserted sucessfully';
    } else {
        $responseData['error'] = true;
        $responseData['message'] = 'data is not inserted';
    }
    $response->getBody()->write(json_encode($responseData));
});


$app->post('/delete_draw',function (Request $request,Response $response)
{
      $requestData = json_decode($request->getBody());
        $d_id=$requestData->d_id;
        $db = new DbOperation();
        $responseData = array();
        if ($db->delete_draw($d_id)){
        $responseData['error'] = false;
        $responseData['message'] = 'data deleted sucessfully';
    } else {
        $responseData['error'] = true;
        $responseData['message'] = 'data is not deleted';
    }
    $response->getBody()->write(json_encode($responseData));
});

$app->post('/update_draw',function (Request $request,Response $response)
{
      $requestData = json_decode($request->getBody());
        $d_id=$requestData->d_id;
        $name=$requestData->name;
        $description=$requestData->description;
        $price=$requestData->price;
        $image=$requestData->image;
        $start_date=$requestData->start_date;
        $to_date=$requestData->to_date;
        $db = new DbOperation();
        $responseData = array();
        if ($db->update_draw($d_id,$name,$description,$price,$image,$start_date,$to_date)){
        $responseData['error'] = false;
        $responseData['message'] = 'data deleted sucessfully';
    } else {
        $responseData['error'] = true;
        $responseData['message'] = 'data is not deleted';
    }
    $response->getBody()->write(json_encode($responseData));
});

$app->post('/add_participation',function (Request $request,Response $response)
{
      $requestData = json_decode($request->getBody());

        $d_id=$requestData->d_id;
        $user_id=$requestData->user_id;
        $ammount=$requestData->ammount;
        $date = $requestData->date;
        $db = new DbOperation();
        $responseData = array();
        if ($db->add_participation($d_id, $user_id, $ammount, $date)){
        $responseData['error'] = false;
        $responseData['message'] = 'data inserted sucessfully';
    } else {
        $responseData['error'] = true;
        $responseData['message'] = 'data is not inserted';
    }
    $response->getBody()->write(json_encode($responseData));
});

    // Add Customer
    $app->post('/addCustomer',function (Request $request,Response $response)
{
    $requestData = json_decode($request->getBody());
    $customerID=$requestData->customerID;
    $CustomerName=$requestData->CustomerName;
    $ContactName=$requestData->ContactName;
    $Address=$requestData->Address;
    $City=$requestData->City;
    $PostalCode=$requestData->PostalCode;
    $Country=$requestData->Country;
    $db = new DbOperation();
    $responseData = array();
    if ($db->updateCustomers($customerID, $CustomerName, $ContactName, $Address, $City, $PostalCode , $Country)){
        $responseData['error'] = false;
        $responseData['message'] = 'data inserted successfully';
    } else {
        $responseData['error'] = true;
        $responseData['message'] = 'data is not inserted';
    }
    $response->getBody()->write(json_encode($responseData));
});
    // Get Total Customers Get Method 
    $app->get('/getStaff' ,function (Request $request, Response $response)
    {
        $db = new DbOperation();
        $result=$db->customers();
        $response->getBody()->write(json_encode($result));
    });

    // Get Total Customers Get Method
    $app->get('/totalCustomers' ,function (Request $request, Response $response)
   {
       $customerID = $request->getAttribute('customerID');
   	 $db = new DbOperation();
   	 $result=$db->totalCustomers($customerID);
   	 $response->getBody()->write(json_encode($result));
   });
    // Update Customer
    $app->post('/updateCustomer',function (Request $request,Response $response)
    {
        $requestData = json_decode($request->getBody());
        $customerID=$requestData->customerID;
        $CustomerName=$requestData->CustomerName;
        $ContactName=$requestData->ContactName;
        $Address=$requestData->Address;
        $City=$requestData->City;
        $PostalCode=$requestData->PostalCode;
        $Country=$requestData->Country;
        $db = new DbOperation();
        $responseData = array();
        if ($db->updateCustomers($customerID,$CustomerName,$ContactName,$Address,$City,$PostalCode,$Country)){
            $responseData['error'] = false;
            $responseData['message'] = 'data updated successfully';
        } else {
            $responseData['error'] = true;
            $responseData['message'] = 'data is not updated';
        }
        $response->getBody()->write(json_encode($responseData));
    });
    // Delete Customer
    $app->post('/deleteCustomer',function (Request $request,Response $response)
    {
        $requestData = json_decode($request->getBody());
        $customerID=$requestData->customerID;
        $db = new DbOperation();
        $responseData = array();
        if ($db->deleteCustomers($customerID)){
            $responseData['error'] = false;
            $responseData['message'] = 'data deleted successfully';
        } else {
            $responseData['error'] = true;
            $responseData['message'] = 'data is not deleted';
        }
        $response->getBody()->write(json_encode($responseData));
    });

    // Get Total Customers Get Method

//
$app->post('/getAbsentee',function (Request $request,Response $response)
{
    $requestData = json_decode($request->getBody());
    $monthName=$requestData->monthName;
    $s_id=$requestData->s_id;
    $db = new DbOperation();
    $responseData = array();
    if ($db->absentee($monthName, $s_id)){
        $responseData['data'] = $db->absentee($monthName, $s_id);
    } else {
        $responseData['error'] = true;
        $responseData['message'] = 'Month is Empty';
    }
    $response->getBody()->write(json_encode($responseData));
});
    // Add Signup
    $app->post('/addSignup',function (Request $request,Response $response)
    {
        $requestData = json_decode($request->getBody());
        $first_name=$requestData->first_name;
        $last_name=$requestData->last_name;
        $email=$requestData->email;
        $password=$requestData->password;
        $db = new DbOperation();
        $responseData = array();
        
        if ($db->getALreadySignup($email)){
            $responseData['error'] = false;
            $responseData['message'] = 'User Already Exists with this Email';
        } else {
            if ($db->addSignup( $first_name, $last_name, $email, $password)){
                $responseData['error'] = false;
                $responseData['message'] = 'data inserted successfully';
            } else {
                $responseData['error'] = true;
                $responseData['message'] = 'data is not inserted';
            }
        }

        $response->getBody()->write(json_encode($responseData));
    });
    // get SIgnup
    $app->post('/getsignup',function (Request $request,Response $response)
    {
        $requestData = json_decode($request->getBody());
        $email=$requestData->email;
        $password=$requestData->password;
        $db = new DbOperation();
        $responseData = array();
        if ($db->getsignup($email, $password)){
            $responseData['error'] = false;
            $responseData['user'] = $db->getsignup($email, $password);
        } else {
            $responseData['error'] = true;
            $responseData['message'] = 'data is not deleted';
        }
        $response->getBody()->write(json_encode($responseData));
    });
    // Update Customer
    $app->post('/updateSignup',function (Request $request,Response $response)
    {
        $requestData = json_decode($request->getBody());
        $id=$requestData->id;
        $first_name=$requestData->first_name;
        $last_name=$requestData->last_name;
        $email=$requestData->email;
        $password=$requestData->password;
        $db = new DbOperation();
        $responseData = array();
        if ($db->updateSignup($id,$first_name,$last_name,$email,$password)){
            $responseData['error'] = false;
            $responseData['message'] = 'data updated successfully';
        } else {
            $responseData['error'] = true;
            $responseData['message'] = 'data is not updated';
        }
        $response->getBody()->write(json_encode($responseData));
    });
    // Delete Signup
    $app->post('/deleteSignup',function (Request $request,Response $response)
    {
        $requestData = json_decode($request->getBody());
        $id=$requestData->id;
        $db = new DbOperation();
        $responseData = array();
        if ($db->deleteSignup($id)){
            $responseData['error'] = false;
            $responseData['message'] = 'data deleted successfully';
        } else {
            $responseData['error'] = true;
            $responseData['message'] = 'data is not deleted';
        }
        $response->getBody()->write(json_encode($responseData));
    });

$app->run();
?>
