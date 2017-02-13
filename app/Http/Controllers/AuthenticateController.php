<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Exception;

use App\prescription\common\ResponsePrescription;
use App\prescription\utilities\ErrorEnum\ErrorEnum;

use Illuminate\Support\Facades\Auth;

class AuthenticateController extends Controller
{
    public function authenticateUser(Request $request)
    {
        $credentials = $request->only('email', 'password');
        //dd($credentials);

        try {
            // verify the credentials and create a token for the user

            //$token = JWTAuth::attempt($credentials);

            //$input = $request->all();

            //dd($request->email);

            //Auth::attempt(['email' => $request->email, 'password' => $request->password]);

            /*if (!Auth::attempt(['email' => $request->email, 'password' => $request->password]))
            {
                $userSession=Auth::user();
                //dd('OK');

                $token = JWTAuth::fromUser($userSession);

                return response()->json(compact('token'));
                //return Response::json(compact('token'));
            }
            else
            {
                dd('Inside else');
            }*/

            //$token = JWTAuth::attempt($input);
            //dd($token);
            //dd($input);
            /*if (!$token = JWTAuth::attempt($input)) {
                return response()->json(['result' => 'wrong email or password.']);
            }*/
            //return response()->json(['result' => $token]);

            //dd($token);

            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }

            $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS);
            $responseJson->setObj(compact('token'));
            $responseJson->sendSuccessResponse();

            return $responseJson;
            //return response()->json(compact('token'));

        }
        catch (JWTException $e) {
            //dd($e);
            // something went wrong
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        catch(Exception $ex)
        {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // if no errors are encountered we can return a JWT
        //return response()->json(compact('token'));
        //return $responseJson;
    }

    public function generateToken()
    {
        return csrf_token();
    }
}
